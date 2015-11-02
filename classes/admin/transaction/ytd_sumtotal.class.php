<?php
/**
 * Initial Declaration
 */
$importTYPE = array(
  	 '0'=>'Select Import Type'
	,'1'=>'YTD Summary'
	,'2'=>'Pay Elements'
);

/**
 * Class Module
 *
 * @author  IR Salvador
 *
 */
class clsYTD_SumTotal{

	var $conn;
	var $fieldMap;
	var $Data;

	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsYTD_SumTotal object
	 */
	function clsYTD_SumTotal($dbconn_ = null){
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
	function dbFetch($gData = array()){
		$qry = array();
		if (!is_null($gData['ytdhead'])) { 
			$qry[] = "a.import_ytd_head_id ='".$gData['ytdhead']."'"; 
		}
		if(!is_null($gData['emp_id'])||$gData['emp_id']!=""){ 
			$qry[] = "a.emp_id ='".$gData['emp_id']."'"; 
		}
		$criteria = (count($qry)>0)?" WHERE ".implode(" AND ",$qry):"";
		$sql = "SELECT import_ytd_detail_arr, d.pps_name, DATE_FORMAT(c.payperiod_start_date, '%d %b %Y') as start_date, DATE_FORMAT(c.payperiod_end_date, '%d %b %Y') as end_date
					FROM import_ytd_details a
					JOIN import_ytd_head b on (a.import_ytd_head_id=b.import_ytd_head_id)
					JOIN payroll_pay_period c on (c.payperiod_id=b.payperiod_id)
					JOIN payroll_pay_period_sched d on (d.pps_id=c.pps_id)
					$criteria";
		$rsResult = $this->conn->Execute($sql);
		if(!$rsResult->EOF){
			$rsResult->fields['paystubdetails'] = unserialize($rsResult->fields['import_ytd_detail_arr']);
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
	function doValidateData($pData_ = array(), $gData_ = array()){
		$isValid = true;
		
		if (!$pData_['uptahead_file']['type']) {
			$_SESSION['eMsg'][] = "Please Choose File.";
			$isValid = false;
			return $isValid;
		} elseif (pathinfo($pData_['uptahead_file']['name'], PATHINFO_EXTENSION) != 'xls') {
			$_SESSION['eMsg'][] = "Invalid File Type. The File Extension should only be .xls";
			$isValid = false;
			return $isValid;
		}
		if($pData_['import_type'] == 0){
			$_SESSION['eMsg'][] = "Please select an import type.";
		}
		$this->xlsData = $this->doReadAndInsertUPLoan($pData_['uptahead_file']['tmp_name']);
		if($pData_['import_type'] == 1){
		// Import Validation for SUMMARY
			if (count($pData_) == 0) {
				return NULL;
			}
			$badQtyCtr = 0;
			$rowPos = 6;
			$rowCnt = $this->xlsData[0]['numRows'];
	//		echo $rowCnt; exit;
	//		if (($rowCnt - 6) == 0) {
	//			$_SESSION['eMsg'][] = "Datasheet is blank.";
	//			$isValid = false;
	//			return $isValid;
	//		}
			if($this->xlsData[0]['cells'][3][1] != "YTD SUMMARY & POLICY"){
				$_SESSION['eMsg'][] = "Incorrect import template. Please select the right template for this import.";
				$isValid = false;
				return $isValid;
			}
			for ($rowPos; $rowPos <= $rowCnt; $rowPos++) {
				$cellDataArr = $this->xlsData[0]['cells'][$rowPos];
				
				//variable assigning
				$emp_idnum = trim($cellDataArr[1]);
	//			$basicSalaryRate = trim($cellDataArr[3]);
	//			$basicPGRate = trim($cellDataArr[4]);
	//			$COLA = trim($cellDataArr[5]);
	//			$OTAmount = trim($cellDataArr[6]);
	//			$LeaveDedAmount = trim($cellDataArr[7]);
	//			$grossPay = trim($cellDataArr[8]);
	//			$otherStatIncome = trim($cellDataArr[9]);
	//			$otherStatTaxIncome = trim($cellDataArr[10]);
	//			$otherStatTaxDed = trim($cellDataArr[11]);
	//			$statIncome = trim($cellDataArr[12]);
	//			$otherTaxIncome = trim($cellDataArr[13]);
	//			$otherTaxDed = trim($cellDataArr[14]);
	//			$taxableIncomeGross = trim($cellDataArr[15]);
	//			$nonTaxIncome = trim($cellDataArr[16]);
	//			$otherDeduction = trim($cellDataArr[17]);
	//			$netPay = trim($cellDataArr[18]);
	//			$tax = trim($cellDataArr[19]);
	//			$PHICee = trim($cellDataArr[20]);
	//			$PHICer = trim($cellDataArr[21]);
	//			$SSSee = trim($cellDataArr[22]);
	//			$SSSer = trim($cellDataArr[23]);
	//			$SSSec = trim($cellDataArr[24]);
	//			$HDMFee = trim($cellDataArr[25]);
	//			$HDMFer = trim($cellDataArr[26]);
				
				if(empty($emp_idnum)){
					$_SESSION['eMsg'][] = "In Row {$rowPos}, Column Employee No. should not be blank.";
					$isValid = false;
				} else {
					$sql = "SELECT emp_idnum FROM `emp_masterfile` WHERE emp_idnum = '{$emp_idnum}'";
					$emp_idnum_result = $this->conn->Execute($sql);
					if (!$emp_idnum_result->fields) {
						$_SESSION['eMsg'][] = "In Row {$rowPos}, Column \"Employee No.\", \"{$emp_idnum}\" is not in the database.";
						$isValid = false;
					}
				}
				$emp_id = $this->getEmpId($emp_idnum);
				if(!$this->validateIfBelongToPPS($emp_id, $gData_['ppsid'])){
					$_SESSION['eMsg'][] = "In Row {$rowPos}, ". trim($cellDataArr[2]) ." does not belong to this pay group.";
					$isValid = false;
				}
			}
		}
		if($pData_['import_type'] == 2){
			$this->xlsData = $this->doReadAndInsertUPLoan($pData_['uptahead_file']['tmp_name']);
			if (count($pData_) == 0) {
				return NULL;
			}
			$badQtyCtr = 0;
			$rowPos = 6;
			$rowCnt = $this->xlsData[0]['numRows'];
	//		echo $rowCnt; exit;
	//		if (($rowCnt - 6) == 0) {
	//			$_SESSION['eMsg'][] = "Datasheet is blank.";
	//			$isValid = false;
	//			return $isValid;
	//		}
			if($this->xlsData[0]['cells'][3][1] != "YTD Pay Element Import"){
				$_SESSION['eMsg'][] = "Incorrect import template. Please select the right template for this import.";
				$isValid = false;
				return $isValid;
			}
			for ($rowPos; $rowPos <= $rowCnt; $rowPos++) {
				$errArrReq = array();
				$cellDataArr = $this->xlsData[0]['cells'][$rowPos];
				
				//variable assigning
				$pe_type = trim($cellDataArr[1]);
				$pe_name = trim($cellDataArr[2]);
				$emp_idnum = trim($cellDataArr[3]);
				$amt = trim($cellDataArr[5]);
				
				if(!empty($emp_idnum)){
					$sql = "SELECT emp_idnum FROM `emp_masterfile` WHERE emp_idnum = '{$emp_idnum}'";
					$emp_idnum_result = $this->conn->Execute($sql);
					if (!$emp_idnum_result->fields) {
						$_SESSION['eMsg'][] = "In Row {$rowPos}, Column \"Employee No.\", \"{$emp_idnum}\" is not in the database.";
						$isValid = false;
					}
					$emp_id = $this->getEmpId($emp_idnum);
					if(!$this->validateIfBelongToPPS($emp_id, $gData_['ppsid'])){
						$_SESSION['eMsg'][] = "In Row {$rowPos}, employee does not belong to this pay group. Please fix the import template.";
						$isValid = false;
					}
					if(empty($pe_type)){
						$errArrReq[] = '"Pay Element Type"';
						$isValid = false;
					}
					if(empty($pe_name)){
						$errArrReq[] = '"Pay Element"';
						$isValid = false;
					}
					if(empty($amt)){
						$errArrReq[] = '"Amount"';
						$isValid = false;
					}
				}
				if (count($errArrReq) > 0) {
					$_SESSION['eMsg'][] = "In Row {$rowPos}, Column " . implode(", ", $errArrReq) . " is required.";
				}
			}
		}
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
		$sql1 = "select payperiod_id from import_ytd_head where import_ytd_head_id=?";
		$r1 = $this->conn->Execute($sql1,array($id_));
		while(!$r1->EOF){
			$pp = $r1->fields['payperiod_id'];
			$sql2 = "select paystub_id from payroll_pay_stub where payperiod_id=?";
			$r2 = $this->conn->Execute($sql2,array($pp));
			while(!$r2->EOF){
				$ps = $r2->fields['paystub_id'];
				// delete amendments
				$sql3 = "delete from payroll_ps_amendemp where paystub_id=?";
				$r3 = $this->conn->Execute($sql3,array($ps));
				// delete payroll entry
				$sql4 = "delete from payroll_paystub_entry where paystub_id=?";
				$r4 = $this->conn->Execute($sql4,array($ps));
				// delete payroll report
				$sql5 = "delete from payroll_paystub_report where paystub_id=?";
				$r5 = $this->conn->Execute($sql5,array($ps));
				$r2->MoveNext();
				// delete TA Record
				$sql6 = "delete from ta_emp_rec where paystub_id=?";
				$r6 = $this->conn->Execute($sql6,array($ps));
			}
			$r1->MoveNext();
		}
		// delete payroll paystub
		$sql6 = "delete from payroll_pay_stub where paystub_id=?";
		$r5 = $this->conn->Execute($sql5,array($ps));
		$sql = "delete from import_ytd_details where import_ytd_head_id=?";
		$this->conn->Execute($sql,array($id_));
		$sql = "delete from import_ytd_head where import_ytd_head_id=?";
		$this->conn->Execute($sql,array($id_));
		$_SESSION['eMsg']="Successfully Deleted.";
	}
	
	function doDeleteEmp($ytdhead_id_ = null, $emp_id_ = null){
		$sql1 = "select payperiod_id from import_ytd_head where import_ytd_head_id=? and emp_id=?";
		$r1 = $this->conn->Execute($sql1,array($ytdhead_id_,$emp_id_));
		if(!$r1->EOF){
			$pp = $r1->fields['payperiod_id'];
			$sql2 = "select paystub_id from payroll_pay_stub where payperiod_id=?";
			$r2 = $this->conn->Execute($sql2,array($pp));
			while(!$r2->EOF){
				$ps = $r2->fields['paystub_id'];
				// delete amendments
				$sql3 = "delete from payroll_ps_amendemp where paystub_id=?";
				$r3 = $this->conn->Execute($sql3,array($ps));
				// delete payroll entry
				$sql4 = "delete from payroll_paystub_entry where paystub_id=?";
				$r4 = $this->conn->Execute($sql4,array($ps));
				// delete payroll report
				$sql5 = "delete from payroll_paystub_report where paystub_id=?";
				$r5 = $this->conn->Execute($sql5,array($ps));
				$r2->MoveNext();
			}
		}
		// delete payroll paystub
		$sql6 = "delete from payroll_pay_stub where paystub_id=?";
		$r5 = $this->conn->Execute($sql5,array($ps));
		$sql = "delete from import_ytd_details where import_ytd_head_id=? and emp_id=?";
		$this->conn->Execute($sql,array($ytdhead_id_, $emp_id_));
		$_SESSION['eMsg']="Employee Successfully Deleted.";
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
				$qry[] = "pps_name like '%$search_field%'";
			}
		}
        $time = date('Y-m-d h:i:s',dDate::parseDateTime(dDate::getTime()));
//		$qry[] = "ppp.payperiod_trans_date > '".$time."'";
		$qry[] = "ppp.pp_stat_id in (1,2)";
		$qry[] = "ppp.payperiod_type=2";
		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		 "pps_name" => "pps_name"
		,"salaryclass_id" => "salaryclass_name"
		,"pp_stat_id" => "stat_name"
		,"payperiod_start_date" => "payperiod_start_date"
		,"payperiod_end_date" => "payperiod_end_date"
		,"payperiod_trans_date" => "payperiod_trans_date"
		);

		if(isset($_GET['sortby'])){
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}else{
			$strOrderBy = " order by ppp.payperiod_id DESC";
		}
		
		//@note: this is used to count and check all the checkbox.
		//@note set t1 = 0
		$sql = "set @t1:=0";
		$this->conn->Execute($sql);	
		
		//get total number of records and pass it to the javascript function CheckAll
			$sql_ = "select count(*) as mycount_
						from payroll_pay_period ppp
						inner join payroll_pay_period_sched psar on (psar.pps_id=ppp.pps_id)
					$criteria
					$strOrderBy";
			$rsResult = $this->conn->Execute($sql_);
			if(!$rsResult->EOF){
				$mycount = $rsResult->fields['mycount_'];
			}

		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$viewLink = "<a href=\"?statpos=ytd_import&view&ppsid=',psar.pps_id,'&ppid=',ppp.payperiod_id,'\">',psar.pps_name,'</a>";
		$empLink = "<a href=\"?statpos=payperiodsched&empinput=',psar.pps_id,'\"><img src=\"".SYSCONFIG_THEME_URLPATH.SYSCONFIG_THEME."/images/admin/report_user.gif\" title=\"Select Employee\" hspace=\"2px\" border=0></a>";
		$ctr=0;		
		$chkAttend = "<input type=\"checkbox\" name=\"chkAttend[]\" id=\"chkAttend[',@t1:=@t1+1,']\" value=\"',ppp.payperiod_id,'\" onclick=\"javascript:UncheckAll(".$mycount.");\">";
//		$editLink = "<a href=\"?statpos=endpayperiod&ppsched=',psar.pps_id,'&ppsched_edit=',ppp.payperiod_id,'\"><img src=\"".SYSCONFIG_THEME_URLPATH.SYSCONFIG_THEME."/images/admin/edit.gif\" title=\"Edit\" hspace=\"2px\" border=0></a>";
//		$delLink = "<a href=\"?statpos=endpayperiod&ppsched=',psar.pps_id,'&ppsched_del=',ppp.payperiod_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_THEME_URLPATH.SYSCONFIG_THEME."/images/admin/trash.gif\" title=\"Delete\" hspace=\"2px\"  border=0></a>";

		// SqlAll Query
		$sql = "select ppp.*, CONCAT('$viewLink') as viewdata, 
				DATE_FORMAT(payperiod_start_date,'%d %b %Y %h:%i %p') as payperiod_start_date,
				DATE_FORMAT(payperiod_end_date,'%d %b %Y %h:%i %p') as payperiod_end_date,
				DATE_FORMAT(payperiod_trans_date,'%d %b %Y') as payperiod_trans_date,
				psar.pps_name, CONCAT('$chkAttend') as chkbox,
				if(salaryclass_id='1','Daily',IF(salaryclass_id='2','Weekly',IF(salaryclass_id='3','Bi-Weekly',IF(salaryclass_id='4','Semi-monthly',IF(salaryclass_id='5','Monthly','Annual'))))) as salaryclass_id,
				IF(pp_stat_id='1','OPEN',IF(pp_stat_id='2','Locked - Pending Approval',IF(pp_stat_id='3','CLOSED','Post Adjustment'))) as pp_stat_id
						from payroll_pay_period ppp
						inner join payroll_pay_period_sched psar on (psar.pps_id=ppp.pps_id)
						$criteria
						$strOrderBy";

		// Field and Table Header Mapping
		$arrFields = array(
		 /*"chkbox"=>"<input type=\"checkbox\" name=\"chkAttendAll\" id=\"chkAttendAll\" onclick=\"javascript:CheckAll(".$mycount.");\">"*/
		 "viewdata" => "Name"
		,"salaryclass_id" => "Type"
		,"pp_stat_id" => "Status"
		,"payperiod_start_date" => "Start"
		,"payperiod_end_date" => "End"
		,"payperiod_trans_date" => "Pay Date"
		);

		// Column (table data) User Defined Attributes
		$arrAttribs = array(
		 "mnu_ord"=>"align='right'"
		,"pps_name"=>"width='150'"
		,"salaryclass_name"=>"width='120'"
		,"chkbox"=>"width='10' align='center'"
		);

		// Process the Table List
		$tblDisplayList = new clsTableList($this->conn);
		$tblDisplayList->arrFields = $arrFields;
		$tblDisplayList->paginator->linkPage = "?$queryStr";
		$tblDisplayList->sqlAll = $sql;
		$tblDisplayList->sqlCount = $sqlcount;
		$tblDisplayList->tblBlock->templateFile = "table_nosort.tpl.php";
		$tblDisplayList->tblBlock->assign("noPaginatorStart","<!--");
		$tblDisplayList->tblBlock->assign("noPaginatorEnd","-->");

		return $tblDisplayList->getTableList($arrAttribs);
	}
	
	function doReadAndInsertUPLoan($fname_ = NULL) {
		if (is_null($fname_)) {
			return NULL;
		}
		$objClsExcelReader = new Spreadsheet_Excel_Reader();
		$objClsExcelReader->read($fname_);
		return $objClsExcelReader->sheets;
	}
	
	/*
	 * Upload YTD
	 */
	function doSummarySaveImport($pData_ = array(), $gData = array()){
		if (count($pData_) == 0) {
			return NULL;
		}
		$badQtyCtr = 0;
		$rowPos = 6;
		$rowCnt = $this->xlsData[0]['numRows'];
		for ($rowPos = 6; $rowPos <= $rowCnt; $rowPos++) {
			$arrData = array();
			$cellDataArr = $this->xlsData[0]['cells'][$rowPos];
			$emp_idnum = trim($cellDataArr[1]);
			$basicSalaryRate = (trim($cellDataArr[3]) == '' ? 0 : trim($cellDataArr[3]));
			$basicPGRate = (trim($cellDataArr[4]) == '' ? 0 : trim($cellDataArr[4]));
			$COLA = (trim($cellDataArr[5]) == '' ? 0 : trim($cellDataArr[5]));
			$OTAmount = (trim($cellDataArr[6]) == '' ? 0 : trim($cellDataArr[6]));
			$LeaveDedAmount = (trim($cellDataArr[7]) == '' ? 0 : trim($cellDataArr[7]));
			$grossPay = (trim($cellDataArr[8]) == '' ? 0 : trim($cellDataArr[8]));
			$otherStatIncome = (trim($cellDataArr[9]) == '' ? 0 : trim($cellDataArr[9]));
			$otherStatTaxIncome = (trim($cellDataArr[10]) == '' ? 0 : trim($cellDataArr[10]));
			$otherStatTaxDed = (trim($cellDataArr[11]) == '' ? 0 : trim($cellDataArr[11]));
			$statIncome = (trim($cellDataArr[12]) == '' ? 0 : trim($cellDataArr[12]));
			$otherTaxIncome = (trim($cellDataArr[13]) == '' ? 0 : trim($cellDataArr[13]));
			$otherTaxDed = (trim($cellDataArr[14]) == '' ? 0 : trim($cellDataArr[14]));
			$taxableIncomeGross = (trim($cellDataArr[15]) == '' ? 0 : trim($cellDataArr[15]));
			$nonTaxIncome = (trim($cellDataArr[16]) == '' ? 0 : trim($cellDataArr[16]));
			$otherDeduction = (trim($cellDataArr[17]) == '' ? 0 : trim($cellDataArr[17]));
			$netPay = (trim($cellDataArr[18]) == '' ? 0 : trim($cellDataArr[18]));
			$tax = (trim($cellDataArr[19]) == '' ? 0 : trim($cellDataArr[19]));
			$PHICWage = (trim($cellDataArr[20]) == '' ? 0 : trim($cellDataArr[20]));
			$PHICee = (trim($cellDataArr[21]) == '' ? 0 : trim($cellDataArr[21]));
			$PHICer = (trim($cellDataArr[23]) == '' ? 0 : trim($cellDataArr[23]));
			$SSSWage = (trim($cellDataArr[24]) == '' ? 0 : trim($cellDataArr[24]));
			$SSSee = (trim($cellDataArr[25]) == '' ? 0 : trim($cellDataArr[25]));
			$SSSer = (trim($cellDataArr[26]) == '' ? 0 : trim($cellDataArr[26]));
			$SSSec = (trim($cellDataArr[27]) == '' ? 0 : trim($cellDataArr[27]));
			$HDMFWage = (trim($cellDataArr[28]) == '' ? 0 : trim($cellDataArr[28]));
			$HDMFee = (trim($cellDataArr[29]) == '' ? 0 : trim($cellDataArr[29]));
			$HDMFer = (trim($cellDataArr[30]) == '' ? 0 : trim($cellDataArr[30]));
			if (empty($emp_idnum)) {
				$skipped_row++;
			} else {
				$emp_id = $this->getEmpId($emp_idnum);
				$flds = array();
				if($this->checkIfPPExists($gData['ppid']) == 0){
					$flds[] = "import_ytd_head_addwho='".AppUser::getData('user_name')."'";
					$flds[] = "payperiod_id='".$gData['ppid']."'";
					$fields = implode(", ",$flds);
					$sqlHead = "INSERT INTO import_ytd_head SET $fields";
					$this->conn->Execute($sqlHead);
					$lastInsertedHeadID = $this->conn->Insert_ID();
				} else {
					$lastInsertedHeadID = $this->checkIfPPExists($gData['ppid']);
				}
				if($this->validateIfBelongToPPS($emp_id, $gData['ppsid'])){
					$flds2 = array();
					if($this->checkIfEmpExists($emp_id, $lastInsertedHeadID)){
						$empinfo = "SELECT *,txcep.taxep_name,a.emp_id,a.comp_id, CONCAT(g.pi_fname,' ',UPPER(SUBSTRING(g.pi_mname,1,1)),'. ',g.pi_lname) as fullname, bank.bankiemp_acct_no, blist.banklist_name, DATE_FORMAT(f.payperiod_trans_date,'%d') as ppdTransDate, DATE_FORMAT(f.payperiod_start_date,'%d') as ppdStartDate 
								FROM emp_masterfile a
								JOIN salary_info b on (b.emp_id=a.emp_id)
								JOIN payroll_pps_user c on (c.emp_id = a.emp_id)
								JOIN payroll_pay_period_sched d on (d.pps_id=c.pps_id)
								JOIN payroll_comp pcal on (a.emp_id=pcal.emp_id)
								JOIN factor_rate e on (e.fr_id=pcal.fr_id)
								LEFT JOIN app_wagerate wrate on (wrate.wrate_id=e.wrate_id)
								JOIN payroll_pay_period f on (f.pps_id=d.pps_id)
								JOIN emp_personal_info g on (g.pi_id=a.pi_id)
								JOIN company_info i on (i.comp_id=a.comp_id)
								LEFT JOIN emp_position h on (h.post_id=a.post_id)
								LEFT JOIN app_userdept j on (j.ud_id=a.ud_id)
								LEFT JOIN emp_type z on (z.emptype_id=a.emptype_id)
								LEFT JOIN bank_infoemp bank on (bank.emp_id=a.emp_id)
								LEFT JOIN bank_list blist on blist.banklist_id=bank.banklist_id
								LEFT JOIN tax_excep txcep on (txcep.taxep_id=a.taxep_id) 
								WHERE a.emp_id='".$emp_id."' and c.pps_id='".$gData['ppsid']."' and f.payperiod_id='".$gData['ppid']."' and b.salaryinfo_isactive='1'";
						$rsResult = $this->conn->Execute($empinfo);
						
						$arrData['empinfo'] = array(
								 "emp_id" => $rsResult->fields['emp_id']
								,"emp_no" => $rsResult->fields['emp_idnum']
								,"fullname" => $rsResult->fields['fullname']
								,"jobcode" => $rsResult->fields['post_code']
								,"jobpos_name" => $rsResult->fields['post_name']
								,"comp_name" => $rsResult->fields['comp_name']
								,"comp_add" => $rsResult->fields['comp_add']
								,"ud_name" => $rsResult->fields['ud_name']
								,"tax_ex_name" => $rsResult->fields['taxep_name']
								,"comp_id" => $rsResult->fields['comp_id']
								,"ud_id" => $rsResult->fields['ud_id']
								,"emptype_name" => $rsResult->fields['emptype_name']
								,"bankiemp_acct_no" => $rsResult->fields['bankiemp_acct_no']
								,"banklist_name" => $rsResult->fields['banklist_name']
								,"pi_emailone" => $rsResult->fields['pi_emailone']
								,"taxep_id" => $rsResult->fields['taxep_id']
								,"salaryclass_id" => $rsResult->fields['salaryclass_id']
								,"tt_pay_group" => $rsResult->fields['tt_pay_group']
								,"ud_code" => $rsResult->fields['ud_code']
								,"taxep_code" => $rsResult->fields['taxep_code']
								,"salarytype_id" => $rsResult->fields['salarytype_id']
							);
						
						$arrData['ytd_summary'] = array(
								"basicSalaryRate" => $basicSalaryRate
								,"basicPGRate" => $basicPGRate
								,"COLA" => $COLA
								,"OTAmount" => $OTAmount
								,"LeaveDedAmount" => $LeaveDedAmount
								,"grossPay" => $grossPay
								,"otherStatIncome" => $otherStatIncome
								,"otherStatTaxIncome" => $otherStatTaxIncome
								,"otherStatTaxDed" => $otherStatTaxDed
								,"statIncome" => $statIncome
								,"otherTaxIncome" => $otherTaxIncome
								,"otherTaxDed" => $otherTaxDed
								,"taxableIncomeGross" => $taxableIncomeGross
								,"nonTaxIncome" => $nonTaxIncome
								,"otherDeduction" => $otherDeduction
								,"netPay" => $netPay
								,"tax" => $tax
								,"PHICWage" => $PHICWage
								,"PHICee" => $PHICee
								,"PHICer" => $PHICer
								,"SSSWage" => $SSSWage
								,"SSSee" => $SSSee
								,"SSSer" => $SSSer
								,"SSSec" => $SSSec
								,"HDMFWage" => $HDMFWage
								,"HDMFee" => $HDMFee
								,"HDMFer" => $HDMFer
							);
						// for earnings and deductions array 
						$arrData['Earnings'] = array();
						$arrData['Deductions'] = array();
						
						$flds2[] = "import_ytd_detail_addwho='".AppUser::getData('user_name')."'";
						$flds2[] = "import_ytd_detail_addwhen=now()";
						$flds2[] = "import_ytd_head_id='".$lastInsertedHeadID."'";
						$flds2[] = "emp_id='".$emp_id."'";
						$flds2[] = "import_ytd_detail_arr='".serialize($arrData)."'";
						$fieldsDetails = implode(", ",$flds2);
						
						$sqlDetails = "INSERT INTO import_ytd_details SET $fieldsDetails";
						$this->conn->Execute($sqlDetails);
					} else {
						$sql = "select import_ytd_detail_arr from import_ytd_details where import_ytd_head_id='".$lastInsertedHeadID."' and emp_id='".$emp_id."'";
						$arrYTD = $this->conn->Execute($sql);
						$temp = unserialize($arrYTD->fields['import_ytd_detail_arr']);
						
						$arrData['ytd_summary'] = array(
								"basicSalaryRate" => $basicSalaryRate
								,"basicPGRate" => $basicPGRate
								,"COLA" => $COLA
								,"OTAmount" => $OTAmount
								,"LeaveDedAmount" => $LeaveDedAmount
								,"grossPay" => $grossPay
								,"otherStatIncome" => $otherStatIncome
								,"otherStatTaxIncome" => $otherStatTaxIncome
								,"otherStatTaxDed" => $otherStatTaxDed
								,"statIncome" => $statIncome
								,"otherTaxIncome" => $otherTaxIncome
								,"otherTaxDed" => $otherTaxDed
								,"taxableIncomeGross" => $taxableIncomeGross
								,"nonTaxIncome" => $nonTaxIncome
								,"otherDeduction" => $otherDeduction
								,"netPay" => $netPay
								,"tax" => $tax
								,"PHICWage" => $PHICWage
								,"PHICee" => $PHICee
								,"PHICer" => $PHICer
								,"SSSWage" => $SSSWage
								,"SSSee" => $SSSee
								,"SSSer" => $SSSer
								,"SSSec" => $SSSec
								,"HDMFWage" => $HDMFWage
								,"HDMFee" => $HDMFee
								,"HDMFer" => $HDMFer
							);
						$temp['ytd_summary'] = $arrData['ytd_summary'] + $temp['ytd_summary'];
//						$updateArr = array_merge(unserialize($arrYTD->fields['import_ytd_detail_arr']),$arrData);
						$flds2[] = "import_ytd_detail_updatewho='".AppUser::getData('user_name')."'";
						$flds2[] = "import_ytd_detail_updatewhen=now()";
						$flds2[] = "import_ytd_detail_arr='".serialize($temp)."'";
						$fieldsDetails = implode(", ",$flds2);
						
						$sqlDetails = "UPDATE import_ytd_details SET $fieldsDetails where import_ytd_head_id='".$lastInsertedHeadID."' and emp_id='".$emp_id."'";
						$this->conn->Execute($sqlDetails);
					}
				}
			}
		}
		$_SESSION['eMsg'] = "YTD Successfully Uploaded!";
	}
	
	function checkIfPPExists($ppid_ = null){
		$sql = "select import_ytd_head_id from import_ytd_head where payperiod_id='$ppid_'";
		$r = $this->conn->Execute($sql);
		if(!$r->EOF){
			return $r->fields['import_ytd_head_id'];
		} else {
			return 0;
		}
	}
	
	function getEmpId($empid_num_ = null){
		$sql = "select emp_id from emp_masterfile where emp_idnum='$empid_num_'";
		$r = $this->conn->Execute($sql);
		if(!$r->EOF){
			return $r->fields['emp_id'];
		} else {
			return false;
		}
	}
	
	function checkIfEmpExists($emp_id_ = null, $ytdhead_id_ = null){
		$sql = "select * from import_ytd_details where emp_id='$emp_id_' and import_ytd_head_id='$ytdhead_id_'";
		$r = $this->conn->Execute($sql);
		if(!$r->EOF){
			return false;
		} else {
			return true;
		}
	}
	
	function validateIfBelongToPPS($emp_id_ = null, $pps_id_ = null){
		$sql = "select 1 from payroll_pps_user where emp_id='$emp_id_' and pps_id='$pps_id_'";
		$r = $this->conn->Execute($sql);
		if(!$r->EOF){
			return true;
		} else {
			return false;
		}
	}
	
	function doPESaveImport($pData_ = array(), $gData = array()){
		if (count($pData_) == 0) {
			return NULL;
		}
		$badQtyCtr = 0;
		$rowPos = 6;
		$rowCnt = $this->xlsData[0]['numRows'];
		for ($rowPos = 6; $rowPos <= $rowCnt; $rowPos++) {
			$arrData = array();
			$cellDataArr = $this->xlsData[0]['cells'][$rowPos];
			
			//variable assigning
			$pe_type = trim($cellDataArr[1]);
			$pe_name = trim($cellDataArr[2]);
			$emp_idnum = trim($cellDataArr[3]);
			$amt = trim($cellDataArr[5]);
			
			if(empty($pe_type) && empty($pe_name) && empty($emp_idnum) && empty($amt)){ 
				$skipped_row++;
				//continue; 
			} else {
				$emp_id = $this->getEmpId($emp_idnum);
				$pe_id = $this->getPEid($pe_name);
				$flds = array();
				if($this->checkIfPPExists($gData['ppid']) == 0){
					$flds[] = "import_ytd_head_addwho='".AppUser::getData('user_name')."'";
					$flds[] = "payperiod_id='".$gData['ppid']."'";
					$fields = implode(", ",$flds);
					$sqlHead = "INSERT INTO import_ytd_head SET $fields";
					$this->conn->Execute($sqlHead);
					$lastInsertedHeadID = $this->conn->Insert_ID();
				} else {
					$lastInsertedHeadID = $this->checkIfPPExists($gData['ppid']);
				}
				if($this->validateIfBelongToPPS($emp_id, $gData['ppsid'])){
					$flds2 = array();
					if($this->checkIfEmpExists($emp_id, $lastInsertedHeadID)){
						$empinfo = "SELECT *,txcep.taxep_name,a.emp_id,a.comp_id, CONCAT(g.pi_fname,' ',UPPER(SUBSTRING(g.pi_mname,1,1)),'. ',g.pi_lname) as fullname, bank.bankiemp_acct_no, blist.banklist_name, DATE_FORMAT(f.payperiod_trans_date,'%d') as ppdTransDate, DATE_FORMAT(f.payperiod_start_date,'%d') as ppdStartDate 
								FROM emp_masterfile a
								JOIN salary_info b on (b.emp_id=a.emp_id)
								JOIN payroll_pps_user c on (c.emp_id = a.emp_id)
								JOIN payroll_pay_period_sched d on (d.pps_id=c.pps_id)
								JOIN payroll_comp pcal on (a.emp_id=pcal.emp_id)
								JOIN factor_rate e on (e.fr_id=pcal.fr_id)
								LEFT JOIN app_wagerate wrate on (wrate.wrate_id=e.wrate_id)
								JOIN payroll_pay_period f on (f.pps_id=d.pps_id)
								JOIN emp_personal_info g on (g.pi_id=a.pi_id)
								JOIN company_info i on (i.comp_id=a.comp_id)
								LEFT JOIN emp_position h on (h.post_id=a.post_id)
								LEFT JOIN app_userdept j on (j.ud_id=a.ud_id)
								LEFT JOIN emp_type z on (z.emptype_id=a.emptype_id)
								LEFT JOIN bank_infoemp bank on (bank.emp_id=a.emp_id)
								LEFT JOIN bank_list blist on blist.banklist_id=bank.banklist_id
								LEFT JOIN tax_excep txcep on (txcep.taxep_id=a.taxep_id) 
								WHERE a.emp_id='".$emp_id."' and c.pps_id='".$gData['ppsid']."' and f.payperiod_id='".$gData['ppid']."' and b.salaryinfo_isactive='1'";
						$rsResult = $this->conn->Execute($empinfo);
						
						$arrData['empinfo'] = array(
								 "emp_id" => $rsResult->fields['emp_id']
								,"emp_no" => $rsResult->fields['emp_idnum']
								,"fullname" => $rsResult->fields['fullname']
								,"jobcode" => $rsResult->fields['post_code']
								,"jobpos_name" => $rsResult->fields['post_name']
								,"comp_name" => $rsResult->fields['comp_name']
								,"comp_add" => $rsResult->fields['comp_add']
								,"ud_name" => $rsResult->fields['ud_name']
								,"tax_ex_name" => $rsResult->fields['taxep_name']
								,"comp_id" => $rsResult->fields['comp_id']
								,"ud_id" => $rsResult->fields['ud_id']
								,"emptype_name" => $rsResult->fields['emptype_name']
								,"bankiemp_acct_no" => $rsResult->fields['bankiemp_acct_no']
								,"banklist_name" => $rsResult->fields['banklist_name']
								,"pi_emailone" => $rsResult->fields['pi_emailone']
								,"taxep_id" => $rsResult->fields['taxep_id']
								,"salaryclass_id" => $rsResult->fields['salaryclass_id']
								,"tt_pay_group" => $rsResult->fields['tt_pay_group']
								,"ud_code" => $rsResult->fields['ud_code']
								,"taxep_code" => $rsResult->fields['taxep_code']
								,"salarytype_id" => $rsResult->fields['salarytype_id']
							);
						// for ytd summary
						$arrData['ytd_summary'] = array();
						// for earnings and deductions array 
						$arrData['Earnings'] = array();
						$arrData['Deductions'] = array();
						
						if($pe_type == "Earning") {
						$arrData['Earnings'][0] = array(
								'psa_id' => $pe_id,
								'psa_type' => 1,
								'psa_name' => $pe_name,
								'ben_payperday' => $amt
							);
						} 
						if($pe_type == "Deduction"){
							$arrData['Deductions'][0] = array(
								'psa_id' => $pe_id,
								'psa_type' => 2,
								'psa_name' => $pe_name,
								'ben_payperday' => $amt
							);
						}
						$flds2[] = "import_ytd_detail_addwho='".AppUser::getData('user_name')."'";
						$flds2[] = "import_ytd_detail_addwhen=now()";
						$flds2[] = "import_ytd_head_id='".$lastInsertedHeadID."'";
						$flds2[] = "emp_id='".$emp_id."'";
						$flds2[] = "import_ytd_detail_arr='".serialize($arrData)."'";
						$fieldsDetails = implode(", ",$flds2);
						$sqlDetails = "INSERT INTO import_ytd_details SET $fieldsDetails";
						$this->conn->Execute($sqlDetails);
					} else {
						$sql = "select import_ytd_detail_arr from import_ytd_details where import_ytd_head_id='".$lastInsertedHeadID."' and emp_id='".$emp_id."'";
						$arrYTD = $this->conn->Execute($sql);
						$temp = unserialize($arrYTD->fields['import_ytd_detail_arr']);
						if($pe_type == "Earning") {
							$arrData['Earnings'][count($arrData['Earnings'])] = array(
								'psa_id' => $pe_id,
								'psa_type' => 1,
								'psa_name' => $pe_name,
								'ben_payperday' => $amt
							);
							$temp['Earnings'] = array_merge($temp['Earnings'],$arrData['Earnings']);
						}
						if($pe_type == "Deduction"){
							$arrData['Deductions'][count($arrData['Deductions'])] = array(
								'psa_id' => $pe_id,
								'psa_type' => 2,
								'psa_name' => $pe_name,
								'ben_payperday' => $amt
							);
							$temp['Deductions'] = array_merge($temp['Deductions'],$arrData['Deductions']);
						}
						$flds2[] = "import_ytd_detail_updatewho='".AppUser::getData('user_name')."'";
						$flds2[] = "import_ytd_detail_updatewhen=now()";
						$flds2[] = "import_ytd_detail_arr='".serialize($temp)."'";
						$fieldsDetails = implode(", ",$flds2);
						
						$sqlDetails = "UPDATE import_ytd_details SET $fieldsDetails where import_ytd_head_id='".$lastInsertedHeadID."' and emp_id='".$emp_id."'";
						$this->conn->Execute($sqlDetails);
					}
				}
			}
		}
		$_SESSION['eMsg'] = "YTD Successfully Uploaded!";
	}
	
	function getImportInfo($gData = array()){
		if (is_null($gData['ppid'])) { return ""; }
		$ppsid = $gData['ppsid'];
		$ppid = $gData['ppid'];
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
				$qry[] = "uptahead_desc like '%$search_field%'";
			}
		} 
//        $time = date('Y-m-d h:i:s',dDate::parseDateTime(dDate::getTime()));
//		$qry[] = "ppp.payperiod_end_date < '".$time."'";
		$qry[] = "a.payperiod_id = '".$ppid."'";
		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		 "viewdata" => "viewdata"
		,"pps_name" => "pps_name"
		,"from_date" => "from_date"
		,"to_date" => "to_date"
		);

		if(isset($_GET['sortby'])){
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}else{
			$strOrderBy = " order by a.payperiod_id DESC";
		}
		
		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
//		$viewLink = "<a href=\"?statpos=ytd_import&ppsched=',psar.pps_id,'&ppsched_view=',ppp.payperiod_id,'\">',psar.pps_name,'</a>";
		$editLink = "<a href=\"?statpos=ytd_import&edit&ytdhead=',a.import_ytd_head_id,'&ppsid=$ppsid&ppid=$ppid\"><img src=\"".SYSCONFIG_THEME_URLPATH.SYSCONFIG_THEME."/images/admin/zoom.gif\" title=\"View Details\" hspace=\"2px\" border=0></a>";
		$delLink = "<a href=\"?statpos=ytd_import&delete&ytdhead=',a.import_ytd_head_id,'&ppsid=$ppsid&ppid=$ppid\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\"  border=0 width=\"16\" height=\"16\"></a>";

		// SqlAll Query
		$sql = "select a.*,date_format(a.import_ytd_head_addwhen,'".APPCONFIG_FORMAT_DATETIME_SQL."') as import_ytd_date, CONCAT('$viewLink','$editLink','$delLink') as viewdata,
						date_format(b.payperiod_start_date,'%M %d %Y') as from_date,
						date_format(b.payperiod_end_date,'%M %d %Y') as to_date,
						c.pps_name
						from import_ytd_head a
						JOIN payroll_pay_period b ON (b.payperiod_id=a.payperiod_id)
						JOIN payroll_pay_period_sched c ON (c.pps_id=b.pps_id)
						$criteria
						$strOrderBy";

		// Field and Table Header Mapping
		$arrFields = array(
		 "viewdata" => "Action"
		,"pps_name"=>"Name"
		,"from_date" => "From Date"
		,"to_date" => "To Date"
		);

		// Column (table data) User Defined Attributes
		$arrAttribs = array(
		 "mnu_ord"=>" align='right'"
		,"pps_name"=>"width='150'"
		,"salaryclass_name"=>"width='120'"
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
	
	function doDownloadTemplate(){
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();	
		$sheet = $objPHPExcel->getActiveSheet();
		//$sheet->getProtection()->setSheet(true);
		$filename = "YTDPayElementTemplate.xls";
	    // Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		$objReader = PHPExcel_IOFactory::createReader('Excel5');
		$objPHPExcel = $objReader->load("importtemplate/YTDPayElementTemplate.xls");
		
		$objPHPExcel->createSheet();
		$objPHPExcel->setActiveSheetIndex(1);
		$objPHPExcel->getActiveSheet()->setTitle("PayElements");
		$payElements = $this->getPayElements();
		$row=2;
		foreach($payElements as $pe){
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $pe);
			$row++;
		}
	    $objPHPExcel->setActiveSheetIndex(0);
		$objValidation = $objPHPExcel->getActiveSheet()->getCell('B6')->getDataValidation();
		$objValidation->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
		$objValidation->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
		$objValidation->setAllowBlank(false);
		$objValidation->setShowInputMessage(true);
		$objValidation->setShowErrorMessage(true);
		$objValidation->setShowDropDown(true);
		//$objValidation->setPrompt('Please pick a pay element from the drop-down list.');
		
		$objValidation->setFormula1('PayElements!$A$2:$A$'.$row);
		for($i=6;$i<=1000;$i++){
			$objPHPExcel->getActiveSheet()->getCell('B'.$i)->setDataValidation($objValidation);
			
		}
		$sheet->setTitle($filename);
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);
		// Redirect output to a client's web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename='.$filename);
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
	    exit;
	}
	
	function getPayElements(){
		$arr = array();
		$sql = "select psa_name from payroll_ps_account where psa_type in (1,2) and psa_clsfication!=4 order by psa_type, psa_name";
		$r = $this->conn->Execute($sql);
		while(!$r->EOF){
			$arr[] = $r->fields['psa_name'];
			$r->MoveNext();
		}
		return $arr;
	}

	function getYTDDetails($ytd_head = null){
		$sql ="select * import_ytd_details where import_ytd_head_id = '$ytd_head'";
		$r = $this->conn->Execute($sql);
		if(!$r->EOF){
			return $r->fields;
		} else {
			return 0;
		}
	}
	
	function getEmployeeYTD($gData = array()){
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
				$qry[] = "pi_lname like '%$search_field%' || pi_fname like '%$search_field%'";
			}
		} 
//        $time = date('Y-m-d h:i:s',dDate::parseDateTime(dDate::getTime()));
//		$qry[] = "ppp.payperiod_end_date < '".$time."'";
		$qry[] = "import_ytd_head_id = '".$gData['ytdhead']."'";
		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		 "viewdata" => "viewdata"
		,"emp_idnum" => "emp_idnum"
		,"pi_lname" => "pi_lname"
		,"pi_fname" => "pi_fname"
		,"post_name" => "post_name"
		);

		if(isset($_GET['sortby'])){
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}else{
			$strOrderBy = " order by pi_lname ASC";
		}
		$ppsid = $gData['ppsid'];
		$ppid = $gData['ppid'];
		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$viewLink = "<a href=\"?statpos=ytd_import&viewdetails&ytdhead=',a.import_ytd_head_id,'&emp_id=',a.emp_id,'&ppsid=$ppsid&ppid=$ppid\"><img src=\"".SYSCONFIG_THEME_URLPATH.SYSCONFIG_THEME."/images/admin/zoom.gif\" title=\"View Details\" hspace=\"2px\" border=0></a>";
	//	$editLink = "<a href=\"?statpos=ytd_import&edit&ytdhead=',a.import_ytd_head_id,'&ppsid=$ppsid&ppid=$ppid\"><img src=\"".SYSCONFIG_THEME_URLPATH.SYSCONFIG_THEME."/images/admin/zoom.gif\" title=\"View Details\" hspace=\"2px\" border=0></a>";
		$delLink = "<a href=\"?statpos=ytd_import&deleteEmp&ytdhead=',a.import_ytd_head_id,'&emp_id=',a.emp_id,'&ppsid=$ppsid&ppid=$ppid\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\"  border=0 width=\"16\" height=\"16\"></a>";

		// SqlAll Query
		$sql = "select emp_idnum, pi_lname, pi_fname, post_name, CONCAT('$viewLink','$editLink','$delLink') as viewdata
				from import_ytd_details a
				JOIN emp_masterfile b on (b.emp_id=a.emp_id)
				JOIN emp_personal_info c on (c.pi_id=b.pi_id)
				LEFT JOIN emp_position d on (d.post_id=b.post_id)
						$criteria
						$strOrderBy";

		// Field and Table Header Mapping
		$arrFields = array(
		 "viewdata" => "Action"
		,"emp_idnum" => "Emp No."
		,"pi_lname" => "Last Name"
		,"pi_fname" => "First Name"
		,"post_name" => "Position"
		);

		// Column (table data) User Defined Attributes
		$arrAttribs = array(
		 "mnu_ord"=>" align='right'"
		,"emp_idnum"=>"width='150'"
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
	
	function getPPDetails($ppid_ = null){
		$sql ="select b.pps_name, DATE_FORMAT(a.payperiod_start_date, '%M %d, %Y') as pp_start, DATE_FORMAT(a.payperiod_end_date,'%M %d, %Y') as pp_end
				from payroll_pay_period a
				join payroll_pay_period_sched b on (b.pps_id=a.pps_id)
				where payperiod_id='$ppid_'";
		$rsResult = $this->conn->Execute($sql);
		if(!$rsResult->EOF){
			return $rsResult->fields;
		}
	}
	
	function getPEid($pe_name = null){
		$sql = "select psa_id from payroll_ps_account where psa_name='$pe_name'";
		$rsResult = $this->conn->Execute($sql);
		if(!$rsResult->EOF){
			return $rsResult->fields['psa_id'];
		}
	}
}

?>