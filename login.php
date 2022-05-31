<?php
require_once './db.php';
require_once './common.php';

session_start();
if (isset($_SESSION['is_admin'])) {
    header('Location: ./admin.php');
}

if (isset($_SESSION['user_id'])) {
    header('Location: ./user.php');
}

if (isset($_SESSION['is_new_user'])) {
    $is_new_user = $_SESSION['is_new_user'];
    check_new_user($is_new_user);
}

if (isset($_POST['username']) && $_POST['password'] && !empty($_POST['username']) && !empty($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($_POST['username'] == 'admin' && $_POST['password'] == 123456) {
        $_SESSION['is_admin'] = true;
        header('Location: admin.php');
    } else {
        if (strlen($username) !== 10) {
            $error_message = "Vui lòng nhập đúng định dạng của tên đăng nhập";
        } else if (strlen($password) < 6) {
            $error_message = "Vui lòng nhập mật khẩu có độ dài ít nhất 6 ký tự";
        } else {
            $data = login($username, $password);

            if (isset($data['activated_state']) && $data['activated_state'] === "vô hiệu hóa") {
                $error_message = "tài khoản này đã bị vô hiệu hóa, vui lòng liên hệ tổng đài 18001008";
            } else if (isset($data['is_locked']) && $data['is_locked'] === 1) {
                $error_message = "Tài khoản đã bị khóa do nhập sai mật khẩu nhiều lần, vui lòng liên hệ quản trị viên để được hỗ trợ";
                $is_locked = 1;
            } else if ($data['code'] === 0) {
                $is_new_user = $data['data']['IS_NEW_USER'];
                unset($_SESSION['user_id']);
                unset($_SESSION['is_new_user']);
                unset($_SESSION['activated_state']);
                $_SESSION['user_id'] = $data['data']['USER_ID'];
                $_SESSION['is_new_user'] = $data['data']['IS_NEW_USER'];
                $_SESSION['activated_state'] = $data['data']['ACTIVATED_STATE'];

                if ($is_new_user === 1) {
                    header('Location: change_password_first_time.php');
                } else {
                    header('location: user.php');
                }
            } else if ($data['code'] === 1) {
                if (isset($data['abnormal_login_count']) && $data['abnormal_login_count'] === 1) {
                    $_SESSION['temp_lock_time'] = time();
                } else {
                    $error_message = $data['error'];
                }
            }
        }
    }

}

if (isset($_SESSION['temp_lock_time'])) {
    if ((time() - $_SESSION['temp_lock_time'] < 60)) {
        $is_temp_locked = 1;
        $error_message = 'Tài khoản đã bị khóa tạm thời';
    } else {
        unset($_SESSION['temp_lock_time']);
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
    <link rel="stylesheet" href="./style.css" />
  </head>
  <body >
    <?php include './navbar.php'?>
    <div class="d-flex justify-content-center align-items-center h-100 relative">
      <?php if (isset($is_locked) && $is_locked === 1) {?>
        <div class="alert alert-danger" role="alert">
          <?php echo $error_message ?>
        </div>
      <?php } else if (isset($is_temp_locked) && $is_temp_locked === 1) {?>
        <div class="alert alert-danger"><?php echo $error_message ?></div>
        <?php } else if (isset($data['activated_state']) && $data['activated_state'] === "vô hiệu hóa") {?>
          <div class="alert alert-danger"><?php echo $error_message ?></div>
        <?php } else {?>
      <form class="col-4 border main" action="login.php" method="POST" >
        <h1>Đăng nhập:</h1>
        <div class="text-danger h-6">
          <?php
if (isset($error_message) && strlen($error_message) !== 0) {
    echo $error_message;
}
    ?>
        </div>
        <div class="form-group">
          <label for="username">Tên đăng nhập: </label>
          <input
            id="username"
            class="form-control"
            placeholder="Tên đăng nhập"
            type="text"
            name="username"
          />
        </div>
        <div class="form-group">
          <label for="username">Mật khẩu: </label>
          <input
            id="password"
            class="form-control"
            placeholder="Mật khẩu"
            type="password"
            name="password"
          />
        </div>
        <button type="submit" class="btn btn-success btn-block mb-3">
          Đăng nhập
        </button>
        <div class="text-right">Chưa có tài khoản đăng ký <a href="./register.php">tại đây</a></div>
        <div class="text-right"><a href="./forgot_password.php">Quên mật khẩu</a></div>
      </form>
      <?php }?>
    </div>
  </body>
</html>
