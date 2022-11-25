<?php
include_once __DIR__ . "\\..\\php\\matchmaking.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
  matchMaking($_POST['user_id']);
}
  
?>