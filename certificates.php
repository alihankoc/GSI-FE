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
    case "3":
        header('Location: field_operation_page.php');
        break;
    case "4":
        header('Location: laboratory_analysis_page.php');
        break;
    case "7":
        header('Location: sample_tracking_page.php');
        break;
}


$apiCaller = new ApiCaller('1', $_SESSION['token']);

$certificates = $apiCaller->sendRequest(array(
    'api_url' => 'https://lumen.krekpot.com/api/v1/viewCertificates',
    'api_method' => 'get',
));

$normalCount = 0;
$approvedCount = 0;
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
            <a class="nav-link" href="logout_function.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </li>
    </ul>
</nav>
<div class="jumbotron jumbotron-fluid">
    <div class="container">
        <h4>Certificates</h4>
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
            Section where you can find you generated Certificates.
            <a href="new_certificate.php" class="btn btn-outline-primary btn-sm float-right"
               style="margin-right: 5px;"><i
                        class="fas fa-plus-circle"></i> Generate Certificate
            </a>
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a id="normalTabLink" class="nav-link active" data-toggle="tab" href="#normal">Draft
                        Certificates</a>
                </li>
                <li class="nav-item">
                    <a id="activeTabLink" class="nav-link" data-toggle="tab" href="#approved">Approved Certificates</a>
                </li>
            </ul>
            <div class="tab-content">
                <div id="normal" class="container tab-pane active">
                    <div class="input-group" style="margin-top: 10px;">
                        <input id="myInputNormal" type="text" class="form-control"
                               placeholder="Type something for search..">
                        <div class="input-group-append">
                            <button class="btn btn-secondary" type="button">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped" id="normalTable" style="font-size: 12px;">
                            <thead>
                            <tr>
                                <th onclick="sortTable(0,'normalTable','str')">#</th>
                                <th onclick="sortTable(1,'normalTable','str')">No</th>
                                <th onclick="sortTable(2,'normalTable','str')">Job Name</th>
                                <th onclick="sortTable(3,'normalTable','str')">Customer</th>
                                <th onclick="sortTable(4,'normalTable','str')">Note</th>
                                <th onclick="sortTable(5,'normalTable','str')">File Name</th>
                                <th onclick="sortTable(6,'normalTable','date')">Date</th>
                                <th onclick="sortTable(7,'normalTable','str')">Type</th>
                                <th>Processes</th>
                            </tr>
                            </thead>
                            <tbody id="myTableNormal">
                            <?php foreach ($certificates['data'] as $crt) {
                                if ($crt->is_approved == 0) {
                                    ?>
                                    <tr>
                                        <td><?php echo $crt->certificate_id; ?></td>
                                        <td>
                                            <?php echo $crt->certificate_no; ?>
                                        </td>
                                        <td>
                                            <?php echo $crt->certificate_job; ?>
                                        </td>
                                        <td>
                                            <?php echo $crt->certificate_customer; ?>
                                        </td>
                                        <td>
                                            <?php echo (is_null($crt->certificate_note)) ? '-' : $crt->certificate_note; ?>
                                        </td>
                                        <td>
                                            <strong><?php echo $crt->certificate_name; ?></strong>
                                        </td>
                                        <td>
                                            <?php echo $crt->created_at; ?>
                                        </td>
                                        <td>
                                            <?php echo strtoupper($crt->certificate_type); ?>
                                        </td>
                                        <td>
                                            <a class="text-danger removeCertificateButton" data-toggle="modal"
                                               data-target="#warnRemove" data-backdrop="static"
                                               data-keyboard="false"
                                               id="<?php echo $crt->certificate_id; ?>" href="#"><i
                                                        class="fas fa-trash"></i></a>
                                            <a href="<?php echo $crt->certificate_path; ?>" target="_blank"
                                               download="<?php echo $crt->certificate_name; ?>"><i
                                                        class="fas fa-download"></i></a>
                                            <a class="text-success approveCertificateButton" data-toggle="modal"
                                            data-target="#warnApprove" data-backdrop="static"
                                            data-keyboard="false"
                                            id="<?php echo $crt->certificate_id; ?>" href="#"><i
                                                        class="fas fa-check"></i></a>
                                        </td>

                                    </tr>
                                    <?php
                                    $normalCount++;
                                }
                            }
                            if ($normalCount == 0) {
                                ?>
                                <tr>
                                    <td colspan="10" class="text-center">
                                        <p><strong>There is no draft certificates.</strong></p>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="approved" class="container tab-pane fade">
                    <div class="input-group" style="margin-top: 10px;">
                        <input id="myInputApproved" type="text" class="form-control"
                               placeholder="Type something for search..">
                        <div class="input-group-append">
                            <button class="btn btn-secondary" type="button">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped" id="approvedTable" style="font-size: 12px;">
                            <thead>
                            <tr>
                                <th onclick="sortTable(0,'approvedTable','str')">#</th>
                                <th onclick="sortTable(1,'approvedTable','str')">No</th>
                                <th onclick="sortTable(2,'approvedTable','str')">Job Name</th>
                                <th onclick="sortTable(3,'approvedTable','str')">Customer</th>
                                <th onclick="sortTable(4,'approvedTable','str')">Note</th>
                                <th onclick="sortTable(5,'approvedTable','str')">File Name</th>
                                <th onclick="sortTable(6,'approvedTable','date')">Date</th>
                                <th onclick="sortTable(7,'approvedTable','str')">Type</th>
                                <th>Processes</th>
                            </tr>
                            </thead>
                            <tbody id="myTableApproved">
                            <?php foreach ($certificates['data'] as $crt) {
                                if ($crt->is_approved == 1) {
                                    ?>
                                    <tr>
                                        <td><?php echo $crt->certificate_id; ?></td>
                                        <td>
                                            <?php echo $crt->certificate_no; ?>
                                        </td>
                                        <td>
                                            <?php echo $crt->certificate_job; ?>
                                        </td>
                                        <td>
                                            <?php echo $crt->certificate_customer; ?>
                                        </td>
                                        <td>
                                            <?php echo (is_null($crt->certificate_note)) ? '-' : $crt->certificate_note; ?>
                                        </td>
                                        <td>
                                            <strong><?php echo $crt->certificate_name; ?></strong>
                                        </td>
                                        <td>
                                            <?php echo $crt->created_at; ?>
                                        </td>
                                        <td>
                                            <?php echo strtoupper($crt->certificate_type); ?>
                                        </td>
                                        <td>
                                            <a href="<?php echo $crt->certificate_path; ?>" target="_blank"
                                               download="<?php echo $crt->certificate_name; ?>"><i
                                                        class="fas fa-download"></i></a>
                                        </td>
                                    </tr>
                                    <?php
                                    $approvedCount++;
                                }
                            }
                            if ($approvedCount == 0) {
                                ?>
                                <tr>
                                    <td colspan="10" class="text-center">
                                        <p><strong>There is no approved certificates.</strong></p>
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

<!-- Approve Certificate Modal -->
<div id="warnApprove" class="modal fade" role="dialog" data-backdrop="false">
    <div class="modal-dialog modal-dialog-centered">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                Warning
            </div>
            <form id="approveCertificateForm" name="approveCertificateForm" action="certificate_operations.php" method="post">
                <input type="hidden" name="approveCertificateID" id="approveCertificateID"/>
                <div class="modal-body">
                    <p id="approveCertificateWarning"></p>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary closeApproveCertificateModal">Close</button>
                    <button type="submit" class="btn btn-primary" value="1">Approve Certificate</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Remove Draft Certificate Modal -->
<div id="warnRemove" class="modal fade" role="dialog" data-backdrop="false">
    <div class="modal-dialog modal-dialog-centered">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                Warning
            </div>
            <form id="removeCertificateForm" name="removeCertificateForm" action="certificate_operations.php" method="post">
                <input type="hidden" name="removeCertificateID" id="removeCertificateID"/>
                <div class="modal-body">
                    <p id="removeCertificateWarning"></p>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary closeRemoveCertificateModal">Close</button>
                    <button type="submit" class="btn btn-primary" value="1">Delete Certificate</button>
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

    var $invokerLinkIDForCancel = 0;

    $(document).ready(function () {
        $('[data-toggle="popover"]').popover();


        //Listen active tab change and write in to cookie
        $('.nav-tabs a').on('shown.bs.tab', function () {
            createCookie('activeTab', tabs.find('.active').prop('id').charAt(0), 1);
        });
        //END REMEMBER TAB

        //BEGIN TABLE SORTING
        $("#myInputNormal").on("keyup", function () {
            var value = $(this).val().toLowerCase();
            $("#myTableNormal tr").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });

        $("#myInputApproved").on("keyup", function () {
            var value = $(this).val().toLowerCase();
            $("#myTableApproved tr").filter(function () {
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

            var divToPrint = $("#certificateTable").clone();
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

        //BEGIN REMOVE COOKIES
        $('.removeCookieClass').click(function () {
            var cookieID = $(this).prop('id');
            eraseCookie(cookieID);
        });
        //END REMOVE COOKIES


        //BEGIN APPROVE CERTIFICATE
        //Filling Modal ( Approve Certificate )
        $('.approveCertificateButton').on('click', function () {
            $('#approveCertificateID').val($(this).prop('id'));
            $('#approveCertificateWarning').text('Are you sure you want to approve the certificate #' + $(this).prop('id') + ' ?');
        });
        //Confirm Warning ( Approve Certificate )
        $('.closeApproveCertificateModal').click(function () {
            var $newMdl = $('#warnApprove');
            $newMdl.modal('hide');
            var $submitBtn = $("#approveCertificateForm").find(':submit');
            var $cancelBtn = $('.closeApproveCertificateModal');
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

            $('#approveCertificateID').val('').text('');
            $('#approveCertificateWarning').val('').text('');
        });
        //Accept Process ( Approve Certificate )
        $('#approveCertificateForm').submit(function () {
            var $submitBtn = $(this).find(':submit');
            var $cancelBtn = $('.closeApproveCertificateModal');
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
        //END APPROVE CERTIFICATE

        //BEGIN REMOVE CERTIFICATE
        //Filling Modal ( Remove Certificate )
        $('.removeCertificateButton').on('click', function () {
            $('#removeCertificateID').val($(this).prop('id'));
            $('#removeCertificateWarning').text('Are you sure you want to delete the draft certificate #' + $(this).prop('id') + ' ?');
        });
        //Confirm Warning ( Remove Certificate )
        $('.closeRemoveCertificateModal').click(function () {
            var $newMdl = $('#warnRemove');
            $newMdl.modal('hide');
            var $submitBtn = $("#removeCertificateForm").find(':submit');
            var $cancelBtn = $('.closeRemoveCertificateModal');
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

            $('#removeCertificateID').val('').text('');
            $('#removeCertificateWarning').val('').text('');
        });
        //Accept Process ( Remove Certificate )
        $('#removeCertificateForm').submit(function () {
            var $submitBtn = $(this).find(':submit');
            var $cancelBtn = $('.closeRemoveCertificateModal');
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
        //END REMOVE CERTIFICATE


    });

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