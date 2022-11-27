<?php
include_once __DIR__ . "\\..\\php\\matchmaking.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
  $msg = matchMaking($_POST['user_id'], $_POST['game_type']);
  if($msg === "Opponent found.")
  {
    $output = array("gametype"=>$_POST['game_type']);
    exit(json_encode($output));
  }
  else
  {
    $output = array("errormessage"=>$msg);
    exit(json_encode($output));
  }
}
  
?>