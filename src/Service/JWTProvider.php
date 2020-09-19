<?php declare(strict_types=1);

namespace App\Service;

use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;

class JWTProvider
{
    private array $config;

    public function __construct()
    {
        $this->config = [
            'ALGORITHM' => getenv('JWT_ALGORITHM'),
            'QT_KEY' => getenv('JWT_QT_KEY'),
            'PT_KEY' => getenv('JWT_PT_KEY'),
            'QT_ISSUER' => getenv('JWT_QT_ISSUER'),
            'QT_AUDIENCE' => getenv('JWT_QT_AUDIENCE'),
            'PT_ISSUER' => getenv('JWT_PT_ISSUER'),
            'PT_AUDIENCE' => getenv('JWT_PT_AUDIENCE')];
    }

    public function validateQueueToken(string $queueToken): bool
    {
        $result = $this->validateJWTToken($queueToken, $this->config['QT_KEY']);
        if (isset($result->iss) && $result->iss === $this->config['QT_ISSUER']) {
            return true;
        }
        return false;
    }

    private function validateJWTToken(string $token, string $key): object
    {
        try {
            return JWT::decode($token, $key, [$this->config['ALGORITHM']]);
        } catch (ExpiredException $exception) {
            throw $exception;
        }
    }

    public function validatePurchaseToken(string $purchaseToken): bool
    {
        $result = $this->validateJWTToken($purchaseToken, $this->config['PT_KEY']);
        if (isset($result->iss) && $result->iss === $this->config['PT_ISSUER']) {
            return true;
        }
        return false;
    }

    public function createQueueToken(): string
    {
        $payload = $this->getPayload($this->config['QT_ISSUER'], $this->config['QT_AUDIENCE']);

        return JWT::encode($payload, $this->config['QT_KEY'], $this->config['ALGORITHM']);
    }

    private function getPayload(string $issuer, string $audience): array
    {
        /* creating access token */
        $issuedAt = time();
        // jwt valid for 12 hours (24 hours * 60 days)
        $expirationTime = $issuedAt + 24 * 60;
        return [
            "iss" => $issuer,
            "aud" => $audience,
            'iat' => $issuedAt,
            'exp' => $expirationTime,
        ];
    }

    public function createPurchaseToken(): string
    {
        $payload = $this->getPayload($this->config['PT_ISSUER'], $this->config['PT_AUDIENCE']);
        return JWT::encode($payload, $this->config['PT_KEY']);
    }
}
