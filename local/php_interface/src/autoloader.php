<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

spl_autoload_register(function ($class): void {
    if (!str_contains($class, 'Otus')) {
        return;
    }

    $class = str_replace('\\', '/', $class);

    $path = __DIR__ . '/' . $class . '.php';
    echo $path;
    if(is_file($path)) {
        
        require_once $path;
    }
});