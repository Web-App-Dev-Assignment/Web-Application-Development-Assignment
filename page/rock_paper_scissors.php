<?php
  include_once __DIR__ . "\\..\\php\\function.php";

  session_start();

  if (isset($_SESSION["user_id"]) && isset($_SESSION['game_id']))
  //if(isset($_SESSION["user_id"]))
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
    <div id="rpsWrapper">
      <button type="button" class="rps" id="rock">✊</button>
      <button type="button" class="rps" id="paper">🖐</button>
      <button type="button" class="rps" id="scissors">✌</button>
    </div>
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
				insertMessage($("#chatInput").val(), <?php //echo json_encode($_SESSION["user_id"]);?>, $_SESSION["game_id"]);
        //insertMessage($("#chatInput").val(), <?php echo json_encode($_SESSION["user_id"]);?>, '');
			}
	})

  $(".chatBox").load("../ajax/ajax_displaymessage.php",
    {
      game_id:'='.$_SESSION["game_id"]
      //game_id:"IS NULL"
    });
	setInterval(function()
  {
    $(".chatBox").load("../ajax/ajax_displaymessage.php",
    {
      game_id:'='.$_SESSION["game_id"]
      //game_id:"IS NULL"
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

    setMove($_SESSION["user_id"], $_SESSION["game_id"], event.target.id);
    //console.dir(event.target.id);
  })

});


</script>

<script src="../javascript/function.js"></script>
<script src="../javascript/onlinestatus.js"></script>
<script src="../javascript/chat.js"></script>
<script src="../javascript/rock_paper_scissors.js"></script>