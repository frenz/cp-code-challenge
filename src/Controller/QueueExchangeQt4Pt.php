<?php declare(strict_types=1);

namespace App\Controller;

use App\Exception\QueuePurchaseTokenNotFoundException;
use App\Service\QueueTokenExchanger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("api/queue/exchange-qt-for-pt", name="exchange_queue_token", methods={"POST"}))
 */
class QueueExchangeQt4Pt extends AbstractController
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
        try {
            $purchaseToken = $this->queueTokenExchanger->exchangeForPurchaseToken($token);
        } catch (QueuePurchaseTokenNotFoundException $e) {
            $message = $e->getMessage();
            return $this->json(['error' => $message], 404);

        }
        return $this->json(['purchase_token' => $purchaseToken]);
    }
}
