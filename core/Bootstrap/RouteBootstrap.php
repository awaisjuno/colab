<?php

namespace Core\Bootstrap;

use System\Routing;

class RouteBootstrap
{
    public static function handle(): void
    {
        $router = new Routing();
        $router->handle();
    }
}
