<?php

require_once(base_path('models/Core/Database.php'));
require_once(base_path('models/DataSets/Skill.php'));

class SkillsDataSet
{
    protected $dbHandle, $dbInstance;


    public function __construct() {
        $this->dbInstance = Database::getInstance();
        $this->dbHandle = $this->dbInstance->getdbConnection();
    }

    public function fetchAllSkills()
    {
        $sqlQuery = 'SELECT * FROM skills';

        $statement = $this->dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement


        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new Skill($row);
        }
        return $dataSet;
    }

    public function fetchSkillById($id)
    {
        $sqlQuery = 'SELECT * FROM skills WHERE id = :id';

        $statement = $this->dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(['id' => $id]); // execute the PDO statement

        $row = $statement->fetch();
        return new Skill($row);
    }

    public function fetchSkillsbyIdArray($ids)
    {
        // Create placeholders for the IN clause
        $placeholders = rtrim(str_repeat('?,', count($ids)), ',');

        $sqlQuery = 'SELECT * FROM skills WHERE id IN (' . $placeholders . ')';
        $statement = $this->dbHandle->prepare($sqlQuery);

        // Bind values to placeholders
        foreach ($ids as $key => $value) {
            $statement->bindValue(($key + 1), $value);
        }

        $statement->execute();

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new Skill($row);
        }
        return $dataSet;
    }


    public function fetchSkillsByName($name)
    {
        $sqlQuery = 'SELECT * FROM skills WHERE skillName = :name';

        $statement = $this->dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(['name' => $name]); // execute the PDO statement

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new Skill($row);
        }
        return $dataSet;
    }

    public function fetchSkillByNameAndProficiencyId($name, $proficiencyId)
    {
        $sqlQuery = 'SELECT * FROM skills WHERE skillName = :name AND proficiency = :proficiencyId';

        $statement = $this->dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(['name' => $name, 'proficiencyId' => $proficiencyId]); // execute the PDO statement

        $row = $statement->fetch();
        if ($row) {
            return new Skill($row);
        } else {
            // Handle the case where the row is empty, e.g., return null or throw an exception
            return null; // or throw new Exception('Skill not found');
        }
    }

    public function fetchSkillsByProficiency($proficiency)
    {
        $sqlQuery = 'SELECT * FROM skills WHERE proficiency = :proficiency';

        $statement = $this->dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(['proficiency' => $proficiency]); // execute the PDO statement

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new Skill($row);
        }
        return $dataSet;
    }

   // public function fetchSkill

    public function fetchProficiencyById($id)
    {
        $sqlQuery = 'SELECT proficiency FROM skills WHERE id = :id';

        $statement = $this->dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(['id' => $id]); // execute the PDO statement

        $row = $statement->fetch();
        if ($row) {
            return $row['proficiency'];
        }
        return null;
    }

}