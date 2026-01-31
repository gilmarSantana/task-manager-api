<?php

namespace App\Config;

use Dotenv\Dotenv;
use Exception;
use PDO;
use PDOException;

class Database
{
    private string $dbHost;
    private string $dbName;
    private string $dbUsername;
    private string $dbPassword;
    private int $dbPort;
    private ?PDO $pdo = null;


    public function __construct()
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();

        $this->dbHost = $_ENV['DB_HOST'];
        $this->dbName = $_ENV['DB_NAME'];
        $this->dbUsername = $_ENV['DB_USER'];
        $this->dbPassword = $_ENV['DB_PASS'];
        $this->dbPort = $_ENV['DB_PORT'];
    }


    public function connect()
    {

        if ($this->pdo !== null) {
            return $this->pdo;
        }

        try {
            $this->pdo = new PDO("pgsql:host=$this->dbHost;port=$this->dbPort;dbname=$this->dbName", $this->dbUsername, $this->dbPassword);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // Adiciona o modo de fetch associativo como padrão
        } catch (PDOException $e) {
            throw new \Exception("Error connection: " . $e->getMessage());
        }
        return $this->pdo;
    }
}
