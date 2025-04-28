<?php

namespace System;

use System\Handlers\ErrorHandler;
use System\lib\Container;
use System\ServiceLoader;
use System\Routing;
use System\Helpers\EnvLoader;
use System\Handlers\ApiAuth;

/**
 * Processing class initializes core components of the system with Lazy Loading.
 */
class Processing
{
    private ?Routing $routing = null;
    private ?ServiceLoader $serviceLoader = null;
    private ?Container $container = null;

    public function __construct()
    {
        EnvLoader::load(\ROOT_DIR . '/.env');
        (new ErrorHandler())->setup();
        // Process the incoming request
        $this->processRequest();
    }

    /**
     * Lazily initialize and return the Container.
     *
     * @return Container
     */
    private function getContainer(): Container
    {
        if ($this->container === null) {
            $this->container = new Container();
        }
        return $this->container;
    }

    /**
     * Lazily load services required by the system.
     *
     * @return void
     */
    private function loadServices(): void
    {
        if ($this->serviceLoader === null) {
            $this->serviceLoader = new ServiceLoader();
            $this->serviceLoader->load();
        }
    }

    /**
     * Lazily initialize and return the Routing instance.
     *
     * @return Routing
     */
    private function getRouting(): Routing
    {
        if ($this->routing === null) {
            $this->routing = new Routing();
        }
        return $this->routing;
    }

    /**
     * Forward the request to the routing system.
     *
     * @return void
     */
    private function forwardToRouting(): void
    {
        $this->getRouting()->handle();
    }

    /**
     * Process incoming request, check if it's an API call and handle accordingly.
     *
     * @return void
     */
    private function processRequest(): void
    {
        $requestUri = $_SERVER['REQUEST_URI'];
        $baseUri = EnvLoader::get('API_URL');
        $normalizedUri = substr($requestUri, strlen($baseUri));
        $isApi = strpos($normalizedUri, '/api') === 0;

        if ($isApi) {
            //Forward Request to APIAuth
            $this->handleApiAuth($requestUri);
        }

        //Process Request
        $this->runSchedulerInBackground();
        $this->loadServices();
        $this->forwardToRouting();
        $this->runScheduler();
    }

    /**
     * Runs the scheduler in the background.
     *
     * This method executes the scheduler script asynchronously by invoking a
     * background process using the `shell_exec` function. This allows the
     * scheduler to run without blocking the main execution flow, enabling
     * other operations to proceed concurrently.
     *
     * @return void
     */
    private function runSchedulerInBackground(): void
    {
        $phpCommand = PHP_BINARY;
        $schedulerScript = \ROOT_DIR . '/cli/scheduler.php';
        $command = "$phpCommand $schedulerScript > /dev/null 2>&1 &";
        shell_exec($command);
    }

    /**
     * Runs the scheduler to process any pending tasks.
     *
     * This method creates an instance of the Scheduler class and calls its
     * `run()` method to execute tasks that are pending.
     *
     * @return void
     */
    private function runScheduler(): void
    {
        $scheduler = new Scheduler();
        $scheduler->run();
    }

    /**
     * Handle API authentication and log API hit.
     *
     * @param string $requestUri The requested URI
     *
     * @return void
     */
    private function handleApiAuth(string $requestUri): void
    {
        try {
            $headers = getallheaders();
            $token = $headers['Authorization'] ?? null;

            if (!$token) {
                throw new \Exception("Missing API token in headers.");
            }

            $token = str_replace('Bearer ', '', $token);

            $apiAuth = new ApiAuth();
            $tokenData = $apiAuth->validateToken($token);
            //$apiAuth->logHit($requestUri, $_SERVER['REMOTE_ADDR'], $tokenData['detail_id']);
        } catch (\Exception $e) {
            http_response_code(401);
            echo json_encode(['error' => $e->getMessage()]);
            exit;
        }
    }
}
