<?php

namespace Core;

class Autoload
{
    public static function registrate(string $rootPath): void
    {
        $autoload = function(string $className) use ($rootPath) {
            $path = $rootPath . str_replace('\\', '/', $className) . '.php';

            if (file_exists($path)) {
                require_once $path;
                return true;
            }
            return false;
        };

        spl_autoload_register($autoload);
    }
}