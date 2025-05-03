<?php

define('ROOT_DIR', __DIR__ . '/../'); // Define ROOT_DIR for config loading

require_once ROOT_DIR . '/vendor/autoload.php';

use Core\Bootstrap\ErrorBootstrap;

// Initialize the error handler
ErrorBootstrap::setup();

// Trigger a notice-level error (undefined variable)
echo $undefinedVar;

// Trigger a warning (optional)
// include 'non_existing_file.php';

// Trigger an exception (optional)
// throw new Exception("Test exception for error handler");
