<?php
require_once "./db.php";
require_once "./common.php";
session_start();
$activated_state = $_SESSION['activated_state'];
if ($activated_state === "chưa xác minh") {
    $error_message = "Tính năng này chỉ dành cho người dùng đã xác minh";
}

$conn = connect_database();
$sql = "Select * from  history where type ='transaction'";
$result = $conn->query($sql);

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
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="./main.js"></script>
  </head>
  <body>
  <?php require_once 'navbar_admin.php'?>
  <?php if (isset($error_message) && $error_message !== "") {?>
    <div class="alert alert-danger">echo $error_message</div>
  <?php } else {?>
    <div class="container">
        <h2 style="text-align: center">Transaction History</h2>

       <br>
       <div>
    <table class = "table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Người gửi</th>
                <th>SĐT người nhận</th>
                <th>Số tiền</th>
                <th>Thời gian</th>

            </tr>
        </thead>
        <tbody>

        <?php

    while ($row = $result->fetch_assoc()) {
        ?>
    <tr>
        <td><?php echo $row['ID']; ?></td>
        <td><?php echo $row['USER_ID']; ?></td>
        <td><?php echo $row['RECEIVER_PHONE']; ?></td>
        <td id='money'><?php echo $row['AMOUNT']; ?></td>
        <td><?php echo $row['TIME']; ?></td>


    </tr>
    <?php
}
    $conn->close();
    $conn = connect_database();

    $sql1 = "Select * from  history where type ='recharge'";
    $result1 = $conn->query($sql1);
    ?>

 </table>

 <div class="container">
        <h2 style="text-align: center">Recharge History</h2>

       <br>
       <div>
    <table class = "table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Người nạp</th>
                <th>Số tiền</th>
                <th>Thời gian</th>

            </tr>
        </thead>
        <tbody>

        <?php

    while ($row1 = $result1->fetch_assoc()) {
        ?>
    <tr>
        <td><?php echo $row1['ID']; ?></td>
        <td><?php echo $row1['USER_ID']; ?></td>
        <td><?php echo $row1['AMOUNT']; ?></td>
        <td><?php echo $row1['TIME']; ?></td>


    </tr>
    <?php
}

    ?>
 </table>

 <?php }?>
 </body>
</html>