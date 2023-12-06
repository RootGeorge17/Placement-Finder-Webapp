<?php

//dd(base_path('models/DataSets/Company.php'));
require(base_path("models/DataSets/PlacementsDataSet.php"));
require(base_path("models/DataSets/CompaniesDataSet.php"));



$placements = new PlacementsDataSet();
$companies = new CompaniesDataSet();
$placementsDataSet = $placements->fetchAllPlacements();
$companyDataSet = $companies->fetchAllCompanies();

view("studentdashboard.phtml",[
    'pageTitle' => 'Dashboard',
    'placements' => $placements,
    'placementsDataSet' => $placementsDataSet,
    'companies' => $companies,
    'companyDataSet' => $companyDataSet
]);