<?php
require base_path("models/DataSets/SkillsDataSet.php");
require base_path("models/DataSets/CoursesDataSet.php");
require base_path("models/DataSets/ProficienciesDataSet.php");
require base_path("models/DataSets/IndustriesDataSet.php");

class GenerateStudentFormData
{
    protected $coursesDataSet, $proficienciesDataSet, $skillsDataSet, $industriesDataSet;

    public function __construct()
    {
        $this->proficienciesDataSet = new ProficienciesDataSet();
        $this->skillsDataSet = new SkillsDataSet();
        $this->coursesDataSet = new CoursesDataSet();
        $this->industriesDataSet = new IndustriesDataSet();
    }

    public function getCourses()
    {
        return $this->coursesDataSet->fetchAllCourses();
    }

    public function getSkills()
    {
        return $this->skillsDataSet->fetchAllSkills();
    }

    public function getProficiencies()
    {
        return $this->proficienciesDataSet->fetchAllProficiencies();
    }

    public function getIndustries()
    {
        return $this->industriesDataSet->fetchAllIndustries();
    }

    public function getUniversities()
    {
        // Get the JSON data
        $json = file_get_contents(base_path('models/JsonData/uk-universities.json'));
        // Convert JSON string to Array
        $universities = json_decode($json, true);

        function cmp(array $a, array $b): int
        {
            return strcmp($a["name"], $b["name"]);
        }

        usort($universities, "cmp");

        return $universities;
    }
}