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

    public function fetchCompanyName($id): mixed
    {
        $sqlQuery = 'SELECT companyName FROM company WHERE id = :id';

        $statement = $this->dbHandle->prepare($sqlQuery);
        $statement->execute([
            ':id' => $id
        ]);

        if ($row = $statement->fetch()){
            return $row['companyName'];
        } else {
            return null;
        }
    }

    public function fetchCompanyById($id): ?Company
    {
        $sqlQuery = 'SELECT * FROM company WHERE id = :id';

        $statement = $this->dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(['id' => $id]); // execute the PDO statement

        if($row = $statement->fetch()) {
            return new Company($row);
        } else {
            return null;
        }
    }

}