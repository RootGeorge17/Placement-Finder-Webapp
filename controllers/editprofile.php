<?php

require base_path("models/DataSets/UsersDataSet.php");
require base_path("models/DataSets/SkillsDataSet.php");
require base_path("models/DataSets/StudentsDataSet.php");
require base_path("models/DataSets/CoursesDataSet.php");
require base_path("models/DataSets/ProficienciesDataSet.php");

if (!authenticated()) {
    header('location: /login');
    exit();
}

$usersDataSet = new UsersDataSet();
$skillsDataSet = new SkillsDataSet();
$studentDataSet = new StudentsDataSet();
$coursesDataSet = new CoursesDataSet();
$proficienciesDataSet = new ProficienciesDataSet();


if (isset($_SESSION['user'])) {
    $user = $usersDataSet->fetchUserById($_SESSION['user']['id']);
    if ($user->getStudentData() != null) {
        $userStudentData = $studentDataSet->fetchStudentDataById($user->getStudentData());
        $userCourse = $coursesDataSet->fetchCourseById($userStudentData->getCourse());
        var_dump($user);
        var_dump($userCourse);
    }
}

if ($_SESSION['user']['usertype'] == 1) {


    $userSkillIds = [
        $userStudentData->getSkill1(),
        $userStudentData->getSkill2(),
        $userStudentData->getSkill3(),
    ];

    $userSkills = $skillsDataSet->fetchSkillsbyIdArray($userSkillIds);

// Fetch all proficiencies
    $allProficiencies = $proficienciesDataSet->fetchAllProficiencies();

// Map user skills to their respective proficiencies
    $userSkillsAndProficiencies = [];
    foreach ($userSkills as $userSkill) {
        $proficiencyId = $userSkill->getProficiency();

        // Find the proficiency in the fetched list by ID
        $foundProficiency = array_filter($allProficiencies, function ($proficiency) use ($proficiencyId) {
            return $proficiency->getId() === $proficiencyId;
        });

        // If the proficiency is found, add it to the result
        if (!empty($foundProficiency)) {
            $userSkillsAndProficiencies[] = [
                'skill' => $userSkill,
                'proficiency' => reset($foundProficiency), // Use reset to get the first element from the filtered array
            ];
        }
    }


    view("EditProfile/editstudent.phtml", [
        'pageTitle' => 'Edit Profile',
        'user' => $user,
        'userStudentData' => $userStudentData,
        'userSkills' => $userSkills,
        'userCourse' => $userCourse,
        'userSkillsAndProficiencies' => $userSkillsAndProficiencies,
    ]);
} else if ($_SESSION['user']['usertype'] == 2) {
    view("EditProfile/editemployer.phtml", [
        'pageTitle' => 'Edit Profile',
        'user' => $user,
    ]);
}

