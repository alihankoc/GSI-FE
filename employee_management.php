<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 5.05.2019
 * Time: 04:00
 */

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

$users = $apiCaller->sendRequest(array(
    'api_url' => 'https://lumen.krekpot.com/api/v1/viewUsers',
    'api_method' => 'get',
));

$userTypes = $apiCaller->sendRequest(array(
    'api_url' => 'https://lumen.krekpot.com/api/v1/viewUserTypes',
    'api_method' => 'get',
));

$locations = $apiCaller->sendRequest(array(
    'api_url' => 'https://lumen.krekpot.com/api/v1/viewLocations',
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
        <h4>Employee Management</h4>
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a class="nav-link active" href="admin_panel.php">Back To Panel</a>
            </li>
        </ul>
    </div>
</div>

<div class="container">
    <?php if (isset($_COOKIE['deleteSuccess'])) { ?>
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Success!</strong> User was deleted successfully.
        </div>
    <?php } ?>

    <?php if (isset($_COOKIE['deleteError'])) { ?>
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Alert!</strong> User is connected some operations, you can not delete him now.
        </div>
    <?php } ?>

    <?php if (isset($_COOKIE['addSuccess'])) { ?>
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Success!</strong> User was added successfully.
        </div>
    <?php } ?>

    <?php if (isset($_COOKIE['addError'])) { ?>
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Alert!</strong> There is a problem.
        </div>
    <?php } ?>

    <div class="card bg-light text-dark">
        <div class="card-header">
            Section where you can manage your employee accounts.
            <button type="button" class="btn btn-outline-info float-right" data-toggle="modal"
                    data-target="#newUserModal"><i
                        class="fas fa-plus-circle"></i> Add New Employee
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="myTable">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th onclick="sortTable(1)">Name</th>
                        <th onclick="sortTable(2)">Surname</th>
                        <th onclick="sortTable(3)">Email</th>
                        <th onclick="sortTable(4)">Phone Number</th>
                        <th onclick="sortTable(5)">Type</th>
                        <th onclick="sortTable(6)">Location</th>
                        <th onclick="sortTable(7)">Office</th>
                        <th>Process</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($users['data'] as $user) { ?>
                        <tr>
                            <td><?php echo $user->user_id ?></td>
                            <td><?php echo $user->name ?></td>
                            <td><?php echo $user->surname ?></td>
                            <td><?php echo $user->email ?></td>
                            <td><?php echo $user->phone_number ?></td>
                            <td><?php echo ucfirst($user->get_user_type->type_name) ?></td>
                            <td><?php echo $user->get_office->office_name ?></td>
                            <td><?php echo $user->get_office->get_office_location->location_name ?></td>
                            <td style="padding-right: 0;margin-right: 0;">

                                <button id="<?php echo $user->user_id ?>" class="btn btn-info btn-sm editUser"
                                        data-toggle="modal"
                                        data-target="#editUserModal">
                                    <i class="fa fa-user-edit"></i>
                                </button>

                                <form action="admin_operations.php" method="post" style="display: inline;">
                                    <input type="hidden" id="userID" name="userID" value="<?php echo $user->user_id ?>">
                                    <button type="submit" name="deleteUser" class="btn btn-danger btn-sm">
                                        <i class="fa fa-user-times"></i>
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

    <!-- New User Modal -->
    <div class="modal fade" id="newUserModal" tabindex="-1" role="dialog"
         aria-labelledby="newUserModal"
         aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Add New Employee</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="admin_operations.php" method="post">
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="userName" class="col-sm-3 col-form-label">Name:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="userName" id="userName"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="userSurname" class="col-sm-3 col-form-label">Surname:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="userSurname" id="userSurname"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="userEmail" class="col-sm-3 col-form-label">Email:</label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" name="userEmail" id="userEmail"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="userPassword" class="col-sm-3 col-form-label">Password:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="userPassword" id="userPassword"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="userPhone" class="col-sm-3 col-form-label">Phone:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="userPhone" id="userPhone"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="userType" class="col-sm-3 col-form-label">User Type:</label>
                            <div class="col-sm-9">
                                <select class="form-control" name="userType" id="userType">
                                    <?php foreach ($userTypes['data'] as $userType) { ?>
                                        <option value="<?php echo $userType->type_id ?>"><?php echo ucfirst($userType->type_name) ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="userLocation" class="col-sm-3 col-form-label">Location:</label>
                            <div class="col-sm-9">
                                <select class="form-control" name="userLocation" id="userLocation">
                                    <option value="0" selected>Select Location</option>
                                    <?php foreach ($locations['data'] as $location) { ?>
                                        <option value="<?php echo $location->location_id ?>"><?php echo ucfirst($location->location_name) ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="userOffice" class="col-sm-3 col-form-label">Office:</label>
                            <div class="col-sm-9">
                                <select class="form-control" name="userOffice" id="userOffice" disabled>
                                    <option value="0">Select Office</option>
                                </select>
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

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog"
         aria-labelledby="editUserModal"
         aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Edit Employee</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="admin_operations.php" method="post">
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="editUserName" class="col-sm-3 col-form-label">Name:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="editUserName" id="editUserName"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="editUserSurname" class="col-sm-3 col-form-label">Surname:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="editUserSurname" id="editUserSurname"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="editUserEmail" class="col-sm-3 col-form-label">Email:</label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" name="editUserEmail" id="editUserEmail"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="editUserPhone" class="col-sm-3 col-form-label">Phone:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="editUserPhone" id="editUserPhone"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="editUserType" class="col-sm-3 col-form-label">User Type:</label>
                            <div class="col-sm-9">
                                <select class="form-control" name="editUserType" id="editUserType">

                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="editUserLocation" class="col-sm-3 col-form-label">Location:</label>
                            <div class="col-sm-9">
                                <select class="form-control" name="editUserLocation" id="editUserLocation">

                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="editUserOffice" class="col-sm-3 col-form-label">Office:</label>
                            <div class="col-sm-9">
                                <select class="form-control" name="editUserOffice" id="editUserOffice">

                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
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

        var i = 0;

        $("#userLocation").change(function (event) {

            event.preventDefault();

            if (i === 0) {
                $("#userLocation option[value='0']").remove();


                var postData = {selectedLocation: $("#userLocation").val()};
                $.ajax({
                    type: 'POST',
                    url: 'admin_operations.php',
                    data: postData,
                    dataType: 'text',
                    success: function (resultData) {

                        var result = JSON.parse(resultData);

                        for (var j = 0; j < result.length; j++) {

                            var o = new Option(result[j].office_name, result[j].office_id);
                            $(o).html(result[j].office_name);
                            $("#userOffice").append(o);

                        }

                        var isDisabled = $('#userOffice').prop('disabled');
                        if (isDisabled) {
                            $('#userOffice').prop('disabled', false);
                        }

                    }
                });


            } else {

                $('#userOffice').find('option').remove().end();

                var postDatas = {selectedLocation: $("#userLocation").val()};
                $.ajax({
                    type: 'POST',
                    url: 'admin_operations.php',
                    data: postDatas,
                    dataType: 'text',
                    success: function (resultData) {

                        var result = JSON.parse(resultData);

                        for (var j = 0; j < result.length; j++) {

                            var o = new Option(result[j].office_name, result[j].office_id);
                            $(o).html(result[j].office_name);
                            $("#userOffice").append(o);

                        }
                    }
                });


            }
            i++;
        });

        $("#userOffice").change(function () {
            $("#userOffice option[value='0']").remove();
        });

        $(".editUser").on('click', function (event) {

            //(... rest of your JS code)

            var postDatass = {selectedUser: $(this).attr('id')};
            $.ajax({
                type: 'POST',
                url: 'admin_operations.php',
                data: postDatass,
                dataType: 'text',
                success: function (resultData) {

                    //var userName = resultData.name;
                    var result = JSON.parse(resultData);

                    $(function () {
                        $("#editUserName").val(result.name);
                        $("#editUserSurname").val(result.surname);
                        $("#editUserEmail").val(result.email);
                        $("#editUserPhone").val(result.phone_number);
                    });

                }
            });

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
