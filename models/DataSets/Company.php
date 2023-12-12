<?php


class Company
{
    protected $id, $companyName, $companyDescription, $companyIndustry;

    public function __construct($dbRow)
    {
        $this->id = $dbRow['id'];
        $this->companyName = $dbRow['companyName'];
        $this->companyDescription = $dbRow['companyDescription'];
        $this->companyIndustry = $dbRow['companyIndustry'];
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCompanyName()
    {
        return $this->companyName;
    }

    public function getCompanyDescription(): mixed
    {
        return $this->companyDescription;
    }

    public function getCompanyIndustry(): mixed
    {
        return $this->companyIndustry;
    }


}