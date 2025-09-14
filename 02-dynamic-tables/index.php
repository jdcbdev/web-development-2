<?php
    // 2D array of products
    $products = array(
        array("name"=>"Product A", "price"=>10.50, "stock"=>12),
        array("name"=>"Product B", "price"=>5.60, "stock"=>7),
        array("name"=>"Product C", "price"=>7.00, "stock"=>5),
        array("name"=>"Product D", "price"=>12.00, "stock"=>20)
    );
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dynamic Table</title>
    <style>
        /* Basic table styling */
        .container{ width: 100%; max-width: 1200px; margin: 0 auto; }
        table { border-collapse: collapse; width: 100%; margin: 20px auto; }
        th, td { border: 1px solid black; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
        .low-stock { color: red; } /* highlight products with low stock */
    </style>
</head>
<body>
    <div class="container">
        <h2>Product Inventory</h2>
        <table>
            <tr>
                <th>No.</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Stock</th>
            </tr>

            <?php 
                $no = 1; // counter for numbering
                foreach($products as $p){
                    // Add a class if stock is below 10
                    $rowClass = ($p["stock"] < 10) ? "low-stock" : "";
            ?>
                <tr class="<?= $rowClass ?>">
                    <td><?= $no++ ?></td>
                    <td><?= $p["name"] ?></td>
                    <td><?= $p["price"] ?></td>
                    <td><?= $p["stock"] ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>
