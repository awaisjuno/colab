<?php

namespace System;

/**
 * Class Routing
 * -------------
 * Handles route registration, matching, execution, and middleware support.
 * It supports route caching to improve performance and fallback routing for dynamic URL resolution.
 */
class Routing {
    /**
     * @var array Stores all defined routes categorized by HTTP method.
     */
    protected static array $routes = [];

    /**
     * @var string File path for storing cached routes.
     */
    protected static string $cacheFile = 'cache/routes.cache.php';

    /**
     * @var string Path to the route definition file.
     */
    protected static string $routeFile = 'config/routes.php';

    /**
     * Constructor: Initializes the router and loads routes (from cache if possible).
     */
    public function __construct() {
        self::loadRoutesFromCacheOrFile();
    }

    // ---------------------- Route Registration Methods ----------------------

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

    /**
     * Registers a route for a specific HTTP method.
     */
    public static function route(string $uri, string $action, array $middleware = [], string $method = 'GET'): void {
        [$controller, $methodName] = explode('@', $action);
        self::$routes[strtoupper($method)][$uri] = [
            'controller' => $controller,
            'method' => $methodName,
            'middleware' => $middleware
        ];
    }

    /**
     * Loads routes either from the cache (if hash matches) or from the original file.
     */
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

        // Save new route cache with file hash
        file_put_contents(self::$cacheFile, '<?php return ' . var_export([
                'hash' => $hash,
                'routes' => self::$routes
            ], true) . ';');
    }

    // ---------------------- Request Handling ----------------------

    /**
     * Main entry point to handle the incoming request.
     */
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

    /**
     * Tries to match the given URL and method with a registered route.
     * Supports dynamic parameters in routes (e.g., /user/{id}).
     */
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

    /**
     * Handles default landing page route when URL is empty.
     */
    private function handleLanding(): void {
        $this->executeController('Pages', 'index');
    }

    /**
     * Fallback for dynamically resolving controller and method from URL segments.
     */
    private function handleDynamicRoute(array $url): void {
        $controllerName = ucfirst($url[0]);
        $method = $url[1] ?? 'index';
        $params = array_slice($url, 2);

        $this->executeController($controllerName, $method, $params);
    }

    /**
     * Executes the controller method with passed parameters.
     */
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

    // ---------------------- Middleware ----------------------

    /**
     * Executes middleware chain before controller execution.
     */
    private function executeMiddleware(array $middleware, callable $next): void {
        $request = $_SERVER;

        if (empty($middleware)) {
            $next();
            return;
        }

        $middlewareIndex = 0;

        $this->callMiddleware($middleware, $middlewareIndex, $request, $next);
    }

    /**
     * Recursively processes middleware one by one.
     */
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
