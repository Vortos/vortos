<?php

namespace App\User\Application\Command\RegisterUser;

use Fortizan\Tekton\Bus\Command\Contract\CommandInterface;

readonly class RegisterUserCommand implements CommandInterface
{
    public function __construct(
        public string $firstName,
        public string $email
    )
    {}
}