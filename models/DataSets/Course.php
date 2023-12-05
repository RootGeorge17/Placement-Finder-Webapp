<?php

namespace models\DataSets;

class Course
{

    protected $id, $courseName;

    public function __construct($dbRow)
    {
        $this->id = $dbRow['id'];
        $this->courseName = $dbRow['courseName'];
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCourseName()
    {
        return $this->courseName;
    }



}