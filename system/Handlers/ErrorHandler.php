<?php

namespace System\Handlers;

use System\Logging\BugLogger;

/**
 * Class ErrorHandler
 *
 * Handles application errors and exceptions, with support for different environments (production/development).
 */
class ErrorHandler
{
    /**
     * Logger instance to log errors and exceptions.
     *
     * @var BugLogger
     */
    private BugLogger $logger;

    /**
     * Constructor - Initializes the logger.
     */
    public function __construct()
    {
        $this->logger = new BugLogger();
    }

    /**
     * Set up error and exception handling based on the environment mode.
     */
    public function setup(): void
    {
        // Load application configuration
        $config = require \ROOT_DIR . '/config/config.php';

        // In production, hide errors from the user and log them
        if ($config['mode'] === 'production') {
            error_reporting(0);                   // Suppress error reporting
            ini_set('display_errors', '0');       // Do not display errors to users

            // Register custom handlers
            set_exception_handler([$this, 'handleException']);
            set_error_handler([$this, 'handleError']);
        } else {
            // In development, show all errors
            error_reporting(E_ALL);
            ini_set('display_errors', '1');
        }
    }

    /**
     * Custom exception handler.
     *
     * @param \Throwable $exception The uncaught exception.
     */
    public function handleException($exception): void
    {
        // Log exception message
        $this->logger->log($exception->getMessage());
    }

    /**
     * Custom error handler.
     *
     * @param int    $errno   Error level
     * @param string $errstr  Error message
     * @param string $errfile File where the error occurred
     * @param int    $errline Line number where the error occurred
     */
    public function handleError($errno, $errstr, $errfile, $errline): void
    {
        // Format error message and log it
        $message = "Error [$errno] $errstr in $errfile on line $errline";
        $this->logger->log($message);
    }
}
