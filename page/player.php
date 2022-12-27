<?php

// if(!isset($_SESSION["user_id"]) && $_SESSION["role"] !== "admin")
// {
//   header("Location: index.php");
// }

?>

<!DOCTYPE HTML>  
<html>
<head>
  <title>Player</title>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
  <link rel="stylesheet" href="../css/stylesheet.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<style>
</style>
</head>
<body style="max-width: none;">

<!DOCTYPE HTML>  
<html>
<head>
  <title>Player</title>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
  <link rel="stylesheet" href="../css/stylesheet.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<style>
</style>
</head>
<body>

  <!---
  <table>
    <header>
      <tr>
        <td></td>
        <td>user id</td>
      </tr>
    </header>
    <tr>
      <td>
          <input type='checkbox' name='checkbox' value='100001' />
      </td>
    </tr>

    <tr>
      <td>
          <input type='checkbox' name='checkbox' value='100002' />
      </td>
    </tr>
  </table>
  --->

  
  
</body>
</html>
  
</body>
</html>

<?php
  include_once __DIR__ . "\\..\\php\\database.php";
  $sql = sprintf("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '%s' AND TABLE_NAME = '%s'", $dbname, $tbname);
  $stmt = $db_conn->prepare($sql);
  $stmt->execute();
  $results = $stmt->get_result();
  $results = $results->fetch_all(MYSQLI_ASSOC);
  //$stmt->bind_result($result);
  //$row = $smtm->array($result);

  //$result -> fetch_all(MYSQLI_ASSOC);
  //$stmt->fetch();
  // $keys = array_keys( $users );
  // $key = ${array_keys($users[$keys[0]])};

  // echo $key;

  // ${"var0"} = "test1";
  // echo $var0;

  // echo $users[0]["COLUMN_NAME"];
  //print_r($results);
  $column_name = [];

  foreach($results as $result)
  {
    foreach($result as $resul)
    {
      array_push($column_name,$resul);
    }
  }
  $output = "
  <table>
    <header>
      <tr>
        <td></td>";
  foreach($column_name as $col)
    {
      $output .= "<th style='text-align:center;'>".$col."</th>";
      //echo $col . "<br>";
    }
    $output .= "
      </tr>
    </header>";

    $sql = "SELECT * FROM $tbname WHERE `role`='user'";

    $stmt = $db_conn->prepare($sql);
    $stmt->execute();
    $results = $stmt->get_result();
    //print_r($results);
    while($result = $results->fetch_assoc())
    {
      $output .="
      <tr>
      <td></td>
      ";
      // for ($i=0; $i < count($column_name) ; $i++) { 
      //   $output .= "<td style='text-align:center;>";
      //   if (!empty($result[$column_name[$i]]))
      //   {
      //     $output .= $result[$column_name[$i]];
      //   }
      //   else
      //   {
      //     $output .= "NULL";
      //   }
      //   $output .= "</td>";
      // }
      foreach($column_name as $col)
      {
        //echo "<script>console.log('$result[$col]');</script>";
        $output .= "<td style='text-align:center;'>" . $result[$col] . "</td>";
      }
      $output .="</tr>";
      //echo $result[$column_name[0]] . "<br>";
      

      // $i = 2;
      // echo $column_name[$i] . " " ;
      // echo $result[$column_name[$i]] . "<br>";
    }

    $output .="</table>";
    echo $output;

    // for ($i=0; $i < strlen($column_name) ; $i++) { 
    //   $sql = "SELECT * FROM $tbname WHERE `role`='user'";
    // }

    

//  echo $users;
  //echo "$result";
  //echo "$row";
// foreach ($_POST['checkbox'] as $checkbox) {

//   //$condition = $_POST['condition'][$delete];
//   // Do stuff
// }
//checkbox,username,action
?>