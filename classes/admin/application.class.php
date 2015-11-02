<?php
require_once(SYSCONFIG_ROOT_PATH.'configurations/database/adodb/adodb.inc.php');
require_once(SYSCONFIG_CLASS_PATH.'admin/appsession.class.php');
require_once(SYSCONFIG_CLASS_PATH.'admin/appuser.class.php');
require_once(SYSCONFIG_CLASS_PATH.'util/misc.class.php');
require_once(SYSCONFIG_CLASS_PATH.'util/ddate.class.php');
if(isset($_SESSION['security'])){
	if (strpos($_SESSION['security'],$_GET['statpos']) !== false) {
	    
	} else {
		echo "<p style='color:red;'>You can't access this page.</p>"; exit;
	}
}
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
		$_SESSION['admin_session_obj']['timer'] = time();
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
	
	function mysqli_connect($dbName){
		$mysqli = mysqli_init();
		if (!$mysqli) {
		    die('mysqli_init failed');
		    break;
		}
		if (!$mysqli->options(MYSQLI_INIT_COMMAND, 'SET AUTOCOMMIT = 0')) {
		    die('Setting MYSQLI_INIT_COMMAND failed');
		    break;
		}
		if (!$mysqli->options(MYSQLI_OPT_CONNECT_TIMEOUT, 5)) {
		    die('Setting MYSQLI_OPT_CONNECT_TIMEOUT failed');
		    break;
		}
		if (!$mysqli->real_connect(SYSCONFIG_DBHOST, SYSCONFIG_DBUSER, SYSCONFIG_DBPASS, $dbName)) {
		    die('Connect Error (' . mysqli_connect_errno() . ') '. mysqli_connect_error());
		    break;
		}
		return $mysqli;
	}
	
	function getGeneralDecimalSettings(){
		$this->conn =& $this->db_open();
		$sql = "SELECT set_decimal_places FROM app_settings WHERE set_name='General Decimal Settings'";
		$rsResult = $this->conn->Execute($sql);
		if(!$rsResult->EOF){
			return $rsResult->fields['set_decimal_places'];
		}
	}
	
	function getFinalDecimalSettings(){
		$this->conn =& $this->db_open();
		$sql = "SELECT set_decimal_places FROM app_settings WHERE set_name='Final Decimal Settings'";
		$rsResult = $this->conn->Execute($sql);
		if(!$rsResult->EOF){
			return $rsResult->fields['set_decimal_places'];
		}
	}
	
	function setGeneralDecimalPlaces($value = 0){
		return number_format($value, $this->getGeneralDecimalSettings(),'.',',');
	}
	
	function setFinalDecimalPlaces($value = 0){
		return number_format($value, $this->getFinalDecimalSettings(),'.',',');
	}
}
?>