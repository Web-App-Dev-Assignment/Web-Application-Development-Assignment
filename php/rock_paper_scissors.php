<?php
require_once __DIR__ . "/database.php";

$game_type = 'rock_paper_scissors';
function decreaseHP($user_id, $move)
{
  global $db_conn, $game_type;

  $sql = sprintf("UPDATE %s 
  SET health = health-%s
  WHERE id = '%s'", $game_type, $move, $user_id);
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

function setMove($user_id, $move)
{
  global $db_conn, $game_type;

  $sql = sprintf("UPDATE %s
  SET `move` = '%s'
  WHERE id = '%s'", $game_type, $move, $user_id);
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

function isReady($user_id)
{
  global $db_conn, $game_type;

  $sql = sprintf("SELECT COUNT(id) FROM %s
  WHERE id = '%s' AND is_checked = 0"
  , $game_type, $user_id);
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
  $stmt->bind_result($result);
  $stmt->fetch();
  if($result === 1)
  {
    return true;
  }
  else
  {
    return false;
  }
}

function setCheck($user_id)
{
  global $db_conn, $game_type;
  $sql = sprintf("UPDATE %s
  SET is_checked = 1
  WHERE id = '%s'"
  , $game_type, $user_id);
  $stmt = $db_conn->prepare($sql);
  $stmt->execute();
}

function usersIsChecked($game_id)
{
  global $db_conn, $game_type;
  
  $sql = sprintf("SELECT COUNT(is_checked) FROM %s
  WHERE game_id = '%s' AND is_checked = 1
  LIMIT 2"
  ,$game_type ,$game_id);
  $stmt = $db_conn->prepare($sql);
  $stmt->execute();
  $stmt->bind_result($result);
  $stmt->fetch();

  if($result === 2)
  {
    return true;
  }
  else
  {
    return false;
  }
}

function checkMatch($user_id, $game_id)
{
  global $db_conn, $game_type;

  $message = "";

  $sql = sprintf("SELECT id FROM %s
  WHERE game_id = '%s' AND health = 0
  LIMIT 1"
  ,$game_type ,$game_id);
  $stmt = $db_conn->prepare($sql);
  $stmt->execute();
  $result = $stmt->get_result();
  $loser = $result->fetch_assoc();
  if ($loser)
  {
    setCheck($user_id);
    $result = usersIsChecked($game_id);
    
    if($result)
    {
      include_once __DIR__ . "\\..\\php\\game.php";
      deleteGameType($game_type, $game_id);
    }
    
    if($loser['id'] !== $user_id)
    {
      //you win the match
      $message = "You've won the match!";
    }
    else
    {
      //you lose the match
      $message = "You've lost the match.";
    }
  }
  return $message;
}

/*
if is_checked is false, continue, if true, return
check if both made a move
if both made a move, then check for the move and perform conditions accordingly
if updated, set the is_checked to 1
after both players is_check is set to 1, reset the move to NULL and is_checked to 0
*/
function rockPaperScissors($user_id, $game_id)
{
  global $db_conn, $game_type;
  
  try
  {
    $sql = sprintf("SELECT COUNT(is_checked) FROM $game_type
    WHERE id = '%s' AND is_checked = 1
    LIMIT 1"
    , $user_id);
    $stmt = $db_conn->prepare($sql);
    $stmt->execute();
    $stmt->bind_result($result);
    $stmt->fetch();
    
    if($result === 1)//is_checked = true
    {
      return "";
      //if updated, don't do anything; wait for the other player to be updated
    }
    
    $sql = sprintf("SELECT COUNT(`move`) FROM %s
    WHERE game_id = '%s' AND NOT `move` IS NULL
    LIMIT 2"
    , $game_type, $game_id);

    $stmt->free_result();
    $stmt = $db_conn->prepare($sql);
    $stmt->execute();
    $stmt->bind_result($result);
    $stmt->fetch();

    if($result < 2)//not every player made a move
    {
      //Function to tell who selected and to select move
      return "";
    }

    $sql = sprintf("SELECT id, `move` FROM %s
    WHERE game_id = '%s'
    LIMIT 2"
    , $game_type, $game_id);
    $stmt->free_result();
    $stmt = $db_conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $users = $result->fetch_all(MYSQLI_ASSOC);


    $user = $users[0];

    if ($user['id'] === $user_id)
    {
      $other_user = $users[1];
    }
    else
    {
      $other_user = $user;
      $user = $users[1];
    }

    if($user['move'] === $other_user['move'])
    {
      //draw
      $message = "Draw";
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
        decreaseHP($user_id, 1);
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
        decreaseHP($user_id, 1);
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
        decreaseHP($user_id, 1);
        $message = "Lose";
      }
    }

    setCheck($user_id);
    
    //only reset if both is_check is 1
    $result = usersIsChecked($game_id);
    
    if($result)
    {
      $sql = sprintf("UPDATE %s
      SET is_checked = 0, `move` = NULL
      WHERE game_id = '%s'"
      , $game_type ,$game_id);
      $stmt->free_result();
      $stmt = $db_conn->prepare($sql);
      $stmt->execute();
    }

    return $message;
  }
  catch(Throwable $e)
  {
    echo $e;
  }
}
?>