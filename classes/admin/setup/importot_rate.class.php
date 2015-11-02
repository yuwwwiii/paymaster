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
class clsImportOT_Rate {

	var $conn;
	var $fieldMap;
	var $Data;

	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsImportOT_Rate object
	 */
	function clsImportOT_Rate($dbconn_ = null) {
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
		
		$this->xlsData = $this->doReadAndInsertUPOTRateFile($pData_['uptahead_file']['tmp_name']);
		
		// Import Validation
		if (count($pData_) == 0) {
			return NULL;
		}
		$badQtyCtr = 0;
		$rowPos = 5;
		$rowCnt = $this->xlsData[0]['numRows'];
		for ($rowPos; $rowPos <= $rowCnt; $rowPos++) {
			$errArrReq = array(); // By Sir IR Salvador
			$cellDataArr = $this->xlsData[0]['cells'][$rowPos];
			
			// Variable Asigning and Sanitation
			
			$code = trim($cellDataArr[1]);
			$description = trim($cellDataArr[2]);
			$ot_rate_type = trim($cellDataArr[3]);
			$factor_rate = trim($cellDataArr[4]);
			$maximum_rate_amount = trim($cellDataArr[5]);
			
			if ( 	
					(empty($code)) &&
					(empty($description)) &&
					(empty($ot_rate_type)) &&
					(empty($factor_rate)) &&
					(empty($maximum_rate_amount))
				) {
				$skipped_row++;
			} else {
				// icheck yung required column
				if (empty($code)) {
					$errArrReq[] = '"Code"';
					$isValid = false;
				}
				if (empty($description)) {
					$errArrReq[] = '"Description"';
					$isValid = false;
				}
				if (empty($ot_rate_type)) {
					$errArrReq[] = '"OT Rate Type"';
					$isValid = false;
				} else {
					// if the value of OT Rate Type Column is not equal to the one of the choices, then display the error
					$ot_rate_type_choices = array('Hour', 'Fixed', 'Day');
					if (!in_array($ot_rate_type, $ot_rate_type_choices)) {
						$_SESSION['eMsg'][] = "In Row {$rowPos}, Column \"OT Rate Type\" should be one of these only: " . implode(", ", $ot_rate_type_choices) . ".";
						$isValid = false;
					}
				}
				if (empty($factor_rate)) {
					$errArrReq[] = '"Factor Rate"';
					$isValid = false;
				} else {
					if (!is_numeric($factor_rate)) {
						$_SESSION['eMsg'][] = "In Row {$rowPos}, Column \"Factor Rate\" is not valid. It should be Numbers Only.";
						$isValid = false;
					}
				}
				if (!empty($maximum_rate_amount) && !is_numeric($maximum_rate_amount)) {
					$_SESSION['eMsg'][] = "In Row {$rowPos}, Column \"Maximum Rate Amount\" is not valid. It should be Numbers Only.";
					$isValid = false;
				}
			}
			
			if (count($errArrReq) > 0) {
				$_SESSION['eMsg'][] = "In Row {$rowPos}, Column " . implode(", ", $errArrReq) . " is required.";
			}
			$_SESSION['eMsg'] > 0 ? $isValid = false : '';
		} // this is the end of for loop
		
		if ( ($rowPos - (5 + $skipped_row) == 0) ) {
			$_SESSION['eMsg'][] = "Invalid record count. Data is less than 5 rows.";
			$isValid = false;
		}
		return $isValid;
	}
	
	function doReadAndInsertUPOTRateFile($fname_ = null) {
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
	function doSaveImportOTRate($pData_ = array()) {
		if (count($pData_) == 0) {
			return NULL;
		}
		$badQtyCtr = 0;
		$rowPos = 5;
		$rowCnt = $this->xlsData[0]['numRows'];
		for ($rowPos = 5; $rowPos <= $rowCnt; $rowPos++) {
			$cellDataArr = $this->xlsData[0]['cells'][$rowPos];
			$eMsg = array();
			$isValid = true;
			
			// Variable Asigning and Triming
			
			$code = trim($cellDataArr[1]);
			$description = trim($cellDataArr[2]);
			$ot_rate_type = trim($cellDataArr[3]);
			$factor_rate = trim($cellDataArr[4]);
			$maximum_rate_amount = trim($cellDataArr[5]);
			
			if ( 	
					(empty($code)) &&
					(empty($description)) &&
					(empty($ot_rate_type)) &&
					(empty($factor_rate)) &&
					(empty($maximum_rate_amount))
				) {
				$skipped_row++;
			} else {
				$flds = array();
				$flds[] = "otr_name='".$code."'";
				$flds[] = "otr_desc='".$description."'";
				$flds[] = "otr_type='".$ot_rate_type."'";
				$flds[] = "otr_factor='".$factor_rate."'";
				$flds[] = "otr_max='".$maximum_rate_amount."'";
				$flds[] = "otr_addwho='".AppUser::getData('user_name')."'";
				$fields = implode(", ",$flds);
				$sql = "INSERT INTO ot_rates SET $fields";
				$this->conn->Execute($sql);
			}
		}
		$_SESSION['eMsg'] = "Successfully Uploaded! Number of Record(s) Imported: ".( $rowPos - (5 + $skipped_row) );
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

	/**
	 * Get all the Table Listings
	 *
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
				$qry[] = "mnu_name like '%$search_field%'";

			}
		}

		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		 "viewdata"=>"viewdata"
		,"mnu_name"=>"mnu_name"
		,"mnu_link"=>"mnu_link"
		,"mnu_ord"=>"mnu_ord"
		);

		if (isset($_GET['sortby'])) {
			$strOrderBy = "ORDER BY ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}

		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$viewLink = "";
		$editLink = "<a href=\"?statpos=importot_rate&edit=',am.mnu_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/edit.png\" title=\"Edit\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
		$delLink = "<a href=\"?statpos=importot_rate&delete=',am.mnu_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\"  border=0 width=\"16\" height=\"16\"></a>";
		$action = "<a href=\"?statpos=importot_rate&action=add\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/add.png\" title=\"Add New\" border=0 width=\"16\" height=\"16\"></a>";

		// SqlAll Query
		$sql = "SELECT am.*, CONCAT('$viewLink','$editLink','$delLink') AS viewdata
						FROM app_modules am
						$criteria
						$strOrderBy";

		// Field and Table Header Mapping
		$arrFields = array(
		 "viewdata"=>$action
		,"mnu_name"=>"Module Name"
		,"mnu_link"=>"Link"
		,"mnu_ord"=>"Order"
		);

		// Column (table data) User Defined Attributes
		$arrAttribs = array(
		"mnu_ord" => "align='center'",
		"viewdata" => "width='50' align='center'"
		);

		// Process the Table List
		$tblDisplayList = new clsTableList($this->conn);
		$tblDisplayList->arrFields = $arrFields;
		$tblDisplayList->paginator->linkPage = "?$queryStr";
		$tblDisplayList->sqlAll = $sql;
		$tblDisplayList->sqlCount = $sqlcount;

		return $tblDisplayList->getTableList($arrAttribs);
	}

}

?>