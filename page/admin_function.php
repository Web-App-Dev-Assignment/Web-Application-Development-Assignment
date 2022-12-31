<?php
session_start();
if(!isset($_SESSION["user_id"]) && $_SESSION["role"] !== "admin")
{
  header("Location: index.php");
}

?>

<!DOCTYPE HTML>  
<html>
<head>
  <title>Admin Function</title>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
  <link rel="stylesheet" href="../css/stylesheet.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<style>
</style>
</head>
<body style="max-width: none;">

  <span class="clickable symbol" onclick="document.location='index.php'" style=" font-size:xx-large;">&#xE91E;</span>

<?php
  include_once __DIR__ . "\\..\\php\\action.php";

  generateButtons();
?>

<script src="../javascript/action.js"></script>

</body>
</html>