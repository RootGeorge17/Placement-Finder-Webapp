<?php

class GenerateStudentFormData
{
    protected $userStudentData, $user;
    protected $coursesDataSet, $proficienciesDataSet, $skillsDataSet, $usersDataSet, $studentDataSet;

    public function __construct()
    {
        $this->coursesDataSet = new CoursesDataSet();
        $this->proficienciesDataSet = new ProficienciesDataSet();
        $this->skillsDataSet = new SkillsDataSet();
        $this->usersDataSet = new UsersDataSet();
        $this->studentDataSet = new StudentsDataSet();
    }

    // returns the user object
    public function getUser()
    {
        return $this->user;
    }

    // fetches from the database the user using the id
    public function setUser($id): void
    {
        $this->user = $this->usersDataSet->fetchUserById($id);
    }

    // fetches from the database studentData using the studentDataId from the user table
    public function getUserStudentData(): ?StudentData
    {
        if ($this->userStudentData == null) {
            $this->userStudentData = $this->studentDataSet->fetchStudentDataById($this->user->getStudentData()); // this method is badly named should be getStudentDataId
            return $this->userStudentData;
        }
        return null;
    }

    // fetches from the database the course using the courseId from the studentData table
    public function getPreferredCourse(): ?Course
    {
        if ($this->userStudentData != null) {
            return $this->coursesDataSet->fetchCourseById($this->userStudentData->getCourse()); // preferredCourseId
        }
        return null;
    }

    // parses the university data from the json file
    public function getUniversities()
    {
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

        return $universities;
    }

    public function getLocations()
    {
        $json = file_get_contents(base_path('models/JsonData/uk-cities.json'));

        $locations = json_decode($json, true);

        return $locations;
    }

    // maps the user skills objects to the proficiencies
    public function getStudentSkillsAndProficiencies($userSkills, $allProficiencies): array
    {
        $userSkillsAndProficiencies = [];

        foreach ($userSkills as $userSkill) { // loop through the user skills
            $proficiencyId = $userSkill->getProficiency(); // get the proficiency id from the user skill

            // Find the proficiency in the fetched list by ID
            $foundProficiency = array_filter($allProficiencies, function ($proficiency) use ($proficiencyId) {
                return $proficiency->getId() === $proficiencyId; // return the proficiency if the id matches
            });

            // If the proficiency is found, add it to the result
            if (!empty($foundProficiency)) {
                $userSkillsAndProficiencies[] = [
                    'skill' => $userSkill,
                    'proficiency' => reset($foundProficiency), // Use reset to get the first element from the filtered array
                ];
            }
        }
        return $userSkillsAndProficiencies;
    }

}