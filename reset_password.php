<?php
require_once './db.php';
require_once './common.php';
session_start();
print_r($_SESSION);

if (isset($_SESSION['is_new_user'])) {
    $is_new_user = $_SESSION['is_new_user'];
    check_new_user($is_new_user);
}

if (isset($_SESSION['otp']['started']) && (time() - $_SESSION['otp']['started'] < 60)) {
    echo time() - $_SESSION['otp']['started'];
    if (isset($_POST['otp'])) {

        $otp = $_SESSION['otp']['otp'];
        echo $otp;
        echo $_POST['otp'];
        if ($_POST['otp'] === $otp) {
            unset($_SESSION['otp']);
            header('Location: change_password_first_time.php');
        }
    }
} else {
    header('Location: forgot_password.php');
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
      <form class="col-4" action="reset_password.php" method="POST">
        <h1>Nhập mã OTP</h1>
        <div class="form-group">
          <label for="otp">Nhập mã: </label>
          <input
            id="otp"
            class="form-control"
            placeholder="Mã OTP"
            type="text"
            name="otp"
          />
        </div>
        <button type="submit" class="btn btn-success btn-block mb-3">
			Xác nhận
        </button>
      </form>
    </div>
  </body>
</html>
