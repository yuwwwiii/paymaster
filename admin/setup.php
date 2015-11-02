<?php
include("../configurations/adminconfig.php");
include_once(SYSCONFIG_CLASS_PATH."admin/application.class.php");

Application::app_initialize(array('login_required'=>true));
$dbconn = Application::db_open();

define('_MODWHO'		,AppUser::getData('user_name'));
define('_MODWHEN' 		,date('Y-m-d H:i:s'));

$cMap = array(
"default" => "setup.php"
,"manageuser" => "manage_user.php"
,"manageusertype" => "manage_usertype.php"
,"managemodule" => "manage_modules.php"
,"managedept" => "manage_dept.php"
,"manage_comp" => "manage_comp.controller.php"
,"manage_bank" => "manage_bank.controller.php"
,"empcateg" => "empcateg.controller.php"
,"emptype" => "emptype.controller.php"
,"jobpost" => "jobpost.controller.php"
,"compbanks" => "compbanks.controller.php"
,"mnge_pe" => "mnge_pe.controller.php"
,"mnge_tt" => "mnge_tt.controller.php"
,"mnge_sc" => "mnge_sc.controller.php"
,"mnge_pg" => "mnge_pg.controller.php"
,"mnge_te" => "mnge_te.controller.php"
,"salaryclass" => "salaryclass.controller.php"
,"mnge_calen" => "mnge_calen.controller.php"
,"tax_excep" => "tax_excep.controller.php"
,"factor_rate" => "factor_rate.controller.php"
,"deductype" => "deductype.controller.php"
,"ottable" => "ottable.controller.php"
,"otrate" => "otrate.controller.php"
,"country" => "country.controller.php"
,"zipcode" => "zipcode.controller.php"
,"region" => "region.controller.php"
,"state_province" => "state_province.controller.php"
,"mnge_leave" => "mnge_leave.controller.php"
,"mnge_ta" => "mnge_ta.controller.php"
,"201status" => "201status.controller.php"
,"comptype" => "comptype.controller.php"
,"bankaccntype" => "bankaccntype.controller.php"
,"bckup_dbase" => "bckup_dbase.controller.php"
,"restore_dbase" => "restore_dbase.controller.php"
,"dload_dbase" => "dload_dbase.controller.php"
,"loantype" => "loantype.controller.php"
,"wagesetup" => "wagesetup.controller.php"
,"importcomp" => "importcomp.controller.php"
,"importbrch" => "importbrch.controller.php"
,"importdept" => "importdept.controller.php"
,"importjob" => "importjob.controller.php"
,"import201stat" => "import201stat.controller.php"
,"importemp_type" => "importemp_type.controller.php"
,"importemp_categ" => "importemp_categ.controller.php"
,"importot_rate" => "importot_rate.controller.php"
,"importleav_type" => "importleav_type.controller.php"
,"branchpro" => "branchpro.controller.php"
,"empclasify" => "empclasify.controller.php"
,"importcompbanks" => "importcompbanks.controller.php"
,"import201file" => "import201file.controller.php"
,"import201bank" => "import201bank.controller.php"
,"importtaxdepnts" => "importtaxdepnts.controller.php"
,"auditrail" => "auditrail.controller.php"
,"wagerate" => "wagerate.controller.php"
,"importloan" => "importloan.controller.php"
,"imbenduc" => "imbenduc.controller.php"
,"import_pe" => "import_pe.controller.php"
,"manage_decimal" => "mnge_decimal.controller.php"
,"mass_assign_factor_rate" => "mass_assign_factor_rate.controller.php"
,"mass_assign_ot_table" => "mass_assign_ot_table.controller.php"
,"mass_assign_bank_group" => "mass_assign_bank_group.controller.php"
,"mass_assign_deduction_type" => "mass_assign_deduction_type.controller.php"
,"mng_cf" => "mng_cf.controller.php"
,"taxpol" => "taxpol.controller.php"
,"audit_ePayslip" => "audit_epayslip.controller.php"
,"manageps_passwd" => "manageps_passwd.controller.php"
,"manage_cc" => "manage_cc.controller.php"
,"mass_assign_cc" => "mass_assign_cc.controller.php"
,"import_cc" => "import_cc.controller.php"
);

$cmapKey = (isset($_GET['statpos']) AND !empty($_GET['statpos']))?$_GET['statpos']:'default';

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
	include(SYSCONFIG_MODULE_PATH."admin/setup/".$cMap[$cmapKey]);
}else {
	//$indexErrMsg = 'Controller for "'.$_SERVER['PHP_SELF'].((isset($_GET['statpos']))?"?statpos=".$_GET['statpos']:"")."\" - does not exist.";
	//$indexErrMsg .= "<br> Please check the <b>\$cMap</b> on <b>$cMap[default]</b>";
	//include(SYSCONFIG_MODULE_PATH."admin/admin.php");
	header("Location: ".BASE_URL);
}


?>