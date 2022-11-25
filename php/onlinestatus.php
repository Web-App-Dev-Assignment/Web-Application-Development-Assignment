<?php
require_once __DIR__ . "/database.php";

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

?>