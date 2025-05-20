<?php

namespace System\Cli;

class Kernel
{
    public static function handle(array $argv, int $argc): void
    {
        $commands = require ROOT_DIR . '/config/commands.php';

        if ($argc < 2) {
            echo "Usage: php cli [command] [arguments]\n";
            echo "Tip: Use `php cli list` to see available commands.\n";
            exit;
        }

        $command = $argv[1];
        $arguments = array_slice($argv, 2);

        if (!isset($commands[$command])) {
            echo "Error: Command '{$command}' not recognized.\n";
            echo "Available commands:\n";
            foreach (array_keys($commands) as $cmd) {
                echo " - {$cmd}\n";
            }
            exit;
        }

        $commandClass = $commands[$command];

        if (!class_exists($commandClass)) {
            echo "Error: Command class '{$commandClass}' not found.\n";
            exit;
        }

        try {
            $reflection = new \ReflectionClass($commandClass);
            $constructor = $reflection->getConstructor();

            // Check if constructor exists and has required parameters
            if ($constructor && $constructor->getNumberOfRequiredParameters() > count($arguments)) {
                $expected = $constructor->getNumberOfRequiredParameters();
                echo "Error: Command requires {$expected} argument(s), " . count($arguments) . " given.\n";
                exit;
            }

            // Create instance with arguments
            $instance = $constructor
                ? $reflection->newInstanceArgs($arguments)
                : $reflection->newInstance();

            // Execute appropriate method
            if (method_exists($instance, 'execute')) {
                $instance->execute();
            } elseif (method_exists($instance, 'run')) {
                $instance->run();
            } else {
                echo "Error: No executable method ('execute' or 'run') found in '{$commandClass}'.\n";
            }
        } catch (\Throwable $e) {
            echo "Exception: " . $e->getMessage() . "\n";
        }
    }
}
