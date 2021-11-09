<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 14.02.2019
 * Time: 22:30
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
    case "3":
        header('Location: field_operation_page.php');
        break;
    case "7":
        header('Location: sample_tracking_page.php');
        break;
    case "8":
        header('Location: certificates.php');
        break;
}

$apiCaller = new ApiCaller('1', $_SESSION['token']);

$waitingForms = $apiCaller->sendRequest(array(
    'api_url' => 'https://lumen.krekpot.com/api/v1/getLaboratoryForms/1',
    'api_method' => 'get',
));
$activeForms = $apiCaller->sendRequest(array(
    'api_url' => 'https://lumen.krekpot.com/api/v1/getLaboratoryForms/2',
    'api_method' => 'get',
));
$completedForms = $apiCaller->sendRequest(array(
    'api_url' => 'https://lumen.krekpot.com/api/v1/getLaboratoryForms/3',
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
        <h4>Laboratory Analysis</h4>
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
            <ul class="nav nav-pills" style="display: inline-flex;" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="pill" href="#operational">
                        Operational Analysis</a>
                </li>
            </ul>
        </div>
        <div class="card-body" style="padding-top: 0;">
            <!-- Tab panes -->
            <div class="tab-content">
                <div id="operational" class="container tab-pane active"><br>
                    <ul class="nav nav-tabs" role="tablist">

                        <li class="nav-item">
                            <a id="waitingTabLink" class="nav-link active" data-toggle="tab" href="#waiting">Waiting
                                List <span
                                        class="badge badge-primary"><?php echo $waitingForms['data']->count;
                                    unset($waitingForms['data']->count); ?></span></a>
                        </li>
                        <li class="nav-item">
                            <a id="activeTabLink" class="nav-link" data-toggle="tab" href="#active">Active List <span
                                        class="badge badge-success"><?php echo $activeForms['data']->count;
                                    unset($activeForms['data']->count); ?></span></a>
                        </li>
                        <li class="nav-item">
                            <a id="completedTabLink" class="nav-link" data-toggle="tab" href="#completed">Completed List
                                <span
                                        class="badge badge-secondary"><?php echo $completedForms['data']->count;
                                    unset($completedForms['data']->count); ?></span></a>
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
                                        <th onclick="sortTable(1,'waitingTable','int')">Operation ID</th>
                                        <th onclick="sortTable(2,'waitingTable','date')">Date</th>
                                        <th onclick="sortTable(3,'waitingTable','str')">Goods</th>
                                        <th onclick="sortTable(4,'waitingTable','int')">Amount</th>
                                        <th onclick="sortTable(5,'waitingTable','str')">Officer</th>
                                        <th>Processes</th>
                                    </tr>
                                    </thead>
                                    <tbody id="myTableWaiting">
                                    <?php foreach ($waitingForms['data'] as $frm) { ?>
                                        <tr>
                                            <td class="waitingOperationID"><?php echo $frm->lab_form_id; ?></td>
                                            <td class="waitingCustomer"><?php echo "GSI" . $frm->operation->operation_id; ?></td>
                                            <td class="waitingDate"><?php echo date("d-m-Y H:i", strtotime($frm->created_at)); ?></td>
                                            <td class="waitingGoods"><?php echo $frm->operation->type_of_goods; ?></td>
                                            <td class="waitingAmount"><?php echo $frm->operation->amount; ?></td>
                                            <td class="waitingOfficer"><?php echo $frm->creator->name . " " . $frm->creator->surname; ?></td>
                                            <td>
                                                <div class="btn-group dropdown">
                                                    <button type="button" class="btn btn-primary dropdown-toggle btn-sm"
                                                            data-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                        Menu
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item viewDetailedButtonWaiting"
                                                           data-toggle="modal"
                                                           data-target="#viewDetailedModalWaiting"
                                                           data-backdrop="static"
                                                           data-keyboard="false"
                                                           href="#" id="<?php echo $frm->lab_form_id; ?>">View
                                                            Detailed</a>
                                                        <a class="dropdown-item acceptFormButtonWaiting"
                                                           data-toggle="modal"
                                                           data-target="#warnAcceptW" data-backdrop="static"
                                                           data-keyboard="false"
                                                           id="<?php echo $frm->lab_form_id; ?>" href="#">Accept
                                                            Operation</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php }
                                    if (empty((array)$waitingForms['data'])) {
                                        ?>
                                        <tr>
                                            <td colspan="8" class="text-center">
                                                <p><strong>There is no waiting forms.</strong></p>
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
                                        <th onclick="sortTable(1,'activeTable','int')">Operation ID</th>
                                        <th onclick="sortTable(2,'activeTable','date')">Date</th>
                                        <th onclick="sortTable(3,'activeTable','str')">Goods</th>
                                        <th onclick="sortTable(4,'activeTable','int')">Amount</th>
                                        <th onclick="sortTable(5,'activeTable','str')">Officer</th>
                                        <th>Processes</th>
                                    </tr>
                                    </thead>
                                    <tbody id="myTableActive">
                                    <?php foreach ($activeForms['data'] as $frm) { ?>
                                        <tr>
                                            <td class="activeFormID"><?php echo $frm->lab_form_id; ?></td>
                                            <td class="activeOperationID"><?php echo "GSI" . $frm->operation->operation_id; ?></td>
                                            <td class="activeDate"><?php echo date("d-m-Y H:i", strtotime($frm->created_at)); ?></td>
                                            <td class="activeGoods"><?php echo urldecode($frm->operation->type_of_goods); ?></td>
                                            <td class="activeAmount"><?php echo $frm->operation->amount; ?></td>
                                            <td class="activeOfficer"><?php echo urldecode($frm->creator->name . " " . $frm->creator->surname); ?></td>
                                            <td>
                                                <div class="btn-group dropdown">
                                                    <button type="button" class="btn btn-primary dropdown-toggle btn-sm"
                                                            data-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                        Menu
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <?php if (!isset($frm->isOwner)) { ?>
                                                            <a class="dropdown-item joinFormButtonActive"
                                                               data-toggle="modal"
                                                               data-target="#warnJoinA" data-backdrop="static"
                                                               data-keyboard="false"
                                                               id="<?php echo $frm->lab_form_id; ?>" href="#">Join
                                                                Form</a>
                                                        <?php } ?>
                                                        <?php if (isset($frm->isOwner)) { ?>
                                                            <a class="dropdown-item viewDetailedButtonActive">View
                                                                Laboratory
                                                                Form</a>
                                                            <a class="dropdown-item showOperationLabNotes"
                                                               data-toggle="modal"
                                                               data-target="#viewOperationLabNotesModal"
                                                               data-backdrop="static"
                                                               data-keyboard="false"
                                                               href="#"
                                                               id="<?php echo $frm->operation->operation_id; ?>">Show
                                                                Notes</a>
                                                            <a class="dropdown-item addNewOperationLabNoteButton"
                                                               data-toggle="modal"
                                                               data-target="#addNewOperationLabNoteModal"
                                                               data-backdrop="static"
                                                               data-keyboard="false"
                                                               href="#"
                                                               id="<?php echo $frm->operation->operation_id; ?>">Add
                                                                Note</a>
                                                            <a class="dropdown-item enterAnalysisButtonActive"
                                                               data-toggle="modal"
                                                               data-target="#enterAnalysisModal"
                                                               data-backdrop="static"
                                                               data-keyboard="false"
                                                               href="#" id="<?php echo $frm->lab_form_id; ?>">Enter
                                                                Analysis</a>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php }
                                    if (empty((array)$activeForms['data'])) {
                                        ?>
                                        <tr>
                                            <td colspan="11" class="text-center">
                                                <p><strong>There is no active form.</strong></p>
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
                                        <th onclick="sortTable(1,'completedTable','int')">Operation ID</th>
                                        <th onclick="sortTable(2,'completedTable','date')">Date</th>
                                        <th onclick="sortTable(3,'completedTable','str')">Goods</th>
                                        <th onclick="sortTable(4,'completedTable','int')">Amount</th>
                                        <th onclick="sortTable(5,'completedTable','str')">Officer</th>
                                        <th onclick="sortTable(6,'completedTable','str')">Completer</th>
                                        <th onclick="sortTable(7,'completedTable','str')">Completed Date</th>
                                        <th>Processes</th>
                                    </tr>
                                    </thead>
                                    <tbody id="myTableCompleted">
                                    <?php foreach ($completedForms['data'] as $frm) { ?>
                                        <tr>
                                            <td class="completedOperationID"><?php echo $frm->lab_form_id; ?></td>
                                            <td class="completedCustomer"><?php echo "GSI" . $frm->operation->operation_id; ?></td>
                                            <td class="completedDate"><?php echo date("d-m-Y H:i:s", strtotime($frm->created_at)); ?></td>
                                            <td class="completedGoods"><?php echo $frm->operation->type_of_goods; ?></td>
                                            <td class="completedAmount"><?php echo $frm->operation->amount; ?></td>
                                            <td class="completedOfficer"><?php echo $frm->creator->name . " " . $frm->creator->surname; ?></td>
                                            <td class="completedOfficer"><?php echo $frm->completer->name . " " . $frm->completer->surname; ?></td>
                                            <td class="completedOfficer"><?php echo date("d-m-Y H:i:s", strtotime($frm->completed_date)); ?></td>
                                            <td>
                                                <div class="btn-group dropdown">
                                                    <button type="button" class="btn btn-primary dropdown-toggle btn-sm"
                                                            data-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                        Menu
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item viewDetailedButtonCompleted">View
                                                            Detailed</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php }
                                    if (empty((array)$completedForms['data'])) {
                                        ?>
                                        <tr>
                                            <td colspan="12" class="text-center">
                                                <p><strong>There is no completed form.</strong></p>
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
    </div>

</div>

<!-- Add New Operation Note -->

<div class="modal fade" id="addNewOperationLabNoteModal" tabindex="-1" role="dialog"
     aria-labelledby="addNewOperationLabNoteModalTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addNewOperationLabNoteModalTitle">Add New Operation Note</h5>
            </div>

            <form id="addNewOperationNoteForm" name="addNewOperationNoteForm" action="laborant_operations.php"
                  method="post">

                <input type="hidden" id="addNewOperationLabNoteOptID" name="addNewOperationLabNoteOptID" value="0"/>

                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-3"><h6>Operation: #<strong class="text-muted"
                                                                      id="addNewOperationNoteHeader"></strong></h6>
                        </div>
                    </div>
                    <hr/>


                    <div class="form-group">
                        <label for="operationLabNote" style="font-weight: 500;">Note:</label>
                        <textarea class="form-control" id="operationLabNote"
                                  name="operationLabNote" rows="3" required></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary closeAddNewOperationLabNoteModal">Close</button>
                    <button type="submit" class="btn btn-primary" value="1">Save</button>
                </div>
            </form>


        </div>
    </div>
</div>

<!-- Show Operation Notes Modal -->
<div class="modal fade" id="viewOperationLabNotesModal" tabindex="-1" role="dialog"
     aria-labelledby="viewOperationLabNotesModalTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewOperationLabNotesModalTitle">Operation Laboratory Notes</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-3"><h6>Operation: #<strong class="text-muted"
                                                                  id="operationNotesOptHeader"></strong></h6>
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

<!-- Enter Analysis Modal -->
<div class="modal fade" id="enterAnalysisModal" tabindex="-1" role="dialog"
     aria-labelledby="enterAnalysisModalTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="enterAnalysisModalTitle">Enter New Analysis (Lab Form:<strong
                            id="enterAnlFormInfo"></strong><br/> Operation:
                    <strong id="enterAnlOptInfo" style="font-size: 16px;"></strong></h5>
                <div>
                    <button type="button" class="btn btn-success float-right addMultipleAnalysis"><i
                                class="fas fa-plus-circle"></i> Add Analysis
                    </button>
                    <button type="button" class="btn btn-danger float-right removeMultipleAnalysis" disabled><i
                                class="fas fa-plus-circle"></i> Remove Analysis
                    </button>
                </div>
            </div>
            <form id="enterAnalysisForm" name="enterAnalysisForm" action="laborant_operations.php"
                  method="post">
                <div class="modal-body">
                    <h6>Is Subcontractor</h6>
                    <div class="container">
                        <div class="form-row">
                            <div class="col-3">
                                <div class="form-group">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="isSubcontractor1" name="isSubcontractor"
                                               class="custom-control-input isSubcontractor"
                                               value="1" required>
                                        <label class="custom-control-label"
                                               for="isSubcontractor1">Yes</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="isSubcontractor2" name="isSubcontractor"
                                               class="custom-control-input isSubcontractor"
                                               value="0" required>
                                        <label class="custom-control-label"
                                               for="isSubcontractor2">No</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-9">
                                <div class="row">
                                    <label for="subcontractorName">Laboratory Name:</label>
                                    <input type="text" class="form-control" id="subcontractorName"
                                           name="subcontractorName"
                                           placeholder="If yes, enter subcontractor laboratory name."
                                           disabled required>
                                </div>
                                <div class="row">
                                    <label for="subcontractorSendDate">Send Date:</label>
                                    <input type="datetime-local" class="form-control"
                                           name="subcontractorSendDate"
                                           id="subcontractorSendDate"
                                           disabled required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr/>
                    <div id="accordion">
                        <div class="card">
                            <div class="card-header" id="headingOne">
                                <h5 class="mb-0">
                                    <button type="button" class="btn btn-link" data-toggle="collapse"
                                            data-target="#collapseOne"
                                            aria-expanded="true" aria-controls="collapseOne">
                                        Analysis #1
                                    </button>
                                </h5>
                            </div>

                            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                                 data-parent="#accordion">
                                <div class="card-body">
                                    <input type="hidden" name="enterAnalysisFormID" id="enterAnalysisFormID"/>

                                    <div class="form-row">
                                        <label for="enterAnalysisSample1"><strong>Sample</strong></label>
                                        <select class="form-control selectpicker show-tick" title="Choose one..."
                                                id="enterAnalysisSample1"
                                                name="enterAnalysisSample1" required>
                                        </select>
                                        <p id="noSampleMessage" style="font-size: 12px; color:red;"></p>
                                    </div>
                                    <hr/>

                                    <div class="form-row">
                                        <div class="col-4">
                                            <label for="enterAnalysisCondition1"><strong>Condition</strong></label>
                                            <select class="form-control selectpicker show-tick" title="Choose one..."
                                                    id="enterAnalysisCondition1"
                                                    name="enterAnalysisCondition1" required>
                                            </select>
                                        </div>
                                        <div class="col-4">
                                            <label for="enterAnalysisStandard1"><strong>Standard</strong></label>
                                            <select class="form-control selectpicker show-tick" title="Choose one..."
                                                    id="enterAnalysisStandard1"
                                                    name="enterAnalysisStandard1" required>
                                            </select>
                                        </div>
                                        <div class="col-4">
                                            <label for="enterAnalysisReq1"><strong>Analysis</strong></label>
                                            <select class="form-control selectpicker show-tick" title="Choose one..."
                                                    id="enterAnalysisReq1"
                                                    name="enterAnalysisReq1" required>
                                            </select>
                                            <p id="specMessage" style="font-size: 12px; color:red;"></p>
                                        </div>
                                    </div>
                                    <hr/>

                                    <div class="form-row">
                                        <div class="col-4">
                                            <label for="anlDemandDate1"><strong>Analysis Demand Date:</strong></label>
                                            <input type="datetime-local" class="form-control" name="anlDemandDate1"
                                                   id="anlDemandDate1"
                                                   value="" readonly>
                                        </div>
                                        <div class="col-4">
                                            <label for="anlStartDate1"><strong>Analysis Start Date:</strong></label>
                                            <input type="datetime-local" class="form-control" name="anlStartDate1"
                                                   id="anlStartDate1"
                                                   value="" required>
                                        </div>
                                        <div class="col-4">
                                            <label for="anlEndDate1"><strong>Analysis End Date:</strong></label>
                                            <input type="datetime-local" class="form-control" name="anlEndDate1"
                                                   id="anlEndDate1"
                                                   value="" required>
                                        </div>
                                    </div>
                                    <hr/>

                                    <h6>Result & Remarks</h6>
                                    <hr/>

                                    <div class="form-row">
                                        <div class="col-6">
                                            <label for="anlResult1"><strong>Result:</strong></label>
                                            <input type="text" class="form-control" id="anlResult1"
                                                   name="anlResult1" required>
                                        </div>
                                        <div class="col-6">
                                            <label for="anlRemarks1"><strong>Remarks:</strong></label>
                                            <textarea class="form-control" rows="5" id="anlRemarks1" name="anlRemarks1"
                                                      placeholder="Optional"></textarea>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div id="multiple-part">

                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary closeEnterAnalysisModal">Close</button>
                    <button type="submit" class="btn btn-primary" value="1">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Close Warn For Enter Analysis -->

<div id="cancelWarnEnterAnalysis" class="modal fade" role="dialog" data-backdrop="false">
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
                <button type="button" class="btn btn-danger confirmWarnEnterAnalysis float-right btn-sm">Yes</button>
            </div>
        </div>
    </div>
</div>

<!-- Accept Laboratory Form Modal -->
<div id="warnAcceptW" class="modal fade" role="dialog" data-backdrop="false">
    <div class="modal-dialog modal-dialog-centered">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                Warning
            </div>
            <form id="acceptLabForm" name="acceptLabForm" action="laborant_operations.php" method="post">
                <input type="hidden" name="acceptLabFormID" id="accceptLabFormID"/>
                <div class="modal-body">
                    <p id="acceptFormWarning"></p>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary closeAcceptFormModal">Close</button>
                    <button type="submit" class="btn btn-primary" value="1">Accept Form</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Join Laboratory Form Modal -->
<div id="warnJoinA" class="modal fade" role="dialog" data-backdrop="false">
    <div class="modal-dialog modal-dialog-centered">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                Warning
            </div>
            <form id="joinLabForm" name="joinLabForm" action="laborant_operations.php" method="post">
                <input type="hidden" name="joinLabFormID" id="joinLabFormID"/>
                <div class="modal-body">
                    <p id="joinFormWarning"></p>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary closeJoinFormModal">Close</button>
                    <button type="submit" class="btn btn-primary" value="1">Join Form</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Part For Extra Instruction -->

<div class="modal fade" id="viewDetailedWaitingModal" tabindex="-1" role="dialog"
     aria-labelledby="viewDetailedWaitingModal"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewDetailedWaitingModal">Operation Analysis Details #234</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>

<!-- OPERATIONAL ANALYSIS -->

<!-- Modal Part For Waiting View Detailed -->

<div class="modal fade" id="viewDetailedWaitingModal" tabindex="-1" role="dialog"
     aria-labelledby="viewDetailedWaitingModal"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewDetailedWaitingModal">Operation Analysis Details #234</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>

<!-- Modal Part For Completed View Detailed -->

<div class="modal fade" id="viewDetailedCompletedModal" tabindex="-1" role="dialog"
     aria-labelledby="viewDetailedCompletedModal"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewDetailedCompletedModal">Operation Analysis Details #234</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>


<!-- Modal Part For Edit Analysis Report -->

<div class="modal fade" id="editReportModal" tabindex="-1" role="dialog"
     aria-labelledby="editReportModal"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editReportModal">Operation #234 Analysis Report #1</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>


<!-- EXTERNAL ANALYSIS -->

<!-- New External Analysis Modal -->

<div class="modal fade" id="newExternalAnalysisModal" tabindex="-1" role="dialog"
     aria-labelledby="newExternalAnalysisModal"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newExternalAnalysisModal">Extra Instruction For Operation #1</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>

<!-- Modal Part For Waiting View Detailed -->

<div class="modal fade" id="extraInstructionReport" tabindex="-1" role="dialog"
     aria-labelledby="extraInstructionReport"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="extraInstructionReport">Extra Instruction For Operation #1</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>

<!-- Modal Part For Completed View Detailed -->

<div class="modal fade" id="viewDetailedCompletedExtModal" tabindex="-1" role="dialog"
     aria-labelledby="viewDetailedCompletedExtModal"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewDetailedCompletedExtModal">External Analysis Details #1</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>


<!-- Modal Part For Edit Analysis Report -->

<div class="modal fade" id="editReportExtModal" tabindex="-1" role="dialog"
     aria-labelledby="editReportExtModal"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editReportExtModal">External Analysis Report #1</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>

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


<script>
    var detectTab = readCookie('activeTab');

    $(document).ready(function () {
        var multipleCounter = 2;

        $('[data-toggle="popover"]').popover();


        $('#isSubcontractor1').click(function () {
            $('#subcontractorName').prop('disabled', false);
            $('#subcontractorSendDate').prop('disabled', false);
        });

        $('#isSubcontractor2').click(function () {
            $('#subcontractorName').prop('disabled', true);
            $('#subcontractorSendDate').prop('disabled', true);
        });

        var removeMultipleAnalysisButton = $('.removeMultipleAnalysis');
        var currentSampleList = [];
        var currentConditionList = [];
        var currentStandardList = [];
        var currentAnalysisList = [];
        var currentDemand = "";
        var multiplePart = $('#multiple-part');

        //BEGIN SELECTPICKERS
        var newAnlSample = $('#enterAnalysisSample1');
        var newAnlCon = $('#enterAnalysisCondition1');
        var newAnlStd = $('#enterAnalysisStandard1');
        var newAnl = $('#enterAnalysisReq1');
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

        //BEGIN ADD OPERATION NOTE
        //Filling Modal ( Add New Operation Note )
        $('.addNewOperationLabNoteButton').on('click', function () {
            var $row = $(this).closest("tr");
            $('#addNewOperationLabNoteOptID').val($(this).attr('id'));
            $('#addNewOperationNoteHeader').text($(this).attr('id'));

        });

        //Cancel Modal ( Add New Operation Note )
        $('.closeAddNewOperationLabNoteModal').click(function () {
            $('#cancelWarnOperationNote').modal('show');
        });

        //Confirm Warning ( Add New Operation Note )
        $('.confirmWarnOperationNote').click(function () {
            var $noteMdl = $('#addNewOperationLabNoteModal');
            var $submitBtn = $("#addNewOperationNoteForm").find(':submit');
            var $cancelBtn = $('.closeAddNewOperationLabNoteModal');
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
            var $cancelBtn = $('.closeAddNewOperationLabNoteModal');
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

        //BEGIN SHOW NOTES
        $('.showOperationLabNotes').on('click', function () {
            var $row = $(this).closest("tr");
            $('#operationNotesOptHeader').text($(this).prop('id'));

            var postData = {selectedOptLabShowNotes: $(this).prop('id')};
            $.ajax({
                type: 'POST',
                url: 'laborant_operations.php',
                data: postData,
                dataType: 'text',
                success: function (resultData) {

                    var result = JSON.parse(resultData);

                    if (result.hasOwnProperty('error')) {
                        //Make error message
                        alert("OOPS");
                    }
                    if (result.hasOwnProperty('operation_id')) {

                        if (result.operation_notes_for_lab.length > 0) {

                            var notesString = "";

                            result.operation_notes_for_lab.forEach(function (note, index) {
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

        //BEGIN ENTER ANALYSIS
        //Filling Modal ( Enter Analysis )
        $('.enterAnalysisButtonActive').on('click', function () {
            var $row = $(this).closest("tr");
            $('#enterAnlFormInfo').text($(this).attr('id') + ")");
            $('#enterAnlOptInfo').text($row.find('.activeOperationID').text());
            $('#enterAnalysisFormID').val($(this).prop('id'));

            let dateArray = $row.find('.activeDate').text().split(" ");
            let time = dateArray[1].split(":");
            let dateParts = dateArray[0].split("-");
            $('#anlDemandDate1').val(dateParts[2] + "-" + dateParts[1] + "-" + dateParts[0] + "T" + time[0] + ":" + time[1]);
            currentDemand = dateParts[2] + "-" + dateParts[1] + "-" + dateParts[0] + "T" + time[0] + ":" + time[1];

            let noSampleMsg = $('#noSampleMessage');
            noSampleMsg.text('');

            newAnlSample.val(null);
            newAnlCon.val(null);
            newAnlStd.val(null);
            newAnl.val(null);
            newAnlSample.selectpicker('refresh');
            newAnlCon.selectpicker('refresh');
            newAnlStd.selectpicker('refresh');
            newAnl.selectpicker('refresh');

            var postDataS = {labFormIDForSample: $(this).prop('id')};
            $.ajax({
                type: 'POST',
                url: 'laborant_operations.php',
                data: postDataS,
                dataType: 'text',
                success: function (resultData) {

                    var result = JSON.parse(resultData);

                    if (result.length > 0) {

                        currentSampleList = [];
                        result.forEach(function (sample, index) {
                            var sampleOption = $('<option>').val(sample.sample_id).text("#" + sample.sample_id + " " + sample.sample_name + " Type: " + sample.type_of_goods + " Amount: " + sample.amount);
                            var sampleObj = {
                                sampleID: sample.sample_id,
                                sampleName: sample.sample_name,
                                sampleType: sample.type_of_goods,
                                sampleAmount: sample.amount
                            };
                            currentSampleList.push(sampleObj);
                            newAnlSample.append(sampleOption);
                        });

                    } else {
                        noSampleMsg.text('There is no sample, please add first.');
                    }

                    newAnlSample.selectpicker('refresh');
                }
            });

            var postDataStd = {standardsForLabForm: $(this).prop('id')};
            $.ajax({
                type: 'POST',
                url: 'laborant_operations.php',
                data: postDataStd,
                dataType: 'text',
                success: function (resultData) {

                    var result = JSON.parse(resultData);

                    if (result.length > 0) {

                        currentStandardList = [];
                        result.forEach(function (standard, index) {
                            var standardOption = $('<option>').val(standard.standard_id).text(standard.standard_name);
                            var standardObj = {standardID: standard.standard_id, standardName: standard.standard_name};
                            currentStandardList.push(standardObj);
                            newAnlStd.append(standardOption);
                        });

                    }

                    newAnlStd.selectpicker('refresh');
                }
            });

            var postDataC = {labFormIDForCondition: $(this).prop('id')};
            $.ajax({
                type: 'POST',
                url: 'laborant_operations.php',
                data: postDataC,
                dataType: 'text',
                success: function (resultData) {

                    var result = JSON.parse(resultData);

                    if (result.length > 0) {

                        currentConditionList = [];
                        result.forEach(function (condition, index) {
                            var conOption = $('<option>').val(condition.analysis_condition_id).text(condition.analysis_condition_name);
                            var conObj = {
                                conditionID: condition.analysis_condition_id,
                                conditionName: condition.analysis_condition_name
                            };
                            currentConditionList.push(conObj);
                            newAnlCon.append(conOption);
                        });

                    }

                    newAnlCon.selectpicker('refresh');

                }
            });

            var postDataANL = {labFormIDForReq: $(this).prop('id')};
            $.ajax({
                type: 'POST',
                url: 'laborant_operations.php',
                data: postDataANL,
                dataType: 'text',
                success: function (resultData) {

                    var result = JSON.parse(resultData);

                    if (result.length > 0) {

                        currentAnalysisList = [];
                        result.forEach(function (req, index) {
                            var reqOption = $('<option>').val(req.analysis_id).text(req.analysis_name + " (Spec: " + req.pivot.spec_info + ")");
                            var reqObj = {
                                reqID: req.analysis_id,
                                reqName: req.analysis_name,
                                reqSpec: req.pivot.spec_info
                            };
                            currentAnalysisList.push(reqObj);
                            newAnl.append(reqOption);
                        });

                    }

                    newAnl.selectpicker('refresh');

                }
            });


        });

        //Cancel Modal ( Enter Analysis )
        $('.closeEnterAnalysisModal').click(function () {
            $('#cancelWarnEnterAnalysis').modal('show');
        });

        //Confirm Warning ( Enter Analysis )
        $('.confirmWarnEnterAnalysis').click(function () {
            var $noteMdl = $('#enterAnalysisModal');
            var $submitBtn = $("#enterAnalysisForm").find(':submit');
            var $cancelBtn = $('.closeEnterAnalysisModal');
            $('#cancelWarnEnterAnalysis').modal('hide');
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
            multipleCounter = 2;
            $('#multiple-part').empty();
            newAnlSample.empty();
            newAnlCon.empty();
            newAnl.empty();
            newAnlStd.empty();
            newAnlSample.selectpicker('refresh');
            newAnlCon.selectpicker('refresh');
            newAnl.selectpicker('refresh');
            newAnlStd.selectpicker('refresh');
            clearForm($noteMdl);
            $('#subcontractorName').prop('disabled', true);
            $('#subcontractorSendDate').prop('disabled', true);

            if (!removeMultipleAnalysisButton.is(':disabled')) {
                removeMultipleAnalysisButton.prop('disabled', true);
            }
        });

        //Accept Process ( Enter Analysis )
        $("#enterAnalysisForm").submit(function () {
            var $submitBtn = $(this).find(':submit');
            var $cancelBtn = $('.closeEnterAnalysisModal');
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
        //END ENTER ANALYSIS

        //BEGIN ACCEPT LAB FORM
        //Filling Modal ( Accept Lab Form )
        $('.acceptFormButtonWaiting').on('click', function () {
            $('#accceptLabFormID').val($(this).prop('id'));
            $('#acceptFormWarning').text('Are you sure you want to accept the laboratory form #' + $(this).prop('id') + ' ?');
        });
        //Confirm Warning ( Accept Lab Form )
        $('.closeAcceptFormModal').click(function () {
            var $newMdl = $('#warnAcceptW');
            $newMdl.modal('hide');
            var $submitBtn = $("#acceptLabForm").find(':submit');
            var $cancelBtn = $('.closeAcceptFormModal');
            if ($submitBtn.hasClass("btn-success")) {
                $submitBtn.val("1");
                $submitBtn.removeClass("btn-success");
                $submitBtn.addClass("btn-primary");
                $submitBtn.text("Accept Form");
            }
            if ($cancelBtn.hasClass("btn-danger")) {
                $cancelBtn.removeClass("btn-danger");
                $cancelBtn.addClass("btn-secondary");
                $cancelBtn.text("Close");
            }

            $('#accceptLabFormID').val('').text('');
            $('#acceptFormWarning').val('').text('');
        });
        //Accept Process ( Accept Lab Form )
        $('#acceptLabForm').submit(function () {
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
        //END ACCEPT LAB FORM


        //BEGIN JOIN LAB FORM
        //Filling Modal ( Join Lab Form )
        $('.joinFormButtonActive').on('click', function () {
            $('#joinLabFormID').val($(this).prop('id'));
            $('#joinFormWarning').text('Are you sure you want to join the laboratory form #' + $(this).prop('id') + ' ?');
        });
        //Confirm Warning ( Join Lab Form )
        $('.closeJoinFormModal').click(function () {
            var $newMdl = $('#warnJoinA');
            $newMdl.modal('hide');
            var $submitBtn = $("#joinLabForm").find(':submit');
            var $cancelBtn = $('.closeJoinFormModal');
            if ($submitBtn.hasClass("btn-success")) {
                $submitBtn.val("1");
                $submitBtn.removeClass("btn-success");
                $submitBtn.addClass("btn-primary");
                $submitBtn.text("Join Form");
            }
            if ($cancelBtn.hasClass("btn-danger")) {
                $cancelBtn.removeClass("btn-danger");
                $cancelBtn.addClass("btn-secondary");
                $cancelBtn.text("Close");
            }

            $('#joinLabFormID').val('').text('');
            $('#joinOperationWarning').val('').text('');
        });
        //Accept Process ( Join Lab Form )
        $('#joinLabForm').submit(function () {
            var $submitBtn = $(this).find(':submit');
            var $cancelBtn = $('.closeJoinFormModal');
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
        //END JOIN LAB FORM

        //BEGIN REMOVE COOKIES
        $('.removeCookieClass').click(function () {
            var cookieID = $(this).prop('id');
            eraseCookie(cookieID);
        });
        //END REMOVE COOKIES

        removeMultipleAnalysisButton.on('click', function () {

            var multipleAnalysisList = $('#multiple-part');


            if (multipleAnalysisList.children().length > 0) {

                multipleAnalysisList.find('.card:last').hide('slow', function () {
                    $(this).remove();
                });

                multipleCounter--;

                if (multipleAnalysisList.children().length === 0) {

                    if (!removeMultipleAnalysisButton.is(':disabled')) {
                        removeMultipleAnalysisButton.prop('disabled', true);
                    }

                }
            }

        });

        $('.addMultipleAnalysis').on('click', function () {


            multiplePart.append('<div class="card">\n' +
                '                            <div class="card-header" id="heading' + multipleCounter + '">\n' +
                '                                <h5 class="mb-0">\n' +
                '                                    <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapse' + multipleCounter + '"\n' +
                '                                            aria-expanded="true" aria-controls="collapse' + multipleCounter + '">\n' +
                '                                        Analysis #' + multipleCounter + '\n' +
                '                                    </button>\n' +
                '                                </h5>\n' +
                '                            </div>\n' +
                '\n' +
                '                            <div id="collapse' + multipleCounter + '" class="collapse show" aria-labelledby="heading' + multipleCounter + '"\n' +
                '                                 data-parent="#accordion">\n' +
                '                                <div class="card-body">\n' +
                '                                    <div class="form-row">\n' +
                '                                        <label for="enterAnalysisSample' + multipleCounter + '"><strong>Sample</strong></label>\n' +
                '                                        <select class="form-control selectpicker show-tick" title="Choose one..."\n' +
                '                                                id="enterAnalysisSample' + multipleCounter + '"\n' +
                '                                                name="enterAnalysisSample' + multipleCounter + '" required>\n' +
                '                                        </select>\n' +
                '                                        <p id="noSampleMessage' + multipleCounter + '" style="font-size: 12px; color:red;"></p>\n' +
                '                                    </div>\n' +
                '                                    <hr/>\n' +
                '\n' +
                '                                    <div class="form-row">\n' +
                '                                        <div class="col-4">\n' +
                '                                            <label for="enterAnalysisCondition' + multipleCounter + '"><strong>Condition</strong></label>\n' +
                '                                            <select class="form-control selectpicker show-tick" title="Choose one..."\n' +
                '                                                    id="enterAnalysisCondition' + multipleCounter + '"\n' +
                '                                                    name="enterAnalysisCondition' + multipleCounter + '" required>\n' +
                '                                            </select>\n' +
                '                                        </div>\n' +
                '                                        <div class="col-4">\n' +
                '                                            <label for="enterAnalysisStandard' + multipleCounter + '"><strong>Standard</strong></label>\n' +
                '                                            <select class="form-control selectpicker show-tick" title="Choose one..."\n' +
                '                                                    id="enterAnalysisStandard' + multipleCounter + '"\n' +
                '                                                    name="enterAnalysisStandard' + multipleCounter + '" required>\n' +
                '                                            </select>\n' +
                '                                        </div>\n' +
                '                                        <div class="col-4">\n' +
                '                                            <label for="enterAnalysisReq' + multipleCounter + '"><strong>Analysis</strong></label>\n' +
                '                                            <select class="form-control selectpicker show-tick" title="Choose one..."\n' +
                '                                                    id="enterAnalysisReq' + multipleCounter + '"\n' +
                '                                                    name="enterAnalysisReq' + multipleCounter + '" required>\n' +
                '                                            </select>\n' +
                '                                            <p id="specMessage" style="font-size: 12px; color:red;"></p>\n' +
                '                                        </div>\n' +
                '                                    </div>\n' +
                '                                    <hr/>\n' +
                '\n' +
                '                                    <div class="form-row">\n' +
                '                                        <div class="col-4">\n' +
                '                                        <label for="anlDemandDate' + multipleCounter + '"><strong>Analysis Demand Date:</strong></label>\n' +
                '                                        <input type="datetime-local" class="form-control" name="anlDemandDate' + multipleCounter + '"\n' +
                '                                               id="anlDemandDate' + multipleCounter + '"\n' +
                '                                               value="' + currentDemand + '" readonly>\n' +
                '                                        </div>\n' +
                '                                        <div class="col-4">\n' +
                '                                            <label for="anlStartDate"><strong>Analysis Start Date:</strong></label>\n' +
                '                                            <input type="datetime-local" class="form-control" name="anlStartDate' + multipleCounter + '"\n' +
                '                                                   id="anlStartDate' + multipleCounter + '"\n' +
                '                                                   value="" required>\n' +
                '                                        </div>\n' +
                '                                        <div class="col-4">\n' +
                '                                            <label for="anlEndDate"><strong>Analysis End Date:</strong></label>\n' +
                '                                            <input type="datetime-local" class="form-control" name="anlEndDate' + multipleCounter + '"\n' +
                '                                                   id="anlEndDate' + multipleCounter + '"\n' +
                '                                                   value="" required>\n' +
                '                                        </div>\n' +
                '                                    </div>\n' +
                '                                    <hr/>\n' +
                '\n' +
                '                                    <h6>Result & Remarks</h6>\n' +
                '                                    <hr/>\n' +
                '\n' +
                '                                    <div class="form-row">\n' +
                '                                        <div class="col-6">\n' +
                '                                            <label for="anlResult"><strong>Result:</strong></label>\n' +
                '                                            <input type="text" class="form-control" id="anlResult' + multipleCounter + '"\n' +
                '                                                   name="anlResult' + multipleCounter + '" required>\n' +
                '                                        </div>\n' +
                '                                        <div class="col-6">\n' +
                '                                            <label for="anlRemarks"><strong>Remarks:</strong></label>\n' +
                '                                            <textarea class="form-control" rows="5" id="anlRemarks' + multipleCounter + '" name="anlRemarks' + multipleCounter + '"\n' +
                '                                                      placeholder="Optional"></textarea>\n' +
                '                                        </div>\n' +
                '                                    </div>\n' +
                '\n' +
                '\n' +
                '                                </div>\n' +
                '                            </div>\n' +
                '                        </div>');

            multiplePart.children(':last').hide().fadeIn(500);


            var newAddedSampleList = "enterAnalysisSample" + multipleCounter;
            var newAddedConditionList = "enterAnalysisCondition" + multipleCounter;
            var newAddedStandardList = "enterAnalysisStandard" + multipleCounter;
            var newAddedAnalysisList = "enterAnalysisReq" + multipleCounter;

            var selectAddedSampleList = $('#' + newAddedSampleList);
            var selectAddedConditionList = $('#' + newAddedConditionList);
            var selectAddedStandardList = $('#' + newAddedStandardList);
            var selectAddedAnalysisList = $('#' + newAddedAnalysisList);


            $.each(currentSampleList, function (key, value) {
                var newOption = $('<option>').val(value.sampleID).text("#" + value.sampleID + " " + value.sampleName + " Type: " + value.sampleType + " Amount: " + value.sampleAmount);
                selectAddedSampleList.append(newOption);
            });
            selectAddedSampleList.selectpicker('refresh');


            $.each(currentConditionList, function (key, value) {
                var newConOption = $('<option>').val(value.conditionID).text(value.conditionName);
                selectAddedConditionList.append(newConOption);
            });
            selectAddedConditionList.selectpicker('refresh');


            $.each(currentStandardList, function (key, value) {
                var newStandardOption = $('<option>').val(value.standardID).text(value.standardName);
                selectAddedStandardList.append(newStandardOption);
            });
            selectAddedStandardList.selectpicker('refresh');

            $.each(currentAnalysisList, function (key, value) {
                var newReqOption = $('<option>').val(value.reqID).text(value.reqName + " (Spec: " + value.reqSpec + ")");
                selectAddedAnalysisList.append(newReqOption);
            });
            selectAddedAnalysisList.selectpicker('refresh');


            multipleCounter++;

            if (removeMultipleAnalysisButton.is(':disabled')) {
                removeMultipleAnalysisButton.prop('disabled', false);
            }
        });


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