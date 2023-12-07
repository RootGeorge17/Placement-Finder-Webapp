<?php
    class User
    {
        protected $id, $username, $password, $firstName, $lastName, $email, $phoneNumber, $userType, $studentData, $companyData;


        public function __construct($dbRow)

        {
            $this->id = $dbRow['id'];
            $this->username = $dbRow['username'];
            $this->password = $dbRow['password'];
            $this->email = $dbRow['email'];
            $this->firstName = $dbRow['firstName'];
            $this->lastName = $dbRow['lastName'];
            $this->phoneNumber = $dbRow['phoneNumber'] ?? null;
            $this->userType = $dbRow['userType'];
            $this->studentData = $dbRow['studentData'] ?? null;
            $this->companyData = $dbRow['companyData'] ?? null;
        }

        public function getFirstName(): mixed
        {
            return $this->firstName;
        }

        public function getLastName(): mixed
        {
            return $this->lastName;
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

        public function getEmail(): mixed
        {
            return $this->email;
        }

        public function getPhoneNumber(): mixed
        {
            return $this->phoneNumber;
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