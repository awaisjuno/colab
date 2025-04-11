<?php

namespace App\Middleware;

class Authenticate
{
    public function handle($request, $next)
    {
        echo "Authenticating...\n";
        $next($request);
    }
}