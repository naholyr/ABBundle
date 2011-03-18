<?php

spl_autoload_register(function($class_name) {
    $root = realpath(__DIR__ . '/../../..');

    $file = $root . '/' . implode('/', explode('\\', $class_name)) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});
