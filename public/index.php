<?php

session_start();

// Define a constant for the base path directory for the project
const BASE_PATH = __DIR__ . '/../';

// Require necessary files
require BASE_PATH . 'Core/functions.php';

// Register an autoloader function
spl_autoload_register(function ($class) {
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);

    require base_path("{$class}.php");
});

$router = new \Core\Router();
$routes = require_once(base_path("routes.php"));

$uri = parse_url($_SERVER['REQUEST_URI'])['path']; // Extract the path component from the current request's URI
$method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];

$router->route($uri, $method);

