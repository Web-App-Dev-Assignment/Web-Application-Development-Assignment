<?php
  include_once __DIR__ . "\\..\\php\\function.php";

  session_start();

  if (!isset($_SESSION["user_id"]) && empty($_SESSION['game_id']))
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

  <div id="darkLayer" class="darkLayer" style="display:none">
    <p id="gameText" class="gameText" style="">Waiting for opponent to make a move<span id="animatedDots" class="animatedDots" ></span></p>
  </div>

  <div style="height:100%;width:100%;">
    <div id="rpsWrapper">
      <button type="button" class="rps" id="rock">✊</button>
      <button type="button" class="rps" id="paper">🖐</button>
      <button type="button" class="rps" id="scissors">✌</button>
    </div>
  </div>

  <div class="chatSetting" style="margin-right:0.3em"><!--temp, might not be necessary to fix text input--->
    <button type="button" class="collapsible" id="chatButton" style="max-width:none;width:100%;">Chat</button>
      <div class ="chat">
        <div class="chatBox">
        </div>
      </div>
    <input type="text" id="chatInput" name="chatInput" placeholder="Message" class="chatInput" style="">
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
				insertMessage($("#chatInput").val(), <?php echo json_encode($_SESSION["user_id"]);?>, <?php echo json_encode($_SESSION["game_id"]);?>);
			}
	})

  $(".chatBox").load("../ajax/ajax_displaymessage.php",
    {
      game_id:'='+'<?php echo json_encode($_SESSION["game_id"]);?>'
    });
	setInterval(function()
  {
    $(".chatBox").load("../ajax/ajax_displaymessage.php",
    {
      game_id:'='+'<?php echo json_encode($_SESSION["game_id"]);?>'
    });
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

  const wrapper = document.getElementById('rpsWrapper');
  wrapper.addEventListener('click', (event) => {
    const isButton = event.target.nodeName === 'BUTTON';
    if (!isButton) {
      return;
    }

    setMove(<?php echo json_encode($_SESSION["user_id"]);?>, <?php echo json_encode($_SESSION["game_id"]);?>, event.target.id);
  })
});


</script>

<script src="../javascript/function.js"></script>
<script src="../javascript/onlinestatus.js"></script>
<script src="../javascript/chat.js"></script>
<script src="../javascript/rock_paper_scissors.js"></script>