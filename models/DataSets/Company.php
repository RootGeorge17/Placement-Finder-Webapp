<?php

namespace models\DataSets;

class Company
{
    protected $id, $companyName, $email, $phoneNumber;

    public function __construct($dbRow)
    {
        $this->id = $dbRow['id'];
        $this->companyName = $dbRow['companyName'];
        $this->email = $dbRow['email'];
        $this->phoneNumber = $dbRow['phoneNumber'];
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCompanyName()
    {
        return $this->companyName;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }


}