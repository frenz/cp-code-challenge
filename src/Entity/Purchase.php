<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\PurchaseRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PurchaseRepository::class)
 * @ORM\Table(name="`purchase`")
 */
class Purchase
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
    private ?string $purchaseToken;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="purchases")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Product $product;

    public function __construct(string $purchaseToken, Product $product)
    {
        $this->purchaseToken = $purchaseToken;
        $this->product = $product;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }
}
