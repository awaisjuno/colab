<?php

namespace System\Support;

class App
{
    protected static array $bindings = [];

    public static function bind(string $key, \Closure $resolver)
    {
        static::$bindings[$key] = $resolver;
    }

    public static function make(string $key)
    {
        if (!isset(static::$bindings[$key])) {
            throw new \Exception("Service {$key} not bound.");
        }

        return call_user_func(static::$bindings[$key]);
    }
}
