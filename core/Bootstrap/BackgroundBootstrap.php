<?php

namespace Core\Bootstrap;

use System\Helpers\BackgroundJobManager;

class BackgroundBootstrap
{
    public static function start(): void
    {
        BackgroundJobManager::runJob('scheduler.php');
        BackgroundJobManager::runJob('backgroundprocess.php');
    }
}
