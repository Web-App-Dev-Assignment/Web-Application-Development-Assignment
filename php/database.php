<?php
include_once __DIR__ . "/function.php";
//Under the xampp control panel, ensure that the module Apache and MySQL has been started

//To connect to the localhost from another device, make sure that the localhost and device are connected to the same network. Also make sure that the localhost's firewall is disabled.

//Refer to the xampp control panel, Start MySQL -> admin -> privilages/user accounts
$host = "localhost";
$hostusername = "root";
$hostpassword = "";
$dbname = "mydb";
$tbname = "users";
$game_tb = "game_table";
$chat_tb = "chat";
$matchmaking_tb = "matchmaking_table";

$timezone = "Asia/Singapore";
date_default_timezone_set($timezone);
//--------------------------Connecting to host code--------------------------
try
{
  $db_conn = mysqli_connect($host,$hostusername,$hostpassword);
}
catch(Throwable $e)
{
  $e = test_escape_char($e);
}
//--------------------------End of connecting to host code--------------------------

//--------------------------Connecting to the database code--------------------------
try
{
  $db_conn->select_db($dbname);
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
  }
  catch(Throwable $e)
  {
    $e = test_escape_char($e);
  }
}
//--------------------------End of connecting to database code--------------------------
?>