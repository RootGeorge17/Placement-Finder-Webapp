<?php
require_once(base_path('models\Core\Database.php'));
require_once(base_path('models\DataSets\User.php'));

class UsersDataSet
{
    protected $dbHandle, $dbInstance;


    public function __construct()
    {
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

    public function emailMatch($email): bool
    {
        $sqlQuery = 'SELECT email from user where email = :email';

        $statement = $this->dbHandle->prepare($sqlQuery);
        $statement->execute([
            ':email' => $email,
        ]);

        $user = $statement->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            return true; // Email match
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

        if ($row = $statement->fetch()) {
            return new User($row);
        } else {
            return null;
        }
    }

    public function fetchUserByStudentId($id)
    {
        $sqlQuery = 'SELECT * FROM user WHERE studentData = :id';

        $statement = $this->dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(['id' => $id]); // execute the PDO statement

        if ($row = $statement->fetch()) {
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

    public function createCompanyUser($firstName, $lastName, $email, $password, $contactNumber, $companyName, $companyDescription, $companyIndustry)
    {
        // Find the industry ID based on the provided industry name
        $sqlFindIndustry = 'SELECT id FROM industry WHERE industry = :companyIndustry';
        $statementFindIndustry = $this->dbHandle->prepare($sqlFindIndustry);
        $statementFindIndustry->execute([':companyIndustry' => $companyIndustry]);
        $industryRow = $statementFindIndustry->fetch(PDO::FETCH_ASSOC);

        if ($industryRow) {
            $industryId = $industryRow['id'];

            // Insert the company with details and industry ID
            $sqlCompany = 'INSERT INTO company (companyName, companyDescription, companyIndustry) 
                    VALUES (:companyName, :companyDescription, :industryId)';
            $statementCompany = $this->dbHandle->prepare($sqlCompany);
            $statementCompany->execute([
                ':companyName' => $companyName,
                ':companyDescription' => $companyDescription,
                ':industryId' => $industryId
            ]);

            $lastCompanyId = $this->dbHandle->lastInsertId();

            // Insert the user associated with the created company
            $sqlUser = 'INSERT INTO user (email, password, userType, companyData, phoneNumber, firstName, lastName) 
                VALUES (:email, :password, :userType, :companyData, :phoneNumber, :firstName, :lastName)';
            $statementUser = $this->dbHandle->prepare($sqlUser);
            $statementUser->execute([
                ':email' => $email,
                ':password' => password_hash($password, PASSWORD_BCRYPT),
                ':userType' => 2,
                ':companyData' => $lastCompanyId,
                ':phoneNumber' => $contactNumber,
                ':firstName' => $firstName,
                ':lastName' => $lastName
            ]);
        }
    }

    public function createStudentUser($firstName, $lastName, $email, $password, $contactNumber, $location, $course, $institution, $industry, $skill1, $skill2, $skill3, $proficiency1, $proficiency2, $proficiency3, $cv)
    {

    }

    public function createCareersUser($firstName, $lastName, $email, $password, $contactNumber)
    {
        $sqlUser = 'INSERT INTO user (email, password, userType, phoneNumber, firstName, lastName) 
                VALUES (:email, :password, :userType, :phoneNumber, :firstName, :lastName)';
        $statementUser = $this->dbHandle->prepare($sqlUser);
        $statementUser->execute([
            ':email' => $email,
            ':password' => password_hash($password, PASSWORD_BCRYPT),
            ':userType' => 3,
            ':phoneNumber' => $contactNumber,
            ':firstName' => $firstName,
            ':lastName' => $lastName
        ]);
    }

    public function createLibraryUser($firstName, $lastName, $email, $password, $contactNumber)
    {
        $sqlUser = 'INSERT INTO user (email, password, userType, phoneNumber, firstName, lastName) 
                VALUES (:email, :password, :userType, :phoneNumber, :firstName, :lastName)';
        $statementUser = $this->dbHandle->prepare($sqlUser);
        $statementUser->execute([
            ':email' => $email,
            ':password' => password_hash($password, PASSWORD_BCRYPT),
            ':userType' => 4,
            ':phoneNumber' => $contactNumber,
            ':firstName' => $firstName,
            ':lastName' => $lastName
        ]);
    }
}