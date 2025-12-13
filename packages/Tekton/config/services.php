<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator) {

    $services = $configurator->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $configurator->import('./packages/tekton.php');
    $configurator->import('./packages/messenger.php');
    $configurator->import('./packages/route.php');
    $configurator->import('./packages/event.php');

    $services->load('Fortizan\\Tekton\\', '../src')
        ->exclude([
            '../src/Container/Container.php',
            '../src/Http/kernel.php',
            '../src/EventListener',
        ]);
};
