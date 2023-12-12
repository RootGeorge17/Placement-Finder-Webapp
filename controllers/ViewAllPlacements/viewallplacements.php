<?php

require base_path('models/DataSets/PlacementsDataSet.php');
require base_path('models/DataSets/CompaniesDataSet.php');
require base_path('models/DataSets/IndustriesDataSet.php');
require base_path('models/Extensions/PlacementHelpers.php');

$placementsDataSet = new PlacementsDataSet();
$companiesDataSet = new CompaniesDataSet();
$industriesDataSet = new IndustriesDataSet();
$placementHelpers = new PlacementHelpers();
$proficienciesDataSet = new ProficienciesDataSet();
$skillsDataSet = new SkillsDataSet();

if (authenticated()){
    if ($_SESSION['user']['usertype'] != 1)
    {
        header("Location: /");
        exit();
    }
}

$limit = 16; // default limit of deliveries per page
$page = 1; // default page number
if (isset($_GET['limit'])) {
    if ($_GET['limit'] >= 1 && $_GET['limit'] <= 64) {
        $limit = $_GET['limit'];
    }
    else {
        header("Location: /placements?page=1&limit=16");
    }
}

if (isset($_GET['page'])) {
    if ($_GET['page'] >= 1) {
        $page = $_GET['page'];
    }
    else {
        header("Location: /placements?page=1&limit=16");
    }
}

$start = ($page - 1) * $limit; // start index of placements to be displayed

$rowCount = $placementsDataSet->fetchRowCountAll();

$total = ceil($rowCount / $limit); // total number of pages


view('/ViewAllPlacements/viewallplacements.phtml', [
        'pageTitle' => 'All Placements',
        'allPlacements' => $placementsDataSet->fetchByLimit($start, $limit),
        'companiesDataSet' => $companiesDataSet,
        'industriesDataSet' => $industriesDataSet,
        'skillsDataSet' => $skillsDataSet,
        'allProficiencies' => $proficienciesDataSet->fetchAllProficiencies(),
        'placementHelpers' => $placementHelpers,
        'total' => $total,
        'page' => $page,
        'limit' => $limit
        ]
);
