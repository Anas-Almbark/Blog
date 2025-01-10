<?php

class dbConnected
{
    private $dsn = 'mysql:host=localhost;dbname=project_uv_db1;charset=utf8';
    private $user = "root";
    private $pass = "";
    private $conn;

    public function __construct()
    {
        try {
            $this->conn = new PDO($this->dsn, $this->user, $this->pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
    public function getConnection()
    {
        return $this->conn;
    }
}
