<?php

namespace System;

/**
 * Class ServiceLoader
 * --------------------
 * Responsible for loading and registering service providers from the configuration file.
 * It dynamically initializes and stores all the services defined in config/services.php.
 */
class ServiceLoader
{
    /**
     * @var array $services
     * Stores the list of instantiated service provider objects.
     */
    protected $services = [];

    /**
     * Loads and registers all service providers defined in the config file.
     *
     * This method:
     * 1. Loads an array of service provider class names from config/services.php.
     * 2. Checks if each class exists and instantiates it.
     * 3. If the service provider has a 'register' method, it will call it.
     * 4. Stores the initialized provider instance in the $services array.
     */
    public function load()
    {
        $providers = require __DIR__ . '/../config/services.php';

        foreach ($providers as $providerClass) {
            if (class_exists($providerClass)) {
                $provider = new $providerClass();
                if (method_exists($provider, 'register')) {
                    $provider->register();
                }
                $this->services[] = $provider;
            }
        }
    }

    /**
     * Returns the list of all loaded service provider instances.
     *
     * @return array
     */
    public function getServices()
    {
        return $this->services;
    }
}
