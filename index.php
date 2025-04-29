<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

define('ROOT_DIR', __DIR__);

require_once ROOT_DIR . '/vendor/autoload.php';

use System\Processing;

// Report all PHP errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Catch Fatal Errors also
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error !== null) {
        echo "<h1>Fatal Error</h1>";
        echo "<pre>" . print_r($error, true) . "</pre>";
    }
});


try {
    $processing = new Processing();
} catch (Throwable $e) {
    echo "<h1>Something went wrong!</h1>";
    echo "<pre>" . $e->getMessage() . "</pre>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
