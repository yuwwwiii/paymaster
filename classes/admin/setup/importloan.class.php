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
class clsImportLoan {

	var $conn;
	var $fieldMap;
	var $Data;

	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsImportLoan object
	 */
	function clsImportLoan($dbconn_ = NULL) {
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
		
		$this->xlsData = $this->doReadAndInsertUPLoan($pData_['uptahead_file']['tmp_name']);
		
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
			$type = trim($cellDataArr[4]);
			$date_granted = trim($cellDataArr[7]);
			$start_month_and_year = trim($cellDataArr[8]);
			$end_date = trim($cellDataArr[9]);
			$number_of_months = trim($cellDataArr[10]);
			$principal_amount = trim($cellDataArr[11]);
			$monthly_amortization = trim($cellDataArr[14]);
			$frequency = trim($cellDataArr[15]);
			$amount_per_pay_period = trim($cellDataArr[16]);
			$balance_as_of_last_deduction = trim($cellDataArr[17]);
			
			// Required Fields
			if ( 	
					(empty($emp_idnum)) &&
					(empty($pay_element)) &&
					(empty($type)) &&
					(empty($date_granted)) &&
					(empty($start_month_and_year)) &&
					(empty($number_of_months)) &&
					(empty($principal_amount)) &&
					(empty($monthly_amortization)) &&
					(empty($frequency)) &&
					(empty($amount_per_pay_period))
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
					if ( is_numeric($type) ) {
						$_SESSION['eMsg'][] = "In Row {$rowPos}, Column \"Pay Element Type\" is not valid.";
						$isValid = false;
					}
				}
				if (empty($date_granted)) {
					$errArrReq[] = '"Date Granted"';
					$isValid = false;
				} else {
					if (strlen($date_granted) != 10) {
						$_SESSION['eMsg'][] = "In Row {$rowPos}, Column \"Date Granted\", \"{$date_granted}\" is invalid Date format. The format should be YYYY-MM-DD.";
						$isValid = false;
					} elseif ($date_granted == '0000-00-00') {
						$_SESSION['eMsg'][] = "In Row {$rowPos}, Column \"Date Granted\", \"{$date_granted}\" is not a valid Date.";
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
						$_SESSION['eMsg'][] = "In Row {$rowPos}, Column \"Start Date\", \"{$start_month_and_year}\" is not a valid Date.";
						$isValid = false;
					}
				}
				if (!empty($end_date)) {
					if (strlen($end_date) != 10) {
						$_SESSION['eMsg'][] = "In Row {$rowPos}, Column \"End Date\", \"{$end_date}\" is invalid Date format. The format should be YYYY-MM-DD.";
						$isValid = false;
					} elseif ($end_date == '0000-00-00') {
						$_SESSION['eMsg'][] = "In Row {$rowPos}, Column \"End Date\", \"{$end_date}\" is not a valid Date.";
						$isValid = false;
					}
				}
				if (empty($number_of_months)) {
					$errArrReq[] = '"No. of Months"';
					$isValid = false;
				} else {
					if (!is_numeric($number_of_months)) {
						$_SESSION['eMsg'][] = "In Row {$rowPos}, Column \"No. of Months\" is not valid. It should be Numbers Only.";
						$isValid = false;
					}
				}
				if (empty($principal_amount)) {
					$errArrReq[] = '"Principal Amount"';
					$isValid = false;
				} else {
					if (!is_numeric($principal_amount)) {
						$_SESSION['eMsg'][] = "In Row {$rowPos}, Column \"Principal Amount\" is not valid. It should be Numbers Only.";
						$isValid = false;
					}
				}
				if (empty($monthly_amortization)) {
					$errArrReq[] = '"Monthly Amortization"';
					$isValid = false;
				} else {
					if (!is_numeric($monthly_amortization)) {
						$_SESSION['eMsg'][] = "In Row {$rowPos}, Column \"Monthly Amortization\" is not valid. It should be Numbers Only.";
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
				if (empty($amount_per_pay_period)) {
					$errArrReq[] = '"Amount per Pay Period"';
					$isValid = false;
				} else {
					if (!is_numeric($amount_per_pay_period)) {
						$_SESSION['eMsg'][] = "In Row {$rowPos}, Column \"Amount per Pay Period\" is not valid. It should be Numbers Only.";
						$isValid = false;
					}
				}
				if (!empty($balance_as_of_last_deduction) && !is_numeric($balance_as_of_last_deduction)) {
					$_SESSION['eMsg'][] = "In Row {$rowPos}, Column \"Balance as of Last Deduction\" is not valid. It should be Numbers Only.";
					$isValid = false;
				}
			}
			
			if (count($errArrReq) > 0) {
				$_SESSION['eMsg'][] = "In Row {$rowPos}, Column " . implode(", ", $errArrReq) . " is required.";
			}
			$_SESSION['eMsg'] > 0 ? $isValid = false : '';
		} // this is the end of for loop
		
		if (($rowPos - (6 + $skipped_row) == 0)) {
			$_SESSION['eMsg'][] = "Invalid record count. Data is less than 6 rows.";
			$isValid = false;
		}
		return $isValid;
	}
	
	function doReadAndInsertUPLoan($fname_ = NULL) {
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
	function doSaveImportLoan($pData_ = array()) {
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
			$type = trim($cellDataArr[4]);
			$date_granted = trim($cellDataArr[7]);
			$start_month_and_year = trim($cellDataArr[8]);
			$end_date = trim($cellDataArr[9]);
			$number_of_months = trim($cellDataArr[10]);
			$principal_amount = trim($cellDataArr[11]);
			$monthly_amortization = trim($cellDataArr[14]);
			$frequency = trim($cellDataArr[15]);
			$amount_per_pay_period = trim($cellDataArr[16]);
			$balance_as_of_last_deduction = trim($cellDataArr[17]);
			$subInteres = $cellDataArr[12] / 100;
			$subLOANInteres = $cellDataArr[11] * $subInteres;
			IF($subLOANInteres > 0){
				$totalLOAN = $cellDataArr[11] + $subLOANInteres;
			}ELSE{
				$totalLOAN = $cellDataArr[11] + $cellDataArr[13];
			}
			
			// Required Fields
			if ( 	
					(empty($emp_idnum)) &&
					(empty($pay_element)) &&
					(empty($type)) &&
					(empty($date_granted)) &&
					(empty($start_month_and_year)) &&
					(empty($number_of_months)) &&
					(empty($principal_amount)) &&
					(empty($monthly_amortization)) &&
					(empty($frequency)) &&
					(empty($amount_per_pay_period))
				) {
				$skipped_row++;
			} else {
				/* =========== Employee ID =========== */
				$loanparam = "emp_id, emp_idnum";
				$qryloan = array();
				$qryloan[] = "emp_idnum = '{$cellDataArr[1]}'";
				$qryloan[] = "emp_stat != '0'";
				$table = "emp_masterfile";
				$emp_id = $this->getIDByParameter($cellDataArr[1],$loanparam,$qryloan,$table);
				
				/* =========== Pay Element =========== */
				$loanparam = "psa_id, psa_name";
				$qryloan = array();
				$qryloan[] = "psa_name = '{$cellDataArr[3]}'";
				$table = "payroll_ps_account";
				$psa_id = $this->getIDByParameter($cellDataArr[3],$loanparam,$qryloan,$table);
	
				/* =========== Loan Type =========== */
				$loanparam = "loantype_id, loantype_desc";
				$qryloan = array();
				$qryloan[] = "loantype_desc = '{$cellDataArr[4]}'";
				$table = "loan_type";
				$loantype_id = $this->getIDByParameter($cellDataArr[4],$loanparam,$qryloan,$table);
				
				//$dagdagan == 2?exit:$dagdagan++;
				
				$flds = array(); // Loan
				$flds[] = "emp_id='{$emp_id['emp_id']}'";
				$flds[] = "psa_id='{$psa_id['psa_id']}'";
				$flds[] = "loantype_id='{$loantype_id['loantype_id']}'";
	
				$date = new DateTime($cellDataArr[7]);
				$flds[] = "loan_dategrant='{$date->format('Y-m-d')}'";
				$flds[] = "loan_datepromissory='{$date->format('Y-m-d')}'";
				
				$date = new DateTime($cellDataArr[8]);
				$flds[] = "loan_startdate='{$date->format('F Y')}'";
				
				$date = new DateTime($cellDataArr[9]);
				$flds[] = "loan_enddate='{$date->format('F Y')}'";
	
				$flds[] = "loan_addwho='".AppUser::getData('user_name')."'";
				$flds[] = "loan_numofmonths='{$cellDataArr[10]}'";
				$flds[] = "loan_principal='{$cellDataArr[11]}'";
				$flds[] = "loan_monthly_amortization='{$cellDataArr[14]}'";
				$flds[] = "loan_periodselection='{$cellDataArr[15]}'";
				$flds[] = "loan_payperperiod='{$cellDataArr[16]}'";
				$flds[] = "loan_balance='{$cellDataArr[17]}'";
				$flds[] = "loan_interestamount='{$cellDataArr[13]}'";
				$flds[] = "loan_interestperc='{$cellDataArr[12]}'";
				$flds[] = "loan_total='".$totalLOAN."'";
				$flds[] = "loan_voucher_no='{$cellDataArr[5]}'";
				$fields = implode(", ",$flds);
				$sql = "INSERT INTO loan_info SET $fields";
				$emp_id['emp_id'] != 0 ? $this->conn->Execute($sql) : '';
				$lastInsertedID = $this->conn->Insert_ID();
				
				switch ($cellDataArr[15]) {
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
						$flds2[] = "loan_id='{$lastInsertedID}'";
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
							$flds2[] = "loan_id='{$lastInsertedID}'";
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
	
	function getIDByParameter($var = NULL, $qrySelect = 0, $qryWhere = 0, $table = NULL) {
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
	
	function doDownloadTemplate($pay_elements_= array(), $loan_type_ = array()){
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		$sheet = $objPHPExcel->getActiveSheet();
		//$sheet->getProtection()->setSheet(true);
		$filename = "LoanTemplate.xls";
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		$objReader = PHPExcel_IOFactory::createReader('Excel5');
		$objPHPExcel = $objReader->load("importtemplate/LoanTemplate.xls");
		
		$objPHPExcel->createSheet();
		$objPHPExcel->setActiveSheetIndex(1);
		$objPHPExcel->getActiveSheet()->setTitle("PayElements");
		$row=2;
		foreach($pay_elements_ as $pe){
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $pe);
			$row++;
		}
		$objPHPExcel->createSheet();
		$objPHPExcel->setActiveSheetIndex(2);
		$objPHPExcel->getActiveSheet()->setTitle("LoanType");
		$row=2;
		foreach($loan_type_ as $lt){
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $lt['loantype_desc']);
			$row++;
		}
		$objPHPExcel->setActiveSheetIndex(0);
		$objValidation = $objPHPExcel->getActiveSheet()->getCell('C6')->getDataValidation();
		$objValidation->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
		$objValidation->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
		$objValidation->setAllowBlank(false);
		$objValidation->setShowInputMessage(true);
		$objValidation->setShowErrorMessage(true);
		$objValidation->setShowDropDown(true);
		$objValidation->setFormula1('PayElements!$A$2:$A$'.$row);
		for($i=6;$i<=1000;$i++){
			$objPHPExcel->getActiveSheet()->getCell('C'.$i)->setDataValidation($objValidation);
		
		}
		
		$objValidation2 = $objPHPExcel->getActiveSheet()->getCell('D6')->getDataValidation();
		$objValidation2->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
		$objValidation2->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
		$objValidation2->setAllowBlank(false);
		$objValidation2->setShowInputMessage(true);
		$objValidation2->setShowErrorMessage(true);
		$objValidation2->setShowDropDown(true);
		$objValidation2->setFormula1('LoanType!$A$2:$A$'.$row);
		for($t=6;$t<=1000;$t++){
			$objPHPExcel->getActiveSheet()->getCell('D'.$i)->setDataValidation($objValidation);
		
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

}

?>