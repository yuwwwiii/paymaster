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
class clsImportCompBanks {

	var $conn;
	var $fieldMap;
	var $Data;

	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsImportCompBanks object
	 */
	function clsImportCompBanks ($dbconn_ = null) {
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
		$this->xlsData = $this->doReadAndInsertUPCompBank($pData_['uptahead_file']['tmp_name']);
		if ($this->xlsData[0]['numRows'] < 6) {
			$_SESSION['eMsg'][] = "Invalid record count. Data is less than 6 rows.";
			$isValid = false;
		}
		return $isValid;
	}
	
	function doReadAndInsertUPCompBank ($fname_ = null) {
		if (is_null($fname_)) {
			return null;
		}
		$objClsExcelReader = new Spreadsheet_Excel_Reader();
		$objClsExcelReader->read($fname_);
		return $objClsExcelReader->sheets;
	}
	
	/**
	 * Save New
	 *
	 */
	function doSaveImportCompBanks ($pData_ = array()) {
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
			$comp_id = $this->getCompIDByCompCODE($cellDataArr[1]); //get company ID
			$bank_id = $this->getBankListIDByBankName($cellDataArr[3]); //get Bank ID
			$baccntype_id = $this->getBAccnTypeIDByTypeName($cellDataArr[4]); //get Bank Account Type ID
			
			$flds = array();
			$flds[] = "baccntype_id='".$baccntype_id['baccntype_id']."'";
			$flds[] = "comp_id='".$comp_id['comp_id']."'";
			$flds[] = "banklist_id='".$bank_id['banklist_id']."'";
			$flds[] = "bank_routing_number='".$cellDataArr[5]."'";
			$flds[] = "bank_company_code='".$cellDataArr[6]."'";
			$flds[] = "bank_acct_no='".$cellDataArr[7]."'";
			$flds[] = "bank_acct_name='".$cellDataArr[8]."'";
			$flds[] = "bank_ceiling_amount='".$cellDataArr[9]."'";
			$flds[] = "bank_swift_code='".$cellDataArr[10]."'";
			$flds[] = "bank_branch='".$cellDataArr[11]."'";
			$flds[] = "bank_contact='".$cellDataArr[12]."'";
			$flds[] = "bank_isactive='1'";
			$flds[] = "bank_addwho='".AppUser::getData('user_name')."'";
			$fields = implode(", ",$flds);
			$sql = "INSERT INTO bank_info SET $fields";
			$this->conn->Execute($sql);
		}
		$_SESSION['eMsg']="Successfully Uploaded! Number of Records Imported: ".($rowPos-6);
	}
	
	/**
	 * fetched company id
	 * @param $comp_id_
	 */
	function getCompIDByCompCODE ($comp_code_ = null) {
		if (is_null($comp_code_)) {
			return 1;
		}
		$qry = array();
		$qry[] = "comp_code = '".$comp_code_."'";
		$criteria = (count($qry)>0)?" WHERE ".implode(" AND ",$qry):"";
		$sql = "SELECT comp_id, comp_code, comp_name FROM company_info $criteria";
		$rsResult = $this->conn->Execute($sql);
		if (!$rsResult->EOF) {
			return $rsResult->fields;
		} else {
			return 1;
		}
	}
	
	/**
	 * fetched Bank Account Type ID
	 * @param $comp_id_
	 */
	function getBAccnTypeIDByTypeName ($baccntype_name_ = null) {
		if (is_null($baccntype_name_)) {
			return 1;
		}
		$qry = array();
		$qry[] = "baccntype_name = '".$baccntype_name_."'";
		$criteria = (count($qry)>0)?" WHERE ".implode(" AND ",$qry):"";
		$sql = "SELECT baccntype_id, baccntype_name FROM bnkaccnt_type $criteria";
		$rsResult = $this->conn->Execute($sql);
		if (!$rsResult->EOF) {
			return $rsResult->fields;
		} else {
			return 1;
		}
	}
	
	/**
	 * fetched Bank ID
	 * @param $comp_id_
	 */
	function getBankListIDByBankName ($banklist_name_ = null) {
		if (is_null($banklist_name_)) {
			return 1;
		}
		$qry = array();
		$qry[] = "banklist_name = '".$banklist_name_."'";
		$criteria = (count($qry)>0)?" WHERE ".implode(" AND ",$qry):"";
		$sql = "SELECT banklist_id, banklist_name FROM bank_list $criteria";
		$rsResult = $this->conn->Execute($sql);
		if (!$rsResult->EOF) {
			return $rsResult->fields;
		} else {
			return 1;
		}
	}
}
?>