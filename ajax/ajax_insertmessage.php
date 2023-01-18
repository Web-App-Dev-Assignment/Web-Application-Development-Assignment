<?php
include_once __DIR__ . "\\..\\php\\chat.php";

	if(isset($_POST['chat_text']))
  {
		insertChatMessage($_POST['chat_text'], $_POST['user_id'], $_POST['game_id']);
	}
?>