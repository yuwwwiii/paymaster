<?php
//include("configurations/config.php");
 include("../configurations/adminconfig.php");
include_once(SYSCONFIG_CLASS_PATH."admin/application.class.php");
include_once(SYSCONFIG_CLASS_PATH."util/auto_ajax_server.class.php");

Application::app_initialize();

$objAutoAjaxServer = new clsAutoAJAXServer();
$objAutoAjaxServer->handleRequest();

?>