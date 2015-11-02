<?php
/**
 * Initial Declaration
 */
/* This is used to declare the different type of paygroup */
$typePS = array(
  	 '1'=>'Daily'
	,'2'=>'Weekly'
	,'3'=>'Bi-Weekly'
	,'4'=>'Semi-Monthly'
	,'5'=>'Monthly'
	,'6'=>'Annual'
);

$processTYPE = array(
  	 '1'=>'Normal'
	,'2'=>'YTD'
	,'3'=>'Bonus'
	,'4'=>'Others'
//	,'5'=>'Last Pay'
);

$transdate = array(
	 '0'=>'0'
	,'1'=>'1'
	,'2'=>'2'
	,'3'=>'3'
	,'4'=>'4'
	,'5'=>'5'
	,'6'=>'6'
	,'7'=>'7'
	,'8'=>'8'
	,'9'=>'9'
	,'10'=>'10'
	,'11'=>'11'
	,'12'=>'12'
	,'13'=>'13'
	,'14'=>'14'
	,'15'=>'15'
	,'16'=>'16'
	,'17'=>'17'
	,'18'=>'18'
	,'19'=>'19'
	,'20'=>'20'
	,'21'=>'21'
	,'22'=>'22'
	,'23'=>'23'
	,'24'=>'24'
	,'25'=>'25'
	,'26'=>'26'
	,'27'=>'27'
	,'28'=>'28'
	,'29'=>'29'
	,'30'=>'30'
	,'31'=>'31'
);

$transdate_month = array(
	 '1'=>'1'
	,'2'=>'2'
	,'3'=>'3'
	,'4'=>'4'
	,'5'=>'5'
	,'6'=>'6'
	,'7'=>'7'
	,'8'=>'8'
	,'9'=>'9'
	,'10'=>'10'
	,'11'=>'11'
	,'12'=>'12'
	,'13'=>'13'
	,'14'=>'14'
	,'15'=>'15'
	,'16'=>'16'
	,'17'=>'17'
	,'18'=>'18'
	,'19'=>'19'
	,'20'=>'20'
	,'21'=>'21'
	,'22'=>'22'
	,'23'=>'23'
	,'24'=>'24'
	,'25'=>'25'
	,'26'=>'26'
	,'27'=>'27'
	,'28'=>'28'
	,'29'=>'29'
	,'30'=>'30'
	,'31'=>'31'
	,'-1'=>'Last Day of Month'
);

$stat = array(
		1 => 'OPEN',
		2 => 'Locked - Pending Approval', //Go to this state as soon as date2 is passed
		3 => 'CLOSED', //Once paid
		4 => 'Post Adjustment'
		);
		
$weekdays = array(
  	 '1'=>'Sunday'
	,'2'=>'Monday'
	,'3'=>'Tuesday'
	,'4'=>'Wednesday'
	,'5'=>'Thursday'
	,'6'=>'Friday'
	,'7'=>'Saturday'
);

/**
 * Class Module
 *
 * @author  JIM
 *
 */
class clsMnge_PG {

	var $conn;
	var $fieldMap;
	var $fieldMap_PP;
	var $Data;
	var $Data_;
	var $otDetail = array();
	var $taDetail = array();
	var $holidayDetail = array();
	var $premiumDetail = array();
	var $payslips;

	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsMnge_PG object
	 */
	function clsMnge_PG($dbconn_ = null) {
		$this->conn =& $dbconn_;
		$this->fieldMap = array(
		 "pps_name" => "pps_name"
		,"pps_desc" => "pps_desc"
		,"comp_id" => "comp_id"
		,"salaryclass_id" => "salaryclass_id"
		,"pps_desc" => "pps_desc"
		,"pps_anchor_date" => "pps_anchor_date"
		,"pps_primary_date" => "pps_primary_date"
		,"pps_primary_trans_date" => "pps_primary_trans_date"
		,"pps_secnd_date" => "pps_secnd_date"
		,"pps_secnd_trans_date" => "pps_secnd_trans_date"
		,"pps_day_start_time" => "pps_day_start_time"
		,"pps_day_continuous_time" => "pps_day_continuous_time"
		,"pps_start_week_day" => "pps_start_week_day"
		,"pps_start_day_week" => "pps_start_day_week"
		,"pps_trans_date" => "pps_trans_date"
		,"pps_pri_daymonth" => "pps_pri_daymonth"
		,"pps_secnd_daymonth" => "pps_secnd_daymonth"
		,"pps_pri_trans_daymonth" => "pps_pri_trans_daymonth"
		,"pps_secnd_trans_daymonth" => "pps_secnd_trans_daymonth"
		,"pps_trans_datebd" => "pps_trans_datebd"
		,"pps_time_zone" => "pps_time_zone"
		,"pps_new_daytrigger_time" => "pps_new_daytrigger_time"
		,"pps_max_shifttime" => "pps_max_shifttime"
		,"fr_id" => "fr_id"
		,"tt_pay_group" => "tt_pay_group"
		);
		$this->fieldMap_PP = array(
		 "pp_stat_id" => "payperiod_status_id"
		,"payperiod_name" => "payperiod_name" 
		,"payperiod_period" => "payperiod_period" 
		,"payperiod_period_year" => "payperiod_period_year"
		,"payperiod_is_primary" => "payperiod_is_primary"
		,"payperiod_start_date" => "payperiod_start_date"
		,"payperiod_end_date" => "payperiod_end_date"
		,"payperiod_trans_date" => "payperiod_trans_date"
		,"payperiod_adv_end_date" => "payperiod_adv_end_date"
		,"payperiod_adv_trans_date" => "payperiod_adv_trans_date"
		,"payperiod_tainted" => "payperiod_tainted"
		,"payperiod_type" => "type"
		,"is_payslip_viewable" => "ess_payslip"
		,"convert_leave" => "convert_leave"
		);
	}

	/**
	 * Get the records from the database
	 *
	 * @param string $id_
	 * @return array
	 */
	function dbFetch($id_ = "") {
		$sql = "Select a.*, b.fr_name, c.comp_name, 
				IF(a.salaryclass_id='1','Daily',IF(a.salaryclass_id='2','Weekly',IF(a.salaryclass_id='3','Bi-Weekly',IF(a.salaryclass_id='4','Semi-Monthly',IF(a.salaryclass_id='5','Monthly','Annual'))))) as salaryclass
				from payroll_pay_period_sched a 
				left join factor_rate b on (b.fr_id=a.fr_id) 
				inner join company_info c on (c.comp_id=a.comp_id) 
				where a.pps_id=?";
		$rsResult = $this->conn->Execute($sql,array($id_));
		if(!$rsResult->EOF){
			$rsResult->fields['pps_new_daytrigger_time']=dDate::convertSecondsToHMS($rsResult->fields['pps_new_daytrigger_time'],false);
			$rsResult->fields['pps_max_shifttime']=dDate::convertSecondsToHMS($rsResult->fields['pps_max_shifttime'],false);
			return $rsResult->fields;
		}
	}
	
	/**
	 * Get the records from the database
	 *
	 * @param string $id_
	 * @return array
	 */
	function dbFetch_pp($id_ = "") {
		 $sql = "SELECT ppp.*, psar.pps_name,
				DATE_FORMAT(payperiod_start_date,'%Y-%m-%d') as payperiod_start_date,
				DATE_FORMAT(payperiod_end_date,'%Y-%m-%d') as payperiod_end_date,
				DATE_FORMAT(payperiod_trans_date,'%Y-%m-%d') as payperiod_trans_date
					FROM payroll_pay_period ppp
					JOIN payroll_pay_period_sched psar on (psar.pps_id=ppp.pps_id)
					where ppp.payperiod_id=?";
		$rsResult = $this->conn->Execute($sql,array($id_));
		if (!$rsResult->EOF){
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
		if(count($pData_)>0){
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
	 * Populate array parameters to Data Variable
	 *
	 * @param array $pData_
	 * @param boolean $isForm_
	 * @return bool
	 */
	function doPopulateData_pp($pData_ = array(),$isForm_ = false){
		if(count($pData_)>0){
			foreach ($this->fieldMap_PP as $key => $value){
				if($isForm_){
					$this->Data_[$value] = $pData_[$value];
				}else{
					$this->Data_[$key] = $pData_[$value];
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
		if (empty($pData_['pps_name'])){
			$isValid = false;
			$_SESSION['eMsg'][] = "Please enter a Name.";
		}
		return $isValid;
	}
	
	function doValidateData_pp($pData_ = array()){
		$isValid = true;
		if ((empty($pData_['payperiod_start_date']) && empty($pData_['payperiod_end_date']) && empty($pData_['payperiod_trans_date'])) || (($pData_['payperiod_start_date'] == 0000-00-00) && ($pData_['payperiod_end_date'] == 0000-00-00) && ($pData_['payperiod_trans_date'] == 0000-00-00))) {
			$isValid = false;
			$_SESSION['eMsg'][] = "Please provide a valid Date.";
		}
//		$sql = "SELECT * FROM payroll_pay_period";
//		$rsResult = $this->conn->Execute($sql,array($id_));
//		IF (!$rsResult->EOF){
//			$isValid = false;
//			$_SESSION['eMsg'][] = "Cannot Save Duplicate Pay Period Date.";
//		}
		return $isValid;
	}
	
	function doValidateData_emp($pData_ = array()) {
		$isValid = true;
		if (empty($pData_['chkAttend'])) {
			$isValid = false;
			$_SESSION['eMsg'][] = "Please Select Employee first.";
		}
		return $isValid;
	}

	/**
	 * Save New
	 *
	 */
	function doSaveAdd() {
		$flds = array();
		foreach ($this->Data as $keyData => $valData) {
			if ($keyData == 'pps_new_daytrigger_time') {
				$valData = dDate::parseTimeUnit($_POST['pps_new_daytrigger_time']);
			}
			if ($keyData == 'pps_max_shifttime') {
				$valData = dDate::parseTimeUnit($_POST['pps_max_shifttime']);
			}
			$valData = trim(addslashes($valData));
			$flds[] = "$keyData='$valData'";
		}
		$flds[] = "pps_addwho = '".AppUser::getData('user_name')."'";
		$fields = implode(", ",$flds);
		$sql = "insert into payroll_pay_period_sched set $fields";
		$this->conn->Execute($sql);

		$_SESSION['eMsg']="Successfully Added.";
	}
	
	/**
	 * Save New
	 *
	 */
	function doSaveAdd_PP() {
		$sqlSched = "Select salaryclass_id,pps_pri_daymonth,pps_pri_trans_daymonth,pps_secnd_daymonth,pps_secnd_trans_daymonth from payroll_pay_period_sched where pps_id = '".$_GET['ppsched']."'";
		$varSched = $this->conn->Execute($sqlSched);
//		printa($varSched->fields);
//		printa($_POST);
//		exit;
		$flds = array();
		foreach ($this->Data_ as $keyData => $valData) {
			if ($keyData == 'payperiod_start_date'){
				$valData = $_POST['payperiod_start_date'].' 00:00';
			}
			if ($keyData == 'payperiod_end_date') {
				$valData = $_POST['payperiod_end_date'].' 23:59';
			}
			if ($keyData == 'payperiod_trans_date') {
				$valData = $_POST['payperiod_trans_date'].' 23:59';
			}
			if ($keyData == 'pp_stat_id') {
				$valData = '1';
			}
			$valData = trim(addslashes($valData));
			$flds[] = "$keyData='$valData'";
		}
		$flds[] = "payperiod_freq = '".$_POST['payperiod_freq']."'";
		$flds[] = "pps_id = '".$_GET['ppsched']."'";
		$flds[] = "payperiod_addwho = '".AppUser::getData('user_name')."'";
		$fields = implode(", ",$flds);
//		printa($flds);
//		exit;
		$sql = "insert into payroll_pay_period set $fields";
		$this->conn->Execute($sql);

		$_SESSION['eMsg']="Successfully Added.";
	}
	
	function doSaveAdd_PP_YTD() {
		$sqlSched = "Select salaryclass_id,pps_pri_daymonth,pps_pri_trans_daymonth,pps_secnd_daymonth,pps_secnd_trans_daymonth from payroll_pay_period_sched where pps_id = '".$_GET['ppsched']."'";
		$varSched = $this->conn->Execute($sqlSched);
//		printa($varSched->fields);
//		printa($_POST);
//		exit;
		$flds = array();
//		foreach ($this->Data_ as $keyData => $valData) {
//			if ($keyData == 'payperiod_start_date'){
//				$valData = $_POST['payperiod_start_date'].' 00:00';
//			}
//			if ($keyData == 'payperiod_end_date') {
//				$valData = $_POST['payperiod_end_date'].' 23:59';
//			}
//			if ($keyData == 'payperiod_trans_date') {
//				$valData = $_POST['payperiod_trans_date'].' 23:59';
//			}
//			if ($keyData == 'pp_stat_id') {
//				$valData = '1';
//			}
//			$valData = trim(addslashes($valData));
//			$flds[] = "$keyData='$valData'";
//		}
//		printa($flds); exit;
		$flds[] = "payperiod_period = '".$_POST['from_month']."'";
		$flds[] = "payperiod_period_to = '".$_POST['to_month']."'";
		$flds[] = "payperiod_period_year = '".$_POST['from_year']."'";
		$flds[] = "payperiod_period_year_to = '".$_POST['to_year']."'";
		
		$flds[] = "payperiod_start_date = '".$_POST['payperiod_start_date']."'";
		$flds[] = "payperiod_end_date = '".$_POST['payperiod_end_date']."'";
		$flds[] = "payperiod_trans_date = '".$_POST['payperiod_trans_date']."'";
		$flds[] = "pp_stat_id = '1'";
		
		$flds[] = "payperiod_type = '".$_POST['type']."'";
		$flds[] = "payperiod_freq = '".$_POST['payperiod_freq']."'";
		$flds[] = "pps_id = '".$_GET['ppsched']."'";
		$flds[] = "payperiod_addwho = '".AppUser::getData('user_name')."'";
		$flds[] = "payperiod_name = '".$_POST['payperiod_name']."'";
		$fields = implode(", ",$flds);
//		printa($flds);
//		exit;
		$sql = "insert into payroll_pay_period set $fields";
		$this->conn->Execute($sql);

		$_SESSION['eMsg']="Successfully Added.";
	}
	/**
	 * Save Employee
	 *
	 */
	function doSaveEmployee($pData) {
		$flds = array();
		$flds_ = array();
		$ctr=0;
		do {
			$sqlpcal = "SELECT * FROM payroll_comp WHERE emp_id='".$pData['chkAttend'][$ctr]."'";
			$pps = $this->conn->Execute($sqlpcal);
			if (!$pps->EOF) {
				$flds_[]="pps_id='".$_GET['empinput']."'";
				$fields_ = implode(", ",$flds_);
				$sqlupdate = "UPDATE payroll_comp set $fields_ WHERE emp_id='".$pps->fields['emp_id']."'";
				$this->conn->Execute($sqlupdate);
			} else {
				$flds_[] = "emp_id='".$pData['chkAttend'][$ctr]."'";
				$flds_[]="pps_id='".$_GET['empinput']."'";
				$fields_ = implode(", ",$flds_);
				$sqlinsert = "INSERT INTO payroll_comp set $fields_";
				$this->conn->Execute($sqlinsert);
			}
			$flds[] = "emp_id='".$pData['chkAttend'][$ctr]."'";
			$flds[] = "pps_id='".$_GET['empinput']."'";
			$flds[] = "ppsu_addwho='".AppUser::getData('user_name')."'";
			$fields = implode(", ",$flds);
			$sql = "insert into payroll_pps_user set $fields";
			$this->conn->Execute($sql);
			$flds = "";
			$fields = "";
			$flds_ = "";
			$fields_ = "";
			$ctr++;
		} while($ctr < sizeof($pData['chkAttend']));
		$_SESSION['eMsg']="Successfully Updated.";
	}

	/**
	 * Save Update
	 *
	 */
	function doSaveEdit_PP(){
		$id = $_GET['ppsched_edit'];
		$flds = array();
		foreach ($this->Data_ as $keyData => $valData) {
			if ($keyData == 'payperiod_start_date') {
				$valData = $_POST['payperiod_start_date'].' 00:00';
			}
			if ($keyData == 'payperiod_end_date') {
				$valData = $_POST['payperiod_end_date'].' 23:59';
			}
			if ($keyData == 'payperiod_trans_date') {
				$valData = $_POST['payperiod_trans_date'].' 23:59';
			}
			if ($keyData == 'pp_stat_id') {
				$valData = $_POST['pp_stat_id'];
			}
			$valData = trim(addslashes($valData));
			$flds[] = "$keyData='$valData'";
		}
		if($_POST['type']=='2'){
			$flds[] = "payperiod_period='".$_POST['from_month']."'";
			$flds[] = "payperiod_period_to='".$_POST['to_month']."'";
			$flds[] = "payperiod_period_year='".$_POST['from_year']."'";
			$flds[] = "payperiod_period_year_to='".$_POST['to_year']."'";
		}
//		$flds[] = "pp_stat_id='".$_POST['pp_stat_id']."'";
		$flds[] = "payperiod_freq='".$_POST['payperiod_freq']."'";
		$flds[] = "payperiod_updatewho = '".AppUser::getData('user_name')."'";
		$flds[] = "payperiod_updatewhen = '".date('Y-m-d h:i:s')."'";
		$fields = implode(", ",$flds);
		$sql = "update payroll_pay_period set $fields where payperiod_id=$id";
		$this->conn->Execute($sql);
		$_SESSION['eMsg']="Successfully Updated.";
	}
	
	/**
	 * Save Update
	 *
	 */
	function doSaveEdit(){
		$id = $_GET['edit'];
		$flds = array();
		foreach ($this->Data as $keyData => $valData) {
			if ($keyData == 'pps_new_daytrigger_time'){
				$valData = dDate::parseTimeUnit($_POST['pps_new_daytrigger_time']);
			}
			if ($keyData == 'pps_max_shifttime'){
				$valData = dDate::parseTimeUnit($_POST['pps_max_shifttime']);
			}
			$valData = trim(addslashes($valData));
			$flds[] = "$keyData='$valData'";
		}
		$flds[]="pps_updatewho = '".AppUser::getData('user_name')."'";
		$flds[]="pps_updatewhen = '".date('Y-m-d h:i:s')."'";
		$fields = implode(", ",$flds);
		$sql = "update payroll_pay_period_sched set $fields where pps_id=$id";
		$this->conn->Execute($sql);
		$_SESSION['eMsg']="Successfully Updated.";
	}

	/**
	 * added by: jmabignay (2009.01.27)
	 * Update Pay Period Status
	 *
	 */
	function doSaveEdit_PPstat($payperiod_id_ = null){
		$flds = array();
		$flds[] = "pp_stat_id='".$_POST['pp_stat_id']."'";
		$fields = implode(", ",$flds);
		$sql = "update payroll_pay_period set $fields where payperiod_id=$payperiod_id_";
		$this->conn->Execute($sql);
		$_SESSION['eMsg']="Successfully Updated.";
	}
	
	/**
	 * Delete Pay Period Schedule Record
	 * @param string $id_
	 */
	function doDelete ($id_ = "") {
		$sqlcheck = "SELECT * FROM payroll_pay_period WHERE pps_id=?";
		$rsResult = $this->conn->Execute($sqlcheck,array($id_));
		if($rsResult->EOF){
			$sqlcheck = "SELECT * FROM payroll_pps_user WHERE pps_id=?";
			$rsResult_ = $this->conn->Execute($sqlcheck,array($id_));
			if($rsResult_->EOF){
				$sql_ = "DELETE FROM payroll_pay_period_sched WHERE pps_id=?";
				$this->conn->Execute($sql_,array($id_));
				$_SESSION['eMsg']="Successfully Deleted.";
			}else{
				$_SESSION['eMsg']="Cannot Delete, This Pay Group is used in other table.";
			}
		}else{
			$_SESSION['eMsg']="Cannot Delete, This Pay Group is used in other table.";
		}
	}

	/**
	 * Delete Employee Assign Record
	 * @param string $id_
	 */
	function doDelete_Emp ($id_ = "") {
		$sqlpps = "SELECT * FROM payroll_pps_user where ppsu_id=?";
		$pps = $this->conn->Execute($sqlpps,array($id_));
		if(!$pps->EOF){
			$flds[]="pps_id='0'";
			$fields = implode(", ",$flds);
			$sqlupdate = "UPDATE payroll_comp set $fields WHERE emp_id='".$pps->fields['emp_id']."'";
			$this->conn->Execute($sqlupdate);
			
			$sql = "DELETE FROM payroll_pps_user WHERE ppsu_id=?";
			$this->conn->Execute($sql,array($id_));
		}
		$_SESSION['eMsg']="Successfully Deleted.";
	}
	
	/**
	 * DELETE Pay Period Record 
	 * @param string $id_
	 */
	function DeletePayPeriodList ($id_ = "") {
		$sqlcheck = "SELECT * FROM payroll_pay_stub WHERE payperiod_id=?";
		$rsResult = $this->conn->Execute($sqlcheck,array($id_));
		IF($rsResult->EOF){
			$sqlTArec = "SELECT * FROM ta_emp_rec WHERE payperiod_id=?";
			$rsResultTArec = $this->conn->Execute($sqlTArec,array($id_));
			IF($rsResultTArec->EOF){
				$sqlOTrec = "SELECT * FROM ot_record WHERE payperiod_id=?";
				$rsResultOTrec = $this->conn->Execute($sqlOTrec,array($id_));
				IF($rsResultOTrec->EOF){
					$sql = "DELETE FROM payroll_pay_period WHERE payperiod_id=?";
					$this->conn->Execute($sql,array($id_));
					$_SESSION['eMsg']="Successfully Deleted.";
				}ELSE{
					$_SESSION['eMsg']="Cannot Delete, Please delete OT Record.";
				}
			}ELSE{
				$_SESSION['eMsg']="Cannot Delete, Please delete Imported TA Summary.";
			}
		}ELSE{
			$_SESSION['eMsg']="Cannot Delete, Please delete employee with record in this pay period.";
		}
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
		if (isset($_REQUEST['search_field'])) {

			// lets check if the search field has a value
			if (strlen($_REQUEST['search_field'])>0) {
				// lets assign the request value in a variable
				$search_field = $_REQUEST['search_field'];

				// create a custom criteria in an array
				$qry[] = "pps_name like '%$search_field%'";
			}
		}

		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		 "viewdata"=>"viewdata"
		,"comp_name" => "comp_name"
		,"pps_name" => "pps_name"
		,"pps_desc" => "pps_desc"
		,"salaryclass_id" => "salaryclass_id"
		);
		if(isset($_GET['sortby'])){
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}

		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$viewLink = "<a href=\"?statpos=mnge_pg&ppsched=',am.pps_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES."calendar.png\" title=\"View Schedule\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
		$editLink = "<a href=\"?statpos=mnge_pg&edit=',am.pps_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/edit.png\" title=\"Edit\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
		$empLink = "<a href=\"?statpos=mnge_pg&empinput=',am.pps_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/useradd.png\" title=\"Select Employee\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
		$delLink = "<a href=\"?statpos=mnge_pg&delete=',am.pps_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";

		// SqlAll Query
		$sql = "SELECT am.*, CONCAT('$empLink','$viewLink','$editLink','$delLink') as viewdata,b.comp_name,
				if(salaryclass_id='1','Daily',IF(salaryclass_id='2','Weekly',IF(salaryclass_id='3','Bi-Weekly',IF(salaryclass_id='4','Semi-monthly',IF(salaryclass_id='5','Monthly','Annual'))))) as salaryclass_id
						FROM payroll_pay_period_sched am
						JOIN company_info b on (am.comp_id=b.comp_id)
						$criteria
						$strOrderBy";

		// Field and Table Header Mapping
		$arrFields = array(
		 "viewdata"=>"<a href=\"?statpos=mnge_pg&action=add\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/add.png\" title=\"Add New\" border=0 width=\"16\" height=\"16\" /></a>"
		,"comp_name" => "Company"
		,"pps_name" => "Name"
		,"pps_desc" => "Description"
		,"salaryclass_id" => "Type"
		);

		// Column (table data) User Defined Attributes
		$arrAttribs = array(
		"mnu_ord" => "align='center'",
		"viewdata" => "width='80' align='center'"
		);

		// Process the Table List
		$tblDisplayList = new clsTableList($this->conn);
		$tblDisplayList->arrFields = $arrFields;
		$tblDisplayList->paginator->linkPage = "?$queryStr";
		$tblDisplayList->sqlAll = $sql;
		$tblDisplayList->sqlCount = $sqlcount;
		$tblDisplayList->tblBlock->assign("title","Manage Pay Group");

		return $tblDisplayList->getTableList($arrAttribs);
	}
	
	/**
	 * Get all the Table Listings
	 * @return array
	 */
	function getTableList_SchedList() {
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
				$qry[] = "psar.pps_name like '%$search_field%'";
			}
		}
		$qry[]="ppp.pps_id='".$_GET['ppsched']."'";
		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		 "viewdata" => "viewdata"
		,"pps_name" => "pps_name"
		,"paysched"=>"paysched"
		,"payperiod_trans_date" => "payperiod_trans_date"
		,"pperiod" => "pperiod"
		,"salaryclass_id" => "salaryclass_id"
		,"classification" => "classification"
		,"pp_stat_id" => "pp_stat_id"
		,"is_payslip_viewable" => "is_payslip_viewable"
		);

		if (isset($_GET['sortby'])) {
			$strOrderBy = " ORDER BY ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		} else {
            $strOrderBy = " ORDER BY ppp.payperiod_trans_date DESC";
        }

		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$viewLink = "<a href=\"?statpos=mnge_pg&ppsched=',psar.pps_id,'&ppsched_view=',ppp.payperiod_id,'\"><img src=\"".SYSCONFIG_THEME_URLPATH.SYSCONFIG_THEME."/images/admin/zoom.gif\" title=\"View\" hspace=\"2px\" border=0></a>";
		$editLink = "<a href=\"?statpos=mnge_pg&ppsched=',psar.pps_id,'&ppsched_edit=',ppp.payperiod_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/edit.png\" title=\"Edit\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
		$delLink = "<a href=\"?statpos=mnge_pg&ppsched=',psar.pps_id,'&ppsched_del=',ppp.payperiod_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\"  border=0 width=\"16\" height=\"16\"></a>";

		// SqlAll Query
		$sql = "SELECT ppp.*, CONCAT('$editLink','$delLink') as viewdata, 
				IFNULL(NULLIF(ppp.payperiod_name,''),psar.pps_name) as pps_name,
				CONCAT(UPPER(date_format(payperiod_start_date,'%b %d')),' to ',UPPER(date_format(payperiod_end_date,'%b %d, %Y'))) as paysched, 
				DATE_FORMAT(payperiod_start_date,'%d-%b-%y %h:%i %p') as payperiod_start_date,
				DATE_FORMAT(payperiod_end_date,'%d-%b-%y %h:%i %p') as payperiod_end_date,
				UPPER(DATE_FORMAT(payperiod_trans_date,'%M %d, %Y')) as payperiod_trans_date,
				IF(salaryclass_id='1','Daily',IF(salaryclass_id='2','Weekly',IF(salaryclass_id='3','Bi-Weekly',IF(salaryclass_id='4','Semi-monthly',IF(salaryclass_id='5','Monthly','Annual'))))) as salaryclass_id,
				IF(pp_stat_id='1','OPEN',IF(pp_stat_id='2','Locked - Pending Approval',IF(pp_stat_id='3','CLOSED','Post Adjustment'))) as pp_stat_id,
				IF(ppp.payperiod_type='2','YTD',IF(ppp.payperiod_type='3','Bonus',IF(ppp.payperiod_type='4','Others',IF(ppp.payperiod_type='5','Last Pay','Normal')))) as classification,
				IF(ppp.payperiod_freq='1','1st',IF(ppp.payperiod_freq='2','2nd',IF(ppp.payperiod_freq='3','3rd',IF(ppp.payperiod_freq='4','4th',IF(ppp.payperiod_freq='5','5th','All'))))) as pperiod,	
				IF(ppp.is_payslip_viewable=1,'YES','NO') as is_payslip_viewable	
					FROM payroll_pay_period ppp
					JOIN payroll_pay_period_sched psar on (psar.pps_id=ppp.pps_id)
					$criteria
					$strOrderBy";

		// Field and Table Header Mapping
		$arrFields = array(
		 "viewdata" => "Action"
		,"pps_name" => "Name"
		,"paysched"=>"Cut-offs"
		,"payperiod_trans_date" => "Pay Date"
		,"pperiod" => "Period"
		,"salaryclass_id" => "Type"
		,"classification" => "Payroll Type"
		,"pp_stat_id" => "Status"
		,"is_payslip_viewable" => "View Payslip"
		);

		// Column (table data) User Defined Attributes
		$arrAttribs = array("viewdata"=>"width='60' align='center'");

		// Process the Table List
		$tblDisplayList = new clsTableList($this->conn);
		$tblDisplayList->arrFields = $arrFields;
		$tblDisplayList->paginator->linkPage = "?$queryStr";
		$tblDisplayList->sqlAll = $sql;
		$tblDisplayList->sqlCount = $sqlcount;
		return $tblDisplayList->getTableList($arrAttribs);
	}
	
	/**
	 * Get all the Table Listings
	 * @return array
	 */
	function getTableList_Emp() {
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
				$qry[] = "(pi_lname like '%$search_field%' || pi_fname like '%$search_field%' || post_name like '%$search_field%')";
			}
		}
		//@note query filters for Quick Search->Suppliers
		if (count($_POST) > 0) {
			//@note for department
			if (($_POST['ud_name'] != '') && ($_POST['ud_name'] != '*')) {
				$qry[] = "dept.ud_name like '" . $_POST['ud_name'] . "%' ";
			}
			//@note for product line category
			if (($_POST['emp_idnum'] != '') && ($_POST['emp_idnum'] != '*')) {
				//$qry[] = "MATCH(am.si_prodlinecat) AGAINST('" . $_POST['si_prodlinecat'] . "') ";
				$qry[] = "(empinfo.emp_idnum LIKE '%".$_POST['emp_idnum']."%' || empinfo.pi_fname LIKE '%".$_POST['emp_idnum']."%' || empinfo.pi_lname LIKE '%".$_POST['emp_idnum']."%') ";
			}
		}
		if ($_GET['statpos']=='process_payroll') {
			$qrysql = "JOIN salary_info sal on (sal.emp_id=empinfo.emp_id)";
			$qrysql_= "JOIN payroll_pps_user pps on (pps.emp_id=empinfo.emp_id)";
			$qrysql2= "JOIN payroll_comp pc on (pc.emp_id=sal.emp_id)";
			$sqlsal = "SELECT payperiod_type,DATE_FORMAT(payperiod_start_date,'%Y-%m-%d') as payperiod_start_date, DATE_FORMAT(payperiod_end_date,'%Y-%m-%d') as payperiod_end_date, payperiod_id FROM payroll_pay_period WHERE payperiod_id='".$_GET['ppsched_view']."'";
			$result = $this->conn->Execute($sqlsal);
			$var = $result->fields['payperiod_id'];
			$qry[]="empinfo.emp_id not in (SELECT a.emp_id FROM payroll_pps_user a JOIN payroll_paystub_report re on (a.emp_id=re.emp_id) WHERE payperiod_id = '".$_GET['ppsched_view']."')";
			$qry[]="sal.salaryinfo_effectdate <= '".$result->fields['payperiod_end_date']."'";
			$qry[]="pps.pps_id = '".$_GET['ppsched']."'";
			IF($result->fields['payperiod_type']==3){
				$otadd = "N/A";
			} ELSE {
				$otadd = "<a href=\"?statpos=process_payroll&otcomp=',pps.pps_id,'&emp=',empinfo.emp_id,'&edit=',$var,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/leaveicon.png\" title=\"TA\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
			}
			$leaveadd = "<a href=\"?statpos=process_payroll&edit=',pps.pps_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/leaveicon.png\" title=\"Leave\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
			
			$qry[]="sal.salaryinfo_isactive = '1'";
			$qry[] = "pc.fr_id not in (0)";
			$listcomp = $_SESSION[admin_session_obj][user_comp_list2];
			$listloc = $_SESSION[admin_session_obj][user_branch_list2];
			$listpgroup = $_SESSION[admin_session_obj][user_paygroup_list2];
			IF(count($listcomp)>0){
				$qry[] = "empinfo.comp_id in (".$listcomp.")";//company that can access
			}
			IF(count($listloc)>0){
				$qry[] = "empinfo.branchinfo_id in (".$listloc.")";//location that can access
			}
			IF(count($listpgroup)>0){
				$qry[] = "pps.pps_id in (".$listpgroup.")";//pay group that can access
			}
		} else {
			$qry[]="empinfo.emp_id not in (SELECT a.emp_id FROM payroll_pps_user a)";
		}
        $qry[] = "empinfo.emp_stat in ('1','7','10','8')";
        $qry[] = "((empinfo.emp_resigndate IS NULL) OR (empinfo.emp_resigndate >= '".$result->fields['payperiod_start_date']."' AND empinfo.emp_resigndate <= '".$result->fields['payperiod_end_date']."'))";
		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" where ".implode(" AND ",$qry):"";
		// Sort field mapping
		$arrSortBy = array(
		 "chkbox" => "chkbox"
		,"empLink" => "empLink"
		,"emp_idnum" => "emp_idnum"
		,"pi_lname" => "pi_lname"
		,"pi_fname" => "pi_fname"
		,"post_name" => "post_name"
		,"comp_name" => "comp_name"
		,"branchinfo_name" => "branchinfo_name"
		,"ud_name" => "ud_name"
		);

		if (isset($_GET['sortby'])) {
			$strOrderBy = " ORDER BY ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		} else {
			$strOrderBy = " ORDER BY pinfo.pi_lname";
		}

		//@note: this is used to count and check all the checkbox.
		//@note set t1 = 0
		$sql = "set @t1:=0";
		$this->conn->Execute($sql);
		//get total number of records and pass it to the javascript function CheckAll
		$sql_ = "SELECT COUNT(*) AS mycount_
					FROM emp_masterfile empinfo
					JOIN emp_personal_info pinfo ON (pinfo.pi_id=empinfo.pi_id)
					LEFT JOIN app_userdept dept ON (dept.ud_id=empinfo.ud_id)
					LEFT JOIN emp_position post ON (post.post_id=empinfo.post_id)
					LEFT JOIN company_info comp ON (comp.comp_id=empinfo.comp_id)
					LEFT JOIN branch_info bran ON (bran.branchinfo_id=empinfo.branchinfo_id)
				$qrysql
				$qrysql_
				$qrysql2
				$criteria
				$strOrderBy";
		$rsResult = $this->conn->Execute($sql_);
		if (!$rsResult->EOF) {
			$mycount = $rsResult->fields['mycount_'];
		}
		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$ctr=0;
		$chkAttend = "<input type=\"checkbox\" name=\"chkAttend[]\" id=\"chkAttend[',@t1:=@t1+1,']\" value=\"',empinfo.emp_id,'\" onclick=\"javascript:UncheckAll({$mycount});\">";
		
		// SqlAll Query
		$sql = "SELECT pinfo.pi_lname,pinfo.pi_fname, dept.ud_name,  post.post_name, bran.branchinfo_name, comp.comp_name,
				CONCAT('$chkAttend') as chkbox, empinfo.emp_idnum, CONCAT('$otadd') as empLink
						FROM emp_masterfile empinfo
						JOIN emp_personal_info pinfo on (pinfo.pi_id=empinfo.pi_id)
						LEFT JOIN app_userdept dept on (dept.ud_id=empinfo.ud_id)
						LEFT JOIN emp_position post on (post.post_id=empinfo.post_id)
						LEFT JOIN company_info comp on (comp.comp_id=empinfo.comp_id)
						LEFT JOIN branch_info bran on (bran.branchinfo_id=empinfo.branchinfo_id)
						$qrysql
						$qrysql_
						$qrysql2
						$criteria
						$strOrderBy";
		// Field and Table Header Mapping
		$arrFields = array(
		 "chkbox" => "<input type=\"checkbox\" name=\"chkAttendAll\" id=\"chkAttendAll\" onclick=\"javascript:CheckAll({$mycount});\">"
		,"empLink" => "Action"
		,"emp_idnum" => "Emp No."
		,"pi_lname" => "Last Name"
		,"pi_fname" => "First Name"
		,"post_name" => "Position"
		,"comp_name" => "Company"
		,"branchinfo_name" => "Branch"
		,"ud_name" => "Department"
		);
		// Column (table data) User Defined Attributes
		$arrAttribs = array(
		 "viewdata"=>"width='30' align='center'"
		,"empLink"=>"width='30' align='center'"
		,"chkbox"=>"width='10' align='center'"
		);
		// Process the Table List
		$tblDisplayList = new clsTableList($this->conn);
//		$tblDisplayList->tblBlock->templateFile = "table2.tpl.php";
		$tblDisplayList->arrFields = $arrFields;
		$tblDisplayList->paginator->linkPage = "?$queryStr";
		$tblDisplayList->sqlAll = $sql;
		$tblDisplayList->sqlCount = $sqlcount;
//		$tblDisplayList->tblBlock->templateFile = "table_nosort.tpl.php";
		$tblDisplayList->tblBlock->assign("noSearchStart","<!--");
		$tblDisplayList->tblBlock->assign("noSearchEnd","-->");
		return $tblDisplayList->getTableList($arrAttribs);
	}
	
	/**
	 * Get all the Table Listings
	 *
	 * @return array
	 */
	function getTableList_EmpSave() {
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
				$qry[] = "(pinfo.pi_fname like '%$search_field%' or pinfo.pi_lname like '%$search_field%' or dept.ud_name like '%$search_field%')";
			}
		}
		$qry[]="psar.pps_id = '".$_GET['empinput']."'";
        $qry[]  = "empinfo.emp_stat in ('1','7')";
		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		 "viewdata"=>"viewdata"
		,"emp_idnum" => "emp_idnum"
		,"pi_lname" => "pi_lname"
		,"pi_fname" => "pi_fname"
		,"post_name" => "post_name"
		,"comp_name" => "comp_name"
		,"branchinfo_name" => "branchinfo_name"
		,"ud_name" => "ud_name"
		);

		if(isset($_GET['sortby'])){
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}else{
			$strOrderBy = " order by pinfo.pi_lname";
		}

		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$delLink = "<a href=\"?statpos=mnge_pg&empinput=',psar.pps_id,'&empinput_del=',psaru.ppsu_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\"  border=0 width=\"16\" height=\"16\"></a>";

		// SqlAll Query
		$sql = "SELECT psaru.*,pinfo.pi_lname,pinfo.pi_fname, dept.ud_name, psar.*, post.post_name,
				CONCAT('$delLink') as viewdata,empinfo.emp_idnum, bran.branchinfo_name, comp.comp_name
						FROM payroll_pps_user psaru
						JOIN payroll_pay_period_sched psar on (psar.pps_id=psaru.pps_id)
						JOIN emp_masterfile empinfo on (empinfo.emp_id=psaru.emp_id)
						JOIN emp_personal_info pinfo on (pinfo.pi_id=empinfo.pi_id)
						LEFT JOIN app_userdept dept on (dept.ud_id=empinfo.ud_id)
						LEFT JOIN emp_position post on (post.post_id=empinfo.post_id)
						LEFT JOIN company_info comp on (comp.comp_id=empinfo.comp_id)
						LEFT JOIN branch_info bran on (bran.branchinfo_id=empinfo.branchinfo_id)
						$criteria
						$strOrderBy";

		// Field and Table Header Mapping
		$arrFields = array(
		 "viewdata"=>"Action"
		,"emp_idnum" => "Emp No."
		,"pi_lname" => "Last Name"
		,"pi_fname" => "First Name"
		,"post_name" => "Position"
		,"comp_name" => "Company"
		,"branchinfo_name" => "Branch"
		,"ud_name" => "Department"
		);
		// Column (table data) User Defined Attributes
		$arrAttribs = array(
		"viewdata"=>"width='30' align='center'"
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
	 * @note: This is used to Generate Payslip
	 * @param $payperiod_id_
	 * @param $pData
	 * @return $arrPayStub or $retval
	 */
	function doSaveGeneReport($payperiod_id_ = null, $pData){
		$objClsMngeDecimal = new Application();
		$retval = "";
		IF (is_null($payperiod_id_ )){ return $retval; }
		IF (is_null($pData)){ return $retval; }
		$ctr = 0;
		do {$payStubSerialize = array();
			$paystub_id = $this->doSavePayStub($pData['chkAttend'][$ctr],$payperiod_id_); // Save Paystub
			$empinfo = "SELECT *,txcep.taxep_name,a.emp_id,a.comp_id, CONCAT(g.pi_fname,' ',UPPER(SUBSTRING(g.pi_mname,1,1)),'. ',g.pi_lname) as fullname, bank.bankiemp_acct_no, blist.banklist_name, DATE_FORMAT(f.payperiod_trans_date,'%d') as ppdTransDate, DATE_FORMAT(f.payperiod_start_date,'%d') as ppdStartDate 
							FROM emp_masterfile a
							JOIN salary_info b on (b.emp_id=a.emp_id)
							JOIN payroll_pps_user c on (c.emp_id = a.emp_id)
							JOIN payroll_pay_period_sched d on (d.pps_id=c.pps_id)
							JOIN payroll_comp pcal on (a.emp_id=pcal.emp_id)
							JOIN factor_rate e on (e.fr_id=pcal.fr_id)
							JOIN payroll_pay_period f on (f.pps_id=d.pps_id)
							JOIN emp_personal_info g on (g.pi_id=a.pi_id)
							JOIN company_info i on (i.comp_id=a.comp_id)
							LEFT JOIN app_wagerate wrate on (wrate.wrate_id=e.wrate_id)
							LEFT JOIN emp_position h on (h.post_id=a.post_id)
							LEFT JOIN app_userdept j on (j.ud_id=a.ud_id)
							LEFT JOIN emp_type z on (z.emptype_id=a.emptype_id)
							LEFT JOIN bank_infoemp bank on (bank.emp_id=a.emp_id)
							LEFT JOIN bank_list blist on blist.banklist_id=bank.banklist_id
							LEFT JOIN tax_excep txcep on (txcep.taxep_id=a.taxep_id) 
							WHERE a.emp_id='".$pData['chkAttend'][$ctr]."' and c.pps_id='".$_GET['ppsched']."' and f.payperiod_id='".$payperiod_id_."' and b.salaryinfo_isactive='1'";
			$rsResult = $this->conn->Execute($empinfo);
//		printa($rsResult->fields);
			// get the Basic Pay.
			//-------------------------------------------------------->>
			$BPDayRate = $this->getBPDayRate($pData['chkAttend'][$ctr],$rsResult->fields['salarytype_id'],$rsResult->fields['salaryinfo_basicrate'],$rsResult->fields['payperiod_id'],$rsResult->fields['salaryinfo_ecola']);
//			$BPDayRate = $this->getBPDayRate($pData['chkAttend'][$ctr],$rsResult->fields['salarytype_id'],$rsResult->fields['payperiod_id'],$rsResult->fields['salaryinfo_ecola']);
//			echo $rsResult->fields['salaryinfo_basicrate'];
//			printa($BPDayRate); exit;
			$rateperday[$ctr] = $BPDayRate['rateperday'];
			$rateperhour[$ctr] = $BPDayRate['rateperhour'];
			$ratepersec[$ctr] = $BPDayRate['ratepersec'];
			$varTotalRugalarTimeRate[$ctr] = $BPDayRate['PayPeriodBP'];
			$varActualDaysRender[$ctr] = $BPDayRate['nodays'];
			$MWR[$ctr] = $rsResult->fields['wrate_minwagerate'];
			
			//COLA
			$colaperday[$ctr] = $BPDayRate['colaperday'];
			$colaperhour[$ctr] = $BPDayRate['colaperhour'];
			$colapersec[$ctr] = $BPDayRate['colapersec'];
			$PayPeriodCOLA[$ctr] = $BPDayRate['PayPeriodCOLA'];
//			echo "==================Basic Rate===================<br>";
//			printa($BPDayRate);
//			echo $varTotalRugalarTimeRate[$ctr]." Basic Rate<br>";
//			echo $rateperhour[$ctr]." rate per Hours<br>";
//			echo $rateperday[$ctr]." rate per Day<br>";
//			echo $ratepersec[$ctr]." rate per second<br>";
//			echo $varActualDaysRender[$ctr]." # of days in cutoff<br>";
//			echo "==================COLA Rate==================<br>";
//			echo $colaperday[$ctr]." rate per day<br>";
//			echo $colaperhour[$ctr]." rate per hrs<br>";
//			echo $colapersec[$ctr]." rate per sec<br>";
//			echo $PayPeriodCOLA[$ctr]." COLA Rate<br>";
			//---------------------------END--------------------------<<
			
			// get total OT TIME
			//-------------------------------------------------------->>
			$otSettings = clsPayroll_Details::getGeneralSetup("Overtime Computation");
			$varData[$ctr]['totalOTtime'] = $this->getTotalOTByPayPeriod($pData['chkAttend'][$ctr],$payperiod_id_,$rsResult->fields['ot_id']);
			$totalRegtime = $varData[$ctr]['totalOTtime'];
			$varTotalallOT[$ctr] = 0;
			$TotalNonTaxOT[$ctr] = 0;
			for ($a=0;$a<count($totalRegtime);$a++) {
				if($otSettings['set_stat_type']==0){
					$varOTTotalrate_ = $totalRegtime[$a]['otr_factor'] * ($rateperhour[$ctr]);
				} else {
					$varOTTotalrate_ = $totalRegtime[$a]['otr_factor'] * ($rateperhour[$ctr]+$colaperhour[$ctr]);
				}
				$varOTTotal_ = ($totalRegtime[$a]['otrec_totalhrs'] * $varOTTotalrate_);
				$vartotalotrate = $varOTTotal_;
					$this->otDetail[$ctr][$a] = array(
						 "otrec_id"=>$totalRegtime[$a]['otrec_id']
						,"ot_name"=>$totalRegtime[$a]['otr_name']
						,"rate"=>$totalRegtime[$a]['otr_factor']
						,"totaltimehr"=>($totalRegtime[$a]['otrec_totalhrs'])
						,"ratephrs"=>$rateperhour[$ctr]
						,"rateperhr"=>$varOTTotalrate_
						,"otamount"=>Number_Format($vartotalotrate,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						,"ot_istax"=>$totalRegtime[$a]['ot_istax']
					);
				IF($totalRegtime[$a]['ot_istax']==0){				
					$TotalNonTaxOT[$ctr] += $varOTTotal_;
				}ELSE{
					$varTotalallOT[$ctr] += $varOTTotal_;
				}
				$this->updateOTrecord($totalRegtime[$a]['otrec_id'],$paystub_id,$vartotalotrate);// update OT Record
            }
//			printa ($this->otDetail[$ctr]);
			//---------------------------END--------------------------<<

			// get total TA Time
			//-------------------------------------------------------->> 
			$taSettings = clsPayroll_Details::getGeneralSetup("Leave Deduction");
			$varData[$ctr]['totalTAtime'] = $this->getTotalTAByPayPeriod($pData['chkAttend'][$ctr],$payperiod_id_);
			$totalTAtime = $varData[$ctr]['totalTAtime'];
			$varTotalallTA[$ctr] = 0;
			for ($a=0;$a<count($totalTAtime);$a++) {
				if($taSettings['set_stat_type']==0){
					($totalTAtime[$a]['tatbl_rate']=='2')?$varTArate_ = $rateperday[$ctr] : $varTArate_ = $rateperhour[$ctr];//get the TA type: 2=Day, 1=hrs
				} else {
					($totalTAtime[$a]['tatbl_rate']=='2')?$varTArate_ = $rateperday[$ctr]+$colaperday[$ctr] : $varTArate_ = $rateperhour[$ctr]+$colaperhour[$ctr];//get the TA type: 2=Day, 1=hrs
				}
				($totalTAtime[$a]['tatbl_rate']=='2')?$varTAtype_ = 'D':$varTAtype_ = 'H';
				if($totalTAtime[$a]['tatbl_name']=='Custom Days'){$varTArate_ = 0;}
				$varTATotal_ = ($totalTAtime[$a]['emp_tarec_nohrday'] * $varTArate_);
				$vartotaltarate = $varTATotal_;
//				$a=$totalTAtime[$a]['tatbl_name'];
				$this->taDetail[$ctr][$a] = array(
								 "emp_tarec_id"=>$totalTAtime[$a]['emp_tarec_id']
								,"ta_name"=>$totalTAtime[$a]['tatbl_name']
								,"type"=>$totalTAtime[$a]['tatbl_rate']
								,"totaltimehr"=>($totalTAtime[$a]['emp_tarec_nohrday'])
								,"ratetype"=>$varTAtype_
								,"rateperDH"=>$varTArate_
								,"taamount"=>$vartotaltarate
							);
				$varTotalallTA[$ctr] += Number_Format($varTATotal_,$objClsMngeDecimal->getFinalDecimalSettings(),'.','');
				$this->updateTArecord($totalTAtime[$a]['emp_tarec_id'],$paystub_id,$vartotaltarate);// update TA record
            }
//            printa ($this->taDetail[$ctr]);
			//----------------------------END-------------------------<<
			
            // get Leave Records
			//-------------------------------------------------------->> 
			$LeaveRecords = $this->getLeaveRecord($pData['chkAttend'][$ctr],$rateperday[$ctr]);
			// added for leave conversion
			$totalTSEarningLeave[$ctr] = 0;
			$totalSEarningLeave[$ctr] = 0;
			$totalTEarningLeave[$ctr] = 0;
			$totalNTSEarningLeave[$ctr] = 0;
			$convert_leave = array();
			IF($rsResult->fields['convert_leave']){
				FOR($leavecount=0;count($LeaveRecords)>$leavecount;$leavecount++){
					IF($LeaveRecords[$leavecount]['leave_conv_cash'] == 'Yes'){
						$psa_leave = $this->savePayElement($LeaveRecords[$leavecount]['leave_name']." Conversion");
						$convertedLeave = $LeaveRecords[$leavecount]['empleave_available_day']*$rateperday[$ctr];
						if($psa_leave['psa_statutory'] == 1){
							if($psa_leave['psa_tax'] == 1){
								$totalTSEarningLeave[$ctr] += $convertedLeave;   //subject to Tax & Statutory
							}else{
								$totalSEarningLeave[$ctr] += $convertedLeave;    //subject to Statutory
							}
						}else{
							if($psa_leave['psa_tax'] == 1){
								$totalTEarningLeave[$ctr] += $convertedLeave;    //subject to Tax 
							}else{
								$totalNTSEarningLeave[$ctr] += $convertedLeave;  //Non Tax & Statutory
							}
						}
						$convert_leave[] = array(
							"psa_id" => $psa_leave['psa_id']
							,"psa_name" => $psa_leave['psa_name']
							,"effective_date" => date("Y-m-d", strtotime($rsResult->fields['payperiod_start_date']))
							,"amount" => $convertedLeave
						);
						$this->doSavePayStubEntry($paystub_id,$psa_leave['psa_id'], $convertedLeave);
					}
				}
			} //printa($LeaveRecords);exit;
			// end of leave conversion
//			printa($LeaveRecords); exit;
			//----------------------------END-------------------------<<
			
			// Call the getAmendments function
			//-------------------------------------------------------->>
			$amendments = $this->getAmendments($pData['chkAttend'][$ctr],$rsResult->fields['payperiod_trans_date'],$rsResult->fields['payperiod_start_date'],$rsResult->fields['payperiod_end_date'],$paystub_id,$payperiod_id_);
			$totalTSEarningAmendments[$ctr] = 0;   // Earning subject to Tax & Statutory
			$totalTEarningAmendments[$ctr] = 0;    // Earning subject to Tax
			$totalSEarningAmendments[$ctr] = 0;    // Earning subject to Statutory
			$totalNTSEarningAmendments[$ctr] = 0;  // Earning Non Tax & Statutory
			$totalTSDeductionAmendments[$ctr] = 0; // Deduction subject to Tax & Statutory
			$totalTDeductionAmendments[$ctr] = 0;  // Deduction subject to Tax
			$totalSDeductionAmendments[$ctr] = 0;  // Deduction subject to Statutory
			$totalNTSDeductionAmendments[$ctr] = 0;// Deduction Non Tax & Statutory
			//sum up the amount of Amendments
			if (count($amendments)>0) {
				foreach ($amendments as $keyamend => $valamend){
//				$this->doSavePayStubEntry($paystub_id,$amendments[$r]['psa_id'], $amendments[$r]['psamend_amount'], $amendments[$r]['psamend_rate'],$amendments[$r]['psamend_unit']);
					if ($valamend['psa_type']==1) { // Earning
						if($valamend['psa_statutory'] == 1){
							if($valamend['psa_tax'] == 1){
								$totalTSEarningAmendments[$ctr] += $valamend['amendemp_amount'];   //subject to Tax & Statutory
							}else{
								$totalSEarningAmendments[$ctr] += $valamend['amendemp_amount'];    //subject to Statutory
							}
						}else{
							if($valamend['psa_tax'] == 1){
								$totalTEarningAmendments[$ctr] += $valamend['amendemp_amount'];    //subject to Tax 
							}else{
								$totalNTSEarningAmendments[$ctr] += $valamend['amendemp_amount'];  //Non Tax & Statutory
							}
						}
					} else { // Deduction
						if($valamend['psa_statutory'] == 1){
							if($valamend['psa_tax'] == 1){
								$totalTSDeductionAmendments[$ctr] += $valamend['amendemp_amount']; //subject to Tax & Statutory
							}else{
								$totalSDeductionAmendments[$ctr] += $valamend['amendemp_amount'];  //subject to Statutory
							}
						}else{
							if($valamend['psa_tax'] == 1){
								$totalTDeductionAmendments[$ctr] += $valamend['amendemp_amount'];  //subject to Tax 
							}else{
								$totalNTSDeductionAmendments[$ctr] += $valamend['amendemp_amount'];//Non Tax & Statutory
							}
						}
					}
				}
			}
//			echo "<br><br>==================Amendments===================<br>";
//			printa($amendments);
//			echo $totalTSEarningAmendments[$ctr]." E Tax & Stat<br>";
//			echo $totalTEarningAmendments[$ctr]." E Tax<br>";
//			echo $totalSEarningAmendments[$ctr]." E Stat<br>";
//			echo $totalNTSEarningAmendments[$ctr]." E Non Tax & Stat<br>";
//			echo $totalTSDeductionAmendments[$ctr]." D Tax & Stat<br>";
//			echo $totalTDeductionAmendments[$ctr]." D Tax<br>";
//			echo $totalSDeductionAmendments[$ctr]." D Stat<br>";
//			echo $totalNTSDeductionAmendments[$ctr]." D Non Tax & Stat<br>";exit;
			//----------------------------END-------------------------<<

			// get employee benefits & Deduction
			//-------------------------------------------------------->>
			$EmployeeBenefits[$ctr] = $this->dbFetchEmployeeBenefits($pData['chkAttend'][$ctr],$rsResult->fields['payperiod_trans_date'],$rsResult->fields['payperiod_start_date'],$rsResult->fields['payperiod_end_date'],"",$rsResult->fields['payperiod_freq']);
			$totalTSEarningBenefits[$ctr] = 0;   // Earning subject to Tax & Statutory
			$totalTEarningBenefits[$ctr] = 0;    // Earning subject to Tax
			$totalSEarningBenefits[$ctr] = 0;    // Earning subject to Statutory
			$totalNTSEarningBenefits[$ctr] = 0;  // Earning Non Tax & Statutory
			$totalTSDeductionBenefits[$ctr] = 0; // Deduction subject to Tax & Statutory
			$totalTDeductionBenefits[$ctr] = 0;  // Deduction subject to Tax
			$totalSDeductionBenefits[$ctr] = 0;  // Deduction subject to Statutory
			$totalNTSDeductionBenefits[$ctr] = 0;// Deduction Non Tax & Statutory
			//sum up the amount of benefits
			//printa($EmployeeBenefits[$ctr]); exit;
			$this->doSaveTempPayStub($paystub_id, 1, $BPDayRate['PayPeriodBP']);          //save Basic computed base to salary type and pay group to payroll_paystub_entry table
			$this->doSaveTempPayStub($paystub_id, -1, $BPDayRate['rateperday']);		//save Rate per Day
			$this->doSaveTempPayStub($paystub_id, -2, $BPDayRate['rateperhour']);
			
			$ppStartDate = new DateTime($rsResult->fields['payperiod_start_date']);
			$ppEndDate = new DateTime($rsResult->fields['payperiod_end_date']);
			
			if (count($EmployeeBenefits[$ctr])>0) {
				foreach ($EmployeeBenefits[$ctr] as $keyben => $valben){
				//validate if pay element is formula-based
					if(((strtotime($valben['ben_startdate']) > strtotime($ppStartDate->format("Y-m-d"))) && (strtotime($valben['ben_startdate']) <= strtotime($ppEndDate->format("Y-m-d")))) || ((strtotime($rsResult->fields['emp_resigndate']) >= strtotime($ppStartDate->format("Y-m-d"))) && (strtotime($rsResult->fields['emp_resigndate']) < strtotime($ppEndDate->format("Y-m-d"))))){
						// pro-rated recurring
						$recur = $this->getBPDayRate($pData['chkAttend'][$ctr],$rsResult->fields['salarytype_id'],$v = $valben['ben_amount'],$rsResult->fields['payperiod_id'],0,$valben);
						$v = $recur['PayPeriodBP'];
						$valben['ben_payperday'] = $v;
					} else {
						if($this->validateFormulaExists($valben['psa_id'], $pData['chkAttend'][$ctr])){
							$v = $this->doGetFormulaVal($pData['chkAttend'][$ctr], $valben['psa_id'], $paystub_id, $payperiod_id_);
						} else {
							$v = $valben['ben_payperday'];
						}
					}
					$this->doSavePayStubEntry($paystub_id,$valben['psa_id'], $v);
					if ($valben['psa_type']==1) { // Earning
						if($valben['psa_statutory'] == 1){
							if($valben['psa_tax'] == 1){
								$totalTSEarningBenefits[$ctr] += $v;   //subject to Tax & Statutory
							}else{
								$totalSEarningBenefits[$ctr] += $v;    //subject to Statutory
							}
						}else{
							if($valben['psa_tax'] == 1){
								$totalTEarningBenefits[$ctr] += $v;    //subject to Tax 
							}else{
								$totalNTSEarningBenefits[$ctr] += $v;  //Non Tax & Statutory
							}
						}
					} else { // deduction
						if($valben['psa_statutory'] == 1){
							if($valben['psa_tax'] == 1){
								$totalTSDeductionBenefits[$ctr] += $v; //subject to Tax & Statutory
							}else{
								$totalSDeductionBenefits[$ctr] += $v;  //subject to Statutory
							} 
						}else{
							if($valben['psa_tax'] == 1){
								$totalTDeductionBenefits[$ctr] += $v;  //subject to Tax 
							}else{
								$totalNTSDeductionBenefits[$ctr] += $v;//Non Tax & Statutory
							}
						}
					}
					if($valben['ben_isfixed']){
						$payStubSerialize[] = array(
											"psa_id" => $valben['psa_id'],
											"ben_id" => $valben['ben_id'],
								            "psa_name" => $valben['psa_name'],
								            "psa_type" => $valben['psa_type'],
								            "ben_amount" => $valben['ben_amount'],
								            "ben_payperday" => $v,
								            "ben_startdate" => $valben['ben_startdate'],
								            "ben_enddate" => $valben['ben_enddate'],
								            "ben_isfixed" => $valben['ben_isfixed'],
								            "ben_suspend" => $valben['ben_suspend'],
								            "ben_periodselection" => $valben['ben_periodselection'],
								            "psa_priority" => $valben['psa_priority'],
								            "psa_tax" => $valben['psa_tax'],
								            "psa_statutory" => $valben['psa_statutory'],
								            "year_" => $valben['year_'],
								            "month_" => $valben['month_'],
								            "yearend_" => $valben['yearend_'],
								            "monthend_" => $valben['monthend_']
										);
					} else {
						$payStubSerialize[] = $valben;
					}
				}
			}
//			printa($payStubSerialize); exit;
//			echo "====================Benefits====================<br>";
//			printa($EmployeeBenefits[$ctr]);
//			echo $totalTSEarningBenefits[$ctr]." E Tax & Stat<br>";
//			echo $totalTEarningBenefits[$ctr]." E Tax<br>";
//			echo $totalSEarningBenefits[$ctr]." E Stat<br>";
//			echo $totalNTSEarningBenefits[$ctr]." E Non Tax & Stat<br>";
//			echo $totalTSDeductionBenefits[$ctr]." D Tax & Stat<br>";
//			echo $totalTDeductionBenefits[$ctr]." D Tax<br>";
//			echo $totalSDeductionBenefits[$ctr]." D Stat<br>";
//			echo $totalNTSDeductionBenefits[$ctr]." D Non Tax & Stat<br>";
			//----------------------------END-------------------------<<
			//Sum all Payelements
			//-------------------------------------------------------->>
			$SumTSABEarning = $totalTSEarningLeave[$ctr] + $totalTSEarningAmendments[$ctr] + $totalTSEarningBenefits[$ctr];         //TS Earning
			$SumTABEarning = $totalTEarningLeave[$ctr] + $totalTEarningAmendments[$ctr] + $totalTEarningBenefits[$ctr];			   //T Earning
			$SumSABEarning = $totalSEarningLeave[$ctr] + $totalSEarningAmendments[$ctr] + $totalSEarningBenefits[$ctr];			   //S Earning
			$SumNonABEarning = $totalNTSEarningLeave[$ctr] + $totalNTSEarningAmendments[$ctr] + $totalNTSEarningBenefits[$ctr];   //Non TS Earning
			$SumALLABEarning = $SumTSABEarning + $SumTABEarning + $SumSABEarning + $SumNonABEarning;   //SUM all AB Earning
			
			$SumTSABDeduction = $totalTSDeductionAmendments[$ctr] + $totalTSDeductionBenefits[$ctr];   //TS Deduction
			$SumTABDeduction = $totalTDeductionAmendments[$ctr] + $totalTDeductionBenefits[$ctr];	   //T Deduction
			$SumSABDeduction = $totalSDeductionAmendments[$ctr] + $totalSDeductionBenefits[$ctr];      //S Deduction
			$SumNonABDeduction = $totalNTSDeductionAmendments[$ctr] + $totalNTSDeductionBenefits[$ctr];//Non TS Deduction
			$SumALLABDeduction = $SumTSABDeduction + $SumTABDeduction + $SumSABDeduction + $SumNonABDeduction;//SUM all AB Deduction
			
			$TotalTSSDeduction = $SumTSABDeduction + $SumSABDeduction;
			//----------------------------END-------------------------<<

			// Amount Computed base in Basic Pay.
			//-------------------------------------------------------->>
			$varSumAllOTRate=0;
			$SumNonTaxOTRate=0;
			$basicpay_rate = $rsResult->fields['salaryinfo_basicrate'];													  //BasicPay Rate
			$BPperperiod = $varTotalRugalarTimeRate[$ctr]; 																  //Basic computed base to salary type and pay group
			$COLAperperiod = $PayPeriodCOLA[$ctr];	
			IF($totalRegtime[0]['ot_istax']==1){						  						      			      //COLA computed base to salary type and pay group
				$varSumAllOTRate = Number_Format($varTotalallOT[$ctr],$objClsMngeDecimal->getFinalDecimalSettings(),'.','');  //sum of all OT
			}ELSE{
				$SumNonTaxOTRate = Number_Format($TotalNonTaxOT[$ctr],$objClsMngeDecimal->getFinalDecimalSettings(),'.','');  //sum of all Non-TAX OT	
			}
			$varSumAllTARate = Number_Format($varTotalallTA[$ctr],$objClsMngeDecimal->getFinalDecimalSettings(),'.','');  //sum of all TA
			$basicgrosspg = ($BPperperiod + $varSumAllOTRate + $COLAperperiod) - $varSumAllTARate; 				  		  //(Basic Pay + OT + COLA)- TA
			$basicPG_ABS = ($basicgrosspg + $SumSABEarning + $SumTSABEarning) - ($SumTSABDeduction + $SumSABDeduction);	  //Add AB Earning and Less AB Deduction subject to Stat 
			$basicPG_nostat = ($basicgrosspg + $SumTSABEarning + $SumTABEarning) - ($SumTABDeduction + $SumTSABDeduction);//Sum all Earning and Deduction that subject to tax.
			$nontaxableGross = $SumNonABEarning; 																	      //Sum all non AB Earning
			$other_nontaxdeduction = $SumNonABDeduction;															 	  //Sum all non AB Deduction
			$SumAllEarning = $BPperperiod + $varSumAllOTRate + $SumNonTaxOTRate + $SumALLABEarning + $PayPeriodCOLA[$ctr];
//			echo "<br>================== Summary ===================<br>";
//			echo $varTotalRugalarTimeRate[$ctr]. ' Basic Pay<br>';
//			echo $varSumAllOTRate.' Total OT<br>';
//			echo $varSumAllTARate.' Total LUA<br>';
//			echo $basicgrosspg.' (BasicPay+OT)-LUA<br>';
//			echo $basicPG_ABS.' Basic AB Stat<br>';
//			echo $nontaxableGross.' Non-TaxGross<br>';
//			echo $other_nontaxdeduction.' Non-TaxDeduction<br>';
			
            if($emp_id_ == ""){
            	$this->doSavePayStubEntry($paystub_id, 1, $BPperperiod);          //save Basic computed base to salary type and pay group to payroll_paystub_entry table
				$this->doSavePayStubEntry($paystub_id, 4, $basicgrosspg);		  //save Total Grosspay to payroll_paystub_entry table
            	$this->doSavePayStubEntry($paystub_id,16, $varSumAllOTRate);	  //save Total OT to payroll_paystub_entry table
				$this->doSavePayStubEntry($paystub_id,17, $varSumAllTARate);	  //save Total TA to payroll_paystub_entry table
				$this->doSavePayStubEntry($paystub_id,25, $SumSABEarning);		  //save Total S Earning to payroll_paystub_entry table
				$this->doSavePayStubEntry($paystub_id,26, $SumTSABEarning);		  //save Total TS Earning to payroll_paystub_entry table
				$this->doSavePayStubEntry($paystub_id,28, $SumTABEarning);		  //save Total T Earning to payroll_paystub_entry table
				$this->doSavePayStubEntry($paystub_id,29, $SumTABDeduction);	  //save Total T Deduction to payroll_paystub_entry table
				$this->doSavePayStubEntry($paystub_id,33, $SumSABDeduction);	  //save Total S Deduction to payroll_paystub_entry table
				$this->doSavePayStubEntry($paystub_id,34, $SumTSABDeduction);	  //save Total ST Deduction to payroll_paystub_entry table
            	$this->doSavePayStubEntry($paystub_id,39, $COLAperperiod);        //save Total COLA computed base to salary type and pay group to payroll_paystub_entry table
            }
			//----------------------------END-------------------------<<
			// Statutory deduction
				$varData[$ctr]['typeDeduc'] = $this->getTypeDeduction();
				$dectype = $varData[$ctr]['typeDeduc'];
//				printa($dectype);
							
				/**
				 * modified by rblising
				 * wag muna ideduct ang tax
				 * deduct it after the sss,phic,hdmf, and others are duducted
				 */
				for ($b=0;$b<count($dectype);$b++){
					$qry = array();
					$qry[] = "a.sc_id = '".$dectype[$b]['GenSetup']['set_decimal_places']."'";
					$qry[] = "a.dec_id = '".$dectype[$b]['dec_id']."'";
					$criteria = count($qry)>0 ? " WHERE ".implode(' and ',$qry) : '';
					$sql = "SELECT * FROM statutory_contribution a $criteria";
					$varDeducH = $this->conn->Execute($sql);
					if(!$varDeducH->EOF){
						$varHdeduc = $varDeducH->fields;
					}
//					printa($varHdeduc);exit;
					$transdateSched = $rsResult->fields['ppdTransDate'];
					$startdateSched = $rsResult->fields['ppdStartDate'];
					$schedSecond = $rsResult->fields['pps_secnd_trans_daymonth'];
//					echo "<BR>".$transdateSched." transdateSched<br>"; echo $startdateSched." startdateSched<br>"; echo $schedSecond." schedSecond<br>";

					//@todo: check for 1st & 2nd Half
					$varDeductionSched = $this->getDeductionSched($pData['chkAttend'][$ctr],$dectype[$b]['dec_id']);
					//printa($varDeductionSched).'<br>'; echo count($varDeductionSched).'<br>'; exit;
					switch ($dectype[$b]['dec_id']) {
						//@Aut:jim(20120817)
						//@note: ADJUST getMonthlyRecordStat add $rsResult->fields['payperiod_period'] to correct computation of STAT.
						case 1:
							IF(count($varDeductionSched)=='1'){
								IF($rsResult->fields['salaryclass_id']=='5'){//FOR MONTHLY PG SSS COMPUTATION
									IF($varDeductionSched[0]['bldsched_period']=='0'){
										$varDecSSS = 0;
			                            $varDecSSSEC = 0;
			                            $er[$ctr] = 0;
			                            $varDecSSSER = 0;
									}ELSE{
										$schedSecond = '1';
										$varPPD_ = $this->getMonthlyRecordStat($pData['chkAttend'][$ctr],'7',$rsResult->fields['payperiod_period'],$rsResult->fields['payperiod_period_year'],$rsResult->fields['payperiod_id'],1);
										IF($dectype[$b]['GenSetup']['set_stat_type'] == 1){
											//SSS BASE ON GROSS
											$totalSSSgross = $basicPG_ABS + $varPPD_['ppe_rate'];
										}ELSE{
											//SSS BASE ON BASIC
											$totalSSSgross = $BPperperiod;
										}
										$varDec = $this->getTotalDeductionByPayPeriod($pData['chkAttend'][$ctr],$varHdeduc['sc_id'],$dectype[$b]['dec_id'],$totalSSSgross,$rsResult->fields['taxep_id']);
					                    $varDecSSS = $varDec['scr_ee'] - $varPPD_['ppe_amount'];
					                    $er[$ctr] = $varDec['scr_er'] - $varPPD_['ppe_amount_employer'];
					                    $varDecSSSER = $varDec['scr_er'] - $varPPD_['ppe_amount_employer'];
					                    $varDecSSSEC = $varDec['scr_ec'] - $varPPD_['ppe_units'];
									}
								}ELSEIF($rsResult->fields['salaryclass_id']=='4'){//FOR SEMI-MONTHLY PG SSS COMPUTATION
									IF($varDeductionSched[0]['bldsched_period']=='0' OR $varDeductionSched[0]['bldsched_period']=='1'){
											$varDecSSS = 0;
				                            $varDecSSSEC = 0;
				                            $er[$ctr] = 0;
				                            $varDecSSSER = 0;
									}ELSE{
										IF($rsResult->fields['payperiod_freq']=='2'){
											$schedSecond = '1';
											$varPPD_ = $this->getMonthlyRecordStat($pData['chkAttend'][$ctr],'7',$rsResult->fields['payperiod_period'],$rsResult->fields['payperiod_period_year'],$rsResult->fields['payperiod_id'],1);
											IF($dectype[$b]['GenSetup']['set_stat_type'] == 1){
												//SSS BASE ON GROSS
												$totalSSSgross = $basicPG_ABS + $varPPD_['ppe_rate'];
											}ELSE{
												//SSS BASE ON BASIC
												$totalSSSgross = $BPperperiod + $varPPD_['ppe_rate'];
											}
											$varDec = $this->getTotalDeductionByPayPeriod($pData['chkAttend'][$ctr],$varHdeduc['sc_id'],$dectype[$b]['dec_id'],$totalSSSgross,$rsResult->fields['taxep_id']);
						                    $varDecSSS = $varDec['scr_ee'];
						                    $er[$ctr] = $varDec['scr_er'];
						                    $varDecSSSER = $varDec['scr_er'];
						                    $varDecSSSEC = $varDec['scr_ec'];
										}else{
											$totalSSSgross = $basicPG_ABS;
											$varDecSSS = 0;
				                            $varDecSSSEC = 0;
				                            $er[$ctr] = 0;
				                            $varDecSSSER = 0;
										}
									}
								}
							}ELSE{
								IF($rsResult->fields['payperiod_freq']>='2' AND !(strtotime($rsResult->fields['emp_hiredate']) >= strtotime($rsResult->fields['payperiod_start_date'])) && (strtotime($rsResult->fields['emp_hiredate']) <= strtotime($rsResult->fields['payperiod_end_date']))){
									$schedSecond = '1';
									$varPPD_ = $this->getMonthlyRecordStat($pData['chkAttend'][$ctr],'7',$rsResult->fields['payperiod_period'],$rsResult->fields['payperiod_period_year'],$rsResult->fields['payperiod_id'],1);
									IF($dectype[$b]['GenSetup']['set_stat_type'] == 1){
										//SSS BASE ON GROSS
										$totalSSSgross = $basicPG_ABS + $varPPD_['ppe_rate'];
									}ELSE{
										//SSS BASE ON BASIC
										$totalSSSgross = $BPperperiod + $varPPD_['ppe_rate'];
									}
									//echo $basicPG_ABS."<br>";
									//echo $varPPD_['ppe_rate']."<br>";
									//echo $totalSSSgross; exit;
									$varDec = $this->getTotalDeductionByPayPeriod($pData['chkAttend'][$ctr],$varHdeduc['sc_id'],$dectype[$b]['dec_id'],$totalSSSgross,$rsResult->fields['taxep_id']);
				                    $varDecSSS = $varDec['scr_ee'] - $varPPD_['ppe_amount'];
				                    $er[$ctr] = $varDec['scr_er'] - $varPPD_['ppe_amount_employer'];
				                    $varDecSSSER = $varDec['scr_er'] - $varPPD_['ppe_amount_employer'];
				                    $varDecSSSEC = $varDec['scr_ec'] - $varPPD_['ppe_units'];
								}ELSE{
									$schedSecond = '0';
									$totalSSSgross = $basicPG_ABS;
									$varDec = $this->getTotalDeductionByPayPeriod($pData['chkAttend'][$ctr],$varHdeduc['sc_id'],$dectype[$b]['dec_id'],$totalSSSgross,$rsResult->fields['taxep_id']);
			                        IF($rsResult->fields['salaryclass_id'] == 5){
			                            $varDecSSS = $varDec['dd_phic_sss_ee']/2;
			                            $varDecSSSEC = $varDec['dd_phic_sss_ec']/2;
			                            $er[$ctr] = $varDec['dd_phic_sss_er']/2;
			                            $varDecSSSER = $varDec['dd_phic_sss_er']/2;
			                        }ELSE{
			                            $varDecSSS = $varDec['scr_ee'];
			                            $varDecSSSEC = $varDec['scr_ec'];
			                            $er[$ctr] = $varDec['scr_er'];
			                            $varDecSSSER = $varDec['scr_er'];
			                        }
								}
							}
							$totalDeDuction[$ctr] += $varDecSSS;
							$psaSSS = "SSS";
							if($emp_id_ == ""){
	                            $this->doSavePayStubEntry($paystub_id, 7, $varDecSSS,$er[$ctr], $totalSSSgross,$varDecSSSEC);
	                        }
//	                        echo "================== SSS STAT ==================<br>";
//	                        echo $schedSecond." schedSecond <br>";
//		                    echo $totalSSSgross." total SSS GROSS <br>";
//		                    echo $varDecSSS." Total employee deduction <br>";
//		                    echo $er[$ctr]." Total Employer Deduction <br>";
//		                    echo $varDecSSSER." Total Employer Deduction <br>";
//		                    echo $varDecSSSEC." ECC <br>";
							break;
						case 2:
							if (count($varDeductionSched)=='1') {
								if ($rsResult->fields['salaryclass_id']=='5') {//FOR MONTHLY PG PHIC COMPUTATION
									IF ($varDeductionSched[0]['bldsched_period']=='0') {
										$varDecPHIL = 0;
			                            $er[$ctr] = 0;
			                            $varDecPHILER = 0;
			                            $varDecPHILEC = 0;
									} ELSE {
										$varPPD_ = $this->getMonthlyRecordStat($pData['chkAttend'][$ctr],'14',$rsResult->fields['payperiod_period'],$rsResult->fields['payperiod_period_year'],$rsResult->fields['payperiod_id'],1);
										IF($dectype[$b]['GenSetup']['set_stat_type'] == 1){
											//PHIC BASE ON GROSS
											$totalPhilgross = $basicPG_ABS + $varPPD_['ppe_rate'];
										}ELSE{
											//PHIC BASE ON BASIC
											$totalPhilgross = $BPperperiod;
										}
										$varDec = $this->getTotalDeductionByPayPeriod($pData['chkAttend'][$ctr],$varHdeduc['sc_id'],$dectype[$b]['dec_id'],$totalPhilgross,$rsResult->fields['taxep_id']);
					                    $varDecPHIL = $varDec['scr_ee'] - $varPPD_['ppe_amount'];
					                    $er[$ctr] = $varDec['scr_er'] - $varPPD_['ppe_amount_employer'];
					                    $varDecPHILER = $varDec['scr_er'] - $varPPD_['ppe_amount_employer'];
					                    $varDecPHILEC = $varDec['scr_ec'];
									}
								} elseif ($rsResult->fields['salaryclass_id']=='4') {//FOR SEMI-MONTHLY PG PHIC COMPUTATION
									if ($varDeductionSched[0]['bldsched_period']=='0' OR $varDeductionSched[0]['bldsched_period']=='1') {
											$varDecPHIL = 0;
				                            $er[$ctr] = 0;
				                            $varDecPHILER = 0;
				                            $varDecPHILEC = 0;
									} else {
										if ($rsResult->fields['payperiod_freq']=='2') {
											$varPPD_ = $this->getMonthlyRecordStat($pData['chkAttend'][$ctr],'14',$rsResult->fields['payperiod_period'],$rsResult->fields['payperiod_period_year'],$rsResult->fields['payperiod_id'],1);
											IF($dectype[$b]['GenSetup']['set_stat_type'] == 1){
												//PHIC BASE ON GROSS
												$totalPhilgross = $basicPG_ABS + $varPPD_['ppe_rate'];
											}ELSE{
												//PHIC BASE ON BASIC
												$totalPhilgross = $BPperperiod + $varPPD_['ppe_rate'];
											}
											//echo $totalPhilgross; echo '<br>'.$BPperperiod.'<br>';  echo '<br>'.$varPPD_['ppe_rate'].'<br>';exit;
											$varDec = $this->getTotalDeductionByPayPeriod($pData['chkAttend'][$ctr],$varHdeduc['sc_id'],$dectype[$b]['dec_id'],$totalPhilgross,$rsResult->fields['taxep_id']);
						                    $varDecPHIL = $varDec['scr_ee'];
						                    $er[$ctr] = $varDec['scr_er'];
						                    $varDecPHILER = $varDec['scr_er'];
						                    $varDecPHILEC = $varDec['scr_ec'];
										} ELSE {
											$totalPhilgross = $basicPG_ABS;
											$varDecPHIL = 0;
				                            $er[$ctr] = 0;
				                            $varDecPHILER = 0;
				                            $varDecPHILEC = 0;
										}
									}
								}
							} ELSE  {
								if ($rsResult->fields['payperiod_freq']>='2' AND !(strtotime($rsResult->fields['emp_hiredate']) >= strtotime($rsResult->fields['payperiod_start_date'])) && (strtotime($rsResult->fields['emp_hiredate']) <= strtotime($rsResult->fields['payperiod_end_date']))) {
									$varPPD_ = $this->getMonthlyRecordStat($pData['chkAttend'][$ctr],'14',$rsResult->fields['payperiod_period'],$rsResult->fields['payperiod_period_year'],$rsResult->fields['payperiod_id'],1);
									IF($dectype[$b]['GenSetup']['set_stat_type'] == 1){
										//PHIC BASE ON GROSS
										$totalPhilgross = $basicPG_ABS + $varPPD_['ppe_rate'];
									}ELSE{
										//PHIC BASE ON BASIC
										$totalPhilgross = $BPperperiod + $varPPD_['ppe_rate'];
									}
									//echo $totalPhilgross; echo '<br>'.$BPperperiod.'<br>';  echo '<br>'.$varPPD_['ppe_rate'].'<br>';exit;
									$varDec = $this->getTotalDeductionByPayPeriod($pData['chkAttend'][$ctr],$varHdeduc['sc_id'],$dectype[$b]['dec_id'],$totalPhilgross,$rsResult->fields['taxep_id']);
				                    $varDecPHIL = $varDec['scr_ee'] - $varPPD_['ppe_amount'];
				                    $er[$ctr] = $varDec['scr_er'] - $varPPD_['ppe_amount_employer'];
				                    $varDecPHILER = $varDec['scr_er'] - $varPPD_['ppe_amount_employer'];
				                    $varDecPHILEC = $varDec['scr_ec'];
								} else {
									$totalPhilgross = $BPperperiod;
									$varDec = $this->getTotalDeductionByPayPeriod($pData['chkAttend'][$ctr],$varHdeduc['sc_id'],$dectype[$b]['dec_id'],$totalPhilgross,$rsResult->fields['taxep_id']);
			                        if ($rsResult->fields['salaryclass_id'] == 5) {
			                            $varDecPHIL = $varDec['dd_phic_sss_ee']/2;
			                            $er[$ctr] = $varDec['dd_phic_sss_er']/2;
			                            $varDecPHILER = $varDec['dd_phic_sss_er']/2;
			                            $varDecPHILEC = $varDec['scr_ec'];
			                        } else {
			                            $varDecPHIL = $varDec['scr_ee'];
			                            $er[$ctr] = $varDec['scr_er'];
			                            $varDecPHILER = $varDec['scr_er'];
			                            $varDecPHILEC = $varDec['scr_ec'];
			                        }
								}
							}
							$totalDeDuction[$ctr] += $varDecPHIL;
							$psaPhil = "PHIC";
							if ($emp_id_ == "") {
	                            $this->doSavePayStubEntry($paystub_id, 14, $varDecPHIL,$er[$ctr], $totalPhilgross,0);
	                        }
//	                        echo "================== PHIC STAT ================<br>";
//		                    echo $totalPhilgross." total PHIC GROSS <br>";
//		                    echo $varDecPHIL." Total employee deduction <br>";
//		                    echo $er[$ctr]." Total Employer Deduction <br>";
//		                    echo $varDecPHILER." Total Employer Deduction <br>";
//		                    echo $varDecPHILEC." MSB <br>";
							break;
						case 3:
							if (count($varDeductionSched)=='1') {
								if ($rsResult->fields['salaryclass_id']=='5') {//FOR MONTHLY PG HDMF COMPUTATION
									if ($varDeductionSched[0]['bldsched_period']=='0') {
										$varDecPagibig = 0;
			                            $varDecPagibigEmployer = 0;
									} else {
										$varPPD_ = $this->getMonthlyRecordStat($pData['chkAttend'][$ctr],'15',$rsResult->fields['payperiod_period'],$rsResult->fields['payperiod_period_year'],$rsResult->fields['payperiod_id'],1);
										$totalHDMFgross = $varPPD_['ppe_rate'] + $basicPG_ABS;
										$varDec = $this->getTotalDeductionByPayPeriod($pData['chkAttend'][$ctr],$varHdeduc['sc_id'],$dectype[$b]['dec_id'],$totalHDMFgross,$rsResult->fields['taxep_id']);
										$varDecPagibig = $varDec['scr_ee'] - $varPPD_['ppe_amount'];
					                    $varDecPagibigEmployer = $varDec['scr_er'] - $varPPD_['ppe_amount_employer'];
									}
								} elseif ($rsResult->fields['salaryclass_id']=='4') {//FOR SEMI-MONTHLY PG HDMF COMPUTATION
									if ($varDeductionSched[0]['bldsched_period']=='0' || $varDeductionSched[0]['bldsched_period']=='1') {
											$varDecPagibig = 0;
			                            	$varDecPagibigEmployer = 0;
									} else {
										if ($rsResult->fields['payperiod_freq']=='2') {
											$varPPD_ = $this->getMonthlyRecordStat($pData['chkAttend'][$ctr],'15',$rsResult->fields['payperiod_period'],$rsResult->fields['payperiod_period_year'],$rsResult->fields['payperiod_id'],1);
											$totalHDMFgross = $varPPD_['ppe_rate'] + $basicPG_ABS;
											$varDec = $this->getTotalDeductionByPayPeriod($pData['chkAttend'][$ctr],$varHdeduc['sc_id'],$dectype[$b]['dec_id'],$totalHDMFgross,$rsResult->fields['taxep_id']);
											$varDecPagibig = $varDec['scr_ee'];
						                    $varDecPagibigEmployer = $varDec['scr_er'];
										} else {
											$totalHDMFgross = $basicPG_ABS;
											$varDecPagibig = 0;
			                            	$varDecPagibigEmployer = 0;
										}
									}
								}
							} else {
								if ($rsResult->fields['payperiod_freq']>='2') {
									$varPPD_ = $this->getMonthlyRecordStat($pData['chkAttend'][$ctr],'15',$rsResult->fields['payperiod_period'],$rsResult->fields['payperiod_period_year'],$rsResult->fields['payperiod_id'],1);
									$totalHDMFgross = $varPPD_['ppe_rate'] + $basicPG_ABS;
									$varDec = $this->getTotalDeductionByPayPeriod($pData['chkAttend'][$ctr],$varHdeduc['sc_id'],$dectype[$b]['dec_id'],$totalHDMFgross,$rsResult->fields['taxep_id']);
									$varDecPagibig = $varDec['scr_ee'] - $varPPD_['ppe_amount'];
				                    $varDecPagibigEmployer = $varDec['scr_er'] - $varPPD_['ppe_amount_employer'];
								} else {
									$totalHDMFgross = $basicPG_ABS;
									$varDec = $this->getTotalDeductionByPayPeriod($pData['chkAttend'][$ctr],$varHdeduc['sc_id'],$dectype[$b]['dec_id'],$totalHDMFgross,$rsResult->fields['taxep_id']);
			                        if ($varDec['dduct_isnominal'] == 10) {
			                            if ($rsResult->fields['salaryclass_id'] == 5) {
			                                $varDecPagibig = (($varDec['dd_phic_sss_ee']*$totalHDMFgross)/2);
			                                $varDecPagibigEmployer = ($varDec['dd_phic_sss_er']*$totalHDMFgross)/2;
			                            } else {
			                                $varDecPagibig = $varDec['dd_phic_sss_ee']*$totalHDMFgross;
			                                $varDecPagibigEmployer = $varDec['dd_phic_sss_er']*$totalHDMFgross;
			                            }
			                        } else {
			                            $varDecPagibig = $varDec['scr_ee'];
			                            $varDecPagibigEmployer = $varDec['scr_er'];
			                        }
								}
							}	
							$totalDeDuction[$ctr] += $varDecPagibig;
							$psapagibig = "Pag-ibig";
							if ($emp_id_ == "") {
	                            $this->doSavePayStubEntry($paystub_id, 15, $varDecPagibig,$varDecPagibigEmployer,$totalHDMFgross,0);
	                        }
//	                        printa($varDec);
//	                        echo "================== HDMF STAT ================<br>";
//		                    echo $totalHDMFgross." total HDMF GROSS <br>";
//		                    echo $varDecPagibig." Total employee deduction <br>";
//		                    echo $varDecPagibigEmployer." Total Employer Deduction <br>";
							break;
						default:
						break;
					}
				}
				
				$varDeduction = $totalDeDuction[$ctr];//sum all statutory contribution.
				$deduction = array( "SSS" => $varDecSSS
									,"SSSER" => $varDecSSSER
									,"SSSEC" => $varDecSSSEC
									,"PhilHealth" => $varDecPHIL
									,"PhilHealthER" => $varDecPHILER
									,"Pag-ibig" => $varDecPagibig
									,"Pag-ibigER" => $varDecPagibigEmployer
									,"Others" => $varDecOther);
	            if ($emp_id_ == "") {
					$this->doSavePayStubEntry($paystub_id, 27, $varDeduction);
	            }
	       
			// Compute W/H Tax
			//-------------------------------------------------------->>
			//Used to check if MWE
			$qry_ = array();
			$qry_[] = "a.empdd_id = '5'";
			$qry_[] = "a.emp_id = '".$rsResult->fields['emp_id']."'";
			$criteria = count($qry_)>0 ? " WHERE ".implode(' AND ',$qry_) : '';
			$sql_= "SELECT a.bldsched_period,a.percent_tax,a.s_ltu,a.s_stat FROM period_benloanduc_sched a $criteria";
			$varMWE = $this->conn->Execute($sql_);
			IF(!$varMWE->EOF){
				$varMWE_ = $varMWE->fields;
			}ELSE{
				$varMWE_ = 0;
			}
			//Get General Setup for TAX Computation
			$varHdeduc = clsPayroll_Details::getGeneralSetup('TAX');
			IF($varHdeduc['set_stat_type']=='1'){//1 = GROSS PAY
				IF($varHdeduc['set_order']=='1'){//1 = Subject to statutory Deduction
	        		$taxableGross = $basicPG_nostat - $varDeduction;//Taxable gross - Total Statutory Deduection
					IF($varMWE_['bldsched_period']=='4'){
						IF($varMWE_['s_stat']!='1'){//if no-stat plus Statutory
							$taxableGross = $taxableGross + $varDeduction;
						}
						IF($varMWE_['s_ltu']!='1'){
							$taxableGross = $taxableGross + $varSumAllTARate;
						}
					}
				}ELSE{//0 = not suject to statutory deduction
					$taxableGross = $basicPG_nostat;
					IF($varMWE_['bldsched_period']=='4'){
		        		IF($varMWE_['s_stat']=='1'){
							$taxableGross = $taxableGross - $varDeduction;
						}
						IF($varMWE_['s_ltu']!='1'){
							$taxableGross = $taxableGross + $varSumAllTARate;
						}
					}
				}
			}ELSE{//0 = BASIC SALARY
				IF($varHdeduc['set_order']=='1'){//1 = Subject to statutory Deduction
					$taxableGross = $BPperperiod - $varDeduction;//Basic Salary - Total Statutory Deduction
					IF($varMWE_['bldsched_period']=='4'){
		        		IF($varMWE_['s_stat']!='1'){
							$taxableGross = $taxableGross + $varDeduction;
						}
						IF($varMWE_['s_ltu']=='1'){
							$taxableGross = $taxableGross - $varSumAllTARate;
						}
					}
				}ELSE{//0 = not suject to statutory deduction
					$taxableGross = $BPperperiod;//Basic Salary
					IF($varMWE_['bldsched_period']=='4'){
		        		IF($varMWE_['s_stat']=='1'){
							$taxableGross = $taxableGross - $varDeduction;
						}
						IF($varMWE_['s_ltu']=='1'){
							$taxableGross = $taxableGross - $varSumAllTARate;
						}
					}
				}
			} 
			$annualize = clsPayroll_Details::getGeneralSetup('Annualize Tax on Last Pay Period of the Year');
	        $objClsProcess_Payroll = new clsProcess_Payroll(Application::db_open());
	        $oData = $objClsProcess_Payroll->dbFetch($_GET['ppsched_view'],$_GET['ppsched']);
	        $newDate = date("Y-m-d", strtotime($oData['payperiod_trans_date']));
	        // Verify if annualization is ON
	        IF($annualize['set_stat_type'] && $oData['payperiod_type'] == 1 && $newDate == $annualize['set_other_data']){
	        	$objClsBir_Alphalist = new clsBIRAlphalist(Application::db_open());
	        	$tax_policy = $objClsBir_Alphalist->getTaxPolicy();
	        	IF($objClsBir_Alphalist->getBonus($rsResult->fields['emp_id'], $oData['payperiod_period_year'],0)>$tax_policy['tp_other_benefits']){
	        		$addtn_bonus_taxable = $objClsBir_Alphalist->getBonus($rsResult->fields['emp_id'], $oData['payperiod_period_year'],0)-$tax_policy['tp_other_benefits'];
				} ELSE {
	        		$addtn_bonus_taxable = 0;
	        	}
	        	// Get taxable income for the whole year
	        	$basic_taxable = ($objClsBir_Alphalist->getBasicIncome($rsResult->fields['emp_id'], $oData['payperiod_period_year'])-$objClsBir_Alphalist->getStatutoryAndUnionDues($rsResult->fields['emp_id'], $oData['payperiod_period_year']))+$varSumAllTARate;
	        	$bonus_taxable = $objClsBir_Alphalist->getBonus($rsResult->fields['emp_id'], $oData['payperiod_period_year'],1)+$addtn_bonus_taxable;
	        	$other_comp_taxable = $objClsBir_Alphalist->getOtherCompensationTaxable($rsResult->fields['emp_id'], $oData['payperiod_period_year']);
	        	// $varSumAllOTRate  //save Total OT to payroll_paystub_entry table
				// $varSumAllTARate
	        	// Add all taxable including this pay period
	        	$total_taxable = $basic_taxable+$bonus_taxable+$other_comp_taxable+$taxableGross;
	        	switch ($rsResult->fields['taxep_code']) {
						case 'ME': $taxep_code = 'M'; break;
						case 'ME1': $taxep_code = 'M1'; break;
						case 'ME2': $taxep_code = 'M2'; break;
						case 'ME3': $taxep_code = 'M3'; break;
						case 'ME4': $taxep_code = 'M4'; break;
						default: $taxep_code = $rsResult->fields['taxep_code']; break;
					}
					
	        	// Get tax due for the whole year
	        	$taxdue = $objClsBir_Alphalist->getAnnualTaxDue($rsResult->fields['emp_id'], $oData['payperiod_period_year'],$taxep_code,$total_taxable);
	        	$taxwithheld = $objClsBir_Alphalist->getTaxWithheld($rsResult->fields['emp_id'], $oData['payperiod_period_year']);
	        	$taxwithheld_dec  = $objClsBir_Alphalist->getTaxWithheldDecember($rsResult->fields['emp_id'], $oData['payperiod_period_year']);
	        	$totalTax = $taxdue-($taxwithheld+$taxwithheld_dec);
	        	/**echo "<br>Basic YTD: ".$basic_taxable;
	        	echo "<br>Bonus YTD: ".$bonus_taxable;
	        	echo "<br>Others YTD: ".$other_comp_taxable;
	        	echo "<br>Taxable: ".$total_taxable;
	        	echo "<br>Tax Due: ".$taxdue;
	        	echo "<br>Tax Withheld(Jan-Nov): ".$taxwithheld;
	        	echo "<br>Tax Withheld(Dec): ".$taxwithheld_dec;
	        	echo "<br>Refund or Payable: ".$totalTax;
	        	echo "<br><br>Basic: ".$BPperperiod;
	        	echo "<br>TA: ".$varSumAllTARate;
	        	echo "<br>Basic Taxable minus TA: ".$basic_taxable;
	        	echo "<br>Bonus: ".$bonus_taxable;
	        	echo "<br>Other Taxable: ".$other_comp_taxable;
	        	echo "<br>Statutory: ".$objClsBir_Alphalist->getStatutoryAndUnionDues($rsResult->fields['emp_id'], $oData['payperiod_period_year']);
	        	echo "<br>Taxable Gross: ".$taxableGross;
	        	exit;**/
	        } ELSE {
				IF($BPDayRate['rateperday'] <= $MWR[$ctr]){
					//echo "MWE";
					$totalTax = 0;
					$conVertTaxper = 0;
				}ELSE{
					IF($varMWE_['bldsched_period']=='2'){
						//echo "2";
						$totalTax = 0;
						$conVertTaxper = 0;
					}ELSEIF($varMWE_['bldsched_period']=='3'){
						//echo "3";
						$totalTax = 0;
						$conVertTaxper = 0;	
					}ELSEIF($varMWE_['bldsched_period']=='4'){
						//echo "4";
						$conVertTaxper = $varMWE_['percent_tax'] / 100;
						$totalTax = $taxableGross * $conVertTaxper;
						$totalTax_ = $totalTax;
						$psaTax = "Tax";
					}ELSE{
						//echo "the else";
						$varDec = $this->getTotalTaxByPayPeriod($pData['chkAttend'][$ctr],$varHdeduc['set_decimal_places'],2,$taxableGross,$rsResult->fields['taxep_id'],$rsResult->fields['tt_pay_group']);
						$varStax = $taxableGross - $varDec['tt_minamount'];
						$conVertTaxper =  $varDec['tt_over_pct'] / 100;
						$varStax_p =  $varStax * $conVertTaxper;
						$totalTax = $varDec['tt_taxamount'] + $varStax_p;
						$totalTax_ = $totalTax;
						$psaTax = "Tax";
					}
				}
	        }
			//exit;
//			echo "<br>================= Tax Summary ================<br>";
//			echo $taxableGross." Taxable Gross<br>";
//			echo $varMWE_['percent_tax']." varMWE_[percent_tax]<br>";
//			echo $conVertTaxper." conVertTaxper<br>";
//			echo $totalTax." W/H Tax<br>";
			//----------------------------END-------------------------<<
			
			//get gov/reg loans
			//-------------------------------------------------------->>
			$govreg_loan[$ctr] = $this->getEmployeeActiveGovRegLoan($pData['chkAttend'][$ctr],$rsResult->fields['payperiod_trans_date'],$paystub_id,$rsResult->fields['payperiod_freq']);
			$totalregloan[$ctr] = 0;
			if(count($govreg_loan[$ctr])>0){
				foreach ($govreg_loan[$ctr] as $keyregloan => $valregloan){
                    //check if negative number
                    if($valregloan['loan_payperperiod'] >0){
                        $totalregloan[$ctr] +=  $valregloan['loan_payperperiod'];
                    }
				}
			}
//			printa($govreg_loan[$ctr]);
			//----------------------------END-------------------------<<

			$afterTaxGross = Number_Format($taxableGross,$objClsMngeDecimal->getFinalDecimalSettings(),'.','') - Number_Format($totalTax,$objClsMngeDecimal->getFinalDecimalSettings(),'.','');//deduct the tax to the taxable gross
			$grossandnontaxable = Number_Format($nontaxableGross,$objClsMngeDecimal->getFinalDecimalSettings(),'.','') + $SumNonTaxOTRate;//sum all non-taxable income
			$otherdeducNtax = Number_Format($other_nontaxdeduction,$objClsMngeDecimal->getFinalDecimalSettings(),'.','') + Number_Format($totalregloan[$ctr],$objClsMngeDecimal->getFinalDecimalSettings(),'.','');//sum all non-taxble deduction
			$SumAllDeduction = $varSumAllTARate + Number_Format($SumALLABDeduction,$objClsMngeDecimal->getFinalDecimalSettings(),'.','') + Number_Format($totalTax,2,'.','') + Number_Format($varDeduction,$objClsMngeDecimal->getFinalDecimalSettings(),'.','') + Number_Format($totalregloan[$ctr],$objClsMngeDecimal->getFinalDecimalSettings(),'.','');//Sum all Deduction
			$varNetpay[$ctr] = $SumAllEarning - $SumAllDeduction;//NETPAY

//			echo "<br>================ Final Summary ================<br>";
//			echo $afterTaxGross." After Tax Gross ( TaxableGross - W/H Tax)<br>";
//			echo $grossandnontaxable." sum all non-taxable income<br>";
//			echo $otherdeducNtax." sum all non-taxble deduction<br>";
//			echo "<br>================ DEDUCTION ================<br>";
//			echo Number_Format($varSumAllTARate,$objClsMngeDecimal->getFinalDecimalSettings(),'.','').' varSumAllTARate<br>';
//			echo Number_Format($SumALLABDeduction,$objClsMngeDecimal->getFinalDecimalSettings(),'.','').' SumALLABDeduction<br>';
//			echo Number_Format($totalTax,$objClsMngeDecimal->getFinalDecimalSettings(),'.','').' WHTAX<br>';
//			echo Number_Format($varDeduction,$objClsMngeDecimal->getFinalDecimalSettings(),'.','').' varDeduction<br>';
//			echo Number_Format($totalregloan[$ctr],$objClsMngeDecimal->getFinalDecimalSettings(),'.','')." totalregloan<br>";
//			echo $SumAllDeduction." Sum all Deduction<br>";
//			echo $varNetpay[$ctr]." NETPAY<br>"; exit;
			
			if($emp_id_ == ""){
				$this->doSavePayStubEntry($paystub_id,31,$grossandnontaxable);//save Total Non TS Earning to payroll_paystub_entry table
				$this->doSavePayStubEntry($paystub_id,32,$otherdeducNtax);	  //save Total Non TS Deduction to payroll_paystub_entry table
            	$this->doSavePayStubEntry($paystub_id,2,$SumAllDeduction);    //save Total Deduction to payroll_paystub_entry table
				$this->doSavePayStubEntry($paystub_id,5,$varNetpay[$ctr]);    //save netpay to payroll_paystub_entry table
				$this->doSavePayStubEntry($paystub_id,30,$taxableGross);	  //save Taxablegross
            	$this->doSavePayStubEntry($paystub_id,8,$totalTax,$taxableGross,$conVertTaxper); //save W/H Tax
			}

			//array structure of details on the paystub
			$arrPayStub[$ctr]['empinfo'] = array(
				 "emp_id" => $rsResult->fields['emp_id']
				,"emp_no" => $rsResult->fields['emp_idnum']
				,"fullname" => $rsResult->fields['fullname']
				,"jobcode" => $rsResult->fields['post_code']
				,"jobpos_name" => $rsResult->fields['post_name']
				,"comp_name" => $rsResult->fields['comp_name']
				,"comp_add" => $rsResult->fields['comp_add']
				,"ud_name" => $rsResult->fields['ud_name']
				,"tax_ex_name" => $rsResult->fields['taxep_name']
				,"comp_id" => $rsResult->fields['comp_id']
				,"ud_id" => $rsResult->fields['ud_id']
				,"emptype_name" => $rsResult->fields['emptype_name']
				,"bankiemp_acct_no" => $rsResult->fields['bankiemp_acct_no']
				,"banklist_name" => $rsResult->fields['banklist_name']
				,"pi_emailone" => $rsResult->fields['pi_emailone']
				,"taxep_id" => $rsResult->fields['taxep_id']
				,"salaryclass_id" => $rsResult->fields['salaryclass_id']
				,"tt_pay_group" => $rsResult->fields['tt_pay_group']
				,"ud_code" => $rsResult->fields['ud_code']
				,"taxep_code" => $rsResult->fields['taxep_code']
				,"salarytype_id" => $rsResult->fields['salarytype_id']
			);
			$arrPayStub[$ctr]['paystubdetail'] = array(
					"paystubsched" =>array(
						 "pps_id" => $rsResult->fields['pps_id']
						,"pps_name" => $rsResult->fields['pps_name']
						,"payperiod_id" => $rsResult->fields['payperiod_id']
						,"paystub_id" => $paystub_id
						,"payperiod_start_date" => $rsResult->fields['payperiod_start_date']
						,"payperiod_end_date" => $rsResult->fields['payperiod_end_date']
						,"payperiod_trans_date" => $rsResult->fields['payperiod_trans_date']
						,"schedSecond" => $rsResult->fields['pps_secnd_trans_daymonth']
						,"payperiod_freq" => $rsResult->fields['payperiod_freq']
					)
					,"paystubaccount" => array(
						 "earning" => array(
							 "basic" => $basicpay_rate
							,"Regulartime" => $BPperperiod
							,"COLA" => $COLAperperiod
							,"COLAperDay" => $colaperday[$ctr]
							,"totalDays" => $varActualDaysRender[$ctr]
							,"MWR" => $MWR[$ctr]
							,"DailyRate" => $rateperday[$ctr]
							,"HourlyRate" => $rateperhour[$ctr]
							,"OT" => array(
								 "OTDetails" => $this->otDetail[$ctr]
								,"TotalallOT" => $varSumAllOTRate + $SumNonTaxOTRate
								,"OTbackpay" => Number_Format("0",$objClsMngeDecimal->getFinalDecimalSettings(),'.','') + $SumNonTaxOTRate
								,"SumAllOTRate" => $varSumAllOTRate + $SumNonTaxOTRate
							)
						)
						,"deduction" => $deduction
						,"TUA"=> array(
							 "TADetails" => $this->taDetail[$ctr]
							,"TotalLeave" => Number_Format($varSumAllTARate,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						)
						,"leave_record" => $LeaveRecords
						,"convert_leave" => $convert_leave
						,"government_regular" => $govreg_loan[$ctr]
						,"benefits" => $payStubSerialize
						,"amendments" => array(
								 $amendments
								,$recurring_amendments
								,"total_TSAEarning" => Number_Format($totalTSEarningAmendments[$ctr],$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
								,"total_TAEarning" => Number_Format($totalTEarningAmendments[$ctr],$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
								,"total_SAEarning" => Number_Format($totalSEarningAmendments[$ctr],$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
								,"total_NTSAEarning" => Number_Format($totalNTSEarningAmendments[$ctr],$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
								,"total_TSADeduction" => Number_Format($totalTSDeductionAmendments[$ctr],$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
								,"total_TADeduction" => Number_Format($totalTDeductionAmendments[$ctr],$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
								,"total_SADeduction" => Number_Format($totalSDeductionAmendments[$ctr],$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
								,"total_NTSADeduction" => Number_Format($totalNTSDeductionAmendments[$ctr],$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						)
						,"pstotal" => array(
						 	 "gross" => Number_Format($SumAllEarning,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"Basic Salary" => Number_Format($basicpay_rate,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"PGsalary" => Number_Format($basicgrosspg,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"gross_nontaxable_income" => Number_Format($grossandnontaxable,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"taxable_Gross" => Number_Format($taxableGross,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"Deduction" => Number_Format($SumAllDeduction,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"SatutoryDeduction" => Number_Format($varDeduction,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"W/H Tax" => Number_Format($totalTax,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"aftertaxgross" => Number_Format($afterTaxGross,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"Net Pay" => Number_Format($varNetpay[$ctr],$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"Loan_Total" => Number_Format($totalregloan[$ctr],$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"other_taxable_income" => Number_Format($totalTEarningAmendments[$ctr],$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"other_deduction" => Number_Format($otherdeducNtax,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"TotalEarning_payslip" => Number_Format($SumAllEarning,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"SumSABEarning" => Number_Format($SumSABEarning,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"SumTSABEarning" => Number_Format($SumTSABEarning,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"SumTABDeduction" => Number_Format($SumTABDeduction,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"SumTABEarning" => Number_Format($SumTABEarning,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"BaseSTATGross" => Number_Format($basicPG_ABS,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"SumTSABDeduction" => Number_Format($TotalTSSDeduction,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 )
					)
			);
		  $ctr++;
		} while($ctr < sizeof($pData['chkAttend']));
//		printa($arrPayStub);
//		exit;
		$this->doSavePayStubArr($arrPayStub);
        if($emp_id_ == ""){
            return $retval;
        }else{
            return $arrPayStub;
        }
	}
	
	/**
	 * author:rblising
	 * Takes $needles as an array, loops through them returning matching
	 * keys => value pairs from haystack
	 * Useful for filtering results to a select box, like status.
	 * @param unknown_type $needles
	 * @param unknown_type $haystack
	 */
	function getByArray($needles, $haystack) {
		if (!is_array($needles) ) {
			$needles = array($needles);
		}
//		printa($needles);
		$needles = array_unique($needles);
		foreach($needles as $needle){
			if(isset($haystack[$needle])){
				$retval[$needle] = $haystack[$needle];
			}
		}
		if (isset($retval)){
			return $retval;
		}
		return FALSE;
	}
	
	/**
	 * @note: Get the Total Employee per pay group.
	 * 
	 * @param unknown_type $pps_id_
	 * @param unknown_type $payperiod_id_
	 * @param unknown_type $isnot_
	 */
	function get_totalEmp($pps_id_ = null, $payperiod_id_ = null, $isnot_ = false){
		$qry = array();
		if($isnot_==true){
			$notin = 'in';
		}else{
		/**	$sqlsal = "SELECT DATE_FORMAT(payperiod_start_date,'%Y-%m-%d') as payperiod_start_date, DATE_FORMAT(payperiod_end_date,'%Y-%m-%d') as payperiod_end_date, payperiod_id  from payroll_pay_period where payperiod_id='".$payperiod_id_."'";
			$sqlsal_ = $this->conn->Execute($sqlsal);
//			printa($sqlsal_);
			$notin = 'not in';
			$qry[] = "d.salaryinfo_effectdate <= '".$sqlsal_->fields['payperiod_end_date']."'";
			$qry[] = "c.emp_stat in ('1','7','10')";
			$qry[] = "d.salaryinfo_isactive = 1";
			$stat_ = (count($qry)>0)?" and ".implode(" and ",$qry):"";
			$var = "JOIN salary_info d on (c.emp_id=d.emp_id)";
			if( $_GET['statpos'] == 'ytdentry_process'){
				$var .=" JOIN import_ytd_details e on (c.emp_id=a.emp_id)" ;
			}
		**/
		if ($_GET['statpos']=='process_payroll') {
				$qrysql = "JOIN salary_info sal on (sal.emp_id=empinfo.emp_id)";
				$qrysql_= "JOIN payroll_pps_user pps on (pps.emp_id=empinfo.emp_id)";
				$qrysql2= "JOIN payroll_comp pc on (pc.emp_id=sal.emp_id)";
				$sqlsal = "SELECT payperiod_type,DATE_FORMAT(payperiod_start_date,'%Y-%m-%d') as payperiod_start_date, DATE_FORMAT(payperiod_end_date,'%Y-%m-%d') as payperiod_end_date, payperiod_id FROM payroll_pay_period WHERE payperiod_id='".$_GET['ppsched_view']."'";
				$result = $this->conn->Execute($sqlsal);
				$var = $result->fields['payperiod_id'];
				$qry[]="empinfo.emp_id not in (SELECT a.emp_id FROM payroll_pps_user a JOIN payroll_paystub_report re on (a.emp_id=re.emp_id) WHERE payperiod_id = '".$_GET['ppsched_view']."')";
				$qry[]="sal.salaryinfo_effectdate <= '".$result->fields['payperiod_end_date']."'";
				$qry[]="pps.pps_id = '".$_GET['ppsched']."'";
				IF($result->fields['payperiod_type']==3){
					$otadd = "N/A";
				} ELSE {
					$otadd = "<a href=\"?statpos=process_payroll&otcomp=',pps.pps_id,'&emp=',empinfo.emp_id,'&edit=',$var,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/leaveicon.png\" title=\"TA\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
				}
				$leaveadd = "<a href=\"?statpos=process_payroll&edit=',pps.pps_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/leaveicon.png\" title=\"Leave\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
				
				$qry[]="sal.salaryinfo_isactive = '1'";
				$qry[] = "pc.fr_id not in (0)";
				$listcomp = $_SESSION[admin_session_obj][user_comp_list2];
				$listloc = $_SESSION[admin_session_obj][user_branch_list2];
				$listpgroup = $_SESSION[admin_session_obj][user_paygroup_list2];
				IF(count($listcomp)>0){
					$qry[] = "empinfo.comp_id in (".$listcomp.")";//company that can access
				}
				IF(count($listloc)>0){
					$qry[] = "empinfo.branchinfo_id in (".$listloc.")";//location that can access
				}
				IF(count($listpgroup)>0){
					$qry[] = "pps.pps_id in (".$listpgroup.")";//pay group that can access
				}
			} elseif($_GET['statpos'] == 'ytdentry_process') { 
				$var .=" JOIN import_ytd_details e on (c.emp_id=a.emp_id)" ;
			} else {
				$qry[]="empinfo.emp_id not in (SELECT a.emp_id FROM payroll_pps_user a)";
			}
	        $qry[] = "empinfo.emp_stat in ('1','7','10','8')";
	        $qry[] = "((empinfo.emp_resigndate IS NULL) OR (empinfo.emp_resigndate >= '".$result->fields['payperiod_start_date']."' AND empinfo.emp_resigndate <= '".$result->fields['payperiod_end_date']."'))";
			// put all query array into one criteria string
			$criteria = (count($qry)>0)?" where ".implode(" AND ",$qry):"";	
		}
		IF($_GET['statpos'] == 'process_payroll'){
			$sql = "SELECT COUNT(*) AS totalemp
					FROM emp_masterfile empinfo
					JOIN emp_personal_info pinfo ON (pinfo.pi_id=empinfo.pi_id)
					LEFT JOIN app_userdept dept ON (dept.ud_id=empinfo.ud_id)
					LEFT JOIN emp_position post ON (post.post_id=empinfo.post_id)
					LEFT JOIN company_info comp ON (comp.comp_id=empinfo.comp_id)
					LEFT JOIN branch_info bran ON (bran.branchinfo_id=empinfo.branchinfo_id)
				$qrysql
				$qrysql_
				$qrysql2
				$criteria
				$strOrderBy";
		} ELSE {
			$sql = "SELECT count(c.emp_id) as totalemp 
				FROM payroll_pps_user a 
				JOIN emp_masterfile c on (a.emp_id = c.emp_id)
				JOIN payroll_comp pc on (pc.emp_id=c.emp_id)
				$var
				WHERE pc.fr_id not in (0) AND a.pps_id='".$pps_id_."' $stat_ and a.emp_id $notin (SELECT a.emp_id FROM payroll_pps_user a JOIN payroll_paystub_report re on (a.emp_id=re.emp_id) WHERE payperiod_id = '".$payperiod_id_."')";
		}
		$objData = $this->conn->Execute($sql);
		if(!$objData->EOF){
			return $objData->fields;
		}
	}
	
/**
	 * @note: Get the Total Employee per pay group.
	 * 
	 * @param unknown_type $pps_id_
	 * @param unknown_type $payperiod_id_
	 * @param unknown_type $isnot_
	 */
	function get_totalEmpOther($pps_id_ = null, $payperiod_id_ = null, $isnot_ = false){
		$qry = array();
		if($isnot_==true){
			$notin = 'in';
		}else{
			$sqlsal = "SELECT DATE_FORMAT(payperiod_start_date,'%Y-%m-%d') as payperiod_start_date, DATE_FORMAT(payperiod_end_date,'%Y-%m-%d') as payperiod_end_date, payperiod_id  from payroll_pay_period where payperiod_id='".$payperiod_id_."'";
			$sqlsal_ = $this->conn->Execute($sqlsal);
//			printa($sqlsal_);
			$notin = 'not in';
			$qry[] = "d.salaryinfo_effectdate <= '".$sqlsal_->fields['payperiod_end_date']."'";
			$qry[] = "c.emp_stat in ('1','7','10')";
			$qry[] = "d.salaryinfo_isactive = 1";
			$qry[] = "f.payperiod_id = ".$payperiod_id_;
			$stat_ = (count($qry)>0)?" and ".implode(" and ",$qry):"";
			$var = "JOIN salary_info d on (c.emp_id=d.emp_id)";
			$var .= "JOIN payroll_ps_amendemp e on (e.emp_id=d.emp_id)";
			$var .= "JOIN payroll_ps_amendment f on (f.psamend_id=e.psamend_id)";
		}
		$sql = "SELECT count(c.emp_id) as totalemp 
				FROM payroll_pps_user a 
				JOIN emp_masterfile c on (a.emp_id = c.emp_id)
				JOIN payroll_comp pc on (pc.emp_id=c.emp_id)
				$var
				WHERE pc.fr_id not in (0) AND a.pps_id='".$pps_id_."' $stat_ and a.emp_id $notin (SELECT a.emp_id FROM payroll_pps_user a JOIN payroll_paystub_report re on (a.emp_id=re.emp_id) WHERE payperiod_id = '".$payperiod_id_."')";
		
		$objData = $this->conn->Execute($sql);
		if(!$objData->EOF){
			return $objData->fields;
		}
	}
	
	/**
	 * @note: Save Data to payroll_pay_stub table
	 * 
	 * @param $val
	 * @param $payperiod_id_
	 */
	function doSavePayStub($val,$payperiod_id_) {
//		printa($val);
//		$this->conn->debug=1;
		$retval = "";
		if (is_null($payperiod_id_ )) { return $retval; }
		$sql = "select * from payroll_pay_stub where emp_id = '".$val."'
				and payperiod_id = '".$payperiod_id_."'";
		$rsResult = $this->conn->Execute($sql);
		if (!$rsResult->EOF) {
			return $rsResult->fields['paystub_id'];
		} else {
			$sql_ = "Select * from payroll_pay_period where payperiod_id = '".$payperiod_id_."'";
			$rsResult_ = $this->conn->Execute($sql_);
			if (!$rsResult_->EOF) {
				$flds = array();
				$flds[] = "payperiod_id = '".$payperiod_id_."'";
				$flds[] = "paystub_addwho = '".AppUser::getData('user_name')."'";
				$flds[] = "paystub_start_date = '".$rsResult_->fields['payperiod_start_date']."'";
				$flds[] = "paystub_end_date = '".$rsResult_->fields['payperiod_end_date']."'";
				$flds[] = "paystub_trans_date = '".$rsResult_->fields['payperiod_trans_date']."'";
				$flds[] = "emp_id = '".$val."'";
				$fields = implode(", ",$flds);
				$sql = "insert into payroll_pay_stub set $fields";
				$this->conn->Execute($sql);
				$paystub_id = $this->conn->Insert_ID();
			}
			return $paystub_id;
		}
	}
	
	/**
	 * @note: Get rate per employee
	 * 
	 * @param unknown_type $salary_class
	 * @param unknown_type $compensation
	 * @param unknown_type $rate_type
	 */
	function getrate($salary_class = "", $compensation= "", $rate_type = ""){
		/*
		@TODO: wla pa computattion para
			  sa weekly, annual, bi-weekly
		*/
		if ($salary_class == "") { return 0; }
		if ($compensation == "") { return 0; }
		if ($salary_class==5) {
			//monthly
            $periodic = ($compensation*12)/13;
			$rateper_hour =  (($periodic)/2)/112;
			$rateper_sec = (($periodic/2)/112)/60/60;
//            printa($periodic);
//            printa($rateper_hour);
		}elseif ($salary_class==1) {
			//hourly
            $periodic = ($compensation*12)/13;
			$rateper_hour =  (($periodic)/2)/112;
			$rateper_sec = ((($periodic/2)/112)/60)/60;

		}else{
            //daily
			$rateper_day = $compensation;
			$rateper_sec = (($compensation/8)/60)/60;
		}
		/*
		ratetype 1 = per day
		ratetype 2 = per sec
		ratetype 3 = per hour
		*/
		if ($rate_type == 1) {
			return $rateper_day;
		}elseif($rate_type == 3){
            return $rateper_hour;
        }else{
			return $rateper_sec;
		}
	}
	
	/**
	 * @note: Save Data to payroll_paystub_entry table
	 * @param $paystub_id_
	 * @param $psa_id_
	 * @param $psatotal_
	 * @param $psatotal_employer
	 * @param $ppe_rate_
	 * @param $ppe_units_
	 */
	function doSavePayStubEntry($paystub_id_ = null, $psa_id_ = null, $psatotal_ = 0,$psatotal_employer = 0, $ppe_rate_ = 0, $ppe_units_ = 0) {
		$fldspse = array();
		$sql = "SELECT * FROM payroll_paystub_entry WHERE paystub_id = $paystub_id_ and psa_id = $psa_id_";
		$rsResult = $this->conn->Execute($sql);
		$fldspse[] = "psa_id = '".$psa_id_."'";
		$fldspse[] = "paystub_id = '".$paystub_id_."'";
		$fldspse[] = "ppe_amount = '".$psatotal_."'";
		$fldspse[] = "ppe_amount_employer = '".$psatotal_employer."'";
		$fldspse[] = "ppe_rate = '".$ppe_rate_."'";
		$fldspse[] = "ppe_units = '".$ppe_units_."'";
		if (!$rsResult->EOF) {
			$fldspse[] = "ppe_isedited = 1";
			$fldspse[] = "ppe_updatewho = '".AppUser::getData('user_name')."'";
			$fldspse[] = "ppe_updatewhen = '".date('Y-m-d h:i:s')."'";
			$fieldspse = implode(", ",$fldspse);
			$sql = "UPDATE payroll_paystub_entry set $fieldspse where paystub_id = $paystub_id_ and psa_id = $psa_id_";
			$this->conn->Execute($sql);
		}else{
			$fldspse[] = "ppe_addwho = '".AppUser::getData('user_name')."'";
			$fieldspse = implode(", ",$fldspse);
			if($psa_id_ == '7' || $psa_id_ == '14' || $psa_id_ == '15'){
				$sql = "INSERT INTO payroll_paystub_entry set $fieldspse";
				$this->conn->Execute($sql);
			}ELSE{
				if($psatotal_ != 0){
					$sql = "INSERT INTO payroll_paystub_entry set $fieldspse";
					$this->conn->Execute($sql);
				}
			}
			/*IF($psatotal_ > 0){
				$sql = "INSERT into payroll_paystub_entry set $fieldspse";
				$this->conn->Execute($sql);
			}ELSE{
				IF($psa_id_=='7' || $psa_id_=='14' || $psa_id_=='15'){
					$sql = "INSERT into payroll_paystub_entry set $fieldspse";
					$this->conn->Execute($sql);
				}
			}*/
		}
	}
	
	/**
	 * @note: update ot_record table
	 * @param $otrec_id_
	 * @param $paystub_id_
	 * @param $otamt
	 */
	function updateOTrecord($otrec_id_ = null, $paystub_id_ = null, $otamt = 0){
		$flds = array();
		$flds[] = "paystub_id = '".$paystub_id_."'";
		$flds[] = "otrec_subtotal = '".$otamt."'";
		$flds[] = "otrec_updatewho = '".AppUser::getData('user_name')."'";
		$flds[] = "otrec_updatewhen = '".date('Y-m-d h:i:s')."'";
		$fields = implode(", ",$flds);

		$sql = "UPDATE ot_record set $fields WHERE otrec_id=$otrec_id_";
		$this->conn->Execute($sql);
	}
	
	/**
	 * @note: update ta_emp_rec table
	 * @param $emp_tarec_id_
	 * @param $paystub_id_
	 * @param $taamt
	 */
	function updateTArecord($emp_tarec_id_ = null, $paystub_id_ = null, $taamt = 0){
		$flds = array();
		$flds[] = "paystub_id = '".$paystub_id_."'";
		$flds[] = "emp_tarec_amtperrate = '".$taamt."'";
		$flds[] = "emp_tarec_updatewho = '".AppUser::getData('user_name')."'";
		$flds[] = "emp_tarec_updatewhen = '".date('Y-m-d h:i:s')."'";
		$fields = implode(", ",$flds);

		$sql = "update ta_emp_rec set $fields where emp_tarec_id=$emp_tarec_id_";
		$this->conn->Execute($sql);
	}
	
	/**
	 * Get Amendments
	 * @param integer $empid
	 * @return fields
	 */
	function getAmendments($empid_,$transdate,$startdate,$enddate,$paystub_id_ = "",$payperiod_id_=null) {
		$qry = array();
		IF($payperiod_id_ != null){ $qry[] = "a.payperiod_id = $payperiod_id_"; }
		$qry[] = "c.emp_id = $empid_";
		$qry[] = "a.psamend_effect_date = '".date('Y-m-d',dDate::parseDateTime($transdate))."'";
		$qry[] = "(a.psamend_effect_date >= '".date('Y-m-d',dDate::parseDateTime($transdate))."' 
				  AND a.psamend_effect_date <= '".date('Y-m-d',dDate::parseDateTime($enddate))."')";
		$qry[] = "(ISNULL(c.paystub_id) OR c.paystub_id='0' OR c.paystub_id='".$paystub_id_."')";
		$criteria = count($qry)>0 ? " where ".implode(' AND ',$qry) : '';
		$sql = "SELECT a.psa_id,a.psamend_id,c.amendemp_id,b.psa_name,a.psamend_desc,b.psa_type,a.psamend_rate,a.psamend_units,a.psamend_amount,psamend_effect_date,a.psamend_istaxable,b.psa_tax,b.psa_statutory,c.amendemp_amount
					FROM payroll_ps_amendment a
					JOIN payroll_ps_amendemp c on (c.psamend_id=a.psamend_id)
					JOIN payroll_ps_account b on a.psa_id=b.psa_id
					$criteria";
		$rsResult = $this->conn->Execute($sql);
		$x = 0;
        while (!$rsResult->EOF) {
			$arrResult[$x] = $rsResult->fields;
            $sqlhead = "UPDATE payroll_ps_amendment a SET a.psamend_status = '2' WHERE psamend_id = '".$arrResult[$x]['psamend_id']."'";
            $this->conn->Execute($sqlhead);
            $sqlemp = "UPDATE payroll_ps_amendemp a SET a.paystub_id = '".$paystub_id_."' WHERE amendemp_id = '".$arrResult[$x]['amendemp_id']."'";
			$this->conn->Execute($sqlemp);
			$rsResult->MoveNext();
            $x++;
		}
		return $arrResult;
	}
	
	function getSchedPeriod($empid_,$transdate,$startdate,$enddate) {
		$qry = array();
		$qry[] = "c.emp_id = $empid_";
//		$qry[] = "a.psamend_effect_date = '".date('Y-m-d',dDate::parseDateTime($transdate))."'";
		$qry[] = "(a.psamend_effect_date >= '".$startdate."' 
				  and a.psamend_effect_date <= '".$enddate."')";
		$criteria = count($qry)>0 ? " where ".implode(' and ',$qry) : '';
		$sql = "select a.psamend_id,a.psamend_rate,a.psamend_units,a.psamend_amount, a.psamend_istaxable, b.psa_type,a.psamend_desc, b.psa_name,a.psa_id, psamend_effect_date
					from payroll_ps_amendment a
					inner join payroll_ps_amendemp c on (c.psamend_id=a.psamend_id)
					inner join payroll_ps_account b on a.psa_id=b.psa_id
					$criteria";
		$rsResult = $this->conn->Execute($sql);
		$x = 0;
        while (!$rsResult->EOF) {
			$arrResult[$x] = $rsResult->fields;
//            $sql = "update payroll_ps_amendment a set a.psamend_status = 2 where psamend_id = '".$arrResult[$x]['psamend_id']."'";
//            $this->conn->Execute($sql);
			$rsResult->MoveNext();
            $x++;
		}
		return $arrResult;
	}
	
	
	/**
	 * @Note: Get Amendments per employee
	 *
	 * @param integer $empid
	 * @return fields
	 *
	 */
	function getRecurringBenifitsDeduction($empid_,$transdate,$startdate,$enddate) {
		$qry = array();
		$qry[] = "a.emp_id = $empid_";
//		$qry[] = "a.psamend_effect_date = '".date('Y-m-d',dDate::parseDateTime($transdate))."'";
		$qry[] = "(a.ben_startdate <= '".date('F Y',strtotime($startdate))."')";
//		 and a.psamend_effect_date <= '".date('F Y',strtotime($enddate))."')
		$criteria = count($qry)>0 ? " where ".implode(' and ',$qry) : '';
		$sql = "select a.ben_id,a.ben_amount,a.ben_payperday, b.psa_type, b.psa_name, a.psa_id, a.ben_startdate, a.ben_enddate
					from emp_benefits a
					inner join payroll_ps_account b on a.psa_id=b.psa_id
					$criteria";
		$rsResult = $this->conn->Execute($sql);
		$x = 0;
        while (!$rsResult->EOF) {
			$arrResult[$x] = $rsResult->fields;
			$rsResult->MoveNext();
            $x++;
		}
		return $arrResult;
	}
	
	
	/**
	 * Get type of Deduction in hris that connected per employee
	 * @param $emp_id_
	 */
	function getTypeDeduction() {
//		$this->conn->debug=1;
		$arrData = array();
		$qry = array();
		$qry[] = "a.dec_id not in (4,5)"; //tax not included
		$criteria = count($qry)>0 ? " WHERE ".implode(' AND ',$qry) : '';
		$sql = "SELECT a.* FROM deduction_type a $criteria";
		$rsResult = $this->conn->Execute($sql);
		$ctr = 0;
		while (!$rsResult->EOF) {
			$arrData[$ctr] = $rsResult->fields;
			$arrData[$ctr]['GenSetup'] = clsPayroll_Details::getGeneralSetup($rsResult->fields['dec_code']);
			$ctr++;
			$rsResult->MoveNext();
		}
		return $arrData; //$arrData = array('1','2','3');
	}
	
	/**
	 * Get the Total Deduction.
	 *
	 * @param unknown_type $emp_id_
	 * @param unknown_type $payperiod_id_
	 * @param unknown_type $tksudttot_status_
	 * @param unknown_type $tksudttot_type_
	 * @param unknown_type $groupby_option_
	 * @return unknown
	 */
	function getTotalDeductionByPayPeriod($emp_id_ = null, $dduct_id_ = null,$dduct_type_ = null, $totaltgross_ = 0, $tax_ex_id_ = null) {
		$arrData = array();
		$qry = array();
		IF (is_null($emp_id_)) { return $arrData; }
		IF (is_null($dduct_id_) || empty($dduct_id_)) { return $arrData; }
		IF (is_null($dduct_type_)) { return $arrData; }
		
		if ($dduct_type_==5){ $qry[] = "c.tax_ex_id = $tax_ex_id_"; }
        if($dduct_id_ == 1){
            $qry[] = " $totaltgross_ >= b.min_salary";
        }else if($dduct_id_ == 2 or $dduct_id_ == 5 or $dduct_id_ == 6 or $dduct_id_ == 7){
            $qry[] = " $totaltgross_ >= b.min_salary";
        }else{
            $qry[] = "b.min_salary < $totaltgross_";
        }
//		$qry[] = "a.emp_id = $emp_id_";
        $qry[] = "a.sc_id = $dduct_id_";
		$qry[] = "a.dec_id = $dduct_type_";
		$criteria = count($qry)>0 ? " where ".implode(' and ',$qry) : '';
		$orderBy = "order by b.min_salary desc";
		$sql = "select * 
					FROM statutory_contribution a 
					JOIN sc_records b on (a.sc_id = b.sc_id) 
					$criteria $orderBy limit 1";
		$rsResult = $this->conn->Execute($sql);
		if (!$rsResult->EOF) {
			return $rsResult->fields;
		}
	}
	
	
	/**
	 * @note: Get the Total Deduction TAX.
	 * 
	 * @param $emp_id_ as employee id
	 * @param $dduct_id_ as deduction id
	 * @param $dduct_type_ as deduction type
	 * @param $totaltgross_ as total gross
	 * @param $tax_ex_id_ as tax exception
	 * @param $tax_table_ as tax table
	 */
	function getTotalTaxByPayPeriod($emp_id_ = null, $dduct_id_ = null,$dduct_type_ = null, $totaltgross_ = 0, $tax_ex_id_ = null, $tax_table_ = null) {
		$arrData = array();
		$qry = array();
		if (is_null($emp_id_)) { return $arrData; }
		if (is_null($dduct_id_) || empty($dduct_id_)) { return $arrData; }
		if (is_null($dduct_type_)) { return $arrData; }
		if($dduct_type_==5){ $qry[] = "a.tt_exemption = $tax_ex_id_"; }
//		$qry[] = "a.emp_id = $emp_id_";
		$qry[] = "b.tp_id = $dduct_id_";
        if($dduct_id_ == 1){
            $qry[] = " $totaltgross_ >= b.tt_minamount";
        }else if($dduct_id_ == 2){
            $qry[] = " $totaltgross_ >= b.tt_minamount";
        }else{
            $qry[] = "b.tt_minamount < $totaltgross_";
        }
        
//		$qry[] = "a.dec_id = $dduct_type_";
//		if ($tax_table_==1) {
//			//Daily
//			$qry[] = "b.tt_pay_group = '1'";
//		}elseif ($tax_table_==2 || $tax_table_==3){
//			//weekly & Bi-weekly
//			$qry[] = "b.tt_pay_group = '2'";
//		}elseif ($tax_table_==4){
//			//Semi-Monthly
//			$qry[] = "b.tt_pay_group = '3'";
//		}elseif ($tax_table_==5){
//			//Monthly
//			$qry[] = "b.tt_pay_group = '4'";
//		}else{
//			//Annual
//			$qry[] = "b.tt_pay_group = '5'";
//		}
		$qry[] = "b.tt_pay_group = '".$tax_table_."'";//tax table to be used in computation.
 		
//		get the tax exemption.
		if($tax_ex_id_ == 3 || $tax_ex_id_ == 8){
			//S/ME Exemption
			$qry[] = "b.tt_exemption = '3'";
		}elseif($tax_ex_id_ == 4 || $tax_ex_id_ == 9){
			//ME1/S1 Exempton
			$qry[] = "b.tt_exemption = '4'";
		}elseif($tax_ex_id_ == 5 || $tax_ex_id_ == 10){
			//ME2/S2 Exemption
			$qry[] = "b.tt_exemption = '5'";
		}elseif($tax_ex_id_ == 6 || $tax_ex_id_ == 11){
			//ME3/S3 Exemption
			$qry[] = "b.tt_exemption = '6'";
		}elseif($tax_ex_id_ == 7 || $tax_ex_id_ == 12){
			//ME4/S4 Exemption
			$qry[] = "b.tt_exemption = '7'";
		}else{
			//Zero Exemption
        	$qry[] = "b.tt_exemption = '1'";
		}
        
		$criteria = count($qry)>0 ? " where ".implode(' and ',$qry) : '';
		$sql = "SELECT c.taxep_name , a.*, b.*
						FROM tax_policy a
						JOIN tax_table b on (a.tp_id = b.tp_id)
						JOIN tax_excep c on (c.taxep_id=b.tt_exemption)
						$criteria
						ORDER BY b.tt_minamount desc
						limit 1";
		$rsResult = $this->conn->Execute($sql);
		if (!$rsResult->EOF){
			return $rsResult->fields;
		}
	}
	
	/**
	 * @note: Get Employee Benefits Record
	 * @param $id_
	 * @param $transdate
	 * @param $startdate
	 * @param $enddate
	 * @param $ben_id_
	 */
	function dbFetchEmployeeBenefits($id_ = "",$transdate,$startdate,$enddate,$ben_id_=null,$period = ""){
		if(isset($_GET['popupbenpe'])){
			$qry[] = "a.ben_id = $ben_id_";
		}else{
			$qry[] = "a.emp_id = '".$id_."'";
			$qry[] = "c.bldsched_period = '".$period."'";
			$qry[] = "a.ben_suspend != '1'";
			$qry[] = "'".date("Y-m-d")."' >= a.ben_startdate";
			$qry[] = "IF(a.ben_enddate='0000-00-00',a.ben_enddate <= '".date("Y-m-d")."',a.ben_enddate >= '".date("Y-m-d")."')";
		}
		$criteria = count($qry)>0 ? " where ".implode(' and ',$qry) : '';
		$sql = "SELECT a.psa_id,a.ben_id,b.psa_name,b.psa_type,a.ben_amount,a.ben_payperday,a.ben_startdate,a.ben_enddate,a.ben_isfixed,a.ben_suspend,a.ben_periodselection,b.psa_priority,b.psa_tax,b.psa_statutory, DATE_FORMAT(a.ben_startdate,'%Y') as year_,
				DATE_FORMAT(a.ben_startdate,'%m') as month_, DATE_FORMAT(a.ben_enddate,'%Y') as yearend_, DATE_FORMAT(a.ben_enddate,'%m') as monthend_
					FROM emp_benefits a
					INNER JOIN payroll_ps_account b on (a.psa_id=b.psa_id)
					LEFT JOIN period_benloanduc_sched c on (a.ben_id=c.ben_id)
					$criteria";
		$rsResult = $this->conn->Execute($sql);
		$arrData = array();
		$x = 0;
		while(!$rsResult->EOF){
			 $arrData[$x] = $rsResult->fields;
			 $rsResult->MoveNext();
			 $x++;
		}
		return $arrData;
	}
	
	/**
	 * Save Array Pay Stub.
	 *
	 * @param unknown_type $arrPayStub
	 */
	function doSavePayStubArr($arrPayStub) {
        unset($_SESSION['payslip']);
        $this->payslips = array();
		for ($i=0;$i<count($arrPayStub);$i++) {
				$arrPayStubSer = "";
				$arrPayStubSer = serialize($arrPayStub[$i]);
				$sql = "select * from payroll_paystub_report where paystub_id='".$arrPayStub[$i]['paystubdetail']['paystubsched']['paystub_id']."'
						and emp_id = '".$arrPayStub[$i]['empinfo']['emp_id']."'";
				$rsResult = $this->conn->Execute($sql);

				if (!$rsResult->EOF) {
                    $this->payslips[] = array('name' => $arrPayStub[$i]['empinfo']['fullname'],'status' => 1);
					$fldsppr = array();
					$fldsppr[] = "ud_id = '".$arrPayStub[$i]['empinfo']['ud_id']."'";
                    $fldsppr[] = "comp_id = '".$arrPayStub[$i]['empinfo']['comp_id']."'";
					$fldsppr[] = "ppr_paystubdetails = '".$arrPayStubSer."'";
					$fldsppr[] = "ppr_updatewho = '".AppUser::getData('user_name')."'";
					$fldsppr[] = "ppr_updatewhen = '".date("Y-m-d H:i:s")."'";
					$fieldsppr = implode(", ",$fldsppr);
					
					$sql = "update payroll_paystub_report set $fieldsppr where paystub_id='".$arrPayStub[$i]['paystubdetail']['paystubsched']['paystub_id']."' and emp_id = '".$arrPayStub[$i]['empinfo']['emp_id']."'";
					$this->conn->Execute($sql);
				} else {
                    $this->payslips[] = array('name' => $arrPayStub[$i]['empinfo']['fullname'],'status' => 2);
					$fldsppr = array();
					$fldsppr[] = "payperiod_id = '".$arrPayStub[$i]['paystubdetail']['paystubsched']['payperiod_id']."'";
					$fldsppr[] = "emp_id = '".$arrPayStub[$i]['empinfo']['emp_id']."'";
					$fldsppr[] = "paystub_id = '".$arrPayStub[$i]['paystubdetail']['paystubsched']['paystub_id']."'";
                    $fldsppr[] = "ud_id = '".$arrPayStub[$i]['empinfo']['ud_id']."'";
                    $fldsppr[] = "comp_id = '".$arrPayStub[$i]['empinfo']['comp_id']."'";
                    $fldsppr[] = "ppr_paystubdetails = '".trim(addslashes($arrPayStubSer))."'";
					$fldsppr[] = "ppr_addwho = '".AppUser::getData('user_name')."'";
					$fieldsppr = implode(", ",$fldsppr);

					$sql = "insert into payroll_paystub_report set $fieldsppr";
					$this->conn->Execute($sql);
				}
		}
        $_SESSION['payslips'] = $this->payslips;
	}
	
	/**
	 * @note: used to convert month name to num
	 * @param $monthname
	 */
	function convert_month_to_num($monthname='') {
		$month = $monthname; //or whatever
		$month_number = "";
		for($i=1;$i<=12;$i++){
			if(date("F", mktime(0, 0, 0, $i, 1, 0))==$month){
			$month_number = $i;
			break;
			}
		} 
		return $month_number;
	}
	
	/**
	 * @note Delete Loan History and Update Balance and YTD in Loan Info Table.
	 * @param $paystub_id_
	 */
	function DeleteLoanHistory($paystub_id_ = "") {
		$sqlLoanD = "SELECT loansum_id, loan_id, loansum_payment FROM loan_detail_sum WHERE paystub_id = '".$paystub_id_."'";
		$rsResultLoanD = $this->conn->Execute($sqlLoanD);
		if (count($rsResultLoanD)>0) {
			foreach ($rsResultLoanD as $keyLoanD => $valLoanD){//delete payroll_paystub_entry record.
				$sqlLoanInfo = "SELECT loan_id,loantype_id,psa_id,emp_id,loan_ytd,loan_balance,loan_total FROM loan_info WHERE loan_id='".$valLoanD['loan_id']."'";
				$varLoanInfo = $this->conn->Execute($sqlLoanInfo);
				$varLoanBalance = $varLoanInfo->fields['loan_balance'] + $valLoanD['loansum_payment'];
				$varLoanYTD = $varLoanInfo->fields['loan_ytd'] - $valLoanD['loansum_payment'];
//				echo "<br>=========LOAN DELETE==========<br>";
//				echo $varLoanBalance." Loan Balance <br>";
//				echo $varLoanYTD." Loan YTD";
//				exit;
				$flds = array();
				$flds[] = "loan_balance = '".trim(addslashes($varLoanBalance))."'";
				$flds[] = "loan_ytd = '".trim(addslashes($varLoanYTD))."'";
				$fields = implode(", ",$flds);
				$sqlLoanInfoUPdate = "UPDATE loan_info set $fields WHERE loan_id='".$valLoanD['loan_id']."'";//update loan_info before delete loan_detail_sum
				$this->conn->Execute($sqlLoanInfoUPdate);
				$sqlLoanDsum = "delete from loan_detail_sum where loansum_id='".$valLoanD['loansum_id']."'";//delete record in loan_detail_sum
				$this->conn->Execute($sqlLoanDsum);
			}
		}
	}
	
	/**
	 * @note: Get Active Loan of employee
	 * @param unknown_type $emp_id_
	 * @param unknown_type $trans_date
	 * @param unknown_type $paystub_id_
	 */
	function getEmployeeActiveGovRegLoan($emp_id_, $trans_date,$paystub_id_,$sched){
		$this->DeleteLoanHistory($paystub_id_);//delete previews record in history.
		$varDate = DATE('Y-M-D');
		$qry[] = "a.emp_id = '".$emp_id_."'";
    	$qry[] = "a.loan_suspend != '1'";
    	$qry[] = "a.loan_balance > 0";
    	$qry[] = "c.bldsched_period = '".$sched."'";
		$criteria = count($qry)>0?" WHERE ".implode(' AND ',$qry):'';
		$sql = "SELECT a.*, b.psa_name, CONCAT(SUBSTRING_INDEX(SUBSTRING_INDEX(a.loan_startdate,' ',2),' ',-1),'-',SUBSTRING_INDEX(a.loan_startdate,' ',1),'-01') as loan_startdate, SUBSTRING_INDEX(a.loan_startdate,' ',1) as month_, SUBSTRING_INDEX(SUBSTRING_INDEX(a.loan_startdate,' ',2),' ',-1) as year_
					FROM loan_info a 
					JOIN payroll_ps_account b on (a.psa_id=b.psa_id)
					JOIN period_benloanduc_sched c on (c.loan_id=a.loan_id)
					$criteria";
		$rsResult = $this->conn->Execute($sql);
		$arrData = array();
        $ctr = 0;
		while(!$rsResult->EOF){
			$monthnum = $this->convert_month_to_num($rsResult->fields['month_']);
			$startDate = $rsResult->fields['year_']."-".$monthnum."-01";
			IF($rsResult->fields['year_'] == date('Y',strtotime($trans_date))){
				 $monthnum = $this->convert_month_to_num($rsResult->fields['month_']);
				 IF($monthnum <= date('n',strtotime($trans_date))){
	             	$arrData[$ctr] = $rsResult->fields;
            		$LoanPAID = $this->saveGovRegLoanDetails($arrData[$ctr],$paystub_id_);
            		$arrData[$ctr]['loan_payperperiod'] = $LoanPAID['loan_payment']; // LOAN Amount as of current Deduction
            		$arrData[$ctr]['loan_balance'] = $LoanPAID['loan_balance']; 	 // Balance as of current Deduction
            		$arrData[$ctr]['loan_ytd'] = $LoanPAID['loan_ytd']; 			 // YTD as of current Deduction
				 }
			}ELSE{
				IF($rsResult->fields['year_'] < date('Y',strtotime($trans_date))){
	             	$arrData[$ctr] = $rsResult->fields;
	            	$LoanPAID = $this->saveGovRegLoanDetails($arrData[$ctr],$paystub_id_);
	            	$arrData[$ctr]['loan_payperperiod'] = $LoanPAID['loan_payment']; // LOAN Amount as of current Deduction
	            	$arrData[$ctr]['loan_balance'] = $LoanPAID['loan_balance']; 	 // Balance as of current Deduction
	            	$arrData[$ctr]['loan_ytd'] = $LoanPAID['loan_ytd']; 			 // YTD as of current Deduction
				}
			}
			$rsResult->MoveNext();
            $ctr++;
		}
		//printa($arrData); exit;
		return $arrData;
	}
	
	function saveGovRegLoanDetails($arrData = array(),$paystub_id_ = ""){
		$sqlgetloand = "SELECT * FROM loan_detail_sum WHERE paystub_id='".$paystub_id_."' AND loan_id='".$arrData['loan_id']."'";
		$rsResult = $this->conn->Execute($sqlgetloand);
		if(!$rsResult->EOF){
//			$loansum_id_ = $rsResult->fields['loansum_id'];
//			$totalpaid = $this->getPaidLoan($arrData['loan_id']);
		}else{
			if($arrData['loan_balance'] < $arrData['loan_payperperiod']){
				$loan_payment = $arrData['loan_balance'];
			}else{
				$loan_payment = $arrData['loan_payperperiod'];
			}
			$afterbal = $arrData['loan_balance'] - $loan_payment;
	        $afterytd = $arrData['loan_ytd'] + $loan_payment;
			
			$flds_[]="paystub_id='".$paystub_id_."'";
			$flds_[]="loan_id='".$arrData['loan_id']."'";
			$flds_[]="loansum_year='".Date('Y')."'";
			$flds_[]="loansum_payment='".$loan_payment."'";
			$flds_[]="loansum_addwho='".AppUser::getData('user_name')."'";
			$fields_ = implode(", ",$flds_);
			
			$sql = "INSERT INTO loan_detail_sum SET $fields_";
			$this->conn->Execute($sql);
			$loansum_id_ = $this->conn->Insert_ID();
			$totalpaid = $this->getPaidLoan($arrData['loan_id']);
			
			$flds = array();
			$flds[] = "loan_ytd = '".$afterytd."'";
			$flds[] = "loan_balance = '".$afterbal."'";
			$flds[] = "loan_updatewho = '".AppUser::getData('user_name')."'";
			$flds[] = "loan_updatewhen = '".date('Y-m-d')."'";
			$fields = implode(", ",$flds);
			$sql3 = "UPDATE loan_info SET $fields WHERE loan_id = '".$arrData['loan_id']."'";
            $this->conn->Execute($sql3);
            return $arrData = array("loan_payment" => $loan_payment
									,"loan_balance" => $afterbal
									,"loan_ytd" => $afterytd);
		}
	}
	
    function getPaidLoan($loan_id_ = ""){
        $sql = "select sum(loansum_payment) as total_amount from loan_detail_sum where loan_id = '".$loan_id_."'";
        $rsResultPaid = $this->conn->Execute($sql);
        if(!$rsResultPaid->EOF){
            $paid = $rsResultPaid->fields['total_amount'];
        }else{
            $paid = 0.00;
        }
        return $paid ;
    }
    
    /**
     * note: This function is used to get the Day Rate.
     * @param $SalaryDetails_
     * @BP = Basic Pay
     */
    function getBPDayRate($emp_id_ = null, $salarytype_id_ = null, $BP = 0, $payperiod_id_ = null, $cola_ = 0, $ben = array()){
//    	$this->conn->debug=1;
    	$qry[] = "a.emp_id = '".$emp_id_."'";
    	$qry[] = "d.salaryinfo_isactive = '1'";
    	if($payperiod_id_ != null){
    		$qry[] = "f.payperiod_id = '".$payperiod_id_."'";
    	}
		$criteria = count($qry)>0 ? " where ".implode(' and ',$qry) : '';
    	$sql = "SELECT *
    					FROM payroll_pps_user a 
    					JOIN payroll_pay_period_sched b on (a.pps_id=b.pps_id) 
    					JOIN payroll_pay_period f on (f.pps_id=b.pps_id)
    					JOIN salary_info d on (d.emp_id=a.emp_id)
    					JOIN payroll_comp e on (e.emp_id=a.emp_id)
    					JOIN factor_rate c on (e.fr_id=c.fr_id)
    					JOIN emp_masterfile g on (g.emp_id=a.emp_id)
    					$criteria";
    	$rsResult = $this->conn->Execute($sql);
//    	printa($rsResult); exit;
    	//get days work
    	$qry_[] = "emp_id = '".$emp_id_."'";
    	$qry_[] = "payperiod_id = '".$payperiod_id_."'";
    	$qry_[] = "tatbl_id = '5'";
		$criteria_ = count($qry_)>0 ? " where ".implode(' and ',$qry_) : '';
    	$sqlWD = "SELECT emp_tarec_nohrday from ta_emp_rec a $criteria_";
    	$wdResult = $this->conn->Execute($sqlWD);
    	
    	/*compute rate per day, per hour, per sec per employee */
		/**	if ($salarytype_id_==5) {
				//convert monthly rate
				$rateperday = (($BP*12)/$rsResult->fields['fr_dayperyear']);
				$rateperhour = (($rateperday)/$rsResult->fields['fr_hrperday']);
				$ratepersec = (($rateperhour)/3600);
				$colaperday = (($cola_*12)/$rsResult->fields['fr_dayperyear']);
				$colaperhour = $colaperday/$rsResult->fields['fr_hrperday'];
				$colapersec = $colaperhour/3600;
			} elseif ($salarytype_id_==1) {
				//convert hourly rate 
				$rateperday = ($BP*$rsResult->fields['fr_hrperday']);
				$rateperhour = $BP;
				$ratepersec = ($BP/3600);
				$colaperday = ($cola_*$rsResult->fields['fr_hrperday']);
				$colaperhour = $colaperday/$rsResult->fields['fr_hrperday'];
				$colapersec = $colaperhour/3600;
			} elseif ($salarytype_id_==3) {
				//convert weekly rate
				$rateperday = ($BP/$rsResult->fields['fr_dayperweek']);
				$rateperhour = (($rateperday)/$rsResult->fields['fr_hrperday']);
				$ratepersec = (($rateperhour)/3600);
				$colaperday = ($cola_/$rsResult->fields['fr_dayperweek']);
				$colaperhour = $colaperday/$rsResult->fields['fr_hrperday'];
				$colapersec = $colaperhour/3600;
			} elseif ($salarytype_id_==6) {
				//convert annual rate to second
				$rateperday = ($BP/$rsResult->fields['fr_dayperyear']);
				$rateperhour = (($rateperday)/$rsResult->fields['fr_hrperday']);
				$ratepersec = (($rateperhour)/3600);
				$colaperday = ($cola_/$rsResult->fields['fr_dayperyear']);
				$colaperhour = $colaperday/$rsResult->fields['fr_hrperday'];
				$colapersec = $colaperhour/3600;
			} elseif ($salarytype_id_==4) {
				//convert bi-weekly rate
				$rateperday = ($BP/($rsResult->fields['fr_dayperweek'] * 2));
				$rateperhour = (($rateperday)/$rsResult->fields['fr_hrperday']);
				$ratepersec = (($rateperhour)/3600);
				$colaperday = ($cola_/($rsResult->fields['fr_dayperweek'] * 2));
				$colaperhour = $colaperday/$rsResult->fields['fr_hrperday'];
				$colapersec = $colaperhour/3600;
			} else {
				//convert daily rate
				$rsResult->fields['fr_hrperday'];
				$rateperday = $BP;
				$rateperhour = ($rateperday/$rsResult->fields['fr_hrperday']);
				$ratepersec = (($rateperhour)/3600);
				$colaperday = $cola_;
				$colaperhour = $colaperday/$rsResult->fields['fr_hrperday'];
				$colapersec = $colaperhour/3600;
			}**/
    		$employeeRates = $this->computeRates($BP, $cola_, $rsResult->fields['fr_hrperday'], $rsResult->fields['fr_dayperweek'], $rsResult->fields['fr_dayperyear'], $rsResult->fields['fr_hrperweek'], $salarytype_id_);
			$rateperday = $employeeRates['rateperday'];
			$rateperhour = $employeeRates['rateperhour'];
			$ratepersec = $employeeRates['ratepersec'];
			$colaperday = $employeeRates['colaperday'];
			$colaperhour = $employeeRates['colaperhour'];
			$colapersec = $employeeRates['colapersec'];
			
			/* @author: IR Salvador
			*	Prorate Mid Month Allocation Method (Actual Days)
			*	Based on Hire Date, Resigned Date or Salary Effective Date
			**/
			// for formatting of date
			$hireDate = (($rsResult->fields['emp_hiredate'] == "" OR empty($rsResult->fields['emp_hiredate'])) ? "" : new DateTime($rsResult->fields['emp_hiredate']));
			$ppStartDate = new DateTime($rsResult->fields['payperiod_start_date']);
			$ppEndDate = new DateTime($rsResult->fields['payperiod_end_date']);
			$resDate = (($rsResult->fields['emp_resigndate'] == "" OR empty($rsResult->fields['emp_resigndate'])) ? "" : new DateTime($rsResult->fields['emp_resigndate']));

			// for newly-hired employees
			if(!empty($hireDate)){
				if((strtotime($hireDate->format("Y-m-d")) > strtotime($ppStartDate->format("Y-m-d"))) && (strtotime($hireDate->format("Y-m-d")) <= strtotime($ppEndDate->format("Y-m-d")))){
					$days = $this->countDays($rsResult->fields['emp_hiredate'], $rsResult->fields['payperiod_end_date']);
					$proRateDays = 0;
					for($d=0;$d<=$days;$d++){
						$day =  new DateTime(date('Y-m-d', strtotime($rsResult->fields['emp_hiredate'].' +'.$d.' day')));
						if(!in_array($day->format('D'), $this->validateDaysByFactorRate($rsResult->fields['fr_dayperweek']))){
							$proRateDays++;
						}
					}
					$proRateBP = $rateperday*$proRateDays;
				}
			}
			// for resigned employees
			if(!empty($resDate)){
	    		if((strtotime($resDate->format("Y-m-d")) >= strtotime($ppStartDate->format("Y-m-d"))) && (strtotime($resDate->format("Y-m-d")) < strtotime($ppEndDate->format("Y-m-d")))){
	    			$days = $this->countDays($rsResult->fields['payperiod_start_date'], $rsResult->fields['emp_resigndate']);
					$proRateDays = 0;
					for($d=0;$d<=$days;$d++){
						$day =  new DateTime(date('Y-m-d', strtotime($rsResult->fields['payperiod_start_date'].' +'.$d.' day')));
						if(!in_array($day->format('D'), $this->validateDaysByFactorRate($rsResult->fields['fr_dayperweek']))){
							$proRateDays++;
						}
					}
					$proRateBP = $rateperday*$proRateDays;
				}
			}
			// for salary increase
			if(count($ben) > 0){
				if($proRateBP == 0){
					$benefit = $this->getLastBenefit($ben['psa_id'],$emp_id_);
					$days = $this->countDays($ben['ben_startdate'], $rsResult->fields['payperiod_end_date']);
					$proRateDays = 0;
					for($d=0;$d<=$days;$d++){
						$day =  new DateTime(date('Y-m-d', strtotime($ben['ben_startdate'].' +'.$d.' day')));
						if(!in_array($day->format('D'), $this->validateDaysByFactorRate($rsResult->fields['fr_dayperweek']))){
							$proRateDays++;
						}
					}
					$afterProRateBP = $rateperday*$proRateDays;
					$endDayBefore =  new DateTime(date('Y-m-d', strtotime($ben['ben_startdate'].' - 1 day')));
					$daysBefore = $this->countDays($rsResult->fields['payperiod_start_date'], $endDayBefore->format('Y-m-d'));
					
					$proRateDaysBefore = 0;
			        for($bd=0;$bd<=$daysBefore;$bd++){
						$dayBefore =  new DateTime(date('Y-m-d', strtotime($rsResult->fields['payperiod_start_date'].' +'.$bd.' day')));
						if(!in_array($dayBefore->format('D'), $this->validateDaysByFactorRate($rsResult->fields['fr_dayperweek']))){
							$proRateDaysBefore++;
						}
					}
	
					$benRate = $this->computeRates($benefit['ben_amount'], 0, $rsResult->fields['fr_hrperday'], $rsResult->fields['fr_dayperweek'], $rsResult->fields['fr_dayperyear'], $rsResult->fields['fr_hrperweek'], $salarytype_id_);
					$beforeProRateBP = $benRate['rateperday']*$proRateDaysBefore;
					$proRateBP = $afterProRateBP + $beforeProRateBP;
				}
			} else {
				if(strtotime($rsResult->fields['salaryinfo_effectdate']) != strtotime($ppStartDate->format("Y-m-d")) && $proRateBP == 0){
					if(($this->validateSalaryInc($emp_id_) > 1) && ((strtotime($rsResult->fields['salaryinfo_effectdate']) >= strtotime($ppStartDate->format("Y-m-d"))) && (strtotime($rsResult->fields['salaryinfo_effectdate']) <= strtotime($ppEndDate->format("Y-m-d"))))){
						$days = $this->countDays($rsResult->fields['salaryinfo_effectdate'], $rsResult->fields['payperiod_end_date']);
						$proRateDays = 0;
						for($d=0;$d<=$days;$d++){
							$day =  new DateTime(date('Y-m-d', strtotime($rsResult->fields['salaryinfo_effectdate'].' +'.$d.' day')));
							if(!in_array($day->format('D'), $this->validateDaysByFactorRate($rsResult->fields['fr_dayperweek']))){
								$proRateDays++;
							}
						}
		//				echo $proRateDays.'<br>'; echo $rateperday.'<br>'; 
						$afterProRateBP = $rateperday*$proRateDays;
						//echo $afterProRateBP; exit;
						$endDayBefore =  new DateTime(date('Y-m-d', strtotime($rsResult->fields['salaryinfo_effectdate'].' - 1 day')));
						$daysBefore = $this->countDays($rsResult->fields['payperiod_start_date'], $endDayBefore->format('Y-m-d'));
						
						$proRateDaysBefore = 0;
		        		for($bd=0;$bd<=$daysBefore;$bd++){
							$dayBefore =  new DateTime(date('Y-m-d', strtotime($rsResult->fields['payperiod_start_date'].' +'.$bd.' day')));
							if(!in_array($dayBefore->format('D'), $this->validateDaysByFactorRate($rsResult->fields['fr_dayperweek']))){
								$proRateDaysBefore++;
							}
						}
						$beforeProRateBP = $this->getLastSalaryRate($emp_id_)*$proRateDaysBefore;
						$proRateBP = $afterProRateBP + $beforeProRateBP;
					}
				}
			}
			/* compute total working days*/
			$days = $this->countDays($rsResult->fields['payperiod_start_date'], $rsResult->fields['payperiod_end_date']);
    		for($d=0;$d<=$days;$d++){
					$day =  new DateTime(date('Y-m-d', strtotime($rsResult->fields['payperiod_start_date'].' +'.$d.' day')));
					if(!in_array($day->format('D'), $this->validateDaysByFactorRate($rsResult->fields['fr_dayperweek']))){
						$totalDays++;
					}
				}
/**echo '<br>'.$totalDays.'<br>';
echo "BEFORE DAYS: ". $proRateDaysBefore;
echo "<br>";
echo "BEFORE RATE: ". $beforeProRateBP;
echo "<br><br>";
echo "AFTER DAYS: ". $proRateDays;
echo "<br>";
echo "AFTER RATE: ". $afterProRateBP;
echo "<br><br>";
echo "TOTAL RATE: ". $proRateBP;
exit;**/
			// end of prorate computation
			if($proRateBP > 0){ // if pro-rated PayPeriodBP should be the computed pro-rated
				$PayPeriodBP = $proRateBP;
			} else { // else regular computation will apply
				//compute regular time base to payperiod type.
				//eg. semi-monthy, monthly, daily ect.
	            //Daily
				if ($rsResult->fields['salaryclass_id'] == 1) {
						$PayPeriodBP = $rateperday;
						$PayPeriodCOLA = $colaperday;
	     
	            //weekly
				}elseif ($rsResult->fields['salaryclass_id'] == 2){
					if($salarytype_id_==5){
						//monthly
						$PayPeriodBP = (($BP*12)/52);
						$PayPeriodCOLA = (($cola_*12)/52);
					}elseif ($salarytype_id_==3){
						//weekly
						$PayPeriodBP = $BP;
						$PayPeriodCOLA = $cola_;
					}elseif ($salarytype_id_==6){
						//annual
						$PayPeriodBP = $BP/52;
						$PayPeriodCOLA = $cola_/52;
					}elseif ($salarytype_id_==4){
						//bi-weekly
						$PayPeriodBP = $BP/2;
						$PayPeriodCOLA = $cola_/2;
					}else{
						//daily & hourly
						$PayPeriodBP = $rateperday * $wdResult->fields['emp_tarec_nohrday'];
						$PayPeriodCOLA = $colaperday * $wdResult->fields['emp_tarec_nohrday'];
					}
				
				//bi-weekly
				}elseif ($rsResult->fields['salaryclass_id'] == 3){
					if($salarytype_id_==5){
						//monthly
						$PayPeriodBP = (($BP*12)/26);
						$PayPeriodCOLA = (($cola_*12)/26);
					}elseif ($salarytype_id_==3){
						//weekly
						$PayPeriodBP = $BP*2;
						$PayPeriodCOLA = $cola_*2;
					}elseif ($salarytype_id_==6){
						//annual
						$PayPeriodBP = $BP/26;
						$PayPeriodCOLA = $cola_/26;
					}elseif ($salarytype_id_==4){
						//bi-weekly
						$PayPeriodBP = $BP;
						$PayPeriodCOLA = $cola_;
					}else{
						//daily & hourly
						$PayPeriodBP = ($rateperday * $wdResult->fields['emp_tarec_nohrday']);
						$PayPeriodCOLA = $colaperday * $wdResult->fields['emp_tarec_nohrday'];
					}
				
				// semi-monthly
				}elseif ($rsResult->fields['salaryclass_id'] == 4){
					if($salarytype_id_==5){
						//monthly
						if($rsResult->fields['emp_hiredate'] > date("Y-m-d",$rsResult->fields['payperiod_start_date'])){
	//						@todo: for back pay 
	//						$vardate = $rsResult->fields['emp_hiredate'] - $rsResult->fields['payperiod_start_date'];
	//						echo $rsResult->fields['emp_hiredate'];
	//						echo "<br>"; 
	//						echo date('Y-m-d',$rsResult->fields['payperiod_start_date']);
	//						echo "<br>";
	//						echo $vardate;
	//						echo "<br>";
	//						echo "hi";
	//						exit;
							$PayPeriodBP = ($BP/2);
							$PayPeriodCOLA = $cola_/2;
						}else{
							$PayPeriodBP = ($BP/2);
							$PayPeriodCOLA = $cola_/2;
						}
					}elseif ($salarytype_id_==3){
						//weekly
						$PayPeriodBP = $BP*2;
						$PayPeriodCOLA = $cola_*2;
					}elseif ($salarytype_id_==6){
						//annual
						$PayPeriodBP = (($BP/12)/2);
						$PayPeriodCOLA = (($cola_/12)/2);
					}elseif ($salarytype_id_==4){
						//bi-weekly
						$PayPeriodBP = $BP;
						$PayPeriodCOLA = $cola_;
					}else{
						//daily & hourly
						$PayPeriodBP = ($rateperday * $wdResult->fields['emp_tarec_nohrday']);
						$PayPeriodCOLA = $colaperday * $wdResult->fields['emp_tarec_nohrday'];
					}
	            //monthly
				}elseif ($rsResult->fields['salaryclass_id'] == 5){
					if($salarytype_id_==5){
						//monthly
						$PayPeriodBP = $BP;
						$PayPeriodCOLA = $cola_;
					}elseif ($salarytype_id_==3){
						//weekly
						$PayPeriodBP = (($BP*12)/52);
						$PayPeriodCOLA = (($cola_*12)/52);
					}elseif ($salarytype_id_==6){
						//annual
						$PayPeriodBP = $BP/12;
						$PayPeriodCOLA = $cola_/12;
					}elseif ($salarytype_id_==4){
						//bi-weekly
						$PayPeriodBP = (($BP*12)/13);
						$PayPeriodCOLA = (($cola_*12)/13);
					}else{
						//daily & hourly
						$PayPeriodBP = ($rateperday * $wdResult->fields['emp_tarec_nohrday']);
						$PayPeriodCOLA = $colaperday * $wdResult->fields['emp_tarec_nohrday'];
					}
				//annual	
				}else {
					if($salarytype_id_==5){
						//monthly
						$PayPeriodBP = $BP*12;
						$PayPeriodCOLA = $cola_*12;
					}elseif ($salarytype_id_==3){
						//weekly
						$PayPeriodBP = $BP*52;
						$PayPeriodCOLA = $cola_*52;
					}elseif ($salarytype_id_==6){
						//annual
						$PayPeriodBP = $BP;
						$PayPeriodCOLA = $cola_;
					}elseif ($salarytype_id_==4){
						//bi-weekly
						$PayPeriodBP = $BP*26;
						$PayPeriodCOLA = $cola_*26;
					}else{
						//daily & hourly
						$PayPeriodBP = ($rateperday * $rsResult->fields['fr_dayperyear']);
						$PayPeriodCOLA = $colaperday * $rsResult->fields['fr_dayperyear'];
					}
				}
			}
//			return the day rate value
			$objClsMngeDecimal = new Application();
			return $DayRate = array("rateperday" => Number_Format($rateperday,$objClsMngeDecimal->getGeneralDecimalSettings(),'.','')
									,"rateperhour" => Number_Format($rateperhour,$objClsMngeDecimal->getGeneralDecimalSettings(),'.','')
									,"ratepersec" => Number_Format($ratepersec,$objClsMngeDecimal->getGeneralDecimalSettings(),'.','')
									,"PayPeriodBP" => Number_Format($PayPeriodBP,$objClsMngeDecimal->getGeneralDecimalSettings(),'.','')
									,"PayPeriodCOLA" => Number_Format($PayPeriodCOLA,$objClsMngeDecimal->getGeneralDecimalSettings(),'.','')
									,"colaperday" => Number_Format($colaperday,$objClsMngeDecimal->getGeneralDecimalSettings(),'.','')
									,"colaperhour" => Number_Format($colaperhour,$objClsMngeDecimal->getGeneralDecimalSettings(),'.','')
									,"colapersec" => Number_Format( $colapersec,$objClsMngeDecimal->getGeneralDecimalSettings(),'.','')
									,"nodays" => Number_Format($wdResult->fields['emp_tarec_nohrday'],$objClsMngeDecimal->getGeneralDecimalSettings(),'.','')
									);
    }
    
    /**
     * @note: Get OT Record.
     * @param $emp_id_
     * @param $payperiod_id_
     */
	function getTotalOTByPayPeriod($emp_id_ = null, $payperiod_id_ = null, $ot_id_ = 0) {
		$arrData = array();
		$qry = array();
		if (is_null($emp_id_)) { return $arrData; }
		if (is_null($payperiod_id_) || empty($payperiod_id_)) { return $arrData; }

		$qry[] = "a.emp_id=$emp_id_";
		$qry[] = "a.payperiod_id=$payperiod_id_";
		$qry[] = "b.ot_id=$ot_id_";
//		$groupBy = "group by a.payperiod_id";
		$criteria = count($qry)>0 ? " WHERE ".implode(' AND ',$qry) : '';
		$sql = "SELECT otr.otr_name, otr.otr_factor, a.*, c.ot_istax
				FROM ot_record a 
				JOIN ot_tr b on (a.otr_id=b.otr_id)
				JOIN ot_rates otr on (otr.otr_id=a.otr_id)
				JOIN ot_tbl c on (c.ot_id=b.ot_id)
				$criteria";
		$rsResult = $this->conn->Execute($sql);		
		while (!$rsResult->EOF) {
			$arrData[] = $rsResult->fields;
			$rsResult->MoveNext();
		}		
		return $arrData;		
	}
	
    /**
     * @note: Get TA Record.
     * @param $emp_id_
     * @param $payperiod_id_
     */
	function getTotalTAByPayPeriod($emp_id_ = null, $payperiod_id_ = null) {
		$arrData = array();
		$qry = array();
		if (is_null($emp_id_)) { return $arrData; }
		if (is_null($payperiod_id_) || empty($payperiod_id_)) { return $arrData; }

		$qry[] = "a.emp_id = $emp_id_";
		$qry[] = "a.payperiod_id = $payperiod_id_";
		$qry[] = "a.tatbl_id != '5'";
//		$groupBy = "group by a.payperiod_id";
		
		$criteria = count($qry)>0 ? " where ".implode(' and ',$qry) : '';
		$sql = "select b.tatbl_name,b.tatbl_rate,a.*
				from ta_emp_rec a 
				inner join ta_tbl b on (b.tatbl_id=a.tatbl_id)
				$criteria";
		$rsResult = $this->conn->Execute($sql);		
		while (!$rsResult->EOF) {
			$arrData[] = $rsResult->fields;
			$rsResult->MoveNext();
		}		
		return $arrData;		
	}
	
	/**
     * @note: Get Leave Record.
     * @param $emp_id_
     */
	function getLeaveRecord($emp_id_ = null,$dailyrate = 0) {
		$objClsMngeDecimal = new Application();
		$arrData = array();
		$qry = array();
		if (is_null($emp_id_)) { return $arrData; }

		$qry[] = "a.emp_id=$emp_id_";
//		$groupBy = "group by a.payperiod_id";
		
		$criteria = count($qry)>0 ? " where ".implode(' and ',$qry) : '';
		$sql = "SELECT a.empleave_id,a.leave_id,b.leave_name,a.empleave_used_day,a.empleave_available_day,a.empleave_substitute,a.empleave_credit,a.empleave_stat,b.leave_conv_cash
				FROM emp_leave a
				JOIN leave_type b on (b.leave_id=a.leave_id) 
				$criteria";
		$rsResult = $this->conn->Execute($sql);
		$x = 0;	
		while (!$rsResult->EOF) {
			$arrData[$x] = $rsResult->fields;
			$arrData[$x]['amount'] = Number_Format($rsResult->fields['empleave_used_day'] * $dailyrate,$objClsMngeDecimal->getFinalDecimalSettings(),'.','');
			$rsResult->MoveNext();
			$x++;
		}		
		return $arrData;		
	}
	
	/**
	 * @note: To get Monthly Record for Satutory Report and Computation.
	 * @param $emp_id_
	 * @param $psa_id_
	 */
	function getMonthlyRecordStat($emp_id_ = null, $psa_id_ = null, $payperiod_period_ = '', $payperiod_period_year_='', $payperiod_id_='', $stat=0){
		if (is_null($emp_id_)) { return $arrData; }
		if (is_null($psa_id_) || empty($psa_id_)) { return $arrData; }
		IF($stat > 0){
			$qry[]="a.emp_id='".$emp_id_."'";
			$qry[]="b.payperiod_type=1";
			$qry[]="b.payperiod_period = '".$payperiod_period_."'";
			$qry[]="b.payperiod_period_year = '".$payperiod_period_year_."'";
			$qry[]="b.payperiod_id != '".$payperiod_id_."'";
		}ELSE{
			$qry[]="a.emp_id='".$emp_id_."'";
			$qry[]="b.payperiod_period != '".$payperiod_period_."'";
		}
		// put all query array into one string criteria
		$criteria = " WHERE ".implode(" AND ",$qry);
		//$qrySSS = "SELECT * FROM payroll_pay_stub a JOIN payroll_pay_period b on (a.payperiod_id=b.payperiod_id) $criteria ORDER BY a.paystub_id desc limit 1,1";
		$qrySSS = "SELECT * FROM payroll_pay_stub a JOIN payroll_pay_period b on (a.payperiod_id=b.payperiod_id) $criteria";
		$varPPS = $this->conn->Execute($qrySSS);
		if(!$varPPS->EOF){
			$varPPS_ = $varPPS->fields;
		}
		$qryPPDsss = "Select * from payroll_paystub_entry where psa_id = '".$psa_id_."' and paystub_id = '".$varPPS_['paystub_id']."'";
		$varPPD = $this->conn->Execute($qryPPDsss);
		if(!$varPPD->EOF){
			$varPPD_ = $varPPD->fields;
			return $varPPD_;
		}
	}
	
	function getDeductionSched($emp_id_ = null, $dec_id_ = null, $period_ = null, $schedType = 0){
		//@editedby: jim(20121008)
		//@note: to cater other schedule, like loan & gov sched
		IF($schedType=='1'){//Loan
			$select_qry = "*";
			$qry[]="s.emp_id='".$emp_id_."'";
			$qry[]="s.loan_id != '0'";
			$qry[]="s.loan_id = '".$dec_id_."'";
		}ELSE{
			$select_qry = "t.dec_code,t.dec_name,s.bldsched_period";
			$join_qry = "JOIN deduction_type t on (t.dec_id=s.empdd_id)";
			$qry[]="s.emp_id='".$emp_id_."'";
			$qry[]="s.empdd_id != '0'";
			$qry[]="s.empdd_id = '".$dec_id_."'";
		}
		// put all query array into one string criteria
		$criteria = " where ".implode(" and ",$qry);
		$sql = "SELECT $select_qry	
				FROM period_benloanduc_sched s
				$join_qry
				$criteria";
		$rsResult = $this->conn->Execute($sql);
		while (!$rsResult->EOF) {
			$arrData[] = $rsResult->fields;
			$rsResult->MoveNext();
		}		
		return $arrData;
	}
	
	function validateFormulaExists($psa_id,$emp_id){
		$sql = "select ben_isfixed from payroll_ps_account a inner join emp_benefits b on (b.psa_id=a.psa_id) where a.psa_id=? and emp_id = ? and psa_formula IS NOT NULL";
		$r = $this->conn->Execute($sql,array($psa_id,$emp_id));
		if(!$r->EOF){
			if($r->fields['ben_isfixed']){
				$val = true;
			} else {
				$val = false;
			}
		} else {
			$val = false;
		}
		return $val;
	}
	
	function doGetFormulaVal($emp_id, $psa_id, $paystub_id, $payperiod_id){
		$sql = "select psa_formula, psa_formula_el from payroll_ps_account where psa_id = ?";
		$r = $this->conn->Execute($sql,$psa_id);
		$el = unserialize($r->fields['psa_formula_el']);
		if(is_array($el)){
			foreach($el as $e){
				if(!is_numeric($e)){
					$eSql = "select cfhead_id, app_fkey_query, app_fkey_vars, app_fkey_result from app_formula_keywords where app_fkey_name = ?";
					$eResult = $this->conn->Execute($eSql,$e);
					if($eResult->fields['cfhead_id'] > 0 || $eResult->fields['cfhead_id'] != NULL){
						$updateCFsql = "update cf_detail set paystub_id=? where payperiod_id=? and emp_id=?";
						$this->conn->Execute($updateCFsql,array($paystub_id,$payperiod_id,$emp_id));
					}
					eval("\$arr[\$e] = ".$eResult->fields['app_fkey_vars'].";");
					$rVal = $this->conn->Execute($eResult->fields['app_fkey_query'], $arr[$e]);
					if(!$rVal->EOF){
						$strReplaceVal[] = $rVal->fields[$eResult->fields['app_fkey_result']];
					} else {$strReplaceVal[] = 0;}
				} else {
					$strReplaceVal[] = $e;
				}
			}
		}
		$strFormula = str_replace($el,$strReplaceVal,$r->fields['psa_formula']);
		eval("\$returnVal = ".$strFormula.";");
		return $returnVal;
	}
	
	function doSaveTempPayStub($paystub_id, $psa_id, $amount){
		$sql = "SELECT * FROM z_formula_temp WHERE paystub_id = '$paystub_id' and psa_id = '$psa_id'";
		$rsResult = $this->conn->Execute($sql);
		if ($rsResult->EOF) {
			$sql_ = "INSERT INTO z_formula_temp (amount,paystub_id,psa_id) VALUES (?,?,?)";
		} else {
			$sql_ = "UPDATE z_formula_temp SET amount=? WHERE paystub_id=? and psa_id=?";
		}
		$result_ = $this->conn->Execute($sql_,array($amount,$paystub_id,$psa_id));
	}
	
	function countDays($date1 = null, $date2 = null){
		IF($this->formatDateYmd($date1)==$this->formatDateYmd($date2)){
			$days = 1;
		} ELSE {
			$diff = abs(strtotime($date2) - strtotime($date1));
			$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
		}
		return $days;
	}
	
	function validateDaysByFactorRate($fr_id_ = null){
		switch($fr_id_) {
			case 5: return array("Sat","Sun");
			case 6: return array("Sun");
			default: return array();
		}
	}
	
	function formatDateYmd($date_ = null){
		$date = new DateTime($date_);
		return $date->format('Y-m-d');
	}
	
	function validateSalaryInc($emp_id_ = null){
		$sql = "select count(*) as num from salary_info where emp_id='$emp_id_'";
		$rsResult = $this->conn->Execute($sql);
		return $rsResult->fields['num'];
	}
	
	function getLastSalaryRate($emp_id_ = null){
		$sql = "SELECT * FROM salary_info a
				JOIN payroll_comp b on (b.emp_id=a.emp_id)
				JOIN factor_rate c on (c.fr_id=b.fr_id)
				where a.emp_id='$emp_id_' ORDER BY salaryinfo_effectdate DESC LIMIT 1,1";
		$rsResult = $this->conn->Execute($sql);
		/*compute rate per day, per hour, per sec per employee */
		if ($rsResult->fields['salarytype_id']==5) {
			//convert monthly rate
//			echo $rsResult->fields['salaryinfo_basicrate'];
//			echo "<br>";
//			echo $rsResult->fields['fr_dayperyear'];
//			echo "<br>";
			$rateperday = (($rsResult->fields['salaryinfo_basicrate']*12)/$rsResult->fields['fr_dayperyear']);
		} elseif ($rsResult->fields['salarytype_id']==1) {
			//convert hourly rate 
			$rateperday = ($rsResult->fields['salaryinfo_basicrate']*$rsResult->fields['fr_hrperday']);
		} elseif ($rsResult->fields['salarytype_id']==3) {
			//convert weekly rate
			$rateperday = ($rsResult->fields['salaryinfo_basicrate']/$rsResult->fields['fr_dayperweek']);
		} elseif ($rsResult->fields['salarytype_id']==6) {
			//convert annual rate to second
			$rateperday = ($rsResult->fields['salaryinfo_basicrate']/$rsResult->fields['fr_dayperyear']);
		} elseif ($rsResult->fields['salarytype_id']==4) {
			//convert bi-weekly rate
			$rateperday = ($rsResult->fields['salaryinfo_basicrate']/($rsResult->fields['fr_dayperweek'] * 2));
		} else {
			//convert daily 
			$rateperday = $rsResult->fields['salaryinfo_basicrate'];
		}
		return $rateperday;
	}
	
	function getLastBenefit($psa_id_ = null, $emp_id_ = null){
		$sql = "SELECT * FROM emp_benefits WHERE psa_id=? AND emp_id=?";
		$rsResult = $this->conn->GetAll($sql, array($psa_id_,$emp_id_));
		return $rsResult[count($rsResult)-2];
	}
	/**
	 * 
	 * compute rate per day, per hour, per sec of employee
	 * @author IR Salvador
	 * @param decimal $BP
	 * @param decimal $cola_
	 * @param decimal $fr_hrperday
	 * @param decimal $fr_dayperweek
	 * @param decimal $fr_dayperyear
	 * @param decimal $fr_hrperweek
	 * @param decimal $salarytype_id_
	 */
	function computeRates($BP = 0, $cola_ = 0, $fr_hrperday = 0, $fr_dayperweek = 0, $fr_dayperyear = 0, $fr_hrperweek = 0, $salarytype_id_ = null){
		if ($salarytype_id_==5) {
				//convert monthly rate
				$rateperday = (($BP*12)/$fr_dayperyear);
				$rateperhour = (($rateperday)/$fr_hrperday);
				$ratepersec = (($rateperhour)/3600);
				$colaperday = (($cola_*12)/$fr_dayperyear);
				$colaperhour = $colaperday/$fr_hrperday;
				$colapersec = $colaperhour/3600;
			} elseif ($salarytype_id_==1) {
				//convert hourly rate 
				$rateperday = ($BP*$fr_hrperday);
				$rateperhour = $BP;
				$ratepersec = ($BP/3600);
				$colaperday = ($cola_*$fr_hrperday);
				$colaperhour = $colaperday/$fr_hrperday;
				$colapersec = $colaperhour/3600;
			} elseif ($salarytype_id_==3) {
				//convert weekly rate
				$rateperday = ($BP/$fr_dayperweek);
				$rateperhour = (($rateperday)/$fr_hrperday);
				$ratepersec = (($rateperhour)/3600);
				$colaperday = ($cola_/$fr_dayperweek);
				$colaperhour = $colaperday/$fr_hrperday;
				$colapersec = $colaperhour/3600;
			} elseif ($salarytype_id_==6) {
				//convert annual rate to second
				$rateperday = ($BP/$fr_dayperyear);
				$rateperhour = (($rateperday)/$fr_hrperday);
				$ratepersec = (($rateperhour)/3600);
				$colaperday = ($cola_/$fr_dayperyear);
				$colaperhour = $colaperday/$fr_hrperday;
				$colapersec = $colaperhour/3600;
			} elseif ($salarytype_id_==4) {
				//convert bi-weekly rate
				$rateperday = ($BP/($fr_dayperweek * 2));
				$rateperhour = (($rateperday)/$fr_hrperday);
				$ratepersec = (($rateperhour)/3600);
				$colaperday = ($cola_/($fr_dayperweek * 2));
				$colaperhour = $colaperday/$fr_hrperday;
				$colapersec = $colaperhour/3600;
			} else {
				//convert daily rate
				$rateperday = $BP;
				$rateperhour = ($rateperday/$fr_hrperday);
				$ratepersec = (($rateperhour)/3600);
				$colaperday = $cola_;
				$colaperhour = $colaperday/$fr_hrperday;
				$colapersec = $colaperhour/3600;
			}
			$arr = array(
						'rateperday' =>$rateperday
						,'rateperhour' =>$rateperhour
						,'ratepersec' =>$ratepersec
						,'colaperday' =>$colaperday
						,'colaperhour' =>$colaperhour
						,'colapersec' =>$colapersec
					);
			return $arr;
	}
	
/**
	 * @note: This is used to Generate Payslip
	 * @param $payperiod_id_
	 * @param $pData
	 * @return $arrPayStub or $retval
	 */
	function doSaveGeneReportOtherPay($payperiod_id_ = null, $pData){
		$objClsMngeDecimal = new Application();
		$retval = "";
		IF (is_null($payperiod_id_ )){ return $retval; }
		IF (is_null($pData)){ return $retval; }
		$ctr = 0;
		do {$payStubSerialize = array();
			$paystub_id = $this->doSavePayStub($pData['chkAttend'][$ctr],$payperiod_id_); // Save Paystub
			$empinfo = "SELECT *,txcep.taxep_name,a.emp_id,a.comp_id, CONCAT(g.pi_fname,' ',UPPER(SUBSTRING(g.pi_mname,1,1)),'. ',g.pi_lname) as fullname, bank.bankiemp_acct_no, blist.banklist_name, DATE_FORMAT(f.payperiod_trans_date,'%d') as ppdTransDate, DATE_FORMAT(f.payperiod_start_date,'%d') as ppdStartDate 
							FROM emp_masterfile a
							JOIN salary_info b on (b.emp_id=a.emp_id)
							JOIN payroll_pps_user c on (c.emp_id = a.emp_id)
							JOIN payroll_pay_period_sched d on (d.pps_id=c.pps_id)
							JOIN payroll_comp pcal on (a.emp_id=pcal.emp_id)
							JOIN factor_rate e on (e.fr_id=pcal.fr_id)
							JOIN payroll_pay_period f on (f.pps_id=d.pps_id)
							JOIN emp_personal_info g on (g.pi_id=a.pi_id)
							JOIN company_info i on (i.comp_id=a.comp_id)
							LEFT JOIN app_wagerate wrate on (wrate.wrate_id=e.wrate_id)
							LEFT JOIN emp_position h on (h.post_id=a.post_id)
							LEFT JOIN app_userdept j on (j.ud_id=a.ud_id)
							LEFT JOIN emp_type z on (z.emptype_id=a.emptype_id)
							LEFT JOIN bank_infoemp bank on (bank.emp_id=a.emp_id)
							LEFT JOIN bank_list blist on blist.banklist_id=bank.banklist_id
							LEFT JOIN tax_excep txcep on (txcep.taxep_id=a.taxep_id) 
							WHERE a.emp_id='".$pData['chkAttend'][$ctr]."' and c.pps_id='".$_GET['ppsched']."' and f.payperiod_id='".$payperiod_id_."' and b.salaryinfo_isactive='1'";
			$rsResult = $this->conn->Execute($empinfo);
//			printa($rsResult->fields); exit;
			// get the Basic Pay.
			//-------------------------------------------------------->>

			// Call the getAmendments function
			//-------------------------------------------------------->>
			$amendments = $this->getAmendments($pData['chkAttend'][$ctr],$rsResult->fields['payperiod_trans_date'],$rsResult->fields['payperiod_start_date'],$rsResult->fields['payperiod_end_date'],$paystub_id,$payperiod_id_);
			$totalTSEarningAmendments[$ctr] = 0;   // Earning subject to Tax & Statutory
			$totalTEarningAmendments[$ctr] = 0;    // Earning subject to Tax
			$totalSEarningAmendments[$ctr] = 0;    // Earning subject to Statutory
			$totalNTSEarningAmendments[$ctr] = 0;  // Earning Non Tax & Statutory
			$totalTSDeductionAmendments[$ctr] = 0; // Deduction subject to Tax & Statutory
			$totalTDeductionAmendments[$ctr] = 0;  // Deduction subject to Tax
			$totalSDeductionAmendments[$ctr] = 0;  // Deduction subject to Statutory
			$totalNTSDeductionAmendments[$ctr] = 0;// Deduction Non Tax & Statutory
			//printa($amendments);exit;
			$LeaveRecords = $this->getLeaveRecord($pData['chkAttend'][$ctr],$rateperday[$ctr]);
			//sum up the amount of Amendments
			if (count($amendments)>0) {
				foreach ($amendments as $keyamend => $valamend){
//				$this->doSavePayStubEntry($paystub_id,$amendments[$r]['psa_id'], $amendments[$r]['psamend_amount'], $amendments[$r]['psamend_rate'],$amendments[$r]['psamend_unit']);
					if ($valamend['psa_type']==1) { // Earning
						if($valamend['psa_statutory'] == 1){
							if($valamend['psa_tax'] == 1){
								$totalTSEarningAmendments[$ctr] += $valamend['amendemp_amount'];   //subject to Tax & Statutory
							}else{
								$totalSEarningAmendments[$ctr] += $valamend['amendemp_amount'];    //subject to Statutory
							}
						}else{
							if($valamend['psa_tax'] == 1){
								$totalTEarningAmendments[$ctr] += $valamend['amendemp_amount'];    //subject to Tax 
							}else{
								$totalNTSEarningAmendments[$ctr] += $valamend['amendemp_amount'];  //Non Tax & Statutory
							}
						}
					} else { // Deduction
						if($valamend['psa_statutory'] == 1){
							if($valamend['psa_tax'] == 1){
								$totalTSDeductionAmendments[$ctr] += $valamend['amendemp_amount']; //subject to Tax & Statutory
							}else{
								$totalSDeductionAmendments[$ctr] += $valamend['amendemp_amount'];  //subject to Statutory
							}
						}else{
							if($valamend['psa_tax'] == 1){
								$totalTDeductionAmendments[$ctr] += $valamend['amendemp_amount'];  //subject to Tax 
							}else{
								$totalNTSDeductionAmendments[$ctr] += $valamend['amendemp_amount'];//Non Tax & Statutory
							}
						}
					}
				}
			}
//			echo "<br><br>==================Amendments===================<br>";
//			printa($amendments);
//			echo $totalTSEarningAmendments[$ctr]." E Tax & Stat<br>";
//			echo $totalTEarningAmendments[$ctr]." E Tax<br>";
//			echo $totalSEarningAmendments[$ctr]." E Stat<br>";
//			echo $totalNTSEarningAmendments[$ctr]." E Non Tax & Stat<br>";
//			echo $totalTSDeductionAmendments[$ctr]." D Tax & Stat<br>";
//			echo $totalTDeductionAmendments[$ctr]." D Tax<br>";
//			echo $totalSDeductionAmendments[$ctr]." D Stat<br>";
//			echo $totalNTSDeductionAmendments[$ctr]." D Non Tax & Stat<br>";exit;
			//----------------------------END-------------------------<<

			//Sum all Payelements
			//-------------------------------------------------------->>
			$SumTSABEarning = $totalTSEarningAmendments[$ctr] + $totalTSEarningBenefits[$ctr];         //TS Earning
			$SumTABEarning = $totalTEarningAmendments[$ctr] + $totalTEarningBenefits[$ctr];			   //T Earning
			$SumSABEarning = $totalSEarningAmendments[$ctr] + $totalSEarningBenefits[$ctr];			   //S Earning
			$SumNonABEarning = $totalNTSEarningAmendments[$ctr] + $totalNTSEarningBenefits[$ctr];   //Non TS Earning
			$SumALLABEarning = $SumTSABEarning + $SumTABEarning + $SumSABEarning + $SumNonABEarning;   //SUM all AB Earning
			
			$SumTSABDeduction = $totalTSDeductionAmendments[$ctr] + $totalTSDeductionBenefits[$ctr];   //TS Deduction
			$SumTABDeduction = $totalTDeductionAmendments[$ctr] + $totalTDeductionBenefits[$ctr];	   //T Deduction
			$SumSABDeduction = $totalSDeductionAmendments[$ctr] + $totalSDeductionBenefits[$ctr];      //S Deduction
			$SumNonABDeduction = $totalNTSDeductionAmendments[$ctr] + $totalNTSDeductionBenefits[$ctr];//Non TS Deduction
			$SumALLABDeduction = $SumTSABDeduction + $SumTABDeduction + $SumSABDeduction + $SumNonABDeduction;//SUM all AB Deduction
			
			$TotalTSSDeduction = $SumTSABDeduction + $SumSABDeduction;
			//----------------------------END-------------------------<<

			// Amount Computed base in Basic Pay.
			//-------------------------------------------------------->>
			$varSumAllOTRate=0;
			$SumNonTaxOTRate=0;
			$basicpay_rate = 0;													  //BasicPay Rate
			$BPperperiod = 0; 																  //Basic computed base to salary type and pay group
			$COLAperperiod = 0;	
			IF($totalRegtime[0]['ot_istax']==1){						  						      			      //COLA computed base to salary type and pay group
				$varSumAllOTRate = Number_Format($varTotalallOT[$ctr],$objClsMngeDecimal->getFinalDecimalSettings(),'.','');  //sum of all OT
			}ELSE{
				$SumNonTaxOTRate = Number_Format($TotalNonTaxOT[$ctr],$objClsMngeDecimal->getFinalDecimalSettings(),'.','');  //sum of all Non-TAX OT	
			}
			$basicPG_ABS = ($SumSABEarning + $SumTSABEarning) - ($SumTSABDeduction + $SumSABDeduction);	  //Add AB Earning and Less AB Deduction subject to Stat 
			$basicPG_nostat = ($SumTSABEarning + $SumTABEarning) - ($SumTABDeduction + $SumTSABDeduction);//Sum all Earning and Deduction that subject to tax.
			$nontaxableGross = $SumNonABEarning; 																	      //Sum all non AB Earning
			$other_nontaxdeduction = $SumNonABDeduction;															 	  //Sum all non AB Deduction
			$SumAllEarning = $SumALLABEarning;
//			echo "<br>================== Summary ===================<br>";
//			echo $varTotalRugalarTimeRate[$ctr]. ' Basic Pay<br>';
//			echo $varSumAllOTRate.' Total OT<br>';
//			echo $varSumAllTARate.' Total LUA<br>';
//			echo $basicgrosspg.' (BasicPay+OT)-LUA<br>';
//			echo $basicPG_ABS.' Basic AB Stat<br>';
//			echo $nontaxableGross.' Non-TaxGross<br>';
//			echo $other_nontaxdeduction.' Non-TaxDeduction<br>';
			
            if($emp_id_ == ""){
//            	$this->doSavePayStubEntry($paystub_id, 1, $BPperperiod);          //save Basic computed base to salary type and pay group to payroll_paystub_entry table
//				$this->doSavePayStubEntry($paystub_id, 4, $basicgrosspg);		  //save Total Grosspay to payroll_paystub_entry table
//            	$this->doSavePayStubEntry($paystub_id,16, $varSumAllOTRate);	  //save Total OT to payroll_paystub_entry table
//				$this->doSavePayStubEntry($paystub_id,17, $varSumAllTARate);	  //save Total TA to payroll_paystub_entry table
				$this->doSavePayStubEntry($paystub_id,25, $SumSABEarning);		  //save Total S Earning to payroll_paystub_entry table
				$this->doSavePayStubEntry($paystub_id,26, $SumTSABEarning);		  //save Total TS Earning to payroll_paystub_entry table
				$this->doSavePayStubEntry($paystub_id,28, $SumTABEarning);		  //save Total T Earning to payroll_paystub_entry table
				$this->doSavePayStubEntry($paystub_id,29, $SumTABDeduction);	  //save Total T Deduction to payroll_paystub_entry table
				$this->doSavePayStubEntry($paystub_id,33, $SumSABDeduction);	  //save Total S Deduction to payroll_paystub_entry table
				$this->doSavePayStubEntry($paystub_id,34, $SumTSABDeduction);	  //save Total ST Deduction to payroll_paystub_entry table
            	$this->doSavePayStubEntry($paystub_id,39, $COLAperperiod);        //save Total COLA computed base to salary type and pay group to payroll_paystub_entry table
            }
			//----------------------------END-------------------------<<
			// Statutory deduction
				$varData[$ctr]['typeDeduc'] = $this->getTypeDeduction();
				$dectype = $varData[$ctr]['typeDeduc'];
//				printa($dectype);
							
				/**
				 * modified by rblising
				 * wag muna ideduct ang tax
				 * deduct it after the sss,phic,hdmf, and others are duducted
				 */
				for ($b=0;$b<count($dectype);$b++){
					$qry = array();
					$qry[] = "a.sc_id = '".$dectype[$b]['GenSetup']['set_decimal_places']."'";
					$qry[] = "a.dec_id = '".$dectype[$b]['dec_id']."'";
					$criteria = count($qry)>0 ? " WHERE ".implode(' and ',$qry) : '';
					$sql = "SELECT * FROM statutory_contribution a $criteria";
					$varDeducH = $this->conn->Execute($sql);
					if(!$varDeducH->EOF){
						$varHdeduc = $varDeducH->fields;
					}
//					printa($varHdeduc);exit;
					$transdateSched = $rsResult->fields['ppdTransDate'];
					$startdateSched = $rsResult->fields['ppdStartDate'];
					$schedSecond = $rsResult->fields['pps_secnd_trans_daymonth'];
//					echo "<BR>".$transdateSched." transdateSched<br>"; echo $startdateSched." startdateSched<br>"; echo $schedSecond." schedSecond<br>";
					//@todo: check for 1st & 2nd Half
					$varDeductionSched = $this->getDeductionSched($pData['chkAttend'][$ctr],$dectype[$b]['dec_id']);
					//printa($varDeductionSched).'<br>'; echo count($varDeductionSched).'<br>'; exit;
					switch ($dectype[$b]['dec_id']) {
						//@Aut:jim(20120817)
						//@note: ADJUST getMonthlyRecordStat add $rsResult->fields['payperiod_period'] to correct computation of STAT.
						case 1:
							IF(count($varDeductionSched)=='1'){
								IF($rsResult->fields['salaryclass_id']=='5'){//FOR MONTHLY PG SSS COMPUTATION
									IF($varDeductionSched[0]['bldsched_period']=='0'){
										$varDecSSS = 0;
			                            $varDecSSSEC = 0;
			                            $er[$ctr] = 0;
			                            $varDecSSSER = 0;
									}ELSE{
										$schedSecond = '1';
										$varPPD_ = $this->getMonthlyRecordStat($pData['chkAttend'][$ctr],'7',$rsResult->fields['payperiod_period'],$rsResult->fields['payperiod_period_year'],$rsResult->fields['payperiod_id'],1);
										IF($dectype[$b]['GenSetup']['set_stat_type'] == 1){
											//SSS BASE ON GROSS
											$totalSSSgross = $basicPG_ABS + $varPPD_['ppe_rate'];
										}ELSE{
											//SSS BASE ON BASIC
											$totalSSSgross = $BPperperiod;
										}
										$varDec = $this->getTotalDeductionByPayPeriod($pData['chkAttend'][$ctr],$varHdeduc['sc_id'],$dectype[$b]['dec_id'],$totalSSSgross,$rsResult->fields['taxep_id']);
					                    $varDecSSS = $varDec['scr_ee'] - $varPPD_['ppe_amount'];
					                    $er[$ctr] = $varDec['scr_er'] - $varPPD_['ppe_amount_employer'];
					                    $varDecSSSER = $varDec['scr_er'] - $varPPD_['ppe_amount_employer'];
					                    $varDecSSSEC = $varDec['scr_ec'] - $varPPD_['ppe_units'];
									}
								}ELSEIF($rsResult->fields['salaryclass_id']=='4'){//FOR SEMI-MONTHLY PG SSS COMPUTATION
									IF($varDeductionSched[0]['bldsched_period']=='0' OR $varDeductionSched[0]['bldsched_period']=='1'){
											$varDecSSS = 0;
				                            $varDecSSSEC = 0;
				                            $er[$ctr] = 0;
				                            $varDecSSSER = 0;
									}ELSE{
										IF($rsResult->fields['payperiod_freq']=='2'){
											$schedSecond = '1';
											$varPPD_ = $this->getMonthlyRecordStat($pData['chkAttend'][$ctr],'7',$rsResult->fields['payperiod_period'],$rsResult->fields['payperiod_period_year'],$rsResult->fields['payperiod_id'],1);
											IF($dectype[$b]['GenSetup']['set_stat_type'] == 1){
												//SSS BASE ON GROSS
												$totalSSSgross = $basicPG_ABS + $varPPD_['ppe_rate'];
											}ELSE{
												//SSS BASE ON BASIC
												$totalSSSgross = $BPperperiod + $varPPD_['ppe_rate'];
											}
											$varDec = $this->getTotalDeductionByPayPeriod($pData['chkAttend'][$ctr],$varHdeduc['sc_id'],$dectype[$b]['dec_id'],$totalSSSgross,$rsResult->fields['taxep_id']);
						                    $varDecSSS = $varDec['scr_ee'];
						                    $er[$ctr] = $varDec['scr_er'];
						                    $varDecSSSER = $varDec['scr_er'];
						                    $varDecSSSEC = $varDec['scr_ec'];
										}else{
											$totalSSSgross = $basicPG_ABS;
											$varDecSSS = 0;
				                            $varDecSSSEC = 0;
				                            $er[$ctr] = 0;
				                            $varDecSSSER = 0;
										}
									}
								}
							}ELSE{
								IF($rsResult->fields['payperiod_freq']>='2' AND !(strtotime($rsResult->fields['emp_hiredate']) >= strtotime($rsResult->fields['payperiod_start_date'])) && (strtotime($rsResult->fields['emp_hiredate']) <= strtotime($rsResult->fields['payperiod_end_date']))){
									$schedSecond = '1';
									$varPPD_ = $this->getMonthlyRecordStat($pData['chkAttend'][$ctr],'7',$rsResult->fields['payperiod_period'],$rsResult->fields['payperiod_period_year'],$rsResult->fields['payperiod_id'],1);
									IF($dectype[$b]['GenSetup']['set_stat_type'] == 1){
										//SSS BASE ON GROSS
										$totalSSSgross = $basicPG_ABS + $varPPD_['ppe_rate'];
									}ELSE{
										//SSS BASE ON BASIC
										$totalSSSgross = $BPperperiod + $varPPD_['ppe_rate'];
									}
									//echo $basicPG_ABS."<br>";
									//echo $varPPD_['ppe_rate']."<br>";
									//echo $totalSSSgross; exit;
									$varDec = $this->getTotalDeductionByPayPeriod($pData['chkAttend'][$ctr],$varHdeduc['sc_id'],$dectype[$b]['dec_id'],$totalSSSgross,$rsResult->fields['taxep_id']);
				                    $varDecSSS = $varDec['scr_ee'] - $varPPD_['ppe_amount'];
				                    $er[$ctr] = $varDec['scr_er'] - $varPPD_['ppe_amount_employer'];
				                    $varDecSSSER = $varDec['scr_er'] - $varPPD_['ppe_amount_employer'];
				                    $varDecSSSEC = $varDec['scr_ec'] - $varPPD_['ppe_units'];
								}ELSE{
									$schedSecond = '0';
									$totalSSSgross = $basicPG_ABS;
									$varDec = $this->getTotalDeductionByPayPeriod($pData['chkAttend'][$ctr],$varHdeduc['sc_id'],$dectype[$b]['dec_id'],$totalSSSgross,$rsResult->fields['taxep_id']);
			                        IF($rsResult->fields['salaryclass_id'] == 5){
			                            $varDecSSS = $varDec['dd_phic_sss_ee']/2;
			                            $varDecSSSEC = $varDec['dd_phic_sss_ec']/2;
			                            $er[$ctr] = $varDec['dd_phic_sss_er']/2;
			                            $varDecSSSER = $varDec['dd_phic_sss_er']/2;
			                        }ELSE{
			                            $varDecSSS = $varDec['scr_ee'];
			                            $varDecSSSEC = $varDec['scr_ec'];
			                            $er[$ctr] = $varDec['scr_er'];
			                            $varDecSSSER = $varDec['scr_er'];
			                        }
								}
							}
							$totalDeDuction[$ctr] += $varDecSSS;
							$psaSSS = "SSS";
							if($emp_id_ == ""){
	                            $this->doSavePayStubEntry($paystub_id, 7, $varDecSSS,$er[$ctr], $totalSSSgross,$varDecSSSEC);
	                        }
//	                        echo "================== SSS STAT ==================<br>";
//	                        echo $schedSecond." schedSecond <br>";
//		                    echo $totalSSSgross." total SSS GROSS <br>";
//		                    echo $varDecSSS." Total employee deduction <br>";
//		                    echo $er[$ctr]." Total Employer Deduction <br>";
//		                    echo $varDecSSSER." Total Employer Deduction <br>";
//		                    echo $varDecSSSEC." ECC <br>";
							break;
						case 2:
							if (count($varDeductionSched)=='1') {
								if ($rsResult->fields['salaryclass_id']=='5') {//FOR MONTHLY PG PHIC COMPUTATION
									IF ($varDeductionSched[0]['bldsched_period']=='0') {
										$varDecPHIL = 0;
			                            $er[$ctr] = 0;
			                            $varDecPHILER = 0;
			                            $varDecPHILEC = 0;
									} ELSE {
										$varPPD_ = $this->getMonthlyRecordStat($pData['chkAttend'][$ctr],'14',$rsResult->fields['payperiod_period'],$rsResult->fields['payperiod_period_year'],$rsResult->fields['payperiod_id'],1);
										IF($dectype[$b]['GenSetup']['set_stat_type'] == 1){
											//PHIC BASE ON GROSS
											$totalPhilgross = $basicPG_ABS + $varPPD_['ppe_rate'];
										}ELSE{
											//PHIC BASE ON BASIC
											$totalPhilgross = $BPperperiod;
										}
										$varDec = $this->getTotalDeductionByPayPeriod($pData['chkAttend'][$ctr],$varHdeduc['sc_id'],$dectype[$b]['dec_id'],$totalPhilgross,$rsResult->fields['taxep_id']);
					                    $varDecPHIL = $varDec['scr_ee'] - $varPPD_['ppe_amount'];
					                    $er[$ctr] = $varDec['scr_er'] - $varPPD_['ppe_amount_employer'];
					                    $varDecPHILER = $varDec['scr_er'] - $varPPD_['ppe_amount_employer'];
					                    $varDecPHILEC = $varDec['scr_ec'];
									}
								} elseif ($rsResult->fields['salaryclass_id']=='4') {//FOR SEMI-MONTHLY PG PHIC COMPUTATION
									if ($varDeductionSched[0]['bldsched_period']=='0' OR $varDeductionSched[0]['bldsched_period']=='1') {
											$varDecPHIL = 0;
				                            $er[$ctr] = 0;
				                            $varDecPHILER = 0;
				                            $varDecPHILEC = 0;
									} else {
										if ($rsResult->fields['payperiod_freq']=='2') {
											$varPPD_ = $this->getMonthlyRecordStat($pData['chkAttend'][$ctr],'14',$rsResult->fields['payperiod_period'],$rsResult->fields['payperiod_period_year'],$rsResult->fields['payperiod_id'],1);
											IF($dectype[$b]['GenSetup']['set_stat_type'] == 1){
												//PHIC BASE ON GROSS
												$totalPhilgross = $basicPG_ABS + $varPPD_['ppe_rate'];
											}ELSE{
												//PHIC BASE ON BASIC
												$totalPhilgross = $BPperperiod + $varPPD_['ppe_rate'];
											}
											//echo $totalPhilgross; echo '<br>'.$BPperperiod.'<br>';  echo '<br>'.$varPPD_['ppe_rate'].'<br>';exit;
											$varDec = $this->getTotalDeductionByPayPeriod($pData['chkAttend'][$ctr],$varHdeduc['sc_id'],$dectype[$b]['dec_id'],$totalPhilgross,$rsResult->fields['taxep_id']);
						                    $varDecPHIL = $varDec['scr_ee'];
						                    $er[$ctr] = $varDec['scr_er'];
						                    $varDecPHILER = $varDec['scr_er'];
						                    $varDecPHILEC = $varDec['scr_ec'];
										} ELSE {
											$totalPhilgross = $basicPG_ABS;
											$varDecPHIL = 0;
				                            $er[$ctr] = 0;
				                            $varDecPHILER = 0;
				                            $varDecPHILEC = 0;
										}
									}
								}
							} ELSE  {
								if ($rsResult->fields['payperiod_freq']>='2' AND !(strtotime($rsResult->fields['emp_hiredate']) >= strtotime($rsResult->fields['payperiod_start_date'])) && (strtotime($rsResult->fields['emp_hiredate']) <= strtotime($rsResult->fields['payperiod_end_date']))) {
									$varPPD_ = $this->getMonthlyRecordStat($pData['chkAttend'][$ctr],'14',$rsResult->fields['payperiod_period'],$rsResult->fields['payperiod_period_year'],$rsResult->fields['payperiod_id'],1);
									IF($dectype[$b]['GenSetup']['set_stat_type'] == 1){
										//PHIC BASE ON GROSS
										$totalPhilgross = $basicPG_ABS + $varPPD_['ppe_rate'];
									}ELSE{
										//PHIC BASE ON BASIC
										$totalPhilgross = $BPperperiod + $varPPD_['ppe_rate'];
									}
									//echo $totalPhilgross; echo '<br>'.$BPperperiod.'<br>';  echo '<br>'.$varPPD_['ppe_rate'].'<br>';exit;
									$varDec = $this->getTotalDeductionByPayPeriod($pData['chkAttend'][$ctr],$varHdeduc['sc_id'],$dectype[$b]['dec_id'],$totalPhilgross,$rsResult->fields['taxep_id']);
				                    $varDecPHIL = $varDec['scr_ee'] - $varPPD_['ppe_amount'];
				                    $er[$ctr] = $varDec['scr_er'] - $varPPD_['ppe_amount_employer'];
				                    $varDecPHILER = $varDec['scr_er'] - $varPPD_['ppe_amount_employer'];
				                    $varDecPHILEC = $varDec['scr_ec'];
								} else {
									$totalPhilgross = $BPperperiod;
									$varDec = $this->getTotalDeductionByPayPeriod($pData['chkAttend'][$ctr],$varHdeduc['sc_id'],$dectype[$b]['dec_id'],$totalPhilgross,$rsResult->fields['taxep_id']);
			                        if ($rsResult->fields['salaryclass_id'] == 5) {
			                            $varDecPHIL = $varDec['dd_phic_sss_ee']/2;
			                            $er[$ctr] = $varDec['dd_phic_sss_er']/2;
			                            $varDecPHILER = $varDec['dd_phic_sss_er']/2;
			                            $varDecPHILEC = $varDec['scr_ec'];
			                        } else {
			                            $varDecPHIL = $varDec['scr_ee'];
			                            $er[$ctr] = $varDec['scr_er'];
			                            $varDecPHILER = $varDec['scr_er'];
			                            $varDecPHILEC = $varDec['scr_ec'];
			                        }
								}
							}
							$totalDeDuction[$ctr] += $varDecPHIL;
							$psaPhil = "PHIC";
							if ($emp_id_ == "") {
	                            $this->doSavePayStubEntry($paystub_id, 14, $varDecPHIL,$er[$ctr], $totalPhilgross,0);
	                        }
//	                        echo "================== PHIC STAT ================<br>";
//		                    echo $totalPhilgross." total PHIC GROSS <br>";
//		                    echo $varDecPHIL." Total employee deduction <br>";
//		                    echo $er[$ctr]." Total Employer Deduction <br>";
//		                    echo $varDecPHILER." Total Employer Deduction <br>";
//		                    echo $varDecPHILEC." MSB <br>";
							break;
						case 3:
							if (count($varDeductionSched)=='1') {
								if ($rsResult->fields['salaryclass_id']=='5') {//FOR MONTHLY PG HDMF COMPUTATION
									if ($varDeductionSched[0]['bldsched_period']=='0') {
										$varDecPagibig = 0;
			                            $varDecPagibigEmployer = 0;
									} else {
										$varPPD_ = $this->getMonthlyRecordStat($pData['chkAttend'][$ctr],'15',$rsResult->fields['payperiod_period'],$rsResult->fields['payperiod_period_year'],$rsResult->fields['payperiod_id'],1);
										$totalHDMFgross = $varPPD_['ppe_rate'] + $basicPG_ABS;
										$varDec = $this->getTotalDeductionByPayPeriod($pData['chkAttend'][$ctr],$varHdeduc['sc_id'],$dectype[$b]['dec_id'],$totalHDMFgross,$rsResult->fields['taxep_id']);
										$varDecPagibig = $varDec['scr_ee'] - $varPPD_['ppe_amount'];
					                    $varDecPagibigEmployer = $varDec['scr_er'] - $varPPD_['ppe_amount_employer'];
									}
								} elseif ($rsResult->fields['salaryclass_id']=='4') {//FOR SEMI-MONTHLY PG HDMF COMPUTATION
									if ($varDeductionSched[0]['bldsched_period']=='0' || $varDeductionSched[0]['bldsched_period']=='1') {
											$varDecPagibig = 0;
			                            	$varDecPagibigEmployer = 0;
									} else {
										if ($rsResult->fields['payperiod_freq']=='2') {
											$varPPD_ = $this->getMonthlyRecordStat($pData['chkAttend'][$ctr],'15',$rsResult->fields['payperiod_period'],$rsResult->fields['payperiod_period_year'],$rsResult->fields['payperiod_id'],1);
											$totalHDMFgross = $varPPD_['ppe_rate'] + $basicPG_ABS;
											$varDec = $this->getTotalDeductionByPayPeriod($pData['chkAttend'][$ctr],$varHdeduc['sc_id'],$dectype[$b]['dec_id'],$totalHDMFgross,$rsResult->fields['taxep_id']);
											$varDecPagibig = $varDec['scr_ee'];
						                    $varDecPagibigEmployer = $varDec['scr_er'];
										} else {
											$totalHDMFgross = $basicPG_ABS;
											$varDecPagibig = 0;
			                            	$varDecPagibigEmployer = 0;
										}
									}
								}
							} else {
								if ($rsResult->fields['payperiod_freq']>='2') {
									$varPPD_ = $this->getMonthlyRecordStat($pData['chkAttend'][$ctr],'15',$rsResult->fields['payperiod_period'],$rsResult->fields['payperiod_period_year'],$rsResult->fields['payperiod_id'],1);
									$totalHDMFgross = $varPPD_['ppe_rate'] + $basicPG_ABS;
									$varDec = $this->getTotalDeductionByPayPeriod($pData['chkAttend'][$ctr],$varHdeduc['sc_id'],$dectype[$b]['dec_id'],$totalHDMFgross,$rsResult->fields['taxep_id']);
									$varDecPagibig = $varDec['scr_ee'] - $varPPD_['ppe_amount'];
				                    $varDecPagibigEmployer = $varDec['scr_er'] - $varPPD_['ppe_amount_employer'];
								} else {
									$totalHDMFgross = $basicPG_ABS;
									$varDec = $this->getTotalDeductionByPayPeriod($pData['chkAttend'][$ctr],$varHdeduc['sc_id'],$dectype[$b]['dec_id'],$totalHDMFgross,$rsResult->fields['taxep_id']);
			                        if ($varDec['dduct_isnominal'] == 10) {
			                            if ($rsResult->fields['salaryclass_id'] == 5) {
			                                $varDecPagibig = (($varDec['dd_phic_sss_ee']*$totalHDMFgross)/2);
			                                $varDecPagibigEmployer = ($varDec['dd_phic_sss_er']*$totalHDMFgross)/2;
			                            } else {
			                                $varDecPagibig = $varDec['dd_phic_sss_ee']*$totalHDMFgross;
			                                $varDecPagibigEmployer = $varDec['dd_phic_sss_er']*$totalHDMFgross;
			                            }
			                        } else {
			                            $varDecPagibig = $varDec['scr_ee'];
			                            $varDecPagibigEmployer = $varDec['scr_er'];
			                        }
								}
							}	
							$totalDeDuction[$ctr] += $varDecPagibig;
							$psapagibig = "Pag-ibig";
							if ($emp_id_ == "") {
	                            $this->doSavePayStubEntry($paystub_id, 15, $varDecPagibig,$varDecPagibigEmployer,$totalHDMFgross,0);
	                        }
//	                        printa($varDec);
//	                        echo "================== HDMF STAT ================<br>";
//		                    echo $totalHDMFgross." total HDMF GROSS <br>";
//		                    echo $varDecPagibig." Total employee deduction <br>";
//		                    echo $varDecPagibigEmployer." Total Employer Deduction <br>";
							break;
						default:
						break;
					}
				}
				
				$varDeduction = $totalDeDuction[$ctr];//sum all statutory contribution.
				$deduction = array( "SSS" => $varDecSSS
									,"SSSER" => $varDecSSSER
									,"SSSEC" => $varDecSSSEC
									,"PhilHealth" => $varDecPHIL
									,"PhilHealthER" => $varDecPHILER
									,"Pag-ibig" => $varDecPagibig
									,"Pag-ibigER" => $varDecPagibigEmployer
									,"Others" => $varDecOther);
	            if ($emp_id_ == "") {
					$this->doSavePayStubEntry($paystub_id, 27, $varDeduction);
	            }
	            
			// Compute W/H Tax
			//-------------------------------------------------------->>
			//Used to check if MWE
			$qry_ = array();
			$qry_[] = "a.empdd_id = '5'";
			$qry_[] = "a.emp_id = '".$rsResult->fields['emp_id']."'";
			$criteria = count($qry_)>0 ? " WHERE ".implode(' AND ',$qry_) : '';
			$sql_= "SELECT a.bldsched_period,a.percent_tax,a.s_ltu,a.s_stat FROM period_benloanduc_sched a $criteria";
			$varMWE = $this->conn->Execute($sql_);
			IF(!$varMWE->EOF){
				$varMWE_ = $varMWE->fields;
			}ELSE{
				$varMWE_ = 0;
			}
			//Get General Setup for TAX Computation
			$varHdeduc = clsPayroll_Details::getGeneralSetup('TAX');
			IF($varHdeduc['set_stat_type']=='1'){//1 = GROSS PAY
				IF($varHdeduc['set_order']=='1'){//1 = Subject to statutory Deduction
	        		$taxableGross = $basicPG_nostat - $varDeduction;//Taxable gross - Total Statutory Deduection
					IF($varMWE_['bldsched_period']=='4'){
						IF($varMWE_['s_stat']!='1'){//if no-stat plus Statutory
							$taxableGross = $taxableGross + $varDeduction;
						}
						IF($varMWE_['s_ltu']!='1'){
							$taxableGross = $taxableGross + $varSumAllTARate;
						}
					}
				}ELSE{//0 = not suject to statutory deduction
					$taxableGross = $basicPG_nostat;
					IF($varMWE_['bldsched_period']=='4'){
		        		IF($varMWE_['s_stat']=='1'){
							$taxableGross = $taxableGross - $varDeduction;
						}
						IF($varMWE_['s_ltu']!='1'){
							$taxableGross = $taxableGross + $varSumAllTARate;
						}
					}
				}
			}ELSE{//0 = BASIC SALARY
				IF($varHdeduc['set_order']=='1'){//1 = Subject to statutory Deduction
					$taxableGross = $BPperperiod - $varDeduction;//Basic Salary - Total Statutory Deduction
					IF($varMWE_['bldsched_period']=='4'){
		        		IF($varMWE_['s_stat']!='1'){
							$taxableGross = $taxableGross + $varDeduction;
						}
						IF($varMWE_['s_ltu']=='1'){
							$taxableGross = $taxableGross - $varSumAllTARate;
						}
					}
				}ELSE{//0 = not suject to statutory deduction
					$taxableGross = $BPperperiod;//Basic Salary
					IF($varMWE_['bldsched_period']=='4'){
		        		IF($varMWE_['s_stat']=='1'){
							$taxableGross = $taxableGross - $varDeduction;
						}
						IF($varMWE_['s_ltu']=='1'){
							$taxableGross = $taxableGross - $varSumAllTARate;
						}
					}
				}
			}
			IF($BPDayRate['rateperday'] <= $MWR[$ctr]){
				//echo "MWE";
				$totalTax = 0;
				$conVertTaxper = 0;
			}ELSE{
				IF($varMWE_['bldsched_period']=='2'){
					//echo "2";
					$totalTax = 0;
					$conVertTaxper = 0;
				}ELSEIF($varMWE_['bldsched_period']=='3'){
					//echo "3";
					$totalTax = 0;
					$conVertTaxper = 0;	
				}ELSEIF($varMWE_['bldsched_period']=='4'){
					//echo "4";
					$conVertTaxper = $varMWE_['percent_tax'] / 100;
					$totalTax = $taxableGross * $conVertTaxper;
					$totalTax_ = $totalTax;
					$psaTax = "Tax";
				}ELSE{
					//echo "the else";
					$varDec = $this->getTotalTaxByPayPeriod($pData['chkAttend'][$ctr],$varHdeduc['set_decimal_places'],2,$taxableGross,$rsResult->fields['taxep_id'],$rsResult->fields['tt_pay_group']);
					$varStax = $taxableGross - $varDec['tt_minamount'];
					$conVertTaxper =  $varDec['tt_over_pct'] / 100;
					$varStax_p =  $varStax * $conVertTaxper;
					$totalTax = $varDec['tt_taxamount'] + $varStax_p;
					$totalTax_ = $totalTax;
					$psaTax = "Tax";
				}
			}
			//exit;
//			echo "<br>================= Tax Summary ================<br>";
//			echo $taxableGross." Taxable Gross<br>";
//			echo $varMWE_['percent_tax']." varMWE_[percent_tax]<br>";
//			echo $conVertTaxper." conVertTaxper<br>";
//			echo $totalTax." W/H Tax<br>";
			//----------------------------END-------------------------<<
			
			//get gov/reg loans
			//-------------------------------------------------------->>
//			$govreg_loan[$ctr] = $this->getEmployeeActiveGovRegLoan($pData['chkAttend'][$ctr],$rsResult->fields['payperiod_trans_date'],$paystub_id,$rsResult->fields['payperiod_freq']);
//			$totalregloan[$ctr] = 0;
//			if(count($govreg_loan[$ctr])>0){
//				foreach ($govreg_loan[$ctr] as $keyregloan => $valregloan){
//                    //check if negative number
//                    if($valregloan['loan_payperperiod'] >0){
//                        $totalregloan[$ctr] +=  $valregloan['loan_payperperiod'];
//                    }
//				}
//			}
//			printa($govreg_loan[$ctr]);
			//----------------------------END-------------------------<<

			$afterTaxGross = Number_Format($taxableGross,$objClsMngeDecimal->getFinalDecimalSettings(),'.','') - Number_Format($totalTax,$objClsMngeDecimal->getFinalDecimalSettings(),'.','');//deduct the tax to the taxable gross
			$grossandnontaxable = Number_Format($nontaxableGross,$objClsMngeDecimal->getFinalDecimalSettings(),'.','') + $SumNonTaxOTRate;//sum all non-taxable income
			$otherdeducNtax = Number_Format($other_nontaxdeduction,$objClsMngeDecimal->getFinalDecimalSettings(),'.','') + Number_Format($totalregloan[$ctr],$objClsMngeDecimal->getFinalDecimalSettings(),'.','');//sum all non-taxble deduction
			$SumAllDeduction = $varSumAllTARate + Number_Format($SumALLABDeduction,$objClsMngeDecimal->getFinalDecimalSettings(),'.','') + Number_Format($totalTax,2,'.','') + Number_Format($varDeduction,$objClsMngeDecimal->getFinalDecimalSettings(),'.','') + Number_Format($totalregloan[$ctr],$objClsMngeDecimal->getFinalDecimalSettings(),'.','');//Sum all Deduction
			$varNetpay[$ctr] = $SumAllEarning - $SumAllDeduction;//NETPAY

//			echo "<br>================ Final Summary ================<br>";
//			echo $afterTaxGross." After Tax Gross ( TaxableGross - W/H Tax)<br>";
//			echo $grossandnontaxable." sum all non-taxable income<br>";
//			echo $otherdeducNtax." sum all non-taxble deduction<br>";
//			echo "<br>================ DEDUCTION ================<br>";
//			echo Number_Format($varSumAllTARate,$objClsMngeDecimal->getFinalDecimalSettings(),'.','').' varSumAllTARate<br>';
//			echo Number_Format($SumALLABDeduction,$objClsMngeDecimal->getFinalDecimalSettings(),'.','').' SumALLABDeduction<br>';
//			echo Number_Format($totalTax,$objClsMngeDecimal->getFinalDecimalSettings(),'.','').' WHTAX<br>';
//			echo Number_Format($varDeduction,$objClsMngeDecimal->getFinalDecimalSettings(),'.','').' varDeduction<br>';
//			echo Number_Format($totalregloan[$ctr],$objClsMngeDecimal->getFinalDecimalSettings(),'.','')." totalregloan<br>";
//			echo $SumAllDeduction." Sum all Deduction<br>";
//			echo $varNetpay[$ctr]." NETPAY<br>"; exit;
			
			if($emp_id_ == ""){
				$this->doSavePayStubEntry($paystub_id,31,$grossandnontaxable);//save Total Non TS Earning to payroll_paystub_entry table
				$this->doSavePayStubEntry($paystub_id,32,$otherdeducNtax);	  //save Total Non TS Deduction to payroll_paystub_entry table
            	$this->doSavePayStubEntry($paystub_id,2,$SumAllDeduction);    //save Total Deduction to payroll_paystub_entry table
				$this->doSavePayStubEntry($paystub_id,5,$varNetpay[$ctr]);    //save netpay to payroll_paystub_entry table
				$this->doSavePayStubEntry($paystub_id,30,$taxableGross);	  //save Taxablegross
            	$this->doSavePayStubEntry($paystub_id,8,$totalTax,$taxableGross,$conVertTaxper); //save W/H Tax
			}

			//array structure of details on the paystub
			$arrPayStub[$ctr]['empinfo'] = array(
				 "emp_id" => $rsResult->fields['emp_id']
				,"emp_no" => $rsResult->fields['emp_idnum']
				,"fullname" => $rsResult->fields['fullname']
				,"jobcode" => $rsResult->fields['post_code']
				,"jobpos_name" => $rsResult->fields['post_name']
				,"comp_name" => $rsResult->fields['comp_name']
				,"comp_add" => $rsResult->fields['comp_add']
				,"ud_name" => $rsResult->fields['ud_name']
				,"tax_ex_name" => $rsResult->fields['taxep_name']
				,"comp_id" => $rsResult->fields['comp_id']
				,"ud_id" => $rsResult->fields['ud_id']
				,"emptype_name" => $rsResult->fields['emptype_name']
				,"bankiemp_acct_no" => $rsResult->fields['bankiemp_acct_no']
				,"banklist_name" => $rsResult->fields['banklist_name']
				,"pi_emailone" => $rsResult->fields['pi_emailone']
				,"taxep_id" => $rsResult->fields['taxep_id']
				,"salaryclass_id" => $rsResult->fields['salaryclass_id']
				,"tt_pay_group" => $rsResult->fields['tt_pay_group']
				,"ud_code" => $rsResult->fields['ud_code']
				,"taxep_code" => $rsResult->fields['taxep_code']
				,"salarytype_id" => $rsResult->fields['salarytype_id']
			);
			$arrPayStub[$ctr]['paystubdetail'] = array(
					"paystubsched" =>array(
						 "pps_id" => $rsResult->fields['pps_id']
						,"pps_name" => $rsResult->fields['pps_name']
						,"payperiod_id" => $rsResult->fields['payperiod_id']
						,"paystub_id" => $paystub_id
						,"payperiod_start_date" => $rsResult->fields['payperiod_start_date']
						,"payperiod_end_date" => $rsResult->fields['payperiod_end_date']
						,"payperiod_trans_date" => $rsResult->fields['payperiod_trans_date']
						,"schedSecond" => $rsResult->fields['pps_secnd_trans_daymonth']
						,"payperiod_freq" => $rsResult->fields['payperiod_freq']
					)
					,"paystubaccount" => array(
						 "earning" => array(
							 "basic" => $basicpay_rate
							,"Regulartime" => $BPperperiod
							,"COLA" => $COLAperperiod
							,"COLAperDay" => $colaperday[$ctr]
							,"totalDays" => $varActualDaysRender[$ctr]
							,"MWR" => $MWR[$ctr]
							,"DailyRate" => $rateperday[$ctr]
							,"HourlyRate" => $rateperhour[$ctr]
							,"OT" => array(
								 "OTDetails" => $this->otDetail[$ctr]
								,"TotalallOT" => $varSumAllOTRate + $SumNonTaxOTRate
								,"OTbackpay" => Number_Format("0",$objClsMngeDecimal->getFinalDecimalSettings(),'.','') + $SumNonTaxOTRate
								,"SumAllOTRate" => $varSumAllOTRate + $SumNonTaxOTRate
							)
						)
						,"deduction" => $deduction
						,"TUA"=> array(
							 "TADetails" => $this->taDetail[$ctr]
							,"TotalLeave" => Number_Format($varSumAllTARate,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						)
						,"leave_record" => $LeaveRecords
						,"government_regular" => $govreg_loan[$ctr]
						,"benefits" => $payStubSerialize
						,"amendments" => array(
								 $amendments
								,$recurring_amendments
								,"total_TSAEarning" => Number_Format($totalTSEarningAmendments[$ctr],$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
								,"total_TAEarning" => Number_Format($totalTEarningAmendments[$ctr],$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
								,"total_SAEarning" => Number_Format($totalSEarningAmendments[$ctr],$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
								,"total_NTSAEarning" => Number_Format($totalNTSEarningAmendments[$ctr],$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
								,"total_TSADeduction" => Number_Format($totalTSDeductionAmendments[$ctr],$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
								,"total_TADeduction" => Number_Format($totalTDeductionAmendments[$ctr],$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
								,"total_SADeduction" => Number_Format($totalSDeductionAmendments[$ctr],$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
								,"total_NTSADeduction" => Number_Format($totalNTSDeductionAmendments[$ctr],$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						)
						,"pstotal" => array(
						 	 "gross" => Number_Format($SumAllEarning,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"Basic Salary" => Number_Format($basicpay_rate,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"PGsalary" => Number_Format($basicgrosspg,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"gross_nontaxable_income" => Number_Format($grossandnontaxable,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"taxable_Gross" => Number_Format($taxableGross,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"Deduction" => Number_Format($SumAllDeduction,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"SatutoryDeduction" => Number_Format($varDeduction,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"W/H Tax" => Number_Format($totalTax,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"aftertaxgross" => Number_Format($afterTaxGross,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"Net Pay" => Number_Format($varNetpay[$ctr],$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"Loan_Total" => Number_Format($totalregloan[$ctr],$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"other_taxable_income" => Number_Format($totalTEarningAmendments[$ctr],$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"other_deduction" => Number_Format($otherdeducNtax,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"TotalEarning_payslip" => Number_Format($SumAllEarning,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"SumSABEarning" => Number_Format($SumSABEarning,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"SumTSABEarning" => Number_Format($SumTSABEarning,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"SumTABDeduction" => Number_Format($SumTABDeduction,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"SumTABEarning" => Number_Format($SumTABEarning,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"BaseSTATGross" => Number_Format($basicPG_ABS,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"SumTSABDeduction" => Number_Format($TotalTSSDeduction,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 )
					)
			);
		  $ctr++;
		} while($ctr < sizeof($pData['chkAttend']));
//		printa($arrPayStub);
//		exit;
		$this->doSavePayStubArr($arrPayStub);
        if($emp_id_ == ""){
            return $retval;
        }else{
            return $arrPayStub;
        }
	}
	
	function savePayElement($psa_name_ = null){
		$return_arr = array();
		$sql = "SELECT * FROM payroll_ps_account WHERE psa_name='$psa_name_'";
		$rsResult = $this->conn->Execute($sql);
		IF(!$rsResult->EOF){
			$return_arr = $rsResult->fields;
		} ELSE {
			$flds = array();
			$flds[] = "psa_status=1";
			$flds[] = "psa_type=1";
			$flds[] = "psa_order=2";
			$flds[] = "psa_name='".$psa_name_."'";
			$flds[] = "psa_tax=0";
			$flds[] = "psa_statutory=0";
			$flds[] = "psa_clsfication=1";
			$flds[] = "psa_procode=3";
			$fields = implode(", ",$flds);
			$sql = "insert into payroll_ps_account set $fields";
			$this->conn->Execute($sql);
			$psa_id = $this->conn->Insert_ID();
			$sql = "SELECT * FROM payroll_ps_account WHERE psa_id='$psa_id'";
			$rsResult = $this->conn->Execute($sql);
			$return_arr = $rsResult->fields;
		}
		return $return_arr;
	}
	
}
?>