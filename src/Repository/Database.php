<?php

namespace App\Repository;

use PDO;
use PDOException;


class Database
{
    private static ?PDO $connection = null;
    private string $host;
    private string $dbName;
    private string $username;
    private string $password;
    private int $port;

    private function __construct()
    {
        $this->host =  $_ENV['DB_HOST'] ?? 'localhost';
        $this->dbName =  $_ENV['DB_NAME'] ?? 'notation_universitaire';
        $this->username =  $_ENV['DB_USER'] ?? 'pape';
        $this->password =  $_ENV['DB_PASS'] ?? '1234';
        $this->port =  $_ENV['DB_PORT'] ?? 5432;
    }


    public static function getInstance(): PDO
    {
        if (self::$connection === null) {
            self::$connection = (new self())->connect();
        }
        return self::$connection;
    }

    private function connect(): PDO
    {
        try {
            $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->dbName}";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ];
            return new PDO($dsn, $this->username, $this->password, $options);
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }
  
    public static function closeConnection(): void
    {
        self::$connection = null;
    }

       public function getPdo(): PDO
    {
        return $this->pdo;
    }
}

