<?php

namespace Fortizan\Tekton\DependencyInjection\Compiler\Messenger;

use Fortizan\Tekton\Bus\Event\Attribute\AsEvent;
use Fortizan\Tekton\Messenger\Consumer;
use ReflectionClass;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Messenger\Transport\TransportInterface;

class ConsumerTransportPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition(Consumer::class)) {
            return;
        }

        $consumerDefinition = $container->getDefinition(Consumer::class);
        $handlersMap = $consumerDefinition->getArgument('$globalHandlerMap');
        
        $groupsToTopicsMap = [];
        foreach ($handlersMap as $groupId => $groupData) {

            $currentGroupTopics = [];
            foreach ($groupData as $eventClass => $handlerData) {
                $reflectionEventClass = new ReflectionClass($eventClass);
                $asEventAttributes = $reflectionEventClass->getAttributes(AsEvent::class);

                if (empty($asEventAttributes)) {
                    throw new \RuntimeException(sprintf(
                        'The event class "%s" is missing the #[AsEvent] attribute. Every handled event must define its topic.',
                        $eventClass
                    ));
                }

                $attributeArgs = $asEventAttributes[0]->getArguments();

                if (empty($attributeArgs['topic'])) {
                    throw new \RuntimeException(sprintf(
                        'The #[AsEvent] attribute on class "%s" is missing the "topic" argument.',
                        $eventClass
                    ));
                }
                
                $topic = $attributeArgs['topic'];
                $currentGroupTopics[] = $topic;
            }

            $groupsToTopicsMap[$groupId] = array_unique($currentGroupTopics);
        }

        foreach ($groupsToTopicsMap as $groupId => $topics) {
            $consumerTransport = "MESSENGER_TRANSPORT_" . strtoupper(str_replace(['-', ' '], '_', $groupId)) . "_CONSUMER_DSN";
            $consumerTransportId = "tekton.transport." . strtolower(str_replace(['-', ' '], '_', $groupId)) . ".consumer";

            $container->register($consumerTransportId, TransportInterface::class)
                ->setFactory([new Reference('tekton.transport.factory'), 'createTransport'])
                ->setArguments([
                    $consumerTransport,
                    [
                        'topic' =>  $topics,
                        'kafka_conf' => [
                            'group.id' => $groupId,
                            'auto.offset.reset' => 'earliest'
                        ]
                    ],
                    new Reference('tekton.messenger.serializer')
                ])->setPublic(true);
        }
    }
}
