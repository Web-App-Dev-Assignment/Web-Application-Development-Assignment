<script>
function login()
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
    window.location.href="index.php";
    alert("Correct Details");
    console.log(response);
  }
  else
  {
    alert("Wrong Details");
    console.log(response);
  }
  }
   });
}
</script>



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

    

    <form method="post">
      <label for="username">Username</label>
      <input type="username" name="username" id="username" value="<?= htmlspecialchars($_POST["username"] ?? "") ?>" placeholder="Enter your username.">

      <label for="password">Password</label>
      <input type="password" name="password" id="password" value="<?= htmlspecialchars($_POST["password"] ?? "") ?>" placeholder="Enter your password.">

      <button type="button" onclick="login()">Login</button>
    </form>
</body>
</html>
