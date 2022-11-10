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
<style>
.error {color: #FF0000;}
</style>
</head>
<body>
    <h1>Login</h1>

    <form method="post" submit="false">
      <label for="username">Username</label>
      <input type="username" name="username" id="username" value="<?= htmlspecialchars($_POST["username"] ?? "") ?>" placeholder="Enter your username.">

      <label for="password">Password</label>
      <input type="password" name="password" id="password" value="<?= htmlspecialchars($_POST["password"] ?? "") ?>" placeholder="Enter your password.">
      <span class="error" id="err"></span><br><br>

      <button type="button" id="login">Login</button>
    </form>
<!--
    <form method="post" submit="false">
      <label for="username">Username</label>
      <input type="username" name="username" id="username" value="<?= htmlspecialchars($_POST["username"] ?? "") ?>" placeholder="Enter your username.">

      <label for="password">Password</label>
      <input type="password" name="password" id="password" value="<?= htmlspecialchars($_POST["password"] ?? "") ?>" placeholder="Enter your password.">

      <button type="button" onclick="login()">Login</button>
    </form>
-->


</body>
</html>

<script>
  $(document).ready(function()
  {
    $("#login").on('click', function()
    {
      //$("#err").text("testing");
      var username = $("#username").val();
      var password=$("#password").val();
      console.log(username + " , " + password);

      $.ajax
      ({
        type:'post',
        url:'do_login.php',
        data:
        {
          login:1,
          username:username,
          password:password
        },
        success:function(response)
        {
          //console.log(response);
          
          //if(response=="success")
          if(response.indexOf('@0^/s&d~v~x2LiN?^k+ZJ[+Nk1QK+b') >= 0)
          {
            window.location.href="index.php";
            $("#err").text("*Login success.");
            //console.log("Correct Details");
          }
          else
          {
            $("#err").text("*Login failed.");
            //console.log("Wrong Details");
          }
          // console.log(response);
          // console.log(username);
        }//,
        //dataType: 'text'
      });
    })
  })
</script>

<script>
function loginfunction()
{
  var username=$("#username").val();
  var password=$("#password").val();

  $.ajax
  ({
  type:'post',
  url:'do_login.php',
  data:{
   username:username,
   password:password
  },
  success:function(response) {
  //if(response=="success")
  if(response.indexOf('success') >= 0)
  {
    //window.location.href="index.php";
    alert("Correct Details");
  }
  else
  {
    alert("Wrong Details");
  }
  console.log(response);
  console.log(username);
  }
   });
}
</script>