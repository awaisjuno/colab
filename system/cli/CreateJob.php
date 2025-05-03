<?php

namespace System\Cli;

class CreateJob
{
    private $jobName;

    public function __construct($jobName)
    {
        $this->jobName = ucfirst($jobName);
    }

    public function execute()
    {
        // Define the job directory
        $jobDir = ROOT_DIR . 'app/jobs/';

        // Ensure the job directory exists
        if (!is_dir($jobDir)) {
            echo "Job directory does not exist. Creating...\n";
            mkdir($jobDir, 0777, true);
        }

        $fileName = $jobDir . $this->jobName . '.php';

        // Check if the job file already exists
        if (file_exists($fileName)) {
            echo "Job file '{$fileName}' already exists.\n";
            return;
        }

        // Job class template
        $template = "<?php\n\n";
        $template .= "namespace App\\Jobs;\n\n";
        $template .= "/**\n";
        $template .= " * Class {$this->jobName}\n";
        $template .= " *\n";
        $template .= " * Job to handle specific task logic.\n";
        $template .= " */\n";
        $template .= "class {$this->jobName}\n";
        $template .= "{\n";
        $template .= "    /**\n";
        $template .= "     * Handle the job logic.\n";
        $template .= "     *\n";
        $template .= "     * @param array \$data\n";
        $template .= "     *\n";
        $template .= "     * @return void\n";
        $template .= "     */\n";
        $template .= "    public function handle(array \$data): void\n";
        $template .= "    {\n";
        $template .= "        echo \"Processing job with data: \" . json_encode(\$data);\n";
        $template .= "        // Your job logic here\n";
        $template .= "    }\n";
        $template .= "}\n";

        file_put_contents($fileName, $template);
        echo "Job class '{$fileName}' created successfully.\n";
    }
}
