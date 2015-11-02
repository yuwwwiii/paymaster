<?php
/**
 * Initial Declaration
 */


/**
 * Class Module
 *
 * @author  Jason I. Mabignay
 *
 */
class cls201File_Review{

	var $conn;
	var $fieldMap;
	var $Data;

	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return cls201File_Review object
	 */
	function cls201File_Review($dbconn_ = null){
		$this->conn =& $dbconn_;
		$this->fieldMap = array(
		 "mnu_name" => "mnu_name"
		,"mnu_desc" => "mnu_desc"
		,"mnu_parent" => "mnu_parent"
		,"mnu_icon" => "mnu_icon"
		,"mnu_ord" => "mnu_ord"
		,"mnu_status" => "mnu_status"
		,"mnu_link_info" => "mnu_link_info"
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
			$valData = addslashes($valData);
			$flds[] = "$keyData='$valData'";
		}
		$fields = implode(", ",$flds);
		$sql = "insert into /*app_modules*/ set $fields";
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
			$flds[] = "$keyData='$valData'";
		}
		$fields = implode(", ",$flds);
		$sql = "update /*app_modules*/ set $fields where mnu_id=$id";
		$this->conn->Execute($sql);
		$_SESSION['eMsg']="Successfully Updated.";
	}

	/**
	 * Delete Record
	 *
	 * @param string $id_
	 */
	function doDelete($id_ = ""){
		$sql = "delete from /*app_modules*/ where mnu_id=?";
		$this->conn->Execute($sql,array($id_));
		$_SESSION['eMsg']="Successfully Deleted.";
	}

	/**
	 * Get all the Table Listings
	 *
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
				$qry[] = "(c.pi_fname like '%$search_field%' || c.pi_lname like '%$search_field%' || am.emp_idnum like '%$search_field%')";
			}
		}
		$listcomp = $_SESSION[admin_session_obj][user_comp_list2];
		$listloc = $_SESSION[admin_session_obj][user_branch_list2];
		$listpgroup = $_SESSION[admin_session_obj][user_paygroup_list2];
		IF(count($listcomp)>0){
			$qry[] = "am.comp_id in (".$listcomp.")";//company that can access
		}
		IF(count($listloc)>0){
			$qry[] = "am.branchinfo_id in (".$listloc.")";//location that can access
		}
		IF(count($listpgroup)>0){
			$qry[] = "ppuser.pps_id in (".$listpgroup.")";//pay group that can access
		}
		$emp201status_id = $_GET['emp201status_id'];
		// put all query array into one criteria string
		if($emp201status_id!=""){
			$qry[] = "am.emp_stat = '".$emp201status_id."'";
		}
//		$qry[] = "a.emp_status = '2'";
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";
		// Sort field mapping
		$arrSortBy = array(
		 "viewdata"=>"viewdata"
		,"emp_idnum"=>"am.emp_idnum"
		,"pi_lname"=>"c.pi_lname"
		,"pi_fname"=>"c.pi_fname"
		,"pi_mname"=>"c.pi_mname"
		,"post_name"=>"post_name"
		,"comp_name"=>"comp_name"
		,"branchinfo_name"=>"branchinfo_name"
		,"ud_name"=>"ud_name"
		);
		if(isset($_GET['sortby'])){
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}else{
			$strOrderBy = " order by comp.comp_name,c.pi_lname";
		}
		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$viewLink = "<a href=\"?statpos=emp_masterfile&empinfo=',am.emp_id,'&201filereview=yes#tab-1\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."sicon_.png\" title=\"View\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
//		$editLink = "<a href=\"?statpos=201file_review&edit=',a.emp_id,'&201filereview=yes\"><img src=\"".SYSCONFIG_THEME_URLPATH.SYSCONFIG_THEME."/images/admin/edit.gif\" title=\"re-employment\" hspace=\"2px\" border=0></a>";
//		$delLink = "<a href=\"?statpos=201file_review&delete=',a.emp_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_THEME_URLPATH.SYSCONFIG_THEME."/images/admin/trash.gif\" title=\"Delete\" hspace=\"2px\"  border=0></a>";

		// SqlAll Query
		$sql = "SELECT am.*, c.pi_lname, comp.comp_name, c.pi_fname, CONCAT(UPPER(SUBSTRING(c.pi_mname,1,1)),'.') as pi_mname, post.post_name, c.pi_emailone,
				CONCAT('$viewLink','$empinfoLink','$delLink') as viewdata, dept.ud_name, bran.branchinfo_name
						FROM emp_masterfile am
						JOIN emp_personal_info c on (c.pi_id=am.pi_id)
						JOIN payroll_pps_user ppuser on (ppuser.emp_id=am.emp_id)
						LEFT JOIN emp_position post on (post.post_id=am.post_id)
						LEFT JOIN company_info comp on (comp.comp_id=am.comp_id)
						LEFT JOIN app_userdept dept on (dept.ud_id=am.ud_id)
						LEFT JOIN branch_info bran on (bran.branchinfo_id=am.branchinfo_id)
						$criteria
						$strOrderBy";
		// Field and Table Header Mapping
		$arrFields = array(
		 "viewdata"=>"Action"
		,"emp_idnum"=>"Emp No"
		,"pi_lname"=>"Last Name"
		,"pi_fname"=>"First Name"
		,"pi_mname"=>"MI"
		,"post_name"=>"Position"
		,"comp_name"=>"Company"
		,"branchinfo_name"=>"Location"
		,"ud_name"=>"Department"
		);
		// Column (table data) User Defined Attributes
		$arrAttribs = array(
		 "viewdata"=>"width='40' align='center'"
		);
		// Process the Table List
		$tblDisplayList = new clsTableList($this->conn);
		$tblDisplayList->arrFields = $arrFields;
		$tblDisplayList->paginator->linkPage = "?$queryStr";
		$tblDisplayList->sqlAll = $sql;
		$tblDisplayList->sqlCount = $sqlcount;
		return $tblDisplayList->getTableList($arrAttribs);
	}
	
	function emp201status(){
		$sql = "SELECT * FROM emp_201status order by emp201status_ord";
		$rsResult = $this->conn->Execute($sql);
		$arrData = array();
		while(!$rsResult->EOF){
			$arrData[] = $rsResult->fields;
			$rsResult->MoveNext();
		}
		return $arrData;
	}
}
?>