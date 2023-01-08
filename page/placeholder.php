<?php
  include_once __DIR__ . "\\..\\php\\function.php";

  include_once __DIR__ . "\\..\\php\\database.php";

  $sql = "SELECT * FROM users";

  $results = $db_conn -> query($sql);
  //$row = mysqli_fetch_array($results);
  //$column = array_keys($row);
  print_r($results);
  //print_r($column);
  //print_r($row);
  echo "<br><br>";
  $result = mysqli_fetch_all($results, MYSQLI_NUM);
  // Process all rows
  //while($row = mysqli_fetch_array($results, MYSQLI_NUM)) 
  foreach ($result as $resul) 
  {
    //echo $row['column_name']; // Print a single column data
    echo print_r($resul);       // Print the entire row data
    echo "<br><br>";
    $columns = array_keys($resul);
    echo print_r($columns);       // Print the entire row data
    echo "<br><br>";
    // foreach ($columns as $column) {
    //   //echo $row[$column] . "<br>";
    // }
    echo "<hr>";
  }
?>

<!DOCTYPE HTML>  
<html>
<head>
  <title>Rock Paper Scissors</title>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
  <link rel="stylesheet" href="../css/stylesheet.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<style>
</style>
</head>
<body>

  <div>

  </div>
  
</body>
</html>