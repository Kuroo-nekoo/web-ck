<?php
require_once './common.php';
require_once './db.php';
if (get_users_data()['code'] == 0) {
    $users = get_users_data()['data'];
} else {
    $users = [];
}

if (get_users_data_sort_date('sort_date_created')['code'] == 0) {
    $users_sort_date_created = get_users_data_sort_date('sort_date_created')['data'];
} else {
    $users_sort_date_created = [];
}

if (get_users_data_sort_date('sort_date_locked')['code'] == 0) {
  $users_sort_date_locked = get_users_data_sort_date('sort_date_locked')['data'];
} else {
  $users_sort_date_locked = [];
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
  <link rel="stylesheet" href="main.css">
</head>

<body>

    <?php include './navbar.php'?>
    <div class="container">
          <h3 class="">Danh sách tài khoản chưa xác thực</h3>

          <div class="table-responsive table-wrapper-scroll-y my-custom-scrollbar">
            <table class="table table-bordered table-striped mb-0 table-hover">
              <thead>
                <tr>
                  <th>User ID</th>
                  <th>Mã số tài khoản</th>
                  <th>Tên tài khoản</th>
                  <th>Email</th>
                  <th>Trạng thái</th>

                </tr>
              </thead>
              <tbody>
                <?php if ($users): ?>
                <?php foreach ($users as $user):
    if ($user['ACTIVATED_STATE'] === 'chờ xác minh'):
    ?>
		                  <tr class='clickable-row' data-toggle="tooltip" data-placement="top" title="Xem thông tin" data-href='/user.php'>
		                    <td><?php echo $user['USER_ID'] ?></td>
		                    <td><?php echo $user['USERNAME'] ?></td>
		                    <td><?php echo $user['FULL_NAME'] ?></td>
		                    <td><?php echo $user['EMAIL'] ?></td>
		                    <td class='bg-warning'><?php echo 'Chưa xác thực' ?></td>
		                  </tr>
		                <?php endif;?>
                <?php endforeach;?>
                <?php endif;?>

              </tbody>
            </table>
          </div>

          <h3 class="mt-3">Danh sách tài khoản đã xác thực</h3>

          <div class="table-responsive table-wrapper-scroll-y my-custom-scrollbar">
            <table class="table table-bordered table-striped mb-0 ">
              <thead>
                <tr>
                    <th>User ID</th>
                    <th>Mã số tài khoản</th>
                    <th>Tên tài khoản</th>
                    <th>Email</th>
                    <th>Ngày tạo</th>
                    <th>Trạng thái</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($users_sort_date_created): ?>

                <?php foreach ($users_sort_date_created as $user):
    if ($user['ACTIVATED_STATE'] == 'đã xác minh'):
    ?>
		                  <tr>
		                    <td><?php echo $user['USER_ID'] ?></td>
		                    <td><?php echo $user['USERNAME'] ?></td>
		                    <td><?php echo $user['FULL_NAME'] ?></td>
		                    <td><?php echo $user['EMAIL'] ?></td>
		                    <td><?php echo $user['DATE_CREATED'] ?></td>
		                    <td class='bg-success'><?php echo 'Đã xác thực' ?></td>
		                  </tr>
		                <?php endif;?>

                <?php endforeach;?>
                <?php endif;?>

              </tbody>
            </table>
          </div>

          <h3 class="mt-3">Danh sách tài khoản bị vô hiệu hóa</h3>

          <div class="table-responsive table-wrapper-scroll-y my-custom-scrollbar">
            <table class="table table-bordered table-striped mb-0 ">
              <thead>
                <tr>
                    <th>User ID</th>
                    <th>Mã số tài khoản</th>
                    <th>Tên tài khoản</th>
                    <th>Email</th>
                    <th>Ngày tạo</th>
                    <th>Trạng thái</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($users_sort_date_created): ?>

                <?php foreach ($users_sort_date_created as $user):
    if ($user['ACTIVATED_STATE'] === "vô hiệu hóa"):
    ?>
		                  <tr>
		                    <td><?php echo $user['USER_ID'] ?></td>
		                    <td><?php echo $user['USERNAME'] ?></td>
		                    <td><?php echo $user['FULL_NAME'] ?></td>
		                    <td><?php echo $user['EMAIL'] ?></td>
		                    <td><?php echo $user['DATE_CREATED'] ?></td>
		                    <td class='table-secondary'><?php echo 'vô hiệu hóa' ?></td>
		                  </tr>
		                <?php endif;?>

                <?php endforeach;?>
                <?php endif;?>

              </tbody>
            </table>
          </div>

          <h3 class="mt-3">Danh sách tài khoản bị khóa</h3>

          <div class="table-responsive table-wrapper-scroll-y my-custom-scrollbar">
            <table class="table table-bordered table-striped mb-0 ">
              <thead>
                <tr>
                    <th>User ID</th>
                    <th>Mã số tài khoản</th>
                    <th>Tên tài khoản</th>
                    <th>Email</th>
                    <th>Ngày tạo</th>
                    <th>Trạng thái</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($users_sort_date_locked): ?>

                <?php foreach ($users_sort_date_locked as $user):
    if ($user['IS_LOCKED'] == 1):
    ?>
		                  <tr>
		                    <td><?php echo $user['USER_ID'] ?></td>
		                    <td><?php echo $user['USERNAME'] ?></td>
		                    <td><?php echo $user['FULL_NAME'] ?></td>
		                    <td><?php echo $user['EMAIL'] ?></td>
		                    <td><?php echo $user['DATE_LOCKED'] ?></td>
		                    <td class='bg-danger'><?php echo 'đã bị khóa' ?></td>
		                  </tr>
		                <?php endif;?>

                <?php endforeach;?>
                <?php endif;?>

              </tbody>
            </table>
          </div>
    </div>

    <script>
      jQuery(document).ready(function($) {
    $(".clickable-row").click(function() {
        window.location = $(this).data("href");
    });
});
    </script>

</body>
</html>