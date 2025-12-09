<?php

use App\Context\Application\Command\UserMessage;
use App\Context\Application\Command\UserMessageHandler;
use App\Context\Representation\Controller\TestController;
use Fortizan\Tekton\EventListener\GoogleListener;
use Fortizan\Tekton\EventListener\StringResponseListener;
use Fortizan\Tekton\Http\Kernal;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ContainerControllerResolver;
use Symfony\Component\HttpKernel\EventListener\ErrorListener;
use Symfony\Component\HttpKernel\EventListener\ResponseListener;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\DependencyInjection\MessengerPass;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

$container = new ContainerBuilder();

$loader = new PhpFileLoader($container, new FileLocator(__DIR__ . '/../../config'));
$loader->load('services.php');

// i can make my own stribute and make it auto discover. for that i need my own compiler pass and register it like below
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

// if i have a custom bus too, i need another locator just like this with my owns id with empty arguments
$container->register('messenger.bus.default.messenger.handlers_locator', HandlersLocator::class)
    ->setArguments([[]]);

// this shit is called when a Message is dipatched right? i can create my own for my own bus right? can i also use this one for my own too?
$container->register('messenger.middleware.handle_message', HandleMessageMiddleware::class)
    ->setArguments([new Reference('messenger.bus.default.messenger.handlers_locator')]);


$container->register('messenger.bus.default', MessageBus::class)
    ->setArguments([
        [new Reference('messenger.middleware.handle_message')] # so is message bus is the one who calls middleware thing above??
    ])->addTag('messenger.bus'); #this tag says this is a message bus, if i create my own it also needs this same tag

// this will take all the handlers with AsMessageHandler attribute and read eachs meta data and put it in to it relevent locator depending on its message bus, right?
$container->addCompilerPass(new MessengerPass());


// -----------------------------------------------------------------------------------

$container->setParameter('charset', 'UTF-8');
$container->setParameter('charset', 'ISO-8859-1');

$container->register('context', RequestContext::class);
$container->register('request_stack', RequestStack::class);
$container->register('argument_resolver', ArgumentResolver::class);

$container->register('controller_resolver', ContainerControllerResolver::class)
    ->setArguments([new Reference('service_container')]);


$container->register('routes', RouteCollection::class)->setSynthetic(true);

$container->register('matcher', UrlMatcher::class)->setArguments([new Reference('routes'), new Reference('context')]);

$container->register('listener.router', RouterListener::class)
    ->setArguments([new Reference('matcher'), new Reference('request_stack')]);

$container->register('listener.response', ResponseListener::class)
    ->setArguments(['%charset%']);

$container->register('listener.exception', ErrorListener::class)
    ->setArguments(['Fortizan\Tekton\Controller\ErrorController::handle']);

$container->register('listener.google', GoogleListener::class);
$container->register('listener.string_response_listener', StringResponseListener::class);

$container->register('dispatcher', EventDispatcher::class)
    ->addMethodCall('addSubscriber', [new Reference('listener.router')])
    ->addMethodCall('addSubscriber', [new Reference('listener.response')])
    ->addMethodCall('addSubscriber', [new Reference('listener.exception')])
    ->addMethodCall('addSubscriber', [new Reference('listener.google')])
    ->addMethodCall('addSubscriber', [new Reference('listener.string_response_listener')]);

$container->register('framework', Kernal::class)->setPublic(true)->setArguments([
    new Reference('dispatcher'),
    new Reference('controller_resolver'),
    new Reference('request_stack'),
    new Reference('argument_resolver'),
]);


return $container;
