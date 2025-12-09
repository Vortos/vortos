<?php

namespace Fortizan\Tekton\Database;

use Attribute;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class AsCommandHandler extends AsMessageHandler
{
    public function __construct(
        public ?string $bus = 'command.bus',
        public ?string $fromTransport = null,
        public ?string $handles = null,
        public ?string $method = null,
        public int $priority = 0
    ) {
        parent::__construct(
            bus: $bus,
            fromTransport: $fromTransport,
            handles: $handles,
            method: $method,
            priority: $priority
        );
    }
}
