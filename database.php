<?php
include_once __DIR__ . "/Functions.php";
//Under the xampp control panel, ensure that the module Apache and MySQL has been started
//Refer to the xampp control panel, Start MySQL -> admin -> privilages/user accounts
$host = "localhost";
$hostusername = "root";
$hostpassword = "";
$dbname = "myDB";
$tbname = "Users";

//--------------------------Connecting to host code--------------------------
try
{
  $db_conn = mysqli_connect($host,$hostusername,$hostpassword);
  debug_to_console("Connected $host successfully!",0);
}
catch(Throwable $e)
{
  $e = test_escape_char($e);
  debug_to_console("Connection to $host unsuccessful. \\nError:\\n$e",2);
}
//--------------------------End of connecting to host code--------------------------

//--------------------------Connecting to the database code--------------------------
try
{
  $db_conn->select_db($dbname);
  debug_to_console("Connected database $dbname successfully!",0);
}
catch(Throwable $e)
{
  try
  {
    //Create the database if not found, then connect to the newly created database
    $sql = "CREATE DATABASE $dbname";
    $db_conn->query($sql);
    $db_conn->select_db($dbname);
    $e = test_escape_char($e);
    debug_to_console("Database $dbname  not found. Database $dbname created. \\nWarning:\\n$e",1);
  }
  catch(Throwable $e)
  {
    $e = test_escape_char($e);
    debug_to_console("Unable to connect to the database $dbname. Try checking if MySQL is running. \\nError:\\n$e",2);
  }
}
//--------------------------End of connecting to database code--------------------------

//--------------------------Inserting to the table--------------------------
function insert_to_table($name, $username, $password, $email){
  
  try
  {
    global $tbname, $db_conn;
    $id = md5(uniqid());
    $hash = password_hash($password,PASSWORD_ARGON2ID);
    $sql = "INSERT INTO $tbname (id,name ,username, pw, email)
    VALUES ( ?, NULLIF(?,''), ?, ?, NULLIF(?,''))";
    $stmt = $db_conn->stmt_init();

    try
    {
      $stmt->prepare($sql);
    }
    catch(Throwable $e){
      debug_to_console(test_escape_char($db_conn->error) . "\\nError Code : " . $db_conn->errno ,1);
      debug_to_console("Error preparing the SQL code. \\nError:\\n" . test_escape_char($e),1);
      
      if($db_conn->errno === 1146)//1146 Table doesn't exist
      {
        debug_to_console("Table $tbname doesn\\'t exists.", 1);
        try
        {
          $sql_table = "CREATE TABLE $tbname
          (
            id VARCHAR(32) NOT NULL PRIMARY KEY,
            name VARCHAR(128),
            username VARCHAR(128) NOT NULL UNIQUE,
            pw VARCHAR(255) NOT NULL,
            email VARCHAR(255) UNIQUE,
            reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
          )";
          $db_conn->query($sql_table);
          $stmt->prepare($sql);
          debug_to_console("Table $tbname not found. Table $tbname created.",1);
        }
        catch(Throwable $e)
        {
          debug_to_console(test_escape_char($db_conn->error) . "\\nError Code : " . $db_conn->errno ,1);
          if ($db_conn->errno === 1050)//1050 duplicate table
          {
            debug_to_console("Table $tbname already exists. \\nError:\\n" . test_escape_char($e),1);
          }
          else
          {
            debug_to_console("Opps, something went wrong. \\nError:\\n" . test_escape_char($e),2);
          }
        }
      }
      else
      {
        debug_to_console("Opps, something went wrong. \\nError:\\n" . test_escape_char($e),2);
      }
    }

    $stmt->bind_param("sssss", $id, $name, $username, $hash, $email);

    try
    {
      $stmt->execute();
      debug_to_console("Insertion into table $tbname success!",0);
    }
    catch(Throwable $e)
    {
      debug_to_console(test_escape_char($db_conn->error) . "\\nError Code : " . $db_conn->errno ,1);
      if($db_conn->errno === 1062)//1062 duplicate Unique information
      {
        debug_to_console("Duplicate Unique entry. \\nError:\\n" . test_escape_char($e),1);
      }
      else
      {
        debug_to_console("Insertion into table $tbname unsuccessful. \\nError:\\n" . test_escape_char($e),2);
      }
    }

    //$sql = "INSERT INTO $tbname (id,name ,username, pw, email)
    //VALUES ( '$id' , NULLIF('$name','') ,'$username' , '$hash' , NULLIF('$email',''))";
    //$db_conn->query($sql);
  }
  catch(Throwable $e)
  {
    debug_to_console(test_escape_char($db_conn->error) . "\\nError Code : " . $db_conn->errno ,1);
    debug_to_console("Opps, something went wrong. \\nError:\\n" . test_escape_char($e), 2);
  }
}
//--------------------------End of insertion to table--------------------------

//The connection will be closed automatically when the script ends. To close the connection before, use the following:
//$stmt->close();
//$db_conn->close(); 

return $db_conn;
?>