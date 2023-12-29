<?php

require_once(base_path('models/Core/Database.php'));
require_once(base_path('models/DataSets/UserType.php'));


class UserTypesDataSet
{
    protected $dbHandle, $dbInstance;


    public function __construct() {
        $this->dbInstance = Database::getInstance();
        $this->dbHandle = $this->dbInstance->getdbConnection();
    }

    public function fetchAllUserTypes()
    {
        $sqlQuery = 'SELECT * FROM userTypes';

        $statement = $this->dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new UserType($row);
        }
        return $dataSet;
    }

    public function fetchUserTypeById($id)
    {
        $sqlQuery = 'SELECT * FROM userTypes WHERE id = :id';

        $statement = $this->dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(['id' => $id]); // execute the PDO statement

        if($row = $statement->fetch()) {
            return new UserType($row);
        } else {
            return null;
        }
    }
}