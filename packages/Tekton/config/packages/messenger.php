<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\MessageBusInterface;

return static function (ContainerConfigurator $configurator) {

    $services = $configurator->services();

    $services->alias(MessageBusInterface::class . ' $messageBus', 'messenger.bus.default');

    $services->set('messenger.bus.default', MessageBus::class )
            ->args([[new Reference('messenger.middleware.handle_message')]])
            ->tag('messenger.bus');
            
};
