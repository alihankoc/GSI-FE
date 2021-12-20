<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 15.02.2019
 * Time: 02:11
 */
include 'vendor/autoload.php';
$dotenv =\Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

include_once "url_slug.php";

session_start();

if (!isset($_SESSION["token"])) {
    header('Location: admin_login_page.php');
    setcookie("pleaseLogin", "pleaseLogin", time() + (2));
    exit();
} else {
    if ($_SESSION["user_type_id"] != 1) {
        header('Location: emp_login_page.php');
        setcookie("pleaseLogin", "pleaseLogin", time() + (2));
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <title><?php echo url_slug($_SERVER["PHP_SELF"]); ?></title>

    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/custom.css">
    <link rel="stylesheet" type="text/css" href="css/all.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap4-toggle.css">
    <script type="text/javascript" src="js/jquery-3.3.1.js"></script>
    <script type="text/javascript" src="js/popper.js"></script>
    <script type="text/javascript" src="js/bootstrap.js"></script>
    <script type="text/javascript" src="js/jquery.are-you-sure.js"></script>
    <script type="text/javascript" src="js/bootstrap4-toggle.js"></script>


</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">
        <img src="img/general-survey-gozetme-ltd-sti.png" alt="Logo" style="width:200px;">
    </a>

    <ul class="navbar-nav ml-auto">
        <li class="nav-item active">
            <a class="nav-link" href="#"><span class="fas fa-user"></span> Welcome <?php echo $_SESSION["user_name"] ?>
            </a>
        </li>
        <li class="nav-item active">
            <a class="nav-link" href="#"><i class="fas fa-wrench"></i> Settings</a>
        </li>
        <li class="nav-item active">
            <a class="nav-link" href="logout_function.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </li>
    </ul>
</nav>
<div class="jumbotron jumbotron-fluid">
    <div class="container">
        <h4>Admin Panel</h4>
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a class="nav-link active" href="employee_management.php">Employee Management</a>
            </li>
            <li class="nav-item" style="padding-left: 10px;">
                <a class="nav-link active" href="customer_management.php">Customer Management</a>
            </li>
            <li class="nav-item" style="padding-left: 10px;">
                <a class="nav-link active" href="office_management.php">Office Management</a>
            </li>
            <li class="nav-item" style="padding-left: 10px;">
                <a class="nav-link active" href="category_management.php">Category Management</a>
            </li>
        </ul>
    </div>
</div>

<div class="container">


</div>
<footer class="footer bg-dark">
    <div class="container">
        <p class="text-muted">Krekpot Bilgi Teknolojileri 2019&reg; All Rights Reserved.
        </p>

    </div>
</footer>
<script>
    $(document).ready(function () {
        $('[data-toggle="popover"]').popover();
    });
</script>
</body>
</html>