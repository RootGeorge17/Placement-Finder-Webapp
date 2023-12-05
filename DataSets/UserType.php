<?php

namespace DataSets;

class UserType
{
    protected $id, $type;

    public function __construct($dbRow)
    {
        $this->id = $dbRow['id'];
        $this->type = $dbRow['type'];
    }
    public function getId()
    {
        return $this->id;
    }

    public function getType()
    {
        return $this->type;
    }



}