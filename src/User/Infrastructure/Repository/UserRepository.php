<?php

namespace App\User\Infrastructure\Repository;

use App\User\Domain\Entity\User;
use Vortos\Domain\Aggregate\AggregateRoot;
use Vortos\Domain\Identity\AggregateId;
use Vortos\Domain\Repository\WriteRepositoryInterface;

class UserRepository implements WriteRepositoryInterface
{
    public function __construct(
        
    ) {}

    public function findByEmail(): User
    {

    }

    public function save(AggregateRoot $aggregate): void
    {
        throw new \Exception('Not implemented');
    }

    public function delete(AggregateRoot $aggregate): void
    {
        throw new \Exception('Not implemented');
    }

    public function findById(AggregateId $id): ?AggregateRoot
    {
        throw new \Exception('Not implemented');
    }
}
