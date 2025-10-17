<?php

session_start();

// if user attempts to access this page without logging in
if (!isset($_SESSION["user"]) || 
    ($_SESSION["user"]["role"] != "Staff" && $_SESSION["user"]["role"] != "Admin")) {
    header('location: ../account/login.php');
    exit(); // always add exit() after header redirect
}

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
    <h2>Hi <?= $_SESSION["user"]["role"] ?></h2><a href="../account/logout.php">Logout</a>
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
            <th>Action</th>
        </tr>
        <?php
        $no = 1;
        foreach($productObj->viewProducts($search, $category) as $product){
            $message = "Are you sure you want to delete the product " . $product["pname"] . "?";
        ?>
        <tr>
            <td><?= $no++ ?></td>
            <!-- base on the result set return by the viewProducts function, we have to adjust the key names as per the alias set in the query -->
            <td><?= $product["pname"] ?></td>
            <td><?= $product["cname"] ?></td>
            <td><?= number_format($product["price"], 2) ?></td>
            <td>
                <a href="editproduct.php?id=<?= $product["pid"] ?>">Edit</a>
                <?php
                // only admin can delete
                if($_SESSION["user"]["role"] == "Admin"){
                ?>
                <a href="deleteproduct.php?id=<?= $product["pid"] ?>" onclick="return confirm('<?= $message ?>')">Delete</a>
                <?php
                }
                ?>
            </td>
        </tr>
        <?php
        }
        ?>
    </table>
</body>
</html>