<?php

namespace Fortizan\Tekton\DependencyInjection\Compiler\Bus;

use Exception;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Messenger\Transport\TransportInterface;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service_locator;

class EventAttributeCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition('messenger.sender_locator')) {
            return;
        }

        $senderLocatorDefinition = $container->getDefinition('messenger.sender_locator');

        $eventIds = $container->findTaggedServiceIds('tekton.event');

        // building the event map with the transports
        $eventMap = [];
        $transports = [];
        foreach ($eventIds as $id => $tags) {

            $eventTransports = explode(',', $tags[0]['transport']);

            $topic =  $tags[0]['topic'];
            $transportIds = [];
            foreach ($eventTransports as $eventTransport) {

                $transportDsnEnvName = "MESSENGER_TRANSPORT_" . strtoupper($eventTransport) . "_DSN";

                $transportId = 'tekton.transport.' . $eventTransport . '.' . $topic;
                $transportIds[] = $transportId;

                $transports[$transportId] = [$transportDsnEnvName, $topic];
            }
            $eventMap[$id] =  $transportIds;
        }

        // build transports map assuming convention is followed
        $transportMap = [];
        foreach ($transports as $transportId => [$dsnEnvName, $topic]) {

            if (!$container->hasDefinition($transportId)) {
                $container->register($transportId, TransportInterface::class)
                    ->setFactory([new Reference('tekton.transport.factory'), 'createTransport'])
                    ->setArguments([
                        $dsnEnvName,
                        [
                            'topic' => [
                                'name' => $topic
                            ],
                            // 'kafka_conf' => [
                            //     'group.id' => '%messenger.consumer.async.group_id%',
                            //     'auto.offset.reset' => 'earliest'
                            // ]
                        ],
                        new Reference('tekton.messenger.serializer')
                    ]);
            }

            $transportMap[$transportId] = new Reference($transportId);
        }

        $senderLocatorDefinition->setArguments([
            $eventMap,
            service_locator($transportMap)
        ]);
    }
}
