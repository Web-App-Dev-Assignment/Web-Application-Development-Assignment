<!DOCTYPE HTML>  
<html>
<head>
  <title>Login</title>
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