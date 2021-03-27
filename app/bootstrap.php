<?php

defined('DS') or define('DS', DIRECTORY_SEPARATOR);
defined('ROOT') or define('ROOT', dirname(__DIR__) . DS);

defined('PATH_APP') or define('PATH_APP', realpath(ROOT . 'app') . DS);
defined('PATH_PUBLIC') or define('PATH_PUBLIC', realpath(ROOT . 'public') . DS);

require realpath(PATH_APP . 'core/helpers.php');

spl_autoload_register(function ($concrete) {
    $path = str_replace('Gov\\', PATH_APP, $concrete);
    $path = realpath($path . '.php');


    if ($path) {
        return require $path;
    }

    throw new \RuntimeException(
        sprintf("Can't resolve class with name: %s", $concrete)
    );
});
