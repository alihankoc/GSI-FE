<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 15.02.2019
 * Time: 02:11
 */

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

    <div class="card bg-light text-dark">
        <div class="card-header">
            <div class="form-inline" style="display: inline-flex;">
                <label for="sel1"></label>
                <select class="form-control" id="sel1" name="sellist1">
                    <option>Country</option>
                    <option>Turkey</option>
                    <option>Russia</option>
                    <option>Ukrania</option>
                </select>
                <label for="sel2"></label>
                <select class="form-control" id="sel2" name="sellist2">
                    <option>Office</option>
                    <option>Ankara</option>
                    <option>Istanbul</option>
                    <option>Adana</option>
                </select>
                <button type="submit" class="btn btn-success">Filter</button>
            </div>
            <button type="button" class="btn btn-outline-info float-right"><i class="fas fa-plus-circle"></i> Start
                New Operation
            </button>
        </div>
        <div class="card-body">
            <!-- Tab panes -->
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#waiting">Waiting List <span
                                class="badge badge-danger">4</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#active">Active List <span
                                class="badge badge-danger">4</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#completed">Completed List <span
                                class="badge badge-danger">4</span></a>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div id="waiting" class="container tab-pane active"><br>
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Operation ID</th>
                            <th>Location</th>
                            <th>Customer</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>234</td>
                            <td>Russia</td>
                            <td>Customer 1</td>
                        </tr>
                        <tr>
                            <td>123</td>
                            <td>Ukrania</td>
                            <td>Customer 2</td>
                        </tr>
                        <tr>
                            <td>654</td>
                            <td>Turkey</td>
                            <td>Customer 3</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div id="active" class="container tab-pane fade"><br>
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Operation ID</th>
                            <th>Location</th>
                            <th>Customer</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>234</td>
                            <td>Russia</td>
                            <td>Customer 1</td>
                        </tr>
                        <tr>
                            <td>123</td>
                            <td>Ukrania</td>
                            <td>Customer 2</td>
                        </tr>
                        <tr>
                            <td>654</td>
                            <td>Turkey</td>
                            <td>Customer 3</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div id="completed" class="container tab-pane fade"><br>
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Operation ID</th>
                            <th>Location</th>
                            <th>Customer</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>234</td>
                            <td>Russia</td>
                            <td>Customer 1</td>
                        </tr>
                        <tr>
                            <td>123</td>
                            <td>Ukrania</td>
                            <td>Customer 2</td>
                        </tr>
                        <tr>
                            <td>654</td>
                            <td>Turkey</td>
                            <td>Customer 3</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

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