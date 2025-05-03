<?php

namespace System\Helpers;

class BackgroundJobManager
{
    public static function runJob(string $script): void
    {
        $phpCommand = PHP_BINARY;
        $scriptPath = ROOT_DIR . '/cli/' . $script;

        if (strpos(PHP_OS, 'WIN') === false) {
            exec("{$phpCommand} {$scriptPath} > /dev/null 2>&1 &");
        } else {
            pclose(popen("start /B {$phpCommand} {$scriptPath}", 'r'));
        }
    }
}
