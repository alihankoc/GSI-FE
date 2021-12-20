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

$analysises = $apiCaller->sendRequest(array(
    'api_url' => $_ENV['LINK'].'viewAnalysises',
    'api_method' => 'get',
));

$analysisConditions = $apiCaller->sendRequest(array(
    'api_url' => $_ENV['LINK'].'viewAnalysisConditions',
    'api_method' => 'get',
));

$surveillanceTypes = $apiCaller->sendRequest(array(
    'api_url' => $_ENV['LINK'].'viewSurveillanceTypes',
    'api_method' => 'get',
));

$processTypes = $apiCaller->sendRequest(array(
    'api_url' => $_ENV['LINK'].'viewProcessTypes',
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
        <h4>Category Management</h4>
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a class="nav-link active" href="admin_panel.php">Back To Panel</a>
            </li>
        </ul>
    </div>
</div>

<div class="container">

    <?php if (isset($_COOKIE['addSuccessA'])) { ?>
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Success!</strong> Analysis was added successfully.
        </div>
    <?php } ?>

    <?php if (isset($_COOKIE['addErrorA'])) { ?>
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Alert!</strong> There is a problem.
        </div>
    <?php } ?>

    <?php if (isset($_COOKIE['addSuccessAC'])) { ?>
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Success!</strong> Analysis condition was added successfully.
        </div>
    <?php } ?>

    <?php if (isset($_COOKIE['addErrorAC'])) { ?>
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Alert!</strong> There is a problem.
        </div>
    <?php } ?>

    <?php if (isset($_COOKIE['addSuccessP'])) { ?>
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Success!</strong> Process type was added successfully.
        </div>
    <?php } ?>

    <?php if (isset($_COOKIE['addErrorP'])) { ?>
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Alert!</strong> There is a problem.
        </div>
    <?php } ?>

    <?php if (isset($_COOKIE['addSuccessS'])) { ?>
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Success!</strong> Inspection type was added successfully.
        </div>
    <?php } ?>

    <?php if (isset($_COOKIE['addErrorS'])) { ?>
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Alert!</strong> There is a problem.
        </div>
    <?php } ?>

    <?php if (isset($_COOKIE['deleteSuccessA'])) { ?>
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Success!</strong> Analysis was deleted successfully.
        </div>
    <?php } ?>

    <?php if (isset($_COOKIE['deleteErrorA'])) { ?>
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Alert!</strong> Analysis is connected some operations, you can not delete now.
        </div>
    <?php } ?>

    <?php if (isset($_COOKIE['deleteSuccessAC'])) { ?>
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Success!</strong> Analysis condition was deleted successfully.
        </div>
    <?php } ?>

    <?php if (isset($_COOKIE['deleteErrorAC'])) { ?>
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Alert!</strong> Analysis condition is connected some operations, you can not delete now.
        </div>
    <?php } ?>

    <?php if (isset($_COOKIE['deleteSuccessP'])) { ?>
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Success!</strong> Process type was deleted successfully.
        </div>
    <?php } ?>

    <?php if (isset($_COOKIE['deleteErrorP'])) { ?>
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Alert!</strong> Process type is connected some operations, you can not delete now.
        </div>
    <?php } ?>

    <?php if (isset($_COOKIE['deleteSuccessS'])) { ?>
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Success!</strong> Inspection type was deleted successfully.
        </div>
    <?php } ?>

    <?php if (isset($_COOKIE['deleteErrorS'])) { ?>
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Alert!</strong> Inspection type is connected some operations, you can not delete now.
        </div>
    <?php } ?>

    <div class="card bg-light text-dark">
        <div class="card-header">
            <button type="button" class="btn btn-outline-info float-right" data-toggle="modal"
                    data-target="#newProcessTypeModal" style="margin-right: 10px;"><i class="fas fa-plus-circle"></i>
                Add
                New Process Type
            </button>
            <button type="button" class="btn btn-outline-info float-right" data-toggle="modal"
                    data-target="#newSurveillanceTypeModal" style="margin-right: 10px;"><i
                        class="fas fa-plus-circle"></i> Add
                New Inspection Type
            </button>
            <button type="button" class="btn btn-outline-info float-right" data-toggle="modal"
                    data-target="#newAnalysisConditionModal"><i class="fas fa-plus-circle"></i> Add
                New Analysis Condition
            </button>
            <button type="button" class="btn btn-outline-info float-right" data-toggle="modal"
                    data-target="#newAnalysisModal" style="margin-right: 10px;"><i
                        class="fas fa-plus-circle"></i> Add
                New Analysis
            </button>
        </div>
        <div class="card-body">
            <!-- Tab panes -->
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#analysis">Analysis List</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#condition">Analysis Condition List</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#inspection">Inspection Type List</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#process">Process Type List</a>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div id="analysis" class="container tab-pane active"><br>
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Analysis ID</th>
                            <th onclick="sortTable(1)">Analysis Name</th>
                            <th>Process</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($analysises['data'] as $analysis) { ?>
                            <tr>
                                <td><?php echo $analysis->analysis_id ?></td>
                                <td><?php echo $analysis->analysis_name ?></td>
                                <td>
                                    <form action="admin_operations.php" method="post" style="display: inline;">
                                        <input type="hidden" id="analysisID" name="analysisID"
                                               value="<?php echo $analysis->analysis_id ?>">
                                        <button type="submit" name="deleteAnalysis" class="btn btn-danger btn-sm">
                                            <i class="fa fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div id="condition" class="container tab-pane fade"><br>
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Analysis Condition ID</th>
                            <th onclick="sortTable(1)">Analysis Condition Name</th>
                            <th>Process</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($analysisConditions['data'] as $analysisCondition) { ?>
                            <tr>
                                <td><?php echo $analysisCondition->analysis_condition_id ?></td>
                                <td><?php echo $analysisCondition->analysis_condition_name ?></td>
                                <td>
                                    <form action="admin_operations.php" method="post" style="display: inline;">
                                        <input type="hidden" id="analysisConditionID" name="analysisConditionID"
                                               value="<?php echo $analysisCondition->analysis_condition_id ?>">
                                        <button type="submit" name="deleteAnalysisCondition"
                                                class="btn btn-danger btn-sm">
                                            <i class="fa fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div id="inspection" class="container tab-pane fade"><br>
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Inspection Type ID</th>
                            <th onclick="sortTable(1)">Inspection Type Name</th>
                            <th>Process</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($surveillanceTypes['data'] as $surveillanceType) { ?>
                            <tr>
                                <td><?php echo $surveillanceType->surveillance_type_id ?></td>
                                <td><?php echo $surveillanceType->surveillance_type_name ?></td>
                                <td>
                                    <form action="admin_operations.php" method="post" style="display: inline;">
                                        <input type="hidden" id="surveillanceTypeID" name="surveillanceTypeID"
                                               value="<?php echo $surveillanceType->surveillance_type_id ?>">
                                        <button type="submit" name="deleteSurveillanceType"
                                                class="btn btn-danger btn-sm">
                                            <i class="fa fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div id="process" class="container tab-pane fade"><br>
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Process Type ID</th>
                            <th onclick="sortTable(1)">Process Type Name</th>
                            <th>Process</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($processTypes['data'] as $processType) { ?>
                            <tr>
                                <td><?php echo $processType->process_type_id ?></td>
                                <td><?php echo $processType->process_type_name ?></td>
                                <td>
                                    <form action="admin_operations.php" method="post" style="display: inline;">
                                        <input type="hidden" id="processTypeID" name="processTypeID"
                                               value="<?php echo $processType->process_type_id ?>">
                                        <button type="submit" name="deleteProcessType" class="btn btn-danger btn-sm">
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

    <!-- New Analysis Modal -->
    <div class="modal fade" id="newAnalysisModal" tabindex="-1" role="dialog"
         aria-labelledby="newAnalysisModal"
         aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Add New Analysis</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="admin_operations.php" method="post">
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="analysisName" class="col-sm-3 col-form-label">Analysis Name:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="analysisName" id="analysisName"/>
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

    <!-- New Analysis Condition Modal -->
    <div class="modal fade" id="newAnalysisConditionModal" tabindex="-1" role="dialog"
         aria-labelledby="newAnalysisConditionModal"
         aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Add New Analysis Condition</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="admin_operations.php" method="post">
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="analysisConditionName" class="col-sm-3 col-form-label">Analysis Condition
                                Name:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="analysisConditionName"
                                       id="analysisConditionName"/>
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

    <!-- New Process Type Modal -->
    <div class="modal fade" id="newProcessTypeModal" tabindex="-1" role="dialog"
         aria-labelledby="newProcessTypeModal"
         aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Add New Process Type</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="admin_operations.php" method="post">
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="processTypeName" class="col-sm-3 col-form-label">Process Type Name:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="processTypeName" id="processTypeName"/>
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

    <!-- New Surveillance Type Modal -->
    <div class="modal fade" id="newSurveillanceTypeModal" tabindex="-1" role="dialog"
         aria-labelledby="newSurveillanceTypeModal"
         aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Add New Inspection Type</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="admin_operations.php" method="post">
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="surveillanceTypeName" class="col-sm-3 col-form-label">Inspection Type
                                Name:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="surveillanceTypeName"
                                       id="surveillanceTypeName"/>
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