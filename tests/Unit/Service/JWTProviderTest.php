<?php

namespace Tests\Unit\Service;

use App\Service\JWTProvider;
use PHPUnit\Framework\TestCase;

class JWTProviderTest extends TestCase
{
    protected array $config;
    private JWTProvider $JWTProvider;

    public function testCreateQueueToken(): string
    {
        $queueToken = $this->JWTProvider->createQueueToken();
        $this->assertIsString($queueToken);
        return $queueToken;
    }

    /**
     * @depends testCreateQueueToken
     * @param string $queueToken
     */
    public function testValidateQueueToken(string $queueToken)
    {
        $response = $this->JWTProvider->validateQueueToken($queueToken);
        $this->assertIsBool($response);
        $this->assertTrue($response);
    }

    public function testCreatePurchaseToken()
    {
        $purchaseToken = $this->JWTProvider->createPurchaseToken();
        $this->assertIsString($purchaseToken);
        return $purchaseToken;
    }

    /**
     * @depends testCreatePurchaseToken
     * @param string $purchaseToken
     */
    public function testValidatePurchaseToken(string $purchaseToken)
    {
        $response = $this->JWTProvider->validatePurchaseToken($purchaseToken);
        $this->assertIsBool($response);
        $this->assertTrue($response);
    }

    protected function setUp(): void
    {
        $this->config = [
            'ALGORITHM' => 'JWT_ALGORITHM',
            'QT_KEY' => 'JWT_QT_KEY',
            'PT_KEY' => 'JWT_PT_KEY',
            'QT_ISSUER' => 'JWT_QT_ISSUER',
            'QT_AUDIENCE' => 'JWT_QT_AUDIENCE',
            'PT_ISSUER' => 'JWT_PT_ISSUER',
            'PT_AUDIENCE' => 'JWT_PT_AUDIENCE'];

        $this->JWTProvider = new JWTProvider;
    }
}
