<?php

namespace DataSets;
require_once('Core/Database.php');
require_once('DataSets/Industry.php');

class IndustriesDataSet
{
    protected $dbHandle, $dbInstance;


    public function __construct() {
        $this->dbInstance = \Core\Database::getInstance();
        $this->dbHandle = $this->dbInstance->getdbConnection();
    }

    public function fetchAllIndustries()
    {
        $sqlQuery = 'SELECT * FROM industry';

        $statement = $this->dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new Industry($row);
        }
        return $dataSet;

    }

}