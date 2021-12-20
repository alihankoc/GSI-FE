<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 14.02.2019
 * Time: 02:54
 */
include 'vendor/autoload.php';
$dotenv =\Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

include_once "url_slug.php";
include_once "ApiCaller.php";

session_start();
date_default_timezone_set('Europe/Istanbul');

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
    <script type="text/javascript" src="js/tinymce/tinymce.min.js"></script>
    <script type="text/javascript" src="js/certificateTemplates/NewCertificates.js"></script>
    <script type="text/javascript">
        tinymce.init({
            selector: 'textarea#certificateEditor',
            plugins: 'print preview powerpaste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons',
            imagetools_cors_hosts: ['picsum.photos'],
            menubar: 'file edit view insert format tools table help',
            toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl',
            toolbar_sticky: true,
            autosave_ask_before_unload: true,
            autosave_interval: '30s',
            autosave_prefix: '{path}{query}-{id}-',
            autosave_restore_when_empty: false,
            autosave_retention: '2m',
            image_advtab: true,
            link_list: [
                {title: 'My page 1', value: 'https://www.tiny.cloud'},
                {title: 'My page 2', value: 'http://www.moxiecode.com'}
            ],
            image_list: [
                {title: 'My page 1', value: 'https://www.tiny.cloud'},
                {title: 'My page 2', value: 'http://www.moxiecode.com'}
            ],
            image_class_list: [
                {title: 'None', value: ''},
                {title: 'Some class', value: 'class-name'}
            ],
            importcss_append: true,
            file_picker_callback: function (callback, value, meta) {
                /* Provide file and text for the link dialog */
                if (meta.filetype === 'file') {
                    callback('https://www.google.com/logos/google.jpg', {text: 'My text'});
                }

                /* Provide image and alt text for the image dialog */
                if (meta.filetype === 'image') {
                    callback('https://www.google.com/logos/google.jpg', {alt: 'My alt text'});
                }

                /* Provide alternative source and posted for the media dialog */
                if (meta.filetype === 'media') {
                    callback('movie.mp4', {source2: 'alt.ogg', poster: 'https://www.google.com/logos/google.jpg'});
                }
            },
            templates: [
                {title: 'Evacuation Weight Concern', description: 'Evacuation Weight Concern', content:EvacuationWeightConcern },
                {title: 'Evacuation Sampling Concern', description: 'Evacuation Sampling Concern', content:EvacuationSamplingConcern },
                {title: 'Evacuation Weight', description: 'Evacuation Weight', content:EvacuationWeight },
                {title: 'Hold Cleanliness Concern', description: 'Hold Cleanliness Concern', content:HoldCleanlinessConcern },
                {title: 'Non Fumigation Concern', description: 'Non Fumigation Concern', content:NonFumigationConcern },
                {title: 'Co Treatment Concercn', description: 'Co Treatment Concercn', content:CoTreatmentConcercn },
                {title: 'Aflatoxin Concern', description: 'Aflatoxin Concern', content:AflatoxinConcern },
                {title: 'Pesticides Concern', description: 'Pesticides Concern', content:PesticidesConcern },
                {title: 'Salmonella Free Concern', description: 'Salmonella Free Concern', content:SalmonellaFreeConcern },
                {title: 'Heavy Metals Concern', description: 'Heavy Metals Concern', content:HeavyMetalsConcern },
                {title: 'Non Gmo Concern', description: 'Non Gmo Concern', content:NonGmoConcern },
                {title: 'Quality Concern', description: 'Quality Concern', content:QualityConcern },
                {title: 'Non Radioactivity Concern', description: 'Non Radioactivity Concern', content:NonRadioactivityConcern },
                {title: 'Analysis Report Concern', description: 'Analysis Report Concern', content:AnalysisReportConcern },
                {title: 'Non Dioxin(Russia)', description: 'Non Dioxin-Russia', content:NonDioxin },
                {title: 'Pesticides Per Euro(Russia)', description: 'Pesticides Per Euro-Russia', content:PesticidesPerEuro },
                {title: 'Veterinary(Russia)', description: 'Veterinary-Russia', content:Veterinary },
                {title: 'Analysis Oil(Russia)', description: 'Analysis Oil-Russia', content:AnalysisOil },
                {title: 'DeclarationStatement(Russia)', description: 'DeclarationStatement-Russia', content:DeclarationStatement },
                {title: 'Fit For Animal Consumption(Russia)', description: 'Fit For Animal Consumption-Russia', content:FitForAnimalConsumption },
                {title: 'Health(Russia)', description: 'Health-Russia', content:Health },
                {title: 'Load Compartment Inspection Certificate(Russia)', description: 'Load Compartment Inspection Certificate-Russia', content:LoadCompartmentInspectionCertificate },
                {title: 'Radioactivity(Russia)', description: 'Radioactivity-Russia', content:Radioactivity },
                {title: 'Salmonella EColli(Russia)', description: 'Salmonella EColli-Russia', content:SalmonellaEColli },
                {title: 'Starch(Russia)', description: 'Starch-Russia', content:Starch },
                {title: 'Urea(Russia)', description: 'Urea-Russia', content:Urea },
                {title: 'Fumigation(Romania)', description: 'Fumigation-Romania', content:FumigationRomania },
                {title: 'Fumigation(Hungarian)', description: 'Fumigation-Hungarian', content:FumigationHungarian },
                {title: 'FumigationCorn(Romania)', description: 'Fumigation-RomaniaCorn', content:FumigationCornRomania },
                {title: 'Fumigation(Slovacia)', description: 'Fumigation-Slovacia', content:FumigationSlovacia }

            ],
            template_cdate_format: '[Date Created (CDATE): %m/%d/%Y : %H:%M:%S]',
            template_mdate_format: '[Date Modified (MDATE): %m/%d/%Y : %H:%M:%S]',
            height: 800,
            image_caption: true,
            quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
            noneditable_noneditable_class: 'mceNonEditable',
            toolbar_mode: 'sliding',
            contextmenu: 'link image imagetools table',
        });
    </script>
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
        <h4>New Certificate</h4>
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
            <a href="certificates.php" class="btn btn-outline-primary btn-sm float-right"
               style="margin-right: 5px;"><i
                        class="fas fa-list"></i> Show Certificates
            </a>
        </div>
        <div class="card-body">

            <form id="newCertificate" name="newCertificate" action="certificate_operations.php"
                  method="post">

                <div class="row">
                    <div class="form-group col-md-6">
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="certificateType1" name="certificateType" class="custom-control-input" value="tr" required>
                            <label class="custom-control-label" for="certificateType1">Turkey</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="certificateType2" name="certificateType" class="custom-control-input" value="ru">
                            <label class="custom-control-label" for="certificateType2">Russia</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="certificateType3" name="certificateType" class="custom-control-input" value="rom">
                            <label class="custom-control-label" for="certificateType3">Romania</label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="certificateNo">Certificate No:</label>
                        <input class="form-control" type="text" name="certificateNo" id="certificateNo" required/>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="certificateJobName">Job Name/Location</label>
                        <input class="form-control" type="text" name="certificateJobName" id="certificateJobName"
                               required/>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="certificateCustomer">Customer:</label>
                        <input class="form-control" type="text" name="certificateCustomer" id="certificateCustomer" required/>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="certificateNote">Note (Optional):</label>
                        <input class="form-control" type="text" name="certificateNote" id="certificateNote"/>
                    </div>
                </div>

                <div class="form-group">
                    <label for="certificateEditor" style="font-weight: 500;">Optionals:</label>
                    <textarea id="certificateEditor" name="certificateEditor"></textarea>
                </div>

                <input type="submit" class="btn btn-primary" name="saveCertificate" id="saveCertificate"
                       value="Generate"/>

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
        $("#myInputCertificate").on("keyup", function () {
            var value = $(this).val().toLowerCase();
            $("#myTableCertificate tr").filter(function () {
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