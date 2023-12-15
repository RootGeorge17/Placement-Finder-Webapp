<?php

require_once(base_path('models/Core/Database.php'));
require_once(base_path('models/DataSets/StudentData.php'));

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

    public function fetchRowCountAll()
    {
        $sqlQuery = 'SELECT COUNT(*) FROM studentData';

        $statement = $this->dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement

        return $statement->fetchColumn();
    }

    public function fetchByLimit(int $start, int $limit) : array
    {
        $sqlQuery = 'SELECT * FROM studentData LIMIT :start, :limit';

        $statement = $this->dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindParam(':start', $start, PDO::PARAM_INT);
        $statement->bindParam(':limit', $limit, PDO::PARAM_INT);
        $statement->execute(); // execute the PDO statement

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new StudentData($row);
        }
        return $dataSet;
    }

}