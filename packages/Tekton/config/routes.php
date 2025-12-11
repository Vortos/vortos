<?php

use Fortizan\Tekton\Routing\RouteAttributeClassLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Routing\Loader\AttributeDirectoryLoader;
use Symfony\Component\Routing\RouteCollection;

return function (ContainerBuilder $container) {

    $fileLocator = new FileLocator($container->getParameter('kernel.project_dir'));
    $classLoader = new RouteAttributeClassLoader();

    $loader = new AttributeDirectoryLoader($fileLocator, $classLoader);

    $controllerIds = $container->findTaggedServiceIds('tekton.api.controller');

    $routes = new RouteCollection();

    foreach ($controllerIds as $id => $t) {

        $pathParts = explode('\\', $container->getDefinition($id)->getClass());
        array_shift($pathParts);
        array_pop($pathParts);
        $path = implode('/', $pathParts);

        $routes->addCollection($loader->load($path));
    }

    return $routes;
};
