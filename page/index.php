<?php
  include_once __DIR__ . "\\..\\php\\function.php";

  session_start();

  try
  {
    if (isset($_SESSION["user_id"]))
    {
      include_once __DIR__ . "\\..\\php\\database.php";
      
        $sql = "SELECT `name`, username, `role` FROM $tbname
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

  <?php if (isset($user)): ?>
    <?php if($_SESSION["role"]==="admin"):?>
      <span style="font-family:symbols;font-size:xxx-large;">&#xE91F;</span>
    <?php endif; ?>
  <?php endif; ?>
  
  <h1>Home</h1>

  <?php if (isset($user)): ?>
    <p>Hello
    <?php if (!empty($user["name"])): ?>
      <?= htmlspecialchars($user["name"])?>
    <?php elseif (!empty($user["username"])): ?>
      <?= htmlspecialchars($user["username"])?>
    <?php endif; ?>
      </p><br>
      <div class="buttonContainer">
        <button class="buttonWrapper" id="lobbyButton" onclick="document.location='lobby.php'"><span id="lobbySpan" style="font-family:symbols;">&#xE91A;</span>Lobby</button><br>
        <button class="buttonWrapper" onclick="document.location='single_player_game.php'"><span id="lobbySpan" style="font-family:symbols;">&#xE920;</span>Single Player Game</button><br>
        <?php if($_SESSION["role"]==="admin"):?>
          <button class="buttonWrapper" onclick="document.location='admin_function.php'"><span style="font-family:symbols;">&#xE914;</span>Admin</button><br>
        <?php endif; ?>
        <button class="buttonWrapper" onclick="document.location='../php/logout.php'"><span style="font-family:symbols;">&#xE917;</span>Log out</button><br>
      </div>
  <?php else: ?>
    <div class="buttonContainer">
      <button class="buttonWrapper" onclick="document.location='login.php'"><span style="font-family:symbols;">&#xE916;</span>Log in</button><br>
      <button class="buttonWrapper" onclick="document.location='signup.php'"><span style="font-family:symbols;">&#xE913;</span>Sign up</button><br>
    </div>
  <?php endif; ?>

</body>
</html>