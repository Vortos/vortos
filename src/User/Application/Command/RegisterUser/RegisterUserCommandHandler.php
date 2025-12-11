<?php

namespace App\User\Application\Command\RegisterUser;

use Fortizan\Tekton\Bus\Attribute\CommandHandler;
use Fortizan\Tekton\Bus\Contract\Command\CommandHandlerInterface;

#[CommandHandler]
class RegisterUserCommandHandler implements CommandHandlerInterface
{
    public function __invoke(RegisterUserCommand $command)
    {
        echo("User with name : " . $command->firstName . " is now registering. <br>");
    }
}
