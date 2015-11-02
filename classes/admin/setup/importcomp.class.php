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
class clsImportComp {

	var $conn;
	var $fieldMap;
	var $Data;

	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsImportComp object
	 */
	function clsImportComp ($dbconn_ = null) {
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
		$this->xlsData = $this->doReadAndInsertUPCompFile($pData_['uptahead_file']['tmp_name']);
		if ($this->xlsData[0]['numRows'] < 5) {
			$_SESSION['eMsg'][] = "Invalid record count. Data is less than 5 rows.";
			$isValid = false;
		}
		return $isValid;
	}
	
	function doReadAndInsertUPCompFile ($fname_ = null) {
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
	function doSaveImportComp ($pData_ = array()) {
		if (count($pData_) == 0) {
			return null;
		}
		$badQtyCtr = 0;
		$rowPos = 5;
		$rowCnt = $this->xlsData[0]['numRows'];
		for ($rowPos = 5;$rowPos <= $rowCnt;$rowPos++) {
			$cellDataArr = $this->xlsData[0]['cells'][$rowPos];
			$eMsg = array();
			$isValid = true;
			$comptype_desc = $this->getCompTYPEByCompTypeCODE($cellDataArr[2]);
			$flds = array();
			$flds[] = "comptype_id='".$comptype_desc['comptype_id']."'";
			$flds[] = "comp_code='".$cellDataArr[1]."'";
			$flds[] = "comp_name='".$cellDataArr[3]."'";
			$flds[] = "comp_add='".$cellDataArr[4]."'";
			$flds[] = "comp_zipcode='".$cellDataArr[5]."'";
			$flds[] = "comp_tel='".$cellDataArr[6]."'";
			$flds[] = "comp_email='".$cellDataArr[7]."'";
			$flds[] = "comp_prim_contc='".$cellDataArr[8]."'";
			$flds[] = "comp_tin='".$cellDataArr[9]."'";
			$flds[] = "comp_sss='".$cellDataArr[10]."'";
			$flds[] = "comp_phic='".$cellDataArr[11]."'";
			$flds[] = "comp_hdmf='".$cellDataArr[12]."'";
			$flds[] = "comp_addwho='".AppUser::getData('user_name')."'";
			$fields = implode(", ",$flds);
			$sql = "insert into company_info set $fields";
			$this->conn->Execute($sql);
		}
		$_SESSION['eMsg']="Successfully Uploaded! Number of Records Imported: ".($rowPos-5);
	}
	
	function getCompTYPEByCompTypeCODE ($comptype_desc_ = null) {
		if (is_null($comptype_desc_)) {
			return 1;
		}
		$qry = array();
		$qry[] = "comptype_desc = '".$comptype_desc_."'";
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";
		$sql = "Select comptype_id,comptype_desc from company_type $criteria";
		$rsResult = $this->conn->Execute($sql);
		if (!$rsResult->EOF) {
			return $rsResult->fields;
		} else {
			return 1;
		}
	}
}
?>