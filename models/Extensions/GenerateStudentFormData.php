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
                $userSkillsAndProficiencies[] =  [
                    'skill' => $userSkill,
                    'proficiency' => reset($foundProficiency), // Use reset to get the first element from the filtered array
                ];
            }
        }
        return $userSkillsAndProficiencies;
    }

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
    public function generateSelectOptions(string $selectedValue, array $optionsArray, string $getterFunction, int $option): void
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
    public function generateSkillSelectOptions(skill $selectedSkill, array $allSkills, skill $userSkills): void
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
    public function generateProficiencySelectOptions(array $selectedProficiency, array $allProficiencies, array $userProficiency): void
    {
        echo "<option value='" . $userProficiency['proficiency']->getProficiency() . "'>" . $userProficiency['proficiency']->getProficiency() . "</option>";
        foreach ($allProficiencies as $proficiency) {
            if ($proficiency->getProficiency() == $selectedProficiency['proficiency']->getProficiency()) {
                continue;
            }
            echo "<option value='" . $proficiency->getProficiency() . "'>" . $proficiency->getProficiency() . "</option>";
        }
    }

}