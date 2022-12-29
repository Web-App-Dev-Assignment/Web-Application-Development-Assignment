<?php
require_once __DIR__ . "/database.php";

function delete($table, $unique_column, $unique_value)
{
  global $db_conn;

  $sql = "DELETE FROM $table WHERE $unique_column = '$unique_value'";
  $stmt = $db_conn->prepare($sql);
  $stmt->execute();
}

function update($table, $unique_column, $unique_value, $column, $value)
{
  global $db_conn;

  $sql = "UPDATE $table SET $column = '$value' WHERE $unique_column = '$unique_value'";
  $stmt = $db_conn->prepare($sql);
  $stmt->execute();
}

?>