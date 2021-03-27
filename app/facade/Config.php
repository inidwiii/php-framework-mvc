<?php

namespace Gov\Facade;

use Gov\Core\Config as CoreConfig;
use Gov\Facade\Facade;

class Config extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return CoreConfig::class;
    }
}
