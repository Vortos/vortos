<?php

namespace Fortizan\Tekton\DependencyInjection;

use Fortizan\Tekton\Attribute\ApiController;
use Fortizan\Tekton\Bus\Command\Attribute\CommandHandler;
use Fortizan\Tekton\Bus\Query\Attribute\QueryHandler;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

class TektonExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container):void
    {
        $loader = new PhpFileLoader($container, new FileLocator(__DIR__ . '/../../config'));
        $loader->load('services.php');

        $this->registerCqrsAttributes($container);
        $this->registerMessengerAttributes($container);
        $this->registerHttpAttributes($container);
        
    }

    private function registerCqrsAttributes(ContainerBuilder $container):void
    {
        $container->registerAttributeForAutoconfiguration(
            CommandHandler::class,
            static function (ChildDefinition $definition, CommandHandler $attribute) {
                $definition->addTag('tekton.command.handler', [
                    'bus' => $attribute->bus,
                    'from_transport' => $attribute->fromTransport
                ]);
            }
        );

        $container->registerAttributeForAutoconfiguration(
            QueryHandler::class,
            static function (ChildDefinition $definition, QueryHandler $attribute) {
                $definition->addTag('tekton.query.handler', [
                    'bus' => $attribute->bus
                ]);
            }
        );
    }

    private function registerMessengerAttributes(ContainerBuilder $container): void 
    {
        $container->registerAttributeForAutoconfiguration(
            AsMessageHandler::class,
            static function (ChildDefinition $definition, AsMessageHandler $attribute) {
                $definition->addTag('messenger.message_handler', [
                    'bus' => $attribute->bus,
                    'from_transport' => $attribute->fromTransport,
                    'handles' => $attribute->handles,
                    'method' => $attribute->method,
                    'priority' => $attribute->priority,
                    'sign' => $attribute->sign
                ]);
            }
        );
    }

    private function registerHttpAttributes(ContainerBuilder $container): void 
    {
        $container->registerAttributeForAutoconfiguration(
            ApiController::class,
            static function (ChildDefinition $definition, ApiController $attribute) {
                $definition->setPublic(true);
                $definition->addTag('tekton.api.controller');
            }
        );
    }
}