<?php

if (!authenticated()) {
    header('location: /login');
    exit();
}

if ($_SESSION['user']['usertype'] == 1) { // check if student
    header('location: /dashboard');
    exit();
}

$_SESSION['addPlacementFormData'] = new AddPlacementData();

$allIndustries = $_SESSION['addPlacementFormData']->generatePlacementFormData()['industries'];
$allSkills = $_SESSION['addPlacementFormData']->generatePlacementFormData()['skills'];
$allProficiencies = $_SESSION['addPlacementFormData']->generatePlacementFormData()['proficiencies'];
$allLocation = $_SESSION['addPlacementFormData']->generatePlacementFormData()['locations'];


return view("AddPlacement/addplacement.phtml", [
    'pageTitle' => 'Add Placement',
    'allIndustries' => $allIndustries,
    'allSkills' => $allSkills,
    'allProficiencies' => $allProficiencies,
    'allLocation' => $allLocation,
]);