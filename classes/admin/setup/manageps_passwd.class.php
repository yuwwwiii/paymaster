<?php
/**
 * Initial Declaration
 */

/**
 * Class Module
 * @author  JIM
 */
class clsManagePS_Passwd{
	var $conn;
	var $fieldMap;
	var $Data;

	/**
	 * Class Constructor
	 * @param object $dbconn_
	 * @return clsManagePS_Passwd object
	 */
	function clsManagePS_Passwd($dbconn_ = null){
		$this->conn =& $dbconn_;
		$this->fieldMap = array(
		"ps_passwd_password" => "ps_passwd_password"
		);
	}

	/**
	 * Get the records from the database
	 * @param string $id_
	 * @return array
	 */
	function dbFetch($id_ = ""){
		$sql = "select CONCAT(pi_fname,' ',pi_lname) as fullname, ps_passwd_password
				from emp_personal_info a
				join emp_masterfile b on (b.pi_id=a.pi_id)
				left join app_ps_passwd c on (c.emp_id=b.emp_id)
				where b.emp_id=?";
		$rsResult = $this->conn->Execute($sql,array($id_));
		if(!$rsResult->EOF){
			return $rsResult->fields;
		}
	}
	
	/**
	 * Populate array parameters to Data Variable
	 * @param array $pData_
	 * @param boolean $isForm_
	 * @return bool
	 */
	function doPopulateData($pData_ = array(),$isForm_ = false){
		if(count($pData_)>0){
			foreach ($this->fieldMap as $key => $value) {
				if ($isForm_) {
					$this->Data[$value] = $pData_[$value];
				}else {
					$this->Data[$key] = $pData_[$value];
				}
			}
			return true;
		}
		return false;
	}

	/**
	 * Validation function
	 * @param array $pData_
	 * @return bool
	 */
	function doValidateData($pData_ = array()){
		$isValid = true;
		if(strlen($pData_['ps_passwd_password']) < 6){
			$isValid = false;
			$_SESSION['eMsg'][] = "Password should not be less than 6 characters";
		}
		if($pData_['ps_passwd_password'] != $pData_['cps_passwd_password']){
			$isValid = false;
			$_SESSION['eMsg'][] = "Password and Confirm Password should match.";
		}
//		$isValid = false;
		return $isValid;
	}

	/**
	 * Save New Record
	 */
	function doSaveAdd(){
		$flds = array();
		foreach ($this->Data as $keyData => $valData) {
			$valData = addslashes($valData);
			$flds[] = "$keyData='$valData'";
		}
		$fields = implode(", ",$flds);
		$sql = "INSERT INTO /*app_modules*/ SET $fields";
		$this->conn->Execute($sql);
		$_SESSION['eMsg']="Successfully Added.";
	}

	/**
	 * Save Update
	 *
	 */
	function doSaveEdit(){
		$id = $_GET['edit'];
		$flds = array();
		foreach ($this->Data as $keyData => $valData) {
			$valData = addslashes($valData);
			$valData = clsEncryptHelper::encrypt($valData,BASE_URL);
			$flds[] = "$keyData='$valData'";
		}
		$fields = implode(", ",$flds);
		$sql = $this->validateEmpPasswd($id) ? "UPDATE app_ps_passwd SET $fields WHERE emp_id=$id" : "INSERT INTO app_ps_passwd SET $fields, emp_id=$id";
		$this->conn->Execute($sql);
		$_SESSION['eMsg']="Successfully Updated.";
	}

	/**
	 * Delete Record
	 * @param string $id_
	 */
	function doDelete($id_ = ""){
		$sql = "DELETE FROM /*app_modules*/ WHERE mnu_id=?";
		$this->conn->Execute($sql,array($id_));
		$_SESSION['eMsg']="Successfully Deleted.";
	}

	/**
	 * Get all the Table Listings
	 * @return array
	 */
	function getTableList(){
		// Process the query string and exclude querystring named "p"
		if (!empty($_SERVER['QUERY_STRING'])) {
			$qrystr = explode("&",$_SERVER['QUERY_STRING']);
			foreach ($qrystr as $value) {
				$qstr = explode("=",$value);
				if ($qstr[0]!="p") {
					$arrQryStr[] = implode("=",$qstr);
				}
			}
			$aQryStr = $arrQryStr;
			$aQryStr[] = "p=@@";
			$queryStr = implode("&",$aQryStr);
		}

		//bby: search module
		$qry = array();
		if (isset($_REQUEST['search_field'])) {
			// lets check if the search field has a value
			if (strlen($_REQUEST['search_field'])>0) {
				// lets assign the request value in a variable
				$search_field = $_REQUEST['search_field'];
				// create a custom criteria in an array
				$qry[] = "CONCAT(pi_lname,', ',pi_fname,' ',pi_mname) like '%$search_field%'";
			}
		}
		$qry[] = "a.emp_stat in (7)";
		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" WHERE ".implode(" AND ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		 "viewdata"=>"viewdata"
		,"fullname"=>"fullname"
		,"ps_passwd_password"=>"ps_passwd_password"
		);

		if(isset($_GET['sortby'])){
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		} else {
			$strOrderBy = "order by b.pi_lname";
		}

		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
//		$viewLink = "";
		$editLink = "<a href=\"?statpos=manageps_passwd&edit=',a.emp_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/edit.png\" title=\"Edit\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
//		$delLink = "<a href=\"?statpos=manageps_passwd&delete=',am.mnu_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\"  border=0 width=\"16\" height=\"16\"></a>";
//		$action = "<a href=\"?statpos=manageps_passwd&action=add\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/add.png\" title=\"Add New\" border=0 width=\"16\" height=\"16\"></a>";

		// SqlAll Query
		$sql = "SELECT CONCAT('$editLink') as viewdata,	CONCAT(pi_lname,', ',pi_fname,' ',pi_mname) as fullname,
				IF(ps_passwd_password IS NULL,'',REPEAT('&#x25cf;',20)) as ps_passwd_password
				FROM emp_masterfile a 
				LEFT JOIN emp_personal_info b ON (b.pi_id=a.pi_id)
				LEFT JOIN app_ps_passwd c ON (c.emp_id=a.emp_id)
						$criteria
						$strOrderBy";

		// Field and Table Header Mapping
		$arrFields = array(
		 "viewdata"=>$action
		,"fullname"=>"Employee Name"
		,"ps_passwd_password"=>"Password Set for Payslip"
		);

		// Column (table data) User Defined Attributes
		$arrAttribs = array(
		"mnu_ord"=>" align='center'",
		"viewdata"=>"width='50' align='center'"
		);

		// Process the Table List
		$tblDisplayList = new clsTableList($this->conn);
		$tblDisplayList->arrFields = $arrFields;
		$tblDisplayList->paginator->linkPage = "?$queryStr";
		$tblDisplayList->sqlAll = $sql;
		$tblDisplayList->sqlCount = $sqlcount;
		return $tblDisplayList->getTableList($arrAttribs);
	}
	
	function validateEmpPasswd($emp_id_ = ""){
		$sql = "select 1 from app_ps_passwd where emp_id='$emp_id_'";
		$rsResult= $this->conn->Execute($sql);
		if(!$rsResult->EOF){
			return true;
		} else {
			return false;
		}
	}
}
?>