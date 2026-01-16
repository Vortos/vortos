<?php

namespace App\User\Domain\Event;

use Fortizan\Tekton\Bus\Event\Attribute\AsEvent;
use Fortizan\Tekton\Domain\Event\DomainEventInterface;
use Symfony\Component\Uid\UuidV7;

#[AsEvent(transport:'async', topic: 'user.created')]
final readonly class UserCreatedEvent implements DomainEventInterface
{
    public function __construct(
        public UuidV7 $id ,
        public string $name ,
        public string $email 
    ){
    }
}