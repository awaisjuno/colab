<?php

namespace System;

class ServiceLoader
{
    protected $services = [];

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

    public function getServices()
    {
        return $this->services;
    }
}
