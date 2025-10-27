<?php
    //resume session here to fetch session values
    session_start();

    // if user already logged in
    if (isset($_SESSION['user']) && ($_SESSION['user'] == 'Staff' || $_SESSION['user'] == 'Admin')){
        // for now will send user to view product
        header('location: ../product/viewproduct.php');
    }else{
        // if user is not log in, send them to login
        header('location: account/login.php');
    }