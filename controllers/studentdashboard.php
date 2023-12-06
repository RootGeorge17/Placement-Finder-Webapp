<?php

//dd(base_path('models/DataSets/Company.php'));
require(base_path("models/DataSets/PlacementsDataSet.php"));
require(base_path("models/DataSets/CompaniesDataSet.php"));
require(base_path("models/DataSets/SkillsDataSet.php"));
require(base_path("models/DataSets/StudentsDataSet.php"));


$placementsDataSet = new PlacementsDataSet();
$companiesDataSet = new CompaniesDataSet();
$skillsDataSet = new SkillsDataSet();
$studentsDataSet = new StudentsDataSet();
$allPlacements = $placementsDataSet->fetchAllPlacements();
$allCompanies = $companiesDataSet->fetchAllCompanies();
$allSkills = $skillsDataSet->fetchAllSkills();
$testStudent = $studentsDataSet->fetchStudentDataById(1);

function getMatches(StudentData $studentData, $placementData, $skillsDataSet)
{
    $skills = new SkillsDataSet();

    $studentSkills = [
        $studentData->getSkill1(),
        $studentData->getSkill2(),
        $studentData->getSkill3()
    ];

    $studentSkillProficiencies = [];
    foreach ($studentSkills as $skill) {
        $studentSkillProficiencies[$skill] = $skills->fetchProficiencyById($skill);
    }

    $matches = [];

    foreach ($placementData as $placement) {
        $skillMatchCount = 0;
        $sameProficiencyCount = 0;

        $placementSkills = [
            $placement->getSkill1(),
            $placement->getSkill2(),
            $placement->getSkill3()
        ];

        foreach ($studentSkills as $studentSkill) {
            if (in_array($studentSkill, $placementSkills)) {
                $skillMatchCount++;

                $studentSkillProficiency = $studentSkillProficiencies[$studentSkill];
                $placementSkillProficiency = $skills->fetchProficiencyById($studentSkill);

                if ($studentSkillProficiency == $placementSkillProficiency) {
                    $sameProficiencyCount++;
                }
            }
        }

        if ($skillMatchCount === 3 && $sameProficiencyCount === 3) {
            $matches['high'][] = $placement; // skills match and proficiencies match
        } elseif ($skillMatchCount === 2 && $sameProficiencyCount === 2) {
            $matches['medium'][] = $placement; // if a couple of skills match and proficiencies match
        } elseif ($skillMatchCount === 3 && $sameProficiencyCount !== 3) {
            $matches['medium'][] = $placement; // if all skills match but proficiencies don't match
        } elseif ($skillMatchCount >= 1 && $sameProficiencyCount >= 1) {
            $matches['low'][] = $placement; // if at least one skill matches and at least one proficiency matches
        }
    }
    return $matches;
}

function getSkillNames($studentData, $allSkills){
    $skillNames = [];
    foreach ($allSkills as $skill){
        if ($skill->getId() == $studentData->getSkill1()){
            $skillNames[] = $skill->getSkillName();
        }
        if ($skill->getId() == $studentData->getSkill2()){
            $skillNames[] = $skill->getSkillName();
        }
        if ($skill->getId() == $studentData->getSkill3()){
            $skillNames[] = $skill->getSkillName();
        }
    }
    return $skillNames;
}

view("studentdashboard.phtml",[
    'pageTitle' => 'Dashboard',
    'placementsDataSet' => $placementsDataSet,
    'allPlacements' => $allPlacements,
    'companiesDataSet' => $companiesDataSet,
    'allCompanies' => $allCompanies,
    'testStudent' => $testStudent,
    'allSkills' => $allSkills,
]);