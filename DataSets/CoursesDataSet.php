<?php

namespace DataSets;
require_once('Core/Database.php');
require_once('DataSets/Course.php');

class CoursesDataSet
{
    protected $dbHandle, $dbInstance;


    public function __construct() {
        $this->dbInstance = \Core\Database::getInstance();
        $this->dbHandle = $this->dbInstance->getdbConnection();
    }

    public function fetchAllCourses()
    {
        $sqlQuery = 'SELECT * FROM courses';

        $statement = $this->dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new Course($row);
        }
        return $dataSet;

    }

}