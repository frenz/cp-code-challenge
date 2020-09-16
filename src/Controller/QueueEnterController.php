<?php

namespace App\Controller;

use App\Message\QueueMessage;
use Firebase\JWT\JWT;
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

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function __invoke(): JsonResponse
    {
        $token = $this->getJWTToken();
        $this->messageBus->dispatch(new QueueMessage($token));
        return $this->json(['token' => $token]);
    }

    /**
     * @return string
     */
    private function getJWTToken(): string
    {
        /* creating access token */
        $issuedAt = time();
        // jwt valid for 12 hours (24 hours * 60 days)
        $expirationTime = $issuedAt + 24 * 60;
        $payload = array(
            'iat' => $issuedAt,
            'exp' => $expirationTime,
        );
        $key = $this->getParameter('app.jwt_key');
        $token = JWT::encode($payload, $key);
        return $token;
    }
}
