<?php
require_once './db.php';
session_start();
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
}

if (isset($_POST['new_password']) && isset($_POST['confirm_new_password'])
    && $_POST['new_password'] === $_POST['confirm_new_password']
    && $_POST['new_password'] !== '' && $_POST['confirm_new_password'] !== ''
    && isset($user_id)) {
    $new_password = $_POST['new_password'];
    $_SESSION['is_new_user'] = 0;
    if (strlen($new_password) < 6) {
        $error_message = "Vui lòng nhập mật khẩu có độ dài lớn hơn 6 ký tự";
    } else {
        $data = change_password_first_time($new_password, $user_id);
        if ($data['code'] === 0) {
            header('Location: login.php');
        } else if ($data['code'] === 1) {
            $error_message = $data['error'];
        }
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
  <body style="height: 100vh;" >
  <?php require_once './navbar.php'?>
      <div class="d-flex justify-content-center align-items-center h-100">
    <form class="col-4 border" action="change_password_first_time.php" method="POST">
        <h1 class="text-center ">Change password</h1>
        <div class="text-danger">
          <?php
echo isset($error_message) ? $error_message : '';
?>
        </div>
        <div class="form-group">
          <label for="new_password">Password: </label>
          <input
            id="new_password"
            class="form-control"
            placeholder="Enter your password"
            type="password"
            name="new_password"
          />
        </div>
        <div class="form-group">
          <label for="new_password_confirm">
            Repeat password:
          </label>
          <input
            id="confirm_new_password"
            class="form-control"
            placeholder="Repeat your password"
            type="password"
            name="confirm_new_password"
          />
        </div>
        <div class="form-row">
          <div class="col">
            <button type="submit" class="btn btn-success btn-block">
              Change password
            </button>
          </div>
          <div class="col">
            <a href="/home.php"> <button class="btn btn-success btn-block">Or go back to home</button></a>
          </div>
        </div>
      </form>
    </div>
  </body>
</html>
