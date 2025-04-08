<?php

namespace System;

class Processing
{
    private $routing;

    public function __construct()
    {
        $this->routing = new Routing();
        //$this->checkServiceProviders();
        $this->forwardToRouting();
    }

    private function checkServiceProviders()
    {
        echo "Checking service providers...\n";

        // Load the configuration
        $config = require ROOT_DIR . '/config.php';

        $serviceProviders = $config['service_providers'];

        foreach ($serviceProviders as $provider) {
            if (!class_exists($provider)) {
                throw new \Exception("Service provider '{$provider}' is not available.");
            }
            echo "Service provider '{$provider}' is available.\n";
        }
    }

    private function forwardToRouting()
    {
        $this->routing->handle();
    }
}
