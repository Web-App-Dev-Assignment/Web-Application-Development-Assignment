<?php
    include_once __DIR__ . "/functions.php";
    $db_conn = include_once __DIR__ . "/database.php";
    
    $usernameErr = usernameCondition($_POST["username"]);

    if(empty($usernameErr))
    {
        exit();
    }
    else
    {
        $output = array("errormessage"=>$usernameErr);
        exit(json_encode($output));
    }
?>