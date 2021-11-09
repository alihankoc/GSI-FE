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

$customers = $apiCaller->sendRequest(array(
    'api_url' => 'https://lumen.krekpot.com/api/v1/viewCustomers',
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
        <h4>Customer Management</h4>
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
            <strong>Success!</strong> Customer was deleted successfully.
        </div>
    <?php } ?>

    <?php if (isset($_COOKIE['deleteError'])) { ?>
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Alert!</strong> Customer is connected some operations, you can not delete him now.
        </div>
    <?php } ?>

    <?php if (isset($_COOKIE['addSuccess'])) { ?>
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Success!</strong> Customer was added successfully.
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
            Section where you can manage your customers.
            <button type="button" class="btn btn-outline-info float-right" data-toggle="modal"
                    data-target="#newCustomerModal"><i
                        class="fas fa-plus-circle"></i> Add New Customer
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="myTable" style="font-size: 12px;">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th onclick="sortTable(1)">Company Name</th>
                        <th onclick="sortTable(2)">Company Shortcode</th>
                        <th onclick="sortTable(3)">Address</th>
                        <th onclick="sortTable(4)">Email</th>
                        <th onclick="sortTable(5)">Phone Number</th>
                        <th onclick="sortTable(6)">Contact Name</th>
                        <th onclick="sortTable(7)">Contact Email</th>
                        <th onclick="sortTable(8)">Contact Phone</th>
                        <th>Process</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($customers['data'] as $customer) { ?>
                        <tr>
                            <td><?php echo $customer->customer_id ?></td>
                            <td><?php echo $customer->company_name ?></td>
                            <td><?php echo $customer->company_shortcode ?></td>
                            <td><?php echo $customer->company_address ?></td>
                            <td><?php echo $customer->company_mail ?></td>
                            <td><?php echo $customer->company_phone ?></td>
                            <td><?php echo $customer->contact_person_name . ' ' . $customer->contact_person_surname. ' ('.$customer->contact_person_title.')' ?></td>
                            <td><?php echo $customer->contact_person_mail ?></td>
                            <td><?php echo $customer->contact_person_phone ?></td>
                            <td style="padding-right: 0;margin-right: 0;">

                                <button id="<?php echo $customer->customer_id ?>" class="btn btn-info btn-sm editCustomer"
                                        data-toggle="modal"
                                        data-target="#editCustomerModal">
                                    <i class="fa fa-user-edit"></i>
                                </button>

                                <form action="admin_operations.php" method="post" style="display: inline;">
                                    <input type="hidden" id="customerID" name="customerID"
                                           value="<?php echo $customer->customer_id ?>">
                                    <button type="submit" name="deleteCustomer" class="btn btn-danger btn-sm">
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

    <!-- New Customer Modal -->
    <div class="modal fade" id="newCustomerModal" tabindex="-1" role="dialog"
         aria-labelledby="newCustomerModal"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Add New Customer</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="admin_operations.php" method="post">
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="companyName" class="col-sm-3 col-form-label">Company Name:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="companyName" id="companyName"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="companyShortcode" class="col-sm-3 col-form-label">Company Shortcode:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="companyShortcode" id="companyShortcode"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="companyAddress" class="col-sm-3 col-form-label">Company Address:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="companyAddress" id="companyAddress"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="companyEmail" class="col-sm-3 col-form-label">Company Email:</label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" name="companyEmail" id="companyEmail"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="companyPhone" class="col-sm-3 col-form-label">Company Phone:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="companyPhone" id="companyPhone"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="contactName" class="col-sm-3 col-form-label">Contact Name:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="contactName" id="contactName"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="contactSurname" class="col-sm-3 col-form-label">Contact Surname:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="contactSurname" id="contactSurname"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="contactTitle" class="col-sm-3 col-form-label">Contact Title:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="contactTitle" id="contactTitle"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="contactEmail" class="col-sm-3 col-form-label">Contact Email:</label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" name="contactEmail" id="contactEmail"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="contactPhone" class="col-sm-3 col-form-label">Contact Phone:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="contactPhone" id="contactPhone"/>
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

    <!-- Edit Customer Modal -->
    <div class="modal fade" id="editCustomerModal" tabindex="-1" role="dialog"
         aria-labelledby="editCustomerModal"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Edit Customer</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="admin_operations.php" method="post">
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="editCompanyName" class="col-sm-3 col-form-label">Company Name:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="editCompanyName" id="editCompanyName"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="editCompanyShortCode" class="col-sm-3 col-form-label">Company Shortcode:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="editCompanyShortcode" id="editCompanyShortcode"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="editCompanyAddress" class="col-sm-3 col-form-label">Company Address:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="editCompanyAddress" id="editCompanyAddress"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="editCompanyEmail" class="col-sm-3 col-form-label">Company Email:</label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" name="editCompanyEmail" id="editCompanyEmail"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="editCompanyPhone" class="col-sm-3 col-form-label">Company Phone:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="editCompanyPhone" id="editCompanyPhone"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="editContactName" class="col-sm-3 col-form-label">Contact Name:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="editContactName" id="editContactName"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="editContactSurname" class="col-sm-3 col-form-label">Contact Surname:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="editContactSurname" id="editContactSurname"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="editContactTitle" class="col-sm-3 col-form-label">Contact Title:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="editContactTitle" id="editContactTitle"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="editContactEmail" class="col-sm-3 col-form-label">Contact Email:</label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" name="editContactEmail" id="editContactEmail"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="editContactPhone" class="col-sm-3 col-form-label">Contact Phone:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="editContactPhone" id="editContactPhone"/>
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

        $(".editCustomer").on('click', function () {

            //(... rest of your JS code)

            var postDatass = {selectedCustomer: $(this).attr('id')};
            $.ajax({
                type: 'POST',
                url: 'admin_operations.php',
                data: postDatass,
                dataType: 'text',
                success: function (resultData) {

                    //var userName = resultData.name;
                    var result = JSON.parse(resultData);

                    $(function () {
                        $("#editCompanyName").val(result.company_name);
                        $("#editCompanyShortcode").val(result.company_shortcode);
                        $("#editCompanyAddress").val(result.company_address);
                        $("#editCompanyEmail").val(result.company_mail);
                        $("#editCompanyPhone").val(result.company_phone);
                        $("#editContactName").val(result.contact_person_name);
                        $("#editContactSurname").val(result.contact_person_surname);
                        $("#editContactTitle").val(result.contact_person_title);
                        $("#editContactEmail").val(result.contact_person_mail);
                        $("#editContactPhone").val(result.contact_person_phone);
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