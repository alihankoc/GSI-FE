<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 28.02.2019
 * Time: 03:01
 */
include 'vendor/autoload.php';
$dotenv =\Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

date_default_timezone_set('Asia/Istanbul');

$email = $password = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["email"]) && isset($_POST["password"]) && !empty($_POST["email"]) && !empty($_POST["password"])) {

    $data = array(
        'email' => test_input($_POST["email"]),
        //'password' => password_hash(test_input($_POST["password"]), PASSWORD_BCRYPT)
        'password' => test_input($_POST["password"])
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://localhost:8080/auth/login');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_ENCODING, '');
    curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');

    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    $response = curl_exec($ch);


    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);

    if ($http_code == "200") {

        $token = json_decode($response)->token;
        $userObj = json_decode($response)->user;
        //header('Location: officer_main_page.php');

        $userID = $userObj->user_id;
        $userName = $userObj->name;
        $userSurname = $userObj->surname;
        $userEmail = $userObj->email;
        $userType = $userObj->user_type_id;
        $userOffice = $userObj->user_office_id;


        ob_start();
        session_start();

        $_SESSION["token"] = $token;
        $_SESSION["user_id"] = $userID;
        $_SESSION["user_name"] = $userName;
        $_SESSION["user_surname"] = $userSurname;
        $_SESSION["user_email"] = $userEmail;
        $_SESSION["user_type_id"] = $userType;
        $_SESSION["user_office_id"] = $userOffice;

        switch ($userType) {
            case "1":
                header('Location: admin_panel.php');
                break;
            case "2":
                header('Location: officer_main_page.php');
                break;
            case "3":
                header('Location: field_operation_page.php');
                break;
            case "4":
                header('Location: laboratory_analysis_page.php');
                break;
            case "7":
                header('Location: sample_tracking_page.php');
                break;
            case "8":
                header('Location: certificates.php');
                break;
        }

        ob_end_flush();

    } elseif ($http_code == "400") {
        header('Location: emp_login_page.php');
        setcookie("noUser", "noUser", time() + (2));
    } else {
        //header('Location: emp_login_page.php');
        //setcookie("connErr", "connErr", time() + (2));

        echo 'Curl hatası: ' . curl_error($ch);
    }


    curl_close($ch);


}

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function getHeaders($respHeaders)
{
    $headers = array();
    $headerText = substr($respHeaders, 0, strpos($respHeaders, "\r\n\r\n"));
    foreach (explode("\r\n", $headerText) as $i => $line) {
        if ($i === 0) {
            $headers['http_code'] = $line;
        } else {
            list ($key, $value) = explode(': ', $line);
            $headers[$key] = $value;
        }
    }
    return $headers;
}