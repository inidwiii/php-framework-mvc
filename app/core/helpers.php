<?php

use Gov\Facade\Config;
use Gov\Facade\Cookie;
use Gov\Facade\View;
use Gov\Facade\Session;
use Gov\Core\Application;

if (!function_exists('app')) {
    function app(?string $abstract = null)
    {
        $instance = Application::instance();

        if ($abstract !== null) {
            return $instance->make($abstract);
        }

        return $instance;
    }
}

if (!function_exists('arrayForget')) {
    function arrayForget(array &$array, $key)
    {
        $keys = (array) $key;
        $original = &$array;

        foreach ($keys as $key) {
            if (isset($array[$key])) {
                unset($array[$key]);
                continue;
            }

            $parts = explode('.', $key);
            $array = &$original;

            while (count($parts) > 1) {
                $part = array_shift($parts);

                if (!isset($array[$part])) {
                    continue 2;
                } else {
                    $array = &$array[$part];
                }
            }

            unset($array[array_shift($parts)]);
        }

        return $original;
    }
}

if (!function_exists('arrayGet')) {
    function arrayGet(array $array, ?string $key, $default = null)
    {
        if ($key === null) {
            return $array;
        }

        if (isset($array[$key])) {
            return $array[$key];
        }

        $keys = explode('.', $key);

        foreach ($keys as $key) {
            if (!isset($array[$key])) {
                return $default;
            }

            $array = $array[$key];
        }

        return $array;
    }
}

if (!function_exists('arraySet')) {
    function arraySet(array &$array, ?string $key, $value)
    {
        if ($key === null) {
            return $array = $value;
        }

        $keys = explode('.', $key);

        while (count($keys) > 1) {
            $key = array_shift($keys);

            if (!isset($array[$key]) || !is_array($array[$key])) {
                $array[$key] = [];
            }

            $array = &$array[$key];
        }

        return $array[array_shift($keys)] = $value;
    }
}

if (!function_exists('cookie')) {
    function config($key = null, $default = null)
    {
        if (is_null($key)) {
            return app(\Gov\Core\Config::class);
        }

        if (is_array($key)) {
            foreach ($key as $name => $value) {
                Config::set($name, $value);
            }

            return Config::all();
        }

        return Config::get($key, $default);
    }
}

if (!function_exists('cookie')) {
    function cookie($key = null, $default = null)
    {
        if (is_null($key)) {
            return app(\Gov\Core\Cookie::class);
        }

        if (is_array($key)) {
            foreach ($key as $name => $value) {
                Cookie::set($name, $value);
            }

            return Cookie::all();
        }

        return Cookie::get($key, $default);
    }
}

if (!function_exists('asset')) {
    function asset(string $path)
    {
        return public_path('assets/' . $path);
    }
}

if (!function_exists('css')) {
    function css(string $path)
    {
        return public_path('css/' . $path);
    }
}

if (!function_exists('js')) {
    function js(string $path)
    {
        return public_path('js/' . $path);
    }
}

if (!function_exists('public_path')) {
    function public_path(string $suffix = '')
    {
        return config('app.baseuri') . $suffix;
    }
}

if (!function_exists('session')) {
    function session($key = null, $default = null)
    {
        if (is_null($key)) {
            return app(\Gov\Core\Session::class);
        }

        if (is_array($key)) {
            foreach ($key as $name => $value) {
                Session::set($name, $value);
            }

            return Session::all();
        }

        return Session::get($key, $default);
    }
}

if (!function_exists('view')) {
    function view(string $name, array $data = [])
    {
        return View::make($name, $data);
    }
}
