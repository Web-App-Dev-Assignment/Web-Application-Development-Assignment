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

function rock_paper_scissors(user_id, game_id, move)
{
  $.ajax
  ({
    type:'post',
    url:"../ajax/ajax_rock_paper_scissors.php",
    data:{
          user_id:user_id,
          game_id:game_id,
          move:move
        },
    success:function(response)
    {
      try
      {
        jason = $.parseJSON(response);
        if(jason.gametype)
        {
          clearInterval(interval);
          //window.location.href= jason.gametype+".php";
        }
      }
      catch(err)
      {
        console.error(err);
      }
    }
  })
}