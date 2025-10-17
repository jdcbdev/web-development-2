<?php

require_once "database.php";

class Product extends Database{
    public $id = "";
    public $name = "";
    public $category = "";
    public $price = "";

    public function addProduct() {
        $sql = "INSERT INTO product (name, category, price) VALUES (:name, :category, :price)";
        
        $query = $this->connect()->prepare($sql);

        $query->bindParam(":name", $this->name);
        $query->bindParam(":category", $this->category);
        $query->bindParam(":price", $this->price);

        return $query->execute();
    }

    //use inner join to combine the results of the 2 table
    public function viewProducts($search="", $category="") {
        $sql = "SELECT p.id as pid, p.name as pname, c.name as cname, price FROM product p INNER JOIN category c ON p.category=c.id WHERE p.name LIKE CONCAT('%', :search, '%') AND c.name LIKE CONCAT('%', :category, '%') ORDER BY p.name ASC";
        $query = $this->connect()->prepare($sql);
        $query->bindParam(":search", $search);
        $query->bindParam(":category", $category);
        
        if ($query->execute()) {
            return $query->fetchAll();
        } else {
            return null;
        }
    }

    public function isProductExist($pname, $pid=""){
        $sql = "SELECT COUNT(*) as total FROM product WHERE name = :name and id <> :id";
        $query = $this->connect()->prepare($sql);
        $query->bindParam(":name", $pname);
        $query->bindParam(":id", $pid);

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

    public function fetchProduct($pid) {
        $sql = "SELECT * FROM product WHERE id = :id";
        $query = $this->connect()->prepare($sql);
        $query->bindParam(":id", $pid);
        
        if ($query->execute()) {
            return $query->fetch();
        } else {
            return null;
        }
    }

    public function editProduct($pid) {
        $sql = "UPDATE product SET name=:name, category=:category, price=:price WHERE id = :id";
        
        $query = $this->connect()->prepare($sql);

        $query->bindParam(":name", $this->name);
        $query->bindParam(":category", $this->category);
        $query->bindParam(":price", $this->price);
        $query->bindParam(":id", $pid);

        return $query->execute();
    }

    public function deleteProduct($pid) {
        $sql = "DELETE FROM product WHERE id = :id";
        
        $query = $this->connect()->prepare($sql);
        $query->bindParam(":id", $pid);

        return $query->execute();
    }
}

// $obj = new Product();
// $obj->name = "Laptop";
// $obj->category = "Gadget";
// $obj->price = 12000;

// Insert product
// var_dump($obj->addProduct());

// View products
// var_dump($obj->viewProducts());
