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
<h2>Signup</h2>
<p><span class="error">* required field</span></p>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
  Name: <input type="text" name="name" value="<?php echo $name;?>">
  <span class="error">* <?php echo $nameErr;?></span>
  <br><br>  
  Username: <input type="text" name="username" value="<?php echo htmlspecialchars($username);?>">
  <span class="error">* <?php echo $usernameErr;?></span>
  <br><br>
  Password: <input type="password" name="password" value="<?php echo htmlspecialchars($password);?>" id="password">
  <span class="error">* <?php echo $passwordErr;?></span><br>
  <input type="checkbox" onclick="passwordVisibility('password')" name="passwordVisibilityCheckbox" <?php if(!empty($_POST['passwordVisibilityCheckbox'])){echo "checked";} ?>  >Show Password
  <br><br>
  Password Confirmation: <input type="password" name="passwordConfirmation" value="<?php echo htmlspecialchars($passwordConfirmation);?>" id="passwordConfirmation">
  <span class="error">* <?php echo $passwordConfirmationErr;?></span><br>
  <input type="checkbox" onclick="passwordVisibility('passwordConfirmation')" name="passwordConfirmationVisibilityCheckbox" <?php if(!empty($_POST['passwordConfirmationVisibilityCheckbox'])){echo "checked";} ?>  >Show Password
  <br><br>
  E-mail: <input type="text" name="email" value="<?php echo htmlspecialchars($email);?>">
  <span class="error">* <?php echo $emailErr;?></span>
  <br><br>
  Website: <input type="text" name="website" value="<?php echo htmlspecialchars($website);?>">
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
//echo $banana;
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

/*
$host = "localhost";
$hostusername = "root";
$hostpassword = "";
$dbname = "myDB";
$tbname = "Users";
$db_conn = new mysqli($host,$hostusername,$hostpassword);
if ($db_conn->connect_errno){
  die("Connection error: " . $db_conn->connect_error);
}
*/

?> 

</body>
</html>