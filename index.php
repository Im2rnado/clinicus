<?php

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load configuration and autoloader
require_once "Model/config.php";
require_once "Model/autoload.php";

// Get the request URI and remove any query strings
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove leading slash and get the first segment
$path = trim($request_uri, '/');
$segments = explode('/', $path);

// Remove the base path (clinicus) if it exists
if ($segments[0] === 'clinicus') {
    array_shift($segments);
}

// Default controller and action
$controller = 'Home';
$action = 'index';
$params = [];

// If we have segments, use them to determine controller and action
if (!empty($segments[0])) {
    $controller = ucfirst($segments[0]);
    if (!empty($segments[1])) {
        // Handle special actions like delete and edit
        if ($segments[1] === 'delete' || $segments[1] === 'edit') {
            $action = $segments[1] . ucfirst($segments[0]);
            // The ID should be the next segment
            if (!empty($segments[2])) {
                $params = [$segments[2]];
            }
        } else {
            $action = $segments[1];
            // Any remaining segments are parameters
            $params = array_slice($segments, 2);
        }
    }
}

// Construct the controller class name
$controllerClass = $controller . 'Controller';
$controllerFile = "Controllers/{$controllerClass}.php";

// Check if the controller file exists
if (file_exists($controllerFile)) {
    require_once $controllerFile;

    // Create controller instance
    $controllerInstance = new $controllerClass();

    // Check if the action exists
    if (method_exists($controllerInstance, $action)) {
        // Call the action with parameters
        call_user_func_array([$controllerInstance, $action], $params);
    } else {
        // Action not found
        header("HTTP/1.0 404 Not Found");
        echo "Action not found";
    }
} else {
    // Controller not found
    header("HTTP/1.0 404 Not Found");
    echo "Controller not found";
}