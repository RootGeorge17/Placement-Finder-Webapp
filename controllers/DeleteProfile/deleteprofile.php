<?php

if(!authenticated()){
    header('Location: /login');
    exit();
}

require_once base_path("models/DataSets/UsersDataSet.php");
$usersDataSet = new UsersDataSet();

if ($_SESSION['user']['usertype'] == 1) {
    // ------------------------- Student
    $usersDataSet->deleteUser($_SESSION['user']['id']);

    // Perform the logout process
    logout();

// Redirect the user to the login page
    header('location: /');
    exit();

} elseif($_SESSION['user']['usertype'] == 2) {
    // ------------------------- Employer
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