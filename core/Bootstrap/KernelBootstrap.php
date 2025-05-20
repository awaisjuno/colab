<?php

namespace Core\Bootstrap;

use System\Cli\Kernel;

class KernelBootstrap
{
    /**
     * Bootstraps and handles the CLI application.
     *
     * @param array $argv
     * @param int $argc
     * @return void
     */
    public static function handle(array $argv, int $argc): void
    {
        Kernel::handle($argv, $argc);
    }
}
