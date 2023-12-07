<?php

require(base_path("models/DataSets/PlacementsDataSet.php"));
require(base_path("models/DataSets/CompaniesDataSet.php"));
require(base_path("models/DataSets/SkillsDataSet.php"));
require(base_path("models/DataSets/StudentsDataSet.php"));
require(base_path("models/Extensions/PlacementHelpers.php"));

$placementsDataSet = new PlacementsDataSet();
$companiesDataSet = new CompaniesDataSet();
$skillsDataSet = new SkillsDataSet();
$studentsDataSet = new StudentsDataSet();
$placementHelpers = new PlacementHelpers();
$allStudents = $studentsDataSet->fetchAllStudentData();


view("employerdashboard.phtml",[
    'pageTitle' => 'Employer Dashboard',
    'studentsDataSet' => $studentsDataSet,
    'allStudents' => $allStudents,
    'placementHelpers' => $placementHelpers,
]);