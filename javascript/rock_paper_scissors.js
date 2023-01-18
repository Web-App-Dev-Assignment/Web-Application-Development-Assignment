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
      $("#rpsWrapper").hide();
      $("#darkLayer").show();
      document.getElementById("gameText").innerHTML = "Waiting for opponent to make a move"+ '<span id="animatedDots" class="animatedDots" ></span>';
      interval = setInterval(function()
          { 
            rock_paper_scissors(user_id, game_id)}
            ,1500);  
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
        jason = $.parseJSON(response);
        
        if(jason.gameStatus)
        {          
          clearInterval(interval);
          $('#gameText').text(jason.gameStatus);

          setTimeout(function()
          {
            interval = setInterval(function()
            { 
              isReady(user_id, game_id)
            }
              ,1500);  
          }
            , 3000);
          
        }
        
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