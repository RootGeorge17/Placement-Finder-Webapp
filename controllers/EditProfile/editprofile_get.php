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

/**
 * Generates the options for a select element
 *
 * 09-12-2023 - muj
 * @param string $selectedValue string
 * @param array $optionsArray array of objects or arrays
 * @param string $getterFunction to get the value from the object
 * @param int $option 0 for object, 1 for array
 * @return void
 */
function generateSelectOptions(string $selectedValue, array $optionsArray, string $getterFunction, int $option): void
{
    echo "<option value='$selectedValue'>$selectedValue</option>";

    foreach ($optionsArray as $optionItem) {
        if ($option == 0) {
            $type = $optionItem->$getterFunction();
        } elseif ($option == 1) {
            $type = $optionItem[$getterFunction];
        }

        if ($type == $selectedValue) {
            continue;
        }

        echo "<option value='" . $type . "'>" . $type . "</option>";
    }
}

/**
 * Generates the options for a select element
 *
 * 09-12-2023 - muj
 * @param skill $selectedSkill string
 * @param array $allSkills array of objects or arrays
 * @param skill $userSkills array of objects or arrays
 * @return void
 */
function generateSkillSelectOptions(skill $selectedSkill, array $allSkills, skill $userSkills): void
{
    echo "<option value='" . $userSkills->getSkillName() . "'>" . $userSkills->getSkillName() . "</option>";
    foreach ($allSkills as $skill) {
        if ($skill->getSkillName() == $selectedSkill->getSkillName()) {
            continue;
        }
        echo "<option value='" . $skill->getSkillName() . "'>" . $skill->getSkillName() . "</option>";
    }
}

/**
 * Generates the options for a select element
 *
 * 09-12-2023 - muj
 * @param array $selectedProficiency array object
 * @param array $allProficiencies array of objects or arrays
 * @param array $userProficiency array object
 * @return void
 */
function generateProficiencySelectOptions(array $selectedProficiency, array $allProficiencies, array $userProficiency): void
{
    echo "<option value='" . $userProficiency['proficiency']->getProficiency() . "'>" . $userProficiency['proficiency']->getProficiency() . "</option>";
    foreach ($allProficiencies as $proficiency) {
        if ($proficiency->getProficiency() == $selectedProficiency['proficiency']->getProficiency()) {
            continue;
        }
        echo "<option value='" . $proficiency->getProficiency() . "'>" . $proficiency->getProficiency() . "</option>";
    }
}

if (isset($_SESSION['user'])) {
    $user = $usersDataSet->fetchUserById($_SESSION['user']['id']);
    if ($user->getStudentData() != null || $user->getStudentData() != "" || empty($user->getStudentData())) {
        $userStudentData = $studentDataSet->fetchStudentDataById($user->getStudentData());
        $userCourse = $coursesDataSet->fetchCourseById($userStudentData->getCourse());
    }

    // Get the JSON data
    $json = file_get_contents(base_path('models/JsonData/uk-universities.json'));
    // Convert JSON string to Array
    $universities = json_decode($json, true);

    /**
     * Sort the universities by name
     *
     * 09-12-2023 - muj
     * @param array $a array object
     * @param array $b array object
     * @return int
     */
    function cmp(array $a, array $b): int
    {
        return strcmp($a["name"], $b["name"]);
    }

    usort($universities, "cmp");

} else {
    header('location: /login');
    exit();
}

if ($_SESSION['user']['usertype'] == 1) {

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

