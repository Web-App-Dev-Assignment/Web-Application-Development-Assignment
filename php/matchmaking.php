<?php
require_once __DIR__ . "/database.php";
require_once __DIR__ . "/game.php";

function createMatchmakingTable()
{
  global $tbname, $db_conn, $matchmaking_tb, $game_tb;

  try
  {
    $sql_table = "CREATE TABLE $matchmaking_tb
    (
      `index` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
      game_type ENUM('chess','rock_paper_scissors', 'tick_tack_toe'),
      id VARCHAR(128) NOT NULL UNIQUE,
      game_id VARCHAR(128) NOT NULL,
      FOREIGN KEY(id) REFERENCES $tbname(id)
    )";
    $db_conn->query($sql_table);
  }
  catch(Throwable $e)
  {
    echo $e;
  }
}

function startMatchMaking($user_id, $game_type)//should call it when player presses the matchmaking button
{
  global $db_conn, $matchmaking_tb, $game_tb;
  //--------------------------Check if matchmaking--------------------------
  if(isInTable($matchmaking_tb, $user_id))//is currently matchmaking; don't do anything
  {
    $errMsg = "Already matchmaking.";
    return $errMsg;
  }
  //--------------------------End of check if matchmaking--------------------------
  //--------------------------Check if in game--------------------------
  else if(isInTable($game_tb, $user_id))//is currently ingame
  {
    $errMsg = "Already in game.";
    return $errMsg;
  }
  //--------------------------End of check if in game--------------------------
  
  
  $sql = "INSERT INTO $matchmaking_tb (id, game_type) 
  VALUES (?,?)";
  try
  {
    $stmt = $db_conn->prepare($sql);
  }
  catch(Throwable $e)
  {
    if($db_conn->errno === 1146)//1146 Table doesn't exist
    {
      createMatchmakingTable();
      $stmt = $db_conn->prepare($sql);
    }
    return;
  }
  
  $stmt->bind_param("ss", $user_id, $game_type);
  $stmt->execute();
}

function cancelMatchMaking($user_id)//should call it when the player presses the cancel matchmaking button or is offline
{
  global $db_conn, $matchmaking_tb;

  $sql = sprintf("DELETE FROM $matchmaking_tb
  WHERE id = '%s'", $user_id);

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

function matchMaking($user_id, $game_type)//check if we are in game or if someone else is looking to play the same game
{
  global $db_conn, $matchmaking_tb, $game_tb;
  try
  {
    if(isInTable($game_tb, $user_id))//is currently ingame, redirect user to the game
    {
      //To redirect player to multiplayer game
      $sql = sprintf("SELECT game_id FROM %s
      WHERE id = '%s'
      LIMIT 1", $game_type, $user_id);

      $stmt = $db_conn->stmt_init();
      $stmt->prepare($sql);
      $stmt->execute();
      $result = $stmt->get_result();
      $user = $result->fetch_assoc();

      if($user)
      {
        $msg = "Opponent found.";
        return $msg;
      }
    }
    
    //--------------------------Matchmaking--------------------------
    //look for someone else queueing for the same game_type
    $sql = sprintf("SELECT id FROM $matchmaking_tb
    WHERE id != '%s' AND game_type = '%s'
    LIMIT 1", $user_id, $game_type);

    $stmt = $db_conn->stmt_init();
    $stmt->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if($user)//there is player matchmaking
    {
      
      //$user["id"]//the other user
      //$_SESSION["user_id"]//the current user
      //remove you and the other player from matchmaking
      $sql = sprintf("DELETE FROM $matchmaking_tb
      WHERE id IN ('%s','{$user["id"]}')", $user_id);
      $stmt = $db_conn->stmt_init();
      $stmt->prepare($sql);
      $stmt->execute();

      //insert our record into the game type
      $game_id = md5(uniqid());
      $players = array("player1","player2");
      shuffle($players);
      $sql = "INSERT INTO $game_type (game_id, player, id) 
      VALUES (?, ?, ?)";
      try
      {
        $stmt = $db_conn->prepare($sql);
      }
      catch(Throwable $e)
      {
        echo $e;
        if($db_conn->errno === 1146)//1146 Table doesn't exist
        {
          createGameType($game_type);
          $stmt = $db_conn->prepare($sql);
        }
      }
      
      $id = $user_id;
      $player = array_pop($players);
      $stmt->bind_param("sss", $game_id, $player, $id);
      $stmt->execute();

      $id = $user["id"];
      $player = array_pop($players);
      $stmt->execute();


      //insert our record into the game table
      $sql = "INSERT INTO $game_tb (game_id, game_type, id) 
      VALUES (?, ?, ?)";
      $stmt = $db_conn->prepare($sql);

      $stmt->bind_param("sss", $game_id, $game_type, $id);
      $stmt->execute();

      $id = $user_id;
      $stmt->execute();
    }
    //--------------------------End of matchmaking--------------------------
  }
  catch(Throwable $e)
  {
    echo $e;
  }
}

function isInTable($table_name, $user_id)
{
  global $db_conn, $game_tb, $tbname, $matchmaking_tb;
  try
  {
    $sql = sprintf("SELECT id FROM %s
    WHERE id = '%s'", $table_name, $user_id);
    $stmt = $db_conn->stmt_init();
    
    try
    {
      $stmt->prepare($sql);
    }
    catch(Throwable $e)
    {
      if($db_conn->errno === 1146 && $table_name === $game_tb)//1146 Table doesn't exist
      {
        createGameTable();
        $stmt->prepare($sql);
      }
      else if($db_conn->errno === 1146 && $table_name === $matchmaking_tb)//1146 Table doesn't exist
      {
        createMatchmakingTable();
        $stmt->prepare($sql);
      }
      return;//do not remove this, it helps to initialize the $result outside the try catch block
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    if($user)
    {
      return true;
    }
    else
    {
      return false;
    }
  }
  catch(Throwable $e)
  {
    echo $e;
  }
  
}
?>