<?php

require_once "../classes/product.php";
$productObj = new Product();

$search = $category = "";

if($_SERVER["REQUEST_METHOD"] == "GET"){
    $search = isset($_GET["search"])? trim(htmlspecialchars($_GET["search"])) : "";
    $category = isset($_GET["category"])? trim(htmlspecialchars($_GET["category"])) : "";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Products</title>
</head>
<body>
    <h1>Products</h1>
    <form action="" method="get">
        <label for="">Search:</label>
        <input type="search" name="search" id="search" value="<?= $search ?>">
        <select name="category" id="category">
            <option value="">All</option>
            <option value="Home Appliance" <?= (isset($category) && $category == "Home Appliance")? "selected":"" ?>>Home Appliance</option>
            <option value="Gadget" <?= (isset($category) && $category == "Gadget")? "selected":"" ?>>Gadget</option>
        </select>
        <input type="submit" value="Search">
    </form>
    <button><a href="addproduct.php">Add Product</a></button>
    <table border=1>
        <tr>
            <th>No.</th>
            <th>Product Name</th>
            <th>Category</th>
            <th>Price</th>
        </tr>
        <?php
        $no = 1;
        foreach($productObj->viewProducts($search, $category) as $product){
        ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= $product["name"] ?></td>
            <td><?= $product["category"] ?></td>
            <td><?= number_format($product["price"], 2) ?></td>
        </tr>
        <?php
        }
        ?>
    </table>
</body>
</html>