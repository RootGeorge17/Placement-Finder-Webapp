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

    /* For hashed passwords, when we will hash them
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
    */

    public function credentialsMatch($email, $password): bool
    {
        $sqlQuery = 'SELECT password FROM user WHERE email = :email';

        $statement = $this->dbHandle->prepare($sqlQuery);
        $statement->execute([
            ':email' => $email,
        ]);

        $user = $statement->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $storedPassword = $user['password'];

            // Compare plain-text passwords directly
            if ($password === $storedPassword) {
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

    public function fetchUserById($id): ?User
    {
        $sqlQuery = 'SELECT * FROM user WHERE id = :id';

        $statement = $this->dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(['id' => $id]); // execute the PDO statement

        if($row = $statement->fetch()) {
            return new User($row);
        } else {
            return null;
        }
    }

    public function fetchUserByStudentId($id){
        $sqlQuery = 'SELECT * FROM user WHERE studentData = :id';

        $statement = $this->dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(['id' => $id]); // execute the PDO statement

        if($row = $statement->fetch()) {
            return new User($row);
        } else {
            return null;
        }
    }

    public function fetchStudentDataById($id)
    {
        $sqlQuery = 'SELECT studentData.* 
                     FROM studentData 
                     INNER JOIN user ON studentData.id = user.id
                     WHERE user.id = :id';

        $statement = $this->dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(['id' => $id]); // execute the PDO statement

        $row = $statement->fetch();
        return new StudentData($row);
    }
}