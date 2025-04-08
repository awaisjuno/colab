<?php

namespace System\Cli;

class RouteClear {
    public function run() {
        $cacheFile = 'storage/cache/routes.cache.php';

        if (file_exists($cacheFile)) {
            unlink($cacheFile);
            echo "Route cache cleared.\n";
        } else {
            echo "No cache file found.\n";
        }
    }
}