<?php

try {
    spl_autoload_register(function ($className) {
        $root = dirname(__DIR__) . DIRECTORY_SEPARATOR;
        $classLocation = realpath($root . $className . '.php');
        if (!$classLocation) throw new \TypeError("Class '{$className}' is not exists.");
        require $classLocation;
    }); 

    return new \Src\Core\Application();
} catch (TypeError $exception) { die($exception->getMessage()); }