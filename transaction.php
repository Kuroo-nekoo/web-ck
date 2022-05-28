<?php
require_once "./db.php";
require_once "./common.php";
require_once "./email.php";
session_start();
$conn = connect_database();
if (isset($_POST['phone_number']) && isset($_POST['money'])){
    $phone_number= $_POST['phone_number'];
    $money= $_POST['money'];
    $sql = "Select * from  acount WHERE PHONE_NUMBER = ?";    
    $stm = $conn->prepare($sql);
    $stm->bind_param('s',$phone_number);
    if (!$stm->execute()) {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    $result = $stm->get_result();
    $row= $result->fetch_array();
    $old_money = $row['BALANCE'];
    $new_money = $old_money + $money;
    $name= $row['Name'];
    $email= $row['EMAIL'];

    $sql1 = "Update account set BALANCE= ? where phone_number =?";
    $stm1 = $conn->prepare($sql1);
    $stm1->bind_param('ds',$new_money,$phone_number);
    if (!$stm1->execute()) {
        echo "Error: " . $sql1 . "<br>" . $conn->error;
    }
    // send mail to notice
    $subject = "Balance fluctuations";
    $body = "Tài khoản: + " .$money.'k' . "<br/>" . "Số dư" . $new_money;
    $email_address = $email;
    send_email($subject, $body, $email_address);

    // add to history table
    date_default_timezone_set('Asia/Ho_Chi_Minh');           
    $date = date('Y-m-d H:i:s',time());
    $sql2="insert into history (RECEIVER_USER, RECEIVER_PHONE,AMOUNT,TIME) values (?,?,?,?)";
    $stm2 = $conn->prepare($sql2);
    $stm2->bind_param('ssds',$name,$phone_number,$money,$date);
    if (!$stm2->execute()) {
        echo "Error: " . $sql2 . "<br>" . $conn->error;
    }
  }
?>$
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
  </head>
  <body>
    <?php include_once './navbar.php'?>
    <div class="d-flex justify-content-center align-items-center">
      <form class="col-md-4 border" action="transaction.php" method="post">
      <div class="text-danger h5">
       </div>
        <h1>Chi tiết giao dịch </h1>
        <div class="form-group">
          <label for="phone_number">Số điện thoại: </label>
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
          <label for="money">Số tiền: </label>
          <input
            id="email"

            class="form-control"
            type="money"
            placeholder="Số tiền"
            name="money"
            require
          />
        </div>
        <div class="form-group">
          <label for="content">Nội dung=========: </label>
          <input
            type="text"
            id="content"
            placeholder="Ghi chú"
            class="form-control"
            name="content"
          />
        </div>
        <div class="form-group">
          <label for="receiver_name">Người nhận:  <?php if (isset($receiver_name) ) {
            echo $error_message;}?>
        </label>
          <input type="text" class="form-control" id="address" placeholder="Autofill" name="address">
        </div>
        <button type="submit" class="btn btn-success btn-block">Xác nhận chuyển</button>
      </form>
    </div>
  </body>
</html>