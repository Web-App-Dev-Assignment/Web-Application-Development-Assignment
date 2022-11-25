<?php
include_once __DIR__ . "/functions.php";
//Under the xampp control panel, ensure that the module Apache and MySQL has been started
//Refer to the xampp control panel, Start MySQL -> admin -> privilages/user accounts
$host = "localhost";
$hostusername = "root";
$hostpassword = "";
$dbname = "mydb";
$tbname = "users";
$game_tb = "game";
$chat_tb = "chat";
$matchmaking_tb = "matchmaking";

$timezone = "Asia/Singapore";
date_default_timezone_set($timezone);
//--------------------------Connecting to host code--------------------------
try
{
  $db_conn = mysqli_connect($host,$hostusername,$hostpassword);
  //debug_to_console("Connected $host successfully!",0);
}
catch(Throwable $e)
{
  $e = test_escape_char($e);
  //debug_to_console("Connection to $host unsuccessful. \\nError:\\n$e",2);
}
//--------------------------End of connecting to host code--------------------------

//--------------------------Connecting to the database code--------------------------
try
{
  $db_conn->select_db($dbname);
  //debug_to_console("Connected database $dbname successfully!",0);
}
catch(Throwable $e)
{
  try
  {
    //Create the database if not found, then connect to the newly created database
    $sql = "CREATE DATABASE $dbname";
    $db_conn->query($sql);
    $db_conn->select_db($dbname);
    $e = test_escape_char($e);
    //debug_to_console("Database $dbname  not found. Database $dbname created. \\nWarning:\\n$e",1);
  }
  catch(Throwable $e)
  {
    $e = test_escape_char($e);
    //debug_to_console("Unable to connect to the database $dbname. Try checking if MySQL is running. \\nError:\\n$e",2);
  }
}
//--------------------------End of connecting to database code--------------------------

//--------------------------Inserting to the table--------------------------
function insertToTable($name, $username, $password, $email){
  
  try
  {
    global $tbname, $db_conn;
    $id = md5(uniqid());
    $hash = password_hash($password,PASSWORD_ARGON2ID);
    $sql = "INSERT INTO $tbname (id,name ,username, password, email)
    VALUES ( ?, NULLIF(?,''), ?, ?, NULLIF(?,''))";
    $stmt = $db_conn->stmt_init();

    try
    {
      $stmt->prepare($sql);
    }
    catch(Throwable $e){
      // debug_to_console(test_escape_char($db_conn->error) . "\\nError Code : " . $db_conn->errno ,1);
      // debug_to_console("Error preparing the SQL code. \\nError:\\n" . test_escape_char($e),1);
      
      if($db_conn->errno === 1146)//1146 Table doesn't exist
      {
        // debug_to_console("Table $tbname doesn\\'t exists.", 1);
        try
        {
          $sql_table = "CREATE TABLE $tbname
          (
            id VARCHAR(128) NOT NULL PRIMARY KEY,
            name VARCHAR(128),
            username VARCHAR(128) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            gender ENUM ('male','female','other','prefer_not_to_say'),  
            last_online TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
          )";//ON INSERT or ON UPDATE, check
          $db_conn->query($sql_table);
          $stmt->prepare($sql);
          // debug_to_console("Table $tbname not found. Table $tbname created.",1);
        }
        catch(Throwable $e)
        {
          // debug_to_console(test_escape_char($db_conn->error) . "\\nError Code : " . $db_conn->errno ,1);
          if ($db_conn->errno === 1050)//1050 duplicate table
          {
            // debug_to_console("Table $tbname already exists. \\nError:\\n" . test_escape_char($e),1);
          }
          else
          {
            // debug_to_console("Opps, something went wrong. \\nError:\\n" . test_escape_char($e),2);
          }
        }
      }
      else
      {
        // debug_to_console("Opps, something went wrong. \\nError:\\n" . test_escape_char($e),2);
      }
    }

    $stmt->bind_param("sssss", $id, $name, $username, $hash, $email);

    try
    {
      $stmt->execute();
      // debug_to_console("Insertion into table $tbname success!",0);
      //header("Location: signup-success.html");
      //exit;
    }
    catch(Throwable $e)
    {
      // debug_to_console(test_escape_char($db_conn->error) . "\\nError Code : " . $db_conn->errno ,1);
      if($db_conn->errno === 1062)//1062 duplicate Unique information
      {
        // debug_to_console("Duplicate Unique entry. \\nError:\\n" . test_escape_char($e),1);
      }
      else
      {
        // debug_to_console("Insertion into table $tbname unsuccessful. \\nError:\\n" . test_escape_char($e),2);
      }
    }

    //$sql = "INSERT INTO $tbname (id,name ,username, pw, email)
    //VALUES ( '$id' , NULLIF('$name','') ,'$username' , '$hash' , NULLIF('$email',''))";
    //$db_conn->query($sql);
  }
  catch(Throwable $e)
  {
    // debug_to_console(test_escape_char($db_conn->error) . "\\nError Code : " . $db_conn->errno ,1);
    //debug_to_console("Opps, something went wrong. \\nError:\\n" . test_escape_char($e), 2);
  }
}
//--------------------------End of insertion to table--------------------------

//--------------------------Updating to the table--------------------------
function updateTable($id, $name, $username, $password, $email)
{
  try
  {
    $usernameErr = username_condition($username);
    $passwordErr = password_condition($password);
    $emailErr = email_condition($email);
    
    $sql = "UPDATE $tbname
    SET name = $db_conn->real_escape_string($name)";

    if(empty($usernameErr))
    {
      $sql .= ", username = $db_conn->real_escape_string($username)";

    }
    if(empty($passwordErr))
    {
      $sql .= ", password = $db_conn->real_escape_string($password)";

    }
    if(empty($emailErr))
    {
      $sql .= ", email = $db_conn->real_escape_string($email)";

    }
    $sql .= "WHERE id = $id";
  }
  catch(Throwable $e)
  {
    // debug_to_console(test_escape_char($e), 0);
  }
}
//--------------------------End of update to table--------------------------

//--------------------------Retrieving current user data--------------------------
function retrieveCurrentUserData()
{
  include_once __DIR__ . "/index.php";
  try
  {
    if (isset($user))
    {
      return $user;
    }

    // if (isset($_SESSION["user_id"]))
    // {
    //   $sql = "SELECT * FROM $tbname WHERE id = {$_SESSION["user_id"]}";
    //   $result = $db_conn->query($sql);
  
    //   $user = $result->fetch_assoc();
    // }
  }
  catch(Throwable $e)
  {
    // debug_to_console(test_escape_char($e), 0);
  }
}
//--------------------------End Retrieving current user data--------------------------

function deleteUserAccount()
{
  try
  {
    if (isset($_SESSION["user_id"]))
    {
      $sql = "DELETE FROM $tbname WHERE id = {$_SESSION["user_id"]}";
      $db_conn->query($sql);
      session_destroy();
      //header("Location: index.php");
    }
  }
  catch(Throwable $e)
  {
    // debug_to_console(test_escape_char($e), 0);
  }
}

//format of the username condition
function usernameCondition($username)
{
  global $tbname, $db_conn;
  $usernameErr = "";

  if (empty($username)) 
  {
    $usernameErr = "Username is required";
    return $usernameErr;
  } 
  else
  {
    try
    {
      $sql = sprintf("SELECT username FROM $tbname 
      WHERE username = '%s'",
      $db_conn->real_escape_string($_POST["username"]));
    
      $result = $db_conn->query($sql);
      $user = $result->fetch_assoc();

      if($user)
      {
        $usernameErr = "Username has already been taken.";
      }
    }
    catch(Throwable $e)
    {
      //debug_to_console(test_escape_char($e), 0);
    }
    return $usernameErr;
  }
}

//format of the password condition
function passwordCondition($password)
{
  $passwordErr = "";
  if(strlen($password)<8 || strlen($password)>16){
    $passwordErr = "Password must be at least 8-16 characters.";
  }

  else if (!preg_match("/[a-zA-Z]/", $password)) {
    $passwordErr = "Password must contain at least one letter.";
    //need toggle password visibility, need ensure user type 8~16 char with special character
  }
  
  else if (!preg_match("/[0-9]/", $password)) {
    $passwordErr = "Password must contain at least one number.";
  }
  else if (!preg_match("/[^A-Za-z0-9]/", $password)){
    $passwordErr = "Password must contain at least one special character.";
  }
  return $passwordErr;
}

//format of the email condition
function emailCondition($email)
{
  global $tbname, $db_conn;
  $emailErr = "";
  if (empty($email)) 
  {
    return $emailErr;
  } 
  else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
  {
    $emailErr = "Invalid email format";
  }
  else
  {
    try
    {
      $sql = sprintf("SELECT email FROM $tbname
      WHERE email = '%s'",
      $db_conn->real_escape_string($_POST["email"]));
    
      $result = $db_conn->query($sql);
      $user = $result->fetch_assoc();

      if($user)
      {
        $emailErr = "Email has already been taken.";
      }
    }
    catch(Throwable $e)
    {
      //debug_to_console(test_escape_char($e), 0);
    }
  }
  
  return $emailErr;
}

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

// function setIsChecked($game_type, $value)
// {
//   global $db_conn;

//   $sql = sprintf("UPDATE %s
//   SET is_checked = %s"
//   , $game_type, $value);
//   $stmt = $db_conn->prepare($sql);
//   $stmt->execute();
// }

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

function createGameType($game_type)
{
  if ($game_type === 'rock_paper_scissors')
  {
    $sql = sprintf("CREATE TABLE %s
    (
      `index` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
      health UNSIGNED TINYINT NOT NULL DEFAULT 3,
      `move` ENUM('rock','paper','scissors') DEFAULT NULL,
      `is_checked` BIT(1) DEFAULT 0,
      player ENUM('player1','player2'),
      game_id VARCHAR(128) NOT NULL,
      id VARCHAR(128) NOT NULL UNIQUE,
      FOREIGN KEY(id) REFERENCES $tbname(id)
      FOREIGN KEY(game_id) REFERENCES $game_tb(game_id)
    )"
    ,$game_type);
  }
  else if ($game_type === 'chess')
  {
    $sql = "";
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

function deleteGameSession($game_tb, $game_type, $user_id)
{
  global $db_conn;
  try
  {
    $sql = sprintf("DELETE FROM %s INNER JOIN %s
    WHERE id= '%s' ", $game_tb, $game_type, $user_id);
    $stmt = $db_conn->prepare($sql);
    $stmt->execute();
  }
  catch(Throwable $e)
  {
    echo $e;
  }
}

function createGameTable()
{
  global $db_conn, $tbname, $game_tb;

  try
  {
    $sql_table = "CREATE TABLE $game_tb
    (
      `index` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
      game_id VARCHAR(128) NOT NULL UNIQUE,
      game_type ENUM('chess','rock_paper_scissors', 'tick_tack_toe'),
      id VARCHAR(128) NOT NULL UNIQUE,
      FOREIGN KEY(id) REFERENCES $tbname(id)
    )";
    $stmt = $db_conn->prepare($sql);
    $stmt->execute();
    //debug_to_console("Table $game_db not found. Table $game_db created.",1);
  }
  catch(Throwable $e)
  {
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

/*
function createGameDatabase()
{
  global $tbname, $db_conn, $game_db;

  try
  {
    $sql_table = "CREATE TABLE $game_db
    (
      `index` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
      game_id VARCHAR(128) NOT NULL UNIQUE,
      chess_color ENUM('black','white'),
      move_string VARCHAR(256) NOT NULL,
      latest_move VARCHAR(256) NOT NULL,
      id VARCHAR(128),
      FOREIGN KEY(id) REFERENCES $tbname(id)
    )";
    $db_conn->query($sql_table);
    //debug_to_console("Table $game_db not found. Table $game_db created.",1);
  }
  catch(Throwable $e)
  {
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
*/

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

function fetchOtherUsers()
{
  global $db_conn, $tbname;

  $sql = "SELECT `name`, username FROM $tbname
  WHERE id != '{$_SESSION["user_id"]}'";

  $result = $db_conn->query($sql);

  $user = $result->fetchAll();

  if ($user)
  {
    foreach ($user as $row) 
    {
      $output .= '
      <tr>
       <td>';
       if(!empty($row["name"]))
       {
        $output .= htmlspecialchars($row['name']);
       }
       else
       {
        $output .= htmlspecialchars($row['username']);
       }
       $output .=
       '</td>
      </tr>
      ';
     }
     
     $output .= '</table>';
     
     echo $output;

  }
  else
  {
    $err = "There are no other users.";
    return $err;
  }
}

function updateLastOnline($user_id)
{
  global $db_conn, $tbname;

  $sql = sprintf("UPDATE $tbname
  SET last_online = now() 
  WHERE id = '%s'", $user_id);

  $stmt = $db_conn->stmt_init();
  $stmt->prepare($sql);
  $stmt->execute();
}

function fetchUserLastActivity($table_name, $user_id)
{
  global $db_conn;

  $sql = sprintf("SELECT last_online FROM %s
  WHERE id = '%s'
  LIMIT 1", $table_name, $user_id);
  $stmt = $db_conn->stmt_init();
  $stmt->prepare($sql);
  $stmt->execute();
  $result = $stmt->get_result();
  $user = $result->fetch_assoc();
  if($user)
  {
    return $user['last_online'];
  }
}

function checkOnlineStatus($table_name, $user_id)
{
  //$get_last_online = fetch_user_last_activity($_SESSION["user_id"]);
  $get_last_online = fetchUserLastActivity($table_name, $user_id);
  $time_stamp = date('Y-m-d H:i:s', strtotime('- 10 second'));

  if($get_last_online > $time_stamp)
  {
    //online
    return "online";
  }
  else
  {
    
    //offline
    return "offline";
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
      $result = $db_conn->query($sql);
    }
    catch(Throwable $e)
    {
      if($db_conn->errno === 1146 && $table_name === $game_tb)//1146 Table doesn't exist
      {
        createGameTable();
        $result = $db_conn->query($sql);
      }
      return;//do not remove this, it helps to initialize the $result outside the try catch block
    }
    $user = $result->fetch_assoc();
    if($user)
    {
      //header("Location: multiplayer.php");
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

//The connection will be closed automatically when the script ends. To close the connection before, use the following:
//$stmt->close();
//$db_conn->close(); 

return $db_conn;
?>