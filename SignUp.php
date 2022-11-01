
<!DOCTYPE HTML>  
<html>
<head>
  <title>Signup</title>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
<style>
.error {color: #FF0000;}
</style>
</head>
<body>  

<?php
include_once "Functions.php";
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

    if (!empty($_POST["username"]) && !empty($_POST["password"]) && !empty($_POST["name"]))
    {
      insert_to_table($_POST["name"], $_POST["username"], $_POST["password"], $_POST["email"]);
    }
}
?>

<h2>PHP Form Validation Example</h2>
<p><span class="error">* required field</span></p>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
  Name: <input type="text" name="name" value="<?php echo $name;?>">
  <span class="error">* <?php echo $nameErr;?></span>
  <br><br>  
  Username: <input type="text" name="username" value="<?php echo $username;?>">
  <span class="error">* <?php echo $usernameErr;?></span>
  <br><br>
  Password: <input type="password" name="password" value="<?php echo $password;?>" id="password">
  <span class="error">* <?php echo $passwordErr;?></span><br>
  <input type="checkbox" onclick="passwordVisibility('password')" name="passwordVisibilityCheckbox" <?php if(!empty($_POST['passwordVisibilityCheckbox'])){echo "checked";} ?>  >Show Password
  <br><br>
  Password Confirmation: <input type="password" name="passwordConfirmation" value="<?php echo $passwordConfirmation;?>" id="passwordConfirmation">
  <span class="error">* <?php echo $passwordConfirmationErr;?></span><br>
  <input type="checkbox" onclick="passwordVisibility('passwordConfirmation')" name="passwordConfirmationVisibilityCheckbox" <?php if(!empty($_POST['passwordConfirmationVisibilityCheckbox'])){echo "checked";} ?>  >Show Password
  <br><br>
  E-mail: <input type="text" name="email" value="<?php echo $email;?>">
  <span class="error">* <?php echo $emailErr;?></span>
  <br><br>
  Website: <input type="text" name="website" value="<?php echo $website;?>">
  <span class="error"><?php echo $websiteErr;?></span>
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

debug_to_console($banana,0);
/*
//demonstration of password hashing
$hash = password_hash("password",PASSWORD_ARGON2ID);
//$hash = md5(uniqid());
echo strlen($hash);
echo "<br>\n";
if (password_verify("password", $hash)) {
  echo 'Password is valid!';
} else {
  echo 'Invalid password.';
}
echo "<br>\n";
*/

//Under the xampp control panel, ensure that the module Apache and MySQL has been started
//Refer to the xampp control panel, Start MySQL -> admin -> privilages/user accounts
$servername = "localhost";
$serverusername = "root";
$serverpassword = "";
$dbname = "myDB";
$tbname = "Users";

//--------------------------Connecting to MySQL server--------------------------
try
{
  $db_conn = mysqli_connect($servername,$serverusername,$serverpassword);
  debug_to_console("Connected MySQL server successfully!",0);
}
catch(Throwable $e)
{
  debug_to_console("Connection to MySQL server unsuccessful.",2);
}
//--------------------------End of connecting to MySQL server--------------------------

//--------------------------Connecting to the database--------------------------
try
{
  $db_conn->select_db($dbname);
  debug_to_console("Connected database $dbname successfully!",0);
}
catch(Throwable $e)
{
  try
  {
    //Create the database if not found, then connect to the newly created database
    $sql = "CREATE DATABASE $dbname";
    $db_conn->query($sql);
    $db_conn->select_db($dbname);
    debug_to_console("Database $dbname  not found. Database $dbname created.",1);
  }
  catch(Throwable $e)
  {
    $e = test_escape_char($e);
    debug_to_console("Unable to connect to the database $dbname. Try checking if MySQL is running. \\nError:\\n$e",2);
  }
}
//--------------------------End of connecting to database--------------------------

//--------------------------Inserting to the table--------------------------
function insert_to_table($name, $username, $password, $email){
  try
  {
    $id = md5(uniqid());
    $hash = password_hash($password,PASSWORD_ARGON2ID);
    $sql = "INSERT INTO $tbname (id,name ,username, pw, email)
    VALUES ( '$id' , NULLIF('$name','') ,'$username' , '$hash' , NULLIF('$email',''))";
    $db_conn->query($sql);
    debug_to_console("Insertion into table $tbname success!",0);
  }
  catch(Throwable $e)
  {
    $e = test_escape_char($e);
    debug_to_console("$e",2);
    try
    {
      $sql = "CREATE TABLE $tbname
      (
        id VARCHAR(32) NOT NULL PRIMARY KEY,
        name VARCHAR(50),
        username VARCHAR(30) NOT NULL UNIQUE,
        pw VARCHAR(97) NOT NULL,
        email VARCHAR(50) UNIQUE,
        reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
      )";
      $db_conn->query($sql);
      debug_to_console("Table $tbname not found. Table $tbname created.",1);
    }
    catch(Throwable $e)
    {
      $e = test_escape_char($e);
      debug_to_console("Table $tbname already exists. Try checking the sql code. \\nError:\\n$e",2);
    }
  }
}
//--------------------------End of insertion to table--------------------------

//The connection will be closed automatically when the script ends. To close the connection before, use the following:
$db_conn->close(); 
?> 

</body>
</html>
