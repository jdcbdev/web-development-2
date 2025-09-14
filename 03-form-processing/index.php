<?php
// Step 1: Prepare variables
$product_name = "";
$category = "";
$price = "";
$stock_quantity = "";
$expiration_date = "";
$status = "";

// Error messages
$product_name_error = "";
$category_error = "";
$price_error = "";
$stock_error = "";
$date_error = "";
$status_error = "";

// Step 2: Run validation only if form was submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Sanitize inputs (remove harmful code)
    $product_name = trim(htmlspecialchars($_POST["product_name"]));
    $category = trim(htmlspecialchars($_POST["category"]));
    $price = trim(htmlspecialchars($_POST["price"]));
    $stock_quantity = trim(htmlspecialchars($_POST["stock_quantity"]));
    $expiration_date = trim(htmlspecialchars($_POST["expiration_date"]));
    $status = (isset($_POST["status"]))? trim(htmlspecialchars($_POST["status"])) : "";

    // ✅ VALIDATION RULES
    if(empty($product_name)){
        $product_name_error = "Product name is required";
    }
    if(empty($category)){
        $category_error = "Category is required";
    }
    if(empty($price) || !is_numeric($price) || $price <= 0){
        $price_error = "Enter a valid price greater than 0";
    }
    if(empty($stock_quantity) || !is_numeric($stock_quantity) || $stock_quantity < 0){
        $stock_error = "Enter a valid stock number (0 or higher)";
    }
    if(empty($expiration_date) || strtotime($expiration_date) < strtotime(date("Y-m-d"))){
        $date_error = "Expiration date is required and must not be in the past";
    }
    if(empty($status)){
        $status_error = "Status is required";
    }

    // ✅ If no errors → Redirect
    if(empty($product_name_error) && empty($category_error) && empty($price_error) 
        && empty($stock_error) && empty($date_error) && empty($status_error)){
        header("Location: redirect.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Form</title>
    <style>
        .error { color: red; font-size: 0.9em; margin: 0; }
        label { display: block; }
    </style>
</head>
<body>

<h2>Product Entry Form</h2>

<form action="" method="post">
    <!-- Product Name -->
    <label>Product Name:</label>
    <input type="text" name="product_name" value="<?php echo $product_name; ?>">
    <p class="error"><?php echo $product_name_error; ?></p>

    <!-- Category -->
    <label>Category:</label>
    <select name="category">
        <option value="">-- Select Category --</option>
        <option value="Category A" <?php if($category=="Category A") echo "selected"; ?>>Category A</option>
        <option value="Category B" <?php if($category=="Category B") echo "selected"; ?>>Category B</option>
        <option value="Category C" <?php if($category=="Category C") echo "selected"; ?>>Category C</option>
    </select>
    <p class="error"><?php echo $category_error; ?></p>

    <!-- Price -->
    <label>Price (₱):</label>
    <input type="number" step="0.01" name="price" value="<?php echo $price; ?>">
    <p class="error"><?php echo $price_error; ?></p>

    <!-- Stock Quantity -->
    <label>Stock Quantity:</label>
    <input type="number" name="stock_quantity" value="<?php echo $stock_quantity; ?>">
    <p class="error"><?php echo $stock_error; ?></p>

    <!-- Expiration Date -->
    <label>Expiration Date:</label>
    <input type="date" name="expiration_date" value="<?php echo $expiration_date; ?>">
    <p class="error"><?php echo $date_error; ?></p>

    <!-- Status -->
    <label>Status:</label>
    <input type="radio" name="status" value="active" <?php if($status=="active") echo "checked"; ?>> Active
    <input type="radio" name="status" value="inactive" <?php if($status=="inactive") echo "checked"; ?>> Inactive
    <p class="error"><?php echo $status_error; ?></p>
    <br>
    <!-- Submit Button -->
    <input type="submit" value="Save Product">
</form>

</body>
</html>
