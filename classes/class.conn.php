<?php

class conn
{
    public $conn;
    public function __construct()
    {
        $servername = "localhost";
        $username   = "root";
        $password   = "";
        try {
            $conn = new PDO("mysql:host=$servername;dbname=Products", $username, $password);
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //echo "Connected successfully";
            $this->conn = $conn;
        }
        catch (PDOException $e) {
            // echo "Connection failed: " . $e->getMessage();
        }
        
    }
    // public function __destruct()
    // {
    //     $this->conn = null;
    // }
}

?>