<?php

namespace Gov\Core;

class Container
{
    private static $instance;

    protected $bindings = [];

    protected function __construct()
    {
        self::$instance = $this;
    }

    public function bind(string $abstract, $concrete = null)
    {
        return $this->addBinding($abstract, $concrete ?? $abstract, true);
    }

    public function make(string $abstract)
    {
        if (!$this->isBound($abstract)) {
            throw new \RuntimeException(
                sprintf("Can't resolve binding at name %s", $abstract)
            );
        }

        $binding = $this->getBinding($abstract);

        if ($binding['singleton'] && isset($binding['resolved'])) {
            return $binding['resolved'];
        }

        return $this->resolveBinding(
            $abstract,
            is_callable($binding['concrete'])
                ? call_user_func($binding['concrete'], $this)
                : $this->reflectConcrete($binding)
        );
    }

    public function resolve(string $concrete, array $args = [])
    {
        return $this->reflectConcrete([
            'concrete' => $concrete,
            'arguments' => $args
        ]);
    }

    public function singleton(string $abstract, $concrete = null)
    {
        return $this->addBinding($abstract, $concrete ?? $abstract, false);
    }

    public function with(array $args)
    {
        $this->bindings[count($this->bindings) - 1]['arguments'] = $args;
        return $this;
    }

    public static function instance()
    {
        return self::$instance;
    }

    protected function addBinding(string $abstract, $concrete, bool $new)
    {
        $this->bindings[] = [
            'abstract' => $abstract,
            'concrete' => $concrete,
            'arguments' => [],
            'resolved' => null,
            'singleton' => !$new,
        ];

        return $this;
    }

    protected function getBinding(string $abstract)
    {
        foreach ($this->bindings as $binding) {
            if ($abstract === $binding['abstract']) {
                return $binding;
            }
        }

        throw new \RuntimeException(
            sprintf("Can't find binding at name %s", $abstract)
        );
    }

    protected function resolveBinding(string $abstract, $concrete)
    {
        foreach ($this->bindings as $key => $binding) {
            if ($abstract === $binding['abstract']) {
                return $this->bindings[$key]['resolved'] = $concrete;
            }
        }
    }

    protected function isBound(string $abstract)
    {
        foreach ($this->bindings as $binding) {
            if ($abstract === $binding['abstract']) {
                return true;
            }
        }

        return false;
    }

    protected function reflectConcrete($binding)
    {
        $reflector = new \ReflectionClass($binding['concrete']);
        $dependencies = $this->reflectDependencies(
            $reflector->getConstructor(),
            $binding['arguments'] ?? []
        );

        return $reflector->newInstanceArgs($dependencies);
    }

    protected function reflectDependencies($reflector, $args = [])
    {
        if ($reflector instanceof \ReflectionMethod) {
            return array_map(
                function (\ReflectionParameter $parameter) use (&$args, $reflector) {
                    $name = $parameter->getName();
                    $type = $parameter->getType();

                    if ($type instanceof \ReflectionNamedType && !$type->isBuiltin()) {
                        if ($this->isBound($type->getName())) {
                            return $this->make($type->getName());
                        }

                        return $this->reflectConcrete($type->getName());
                    }

                    if (count($args) !== 0) {
                        return $args[$name] ?? array_shift($args);
                    }

                    if ($parameter->isDefaultValueAvailable()) {
                        return $parameter->getDefaultValue();
                    }

                    throw new \RuntimeException(
                        sprintf(
                            "Can't resolve argument %s at %s",
                            $name,
                            $reflector->getName()
                        )
                    );
                },
                $reflector->getParameters()
            );
        }

        return [];
    }
}
