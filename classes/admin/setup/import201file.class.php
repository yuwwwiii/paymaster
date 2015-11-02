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
class clsImport201File{

	var $conn;
	var $fieldMap;
	var $Data;

	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsImport201File object
	 */
	function clsImport201File ($dbconn_ = null) {
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
		$this->xlsData = $this->doReadAndInsertUP201File($pData_['uptahead_file']['tmp_name']);
		if ($this->xlsData[0]['numRows'] < 6) {
			$_SESSION['eMsg'][] = "Invalid record count. Data is less than 6 rows.";
			$isValid = false;
		}
		return $isValid;
	}
	
	function doReadAndInsertUP201File ($fname_ = null) {
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
	function doSaveImport201File ($pData_ = array()) {
		if (count($pData_) == 0) {
			return null;
		}
		$badQtyCtr = 0;
		$rowPos = 6;
		$rowCnt = $this->xlsData[0]['numRows'];
		for ($rowPos = 6;$rowPos <= $rowCnt;$rowPos++) {
			$cellDataArr = $this->xlsData[0]['cells'][$rowPos];
//			echo $cellDataArr[31]." HIREDATE<br>";
//			$hireday = $cellDataArr[31];
//			echo date('Y-m-d', strtotime($cellDataArr[31]));
//			printa($cellDataArr);
//			exit;
			$eMsg = array();
			$isValid = true;
			
			$p_id = $this->getProvinceIDByProvinceName($cellDataArr[16]);
			$zipcode_id = $this->getZipcodeIDByZipcode($cellDataArr[17]);
			
			//===========Company Info===========
			$comparam = "comp_id,comp_code,comp_name";
			$table = "company_info";
			$qrycomp = array();
			$qrycomp[] = "comp_code = '".$cellDataArr[24]."'";
			$comp_id = $this->getIDByParamiter($cellDataArr[24],$qrycomp,$comparam,$table);
//			printa($comp_id);
			//===========Branch Info============
			$branparam = "branchinfo_id,branchinfo_code,branchinfo_name";
			$table = "branch_info";
			$qrybranch = array();
			$qrybranch[] = "branchinfo_code = '".$cellDataArr[25]."'";
			$branchinfo_id = $this->getIDByParamiter($cellDataArr[25],$qrybranch,$branparam,$table);
//			printa($branchinfo_id);
			//===========Depart Info============
			$udparam = "ud_id,ud_desc,ud_name";
			$table = "app_userdept";
			$qryud = array();
			$qryud[] = "ud_name = '".$cellDataArr[26]."'";
			$ud_id = $this->getIDByParamiter($cellDataArr[26],$qryud,$udparam,$table);
//			printa($ud_id);
			//===========Position Info==========
			$posparam = "post_id,post_code,post_name";
			$table = "emp_position";
			$qrypos = array();
			$qrypos[] = "post_code = '".$cellDataArr[27]."'";
			$post_id = $this->getIDByParamiter($cellDataArr[27],$qrypos,$posparam,$table);
//			printa($post_id);
			//===========EmpType Info===========
			$etypeparam = "emptype_id,emptype_name";
			$table = "emp_type";
			$qryetype = array();
			$qryetype[] = "emptype_name = '".$cellDataArr[28]."'";
			$emptype_id = $this->getIDByParamiter($cellDataArr[28],$qryetype,$etypeparam,$table);
//			printa($emptype_id);
			//===========EmpCateg Info===========
			$ecategparam = "empcateg_id,empcateg_name";
			$table = "emp_category";
			$qryecateg = array();
			$qryecateg[] = "empcateg_name = '".$cellDataArr[29]."'";
			$empcateg_id = $this->getIDByParamiter($cellDataArr[29],$qryecateg,$ecategparam,$table);
//			printa($empcateg_id);
			//===========EmpStat Info===========
			$estatparam = "emp201status_id,emp201status_name";
			$table = "emp_201status";
			$qryestat = array();
			$qryestat[] = "emp201status_name = '".$cellDataArr[30]."'";
			$emp_stat = $this->getIDByParamiter($cellDataArr[30],$qryestat,$estatparam,$table);
//			printa($emp_stat);
			//===========TaxStatus Info==========
			$taxstat = 0;
			if ($cellDataArr[41]=='MWE') {
				$taxstat = 2;
			} elseif ($cellDataArr[41]=='OTHERS') {
				$taxstat = 3;
			} else {
				$taxstat = 1;
			}
//			printa($taxstat);
			//===========TaxExcep Info==========
			$texparam = "taxep_id,taxep_code,taxep_name";
			$table = "tax_excep";
			$qrytaxe = array();
			$qrytaxe[] = "taxep_code = '".$cellDataArr[40]."'";
			$taxep_id = $this->getIDByParamiter($cellDataArr[40],$qrytaxe,$texparam,$table);
//			printa($taxep_id);
			//===========Salary Type Info==========
			$salparam = "salarytype_id,salarytype_name";
			$table = "salary_type";
			$qrysal = array();
			$qrysal[] = "salarytype_name = '".$cellDataArr[41]."'";
			$salarytype_id = $this->getIDByParamiter($cellDataArr[41],$qrysal,$salparam,$table);
//			printa($salarytype_id);
//			exit;
			
			$flds = array();//Personal Info.
			$flds[] = "zipcode_id='".$zipcode_id['zipcode_id']."'";
			$flds[] = "p_id='".$p_id['p_id']."'";
			$flds[] = "pi_fname='".trim($cellDataArr[2])."'";
			$flds[] = "pi_mname='".trim($cellDataArr[3])."'";
			$flds[] = "pi_lname='".trim($cellDataArr[4])."'";
			$flds[] = "pi_gender='".$cellDataArr[5]."'";
			$flds[] = "pi_bdate='".date('Y-m-d', strtotime($cellDataArr[6]))."'";
			$flds[] = "pi_place_bdate='".trim($cellDataArr[7])."'";
			$flds[] = "pi_civil='".$cellDataArr[8]."'";
			$flds[] = "pi_religion='".$cellDataArr[9]."'";
			$flds[] = "pi_nationality='".$cellDataArr[10]."'";
			$flds[] = "pi_race='".$cellDataArr[11]."'";
			$flds[] = "pi_height='".$cellDataArr[12]."'";
			$flds[] = "pi_weight='".$cellDataArr[13]."'";
			switch ($cellDataArr[14]) {
				case 'None':
					$blood_type = 'Unknown';
					break;
				case '':
					$blood_type = 'Unknown';
					break;
			}
			$flds[] = "pi_bloodtype='".$blood_type."'";
			$flds[] = "pi_add='".trim($cellDataArr[15])."'";
			$flds[] = "pi_telone='".$cellDataArr[18]."'";
			$flds[] = "pi_teltwo='".$cellDataArr[19]."'";
			$flds[] = "pi_mobileone='".$cellDataArr[20]."'";
			$flds[] = "pi_mobiletwo='".$cellDataArr[21]."'";
			$flds[] = "pi_emailone='".trim($cellDataArr[22])."'";
			$flds[] = "pi_emailtwo='".$cellDataArr[23]."'";
			$flds[] = "pi_tin='".$cellDataArr[34]."'";
			$flds[] = "pi_sss='".$cellDataArr[35]."'";
			$flds[] = "pi_phic='".$cellDataArr[36]."'";
			$flds[] = "pi_hdmf='".$cellDataArr[37]."'";
			$flds[] = "pi_passport='".$cellDataArr[38]."'";
			$flds[] = "pi_addwho='".AppUser::getData('user_name')."'";
			$fields = implode(", ",$flds);
			$sql = "insert into emp_personal_info set $fields";
			$this->conn->Execute($sql);
			$uptadet_id_ = $this->conn->Insert_ID();
			
			$flds_ = array();//Employee Info.
			$flds_[] = "taxep_id='".$taxep_id['taxep_id']."'";
			$flds_[] = "ud_id='".$ud_id['ud_id']."'";
			$flds_[] = "comp_id='".$comp_id['comp_id']."'";
			$flds_[] = "post_id='".$post_id['post_id']."'";
			$flds_[] = "empcateg_id='".$empcateg_id['empcateg_id']."'";
			$flds_[] = "emptype_id='".$emptype_id['emptype_id']."'";
			$flds_[] = "pi_id='".$uptadet_id_."'";
			$flds_[] = "branchinfo_id='".$branchinfo_id['branchinfo_id']."'";
			$flds_[] = "emp_idnum='".trim($cellDataArr[1])."'";
			$flds_[] = "emp_hiredate='".date('Y-m-d', strtotime($cellDataArr[31]))."'";
			$flds_[] = "emp_resigndate='".$cellDataArr[32]."'";
			$flds_[] = "emp_resonresign='".$cellDataArr[33]."'";
			$flds_[] = "emp_stat='".$emp_stat['emp201status_id']."'";
			$flds_[] = "emp_addwho='".AppUser::getData('user_name')."'";
			$fields_ = implode(", ",$flds_);
			$sql_ = "insert into emp_masterfile set $fields_";
			$this->conn->Execute($sql_);
			$emp_id_ = $this->conn->Insert_ID();
			
			$flds2 = array();//Salary Info.
			$flds2[] = "emp_id='".$emp_id_."'";
			$flds2[] = "fr_id='1'";
			$flds2[] = "salarytype_id='".$salarytype_id['salarytype_id']."'";
			$flds2[] = "salaryinfo_basicrate='".trim($cellDataArr[42])."'";
			$flds2[] = "salaryinfo_ecola='".trim($cellDataArr[43])."'";
			$flds2[] = "salaryinfo_effectdate='".$cellDataArr[44]."'";
			$flds2[] = "salaryinfo_ceilingpay='".$cellDataArr[45]."'";
			$flds2[] = "salaryinfo_addwho='".AppUser::getData('user_name')."'";
			$fields2 = implode(", ",$flds2);
			$sql2 = "insert into salary_info set $fields2";
			$this->conn->Execute($sql2);
			
			$flds3 = array();//Tax Status.
			$flds3[] = "empdd_id='5'";
			$flds3[] = "emp_id='".$emp_id_."'";
			$flds3[] = "bldsched_period='".$taxstat."'";
			$fields3 = implode(", ",$flds3);
			$sql3 = "insert into period_benloanduc_sched set $fields3";
			$this->conn->Execute($sql3);
			
		}
		$_SESSION['eMsg']="Successfully Uploaded! Number of Records Imported: ".($rowPos-6);
	}
	
	function getProvinceIDByProvinceName($province_name_ = null) {
		if(is_null($province_name_)) {
			return 1;
		}
		$qry = array();
		$qry[] = "province_name = '".$province_name_."'";
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";
		$sql = "Select p_id,province_name from app_province $criteria";
		$rsResult = $this->conn->Execute($sql);
		if (!$rsResult->EOF) {
			return $rsResult->fields;
		} else {
			return 1;
		}
	}
	
	function getZipcodeIDByZipcode($zipcode_ = null){
		if (is_null($zipcode_)) {
			return 1;
		}
		$qry = array();
		$qry[] = "zipcode = '".$zipcode_."'";
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";
		$sql = "Select zipcode_id,zipcode from app_zipcodes $criteria";
		$rsResult = $this->conn->Execute($sql);
		if (!$rsResult->EOF) {
			return $rsResult->fields;
		} else {
			return 1;
		}
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
		} else {
			return 0;
		}
	}
}
?>