<?php

namespace App\Model;

use System\Model;

/**
 * Class General
 *
 * Model class for the 'general' table.
 */
class General extends Model
{
    /**
     * The name of the database table used by this model.
     */
    protected string $tableName = 'general';

    /**
     * General constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Insert data into the 'general' table.
     *
     * @param array $data
     * @return bool
     */
    public function insertData(array $data): bool
    {
        return $this->insert($this->tableName, $data);
    }

    /**
     * Update a record by ID.
     *
     * @param int|string $id
     * @param array $data
     * @return bool
     */
    public function updateData($id, array $data): bool
    {
        return $this->update($this->tableName, $data, ['id' => $id]);
    }

    /**
     * Delete a record by ID.
     *
     * @param int|string $id
     * @return bool
     */
    public function deleteData($id): bool
    {
        return $this->delete($this->tableName, ['id' => $id]);
    }

    /**
     * Get multiple records from the table with optional conditions.
     *
     * @param array $conditions
     * @param int|null $limit
     * @param string|null $orderBy
     * @return array
     */
    public function selectData(array $conditions = [], int $limit = null, string $orderBy = null): array
    {
        return $this->select($this->tableName, $conditions, $limit, $orderBy);
    }

    /**
     * Get a single record by ID.
     *
     * @param int|string $id
     * @return array|null
     */
    public function findData($id): ?array
    {
        return $this->selectOne($this->tableName, ['id' => $id]);
    }

    /**
     * Count rows in the general table with optional conditions.
     *
     * @param array $conditions
     * @return int
     */
    public function countData(array $conditions = []): int
    {
        return $this->count($this->tableName, $conditions);
    }
}
