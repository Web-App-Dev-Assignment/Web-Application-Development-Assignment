<?php
require_once __DIR__ . "/database.php";

function decreaseHP($game_type, $value)
{
  global $db_conn;

  $sql = sprintf("UPDATE %s 
  SET health = health-%s", $game_type, $value);
  $stmt = $db_conn->prepare($sql);
  $stmt->execute();
}

function updateGame($game_type, $game_id, $user_id)
{
  if ($game_type === 'rock_paper_scissors')
  {
    //if is_checked is false, continue, if true, return
    //check if both made a move
    //if both made a move, then check for the move and perform conditions accordingly
    //if updated, set the is_checked to 1
    //after both players is_check is set to 1, reset the move to NULL and is_checked to 0

    // $sql = sprintf("SELECT `move` FROM %s
    // WHERE game_id = '%s' AND NOT `move` = NULL
    // LIMIT 2"
    // , $game_type, $game_id);

    $sql = sprintf("SELECT COUNT(is_checked) FROM %s
    WHERE id = '%s' AND is_checked = 1
    LIMIT 1"
    , $user_id);
    $stmt = $db_conn->prepare($sql);
    $stmt->execute();
    $count = $stmt->get_result();

    if($count === 1)//is_checked = true
    {
      exit();//if updated, don't do anything; wait for the other player to be updated
    }

    $sql = sprintf("SELECT COUNT(`move`) FROM %s
    WHERE game_id = '%s' AND NOT `move` = NULL
    LIMIT 2"
    , $game_type, $game_id);
    $stmt = $db_conn->prepare($sql);
    $stmt->execute();

    //$result = $stmt->get_result();
    //$count = $stmt->rowCount();

    $count = $stmt->get_result();

    if($count < 2)//not every player made a move
    {
      //write a function to tell who selected and to select move
      exit();
    }

    $sql = sprintf("SELECT * FROM %s
    WHERE game_id = %s
    LIMIT 2"
    , $game_type, $game_id);
    $stmt = $db_conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $users = $result->fetchAll();

    $user = $users[0]['id'];
    if ($users[0]['id'] === $user_id)
    {
      $other_user = $users[1]['id'];
    }
    else
    {
      $other_user = $user;
      $user = $users[1]['id'];
    }

    // $sql = sprintf("SELECT * FROM %s
    // WHERE NOT id = '%s' AND game_id = %s
    // LIMIT 1"
    // , $game_type, $user_id, $game_id);
    // $stmt = $db_conn->prepare($sql);
    // $stmt->execute();
    // $result = $stmt->get_result();
    // $other_user = $result->fetch_assoc();

    $sql = sprintf("UPDATE %s
    SET is_checked = %s"
    , $game_type, $value);
    $stmt = $db_conn->prepare($sql);
    $stmt->execute();

    if($user['move'] === $other_user['move'])
    {
      //draw
      $message = "draw";
    }
    else if($user['move'] === 'rock')
    {
      if ($other_user['move'] === 'scissors')
      {
        //you win
        $message = "Win";
      }
      else if ($other_user['move'] === 'paper')
      {
        //you lose
        decreaseHP($game_type, 1);
        $message = "Lose";
      }
    }
    else if($user['move'] === 'paper')
    {
      if ($other_user['move'] === 'rock')
      {
        //you win
        $message = "Win";
      }
      else if ($other_user['move'] === 'scissors')
      {
        //you lose
        decreaseHP($game_type, 1);
        $message = "Lose";
      }
    }
    else if($user['move'] === 'scissors')
    {
      if ($other_user['move'] === 'paper')
      {
        //you win
        $message = "Win";
      }
      else if ($other_user['move'] === 'rock')
      {
        //you lose
        decreaseHP($game_type, 1);
        $message = "Lose";
      }
    }

    $sql = sprintf("SELECT id FROM %s
    WHERE game_id = '%s' AND health = 0
    LIMIT 1"
    ,$game_type ,$game_id);
    $stmt = $db_conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $loser = $result->fetch_assoc();
    if($loser !== $user)
    {
      //you win
    }
    else
    {
      //you lose
    }

    
    //only reset if both is_check is 1
    $sql = sprintf("SELECT COUNT(is_checked) FROM %s
    WHERE game_id = '%s' AND is_checked = 1
    LIMIT 2"
    ,$game_type ,$game_id);
    $stmt = $db_conn->prepare($sql);
    $stmt->execute();
    $count = $stmt->get_result();
    
    if($count === 2)
    {
      $sql = sprintf("UPDATE %s
      SET is_checked = 0 AND `move` = NULL
      WHERE game_id = '%s'"
      , $game_type ,$game_id);
    }

    $output = array("GameStatus"=>$message);
    exit(json_encode($output));
  }
}
?>