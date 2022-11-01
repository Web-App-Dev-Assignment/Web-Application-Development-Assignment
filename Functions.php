<?php

$banana = "banana";

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function test_escape_char($data){
  $sentence = "";
  foreach(preg_split("/((\r?\n)|(\r\n?))/", $data) as $line)
  {
    $line = str_replace("\\","\\\\",$line);
    $line = str_replace("'","\\'",$line);
    $sentence .=$line;
  } 
  return $sentence;
}

function debug_to_console($data, $switch){
  switch ($switch) {
    case 0:
      echo "<script>console.log('$data');</script>";
      break;
    case 1:
      echo "<script>console.warn('$data');</script>";
      break;
    case 2:
      echo "<script>console.error('$data');</script>";
      break;
  } 
}

?>

<script>
function passwordVisibility($string) {
  var x = document.getElementById($string);
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}
</script>