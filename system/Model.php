<?php

namespace System;

use System\Database\Connection;

/**
 * Class Model
 *
 * Provides a base database interaction layer using PDO.
 * Supports CRUD operations with method chaining for flexible query building.
 *
 * @package System
 */
class Model
{
    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * @var array Conditions to apply in WHERE clause
     */
    private array $whereConditions = [];

    /**
     * @var string Table name to operate on
     */
    private string $table = '';

    /**
     * @var int|null Limit for SELECT query
     */
    private ?int $limitValue = null;

    /**
     * Model constructor.
     * Initializes database connection using the Connection class.
     */
    public function __construct()
    {
        $connection = new Connection();
        $this->pdo = $connection->getConnection();
    }

    /**
     * Insert a new record into the database.
     *
     * @param string $table Table name
     * @param array $data Associative array of column => value
     * @return bool True on success, false on failure
     */
    public function insert(string $table, array $data): bool
    {
        $columns = implode(", ", array_keys($data));
        $values = ":" . implode(", :", array_keys($data));

        $sql = "INSERT INTO $table ($columns) VALUES ($values)";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute($data);
    }

    /**
     * Update records in the database.
     *
     * @param string $table Table name
     * @param array $data Associative array of column => value to update
     * @return bool True on success, false on failure
     */
    public function update(string $table, array $data): bool
    {
        $set = [];
        foreach ($data as $key => $value) {
            $set[] = "$key = :$key";
        }
        $set = implode(", ", $set);

        $where = '';
        if (!empty($this->whereConditions)) {
            $where = 'WHERE ' . implode(' AND ', array_map(
                    fn($key) => "$key = :$key", array_keys($this->whereConditions)
                ));
        }

        $sql = "UPDATE $table SET $set $where";
        $stmt = $this->pdo->prepare($sql);
        $params = array_merge($data, $this->whereConditions);

        // Reset conditions
        $this->whereConditions = [];

        return $stmt->execute($params);
    }

    /**
     * Delete records from the database.
     *
     * @param string $table Table name
     * @return bool True on success, false on failure
     */
    public function delete(string $table): bool
    {
        $where = '';
        if (!empty($this->whereConditions)) {
            $where = 'WHERE ' . implode(' AND ', array_map(
                    fn($key) => "$key = :$key", array_keys($this->whereConditions)
                ));
        }

        $sql = "DELETE FROM $table $where";
        $stmt = $this->pdo->prepare($sql);

        $params = $this->whereConditions;

        // Reset conditions
        $this->whereConditions = [];

        return $stmt->execute($params);
    }

    /**
     * Select multiple records from the database.
     *
     * @param string $table Table name
     * @param array $conditions Associative array of conditions
     * @param int|null $limit Limit the number of results
     * @return array Result set as associative array
     */
    public function select(string $table, array $conditions = [], int $limit = null): array
    {
        $where = '';
        if (!empty($conditions)) {
            $where = 'WHERE ' . implode(' AND ', array_map(
                    fn($key) => "$key = :$key", array_keys($conditions)
                ));
        }

        $sql = "SELECT * FROM $table $where" . ($limit ? " LIMIT $limit" : "");
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($conditions);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Find a single record by condition.
     *
     * @param string $table Table name
     * @param array $conditions Conditions for WHERE clause
     * @return array|null Single record or null if not found
     */
    public function find(string $table, array $conditions = []): ?array
    {
        $where = '';
        if (!empty($conditions)) {
            $where = 'WHERE ' . implode(' AND ', array_map(
                    fn($key) => "$key = :$key", array_keys($conditions)
                ));
        }

        $sql = "SELECT * FROM $table $where LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($conditions);

        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Add a WHERE condition to the query.
     *
     * @param string $field Column name
     * @param mixed $value Value to match
     * @return $this
     */
    public function where(string $field, $value): self
    {
        $this->whereConditions[$field] = $value;
        return $this;
    }

    /**
     * Add a LIMIT to the query.
     *
     * @param int $limit Number of records to retrieve
     * @return $this
     */
    public function limit(int $limit): self
    {
        $this->limitValue = $limit;
        return $this;
    }

    /**
     * Set the table name for query building.
     *
     * @param string $tableName
     * @return $this
     */
    public function table(string $tableName): self
    {
        $this->table = $tableName;
        return $this;
    }

    /**
     * Execute the built SELECT query and return the result.
     *
     * @param string|null $table Optional table name (overrides table())
     * @return array Result set as associative array
     */
    public function get(string $table = null): array
    {
        $tableName = $table ?? $this->table;

        $where = '';
        if (!empty($this->whereConditions)) {
            $where = 'WHERE ' . implode(' AND ', array_map(
                    fn($key) => "$key = :$key", array_keys($this->whereConditions)
                ));
        }

        $sql = "SELECT * FROM $tableName $where";

        if ($this->limitValue !== null) {
            $sql .= " LIMIT " . $this->limitValue;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->whereConditions);

        // Reset state after query
        $this->whereConditions = [];
        $this->limitValue = null;

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Get the last inserted ID.
     *
     * @return string Last insert ID
     */
    public function lastInsertId(): string
    {
        return $this->pdo->lastInsertId();
    }

}