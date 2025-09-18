<?php

class Database {
    // Database connection properties
    private $host = "localhost";   // Host (local server)
    private $username = "root";    // Default MySQL username
    private $password = "";        // Default MySQL password (blank in XAMPP)
    private $dbname = "ecommerce_db"; // Database name

    // Property to hold the PDO object
    protected $conn;

    // Method to connect to the database
    public function connect() {
        // Create a new PDO instance
        $this->conn = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->username, $this->password);

        return $this->conn; // return the connection
    }
}

// Example usage:
// $obj = new Database();
// var_dump($obj->connect()); // Shows PDO object if successful
