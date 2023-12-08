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

        $matches = [
            'excellent' => [],
            'good' => [],
            'poor' => []
        ];

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
                $matches['excellent'][] = $placement; // skills match and proficiencies match
            } elseif ($skillMatchCount === 2 && $sameProficiencyCount === 2) {
                $matches['good'][] = $placement; // if a couple of skills match and proficiencies match
            } elseif ($skillMatchCount === 3 && $sameProficiencyCount !== 3) {
                $matches['good'][] = $placement; // if all skills match but proficiencies don't match
            } elseif ($skillMatchCount >= 1 && $sameProficiencyCount >= 1) {
                $matches['poor'][] = $placement; // if at least one skill matches and at least one proficiency matches
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
        $skillMap = [];
        $proficiencyMap = [];

        // Create a mapping of skill IDs to skill objects
        foreach ($allSkills as $skill) {
            $skillMap[$skill->getId()] = $skill;
        }

        // Create a mapping of proficiency IDs to proficiency objects
        foreach ($allProficiencies as $proficiency) {
            $proficiencyMap[$proficiency->getId()] = $proficiency;
        }

        $studentSkillsIds = [
            $studentData->getSkill1(),
            $studentData->getSkill2(),
            $studentData->getSkill3()
        ];

        foreach ($studentSkillsIds as $studentSkillId) {
            if (isset($skillMap[$studentSkillId])) {
                $skill = $skillMap[$studentSkillId];
                $skillNames[$studentSkillId] = [
                    'skillId' => $skill->getId(),
                    'skillName' => $skill->getSkillName(),
                    'skillProficiency' => $proficiencyMap[$skill->getProficiency()]->getProficiency(),
                ];
            }
        }

        return $skillNames;
    }


    /**
     * this function returns the proficiency name of a skill
     *
     * muj - 07/12/2023
     * @param Skill $skill the skill you want the proficiency name for
     * @param Proficiency[] $allProficiencies all proficiencies
     * @return string
     */
    public function getProficiencyFromSkill(Skill $skill, array $allProficiencies): string
    {
        $proficiencyMap = [];
        foreach ($allProficiencies as $proficiency) {
            $proficiencyMap[$proficiency->getId()] = $proficiency->getProficiency();
        }

        $skillProficiencyId = $skill->getProficiency();
        if (isset($proficiencyMap[$skillProficiencyId])) {
            return $proficiencyMap[$skillProficiencyId];
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
        $placementData = $placementDataSet->fetchPlacementsByCompanyId($companyId);

        $matches = [];

        foreach ($placementData as $placement) {
            $placementId = $placement->getId();
            $placementMatches = [
                'placement' => $placement,
                'students' => []
            ];

            foreach ($allStudents as $studentData) {
                $studentMatches = $this->getPlacementMatches($studentData, [$placement], $allProficiencies, $allSkills);

                if (!empty($studentMatches['excellent']) || !empty($studentMatches['good']) || !empty($studentMatches['poor'])) {
                    $studentId = $studentData->getId();

                    // Calculate match grade here based on $studentMatches
                    $matchGrade = $this->calculateMatchGrade($studentMatches);

                    // Include match grade in the student's matches
                    $placementMatches['students'][$studentId] = [
                        'matches' => $studentMatches,
                        'grade' => $matchGrade, // Add match grade here
                        'studentData' => $studentData,
                    ];
                }
            }

            if (!empty($placementMatches['students'])) {
                $matches[$placementId] = $placementMatches;
            }
        }

        return $matches;
    }

    private function calculateMatchGrade(array $studentMatches): string
    {
        if (!empty($studentMatches['excellent'])) {
            return 'Excellent';
        } elseif (!empty($studentMatches['good'])) {
            return 'Good';
        } elseif (!empty($studentMatches['poor'])) {
            return 'Poor';
        } else {
            return 'No Match';
        }
    }

}