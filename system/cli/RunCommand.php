<?php

namespace System\Cli;

class RunCommand
{
    public function __construct()
    {
    }

    public function execute($commandName = null)
    {
        $consoleDir = ROOT_DIR . 'app/console/';

        if (!is_dir($consoleDir)) {
            echo "Error: Console directory does not exist.\n";
            return;
        }

        $consoleFiles = glob($consoleDir . '*.php');

        if (empty($consoleFiles)) {
            echo "No commands found.\n";
            return;
        }

        if ($commandName) {
            $consoleFile = $consoleDir . $commandName . '.php';

            if (file_exists($consoleFile)) {
                require_once $consoleFile;

                $className = '\\Controller\\' . $commandName;

                if (class_exists($className)) {
                    $consoleInstance = new $className();

                    if (method_exists($consoleInstance, 'execute')) {
                        echo "Running Command: {$commandName}...\n";
                        $consoleInstance->execute();
                    } else {
                        echo "Command {$className} does not have an 'execute' method.\n";
                    }
                } else {
                    echo "Class {$className} not found in {$consoleFile}.\n";
                }
            } else {
                echo "Command file {$commandName}.php not found.\n";
            }
        } else {
            foreach ($consoleFiles as $consoleFile) {
                require_once $consoleFile;

                $className = '\\Controller\\' . pathinfo($consoleFile, PATHINFO_FILENAME);

                if (class_exists($className)) {
                    $consoleInstance = new $className();

                    if (method_exists($consoleInstance, 'execute')) {
                        echo "Running Command: {$className}...\n";
                        $consoleInstance->execute();
                    } else {
                        echo "Command {$className} does not have an 'execute' method.\n";
                    }
                } else {
                    echo "Class {$className} not found in {$consoleFile}.\n";
                }
            }
        }

        echo "Commands executed successfully.\n";
    }
}
