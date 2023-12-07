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

// Render a view file with data fields
function view($path, $fields = [])
{
    extract($fields); // Turns array into set of variables accessible by the method, variable name = key, variable value = key-value
    require base_path('views/' . $path);
}

function login($id, $email, $usertypeid)
{
    $loggedIn = true;

    $_SESSION['user'] = [
        'id' => $id,
        'email' => $email,
        'usertypeid' => $usertypeid,
        'loggedIn' => $loggedIn,
    ];

    session_regenerate_id(true);
}