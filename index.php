<?php

define('ROOT_DIR', __DIR__);

require_once ROOT_DIR . '/vendor/autoload.php';

use System\Processing;

try {
    $processing = new Processing();
} catch (Throwable $e) {
    echo "<h1>Something went wrong!</h1>";
    echo "<pre>" . $e->getMessage() . "</pre>";
}
