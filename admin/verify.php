<?php session_start();
include("../configurations/adminconfig.php");
$orangehrmSSO_TokenVerificationWSUrl = SYSCONFIG_ORANGE_URL.'symfony/web/index.php/api/wsCall/verifySingleSignOnToken';
$username = $_REQUEST['username'];
$token = $_REQUEST['token'];
$authHeaders = json_encode(array(
	'app_id' => SYSCONFIG_APP_ID,
	'app_token' => SYSCONFIG_APP_TOKEN,
	'session_token' => '_absdjwsef43ismk43efdker',
));
$methodParamHeaders = json_encode(array(
	'username' => $username,
	'tokenValue' => $token
));
$data = '';
$curlResource = curl_init();
curl_setopt($curlResource, CURLOPT_URL, $orangehrmSSO_TokenVerificationWSUrl);
curl_setopt($curlResource, CURLOPT_PORT, 80);
curl_setopt($curlResource, CURLOPT_VERBOSE, 0);
curl_setopt($curlResource, CURLOPT_HEADER, 0);
curl_setopt($curlResource, CURLOPT_SSLVERSION, 3);
curl_setopt($curlResource, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($curlResource, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curlResource, CURLOPT_POSTFIELDS, $data);
curl_setopt($curlResource, CURLOPT_HTTPHEADER, array(
	'Content-Type: text/json',
	"ohrm_ws_auth_parameters: {$authHeaders}",
	"ohrm_ws_method_parameters: {$methodParamHeaders}",
	"Content-length: " . strlen($data),
));
$resultData = curl_exec($curlResource);
if (!empty($resultData)) { 
    $authObj = json_decode($resultData); 
    $_SESSION['resultData'] = $authObj;
    if ($authObj->isAuthenticated) { 
        $_SESSION['ext_app.isAuthenticated'] = true; 
        $_SESSION['ext_app.username'] = $authObj->username; 
        $_SESSION['ext_app.isAdmin'] = $authObj->isAdmin; 
        $_SESSION['ext_app.rights'] = $authObj->rights; 
        header('Location: index.php'); 
    } else { 
        echo 'User was not authenticated by OrangeHRM SSO'; 
    } 
} else { 
    echo 'Received an empty response'; 
}

//$_SESSION['resultData'] = json_decode($resultData);
//print '<pre>'; print_r($_SESSION['resultData']); print '</pre>'; echo $_SESSION['resultData']->isAuthenticated; exit;
if($_SESSION['ext_app.isAuthenticated'] && $_SESSION['ext_app.isAdmin']){
	header('Location:'.BASE_URL);
}
//header('Location: http://192.168.1.11/ipay');
//print_r(json_decode($resultData));