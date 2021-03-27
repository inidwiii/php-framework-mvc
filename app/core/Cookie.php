<?php

namespace Gov\Core;

class Cookie
{
    public function all()
    {
        return $_COOKIE;
    }

    public function flush()
    {
        $_COOKIE = [];
    }

    public function forget($name)
    {
        return arrayForget($_COOKIE, $name);
    }

    public function get(string $name, $default = null)
    {
        return arrayGet($_COOKIE, $name, $default);
    }

    public function has($key)
    {
        $keys = (array) $key;
        $array = $_COOKIE;

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
        return arraySet($_COOKIE, $name, $value);
    }
}
