<?php
require_once(base_path("models/Core/Validator.php"));
require_once(base_path("models/DataSets/UsersDataSet.php"));
$usersDataSet = new UsersDataSet();
$errors = [];

if ($_POST['submit'] == "first") {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $accountType = $_POST['accountType'] ?? '';
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $contactNumber = $_POST['contactNumber'];

    if (!Validator::string($firstName, 2, 75) && !Validator::string($lastName, 2, 75)) {
        $errors['InvalidName'] = "First name must be between 2 and 75 characters maximum!";
    }

    if (!Validator::string($lastName, 2, 75)) {
        $errors['InvalidLastName'] = "Last name must be between 2 and 75 characters maximum!";
    }

    if (!Validator::email($email)) {
        $errors['InvalidEmail'] = "You have provided an invalid email";
    } elseif($usersDataSet->emailMatch($email)) {
        $errors['InvalidEmail'] = "Email belongs to an already created account!";
    }

    if (!Validator::string($password, 2, 75)) {
        $errors['InvalidPassword'] = "Password must be between 2 and 75 characters maximum!";
    }

    if(empty($accountType))
    {
        $errors['EmptyUserType'] = "Please select an Account Type!";
    }

    if ($password !== $confirmPassword) {
        $errors['PasswordMatch'] = "Passwords do not match!";
    }

    if(!Validator::phoneNumber($contactNumber))
    {
        $errors['InvalidContactNumber'] = "Phone number must contain 11 numbers";
    } else {
        // check if unique
    }

    if (empty($errors)) {
        $_SESSION['registration'] = new Registration();
        $_SESSION['registration']->setFirstName($firstName);
        $_SESSION['registration']->setLastName($lastName);
        $_SESSION['registration']->setUserType($accountType);
        $_SESSION['registration']->setEmail($email);
        $_SESSION['registration']->setPassword($password);
        $_SESSION['registration']->setContactNumber($contactNumber);
        $_SESSION['registration']->setStep(1);

        if ($accountType == "careers") {
            // register
            $_SESSION['registration']->registerCareersUser();
            $userDetails = $usersDataSet->getUserDetails($_SESSION['registration']->getEmail());
            registerLogin(
                $userDetails['id'],
                $userDetails['email'],
                $userDetails['userType']
            );

            header('Location: /');
            exit();
        }

        if ($accountType == "library") {
            // register
            $_SESSION['registration']->registerLibraryUser();
            $userDetails = $usersDataSet->getUserDetails($_SESSION['registration']->getEmail());
            registerLogin(
                $userDetails['id'],
                $userDetails['email'],
                $userDetails['userType']
            );

            header('Location: /');
            exit();
        }

        if ($accountType == "student") {
            header('Location: /register?step=2');
            exit();
        }

        if ($accountType == "employer") {
            header('Location: /register?step=3');
            exit();
        }
    } else {
        return view('Authentication/register.phtml', [
            'errors' => $errors,
            'pageTitle' => 'Registration'
        ]);
    }
}

if ($_POST['submit'] == "second") {
    $location = $_POST['location'] ?? '';
    $course = $_POST['course'] ?? '';
    $institution = $_POST['institution'] ?? '';
    $skill1 = $_POST['skill1'] ?? '';
    $skill2 = $_POST['skill2'] ?? '';
    $skill3 = $_POST['skill3'] ?? '';
    $proficiency1 = $_POST['proficiency1'] ?? '';
    $proficiency2 = $_POST['proficiency2'] ?? '';
    $proficiency3 = $_POST['proficiency3'] ?? '';
    $prefIndustry = $_POST['prefIndustry'] ?? '';
    $cv = $_FILES['cvFile'] ?? '';

    if (!Validator::string($location, 4, 75)) {
        $errors['InvalidLocation'] = "Location must be between 4 and 75 characters maximum!";
    }

    if(empty($course))
    {
        $errors['EmptyCourse'] = "Please select a Course!";
    }

    if(empty($institution))
    {
        $errors['EmptyInstitution'] = "Please select a Institution!";
    }

    if(empty($skill1))
    {
        $errors['EmptySkill1'] = "Please select 3 skills!";
    } elseif(empty($proficiency1)) {
        $errors['EmptyProficiency1'] = "Please select a proficiency!";
    }

    if(empty($skill2))
    {
        $errors['EmptySkill2'] = "Please select 3 skills!";
    } elseif(empty($proficiency2)) {
        $errors['EmptyIndustry2'] = "Please select a proficiency!";
    }

    if(empty($skill3))
    {
        $errors['EmptySkill3'] = "Please select 3 skills!";
    } elseif(empty($proficiency3)) {
        $errors['EmptyIndustry3'] = "Please select a proficiency!";
    }

    if(empty($prefIndustry))
    {
        $errors['EmptyPrefIndustry'] = "Please select a preferred industry!";
    }

    if(isset($_FILES['cvFile'])) {
        $fileName = $cv['name'];
        $fileTmpName = $cv['tmp_name'];
        $fileSize = $cv['size'];
        $fileError = $cv['error'];
        $fileType = $cv['type'];

        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        if ($fileExtension !== "pdf") {
            $errors['CV'] = "Only PDF files are allowed!";
        }

        $uploadDirectory = '/uploads/';
        $newFileName = uniqid('cv_') . '.' . $fileExtension;
        $destination = $uploadDirectory . $newFileName;

        if (!move_uploaded_file($fileTmpName, $destination)) {
            $errors['CV'] = "There was an error uploading your CV! Try again";
        }
    }

    if (empty($errors)) {
        $_SESSION['registration']->setLocation($location);
        $_SESSION['registration']->setCourse($course);
        $_SESSION['registration']->setInstitution($institution);
        $_SESSION['registration']->setSkill1($skill1);
        $_SESSION['registration']->setSkill2($skill2);
        $_SESSION['registration']->setSkill3($skill3);
        $_SESSION['registration']->setProficiency1($proficiency1);
        $_SESSION['registration']->setProficiency2($proficiency2);
        $_SESSION['registration']->setProficiency3($proficiency3);
        $_SESSION['registration']->setIndustry($prefIndustry);
        $_SESSION['registration']->setCv($cv);
        $_SESSION['registration']->setStep(2);
        $_SESSION['registration']->registerStudent();
        $userDetails = $usersDataSet->getUserDetails($_SESSION['registration']->getEmail());

        registerLogin(
            $userDetails['id'],
            $userDetails['email'],
            $userDetails['userType']
        );

        header('Location: /dashboard');
        exit();
    } else {
        return view('Authentication/register2.phtml', [
            'errors' => $errors,
            'pageTitle' => 'Registration',
            'universities' => $_SESSION['registration']->generateStepTwoFormData()['universities'],
            'locations' => $_SESSION['registration']->generateStepTwoFormData()['locations'],
            'courses' => $_SESSION['registration']->generateStepTwoFormData()['courses'],
            'skills' => $_SESSION['registration']->generateStepTwoFormData()['skills'],
            'proficiencies' => $_SESSION['registration']->generateStepTwoFormData()['proficiencies'],
            'industries' => $_SESSION['registration']->generateStepTwoFormData()['industries'],
        ]);
    }
}

if($_POST['submit'] == "third")
{
    $companyName = $_POST['companyName'];
    $description = $_POST['description'];
    $industry = $_POST['industry'] ?? '';

    if (!Validator::string($companyName, 2, 75)) {
        $errors['InvalidCompanyName'] = "Company name must be between 2 and 75 characters maximum!";
    } else {
        // check for company Name unique
    }

    if (!Validator::string($description, 10, 150)) {
        $errors['InvalidCompanyDescription'] = "Company description must be between 10 and 150 characters maximum!";
    }

    if(empty($industry))
    {
        $errors['EmptyIndustry'] = "Please select a Industry!";
    }

    if(empty($errors))
    {
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

        header('Location: /dashboard');
        exit();
    } else {
        return view('Authentication/register3.phtml', [
            'errors' => $errors,
            'pageTitle' => 'Registration',
            'industries' => $_SESSION['registration']->generateStepThreeFormData()['industries'],
        ]);
    }
}