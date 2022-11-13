<script>
function passwordVisibility($string) {
  var x = document.getElementById($string);
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}
</script>