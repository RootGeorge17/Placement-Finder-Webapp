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

view('/ViewAllPlacements/viewallplacements.phtml', [
        'pageTitle' => 'All Placements',
        'allPlacements' => $placementsDataSet->fetchAllPlacements(),
        'companiesDataSet' => $companiesDataSet,
        'industriesDataSet' => $industriesDataSet,
        'skillsDataSet' => $skillsDataSet,
        'allProficiencies' => $proficienciesDataSet->fetchAllProficiencies(),
        'placementHelpers' => $placementHelpers,
        ]
);
