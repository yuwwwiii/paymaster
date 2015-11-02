<?php
//include("configurations/config.php");
include("../configurations/adminconfig.php");
include_once(SYSCONFIG_CLASS_PATH."admin/application.class.php");

Application::app_initialize(array('login_required'=>true));

// Controller Map
$cMap = array(
 "default" => "popup.php"
,"popuptype" => "popuptype.controller.php"
,"popupcateg" => "popupcateg.controller.php"
,"popupdepart" => "popupdepart.controller.php"
,"popupcomp" => "popupcomp.controller.php"
,"popupjobpost" => "popupjobpost.controller.php"
,"popupbank" => "popupbank.controller.php"
,"popupsalarytype" => "popupsalarytype.controller.php"
,"popuptaxep" => "popuptaxep.controller.php"
,"popupfactorrate" => "popupfactorrate.controller.php"
,"popupdectype" => "popupdectype.controller.php"
,"popupotrates" => "popupotrates.controller.php"
,"popupzipcode" => "popupzipcode.controller.php"
,"popupcity" => "popupcity.controller.php"
,"popuppayslip_ot" => "popuppayslip_ot.controller.php"
,"popuppayslip_ta" => "popuppayslip_ta.controller.php"
,"popupregion" => "popupregion.controller.php"
,"popupcountry" => "popupcountry.controller.php"
,"popupbenpe" => "popupbenpe.controller.php"
,"popup_bankinfo" => "popup_bankinfo.controller.php"
,"popup_paydetails" => "popup_paydetails.controller.php"
,"popuploanpaymenthistory" => "popuploanpaymenthistory.controller.php"
);

$cmapKey = isset($_GET['statpos'])?$_GET['statpos']:'default';

if (isset($_GET['statpos']) && !empty($_GET['statpos']) && array_key_exists($_GET['statpos'],$cMap)) {
	include(SYSCONFIG_MODULE_PATH."admin/popup/".$cMap[$cmapKey]);
} else {
	$indexErrMsg = 'Controller for "'.$_SERVER['PHP_SELF'].((isset($_GET['statpos']))?"?statpos=".$_GET['statpos']:"")."\" - does not exist.";
	$indexErrMsg .= "<br /> Please check the <strong>\$cMap</strong> on <strong>$cMap[default]</strong>";
	include(SYSCONFIG_MODULE_PATH."admin/index.php");
}
?>