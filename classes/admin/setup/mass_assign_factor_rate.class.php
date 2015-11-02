<?php
/**
 * Initial Declaration
 */

/**
 * Class Module
 *
 * @author  Grey M. Untiveros
 *
 */
class clsMassAssignFactorRate {

	var $conn;
	var $fieldMap;
	var $Data;

	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsMassAssignFactorRate object
	 */
	function clsMassAssignFactorRate($dbconn_ = NULL) {
		$this->conn =& $dbconn_;
		$this->fieldMap = array(
		 "wrate_id"=>"wrate_id"
		,"fr_name"=>"fr_name"
		,"fr_hrperday"=>"fr_hrperday"
		,"fr_hrperweek"=>"fr_hrperweek"
		,"fr_dayperweek"=>"fr_dayperweek"
		,"fr_dayperyear"=>"fr_dayperyear"
		);
	}

	/**
	 * Get the records from the database
	 *
	 * @param string $id_
	 * @return array
	 */
	function dbFetch($id_ = "") {
		$sql = "SELECT
					`factor_rate`.`fr_name`
					, `factor_rate`.`fr_hrperday`
					, `factor_rate`.`fr_hrperweek`
					, `factor_rate`.`fr_dayperweek`
					, `factor_rate`.`fr_dayperyear`
					, `app_wagerate`.`wrate_minwagerate`
				FROM
					`factor_rate`
					LEFT JOIN `app_wagerate` 
						ON (`factor_rate`.`wrate_id` = `app_wagerate`.`wrate_id`)
				WHERE fr_id=?";
				
		$rsResult = $this->conn->Execute($sql, array($id_));
		if (!$rsResult->EOF) {
			return $rsResult->fields;
		}
	}
	
	function getTotalEmployee() {
		// Get total number of records and pass it to the javascript function CheckAll
		$sql2 = "SELECT COUNT(`emp_masterfile`.`emp_idnum`) AS mycount
				FROM
					`emp_masterfile`
					INNER JOIN `payroll_comp` 
						ON (`emp_masterfile`.`emp_id` = `payroll_comp`.`emp_id`)
					INNER JOIN `salary_info` 
						ON (`emp_masterfile`.`emp_id` = `salary_info`.`emp_id`)
					WHERE
						payroll_comp.fr_id = '{$_GET['fr_id']}' AND salary_info.salaryinfo_isactive = '1' AND emp_masterfile.emp_stat IN ('1','7','10')";
		$rsResult2 = $this->conn->Execute($sql2);
		if (!$rsResult2->EOF) {
			return $rsResult2->fields['mycount'];
		}
	}
	
	/**
	 * Validation function
	 *
	 * @param array $pData_
	 * @return bool
	 */
	function doValidateData($pData_ = array(), $kind_of_validation) {
		$isValid = true;
		
		if (empty($pData_['chkAttend'])){
			$isValid = false;
			$_SESSION['eMsg'][] = "Please Select Employee/s to {$kind_of_validation}.";
		}
		
		return $isValid;
	}

	/**
	 * Get all the Table Listings
	 *
	 * @return array
	 */
	function getTableList() {
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
			if (strlen($_REQUEST['search_field']) > 0) {
				// lets assign the request value in a variable
				$search_field = $_REQUEST['search_field'];
				// create a custom criteria in an array
				$qry[] = "fr_name LIKE '%$search_field%'";
			}
		}

		// put all query array into one criteria string
		$criteria = (count($qry)> 0 )?" WHERE ".implode(" AND ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		 "viewdata"=>"viewdata"
		,"fr_name"=>"fr_name"
		,"fr_hrperday"=>"fr_hrperday"
		,"fr_hrperweek"=>"fr_hrperweek"
		,"fr_dayperweek"=>"fr_dayperweek"
		,"fr_dayperyear"=>"fr_dayperyear"
		,"wrate_minwagerate"=>"wrate_minwagerate"
		);

		if (isset($_GET['sortby'])) {
			$strOrderBy = " ORDER BY {$arrSortBy[$_GET['sortby']]} {$_GET['sortof']}";
		}

		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$viewAssignedEmployee = "<a href=\"?statpos=mass_assign_factor_rate&fr_id=',am.fr_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/useradd.png\" title=\"View Assigned Employee\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
		
		$objClsMngeDecimal = new Application();
		
		// SqlAll Query
		$sql = "SELECT am.*,
				FORMAT(b.wrate_minwagerate,{$objClsMngeDecimal->getGeneralDecimalSettings()}) AS wrate_minwagerate,
						FORMAT(am.fr_hrperday,{$objClsMngeDecimal->getGeneralDecimalSettings()}) AS fr_hrperday,
						FORMAT(am.fr_hrperweek,{$objClsMngeDecimal->getGeneralDecimalSettings()}) AS fr_hrperweek,
						FORMAT(am.fr_dayperweek,{$objClsMngeDecimal->getGeneralDecimalSettings()}) AS fr_dayperweek,
						FORMAT(am.fr_dayperyear,{$objClsMngeDecimal->getGeneralDecimalSettings()}) AS fr_dayperyear,
				CONCAT('$viewAssignedEmployee') AS viewdata
						FROM factor_rate am
						LEFT JOIN app_wagerate b ON (b.wrate_id=am.wrate_id)
						$criteria
						$strOrderBy";

		// Field and Table Header Mapping
		$arrFields = array(
		 "viewdata"=>"Action"
		,"fr_name"=>"Name"
		,"fr_hrperday"=>"Hour/s per Day"
		,"fr_hrperweek"=>"Hour/s per Week"
		,"fr_dayperweek"=>"Day/s per Week"
		,"fr_dayperyear"=>"Day/s per Year"
		,"wrate_minwagerate"=>"MWR"
		);

		// Column (table data) User Defined Attributes
		$arrAttribs = array(
		"mnu_ord"=>"align='center'",
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
	
	function getTableListEmployee() {
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
			if (strlen($_REQUEST['search_field']) > 0) {
				// lets assign the request value in a variable
				$search_field = $_REQUEST['search_field'];
				
				// create a custom criteria in an array
				$qry[] = "(pinfo.pi_fname LIKE '%$search_field%' OR pinfo.pi_lname LIKE '%$search_field%' OR dept.ud_name LIKE '%$search_field%')";
			}
		}
		
		$qry[] = "payroll_comp.fr_id = '{$_GET['fr_id']}'";
		$qry[] = "salary_info.salaryinfo_isactive = '1'";
		$qry[] = "emp_masterfile.emp_stat IN ('1','7','10')";
        
		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" WHERE ".implode(" AND ",$qry):"";
		
		// Sort field mapping
		$arrSortBy = array(
		"checkbox"=>"checkbox"
		,"emp_idnum"=>"emp_idnum"
		,"pi_lname"=>"pi_lname"
		,"pi_fname"=>"pi_fname"
		,"post_name"=>"post_name"
		,"comp_name"=>"comp_name"
		,"branchinfo_name"=>"branchinfo_name"
		,"ud_name"=>"ud_name"
		);

		if (isset($_GET['sortby'])) {
			$strOrderBy = " ORDER BY {$arrSortBy[$_GET['sortby']]} {$_GET['sortof']}";
		} else {
			$strOrderBy = " ORDER BY pi_lname";
		}
		
		// @note: This is used to count and check all the checkbox.
		// @note: SET t1 = 0
		$sql = "SET @t1:=0";
		$this->conn->Execute($sql);
		// Get total number of records and pass it to the javascript function CheckAll
		$sql2 = "SELECT COUNT(`emp_masterfile`.`emp_idnum`) AS mycount
				FROM
					`emp_masterfile`
					INNER JOIN `payroll_comp` 
						ON (`emp_masterfile`.`emp_id` = `payroll_comp`.`emp_id`)
					INNER JOIN `salary_info` 
						ON (`emp_masterfile`.`emp_id` = `salary_info`.`emp_id`)
					$criteria";
		$rsResult = $this->conn->Execute($sql2);
		if (!$rsResult->EOF) {
			$mycount = $rsResult->fields['mycount'];
		}
		
		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$chkAttend = "<input type=\"checkbox\" name=\"chkAttend[]\" id=\"chkAttend[',@t1:=@t1+1,']\" value=\"',`emp_masterfile`.`emp_id`,'\" onclick=\"javascript:UncheckAll({$mycount});\">";
		
		// SqlAll Query
		$sql = "SELECT
					`emp_masterfile`.`emp_idnum`
					, `emp_masterfile`.`emp_id`
					, `emp_personal_info`.`pi_lname`
					, `emp_personal_info`.`pi_fname`
					, `emp_position`.`post_name`
					, `company_info`.`comp_name`
					, `branch_info`.`branchinfo_name`
					, `app_userdept`.`ud_name`
					,  CONCAT('$chkAttend') AS chkbox
				FROM
					`emp_masterfile`
					INNER JOIN `payroll_comp` 
						ON (`emp_masterfile`.`emp_id` = `payroll_comp`.`emp_id`)
					INNER JOIN `emp_personal_info` 
						ON (`emp_personal_info`.`pi_id` = `emp_masterfile`.`pi_id`)
					INNER JOIN `emp_position` 
						ON (`emp_position`.`post_id` = `emp_masterfile`.`post_id`)
					INNER JOIN `company_info` 
						ON (`company_info`.`comp_id` = `emp_masterfile`.`comp_id`)
					LEFT JOIN `branch_info` 
						ON (`emp_masterfile`.`branchinfo_id` = `branch_info`.`branchinfo_id`)
					LEFT JOIN `app_userdept` 
						ON (`emp_masterfile`.`ud_id` = `app_userdept`.`ud_id`)
					INNER JOIN `salary_info` 
						ON (`emp_masterfile`.`emp_id` = `salary_info`.`emp_id`)
					$criteria
					$strOrderBy";

		// Field and Table Header Mapping
		$arrFields = array(
		"chkbox"=>"<input title=\"Select All\" type=\"checkbox\" name=\"chkAttendAll\" id=\"chkAttendAll\" onclick=\"javascript:CheckAll({$mycount});\" style=\"margin-left: 3px;\" />"
		//"chkbox"=>"Action"
		,"emp_idnum"=>"Emp No."
		,"pi_lname"=>"Last Name"
		,"pi_fname"=>"First Name"
		,"post_name"=>"Position"
		,"comp_name"=>"Company"
		,"branchinfo_name"=>"Location"
		,"ud_name"=>"Department"
		);
		
		// Column (table data) User Defined Attributes
		$arrAttribs = array(
		"chkbox"=>"width='30' align='center'"
		);
		
		// Process the Table List
		$tblDisplayList = new clsTableList($this->conn);
		$tblDisplayList->arrFields = $arrFields;
		$tblDisplayList->paginator->linkPage = "?$queryStr";
		$tblDisplayList->sqlAll = $sql;
		$tblDisplayList->sqlCount = $sqlcount;
		$tblDisplayList->tblBlock->templateFile = "table_no_fieldset.tpl.php";
		$tblDisplayList->tblBlock->assign("noSearchStart","<!--");
		$tblDisplayList->tblBlock->assign("noSearchEnd","-->");
		
		return $tblDisplayList->getTableList($arrAttribs);
	}
	
	/**
	 * Display Employee with no Factor Rate assigned
	 *
	 * @return array
	 */
	
	function getTableListEmployeeWithNoFactorRateAssigned() {
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
		
		$qry[] = "(isnull(payroll_comp.fr_id) OR payroll_comp.fr_id = 0)";
		$qry[] = "salary_info.salaryinfo_isactive = '1'";
		$qry[] = "emp_masterfile.emp_stat IN ('1','7','10')";
        
		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" WHERE ".implode(" AND ",$qry):"";
		
		// Sort field mapping
		$arrSortBy = array(
		 "checkbox"=>"checkbox"
		,"emp_idnum"=>"emp_idnum"
		,"pi_lname"=>"pi_lname"
		,"pi_fname"=>"pi_fname"
		,"post_name"=>"post_name"
		,"comp_name"=>"comp_name"
		,"branchinfo_name"=>"branchinfo_name"
		,"ud_name"=>"ud_name"
		);
		
		if (isset($_GET['sortby'])) {
			$strOrderBy = " ORDER BY {$arrSortBy[$_GET['sortby']]} {$_GET['sortof']}";
		} else {
			$strOrderBy = " ORDER BY ud_name, pi_lname";
		}
		
		// @note: This is used to count and check all the checkbox.
		// @note: SET t1 = 0
		$sql = "SET @t1:=0";
		$this->conn->Execute($sql);
		// Get total number of records and pass it to the javascript function CheckAll
		$sql2 = "SELECT COUNT(`emp_masterfile`.`emp_idnum`) AS mycount
				FROM
					`emp_masterfile`
					LEFT JOIN `payroll_comp` 
						ON (`emp_masterfile`.`emp_id` = `payroll_comp`.`emp_id`)
					INNER JOIN `salary_info` 
						ON (`emp_masterfile`.`emp_id` = `salary_info`.`emp_id`)
					$criteria";
		$rsResult = $this->conn->Execute($sql2);
		if (!$rsResult->EOF) {
			$mycount = $rsResult->fields['mycount'];
		}
		
		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$chkAttend = "<input type=\"checkbox\" name=\"chkAttend[]\" id=\"chkAttend[',@t1:=@t1+1,']\" value=\"',`emp_masterfile`.`emp_id`,'\" onclick=\"javascript:UncheckAll({$mycount});\">";
		
		// SqlAll Query
		$sql = "SELECT
					`emp_masterfile`.`emp_idnum`
					, `emp_masterfile`.`emp_id`
					, `emp_personal_info`.`pi_lname`
					, `emp_personal_info`.`pi_fname`
					, `emp_position`.`post_name`
					, `company_info`.`comp_name`
					, `branch_info`.`branchinfo_name`
					, `app_userdept`.`ud_name`
					,  CONCAT('$chkAttend') AS chkbox
				FROM
					`emp_masterfile`
					LEFT JOIN `payroll_comp` 
						ON (`emp_masterfile`.`emp_id` = `payroll_comp`.`emp_id`)
					INNER JOIN `emp_personal_info` 
						ON (`emp_personal_info`.`pi_id` = `emp_masterfile`.`pi_id`)
					INNER JOIN `emp_position` 
						ON (`emp_position`.`post_id` = `emp_masterfile`.`post_id`)
					INNER JOIN `company_info` 
						ON (`company_info`.`comp_id` = `emp_masterfile`.`comp_id`)
					LEFT JOIN `branch_info` 
						ON (`emp_masterfile`.`branchinfo_id` = `branch_info`.`branchinfo_id`)
					LEFT JOIN `app_userdept` 
						ON (`emp_masterfile`.`ud_id` = `app_userdept`.`ud_id`)
					INNER JOIN `salary_info` 
						ON (`salary_info`.`emp_id` = `emp_masterfile`.`emp_id`)
					$criteria
					$strOrderBy";

		// Field and Table Header Mapping
		$arrFields = array(
		 "chkbox"=>"<input title=\"Select All\" type=\"checkbox\" name=\"chkAttendAll\" id=\"chkAttendAll\" onclick=\"javascript:CheckAll({$mycount});\" style=\"margin-left: 3px;\" />"
		,"emp_idnum"=>"Emp No."
		,"pi_lname"=>"Last Name"
		,"pi_fname"=>"First Name"
		,"post_name"=>"Position"
		,"comp_name"=>"Company"
		,"branchinfo_name"=>"Location"
		,"ud_name"=>"Department"
		);

		// Column (table data) User Defined Attributes
		$arrAttribs = array(
		"chkbox"=>"width='30' align='center'"
		);

		// Process the Table List
		$tblDisplayList = new clsTableList($this->conn);
		$tblDisplayList->arrFields = $arrFields;
		$tblDisplayList->paginator->linkPage = "?$queryStr";
		$tblDisplayList->sqlAll = $sql;
		$tblDisplayList->sqlCount = $sqlcount;
		$tblDisplayList->tblBlock->templateFile = "table_no_fieldset.tpl.php";
		$tblDisplayList->tblBlock->assign("noSearchStart","<!--");
		$tblDisplayList->tblBlock->assign("noSearchEnd","-->");
		
		return $tblDisplayList->getTableList($arrAttribs);
	}
	
	function assignEmployee($pData) {
		$flds = array();
		$flds_ = array();
		$ctr = 0;
		
		do {
			$sqlpcal = "SELECT * FROM payroll_comp WHERE emp_id='{$pData['chkAttend'][$ctr]}'";
			$pps = $this->conn->Execute($sqlpcal);
			if (!$pps->EOF) {
				$flds_[] = "fr_id='{$_GET['fr_id']}'";
				$flds_[] = "pc_addwho='{$_SESSION['admin_session_obj']['user_data']['user_name']}'";
				$fields_ = implode(", ",$flds_);
				$sql_update = "UPDATE payroll_comp SET $fields_ WHERE emp_id='{$pps->fields['emp_id']}'";
				$this->conn->Execute($sql_update);
			} else {
				$flds_[] = "emp_id='{$pData['chkAttend'][$ctr]}'";
				$flds_[] = "pc_addwho='{$_SESSION['admin_session_obj']['user_data']['user_name']}'";
				$flds_[] = "fr_id='{$_GET['fr_id']}'";
				$fields_ = implode(", ",$flds_);
				$sql_insert = "INSERT INTO payroll_comp SET $fields_";
				$this->conn->Execute($sql_insert);
			}
			$flds_ = "";
			$fields_ = "";
			$ctr++;
		} while($ctr < sizeof($pData['chkAttend']));
		
		$sql_factor_rate = "SELECT DISTINCT fr_name FROM `payroll_comp` INNER JOIN `factor_rate` ON (`payroll_comp`.`fr_id` = `factor_rate`.`fr_id`) WHERE `factor_rate`.`fr_id` = '{$_GET['fr_id']}'";
		$sql_factor_rate_result = $this->conn->Execute($sql_factor_rate);
		
		$_SESSION['eMsg']="Successfully Assigned Employee/s to {$sql_factor_rate_result->fields[fr_name]}.";
	}
	
	function removeEmployee($pData) {
		$flds = array();
		$flds_ = array();
		$ctr = 0;
		
		$sql_factor_rate = "SELECT DISTINCT `factor_rate`.`fr_name` FROM `payroll_comp` INNER JOIN `factor_rate` ON (`payroll_comp`.`fr_id` = `factor_rate`.`fr_id`) WHERE `factor_rate`.`fr_id` = '{$_GET['fr_id']}'";
		$sql_factor_rate_result = $this->conn->Execute($sql_factor_rate);
		
		do {
			$sqlpcal = "SELECT * FROM payroll_comp WHERE emp_id='{$pData['chkAttend'][$ctr]}'";
			$pps = $this->conn->Execute($sqlpcal);
			if (!$pps->EOF) {
				$flds_[] = "fr_id = 0";
				$flds_[] = "pc_addwho='{$_SESSION['admin_session_obj']['user_data']['user_name']}'";
				$fields_ = implode(", ",$flds_);
				$sql_update = "UPDATE payroll_comp SET $fields_ WHERE emp_id='{$pps->fields['emp_id']}'";
				$this->conn->Execute($sql_update);
			} else {
				$flds_[] = "emp_id='{$pData['chkAttend'][$ctr]}'";
				$flds_[] = "pc_addwho='{$_SESSION['admin_session_obj']['user_data']['user_name']}'";
				$flds_[] = "fr_id = 0";
				$fields_ = implode(", ",$flds_);
				$sql_insert = "INSERT INTO payroll_comp SET $fields_";
				$this->conn->Execute($sql_insert);
			}
			$flds_ = "";
			$fields_ = "";
			$ctr++;
		} while($ctr < sizeof($pData['chkAttend']));
		
		$_SESSION['eMsg']="Successfully Removed Employee/s to {$sql_factor_rate_result->fields[fr_name]}.";
	}
	
	function checkEmployeeWithNoFactorRate() {
		$isValid = true;
		
		// Count Employee with no Factor Rate
		$sql = "SELECT COUNT(`emp_masterfile`.`emp_idnum`) AS number_of_employee_with_no_factor_rate
					FROM
						`emp_masterfile`
					LEFT JOIN `payroll_comp` 
						ON (`emp_masterfile`.`emp_id` = `payroll_comp`.`emp_id`)
					INNER JOIN `salary_info` 
						ON (`emp_masterfile`.`emp_id` = `salary_info`.`emp_id`)
					WHERE
						(isnull(payroll_comp.fr_id) OR payroll_comp.fr_id = 0) AND salary_info.salaryinfo_isactive = '1' AND emp_masterfile.emp_stat IN ('1', '7', '10')";
		$sql_result = $this->conn->Execute($sql);
		
		if ($sql_result->fields[number_of_employee_with_no_factor_rate] == 0) {
			$isValid = false;
			$_SESSION['eMsg'][] = "All Employee is assigned to a Factor Rate.";
		}
		
		return $isValid;
	}
}
?>