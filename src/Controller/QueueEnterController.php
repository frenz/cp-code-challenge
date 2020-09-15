<?php

namespace App\Controller;

use App\Message\QueueMessage;
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
        $this->messageBus->dispatch(new QueueMessage('bar'));
        return $this->json(['foo' => 'bar']);
    }
}
