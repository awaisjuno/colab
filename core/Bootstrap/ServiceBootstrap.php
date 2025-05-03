<?php

namespace Core\Bootstrap;

use System\ServiceLoader;

class ServiceBootstrap
{
    public static function load(): void
    {
        $loader = new ServiceLoader();
        $loader->load();
    }
}
