<?php

require_once(base_path('models\Core\Database.php'));
require_once(base_path('models\DataSets\StudentData.php'));

class StudentsDataSet
{
    protected $dbHandle, $dbInstance;


    public function __construct() {
        $this->dbInstance = Database::getInstance();
        $this->dbHandle = $this->dbInstance->getdbConnection();
    }

    public function fetchAllStudentData()
    {
        $sqlQuery = 'SELECT * FROM studentData';

        $statement = $this->dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new StudentData($row);
        }
        return $dataSet;
    }

    public function fetchStudentDataById($id)
    {
        $sqlQuery = 'SELECT * FROM studentData WHERE id = :id';

        $statement = $this->dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(['id' => $id]); // execute the PDO statement

        $row = $statement->fetch();
        return new StudentData($row);
    }

}