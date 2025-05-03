<?php

require_once __DIR__ . '/vendor/autoload.php';

use System\Logging\BugLogger;

// Optional: Logging setup
$logger = new BugLogger();

try {
    echo "Starting background process..." . PHP_EOL;

    // Example: Simulate some background work
    for ($i = 1; $i <= 5; $i++) {
        // Do any long-running task here, like checking queue, cleaning, syncing etc.
        echo "Processing item {$i}..." . PHP_EOL;
        sleep(1); // Simulate time-consuming task
    }

    echo "Background process completed." . PHP_EOL;
    $logger->log("Background process finished successfully.");

} catch (Throwable $e) {
    echo "Error occurred: " . $e->getMessage();
    $logger->log("Error in backgroundprocess.php: " . $e->getMessage());
}
