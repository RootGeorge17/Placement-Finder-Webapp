<?php

namespace models\DataSets;

require_once('models/Core/Database.php');
require_once('models/DataSets/PlacementData.php');


class PlacementsDataSet
{
    protected $dbHandle, $dbInstance;


    public function __construct() {
        $this->dbInstance = \models\Core\Database::getInstance();
        $this->dbHandle = $this->dbInstance->getdbConnection();
    }

    public function fetchAllUserTypes()
    {
        $sqlQuery = 'SELECT * FROM placementData';

        $statement = $this->dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new UserType($row);
        }
        return $dataSet;

    }
}