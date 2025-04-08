<?php

namespace System\Cli;

class CreateMiddleware
{
    private $middlewareName;

    public function __construct($middlewareName)
    {
        $this->middlewareName = ucfirst($middlewareName);
    }

    public function execute()
    {
        $middlewareDir = ROOT_DIR . 'app/middleware/';

        if (!is_dir($middlewareDir)) {
            echo "Error: Middleware directory does not exist. Creating...\n";
            mkdir($middlewareDir, 0777, true);
        }

        $fileName = $middlewareDir . $this->middlewareName . '.php';

        if (file_exists($fileName)) {
            echo "Middleware file '{$fileName}' already exists.\n";
            return;
        }

        // Middleware template with placeholders for the middleware name
        $template = "<?php\n\n";
        $template .= "namespace Middleware;\n\n";
        $template .= "class {$this->middlewareName}\n";
        $template .= "{\n";
        $template .= "    public function handle(\$request, \$next)\n";
        $template .= "    {\n";
        $template .= "        echo \"<h3>Calling Middleware.</h3>\";\n";
        $template .= "        return \$next(\$request);\n";
        $template .= "    }\n";
        $template .= "}\n";

        file_put_contents($fileName, $template);

        echo "Middleware file '{$fileName}' created successfully.\n";
    }
}