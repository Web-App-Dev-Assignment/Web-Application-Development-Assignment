<?php
include_once __DIR__ . "\\..\\php\\chat.php";

//displayMessage($_SESSION['game_id']);
//echo($_POST['game_id']);
//$messages = displayMessage($_POST['game_id']);
$messages = displayMessage("IS NULL");
echo "$messages";
?>