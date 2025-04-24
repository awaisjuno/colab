<?php

namespace System\Helpers;

class Config
{
    protected static array $items = [];

    protected static function load()
    {
        if (empty(self::$items)) {
            $file = __DIR__ . '/../../config/database.php';
            if (file_exists($file)) {
                self::$items = include $file;
            } else {
                die("Config file not found at: $file");
            }
        }
    }

    public static function get(string $key, $default = null)
    {
        self::load();

        $segments = explode('.', $key);
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
