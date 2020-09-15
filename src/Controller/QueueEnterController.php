<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/queue/enter", name="queue_enter")
 */
class QueueEnterController extends AbstractController
{

    public function __invoke(): JsonResponse
    {
        return $this->json(['foo' => 'bar']);
    }
}
