<?php

namespace App\User\Application\Query\GetUser;

use Fortizan\Tekton\Bus\Contract\Query\QueryInterface;

class GetUserQuery implements QueryInterface
{
    public function __construct(
        public int $userId
    )
    {}
}