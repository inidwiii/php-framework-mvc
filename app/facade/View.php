<?php

namespace Gov\Facade;

use Gov\Core\View as CoreView;
use Gov\Facade\Facade;

class View extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return CoreView::class;
    }
}
