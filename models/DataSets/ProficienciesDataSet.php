<?php

require_once(base_path('models/Core/Database.php'));
require_once(base_path('models/DataSets/Proficiency.php'));

class ProficienciesDataSet
{
    protected $dbHandle, $dbInstance;


    public function __construct() {
        $this->dbInstance = Database::getInstance();
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

    public function fetchProficiencyById($id): ?Proficiency
    {
        $sqlQuery = 'SELECT * FROM proficiency WHERE id = :id';

        $statement = $this->dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute(); // execute the PDO statement

        if ($row = $statement->fetch()) {
            return new Proficiency($row);
        } else {
            return null;
        }
    }

}