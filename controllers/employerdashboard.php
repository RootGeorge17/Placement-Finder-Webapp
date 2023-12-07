<?php

require(base_path("models/DataSets/PlacementsDataSet.php"));
require(base_path("models/DataSets/CompaniesDataSet.php"));
require(base_path("models/DataSets/SkillsDataSet.php"));
require(base_path("models/DataSets/StudentsDataSet.php"));

$placementsDataSet = new PlacementsDataSet();
$companiesDataSet = new CompaniesDataSet();
$skillsDataSet = new SkillsDataSet();
$studentsDataSet = new StudentsDataSet();
$companyPlacement = $placementsDataSet->fetchPlacementByCompanyId(1);
$allStudents = $studentsDataSet->fetchAllStudentData();



function getStudentMatches($placementData, $studentData){
    $matchedStudents = [];

    foreach ($studentData as $student) {
        // Implement your matching logic here
        // For example, matching based on skills
        $studentSkills = $student->getSkills(); // Assuming a method to retrieve skills

        foreach ($placementData as $placement) {
            $requiredSkills = $placement->getRequiredSkills(); // Assuming a method to retrieve required skills for a placement

            // Check if student's skills match the required skills for the placement
            if (array_intersect($studentSkills, $requiredSkills)) {
                $matchedStudents[] = $student;
                break; // Move to the next student once a match is found for this placement
            }
        }
    }

    return $matchedStudents;
}


view("employerdashboard.phtml",[
    'pageTitle' => 'Employer Dashboard',
]);