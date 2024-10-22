<?php

require base_path("models/DataSets/CompaniesDataSet.php");
require base_path("models/DataSets/IndustriesDataSet.php");
require base_path("models/Extensions/GenerateStudentFormData.php");

$usersDataSet = new UsersDataSet();
$skillsDataSet = new SkillsDataSet();
$studentDataSet = new StudentsDataSet();
$coursesDataSet = new CoursesDataSet();
$proficienciesDataSet = new ProficienciesDataSet();
$generateStudentFormData = new GenerateStudentFormData();
$industriesDataSet = new IndustriesDataSet();

if (!authenticated()) {
    header('location: /login');
    exit();
} else {
    $generateStudentFormData->setUser($_SESSION['user']['id']); // set user data
    $user = $generateStudentFormData->getUser(); // get user data
}


if ($_SESSION['user']['usertype'] == 1) {
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

    view("EditProfile/editstudent.phtml", [
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

} else if ($_SESSION['user']['usertype'] == 2) {
    $companiesDataSet = new CompaniesDataSet();

    // Fetch company data by ID
    $userCompanyData = $companiesDataSet->fetchCompanyById($user->getCompanyId());

    view("EditProfile/editemployer.phtml", [
        'pageTitle' => 'Edit Profile',
        'userCompanyData' => $userCompanyData,
        'companyIndustry' => $industriesDataSet->fetchIndustryById($userCompanyData->getCompanyIndustry()), // get company industry object
        'allIndustries' => $industriesDataSet->fetchAllIndustries(), // get all industries
        'user' => $user,
    ]);
} else if ($_SESSION['user']['usertype'] == 3 || $_SESSION['user']['usertype'] == 4) {

    view("EditProfile/editcareersofficer.phtml", [
        'pageTitle' => 'Edit Profile',
        'user' => $user,
    ]);
}