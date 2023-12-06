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

}