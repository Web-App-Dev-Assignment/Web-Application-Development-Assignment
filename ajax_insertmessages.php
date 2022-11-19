<?php
$db_conn = include_once __DIR__ . "/database.php";

	if(isset($_POST['chat_text']))
  {
		insertChatMessage($_POST['chat_text'], $_SESSION["user_id"], $_SESSION['game_id']);
	}
?>