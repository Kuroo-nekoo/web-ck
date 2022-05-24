<?php
require_once "./db.php";
require_once "./common.php";
require_once "./email.php";

session_start();
print_r($_SESSION);
if (isset($_SESSION['is_new_user'])) {
    $is_new_user = $_SESSION['is_new_user'];
    check_new_user($is_new_user);
}

if (isset($_POST['email']) && isset($_POST['phone_number']) && isset($_POST['full_name']) && isset($_POST['date_of_birth']) && isset($_POST['address'])) {
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $full_name = $_POST['full_name'];
    $date_of_birth = $_POST['date_of_birth'];
    $address = $_POST['address'];
    echo $date_of_birth;
    echo date('Y-m-d');

    $data = register($phone_number, $email, $full_name, $date_of_birth, $address);
    if ($data['code'] === 0 && $data['username'] !== null && $data['password'] !== null) {
        $subject = "Username and Password: ";
        $body = "Tài khoản: " . $data['username'] . "<br/>" . "Mật khẩu: " . $data['password'];
        $email_address = $email;
        send_email($subject, $body, $email_address);
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
    <link rel="stylesheet" href="" />
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
    <link rel="stylesheet" href="./main.css" />
    <script src="./script.js"></script>
  </head>
  <body>
    <?php include_once './navbar.php'?>
    <div class="d-flex justify-content-center align-items-center">
      <form class="col-md-4 border" method="POST" action="register.php">
      <div class="text-danger h5">
        <?php if (isset($error_message) && !empty($error_message)) {
    echo $error_message;
}
?></div>
        <h1>Đăng ký tài khoản</h1>
        <div class="form-group">
          <label for="phone_number">Số điện thoại: </label>
          <input
            id="phone_number"
            class="form-control"
            placeholder="Số điện thoại"
            type="text"
            name="phone_number"
          />
        </div>
        <div class="form-group">
          <label for="email">Email: </label>
          <input
            id="email"
            class="form-control"
            type="email"
            placeholder="Email"
            name="email"
          />
        </div>
        <div class="form-group">
          <label for="lastname">Họ: </label>
          <input
            type="text"
            id="full_name"
            placeholder="Họ và tên"
            class="form-control"
            name="full_name"
          />
        </div>
        <div class="form-group">
          <label for="date_of_birth">Ngày/tháng/năm sinh: </label>
          <input type="date" class="form-control" id="date_of_birth" name="date_of_birth">
        </div>
        <div class="form-group">
          <label for="address">Địa chỉ: </label>
          <input type="text" class="form-control" id="address" placeholder="Số nhà,tên đường,...." name="address">
        </div>
        <div class="form-group">
          <label for="frontsideimg">Ảnh mặt trước CMND: </label>
          <input
            type="file"
            accept="image/*"
            name="frontsideimg"
            id="frontsideimg"
            onchange="readURL(this, '#front');"
          />
          <img id="front" />
        </div>
        <div class="form-group">
          <label for="backsideimg">Ảnh mặt sau CMND: </label>
          <input
            type="file"
            accept="image/*"
            name="backsideimg"
            id="backsideimg"
            onchange="readURL(this, '#back');"
          />
          <img id="back"/>
        </div>
        <button type="submit" class="btn btn-success btn-block">Đăng ký</button>
      </form>
    </div>
  </body>
</html>
