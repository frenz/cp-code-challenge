<?php declare(strict_types=1);

namespace App\Controller;

use App\Service\QueueTokenExchanger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/queue/exchange-qt-for-pt", name="exchange_queue_token", methods={"POST"}))
 */
class ExchangeQueueTokenController extends AbstractController
{
    private QueueTokenExchanger $queueTokenExchanger;

    public function __construct(QueueTokenExchanger $queueTokenExchanger)
    {
        $this->queueTokenExchanger = $queueTokenExchanger;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $body = json_decode($request->getContent()) ?? [];
        $token = $body->queue_token;
        $purchaseToken = $this->queueTokenExchanger->exchangeForPurchaseToken($token);
        return $this->json(['purchase_token' => $purchaseToken]);
    }
}
