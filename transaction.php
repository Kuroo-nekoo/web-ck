<?php
require_once "./db.php";
require_once "./common.php";
require_once "./email.php";
session_start();

$type='transaction';
$conn = connect_database();
if (!$_SESSION['user_id']) {
  header('Location: login.php');
}
$user_id = $_SESSION['user_id'];


if (isset($_POST['phone_number']) && isset($_POST['money'] )&& isset($_POST['fee_transaction']) && isset($_POST['content'] )){
    $content= $_POST['content'];
    $who_pay = $_POST['fee_transaction'];
    $phone_number= $_POST['phone_number'];
    $money= $_POST['money'];
    $sql = "Select * from  account WHERE PHONE_NUMBER = ?";    
    $stm = $conn->prepare($sql);
    $stm->bind_param('s',$phone_number);
    if (!$stm->execute()) {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    $result = $stm->get_result();
    $row= $result->fetch_assoc();
    $old_money = $row['BALANCE'];// old money of receiver
    $new_money = $old_money + $money; // new money of receiver
    $email= $row['EMAIL'];
    $fee_transaction =$money*5/100;
    // money of sender

    $sql0 = "Select * from  account WHERE USER_ID =?";    
    $stm0 = $conn->prepare($sql0);
    $stm0->bind_param('i',$user_id);
    if (!$stm0->execute()) {
        echo "Error: " . $sql0 . "<br>" . $conn->error;
    }

    $result0 = $stm0->get_result();
    $row0= $result0->fetch_assoc();
    $old_money0 = $row0['BALANCE'];
    if($_POST['money'] > 5000000){
      $is_allow =0;
   }
   
   $is_allow=1;
    if ($who_pay ==='sender'){ 
      // update money for receiver
        $sql1 = "Update account set BALANCE= ?+? where phone_number =?";
        $stm1 = $conn->prepare($sql1);
        $stm1->bind_param('dds',$old_money,$money,$phone_number);
        if (!$stm1->execute()) {
            echo "Error: " . $sql1 . "<br>" . $conn->error;
        }
        // update money for sender
        $sql2= "update `account` set balance =?-?-? WHERE USER_ID =?";
        $new_money0= $old_money0-$money-$fee_transaction;
        $stm2= $conn->prepare($sql2);
        $stm2->bind_param('dddi',$old_money0,$fee_transaction,$money,$user_id);
        if (!$stm2->execute()) {
          echo "Error: " . $sql4 . "<br>" . $conn->error;
         }
        $subject = "Balance fluctuations";
        $body = "Tài khoản: + " .$money.'k' . "<br/>" . "Số dư" . $new_money."k";
        $email_address = $email;
        send_email($subject, $body, $email_address);

    }
    else{
    // check allow
     // update money for receiver
     $sql3 = "Update account set balance= ?-? where phone_number =?";
     $stm3 = $conn->prepare($sql3);
     $stm3->bind_param('dds',$new_money,$fee_transaction,$phone_number);
     if (!$stm3->execute()) {
         echo "Error: " . $sql3 . "<br>" . $conn->error;
     }
     // update money for sender
     $sql4= "update `account` set balance =? WHERE USER_ID =?";
     $new_money0= $old_money0-$money;
     $stm4= $conn->prepare($sql4);
     $stm4->bind_param('di',$new_money0,$user_id);

     if (!$stm4->execute()) {
      echo "Error: " . $sql4 . "<br>" . $conn->error;
     }

    // send mail to notice
    $subject = "Balance fluctuations";
    $body = "Tài khoản: + " .$money.'k' . "<br/>" . "Số dư" . ($new_money-$fee_transaction);
    $email_address = $email;
    send_email($subject, $body, $email_address);
   }
    
    // add to history table
    date_default_timezone_set('asia/ho_chi_minh'); //set timezone
    $date = date('y-m-d G:i:s');
    $sql5="insert into history (USER_ID, RECEIVER_PHONE,AMOUNT,TIME,IS_ALLOW,CONTENT,TYPE) values (?,?,?,?,?,?,?)";
    $stm5 = $conn->prepare($sql5);
    $stm5->bind_param('isissss', $user_id, $phone_number, $money, $date, $is_allow, $content,$type);
    if (!$stm5->execute()) {
        echo "Error: " . $sql5 . "<br>" . $conn->error;
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
    <link rel="stylesheet" href="./style.css">
    <script type="" src="http://localhost/main.js"></script>
  </head>
  <body>
    <?php include_once './navbar_user.php'?>
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
          <label for="money">Số tiền (VNĐ): </label>

          <input 
          class="form-control"
          type="text" 
          name="money" 
          id="currency-field" 
          pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" 
          data-type="currency" 
          placeholder="Số tiền">
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
          />
        </div>
        <button type="submit" class="btn btn-success btn-block">Xác nhận chuyển</button>
      </form>
    </div>
  </body>
</html>