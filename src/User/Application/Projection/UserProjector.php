<?php

namespace App\User\Application\Projection;

use App\User\Domain\Event\UserCreatedEvent;
use Fortizan\Tekton\Bus\Projection\Attribute\ProjectionHandler;
use Fortizan\Tekton\Persistence\Contract\ProjectionWriterInterface;

class UserProjector 
{
    public function __construct(
        private ProjectionWriterInterface $writer
        ) {}
        
    #[ProjectionHandler(priority:6)]
    public function onUserCreated(UserCreatedEvent $event): void
    {
        $this->writer->upsert('users', $event->id, [
            'name' => $event->name,
            'email' => $event->email,
            'synced_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    #[ProjectionHandler(priority:5)]
    public function onUserDeleted(UserCreatedEvent $event): void
    {
        $this->writer->upsert('profile', $event->id, [
            'name' => $event->name . " Profile",
            'email' => $event->email,
            'synced_at' => date('Y-m-d H:i:s')
        ]);
    }
}
