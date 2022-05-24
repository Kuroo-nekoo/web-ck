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

function register($phone_number, $email, $full_name, $date_of_birth, $address)
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

    $sql = "INSERT INTO ACCOUNT (PHONE_NUMBER, EMAIL, FULL_NAME, DATE_OF_BIRTH, ADDRESS, USERNAME, PASSWORD, IS_NEW_USER, IS_VALIDATED, FAIL_LOGIN_COUNT, ABNORMAL_LOGIN_COUNT, IS_LOCKED) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stm = $conn->prepare($sql);
    $is_new_user = 1;
    $is_validated = 0;
    $fail_login_count = 0;
    $abnormal_login_count = 0;
    $is_locked = 1;
    $stm->bind_param('sssssssiiiii', $phone_number, $email, $full_name, $date_of_birth, $address, $username, $password, $is_new_user, $is_validated, $fail_login_count, $abnormal_login_count, $is_locked);
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
    if ($result->num_rows === 0) {
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
                $sql = "UPDATE ACCOUNT SET FAIL_LOGIN_COUNT = ?, ABNORMAL_LOGIN_COUNT = ? WHERE USERNAME = ?";
                $stm = $conn->prepare($sql);
                $stm->bind_param('iis', $fail_login_count, $abnormal_login_count, $username);

                if (!$stm->execute()) {
                    return array('code' => 1, 'error' => 'Error: ' . $sql . "<br>" . $conn->error);
                }
                $stm->execute();
            } else if ($data['ABNORMAL_LOGIN_COUNT'] === 1) {
                $is_locked = 0;
                $fail_login_count = 0;
                $abnormal_login_count = 0;
                $sql = "UPDATE ACCOUNT SET FAIL_LOGIN_COUNT = ?, ABNORMAL_LOGIN_COUNT = ?, IS_LOCKED = ? WHERE USERNAME = ?";
                $stm = $conn->prepare($sql);
                $stm->bind_param('iiis', $fail_login_count, $abnormal_login_count, $is_locked, $username);

                if (!$stm->execute()) {
                    return array('code' => 1, 'error' => 'Error: ' . $sql . "<br>" . $conn->error);
                }
                $stm->execute();
            }
        }
        return array('code' => 1, 'error' => 'Sai mật khẩu');
    }

    return array('code' => 0, 'data' => $data);
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
        return array('code' => 1, 'error' => 'Account not found');
    }

    return array('code' => 0);
}

function get_users_data()
{
    $conn = connect_database();
    $sql = "SELECT * FROM ACCOUNT";
    $stm = $conn->prepare($sql);

    if (!$stm->execute()) {
        return array('code' => 1, 'error' => 'Error: ' . $sql . "<br>" . $conn->error);
    }

    $result = $stm->get_result();
    $data = $result->fetch_assoc();
    return array('code' => 0, 'data' => $data);
}
