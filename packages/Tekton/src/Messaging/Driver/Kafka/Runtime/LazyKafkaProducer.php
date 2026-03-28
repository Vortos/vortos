<?php

declare(strict_types=1);

namespace Fortizan\Tekton\Messaging\Driver\Kafka\Runtime;

use Fortizan\Tekton\Messaging\Contract\DomainEventInterface;
use Fortizan\Tekton\Messaging\Contract\ProducerInterface;
use Fortizan\Tekton\Messaging\Driver\Kafka\Factory\KafkaProducerFactory;

final class LazyKafkaProducer implements ProducerInterface
{
    private array $producers = [];

    public function __construct(
        private KafkaProducerFactory $factory
    ) {}

    public function produce(string $transportName, DomainEventInterface $event, array $headers = []): void
    {
        $this->get($transportName)->produce($transportName, $event, $headers);
    }

    public function produceBatch(string $transportName, array $events, array $headers = []): void
    {
        $this->get($transportName)->produceBatch($transportName, $events, $headers);
    }

    private function get(string $transportName): KafkaProducer
    {
        if (!isset($this->producers[$transportName])) {
            $this->producers[$transportName] = $this->factory->create($transportName);
        }
        return $this->producers[$transportName];
    }
}
