<?php
require_once './db.php';
require_once './common.php';
require_once './email.php';
session_start();
if (!$_SESSION['user_id']) {
  header('Location: login.php');
}
if (isset($_SESSION['is_new_user'])) {
    $is_new_user = $_SESSION['is_new_user'];
    check_new_user($is_new_user);
}

if (isset($_POST['email_phone_number']) && $_POST['email_phone_number'] !== '') {
    $email_phone_number = $_POST['email_phone_number'];

    $data = check_email_phone_number($email_phone_number);
    if ($data['code'] === 0) {
        if (isset($_SESSION['started']) && (time() - $_SESSION['otp']['started'] < 60)) {
            $otp = $_SESSION['otp'];
        } else {
            $otp = gen_otp();
            $_SESSION['otp'] = array('otp' => $otp, 'started' => time(), 'email_phone_number' => $email_phone_number);
            $subject = "OTP";
            $body = "Mã OTP của bạn là: $otp";
            send_email($subject, $body, $email_phone_number);
        }

        header('Location: reset_password.php');
    } else if ($data['code'] === 1) {
        $error_message = $data['error'];
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
    <link rel="stylesheet" href="./register.css" />
  </head>
  <body>
    <?php include './navbar.php'?>
    <div class="d-flex justify-content-center align-items-center">
      <form class="col-4 border" action="forgot_password.php" method="POST">
        <div class="text-danger h6">
          <?php
if (isset($error_message) && !empty($error_message)) {
    echo $error_message;
}
?>
        </div>
        <h1>Quên mật khẩu:</h1>
        <div class="form-group">
          <label for="otp">Email:</label>
          <input
            id="email_phone_number"
            class="form-control"
            placeholder="Email"
            type="text"
            name="email_phone_number"
          />
        </div>
        <button type="submit" class="btn btn-success btn-block mb-3">
			Xác nhận
        </button>
      </form>
    </div>
  </body>
</html>

