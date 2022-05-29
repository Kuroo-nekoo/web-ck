<?php
function connect_database()
{
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "QUANLYVIDIENTU";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

function register($phone_number, $email, $full_name, $date_of_birth, $address, $front_id_image_dir, $back_id_image_dir)
{
    $conn = connect_database();
    $sql = "SELECT * FROM ACCOUNT WHERE EMAIL = ? OR PHONE_NUMBER = ?";
    $stm = $conn->prepare($sql);
    $stm->bind_param('ss', $email, $phone_number);
    $username = '';
    $password = '';

    if (!$stm->execute()) {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $result = $stm->get_result();
    if ($result->num_rows > 0) {
        return array('code' => 1, 'error' => 'Tài khoản đã tồn tại');
    }

    for ($i = 0; $i < 10; $i++) {
        $username = $username . rand(0, 9);
    }

    for ($i = 0; $i < 6; $i++) {
        $password = $password . chr(rand(0, 25) + 97);
    }

    $sql = "INSERT INTO ACCOUNT (PHONE_NUMBER, EMAIL, FULL_NAME, DATE_OF_BIRTH, ADDRESS, USERNAME, PASSWORD, IS_NEW_USER, ACTIVATED_STATE, FAIL_LOGIN_COUNT, ABNORMAL_LOGIN_COUNT, IS_LOCKED, DATE_LOCKED, DATE_CREATED, BALANCE, FRONT_ID_IMAGE_DIR, BACK_ID_IMAGE_DIR) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stm = $conn->prepare($sql);
    $is_new_user = 1;
    $activated_state = "chờ xác minh";
    $fail_login_count = 0;
    $abnormal_login_count = 0;
    $is_locked = 0;
    date_default_timezone_set('asia/ho_chi_minh'); //set timezone
    $date_created = date('y-m-d G:i:s'); // get current date
    $date_locked = null;
    $balance = 0;
    $stm->bind_param('sssssssisiiississ', $phone_number, $email, $full_name, $date_of_birth, $address, $username, $password, $is_new_user, $activated_state, $fail_login_count, $abnormal_login_count, $is_locked, $date_locked, $date_created, $balance, $front_id_image_dir, $back_id_image_dir);
    if (!$stm->execute()) {
        return array('code' => 1, 'error' => 'Error: ' . $sql . "<br>" . $conn->error);
    }

    return array('code' => 0, 'username' => $username, 'password' => $password);
}

function login($username, $password)
{
    $conn = connect_database();
    $sql = "SELECT * FROM ACCOUNT WHERE USERNAME = ?";
    $stm = $conn->prepare($sql);
    $stm->bind_param('s', $username);
    if (!$stm->execute()) {
        return array('code' => 1, 'error' => 'Error: ' . $sql . "<br>" . $conn->error);
    }

    $result = $stm->get_result();
    $data = $result->fetch_assoc();
    if ($data['IS_LOCKED'] === 1) {
        return array('code' => 1, 'error' => 'Tài khoản đã bị khóa', 'is_locked' => 1);
    } else if ($result->num_rows === 0) {
        return array('code' => 1, 'error' => 'Sai tên đăng nhập');
    } else if (isset($data['PASSWORD']) && $data['PASSWORD'] != $password) {
        if ($data['FAIL_LOGIN_COUNT'] === null) {
            $fail_login_count = 1;
        } else {
            $fail_login_count = $data['FAIL_LOGIN_COUNT'] + 1;
        }
        $sql = "UPDATE ACCOUNT SET FAIL_LOGIN_COUNT = ? WHERE USERNAME = ?";
        $stm = $conn->prepare($sql);
        $stm->bind_param('is', $fail_login_count, $username);
        if (!$stm->execute()) {
            return array('code' => 1, 'error' => 'Error: ' . $sql . "<br>" . $conn->error);
        }

        if ($fail_login_count === 3) {
            if ($data['ABNORMAL_LOGIN_COUNT'] === 0) {
                $abnormal_login_count = 1;
                $fail_login_count = 0;
                date_default_timezone_set('asia/ho_chi_minh'); // set timezone
                $date_locked = date('y-m-d G:i:s'); // get current date
                $sql = "UPDATE ACCOUNT SET FAIL_LOGIN_COUNT = ?, ABNORMAL_LOGIN_COUNT = ?, DATE_LOCKED = ? WHERE USERNAME = ?";
                $stm = $conn->prepare($sql);
                $stm->bind_param('iiss', $fail_login_count, $abnormal_login_count, $date_locked, $username);

                if (!$stm->execute()) {
                    return array('code' => 1, 'error' => 'Error: ' . $sql . "<br>" . $conn->error);
                }

                return array('code' => 1, 'error' => 'Tài khoản đã bị khóa tạm thời', 'abnormal_login_count' => $abnormal_login_count);
            } else if ($data['ABNORMAL_LOGIN_COUNT'] === 1) {
                $is_locked = 1;
                $fail_login_count = 0;
                $abnormal_login_count = 0;

                $sql = "UPDATE ACCOUNT SET FAIL_LOGIN_COUNT = ?, ABNORMAL_LOGIN_COUNT = ?, IS_LOCKED = ? WHERE USERNAME = ?";
                $stm = $conn->prepare($sql);
                $stm->bind_param('iiis', $fail_login_count, $abnormal_login_count, $is_locked, $username);

                if (!$stm->execute()) {
                    return array('code' => 1, 'error' => 'Error: ' . $sql . "<br>" . $conn->error);
                }

                return array('code' => 1, 'error' => 'Tài khoản đã bị khóa vĩnh viễn', 'is_locked' => $is_locked);
            }
        }
        return array('code' => 1, 'error' => 'Sai mật khẩu');
    } else {
        $sql = "UPDATE ACCOUNT SET FAIL_LOGIN_COUNT = 0, ABNORMAL_LOGIN_COUNT = 0 WHERE USERNAME = ?";
        $stm = $conn->prepare($sql);
        $stm->bind_param('s', $username);
        if (!$stm->execute()) {
            return array('code' => 1, 'error' => 'Error: ' . $sql . "<br>" . $conn->error);
        }
        return array('code' => 0, 'data' => $data);
    }
}

function change_password_first_time($new_password, $user_id)
{
    $conn = connect_database();
    $sql = "UPDATE ACCOUNT SET PASSWORD = ?, IS_NEW_USER = 0 WHERE USER_ID = ?";
    $stm = $conn->prepare($sql);
    $stm->bind_param('ss', $new_password, $user_id);

    if (!$stm->execute()) {
        return array('code' => 1, 'error' => 'Error: ' . $sql . "<br>" . $conn->error);
    }

    $stm->execute();
    return array('code' => 0);
}

function get_user_data($user_id)
{
    $conn = connect_database();
    $sql = "SELECT * FROM ACCOUNT WHERE USER_ID = ?";
    $stm = $conn->prepare($sql);
    $stm->bind_param('s', $user_id);

    if (!$stm->execute()) {
        return array('code' => 1, 'error' => 'Error: ' . $sql . "<br>" . $conn->error);
    }

    $result = $stm->get_result();
    $data = $result->fetch_assoc();
    return array('code' => 0, 'data' => $data);
}

function change_password($old_password, $new_password, $user_id)
{
    $conn = connect_database();
    $sql = "SELECT * FROM ACCOUNT WHERE USER_ID = ?";
    $stm = $conn->prepare($sql);
    $stm->bind_param('s', $user_id);
    if (!$stm->execute()) {
        return array('code' => 1, 'error' => 'Error: ' . $sql . "<br>" . $conn->error);
    }
    $result = $stm->get_result();
    $data = $result->fetch_assoc();

    if ($result->num_rows === 0) {
        return array('code' => 1, 'error' => 'User not found');
    }

    if ($old_password === $data['PASSWORD']) {
        $sql = "UPDATE ACCOUNT SET PASSWORD = ? WHERE USER_ID = ?";
        $stm = $conn->prepare($sql);
        $stm->bind_param('ss', $new_password, $user_id);
        if (!$stm->execute()) {
            return array('code' => 1, 'error' => 'Error: ' . $sql . "<br>" . $conn->error);
        }

        $stm->execute();
        return array('code' => 0);
    } else {
        return array('code' => 1, 'error' => 'Sai mật khẩu cũ');
    }
}

function gen_otp()
{
    $otp = '';

    for ($i = 0; $i < 6; $i++) {
        $otp = $otp . rand(0, 9);
    }
    return $otp;
}

function check_email_phone_number($email_phone_number)
{
    $conn = connect_database();
    $sql = "SELECT * FROM ACCOUNT WHERE EMAIL = ? OR PHONE_NUMBER = ?";
    $stm = $conn->prepare($sql);
    $stm->bind_param('ss', $email_phone_number, $email_phone_number);
    if (!$stm->execute()) {
        return array('code' => 1, 'error' => 'Error: ' . $sql . "<br>" . $conn->error);
    }

    $result = $stm->get_result();
    if ($result->num_rows === 0) {
        return array('code' => 1, 'error' => 'Tài khoản không tồn tại');
    }

    return array('code' => 0);
}

function get_users_data()
{
    $conn = connect_database();
    $sql = "SELECT * FROM ACCOUNT";
    $result = $conn->query($sql);
    $data = array();
    while (($row = $result->fetch_assoc())) {
        $data[] = $row;
    }
    if ($result->num_rows === 0) {
        return array('code' => 1, 'error' => 'Empty data');
    }
    return array('code' => 0, 'data' => $data);
}

function sort_date_created($a, $b)
{
    return strtotime($b['DATE_CREATED']) - strtotime($a['DATE_CREATED']);
}

function sort_date_locked($a, $b)
{
    return strtotime($b['DATE_LOCKED']) - strtotime($a['DATE_LOCKED']);
}

function get_users_data_sort_date($type)
{
    $data = get_users_data();
    if ($data['code'] == 0) {
        $result = $data['data'];
        usort($result, $type);
        return array('code' => 0, 'data' => $result);
    }
    return array('code' => 1, 'error' => 'Empty data');
}

function update_state($user_id, $state) {
    $conn = connect_database();
    $sql = "UPDATE ACCOUNT SET ACTIVATED_STATE = '{$state}'  WHERE USER_ID = ?";
    $stm = $conn->prepare($sql);
    $stm->bind_param('s', $user_id);
    if (!$stm->execute()) {
        return array('code' => 1, 'error' => 'Error: ' . $sql . "<br>" . $conn->error);
    }

    $stm->execute();
    return array('code' => 0);
}

function unlock($user_id) {
    $conn = connect_database();
    $sql = "UPDATE ACCOUNT SET ACTIVATED_STATE = 'đã xác minh', IS_LOCKED = 0, DATE_LOCKED = NULL, FAIL_LOGIN_COUNT = 0, ABNORMAL_LOGIN_COUNT = 0  WHERE USER_ID = ?";
    $stm = $conn->prepare($sql);
    $stm->bind_param('s', $user_id);
    if (!$stm->execute()) {
        return array('code' => 1, 'error' => 'Error: ' . $sql . "<br>" . $conn->error);
    }

    $stm->execute();
    return array('code' => 0);
}

function update_id_card ($user_id, $front_id_image_dir, $back_id_image_dir) {
    $conn = connect_database();
    $sql = "UPDATE ACCOUNT SET FRONT_ID_IMAGE_DIR = '{$front_id_image_dir}', BACK_ID_IMAGE_DIR = '{$back_id_image_dir}' WHERE USER_ID = ?";
    $stm = $conn->prepare($sql);
    $stm->bind_param('s', $user_id);
    if (!$stm->execute()) {
        return array('code' => 1, 'error' => 'Error: ' . $sql . "<br>" . $conn->error);
    }

    $stm->execute();
    return array('code' => 0);
}