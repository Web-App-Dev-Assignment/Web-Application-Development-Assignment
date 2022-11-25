<?php
  include_once __DIR__ . "\\..\\php\\functions.php";

  //$projectFolderName = explode('/', $_SERVER['PHP_SELF'])[1];
  //echo $projectFolderName;
  //echo getcwd();

  session_start();

  try
  {
    if (isset($_SESSION["user_id"]))
    {
      include_once __DIR__ . "\\..\\php\\database.php";
        
        $sql = "SELECT * FROM $tbname
        WHERE id = '{$_SESSION["user_id"]}'";
    
        $result = $db_conn->query($sql);
    
        $user = $result->fetch_assoc();
    }
  }
  catch(Throwable $e)
  {
    debug_to_console(test_escape_char($e), 0);
    return;
  }


  //print_r($_SESSION);
  //debug_to_console(, 0);

?>

<!DOCTYPE HTML>  
<html>
<head>
  <title>Home</title>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
  <link rel="stylesheet" href="../css/stylesheet.css">
<style>
</style>
</head>
<body>
    <h1>Home</h1>

    <?php if (isset($user)): ?>
      <p>Hello
      <?php if (!empty($user["name"])): ?>
        <?= htmlspecialchars($user["name"])?>
      <?php elseif (!empty($user["username"])): ?>
        <?= htmlspecialchars($user["username"])?>
      <?php endif; ?>
       </p><br>
       <button onclick="document.location='lobby.php'">Lobby</button><br>
       <button onclick="document.location='logout.php'">Log out</button><br>
      <!---<p><a href="logout.php">Log out</a></p>--->
    <?php else: ?>
      <button onclick="document.location='login.php'">Log in</button><br>
      <button onclick="document.location='signup.php'">Signup</button><br>
            <!---<p><a href="login.php">Log in</a> or <a href="signup.php">sign up</a></p>--->
    <?php endif; ?>

</body>
</html>