<?php
require_once './common.php';
require_once './db.php';

session_start();
if (!isset($_SESSION['is_admin']))  
  header('Location: ./login.php');

$user_data = get_user_data($_GET['user_id'])['data'];
$user_id = $user_data['USER_ID'];

if (isset($_GET['is_accepted'])) {
    if ($_GET['is_accepted'] == 1) {
        $result = update_state($user_id,'đã xác minh');
        header('Location: ./user_info.php?user_id=' . $user_id);
    }
}

if (isset($_GET['is_rejected'])) {
    if ($_GET['is_rejected'] == 1) {
        $result = update_state($user_id,'vô hiệu hóa');
        header('Location: ./user_info.php?user_id=' . $user_id);
    }
}

if (isset($_GET['is_added'])) {
    if ($_GET['is_added'] == 1) {
        $result = update_state($user_id,'chờ cập nhật');
        header('Location: ./user_info.php?user_id=' . $user_id);
    }
}

if (isset($_GET['is_unlock'])) {
    if ($_GET['is_unlock'] == 1) {
        $result = unlock($user_id);
        header('Location: ./user_info.php?user_id=' . $user_id);
    }
}
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
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous" />
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <link rel="stylesheet" href="./style.css">
    <script type="" src="./main.js"></script>
</head>

<body>
<?php include './navbar_admin.php'?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <form class="main">
                <h3>Thông tin người dùng</h3>
                <div class="form-group form-row">
                    <label for="name" class="col-md-3">User name:</label>
                    <input class="form-control col-md-8"  type="text" value="<?php echo $user_data['USERNAME']; ?> " disabled/>
                </div>
                <div class="form-group form-row">
                    <label for="name" class="col-md-3">Tên người dùng:</label>
                    <input class="form-control col-md-8"  type="text" value="<?php echo $user_data['FULL_NAME']; ?> " disabled/>
                </div>
                <div class="form-group form-row">
                    <label for="email" class="col-md-3">Email:</label>
                    <input class="form-control col-md-8" type="text" value="<?php echo $user_data['EMAIL']; ?>" disabled/>
                </div>
                <div class="form-group form-row">
                    <label class="col-md-3" for="phone_number">Số điện thoại:</label>
                    <input class="form-control col-md-8" type="text" value="<?php echo $user_data['PHONE_NUMBER']; ?>" disabled/>
                </div>
                <div class="form-group form-row">
                    <label class="col-md-3" for="date_of_birth">Ngày sinh:</label>
                    <input class="form-control col-md-8" type="text" value="<?php echo $user_data['DATE_OF_BIRTH']; ?>" disabled/>
                </div>
                <div class="form-group form-row">
                    <label class="col-md-3" for="address">Địa chỉ:</label>
                    <input class="form-control col-md-8"type="text" value="<?php echo $user_data['ADDRESS']; ?>" disabled/>
                </div>
                <div class="form-group form-row">
                    <label class="col-md-3" for="is_active">Trạng thái:</label>
                    <?php if ($user_data['ACTIVATED_STATE'] == 'chờ xác minh'):
                            $bg_color = 'form-control col-md-8 bg-warning text-dark'; 
                            elseif ($user_data['ACTIVATED_STATE'] == 'đã xác minh'):
                                $bg_color = 'form-control col-md-8 bg-success text-white';
                            elseif ($user_data['ACTIVATED_STATE'] == 'đã bị khóa'):
                                $bg_color = 'form-control col-md-8 bg-danger text-white';  
                            else:
                                $bg_color = 'form-control col-md-8 bg-secondary text-white';
                    endif;?>
                    <input class= '<?php echo $bg_color?>' type="text" value="<?php echo $user_data['ACTIVATED_STATE'] ?>" disabled/>
                </div>
                <div class="form-group form-row">
					<label class="col-md-3" for="cmnd">CMND</label>
					<div class="col-md-4" id="img-1"> 
						<img class="id-card" src="<?php echo $user_data['FRONT_ID_IMAGE_DIR']; ?>" alt="mặt trước cmnd">
					</div>
					<div class="col-md-4" id="img-2"> 
						<img class="id-card" src="<?php echo $user_data['BACK_ID_IMAGE_DIR']; ?>" alt="mặt sau cmnd">
					</div>
				</div>
                <div class="form-group form-row">
                    <label class="col-md-3" for="permission">Quyền:</label>
                    <button type="button" class="btn btn-primary mr-2" onclick='rejected(<?php echo $user_id ?>)' data-toggle="modal" data-target="#modal">
                            Vô hiệu
                    </button>
                    <?php if($user_data['ACTIVATED_STATE'] == 'chờ xác minh'):?>
                        <button type="button" class="btn btn-primary mr-2" onclick='verification(<?php echo $user_id ?>)' data-toggle="modal" data-target="#modal">
                            Xác minh
                        </button>
                        <button type="button" class="btn btn-primary ml-2" onclick='additional(<?php echo $user_id ?>)' data-toggle="modal" data-target="#modal">
                            Bổ sung thông tin
                        </button>
                    <?php elseif($user_data['IS_LOCKED'] == 1): ?>
                    <button type="button" class="btn btn-primary mr-2" onclick='unlock(<?php echo $user_id ?>)' data-toggle="modal" data-target="#modal">
                        Mở khóa
                    </button>
                    <?php endif;?>
                </div>
		    </form>
	        </div>
            <div class="col-md-6">
            <div class="container">
                <div class="row">
      <div class="main col-md-12">
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
 <div class="row">
    <div class="main col-md-12">
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
      </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modal-label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-label">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="accept">Xác nhận</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>