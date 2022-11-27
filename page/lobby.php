<?php
  include_once __DIR__ . "\\..\\php\\function.php";

  session_start();

  if (isset($_SESSION["user_id"]))
  {
    include_once __DIR__ . "\\..\\php\\matchmaking.php";  
    include_once __DIR__ . "\\..\\php\\game.php";

    //$result = isInTable("game", $_SESSION["user_id"]);//check if player is in game
    redirectGameSession($_SESSION["user_id"]);//redirect the player to game page if they are in a game session
    //$_SESSION['matchmaking'];
    //if matchmaking then resume/cancel?
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
  <title>Lobby</title>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
  <link rel="stylesheet" href="../css/stylesheet.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<style>
</style>
</head>
<body style="max-width: none;">
  <div class="darkLayer" style="display:none">
    <div class="loader center" style="margin-top:10%;"></div>
    <button type="button" id="cancelMatchmakingButton" class="center" style="margin-top: 10%;">Cancel Matchmaking</button>
  </div>
  <div style="height:100%;width:100%;">
    <div class="leftColumn" style="background-color:#aaa;">
      <h2>Column 1</h2>
      <p>Some text..</p>
    </div>
    <div class="middleColumn" style="background-color:#bbb;">
      <h2>Column 2</h2>
      <p>Some text..</p>
      <button type="button" id="matchmakingButton" class="center" style="margin-top: 20%;">Matchmaking</button>
    </div>
    <div class="rightColumn">
      <form method="post" submit="false">
      <label for="cars">Choose a game:</label>
      <select name="game_type" id="game_type">
        <optgroup label="Game Type">
          <option value="rock_paper_scissors">Rock Paper Scissors</option>
          <option value="tick_tack_toe">Tick Tack Toe</option>
        </optgroup>
      </select>
      </form><br>
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
  updateLastOnline();
  setInterval(function()
  {
    updateLastOnline();
    //matchMaking();//need to change to only match make if the player pressed matchmaking button
  }, 5000);

 function updateLastOnline()
 {
    $.ajax(
    {
      type:'post',
      url:"../ajax/ajax_updateLastOnline.php",
      data:{
            user_id:<?php echo json_encode($_SESSION["user_id"]);?>
          },
      success:function()
      {
        //if offline, cancel matchmaking code here
      }
    })
 }
 
});  
</script>

<script>
  $(document).ready(function()
  {
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

    $("#matchmakingButton").on('click', function()
    { 
      if($('#game_type').val())
      {
        $("#matchmakingButton").hide();
        $('.darkLayer').attr('style', '');

        startMatchMaking(<?php echo json_encode($_SESSION["user_id"]);?>, $('#game_type').val());
      }
      else
      {
        $("#matchmakingErr").text("*Please choose a game type to start matchmaking.");
      }
    })
    $("#cancelMatchmakingButton").on('click', function()
    {
      $("#matchmakingButton").show();
      $('.darkLayer').attr('style', 'display: none');

      cancelMatchMaking(<?php echo json_encode($_SESSION["user_id"]);?>);
    })
  });
</script>

<script>
$(document).ready(function() 
{
	$("#chatInput").keyup(function(e)
  {
			if(e.keyCode == 13)//the enter key
      {
				insertMessage($("#chatInput").val(), <?php echo json_encode($_SESSION["user_id"]);?>, '');
			}
	})
	
	setInterval(function()
  {
    //$(".chatBox").html(displayMessage("IS NULL"));
    $(".chatBox").load("../ajax/ajax_displaymessage.php",
    {
      game_id:"IS NULL"
    }
    );
	},1500)
	
	$(".chatBox").load("../ajax/ajax_displaymessage.php",
    {
      game_id:"IS NULL"
    }
    );
	
});

</script>

<script src="../javascript/function.js"></script>
<script src="../javascript/chat.js"></script>
<script src="../javascript/matchmaking.js"></script>