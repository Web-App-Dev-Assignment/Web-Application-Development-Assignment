<?php
$db_conn = include_once __DIR__ . "/database.php";

updateLastOnline();
if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
  $onlineStatus = checkOnlineStatus($_SESSION["user_id"]);

  if (!$onlineStatus)//is offline
  {
    $sql = "DELETE FROM $matchmaking_db
    WHERE id = $user_id";
    $db_conn->query($sql);
  }
}
?>