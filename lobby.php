<?php

?>

<!DOCTYPE HTML>  
<html>
<head>
  <title>Login</title>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<style>
.error {color: #FF0000;}
</style>
</head>
<body>
    <h1>Login</h1>

    <form method="post" submit="false">
      <label for="username">Username/Email</label>
      <input type="username" name="username" id="username" value="" placeholder="Enter username/email.">

      <label for="password">Password</label>
      <input type="password" name="password" id="password" value="" placeholder="Enter password.">
      <span class="error" id="err"></span><br><br>

      <button type="button" id="login">Login</button>
    </form>

</body>
</html>

<script>  
$(document).ready(function()
{

  setInterval(function()
  {
    updateLastOnline();
    matchMaking();
  }, 5000);

 function updateLastOnline()
 {
    $.ajax(
    {
      url:"ajax_updateLastOnline.php",
      success:function()
      {
        //if offline, cancel matchmaking code here
      }
    })
 }

 function matchMaking()
  {
    $.ajax(
    {
      url:"ajax_matchmaking.php",
      method:"POST",
      success:function(data)
      {
        jason = $.parseJSON(response);
        if(jason.errormessage)
        {
          window.location.href="multiplayer.php";
        }
      }
    })
  }
 
});  
</script>