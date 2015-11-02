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
class clsImportDept{

	var $conn;
	var $fieldMap;
	var $Data;

	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsImportDept object
	 */
	function clsImportDept($dbconn_ = null){
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
	function dbFetch($id_ = ""){
		$sql = "";
		$rsResult = $this->conn->Execute($sql,array($id_));
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
	function doValidateData($pData_ = array()){
		$isValid = true;
		$this->xlsData = $this->doReadAndInsertUPDeptFile($pData_['uptahead_file']['tmp_name']);
		if ($this->xlsData[0]['numRows'] < 5) {
			$_SESSION['eMsg'][] = "Invalid record count. Data is less than 5 rows.";
			$isValid = false;
		}
		return $isValid;
	}
	
	function doReadAndInsertUPDeptFile($fname_ = null){
		if (is_null($fname_)) {
			return null;
		}
		$objClsExcelReader = new Spreadsheet_Excel_Reader();
		$objClsExcelReader->read($fname_);
		return  $objClsExcelReader->sheets;
	}
	
	/**
	 * Upload Dept. Info
	 *
	 */
	function doSaveImportDept($pData_ = array()){
		if (count($pData_) == 0) {
			return null;
		}
		$badQtyCtr = 0;
		$rowPos = 5;
		$rowCnt = $this->xlsData[0]['numRows'];
		for ($rowPos = 5;$rowPos <= $rowCnt;$rowPos++){
			$cellDataArr = $this->xlsData[0]['cells'][$rowPos];
			$eMsg = array();
			$isValid = true;
			$flds = array();
			$flds[] = "ud_code='".$cellDataArr[1]."'";
			$flds[] = "ud_name='".$cellDataArr[2]."'";
			$flds[] = "ud_desc='".$cellDataArr[3]."'";
			$flds[] = "ud_parent='".$cellDataArr[4]."'";
			$fields = implode(", ",$flds);
			$sql = "insert into app_userdept set $fields";
			$this->conn->Execute($sql);
		}
		$_SESSION['eMsg'] = "Successfully Uploaded! Number of Records Imported: ".($rowPos-5);
	}
}
?>