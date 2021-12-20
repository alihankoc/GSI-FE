<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 5.05.2019
 * Time: 04:00
 */
include 'vendor/autoload.php';
$dotenv =\Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

include_once "url_slug.php";
include_once 'ApiCaller.php';

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


$apiCaller = new ApiCaller('1', $_SESSION["token"]);

$offices = $apiCaller->sendRequest(array(
    'api_url' => $_ENV['LINK'].'viewOffices',
    'api_method' => 'get',
));

$locations = $apiCaller->sendRequest(array(
    'api_url' => $_ENV['LINK'].'viewLocations',
    'api_method' => 'get',
));
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
    <link rel="stylesheet" type="text/css" href="css/fontawesome.min.css">
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
        <h4>Office Management</h4>
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a class="nav-link active" href="admin_panel.php">Back To Panel</a>
            </li>
        </ul>
    </div>
</div>

<div class="container">

    <?php if (isset($_COOKIE['addSuccessO'])) { ?>
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Success!</strong> Office was added successfully.
        </div>
    <?php } ?>

    <?php if (isset($_COOKIE['addErrorO'])) { ?>
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Alert!</strong> There is a problem.
        </div>
    <?php } ?>

    <?php if (isset($_COOKIE['addSuccessL'])) { ?>
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Success!</strong> Location was added successfully.
        </div>
    <?php } ?>

    <?php if (isset($_COOKIE['addErrorL'])) { ?>
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Alert!</strong> There is a problem.
        </div>
    <?php } ?>

    <?php if (isset($_COOKIE['deleteSuccessL'])) { ?>
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Success!</strong> Location was deleted successfully.
        </div>
    <?php } ?>

    <?php if (isset($_COOKIE['deleteErrorL'])) { ?>
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Alert!</strong> Location is connected some offices, you can not delete now.
        </div>
    <?php } ?>

    <?php if (isset($_COOKIE['deleteSuccessO'])) { ?>
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Success!</strong> Office was deleted successfully.
        </div>
    <?php } ?>

    <?php if (isset($_COOKIE['deleteErrorO'])) { ?>
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Alert!</strong> Office is connected some operations, you can not delete him now.
        </div>
    <?php } ?>

    <div class="card bg-light text-dark">
        <div class="card-header">
            Section where you can manage your customers.
            <button type="button" class="btn btn-outline-info float-right" data-toggle="modal"
                    data-target="#newLocationModal"><i class="fas fa-plus-circle"></i> Add
                New Location
            </button>
            <button type="button" class="btn btn-outline-info float-right" style="margin-right: 10px;"
                    data-toggle="modal"
                    data-target="#newOfficeModal"><i
                        class="fas fa-plus-circle"></i> Add
                New Office
            </button>
        </div>
        <div class="card-body">
            <!-- Tab panes -->
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#office">Office List</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#location">Location List</a>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div id="office" class="container tab-pane active"><br>
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Office ID</th>
                            <th onclick="sortTable(1)">Office Name</th>
                            <th onclick="sortTable(2)">Location</th>
                            <th>Process</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($offices['data'] as $office) { ?>
                            <tr>
                                <td><?php echo $office->office_id ?></td>
                                <td><?php echo $office->office_name ?></td>
                                <td><?php echo $office->get_office_location->location_name ?></td>
                                <td>
                                    <form action="admin_operations.php" method="post" style="display: inline;">
                                        <input type="hidden" id="officeID" name="officeID"
                                               value="<?php echo $office->office_id ?>">
                                        <button type="submit" name="deleteOffice" class="btn btn-danger btn-sm">
                                            <i class="fa fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div id="location" class="container tab-pane fade"><br>
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Location ID</th>
                            <th onclick="sortTable(1)">Location Name</th>
                            <th onclick="sortTable(2)">Process</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($locations['data'] as $location) { ?>
                            <tr>
                                <td><?php echo $location->location_id ?></td>
                                <td><?php echo $location->location_name ?></td>
                                <td>
                                    <form action="admin_operations.php" method="post" style="display: inline;">
                                        <input type="hidden" id="locationID" name="locationID"
                                               value="<?php echo $location->location_id ?>">
                                        <button type="submit" name="deleteLocation" class="btn btn-danger btn-sm">
                                            <i class="fa fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- New Office Modal -->
    <div class="modal fade" id="newOfficeModal" tabindex="-1" role="dialog"
         aria-labelledby="newOfficeModal"
         aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Add New Office</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="admin_operations.php" method="post">
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="officeLocation" class="col-sm-3 col-form-label">Location:</label>
                            <div class="col-sm-9">
                                <select class="form-control" name="officeLocation" id="officeLocation">
                                    <option value="0" selected>Select Location</option>
                                    <?php foreach ($locations['data'] as $location) { ?>
                                        <option value="<?php echo $location->location_id ?>"><?php echo ucfirst($location->location_name) ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="officeName" class="col-sm-3 col-form-label">Office Name:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="officeName" id="officeName"/>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- New Location Modal -->
    <div class="modal fade" id="newLocationModal" tabindex="-1" role="dialog"
         aria-labelledby="newLocationModal"
         aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Add New Location</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="admin_operations.php" method="post">
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="locationName" class="col-sm-3 col-form-label">Location Name:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="locationName" id="locationName"/>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
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

        $("#officeLocation").change(function (event) {
            $("#officeLocation option[value='0']").remove();
        });

    });

    function sortTable(n) {
        var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
        table = document.getElementById("myTable");
        switching = true;
        //Set the sorting direction to ascending:
        dir = "asc";
        /*Make a loop that will continue until
        no switching has been done:*/
        while (switching) {
            //start by saying: no switching is done:
            switching = false;
            rows = table.rows;
            /*Loop through all table rows (except the
            first, which contains table headers):*/
            for (i = 1; i < (rows.length - 1); i++) {
                //start by saying there should be no switching:
                shouldSwitch = false;
                /*Get the two elements you want to compare,
                one from current row and one from the next:*/
                x = rows[i].getElementsByTagName("TD")[n];
                y = rows[i + 1].getElementsByTagName("TD")[n];
                /*check if the two rows should switch place,
                based on the direction, asc or desc:*/
                if (dir == "asc") {
                    if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                        //if so, mark as a switch and break the loop:
                        shouldSwitch = true;
                        break;
                    }
                } else if (dir == "desc") {
                    if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                        //if so, mark as a switch and break the loop:
                        shouldSwitch = true;
                        break;
                    }
                }
            }
            if (shouldSwitch) {
                /*If a switch has been marked, make the switch
                and mark that a switch has been done:*/
                rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                switching = true;
                //Each time a switch is done, increase this count by 1:
                switchcount++;
            } else {
                /*If no switching has been done AND the direction is "asc",
                set the direction to "desc" and run the while loop again.*/
                if (switchcount == 0 && dir == "asc") {
                    dir = "desc";
                    switching = true;
                }
            }
        }
    }
</script>
</body>
</html>