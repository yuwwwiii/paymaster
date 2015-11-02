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
class clsMassAssignDeductionType {
	
	var $conn;
	var $fieldMap;
	var $Data;
	
	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsMassAssignBankGroup object
	 */
	function clsMassAssignDeductionType($dbconn_ = NULL) {
		$this->conn =& $dbconn_;
	}
	
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
				$qry[] = "dec_name LIKE '%$search_field%' OR dec_code LIKE '%$search_field%'";
			}
		}

		// put all query array into one criteria string
		$criteria = (count($qry) > 0 )?" WHERE ".implode(" AND ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		"dec_code"=>"dec_code",
		"dec_name"=>"dec_name"
		);

		if (isset($_GET['sortby'])) {
			$strOrderBy = " ORDER BY {$arrSortBy[$_GET['sortby']]} {$_GET['sortof']}";
		}

		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$viewAssignedEmployee = "<a href=\"?statpos=mass_assign_deduction_type&dec_id=',`deduction_type`.`dec_id`,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/useradd.png\" title=\"View Assigned Employee\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";

		// SqlAll Query
		$sql = "SELECT	*,
						CONCAT('$viewAssignedEmployee') AS viewdata
				FROM
						`deduction_type`
				$criteria
				$strOrderBy";

		// Field and Table Header Mapping
		$arrFields = array(
		"viewdata"=>"Action"
		,"dec_code"=>"Deduction Code"
		,"dec_name"=>"Deduction Name"
		);

		// Column (table data) User Defined Attributes
		$arrAttribs = array(
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
	
	/**
	 * Get the records from the database
	 *
	 * @param string $id_
	 * @return array
	 */
	function dbFetch($id_ = "") {
		$sql = "SELECT
					*
				FROM
					`deduction_type`
				WHERE `deduction_type`.`dec_id` = ?";
		
		$rsResult = $this->conn->Execute($sql, array($id_));
		if (!$rsResult->EOF) {
			return $rsResult->fields;
		}
	}
	
	function getTableListAssignedEmployee() {
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
		
		$qry[] = "`deduction_type`.`dec_id` = '{$_GET['dec_id']}'";
		$qry[] = "emp_masterfile.emp_stat IN ('1', '7', '10')";
		
		// put all query array into one criteria string
		$criteria = (count($qry)> 0 )?" WHERE ".implode(" AND ",$qry):"";
		
		// Sort field mapping
		$arrSortBy = array(
		"checkbox"=>"checkbox"
		,"emp_idnum"=>"emp_idnum"
		,"pi_lname"=>"pi_lname"
		,"pi_fname"=>"pi_fname"
		,"post_name"=>"post_name"
		,"comp_name"=>"comp_name"
		,"branchinfo_name"=>"branchinfo_name"
		,"pay_period"=>"pay_period"
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
		// SqlAll Query
		$sql2 = "SELECT
						COUNT(`emp_masterfile`.`emp_idnum`) AS mycount
				FROM
					    `emp_masterfile`
					    INNER JOIN `period_benloanduc_sched` 
					        ON (`period_benloanduc_sched`.`emp_id` = `emp_masterfile`.`emp_id`)
					    INNER JOIN `deduction_type` 
					        ON (`deduction_type`.`dec_id` = `period_benloanduc_sched`.`empdd_id`)
				$criteria";
		$rsResult = $this->conn->Execute($sql2);
		if (!$rsResult->EOF) {
			$mycount = $rsResult->fields['mycount'];
		}
		
		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$chkAttend = "<input type=\"checkbox\" name=\"chkAttend[]\" id=\"chkAttend[',@t1:=@t1+1,']\" value=\"',`emp_masterfile`.`emp_id`,'\" onclick=\"javascript:UncheckAll({$mycount});\">";
		
		// SqlAll Query
		$sql = "SELECT	*,
						CONCAT('$chkAttend') AS chkbox,
						IF(empdd_id = '5',CASE empdd_id = '5' 
							WHEN bldsched_period  = '1' THEN 'Tax'
							WHEN bldsched_period  = '2' THEN 'None - MWE'
							WHEN bldsched_period  = '3' THEN 'None - Others'
					        ELSE bldsched_period END,bldsched_period) AS pay_period
				FROM
					    `emp_masterfile`
					    INNER JOIN `period_benloanduc_sched` 
					        ON (`period_benloanduc_sched`.`emp_id` = `emp_masterfile`.`emp_id`)
					    INNER JOIN `deduction_type` 
					        ON (`deduction_type`.`dec_id` = `period_benloanduc_sched`.`empdd_id`)
					    INNER JOIN `emp_personal_info` 
					        ON (`emp_personal_info`.`pi_id` = `emp_masterfile`.`pi_id`)
					    INNER JOIN `emp_position` 
					        ON (`emp_masterfile`.`post_id` = `emp_position`.`post_id`)
					    INNER JOIN `company_info` 
					        ON (`emp_masterfile`.`comp_id` = `company_info`.`comp_id`)
					    LEFT JOIN `branch_info` 
					        ON (`emp_masterfile`.`branchinfo_id` = `branch_info`.`branchinfo_id`)
				$criteria
				$strOrderBy";
		
		// Field and Table Header Mapping
		$arrFields = array(
		"chkbox"=>"<input title=\"Select All\" type=\"checkbox\" name=\"chkAttendAll\" id=\"chkAttendAll\" onclick=\"javascript:CheckAll({$mycount});\" style=\"margin-left: 3px;\" />"
		,"emp_idnum"=>"Emp. No."
		,"pi_lname"=>"Last Name"
		,"pi_fname"=>"First Name"
		,"post_name"=>"Position"
		,"comp_name"=>"Company"
		,"branchinfo_name"=>"Location"
		,"pay_period"=>"Pay Period"
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
	 * 
	 * Count Employee with no Deduction Type
	 */
	function checkEmployeeWithNoDeductionType() {
		$isValid = true;
		
		$sql = "SELECT
						COUNT(`emp_masterfile`.`emp_id`) AS number_of_employee__with_no_deduction_type
				FROM
						`emp_masterfile`
						INNER JOIN `emp_personal_info` ON (`emp_personal_info`.`pi_id` = `emp_masterfile`.`pi_id`)
						INNER JOIN `emp_position` ON (`emp_masterfile`.`post_id` = `emp_position`.`post_id`)
						INNER JOIN `company_info` ON (`emp_masterfile`.`comp_id` = `company_info`.`comp_id`)
				WHERE
						`emp_masterfile`.`emp_id`
						NOT IN
								(SELECT `emp_masterfile`.`emp_id`
								FROM `emp_masterfile`
								INNER JOIN `period_benloanduc_sched` ON (`emp_masterfile`.`emp_id` = `period_benloanduc_sched`.`emp_id`)
								WHERE `period_benloanduc_sched`.`empdd_id` = '{$_GET['dec_id']}')
						AND emp_masterfile.emp_stat IN ('1', '7', '10')";
		
		$sql_result = $this->conn->Execute($sql);
		
		if ($sql_result->fields[number_of_employee__with_no_deduction_type] == 0) {
			$isValid = false;
			$_SESSION['eMsg'][] = "All Employee is assigned to a Deduction Type.";
		}
		
		return $isValid;
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

		$qry[] = "fr_id = '{$_GET['fr_id']}'";
        
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
					INNER JOIN `payroll_comp` 
						ON (`emp_masterfile`.`emp_id` = `payroll_comp`.`emp_id`)
					INNER JOIN `emp_personal_info` 
						ON (`emp_personal_info`.`pi_id` = `emp_masterfile`.`pi_id`)
					INNER JOIN `emp_position` 
						ON (`emp_position`.`post_id` = `emp_masterfile`.`post_id`)
					INNER JOIN `company_info` 
						ON (`company_info`.`comp_id` = `emp_masterfile`.`comp_id`)
					INNER JOIN `app_userdept` 
						ON (`emp_masterfile`.`ud_id` = `app_userdept`.`ud_id`)
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
					INNER JOIN `app_userdept` 
						ON (`emp_masterfile`.`ud_id` = `app_userdept`.`ud_id`)
					$criteria
					$strOrderBy";

		// Field and Table Header Mapping
		$arrFields = array(
		"chkbox"=>"<input title=\"Select All\" type=\"checkbox\" name=\"chkAttendAll\" id=\"chkAttendAll\" onclick=\"javascript:CheckAll({$mycount});\" style=\"margin-left: 9px;\" />"
		,"emp_idnum"=>"Emp No."
		,"pi_lname"=>"Last Name"
		,"pi_fname"=>"First Name"
		,"post_name"=>"Position"
		,"comp_name"=>"Company"
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
	 * Display Employee with no Deduction Type assigned
	 *
	 * @return array
	 */
	function getTableListEmployeeWithNoDeductionTypeAssigned() {
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
		
		$qry[] = "`emp_masterfile`.`emp_id`
					NOT IN
							(SELECT `emp_masterfile`.`emp_id`
							FROM `emp_masterfile`
							INNER JOIN `period_benloanduc_sched` ON (`emp_masterfile`.`emp_id` = `period_benloanduc_sched`.`emp_id`)
							WHERE `period_benloanduc_sched`.`empdd_id` = '{$_GET['dec_id']}')";
		$qry[] = "emp_masterfile.emp_stat IN ('1', '7', '10')";
        
		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" WHERE ".implode(" AND ",$qry):"";
		
		// Sort field mapping
		$arrSortBy = array(
		"emp_idnum"=>"emp_idnum"
		,"pi_lname"=>"pi_lname"
		,"pi_fname"=>"pi_fname"
		,"post_name"=>"post_name"
		,"comp_name"=>"comp_name"
		,"branchinfo_name"=>"branchinfo_name"
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
		$sql2 = "SELECT
						COUNT(`emp_masterfile`.`emp_id`) AS mycount
				FROM
						`emp_masterfile`
				$criteria";
				
		$rsResult = $this->conn->Execute($sql2);
		if (!$rsResult->EOF) {
			$mycount = $rsResult->fields['mycount'];
		}

		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$chkAttend = "<input type=\"checkbox\" name=\"chkAttend[]\" id=\"chkAttend[',@t1:=@t1+1,']\" value=\"',`emp_masterfile`.`emp_id`,'\" onclick=\"javascript:UncheckAll({$mycount});\">";
		
		// SqlAll Query
		$sql = "SELECT
						`emp_masterfile`.`emp_id`,
						`emp_masterfile`.`emp_idnum`,
						`emp_personal_info`.`pi_lname`,
						`emp_personal_info`.`pi_fname`,
						`emp_position`.`post_name`,
						`company_info`.`comp_name`,
						`branch_info`.`branchinfo_name`,
						CONCAT('$chkAttend') AS chkbox
				FROM
						`emp_masterfile`
				INNER JOIN `emp_personal_info` ON (`emp_personal_info`.`pi_id` = `emp_masterfile`.`pi_id`)
				INNER JOIN `emp_position` ON (`emp_masterfile`.`post_id` = `emp_position`.`post_id`)
				INNER JOIN `company_info` ON (`emp_masterfile`.`comp_id` = `company_info`.`comp_id`)
				LEFT JOIN `branch_info` ON (`emp_masterfile`.`branchinfo_id` = `branch_info`.`branchinfo_id`)
				$criteria
				$strOrderBy";
				
		// Field and Table Header Mapping
		$arrFields = array(
		"chkbox"=>"<input title=\"Select All\" type=\"checkbox\" name=\"chkAttendAll\" id=\"chkAttendAll\" onclick=\"javascript:CheckAll({$mycount});\" style=\"margin-left: 3px;\" />"
		,"emp_idnum"=>"Emp. No."
		,"pi_lname"=>"Last Name"
		,"pi_fname"=>"First Name"
		,"post_name"=>"Position"
		,"comp_name"=>"Company"
		,"branchinfo_name"=>"Location"
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
		//printa($pData);exit;
		$flds = array();
		$ctr = 0;
		
		do {
			$flds[] = "emp_id='{$pData['chkAttend'][$ctr]}'";
			$flds[] = "empdd_id='{$_GET['dec_id']}'";
			if ($_GET['dec_id'] == 5) {
				$flds[]="bldsched_period='{$pData['tax']}'";
			} else {
				if ( ($_GET['pay_period'] > 0) && ($_GET['pay_period'] < 6) ) {
					$flds[]="bldsched_period='{$_GET['pay_period']}'";
				} else {
					$_SESSION['eMsg']="Invalid Pay Period!";
					return;
				}
			}
			$fields = implode(", ",$flds);
			$sql_insert = "INSERT INTO `period_benloanduc_sched` SET $fields";
			$this->conn->Execute($sql_insert);
			
			$flds = "";
			$fields = "";
			$ctr++;
		} while($ctr < sizeof($pData['chkAttend']));
		
		$sql_deduction_type = "SELECT dec_code FROM `deduction_type` WHERE `deduction_type`.`dec_id` = '{$_GET['dec_id']}'";
		$sql_deduction_type_result = $this->conn->Execute($sql_deduction_type);
		
		switch ($_GET['pay_period']) {
			case 1: $pay_period = ', 1st Pay Period'; break;
			case 2: $pay_period = ', 2nd Pay Period'; break;
			case 3: $pay_period = ', 3rd Pay Period'; break;
			case 4: $pay_period = ', 4th Pay Period'; break;
			case 5: $pay_period = ', 5th Pay Period'; break;
			default: $pay_period = ''; break;
		}
		
		$_SESSION['eMsg']="Successfully Assigned Employee/s to {$sql_deduction_type_result->fields[dec_code]}{$pay_period}.";
	}
	
	function removeEmployee($pData) {
		//printa($pData);exit;
		$flds = array();
		$flds_ = array();
		$ctr = 0;
		
		$sql_deduction_type = "SELECT dec_code FROM `deduction_type` WHERE `deduction_type`.`dec_id` = '{$_GET['dec_id']}'";
		$sql_deduction_type_result = $this->conn->Execute($sql_deduction_type);
		
		do {
			$sql_remove = "DELETE FROM period_benloanduc_sched WHERE empdd_id = '{$_GET['dec_id']}' AND emp_id='{$pData['chkAttend'][$ctr]}'";
			$this->conn->Execute($sql_remove);
			
			$ctr++;
		} while($ctr < sizeof($pData['chkAttend']));
		
		$_SESSION['eMsg']="Successfully Removed Employee/s to {$sql_deduction_type_result->fields[dec_code]}.";
	}
	
	function getTableListEmployeeWithThisPayPeriod() {
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
		
		$qry[] = "`emp_masterfile`.`emp_id`
					NOT IN
							(SELECT `period_benloanduc_sched`.`emp_id`
							FROM `emp_masterfile`
							INNER JOIN `period_benloanduc_sched` ON (`emp_masterfile`.`emp_id` = `period_benloanduc_sched`.`emp_id`)
							INNER JOIN `payroll_pps_user` ON (`emp_masterfile`.`emp_id` = `payroll_pps_user`.`emp_id`) 
							INNER JOIN `payroll_pay_period_sched` ON (`payroll_pps_user`.`pps_id` = `payroll_pay_period_sched`.`pps_id`)
							WHERE `period_benloanduc_sched`.`empdd_id` = '{$_GET['dec_id']}' AND `period_benloanduc_sched`.`bldsched_period` = '{$_GET['pay_period']}')";
		$qry[] = "emp_masterfile.emp_stat IN ('1', '7', '10')";
		
		if ($_GET['pay_period'] == 1) {
			$qry[] = "(salaryclass_id = 2 OR salaryclass_id = 1 OR salaryclass_id = 3 OR salaryclass_id = 4 OR salaryclass_id = 5 OR salaryclass_id = 6)";
		} elseif ($_GET['pay_period'] == 2) {
			$qry[] = "(salaryclass_id = 2 OR salaryclass_id = 3 OR salaryclass_id = 4)";
		} elseif ($_GET['pay_period'] == 3 || $_GET['pay_period'] == 4 || $_GET['pay_period'] == 5) {
			$qry[] = "(salaryclass_id = 2)";
		}
		
		$qry[] = "`emp_masterfile`.`emp_id` NOT IN
		(SELECT 
		  `period_benloanduc_sched`.`emp_id`
		FROM
		  `deduction_type` 
		  INNER JOIN `period_benloanduc_sched` 
		    ON (
		      `deduction_type`.`dec_id` = `period_benloanduc_sched`.`empdd_id`
		    ) 
		  INNER JOIN `emp_masterfile` 
		    ON (
		      `emp_masterfile`.`emp_id` = `period_benloanduc_sched`.`emp_id`
		    ) 
		  INNER JOIN `emp_personal_info` 
		    ON (
		      `emp_personal_info`.`pi_id` = `emp_masterfile`.`pi_id`
		    ) 
		  INNER JOIN `emp_position` 
		    ON (
		      `emp_masterfile`.`post_id` = `emp_position`.`post_id`
		    ) 
		  INNER JOIN `company_info` 
		    ON (
		      `emp_masterfile`.`comp_id` = `company_info`.`comp_id`
		    ) 
		WHERE `deduction_type`.`dec_id` = '{$_GET['dec_id']}' 
		AND `period_benloanduc_sched`.`bldsched_period` = '{$_GET['pay_period']}')";
		
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
		$sql2 = "SELECT
						COUNT(`emp_masterfile`.`emp_id`) AS mycount
				FROM
						`emp_masterfile`
				INNER JOIN `emp_personal_info` ON (`emp_personal_info`.`pi_id` = `emp_masterfile`.`pi_id`)
				INNER JOIN `emp_position` ON (`emp_masterfile`.`post_id` = `emp_position`.`post_id`)
				INNER JOIN `company_info` ON (`emp_masterfile`.`comp_id` = `company_info`.`comp_id`)
			    INNER JOIN `payroll_pps_user` ON (`emp_masterfile`.`emp_id` = `payroll_pps_user`.`emp_id`)
			    INNER JOIN `payroll_pay_period_sched` ON (`payroll_pps_user`.`pps_id` = `payroll_pay_period_sched`.`pps_id`)
				$criteria";
				
		$rsResult = $this->conn->Execute($sql2);
		if (!$rsResult->EOF) {
			$mycount = $rsResult->fields['mycount'];
		}

		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$chkAttend = "<input type=\"checkbox\" name=\"chkAttend[]\" id=\"chkAttend[',@t1:=@t1+1,']\" value=\"',`emp_masterfile`.`emp_id`,'\" onclick=\"javascript:UncheckAll({$mycount});\">";
		
		// SqlAll Query
		$sql = "SELECT
						`emp_masterfile`.`emp_id`,
						`emp_masterfile`.`emp_idnum`,
						`emp_personal_info`.`pi_lname`,
						`emp_personal_info`.`pi_fname`,
						`emp_position`.`post_name`,
						`company_info`.`comp_name`,
						`branch_info`.`branchinfo_name`,
						CONCAT('$chkAttend') AS chkbox,
						`payroll_pay_period_sched`.`salaryclass_id`
				FROM
						`emp_masterfile`
				INNER JOIN `emp_personal_info` ON (`emp_personal_info`.`pi_id` = `emp_masterfile`.`pi_id`)
				INNER JOIN `emp_position` ON (`emp_masterfile`.`post_id` = `emp_position`.`post_id`)
				INNER JOIN `company_info` ON (`emp_masterfile`.`comp_id` = `company_info`.`comp_id`)
				LEFT JOIN `branch_info` ON (`emp_masterfile`.`branchinfo_id` = `branch_info`.`branchinfo_id`)
			    INNER JOIN `payroll_pps_user` ON (`emp_masterfile`.`emp_id` = `payroll_pps_user`.`emp_id`)
			    INNER JOIN `payroll_pay_period_sched` ON (`payroll_pps_user`.`pps_id` = `payroll_pay_period_sched`.`pps_id`)
				$criteria
				$strOrderBy";
				
		// Field and Table Header Mapping
		$arrFields = array(
		"chkbox"=>"<input title=\"Select All\" type=\"checkbox\" name=\"chkAttendAll\" id=\"chkAttendAll\" onclick=\"javascript:CheckAll({$mycount});\" style=\"margin-left: 3px;\" />"
		,"emp_idnum"=>"Emp. No."
		,"pi_lname"=>"Last Name"
		,"pi_fname"=>"First Name"
		,"post_name"=>"Position"
		,"comp_name"=>"Company"
		,"branchinfo_name"=>"Location"
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
	 * Populate array parameters to Data Variable
	 *
	 * @param array $pData_
	 * @param boolean $isForm_
	 * @return bool
	 */
	function doPopulateData($pData_ = array(),$isForm_ = false) {
		if (count($pData_) > 0) {
			foreach ($this->fieldMap as $key => $value) {
				if ($isForm_) {
					$this->Data[$value] = $pData_[$value];
				} else {
					$this->Data[$key] = $pData_[$value];
				}
			}
			return true;
		}
		return false;
	}
}
?>