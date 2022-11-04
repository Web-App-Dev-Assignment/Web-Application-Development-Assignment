<?php
include_once __DIR__ . "/functions.php";

if (isset($SESSION["user_id"]))
{
    $db_conn = include_once __DIR__ . "/database.php";

    $sql = "SELECT * FROM user
    WHERE id = {$SESSION["user_id"]}";

    $result = $db_conn->query($sql);

    $user = $result->fetch_assoc();
}

session_start();

//print_r($_SESSION);
debug_to_console($_SESSION, 0);

?>

<!DOCTYPE HTML>  
<html>
<head>
  <title>Home</title>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
<style>
.error {color: #FF0000;}
</style>
</head>
<body>
    <h1>Home</h1>

    <?php if (isset($user)): ?>

      <p>Hello <?= htmlspecialchars($user["name"])?></p>
      <p><a href="logout.php">Log out</a></p>
    <?php else: ?>
            <p><a href="login.php">Log in</a> or <a href="signup.html">sign up</a></p>
    <?php endif; ?>

</body>
</html>