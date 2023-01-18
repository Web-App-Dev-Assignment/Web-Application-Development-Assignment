function insertMessage(chat_text, user_id, game_id)
{
  $.ajax
  ({
    type:'POST',
    url:'../ajax/ajax_insertmessage.php',
    data:
    {
      chat_text:chat_text,
      user_id:user_id,
      game_id:game_id
    },
    success:function()
    {
      $("#chatInput").val("");
    }
  })
}

function displayMessage(game_id)
{
  $.ajax
  ({
    type:'POST',
    url:'../ajax/ajax_displayMessage.php',
    data:
    {
      game_id:game_id
    }
  })
}