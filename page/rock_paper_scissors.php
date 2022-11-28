<?php
  include_once __DIR__ . "\\..\\php\\function.php";

  session_start();

  if (isset($_SESSION["user_id"]) && isset($_SESSION['game_id']))
  {
    //include_once __DIR__ . "\\..\\php\\chat.php";
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
  <div style="height:100%;width:100%;">
    <p>Some text here</p>
    <button type="button" id="rock" style="display:flex;">✊</button>
    <button type="button" id="paper" style="display:flex;">🖐</button>
    <button type="button" id="scissors" style="display:flex;">✌</button>
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
	updateLastOnline(<?php echo json_encode($_SESSION["user_id"]);?>);
  setInterval(function()
  {
    updateLastOnline(<?php echo json_encode($_SESSION["user_id"]);?>);
  }, 5000);
  
  $("#chatInput").keyup(function(e)
  {
			if(e.keyCode == 13)//the enter key
      {
				insertMessage($_SESSION["game_id"]);
			}
	})

  $(".chatBox").load("../ajax/ajax_displaymessage.php",
    {
      game_id:'='.$_SESSION["game_id"]
    }
    );
	setInterval(function()
  {
    //$(".chatBox").html(displayMessage("IS NULL"));
    $(".chatBox").load("../ajax/ajax_displaymessage.php",
    {
      game_id:'='.$_SESSION["game_id"]
    }
    );
	},1500)
	
  $("#chatButton").on('click', function()
    { 
      var chatSetting = $(this).closest('.chatSetting')[0];
      if (chatSetting.style.height)
      {
        $('.chatSetting').attr('style', '');
        $('.chatBox').attr('style', '');
      } 
      else 
      {
        $('.chatSetting').attr('style', 'height:50%');
        $('.chatBox').attr('style', 'overflow-y:scroll');
      } 
    })
});
</script>

<script src="../javascript/function.js"></script>
<script src="../javascript/onlinestatus.js"></script>
<script src="../javascript/chat.js"></script>