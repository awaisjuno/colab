<?php

namespace Core\Bootstrap;

use System\Helpers\EnvLoader;

class EnvBootstrap
{
    public static function load(): void
    {
        EnvLoader::load(ROOT_DIR . '/.env');
    }
}
