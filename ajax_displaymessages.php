<?php
	$db_conn = include_once __DIR__ . "/database.php";
  
  //displayMessage($_SESSION['game_id']);
  $messages = displayMessage("IS NULL");
  echo "$messages";
?>