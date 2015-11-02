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
class clsMassAssignBankGroup {
	
	var $conn;
	var $fieldMap;
	var $Data;
	
	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsMassAssignBankGroup object
	 */
	function clsMassAssignBankGroup($dbconn_ = null) {
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
				$qry[] = "comp_name LIKE '%$search_field%'";
			}
		}

		// put all query array into one criteria string
		$criteria = (count($qry) > 0 )?" WHERE ".implode(" and ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		"comp_code"=>"comp_code"
		,"comp_name"=>"comp_name"
		,"comp_add"=>"comp_add"
		,"comp_tel"=>"comp_tel"
		);

		if (isset($_GET['sortby'])) {
			$strOrderBy = " ORDER BY ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}

		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$viewBanks = "<a href=\"?statpos=mass_assign_bank_group&comp_id=',comp_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/search.png\" title=\"View Bank Account/s\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";

		// SqlAll Query
		$sql = "SELECT	*,
						CONCAT('$viewBanks') AS viewdata
				FROM
						`company_info`
				$criteria
				$strOrderBy";

		// Field and Table Header Mapping
		$arrFields = array(
		"viewdata"=>"Action"
		,"comp_code"=>"Company Code"
		,"comp_name"=>"Company Name"
		,"comp_add"=>"Address"
		,"comp_tel"=>"Telephone No."
		);

		// Column (table data) User Defined Attributes
		$arrAttribs = array(
		"viewdata" => "width='50' align='center'"
		);

		// Process the Table List
		$tblDisplayList = new clsTableList($this->conn);
		$tblDisplayList->arrFields = $arrFields;
		$tblDisplayList->paginator->linkPage = "?$queryStr";
		$tblDisplayList->sqlAll = $sql;
		$tblDisplayList->sqlCount = $sqlcount;
		
		return $tblDisplayList->getTableList($arrAttribs);
	}
	
	function getTableListBank($gData = array()) {
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
				$qry[] = "banklist_name LIKE '%$search_field%'";

			}
		}
		
		IF(isset($gData['local'])){
			$qry[] = "a.comp_id = '".$gData['comp_id']."'";
			$qry[] = "a.branchinfo_id = '".$gData['local']."'";
		}ELSE{
			$qry[] = "a.comp_id = '".$gData['comp_id']."'";
			$qry[] = "isNULL(a.branchinfo_id)";
		}
		
		// put all query array into one criteria string
		$criteria = (count($qry)> 0 )?" WHERE ".implode(" and ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		"banklist_name"=>"banklist_name"
		,"bank_acct_no"=>"bank_acct_no"
		,"bank_acct_name"=>"bank_acct_name"
		,"bank_branch"=>"bank_branch"
		,"bank_isactive" => "bank_isactive"
		);

		if (isset($_GET['sortby'])) {
			$strOrderBy = " ORDER BY ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}

		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$viewAssignedEmployee = "<a href=\"?statpos=mass_assign_bank_group&comp_id=',a.comp_id,'&bank_id=',a.bank_id,'&banklist_id=',a.banklist_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/useradd.png\" title=\"View Assigned Employee\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
		
		// SqlAll Query
		$sql = "SELECT	* ,IF(bank_isactive = '1','Active','Inactive') as bank_isactive, CONCAT('$viewAssignedEmployee') AS viewdata
				FROM bank_info a
				JOIN bank_list b ON (b.banklist_id = a.banklist_id)
					$criteria
					$strOrderBy";

		// Field and Table Header Mapping
		$arrFields = array(
		"viewdata"=>"Action"
		,"banklist_name"=>"Bank Name"
		,"bank_acct_no"=>"Account No."
		,"bank_acct_name"=>"Account Name"
		,"bank_branch"=>"Branch"
		,"bank_isactive"=>"Status"
		);

		// Column (table data) User Defined Attributes
		$arrAttribs = array(
		"viewdata"=>"width='30' align='center'"
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
	//TODO: Ayusin ito mamaya
	function dbFetch($id_ = "") {
		$sql = "SELECT
					*
					,IF(bank_isactive = '1','Active','Inactive') as bank_isactive
				FROM
					`bank_list`
					LEFT JOIN `bank_info` 
        				ON (`bank_list`.`banklist_id` = `bank_info`.`banklist_id`)
				WHERE `bank_list`.`banklist_id` = ?";
				
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

		//bby: search module
		$qry = array();
		if (isset($_REQUEST['search_field'])) {
			// lets check if the search field has a value
			if (strlen($_REQUEST['search_field']) > 0) {
				// lets assign the request value in a variable
				$search_field = $_REQUEST['search_field'];

				// create a custom criteria in an array
				$qry[] = "bankiemp_acct_name LIKE '%$search_field%'";
			}
		}
		
		$qry[] = "`bank_infoemp`.`banklist_id` = '".$_GET['banklist_id']."'";
		$qry[] = "`bank_infoemp`.`bankiemp_id` NOT IN (SELECT `bank_empgroup`.`bankiemp_id` FROM `bank_empgroup` WHERE `bank_empgroup`.`bank_id`='".$_GET['bank_id']."')";
		$qry[] = "`emp_masterfile`.`emp_stat` IN ('1', '7', '10')";
		
		// put all query array into one criteria string
		$criteria = (count($qry)> 0 )?" WHERE ".implode(" AND ",$qry):"";
		
		// Sort field mapping
		$arrSortBy = array(
		"emp_idnum" => "emp_idnum"
		,"pi_lname" => "pi_lname"
		,"pi_fname" => "pi_fname"
		,"post_name" => "post_name"
		,"bankiemp_acct_name" => "bankiemp_acct_name"
		,"banklist_name" => "banklist_name"
		,"bankiemp_acct_no" => "bankiemp_acct_no"
		,"baccntype_name" => "baccntype_name"
		);

		if (isset($_GET['sortby'])) {
			$strOrderBy = " ORDER BY ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
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
				    INNER JOIN `bank_infoemp` 
				        ON (`bank_infoemp`.`emp_id` = `emp_masterfile`.`emp_id`)
				$criteria";
		$rsResult = $this->conn->Execute($sql2);
		if (!$rsResult->EOF) {
			$mycount = $rsResult->fields['mycount'];
		}
		
		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$chkAttend = "<input type=\"checkbox\" name=\"chkAttend[]\" id=\"chkAttend[',@t1:=@t1+1,']\" value=\"',`bank_infoemp`.`bankiemp_id`,'\" onclick=\"javascript:UncheckAll({$mycount});\">";
		
		// SqlAll Query
		$sql = "SELECT
					emp_idnum,
					pi_lname,
					pi_fname,
					post_name,
					bankiemp_acct_name,
					banklist_name,
					bankiemp_acct_no,
					baccntype_name,
					CONCAT('$chkAttend') AS chkbox
				FROM
				    `emp_masterfile`
				    INNER JOIN `emp_personal_info` 
				        ON (`emp_personal_info`.`pi_id` = `emp_masterfile`.`pi_id`)
				    INNER JOIN `bank_infoemp` 
				        ON (`bank_infoemp`.`emp_id` = `emp_masterfile`.`emp_id`)
				    INNER JOIN `emp_position` 
				        ON (`emp_position`.`post_id` = `emp_masterfile`.`post_id`)
				    INNER JOIN `bnkaccnt_type` 
				        ON (`bnkaccnt_type`.`baccntype_id` = `bank_infoemp`.`baccntype_id`)
				    INNER JOIN `bank_list` 
				        ON (`bank_list`.`banklist_id` = `bank_infoemp`.`banklist_id`)
				$criteria
				$strOrderBy";
		
		// Field and Table Header Mapping
		$arrFields = array(
		"chkbox" =>"<input title=\"Select All\" type=\"checkbox\" name=\"chkAttendAll\" id=\"chkAttendAll\" onclick=\"javascript:CheckAll({$mycount});\" style=\"margin-left: 3px;\" />"
		,"emp_idnum"=>"Emp. No."
		,"pi_lname"=>"Last Name"
		,"pi_fname"=>"First Name"
		,"post_name"=>"Position"
		,"bankiemp_acct_name"=>"Account Name"
		,"banklist_name"=>"Bank Name"
		,"bankiemp_acct_no"=>"Account Number"
		,"baccntype_name"=>"Account Type"
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
		$tblDisplayList->tblBlock->assign("noSearchStart","<!--");
		$tblDisplayList->tblBlock->assign("noSearchEnd","-->");
		
		return $tblDisplayList->getTableList($arrAttribs);
	}
	
	function checkEmployeeAccountWithNoBankGroup() {
		$isValid = true;
		
		// Count Employee with no Bank Group
		$sql = "SELECT 
				  COUNT(`bank_infoemp`.`banklist_id`) AS number_of_employee_account_with_no_bank_group 
				FROM
				  `bank_infoemp` 
				  INNER JOIN `emp_masterfile` 
				    ON (
				      `emp_masterfile`.`emp_id` = `bank_infoemp`.`emp_id`
				    ) 
				WHERE `bank_infoemp`.`banklist_id` = 0 AND `emp_masterfile`.`emp_stat` IN ('1', '7', '10')";
		$sql_result = $this->conn->Execute($sql);
		
		if ($sql_result->fields[number_of_employee_account_with_no_bank_group] == 0) {
			$isValid = false;
			$_SESSION['eMsg'][] = "All Employee Account is assigned to a Bank Group.";
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
			$_SESSION['eMsg'][] = "Please Select Employee Account/s to {$kind_of_validation}.";
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
				$qry[] = "(pinfo.pi_fname like '%$search_field%' or pinfo.pi_lname like '%$search_field%' or dept.ud_name like '%$search_field%')";
			}
		}

		$qry[] = "fr_id = '".$_GET['fr_id']."'";
        
		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" WHERE ".implode(" and ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		"checkbox"=>"checkbox"
		,"emp_idnum" => "emp_idnum"
		,"pi_lname" => "pi_lname"
		,"pi_fname" => "pi_fname"
		,"post_name" => "post_name"
		,"comp_name" => "comp_name"
		,"ud_name" => "ud_name"
		);

		if (isset($_GET['sortby'])) {
			$strOrderBy = " ORDER BY ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
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
		"chkbox" => "<input title=\"Select All\" type=\"checkbox\" name=\"chkAttendAll\" id=\"chkAttendAll\" onclick=\"javascript:CheckAll({$mycount});\" style=\"margin-left: 9px;\" />"
		,"emp_idnum" => "Emp No."
		,"pi_lname" => "Last Name"
		,"pi_fname" => "First Name"
		,"post_name" => "Position"
		,"comp_name" => "Company"
		,"ud_name" => "Department"
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
		$tblDisplayList->tblBlock->templateFile = "table_nosort.tpl.php";
		
		return $tblDisplayList->getTableList($arrAttribs);
	}
	
	/**
	 * Display Employee with no Bank Group assigned
	 *
	 * @return array
	 */
	function getTableListEmployeeWithNoBankGroupAssigned() {
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
				$qry[] = "(bankiemp_acct_name LIKE '%$search_field%')";
			}
		}

		$qry[] = "`bank_infoemp`.`banklist_id` = 0";
		$qry[] = "`emp_masterfile`.`emp_stat` IN ('1', '7', '10')";
        
		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" WHERE ".implode(" and ",$qry):"";
		
		// Sort field mapping
		$arrSortBy = array(
		"emp_idnum" => "emp_idnum"
		,"pi_lname" => "pi_lname"
		,"pi_fname" => "pi_fname"
		,"post_name" => "post_name"
		,"bankiemp_acct_name" => "bankiemp_acct_name"
		,"bankiemp_acct_no" => "bankiemp_acct_no"
		,"baccntype_name" => "baccntype_name"
		);

		if (isset($_GET['sortby'])) {
			$strOrderBy = " ORDER BY ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
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
				    INNER JOIN `bank_infoemp` 
				        ON (`bank_infoemp`.`emp_id` = `emp_masterfile`.`emp_id`)
					$criteria";
		$rsResult = $this->conn->Execute($sql2);
		if (!$rsResult->EOF) {
			$mycount = $rsResult->fields['mycount'];
		}

		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$chkAttend = "<input type=\"checkbox\" name=\"chkAttend[]\" id=\"chkAttend[',@t1:=@t1+1,']\" value=\"',`bank_infoemp`.`bankiemp_id`,'\" onclick=\"javascript:UncheckAll({$mycount});\">";
		
		// SqlAll Query
		$sql = "SELECT
					emp_idnum,
					pi_lname,
					pi_fname,
					post_name,
					bankiemp_acct_name,
					bankiemp_acct_no,
					baccntype_name,
					CONCAT('$chkAttend') AS chkbox
				FROM
				    `emp_masterfile`
				    INNER JOIN `emp_personal_info` 
				        ON (`emp_personal_info`.`pi_id` = `emp_masterfile`.`pi_id`)
				    INNER JOIN `bank_infoemp` 
				        ON (`bank_infoemp`.`emp_id` = `emp_masterfile`.`emp_id`)
				    INNER JOIN `emp_position` 
				        ON (`emp_position`.`post_id` = `emp_masterfile`.`post_id`)
				    INNER JOIN `bnkaccnt_type` 
				        ON (`bnkaccnt_type`.`baccntype_id` = `bank_infoemp`.`baccntype_id`)
				$criteria
				$strOrderBy";
		
		// Field and Table Header Mapping
		$arrFields = array(
		"chkbox" =>"<input title=\"Select All\" type=\"checkbox\" name=\"chkAttendAll\" id=\"chkAttendAll\" onclick=\"javascript:CheckAll({$mycount});\" style=\"margin-left: 3px;\" />"
		,"emp_idnum"=>"Emp. No."
		,"pi_lname"=>"Last Name"
		,"pi_fname"=>"First Name"
		,"post_name"=>"Position"
		,"bankiemp_acct_name"=>"Account Name"
		,"bankiemp_acct_no"=>"Account Number"
		,"baccntype_name"=>"Account Type"
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
		$tblDisplayList->tblBlock->assign("noSearchStart","<!--");
		$tblDisplayList->tblBlock->assign("noSearchEnd","-->");
		
		return $tblDisplayList->getTableList($arrAttribs);
	}
	
	function assignEmployeeAccount($pData) {
		//printa($pData);exit;
		$flds = array();
		$flds_ = array();
		$ctr = 0;
		
		do {
			$sqlpcal = "SELECT * FROM bank_infoemp WHERE bankiemp_id='".$pData['chkAttend'][$ctr]."'";
			$pps = $this->conn->Execute($sqlpcal);
			if (!$pps->EOF) {
				$flds_[]="banklist_id='".$_GET['banklist_id']."'";
				$flds_[] = "bankiemp_updatewho='".$_SESSION['admin_session_obj']['user_data']['user_name']."'";
				$fields_ = implode(", ",$flds_);
				$sql_update = "UPDATE bank_infoemp SET $fields_ WHERE bankiemp_id='".$pps->fields['bankiemp_id']."'";
				$this->conn->Execute($sql_update);
			} else {
				$flds_[] = "bankiemp_id='".$pData['chkAttend'][$ctr]."'";
				$flds_[] = "bankiemp_addwho='".$_SESSION['admin_session_obj']['user_data']['user_name']."'";
				$flds_[]="banklist_id='".$_GET['banklist_id']."'";
				$fields_ = implode(", ",$flds_);
				$sql_insert = "INSERT INTO bank_infoemp SET $fields_";
				$this->conn->Execute($sql_insert);
			}
			$flds_ = "";
			$fields_ = "";
			$ctr++;
		} while($ctr < sizeof($pData['chkAttend']));
		
		$sql_bank_group = "SELECT DISTINCT banklist_name FROM `bank_list` INNER JOIN `bank_infoemp` ON (`bank_list`.`banklist_id` = `bank_infoemp`.`banklist_id`) WHERE `bank_infoemp`.`banklist_id` = '".$_GET['banklist_id']."'";
		$sql_bank_group_result = $this->conn->Execute($sql_bank_group);
		
		$_SESSION['eMsg']="Successfully Assigned Employee Account/s to {$sql_bank_group_result->fields[banklist_name]}.";
	}
	
	function removeEmployeeAccount($pData) {
		//printa($pData);exit;
		$flds = array();
		$flds_ = array();
		$ctr = 0;
		
		$sql_bank_group = "SELECT DISTINCT banklist_name FROM `bank_list` INNER JOIN `bank_infoemp` ON (`bank_list`.`banklist_id` = `bank_infoemp`.`banklist_id`) WHERE `bank_infoemp`.`banklist_id` = '".$_GET['banklist_id']."'";
		$sql_bank_group_result = $this->conn->Execute($sql_bank_group);
		
		do {
			$sqlpcal = "SELECT * FROM bank_infoemp WHERE bankiemp_id='".$pData['chkAttend'][$ctr]."'";
			$pps = $this->conn->Execute($sqlpcal);
			if (!$pps->EOF) {
				$flds_[]="banklist_id = 0";
				$flds_[] = "bankiemp_updatewho='".$_SESSION['admin_session_obj']['user_data']['user_name']."'";
				$fields_ = implode(", ",$flds_);
				$sql_update = "UPDATE bank_infoemp SET $fields_ WHERE bankiemp_id='".$pps->fields['bankiemp_id']."'";
				$this->conn->Execute($sql_update);
			} else {
				$flds_[] = "bankiemp_id='".$pData['chkAttend'][$ctr]."'";
				$flds_[] = "bankiemp_addwho='".$_SESSION['admin_session_obj']['user_data']['user_name']."'";
				$flds_[]="banklist_id = 0";
				$fields_ = implode(", ",$flds_);
				$sql_insert = "INSERT INTO bank_infoemp SET $fields_";
				$this->conn->Execute($sql_insert);
			}
			$flds_ = "";
			$fields_ = "";
			$ctr++;
		} while($ctr < sizeof($pData['chkAttend']));
		
		$_SESSION['eMsg']="Successfully Removed Employee Account/s to {$sql_bank_group_result->fields[banklist_name]}.";
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
	
	/**
	 * @note: Get Branch List
	 */
	function getBrachList($comp = null){
		$sql = "SELECT * FROM branch_info WHERE comp_id ='".$comp."' order by  branchinfo_name asc";
		$rsResult = $this->conn->Execute($sql);
		$cResult = array();
		while ( !$rsResult->EOF ) {       	
			$cResult[] = $rsResult->fields;        	
        	$rsResult->MoveNext();
        }
        return $cResult;
	}
}
?>