<?php
include_once __DIR__ . "/functions.php";
//Under the xampp control panel, ensure that the module Apache and MySQL has been started
//Refer to the xampp control panel, Start MySQL -> admin -> privilages/user accounts
$host = "localhost";
$hostusername = "root";
$hostpassword = "";
$dbname = "mydb";
$tbname = "users";
$game_tb = "game";
$chat_tb = "chat";
$matchmaking_tb = "matchmaking";

$timezone = "Asia/Singapore";
date_default_timezone_set($timezone);
//--------------------------Connecting to host code--------------------------
try
{
  $db_conn = mysqli_connect($host,$hostusername,$hostpassword);
  //debug_to_console("Connected $host successfully!",0);
}
catch(Throwable $e)
{
  $e = test_escape_char($e);
  //debug_to_console("Connection to $host unsuccessful. \\nError:\\n$e",2);
}
//--------------------------End of connecting to host code--------------------------

//--------------------------Connecting to the database code--------------------------
try
{
  $db_conn->select_db($dbname);
  //debug_to_console("Connected database $dbname successfully!",0);
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
    //debug_to_console("Database $dbname  not found. Database $dbname created. \\nWarning:\\n$e",1);
  }
  catch(Throwable $e)
  {
    $e = test_escape_char($e);
    //debug_to_console("Unable to connect to the database $dbname. Try checking if MySQL is running. \\nError:\\n$e",2);
  }
}
//--------------------------End of connecting to database code--------------------------

function isInTable($table_name, $user_id)//mabye write a function to differentiate you and other players?
{
  global $db_conn, $game_tb, $tbname;
  try
  {
    $sql = sprintf("SELECT id FROM %s
    WHERE id = '%s'", $table_name, $user_id);
    $stmt = $db_conn->stmt_init();
    
    try
    {
      $result = $db_conn->query($sql);
    }
    catch(Throwable $e)
    {
      if($db_conn->errno === 1146 && $table_name === $game_tb)//1146 Table doesn't exist
      {
        createGameTable();
        $result = $db_conn->query($sql);
      }
      return;//do not remove this, it helps to initialize the $result outside the try catch block
    }
    $user = $result->fetch_assoc();
    if($user)
    {
      //header("Location: multiplayer.php");
      //return $user['game_id'];//return or exit
      return true;
    }
    else
    {
      return false;
    }
  }
  catch(Throwable $e)
  {
    //echo $e;
  }
  
}



//The connection will be closed automatically when the script ends. To close the connection before, use the following:
//$stmt->close();
//$db_conn->close(); 

//return $db_conn;
?>