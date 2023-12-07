<?php

require_once base_path('models/DataSets/PlacementsDataSet.php');
require_once base_path('models/DataSets/SkillsDataSet.php');
require_once base_path('models/DataSets/ProficienciesDataSet.php');

class PlacementHelpers
{
    public function __construct()
    {
    }

    /**
     * this function returns an array of placements that match the student's skills and proficiencies
     *
     * muj - 07/12/2023
     * @param StudentData $studentData the student you want the matches for
     * @param PlacementData[] $placementsData
     * @param Proficiency[] $allProficiencies all proficiencies
     * @param skill[] $allSkills all skills
     * @return array
     */
    public function getPlacementMatches(StudentData $studentData, $placementsData, array $allProficiencies, array $allSkills): array
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

        foreach ($placementsData as $placement) {
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

    /**
     * this function returns an array of skill names the student has
     *
     * muj - 07/12/2023
     * @param StudentData $studentData the student you want the skill names for
     * @param skill[] $allSkills all skills
     * @return array
     */
    public function getSkillNames(StudentData $studentData, array $allSkills, array $allProficiencies): array
    {
        $skillNames = [];
        $studentSkillsIds = [
            $studentData->getSkill1(),
            $studentData->getSkill2(),
            $studentData->getSkill3()
        ];

        foreach ($studentSkillsIds as $studentSkillId) {
            foreach ($allSkills as $skill) {
                if ($skill->getId() == $studentSkillId) {
                    $skillId = $skill->getId();
                    $skillName = $skill->getSkillName();
                    $studentSkillProficiency = $skill->getProficiency();

                    // Find proficiency level based on skill proficiency
                    foreach ($allProficiencies as $proficiency) {
                        if ($proficiency->getId() === $studentSkillProficiency) {
                            $skillNames[$studentSkillId] = [
                                'skillId' => $skillId,
                                'skillName' => $skillName,
                                'skillProficiency' => $proficiency->getProficiency(),
                            ];
                            break; // Stop checking other proficiencies once a match is found
                        }
                    }
                    break; // Stop checking other skills once a match is found
                }
            }
        }
        return $skillNames;
    }

    public function getProficiencyFromSkill(skill $skill, array $allProficiencies): string
    {
        foreach ($allProficiencies as $proficiency) {
            if ($proficiency->getId() == $skill->getProficiency()) {
                return $proficiency->getProficiency();
            }
        }
        return '';
    }

    /**
     * this function returns an array of students and the placements that match their skills and proficiencies
     * that the company has posted
     *
     * muj - 07/12/2023
     * @param int $companyId the company id you want the matches for
     * @param StudentData[] $allStudents all students
     * @return array
     */
    public function getStudentMatchesForCompany(int $companyId, array $allStudents): array
    {
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