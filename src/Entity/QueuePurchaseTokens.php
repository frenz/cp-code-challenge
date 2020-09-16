<?php

namespace App\Entity;

use App\Repository\QueuePurchaseTokensRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=QueuePurchaseTokensRepository::class)
 */
class QueuePurchaseTokens
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $queueToken;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $purchaseToken;

    public function __construct(string $queueToken, string $purchaseToken)
    {
        $this->queueToken = $queueToken;
        $this->purchaseToken = $purchaseToken;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQueueToken(): ?string
    {
        return $this->queueToken;
    }

    public function setQueueToken(string $queueToken): self
    {
        $this->queueToken = $queueToken;

        return $this;
    }

    public function getPurchaseToken(): ?string
    {
        return $this->purchaseToken;
    }

    public function setPurchaseToken(string $purchaseToken): self
    {
        $this->purchaseToken = $purchaseToken;

        return $this;
    }
}
