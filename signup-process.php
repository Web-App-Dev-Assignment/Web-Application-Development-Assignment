<?php
include_once __DIR__ . "/functions.php";
$db_conn = include_once __DIR__ . "/database.php";
// define variables and set to empty values
$usernameErr = $passwordErr = $passwordConfirmationErr = $nameErr = $emailErr = $genderErr = $websiteErr = "";
$username = $password = $passwordConfirmation = $name = $email = $gender = $website = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
  if (empty($_POST["name"])) {
    $nameErr = "Name is required";
  } else {
    $name = test_input($_POST["name"]);
    // check if name only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z-' ]*$/",$name)) {
      $nameErr = "Only letters and white space allowed";
    }
  }  
  if (empty($_POST["username"])) {
    $usernameErr = "Username is required";
  } else {
    $username = test_input($_POST["username"]);
    //also need to check if username is taken
  }
  if (empty($_POST["password"])) {
    $passwordErr = "Password is required";
  } else {
    $password = $_POST["password"];
    // check if name only contains letters and whitespace
    //print_r($_POST);
    if(strlen($_POST["password"])<8 || strlen($_POST["password"])>16){
      $passwordErr = "Password must be at least 8-16 characters.";
    }

    else if (!preg_match("/[a-zA-Z]/", $_POST["password"])) {
      $passwordErr = "Password must contain at least one letter.";
      //need toggle password visibility, need ensure user type 8~16 char with special character
    }
    
    else if (!preg_match("/[0-9]/", $_POST["password"])) {
      $passwordErr = "Password must contain at least one number.";
    }
    else if (!preg_match("/[^A-Za-z0-9]/", $_POST["password"])){
      $passwordErr = "Password must contain at least one special character.";
    }
  }

  if (empty($_POST["passwordConfirmation"])) {
    $passwordConfirmationErr = "Password confirmation is required.";
  } else {
    if ($_POST["password"] !== $_POST["passwordConfirmation"])
    {
      $passwordConfirmationErr = "Password must match.";
    }
    $passwordConfirmation = $_POST["passwordConfirmation"];
    //$passwordConfirmation = test_input($_POST["passwordConfirmation"]);
  }
    
    if (empty($_POST["email"])) {
        $emailErr = "";
        //$emailErr = "Email is required";
    } else {
        $email = test_input($_POST["email"]);
        // check if e-mail address is well-formed
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format";
        }
    }
        
    if (empty($_POST["website"])) {
        $website = "";
    } else {
        $website = test_input($_POST["website"]);
        // check if URL address syntax is valid (this regular expression also allows dashes in the URL)
        if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$website)) {
        $websiteErr = "Invalid URL";
        }
    }

    if (empty($_POST["gender"])) {
        $genderErr = "Gender is required";
    } else {
        $gender = test_input($_POST["gender"]);
    }

    if (!empty($_POST["username"]) && !empty($_POST["password"]) && !empty($_POST["name"] && $_POST["password"] === $_POST["passwordConfirmation"]))
    {
      insert_to_table($_POST["name"], $_POST["username"], $_POST["password"], $_POST["email"]);
    }
}
?>