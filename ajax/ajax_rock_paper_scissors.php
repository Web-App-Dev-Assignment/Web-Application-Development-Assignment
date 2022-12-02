<?php
include_once __DIR__ . "\\..\\php\\rock_paper_scissors.php";


if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
  $msg = rockPaperScissors($_POST['user_id'], $_POST['game_id']);
  // $msg = matchMaking($_POST['user_id'], $_POST['game_type']);
  // switch ($msg) 
  // {
  //   case 'Draw':
  //     $output = array("gameStatus"=>'Draw');
  //     break;
  //   case 'Win':
  //     $output = array("gameStatus"=>"Win");
  //     break;
  //   case 'Lose':
  //     $output = array("gameStatus"=>"Lose");
  //     break;
  //   case 'Win match':
  //     $output = array("gameStatus"=>"Win match");
  //     break;
  //   case 'Lose match':
  //     $output = array("gameStatus"=>"Lose match");
  //     break;
  //   default:
  //   $output = array("gameStatus"=>"");
  //     break;
  // }
  //exit("testing");
  //$output = array("gameStatus"=>$msg);
  
  $output = array("gameStatus"=>$msg);
  exit(json_encode($output));
}
  
?>