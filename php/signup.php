<?php
include_once __DIR__ . '/database.php';

function createUserTable()
{
  global $tbname, $db_conn;
  
  $sql_table = "CREATE TABLE $tbname
  (
    id VARCHAR(128) NOT NULL PRIMARY KEY,
    `name` VARCHAR(128),
    username VARCHAR(128) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE,
    gender ENUM ('male','female','other','prefer_not_to_say'),
    `role` ENUM ('user','admin') NOT NULL,
    last_online TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
  )";//ON INSERT or ON UPDATE, check
  $db_conn->query($sql_table);
}

//format of the username condition
function usernameCondition($username)
{
  global $tbname, $db_conn;
  $usernameErr = "";

  if (empty($username)) 
  {
    $usernameErr = "Username is required";
    return $usernameErr;
  } 
  else
  {
    try
    {
      $sql = sprintf("SELECT username FROM $tbname 
      WHERE username = '%s'",
      $db_conn->real_escape_string($_POST["username"]));
    
      $result = $db_conn->query($sql);
      $user = $result->fetch_assoc();

      if($user)
      {
        $usernameErr = "Username has already been taken.";
      }
    }
    catch(Throwable $e)
    {
    }
    return $usernameErr;
  }
}

//format of the password condition
function passwordCondition($password)
{
  $passwordErr = "";
  if(strlen($password)<8 || strlen($password)>16){
    $passwordErr = "Password must be at least 8-16 characters.";
  }

  else if (!preg_match("/[a-zA-Z]/", $password)) {
    $passwordErr = "Password must contain at least one letter.";
  }
  
  else if (!preg_match("/[0-9]/", $password)) {
    $passwordErr = "Password must contain at least one number.";
  }
  else if (!preg_match("/[^A-Za-z0-9]/", $password)){
    $passwordErr = "Password must contain at least one special character.";
  }
  return $passwordErr;
}

//format of the email condition
function emailCondition($email)
{
  global $tbname, $db_conn;
  $emailErr = "";
  if (empty($email)) 
  {
    return $emailErr;
  } 
  else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
  {
    $emailErr = "Invalid email format";
  }
  else
  {
    try
    {
      $sql = sprintf("SELECT email FROM $tbname
      WHERE email = '%s'",
      $db_conn->real_escape_string($_POST["email"]));
    
      $result = $db_conn->query($sql);
      $user = $result->fetch_assoc();

      if($user)
      {
        $emailErr = "Email has already been taken.";
      }
    }
    catch(Throwable $e)
    {
    }
  }
  
  return $emailErr;
}

//--------------------------Inserting to the table--------------------------
function createUser($name, $username, $password, $email, $gender){
  
  try
  {
    global $tbname, $db_conn;
    $id = md5(uniqid());
    $hash = password_hash($password,PASSWORD_ARGON2ID);
    $sql = "INSERT INTO $tbname (id,`name` ,username, `password`, email, gender, `role`)
    VALUES ( ?, NULLIF(?,''), ?, ?, NULLIF(?,''), ?, ?)";
    $stmt = $db_conn->stmt_init();

    try
    {
      $stmt->prepare($sql);
    }
    catch(Throwable $e){
       debug_to_console(test_escape_char($db_conn->error) . "\\nError Code : " . $db_conn->errno ,1);
      
      if($db_conn->errno === 1146)//1146 Table doesn't exist
      {
        try
        {
          createUserTable();
        }
        catch(Throwable $e)
        {
        }
      }
    }
    $role = 'user';

    $stmt->bind_param("sssssss", $id, $name, $username, $hash, $email, $gender, $role);

    try
    {
      $stmt->execute();
    }
    catch(Throwable $e)
    {
       debug_to_console(test_escape_char($db_conn->error) . "\\nError Code : " . $db_conn->errno ,1);
    }

  }
  catch(Throwable $e)
  {
  }
}
//--------------------------End of insertion to table--------------------------
?>