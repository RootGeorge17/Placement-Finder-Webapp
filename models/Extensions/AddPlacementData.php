<?php

require_once base_path("models/Extensions/GeneratePlacementData.php");

class AddPlacementData
{
    protected $placementsDataSet;
    protected $companyId, $description, $industryId, $location, $salary;
    protected $startDate, $endDate;
    protected $skill1Id, $skill2Id, $skill3Id;
    protected $skillsDataSet;

    public function __construct()
    {
    }

    function generatePlacementFormData() {
        return [
            'companies' => GeneratePlacementData::getCompanies(),
            'industries' => GeneratePlacementData::getIndustries(),
            'proficiencies' => GeneratePlacementData::getProficiencies(),
            'locations' => GeneratePlacementData::getLocations(),
            'skills' => GeneratePlacementData::getSkills()
        ];
    }


    public function addPlacement(): bool
    {
        $this->placementsDataSet = new PlacementsDataSet();
        $result = $this->placementsDataSet->addPlacement(
            $this->getCompanyId(), $this->getDescription(), $this->getIndustryId(),
            $this->getSalary(), $this->getLocation(), $this->getStartDate(),
            $this->getEndDate(), $this->getSkill1Id(), $this->getSkill2Id(), $this->getSkill3Id());

        if (!$result) {
            return false;
        }
        return true;
    }

    function getSkillsDataSet() {
        if (!$this->skillsDataSet) {
            $this->skillsDataSet = new SkillsDataSet();
        }
        return $this->skillsDataSet;
    }

    /**
     * @return mixed
     */
    public function getCompanyId()
    {
        return $this->companyId;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getIndustryId()
    {
        return $this->industryId;
    }

    /**
     * @return mixed
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @return mixed
     */
    public function getSalary()
    {
        return $this->salary;
    }

    /**
     * @return mixed
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @return mixed
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @return mixed
     */
    public function getSkill1Id()
    {
        return $this->skill1Id;
    }

    /**
     * @return mixed
     */
    public function getSkill2Id()
    {
        return $this->skill2Id;
    }

    /**
     * @return mixed
     */
    public function getSkill3Id()
    {
        return $this->skill3Id;
    }

    /**
     * @param mixed $companyId
     */
    public function setCompanyIdUsingUserId($userId): void
    {
        $usersDataSet = new UsersDataSet();
        $user = $usersDataSet->fetchUserById($userId);
        $this->companyId = $user->getCompanyId();
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * @param mixed $industryId
     */
    public function setIndustryId($industryId): void
    {
        $this->industryId = $industryId;
    }

    /**
     * @param mixed $location
     */
    public function setLocation($location): void
    {
        $this->location = $location;
    }

    /**
     * @param mixed $salary
     */
    public function setSalary($salary): void
    {
        $this->salary = $salary;
    }

    /**
     * @param mixed $startDate
     */
    public function setStartDate($startDate): void
    {
        $this->startDate = $startDate;
    }

    /**
     * @param mixed $endDate
     */
    public function setEndDate($endDate): void
    {
        $this->endDate = $endDate;
    }

    /**
     * @param mixed $skill1Id
     */
    public function setSkill1($skill1Name, $proficiency1Id): void
    {
        $selectedSkill = $this->getSkillsDataSet()->fetchSkillByNameAndProficiencyId($skill1Name, $proficiency1Id);

        if ($selectedSkill != null) {
            $this->skill1Id = $selectedSkill->getId();
        }
    }

    /**
     * @param mixed $skill2Id
     */
    public function setSkill2($skill2Name, $proficiency2Id): void
    {
        $selectedSkill = $this->getSkillsDataSet()->fetchSkillByNameAndProficiencyId($skill2Name, $proficiency2Id);

        if ($selectedSkill != null) {
            $this->skill2Id = $selectedSkill->getId();
        }
    }

    /**
     * @param mixed $skill3Id
     */
    public function setSkill3($skill3Name, $proficiency3Id): void
    {
        $selectedSkill = $this->getSkillsDataSet()->fetchSkillByNameAndProficiencyId($skill3Name, $proficiency3Id);

        if ($selectedSkill != null) {
            $this->skill3Id = $selectedSkill->getId();
        }
    }



}