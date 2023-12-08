<?php

require_once(base_path('models/Core/Database.php'));
require_once(base_path('models/DataSets/Course.php'));

class CoursesDataSet
{
    protected $dbHandle, $dbInstance;


    public function __construct() {
        $this->dbInstance = Database::getInstance();
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

    public function fetchCourseById($id)
    {
        $sqlQuery = 'SELECT * FROM courses WHERE id = :id';

        $statement = $this->dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(['id' => $id]); // execute the PDO statement

        $row = $statement->fetch();
        return new Course($row);
    }

}