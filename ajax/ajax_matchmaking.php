<?php
include_once __DIR__ . "\\..\\php\\matchmaking.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
  $game_type = matchMaking($_POST['user_id']);
  if(!empty($game_type))
  {
    $output = array("gametype"=>$game_type);
    exit(json_encode($game_type));
  }
  else
  {
    $output = array("errormessage"=>$game_type);
    exit(json_encode($game_type));
  }
}
  
?>