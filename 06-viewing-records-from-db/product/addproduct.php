<?php

require_once "../classes/product.php";

$product = [];
$errors = [];

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $product["name"] = trim(htmlspecialchars($_POST["name"]));
    $product["category"] = trim(htmlspecialchars($_POST["category"]));
    $product["price"] = trim(htmlspecialchars($_POST["price"]));

    if(empty($product["name"])){
        $errors["name"] = "Product name is required";
    }

    if(empty($product["category"])){
        $errors["category"] = "Please select a category";
    }

    if(empty($product["price"]) && $product["price"] != 0){
        $errors["price"] = "Price is required";
    }elseif(!is_numeric($product["price"]) || $product["price"] < 1){
        $errors["price"] = "Price must be a number greater than 0";
    }

    if(empty(array_filter($errors))){
        $productObj = new Product();
        $productObj->name = $product["name"];
        $productObj->category = $product["category"];
        $productObj->price = $product["price"];

        if($productObj->addProduct()){
            header("Location: viewproduct.php");
        }else{
            echo "error";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <style>
        label{ display: block; }
        span{ color: red; }
        p.error{ color: red; margin: 0; }
    </style>
</head>
<body>
    <h1>Add Product</h1>
    <label>Fields with <span>*</span> are required</label>
    <form action="" method="post">
        <label for="name">Product Name <span>*</span></label>
        <input type="text" name="name" id="name" value="<?php if(isset($product["name"])) { echo $product["name"]; } ?>">
        <p class="error"><?php if(isset($errors["name"])) { echo $errors["name"]; } ?></p>
        <label for="category">Category <span>*</span></label>
        <select name="category" id="category">
            <option value="">--Select--</option>
            <option value="Home Appliance" <?= (isset($product["category"]) && $product["category"] == "Home Appliance")? "selected":"" ?>>Home Appliance</option>
            <option value="Gadget" <?= (isset($product["category"]) && $product["category"] == "Gadget")? "selected":"" ?>>Gadget</option>
        </select>
        <p class="error"><?php if(isset($errors["category"])) { echo $errors["category"]; } ?></p>
        <label for="price">Price <span>*</span></label>
        <input type="text" name="price" id="price" value="<?php if(isset($product["price"])) { echo $product["price"]; } ?>">
        <p class="error"><?php if(isset($errors["price"])) { echo $errors["price"]; } ?></p>
        <br><br>
        <input type="submit" value="Save Product">
    </form>
</body>
</html>