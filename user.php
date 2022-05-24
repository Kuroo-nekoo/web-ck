<?php
require_once './common.php';
require_once './db.php';

session_start();

if (isset($_SESSION['is_new_user'])) {
    $is_new_user = $_SESSION['is_new_user'];
    check_new_user($is_new_user);
}

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $user_data = get_user_data($user_id)['data'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous" />
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</head>

<body>
	<?php require_once 'navbar.php'?>
	<div class="col-md-6 ml-auto">
		<form>
			<div class="form-group form-row">
				<label for="name" class="col-md-3">Tên người dùng:</label>
				<input class="form-control col-md-8" id="name" name="name" type="text" value="<?php echo $user_data['FULL_NAME']; ?>" />
			</div>
			<div class="form-group form-row">
				<label for="email" class="col-md-3">Email:</label>
				<input class="form-control col-md-8" id="email" name="email" type="text" value="<?php echo $user_data['EMAIL']; ?>" />
			</div>
			<div class="form-group form-row">
				<label class="col-md-3" for="phone_number">Số điện thoại:</label>
				<input class="form-control col-md-8" id="phone_number" name="phone_number" type="text" value="<?php echo $user_data['PHONE_NUMBER']; ?>" />
			</div>
			<div class="form-group form-row">
				<label class="col-md-3" for="date_of_birth">Ngày sinh:</label>
				<input class="form-control col-md-8" id="date_of_birth" name="date_of_birth" type="date" value="<?php echo $user_data['DATE_OF_BIRTH']; ?>" />
			</div>
			<div class="form-group form-row">
				<label class="col-md-3" for="address">Địa chỉ:</label>
				<input class="form-control col-md-8" id="address" name="address" type="text" value="<?php echo $user_data['ADDRESS']; ?>" />
			</div>
			<div class="form-group form-row">
				<label class="col-md-3" for="is_active">Trạng thái:</label>
				<input class="form-control col-md-8" id="address" name="address" type="text" value="<?php echo $user_data['IS_VALIDATED'] === 1 ? "đã kích hoạt" : "chưa kích hoạt"; ?>" />
			</div>
		</form>
		<a href="./change_password.php"><button class="btn btn-success">Đổi mật khẩu</button></a>
	</div>
</body>
</html>