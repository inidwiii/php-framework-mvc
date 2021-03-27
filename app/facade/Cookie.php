<?php

namespace Gov\Facade;

use Gov\Core\Cookie as CoreCookie;
use Gov\Facade\Facade;

class Cookie extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return CoreCookie::class;
    }
}
