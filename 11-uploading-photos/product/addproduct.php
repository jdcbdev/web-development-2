<?php

require_once "../classes/product.php";
$productObj = new Product();

$product = [];
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product["name"] = trim(htmlspecialchars($_POST["name"]));
    $product["category"] = trim(htmlspecialchars($_POST["category"]));
    $product["price"] = trim(htmlspecialchars($_POST["price"]));

    // === VALIDATION ===
    if (empty($product["name"])) {
        $errors["name"] = "Product name is required";
    } elseif ($productObj->isProductExist($product["name"])) {
        $errors["name"] = "Product name already exists";
    }

    if (empty($product["category"])) {
        $errors["category"] = "Please select a category";
    }

    if ($product["price"] === "" || !is_numeric($product["price"]) || $product["price"] < 1) {
        $errors["price"] = "Price must be a valid number greater than 0";
    }

    // === FILE UPLOAD HANDLING ===
    $photoPath = "";
    if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] === UPLOAD_ERR_OK) {
        $uploadDir = "../uploads/";

        // Ensure directory exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Generate a random filename
        $fileExtension = pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION);
        $randomFileName = uniqid("product_", true) . "." . strtolower($fileExtension);

        // Target path
        $targetFile = $uploadDir . $randomFileName;

        // Move uploaded file
        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFile)) {
            // Store relative path (for DB)
            $photoPath = "uploads/" . $randomFileName;
        } else {
            $errors["photo"] = "Failed to upload photo.";
        }
    } else {
        $errors["photo"] = "Product photo is required.";
    }

    // === INSERT INTO DATABASE ===
    if (empty(array_filter($errors))) {
        $productObj->name = $product["name"];
        $productObj->category = $product["category"];
        $productObj->price = $product["price"];
        $productObj->pathPhoto = $photoPath;

        if ($productObj->addProduct()) {
            header("Location: viewproduct.php");
            exit;
        } else {
            echo "Error: Failed to add product.";
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
    <form action="" method="post" enctype="multipart/form-data">
        <!-- Product Name -->
        <label for="name">Product Name <span>*</span></label>
        <input type="text" name="name" id="name" value="<?= htmlspecialchars($product["name"] ?? "") ?>">
        <p class="error"><?= $errors["name"] ?? "" ?></p>

        <!-- Category -->
        <label for="category">Category <span>*</span></label>
        <select name="category" id="category">
            <option value="">--Select--</option>
            <?php
            require_once "../classes/category.php";
            $categoryObj = new Category();

            foreach ($categoryObj->getCategories() as $category) {
                $selected = (isset($product["category"]) && $product["category"] == $category["id"]) ? "selected" : "";
                echo "<option value='{$category["id"]}' $selected>{$category["name"]}</option>";
            }
            ?>
        </select>
        <p class="error"><?= $errors["category"] ?? "" ?></p>

        <!-- Price -->
        <label for="price">Price <span>*</span></label>
        <input type="text" name="price" id="price" value="<?= htmlspecialchars($product["price"] ?? "") ?>">
        <p class="error"><?= $errors["price"] ?? "" ?></p>

        <!-- Photo Upload -->
        <label for="photo">Product Photo <span>*</span></label>
        <input type="file" name="photo" id="photo" accept="image/*">
        <p class="error"><?= $errors["photo"] ?? "" ?></p>

        <!-- Image Preview -->
        <div id="preview-container" style="margin-top:10px;">
            <img id="photo-preview" src="<?= isset($product["pathPhoto"]) ? htmlspecialchars($product["pathPhoto"]) : "" ?>" 
                alt="Image Preview" 
                style="display:<?= isset($product["pathPhoto"]) ? 'block' : 'none' ?>;max-width:150px;border:1px solid #ccc;border-radius:8px;">
        </div>

        <br>
        <input type="submit" value="Save Product">
    </form>

<!-- JS for Live Preview -->
<script>
document.getElementById("photo").addEventListener("change", function (event) {
    const file = event.target.files[0];
    const preview = document.getElementById("photo-preview");
    const previewContainer = document.getElementById("preview-container");

    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            preview.src = e.target.result;
            preview.style.display = "block";
        };
        reader.readAsDataURL(file);
    } else {
        preview.src = "";
        preview.style.display = "none";
    }
});
</script>

</body>
</html>