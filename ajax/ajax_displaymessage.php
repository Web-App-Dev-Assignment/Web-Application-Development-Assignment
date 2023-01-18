<?php
include_once __DIR__ . "\\..\\php\\chat.php";

$messages = displayMessage($_POST['game_id']);
echo "$messages";
?>