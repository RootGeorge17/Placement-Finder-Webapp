<?php
require_once base_path("models/Core/Validator.php");

require base_path("models/DataSets/CompaniesDataSet.php");
require base_path("models/DataSets/IndustriesDataSet.php");
require base_path("models/Extensions/GenerateStudentFormData.php");
require base_path("models/Extensions/SaveProfileData.php");

$usersDataSet = new UsersDataSet();
$skillsDataSet = new SkillsDataSet();
$studentDataSet = new StudentsDataSet();
$coursesDataSet = new CoursesDataSet();
$proficienciesDataSet = new ProficienciesDataSet();
$generateStudentFormData = new GenerateStudentFormData();
$companiesDataSet = new CompaniesDataSet();
$industriesDataSet = new IndustriesDataSet();
$saveProfileData = new SaveProfileData();


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

    #region validation
    if (!Validator::string($firstName, 2, 75)) {
        $errors['InvalidName'] = "First name must be between 2 and 75 characters maximum!";
    }

    if (!Validator::string($lastName, 2, 75)) {
        $errors['InvalidLastName'] = "Last name must be between 2 and 75 characters maximum!";
    }

    if (!Validator::email($email)) {
        $errors['InvalidEmail'] = "You have provided an invalid email";
    } elseif (!$usersDataSet->isUserEmail($_SESSION['user']['id'], $email)) { // if the email is not the same as the user's email
        if ($usersDataSet->emailMatch($email)) {
            $errors['InvalidEmail'] = "Email belongs to an already created account!"; // check if it belongs to another account
        }
    }

    if (!Validator::phoneNumber($contactNumber)) {
        $errors['InvalidContactNumber'] = "Phone number must contain 11 numbers";
    } elseif(!$usersDataSet->isUserPhone($_SESSION['user']['id'], $contactNumber)) { // if the phone number is not the same as the user's phone number
        if ($usersDataSet->phoneMatch($contactNumber)) {
            $errors['InvalidEmail'] = "Phone number belongs to an already created account!"; // check if it belongs to another account
        }
    }

    if (!Validator::string($companyName, 2, 75)) {
        $errors['InvalidCompanyName'] = "Company name must be between 2 and 75 characters maximum!";
    } elseif(!$companiesDataSet->isUserCompanyName($_SESSION['user']['id'], $companyName)) { // if the company name is not the same as the user's company name
        if ($companiesDataSet->companyNameMatch($companyName)) {
            $errors['InvalidCompanyName'] = "Company name belongs to an already created account!"; // check if it belongs to another account
        }
    }

    if (!Validator::string($description, 10, 150)) {
        $errors['InvalidCompanyDescription'] = "Company description must be between 10 and 150 characters maximum!";
    }

    if (empty($industry)) {
        $errors['EmptyIndustry'] = "Please select a Industry!";
    }
    #endregion

    $companiesDataSet = new CompaniesDataSet();

    $generateStudentFormData->setUser($_SESSION['user']['id']); // set user data
    $user = $generateStudentFormData->getUser(); // get user data

    // Fetch company data by ID
    $userCompanyData = $companiesDataSet->fetchCompanyById($user->getCompanyId());


    if (empty($errors)) {
        $saveProfileData->setUser($user);
        $saveProfileData->setFirstName($firstName);
        $saveProfileData->setLastName($lastName);
        $saveProfileData->setEmail($email);
        $saveProfileData->setContactNumber($contactNumber);
        $saveProfileData->setCompanyName($companyName);
        $saveProfileData->setCompanyDescription($description);
        $saveProfileData->setCompanyIndustry($industry);
        $saveProfileData->saveCompanyData(); // save company data

        header('Location: /success');
        exit();
    } else {
        view("EditProfile/editemployer.phtml", [
            'pageTitle' => 'Edit Profile',
            'errors' => $errors,
            'userCompanyData' => $userCompanyData,
            'companyIndustry' => $industriesDataSet->fetchIndustryById($userCompanyData->getCompanyIndustry()), // get company industry object
            'allIndustries' => $industriesDataSet->fetchAllIndustries(), // get all industries
            'user' => $user,
        ]);
    }
} elseif ($_POST['submit'] == 'student') {
    // hacky solution to get all the data needed for the view. could be moved to a helper class something like GenerateEditProfileFormData
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $location = $_POST['location'];
    $contactNumber = $_POST['contactNumber'];
    $course = $_POST['course'];
    $institution = $_POST['institution'];
    $preferredIndustry = $_POST['preferredIndustry'];
    $skill1Name = $_POST['skill1'];
    $skill2Name = $_POST['skill2'];
    $skill3Name = $_POST['skill3'];
    $proficiency1 = $_POST['proficiency1'];
    $proficiency2 = $_POST['proficiency2'];
    $proficiency3 = $_POST['proficiency3'];
    $cv = $_FILES['cvUpload']  ?? '';

    $errors = [];

    #region validation
    if (!Validator::string($firstName, 2, 75)) {
        $errors['InvalidName'] = "First name must be between 2 and 75 characters maximum!";
    }

    if (!Validator::string($lastName, 2, 75)) {
        $errors['InvalidLastName'] = "Last name must be between 2 and 75 characters maximum!";
    }

    if (!Validator::email($email)) {
        $errors['InvalidEmail'] = "You have provided an invalid email";
    } elseif (!$usersDataSet->isUserEmail($_SESSION['user']['id'], $email)) { // if the email is not the same as the user's email
        if ($usersDataSet->emailMatch($email)) {
            $errors['InvalidEmail'] = "Email belongs to an already created account!"; // check if it belongs to another account
        }
    }

    if (!Validator::phoneNumber($contactNumber)) {
        $errors['InvalidContactNumber'] = "Phone number must contain 11 numbers";
    } elseif(!$usersDataSet->isUserPhone($_SESSION['user']['id'], $contactNumber)) { // if the phone number is not the same as the user's phone number
        if ($usersDataSet->phoneMatch($contactNumber)) {
            $errors['InvalidEmail'] = "Phone number belongs to an already created account!"; // check if it belongs to another account
        }
    }

    if (empty($location)) {
        $errors['EmptyLocation'] = "Please select a Location!";
    }

    if (empty($course)) {
        $errors['EmptyCourse'] = "Please select a Course!";
    }

    if (empty($institution)) {
        $errors['EmptyInstitution'] = "Please select a Institution!";
    }

    if (empty($preferredIndustry)) {
        $errors['EmptyPreferredIndustry'] = "Please select a Preferred Industry!";
    }

    if (empty($skill1Name)) {
        $errors['EmptySkill1'] = "Please select a Skill!";
    }

    if (empty($skill2Name)) {
        $errors['EmptySkill2'] = "Please select a Skill!";
    }

    if (empty($skill3Name)) {
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

    if ($skill1Name == $skill2Name || $skill1Name == $skill3Name || $skill2Name == $skill3Name) {
        $errors['DuplicateSkills'] = "Please select three different skills!";
    }

    if (isset($_FILES['cvUpload']) && !empty($cv['name'])) {
        $fileName = $cv['name'];
        $fileTmpName = $cv['tmp_name'];
        $fileSize = $cv['size'];
        $fileError = $cv['error'];
        $fileType = $cv['type'];

        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if ($fileError !== UPLOAD_ERR_OK) {
            $errors['CV'] = "Error uploading file. Please try again.";
        } elseif ($fileExtension !== "pdf") {
            $errors['CV'] = "Only PDF files are allowed!";
        } else {
            $uploadDirectory = '/uploads/';
            $newFileName = uniqid('cv_') . '.' . $fileExtension;
            $destination = $_SERVER['DOCUMENT_ROOT'] . $uploadDirectory . $newFileName;

            if (move_uploaded_file($fileTmpName, $destination)) {
                $userStudentData = $studentDataSet->fetchStudentDataByUserId($_SESSION['user']['id']);
                $studentDataSet->updateCV($userStudentData->getId(), $newFileName); // studentDataId, file name
            } else {
                $errors['CV'] = "There was an error uploading your CV! Try again";
            }
        }
    }
    #endregion

    $generateStudentFormData->setUser($_SESSION['user']['id']); // set user data
    $user = $generateStudentFormData->getUser(); // get user data

    $userStudentData = $generateStudentFormData->getUserStudentData(); // get student data
    $userCourse = $generateStudentFormData->getPreferredCourse(); // get preferred course data
    $userCV = $generateStudentFormData->getUserCV($_SESSION['user']['id']);

    // all the ids of the skills the user has
    $userSkillIds = [
        $userStudentData->getSkill1(),
        $userStudentData->getSkill2(),
        $userStudentData->getSkill3(),
    ];

    $universities = getUniversities(); // get universities data

    if (empty($errors)){
        // update details
        $saveProfileData->setUser($user);
        $saveProfileData->setFirstName($firstName);
        $saveProfileData->setLastName($lastName);
        $saveProfileData->setEmail($email);
        $saveProfileData->setContactNumber($contactNumber);
        $saveProfileData->setLocation($location);
        $saveProfileData->setCourse($course);
        $saveProfileData->setInstitution($institution);
        $saveProfileData->setPreferredIndustry($preferredIndustry);
        $saveProfileData->setSkill1($skill1Name);
        $saveProfileData->setSkill2($skill2Name);
        $saveProfileData->setSkill3($skill3Name);
        $saveProfileData->setProficiency1($proficiency1);
        $saveProfileData->setProficiency2($proficiency2);
        $saveProfileData->setProficiency3($proficiency3);
        $saveProfileData->saveStudentData(); // save student data
        // no need to save the cv as it's already saved above in the validation

        header('Location: /success');
        exit();
    } else {
        return view("EditProfile/editstudent.phtml", [
            'errors' => $errors,
            'pageTitle' => 'Edit Profile',
            'user' => $user,
            'userStudentData' => $userStudentData,
            'userSkills' => $skillsDataSet->fetchSkillsbyIdArray($userSkillIds), // get the user's skills objects
            'userCourse' => $userCourse,
            'courses' => $coursesDataSet->fetchAllCourses(), // get all courses
            'universities' => $universities, // get all universities
            'generateStudentFormData' => $generateStudentFormData,

            'studentPreferredIndustry' => $industriesDataSet->fetchIndustryById($userStudentData->getPrefIndustry()), // get student preferred industry object

            'allIndustries' => $industriesDataSet->fetchAllIndustries(), // get all industries
            'allSkills' => $skillsDataSet->fetchAllSkills(), // get all skills
            'allProficiencies' => $proficienciesDataSet->fetchAllProficiencies(), // get all proficiencies
            'userSkillsAndProficiencies' => $generateStudentFormData->getStudentSkillsAndProficiencies( // get the user's skills and proficiencies
                $skillsDataSet->fetchSkillsbyIdArray($userSkillIds), // get the user's skills objects
                $proficienciesDataSet->fetchAllProficiencies()), // get all proficiencies
            'allLocations' => $generateStudentFormData->getLocations(), // get all locations
            'cv' => $userCV,
        ]);
    }
} elseif ($_POST['submit'] == 'careers') {

    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $contactNumber = $_POST['contactNumber'];

    $errors = [];

    #region validation
    if (!Validator::string($firstName, 2, 75)) {
        $errors['InvalidName'] = "First name must be between 2 and 75 characters maximum!";
    }

    if (!Validator::string($lastName, 2, 75)) {
        $errors['InvalidLastName'] = "Last name must be between 2 and 75 characters maximum!";
    }

    if (!Validator::email($email)) {
        $errors['InvalidEmail'] = "You have provided an invalid email";
    } elseif (!$usersDataSet->isUserEmail($_SESSION['user']['id'], $email)) { // if the email is not the same as the user's email
        if ($usersDataSet->emailMatch($email)) {
            $errors['InvalidEmail'] = "Email belongs to an already created account!"; // check if it belongs to another account
        }
    }

    if (!Validator::phoneNumber($contactNumber)) {
        $errors['InvalidContactNumber'] = "Phone number must contain 11 numbers";
    } elseif(!$usersDataSet->isUserPhone($_SESSION['user']['id'], $contactNumber)) { // if the phone number is not the same as the user's phone number
        if ($usersDataSet->phoneMatch($contactNumber)) {
            $errors['InvalidEmail'] = "Phone number belongs to an already created account!"; // check if it belongs to another account
        }
    }
    #endregion

    $generateStudentFormData->setUser($_SESSION['user']['id']); // set user data
    $user = $generateStudentFormData->getUser(); // get user data

    if (empty($errors)) {
        $saveProfileData->setUser($user);
        $saveProfileData->setFirstName($firstName);
        $saveProfileData->setLastName($lastName);
        $saveProfileData->setEmail($email);
        $saveProfileData->setContactNumber($contactNumber);
        $saveProfileData->saveCommonData(); // save careers data

        header('Location: /success');
        exit();
    } else {
        view("EditProfile/editcareersofficer.phtml", [
            'pageTitle' => 'Edit Profile',
            'user' => $user,
            'errors' => $errors,
        ]);
    }

}