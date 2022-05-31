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

function getHistoryInfo(id) {
  location.replace("/history_info.php?id=" + id);
}

function browse(id) {
  var url = window.location.href
  console.log(url)
  $('#agree').click(function () {
    location.replace(url + '&is_browsed=1');
  })
  $('#disagree').click(function () {
    location.replace(url + '&is_browsed=0');
  })
}

function formatMoney() {
  if(document.getElementsByClassName("money")) {
    list_money = document.getElementsByClassName("money");
    for(let i = 0; i < list_money.length; i++) {
      var money = list_money[i].innerHTML;
      money = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(money)
      list_money[i].innerHTML = money;
    }
  }
}

function confirmTransfer(error_message) {
  if(error_message) {
    $('.modal-title').html('Thông báo');
    $('.modal-body').html(error_message);
  }
  else {
    var url = window.location.href
    console.log(url)
    $('#agree').click(function () {
      location.replace(url + '&is_confirmed=1');
    })

  }
  
}

function clickSubmit() {
  $('#modal_form').click(function () {
    $('#submit_form').click();
  })
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
$(document).ready(function () {
  $("input[data-type='currency']").on({
    keyup: function() {
      formatCurrency($(this));
    },
    blur: function() { 
      formatCurrency($(this), "blur");
    }
  });
  $("#submit_form").click(function(e) {
    e.preventDefault();
    var phone_number = $("#phone_number").val(); 
    var money = $("#money").val();
    var fee_transaction = $("#fee_transaction").val();
    var content = $("#content").val();
    var dataString = 'phone_number='+phone_number+'&money='+money+'&fee_transaction='+fee_transaction+'&content='+content;
    $.ajax({
      type:'POST',
      data:dataString,
      url:'transaction.php',
      success:function(data) {
        $('#modal_transfer').modal('show');
      }
    });
  });
  formatMoney()
});

function formatNumber(n) {
  // format number 1000000 to 1,234,567
  return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
}

function formatCurrency(input, blur) {
  // get input value
  var input_val = input.val();

  // don't validate empty input
  if (input_val === "") { return; }

  // original length
  var original_len = input_val.length;

  // initial caret position 
  var caret_pos = input.prop("selectionStart");
    
  // check for decimal
  if (input_val.indexOf(".") >= 0) {

    // get position of first decimal
    // this prevents multiple decimals from
    // being entered
    var decimal_pos = input_val.indexOf(".");

    // split number by decimal point
    var left_side = input_val.substring(0, decimal_pos);
    var right_side = input_val.substring(decimal_pos);

    // add commas to left side of number
    left_side = formatNumber(left_side);

    // validate right side
    right_side = formatNumber(right_side);
    
    // On blur make sure 2 numbers after decimal
    if (blur === "blur") {
      right_side += "00";
    }
    
    // Limit decimal to only 2 digits
    right_side = right_side.substring(0, 2);

    // join number by .
    input_val = left_side + "." + right_side;

  } else {
    // no decimal entered
    // add commas to number
    // remove all non-digits
    input_val = formatNumber(input_val);
    input_val = input_val;
    
    // final formatting
    // if (blur === "blur") {
    //   input_val += ".00";
    // }
  }

  // send updated string to input
  input.val(input_val);

  // put caret back in the right position
  var updated_len = input_val.length;
  caret_pos = updated_len - original_len + caret_pos;
  input[0].setSelectionRange(caret_pos, caret_pos);
}