<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\Product;
use App\Entity\Purchase;
use App\Exception\ProductNotFoundException;
use App\Exception\ProductOutOfStockException;
use App\Exception\TokenNotValidException;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Firebase\JWT\ExpiredException;

class PurchaseProduct
{
    private ProductRepository $productRepository;
    private EntityManagerInterface $em;
    private JWTProvider $JWTProvider;

    public function __construct(ProductRepository $productRepository, EntityManagerInterface $em, JWTProvider $JWTProvider)
    {
        $this->productRepository = $productRepository;
        $this->em = $em;
        $this->JWTProvider = $JWTProvider;
    }

    /**
     * @param string $purchaseToken
     * @param int $productId
     * @return Purchase
     * @throws ProductNotFoundException
     * @throws ProductOutOfStockException
     * @throws TokenNotValidException
     * @throws ExpiredException
     */
    public function withNameAndPurchaseToken(string $purchaseToken, int $productId): Purchase
    {
        $valid = $this->JWTProvider->validatePurchaseToken($purchaseToken);
        if (!$valid) {
            throw new TokenNotValidException('token not valid');
        }
        $product = $this->getProduct($productId);

        if ($product->isOutOfStock()) {
            throw new ProductOutOfStockException('Product is out of stock');
        }

        return $this->createPurchase($purchaseToken, $product);
    }

    /**
     * @param int $productId
     * @return Product
     * @throws ProductNotFoundException
     */
    private function getProduct(int $productId): Product
    {
        $product = $this->productRepository->find($productId);

        if (!$product instanceof Product) {
            throw new ProductNotFoundException('Product not found');
        }

        return $product;
    }

    private function createPurchase(string $token, Product $product): Purchase
    {
        $product->decrementStock();

        $purchase = new Purchase($token, $product);

        $this->em->persist($purchase);
        $this->em->persist($product);
        $this->em->flush();

        return $purchase;
    }
}
