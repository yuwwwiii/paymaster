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
class clsImport201Bank{

	var $conn;
	var $fieldMap;
	var $Data;

	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsImport201Bank object
	 */
	function clsImport201Bank($dbconn_ = null){
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
		$this->xlsData = $this->doReadAndInsertUP201BankFile($pData_['uptahead_file']['tmp_name']);
		if ($this->xlsData[0]['numRows'] < 5) {
			$_SESSION['eMsg'][] = "Invalid record count. Data is less than 5 rows.";
			$isValid = false;
		}
		return $isValid;
	}
	
	function doReadAndInsertUP201BankFile($fname_ = null){
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
	function doSaveImport201Bank($pData_ = array()){
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
			//===========BankType Info===========
			$bnktypeparam = "baccntype_id,baccntype_name";
			$table = "bnkaccnt_type";
			$qrybnktype = array();
			$qrybnktype[] = "baccntype_name = '".$cellDataArr[4]."'";
			$baccntype_id = $this->getIDByParamiter($cellDataArr[4],$qrybnktype,$bnktypeparam,$table);
//			printa($baccntype_id);
			//===========Emp Info===========
			$emparam = "emp_id,emp_idnum,comp_id";
			$table = "emp_masterfile";
			$qryemp = array();
			$qryemp[] = "emp_idnum = '".$cellDataArr[1]."'";
			$emp_id = $this->getIDByParamiter($cellDataArr[1],$qryemp,$emparam,$table);
//			printa($emp_id);
			//===========bank Info===========
//			$bnkparam = "a.bank_id, a.banklist_id, a.comp_id, b.banklist_name";
//			$table = "bank_info a JOIN bank_list b on (b.banklist_id=a.banklist_id) JOIN emp_masterfile c on (c.comp_id=a.comp_id)";
//			$qrybnk = array();
//			$qrybnk[] = "b.banklist_name = '".$cellDataArr[3]."'";
//			$qrybnk[] = "c.comp_id = '".$emp_id['comp_id']."'";
//			$qrybnk[] = "c.emp_idnum = '".$emp_id['emp_idnum']."'";
//			$bank_id = $this->getIDByParamiter($cellDataArr[3],$qrybnk,$bnkparam,$table);
			$bnkparam = "a.banklist_id, a.banklist_name";
			$table = "bank_list a";
			$qrybnk = array();
			$qrybnk[] = "a.banklist_name = '".$cellDataArr[3]."'";
			$bank_id = $this->getIDByParamiter($cellDataArr[3],$qrybnk,$bnkparam,$table);
//			printa($bank_id);
			$flds = array();
			$flds[] = "baccntype_id='".$baccntype_id['baccntype_id']."'";
			$flds[] = "emp_id='".$emp_id['emp_id']."'";
			$flds[] = "banklist_id='".$bank_id['banklist_id']."'";
			$flds[] = "bankiemp_acct_no='".$cellDataArr[5]."'";
			$flds[] = "bankiemp_acct_name='".$cellDataArr[6]."'";
			$flds[] = "bankiemp_addwho='".AppUser::getData('user_name')."'";
			$fields = implode(", ",$flds);
			$sql = "insert into bank_infoemp set $fields";
			$this->conn->Execute($sql);
		}
		$_SESSION['eMsg']="Successfully Uploaded! Number of Records Imported: ".($rowPos-5);
	}
	
	function getIDByParamiter($var = null,$qryWhere = 0,$qrySelect = 0,$table_ = null){
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
		}else {
			return 0;
		}
	}
}
?>