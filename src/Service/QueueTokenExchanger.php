<?php declare(strict_types=1);


namespace App\Service;


use App\Entity\QueuePurchaseToken;
use App\Exception\QueuePurchaseTokenNotFoundException;
use App\Repository\QueuePurchaseTokenRepository;

class QueueTokenExchanger
{
    private QueuePurchaseTokenRepository $queuePurchaseTokenRepository;
    private JWTProvider $JWTProvider;

    public function __construct(QueuePurchaseTokenRepository $queuePurchaseTokenRepository, JWTProvider $JWTProvider)
    {
        $this->JWTProvider = $JWTProvider;
        $this->queuePurchaseTokenRepository = $queuePurchaseTokenRepository;
    }

    public function exchangeForPurchaseToken(string $queueToken): string
    {
        $valid = $this->JWTProvider->validateQueueToken($queueToken);
        if (!$valid) {
            throw new QueuePurchaseTokenNotFoundException('Token not valid');
        }
        $purchaseToken = $this->queuePurchaseTokenRepository->findByQueueToken($queueToken);

        if (!$purchaseToken instanceof QueuePurchaseToken) {
            throw new QueuePurchaseTokenNotFoundException('Token not available');
        }

        return $purchaseToken->getPurchaseToken();
    }
}