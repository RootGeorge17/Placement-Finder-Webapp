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
}