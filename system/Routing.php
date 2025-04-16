<?php

namespace System;

class Routing {
    protected static array $routes = [];
    protected static string $cacheFile = 'cache/routes.cache.php';
    protected static string $routeFile = 'config/routes.php';

    public function __construct() {
        self::loadRoutesFromCacheOrFile();
    }

    public static function get(string $uri, string $action, array $middleware = []): void {
        self::route($uri, $action, $middleware, 'GET');
    }

    public static function post(string $uri, string $action, array $middleware = []): void {
        self::route($uri, $action, $middleware, 'POST');
    }

    public static function put(string $uri, string $action, array $middleware = []): void {
        self::route($uri, $action, $middleware, 'PUT');
    }

    public static function delete(string $uri, string $action, array $middleware = []): void {
        self::route($uri, $action, $middleware, 'DELETE');
    }

    public static function route(string $uri, string $action, array $middleware = [], string $method = 'GET'): void {
        [$controller, $methodName] = explode('@', $action);
        self::$routes[strtoupper($method)][$uri] = [
            'controller' => $controller,
            'method' => $methodName,
            'middleware' => $middleware
        ];
    }

    protected static function loadRoutesFromCacheOrFile(): void {
        $hash = hash_file('md5', self::$routeFile);

        if (file_exists(self::$cacheFile)) {
            $cached = include self::$cacheFile;
            if (isset($cached['hash']) && $cached['hash'] === $hash) {
                self::$routes = $cached['routes'];
                return;
            }
        }

        require self::$routeFile;

        if (!is_dir('cache')) {
            mkdir('cache', 0755, true);
        }

        file_put_contents(self::$cacheFile, '<?php return ' . var_export([
                'hash' => $hash,
                'routes' => self::$routes
            ], true) . ';');
    }

    public function handle(): void {
        $urlParts = isset($_GET['url']) ? explode('/', trim($_GET['url'], '/')) : [];
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        if (empty($urlParts[0])) {
            $this->handleLanding();
            return;
        }

        $route = $this->matchRoute($method, $urlParts);

        if ($route) {
            $controllerName = $route['controller'];
            $methodName = $route['method'];
            $params = $route['params'];
            $middleware = $route['middleware'] ?? [];

            $this->executeMiddleware($middleware, function() use ($controllerName, $methodName, $params) {
                $this->executeController($controllerName, $methodName, $params);
            });
        } else {
            $this->handleDynamicRoute($urlParts);
        }
    }

    protected function matchRoute(string $httpMethod, array $urlParts): ?array {
        $methodRoutes = self::$routes[strtoupper($httpMethod)] ?? [];

        foreach ($methodRoutes as $route => $action) {
            $routeParts = explode('/', trim($route, '/'));

            if (count($routeParts) === count($urlParts)) {
                $params = [];
                $match = true;

                foreach ($routeParts as $i => $part) {
                    if (preg_match('/^{\w+}$/', $part)) {
                        $params[] = $urlParts[$i];
                    } elseif ($part !== $urlParts[$i]) {
                        $match = false;
                        break;
                    }
                }

                if ($match) {
                    return [
                        'controller' => $action['controller'],
                        'method' => $action['method'],
                        'params' => $params,
                        'middleware' => $action['middleware'] ?? []
                    ];
                }
            }
        }

        return null;
    }

    private function handleLanding(): void {
        $this->executeController('Pages', 'index');
    }

    private function handleDynamicRoute(array $url): void {
        $controllerName = ucfirst($url[0]);
        $method = $url[1] ?? 'index';
        $params = array_slice($url, 2);

        $this->executeController($controllerName, $method, $params);
    }

    private function executeController(string $controllerName, string $methodName, array $params = []): void {
        $controllerFile = "app/controller/{$controllerName}.php";
        $controllerClass = "App\\Controller\\{$controllerName}";

        if (!file_exists($controllerFile)) {
            echo "Controller file $controllerFile not found<br>";
            return;
        }

        require_once $controllerFile;

        if (!class_exists($controllerClass)) {
            echo "Class $controllerClass not found<br>";
            return;
        }

        $controller = new $controllerClass;

        if (!method_exists($controller, $methodName)) {
            echo "Method $methodName not found in controller $controllerClass<br>";
            return;
        }

        call_user_func_array([$controller, $methodName], $params);
    }

    private function executeMiddleware(array $middleware, callable $next): void {
        $request = $_SERVER;

        if (empty($middleware)) {
            $next();
            return;
        }

        $middlewareIndex = 0;

        $this->callMiddleware($middleware, $middlewareIndex, $request, $next);
    }

    private function callMiddleware(array $middleware, int $index, array $request, callable $next): void {
        if ($index >= count($middleware)) {
            $next();
            return;
        }

        $middlewareClass = 'Middleware\\' . $middleware[$index];

        if (!class_exists($middlewareClass)) {
            echo "Middleware class $middlewareClass not found<br>";
            return;
        }

        $middlewareInstance = new $middlewareClass;
        $middlewareInstance->handle($request, function() use ($middleware, $index, $request, $next) {
            $this->callMiddleware($middleware, $index + 1, $request, $next);
        });
    }
}
