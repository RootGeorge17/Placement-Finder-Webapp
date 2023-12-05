<?php

namespace DataSets;
require_once('Core/Database.php');
require_once('DataSets/Proficiency.php');

class ProficienciesDataSet
{
    protected $dbHandle, $dbInstance;


    public function __construct() {
        $this->dbInstance = \Core\Database::getInstance();
        $this->dbHandle = $this->dbInstance->getdbConnection();
    }

    public function fetchAllProficiencies()
    {
        $sqlQuery = 'SELECT * FROM proficiency';

        $statement = $this->dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new Proficiency($row);
        }
        return $dataSet;

    }

}