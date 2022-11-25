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
      id VARCHAR(128) NOT NULL UNIQUE,
      FOREIGN KEY(id) REFERENCES $tbname(id)
    )";
    $db_conn->query($sql_table);
    //debug_to_console("Table $game_tb not found. Table $game_tb created.",1);
  }
  catch(Throwable $e)
  {
    //echo $e;
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

function startMatchMaking($user_id)//should call it when player presses the matchmaking button
{
  global $db_conn, $matchmaking_tb, $game_tb;

  //--------------------------Check if matchmaking--------------------------
  if(isInTable($matchmaking_tb, $user_id))//is currently matchmaking
  {
    //write code to deal with already matchmaking here.//resume? cancel then restart the process?
    $output = array("errormessage"=>"Already matchmaking.");
    exit(json_encode($output));
  }
  //--------------------------End of check if matchmaking--------------------------
  //--------------------------Check if in game--------------------------
  else if(isInTable($game_tb, $user_id))//is currently ingame
  {
    //write code to redirect player to multiplay game here
    $output = array("errormessage"=>"Already in game.");
    exit(json_encode($output));
  }
  //--------------------------End of check if in game--------------------------
  
  
  $sql = "INSERT INTO $matchmaking_tb (id) 
  VALUES (?)";

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
  
  $stmt->bind_param("s", $user_id);
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
    //echo $e;
  }
  $stmt->execute();
}

function matchMaking($user_id)
{
  global $db_conn, $matchmaking_tb, $game_tb;
  try
  {
    

    //--------------------------Matchmaking--------------------------
    $sql = sprintf("SELECT id FROM $matchmaking_tb
    WHERE id != '%s'
    LIMIT 1", $user_id);

    $stmt = $db_conn->stmt_init();
    $stmt->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if($user)//there is player matchmaking
    {
      //$user["id"]//the other user
      //$_SESSION["user_id"]//the current user
      $sql = sprintf("DELETE FROM $matchmaking_tb
      WHERE id IN ('%s','{$user["id"]}')", $user_id);
      $stmt = $db_conn->stmt_init();
      $stmt->prepare($sql);
      $stmt->execute();

      $game_id = md5(uniqid());
      $color = array("black","white");
      shuffle($color);
      $id = $_SESSION["user_id"];
      $sql = "INSERT INTO $game_tb (game_id, chess_color, id) 
      VALUES (?, ?, ?)";
      $stmt = $db_conn->prepare($sql);

      $stmt->bind_param("sss", $game_id, array_pop($color), $id);
      $stmt->execute();

      $id = $user["id"];
      $stmt->execute();

      $output = array("successmessage"=>"Player found.");
      exit(json_encode($output));
    }
    //--------------------------End of matchmaking--------------------------
  }
  catch(Throwable $e)
  {}
}
?>