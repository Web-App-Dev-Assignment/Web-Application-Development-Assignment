<?php
require_once __DIR__ . "/database.php";

function createMatchmakingTable()
{
  global $tbname, $db_conn, $matchmaking_tb;

  try
  {
    $sql_table = "CREATE TABLE $matchmaking_tb
    (
      `index` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
      game_type ENUM('rock_paper_scissors', 'tick_tack_toe')
      id VARCHAR(128) NOT NULL UNIQUE,
      FOREIGN KEY(id) REFERENCES $tbname(id)
    )";
    $db_conn->query($sql_table);
  }
  catch(Throwable $e)
  {
    if ($db_conn->errno === 1050)//1050 duplicate table
    {
    }
    else
    {
    }
  }
}

function startMatchMaking($user_id, $game_type)//should call it when player presses the matchmaking button
{
  global $db_conn, $matchmaking_tb, $game_tb;

  //$errMsg = "";
  //--------------------------Check if matchmaking--------------------------
  if(isInTable($matchmaking_tb, $user_id))//is currently matchmaking; don't do anything? just exit()
  {
    //write code to deal with already matchmaking here.//resume? cancel then restart the process?
    $errMsg = "Already matchmaking.";
    return $errMsg;
    // $output = array("errormessage"=>"Already matchmaking.");
    // exit(json_encode($output));
  }
  //--------------------------End of check if matchmaking--------------------------
  //--------------------------Check if in game--------------------------
  else if(isInTable($game_tb, $user_id))//is currently ingame
  {
    //write code to redirect player to multiplay game here
    $errMsg = "Already in game.";
    return $errMsg;
    // $output = array("errormessage"=>"Already in game.");
    // exit(json_encode($output));
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
    //echo $db_conn->errno;
    //echo $e;
    if($db_conn->errno === 1146)//1146 Table doesn't exist
    {
      createMatchmakingTable();
      $stmt = $db_conn->prepare($sql);
    }
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
    return $e;
    //echo $e;
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
      //write code to redirect player to multiplay game here
      $sql = sprintf("SELECT game_type FROM %s
      WHERE id = '%s'
      LIMIT 1", $game_tb, $user_id);

      $stmt = $db_conn->stmt_init();
      $stmt->prepare($sql);
      $stmt->execute();
      $result = $stmt->get_result();
      $user = $result->fetch_assoc();

      if($user)
      {
        return $user['game_type'];
        //$_SESSION['game_type'] = $user['game_type'];
        //exit();
        //header('Location: ../page/'.$user['game_type'].'.php');
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
      $id = $user_id;
      $sql = "INSERT INTO $game_type (game_id, player, id) 
      VALUES (?, ?, ?)";
      $stmt = $db_conn->prepare($sql);

      $stmt->bind_param("sss", $game_id, array_pop($players), $id);
      $stmt->execute();

      $id = $user["id"];
      $stmt->execute();


      //insert our record into the game table
      $sql = "INSERT INTO $game_tb (game_id, game_type, id) 
      VALUES (?, ?, ?)";
      $stmt = $db_conn->prepare($sql);

      $stmt->bind_param("sss", $game_id, $game_type, $id);
      $stmt->execute();

      $id = $user_id;
      $stmt->execute();

      //$output = array("successmessage"=>"Player found.");
      //exit(json_encode($output));
    }
    //--------------------------End of matchmaking--------------------------
  }
  catch(Throwable $e)
  {}
}

function isInTable($table_name, $user_id)//mabye write a function to differentiate you and other players?
{
  global $db_conn, $game_tb, $tbname;
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
      return;//do not remove this, it helps to initialize the $result outside the try catch block
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    if($user)
    {
      //header("Location: ../page/rock_paper_scissors.php");
      //return $user['game_id'];//return or exit
      return true;
    }
    else
    {
      return false;
    }
  }
  catch(Throwable $e)
  {
    //echo $e;
  }
  
}
?>