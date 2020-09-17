<?php declare(strict_types=1);

namespace App\MessageHandler;

use App\Entity\QueuePurchaseToken;
use App\Message\QueueTokenMessage;
use App\Service\JWTProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class QueueTokenWorker implements MessageHandlerInterface
{
    private EntityManagerInterface $em;
    private JWTProvider $JWTProvider;

    public function __construct(EntityManagerInterface $em, JWTProvider $JWTProvider)
    {
        $this->em = $em;
        $this->JWTProvider = $JWTProvider;
    }

    public function __invoke(QueueTokenMessage $message)
    {
        $queueToken = $message->getToken();
        $queuePurchaseTokens = new QueuePurchaseToken($queueToken, $this->JWTProvider->createPurchaseToken());
        $this->em->persist($queuePurchaseTokens);
        $this->em->flush();
    }

}
