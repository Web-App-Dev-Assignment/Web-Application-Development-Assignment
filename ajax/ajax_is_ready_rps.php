<?php
include_once __DIR__ . "\\..\\php\\rock_paper_scissors.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
  if(isReady($_POST['user_id']))
  {
    $output = array("isReady"=>true);
    exit(json_encode($output));
  }
  else
  {
    $output = array("isReady"=>false);
    exit(json_encode($output));
  }
}
  
?>