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
  Name: <input type="text" name="name" id="name" value="" placeholder="Enter your name.">
  <span class="error" id="nameErr" value=""></span>
  <br><br>  
  Username: <input type="text" name="username" id="username" value="" placeholder="Enter your username.">
  <span class="error" id="usernameErr" value=""></span>
  <br><br>
  Password: <input type="password" name="password" id="password" value="" placeholder="Enter your password.">
  <span class="error" id="passwordErr" value=""></span><br>
  <input type="checkbox" onclick="passwordVisibility('password')" name="passwordVisibilityCheckbox">Show Password
  <br><br>
  Password Confirmation: <input type="password" name="passwordConfirmation" id="passwordConfirmation" value="" placeholder="Re-enter your password.">
  <span class="error" id="passwordConfirmationErr" value=""></span><br>
  <input type="checkbox" onclick="passwordVisibility('passwordConfirmation')" name="passwordConfirmationVisibilityCheckbox">Show Password
  <br><br>
  E-mail: <input type="text" name="email" id="email" value="" placeholder="Enter your email.">
  <span class="error" id="emailErr" value=""></span>
  <br><br>
  Website: <input type="text" name="website" value="">
  <span class="error" id="websiteErr" value=""></span>
  <br><br>
  Gender:
  <input type="radio" name="gender" id="gender1"  value="male">Male
  <input type="radio" name="gender" id="gender2"  value="female">Female
  <input type="radio" name="gender" id="gender3"  value="other">Other  
  <input type="radio" name="gender" id="gender4"  value="prefer_not_to_say">Prefer not to say  
  <br>
  <span class="error" id="genderErr"></span>
  <br><br>
  <button type="button" id="signup">Signup</button>
</form>

<script>
  $(document).ready(function(){
    $('#name').keyup(function()
    {
      console.log("you are typing something...");
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
      console.log("you are typing something...");
      // console.log($("#username").val());
      // console.log($("#username").text());
      // console.log($("#usernameErr").text());
      // console.log($("#usernameErr").text());
      
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
          if(response.indexOf('Invalid email format') >= 0)
          {
            $("#emailErr").text("*Invalid email format.");
          }
          else if(response.indexOf('Email has already been taken') >= 0)
          {
            $("#emailErr").text("*Email has already been taken.");
          }
          else
          {
            $("#emailErr").text("");
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
      if($("#usernameErr").text() || $("#passwordErr").text() || $("#passwordConfirmationErr").text())
      {
        return;
      }
      else if(!$("#username").val())
      {
        $("#usernameErr").text("Username is required.");
        return;
      }
      else if(!$("#password").val())
      {
        $("#passwordErr").text("Password is required.");
        return;
      }
      else if(!$("#passwordConfirmation").val())
      {
        $("#passwordConfirmationErr").text("Password confirmation is required.");
        return;
      }
      else if(!$("input[name='gender']:checked").val())
      {
        $("#genderErr").text("Gender is required.");
        return;
      }

      else if(!$("#nameErr").text() && !$("#usernameErr").text() && !$("#passwordErr").text() && !$("#passwordConfirmationErr").text() && !$("#emailErr").text() && !$("input[name='gender']:checked").val())
      {
        return;//remember to remove this
        $.ajax
        ({
          type:'post',
          url:'do_signup.php',
          data:
          {
            signup:1,
            name:$("#name").val(),
            username:$("#username").val(),
            password:$("#password").val(),
            email:$("#email").val(),
            gender:$("input[name='gender']:checked").val()
          },
          success:function(response)
          {
            if(response.indexOf('@0^/s&d~v~x2LiN?^k+ZJ[+Nk1QK+b') >= 0)
            {
              window.location.href="index.php";
              $("#err").text("*Signup success.");
            }
            else
            {
              $("#err").text("*Signup failed.");
            }
          }
        });
      }
    })
  })
</script>

</body>
</html>