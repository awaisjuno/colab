<?php

namespace System;
use System\Database\Connection;

/**
 * Model class handles database interactions using PDO for executing CRUD operations.
 */
class Model {

    /**
     * @var \PDO
     * The PDO instance used to connect to the database and execute queries.
     */
    private $pdo;

    /**
     * @var array
     * Stores the "WHERE" conditions for database queries.
     */
    private $whereConditions = [];

    /**
     * Constructor initializes the database connection.
     * It sets up the PDO instance using configuration values.
     *
     * @throws \PDOException if the connection fails
     */
    public function __construct()
    {
        $connection = new Connection();
        $this->pdo = $connection->getConnection();
    }

    /**
     * Insert data into the specified table.
     *
     * @param string $table The name of the table to insert data into.
     * @param array $data Associative array of column names and their values.
     *
     * @return bool Returns true on success, false on failure.
     */
    public function insert($table, $data) {
        // Prepare columns and values for the SQL query
        $columns = implode(", ", array_keys($data));
        $values = ":" . implode(", :", array_keys($data));

        // Build the SQL query
        $sql = "INSERT INTO $table ($columns) VALUES ($values)";
        $stmt = $this->pdo->prepare($sql);

        // Execute the statement with the data
        return $stmt->execute($data);
    }

    /**
     * Update data in the specified table based on set conditions.
     *
     * @param string $table The name of the table to update data in.
     * @param array $data Associative array of column names and their new values.
     *
     * @return bool Returns true on success, false on failure.
     */
    public function update($table, $data) {
        // Prepare SET clause
        $set = [];
        foreach ($data as $key => $value) {
            $set[] = "$key = :$key";
        }
        $set = implode(", ", $set);

        // Prepare WHERE clause based on stored conditions
        $where = '';
        if (!empty($this->whereConditions)) {
            $where = 'WHERE ' . implode(' AND ', array_map(fn($key) => "$key = :$key", array_keys($this->whereConditions)));
        }

        // Build the SQL query
        $sql = "UPDATE $table SET $set $where";
        $stmt = $this->pdo->prepare($sql);

        // Merge the data and conditions and execute the statement
        $params = array_merge($data, $this->whereConditions);
        $this->whereConditions = [];

        return $stmt->execute($params);
    }

    /**
     * Delete data from the specified table based on conditions.
     *
     * @param string $table The name of the table to delete data from.
     *
     * @return bool Returns true on success, false on failure.
     */
    public function delete($table) {
        // Prepare WHERE clause based on stored conditions
        $where = '';
        if (!empty($this->whereConditions)) {
            $where = 'WHERE ' . implode(' AND ', array_map(fn($key) => "$key = :$key", array_keys($this->whereConditions)));
        }

        // Build the SQL query
        $sql = "DELETE FROM $table $where";
        $stmt = $this->pdo->prepare($sql);

        // Execute the statement with the conditions
        $params = $this->whereConditions;
        $this->whereConditions = [];

        return $stmt->execute($params);
    }

    /**
     * Select data from the specified table based on conditions and optional limit.
     *
     * @param string $table The name of the table to select data from.
     * @param array $conditions Associative array of conditions for the WHERE clause.
     * @param int|null $limit Optional limit for the number of rows to return.
     *
     * @return array Returns an array of results from the query.
     */
    public function select($table, $conditions = [], $limit = null) {
        // Prepare WHERE clause based on conditions
        $where = '';
        if (!empty($conditions)) {
            $where = 'WHERE ' . implode(' AND ', array_map(fn($key) => "$key = :$key", array_keys($conditions)));
        }

        // Build the SQL query with optional LIMIT
        $sql = "SELECT * FROM $table $where" . ($limit ? " LIMIT $limit" : "");
        $stmt = $this->pdo->prepare($sql);

        // Execute the query with the conditions
        $stmt->execute($conditions);

        // Return the fetched results as an associative array
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Add a WHERE condition to the query.
     *
     * @param string $field The column name for the condition.
     * @param mixed $value The value to match in the condition.
     *
     * @return \System\Model Returns the Model instance to allow method chaining.
     */
    public function where($field, $value) {
        $this->whereConditions[$field] = $value;
        return $this;
    }

    /**
     * Set a limit for the query.
     *
     * @param int $limit The number of rows to limit the result to.
     *
     * @return int The limit value.
     */
    public function limit($limit) {
        return $limit;
    }

    /**
     * Get the last inserted ID from the database.
     *
     * @return string The ID of the last inserted row.
     */
    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }
}

?>