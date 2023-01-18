var matchmaking;

function startMatchMaking(user_id, game_type)
{
  $.ajax
  ({
    type:'POST',
    url:'../ajax/ajax_startMatchMaking.php',
    data:
    {
      user_id:user_id,
      game_type:game_type
    },
    success:function(response)
    {
      try
      {
        jason = $.parseJSON(response);
        if(!jason.errormessage)
        {
          matchmaking = setInterval(function()
          { 
            matchMaking(user_id, game_type)}
            ,1500);  
        }
      }
      catch(err)
      {
        console.error(err);
      }
    }
  })
}
function cancelMatchMaking(user_id)
{
  $.ajax
  ({
    type:'POST',
    url:'../ajax/ajax_cancelMatchMaking.php',
    data:
    {
      user_id:user_id
    },
    success:function(response)
    {
      try
      {
        jason = $.parseJSON(response);
        if(!jason.errormessage)
        {
          clearInterval(matchmaking);
        }
      }
      catch(err)
      {
        console.error(err);
      }
      
    }
  })
}

function matchMaking(user_id, game_type)
{
  $.ajax
  ({
    type:'post',
    url:"../ajax/ajax_matchmaking.php",
    data:{
          user_id:user_id,
          game_type:game_type
        },
    success:function(response)
    {
      try
      {
        jason = $.parseJSON(response);
        if(jason.gametype)
        {
          window.location.href= jason.gametype+".php";
        }
      }
      catch(err)
      {
        console.error(err);
      }
    }
  })
}