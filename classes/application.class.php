<?php

require_once(SYSCONFIG_ROOT_PATH.'configurations/database/adodb/adodb.inc.php');
require_once(SYSCONFIG_CLASS_PATH.'appsession.class.php');
require_once(SYSCONFIG_CLASS_PATH.'appuser.class.php');

class Application {
	
	/**
	 * @var ADOConnection
	 */var $db;
	
	function app_initialize($params = array()) {
		session_start();
		
		Application::session_check($params);
		Application::session_update($params);
//		Application::db_open();
	}

	function session_check($params) {
		// Application::session_update($params);
		AppSession::check($params);
	}

	function session_update($params=array()) {
		$_SESSION['session_obj']['timer'] = time();
	}

	/**
	 * pass $params[newsession] as array of new session obj variables
	 *
	 * @param unknown_type $params
	 * @static
	 */
	function session_create($params) {
		// destroy all in session first. this will avoid session fixation attcks.
		// session_destroy();
		AppSession::create($params);
	}

	/**
	 * returns ado connection handle
	 *
	 * @return ADOConnection
	 */
	function & db_open($paramDBName_ = null) {
		static $conn;
		$dbName = is_null($paramDBName_)?SYSCONFIG_DBNAME:$paramDBName_;
		// if conn exists, no need to reconnect
		if ( !$conn ) {
			$conn = NewADOConnection('mysql');
			$conn->Connect(
				SYSCONFIG_DBHOST,
				SYSCONFIG_DBUSER,
				SYSCONFIG_DBPASS,
				$dbName);
			$conn->SetFetchMode(ADODB_FETCH_ASSOC);
			return $conn;
		} else {
			return $conn;
		}

	}
	
}

?>