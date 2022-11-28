<?php
include_once __DIR__ . "\\..\\php\\rock_paper_scissors.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
  rockPaperScissors($_POST['user_id'], $_POST['game_id']);
  // $msg = matchMaking($_POST['user_id'], $_POST['game_type']);
  // if($msg === "Opponent found.")
  // {
  //   include_once __DIR__ . "\\..\\php\\game.php";
  //   $_SESSION['game_id'] = getGameId($user_id);
  //   $output = array("gametype"=>$_POST['game_type']);
  //   exit(json_encode($output));
  // }
  // else
  // {
  //   $output = array("errormessage"=>$msg);
  //   exit(json_encode($output));
  // }
}
  
?>