<?php

require_once(base_path("models/Extensions/GenerateRegistrationData.php"));

class Registration
{
    protected $userDataSet;
    protected $firstName, $lastName, $userType, $email, $password;
    protected $finishedStep;
    protected $contactNumber, $location, $course, $institution, $skill1, $skill2, $skill3, $proficiency1, $proficiency2, $proficiency3, $industry;
    protected $cv;
    protected $companyName, $companyDescription, $companyIndustry;

    function __construct()
    {

    }

    function generateStepTwoFormData() {
        return [
            'universities' => getUniversities(),
            'locations' => GenerateRegistrationData::getLocations(),
            'courses' => GenerateRegistrationData::getCourses(),
            'skills' => GenerateRegistrationData::getSkills(),
            'proficiencies' => GenerateRegistrationData::getProficiencies(),
            'industries' => GenerateRegistrationData::getIndustries()
        ];
    }

    function generateStepThreeFormData() {
        return [
            'industries' => GenerateRegistrationData::getIndustries(),
        ];
    }

    public function registerStudent()
    {
        $this->userDataSet = new UsersDataSet();
        $this->userDataSet->createStudentUser(
            $this->getFirstName(), $this->getLastName(), $this->getEmail(), $this->getPassword(),
            $this->getContactNumber(), $this->getLocation(), $this->getCourse(), $this->getInstitution(), $this->getIndustry(),
            $this->getSkill1(), $this->getSkill2(), $this->getSkill3(),
            $this->getProficiency1(), $this->getProficiency2(), $this->proficiency3, $this->getCv());
    }

    public function registerCompany()
    {
        $this->userDataSet = new UsersDataSet();
        $this->userDataSet->createCompanyUser(
            $this->getFirstName(), $this->getLastName(), $this->getEmail(), $this->getPassword(),
            $this->getContactNumber(), $this->getCompanyName(), $this->getCompanyDescription(), $this->getCompanyIndustry());
    }

    public function registerCareersUser()
    {
        $this->userDataSet = new UsersDataSet();
        $this->userDataSet->createCareersUser($this->getFirstName(), $this->getLastName(), $this->getEmail(), $this->getPassword(), $this->getContactNumber());
    }

    public function registerLibraryUser()
    {
        $this->userDataSet = new UsersDataSet();
        $this->userDataSet->createLibraryUser($this->getFirstName(), $this->getLastName(), $this->getEmail(), $this->getPassword(), $this->getContactNumber());
    }


    // Getters
    public function getFirstName()
    {
        return $this->firstName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function getUserType()
    {
        return $this->userType;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getStep()
    {
        return $this->finishedStep;
    }

    public function getContactNumber()
    {
        return $this->contactNumber;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function getCourse()
    {
        return $this->course;
    }

    public function getInstitution()
    {
        return $this->institution;
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

    public function getProficiency1()
    {
        return $this->proficiency1;
    }

    public function getProficiency2()
    {
        return $this->proficiency2;
    }

    public function getProficiency3()
    {
        return $this->proficiency3;
    }

    public function getIndustry()
    {
        return $this->industry;
    }

    public function getCv()
    {
        return $this->cv;
    }

    public function getCompanyName()
    {
        return $this->companyName;
    }

    public function getCompanyDescription()
    {
        return $this->companyDescription;
    }

    public function getCompanyIndustry()
    {
        return $this->companyIndustry;
    }

    // Setters
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    public function setUserType($userType)
    {
        $this->userType = $userType;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function setStep($step)
    {
        $this->finishedStep = $step;
    }

    public function setContactNumber($contactNumber)
    {
        $this->contactNumber = $contactNumber;
    }

    public function setLocation($location)
    {
        $this->location = $location;
    }

    public function setCourse($course)
    {
        $this->course = $course;
    }

    public function setInstitution($institution)
    {
        $this->institution = $institution;
    }

    public function setSkill1($skill1)
    {
        $this->skill1 = $skill1;
    }

    public function setSkill2($skill2)
    {
        $this->skill2 = $skill2;
    }

    public function setSkill3($skill3)
    {
        $this->skill3 = $skill3;
    }

    public function setProficiency1($proficiency1)
    {
        $this->proficiency1 = $proficiency1;
    }

    public function setProficiency2($proficiency2)
    {
        $this->proficiency2 = $proficiency2;
    }

    public function setProficiency3($proficiency3)
    {
        $this->proficiency3 = $proficiency3;
    }

    public function setIndustry($industry)
    {
        $this->industry = $industry;
    }

    public function setCv($cv)
    {
        $this->cv = $cv;
    }

    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;
    }

    public function setCompanyDescription($companyDescription)
    {
        $this->companyDescription = $companyDescription;
    }

    public function setCompanyIndustry($companyIndustry)
    {
        $this->companyIndustry = $companyIndustry;
    }
}