<?php

namespace System\Cli;

use System\Model;

class CreateModel
{
    private $modelName;

    public function __construct($modelName)
    {
        $this->modelName = $modelName;
    }

    public function execute()
    {
        // Define the models directory
        $modelDir = ROOT_DIR . 'app/model/';

        // Create the directory if it doesn't exist
        if (!is_dir($modelDir)) {
            echo "Error: Models directory does not exist. Creating...\n";
            mkdir($modelDir, 0777, true);
        }

        // Define the model file path
        $modelFile = $modelDir . ucfirst($this->modelName) . '.php';

        // Check if the model file already exists
        if (file_exists($modelFile)) {
            echo "Error: Model file '{$modelFile}' already exists.\n";
            return;
        }

        // Generate the model content based on the model name
        $modelTemplate = "<?php\n\n";
        $modelTemplate .= "namespace App\\Model;\n\n";
        $modelTemplate .= "use System\\Model;\n\n";
        $modelTemplate .= "class " . ucfirst($this->modelName) . " extends Model\n{\n";
        $modelTemplate .= "    protected \$table = '" . strtolower($this->modelName) . "';\n";
        $modelTemplate .= "    protected \$primaryKey = 'id';\n";
        $modelTemplate .= "    protected \$timestamps = true;\n\n";
        $modelTemplate .= "    public function __construct()\n";
        $modelTemplate .= "    {\n";
        $modelTemplate .= "        parent::__construct();\n";
        $modelTemplate .= "    }\n\n";
        $modelTemplate .= "    public function insertData(array \$data)\n";
        $modelTemplate .= "    {\n";
        $modelTemplate .= "        return \$this->insert(\$this->table, \$data);\n";
        $modelTemplate .= "    }\n\n";
        $modelTemplate .= "    public function updateData(\$id, array \$data)\n";
        $modelTemplate .= "    {\n";
        $modelTemplate .= "        return \$this->update(\$this->table, \$data, ['id' => \$id]);\n";
        $modelTemplate .= "    }\n\n";
        $modelTemplate .= "    public function deleteData(\$id)\n";
        $modelTemplate .= "    {\n";
        $modelTemplate .= "        return \$this->delete(\$this->table, ['id' => \$id]);\n";
        $modelTemplate .= "    }\n\n";
        $modelTemplate .= "    public function selectData(array \$conditions = [])\n";
        $modelTemplate .= "    {\n";
        $modelTemplate .= "        return \$this->select(\$this->table, \$conditions);\n";
        $modelTemplate .= "    }\n\n";
        $modelTemplate .= "    public function findData(\$id)\n";
        $modelTemplate .= "    {\n";
        $modelTemplate .= "        return \$this->select(\$this->table, ['id' => \$id])->first();\n";
        $modelTemplate .= "    }\n";
        $modelTemplate .= "}\n";

        // Write the model template to the file
        file_put_contents($modelFile, $modelTemplate);

        echo "Model file '{$modelFile}' created successfully.\n";
    }
}