<?php
include_once __DIR__ . '/database.php';

function createUserTable()
{
  global $db_conn;
  
  $sql_table = "CREATE TABLE $tbname
  (
    id VARCHAR(128) NOT NULL PRIMARY KEY,
    name VARCHAR(128),
    username VARCHAR(128) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    gender ENUM ('male','female','other','prefer_not_to_say'),  
    last_online TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
  )";//ON INSERT or ON UPDATE, check
  $db_conn->query($sql_table);
  //$stmt->prepare($sql);
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
      //debug_to_console(test_escape_char($e), 0);
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
    //need toggle password visibility, need ensure user type 8~16 char with special character
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
      //debug_to_console(test_escape_char($e), 0);
    }
  }
  
  return $emailErr;
}

//--------------------------Inserting to the table--------------------------
function createUser($name, $username, $password, $email){
  
  try
  {
    global $tbname, $db_conn;
    $id = md5(uniqid());
    $hash = password_hash($password,PASSWORD_ARGON2ID);
    $sql = "INSERT INTO $tbname (id,name ,username, password, email)
    VALUES ( ?, NULLIF(?,''), ?, ?, NULLIF(?,''))";
    $stmt = $db_conn->stmt_init();

    try
    {
      $stmt->prepare($sql);
    }
    catch(Throwable $e){
      // debug_to_console(test_escape_char($db_conn->error) . "\\nError Code : " . $db_conn->errno ,1);
      // debug_to_console("Error preparing the SQL code. \\nError:\\n" . test_escape_char($e),1);
      
      if($db_conn->errno === 1146)//1146 Table doesn't exist
      {
        // debug_to_console("Table $tbname doesn\\'t exists.", 1);
        try
        {
          createUserTable();
          // debug_to_console("Table $tbname not found. Table $tbname created.",1);
        }
        catch(Throwable $e)
        {
          // debug_to_console(test_escape_char($db_conn->error) . "\\nError Code : " . $db_conn->errno ,1);
          if ($db_conn->errno === 1050)//1050 duplicate table
          {
            // debug_to_console("Table $tbname already exists. \\nError:\\n" . test_escape_char($e),1);
          }
          else
          {
            // debug_to_console("Opps, something went wrong. \\nError:\\n" . test_escape_char($e),2);
          }
        }
      }
      else
      {
        // debug_to_console("Opps, something went wrong. \\nError:\\n" . test_escape_char($e),2);
      }
    }

    $stmt->bind_param("sssss", $id, $name, $username, $hash, $email);

    try
    {
      $stmt->execute();
      // debug_to_console("Insertion into table $tbname success!",0);
      //header("Location: signup-success.html");
      //exit;
    }
    catch(Throwable $e)
    {
      // debug_to_console(test_escape_char($db_conn->error) . "\\nError Code : " . $db_conn->errno ,1);
      if($db_conn->errno === 1062)//1062 duplicate Unique information
      {
        // debug_to_console("Duplicate Unique entry. \\nError:\\n" . test_escape_char($e),1);
      }
      else
      {
        // debug_to_console("Insertion into table $tbname unsuccessful. \\nError:\\n" . test_escape_char($e),2);
      }
    }

    //$sql = "INSERT INTO $tbname (id,name ,username, pw, email)
    //VALUES ( '$id' , NULLIF('$name','') ,'$username' , '$hash' , NULLIF('$email',''))";
    //$db_conn->query($sql);
  }
  catch(Throwable $e)
  {
    // debug_to_console(test_escape_char($db_conn->error) . "\\nError Code : " . $db_conn->errno ,1);
    //debug_to_console("Opps, something went wrong. \\nError:\\n" . test_escape_char($e), 2);
  }
}
//--------------------------End of insertion to table--------------------------
?>