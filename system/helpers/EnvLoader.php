<?php

namespace System\Helpers;

class EnvLoader
{
    /**
     * Loads environment variables from a .env file into $_ENV and system environment.
     *
     * @param string $filePath Path to the .env file
     * @throws \Exception if the file does not exist
     */
    public static function load(string $filePath): void
    {
        // Check if the .env file exists
        if (!file_exists($filePath)) {
            throw new \Exception(".env file not found at: $filePath");
        }

        // Read lines from the file, ignoring empty lines
        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            // Skip comments and lines that do not contain '='
            if (str_starts_with(trim($line), '#') || !str_contains($line, '=')) {
                continue;
            }

            // Split the line into key and value on the first '=' only
            [$key, $value] = explode('=', $line, 2);

            // Trim whitespace around key and value
            $key = trim($key);
            $value = trim($value);

            // Remove surrounding quotes if present
            $value = trim($value, "\"'");

            // Store the variable in the $_ENV superglobal
            $_ENV[$key] = $value;

            // Also set the environment variable using putenv (for compatibility)
            putenv("$key=$value");
        }
    }

    /**
     * Retrieves the value of an environment variable.
     *
     * @param string $key     The environment variable name
     * @param mixed  $default Default value if the variable is not found
     * @return mixed
     */
    public static function get(string $key, $default = null): mixed
    {
        // Check in $_ENV first, then in system environment variables, else return default
        return $_ENV[$key] ?? getenv($key) ?: $default;
    }
}
