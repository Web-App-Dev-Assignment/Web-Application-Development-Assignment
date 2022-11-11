<?php
include_once __DIR__ . "/functions.php";
$db_conn = include_once __DIR__ . "/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
  try
  {
    insert_to_table($_POST["name"], $_POST["username"], $_POST["password"], $_POST["email"]);
    exit('@0^/s&d~v~x2LiN?^signup successk+ZJ[+Nk1QK+b');
  }
  catch(Throwable $e)
  {
    debug_to_console(test_escape_char($e), 0);
    exit('fail');
  }
}
else
{
  exit('fail');
}
?>