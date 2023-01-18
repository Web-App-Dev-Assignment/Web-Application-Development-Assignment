<?php
include_once __DIR__ . "\\..\\php\\rock_paper_scissors.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
  if(isReady($_POST['user_id']))//check if the players game details are checked and reset
  {
    $gameStatus = checkMatch($_POST['user_id'], $_POST['game_id']);// check if any player's hp is equal to zero
    if(empty($gameStatus))
    {
      $redirect=false;
    }
    else
    {
      include_once __DIR__ . "\\..\\php\\game.php";
      deleteUserGameRecord('game_table', $_POST['user_id']);
      unset($_SESSION['game_id']);
      $redirect=true;
    }

    $output = array("isReady"=>true, "redirect"=>$redirect, "gameStatus"=>$gameStatus);
    exit(json_encode($output));
  }
  else
  {
    $output = array("isReady"=>false, "redirect"=>false);
    exit(json_encode($output));
  }
}
  
?>