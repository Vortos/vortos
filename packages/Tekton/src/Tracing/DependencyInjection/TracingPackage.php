<?php

declare(strict_types=1);

namespace Fortizan\Tekton\Tracing\DependencyInjection;

use Fortizan\Tekton\Container\Contract\PackageInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

final class TracingPackage implements PackageInterface
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new TracingExtension();
    }

    public function build(ContainerBuilder $container): void
    {
        // add compiler passes here
    }
}
