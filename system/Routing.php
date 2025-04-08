<?php

namespace System;

class Routing {
    protected array $routes = [];

    public function __construct() {
        $routeFile = 'config/routes.php';
        $cacheFile = 'cache/routes.cache.php';

        $hash = hash_file('md5', $routeFile);

        // Load from cache if it exists and hash matches
        if (file_exists($cacheFile)) {
            $cached = include $cacheFile;

            if (isset($cached['hash']) && $cached['hash'] === $hash) {
                $this->routes = $cached['routes'];
                return;
            }
        }

        // If cache doesn't exist or hash doesn't match, regenerate cache
        $this->routes = include $routeFile;

        // Make sure the cache directory exists
        if (!is_dir('cache')) {
            mkdir('cache', 0755, true);
        }

        // Save new cache
        file_put_contents($cacheFile, '<?php return ' . var_export([
                'hash' => $hash,
                'routes' => $this->routes
            ], true) . ';');
    }

    public function handle(): void {
        $url = isset($_GET['url']) ? explode('/', trim($_GET['url'], '/')) : [];

        if (empty($url[0])) {
            $this->handleLanding();
            return;
        }

        $route = $this->matchRoute($url);

        if ($route) {
            $controllerName = $route['controller'];
            $method = $route['method'];
            $params = $route['params'];

            $controllerFile = "app/controller/{$controllerName}Controller.php";

            if (file_exists($controllerFile)) {
                require_once $controllerFile;

                $controllerNameWithNamespace = 'App\Controller\\' . $controllerName . 'Controller';

                if (class_exists($controllerNameWithNamespace)) {
                    $controller = new $controllerNameWithNamespace;

                    if (method_exists($controller, $method)) {
                        call_user_func_array([$controller, $method], $params);
                        return;
                    }

                    echo "Method $method not found in controller $controllerName<br>";
                } else {
                    echo "Class $controllerNameWithNamespace not found<br>";
                }
            } else {
                echo "Controller file $controllerFile not found<br>";
            }
        } else {
            $this->handleDynamicRoute($url);
        }
    }

    private function handleLanding(): void {
        $controllerName = 'Pages';
        $method = 'index';

        $controllerFile = "app/controller/{$controllerName}.php";

        if (file_exists($controllerFile)) {
            require_once $controllerFile;

            $controllerNameWithNamespace = 'App\Controller\\' . $controllerName;

            if (class_exists($controllerNameWithNamespace)) {
                $controller = new $controllerNameWithNamespace;

                if (method_exists($controller, $method)) {
                    call_user_func_array([$controller, $method], []);
                    return;
                }
            }
        }

        echo "Landing page controller or method not found<br>";
    }

    private function handleDynamicRoute(array $url): void {
        $controllerName = ucfirst($url[0]);
        $method = isset($url[1]) ? $url[1] : 'index';
        $params = array_slice($url, 2);

        $controllerFile = "app/controller/{$controllerName}.php";

        if (file_exists($controllerFile)) {
            require_once $controllerFile;

            $controllerNameWithNamespace = 'App\Controller\\' . $controllerName;

            if (class_exists($controllerNameWithNamespace)) {
                $controller = new $controllerNameWithNamespace;

                if (method_exists($controller, $method)) {
                    call_user_func_array([$controller, $method], $params);
                    return;
                } else {
                    echo "Method $method not found in controller $controllerName<br>";
                }
            } else {
                echo "Class $controllerNameWithNamespace not found<br>";
            }
        } else {
            echo "Controller file $controllerFile not found<br>";
        }
    }

    protected function matchRoute(array $url): ?array {
        foreach ($this->routes as $route => $action) {
            $routeParts = explode('/', trim($route, '/'));
            $urlParts = $url;

            if (count($routeParts) === count($urlParts)) {
                $params = [];
                $match = true;

                foreach ($routeParts as $index => $part) {
                    if (strpos($part, '{') === false) {
                        if ($part !== $urlParts[$index]) {
                            $match = false;
                            break;
                        }
                    } else {
                        $params[] = $urlParts[$index];
                    }
                }

                if ($match) {
                    return [
                        'controller' => $action['controller'],
                        'method' => $action['method'],
                        'params' => $params
                    ];
                }
            }
        }

        return null;
    }
}
