<?php
require_once './common.php';
require_once './db.php';

session_start();
if (!isset($_SESSION['is_admin']))  
  header('Location: ./login.php');

$history_data = get_history_data($_GET['id'])['data'];
$depositor = get_user_data($history_data['USER_ID'])['data'];
$receiver = get_user_data_by_phone($history_data['RECEIVER_PHONE'])['data'];

if (isset($_GET['is_browsed'])) {
    if ($_GET['is_browsed'] == 1) {
        $result = update_state_history($history_data['ID'],1);
        header('Location: ./history_admin.php');
    }
    else if ($_GET['is_browsed'] == 0) {
        $result = update_state_history($history_data['ID'],2);
        header('Location: ./history_admin.php');
    }
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
    <div class="container">
        <div class="row">
            <div class="col-md-9 mr-auto">
            <h3>Chi tiết giao dịch</h3>
            <form>
                <div class="form-group form-row">
                    <label for="id" class="col-md-3">ID:</label>
                    <input class="form-control col-md-8"  type="text" value="<?php echo $history_data['ID']; ?> " disabled/>
                </div>
                <div class="form-group form-row">
                    <label for="depositor" class="col-md-3">Người gửi:</label>
                    <input class="form-control col-md-8"  type="text" value="<?php echo $depositor['FULL_NAME']; ?> " disabled/>
                </div>
                <div class="form-group form-row">
                    <label for="receiver" class="col-md-3">Người nhận</label>
                    <input class="form-control col-md-8" type="text" value="<?php echo $receiver['FULL_NAME']; ?>" disabled/>
                </div>
                <div class="form-group form-row">
                    <label class="col-md-3" for="amount">Số tiền chuyển:</label>
                    <input class="form-control col-md-8" type="text" value="<?php echo $history_data['AMOUNT']; ?>" disabled/>
                </div>
                <div class="form-group form-row">
                    <label class="col-md-3" for="time">Thời gian:</label>
                    <input class="form-control col-md-8" type="text" value="<?php echo $history_data['TIME']; ?>" disabled/>
                </div>
                <div class="form-group form-row">
                    <label class="col-md-3" for="is_active">Trạng thái:</label>
                    <?php   if ($history_data['IS_ALLOW'] == 0):
                                $state = 'chưa xử lí';
                                $bg_color = 'form-control col-md-8 bg-warning text-dark'; 
                            else:
                                $state = 'đã xử lí';
                                $bg_color = 'form-control col-md-8 bg-success text-dark';
                            endif;
                    ?>
                    <input class= '<?php echo $bg_color?>' type="text" value="<?php echo $state ?>" disabled/>
                </div>
                <div class="form-group form-row">
                    <label class="col-md-3" for="permission">Quyền:</label>
                    <button type="button" class="btn btn-primary mr-2" onclick="browse(<?php echo $history_data['ID'] ?>)" data-toggle="modal" data-target="#modal">
                        Duyệt
                    </button>
                </div>
		    </form>
	        </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modal-label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-label">Phê duyệt</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Bạn có muốn phê duyệt cho giao dịch này ?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="agree">Đồng ý</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="disagree">Không đồng ý</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>