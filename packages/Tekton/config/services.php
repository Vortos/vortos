<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator) {

    $services = $configurator->services()
        ->defaults()
        ->autowire()     
        ->autoconfigure();

    $configurator->import('./packages/messenger.php');

    $services->load('Fortizan\\Tekton\\', '../src')
        ->exclude('{Container}');

    $services->load('App\\', '../../../src/')
        ->exclude([
            '../../../src/Context/Representation/View/',
            '../../../src/Entity/'
        ]);

    $services->load('App\\Context\\Representation\\Controller\\', '../../../src/Context/Representation/Controller/')
        ->public();
 
};