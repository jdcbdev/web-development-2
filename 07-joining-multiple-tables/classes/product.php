<?php

require_once "database.php"; // include database connection

class Product {
    // Properties (product fields)
    public $id = "";
    public $name = "";
    public $category = "";
    public $price = "";

    // Database connection
    protected $db;

    // Constructor: initialize the Database class
    public function __construct() {
        $this->db = new Database();
    }

    // Add a new product to the database
    public function addProduct() {
        // SQL with placeholders (to prevent SQL injection)
        $sql = "INSERT INTO product (name, category_id, price) VALUES (:name, :category, :price)";
        
        // Prepare the statement
        $query = $this->db->connect()->prepare($sql);

        // Bind parameters to class properties
        $query->bindParam(":name", $this->name);
        $query->bindParam(":category", $this->category);
        $query->bindParam(":price", $this->price);

        // Execute the statement
        return $query->execute();
    }

    // View all products (returns an array of rows)
    public function viewProducts() {
        $sql = "SELECT p.id as product_id, p.name as product_name, c.name as category_name, price FROM product p INNER JOIN category c ON p.category_id = c.id ORDER BY p.name ASC;";
        $query = $this->db->connect()->prepare($sql);
        
        if ($query->execute()) {
            return $query->fetchAll(); // fetch as associative array
        } else {
            return null;
        }
    }

    public function isProductExist($pname){
        $sql = "SELECT COUNT(*) as total FROM product WHERE name = :name";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(":name", $pname);

        if($query->execute()){
            $result = $query->fetch();
            if($result["total"] > 0){
                return true;
            }else{
                return false;
            }
        }else{
            return -1;
        }
    }
}

// Example usage:

// $obj = new Product();
// $obj->name = "Laptop";
// $obj->category = "Gadget";
// $obj->price = 12000;

// Insert product
// var_dump($obj->addProduct());

// View products
// var_dump($obj->viewProducts());

//var_dump($obj->isProductExist('laptopx'));