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

//The connection will be closed automatically when the script ends. To close the connection before, use the following:
//$stmt->close();
//$db_conn->close(); 

//return $db_conn;
?>