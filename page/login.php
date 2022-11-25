<?php
  session_start();

  if (isset($_SESSION["user_id"]))
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
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <link rel="stylesheet" href="../css/stylesheet.css">
<style>
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
    </form><br>
      <button onclick="document.location='index.php'">Back</button><br>

</body>
</html>

<script>
  $(document).ready(function()
  {
    $("#login").on('click', function()
    {
      $.ajax
      ({
        type:'post',
        url:'../ajax/ajax_login.php',
        data:
        {
          username:$("#username").val(),
          password:$("#password").val()
        },
        //dataType:'json',
        success:function(response)
        {
          jason = $.parseJSON(response);
          console.log(response);
          console.log(jason.name);
          if(!jason.errormessage)
          {
            $("#err").text("*Login success.");
            window.location.href="index.php";
          }
          else
          {
            $("#err").text("*"+jason.errormessage);
          }
        }
      });
    })
  })
</script>