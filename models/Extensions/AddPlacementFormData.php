<?php

require base_path('models/DataSets/CompaniesDataSet.php');
require base_path('models/DataSets/UsersDataSet.php');
require base_path('models/DataSets/IndustriesDataSet.php');
require base_path('models/DataSets/ProficienciesDataSet.php');
require base_path('models/DataSets/SkillsDataSet.php');

class AddPlacementFormData {
    protected $companiesDataSet, $usersDataSet, $industriesDataSet, $proficienciesDataSet, $skillsDataSet;

    public function __construct()
    {
        $this->companiesDataSet = new CompaniesDataSet();
        $this->usersDataSet = new UsersDataSet();
        $this->industriesDataSet = new IndustriesDataSet();
        $this->proficienciesDataSet = new ProficienciesDataSet();
        $this->skillsDataSet = new SkillsDataSet();
    }

    public function getCompanies()
    {
        return $this->companiesDataSet->fetchAllCompanies();
    }

    public function getIndustries()
    {
        return $this->industriesDataSet->fetchAllIndustries();
    }

    public function getProficiencies()
    {
        return $this->proficienciesDataSet->fetchAllProficiencies();
    }

    public function getSkills()
    {
        return $this->skillsDataSet->fetchAllSkills();
    }

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

}