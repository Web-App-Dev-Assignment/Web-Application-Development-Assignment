<?php
    include_once __DIR__ . "/functions.php";
    $db_conn = include_once __DIR__ . "/database.php";
    
    $emailErr = email_condition($_POST["email"]);

    if(empty($emailErr))
    {
        exit();
    }
    else
    {
        exit($emailErr);
    }
?>