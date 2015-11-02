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
class clsImportTaxDepnts {

	var $conn;
	var $fieldMap;
	var $Data;

	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsImportTaxDepnts object
	 */
	function clsImportTaxDepnts ($dbconn_ = null) {
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
		$this->xlsData = $this->doReadAndInsertUPTDepentsFile($pData_['uptahead_file']['tmp_name']);
		if ($this->xlsData[0]['numRows'] < 5) {
			$_SESSION['eMsg'][] = "Invalid record count. Data is less than 5 rows.";
			$isValid = false;
		}
		return $isValid;
	}
	
	function doReadAndInsertUPTDepentsFile ($fname_ = null) {
		if (is_null($fname_)) {
			return null;
		}
		$objClsExcelReader = new Spreadsheet_Excel_Reader();
		$objClsExcelReader->read($fname_);
		return  $objClsExcelReader->sheets;
	}

	/**
	 * Save New
	 *
	 */
	function doSaveImportTaxDepents ($pData_ = array()) {
		if (count($pData_) == 0) {
			return null;
		}
		$badQtyCtr = 0;
		$rowPos = 5;
		$rowCnt = $this->xlsData[0]['numRows'];
		for ($rowPos = 5; $rowPos <= $rowCnt; $rowPos++) {
			$cellDataArr = $this->xlsData[0]['cells'][$rowPos];
			$eMsg = array();
			$isValid = true;
			//===========Employee Info===========
			$empparam = "emp_id,emp_idnum";
			$table = "emp_masterfile";
			$qryemp = array();
			$qryemp[] = "emp_idnum = '".$cellDataArr[2]."'";
			$emp_id = $this->getIDByParamiter($cellDataArr[2],$qryemp,$empparam,$table);
//			printa($emp_id);

			$flds = array();
			$flds[] = "emp_id='".$emp_id['emp_id']."'";
			$flds[] = "depnd_lname='".$cellDataArr[4]."'";
			$flds[] = "depnd_fname='".$cellDataArr[5]."'";
			$flds[] = "depnd_mname='".$cellDataArr[6]."'";
			$flds[] = "depnd_bdate='".date('Y-m-d', strtotime($cellDataArr[7]))."'";
			$flds[] = "depnd_relationship='".$cellDataArr[8]."'";
			$flds[] = "depnd_addwho='".AppUser::getData('user_name')."'";
			$fields = implode(", ",$flds);
			$sql = "insert into dependent_info set $fields";
			$this->conn->Execute($sql);
		}
		$_SESSION['eMsg']="Successfully Uploaded! Number of Records Imported: ".($rowPos-5);
	}
	
	function getIDByParamiter ($var = null,$qryWhere = 0,$qrySelect = 0,$table_ = null) {
		if (is_null($var)){
			return 0;
		}
		if (is_null($table_)){
			return 0;
		}
		$qry = (count($qrySelect)>0)?$qrySelect:"*";
		$criteria = (count($qryWhere)>0)?" where ".implode(" and ",$qryWhere):"";
		$sql = "Select $qry from $table_ $criteria";
		$rsResult = $this->conn->Execute($sql);
		if (!$rsResult->EOF) {
			return $rsResult->fields;
		} else {
			return 0;
		}
	}
}
?>