<?php

namespace Gov\Facade;

abstract class Facade
{
    abstract public static function getFacadeAccessor(): string;

    public static function __callStatic($name, $arguments)
    {
        return call_user_func_array(
            [app(static::getFacadeAccessor()), $name],
            $arguments
        );
    }
}
