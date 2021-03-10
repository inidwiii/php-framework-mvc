<?php

try {
    spl_autoload_register(function ($className) {
        $classLocation = str_replace('Illuminate', '', trim($className, '\\'));
        $classLocation = realpath(__DIR__ . $classLocation . '.php');

        if (!$classLocation) throw new \TypeError("Class '{$className}' is not exists.");
        require $classLocation;
    });

    return new \Illuminate\Core\Application();
} catch (TypeError $exception) { die($exception->getMessage()); }