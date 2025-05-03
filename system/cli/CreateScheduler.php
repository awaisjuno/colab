<?php

namespace System\Cli;

class CreateScheduler
{
    private $schedulerName;

    public function __construct($schedulerName)
    {
        $this->schedulerName = ucfirst($schedulerName);
    }

    public function execute()
    {
        $schedulerDir = ROOT_DIR . 'app/schedulers/';

        // Create the directory if it doesn't exist
        if (!is_dir($schedulerDir)) {
            echo "Creating scheduler directory at '{$schedulerDir}'...\n";
            mkdir($schedulerDir, 0777, true);
        }

        $fileName = $schedulerDir . $this->schedulerName . '.php';

        if (file_exists($fileName)) {
            echo "Scheduler file '{$fileName}' already exists.\n";
            return;
        }

        // Scheduler template
        $template = "<?php\n\n";
        $template .= "namespace App\\Schedulers;\n\n";
        $template .= "/**\n";
        $template .= " * Class {$this->schedulerName}\n";
        $template .= " *\n";
        $template .= " * This scheduler defines when to run a job.\n";
        $template .= " */\n";
        $template .= "class {$this->schedulerName}\n";
        $template .= "{\n";
        $template .= "    /**\n";
        $template .= "     * Run the scheduler task.\n";
        $template .= "     *\n";
        $template .= "     * @return void\n";
        $template .= "     */\n";
        $template .= "    public function run(): void\n";
        $template .= "    {\n";
        $template .= "        // Example logic for when to run a job\n";
        $template .= "        echo \"Running scheduler: {$this->schedulerName}\\n\";\n";
        $template .= "    }\n";
        $template .= "}\n";

        file_put_contents($fileName, $template);
        echo "Scheduler file '{$fileName}' created successfully.\n";
    }
}
