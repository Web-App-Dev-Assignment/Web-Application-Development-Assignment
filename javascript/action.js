function action(action, table_name, unique_column, unique_value, column, value)
{
  $.ajax
  ({
    type:'POST',
    url:'../ajax/ajax_action.php',
    data:
    {
      action:action,
      table_name:table_name,
      unique_column:unique_column,
      unique_value:unique_value,
      column:column,
      value:value
    }
  })
}

function generateTable(action, table_name)
{
  $.ajax
  ({
    type:'POST',
    url:'../ajax/ajax_action.php',
    data:
    {
      action:action,
      table_name:table_name
    },
    success:function(response)
    {
      jason = $.parseJSON(response);
      var table = $("#edit_table");

      if ($(table).length != 0 )
      {
        $(table).html(jason.table);
      }
      else
      {
        var s = document.createElement('div');
        s.setAttribute("id", "edit_table");
        document.getElementsByTagName('body')[0].appendChild(s);
        $(s).html(jason.table);
      }

      if ($("#edit_script").length != 0 )
      {
        $("#edit_script").remove();
        var s = document.createElement('script');
        s.setAttribute("id", "edit_script");
        s.text = jason.script;
        document.getElementsByTagName('body')[0].appendChild(s);
      }
      else
      {
        var s = document.createElement('script');
        s.setAttribute("id", "edit_script");
        s.text = jason.script;
        document.getElementsByTagName('body')[0].appendChild(s);
      }
    }
  })
}

function addDeleteListener(table_name, class_name, unique_index, unique_field)
{
  $(class_name).click(function() 
  {
    var row = $(this).closest("tr");
    var table = $(this).closest(table_name);
    var unique_column = row.find('td:nth-child('+unique_index+')');
    var unique_text = unique_column.text();
    row.remove();
    action('delete', table_name, unique_field , unique_text, '', '');
    console.log(unique_text);
});
}

function addUpdateListener(table_name, unique_index, unique_field)
{
  var table_id = '#'+table_name;
  document.querySelectorAll(table_id+" tr:nth-child(1n+2) td:nth-child(1n+2)").forEach(function(node){
	
    var input;
    var prev_input;
    node.ondblclick=function()
    {
      prev_input = this.innerHTML;
      input=document.createElement("textArea");
      input.value=prev_input;
      input.onblur=function()
      {
        var column_index = $(this.parentNode).index();
        var table = $(this).closest(table_id);
        var column_field = $(table).find('th:nth-child('+(column_index+1)+')').text();

        var row = $(this).closest("tr");
        var unique_column = row.find('td:nth-child('+unique_index+')');

        var unique_text;
        if(unique_field != column_field)
        {
          unique_text = unique_column.text();
        }
        else
        {
          unique_text = this.value;
        }

        action('update', table_name, unique_field , unique_text, column_field, this.value);

        this.parentNode.innerHTML=this.value;
      }
      this.innerHTML="";
      this.appendChild(input);
      input.focus();
    }
  
    $(node).on('keydown' , function (e) 
    {
      if(e.key === "Enter" && !e.shiftKey) {
          e.preventDefault();
          
          input.onblur=function(){

            var column_index = $(this.parentNode).index();
            var table = $(this).closest(table_id);
            var column_field = $(table).find('th:nth-child('+(column_index+1)+')').text();

            var row = $(this).closest("tr");
            var unique_column = row.find('td:nth-child('+unique_index+')');

            var unique_text;
            if(unique_field != column_field)
            {
              unique_text = unique_column.text();
            }
            else
            {
              unique_text = this.value;
            }
            
            action('update', table_name, unique_field , unique_text, column_field, this.value);

            this.parentNode.innerHTML=this.value;
          }
  
          input.blur();
      }
      else if(e.key === "Escape")
      {
        input.onblur=function(){
          this.parentNode.innerHTML=prev_input;
        }
        input.blur();
      }
    });
  });
}