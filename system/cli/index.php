<?php

define('ROOT_DIR', __DIR__ . '/../../');

require_once ROOT_DIR . 'vendor/autoload.php';

$commands = require_once ROOT_DIR . 'config/commands.php';

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
    foreach ($commands as $key => $value) {
        echo " - $key\n";
    }
    exit;
}

$commandClass = $commands[$command];

if (!class_exists($commandClass)) {
    echo "Error: Command class '{$commandClass}' not found.\n";
    exit;
}

try {
    $reflection = new ReflectionClass($commandClass);

    // Create instance based on constructor args
    $instance = $reflection->getConstructor()
        ? $reflection->newInstanceArgs($arguments)
        : $reflection->newInstance();

    if (method_exists($instance, 'execute')) {
        $instance->execute();
    } elseif (method_exists($instance, 'run')) {
        $instance->run();
    } else {
        echo "Error: No executable method found in '{$commandClass}'.\n";
    }

} catch (Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
