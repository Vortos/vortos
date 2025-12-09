<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;

return static function (ContainerConfigurator $configurator) {

    $services = $configurator->services();

    $services->alias(MessageBusInterface::class . ' $messageBus', 'messenger.bus.default');

    $services->set('messenger.bus.default', MessageBus::class )
            ->args([[new Reference('messenger.middleware.handle_message')]])
            ->tag('messenger.bus');

    $services->set('messenger.middleware.handle_message', HandleMessageMiddleware::class)
            ->args([new Reference('messenger.bus.default.messenger.handlers_locator')]);

    $services->set('messenger.bus.default.messenger.handlers_locator', HandlersLocator::class)
            ->args([[]]);
};
