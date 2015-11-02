<?php
//include("configurations/config.php");
include("../configurations/adminconfig.php");
include_once(SYSCONFIG_CLASS_PATH."admin/application.class.php");

Application::app_initialize(array('login_required'=>true));
$dbconn = Application::db_open();

// Controller Map
$cMap = array(
 "default" => "time.php"
,"dataimport" => "dataimport.controller.php"
,"tasummary" => "tasummary.controller.php"
);

$cmapKey = isset($_GET['statpos'])?$_GET['statpos']:'default';

if(isset($_GET['statpos']) && !empty($_GET['statpos']) && array_key_exists($_GET['statpos'],$cMap)){
	if($_SESSION['admin_session_obj']['user_data']['user_type'] == 'ESS' && $cmapKey != 'mypayslip'){
		$protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === FALSE ? 'http' : 'https';
		$host     = $_SERVER['HTTP_HOST'];
		$script   = $_SERVER['SCRIPT_NAME'];
		$params   = $_SERVER['QUERY_STRING'];
		$currentUrl = $protocol . '://' . $host . $script . '?' . $params;
		$fullname = $_SESSION['admin_session_obj']['user_data']['user_fullname'];
		$sql = "INSERT INTO audit_access SET fullname=?, track_module=?";
		$dbconn->Execute($sql,array($fullname,$currentUrl));
		header("Location: ".BASE_URL."reports.php?statpos=mypayslip");
	}
	include(SYSCONFIG_MODULE_PATH."admin/time/".$cMap[$cmapKey]);
}else {
	//$indexErrMsg = 'Controller for "'.$_SERVER['PHP_SELF'].((isset($_GET['statpos']))?"?statpos=".$_GET['statpos']:"")."\" - does not exist.";
	//$indexErrMsg .= "<br> Please check the <b>\$cMap</b> on <b>$cMap[default]</b>";
	//include(SYSCONFIG_MODULE_PATH."admin/index.php");
	header("Location: ".BASE_URL);
}


?>