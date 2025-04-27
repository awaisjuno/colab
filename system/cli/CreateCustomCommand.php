<?php

namespace System\Cli;

class CreateCustomCommand
{
    private $commandName;
    private $commandTitle;
    private $commandDescription;
    private $parameters = [];

    public function __construct($commandName)
    {
        $this->commandName = ucfirst($commandName);
    }

    public function promptForDetails()
    {
        echo "Enter a title for the command: ";
        $this->commandTitle = trim(fgets(STDIN));

        echo "Enter a description for the command: ";
        $this->commandDescription = trim(fgets(STDIN));

        echo "Do you want to add parameters to this command? (y/n): ";
        $addParameters = trim(fgets(STDIN));

        if (strtolower($addParameters) == 'y') {
            $this->askForParameters();
        }
    }

    private function askForParameters()
    {
        echo "Enter the parameters (comma separated): ";
        $params = trim(fgets(STDIN));

        if ($params) {
            $this->parameters = explode(',', $params);
            $this->parameters = array_map('trim', $this->parameters);
        }
    }

    public function execute()
    {
        $this->promptForDetails();

        $CustomCommandDir = ROOT_DIR . 'app/console/';

        if (!is_dir($CustomCommandDir)) {
            echo "Error: Controller directory does not exist. Creating...\n";
            mkdir($CustomCommandDir, 0777, true);
        }

        $fileName = $CustomCommandDir . $this->commandName . '.php';

        if (file_exists($fileName)) {
            echo "Controller file '{$fileName}' already exists.\n";
            return;
        }

        $template = "<?php\n\n";
        $template .= "namespace Controller;\n\n";
        $template .= "class {$this->commandName}\n";
        $template .= "{\n";
        $template .= "    const TITLE = '{$this->commandTitle}';\n";
        $template .= "    const DESCRIPTION = '{$this->commandDescription}';\n\n";
        $template .= "    public function __construct()\n";
        $template .= "    {\n";
        $template .= "        parent::__construct();\n";
        $template .= "    }\n\n";
        $template .= "    public function index()\n";
        $template .= "    {\n";
        $template .= "        echo 'Command executed: ' . self::TITLE . '\\n';\n";

        if (!empty($this->parameters)) {
            $template .= "        echo 'Parameters: " . implode(", ", $this->parameters) . "\\n';\n";
        }

        $template .= "    }\n";

        if (!empty($this->parameters)) {
            $template .= "\n    // Parameters:\n";
            foreach ($this->parameters as $param) {
                $template .= "    const PARAM_{$param} = '{$param}';\n";
            }
        }

        $template .= "}\n";

        file_put_contents($fileName, $template);
        echo "Controller file '{$fileName}' created successfully.\n";
    }
}
