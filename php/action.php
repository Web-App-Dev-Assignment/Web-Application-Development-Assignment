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

  //$sql = "UPDATE $table SET $column = '$value' WHERE $unique_column = '$unique_value'";
  try
  {
    $sql = sprintf("UPDATE `%s` SET `%s` = '%s' WHERE `%s` = '%s'", $table, $column, $value, $unique_column, $unique_value);
    $stmt = $db_conn->prepare($sql);
    $stmt->execute();
    //echo $sql;
  }
  catch(Throwable $e)
  {
    echo $e;
  }
  
}

function generateTable($table_name)
{
  global $dbname, $db_conn;
  
  $sql = sprintf("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '%s' AND TABLE_NAME = '%s'", $dbname, $table_name);
  $stmt = $db_conn->prepare($sql);
  $stmt->execute();
  $results = $stmt->get_result();
  $results = $results->fetch_all(MYSQLI_ASSOC);  

  //store the column names
  $column_name = [];
  foreach($results as $result)
  {
    foreach($result as $resul)
    {
      array_push($column_name,$resul);
    }
  }

  //Generate the table
  $output = "
  <table id='$table_name'>
    <caption style='text-align:center;'>$table_name</caption>
      <tr>
        <td></td>";
  foreach($column_name as $col)
  {
    $output .= "<th style='text-align:center;'>".$col."</th>";
    //echo $col . "<br>";
  }
  $output .= "
    </tr>";

  //$sql = "SELECT * FROM $table_name WHERE `role`='user'";
  $sql = "SELECT * FROM $table_name";

  $stmt = $db_conn->prepare($sql);
  $stmt->execute();
  $results = $stmt->get_result();
  //print_r($results);
  while($result = $results->fetch_assoc())
  {
    $output .="
    <tr>
    <td>
      <div class='clickable delete'><span class='symbol'>&#xE922;</span><span style='color:red;'>delete</span></div>
    </td>
    ";
    foreach($column_name as $col)
    {
      $data = $result[$col];
      if(empty($data))
      {
        $data = 'NULL';
      }
      $output .= "<td style='text-align:center;'>" . $data . "</td>";
    }
    $output .="</tr>";
  }

  $output .="</table>";
  //echo $output;

  //Find the primary key
  $sql = "DESCRIBE $table_name;";
  $stmt = $db_conn->prepare($sql);
  $stmt->execute();
  $results = $stmt->get_result();
  $results = $results->fetch_all(MYSQLI_ASSOC);
  //print_r($results);
  $i=2;
  foreach ($results as $result)
  {
    if ($result['Key']==='PRI')
    {
      //$primary_key = $result['Field'];
      $primary_key = array('index'=>$i, 'field'=>$result['Field']);
      //echo "<br>Primary Key is " . $result['Field'] . "index is " . $primary_key['index'];
      break;
    }
    $i++;
  }

  $script = '$(document).ready(function() 
  {
    addDeleteListener(' . '"' . $table_name . '", ".delete", ' . $primary_key["index"] . ', ' . json_encode($primary_key['field']) . ')
    addUpdateListener(' . '"' . $table_name . '", ' . $primary_key['index'] . ',  ' . json_encode($primary_key['field']) . ')
  });';

  echo (json_encode(array('table' => $output, 'script' => $script)));
}

function generateButtons()
{
  global $dbname, $db_conn;
  //Find tables in the database
  $sql = "SHOW TABLES FROM $dbname;";
  $stmt = $db_conn->prepare($sql);
  $stmt->execute();
  $results = $stmt->get_result();
  $results = $results->fetch_all(MYSQLI_ASSOC);
  
  //store the table names
  $table_name = [];
  $output = "<div class='generatedButtons'>";
  foreach($results as $result)
  {
    foreach($result as $resul)
    {
      array_push($table_name,$resul);
      $output .= "<button id=$resul onclick=\"generateTable('generate_table', this.id)\">$resul</button><br>";
      //echo "<br>" . $resul;
    }
  }
  $output .= "</div>";
  echo $output;
}
?>