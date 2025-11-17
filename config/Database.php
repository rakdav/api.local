<?php
class Database
{
    private $host = "192.168.110.160";
    private $username = "teacher";
    private $password = "Aa_000000";
    private $database = "db_teacher";
    private $conn;
    public function getConnection(){
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=$this->host;
            dbname=$this->database", $this->username, $this->password);
            $this->conn->exec("set names utf8");
        }
        catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        return $this->conn;
    }
}