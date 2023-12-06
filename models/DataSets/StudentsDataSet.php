<?php

namespace models\DataSets;


require_once('models\Core\Database.php');
require_once('models\DataSets\StudentData.php');

class StudentsDataSet
{
    protected $dbHandle, $dbInstance;


    public function __construct() {
        $this->dbInstance = \models\Core\Database::getInstance();
        $this->dbHandle = $this->dbInstance->getdbConnection();
    }

    public function fetchAllStudents()
    {
        $sqlQuery = 'SELECT * FROM studentData';

        $statement = $this->dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement

        $dataSet = [];
        while ($row = $statement->fetch()) {
            var_dump($row);
            $dataSet[] = new StudentData($row);
        }
        return $dataSet;

    }

}