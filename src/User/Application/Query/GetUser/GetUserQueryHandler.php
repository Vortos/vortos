<?php

namespace App\User\Application\Query\GetUser;

use Fortizan\Tekton\Bus\Attribute\QueryHandler;
use Fortizan\Tekton\Bus\Contract\Query\QueryHandlerInterface;

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