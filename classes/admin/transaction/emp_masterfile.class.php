<?php
session_start();
/**
 *
 *
 validate(
 
/**
 * Initial Declaration
 */
$SalaryActive = array(
  	 '1'=>'Active',
	 '0'=>'Inactive'
);

/**
 * Class Module
 *
 * @author  JIM
 *
 */
class clsEMP_MasterFile{

	var $conn;
	var $fieldMap;
	var $Data;
	var $Data_pinfo;
	var $fieldMap_pinfo;
	var $fieldMap_basic;
	var $Data_basic;
	var $Data_bankinfo;
	var $required;
	var $vloan;
	var $fieldMapPrevEmp;
	var $Data_prevEmp;
	var $fieldMapPrevEmpMWE;
	var $Data_prevEmpMWE;
	
	function seepost(){
	//print implode('-',$_POST['sss']);
	//print implode('-',$_POST['phic']);
	//print implode('-',$_POST['tin']);
	//print implode('-',$_POST['phic']);
	//exit;
		//$this->doPopulateData($_POST);
		//$this->doPopulateData($_POST);
		//printa($this->Data_pinfo);
		//printa($this->Data);
		//printa($this->Data_basic);
		//printa($this->Data_bankinfo);
		//print $_POST['ud_name'];
		//$this->doSaveAdd();	
		
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
				if ($key=='emp_picture') {
					$this->Data[$key] = ($_FILES[$key]);
				} else {
					$this->Data[$key] = $pData_[$value];
				}
			}
			foreach ($this->fieldMap_pinfo as $key => $value) {
				if ($isForm_) {
					$this->Data_pinfo[$value] = $pData_[$value];
				} else {
					$this->Data_pinfo[$key] = $pData_[$value];
				}

			}
			foreach ($this->fieldMap_basic as $key => $value) {
				if ($isForm_) {
					$this->Data_basic[$value] = $pData_[$value];
				} else {
					$this->Data_basic[$key] = $pData_[$value];
				}
			}
			foreach($this->fieldMapPrevEmp as $key => $value){
				if ($isForm_) {
					$this->Data_prevEmp[$value] = $pData_[$value];
				} else {
					$this->Data_prevEmp[$key] = $pData_[$value];
				}
			}
			foreach($this->fieldMapPrevEmpMWE as $key => $value){
				if ($isForm_) {
					$this->Data_prevEmpMWE[$value] = $pData_[$value];
				} else {
					$this->Data_prevEmpMWE[$key] = $pData_[$value];
				}
			}
			return true;
		}
		return false;
	}
	
	function populateData($field){
		foreach ($field as $key => $value) {
			$postData[] = $key."='".$_POST[$value]."'";
		}
		return implode(",",$postData);
	}
	
	function validate(){
			$this->required = array(
			"emp_idnum" => "Employee Number"
			,"pi_fname" => "First Name"
			,"pi_mname" => "Middle Name"
			,"pi_lname" => "Last Name"
			,"comp_id" => "Company Name"
			,"post_id" => "Position"
			,"empcateg_id" => "Category"
			/*,"emptype_id" => "Type"*/
			,"emp_hiredate" => "Hire Date"
			,"ud_id" => "Department"
			,"pi_bdate" => "Birth Date"
			,"pi_gender" => "Gender"
			,"taxep_id" => "taxep_id"
			);
			
			foreach($this->required as $var => $description){
				if(empty($_POST[$var])){
					$error[] = $description;
				}
			}
			$x = count($error);
			if($x>0){
				$c = ($x==1)?' is ':' are ';				
				$_SESSION['eMsg'][]= implode(', ',$error).$c." required.";
			}
			else return true;
	}
	
	function returnPost($vname='required') {
		foreach($this->$vname as $var => $description) {
			$post[$var] = $_POST[$var];
		}
		return $post;
	}
	
	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsSample object
	 */
	function clsEMP_MasterFile($dbconn_ = null) {
		$this->conn =& $dbconn_;
		
		$this->vloan = array(
		"psa_id"=>"psa_id",
		"loantype_id"=>"loantype_id",
		"emp_id"=>"emp_id",
		"loan_voucher_no"=>"loan_voucher_no",
		"loan_datepromissory"=>"loan_datepromissory",
		"loan_dategrant"=>"loan_dategrant",
		"loan_principal"=>"loan_principal",
		"loan_interestamount"=>"loan_interestamount",
		"loan_interestperc"=>"loan_interestperc",
		"loan_monthly_amortization"=>"loan_monthly_amortization",
		"loan_payperperiod"=>"loan_payperperiod",
		"loan_ytd"=>"loan_ytd",
		"loan_balance"=>"loan_balance",
		"loan_startdate"=>"loan_startdate",
		"loan_enddate"=>"loan_enddate",
		"loan_suspend"=>"loan_suspend",
		"loan_total"=>"loan_total",
		"loan_numofmonths"=>"loan_numofmonths",
		"loan_periodselection"=>"loan_periodselection"
		);
		$this->fieldMap = array(
		 "ud_id" => "ud_id"
		,"emp_stat"=>"emp_stat"
		,"emp_isconfidentail" => "emp_isconfidentail"
		,"comp_id" => "comp_id"
		,"post_id" => "post_id"
		,"empcateg_id" => "empcateg_id"
		,"emptype_id" => "emptype_id"
		,"pi_id" => "pi_id"
		,"branchinfo_id" => "branchinfo_id"
		,"emp_idnum" => "emp_idnum"
		,"emp_hiredate" => "emp_hiredate"
		,"taxep_id" => "taxep_id"
		,"emp_tacessid" => "emp_tacessid"
		,"emp_picture" => "emp_picture"
		);
		$this->fieldMap_basic = array(
		 "salarytype_id" => "salarytype_id"
		,"emp_id" => "emp_id"
		,"fr_id" => "fr_id"
		,"salaryinfo_effectdate" => "salaryinfo_effectdate"
		,"salaryinfo_basicrate" => "salaryinfo_basicrate"
		,"salaryinfo_isactive" => "salaryinfo_isactive"
		,"salaryinfo_ceilingpay" => "salaryinfo_ceilingpay"
		,"salaryinfo_ecola" => "salaryinfo_ecola"
		);
		$this->fieldMap_bankinfo = array(
		 "banklist_id" => "banklist_id"
		,"emp_id" => "emp_id"
		,"baccntype_id" => "baccntype_id"
		,"bankiemp_acct_no" => "bankiemp_acct_no"
		,"bankiemp_acct_name" => "bankiemp_acct_name"
		,"bankiemp_swift_code" => "bankiemp_swift_code"
		);
		$this->fieldMap_leaveinfo = array(
		 "leave_id" => "leave_id"
		,"emp_id" => "emp_id"
		,"empleave_used_day" => "empleave_used_day"
		,"empleave_available_day" => "empleave_available_day"
		,"empleave_credit" => "empleave_credit"
		,"empleave_stat" => "empleave_stat"
		);
		$this->fieldMap_pinfo = array(
		 "pi_fname" => "pi_fname"
		,"pi_mname" => "pi_mname"
		,"pi_lname" => "pi_lname"
		,"pi_gender" => "pi_gender"
		,"pi_bdate" => "pi_bdate"
		,"pi_place_bdate" => "pi_place_bdate"
		,"pi_nationality" => "pi_nationality"
		,"pi_religion" => "pi_religion"
		,"pi_race" => "pi_race"
		,"pi_bloodtype" => "pi_bloodtype"
		,"pi_height" => "pi_height"
		,"pi_weight" => "pi_weight"
		,"pi_add" => "pi_add"
		,"pi_telone" => "pi_telone"
		,"pi_teltwo" => "pi_teltwo"
		,"pi_mobileone" => "pi_mobileone"
		,"pi_mobiletwo" => "pi_mobiletwo"
		,"pi_emailone" => "pi_emailone"
		,"pi_emailtwo" => "pi_emailtwo"
		,"pi_tin" => "pi_tin"
		,"pi_sss" => "pi_sss"
		,"pi_phic" => "pi_phic"
		,"pi_hdmf" => "pi_hdmf"
		,"pi_nhmfc" => "pi_nhmfc"
		,"p_id" => "p_id"
		,"zipcode_id" => "zipcode_id"
		,"pi_passport" => "pi_passport"
		,"pi_civil" => "pi_civilstat"
		);
		$this->fieldMap_dependent = array(
		"emp_id" => "emp_id",
		"depnd_fname" => "depnd_fname",
		"depnd_mname" => "depnd_mname",
		"depnd_lname" => "depnd_lname",
		"depnd_bdate" => "depnd_bdate",
		"depnd_relationship" => "depnd_relationship",
		"depnd_nationality" => "depnd_nationality",
		);
		$this->fieldMapPrevEmp = array(
		"bir_alphalist_year" => "bir_alphalist_year"
		,"taxable_basic" => "taxable_basic"
		,"taxable_other_ben" => "taxable_other_ben"
		,"taxable_compensation" => "taxable_compensation"
		,"nt_other_ben" => "nt_other_ben"
		,"nt_deminimis" => "nt_deminimis"
		,"nt_statutories" => "nt_statutories"
		,"nt_compensation" => "nt_compensation"
		);
		$this->fieldMapPrevEmpMWE = array(
		"bir_alphalist_year" => "bir_alphalist_year"
		,"gross_compensation" => "gross_compensation"
		,"basic_smw" => "basic_smw"
		,"holiday_pay" => "holiday_pay"
		,"overtime_pay" => "overtime_pay"
		,"night_differential" => "night_differential"
		,"hazard_pay" => "hazard_pay"
		,"nt_other_ben" => "nt_other_ben"
		,"nt_deminimis" => "nt_deminimis"
		,"nt_statutories" => "nt_statutories"
		,"nt_compensation" => "nt_compensation"
		,"taxable_other_ben" => "taxable_other_ben"
		,"taxable_compensation" => "taxable_compensation"
		);
	}

	/**
	 * Get the records from the database
	 *
	 * @param string $id_
	 * @return array
	 */
	function dbFetch($id_ = ""){
		$sql = "SELECT pps.pps_id,empinfo.*, pinfo.*, zcode.zipcode, city.province_name, reg_.region_name, coun_.cou_description, 
				comp.comp_name, post.post_name, dept.ud_name, type.emptype_name, categ.empcateg_name, taxep.taxep_name, 
				comp_.ot_id, ppsdetails.salaryclass_id, emstat.emp201status_name, pcal.ot_id, pcal.fr_id, pcal.pps_id, brnch.branchinfo_name
				FROM emp_masterfile empinfo					
				JOIN emp_personal_info pinfo on (pinfo.pi_id=empinfo.pi_id)
				LEFT JOIN app_userdept dept on (dept.ud_id=empinfo.ud_id)
				LEFT JOIN emp_category categ on (categ.empcateg_id=empinfo.empcateg_id)
				LEFT JOIN emp_type type on (type.emptype_id=empinfo.emptype_id)
				LEFT JOIN company_info comp on (comp.comp_id=empinfo.comp_id)
				LEFT JOIN emp_position post on (post.post_id=empinfo.post_id)
				LEFT JOIN payroll_pps_user pps on (pps.emp_id=empinfo.emp_id)
				LEFT JOIN payroll_comp comp_ on (comp_.emp_id=empinfo.emp_id)
				LEFT JOIN tax_excep taxep on (taxep.taxep_id=empinfo.taxep_id)
				LEFT JOIN app_province city on (city.p_id=pinfo.p_id)
				LEFT JOIN app_region reg_ on (reg_.r_id=city.r_id)
				LEFT JOIN app_country coun_ on (coun_.cou_id=reg_.cou_id)
				LEFT JOIN app_zipcodes zcode on (zcode.zipcode_id=pinfo.zipcode_id)
				LEFT JOIN payroll_pay_period_sched ppsdetails on (ppsdetails.pps_id=pps.pps_id)
				LEFT JOIN emp_201status emstat on (emstat.emp201status_id=empinfo.emp_stat)
				LEFT JOIN payroll_comp pcal on (pcal.emp_id=empinfo.emp_id)
				LEFT JOIN branch_info brnch on (brnch.branchinfo_id=empinfo.branchinfo_id) 
				WHERE empinfo.emp_id = ?";

		$rsResult = $this->conn->Execute($sql,array($id_));
		if(!$rsResult->EOF){
//			$rsResult->fields['pi_sss'] = explode('-',$rsResult->fields['pi_sss']);
//			$rsResult->fields['pi_tin'] = explode('-',$rsResult->fields['pi_tin']);
//			$rsResult->fields['pi_phic'] = explode('-',$rsResult->fields['pi_phic']);
//			$rsResult->fields['pi_hdmf'] = explode('-',$rsResult->fields['pi_hdmf']);
			$_SESSION['emplid'] = $id_;
			$_SESSION['pps_id'] = $rsResult->fields['pps_id'];
			return $rsResult->fields;
		}
	}
	function dbFetchBasicRate($id_ = "") {
		$sql = "select sinfo.*, stype.salarytype_name, stype.salarytype_id, puser.pps_id, 
				if(sinfo.salaryinfo_isactive = '1','Active','Inactive') as salaryinfo_isactive 
					from salary_info sinfo
					left join salary_type stype on (stype.salarytype_id=sinfo.salarytype_id) 
					left join payroll_pps_user puser on (puser.emp_id=sinfo.emp_id)
					where sinfo.salaryinfo_id=?";
		$rsResult = $this->conn->Execute($sql,array($id_));
		if(!$rsResult->EOF){
			return $rsResult->fields;
		}
	}
	
	/**
	 * Dependent Tab
	 *
	 */
	function dbFetchDependent($id_ = "") {
		$sql = "select * from dependent_info where depnd_id=?";
		$rsResult = $this->conn->Execute($sql,array($id_));
		if(!$rsResult->EOF){
			return $rsResult->fields;
		}
	}
	
	function dbFetchBankInfo($id_ = "") {
		$sql = "select binfoemp.*, binfolist.banklist_name
						from bank_infoemp binfoemp
						left join bank_list binfolist on (binfolist.banklist_id=binfoemp.banklist_id)
					where bankiemp_id=? AND emp_id=?";
		$rsResult = $this->conn->Execute($sql,array($id_,$_GET['empinfo']));
		if (!$rsResult->EOF) {
			return $rsResult->fields;
		}
	}
	
	/**
	 * Validation function
	 *
	 * @param array $pData_
	 * @return bool
	 */
	function doValidateData($pData_ = array()){
		$isValid = true;
		
		
		// Define file size limit
		$limit_size=204800;
		
//		if (!isset($_GET['edit'])) {
//			$sql = "select * from emp_masterfile where emp_idnum=?";
//			$rsResult = $this->conn->Execute($sql,array($pData_['emp_idnum']));
//				if (!$rsResult->EOF) {
//					$_SESSION['eMsg'][] = "Employee ID is Already Exist.";
//					$isValid = false;
//			}
//		}
//if (empty($pData_['emp_idnum'])) {
//	$isValid = false;
//	$_SESSION['eMsg'][] = "Please enter Employee ID.";
//}
//
//if (empty($pData_['pi_fname'])) {
//	$isValid = false;
//	$_SESSION['eMsg'][] = "Please enter Employee First Name.";
//}
//
//if (empty($pData_['pi_mname'])) {
//	$isValid = false;
//	$_SESSION['eMsg'][] = "Please enter Employee Middle Name.";
//}
//
//if (empty($pData_['pi_lname'])) {
//	$isValid = false;
//	$_SESSION['eMsg'][] = "Please enter Employee Last Name.";
//}
		
		//  Store upload file size in $file_size
		//	$size_ = $_FILES['emp_picture']['size'];
		if ($_FILES['emp_picture']['size'] > $limit_size) {
//			echo $_FILES['emp_picture']['size']."filesize";
//			exit;
			
			$_SESSION['eMsg'] = "The image size exceed to 200KB.";
			$isValid = false;
		}
		
		return $isValid;
	}

	
	/**
	 * Save New
	 *
	 */
	function doSaveAdd($update=false) {
		$flds = array();
		//this is used to save the emp_personal_info
		$gov = array(
			'pi_sss'=>implode('-',$_POST['sss']),
			'pi_phic'=>implode('-',$_POST['phic']),
			'pi_hdmf'=>implode('-',$_POST['hdmf']),
			'pi_tin'=>implode('-',$_POST['tin'])
		);
		foreach ($this->Data_pinfo as $keyData => $valData) {
			$valData = $valData;
			if(in_array($keyData,array_keys($gov))){
				$flds[] = $keyData."='".$gov[$keyData]."'";
			}else{
				$flds[] = "$keyData='$valData'";
			}
		}
		if($update){
			$flds[]="pi_updatewho='".AppUser::getData('user_name')."'";
		}else{
			$flds[]="pi_addwho='".AppUser::getData('user_name')."'";
		}
		$fields = implode(", ",$flds);
		if($update){
			$sql = "update emp_personal_info set $fields where pi_id='".$_POST['pi_id']."'";
			if($this->conn->Execute($sql))
			$_SESSION['eMsg']="Successfully Updated.";
			else
			$_SESSION['eMsg']=mysql_error();
			if($_POST['emp_isconfidentail']) $emp_isconfidentail = 1; else $emp_isconfidentail = 0;
			
			//this is used to update emp_masterfile
			foreach ($this->Data as $keyData => $valData) {
				//--------------------------------->>
				// edited by jmabignay 2009.12.02
					if($keyData=="emp_picture"){
						if (empty($valData['error'])) {
							$valData = (file_get_contents($this->Data['emp_picture']['tmp_name']));
						} else {
							break;
						}
					}
				//---------------------------------<<
				if($keyData=='emp_isconfidentail') $valData = $emp_isconfidentail;
				$valData = addslashes($valData);
				$flds_[] = "$keyData='$valData'";
			}
			$flds_[]="emp_updatewho='".AppUser::getData('user_name')."'";
			$flds_[]="emp_updatewhen='".date('Y-m-d H:i:s')."'";
			$flds_[]="emp_resigndate='".$_POST['emp_resigndate']."'";
			$fields_ = implode(", ",$flds_);
			$sql_ = "update emp_masterfile set $fields_ where pi_id='".$_POST['pi_id']."'";
			if ($this->conn->Execute($sql_)) {
				$_SESSION['eMsg']="Successfully Updated.";
			} else {
				print mysql_error();
			}
		}else{
			//this is used to save emp_info
			$sql = "insert into emp_personal_info set $fields";
			if($this->conn->Execute($sql)){
				$id = mysql_insert_id();				
			}
			else
			print mysql_error();
			
			//this is used to save emp_masterfile
			foreach ($this->Data as $keyData => $valData) {
				if ($keyData=='pi_id') {
					$valData=$id;
				}
				if($keyData=="emp_stat"){
						$valData='1';		
				}
				if($keyData=="emp_picture"){
						if (!empty($valData)) {	
							$valData = file_get_contents($this->Data['emp_picture']['tmp_name']);
						}		
				}
				$valData = addslashes($valData);
				$flds_[] = "$keyData='$valData'";
			}
			$flds_[]="emp_addwho='".AppUser::getData('user_name')."'";
			$fields_ = implode(", ",$flds_);
			$sql_ = "insert into emp_masterfile set $fields_";
			$this->conn->Execute($sql_);
			
			//@notes: get last inserted ID
			$lastinsertID_emp_id = $this->conn->Insert_ID();
			
			//@notes: return last inserted ID
			return $lastinsertID_emp_id;
	
			$_SESSION['eMsg']="Successfully Added.";
			
//			 if($this->conn->Execute($sql_))
//			 {
//			 $_SESSION['eMsg']="Successfully Added.";
//				 $id =  mysql_insert_id();
//				 $ppssql = "insert into payroll_pps_user set emp_id='".$id."',ppsu_addwho='".AppUser::getData('user_name')."'";
//				 $this->conn->Execute($ppssql);//set the default pay group to semi-monthly,,,pps_id='4',
//				 header("Location: transaction.php?statpos=emp_masterfile&empinfo=".$id."#tab-1");
//			 }
//			 else
//			 $_SESSION['eMsg']=mysql_error();
		}
	}

	/**
	 * Save Update
	 *
	 */
	function doSaveEdit(){

	}
	
	/**
	 * Save Update
	 *
	 */
	function doSaveBasicRate($emp_id_ = "") {

		$flds = array();
		if (isset($_POST['bntBankinfo'])) {
			//this is used to save bank infi per employee
			foreach ($this->Data_bankinfo as $keyData => $valData) {
				if ($keyData=='emp_id') {
					$valData = $emp_id_;
				}
				$valData = addslashes($valData);
				$flds_[] = "$keyData='$valData'";
			}
			$flds_[]="bankiemp_addwho='".AppUser::getData('user_name')."'";
			$fields_ = implode(", ",$flds_);
	
			$sql = "insert into bank_infoemp set $fields_";
			$this->conn->Execute($sql);
			
		}else{
			//this used to save basic rate
			foreach ($this->Data_basic as $keyData => $valData) {
				if ($keyData=='emp_id') {
					$valData = $emp_id_;
				}
				$valData = addslashes($valData);
				$flds_[] = "$keyData='$valData'";
			}
			$flds_[]="salaryinfo_addwho='".AppUser::getData('user_name')."'";
			$fields_ = implode(", ",$flds_);
	
			$sql = "insert into salary_info set $fields_";
			$this->conn->Execute($sql);
		}
		
		$_SESSION['eMsg']="Successfully Added.";
	}
	
	/**
	 * Save Update
	 *
	 */
	function doSaveEditBasicRate($emp_id_ = ""){
		$flds = array();
		if (isset($_POST['bntBankinfo'])) {
			foreach ($this->Data_bankinfo as $keyData => $valData) {
				if ($keyData=='emp_id') {
					$valData = $emp_id_;
				}
				$valData = addslashes($valData);
				$flds_[] = "$keyData='$valData'";
			}
			$flds_[]="bankiemp_updatewho='".AppUser::getData('user_name')."'";
			$flds_[]="bankiemp_updatewhen='".date('Y-m-d H:i:s')."'";
			$fields_ = implode(", ",$flds_);
	
			$sql = "update bank_infoemp set $fields_ where bankiemp_id='".$_GET['empinfoedit']."'";
			$this->conn->Execute($sql);
		} else {
			foreach ($this->Data_basic as $keyData => $valData) {
				if ($keyData=='emp_id') {
					$valData = $emp_id_;
				}
				$valData = addslashes($valData);
				$flds_[] = "$keyData='$valData'";
			}
			$flds_[]="salaryinfo_updatewho='".AppUser::getData('user_name')."'";
			$flds_[]="salaryinfo_updatewhen='".date('Y-m-d H:i:s')."'";
			$fields_ = implode(", ",$flds_);
	
			$sql = "update salary_info set $fields_ where salaryinfo_id='".$_GET['empinfoedit']."'";
			$this->conn->Execute($sql);
		}
		$_SESSION['eMsg']="Successfully Updated.";
	}

	/**
	 * Delete Record
	 *
	 * @param string $id_
	 */
	function doDelete($tbl = "",$id_ = "") {
		/*$sql = "delete from emp_masterfile where emp_id=?";*/
		// if (isset($_GET['empinfodelete'])) {
			// $sql = "delete from salary_info where salaryinfo_id=?";
			// $this->conn->Execute($sql,array($id_));
		// }else{
			// $sql="update emp_masterfile set emp_stat='0' where emp_id=?";
			// $this->conn->Execute($sql,array($id_));
		// }
		if ($tbl == 'bank_info') {
			$sql = "delete from bank_infoemp where bankiemp_id=?";
			$this->conn->Execute($sql,array($id_));
		}
		if ($tbl == 'salary_info') {
			$sql = "delete from salary_info where salaryinfo_id=?";
			$this->conn->Execute($sql,array($id_));
		}
		if ($tbl=='main') {
			$sql="update emp_masterfile set emp_stat='0' where emp_id=?";
			$this->conn->Execute($sql,array($id_));
		}
		if ($tbl=='ben_info') {
			$sqlsched = "delete from period_benloanduc_sched where ben_id=?";// to delete record in period_benloanduc_sched table
			$this->conn->Execute($sqlsched,array($id_));
			
			$sql="delete from emp_benefits where ben_id=?";
			$this->conn->Execute($sql,array($id_));
		}
		if ($tbl=='loan_info') {
			$sqlsched = "delete from period_benloanduc_sched where loan_id=?";// to delete record in period_benloanduc_sched table
			$this->conn->Execute($sqlsched,array($id_));
			
			$sql="delete from loan_info where loan_id=?";// to delete loan record.
			$this->conn->Execute($sql,array($id_));
		}
		if ($tbl=='leave_info') {
			$sql="delete from emp_leave where empleave_id=?";
			$this->conn->Execute($sql,array($id_));
		}
		if ($tbl=='dependent_info') {
			$sql_ = "Select (YEAR(CURDATE())-YEAR(depnd_bdate)) - (RIGHT(CURDATE(),5)<RIGHT(depnd_bdate,5)) AS depnd_age from dependent_info a where depnd_id='".$id_."'";
			$var = $this->conn->Execute($sql_);
//			if(){
//				
//			}else{
				$sql="DELETE FROM dependent_info WHERE depnd_id=?";
				$this->conn->Execute($sql,array($id_));
//			}
		}
		$_SESSION['eMsg']="Successfully Deleted.";
	}

//	function updateTaxExemption($id_ = "") {
//			$sql="UPDATE `payroll_db`.`emp_masterfile` SET `taxep_id` = '5' WHERE `emp_masterfile`.`emp_id` = ?;";
//			$this->conn->Execute($sql,array($id_));
//	}
	
	/**
	 * Get all the Table Listings
	 *
	 * @return array
	 */
	function getTableList () {
//		$this->conn->debug=1;
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
			// lets check if the search field has a value
			if ($_POST['search_field_name'] != '') {
				$search_field = $_POST['search_field_name'];
				$qry[] = "(pi_lname like '%$search_field%' || pi_fname like '%$search_field%' || pi_mname like '%$search_field%' || concat(pi_fname,' ',pi_mname,' ',pi_lname) LIKE '%$search_field%')";
			}
			if ($_POST['search_field_id'] != ''){
				$search_field_id = $_POST['search_field_id'];
				$qry[] = "emp_idnum like '%$search_field_id%'";
			}
			if($_POST['search_position'] != ''){
				$search_position = $_POST['search_position'];
				$qry[] = "post_name like '%$search_position%'";
			}
			if($_POST['search_department'] != ''){
				$search_department = $_POST['search_department'];
				$qry[] = "ud_name like '%$search_department%'";
			}
			if($_POST['search_company_name'] != ''){
				$search_company = $_POST['search_company_name'];
				$qry[] = "comp_name like '%$search_company%'";
			}
			if($_POST['search_location'] != ''){
				$search_location = $_POST['search_location'];
				$qry[] = "branchinfo_name like '%$search_location%'";
			}
			if($_POST['search_status'] != ''){
				$search_status = $_POST['search_status'];
				$qry[] = "am.emp_stat like '%$search_status%'";
			} else {
				$qry[]="am.emp_stat in ('1','7','4','10')";
			}
		$listcomp =  $_SESSION[admin_session_obj][user_comp_list2];
		$listloc =  $_SESSION[admin_session_obj][user_branch_list2];
		$listpgroup =  $_SESSION[admin_session_obj][user_paygroup_list2];
		IF(count($listcomp)>0){
			$qry[] = "am.comp_id in (".$listcomp.")";//company that can access
		}
		IF(count($listloc)>0){
			$qry[] = "am.branchinfo_id in (".$listloc.")";//location that can access
		}
		IF(count($listpgroup)>0){
			$qry[] = "ppuser.pps_id in (".$listpgroup.")";//pay group that can access
		}
		
		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" WHERE ".implode(" AND ",$qry):"";
		// Sort field mapping
		$arrSortBy = array(
		 "viewdata"=>"viewdata"
		,"emp_idnum"=>"am.emp_idnum"
		,"pi_lname"=>"pinfo.pi_lname"
		,"pi_fname"=>"pinfo.pi_fname"
		,"pi_mname"=>"pi_mname"
		,"post_name"=>"post_name"
		,"comp_name"=>"comp_name"
		,"branchinfo_name"=>"branchinfo_name"
		,"ud_name"=>"ud_name"
		);
		if (isset($_GET['sortby'])) {
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		} else {
			$strOrderBy = " order by comp.comp_name, bran.branchinfo_name, pinfo.pi_lname";
		}
//		exit;
		
		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$viewLink = "";
		$empinfoLink = "<a href=\"?statpos=emp_masterfile&empinfo=',am.emp_id,'#tab-1\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."content.png\" title=\"Employment Details\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
		//$empinfoLink = "<a href=\"?statpos=emp_masterfile&empinfo=',am.emp_id,'#tab-1\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."sicon_.png\" title=\"Employment Details\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
		$editLink = "<a href=\"?statpos=emp_masterfile&edit=',am.emp_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/edit.png\" title=\"Edit\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
		$emp_num_ = "<a href=\"?statpos=emp_masterfile&empinfo=',am.emp_id,'#tab-1\">',am.emp_idnum,'</a>";
		$emp_lname_ = "<a href=\"?statpos=emp_masterfile&empinfo=',am.emp_id,'#tab-1\">',pinfo.pi_lname,'</a>";
		$emp_fname_ = "<a href=\"?statpos=emp_masterfile&empinfo=',am.emp_id,'#tab-1\">',pinfo.pi_fname,'</a>";
		$delLink = "<a href=\"?statpos=emp_masterfile&delete=',am.emp_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";

		// SqlAll Query
		$sql = "SELECT am.*,CONCAT('$emp_num_') as emp_idnum, CONCAT('$emp_lname_') as pi_lname, comp.comp_name, CONCAT('$emp_fname_') as pi_fname, IF(pinfo.pi_mname!='', CONCAT(UPPER(SUBSTRING(pinfo.pi_mname,1,1)),'.'),'') as pi_mname, post.post_name, pinfo.pi_emailone,
				CONCAT('$viewLink','$empinfoLink') as viewdata, dept.ud_name, bran.branchinfo_name
						FROM emp_masterfile am
						JOIN emp_personal_info pinfo on (pinfo.pi_id=am.pi_id)
						JOIN payroll_pps_user ppuser on (ppuser.emp_id=am.emp_id)
						LEFT JOIN emp_position post on (post.post_id=am.post_id)
						LEFT JOIN company_info comp on (comp.comp_id=am.comp_id)
						LEFT JOIN app_userdept dept on (dept.ud_id=am.ud_id)
						LEFT JOIN branch_info bran on (bran.branchinfo_id=am.branchinfo_id)
						$criteria
						$strOrderBy";
						
		// Field and Table Header Mapping
		$arrFields = array(
		 "viewdata"=>"Action"
		,"emp_idnum"=>"Emp No"
		,"pi_lname"=>"Last Name"
		,"pi_fname"=>"First Name"
		,"pi_mname"=>"MI"
		,"post_name"=>"Position"
		,"comp_name"=>"Company"
		,"branchinfo_name"=>"Location"
		,"ud_name"=>"Department"
		);
		
		// Column (table data) User Defined Attributes
		$arrAttribs = array(
		 "viewdata" => "width='40' align='center'"
		);
		
		// Process the Table List
		$tblDisplayList = new clsTableList($this->conn);
		$tblDisplayList->arrFields = $arrFields;
		$tblDisplayList->paginator->linkPage = "?$queryStr";
		$tblDisplayList->sqlAll = $sql;
		$tblDisplayList->sqlCount = $sqlcount;
		$tblDisplayList->tblBlock->assign('deptList',$tblDisplayList->departments());
		$tblDisplayList->tblBlock->assign('compList',$tblDisplayList->comp());
		$tblDisplayList->tblBlock->assign('posList',$tblDisplayList->position());
		$tblDisplayList->tblBlock->assign('locList',$tblDisplayList->location());
		$tblDisplayList->tblBlock->assign('empStat',$this->empstat());
//		$tblDisplayList->tblBlock->templateFile = "table_nosort.tpl.php";
//		$tblDisplayList->tblBlock->assign("noPaginatorStart","<!--");
//		$tblDisplayList->tblBlock->assign("noPaginatorEnd","-->");
		$tblDisplayList->tblBlock->assign("title","Employee Information");
		return $tblDisplayList->getTableList($arrAttribs);
	}
	
	function getSalaryList(){
//		$this->conn->debug=1;
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
				$qry[] = "(salarytype_name like '%$search_field%' || salaryinfo_effectdate like '%$search_field%' || salaryinfo_basicrate like '%$search_field%')";

			}
		}

		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		 "salarytype_name"=>"salarytype_name"
		,"salaryinfo_effectdate"=>"salaryinfo_effectdate"
		,"salaryinfo_basicrate"=>"salaryinfo_basicrate"
		,"salaryinfo_ecola"=>"salaryinfo_ecola"
		,"salaryinfo_ceilingpay"=>"salaryinfo_ceilingpay"
		,"salaryinfo_isactive"=>"salaryinfo_isactive"
		);

		if(isset($_GET['sortby'])){
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}else{
			$strOrderBy = " order by sinfo.salaryinfo_effectdate DESC";
		}

		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$editLink = "<a href=\"?statpos=emp_masterfile&empinfo=',sinfo.emp_id,'&empsalaryinfoedit=',sinfo.salaryinfo_id,'#tab-2\">',stype.salarytype_name,'</a>";
		$delLink = "<a href=\"?statpos=emp_masterfile&empinfo=',sinfo.emp_id,'&empsalaryinfodelete=',sinfo.salaryinfo_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";

		$objClsMngeDecimal = new Application();
		// SqlAll Query
		$sql = "SELECT am.*, stype.*, sinfo.*, pinfo.pi_lname, comp.comp_name, pinfo.pi_fname, pinfo.pi_emailone,
				FORMAT(sinfo.salaryinfo_basicrate,".$objClsMngeDecimal->getGeneralDecimalSettings().") as salaryinfo_basicrate,
				FORMAT(sinfo.salaryinfo_ecola,".$objClsMngeDecimal->getGeneralDecimalSettings().") as salaryinfo_ecola,
				FORMAT(sinfo.salaryinfo_ceilingpay,".$objClsMngeDecimal->getGeneralDecimalSettings().") as salaryinfo_ceilingpay,
				CONCAT(UPPER(SUBSTRING(pinfo.pi_mname,1,1)),'.') as pi_mname, post.post_name, 
				IF(sinfo.salaryinfo_isactive='1','Active','Inactive') as salaryinfo_isactive
						FROM salary_info sinfo
						LEFT JOIN salary_type stype on (sinfo.salarytype_id=stype.salarytype_id)
						LEFT JOIN emp_masterfile am on (am.emp_id=sinfo.emp_id)
						LEFT JOIN emp_personal_info pinfo on (pinfo.pi_id=am.pi_id)
						LEFT JOIN emp_position post on (post.post_id=am.post_id)
						LEFT JOIN company_info comp on (comp.comp_id=am.comp_id)
						WHERE sinfo.emp_id = '".$_GET['empinfo']."'
						$criteria
						$strOrderBy";

		// Field and Table Header Mapping
		$arrFields = array(
		 "salarytype_name"=>"Salary Type"
		,"salaryinfo_effectdate"=>"Effective Date"
		,"salaryinfo_basicrate"=>"Basic Rate"
		,"salaryinfo_ecola"=>"COLA"
		,"salaryinfo_ceilingpay"=>"Minimum Net Pay"
		,"salaryinfo_isactive"=>"Status"
		);

		// Column (table data) User Defined Attributes
		$arrAttribs = array(
		"viewdata"=>"width='50' align='center'"
		,"salaryinfo_ceilingpay"=>"width='130'"
		,"salaryinfo_isactive"=>"width='50'"
		);

		// Process the Table List
		$tblDisplayList = new clsTableList($this->conn);
		$tblDisplayList->arrFields = $arrFields;
		$tblDisplayList->paginator->linkPage = "?$queryStr";
		$tblDisplayList->sqlAll = $sql;
		$tblDisplayList->sqlCount = $sqlcount;
		$tblDisplayList->tblBlock->templateFile = "table_nosort.tpl.php";
		$tblDisplayList->tblBlock->assign("noSearchStart","<!--");
		$tblDisplayList->tblBlock->assign("noSearchEnd","-->");
		return $tblDisplayList->getTableList($arrAttribs);
	}
	
	function loanList(){
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
				$qry[] = "(loan_voucher_no like '%$search_field%' || psa_name like '%$search_field%')";
			}
		}
		if($_GET['statpos']=='loan_app'){
			$qry[] = "loan_info.emp_id='".$_GET['edit']."'";
		}else{
			$qry[] = "loan_info.emp_id='".$_GET['empinfo']."'";
		}
		$qry[] = "loan_info.psa_id=payroll_ps_account.psa_id";
		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";
		
		// Sort field mapping
		$arrSortBy = array(
		 "viewdata"=>"viewdata"
		,"psa_id"=>"psa_id"
		,"loan_voucher_no"=>"loan_voucher_no"
		,"loan_monthly_amortization"=>"loan_monthly_amortization"
		,"loan_payperperiod"=>"loan_payperperiod"
		,"loan_dategrant"=>"loan_dategrant"
		,"loan_startdate"=>"loan_startdate"
		,"loan_enddate"=>"loan_enddate"
		,"loan_suspend"=>"loan_suspend"
		);

		if (isset($_GET['sortby'])) {
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		} else {
			$strOrderBy = " order by loan_info.loan_id desc";
		}
		
		if ($_GET['statpos']=='loan_app') {
			$loanPaymentHistory = "<a class=\"popup\" href=\"popup.php?statpos=popuploanpaymenthistory&emp_id=".$_GET['edit']."&loan_id=',loan_info.loan_id,'&loantype_id=',loan_info.loantype_id,'\" target=\"_blank\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."content.png\" title=\"Loan Payment History\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
			$editLink = "<a href=\"?statpos=loan_app&loanedit=',loan_info.loan_id,'&edit=".$_GET['edit']."\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/edit.png\" title=\"Edit\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
			$delLink = "<a href=\"?statpos=loan_app&edit=',loan_info.emp_id,'&loandelete=',loan_info.loan_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete this loan?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
			
			// Field and Table Header Mapping
			$arrFields = array(
			 "viewdata"=>"Action"
			,"psa_id"=>"Pay Element"
			,"loan_voucher_no"=>"Voucher#"
			,"loan_principal"=>"Principal Amount"
			,"loan_monthly_amortization"=>"Amortization"
			,"loan_payperperiod"=>"Pay Period Deduction"
			,"loan_dategrant"=>"Date Granted"
			,"loan_startdate"=>"Start Date"
			,"loan_enddate"=>"End Date"
			,"loan_suspend"=>"Suspended"
			);
		} else {
			// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
			$viewLink = "";
//			$editLink = "<a href=\"?statpos=emp_masterfile&loanedit=',loan_info.loan_id,'&empinfo=".$_GET['empinfo']."#tab-8\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/edit.png\" title=\"Edit\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
//			$delLink = "<a href=\"?statpos=emp_masterfile&empinfo=',loan_info.emp_id,'&loandelete=',loan_info.loan_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\"  border=0 width=\"16\" height=\"16\"></a>";
		// Field and Table Header Mapping
			$arrFields = array(
			 "psa_id"=>"Pay Element"
			,"loan_voucher_no"=>"Voucher#"
			,"loan_principal"=>"Principal Amount"
			,"loan_monthly_amortization"=>"Amortization"
			,"loan_payperperiod"=>"PP Deduction"
			,"loan_dategrant"=>"Date Granted"
			,"loan_startdate"=>"Start Date"
			,"loan_enddate"=>"End Date"
			,"loan_suspend"=>"Suspended"
			,"loan_balance"=>"Balance"
			,"loan_ytd"=>"YTD"
			);
		}
		// SqlAll Query
		//$sql = "select 	*,CONCAT('$viewLink','$editLink') as viewdata
		//				from loan_info where loan_addwho='".AppUser::getData('user_name')."' order by psa_id";
		$objClsMngeDecimal = new Application();
		$sql = "SELECT payroll_ps_account.psa_name as psa_id,loan_info.loan_voucher_no,
				loan_info.loan_monthly_amortization as loan_monthly_amortization,
				loan_principal,
				loan_ytd,
				loan_balance,
				loan_payperperiod,
				loan_info.loan_dategrant,loan_info.loan_startdate,loan_info.loan_enddate,loan_info.loan_id,
				IF(loan_info.loan_suspend='0','No','Yes') as loan_suspend,CONCAT('$viewLink','$loanPaymentHistory','$editLink','$delLink') as viewdata
					FROM loan_info,payroll_ps_account
					$criteria
					$strOrderBy";
		
		// Column (table data) User Defined Attributes
		$arrAttribs = array(
		"viewdata"=>"width='60' align='center'"
		,"loan_suspend"=>"align='center'"
		);

		// Process the Table List
		$tblDisplayList = new clsTableList($this->conn);
		$tblDisplayList->arrFields = $arrFields;
		$tblDisplayList->paginator->linkPage = "?$queryStr";
		$tblDisplayList->sqlAll = $sql;
		$tblDisplayList->sqlCount = $sqlcount;
		$tblDisplayList->tblBlock->templateFile = "table_nosort.tpl.php";
		$tblDisplayList->tblBlock->assign("noSearchStart","<!--");
		$tblDisplayList->tblBlock->assign("noSearchEnd","-->");

		return $tblDisplayList->getTableList($arrAttribs);
	}
	
	function benList($emp_id_= null){
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
				$qry[] = "(psa.psa_name like '%$search_field%' || ben_startdate like '%$search_field%' || ben_suspend like '%$search_field%')";

			}
		}
		IF($_GET['statpos']=='recur_setup'){
			$qry[] = "emp.emp_id='".$emp_id_."'";
		}else{
			$qry[] = "emp.emp_id='".$_GET['empinfo']."'";
		}
		$qry[] = "psa.psa_id = emp.psa_id";
		
		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		 "viewdata"=>"viewdata"
		,"psa_name"=>"psa_name"
		,"ben_amount"=>"ben_amount"
		,"ben_payperday"=>"ben_payperday"
		,"ben_startdate"=>"ben_startdate"
		,"ben_enddate"=>"ben_enddate"
		,"ben_suspend"=>"ben_suspend"
		);

		if (isset($_GET['sortby'])) {
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}
		
		if ($_GET['statpos']=='recur_setup') {
			$editLink = "<a href=\"?statpos=recur_setup&benedit=',ben_id,'&edit=".$emp_id_."\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/edit.png\" title=\"Edit\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
			$delLink = "<a href=\"?statpos=recur_setup&edit=',emp.emp_id,'&bendelete=',emp.ben_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\"  border=0 width=\"16\" height=\"16\"></a>";
			// Field and Table Header Mapping
			$arrFields = array(
			 "viewdata"=>"Action"
			,"psa_name"=>"Pay Element"
			,"ben_amount"=>"Base Amount"
			,"ben_payperday"=>"Amt/payroll"
			,"ben_startdate"=>"Start Date"
			,"ben_enddate"=>"End Date"
			,"ben_suspend"=>"Suspended"
			);
		} else {
			// Field and Table Header Mapping
			$arrFields = array(
			 "psa_name"=>"Pay Element"
			,"ben_amount"=>"Base Amount"
			,"ben_payperday"=>"Amt/payroll"
			,"ben_startdate"=>"Start Date"
			,"ben_enddate"=>"End Date"
			,"ben_suspend"=>"Suspended"
		);
		}
		$objClsMngeDecimal = new Application();
		// SqlAll Query
		$sql = "select psa.psa_name,emp.*,
				FORMAT(emp.ben_amount,".$objClsMngeDecimal->getGeneralDecimalSettings().") as ben_amount,
				FORMAT(emp.ben_payperday,".$objClsMngeDecimal->getGeneralDecimalSettings().") as ben_payperday,
				CONCAT('$viewLink','$editLink','$delLink') as viewdata, IF(emp.ben_suspend='0','No','Yes') as ben_suspend
				from emp_benefits as emp,payroll_ps_account as psa 
				$criteria 
				order by emp.ben_id desc";

		// Column (table data) User Defined Attributes
		$arrAttribs = array(
		"viewdata"=>"width='50' align='center'"
		);

		// Process the Table List
		$tblDisplayList = new clsTableList($this->conn);
		$tblDisplayList->arrFields = $arrFields;
		$tblDisplayList->paginator->linkPage = "?$queryStr";
		$tblDisplayList->sqlAll = $sql;
		$tblDisplayList->sqlCount = $sqlcount;
		$tblDisplayList->tblBlock->templateFile = "table_nosort.tpl.php";
		$tblDisplayList->tblBlock->assign("noSearchStart","<!--");
		$tblDisplayList->tblBlock->assign("noSearchEnd","-->");

		return $tblDisplayList->getTableList($arrAttribs);
	}
	
	function leaveList(){
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
				$qry[] = "(leave_name like '%$search_field%')";

			}
		}
		
//		$qry[] = "psa.psa_id = emp.psa_id";
		$qry[] = "empleave.emp_id='".$_GET['empinfo']."'";
		
		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		 "leave_name"=>"leave_name"
		,"empleave_used_day"=>"empleave_used_day"
		,"empleave_available_day"=>"empleave_available_day"
		,"empleave_credit"=>"empleave_credit"
		,"empleave_stat"=>"empleave_stat"
		);

		if(isset($_GET['sortby'])){
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}

		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$viewLink = "";
		$editLink = "<a href=\"?statpos=emp_masterfile&leaveedit=',empleave.empleave_id,'&empinfo=".$_GET['empinfo']."#tab-6\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/edit.png\" title=\"Edit\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
		$delLink = "<a href=\"?statpos=emp_masterfile&empinfo=',empleave.emp_id,'&leavedelete=',empleave.empleave_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\"  border=0 width=\"16\" height=\"16\"></a>";
		
		$objClsMngeDecimal = new Application();
		// SqlAll Query
		//$sql = "select 	*,CONCAT('$viewLink','$editLink') as viewdata
		//				from loan_info where loan_addwho='".AppUser::getData('user_name')."' order by psa_id";
		$sql = "select leavetype.leave_name,empleave.*,
				FORMAT(empleave.empleave_used_day,".$objClsMngeDecimal->getGeneralDecimalSettings().") as empleave_used_day,
				FORMAT(empleave.empleave_available_day,".$objClsMngeDecimal->getGeneralDecimalSettings().") as empleave_available_day,
				FORMAT(empleave.empleave_credit,".$objClsMngeDecimal->getGeneralDecimalSettings().") as empleave_credit,
				CONCAT('$viewLink','$editLink','$delLink') as viewdata,
				IF(empleave_stat='1','Active','Inactive') as empleave_stat
				from emp_leave as empleave
				left join leave_type as leavetype on (empleave.leave_id=leavetype.leave_id)
				$criteria 
				order by empleave.empleave_id desc";
		
		// Field and Table Header Mapping
		$arrFields = array(
		 "leave_name"=>"Leave Type"
		,"empleave_used_day"=>"Leaves Taken"
		,"empleave_available_day"=>"Leave Balance"
		,"empleave_credit"=>"Leave Credit"
		,"empleave_stat"=>"Status"
		);

		// Column (table data) User Defined Attributes
		$arrAttribs = array(
		"viewdata"=>"width='50' align='center'"
		);

		// Process the Table List
		$tblDisplayList = new clsTableList($this->conn);
		$tblDisplayList->arrFields = $arrFields;
		$tblDisplayList->paginator->linkPage = "?$queryStr";
		$tblDisplayList->sqlAll = $sql;
		$tblDisplayList->sqlCount = $sqlcount;
		$tblDisplayList->tblBlock->templateFile = "table_nosort.tpl.php";
		$tblDisplayList->tblBlock->assign("noSearchStart","<!--");
		$tblDisplayList->tblBlock->assign("noSearchEnd","-->");

		return $tblDisplayList->getTableList($arrAttribs);
	}
	
	function dependList(){
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
				$qry[] = "(depnd_lname like '%$search_field%')";
			}
		}
		
		$qry[]="depnd.emp_id = '".$_GET['empinfo']."'";
		
		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		 "viewdata"=>"viewdata"
		,"depnd_fname"=>"depnd_fname"
		,"depnd_bdate"=>"depnd_bdate"
		,"depnd_relationship"=>"depnd_relationship"
		,"counted"=>"counted"
		);

		if(isset($_GET['sortby'])){
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}

		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$viewLink = "";
		$editLink = "<a href=\"?statpos=emp_masterfile&empinfo=',depnd.emp_id,'&depndedit=',depnd.depnd_id,'#tab-9\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/edit.png\" title=\"Edit\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
		$delLink = "<a href=\"?statpos=emp_masterfile&empinfo=',depnd.emp_id,'&depnddelete=',depnd.depnd_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
		
		// SqlAll Query
		$sql = "SELECT depnd.*, CONCAT('$viewLink','$editLink','$delLink') AS viewdata,
				(YEAR(CURDATE())-YEAR(depnd_bdate)) - (RIGHT(CURDATE(),5)<RIGHT(depnd_bdate,5)) AS depnd_age
				FROM dependent_info AS depnd
				$criteria
				$strOrderBy";
		
		// Field and Table Header Mapping
		$arrFields = array(
		 "viewdata"=>"Action"
		,"depnd_fname"=>"Dependent Name"
		,"depnd_bdate"=>"Date of Birth"
		,"depnd_age"=>"Age"
		,"depnd_relationship"=>"Dependent Relation"
		);

		// Column (table data) User Defined Attributes
		$arrAttribs = array(
		"viewdata"=>"width='50' align='center'"
		);

		// Process the Table List
		$tblDisplayList = new clsTableList($this->conn);
		$tblDisplayList->arrFields = $arrFields;
		$tblDisplayList->paginator->linkPage = "?$queryStr";
		$tblDisplayList->sqlAll = $sql;
		$tblDisplayList->sqlCount = $sqlcount;
		$tblDisplayList->tblBlock->templateFile = "table_nosort.tpl.php";
		$tblDisplayList->tblBlock->assign("noSearchStart","<!--");
		$tblDisplayList->tblBlock->assign("noSearchEnd","-->");

		return $tblDisplayList->getTableList($arrAttribs);
	}
	
	function getBankinfoList(){
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
				$qry[] = "(bankiemp_acct_no like '%$search_field%' || bankiemp_acct_name like '%$search_field%' || bankiemp_swift_code like '%$search_field%' || banklist_name like '%$search_field%')";

			}
		}
		$qry[] = "emp_id='".$_GET['empinfo']."'";
		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" WHERE ".implode(" and ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		 "banklist_name"=>"banklist_name"
		,"baccntype_name"=>"baccntype_name"
		,"bankiemp_acct_no"=>"bankiemp_acct_no"
		,"bankiemp_acct_name"=>"bankiemp_acct_name"
		,"bankiemp_swift_code"=>"bankiemp_swift_code"
		,"bankiemp_perc"=>"bankiemp_perc"
		);

		if(isset($_GET['sortby'])){
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}

		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$viewLink = "";
		$editLink = "<a href=\"?statpos=emp_masterfile&empinfo=',binfoemp.emp_id,'&empinfoedit=',binfoemp.bankiemp_id,'#tab-4\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/edit.png\" title=\"Edit\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
		$delLink = "<a href=\"?statpos=emp_masterfile&empinfo=',binfoemp.emp_id,'&empinfodelete=',binfoemp.bankiemp_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\"  border=0 width=\"16\" height=\"16\"></a>";
		
		$objClsMngeDecimal = new Application();
		// SqlAll Query
		$sql = "SELECT binfoemp.*, binfolist.banklist_name,btype.baccntype_name,
				FORMAT(binfoemp.bankiemp_perc,".$objClsMngeDecimal->getGeneralDecimalSettings().") as bankiemp_perc,
				CONCAT('$editLink') as viewdata
						FROM bank_infoemp binfoemp
						JOIN bank_list binfolist on (binfolist.banklist_id=binfoemp.banklist_id)
						JOIN bnkaccnt_type btype on (btype.baccntype_id=binfoemp.baccntype_id)
						$criteria
						$strOrderBy";

		// Field and Table Header Mapping
		$arrFields = array(
		 "banklist_name"=>"Bank"
		,"baccntype_name"=>"Acct. Type"
		,"bankiemp_acct_no"=>"Acct. No"
		,"bankiemp_acct_name"=>"Acct. Name"
		,"bankiemp_swift_code"=>"Swift Code"
		,"bankiemp_perc"=>"Percentage"
		);

		// Column (table data) User Defined Attributes
		$arrAttribs = array(
		"viewdata"=>"width='50' align='center'"
		);

		// Process the Table List
		$tblDisplayList = new clsTableList($this->conn);
		$tblDisplayList->arrFields = $arrFields;
		$tblDisplayList->paginator->linkPage = "?$queryStr";
		$tblDisplayList->sqlAll = $sql;
		$tblDisplayList->sqlCount = $sqlcount;
		$tblDisplayList->tblBlock->templateFile = "table_nosort.tpl.php";
		$tblDisplayList->tblBlock->assign("noSearchStart","<!--");
		$tblDisplayList->tblBlock->assign("noSearchEnd","-->");

		return $tblDisplayList->getTableList($arrAttribs);
	}
	
	function baseempdec_rel()
	{
		$sql = "select * from empdec_rel";

		// Field and Table Header Mapping
		// Field and Table Header Mapping
		$arrFields = array(
		 "viewdata"=>"Action"
		,"empdec_effectivedate"=>"Effective Date"
		,"sc_id"=>"Statutory Policy"
		,"dec_id"=>"Statutory Scheme"
		,"empdec_remarks"=>"Remarks"
		);

		// Column (table data) User Defined Attributes
		$arrAttribs = array(
		"mnu_ord"=>" align='right'",
		"viewdata"=>"width='60' align='center'"
		);
		// Process the Table List
		$tblDisplayList = new clsTableList($this->conn);
		$tblDisplayList->arrFields = $arrFields;
		$tblDisplayList->paginator->linkPage = "?$queryStr";
		$tblDisplayList->sqlAll = $sql;
		$tblDisplayList->sqlCount = $sqlcount;

		return $tblDisplayList->getTableList($arrAttribs);
		
		
	}
	
	/**
	 * @note: Get Department List
	 */
	function departments(){
		$sql = "select ud_id,ud_name from app_userdept order by ud_name asc";
		$rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF){
			$deptvalue[] = $rsResult->fields['ud_id'];
			$dept[] = $rsResult->fields['ud_name'];
			$rsResult->MoveNext();
		}
		$_SESSION['deptvalue'] = $deptvalue;
		return $dept;
	}
	
	/**
	 * @note: Get Company Information
	 */
	function comp(){
		$sql = "select comp_name,comp_id from company_info order by comp_name asc";
		$rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF){
			$compvalue[] = $rsResult->fields['comp_id'];
			$comp[] = $rsResult->fields['comp_name'];
			$rsResult->MoveNext();
		}
		$_SESSION['compvalue'] = $compvalue;
		return $comp;
	}
	
	/**
	 * @note: Get Employee Type
	 */
	function emptype(){
		$sql = "select emptype_id,emptype_name from emp_type order by emptype_name asc";
		$rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF){
			$typevalue[] = $rsResult->fields['emptype_id'];
			$type[] = $rsResult->fields['emptype_name'];
			$rsResult->MoveNext();
		}
		$_SESSION['emptypevalue'] = $typevalue;
		return $type;
	}
	
	/**
	 * @note: Get Employee Category List
	 */
	function empcateg(){
		$sql = "select empcateg_id, empcateg_name from emp_category order by  empcateg_name asc";
		$rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF){
			$empcategvalue[] = $rsResult->fields['empcateg_id'];
			$empcateg[] = $rsResult->fields['empcateg_name'];
			$rsResult->MoveNext();
		}
		$_SESSION['empcategvalue'] = $empcategvalue;
		return $empcateg;
	}
	
	/**
	 * @note: Get Employee Status List
	 */
	function empstat(){
		$sql = "SELECT emp201status_id, emp201status_name FROM emp_201status ORDER BY  emp201status_name asc";
		$rsResult = $this->conn->Execute($sql);
		$cResult = array();
		while ( !$rsResult->EOF ) {       	
			$cResult[] = $rsResult->fields;        	
        	$rsResult->MoveNext();
        }
        return $cResult;
	}
	
	/**
	 * @note: Get Branch List
	 */
	function brachlist(){
		$listloc = $_SESSION[admin_session_obj][user_branch_list2];
		IF(count($listloc)>0){
			$qry[] = "branchinfo_id in (".$listloc.")";//location that can access
		}
		$criteria = (count($qry)>0)?" WHERE ".implode(" AND ",$qry):"";
		$sql = "SELECT branchinfo_id, branchinfo_name FROM branch_info $criteria ORDER BY  branchinfo_name asc";
		$rsResult = $this->conn->Execute($sql);
		$cResult = array();
		while ( !$rsResult->EOF ) {       	
			$cResult[] = $rsResult->fields;        	
        	$rsResult->MoveNext();
        }
        return $cResult;
	}
	
	function position(){
		$sql = "select post_id,post_name from emp_position order by  post_name asc";
		$rsResult = $this->conn->Execute($sql);
		
		while(!$rsResult->EOF){
		$positionvalue[] = $rsResult->fields['post_id'];
		$position[] = $rsResult->fields['post_name'];
		$rsResult->MoveNext();
		}
		$_SESSION['positionvalue'] = $positionvalue;
		return $position;
		
	}
	
	function tax_exemption(){
		$sql = "SELECT taxep_id, taxep_name FROM tax_excep order by taxep_id asc";
		$rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF){
			$taxepvalue[] = $rsResult->fields['taxep_id'];
			$taxep[] = $rsResult->fields['taxep_name'];
			$rsResult->MoveNext();
		}
		$_SESSION['taxepvalue'] = $taxepvalue;
		return $taxep;
	}


	function loan(){//drop down pay element for loan
		$sql = "select psa_id,psa_name from payroll_ps_account where psa_type='2' and psa_clsfication='5' order by psa_name asc";
		$rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF){
			$loanvalue[] = $rsResult->fields['psa_id'];
			$loan[] = $rsResult->fields['psa_name'];
			$rsResult->MoveNext();
		}
		$_SESSION['loanvalue'] = $loanvalue;
		return $loan;
	}
	
	/**
	 * Loan Type
	 */
	function loantype($psa_id_ = ''){//drop down pay element for loan type
//		$sql = "select psa_id,psachild_name,psachild_id from payroll_psa_child where psachild_type='2' and psa_id='".$psa_id_."' order by  psachild_name asc";
		$sql = "Select * from loan_type order by  loantype_desc asc";
		$rsResult = $this->conn->Execute($sql);
		while ( !$rsResult->EOF ) {       	
			$cResult[] = $rsResult->fields;        	
        	$rsResult->MoveNext();
        }
        return $cResult;
		
//		while(!$rsResult->EOF){
//		$loantypevalue[] = $rsResult->fields['loantype_id'];
//		$loantype[] = $rsResult->fields['loantype_desc'];
//		$rsResult->MoveNext();
//		}
//		$_SESSION['$loantypevalue'] = $loantypevalue;
//		printa ($_SESSION['$loantypevalue']);
//		exit;
//		return $loantype;
	}
	
	function emp_bank_info(){//drop down bank list
		//edit by: jim change in table.
		/*$sql = "select info.bank_id,list.banklist_id,list.banklist_name 
				from bank_list list,bank_info info 
				where info.comp_id = (select comp_id from emp_masterfile where emp_id='".$_GET['empinfo']."')
				AND list.banklist_id = info.banklist_id";*/
		$sql = "select * from bank_list";
		$rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF){
			$bank_info[] = $rsResult->fields;
			$rsResult->MoveNext();
		}
		return $bank_info;
	}
	
	function payelement(){//drop down pay element deduction/earning
		$sql = "select psa_id,if(psa_type=1,CONCAT('Earning - ',psa_name),CONCAT('Employee Deduction - ',psa_name)) as psa_name from payroll_ps_account where psa_type IN(1,2) order by  psa_type,psa_name asc";
		$rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF){
			$payvalue[] = $rsResult->fields['psa_id'];
			$pay[] = $rsResult->fields['psa_name'];
			$rsResult->MoveNext();
		}
		$_SESSION['payvalue'] = $payvalue;
		return $pay;
	}	
	
	function deductiontype(){//drop down pay element sss,phic.. in gov information tab
		$sql = "select dec_code,dec_name,dec_id from deduction_type order by dec_name asc";
		$rsResult = $this->conn->Execute($sql);
		
		while(!$rsResult->EOF){
		//$payvalue[] = $rsResult->fields['psa_id'];
		$deduction[] = $rsResult->fields;
		$rsResult->MoveNext();
		}
		//$_SESSION['payvalue'] = $payvalue;
		return $deduction;
		
	}
	
	function salarytype(){//drop down salary type hourly,daily,weekly in compensation tab
		$objData = $this->conn->Execute("select * from salary_type");
		$cResult = array();
		while ( !$objData->EOF ) {       	
			$cResult[] = $objData->fields;        	
        	$objData->MoveNext();
        }
        return $cResult;
//	}
//		$sql = "select salarytype_name, salarytype_id from salary_type";
//		$rsResult = $this->conn->Execute($sql);
//		$salarytype = array();
//		while(!$rsResult->EOF){
//			$salarytype[] = $rsResult->fields;
//			$rsResult->MoveNext();
//		}
//		
//		return $salarytype;
	}

	function leavetype(){//drop down salary type hourly,daily,weekly in compensation tab
		
		$objData = $this->conn->Execute("select * from leave_type");
		$cResult = array();
		while ( !$objData->EOF ) {       	
			$cResult[] = $objData->fields;        	
        	$objData->MoveNext();
        }
        return $cResult;
//	}
//		$sql = "select salarytype_name, salarytype_id from salary_type";
//		$rsResult = $this->conn->Execute($sql);
//		$salarytype = array();
//		while(!$rsResult->EOF){
//			$salarytype[] = $rsResult->fields;
//			$rsResult->MoveNext();
//		}
//		
//		return $salarytype;
	}
	
	function loanData(){
		$objClsMngeDecimal = new Application();
		//@note:jim(20121117), pls don't format the data.
		$sql = "select *,
				REPLACE(FORMAT(loan_principal,".$objClsMngeDecimal->getGeneralDecimalSettings()."),',','') as loan_principal,
				REPLACE(FORMAT(loan_interestamount,".$objClsMngeDecimal->getGeneralDecimalSettings()."),',','') as loan_interestamount,
				REPLACE(FORMAT(loan_interestperc,".$objClsMngeDecimal->getGeneralDecimalSettings()."),',','') as loan_interestperc,
				REPLACE(FORMAT(loan_monthly_amortization,".$objClsMngeDecimal->getGeneralDecimalSettings()."),',','') as loan_monthly_amortization,
				REPLACE(FORMAT(loan_payperperiod,".$objClsMngeDecimal->getGeneralDecimalSettings()."),',','') as loan_payperperiod,
				REPLACE(FORMAT(loan_ytd,".$objClsMngeDecimal->getGeneralDecimalSettings()."),',','') as loan_ytd,
				REPLACE(FORMAT(loan_balance,".$objClsMngeDecimal->getGeneralDecimalSettings()."),',','') as loan_balance,
				REPLACE(FORMAT(loan_total,".$objClsMngeDecimal->getGeneralDecimalSettings()."),',','') as loan_total 
				from loan_info where loan_id='".$_GET['loanedit']."'";
		$rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF){
			$loanData[] = $rsResult->fields;
			$rsResult->MoveNext();
		}
		$loanData[0]['loan_startdate'] = explode(" ",$loanData[0]['loan_startdate']);
		$loanData[0]['loan_enddate'] = explode(" ",$loanData[0]['loan_enddate']);
		$loanData[0]['loan_periodselection'] = explode(",",$loanData[0]['loan_periodselection']);
		return $loanData;
	}	
	
	function saveGovInfo($update=false){
		//delete previous entries
		$sqlDel = "DELETE FROM period_benloanduc_sched WHERE empdd_id != '0' AND emp_id='".$_GET['empinfo']."'";
		$this->conn->Execute($sqlDel);
		//deduction type from the database
		$dd = $this->deductiontype();
		$x=0;
		foreach($dd as $type){
			$deduction = $dd_code[] = $dd[$x]['dec_code'];
			if(strtolower($deduction)=='tax'){
			 //save tax value
			 //Possible values: tax=1,mwe=2,others=3
			 $this->savePS('gov',$dd[$x]['dec_id'],$_POST['tax'],1);
			}else{
				if($_POST[$deduction]){//if excluded hdmf,ecola,phic,sss
					//do nothing
					$this->savePS('gov',$dd[$x]['dec_id'],0);
				}else{
					//loop the selection
					for($y=1; $y<6; $y++){
						if($_POST[$deduction.'_'.$y]){
							//save selection
							//$_POST[$deduction.'_'.$y];
							$this->savePS('gov',$dd[$x]['dec_id'],$y);
						}
					}
				}
			}
			$x++;
		}
		//printa($_POST);exit;
		//print implode(',',$dd_code);
	}
	
	function saveBankInfo($update=false){
		//this is used to save bank info
		foreach ($this->fieldMap_bankinfo as $key => $value) {
			if ($key=='emp_id') {
				$var[] = $key."='".$_GET['empinfo']."'";
			}else{
				$var[] = $key."='".$_POST[$value]."'";	
			}
		}
		$var[]="bankiemp_addwho='".AppUser::getData('user_name')."'";
		$fields = implode(", ",$var);
//		printa($_POST);
//		exit;		
		if($update==false){
			$sql = "insert into bank_infoemp set ".$fields;
		}else{
			$sql = "update bank_infoemp set ".$fields." where bankiemp_id='".$_GET['empinfoedit']."'";
		}
		if($this->conn->Execute($sql)){
			$redirect = $update?$_GET['empinfoedit']:mysql_insert_id();
			header("Location: transaction.php?statpos=emp_masterfile&empinfo=".$_GET['empinfo']."#tab-4");exit;
		}
		//printa($fields);exit;
		//printa($_POST);exit;
		//print implode(',',$dd_code);		
	}
	
	/**
	 * 
	 */
	function saveOTInfo($id_= ""){
		$flds[]="ot_id='".$_POST['ot_id']."'";
		$flds[]="fr_id='".$_POST['fr_id']."'";
		$flds[]="pps_id='".$_POST['pps_id']."'";

		$sql = "SELECT * FROM payroll_comp WHERE emp_id=?";
		$rsResult = $this->conn->Execute($sql,array($id_));
		if(!$rsResult->EOF){
			$fields = implode(", ",$flds);
			$sqlupdate = "UPDATE payroll_comp set $fields WHERE pc_id='".$rsResult->fields['pc_id']."'";
			$this->conn->Execute($sqlupdate);
		}else{
			$flds[]="emp_id='".$id_."'";
			$flds[]="pc_addwho='"._MODWHO."'";
			$fields = implode(", ",$flds);
			$sqlinsert = "INSERT INTO payroll_comp set $fields";
			$this->conn->Execute($sqlinsert);
		}
		
		/*To Check and save payroll_pps_user table*/
		$sql_ = "SELECT * FROM payroll_pps_user WHERE emp_id=?";
		$rsResult_ = $this->conn->Execute($sql_,array($id_));
		if (!$rsResult_->EOF) {
			$flds_[]="pps_id='".$_POST['pps_id']."'";
			$fields_ = implode(", ",$flds_);
			$sqlupdatepps = "UPDATE payroll_pps_user set $fields_ WHERE ppsu_id='".$rsResult_->fields['ppsu_id']."'";
			$this->conn->Execute($sqlupdate);
		} else {
			$flds_[]="pps_id='".$_POST['pps_id']."'";
			$flds_[]="emp_id='".$id_."'";
			$flds_[]="ppsu_addwho='"._MODWHO."'";
			$fields_ = implode(", ",$flds_);
			$sqlinsertpps = "INSERT INTO payroll_pps_user SET $fields_";
			$this->conn->Execute($sqlinsertpps);
		}
		$_SESSION['eMsg']="Successfully Saved Pay Calculation.";
	}
	
	function saveLeaveInfo(){
		//this is used to save bank info
		foreach ($this->fieldMap_leaveinfo as $key => $value){
			if($key=='emp_id'){
				$var[] = $key."='".$_GET['empinfo']."'";
			}elseif($key=='empleave_available_day'){
				if($update==false){
					$var[] = $key."='".$_POST['empleave_creadit']."'";
				}else{
					$var[] = $key."='".$_POST[$value]."'";
				}
			}else{
				$var[] = $key."='".$_POST[$value]."'";
			}
		}
				
		if($update==false){	
			$var[]="empleave_addwho='".AppUser::getData('user_name')."'";
			$fields = implode(", ",$var);
			
			$qry[]="emp_id='".$_GET['empinfo']."'";
			$qry[]="leave_id = '".$_POST['leave_id']."'";
		
			// put all query array into one string criteria
			$criteria = " where ".implode(" and ",$qry);
			$sqlLeave = "Select * from emp_leave $criteria";
			$rsResult = $this->conn->Execute($sqlLeave);
			if($rsResult->EOF){
				$sql = "insert into emp_leave set ".$fields;
			}else{
				$redirect = $update?$_GET['empinfoedit']:mysql_insert_id();
				header("Location: transaction.php?statpos=emp_masterfile&empinfo=".$_GET['empinfo']."#tab-6");
				exit;
			}
		}else{
			$var[]="empleave_updatewho='".AppUser::getData('user_name')."'";
			$flds[]="empleave_updatewhen='".date('Y-m-d H:i:s')."'";
			$fields = implode(", ",$var);
			$sql = "update emp_leave set ".$fields." where empleave_id='".$_GET['empinfoedit']."'";
		}
		
		if($this->conn->Execute($sql)){
			$redirect = $update?$_GET['empinfoedit']:mysql_insert_id();
			header("Location: transaction.php?statpos=emp_masterfile&empinfo=".$_GET['empinfo']."#tab-6");exit;
		}
	}
	
	function add_compensation($update=false){
		//this is used to save bank info
		foreach ($this->fieldMap_basic as $key => $value){
			if ($key=='emp_id'){
				$var[] = $key."='".$_GET['empinfo']."'";
			}else{
				$var[] = $key."='".$_POST[$value]."'";	
			}
		}
				
		//--------------------------->>
		//for pay group user
			$flds[] = "pps_id = '".$_POST['pps_id']."'";
			$flds[] = "emp_id = '".$_GET['empinfo']."'";
			$flds[] = "ppsu_addwho = '".AppUser::getData('user_name')."'";
			$fields_ = implode(", ",$flds);
		//---------------------------<<		
		
		if($update==false){
			$var[] ="salaryinfo_addwho='".AppUser::getData('user_name')."'";
			$fields = implode(", ",$var);
			
			$sqlsal ="Select * from salary_info where emp_id='".$_GET['empinfo']."' && salaryinfo_isactive = '1'";
			$rsResult_ = $this->conn->Execute($sqlsal);
			if($rsResult_->EOF){
				$sql = "insert into salary_info set ".$fields;
			}else{
				$sqlsalup = "update salary_info set salaryinfo_isactive='0' where salaryinfo_id='".$rsResult_->fields['salaryinfo_id']."'";
				$this->conn->Execute($sqlsalup);
				$sql = "insert into salary_info set ".$fields;
			}
			
			$sqlpps = "Select * from payroll_pps_user where emp_id='".$_GET['empinfo']."'";
			$rsResult = $this->conn->Execute($sqlpps);
			if($rsResult->EOF){
				$sql_ = "insert into payroll_pps_user set $fields_";
				$this->conn->Execute($sql_);
			}else{
				$sql_ = "update payroll_pps_user set $fields_ where emp_id='".$_GET['empinfo']."'";
				$this->conn->Execute($sql_);
			}
		}else{
			$var[] = "salaryinfo_updatewho = '".AppUser::getData('user_name')."'";
			$var[] = "salaryinfo_updatewhen = '".date('Y-m-d h:i:s')."'";
			$fields = implode(", ",$var);
			
			$sql = "update salary_info set ".$fields." where salaryinfo_id='".$_GET['empsalaryinfoedit']."'";
			
			$sql_ = "update payroll_pps_user set $fields_ where emp_id='".$_GET['empinfo']."'";
			$this->conn->Execute($sql_);
		}
		
		if($this->conn->Execute($sql)){
			$redirect = $update?$_GET['empsalaryinfoedit']:mysql_insert_id();
			header("Location: transaction.php?statpos=emp_masterfile&empinfo=".$_GET['empinfo']."#tab-2");exit;
		}else{
			print mysql_error();exit;
		}
		//printa($fields);exit;
		//printa($_POST);exit;
		//print implode(',',$dd_code);		
	}
	
	function govTableList(){
		$sql = "select * from period_benloanduc_sched where emp_id='".$_GET['empinfo']."' AND empdd_id != '0'";
		$rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF){
			$govList[] = $rsResult->fields;
			$rsResult->MoveNext();
		}
		$_SESSION['govList'] = $govList;
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
		if (isset($_REQUEST['search_field'])) {
			// lets check if the search field has a value
			if (strlen($_REQUEST['search_field'])>0) {
				// lets assign the request value in a variable
				$search_field = $_REQUEST['search_field'];
				// create a custom criteria in an array
				$qry[] = "t.dec_code like '%$search_field%'";	
			}
		}
		$qry[]="s.emp_id='".$_GET['empinfo']."'";
		$qry[]="s.empdd_id != '0'";
		$qry[]="s.empdd_id = t.dec_id";
		// put all query array into one string criteria
		$criteria = " where ".implode(" and ",$qry);
		$arrSortBy = array(
		"empdd_id"=>"Deduction Type",
		"bldsched_period"=>"Pay Period",
		);

		if(isset($_GET['sortby'])){
			//$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		} else { $strOrderBy = 'order by empdd_id desc'; } 

		$viewLink = "";
		$editLink = "<img style=\"cursor:pointer;\" onclick=\"document.getElementById(\'govoption_',s.empdd_id,'\').selected=\'true\';gov_deduction()\" src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/edit.png\" title=\"Edit\" hspace=\"2px\" border=0 width=\"16\" height=\"16\">";
		$delLink = "<a href=\"setup.php?statpos=otrate&delete=',otr_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
		
		$sql = "select t.dec_code as code,
				CONCAT('$editLink') as viewdata,t.dec_name as name
				,IF(s.empdd_id = '5',CASE s.empdd_id = '5' 
				WHEN s.bldsched_period  = '1' THEN 'Tax'
				WHEN s.bldsched_period  = '2' THEN 'None - MWE'
				WHEN s.bldsched_period  = '3' THEN 'None - Others'
				WHEN s.bldsched_period  = '4' THEN 'by Percent(%)'
		        ELSE s.bldsched_period END,s.bldsched_period) as period		
				from period_benloanduc_sched s,deduction_type t
				$criteria 
				$strOrderBy";

		$arrFields = array(
		/*"viewdata"=>"Action",*/
		"code"=>"Deduction Type",
		"name"=>"Description",
		"period"=>"Pay Period",
		);

		$arrAttribs = array(
		"viewdata"=>"width='50' align='center'"
		);

		$tblDisplayList = new clsTableList($this->conn);
		$tblDisplayList->arrFields = $arrFields;
		$tblDisplayList->paginator->linkPage = "?$queryStr";
		$tblDisplayList->sqlAll = $sql;
		$tblDisplayList->sqlCount = $sqlcount;
		$tblDisplayList->trID = 'bldsched_id';
		$tblDisplayList->tblBlock->templateFile = "table_nosort.tpl.php";
		$tblDisplayList->tblBlock->assign("noSearchStart","<!--");
		$tblDisplayList->tblBlock->assign("noSearchEnd","-->");

		return $tblDisplayList->getTableList($arrAttribs);
	}
	
	function saveLoan($update=false){
		$selection = array();
		$sel = array('pp1','pp2','pp3','pp4','pp5');
		if($_POST['pp1']) $selection[0] = 1; else $selection[0] = 0;
		if($_POST['pp2']) $selection[1] = 1; else $selection[1] = 0;
		if($_POST['pp3']) $selection[2] = 1; else $selection[2] = 0;
		if($_POST['pp4']) $selection[3] = 1; else $selection[3] = 0;
		if($_POST['pp5']) $selection[4] = 1; else $selection[4] = 0;		
		
		if($update==false){
			//save loan info
			foreach ($this->vloan as $key => $value){
				if($key=='loan_startdate' || $key=='loan_enddate')
					$postData[] = $key."='".implode(' ',$_POST[$key])."'";
				else if($key=='emp_id')
					$postData[] = $key."='".$_SESSION['emplid']."'";
				else if($key=='loan_suspend')
					$postData[] =  $_POST[$key]?$key."='1'":$key."='0'";//if supend is check save 1 else 0
				else if($key=='loan_periodselection')
					$postData[] = "loan_periodselection='".implode(',',$selection)."'";
				else{
					if(is_float($_POST[$key])){ $pvalue = number_format($_POST[$key],2); }else{ $pvalue = $_POST[$key]; }
					$postData[] = $key."='".$pvalue."'";
				}
			}
			
			$postData[] = "loan_addwho='".AppUser::getData('user_name')."'";
			$fields = implode(",",$postData);
			
			$sql = "insert into loan_info set ".$fields;
			$res = "added.";
		}else if($update==true){ 
			$sqlWA = "Select loantype_id,psa_id,loan_numofmonths from loan_info where loan_id='".$_GET['loanedit']."'";
			$rsResult = $this->conn->Execute($sqlWA);
			if(!$rsResult->EOF){
				$rsResult->fields;
			}
			//update loan info
			foreach ($this->vloan as $key => $value){
				if($key=='loan_startdate' || $key=='loan_enddate')
					$postData[] = $key."='".implode(' ',$_POST[$key])."'";
				else if($key=='emp_id')
					$postData[] = $key."='".$_SESSION['emplid']."'";
				else if($key=='psa_id')
					$postData[] = $key."='".$rsResult->fields['psa_id']."'";
				else if($key=='loantype_id')
					$postData[] = $key."='".$rsResult->fields['loantype_id']."'";
				else if($key=='loan_numofmonths')
					$postData[] = $key."='".$rsResult->fields['loan_numofmonths']."'";
				else if($key=='loan_suspend')
					$postData[] =  $_POST[$key]?$key."='1'":$key."='0'";//if supend is check save 1 else 0
				else if($key=='loan_periodselection')
					$postData[] = "loan_periodselection='".implode(',',$selection)."'";
				else{
					if(is_float($_POST[$key])){ $pvalue = number_format($_POST[$key],2); }else{ $pvalue = $_POST[$key]; }
					$postData[] = $key."='".$pvalue."'";
				}
			}
			$postData[] = "loan_updatewho='".AppUser::getData('user_name')."'";
			$postData[] = "loan_updatewhen='".date('Y-m-d h:i:s')."'";
			$fields = implode(",",$postData);
			$sql = "update loan_info set $fields where loan_id='".$_GET['loanedit']."'";
			$res = "updated.";
		}
		$_SESSION['eMsg'] = $this->conn->Execute($sql)?"Successfully ".$res:mysql_error();
		
		if(!mysql_error()){
			if($update==true){
				$sqlDel = "delete from period_benloanduc_sched where loan_id != '0'";
				$this->conn->Execute($sqlDel);
				$bdlid = $_GET['loanedit'];
			}else{
				$bdlid = mysql_insert_id();
			}
			for($x=0; $x<5; $x++){	//Iterate save after delete Period Schedules
				if($_POST[$sel[$x]]){ 
					$this->savePS('loan',$bdlid,$x+1);
				}
			}
		}
	}
	
	
	function saveLeave($update=false){
		$selection = array();
		$sel = array('pp1','pp2','pp3','pp4','pp5');
		if($_POST['pp1']) $selection[0] = 1; else $selection[0] = 0;
		if($_POST['pp2']) $selection[1] = 1; else $selection[1] = 0;
		if($_POST['pp3']) $selection[2] = 1; else $selection[2] = 0;
		if($_POST['pp4']) $selection[3] = 1; else $selection[3] = 0;
		if($_POST['pp5']) $selection[4] = 1; else $selection[4] = 0;		
		
		if($update==false){ //save loan info
			foreach ($this->vloan as $key => $value) {
				if($key=='loan_startdate' || $key=='loan_enddate')
				$postData[] = $key."='".implode(' ',$_POST[$key])."'";
				else if($key=='emp_id')
				$postData[] = $key."='".$_SESSION['emplid']."'";
				else if($key=='loan_suspend')
				$postData[] =  $_POST[$key]?$key."='1'":$key."='0'";//if supend is check save 1 else 0
				else if($key=='loan_periodselection')
				$postData[] = "loan_periodselection='".implode(',',$selection)."'";
				else{
					if(is_float($_POST[$key])){ $pvalue = number_format($_POST[$key],2); }else{ $pvalue = $_POST[$key]; }
					$postData[] = $key."='".$pvalue."'";
				}
			}
			$postData[] = "loan_addwho='".AppUser::getData('user_name')."'";
			$fields = implode(",",$postData);
			$sql = "insert into loan_info set ".$fields;
			$res = "added.";
		}else if($update==true){//update loan info
			$suspend = $_POST['loan_suspend']?'loan_suspend'."='1'":'loan_suspend'."='0'";
			$sql = "update loan_info set ".$suspend.",loan_periodselection='".implode(',',$selection)."' where loan_id='".$_GET['loanedit']."'";
			$res = "updated.";
		}
		$_SESSION['eMsg'] = $this->conn->Execute($sql)?"Successfully ".$res:mysql_error();
		
		if(!mysql_error()){
			if($update==true){
				$sqlDel = "delete from period_benloanduc_sched where loan_id != '0'";
				$this->conn->Execute($sqlDel);
				$bdlid = $_GET['loanedit'];
			}else{
				$bdlid = mysql_insert_id();
			}
			for($x=0; $x<5; $x++){	//Iterate save after delete Period Schedules
				if($_POST[$sel[$x]]){ 
					$this->savePS('loan',$bdlid,$x+1);
				}
			}
		}
	}
	
	function benData(){
		IF($_GET['statpos']=='recur_setup'){
			$qry[] = "ben_id='".$_GET['benedit']."'";
			$qry[] = "emp_id='".$_GET['edit']."'";
		}ELSE{
			$qry[] = "ben_id='".$_GET['benedit']."'";
			$qry[] = "emp_id='".$_GET['empinfo']."'";
		}
		$criteria = " WHERE ".implode(" and ",$qry);
		$sql = "SELECT * FROM emp_benefits $criteria";
		$rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF){
			$benData[] = $rsResult->fields;
			$rsResult->MoveNext();
		}
		$benData[0]['ben_periodselection'] = explode(",",$benData[0]['ben_periodselection']);
		
		return $benData;
	}
	
	function otList(){
		$sql_ = "select * from ot_tbl";
		$rsResult_ = $this->conn->Execute($sql_);
		while(!$rsResult_->EOF){
			$ot[] = $rsResult_->fields;
			$rsResult_->MoveNext();
		}
		return $ot;
	}
	
	function getotTableList(){
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
		if (isset($_REQUEST['search_field'])) {
			// lets check if the search field has a value
			if (strlen($_REQUEST['search_field'])>0) {
				// lets assign the request value in a variable
				$search_field = $_REQUEST['search_field'];
				// create a custom criteria in an array
				$qry[] = "ud.te_name like '%$search_field%'";
			}
			// put all query array into one string criteria
			$criteria = " where ".implode(" or ",$qry);
		}
		$arrSortBy = array(
		"viewdata"=>"viewdata",
		"otr_name"=>"otr_name",
		"otr_desc"=>"otr_desc",
		"otr_type"=>"otr_type",
		"otr_factor"=>"otr_factor",
		);
		if(isset($_GET['sortby'])){
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}else{
			$strOrderBy = " order by rates.otr_id ASC";
		}
		$viewLink = "";
		$editLink = "<a href=\"setup.php?statpos=otrate&edit=',ottr.otr_id,'&otype=',otr_type,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/edit.png\" title=\"Edit\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
		$delLink = "<a href=\"setup.php?statpos=otrate&delete=',ottr.otr_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\"  border=0 width=\"16\" height=\"16\"></a>";
		$sql = "select rates.*, CONCAT('$editLink') as viewdata 
					from payroll_comp comp 
					inner join ot_tbl ottbl on (ottbl.ot_id=comp.ot_id)
					inner join ot_tr ottr on (ottbl.ot_id = ottr.ot_id)
					inner join ot_rates rates on (rates.otr_id = ottr.otr_id)
					where comp.emp_id='".$_GET['empinfo']."'
					$strOrderBy";
		$arrFields = array(
		"viewdata"=>"Action",
		"otr_name"=>"Name",
		"otr_desc"=>"Description",
		"otr_type"=>"Type",
		"otr_factor"=>"Factor Rate",
		);
		$arrAttribs = array(
		"viewdata"=>"width='50' align='center'"
		);
		$tblDisplayList = new clsTableList($this->conn);
		$tblDisplayList->arrFields = $arrFields;
		$tblDisplayList->paginator->linkPage = "?$queryStr";
		$tblDisplayList->sqlAll = $sql;
		$tblDisplayList->sqlCount = $sqlcount;
		$tblDisplayList->trID = 'otr_id';
		
		return $tblDisplayList->getTableList($arrAttribs);
	}
	
	
	function saveBen($update=false){
		$selection = array();
		$sel = array('ben1','ben2','ben3','ben4','ben5');
		$var = array('ben_amount','ben_startdate','ben_enddate','ben_isfixed','ben_suspend','psa_id','emp_id','ben_periodselection','ben_payperday');
		for($x=0; $x<5; $x++){
			if($_POST[$sel[$x]]) $selection[$x] = 1; else $selection[$x] = 0;
		}
		if($update==false){ //save loan info
			foreach ($var as $key){
				if($key=='ben_suspend')
					$postData[] =  $_POST[$key]?$key."='1'":$key."='0'";//if supend is check save 1 else 0
				else if($key=='ben_periodselection')
					$postData[] = "ben_periodselection='".implode(',',$selection)."'";
				else{
					if(is_float($_POST[$key])){
						$pvalue = number_format($_POST[$key],2);
					}else{
						$pvalue = $_POST[$key];
					}
					$postData[] = $key."='".$pvalue."'";
				}
			}
			$postData[] = "ben_addwho='".AppUser::getData('user_name')."'";
			$fields = implode(",",$postData);
			$sql = "insert into emp_benefits set ".$fields;
			$res = "added.";
		}else if($update==true){ //update benefit/deduction info
			$suspend = $_POST['ben_suspend']?'1':'0';
			$qry[] = "ben_suspend='".$suspend."'";
			$qry[] = "ben_updatewho='".AppUser::getData('user_name')."'";	
			$qry[] = "ben_updatewhen='".date('Y-m-d h:i:s')."'";
			$fields = implode(",",$qry);
			$sql = "update emp_benefits set ".$fields." where ben_id='".$_GET['benedit']."'";
			$res = "updated.";
		}
		
		$_SESSION['eMsg'] = $this->conn->Execute($sql)?"Successfully ".$res:mysql_error();
		if(!mysql_error()){
			if($update==true){
				$sqlDel = "delete from period_benloanduc_sched where ben_id !='0'";
				$this->conn->Execute($sqlDel);
				$bdlid = $_GET['benedit'];
			}else{
				$bdlid = mysql_insert_id();
			}
			for($x=0; $x<5; $x++){	//Iterate save after delete Period Schedules
				if($_POST[$sel[$x]]){ 
					$this->savePS('benefit',$bdlid,$x+1);
				}
			}
		}
	}
	
	function savePS($isBen,$bdlID,$period,$isTAX=0) { //Period Schedule
		if($isBen=='benefit'){
			$ben_id = $bdlID;
			$loan_id = 0;
			$empdd_id = 0;
		}else if($isBen=='loan'){
			$ben_id = 0;
			$loan_id = $bdlID;
			$empdd_id = 0;			
		}else if($isBen=='gov'){
			$ben_id = 0;
			$loan_id = 0;
			$empdd_id = $bdlID;	
			IF($isTAX=='1'){
				if($_POST['s_ltu']) $s_ltu = 1; else $s_ltu = 0;
				if($_POST['s_stat']) $s_stat = 1; else $s_stat = 0;
				$flds[]="percent_tax='".$_POST['percent_tax']."'";
				$flds[]="s_ltu='".$s_ltu."'";
				$flds[]="s_stat='".$s_stat."'";
			}
		}
		$flds[]="empdd_id='".$empdd_id."'";
		$flds[]="ben_id='".$ben_id."'";
		$flds[]="pps_id='".$_SESSION['pps_id']."'";
		$flds[]="emp_id='".$_GET['empinfo']."'";
		$flds[]="loan_id='".$loan_id."'";
		$flds[]="bldsched_period='".$period."'";
		$fields = implode(", ",$flds);
		$sqlInsert = "insert into period_benloanduc_sched set $fields";
		if(!$this->conn->Execute($sqlInsert)){
			print mysql_error();
			exit;
		}
	}
	
	function add_dependent() {
		foreach ($this->fieldMap_dependent as $key => $value) {
			if ($key=='emp_id') {
				$var[] = $key."='".$_GET['empinfo']."'";
			} else {
				$var[] = $key."='".$_POST[$value]."'";
			}
			$postData[] = $key."='".$pvalue."'";
		}
		$fields = implode(",",$var);
		$sqlInsert = "insert into dependent_info set $fields";
		if (!$this->conn->Execute($sqlInsert)) {
			print mysql_error();
			exit;
		}
	}
	
	function update_dependent() {
		foreach ($this->fieldMap_dependent as $key => $value) {
			if ($key=='emp_id') {
				$var[] = $key."='".$_GET['empinfo']."'";
			} else {
				$var[] = $key."='".$_POST[$value]."'";
			}
			$postData[] = $key."='".$pvalue."'";
		}
		$fields = implode(",",$var);
		$sql_update_dependent = "update dependent_info set $fields where depnd_id='".$_GET['depndedit']."'";
//		$sql_update_dependent?exit:"";
		$this->conn->Execute($sql_update_dependent);
	}
	
	function validate_dependent($pData_ = array()) {
		$isValid = true;
//		if (empty($pData_['depnd_fname'])) {
//			$isValid = false;
//			$_SESSION['eMsg'][] = "Please enter First Name of Dependent.";
//		}
//		
//		if (empty($pData_['depnd_mname'])) {
//			$isValid = false;
//			$_SESSION['eMsg'][] = "Please enter Middle Name of Dependent.";
//		}
//		
//		if (empty($pData_['depnd_lname'])) {
//			$isValid = false;
//			$_SESSION['eMsg'][] = "Please enter Last Name of Dependent.";
//		}
//		
//		if (empty($pData_['depnd_bdate'])) {
//			$isValid = false;
//			$_SESSION['eMsg'][] = "Please enter Birth Date of Dependent.";
//		}
//		
//		if (empty($pData_['depnd_relationship'])) {
//			$isValid = false;
//			$_SESSION['eMsg'][] = "Please enter Relation between the Dependent.";
//		}
//		$countThis[] = "select * from dependent_info where emp_id = '".$_GET['empinfo']."'";
//		$this->conn->Execute($countThis);
		$countThis = $this->conn->Execute("select count(emp_id) as counted
											from dependent_info where emp_id = '".$_GET['empinfo']."'");
		if (count($countThis) > 1) {
			$isValid = false;
		}
		return $isValid;
	}
	
	function count_dependent($id_ = ""){
		$sql = "select count(emp_id) as counted	from dependent_info where emp_id = '".$_GET['empinfo']."'";
		$rsResult = $this->conn->Execute($sql,array($id_));
		if(!$rsResult->EOF){
			return $rsResult->fields;
		}
	}
	
	/**
	 * Get Pay Group List
 	 * @return all Pay Group List array
	 */
	function getPGroup() {
		$objData = $this->conn->Execute("select * from payroll_pay_period_sched");
		$cResult = array();
		while ( !$objData->EOF ) {       	
			$cResult[] = $objData->fields;        	
        	$objData->MoveNext();
        }
        return $cResult;
	}
	
	/**
	 * Get Pay Group List
 	 * @return all Factor Rate List array
	 */
	function getFactorRate() {
		$objData = $this->conn->Execute("select * from factor_rate");
		$cResult = array();
		while ( !$objData->EOF ) {       	
			$cResult[] = $objData->fields;        	
        	$objData->MoveNext();
        }
        return $cResult;
	}
	
	/**
	 * Get First Employee in 201 Record
 	 * @return emp_id
	 */
	function getFirstEmp() {
		$rsResult = $this->conn->Execute("SELECT emp_id FROM emp_masterfile a JOIN emp_personal_info b on (a.pi_id=b.pi_id) WHERE a.emp_stat='7' ORDER BY b.pi_lname");
//		printa($rsResult);
		if(!$rsResult->EOF){
			return $rsResult->fields['emp_id'];
		}
	}
	
	/**
	 * Get First Employee in 201 Record
 	 * @return emp_id
	 */
	function getLastEmp() {
		$rsResult = $this->conn->Execute("SELECT emp_id FROM emp_masterfile a JOIN emp_personal_info b on (a.pi_id=b.pi_id) WHERE a.emp_stat='7' ORDER BY b.pi_lname DESC");
//		printa($rsResult);
		if(!$rsResult->EOF){
			return $rsResult->fields['emp_id'];
		}
	}
	
/**
	 * Get First Employee in 201 Record
 	 * @return emp_id
	 */
	function getNextEmp($emp_id_ = null) {
		$rsResult = $this->conn->Execute("SELECT emp_id FROM emp_masterfile a JOIN emp_personal_info b on (a.pi_id=b.pi_id) WHERE a.emp_stat='7' ORDER BY b.pi_lname");
//		printa($rsResult);
		if(!$rsResult->EOF){
			return $rsResult->fields['emp_id'];
		}
	}
	
	/**
	 * Get First Employee in 201 Record
 	 * @return emp_id
	 */
	function getPrevEmp($emp_id_ = null) {
		$rsResult = $this->conn->Execute("SELECT emp_id FROM emp_masterfile a JOIN emp_personal_info b on (a.pi_id=b.pi_id) WHERE a.emp_stat='7' ORDER BY b.pi_lname");
//		printa($rsResult);
		if(!$rsResult->EOF){
			return $rsResult->fields['emp_id'];
		}
	}
	
	/**
	 * Get search
 	 * @return all search
	 */
	function getSearch($search_= null) {
		$rsResult = $this->conn->Execute("select emp_id 
											from emp_masterfile a
											inner join emp_personal_info b on (a.pi_id=b.pi_id)
											where emp_idnum = '".$search_."' and a.emp_stat != '0'");
		if(!$rsResult->EOF){
			return $rsResult->fields;
		}
	}
	/**
	 * 
	 * validate previous employer
	 * @param array $pData
	 */
	function doValidatePrevEmp($pData = array(), $isMWE = array()){
		$isValid = true;
		$error = array();
		if($isMWE == 2){
			$arrValidation = array(
				"bir_alphalist_year" => "Covered Year"
				,"gross_compensation" => "Gross Compensation Income"
				,"basic_smw" => "Basic/SMW"
				,"holiday_pay" => "Holiday Pay"
				,"overtime_pay" => "Overtime Pay"
				,"night_differential" => "Night Shift Differential"
				,"hazard_pay" => "Hazard Pay"
				,"nt_other_ben" => "13th Month & Other Benefits(Non Taxable)"
				,"nt_deminimis" => "De Minimis Benefits"
				,"nt_statutories" => "SSS, PHIC, & HDMF Contributions and Union Dues"
				,"nt_compensation" => "Salaries & Other Forms of Compensation(Non Taxable)"
				,"taxable_other_ben" => "13th Month & Other Benefits(Taxable)"
				,"taxable_compensation" => "Salaries & Other Forms of Compensation(Taxable)"
			);
		} else {
			$arrValidation = array(
				"bir_alphalist_year" => "Covered Year"
				,"taxable_basic" => "Basic Salary(Taxable)"
				,"taxable_other_ben" => "13th Month & Other Benefits(Taxable)"
				,"taxable_compensation" => "Salaries & Other Forms of Compensation(Taxable)"
				,"nt_other_ben" => "13th Month & Other Benefits(Non Taxable)"
				,"nt_deminimis" => "De Minimis Benefits(Non Taxable)"
				,"nt_statutories" => "SSS, PHIC, & HDMF Contributions and Union Dues(Non Taxable)"
				,"nt_compensation" => "Salaries & Other Forms of Compensation(Non Taxable)");
		}
		$fieldMapPrevious = ($isMWE==2 ? $this->fieldMapPrevEmpMWE : $this->fieldMapPrevEmp);
		foreach($fieldMapPrevious as $key => $val){
			if(!is_numeric($pData[$val]) && !empty($pData[$val])){
				$error[]= $arrValidation[$key]." should be a valid number.";
			}
			if(empty($pData[$val]) && $pData[$val] == null){
				$error[]= $arrValidation[$key]." should not be blank. Please put 0 if field has no value.";
			}
		}
		if(count($error) > 0){
			$isValid = false;
			$_SESSION['eMsg'] = $error;
		}
		return $isValid;
	}
	/**
	 * 
	 * save previous employer
	 * @param array $pData
	 * @param int $emp_id_
	 */
	function doSavePrevEmp($pData = array(), $emp_id_ = null){
		$pData["emp_id"] = $emp_id_;
		$getRec = $this->conn->GetAll("SELECT * FROM bir_alphalist_prev_emp WHERE emp_id='$emp_id_'");
		if(empty($getRec)){
			$result = $this->conn->AutoExecute("bir_alphalist_prev_emp",$pData,'INSERT');
		} else {
			$result = $this->conn->AutoExecute("bir_alphalist_prev_emp",$pData,'UPDATE',"emp_id='$emp_id_'");
		}
		$_SESSION['eMsg'] = "Successfully Saved.";
	}
	/**
	 * 
	 * get previous employer record
	 * @param int $emp_id_
	 */
	function getPrevEmpRecord($emp_id_ = null){
		$sql = "SELECT * FROM bir_alphalist_prev_emp WHERE emp_id=?";
		$result = $this->conn->Execute($sql,array($emp_id_));
		return $result->fields;
	}
	
	function getTaxSettings($emp_id_ = null){
		$sql = "select bldsched_period from period_benloanduc_sched where emp_id='".$emp_id_."' AND empdd_id = '5'";
		$result = $this->conn->Execute($sql,array($emp_id_));
		return $result->fields;
	}
}
?>
