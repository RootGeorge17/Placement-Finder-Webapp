<?php

require_once(base_path('models/Core/Database.php'));
require_once(base_path('models/DataSets/PlacementData.php'));


class PlacementsDataSet
{
    protected $dbHandle, $dbInstance;


    public function __construct() {
        $this->dbInstance = Database::getInstance();
        $this->dbHandle = $this->dbInstance->getdbConnection();
    }

    public function fetchAllPlacements(): array
    {
        $sqlQuery = 'SELECT * FROM placementData';

        $statement = $this->dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new PlacementData($row);
        }
        return $dataSet;
    }

}