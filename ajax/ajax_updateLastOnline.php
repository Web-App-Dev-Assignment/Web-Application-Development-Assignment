<?php
include_once __DIR__ . "\\..\\php\\onlinestatus.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
  updateLastOnline($_POST["user_id"]);
}
?>