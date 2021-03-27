<?php

namespace Gov\Core;

class Session
{
    public function __construct()
    {
        if (!session_id() || session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    public function all()
    {
        if ($this->isActive()) {
            return $_SESSION;
        }

        throw new \RuntimeException(
            sprintf("Can't get the session due to inactive session.")
        );
    }

    public function flush()
    {
        if ($this->isActive()) {
            $_SESSION = [];
            return;
        }

        throw new \RuntimeException(
            sprintf("Can't flush the session due to inactive session.")
        );
    }

    public function forget($name)
    {
        if ($this->isActive()) {
            return arrayForget($_SESSION, $name);
        }

        throw new \RuntimeException(
            sprintf("Can't delete value from session due to inactive session.")
        );
    }

    public function get(string $name, $default = null)
    {
        if ($this->isActive()) {
            return arrayGet($_SESSION, $name, $default);
        }

        return $default;
    }

    public function has($key)
    {
        $keys = (array) $key;
        $array = $_SESSION;

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
        if ($this->isActive()) {
            return arraySet($_SESSION, $name, $value);
        }

        throw new \RuntimeException(
            sprintf("Can't insert value into session due to inactive session.")
        );
    }

    protected function isActive()
    {
        return session_id() !== '' || session_status() === PHP_SESSION_ACTIVE;
    }
}
