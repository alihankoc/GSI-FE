<?php

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
    case "2":
        header('Location: officer_main_page.php');
        break;
    case "3":
        header('Location: field_operation_page.php');
        break;
}

$apiCaller = new ApiCaller('1', $_SESSION['token']);

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["singleLaboratoryFormID"]) && !empty($_GET["singleLaboratoryFormID"])) {
    if (is_numeric($_GET["singleSurveillanceFormID"])) {
        $survForm = $apiCaller->sendRequest(array(
            'api_url' => $_ENV['LINK'].'viewSingleLaboratoryFormOfficer/' . $_GET["singleLaboratoryFormID"],
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

        <div class="container mt-3 pt-3" style="font-size: 14px;">
            <div class="d-flex flex-row justify-content-center">
                <img src="img/general-survey-gozetme-ltd-sti.png" alt="Logo" style="width:200px;">
            </div>
            <div class="d-flex flex-row justify-content-center mt-3 pt-3">
                <h3>Laboratory Form</h3>
            </div>
            <div class="row">
                <div class="col-12 border">
                    <h5>Operation Informations</h5>
                </div>
            </div>
            <div class="row">
                <div class="col-2 border">
                    <b>Operation ID</b>
                </div>
                <div class="col-2 border">
                    <?php echo "GSI".$survForm['data']->operation->operation_id; ?>
                </div>
                <div class="col-2 border">
                    <b>Form ID</b>
                </div>
                <div class="col-2 border">
                    <?php echo $survForm['data']->info_form_id; ?>
                </div>
                <div class="col-2 border">
                    <b>Laborant</b>
                </div>
                <div class="col-2 border">
                    <?php echo $survForm['data']->user->name . " " . $survForm['data']->user->surname; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-2 border">
                    <b>Customer</b>
                </div>
                <div class="col-4 border">
                    <?php echo $survForm['data']->operation->customer->company_name; ?>
                </div>
                <div class="col-2 border">
                    <b>Nomination Customer</b>
                </div>
                <div class="col-4 border">
                    <?php
                    if ($survForm['data']->operation->is_double_nomination == 1) {
                        echo $survForm['data']->operation->nomination_customer->company_name;
                    } else {
                        echo "-";
                    }
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-2 border">
                    <b>Vessel Name</b>
                </div>
                <div class="col-2 border">
                    <?php echo $survForm['data']->operation->vessel_name; ?>
                </div>
                <div class="col-2 border">
                    <b>Type Of Goods</b>
                </div>
                <div class="col-2 border">
                    <?php echo $survForm['data']->operation->type_of_goods; ?>
                </div>
                <div class="col-2 border">
                    <b>Amount</b>
                </div>
                <div class="col-2 border">
                    <?php echo $survForm['data']->operation->amount; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-2 border">
                    <b>Inspection Location</b>
                </div>
                <div class="col-10 border">
                    <?php
                    $inspectionLocationsString = "";
                    foreach ($survForm['data']->operation->offices as $office) {
                        $inspectionLocationsString .= $office->office_name . ", ";
                    }
                    $inspectionLocationsString = substr($inspectionLocationsString, 0, -2);
                    echo $inspectionLocationsString;
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-2 border"><b>Buyer</b></div>
                <div class="col-2 border"><?php echo $survForm['data']->operation->buyer; ?></div>
                <div class="col-2 border"><b>Seller</b></div>
                <div class="col-2 border"><?php echo $survForm['data']->operation->seller; ?></div>
                <div class="col-2 border"><b>Supplier</b></div>
                <div class="col-2 border"><?php echo $survForm['data']->operation->supplier; ?></div>
            </div>
            <div class="row">
                <div class="col-2 border"><b>Surveillance Types</b></div>
                <div class="col-2 border">
                    <?php
                    $survTypesString = "";
                    foreach ($survForm['data']->operation->surveillance_types as $surveillance_type) {
                        $survTypesString .= $surveillance_type->surveillance_type_name . ", ";
                    }
                    $survTypesString = substr($survTypesString, 0, -2);
                    echo $survTypesString;
                    ?>
                </div>
                <div class="col-2 border"><b>Process Types</b></div>
                <div class="col-2 border">
                    <?php
                    $procTypesString = "";
                    foreach ($survForm['data']->operation->process_types as $process_type) {
                        $procTypesString .= $process_type->process_type_name . ", ";
                    }
                    $procTypesString = substr($procTypesString, 0, -2);
                    echo $procTypesString;
                    ?>
                </div>
                <div class="col-2 border"><b>Analysis Conditions</b></div>
                <div class="col-2 border">
                    <?php
                    $anlCondString = "";
                    foreach ($survForm['data']->operation->analysis_conditions as $analysis_condition) {
                        $anlCondString .= $analysis_condition->analysis_condition_name . ", ";
                    }
                    $anlCondString = substr($anlCondString, 0, -2);
                    echo $anlCondString;
                    ?>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-12 border">
                    <h5>Requested Surveillance</h5>
                </div>
            </div>
            <div class="row">
                <?php
                foreach ($survForm['data']->operation->surveillances as $key => $val) {
                    ?>
                    <div class="col-2 border">
                        <b><?php echo $val->surveillance_name; ?></b>
                    </div>
                    <div class="col-4 border">
                        <?php
                        if ($survForm['data']->done_surveillances[$key]->is_completed == 1) {
                            echo "Completed - " . date("d-m-Y H:i:s", strtotime($survForm['data']->done_surveillances[$key]->updated_at));
                        } else {
                            echo "Not Completed - " . date("d-m-Y H:i:s", strtotime($survForm['data']->done_surveillances[$key]->updated_at));
                        }
                        ?>
                    </div>
                    <?php
                }
                ?>
            </div>
            <br>
            <div class="row">
                <div class="col-6 border">
                    <h5>Used Equipment</h5>
                </div>
                <div class="col-6 border">
                    <?php echo $survForm['data']->equipment_type->equipment_type_name; ?>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-12 border">
                    <h5>Surveillance Notes</h5>
                </div>
            </div>
            <div class="row">
                <div class="col-2 border">
                    <b>Vessel Arrival Date</b>
                </div>
                <div class="col-4 border">
                    <?php echo ($survForm['data']->vessel_arrival_date != null) ? date("d-m-Y H:i:s", strtotime($survForm['data']->vessel_arrival_date)) : "-"; ?>
                </div>
                <div class="col-2 border">
                    <b>Vessel Land Date</b>
                </div>
                <div class="col-4 border">
                    <?php echo ($survForm['data']->vessel_land_date != null) ? date("d-m-Y H:i:s", strtotime($survForm['data']->vessel_land_date)) : "-"; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-2 border">
                    <b>Cleaning/Suitability Date</b>
                </div>
                <div class="col-4 border">
                    <?php echo ($survForm['data']->cleaning_suitability_date != null) ? date("d-m-Y H:i:s", strtotime($survForm['data']->cleaning_suitability_date)) : "-"; ?>
                </div>
                <div class="col-2 border">
                    <b>Beginning Draft Date</b>
                </div>
                <div class="col-4 border">
                    <?php echo ($survForm['data']->beginning_draft_date != null) ? date("d-m-Y H:i:s", strtotime($survForm['data']->beginning_draft_date)) : "-"; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-2 border">
                    <b>Intermediary Draft Date</b>
                </div>
                <div class="col-4 border">
                    <?php echo ($survForm['data']->middle_draft_date != null) ? date("d-m-Y H:i:s", strtotime($survForm['data']->middle_draft_date)) : "-"; ?>
                </div>
                <div class="col-2 border">
                    <b>Final Draft Date</b>
                </div>
                <div class="col-4 border">
                    <?php echo ($survForm['data']->final_draft_date != null) ? date("d-m-Y H:i:s", strtotime($survForm['data']->final_draft_date)) : "-"; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-2 border">
                    <b>Load/Landing Start Date</b>
                </div>
                <div class="col-4 border">
                    <?php echo ($survForm['data']->load_landing_start_date != null) ? date("d-m-Y H:i:s", strtotime($survForm['data']->load_landing_start_date)) : "-"; ?>
                </div>
                <div class="col-2 border">
                    <b>Load/Landing End Date</b>
                </div>
                <div class="col-4 border">
                    <?php echo ($survForm['data']->load_landing_finish_date != null) ? date("d-m-Y H:i:s", strtotime($survForm['data']->load_landing_finish_date)) : "-"; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-2 border">
                    <b>Fumigation Start Date</b>
                </div>
                <div class="col-4 border">
                    <?php echo ($survForm['data']->fumigation_start_date != null) ? date("d-m-Y H:i:s", strtotime($survForm['data']->fumigation_start_date)) : "-"; ?>
                </div>
                <div class="col-2 border">
                    <b>Fumigation End Date</b>
                </div>
                <div class="col-4 border">
                    <?php echo ($survForm['data']->fumigation_finish_date != null) ? date("d-m-Y H:i:s", strtotime($survForm['data']->fumigation_finish_date)) : "-"; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-2 border">
                    <b>Warehouse Sealing Date</b>
                </div>
                <div class="col-4 border">
                    <?php echo ($survForm['data']->warehouse_sealing_date != null) ? date("d-m-Y H:i:s", strtotime($survForm['data']->warehouse_sealing_date)) : "-"; ?>
                </div>
                <div class="col-2 border">
                    <b>Warehouse Removal Date</b>
                </div>
                <div class="col-4 border">
                    <?php echo ($survForm['data']->warehouse_removal_date != null) ? date("d-m-Y H:i:s", strtotime($survForm['data']->warehouse_removal_date)) : "-"; ?>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-12 border">
                    <h5>Results & Differences</h5>
                </div>
            </div>
            <div class="row">
                <div class="col-2 border">
                    <b>Weighing Result</b>
                </div>
                <div class="col-4 border">
                    <?php echo ($survForm['data']->weighing_result != null) ? $survForm['data']->weighing_result : "-"; ?>
                </div>
                <div class="col-2 border">
                    <b>Difference (+/-)</b>
                </div>
                <div class="col-4 border">
                    <?php echo ($survForm['data']->weighing_difference != null) ? $survForm['data']->weighing_difference : "-"; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-2 border">
                    <b>Ship Draft/Tanker Ullage Survey Result</b>
                </div>
                <div class="col-4 border">
                    <?php echo ($survForm['data']->vessel_ullage_result != null) ? $survForm['data']->vessel_ullage_result : "-"; ?>
                </div>
                <div class="col-2 border">
                    <b>Difference (+/-)</b>
                </div>
                <div class="col-4 border">
                    <?php echo ($survForm['data']->vessel_ullage_difference != null) ? $survForm['data']->vessel_ullage_difference : "-"; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-2 border">
                    <b>Vehicle/Piece Counting Result</b>
                </div>
                <div class="col-4 border">
                    <?php echo ($survForm['data']->piece_count_result != null) ? $survForm['data']->piece_count_result : "-"; ?>
                </div>
                <div class="col-2 border">
                    <b>Difference (+/-)</b>
                </div>
                <div class="col-4 border">
                    <?php echo ($survForm['data']->piece_count_difference != null) ? $survForm['data']->piece_count_difference : "-"; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-2 border">
                    <b>Shore Tank Ullage Survey Result</b>
                </div>
                <div class="col-4 border">
                    <?php echo ($survForm['data']->shore_ullage_result != null) ? $survForm['data']->shore_ullage_result : "-"; ?>
                </div>
                <div class="col-2 border">
                    <b>Difference (+/-)</b>
                </div>
                <div class="col-4 border">
                    <?php echo ($survForm['data']->shore_ullage_difference != null) ? $survForm['data']->shore_ullage_difference : "-"; ?>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-12 border">
                    <h5>Remarks</h5>
                </div>
            </div>
            <div class="row">
                <div class="col-12 border">
                    <?php echo ($survForm['data']->remarks != null) ? $survForm['data']->remarks : "-"; ?>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-6 border">
                    <b>Inspector Name/Surname/Sign/Date</b>
                </div>
                <div class="col-6 border">
                    <b>Specialist Name/Surname/Sign/Date</b>
                </div>
            </div>
            <div class="row">
                <div class="col-6 border">
                    <br>
                    <br>
                </div>
                <div class="col-6 border">
                    <br>
                    <br>
                </div>
            </div>


        </div>

        </body>
        </html>
        <?php
    }
}
?>