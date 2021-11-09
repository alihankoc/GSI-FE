<?php
include_once 'ApiCaller.php';

session_start();
date_default_timezone_set('Asia/Istanbul');


$apiCaller = new ApiCaller('1', $_SESSION["token"]);

function getAllSamplesExcel($apiCaller, $sampleHtmlContent)
{
    $response = $apiCaller->sendRequest(array(
        'api_url' => 'https://lumen.krekpot.com/api/v1/downloadAllSamplesExcel',
        'api_method' => 'post',
        'isDownload' => true,
        'htmlContent' => $sampleHtmlContent,
    ));


    $fileToDownload = $response['data'];

    //START DOWNLOAD
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=Yolbilgisi_'.date("d-m-Y").'_'.date("H:i:s").'.xls');
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . strlen($fileToDownload));
    ob_clean();
    flush();
    echo $fileToDownload;
    exit;
}

function createSample($apiCaller, $sampleName, $typeOfGoods, $deliveryMethod, $deliveryDate, $amount, $isExternal, $samplePlace, $operationID)
{

    $response = $apiCaller->sendRequest(array(
        'api_url' => 'https://lumen.krekpot.com/api/v1/addNewSample',
        'api_method' => 'post',
        'sampleName' => $sampleName,
        'typeOfGoods' => $typeOfGoods,
        'deliveryMethod' => $deliveryMethod,
        'deliveryDate' => $deliveryDate,
        'amount' => $amount,
        'isExternal' => $isExternal,
        'samplePlace' => $samplePlace,
        'operationID' => $operationID,
    ));

    if ($response['error'] == false) {

        if (isset($response['data']->success)) {
            setcookie("successfulAddSample", $response['data']->success, time() + 60 * 60 * 24 * 1);
            header('Location: sample_tracking_page.php');
        } elseif (isset($response['data']->error)) {
            setcookie("errorAddSample", $response['data']->error, time() + 60 * 60 * 24 * 1);
            header('Location: sample_tracking_page.php');
        }

    } elseif ($response['error'] == true) {
        if ($response['status'] == '401') {
            echo "There is no token";
        } elseif ($response['status'] == "400") {
            echo "Token expired or token decode error";
        } elseif ($response['status'] == "404") {
            echo "There is no such thing";
        } else {
            header('Location: sample_tracking_page.php');
        }
    }
}

function unavailableSample($apiCaller, $sampleID)
{
    $response = $apiCaller->sendRequest(array(
        'api_url' => 'https://lumen.krekpot.com/api/v1/makeUnavailable',
        'api_method' => 'post',
        'sampleID' => $sampleID,
    ));

    if ($response['error'] == false) {

        if (isset($response['data']->success)) {
            setcookie("successfulUnavailableSample", $response['data']->success, time() + 60 * 60 * 24 * 1);
            header('Location: sample_tracking_page.php');
        } elseif (isset($response['data']->error)) {
            setcookie("errorUnavailableSample", $response['data']->error, time() + 60 * 60 * 24 * 1);
            header('Location: sample_tracking_page.php');
        }

    } elseif ($response['error'] == true) {
        if ($response['status'] == '401') {
            echo "There is no token";
        } elseif ($response['status'] == "400") {
            echo "Token expired or token decode error";
        } elseif ($response['status'] == "404") {
            echo "There is no such thing";
        } else {
            header('Location: sample_tracking_page.php');
        }
    }
}

function availableSample($apiCaller, $sampleID)
{
    $response = $apiCaller->sendRequest(array(
        'api_url' => 'https://lumen.krekpot.com/api/v1/makeAvailable',
        'api_method' => 'post',
        'sampleID' => $sampleID,
    ));

    if ($response['error'] == false) {

        if (isset($response['data']->success)) {
            setcookie("successfulAvailableSample", $response['data']->success, time() + 60 * 60 * 24 * 1);
            header('Location: sample_tracking_page.php');
        } elseif (isset($response['data']->error)) {
            setcookie("errorAvailableSample", $response['data']->error, time() + 60 * 60 * 24 * 1);
            header('Location: sample_tracking_page.php');
        }

    } elseif ($response['error'] == true) {
        if ($response['status'] == '401') {
            echo "There is no token";
        } elseif ($response['status'] == "400") {
            echo "Token expired or token decode error";
        } elseif ($response['status'] == "404") {
            echo "There is no such thing";
        } else {
            header('Location: sample_tracking_page.php');
        }
    }
}

function deleteSample($apiCaller, $sampleID)
{
    $response = $apiCaller->sendRequest(array(
        'api_url' => 'https://lumen.krekpot.com/api/v1/deleteSample',
        'api_method' => 'delete',
        'sampleID' => $sampleID,
    ));

    if ($response['error'] == false) {

        if (isset($response['data']->success)) {
            setcookie("successfulDeleteSample", $response['data']->success, time() + 60 * 60 * 24 * 1);
            header('Location: sample_tracking_page.php');
        } elseif (isset($response['data']->error)) {
            setcookie("errorDeleteSample", $response['data']->error, time() + 60 * 60 * 24 * 1);
            header('Location: sample_tracking_page.php');
        }

    } elseif ($response['error'] == true) {
        if ($response['status'] == '401') {
            echo "There is no token";
        } elseif ($response['status'] == "400") {
            echo "Token expired or token decode error";
        } elseif ($response['status'] == "404") {
            echo "There is no such thing";
        } else {
            header('Location: sample_tracking_page.php');
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST["unavailableSampleID"]) && !empty($_POST["unavailableSampleID"])) {
        unavailableSample($apiCaller, $_POST["unavailableSampleID"]);
    }

    if (isset($_POST["availableSampleID"]) && !empty($_POST["availableSampleID"])) {
        availableSample($apiCaller, $_POST["availableSampleID"]);
    }

    if (isset($_POST["deleteSampleID"]) && !empty($_POST["deleteSampleID"])) {
        deleteSample($apiCaller, $_POST["deleteSampleID"]);
    }

    if (isset($_POST["sampleHtmlContent"]) && !empty($_POST["sampleHtmlContent"])) {
        getAllSamplesExcel($apiCaller, $_POST["sampleHtmlContent"]);
    }

    if (isset($_POST["sampleName"]) && isset($_POST["typeOfGoods"]) && isset($_POST["deliveryMethod"]) && isset($_POST["deliveryDate"]) && isset($_POST["amount"]) && isset($_POST["isExternal"]) && isset($_POST["samplePlace"])) {
        if (isset($_POST["operationID"])) {
            createSample($apiCaller, $_POST["sampleName"], $_POST["typeOfGoods"], $_POST["deliveryMethod"], $_POST["deliveryDate"], $_POST["amount"], $_POST["isExternal"], $_POST["samplePlace"], $_POST["operationID"]);
        } else {
            createSample($apiCaller, $_POST["sampleName"], $_POST["typeOfGoods"], $_POST["deliveryMethod"], $_POST["deliveryDate"], $_POST["amount"], $_POST["isExternal"], $_POST["samplePlace"], null);
        }
    }
}