<?php
  $db_conn = include_once __DIR__ . "/database.php";
  include_once __DIR__ . "/functions.php";
  
  session_start();

  if (isset($_SESSION["user_id"]))
  {
    $result = isIngame($_SESSION["user_id"]);
    debug_to_console($_SESSION["user_id"],0) ;
    if ($result)
    {
      //header("Location: multiplayer.php");
      //exit();
    }
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
  <title>Login</title>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
  <link rel="stylesheet" href="stylesheet.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<style>
</style>
</head>
<body style="max-width: none;">
  <div class="darkLayer" style="display:none">
    <div class="loader center" style="margin-top:10%;"></div>
    <button type="button" id="cancelMatchmakingButton" class="center" style="margin-top: 10%;">Cancel Matchmaking</button>
  </div>
  <div>
    <div class="leftColumn" style="background-color:#aaa;">
      <h2>Column 1</h2>
      <p>Some text..</p>
    </div>
    <div class="middleColumn" style="background-color:#bbb;">
      <h2>Column 2</h2>
      <p>Some text..</p>
      <button type="button" id="matchmakingButton" class="center" style="margin-top: 20%;">Matchmaking</button>
    </div>
    <div class="rightColumn" style="background-color:#ccc;">
      <h2>Column 3</h2>
      <p>Some text..</p>
    </div>
  </div>
  <div style="margin-right:0.3em"><!--temp, might not be necessary to fix text input--->
    <div class ="chat">
      <button type="button" class="collapsible" style="max-width:none;width:100%;">Chat</button>
      <div class="chatBox">
      </div>
      <input type="text" id="chatInput" name="chatInput" style="width:90%;">
    </div>
  </div>
  

</body>
</html>

<script>  
$(document).ready(function()
{

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
      url:"ajax_updateLastOnline.php",
      data:{
            user_id:<?php echo json_encode($_SESSION["user_id"]);?>
          },
      success:function()
      {
        //if offline, cancel matchmaking code here
      }
    })
 }

 function matchMaking()
  {
    $.ajax
    ({
      type:'post',
      url:"ajax_matchmaking.php",
      data:{
            user_id:<?php echo json_encode($_SESSION["user_id"]);?>
          },
      success:function(data)
      {
        jason = $.parseJSON(response);
        if(jason.successmessage)
        {
          //window.location.href="multiplayer.php";
        }
      }
    })
  }
 
});  
</script>

<script>
  $(document).ready(function()
{
  collapsible("collapsible");
  $("#matchmakingButton").on('click', function()
  {
    $("#matchmakingButton").hide();
    $('.darkLayer').attr('style', '');
    //$("#cancelMatchmakingButton").show();
  })
  $("#cancelMatchmakingButton").on('click', function()
  {
    $("#matchmakingButton").show();
    $('.darkLayer').attr('style', 'display: none');
    //$("#cancelMatchmakingButton").hide();
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
				$.ajax
        ({
					type:'POST',
					url:'ajax_insertmessages.php',
					data:
          {
            chat_text:$("#chatInput").val(),
            user_id:<?php echo json_encode($_SESSION["user_id"]);?>
          },
					success:function()
          {
						$("#chatInput").val("");
					}
				})
			}
	})
	
	setInterval(function()
  {
			$(".chatBox").load("ajax_displaymessages.php");
	},1500)
	
	$(".chatBox").load("ajax_displaymessages.php");
	
});
</script>

<script src="functions.js"></script>