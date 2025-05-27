<?php

namespace System;

use System\Database\Connection;
use PDO;
use PDOStatement;

class Model
{
    /**
     * @var PDO The PDO database connection.
     */
    protected PDO $pdo;

    /**
     * Table name used by this model. Should be set by child classes.
     */
    protected string $table = '';

    /**
     * Base Model constructor.
     * Automatically initializes the PDO connection via the Connection class.
     */
    public function __construct()
    {
        $this->pdo = (new Connection())->getConnection();
    }

    /**
     * Get PDO instance.
     *
     * @return PDO
     */
    protected function getPDO(): PDO
    {
        return $this->pdo;
    }

    /**
     * Insert a record.
     *
     * @param string $table
     * @param array $data
     * @return bool
     */
    public function insert(string $table, array $data): bool
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    /**
     * Update records with conditions.
     *
     * @param string $table
     * @param array $data
     * @param array $where
     * @return bool
     */
    public function update(string $table, array $data, array $where): bool
    {
        $set = implode(', ', array_map(fn($col) => "$col = :$col", array_keys($data)));
        $conditions = implode(' AND ', array_map(fn($col) => "$col = :where_$col", array_keys($where)));

        $sql = "UPDATE {$table} SET {$set} WHERE {$conditions}";
        $stmt = $this->pdo->prepare($sql);

        foreach ($where as $key => $value) {
            $stmt->bindValue(":where_{$key}", $value);
        }

        foreach ($data as $key => $value) {
            $stmt->bindValue(":{$key}", $value);
        }

        return $stmt->execute();
    }

    /**
     * Delete records by condition.
     *
     * @param string $table
     * @param array $where
     * @return bool
     */
    public function delete(string $table, array $where): bool
    {
        $conditions = implode(' AND ', array_map(fn($col) => "$col = :$col", array_keys($where)));
        $sql = "DELETE FROM {$table} WHERE {$conditions}";
        $stmt = $this->pdo->prepare($sql);

        foreach ($where as $key => $value) {
            $stmt->bindValue(":{$key}", $value);
        }

        return $stmt->execute();
    }

    /**
     * Select multiple records.
     *
     * @param string $table
     * @param array $conditions
     * @param int|null $limit
     * @param string|null $orderBy
     * @return array
     */
    public function select(string $table, array $conditions = [], int $limit = null, string $orderBy = null): array
    {
        $sql = "SELECT * FROM {$table}";
        if ($conditions) {
            $where = implode(' AND ', array_map(fn($col) => "$col = :$col", array_keys($conditions)));
            $sql .= " WHERE {$where}";
        }

        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy}";
        }

        if ($limit !== null) {
            $sql .= " LIMIT {$limit}";
        }

        $stmt = $this->pdo->prepare($sql);
        foreach ($conditions as $key => $value) {
            $stmt->bindValue(":{$key}", $value);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Select one record.
     *
     * @param string $table
     * @param array $conditions
     * @return array|null
     */
    public function selectOne(string $table, array $conditions = []): ?array
    {
        $results = $this->select($table, $conditions, 1);
        return $results[0] ?? null;
    }

    /**
     * Execute raw SQL query.
     *
     * @param string $sql
     * @param array $params
     * @return PDOStatement
     */
    public function raw(string $sql, array $params = []): PDOStatement
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * Create an index on a column for optimization.
     *
     * @param string $table
     * @param string $column
     * @param string|null $indexName
     * @return bool
     */
    public function createIndex(string $table, string $column, string $indexName = null): bool
    {
        $indexName = $indexName ?? "{$table}_{$column}_idx";
        $sql = "CREATE INDEX IF NOT EXISTS {$indexName} ON {$table} ({$column})";

        try {
            return $this->pdo->exec($sql) !== false;
        } catch (\PDOException $e) {
            return false;
        }
    }

    /**
     * Optimize table (e.g., after many deletes/updates).
     *
     * @param string $table
     * @return bool
     */
    public function optimizeTable(string $table): bool
    {
        try {
            return $this->pdo->exec("OPTIMIZE TABLE {$table}") !== false;
        } catch (\PDOException $e) {
            return false;
        }
    }

    /**
     * Count rows with optional conditions.
     *
     * @param string $table
     * @param array $conditions
     * @return int
     */
    public function count(string $table, array $conditions = []): int
    {
        $sql = "SELECT COUNT(*) as total FROM {$table}";
        if ($conditions) {
            $where = implode(' AND ', array_map(fn($col) => "$col = :$col", array_keys($conditions)));
            $sql .= " WHERE {$where}";
        }

        $stmt = $this->pdo->prepare($sql);
        foreach ($conditions as $key => $value) {
            $stmt->bindValue(":{$key}", $value);
        }

        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    /**
     * Repair the given table.
     *
     * @param string $tableName
     * @return bool
     */
    protected function repairTable(string $tableName): bool
    {
        try {
            $sql = "REPAIR TABLE {$tableName}";
            return $this->pdo->exec($sql) !== false;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Analyze the given table.
     *
     * @param string $tableName
     * @return bool
     */
    protected function analyzeTable(string $tableName): bool
    {
        try {
            $sql = "ANALYZE TABLE {$tableName}";
            return $this->pdo->exec($sql) !== false;
        } catch (PDOException $e) {
            return false;
        }
    }
}
