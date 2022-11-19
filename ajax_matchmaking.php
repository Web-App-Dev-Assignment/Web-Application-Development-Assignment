<?php
$db_conn = require_once __DIR__ . "/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
  //--------------------------Check if in game--------------------------
  $sql = "SELECT * FROM $game_db
  WHERE id = '{$_SESSION["user_id"]}'";

  $result = $db_conn->query($sql);
  
  $user = $result->fetch_assoc();

  if($user)//is currently ingame
  {
    //write code to redirect player to multiplay game here
    $output = array("errormessage"=>"Already in game.");
    exit(json_encode($output));
  }
  //--------------------------End of check if in game--------------------------

  //--------------------------Matchmaking--------------------------
  $sql = "SELECT * FROM $matchmaking_db
  WHERE id != '{$_SESSION["user_id"]}'
  LIMIT 1";

  $result = $db_conn->query($sql);
  
  $user = $result->fetch_assoc();

  if($user)//there is player matchmaking
  {
    //$user["id"]//the other user
    //$_SESSION["user_id"]//the current user
    $sql = "DELETE FROM $matchmaking_db
    WHERE id IN ('{$_SESSION["user_id"]}','{$user["id"]}')";
    $db_conn->query($sql);

    $game_id = md5(uniqid());
    $color = array("black","white");
    shuffle($color);
    $id = $_SESSION["user_id"];
    $sql = "INSERT INTO $game_db (game_id, chess_color, id) 
    VALUES (?, ?, ?)";
    $stmt = $db_conn->prepare($sql);

    $stmt->bind_param("sss", $game_id, array_pop($color), $id);
    $stmt->execute();

    $id = $user["id"];
    $stmt->execute();

    $output = array("successmessage"=>"Player found.");
    exit(json_encode($output));
  }
  //--------------------------End of matchmaking--------------------------
}
  
?>