<?php
include_once __DIR__ . "\\..\\php\\rock_paper_scissors.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
  setMove($_POST['user_id'], $_POST['move']);
  echo "testing";
}

  
?>