<?php

if($_SESSION['user']['usertype'] == 1)
{
    require(base_path("models/DataSets/ProficienciesDataSet.php"));
    require(base_path("models/Extensions/PlacementHelpers.php"));


    $placementsDataSet = new PlacementsDataSet();
    $studentsDataSet = new StudentsDataSet();
    $skillsDataSet = new SkillsDataSet();
    $companiesDataSet = new CompaniesDataSet();
    $proficiencies = new ProficienciesDataSet();

    $placementHelpers = new PlacementHelpers();

    $allProficiencies = $proficiencies->fetchAllProficiencies();
    $allPlacements = $placementsDataSet->fetchAllPlacements();
    $allCompanies = $companiesDataSet->fetchAllCompanies();
    $allSkills = $skillsDataSet->fetchAllSkills();
    $testStudent = $studentsDataSet->fetchStudentDataById(1);


    view("studentdashboard.phtml",[
        'pageTitle' => 'Student Dashboard',
        'placementsDataSet' => $placementsDataSet,
        'studentsDataSet' => $studentsDataSet,
        'companiesDataSet' => $companiesDataSet,
        'testStudent' => $testStudent,
        'allPlacements' => $allPlacements,
        'allCompanies' => $allCompanies,
        'allSkills' => $allSkills,
        'allProficiencies' => $allProficiencies,
        'placementHelpers' => $placementHelpers,
    ]);

} elseif($_SESSION['user']['usertype'] == 2)
{
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
}
