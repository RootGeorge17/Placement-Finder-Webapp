<?php

if(!authenticated()){
    header('Location: /login');
    exit();
}

require_once base_path("models/DataSets/UsersDataSet.php");
require_once base_path("models/DataSets/CompaniesDataSet.php");
require_once base_path("models/DataSets/StudentsDataSet.php");

$usersDataSet = new UsersDataSet();
$studentsDataSet = new StudentsDataSet();
$companiesDataSet = new CompaniesDataSet();

$user = $usersDataSet->fetchUserById($_SESSION['user']['id']);

if ($_SESSION['user']['usertype'] == 1) {
    // ------------------------- Student
    $studentsDataSet->deleteStudentData($user->getStudentData()); // delete the student data first
    $usersDataSet->deleteUser($_SESSION['user']['id']);

    // Perform the logout process
    logout();

// Redirect the user to the login page
    header('location: /');
    exit();

} elseif($_SESSION['user']['usertype'] == 2) {
    // ------------------------- Employer
    $companiesDataSet->deleteCompanyData($user->getCompanyId()); // delete the company data first
    $usersDataSet->deleteUser($_SESSION['user']['id']);

    // Perform the logout process
    logout();

// Redirect the user to the login page
    header('location: /');
    exit();

} elseif($_SESSION['user']['usertype'] == 3) {
    // ------------------------- Careers Staff
    $usersDataSet->deleteUser($_SESSION['user']['id']);

    // Perform the logout process
    logout();

// Redirect the user to the login page
    header('location: /');
    exit();

} elseif ($_SESSION['user']['usertype'] == 4) {
    // ------------------------- Library User
    $usersDataSet->deleteUser($_SESSION['user']['id']);

    // Perform the logout process
    logout();

// Redirect the user to the login page
    header('location: /');
    exit();

}