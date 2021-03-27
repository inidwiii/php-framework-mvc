<?php

namespace Gov\Core;

class Config
{
    protected $repository = [];

    public function __construct(string $root)
    {
        $this->resolveConfigurations($root);
    }

    public function all()
    {
        return $this->repository;
    }

    public function get(string $name, $default = null)
    {
        return arrayGet($this->repository, $name, $default);
    }

    public function has($key)
    {
        $keys = (array) $key;
        $array = $this->repository;

        foreach ($keys as $key) {
            $parts = explode('.', $key);
            $sub = $array;

            foreach ($parts as $part) {
                if (!isset($sub[$part])) {
                    return false;
                }

                $sub = $sub[$part];
            }
        }

        return true;
    }

    public function set(string $name, $value)
    {
        return arraySet($this->repository, $name, $value);
    }

    protected function resolveConfigurations(string $path): void
    {
        foreach (glob($path . '*.php') as $configPath) {
            $this->repository[explode('.', basename($configPath), 2)[0]] = require $configPath;
        }
    }
}
