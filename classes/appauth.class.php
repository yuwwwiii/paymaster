<?php

class AppAuth {
	
	var $userID;
	var $userType;
	var $userName;
	var $user_status;
	var $user_email;
	var $user_emailpass;
	var $user_smtp;
	var $user_port;
	var $conn;
	var $Data;

	var $error;
	
	function AppAuth(&$conn) {
		$this->conn = $conn;
	}
	
	/**
	 * @param string $user
	 * @param string $pass
	 * @return int the user's member_id
	 * @static
	 */
	function doAuth($user,$pass) {
		$sql = "SELECT user_id, user_name, user_password, user_type, user_status, user_fullname, user_email, user_emailpass, user_smtp, user_port  FROM app_users WHERE user_name=? AND user_password=? LIMIT 1";
		$rs = $this->conn->Execute($sql, array($user,$pass));
		if ( $rs === false || $rs->EOF ) {
			$this->error = 404;
			return false;
		}
		
		$this->userID = $rs->fields['user_id'];
		$this->userType = $rs->fields['user_type'];
		$this->user_status = $rs->fields['user_status'];
		$this->userName = $rs->fields['user_name'];
		$this->user_email = $rs->fields['user_email'];
		$this->user_emailpass = $rs->fields['user_emailpass'];
		$this->user_smtp = $rs->fields['user_smtp'];
		$this->user_port = $rs->fields['user_port'];

		$this->Data = $rs->fields;
		
		return true;
	}
	
	function getModules($dbconn_ = null,$arrMenu_ = array(), $isChild_ = false, $level = 0){
		if(count($arrMenu_) > 0){
			$arrCtr = 0;
			foreach ($arrMenu_ as $key => $value) {
				$sql = "select * from app_modules where mnu_parent=? order by mnu_ord asc";
				$rsResult = $dbconn_->Execute($sql,array($value['mnu_id']));
				
				if($isChild_ && $level > 0)
				$mnuData .= ",";
				
				$mnuIcon = empty($value['mnu_icon'])?"null":"'$value[mnu_icon]'";
				$mnuLink = empty($value['mnu_link'])?"null":"'$value[mnu_link]'";
	
				$mnuData .= "[$mnuIcon, '$value[mnu_name]', $mnuLink, null, 'P$value[mnu_id]'";
				
				$arrMenuIn = array();
				while(!$rsResult->EOF){
					$arrMenuIn[] = $rsResult->fields;
					$rsResult->MoveNext();
				}
				if(count($arrMenuIn) > 0){
					$mnuData .= $this->getModules($dbconn_,$arrMenuIn,true,$level + 1);
				}
				$mnuData .= "]";
				if(!$isChild_ && (count($arrMenu_)-1) > $arrCtr++)
				$mnuData .= ",\n";
			}
		}
		return "$mnuData";
	}
	
}

?>