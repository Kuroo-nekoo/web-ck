<?php
require_once "./db.php";
require_once "./common.php";
require_once "./email.php";
session_start();

if (!$_SESSION['user_id']) {
    header('Location: login.php');
}

$activated_state = $_SESSION['activated_state'];
if ($activated_state === "chưa xác minh" || $activated_state === "chờ cập nhật") {
    $error_message = "Tính năng này chỉ dành cho người dùng đã xác minh";
}
$user_id = $_SESSION['user_id'];

if (isset($_POST['phone_number']) && isset($_POST['money']) && isset($_POST['fee_transaction']) && isset($_POST['content'])) {
    $content = $_POST['content'];
    $who_pay = $_POST['fee_transaction'];
    $phone_number = $_POST['phone_number'];
    $money = str_replace('.', '', $_POST['money']);
    $receiver = get_user_data_by_phone($phone_number)['data'];
}

if (isset($_POST['is_confirmed'])) {
    $result = transaction($user_id, $phone_number, $money, $content, $who_pay);
    echo $result['code'];
    if ($result['code'] == 0) {
        if (isset($_SESSION['started']) && (time() - $_SESSION['otp']['started'] < 60)) {
            $otp = $_SESSION['otp'];
        } else {
            $otp = gen_otp();
            $email_phone_number = get_user_data($user_id)['email'];
            $_SESSION['otp'] = array('otp' => $otp, 'started' => time(), 'email_phone_number' => $email_phone_number);
            $subject = "Xác nhận chuyển tiền";
            $body = "Mã OTP của bạn là: $otp";
            send_email($subject, $body, $email_phone_number);
        }
        header('Location: confirm_transfer.php');
    } else {
        $error_message = $result['error'];
    }
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
      integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
      crossorigin="anonymous"
    />
    <script
      src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
      integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
      crossorigin="anonymous"
    ></script>
    <script
      src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"
      integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
      crossorigin="anonymous"
    ></script>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"
      integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
      crossorigin="anonymous"
    ></script>
    <link rel="stylesheet" href="./style.css">
    <script type="" src="./main.js"></script>
  </head>
  <body>
    <?php include_once './navbar_user.php'?>
    <?php if (isset($error_message) && $error_message !== ""): ?>
      <div class="alert alert-danger"><?php echo $error_message ?> </div>
    <?php endif;?>
    <div class="d-flex justify-content-center align-items-center">
      <form id='myForm' class="col-md-4 border main">
          <h1 class='ml-5'>Chuyển tiền</h1>
          <div class="form-group">
            <label for="phone_number">Số điện thoại người nhận: </label>
            <input
              id="phone_number"
              class="form-control"
              placeholder="Số điện thoại"
              type="text"
              name="phone_number"
              require
            />
          </div>
          <div class="form-group">
            <label for="money">Số tiền (VNĐ): </label>
            <input 
              class="form-control"
              type="text" 
              id="money"
              name="money" 
              data-type="currency" 
              placeholder="Số tiền"
              require>
          </div>
          <div class="form-group">
            <label for="fee_transaction">Phí chuyển (5%): </label>
            <select name="fee_transaction" id="fee_transaction" class="custom-select" >
                <option value="sender">Người gửi trả</option>
                <option value="receiver">Người nhận trả</option>
            </select>
          </div>
          <div class="form-group">
            <label for="content">Ghi chú: </label>
            <input
              type="text"
              id="content"
              placeholder="Ghi chú"
              class="form-control"
              name="content"
              require
            />
          </div>
          <div class="form-group float-right">
            <button id='submit_form' type="submit" class="btn btn-primary mr-2">Chuyển</button>
            <!-- <button id='modal_form' type="button" class="btn btn-primary mr-2" data-toggle="modal" data-target="#modal">Chuyển</button> -->
          </div>
          
      </form>
    </div>

    <div class="modal fade" id="modal_transfer" tabindex="-1" role="dialog" aria-labelledby="modal-label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-label">Xác nhận chuyển tiền</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container">
                      <div class="row">
                        <div class="col-md-6">
                          <p>Số tiền: <span class='money'><?php echo intval($money)*1000?></span></p>
                        </div>  
                        <div class="col-md-6">
                          <p>Phí: <span class='money'><?php echo intval($money)*0.05*1000?></span></p>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <p>Người nhận: <span><?php echo $receiver['FULL_NAME']?></span></p>
                        </div>
                        <div class="col-md-6">
                        </div>
                      </div>
                    </div>
        
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="agree">Xác nhận</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                </div>
            </div>
        </div>
    </div>
  </body>
</html>