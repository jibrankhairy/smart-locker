<?php
require_once("db_conn.php");

class user extends Database{
    private $table = 'user';

    public function read(){
        $sql = "SELECT * FROM $this->table";
        $st = $this->conn->prepare($sql);
        $st->execute();
        
        if ($st->rowCount() > 0) {
            $users = $st->fetchAll();
            return $users;
        }else return 0;
    }
}