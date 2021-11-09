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


$apiCaller = new ApiCaller('1', $_SESSION['token']);

$customers = $apiCaller->sendRequest(array(
    'api_url' => 'https://lumen.krekpot.com/api/v1/viewCustomersForNewOperation',
    'api_method' => 'get',
));

$inspectionLocations = $apiCaller->sendRequest(array(
    'api_url' => 'https://lumen.krekpot.com/api/v1/viewMyOffices',
    'api_method' => 'get',
));

$surveillanceTypes = $apiCaller->sendRequest(array(
    'api_url' => 'https://lumen.krekpot.com/api/v1/viewSurveillanceTypes',
    'api_method' => 'get',
));

$processTypes = $apiCaller->sendRequest(array(
    'api_url' => 'https://lumen.krekpot.com/api/v1/viewProcessTypes',
    'api_method' => 'get',
));

$analysisConditions = $apiCaller->sendRequest(array(
    'api_url' => 'https://lumen.krekpot.com/api/v1/viewAnalysisConditions',
    'api_method' => 'get',
));

$analyzes = $apiCaller->sendRequest(array(
    'api_url' => 'https://lumen.krekpot.com/api/v1/viewAnalysises',
    'api_method' => 'get',
));

$surveillances = $apiCaller->sendRequest(array(
    'api_url' => 'https://lumen.krekpot.com/api/v1/viewSurveillances',
    'api_method' => 'get',
));

$waitingOperations = $apiCaller->sendRequest(array(
    'api_url' => 'https://lumen.krekpot.com/api/v1/getOperationsOfficer/1',
    'api_method' => 'get',
));
$activeOperations = $apiCaller->sendRequest(array(
    'api_url' => 'https://lumen.krekpot.com/api/v1/getOperationsOfficer/2',
    'api_method' => 'get',
));
$completedOperations = $apiCaller->sendRequest(array(
    'api_url' => 'https://lumen.krekpot.com/api/v1/getOperationsOfficer/3',
    'api_method' => 'get',
));
$myOperations = $apiCaller->sendRequest(array(
    'api_url' => 'https://lumen.krekpot.com/api/v1/myOperations/3',
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
    <link rel="stylesheet" type="text/css" href="css/custom.css">
    <link rel="stylesheet" type="text/css" href="css/all.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap4-toggle.css">
    <link rel="stylesheet" type="text/css" href="css/select2.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap-select.css">
    <script type="text/javascript" src="js/jquery-3.3.1.js"></script>
    <script type="text/javascript" src="js/popper.js"></script>
    <script type="text/javascript" src="js/bootstrap.js"></script>
    <script type="text/javascript" src="js/bootstrap4-toggle.js"></script>
    <script type="text/javascript" src="js/select2.js"></script>
    <script type="text/javascript" src="js/bootstrap-select.js"></script>
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
        <h4>Officer Operations</h4>
    </div>
</div>

<div class="container">


    <?php foreach ($_COOKIE as $key => $value) {
        if (substr($key, 0, strlen("success")) === "success") {
            ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong><?php echo $value ?></strong>
                <button id="<?php echo trim($key); ?>" type="button" class="close removeCookieClass"
                        data-dismiss="alert"
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
            Section where you can follow the operations connected to your office.
            <button type="button" class="btn btn-outline-primary btn-sm float-right newOperationButton"
                    data-toggle="modal"
                    data-target="#newOperationModal" data-backdrop="static" data-keyboard="false"><i
                        class="fas fa-plus-circle"></i> Start
                New Operation
            </button>
            <button type="button" class="btn btn-outline-primary btn-sm float-right printTable"
                    style="margin-right: 5px;"><i
                        class="fas fa-print"></i> Print
            </button>
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a id="waitingTabLink" class="nav-link active" data-toggle="tab" href="#waiting">Waiting List <span
                                class="badge badge-primary"><?php echo $waitingOperations['data']->count;
                            unset($waitingOperations['data']->count); ?></span></a>
                </li>
                <li class="nav-item">
                    <a id="activeTabLink" class="nav-link" data-toggle="tab" href="#active">Active List <span
                                class="badge badge-success"><?php echo $activeOperations['data']->count;
                            unset($activeOperations['data']->count); ?></span></a>
                </li>
                <li class="nav-item">
                    <a id="completedTabLink" class="nav-link" data-toggle="tab" href="#completed">Completed List <span
                                class="badge badge-secondary"><?php echo $completedOperations['data']->count;
                            unset($completedOperations['data']->count); ?></span></a>
                </li>
                <li class="nav-item">
                    <a id="myOptTabLink" class="nav-link" data-toggle="tab" href="#myopt">My Operations<span
                                class="badge badge-secondary"><?php echo $myOperations['data']->count;
                            unset($myOperations['data']->count); ?></span></a>
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
                            <?php foreach ($waitingOperations['data'] as $opt) { ?>
                                <tr>
                                    <td class="waitingOperationID">GSI<?php echo $opt->operation_id; ?></td>
                                    <td class="waitingCustomer">
                                        <strong><?php echo $opt->customer->company_shortcode . "</strong><br/>(" . $opt->customer->contact_person_title . ") " . $opt->customer->contact_person_name . " " . $opt->customer->contact_person_surname . "<br/>" . $opt->customer->contact_person_phone; ?>
                                    </td>
                                    <td class="waitingNominationCustomer">
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
                                                <a class="dropdown-item editDetailedButtonWaiting" data-toggle="modal"
                                                   data-target="#editDetailedModalWaiting" data-backdrop="static"
                                                   data-keyboard="false" href="#"
                                                   id="<?php echo $opt->operation_id; ?>">Edit Detailed</a>
                                                <a class="dropdown-item cancelOperationButtonWaiting"
                                                   data-toggle="modal"
                                                   data-target="#warnCancelW" data-backdrop="static"
                                                   data-keyboard="false"
                                                   id="<?php echo $opt->operation_id; ?>" href="#">Cancel Operation</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php }
                            if (empty((array)$waitingOperations['data'])) {
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
                            <?php foreach ($activeOperations['data'] as $opt) { ?>
                                <tr>
                                    <td class="activeOperationID">GSI<?php echo $opt->operation_id; ?></td>
                                    <td class="activeCustomer">
                                        <strong><?php echo $opt->customer->company_shortcode . "</strong><br/>(" . $opt->customer->contact_person_title . ") " . $opt->customer->contact_person_name . " " . $opt->customer->contact_person_surname . "<br/>" . $opt->customer->contact_person_phone; ?>
                                    </td>
                                    <td class="activeNominationCustomer">
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
                                                <a class="dropdown-item deptStatusButtonActive" data-toggle="modal"
                                                   data-target="#deptStatusModalActive" data-backdrop="static"
                                                   data-keyboard="false"
                                                   href="#" id="<?php echo $opt->operation_id; ?>">Dept. Statuses</a>
                                                <a class="dropdown-item viewDetailButtonActive" data-toggle="modal"
                                                   data-target="#viewDetailModalActive" data-backdrop="static"
                                                   data-keyboard="false"
                                                   href="#" id="<?php echo $opt->operation_id; ?>">View Detailed</a>
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
                                                <a class="dropdown-item infCustButtonActive" data-toggle="modal"
                                                   data-target="#informCustomerModalActive" data-backdrop="static"
                                                   data-keyboard="false"
                                                   href="#" id="<?php echo $opt->operation_id; ?>">Inform Customer</a>
                                                <a class="dropdown-item showOperationExpenses" data-toggle="modal"
                                                   data-target="#viewOperationExpensesModal" data-backdrop="static"
                                                   data-keyboard="false"
                                                   href="#" id="<?php echo $opt->operation_id; ?>">Show Operation
                                                    Expenses</a>
                                                <a class="dropdown-item finOptButtonActive" data-toggle="modal"
                                                   data-target="#finishOperationModalActive" data-backdrop="static"
                                                   data-keyboard="false"
                                                   href="#" id="<?php echo $opt->operation_id; ?>">Complete
                                                    Operation</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php }
                            if (empty((array)$activeOperations['data'])) {
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
                            <?php foreach ($completedOperations['data'] as $opt) { ?>
                                <tr>
                                    <td>GSI<?php echo $opt->operation_id; ?></td>
                                    <td>
                                        <strong><?php echo $opt->customer->company_shortcode . "</strong><br/>(" . $opt->customer->contact_person_title . ") " . $opt->customer->contact_person_name . " " . $opt->customer->contact_person_surname . "<br/>" . $opt->customer->contact_person_phone; ?>
                                    </td>
                                    <td>
                                        <strong><?php echo ($opt->nomination_customer_id == null) ? "-" : $opt->nomination_customer->company_shortcode . "</strong><br/>(" . $opt->nomination_customer->contact_person_title . ") " . $opt->nomination_customer->contact_person_name . " " . $opt->nomination_customer->contact_person_surname . "<br/>" . $opt->nomination_customer->contact_person_phone; ?>
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
                                            <button type="button" class="btn btn-primary btn-sm">
                                                View Detailed
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php }
                            if (empty((array)$completedOperations['data'])) {
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
                <div id="myopt" class="container tab-pane fade">
                    <div class="input-group" style="margin-top: 10px;">
                        <input id="myInputMyOpt" type="text" class="form-control"
                               placeholder="Type something for search..">
                        <div class="input-group-append">
                            <button class="btn btn-secondary" type="button">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped" id="myOptTable" style="font-size: 12px;">
                            <thead>
                            <tr>
                                <th onclick="sortTable(0,'myOptTable','str')">#</th>
                                <th onclick="sortTable(1,'myOptTable','str')">Customer</th>
                                <th onclick="sortTable(2,'myOptTable','str')">Nomination Customer</th>
                                <th onclick="sortTable(3,'myOptTable','date')">Started Date</th>
                                <th onclick="sortTable(4,'myOptTable','date')">Completed Date</th>
                                <th onclick="sortTable(5,'myOptTable','str')">Vessel Name</th>
                                <th onclick="sortTable(6,'myOptTable','str')">Goods</th>
                                <th onclick="sortTable(7,'myOptTable','int')">Amount</th>
                                <th onclick="sortTable(8,'myOptTable','str')">Officer</th>
                                <th onclick="sortTable(9,'myOptTable','str')">Expert</th>
                                <th onclick="sortTable(10,'myOptTable','str')">Inspectors</th>
                                <th>Location</th>
                                <th>Processes</th>
                            </tr>
                            </thead>
                            <tbody id="myTableMyOpt">
                            <?php foreach ($myOperations['data'] as $opt) { ?>
                                <tr>
                                    <td>GSI<?php echo $opt->operation_id; ?></td>
                                    <td>
                                        <strong><?php echo $opt->customer->company_name . "</strong><br/>(" . $opt->customer->contact_person_title . ") " . $opt->customer->contact_person_name . " " . $opt->customer->contact_person_surname . "<br/>" . $opt->customer->contact_person_phone; ?>
                                    </td>
                                    <td>
                                        <strong><?php echo (is_null($opt->nomination_customer_id)) ? "-" : $opt->nomination_customer->company_shortcode . "</strong><br/>(" . $opt->nomination_customer->contact_person_title . ") " . $opt->nomination_customer->contact_person_name . " " . $opt->nomination_customer->contact_person_surname . "<br/>" . $opt->nomination_customer->contact_person_phone; ?>
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
                                            <button type="button" class="btn btn-primary btn-sm">
                                                View Detailed
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php }
                            if (empty((array)$myOperations['data'])) {
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

<!-- Begin Waiting Modals -->
<!-- Cancel Operation Modal -->
<div id="warnCancelW" class="modal fade" role="dialog" data-backdrop="false">
    <div class="modal-dialog modal-dialog-centered">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                Warning
            </div>
            <form id="cancelOperationForm" name="cancelOperationForm" action="officer_operations.php" method="post">
                <input type="hidden" name="cancelOperationID" id="cancelOperationID"/>
                <div class="modal-body">
                    <p id="cancelOperationWarning"></p>

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="cancelOperationExplanation">Explanation:</label>
                            <textarea class="form-control" id="cancelOperationExplanation"
                                      name="cancelOperationExplanation" rows="3"
                                      placeholder="You should type an explanation for canceling operation."
                                      required></textarea>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary closeCancelOperationModal">Close</button>
                    <button type="submit" class="btn btn-primary" value="1">Cancel Operation</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Warning For Cancel Operation Modal -->
<div id="cancelWarnCancel" class="modal fade" role="dialog" data-backdrop="false">
    <div class="modal-dialog modal-dialog-centered">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                Warning
            </div>
            <div class="modal-body">
                <p>If you close without saving, your changes will be lost. Are you sure you want to close?</p>
                <button type="button" class="btn btn-primary float-right btn-sm" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-danger confirmWarnCancel float-right btn-sm">Yes</button>
            </div>
        </div>
    </div>
</div>

<!-- View Detail Modal -->
<div class="modal fade" id="viewDetailedModalWaiting" tabindex="-1" role="dialog"
     aria-labelledby="viewDetailedModalWaitingTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="viewDetailedModalWaitingTitle">Operation: <strong
                            id="optViewDW"></strong><br/> Customer:
                    <strong id="custViewDW" style="text-transform:capitalize;"></strong><br/> Nomination
                    Customer:<strong id="nomCustViewDW"></strong><br/> Buyer: <strong
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

                <h6 style="font-size: 16px; margin-top: 20px;">Requested Analysis</h6>
                <hr/>
                <div class="row" id="reqAnlViewDW">
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
<!-- End Waiting Modals -->

<!-- Begin Active Modals -->
<!-- Department Statuses Modal -->
<div class="modal fade" id="deptStatusModalActive" tabindex="-1" role="dialog"
     aria-labelledby="deptStatusModalActiveTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="deptStatusModalActiveTitle">Operation: <strong
                            id="optDeptSA"></strong><br/> Customer:
                    <strong id="custDeptSA"></strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-4 col-md-4" style="margin-bottom: 10px; font-weight: 500;">
                        Surveillance Forms:
                        <div id="deptStatusSurvCount">(0/2)</div>
                    </div>
                    <div id="deptStatusSurvList" class="col-lg-8 col-md-8">
                    </div>
                </div>
                <hr/>
                <div class="row">
                    <div class="col-lg-4 col-md-4" style="margin-bottom: 10px; font-weight: 500;">
                        Laboratory Forms:
                        <div id="deptStatusLabCount">(0/2)</div>
                    </div>
                    <div id="deptStatusLabList" class="col-lg-8 col-md-8">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Finish Operation Modal -->
<div class="modal fade" id="finishOperationModalActive" tabindex="-1" role="dialog"
     aria-labelledby="finishOperationModalActiveTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="finishOperationModalActiveTitle">Operation: <strong
                            id="optFinOA"></strong><br/> Customer:
                    <strong id="custFinOA"></strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="finishOperationForm" name="finishOperationForm" action="officer_operations.php" method="post">
                <input type="hidden" name="finishOperationID" id="finishOperationID"/>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-4 col-md-4" style="margin-bottom: 10px; font-weight: 500;">
                            Surveillance Forms:
                            <div id="finOptSurvCount"></div>
                        </div>
                        <div id="finOptSurvMessage" class="col-lg-8 col-md-8">
                        </div>
                    </div>
                    <hr/>
                    <div class="row">
                        <div class="col-lg-4 col-md-4" style="margin-bottom: 10px; font-weight: 500;">
                            Laboratory Forms:
                            <div id="finOptLabCount"></div>
                        </div>
                        <div id="finOptLabMessage" class="col-lg-8 col-md-8">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-toggle="modal"
                            data-target="#warnAnyway" data-backdrop="static"
                            data-keyboard="false" class="btn btn-danger mr-auto finishAnywayButton">Finish Anyway
                    </button>
                    <button type="button" class="btn btn-secondary closeFinishOperationModal">Close</button>
                    <button type="submit" class="btn btn-primary confirmCompleteOptButton" value="1">Complete
                        Operation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Warning For Finish Operation Modal -->
<div id="cancelWarnFinish" class="modal fade" role="dialog" data-backdrop="false">
    <div class="modal-dialog modal-dialog-centered">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                Warning
            </div>
            <div class="modal-body">
                <p>Are you sure you want to close?</p>
                <button type="button" class="btn btn-primary float-right btn-sm" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-danger confirmWarnFinish float-right btn-sm">Yes</button>
            </div>
        </div>
    </div>
</div>

<!-- Inform Customer Modal -->
<div id="informCustomerModalActive" class="modal fade" role="dialog" data-backdrop="false">
    <div class="modal-dialog modal-dialog-centered">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="informCustomerModalActiveTitle">Operation: <strong
                            id="optInfCA"></strong><br/> Customer:
                    <strong id="custInfCA"></strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="informCustomerForm" name="informCustomerForm" action="officer_operations.php" method="post">
                <input type="hidden" name="informCustomerOptID" id="informCustomerOptID"/>
                <div class="modal-body">

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="informCustomerContact" style="font-weight: 500;">Contact Person Email:</label>
                            <p id="informCustomerInformations"></p>
                            <input type="email" class="form-control" id="informCustomerContact"
                                   name="informCustomerContact" required readonly/>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="informCustomerMessage" style="font-weight: 500;">Message:</label>
                            <textarea class="form-control" id="informCustomerMessage"
                                      name="informCustomerMessage" rows="3" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary closeInformCustomerModal">Close</button>
                    <button type="submit" class="btn btn-primary" value="1">Send Mail</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Warning For Inform Customer Modal -->
<div id="cancelWarnInformCust" class="modal fade" role="dialog" data-backdrop="false">
    <div class="modal-dialog modal-dialog-centered">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                Warning
            </div>
            <div class="modal-body">
                <p>If you close without saving, your changes will be lost. Are you sure you want to close?</p>
                <button type="button" class="btn btn-primary float-right btn-sm" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-danger confirmWarnInformCustomer float-right btn-sm">Yes</button>
            </div>
        </div>
    </div>
</div>

<!-- View Detail Modal Active -->
<div class="modal fade" id="viewDetailModalActive" tabindex="-1" role="dialog"
     aria-labelledby="viewDetailModalActiveTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="viewDetailModalActiveTitle">Operation: <strong
                            id="optViewDA">#234</strong><br/> Customer:
                    <strong id="custViewDA"></strong><br/>Nomination Customer: <strong id="custNomViewDA"></strong><br/>
                    Buyer: <strong
                            id="buyerViewDA"
                            style="text-transform:capitalize;"></strong><br/>
                    Seller:
                    <strong
                            id="sellerViewDA" style="text-transform:capitalize;"></strong><br/> Supplier: <strong
                            id="supplierViewDA" style="text-transform:capitalize;"></strong></h6>
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
                            <label for="vesselViewDA">Vessel Name</label>
                            <input type="text" class="form-control form-control-sm" id="vesselViewDA" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="goodsViewDA">Type Of Goods</label>
                            <input type="text" class="form-control form-control-sm" id="goodsViewDA" readonly>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="amountViewDA">Amount</label>
                            <input type="text" class="form-control form-control-sm" id="amountViewDA" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="ilocViewDA">Inspection Locations</label>
                            <input type="text" class="form-control form-control-sm" id="ilocViewDA" readonly>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="survTypeViewDA"><b>Surveillance Types</b></label>
                            <div id="survTypeViewDA"></div>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="procTypeViewDA"><b>Process Types</b></label>
                            <div id="procTypeViewDA"></div>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="anlCondViewDA"><b>Analysis Conditions</b></label>
                            <div id="anlCondViewDA"></div>
                        </div>
                    </div>
                </form>

                <h6 style="font-size: 16px; margin-top: 10px;">Requested Surveillance</h6>
                <hr/>
                <div class="row" id="reqSurvViewDA">
                </div>

                <h6 style="font-size: 16px; margin-top: 20px;">Requested Analysis</h6>
                <hr/>
                <div class="row" id="reqAnlViewDA">
                </div>

                <div class="row" style="margin-top: 20px;">
                    <div class="col-lg-6">
                        <h6 style="font-size: 16px; margin-top: 20px;">Surveillance Info Forms</h6>
                        <hr/>
                        <div class="row">
                            <div class="col-lg-12 col-md-12" id="survFormViewDA">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <h6 style="font-size: 16px; margin-top: 20px;">Laboratory Forms</h6>
                        <hr/>
                        <div class="row">
                            <div class="col-lg-12 col-md-12" id="labFormViewDA">
                            </div>
                        </div>
                    </div>
                </div>


                <h6 style="font-size: 16px; margin-top: 30px;">Form Owner & Date</h6>
                <hr/>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="">Officer & Creation Date</span>
                    </div>
                    <input type="text" class="form-control" id="officerViewDA" readonly>
                    <input type="text" class="form-control" id="dateViewDA" readonly>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End Active Modals -->

<!-- Begin New Operation Modal -->
<div class="modal fade" id="newOperationModal" tabindex="-1" role="dialog" aria-labelledby="newOperationModalTitle"
     aria-hidden="true" style="overflow-y:auto;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newOperationModalTitle">New Operation Form</h5>
            </div>

            <form id="newOperationForm" name="newOperationForm" action="officer_operations.php" method="post">

                <div class="modal-body" style="font-size: 14px;">

                    <h6 style="font-size: 16px;">Informations</h6>
                    <hr/>
                    <div class="form-row">
                        <div class="form-group col-md-6 col-sm-12">
                            <label for="newOperationCustomer">Customer</label>
                            <select class="selectpicker show-tick" title="Choose one..."
                                    id="newOperationCustomer"
                                    name="newOperationCustomer" data-live-search="true" data-width="100%" required>
                                <?php foreach ($customers['data'] as $cst) { ?>
                                    <option value="<?php echo $cst->customer_id; ?>"><?php echo $cst->company_name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group col-md-6 col-sm-12">
                            <label for="newOperationNominationCustomer">Nomination Customer</label>
                            <select class="selectpicker show-tick" title="Choose one..."
                                    id="newOperationNominationCustomer"
                                    name="newOperationNominationCustomer" data-live-search="true" data-width="100%"
                                    multiple data-max-options="1">
                                <?php foreach ($customers['data'] as $cst) { ?>
                                    <option value="<?php echo $cst->customer_id; ?>"><?php echo $cst->company_name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="newOperationBuyer">Buyer</label>
                            <input type="text" class="form-control form-control-sm" id="newOperationBuyer"
                                   name="newOperationBuyer">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="newOperationSeller">Seller</label>
                            <input type="text" class="form-control form-control-sm" id="newOperationSeller"
                                   name="newOperationSeller">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="newOperationSupplier">Supplier</label>
                            <input type="text" class="form-control form-control-sm" id="newOperationSupplier"
                                   name="newOperationSupplier">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="newOperationVessel">Vessel Name</label>
                            <input type="text" class="form-control form-control-sm" id="newOperationVessel"
                                   name="newOperationVessel" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="newOperationGoods">Type Of Goods</label>
                            <input type="text" class="form-control form-control-sm" id="newOperationGoods"
                                   name="newOperationGoods" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="newOperationAmount">Amount</label>
                            <input type=number min="0" step="0.001" class="form-control form-control-sm"
                                   id="newOperationAmount"
                                   name="newOperationAmount" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="newOperationLocation">Inspection Locations</label>
                            <select class="js-example-basic-multiple js-states form-control"
                                    name="newOperationLocation[]"
                                    multiple="multiple"
                                    style="width: 100%;"
                                    id="newOperationLocation" required>
                                <?php foreach ($inspectionLocations['data'] as $ins) { ?>
                                    <option value="<?php echo $ins->office_id; ?>"><?php echo $ins->office_name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="newOperationSurvT">Surveillance Type</label>
                            <select class="form-control selectpicker show-tick" multiple title="Choose one..."
                                    id="newOperationSurvT"
                                    name="newOperationSurvT[]" required>
                                <?php foreach ($surveillanceTypes['data'] as $surv) { ?>
                                    <option value="<?php echo $surv->surveillance_type_id; ?>"><?php echo $surv->surveillance_type_name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="newOperationProcT">Process Type</label>
                            <select class="form-control selectpicker show-tick" multiple title="Choose one..."
                                    id="newOperationProcT"
                                    name="newOperationProcT[]" required>
                                <?php foreach ($processTypes['data'] as $proc) { ?>
                                    <option value="<?php echo $proc->process_type_id; ?>"><?php echo $proc->process_type_name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="newOperationAnlC">Analysis Condition</label>
                            <select class="form-control selectpicker show-tick" multiple title="Choose one..."
                                    id="newOperationAnlC"
                                    name="newOperationAnlC[]" required>
                                <?php foreach ($analysisConditions['data'] as $anl) { ?>
                                    <option value="<?php echo $anl->analysis_condition_id; ?>"><?php echo $anl->analysis_condition_name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <h6 style="font-size: 16px; margin-top: 10px;">Requested Surveillance</h6>
                    <hr/>
                    <div class="row" id="reqSurvCheckboxes">
                        <?php foreach ($surveillances['data'] as $singleSurv) { ?>
                            <div class="col-md-4" style="margin-bottom: 5px;">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="newOperationReqSurv<?php echo $singleSurv->surveillance_id; ?>"
                                           name="newOperationReqSurv[]"
                                           value="<?php echo $singleSurv->surveillance_id; ?>">
                                    <label class="custom-control-label"
                                           for="newOperationReqSurv<?php echo $singleSurv->surveillance_id; ?>"><?php echo $singleSurv->surveillance_name; ?></label>
                                </div>
                            </div>
                        <?php } ?>
                    </div>

                    <h6 style="font-size: 16px; margin-top: 20px;">Requested Analysis</h6>
                    <hr/>
                    <div class="row" id="reqAnlCheckboxes">
                        <?php foreach ($analyzes['data'] as $singleAnl) { ?>
                            <div class="col-md-4" style="margin-bottom: 5px;">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input reqAnlCheckboxClass"
                                           id="newOperationReqAnl<?php echo $singleAnl->analysis_id; ?>"
                                           name="newOperationReqAnl[]" value="<?php echo $singleAnl->analysis_id; ?>">
                                    <label class="custom-control-label"
                                           for="newOperationReqAnl<?php echo $singleAnl->analysis_id; ?>"><?php echo $singleAnl->analysis_name; ?></label>
                                </div>
                                <input type="text" class="form-control"
                                       id="newOperationReqSpec<?php echo $singleAnl->analysis_id; ?>"
                                       name="newOperationReqSpec<?php echo $singleAnl->analysis_id; ?>" disabled>
                            </div>
                        <?php } ?>
                    </div>

                    <h6 style="font-size: 16px; margin-top: 20px;">Form Owner & Date</h6>
                    <hr/>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="">Officer & Creation Date</span>
                        </div>
                        <input type="text" class="form-control"
                               value="<?php echo $_SESSION["user_name"] . " " . $_SESSION["user_surname"] ?>" readonly>
                        <input type="datetime-local" class="form-control" readonly id="newOperationFormDate">
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary closeNewOperationModal">Close</button>
                    <button type="submit" class="btn btn-primary" value="1">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- BEGIN EDIT OPERATION -->
<!-- Edit Operation Modal -->
<div class="modal fade" id="editDetailedModalWaiting" tabindex="-1" role="dialog"
     aria-labelledby="editDetailedModalWaitingTitle"
     aria-hidden="true" style="overflow-y:auto;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editDetailedModalWaitingTitle">Edit Operation Form - Operation <strong
                            id="editOptWOptID"></strong></h5>
            </div>

            <form id="editOperationWaitingForm" name="editOperationWaitingForm" action="officer_operations.php"
                  method="post">

                <input type="hidden" id="editOperationOptID" name="editOperationOptID" value="0"/>

                <div class="modal-body" style="font-size: 14px;">

                    <h6 style="font-size: 16px;">Informations</h6>
                    <hr/>
                    <div class="form-row">
                        <div class="form-group col-md-6 col-sm-12">
                            <label for="editOperationCustomer">Customer</label>
                            <select class="selectpicker show-tick" title="Choose one..."
                                    id="editOperationCustomer"
                                    name="editOperationCustomer" data-live-search="true" data-width="100%" required>
                                <?php foreach ($customers['data'] as $cst) { ?>
                                    <option value="<?php echo $cst->customer_id; ?>"><?php echo $cst->company_name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group col-md-6 col-sm-12">
                            <label for="editOperationNominationCustomer">Nomination Customer</label>
                            <select class="selectpicker show-tick" title="Choose one..."
                                    id="editOperationNominationCustomer"
                                    name="editOperationNominationCustomer" data-live-search="true" data-width="100%"
                                    multiple data-max-options="1">
                                <?php foreach ($customers['data'] as $cst) { ?>
                                    <option value="<?php echo $cst->customer_id; ?>"><?php echo $cst->company_name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="editOperationBuyer">Buyer</label>
                            <input type="text" class="form-control form-control-sm" id="editOperationBuyer"
                                   name="editOperationBuyer">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="editOperationSeller">Seller</label>
                            <input type="text" class="form-control form-control-sm" id="editOperationSeller"
                                   name="editOperationSeller">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="editOperationSupplier">Supplier</label>
                            <input type="text" class="form-control form-control-sm" id="editOperationSupplier"
                                   name="editOperationSupplier">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="editOperationVessel">Vessel Name</label>
                            <input type="text" class="form-control form-control-sm" id="editOperationVessel"
                                   name="editOperationVessel" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="editOperationGoods">Type Of Goods</label>
                            <input type="text" class="form-control form-control-sm" id="editOperationGoods"
                                   name="editOperationGoods" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="editOperationAmount">Amount</label>
                            <input type=number min="0" step="0.001" class="form-control form-control-sm"
                                   id="editOperationAmount"
                                   name="editOperationAmount" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="editOperationLocation">Inspection Locations</label>
                            <select class="js-example-basic-multiple js-states form-control"
                                    name="editOperationLocation[]"
                                    multiple="multiple"
                                    style="width: 100%;"
                                    id="editOperationLocation" disabled>
                                <?php foreach ($inspectionLocations['data'] as $ins) { ?>
                                    <option value="<?php echo $ins->office_id; ?>"><?php echo $ins->office_name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="editOperationSurvT">Surveillance Type</label>
                            <select class="form-control selectpicker show-tick" multiple title="Choose one..."
                                    id="editOperationSurvT"
                                    name="editOperationSurvT[]" disabled>
                                <?php foreach ($surveillanceTypes['data'] as $surv) { ?>
                                    <option value="<?php echo $surv->surveillance_type_id; ?>"><?php echo $surv->surveillance_type_name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="editOperationProcT">Process Type</label>
                            <select class="form-control selectpicker show-tick" multiple title="Choose one..."
                                    id="editOperationProcT"
                                    name="editOperationProcT[]" disabled>
                                <?php foreach ($processTypes['data'] as $proc) { ?>
                                    <option value="<?php echo $proc->process_type_id; ?>"><?php echo $proc->process_type_name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="editOperationAnlC">Analysis Condition</label>
                            <select class="form-control selectpicker show-tick" multiple title="Choose one..."
                                    id="editOperationAnlC"
                                    name="editOperationAnlC[]" disabled>
                                <?php foreach ($analysisConditions['data'] as $anl) { ?>
                                    <option value="<?php echo $anl->analysis_condition_id; ?>"><?php echo $anl->analysis_condition_name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <h6 style="font-size: 16px; margin-top: 10px;">Requested Surveillance</h6>
                    <hr/>
                    <div class="row" id="reqSurvEditCheckboxes">
                        <?php foreach ($surveillances['data'] as $singleSurv) { ?>
                            <div class="col-md-4" style="margin-bottom: 5px;">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="editOperationReqSurv<?php echo $singleSurv->surveillance_id; ?>"
                                           name="editOperationReqSurv[]"
                                           value="<?php echo $singleSurv->surveillance_id; ?>">
                                    <label class="custom-control-label"
                                           for="editOperationReqSurv<?php echo $singleSurv->surveillance_id; ?>"><?php echo $singleSurv->surveillance_name; ?></label>
                                </div>
                            </div>
                        <?php } ?>
                    </div>

                    <h6 style="font-size: 16px; margin-top: 20px;">Requested Analysis</h6>
                    <hr/>
                    <div class="row" id="reqAnlEditCheckboxes">
                        <?php foreach ($analyzes['data'] as $singleAnl) { ?>
                            <div class="col-md-4" style="margin-bottom: 5px;">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input reqAnlEditCheckboxClass"
                                           id="editOperationReqAnl<?php echo $singleAnl->analysis_id; ?>"
                                           name="editOperationReqAnl[]" value="<?php echo $singleAnl->analysis_id; ?>">
                                    <label class="custom-control-label"
                                           for="editOperationReqAnl<?php echo $singleAnl->analysis_id; ?>"><?php echo $singleAnl->analysis_name; ?></label>
                                </div>
                                <input type="text" class="form-control"
                                       id="editOperationReqSpec<?php echo $singleAnl->analysis_id; ?>"
                                       name="editOperationReqSpec<?php echo $singleAnl->analysis_id; ?>" disabled>
                            </div>
                        <?php } ?>
                    </div>

                    <h6 style="font-size: 16px; margin-top: 20px;">Form Owner & Date</h6>
                    <hr/>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="">Officer & Creation Date</span>
                        </div>
                        <input type="text" class="form-control"
                               value="<?php echo $_SESSION["user_name"] . " " . $_SESSION["user_surname"] ?>" readonly>
                        <input type="datetime-local" class="form-control" readonly id="editOperationFormDate">
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary closeEditOperationModal">Close</button>
                    <button type="submit" class="btn btn-primary" value="1">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Warning For Edit Operation Modal -->
<div id="cancelWarnEdit" class="modal fade" role="dialog" data-backdrop="false">
    <div class="modal-dialog modal-dialog-centered">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                Warning
            </div>
            <div class="modal-body">
                <p>If you close without saving, your changes will be lost. Are you sure you want to close?</p>
                <button type="button" class="btn btn-primary float-right btn-sm" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-danger confirmWarnEdit float-right btn-sm">Yes</button>
            </div>
        </div>
    </div>
</div>

<!-- END EDIT OPERATION -->

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

            <form id="addNewOperationNoteForm" name="addNewOperationNoteForm" action="officer_operations.php"
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

                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input"
                                   id="addOperationNoteTypeOne"
                                   name="addOperationNoteType[]" value="1">
                            <label class="custom-control-label"
                                   for="addOperationNoteTypeOne">For Inspector</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input"
                                   id="addOperationNoteTypeTwo"
                                   name="addOperationNoteType[]" value="2">
                            <label class="custom-control-label"
                                   for="addOperationNoteTypeTwo">For Laborant</label>
                        </div>
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

<!-- Show Expenses Modal -->
<div class="modal fade" id="viewOperationExpensesModal" tabindex="-1" role="dialog"
     aria-labelledby="viewOperationExpensesModalTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewOperationExpensesModalTitle">Operation Expenses</h5>
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

<!-- Warning For New Operation Modal -->
<div id="cancelWarnNew" class="modal fade" role="dialog" data-backdrop="false">
    <div class="modal-dialog modal-dialog-centered">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                Warning
            </div>
            <div class="modal-body">
                <p>If you close without saving, your changes will be lost. Are you sure you want to close?</p>
                <button type="button" class="btn btn-primary float-right btn-sm" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-danger confirmWarnNew float-right btn-sm">Yes</button>
            </div>
        </div>
    </div>
</div>
<!-- End New Operation Modal -->

<!-- Warning For Finish Anyway Modal -->
<div id="warnAnyway" class="modal fade" role="dialog" data-backdrop="false">
    <div class="modal-dialog modal-dialog-centered">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                Warning
            </div>
            <div class="modal-body">
                <p>Are you sure you want to complete operation without waiting surveillances and analysis forms
                    completed ?</p>
                <button type="button" class="btn btn-primary float-right btn-sm" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-danger float-right btn-sm confirmWarnAnyway">Yes</button>
            </div>
        </div>
    </div>
</div>
<!-- End Warning Finish Anyway Modal -->

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

<footer class="footer bg-dark">
    <div class="container">
        <p class="text-muted">Krekpot Bilgi Teknolojileri 2019&reg; All Rights Reserved.
        </p>

    </div>
</footer>
<script>
    var detectTab = readCookie('activeTab');
    var $invokerLinkIDForCancel = 0;

    $(document).ready(function () {
        $('[data-toggle="popover"]').popover();
        $('.js-example-basic-multiple').select2();

        //BEGIN SELECTPICKERS
        var newOptCust = $('#newOperationCustomer');
        var newOptNmCust = $('#newOperationNominationCustomer');
        var newOptSurvT = $('#newOperationSurvT');
        var newOptProcT = $('#newOperationProcT');
        var newOptAnlC = $('#newOperationAnlC');

        var editOptCust = $('#editOperationCustomer');
        var editOptNmCust = $('#editOperationNominationCustomer');
        var editOptSurvT = $('#editOperationSurvT');
        var editOptProcT = $('#editOperationProcT');
        var editOptAnlC = $('#editOperationAnlC');
        //END SELECTPICKERS

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
            case "m":
                tabs.find('#myOptTabLink').trigger('click');
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
        $("#myInputMyOpt").on("keyup", function () {
            var value = $(this).val().toLowerCase();
            $("#myTableMyOpt tr").filter(function () {
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
                case "m":
                    divToPrint = $("#myOptTable").clone();
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

        //BEGIN EDIT OPERATION WAITING
        //Filling Modal ( Edit Operation Waiting )
        $('.editDetailedButtonWaiting').on('click', function () {
            $('#editOptWOptID').text("#" + $(this).prop('id'));
            $('#editOperationOptID').val($(this).prop('id'));
            $('#editOperationFormDate').setNow();

            var postDataEditW = {selectedOperationEditW: $(this).prop('id')};
            $.ajax({
                type: 'POST',
                url: 'officer_operations.php',
                data: postDataEditW,
                dataType: 'text',
                success: function (resultData) {

                    var result = JSON.parse(resultData);

                    if (result.hasOwnProperty('error')) {
                        //Make error message
                        alert("OOPS");
                    }
                    if (result.hasOwnProperty('operation_id')) {

                        editOptCust.val(result.customer.customer_id);
                        if (result.is_double_nomination !== 0) {
                            editOptNmCust.val(result.nomination_customer_id);
                            editOptCust.selectpicker('refresh');
                        }
                        $('#editOperationBuyer').val(result.buyer);
                        $('#editOperationSeller').val(result.seller);
                        $('#editOperationSupplier').val(result.supplier);
                        $('#editOperationVessel').val(result.vessel_name);
                        $('#editOperationGoods').val(result.type_of_goods);
                        $('#editOperationAmount').val(result.amount);

                        let tmpSurvArray = [];
                        if (result.surveillance_types.length > 0) {
                            result.surveillance_types.forEach(function (survt, index) {
                                tmpSurvArray.push(survt.surveillance_type_id);
                            });
                        }
                        editOptSurvT.val(tmpSurvArray);

                        let tmpProcArray = [];
                        if (result.process_types.length > 0) {
                            result.process_types.forEach(function (proc, index) {
                                tmpProcArray.push(proc.process_type_id);
                            });
                        }
                        editOptProcT.val(tmpProcArray);

                        let tmpAnlArray = [];
                        if (result.analysis_conditions.length > 0) {
                            result.analysis_conditions.forEach(function (anlc, index) {
                                tmpAnlArray.push(anlc.analysis_condition_id);
                            });
                        }
                        editOptAnlC.val(tmpAnlArray);

                        let tmpLocArray = [];
                        if (result.offices.length > 0) {
                            result.offices.forEach(function (ofc, index) {
                                tmpLocArray.push(ofc.office_id);
                            });
                        }
                        $('#editOperationLocation').val(tmpLocArray).trigger('change');


                        if (result.surveillances.length > 0) {
                            result.surveillances.forEach(function (surv, index) {
                                $('#editOperationReqSurv' + surv.surveillance_id).prop('checked', true);
                            });
                        }

                        if (result.laboratory_forms[0].analyzes.length > 0) {
                            result.laboratory_forms[0].analyzes.forEach(function (anl, index) {
                                $('#editOperationReqAnl' + anl.analysis_id).prop('checked', true).trigger('change');
                                $('#editOperationReqSpec' + anl.analysis_id).val(urldecode(anl.pivot.spec_info));
                            });
                        }

                        editOptSurvT.selectpicker('refresh');
                        editOptProcT.selectpicker('refresh');
                        editOptAnlC.selectpicker('refresh');
                        editOptCust.selectpicker('refresh');


                    }


                }
            });

        });

        //Cancel Modal ( Edit Operation )
        $('.closeEditOperationModal').click(function () {
            $('#cancelWarnEdit').modal('show');
        });

        //Confirm Warning ( Edit Operation )
        $('.confirmWarnEdit').click(function () {
            var $newMdl = $('#editDetailedModalWaiting');
            $('#cancelWarnEdit').modal('hide');
            $newMdl.modal('hide');
            var $submitBtn = $("#editOperationWaitingForm").find(':submit');
            var $cancelBtn = $('.closeEditOperationModal');
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

            editOptCust.val(null);
            editOptNmCust.val(null);
            editOptSurvT.val(null);
            editOptProcT.val(null);
            editOptAnlC.val(null);
            editOptCust.selectpicker('refresh');
            editOptNmCust.selectpicker('refresh');
            editOptSurvT.selectpicker('refresh');
            editOptProcT.selectpicker('refresh');
            editOptAnlC.selectpicker('refresh');
            $('#editOperationLocation').val(null).trigger('change');

            $('#editOperationBuyer').val('').text('');
            $('#editOperationSeller').val('').text('');
            $('#editOperationSupplier').val('').text('');
            $('#editOperationVessel').val('').text('');
            $('#editOperationGoods').val('').text('');
            $('#editOperationAmount').val('').text('');

            $('input[name^="editOperationReqSpec"]').val('').text('');

            $('#reqSurvEditCheckboxes :checkbox:checked').each(function () {
                $(this).prop('checked', false);
            });

            $('#reqAnlEditCheckboxes :checkbox:checked').each(function () {
                $(this).prop('checked', false);
                $(this).trigger('change');
            });

        });

        //Accept Process ( Edit Operation )
        $('#editOperationWaitingForm').submit(function () {
            if ($('#reqSurvEditCheckboxes :checkbox:checked').length <= 0) {
                alert("Please select at least one surveillance");
                return false;
            }
            if ($('#reqAnlEditCheckboxes :checkbox:checked').length <= 0) {
                alert("Please select at least one analysis");
                return false;
            }
            var $submitBtn = $(this).find(':submit');
            var $cancelBtn = $('.closeEditOperationModal');
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
        //END EDIT OPERATION WAITING

        //BEGIN SHOW EXPENSES
        $('.showOperationExpenses').on('click', function () {
            var $row = $(this).closest("tr");
            $('#operationExpenseOptHeader').text($(this).prop('id'));
            var customerText = $row.find('.activeCustomer').text();
            $('#operationExpenseCustomerHeader').text(customerText.substring(0, customerText.indexOf('(')));

            var postData = {selectedOptShowExpenses: $(this).prop('id')};
            $.ajax({
                type: 'POST',
                url: 'officer_operations.php',
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

        //BEGIN INFORM CUSTOMER
        //Filling Modal ( Inform Customer )
        $('.infCustButtonActive').on('click', function () {
            $('#informCustomerOptID').val($(this).prop('id'));
            var $row = $(this).closest("tr");
            $('#optInfCA').text("#" + $(this).prop('id'));
            var customerText = $row.find('.activeCustomer').text();
            $('#custInfCA').text(customerText.substring(0, customerText.indexOf('(')));

            var officeString = "";
            var processTypesString = "";

            var postDataEEE = {selectedOperationCustomerA: $(this).prop('id')};
            $.ajax({
                type: 'POST',
                url: 'officer_operations.php',
                data: postDataEEE,
                dataType: 'text',
                success: function (resultData) {

                    var result = JSON.parse(resultData);

                    if (result.length > 0) {

                        $('#informCustomerInformations').text('( ' + result[0].customer.contact_person_title + ' ) ' + result[0].customer.contact_person_name + ' ' + result[0].customer.contact_person_surname);
                        $('#informCustomerContact').val(result[0].customer.contact_person_mail);

                        result[0].offices.forEach(function (office, index) {

                            if (result[0].offices.length === 1 || index === result[0].offices.length - 1) {
                                officeString += office.office_name;
                            } else {
                                officeString += (office.office_name + ", ");
                            }

                        });

                        result[0].process_types.forEach(function (processType, index) {

                            if (result[0].process_types.length === 1 || index === result[0].process_types.length - 1) {
                                processTypesString += processType.process_type_name;
                            } else {
                                processTypesString += (processType.process_type_name + ", ");
                            }

                        });

                        $('#informCustomerMessage').val('Country: ' + result[0].location.location_name + ' Offices: ' + officeString + ' Process Types: ' + processTypesString + ' Vessel Name: ' + result[0].vessel_name + ' Tonnage: ......');

                    }
                }
            });

        });
        //Cancel Modal ( Inform Customer )
        $('.closeInformCustomerModal').click(function () {
            $('#cancelWarnInformCust').modal('show');
        });
        //Confirm Warning ( Inform Customer )
        $('.confirmWarnInformCustomer').click(function () {
            var $newMdl = $('#informCustomerModalActive');
            $('#cancelWarnInformCust').modal('hide');
            $newMdl.modal('hide');
            var $submitBtn = $("#informCustomerForm").find(':submit');
            var $cancelBtn = $('.closeInformCustomerModal');
            if ($submitBtn.hasClass("btn-success")) {
                $submitBtn.val("1");
                $submitBtn.removeClass("btn-success");
                $submitBtn.addClass("btn-primary");
                $submitBtn.text("Send Mail");
            }
            if ($cancelBtn.hasClass("btn-danger")) {
                $cancelBtn.removeClass("btn-danger");
                $cancelBtn.addClass("btn-secondary");
                $cancelBtn.text("Close");
            }

            $('#informCustomerOptID').val('').text('');
            $('#informCustomerInformations').text('');
            $('#informCustomerMessage').val('').text('');
        });
        //Accept Process ( Inform Customer )
        $('#informCustomerForm').submit(function () {
            var $submitBtn = $(this).find(':submit');
            var $cancelBtn = $('.closeInformCustomerModal');
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
        //END INFORM CUSTOMER

        //BEGIN FINISH OPERATION
        //Filling Modal ( Finish Operation )
        $('.finOptButtonActive').on('click', function () {
            $('#finishOperationID').val($(this).prop('id'));
            var $row = $(this).closest("tr");
            $('#optFinOA').text("#" + $(this).prop('id'));
            var customerText = $row.find('.activeCustomer').text();
            $('#custFinOA').text(customerText.substring(0, customerText.indexOf('(')));

            var completeButton = $('.confirmCompleteOptButton');
            var anywayButton = $('.finishAnywayButton');
            anywayButton.attr('id', $(this).prop('id'));

            var survMessagePart = $('#finOptSurvMessage');
            var labMessagePart = $('#finOptLabMessage');

            survMessagePart.empty();
            labMessagePart.empty();

            var survMessageUserString = "";
            var labMessageUserString = "";

            var survFormWarn = false;
            var labFormWarn = false;

            var postDataEE = {selectedOperationDeptSA: $(this).prop('id')};
            $.ajax({
                type: 'POST',
                url: 'officer_operations.php',
                data: postDataEE,
                dataType: 'text',
                success: function (resultData) {

                    var result = JSON.parse(resultData);

                    if (result.length > 0) {

                        $('#finOptSurvCount').text('( ' + result[0].surv_completed_count + ' / ' + result[0].surveillance_forms_count + ' )');
                        $('#finOptLabCount').text('( ' + result[0].lab_completed_count + ' / ' + result[0].laboratory_forms_count + ' )');


                        //Listing surveillance info forms user(officer) by user(officer)
                        if (result[0].surveillance_forms_for_dept_status.length > 0) {

                            result[0].surveillance_forms_for_dept_status.forEach(function (survForm, index) {

                                if (survForm.is_completed === 0) {
                                    survFormWarn = true;
                                    if (result[0].surveillance_forms_for_dept_status.length === 1 || index === result[0].surveillance_forms_for_dept_status.length - 1) {
                                        survMessageUserString += (survForm.user.name + " " + survForm.user.surname);
                                    } else {
                                        survMessageUserString += (survForm.user.name + " " + survForm.user.surname + ", ");
                                    }
                                }

                            });

                        }

                        //Listing laboratory forms user(laborant) by user(laborant)
                        if (result[0].laboratory_forms_for_dept_status.length > 0) {

                            result[0].laboratory_forms_for_dept_status.forEach(function (labForm, index) {

                                if (labForm.is_completed === 0) {
                                    labFormWarn = true;
                                    if (result[0].laboratory_forms_for_dept_status.length === 1 || index === result[0].laboratory_forms_for_dept_status.length - 1) {
                                        labMessageUserString += ("Laboratory Form: #" + labForm.lab_form_id);
                                    } else {
                                        labMessageUserString += ("Laboratory Form: #" + labForm.lab_form_id + ", ");
                                    }
                                }

                            });

                        }

                        if (survFormWarn === true) {

                            survMessagePart.append($('<div>').prop('class', 'alert alert-danger deptStatusAlert').prop('role', 'alert').text(survMessageUserString + " user(s) not complete their surveillance information form, you cannot complete operation now."));

                        } else {

                            survMessagePart.append($('<div>').prop('class', 'alert alert-success deptStatusAlert').prop('role', 'alert').text("All users complete their surveillance forms, you can complete operation."));

                        }

                        if (labFormWarn === true) {

                            labMessagePart.append($('<div>').prop('class', 'alert alert-danger deptStatusAlert').prop('role', 'alert').text(labMessageUserString + " not completed yet, you cannot complete operation now."));

                        } else {

                            labMessagePart.append($('<div>').prop('class', 'alert alert-success deptStatusAlert').prop('role', 'alert').text("All users complete their laboratory forms, you can complete operation."));

                        }

                        if (survFormWarn === true || labFormWarn === true) {
                            if (!completeButton.is(':disabled')) {
                                completeButton.prop('disabled', true);
                            }
                            if (anywayButton.is(':disabled')) {
                                anywayButton.prop('disabled', false);
                            }
                        } else {
                            if (completeButton.is(':disabled')) {
                                completeButton.prop('disabled', false);
                            }
                            if (!anywayButton.is(':disabled')) {
                                anywayButton.prop('disabled', true);
                            }
                        }

                    }

                }
            });

        });
        //Cancel Modal ( Finish Operation )
        $('.closeFinishOperationModal').click(function () {
            $('#cancelWarnFinish').modal('show');
        });
        //Confirm Warning ( Finish Operation )
        $('.confirmWarnFinish').click(function () {
            var $newMdl = $('#finishOperationModalActive');
            $('#cancelWarnFinish').modal('hide');
            $newMdl.modal('hide');
            var $submitBtn = $("#finishOperationForm").find(':submit');
            var $cancelBtn = $('.closeFinishOperationModal');
            if ($submitBtn.hasClass("btn-success")) {
                $submitBtn.val("1");
                $submitBtn.removeClass("btn-success");
                $submitBtn.addClass("btn-primary");
                $submitBtn.text("Complete Operation");
            }
            if ($cancelBtn.hasClass("btn-danger")) {
                $cancelBtn.removeClass("btn-danger");
                $cancelBtn.addClass("btn-secondary");
                $cancelBtn.text("Close");
            }

            $('#finishOperationID').val('').text('');
        });
        //Accept Process ( Finish Operation )
        $('#finishOperationForm').submit(function () {
            var $submitBtn = $(this).find(':submit');
            var $cancelBtn = $('.closeFinishOperationModal');
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
        //END FINISH OPERATION

        //BEGIN CANCEL OPERATION
        //Filling Modal ( Cancel Operation )
        $('.cancelOperationButtonWaiting').on('click', function () {
            $('#cancelOperationID').val($(this).prop('id'));
            $('#cancelOperationWarning').text('Are you sure you want to cancel the operation #' + $(this).prop('id') + ' ?');
        });
        //Cancel Modal ( Cancel Operation )
        $('.closeCancelOperationModal').click(function () {
            $('#cancelWarnCancel').modal('show');
        });
        //Confirm Warning ( Cancel Operation )
        $('.confirmWarnCancel').click(function () {
            var $newMdl = $('#warnCancelW');
            $('#cancelWarnCancel').modal('hide');
            $newMdl.modal('hide');
            var $submitBtn = $("#cancelOperationForm").find(':submit');
            var $cancelBtn = $('.closeCancelOperationModal');
            if ($submitBtn.hasClass("btn-success")) {
                $submitBtn.val("1");
                $submitBtn.removeClass("btn-success");
                $submitBtn.addClass("btn-primary");
                $submitBtn.text("Cancel Operation");
            }
            if ($cancelBtn.hasClass("btn-danger")) {
                $cancelBtn.removeClass("btn-danger");
                $cancelBtn.addClass("btn-secondary");
                $cancelBtn.text("Close");
            }

            $('#cancelOperationID').val('').text('');
            $('#cancelOperationWarning').val('').text('');
            $('#cancelOperationExplanation').val('').text('');
        });
        //Accept Process ( Cancel Operation )
        $('#cancelOperationForm').submit(function () {
            var $submitBtn = $(this).find(':submit');
            var $cancelBtn = $('.closeCancelOperationModal');
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
        //END CANCEL OPERATION

        //BEGIN SHOW NOTES
        $('.showOperationNotes').on('click', function () {
            var $row = $(this).closest("tr");
            $('#operationNotesOptHeader').text($(this).prop('id'));
            var customerText = $row.find('.activeCustomer').text();
            $('#operationNotesCustomerHeader').text(customerText.substring(0, customerText.indexOf('(')));

            var postData = {selectedOperationShowNotes: $(this).prop('id')};
            $.ajax({
                type: 'POST',
                url: 'officer_operations.php',
                data: postData,
                dataType: 'text',
                success: function (resultData) {

                    var result = JSON.parse(resultData);

                    if (result.hasOwnProperty('error')) {
                        //Make error message
                        alert("OOPS");
                    }
                    if (result.hasOwnProperty('operation_id')) {

                        if (result.operation_notes_for_officer.length > 0) {

                            var notesString = "";

                            result.operation_notes_for_officer.forEach(function (note, index) {

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
            $('#operationNote').val('');
            $('#addOperationNoteTypeOne').prop('checked', false);
            $('#addOperationNoteTypeTwo').prop('checked', false);
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
                url: 'officer_operations.php',
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

                        if (result.laboratory_forms[0].analyzes.length > 0) {

                            result.laboratory_forms[0].analyzes.forEach(function (anl, index) {

                                var anlDiv = $('<div>').prop('class', 'col-lg-4');
                                var anlCustomDiv = $('<div>').prop('class', 'custom-control custom-checkbox');
                                var anlInput = $('<input>').prop('type', 'checkbox').prop('class', 'custom-control-input').prop('id', 'reqAnlDetW' + anl.analysis_id).prop('checked', true).prop('disabled', true);
                                let specString = "";
                                if (anl.pivot.spec_info === null) {
                                    specString = "No Information";
                                } else {
                                    specString = urldecode(anl.pivot.spec_info);
                                }
                                var anlLabel = $('<label>').prop('class', 'custom-control-label').prop('for', 'reqSurvDetW' + anl.analysis_id).css('white-space', 'pre-wrap').text(anl.analysis_name + "\n (Spec: " + specString + ") ");

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

        //BEGIN VIEW DETAILED ACTIVE
        $('.viewDetailButtonActive').on('click', function () {
            var $row = $(this).closest("tr");
            $('#optViewDA').text("#" + $(this).prop('id'));
            $('#vesselViewDA').val($row.find('.activeVessel').text());
            $('#goodsViewDA').val($row.find('.activeGoods').text());
            $('#amountViewDA').val($row.find('.activeAmount').text());
            $('#officerViewDA').val($row.find('.activeOfficer').text());
            $('#dateViewDA').val($row.find('.activeDate').text());

            var optCust = $('#custViewDA');
            optCust.text('');
            var optNomCust = $('#custNomViewDA');
            optNomCust.text('');
            var optBuyer = $('#buyerViewDA');
            optBuyer.text('');
            var optSeller = $('#sellerViewDA');
            optSeller.text('');
            var optSupplier = $('#supplierViewDA');
            optSupplier.text('');

            var requestedSurvList = $('#reqSurvViewDA');
            requestedSurvList.empty();
            var requestedAnlList = $('#reqAnlViewDA');
            requestedAnlList.empty();
            var survFormList = $('#survFormViewDA');
            survFormList.empty();
            var labFormList = $('#labFormViewDA');
            labFormList.empty();

            var survTypeList = $('#survTypeViewDA');
            survTypeList.text('');
            var procTypeList = $('#procTypeViewDA');
            procTypeList.text('');
            var anlTypeList = $('#anlCondViewDA');
            anlTypeList.text('');

            var postDataAA = {selectedOperationDetA: $(this).prop('id')};
            $.ajax({
                type: 'POST',
                url: 'officer_operations.php',
                data: postDataAA,
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

                            $('#ilocViewDA').val(officesString);
                        }

                        if (result.surveillances.length > 0) {

                            result.surveillances.forEach(function (surv, index) {

                                var survDiv = $('<div>').prop('class', 'col-lg-4');
                                var survCustomDiv = $('<div>').prop('class', 'custom-control custom-checkbox');
                                var survInput = $('<input>').prop('type', 'checkbox').prop('class', 'custom-control-input').prop('id', 'reqSurvDetA' + surv.surveillance_id).prop('checked', true).prop('disabled', true);
                                var survLabel = $('<label>').prop('class', 'custom-control-label').prop('for', 'reqSurvDetA' + surv.surveillance_id).text(surv.surveillance_name);

                                requestedSurvList.append(survDiv.append(survCustomDiv.append(survInput).append(survLabel)));

                            });

                        } else {
                            var thereIsNoSurvMessage = $('<div>').prop('class', 'text-center font-italic').text('There is no requested surveillances.');
                            requestedSurvList.append(thereIsNoSurvMessage);
                        }

                        if (result.laboratory_forms.length > 0) {
                            result.laboratory_forms.forEach(function (lf, index) {
                                if (lf.analyzes.length > 0) {
                                    let formType = "";
                                    if (lf.is_extra === 1) {
                                        formType = "(Additional instruction)";
                                    }
                                    lf.analyzes.forEach(function (anl, index) {
                                        var anlDiv = $('<div>').prop('class', 'col-lg-4');
                                        var anlCustomDiv = $('<div>').prop('class', 'custom-control custom-checkbox');
                                        var anlInput = $('<input>').prop('type', 'checkbox').prop('class', 'custom-control-input').prop('id', 'reqAnlDetA' + anl.analysis_id).prop('checked', true).prop('disabled', true);

                                        let specString = "";
                                        if (anl.pivot.spec_info === null) {
                                            specString = "No Information";
                                        } else {
                                            specString = urldecode(anl.pivot.spec_info);
                                        }

                                        var anlLabel = $('<label>').prop('class', 'custom-control-label').prop('for', 'reqSurvDetA' + anl.analysis_id).css('white-space', 'pre-wrap').text(anl.analysis_name + " " + formType + "\n (Spec: " + specString + ") ");

                                        requestedAnlList.append(anlDiv.append(anlCustomDiv.append(anlInput).append(anlLabel)));
                                    });
                                }
                            });
                        }

                        if (result.surveillance_forms.length > 0) {

                            result.surveillance_forms.forEach(function (srvF, index) {

                                var srvFRowDiv = $('<div>').prop('class', 'row mt-1');
                                var srvFNameDiv = $('<div>').prop('class', 'col-lg-6 col-md-6 font-weight-bold').text(srvF.user.name + ' ' + srvF.user.surname);
                                var srvFButtonDiv = $('<div>').prop('class', 'col-lg-6 col-md-6');
                                var srvFButton = $('<a>').prop('role', 'button').prop('class', 'btn btn-primary btn-sm').prop('target', '_blank').prop('href', 'surveillance_info_form.php?singleSurveillanceFormID=' + srvF.info_form_id).text('View Form ');
                                var srvFIcon = $('<i>').prop('class', 'far fa-clipboard');

                                survFormList.append(srvFRowDiv.append(srvFNameDiv).append(srvFButtonDiv.append(srvFButton.append(srvFIcon))));
                            });

                        } else {
                            var thereIsNoSurvFormsMessage = $('<div>').prop('class', 'text-center font-italic').text('There is no surveillance information forms.');
                            survFormList.append(thereIsNoSurvFormsMessage);
                        }


                        if (result.laboratory_forms.length > 0) {

                            result.laboratory_forms.forEach(function (labF, index) {

                                let formType = "";
                                if (labF.is_extra === 1) {
                                    formType = "(Additional instruction)";
                                }


                                var labFRowDiv = $('<div>').prop('class', 'row mt-1');
                                var labFNameDiv = $('<div>').prop('class', 'col-lg-6 col-md-6 font-weight-bold').text("Laboratory Form #" + labF.lab_form_id + " " + formType);
                                var labFButtonDiv = $('<div>').prop('class', 'col-lg-6 col-md-6');
                                var labFButton = $('<button>').prop('type', 'button').prop('class', 'btn btn-primary btn-sm').text('View Form ');
                                var labFIcon = $('<i>').prop('class', 'far fa-clipboard');

                                labFormList.append(labFRowDiv.append(labFNameDiv).append(labFButtonDiv.append(labFButton.append(labFIcon))));

                            });

                        } else {
                            var thereIsNoLabFormsMessage = $('<div>').prop('class', 'text-center font-italic').text('There is no laboratory forms.');
                            labFormList.append(thereIsNoLabFormsMessage);
                        }

                    }

                }
            });

        });
        //END VIEW DETAILED ACTIVE

        //BEGIN DEPARTMENT STATUSES
        $('.deptStatusButtonActive').on('click', function () {
            var clickedOptID = $(this).prop('id');
            var $row = $(this).closest("tr");
            $('#optDeptSA').text("#" + clickedOptID);
            var customerText = $row.find('.activeCustomer').text();
            $('#custDeptSA').text(customerText.substring(0, customerText.indexOf('(')));

            var deptSurvList = $('#deptStatusSurvList');
            var deptLabList = $('#deptStatusLabList');

            deptSurvList.empty();
            deptLabList.empty();

            var postDataE = {selectedOperationDeptSA: clickedOptID};
            $.ajax({
                type: 'POST',
                url: 'officer_operations.php',
                data: postDataE,
                dataType: 'text',
                success: function (resultData) {

                    var result = JSON.parse(resultData);

                    if (result.length > 0) {

                        $('#deptStatusSurvCount').text('( ' + result[0].surv_completed_count + ' / ' + result[0].surveillance_forms_count + ' )');
                        $('#deptStatusLabCount').text('( ' + result[0].lab_completed_count + ' / ' + result[0].laboratory_forms_count + ' )');


                        //Listing surveillance info forms user(officer) by user(officer)
                        if (result[0].surveillance_forms_for_dept_status.length > 0) {

                            result[0].surveillance_forms_for_dept_status.forEach(function (survForm, index) {

                                var survRowDiv = $('<div>').prop('class', 'row deptStatusRow');
                                var survUserColDiv = $('<div>').prop('class', 'col-lg-6 col-md-6').text(survForm.user.name + ' ' + survForm.user.surname);
                                var survCountColDiv = $('<div>').prop('class', 'col-lg-6 col-md-6');
                                var survAlertDiv = $('<div>').prop('class', 'alert deptStatusAlert text-center');
                                if (survForm.is_completed === 1) {
                                    survAlertDiv.addClass('alert-success');
                                    survAlertDiv.text('Completed');
                                } else {
                                    survAlertDiv.addClass('alert-danger');
                                    survAlertDiv.text('Not Yet');
                                }

                                deptSurvList.append(survRowDiv.append(survUserColDiv).append(survCountColDiv.append(survAlertDiv)));

                            });

                        }

                        //Listing laboratory forms user(laborant) by user(laborant)
                        if (result[0].laboratory_forms_for_dept_status.length > 0) {

                            result[0].laboratory_forms_for_dept_status.forEach(function (labForm, index) {

                                let formType = "";
                                if (labForm.is_extra === 1) {
                                    formType = "(Additional instruction)";
                                }

                                var labRowDiv = $('<div>').prop('class', 'row deptStatusRow');
                                var labUserColDiv = $('<div>').prop('class', 'col-lg-6 col-md-6').text("Laboratory Form: #" + labForm.lab_form_id + " " + formType);
                                var labCountColDiv = $('<div>').prop('class', 'col-lg-6 col-md-6');
                                var labAlertDiv = $('<div>').prop('class', 'alert deptStatusAlert text-center');
                                if (labForm.is_completed === 1) {
                                    labAlertDiv.addClass('alert-success');
                                    labAlertDiv.text('Completed');
                                } else {
                                    labAlertDiv.addClass('alert-danger');
                                    labAlertDiv.text('Not Yet');
                                }

                                deptLabList.append(labRowDiv.append(labUserColDiv).append(labCountColDiv.append(labAlertDiv)));

                            });

                        }


                    }

                }
            });

        });
        //END DEPARTMENT STATUSES

        //BEGIN NEW OPERATION
        //Filling Modal ( New Operation )
        $('.newOperationButton').on('click', function () {
            $('#newOperationFormDate').setNow();
        });

        //Cancel Modal ( New Operation )
        $('.closeNewOperationModal').click(function () {
            $('#cancelWarnNew').modal('show');
        });

        //Confirm Warning ( New Operation )
        $('.confirmWarnNew').click(function () {
            var $newMdl = $('#newOperationModal');
            $('#cancelWarnNew').modal('hide');
            $newMdl.modal('hide');
            var $submitBtn = $("#newOperationForm").find(':submit');
            var $cancelBtn = $('.closeNewOperationModal');
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

            newOptCust.val(null);
            newOptNmCust.val(null);
            newOptSurvT.val(null);
            newOptProcT.val(null);
            newOptAnlC.val(null);
            newOptCust.selectpicker('refresh');
            newOptNmCust.selectpicker('refresh');
            newOptSurvT.selectpicker('refresh');
            newOptProcT.selectpicker('refresh');
            newOptAnlC.selectpicker('refresh');
            $('#newOperationLocation').val(null).trigger('change');

            $('#newOperationBuyer').val('').text('');
            $('#newOperationSeller').val('').text('');
            $('#newOperationSupplier').val('').text('');
            $('#newOperationVessel').val('').text('');
            $('#newOperationGoods').val('').text('');
            $('#newOperationAmount').val('').text('');

            $('input[name^="newOperationReqSpec"]').val('').text('');

            $('#reqSurvCheckboxes :checkbox:checked').each(function () {
                $(this).prop('checked', false);
            });

            $('#reqAnlCheckboxes :checkbox:checked').each(function () {
                $(this).prop('checked', false);
                $(this).trigger('change');
            });

        });

        //Accept Process ( New Operation )
        $('#newOperationForm').submit(function () {
            if ($('#reqSurvCheckboxes :checkbox:checked').length <= 0) {
                alert("Please select at least one surveillance");
                return false;
            }
            if ($('#reqAnlCheckboxes :checkbox:checked').length <= 0) {
                alert("Please select at least one analysis");
                return false;
            }
            var $submitBtn = $(this).find(':submit');
            var $cancelBtn = $('.closeNewOperationModal');
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
        //END NEW OPERATION

        //BEGIN FINISH ANYWAY
        //For Binding OperationID To Modal ( Finish Anyway Operation )
        $('#warnAnyway').on('show.bs.modal', function (e) {
            $invokerLinkIDForCancel = $(e.relatedTarget).attr('id');
        });

        //Confirm Anyway ( Finish Anyway Operation )
        $('.confirmWarnAnyway').click(function () {
            var postDatass = {anywayOperationID: $invokerLinkIDForCancel};
            $.ajax({
                type: 'POST',
                url: 'officer_operations.php',
                data: postDatass,
                dataType: 'text',
                success: function (resultData) {
                    var result = JSON.parse(resultData);

                    if (result.hasOwnProperty('success')) {
                        createCookie('successfulAnywayOperation', result.success, 1);
                        location.reload(true);
                    }

                }
            });
        });
        //END FINISH ANYWAY

        //BEGIN REMOVE COOKIES
        $('.removeCookieClass').click(function () {
            var cookieID = $(this).prop('id');
            eraseCookie(cookieID);
        });
        //END REMOVE COOKIES

        $('.reqAnlCheckboxClass').on('change', function () {
            let clickedID = $(this).val();
            let thatInput = $('#newOperationReqSpec' + clickedID);
            if (thatInput.is(':disabled')) {
                thatInput.prop('disabled', false);
            } else {
                thatInput.prop('disabled', true);
            }
        });

        $('.reqAnlEditCheckboxClass').on('change', function () {
            let clickedID = $(this).val();
            let thatInput = $('#editOperationReqSpec' + clickedID);
            if (thatInput.is(':disabled')) {
                thatInput.prop('disabled', false);
            } else {
                thatInput.prop('disabled', true);
            }
        });

    });

    //System JS Functions

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
        if (whichForm.prop('id') === "enterAnalysisModal") {
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

    function eraseCookie(name) {
        createCookie(name, "", -1);
    }

    function urldecode(str) {
        return decodeURIComponent((str + '').replace(/\+/g, '%20'));
    }

    function universalDate(trDate) {
        var dateArray = trDate.split(" ");
        var datePartArray = dateArray[0].split("-");
        var timePartArray = dateArray[1].split(":");

        return new Date(datePartArray[2], datePartArray[1] - 1, datePartArray[0], timePartArray[0], timePartArray[1], timePartArray[2], 0);
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
</body>
</html>