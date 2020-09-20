<?php declare(strict_types = 1);

namespace App\Repository;

use App\Entity\QueuePurchaseToken;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method QueuePurchaseToken|null find($id, $lockMode = null, $lockVersion = null)
 * @method QueuePurchaseToken|null findOneBy(array $criteria, array $orderBy = null)
 * @method QueuePurchaseToken[]    findAll()
 * @method QueuePurchaseToken[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QueuePurchaseTokenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QueuePurchaseToken::class);
    }

    public function findByQueueToken(string $queueToken): ?QueuePurchaseToken
    {
        return $this->findOneBy(['queueToken' => $queueToken]);
    }
}
