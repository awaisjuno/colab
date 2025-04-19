<?php

namespace System\Logging;

use Exception;
use System\Database\Connection;

class BugLogger
{
    private string $logDir = \ROOT_DIR . '/storage/logs';

    public function log(string $message): void
    {
        $this->writeToFile($message);
    }

    private function writeToFile(string $message): void
    {
        if (!is_dir($this->logDir)) {
            mkdir($this->logDir, 0755, true);
        }

        $formatted = '[' . date('Y-m-d H:i:s') . '] ' . $message . PHP_EOL;
        file_put_contents($this->logDir . '/errors.log', $formatted, FILE_APPEND);
    }

}
