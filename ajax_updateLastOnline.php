<?php
$db_conn = include_once __DIR__ . "/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
  updateLastOnline($_POST["user_id"]);
  // $onlineStatus = checkOnlineStatus($_POST["user_id"]);

  // if (!$onlineStatus)//is offline
  // {
  //   $sql = "DELETE FROM $matchmaking_db
  //   WHERE id = $user_id";
  //   $db_conn->query($sql);
  // }
}
?>