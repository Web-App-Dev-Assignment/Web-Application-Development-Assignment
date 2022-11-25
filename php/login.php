<?php
include_once __DIR__ . "/database.php";

function login($username_email, $password)
{
  global $db_conn, $tbname;
  
  $loginErr = "";
  
  $sql = "SELECT id, `password` FROM $tbname 
  WHERE ";

  if(filter_var($username_email, FILTER_VALIDATE_EMAIL))
  {
    $sql .= "email = ";
  }
  else
  {
    $sql .= "username = ";
  }

  $sql = sprintf("$sql '%s'",
  $db_conn->real_escape_string($username_email));

  try
  {
    $stmt = $db_conn->prepare($sql);
  }
  catch(Throwable $e)
  {
    $loginErr = "Login unsuccessful";
    return $loginErr;
  }
  
  $stmt->execute();
  $result = $stmt->get_result();

  $user = $result->fetch_assoc();
  
  if($user)
  {
    
    if(password_verify($password, $user["password"]))
    {
      session_start();
      session_regenerate_id();//prevent session fixation attack
      $_SESSION["user_id"] = $user["id"];
    }
    else
    {
      $loginErr = "Login unsuccessful";
    }
  }
  else
  {
    $loginErr = "Login unsuccessful";
    
  }
  return $loginErr;
}


?>