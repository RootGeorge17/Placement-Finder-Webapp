<?php

class GenerateRegistrationData
{
    protected static $coursesDataSet, $proficienciesDataSet, $skillsDataSet, $industriesDataSet;

    public static function initializeDataSets()
    {
        require base_path("models/DataSets/SkillsDataSet.php");
        require base_path("models/DataSets/CoursesDataSet.php");
        require base_path("models/DataSets/ProficienciesDataSet.php");
        require base_path("models/DataSets/IndustriesDataSet.php");
        self::$proficienciesDataSet = new ProficienciesDataSet();
        self::$skillsDataSet = new SkillsDataSet();
        self::$coursesDataSet = new CoursesDataSet();
        self::$industriesDataSet = new IndustriesDataSet();
    }

    public static function getLocations()
    {
        $json = file_get_contents(base_path('models/JsonData/uk-cities.json'));

        $locations = json_decode($json, true);

        return $locations;
    }

    public static function getCourses()
    {
        if (!self::$coursesDataSet) {
            self::initializeDataSets();
        }

        return self::$coursesDataSet->fetchAllCourses();
    }

    public static function getSkills()
    {
        if (!self::$skillsDataSet) {
            self::initializeDataSets();
        }

        return self::$skillsDataSet->fetchAllSkills();
    }

    public static function getProficiencies()
    {
        if (!self::$proficienciesDataSet) {
            self::initializeDataSets();
        }

        return self::$proficienciesDataSet->fetchAllProficiencies();
    }

    public static function getIndustries()
    {
        if (!self::$industriesDataSet) {
            self::initializeDataSets();
        }

        return self::$industriesDataSet->fetchAllIndustries();
    }
}
