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

function getUserInfo(user_id) {
  location.replace("/user_info.php?user_id=" + user_id);
}


function verification(user_id) {
  $('.modal-title').html('Bạn có chắc xác minh tài khoản ?');
}