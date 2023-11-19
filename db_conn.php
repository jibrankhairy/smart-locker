<?php

class Database{
    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $db_name = "smart-locker";

    protected $conn;

    function __construct(){
        try {
            $this->conn = new PDO("mysql:host=$this->servername;dbname=$this->db_name", $this->username, $this->password);
        
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        }catch(PDOException $e){
            $this->conn = null;
            echo "Connection Failed! ". $e->getMessage();
        }
    }
}