<?php

namespace System\Helpers;

class Config
{
    protected static array $items = [];

    public static function load(string $path): void
    {
        $files = glob($path . '/*.php');
        foreach ($files as $file) {
            $key = basename($file, '.php');
            self::$items[$key] = include $file;
        }
    }

    public static function get(string $key, $default = null)
    {
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
