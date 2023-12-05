<?php

namespace models\DataSets;

class Proficiency
{

    protected $id, $proficiency;

    public function __construct($dbRow)
    {
        $this->id = $dbRow['id'];
        $this->proficiency = $dbRow['proficiency'];
    }

    public function getId()
    {
        return $this->id;
    }

    public function getProficiency()
    {
        return $this->proficiency;
    }


}