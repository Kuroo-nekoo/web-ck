function readURL(input, placeToRender) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function (e) {
      $(placeToRender).attr("src", e.target.result);
      $(placeToRender).addClass("img_show");
    };

    reader.readAsDataURL(input.files[0]);
  }
}
