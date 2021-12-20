<?php
include_once 'ApiCaller.php';
include 'vendor/autoload.php';
$dotenv =\Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

session_start();
date_default_timezone_set('Asia/Istanbul');


$apiCaller = new ApiCaller('1', $_SESSION["token"]);

function addOperationNoteForLab($apiCaller, $operationID, $operationNote)
{

    $response = $apiCaller->sendRequest(array(
        'api_url' => $_ENV['LINK'].'addNoteForLab',
        'api_method' => 'post',
        'operationID' => $operationID,
        'operationNote' => $operationNote,
        'noteType' => 2,
    ));

    if ($response['error'] == false) {

        if (isset($response['data']->success)) {
            setcookie("successfulAddOperationNote", $response['data']->success, time() + 60 * 60 * 24 * 1);
            header('Location: laboratory_analysis_page.php');
        } elseif (isset($response['data']->error)) {
            setcookie("errorAddOperationNote", $response['data']->error, time() + 60 * 60 * 24 * 1);
            header('Location: laboratory_analysis_page.php');
        }

    } elseif ($response['error'] == true) {
        if ($response['status'] == '401') {
            echo "There is no token";
        } elseif ($response['status'] == "400") {
            echo "Token expired or token decode error";
        } elseif ($response['status'] == "404") {
            echo "There is no such thing";
        } else {
            header('Location: laboratory_analysis_page.php');
        }
    }

}

function showMyNotes($apiCaller, $operationID)
{
    $response = $apiCaller->sendRequest(array(
        'api_url' => $_ENV['LINK'].'showNotesForLab/' . $operationID,
        'api_method' => 'get',
    ));

    echo json_encode($response['data']);
}

function acceptForm($apiCaller, $labFormID, $isExternal = false)
{
    $response = $apiCaller->sendRequest(array(
        'api_url' => $_ENV['LINK'].'acceptLabForm',
        'api_method' => 'post',
        'labFormID' => $labFormID,
    ));

    $redirect = $isExternal ? 'external_laboratory_analysis_page.php' : 'laboratory_analysis_page.php';

    if ($response['error'] == false) {

        if (isset($response['data']->success)) {
            setcookie("successfulAcceptLabForm", $response['data']->success, time() + 60 * 60 * 24 * 1);
            header('Location: '.$redirect);
        } elseif (isset($response['data']->error)) {
            setcookie("errorAcceptLabForm", $response['data']->error, time() + 60 * 60 * 24 * 1);
            header('Location: '.$redirect);
        }

    } elseif ($response['error'] == true) {
        if ($response['status'] == '401') {
            echo "There is no token";
        } elseif ($response['status'] == "400") {
            echo "Token expired or token decode error";
        } elseif ($response['status'] == "404") {
            echo "There is no such thing";
        } else {
            header('Location: '.$redirect);
        }
    }
}

function joinForm($apiCaller, $labFormID, $isExternal = false)
{
    $redirect = $isExternal ? 'external_laboratory_analysis_page.php' : 'laboratory_analysis_page.php';
    $response = $apiCaller->sendRequest(array(
        'api_url' => $_ENV['LINK'].'joinLabForm',
        'api_method' => 'post',
        'labFormID' => $labFormID,
    ));
    if ($response['error'] == false) {

        if (isset($response['data']->success)) {
            setcookie("successfulJoinLabForm", $response['data']->success, time() + 60 * 60 * 24 * 1);
            header('Location: '.$redirect);
        } elseif (isset($response['data']->error)) {
            setcookie("errorJoinLabForm", $response['data']->error, time() + 60 * 60 * 24 * 1);
            header('Location: '.$redirect);
        }

    } elseif ($response['error'] == true) {
        if ($response['status'] == '401') {
            echo "There is no token";
        } elseif ($response['status'] == "400") {
            echo "Token expired or token decode error";
        } elseif ($response['status'] == "404") {
            echo "There is no such thing";
        } else {
            header('Location: '.$redirect);
        }
    }
}

function getStandards($apiCaller)
{
    $response = $apiCaller->sendRequest(array(
        'api_url' => $_ENV['LINK'].'getAllStandards',
        'api_method' => 'get',
    ));

    echo json_encode($response['data']);
}

function getSampleForLabForm($apiCaller, $laboratoryFormID)
{
    $response = $apiCaller->sendRequest(array(
        'api_url' => $_ENV['LINK'].'getSamplesForAnalysis',
        'api_method' => 'post',
        'laboratoryFormID' => $laboratoryFormID,
    ));

    echo json_encode($response['data']);
}

function getConditionsForLabForm($apiCaller, $laboratoryFormID)
{
    $response = $apiCaller->sendRequest(array(
        'api_url' => $_ENV['LINK'].'getAnlCForLaboratoryForm/' . $laboratoryFormID,
        'api_method' => 'get',
    ));

    echo json_encode($response['data']);
}

function getReqAnlayzesForLabForm($apiCaller, $laboratoryFormID)
{
    $response = $apiCaller->sendRequest(array(
        'api_url' => $_ENV['LINK'].'getReqAnlForLaboratoryForm/' . $laboratoryFormID,
        'api_method' => 'get',
    ));

    echo json_encode($response['data']);
}

function doAnalyzeForOperation($isExternal = false, $apiCaller, $labFormID, $sampleID, $conditionID, $standardID, $analysisID, $demandDate, $startDate, $endDate, $result, $remarks, $isSubcontractor, $subcontractorName, $subcontractorSendDate, $sampleArray, $conditionArray, $standardArray, $analysisArray, $demandArray, $startArray, $endArray, $resultArray, $remarksArray)
{
    $redirect = $isExternal ? 'external_laboratory_analysis_page.php' : 'laboratory_analysis_page.php';
    $apiArray = array(
        'api_url' => $_ENV['LINK'].'makeNewAnalyze',
        'api_method' => 'post',
        'labFormID' => $labFormID,
        'isSubcontractor' => $isSubcontractor,
        'subcontractorName' => $subcontractorName,
        'subcontractorSendDate' => $subcontractorSendDate,
    );

    foreach ($sampleArray as $key => $val) {
        $apiArray[$key] = $val;
    }

    foreach ($conditionArray as $key => $val) {
        $apiArray[$key] = $val;
    }

    foreach ($standardArray as $key => $val) {
        $apiArray[$key] = $val;
    }

    foreach ($analysisArray as $key => $val) {
        $apiArray[$key] = $val;
    }

    foreach ($demandArray as $key => $val) {
        $apiArray[$key] = $val;
    }

    foreach ($startArray as $key => $val) {
        $apiArray[$key] = $val;
    }

    foreach ($endArray as $key => $val) {
        $apiArray[$key] = $val;
    }

    foreach ($resultArray as $key => $val) {
        $apiArray[$key] = $val;
    }

    foreach ($remarksArray as $key => $val) {
        $apiArray[$key] = $val;
    }

    $response = $apiCaller->sendRequest($apiArray);
    if ($response['error'] == false) {

        if (isset($response['data']->success)) {
            setcookie("successfulMakeAnalyze", $response['data']->success, time() + 60 * 60 * 24 * 1);
            header('Location: '.$redirect);
        } elseif (isset($response['data']->error)) {
            setcookie("errorMakeAnalyze", $response['data']->error, time() + 60 * 60 * 24 * 1);
            header('Location: '.$redirect);
        }

    } elseif ($response['error'] == true) {
        if ($response['status'] == '401') {
            echo "There is no token";
        } elseif ($response['status'] == "400") {
            echo "Token expired or token decode error";
        } elseif ($response['status'] == "404") {
            echo "There is no such thing";
        } else {
            header('Location: '.$redirect);
        }
    }

}

function newExternal ($apiCaller, $data) {
    $response = $apiCaller->sendRequest(
        array_merge(['api_url' => $_ENV['LINK'].'externalLabJob/',
        'api_method' => 'post',], $data)
        );

    echo json_encode($response['data']);
}

function getOperationForDetailWaitingLaborant($apiCaller, $operationID)
{
    $response = $apiCaller->sendRequest(array(
        'api_url' => $_ENV['LINK'].'viewDetailedWaitingLaborant/' . $operationID,
        'api_method' => 'get',
    ));

    echo json_encode($response['data']);
}

function getLabFormDetailForWaitingLaborant($apiCaller, $labFormId)
{
    $response = $apiCaller->sendRequest(array(
        'api_url' => $_ENV['LINK'].'getLabFormDetailForWaitingLaborant/' . $labFormId,
        'api_method' => 'get',
    ));

    echo json_encode($response['data']);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST["acceptLabFormID"]) && !empty($_POST["acceptLabFormID"])) {
        acceptForm($apiCaller, $_POST["acceptLabFormID"], $_POST["isExternal"]);
    }

    if (isset($_POST["newExternal"])) {
        newExternal($apiCaller, $_POST);
    }

    
    if (isset($_POST["selectedOperationDetW"]) && !empty($_POST["selectedOperationDetW"])) {
        if (is_numeric($_POST["selectedOperationDetW"])) {
            getOperationForDetailWaitingLaborant($apiCaller, $_POST["selectedOperationDetW"]);
        }
    }

    if (isset($_POST["selectedLabDetW"]) && !empty($_POST["selectedLabDetW"])) {
        if (is_numeric($_POST["selectedLabDetW"])) {
            getLabFormDetailForWaitingLaborant($apiCaller, $_POST["selectedLabDetW"]);
        }
    }

    if (isset($_POST["joinLabFormID"]) && !empty($_POST["joinLabFormID"])) {
        joinForm($apiCaller, $_POST["joinLabFormID"],$_POST["isExternal"]);
    }

    if (isset($_POST["labFormIDForSample"]) && !empty($_POST["labFormIDForSample"])) {
        getSampleForLabForm($apiCaller, $_POST["labFormIDForSample"]);
    }

    if (isset($_POST["standardsForLabForm"]) && !empty($_POST["standardsForLabForm"])) {
        getStandards($apiCaller);
    }

    if (isset($_POST["labFormIDForCondition"]) && !empty($_POST["labFormIDForCondition"])) {
        getConditionsForLabForm($apiCaller, $_POST["labFormIDForCondition"]);
    }

    if (isset($_POST["labFormIDForReq"]) && !empty($_POST["labFormIDForReq"])) {
        getReqAnlayzesForLabForm($apiCaller, $_POST["labFormIDForReq"]);
    }

    if (isset($_POST["addNewOperationLabNoteOptID"]) && isset($_POST["operationLabNote"])) {
        if (!empty($_POST["addNewOperationLabNoteOptID"]) && !empty($_POST["operationLabNote"])) {
            addOperationNoteForLab($apiCaller, $_POST["addNewOperationLabNoteOptID"], $_POST["operationLabNote"]);
        }
    }

    if (isset($_POST["selectedOptLabShowNotes"]) && !empty($_POST["selectedOptLabShowNotes"])) {
        if (is_numeric($_POST["selectedOptLabShowNotes"])) {
            showMyNotes($apiCaller, $_POST["selectedOptLabShowNotes"]);
        }
    }

    if (isset($_POST["enterAnalysisFormID"]) && !empty($_POST["enterAnalysisFormID"]) && isset($_POST["enterAnalysisSample1"]) && !empty($_POST["enterAnalysisSample1"]) && isset($_POST["enterAnalysisCondition1"]) && !empty($_POST["enterAnalysisCondition1"]) && isset($_POST["enterAnalysisStandard1"]) && !empty($_POST["enterAnalysisStandard1"]) && isset($_POST["enterAnalysisReq1"]) && !empty($_POST["enterAnalysisReq1"]) && isset($_POST["anlDemandDate1"]) && !empty($_POST["anlDemandDate1"]) && isset($_POST["anlStartDate1"]) && !empty($_POST["anlStartDate1"]) && isset($_POST["anlEndDate1"]) && !empty($_POST["anlEndDate1"]) && isset($_POST["anlResult1"]) && !empty($_POST["anlResult1"]) && isset($_POST["anlRemarks1"]) && isset($_POST["isSubcontractor"])) {

        $sampleArray = array();
        $conditionArray = array();
        $standardArray = array();
        $analysisArray = array();
        $demandArray = array();
        $startArray = array();
        $endArray = array();
        $resultArray = array();
        $remarksArray = array();

        foreach ($_POST as $key => $value) {
            $param_name_smp = 'enterAnalysisSample';
            $param_name_con = 'enterAnalysisCondition';
            $param_name_std = 'enterAnalysisStandard';
            $param_name_anl = 'enterAnalysisReq';
            $param_name_dmn = 'anlDemandDate';
            $param_name_str = 'anlStartDate';
            $param_name_end = 'anlEndDate';
            $param_name_rsl = 'anlResult';
            $param_name_rmk = 'anlRemarks';

            if (substr($key, 0, strlen($param_name_smp)) == $param_name_smp) {
                $sampleArray[$key] = $value;
            }

            if (substr($key, 0, strlen($param_name_con)) == $param_name_con) {
                $conditionArray[$key] = $value;
            }

            if (substr($key, 0, strlen($param_name_std)) == $param_name_std) {
                $standardArray[$key] = $value;
            }

            if (substr($key, 0, strlen($param_name_anl)) == $param_name_anl) {
                $analysisArray[$key] = $value;
            }

            if (substr($key, 0, strlen($param_name_dmn)) == $param_name_dmn) {
                $demandArray[$key] = $value;
            }

            if (substr($key, 0, strlen($param_name_str)) == $param_name_str) {
                $startArray[$key] = $value;
            }

            if (substr($key, 0, strlen($param_name_end)) == $param_name_end) {
                $endArray[$key] = $value;
            }

            if (substr($key, 0, strlen($param_name_rsl)) == $param_name_rsl) {
                $resultArray[$key] = $value;
            }

            if (substr($key, 0, strlen($param_name_rmk)) == $param_name_rmk) {
                $remarksArray[$key] = $value;
            }

        }

        if ($_POST["isSubcontractor"] === "1") {
            if (isset($_POST["subcontractorName"]) && !empty($_POST["subcontractorName"]) && isset($_POST["subcontractorSendDate"]) && !empty($_POST["subcontractorSendDate"])) {
                doAnalyzeForOperation($_POST['isExternal'], $apiCaller, $_POST["enterAnalysisFormID"], $_POST["enterAnalysisSample1"], $_POST["enterAnalysisCondition1"], $_POST["enterAnalysisStandard1"], $_POST["enterAnalysisReq1"], $_POST["anlDemandDate1"], $_POST["anlStartDate1"], $_POST["anlEndDate1"], $_POST["anlResult1"], $_POST["anlRemarks1"], $_POST["isSubcontractor"], $_POST["subcontractorName"], $_POST["subcontractorSendDate"], $sampleArray, $conditionArray, $standardArray, $analysisArray, $demandArray, $startArray, $endArray, $resultArray, $remarksArray);
            }
        } else {
            doAnalyzeForOperation($_POST['isExternal'], $apiCaller, $_POST["enterAnalysisFormID"], $_POST["enterAnalysisSample1"], $_POST["enterAnalysisCondition1"], $_POST["enterAnalysisStandard1"], $_POST["enterAnalysisReq1"], $_POST["anlDemandDate1"], $_POST["anlStartDate1"], $_POST["anlEndDate1"], $_POST["anlResult1"], $_POST["anlRemarks1"], $_POST["isSubcontractor"], null, null, $sampleArray, $conditionArray, $standardArray, $analysisArray, $demandArray, $startArray, $endArray, $resultArray, $remarksArray);
        }
    }

}