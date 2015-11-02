<?php
/**
 * Class Module
 * @author Dionisio Untiveros
 */
class clsImportAmendments {
	var $conn;
	var $fieldMap;
	var $Data;

	/**
	 * Class Constructor
	 * @param object $dbconn_
	 * @return clsImBenDuc object
	 */
	function clsImportAmendments($dbconn_ = null) {
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
	function dbFetch($id_ = "") {
		$sql = "";
		$rsResult = $this->conn->Execute($sql,array($id_));
		if (!$rsResult->EOF) {
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
		if (count($pData_)>0) {
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
	 * Validation function
	 * @param array $pData_
	 * @return bool
	 */
	function doValidateData($pData_ = array()) {
		$isValid = true;
		
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
		} elseif ($pData_['payperiod'] == '0') {
			$_SESSION['eMsg'][] = "Please select pay period for this import.";
			$isValid = false;
			return $isValid;
		}
		
		$this->xlsData = $this->doReadAndInsertUPAmendments($pData_['uptahead_file']['tmp_name']);
		
		// Import Validation
		if (count($pData_) == 0) {
			return NULL;
		}
		$badQtyCtr = 0;
		$rowPos = 6;
		$rowCnt = $this->xlsData[0]['numRows'];
		for ($rowPos; $rowPos <= $rowCnt; $rowPos++) {
			$errArrReq = array(); // By Sir IR Salvador
			$cellDataArr = $this->xlsData[0]['cells'][$rowPos];
			
			// Variable Asigning and Triming
			
			$psamend_status = ucfirst(strtolower(trim($cellDataArr[1])));
			$psa_type = ucfirst(strtolower(trim($cellDataArr[2]))); // Pay Element Type
			$psa_name = trim($cellDataArr[3]);
			$psamend_effect_date = trim($cellDataArr[4]);
			$emp_idnum = trim($cellDataArr[5]);
			$amendemp_amount = trim($cellDataArr[7]);
			
			if ( 	
					(empty($psamend_status)) &&
					(empty($psa_type)) &&
					(empty($psamend_name)) &&
					(empty($psamend_effect_date)) &&
					(empty($emp_idnum)) &&
					(empty($amendemp_amount))
				) {
				$skipped_row++;
			} else {
				// icheck yung required column
				if (empty($psamend_status)) {
					$errArrReq[] = '"Status"';
					$isValid = false;
				} else {
					// if the value of Status Column is not equal to Active and not equal to Paid, then display the error
					if ($psamend_status != 'Active' && $psamend_status != 'Paid') {
						$_SESSION['eMsg'][] = "In Row {$rowPos}, Column \"Status\" should be one of these only: Active, Paid.";
						$isValid = false;
					}
				}
				if (empty($psa_type)) {
					$errArrReq[] = '"Pay Element Type"';
					$isValid = false;
				} else {
					// if the value of Pay Element Type is not equal to Earning and not equal to Deduction, then display the error
					if ($psa_type != 'Earning' && $psa_type != 'Deduction') {
						$_SESSION['eMsg'][] = "In Row {$rowPos}, Column \"Pay Element Type\" should be Earning or Deduction only.";
						$isValid = false;
					}
				}
				if (empty($psa_name)) {
					$errArrReq[] = '"Pay Element Name"';
					$isValid = false;
				} else {
					switch ($psa_type) {
						case 'Earning':
							$psa_type_in_database = 1;
							break;
						case 'Deduction':
							$psa_type_in_database = 2;
							break;
						default:
							break;
					}
					
					// if Pay Element Name is not Earning or Deduction in Type, display the error
					// it also covers the validation in the Pay Element Name if the value is not in the database or not in the List of Pay Element Name
					$sql = "SELECT psa_id FROM `payroll_ps_account` WHERE psa_type = '{$psa_type_in_database}' AND psa_name =  '{$psa_name}'";
					$psa_id_result = $this->conn->Execute($sql);
					if ($psa_id_result->EOF) {
						$_SESSION['eMsg'][] = "In Row {$rowPos}, Column \"Pay Element Name\", \"{$psa_name}\" is not Earning or Deduction in Type.";
						$isValid = false;
					}
				}
				if (empty($psamend_effect_date)) {
					$errArrReq[] = '"Effective Date"';
					$isValid = false;
				} else {
					if (strlen($psamend_effect_date) != 10) {
						$_SESSION['eMsg'][] = "In Row {$rowPos}, Column \"Effective Date\", \"{$psamend_effect_date}\" is invalid Date format. The format should be YYYY-MM-DD.";
						$isValid = false;
					}
				}
				
				// For payroll_ps_amendemp table
				IF (empty($emp_idnum)) {
					$errArrReq[] = '"Employee No."';
					$isValid = false;
				} ELSE {
					/* =========== Employee ID =========== */
					$employee_param = "emp_id, emp_idnum";
					$qryloan = array();
					$qryloan[] = "emp_idnum = '{$emp_idnum}'";
					$qryloan[] = "emp_stat != '0'";
					$table = "emp_masterfile";
					$emp_id = $this->getIDByParameter($emp_idnum,$employee_param,$qryloan,$table);
					
					IF (!$emp_id[emp_id]) {
						$_SESSION['eMsg'][] = "In Row {$rowPos}, Column \"Employee No.\", \"{$emp_idnum}\" is not in the database.";
						$isValid = false;
					}
					
					switch ($psamend_status) {
						case 'Active':
							$psamend_status = 1;
							break;
						case 'Paid':
							$psamend_status = 2;
							break;
						default:
							break;
					}
					
					/* =========== Pay Element =========== */
					$pay_element_param = "psa_id, psa_name";
					$qryloan = array();
					$qryloan[] = "psa_name = '{$psa_name}'";
					$table = "payroll_ps_account";
					$psa_id = $this->getIDByParameter($psa_name,$pay_element_param,$qryloan,$table);
					
					$sql_psamend_id = "SELECT psamend_id FROM `payroll_ps_amendment` WHERE psamend_name = '{$psa_name}' AND psamend_status = '{$psamend_status}' AND psa_id = '{$psa_id['psa_id']}' AND psamend_effect_date = '{$psamend_effect_date}'";
					$sql_validator_result = $this->conn->Execute($sql_psamend_id);
					
					if(!$sql_validator_result->EOF){
						$sql_double_entry_searcher = "SELECT emp_id, psamend_id FROM `payroll_ps_amendemp` WHERE emp_id = '{$emp_id['emp_id']}' AND psamend_id = '{$sql_validator_result->fields[psamend_id]}'";
						$sql_double_entry_searcher_result = $this->conn->Execute($sql_double_entry_searcher);
						
						if (!$sql_double_entry_searcher_result->EOF) {
							$_SESSION['eMsg'][] = "In Rows {$rowPos}, Column \"Employee No.\", \"{$emp_idnum}\" is already assigned to \"{$psa_name}\".";
							$isValid = false;
						}
					}
					
				}
				if (empty($amendemp_amount)) {
					$errArrReq[] = '"Total Amount"';
					$isValid = false;
				} else {
					if (!is_numeric($amendemp_amount)) {
						$_SESSION['eMsg'][] = "In Row {$rowPos}, Column \"Total Amount\" is not valid. It should be Numbers Only.";
						$isValid = false;
					}
				}
			}
			
			if (count($errArrReq) > 0) {
				$_SESSION['eMsg'][] = "In Row {$rowPos}, Column " . implode(", ", $errArrReq) . " is required.";
			}
			$_SESSION['eMsg'] > 0 ? $isValid = false : '';
		} // this is the end of for loop
		
		if ( ($rowPos - (6 + $skipped_row) == 0) ) {
			$_SESSION['eMsg'][] = "Invalid record count. Data is less than 6 rows.";
			$isValid = false;
		} elseif ($rowPos - (6 + $skipped_row) > 1000) {
			$_SESSION['eMsg'][] = "Record count is exceeded to 1000. Please divide your record.";
			$isValid = false;
		}
		return $isValid;
	}
	
	function doReadAndInsertUPAmendments($fname_ = NULL) {
		if (is_null($fname_)) { return NULL; }
		$objClsExcelReader = new Spreadsheet_Excel_Reader();
		$objClsExcelReader->read($fname_);
		return $objClsExcelReader->sheets;
	}

	/**
	 * Upload Loan
	 *
	 */
	function doSaveImportAmendments($pData_ = array()) {
		IF (count($pData_) == 0) { return NULL; }
		$badQtyCtr = 0;
		$rowPos = 6;
		$rowCnt = $this->xlsData[0]['numRows'];
		for ($rowPos = 6; $rowPos <= $rowCnt; $rowPos++) {
			$cellDataArr = $this->xlsData[0]['cells'][$rowPos];
			$eMsg = array();
			$isValid = true;
			
			// Variable Asigning and Triming
			$psamend_status = ucfirst(strtolower(trim($cellDataArr[1])));
			$psa_type = ucfirst(strtolower(trim($cellDataArr[2]))); // Pay Element Type
			$psa_name = mysql_real_escape_string(trim($cellDataArr[3]));
			$psamend_effect_date = trim($cellDataArr[4]);
			$emp_idnum = trim($cellDataArr[5]);
			$amendemp_amount = trim($cellDataArr[7]);
			if ( 	
					(empty($psamend_status)) &&
					(empty($psa_type)) &&
					(empty($psamend_name)) &&
					(empty($psamend_effect_date)) &&
					(empty($emp_idnum)) &&
					(empty($amendemp_amount))
				) {
				$skipped_row++;
			} else {
				/* =========== Pay Element =========== */
				$pay_element_param = "psa_id, psa_name";
				$qryloan = array();
				$qryloan[] = "psa_name = '{$psa_name}'";
				$table = "payroll_ps_account";
				$psa_id = $this->getIDByParameter($psa_name,$pay_element_param,$qryloan,$table);
				
				switch ($psamend_status) {
					case 'Active':
						$psamend_status = 1;
						break;
					case 'Paid':
						$psamend_status = 2;
						break;
					default:
						break;
				}
				
				$sql_validator = "SELECT psamend_id, psamend_name, psamend_status, psa_id, psamend_effect_date FROM `payroll_ps_amendment` WHERE psamend_name = '{$psa_name}' AND psamend_status = '{$psamend_status}' AND psa_id = '{$psa_id['psa_id']}' AND psamend_effect_date = '{$psamend_effect_date}'";
				$sql_validator_result = $this->conn->Execute($sql_validator);
				
				if ($sql_validator_result->EOF) {
					$flds = array(); // Amendments
					$flds[] = "psamend_status='{$psamend_status}'";
					$flds[] = "pps_id='".$_SESSION[admin_session_obj][user_paygroup_list][0]."'";
					$flds[] = "psa_id='{$psa_id['psa_id']}'";
					$flds[] = "psamend_name='{$psa_name}'";
					$flds[] = "psamend_effect_date='{$psamend_effect_date}'";
					$flds[] = "psamend_addwho='".AppUser::getData('user_name')."'";
					$flds[] = "payperiod_id='".$pData_['payperiod']."'";
					$fields = implode(", ",$flds);
					$sql = "INSERT INTO payroll_ps_amendment SET $fields";
					$this->conn->Execute($sql);
					
					$sql_psamend_id = "SELECT psamend_id FROM `payroll_ps_amendment` 
										WHERE psamend_name = '{$psa_name}' 
										AND psamend_status = '{$psamend_status}' 
										AND psa_id = '{$psa_id['psa_id']}' 
										AND psamend_effect_date = '{$psamend_effect_date}'";
					$sql_validator_result = $this->conn->Execute($sql_psamend_id);
				}
				// For payroll_ps_amendemp table
				/* =========== Employee ID =========== */
				$employee_param = "emp_id, emp_idnum";
				$qryloan = array();
				$qryloan[] = "emp_idnum = '{$emp_idnum}'";
				$qryloan[] = "emp_stat != '0'";
				$table = "emp_masterfile";
				$emp_id = $this->getIDByParameter($emp_idnum,$employee_param,$qryloan,$table);
				
				$flds2 = array(); // Amendments
				$flds2[] = "emp_id='{$emp_id['emp_id']}'";
				$flds2[] = "psamend_id='{$sql_validator_result->fields[psamend_id]}'";
				$flds2[] = "amendemp_amount='{$amendemp_amount}'";
				$flds2[] = "amendemp_addwho='".AppUser::getData('user_name')."'";
				$fields2 = implode(", ",$flds2);
				$sql2 = "INSERT INTO payroll_ps_amendemp SET $fields2";
				$this->conn->Execute($sql2);
			}
		}
		$_SESSION['eMsg'] = "Successfully Uploaded! Number of Record(s) Imported: ".( $rowPos - (6 + $skipped_row) );
	}
	
	function getIDByParameter($var = NULL,$qrySelect = 0,$qryWhere = 0,$table = NULL) {
		IF (is_null($var)) { return 0; }
		IF (is_null($table)) { return 0; }
		$qry = (count($qrySelect)>0) ? $qrySelect : "*";
		$criteria = (count($qryWhere)>0) ? ' WHERE '.implode(' AND ',$qryWhere) : '';
		$sql = "SELECT {$qry} FROM {$table} {$criteria}";
		$rsResult = $this->conn->Execute($sql);
		if (!$rsResult->EOF) {
			return $rsResult->fields;
		} else {
			return 0;
		}
	}
	
	function getPayperiod(){
		$arr = array();
		$sql = "SELECT payperiod_id,payperiod_name FROM payroll_pay_period WHERE pp_stat_id=1";
		$rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF){
			$arr[$rsResult->fields['payperiod_id']] = $rsResult->fields['payperiod_name'];
			$rsResult->MoveNext();
		}
		return $arr;
	}
}
?>