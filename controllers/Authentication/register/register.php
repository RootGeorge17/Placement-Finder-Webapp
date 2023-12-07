<?php

$currentUrl = $_SERVER['REQUEST_URI'];
$queryParams = parse_url($currentUrl, PHP_URL_QUERY);

parse_str($queryParams, $query);

$step = $query['step'] ?? '';

switch ($step) {
    case '1':
        // Handle registration step 1 logic
        view("Authentication/register2.phtml", [
            'pageTitle' => 'Registration - Step 1',
        ]);
        break;
    case '2':
        // Handle registration step 2 logic
        view("Authentication/register3.phtml", [
            'pageTitle' => 'Registration - Step 2',
        ]);
        break;
    default:
        // Handle cases (if step parameter is missing or invalid) => Redirect
        header('Location: /register?step=1');
        break;
}




