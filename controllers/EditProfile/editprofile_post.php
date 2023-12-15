<?php

if (!authenticated()) {
    header('location: /login');
    exit();
}

if($_POST['submit'] == 'employer')
{
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $contactNumber = $_POST['contactNumber'];
    $companyName = $_POST['companyName'];
    $description = $_POST['description'];
    $industry = $_POST['industry'] ?? '';

    if (!Validator::string($firstName, 2, 75)) {
        $errors['InvalidName'] = "First name must be between 2 and 75 characters maximum!";
    }

    if (!Validator::string($lastName, 2, 75)) {
        $errors['InvalidLastName'] = "Last name must be between 2 and 75 characters maximum!";
    }

    if (!Validator::email($email)) {
        $errors['InvalidEmail'] = "You have provided an invalid email";
    } elseif ($usersDataSet->emailMatch($email)) {
        $errors['InvalidEmail'] = "Email belongs to an already created account!";
    }

    if (!Validator::phoneNumber($contactNumber)) {
        $errors['InvalidContactNumber'] = "Phone number must contain 11 numbers";
    } else {
        // check if unique
    }

    if (!Validator::string($companyName, 2, 75)) {
        $errors['InvalidCompanyName'] = "Company name must be between 2 and 75 characters maximum!";
    } else {
        // check for company Name unique
    }

    if (!Validator::string($description, 10, 150)) {
        $errors['InvalidCompanyDescription'] = "Company description must be between 10 and 150 characters maximum!";
    }

    if (empty($industry)) {
        $errors['EmptyIndustry'] = "Please select a Industry!";
    }

    if (empty($errors)) {
        $_SESSION['registration']->setCompanyName($companyName);
        $_SESSION['registration']->setCompanyDescription($description);
        $_SESSION['registration']->setCompanyIndustry($industry);
        $_SESSION['registration']->setStep(3);
        $_SESSION['registration']->registerCompany();
        $userDetails = $usersDataSet->getUserDetails($_SESSION['registration']->getEmail());

        registerLogin(
            $userDetails['id'],
            $userDetails['email'],
            $userDetails['userType']
        );

        header('Location: /editprofile');
        exit();
    } else {
        return view('EditProfile/editemployer.phtml', [
            'errors' => $errors,
            'pageTitle' => 'Registration',
            'industries' => $_SESSION['registration']->generateStepThreeFormData()['industries'],
        ]);
    }
}

if ($_POST['submit'] == 'changepassword') {

}