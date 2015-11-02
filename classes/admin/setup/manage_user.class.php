<?php
/**
 * Initial Declaration
 */
require_once SYSCONFIG_CLASS_PATH.'util/swiftmailer/lib/swift_required.php';

$arrStatus = array(
1 => "Active",
2 => "Inactive"
);


/**
 * Class Module
 *
 * @author  JIM
 *
 */
class clsManageUser{

	var $conn;
	var $fieldMap;
	var $Data;

	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsManageUser object
	 */
	function clsManageUser($dbconn_ = null){
		$this->conn =& $dbconn_;
		$this->fieldMap = array(
		 /*"user_name" => "user_name"
		,"user_fullname" => "user_fullname"
		,"user_password" => "user_password"
		,"user_type" => "user_type"
		,"ud_id" => "ud_id"
		,"user_status" => "user_status"*/
		"user_email" => "user_email"
		,"user_emailpass" => "user_emailpass"
		,"user_smtp" => "user_smtp"
		,"user_port" => "user_port"
		,"user_paygroup_list" => "user_paygroup_list"
		,"user_comp_list" => "user_comp_list"
		,"user_branch_list" => "user_branch_list"
		/*,"user_201stat_list" => "user_201stat_list"*/
		,"user_picture" => "user_picture"
		);
	}

	/**
	 * Get the records from the database
	 *
	 * @param string $id_
	 * @return array
	 */
	function dbFetch($id_ = ""){
		$sql = "select * from app_users where user_id=?";
		$rsResult = $this->conn->Execute($sql,array($id_));
		if(!$rsResult->EOF){
			/*if (empty($rsResult->fields['user_comp_list'])) {
				$rsResult->fields['user_comp_list']=array();
			}else{
				$rsResult->fields['user_comp_list'] = unserialize($rsResult->fields['user_comp_list']);
			}
			if (empty($rsResult->fields['user_branch_list'])) {
				$rsResult->fields['user_branch_list']=array();
			}else{
				$rsResult->fields['user_branch_list'] = unserialize($rsResult->fields['user_branch_list']);
			}
			if (empty($rsResult->fields['user_201stat_list'])) {
				$rsResult->fields['user_201stat_list']=array();
			}else{
				$rsResult->fields['user_201stat_list'] = unserialize($rsResult->fields['user_201stat_list']);
			}*/
			if (empty($rsResult->fields['user_emailpass'])) {
				$rsResult->fields['user_emailpass'] = "";
			} else {
				$rsResult->fields['user_emailpass'] = clsEncryptHelper::decrypt($rsResult->fields['user_emailpass'], BASE_URL);
			}
			return $rsResult->fields;
		}
	}
	/**
	 * Populate array parameters to Data Variable
	 *
	 * @param array $pData_
	 * @return bool
	 */
	function doPopulateData($pData_ = array(),$isForm_ = false){
		if(count($pData_)>0){
			foreach ($this->fieldMap as $key => $value) {
				if ($key=='user_picture') {
					$this->Data[$key] = ($_FILES[$key]);
				} else {
					$this->Data[$key] = $pData_[$value];
				}
				if($key=='user_paygroup_list' || $key=='user_comp_list' || $key=='user_branch_list'){
					$this->Data[$key] = serialize($pData_[$value]);
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
		
		/*if (empty($pData_['user_name'])) {
			$isValid = false;
			$_SESSION['eMsg'][] = "Please enter a User ID.";
		}
		
		if (empty($pData_['user_password'])) {
			$isValid = false;
			$_SESSION['eMsg'][] = "Please enter a Password.";
		}
		
		if (empty($pData_['user_fullname'])) {
			$isValid = false;
			$_SESSION['eMsg'][] = "Please enter a Name.";
		}
		
		if (!filter_var($pData_['user_email'], FILTER_VALIDATE_EMAIL) && !empty($pData_['user_email'])) {
			$isValid = false;
			$_SESSION['eMsg'][] = "Please enter a valid Email Address.";
		}
		
		if(empty($pData_['user_comp_list'])){
			$isValid = false;
			$_SESSION['eMsg'][] = "Please choose at least one company.";
		}*/
		if (($_FILES['user_picture']['type'] !== 'image/gif'
			&& $_FILES['user_picture']['type'] !== 'image/png'
			&& $_FILES['user_picture']['type'] !== 'image/jpg'
			&& $_FILES['user_picture']['type'] !== 'image/jpeg') && $_FILES['user_picture']['error'] !== 4) {
			$isValid = false;
			$_SESSION['eMsg'][] = "gif, png, jpg/jpeg only.";
		} else {
			if ($_FILES['user_picture']['size'] > 15728640) {
				$isValid = false;
				$_SESSION['eMsg'][] = "The image size exceed to 200KB.";
			}
		}
		
		if (!is_numeric($pData_['user_port']) && !empty($pData_['user_port'])) {
			$isValid = false;
			$_SESSION['eMsg'][] = "Please enter a valid Port number.";
		}

		return $isValid;
	}

	/**
	 * Save New
	 *
	 */
	function doSaveAdd(){
		$flds = array();
		foreach ($this->Data as $keyData => $valData) {
			if ($keyData=="user_picture") {
//				if (!empty($valData)) {	
					$valData = file_get_contents($this->Data['user_picture']['tmp_name']);
//				}
			} elseif ($keyData == "user_password") {
					$valData = trim(md5($valData));
			} /*elseif ($keyData == 'user_comp_list'){
				$valData = serialize($valData);
			} elseif($keyData == 'user_branch_list'){
				$valData = serialize($valData);
			} elseif($keyData == 'user_201stat_list'){
				$valData = serialize($valData);
			}*/
			$valData = trim(addslashes($valData));
			$flds[] = "$keyData='$valData'";
		}
		$fields = implode(", ",$flds);

		$sql = "insert into app_users set $fields";
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
		//printa($this->Data);
		foreach ($this->Data as $keyData => $valData) {
			/*if ($keyData == "user_password") {
				$valData = trim(md5($valData));
			}
			if($keyData=="user_comp_list"){
				$valData = serialize($valData);
			}
			if($keyData=="user_branch_list"){
				$valData = serialize($valData);
			}
			if($keyData=="user_201stat_list"){
				$valData = serialize($valData);
			}*/
			if ($keyData=="user_picture") {
				if ($valData['error'] == 4) {
					break;
				} else {
					$valData = file_get_contents($this->Data['user_picture']['tmp_name']);
				}
			}
			if ($keyData=="user_emailpass") {
				$valData = clsEncryptHelper::encrypt($valData, BASE_URL);
			}
			
			$valData = trim(addslashes($valData));
			$flds[] = "$keyData='$valData'";
		}
		$fields = implode(", ",$flds);

		$sql = "update app_users set $fields where user_id=$id";
		$this->conn->Execute($sql);
		$_SESSION['eMsg']="Successfully Updated.";
	}

	/**
	 * Delete Record
	 *
	 * @param string $id_
	 */
	function doDelete($id_ = "") {
		$sql = "delete from app_users where user_id=?";
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
		if (isset($_REQUEST['search_field'])) {
			// lets check if the search field has a value
			if (strlen($_REQUEST['search_field'])>0) {
				// lets assign the request value in a variable
				$search_field = $_REQUEST['search_field'];
				// create a custom criteria in an array
				$qry[] = "au.user_name like '%$search_field%'";
			}
		}
		
		//this is to fillter if not super admin hide!.
		if($_SESSION['admin_session_obj']['user_type']!='Super Administrator'){
			$qry[]="au.user_type != 'Super Administrator'";
			$qry[]="au.emp_id != '0'";
		}
		
		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";
				
		$arrSortBy = array(
		"viewdata"=>"viewdata",
		"user_name"=>"au.user_name",
		"user_fullname"=>"au.user_fullname",
		"user_type"=>"au.user_type",
		"ud_name"=>"aud.ud_name",
		"user_status"=>"au.user_status",
		);

		if(isset($_GET['sortby'])){
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}

		$viewLink = "";
		$editLink = "<a href=\"?statpos=manageuser&edit=',au.user_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/edit.png\" title=\"Edit\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
//		$delLink = "<a href=\"?statpos=manageuser&delete=',au.user_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";

		$sql = "SELECT au.*,aud.ud_name, CONCAT('$viewLink','$editLink','$delLink') as viewdata,
				IF(au.user_status = '1','Active','Inactive') as user_status
					FROM app_users au
					JOIN app_userdept aud on (au.ud_id = aud.ud_id)
					$criteria
					$strOrderBy";

		$sqlcount = "select count(*) as mycount from app_users au $criteria";

		/**$arrFields = array(
		"viewdata"=>"<a href=\"?statpos=manageuser&action=add\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/add.png\" title=\"Add New\" border=0 width=\"16\" height=\"16\"></a>",
		"user_name"=>"User ID",
		"user_fullname"=>"Full Name",
		"user_type"=>"User Type",
		"ud_name"=>"Department",
		"user_status"=>"Status"
		);**/
		$arrFields = array(
			"viewdata"=>"",
			"user_name"=>"User ID",
			"user_fullname"=>"Full Name",
			"user_type"=>"User Type",
			"ud_name"=>"Department",
			"user_status"=>"Status"
		);
		$arrAttribs = array(
		"viewdata"=>"width='40' align='center'"
		);

		$tblDisplayList = new clsTableList($this->conn);
		$tblDisplayList->arrFields = $arrFields;
		$tblDisplayList->paginator->linkPage = "?$queryStr";
		$tblDisplayList->sqlAll = $sql;
		$tblDisplayList->sqlCount = $sqlcount;
		return $tblDisplayList->getTableList($arrAttribs);
	}

	function getUserTypes(){
		//this is to fillter if not super admin hide!.
		if($_SESSION['admin_session_obj']['user_type']!='Super Administrator'){
			$qry[]="user_type != 'Super Administrator'";
		}
		
		$qry[]="user_type_status = '1'";
		
		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";
		
		$sql = "select * from app_usertype $criteria order by user_type_ord";
		$rsResult = $this->conn->Execute($sql);
		$arrData = array();
		while (!$rsResult->EOF) {
			$arrData[] = $rsResult->fields;
			$rsResult->MoveNext();
		}
		if (count($arrData)==0) return $arrData;
		return $arrData;
	}

	function getDepartment(){
		$sql = "select * from app_userdept order by ud_name";
		$rsResult = $this->conn->Execute($sql);
		$arrData = array();
		while (!$rsResult->EOF) {
			$arrData[] = $rsResult->fields;
			$rsResult->MoveNext();
		}
		if (count($arrData)==0) return $arrData;
		return $arrData;
	}
	
	//this is used to list company. 
	function getCompanyList(){
		$sql = "select * from company_info order by comp_name";
		$rsResult = $this->conn->Execute($sql);
		$arrData = array();
		while (!$rsResult->EOF) {
			$arrData[] = $rsResult->fields;
			$rsResult->MoveNext();
		}
		if (count($arrData)==0) return $arrData;
		return $arrData;
	}
	//this is used to list Branch. 
	function getBranchList(){
		$sql = "select * from branch_info order by branchinfo_name";
		$rsResult = $this->conn->Execute($sql);
		$arrData = array();
		while (!$rsResult->EOF) {
			$arrData[] = $rsResult->fields;
			$rsResult->MoveNext();
		}
		if (count($arrData)==0) return $arrData;
		return $arrData;
	}
	//this is used to list 201 Status. 
	function get201StatList(){
		$sql = "select * from emp_201status order by emp201status_name";
		$rsResult = $this->conn->Execute($sql);
		$arrData = array();
		while (!$rsResult->EOF) {
			$arrData[] = $rsResult->fields;
			$rsResult->MoveNext();
		}
		if (count($arrData)==0) return $arrData;
		return $arrData;
	}
	
	function sendEmail($pData = array()){
		try { 
			$body = "<html><head><meta http-equiv=\"Content-Type\" content=\"text/html;charset=utf-8\">
					</head>
					<body>
					<p></p>
					</body></html>";
			// Create the Transport
			$transport = Swift_SmtpTransport::newInstance($pData['user_smtp'], $pData['user_port'])
			  ->setUsername($pData['user_email'])
			  ->setPassword($pData['user_emailpass'])
			  ;
			
			// Create the Mailer using your created Transport
			$mailer = Swift_Mailer::newInstance($transport);
			
			// Create the message
			$message = Swift_Message::newInstance()
			// Give the message a subject
			->setSubject($pData['test_email_add'],"SMTP Configuration Test Email")
			// Set the From address with an associative array
			->setFrom(array($pData['user_email'] => $pData['user_fullname']))
			// Set the To addresses with an associative array
			->setTo(array($pData['test_email_add'] => $pData['user_fullname']))
			// Give it a body
			->setBody('This email confirms that SMTP details set in OrangeHRM are correct. You received this email since your email address was entered to test email in configuration screen.');
			$result = $mailer->send($message, $failures);
			$isValid = TRUE;
			} catch (Swift_ConnectionException $Error) { 
				$isValid = false;
				$_SESSION['eMsg'][] = "Communication problem with SMTP server :".$Error->getMessage();			
			} catch (Swift_Message_MimeException $Error) { 
				$isValid = false;
				$_SESSION['eMsg'][] = "Problems building e-mail :".$Error->getMessage();
			}  catch (Swift_TransportException $Error) {
				$isValid = false;
				$_SESSION['eMsg'][] = "Problems with SMTP Credentials :".$Error->getMessage();;
			}
			return $isValid;
	}
	
	function getPayGroupList(){
		$sql = "select * from payroll_pay_period_sched order by pps_name";
		$rsResult = $this->conn->Execute($sql);
		$arrData = array();
		while (!$rsResult->EOF) {
			$arrData[] = $rsResult->fields;
			$rsResult->MoveNext();
		}
		if (count($arrData)==0) return $arrData;
		return $arrData;
	}
}
?>