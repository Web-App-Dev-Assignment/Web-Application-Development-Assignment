function updateLastOnline(user_id)
 {
    $.ajax(
    {
      type:'post',
      url:"../ajax/ajax_updateLastOnline.php",
      data:{
            user_id:user_id
          }
    })
 }