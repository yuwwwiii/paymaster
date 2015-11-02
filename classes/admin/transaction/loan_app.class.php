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
class clsLoan_App {

	var $conn;
	var $fieldMap;
	var $Data;
	var $vloan;

	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsLoan_App object
	 */
	function clsLoan_App ($dbconn_ = null) {
		$this->conn =& $dbconn_;
		$this->fieldMap = array(
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
	}

	/**
	 * Get the records from the database
	 *
	 * @param string $id_
	 * @return array
	 */
	function dbFetch ($id_ = "") {
		$sql = "SELECT emp.emp_idnum,sal.salaryinfo_basicrate,saltype.salarytype_name, CONCAT(pinfo.pi_lname,', ',pinfo.pi_fname) as empname, comp.comp_name, bran.branchinfo_name, epost.post_name, dept.ud_name
				FROM emp_masterfile emp
				JOIN emp_personal_info pinfo on (emp.pi_id=pinfo.pi_id)
				LEFT JOIN company_info comp on (comp.comp_id=emp.comp_id)
				LEFT JOIN branch_info bran on (bran.branchinfo_id=emp.branchinfo_id)
				LEFT JOIN emp_position epost on (epost.post_id=emp.post_id)
				LEFT JOIN app_userdept dept on (dept.ud_id=emp.ud_id)
				LEFT JOIN salary_info sal on (sal.emp_id=emp.emp_id)
				LEFT JOIN salary_type saltype on (saltype.salarytype_id=sal.salarytype_id)
				WHERE emp.emp_id='".$id_."' AND sal.salaryinfo_isactive='1'";
		$rsResult = $this->conn->Execute($sql);
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

//		$isValid = false;

		return $isValid;
	}

	/**
	 * Save New
	 *
	 */
	function doSaveLOAN ($update=false) {
		$objClsMngeDecimal = new Application();
		$selection = array();
		$sel = array('pp1','pp2','pp3','pp4','pp5');
		if($_POST['pp1']) $selection[0] = 1; else $selection[0] = 0;
		if($_POST['pp2']) $selection[1] = 1; else $selection[1] = 0;
		if($_POST['pp3']) $selection[2] = 1; else $selection[2] = 0;
		if($_POST['pp4']) $selection[3] = 1; else $selection[3] = 0;
		if($_POST['pp5']) $selection[4] = 1; else $selection[4] = 0;		
		
		if($update==false){
			//save loan info
			foreach ($this->fieldMap as $key => $value){
				if($key=='loan_startdate' || $key=='loan_enddate')
					$postData[] = $key."='".implode(' ',$_POST[$key])."'";
				else if($key=='emp_id')
					$postData[] = $key."='".$_GET['edit']."'";
				else if($key=='loan_suspend')
					$postData[] =  $_POST[$key]?$key."='1'":$key."='0'";//if supend is check save 1 else 0
				else if($key=='loan_periodselection')
					$postData[] = "loan_periodselection='".implode(',',$selection)."'";
				else{
					if(is_float($_POST[$key])){ $pvalue = number_format($_POST[$key],$objClsMngeDecimal->getGeneralDecimalSettings()); }else{ $pvalue = $_POST[$key]; }
					$postData[] = $key."='".$pvalue."'";
				}
			}
			
			$postData[] = "loan_addwho='".AppUser::getData('user_name')."'";
			$fields = implode(",",$postData);
			
			$sql = "INSERT INTO loan_info SET ".$fields;
			$res = "Added.";
		} else { 
			$sqlWA = "SELECT loantype_id,psa_id,loan_numofmonths FROM loan_info WHERE loan_id='".$_GET['loanedit']."'";
			$rsResult = $this->conn->Execute($sqlWA);
			if (!$rsResult->EOF) {
				$rsResult->fields;
			}
			//update loan info
			foreach ($this->fieldMap as $key => $value){
				if($key=='loan_startdate' || $key=='loan_enddate')
					$postData[] = $key."='".implode(' ',$_POST[$key])."'";
				else if($key=='emp_id')
					$postData[] = $key."='".$_GET['edit']."'";
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
					if(is_float($_POST[$key])){ $pvalue = number_format($_POST[$key],$objClsMngeDecimal->getGeneralDecimalSettings(),'.',''); }else{ $pvalue = $_POST[$key]; }
					$postData[] = $key."='".$pvalue."'";
				}
			}
			$postData[] = "loan_updatewho='".AppUser::getData('user_name')."'";
			$postData[] = "loan_updatewhen='".date('Y-m-d h:i:s')."'";
			$fields = implode(",",$postData);
			$sql = "UPDATE loan_info set $fields WHERE loan_id='".$_GET['loanedit']."'";
			$res = "Updated.";
		}
		$_SESSION['eMsg'] = $this->conn->Execute($sql)?"Successfully ".$res:mysql_error();
		if (!mysql_error()) {
			if ($update==true) {
				$sqlDel = "delete from period_benloanduc_sched where loan_id = '".$_GET['loanedit']."'";
				$this->conn->Execute($sqlDel);
				$bdlid = $_GET['loanedit'];
			} else {
				$bdlid = mysql_insert_id();
			}
			for ($x=0; $x<5; $x++) {	//Iterate save after delete Period Schedules
				if ($_POST[$sel[$x]]) { 
					$this->savePS('loan',$bdlid,$x+1);
				}
			}
		}
	}
	
	function savePS ($isBen,$bdlID,$period) { //Period Schedule
		if ($isBen=='benefit') {
			$ben_id = $bdlID;
			$loan_id = 0;
			$empdd_id = 0;
		} else if($isBen=='loan') {
			$ben_id = 0;
			$loan_id = $bdlID;
			$empdd_id = 0;			
		} else if($isBen=='gov') {
			$ben_id = 0;
			$loan_id = 0;
			$empdd_id = $bdlID;		
		}
		$flds[]="empdd_id='".$empdd_id."'";
		$flds[]="ben_id='".$ben_id."'";
		$flds[]="pps_id='".$_SESSION['pps_id']."'";
		$flds[]="emp_id='".$_GET['edit']."'";
		$flds[]="loan_id='".$loan_id."'";
		$flds[]="bldsched_period='".$period."'";
		$fields = implode(", ",$flds);
		$sqlInsert = "INSERT INTO period_benloanduc_sched SET $fields";
		if (!$this->conn->Execute($sqlInsert)) {
			print mysql_error();
			exit;
		}
	}
	
	/**
	 * Save Update
	 *
	 */
	function doSaveEdit(){
		$id = $_GET['edit'];

		$flds = array();
		foreach ($this->Data as $keyData => $valData) {
			$valData = addslashes($valData);
			$flds[] = "$keyData='$valData'";
		}
		$fields = implode(", ",$flds);

		$sql = "update /*app_modules*/ set $fields where mnu_id=$id";
		$this->conn->Execute($sql);
		$_SESSION['eMsg']="Successfully Updated.";
	}

	/**
	 * Delete Record
	 *
	 * @param string $id_
	 */
	function doDelete($id_ = ""){
		$sql="delete from loan_info where loan_id=?";
		$this->conn->Execute($sql,array($id_));
		$_SESSION['eMsg']="Successfully Deleted.";
	}

	/**
	 * Get all the Table Listings
	 *
	 * @return array
	 */
	function getTableList(){
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
			if($_POST['search_company'] != ''){
				$search_company = $_POST['search_company'];
				$qry[] = "comp_name like '%$search_company%'";
			}
			if($_POST['search_location'] != ''){
				$search_location = $_POST['search_location'];
				$qry[] = "branchinfo_name like '%$search_location%'";
			}
		$listcomp = $_SESSION[admin_session_obj][user_comp_list2];
		$listloc = $_SESSION[admin_session_obj][user_branch_list2];
		$listpgroup = $_SESSION[admin_session_obj][user_paygroup_list2];
		IF(count($listcomp)>0){
			$qry[] = "am.comp_id in (".$listcomp.")";//company that can access
		}
		IF(count($listloc)>0){
			$qry[] = "am.branchinfo_id in (".$listloc.")";//location that can access
		}
		IF(count($listpgroup)>0){
			$qry[] = "ppuser.pps_id in (".$listpgroup.")";//pay group that can access
		}
		$qry[]="am.emp_stat IN ('1','7','4','10')";
		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" WHERE ".implode(" AND ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		 "viewdata"=>"viewdata"
		,"emp_idnum"=>"emp_idnum"
		,"pi_lname"=>"pi_lname"
		,"pi_fname"=>"pi_fname"
		,"pi_mname"=>"pi_mname"
		,"post_name"=>"post_name"
		,"comp_name"=>"comp_name"
		,"branchinfo_name"=>"branchinfo_name"
		,"ud_name"=>"ud_name"
		);

		if(isset($_GET['sortby'])){
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}else{
			$strOrderBy = " order by comp.comp_name,bran.branchinfo_name,pinfo.pi_lname";
		}

		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$viewLink = "";
		$emp_num_ = "<a href=\"?statpos=loan_app&edit=',am.emp_id,'\">',am.emp_idnum,'</a>";
		$emp_lname_ = "<a href=\"?statpos=loan_app&edit=',am.emp_id,'\">',pinfo.pi_lname,'</a>";
		$emp_fname_ = "<a href=\"?statpos=loan_app&edit=',am.emp_id,'\">',pinfo.pi_fname,'</a>";
		$editLink = "<a href=\"?statpos=loan_app&edit=',am.emp_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."content.png\" title=\"Employment Details\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
		$delLink = "<a href=\"?statpos=loan_app&delete=',am.emp_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\"  border=0 width=\"16\" height=\"16\"></a>";
		$action = "<a href=\"?statpos=loan_app&action=add\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/add.png\" title=\"Add New\" border=0 width=\"16\" height=\"16\"></a>";

		// SqlAll Query
		$sql = "SELECT am.*, CONCAT('$emp_num_') as emp_idnum, CONCAT('$emp_lname_') as pi_lname, comp.comp_name, CONCAT('$emp_fname_') as pi_fname, CONCAT(UPPER(SUBSTRING(pinfo.pi_mname,1,1)),'.') as pi_mname, post.post_name, pinfo.pi_emailone,
				CONCAT('$viewLink','$editLink') as viewdata, dept.ud_name, bran.branchinfo_name
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
		 "viewdata"=>"width='40' align='center'"
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
		$tblDisplayList->tblBlock->assign("title","Loan Application");

		return $tblDisplayList->getTableList($arrAttribs);
	}
}
?>