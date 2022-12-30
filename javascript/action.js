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
    },
    success:function()
    {
      //$("#chatInput").val("");
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
      //console.log(response);
      jason = $.parseJSON(response);
      //console.log(jason.table);
      var table = $("#edit_table");
      //var table = $("#table");
      //$(table).html(jason.table);

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
    //var table_id = '#'+table_name;
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
        console.log("0");
        var column_index = $(this.parentNode).index();
        //var table_id = '#'+table_name;
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
          //console.log(unique_field + " = " + column_field);
        }

        action('update', table_name, unique_field , prev_input, column_field, this.value);

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
          //console.log("enter pressed.");
          
          input.onblur=function(){
            console.log("1");

            var column_index = $(this.parentNode).index();
            //var table_id = '#'+table_name;
            var table = $(this).closest(table_id);
            var column_field = $(table).find('th:nth-child('+(column_index+1)+')').text();

            var row = $(this).closest("tr");
            var unique_column = row.find('td:nth-child('+unique_index+')');

            /*
            var text_area = unique_column.find('textarea');
            if (text_area.length != 0)
            {
              console.log(text_area.val());
            }
            */
            var unique_text;
            if(unique_field != column_field)
            {
              unique_text = unique_column.text();
            }
            else
            {
              unique_text = this.value;
              //console.log(unique_field + " = " + column_field);
            }
            
            console.log("Index is " + unique_index + " ,table name is " + table_id + " ,unique_text is " + unique_text + " ,text value is " + this.value);

            action('update', table_name, unique_field , prev_input, column_field, this.value);

            this.parentNode.innerHTML=this.value;
          }
  
          input.blur();
      }
      else if(e.key === "Escape")
      {
        console.log('esc pressed.');;
        
        input.onblur=function(){
          console.log("2");
          this.parentNode.innerHTML=prev_input;
        }
        input.blur();
      }
    });
  });
}