<?php
include_once __DIR__ . "/functions.php";
$db_conn = include_once __DIR__ . "/database.php";
// define variables and set to empty values
$usernameErr = $passwordErr = $passwordConfirmationErr = $nameErr = $emailErr = $genderErr = $websiteErr = "";
$username = $password = $passwordConfirmation = $name = $email = $gender = $website = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
  if (empty($_POST["name"])) {
    //$nameErr = "Name is required";
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
    $username = $_POST["username"];
    $usernameErr = username_condition($username);
  }
  if (empty($_POST["password"])) {
    $passwordErr = "Password is required";
  } else {
    $password = $_POST["password"];
    $passwordErr = password_condition($password);
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
        //$emailErr = "";
        //$emailErr = "Email is required";
    } else {
        $email = test_input($_POST["email"]);
        // check if e-mail address is well-formed
        $emailErr = email_condition($email);
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

    if (empty($nameErr) && empty($usernameErr) && empty($passwordErr) && empty($passwordConfirmationErr))
    {
      debug_to_console("empty($nameErr) empty($usernameErr) empty($passwordErr)",0);
      insert_to_table($_POST["name"], $_POST["username"], $_POST["password"], $_POST["email"]);
    }
}
?>