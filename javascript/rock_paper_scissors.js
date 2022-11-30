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
      console.log(move + " set");
      $("#rpsWrapper").hide();
      $('.darkLayer').attr('style', '');
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
      try
      {
        jason = $.parseJSON(response);
        switch(jason.gameStatus)
        {
          case "Win":
            console.log("Win");
            break;
          case "Lose":
            console.log("Lose");
            break;
          case "Win match":
            console.log("Win match");
            break;
          case "Lose match":
            console.log("Lose match");
            break;
          default:
            break;
        }
        
        
        //if(jason.gameStatus === "Win")
        if(jason.gameStatus)
        {
          clearInterval(interval);
          //ready();
          interval = setInterval(function()
          { 
            isReady(user_id)}
            ,1500);  
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

function isReady()
{
  $.ajax
  ({
    type:'post',
    url:"../ajax/ajax_is_ready_rps.php",
    data:{
          user_id:user_id
        },
    success:function(response)
    {
      try
      {
        jason = $.parseJSON(response);
        if(jason.isReady)
        {
          $("#rpsWrapper").show();
          $('.darkLayer').attr('style', 'display: none');
          //resume()
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