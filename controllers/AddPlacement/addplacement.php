<?php

require base_path('models/Extensions/AddPlacementFormData.php');

if (!authenticated()) {
    header('location: /login');
    exit();
}

if ($_SESSION['user']['usertype'] == 1) { // check if student
    header('location: /dashboard');
    exit();
}

$addPlacementFormData = new AddPlacementFormData();


view("AddPlacement/AddPlacement.phtml", [
    'pageTitle' => 'Add Placement',
    'allIndustries' => $addPlacementFormData->getIndustries(),
    'allSkills' => $addPlacementFormData->getSkills(),
    'allProficiencies' => $addPlacementFormData->getProficiencies(),
    'allLocation' => $addPlacementFormData->getLocations(),
]);