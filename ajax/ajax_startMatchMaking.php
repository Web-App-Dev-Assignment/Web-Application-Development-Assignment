<?php
include_once __DIR__ . "\\..\\php\\matchmaking.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
  $errMsg = startMatchMaking($_POST['user_id']);

  if (empty($errMsg))
  {
    $output = array("successmessage"=>$errMsg);
    exit(json_encode($output));
  }
  else
  {
    $output = array("errormessage"=>$errMsg);
    exit(json_encode($output));
  }
}
  
?>