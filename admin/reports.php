<?php
//include("configurations/config.php");
include("../configurations/adminconfig.php");
include_once(SYSCONFIG_CLASS_PATH."admin/application.class.php");

Application::app_initialize(array('login_required'=>true));
$dbconn = Application::db_open();

// Controller Map
$cMap = array(
"default" => "reports.php"
,"sss" => "sss.controller.php"
,"sss_collection" => "sss_collection.controller.php"
,"phic" => "phic.controller.php"
,"tax" => "tax.controller.php"
,"hdmf" => "hdmf.controller.php"
,"bir_alphalist" => "bir_alphalist.controller.php"
,"hdmf_collection" => "hdmf_collection.controller.php"
,"payroll_register" => "payroll_register.controller.php"
,"payroll_summary" => "payroll_summary.controller.php"
,"payroll_receipt" => "payroll_receipt.controller.php"
,"bank_export_report" => "bank_export_report.controller.php"
,"201mlist" => "201mlist.controller.php"
,"bdaylist" => "bdaylist.controller.php"
,"pslipr"=>"pslipr.controller.php"
,"ytdrept"=>"ytdrept.controller.php"
,"summary"=>"stat_summary.controller.php"
,"loanrept"=>"loanrept.controller.php"
,"other_lr"=>"other_lr.controller.php"
,"otreport"=>"otreport.controller.php"
,"otrpt"=>"otrpt.controller.php"
,"bonusrpt"=>"bonusrpt.controller.php"
,"mypayslip"=>"mypayslip.controller.php"
,"variance" => "variance.controller.php"
,"custom_paydetails" => "custom_paydetails.controller.php"
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
	include(SYSCONFIG_MODULE_PATH."admin/reports/".$cMap[$cmapKey]);
}else {
	//$indexErrMsg = 'Controller for "'.$_SERVER['PHP_SELF'].((isset($_GET['statpos']))?"?statpos=".$_GET['statpos']:"")."\" - does not exist.";
	//$indexErrMsg .= "<br> Please check the <b>\$cMap</b> on <b>$cMap[default]</b>";
	//include(SYSCONFIG_MODULE_PATH."admin/admin.php");
	header("Location: ".BASE_URL);
}
?>