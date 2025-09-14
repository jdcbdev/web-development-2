<?php

require_once "../classes/product.php";

$productObj = new Product();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Products</title>
</head>
<body>
    <h1>Product List</h1>
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
        foreach($productObj->viewProducts() as $product){
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