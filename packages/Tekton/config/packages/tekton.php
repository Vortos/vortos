<?php

use Fortizan\Tekton\Http\Kernel;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ContainerControllerResolver;

return static function (ContainerConfigurator $configurator) {

    $services = $configurator->services()
        ->defaults()
        ->autoconfigure()
        ->autowire();

    $services->set('tekton', Kernel::class)
        ->public()
        ->args([
            new Reference(EventDispatcher::class),
            new Reference(ContainerControllerResolver::class),
            new Reference(RequestStack::class),
            new Reference(ArgumentResolver::class),
        ]);
};
