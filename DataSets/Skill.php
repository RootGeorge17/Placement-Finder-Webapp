<?php
namespace DataSets;
class skill
{

    protected $id, $skillName, $description, $proficiency;

    public function __construct($dbRow)
    {

        $this->id = $dbRow['id'];
        $this->skillName = $dbRow['skillName'];
        $this->description = $dbRow['description'];
        $this->proficiency = $dbRow['proficiency'];

    }

    public function getId()
    {
        return $this->id;
    }

    public function getSkillName()
    {
        return $this->skillName;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getProficiency()
    {
        return $this->proficiency;
    }



}