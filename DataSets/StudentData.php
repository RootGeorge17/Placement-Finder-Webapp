<?php

namespace DataSets;
    class StudentData
    {
        protected $id, $firstName, $lastName, $email, $phoneNumber, $location, $cv, $course, $institution, $prefIndustry, $skill1, $skill2, $skill3;


        public function __construct($dbRow)
        {
            $this->id = $dbRow['id'];
            $this->firstName = $dbRow['firstName'];
            $this->lastName = $dbRow['lastName'];
            $this->email = $dbRow['email'];
            $this->phoneNumber = $dbRow['phoneNumber'];
            $this->location = $dbRow['location'];
            $this->cv = $dbRow['cv'];
            $this->course = $dbRow['course'];
            $this->institution = $dbRow['institution'];
            $this->prefIndustry = $dbRow['prefIndustry'];
            $this->skill1 = $dbRow['skill1'];
            $this->skill2 = $dbRow['skill2'];
            $this->skill3 = $dbRow['skill3'];

        }


        public function getId()
        {
            return $this->id;
        }

        public function getFirstName()
        {
            return $this->firstName;
        }

        public function getLastName()
        {
            return $this->lastName;
        }

        public function getEmail()
        {
            return $this->email;
        }

        public function getPhoneNumber()
        {
            return $this->phoneNumber;
        }

        public function getLocation()
        {
            return $this->location;
        }

        public function getCv()
        {
            return $this->cv;
        }

        public function getCourse()
        {
            return $this->course;
        }

        public function getInstitution()
        {
            return $this->institution;
        }

        public function getPrefIndustry()
        {
            return $this->prefIndustry;
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
