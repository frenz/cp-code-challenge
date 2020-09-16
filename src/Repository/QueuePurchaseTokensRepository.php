<?php

namespace App\Repository;

use App\Entity\QueuePurchaseTokens;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method QueuePurchaseTokens|null find($id, $lockMode = null, $lockVersion = null)
 * @method QueuePurchaseTokens|null findOneBy(array $criteria, array $orderBy = null)
 * @method QueuePurchaseTokens[]    findAll()
 * @method QueuePurchaseTokens[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QueuePurchaseTokensRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QueuePurchaseTokens::class);
    }

}
