<?php
require base_path("models/Extensions/GenerateStudentFormData.php");

if (!authenticated()) {
    header('location: /login');
    exit();
}

$generateStudentFormData = new GenerateStudentFormData();
$generateStudentFormData->setUser($_SESSION['user']['id']); // set user data
$user = $generateStudentFormData->getUser(); // get user data


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

