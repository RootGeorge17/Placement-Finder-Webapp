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

    public function fetchCompanyEmail($id): mixed
    {
        $sqlQuery = 'SELECT u.email FROM company c
                 JOIN user u ON c.id = u.companyData
                 WHERE u.companyData = :id';

        $statement = $this->dbHandle->prepare($sqlQuery);
        $statement->execute([
            ':id' => $id
        ]);

        if ($row = $statement->fetch()){
            return $row['email'];
        } else {
            return null;
        }
    }

    public function isUserCompanyName($userId, $companyName): bool
    {
        $sqlQuery = 'SELECT company.* 
                     FROM user
                     INNER JOIN company ON user.companyData = company.id
                     WHERE user.id = :userId AND company.companyName = :companyName';

        $statement = $this->dbHandle->prepare($sqlQuery);
        $statement->execute([
            ':userId' => $userId,
            ':companyName' => $companyName
        ]);

        if ($statement->fetch()){
            return true;
        } else {
            return false;
        }
    }

    public function companyNameMatch($companyName)
    {
        $sqlQuery = 'SELECT * FROM company WHERE companyName = :companyName';

        $statement = $this->dbHandle->prepare($sqlQuery);
        $statement->execute([
            ':companyName' => $companyName
        ]);

        if ($statement->fetch()){
            return true;
        } else {
            return false;
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

    public function updateCompanyData($id, $companyName, $companyDescription, $companyIndustry): bool
    {
        $sqlQuery = 'UPDATE company 
                     SET companyName = :companyName, companyDescription = :companyDescription, companyIndustry = :companyIndustry 
                     WHERE id = :id';

        $statement = $this->dbHandle->prepare($sqlQuery);
        $statement->execute([
            ':companyName' => $companyName,
            ':companyDescription' => $companyDescription,
            ':companyIndustry' => $companyIndustry,
            ':id' => $id
        ]);

        if ($statement->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteCompanyData($id): bool
    {
        $sqlQuery = 'DELETE FROM company WHERE id = :id';

        $statement = $this->dbHandle->prepare($sqlQuery);
        $statement->execute([
            ':id' => $id
        ]);

        if ($statement->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

}