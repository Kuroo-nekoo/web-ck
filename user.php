<?php
require_once './common.php';
require_once './db.php';

session_start();
if (isset($_SESSION['user_id'])) {}
$user_id = $_SESSION['user_id'];

if (!isset($_SESSION['user_id'])) {
    header('Location: ./login.php');
}

if (isset($_SESSION['is_new_user'])) {
    $is_new_user = $_SESSION['is_new_user'];
    check_new_user($is_new_user);
}

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $user_data = get_user_data($user_id)['data'];
}

if (isset($_FILES['front_id_image']) && isset($_FILES['back_id_image'] )&& isset($_FILES['update_id_image'])
    && !empty($_FILES['front_id_image']) && !empty($_FILES['back_id_image'])) {
    $tmp_name = $_FILES['front_id_image']['tmp_name'];
    $name = basename($_FILES['front_id_image']['name']);
    move_uploaded_file($tmp_name, './uploads/' . $name);
    $front_id_image_dir = './uploads/' . $name;

    $tmp_name = $_FILES['back_id_image']['tmp_name'];
    $name = basename($_FILES['back_id_image']['name']);
    move_uploaded_file($tmp_name, './uploads/' . $name);
    $back_id_image_dir = './uploads/' . $name;

    update_id_image($user_id, $front_id_image_dir, $back_id_image_dir);
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
	<link rel="stylesheet" type="text/css" href="style.css">
    <script src="./main.js"></script>
</head>

<body>
	<?php require_once 'navbar_user.php'?>
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
				<input class="form-control col-md-8" id="address" name="address" type="text" value="<?php echo $user_data['ACTIVATED_STATE'] ?>" />
			</div>
				<div class="form-group form-row">
					<label class="col-md-3" for="phone_number">Số điện thoại:</label>
					<input class="form-control col-md-8" type="text" value="<?php echo $user_data['PHONE_NUMBER']; ?>" readonly/>
				</div>
				<div class="form-group form-row">
					<label class="col-md-3" for="date_of_birth">Ngày sinh:</label>
					<input class="form-control col-md-8" type="text" value="<?php echo $user_data['DATE_OF_BIRTH']; ?>" readonly/>
				</div>
				<div class="form-group form-row">
					<label class="col-md-3" for="address">Địa chỉ:</label>
					<input class="form-control col-md-8" type="text" value="<?php echo $user_data['ADDRESS']; ?>" readonly/>
				</div>
				<?php
if ($user_data['ACTIVATED_STATE'] == 'chờ xác minh' or $user_data['ACTIVATED_STATE'] == 'chờ cập nhật'):
    $bg_color = 'form-control col-md-8 bg-warning text-dark';
elseif ($user_data['ACTIVATED_STATE'] == 'đã bị khóa'):
    $bg_color = 'form-control col-md-8 bg-danger text-white';
else:
    $bg_color = 'form-control col-md-8 bg-secondary text-white';
endif;
if ($user_data['ACTIVATED_STATE'] != 'đã xác minh'): ?>
				<div class="form-group form-row">
					<label class="col-md-3" for="is_active">Trạng thái:</label>
					<input class= '<?php echo $bg_color ?>' type="text" value="<?php echo $user_data['ACTIVATED_STATE'] ?>" readonly/>
				</div>
				<?php endif;?>

				<?php if ($user_data['ACTIVATED_STATE'] != 'chờ cập nhật'): ?>
				<div class="form-group form-row">
					<label class="col-md-3" for="cmnd">CMND</label>
					<div class="col-md-4" id="img-1">
						<img class="id-card" src="<?php echo $user_data['FRONT_ID_IMAGE_DIR']; ?>" alt="mặt trước cmnd">
					</div>
					<div class="col-md-4" id="img-2">
						<img class="id-card" src="<?php echo $user_data['BACK_ID_IMAGE_DIR']; ?>" alt="mặt sau cmnd">
					</div>
				</div>
				<?php else: ?>
					<div class="form-group form-row">
						<label class="col-md-3" for="frontsideimg">Ảnh mặt trước CMND: </label>
						<button id='front_img' class="file-upload-btn col-md-2" type="button" onclick="$('#front_id_image').trigger( 'click' )">Add Image</button>
						<input
							class="form-control-file"
							type="file"
							accept="image/*"
							name="front_id_image"
							id="front_id_image"
							onchange="readURL(this, '#front','#front_id_image');"
						/>
						<img id="front" />
					</div>
					<div class="form-group form-row">
						<label class="col-md-3" for="back_id_image">Ảnh mặt sau CMND: </label>
						<button id='back_img' class="file-upload-btn col-md-2" type="button" onclick="$('#back_id_image').trigger( 'click' )">Add Image</button>
						<input
							class="form-control-file"
							type="file"
							accept="image/*"
							name="back_id_image"
							id="back_id_image"
							onchange="readURL(this, '#back', '#back_id_image');"

						/>
						<img id="back"/>
					</div>
				<?php endif;?>
				<div class="d-flex">
					<button id='update' type="submit" class="btn btn-primary ml-auto mr-3" name="update_id_image" >Cập nhật</button>
					<a href="./change_password.php"><button class="btn btn-success mr-3">Đổi mật khẩu</button></a>
				</div>
			</div>
		</form>
		</div>
	</div>
</body>
</html>