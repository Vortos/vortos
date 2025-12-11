<?php

namespace App\User\Representation\Controller;

use App\User\Application\Query\GetUser\GetUserQuery;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class GetUserController{
    public function __construct(
        private MessageBusInterface $queryBus
    ){}

    public function __invoke(Request $request):Response
    {
        $envelop = $this->queryBus->dispatch(message: new GetUserQuery(userId: 1));
        $stamp = $envelop->last(HandledStamp::class);
        return new Response(new JsonResponse($stamp->getResult()));
    }
}