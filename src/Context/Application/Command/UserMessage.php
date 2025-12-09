<?php

namespace App\Context\Application\Command;

readonly class UserMessage
{
    public function __construct(
        public string $message,
        // public string $status
    )
    {}
}