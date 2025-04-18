<?php

namespace System;

class Container
{
    protected array $bindings = [];

    public function bind(string $abstract, $concrete = null): void
    {
        if (is_null($concrete)) {
            $concrete = $abstract;
        }

        $this->bindings[$abstract] = $concrete;
    }

    public function resolve(string $abstract)
    {
        if (!isset($this->bindings[$abstract])) {
            return new $abstract();
        }

        $concrete = $this->bindings[$abstract];

        if ($concrete instanceof \Closure) {
            return $concrete($this);
        }

        return new $concrete();
    }

    public function has(string $abstract): bool
    {
        return isset($this->bindings[$abstract]);
    }
}
