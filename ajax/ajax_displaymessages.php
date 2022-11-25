<?php
include_once __DIR__ . "\\..\\php\\chat.php";

//displayMessage($_SESSION['game_id']);
$messages = displayMessage("IS NULL");
echo "$messages";
?>