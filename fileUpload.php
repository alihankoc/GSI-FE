<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 10.07.2019
 * Time: 21:25
 */
session_start();

include_once 'ApiCaller.php';

$apiCaller = new ApiCaller('1', $_SESSION["token"]);

function sendMyFile($apiCaller, $file)
{
    $response = $apiCaller->sendRequest(array(
        'api_url' => 'https://lumen.krekpot.com/api/v1/uploadSurvDoc',
        'api_method' => 'file',
        'myFile' => $file,
    ));

    if ($response['error'] == false) {
        print_r(json_encode($response['data']));
    } elseif ($response['error'] == true) {
        if ($response['status'] == '401') {
            echo "There is no token";
        } elseif ($response['status'] == "400") {
            echo "Token expired or token decode error";
        } elseif ($response['status'] == "404") {
            echo "There is no such thing";
        } elseif ($response['status'] == "500") {
            echo $response['error'];
        }
    }

}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["sendFileButton"]) && isset($_FILES["myFile"]) && !empty($_FILES["myFile"])) {
        sendMyFile($apiCaller, $_FILES["myFile"]);
    }
}