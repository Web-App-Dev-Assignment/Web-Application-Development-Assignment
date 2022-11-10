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
        $genderErr = "*Gender is required";
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
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<style>
.error {color: #FF0000;}
</style>
</head>
<body>  
<h2>Signup</h2>
<p><span class="error">* required field</span></p>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
  Name: <input type="text" name="name" id="name" value="<?php echo $name;?>" placeholder="Enter your name.">
  <span class="error">* <?php echo $nameErr;?></span>
  <br><br>  
  Username: <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($username);?>" placeholder="Enter your username.">
  <span class="error" id="usernameErr" value=""></span>
  <br><br>
  Password: <input type="password" name="password" id="password" value="<?php echo htmlspecialchars($password);?>" placeholder="Enter your password.">
  <span class="error" id="passwordErr" value=""></span><br>
  <input type="checkbox" onclick="passwordVisibility('password')" name="passwordVisibilityCheckbox" <?php if(!empty($_POST['passwordVisibilityCheckbox'])){echo "checked";} ?>  >Show Password
  <br><br>
  Password Confirmation: <input type="password" name="passwordConfirmation" id="passwordConfirmation" value="<?php echo htmlspecialchars($passwordConfirmation);?>" placeholder="Re-enter your password.">
  <span class="error" id="passwordConfirmationErr" value=""></span><br>
  <input type="checkbox" onclick="passwordVisibility('passwordConfirmation')" name="passwordConfirmationVisibilityCheckbox" <?php if(!empty($_POST['passwordConfirmationVisibilityCheckbox'])){echo "checked";} ?>  >Show Password
  <br><br>
  E-mail: <input type="text" name="email" id="email" value="<?php echo htmlspecialchars($email);?>" placeholder="Enter your email.">
  <span class="error" id="emailErr" value=""></span>
  <br><br>
  Website: <input type="text" name="website" value="<?php echo htmlspecialchars($website);?>">
  <span class="error"><?php echo $websiteErr;?></span>
  <br><br>
  Gender:
  <input type="radio" name="gender" id="gender1" <?php if (isset($gender) && $gender=="male") echo "checked";?> value="male">Male
  <input type="radio" name="gender" id="gender2" <?php if (isset($gender) && $gender=="female") echo "checked";?> value="female">Female
  <input type="radio" name="gender" id="gender3" <?php if (isset($gender) && $gender=="other") echo "checked";?> value="other">Other  
  <input type="radio" name="gender" id="gender4" <?php if (isset($gender) && $gender=="prefer_not_to_say") echo "checked";?> value="prefer_not_to_say">Prefer not to say  
  <span class="error"><?php echo $genderErr;?></span>
  <br><br>
  <button type="button" id="signup">Signup</button>
</form>

<script>
  $(document).ready(function(){
   $('#username').keyup(function()
   {
      console.log("you are typing something...");
      $.ajax
      ({
        type:'post',
        url:'check_username.php',
        data:
        {
          username:$("#username").val()
        },
        success:function(response)
        {
          console.log(response);
          if(response.indexOf('Username has already been taken') >= 0)
          {
            $("#usernameErr").text("*Username has already been taken.");
          }
          else if(response.indexOf('Username is required') >= 0)
          {
            $("#usernameErr").text("*Username is required.");
          }
          else
          {
            $("#usernameErr").text("");
          }
        }
      });
   });

   $('#password').keyup(function()
   {
      console.log("you are typing something...");
      //console.log($("#password").val().length);
      if(!$("#password").val())//redundant
      {
        $("#passwordErr").text("*Password is required.");
      }
      else if ($("#password").val().length < 8 || $("#password").val().length > 16)
      {
        $("#passwordErr").text("*Password must be at least 8-16 characters.");
      }
      else if(!$("#password").val().match(/[a-zA-Z]/))
      {
        $("#passwordErr").text("*Password must contain at least one letter.");
      }
      else if(!$("#password").val().match(/[0-9]/))//same as /\d/
      {
        $("#passwordErr").text("*Password must contain at least one number.");
      }
      else if(!$("#password").val().match(/[^A-Za-z0-9]/))//same as /\W/
      {
        $("#passwordErr").text("*Password must contain at least one special character.");
      }
      else
      {
        $("#passwordErr").text("");
      }
    });

    $('#passwordConfirmation').keyup(function()
    {
      console.log("you are typing something...");
      //console.log($("#passwordConfirmation").val().length);
      if(!$("#passwordConfirmation").val())//redundant
      {
        $("#passwordConfirmationErr").text("*Password confirmation is required.");
      }
      else if ($("#password").val() !== $("#passwordConfirmation").val())
      {
        $("#passwordConfirmationErr").text("*Password must match.");
      }
      else
      {
        $("#passwordConfirmationErr").text("");
      }
    });

    $('#email').keyup(function()
    {
      console.log("you are typing something...");
      $.ajax
      ({
        type:'post',
        url:'check_email.php',
        data:
        {
          email:$("#email").val()
        },
        success:function(response)
        {
          console.log(response);
          if(response.indexOf('Email has already been taken') >= 0)
          {
            $("#emailErr").text("*Email has already been taken.");
          }
          else if(response.indexOf('Invalid email format') >= 0)
          {
            $("#emailErr").text("*Invalid email format.");
          }
          else
          {
            $("#emailErr").text("");
          }
        }
      });
    });
      
   });
</script>

<script>
// function signup()
// {
//   var name=$("#name").val();
//   var username=$("#username").val();
//   var password=$("#password").val();
//   var email=$("#email").val();
//   var gender=$("#gender").val();



//   $.ajax
//   ({
//   type:'post',
//   url:'signup.php',
//   data:{
//    name:name,
//    username:username,
//    password:password,
//    email:email,
//    gemder:gender
   
//   },
//   success:function(response) {
//   if(response=="success")
//   {
//     window.location.href="index.php";
//   }
//   else
//   {
//     alert("Wrong Details");
//   }
//   }
//   });
// }
</script>

<script>
  //$("input[name='gender']:checked").val();
  function checkGenderValue() 
  {
    if(!$('gender').val()) 
    { 
      console.log("it's checked"); 
    }
    var radioButton = document.getElementsByName('gender');
      
    for(i = 0; i < radioButton.length; i++) 
    {
      if(radioButton[i].checked)
      {
        return true;
      }
    }
    return false;
  }
</script>

<script>
  $(document).ready(function()
  {
    $("#signup").on('click', function()
    {
      //var username = $("#username").val();
      //var password = $("#password").val();
      //console.log(username + " , " + password);
      if(!$("#usernameErr").val() && !$("#passwordErr").val() && !$("#passwordConfirmationErr").val() && !$("#emailErr").val() && !$("input[name='gender']:checked").val())
      {
        $.ajax
        ({
          type:'post',
          url:'do_signup.php',
          data:
          {
            login:1,
            username:$("#username").val(),
            password:$("#password").val()
          },
          success:function(response)
          {
            if(response.indexOf('@0^/s&d~v~x2LiN?^k+ZJ[+Nk1QK+b') >= 0)
            {
              window.location.href="index.php";
              $("#err").text("*Login success.");
            }
            else
            {
              $("#err").text("*Login failed.");
            }
          }
        });
      }
    })
  })
</script>


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