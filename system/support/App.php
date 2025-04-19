<?php

namespace System\Support;

/**
 * Class App
 *
 * A lightweight service container for managing and resolving dependencies via bindings.
 */
class App
{
    /**
     * The array of service bindings.
     *
     * @var array
     */
    protected static array $bindings = [];

    /**
     * Bind a service key to a closure (resolver).
     *
     * @param string $key The unique identifier for the service.
     * @param \Closure $resolver A closure that returns the instance of the service.
     */
    public static function bind(string $key, \Closure $resolver)
    {
        static::$bindings[$key] = $resolver;
    }

    /**
     * Resolve a service by its key.
     *
     * @param string $key The service identifier to resolve.
     * @return mixed The result of the resolver closure (usually an object).
     * @throws \Exception If the service is not bound in the container.
     */
    public static function make(string $key)
    {
        if (!isset(static::$bindings[$key])) {
            throw new \Exception("Service {$key} not bound.");
        }

        // Call the resolver closure to get the service instance
        return call_user_func(static::$bindings[$key]);
    }
}
