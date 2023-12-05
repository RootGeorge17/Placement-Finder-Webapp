<?php

namespace DataSets;
class PlacementData
{
    protected $id, $companyId, $company, $description, $industry, $salary, $location, $startDate, $endDate, $skill1, $skill2, $skill3;

    public function __construct($dbRow)
    {

        $this->id = $dbRow['id'];
        $this->companyId = $dbRow['companyId'];
        $this->company = $dbRow['company'];
        $this->description = $dbRow['description'];
        $this->industry = $dbRow['industry'];
        $this->salary = $dbRow['salary'];
        $this->location = $dbRow['location'];
        $this->startDate = $dbRow['startDate'];
        $this->endDate = $dbRow['endDate'];
        $this->skill1 = $dbRow['skill1'];
        $this->skill2 = $dbRow['skill2'];
        $this->skill3 = $dbRow['skill3'];

    }

    public function getId()
    {
        return $this->id;
    }

    public function getCompanyId()
    {
        return $this->companyId;
    }

    public function getCompany()
    {
        return $this->company;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getIndustry()
    {
        return $this->industry;
    }

    public function getSalary()
    {
        return $this->salary;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function getStartDate()
    {
        return $this->startDate;
    }

    public function getEndDate()
    {
        return $this->endDate;


    }

    public function getSkill1()
    {
        return $this->skill1;
    }

    public function getSkill2()
    {
        return $this->skill2;
    }

    public function getSkill3()
    {
        return $this->skill3;
    }








}