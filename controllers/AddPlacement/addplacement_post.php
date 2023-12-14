<?php
require_once(base_path('models/Core/Validator.php'));


$errors = [];
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
    $skill1Name = $_POST['skill1'];
    $skill2Name = $_POST['skill2'];
    $skill3Name = $_POST['skill3'];
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

    if (!Validator::date($startDate)) {
        $errors['EmptyStartDate'] = "Please select a Start Date!";
    }

    if (!Validator::date($endDate)) {
        $errors['EmptyEndDate'] = "Please select a End Date!";
    }

    if (empty($salary)) {
        $errors['EmptySalary'] = "Please enter a Salary!";
    }

    if (empty($skill1Name)) {
        $errors['EmptySkill1'] = "Please select a Skill!";
    }

    if (empty($proficiency1)) {
        $errors['EmptyProficiency1'] = "Please select a Proficiency!";
    }

    if (empty($skill2Name)) {
        $errors['EmptySkill2'] = "Please select a Skill!";
    }

    if (empty($proficiency2)) {
        $errors['EmptyProficiency2'] = "Please select a Proficiency!";
    }

    if (empty($skill3Name)) {
        $errors['EmptySkill3'] = "Please select a Skill!";
    }

    if (empty($proficiency3)) {
        $errors['EmptyProficiency3'] = "Please select a Proficiency!";
    }

    if ($skill1Name == $skill2Name || $skill1Name == $skill3Name || $skill2Name == $skill3Name) {
        $errors['DuplicateSkills'] = "Please select 3 different skills!";
    }

    if(empty($errors))
    {
        $_SESSION['addPlacementFormData']->setCompanyIdUsingUserId($_SESSION['user']['id']);
        $_SESSION['addPlacementFormData']->setDescription($description);
        $_SESSION['addPlacementFormData']->setIndustryId($industry);
        $_SESSION['addPlacementFormData']->setLocation($location);
        $_SESSION['addPlacementFormData']->setStartDate($startDate);
        $_SESSION['addPlacementFormData']->setEndDate($endDate);
        $_SESSION['addPlacementFormData']->setSalary($salary);
        $_SESSION['addPlacementFormData']->setSkill1($skill1Name, $proficiency1);
        $_SESSION['addPlacementFormData']->setSkill2($skill2Name, $proficiency2);
        $_SESSION['addPlacementFormData']->setSkill3($skill3Name, $proficiency3);
        $_SESSION['addPlacementFormData']->addPlacement();

        header('Location: /dashboard');
        exit();
    }
    else
    {
        return view('AddPlacement/addplacement.phtml', [
            'errors' => $errors,
            'pageTitle' => 'Add Placement',
            'allIndustries' => $_SESSION['addPlacementFormData']->generatePlacementFormData()['industries'],
            'allSkills' => $_SESSION['addPlacementFormData']->generatePlacementFormData()['skills'],
            'allProficiencies' => $_SESSION['addPlacementFormData']->generatePlacementFormData()['proficiencies'],
            'allLocation' => $_SESSION['addPlacementFormData']->generatePlacementFormData()['locations'],
        ]);
    }
}