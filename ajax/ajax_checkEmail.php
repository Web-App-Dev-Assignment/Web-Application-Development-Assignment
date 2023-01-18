<?php
include_once __DIR__ . '\\..\\php\\function.php';
include_once __DIR__ . '\\..\\php\\signup.php';

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