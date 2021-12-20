<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 27.04.2019
 * Time: 23:02
 */
include 'vendor/autoload.php';
$dotenv =\Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

include_once 'ApiCaller.php';

session_start();

$apiCaller = new ApiCaller('1', $_SESSION["token"]);

$items = $apiCaller->sendRequest(array(
    'api_url' => $_ENV['LINK'].'hotoQR',
    'api_method' => 'get',
));

/*$items = $apiCaller->sendRequest(array(
    'api_url' => $_ENV['LINK'].'addExpense',
    'api_method' => 'post',
    'operationID' => 81,
    'operationExpenseContent' => 'Yeni Deneme',
    'operationExpenseAmount' => 120,
));*/
/*header('Content-Description: File Transfer');
header('Content-Type: image/jpeg');
header('Content-Disposition: attachment; filename="Haziran_Kiralama_Ronin_191000.jpg"');
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
ob_clean();
flush();
echo $items['data'];
exit;*/



if ($items['error'] == false) {
    print_r(json_encode($items['data']));
} elseif ($items['error'] == true) {
    if ($items['status'] == '401') {
        echo "There is no token";
    } elseif ($items['status'] == "400") {
        echo "Token expired or token decode error";
    } elseif ($items['status'] == "404") {
        echo "There is no such thing";
    } else {
        print_r($items);
    }
}