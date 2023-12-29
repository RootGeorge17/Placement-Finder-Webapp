<?php

if(!authenticated())
{
    header('location: /login');
    exit();
}

require_once(base_path("models/DataSets/PlacementsDataSet.php"));
require_once(base_path("models/DataSets/StudentsDataSet.php"));
require_once(base_path("models/DataSets/SkillsDataSet.php"));
require_once(base_path("models/DataSets/CompaniesDataSet.php"));
require_once(base_path("models/Extensions/PlacementHelpers.php"));
require_once base_path("models/DataSets/IndustriesDataSet.php");
require_once base_path("models/DataSets/UsersDataSet.php");

if (!isset($_SESSION['user'])) {
    header("Location: /");
    exit();
}
if($_SESSION['user']['usertype'] == 1)
{
    require_once base_path("models/DataSets/ProficienciesDataSet.php");

    $placementsDataSet = new PlacementsDataSet();
    $studentsDataSet = new StudentsDataSet();
    $skillsDataSet = new SkillsDataSet();
    $companiesDataSet = new CompaniesDataSet();
    $placementHelpers = new PlacementHelpers();
    $proficienciesDataSet = new ProficienciesDataSet();
    $industriesDataSet = new IndustriesDataSet();
    $usersDataSet = new UsersDataSet();

    $userId = $_SESSION['user']['id'];

    $user = $usersDataSet->fetchUserById($userId);

    $allProficiencies = $proficienciesDataSet->fetchAllProficiencies();
    $allPlacements = $placementsDataSet->fetchAllPlacements();
    $allCompanies = $companiesDataSet->fetchAllCompanies();
    $allSkills = $skillsDataSet->fetchAllSkills();
    $testStudent = $studentsDataSet->fetchStudentDataById($user->getStudentData()); // dont mind the naming cba refactoring everything else

    view("Dashboard/studentdashboard.phtml",[
        'pageTitle' => 'Student Dashboard',
        'placementsDataSet' => $placementsDataSet,
        'studentsDataSet' => $studentsDataSet,
        'companiesDataSet' => $companiesDataSet,
        'testStudent' => $testStudent,
        'industriesDataSet' => $industriesDataSet,

        'allPlacements' => $allPlacements,
        'allCompanies' => $allCompanies,
        'allSkills' => $allSkills,
        'allProficiencies' => $allProficiencies,
        'placementHelpers' => $placementHelpers,
    ]);

} elseif($_SESSION['user']['usertype'] == 2)
{
    require_once base_path("models/DataSets/CoursesDataSet.php");

    $placementsDataSet = new PlacementsDataSet();
    $studentsDataSet = new StudentsDataSet();
    $skillsDataSet = new SkillsDataSet();
    $companiesDataSet = new CompaniesDataSet();
    $placementHelpers = new PlacementHelpers();
    $industriesDataSet = new IndustriesDataSet();
    $usersDataSet = new UsersDataSet();
    $coursesDataSet = new CoursesDataSet();
    $allStudents = $studentsDataSet->fetchAllStudentData();


    view("Dashboard/employerdashboard.phtml",[
        'pageTitle' => 'Employer Dashboard',
        'studentsDataSet' => $studentsDataSet,
        'companiesDataSet' => $companiesDataSet,
        'usersDataSet' => $usersDataSet,
        'industriesDataSet' => $industriesDataSet,
        'coursesDataSet' => $coursesDataSet,
        'allStudents' => $allStudents,
        'placementHelpers' => $placementHelpers,
    ]);
}