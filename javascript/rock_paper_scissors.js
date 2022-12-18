var interval;

function setMove(user_id, game_id, move)
{
  $.ajax
  ({
    type:'post',
    url:"../ajax/ajax_set_move_rps.php",
    data:{
          user_id:user_id,
          move:move
        },
    success:function(response)
    {
      console.log(response);
      console.log(move + " set");
      $("#rpsWrapper").hide();
      $("#darkLayer").show();
      document.getElementById("gameText").innerHTML = "Waiting for opponent to make a move"+ '<span id="animatedDots" class="animatedDots" ></span>';
      //$('#gameText').text('Waiting for opponent to make a move' + '<span id="animatedDots" class="animatedDots" ></span>');
      //$('.darkLayer').attr('style', '');
      //$('#gameText').text("Waiting for opponent to make a move");
      //$('#animatedDots').show();
      interval = setInterval(function()
          { 
            rock_paper_scissors(user_id, game_id)}
            ,1500);  
      // try
      // {
      //   jason = $.parseJSON(response);
      //   if(jason.gametype)
      //   {
      //     window.location.href= jason.gametype+".php";
      //   }
      // }
      // catch(err)
      // {
      //   console.error(err);
      // }
    }
  })
}

function rock_paper_scissors(user_id, game_id)
{
  $.ajax
  ({
    type:'post',
    url:"../ajax/ajax_rock_paper_scissors.php",
    data:{
          user_id:user_id,
          game_id:game_id
        },
    success:function(response)
    {
      console.log(response);
      try
      {
        // console.log(response);
        jason = $.parseJSON(response);
        //console.log(jason.gameStatus);
        // switch(jason.gameStatus)
        // {
        //   case "Win":
        //     //console.log("Win");
        //     break;
        //   case "Lose":
        //     //console.log("Lose");
        //     break;
        //   case "Win match":
        //     // console.log("Win match");
        //     break;
        //   case "Lose match":
        //     // console.log("Lose match");
        //     break;
        //   default:
        //     break;
        // }
        
        
        //if(jason.gameStatus === "Win")
        
        if(jason.gameStatus)
        {
          // if(jason.gameStatus === "Win match." || jason.gameStatus === "Lose match.")
          // {
          //   redirect = true;
          // }
          // else
          // {
          //   redirect = false;
          // }
          
          clearInterval(interval);
          $('#gameText').text(jason.gameStatus);

          //need to set message to show if win or lose

          setTimeout(function()
          {
            interval = setInterval(function()
            { 
              isReady(user_id, game_id)
            }
              ,1500);  
          }
            , 3000);
          
          //window.location.href= jason.gametype+".php";
        }
        
        // else if(jason.gameStatus === "Lose")
        // {
        //   clearInterval(interval);
        //   //ready();
        //   interval = setInterval(function()
        //   { 
        //     isReady(user_id)}
        //     ,1500); 
        //   //window.location.href= jason.gametype+".php";
        // }
      }
      catch(err)
      {
        console.error(err);
      }
    }
  })
}

function isReady(user_id, game_id)
{
  $.ajax
  ({
    type:'post',
    url:"../ajax/ajax_is_ready_rps.php",
    data:{
          user_id:user_id,
          game_id:game_id
        },
    success:function(response)
    {
      try
      {
        console.log(response);
        jason = $.parseJSON(response);
        if(jason.isReady)
        {
          if(!jason.redirect)
          {
            $("#rpsWrapper").show();
            $("#darkLayer").hide();
            clearInterval(interval);
          }
          else
          {
            clearInterval(interval);
            $('#gameText').text(jason.gameStatus);
            interval = setTimeout(function()
            { 
              window.location.href= 'lobby'+'.php';}
              ,1500); 
          }
        }
      }
      catch(err)
      {
        console.error(err);
      }
    }
  })
}