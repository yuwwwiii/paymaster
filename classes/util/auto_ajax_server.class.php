<?php
require_once(SYSCONFIG_CLASS_PATH.'util/HTML/AJAX/Server.php');
require_once(SYSCONFIG_CLASS_PATH."util/ajax_server.class.php");

class clsAutoAJAXServer extends HTML_AJAX_Server{
    // this flag must be set for your init methods to be used
        var $initMethods = true;

        // init method for my ajax class
        function initclsAJAX_Server() {
			$ajax = new clsAJAX_Server();
			$this->registerClass($ajax);
        }
}

?>