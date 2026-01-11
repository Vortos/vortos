<?php

namespace Fortizan\Tekton\Bus\Event\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class AsEvent
{
    public function __construct(
        public  string $transport = 'async',
        public string $topic = 'default_topic'
    ) {}
}
