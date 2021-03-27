<?php

namespace Gov\Facade;

use Gov\Core\Session as CoreSession;
use Gov\Facade\Facade;

class Session extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return CoreSession::class;
    }
}
