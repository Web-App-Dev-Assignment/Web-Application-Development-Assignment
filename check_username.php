<?php
    include_once __DIR__ . "/functions.php";
    $db_conn = include_once __DIR__ . "/database.php";
    
    $usernameErr = username_condition($_POST["username"]);

    if(empty($usernameErr))
    {
        exit();
    }
    else
    {
        exit($usernameErr);
    }
?>