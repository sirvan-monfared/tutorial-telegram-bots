<?php

namespace App\Core;

use Exception;

class Container
{
    protected array $bindings = [];

    public function bind(string $key, callable $resolver): void
    {
        $this->bindings[$key] = $resolver;
    }

    /**
     * @throws Exception
     */
    public function resolve($key)
    {
        if (! array_key_exists($key, $this->bindings)) {
            throw new Exception("No Match Found for {$key} Binding");
        }

        $resolver = $this->bindings[$key];

        return call_user_func($resolver);
    }
}