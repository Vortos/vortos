<?php

declare(strict_types=1);

namespace Fortizan\Tekton\Messaging\Command;

use Fortizan\Tekton\Messaging\Contract\OutboxPollerInterface;
use Fortizan\Tekton\Messaging\Contract\ProducerInterface;
use Fortizan\Tekton\Messaging\Serializer\SerializerLocator;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Replays permanently failed outbox messages by re-producing them to their
 * original transport. Use --dry-run to inspect failed messages first.
 *
 * A message is eligible for replay when its status is 'failed' — meaning
 * it has exhausted all automatic retry attempts. Replaying re-produces the
 * message and marks it as published on success.
 */
#[AsCommand(
    name: 'tekton:dlq:replay',
    description: 'Replay failed outbox messages back into the relay pipeline'
)]
final class ReplayDeadLetterCommand extends Command
{
    public function __construct(
        private OutboxPollerInterface $poller,
        private ProducerInterface $producer,
        private SerializerLocator $serializerLocator,
        private LoggerInterface $logger
    ) {
        parent::__construct();
    }

    public function configure(): void
    {
        $this->addOption('limit', 'l', InputOption::VALUE_OPTIONAL, 'Max messages to replay', 50)
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'List messages that would be replayed without replaying them');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $limit  = (int) $input->getOption('limit');
        $dryRun = (bool) $input->getOption('dry-run');

        $messages = $this->poller->fetchFailed($limit);

        if (empty($messages)) {
            $output->writeln('<info>No failed messages found.</info>');
            return Command::SUCCESS;
        }

        $output->writeln(sprintf('<info>Found %d failed message(s).</info>', count($messages)));
        $output->writeln('');

        if ($dryRun) {
            $output->writeln('<comment>[DRY RUN] The following messages would be replayed:</comment>');
            $output->writeln('');
            foreach ($messages as $message) {
                $output->writeln(sprintf(
                    '  • %s  |  %s  →  %s',
                    $message->id,
                    $message->eventClass,
                    $message->transportName
                ));
            }
            $output->writeln('');
            $output->writeln('<comment>Dry run complete. No messages replayed.</comment>');
            return Command::SUCCESS;
        }

        $replayed = 0;
        $failed   = 0;

        foreach ($messages as $message) {
            try {
                $serializer = $this->serializerLocator->locate('json');
                $event      = $serializer->deserialize($message->payload, $message->eventClass);

                $this->producer->produce($message->transportName, $event, $message->headers);
                $this->poller->markPublished($message->id);

                $output->writeln(sprintf(
                    '  <info>✔ Replayed:</info> %s  |  %s  →  %s',
                    $message->id,
                    $message->eventClass,
                    $message->transportName
                ));
                $replayed++;
            } catch (\Throwable $e) {
                $this->logger->error('DLQ replay failed', [
                    'id'         => $message->id,
                    'event_class' => $message->eventClass,
                    'transport'  => $message->transportName,
                    'error'      => $e->getMessage(),
                ]);
                $output->writeln(sprintf(
                    '  <error>✘ Failed:</error>   %s  |  %s — %s',
                    $message->id,
                    $message->eventClass,
                    $e->getMessage()
                ));
                $failed++;
            }
        }

        $output->writeln('');
        $output->writeln(sprintf(
            '<info>Done.</info> Replayed: <info>%d</info>  Failed: %s',
            $replayed,
            $failed > 0 ? sprintf('<error>%d</error>', $failed) : '<info>0</info>'
        ));

        return $failed === 0 ? Command::SUCCESS : Command::FAILURE;
    }
}
