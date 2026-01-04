<?php

namespace Fortizan\Tekton\Messenger;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Transport\TransportInterface;
use Symfony\Component\Messenger\Worker;

class Consumer
{
    private Worker $worker;

    public function __construct(
        TransportInterface $transport,
        MessageBusInterface $eventBus,
        ?EventDispatcherInterface $eventDispatcher = null
    ) {
        $this->worker = new Worker(
            receivers: [$transport],
            bus: $eventBus,
            eventDispatcher: $eventDispatcher
        );
    }

    public function run(): void
    {
        $this->worker->run();
    }
}