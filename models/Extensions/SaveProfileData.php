<?php

require_once base_path("models/DataSets/StudentsDataSet.php");

class SaveProfileData
{

    protected User $user; // the user object
    protected $firstName, $lastName, $email,$contactNumber; // common to all users
    protected $location, $course, $institution, $preferredIndustry; // student
    protected $skill1, $skill2, $skill3; // student
    protected $proficiency1, $proficiency2, $proficiency3; // student
    protected $companyName, $companyDescription, $companyIndustry; // employer

    public function __construct()
    {
    }

    #region setters
    public function setUser($user): void
    {
        $this->user = $user;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @param mixed $contactNumber
     */
    public function setContactNumber($contactNumber): void
    {
        $this->contactNumber = $contactNumber;
    }

    /**
     * @param mixed $location
     */
    public function setLocation($location): void
    {
        $this->location = $location;
    }

    /**
     * @param mixed $course
     */
    public function setCourse($course): void
    {
        $this->course = $course;
    }

    /**
     * @param mixed $institution
     */
    public function setInstitution($institution): void
    {
        $this->institution = $institution;
    }

    /**
     * @param mixed $preferredIndustry
     */
    public function setPreferredIndustry($preferredIndustry): void
    {
        $this->preferredIndustry = $preferredIndustry;
    }

    /**
     * @param mixed $skill1
     */
    public function setSkill1($skill1): void
    {
        $this->skill1 = $skill1;
    }

    /**
     * @param mixed $skill2
     */
    public function setSkill2($skill2): void
    {
        $this->skill2 = $skill2;
    }

    /**
     * @param mixed $skill3
     */
    public function setSkill3($skill3): void
    {
        $this->skill3 = $skill3;
    }

    /**
     * @param mixed $proficiency1
     */
    public function setProficiency1($proficiency1): void
    {
        $this->proficiency1 = $proficiency1;
    }

    /**
     * @param mixed $proficiency2
     */
    public function setProficiency2($proficiency2): void
    {
        $this->proficiency2 = $proficiency2;
    }

    /**
     * @param mixed $proficiency3
     */
    public function setProficiency3($proficiency3): void
    {
        $this->proficiency3 = $proficiency3;
    }

    /**
     * @param mixed $companyName
     */
    public function setCompanyName($companyName): void
    {
        $this->companyName = $companyName;
    }

    /**
     * @param mixed $companyDescription
     */
    public function setCompanyDescription($companyDescription): void
    {
        $this->companyDescription = $companyDescription;
    }

    /**
     * @param mixed $companyIndustry
     */
    public function setCompanyIndustry($companyIndustry): void
    {
        $this->companyIndustry = $companyIndustry;
    }
    #endregion

    /**
     * make sure to set the values before calling this function!
     * @return mixed
     */
    public function saveStudentData(): bool
    {
        $this->saveCommonData(); // save common data first

        $studentDataSet = new StudentsDataSet();
        $result = $studentDataSet->updateStudentUser(
            $this->email, $this->location, $this->course,
            $this->institution, $this->preferredIndustry,
            $this->skill1, $this->skill2, $this->skill3,
            $this->proficiency1, $this->proficiency2, $this->proficiency3);

        if (!$result) {
            return false;
        }
        return true;
    }

    public function saveCompanyData():bool
    {
        $this->saveCommonData(); // save common data first

        $companiesDataSet = new CompaniesDataSet();
        $result = $companiesDataSet->updateCompanyData(
            $this->user->getCompanyId(),
            $this->companyName,
            $this->companyDescription,
            $this->companyIndustry);

        if (!$result) {
            return false;
        }
        return true;
    }

    /**
     * make sure to set the values before calling this function!
     * uses the user object, firstName, lastName, email, contactNumber
     * @return mixed
     */
    public function saveCommonData():bool
    {
        $usersDataSet = new UsersDataSet();
        $result = $usersDataSet->updateUser(
            $this->user->getId(),
            $this->firstName,
            $this->lastName,
            $this->email,
            $this->contactNumber);

        if (!$result) {
            return false;
        }
        return true;
    }


}