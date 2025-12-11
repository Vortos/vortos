<?php

namespace Fortizan\Tekton\DependencyInjection\Compiler\Cqrs;

use Exception;
use Fortizan\Tekton\Bus\Query\Contract\QueryHandlerInterface;
use Fortizan\Tekton\Bus\Query\Contract\QueryInterface;
use ReflectionClass;
use ReflectionMethod;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class QueryHandlerPass implements CompilerPassInterface
{

    public function process(ContainerBuilder $container): void
    {
        if (!$container->has('tekton.bus.query.locator')) {
            return;
        }

        $locatorDefinition = $container->findDefinition('tekton.bus.query.locator');

        $handlers = $container->findTaggedServiceIds('tekton.query.handler');

        $handlersMap = [];

        foreach ($handlers as $serviceId => $metaData) {
            $handlerDefinision = $container->getDefinition($serviceId);
         
            $method = new ReflectionMethod($handlerDefinision->getClass() . "::__invoke");
            $handlerClass = $method->getDeclaringClass();
            $handlerInterfaceNames = $handlerClass->getInterfaceNames();

            if (!in_array(QueryHandlerInterface::class, $handlerInterfaceNames)) {
                throw new Exception(
                    "Did you forgot to implement 'QueryHandlerInterface' in your " . $handlerClass->getShortName() . " class ?"
                );
            }

            $queryFqcn = $method->getParameters()[0]->getType()->getName();
            $queryClass = new ReflectionClass($queryFqcn);
            $queryInterfaceNames = $queryClass->getInterfaceNames();

            if (!in_array(QueryInterface::class, $queryInterfaceNames)) {
                throw new Exception(
                    "Did you forgot to implement 'QueryInterface' in your " . $queryClass->getShortName() . " class ?"
                );
            }

            $handlersMap[$queryFqcn] = [new Reference($serviceId)];
        }

        $locatorDefinition->replaceArgument(0, $handlersMap);
    }
}
