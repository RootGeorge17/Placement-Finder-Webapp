<?php

require base_path("models/DataSets/UsersDataSet.php");
require base_path("models/DataSets/SkillsDataSet.php");
require base_path("models/DataSets/StudentsDataSet.php");
require base_path("models/DataSets/CoursesDataSet.php");
require base_path("models/DataSets/ProficienciesDataSet.php");
require base_path("models/Extensions/GenerateStudentFormData.php");

if (!authenticated()) {
    header('location: /login');
    exit();
}

$usersDataSet = new UsersDataSet();
$skillsDataSet = new SkillsDataSet();
$studentDataSet = new StudentsDataSet();
$coursesDataSet = new CoursesDataSet();
$proficienciesDataSet = new ProficienciesDataSet();
$generateStudentFormData = new GenerateStudentFormData();

if (isset($_SESSION['user'])) {
    $generateStudentFormData->setUser($_SESSION['user']['id']); // set user data
    $user = $generateStudentFormData->getUser(); // get user data
} else {
    header('location: /login'); // redirect to login page
    exit();
}

if ($_SESSION['user']['usertype'] == 1) {
    $universities = $generateStudentFormData->getUniversities(); // get array of universities
    $userStudentData = $generateStudentFormData->getUserStudentData(); // get student data
    $userCourse = $generateStudentFormData->getPreferredCourse(); // get preferred course data

    // all the ids of the skills the user has
    $userSkillIds = [
        $userStudentData->getSkill1(),
        $userStudentData->getSkill2(),
        $userStudentData->getSkill3(),
    ];

    // Fetch all skills by their IDs
    $userSkills = $skillsDataSet->fetchSkillsbyIdArray($userSkillIds);

    // Fetch all skills
    $allSkills = $skillsDataSet->fetchAllSkills();

    // Fetch all proficiencies
    $allProficiencies = $proficienciesDataSet->fetchAllProficiencies();

    // Map user skills to their respective proficiencies
    $userSkillsAndProficiencies = $generateStudentFormData->getStudentSkillsAndProficiencies($userSkills, $allProficiencies);

    // Fetch all courses
    $courses = $coursesDataSet->fetchAllCourses();

    view("EditProfile/editstudent.phtml", [
        'pageTitle' => 'Edit Profile',
        'user' => $user,
        'userStudentData' => $userStudentData,
        'userSkills' => $userSkills,
        'userCourse' => $userCourse,
        'courses' => $courses,
        'universities' => $universities,
        'generateStudentFormData' => $generateStudentFormData,
        'allSkills' => $allSkills,
        'allProficiencies' => $allProficiencies,
        'userSkillsAndProficiencies' => $userSkillsAndProficiencies,
    ]);
} else if ($_SESSION['user']['usertype'] == 2) {
    view("EditProfile/editemployer.phtml", [
        'pageTitle' => 'Edit Profile',
        'user' => $user,
    ]);
}

