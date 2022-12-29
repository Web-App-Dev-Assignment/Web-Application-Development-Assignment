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
          <input type='checkbox' name='checkbox' value='123' />
      </td>
    </tr>

    <tr>
      <td>
          <input type='checkbox' name='checkbox' value='456' />
      </td>
    </tr>
  </table>
  --->

  
  


<?php
  include_once __DIR__ . "\\..\\php\\action.php";

  generateButtons();
  //generateTable("users");
  //generateTable("chat");
  
  // include_once __DIR__ . "\\..\\php\\database.php";
  // $sql = sprintf("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '%s' AND TABLE_NAME = '%s'", $dbname, $tbname);
  // $stmt = $db_conn->prepare($sql);
  // $stmt->execute();
  // $results = $stmt->get_result();
  // $results = $results->fetch_all(MYSQLI_ASSOC);
  // //print_r($results);
  // //$stmt->bind_result($result);
  // //$row = $smtm->array($result);

  // //$result -> fetch_all(MYSQLI_ASSOC);
  // //$stmt->fetch();
  // // $keys = array_keys( $users );
  // // $key = ${array_keys($users[$keys[0]])};

  // // echo $key;

  // // ${"var0"} = "test1";
  // // echo $var0;

  // // echo $users[0]["COLUMN_NAME"];
  // //print_r($results);
  

  // //store the column names
  // $column_name = [];
  // foreach($results as $result)
  // {
  //   foreach($result as $resul)
  //   {
  //     array_push($column_name,$resul);
  //   }
  // }

  // //Generate the table
  // $output = "
  // <table id='users'>
  //   <caption style='text-align:center;'>users</caption>
  //   <header>
  //     <tr>
  //       <td></td>";
  // foreach($column_name as $col)
  //   {
  //     $output .= "<th style='text-align:center;'>".$col."</th>";
  //     //echo $col . "<br>";
  //   }
  //   $output .= "
  //     </tr>
  //   </header>";

  //   $sql = "SELECT * FROM $tbname WHERE `role`='user'";

  //   $stmt = $db_conn->prepare($sql);
  //   $stmt->execute();
  //   $results = $stmt->get_result();
  //   //print_r($results);
  //   while($result = $results->fetch_assoc())
  //   {
  //     $output .="
  //     <tr>
  //     <td>
  //       <div class='clickable delete'><span class='symbol'>&#xE922;</span><span style='color:red;'>delete</span></div>
  //     </td>
  //     ";
  //     foreach($column_name as $col)
  //     {
  //       $data = $result[$col];
  //       if(empty($data))
  //       {
  //         $data = 'NULL';
  //       }
  //       $output .= "<td style='text-align:center;'>" . $data . "</td>";
  //     }
  //     $output .="</tr>";
  //   }

  //   $output .="</table>";
  //   echo $output;

  //   //Find the primary key
  //   //$sql = "DESCRIBE `sessions`;";
  //   $sql = "DESCRIBE $tbname;";
  //   $stmt = $db_conn->prepare($sql);
  //   $stmt->execute();
  //   $results = $stmt->get_result();
  //   $results = $results->fetch_all(MYSQLI_ASSOC);
  //   //print_r($results);
  //   $i=2;
  //   foreach ($results as $result)
  //   {
  //     if ($result['Key']==='PRI')
  //     {
  //       //$primary_key = $result['Field'];
  //       $primary_key = array('index'=>$i, 'field'=>$result['Field']);
  //       echo "<br>Primary Key is " . $result['Field'] . "index is " . $primary_key['index'];
  //       break;
  //     }
  //     $i++;
  //   }

  //   //Find tables in the database
  //   $sql = "SHOW TABLES FROM $dbname;";
  //   $stmt = $db_conn->prepare($sql);
  //   $stmt->execute();
  //   $results = $stmt->get_result();
  //   $results = $results->fetch_all(MYSQLI_ASSOC);

    

  //   //store the table names
  //   $table_name = [];
  //   foreach($results as $result)
  //   {
  //     foreach($result as $resul)
  //     {
  //       array_push($table_name,$resul);
  //       echo "<br>" . $resul;
  //     }
  //   }

    


// foreach ($_POST['checkbox'] as $checkbox) {

//   //$condition = $_POST['condition'][$delete];
//   // Do stuff
// }
//checkbox,username,action
?>

<script>
  //console.log($("#delete").closest('tr').find('td:nth-child(3)').text());
  //var $row = $(this).closest("tr");

  // $(document).ready(function() 
  // {
  //   addDeleteListener('#users' , ".delete", <?php //echo $primary_key['index']?>, '<?php //echo $primary_key['field']?>')
  //   addUpdateListener('#users', <?php //echo $primary_key['index']?>, '<?php //echo $primary_key['field']?>')
  // });

</script>

<script src="../javascript/action.js"></script>

<div id="table"></div>
<div id="script"></div>

</body>
</html>