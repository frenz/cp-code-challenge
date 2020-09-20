<?php

namespace Tests\Unit\Service;

use App\Entity\Product;
use App\Entity\Purchase;
use App\Repository\ProductRepository;
use App\Service\JWTProvider;
use App\Service\PurchaseProduct;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class PurchaseProductTest extends TestCase
{
    private PurchaseProduct $purchaseProduct;

    /**
     * @dataProvider providePayloadData
     *
     */
    public function testWithNameAndPurchaseToken($purchaseToken, $productName): void
    {
        $purchase = $this->purchaseProduct->withNameAndPurchaseToken($purchaseToken, $productName);
        $this->assertInstanceOf(Purchase::class, $purchase);
    }

    public function providePayloadData()
    {
        yield 'test1' => ['JWTPurchaseToken', 'Iphone 11'];
        yield 'test2' => ['JWTPurchaseToken', 'Iphone 11'];
        yield 'test3' => ['JWTPurchaseToken', 'Iphone 11'];
        yield 'test4' => ['JWTPurchaseToken', 'Iphone 11'];
        yield 'test5' => ['JWTPurchaseToken', 'Iphone 11'];
        yield 'test6' => ['JWTPurchaseToken', 'Iphone 11'];
    }

    protected function setUp(): void
    {
        /**
         * @var EntityManagerInterface $em
         * @var ProductRepository $productRepository
         * @var JWTProvider $JWTProvider
         **/
        $em = $this->getMockEntityManager();
        $JWTProvider = $this->getMockJWTProvider();
        $productRepository = $this->getMockProductRepository();
        $this->purchaseProduct = new PurchaseProduct($productRepository, $em, $JWTProvider);
    }

    protected function getMockEntityManager()
    {
        return $this->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    protected function getMockJWTProvider()
    {
        $JWTProvider = $this->getMockBuilder(JWTProvider::class)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->getMock();

        $JWTProvider->method('validateQueueToken')
            ->willReturn(true);
        $JWTProvider->method('validatePurchaseToken')
            ->willReturn(true);
        $JWTProvider->method('createQueueToken')
            ->willReturn('JWTQueueToken');
        $JWTProvider->method('createPurchaseToken')
            ->willReturn('JWTPurchaseToken');
        return $JWTProvider;
    }

    protected function getMockProductRepository()
    {
        $productRepository = $this->getMockBuilder(ProductRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $productRepository->method('findByName')
            ->willReturn($this->getMockProduct());
        return $productRepository;
    }

    protected function getMockProduct()
    {
        return $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
