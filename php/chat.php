<?php
require_once __DIR__ . "/database.php";
require_once __DIR__ . "/onlinestatus.php";

function createChatTable()
{
  global $db_conn, $tbname,$game_tb, $chat_tb;
  try
  {
    $sql_table = "CREATE TABLE $chat_tb
    (
      chat_id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
      chat_text VARCHAR(255) NOT NULL,
      chat_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      id VARCHAR(128) NOT NULL,
      game_id VARCHAR(128),
      FOREIGN KEY(id) REFERENCES $tbname(id),
      FOREIGN KEY(game_id) REFERENCES $game_tb(game_id)
    )";
    $db_conn->query($sql_table);
    //debug_to_console("Table $chat_tb not found. Table $chat_tb created.",1);
  }
  catch(Throwable $e)
  {
    //debug_to_console(test_escape_char($db_conn->error) . "\\nError Code : " . $db_conn->errno ,1);
    // $output = array("errormessage"=>$e);
    // echo json_encode($output);
    if ($db_conn->errno === 1050)//1050 duplicate table
    {
      //debug_to_console("Table $chat_tb already exists. \\nError:\\n" . test_escape_char($e),1);
    }
    else
    {
      //debug_to_console("Opps, something went wrong. \\nError:\\n" . test_escape_char($e),2);
    }
  }
}

function insertChatMessage($chat_text, $user_id, $game_id)
{
  global $db_conn, $chat_tb;

  $sql = "INSERT INTO $chat_tb (chat_text, id, game_id) 
  VALUES (?, NULLIF(?,''), NULLIF(?,''))";
  try
  {
    $stmt = $db_conn->prepare($sql);

    $stmt->bind_param("sss", $chat_text, $user_id, $game_id);
    $stmt->execute();
  }
  catch(Throwable $e)
  {
    echo $e;
  }
  
}

function displayMessage($game_id)//mabye write a function to differentiate you and other players?
{
  global $db_conn, $tbname, $chat_tb;
  try
  {
    $sql = sprintf("SELECT id, chat_text FROM $chat_tb
    WHERE game_id %s
    ORDER BY chat_date DESC
    LIMIT 15", $game_id);
    
    $stmt = $db_conn->stmt_init();
    $stmt->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    $chats = $result->fetch_all(MYSQLI_ASSOC);

    if ($chats)
    {
      $output = "";
      foreach ($chats as $chat) 
      {
        $sql = "SELECT `name`, username FROM $tbname
        WHERE id = '{$chat['id']}'";//'{$_SESSION["user_id"]}'
        $result = $db_conn->query($sql);
        $user = $result->fetch_assoc();
        if ($user)
        {
          $output .= '<span class=name>';
          $status = checkOnlineStatus($tbname, $chat['id']);
          if ($user['name'])
          {
            $output .= $user['name'];
          }
          else
          {
            $output .= $user['username'];
          }
          //$output .= ' says: ';
          $output .= '</span>';
          $output .= ' ';
          if ($status === "offline")
          {
            $output .= '<span class=offlineStatus>';
          }
          else
          {
            $output .= '<span class=onlineStatus>';
          }
          $output .= '(' . $status . ')';
          $output .= '<br>';
          $output .= '</span>';
          $output .= '<span class=chatText>';
          $output .= $chat['chat_text'];
          $output .= '</span>';
          $output .= '<br><hr>';
          //echo $user['username'];
          //echo $chat['chat_text'];

          //$output = '<span class=name>' .'noname'. ' says: ' .'</spawn>';

          //echo $output;
        }
      }
      //echo "<span style=\"color:blue\">testing123</span>";
      //echo '<span class=name >testing123</span>';
      
      return $output;
    }
  }
  catch(Throwable $e)
  {
    echo $db_conn->errno;
    echo $e;
    if($db_conn->errno === 1146)//1146 Table doesn't exist
      {
        createChatTable();
      }
  }
  
}
?>