<?php
require_once __DIR__ . "/database.php";

function createGameType($game_type)
{
  global $db_conn, $tbname, $game_tb;
  if ($game_type === 'rock_paper_scissors')
  {
    $sql = sprintf("CREATE TABLE %s
    (
      `index` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
      health TINYINT UNSIGNED NOT NULL DEFAULT 3,
      `move` ENUM('rock','paper','scissors') DEFAULT NULL,
      `is_checked` BIT(1) DEFAULT 0,
      player ENUM('player1','player2'),
      game_id VARCHAR(128) NOT NULL,
      id VARCHAR(128) NOT NULL UNIQUE,
      FOREIGN KEY(id) REFERENCES $tbname(id)
    )"
    ,$game_type);
  }
  else if ($game_type === 'chess')
  {
    $sql = "";
  }
  else
  {
    $msg = "Game type doesn't exist.";
    return $msg;
  }
  try
  {
    $stmt = $db_conn->prepare($sql);
  }
  catch(Throwable $e)
  {
    echo $e;
    return;
  }
  $stmt->execute();
}

function createGameTable()
{
  global $db_conn, $tbname, $game_tb;

  try
  {
    $sql_table = "CREATE TABLE $game_tb
    (
      `index` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
      game_id VARCHAR(128) NOT NULL,
      game_type ENUM('chess','rock_paper_scissors', 'tick_tack_toe'),
      id VARCHAR(128) NOT NULL UNIQUE,
      FOREIGN KEY(id) REFERENCES $tbname(id)
    )";
    $stmt = $db_conn->prepare($sql_table);
    $stmt->execute();
    //debug_to_console("Table $game_db not found. Table $game_db created.",1);
  }
  catch(Throwable $e)
  {
    echo $e;
    //debug_to_console(test_escape_char($db_conn->error) . "\\nError Code : " . $db_conn->errno ,1);
    // $output = array("errormessage"=>$e);
    // echo json_encode($output);
    if ($db_conn->errno === 1050)//1050 duplicate table
    {
      //debug_to_console("Table $game_db already exists. \\nError:\\n" . test_escape_char($e),1);
    }
    else
    {
      //debug_to_console("Opps, something went wrong. \\nError:\\n" . test_escape_char($e),2);
    }
  }
}

function insertGameSession($game_tb, $game_type, $user_id)
{
  global $db_conn;
  try
  {
    $sql = sprintf("INSERT INTO %s (game_id, game_type, id)
    VALUES (?,?,?)", $game_tb);
    $stmt = $db_conn->prepare($sql);
    $stmt->bind_param("sss", $game_id, $game_type , $user_id);
    $stmt->execute();
    
    $sql = sprintf("INSERT INTO %s (game_id, id)
    VALUES (?,?)", $game_type);
    $stmt = $db_conn->prepare($sql);
    $stmt->bind_param("ss", $game_id, $user_id);
    $stmt->execute();

  }
  catch(Throwable $e)
  {
    echo $e;
    if($db_conn->errno === 1146)//1146 Table doesn't exist
      {
        //createGameTable();
        //createGameType($game_type);
      }
  }
}

function deleteUserGameRecord($game_tb, $user_id)
{
  global $db_conn;

  try
  {
    $sql = sprintf("DELETE FROM %s
    WHERE id = '%s'", $game_tb, $user_id);
    $stmt = $db_conn->prepare($sql);
    $stmt->execute();
  }
  catch(Throwable $e)
  {
    echo $e;
  }
}

function deleteGameType($game_type, $game_id)
{
  global $db_conn;

  try
  {
    $sql = sprintf("DELETE FROM %s
    WHERE game_id = '%s'", $game_type, $game_id);
    $stmt = $db_conn->prepare($sql);
    $stmt->execute();
  }
  catch(Throwable $e)
  {
    echo $e;
  }
}

function deleteGameSession($user_id)//delete the data from game and game type
{
  global $db_conn, $game_tb;
  try
  {
    $game_type = getGameType($user_id);
    
    $sql = sprintf('DELETE %1$s, %2$s 
    FROM %1$s 
    INNER JOIN %2$s 
    ON %1$s.id = %2$s.id
    WHERE %1$s.id= "%3$s"',$game_tb, $game_type, $user_id);

    // DELETE game_table, rock_paper_scissors FROM game_table INNER JOIN rock_paper_scissors ON game_table.id = rock_paper_scissors.id
    // WHERE game_table.id= 'ec70fe89f59fa30755df7e41adac5be9'

    // $sql = sprintf("DELETE FROM %s INNER JOIN %s
    // WHERE id= '%s' ", $game_tb, $game_type, $user_id);

    $stmt = $db_conn->prepare($sql);
    $stmt->execute();
  }
  catch(Throwable $e)
  {
    echo $e;
  }
}

function deleteGameSessions($game_id)//delete the data from game and game type
{
  global $db_conn, $game_tb;
  try
  {
    $game_type = getGameTypes($game_id);
    
    $sql = sprintf('DELETE %1$s, %2$s 
    FROM %1$s 
    INNER JOIN %2$s 
    ON %1$s.id = %2$s.id
    WHERE %1$s.game_id= "%3$s"',$game_tb, $game_type, $game_id);
    $stmt = $db_conn->prepare($sql);
    $stmt->execute();
  }
  catch(Throwable $e)
  {
    echo $e;
  }
}

function getGameType($user_id)
{
  global $db_conn, $game_tb;

  $sql = sprintf("SELECT game_type FROM $game_tb
  WHERE id = '%s'
  LIMIT 1", $user_id);
  try
  {
    $stmt = $db_conn->prepare($sql);
  }
  catch(Throwable $e)
  {
    echo $e;
    return;
  }
  
  $stmt->execute();
  $result = $stmt->get_result();
  $user = $result->fetch_assoc();
  if (isset($user))
  {
    return $user['game_type'];
  }
}

function getGameTypes($game_id)
{
  global $db_conn, $game_tb;

  $sql = sprintf("SELECT game_type FROM $game_tb
  WHERE game_id = '%s'
  LIMIT 1", $game_id);
  try
  {
    $stmt = $db_conn->prepare($sql);
  }
  catch(Throwable $e)
  {
    echo $e;
    return;
  }
  
  $stmt->execute();
  $result = $stmt->get_result();
  $game = $result->fetch_assoc();
  if (isset($game))
  {
    return $game['game_type'];
  }
}

function getGameId($user_id)
{
  global $db_conn, $game_tb;

  $sql = sprintf("SELECT game_id FROM $game_tb
  WHERE id = '%s'
  LIMIT 1", $user_id);
  try
  {
    $stmt = $db_conn->prepare($sql);
  }
  catch(Throwable $e)
  {
    //echo $e;
    return;
  }
  
  $stmt->execute();
  $result = $stmt->get_result();
  $user = $result->fetch_assoc();
  if (isset($user))
  {
    return $user['game_id'];
  }
}

function redirectGameSession($user_id)
{
  $result = getGameType($user_id);

  if (isset($result))
  {
    $_SESSION['game_id'] = getGameId($user_id);
    header('Location: '.$result.'.php');
    exit();
  }
}
?>