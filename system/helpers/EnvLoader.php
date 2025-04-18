<?php

namespace System\Helpers;

class EnvLoader
{
    public static function load(string $filePath): void
    {
        if (!file_exists($filePath)) {
            throw new \Exception(".env file not found at: $filePath");
        }

        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            if (str_starts_with(trim($line), '#') || !str_contains($line, '=')) {
                continue;
            }

            [$key, $value] = explode('=', $line, 2);

            $key = trim($key);
            $value = trim($value);

            $value = trim($value, "\"'");

            $_ENV[$key] = $value;
            putenv("$key=$value");
        }
    }

    public static function get(string $key, $default = null): mixed
    {
        return $_ENV[$key] ?? getenv($key) ?: $default;
    }
}
