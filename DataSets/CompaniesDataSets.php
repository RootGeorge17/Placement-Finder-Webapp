<?php

namespace DataSets;
require_once('Core/Database.php');
require_once('DataSets/Company.php');

class CompaniesDataSets
{
    protected $dbHandle, $dbInstance;


    public function __construct() {
        $this->dbInstance = \Core\Database::getInstance();
        $this->dbHandle = $this->dbInstance->getdbConnection();
    }

    public function fetchAllCompanies()
    {
        $sqlQuery = 'SELECT * FROM company';

        $statement = $this->dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new Company($row);
        }
        return $dataSet;

    }

}