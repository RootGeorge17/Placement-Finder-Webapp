<?php

require_once base_path('models/DataSets/PlacementsDataSet.php');
require_once base_path('models/DataSets/SkillsDataSet.php');
require_once base_path('models/DataSets/ProficienciesDataSet.php');

class PlacementHelpers
{
    public function __construct()
    {
    }

    public function getPlacementMatches(StudentData $studentData, $placementData, $allProficiencies, $allSkills)
    {
        $skills = new SkillsDataSet();

        $studentSkills = [
            $studentData->getSkill1(),
            $studentData->getSkill2(),
            $studentData->getSkill3()
        ]; // array of skill ids of the student

        $studentSkillProficiencies = []; // array of skill ids of the student with the proficiency name

        foreach ($studentSkills as $studentSkill) { // get id of the skills
            foreach ($allSkills as $skill){
                if ($skill->getId() == $studentSkill){ // if the skill id matches the student skill id
                    foreach ($allProficiencies as $proficiency){ // go through all proficiencies
                        if ($proficiency->getId() == $skill->getProficiency()){
                            $studentSkillProficiencies[$studentSkill] = $proficiency->getProficiency();
                        }
                    }
                }
            }
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
                    $placementSkillProficiency = null;

                    foreach ($allSkills as $skill){
                        if ($skill->getId() == $studentSkill){
                            foreach ($allProficiencies as $proficiency){
                                if ($proficiency->getId() == $skill->getProficiency()){
                                    $placementSkillProficiency = $proficiency->getProficiency();
                                    break;
                                }
                            }
                        }
                    }

                    if ($studentSkillProficiency !== null && $placementSkillProficiency !== null && $studentSkillProficiency == $placementSkillProficiency) {
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

    public function getSkillNames($studentData, $allSkills){
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

    public function getStudentMatchesForCompany($companyId, $allStudents) {
        $placementDataSet = new PlacementsDataSet();
        $proficienciesDataSet = new ProficienciesDataSet();
        $skillsDataSet = new SkillsDataSet();
        $allProficiencies = $proficienciesDataSet->fetchAllProficiencies();
        $allSkills = $skillsDataSet->fetchAllSkills();
        $placementData = $placementDataSet->fetchPlacementsByCompanyId($companyId); // array of PlacementData objects

        $matches = [];

        foreach ($allStudents as $studentData){
            $studentMatches = $this->getPlacementMatches($studentData, $placementData, $allProficiencies, $allSkills);

            if (!empty($studentMatches['high']) || !empty($studentMatches['medium']) || !empty($studentMatches['low'])) {
                $matches[$studentData->getId()] = $studentMatches;
            }
        }
        return $matches;
    }
}