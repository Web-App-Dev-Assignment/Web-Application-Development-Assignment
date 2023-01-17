<?php
  session_start();

  if (isset($_SESSION["user_id"]))
  {
    header("Location: index.php");
    exit();
  }
?>

<!DOCTYPE HTML>  
<html>
<head>
  <title>Signup</title>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
  <link rel="stylesheet" href="../css/stylesheet.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<style>
</style>
</head>
<body>  
<h2>Signup</h2>
<!---<p><span class="error">* required field</span></p>--->
<form method="post" submit="false">
  <label for="name">Name:</label>
  <input type="text" name="name" id="name" value="" placeholder="Enter your name.">
  <span class="error" id="nameErr" value=""></span>
  <br><br>  
  <label for="username">Username:</label>
  <input type="text" name="username" id="username" value="" placeholder="Enter your username.">
  <span class="error" id="usernameErr" value=""></span>
  <br><br>
  <label for="password">Password:</label>
  <input type="password" name="password" id="password" value="" placeholder="Enter your password.">
  <span class="error" id="passwordErr" value=""></span><br>
  <input type="checkbox" onclick="passwordVisibility('password')" name="passwordVisibilityCheckbox">Show Password
  <br><br>
  <label for="passwordConfirmation">Password Confirmation:</label>
  <input type="password" name="passwordConfirmation" id="passwordConfirmation" value="" placeholder="Re-enter your password.">
  <span class="error" id="passwordConfirmationErr" value=""></span><br>
  <input type="checkbox" onclick="passwordVisibility('passwordConfirmation')" name="passwordConfirmationVisibilityCheckbox">Show Password
  <br><br>
  <label for="email">E-mail:</label>
  <input type="text" name="email" id="email" value="" placeholder="Enter your email.">
  <span class="error" id="emailErr" value=""></span>
  <br><br>
  Gender:
  <input type="radio" name="gender" id="gender1"  value="male">Male
  <input type="radio" name="gender" id="gender2"  value="female">Female
  <input type="radio" name="gender" id="gender3"  value="other">Other  
  <input type="radio" name="gender" id="gender4"  value="prefer_not_to_say">Prefer not to say  
  <br>
  <span class="error" id="genderErr"></span>
  <br><br>
  <!---<button type="button" id="signup">Signup</button>--->
  <div class="buttonContainer">
    <button class="buttonWrapper" type="button" id="signup"><span style="font-family:symbols;">&#xE913;</span>Signup</button><br>
  </div>
  <br>
  <span class="error" id="err"></span>
</form>
<!---<button onclick="document.location='index.php'">Back</button><br>--->
<div class="buttonContainer">
  <button class="buttonWrapper" onclick="document.location='index.php'"><span style="font-family:symbols;">&#xE900;</span>Back</button><br>
</div>
<script>
  $(document).ready(function(){
    $('#name').keyup(function()
    {
      if($("#name").val().match(/\d/))//same as /[0-9]/
      {
        $("#nameErr").text("*Name must not contain number.");
      }
      else if($("#name").val().match(/[^a-zA-Z-' ]/))
      {
        $("#nameErr").text("*Name must not contain special character.");
      }
      else
      {
        $("#nameErr").text("");
      }
    });
   $('#username').keyup(function()
   {
      if($("#username").val())
      {
        $("#usernameErr").text("");
      }
      else if(!$("#username").val())
      {
        $("#usernameErr").text("*Username is required.");
        return;
      }
      $.ajax
      ({
        type:'post',
        url:'../ajax/ajax_checkUsername.php',
        data:
        {
          username:$("#username").val()
        },
        success:function(response)
        {
          console.log(response);
          jason = $.parseJSON(response);
          //console.log(response);
          if(!jason.errormessage)//redundant
          {
            $("#usernameErr").text("");
          }
          else
          {
            $("#usernameErr").text("*"+jason.errormessage);
          }
        }
      });
   });

   $('#password').keyup(function()
   {
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
      if(!$("#email").val())
      {
        $("#emailErr").text("");
        return;
      }

      $.ajax
      ({
        type:'post',
        url:'../ajax/ajax_checkEmail.php',
        data:
        {
          email:$("#email").val()
        },
        success:function(response)
        {
          jason = $.parseJSON(response);
          console.log(response);
          if(!jason.errormessage)
          {
            $("#emailErr").text("");
          }
          else
          {
            $("#emailErr").text("*"+jason.errormessage);
          }
        }
      });
    });

    $("input[name='gender']").change(function()
    {
      $("#genderErr").text("");
    });
      
   });
</script>

<script>
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

<script>//might require refinement on the condition
  $(document).ready(function()
  {
    $("#signup").on('click', function()
    {
      if($("#nameErr").text() || $("#usernameErr").text() || $("#passwordErr").text() || $("#passwordConfirmationErr").text() || $("#emailErr").text())
      {
        console.log("error");
        return;
      }
      else if(!$("#username").val())
      {
        $("#usernameErr").text("*Username is required.");
        return;
      }
      else if(!$("#password").val())
      {
        $("#passwordErr").text("*Password is required.");
        return;
      }
      else if(!$("#passwordConfirmation").val())
      {
        $("#passwordConfirmationErr").text("Password confirmation is required.");
        return;
      }
      else if(!$("input[name='gender']:checked").val())
      {
        $("#genderErr").text("*Gender is required.");
        return;
      }

      else
      {
        $.ajax
        ({
          type:'post',
          url:'../ajax/ajax_signup.php',
          data:
          {
            name:$("#name").val(),
            username:$("#username").val(),
            password:$("#password").val(),
            email:$("#email").val(),
            gender:$("input[name='gender']:checked").val()
          },
          success:function(response)
          {
            jason = $.parseJSON(response);
            console.log(response);
            //console.log(jason.testing);
            if(!jason.errormessage)
            {
              $("#err").text("*"+jason.successmessage);
              window.location.href="index.php";
            }
            else
            {
              $("#err").text("*"+jason.errormessage);
            }
          }
        });
      }
    })
  })
</script>

<script src="../javascript/function.js"></script>
</body>
</html>