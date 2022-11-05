<?php
include_once __DIR__ . "/functions.php";

$is_invalid = false;
try
{
  if ($_SERVER["REQUEST_METHOD"] === "POST")
  {
    $db_conn = require_once __DIR__ . "/database.php";
  
    $sql = sprintf("SELECT * FROM user 
    WHERE username = '%s'",
    $db_conn->real_escape_string($_POST["username"]));
  
    $result = $db_conn->query($sql);
  
    $user = $result->fetch_assoc();
  
    // var_dump($user);
    // exit;
  
    if($user)
    {
      if(password_verify($_POST["password"], $user["password_hash"]))
      {
        debug_to_console("Login successful.", 0);
        session_start();
        session_regenerate_id();
  
        $_SESSION["user_id"] = $user["id"];
  
        header("Location: index.php");
        exit;
      }
      else
      {
        debug_to_console("Login unsuccessful.", 1);
      }
    }
  
    $is_invalid = true;
  }
}
catch(Throwable $e)
{
  debug_to_console(test_escape_char($e), 0);
}


?>

<!DOCTYPE HTML>  
<html>
<head>
  <title>Login</title>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
<style>
.error {color: #FF0000;}
</style>
</head>
<body>
    <h1>Login</h1>

    <?php if ($is_invalid): ?>
      <em>Invalid login</em>
    <?php endif; ?>

    <form method="post">
      <label for="username">username</label>
      <input type="username" name="username" id="username value="<?= htmlspecialchars($_POST["username"] ?? "") ?>">

      <label for="password">password</label>
      <input type="password" name="password" id="password" value="<?= htmlspecialchars($_POST["password"] ?? "") ?>">

      <button>Log in</button>
    </form>
</body>
</html>
