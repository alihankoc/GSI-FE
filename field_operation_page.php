<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 14.02.2019
 * Time: 02:54
 */

include_once "url_slug.php";
include_once "ApiCaller.php";

session_start();
date_default_timezone_set('Asia/Istanbul');

if (!isset($_SESSION["token"])) {
    header('Location: emp_login_page.php');
    setcookie("pleaseLogin", "pleaseLogin", time() + (2));
    exit();
}

switch ($_SESSION["user_type_id"]) {
    case "1":
        header('Location: admin_panel.php');
        break;
    case "2":
        header('Location: officer_main_page.php');
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


$apiCaller = new ApiCaller('1', $_SESSION['token']);

$waitingOperations = $apiCaller->sendRequest(array(
    'api_url' => 'https://lumen.krekpot.com/api/v1/getOperationsField/1',
    'api_method' => 'get',
));
$activeOperations = $apiCaller->sendRequest(array(
    'api_url' => 'https://lumen.krekpot.com/api/v1/getOperationsField/2',
    'api_method' => 'get',
));
$completedOperations = $apiCaller->sendRequest(array(
    'api_url' => 'https://lumen.krekpot.com/api/v1/getOperationsField/3',
    'api_method' => 'get',
));
$equipments = $apiCaller->sendRequest(array(
    'api_url' => 'https://lumen.krekpot.com/api/v1/viewEquipmentTypes/',
    'api_method' => 'get',
));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <title><?php echo url_slug($_SERVER["PHP_SELF"]); ?></title>

    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.min.css"
          crossorigin="anonymous">

    <link rel="stylesheet" type="text/css" href="css/custom.css">
    <link rel="stylesheet" type="text/css" href="css/all.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap4-toggle.css">
    <link rel="stylesheet" type="text/css" href="css/select2.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap-select.css">
    <link rel="stylesheet" type="text/css" href="plugins/kartik-v-fileinput/css/fileinput.min.css">

    <script type="text/javascript" src="js/jquery-3.3.1.js"></script>
    <script type="text/javascript" src="js/popper.js"></script>
    <!--<script type="text/javascript" src="js/bootstrap.js"></script>-->
    <script type="text/javascript" src="js/bootstrap4-toggle.js"></script>
    <script type="text/javascript" src="js/select2.js"></script>
    <script type="text/javascript" src="js/bootstrap-select.js"></script>

    <script type="text/javascript" src="plugins/kartik-v-fileinput/js/plugins/piexif.min.js"></script>
    <script type="text/javascript" src="plugins/kartik-v-fileinput/js/plugins/sortable.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="plugins/kartik-v-fileinput/js/fileinput.min.js"></script>
    <script type="text/javascript" src="plugins/kartik-v-fileinput/js/locales/tr.js"></script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">
        <img src="img/general-survey-gozetme-ltd-sti.png" alt="Logo" style="width:200px;">
    </a>

    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown"
               aria-haspopup="true" aria-expanded="false">
                <span class="fas fa-clock"></span>
                <div id="timerMsg" class="d-inline">
                    <?php
                    if (isset($_COOKIE["refreshTimer"])) {
                        if ($_COOKIE["refreshTimer"] == "off") {
                            echo "Off";
                        } elseif ($_COOKIE["refreshTimer"] == "5") {
                            echo "5 min";
                        }
                    }
                    ?>
                </div>
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                <a class="dropdown-item refreshOff" href="#">Off</a>
                <a class="dropdown-item refresh5" href="#">5 min</a>
            </div>
        </li>
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
        <h4>Inspector Operations</h4>
    </div>
</div>

<div class="container">


    <?php foreach ($_COOKIE as $key => $value) {
        if (substr($key, 0, strlen("success")) === "success") {
            ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong><?php echo $value ?></strong>
                <button id="<?php echo $key; ?>" type="button" class="close removeCookieClass" data-dismiss="alert"
                        aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php
        } elseif (substr($key, 0, strlen("error")) === "error") { ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><?php echo $value ?></strong>
                <button id="<?php echo $key; ?>" type="button" class="close removeCookieClass" data-dismiss="alert"
                        aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php }
    } ?>


    <div class="card bg-light text-dark">
        <div class="card-header">
            Section where you can follow the operations connected to you.
            <button type="button" class="btn btn-outline-primary btn-sm float-right printTable"
                    style="margin-right: 5px;"><i
                        class="fas fa-print"></i> Print
            </button>
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a id="waitingTabLink" class="nav-link active" data-toggle="tab" href="#waiting">Waiting List <span
                                class="badge badge-primary"><?php echo $waitingOperations['data']->count_of_opt; ?></span></a>
                </li>
                <li class="nav-item">
                    <a id="activeTabLink" class="nav-link" data-toggle="tab" href="#active">Active List <span
                                class="badge badge-success"><?php echo $activeOperations['data']->count_of_opt; ?></span></a>
                </li>
                <li class="nav-item">
                    <a id="completedTabLink" class="nav-link" data-toggle="tab" href="#completed">My Completed List
                        <span
                                class="badge badge-secondary"><?php echo $completedOperations['data']->count_of_opt; ?></span></a>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div id="waiting" class="container tab-pane active">
                    <div class="input-group" style="margin-top: 10px;">
                        <input id="myInputWaiting" type="text" class="form-control"
                               placeholder="Type something for search..">
                        <div class="input-group-append">
                            <button class="btn btn-secondary" type="button">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped" id="waitingTable" style="font-size: 12px;">
                            <thead>
                            <tr>
                                <th onclick="sortTable(0,'waitingTable','str')">#</th>
                                <th onclick="sortTable(1,'waitingTable','str')">Customer</th>
                                <th onclick="sortTable(2,'waitingTable','str')">Nomination Customer</th>
                                <th onclick="sortTable(3,'waitingTable','date')">Date</th>
                                <th onclick="sortTable(4,'waitingTable','str')">Vessel Name</th>
                                <th onclick="sortTable(5,'waitingTable','str')">Goods</th>
                                <th onclick="sortTable(6,'waitingTable','int')">Amount</th>
                                <th onclick="sortTable(7,'waitingTable','str')">Officer</th>
                                <th>Location</th>
                                <th>Processes</th>
                            </tr>
                            </thead>
                            <tbody id="myTableWaiting">
                            <?php foreach ($waitingOperations['data']->operations_waiting as $opt) { ?>
                                <tr>
                                    <td class="waitingOperationID">GSI<?php echo $opt->operation_id; ?></td>
                                    <td class="waitingCustomer">
                                        <strong><?php echo $opt->customer->company_shortcode . "</strong><br/>(" . $opt->customer->contact_person_title . ") " . $opt->customer->contact_person_name . " " . $opt->customer->contact_person_surname . "<br/>" . $opt->customer->contact_person_phone; ?>
                                    </td>
                                    <td class="waitingNomCustomer">
                                        <strong><?php echo ($opt->nomination_customer_id == null) ? "-" : $opt->nomination_customer->company_shortcode . "</strong><br/>(" . $opt->nomination_customer->contact_person_title . ") " . $opt->nomination_customer->contact_person_name . " " . $opt->nomination_customer->contact_person_surname . "<br/>" . $opt->nomination_customer->contact_person_phone; ?>
                                    </td>
                                    <td class="waitingDate"><?php echo date("d-m-Y H:i:s", strtotime($opt->created_at)); ?></td>
                                    <td class="waitingVessel"><?php echo $opt->vessel_name; ?></td>
                                    <td class="waitingGoods"><?php echo $opt->type_of_goods; ?></td>
                                    <td class="waitingAmount"><?php echo $opt->amount; ?></td>
                                    <td class="waitingOfficer"><?php echo $opt->creator->name . " " . $opt->creator->surname; ?></td>
                                    <td class="waitingLocations">
                                        <?php
                                        if (!empty($opt->offices)) {
                                            $officeCount = 0;
                                            $officeArrayLength = count($opt->offices);
                                            foreach ($opt->offices as $office) { ?>
                                                <?php if ($officeCount === $officeArrayLength - 1 || $officeArrayLength === 1) { ?>
                                                    <strong><?php echo $office->office_name; ?></strong><br/>
                                                <?php } else { ?>
                                                    <strong><?php echo $office->office_name; ?> - </strong><br/>
                                                <?php }
                                                $officeCount++;
                                            }
                                        } ?>
                                    </td>
                                    <td>
                                        <div class="btn-group dropdown">
                                            <button type="button" class="btn btn-primary dropdown-toggle btn-sm"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Menu
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item viewDetailedButtonWaiting" data-toggle="modal"
                                                   data-target="#viewDetailedModalWaiting" data-backdrop="static"
                                                   data-keyboard="false"
                                                   href="#" id="<?php echo $opt->operation_id; ?>">View Detailed</a>
                                                <a class="dropdown-item acceptOperationButtonWaiting"
                                                   data-toggle="modal"
                                                   data-target="#warnAcceptW" data-backdrop="static"
                                                   data-keyboard="false"
                                                   id="<?php echo $opt->operation_id; ?>" href="#">Accept Operation</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php }
                            if (empty((array)$waitingOperations['data']->operations_waiting)) {
                                ?>
                                <tr>
                                    <td colspan="10" class="text-center">
                                        <p><strong>There is no waiting operation.</strong></p>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="active" class="container tab-pane fade">
                    <div class="input-group" style="margin-top: 10px;">
                        <input id="myInputActive" type="text" class="form-control"
                               placeholder="Type something for search..">
                        <div class="input-group-append">
                            <button class="btn btn-secondary" type="button">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped" id="activeTable" style="font-size: 12px;">
                            <thead>
                            <tr>
                                <th onclick="sortTable(0,'activeTable','str')">#</th>
                                <th onclick="sortTable(1,'activeTable','str')">Customer</th>
                                <th onclick="sortTable(2,'activeTable','str')">Nomination Customer</th>
                                <th onclick="sortTable(3,'activeTable','date')">Created Date</th>
                                <th onclick="sortTable(4,'activeTable','date')">Started Date</th>
                                <th onclick="sortTable(5,'activeTable','str')">Vessel Name</th>
                                <th onclick="sortTable(6,'activeTable','str')">Goods</th>
                                <th onclick="sortTable(7,'activeTable','int')">Amount</th>
                                <th onclick="sortTable(8,'activeTable','str')">Officer</th>
                                <th onclick="sortTable(9,'activeTable','str')">Inspectors</th>
                                <th>Location</th>
                                <th>Last Update</th>
                                <th>Processes</th>
                            </tr>
                            </thead>
                            <tbody id="myTableActive">
                            <?php foreach ($activeOperations['data']->operations_active as $opt) {
                                $isMyOperation = false;
                                ?>
                                <tr>
                                    <td class="activeOperationID">GSI<?php echo $opt->operation_id; ?></td>
                                    <td class="activeCustomer">
                                        <strong><?php echo $opt->customer->company_shortcode . "</strong><br/>(" . $opt->customer->contact_person_title . ") " . $opt->customer->contact_person_name . " " . $opt->customer->contact_person_surname . "<br/>" . $opt->customer->contact_person_phone; ?>
                                    </td>
                                    <td class="activeNomCustomer">
                                        <strong><?php echo ($opt->nomination_customer_id == null) ? "-" : $opt->nomination_customer->company_shortcode . "</strong><br/>(" . $opt->nomination_customer->contact_person_title . ") " . $opt->nomination_customer->contact_person_name . " " . $opt->nomination_customer->contact_person_surname . "<br/>" . $opt->nomination_customer->contact_person_phone; ?>
                                    </td>
                                    <td class="activeDate"><?php echo date("d-m-Y H:i:s", strtotime($opt->created_at)); ?></td>
                                    <td><?php echo(!empty($opt->start_date) ? date("d-m-Y H:i:s", strtotime($opt->start_date)) : "null"); ?></td>
                                    <td class="activeVessel"><?php echo $opt->vessel_name; ?></td>
                                    <td class="activeGoods"><?php echo $opt->type_of_goods; ?></td>
                                    <td class="activeAmount"><?php echo $opt->amount; ?></td>
                                    <td class="activeOfficer"><?php echo $opt->creator->name . " " . $opt->creator->surname; ?></td>
                                    <td><?php
                                        foreach ($opt->inspectors as $inspector) { ?>
                                            <strong><?php echo $inspector->name . " " . $inspector->surname; ?>
                                                :</strong>
                                            <br/><?php echo $inspector->phone_number; ?><br/>
                                        <?php } ?></td>
                                    <td>
                                        <?php
                                        if (!empty($opt->offices)) {
                                            $officeCount = 0;
                                            $officeArrayLength = count($opt->offices);
                                            foreach ($opt->offices as $office) { ?>
                                                <?php if ($officeCount === $officeArrayLength - 1 || $officeArrayLength === 1) { ?>
                                                    <strong><?php echo $office->office_name; ?></strong><br/>
                                                <?php } else { ?>
                                                    <strong><?php echo $office->office_name; ?> - </strong><br/>
                                                <?php }
                                                $officeCount++;
                                            }
                                        } ?>
                                    </td>
                                    <td><?php echo date("d-m-Y H:i:s", strtotime($opt->updated_at)); ?></td>
                                    <td>
                                        <div class="btn-group dropdown">
                                            <button type="button" class="btn btn-primary dropdown-toggle btn-sm"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Menu
                                            </button>
                                            <div class="dropdown-menu">
                                                <?php if (!isset($opt->isOwner)) { ?>
                                                    <a class="dropdown-item joinOperationButtonWaiting"
                                                       data-toggle="modal"
                                                       data-target="#warnJoinW" data-backdrop="static"
                                                       data-keyboard="false"
                                                       id="<?php echo $opt->operation_id; ?>" href="#">Join
                                                        Operation</a>
                                                <?php } ?>
                                                <?php if (isset($opt->isOwner)) { ?>
                                                    <?php if (!isset($opt->isSurvCompleted)) { ?>
                                                        <a class="dropdown-item survInfoButton" data-toggle="modal"
                                                           data-target="#surveillanceInfoModal" data-backdrop="static"
                                                           data-keyboard="false"
                                                           href="#" id="<?php echo $opt->operation_id; ?>">Surveillance
                                                            Info
                                                            Form</a>
                                                        <a class="dropdown-item uploadPhotoButton" data-toggle="modal"
                                                           data-target="#surveillancePhotoModal" data-backdrop="static"
                                                           data-keyboard="false"
                                                           href="#" id="<?php echo $opt->operation_id; ?>">Surveillance
                                                            Photos</a>
                                                        <a class="dropdown-item uploadDocumentButton"
                                                           data-toggle="modal"
                                                           data-target="#surveillanceDocumentModal"
                                                           data-backdrop="static"
                                                           data-keyboard="false"
                                                           href="#" id="<?php echo $opt->operation_id; ?>">Surveillance
                                                            Documents</a>
                                                    <?php } else { ?>
                                                        <a class="dropdown-item disabled" href="#">Form Completed</a>
                                                    <?php } ?>
                                                    <a class="dropdown-item showOperationNotes" data-toggle="modal"
                                                       data-target="#viewOperationNotesModal" data-backdrop="static"
                                                       data-keyboard="false"
                                                       href="#" id="<?php echo $opt->operation_id; ?>">Show Operation
                                                        Notes</a>
                                                    <a class="dropdown-item addNewOperationNoteButton"
                                                       data-toggle="modal"
                                                       data-target="#addNewOperationNoteModal" data-backdrop="static"
                                                       data-keyboard="false"
                                                       href="#" id="<?php echo $opt->operation_id; ?>">Add Note</a>
                                                    <a class="dropdown-item showOperationExpenses" data-toggle="modal"
                                                       data-target="#viewOperationExpensesModal" data-backdrop="static"
                                                       data-keyboard="false"
                                                       href="#" id="<?php echo $opt->operation_id; ?>">Show My
                                                        Expenses</a>
                                                    <a class="dropdown-item addNewOperationExpenseButton"
                                                       data-toggle="modal"
                                                       data-target="#addNewOperationExpenseModal" data-backdrop="static"
                                                       data-keyboard="false"
                                                       href="#" id="<?php echo $opt->operation_id; ?>">Add Expense</a>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php }
                            if (empty((array)$activeOperations['data']->operations_active)) {
                                ?>
                                <tr>
                                    <td colspan="13" class="text-center">
                                        <p><strong>There is no active operation.</strong></p>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="completed" class="container tab-pane fade">
                    <div class="input-group" style="margin-top: 10px;">
                        <input id="myInputCompleted" type="text" class="form-control"
                               placeholder="Type something for search..">
                        <div class="input-group-append">
                            <button class="btn btn-secondary" type="button">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped" id="completedTable" style="font-size: 12px;">
                            <thead>
                            <tr>
                                <th onclick="sortTable(0,'completedTable','str')">#</th>
                                <th onclick="sortTable(1,'completedTable','str')">Customer</th>
                                <th onclick="sortTable(2,'completedTable','str')">Nomination Customer</th>
                                <th onclick="sortTable(3,'completedTable','date')">Started Date</th>
                                <th onclick="sortTable(4,'completedTable','date')">Completed Date</th>
                                <th onclick="sortTable(5,'completedTable','str')">Vessel Name</th>
                                <th onclick="sortTable(6,'completedTable','str')">Goods</th>
                                <th onclick="sortTable(7,'completedTable','int')">Amount</th>
                                <th onclick="sortTable(8,'completedTable','str')">Officer</th>
                                <th onclick="sortTable(9,'completedTable','str')">Expert</th>
                                <th onclick="sortTable(10,'completedTable','str')">Inspectors</th>
                                <th>Location</th>
                                <th>Processes</th>
                            </tr>
                            </thead>
                            <tbody id="myTableCompleted">
                            <?php foreach ($completedOperations['data']->inspector_operations as $opt) { ?>
                                <tr>
                                    <td>GSI<?php echo $opt->operation_id; ?></td>
                                    <td>
                                        <strong><?php echo $opt->customer->company_shortcode . "</strong><br/>(" . $opt->customer->contact_person_title . ") " . $opt->customer->contact_person_name . " " . $opt->customer->contact_person_surname . "<br/>" . $opt->customer->contact_person_phone; ?>
                                    </td>
                                    <td>
                                        <strong><?php echo (is_null($opt->nomination_customer)) ? "-" : $opt->nomination_customer->company_shortcode . "</strong><br/>(" . $opt->nomination_customer->contact_person_title . ") " . $opt->nomination_customer->contact_person_name . " " . $opt->nomination_customer->contact_person_surname . "<br/>" . $opt->nomination_customer->contact_person_phone; ?>
                                    </td>
                                    <td><?php echo(!empty($opt->start_date) ? date("d-m-Y H:i:s", strtotime($opt->start_date)) : "null"); ?></td>
                                    <td><?php echo(!empty($opt->finish_date) ? date("d-m-Y H:i:s", strtotime($opt->finish_date)) : "null"); ?></td>
                                    <td><?php echo $opt->vessel_name; ?></td>
                                    <td><?php echo $opt->type_of_goods; ?></td>
                                    <td><?php echo $opt->amount; ?></td>
                                    <td><?php echo $opt->creator->name . " " . $opt->creator->surname; ?></td>
                                    <td><?php echo(!empty($opt->completer) ? $opt->completer->name . " " . $opt->completer->surname : "-"); ?></td>
                                    <td><?php
                                        foreach ($opt->inspectors as $inspector) { ?>
                                            <strong><?php echo $inspector->name . " " . $inspector->surname; ?>
                                                :</strong>
                                            <br/><?php echo $inspector->phone_number; ?><br/>
                                        <?php } ?></td>
                                    <td>
                                        <?php
                                        if (!empty($opt->offices)) {
                                            $officeCount = 0;
                                            $officeArrayLength = count($opt->offices);
                                            foreach ($opt->offices as $office) { ?>
                                                <?php if ($officeCount === $officeArrayLength - 1 || $officeArrayLength === 1) { ?>
                                                    <strong><?php echo $office->office_name; ?></strong><br/>
                                                <?php } else { ?>
                                                    <strong><?php echo $office->office_name; ?> - </strong><br/>
                                                <?php }
                                                $officeCount++;
                                            }
                                        } ?>
                                    </td>
                                    <td>
                                        <div class="btn-group dropdown">
                                            <a role="button" class="btn btn-primary btn-sm" target="_blank"
                                               href="surveillance_info_form_field.php?singleSurveillanceFormID=<?php echo $opt->surv_form->info_form_id; ?>">
                                                View Detailed
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php }
                            if (empty((array)$completedOperations['data']->inspector_operations)) {
                                ?>
                                <tr>
                                    <td colspan="13" class="text-center">
                                        <p><strong>There is no completed operation.</strong></p>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Accept Operation Modal -->
<div id="warnAcceptW" class="modal fade" role="dialog" data-backdrop="false">
    <div class="modal-dialog modal-dialog-centered">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                Warning
            </div>
            <form id="acceptOperationForm" name="acceptOperationForm" action="field_operations.php" method="post">
                <input type="hidden" name="acceptOperationID" id="acceptOperationID"/>
                <div class="modal-body">
                    <p id="acceptOperationWarning"></p>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary closeAcceptOperationModal">Close</button>
                    <button type="submit" class="btn btn-primary" value="1">Accept Operation</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add New Expense -->
<div class="modal fade" id="addNewOperationExpenseModal" tabindex="-1" role="dialog"
     aria-labelledby="addNewOperationExpenseModalTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addNewOperationExpenseModalTitle">Add New Expense</h5>
            </div>

            <form id="addNewOperationExpenseForm" name="addNewOperationExpenseForm" action="field_operations.php"
                  method="post">

                <input type="hidden" id="addNewOperationExpenseOptID" name="addNewOperationExpenseOptID" value="0"/>

                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-3"><h6>Operation: #<strong class="text-muted"
                                                                      id="addNewOperationExpenseHeader"></strong></h6>
                        </div>
                    </div>
                    <hr/>


                    <div class="form-group">
                        <label for="operationExpense" style="font-weight: 500;">Content:</label>
                        <input type="text" class="form-control" id="operationExpense" name="operationExpense" required>
                    </div>

                    <div class="form-group">
                        <label for="operationExpenseAmount" style="font-weight: 500;">Amount:</label>
                        <input type=number min="0" step="0.001" class="form-control" id="operationExpenseAmount"
                               name="operationExpenseAmount" required>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary closeAddNewOperationExpenseModal">Close</button>
                    <button type="submit" class="btn btn-primary" value="1">Save</button>
                </div>
            </form>


        </div>
    </div>
</div>

<!-- Add New Operation Note -->
<div class="modal fade" id="addNewOperationNoteModal" tabindex="-1" role="dialog"
     aria-labelledby="addNewOperationNoteModalTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addNewOperationNoteModalTitle">Add New Operation Note</h5>
            </div>

            <form id="addNewOperationNoteForm" name="addNewOperationNoteForm" action="field_operations.php"
                  method="post">

                <input type="hidden" id="addNewOperationNoteOptID" name="addNewOperationNoteOptID" value="0"/>

                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-3"><h6>Operation: #<strong class="text-muted"
                                                                      id="addNewOperationNoteHeader"></strong></h6>
                        </div>
                        <div class="col-md-3"><h6>Customer: <strong class="text-muted"
                                                                    id="addNewOperationNoteCustomerHeader"></strong>
                            </h6>
                        </div>
                    </div>
                    <hr/>


                    <div class="form-group">
                        <label for="operationNote" style="font-weight: 500;">Note:</label>
                        <textarea class="form-control" id="operationNote"
                                  name="operationNote" rows="3" required></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary closeAddNewOperationNoteModal">Close</button>
                    <button type="submit" class="btn btn-primary" value="1">Save</button>
                </div>
            </form>


        </div>
    </div>
</div>

<!-- Show Expenses Modal -->
<div class="modal fade" id="viewOperationExpensesModal" tabindex="-1" role="dialog"
     aria-labelledby="viewOperationExpensesModalTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewOperationExpensesModalTitle">My Operation Expenses</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-3"><h6>Operation: #<strong class="text-muted"
                                                                  id="operationExpenseOptHeader"></strong></h6>
                    </div>
                </div>
                <hr/>

                <div id="operationExpenseHere"></div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Show Operation Notes Modal -->
<div class="modal fade" id="viewOperationNotesModal" tabindex="-1" role="dialog"
     aria-labelledby="viewOperationNotesModalTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Operation Notes</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-3"><h6>Operation: #<strong class="text-muted"
                                                                  id="operationNotesOptHeader"></strong></h6>
                    </div>
                    <div class="col-md-3"><h6>Customer: <strong class="text-muted"
                                                                id="operationNotesCustomerHeader"></strong>
                        </h6>
                    </div>
                </div>
                <hr/>

                <div id="operationNotesHere"></div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Join Operation Modal -->
<div id="warnJoinW" class="modal fade" role="dialog" data-backdrop="false">
    <div class="modal-dialog modal-dialog-centered">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                Warning
            </div>
            <form id="joinOperationForm" name="joinOperationForm" action="field_operations.php" method="post">
                <input type="hidden" name="joinOperationID" id="joinOperationID"/>
                <div class="modal-body">
                    <p id="joinOperationWarning"></p>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary closeJoinOperationModal">Close</button>
                    <button type="submit" class="btn btn-primary" value="1">Join Operation</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Part For Waiting View Detailed -->
<div class="modal fade" id="viewDetailedModalWaiting" tabindex="-1" role="dialog"
     aria-labelledby="viewDetailedModalWaitingTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="viewDetailedModalWaitingTitle">Operation: <strong
                            id="optViewDW">#234</strong><br/> Customer:
                    <strong id="custViewDW"></strong><br/>Nomination Customer: <strong id="nomCustViewDW"></strong><br/>
                    Buyer: <strong
                            id="buyerViewDW"
                            style="text-transform:capitalize;"></strong><br/>
                    Seller:
                    <strong
                            id="sellerViewDW" style="text-transform:capitalize;"></strong><br/> Supplier: <strong
                            id="supplierViewDW" style="text-transform:capitalize;"></strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="font-size: 14px;">


                <h6 style="font-size: 16px;">Informations</h6>
                <hr/>
                <form>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="vesselViewDW">Vessel Name</label>
                            <input type="text" class="form-control form-control-sm" id="vesselViewDW" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="goodsViewDW">Type Of Goods</label>
                            <input type="text" class="form-control form-control-sm" id="goodsViewDW" readonly>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="amountViewDW">Amount</label>
                            <input type="text" class="form-control form-control-sm" id="amountViewDW" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="ilocViewDW">Inspection Locations</label>
                            <input type="text" class="form-control form-control-sm" id="ilocViewDW" readonly>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="survTypeViewDW"><b>Surveillance Types</b></label>
                            <div id="survTypeViewDW"></div>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="procTypeViewDW"><b>Process Types</b></label>
                            <div id="procTypeViewDW"></div>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="anlCondViewDW"><b>Analysis Conditions</b></label>
                            <div id="anlCondViewDW"></div>
                        </div>
                    </div>
                </form>

                <h6 style="font-size: 16px; margin-top: 10px;">Requested Surveillance</h6>
                <hr/>
                <div class="row" id="reqSurvViewDW">
                </div>

                <h6 style="font-size: 16px; margin-top: 20px;">Form Owner & Date</h6>
                <hr/>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="">Officer & Creation Date</span>
                    </div>
                    <input type="text" class="form-control" id="officerViewDW" readonly>
                    <input type="text" class="form-control" id="dateViewDW" readonly>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<!-- Organize Photos for Surveillance Information Form -->
<div class="modal fade" id="surveillancePhotoModal" tabindex="-1" role="dialog"
     aria-labelledby="surveillancePhotoModalTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">

                <h6 class="modal-title" id="surveillancePhotoModalTitle">Operation: <strong
                            id="optSurvPhoto">#234</strong><br/> Customer:
                    <strong id="custSurvPhoto">Company name</strong></h6>
                <h5 class="modal-title" id="surveillancePhotoModalTitle">Surveillance Photo Form <strong
                            id="formSurvPhoto">#1</strong><br/> Inspector:
                    <strong id="insSurvPhoto" style="font-size: 16px;">Inspector Name: </strong></h5>

            </div>
            <div class="modal-body">
                <h6 style="font-size: 16px;">New Uploads</h6>
                <form id="uploadPhotoForm" name="uploadPhotoForm" action="field_operations.php" method="post"
                      enctype="multipart/form-data">
                    <input type="hidden" name="photoFormID" id="photoFormID"/>
                    <input id="myPhotoFile" name="myPhotoFile[]" type="file" multiple>
                    <button type="submit" class="btn btn-primary float-right pt-2 mt-2" value="1">Upload</button>
                </form>

                <br>

                <h6 class="pt-3" style="font-size: 16px;">Remove Uploads</h6>
                <hr/>

                <form id="removePhotoForm" name="removePhotoForm" action="field_operations.php" method="post">
                    <input type="hidden" name="removeFormID" id="removeFormID"/>


                    <div class="row" id="photo-history">
                    </div>

                    <button type="submit" class="btn btn-primary float-right pt-2 mt-2" value="1">Remove</button>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary closePhotoModal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Organize Documents for Surveillance Information Form -->
<div class="modal fade" id="surveillanceDocumentModal" tabindex="-1" role="dialog"
     aria-labelledby="surveillanceDocumentModalTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">

                <h6 class="modal-title" id="surveillanceDocumentModalTitle">Operation: <strong
                            id="optSurvDocument">#234</strong><br/> Customer:
                    <strong id="custSurvDocument">Company name</strong></h6>
                <h5 class="modal-title" id="surveillanceDocumentModalTitle">Surveillance Document Form <strong
                            id="formSurvDocument">#1</strong><br/> Inspector:
                    <strong id="insSurvDocument" style="font-size: 16px;">Inspector Name: </strong></h5>

            </div>
            <div class="modal-body">
                <h6 style="font-size: 16px;">New Uploads</h6>
                <form id="uploadDocumentForm" name="uploadDocumentForm" action="field_operations.php" method="post"
                      enctype="multipart/form-data">
                    <input type="hidden" name="documentFormID" id="documentFormID"/>
                    <input id="myDocumentFile" name="myDocumentFile[]" type="file" multiple>
                    <button type="submit" class="btn btn-primary float-right pt-2 mt-2" value="1">Upload</button>
                </form>

                <br>

                <h6 class="pt-3" style="font-size: 16px;">Remove Uploads</h6>
                <hr/>

                <form id="removeDocumentForm" name="removeDocumentForm" action="field_operations.php" method="post">
                    <input type="hidden" name="removeDocFormID" id="removeDocFormID"/>


                    <div class="row" id="document-history">
                    </div>

                    <button type="submit" class="btn btn-primary float-right pt-2 mt-2" value="1">Remove</button>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary closeDocumentModal">Close</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal Part For Surveillance Information Form -->

<div class="modal fade" id="surveillanceInfoModal" tabindex="-1" role="dialog"
     aria-labelledby="surveillanceInfoFormTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">

                <h6 class="modal-title" id="surveillanceInfoModalTitle">Operation: <strong
                            id="optSurvInfo">#234</strong><br/> Customer:
                    <strong id="custSurvInfo">Company name</strong><br/> Buyer: <strong
                            id="buyerSurvInfo"
                            style="text-transform:capitalize;"></strong><br/>
                    Seller:
                    <strong id="sellerSurvInfo" style="text-transform:capitalize;"></strong><br/> Supplier: <strong
                            id="supplierSurvInfo" style="text-transform:capitalize;"></strong></h6>
                <h5 class="modal-title" id="surveillanceInfoModalTitle">Surveillance Information Form <strong
                            id="formSurvInfo">#1</strong><br/> Inspector:
                    <strong id="insSurvInfo" style="font-size: 16px;">Inspector Name: </strong></h5>
            </div>
            <form id="survInfoForm" name="survInfoForm" action="field_operations.php" method="post">
                <input type="hidden" name="surveillanceFormOptID" id="surveillanceFormOptID"/>
                <input type="hidden" name="surveillanceFormID" id="surveillanceFormID"/>
                <div class="modal-body">

                    <h6 style="font-size: 16px;">Informations</h6>
                    <hr/>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="vesselSurvInfo">Vessel Name</label>
                            <input type="text" class="form-control form-control-sm" id="vesselSurvInfo" disabled
                                   readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="goodsSurvInfo">Type Of Goods</label>
                            <input type="text" class="form-control form-control-sm" id="goodsSurvInfo" disabled
                                   readonly>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="amountSurvInfo">Amount</label>
                            <input type="text" class="form-control form-control-sm" id="amountSurvInfo" disabled
                                   readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="ilocSurvInfo">Inspection Locations</label>
                            <input type="text" class="form-control form-control-sm" id="ilocSurvInfo" disabled readonly>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="survTypeSurvInfo"><b>Surveillance Types</b></label>
                            <div id="survTypeSurvInfo"></div>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="procTypeSurvInfo"><b>Process Types</b></label>
                            <div id="procTypeSurvInfo"></div>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="anlCondSurvInfo"><b>Analysis Conditions</b></label>
                            <div id="anlCondSurvInfo"></div>
                        </div>
                    </div>


                    <h6>Requested Surveillance</h6>
                    <hr/>


                    <div class="form-group row" id="reqSurvListForSurvInfo">
                    </div>


                    <h6>Used Equipment</h6>
                    <hr/>


                    <?php foreach ($equipments['data'] as $eqp) { ?>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="equip<?php echo $eqp->equipment_type_id; ?>" name="equipmentType"
                                   class="custom-control-input equipmentItem"
                                   value="<?php echo $eqp->equipment_type_id; ?>">
                            <label class="custom-control-label"
                                   for="equip<?php echo $eqp->equipment_type_id; ?>"><?php echo $eqp->equipment_type_name; ?></label>
                        </div>
                    <?php } ?>

                    <hr/>

                    <h6>Surveillance Notes</h6>
                    <hr/>

                    <div class="form-group row">

                        <!-- Gemi Geliş -->
                        <label for="vesselArrive" class="col-md-2 col-form-label">Vessel Arrival Date:</label>
                        <div class="col-md-4 vertAlgn">
                            <input type="datetime-local" class="form-control" name="vesselArrive" id="vesselArrive"
                                   value="">
                        </div>
                        <!-- Gemi Yanaşma -->
                        <label for="vesselLand" class="col-md-2 col-form-label">Vessel Land Date:</label>
                        <div class="col-md-4 vertAlgn">
                            <input type="datetime-local" class="form-control" name="vesselLand" id="vesselLand">
                        </div>
                        <!-- Temizlik Uygunluk -->
                        <label for="cleaningDate" class="col-md-2 col-form-label">Cleaning/Suitability Date:</label>
                        <div class="col-md-4 vertAlgn">
                            <input type="datetime-local" class="form-control" name="cleaningDate" id="cleaningDate">
                        </div>
                        <!-- Başlangıç Draft -->
                        <label for="draftBeginning" class="col-md-2 col-form-label">Beginning Draft Date:</label>
                        <div class="col-md-4 vertAlgn">
                            <input type="datetime-local" class="form-control" name="draftBeginning" id="draftBeginning">
                        </div>
                        <!-- Ara Draft -->
                        <label for="draftInter" class="col-md-2 col-form-label">Intermediary Draft Date:</label>
                        <div class="col-md-4 vertAlgn">
                            <input type="datetime-local" class="form-control" name="draftInter" id="draftInter">
                        </div>
                        <!-- Final Draft -->
                        <label for="draftFinal" class="col-md-2 col-form-label">Final Draft Date:</label>
                        <div class="col-md-4 vertAlgn">
                            <input type="datetime-local" class="form-control" name="draftFinal" id="draftFinal">
                        </div>
                        <!-- Yükleme/Tahliye Başlama -->
                        <label for="loadLandStart" class="col-md-2 col-form-label">Load/Landing Start Date:</label>
                        <div class="col-md-4 vertAlgn">
                            <input type="datetime-local" class="form-control" name="loadLandStart" id="loadLandStart">
                        </div>
                        <!-- Yükleme/Tahliye Bitiş -->
                        <label for="loadlLandEnd" class="col-md-2 col-form-label">Load/Landing End Date:</label>
                        <div class="col-md-4 vertAlgn">
                            <input type="datetime-local" class="form-control" name="loadlLandEnd" id="loadlLandEnd">
                        </div>
                        <!-- Fumigasyon Başlama -->
                        <label for="fumigationStart" class="col-md-2 col-form-label">Fumigation Start Date:</label>
                        <div class="col-md-4 vertAlgn">
                            <input type="datetime-local" class="form-control" name="fumigationStart"
                                   id="fumigationStart">
                        </div>
                        <!-- Fumigasyon Bitiş -->
                        <label for="fumigationEnd" class="col-md-2 col-form-label">Fumigation End Date:</label>
                        <div class="col-md-4 vertAlgn">
                            <input type="datetime-local" class="form-control" name="fumigationEnd" id="fumigationEnd">
                        </div>
                        <!-- Ambar Mühürleme -->
                        <label for="makeSeal" class="col-md-2 col-form-label">Warehouse Sealing Date:</label>
                        <div class="col-md-4 vertAlgn">
                            <input type="datetime-local" class="form-control" name="makeSeal" id="makeSeal">
                        </div>
                        <!-- Ambar Mühür Sökme -->
                        <label for="removeSeal" class="col-md-2 col-form-label">Warehouse Removal Date:</label>
                        <div class="col-md-4 vertAlgn">
                            <input type="datetime-local" class="form-control" name="removeSeal" id="removeSeal">
                        </div>
                    </div>

                    <h6>Results & Differences</h6>
                    <hr/>

                    <div class="form-group row">
                        <!-- Weighing -->
                        <label for="weighingResult" class="col-md-3 col-form-label">Weighing Result:</label>
                        <div class="col-md-3 vertAlgn">
                            <input type="number" min="0" step="0.001" class="form-control" name="weighingResult"
                                   id="weighingResult">
                        </div>
                        <label for="weighingDifference" class="col-md-3 col-form-label">Difference (+/-):</label>
                        <div class="col-md-3 vertAlgn">
                            <input type="number" min="0" step="0.001" class="form-control" name="weighingDifference"
                                   id="weighingDifference">
                        </div>
                        <!-- Ship Draft -->
                        <label for="shipDraftResult" class="col-md-3 col-form-label">Ship Draft/Tanker Ullage Survey
                            Result:</label>
                        <div class="col-md-3 vertAlgn">
                            <input type="number" min="0" step="0.001" class="form-control" name="shipDraftResult"
                                   id="shipDraftResult">
                        </div>
                        <label for="shipDraftDifference" class="col-md-3 col-form-label">Difference (+/-):</label>
                        <div class="col-md-3 vertAlgn">
                            <input type="number" min="0" step="0.001" class="form-control" name="shipDraftDifference"
                                   id="shipDraftDifference">
                        </div>
                        <!-- Vehicle/Piece -->
                        <label for="vehicleCountingResult" class="col-md-3 col-form-label">Vehicle/Piece Counting
                            Result:</label>
                        <div class="col-md-3 vertAlgn">
                            <input type="number" min="0" step="0.001" class="form-control" name="vehicleCountingResult"
                                   id="vehicleCountingResult">
                        </div>
                        <label for="vehicleCountingDifference" class="col-md-3 col-form-label">Difference
                            (+/-):</label>
                        <div class="col-md-3 vertAlgn">
                            <input type="number" min="0" step="0.001" class="form-control"
                                   name="vehicleCountingDifference"
                                   id="vehicleCountingDifference">
                        </div>
                        <!-- ShoreTank -->
                        <label for="shoreTankResult" class="col-md-3 col-form-label">Shore Tank Ullage Survey
                            Result:</label>
                        <div class="col-md-3 vertAlgn">
                            <input type="number" min="0" step="0.001" class="form-control" name="shoreTankResult"
                                   id="shoreTankResult">
                        </div>
                        <label for="shoreTankDifference" class="col-md-3 col-form-label">Difference (+/-):</label>
                        <div class="col-md-3 vertAlgn">
                            <input type="number" min="0" step="0.001" class="form-control" name="shoreTankDifference"
                                   id="shoreTankDifference">
                        </div>
                    </div>

                    <h6>Remarks</h6>
                    <hr/>

                    <label for="additionalNotes" style="min-width: 100%;">
                            <textarea class="form-control" id="additionalNotes" name="additionalNotes"
                                      style="min-width: 100%; min-height: 100px;"
                                      placeholder="You can write additional informations about the operation here.."></textarea>
                    </label>


                    <h6 style="font-size: 16px; margin-top: 20px;">Form Owner & Date</h6>
                    <hr/>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="">Inspector & Creation Date</span>
                        </div>
                        <input type="text" class="form-control" id="formOwnerName" readonly>
                        <input type="datetime-local" class="form-control" id="dateOfForm" readonly>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" data-toggle="modal"
                            data-target="#warnCompleteForm" data-backdrop="static"
                            data-keyboard="false" class="btn btn-danger mr-auto completeFormButton">Complete Form
                    </button>
                    <button type="button" class="btn btn-secondary closeSurveillanceModal">Close</button>
                    <button type="submit" class="btn btn-primary" value="1">Save changes</button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- Warning For Save & Finish Modal -->
<div id="warnCompleteForm" class="modal fade" role="dialog" data-backdrop="false">
    <div class="modal-dialog modal-dialog-centered">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                Warning
            </div>
            <div class="modal-body">
                <p>Are you sure you want to save and complete surveillance info form for this operation ? (You will not
                    change any data after that process!)</p>
                <button type="button" class="btn btn-primary float-right btn-sm" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-danger float-right btn-sm confirmWarnCompleteForm">Yes</button>
            </div>
        </div>
    </div>
</div>
<!-- End Warning Finish Anyway Modal -->

<!-- Warning For Photo Modal -->
<div id="cancelWarnPhoto" class="modal fade" role="dialog" data-backdrop="false">
    <div class="modal-dialog modal-dialog-centered">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                Warning
            </div>
            <div class="modal-body">
                <p>If you close without saving, your changes will be lost. Are you sure you want to close?</p>
                <button type="button" class="btn btn-primary float-right btn-sm" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-danger confirmWarnPhoto float-right btn-sm">Yes</button>
            </div>
        </div>
    </div>
</div>

<!-- Warning For Document Modal -->
<div id="cancelWarnDocument" class="modal fade" role="dialog" data-backdrop="false">
    <div class="modal-dialog modal-dialog-centered">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                Warning
            </div>
            <div class="modal-body">
                <p>If you close without saving, your changes will be lost. Are you sure you want to close?</p>
                <button type="button" class="btn btn-primary float-right btn-sm" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-danger confirmWarnDocument float-right btn-sm">Yes</button>
            </div>
        </div>
    </div>
</div>
<!-- End Surveillance Info Modal -->


<!-- Warning For Surveillance Info Modal -->
<div id="cancelWarnSurveillance" class="modal fade" role="dialog" data-backdrop="false">
    <div class="modal-dialog modal-dialog-centered">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                Warning
            </div>
            <div class="modal-body">
                <p>If you close without saving, your changes will be lost. Are you sure you want to close?</p>
                <button type="button" class="btn btn-primary float-right btn-sm" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-danger confirmWarnSurveillance float-right btn-sm">Yes</button>
            </div>
        </div>
    </div>
</div>
<!-- End Surveillance Info Modal -->

<!-- Close Warn For Add New Operation Note -->
<div id="cancelWarnOperationNote" class="modal fade" role="dialog" data-backdrop="false">
    <div class="modal-dialog modal-dialog-centered">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                Warning
            </div>
            <div class="modal-body">
                <p>If you leave before saving, your changes will be lost. Do you really want to close
                    ?</p>
                <button type="button" class="btn btn-primary float-right btn-sm" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-danger confirmWarnOperationNote float-right btn-sm">Yes</button>
            </div>
        </div>
    </div>
</div>

<!-- Close Warn For Add New Expense -->
<div id="cancelWarnOperationExpense" class="modal fade" role="dialog" data-backdrop="false">
    <div class="modal-dialog modal-dialog-centered">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                Warning
            </div>
            <div class="modal-body">
                <p>If you leave before saving, your changes will be lost. Do you really want to close
                    ?</p>
                <button type="button" class="btn btn-primary float-right btn-sm" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-danger confirmWarnOperationExpense float-right btn-sm">Yes</button>
            </div>
        </div>
    </div>
</div>


<script>
    var detectTab = readCookie('activeTab');
    var myTimer = readCookie('refreshTimer');
    var isRefresh;

    $(document).ready(function () {
        $('[data-toggle="popover"]').popover();

        $("#myPhotoFile").fileinput({
            maxFileCount: 10,
            showRemove: true,
            showUpload: false,
            initialPreviewShowDelete: true,
            allowedFileExtensions: ["jpg", "png"]
        });

        $("#myDocumentFile").fileinput({
            maxFileCount: 10,
            showRemove: true,
            showUpload: false,
            initialPreviewShowDelete: true,
            allowedFileExtensions: ["txt", "pdf", "xls", "xlsx", "doc", "docx", "xlm","xlsm", "docm"]
        });


        $('.refreshOff').on('click', function () {
            $('#timerMsg').text('Off');
            if (isRefresh != null) {
                clearTimeout(isRefresh);
            }
            createCookie('refreshTimer', 'off');
        });

        $('.refresh5').on('click', function () {
            $('#timerMsg').text('5 min');
            isRefresh = setTimeout(function () {
                location.reload();
            }, 300000);
            createCookie('refreshTimer', '5');
        });

        if (myTimer != null) {
            if (myTimer === "off") {
                if (isRefresh != null) {
                    clearTimeout(isRefresh);
                }
            } else if (myTimer === "5") {
                isRefresh = setTimeout(function () {
                    location.reload();
                }, 300000);
            }
        }


        //BEGIN REMEMBER TAB
        //Detect active tab and click on it
        var tabs = $('.nav-tabs');
        switch (detectTab) {
            case "w":
                tabs.find('#waitingTabLink').trigger('click');
                break;
            case "a":
                tabs.find('#activeTabLink').trigger('click');
                break;
            case "c":
                tabs.find('#completedTabLink').trigger('click');
                break;
            default:
                createCookie('activeTab', 'w', 1);
        }
        //Listen active tab change and write in to cookie
        $('.nav-tabs a').on('shown.bs.tab', function () {
            createCookie('activeTab', tabs.find('.active').prop('id').charAt(0), 1);
        });
        //END REMEMBER TAB

        //BEGIN TABLE SORTING
        $("#myInputWaiting").on("keyup", function () {
            var value = $(this).val().toLowerCase();
            $("#myTableWaiting tr").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });

        $("#myInputActive").on("keyup", function () {
            var value = $(this).val().toLowerCase();
            $("#myTableActive tr").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });

        $("#myInputCompleted").on("keyup", function () {
            var value = $(this).val().toLowerCase();
            $("#myTableCompleted tr").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
        //END TABLE SORTING


        //BEGIN PRINT TABLE
        $('.printTable').on('click', function () {
            var headOfHtml = '<!DOCTYPE html>\n' +
                '<html>\n' +
                '<head>\n' +
                '    <meta name="viewport" content="width=device-width, initial-scale=1">\n' +
                '    <meta charset="utf-8">\n' +
                '    <title></title>\n' +
                '\n' +
                '    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">\n' +
                '    <link rel="stylesheet" type="text/css" href="css/custom.css">\n' +
                '    <link rel="stylesheet" type="text/css" href="css/all.css">   \n' +
                ' <style type="text/css" media="print">@page { size: landscape; }</style> \n' +
                '</head>\n' +
                '<body><div class="table-responsive">';
            var footerOfHtml = '</div></body>\n' +
                '</html>';
            var divToPrint;
            switch (readCookie('activeTab')) {
                case "w":
                    divToPrint = $("#waitingTable").clone();
                    divToPrint.find("tr > td:last-child").remove();
                    divToPrint.find("tr > th:last-child").remove();
                    break;
                case "a":
                    divToPrint = $("#activeTable").clone();
                    divToPrint.find("tr > td:last-child").remove();
                    divToPrint.find("tr > th:last-child").remove();
                    break;
                case "c":
                    divToPrint = $("#completedTable").clone();
                    divToPrint.find("tr > td:last-child").remove();
                    divToPrint.find("tr > th:last-child").remove();
                    break;
            }
            newWin = window.open("");
            newWin.document.write(headOfHtml + divToPrint.prop('outerHTML') + footerOfHtml);
            $(newWin).ready(function () {
                newWin.print();
                newWin.close();
            });
        });
        //END PRINT TABLE

        //BEGIN ADD EXPENSE
        //Filling Modal ( Add New Operation Note )
        $('.addNewOperationExpenseButton').on('click', function () {
            var $row = $(this).closest("tr");
            $('#addNewOperationExpenseOptID').val($(this).attr('id'));
            $('#addNewOperationExpenseHeader').text($(this).attr('id'));
        });

        //Cancel Modal ( Add New Operation Note )
        $('.closeAddNewOperationExpenseModal').click(function () {
            $('#cancelWarnOperationExpense').modal('show');
        });

        //Confirm Warning ( Add New Operation Note )
        $('.confirmWarnOperationExpense').click(function () {
            var $noteMdl = $('#addNewOperationExpenseModal');
            var $submitBtn = $("#addNewOperationExpenseForm").find(':submit');
            var $cancelBtn = $('.closeAddNewOperationExpenseModal');
            $('#cancelWarnOperationExpense').modal('hide');
            $noteMdl.modal('hide');
            if ($submitBtn.hasClass("btn-success")) {
                $submitBtn.val("1");
                $submitBtn.removeClass("btn-success");
                $submitBtn.addClass("btn-primary");
                $submitBtn.text("Save");
            }
            if ($cancelBtn.hasClass("btn-danger")) {
                $cancelBtn.removeClass("btn-danger");
                $cancelBtn.addClass("btn-secondary");
                $cancelBtn.text("Close");
            }
            clearForm($noteMdl);
        });

        //Accept Process ( Add New Operation Note )
        $("#addNewOperationExpenseForm").submit(function () {
            var $submitBtn = $(this).find(':submit');
            var $cancelBtn = $('.closeAddNewOperationExpenseModal');
            if ($submitBtn.val() === "1") {
                $submitBtn.val("2");
                $submitBtn.removeClass("btn-primary");
                $submitBtn.addClass("btn-success");
                $submitBtn.text("Confirm");
                $cancelBtn.removeClass("btn-secondary");
                $cancelBtn.addClass("btn-danger");
                $cancelBtn.text("Cancel");
                return false;
            }
        });
        //END ADD EXPENSE

        //BEGIN ADD OPERATION NOTE
        //Filling Modal ( Add New Operation Note )
        $('.addNewOperationNoteButton').on('click', function () {
            var $row = $(this).closest("tr");
            $('#addNewOperationNoteOptID').val($(this).attr('id'));
            $('#addNewOperationNoteHeader').text($(this).attr('id'));
            $('#addNewOperationNoteCustomerHeader').text($row.find('.activeCustomer').text());

        });

        //Cancel Modal ( Add New Operation Note )
        $('.closeAddNewOperationNoteModal').click(function () {
            $('#cancelWarnOperationNote').modal('show');
        });

        //Confirm Warning ( Add New Operation Note )
        $('.confirmWarnOperationNote').click(function () {
            var $noteMdl = $('#addNewOperationNoteModal');
            var $submitBtn = $("#addNewOperationNoteForm").find(':submit');
            var $cancelBtn = $('.closeAddNewOperationNoteModal');
            $('#cancelWarnOperationNote').modal('hide');
            $noteMdl.modal('hide');
            if ($submitBtn.hasClass("btn-success")) {
                $submitBtn.val("1");
                $submitBtn.removeClass("btn-success");
                $submitBtn.addClass("btn-primary");
                $submitBtn.text("Save");
            }
            if ($cancelBtn.hasClass("btn-danger")) {
                $cancelBtn.removeClass("btn-danger");
                $cancelBtn.addClass("btn-secondary");
                $cancelBtn.text("Close");
            }
            clearForm($noteMdl);
        });

        //Accept Process ( Add New Operation Note )
        $("#addNewOperationNoteForm").submit(function () {
            var $submitBtn = $(this).find(':submit');
            var $cancelBtn = $('.closeAddNewOperationNoteModal');
            if ($submitBtn.val() === "1") {
                $submitBtn.val("2");
                $submitBtn.removeClass("btn-primary");
                $submitBtn.addClass("btn-success");
                $submitBtn.text("Confirm");
                $cancelBtn.removeClass("btn-secondary");
                $cancelBtn.addClass("btn-danger");
                $cancelBtn.text("Cancel");
                return false;
            }
        });
        //END ADD OPERATION NOTE

        //BEGIN SHOW EXPENSES
        $('.showOperationExpenses').on('click', function () {
            var $row = $(this).closest("tr");
            $('#operationExpenseOptHeader').text($(this).prop('id'));
            var customerText = $row.find('.activeCustomer').text();
            $('#operationExpenseCustomerHeader').text(customerText.substring(0, customerText.indexOf('(')));

            var postData = {selectedOptInsShowExpenses: $(this).prop('id')};
            $.ajax({
                type: 'POST',
                url: 'field_operations.php',
                data: postData,
                dataType: 'text',
                success: function (resultData) {

                    var result = JSON.parse(resultData);

                    if (result.hasOwnProperty('error')) {
                        //Make error message
                        alert("OOPS");
                    }
                    if (result.hasOwnProperty('operation_id')) {

                        if (result.operation_expenses.length > 0) {

                            var expenseString = "";

                            result.operation_expenses.forEach(function (expense, index) {

                                expenseString += "<b> (" + expense.created_at + ") " + expense.get_which_user.name + " " + expense.get_which_user.surname + "</b><br>" + urldecode(expense.expense_content) + " <b>Amount:</b> " + expense.expense_amount + "<hr>";

                            });

                            $('#operationExpenseHere').html(expenseString);
                        } else {
                            var thereIsNoNoteMessage = $('<div>').prop('class', 'text-center font-italic').text('There is no expenses.');
                            $('#operationExpenseHere').html(thereIsNoNoteMessage);
                        }

                    }

                }
            });

        });
        //END SHOW EXPENSES

        //BEGIN SHOW NOTES
        $('.showOperationNotes').on('click', function () {
            var $row = $(this).closest("tr");
            $('#operationNotesOptHeader').text($(this).prop('id'));
            var customerText = $row.find('.activeCustomer').text();
            $('#operationNotesCustomerHeader').text(customerText.substring(0, customerText.indexOf('(')));

            var postData = {selectedOptInsShowNotes: $(this).prop('id')};
            $.ajax({
                type: 'POST',
                url: 'field_operations.php',
                data: postData,
                dataType: 'text',
                success: function (resultData) {

                    var result = JSON.parse(resultData);

                    if (result.hasOwnProperty('error')) {
                        //Make error message
                        alert("OOPS");
                    }
                    if (result.hasOwnProperty('operation_id')) {

                        if (result.operation_notes_for_field.length > 0) {

                            var notesString = "";

                            result.operation_notes_for_field.forEach(function (note, index) {
                                var userTitle = "";
                                switch (note.get_which_user.user_type_id) {
                                    case 2:
                                        userTitle = "Officer";
                                        break;
                                    case 3:
                                        userTitle = "Inspector";
                                        break;
                                    case 4:
                                        userTitle = "Laborant";
                                        break;
                                }

                                notesString += "<b> (" + note.created_at + ") " + userTitle + ": " + note.get_which_user.name + " " + note.get_which_user.surname + "</b><br>" + urldecode(note.note_content) + "<hr>";

                            });

                            $('#operationNotesHere').html(notesString);
                        } else {
                            var thereIsNoNoteMessage = $('<div>').prop('class', 'text-center font-italic').text('There is no operation notes.');
                            $('#operationNotesHere').html(thereIsNoNoteMessage);
                        }

                    }

                }
            });

        });
        //END SHOW NOTES

        //BEGIN ACCEPT OPERATION
        //Filling Modal ( Accept Operation )
        $('.acceptOperationButtonWaiting').on('click', function () {
            $('#acceptOperationID').val($(this).prop('id'));
            $('#acceptOperationWarning').text('Are you sure you want to accept the operation #' + $(this).prop('id') + ' ?');
        });
        //Confirm Warning ( Accept Operation )
        $('.closeAcceptOperationModal').click(function () {
            var $newMdl = $('#warnAcceptW');
            $newMdl.modal('hide');
            var $submitBtn = $("#acceptOperationForm").find(':submit');
            var $cancelBtn = $('.closeAcceptOperationModal');
            if ($submitBtn.hasClass("btn-success")) {
                $submitBtn.val("1");
                $submitBtn.removeClass("btn-success");
                $submitBtn.addClass("btn-primary");
                $submitBtn.text("Accept Operation");
            }
            if ($cancelBtn.hasClass("btn-danger")) {
                $cancelBtn.removeClass("btn-danger");
                $cancelBtn.addClass("btn-secondary");
                $cancelBtn.text("Close");
            }

            $('#acceptOperationID').val('').text('');
            $('#acceptOperationWarning').val('').text('');
        });
        //Accept Process ( Accept Operation )
        $('#acceptOperationForm').submit(function () {
            var $submitBtn = $(this).find(':submit');
            var $cancelBtn = $('.closeAcceptOperationModal');
            if ($submitBtn.val() === "1") {
                $submitBtn.val("2");
                $submitBtn.removeClass("btn-primary");
                $submitBtn.addClass("btn-success");
                $submitBtn.text("Confirm");
                $cancelBtn.removeClass("btn-secondary");
                $cancelBtn.addClass("btn-danger");
                $cancelBtn.text("Cancel");
                return false;
            }
        });
        //END ACCEPT OPERATION

        //BEGIN JOIN OPERATION
        //Filling Modal ( Join Operation )
        $('.joinOperationButtonWaiting').on('click', function () {
            $('#joinOperationID').val($(this).prop('id'));
            $('#joinOperationWarning').text('Are you sure you want to join the operation #' + $(this).prop('id') + ' ?');
        });
        //Confirm Warning ( Join Operation )
        $('.closeJoinOperationModal').click(function () {
            var $newMdl = $('#warnJoinW');
            $newMdl.modal('hide');
            var $submitBtn = $("#joinOperationForm").find(':submit');
            var $cancelBtn = $('.closeJoinOperationModal');
            if ($submitBtn.hasClass("btn-success")) {
                $submitBtn.val("1");
                $submitBtn.removeClass("btn-success");
                $submitBtn.addClass("btn-primary");
                $submitBtn.text("Join Operation");
            }
            if ($cancelBtn.hasClass("btn-danger")) {
                $cancelBtn.removeClass("btn-danger");
                $cancelBtn.addClass("btn-secondary");
                $cancelBtn.text("Close");
            }

            $('#joinOperationID').val('').text('');
            $('#joinOperationWarning').val('').text('');
        });
        //Accept Process ( Join Operation )
        $('#joinOperationForm').submit(function () {
            var $submitBtn = $(this).find(':submit');
            var $cancelBtn = $('.closeJoinOperationModal');
            if ($submitBtn.val() === "1") {
                $submitBtn.val("2");
                $submitBtn.removeClass("btn-primary");
                $submitBtn.addClass("btn-success");
                $submitBtn.text("Confirm");
                $cancelBtn.removeClass("btn-secondary");
                $cancelBtn.addClass("btn-danger");
                $cancelBtn.text("Cancel");
                return false;
            }
        });
        //END JOIN OPERATION

        //BEGIN VIEW DETAILED WAITING
        $('.viewDetailedButtonWaiting').on('click', function () {
            var $row = $(this).closest("tr");
            $('#optViewDW').text("#" + $(this).prop('id'));
            $('#vesselViewDW').val($row.find('.waitingVessel').text());
            $('#goodsViewDW').val($row.find('.waitingGoods').text());
            $('#amountViewDW').val($row.find('.waitingAmount').text());
            $('#officerViewDW').val($row.find('.waitingOfficer').text());
            $('#dateViewDW').val($row.find('.waitingDate').text());

            var optCust = $('#custViewDW');
            optCust.text('');
            var optNomCust = $('#nomCustViewDW');
            optNomCust.text('');
            var optBuyer = $('#buyerViewDW');
            optBuyer.text('');
            var optSeller = $('#sellerViewDW');
            optSeller.text('');
            var optSupplier = $('#supplierViewDW');
            optSupplier.text('');

            var requestedSurvList = $('#reqSurvViewDW');
            requestedSurvList.empty();
            var requestedAnlList = $('#reqAnlViewDW');
            requestedAnlList.empty();

            var survTypeList = $('#survTypeViewDW');
            survTypeList.text('');
            var procTypeList = $('#procTypeViewDW');
            procTypeList.text('');
            var anlTypeList = $('#anlCondViewDW');
            anlTypeList.text('');

            var postData = {selectedOperationDetW: $(this).prop('id')};
            $.ajax({
                type: 'POST',
                url: 'field_operations.php',
                data: postData,
                dataType: 'text',
                success: function (resultData) {

                    var result = JSON.parse(resultData);

                    if (result.hasOwnProperty('error')) {
                        //Make error message
                        alert("OOPS");
                    }
                    if (result.hasOwnProperty('operation_id')) {

                        if (result.customer != null) {
                            optCust.text(urldecode(result.customer.company_name));
                        } else {
                            optCust.text('-');
                        }

                        if (result.nomination_customer != null) {
                            optNomCust.text(urldecode(result.nomination_customer.company_name));
                        } else {
                            optNomCust.text('-');
                        }

                        if (result.buyer != null) {
                            optBuyer.text(urldecode(result.buyer));
                        } else {
                            optBuyer.text('-');
                        }

                        if (result.seller != null) {
                            optSeller.text(urldecode(result.seller));
                        } else {
                            optSeller.text('-');
                        }

                        if (result.supplier != null) {
                            optSupplier.text(urldecode(result.supplier));
                        } else {
                            optSupplier.text('-');
                        }

                        if (result.surveillance_types.length > 0) {

                            var survTString = "";

                            result.surveillance_types.forEach(function (surv, index) {

                                if (index === result.surveillance_types.length - 1 || result.surveillance_types.length === 1) {
                                    survTString += surv.surveillance_type_name;
                                } else {
                                    survTString += (surv.surveillance_type_name + ", ");
                                }

                                survTypeList.text(survTString);

                            });

                        }

                        if (result.process_types.length > 0) {

                            var procTString = "";

                            result.process_types.forEach(function (proc, index) {

                                if (index === result.process_types.length - 1 || result.process_types.length === 1) {
                                    procTString += proc.process_type_name;
                                } else {
                                    procTString += (proc.process_type_name + ", ");
                                }

                                procTypeList.text(procTString);

                            });

                        }

                        if (result.analysis_conditions.length > 0) {

                            var anlcTString = "";

                            result.analysis_conditions.forEach(function (anlc, index) {

                                if (index === result.analysis_conditions.length - 1 || result.analysis_conditions.length === 1) {
                                    anlcTString += anlc.analysis_condition_name;
                                } else {
                                    anlcTString += (anlc.analysis_condition_name + ", ");
                                }

                                anlTypeList.text(anlcTString);

                            });

                        }

                        if (result.offices.length > 0) {

                            var officesString = "";

                            result.offices.forEach(function (office, index) {

                                if (index === result.offices.length - 1 || result.offices.length === 1) {
                                    officesString += office.office_name;
                                } else {
                                    officesString += (office.office_name + ", ");
                                }

                            });

                            $('#ilocViewDW').val(officesString);
                        }

                        if (result.surveillances.length > 0) {

                            result.surveillances.forEach(function (surv, index) {

                                var survDiv = $('<div>').prop('class', 'col-lg-4');
                                var survCustomDiv = $('<div>').prop('class', 'custom-control custom-checkbox');
                                var survInput = $('<input>').prop('type', 'checkbox').prop('class', 'custom-control-input').prop('id', 'reqSurvDetW' + surv.surveillance_id).prop('checked', true).prop('disabled', true);
                                var survLabel = $('<label>').prop('class', 'custom-control-label').prop('for', 'reqSurvDetW' + surv.surveillance_id).text(surv.surveillance_name);

                                requestedSurvList.append(survDiv.append(survCustomDiv.append(survInput).append(survLabel)));

                            });

                        } else {
                            var thereIsNoSurvMessage = $('<div>').prop('class', 'text-center font-italic').text('There is no requested surveillances.');
                            requestedSurvList.append(thereIsNoSurvMessage);
                        }

                        if (result.analyzes.length > 0) {

                            result.analyzes.forEach(function (anl, index) {

                                var anlDiv = $('<div>').prop('class', 'col-lg-4');
                                var anlCustomDiv = $('<div>').prop('class', 'custom-control custom-checkbox');
                                var anlInput = $('<input>').prop('type', 'checkbox').prop('class', 'custom-control-input').prop('id', 'reqAnlDetW' + anl.analysis_id).prop('checked', true).prop('disabled', true);
                                var anlLabel = $('<label>').prop('class', 'custom-control-label').prop('for', 'reqSurvDetW' + anl.analysis_id).text(anl.analysis_name);

                                requestedAnlList.append(anlDiv.append(anlCustomDiv.append(anlInput).append(anlLabel)));

                            });

                        } else {
                            var thereIsNoAnlMessage = $('<div>').prop('class', 'text-center font-italic').text('There is no requested analyzes.');
                            requestedAnlList.append(thereIsNoAnlMessage);
                        }

                    }

                }
            });

        });
        //END VIEW DETAILED WAITING

        //BEGIN ORGANIZE PHOTOS
        var photoHistoryDiv = $('#photo-history');
        //Cancel Modal ( Upload Photo )
        $('.closePhotoModal').click(function () {
            $('#cancelWarnPhoto').modal('show');
        });
        //Confirm Warning Modal ( Upload Photo )
        $('.confirmWarnPhoto').click(function () {
            var $newMdl = $('#surveillancePhotoModal');
            $('#cancelWarnPhoto').modal('hide');
            $newMdl.modal('hide');
            var $submitBtnPhotoRemove = $("#removePhotoForm").find(':submit');
            var $submitBtnPhotoUpload = $("#uploadPhotoForm").find(':submit');
            var $cancelBtn = $('.closePhotoModal');
            if ($submitBtnPhotoRemove.hasClass("btn-success")) {
                $submitBtnPhotoRemove.val("1");
                $submitBtnPhotoRemove.removeClass("btn-success");
                $submitBtnPhotoRemove.addClass("btn-primary");
                $submitBtnPhotoRemove.text("Remove");
            }
            if ($submitBtnPhotoUpload.hasClass("btn-success")) {
                $submitBtnPhotoUpload.val("1");
                $submitBtnPhotoUpload.removeClass("btn-success");
                $submitBtnPhotoUpload.addClass("btn-primary");
                $submitBtnPhotoUpload.text("Upload");
            }
            if ($cancelBtn.hasClass("btn-danger")) {
                $cancelBtn.removeClass("btn-danger");
                $cancelBtn.addClass("btn-secondary");
                $cancelBtn.text("Close");
            }
            $('#myPhotoFile').fileinput('clear');
            photoHistoryDiv.empty();
        });
        //Accept Process ( Upload Photo )
        $('#uploadPhotoForm').submit(function () {
            var $submitBtn = $(this).find(':submit');
            var $cancelBtn = $('.closePhotoModal');
            if ($submitBtn.val() === "1") {
                $submitBtn.val("2");
                $submitBtn.removeClass("btn-primary");
                $submitBtn.addClass("btn-success");
                $submitBtn.text("Confirm");
                $cancelBtn.removeClass("btn-secondary");
                $cancelBtn.addClass("btn-danger");
                $cancelBtn.text("Cancel");
                return false;
            }
        });
        //Accept Process ( Remove Uploaded Photo )
        $('#removePhotoForm').submit(function () {
            var $submitBtn = $(this).find(':submit');
            var $cancelBtn = $('.closePhotoModal');
            if ($submitBtn.val() === "1") {
                $submitBtn.val("2");
                $submitBtn.removeClass("btn-primary");
                $submitBtn.addClass("btn-success");
                $submitBtn.text("Confirm");
                $cancelBtn.removeClass("btn-secondary");
                $cancelBtn.addClass("btn-danger");
                $cancelBtn.text("Cancel");
                return false;
            }
        });
        //Filling Modal ( Upload Photo )
        $('.uploadPhotoButton').on('click', function () {
            var $row = $(this).closest("tr");
            $('#optSurvPhoto').text("#" + $(this).prop('id'));
            var customerText = $row.find('.activeCustomer').text();
            $('#custSurvPhoto').text(customerText.substring(0, customerText.indexOf('(')));

            $('#surveillanceFormOptID').val($(this).prop('id'));

            var survPhotoNum = $('#formSurvPhoto');
            survPhotoNum.text('');
            var insName = $('#insSurvPhoto');
            insName.text('');
            var photoSurvInfo = $('#photoFormID');
            photoSurvInfo.text('');
            var removePhotoSurvInfo = $('#removeFormID');
            removePhotoSurvInfo.text('');



            photoHistoryDiv.empty();

            var postData = {selectedOperationSurvPhoto: $(this).prop('id')};
            $.ajax({
                type: 'POST',
                url: 'field_operations.php',
                data: postData,
                dataType: 'text',
                success: function (resultData) {

                    var result = JSON.parse(resultData);

                    if (result.hasOwnProperty('error')) {
                        //Make error message
                        alert("OOPS");
                    }
                    if (result.hasOwnProperty('info_form_id')) {

                        survPhotoNum.text("#" + result.info_form_id);
                        photoSurvInfo.val(result.info_form_id);
                        removePhotoSurvInfo.val(result.info_form_id);
                        insName.text(result.user.name + " " + result.user.surname);

                        if (result.uploaded_photos.length > 0) {

                            result.uploaded_photos.forEach(function (photo, index) {

                                if (photo.is_deleted == 0) {

                                    var photoDiv = $('<div>').prop('class', 'col-md-4 pb-3');
                                    var photoAct = $('<img>').prop('class', 'img-fluid').prop('src', photo.photo_path);
                                    var checkDiv = $('<div>').prop('class', 'form-check');
                                    var checkInput = $('<input>').prop('class', 'form-check-input').prop('type', 'checkbox').prop('id', 'photo'+photo.photo_id).prop('name', 'surveyPhotos[]').val(photo.photo_id);
                                    var checkLabel = $('<label>').prop('class', 'form-check-label').prop('for', 'photo'+photo.photo_id).text(photo.photo_name);

                                    photoHistoryDiv.append(photoDiv.append(photoAct).append(checkDiv.append(checkInput).append(checkLabel)));

                                }


                            });

                        }

                    }

                }
            });

        });
        //END ORGANIZE PHOTOS

        //BEGIN ORGANIZE DOCUMENTS
        var docHistoryDiv = $('#document-history');
        //Cancel Modal ( Upload Document )
        $('.closeDocumentModal').click(function () {
            $('#cancelWarnDocument').modal('show');
        });
        //Confirm Warning Modal ( Upload Document )
        $('.confirmWarnDocument').click(function () {
            var $newMdl = $('#surveillanceDocumentModal');
            $('#cancelWarnDocument').modal('hide');
            $newMdl.modal('hide');
            var $submitBtnDocumentRemove = $("#removeDocumentForm").find(':submit');
            var $submitBtnDocumentUpload = $("#uploadDocumentForm").find(':submit');
            var $cancelBtn = $('.closeDocumentModal');
            if ($submitBtnDocumentRemove.hasClass("btn-success")) {
                $submitBtnDocumentRemove.val("1");
                $submitBtnDocumentRemove.removeClass("btn-success");
                $submitBtnDocumentRemove.addClass("btn-primary");
                $submitBtnDocumentRemove.text("Remove");
            }
            if ($submitBtnDocumentUpload.hasClass("btn-success")) {
                $submitBtnDocumentUpload.val("1");
                $submitBtnDocumentUpload.removeClass("btn-success");
                $submitBtnDocumentUpload.addClass("btn-primary");
                $submitBtnDocumentUpload.text("Upload");
            }
            if ($cancelBtn.hasClass("btn-danger")) {
                $cancelBtn.removeClass("btn-danger");
                $cancelBtn.addClass("btn-secondary");
                $cancelBtn.text("Close");
            }
            $('#myDocumentFile').fileinput('clear');
            docHistoryDiv.empty();
        });
        //Accept Process ( Upload Document )
        $('#uploadDocumentForm').submit(function () {
            var $submitBtn = $(this).find(':submit');
            var $cancelBtn = $('.closeDocumentModal');
            if ($submitBtn.val() === "1") {
                $submitBtn.val("2");
                $submitBtn.removeClass("btn-primary");
                $submitBtn.addClass("btn-success");
                $submitBtn.text("Confirm");
                $cancelBtn.removeClass("btn-secondary");
                $cancelBtn.addClass("btn-danger");
                $cancelBtn.text("Cancel");
                return false;
            }
        });
        //Accept Process ( Remove Uploaded Document )
        $('#removeDocumentForm').submit(function () {
            var $submitBtn = $(this).find(':submit');
            var $cancelBtn = $('.closeDocumentModal');
            if ($submitBtn.val() === "1") {
                $submitBtn.val("2");
                $submitBtn.removeClass("btn-primary");
                $submitBtn.addClass("btn-success");
                $submitBtn.text("Confirm");
                $cancelBtn.removeClass("btn-secondary");
                $cancelBtn.addClass("btn-danger");
                $cancelBtn.text("Cancel");
                return false;
            }
        });
        //Filling Modal ( Upload Document )
        $('.uploadDocumentButton').on('click', function () {
            var $row = $(this).closest("tr");
            $('#optSurvDocument').text("#" + $(this).prop('id'));
            var customerText = $row.find('.activeCustomer').text();
            $('#custSurvDocument').text(customerText.substring(0, customerText.indexOf('(')));

            $('#surveillanceFormOptID').val($(this).prop('id'));

            var survDocumentNum = $('#formSurvDocument');
            survDocumentNum.text('');
            var docInsName = $('#insSurvDocument');
            docInsName.text('');
            var removeDocSurvInfo = $('#removeDocFormID');
            removeDocSurvInfo.text('');
            var documentSurvInfo = $('#documentFormID');
            documentSurvInfo.text('');


            docHistoryDiv.empty();

            var postData = {selectedOperationSurvDocument: $(this).prop('id')};
            $.ajax({
                type: 'POST',
                url: 'field_operations.php',
                data: postData,
                dataType: 'text',
                success: function (resultData) {

                    var result = JSON.parse(resultData);

                    if (result.hasOwnProperty('error')) {
                        //Make error message
                        alert("OOPS");
                    }
                    if (result.hasOwnProperty('info_form_id')) {

                        survDocumentNum.text("#" + result.info_form_id);
                        removeDocSurvInfo.val(result.info_form_id);
                        documentSurvInfo.val(result.info_form_id);
                        docInsName.text(result.user.name + " " + result.user.surname);

                        if (result.uploaded_documents.length > 0) {

                            result.uploaded_documents.forEach(function (document, index) {

                                if (document.is_deleted == 0) {

                                    var docDiv = $('<div>').prop('class', 'col-md-4 pb-3');
                                    //var docAct = $('<img>').prop('class', 'img-fluid').prop('src', document.doc_path);
                                    var checkDiv = $('<div>').prop('class', 'form-check');
                                    var checkInput = $('<input>').prop('class', 'form-check-input').prop('type', 'checkbox').prop('id', 'doc'+document.doc_id).prop('name', 'surveyDocuments[]').val(document.doc_id);
                                    var checkLabel = $('<label>').prop('class', 'form-check-label').prop('for', 'doc'+document.doc_id).text(document.doc_name);
                                    var downloadLink = $('<a>').prop('class','pl-1').prop('href',document.doc_path).prop('target','_blank').prop('download',document.doc_name);
                                    var downloadIcon = $('<i>').prop('class','fas fa-download');

                                    docHistoryDiv.append(docDiv.append(checkDiv.append(checkInput).append(checkLabel).append(downloadLink.append(downloadIcon))));

                                }


                            });

                        }

                    }

                }
            });

        });
        //END ORGANIZE DOCUMENTS

        //BEGIN SURVEILLANCE INFO FORM
        //Cancel Modal ( Surveillance Info )
        $('.closeSurveillanceModal').click(function () {
            $('#cancelWarnSurveillance').modal('show');
        });
        //Confirm Warning Modal ( Surveillance Info )
        $('.confirmWarnSurveillance').click(function () {
            var $newMdl = $('#surveillanceInfoModal');
            $('#cancelWarnSurveillance').modal('hide');
            $newMdl.modal('hide');
            var $submitBtn = $("#survInfoForm").find(':submit');
            var $cancelBtn = $('.closeSurveillanceModal');
            if ($submitBtn.hasClass("btn-success")) {
                $submitBtn.val("1");
                $submitBtn.removeClass("btn-success");
                $submitBtn.addClass("btn-primary");
                $submitBtn.text("Save Changes");
            }
            if ($cancelBtn.hasClass("btn-danger")) {
                $cancelBtn.removeClass("btn-danger");
                $cancelBtn.addClass("btn-secondary");
                $cancelBtn.text("Close");
            }
        });
        //Accept Process ( Surveillance Info )
        $('#survInfoForm').submit(function () {
            var $submitBtn = $(this).find(':submit');
            var $cancelBtn = $('.closeSurveillanceModal');
            if ($submitBtn.val() === "1") {
                $submitBtn.val("2");
                $submitBtn.removeClass("btn-primary");
                $submitBtn.addClass("btn-success");
                $submitBtn.text("Confirm");
                $cancelBtn.removeClass("btn-secondary");
                $cancelBtn.addClass("btn-danger");
                $cancelBtn.text("Cancel");
                return false;
            }
        });
        //Filling Modal ( Surveillance Info )
        $('.survInfoButton').on('click', function () {
            var $row = $(this).closest("tr");
            $('#optSurvInfo').text("#" + $(this).prop('id'));
            var customerText = $row.find('.activeCustomer').text();
            $('#custSurvInfo').text(customerText.substring(0, customerText.indexOf('(')));
            $('#vesselSurvInfo').val($row.find('.activeVessel').text());
            $('#goodsSurvInfo').val($row.find('.activeGoods').text());
            $('#amountSurvInfo').val($row.find('.activeAmount').text());
            $('#formOwnerName').val('');
            $('#dateOfForm').val('');

            var completeFormButton = $('.completeFormButton');

            $('#surveillanceFormOptID').val($(this).prop('id'));

            var remarks = $('#additionalNotes');
            remarks.val('');

            var vesselArive = $('#vesselArrive');
            vesselArive.val('');
            var vesselLand = $('#vesselLand');
            vesselLand.val('');
            var cleaningDate = $('#cleaningDate');
            cleaningDate.val('');
            var draftBeginning = $('#draftBeginning');
            draftBeginning.val('');
            var draftInter = $('#draftInter');
            draftInter.val('');
            var draftFinal = $('#draftFinal');
            draftFinal.val('');
            var loadLandStart = $('#loadLandStart');
            loadLandStart.val('');
            var loadLandEnd = $('#loadlLandEnd');
            loadLandEnd.val('');
            var fumigationStart = $('#fumigationStart');
            fumigationStart.val('');
            var fumigationEnd = $('#fumigationEnd');
            fumigationEnd.val('');
            var makeSeal = $('#makeSeal');
            makeSeal.val('');
            var removeSeal = $('#removeSeal');
            removeSeal.val('');

            var weighingResult = $('#weighingResult');
            weighingResult.val('');
            var weighingDifference = $('#weighingDifference');
            weighingDifference.val('');
            var shipDraftResult = $('#shipDraftResult');
            shipDraftResult.val('');
            var shipDraftDifference = $('#shipDraftDifference');
            shipDraftDifference.val('');
            var vehicleCountingResult = $('#vehicleCountingResult');
            vehicleCountingResult.val('');
            var vehicleCountingDifference = $('#vehicleCountingDifference');
            vehicleCountingDifference.val('');
            var shoreTankResult = $('#shoreTankResult');
            shoreTankResult.val('');
            var shoreTankDifference = $('#shoreTankDifference');
            shoreTankDifference.val('');


            $('.equipmentItem').prop('checked', false);

            var survInfoNum = $('#formSurvInfo');
            survInfoNum.text('');
            var insName = $('#insSurvInfo');
            insName.text('');
            var optBuyer = $('#buyerSurvInfo');
            optBuyer.text('');
            var optSeller = $('#sellerSurvInfo');
            optSeller.text('');
            var optSupplier = $('#supplierSurvInfo');
            optSupplier.text('');

            var survTypeList = $('#survTypeSurvInfo');
            survTypeList.text('');
            var procTypeList = $('#procTypeSurvInfo');
            procTypeList.text('');
            var anlTypeList = $('#anlCondSurvInfo');
            anlTypeList.text('');

            var requestedSurvList = $('#reqSurvListForSurvInfo');
            requestedSurvList.empty();

            var postData = {selectedOperationSurvInfo: $(this).prop('id')};
            $.ajax({
                type: 'POST',
                url: 'field_operations.php',
                data: postData,
                dataType: 'text',
                success: function (resultData) {

                    var result = JSON.parse(resultData);

                    if (result.hasOwnProperty('error')) {
                        //Make error message
                        alert("OOPS");
                    }
                    if (result.hasOwnProperty('info_form_id')) {

                        completeFormButton.attr('id', result.info_form_id);
                        $('#surveillanceFormID').val(result.info_form_id);

                        survInfoNum.text("#" + result.info_form_id);
                        insName.text(result.user.name + " " + result.user.surname);

                        $('#formOwnerName').val(result.user.name + " " + result.user.surname);
                        $('#dateOfForm').val(result.created_at.split(' ').join('T'));

                        if (result.info_equipment_id != null) {
                            $('#equip' + result.info_equipment_id).prop('checked', true);
                        }

                        if (result.operation.buyer != null) {
                            optBuyer.text(urldecode(result.operation.buyer));
                        } else {
                            optBuyer.text('-');
                        }

                        if (result.operation.seller != null) {
                            optSeller.text(urldecode(result.operation.seller));
                        } else {
                            optSeller.text('-');
                        }

                        if (result.operation.supplier != null) {
                            optSupplier.text(urldecode(result.operation.supplier));
                        } else {
                            optSupplier.text('-');
                        }

                        if (result.vessel_arrival_date != null) {
                            let dateArray = result.vessel_arrival_date.split(" ");
                            let time = dateArray[1].split(":");
                            vesselArive.val(dateArray[0] + "T" + time[0] + ":" + time[1]);
                        }

                        if (result.vessel_land_date != null) {
                            let dateArray = result.vessel_land_date.split(" ");
                            let time = dateArray[1].split(":");
                            vesselLand.val(dateArray[0] + "T" + time[0] + ":" + time[1]);
                        }

                        if (result.cleaning_suitability_date != null) {
                            let dateArray = result.cleaning_suitability_date.split(" ");
                            let time = dateArray[1].split(":");
                            cleaningDate.val(dateArray[0] + "T" + time[0] + ":" + time[1]);
                        }

                        if (result.beginning_draft_date != null) {
                            let dateArray = result.beginning_draft_date.split(" ");
                            let time = dateArray[1].split(":");
                            draftBeginning.val(dateArray[0] + "T" + time[0] + ":" + time[1]);
                        }

                        if (result.middle_draft_date != null) {
                            let dateArray = result.middle_draft_date.split(" ");
                            let time = dateArray[1].split(":");
                            draftInter.val(dateArray[0] + "T" + time[0] + ":" + time[1]);
                        }

                        if (result.final_draft_date != null) {
                            let dateArray = result.final_draft_date.split(" ");
                            let time = dateArray[1].split(":");
                            draftFinal.val(dateArray[0] + "T" + time[0] + ":" + time[1]);
                        }

                        if (result.load_landing_start_date != null) {
                            let dateArray = result.load_landing_start_date.split(" ");
                            let time = dateArray[1].split(":");
                            loadLandStart.val(dateArray[0] + "T" + time[0] + ":" + time[1]);
                        }

                        if (result.load_landing_finish_date != null) {
                            let dateArray = result.load_landing_finish_date.split(" ");
                            let time = dateArray[1].split(":");
                            loadLandEnd.val(dateArray[0] + "T" + time[0] + ":" + time[1]);
                        }

                        if (result.fumigation_start_date != null) {
                            let dateArray = result.fumigation_start_date.split(" ");
                            let time = dateArray[1].split(":");
                            fumigationStart.val(dateArray[0] + "T" + time[0] + ":" + time[1]);
                        }

                        if (result.fumigation_finish_date != null) {
                            let dateArray = result.fumigation_finish_date.split(" ");
                            let time = dateArray[1].split(":");
                            fumigationEnd.val(dateArray[0] + "T" + time[0] + ":" + time[1]);
                        }

                        if (result.warehouse_sealing_date != null) {
                            let dateArray = result.warehouse_sealing_date.split(" ");
                            let time = dateArray[1].split(":");
                            makeSeal.val(dateArray[0] + "T" + time[0] + ":" + time[1]);
                        }

                        if (result.warehouse_removal_date != null) {
                            let dateArray = result.warehouse_removal_date.split(" ");
                            let time = dateArray[1].split(":");
                            removeSeal.val(dateArray[0] + "T" + time[0] + ":" + time[1]);
                        }

                        if (result.weighing_result != null) {
                            weighingResult.val(result.weighing_result);
                        }

                        if (result.weighing_difference != null) {
                            weighingDifference.val(result.weighing_difference);
                        }

                        if (result.vessel_ullage_result != null) {
                            shipDraftResult.val(result.vessel_ullage_result);
                        }

                        if (result.vessel_ullage_difference != null) {
                            shipDraftDifference.val(result.vessel_ullage_difference);
                        }

                        if (result.piece_count_result != null) {
                            vehicleCountingResult.val(result.piece_count_result);
                        }

                        if (result.piece_count_difference != null) {
                            vehicleCountingDifference.val(result.piece_count_difference);
                        }

                        if (result.shore_ullage_result != null) {
                            shoreTankResult.val(result.shore_ullage_result);
                        }

                        if (result.shore_ullage_difference != null) {
                            shoreTankDifference.val(result.shore_ullage_difference);
                        }

                        if (result.remarks != null) {
                            remarks.val(urldecode(result.remarks));
                        }

                        if (result.operation.surveillance_types.length > 0) {

                            var survTString = "";

                            result.operation.surveillance_types.forEach(function (surv, index) {

                                if (index === result.operation.surveillance_types.length - 1 || result.operation.surveillance_types.length === 1) {
                                    survTString += surv.surveillance_type_name;
                                } else {
                                    survTString += (surv.surveillance_type_name + ", ");
                                }

                                survTypeList.text(survTString);

                            });

                        }

                        if (result.operation.process_types.length > 0) {

                            var procTString = "";

                            result.operation.process_types.forEach(function (proc, index) {

                                if (index === result.operation.process_types.length - 1 || result.operation.process_types.length === 1) {
                                    procTString += proc.process_type_name;
                                } else {
                                    procTString += (proc.process_type_name + ", ");
                                }

                                procTypeList.text(procTString);

                            });

                        }

                        if (result.operation.analysis_conditions.length > 0) {

                            var anlcTString = "";

                            result.operation.analysis_conditions.forEach(function (anlc, index) {

                                if (index === result.operation.analysis_conditions.length - 1 || result.operation.analysis_conditions.length === 1) {
                                    anlcTString += anlc.analysis_condition_name;
                                } else {
                                    anlcTString += (anlc.analysis_condition_name + ", ");
                                }

                                anlTypeList.text(anlcTString);

                            });

                        }

                        if (result.operation.offices.length > 0) {

                            var officesString = "";

                            result.operation.offices.forEach(function (office, index) {

                                if (index === result.operation.offices.length - 1 || result.operation.offices.length === 1) {
                                    officesString += office.office_name;
                                } else {
                                    officesString += (office.office_name + ", ");
                                }

                            });

                            $('#ilocSurvInfo').val(officesString);
                        }

                        if (result.operation.surveillances.length > 0) {

                            result.operation.surveillances.forEach(function (surv, index) {

                                var reqSurvLabel = $('<label>').prop('class', 'col-md-3 col-form-label').prop('for', 'reqSurvForEdit' + surv.surveillance_id).text(surv.surveillance_name);
                                var reqSurvCustomDiv = $('<div>').prop('class', 'col-md-3 vertAlgn');
                                var reqSurvInput = $('<input>').attr('data-on', 'Completed').attr('data-off', 'Not Completed').attr('data-onstyle', 'success').attr('data-offstyle', 'danger').prop('type', 'checkbox').prop('class', 'form-control').prop('id', 'reqSurvForEdit' + surv.surveillance_id).prop('name', 'reqSurvForEdit[]').val(surv.surveillance_id);

                                if (result.done_surveillances[index].is_completed === 0) {
                                    reqSurvInput.prop('checked', false);
                                } else {
                                    reqSurvInput.prop('checked', true);
                                }

                                requestedSurvList.append(reqSurvLabel).append(reqSurvCustomDiv.append(reqSurvInput));

                            });

                            $("#reqSurvListForSurvInfo :input").each(function () {
                                $(this).bootstrapToggle();
                            });

                        } else {
                            var thereIsNoSurvMessage = $('<div>').prop('class', 'text-center font-italic').text('There is no requested surveillances.');
                            requestedSurvList.append(thereIsNoSurvMessage);
                        }

                    }

                }
            });

        });
        //END SURVEILLANCE INFO FORM

        //BEGIN COMPLETE FORM
        //For Binding OperationID To Modal ( Complete Form )
        $('#warnCompleteForm').on('show.bs.modal', function (e) {
            $invokerLinkIDForCancel = $(e.relatedTarget).attr('id');
        });

        //Confirm Complete Form ( Complete Form )
        $('.confirmWarnCompleteForm').click(function () {
            var postDatass = {completeFormID: $invokerLinkIDForCancel};
            $.ajax({
                type: 'POST',
                url: 'field_operations.php',
                data: postDatass,
                dataType: 'text',
                success: function (resultData) {
                    var result = JSON.parse(resultData);

                    if (result.hasOwnProperty('success')) {
                        createCookie('successfulCompleteForm', result.success, 1);
                        location.reload(true);
                    }

                }
            });
        });
        //END COMPLETE FORM


        //BEGIN REMOVE COOKIES
        $('.removeCookieClass').click(function () {
            var cookieID = $(this).prop('id');
            eraseCookie(cookieID);
        });
        //END REMOVE COOKIES

    });


    //System JS Functions start here

    function universalDate(trDate) {
        var dateArray = trDate.split(" ");
        var datePartArray = dateArray[0].split("-");
        var timePartArray = dateArray[1].split(":");

        return new Date(datePartArray[2], datePartArray[1] - 1, datePartArray[0], timePartArray[0], timePartArray[1], timePartArray[2], 0);
    }

    function clearForm(whichForm) {
        $(':input', whichForm)
            .not(':button, :submit, :reset, :hidden, :checkbox, :radio')
            .val('');
        if (whichForm.find(":checkbox").length) {
            $(':input:checkbox', whichForm).bootstrapToggle('off');
        }
        if (whichForm.prop('id') === "addInfoModalA") {
            $(':radio', whichForm).prop('checked', false);
            var roadC = $('#roadCases');
            roadC.find('option').remove().end();
            var o = new Option("Yol durumunu seçiniz(Olağan/Sorun)..", "0");
            roadC.append(o);
            roadC.prop('disabled', true);
        }
        if (whichForm.prop('id') === "finishOperationModalA") {
            $(':radio', whichForm).prop('checked', false);
        }
    }

    jQuery.fn.getParent = function (num) {
        var last = this[0];
        for (var i = 0; i < num; i++) {
            last = last.parentNode;
        }
        return jQuery(last);
    };

    $.fn.setNow = function (onlyBlank) {
        var now = new Date($.now())
            , year
            , month
            , date
            , hours
            , minutes
            , seconds
            , formattedDateTime
        ;

        year = now.getFullYear();
        month = now.getMonth().toString().length === 1 ? '0' + (now.getMonth() + 1).toString() : now.getMonth() + 1;
        date = now.getDate().toString().length === 1 ? '0' + (now.getDate()).toString() : now.getDate();
        hours = now.getHours().toString().length === 1 ? '0' + now.getHours().toString() : now.getHours();
        minutes = now.getMinutes().toString().length === 1 ? '0' + now.getMinutes().toString() : now.getMinutes();
        seconds = now.getSeconds().toString().length === 1 ? '0' + now.getSeconds().toString() : now.getSeconds();

        formattedDateTime = year + '-' + month + '-' + date + 'T' + hours + ':' + minutes;

        if (onlyBlank === true && $(this).val()) {
            return this;
        }

        $(this).val(formattedDateTime);

        return this;
    };

    function createCookie(name, value, days) {
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            var expires = "; expires=" + date.toGMTString();
        } else var expires = "";
        document.cookie = name + "=" + value + expires + "; path=/";
    }

    function readCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }

    function urldecode(str) {
        return decodeURIComponent((str + '').replace(/\+/g, '%20'));
    }

    function eraseCookie(name) {
        createCookie(name, "", -1);
    }

    function sortTable(n, tableID, type) {
        var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
        table = document.getElementById(tableID);
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
                if (type == "date") {
                    if (dir == "asc") {
                        if (universalDate(x.innerHTML).getTime() > universalDate(y.innerHTML).getTime()) {
                            //if so, mark as a switch and break the loop:
                            shouldSwitch = true;
                            break;
                        }
                    } else if (dir == "desc") {
                        if (universalDate(x.innerHTML).getTime() < universalDate(y.innerHTML).getTime()) {
                            //if so, mark as a switch and break the loop:
                            shouldSwitch = true;
                            break;
                        }
                    }
                } else if (type == "int") {
                    if (dir == "asc") {
                        if (parseInt(x.innerHTML) > parseInt(y.innerHTML)) {
                            //if so, mark as a switch and break the loop:
                            shouldSwitch = true;
                            break;
                        }
                    } else if (dir == "desc") {
                        if (parseInt(x.innerHTML) < parseInt(y.innerHTML)) {
                            //if so, mark as a switch and break the loop:
                            shouldSwitch = true;
                            break;
                        }
                    }
                } else if (type == "str") {
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
<footer class="footer bg-dark">
    <div class="container">
        <p class="text-muted">Krekpot Bilgi Teknolojileri 2019&reg; All Rights Reserved.
        </p>

    </div>
</footer>
</body>
</html>