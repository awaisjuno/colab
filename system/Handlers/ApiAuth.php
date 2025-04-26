<?php

namespace System\Handlers;

use System\Database\Connection;

class ApiAuth
{
    protected $pdo;

    public function __construct()
    {
        $this->pdo = (new Connection())->getConnection();
    }

    public function validateToken(string $token): array
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM api_detail 
            WHERE token = :token 
              AND is_active = 1 
              AND is_delete = 0 
              AND (expire_at IS NULL OR expire_at > NOW())
        ");
        $stmt->execute(['token' => $token]);
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        if (empty($result)) {
            throw new \Exception("Invalid or expired token.");
        }

        return $result[0];
    }

    public function logHit(string $route, string $ip, int $tokenId): bool
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO api_hits (route, ip_address, token_id,) 
            VALUES (:route, :ip_address, :token_id)
        ");

        return $stmt->execute([
            'route'      => $route,
            'ip_address' => $ip,
            'token_id'   => $tokenId
        ]);
    }
}