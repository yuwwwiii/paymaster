<?php
/**
 * Initial Declaration
 */

/**
 * Class Module
 *
 * @author Grey M. Untiveros
 *
 */
class clsImBenDuc {

	var $conn;
	var $fieldMap;
	var $Data;

	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsImBenDuc object
	 */
	function clsImBenDuc($dbconn_ = NULL) {
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
	 *
	 * @param array $pData_
	 * @return bool
	 */
	function doValidateData($pData_ = array()) {
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
		} elseif ($mime != 'application/vnd.ms-excel') {
			$_SESSION['eMsg'][] = "Invalid File Type. The File Extension should only be .xls";
			$isValid = false;
			return $isValid;
		}
		
		$this->xlsData = $this->doReadAndInsertUPBenefitsAndDeduction($pData_['uptahead_file']['tmp_name']);
		
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
			
			$emp_idnum = trim($cellDataArr[1]);
			$pay_element = trim($cellDataArr[3]);
			$type = ucwords(strtolower(trim($cellDataArr[4])));
			$start_month_and_year = trim($cellDataArr[5]);
			$end_month_and_year = trim($cellDataArr[6]);
			$frequency = trim($cellDataArr[7]);
			$total_amount = trim($cellDataArr[8]);
			$amount_per_pay_period = trim($cellDataArr[9]);
			
			if ( 	
					(empty($emp_idnum)) && 
					(empty($pay_element)) && 
					(empty($type)) &&
					(empty($start_month_and_year)) &&
					(empty($frequency))/* &&
					(empty($total_amount)) &&
					(empty($amount_per_pay_period))*/
				) {
				$skipped_row++;
			} else {
				// icheck yung required column
				if (empty($emp_idnum)) {
					$errArrReq[] = '"Employee No."';
					$isValid = false;
				} else {
					// if there is no Employee No. in the database, display the error
					$sql = "SELECT emp_idnum FROM `emp_masterfile` WHERE emp_idnum = '{$emp_idnum}'";
					$emp_idnum_result = $this->conn->Execute($sql);
					if (!$emp_idnum_result->fields) {
						$_SESSION['eMsg'][] = "In Row {$rowPos}, Column \"Employee No.\", \"{$emp_idnum}\" is not in the database.";
						$isValid = false;
					}
				}
				if (empty($pay_element)) {
					$errArrReq[] = '"Pay Element"';
					$isValid = false;
				} else {
					if (is_numeric($type)) {
						$_SESSION['eMsg'][] = "In Row {$rowPos}, Column \"Pay Element\" is not valid.";
						$isValid = false;
					}
					// if there is no Pay Element Name in the database, display the error
					$sql = "SELECT psa_name FROM payroll_ps_account WHERE psa_name = '{$pay_element}'";
					$pay_element_name_result = $this->conn->Execute($sql);
					if (!$pay_element_name_result->fields) {
						$_SESSION['eMsg'][] = "In Row {$rowPos}, Column \"Pay Element\", \"{$pay_element}\" is not in the list of Pay Elements in the Manage Pay Element Module. Add it first in the Manage Pay Element Module.";
						$isValid = false;
					}
				}
				if (empty($type)) {
					$errArrReq[] = '"Type"';
					$isValid = false;
				} else {
					if (is_numeric($type)) {
						$_SESSION['eMsg'][] = "In Row {$rowPos}, Column \"Type\" is not valid.";
						$isValid = false;
					} elseif ($type != 'Fixed Amount' && $type != 'Free Amount') {
						$_SESSION['eMsg'][] = "In Row {$rowPos}, Column \"Type\" is not valid. It should be \"Fixed Amount\" or \"Free Amount\" only.";
						$isValid = false;
					}
				}
				if (empty($start_month_and_year)) {
					$errArrReq[] = '"Start Date"';
					$isValid = false;
				} else {
					if (strlen($start_month_and_year) != 10) {
						$_SESSION['eMsg'][] = "In Row {$rowPos}, Column \"Start Date\", \"{$start_month_and_year}\" is invalid Date format. The format should be YYYY-MM-DD.";
						$isValid = false;
					} elseif ($start_month_and_year == '0000-00-00') {
						$_SESSION['eMsg'][] = "In Row {$rowPos}, Column \"Start Date\", \"{$start_month_and_year}\" is not a valid Start Date.";
						$isValid = false;
					}
				}
				if (!empty($end_month_and_year)) {
					if (strlen($end_month_and_year) != 10) {
						$_SESSION['eMsg'][] = "In Row {$rowPos}, Column \"End Date\", \"{$end_month_and_year}\" is invalid Date format. The format should be YYYY-MM-DD.";
						$isValid = false;
					} elseif ($end_month_and_year == '0000-00-00') {
						$_SESSION['eMsg'][] = "In Row {$rowPos}, Column \"End Date\", \"{$end_month_and_year}\" is not a valid End Date.";
						$isValid = false;
					}
				}
				if (empty($frequency)) {
					$errArrReq[] = '"Frequency"';
					$isValid = false;
				} else {
					// if the value of Frequency Column is not equal to the one of the choices, then display the error
					$frequency_choices = array('1,0,0,0,0', '1,1,0,0,0', '1,1,1,0,0', '1,1,1,1,0', '1,1,1,1,1', '0,1,0,0,0', '0,0,1,0,0', '0,0,0,1,0', '0,0,0,0,1');
					if (!in_array($frequency, $frequency_choices)) {
						$_SESSION['eMsg'][] = "In Row {$rowPos}, Column \"Frequency\" should be one of these only: (1,0,0,0,0), (1,1,0,0,0), (1,1,1,0,0), (1,1,1,1,0), (1,1,1,1,1), (0,1,0,0,0), (0,0,1,0,0), (0,0,0,1,0), (0,0,0,0,1).";
						$isValid = false;
					}
				}
//				if (empty($total_amount) && $type == 'Fixed Amount') {
//					$_SESSION['eMsg'][] = "In Row {$rowPos}, Column \"Total Amount\" should not be blank if \"Type\" is \"Fixed Amount\".";
//					$isValid = false;
//				}
					if (!empty($total_amount) && !is_numeric($total_amount)) {
						$_SESSION['eMsg'][] = "In Row {$rowPos}, Column \"Total Amount\" is not valid. It should be Numbers Only.";
						$isValid = false;
					}
//				if (empty($amount_per_pay_period)) {
//					$errArrReq[] = '"Amount per Pay Period"';
//					$isValid = false;
//				} else {
					if (!is_numeric($amount_per_pay_period)) {
						$_SESSION['eMsg'][] = "In Row {$rowPos}, Column \"Amount per Pay Period\" is not valid. It should be Numbers Only.";
						$isValid = false;
					}
//				}
			}
			
			if (count($errArrReq) > 0) {
				$_SESSION['eMsg'][] = "In Row {$rowPos}, Column " . implode(", ", $errArrReq) . " is required.";
			}
			$_SESSION['eMsg'] > 0 ? $isValid = false : '';
		} // this is the end of for loop
		
		if ( ($rowPos - (6 + $skipped_row) == 0) ) {
			$_SESSION['eMsg'][] = "Invalid record count. Data is less than 6 rows.";
			$isValid = false;
		}
		return $isValid;
	}
	
	function doReadAndInsertUPBenefitsAndDeduction($fname_ = NULL) {
		if (is_null($fname_)) {
			return NULL;
		}
		$objClsExcelReader = new Spreadsheet_Excel_Reader();
		$objClsExcelReader->read($fname_);
		return $objClsExcelReader->sheets;
	}

	/**
	 * Upload Loan
	 *
	 */
	function doSaveImportBenefitsAndDeduction($pData_ = array()) {
		if (count($pData_) == 0) {
			return NULL;
		}
		$badQtyCtr = 0;
		$rowPos = 6;
		$rowCnt = $this->xlsData[0]['numRows'];
		for ($rowPos = 6; $rowPos <= $rowCnt; $rowPos++) {
			$cellDataArr = $this->xlsData[0]['cells'][$rowPos];
			$eMsg = array();
			$isValid = true;
			
			// Variable Asigning and Triming
			
			$emp_idnum = trim($cellDataArr[1]);
			$pay_element = trim($cellDataArr[3]);
			$type = ucwords(strtolower(trim($cellDataArr[4])));
			if ($type == 'Fixed Amount') {
				$type = 0;
			} elseif ($type == 'Free Amount') {
				$type = 1;
			}
			$start_month_and_year = trim($cellDataArr[5]);
			$end_month_and_year = trim($cellDataArr[6]);
			$frequency = trim($cellDataArr[7]);
			$total_amount = trim($cellDataArr[8]);
			$amount_per_pay_period = trim($cellDataArr[9]);
			
			if ( 	
					(empty($emp_idnum)) && 
					(empty($pay_element)) && 
					(empty($type)) &&
					(empty($start_month_and_year)) &&
					(empty($frequency))/* &&
					(empty($total_amount)) &&
					(empty($amount_per_pay_period))*/
				) {
				$skipped_row++;
			} else {
				/* =========== Loan =========== */
				$loanparam = "emp_id, emp_idnum";
				$qryloan = array();
				$qryloan[] = "emp_idnum = '{$emp_idnum}'";
				$table = "emp_masterfile";
				$emp_id = $this->getIDByParameter($emp_idnum,$loanparam,$qryloan,$table);
	//			printa($emp_id);
				/* =========== Pay Element =========== */
				$pay_element_param = "psa_id, psa_name";
				$qryloan = array();
				$qryloan[] = "psa_name = '{$pay_element}'";
				$table = "payroll_ps_account";
				$psa_id = $this->getIDByParameter($pay_element,$pay_element_param,$qryloan,$table);
				
				//$dagdagan == 2?exit:$dagdagan++;
					$flds = array(); // Benefits and Deduction
					$flds[] = "emp_id='{$emp_id['emp_id']}'";
					$flds[] = "psa_id='{$psa_id['psa_id']}'";
					$flds[] = "ben_isfixed='{$type}'";
					$flds[] = "ben_startdate='{$start_month_and_year}'";
					$flds[] = "ben_enddate='{$end_month_and_year}'";
					$flds[] = "ben_periodselection='{$frequency}'";
					$flds[] = "ben_amount='{$total_amount}'";
					$flds[] = "ben_payperday='{$amount_per_pay_period}'";
					$flds[] = "ben_addwho='".AppUser::getData('user_name')."'";
					$fields = implode(", ",$flds);
					$sql = "INSERT INTO emp_benefits SET $fields";
					$emp_id['emp_id'] != 0 ? $this->conn->Execute($sql) : '';
					$lastInsertedID = $this->conn->Insert_ID();
				
				switch ($frequency) {
					case '1,0,0,0,0':
						$number_first_combination = 1;
						break;
					case '1,1,0,0,0':
						$number_first_combination = 2;
						break;
					case '1,1,1,0,0':
						$number_first_combination = 3;
						break;
					case '1,1,1,1,0':
						$number_first_combination = 4;
						break;
					case '1,1,1,1,1':
						$number_first_combination = 5;
						break;
					// Other Combination
					case '0,1,0,0,0':
						$number_second_combination = 2;
						break;
					case '0,0,1,0,0':
						$number_second_combination = 3;
						break;
					case '0,0,0,1,0':
						$number_second_combination = 4;
						break;
					case '0,0,0,0,1':
						$number_second_combination = 5;
						break;
					default:
						break;
				}
				
				if ($number_first_combination) {
					for ($count = 1; $count <= $number_first_combination; $count++) {
						$flds2 = array(); // Loan
						$flds2[] = "emp_id='{$emp_id['emp_id']}'";
						$flds2[] = "ben_id='{$lastInsertedID}'";
						$flds2[] = "bldsched_period='{$count}'";
						$fields2 = implode(", ",$flds2);
						$sql2 = "INSERT INTO period_benloanduc_sched SET $fields2";
						$this->conn->Execute($sql2);
					}
				} elseif ($number_second_combination) {
					for ($count = 1; $count <= $number_second_combination; $count++) {
						if ($count < $number_second_combination) {
							continue;
						} else {
							$flds2 = array(); // Loan
							$flds2[] = "emp_id='{$emp_id['emp_id']}'";
							$flds2[] = "ben_id='{$lastInsertedID}'";
							$flds2[] = "bldsched_period='{$count}'";
							$fields2 = implode(", ",$flds2);
							$sql2 = "INSERT INTO period_benloanduc_sched SET $fields2";
							$this->conn->Execute($sql2);
						}
					}
				}
			}
		}
		$_SESSION['eMsg'] = "Successfully Uploaded! Number of Record(s) Imported: ".( $rowPos - (6 + $skipped_row) );
	}
	
	function getIDByParameter($var = NULL,$qrySelect = 0,$qryWhere = 0,$table = NULL) {
		if (is_null($var)) {
			return 0;
		}
		if (is_null($table)) {
			return 0;
		}
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

}

?>