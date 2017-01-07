<?php

namespace App\Services;

use PDO;

class Database{
	private $connection = null;
	private $host = "localhost";
	private $dbname = "";
	private $user = "";
	private $password = "";
    private $port = "";
    
    public function __construct($config){
        if(!isset($config['database'])){
            throw new \InvalidArgumentException(
                'Databse class requires database configuration'
            );
        }
        
        $this->host = $config['database']['host'];
		$this->database = $config['database']['database'];
		$this->username = $config['database']['username'];
		$this->password = $config['database']['password'];
        $this->port = $config['database']['port'];
    }
	
    public function getConnection(){	
		if ($this->connection === null) {
            $this->connection = new PDO(
                    'mysql:host='.$this->host.';port='.$this->port.';dbname='.$this->database, 
                    $this->username, 
                    $this->password
                );
            $this->connection->setAttribute(
                PDO::ATTR_ERRMODE, 
                PDO::ERRMODE_EXCEPTION
            );
        }
		return $this->connection;
    }
	
}