<?php declare(strict_types=1);


namespace App\Service;

use App\Entity\QueuePurchaseToken;
use App\Exception\QueuePurchaseTokenNotFoundException;
use App\Exception\TokenNotValidException;
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

    /**
     * @param string $queueToken
     * @return string
     * @throws QueuePurchaseTokenNotFoundException
     * @throws TokenNotValidException
     */
    public function exchangeForPurchaseToken(string $queueToken): string
    {
        $valid = $this->JWTProvider->validateQueueToken($queueToken);
        if (!$valid) {
            throw new TokenNotValidException('Queue token not valid');
        }
        //remember that the user waits for the QWorker which converts all the QT to PT, token is expired etc with a message for the user
        //here we could wait TODO: check requirements better
        $purchaseToken = $this->queuePurchaseTokenRepository->findByQueueToken($queueToken);
        if (!$purchaseToken instanceof QueuePurchaseToken) {
            throw new QueuePurchaseTokenNotFoundException('Queue token not found');
        }

        return $purchaseToken->getPurchaseToken();
    }
}
