<?php

var_dump($_POST);

use models\Core\Validator;

if (!authenticated()) {
    header('location: /login');
    exit();
}

if ($_SESSION['user']['usertype'] == 1) { // check if student
    header('location: /dashboard');
    exit();
}

if ($_POST['submit'] == 'addPlacement'){
    $description = $_POST['description'];
    $industry = $_POST['industry'];
    $location = $_POST['location'];
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];
    $salary = $_POST['salary'];
    $skill1 = $_POST['skill1'];
    $skill2 = $_POST['skill2'];
    $skill3 = $_POST['skill3'];
    $proficiency1 = $_POST['proficiency1'];
    $proficiency2 = $_POST['proficiency2'];
    $proficiency3 = $_POST['proficiency3'];

    if (!Validator::string($description, 10, 250)) {
        $errors['InvalidDescription'] = "Description must be between 10 and 150 characters maximum!";
    }

    if (empty($industry)) {
        $errors['EmptyIndustry'] = "Please select a Industry!";
    }

    if (empty($location) || !Validator::string($location, 2, 75)) {
        $errors['EmptyLocation'] = "Please select a Location!";
    }

    if (empty($startDate)) {
        $errors['EmptyStartDate'] = "Please select a Start Date!";
    }

    if (empty($endDate)) {
        $errors['EmptyEndDate'] = "Please select a End Date!";
    }

    if (empty($salary)) {
        $errors['EmptySalary'] = "Please enter a Salary!";
    }

    if (empty($skill1)) {
        $errors['EmptySkill1'] = "Please select a Skill!";
    }

    if (empty($skill2)) {
        $errors['EmptySkill2'] = "Please select a Skill!";
    }

    if (empty($skill3)) {
        $errors['EmptySkill3'] = "Please select a Skill!";
    }

    if (empty($proficiency1)) {
        $errors['EmptyProficiency1'] = "Please select a Proficiency!";
    }

    if (empty($proficiency2)) {
        $errors['EmptyProficiency2'] = "Please select a Proficiency!";
    }

    if (empty($proficiency3)) {
        $errors['EmptyProficiency3'] = "Please select a Proficiency!";
    }

    // need to check for skill and proficiency
    // then check for unique placement
    // then add placement

}