<?php
include_once __DIR__ . "\\..\\php\\action.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
  switch ($_POST['action']) 
  {
    case 'delete':
      delete($_POST['table'], $_POST['unique_column'], $_POST['unique_value'], $_POST['column'], $_POST['value']);
      break;
    case 'update':
      update($_POST['table'], $_POST['unique_column'], $_POST['unique_value'], $_POST['column'], $_POST['value']);
      break;
    default:
      break;
  }
  
  // $errMsg = cancelMatchMaking($_POST['user_id']);
  // if(empty($errMsg))
  // {
  //   $output = array("successmessage"=>$errMsg);
  //   exit(json_encode($output));
  // }
  // else
  // {
  //   $output = array("errormessage"=>$errMsg);
  //   exit(json_encode($output));
  // }
}
  
?>