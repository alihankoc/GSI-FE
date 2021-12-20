<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 14.02.2019
 * Time: 02:54
 */

include_once "url_slug.php";
include_once "ApiCaller.php";
include 'vendor/autoload.php';
$dotenv =\Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

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
    case "7":
        header('Location: sample_tracking_page.php');
        break;
    case "8":
        header('Location: certificates.php');
        break;
}


$apiCaller = new ApiCaller('1', $_SESSION['token']);

$customers = $apiCaller->sendRequest(array(
    'api_url' => $_ENV['LINK'].'viewCustomersForNewOperation',
    'api_method' => 'get',
));

$inspectionLocations = $apiCaller->sendRequest(array(
    'api_url' => $_ENV['LINK'].'viewMyOffices',
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

$analysisConditions = $apiCaller->sendRequest(array(
    'api_url' => $_ENV['LINK'].'viewAnalysisConditions',
    'api_method' => 'get',
));

$analyzes = $apiCaller->sendRequest(array(
    'api_url' => $_ENV['LINK'].'viewAnalysises',
    'api_method' => 'get',
));

$surveillances = $apiCaller->sendRequest(array(
    'api_url' => $_ENV['LINK'].'viewSurveillances',
    'api_method' => 'get',
));

$waitingOperations = $apiCaller->sendRequest(array(
    'api_url' => $_ENV['LINK'].'getOperationsOfficer/1',
    'api_method' => 'get',
));
$activeOperations = $apiCaller->sendRequest(array(
    'api_url' => $_ENV['LINK'].'getOperationsOfficer/2',
    'api_method' => 'get',
));
$completedOperations = $apiCaller->sendRequest(array(
    'api_url' => $_ENV['LINK'].'getOperationsOfficer/3',
    'api_method' => 'get',
));
$myOperations = $apiCaller->sendRequest(array(
    'api_url' => $_ENV['LINK'].'myOperations/3',
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
        <h4>New External Analysis</h4>
    </div>
</div>

<div class="container">

    <div class="card bg-light text-dark">
        <div class="card-header">
            Section where you can new external analysis.
        </div>
        <div class="card-body">
      
            
        <form id="newExternalJobForm" name="newExternalJobForm" action="#" method="post">
            <input type="hidden" name="newExternal" value="1">
            <div class="modal-body" style="font-size: 14px;">

                <h6 style="font-size: 16px;">Informations</h6>
                <hr/>

                <div class="form-group row">
                    <label for="userSurname" class="col-sm-3 col-form-label">Request Type:</label>
                    <div class="col-sm-9">
                        <select class="form-control" name="external_request_type">
                            <option value="new"   selected="selected">New Request</option>
                            <option value="additional">Additional Request</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="userSurname" class="col-sm-3 col-form-label">Request Receiver:</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="external_receiver_name" id="userSurname"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="userPassword" class="col-sm-3 col-form-label">Customer Name:</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="external_customer_name" id="userPassword"/>
                    </div>
                </div>


                <h6 style="font-size: 16px; margin-top: 20px;">Requested Analysis</h6>
                <hr/>
                <div class="row" id="reqAnlCheckboxes">
                    <?php foreach ($analyzes['data'] as $singleAnl) { ?>
                        <div class="col-md-4" style="margin-bottom: 5px;">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input reqAnlCheckboxClass"
                                    id="newOperationReqAnl<?php echo $singleAnl->analysis_id; ?>"
                                    name="newOperationReqAnl[<?php echo $singleAnl->analysis_id; ?>]" value="<?php echo $singleAnl->analysis_id; ?>">
                                <label class="custom-control-label"
                                    for="newOperationReqAnl<?php echo $singleAnl->analysis_id; ?>"><?php echo $singleAnl->analysis_name; ?></label>
                            </div>
                            <input type="text" class="form-control"
                                id="newOperationReqSpec<?php echo $singleAnl->analysis_id; ?>"
                                name="newOperationReqSpec[<?php echo $singleAnl->analysis_id; ?>]" disabled>
                        </div>
                    <?php } ?>
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

<div id="warnAcceptW" class="modal fade confirmCompleteFormModal" role="dialog" data-backdrop="false">
    <div class="modal-dialog modal-dialog-centered">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                Warning
            </div>
            <form id="completeLabForm" action="#" method="post">
                <input type="hidden" name="completeLabFormID" id="completeLabFormID"/>
                <input type="hidden" name="isExternal" value="isExternal"/>
                <div class="modal-body">
                    <p>Are you sure?</p>
                </div>
                <div class="modal-footer">
                    <button type="button"  data-dismiss="modal" class="btn btn-secondary closeAcceptFormModal">Close</button>
                    <button type="button" class="btn btn-primary" id="createForm"  data-value="1">Create Form</button>
                </div>
            </form>
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
    $(function() {
        $('.reqAnlCheckboxClass').on('change', function () {
            let clickedID = $(this).val();
            let thatInput = $('#newOperationReqSpec' + clickedID);
            if (thatInput.is(':disabled')) {
                thatInput.prop('disabled', false);
            } else {
                thatInput.prop('disabled', true);
            }
        });

        $('#createForm').click(function() {
            const data = $('#newExternalJobForm').serialize()
            $.ajax({
                type: 'POST',
                data: data,
                dataType: 'JSON',
                headers: {
                    'Authorization': 'Bearer <?php echo $_SESSION['token']; ?>'
                },
                url: ' <?php echo $_ENV['LINK']; ?>externalLabJob',
                success (s) {
                    window.location.href = "external_laboratory_analysis_page.php"
                }
            })
        })


        $('#newExternalJobForm').submit(function (e) {
            e.preventDefault()
            $('.confirmCompleteFormModal').modal('show')
        })
        
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
    })

</script>

</body>
</html>