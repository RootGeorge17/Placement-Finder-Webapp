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
                        WHEN cmp.companyName LIKE :searchField THEN char_length(cmp.companyName)
                        WHEN cmp.companyDescription LIKE :searchField THEN char_length(cmp.companyDescription)
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

}