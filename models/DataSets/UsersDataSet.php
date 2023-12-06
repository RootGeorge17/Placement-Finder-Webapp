<?php
require_once(base_path('models\Core\Database.php'));
require_once(base_path('models\DataSets\User.php'));
class UsersDataSet
{
    protected $dbHandle, $dbInstance;


    public function __construct() {
        $this->dbInstance = Database::getInstance();
        $this->dbHandle = $this->dbInstance->getdbConnection();
    }

    public function fetchAllUsers()
    {
        $sqlQuery = 'SELECT * FROM user';

        $statement = $this->dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement

        $dataSet = [];
        while ($row = $statement->fetch()) {
            var_dump($row);
            $dataSet[] = new User($row);
        }
        return $dataSet;

    }
}