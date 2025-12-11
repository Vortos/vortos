<?php

namespace App\User\Application\Query\GetUser;

class GetUserResponse {
    public function __construct(
        public int $userId,
        public string $userName,
        public string $userEmail
    )
    {}
}