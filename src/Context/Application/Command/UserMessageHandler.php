<?php

namespace App\Context\Application\Command;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class UserMessageHandler
{
    public function __invoke(UserMessage $message)
    {
        echo("Message Handler worked : " . $message->message . "<br>");
    }
}
