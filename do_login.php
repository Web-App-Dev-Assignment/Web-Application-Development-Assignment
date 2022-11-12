<?php
include_once __DIR__ . "/functions.php";

try
{
  //if (isset($_POST['login']))
  if ($_SERVER["REQUEST_METHOD"] == "POST") 
  {
    $db_conn = require_once __DIR__ . "/database.php";

    $sql = "SELECT * FROM $tbname 
    WHERE ";

    if(filter_var($_POST["username"], FILTER_VALIDATE_EMAIL))
    {
      $sql .= "email = ";
    }
    else
    {
      $sql .= "username = ";
    }

    $sql = sprintf("$sql '%s'",
    $db_conn->real_escape_string($_POST["username"]));
  
    $result = $db_conn->query($sql);
  
    $user = $result->fetch_assoc();
  
    if($user)
    {
      if(password_verify($_POST["password"], $user["password"]))
      {
        debug_to_console("Login successful.", 0);
        session_start();
        session_regenerate_id();//prevent session fixation attack
  
        $_SESSION["user_id"] = $user["id"];
  
        exit('@0^/s&d~v~x2LiN?^-login success-k+ZJ[+Nk1QK+b');
      }
      else
      {
        debug_to_console("Login unsuccessful.", 1);
        exit('fail');
      }
    }
    else
    {
      exit('fail');
    }
  }
}
catch(Throwable $e)
{
  debug_to_console(test_escape_char($e), 0);
  if($db_conn->errno === 1146)//1146 Table doesn't exist
  {
    debug_to_console("Login unsuccessful.", 1);
  }
  exit('fail');
}


?>