var matchMaking;

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
      // jason = $.parseJSON(response);
      // if(!jason.errormessage)
      // {
      //   matchmaking = setInterval(matchMaking(user_id),1500);  
      // }
      // else if(jason.errormessage === "Already matchmaking.")
      // {

      // }
      // else
      // {

      // }
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
      // jason = $.parseJSON(response);
      // if(!jason.errormessage)
      // {}
      // clearInterval(matchmaking);
    }
  })
}

function matchMaking(user_id)
{
  $.ajax
  ({
    type:'post',
    url:"../ajax/ajax_matchmaking.php",
    data:{
          user_id:user_id
        },
    success:function(data)
    {
      // jason = $.parseJSON(response);
      // if(jason.gametype)
      // {
      //   window.location.href= jason.gametype.".php";
      // }
    }
  })
}