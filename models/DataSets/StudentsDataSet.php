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

    public function fetchAllStudentData(): array
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
        if (!$row) {
            return null;
        }
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

    public function fetchAllByLimitAndSort($start, $limit, $sort){
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
                $sqlQuery .= ' ORDER BY studentData.id ASC';
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

    public function fetchByLimitAndSortAndFilter(int $start,int $limit,string $sort,
                                              string $location,string $industry,
                                              string $course,string $institution){
        $sqlQuery = 'SELECT studentData.*, user.firstName, user.lastName 
                 FROM studentData 
                 INNER JOIN user ON user.studentData = studentData.id';

        if ($location != 'all' || $industry != 'all' || $course != 'all' || $institution != 'all'){
            $sqlQuery .= ' WHERE';
        }

        if ($location != 'all'){
            $sqlQuery .= ' studentData.location = :location';
        }

        if ($location != 'all' && $industry != 'all'){
            $sqlQuery .= ' AND';
        }

        if ($industry != 'all'){
            $sqlQuery .= ' studentData.prefIndustry = :industry';
        }

        if (($location != 'all' || $industry != 'all') && $course != 'all'){
            $sqlQuery .= ' AND';
        }

        if ($course != 'all'){
            $sqlQuery .= ' studentData.course = :course';
        }

        if (($location != 'all' || $industry != 'all' || $course != 'all') && $institution != 'all'){
            $sqlQuery .= ' AND';
        }

        if ($institution != 'all'){
            $sqlQuery .= ' studentData.institution = :institution';
        }

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
                break;
        }

        $sqlQuery .= ' LIMIT :start, :limit';

        $statement = $this->dbHandle->prepare($sqlQuery);
        $statement->bindParam(':start', $start, PDO::PARAM_INT);
        $statement->bindParam(':limit', $limit, PDO::PARAM_INT);

        if ($location != 'all'){
            $statement->bindParam(':location', $location, PDO::PARAM_STR);
        }

        if ($industry != 'all'){
            $statement->bindParam(':industry', $industry, PDO::PARAM_STR);
        }

        if ($course != 'all'){
            $statement->bindParam(':course', $course, PDO::PARAM_STR);
        }

        if ($institution != 'all'){
            $statement->bindParam(':institution', $institution, PDO::PARAM_STR);
        }

        $statement->execute();

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new StudentData($row);
        }
        return $dataSet;
    }

    public function updateStudentUser(string $email, string $location, string $cv,
                                      int $course, string $institution, int $prefIndustry,
                                      string $skill1Name, int $skill1Proficiency,
                                      string $skill2Name, int $skill2Proficiency,
                                      string $skill3Name, int $skill3Proficiency) : bool
    {
        $studentDataID = null;

        // Fetch user ID based on email
        $userIdQuery = 'SELECT studentData FROM user WHERE email = :email';
        // Execute $userIdQuery using your database connection to get the user ID
        $userIdQueryStatement = $this->dbHandle->prepare($userIdQuery);
        $userIdQueryStatement->execute(['email' => $email]);
        if ($row = $userIdQueryStatement->fetch()) {
            $studentDataID = $row['studentData'];
        } else {
            return false;
        }

        // Fetch skill IDs based on skill names and proficiencies
        $skill1IdQuery = 'SELECT id FROM skills WHERE skillName = :skill1Name AND proficiency = :skill1Proficiency';
        // Execute $skill1IdQuery
        $skill1IdQueryStatement = $this->dbHandle->prepare($skill1IdQuery);
        $skill1IdQueryStatement->execute(['skill1Name' => $skill1Name, 'skill1Proficiency' => $skill1Proficiency]);
        if ($row = $skill1IdQueryStatement->fetch()) {
            $skill1Id = $row['id'];
        } else {
            return false;
        }

        $skill2IdQuery = 'SELECT id FROM skills WHERE skillName = :skill2Name AND proficiency = :skill2Proficiency';
        // Execute $skill2IdQuery
        $skill2IdQueryStatement = $this->dbHandle->prepare($skill2IdQuery);
        $skill2IdQueryStatement->execute(['skill2Name' => $skill2Name, 'skill2Proficiency' => $skill2Proficiency]);
        if ($row = $skill2IdQueryStatement->fetch()) {
            $skill2Id = $row['id'];
        } else {
            return false;
        }

        $skill3IdQuery = 'SELECT id FROM skills WHERE skillName = :skill3Name AND proficiency = :skill3Proficiency';
        // Execute $skill3IdQuery
        $skill3IdQueryStatement = $this->dbHandle->prepare($skill3IdQuery);
        $skill3IdQueryStatement->execute(['skill3Name' => $skill3Name, 'skill3Proficiency' => $skill3Proficiency]);
        if ($row = $skill3IdQueryStatement->fetch()) {
            $skill3Id = $row['id'];
        } else {
            return false;
        }

        // Construct the UPDATE query using the fetched IDs
        $sqlQuery = 'UPDATE studentData
                    SET 
                        skill1 = :skill1Id,
                        skill2 = :skill2Id,
                        skill3 = :skill3Id,
                        location = :location,
                        cv = :cv,
                        course = :course,
                        institution = :institution,
                        prefIndustry = :prefIndustry
                    WHERE id = :studentDataId';

        // Execute the UPDATE query
        $statement = $this->dbHandle->prepare($sqlQuery);
        $statement->execute([
            'skill1Id' => $skill1Id,
            'skill2Id' => $skill2Id,
            'skill3Id' => $skill3Id,
            'location' => $location,
            'cv' => $cv,
            'course' => $course,
            'institution' => $institution,
            'prefIndustry' => $prefIndustry,
            'studentDataId' => $studentDataID
        ]);

        return true;
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