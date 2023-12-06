<?php


class Company
{
    protected $id, $companyName;

    public function __construct($dbRow)
    {
        $this->id = $dbRow['id'];
        $this->companyName = $dbRow['companyName'];
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCompanyName()
    {
        return $this->companyName;
    }

}