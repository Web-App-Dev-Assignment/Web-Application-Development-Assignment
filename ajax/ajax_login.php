<?php
include_once __DIR__ . "\\..\\php\\functions.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
  include_once __DIR__ . "\\..\\php\\login.php";

  try
  {
    $loginErr = login($_POST["username"], $_POST["password"]);

    if(empty($loginErr))
    {
        $output = array("successmessage"=>$loginErr);
        exit(json_encode($output));
    }
    else
    {
        $output = array("errormessage"=>$loginErr);
        exit(json_encode($output));
    }
  }
  catch(Throwable $e)
  {
    $output = array("errormessage"=>"Login unsuccessful");
    if($db_conn->errno === 1146)//1146 Table doesn't exist
    {

    }
    exit(json_encode($output));
  }
}
else
{
  $output = array("errormessage"=>"Login unsuccessful");
  exit(json_encode($output));
}
?>