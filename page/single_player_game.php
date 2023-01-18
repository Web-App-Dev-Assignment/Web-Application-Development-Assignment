<?php
  include_once __DIR__ . "\\..\\php\\function.php";

  session_start();

  if (!isset($_SESSION["user_id"]))
  {
    header("Location: index.php");
    exit();
  }
?>

<!DOCTYPE HTML>  
<html>
<head>
  <title>Single player game</title>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
  <link rel="stylesheet" href="../css/stylesheet.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<style>
</style>
</head>
<body style="max-width: none;">
  <div style="height:100%;width:100%;">
    <div class="leftColumn">
    <span class="clickable symbol" onclick="document.location='index.php'" style=" font-size:xx-large;">&#xE91E;</span>
    </div>
    <div class="middleColumn">
      <button type="button" id="startGameButton" class="center" style="margin-top: 20%;">Start Game</button>
    </div>
    <div class="rightColumn">
      <form method="post" submit="false">
      <label for="cars">Choose a game:</label>
      <select name="game_type" id="game_type">
        <optgroup label="Game Type">
          <option value="ShootingGame">Shooting Game</option>
        </optgroup>
      </select>
      </form><br>
    </div>
  </div>

</body>
</html>

<script>  
$(document).ready(function()
{
  $("#startGameButton").on('click', function()
    { 
      if($('#game_type').val())
      {
        document.location= $('#game_type').val() + '.php';
      }
      else
      {
        $("#matchmakingErr").text("*Please choose a game type to start matchmaking.");
      }
    })
});  
</script>

<script src="../javascript/function.js"></script>