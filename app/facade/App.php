<?php

namespace Gov\Facade;

use Gov\Core\Application as CoreApp;
use Gov\Facade\Facade;

class App extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return CoreApp::class;
    }
}
