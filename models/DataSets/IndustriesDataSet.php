<?php

require_once(base_path('models/Core/Database.php'));
require_once(base_path('models/DataSets/Industry.php'));

class IndustriesDataSet
{
    protected $dbHandle, $dbInstance;


    public function __construct() {
        $this->dbInstance = Database::getInstance();
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

    public function fetchIndustryNameById($id): mixed
    {
        $sqlQuery = 'SELECT industry FROM industry WHERE id = :id';

        $statement = $this->dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute(); // execute the PDO statement

        if ($row = $statement->fetch()) {
            return $row['industry'];
        } else {
            return null;
        }
    }

    public function fetchIndustryById($id): ?Industry
    {
        $sqlQuery = 'SELECT * FROM industry WHERE id = :id';

        $statement = $this->dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute(); // execute the PDO statement

        if ($row = $statement->fetch()) {
            return new Industry($row);
        } else {
            return null;
        }
    }
}