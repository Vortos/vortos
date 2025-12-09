<?php

namespace Fortizan\Tekton\EventListener;

use Fortizan\Tekton\Event\ResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class GoogleListener implements EventSubscriberInterface
{
    public function onResponse(ResponseEvent $event): void
    {
        $response = $event->getResponse();

        if (
            $response->isRedirection()
            || ($response->headers->has('Content-Type') && !str_contains($response->headers->get('Content-Type'), 'html'))
            || 'html' !== $event->getRequest()->getRequestFormat()
        ) {
            return;
        }

        $response->setContent($response->getContent() . " : Event Dispatcher worked correctly");
    }
    public function test(ResponseEvent $event): void
    {
        $response = $event->getResponse();

        if (
            $response->isRedirection()
            || ($response->headers->has('Content-Type') && !str_contains($response->headers->get('Content-Type'), 'html'))
            || 'html' !== $event->getRequest()->getRequestFormat()
        ) {
            return;
        }

        $response->setContent($response->getContent() . " : test event listener");
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'response' => [
                ['test', 2],
                ['onResponse', 1]
                // higher the priority number run quickly
            ],
        ];
    }
}
