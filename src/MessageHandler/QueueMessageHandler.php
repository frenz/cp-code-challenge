<?php

namespace App\MessageHandler;

use App\Entity\QueuePurchaseTokens;
use App\Message\QueueMessage;
use App\Service\JWTProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class QueueMessageHandler implements MessageHandlerInterface
{
    private EntityManagerInterface $em;
    private JWTProvider $JWTProvider;

    public function __construct(EntityManagerInterface $em, JWTProvider $JWTProvider)
    {
        $this->em = $em;
        $this->JWTProvider = $JWTProvider;
    }

    public function __invoke(QueueMessage $message)
    {
        $queueToken = $message->getToken();
        $queuePurchaseTokens = new QueuePurchaseTokens($queueToken, $this->JWTProvider->createPurchaseToken());
        $this->em->persist($queuePurchaseTokens);
        $this->em->flush();
    }

}
