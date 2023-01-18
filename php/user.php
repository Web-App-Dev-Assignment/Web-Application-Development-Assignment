<?php
include_once __DIR__ . "/database.php";

//--------------------------Updating to the table--------------------------
function updateUser($id, $name, $username, $password, $email)//updateTable
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
  }
  catch(Throwable $e)
  {
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
    }
  }
  catch(Throwable $e)
  {
  }
}
?>