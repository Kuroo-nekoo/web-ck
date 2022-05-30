function readURL(input, placeToRender, placeToHide) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function (e) {
      $(placeToHide).hide();
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
  $('.modal-title').html('Xác minh tài khoản');
  $('.modal-body').html('Bạn có muốn xác minh cho tài khoản này?');
  var url = window.location.href
  console.log(url)
  $('#accept').click(function () {
    location.replace(url + '&is_accepted=1');
  })
}

function rejected(user_id) {
  $('.modal-title').html('Vô hiệu hóa tài khoản');
  $('.modal-body').html('Bạn có muốn vô hiệu hóa tài khoản này?');
  var url = window.location.href
  console.log(url)
  $('#accept').click(function () {
    location.replace(url + '&is_rejected=1');
  })
}

function additional(user_id) {
  $('.modal-title').html('Yêu cầu bổ sung thông tin');
  $('.modal-body').html('Bạn có muốn yêu cầu tài khoản này bổ sung thông tin?');
  var url = window.location.href
  console.log(url)
  $('#accept').click(function () {
    location.replace(url + '&is_added=1');
  })
}

function unlock(user_id) {
  $('.modal-title').html('Mở khóa tài khoản');
  $('.modal-body').html('Bạn có muốn mở khóa cho tài khoản này?');
  var url = window.location.href
  console.log(url)
  $('#accept').click(function () {
    location.replace(url + '&is_unlock=1');
  })
}