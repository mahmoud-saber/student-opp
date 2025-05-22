<?php

class Database
{
    private $connection;

    public function __construct()
    {
        $dsn = "mysql:host=localhost;dbname=opp;charset=utf8mb4";
        $username = "root";
        $password = "";

        try {
            $this->connection = new PDO($dsn, $username, $password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die('Connection Failed: ' . $e->getMessage());
        }
    }

    public function getConnection()
    {
        return $this->connection;
    }
}