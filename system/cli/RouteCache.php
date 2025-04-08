<?php

namespace System\Cli;

class RouteCache {
    public function run() {
        $routes = include 'config/routes.php';

        if (!is_dir('storage/cache')) {
            mkdir('storage/cache', 0777, true);
        }

        $cacheFile = 'storage/cache/routes.cache.php';
        $content = "<?php\nreturn " . var_export($routes, true) . ";\n";

        file_put_contents($cacheFile, $content);

        echo "Route cache created: {$cacheFile}\n";
    }
}