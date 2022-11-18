function passwordVisibility($string) {
  var x = document.getElementById($string);
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}

function collapsible(string)//might need to refresh the height when a text has been inserted
{
  var coll = document.getElementsByClassName(string);
  var i;

  for (i = 0; i < coll.length; i++) {
    coll[i].addEventListener("click", function() {
      this.classList.toggle("active");
      var sibling = this.nextElementSibling;
      var parent = this.parentElement;
      if (parent.style.height){
        parent.style.height = null;
        sibling.style.overflowY = null;
      } else {
        parent.style.height = "50%";
        sibling.style.overflowY = "scroll";
      } 
    });
  }
}
