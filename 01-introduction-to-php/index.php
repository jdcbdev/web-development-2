<!DOCTYPE html>
<html>
    <body>
        <!-- Basic HTML -->
        <h2>Your First PHP File</h2>

        <?php 
            // This is a PHP script
            // "echo" is used to print something on the screen
            echo "Hello, World!";
        ?>
        <h2>Variables and Arithmetic</h2>
        <?php
            // Declare variables
            $x = 15;
            $y = 3;

            // Arithmetic operations
            $sum = $x + $y;
            $diff = $x - $y;
            $prod = $x * $y;
            $quot = $x / $y;

            // Display results
            echo "Sum = $sum <br>";
            echo "Difference = $diff <br>";
            echo "Product = $prod <br>";
            echo "Quotient = $quot <br>";
        ?>
        <h2>Conditional Statement</h2>
        <?php
            $x = 15;
            $y = 3;

            // Check if $y is a factor of $x
            if($x % $y == 0){
                echo "$y is a factor of $x";
            } else {
                echo "$y is NOT a factor of $x";
            }
        ?>
        <h2>Loops</h2>
        <?php
            // Print numbers 1 to 10
            for($i = 1; $i <= 10; $i++){
                echo "$i <br>";
            }

            // Print multiples of 3 or 5 from 1–100
            for($i = 1; $i <= 100; $i++){
                if($i % 3 == 0 || $i % 5 == 0){
                    echo "$i <br>";
                }
            }
        ?>
        <h2>Arrays</h2>
        <?php
            // Indexed array
            $products = array("Product A", "Product B", "Product C");
            echo "$products[0] <br>"; // prints "Product A"

            // Change value
            $products[1] = "Product D";

            // Loop through array
            foreach($products as $p){
                echo "$p <br>";
            }

            // Associative array
            $item = array("name"=>"Product A", "price"=>10.50, "stock"=>12);
            echo $item["name"]; // prints Product A
        ?>
    </body>
</html>
