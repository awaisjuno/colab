<?php

namespace System\Helpers;

class Config
{
    protected static array $items = [];
    protected static string $loadedFile = '';

    protected static function load(string $fileName)
    {
        if (self::$loadedFile !== $fileName) {
            if (pathinfo($fileName, PATHINFO_EXTENSION) !== 'php') {
                $fileName .= '.php';
            }

            $filePath = __DIR__ . '/../../config/' . $fileName;

            if (file_exists($filePath)) {
                self::$items = include $filePath;
                self::$loadedFile = $fileName;
                // Optional: Debug
                // echo "Loaded: $filePath\n";
            } else {
                die("Config file not found at: $filePath");
            }
        }
    }

    public static function get(string $key, $default = null)
    {
        $segments = explode('.', $key);
        $fileName = array_shift($segments);
        self::load($fileName);

        $config = self::$items;

        foreach ($segments as $segment) {
            if (!isset($config[$segment])) {
                return $default;
            }
            $config = $config[$segment];
        }

        return $config;
    }
}

