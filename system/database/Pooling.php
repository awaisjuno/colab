<?php

namespace System\Database;

use PDO;
use PDOException;
use System\Helpers\Config;

class Pooling
{
    private array $connections = [];
    private int $maxConnections;
    private string $connectionKey;

    public function __construct(string $connectionKey)
    {
        // Get the max_connections value from config
        $this->maxConnections = Config::get("database.mysql.$connectionKey.max_connections", 5); // Default to 5 if not set
        $this->connectionKey = $connectionKey;
    }

    /**
     * Get a connection from the pool
     *
     * @return PDO|null
     */
    public function getConnection(): ?PDO
    {
        // If the pool has available connections, return one
        if (count($this->connections) < $this->maxConnections) {
            $this->connections[] = $this->createConnection();
        }

        return array_pop($this->connections) ?: null;
    }

    /**
     * Create a new PDO connection
     *
     * @return PDO
     * @throws PDOException
     */
    private function createConnection(): PDO
    {
        // Get database configuration values from the config file
        $dbConfig = Config::get("database.mysql.{$this->connectionKey}");

        if (!$dbConfig) {
            throw new \Exception("Database configuration for connection key {$this->connectionKey} not found.");
        }

        try {
            // Create a new PDO instance
            $dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['dbname']};charset={$dbConfig['charset']}";
            $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password']);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            throw new PDOException("Error connecting to the database: " . $e->getMessage());
        }
    }

    /**
     * Release all connections back to the pool (reset the pool)
     */
    public function releaseConnections(): void
    {
        foreach ($this->connections as $conn) {
            $conn = null;
        }

        $this->connections = [];
    }
}
