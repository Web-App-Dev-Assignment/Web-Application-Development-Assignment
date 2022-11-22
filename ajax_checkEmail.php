<?php
    include_once __DIR__ . "/functions.php";
    $db_conn = include_once __DIR__ . "/database.php";
    
    $emailErr = emailCondition($_POST["email"]);

    if(empty($emailErr))
    {
        $output = array("successmessage"=>"");
        exit(json_encode($output));
    }
    else
    {
        $output = array("errormessage"=>$emailErr);
        exit(json_encode($output));
    }
?>