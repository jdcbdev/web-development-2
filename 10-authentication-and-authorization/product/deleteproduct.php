<?php

require_once "../classes/product.php";
$productObj = new Product();

if($_SERVER["REQUEST_METHOD"] == "GET"){
    if(isset($_GET["id"])){
        $pid = trim(htmlspecialchars($_GET["id"]));
        $product = $productObj->fetchProduct($pid);
        
        if(!$product){
            echo "<a href='viewproduct'>View Product</a>";
            exit("No product found");
        }else{
            $productObj->deleteProduct($pid);
            header("Location: viewproduct.php");
        }
    }else{
        echo "<a href='viewproduct'>View Product</a>";
        exit("No product found");
    }
}