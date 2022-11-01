﻿
<!DOCTYPE HTML>  
<html>
<head>
<style>
.error {color: #FF0000;}
</style>
</head>
<body>  

<?php
// define variables and set to empty values
$usernameErr = $passwordErr = $nameErr = $emailErr = $genderErr = $websiteErr = "";
$username = $password = $name = $email = $gender = $comment = $website = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["username"])) {
    $username = "Username is required";
  } else {
    $username = test_input($_POST["username"]);
    //also need to check if username is taken
  }
  if (empty($_POST["password"])) {
    $nameErr = "Password is required";
  } else {
    $name = test_input($_POST["password"]);
    // check if name only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z-' ]*$/",$password)) {
      $nameErr = "Only letters and white space allowed";
      //need toggle password visibility, need ensure user type 8~16 char with special character
    }
  }

  if (empty($_POST["name"])) {
    $nameErr = "Name is required";
  } else {
    $name = test_input($_POST["name"]);
    // check if name only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z-' ]*$/",$name)) {
      $nameErr = "Only letters and white space allowed";
    }
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

  if (empty($_POST["comment"])) {
    $comment = "";
  } else {
    $comment = test_input($_POST["comment"]);
  }

  if (empty($_POST["gender"])) {
    $genderErr = "Gender is required";
  } else {
    $gender = test_input($_POST["gender"]);
  }
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>

<h2>PHP Form Validation Example</h2>
<p><span class="error">* required field</span></p>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  
  Name: <input type="text" name="name" value="<?php echo $name;?>">
  <span class="error">* <?php echo $nameErr;?></span>
  <br><br>
  E-mail: <input type="text" name="email" value="<?php echo $email;?>">
  <span class="error">* <?php echo $emailErr;?></span>
  <br><br>
  Website: <input type="text" name="website" value="<?php echo $website;?>">
  <span class="error"><?php echo $websiteErr;?></span>
  <br><br>
  Comment: <textarea name="comment" rows="5" cols="40"><?php echo $comment;?></textarea>
  <br><br>
  Gender:
  <input type="radio" name="gender" <?php if (isset($gender) && $gender=="male") echo "checked";?> value="male">Male
  <input type="radio" name="gender" <?php if (isset($gender) && $gender=="female") echo "checked";?> value="female">Female
  <input type="radio" name="gender" <?php if (isset($gender) && $gender=="other") echo "checked";?> value="other">Other  
  <input type="radio" name="gender" <?php if (isset($gender) && $gender=="prefer_not_to_say") echo "checked";?> value="prefer_not_to_say">Prefer not to say  
  <span class="error">* <?php echo $genderErr;?></span>
  <br><br>
  <input type="submit" name="submit" value="Submit">  
</form>

<?php
echo "<h2>Your Input:</h2>";
echo $name;
echo "<br>";
echo $email;
echo "<br>";
echo $website;
echo "<br>";
echo $comment;
echo "<br>";
echo $gender;
?>

<?php
//demonstration of password hashing
$hash = password_hash("password",PASSWORD_ARGON2ID);
echo strlen($hash);
echo "<br>\n";
if (password_verify("password", $hash)) {
  echo 'Password is valid!';
} else {
  echo 'Invalid password.';
}
echo "<br>\n";
//Refer to the xampp control panel, Start MySQL -> admin -> privilages/user accounts
$servername = "localhost";
$serverusername = "root";
$serverpassword = "";
$dbname = "myDB";
$tbname = "Users";

$db_conn;

//Connecting to MySQL server
try
{
  $db_conn = mysqli_connect($servername,$serverusername,$serverpassword);
  echo "Connected MySQL server successfully!\n";
  echo "<br>\n";
}
catch(Throwable $e)
{
  echo "Connection to MySQL server unsuccessful.";
  echo "<br>\n";
}

//Connecting to the db
try
{
  $db_conn->select_db($dbname);
  echo "Connected $dbname successfully!\n";
  echo "<br>\n";
}
catch(Throwable $e)
{
  // sql to create table
  $sql = "CREATE TABLE $tbname 
  (
    id VARCHAR(97) UNSIGNED PRIMARY KEY,
    name VARCHAR(50),
    username VARCHAR(30) NOT NULL UNIQUE,
    pw VARCHAR(30) NOT NULL,
    email VARCHAR(50),
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
  )";
  $db_conn->query($sql);
  $db_conn->select_db($dbname);
  echo "$dbname not found. $dbname created.\n";
  echo "<br>\n";
}

//Connecting to the table
try
{
  $sql = "INSERT INTO $tbname (id, username, pw, email, reg_date)
  VALUES (md5(uniqid()), 'username' ,'pw' , 'john@example.com')";
  $db_conn->query($sql);
  echo "Connected $tbname successfully!\n";
  echo "<br>\n";
}
catch(Throwable $e)
{
  //Create the database if not found, then connect to the newly created database
  $sql = "CREATE TABLE $tbname";
  $db_conn->query($sql);
  echo "$tbname not found. $tbname created.\n";
  echo "<br>\n";
}

/*
$sql = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME='$dbname'";

if ($db_conn->query($sql) === TRUE) 
{
  echo "Database connected\n";
  echo "<br>";
}
 else
{
  echo "Database $dbname is not connected\n";
  echo "<br>";

  

    /*
    // sql to create table
    $sql = "CREATE TABLE IF NOT EXISTS $tbname 
    (
      id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      username VARCHAR(30) NOT NULL UNIQUE,
      password VARCHAR(30) NOT NULL,
      email VARCHAR(50),
      reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";

  if ($conn->query($sql) === TRUE) 
  {
    echo "Table $tbname created successfully";
  } 
  else 
  {
    echo "Error creating table: " . $conn->error;
  }
  //
}
*/



//The connection will be closed automatically when the script ends. To close the connection before, use the following:
$db_conn->close(); 
?> 

</body>
</html>
