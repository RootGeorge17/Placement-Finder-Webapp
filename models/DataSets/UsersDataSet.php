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

    public function credentialsMatch($email, $password): bool
    {
        $sqlQuery = 'SELECT email, password from user where email = :email';

        $statement = $this->dbHandle->prepare($sqlQuery);
        $statement->execute([
            ':email' => $email,
        ]);

        $user = $statement->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $storedHashedPassword = $user['password'];
            if (password_verify($password, $storedHashedPassword)) {
                return true; // Passwords match
            }
        }
        return false;
    }

    public function getUserDetails($email)
    {
        $sqlQuery = 'SELECT * from user where email = :email';

        $statement = $this->dbHandle->prepare($sqlQuery);
        $statement->execute([
            ':email' => $email,
        ]);

        return $statement->fetch(PDO::FETCH_ASSOC);
    }
}