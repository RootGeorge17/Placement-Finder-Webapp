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
        $sqlQuery = 'SELECT * FROM skills WHERE id IN ('.implode(',', $ids).')';

        $statement = $this->dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new Skill($row);
        }
        return $dataSet;
    }

    public function fetchSkillByName($name)
    {
        $sqlQuery = 'SELECT * FROM skills WHERE skillName = :name';

        $statement = $this->dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(['name' => $name]); // execute the PDO statement

        $row = $statement->fetch();
        return new Skill($row);
    }

    public function fetchSkillByProficiency($proficiency)
    {
        $sqlQuery = 'SELECT * FROM skills WHERE proficiency = :proficiency';

        $statement = $this->dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(['proficiency' => $proficiency]); // execute the PDO statement

        $row = $statement->fetch();
        return new Skill($row);
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