<?php
//include_once __DIR__ . "\\..\\php\\functions.php";
include_once __DIR__ . "\\..\\php\\signup.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
  try
  {
    createUser($_POST["name"], $_POST["username"], $_POST["password"], $_POST["email"]);
    $output = array("successmessage"=>"Signup unsuccessful");
    exit(json_encode($output));
    //exit('@0^/s&d~v~x2LiN?^signup successk+ZJ[+Nk1QK+b');
  }
  catch(Throwable $e)
  {
    //debug_to_console(test_escape_char($e), 0);
    $output = array("errormessage"=>"Signup unsuccessful");
    exit(json_encode($output));
  }
}
else
{
  $output = array("errormessage"=>"Signup unsuccessful");
  exit(json_encode($output));
}
?>