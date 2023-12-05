<?php

namespace models\DataSets;
    class User
    {
        protected $id, $username, $password, $userType, $studentData, $companyData;


        public function __construct($dbRow)

        {
            $this->id = $dbRow['id'];
            $this->username = $dbRow['username'];
            $this->password = $dbRow['password'];
            $this->userType = $dbRow['userType'];
            $this->studentData = $dbRow['studentData'];
            $this->companyData = $dbRow['companyData'];
        }

        public function getId()
        {
            return $this->id;
        }

        public function getUsername()
        {
            return $this->username;
        }

        public function getPassword()
        {
            return $this->password;
        }

        public function getUserType()
        {
            return $this->userType;
        }

        public function getStudentData()
        {
            return $this->studentData;
        }

        public function getCompanyData()
        {
            return $this->companyData;
        }







    }