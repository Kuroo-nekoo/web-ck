<?php
require_once "./db.php";
require_once "./common.php";

  session_start();
  if (isset($_SESSION['is_new_user'])) {
    $is_new_user = $_SESSION['is_new_user'];
    check_new_user($is_new_user);
  }

  $conn = connect_database();
  if (!$_SESSION['user_id']) {
    header('Location: login.php');
  }
  $user_id = $_SESSION['user_id'];
  $error='';
  $data_db3=array();
  $type='withdraw';
  $data=array();

  if (isset($_POST['credit_id']) && isset($_POST['cvv'] )&& isset($_POST['expiration_date'])  && isset($_POST['money'])){

    $data['credit_id'] = isset($_POST['credit_id']) ? $_POST['credit_id'] : '';
    $data['expiration_date'] = isset($_POST['expiration_date']) ? $_POST['expiration_date'] : '';
    $data['cvv'] = isset($_POST['cvv']) ? $_POST['cvv'] : '';
    $data['money'] = isset($_POST['money']) ? $_POST['money'] : '';
    if( $data['credit_id']==='' ||  $data['expiration_date']==='' ||  $data['cvv']==='' ||  $data['money']===''){
      $error='Vui lòng điền đầy đủ thông tin';
    
    }
    else if(strlen($_POST['credit_id'])!==6){
      $error='Định dạng thẻ không hợp lệ!';
      die('Định dạng không hợp lệ !');
    }
    $sql0 = "Select `credit_id` from  credit";    
    $result0= $conn->query($sql0);
    $data_db0= $result0->fetch_all();
    foreach ($data_db0 as $temp){
      if($data['credit_id']===$temp){
        $error='';
        break;
      }
      $error='Thẻ không hỗ trợ !';  
    }
    $sql1 = "Select `expiration_date` from  credit";    
    $result1= $conn->query($sql1);
    $data_db1= $result1->fetch_all();
  
    foreach ($data_db1 as $temp){
      if($data['expiration_date']===$temp){
        $error='';
        break;  
      }
      else{
      $error='Ngày hết hạn không trùng khớp!';  
      }
    }
    $sql2 = "Select `cvv` from  credit";    
    $result2= $conn->query($sql2);
    $data_db2= $result2->fetch_all();
    foreach ($data_db2 as $temp){
      if($data['cvv']===$temp){
        $error='';
        break;
      }
      else{
      $error='Cvv không hợp lệ!';  }
    }
    $sql3= "Select * from  credit";
    $result3= $conn->query($sql3);
    $data_db3= $result3->fetch_all();
   
    foreach($data_db3 as $temp){
      if($data['credit_id']===$temp[0] && $data['expiration_date']===$temp[1] && $data['cvv']===$temp[2] ){
        $error='';
        break;
      }
      else{
        $error="Thông tin không trùng khớp! ";
      }
    } 
    if((int)$data['money'] %50000!= 0){
        $error= "Số tiền phải là bội số của 50000";
    }
    // recharge 
    if($error===''){
            $sql4 = "Select * from  account WHERE user_id=?";    
            $stm4 = $conn->prepare($sql4);
            $stm4->bind_param('i',$user_id);
            if (!$stm4->execute()) {
                echo "Error: " . $sql4 . "<br>" . $conn->error;
            }

            $result4 = $stm4->get_result();
            $row4= $result4->fetch_assoc();
            $old_money = $row4['BALANCE'];
            $fee= (int)$data['money']*5/100;
            $sql5= "update `account` set balance =? WHERE USER_ID =?";
            $new_money0= $old_money-(int)$data['money']- $fee ;
            $stm5= $conn->prepare($sql5);
            $stm5->bind_param('di',$new_money0,$user_id);

            if (!$stm5->execute()) {
                echo "Error: " . $sql5 . "<br>" . $conn->error;
            }
            $error= "Rút tiền thành công !";
            date_default_timezone_set('Asia/Ho_Chi_Minh');           
            $date = date('Y-m-d H:i:s',time());
            $sql6="insert into history (USER_ID,AMOUNT,TIME,TYPE) values (?,?,?,?)";
            $conn->close();
            $conn = connect_database();
            $stm6 = $conn->prepare($sql6);
            $stm6->bind_param('idss', $user_id, $data['money'], $date, $type);
            if (!$stm6->execute()) {
                    echo "Error: " . $sql6 . "<br>" . $conn->error;
            }
        
        }
        
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
      src="http://code.jquery.com/jquery-3.3.1.slim.min.js"
      integrity=s"sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
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
  </head>
  <body>
    <?php include_once './navbar.php'?>
    <div class="d-flex justify-content-center align-items-center">
      <form name="rechargeForm" class="col-md-4 border" action="withdraw.php" method="post"  ">
      <div class="text-danger h5">
       </div>
        <h1>Rút tiền </h1>
        <div class="form-group">
          <label for="credit_id">Số thẻ tín dụng: </label>
          <input
            id="credit_id"
            class="form-control"
            placeholder="Số thẻ tín dụng"
            type="text"
            name="credit_id"
            require
          />
        </div>
        <div class="form-group">
          <label for="expiration_date">Ngày hết hạn : </label>
          <input
            id="expiration_date"

            class="form-control"
            type="expiration_date"
            name="expiration_date"
            placeholder ="YYYY-MM-DD"
            require
          />
          <div class="form-group">
          <label for="cvv">CVV : </label>
          <input
            id="CVV"

            class="form-control"
            type="cvv"
            name="cvv"
            placeholder ="***"
            require
          />
        </div>
        <div class="form-group">
          <label for="money">Số tiền:  
        </label>
          <input type="text" class="form-control" id="money" placeholder="Số tiền" name="money">
        </div>
        <div class="errorMessage my-3"><span id="errMessage">  <?php echo isset($error) ? $error : ''; ?></span></div>
        <button type="submit" class="btn btn-success btn-block" name="contact_action" >Xác nhận nạp thẻ</button>
      </form>
    </div>
  </body>
</html>