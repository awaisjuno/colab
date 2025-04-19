<?php

namespace System\Lib;

/**
 * Class Container
 *
 * A simple Dependency Injection (DI) container for managing class bindings and resolving dependencies.
 */
class Container
{
    /**
     * An associative array of abstract types and their concrete implementations.
     *
     * @var array
     */
    protected array $bindings = [];

    /**
     * Bind an abstract type to a concrete implementation.
     *
     * @param string $abstract The name or identifier of the dependency.
     * @param mixed|null $concrete The concrete implementation (class name or closure).
     */
    public function bind(string $abstract, $concrete = null): void
    {
        // If no concrete is provided, default to the abstract itself
        if (is_null($concrete)) {
            $concrete = $abstract;
        }

        // Store the binding
        $this->bindings[$abstract] = $concrete;
    }

    /**
     * Resolve and return an instance of the given abstract type.
     *
     * @param string $abstract The abstract identifier to resolve.
     * @return mixed An instance of the resolved concrete class or result of the closure.
     */
    public function resolve(string $abstract)
    {
        // If not bound, instantiate directly using the class name
        if (!isset($this->bindings[$abstract])) {
            return new $abstract();
        }

        $concrete = $this->bindings[$abstract];

        // If the binding is a closure, invoke it with the container itself
        if ($concrete instanceof \Closure) {
            return $concrete($this);
        }

        // Otherwise instantiate the concrete class
        return new $concrete();
    }

    /**
     * Check if a binding exists for the given abstract type.
     *
     * @param string $abstract The abstract identifier.
     * @return bool True if the binding exists, false otherwise.
     */
    public function has(string $abstract): bool
    {
        return isset($this->bindings[$abstract]);
    }
}
