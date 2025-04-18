<?php

namespace System;

use PDO;
use Exception;
use System\Helpers\EnvLoader;

class Processing
{
    private $routing;
    private PDO $pdo;
    private ServiceLoader $serviceLoader;

    public function __construct()
    {
        EnvLoader::load(\ROOT_DIR . '/.env');
        $this->setupErrorReporting();
        $this->container = new Container();
        $this->setupDatabaseConnection();
        $this->loadServices();
        $this->routing = new Routing();
        $this->forwardToRouting();
    }

    private function setupErrorReporting(): void
    {
        $config = require \ROOT_DIR . '/config/config.php';

        if ($config['mode'] === 'production') {
            error_reporting(0);
            ini_set('display_errors', '0');
            set_exception_handler([$this, 'handleException']);
            set_error_handler([$this, 'handleError']);
        } else {
            error_reporting(E_ALL);
            ini_set('display_errors', '1');
        }
    }

    private function setupDatabaseConnection(): void
    {
        $config = require \ROOT_DIR . '/config/database.php';

        $connectionName = $config['default_connection'] ?? 'default';
        $db = $config['mysql'][$connectionName] ?? null;

        if (!$db || !isset($db['host'], $db['dbname'], $db['username'], $db['password'], $db['charset'])) {
            throw new Exception("Database configuration is incomplete or missing for connection: {$connectionName}");
        }

        $dsn = "mysql:host={$db['host']};dbname={$db['dbname']};charset={$db['charset']}";
        $this->pdo = new PDO($dsn, $db['username'], $db['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }

    private function loadServices(): void
    {
        $this->serviceLoader = new ServiceLoader();
        $this->serviceLoader->load();
    }

    public function handleException($exception): void
    {
        $message = "Exception: " . $exception->getMessage();
        $this->writeToLog($message);
        $this->writeToDatabase($message);
    }

    public function handleError($errno, $errstr, $errfile, $errline): void
    {
        $message = "Error [$errno] $errstr in $errfile on line $errline";
        $this->writeToDatabase($message);
    }

    private function writeToLog(string $message): void
    {
        $logDir = \ROOT_DIR . '/storage/logs';

        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }

        $formatted = '[' . date('Y-m-d H:i:s') . '] ' . $message . PHP_EOL;
        file_put_contents($logDir . '/errors.log', $formatted, FILE_APPEND);
    }

    private function writeToDatabase(string $message): void
    {
        try {
            $route = $_GET['url'] ?? 'N/A';
            $time = date('H:i:s');
            $date = date('Y-m-d');

            $stmt = $this->pdo->prepare("INSERT INTO bug_reporting (route, time, message, date, is_active, is_delete) 
                                         VALUES (:route, :time, :message, :date, 1, 0)");
            $stmt->execute([
                'route' => $route,
                'time' => $time,
                'message' => $message,
                'date' => $date,
            ]);
        } catch (Exception $e) {
            $this->writeToLog("DB Logging Failed: " . $e->getMessage());
        }
    }
    
    
    /**Comment**/

    private function forwardToRouting(): void
    {
        $this->routing->handle();
    }
}
