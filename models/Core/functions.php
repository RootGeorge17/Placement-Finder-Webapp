<?php

// Used for debugging purposes, it displays detailed information about the provided $value
function dd($value)
{
    echo "<pre>";
    var_dump($value);
    echo "</pre>";

    die(); // It terminates the script's execution after displaying the variable's information so that we can inspect it.
}

// This function checks if the current request's URL matches the provided $value.
function isCurrentUrl($url): bool
{
    return $_SERVER['REQUEST_URI'] === $url;
}

function abort($code = 404) {
    http_response_code($code); // Set the HTTP response code (default is 404, but can be customized)

    require base_path("views/{$code}.php"); // Include the corresponding error view based on the provided code

    die(); // Terminate script execution to prevent further processing
}

// Function that constructs the base path for files within the project
function base_path($path): string
{
    return BASE_PATH . $path;
}

// Function that constructs the base path for files within the project
function uploads_path($file): string
{
    return BASE_UPLOADS_PATH . ltrim($file, '/');
}

// Render a view file with data fields
function view($path, $fields = [])
{
    extract($fields); // Turns array into set of variables accessible by the method, variable name = key, variable value = key-value
    require base_path('views/' . $path);
}

function login($id, $email, $usertype)
{
    $loggedIn = true;

    $_SESSION['user'] = [
        'id' => $id,
        'email' => $email,
        'usertype' => $usertype,
        'loggedIn' => $loggedIn,
    ];

    session_regenerate_id(true);
}

function registerLogin($id, $email, $usertype)
{
    $loggedIn = true;

    // Remove any non-serializable data from the session
    unset($_SESSION['registration']); // Change 'registration' to the key that holds the PDO object, if present
    unset($_SESSION['addPlacementFormData']);

    $_SESSION['user'] = [
        'id' => $id,
        'email' => $email,
        'usertype' => $usertype,
        'loggedIn' => $loggedIn,
    ];

    session_regenerate_id(false);
}


function logout()
{
    $_SESSION = [];
    session_destroy();

    $params = session_get_cookie_params();
    setcookie("PHPSESSID", '', time() - 3600, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
}

function authenticated(): bool
{
    if (isset($_SESSION['user']['loggedIn']))
    {
        return true;
    } else {
        return false;
    }
}

function getUniversities()
{
    // Get the JSON data
    $json = file_get_contents(base_path('models/JsonData/uk-universities.json'));
    // Convert JSON string to Array
    $universities = json_decode($json, true);

    usort($universities, "cmp");

    return $universities;
}

function cmp(array $a, array $b): int
{
    return strcmp($a["name"], $b["name"]);
}

