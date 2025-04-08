<?php

namespace System\Cli;

use System\Database\Schema;

class  CreateMigration
{
    private $migrationName;

    public function __construct($migrationName)
    {
        $this->migrationName = $migrationName;
    }

    public function execute()
    {
        // Define the migration directory
        $migrationDir = ROOT_DIR . 'app/migrations/';

        if (!is_dir($migrationDir)) {
            echo "Error: Migrations directory does not exist. Creating...\n";
            mkdir($migrationDir, 0777, true);
        }

        $fileName = $migrationDir . $this->migrationName . '.php';

        if (file_exists($fileName)) {
            echo "Migration file '{$fileName}' already exists.\n";
            return;
        }

        // Start building the migration file content
        $template = "<?php\n\n";
        $template .= "use System\\Database\\Schema;\n\n";
        $template .= "class {$this->migrationName}\n{\n";
        $template .= "    public function up()\n";
        $template .= "    {\n";
        $template .= "        Schema::table('table_name', function(\$table) {\n";
        $template .= "            \$table->int('id', true);  // AutoIncrement\n";
        $template .= "            \$table->string('first_name');\n";
        $template .= "            \$table->string('last_name');\n";
        $template .= "        });\n";
        $template .= "    }\n\n";
        $template .= "    public function down()\n";
        $template .= "    {\n";
        $template .= "        // Add rollback logic here\n";
        $template .= "    }\n";
        $template .= "}\n";

        file_put_contents($fileName, $template);
        echo "Migration file '{$fileName}' created successfully.\n";
    }
}