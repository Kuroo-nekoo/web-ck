<?php
require_once "./db.php";
require_once "./common.php";

session_start();
$activated_state = $_SESSION['activated_state'];
if ($activated_state === "chờ cập nhật" || $activated_state === "chưa xác minh") {
    $error_message = "Tính năng này chỉ dành cho người dùng đã xác minh";
}
if (isset($_SESSION['is_new_user'])) {
    $is_new_user = $_SESSION['is_new_user'];
    check_new_user($is_new_user);
}

$conn = connect_database();
if (!$_SESSION['user_id']) {
    header('Location: login.php');
}
$user_id = $_SESSION['user_id'];
$error = '';
$data_db3 = array();
$type = 'nạp tiền';
// check input
$data = array();

if (isset($_POST['credit_id']) && isset($_POST['cvv']) && isset($_POST['expiration_date'])) {

    $data['credit_id'] = isset($_POST['credit_id']) ? $_POST['credit_id'] : '';
    $data['expiration_date'] = isset($_POST['expiration_date']) ? $_POST['expiration_date'] : '';
    $data['cvv'] = isset($_POST['cvv']) ? $_POST['cvv'] : '';
    $data['money'] = isset($_POST['money']) ? $_POST['money'] : '';
    if ($data['credit_id'] === '' || $data['expiration_date'] === '' || $data['cvv'] === '' || $data['money'] === '') {
        $error = 'Vui lòng điền đầy đủ thông tin';
    } else if (strlen($_POST['credit_id']) !== 6) {
        $error = 'Định dạng thẻ không hợp lệ!';
    }
    $sql0 = "Select `credit_id` from  credit";
    $result0 = $conn->query($sql0);
    $data_db0 = $result0->fetch_all();
    foreach ($data_db0 as $temp) {
        if ($data['credit_id'] === $temp) {
            break;
        }
        $error = 'Thẻ không hỗ trợ !';
    }
    $sql1 = "Select `expiration_date` from  credit";
    $result1 = $conn->query($sql1);
    $data_db1 = $result1->fetch_all();

    foreach ($data_db1 as $temp) {
        if ($data['expiration_date'] === $temp) {
            $error = '';
            break;
        } else {
            $error = 'Ngày hết hạn không trùng khớp!';
        }
    }
    $sql2 = "Select `cvv` from  credit";
    $result2 = $conn->query($sql2);
    $data_db2 = $result2->fetch_all();
    foreach ($data_db2 as $temp) {
        if ($data['cvv'] === $temp) {
            $error = '';
            break;
        } else {
            $error = 'Cvv không hợp lệ!';}
    }
    $sql3 = "Select * from  credit";
    $result3 = $conn->query($sql3);
    $data_db3 = $result3->fetch_all();

    foreach ($data_db3 as $temp) {
        if ($data['credit_id'] === $temp[0] && $data['expiration_date'] === $temp[1] && $data['cvv'] === $temp[2]) {
            $error = '';
            break;
        } else {
            $error = "Thông tin không trùng khớp! ";
        }
    }
    // recharge
    if ($error === '') {
        if ($data['credit_id'] === $data_db3[0][0]) {
            $sql4 = "Select * from  account WHERE $user_id=?";
            $stm4 = $conn->prepare($sql4);
            $stm4->bind_param('i', $user_id);
            if (!$stm4->execute()) {
                echo "Error: " . $sql4 . "<br>" . $conn->error;
            }
            $result4 = $stm4->get_result();
            $row4 = $result4->fetch_assoc();
            $old_money = $row4['BALANCE'];
            $sql5 = "Update account set BALANCE= ?+? where $user_id =?";
            $stm5 = $conn->prepare($sql5);
            $stm5->bind_param('ddi', $old_money, $data['money'], $user_id);
            if (!$stm4->execute()) {
                echo "Error: " . $sql5 . "<br>" . $conn->error;
            }
            $error = "Nạp thành công !";
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $date = date('Y-m-d H:i:s', time());
            $sql6 = "insert into history (USER_ID,AMOUNT,TIME,TYPE) values (?,?,?,?)";
            $conn->close();
            $conn = connect_database();
            $stm6 = $conn->prepare($sql6);
            $stm6->bind_param('idss', $user_id, $data['money'], $date, $type);
            if (!$stm6->execute()) {
                echo "Error: " . $sql6 . "<br>" . $conn->error;
            }

        } else if ($data['credit_id'] === $data_db3[1][0]) {
            if ($data['money'] > 1000000) {
                $error = 'Hạn mức nạp tiền của thẻ là 1tr !';
            } else {
                $sql7 = "Select * from  account WHERE $user_id=?";
                $stm7 = $conn->prepare($sql7);
                $stm7->bind_param('i', $user_id);
                if (!$stm7->execute()) {
                    echo "Error: " . $sql7 . "<br>" . $conn->error;
                }
                $result7 = $stm7->get_result();
                $row7 = $result7->fetch_assoc();
                $old_money7 = $row7['BALANCE'];
                $sql8 = "Update account set BALANCE= ?+? where $user_id =?";
                $stm8 = $conn->prepare($sql8);
                $stm8->bind_param('ddi', $old_money, $data['money'], $user_id);
                if (!$stm8->execute()) {
                    echo "Error: " . $sql8 . "<br>" . $conn->error;
                }
                $error = "Nạp thành công !";

                // add history
                date_default_timezone_set('Asia/Ho_Chi_Minh');

                $date = date('Y-m-d H:i:s', time());
                $sql9 = "insert into history (USER_ID,AMOUNT,TIME,TYPE) values (?,?,?,?)";
                $stm9 = $conn->prepare($sql9);
                $stm9->bind_param('idss', $user_id, $data['money'], $date, $type);
            }
            if (!$stm9->execute()) {
                echo "Error: " . $sql9 . "<br>" . $conn->error;
            }
        } else {
            $error = "Thẻ hết tiền !";
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
      src="http://code.jquery.com/jquery-3.3.1.slim.min.js"
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
    <?php if (isset($error_message) && $error_message !== "") {?>
      <div class="alert alert-danger"><?php echo $error_message ?></div>
      <?php } else {?>
    <div class="d-flex justify-content-center align-items-center">
      <form name="rechargeForm" class="col-md-4 border main" action="recharge.php" method="post" >
      <div class="text-danger h5">
       </div>
       <div class="form-group">
          <h1>Nạp tiền </h1>
          <label for="credit_id">Số thẻ tín dụng: </label>
          <input
            id="credit_id"
            class="form-control"
            placeholder="Số thẻ tín dụng"
            type="text"
            name="credit_id"
            require
          />
        </div>
        <div class="form-group">
          <label for="expiration_date">Ngày hết hạn : </label>
          <input
            id="expiration_date"

            class="form-control"
            type="expiration_date"
            name="expiration_date"
            placeholder ="YYYY-MM-DD"
            require
          />
          <div class="form-group">
          <label for="cvv">CVV : </label>
          <input
            id="CVV"

            class="form-control"
            type="cvv"
            name="cvv"
            placeholder ="***"
            require
          />
        </div>
        <div class="form-group">
          <label for="money">Số tiền:
        </label>
          <input type="text" class="form-control" id="money" placeholder="Số tiền" name="money">
        </div>
        <div class="errorMessage my-3"><span id="errMessage">  <?php echo isset($error) ? $error : ''; ?></span></div>
        <button type="submit" class="btn btn-success btn-block" name="contact_action" >Xác nhận nạp thẻ</button>
      </form>
    </div>
    <?php }?>
  </body>
</html>