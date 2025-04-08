<?php

namespace System\helpers;

use System\Database\Connection;

/**
 * Class Table
 *
 * Used for dynamically creating MySQL tables with support for
 * columns, primary keys, foreign keys, and default values.
 *
 * @package System\helpers
 */
class Table
{
    private string $tableName;
    private array $columns = [];
    private ?string $primaryKey = null;
    private array $foreignKeys = [];
    private Connection $db;

    /**
     * Constructor to set the table name and initialize DB connection
     *
     * @param string $tableName
     */
    public function __construct(string $tableName)
    {
        $this->tableName = $tableName;
        $this->db = new Connection();
    }

    /**
     * Define the primary key column (auto-increment)
     *
     * @param string $name
     * @return $this
     */
    public function id(string $name = 'id'): self
    {
        $this->primaryKey = $name;
        $this->int($name, true);
        return $this;
    }

    /**
     * Define an INT column
     *
     * @param string $columnName
     * @param bool $autoIncrement
     * @return $this
     */
    public function int(string $columnName, bool $autoIncrement = false): self
    {
        $this->columns[] = "$columnName INT" . ($autoIncrement ? " AUTO_INCREMENT" : "");
        return $this;
    }

    /**
     * Define a VARCHAR column
     *
     * @param string $columnName
     * @return $this
     */
    public function string(string $columnName): self
    {
        $this->columns[] = "$columnName VARCHAR(255)";
        return $this;
    }

    /**
     * Define a TEXT column
     *
     * @param string $columnName
     * @return $this
     */
    public function text(string $columnName): self
    {
        $this->columns[] = "$columnName TEXT";
        return $this;
    }

    /**
     * Define an ENUM column
     *
     * @param string $columnName
     * @param array $values
     * @return $this
     */
    public function enum(string $columnName, array $values): self
    {
        $this->columns[] = "$columnName ENUM('" . implode("', '", $values) . "')";
        return $this;
    }

    /**
     * Define a datetime column
     *
     * @param string $columnName
     */
    public function datetime($columnName)
    {
        $this->columns[] = "$columnName DATETIME";
    }

    /**
     * Define a default value for a column
     *
     * @param string $columnName
     * @param mixed $defaultValue
     * @return $this
     */
    public function default(string $columnName, mixed $defaultValue): self
    {
        $value = is_string($defaultValue) ? "'$defaultValue'" : $defaultValue;
        $this->columns[] = "$columnName DEFAULT $value";
        return $this;
    }

    /**
     * Define a foreign key relationship
     *
     * @param string $columnName
     * @param string $referencedTable
     * @param string $referencedColumn
     * @return $this
     */
    public function foreign(string $columnName, string $referencedTable, string $referencedColumn): self
    {
        $this->foreignKeys[] = "FOREIGN KEY ($columnName) REFERENCES $referencedTable($referencedColumn)";
        return $this;
    }

    /**
     * Generate the SQL string to create the table
     *
     * @return string
     */
    public function createSQL(): string
    {
        $sql = "CREATE TABLE IF NOT EXISTS {$this->tableName} (";
        $sql .= implode(", ", $this->columns);

        if ($this->primaryKey) {
            $sql .= ", PRIMARY KEY ({$this->primaryKey})";
        }

        if (!empty($this->foreignKeys)) {
            $sql .= ", " . implode(", ", $this->foreignKeys);
        }

        $sql .= ")";
        return $sql;
    }

    /**
     * Execute the SQL query to create the table
     *
     * @return void
     */
    public function runQuery(): void
    {
        try {
            $pdo = $this->db->getPDO();
            $sql = $this->createSQL();
            $pdo->exec($sql);
            echo "Table '{$this->tableName}' created successfully.\n";
        } catch (\PDOException $e) {
            echo "Error creating table '{$this->tableName}': " . $e->getMessage() . "\n";
        }
    }
}
