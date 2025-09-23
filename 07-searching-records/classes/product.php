<?php

require_once "database.php"; // include database connection

class Product extends Database{
    // Properties (product fields)
    public $id = "";
    public $name = "";
    public $category = "";
    public $price = "";

    // Add a new product to the database
    public function addProduct() {
        // SQL with placeholders (to prevent SQL injection)
        $sql = "INSERT INTO product (name, category, price) VALUES (:name, :category, :price)";
        
        // Prepare the statement
        $query = $this->connect()->prepare($sql);

        // Bind parameters to class properties
        $query->bindParam(":name", $this->name);
        $query->bindParam(":category", $this->category);
        $query->bindParam(":price", $this->price);

        // Execute the statement
        return $query->execute();
    }

    // View all products (returns an array of rows)
    public function viewProducts($search="", $category="") {
        $sql = "SELECT * FROM product WHERE name LIKE CONCAT('%', :search, '%') AND category LIKE CONCAT('%', :category, '%') ORDER BY name ASC";
        $query = $this->connect()->prepare($sql);
        $query->bindParam(":search", $search);
         $query->bindParam(":category", $category);
        
        if ($query->execute()) {
            return $query->fetchAll(); // fetch as associative array
        } else {
            return null;
        }
    }

    public function isProductExist($pname){
        $sql = "SELECT COUNT(*) as total FROM product WHERE name = :name";
        $query = $this->connect()->prepare($sql);
        $query->bindParam(":name", $pname);
        $record = null;
        if ($query->execute()) {
            $record = $query->fetch();
        }

        if($record["total"] > 0){
            return true;
        }else{
            return false;
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
