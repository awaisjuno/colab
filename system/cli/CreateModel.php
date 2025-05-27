<?php

namespace System\Cli;

use System\Model;

class CreateModel
{
    private string $modelName;

    public function __construct(string $modelName)
    {
        $this->modelName = $modelName;
    }

    public function execute(): void
    {
        $modelDir = ROOT_DIR . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Model' . DIRECTORY_SEPARATOR;

        if (!is_dir($modelDir)) {
            echo "Models directory does not exist. Creating...\n";
            mkdir($modelDir, 0777, true);
        }

        $modelFile = $modelDir . ucfirst($this->modelName) . '.php';

        if (file_exists($modelFile)) {
            echo "Error: Model file '{$modelFile}' already exists.\n";
            return;
        }

        $tableName = strtolower($this->modelName);

        $modelTemplate = <<<PHP
<?php

namespace App\Model;

use System\Model;

/**
 * Class {$this->modelName}
 *
 * Model for the '{$tableName}' table.
 */
class {$this->modelName} extends Model
{
    /**
     * The table associated with the model.
     */
    protected string \$tableName = '{$tableName}';

    /**
     * The primary key for the table.
     */
    protected string \$primaryKey = 'id';

    /**
     * Indicates if the model should manage timestamps.
     */
    protected bool \$timestamps = true;

    /**
     * {$this->modelName} constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Insert data into the table.
     *
     * @param array \$data
     * @return bool
     */
    public function insertData(array \$data): bool
    {
        return \$this->insert(\$this->tableName, \$data);
    }

    /**
     * Update a record by ID.
     *
     * @param int|string \$id
     * @param array \$data
     * @return bool
     */
    public function updateData(\$id, array \$data): bool
    {
        return \$this->update(\$this->tableName, \$data, [\$this->primaryKey => \$id]);
    }

    /**
     * Delete a record by ID.
     *
     * @param int|string \$id
     * @return bool
     */
    public function deleteData(\$id): bool
    {
        return \$this->delete(\$this->tableName, [\$this->primaryKey => \$id]);
    }

    /**
     * Select records with optional conditions.
     *
     * @param array \$conditions
     * @param int|null \$limit
     * @param string|null \$orderBy
     * @return array
     */
    public function selectData(array \$conditions = [], ?int \$limit = null, ?string \$orderBy = null): array
    {
        return \$this->select(\$this->tableName, \$conditions, \$limit, \$orderBy);
    }

    /**
     * Find a single record by ID.
     *
     * @param int|string \$id
     * @return array|null
     */
    public function findData(\$id): ?array
    {
        return \$this->selectOne(\$this->tableName, [\$this->primaryKey => \$id]);
    }
}
PHP;

        file_put_contents($modelFile, $modelTemplate);

        echo "Model file '{$modelFile}' created successfully.\n";
    }
}
