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
    private static ?Database $instance = null;


    private function __construct()
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();

        $this->dbHost = $_ENV['DB_HOST'];
        $this->dbName = $_ENV['DB_NAME'];
        $this->dbUsername = $_ENV['DB_USER'];
        $this->dbPassword = $_ENV['DB_PASS'];
        $this->dbPort = $_ENV['DB_PORT'];
    }

    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }


    public function connect(): PDO
    {
        try {
            if ($this->pdo === null) {
                $this->pdo = new PDO("pgsql:host=$this->dbHost;port=$this->dbPort;dbname=$this->dbName", $this->dbUsername, $this->dbPassword);
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // Adiciona o modo de fetch associativo como padrão
            }
            return $this->pdo;
        } catch (PDOException $e) {
            throw new \Exception("Database Error connection: " . $e->getMessage());
        }
    }
}
