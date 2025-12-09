<?php

namespace App\Context\Representation\Controller;

use App\Context\Application\Command\UserMessage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;

class TestController 
{
    public function __construct(
        private MessageBusInterface $messageBus
    )
    {}

    public function index(Request $request){
        $this->messageBus->dispatch(new UserMessage('this is a test message'));
        return new Response('End of response from controller');
    }
}