<?php

namespace App\Controller;

use App\Message\QtTokenMessage;
use App\Service\JWTProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/queue/enter", name="queue_enter")
 */
class QueueEnterController extends AbstractController
{
    private MessageBusInterface $messageBus;
    private JWTProvider $JWTProvider;

    public function __construct(MessageBusInterface $messageBus, JWTProvider $JWTProvider)
    {
        $this->messageBus = $messageBus;
        $this->JWTProvider = $JWTProvider;
    }

    public function __invoke(): JsonResponse
    {
        $token = $this->JWTProvider->createQToken();
        $this->messageBus->dispatch(new QtTokenMessage($token));
        return $this->json(['token' => $token]);
    }

}
