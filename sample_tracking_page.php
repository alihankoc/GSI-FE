<?php
include 'vendor/autoload.php';
$dotenv =\Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

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
    case "4":
        header('Location: laboratory_analysis_page.php');
        break;
    case "8":
        header('Location: certificates.php');
        break;
}

$apiCaller = new ApiCaller('1', $_SESSION['token']);

$samples = $apiCaller->sendRequest(array(
    'api_url' => $_ENV['LINK'].'getAllSamples',
    'api_method' => 'get',
));

$shouldDeleteCount = 0;
foreach ($samples['data'] as $sample) {
    if (date("Y-m-d H:i:s", strtotime('-3 months')) > date("Y-m-d H:i:s", strtotime($sample->delivery_date))) {
        $shouldDeleteCount++;
    }
}

$operations = $apiCaller->sendRequest(array(
    'api_url' => $_ENV['LINK'].'getOperationsForNewSample',
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
            <a tabindex="0" class="nav-link" href="#" role="button" data-toggle="popover" data-placement="bottom"
               data-trigger="focus" title="Sample Notification"
               data-content="<?php echo ($shouldDeleteCount > 0) ? "There is " . $shouldDeleteCount . " sample(s) should be deleted! Please check!" : "There is no sample should be deleted."; ?>"><span
                        class="fas <?php echo ($shouldDeleteCount > 0) ? "fa-bell" : "fa-bell-slash"; ?>"></span>
                Notification
            </a>
        </li>
        <li class="nav-item active">
            <a class="nav-link" href="#"><span class="fas fa-user"></span> Welcome <?php echo $_SESSION["user_name"]; ?>
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
        <h4>Sample Tracking Page</h4>
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
            Section where you can manage samples.

            <button type="button" class="btn btn-outline-info float-right" data-toggle="modal"
                    data-target="#newSampleModal" data-backdrop="static" data-keyboard="false"><i
                        class="fas fa-plus-circle"></i> Add Sample
            </button>
            <button type="button" class="btn btn-outline-info float-right printTable"
                    style="margin-right: 5px;"><i
                        class="fas fa-print"></i> Print
            </button>
            <button type="button" id="allSamplesButton" class="btn btn-outline-info float-right"
                    style="margin-right: 5px;" value="1"><i
                        class="fas fa-file-excel"></i> Excel
            </button>
        </div>
        <div class="card-body" style="padding-top: 0;">

            <div class="input-group" style="margin-top: 10px;">
                <input id="myInputSample" type="text" class="form-control"
                       placeholder="Type something for search..">
                <div class="input-group-append">
                    <button class="btn btn-secondary" type="button">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </div>
            <div id="exportDiv" class="table-responsive">
                <table class="table table-striped" id="sampleTable" style="font-size: 12px;">
                    <thead>
                    <tr>
                        <th onclick="sortTable(0,'sampleTable','int')">#</th>
                        <th onclick="sortTable(1,'sampleTable','str')">Sample Name</th>
                        <th onclick="sortTable(2,'sampleTable','str')">Type Of Goods</th>
                        <th onclick="sortTable(3,'sampleTable','str')">Delivery Method</th>
                        <th onclick="sortTable(4,'sampleTable','date')">Delivery Date</th>
                        <th onclick="sortTable(5,'sampleTable','int')">Amount</th>
                        <th onclick="sortTable(6,'sampleTable','str')">Is External</th>
                        <th onclick="sortTable(7,'sampleTable','str')">Is Available</th>
                        <th onclick="sortTable(8,'sampleTable','int')">Operation ID</th>
                        <th onclick="sortTable(9,'sampleTable','str')">Place</th>
                        <th onclick="sortTable(10,'sampleTable','str')">User</th>
                        <th onclick="sortTable(11,'sampleTable','date')">Expiry Date</th>
                        <th>Processes</th>
                    </tr>
                    </thead>
                    <tbody id="myTableSample">
                    <?php foreach ($samples['data'] as $sample) { ?>
                        <tr <?php echo (date("Y-m-d H:i:s", strtotime('-3 months')) > date("Y-m-d H:i:s", strtotime($sample->delivery_date))) ? 'class="bg-danger"' : ""; ?>>
                            <td><?php echo $sample->sample_id; ?></td>
                            <td><?php echo $sample->sample_name; ?></td>
                            <td><?php echo $sample->type_of_goods; ?></td>
                            <td><?php
                                if ($sample->delivery_method == 0) {
                                    echo "From Hand";
                                } else {
                                    echo "With Cargo";
                                }
                                ?></td>
                            <td><?php echo date("d-m-Y H:i:s", strtotime($sample->delivery_date)); ?></td>
                            <td><?php echo $sample->amount; ?></td>
                            <td><?php
                                if ($sample->is_external == 0) {
                                    echo "Operational";
                                } else {
                                    echo "External";
                                }
                                ?></td>
                            <td><?php
                                if ($sample->is_available == 0) {
                                    echo "Unavailable";
                                } else {
                                    echo "Ready For Analyze";
                                }
                                ?></td>
                            <td><?php echo (!empty($sample->sample_opt_id)) ? "GSI" . $sample->sample_opt_id : "-"; ?></td>
                            <td><?php echo $sample->place_of_sample; ?></td>
                            <td><?php echo $sample->get_which_user->name . " " . $sample->get_which_user->surname; ?></td>
                            <td><?php echo date("d-m-Y H:i:s", strtotime($sample->expiry_date)); ?></td>
                            <td>
                                <div class="btn-group dropdown">
                                    <button type="button" class="btn btn-primary dropdown-toggle btn-sm"
                                            data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                        Menu
                                    </button>
                                    <div class="dropdown-menu">
                                        <?php if ($sample->is_available == 1) { ?>
                                            <button type="button"
                                                    class="dropdown-item btn btn-info btn-sm unavailableSampleButton"
                                                    data-toggle="modal"
                                                    data-target="#warnUnavailable" data-backdrop="static"
                                                    data-keyboard="false"
                                                    id="<?php echo $sample->sample_id; ?>">
                                                Make Unavailable
                                            </button>
                                        <?php } else { ?>
                                            <button type="button"
                                                    class="dropdown-item btn btn-info btn-sm availableSampleButton"
                                                    data-toggle="modal"
                                                    data-target="#warnAvailable" data-backdrop="static"
                                                    data-keyboard="false"
                                                    id="<?php echo $sample->sample_id; ?>">
                                                Make Available
                                            </button>
                                            <?php
                                        }
                                        ?>
                                        <button type="button"
                                                class="dropdown-item btn btn-danger btn-sm deleteSampleButton"
                                                data-toggle="modal"
                                                data-target="#warnDelete" data-backdrop="static"
                                                data-keyboard="false"
                                                id="<?php echo $sample->sample_id; ?>">
                                            Delete
                                        </button>
                                    </div>
                                </div>

                            </td>
                        </tr>
                    <?php }
                    if (empty((array)$samples['data'])) {
                        ?>
                        <tr>
                            <td colspan="12" class="text-center">
                                <p><strong>There is no sample in inventory.</strong></p>
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

<!-- Unavailable Sample Modal -->
<div id="warnUnavailable" class="modal fade" role="dialog" data-backdrop="false">
    <div class="modal-dialog modal-dialog-centered">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                Warning
            </div>
            <form id="unavailableSampleForm" name="unavailableSampleForm" action="sample_operations.php"
                  method="post">
                <input type="hidden" name="unavailableSampleID" id="unavailableSampleID"/>
                <div class="modal-body">
                    <p id="unavailableSampleWarning"></p>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary closeUnavailableSampleModal">Close</button>
                    <button type="submit" class="btn btn-primary" value="1">Make Unavailable</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Available Sample Modal -->
<div id="warnAvailable" class="modal fade" role="dialog" data-backdrop="false">
    <div class="modal-dialog modal-dialog-centered">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                Warning
            </div>
            <form id="availableSampleForm" name="availableSampleForm" action="sample_operations.php"
                  method="post">
                <input type="hidden" name="availableSampleID" id="availableSampleID"/>
                <div class="modal-body">
                    <p id="availableSampleWarning"></p>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary closeAvailableSampleModal">Close</button>
                    <button type="submit" class="btn btn-primary" value="1">Make Available</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Sample Modal -->
<div id="warnDelete" class="modal fade" role="dialog" data-backdrop="false">
    <div class="modal-dialog modal-dialog-centered">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                Warning
            </div>
            <form id="deleteSampleForm" name="deleteSampleForm" action="sample_operations.php" method="post">
                <input type="hidden" name="deleteSampleID" id="deleteSampleID"/>
                <div class="modal-body">
                    <p id="deleteSampleWarning"></p>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary closeDeleteSampleModal">Close</button>
                    <button type="submit" class="btn btn-primary" value="1">Delete Sample</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add New Sample -->

<div class="modal fade" id="newSampleModal" tabindex="-1" role="dialog"
     aria-labelledby="newSampleModalTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newSampleModalTitle">Add New Sample</h5>
            </div>

            <form id="newSampleForm" name="newSampleForm" action="sample_operations.php"
                  method="post">

                <div class="modal-body">

                    <h6 style="font-size: 16px;">Informations</h6>
                    <hr/>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="sampleName">Sample Name(Optional)</label>
                            <input type="text" class="form-control form-control-sm" id="sampleName"
                                   name="sampleName" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="typeOfGoods">Type Of Goods</label>
                            <input type="text" class="form-control form-control-sm" id="typeOfGoods"
                                   name="typeOfGoods" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="samplePlace">Place</label>
                            <input type="text" class="form-control form-control-sm" id="samplePlace"
                                   name="samplePlace" required>
                        </div>
                    </div>

                    <hr/>

                    <h6>Delivery Method</h6>
                    <hr/>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="deliveryMethod1" name="deliveryMethod"
                               class="custom-control-input"
                               value="0" required>
                        <label class="custom-control-label"
                               for="deliveryMethod1">From Hand</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="deliveryMethod2" name="deliveryMethod"
                               class="custom-control-input"
                               value="1" required>
                        <label class="custom-control-label"
                               for="deliveryMethod2">With Cargo</label>
                    </div>

                    <hr/>
                    <div class="form-group row">
                        <label for="deliveryDate" class="col-md-2 col-form-label"><strong>Delivery
                                Date:</strong></label>
                        <div class="col-md-4">
                            <input type="datetime-local" class="form-control" name="deliveryDate" id="deliveryDate"
                                   value="" required>
                        </div>
                        <label for="amount" class="col-md-2 col-form-label"><strong>Amount:</strong></label>
                        <div class="col-md-4 vertAlgn">
                            <input type="number" min="0" step="0.001" class="form-control" name="amount"
                                   id="amount" required>
                        </div>
                    </div>

                    <hr/>

                    <h6>Is External</h6>
                    <hr/>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="isExternal1" name="isExternal"
                               class="custom-control-input isExternal"
                               value="0" required>
                        <label class="custom-control-label"
                               for="isExternal1">Operational</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="isExternal2" name="isExternal"
                               class="custom-control-input isExternal"
                               value="1" required>
                        <label class="custom-control-label"
                               for="isExternal2">External</label>
                    </div>

                    <hr/>

                    <div class="form-row">
                        <label for="operationID"><strong>Operation</strong></label>
                        <select class="form-control selectpicker show-tick" title="Choose one..."
                                id="operationID"
                                name="operationID">
                            <?php foreach ($operations['data'] as $opt) { ?>
                                <option value="<?php echo $opt->operation_id; ?>"><?php echo "Operation GSI" . $opt->operation_id; ?></option>
                            <?php } ?>
                        </select>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary closeNewSampleModal">Close</button>
                    <button type="submit" class="btn btn-primary" value="1">Save</button>
                </div>
            </form>


        </div>
    </div>
</div>

<!-- Close Warn For Add New Sample -->

<div id="cancelWarnNewSample" class="modal fade" role="dialog" data-backdrop="false">
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
                <button type="button" class="btn btn-danger confirmWarnNewSample float-right btn-sm">Yes</button>
            </div>
        </div>
    </div>
</div>

<script>

    $(document).ready(function () {
        $('[data-toggle="popover"]').popover();

        var newSampleOpt = $('#operationID');

        $('#isExternal2').click(function () {
            newSampleOpt.prop('disabled', true);
            newSampleOpt.selectpicker('refresh');
        });

        $('#isExternal1').click(function () {
            newSampleOpt.prop('disabled', false);
            newSampleOpt.selectpicker('refresh');
        });

        //BEGIN EXCEL EXPORT
        $('#allSamplesButton').on('click', function (e) {
            e.preventDefault();

            var sampleHtmlString;
            sampleHtmlString = $("#sampleTable").clone();
            sampleHtmlString.find("tr > td:last-child").remove();
            sampleHtmlString.find("tr > th:last-child").remove();

            var postDataExport = {sampleHtmlContent: sampleHtmlString.prop('outerHTML')};
            $.ajax({
                type: 'POST',
                url: 'sample_operations.php',
                data: postDataExport,
                xhrFields: {
                    responseType: 'blob'
                },
                success: function (resultData) {
                    // check for a filename
                    //window.location = 'sample_operations.php';
                    var a = document.createElement('a');
                    var url = window.URL.createObjectURL(resultData);
                    a.href = url;
                    a.download = 'allSamples.xls';
                    document.body.append(a);
                    a.click();
                    a.remove();
                    window.URL.revokeObjectURL(url);
                }

            });
        });
        //END EXCEL EXPORT

        $("#myInputSample").on("keyup", function () {
            var value = $(this).val().toLowerCase();
            $("#myTableSample tr").filter(function () {
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
            divToPrint = $("#sampleTable").clone();
            divToPrint.find("tr > td:last-child").remove();
            divToPrint.find("tr > th:last-child").remove();

            newWin = window.open("");
            newWin.document.write(headOfHtml + divToPrint.prop('outerHTML') + footerOfHtml);
            $(newWin).ready(function () {
                newWin.print();
                newWin.close();
            });
        });
        //END PRINT TABLE

        //BEGIN MAKE UNAVAILABLE SAMPLE
        //Filling Modal ( Make Unavailable Sample )
        $('.unavailableSampleButton').on('click', function () {
            $('#unavailableSampleID').val($(this).prop('id'));
            $('#unavailableSampleWarning').text('Are you sure you want to make this sample #' + $(this).prop('id') + ' unavailable for future analyzes?');
        });
        //Confirm Warning ( Make Unavailable Sample )
        $('.closeUnavailableSampleModal').click(function () {
            var $newMdl = $('#warnUnavailable');
            $newMdl.modal('hide');
            var $submitBtn = $("#unavailableSampleForm").find(':submit');
            var $cancelBtn = $('.closeUnavailableSampleModal');
            if ($submitBtn.hasClass("btn-success")) {
                $submitBtn.val("1");
                $submitBtn.removeClass("btn-success");
                $submitBtn.addClass("btn-primary");
                $submitBtn.text("Make Unavailable");
            }
            if ($cancelBtn.hasClass("btn-danger")) {
                $cancelBtn.removeClass("btn-danger");
                $cancelBtn.addClass("btn-secondary");
                $cancelBtn.text("Close");
            }

            $('#unavailableSampleID').val('').text('');
            $('#unavailableSampleWarning').val('').text('');
        });
        //Accept Process ( Make Unavailable Sample )
        $('#unavailableSampleForm').submit(function () {
            var $submitBtn = $(this).find(':submit');
            var $cancelBtn = $('.closeUnavailableSampleModal');
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
        //END MAKE UNAVAILABLE SAMPLE

        //BEGIN MAKE AVAILABLE SAMPLE
        //Filling Modal ( Make Unavailable Sample )
        $('.availableSampleButton').on('click', function () {
            $('#availableSampleID').val($(this).prop('id'));
            $('#availableSampleWarning').text('Are you sure you want to make this sample #' + $(this).prop('id') + ' available for future analyzes?');
        });
        //Confirm Warning ( Make Unavailable Sample )
        $('.closeAvailableSampleModal').click(function () {
            var $newMdl = $('#warnAvailable');
            $newMdl.modal('hide');
            var $submitBtn = $("#availableSampleForm").find(':submit');
            var $cancelBtn = $('.closeAvailableSampleModal');
            if ($submitBtn.hasClass("btn-success")) {
                $submitBtn.val("1");
                $submitBtn.removeClass("btn-success");
                $submitBtn.addClass("btn-primary");
                $submitBtn.text("Make Unavailable");
            }
            if ($cancelBtn.hasClass("btn-danger")) {
                $cancelBtn.removeClass("btn-danger");
                $cancelBtn.addClass("btn-secondary");
                $cancelBtn.text("Close");
            }

            $('#availableSampleID').val('').text('');
            $('#availableSampleWarning').val('').text('');
        });
        //Accept Process ( Make Unavailable Sample )
        $('#availableSampleForm').submit(function () {
            var $submitBtn = $(this).find(':submit');
            var $cancelBtn = $('.closeAvailableSampleModal');
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
        //END MAKE AVAILABLE SAMPLE

        //BEGIN DELETE SAMPLE
        //Filling Modal ( Delete Sample )
        $('.deleteSampleButton').on('click', function () {
            $('#deleteSampleID').val($(this).prop('id'));
            $('#deleteSampleWarning').text('Are you sure you want to delete sample #' + $(this).prop('id') + ' ?');
        });
        //Confirm Warning ( Delete Sample )
        $('.closeDeleteSampleModal').click(function () {
            var $newMdl = $('#warnDelete');
            $newMdl.modal('hide');
            var $submitBtn = $("#deleteSampleForm").find(':submit');
            var $cancelBtn = $('.closeDeleteSampleModal');
            if ($submitBtn.hasClass("btn-success")) {
                $submitBtn.val("1");
                $submitBtn.removeClass("btn-success");
                $submitBtn.addClass("btn-primary");
                $submitBtn.text("Delete Sample");
            }
            if ($cancelBtn.hasClass("btn-danger")) {
                $cancelBtn.removeClass("btn-danger");
                $cancelBtn.addClass("btn-secondary");
                $cancelBtn.text("Close");
            }

            $('#deleteSampleID').val('').text('');
            $('#deleteSampleWarning').val('').text('');
        });
        //Accept Process ( Delete Sample )
        $('#deleteSampleForm').submit(function () {
            var $submitBtn = $(this).find(':submit');
            var $cancelBtn = $('.closeDeleteSampleModal');
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
        //END DELETE SAMPLE

        //BEGIN ADD SAMPLE
        //Cancel Modal ( Add New Sample )
        $('.closeNewSampleModal').click(function () {
            $('#cancelWarnNewSample').modal('show');
        });

        //Confirm Warning ( Add New Sample )
        $('.confirmWarnNewSample').click(function () {
            var $noteMdl = $('#newSampleModal');
            var $submitBtn = $("#newSampleForm").find(':submit');
            var $cancelBtn = $('.closeNewSampleModal');
            $('#cancelWarnNewSample').modal('hide');
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

            newSampleOpt.val(null);
            newSampleOpt.selectpicker('refresh');

            clearForm($noteMdl);
        });

        //Accept Process ( Add New Sample )
        $("#newSampleForm").submit(function () {
            var $submitBtn = $(this).find(':submit');
            var $cancelBtn = $('.closeNewSampleModal');
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
        //END ADD SAMPLE

        //BEGIN REMOVE COOKIES
        $('.removeCookieClass').click(function () {
            var cookieID = $(this).prop('id');
            eraseCookie(cookieID);
        });
        //END REMOVE COOKIES

    })
    ;

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
        if (whichForm.prop('id') === "newSampleModal") {
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