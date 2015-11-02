<?php
/**
 * Initial Declaration
 */


/**
 * Class Module
 *
 * @author  JIM
 *
 */
class clsImport_PE {

	var $conn;
	var $fieldMap;
	var $Data;

	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsImport_PE object
	 */
	function clsImport_PE ($dbconn_ = null) {
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
	function dbFetch ($id_ = "") {
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
	function doPopulateData ($pData_ = array(),$isForm_ = false) {
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
	function doValidateData ($pData_ = array()) {
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
		} elseif ($mime != 'application/vnd.ms-excel') {
			$_SESSION['eMsg'][] = "Invalid File Type. The File Extension should only be .xls";
			$isValid = false;
			return $isValid;
		}
		$this->xlsData = $this->doReadAndInsertUPPayElement($pData_['uptahead_file']['tmp_name']);
		if ($this->xlsData[0]['numRows'] < 6) {
			$_SESSION['eMsg'][] = "Invalid record count. Data is less than 6 rows.";
			$isValid = false;
		}
		
		// Import Validation
		if (count($pData_) == 0) {
			return null;
		}
		$badQtyCtr = 0;
		$rowPos = 6;
		$rowCnt = $this->xlsData[0]['numRows'];
		for ($rowPos; $rowPos <= $rowCnt; $rowPos++) {
			$cellDataArr = $this->xlsData[0]['cells'][$rowPos];
			
			$psa_status = trim($cellDataArr[1]);
			$psa_type = trim($cellDataArr[2]);
			$psa_clsfication = trim($cellDataArr[3]);
			$psa_procode = trim($cellDataArr[4]);
			$psa_name = trim($cellDataArr[5]);
			$psa_order = trim($cellDataArr[6]);
			$psa_tax = trim($cellDataArr[7]);
			$psa_statutory = trim($cellDataArr[8]);
			
			// kapag may value sa kahit isang column
			if ( ($psa_status != NULL) || ($psa_type != NULL) || ($psa_clsfication != NULL) || ($psa_procode != NULL) || ($psa_name != NULL) || ($psa_tax != NULL) || ($psa_statutory != NULL) ) {
				// icheck yung required column
				if ($psa_status == NULL) {
					$_SESSION['eMsg'][] = "In Row {$rowPos}, Column \"Status\" is required.";
					$isValid = false;
				} else {
					if ( ($psa_status != 'Enabled') && ($psa_status != 'Disabled') ) {
						$_SESSION['eMsg'][] = "In Row {$rowPos}, Column \"Status\" should be Enabled or Disabled only.";
						$isValid = false;
					}
				}
				if ($psa_type == NULL) {
					$_SESSION['eMsg'][] = "In Row {$rowPos}, Column \"Type\" is required.";
					$isValid = false;
				} else {
					if ( ($psa_type !== 'Earning') && ($psa_type !== 'Employee Deduction') && ($psa_type !== 'Employer Deduction') && ($psa_type !== 'Total') && ($psa_type !== 'Accrual') ) {
						$_SESSION['eMsg'][] = "In Row {$rowPos}, Column \"Type\" should be one of these only: 'Earning', 'Employee Deduction', 'Employer Deduction', 'Total' or 'Accrual'.";
						$isValid = false;
					}
				}
				if ($psa_clsfication == NULL) {
					$_SESSION['eMsg'][] = "In Row {$rowPos}, Column \"Classification\" is required.";
					$isValid = false;
				} else {
					/*if ( ($psa_clsfication != 'N/A') && ($psa_clsfication != 'Ben & Deduc') && ($psa_clsfication != 'OT List') && ($psa_clsfication != 'TUA') && ($psa_clsfication != 'Govt Contrib') && ($psa_clsfication != 'Govt/Reg Loan') ) {*/
					
					$psa_clsfication_choices = array('N/A', 'Ben & Deduc', 'OT List', 'TUA', 'Govt Contrib', 'Govt/Reg Loan');
					// if the value of the Classification Column is not equal to the one of the choices then display the error 
					if (!in_array($psa_clsfication, $psa_clsfication_choices)) {
						$_SESSION['eMsg'][] = "In Row {$rowPos}, Column \"Classification\" should be one of these only: 'N/A', 'Ben & Deduc', 'OT List', 'TUA', 'Govt Contrib' or 'Govt/Reg Loan'.";
						$isValid = false;
					}
				}
				if ($psa_procode == NULL) {
					$_SESSION['eMsg'][] = "In Row {$rowPos}, Column \"Property Code\" is required.";
					$isValid = false;
				} else {
					if ( ($psa_procode != 'Bonus Code') && ($psa_procode != 'Compensation Code') && ($psa_procode != 'Non Taxable Code') && ($psa_procode != 'Recurring Tax Projection Code') && ($psa_procode != 'De Minimis Code') ) {
						$_SESSION['eMsg'][] = "In Row {$rowPos}, Column \"Property Code\" should be one of these only: 'Bonus Code', 'Compensation Code', 'Non Taxable Code', 'Recurring Tax Projection Code' or 'De Minimis Code'.";
						$isValid = false;
					}
				}
				if ($psa_name == NULL) {
					$_SESSION['eMsg'][] = "In Row {$rowPos}, Column \"Name\" is required.";
					$isValid = false;
				}
				if ($psa_order != NULL) {
					if (!is_numeric($psa_order)) {
						$_SESSION['eMsg'][] = "In Row {$rowPos}, Column \"Order\" should be number only.";
						$isValid = false;
					}
				}
				if ($psa_tax == NULL) {
					$_SESSION['eMsg'][] = "In Row {$rowPos}, Column \"Taxable\" is required.";
					$isValid = false;
				} else {
					if ( ($psa_tax != 'Yes') && ($psa_tax != 'No') ) {
						$_SESSION['eMsg'][] = "In Row {$rowPos}, Column \"Taxable\" should be Yes or No only.";
						$isValid = false;
					}
				}
				if ($psa_statutory == NULL) {
					$_SESSION['eMsg'][] = "In Row {$rowPos}, Column \"Statutory Contribution\" is required.";
					$isValid = false;
				} else {
					if ( ($psa_statutory != 'Yes') && ($psa_statutory != 'No') ) {
						$_SESSION['eMsg'][] = "In Row {$rowPos}, Column \"Statutory Contribution\" should be Yes or No only.";
						$isValid = false;
					}
				}
			}
			$_SESSION['eMsg'] > 0 ? $isValid = false : '';
		}
		return $isValid;
	}
	
	function doReadAndInsertUPPayElement ($fname_ = null) {
		if (is_null($fname_)) {
			return null;
		}
		$objClsExcelReader = new Spreadsheet_Excel_Reader();
		$objClsExcelReader->read($fname_);
		return $objClsExcelReader->sheets;
	}

	/**
	 * Upload Loan
	 *
	 */
	function doSaveImportPayElement ($pData_ = array()) {
		if (count($pData_) == 0) {
			return null;
		}
		$badQtyCtr = 0;
		$rowPos = 6;
		$rowCnt = $this->xlsData[0]['numRows'];
		for ($rowPos = 6; $rowPos <= $rowCnt; $rowPos++) {
			$cellDataArr = $this->xlsData[0]['cells'][$rowPos];
			$eMsg = array();
			$isValid = true;

				$flds = array(); // Pay Element
				// Status
				$psa_status = trim($cellDataArr[1]);
				switch ($psa_status) {
					case 'Enabled':
						$psa_status_for_database = 1;
						break;
					case 'Disabled':
						$psa_status_for_database = 0;
						break;
					default:
						break;
				}
				$flds[] = "psa_status='".$psa_status_for_database."'";
				
				// Type
				$psa_type = trim($cellDataArr[2]);
				switch ($psa_type) {
					case 'Earning':
						$psa_type_for_database = 1;
						break;
					case 'Employee Deduction':
						$psa_type_for_database = 2;
						break;
					case 'Employer Deduction':
						$psa_type_for_database = 3;
						break;
					case 'Total':
						$psa_type_for_database = 4;
						break;
					case 'Accrual':
						$psa_type_for_database = 5;
						break;
					default:
						break;
				}
				$flds[] = "psa_type='".$psa_type_for_database."'";
				
				// Classification
				$psa_clsfication = trim($cellDataArr[3]);
				switch ($psa_clsfication) {
					case 'N/A':
						$psa_clsfication_for_database = 0;
						break;
					case 'Ben & Deduc':
						$psa_clsfication_for_database = 1;
						break;
					case 'OT List':
						$psa_clsfication_for_database = 2;
						break;
					case 'TUA':
						$psa_clsfication_for_database = 3;
						break;
					case 'Govt Contrib':
						$psa_clsfication_for_database = 4;
						break;
					case 'Govt/Reg Loan':
						$psa_clsfication_for_database = 5;
						break;
				}
				$flds[] = "psa_clsfication='".$psa_clsfication_for_database."'";
				
				// Property Code
				$psa_procode = trim($cellDataArr[4]);
				switch ($psa_procode) {
					case 'Bonus Code':
						$psa_procode_for_database = 1;
						break;
					case 'Compensation Code':
						$psa_procode_for_database = 2;
						break;
					case 'Non Taxable Code':
						$psa_procode_for_database = 3;
						break;
					case 'Recurring Tax Projection Code':
						$psa_procode_for_database = 4;
						break;
					case 'De Minimis Code':
						$psa_procode_for_database = 5;
						break;
					default:
						break;
				}
				$flds[] = "psa_procode='".$psa_procode_for_database."'";
				
				// Name
				$psa_name = trim($cellDataArr[5]);
				$flds[] = "psa_name='".$psa_name."'";
				
				// Order
				$psa_order = trim($cellDataArr[6]);
				$flds[] = "psa_order='".$psa_order."'";
				
				// Taxable
				$psa_tax = trim($cellDataArr[7]);
				switch ($psa_tax) {
					case 'Yes':
						$psa_tax_for_database = 1;
						break;
					case 'No':
						$psa_tax_for_database = 0;
						break;
					default:
						break;
				}
				$flds[] = "psa_tax='".$psa_tax_for_database."'";
				
				// Statutory Contribution
				$psa_statutory = trim($cellDataArr[8]);
				switch ($psa_statutory) {
					case 'Yes':
						$psa_statutory_for_database = 1;
						break;
					case 'No':
						$psa_statutory_for_database = 0;
						break;
					default:
						break;
				}
				$flds[] = "psa_statutory='".$psa_statutory_for_database."'";
				
				$fields = implode(", ",$flds);
				$sql = "INSERT INTO payroll_ps_account SET $fields";
				// if all required column has a value then execute sql, else don't execute
				if ( ($psa_status != NULL) && ($psa_type != NULL) && ($psa_clsfication != NULL) && ($psa_procode != NULL) && ($psa_name != NULL) && ($psa_tax != NULL) && ($psa_statutory != NULL)) {
					$this->conn->Execute($sql);
				} else {
					$notExecuted++;
				}
		}
		$_SESSION['eMsg'] = "Successfully Uploaded! Number of Records Imported: ".( $rowPos - (6 + $notExecuted) );
	}
	
	function getIDByParameter ($var = null,$qrySelect = 0,$qryWhere = 0,$table = null) {
		if (is_null($var)) {
			return 0;
		}
		if (is_null($table)) {
			return 0;
		}
		$qry = (count($qrySelect)>0)?$qrySelect:"*";
		$criteria = (count($qryWhere)>0)?' WHERE '.implode(' AND ',$qryWhere):'';
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