<?php

namespace Core\Bootstrap;


use System\Handlers\ErrorHandler;

class ErrorBootstrap
{
    public static function setup(): void
    {
        (new ErrorHandler())->setup();
    }
}
