<?php
require_once './common.php';
require_once './db.php';

session_start();
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
}

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

if (isset($_FILES['front_id_image']) && isset($_FILES['back_id_image'])
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
    update_state($user_id, 'chờ xác minh');
    header('Location: ./user.php');
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
	<link rel="stylesheet" type="text/css" href="./style.css">
    <script src="./main.js"></script>
</head>

<body>
	<?php require_once 'navbar_user.php'?>
	<div class="container emp-profile">
		<form action="user.php" method="POST" enctype="multipart/form-data">
			<div class="row">
				<div class="col-md-4">
					<div class="profile-img">
						<img src="./img/user.jpg" alt=""/>
					</div>
				</div>
				<div class="col-md-6">
					<div class="profile-head">
						<h5>
							<?php echo $user_data['FULL_NAME'] ?>
						</h5>
						<ul class="nav nav-tabs" id="myTab" role="tablist">
							<li class="nav-item">
								<a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Thông tin</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Trạng thái</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					<div class="profile-work">
						<p>Số dư</p>
						<div id='money' class="border w-75"><?php echo $user_data['BALANCE'] ?></div>
					</div>
				</div>
				<div class="col-md-8">
					<div class="tab-content profile-tab" id="myTabContent">
						<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
							<div class="row">
								<div class="col-md-6">
									<label>User name</label>
								</div>
								<div class="col-md-6">
									<p><?php echo $user_data['USERNAME'] ?></p>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label>Tên</label>
								</div>
								<div class="col-md-6">
									<p><?php echo $user_data['FULL_NAME'] ?></p>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label>Email</label>
								</div>
								<div class="col-md-6">
									<p><?php echo $user_data['EMAIL'] ?></p>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label>Số điện thoại</label>
								</div>
								<div class="col-md-6">
									<p><?php echo $user_data['PHONE_NUMBER'] ?></p>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label>Ngày sinh</label>
								</div>
								<div class="col-md-6">
									<p><?php echo $user_data['DATE_OF_BIRTH'] ?></p>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label>Địa chỉ</label>
								</div>
								<div class="col-md-6">
									<p><?php echo $user_data['ADDRESS'] ?></p>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<a class='float-right mr-5' href="./change_password.php"><button class="btn btn-success">Đổi mật khẩu</button></a>
								</div>
							</div>
						</div>
						<div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
							<div class="row">
								<div class="col-md-6">
									<label>Trạng thái</label>
								</div>
								<div class="col-md-6">
								<?php
									$bg_color = 'form-control col-md-8 bg-success text-white';
									if ($user_data['ACTIVATED_STATE'] == 'chờ xác minh' or $user_data['ACTIVATED_STATE'] == 'chờ cập nhật'):
										$bg_color = 'form-control col-md-8 bg-warning text-dark';
									elseif ($user_data['ACTIVATED_STATE'] == 'đã bị khóa'):
										$bg_color = 'form-control col-md-8 bg-danger text-white';
									elseif ($user_data['ACTIVATED_STATE'] == 'vô hiệu hóa'):
										$bg_color = 'form-control col-md-8 bg-secondary text-white';
									endif;
									if ($user_data['ACTIVATED_STATE'] != 'chờ cập nhật'): ?>
									<p class= '<?php echo $bg_color ?>' ><?php echo $user_data['ACTIVATED_STATE'] ?></p>
								<?php endif;?>
								</div>
							</div>

							<?php if ($user_data['ACTIVATED_STATE'] == 'chờ cập nhật'): ?>
								<form action="user.php" enctype="multipart/form-data" method="POST">
								<div class="row">
									<div class="col-md-6">
										<label>Ảnh mặt trước CMND:</label>
									</div>
									<div class="col-md-6">
										<button id='front_img' class="file-upload-btn col-md-3" type="button" onclick="$('#front_id_image').trigger( 'click' )">Add Image</button>
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
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>Ảnh mặt sau CMND:</label>
									</div>
									<div class="col-md-6">
										<button id='back_img' class="file-upload-btn col-md-3" type="button" onclick="$('#back_id_image').trigger( 'click' )">Add Image</button>
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
								</div>
								<div class="row">
									<div class="col-md-12">
										<button id='update' type="submit" class="btn btn-primary ml-auto mr-3" name="update_id_image" >Cập nhật</button>
									</div>
								</div>
								</form>

							<?php endif;?>
						</div>
					</div>
				</div>
			</div>
		</form>
    </div>
</body>
</html>