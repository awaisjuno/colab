<?php

namespace System\Cli;

/**
 * Class CreateTask
 *
 * CLI utility to create a new Task class under the App\Tasks namespace.
 *
 * @package System\Cli
 */
class CreateTask
{
    /**
     * @var string $taskName The name of the task class to create.
     */
    private $taskName;

    /**
     * CreateTask constructor.
     *
     * @param string $taskName The name of the task.
     */
    public function __construct($taskName)
    {
        $this->taskName = ucfirst($taskName);
    }

    /**
     * Execute the command to create a new task file.
     *
     * @return void
     */
    public function execute(): void
    {
        // Define the tasks directory
        $taskDir = ROOT_DIR . 'app/tasks/';

        // Ensure the tasks directory exists
        if (!is_dir($taskDir)) {
            echo "Error: Tasks directory does not exist. Creating...\n";
            mkdir($taskDir, 0777, true);
        }

        $fileName = $taskDir . $this->taskName . '.php';

        // Check if the task file already exists
        if (file_exists($fileName)) {
            echo "Task file '{$fileName}' already exists.\n";
            return;
        }

        // Task template with placeholders for the task name
        $template = "<?php\n\n";
        $template .= "namespace App\\Tasks;\n\n";
        $template .= "class {$this->taskName}\n";
        $template .= "{\n";
        $template .= "    public function execute()\n";
        $template .= "    {\n";
        $template .= "        echo \"Hello to Task.\"\\n\";\n";
        $template .= "    }\n";
        $template .= "}\n";

        file_put_contents($fileName, $template);
        echo "Task file '{$fileName}' created successfully.\n";
    }
}
