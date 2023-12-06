<?php

require_once(base_path('models/Core/Database.php'));
require_once(base_path('models/DataSets/Company.php'));

class CompaniesDataSet
{
    protected $dbHandle, $dbInstance;


    public function __construct() {
        $this->dbInstance = Database::getInstance();
        $this->dbHandle = $this->dbInstance->getdbConnection();
    }

    public function fetchAllCompanies(): array
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

    public function fetchCompanyName($id){
        $sqlQuery = 'SELECT companyName FROM company WHERE id = :id';

        $statement = $this->dbHandle->prepare($sqlQuery);
        $statement->execute([
            ':id' => $id
        ]);

        return $statement->fetch();
    }

}