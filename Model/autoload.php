<?php

spl_autoload_register(function ($class) {
    // Convert namespace separators to directory separators
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);

    // Define the base directories to look for classes
    $directories = [
        'Controllers/',
        'Model/',
        'Model/entities/',
        'Model/abstract/',
        'Model/interfaces/'
    ];

    // Look for the class in each directory
    foreach ($directories as $directory) {
        $file = $directory . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});