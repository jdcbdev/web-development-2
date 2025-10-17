<?php
    //resume session here to fetch session values
    session_start();

    if (isset($_SESSION['user']) && ($_SESSION['user']['role'] == 'Staff' || $_SESSION['user']['role'] == 'Admin')){
        // for now will send user to view product
        header('location: ../product/viewproduct.php');
    }

    //if the login button is clicked
    require_once('../classes/account.php');
    $account = new Account();
    $error = "";
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $account->email = htmlentities($_POST['email']);
        $account->password = htmlentities($_POST['password']);
        if ($account->login()){
            $_SESSION["user"] = $account->getStaffByEmail();
            // for now will send user to view product
            header('location: ../product/viewproduct.php');
        }else{
            $error =  'Invalid email/password. Try again.';
        }
    }
    
    //if the above code is false then html below will be displayed
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        label{ display: block; }
        span{ color: red; }
        p.error{ color: red; margin: 0; }
    </style>
</head>
<body>
    <h1>Admin Login</h1>
    <form method="post" action="">
        <label for="email">Email</label>
        <input type="text" id="email" name="email" value="<?php if(isset($_POST['email'])) { echo $_POST['email']; } ?>">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" value="<?php if(isset($_POST['password'])) { echo $_POST['password']; } ?>">
        <br>
        <p class="error"><?= $error ?></p>
        <br>
        <button type="submit" name="login">Login</button>
    </form>
</body>
</html>