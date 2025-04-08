<?php

namespace System\Cli;

class CreateCustomCommand
{
    private $commandName;

    public function __construct($commandName)
    {
        $this->commandName = ucfirst($commandName);
    }

    public function execute()
    {
        // Define the controller directory
        $CustomCommandDir = ROOT_DIR . 'app/console/';

        // Ensure the controller directory exists
        if (!is_dir($CustomCommandDir)) {
            echo "Error: Controller directory does not exist. Creating...\n";
            mkdir($CustomCommandDir, 0777, true);
        }

        $fileName = $CustomCommandDir . $this->commandName . '.php';

        // Check if the controller file already exists
        if (file_exists($fileName)) {
            echo "Controller file '{$fileName}' already exists.\n";
            return;
        }

        // Controller template with placeholders for the controller name
        $template = "<?php\n\n";
        $template .= "namespace Controller;\n";
        $template .= "use System\\Loader;\n\n";
        $template .= "class {$this->commandName} extends Loader\n";
        $template .= "{\n";
        $template .= "    protected \$load;\n\n";
        $template .= "    public function __construct()\n";
        $template .= "    {\n";
        $template .= "        parent::__construct();\n";
        $template .= "    }\n\n";
        $template .= "    public function index()\n";
        $template .= "    {\n";
        $template .= "        // Add your code for the index method here\n";
        $template .= "    }\n";
        $template .= "}\n";

        file_put_contents($fileName, $template);
        echo "Controller file '{$fileName}' created successfully.\n";
    }
}