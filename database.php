<?php
include_once __DIR__ . "/functions.php";
//Under the xampp control panel, ensure that the module Apache and MySQL has been started
//Refer to the xampp control panel, Start MySQL -> admin -> privilages/user accounts
$host = "localhost";
$hostusername = "root";
$hostpassword = "";
$dbname = "mydb";
$tbname = "users";
$game_db = "game";
$chat_db = "chat";
$matchmaking_db = "matchmaking";

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
            email VARCHAR(255) UNIQUE,
            gender VARCHAR(32),
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
      $sql = sprintf("SELECT * FROM $tbname 
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
      $sql = sprintf("SELECT * FROM $tbname
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

function createMatchmakingDatabase()
{
  global $tbname, $db_conn, $matchmaking_db;

  try
  {
    $sql_table = "CREATE TABLE $matchmaking_db
    (
      index int NOT NULL AUTO_INCREMENT PRIMARY KEY,
      id VARCHAR(128) UNIQUE,
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

function createGameDatabase()
{
  global $tbname, $db_conn, $game_db;

  try
  {
    $sql_table = "CREATE TABLE $game_db
    (
      `index` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
      game_id VARCHAR(128) UNIQUE,
      chess_color VARCHAR(16) NOT NULL,
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

function createChatDatabase()
{
  global $tbname, $db_conn, $game_db, $chat_db;
  try
  {
    $sql_table = "CREATE TABLE $chat_db
    (
      chat_id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
      chat_text VARCHAR(255) NOT NULL,
      chat_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      id VARCHAR(128),
      game_id VARCHAR(128),
      FOREIGN KEY(id) REFERENCES $tbname(id),
      FOREIGN KEY(game_id) REFERENCES $game_db(game_id)
    )";
    $db_conn->query($sql_table);
    //debug_to_console("Table $chat_db not found. Table $chat_db created.",1);
  }
  catch(Throwable $e)
  {
    //debug_to_console(test_escape_char($db_conn->error) . "\\nError Code : " . $db_conn->errno ,1);
    // $output = array("errormessage"=>$e);
    // echo json_encode($output);
    if ($db_conn->errno === 1050)//1050 duplicate table
    {
      //debug_to_console("Table $chat_db already exists. \\nError:\\n" . test_escape_char($e),1);
    }
    else
    {
      //debug_to_console("Opps, something went wrong. \\nError:\\n" . test_escape_char($e),2);
    }
  }
}

function fetchOtherUsers()
{
  global $tbname, $db_conn;

  $sql = "SELECT * FROM $tbname
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
  global $tbname, $db_conn;

  $sql = "UPDATE $tbname
  SET last_online = now() 
  WHERE id = $user_id";

  $stmt = $db_conn->stmt_init();
  $stmt->prepare($sql);
  $stmt->execute();
}

function fetchUserLastActivity($user_id)
{
  global $tbname, $db_conn;

  $sql = "SELECT * FROM $tbname
  WHERE id = '$user_id' 
  ORDER BY last_online DESC 
  LIMIT 1";
  $stmt = $db_conn->stmt_init();
  $stmt->prepare($sql);
  $stmt->execute();
  $result = $stmt->fetch_assoc();
  if($result)
  {
    return $row['last_online'];
  }
}

function checkOnlineStatus($user_id)
{
  //$get_last_online = fetch_user_last_activity($_SESSION["user_id"]);
  $get_last_online = fetch_user_last_activity($user_id);
  $timestamp = date('Y-m-d H:i:s', strtotime('- 10 second'));

  if($get_last_online > $timestamp)
  {
    //online
    return "online";
  }
  else
  {
    
    //offline
    //return "offline";
  }
}

function insertChatMessage($chat_text, $user_id, $game_id)
{
  global $db_conn, $chat_db;

  $sql = "INSERT INTO $chat_db (chat_text, id, game_id) 
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
  global $db_conn, $tbname, $chat_db;
  createChatDatabase();
  try
  {
    $sql = sprintf("SELECT * FROM $chat_db
    WHERE game_id = '%s'
    ORDER BY chat_date DESC
    LIMIT 15", $game_id);
    
    $stmt = $db_conn->stmt_init();
    $stmt->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    $chats = $result->fetch_all(MYSQLI_ASSOC);
    if ($chats)
    {
      foreach ($chats as $chat) 
      {
        $sql = "SELECT * FROM $tbname
        WHERE id = {chat['id']}";
        $result = $db_conn->query($sql);
        $user = $result->fetch_assoc();
        if ($user)
        {
          $output .= '<span class="name">';
          if ($user['name'])
          {
            $output .= $user['name'];
          }
          else
          {
            $output .= $user['username'];
          }
          $output .= ' says: ';
          $output = '</span>';
          $output .= '<span class="chatText">';
          $output .= $chat['chat_text'];
          $output = '</span>';
        }
      }
      return $output;
    }
  }
  catch(Throwable $e)
  {
    echo $db_conn->errno;
    echo $e;
    if($db_conn->errno === 1146)//1146 Table doesn't exist
      {
        createChatDatabase();
      }
  }
  
}

function isInGame($user_id)//mabye write a function to differentiate you and other players?
{
  global $db_conn, $game_db, $tbname;
  try
  {
    $sql = "SELECT * FROM $game_db
    WHERE id = $user_id";
    $stmt = $db_conn->stmt_init();
    
    try
    {
      $result = $db_conn->query($sql);
    }
    catch(Throwable $e)
    {
      if($db_conn->errno === 1146)//1146 Table doesn't exist
      {
        createGameDatabase();
        $result = $db_conn->query($sql);
      }
      return;//do not remove this, it helps to initialize the $result outside the try catch block
    }
    $user = $result->fetch_assoc();
    if($user)
    {
      //header("Location: multiplayer.php");
      //return $user['game_id'];//return or exit
    }
  }
  catch(Throwable $e)
  {    
  }
  
}

//The connection will be closed automatically when the script ends. To close the connection before, use the following:
//$stmt->close();
//$db_conn->close(); 

return $db_conn;
?>