<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 3.07.2019
 * Time: 00:19
 */


include_once 'ApiCaller.php';

session_start();

date_default_timezone_set('Asia/Istanbul');

$apiCaller = new ApiCaller('1', $_SESSION["token"]);

function getOperationForInformCustomer($apiCaller, $operationID)
{
    $response = $apiCaller->sendRequest(array(
        'api_url' => 'https://lumen.krekpot.com/api/v1/viewOperationForInformCustomer/' . $operationID,
        'api_method' => 'get',
    ));

    echo json_encode($response['data']);
}

function finishOperation($apiCaller, $operationID)
{
    $response = $apiCaller->sendRequest(array(
        'api_url' => 'https://lumen.krekpot.com/api/v1/finishOperation',
        'api_method' => 'post',
        'operationID' => $operationID,
    ));

    if ($response['error'] == false) {

        if (isset($response['data']->success)) {
            setcookie("successfulFinishOperation", $response['data']->success, time() + 60 * 60 * 24 * 1);
            header('Location: officer_main_page.php');
        } elseif (isset($response['data']->error)) {
            setcookie("errorFinishOperation", $response['data']->error, time() + 60 * 60 * 24 * 1);
            header('Location: officer_main_page.php');
        }

    } elseif ($response['error'] == true) {
        if ($response['status'] == '401') {
            echo "There is no token";
        } elseif ($response['status'] == "400") {
            echo "Token expired or token decode error";
        } elseif ($response['status'] == "404") {
            echo "There is no such thing";
        } else {
            header('Location: officer_main_page.php');
        }
    }
}

function finishAnywayOperation($apiCaller, $operationID)
{
    $response = $apiCaller->sendRequest(array(
        'api_url' => 'https://lumen.krekpot.com/api/v1/finishAnywayOperation',
        'api_method' => 'post',
        'operationID' => $operationID,
    ));

    echo json_encode($response['data']);
}

function cancelOperation($apiCaller, $operationID, $explanation)
{
    $response = $apiCaller->sendRequest(array(
        'api_url' => 'https://lumen.krekpot.com/api/v1/cancelOperation',
        'api_method' => 'post',
        'operationID' => $operationID,
        'explanation' => $explanation,
    ));

    if ($response['error'] == false) {

        if (isset($response['data']->success)) {
            setcookie("successfulCancelOperation", $response['data']->success, time() + 60 * 60 * 24 * 1);
            header('Location: officer_main_page.php');
        } elseif (isset($response['data']->error)) {
            setcookie("errorCancelOperation", $response['data']->error, time() + 60 * 60 * 24 * 1);
            header('Location: officer_main_page.php');
        }

    } elseif ($response['error'] == true) {
        if ($response['status'] == '401') {
            echo "There is no token";
        } elseif ($response['status'] == "400") {
            echo "Token expired or token decode error";
        } elseif ($response['status'] == "404") {
            echo "There is no such thing";
        } else {
            header('Location: officer_main_page.php');
        }
    }
}

function getOperationForDetailActive($apiCaller, $operationID)
{
    $response = $apiCaller->sendRequest(array(
        'api_url' => 'https://lumen.krekpot.com/api/v1/viewDetailedActive/' . $operationID,
        'api_method' => 'get',
    ));

    echo json_encode($response['data']);
}

function getOperationForDetailWaitingOfficer($apiCaller, $operationID)
{
    $response = $apiCaller->sendRequest(array(
        'api_url' => 'https://lumen.krekpot.com/api/v1/viewDetailedWaitingOfficer/' . $operationID,
        'api_method' => 'get',
    ));

    echo json_encode($response['data']);
}

function getDepartmentStatuses($apiCaller, $operationID)
{
    $response = $apiCaller->sendRequest(array(
        'api_url' => 'https://lumen.krekpot.com/api/v1/viewDepartmentStatuses/' . $operationID,
        'api_method' => 'get',
    ));

    echo json_encode($response['data']);
}

function showOperationExpenses($apiCaller, $operationID)
{
    $response = $apiCaller->sendRequest(array(
        'api_url' => 'https://lumen.krekpot.com/api/v1/showExpenses/' . $operationID,
        'api_method' => 'get',
    ));

    echo json_encode($response['data']);
}

function getOperationNotes($apiCaller, $operationID)
{
    $response = $apiCaller->sendRequest(array(
        'api_url' => 'https://lumen.krekpot.com/api/v1/showNotesForOfficer/' . $operationID,
        'api_method' => 'get',
    ));

    echo json_encode($response['data']);
}

function addOperationNoteForOfficer($apiCaller, $operationID, $operationNote, $noteType)
{

    $response = $apiCaller->sendRequest(array(
        'api_url' => 'https://lumen.krekpot.com/api/v1/addNoteForOfficer',
        'api_method' => 'post',
        'operationID' => $operationID,
        'operationNote' => $operationNote,
        'noteType' => $noteType,
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




function newOperation($apiCaller, $customer, $nominationCustomer, $buyer, $seller, $supplier, $vessel, $goods, $amount, $procType, $survType, $anlC, $locationArray, $reqSurvArray, $reqAnlArray, $reqAnlSpecArray)
{

    $apiArray = array(
        'api_url' => 'https://lumen.krekpot.com/api/v1/newOperation',
        'api_method' => 'post',
        'customer' => $customer,
        'nominationCustomer' => $nominationCustomer,
        'buyer' => $buyer,
        'seller' => $seller,
        'supplier' => $supplier,
        'vesselName' => $vessel,
        'typeOfGoods' => $goods,
        'amount' => $amount,
    );

    foreach ($procType as $key => $val) {
        $apiArray[$key] = $val;
    }

    foreach ($survType as $key => $val) {
        $apiArray[$key] = $val;
    }

    foreach ($anlC as $key => $val) {
        $apiArray[$key] = $val;
    }

    foreach ($locationArray as $key => $val) {
        $apiArray[$key] = $val;
    }

    foreach ($reqSurvArray as $key => $val) {
        $apiArray[$key] = $val;
    }

    foreach ($reqAnlArray as $key => $val) {
        $apiArray[$key] = $val;
    }

    foreach ($reqAnlSpecArray as $key => $val) {
        $apiArray[$key] = $val;
    }

    $response = $apiCaller->sendRequest($apiArray);


    if ($response['error'] == false) {
        if (isset($response['data']->success)) {
            setcookie("successfulNewOperation", $response['data']->success, time() + 60 * 60 * 24 * 1, "/");
            header('Location: officer_main_page.php');
        } elseif (isset($response['data']->error)) {
            setcookie("errorNewOperation", $response['data']->error, time() + 60 * 60 * 24 * 1, "/");
            header('Location: officer_main_page.php');
        }
    } elseif ($response['error'] == true) {
        if ($response['status'] == '401') {
            echo "There is no token";
        } elseif ($response['status'] == "400") {
            echo "Token expired or token decode error";
        } elseif ($response['status'] == "404") {
            echo "There is no such thing";
        } else {
            header('Location: officer_main_page.php');
        }
    }
}

function editOperation($apiCaller, $operationID, $customer, $nominationCustomer, $buyer, $seller, $supplier, $vessel, $goods, $amount, $reqSurvArray, $reqAnlArray, $reqAnlSpecArray)
{

    $apiArray = array(
        'api_url' => 'https://lumen.krekpot.com/api/v1/editOperation',
        'api_method' => 'post',
        'operationID' => $operationID,
        'customer' => $customer,
        'nominationCustomer' => $nominationCustomer,
        'buyer' => $buyer,
        'seller' => $seller,
        'supplier' => $supplier,
        'vesselName' => $vessel,
        'typeOfGoods' => $goods,
        'amount' => $amount,
    );

    foreach ($reqSurvArray as $key => $val) {
        $apiArray[$key] = $val;
    }

    foreach ($reqAnlArray as $key => $val) {
        $apiArray[$key] = $val;
    }

    foreach ($reqAnlSpecArray as $key => $val) {
        $apiArray[$key] = $val;
    }

    $response = $apiCaller->sendRequest($apiArray);


    if ($response['error'] == false) {
        if (isset($response['data']->success)) {
            setcookie("successfulEditOperation", $response['data']->success, time() + 60 * 60 * 24 * 1);
            header('Location: officer_main_page.php');
        } elseif (isset($response['data']->error)) {
            setcookie("errorEditOperation", $response['data']->error, time() + 60 * 60 * 24 * 1);
            header('Location: officer_main_page.php');
        }
    } elseif ($response['error'] == true) {
        if ($response['status'] == '401') {
            echo "There is no token";
        } elseif ($response['status'] == "400") {
            echo "Token expired or token decode error";
        } elseif ($response['status'] == "404") {
            echo "There is no such thing";
        } else {
            header('Location: officer_main_page.php');
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST["selectedOperationCustomerA"]) && !empty($_POST["selectedOperationCustomerA"])) {
        getOperationForInformCustomer($apiCaller, $_POST["selectedOperationCustomerA"]);
    }

    if (isset($_POST["finishOperationID"]) && !empty($_POST["finishOperationID"])) {
        finishOperation($apiCaller, $_POST["finishOperationID"]);
    }

    if (isset($_POST["anywayOperationID"]) && !empty($_POST["anywayOperationID"])) {
        finishAnywayOperation($apiCaller, $_POST["anywayOperationID"]);
    }

    if (isset($_POST["selectedOperationDeptSA"]) && !empty($_POST["selectedOperationDeptSA"])) {
        getDepartmentStatuses($apiCaller, $_POST["selectedOperationDeptSA"]);
    }

    if (isset($_POST["selectedOperationDetW"]) && !empty($_POST["selectedOperationDetW"])) {
        if (is_numeric($_POST["selectedOperationDetW"])) {
            getOperationForDetailWaitingOfficer($apiCaller, $_POST["selectedOperationDetW"]);
        }
    }

    if (isset($_POST["selectedOperationEditW"]) && !empty($_POST["selectedOperationEditW"])) {
        if (is_numeric($_POST["selectedOperationEditW"])) {
            getOperationForDetailWaitingOfficer($apiCaller, $_POST["selectedOperationEditW"]);
        }
    }

    if (isset($_POST["selectedOperationDetA"]) && !empty($_POST["selectedOperationDetA"])) {
        if (is_numeric($_POST["selectedOperationDetA"])) {
            getOperationForDetailActive($apiCaller, $_POST["selectedOperationDetA"]);
        }
    }

    if (isset($_POST["selectedOperationShowNotes"]) && !empty($_POST["selectedOperationShowNotes"])) {
        if (is_numeric($_POST["selectedOperationShowNotes"])) {
            getOperationNotes($apiCaller, $_POST["selectedOperationShowNotes"]);
        }
    }

    if (isset($_POST["selectedOptShowExpenses"]) && !empty($_POST["selectedOptShowExpenses"])) {
        if (is_numeric($_POST["selectedOptShowExpenses"])) {
            showOperationExpenses($apiCaller, $_POST["selectedOptShowExpenses"]);
        }
    }

    if (isset($_POST["cancelOperationID"]) && !empty($_POST["cancelOperationID"]) && isset($_POST["cancelOperationExplanation"]) && !empty($_POST["cancelOperationExplanation"])) {
        cancelOperation($apiCaller, $_POST["cancelOperationID"], $_POST["cancelOperationExplanation"]);
    }

    if (isset($_POST["addNewOperationNoteOptID"]) && isset($_POST["operationNote"]) && isset($_POST["addOperationNoteType"]) && !empty($_POST["addOperationNoteType"])) {
        if (!empty($_POST["addNewOperationNoteOptID"]) && !empty($_POST["operationNote"])) {

            $typeCount = count($_POST["addOperationNoteType"]);

            if ($typeCount == 2) {
                addOperationNoteForOfficer($apiCaller, $_POST["addNewOperationNoteOptID"], $_POST["operationNote"], 3);
            } elseif ($typeCount == 1) {
                if ($_POST["addOperationNoteType"][0] == 1) {
                    addOperationNoteForOfficer($apiCaller, $_POST["addNewOperationNoteOptID"], $_POST["operationNote"], 1);
                } elseif ($_POST["addOperationNoteType"][0] == 2) {
                    addOperationNoteForOfficer($apiCaller, $_POST["addNewOperationNoteOptID"], $_POST["operationNote"], 2);
                }
            }


        }
    }

    if (isset($_POST["newOperationCustomer"]) && !empty($_POST["newOperationCustomer"]) && isset($_POST["newOperationBuyer"]) && isset($_POST["newOperationSeller"]) && isset($_POST["newOperationSupplier"]) && isset($_POST["newOperationVessel"]) && !empty($_POST["newOperationVessel"]) && isset($_POST["newOperationGoods"]) && !empty($_POST["newOperationGoods"]) && isset($_POST["newOperationAmount"]) && !empty($_POST["newOperationAmount"]) && isset($_POST["newOperationLocation"]) && !empty($_POST["newOperationLocation"]) && isset($_POST["newOperationSurvT"]) && !empty($_POST["newOperationSurvT"]) && isset($_POST["newOperationProcT"]) && !empty($_POST["newOperationProcT"]) && isset($_POST["newOperationAnlC"]) && !empty($_POST["newOperationAnlC"]) && isset($_POST["newOperationReqSurv"]) && !empty($_POST["newOperationReqSurv"]) && isset($_POST["newOperationReqAnl"])) {

        $insLoc = array();
        $reqSurv = array();
        $reqAnl = array();
        $reqAnlSpecs = array();

        $procT = array();
        $survT = array();
        $anlcT = array();

        foreach ($_POST["newOperationProcT"] as $item) {
            $procT["procT" . $item] = $item;
        }

        foreach ($_POST["newOperationSurvT"] as $item) {
            $survT["survT" . $item] = $item;
        }

        foreach ($_POST["newOperationAnlC"] as $item) {
            $anlcT["anlcT" . $item] = $item;
        }

        foreach ($_POST["newOperationLocation"] as $item) {
            $insLoc["insLoc" . $item] = $item;
        }

        foreach ($_POST["newOperationReqSurv"] as $item) {
            $reqSurv["reqSurv" . $item] = $item;
        }

        if (count($_POST["newOperationReqAnl"]) > 0) {
            foreach ($_POST["newOperationReqAnl"] as $item) {
                $reqAnl["reqAnl" . $item] = $item;
                if (isset($_POST["newOperationReqSpec" . $item]) && !empty($_POST["newOperationReqSpec" . $item])) {
                    $reqAnlSpecs["reqSpec" . $item] = $_POST["newOperationReqSpec" . $item];
                }
            }
        }

        $nominationCustomer = null;

        if (isset($_POST["newOperationNominationCustomer"]) && !empty($_POST["newOperationNominationCustomer"])) {
            $nominationCustomer = $_POST["newOperationNominationCustomer"];
        }

        newOperation($apiCaller, $_POST["newOperationCustomer"], $nominationCustomer, $_POST["newOperationBuyer"], $_POST["newOperationSeller"], $_POST["newOperationSupplier"], $_POST["newOperationVessel"], $_POST["newOperationGoods"], $_POST["newOperationAmount"], $procT, $survT, $anlcT, $insLoc, $reqSurv, $reqAnl, $reqAnlSpecs);

    }

    if (isset($_POST["editOperationOptID"]) && !empty($_POST["editOperationOptID"]) && isset($_POST["editOperationCustomer"]) && !empty($_POST["editOperationCustomer"]) && isset($_POST["editOperationBuyer"]) && isset($_POST["editOperationSeller"]) && isset($_POST["editOperationSupplier"]) && isset($_POST["editOperationVessel"]) && !empty($_POST["editOperationVessel"]) && isset($_POST["editOperationGoods"]) && !empty($_POST["editOperationGoods"]) && isset($_POST["editOperationAmount"]) && !empty($_POST["editOperationAmount"]) && isset($_POST["editOperationReqSurv"]) && !empty($_POST["editOperationReqSurv"]) && isset($_POST["editOperationReqAnl"])) {


        $reqSurv = array();
        $reqAnl = array();
        $reqAnlSpecs = array();


        foreach ($_POST["editOperationReqSurv"] as $item) {
            $reqSurv["reqSurv" . $item] = $item;
        }

        if (count($_POST["editOperationReqAnl"]) > 0) {
            foreach ($_POST["editOperationReqAnl"] as $item) {
                $reqAnl["reqAnl" . $item] = $item;
                if (isset($_POST["editOperationReqSpec" . $item]) && !empty($_POST["editOperationReqSpec" . $item])) {
                    $reqAnlSpecs["reqSpec" . $item] = $_POST["editOperationReqSpec" . $item];
                }
            }
        }

        $nominationCustomer = null;

        if (isset($_POST["editOperationNominationCustomer"]) && !empty($_POST["editOperationNominationCustomer"])) {
            $nominationCustomer = $_POST["editOperationNominationCustomer"];
        }

        editOperation($apiCaller, $_POST["editOperationOptID"], $_POST["editOperationCustomer"], $nominationCustomer, $_POST["editOperationBuyer"], $_POST["editOperationSeller"], $_POST["editOperationSupplier"], $_POST["editOperationVessel"], $_POST["editOperationGoods"], $_POST["editOperationAmount"], $reqSurv, $reqAnl, $reqAnlSpecs);

    }

}