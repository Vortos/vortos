<?php

namespace Fortizan\Tekton\DependencyInjection\Compiler\Http;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\EventDispatcher\EventDispatcher;

class RegisterEventSubscribersPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void 
    {
        if(!$container->has(EventDispatcher::class)){
            return;
        }

        $dispatcherDefinision = $container->getDefinition(EventDispatcher::class);

        $eventSubscribers = $container->findTaggedServiceIds('kernel.event_subscriber');

        foreach($eventSubscribers as $id => $metadata){
            $dispatcherDefinision->addMethodCall('addSubscriber', [new Reference($id)]);
        }
    }
}
