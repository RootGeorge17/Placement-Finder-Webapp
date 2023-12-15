<?php

require_once(base_path('models/Core/Database.php'));
require_once(base_path('models/DataSets/StudentData.php'));

class StudentsDataSet
{
    protected $dbHandle, $dbInstance;


    public function __construct() {
        $this->dbInstance = Database::getInstance();
        $this->dbHandle = $this->dbInstance->getdbConnection();
    }

    public function fetchAllStudentData()
    {
        $sqlQuery = 'SELECT * FROM studentData';

        $statement = $this->dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new StudentData($row);
        }
        return $dataSet;
    }

    public function fetchStudentDataById($id)
    {
        $sqlQuery = 'SELECT * FROM studentData WHERE id = :id';

        $statement = $this->dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(['id' => $id]); // execute the PDO statement

        $row = $statement->fetch();
        return new StudentData($row);
    }

    public function fetchRowCountAll()
    {
        $sqlQuery = 'SELECT COUNT(*) FROM studentData';

        $statement = $this->dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement

        return $statement->fetchColumn();
    }

    public function fetchByLimit(int $start, int $limit) : array
    {
        $sqlQuery = 'SELECT * FROM studentData LIMIT :start, :limit';

        $statement = $this->dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindParam(':start', $start, PDO::PARAM_INT);
        $statement->bindParam(':limit', $limit, PDO::PARAM_INT);
        $statement->execute(); // execute the PDO statement

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new StudentData($row);
        }
        return $dataSet;
    }

    public function fetchAllByLimitAndFilter($start, $limit, $sort){
        $sqlQuery = 'SELECT studentData.*, user.firstName, user.lastName 
                 FROM studentData 
                 INNER JOIN user ON user.studentData = studentData.id';

        switch ($sort) {
            case 'nameasc':
                $sqlQuery .= ' ORDER BY user.firstName ASC, user.lastName ASC';
                break;
            case 'namedesc':
                $sqlQuery .= ' ORDER BY user.firstName DESC, user.lastName DESC';
                break;
            case 'locationasc':
                $sqlQuery .= ' ORDER BY studentData.location ASC';
                break;
            case 'locationdesc':
                $sqlQuery .= ' ORDER BY studentData.location DESC';
                break;
            default:
                // No specific filter, do not add ORDER BY clause
                break;
        }

//        switch ($sort) {
//            case 'course':
//                $sqlQuery .= ' JOIN coursesTable ON studentData.course_id = coursesTable.id';
//                $sqlQuery .= ' ORDER BY coursesTable.courseName ASC';
//                break;
//            case 'institution':
//                $sqlQuery .= ' ORDER BY studentData.institution ASC';
//                break;
//            case 'industry':
//                $sqlQuery .= ' JOIN industriesTable ON studentData.prefIndustry_id = industriesTable.id';
//                $sqlQuery .= ' ORDER BY industriesTable.industryName ASC';
//                break;
//            default:
//                // No specific sorting, do not modify ORDER BY clause
//                break;
//        }

        $sqlQuery .= ' LIMIT :start, :limit';

        $statement = $this->dbHandle->prepare($sqlQuery);
        $statement->bindParam(':start', $start, PDO::PARAM_INT);
        $statement->bindParam(':limit', $limit, PDO::PARAM_INT);
        $statement->execute();

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new StudentData($row);
        }
        return $dataSet;
    }

    public function fetchCV($id)
    {
        $sqlQuery = 'SELECT u.id, u.studentData, sd.cv 
                     FROM user u 
                     LEFT JOIN studentData sd ON u.studentData = sd.id
                     WHERE u.id = :id';

        $statement = $this->dbHandle->prepare($sqlQuery);
        $statement->execute(['id' => $id]);

        $row = $statement->fetch();
        if ($row) {
            return $row;
        }
        return null;
    }


}