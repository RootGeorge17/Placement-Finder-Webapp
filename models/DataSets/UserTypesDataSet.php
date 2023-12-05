<?php

namespace models\DataSets;

require_once('Core/Database.php');
require_once('DataSets/UserType.php');


class UserTypesDataSet
{
    protected $dbHandle, $dbInstance;


    public function __construct() {
        $this->dbInstance = \models\Core\Database::getInstance();
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
}