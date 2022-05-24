<?php
function check_new_user($is_new_user)
{
    if ($is_new_user === 1) {
        header("Location: change_password_first_time.php");
    }
}
