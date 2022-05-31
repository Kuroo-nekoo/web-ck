<?php
require_once './common.php';
require_once './db.php';

session_start();
if (!isset($_SESSION['is_admin']))  
  header('Location: ./login.php');

if (get_list_history()['code'] == 0) {
    $list_history = get_list_history()['data'];
} else {
    $list_history = [];
}

if (get_list_history_sort_date('sort_date_created')['code'] == 0) {
    $list_history_sort_date_created = get_list_history_sort_date('sort_date_created')['data'];
} else {
    $list_history_sort_date_created = [];
}

if (get_list_history_sort_date('sort_date_locked')['code'] == 0) {
  $list_history_sort_date_locked = get_list_history_sort_date('sort_date_locked')['data'];
} else {
  $list_history_sort_date_locked = [];
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>admin</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous" />
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="/style.css">
  <script src="main.js"></script>
</head>

<body>

    <?php include './navbar_admin.php'?>
    <div class="container">
        <div class="row">
            <h3 class="">Danh sách các giao dịch cần duyệt</h3>

            <div class="table-responsive table-wrapper-scroll-y my-custom-scrollbar">
                <table class="table table-bordered table-striped mb-0 table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Số tiền</th>
                            <th>Thời gian</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($list_history as $history):
                            if($history['IS_ALLOW'] == 0): ?>
                            <tr class='clickable-row' data-toggle="tooltip" data-placement="top" title="Xem thông tin" onclick ="getHistoryInfo(<?php echo $history['ID']?>)" data-href="./history_info.php">
                                <td><?php echo $history['ID'] ?></td>
                                <td id='money'> <?php echo $history['AMOUNT']?></td>
                                <td><?php echo $history['TIME'] ?></td>
                                <td class='bg-warning text-dark'>chưa xử lí</td>
                            </tr>
                        <?php endif; endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    

</body>
</html>