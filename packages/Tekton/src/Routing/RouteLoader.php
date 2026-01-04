<?php

namespace Fortizan\Tekton\Routing;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Routing\RouteCollection;

class RouteLoader
{
    public function __construct(
        private string $routeConfigPath,
        private Container $container
    ) {}

    public function load(): RouteCollection
    {
        $routeLoader = include $this->routeConfigPath;
        return $routeLoader($this->container);
    }
}
