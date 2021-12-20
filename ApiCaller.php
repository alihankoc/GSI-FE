<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 26.04.2019
 * Time: 00:17
 */
include 'vendor/autoload.php';
$dotenv =\Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

class ApiCaller
{

    //some variables for the object
    private $_app_id;
    private $_app_key;

    //construct an ApiCaller object, taking an
    //APP ID, APP KEY and API URL parameter
    public function __construct($app_id, $app_key)
    {
        $this->_app_id = $app_id;
        $this->_app_key = $app_key;
    }

    public function sendRequest($request_params)
    {

        $params = (array)$request_params;

        $api_url = $params['api_url'];
        $api_method = $params['api_method'];
        $authorization = "Authorization: Bearer " . $this->_app_key;

        unset($params['api_url']);
        unset($params['api_method']);

        /*$paramAsString = '';
        foreach ($params as $key => $value) {
            $paramAsString .= $key . '=' . urlencode($value) . '&';
        }

        $paramAsString = substr($paramAsString, 0, -1);*/

        if ($api_method != 'file') {
            foreach ($params as $key => $value) {
                if (is_array($value)) {
                    if (isset($params['isDownload'])) {
                        $params[$key] = $value;
                    } else {
                        $params[$key] = urlencode($value);
                    }
                } else {
                    if ($this->validateDate($value)) {
                        $params[$key] = $value;
                    } else {
                        if (isset($params['isDownload'])) {
                            $params[$key] = $value;
                        } else {
                            $params[$key] = urlencode($value);
                        }
                    }
                }
            }
        }

        $curl = curl_init();

        if ($api_method == 'get') {

            curl_setopt($curl, CURLOPT_URL, $api_url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));

        } elseif ($api_method == 'post') {

            curl_setopt_array($curl, array(
                //CURLOPT_PORT => "8000",
                CURLOPT_URL => $api_url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($params),
                CURLOPT_HTTPHEADER => array(
                    $authorization,
                    "Content-Type: application/x-www-form-urlencoded",
                    "cache-control: no-cache"
                ),
            ));

            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));


        } elseif ($api_method == "put") {

            curl_setopt_array($curl, array(
                //CURLOPT_PORT => "8000",
                //CURLOPT_URL => $api_url . "?" . $paramAsString,
                CURLOPT_URL => $api_url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "PUT",
                CURLOPT_POSTFIELDS => json_encode($params),
                CURLOPT_HTTPHEADER => array(
                    $authorization,
                    "Content-Type: application/x-www-form-urlencoded",
                    "cache-control: no-cache"
                ),
            ));

            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));

        } elseif ($api_method == "delete") {

            curl_setopt_array($curl, array(
                //CURLOPT_PORT => "8000",
                CURLOPT_URL => $api_url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "DELETE",
                CURLOPT_POSTFIELDS => json_encode($params),
                CURLOPT_HTTPHEADER => array(
                    $authorization,
                    "Content-Type: application/x-www-form-urlencoded",
                    "cache-control: no-cache"
                ),
            ));

            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));

        } elseif ($api_method == "file") {

            $comingFiles = $params["myFile"];
            $total_count = count($comingFiles['name']);
            $postParams = array();
            //$fileNameArray = array();

            for ($i = 0; $i < $total_count; $i++) {
                $cSingleFile = new CURLFile($comingFiles['tmp_name'][$i], $comingFiles['type'][$i], $comingFiles['name'][$i]);
                $postParams['fileList[' . $i . ']'] = $cSingleFile;
                //array_push($fileNameArray, $comingFiles['name'][$i]);
            }


            if (isset($params["survPhotoFormID"])) {
                $postParams['survPhotoFormID'] = $params["survPhotoFormID"];
            }

            if (isset($params["survDocumentFormID"])) {
                $postParams['survDocumentFormID'] = $params["survDocumentFormID"];
            }

            /*for ($j = 0; $j < $total_count; $j++) {
                $postParams['fileNameList[' . $j . ']'] = $fileNameArray[$j];
            }*/

            curl_setopt_array($curl, array(
                CURLOPT_URL => $api_url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $postParams,
            ));
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data', "cache-control: no-cache", $authorization));
        }

        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        //curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));


        $response = curl_exec($curl);


        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);

        $result = array();
        $result['status'] = $http_code;

        curl_close($curl);

        if ($http_code == "200") {

            $result['error'] = false;
            if (isset($params['isDownload'])) {
                $result['data'] = $response;
            } else {
                $result['data'] = json_decode(urldecode($response));
            }


        } else {
            $result['error'] = true;
            $result['ermsg'] = $response;
        }


        return $result;


    }

    function validateDate($date, $format = 'Y-m-d\TH:i')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }


}