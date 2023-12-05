<?php

namespace models\DataSets;

class Industry
{

    protected $id, $industry;

    public function __construct($dbRow)
    {
        $this->id = $dbRow['id'];
        $this->industry = $dbRow['industry'];
    }

    public function getId()
    {
        return $this->id;
    }

    public function getIndustry()
    {
        return $this->industry;
    }


}