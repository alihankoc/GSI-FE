<?php
include_once 'ApiCaller.php';
include 'vendor/autoload.php';
$dotenv =\Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

session_start();

date_default_timezone_set('Europe/Istanbul');

$apiCaller = new ApiCaller('1', $_SESSION["token"]);

function generateCertificate($apiCaller, $certificateNo, $certificateJobName, $certificateCustomer, $certificateNote, $certificateEditor, $certificateType)
{

    $apiArray = array(
        'api_url' => $_ENV['LINK'].'generateCertificate',
        'api_method' => 'post',
        'certificateNo' => $certificateNo,
        'certificateJobName' => $certificateJobName,
        'certificateCustomer' => $certificateCustomer,
        'certificateNote' => $certificateNote,
        'certificateEditor' => $certificateEditor,
        'certificateType' => $certificateType,
    );


    $response = $apiCaller->sendRequest($apiArray);


    if ($response['error'] == false) {
        if (isset($response['data']->success)) {
            setcookie("successfulGenerateCertificate", $response['data']->success, time() + 60 * 60 * 24 * 1, "/");
            header('Location: certificates.php');
        } elseif (isset($response['data']->error)) {
            setcookie("errorGenerateCertificate", $response['data']->error, time() + 60 * 60 * 24 * 1, "/");
            header('Location: new_certificate.php');
        }
    } elseif ($response['error'] == true) {
        if ($response['status'] == '401') {
            echo "There is no token";
        } elseif ($response['status'] == "400") {
            echo "Token expired or token decode error";
        } elseif ($response['status'] == "404") {
            echo "There is no such thing";
        } else {
            header('Location: certificates.php');
        }
    }

}

function approveCertificate($apiCaller, $certificateID)
{
    $response = $apiCaller->sendRequest(array(
        'api_url' => $_ENV['LINK'].'approveCertificate',
        'api_method' => 'post',
        'approveCertificateID' => $certificateID,
    ));

    if ($response['error'] == false) {

        if (isset($response['data']->success)) {
            setcookie("successfulApproveCertificate", $response['data']->success, time() + 60 * 60 * 24 * 1);
            header('Location: certificates.php');
        } elseif (isset($response['data']->error)) {
            setcookie("errorApproveCertificate", $response['data']->error, time() + 60 * 60 * 24 * 1);
            header('Location: certificates.php');
        }

    } elseif ($response['error'] == true) {
        if ($response['status'] == '401') {
            echo "There is no token";
        } elseif ($response['status'] == "400") {
            echo "Token expired or token decode error";
        } elseif ($response['status'] == "404") {
            echo "There is no such thing";
        } else {
            header('Location: certificates.php');
        }
    }
}

function removeDraftCertificate($apiCaller, $certificateID)
{
    $response = $apiCaller->sendRequest(array(
        'api_url' => $_ENV['LINK'].'removeDraftCertificate',
        'api_method' => 'post',
        'removeDraftCertificateID' => $certificateID,
    ));

    if ($response['error'] == false) {

        if (isset($response['data']->success)) {
            setcookie("successfulRemoveDraftCertificate", $response['data']->success, time() + 60 * 60 * 24 * 1);
            header('Location: certificates.php');
        } elseif (isset($response['data']->error)) {
            setcookie("errorRemoveDraftCertificate", $response['data']->error, time() + 60 * 60 * 24 * 1);
            header('Location: certificates.php');
        }

    } elseif ($response['error'] == true) {
        if ($response['status'] == '401') {
            echo "There is no token";
        } elseif ($response['status'] == "400") {
            echo "Token expired or token decode error";
        } elseif ($response['status'] == "404") {
            echo "There is no such thing";
        } else {
            header('Location: certificates.php');
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {


    if (isset($_POST["certificateEditor"]) && !empty($_POST["certificateEditor"]) && isset($_POST["certificateNo"]) && !empty($_POST["certificateNo"]) && isset($_POST["certificateJobName"]) && !empty($_POST["certificateJobName"]) && isset($_POST["certificateCustomer"]) && !empty($_POST["certificateCustomer"]) && isset($_POST["certificateType"]) && !empty($_POST["certificateType"])) {
        if (isset($_POST["certificateNote"]) && !empty($_POST["certificateNote"])) {
            generateCertificate($apiCaller, $_POST["certificateNo"], $_POST["certificateJobName"], $_POST["certificateCustomer"], $_POST["certificateNote"], $_POST["certificateEditor"], $_POST["certificateType"]);
        } else {
            generateCertificate($apiCaller, $_POST["certificateNo"], $_POST["certificateJobName"], $_POST["certificateCustomer"], null, $_POST["certificateEditor"], $_POST["certificateType"]);
        }

    }


    if (isset($_POST["approveCertificateID"]) && !empty($_POST["approveCertificateID"])) {
        approveCertificate($apiCaller, $_POST["approveCertificateID"]);
    }

    if (isset($_POST["removeCertificateID"]) && !empty($_POST["removeCertificateID"])) {
        removeDraftCertificate($apiCaller, $_POST["removeCertificateID"]);
    }
}