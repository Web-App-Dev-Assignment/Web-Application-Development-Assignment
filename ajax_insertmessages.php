<?php
$db_conn = include_once __DIR__ . "/database.php";

	if(isset($_POST['chat_text']))
  {
		insertChatMessage($_POST['chat_text'], $_POST["user_id"], NULL);
	}
?>