<?php

namespace App\User\Application\Query\GetUser;

use Fortizan\Tekton\Bus\Query\Attribute\QueryHandler;
use Fortizan\Tekton\Bus\Query\Contract\QueryHandlerInterface;

#[QueryHandler]
class GetUserQueryHandler implements QueryHandlerInterface
{
 
    public function __invoke(GetUserQuery $query):GetUserResponse
    {
        return new GetUserResponse(
            userId: $query->userId,
            userEmail: "abc@gmail.com",
            userName: "tekton"
        );
    }
}