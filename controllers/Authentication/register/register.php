<?php

if(authenticated())
{
    header('location: /dashboard');
    exit();
}

require base_path("models/Extensions/GenerateRegistrationData.php");
$generateRegistrationData = new GenerateRegistrationData();

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
        $universities = $generateRegistrationData->getUniversities();
        $courses = $generateRegistrationData->getCourses();
        $skills = $generateRegistrationData->getSkills();
        $proficiencies = $generateRegistrationData->getProficiencies();
        $industries = $generateRegistrationData->getIndustries();
        $locations = $generateRegistrationData->getLocations();

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
        $industries = $generateRegistrationData->getIndustries();

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




