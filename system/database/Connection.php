<?php

namespace System\Database;

/**
 * Class Connection
 * Handles the database connection using PDO.
 *
 * @package System\Database
 */
class Connection
{
    /**
     * @var \PDO $pdo PDO instance for the database connection.
     */
    private $pdo;

    /**
     * @var array $config Database configuration.
     */
    private $config;

    /**
     * Connection constructor.
     * Initializes the PDO connection using the provided connection name.
     *
     * @param string $connectionName The name of the connection to use from the config file.
     * @throws \PDOException If the database connection fails.
     */
    public function __construct($connectionName = 'default')
    {
        // Load the database configuration
        $this->config = require 'config/database.php';

        // Validate connection name
        if (!isset($this->config['mysql'][$connectionName])) {
            die("Database connection '{$connectionName}' not found.");
        }

        // Extract DB connection details
        $dbConfig = $this->config['mysql'][$connectionName];

        // Set DSN and credentials
        $dsn = 'mysql:host=' . $dbConfig['host'] . ';dbname=' . $dbConfig['dbname'] . ';charset=' . $dbConfig['charset'];
        $username = $dbConfig['username'];
        $password = $dbConfig['password'];

        try {
            // Create PDO instance
            $this->pdo = new \PDO($dsn, $username, $password);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            die('Database connection failed: ' . $e->getMessage());
        }
    }

    /**
     * Returns the PDO instance.
     *
     * @return \PDO The PDO connection instance.
     */
    public function getConnection()
    {
        return $this->pdo;
    }

    public function getPDO()
    {
        return $this->pdo;
    }
}

?>