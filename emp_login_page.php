<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 14.02.2019
 * Time: 01:54
 */


include_once "url_slug.php";
include 'vendor/autoload.php';
$dotenv =\Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

session_start();


if (isset($_SESSION["token"])) {
    switch ($_SESSION["user_type_id"]) {
        case "1":
            header('Location: admin_panel.php');
            break;
        case "2":
            header('Location: officer_main_page.php');
            break;
        case "3":
            header('Location: field_operation_page.php');
            break;
        case "4":
            header('Location: laboratory_analysis_page.php');
            break;
        case "7":
            header('Location: sample_tracking_page.php');
            break;
        case "8":
            header('Location: certificates.php');
            break;
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

<div class="container">
    <div class="pb-2 mt-4 mb-2 border-bottom text-center">
        <img class="img-fluid" width="350px" src="img/general-survey-gozetme-ltd-sti.png"/>
    </div>
    <h2>
        General Survey Panel
    </h2>
    <br>
    <?php if (isset($_COOKIE['loggedOut'])) { ?>
        <div class="alert alert-success alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Success!</strong> You have been logged out safely.
        </div>
    <?php } ?>

    <?php if (isset($_COOKIE['pleaseLogin'])) { ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>You should login first.</strong>
        </div>
    <?php } ?>

    <?php if (isset($_COOKIE['noUser'])) { ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>E-mail or password is wrong.</strong>
        </div>
    <?php } ?>

    <?php if (isset($_COOKIE['connErr'])) { ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Connection problem, please try again.</strong>
        </div>
    <?php } ?>

    <form action="login_function.php" method="POST" role="form">
        <div class="form-group">
            <label for="email">E-mail:</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Please enter your e-mail">
        </div>
        <div class="form-group">
            <label for="password">Şifre:</label>
            <input type="password" class="form-control" id="password" name="password"
                   placeholder="Please enter your password">
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
    </form>

</div>

<footer class="footer bg-dark">
    <div class="container">
        <p class="text-muted">Krekpot Bilgi Teknolojileri 2019&reg; All Rights Reserved.
        </p>

    </div>
</footer>