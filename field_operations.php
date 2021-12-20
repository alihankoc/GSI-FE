<?php
include 'vendor/autoload.php';
$dotenv =\Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

include_once 'ApiCaller.php';

session_start();
date_default_timezone_set('Asia/Istanbul');


$apiCaller = new ApiCaller('1', $_SESSION["token"]);

function addOperationNoteForField($apiCaller, $operationID, $operationNote)
{

    $response = $apiCaller->sendRequest(array(
        'api_url' => $_ENV['LINK'].'addNoteForField',
        'api_method' => 'post',
        'operationID' => $operationID,
        'operationNote' => $operationNote,
        'noteType' => 1,
    ));

    if ($response['error'] == false) {

        if (isset($response['data']->success)) {
            setcookie("successfulAddOperationNote", $response['data']->success, time() + 60 * 60 * 24 * 1);
            header('Location: field_operation_page.php');
        } elseif (isset($response['data']->error)) {
            setcookie("errorAddOperationNote", $response['data']->error, time() + 60 * 60 * 24 * 1);
            header('Location: field_operation_page.php');
        }

    } elseif ($response['error'] == true) {
        if ($response['status'] == '401') {
            echo "There is no token";
        } elseif ($response['status'] == "400") {
            echo "Token expired or token decode error";
        } elseif ($response['status'] == "404") {
            echo "There is no such thing";
        } else {
            header('Location: field_operation_page.php');
        }
    }

}

function showMyNotes($apiCaller, $operationID)
{
    $response = $apiCaller->sendRequest(array(
        'api_url' => $_ENV['LINK'].'showNotesForField/' . $operationID,
        'api_method' => 'get',
    ));

    echo json_encode($response['data']);
}

function addOperationExpense($apiCaller, $operationID, $operationExpense, $expenseAmount)
{

    $response = $apiCaller->sendRequest(array(
        'api_url' => $_ENV['LINK'].'addExpense',
        'api_method' => 'post',
        'operationID' => $operationID,
        'operationExpenseContent' => $operationExpense,
        'operationExpenseAmount' => $expenseAmount,
    ));

    if ($response['error'] == false) {

        if (isset($response['data']->success)) {
            setcookie("successfulAddOperationExpense", $response['data']->success, time() + 60 * 60 * 24 * 1);
            header('Location: field_operation_page.php');
        } elseif (isset($response['data']->error)) {
            setcookie("errorAddOperationExpense", $response['data']->error, time() + 60 * 60 * 24 * 1);
            header('Location: field_operation_page.php');
        }

    } elseif ($response['error'] == true) {
        if ($response['status'] == '401') {
            echo "There is no token";
        } elseif ($response['status'] == "400") {
            echo "Token expired or token decode error";
        } elseif ($response['status'] == "404") {
            echo "There is no such thing";
        } else {
            header('Location: field_operation_page.php');
        }
    }

}

function showMyExpenses($apiCaller, $operationID)
{
    $response = $apiCaller->sendRequest(array(
        'api_url' => $_ENV['LINK'].'showExpensesForIns/' . $operationID,
        'api_method' => 'get',
    ));

    echo json_encode($response['data']);
}


function acceptOperation($apiCaller, $operationID)
{
    $response = $apiCaller->sendRequest(array(
        'api_url' => $_ENV['LINK'].'acceptOperation',
        'api_method' => 'post',
        'operationID' => $operationID,
    ));

    if ($response['error'] == false) {

        if (isset($response['data']->success)) {
            setcookie("successfulAcceptOperation", $response['data']->success, time() + 60 * 60 * 24 * 1);
            header('Location: field_operation_page.php');
        } elseif (isset($response['data']->error)) {
            setcookie("errorAcceptOperation", $response['data']->error, time() + 60 * 60 * 24 * 1);
            header('Location: field_operation_page.php');
        }

    } elseif ($response['error'] == true) {
        if ($response['status'] == '401') {
            echo "There is no token";
        } elseif ($response['status'] == "400") {
            echo "Token expired or token decode error";
        } elseif ($response['status'] == "404") {
            echo "There is no such thing";
        } else {
            header('Location: field_operation_page.php');
        }
    }
}

function joinOperation($apiCaller, $operationID)
{
    $response = $apiCaller->sendRequest(array(
        'api_url' => $_ENV['LINK'].'joinOperation/',
        'api_method' => 'post',
        'operationID' => $operationID,
    ));

    if ($response['error'] == false) {

        if (isset($response['data']->success)) {
            setcookie("successfulJoinOperation", $response['data']->success, time() + 60 * 60 * 24 * 1);
            header('Location: field_operation_page.php');
        } elseif (isset($response['data']->error)) {
            setcookie("errorJoinOperation", $response['data']->error, time() + 60 * 60 * 24 * 1);
            header('Location: field_operation_page.php');
        }

    } elseif ($response['error'] == true) {
        if ($response['status'] == '401') {
            echo "There is no token";
        } elseif ($response['status'] == "400") {
            echo "Token expired or token decode error";
        } elseif ($response['status'] == "404") {
            echo "There is no such thing";
        } else {
            header('Location: field_operation_page.php');
        }
    }
}

function getOperationForDetailWaitingField($apiCaller, $operationID)
{
    $response = $apiCaller->sendRequest(array(
        'api_url' => $_ENV['LINK'].'viewDetailedWaitingField/' . $operationID,
        'api_method' => 'get',
    ));

    echo json_encode($response['data']);
}

function getOperationForSurvInfo($apiCaller, $operationID)
{
    $response = $apiCaller->sendRequest(array(
        'api_url' => $_ENV['LINK'].'getOperationForSurvInfo/' . $operationID,
        'api_method' => 'get',
    ));

    echo json_encode($response['data']);
}

function getDetailsForSurvPhotos($apiCaller, $operationID)
{
    $response = $apiCaller->sendRequest(array(
        'api_url' => $_ENV['LINK'].'getOperationWithSurvPhotos/' . $operationID,
        'api_method' => 'get',
    ));

    echo json_encode($response['data']);
}

function getDetailsForSurvDocuments($apiCaller, $operationID)
{
    $response = $apiCaller->sendRequest(array(
        'api_url' => $_ENV['LINK'].'getOperationWithSurvDocuments/' . $operationID,
        'api_method' => 'get',
    ));

    echo json_encode($response['data']);
}

function surveillanceInfoSaveChanges($apiCaller, $equipmenType, $vesselArrive, $vesselLand, $cleaningDate, $draftBeginning, $draftInter, $draftFinal, $loadLandStart, $loadLandEnd, $fumigationStart, $fumigationEnd, $makeSeal, $removeSeal, $weighingResult, $weighingDifference, $shipDraftResult, $shipDraftDifference, $vehicleCountingResult, $vehicleCountingDifference, $shoreTankResult, $shoreTankDifference, $surveillanceFormOptID, $surveillanceFormID, $additionalNotes, $reqSurvForEdit)
{

    $apiArray = array(
        'api_url' => $_ENV['LINK'].'saveChangesOfSurveillanceInfoForm',
        'api_method' => 'post',
        'equipmentType' => $equipmenType,
        'vesselArrive' => $vesselArrive,
        'vesselLand' => $vesselLand,
        'cleaningDate' => $cleaningDate,
        'draftBeginning' => $draftBeginning,
        'draftInter' => $draftInter,
        'draftFinal' => $draftFinal,
        'loadLandStart' => $loadLandStart,
        'loadLandEnd' => $loadLandEnd,
        'fumigationStart' => $fumigationStart,
        'fumigationEnd' => $fumigationEnd,
        'makeSeal' => $makeSeal,
        'removeSeal' => $removeSeal,
        'weighingResult' => $weighingResult,
        'weighingDifference' => $weighingDifference,
        'shipDraftResult' => $shipDraftResult,
        'shipDraftDifference' => $shipDraftDifference,
        'vehicleCountingResult' => $vehicleCountingResult,
        'vehicleCountingDifference' => $vehicleCountingDifference,
        'shoreTankResult' => $shoreTankResult,
        'shoreTankDifference' => $shoreTankDifference,
        'surveillanceFormOptID' => $surveillanceFormOptID,
        'surveillanceFormID' => $surveillanceFormID,
        'additionalNotes' => $additionalNotes,
    );

    foreach ($reqSurvForEdit as $key => $val) {
        $apiArray[$key] = $val;
    }

    $response = $apiCaller->sendRequest($apiArray);


    if ($response['error'] == false) {
        if (isset($response['data']->success)) {
            setcookie("successfulSaveSurveillance", $response['data']->success, time() + 60 * 60 * 24 * 1);
            header('Location: field_operation_page.php');
        } elseif (isset($response['data']->error)) {
            setcookie("errorSaveSurveillance", $response['data']->error, time() + 60 * 60 * 24 * 1);
            header('Location: field_operation_page.php');
        }
    } elseif ($response['error'] == true) {
        if ($response['status'] == '401') {
            echo "There is no token";
        } elseif ($response['status'] == "400") {
            echo "Token expired or token decode error";
        } elseif ($response['status'] == "404") {
            echo "There is no such thing";
        } else {
            header('Location: field_operation_page.php');
        }
    }
}

function surveillanceInfoComplete($apiCaller, $surveillanceFormID)
{
    $response = $apiCaller->sendRequest(array(
        'api_url' => $_ENV['LINK'].'completeSurveillanceInfoForm',
        'api_method' => 'post',
        'surveillanceInfoFormID' => $surveillanceFormID,
    ));

    echo json_encode($response['data']);
}

function uploadPhoto($apiCaller, $file, $surveillanceInfoForm)
{

    $response = $apiCaller->sendRequest(array(
        'api_url' => $_ENV['LINK'].'uploadSurvPhoto',
        'api_method' => 'file',
        'myFile' => $file,
        'survPhotoFormID' => $surveillanceInfoForm,
    ));


    if ($response['error'] == false) {
        if (isset($response['data']->success)) {
            setcookie("successfulSavePhoto", $response['data']->success, time() + 60 * 60 * 24 * 1);
            header('Location: field_operation_page.php');
        } elseif (isset($response['data']->error)) {
            setcookie("errorSavePhoto", $response['data']->error, time() + 60 * 60 * 24 * 1);
            header('Location: field_operation_page.php');
        }
    } elseif ($response['error'] == true) {
        if ($response['status'] == '401') {
            echo "There is no token";
        } elseif ($response['status'] == "400") {
            echo "Token expired or token decode error";
        } elseif ($response['status'] == "404") {
            echo "There is no such thing";
        } else {
            header('Location: field_operation_page.php');
        }
    }
}

function removePhoto($apiCaller, $removeFormID, $surveyPhotos)
{

    $apiArray = array(
        'api_url' => $_ENV['LINK'].'removeSurvPhoto',
        'api_method' => 'post',
        'removeFromSurvID' => $removeFormID,
    );

    foreach ($surveyPhotos as $key => $val) {
        $apiArray["selectedPhotos" . $key] = $val;
    }

    $response = $apiCaller->sendRequest($apiArray);


    if ($response['error'] == false) {
        if (isset($response['data']->success)) {
            setcookie("successfulSavePhoto", $response['data']->success, time() + 60 * 60 * 24 * 1);
            header('Location: field_operation_page.php');
        } elseif (isset($response['data']->error)) {
            setcookie("errorSavePhoto", $response['data']->error, time() + 60 * 60 * 24 * 1);
            header('Location: field_operation_page.php');
        }
    } elseif ($response['error'] == true) {
        if ($response['status'] == '401') {
            echo "There is no token";
        } elseif ($response['status'] == "400") {
            echo "Token expired or token decode error";
        } elseif ($response['status'] == "404") {
            echo "There is no such thing";
        } else {
            header('Location: field_operation_page.php');
        }
    }
}

function uploadDocument($apiCaller, $file, $surveillanceInfoForm)
{

    $response = $apiCaller->sendRequest(array(
        'api_url' => $_ENV['LINK'].'uploadSurvDoc',
        'api_method' => 'file',
        'myFile' => $file,
        'survDocumentFormID' => $surveillanceInfoForm,
    ));


    if ($response['error'] == false) {
        if (isset($response['data']->success)) {
            setcookie("successfulSaveDocument", $response['data']->success, time() + 60 * 60 * 24 * 1);
            header('Location: field_operation_page.php');
        } elseif (isset($response['data']->error)) {
            setcookie("errorSaveDocument", $response['data']->error, time() + 60 * 60 * 24 * 1);
            header('Location: field_operation_page.php');
        }
    } elseif ($response['error'] == true) {
        if ($response['status'] == '401') {
            echo "There is no token";
        } elseif ($response['status'] == "400") {
            echo "Token expired or token decode error";
        } elseif ($response['status'] == "404") {
            echo "There is no such thing";
        } else {
            header('Location: field_operation_page.php');
        }
    }
}

function removeDocument($apiCaller, $removeFormID, $surveyDocuments)
{

    $apiArray = array(
        'api_url' => $_ENV['LINK'].'removeSurvDoc',
        'api_method' => 'post',
        'removeDocFormID' => $removeFormID,
    );

    foreach ($surveyDocuments as $key => $val) {
        $apiArray["selectedDocuments" . $key] = $val;
    }

    $response = $apiCaller->sendRequest($apiArray);


    if ($response['error'] == false) {
        if (isset($response['data']->success)) {
            setcookie("successfulSaveDocument", $response['data']->success, time() + 60 * 60 * 24 * 1);
            header('Location: field_operation_page.php');
        } elseif (isset($response['data']->error)) {
            setcookie("errorSaveDocument", $response['data']->error, time() + 60 * 60 * 24 * 1);
            header('Location: field_operation_page.php');
        }
    } elseif ($response['error'] == true) {
        if ($response['status'] == '401') {
            echo "There is no token";
        } elseif ($response['status'] == "400") {
            echo "Token expired or token decode error";
        } elseif ($response['status'] == "404") {
            echo "There is no such thing";
        } else {
            header('Location: field_operation_page.php');
        }
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST["vesselArrive"]) && isset($_POST["vesselLand"]) && isset($_POST["cleaningDate"]) && isset($_POST["draftBeginning"]) && isset($_POST["draftInter"]) && isset($_POST["draftFinal"]) && isset($_POST["loadLandStart"]) && isset($_POST["loadlLandEnd"]) && isset($_POST["fumigationStart"]) && isset($_POST["fumigationEnd"]) && isset($_POST["makeSeal"]) && isset($_POST["removeSeal"])) {
        if (isset($_POST["weighingResult"]) && isset($_POST["weighingDifference"]) && isset($_POST["shipDraftResult"]) && isset($_POST["shipDraftDifference"]) && isset($_POST["vehicleCountingResult"]) && isset($_POST["vehicleCountingDifference"]) && isset($_POST["shoreTankResult"]) && isset($_POST["shoreTankDifference"])) {
            if (isset($_POST["surveillanceFormOptID"]) && isset($_POST["surveillanceFormID"]) && isset($_POST["additionalNotes"])) {
                $equipment = null;
                if (isset($_POST["equipmentType"])) {
                    $equipment = $_POST["equipmentType"];
                }

                $doneSurvs = array();

                if (isset($_POST["reqSurvForEdit"])) {
                    foreach ($_POST["reqSurvForEdit"] as $item) {
                        $doneSurvs["reqDoneSurv" . $item] = $item;
                    }
                }

                surveillanceInfoSaveChanges($apiCaller, $equipment, $_POST["vesselArrive"], $_POST["vesselLand"], $_POST["cleaningDate"], $_POST["draftBeginning"], $_POST["draftInter"], $_POST["draftFinal"], $_POST["loadLandStart"], $_POST["loadlLandEnd"], $_POST["fumigationStart"], $_POST["fumigationEnd"], $_POST["makeSeal"], $_POST["removeSeal"], $_POST["weighingResult"], $_POST["weighingDifference"], $_POST["shipDraftResult"], $_POST["shipDraftDifference"], $_POST["vehicleCountingResult"], $_POST["vehicleCountingDifference"], $_POST["shoreTankResult"], $_POST["shoreTankDifference"], $_POST["surveillanceFormOptID"], $_POST["surveillanceFormID"], $_POST["additionalNotes"], $doneSurvs);

            }
        }
    }

    if (isset($_POST["completeFormID"]) && !empty($_POST["completeFormID"])) {
        if (is_numeric($_POST["completeFormID"])) {
            surveillanceInfoComplete($apiCaller, $_POST["completeFormID"]);
        }
    }

    if (isset($_POST["addNewOperationNoteOptID"]) && isset($_POST["operationNote"])) {
        if (!empty($_POST["addNewOperationNoteOptID"]) && !empty($_POST["operationNote"])) {
            addOperationNoteForField($apiCaller, $_POST["addNewOperationNoteOptID"], $_POST["operationNote"]);
        }
    }

    if (isset($_POST["selectedOptInsShowNotes"]) && !empty($_POST["selectedOptInsShowNotes"])) {
        if (is_numeric($_POST["selectedOptInsShowNotes"])) {
            showMyNotes($apiCaller, $_POST["selectedOptInsShowNotes"]);
        }
    }

    if (isset($_POST["addNewOperationExpenseOptID"]) && isset($_POST["operationExpense"]) && isset($_POST["operationExpenseAmount"])) {
        if (!empty($_POST["addNewOperationExpenseOptID"]) && !empty($_POST["operationExpense"]) && !empty($_POST["operationExpenseAmount"])) {
            addOperationExpense($apiCaller, $_POST["addNewOperationExpenseOptID"], $_POST["operationExpense"], $_POST["operationExpenseAmount"]);
        }
    }

    if (isset($_POST["selectedOptInsShowExpenses"]) && !empty($_POST["selectedOptInsShowExpenses"])) {
        if (is_numeric($_POST["selectedOptInsShowExpenses"])) {
            showMyExpenses($apiCaller, $_POST["selectedOptInsShowExpenses"]);
        }
    }

    if (isset($_POST["acceptOperationID"]) && !empty($_POST["acceptOperationID"])) {
        acceptOperation($apiCaller, $_POST["acceptOperationID"]);
    }

    if (isset($_POST["joinOperationID"]) && !empty($_POST["joinOperationID"])) {
        joinOperation($apiCaller, $_POST["joinOperationID"]);
    }

    if (isset($_POST["selectedOperationDetW"]) && !empty($_POST["selectedOperationDetW"])) {
        if (is_numeric($_POST["selectedOperationDetW"])) {
            getOperationForDetailWaitingField($apiCaller, $_POST["selectedOperationDetW"]);
        }
    }

    if (isset($_POST["selectedOperationSurvInfo"]) && !empty($_POST["selectedOperationSurvInfo"])) {
        if (is_numeric($_POST["selectedOperationSurvInfo"])) {
            getOperationForSurvInfo($apiCaller, $_POST["selectedOperationSurvInfo"]);
        }
    }

    if (isset($_POST["selectedOperationSurvPhoto"]) && !empty($_POST["selectedOperationSurvPhoto"])) {
        if (is_numeric($_POST["selectedOperationSurvPhoto"])) {
            getDetailsForSurvPhotos($apiCaller, $_POST["selectedOperationSurvPhoto"]);
        }
    }

    if (isset($_POST["selectedOperationSurvDocument"]) && !empty($_POST["selectedOperationSurvDocument"])) {
        if (is_numeric($_POST["selectedOperationSurvDocument"])) {
            getDetailsForSurvDocuments($apiCaller, $_POST["selectedOperationSurvDocument"]);
        }
    }

    if (isset($_FILES["myPhotoFile"]) && !empty($_FILES["myPhotoFile"]) && isset($_POST["photoFormID"]) && !empty($_POST["photoFormID"])) {
        if (is_numeric($_POST["photoFormID"])) {
            uploadPhoto($apiCaller, $_FILES["myPhotoFile"], $_POST["photoFormID"]);
        }
    }

    if (isset($_POST["removeFormID"]) && !empty($_POST["removeFormID"]) && isset($_POST["surveyPhotos"]) && !empty($_POST["surveyPhotos"])) {
        if (is_numeric($_POST["removeFormID"])) {
            removePhoto($apiCaller, $_POST["removeFormID"], $_POST["surveyPhotos"]);
        }
    }

    if (isset($_FILES["myDocumentFile"]) && !empty($_FILES["myDocumentFile"]) && isset($_POST["documentFormID"]) && !empty($_POST["documentFormID"])) {
        if (is_numeric($_POST["documentFormID"])) {
            uploadDocument($apiCaller, $_FILES["myDocumentFile"], $_POST["documentFormID"]);
        }
    }

    if (isset($_POST["removeDocFormID"]) && !empty($_POST["removeDocFormID"]) && isset($_POST["surveyDocuments"]) && !empty($_POST["surveyDocuments"])) {
        if (is_numeric($_POST["removeDocFormID"])) {
            removeDocument($apiCaller, $_POST["removeDocFormID"], $_POST["surveyDocuments"]);
        }
    }

}

