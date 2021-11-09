<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 28.02.2019
 * Time: 05:11
 */

session_start();
ob_start();
unset($_SESSION["token"]);
unset($_SESSION["user_id"]);
unset($_SESSION["user_name"]);
unset($_SESSION["user_email"]);
unset($_SESSION["user_type_id"]);
unset($_SESSION["user_office_id"]);
$past = time() - 3600;
foreach ($_COOKIE as $key => $value) {
    setcookie($key, $value, $past, '/generalsurvey');
}
setcookie("loggedOut", "loggedOut", time() + (2));
header("Location: emp_login_page.php");
ob_end_flush();