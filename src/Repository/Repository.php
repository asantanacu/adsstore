<?php 

namespace App\Repository;

use PDO;

class Repository
{
    private $database;
    
    public function __construct($database)
    {
        $this->database = $database;
    }
    public function getConnection()
    {
        return $this->database->getConnection();
    }
}