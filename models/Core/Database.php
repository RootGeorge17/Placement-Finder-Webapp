<?php

namespace models\Core;
use PDO;
use PDOException;

class Database
{
    protected static $dbInstance = null;
    protected $dbHandle;

    public static function getInstance()
    {
        $config = require_once('config.php');

        if (self::$dbInstance === null) { //checks if the PDO exists
            // creates new instance if not, sending in connection info
            self::$dbInstance = new self($config['database'], $username = '', $password = '');
        }

        return self::$dbInstance;
    }

    // Constructor to establish a database connection
    private function __construct($config, $username, $password)
    {
        // Construct the DSN (Data Source Name) for the PDO connection
        $dsn = 'mysql:' . http_build_query($config, '', ';');

        try {
            // Create a PDO connection with the provided DSN, username, and password
            $this->dbHandle = new PDO($dsn, $username, $password, [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) { // catch any failure to connect to the database
            echo $e->getMessage();
        }
    }

    public function getDBConnection()
    {
        return $this->dbHandle; // returns the PDO handle to be used elsewhere
    }

    public function __destruct()
    {
        $this->dbHandle = null; // destroys the PDO handle when no longer needed
    }
}