<?php
//include("configurations/config.php");
include("../configurations/adminconfig.php");
include_once(SYSCONFIG_CLASS_PATH."admin/application.class.php");

Application::app_initialize(array('login_required'=>true));
$dbconn = Application::db_open();

define('_MODWHO'		,AppUser::getData('user_name'));
define('_MODWHEN' 		,date('Y-m-d H:i:s'));
// Controller Map
$cMap = array(
"default" => "transaction.php"
,"emp_masterfile" => "emp_masterfile.controller.php"
,"process_payroll" => "process_payroll.controller.php"
,"ps_amend" => "ps_amend.controller.php"
,"payroll_details" => "payroll_details.controller.php"
,"time_attend" => "time_attend.controller.php"
,"resignation" => "resignation.controller.php"
,"201file_review" => "201file_review.controller.php"
,"for_hire" => "for_hire.controller.php"
,"applicant_database" => "applicant_database.controller.php"
,"application_form" => "application_form.controller.php"
,"otherprocess" => "otherprocess.controller.php"
,"loan_app" => "loan_app.controller.php"
,"rbd"=>"rbd.controller.php"
,"paydetail"=>"paydetail.controller.php"
,"recur_setup"=>"recur_setup.controller.php"
,"amendimp"=>"amendimp.controller.php"
,"mng_taimp"=>"mng_taimp.controller.php"
,"ytd_payelem"=>"ytd_payelem.controller.php"
,"ytd_import"=>"ytd_sumtotal.controller.php"
,"ytdentry_process"=>"ytdentry_process.controller.php"
,"ytdentry_details"=>"ytdentry_details.controller.php"
);

$cmapKey = isset($_GET['statpos'])?$_GET['statpos']:'default';

if(isset($_GET['statpos']) && !empty($_GET['statpos']) && array_key_exists($_GET['statpos'],$cMap)){
	if($_SESSION['admin_session_obj']['user_data']['user_type'] == 'ESS'){
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
	include(SYSCONFIG_MODULE_PATH."admin/transaction/".$cMap[$cmapKey]);
}else {
	//$indexErrMsg = 'Controller for "'.$_SERVER['PHP_SELF'].((isset($_GET['statpos']))?"?statpos=".$_GET['statpos']:"")."\" - does not exist.";
	//$indexErrMsg .= "<br> Please check the <b>\$cMap</b> on <b>$cMap[default]</b>";
	//include(SYSCONFIG_MODULE_PATH."admin/index.php");
	header("Location: ".BASE_URL);
}


?>