<?php
    session_start();
    unset($_SESSION['is_admin']);
    unset($_SESSION['user_id']);
    unset($_SESSION["username"]);
    unset($_SESSION["password"]);
    header("Location:login.php");
?>