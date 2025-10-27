<?php

require_once "database.php";

class Product extends Database{
    public $name = "";
    public $category = "";
    public $price = "";
    public $pathPhoto = "";

    public function addProduct() {
        // Connect to the database
        $conn = $this->connect();

        // Begin a transaction to ensure both inserts succeed together
        $conn->beginTransaction();

        try {
            /**
             * STEP 1: Insert product details
             * ----------------------------------
             * Inserts the product's name, category, and price
             * into the 'product' table.
             */
            $sqlProduct = "INSERT INTO product (name, category, price) VALUES (:name, :category, :price)";
            $stmtProduct = $conn->prepare($sqlProduct);
            $stmtProduct->bindParam(":name", $this->name);
            $stmtProduct->bindParam(":category", $this->category);
            $stmtProduct->bindParam(":price", $this->price);
            $stmtProduct->execute();

            /**
             * STEP 2: Get the newly inserted product ID
             * ----------------------------------
             * This will be used to link the product to its photo.
             */
            $productId = $conn->lastInsertId();

            /**
             * STEP 3: Insert photo linked to product
             * ----------------------------------
             * Adds the uploaded photo path into the 'photo' table,
             * using the product ID from the previous step.
             */
            $sqlPhoto = "INSERT INTO photo (product_id, path) VALUES (:product_id, :pathPhoto)";
            $stmtPhoto = $conn->prepare($sqlPhoto);
            $stmtPhoto->bindParam(":product_id", $productId);
            $stmtPhoto->bindParam(":pathPhoto", $this->pathPhoto);
            $stmtPhoto->execute();

            /**
             * STEP 4: Commit transaction
             * ----------------------------------
             * If both inserts succeed, commit the changes permanently.
             */
            $conn->commit();

            return true;

        } catch (PDOException $e) {
            /**
             * STEP 5: Rollback on error
             * ----------------------------------
             * If anything fails, revert all changes.
             * Logs the error message for debugging.
             */
            $conn->rollBack();
            error_log("Add Product Error: " . $e->getMessage());
            return false;
        }
    }

    //use inner join to combine the results of the 2 table
    public function viewProducts($search="", $category="") {
        $sql = "SELECT p.id as pid, p.name as pname, c.name as cname, price, path FROM product p INNER JOIN category c ON p.category=c.id LEFT JOIN photo ph ON p.id=ph.product_id WHERE p.name LIKE CONCAT('%', :search, '%') AND c.name LIKE CONCAT('%', :category, '%') ORDER BY p.name ASC";
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
        $sql = "SELECT p.*, ph.path AS pathPhoto 
                FROM product p 
                LEFT JOIN photo ph ON ph.product_id = p.id 
                WHERE p.id = :id";
        
        $query = $this->connect()->prepare($sql);
        $query->bindParam(":id", $pid);

        if ($query->execute()) {
            return $query->fetch(PDO::FETCH_ASSOC);
        } else {
            return null;
        }
    }

    public function editProduct($pid) {
        $conn = $this->connect();
        $conn->beginTransaction();

        try {
            // --- Step 1: Update the product details ---
            $sqlProduct = "UPDATE product SET name = :name, category = :category, price = :price WHERE id = :id";
            $stmtProduct = $conn->prepare($sqlProduct);
            $stmtProduct->bindParam(":name", $this->name);
            $stmtProduct->bindParam(":category", $this->category);
            $stmtProduct->bindParam(":price", $this->price);
            $stmtProduct->bindParam(":id", $pid);
            $stmtProduct->execute();

            // --- Step 2: If there's a new photo uploaded, update or insert it ---
            if (!empty($this->pathPhoto)) {
                // Check if the product already has a photo
                $checkSql = "SELECT id FROM photo WHERE product_id = :pid";
                $stmtCheck = $conn->prepare($checkSql);
                $stmtCheck->bindParam(":pid", $pid);
                $stmtCheck->execute();

                if ($stmtCheck->rowCount() > 0) {
                    // Update existing photo
                    $sqlPhoto = "UPDATE photo SET path = :path WHERE product_id = :pid";
                } else {
                    // Insert new photo
                    $sqlPhoto = "INSERT INTO photo (product_id, path) VALUES (:pid, :path)";
                }

                $stmtPhoto = $conn->prepare($sqlPhoto);
                $stmtPhoto->bindParam(":pid", $pid);
                $stmtPhoto->bindParam(":path", $this->pathPhoto);
                $stmtPhoto->execute();
            }

            // --- Step 3: Commit transaction ---
            $conn->commit();
            return true;

        } catch (PDOException $e) {
            $conn->rollBack();
            error_log("Edit Product Error: " . $e->getMessage());
            return false;
        }
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
