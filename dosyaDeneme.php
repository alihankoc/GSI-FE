<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 10.07.2019
 * Time: 21:21
 */
session_start();
date_default_timezone_set('Asia/Istanbul');

include_once "url_slug.php";


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
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <title><?php echo url_slug($_SERVER["PHP_SELF"]); ?></title>

    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.min.css" crossorigin="anonymous">


    <link rel="stylesheet" type="text/css" href="css/custom.css">
    <link rel="stylesheet" type="text/css" href="css/fontawesome.min.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap4-toggle.css">
    <link rel="stylesheet" type="text/css" href="css/select2.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap-select.css">
    <link rel="stylesheet" type="text/css" href="plugins/kartik-v-fileinput/css/fileinput.min.css">
    <script type="text/javascript" src="js/jquery-3.3.1.js"></script>
    <script type="text/javascript" src="js/popper.js"></script>

    <script type="text/javascript" src="plugins/kartik-v-fileinput/js/plugins/piexif.min.js"></script>
    <script type="text/javascript" src="plugins/kartik-v-fileinput/js/plugins/sortable.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="plugins/kartik-v-fileinput/js/fileinput.min.js"></script>
    <script type="text/javascript" src="plugins/kartik-v-fileinput/js/locales/tr.js"></script>


</head>
<body>
<div class="container">
    <!--<form action="fileUpload.php" method="post" name="fileUploadForm" id="fileUploadForm" enctype="multipart/form-data">
        <div class="custom-file">
            <input type="file" class="custom-file-input" id="customFile" name="myFile">
            <label class="custom-file-label" for="customFile">Choose file</label>
        </div>
        <input class="btn btn-primary" type="submit" name="sendFileButton" id="sendFileButton" value="Send"/>
    </form>-->

    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
        Launch demo modal
    </button>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-0 m-0">
                    <form action="fileUpload.php" method="post" name="fileUploadForm" id="fileUploadForm" enctype="multipart/form-data">
                        <input id="myFile" name="myFile[]" type="file" multiple>
                        <input class="btn btn-primary" type="submit" name="sendFileButton" id="sendFileButton" value="Send"/>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>



</div>
<script>
    $(document).ready(function() {
        $("#myFile").fileinput({
            maxFileCount: 10,
            showRemove: true,
            showUpload: false,
            initialPreviewShowDelete: true,
            allowedFileExtensions: ["jpg", "png"]
        });
    });
</script>
</body>
</html>
