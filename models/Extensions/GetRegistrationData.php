<?php

require base_path("models/DataSets/SkillsDataSet.php");
require base_path("models/DataSets/CoursesDataSet.php");
require base_path("models/DataSets/ProficienciesDataSet.php");
require base_path("models/DataSets/IndustriesDataSet.php");

class GetRegistrationData
{
    protected $coursesDataSet, $proficienciesDataSet, $skillsDataSet, $industriesDataSet;

    public function __construct()
    {

        $this->proficienciesDataSet = new ProficienciesDataSet();
        $this->skillsDataSet = new SkillsDataSet();
        $this->coursesDataSet = new CoursesDataSet();
        $this->industriesDataSet = new IndustriesDataSet();
    }

    public function getLocations()
    {
        $json = file_get_contents(base_path('models/JsonData/uk-cities.json'));

        $locations = json_decode($json, true);

        return $locations;
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
        return getUniversities();
    }
}