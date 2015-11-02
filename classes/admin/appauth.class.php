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
	var $user_comp_list;
	var $user_comp_list2;
	var $user_branch_list;
	var $user_branch_list2;
	var $user_paygroup_list;
	var $user_paygroup_list2;

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
//		$this->conn->debug = true;
		$sql = "SELECT au.user_id, au.emp_id, au.user_name, au.user_password, au.user_type, au.user_status, au.user_fullname, aud.ud_name, aut.user_type_name, emp.comp_id, au.user_comp_list, au.user_branch_list, au.user_paygroup_list
		FROM app_users au 
		JOIN app_userdept aud on au.ud_id = aud.ud_id 
		JOIN app_usertype aut on au.user_type = aut.user_type
		LEFT JOIN emp_masterfile emp on emp.emp_id = au.emp_id
		WHERE au.user_name=? AND au.user_password=? LIMIT 1";
		$rs = $this->conn->Execute($sql, array($user,$pass));
		if ( $rs === false || $rs->EOF ) {
			$this->error = 404;			
			return false;
		}
		
		$this->userID = $rs->fields['user_id'];
		$this->userType = $rs->fields['user_type'];
		$this->userName = $rs->fields['user_name'];
		$this->user_status = $rs->fields['user_status'];
		$this->user_email = $rs->fields['user_email'];
		$this->user_emailpass = $rs->fields['user_emailpass'];
		$this->user_smtp = $rs->fields['user_smtp'];
		$this->user_port = $rs->fields['user_port'];
		
		IF (empty($rs->fields['user_comp_list']) OR is_null($rs->fields['user_comp_list']) OR $rs->fields['user_comp_list']=='N;') {//company
			$this->user_comp_list = $rs->fields['user_comp_list']=array();
		}ELSE{
			$this->user_comp_list = unserialize($rs->fields['user_comp_list']);
			$this->user_comp_list2 = implode(',',$this->user_comp_list);
		}
		IF (empty($rs->fields['user_branch_list']) OR is_null($rs->fields['user_branch_list']) OR $rs->fields['user_branch_list']=='N;') {//location
			$this->user_branch_list = $rs->fields['user_branch_list']=array();
		}ELSE{
			$this->user_branch_list = unserialize($rs->fields['user_branch_list']);
			$this->user_branch_list2 = implode(',',$this->user_branch_list);
		}
		IF (empty($rs->fields['user_paygroup_list']) OR is_null($rs->fields['user_paygroup_list']) OR $rs->fields['user_paygroup_list']=='N;') {//paygroup
			$this->user_paygroup_list = $rs->fields['user_paygroup_list']=array();
		}ELSE{
			$this->user_paygroup_list = unserialize($rs->fields['user_paygroup_list']);
			$this->user_paygroup_list2 = implode(',',$this->user_paygroup_list);
		}
		IF ($this->user_status === '0' || $this->user_status === '2') {
			return false;
		}
		
		$this->Data = $rs->fields;
		
		return true;
	}
	
	function getModules($dbconn_ = null,$arrMenu_ = array(), $isChild_ = false, $level = 0){
		if(count($arrMenu_) > 0){
			$arrCtr = 0;
			foreach ($arrMenu_ as $key => $value) {
				$sql = "select * from app_modules appmod 
				inner join app_userstypeaccess auta on appmod.mnu_id = auta.mnu_id 
				inner join app_users au on au.ud_id = auta.ud_id
				where appmod.mnu_status=1 and appmod.mnu_parent=? and auta.user_type=? and au.user_id=? 
				order by appmod.mnu_ord asc";
				$rsResult = $dbconn_->Execute($sql,array($value['mnu_id'],$_SESSION['admin_session_obj']['user_type'],$_SESSION['admin_session_obj']['user_id']));
				
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