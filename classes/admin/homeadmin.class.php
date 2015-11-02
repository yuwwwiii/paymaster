<?php
/**
 * Initial Declaration
 */


/**
 * Class Module
 *
 * @author  Arnold P. Orbista
 * 
 */
class clsHomeAdmin{
	
	var $conn;
	var $fieldMap;
	var $Data;
	
	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsHomeAdmin object
	 */
	function clsHomeAdmin($dbconn_ = null){
		$this->conn =& $dbconn_;
		$this->fieldMap = array(
		"mnu_name" => "mnu_name",
		"mnu_desc" => "mnu_desc",
		"mnu_parent" => "mnu_parent",
		"mnu_icon" => "mnu_icon",
		"mnu_ord" => "mnu_ord",
		"mnu_status" => "mnu_status",
		"mnu_link_info" => "mnu_link_info"
		);
	}
	
	/**
	 * Get the records from the database
	 *
	 * @param string $id_
	 * @return array
	 */
	function dbFetch($id_ = ""){
		$sql = "";
		$rsResult = $this->conn->Execute($sql,array($id_));
		if(!$rsResult->EOF){
			return $rsResult->fields;
		}
	}
	/**
	 * Populate array parameters to Data Variable
	 *
	 * @param array $pData_
	 * @return bool
	 */
	function doPopulateData($pData_ = array()){
		if(count($pData_)>0){
			foreach ($this->fieldMap as $key => $value) {
				$this->Data[$key] = $pData_[$value];
			}
			return true;
		}
		return false;
	}
	
	/**
	 * Validation function
	 *
	 * @param array $pData_
	 * @return bool
	 */
	function doValidateData($pData_ = array()){
		$isValid = true;
//		$isValid = false;
		return $isValid;
	}
	
	/**
	 * Save New
	 *
	 */
	function doSaveAdd(){
		$flds = array();
		foreach ($this->Data as $keyData => $valData) {
			$flds[] = "$keyData='$valData'";
		}
		$fields = implode(", ",$flds);
		$sql = "insert into app_modules set $fields";
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
			$flds[] = "$keyData='$valData'";
		}
		$fields = implode(", ",$flds);
		
		$sql = "update app_modules set $fields where mnu_id=$id";
		$this->conn->Execute($sql);
		$_SESSION['eMsg']="Successfully Updated.";
	}
	
	/**
	 * Delete Record
	 *
	 * @param string $id_
	 */
	function doDelete($id_ = ""){
		$sql = "delete from app_modules where mnu_id=?";
		$this->conn->Execute($sql,array($id_));
		$_SESSION['eMsg']="Successfully Deleted.";
	}
	
	/**
	 * Get all the Table Listings
	 *
	 * @return array
	 */
	function getTableList(){
		$viewLink = "";
		$editLink = "<a href=\"?statpos=&edit=',am.mnu_id,'\"><img src=\"".SYSCONFIG_THEME_URLPATH.SYSCONFIG_THEME."/images/admin/edit.gif\" title=\"Edit\" hspace=\"2px\" border=0></a>";
		$delLink = "<a href=\"?statpos=&delete=',am.mnu_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_THEME_URLPATH.SYSCONFIG_THEME."/images/admin/trash.gif\" title=\"Delete\" hspace=\"2px\"  border=0></a>";
		
		$sql = "select am.*, CONCAT('$viewLink','$editLink','$delLink') as viewdata from app_modules am order by mnu_ord";
		
		$sqlcount = "select count(*) as mycount from app_modules order by mnu_ord";
		
		$arrFields = array(
		"mnu_name"=>"Module Name",
		"mnu_link"=>"Link",
		"mnu_ord"=>"Order",
		"viewdata"=>"&nbsp;"
		);
		
		$arrAttribs = array(
		"mnu_ord"=>" align='right'",
		"viewdata"=>"width='50' align='center'"
		);
		
		$tblDisplayList = new clsTableList($this->conn);
		$tblDisplayList->arrFields = $arrFields;
		$tblDisplayList->paginator->linkPage = "?statpos=&p=@@";
		$tblDisplayList->sqlAll = $sql;
		$tblDisplayList->sqlCount = $sqlcount;
		
		return $tblDisplayList->getTableList($arrAttribs);
	}
	
	/**
	 * jmabignay (2008.10.21)
	 * used to count the 201 per company.
	 * @return $rsresult
	 */
	function Total201(){
		$listcomp =  $_SESSION[admin_session_obj][user_comp_list2];
		$listloc =  $_SESSION[admin_session_obj][user_branch_list2];
		$listpgroup =  $_SESSION[admin_session_obj][user_paygroup_list2];
		$join = "";
		IF(count($listcomp)>0){
			$qry[] = "am.comp_id in (".$listcomp.")";//company that can access
		}
		IF(count($listloc)>0){
			$qry[] = "am.branchinfo_id in (".$listloc.")";//location that can access
		}
		IF(count($listpgroup)>0){
			$qry[] = "ppuser.pps_id in (".$listpgroup.")";//pay group that can access
			$join .= "JOIN payroll_pps_user ppuser on (ppuser.emp_id=am.emp_id)";
		}
		$qry[] = "am.emp_stat in ('1','7')";
		$criteria = (count($qry)>0)?" WHERE ".implode(" AND ",$qry):"";
		$sql = "select count(*) as mycount, comp.comp_name
					from emp_masterfile am 
					JOIN company_info comp on (comp.comp_id=am.comp_id)
					$join
					$criteria
					GROUP BY am.comp_id";
		$rsresult = $this->conn->Execute($sql);
		$arrData = array();
		while (!$rsresult->EOF) {
				$arrData[] = $rsresult->fields;
				$rsresult->Movenext();
		}
		return $arrData;
//		if(!$rsresult->EOF){
//			return $rsresult->fields;
//		}
	}
	
	function sumEmpperLoc($comp_ = null){
		$sql = "SELECT count(*) as mycount, b.branchinfo_name
					FROM emp_masterfile am 
					JOIN branch_info b on (b.branchinfo_id=am.branchinfo_id)
					WHERE am.emp_stat in ('1','7','10')
					GROUP BY am.branchinfo_id";
		$rsresult = $this->conn->Execute($sql);
		$arrData = array();
		while (!$rsresult->EOF) {
				$arrData[] = $rsresult->fields;
				$rsresult->Movenext();
		}
		return $arrData;
	}
	
	function birthday_dependent() {
		$sql = "SELECT dependent_info.emp_id, pi_fname, pi_mname, pi_lname,
				COUNT((YEAR(CURDATE())-YEAR(depnd_bdate)) - (RIGHT(CURDATE(),5)<RIGHT(depnd_bdate,5))) AS dependent_number
				FROM emp_masterfile
				JOIN dependent_info ON (emp_masterfile.emp_id = dependent_info.emp_id)
				JOIN emp_personal_info ON (emp_personal_info.pi_id = emp_masterfile.pi_id)
				WHERE (YEAR(CURDATE())-YEAR(depnd_bdate)) - (RIGHT(CURDATE(),5)<RIGHT(depnd_bdate,5))> 21
				GROUP BY dependent_info.emp_id";
		$rsResult = $this->conn->Execute($sql);
		$arrData = array();
			while (!$rsResult->EOF) {
				$arrData[] = $rsResult->fields;
				$rsResult->Movenext();
			}
		return $arrData;
	}
	
	function getSupervisor() {
		$sql = "SELECT
					erep_sup_emp_number
				FROM
					".SYSCONFIG_ORANGEHRM_DB.".hs_hr_emp_reportto
				WHERE erep_sup_emp_number = {$_SESSION['empNumber']}";
		
		$rsResult = $this->conn->Execute($sql);
		
		if(!$rsResult->EOF){
			return $rsResult->fields;
		}
	}
}
?>