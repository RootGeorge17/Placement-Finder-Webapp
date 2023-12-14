<?php

if(authenticated())
{
    header('location: /dashboard');
    exit();
}

$currentUrl = $_SERVER['REQUEST_URI'];
$queryParams = parse_url($currentUrl, PHP_URL_QUERY);

parse_str($queryParams, $query);

$step = $query['step'] ?? '';

switch ($step) {
    case '1':
        // Handle registration step 1 logic
        return view("Authentication/register.phtml", [
            'pageTitle' => 'Registration',
        ]);
        break;
    case '2':
        // Handle registration step 2 logic
        $universities = $_SESSION['registration']->generateStepTwoFormData()['universities'];
        $courses = $_SESSION['registration']->generateStepTwoFormData()['courses'];
        $skills = $_SESSION['registration']->generateStepTwoFormData()['skills'];
        $proficiencies = $_SESSION['registration']->generateStepTwoFormData()['proficiencies'];
        $industries = $_SESSION['registration']->generateStepTwoFormData()['industries'];
        $locations = $_SESSION['registration']->generateStepTwoFormData()['locations'];

        return view("Authentication/register2.phtml", [
            'universities' => $universities,
            'courses' => $courses,
            'skills' => $skills,
            'proficiencies' => $proficiencies,
            'industries' => $industries,
            'locations' => $locations,
            'pageTitle' => 'Registration',
        ]);
        break;
    case '3':
        // Handle registration step 2 logic
        $industries = $_SESSION['registration']->generateStepTwoFormData()['industries'];

        return view("Authentication/register3.phtml", [
            'pageTitle' => 'Registration',
            'industries' => $industries,
        ]);
        break;
    default:
        // Handle cases (if step parameter is missing or invalid) => Redirect
        header('Location: /register?step=1');
        break;
}




