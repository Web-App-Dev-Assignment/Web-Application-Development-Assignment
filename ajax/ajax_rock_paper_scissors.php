<?php
include_once __DIR__ . "\\..\\php\\rock_paper_scissors.php";


if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
  $msg = rockPaperScissors($_POST['user_id'], $_POST['game_id']);
  $output = array("gameStatus"=>$msg);
  exit(json_encode($output));
}
  
?>