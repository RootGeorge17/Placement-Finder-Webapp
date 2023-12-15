<?php
// Enable error reporting and display errors for debugging purposes
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define a constant for the base path directory for the project
const BASE_PATH = __DIR__ . '/../';

// Require necessary files
require BASE_PATH . 'models/Core/functions.php'; // Including functions file
require BASE_PATH . "models/Extensions/Registration.php";
require BASE_PATH . "models/Extensions/AddPlacementData.php";

// Starting a PHP session
session_start();

// Requiring and initializing an instance of Router to route user
require_once(base_path("/models/Core/Router.php"));
$router = new Router();
$routes = require_once(base_path("routes.php"));

// Parsing the requested URI and determining the request method
$uri = parse_url($_SERVER['REQUEST_URI'])['path']; // Extract the path component from the current request's URI
$method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];

// Routing the URI and method
$router->route($uri, $method);

