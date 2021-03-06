<?php declare(strict_types=1);

namespace App\Controller;

use App\Exception\QueuePurchaseTokenNotFoundException;
use App\Exception\TokenNotValidException;
use App\Service\QueueTokenExchanger;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("api/queue/exchange-qt-for-pt", name="exchange_queue_token", methods={"POST"}))
 */
class QueueExchangeQt4Pt extends AbstractController
{
    const PURCHASE_TOKEN_LABEL = 'purchase_token';
    const ERROR_LABEL = 'error';
    const INVALID_REQUEST = 'Invalid request';
    private QueueTokenExchanger $queueTokenExchanger;

    public function __construct(QueueTokenExchanger $queueTokenExchanger)
    {
        $this->queueTokenExchanger = $queueTokenExchanger;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $body = json_decode($request->getContent()) ?? [];
        try {
            if (empty($body) || !is_string($body->queue_token ?? null)) {
                throw new Exception(self::INVALID_REQUEST);
            }
            try {
                $token = $body->queue_token;
                $purchaseToken = $this->queueTokenExchanger->exchangeForPurchaseToken($token);
            } catch (TokenNotValidException $e) {
                $message = $e->getMessage();
                return $this->json([self::ERROR_LABEL => $message], Response::HTTP_NOT_FOUND);
            } catch (QueuePurchaseTokenNotFoundException $e) {
                $message = $e->getMessage();
                return $this->json([self::ERROR_LABEL => $message], Response::HTTP_NOT_FOUND);
            }
        } catch (Exception $e) {
            $message = $e->getMessage();
            return $this->json([self::ERROR_LABEL => $message], Response::HTTP_BAD_REQUEST);
        }
        return $this->json([self::PURCHASE_TOKEN_LABEL => $purchaseToken]);
    }
}
