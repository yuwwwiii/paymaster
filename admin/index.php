<?php
include("../configurations/adminconfig.php");

$cMap = array(
"logout" => "logout.php"
,"default" => "admin.php"
,"customer" => "customer.php"
,"sync" => "sync.php"
,"autosync" => "sync.php"
,"send" => "send.php"
);

$cmapKey = isset($_GET['statpos'])?$_GET['statpos']:'default';

if(isset($_GET['statpos']) && !empty($_GET['statpos']) && array_key_exists($_GET['statpos'],$cMap)){
	include(SYSCONFIG_MODULE_PATH."admin/".$cMap[$cmapKey]);
} else {
	include(SYSCONFIG_MODULE_PATH."admin/admin.php");
}

?>