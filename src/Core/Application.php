<?php

namespace Src\Core;

class Application
{
    public function __construct()
    {
        defined('DS') or define('DS', DIRECTORY_SEPARATOR);
        defined('ROOT') or define('ROOT', realpath(dirname(__DIR__, 2)) . DS);
        defined('PATH_APP') or define('PATH_APP', realpath(ROOT . 'app') . DS);
        defined('PATH_APP_HTTP') or define('PATH_APP_HTTP', realpath(PATH_APP . 'http') . DS);
        defined('PATH_APP_HTTP_CONTROLLER') or define('PATH_APP_HTTP_CONTROLLER', realpath(PATH_APP_HTTP . 'controller') . DS);
        defined('PATH_APP_HTTP_MIDDLEWARE') or define('PATH_APP_HTTP_MIDDLEWARE', realpath(PATH_APP_HTTP . 'middleware') . DS);
        defined('PATH_LIB') or define('PATH_LIB', realpath(ROOT . 'src') . DS);
        defined('PATH_LIB_CONTRACT') or define('PATH_LIB_CONTRACT', realpath(PATH_LIB . 'contract') . DS);
        defined('PATH_LIB_CORE') or define('PATH_LIB_CORE', realpath(PATH_LIB . 'core') . DS);

        require realpath(ROOT . 'routes/web.php');
    }
}