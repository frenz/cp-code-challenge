<?php

namespace Tests\Unit\Service;
use App\Entity\QueuePurchaseToken;
use App\Repository\QueuePurchaseTokenRepository;
use App\Service\JWTProvider;
use App\Service\QueueTokenExchanger;
use PHPUnit\Framework\TestCase;

class QueueTokenExchangerTest extends TestCase
{
    /**
     * @var QueueTokenExchanger
     */
    private QueueTokenExchanger $QueueTokenExchanger;

    /**
     * @dataProvider provideQueueTokenAndPurchaseToken
     *
     */
    public function testExchangeForPurchaseToken(string $queueToken, string $purchaseToken)
    {
        $result = $this->QueueTokenExchanger->exchangeForPurchaseToken($queueToken);
        $this->assertEquals($result, $purchaseToken);
    }

    function provideQueueTokenAndPurchaseToken()
    {
        yield 'test1' => ['JWTQueueToken', 'JWTPurchaseToken'];
        yield 'test2' => ['JWTQueueToken', 'JWTPurchaseToken'];
        yield 'test3' => ['JWTQueueToken', 'JWTPurchaseToken'];
        yield 'test4' => ['JWTQueueToken', 'JWTPurchaseToken'];
        yield 'test5' => ['JWTQueueToken', 'JWTPurchaseToken'];
        yield 'test6' => ['JWTQueueToken', 'JWTPurchaseToken'];
    }

    protected function setUp(): void
    {
        /**
         * @var QueuePurchaseTokenRepository $queuePurchaseTokenRepository
         * @var JWTProvider $JWTProvider
         *
         **/
        $JWTProvider = $this->getMockJWTProvider();
        $queuePurchaseToken = new QueuePurchaseToken('JWTQueueToken', 'JWTPurchaseToken');
        $queuePurchaseTokenRepository = $this->getMockQueuePurchaseTokenRepository($queuePurchaseToken);
        $this->QueueTokenExchanger = new QueueTokenExchanger($queuePurchaseTokenRepository, $JWTProvider);
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

    protected function getMockQueuePurchaseTokenRepository(QueuePurchaseToken $queuePurchaseToken)
    {
        $queuePurchaseTokenRepository = $this->getMockBuilder(QueuePurchaseTokenRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $queuePurchaseTokenRepository->expects($this->any())
            ->method('findByQueueToken')
            ->will($this->returnValue($queuePurchaseToken));
        return $queuePurchaseTokenRepository;
    }
}
