<?php
  include_once __DIR__ . "\\..\\php\\function.php";

  session_start();

  if (isset($_SESSION["user_id"]))
  {
    include_once __DIR__ . "\\..\\php\\chat.php";
  }
  else
  {
    header("Location: index.php");
    exit();
  }
?>

<!DOCTYPE HTML>  
<html>
<head>
  <title>Rock Paper Scissors</title>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
  <link rel="stylesheet" href="../css/stylesheet.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<style>
</style>
</head>
<body style="max-width: none;">
  </div>
  <div>
    <p>Some text here</p>
  </div>
  <div class="chatSetting" style="margin-right:0.3em"><!--temp, might not be necessary to fix text input--->
    <div class ="chat">
      <button type="button" class="collapsible" id="chatButton" style="max-width:none;width:100%;">Chat</button>
      <div class="chatBox">
      </div>
      <input type="text" id="chatInput" name="chatInput" placeholder="Message" class="chatInput" style="">
    </div>
  </div>
  

</body>
</html>

<script>
$(document).ready(function() 
{
	$("#chatInput").keyup(function(e)
  {
			if(e.keyCode == 13)//the enter key
      {
				insertMessage($_SESSION["game_id"]);
			}
	})
	
	setInterval(function()
  {
			displayMessage('='.$_SESSION["game_id"]);
	},1500)
	
	displayMessage('='.$_SESSION["game_id"]);
	
});
</script>