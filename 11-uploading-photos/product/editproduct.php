<?php
require_once "../classes/product.php";
$productObj = new Product();

$product = [];
$errors = [];
$pid = $_GET["id"] ?? null;

// === FETCH PRODUCT ON LOAD ===
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if ($pid) {
        $product = $productObj->fetchProduct($pid);
        if (!$product) {
            exit("<a href='viewproduct.php'>View Products</a> | Product not found.");
        }
    } else {
        exit("<a href='viewproduct.php'>View Products</a> | Missing product ID.");
    }
}

// === UPDATE PRODUCT ON SUBMIT ===
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product["id"] = trim(htmlspecialchars($_POST["id"]));
    $product["name"] = trim(htmlspecialchars($_POST["name"]));
    $product["category"] = trim(htmlspecialchars($_POST["category"]));
    $product["price"] = trim(htmlspecialchars($_POST["price"]));
    $product["pathPhoto"] = trim(htmlspecialchars($_POST["existing_photo"] ?? ""));

    // === VALIDATION ===
    if (empty($product["name"])) {
        $errors["name"] = "Product name is required";
    } elseif ($productObj->isProductExist($product["name"], $product["id"])) {
        $errors["name"] = "Product name already exists";
    }

    if (empty($product["category"])) {
        $errors["category"] = "Please select a category";
    }

    if ($product["price"] === "" || !is_numeric($product["price"]) || $product["price"] < 1) {
        $errors["price"] = "Price must be a valid number greater than 0";
    }

    // === FILE UPLOAD HANDLING ===
    $photoPath = $product["pathPhoto"]; // default: existing photo

    if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] === UPLOAD_ERR_OK) {
        $uploadDir = "../uploads/";

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileExtension = pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION);
        $randomFileName = uniqid("product_", true) . "." . strtolower($fileExtension);
        $targetFile = $uploadDir . $randomFileName;

        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFile)) {
            $photoPath = "uploads/" . $randomFileName;
        } else {
            $errors["photo"] = "Failed to upload new photo.";
        }
    }

    // === UPDATE DATABASE ===
    if (empty(array_filter($errors))) {
        $productObj->name = $product["name"];
        $productObj->category = $product["category"];
        $productObj->price = $product["price"];
        $productObj->pathPhoto = $photoPath;

        if ($productObj->editProduct($product["id"])) {
            header("Location: viewproduct.php");
            exit;
        } else {
            echo "Error updating product.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <style>
        label { display: block; }
        span { color: red; }
        p.error { color: red; margin: 0; }
        img { border-radius: 8px; border: 1px solid #ccc; max-width: 150px; margin-top: 10px; }
    </style>
</head>
<body>
    <h1>Edit Product</h1>
    <label>Fields with <span>*</span> are required</label>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= htmlspecialchars($product["id"] ?? "") ?>">
        <input type="hidden" name="existing_photo" value="<?= htmlspecialchars($product["pathPhoto"] ?? "") ?>">

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
        <label for="photo">Product Photo</label>
        <input type="file" name="photo" id="photo" accept="image/*">
        <p class="error"><?= $errors["photo"] ?? "" ?></p>

        <!-- Existing Image -->
        <div id="preview-container">
            <img id="photo-preview"
                 src="<?= isset($product["pathPhoto"]) && $product["pathPhoto"] != '' ? '../' . htmlspecialchars($product["pathPhoto"]) : '' ?>"
                 alt="Preview"
                 style="display:<?= isset($product["pathPhoto"]) && $product["pathPhoto"] != '' ? 'block' : 'none' ?>">
        </div>

        <br>
        <input type="submit" value="Update Product">
    </form>

<!-- JS: Live Image Preview -->
<script>
document.getElementById("photo").addEventListener("change", function (event) {
    const file = event.target.files[0];
    const preview = document.getElementById("photo-preview");

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
