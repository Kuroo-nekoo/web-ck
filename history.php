<?php
require_once "./db.php";
require_once "./common.php";
session_start();
if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
}
$activated_state = $_SESSION['activated_state'];
if ($activated_state === "chưa xác minh" || $activated_state === "chờ cập nhật") {
    $error_message = "Tính năng này chỉ dành cho người dùng đã xác minh";
}
$user_id = $_SESSION['user_id'];
if(get_history_user($user_id,'chuyển tiền')['code'] == 0){
    $history_transfer = get_history_user($user_id,'chuyển tiền')['data'];
}else {
    $history_transfer = [];
}

if(get_history_user($user_id,'nạp tiền')['code'] == 0){
  $history_recharge = get_history_user($user_id,'nạp tiền')['data'];
}else {
  $history_recharge = [];
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
    <link rel="stylesheet" type="text/css" href="./style.css">
    <script src="./main.js"></script>
  </head>
  <body>
  <?php require_once 'navbar_user.php'?>
  <?php if (isset($error_message) && $error_message !== "") {?>
    <div class="alert alert-danger"><?php echo $error_message ?></div>
  <?php } else {?>
    <div class="container">
      <div class="main">
      <h2 style="text-align: center">Lịch sử rút tiền</h2>
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


        <?php if ($history_transfer): ?>
          <?php foreach ($history_transfer as $history):
                ?>
            <tr>
                <td><?php echo $history['ID']; ?></td>
                <td><?php echo $history['USER_ID']; ?></td>
                <td><?php echo $history['RECEIVER_PHONE']; ?></td>
                <td class="money"><?php echo $history['AMOUNT']; ?></td>
                <td><?php echo $history['TIME']; ?></td>


            </tr>
          <?php endforeach; ?>
        <?php endif; ?>

        </table>
        </div>
    </div>
 <div class="container">
   <div class="main">
   <h2 style="text-align: center">Lịch sử nạp tiền</h2>
 <table class = "table table-striped main">
        <thead>
            <tr>
                <th>ID</th>
                <th>Người nạp</th>
                <th>Số tiền</th>
                <th>Thời gian</th>


            </tr>
        </thead>
        <tbody>


<?php if ($history_recharge): ?>
  <?php foreach ($history_recharge as $history):
        ?>
    <tr>
        <td><?php echo $history['ID']; ?></td>
        <td><?php echo $history['USER_ID']; ?></td>
        <td class="money"><?php echo $history['AMOUNT']; ?></td>
        <td><?php echo $history['TIME']; ?></td>


    </tr>
  <?php endforeach; ?>
<?php endif; ?>

 </table>
 </div>
      </div>
    <?php }?>
    </body>
</html>