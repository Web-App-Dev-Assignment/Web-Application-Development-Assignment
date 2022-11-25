<?php
include_once __DIR__ . "\\..\\php\\functions.php";
include_once __DIR__ . "\\..\\php\\signup.php";

$usernameErr = usernameCondition($_POST["username"]);

if(empty($usernameErr))
{
    $output = array("successmessage"=>$usernameErr);
    exit(json_encode($output));
}
else
{
    $output = array("errormessage"=>$usernameErr);
    exit(json_encode($output));
}
?>