<?php
include_once __DIR__ . "\\..\\php\\matchmaking.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
  cancelMatchMaking($_POST['user_id']);
}
  
?>