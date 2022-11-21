<?php
$db_conn = require_once __DIR__ . "/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
  matchMaking($_POST['user_id']);
}
  
?>