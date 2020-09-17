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

    public function validateQtToken(string $qtToken): void
    {
        self::validateJWTToken($qtToken, $this->config['QT_KEY']);
    }

    private function validateJWTToken(string $token, string $key): void
    {
        try {
            JWT::decode($token, $key, [$this->config['JWT_ALGORITHM']]);
        } catch (ExpiredException $exception) {
            throw $exception;
        }
    }

    public function validatePtToken(string $ptToken): void
    {
        self::validateJWTToken($ptToken, $this->config['PT_KEY']);
    }

    public function createQToken(): string
    {
        $payload = $this->getPayload($this->config['QT_ISSUER'], $this->config['QT_AUDIENCE']);

        return JWT::encode($payload, $this->config['QT_KEY'],);
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
