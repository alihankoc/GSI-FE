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

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["singleLabFormID"]) && !empty($_GET["singleLabFormID"])) {
    if (is_numeric($_GET["singleLabFormID"])) {
        $labForm = $apiCaller->sendRequest(array(
            'api_url' => $_ENV['LINK'].'viewSingleLaboratoryFormOfficer/' . $_GET["singleLabFormID"],
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
        <!-- <pre>
        <?php print_r($labForm['data']->operation->laboratory_forms); ?>
        </pre> -->
        <div class="container mt-3 pt-3" style="font-size: 14px;">
            <div class="d-flex flex-row justify-content-center">
                <img src="img/general-survey-gozetme-ltd-sti.png" alt="Logo" style="width:200px;">
            </div>
            <div class="d-flex flex-row justify-content-center mt-3 pt-3">
                <h3>Laboratory Info Form</h3>
            </div>
            <div class="row">
                <div class="col-12 border">
                    <h5>Operation Informations</h5>
                </div>
            </div>
            <div class="row">
                <div class="col-3 border">
                    <b>Operation ID</b>
                </div>
                <div class="col-3 border">
                    <?php  echo "GSI".$labForm['data']->operation->operation_id; ?>
                </div>
                <div class="col-3 border">
                    <b>Form ID</b>
                </div>
                <div class="col-3 border">
                    <?php echo $labForm['data']->lab_form_id; ?>
                </div>
                <div class="col-3 border">
                    <b>Creator</b>
                </div>
                <div class="col-3 border">
                    <?php echo $labForm['data']->creator->name ?? '- '; ?>
                    <?php echo $labForm['data']->creator->surname ?? '- '; ?>
                </div>
                <div class="col-3 border">
                    <b>Completer</b>
                </div>
                <div class="col-3 border">
                <?php echo $labForm['data']->completer->name ?? '- '; ?>
                    <?php echo $labForm['data']->completer->surname ?? '- '; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-2 border">
                    <b>Customer</b>
                </div>
                <div class="col-4 border">
                    <?php echo $labForm['data']->operation->customer->company_name; ?>
                </div>
                <div class="col-2 border">
                    <b>Nomination Customer</b>
                </div>
                <div class="col-4 border">
                    <?php
                    if ($labForm['data']->operation->is_double_nomination == 1) {
                        echo $labForm['data']->operation->nomination_customer->company_name;
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
                    <?php echo $labForm['data']->operation->vessel_name; ?>
                </div>
                <div class="col-2 border">
                    <b>Type Of Goods</b>
                </div>
                <div class="col-2 border">
                    <?php echo $labForm['data']->operation->type_of_goods; ?>
                </div>
                <div class="col-2 border">
                    <b>Amount</b>
                </div>
                <div class="col-2 border">
                    <?php echo $labForm['data']->operation->amount; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-2 border">
                    <b>Inspection Location</b>
                </div>
                <div class="col-10 border">
                    <?php
                    $inspectionLocationsString = "";
                    foreach ($labForm['data']->operation->offices as $office) {
                        $inspectionLocationsString .= $office->office_name . ", ";
                    }
                    $inspectionLocationsString = substr($inspectionLocationsString, 0, -2);
                    echo $inspectionLocationsString;
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-2 border"><b>Buyer</b></div>
                <div class="col-2 border"><?php echo $labForm['data']->operation->buyer; ?></div>
                <div class="col-2 border"><b>Seller</b></div>
                <div class="col-2 border"><?php echo $labForm['data']->operation->seller; ?></div>
                <div class="col-2 border"><b>Supplier</b></div>
                <div class="col-2 border"><?php echo $labForm['data']->operation->supplier; ?></div>
            </div>
            <div class="row">
                <div class="col-2 border"><b>Surveillance Types</b></div>
                <div class="col-2 border">
                    <?php
                    $survTypesString = "";
                    foreach ($labForm['data']->operation->surveillance_types as $surveillance_type) {
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
                    foreach ($labForm['data']->operation->process_types as $process_type) {
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
                    foreach ($labForm['data']->operation->analysis_conditions as $analysis_condition) {
                        $anlCondString .= $analysis_condition->analysis_condition_name . ", ";
                    }
                    $anlCondString = substr($anlCondString, 0, -2);
                    echo $anlCondString;
                    ?>
                </div>
            </div>
            <br>
            <?php
                foreach ($labForm['data']->operation->laboratory_forms as $form) {
                    ?>

                <div class="row">
                    <div class="col-12 border">
                        <h5>Requested Analysis for Form Id: <?php echo $form->lab_form_id; ?></h5>
                    </div>
                    <?php
                    foreach ($form->analyzes as $analy) {
                    ?>
                    <div class="col-2 border">
                        <b><?php echo $analy->analysis_name; ?></b>
                    </div>
                    <div class="col-2 border">
                    <?php
                        $key = array_search($analy->analysis_id, array_column($form->done_analysis, 'analysis_id'));
                        $result = (is_int($key) && $key > -1) ? $form->done_analysis[$key]->pivot->result : ' - ';
                        $key = null;
                        echo '<b>Spec:</b> (' .  $analy->pivot->spec_info   .   '), ';
                    ?>
                    </div>
                    <?php
                    }
                    ?>
                </div>
                <br>

                <div class="row">
                    <div class="col-12 border">
                        <h5>Done Analysis for Form Id: <?php echo $form->lab_form_id; ?></h5>
                    </div>
                    <?php
                    foreach ($form->analyzes as $analy) {
                    ?>
                    <div class="col-2 border">
                        <b><?php echo $analy->analysis_name; ?></b>
                    </div>
                    <?php 
                        $key = array_search($analy->analysis_id, array_column($form->done_analysis, 'analysis_id'));
                        $result = (is_int($key) && $key > -1) ? $form->done_analysis[$key]->pivot->result : ' - ';
                        $is_subcontractor = (is_int($key) && $key > -1) ? $form->done_analysis[$key]->pivot->is_subcontractor : ' - ';
                        $subcontractor_name = (is_int($key) && $key > -1) ? $form->done_analysis[$key]->pivot->subcontractor_name : ' - ';
                        $subcontractor_send_date = (is_int($key) && $key > -1) ? $form->done_analysis[$key]->pivot->subcontractor_send_date : ' - ';
                        $remarks = (is_int($key) && $key > -1) ? $form->done_analysis[$key]->pivot->remarks : ' -';
                        $analysis_demand_date = (is_int($key) && $key > -1) ? $form->done_analysis[$key]->pivot->analysis_demand_date : ' - ';
                        $analysis_start_date = (is_int($key) && $key > -1) ? $form->done_analysis[$key]->pivot->analysis_start_date : ' - ';
                        $analysis_end_date = (is_int($key) && $key > -1) ? $form->done_analysis[$key]->pivot->analysis_end_date : ' - ';
                        
                    ?>
                    <div class="col-10">
                        <div class="row">
                            <div class="col-6 border">
                            <?php
                                echo '<b>Spec:</b> (' .  $analy->pivot->spec_info   .   '), ';
                            ?>
                            </div>
                            <div class="col-6 border">
                            <?php
                                echo '<b>Is subcontractor:</b> ';
                                echo $is_subcontractor === 1 ? 'Yes, ' : 'No, ';
                            ?>
                            </div>
                            <div class="col-6 border">
                            <?php
                                echo '<b>Subcontractor Name:</b> '. $subcontractor_name ?? '-, ';
                            ?>
                            </div>
                            <div class="col-6 border">
                            <?php
                                echo '<b>Subcontractor Send Date: </b>'. $subcontractor_send_date .   ', ' ;
                            ?>
                            </div>
                            <div class="col-6 border">
                            <?php
                                echo '<b>Remarks:</b> '. $remarks .   ', ' ;
                            ?>
                            </div>
                            <div class="col-6 border">
                            <?php
                                echo '<b>Analysis Demand Date:</b> '. $analysis_demand_date .   ', ' ;
                            ?>
                            </div>
                            <div class="col-6 border">
                            <?php
                               echo '<b>Analysis Start Date:</b> '. $analysis_start_date .   ', ' ;
                            ?>
                            </div>
                            <div class="col-6 border">
                            <?php
                               echo '<b>Analysis End Date:</b> '. $analysis_end_date  ;
                            ?>
                            </div>
                            <div class="col-2 border">
                            <?php
                               echo '<b>Result 1:</b> <br>'. ((is_int($key) && $key > -1) ? ($form->done_analysis[$key]->pivot->result && $form->done_analysis[$key]->pivot->result !== '' ?  $form->done_analysis[$key]->pivot->result : '-') : ' - ');
                            ?>
                            </div>
                            <div class="col-2 border">
                            <?php
                               echo '<b>Result 2:</b> <br>'. ((is_int($key) && $key > -1) ? ($form->done_analysis[$key]->pivot->result_2 && $form->done_analysis[$key]->pivot->result_2 !== '' ?  $form->done_analysis[$key]->pivot->result_2 : '-') : ' - ');
                            ?>
                            </div>
                            <div class="col-2 border">
                            <?php
                               echo '<b>Result 3:</b> <br>'. ((is_int($key) && $key > -1) ? ($form->done_analysis[$key]->pivot->result_3 && $form->done_analysis[$key]->pivot->result_3 !== '' ?  $form->done_analysis[$key]->pivot->result_3 : '-') : ' - ');
                            ?>
                            </div>
                            <div class="col-2 border">
                            <?php
                               echo '<b>Result 4:</b> <br>'. ((is_int($key) && $key > -1) ? ($form->done_analysis[$key]->pivot->result_4 && $form->done_analysis[$key]->pivot->result_4 !== '' ?  $form->done_analysis[$key]->pivot->result_4 : '-') : ' - ');
                            ?>
                            </div>
                            <div class="col-2 border">
                            <?php
                               echo '<b>Result 5:</b> <br>'. ((is_int($key) && $key > -1) ? ($form->done_analysis[$key]->pivot->result_5 && $form->done_analysis[$key]->pivot->result_5 !== '' ?  $form->done_analysis[$key]->pivot->result_5 : '-') : ' - ');
                            ?>
                            </div>
                            <div class="col-2 border">
                            <?php
                               echo '<b>Result 6:</b> <br>'. ((is_int($key) && $key > -1) ? ($form->done_analysis[$key]->pivot->result_6 && $form->done_analysis[$key]->pivot->result_6 !== '' ?  $form->done_analysis[$key]->pivot->result_6 : '-') : ' - ');
                            ?>
                            </div>
                            <div class="col-2 border">
                            <?php
                               echo '<b>Result 7:</b> <br>'. ((is_int($key) && $key > -1) ? ($form->done_analysis[$key]->pivot->result_7 && $form->done_analysis[$key]->pivot->result_7 !== '' ?  $form->done_analysis[$key]->pivot->result_7 : '-') : ' - ');
                            ?>
                            </div>
                            <div class="col-2 border">
                            <?php
                               echo '<b>Result 8:</b> <br>'. ((is_int($key) && $key > -1) ? ($form->done_analysis[$key]->pivot->result_8 && $form->done_analysis[$key]->pivot->result_8 !== '' ?  $form->done_analysis[$key]->pivot->result_8 : '-') : ' - ');
                            ?>
                            </div>
                            <div class="col-2 border">
                            <?php
                               echo '<b>Result 9:</b> <br>'. ((is_int($key) && $key > -1) ? ($form->done_analysis[$key]->pivot->result_9 && $form->done_analysis[$key]->pivot->result_9 !== '' ?  $form->done_analysis[$key]->pivot->result_9 : '-') : ' - ');
                            ?>
                            </div>
                            <div class="col-2 border">
                            <?php
                               echo '<b>Result 10:</b> <br>'. ((is_int($key) && $key > -1) ? ($form->done_analysis[$key]->pivot->result_10 && $form->done_analysis[$key]->pivot->result_10 !== '' ?  $form->done_analysis[$key]->pivot->result_10 : '-') : ' - ');
                            ?>
                            </div>
                            <div class="col-2 border">
                            <?php
                               echo '<b>Result 11:</b> <br>'. ((is_int($key) && $key > -1) ? ($form->done_analysis[$key]->pivot->result_11 && $form->done_analysis[$key]->pivot->result_11 !== '' ?  $form->done_analysis[$key]->pivot->result_11 : '-') : ' - ');
                            ?>
                            </div>
                            <div class="col-2 border">
                            <?php
                               echo '<b>Result 12:</b> <br>'. ((is_int($key) && $key > -1) ? ($form->done_analysis[$key]->pivot->result_12 && $form->done_analysis[$key]->pivot->result_12 !== '' ?  $form->done_analysis[$key]->pivot->result_12 : '-') : ' - ');
                            ?>
                            </div>
                            <div class="col-2 border">
                            <?php
                               echo '<b>Result 13:</b> <br>'. ((is_int($key) && $key > -1) ? ($form->done_analysis[$key]->pivot->result_13 && $form->done_analysis[$key]->pivot->result_13 !== '' ?  $form->done_analysis[$key]->pivot->result_13 : '-') : ' - ');
                            ?>
                            </div>
                            <div class="col-2 border">
                            <?php
                               echo '<b>Result 14:</b> <br>'. ((is_int($key) && $key > -1) ? ($form->done_analysis[$key]->pivot->result_14 && $form->done_analysis[$key]->pivot->result_14 !== '' ?  $form->done_analysis[$key]->pivot->result_14 : '-') : ' - ');
                            ?>
                            </div>
                            <div class="col-2 border">
                            <?php
                               echo '<b>Result 15:</b> <br>'. ((is_int($key) && $key > -1) ? ($form->done_analysis[$key]->pivot->result_15 && $form->done_analysis[$key]->pivot->result_15 !== '' ?  $form->done_analysis[$key]->pivot->result_15 : '-') : ' - ');
                            ?>
                            </div>
                            <div class="col-2 border">
                            <?php
                               echo '<b>Result 16:</b> <br>'. ((is_int($key) && $key > -1) ? ($form->done_analysis[$key]->pivot->result_16 && $form->done_analysis[$key]->pivot->result_16 !== '' ?  $form->done_analysis[$key]->pivot->result_16 : '-') : ' - ');
                            ?>
                            </div>
                            <div class="col-2 border">
                            <?php
                               echo '<b>Result 17:</b> <br>'. ((is_int($key) && $key > -1) ? ($form->done_analysis[$key]->pivot->result_17 && $form->done_analysis[$key]->pivot->result_17 !== '' ?  $form->done_analysis[$key]->pivot->result_17 : '-') : ' - ');
                            ?>
                            </div>
                            <div class="col-2 border">
                            <?php
                               echo '<b>Result 18:</b> <br>'. ((is_int($key) && $key > -1) ? ($form->done_analysis[$key]->pivot->result_18 && $form->done_analysis[$key]->pivot->result_18 !== '' ?  $form->done_analysis[$key]->pivot->result_18 : '-') : ' - ');
                            ?>
                            </div>
                            <div class="col-2 border">
                            <?php
                               echo '<b>Result 19:</b> <br>'. ((is_int($key) && $key > -1) ? ($form->done_analysis[$key]->pivot->result_19 && $form->done_analysis[$key]->pivot->result_19 !== '' ?  $form->done_analysis[$key]->pivot->result_19 : '-') : ' - ');
                            ?>
                            </div>
                            <div class="col-2 border">
                            <?php
                               echo '<b>Result 20:</b> <br>'. ((is_int($key) && $key > -1) ? ($form->done_analysis[$key]->pivot->result_20 && $form->done_analysis[$key]->pivot->result_20 !== '' ?  $form->done_analysis[$key]->pivot->result_20 : '-') : ' - ');
                            ?>
                            </div>
                            <div class="col-4 border">
                            <?php
                               echo '<b>Result AVG:</b> <br>'. ((is_int($key) && $key > -1) ? ($form->done_analysis[$key]->pivot->result_avg && $form->done_analysis[$key]->pivot->result_avg !== '' ?  $form->done_analysis[$key]->pivot->result_avg : '-') : ' - ');
                            ?>
                            </div>
                            <div class="col-4 border">
                            <?php
                               echo '<b>Result COMP:</b> <br>'. ((is_int($key) && $key > -1) ? ($form->done_analysis[$key]->pivot->result_comp && $form->done_analysis[$key]->pivot->result_comp !== '' ?  $form->done_analysis[$key]->pivot->result_comp : '-') : ' - ');
                            ?>
                            </div>
                        </div>
                    </div>
                
                    <?php

                    $key = null;
                    }
                    ?>
                </div>
            <?php
                }
                ?>
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