<?php
include_once __DIR__ . "\\..\\php\\action.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
  switch ($_POST['action']) 
  {
    case 'delete':
      delete($_POST['table_name'], $_POST['unique_column'], $_POST['unique_value']);
      break;
    case 'update':
      update($_POST['table_name'], $_POST['unique_column'], $_POST['unique_value'], $_POST['column'], $_POST['value']);
      break;
    case 'generate_table':
      exit(generateTable($_POST['table_name']));
      break;
    default:
      break;
  }
}
  
?>