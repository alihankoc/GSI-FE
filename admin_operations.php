<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 6.05.2019
 * Time: 00:18
 */

include_once 'ApiCaller.php';

session_start();

$apiCaller = new ApiCaller('1', $_SESSION["token"]);


/*$items = $apiCaller->sendRequest(array(
    'api_url' => 'https://lumen.krekpot.com/api/v1/viewUsers',
    'api_method' => 'get',
));*/

function deleteLocation($apiCaller, $locationID)
{
    $response = $apiCaller->sendRequest(array(
        'api_url' => 'https://lumen.krekpot.com/api/v1/deleteLocation/' . $locationID,
        'api_method' => 'delete',
    ));

    if ($response['error'] == false) {
        header('Location: office_management.php');
        setcookie("deleteSuccessL", "deleteSuccessVL", time() + (2));
    } elseif ($response['error'] == true) {
        if ($response['status'] == '401') {
            echo "There is no token";
        } elseif ($response['status'] == "400") {
            echo "Token expired or token decode error";
        } elseif ($response['status'] == "404") {
            echo "There is no such thing";
        } else {
            header('Location: office_management.php');
            setcookie("deleteErrorL", "deleteErrorVL", time() + (2));
        }
    }
}

function deleteOffice($apiCaller, $officeID)
{
    $response = $apiCaller->sendRequest(array(
        'api_url' => 'https://lumen.krekpot.com/api/v1/deleteOffice/' . $officeID,
        'api_method' => 'delete',
    ));

    if ($response['error'] == false) {
        header('Location: office_management.php');
        setcookie("deleteSuccessO", "deleteSuccessVO", time() + (2));
    } elseif ($response['error'] == true) {
        if ($response['status'] == '401') {
            echo "There is no token";
        } elseif ($response['status'] == "400") {
            echo "Token expired or token decode error";
        } elseif ($response['status'] == "404") {
            echo "There is no such thing";
        } else {
            header('Location: office_management.php');
            setcookie("deleteErrorO", "deleteErrorVO", time() + (2));
        }
    }
}

function saveCustomer($apiCaller, $companyName, $companyAddress, $companyEmail, $companyPhone, $contactName, $contactSurname, $contactTitle, $contactEmail, $contactPhone, $companyShortcode)
{
    $response = $apiCaller->sendRequest(array(
        'api_url' => 'https://lumen.krekpot.com/api/v1/newCustomer/',
        'api_method' => 'post',
        'companyName' => $companyName,
        'companyShortcode' => $companyShortcode,
        'companyAddress' => $companyAddress,
        'companyEmail' => $companyEmail,
        'companyPhone' => $companyPhone,
        'contactName' => $contactName,
        'contactSurname' => $contactSurname,
        'contactTitle' => $contactTitle,
        'contactEmail' => $contactEmail,
        'contactPhone' => $contactPhone,
    ));

    if ($response['error'] == false) {
        header('Location: customer_management.php');
        setcookie("addSuccess", "addSuccessV", time() + (2));
    } elseif ($response['error'] == true) {
        if ($response['status'] == '401') {
            echo "There is no token";
        } elseif ($response['status'] == "400") {
            echo "Token expired or token decode error";
        } elseif ($response['status'] == "404") {
            echo "There is no such thing";
        } else {
            header('Location: customer_management.php');
            setcookie("addError", "addErrorV", time() + (2));
        }
    }
}

function deleteCustomer($apiCaller, $customerID)
{
    $response = $apiCaller->sendRequest(array(
        'api_url' => 'https://lumen.krekpot.com/api/v1/deleteCustomer/' . $customerID,
        'api_method' => 'delete',
    ));

    if ($response['error'] == false) {
        header('Location: customer_management.php');
        setcookie("deleteSuccess", "deleteSuccessV", time() + (2));
    } elseif ($response['error'] == true) {
        if ($response['status'] == '401') {
            echo "There is no token";
        } elseif ($response['status'] == "400") {
            echo "Token expired or token decode error";
        } elseif ($response['status'] == "404") {
            echo "There is no such thing";
        } else {
            header('Location: customer_management.php');
            setcookie("deleteError", "deleteErrorV", time() + (2));
        }
    }
}

function saveUser($apiCaller, $name, $surname, $email, $password, $phone, $type, $office, $location)
{

    $response = $apiCaller->sendRequest(array(
        'api_url' => 'https://lumen.krekpot.com/api/v1/newUser',
        'api_method' => 'post',
        'name' => $name,
        'surname' => $surname,
        'email' => $email,
        'password' => $password,
        'phone' => $phone,
        'user_type' => $type,
        'user_office' => $office,
        'user_location' => $location,
    ));

    if ($response['error'] == false) {
        header('Location: employee_management.php');
        setcookie("addSuccess", "addSuccessV", time() + (2));
    } elseif ($response['error'] == true) {
        if ($response['status'] == '401') {
            echo "There is no token";
        } elseif ($response['status'] == "400") {
            echo "Token expired or token decode error";
        } elseif ($response['status'] == "404") {
            echo "There is no such thing";
        } else {
            header('Location: employee_management.php');
            setcookie("addError", "addErrorV", time() + (2));
        }
    }

}

function deleteUser($apiCaller, $userID)
{
    $response = $apiCaller->sendRequest(array(
        'api_url' => 'https://lumen.krekpot.com/api/v1/deleteUser/' . $userID,
        'api_method' => 'delete',
    ));

    if ($response['error'] == false) {
        header('Location: employee_management.php');
        setcookie("deleteSuccess", "deleteSuccessV", time() + (2));
    } elseif ($response['error'] == true) {
        if ($response['status'] == '401') {
            echo "There is no token";
        } elseif ($response['status'] == "400") {
            echo "Token expired or token decode error";
        } elseif ($response['status'] == "404") {
            echo "There is no such thing";
        } else {
            header('Location: employee_management.php');
            setcookie("deleteError", "deleteErrorV", time() + (2));
        }
    }

}

function getSelectedUser($apiCaller, $userID)
{
    $response = $apiCaller->sendRequest(array(
        'api_url' => 'https://lumen.krekpot.com/api/v1/viewUser/' . $userID,
        'api_method' => 'get',
    ));

    echo json_encode($response['data']);
}

function getSelectedCustomer($apiCaller, $customerID)
{
    $response = $apiCaller->sendRequest(array(
        'api_url' => 'https://lumen.krekpot.com/api/v1/viewCustomer/' . $customerID,
        'api_method' => 'get',
    ));

    echo json_encode($response['data']);
}

function getLocationOffices($apiCaller, $locationID)
{
    $response = $apiCaller->sendRequest(array(
        'api_url' => 'https://lumen.krekpot.com/api/v1/getLocationOffices/' . $locationID,
        'api_method' => 'get',
    ));

    echo json_encode($response['data']);
}

function saveLocation($apiCaller, $locationName)
{

    $response = $apiCaller->sendRequest(array(
        'api_url' => 'https://lumen.krekpot.com/api/v1/newLocation/',
        'api_method' => 'post',
        'locationName' => $locationName,
    ));

    if ($response['error'] == false) {
        header('Location: office_management.php');
        setcookie("addSuccessL", "addSuccessVL", time() + (2));
    } elseif ($response['error'] == true) {
        if ($response['status'] == '401') {
            echo "There is no token";
        } elseif ($response['status'] == "400") {
            echo "Token expired or token decode error";
        } elseif ($response['status'] == "404") {
            echo "There is no such thing";
        } else {
            header('Location: office_management.php');
            setcookie("addErrorL", "addErrorVL", time() + (2));
        }
    }

}

function saveOffice($apiCaller, $officeName, $locationID)
{

    $response = $apiCaller->sendRequest(array(
        'api_url' => 'https://lumen.krekpot.com/api/v1/newOffice/',
        'api_method' => 'post',
        'officeName' => $officeName,
        'officeLocation' => $locationID,
    ));

    if ($response['error'] == false) {
        header('Location: office_management.php');
        setcookie("addSuccessO", "addSuccessVO", time() + (2));
    } elseif ($response['error'] == true) {
        if ($response['status'] == '401') {
            echo "There is no token";
        } elseif ($response['status'] == "400") {
            echo "Token expired or token decode error";
        } elseif ($response['status'] == "404") {
            echo "There is no such thing";
        } else {
            header('Location: office_management.php');
            setcookie("addErrorO", "addErrorVO", time() + (2));
        }
    }

}

function deleteAnalysis($apiCaller, $analysisID)
{
    $response = $apiCaller->sendRequest(array(
        'api_url' => 'https://lumen.krekpot.com/api/v1/deleteAnalysis/' . $analysisID,
        'api_method' => 'delete',
    ));

    if ($response['error'] == false) {
        header('Location: category_management.php');
        setcookie("deleteSuccessA", "deleteSuccessVA", time() + (2));
    } elseif ($response['error'] == true) {
        if ($response['status'] == '401') {
            echo "There is no token";
        } elseif ($response['status'] == "400") {
            echo "Token expired or token decode error";
        } elseif ($response['status'] == "404") {
            echo "There is no such thing";
        } else {
            header('Location: category_management.php');
            setcookie("deleteErrorA", "deleteErrorVA", time() + (2));
        }
    }
}

function deleteAnalysisCondition($apiCaller, $analysisConditionID)
{
    $response = $apiCaller->sendRequest(array(
        'api_url' => 'https://lumen.krekpot.com/api/v1/deleteAnalysisCondition/' . $analysisConditionID,
        'api_method' => 'delete',
    ));

    if ($response['error'] == false) {
        header('Location: category_management.php');
        setcookie("deleteSuccessAC", "deleteSuccessVAC", time() + (2));
    } elseif ($response['error'] == true) {
        if ($response['status'] == '401') {
            echo "There is no token";
        } elseif ($response['status'] == "400") {
            echo "Token expired or token decode error";
        } elseif ($response['status'] == "404") {
            echo "There is no such thing";
        } else {
            header('Location: category_management.php');
            setcookie("deleteErrorAC", "deleteErrorVAC", time() + (2));
        }
    }
}

function deleteProcessType($apiCaller, $processTypeID)
{
    $response = $apiCaller->sendRequest(array(
        'api_url' => 'https://lumen.krekpot.com/api/v1/deleteProcessType/' . $processTypeID,
        'api_method' => 'delete',
    ));

    if ($response['error'] == false) {
        header('Location: category_management.php');
        setcookie("deleteSuccessP", "deleteSuccessVP", time() + (2));
    } elseif ($response['error'] == true) {
        if ($response['status'] == '401') {
            echo "There is no token";
        } elseif ($response['status'] == "400") {
            echo "Token expired or token decode error";
        } elseif ($response['status'] == "404") {
            echo "There is no such thing";
        } else {
            header('Location: category_management.php');
            setcookie("deleteErrorP", "deleteErrorVP", time() + (2));
        }
    }
}

function deleteSurveillanceType($apiCaller, $surveillanceTypeID)
{
    $response = $apiCaller->sendRequest(array(
        'api_url' => 'https://lumen.krekpot.com/api/v1/deleteSurveillanceType/' . $surveillanceTypeID,
        'api_method' => 'delete',
    ));

    if ($response['error'] == false) {
        header('Location: category_management.php');
        setcookie("deleteSuccessS", "deleteSuccessVS", time() + (2));
    } elseif ($response['error'] == true) {
        if ($response['status'] == '401') {
            echo "There is no token";
        } elseif ($response['status'] == "400") {
            echo "Token expired or token decode error";
        } elseif ($response['status'] == "404") {
            echo "There is no such thing";
        } else {
            header('Location: category_management.php');
            setcookie("deleteErrorS", "deleteErrorVS", time() + (2));
        }
    }
}

function saveAnalysis($apiCaller, $analysisName)
{

    $response = $apiCaller->sendRequest(array(
        'api_url' => 'https://lumen.krekpot.com/api/v1/newAnalysis/',
        'api_method' => 'post',
        'analysisName' => $analysisName,
    ));

    if ($response['error'] == false) {
        header('Location: category_management.php');
        setcookie("addSuccessA", "addSuccessVA", time() + (2));
    } elseif ($response['error'] == true) {
        if ($response['status'] == '401') {
            echo "There is no token";
        } elseif ($response['status'] == "400") {
            echo "Token expired or token decode error";
        } elseif ($response['status'] == "404") {
            echo "There is no such thing";
        } else {
            header('Location: category_management.php');
            setcookie("addErrorA", "addErrorVA", time() + (2));
        }
    }

}

function saveAnalysisCondition($apiCaller, $analysisConditionName)
{

    $response = $apiCaller->sendRequest(array(
        'api_url' => 'https://lumen.krekpot.com/api/v1/newAnalysisCondition/',
        'api_method' => 'post',
        'analysisConditionName' => $analysisConditionName,
    ));

    if ($response['error'] == false) {
        header('Location: category_management.php');
        setcookie("addSuccessAC", "addSuccessVAC", time() + (2));
    } elseif ($response['error'] == true) {
        if ($response['status'] == '401') {
            echo "There is no token";
        } elseif ($response['status'] == "400") {
            echo "Token expired or token decode error";
        } elseif ($response['status'] == "404") {
            echo "There is no such thing";
        } else {
            header('Location: category_management.php');
            setcookie("addErrorAC", "addErrorVAC", time() + (2));
        }
    }

}

function saveProcessType($apiCaller, $processTypeName)
{

    $response = $apiCaller->sendRequest(array(
        'api_url' => 'https://lumen.krekpot.com/api/v1/newProcessType/',
        'api_method' => 'post',
        'processTypeName' => $processTypeName,
    ));

    if ($response['error'] == false) {
        header('Location: category_management.php');
        setcookie("addSuccessP", "addSuccessVP", time() + (2));
    } elseif ($response['error'] == true) {
        if ($response['status'] == '401') {
            echo "There is no token";
        } elseif ($response['status'] == "400") {
            echo "Token expired or token decode error";
        } elseif ($response['status'] == "404") {
            echo "There is no such thing";
        } else {
            header('Location: category_management.php');
            setcookie("addErrorP", "addErrorVP", time() + (2));
        }
    }

}

function saveSurveillanceType($apiCaller, $surveillanceTypeName)
{

    $response = $apiCaller->sendRequest(array(
        'api_url' => 'https://lumen.krekpot.com/api/v1/newSurveillanceType/',
        'api_method' => 'post',
        'surveillanceTypeName' => $surveillanceTypeName,
    ));

    if ($response['error'] == false) {
        header('Location: category_management.php');
        setcookie("addSuccessS", "addSuccessVS", time() + (2));
    } elseif ($response['error'] == true) {
        if ($response['status'] == '401') {
            echo "There is no token";
        } elseif ($response['status'] == "400") {
            echo "Token expired or token decode error";
        } elseif ($response['status'] == "404") {
            echo "There is no such thing";
        } else {
            header('Location: category_management.php');
            setcookie("addErrorS", "addErrorVS", time() + (2));
        }
    }

}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST["deleteUser"]) && isset($_POST["userID"]) && !empty($_POST["userID"])) {
        deleteUser($apiCaller, $_POST["userID"]);
    }

    if (isset($_POST["selectedLocation"]) && !empty($_POST["selectedLocation"])) {
        getLocationOffices($apiCaller, $_POST["selectedLocation"]);
    }

    if (isset($_POST["userName"]) && !empty($_POST["userName"]) && isset($_POST["userSurname"]) && !empty($_POST["userSurname"]) && isset($_POST["userEmail"]) && !empty($_POST["userEmail"]) && isset($_POST["userPassword"]) && !empty($_POST["userPassword"]) && isset($_POST["userPhone"]) && !empty($_POST["userPhone"]) && isset($_POST["userType"]) && !empty($_POST["userType"]) && isset($_POST["userOffice"]) && !empty($_POST["userOffice"]) && isset($_POST["userLocation"]) && !empty($_POST["userLocation"])) {
        saveUser($apiCaller, $_POST["userName"], $_POST["userSurname"], $_POST["userEmail"], $_POST["userPassword"], $_POST["userPhone"], $_POST["userType"], $_POST["userOffice"], $_POST["userLocation"]);
    }

    if (isset($_POST["selectedUser"]) && !empty($_POST["selectedUser"])) {
        getSelectedUser($apiCaller, $_POST["selectedUser"]);
    }

    if (isset($_POST["companyName"]) && !empty($_POST["companyName"]) && isset($_POST["companyAddress"]) && !empty($_POST["companyAddress"]) && isset($_POST["companyEmail"]) && !empty($_POST["companyEmail"]) && isset($_POST["companyPhone"]) && !empty($_POST["companyPhone"]) && isset($_POST["contactName"]) && !empty($_POST["contactName"]) && isset($_POST["contactSurname"]) && !empty($_POST["contactSurname"]) && isset($_POST["contactTitle"]) && !empty($_POST["contactTitle"]) && isset($_POST["contactEmail"]) && !empty($_POST["contactEmail"]) && isset($_POST["contactPhone"]) && !empty($_POST["contactPhone"]) && isset($_POST["companyShortcode"]) && !empty($_POST["companyShortcode"])) {
        saveCustomer($apiCaller, $_POST["companyName"], $_POST["companyAddress"], $_POST["companyEmail"], $_POST["companyPhone"], $_POST["contactName"], $_POST["contactSurname"], $_POST["contactTitle"], $_POST["contactEmail"], $_POST["contactPhone"], $_POST["companyShortcode"]);
    }

    if (isset($_POST["editCompanyName"]) && !empty($_POST["editCompanyName"]) && isset($_POST["editCompanyAddress"]) && !empty($_POST["editCompanyAddress"]) && isset($_POST["editCompanyEmail"]) && !empty($_POST["editCompanyEmail"]) && isset($_POST["editCompanyPhone"]) && !empty($_POST["editCompanyPhone"]) && isset($_POST["editContactName"]) && !empty($_POST["editContactName"]) && isset($_POST["editContactSurname"]) && !empty($_POST["editContactSurname"]) && isset($_POST["editContactTitle"]) && !empty($_POST["editContactTitle"]) && isset($_POST["editContactEmail"]) && !empty($_POST["editContactEmail"]) && isset($_POST["editContactPhone"]) && !empty($_POST["editContactPhone"]) && isset($_POST["editCompanyShortcode"]) && !empty($_POST["editCompanyShortcode"])) {
        saveCustomer($apiCaller, $_POST["editCompanyName"], $_POST["editCompanyAddress"], $_POST["editCompanyEmail"], $_POST["editCompanyPhone"], $_POST["editContactName"], $_POST["editContactSurname"], $_POST["editContactTitle"], $_POST["editContactEmail"], $_POST["editContactPhone"], $_POST["editCompanyShortcode"]);
    }

    if (isset($_POST["deleteCustomer"]) && isset($_POST["customerID"]) && !empty($_POST["customerID"])) {
        deleteCustomer($apiCaller, $_POST["customerID"]);
    }

    if (isset($_POST["selectedCustomer"]) && !empty($_POST["selectedCustomer"])) {
        getSelectedCustomer($apiCaller, $_POST["selectedCustomer"]);
    }

    if (isset($_POST["deleteOffice"]) && isset($_POST["officeID"]) && !empty($_POST["officeID"])) {
        deleteOffice($apiCaller, $_POST["officeID"]);
    }

    if (isset($_POST["deleteLocation"]) && isset($_POST["locationID"]) && !empty($_POST["locationID"])) {
        deleteLocation($apiCaller, $_POST["locationID"]);
    }

    if (isset($_POST["officeLocation"]) && !empty($_POST["officeLocation"]) && isset($_POST["officeName"]) && !empty($_POST["officeName"])) {
        saveOffice($apiCaller, $_POST["officeName"], $_POST["officeLocation"]);
    }

    if (isset($_POST["locationName"]) && !empty($_POST["locationName"])) {
        saveLocation($apiCaller, $_POST["locationName"]);
    }

    if (isset($_POST["deleteAnalysis"]) && isset($_POST["analysisID"]) && !empty($_POST["analysisID"])) {
        deleteAnalysis($apiCaller, $_POST["analysisID"]);
    }

    if (isset($_POST["deleteAnalysisCondition"]) && isset($_POST["analysisConditionID"]) && !empty($_POST["analysisConditionID"])) {
        deleteAnalysisCondition($apiCaller, $_POST["analysisConditionID"]);
    }

    if (isset($_POST["deleteProcessType"]) && isset($_POST["processTypeID"]) && !empty($_POST["processTypeID"])) {
        deleteProcessType($apiCaller, $_POST["processTypeID"]);
    }

    if (isset($_POST["deleteSurveillanceType"]) && isset($_POST["surveillanceTypeID"]) && !empty($_POST["surveillanceTypeID"])) {
        deleteSurveillanceType($apiCaller, $_POST["surveillanceTypeID"]);
    }

    if (isset($_POST["analysisName"]) && !empty($_POST["analysisName"])) {
        saveAnalysis($apiCaller, $_POST["analysisName"]);
    }

    if (isset($_POST["analysisConditionName"]) && !empty($_POST["analysisConditionName"])) {
        saveAnalysisCondition($apiCaller, $_POST["analysisConditionName"]);
    }

    if (isset($_POST["processTypeName"]) && !empty($_POST["processTypeName"])) {
        saveProcessType($apiCaller, $_POST["processTypeName"]);
    }

    if (isset($_POST["surveillanceTypeName"]) && !empty($_POST["surveillanceTypeName"])) {
        saveSurveillanceType($apiCaller, $_POST["surveillanceTypeName"]);
    }

}