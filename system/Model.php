<?php

namespace System;

use System\Database\Connection;
use PDO;

/**
 * Class Model
 *
 * Provides a fluent interface for building and executing SQL queries using PDO.
 * Includes support for basic CRUD operations and chaining for flexible querying.
 *
 * @package System
 */
class Model
{
    /**
     * @var PDO
     */
    private $pdo;

    /**
     * @var array
     */
    private $whereConditions = [];

    /**
     * @var string
     */
    private $table = '';

    /**
     * @var int|null
     */
    private $limitValue = null;

    /**
     * @var string
     */
    private $orderByClause = '';

    /**
     * @var string
     */
    private $selectColumns = '*';

    /**
     * Model constructor.
     */
    public function __construct()
    {
        $connection = new Connection();
        $this->pdo = $connection->getConnection();
    }

    /**
     * Inserts a new record into the database.
     *
     * @param string $table
     * @param array $data
     * @return bool
     */
    public function insert(string $table, array $data): bool
    {
        $columns = implode(', ', array_keys($data));
        $values = ':' . implode(', :', array_keys($data));
        $sql = "INSERT INTO $table ($columns) VALUES ($values)";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    /**
     * Updates existing records in the database.
     *
     * @param string $table
     * @param array $data
     * @return bool
     */
    public function update(string $table, array $data): bool
    {
        $set = [];
        foreach ($data as $key => $value) {
            $set[] = "$key = :$key";
        }

        $setClause = implode(', ', $set);
        $where = '';

        if (!empty($this->whereConditions)) {
            $where = 'WHERE ' . implode(' AND ', array_map(
                    fn($key) => "$key = :$key",
                    array_keys($this->whereConditions)
                ));
        }

        $sql = "UPDATE $table SET $setClause $where";
        $stmt = $this->pdo->prepare($sql);
        $params = array_merge($data, $this->whereConditions);

        $this->resetState();

        return $stmt->execute($params);
    }

    /**
     * Deletes records from the database.
     *
     * @param string $table
     * @return bool
     */
    public function delete(string $table): bool
    {
        $where = '';

        if (!empty($this->whereConditions)) {
            $where = 'WHERE ' . implode(' AND ', array_map(
                    fn($key) => "$key = :$key",
                    array_keys($this->whereConditions)
                ));
        }

        $sql = "DELETE FROM $table $where";
        $stmt = $this->pdo->prepare($sql);
        $params = $this->whereConditions;

        $this->resetState();

        return $stmt->execute($params);
    }

    /**
     * Retrieves multiple records based on conditions.
     *
     * @param string $table
     * @param array $conditions
     * @param int|null $limit
     * @return array
     */
    public function select(string $table, array $conditions = [], int $limit = null): array
    {
        $where = '';

        if (!empty($conditions)) {
            $where = 'WHERE ' . implode(' AND ', array_map(
                    fn($key) => "$key = :$key",
                    array_keys($conditions)
                ));
        }

        $sql = "SELECT * FROM $table $where" . ($limit ? " LIMIT $limit" : '');
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($conditions);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieves a single record matching conditions.
     *
     * @param string $table
     * @param array $conditions
     * @return array|null
     */
    public function find(string $table, array $conditions = []): ?array
    {
        $where = '';

        if (!empty($conditions)) {
            $where = 'WHERE ' . implode(' AND ', array_map(
                    fn($key) => "$key = :$key",
                    array_keys($conditions)
                ));
        }

        $sql = "SELECT * FROM $table $where LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($conditions);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Adds a WHERE condition to the query.
     *
     * @param string $field
     * @param mixed $value
     * @return $this
     */
    public function where(string $field, mixed $value): self
    {
        $this->whereConditions[$field] = $value;
        return $this;
    }

    /**
     * Sets a LIMIT for the query.
     *
     * @param int $limit
     * @return $this
     */
    public function limit(int $limit): self
    {
        $this->limitValue = $limit;
        return $this;
    }

    /**
     * Sets the table name for the query.
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
     * Specifies the columns to select.
     *
     * @param array $columns
     * @return $this
     */
    public function selectColumns(array $columns): self
    {
        $this->selectColumns = implode(', ', $columns);
        return $this;
    }

    /**
     * Adds an ORDER BY clause to the query.
     *
     * @param string $column
     * @param string $direction
     * @return $this
     */
    public function orderBy(string $column, string $direction = 'ASC'): self
    {
        $this->orderByClause = "ORDER BY $column " . strtoupper($direction);
        return $this;
    }

    /**
     * Executes the query and returns the results.
     *
     * @param string|null $table
     * @return array
     */
    public function get(string $table = null): array
    {
        $tableName = $table ?? $this->table;
        $where = '';

        if (!empty($this->whereConditions)) {
            $where = 'WHERE ' . implode(' AND ', array_map(
                    fn($key) => "$key = :$key",
                    array_keys($this->whereConditions)
                ));
        }

        $sql = "SELECT {$this->selectColumns} FROM $tableName $where";

        if (!empty($this->orderByClause)) {
            $sql .= " {$this->orderByClause}";
        }

        if ($this->limitValue !== null) {
            $sql .= " LIMIT {$this->limitValue}";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->whereConditions);

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->resetState();

        return $results;
    }

    /**
     * Returns the first record from the result.
     *
     * @param string|null $table
     * @return array|null
     */
    public function first(string $table = null): ?array
    {
        $this->limit(1);
        $results = $this->get($table);
        return $results[0] ?? null;
    }

    /**
     * Returns the last inserted ID from the database.
     *
     * @return string
     */
    public function lastInsertId(): string
    {
        return $this->pdo->lastInsertId();
    }

    /**
     * Resets the internal state of the query builder.
     *
     * @return void
     */
    private function resetState(): void
    {
        $this->whereConditions = [];
        $this->limitValue = null;
        $this->orderByClause = '';
        $this->selectColumns = '*';
    }
}
