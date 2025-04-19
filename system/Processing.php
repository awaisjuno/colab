<?php

namespace System;

use System\Handlers\ErrorHandler;
use System\lib\Container;
use System\ServiceLoader;
use System\Routing;
use System\Helpers\EnvLoader;


/**
 * Processing class initializes core components of the system.
 * It sets up the environment, error handling, container, and routing.
 */
class Processing
{
    private Routing $routing;
    private ServiceLoader $serviceLoader;
    private Container $container;

    /**
     * Constructor method to initialize the system.
     * It loads the environment, sets up error handling, initializes services,
     * and forwards the request to the routing system.
     */
    public function __construct()
    {
        EnvLoader::load(\ROOT_DIR . '/.env');
        (new ErrorHandler())->setup();
        $this->container = new Container();
        $this->loadServices();
        $this->routing = new Routing();
        $this->forwardToRouting();
    }

    /**
     * Load services required by the system.
     * This is a separate method to ensure that services are loaded
     * in a modular and efficient way.
     */
    private function loadServices(): void
    {
        $this->serviceLoader = new ServiceLoader();
        $this->serviceLoader->load();
    }

    /**
     * Forward the request to the routing system.
     * This method calls the handle() method of the Routing class to
     * process the incoming request and route it to the correct controller.
     */
    private function forwardToRouting(): void
    {
        $this->routing->handle();
    }
}