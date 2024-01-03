<?php

class GeneratePlacementData
{
    protected static $companiesDataSet, $usersDataSet, $industriesDataSet, $proficienciesDataSet, $skillsDataSet;

    public static function initializeDataSets()
    {
        require base_path("models/DataSets/SkillsDataSet.php");
        require base_path("models/DataSets/CompaniesDataSet.php");
        require base_path("models/DataSets/IndustriesDataSet.php");
        require base_path("models/DataSets/ProficienciesDataSet.php");
        require base_path("models/DataSets/UsersDataSet.php");
        require base_path("models/DataSets/PlacementsDataSet.php");
        self::$proficienciesDataSet = new ProficienciesDataSet();
        self::$skillsDataSet = new SkillsDataSet();
        self::$companiesDataSet = new CompaniesDataSet();
        self::$industriesDataSet = new IndustriesDataSet();
        self::$usersDataSet = new UsersDataSet();
    }

    public static function getLocations()
    {
        $json = file_get_contents(base_path('models/JsonData/uk-cities.json'));

        $locations = json_decode($json, true);

        return $locations;
    }

    public static function getInstitutions()
    {
        $json = file_get_contents(base_path('models/JsonData/uk-universities.json'));

        $institutions = json_decode($json, true);

        usort($institutions, function($a, $b) {
            return $a['name'] <=> $b['name'];
        });

        return $institutions;
    }

    public static function getCompanies()
    {
        if (!self::$companiesDataSet) {
            self::initializeDataSets();
        }

        return self::$companiesDataSet->fetchAllCompanies();
    }

    public static function getIndustries()
    {
        if (!self::$industriesDataSet) {
            self::initializeDataSets();
        }

        return self::$industriesDataSet->fetchAllIndustries();
    }

    public static function getProficiencies()
    {
        if (!self::$proficienciesDataSet) {
            self::initializeDataSets();
        }

        return self::$proficienciesDataSet->fetchAllProficiencies();
    }

    public static function getSkills()
    {
        if (!self::$skillsDataSet) {
            self::initializeDataSets();
        }

        return self::$skillsDataSet->fetchAllSkills();
    }

    public static function getUsers()
    {
        if (!self::$usersDataSet) {
            self::initializeDataSets();
        }

        return self::$usersDataSet->fetchAllUsers();
    }

    public static function getUsersDataSet()
    {
        if (!isset(self::$usersDataSet)) {
            self::initializeDataSets();
        }

        return self::$usersDataSet;
    }

}