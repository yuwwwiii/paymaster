<?php
/**
 * Initial Declaration
 */


/**
 * Class Module
 *
 * @author  IR Salvador
 * 
 */
class clsSync{
	
	var $conn;
	var $fieldMap;
	var $Data;
	
	function clsSync($dbconn_ = null){
		$this->conn =& $dbconn_;
		$this->fieldMap = array(
		"mnu_name" => "mnu_name",
		"mnu_desc" => "mnu_desc",
		"mnu_parent" => "mnu_parent",
		"mnu_icon" => "mnu_icon",
		"mnu_ord" => "mnu_ord",
		"mnu_status" => "mnu_status",
		"mnu_link_info" => "mnu_link_info"
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
	 * @return bool
	 */
	function doPopulateData($pData_ = array()){
		if(count($pData_)>0){
			foreach ($this->fieldMap as $key => $value) {
				$this->Data[$key] = $pData_[$value];
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
		
//		$isValid = false;
		
		return $isValid;
	}
	
	/**
	 * Save New
	 *
	 */
	function doSaveAdd(){
		$flds = array();
		foreach ($this->Data as $keyData => $valData) {
			$flds[] = "$keyData='$valData'";
		}
		$fields = implode(", ",$flds);
		
		$sql = "insert into app_modules set $fields";
		$this->conn->Execute($sql);
		
		$_SESSION['eMsg']="Successfully Added.";
	}
	
	/**
	 * Save Update
	 *
	 */
	function doSaveEdit(){
		$id = $_GET['edit'];
		
		$flds = array();
		foreach ($this->Data as $keyData => $valData) {
			$flds[] = "$keyData='$valData'";
		}
		$fields = implode(", ",$flds);
		
		$sql = "update app_modules set $fields where mnu_id=$id";
		$this->conn->Execute($sql);
		$_SESSION['eMsg']="Successfully Updated.";
	}
	
	/**
	 * Delete Record
	 *
	 * @param string $id_
	 */
	function doDelete($id_ = ""){
		$sql = "delete from app_modules where mnu_id=?";
		$this->conn->Execute($sql,array($id_));
		$_SESSION['eMsg']="Successfully Deleted.";
	}
	
	/**
	 * Get all the Table Listings
	 *
	 * @return array
	 */
	
	/**
	 * irsalvador (2012.07.28)
	 * Orange Web Services
	 *
	 * @return $rsresult
	 */
	
	function callWebService($url = NULL, $paramsString=NULL, $paramSecure=FALSE, $paramDecode=FALSE, $paramDebug=FALSE){
		if(empty($url)){
			$url = "";
		}
		$methodParamHeaders = json_encode(array(
		    'page' => 2,
		    'limit' => 50
		));
		if(!empty($paramsString)){
		$paramsData = explode(',', $paramsString);
		$params = array();
			foreach ($paramsData as $datum) {
				list($key, $value) = explode('=', $datum);
					$params[$key] = $value;
				}
			$methodParamHeaders = json_encode($params);
		}
		$authHeaders = json_encode(array(
		    'app_id' => SYSCONFIG_APP_ID,
		    'app_token' => SYSCONFIG_APP_TOKEN,
		    'session_token' => '_absdjwsef43ismk43efdker'
		));
		
		//$secure = (in_array('--secure', $argv));
		//$decode = (!in_array('--no-decode', $argv));
		//$debug = (in_array('--debug', $argv));
		
		$secure = $paramSecure;
		$decode = $paramDecode;
		$debug = $paramDebug;
		
		$data = ""; 
		
		$curlResource = curl_init(); 
		curl_setopt($curlResource, CURLOPT_URL, $url); 
		curl_setopt($curlResource, CURLOPT_PORT , 80); 
		curl_setopt($curlResource, CURLOPT_VERBOSE, 0); 
		curl_setopt($curlResource, CURLOPT_HEADER, 0); 
		curl_setopt($curlResource, CURLOPT_SSLVERSION, 3); 
		
		if ($secure) {
		    curl_setopt($curlResource, CURLOPT_SSLCERT, getcwd() . "/client.pem"); 
		    curl_setopt($curlResource, CURLOPT_SSLKEY, getcwd() . "/keyout.pem"); 
		    curl_setopt($curlResource, CURLOPT_CAINFO, getcwd() . "/ca.pem"); 
		    curl_setopt($curlResource, CURLOPT_POST, 1); 
		}
		
		curl_setopt($curlResource, CURLOPT_SSL_VERIFYPEER, 1); 
		curl_setopt($curlResource, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($curlResource, CURLOPT_POSTFIELDS, $data); 
		curl_setopt($curlResource, CURLOPT_HTTPHEADER, array(
		    "Content-Type: text/xml",
		    "ohrm_ws_auth_parameters: {$authHeaders}",
		    "ohrm_ws_method_parameters: {$methodParamHeaders}",
		    "Content-length: ". strlen($data),
		)); 
		
		$resultData = curl_exec($curlResource); 
		
		if ($debug) {
		    if(!curl_errno($curlResource)) { 
		        $info = curl_getinfo($curlResource); 
		        print_r($info);
		    } else { 
		        echo 'Curl error: ' . curl_error($curlResource), "\n"; 
		        $info = curl_getinfo($curlResource); 
		        print_r($info);
		    } 
		}
		
		curl_close($curlResource); 
		
		//if ($decode) {
		$resultData = json_decode($resultData);
			//print_r($resultData);
		//} else {
			//echo $resultData; 
		//}
		return $resultData;
	}
	
	function validateHeadCompIfExists(){		
		$isValid = false;
		$sql = "select 1 from company_info where comp_id=1";
		$rsResult = $this->conn->Execute($sql);
		if(!$rsResult->EOF){
			if(count($rsResult->fields) > 0){
				$isValid = true;
			}
		}
		return $isValid;
		
	}
	
	function getCompanyTypeID($compType = NULL){
		$sql = "SELECT comptype_id FROM company_type WHERE comptype_desc='$compType'";
		$rsResult = $this->conn->Execute($sql);
		if(!$rsResult->EOF){
			return $rsResult->fields['comptype_id'];
		} else {
			return 0;
		}
	}
	
	function saveCompanyType($compType = NULL){
		if($this->getCompanyTypeID($compType) == 0 and !empty($compType)){
			$sql = "INSERT INTO company_type (comptype_desc) VALUES ('$compType')";
		} else {
			$sql = "UPDATE company_type SET comptype_desc='$compType' WHERE comptype_id='".$this->getCompanyTypeID($compType)."'";
		}
		$rsResult = $this->conn->Execute($sql);
	}
	
	function validateBankExists($bankId = NULL){
		$sql = "SELECT 1 FROM bank_info WHERE bank_id = '$bankId'";
		$rsResult = $this->conn->Execute($sql);
		if(!$rsResult->EOF){
			return true;
		} else {
			return false;
		}
	}
	
	function getBankListID($bankName = NULL){
		$sql = "SELECT banklist_id FROM bank_list WHERE banklist_name = '$bankName'";
		$rsResult = $this->conn->Execute($sql);
		if(!$rsResult->EOF){
			return $rsResult->fields['banklist_id'];
		} else {
			return 0;
		}
	}
	
	function saveBankList($bankName = NULL, $bankStatus = NULL){
		$bankStat = $this->setStatus($bankStatus);
		if($this->getBankListID($bankName) == 0  and !empty($bankName)){
			$sql = "INSERT INTO bank_list (banklist_name, banklist_isactive) VALUES (?,?)";
		} else {
			$sql = "UPDATE bank_list SET banklist_name='?', banklist_isactive=? WHERE banklist_id='".$this->getBankListID($bankName)."'";
		}
		$rsResult = $this->conn->Execute($sql,array($bankName,$bankStat));
	}
	
	function getBankAcctType($baccntypeName = NULL){
		$sql = "SELECT baccntype_id FROM bnkaccnt_type WHERE baccntype_name='$baccntypeName'";
		$rsResult = $this->conn->Execute($sql);
		if(!$rsResult->EOF){
			return $rsResult->fields['baccntype_id'];
		} else {
			return 0;
		}
	}
	
	function saveBankAcctType($baccntypeName = NULL){
		if($this->getBankAcctType($baccntypeName) == 0 and !empty($baccntypeName)){
			$sql = "INSERT INTO bnkaccnt_type (baccntype_name) VALUES ('$baccntypeName')";
		} else {
			$sql = "UPDATE bnkaccnt_type SET baccntype_name='$baccntypeName' WHERE baccntype_id='".$this->getBankAcctType($baccntypeName)."'";
		}
		$rsResult = $this->conn->Execute($sql);
	}
	
	function setStatus($status_ = NULL){
		if($status_ == "Active"){
			$bankStat = 1;
		} else {
			$bankStat = 0;
		}
		return $bankStat;
	}
	
	function validateIfEmpExist($empID = NULL){
		$sql = "SELECT emp_id FROM emp_masterfile WHERE emp_id='$empID'";
		$rsResult = $this->conn->Execute($sql);
		if(!$rsResult->EOF){
			return $rsResult->fields['emp_id'];
		} else {
			return null;
		}
	}
	
	function getEmpTypeID($empTypeName = NULL){
		$sql = "SELECT emptype_id FROM emp_type WHERE emptype_name='$empTypeName'";
		$rsResult = $this->conn->Execute($sql);
		if(!$rsResult->EOF){
			return $rsResult->fields['emptype_id'];
		} else {
			return 0;
		}
	}
	
	function saveEmpTypeID($empTypeName = NULL){
		if($this->getEmpTypeID($empTypeName) == 0 and !empty($empTypeName)){
			$sql = "INSERT INTO emp_type (emptype_name) VALUES ('$empTypeName')";
		} else {
			$sql = "UPDATE emp_type SET emptype_name='$empTypeName' WHERE emptype_id='".$this->getEmpTypeID($empTypeName)."'";
		}
		$rsResult = $this->conn->Execute($sql);
	}
	
	function getEmpCategID($empCategName = NULL){
		$sql = "SELECT empcateg_id FROM emp_category WHERE empcateg_name='$empCategName'";
		$rsResult = $this->conn->Execute($sql);
		if(!$rsResult->EOF){
			return $rsResult->fields['empcateg_id'];
		} else {
			return 0;
		}
	}
	
	function saveEmpCategID($empCategName = NULL){
		if($this->getEmpCategID($empCategName) == 0 and !empty($empCategName)){
			$sql = "INSERT INTO emp_category (empcateg_name) VALUES ('$empCategName')";
		} else {
			$sql = "UPDATE emp_category SET empcateg_name='$empCategName' WHERE empcateg_id='".$this->getEmpCategID($empCategName)."'";
		}
		$rsResult = $this->conn->Execute($sql);
	}
	
	function getDeptID($deptName = NULL){
		$sql = "SELECT ud_id FROM app_userdept WHERE ud_name='$deptName'";
		$rsResult = $this->conn->Execute($sql);
		if(!$rsResult->EOF){
			return $rsResult->fields['ud_id'];
		} else {
			return -1;
		}
	}
	
	function saveDept($udDesc = null, $udName = null, $udCode = null){
		if($this->getDeptID($udName) == -1 and !empty($udName)){
			$sql = "INSERT INTO app_userdept (ud_desc,ud_name,ud_code) VALUES ('$udDesc','$udName','$udCode')";
		} else {
			$sql = "UPDATE app_userdept SET ud_desc='$udDesc', ud_name='$udName', ud_code='$udCode' WHERE ud_id='".$this->getDeptID($udName)."'";
		}
		$rsResult = $this->conn->Execute($sql);
	}
	
	function getPostID($jobName = NULL){
		$sql = "SELECT post_id FROM emp_position WHERE post_name='$jobName'";
		$rsResult = $this->conn->Execute($sql);
		if(!$rsResult->EOF){
			return $rsResult->fields['post_id'];
		} else {
			return 0;
		}
	}
	
	function savePost($jobDescription = null, $jobName = null, $jobCode = null){
		if($this->getPostID($jobName) == 0 and !empty($jobName)){
			$sql = "INSERT INTO emp_position (post_desc,post_name,post_code) VALUES ('$jobDescription','$jobName','$jobCode')";
		} else {
			$sql = "UPDATE emp_position SET post_desc='$jobDescription', post_name='$jobName', post_code='$jobCode' WHERE post_id='".$this->getPostID($jobName)."'";
		}
		$rsResult = $this->conn->Execute($sql);
	}
	
	function getTaxExcepID($taxExceptionCode = null){
		$sql = "SELECT taxep_id FROM tax_excep WHERE taxep_code='$taxExceptionCode'";
		$rsResult = $this->conn->Execute($sql);
		if(!$rsResult->EOF){
			return $rsResult->fields['taxep_id'];
		} else {
			return 0;
		}
	}
	
	function saveTaxExcep($taxExceptionCode = null, $taxExceptionName = null){
		if($this->getTaxExcepID($taxExceptionCode) == 0 and !empty($taxExceptionCode)){
			$sql = "INSERT INTO tax_excep (taxep_name,taxep_code) VALUES ('$taxExceptionName','$taxExceptionCode')";
		} else {
			$sql = "UPDATE tax_excep SET taxep_name='$taxExceptionName', taxep_code='$taxExceptionCode' WHERE taxep_id='".$this->getTaxExcepID($taxExceptionCode)."'";
		}
		$rsResult = $this->conn->Execute($sql);
	}
	
	function getSalaryTypeID($salaryType = NULL){
		$sql = "SELECT salarytype_id FROM salary_type WHERE salarytype_name='$salaryType'";
		$rsResult = $this->conn->Execute($sql);
		if(!$rsResult->EOF){
			return $rsResult->fields['salarytype_id'];
		} else {
			return 0;
		}
	}
	
	function saveSalaryType($salaryType = null){
		if($this->getSalaryTypeID($salaryType) == 0 and !empty($salaryType)){
			$sql = "INSERT INTO salary_type (salarytype_name) VALUES ('$salaryType')";
		} else {
			$sql = "UPDATE salary_type SET salarytype_name='$salaryType' WHERE salarytype_id='".$this->getSalaryTypeID($salaryType)."'";
		}
		$rsResult = $this->conn->Execute($sql);
	}
	
	function synchronize(){
		$idArr = array();
		// clear salary info to prevent double entry
		$sqlClean = "TRUNCATE table salary_info";
		$rsResult = $this->conn->Execute($sqlClean);
		
		// clear bank info for employees to prevent double entry
		$sqlClean = "TRUNCATE table bank_infoemp";
		$rsResult = $this->conn->Execute($sqlClean);
		
		// clear dependents to prevent double entry
		$sqlClean = "TRUNCATE table dependent_info";
		$rsResult = $this->conn->Execute($sqlClean);
		
		// clear leave types to prevent double entry
		$sqlClean = "TRUNCATE table leave_type";
		$rsResult = $this->conn->Execute($sqlClean);
		
		// clear employee leaves to prevent double entry
		$sqlClean = "TRUNCATE table emp_leave";
		$rsResult = $this->conn->Execute($sqlClean);
		
		$company = $this->callWebService(SYSCONFIG_ORANGE_URL."symfony/web/index.php/api/wsCall/getOrganizationGeneralInformation",NULL,0,0,0);
		
		// Populate company type 
		$this->saveCompanyType($company->companyType);		
		if($this->validateHeadCompIfExists()){
			$sql = "UPDATE company_info SET 
						comp_code=?, 
						comp_name=?, 
						comp_add=?, 
						comp_tin=?, 
						comp_sss=?, 
						comp_phic=?, 
						comp_hdmf=?, 
						comptype_id=?, 
						comp_industry=?, 
						comp_tel=?, 
						comp_email=? 
						WHERE comp_id=1";
		} else {
			$sql = "INSERT INTO company_info (
						comp_code, 
						comp_name, 
						comp_add, 
						comp_tin, 
						comp_sss, 
						comp_phic, 
						comp_hdmf, 
						comptype_id, 
						comp_industry, 
						comp_tel, 
						comp_email) 
						VALUES (?,?,?,?,?,?,?,?,?,?,?)";
		}
		$rsResult = $this->conn->Execute($sql,
						array(
							$company->companyCode, 
							$company->companyName,
							$company->companyAddress,
							$company->TIN,
							$company->SSS,
							$company->PHIC,
							$company->HDMF,
							$this->getCompanyTypeID($company->companyType),
							$company->industry,
							$company->telephoneNumber,
							$company->email
						)
					);
		for($c=0;$c<count($company->bankAccounts);$c++){
			// Populate bank list
			$this->saveBankList($company->bankAccounts[$c]->bankName, $company->bankAccounts[$c]->status);
			
			// Populate bank account type
			if(!$this->getBankAcctType($company->bankAccounts[$c]->accountType)){
				$this->saveBankAcctType($company->bankAccounts[$c]->accountType);
			}
			$bankInfoID[] = $company->bankAccounts[$c]->id;
			if($this->validateBankExists($company->bankAccounts[$c]->id)){
				$sql = "UPDATE bank_info SET
							bank_id=?,
							bank_isactive=?, 
							banklist_id=?, 
							bank_acct_name=?, 
							bank_acct_no=?, 
							baccntype_id=?, 
							bank_branch=?, 
							bank_swift_code=?, 
							comp_id=? 
							WHERE bank_id='".$company->bankAccounts[$c]->id."'";
			} else {
				$sql = "INSERT INTO bank_info (
							bank_id,
							bank_isactive, 
							banklist_id, 
							bank_acct_name, 
							bank_acct_no, 
							baccntype_id, 
							bank_branch, 
							bank_swift_code, 
							comp_id)
							VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
			}
			$rsResult = $this->conn->Execute($sql,
						array(
							$company->bankAccounts[$c]->id,
							$this->setStatus($company->bankAccounts[$c]->status), 
							$this->getBankListID($company->bankAccounts[$c]->bankName),
							$company->bankAccounts[$c]->accountName,
							$company->bankAccounts[$c]->accountNumber,
							$this->getBankAcctType($company->bankAccounts[$c]->accountType),
							$company->bankAccounts[$c]->branchRegister,
							$company->bankAccounts[$c]->swiftCode,
							1
						)
					);
		}
		if(is_array($bankInfoID) and count($bankInfoID) > 0){
			$diffBank = array_diff($this->getBankInfoList(), $bankInfoID);
			foreach($diffBank as $key => $val){
				$sql = "DELETE FROM bank_info WHERE bank_id = '$val'";
				$rsResult = $this->conn->Execute($sql);
			}
		}
		$empList = $this->callWebService(SYSCONFIG_ORANGE_URL."symfony/web/index.php/api/wsCall/getEmployeeList",NULL,0,0,0);
		if(count($empList) > 0){
			foreach($empList as $key => $val){
				$empMoreInfo = $this->callWebService(SYSCONFIG_ORANGE_URL."symfony/web/index.php/api/wsCall/getEmployeeData", "employeeNumber=$val->identifier",0,0,0);
//				printa($empMoreInfo); exit;
				$this->saveEmpTypeID($val->employeeStatus);
				$this->saveEmpCategID($val->employeeCategory);
				$this->saveDept($empMoreInfo->departmentDescription, $empMoreInfo->departmentName, $empMoreInfo->departmentCode);
				$this->savePost($empMoreInfo->jobDescription, $empMoreInfo->jobName, $empMoreInfo->jobCode);
				$this->saveTaxExcep($empMoreInfo->taxExceptionCode, $empMoreInfo->taxExceptionName);
				
				// location or branch information
				$empLocation = $this->callWebService(SYSCONFIG_ORANGE_URL."symfony/web/index.php/api/wsCall/getEmployeeLocationInformation", "employeeNumber=$val->identifier",0,0,0);
				if(isset($empLocation)){
					//printa($empLocation); exit;
					$locationID = $this->getLocation($empLocation->name);
					if($locationID == 0){
						$sql = "INSERT INTO branch_info (
								comp_id,
								branchinfo_code,
								comptype_id,
								branch_industry,
								branchinfo_name,
								branchinfo_add,
								branchinfo_tel1,
								branchinfo_email,
								branchinfo_tin,
								branchinfo_sss,
								branchinfo_phic,
								branchinfo_hdmf)
								VALUES(?,?,?,?,?,?,?,?,?,?,?,?)";
					} else {
						$sql = "UPDATE branch_info SET
								comp_id=?,
								branchinfo_code=?,
								comptype_id=?,
								branch_industry=?,
								branchinfo_name=?,
								branchinfo_add=?,
								branchinfo_tel1=?,
								branchinfo_email=?,
								branchinfo_tin=?,
								branchinfo_sss=?,
								branchinfo_phic=?,
								branchinfo_hdmf=? WHERE branchinfo_id='$locationID'";
					}
					$rsResult = $this->conn->Execute($sql,
							array(
								1,
								$empLocation->locationCode,
								$this->getCompanyTypeID($empLocation->locationType),
								$empLocation->industry,
								$empLocation->name,
								$empLocation->address,
								$empLocation->telephoneNumber,
								$empLocation->email,
								$empLocation->TIN,
								$empLocation->SSSNo,
								$empLocation->PHICNo,
								$empLocation->HDMFNo
								)
							);
				for($branchBankCount=0;$branchBankCount<count($empLocation->bankAccounts);$branchBankCount++){
					$branchBank = $this->validateBankExistsByBankNameAndLocID($empLocation->bankAccounts[$branchBankCount]->accountNumber,$locationID);
					if(!$this->getBankAcctType($empLocation->bankAccounts[$branchBankCount]->accountType)){
						$this->saveBankAcctType($empLocation->bankAccounts[$branchBankCount]->accountType);
					}
					if($branchBank){
						$sql = "UPDATE bank_info SET
									branchinfo_id=?,
									bank_isactive=?, 
									banklist_id=?, 
									bank_acct_name=?, 
									bank_acct_no=?, 
									baccntype_id=?, 
									bank_branch=?, 
									bank_swift_code=?, 
									comp_id=? 
									WHERE bank_id='$branchBank'";
					} else {
						$sql = "INSERT INTO bank_info (
									branchinfo_id,
									bank_isactive, 
									banklist_id, 
									bank_acct_name, 
									bank_acct_no, 
									baccntype_id, 
									bank_branch, 
									bank_swift_code, 
									comp_id)
									VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
					}
					$rsResult = $this->conn->Execute($sql,
								array(
									$this->getLocation($empLocation->name),
									$this->setStatus($empLocation->bankAccounts[$branchBankCount]->status), 
									$this->getBankListID($empLocation->bankAccounts[$branchBankCount]->bankName),
									$empLocation->bankAccounts[$branchBankCount]->accountName,
									$empLocation->bankAccounts[$branchBankCount]->accountNumber,
									$this->getBankAcctType($empLocation->bankAccounts[$branchBankCount]->accountType),
									$empLocation->bankAccounts[$branchBankCount]->branchRegister,
									$empLocation->bankAccounts[$branchBankCount]->swiftCode,
									1
								)
							);
					}
				}
					
				if($this->validateIfEmpExist($val->identifier)){
					$sql = "UPDATE emp_masterfile SET
								emp_id=?,
								emp_idnum=?,
								comp_id=?,
								branchinfo_id=?,
								emp_resonresign=?,
								emptype_id=?,
								empcateg_id=?,
								emp_hiredate=?,
								emp_resigndate=?,
								pi_id=?,
								ud_id=?,
								post_id=?,
								taxep_id=?,
								emp_stat=? WHERE emp_id='$val->identifier'";
					
					$sql2 = "UPDATE emp_personal_info SET 
								pi_id=?,
								pi_fname=?,
								pi_mname=?,
								pi_lname=?,
								pi_nickname=?,
								pi_gender=?,
								pi_bdate=?,
								pi_civil=?,
								pi_add=?,
								pi_telone=?,
								pi_mobileone=?,
								pi_emailone=?,
								pi_tin=?,
								pi_sss=?,
								pi_phic=?,
								pi_hdmf=? WHERE pi_id='$val->identifier'";
					
					if($this->checkIfEmpHasAccess($val->identifier)){
						$sql3 = "UPDATE app_users SET
								ud_id=?,
								user_name=?,
								user_fullname=?,
								user_password=?,
								user_type=?,
								user_status=?,
								emp_id=? WHERE emp_id='$val->identifier'";
					} else {
						$sql3 = "INSERT INTO app_users (
								ud_id,
								user_name,
								user_fullname,
								user_password,
								user_type,
								user_status,
								emp_id) VALUES (?,?,?,?,?,?,?)";
					}
				} else {
					// Insert into masterfile
					$sql = "INSERT INTO emp_masterfile (
							emp_id,
							emp_idnum,
							comp_id,
							branchinfo_id,
							emp_resonresign,
							emptype_id,
							empcateg_id,
							emp_hiredate,
							emp_resigndate,
							pi_id,
							ud_id,
							post_id,
							taxep_id,
							emp_stat
							)
							VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
					
					$sql2 = "INSERT INTO emp_personal_info (
							pi_id,
							pi_fname,
							pi_mname,
							pi_lname,
							pi_nickname,
							pi_gender,
							pi_bdate,
							pi_civil,
							pi_add,
							pi_telone,
							pi_mobileone,
							pi_emailone,
							pi_tin,
							pi_sss,
							pi_phic,
							pi_hdmf)
							VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
					
					$sql3 = "INSERT INTO app_users (
								ud_id,
								user_name,
								user_fullname,
								user_password,
								user_type,
								user_status,
								emp_id) VALUES (?,?,?,?,?,?,?)";
				}
				$rsResult = $this->conn->Execute($sql,
							array(
								$val->identifier,
								$val->employeeId,
								1,
								$this->getLocation($empLocation->name),
								$val->employeeClassification,
								$this->getEmpTypeID($val->employeeStatus),
								$this->getEmpCategID($val->employeeCategory),
								$val->joinedDate,
								$val->terminatedDate,
								$val->identifier,
								$this->getDeptID($empMoreInfo->departmentName),
								$this->getPostID($empMoreInfo->jobName),
								$this->getTaxExcepID($empMoreInfo->taxExceptionCode),
								$this->replaceEmpStat($val->employeeClassification)
								)
							);
					$rsResult2 = $this->conn->Execute($sql2,
							array(
								$val->identifier,
								$val->firstName,
								$val->middleName,
								$val->lastName,
								$val->nickName,
								$val->gender,
								$val->birthDate,
								$val->maritalStatus,
								$val->address,
								$val->telephoneNumber,
								$val->mobileNumber,
								$val->emailAddress,
								$val->TIN,
								$val->SSS,
								$val->PHIC,
								$val->HDMF
								)
							);
							
					if(!empty($val->authData->username)){
						$fullname = $val->firstName." ".$val->lastName;
						$password = md5("sigma13h");
						
						if($val->authData->userType == "Admin"){
							$usertype = "Administrator";
						} else {
							$usertype = $val->authData->userType;;
						}
						
						$deptID = $this->getDeptID($empMoreInfo->departmentName) > 0 ? $this->getDeptID($empMoreInfo->departmentName) : 1 ;
					$rsResult3 = $this->conn->Execute($sql3,
							array(
								$deptID,
								$val->authData->username,
								$fullname,
								$password,
								$usertype,
								1,
								$val->identifier
							)
						);
					}
					// data for salary details
					for($salaryCount=0;$salaryCount<count($empMoreInfo->salaryDetails);$salaryCount++){
						// save salary type if not exists
						$this->saveSalaryType($empMoreInfo->salaryDetails[$salaryCount]->salaryType);
							$sql = "INSERT INTO salary_info (
										salaryinfo_isactive,
										salarytype_id,
										salaryinfo_effectdate,
										salaryinfo_basicrate,
										salaryinfo_ecola,
										salaryinfo_ceilingpay,
										emp_id,
										fr_id,
										salaryinfo_id)
										VALUES (?,?,?,?,?,?,?,?,?)";
						$rsResult = $this->conn->Execute($sql,
							array(
								$this->setStatus($empMoreInfo->salaryDetails[$salaryCount]->status),
								$this->getSalaryTypeID($empMoreInfo->salaryDetails[$salaryCount]->salaryType),
								$empMoreInfo->salaryDetails[$salaryCount]->salaryEffectiveDate,
								$empMoreInfo->salaryDetails[$salaryCount]->basicPayRate,
								$empMoreInfo->salaryDetails[$salaryCount]->COLA,
								$empMoreInfo->salaryDetails[$salaryCount]->minimumPay,
								$val->identifier,
								1,
								$empMoreInfo->salaryDetails[$salaryCount]->identifier
								)
							);
					}
					for($bankEmpCount=0;$bankEmpCount<count($empMoreInfo->directDepositDetails);$bankEmpCount++){
						if(!$this->getBankAcctType($empMoreInfo->directDepositDetails[$bankEmpCount]->accountType)){
							$this->saveBankAcctType($empMoreInfo->directDepositDetails[$bankEmpCount]->accountType);
						}
						$sql = "INSERT INTO bank_infoemp (
									bankiemp_acct_name,
									bankiemp_acct_no,
									baccntype_id,
									bankiemp_perc,
									emp_id,
									banklist_id,
									bankiemp_id)
									VALUES (?,?,?,?,?,?,?)";
						$rsResult2 = $this->conn->Execute($sql,
							array(
								$empMoreInfo->directDepositDetails[$bankEmpCount]->accountName,
								$empMoreInfo->directDepositDetails[$bankEmpCount]->accountNumber,
								$this->getBankAcctType($empMoreInfo->directDepositDetails[$bankEmpCount]->accountType),
								$empMoreInfo->directDepositDetails[$bankEmpCount]->percentage,
								$val->identifier,
								$this->getBankListID($empMoreInfo->directDepositDetails[$bankEmpCount]->bankName),
								$empMoreInfo->directDepositDetails[$bankEmpCount]->identifier
								)
							);
					}
					
					for($dependentCount=0;$dependentCount<count($empMoreInfo->dependents);$dependentCount++){
						
						$sql = "INSERT INTO dependent_info (
									emp_id,
									depnd_fname,
									depnd_bdate,
									depnd_relationship,
									depnd_id
									)
									VALUES (?,?,?,?,?)";
						$rsResult2 = $this->conn->Execute($sql,
							array(
								$val->identifier,
								$empMoreInfo->dependents[$dependentCount]->name,
								$empMoreInfo->dependents[$dependentCount]->birthday,
								$empMoreInfo->dependents[$dependentCount]->relationship,
								$empMoreInfo->dependents[$dependentCount]->identifier
								)
							);
					}
					$leave = $this->callWebService(SYSCONFIG_ORANGE_URL."symfony/web/index.php/api/wsCall/getEmployeeLeaveData", "employeeNumber=$val->identifier",0,0,0);
	//	
					for($leaveCount=0;$leaveCount<count($leave);$leaveCount++){
						$leaveCode = explode("-",$leave[$leaveCount]->identifier);
						$this->saveLeaveType($leaveCode[2], $leave[$leaveCount]->leaveType);
						$validate = $this->validateIfEmpLeaveExists($val->identifier, $this->getLeaveTypeID($leaveCode[2]));
						if($validate == null){
							$sql = "INSERT INTO emp_leave (
										emp_id,
										leave_id,
										empleave_credit,
										empleave_used_day,
										empleave_available_day	
										)
										VALUES (?,?,?,?,?)";
						} else {
							$sql = "UPDATE emp_leave SET 
										emp_id=?,
										leave_id=?,
										empleave_credit=?,
										empleave_used_day=?,
										empleave_available_day=?
										WHERE empleave_id='$validate'";
						}
						$leaveTaken = (empty($leave[$leaveCount]->leaveTaken) ? 0 : $leave[$leaveCount]->leaveTaken);
						$leaveBalance = (empty($leave[$leaveCount]->leaveBalance) ? $leave[$leaveCount]->leaveEntitlement : $leave[$leaveCount]->leaveBalance);
						$rsResult = $this->conn->Execute($sql,
							array(
								$val->identifier,
								$this->getLeaveTypeID($leaveCode[2]),
								$leave[$leaveCount]->leaveEntitlement,
								$leaveTaken,
								$leaveBalance
								)
							);
					}
					
					$idArr[] = $val->identifier;
			}
		}
		if(is_array($idArr) and count($idArr) > 0){
			$ipayList = $this->getIpayEmpList();
			$diff = array_diff($ipayList, $idArr);
			foreach($diff as $key => $val){
				$sql = "DELETE FROM emp_masterfile WHERE emp_id = '$val'";
				$rsResult = $this->conn->Execute($sql);
				$sql = "DELETE FROM emp_personal_info WHERE pi_id = '$val'";
				$rsResult = $this->conn->Execute($sql);
			}
		}
		//header("Location: index.php");
		echo '<div id="reload" style="cursor: pointer;">Data Synchronization Complete!</div>';
		echo "<script type=\"text/javascript\">
			        $(document).ready(function() {
			            $('#reload').click(function() {
			                location.reload(true);
			            });
			        });
			  </script>";
	}
	
	function validateBankExistsByBankNameAndLocID($accountNumber = null, $locationID = null){
		$sql = "SELECT bank_id FROM bank_info WHERE bank_acct_no='$accountNumber' AND branchinfo_id='$locationID'";
		$rsResult = $this->conn->Execute($sql);
		if(!$rsResult->EOF){
			return $rsResult->fields['bank_id'];
		} else {
			return 0;
		}
	}
	
	function getLocation($locationName = null){
		$sql = "SELECT branchinfo_id FROM branch_info WHERE branchinfo_name='$locationName'";
		$rsResult = $this->conn->Execute($sql);
		if(!$rsResult->EOF){
			return $rsResult->fields['branchinfo_id'];
		} else {
			return 0;
		}
	}
	
	function getIpayEmpList(){
		$arr = array();
		$sql = "SELECT emp_id FROM emp_masterfile";
		$rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF){
			$arr[] = $rsResult->fields['emp_id'];
			$rsResult->MoveNext();
		}
		return $arr;
	}
	
	function getLeaveTypeID($leaveCode = null){
		$sql = "SELECT leave_id FROM leave_type WHERE leave_code='$leaveCode'";
		$rsResult = $this->conn->Execute($sql);
		if(!$rsResult->EOF){
			return $rsResult->fields['leave_id'];
		} else {
			return 0;
		}
	}
	
	function saveLeaveType($leaveID = null, $leaveName = null){
		$leaveName = str_replace(array("(",")","-","/")," ",$leaveName);
		if($this->getLeaveTypeID($leaveID) == 0 and !empty($leaveID)){
			$sql = "INSERT INTO leave_type (leave_name,leave_code) VALUES ('".$leaveName."','".$leaveID."')";
		} else {
			$sql = "UPDATE leave_type SET leave_name='".$leaveName."' WHERE leave_code='".$leaveID."'";
		}
		$rsResult = $this->conn->Execute($sql);
	}
	
	function validateIfEmpLeaveExists($empID = null, $leaveID = null){
		$sql = "SELECT empleave_id FROM emp_leave WHERE emp_id='$empID' AND leave_id='$leaveID'";
		$rsResult = $this->conn->Execute($sql);
		if(!$rsResult->EOF){
			return $rsResult->fields['empleave_id'];
		} else {
			return null;
		}
	}
	
	function replaceEmpStat($empStat = null){
		if(empty($empStat)){
			return 7;
		} else {
			return 8;
		}
	}
	
	function getBankInfoList(){
		$sql = "SELECT bank_id FROM bank_info";
		$rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF){
			$arr[] = $rsResult->fields['bank_id'];
			$rsResult->MoveNext();
		}
		return $arr;
	}
	
	function checkIfEmpHasAccess($emp_id = null){
		$sql = "SELECT user_id from app_users WHERE emp_id='".$emp_id."'";
		$rsResult = $this->conn->Execute($sql);
		if(!$rsResult->EOF){
			return true;
		} else {
			return false;
		}
	}
}

?>