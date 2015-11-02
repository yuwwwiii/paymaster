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
class clsTime_Attend {

	var $conn;
	var $fieldMap;
	var $Data;

	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsTime_Attend object
	 */
	function clsTime_Attend($dbconn_ = null) {
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
	function dbFetch($payperiod_id_ = "", $pps_id_ = "") {
		$sql = "SELECT ppp.*, psar.pps_name,
				DATE_FORMAT(payperiod_start_date,'%d-%b-%y %h:%i %p') as payperiod_start_date,
				DATE_FORMAT(payperiod_end_date,'%d-%b-%y %h:%i %p') as payperiod_end_date,
				DATE_FORMAT(payperiod_trans_date,'%d-%b-%y %h:%i %p') as payperiod_trans_date,
				IF(pp_stat_id='1','OPEN',IF(pp_stat_id='2','Locked - Pending Approval',IF(pp_stat_id='3','CLOSED','Post Adjustment'))) as pp_stat_id,
				if(salaryclass_id='1','Daily',IF(salaryclass_id='2','Weekly',IF(salaryclass_id='3','Bi-Weekly',IF(salaryclass_id='4','Semi-monthly',IF(salaryclass_id='5','Monthly','Annual'))))) as salaryclass_id
					FROM payroll_pay_period ppp
					JOIN payroll_pay_period_sched psar on (psar.pps_id=ppp.pps_id)
					WHERE ppp.payperiod_id = '".$payperiod_id_."'";
		$rsResult = $this->conn->Execute($sql);
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
	function doPopulateData($pData_ = array(),$isForm_ = false) {
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
	function doValidateData($pData_ = array()) {
		$isValid = true;

//		$isValid = false;

		return $isValid;
	}
	
	function doValidateDataUPTA($pData_ = array()) {
		$isValid = true;
		// File Extension Validation
		if(isset($pData_['uptahead_file']['tmp_name']) && !empty($pData_['uptahead_file']['tmp_name'])){
		    $finfo = finfo_open(FILEINFO_MIME_TYPE);
		    $mime=finfo_file($finfo, $_FILES['uptahead_file']['tmp_name']);
		    finfo_close($finfo);
		}
		// File Extension Validation
		if (!$pData_['uptahead_file']['type']) {
			$_SESSION['eMsg'][] = "Please Choose File.";
			$isValid = false;
			return $isValid;
		} elseif ($mime != 'application/vnd.ms-excel' && $pData_['uptahead_file']['type'] != 'application/vnd.ms-excel') {
			$_SESSION['eMsg'][] = "Invalid File Type. The File Extension should only be .xls";
			$isValid = false;
			return $isValid;
		}
		$this->xlsData = $this->doReadAndInsertUPTAFile($pData_['uptahead_file']['tmp_name']);
		if ($this->xlsData[0]['numRows'] < 9) {
			$_SESSION['eMsg'][] = "Invalid record count. Data is less than 9 rows.";
			$isValid = false;
		}
		return $isValid;
	}
	
	function doReadAndInsertUPTAFile($fname_ = NULL) {
		if (is_null($fname_)) {
			return NULL;
		}
		$objClsExcelReader = new Spreadsheet_Excel_Reader();
		$objClsExcelReader->read($fname_);
		return $objClsExcelReader->sheets;
	}

	/**
	 * Save New
	 *
	 */
	function doSaveAdd() {
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
	function doSaveEdit() {
		$id = $_GET['edit'];

		$flds = array();
		foreach ($this->Data as $keyData => $valData) {
			$valData = addslashes($valData);
			$flds[] = "$keyData='$valData'";
		}
		$fields = implode(", ",$flds);

		$sql = "UPDATE /*app_modules*/ SET $fields WHERE mnu_id=$id";
		$this->conn->Execute($sql);
		$_SESSION['eMsg']="Successfully Updated.";
	}

	/**
	 * Delete Record
	 *
	 * @param string $id_
	 */
	function doDelete($id_ = "") {
		$sql = "DELETE FROM /*app_modules*/ WHERE mnu_id=?";
		$this->conn->Execute($sql,array($id_));
		$_SESSION['eMsg']="Successfully Deleted.";
	}
	
	function doDeleteUploadTAHead($id_ = "",$get_ = "") {
		//delete all TA record:
		$sql2 = "DELETE FROM ta_emp_rec WHERE payperiod_id = '".$get_['ppsched_view']."'";
		$this->conn->Execute($sql2);
		//delete all OT record:
		$sql3 = "DELETE FROM ot_record WHERE payperiod_id = '".$get_['ppsched_view']."'";
		$this->conn->Execute($sql3);
		//delete all Custom record:
		$sql3 = "DELETE FROM cf_detail WHERE payperiod_id = '".$get_['ppsched_view']."'";
		$this->conn->Execute($sql3);
		//delete all Leave record:
		$sql3 = "DELETE FROM emp_leave_rec WHERE payperiod_id = '".$get_['ppsched_view']."'";
		$this->conn->Execute($sql3);
		//deletes upload TA details
		$sql_ = "DELETE FROM tks_uploadta_details WHERE uptahead_id=?";
		$this->conn->Execute($sql_);
		//delete upload TA Head
		$sql = "DELETE FROM tks_uploadta_header WHERE uptahead_id=?";
		$this->conn->Execute($sql,array($id_));
		$_SESSION['eMsg']="Successfully Deleted.";
	}
	
	function doDeleteUploadTADetail($id_ = "") {
		$sql = "DELETE FROM tks_uploadta_details WHERE uptadet_id=?";
		$this->conn->Execute($sql,array($id_));
		$_SESSION['eMsg']="Successfully Deleted.";
	}

	/**
	 * Get all the Table Listings
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
			if (strlen($_REQUEST['search_field'])>0) {
				// lets assign the request value in a variable
				$search_field = $_REQUEST['search_field'];

				// create a custom criteria in an array
				$qry[] = "pps_name like '%$search_field%' || salaryclass_name like '%$search_field%";
			}
		}
		$time = date('Y-m-d h:i:s',dDate::parseDateTime(dDate::getTime()));
//		$qry[] = "ppp.payperiod_trans_date > '".$time."'";
		$listpgroup = $_SESSION[admin_session_obj][user_paygroup_list2];
		IF(count($listpgroup)>0){
			$qry[] = "psar.pps_id in (".$listpgroup.")";//pay group that can access
		}		
		$qry[] = "ppp.pp_stat_id in (1,2)";
		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" WHERE ".implode(" AND ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		 "viewdata" => "viewdata"
		,"salaryclass_id" => "salaryclass_name"
		,"pp_stat_id" => "stat_name"
		,"payperiod_start_date" => "payperiod_start_date"
		,"payperiod_end_date" => "payperiod_end_date"
		,"payperiod_trans_date" => "payperiod_trans_date"
		);

		IF (isset($_GET['sortby'])) {
			$strOrderBy = " ORDER BY ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		} ELSE {
			$strOrderBy = " ORDER BY ppp.payperiod_id DESC";
		}
		
		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$viewLink = "<a href=\"?statpos=time_attend&ppsched=',psar.pps_id,'&ppsched_view=',ppp.payperiod_id,'\">',psar.pps_name,'</a>";
//		$editLink = "<a href=\"?statpos=endpayperiod&ppsched=',psar.pps_id,'&ppsched_edit=',ppp.payperiod_id,'\"><img src=\"".SYSCONFIG_THEME_URLPATH.SYSCONFIG_THEME."/images/admin/edit.gif\" title=\"Edit\" hspace=\"2px\" border=0></a>";
//		$delLink = "<a href=\"?statpos=endpayperiod&ppsched=',psar.pps_id,'&ppsched_del=',ppp.payperiod_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_THEME_URLPATH.SYSCONFIG_THEME."/images/admin/trash.gif\" title=\"Delete\" hspace=\"2px\"  border=0></a>";

		// SqlAll Query
		$sql = "SELECT CONCAT('$viewLink') as viewdata, 
				DATE_FORMAT(payperiod_start_date,'%d %b %Y %h:%i %p') as payperiod_start_date,
				DATE_FORMAT(payperiod_end_date,'%d %b %Y %h:%i %p') as payperiod_end_date,
				DATE_FORMAT(payperiod_trans_date,'%d %b %Y') as payperiod_trans_date,
				if(salaryclass_id='1','Daily',IF(salaryclass_id='2','Weekly',IF(salaryclass_id='3','Bi-Weekly',IF(salaryclass_id='4','Semi-monthly',IF(salaryclass_id='5','Monthly','Annual'))))) as salaryclass_id,
				IF(pp_stat_id='1','OPEN',IF(pp_stat_id='2','Locked - Pending Approval',IF(pp_stat_id='3','CLOSED','Post Adjustment'))) as pp_stat_id
						FROM payroll_pay_period ppp
						JOIN payroll_pay_period_sched psar on (psar.pps_id=ppp.pps_id)
						$criteria
						$strOrderBy";

		// Field and Table Header Mapping
		$arrFields = array(
		 "viewdata" => "Name"
		,"salaryclass_id" => "Type"
		,"pp_stat_id" => "Status"
		,"payperiod_start_date" => "Start"
		,"payperiod_end_date" => "End"
		,"payperiod_trans_date" => "Pay Date"
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
	
	/**
	 * @author jim
	 * @note: Normal TA Summary
	 * @param unknown_type $pData_
	 */
	function doSaveUploadTAHead($pData_ = array()){
		IF (count($pData_) == 0) { return null; }
		$flds =  array();
		$flds[] = "uptahead_desc='".$pData_['uptahead_desc']."'"; 
		$flds[] = "uptahead_addwho='".AppUser::getData('user_name')."'"; 
		$flds[] = "uptahead_status='new'";
		$flds[] = "payperiod_id='".$_GET['ppsched_view']."'";
		$fields = implode(", ",$flds);
		$sql = "INSERT INTO tks_uploadta_header SET $fields";
		$this->conn->Execute($sql);
		$uptahead_id = $this->conn->Insert_ID();

		$badQtyCtr = 0;
		$rowPos = 9;
		$rowCnt = $this->xlsData[0]['numRows'];
		for ($rowPos = 9;$rowPos <= $rowCnt;$rowPos++){
			$cellDataArr = $this->xlsData[0]['cells'][$rowPos];
			$eMsg = array();
			$isValid = true;
			$emp_id = $this->getEmpIDByEmpNum($cellDataArr[1]);
			IF ($emp_id['emp_id'] == 0) {
				$eMsg[] = "Invalid Employee not included in this Pay Group.";
				$isValid = false;
			}
			$errMsg = "";
			IF (!$isValid) {
				$errMsg = "->".implode(";",$eMsg);
				$badQtyCtr++;
			}
			$flds = array();
			$flds[] = "uptahead_id=$uptahead_id";
			$flds[] = "emp_id='".$emp_id['emp_id']."'";
			$flds[] = "uptadet_empnum='".$cellDataArr[1]."'";
			$flds[] = "uptadet_empname='".$cellDataArr[2]."'";
			$flds[] = "uptadet_desc='".$errMsg."'";
			$flds[] = "uptadet_status='new'";
			$flds[] = "uptadet_isgood=".(($isValid)?1:0);
			$fields = implode(", ",$flds);
			$sql = "INSERT INTO tks_uploadta_details SET $fields";
			$this->conn->Execute($sql);
			$uptadet_id_ = $this->conn->Insert_ID();
			
			IF($emp_id['salarytype_id'] == 2){//this is used for daily employee
				IF($cellDataArr[4]!=0){//for Late
					$this->saveTARecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],3,$uptadet_id_,$cellDataArr[4]);
				}
				IF($cellDataArr[5]!=0){//for U/T
					$this->saveTARecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],4,$uptadet_id_,$cellDataArr[5]);
				}
				IF($cellDataArr[6]!=0){//for Days work
					$vartotalday = $cellDataArr[6] + $cellDataArr[16];
					IF($cellDataArr[12]!=0){ $vartotalday += $cellDataArr[11]; }
					IF($cellDataArr[22]!=0){ $vartotalday += $cellDataArr[21]; }
					IF($cellDataArr[27]!=0){ $vartotalday += $cellDataArr[26]; }
					IF($cellDataArr[32]!=0){ $vartotalday += $cellDataArr[31]; }
					$this->saveTARecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],5,$uptadet_id_,$vartotalday);
				}
				IF($cellDataArr[6]!=0){//for Custom Days
					$vartotalday = $cellDataArr[6] + $cellDataArr[16];
					IF($cellDataArr[12]!=0){ $vartotalday += $cellDataArr[11]; }
					IF($cellDataArr[22]!=0){ $vartotalday += $cellDataArr[21]; }
					IF($cellDataArr[27]!=0){ $vartotalday += $cellDataArr[26]; }
					IF($cellDataArr[32]!=0){ $vartotalday += $cellDataArr[31]; }
					IF($cellDataArr[37]!=0){ $vartotalday += $cellDataArr[36]; }
					$this->saveTARecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],6,$uptadet_id_,$vartotalday);
				}
			}else{//this is used for Monthly employee
				IF($cellDataArr[3]!=0){//for absent
					$this->saveTARecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],1,$uptadet_id_,$cellDataArr[3]);
				}
				IF($cellDataArr[4]!=0){//for late
					$this->saveTARecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],3,$uptadet_id_,$cellDataArr[4]);
				}
				IF($cellDataArr[5]!=0){//for U/T
					$this->saveTARecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],4,$uptadet_id_,$cellDataArr[5]);
				}
			}
			//FOR CUSTOM FIELDS
			//--------------------------------------------------->>
				if($cellDataArr[4]==true){//Tranpo deduction allow
					$vartotalLT = $cellDataArr[4] + $cellDataArr[5];
					$this->saveCustomRD($emp_id['emp_id'],$_GET['ppsched_view'],1,$uptadet_id_,$vartotalLT);
				}
				if($cellDataArr[3]!=0){//Custome Days Abs
					$this->saveCustomRD($emp_id['emp_id'],$_GET['ppsched_view'],2,$uptadet_id_,$cellDataArr[3]);
				}
			//---------------------------------------------------<<	
			//FOR OT RECORD
			//--------------------------------------------------->>
			IF($this->validateOTAssigned($emp_id['emp_id'])){
				//-------------REG TIME------------->>
				IF($cellDataArr[8]!=0){//REGOT
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],26,$uptadet_id_,$cellDataArr[8]);
				}
				IF($cellDataArr[9]!=0){//NDF8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],13,$uptadet_id_,$cellDataArr[9]);
				}
				IF($cellDataArr[10]!=0){//NDX8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],14,$uptadet_id_,$cellDataArr[10]);
				}
				//---------------END----------------<<
				//-----------RESTDAY OT------------->>
				IF($cellDataArr[12]!=0){//OT_RDF8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],15,$uptadet_id_,$cellDataArr[12]);
				}
				IF($cellDataArr[13]!=0){//OT_RD88
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],16,$uptadet_id_,$cellDataArr[13]);
				}
				IF($cellDataArr[14]!=0){//ND_RDF8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],2,$uptadet_id_,$cellDataArr[14]);
				}
				IF($cellDataArr[15]!=0){//ND_RDX8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],3,$uptadet_id_,$cellDataArr[15]);
				}
				//---------------END----------------<<
				//------------HOLIDAY OT------------>>
				IF($cellDataArr[17]!=0){//Holiday
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],1,$uptadet_id_,$cellDataArr[17]);
				}
				IF($cellDataArr[18]!=0){//OT_RHX8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],20,$uptadet_id_,$cellDataArr[18]);
				}
				IF($cellDataArr[19]!=0){//ND_RHF8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],4,$uptadet_id_,$cellDataArr[19]);
				}
				IF($cellDataArr[20]!=0){//ND_RHX8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],7,$uptadet_id_,$cellDataArr[20]);
				}
				//---------------END----------------<<
				//-------Special Holiday OT--------->>
				if($emp_id['salarytype_id'] == 2){//this is used for daily employee
					if($cellDataArr[22]!=0){//OT_SHF8
						$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],21,$uptadet_id_,$cellDataArr[22]);
					}
					if($cellDataArr[24]!=0){//ND_SHF8
						$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],8,$uptadet_id_,$cellDataArr[24]);
					}
				}else{//this is used for monthly employee
					if($cellDataArr[22]!=0){//OT_SHF8M
						$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],22,$uptadet_id_,$cellDataArr[22]);
					}
					if($cellDataArr[24]!=0){//ND_SHF8
						$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],9,$uptadet_id_,$cellDataArr[24]);
					}
				}
				if($cellDataArr[23]!=0){//OT_SHX8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],25,$uptadet_id_,$cellDataArr[23]);
				}
				if($cellDataArr[25]!=0){//ND_SHX8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],12,$uptadet_id_,$cellDataArr[25]);
				}	
				//---------------END----------------<<
				//--------Holiday Restday OT--------<<
				if($cellDataArr[27]!=0){//OT_RHRDF8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],18,$uptadet_id_,$cellDataArr[27]);
				}
				if($cellDataArr[28]!=0){//OT_RHRDX8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],19,$uptadet_id_,$cellDataArr[28]);
				}
				if($cellDataArr[29]!=0){//ND_RHRDF8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],5,$uptadet_id_,$cellDataArr[29]);
				}
				if($cellDataArr[30]!=0){//ND_RHRDX8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],6,$uptadet_id_,$cellDataArr[30]);
				}
				//---------------END----------------<<
				//----Special Holiday Restday OT---->>
				if($cellDataArr[32]!=0){//OT_SHRDF8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],23,$uptadet_id_,$cellDataArr[32]);
				}
				if($cellDataArr[33]!=0){//OT_SHRDF8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],24,$uptadet_id_,$cellDataArr[33]);
				}
				if($cellDataArr[34]!=0){//ND_SHRDF8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],10,$uptadet_id_,$cellDataArr[34]);
				}
				if($cellDataArr[35]!=0){//ND_SHRDX8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],11,$uptadet_id_,$cellDataArr[35]);
				}
				//---------------END----------------<<
			}	
			//-----------------------END-------------------------<<
			//FOR LEAVE RECORD
			//--------------------------------------------------->>
				if($cellDataArr[47]==true){//SL
					$empleave_id = $this->getLeaveID($emp_id['emp_id'],2);
					$var_ = $empleave_id['empleave_available_day'] - $cellDataArr[36];
					$flds_ = array();
					$flds_[] = "empleave_used_day='".$cellDataArr[36]."'";
					$flds_[] = "empleave_available_day='".$cellDataArr[47]."'";
					$fields_ = implode(", ",$flds_);
					$sql_ = "update emp_leave set $fields_ where empleave_id = '".$empleave_id['empleave_id']."'";
					$this->conn->Execute($sql_);
				}
				if($cellDataArr[48]==true){//VL
					$empleave_id = $this->getLeaveID($emp_id['emp_id'],1);
					$var_ = $empleave_id['empleave_available_day'] - $cellDataArr[37];
					$flds_ = array();
					$flds_[] = "empleave_used_day='".$cellDataArr[37]."'";
					$flds_[] = "empleave_available_day='".$cellDataArr[48]."'";
					$fields_ = implode(", ",$flds_);
					$sql_ = "update emp_leave set $fields_ where empleave_id = '".$empleave_id['empleave_id']."'";
					$this->conn->Execute($sql_);
				}
			//-----------------------END-------------------------<<
		}
		$sql = "UPDATE tks_uploadta_header SET uptahead_goodqty=".(($rowPos-9)-$badQtyCtr).", uptahead_badqty=$badQtyCtr WHERE uptahead_id=$uptahead_id";
		$this->conn->Execute($sql);
		$_SESSION['eMsg'] = "Successfully Uploaded with $badQtyCtr bad qty of ".($rowPos-9)." record data.";
		return $uptahead_id;
	}
	
	/**
	 * @author jim(20120925)
	 * @comp: FEAP TA SUMMARY
	 * @param unknown_type $pData_
	 */
	function doSaveUploadTAHead_FEAP($pData_ = array()){
		if (count($pData_) == 0) {
			return null;
		}
		$flds =  array();
		$flds[] = "uptahead_desc='".$pData_['uptahead_desc']."'"; 
		$flds[] = "uptahead_addwho='".AppUser::getData('user_name')."'"; 
		$flds[] = "uptahead_status='new'";
		$flds[] = "payperiod_id='".$_GET['ppsched_view']."'";
		$fields = implode(", ",$flds);
		$sql = "INSERT INTO tks_uploadta_header SET $fields";
		$this->conn->Execute($sql);
		$uptahead_id = $this->conn->Insert_ID();

		$badQtyCtr = 0;
		$rowPos = 9;
		$rowCnt = $this->xlsData[0]['numRows'];
		for ($rowPos = 9;$rowPos <= $rowCnt;$rowPos++){
			$cellDataArr = $this->xlsData[0]['cells'][$rowPos];
			$eMsg = array();
			$isValid = true;
			$emp_id = $this->getEmpIDByEmpNum($cellDataArr[1]);
			if ($emp_id['emp_id'] == 0) {
				$eMsg[] = "Invalid Employee not included in this Pay Group.";
				$isValid = false;
			}
			$errMsg = "";
			if (!$isValid) {
				$errMsg = "->".implode(";",$eMsg);
				$badQtyCtr++;
			}
			$flds = array();
			$flds[] = "uptahead_id=$uptahead_id";
			$flds[] = "emp_id='".$emp_id['emp_id']."'";
			$flds[] = "uptadet_empnum='".$cellDataArr[1]."'";
			$flds[] = "uptadet_empname='".$cellDataArr[2]."'";
			$flds[] = "uptadet_desc='".$errMsg."'";
			$flds[] = "uptadet_status='new'";
			$flds[] = "uptadet_isgood=".(($isValid)?1:0);
			$fields = implode(", ",$flds);
			$sql = "INSERT INTO tks_uploadta_details SET $fields";
			$this->conn->Execute($sql);
			$uptadet_id_ = $this->conn->Insert_ID();
			
			if($emp_id['salarytype_id'] == 2){//this is used for daily employee
				if($cellDataArr[6]!=0){//for Late
					$this->saveTARecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],3,$uptadet_id_,$cellDataArr[6]);
				}
				if($cellDataArr[7]!=0){//for U/T
					$this->saveTARecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],4,$uptadet_id_,$cellDataArr[7]);
				}
				if($cellDataArr[8]!=0){//for Days work
					$this->saveTARecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],5,$uptadet_id_,$cellDataArr[8]);
				}
			}else{//this is used for Monthly employee
				if($cellDataArr[4]!=0){//for absent
					$this->saveTARecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],2,$uptadet_id_,$cellDataArr[4]);
				}
				if($cellDataArr[5]!=0){//for NPL
					$this->saveTARecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],1,$uptadet_id_,$cellDataArr[5]);
				}
				if($cellDataArr[6]!=0){//for Late
					$this->saveTARecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],3,$uptadet_id_,$cellDataArr[6]);
				}
				if($cellDataArr[7]!=0){//for U/T
					$this->saveTARecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],4,$uptadet_id_,$cellDataArr[7]);
				}
			}
			//FOR CUSTOM FIELDS
			//--------------------------------------------------->>
				if($cellDataArr[3]==true){//OT MEAL
					$this->saveCustomRD($emp_id['emp_id'],$_GET['ppsched_view'],1,$uptadet_id_,$cellDataArr[3]);
				}
				if($cellDataArr[11]!=0){//Night Diff
					$this->saveCustomRD($emp_id['emp_id'],$_GET['ppsched_view'],2,$uptadet_id_,$cellDataArr[11]);
				}
				if($cellDataArr[50]!=0){//Custome Days
					$this->saveTARecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],6,$uptadet_id_,$cellDataArr[50]);
				}
			//---------------------------------------------------<<	
			//FOR OT RECORD
			//--------------------------------------------------->>
				//-------------REG TIME------------->>
				IF($cellDataArr[10]!=0){//ROT
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],12,$uptadet_id_,$cellDataArr[10]);
				}
//				IF($cellDataArr[11]!=0){//ND1
//					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],6,$uptadet_id_,$cellDataArr[11]);
//				}
				IF($cellDataArr[12]!=0){//ND2
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],7,$uptadet_id_,$cellDataArr[12]);
				}
				//---------------END----------------<<
				//-----------RESTDAY OT------------->>
				if($cellDataArr[14]!=0){//RST
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],15,$uptadet_id_,$cellDataArr[14]);
				}
				if($cellDataArr[15]!=0){//RSO
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],14,$uptadet_id_,$cellDataArr[15]);
				}
				if($cellDataArr[16]!=0){//RD ND
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],8,$uptadet_id_,$cellDataArr[16]);
				}
				if($cellDataArr[17]!=0){//RD NDOT
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],9,$uptadet_id_,$cellDataArr[17]);
				}
				//---------------END----------------<<
				//------------HOLIDAY OT------------>>
				if($cellDataArr[19]!=0){//RGH
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],10,$uptadet_id_,$cellDataArr[19]);
				}
				if($cellDataArr[20]!=0){//RGO
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],1,$uptadet_id_,$cellDataArr[20]);
				}
				if($cellDataArr[21]!=0){//HOL ND
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],2,$uptadet_id_,$cellDataArr[21]);
				}
				if($cellDataArr[22]!=0){//HOL NDOT
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],3,$uptadet_id_,$cellDataArr[22]);
				}
				//---------------END----------------<<
				//-------Special Holiday OT--------->>
				if($cellDataArr[24]!=0){//SPH
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],20,$uptadet_id_,$cellDataArr[24]);
				}
				if($cellDataArr[25]!=0){//SPO
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],22,$uptadet_id_,$cellDataArr[25]);
				}
				if($cellDataArr[26]!=0){//SP HOL ND
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],16,$uptadet_id_,$cellDataArr[26]);
				}
				if($cellDataArr[27]!=0){//SP HOL NDOT
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],17,$uptadet_id_,$cellDataArr[27]);
				}	
				//---------------END----------------<<
				//--------Holiday Restday OT--------<<
				if($cellDataArr[34]!=0){//RGR
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],11,$uptadet_id_,$cellDataArr[34]);
				}
				if($cellDataArr[35]!=0){//RRO
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],13,$uptadet_id_,$cellDataArr[35]);
				}
				if($cellDataArr[36]!=0){//HOL RD ND
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],4,$uptadet_id_,$cellDataArr[36]);
				}
				if($cellDataArr[37]!=0){//HOL RD NDOT
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],5,$uptadet_id_,$cellDataArr[37]);
				}
				//---------------END----------------<<
				//----Special Holiday Restday OT---->>
				if($cellDataArr[44]!=0){//SPR
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],23,$uptadet_id_,$cellDataArr[44]);
				}
				if($cellDataArr[45]!=0){//SRO
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],24,$uptadet_id_,$cellDataArr[45]);
				}
				if($cellDataArr[46]!=0){//SP HOL RD ND
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],18,$uptadet_id_,$cellDataArr[46]);
				}
				if($cellDataArr[47]!=0){//SP HOL RD NDOT
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],19,$uptadet_id_,$cellDataArr[47]);
				}
				//---------------END----------------<<
				//--------- Company Holiday -------->>
				if($cellDataArr[29]!=0){//COMPANY HOL
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],27,$uptadet_id_,$cellDataArr[29]);
				}
				if($cellDataArr[30]!=0){//COMPANY HOL OT
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],30,$uptadet_id_,$cellDataArr[30]);
				}
				if($cellDataArr[31]!=0){//COMPANY HOL ND
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],28,$uptadet_id_,$cellDataArr[31]);
				}
				if($cellDataArr[32]!=0){//COMPANY HOL NDOT
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],29,$uptadet_id_,$cellDataArr[32]);
				}
				//---------------END----------------<<
				//------Company Holiday Restday----->>
				if($cellDataArr[39]!=0){//COMPANY HOL RD
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],31,$uptadet_id_,$cellDataArr[39]);
				}
				if($cellDataArr[40]!=0){//COMPANY HOL RDOT
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],34,$uptadet_id_,$cellDataArr[40]);
				}
				if($cellDataArr[41]!=0){//COMPANY HOL RD ND
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],32,$uptadet_id_,$cellDataArr[41]);
				}
				if($cellDataArr[42]!=0){//COMPANY HOL RD NDOT
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],33,$uptadet_id_,$cellDataArr[42]);
				}
				//---------------END----------------<<
			//-----------------------END-------------------------<<
			//FOR LEAVE RECORD
			//--------------------------------------------------->>
				if($cellDataArr[78]>=0){//SL
					$this->saveLeaveRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],23,$uptadet_id_,$cellDataArr[51],$cellDataArr[78]);
				}
				if($cellDataArr[79]>=0){//VL
					$this->saveLeaveRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],25,$uptadet_id_,$cellDataArr[52],$cellDataArr[79]);
				}
				if($cellDataArr[53]>=0){//ML
					$this->saveLeaveRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],14,$uptadet_id_,$cellDataArr[53],$cellDataArr[80]);
				}
				if($cellDataArr[54]>=0){//PL
					$this->saveLeaveRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],18,$uptadet_id_,$cellDataArr[54],$cellDataArr[81]);
				}
				if($cellDataArr[55]>=0){//NP
					$this->saveLeaveRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],16,$uptadet_id_,$cellDataArr[55],$cellDataArr[82]);
				}
				if($cellDataArr[56]>=0){//BL
					$this->saveLeaveRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],2,$uptadet_id_,$cellDataArr[56],$cellDataArr[83]);
				}
				if($cellDataArr[57]>=0){//NL
					$this->saveLeaveRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],15,$uptadet_id_,$cellDataArr[57],$cellDataArr[84]);
				}
				if($cellDataArr[58]>=0){//RD/NP
					$this->saveLeaveRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],21,$uptadet_id_,$cellDataArr[58],$cellDataArr[85]);
				}
				if($cellDataArr[59]>=0){//AVL
					$this->saveLeaveRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],1,$uptadet_id_,$cellDataArr[59],$cellDataArr[86]);
				}
				if($cellDataArr[60]>=0){//S
					$this->saveLeaveRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],22,$uptadet_id_,$cellDataArr[60],$cellDataArr[87]);
				}
				if($cellDataArr[61]>=0){//Coy Hol
					$this->saveLeaveRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],3,$uptadet_id_,$cellDataArr[61],$cellDataArr[88]);
				}
				if($cellDataArr[62]>=0){//RD-VL
					$this->saveLeaveRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],20,$uptadet_id_,$cellDataArr[62],$cellDataArr[89]);
				}
				if($cellDataArr[63]>=0){//RD-SL
					$this->saveLeaveRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],19,$uptadet_id_,$cellDataArr[63],$cellDataArr[90]);
				}
				if($cellDataArr[64]>=0){//SPL
					$this->saveLeaveRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],24,$uptadet_id_,$cellDataArr[64],$cellDataArr[91]);
				}
				if($cellDataArr[65]>=0){//EL2
					$this->saveLeaveRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],5,$uptadet_id_,$cellDataArr[65],$cellDataArr[92]);
				}
				if($cellDataArr[66]>=0){//EL3
					$this->saveLeaveRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],6,$uptadet_id_,$cellDataArr[66],$cellDataArr[93]);
				}
				if($cellDataArr[67]>=0){//MC
					$this->saveLeaveRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],13,$uptadet_id_,$cellDataArr[67],$cellDataArr[94]);
				}
				if($cellDataArr[68]>=0){//NPL ( bUFFER)
					$this->saveLeaveRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],17,$uptadet_id_,$cellDataArr[68],$cellDataArr[95]);
				}
				if($cellDataArr[69]>=0){//EL4
					$this->saveLeaveRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],7,$uptadet_id_,$cellDataArr[69],$cellDataArr[96]);
				}
				if($cellDataArr[70]>=0){//EL5
					$this->saveLeaveRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],8,$uptadet_id_,$cellDataArr[70],$cellDataArr[97]);
				}
				/*	if($cellDataArr[71]!=0){//EL6
				 	$this->saveLeaveRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],9,$uptadet_id_,$cellDataArr[71],$cellDataArr[98]);
				}*/
				if($cellDataArr[72]>=0){//EL6
					$this->saveLeaveRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],9,$uptadet_id_,$cellDataArr[72],$cellDataArr[99]);
				}
				if($cellDataArr[73]>=0){//EL7
					$this->saveLeaveRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],10,$uptadet_id_,$cellDataArr[73],$cellDataArr[100]);
				}
				if($cellDataArr[74]>=0){//EL8
					$this->saveLeaveRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],11,$uptadet_id_,$cellDataArr[74],$cellDataArr[101]);
				}
				if($cellDataArr[75]>=0){//EL9
					$this->saveLeaveRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],12,$uptadet_id_,$cellDataArr[75],$cellDataArr[102]);
				}
				if($cellDataArr[76]>=0){//EL10
					$this->saveLeaveRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],4,$uptadet_id_,$cellDataArr[76],$cellDataArr[103]);
				}
			//-----------------------END-------------------------<<
		}
		$sql = "UPDATE tks_uploadta_header SET uptahead_goodqty=".(($rowPos-9)-$badQtyCtr).", uptahead_badqty=$badQtyCtr WHERE uptahead_id=$uptahead_id";
		$this->conn->Execute($sql);
		$_SESSION['eMsg'] = "Successfully Uploaded with $badQtyCtr bad qty of ".($rowPos-9)." record data.";
		return $uptahead_id;
	}
	
	/**
	 * @note: FAS TA MAPPING
	 * @param excel TA data $pData_
	 */
	function doSaveUploadTAHead_FAS($pData_ = array()){
		if (count($pData_) == 0) {
			return null;
		}
		$flds =  array();
		$flds[] = "uptahead_desc='".$pData_['uptahead_desc']."'"; 
		$flds[] = "uptahead_addwho='".AppUser::getData('user_name')."'"; 
		$flds[] = "uptahead_status='new'";
		$flds[] = "payperiod_id='".$_GET['ppsched_view']."'";
		$fields = implode(", ",$flds);
		$sql = "INSERT INTO tks_uploadta_header set $fields";
		$this->conn->Execute($sql);
		$uptahead_id = $this->conn->Insert_ID();

		$badQtyCtr = 0;
		$rowPos = 9;
		$rowCnt = $this->xlsData[0]['numRows'];
		for ($rowPos = 9;$rowPos <= $rowCnt;$rowPos++){
			$cellDataArr = $this->xlsData[0]['cells'][$rowPos];
			$eMsg = array();
			$isValid = true;
			$emp_id = $this->getEmpIDByEmpNum($cellDataArr[1]);
			if ($emp_id['emp_id'] == 0) {
				$eMsg[] = "Invalid Employee not included in this Pay Group.";
				$isValid = false;
			}
			$errMsg = "";
			if (!$isValid) {
				$errMsg = "->".implode(";",$eMsg);
				$badQtyCtr++;
			}
			$flds = array();
			$flds[] = "uptahead_id=$uptahead_id";
			$flds[] = "emp_id='".$emp_id['emp_id']."'";
			$flds[] = "uptadet_empnum='".$cellDataArr[1]."'";
			$flds[] = "uptadet_empname='".$cellDataArr[2]."'";
			$flds[] = "uptadet_desc='".$errMsg."'";
			$flds[] = "uptadet_status='new'";
			$flds[] = "uptadet_isgood=".(($isValid)?1:0);
			$fields = implode(", ",$flds);
			$sql = "INSERT INTO tks_uploadta_details SET $fields";
			$this->conn->Execute($sql);
			$uptadet_id_ = $this->conn->Insert_ID();
			
			IF($emp_id['salarytype_id'] == 2){//this is used for daily employee
				IF($cellDataArr[4]!=0){//for Late
					$this->saveTARecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],3,$uptadet_id_,$cellDataArr[4]);
				}
				IF($cellDataArr[5]!=0){//for U/T
					$this->saveTARecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],4,$uptadet_id_,$cellDataArr[5]);
				}
				IF($cellDataArr[6]!=0){//for Days work
//					$vartotalday = $cellDataArr[6] + $cellDataArr[11] + $cellDataArr[16] + $cellDataArr[21] + $cellDataArr[31] + $cellDataArr[36];
					$vartotalday = $cellDataArr[6] + $cellDataArr[16];
					IF($cellDataArr[12]!=0){ $vartotalday += $cellDataArr[11]; }
					IF($cellDataArr[22]!=0){ $vartotalday += $cellDataArr[21]; }
					IF($cellDataArr[27]!=0){ $vartotalday += $cellDataArr[26]; }
					IF($cellDataArr[32]!=0){ $vartotalday += $cellDataArr[31]; }
					IF($cellDataArr[37]!=0){ $vartotalday += $cellDataArr[36]; }
					$this->saveTARecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],5,$uptadet_id_,$vartotalday);
				}
				IF($cellDataArr[6]!=0){//for Custom Days
					$vartotalday = $cellDataArr[6] + $cellDataArr[16];
					IF($cellDataArr[12]!=0){ $vartotalday += $cellDataArr[11]; }
					IF($cellDataArr[22]!=0){ $vartotalday += $cellDataArr[21]; }
					IF($cellDataArr[27]!=0){ $vartotalday += $cellDataArr[26]; }
					IF($cellDataArr[32]!=0){ $vartotalday += $cellDataArr[31]; }
					IF($cellDataArr[37]!=0){ $vartotalday += $cellDataArr[36]; }
					$this->saveTARecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],6,$uptadet_id_,$vartotalday);
				}
			}ELSE{//this is used for Monthly employee
				IF($cellDataArr[3]!=0){//for absent
					$this->saveTARecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],1,$uptadet_id_,$cellDataArr[3]);
				}
				IF($cellDataArr[4]!=0){//for late
					$this->saveTARecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],3,$uptadet_id_,$cellDataArr[4]);
				}
				IF($cellDataArr[5]!=0){//for U/T
					$this->saveTARecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],4,$uptadet_id_,$cellDataArr[5]);
				}
			}
			//FOR CUSTOM FIELDS
			//--------------------------------------------------->>
				IF($cellDataArr[42]==true){//OT MEAL
					$this->saveCustomRD($emp_id['emp_id'],$_GET['ppsched_view'],1,$uptadet_id_,$cellDataArr[42]);
				}
				IF($cellDataArr[41]==true){//Meal Allowance
					$this->saveCustomRD($emp_id['emp_id'],$_GET['ppsched_view'],2,$uptadet_id_,$cellDataArr[41]);
				}
			//---------------------------------------------------<<	
			IF($this->validateOTAssigned($emp_id['emp_id'])){
			//FOR OT RECORD
			//--------------------------------------------------->>
				//-------------REG TIME------------->>
				IF($cellDataArr[8]!=0){//REGOT
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],26,$uptadet_id_,$cellDataArr[8]);
				}
				IF($cellDataArr[9]!=0){//ND
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],13,$uptadet_id_,$cellDataArr[9]);
				}
				IF($cellDataArr[10]!=0){//NDX8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],14,$uptadet_id_,$cellDataArr[10]);
				}
				//---------------END----------------<<
				//-----------RESTDAY OT------------->>
				IF($cellDataArr[12]!=0){//OT_RDF8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],15,$uptadet_id_,$cellDataArr[12]);
				}
				IF($cellDataArr[13]!=0){//OT_RD88
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],16,$uptadet_id_,$cellDataArr[13]);
				}
				IF($cellDataArr[14]!=0){//ND_RDF8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],2,$uptadet_id_,$cellDataArr[14]);
				}
				IF($cellDataArr[15]!=0){//ND_RDX8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],3,$uptadet_id_,$cellDataArr[15]);
				}
				//---------------END----------------<<
				//------------HOLIDAY OT------------>>
				IF($cellDataArr[17]!=0){//Holiday
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],1,$uptadet_id_,$cellDataArr[17]);
				}
				IF($cellDataArr[18]!=0){//OT_RHX8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],20,$uptadet_id_,$cellDataArr[18]);
				}
				if($cellDataArr[19]!=0){//ND_RHF8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],4,$uptadet_id_,$cellDataArr[19]);
				}
				if($cellDataArr[20]!=0){//ND_RHX8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],7,$uptadet_id_,$cellDataArr[20]);
				}
				//---------------END----------------<<
				//-------Special Holiday OT--------->>
				if($emp_id['salarytype_id'] == 2){//this is used for daily employee
					if($cellDataArr[22]!=0){//OT_SHF8
						$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],21,$uptadet_id_,$cellDataArr[22]);
					}
					if($cellDataArr[23]!=0){//OT_SHX8
						$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],25,$uptadet_id_,$cellDataArr[23]);
					}
					if($cellDataArr[24]!=0){//ND_SHF8
						$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],8,$uptadet_id_,$cellDataArr[24]);
					}
					
				}else{//this is used for monthly employee
					if($cellDataArr[22]!=0){//OT_SHF8M
						$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],22,$uptadet_id_,$cellDataArr[22]);
					}
					if($cellDataArr[23]!=0){//OT_SHX8
						$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],25,$uptadet_id_,$cellDataArr[23]);
					}
					if($cellDataArr[24]!=0){//ND_SHF8
						$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],9,$uptadet_id_,$cellDataArr[24]);
					}
				}
				if($cellDataArr[25]!=0){//ND_SHX8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],12,$uptadet_id_,$cellDataArr[25]);
				}	
				//---------------END----------------<<
				//--------Holiday Restday OT--------<<
				if($cellDataArr[32]!=0){//OT_RHRDF8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],18,$uptadet_id_,$cellDataArr[32]);
				}
				if($cellDataArr[33]!=0){//OT_RHRDX8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],19,$uptadet_id_,$cellDataArr[33]);
				}
				if($cellDataArr[34]!=0){//ND_RHRDF8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],5,$uptadet_id_,$cellDataArr[34]);
				}
				if($cellDataArr[35]!=0){//ND_RHRDX8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],6,$uptadet_id_,$cellDataArr[35]);
				}
				//---------------END----------------<<
				//----Special Holiday Restday OT---->>
				if($cellDataArr[37]!=0){//OT_SHRDF8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],23,$uptadet_id_,$cellDataArr[37]);
				}
				if($cellDataArr[38]!=0){//OT_SHRDF8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],24,$uptadet_id_,$cellDataArr[38]);
				}
				if($cellDataArr[39]!=0){//OT_SHRDF8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],10,$uptadet_id_,$cellDataArr[39]);
				}
				if($cellDataArr[40]!=0){//OT_SHRDF8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],11,$uptadet_id_,$cellDataArr[40]);
				}
				//---------------END----------------<<
				//--------Company Holiday OT-------->>
				if($cellDataArr[27]!=0){//CH_OT_HRS
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],27,$uptadet_id_,$cellDataArr[27]);
				}
				if($cellDataArr[28]!=0){//CH_OT_HRSX8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],28,$uptadet_id_,$cellDataArr[28]);
				}
				if($cellDataArr[29]!=0){//ND_CHF8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],29,$uptadet_id_,$cellDataArr[29]);
				}
				if($cellDataArr[30]!=0){//ND_CHX8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],30,$uptadet_id_,$cellDataArr[30]);
				}
				//---------------END----------------<<
			//-----------------------END-------------------------<<
			}
			//FOR LEAVE RECORD
			//--------------------------------------------------->>
				IF($cellDataArr[56]==true){//SL
					$this->saveLeaveRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],2,$uptadet_id_,$cellDataArr[44],$cellDataArr[56]);
				}
				IF($cellDataArr[57]==true){//VL
					$this->saveLeaveRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],1,$uptadet_id_,$cellDataArr[45],$cellDataArr[57]);
				}
				IF($cellDataArr[58]==true){//ML
					$this->saveLeaveRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],4,$uptadet_id_,$cellDataArr[46],$cellDataArr[58]);
				}
				IF($cellDataArr[59]==true){//PL
					$this->saveLeaveRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],6,$uptadet_id_,$cellDataArr[47],$cellDataArr[59]);
				}
				IF($cellDataArr[60]==true){//EL
					$this->saveLeaveRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],3,$uptadet_id_,$cellDataArr[48],$cellDataArr[60]);
				}
				IF($cellDataArr[61]==true){//BL
					$this->saveLeaveRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],7,$uptadet_id_,$cellDataArr[49],$cellDataArr[61]);
				}
				IF($cellDataArr[62]==true){//SPL
					$this->saveLeaveRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],8,$uptadet_id_,$cellDataArr[50],$cellDataArr[62]);
				}
			//-----------------------END-------------------------<<
		}
		$sql = "update tks_uploadta_header set uptahead_goodqty=".(($rowPos-9)-$badQtyCtr).", uptahead_badqty=$badQtyCtr where uptahead_id=$uptahead_id";
		$this->conn->Execute($sql);
		$_SESSION['eMsg'] = "Successfully Uploaded with $badQtyCtr bad qty of ".($rowPos-9)." record data.";
		return $uptahead_id;
	}
	
	/**
	 * @note: AGROTECH TA MAPPING
	 * @param excel TA data $pData_
	 */
	function doSaveUploadTAHead_AGRO($pData_ = array()){
		if (count($pData_) == 0) {
			return null;
		}
		$flds =  array();
		$flds[] = "uptahead_desc='".$pData_['uptahead_desc']."'"; 
		$flds[] = "uptahead_addwho='".AppUser::getData('user_name')."'"; 
		$flds[] = "uptahead_status='new'";
		$flds[] = "payperiod_id='".$_GET['ppsched_view']."'";
		$fields = implode(", ",$flds);
		$sql = "INSERT INTO tks_uploadta_header set $fields";
		$this->conn->Execute($sql);
		$uptahead_id = $this->conn->Insert_ID();

		$badQtyCtr = 0;
		$rowPos = 9;
		$rowCnt = $this->xlsData[0]['numRows'];
		for ($rowPos = 9;$rowPos <= $rowCnt;$rowPos++){
			$cellDataArr = $this->xlsData[0]['cells'][$rowPos];
			$eMsg = array();
			$isValid = true;
			$emp_id = $this->getEmpIDByEmpNum($cellDataArr[1]);
			if ($emp_id['emp_id'] == 0) {
				$eMsg[] = "Invalid Employee not included in this Pay Group.";
				$isValid = false;
			}
			$errMsg = "";
			if (!$isValid) {
				$errMsg = "->".implode(";",$eMsg);
				$badQtyCtr++;
			}
			$flds = array();
			$flds[] = "uptahead_id=$uptahead_id";
			$flds[] = "emp_id='".$emp_id['emp_id']."'";
			$flds[] = "uptadet_empnum='".$cellDataArr[1]."'";
			$flds[] = "uptadet_empname='".$cellDataArr[2]."'";
			$flds[] = "uptadet_desc='".$errMsg."'";
			$flds[] = "uptadet_status='new'";
			$flds[] = "uptadet_isgood=".(($isValid)?1:0);
			$fields = implode(", ",$flds);
			$sql = "INSERT INTO tks_uploadta_details SET $fields";
			$this->conn->Execute($sql);
			$uptadet_id_ = $this->conn->Insert_ID();
			
			IF($emp_id['salarytype_id'] == 2){//this is used for daily employee
				IF($cellDataArr[4]!=0){//for Late
					$this->saveTARecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],3,$uptadet_id_,$cellDataArr[4]);
				}
				IF($cellDataArr[5]!=0){//for U/T
					$this->saveTARecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],4,$uptadet_id_,$cellDataArr[5]);
				}
				IF($cellDataArr[6]!=0){//for Days work
					$vartotalday = $cellDataArr[6] + $cellDataArr[16];
					IF($cellDataArr[12]!=0){ $vartotalday += $cellDataArr[11]; }
					IF($cellDataArr[22]!=0){ $vartotalday += $cellDataArr[21]; }
					IF($cellDataArr[27]!=0){ $vartotalday += $cellDataArr[26]; }
					IF($cellDataArr[32]!=0){ $vartotalday += $cellDataArr[31]; }
					$this->saveTARecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],5,$uptadet_id_,$vartotalday);
				}
				IF($cellDataArr[6]!=0){//for Custom Days
					$vartotalday = $cellDataArr[6] + $cellDataArr[16];
					IF($cellDataArr[12]!=0){ $vartotalday += $cellDataArr[11]; }
					IF($cellDataArr[22]!=0){ $vartotalday += $cellDataArr[21]; }
					IF($cellDataArr[27]!=0){ $vartotalday += $cellDataArr[26]; }
					IF($cellDataArr[32]!=0){ $vartotalday += $cellDataArr[31]; }
					$this->saveTARecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],6,$uptadet_id_,$vartotalday);
				}
			}else{//this is used for Monthly employee
				IF($cellDataArr[3]!=0){//for absent
					$this->saveTARecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],1,$uptadet_id_,$cellDataArr[3]);
				}
				IF($cellDataArr[4]!=0){//for late
					$this->saveTARecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],3,$uptadet_id_,$cellDataArr[4]);
				}
				IF($cellDataArr[5]!=0){//for U/T
					$this->saveTARecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],4,$uptadet_id_,$cellDataArr[5]);
				}
			}
			//FOR CUSTOM FIELDS
			//--------------------------------------------------->>
				IF($cellDataArr[3]!=0){//Custome Days Abs
					$this->saveCustomRD($emp_id['emp_id'],$_GET['ppsched_view'],1,$uptadet_id_,$cellDataArr[3]);
				}
				IF($cellDataArr[4]==true){//Tranpo deduction allow
					$vartotalLT = $cellDataArr[4] + $cellDataArr[5];
					$this->saveCustomRD($emp_id['emp_id'],$_GET['ppsched_view'],2,$uptadet_id_,$vartotalLT);
				}
				IF($cellDataArr[17]==true){//ALLOW HOLIDAY
					$this->saveCustomRD($emp_id['emp_id'],$_GET['ppsched_view'],3,$uptadet_id_,$cellDataArr[17]);
				}
				IF($cellDataArr[8]==true){//ALLOW ROT
					$this->saveCustomRD($emp_id['emp_id'],$_GET['ppsched_view'],4,$uptadet_id_,$cellDataArr[8]);
				}
				IF($cellDataArr[9]==true){//ALLOW NDF8
					$this->saveCustomRD($emp_id['emp_id'],$_GET['ppsched_view'],5,$uptadet_id_,$cellDataArr[9]);
				}
				IF($cellDataArr[10]==true){//ALLOW NDX8
					$this->saveCustomRD($emp_id['emp_id'],$_GET['ppsched_view'],6,$uptadet_id_,$cellDataArr[10]);
				}
				IF($cellDataArr[18]==true){//ALLOW OT_RHX8
					$this->saveCustomRD($emp_id['emp_id'],$_GET['ppsched_view'],7,$uptadet_id_,$cellDataArr[18]);
				}
				IF($cellDataArr[19]==true){//ALLOW ND_RHF8
					$this->saveCustomRD($emp_id['emp_id'],$_GET['ppsched_view'],8,$uptadet_id_,$cellDataArr[19]);
				}
				IF($cellDataArr[20]==true){//ALLOW ND_RHX8
					$this->saveCustomRD($emp_id['emp_id'],$_GET['ppsched_view'],9,$uptadet_id_,$cellDataArr[20]);
				}
				IF($cellDataArr[12]==true){//ALLOW OT_RDF8
					$this->saveCustomRD($emp_id['emp_id'],$_GET['ppsched_view'],10,$uptadet_id_,$cellDataArr[12]);
				}
				IF($cellDataArr[13]==true){//ALLOW OT_RDX8
					$this->saveCustomRD($emp_id['emp_id'],$_GET['ppsched_view'],11,$uptadet_id_,$cellDataArr[13]);
				}
				IF($cellDataArr[14]==true){//ALLOW ND_RDF8
					$this->saveCustomRD($emp_id['emp_id'],$_GET['ppsched_view'],12,$uptadet_id_,$cellDataArr[14]);
				}
				IF($cellDataArr[15]==true){//ALLOW ND_RDX8
					$this->saveCustomRD($emp_id['emp_id'],$_GET['ppsched_view'],13,$uptadet_id_,$cellDataArr[15]);
				}
				IF($cellDataArr[22]==true){//ALLOW OT_SHF8M
					$this->saveCustomRD($emp_id['emp_id'],$_GET['ppsched_view'],14,$uptadet_id_,$cellDataArr[22]);
				}
				IF($cellDataArr[23]==true){//ALLOW OT_SHX8
					$this->saveCustomRD($emp_id['emp_id'],$_GET['ppsched_view'],15,$uptadet_id_,$cellDataArr[23]);
				}
				IF($cellDataArr[24]==true){//ALLOW ND_SHF8M
					$this->saveCustomRD($emp_id['emp_id'],$_GET['ppsched_view'],16,$uptadet_id_,$cellDataArr[24]);
				}
				IF($cellDataArr[25]==true){//ALLOW ND_SHX8
					$this->saveCustomRD($emp_id['emp_id'],$_GET['ppsched_view'],17,$uptadet_id_,$cellDataArr[25]);
				}
				IF($cellDataArr[27]==true){//ALLOW OT_RHRDF8
					$this->saveCustomRD($emp_id['emp_id'],$_GET['ppsched_view'],18,$uptadet_id_,$cellDataArr[27]);
				}
				IF($cellDataArr[28]==true){//ALLOW OT_RHRDX8
					$this->saveCustomRD($emp_id['emp_id'],$_GET['ppsched_view'],19,$uptadet_id_,$cellDataArr[28]);
				}
				IF($cellDataArr[29]==true){//ALLOW ND_RHRDF8
					$this->saveCustomRD($emp_id['emp_id'],$_GET['ppsched_view'],20,$uptadet_id_,$cellDataArr[29]);
				}
				IF($cellDataArr[30]==true){//ALLOW ND_RHRDX8
					$this->saveCustomRD($emp_id['emp_id'],$_GET['ppsched_view'],21,$uptadet_id_,$cellDataArr[30]);
				}
				IF($cellDataArr[32]==true){//ALLOW OT_SHRDF8
					$this->saveCustomRD($emp_id['emp_id'],$_GET['ppsched_view'],22,$uptadet_id_,$cellDataArr[32]);
				}
				IF($cellDataArr[33]==true){//ALLOW OT_SHRDX8
					$this->saveCustomRD($emp_id['emp_id'],$_GET['ppsched_view'],23,$uptadet_id_,$cellDataArr[33]);
				}
				IF($cellDataArr[34]==true){//ALLOW ND_SHRDF8
					$this->saveCustomRD($emp_id['emp_id'],$_GET['ppsched_view'],24,$uptadet_id_,$cellDataArr[34]);
				}
				IF($cellDataArr[35]==true){//ALLOW ND_SHRDX8
					$this->saveCustomRD($emp_id['emp_id'],$_GET['ppsched_view'],25,$uptadet_id_,$cellDataArr[35]);
				}
			//---------------------------------------------------<<	
			IF($this->validateOTAssigned($emp_id['emp_id'])){
			//FOR OT RECORD
			//--------------------------------------------------->>
				//-------------REG TIME------------->>
				IF($cellDataArr[8]!=0){//REGOT
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],26,$uptadet_id_,$cellDataArr[8]);
				}
				IF($cellDataArr[9]!=0){//NDF8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],13,$uptadet_id_,$cellDataArr[9]);
				}
				IF($cellDataArr[10]!=0){//NDX8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],14,$uptadet_id_,$cellDataArr[10]);
				}
				//---------------END----------------<<
				//-----------RESTDAY OT------------->>
				IF($cellDataArr[12]!=0){//OT_RDF8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],15,$uptadet_id_,$cellDataArr[12]);
				}
				IF($cellDataArr[13]!=0){//OT_RD88
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],16,$uptadet_id_,$cellDataArr[13]);
				}
				IF($cellDataArr[14]!=0){//ND_RDF8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],2,$uptadet_id_,$cellDataArr[14]);
				}
				IF($cellDataArr[15]!=0){//ND_RDX8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],3,$uptadet_id_,$cellDataArr[15]);
				}
				//---------------END----------------<<
				//------------HOLIDAY OT------------>>
				IF($cellDataArr[17]!=0){//Holiday
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],1,$uptadet_id_,$cellDataArr[17]);
				}
				IF($cellDataArr[18]!=0){//OT_RHX8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],20,$uptadet_id_,$cellDataArr[18]);
				}
				if($cellDataArr[19]!=0){//ND_RHF8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],4,$uptadet_id_,$cellDataArr[19]);
				}
				if($cellDataArr[20]!=0){//ND_RHX8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],7,$uptadet_id_,$cellDataArr[20]);
				}
				//---------------END----------------<<
				//-------Special Holiday OT--------->>
				if($emp_id['salarytype_id'] == 2){//this is used for daily employee
					if($cellDataArr[22]!=0){//OT_SHF8
						$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],21,$uptadet_id_,$cellDataArr[22]);
					}
					if($cellDataArr[24]!=0){//ND_SHF8
						$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],8,$uptadet_id_,$cellDataArr[24]);
					}
					
				}else{//this is used for monthly employee
					if($cellDataArr[22]!=0){//OT_SHF8M
						$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],22,$uptadet_id_,$cellDataArr[22]);
					}
					if($cellDataArr[24]!=0){//ND_SHF8
						$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],9,$uptadet_id_,$cellDataArr[24]);
					}
				}
				if($cellDataArr[23]!=0){//OT_SHX8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],25,$uptadet_id_,$cellDataArr[23]);
				}
				if($cellDataArr[25]!=0){//ND_SHX8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],12,$uptadet_id_,$cellDataArr[25]);
				}	
				//---------------END----------------<<
				//--------Holiday Restday OT--------<<
				if($cellDataArr[27]!=0){//OT_RHRDF8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],18,$uptadet_id_,$cellDataArr[27]);
				}
				if($cellDataArr[28]!=0){//OT_RHRDX8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],19,$uptadet_id_,$cellDataArr[28]);
				}
				if($cellDataArr[29]!=0){//ND_RHRDF8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],5,$uptadet_id_,$cellDataArr[29]);
				}
				if($cellDataArr[30]!=0){//ND_RHRDX8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],6,$uptadet_id_,$cellDataArr[30]);
				}
				//---------------END----------------<<
				//----Special Holiday Restday OT---->>
				if($cellDataArr[32]!=0){//OT_SHRDF8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],23,$uptadet_id_,$cellDataArr[32]);
				}
				if($cellDataArr[33]!=0){//OT_SHRDF8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],24,$uptadet_id_,$cellDataArr[33]);
				}
				if($cellDataArr[34]!=0){//ND_SHRDF8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],10,$uptadet_id_,$cellDataArr[34]);
				}
				if($cellDataArr[35]!=0){//ND_SHRDX8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],11,$uptadet_id_,$cellDataArr[35]);
				}
				//---------------END----------------<<
			//-----------------------END-------------------------<<
			}
			//FOR LEAVE RECORD
			//--------------------------------------------------->>
				IF($cellDataArr[36]==true){//SL
					$this->saveLeaveRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],2,$uptadet_id_,$cellDataArr[36],$cellDataArr[54]);
				}
				IF($cellDataArr[37]==true){//VL
					$this->saveLeaveRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],1,$uptadet_id_,$cellDataArr[37],$cellDataArr[55]);
				}
				IF($cellDataArr[38]==true){//ML
					$this->saveLeaveRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],4,$uptadet_id_,$cellDataArr[38],$cellDataArr[43]);
				}
				IF($cellDataArr[39]==true){//PL
					$this->saveLeaveRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],6,$uptadet_id_,$cellDataArr[39],$cellDataArr[44]);
				}
			//-----------------------END-------------------------<<
		}
		$sql = "UPDATE tks_uploadta_header SET uptahead_goodqty=".(($rowPos-9)-$badQtyCtr).", uptahead_badqty=$badQtyCtr WHERE uptahead_id=$uptahead_id";
		$this->conn->Execute($sql);
		$_SESSION['eMsg'] = "Successfully Uploaded with $badQtyCtr bad qty of ".($rowPos-9)." record data.";
		return $uptahead_id;
	}
	
	/**
	 * @note: TACOMA TA MAPPING
	 * @param excel TA data $pData_
	 */
	function doSaveUploadTAHead_TACOMA($pData_ = array()){
		IF (count($pData_) == 0) {
			return null;
		}
		$flds =  array();
		$flds[] = "uptahead_desc='".$pData_['uptahead_desc']."'"; 
		$flds[] = "uptahead_addwho='".AppUser::getData('user_name')."'"; 
		$flds[] = "uptahead_status='new'";
		$flds[] = "payperiod_id='".$_GET['ppsched_view']."'";
		$fields = implode(", ",$flds);
		$sql = "INSERT INTO tks_uploadta_header set $fields";
		$this->conn->Execute($sql);
		$uptahead_id = $this->conn->Insert_ID();

		$badQtyCtr = 0;
		$rowPos = 9;
		$rowCnt = $this->xlsData[0]['numRows'];
		for ($rowPos = 9;$rowPos <= $rowCnt;$rowPos++){
			$cellDataArr = $this->xlsData[0]['cells'][$rowPos];
			$eMsg = array();
			$isValid = true;
			$emp_id = $this->getEmpIDByEmpNum($cellDataArr[1]);
			if ($emp_id['emp_id'] == 0) {
				$eMsg[] = "Invalid Employee not included in this Pay Group.";
				$isValid = false;
			}
			$errMsg = "";
			if (!$isValid) {
				$errMsg = "->".implode(";",$eMsg);
				$badQtyCtr++;
			}
			$flds = array();
			$flds[] = "uptahead_id=$uptahead_id";
			$flds[] = "emp_id='".$emp_id['emp_id']."'";
			$flds[] = "uptadet_empnum='".$cellDataArr[1]."'";
			$flds[] = "uptadet_empname='".$cellDataArr[2]."'";
			$flds[] = "uptadet_desc='".$errMsg."'";
			$flds[] = "uptadet_status='new'";
			$flds[] = "uptadet_isgood=".(($isValid)?1:0);
			$fields = implode(", ",$flds);
			$sql = "INSERT INTO tks_uploadta_details SET $fields";
			$this->conn->Execute($sql);
			$uptadet_id_ = $this->conn->Insert_ID();
			
			IF($emp_id['salarytype_id'] == 2){//this is used for daily employee
				IF($cellDataArr[4]!=0){//for Late
					$this->saveTARecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],3,$uptadet_id_,$cellDataArr[4]);
				}
				IF($cellDataArr[5]!=0){//for U/T
					$this->saveTARecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],4,$uptadet_id_,$cellDataArr[5]);
				}
				IF($cellDataArr[6]!=0){//for Days work
					$vartotalday = $cellDataArr[6] + $cellDataArr[16] + $cellDataArr[26];
					$this->saveTARecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],5,$uptadet_id_,$vartotalday);
				}
				IF($cellDataArr[6]!=0){//for Custom Days
					$vartotalday = $cellDataArr[6] + $cellDataArr[16];
					IF($cellDataArr[12]!=0){ $vartotalday += $cellDataArr[11]; }
					IF($cellDataArr[22]!=0){ $vartotalday += $cellDataArr[21]; }
					IF($cellDataArr[27]!=0){ $vartotalday += $cellDataArr[26]; }
					IF($cellDataArr[32]!=0){ $vartotalday += $cellDataArr[31]; }
					$this->saveTARecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],6,$uptadet_id_,$vartotalday);
				}
			}else{//this is used for Monthly employee
				IF($cellDataArr[3]!=0){//for absent
					$this->saveTARecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],1,$uptadet_id_,$cellDataArr[3]);
				}
				IF($cellDataArr[4]!=0){//for late
					$this->saveTARecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],3,$uptadet_id_,$cellDataArr[4]);
				}
				IF($cellDataArr[5]!=0){//for U/T
					$this->saveTARecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],4,$uptadet_id_,$cellDataArr[5]);
				}
			}
			//FOR CUSTOM FIELDS
			//--------------------------------------------------->>
			//	No RECORD FOUND
			//---------------------------------------------------<<	
			IF($this->validateOTAssigned($emp_id['emp_id'])){
			//FOR OT RECORD
			//--------------------------------------------------->>
				//-------------REG TIME------------->>
				IF($cellDataArr[8]!=0){//REGOT
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],26,$uptadet_id_,$cellDataArr[8]);
				}
				IF($cellDataArr[9]!=0){//NDF8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],13,$uptadet_id_,$cellDataArr[9]);
				}
				IF($cellDataArr[10]!=0){//NDX8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],14,$uptadet_id_,$cellDataArr[10]);
				}
				//---------------END----------------<<
				//-----------RESTDAY OT------------->>
				IF($emp_id['salarytype_id'] == 2){//this is used for daily employee
					IF($cellDataArr[12]!=0){//OT_RDF8
						$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],33,$uptadet_id_,$cellDataArr[12]);
					}
				}ELSE{
					IF($cellDataArr[12]!=0){//OT_RDF8
						$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],15,$uptadet_id_,$cellDataArr[12]);
					}
				}
				IF($cellDataArr[13]!=0){//OT_RD88
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],16,$uptadet_id_,$cellDataArr[13]);
				}
				IF($cellDataArr[14]!=0){//ND_RDF8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],2,$uptadet_id_,$cellDataArr[14]);
				}
				IF($cellDataArr[15]!=0){//ND_RDX8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],3,$uptadet_id_,$cellDataArr[15]);
				}
				//---------------END----------------<<
				//------------HOLIDAY OT------------>>
				IF($emp_id['salarytype_id'] == 2){//this is used for daily employee
					IF($cellDataArr[17]!=0){//Holiday
						$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],34,$uptadet_id_,$cellDataArr[17]);
					}
				}ELSE{
					IF($cellDataArr[17]!=0){//Holiday
						$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],1,$uptadet_id_,$cellDataArr[17]);
					}
				}	
				IF($cellDataArr[18]!=0){//OT_RHX8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],20,$uptadet_id_,$cellDataArr[18]);
				}
				if($cellDataArr[19]!=0){//ND_RHF8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],4,$uptadet_id_,$cellDataArr[19]);
				}
				if($cellDataArr[20]!=0){//ND_RHX8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],7,$uptadet_id_,$cellDataArr[20]);
				}
				//---------------END----------------<<
				//-------Special Holiday OT--------->>
				IF($emp_id['salarytype_id'] == 2){//this is used for daily employee
					IF($cellDataArr[22]!=0){//OT_SHF8
						$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],21,$uptadet_id_,$cellDataArr[22]);
					}
					IF($cellDataArr[24]!=0){//ND_SHF8
						$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],8,$uptadet_id_,$cellDataArr[24]);
					}
					
				}ELSE{//this is used for monthly employee
					IF($cellDataArr[22]!=0){//OT_SHF8M
						$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],22,$uptadet_id_,$cellDataArr[22]);
					}
					IF($cellDataArr[24]!=0){//ND_SHF8
						$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],9,$uptadet_id_,$cellDataArr[24]);
					}
				}
				IF($cellDataArr[23]!=0){//OT_SHX8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],25,$uptadet_id_,$cellDataArr[23]);
				}
				IF($cellDataArr[25]!=0){//ND_SHX8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],12,$uptadet_id_,$cellDataArr[25]);
				}	
				//---------------END----------------<<
				//--------Holiday Restday OT--------<<
				if($cellDataArr[27]!=0){//OT_RHRDF8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],18,$uptadet_id_,$cellDataArr[27]);
				}
				if($cellDataArr[28]!=0){//OT_RHRDX8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],19,$uptadet_id_,$cellDataArr[28]);
				}
				if($cellDataArr[29]!=0){//ND_RHRDF8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],5,$uptadet_id_,$cellDataArr[29]);
				}
				if($cellDataArr[30]!=0){//ND_RHRDX8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],6,$uptadet_id_,$cellDataArr[30]);
				}
				//---------------END----------------<<
				//----Special Holiday Restday OT---->>
				if($cellDataArr[32]!=0){//OT_SHRDF8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],23,$uptadet_id_,$cellDataArr[32]);
				}
				if($cellDataArr[33]!=0){//OT_SHRDF8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],24,$uptadet_id_,$cellDataArr[33]);
				}
				if($cellDataArr[34]!=0){//ND_SHRDF8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],10,$uptadet_id_,$cellDataArr[34]);
				}
				if($cellDataArr[35]!=0){//ND_SHRDX8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],11,$uptadet_id_,$cellDataArr[35]);
				}
				//---------------END----------------<<
			//-----------------------END-------------------------<<
			}
			//FOR LEAVE RECORD
			//--------------------------------------------------->>
				IF($cellDataArr[36]==true){//SL
					$this->saveLeaveRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],2,$uptadet_id_,$cellDataArr[36],$cellDataArr[54]);
				}
				IF($cellDataArr[37]==true){//VL
					$this->saveLeaveRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],1,$uptadet_id_,$cellDataArr[37],$cellDataArr[55]);
				}
				IF($cellDataArr[38]==true){//ML
					$this->saveLeaveRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],4,$uptadet_id_,$cellDataArr[38],$cellDataArr[43]);
				}
				IF($cellDataArr[39]==true){//PL
					$this->saveLeaveRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],6,$uptadet_id_,$cellDataArr[39],$cellDataArr[44]);
				}
			//-----------------------END-------------------------<<
		}
		$sql = "UPDATE tks_uploadta_header SET uptahead_goodqty=".(($rowPos-9)-$badQtyCtr).", uptahead_badqty=$badQtyCtr WHERE uptahead_id=$uptahead_id";
		$this->conn->Execute($sql);
		$_SESSION['eMsg'] = "Successfully Uploaded with $badQtyCtr bad qty of ".($rowPos-9)." record data.";
		return $uptahead_id;
	}
	
	/**
	 * @note: SIGMASOFT TA MAPPING
	 * @param excel TA data $pData_
	 */
	function doSaveUploadTAHead_SIGMA($pData_ = array()){
		if (count($pData_) == 0) {
			return null;
		}
		$flds =  array();
		$flds[] = "uptahead_desc='".$pData_['uptahead_desc']."'"; 
		$flds[] = "uptahead_addwho='".AppUser::getData('user_name')."'"; 
		$flds[] = "uptahead_status='new'";
		$flds[] = "payperiod_id='".$_GET['ppsched_view']."'";
		$fields = implode(", ",$flds);
		$sql = "INSERT INTO tks_uploadta_header set $fields";
		$this->conn->Execute($sql);
		$uptahead_id = $this->conn->Insert_ID();

		$badQtyCtr = 0;
		$rowPos = 9;
		$rowCnt = $this->xlsData[0]['numRows'];
		for ($rowPos = 9;$rowPos <= $rowCnt;$rowPos++){
			$cellDataArr = $this->xlsData[0]['cells'][$rowPos];
			$eMsg = array();
			$isValid = true;
			$emp_id = $this->getEmpIDByEmpNum($cellDataArr[1]);
			if ($emp_id['emp_id'] == 0) {
				$eMsg[] = "Invalid Employee not included in this Pay Group.";
				$isValid = false;
			}
			$errMsg = "";
			if (!$isValid) {
				$errMsg = "->".implode(";",$eMsg);
				$badQtyCtr++;
			}
			$flds = array();
			$flds[] = "uptahead_id=$uptahead_id";
			$flds[] = "emp_id='".$emp_id['emp_id']."'";
			$flds[] = "uptadet_empnum='".$cellDataArr[1]."'";
			$flds[] = "uptadet_empname='".$cellDataArr[2]."'";
			$flds[] = "uptadet_desc='".$errMsg."'";
			$flds[] = "uptadet_status='new'";
			$flds[] = "uptadet_isgood=".(($isValid)?1:0);
			$fields = implode(", ",$flds);
			$sql = "INSERT INTO tks_uploadta_details SET $fields";
			$this->conn->Execute($sql);
			$uptadet_id_ = $this->conn->Insert_ID();
			
			IF($emp_id['salarytype_id'] == 2){//this is used for daily employee
				IF($cellDataArr[4]!=0){//for Late
					$this->saveTARecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],3,$uptadet_id_,$cellDataArr[4]);
				}
				IF($cellDataArr[5]!=0){//for U/T
					$this->saveTARecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],4,$uptadet_id_,$cellDataArr[5]);
				}
				IF($cellDataArr[6]!=0){//for Days work
					$vartotalday = $cellDataArr[6] + $cellDataArr[16];
					IF($cellDataArr[12]!=0){ $vartotalday += $cellDataArr[11]; }
					IF($cellDataArr[22]!=0){ $vartotalday += $cellDataArr[21]; }
					IF($cellDataArr[27]!=0){ $vartotalday += $cellDataArr[26]; }
					IF($cellDataArr[32]!=0){ $vartotalday += $cellDataArr[31]; }
					$this->saveTARecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],5,$uptadet_id_,$vartotalday);
				}
				IF($cellDataArr[6]!=0){//for Custom Days
					$vartotalday = $cellDataArr[6] + $cellDataArr[16];
					IF($cellDataArr[12]!=0){ $vartotalday += $cellDataArr[11]; }
					IF($cellDataArr[22]!=0){ $vartotalday += $cellDataArr[21]; }
					IF($cellDataArr[27]!=0){ $vartotalday += $cellDataArr[26]; }
					IF($cellDataArr[32]!=0){ $vartotalday += $cellDataArr[31]; }
					$this->saveTARecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],6,$uptadet_id_,$vartotalday);
				}
			}else{//this is used for Monthly employee
				IF($cellDataArr[3]!=0){//for absent
					$this->saveTARecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],1,$uptadet_id_,$cellDataArr[3]);
				}
				IF($cellDataArr[4]!=0){//for late
					$this->saveTARecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],3,$uptadet_id_,$cellDataArr[4]);
				}
				IF($cellDataArr[5]!=0){//for U/T
					$this->saveTARecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],4,$uptadet_id_,$cellDataArr[5]);
				}
			}
			//FOR CUSTOM FIELDS
			//--------------------------------------------------->>
				IF($cellDataArr[17]==true){//NTA_LH_OT_HRS
					$this->saveCustomRD($emp_id['emp_id'],$_GET['ppsched_view'],2,$uptadet_id_,$cellDataArr[17]);
				}
				IF($cellDataArr[14]==true){//NTA_ND_RDF8
					$this->saveCustomRD($emp_id['emp_id'],$_GET['ppsched_view'],3,$uptadet_id_,$cellDataArr[14]);
				}
				IF($cellDataArr[15]==true){//NTA_ND_RDX8
					$this->saveCustomRD($emp_id['emp_id'],$_GET['ppsched_view'],4,$uptadet_id_,$cellDataArr[15]);
				}
				IF($cellDataArr[25]==true){//NTA_ND_SHX8
					$this->saveCustomRD($emp_id['emp_id'],$_GET['ppsched_view'],5,$uptadet_id_,$cellDataArr[25]);
				}
				IF($cellDataArr[9]==true){//NTA_NDF8
					$this->saveCustomRD($emp_id['emp_id'],$_GET['ppsched_view'],6,$uptadet_id_,$cellDataArr[9]);
				}
				IF($cellDataArr[12]==true){//NTA_RD_OT_HRS
					$this->saveCustomRD($emp_id['emp_id'],$_GET['ppsched_view'],7,$uptadet_id_,$cellDataArr[12]);
				}
				IF($cellDataArr[13]==true){//NTA_RD_OT_>8HRS
					$this->saveCustomRD($emp_id['emp_id'],$_GET['ppsched_view'],8,$uptadet_id_,$cellDataArr[13]);
				}
				IF($cellDataArr[27]==true){//NTA_LHRD_OT_HRS
					$this->saveCustomRD($emp_id['emp_id'],$_GET['ppsched_view'],9,$uptadet_id_,$cellDataArr[27]);
				}
				IF($cellDataArr[28]==true){//NTA_LHRD_OT_>8HRS
					$this->saveCustomRD($emp_id['emp_id'],$_GET['ppsched_view'],10,$uptadet_id_,$cellDataArr[28]);
				}
				IF($cellDataArr[18]==true){//NTA_LH_OT_>8HRS
					$this->saveCustomRD($emp_id['emp_id'],$_GET['ppsched_view'],11,$uptadet_id_,$cellDataArr[18]);
				}
				IF($cellDataArr[22]==true){//NTA_SH_OT_HRS_M
					$this->saveCustomRD($emp_id['emp_id'],$_GET['ppsched_view'],12,$uptadet_id_,$cellDataArr[22]);
				}
				IF($cellDataArr[32]==true){//NTA_SHRD_OT_HRS
					$this->saveCustomRD($emp_id['emp_id'],$_GET['ppsched_view'],13,$uptadet_id_,$cellDataArr[32]);
				}
				IF($cellDataArr[33]==true){//NTA_SHRD_OT_>8HRS
					$this->saveCustomRD($emp_id['emp_id'],$_GET['ppsched_view'],14,$uptadet_id_,$cellDataArr[33]);
				}
				IF($cellDataArr[23]==true){//NTA_SH_OT_>8HRS
					$this->saveCustomRD($emp_id['emp_id'],$_GET['ppsched_view'],15,$uptadet_id_,$cellDataArr[23]);
				}
				IF($cellDataArr[8]==true){//NTA_REG_OT_HRS
					$this->saveCustomRD($emp_id['emp_id'],$_GET['ppsched_view'],16,$uptadet_id_,$cellDataArr[8]);
				}
			//---------------------------------------------------<<	
			IF($this->validateOTAssigned($emp_id['emp_id'])){
			//FOR OT RECORD
			//--------------------------------------------------->>
				//-------------REG TIME------------->>
				IF($cellDataArr[8]!=0){//REGOT
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],26,$uptadet_id_,$cellDataArr[8]);
				}
				IF($cellDataArr[9]!=0){//NDF8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],13,$uptadet_id_,$cellDataArr[9]);
				}
				IF($cellDataArr[10]!=0){//NDX8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],14,$uptadet_id_,$cellDataArr[10]);
				}
				//---------------END----------------<<
				//-----------RESTDAY OT------------->>
				IF($cellDataArr[12]!=0){//OT_RDF8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],15,$uptadet_id_,$cellDataArr[12]);
				}
				IF($cellDataArr[13]!=0){//OT_RD88
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],16,$uptadet_id_,$cellDataArr[13]);
				}
				IF($cellDataArr[14]!=0){//ND_RDF8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],2,$uptadet_id_,$cellDataArr[14]);
				}
				IF($cellDataArr[15]!=0){//ND_RDX8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],3,$uptadet_id_,$cellDataArr[15]);
				}
				//---------------END----------------<<
				//------------HOLIDAY OT------------>>
				IF($cellDataArr[17]!=0){//Holiday
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],1,$uptadet_id_,$cellDataArr[17]);
				}
				IF($cellDataArr[18]!=0){//OT_RHX8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],20,$uptadet_id_,$cellDataArr[18]);
				}
				if($cellDataArr[19]!=0){//ND_RHF8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],4,$uptadet_id_,$cellDataArr[19]);
				}
				if($cellDataArr[20]!=0){//ND_RHX8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],7,$uptadet_id_,$cellDataArr[20]);
				}
				//---------------END----------------<<
				//-------Special Holiday OT--------->>
				if($emp_id['salarytype_id'] == 2){//this is used for daily employee
					if($cellDataArr[22]!=0){//OT_SHF8
						$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],21,$uptadet_id_,$cellDataArr[22]);
					}
					if($cellDataArr[24]!=0){//ND_SHF8
						$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],8,$uptadet_id_,$cellDataArr[24]);
					}
					
				}else{//this is used for monthly employee
					if($cellDataArr[22]!=0){//OT_SHF8M
						$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],22,$uptadet_id_,$cellDataArr[22]);
					}
					if($cellDataArr[24]!=0){//ND_SHF8
						$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],9,$uptadet_id_,$cellDataArr[24]);
					}
				}
				if($cellDataArr[23]!=0){//OT_SHX8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],25,$uptadet_id_,$cellDataArr[23]);
				}
				if($cellDataArr[25]!=0){//ND_SHX8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],12,$uptadet_id_,$cellDataArr[25]);
				}	
				//---------------END----------------<<
				//--------Holiday Restday OT--------<<
				if($cellDataArr[27]!=0){//OT_RHRDF8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],18,$uptadet_id_,$cellDataArr[27]);
				}
				if($cellDataArr[28]!=0){//OT_RHRDX8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],19,$uptadet_id_,$cellDataArr[28]);
				}
				if($cellDataArr[29]!=0){//ND_RHRDF8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],5,$uptadet_id_,$cellDataArr[29]);
				}
				if($cellDataArr[30]!=0){//ND_RHRDX8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],6,$uptadet_id_,$cellDataArr[30]);
				}
				//---------------END----------------<<
				//----Special Holiday Restday OT---->>
				if($cellDataArr[32]!=0){//OT_SHRDF8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],23,$uptadet_id_,$cellDataArr[32]);
				}
				if($cellDataArr[33]!=0){//OT_SHRDF8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],24,$uptadet_id_,$cellDataArr[33]);
				}
				if($cellDataArr[34]!=0){//ND_SHRDF8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],10,$uptadet_id_,$cellDataArr[34]);
				}
				if($cellDataArr[35]!=0){//ND_SHRDX8
					$this->saveOTRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],11,$uptadet_id_,$cellDataArr[35]);
				}
				//---------------END----------------<<
			//-----------------------END-------------------------<<
			}
			//FOR LEAVE RECORD
			//--------------------------------------------------->>
				IF($cellDataArr[36]==true){//SL
					$this->saveLeaveRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],2,$uptadet_id_,$cellDataArr[36],$cellDataArr[41]);
				}
				IF($cellDataArr[37]==true){//VL
					$this->saveLeaveRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],1,$uptadet_id_,$cellDataArr[37],$cellDataArr[42]);
				}
				IF($cellDataArr[38]==true){//ML
					$this->saveLeaveRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],4,$uptadet_id_,$cellDataArr[38],$cellDataArr[43]);
				}
				IF($cellDataArr[39]==true){//PL
					$this->saveLeaveRecordDetails($emp_id['emp_id'],$_GET['ppsched_view'],6,$uptadet_id_,$cellDataArr[39],$cellDataArr[44]);
				}
			//-----------------------END-------------------------<<
		}
		$sql = "UPDATE tks_uploadta_header SET uptahead_goodqty=".(($rowPos-9)-$badQtyCtr).", uptahead_badqty=$badQtyCtr WHERE uptahead_id=$uptahead_id";
		$this->conn->Execute($sql);
		$_SESSION['eMsg'] = "Successfully Uploaded with $badQtyCtr bad qty of ".($rowPos-9)." record data.";
		return $uptahead_id;
	}
	
	/**
	 * @note: getLeaveID
	 * @param $emp_id_
	 * @param $isType
	 */
	function getLeaveID($emp_id_ = null, $isType = null){
		if (is_null($emp_id_)) {
			return 0;
		}
		$qry = array();
		$qry[] = "a.emp_id = '".$emp_id_."'";
		$qry[] = "a.leave_id = '".$isType."'";
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";
		$sqlget = "SELECT a.empleave_id, a.empleave_available_day FROM emp_leave a $criteria";
		$rsResult = $this->conn->Execute($sqlget);
		if (!$rsResult->EOF) {
			return $rsResult->fields;
		}else {
			return 0;
		}
	}
	
	/**
	 * validate OT Table Assigned
	 * @param string $emp_id
	 */
	function validateOTAssigned($emp_id = null){
		$sql = "SELECT ot_id FROM payroll_comp WHERE emp_id='".$emp_id."'";
		$r = $this->conn->Execute($sql);
		if(!$r->EOF){
			if($r->fields['ot_id']>0){
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	/**
	 * @note: To Save Leave Record Details in table emp_leave_rec
	 */
	function saveLeaveRecordDetails($emp_id_ = null, $payperiod_id_ = null, $leave_id_ = null, $uptadet_id_ = null, $empleave_used_day_ = 0, $empleave_available_day_ = 0){
		$empleave_id = $this->getLeaveID($emp_id_,$leave_id_);
		$var_ = $empleave_id['empleave_available_day'] - $empleave_used_day_;
		$flds_ = array();
		$flds_[] = "empleave_used_day='".$empleave_used_day_."'";
		$flds_[] = "empleave_available_day='".$empleave_available_day_."'";
		$fields_ = implode(", ",$flds_);
		$sql_ = "UPDATE emp_leave SET $fields_ WHERE empleave_id = '".$empleave_id['empleave_id']."'";
		$this->conn->Execute($sql_);
		IF($empleave_used_day_!=0){
			$flds = array();
			$flds[] = "payperiod_id='".$payperiod_id_."'";
			$flds[] = "emp_id='".$emp_id_."'";
			$flds[] = "empleave_id='".$empleave_id['empleave_id']."'";
			$flds[] = "emp_leav_rec_leavedays='".$empleave_used_day_."'";
			$flds[] = "uptadet_id='".$uptadet_id_."'";
			$fields = implode(", ",$flds);
			$sql = "INSERT INTO emp_leave_rec SET $fields";
			$this->conn->Execute($sql);
		}
	}
	
	/**
	 * @note: To Save Custom Record Details in table cf_detail
	 */
	function saveCustomRD($emp_id_ = null, $payperiod_id_ = null, $cfhead_id_ = null, $uptadet_id_ = null, $cfdetail_rec_ = 0){
		$flds = array();
		$flds[] = "payperiod_id='".$payperiod_id_."'";
		$flds[] = "emp_id='".$emp_id_."'";
		$flds[] = "cfhead_id='".$cfhead_id_."'";
		$flds[] = "cfdetail_rec='".$cfdetail_rec_."'";
		$flds[] = "uptadet_id='".$uptadet_id_."'";
		$fields = implode(", ",$flds);
		$sql = "INSERT INTO cf_detail SET $fields";
		$this->conn->Execute($sql);
	}
	
	/**
	 * @note: To Save OT Record Details in table ot_record
	 */
	function saveOTRecordDetails($emp_id_ = null, $payperiod_id_ = null, $otr_id_ = null, $uptadet_id_ = null, $otrec_totalhrs_ = 0){
		$flds = array();
		$flds[] = "payperiod_id='".$payperiod_id_."'";
		$flds[] = "emp_id='".$emp_id_."'";
		$flds[] = "otr_id='".$otr_id_."'";
		$flds[] = "otrec_totalhrs='".$otrec_totalhrs_."'";
		$flds[] = "uptadet_id='".$uptadet_id_."'";
		$fields = implode(", ",$flds);
		$sql = "INSERT INTO ot_record SET $fields";
		$this->conn->Execute($sql);
	}
	
	/**
	 * @note: To Save TA Record Details in table ta_emp_rec
	 */
	function saveTARecordDetails($emp_id_ = null, $payperiod_id_ = null, $tatbl_id_ = null, $uptadet_id_ = null, $emp_tarec_nohrday_ = 0){
		$flds = array();
		$flds[] = "payperiod_id='".$payperiod_id_."'";
		$flds[] = "emp_id='".$emp_id_."'";
		$flds[] = "tatbl_id='".$tatbl_id_."'";
		$flds[] = "emp_tarec_nohrday='".$emp_tarec_nohrday_."'";
		$flds[] = "uptadet_id='".$uptadet_id_."'";
		$fields = implode(", ",$flds);
		$sql = "INSERT INTO ta_emp_rec SET $fields";
		$this->conn->Execute($sql);
	}
	
	function getHeaderTAupload($ppsched_ = null, $ppsched_view_ = null){
		if (is_null($ppsched_)) { return ""; }
		if (is_null($ppsched_view_)) { return ""; }
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
		$qry[] = "a.payperiod_id = '".$ppsched_view_."'";
		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		 "viewdata" => "viewdata"
		,"uptahead_id"=>"uptahead_id"
		,"uptahead_date"=>"uptahead_date"
		,"uptahead_desc"=>"uptahead_desc"
		,"uptahead_goodqty"=>"uptahead_goodqty"
		,"uptahead_badqty"=>"uptahead_badqty"
		,"totalci"=>"totalci"
		,"uptahead_status"=>"uptahead_status"
		);

		if(isset($_GET['sortby'])){
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}else{
			$strOrderBy = " order by a.payperiod_id DESC";
		}
		
		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
//		$viewLink = "<a href=\"?statpos=time_attend&ppsched=',psar.pps_id,'&ppsched_view=',ppp.payperiod_id,'\">',psar.pps_name,'</a>";
		$editLink = "<a href=\"?statpos=time_attend&viewuptahead=',a.uptahead_id,'&ppsched=$ppsched_&ppsched_view=$ppsched_view_\"><img src=\"".SYSCONFIG_THEME_URLPATH.SYSCONFIG_THEME."/images/admin/zoom.gif\" title=\"View Details\" hspace=\"2px\" border=0></a>";
		$delLink = "<a href=\"?statpos=time_attend&deleteuptahead=',a.uptahead_id,'&ppsched=$ppsched_&ppsched_view=$ppsched_view_\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\"  border=0 width=\"16\" height=\"16\"></a>";

		// SqlAll Query
		$sql = "select a.*,(uptahead_goodqty+uptahead_badqty) as totalci,date_format(a.uptahead_addwhen,'".APPCONFIG_FORMAT_DATETIME_SQL."') as uptahead_date, CONCAT('$viewLink','$editLink','$delLink') as viewdata
						from tks_uploadta_header a
						$criteria
						$strOrderBy";

		// Field and Table Header Mapping
		$arrFields = array(
		 "viewdata" => "Action"
		,"uptahead_id"=>"ID"
		,"uptahead_date"=>"Date"
		,"uptahead_desc"=>"Description"
		,"uptahead_goodqty"=>"Valid CI Count"
		,"uptahead_badqty"=>"Invalid CI Count"
		,"totalci"=>"Total"
		,"uptahead_status"=>"Status"
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
	
	function getEmpIDByEmpNum($empnum_ = null){
		IF (is_null($empnum_)) { return 0; }
		$qry = array();
		$qry[] = "ppi.emp_idnum = '$empnum_'";
		$qry[] = "ppi.emp_stat != 0";
		$criteria = (count($qry)>0)?" WHERE ".implode(" AND ",$qry):"";
		$sql = "SELECT ppi.emp_id, b.salarytype_id FROM emp_masterfile ppi JOIN salary_info b on (b.emp_id=ppi.emp_id) $criteria";
		$rsResult = $this->conn->Execute($sql);
		if (!$rsResult->EOF) {
			return $rsResult->fields;
		}else {
			return 0;
		}
	}
	
	function getUploadCIHeadInfo($uptahead_id_ = null){
		if (is_null($uptahead_id_)) { return array(); }
		$qry = array();
		$qry[] = "a.uptahead_id = $uptahead_id_";
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";
		$sql = "select a.* from tks_uploadta_header a $criteria";
		$rsResult = $this->conn->Execute($sql);
		if (!$rsResult->EOF) {
			return $rsResult->fields;
		}
	}
	
	function getUploadCITableListDetails($viewuptahead_ = null,$ppsched_,$ppsched_view_){
		if (is_null($viewuptahead_)) { return ""; }
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
				$qry[] = "pi_lname like '%$search_field%'";
			}
		}
		
		// check filter
		if (isset($_GET['filterby'])) {
			$qry[] = "uptadet_isgood=".$_GET['filterby'];
		}
		$qry[] = "a.uptahead_id = $viewuptahead_";
//		$qry[] = "c.emp_stat != 0";
		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";
		// Sort field mapping
		$arrSortBy = array(
		 "uptadet_id"=>"uptadet_id"
		,"uptadet_empnum"=>"uptadet_empnum"
		,"uptadet_empname"=>"uptadet_empname"
		,"uptadet_desc"=>"uptadet_desc"
		,"uptadet_status"=>"uptadet_status"
		,"uptadet_isgood"=>"uptadet_isgood"
		);
		if(isset($_GET['sortby'])){
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}
		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$viewLink = "<a href=\"?statpos=time_attend&viewtime=',b.emp_id,'&viewuptahead=$viewuptahead_&ppsched=$ppsched_&ppsched_view=$ppsched_view_\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/leaveicon.png\" title=\"TA\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
		$delLink = "<a href=\"?statpos=time_attend&deleteuptadetail=',b.uptadet_id,'&viewuptahead=$viewuptahead_\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\"  border=0 width=\"16\" height=\"16\"></a>";
		// SqlAll Query
		$sql = "SELECT a.*, b.*, IF(b.uptadet_isgood='0',CONCAT('$editLink','$delLink'),CONCAT('$viewLink','$editLink','$delLink')) as viewdata, c.emp_idnum, d.pi_lname, d.pi_fname
						FROM tks_uploadta_header a 
						LEFT JOIN tks_uploadta_details b on a.uptahead_id = b.uptahead_id
						LEFT JOIN emp_masterfile c on c.emp_id=b.emp_id
						LEFT JOIN emp_personal_info d on c.pi_id=d.pi_id
						$criteria
						$strOrderBy";
		// Field and Table Header Mapping
		$arrFields = array(
		 "viewdata"=>"Action"
		,"uptadet_id"=>"ID"
		,"uptadet_empnum"=>"Emp ID"
		,"uptadet_empname"=>"Employee Name"
		,"uptadet_desc"=>"Description"
		,"uptadet_status"=>"Status"
		,"uptadet_isgood"=>"Good"
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
	
	function doTransferFinalTA($upcihead_id_ = null){
		if (is_null($upcihead_id_)) { return null; }
		$qry = array();
		$qry[] = "a.upcihead_id=$upcihead_id_";
		$qry[] = "a.upcidet_isgood=1";
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";
		$sql = "insert into sat_receiveci (customer_id, unit_id, prod_id, prod_code, receiveci_desc, receiveci_qty, receiveci_weeknumber, receiveci_addwho) 
					select a.customer_id, a.unit_id, a.prod_id, a.prod_code, a.upcidet_desc, a.upcidet_qty, a.upcidet_weeknumber, '".AppUser::getData("user_name")."' 
					from azt_salesandtrading_db.sat_uploadci_details a $criteria";
		$this->conn->Execute($sql);
		$sql = "update sat_uploadci_details set upcidet_status='transfered', upcidet_updatewho='".AppUser::getData("user_name")."', upcidet_updatewhen=now() where upcihead_id=$upcihead_id_ and upcidet_isgood=1";
		$this->conn->Execute($sql);
		$sql = "update sat_uploadci_header set upcihead_status='transfered', upcihead_updatewho='".AppUser::getData("user_name")."', upcihead_updatewhen=now() where upcihead_id=$upcihead_id_";
		$this->conn->Execute($sql);
		$_SESSION['eMsg'] = "Successfully transferred to final CI.";
	}
}
?>