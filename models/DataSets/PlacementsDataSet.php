<?php

require_once(base_path('models/Core/Database.php'));
require_once(base_path('models/DataSets/PlacementData.php'));


class PlacementsDataSet
{
    protected $dbHandle, $dbInstance;


    public function __construct() {
        $this->dbInstance = Database::getInstance();
        $this->dbHandle = $this->dbInstance->getdbConnection();
    }

    /**
     * Fetch all placements
     * @return array PlacementData
     */
    public function fetchAllPlacements(): array
    {
        $sqlQuery = 'SELECT * FROM placementData';

        $statement = $this->dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new PlacementData($row);
        }
        return $dataSet;
    }

    /**
     * Fetch placements by search field and limit
     * @param string $searchField
     * @param int $start
     * @param int $limit
     * @return array PlacementData
     */
    public function fetchBySearchLimit(string $searchField, int $start, int $limit): array
    {

        $sqlQuery = 'SELECT pd.*, cmp.companyName AS companyName,
                     CASE
                        WHEN cmp.companyName LIKE :searchField THEN 2
                        WHEN cmp.companyDescription LIKE :searchField THEN 1
                        ELSE 0
                     END AS matchLength
                     FROM placementData pd
                     INNER JOIN company cmp ON pd.companyId = cmp.id
                     WHERE (cmp.companyName LIKE :searchField
                            OR cmp.companyDescription LIKE :searchField)
                     ORDER BY matchLength DESC
                     LIMIT :start, :limit';

        $statement = $this->dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindParam('%'.':searchField'.'%', $searchField, PDO::PARAM_STR);
        $statement->bindParam(':start', $start, PDO::PARAM_INT);
        $statement->bindParam(':limit', $limit, PDO::PARAM_INT);
        $statement->execute(); // execute the PDO statement

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new PlacementData($row);
        }
        return $dataSet;
    }

    /**
     * Fetch placements by limit
     * @param int $start
     * @param int $limit
     * @return array PlacementData
     */
    public function fetchByLimit(int $start, int $limit): array
    {
        $sqlQuery = 'SELECT * FROM placementData LIMIT :start, :limit';

        $statement = $this->dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindParam(':start', $start, PDO::PARAM_INT);
        $statement->bindParam(':limit', $limit, PDO::PARAM_INT);
        $statement->execute(); // execute the PDO statement

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new PlacementData($row);
        }
        return $dataSet;
    }

    /**
     * fetch the row count of the database
     * @return int
     */
    public function fetchRowCountAll(): int
    {
        $sqlQuery = 'SELECT COUNT(*) FROM placementData';

        $statement = $this->dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement

        return $statement->fetchColumn();
    }

    /**
     * Fetch all placements by company id
     * @param int $id
     * @return array PlacementData
     */
    public function fetchPlacementsByCompanyId(int $id): array
    {
        $sqlQuery = 'SELECT * FROM placementData WHERE companyId = :id';

        $statement = $this->dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(['id' => $id]); // execute the PDO statement

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new PlacementData($row);
        }
        return $dataSet;
    }

    public function fetchPlacementById(int $id): ?PlacementData
    {
        $sqlQuery = 'SELECT * FROM placementData WHERE id = :id';

        $statement = $this->dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(['id' => $id]); // execute the PDO statement

        if ($row = $statement->fetch()) {
            return new PlacementData($row);
        } else {
            return null;
        }
    }

    public function addPlacement($companyId, $description, $industry, $salary, $location,
                                 $startDate, $endDate, $skill1, $skill2, $skill3): false|string
    {
        // Check if a similar placement already exists
        $existingPlacementQuery = 'SELECT id FROM placementData 
                               WHERE companyId = :companyId 
                               AND description = :description 
                               AND industry = :industry 
                               AND startDate = :startDate 
                               AND endDate = :endDate';

        $existingStatement = $this->dbHandle->prepare($existingPlacementQuery);
        $existingStatement->execute([
            ':companyId' => $companyId,
            ':description' => $description,
            ':industry' => $industry,
            ':startDate' => $startDate,
            ':endDate' => $endDate
        ]);

        $existingPlacement = $existingStatement->fetch(PDO::FETCH_ASSOC);

        if ($existingPlacement) {
            // Placement already exists, return false
            return false;
        } else {
            // Placement doesn't exist, proceed with the insertion
            $sqlQuery = 'INSERT INTO placementData (
                           companyId, description, industry, 
                           salary, location, startDate, endDate, skill1, skill2, skill3) 
                         VALUES (:companyId, :description, :industry, :salary, :location,
                             :startDate, :endDate, :skill1, :skill2, :skill3)';

            $statement = $this->dbHandle->prepare($sqlQuery); // prepare a PDO statement
            $statement->execute([
                ':companyId' => $companyId,
                ':description' => $description,
                ':industry' => $industry,
                ':salary' => $salary,
                ':location' => $location,
                ':startDate' => $startDate,
                ':endDate' => $endDate,
                ':skill1' => $skill1,
                ':skill2' => $skill2,
                ':skill3' => $skill3
            ]); // execute the PDO statement

            return $this->dbHandle->lastInsertId();
        }
    }

    public function fetchAllByLimitAndSort(int $start, int $limit, string $sort): array
    {
        $sqlQuery = 'SELECT pd.*, cmp.companyName AS companyName
                     FROM placementData pd
                     INNER JOIN company cmp ON pd.companyId = cmp.id';

        switch ($sort) {
            case 'nameasc':
                $sqlQuery .= ' ORDER BY companyName ASC';
                break;
            case 'namedesc':
                $sqlQuery .= ' ORDER BY companyName DESC';
                break;
            case 'salaryasc':
                $sqlQuery .= ' ORDER BY pd.salary ASC';
                break;
            case 'salarydesc':
                $sqlQuery .= ' ORDER BY pd.salary DESC';
                break;
            case 'locationasc':
                $sqlQuery .= ' ORDER BY pd.location ASC';
                break;
            case 'locationdesc':
                $sqlQuery .= ' ORDER BY pd.location DESC';
                break;
            default:
                $sqlQuery .= ' ORDER BY pd.id ASC';
                break;
        }

        $sqlQuery .= ' LIMIT :start, :limit';

        $statement = $this->dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindParam(':start', $start, PDO::PARAM_INT);
        $statement->bindParam(':limit', $limit, PDO::PARAM_INT);
        $statement->execute(); // execute the PDO statement

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new PlacementData($row);
        }
        return $dataSet;
    }

    public function fetchAllByLimitAndSortAndFilter($start, $limit, $sort, $location, $industry)
    {
        $sqlQuery = 'SELECT pd.*, cmp.companyName AS companyName
                 FROM placementData pd
                 INNER JOIN company cmp ON pd.companyId = cmp.id';

        if ($location !== 'all' || $industry !== 'all') {
            $sqlQuery .= ' WHERE';

            if ($location !== 'all') {
                $sqlQuery .= ' pd.location = :location';
            }

            if ($location !== 'all' && $industry !== 'all') {
                $sqlQuery .= ' AND';
            }

            if ($industry !== 'all') {
                $sqlQuery .= ' pd.industry = :industry';
            }
        }

        switch ($sort) {
            case 'nameasc':
                $sqlQuery .= ' ORDER BY cmp.companyName ASC';
                break;
            case 'namedesc':
                $sqlQuery .= ' ORDER BY cmp.companyName DESC';
                break;
            case 'salaryasc':
                $sqlQuery .= ' ORDER BY pd.salary ASC';
                break;
            case 'salarydesc':
                $sqlQuery .= ' ORDER BY pd.salary DESC';
                break;
            case 'locationasc':
                $sqlQuery .= ' ORDER BY pd.location ASC';
                break;
            case 'locationdesc':
                $sqlQuery .= ' ORDER BY pd.location DESC';
                break;
            default:
                // No default ordering
                break;
        }

        $sqlQuery .= ' LIMIT :start, :limit';

        $statement = $this->dbHandle->prepare($sqlQuery); // prepare a PDO statement

        if ($location !== 'all') {
            $statement->bindParam(':location', $location, PDO::PARAM_STR);
        }

        if ($industry !== 'all') {
            $statement->bindParam(':industry', $industry, PDO::PARAM_STR);
        }

        $statement->bindParam(':start', $start, PDO::PARAM_INT);
        $statement->bindParam(':limit', $limit, PDO::PARAM_INT);
        $statement->execute(); // execute the PDO statement

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new PlacementData($row);
        }
        return $dataSet;
    }



    public function deletePlacement(int $companyId, int $placementId): bool
    {
        $sqlQuery = 'DELETE FROM placementData WHERE id = :placementId AND companyId = :companyId';

        $statement = $this->dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $success = $statement->execute(['placementId' => $placementId, ':companyId' => $companyId]); // execute the PDO statement

        return $success;
    }
}