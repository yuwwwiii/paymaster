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
	,'-1'=>' Last Day of Month - '
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
 * @author  Jason I. Mabignay
 *
 */
class clsMnge_PG{

	var $conn;
	var $fieldMap;
	var $fieldMap_PP;
	var $Data;
	var $Data_;
	var $otDetail = array();
	var $holidayDetail = array();
	var $premiumDetail = array();
	var $payslips;

	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsMnge_PG object
	 */
	function clsMnge_PG($dbconn_ = null){
		$this->conn =& $dbconn_;
		$this->fieldMap = array(
		 "pps_name" => "pps_name"
		,"pps_desc" => "pps_desc"
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
		);
		$this->fieldMap_PP = array(
		 "pp_stat_id" => "payperiod_status_id"
		,"payperiod_is_primary" => "payperiod_is_primary"
		,"payperiod_start_date" => "payperiod_start_date"
		,"payperiod_end_date" => "payperiod_end_date"
		,"payperiod_trans_date" => "payperiod_trans_date"
		,"payperiod_adv_end_date" => "payperiod_adv_end_date"
		,"payperiod_adv_trans_date" => "payperiod_adv_trans_date"
		,"payperiod_tainted" => "payperiod_tainted"
		);
	}

	/**
	 * Get the records from the database
	 *
	 * @param string $id_
	 * @return array
	 */
	function dbFetch($id_ = ""){
		$sql = "Select a.*, b.fr_name from payroll_pay_period_sched a left join factor_rate b on (b.fr_id=a.fr_id) where a.pps_id=?";
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
	function dbFetch_pp($id_ = ""){
		 $sql = "select ppp.*, psar.pps_name,
				DATE_FORMAT(payperiod_start_date,'%Y-%m-%d') as payperiod_start_date,
				DATE_FORMAT(payperiod_end_date,'%Y-%m-%d') as payperiod_end_date,
				DATE_FORMAT(payperiod_trans_date,'%Y-%m-%d') as payperiod_trans_date
					from payroll_pay_period ppp
					inner join payroll_pay_period_sched psar on (psar.pps_id=ppp.pps_id)
					where ppp.payperiod_id=?";
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
			foreach ($this->fieldMap_PP as $key => $value) {
				if ($isForm_) {
					$this->Data_[$value] = $pData_[$value];
				} else {
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
			if ($keyData == 'pps_new_daytrigger_time'){
				$valData = dDate::parseTimeUnit($_POST['pps_new_daytrigger_time']);
			}
			if ($keyData == 'pps_max_shifttime'){
				$valData = dDate::parseTimeUnit($_POST['pps_max_shifttime']);
			}
			$valData = addslashes($valData);
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
	function doSaveAdd_PP(){
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
			$valData = addslashes($valData);
			$flds[] = "$keyData='$valData'";
		}
		$flds[] = "pps_id = '".$_GET['ppsched']."'";
		$flds[] = "payperiod_addwho = '".AppUser::getData('user_name')."'";
		$fields = implode(", ",$flds);
		$sql = "insert into payroll_pay_period set $fields";
		$this->conn->Execute($sql);

		$_SESSION['eMsg']="Successfully Added.";
	}
	
	/**
	 * Save Employee
	 *
	 */
	function doSaveEmployee($pData){
		$flds = array();
		$ctr=0;
		do{
			$flds[] = "emp_id='".$pData['chkAttend'][$ctr]."'";
			$flds[] = "pps_id='".$_GET['empinput']."'";
			$flds[] = "ppsu_addwho='".$_SESSION['admin_session_obj']['user_data']['user_name']."'";
			$fields = implode(", ",$flds);
			$sql = "insert into payroll_pps_user set $fields";
			$this->conn->Execute($sql);
			$flds = "";
			$fields = "";
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
				$valData = $_POST['pp_stat_id'];
			}
			$valData = addslashes($valData);
			$flds[] = "$keyData='$valData'";
		}
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
			$valData = addslashes($valData);
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
	 * Delete Record
	 *
	 * @param string $id_
	 */
	function doDelete($id_ = ""){
		$sql = "delete from payroll_pay_period_sched where pps_id=?";
		$this->conn->Execute($sql,array($id_));
		$_SESSION['eMsg']="Successfully Deleted.";
	}

	/**
	 * Delete Record
	 *
	 * @param string $id_
	 */
	function doDelete_Emp($id_ = ""){
		$sql = "delete from payroll_pps_user where ppsu_id=?";
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
		,"pps_name" => "pps_name"
		,"pps_desc" => "pps_desc"
		,"salaryclass_id" => "Type"
		);

		if(isset($_GET['sortby'])){
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}

		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$viewLink = "<a href=\"?statpos=mnge_pg&ppsched=',am.pps_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES."calendar.png\" title=\"View Schedule\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
		$editLink = "<a href=\"?statpos=mnge_pg&edit=',am.pps_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/edit.png\" title=\"Edit\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
		$empLink = "<a href=\"?statpos=mnge_pg&empinput=',am.pps_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/useradd.png\" title=\"Select Employee\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
		$delLink = "<a href=\"?statpos=mnge_pg&delete=',am.pps_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\"  border=0 width=\"16\" height=\"16\"></a>";

		// SqlAll Query
		$sql = "select am.*, CONCAT('$empLink','$viewLink','$editLink','$delLink') as viewdata,
				if(salaryclass_id='1','Daily',IF(salaryclass_id='2','Weekly',IF(salaryclass_id='3','Bi-Weekly',IF(salaryclass_id='4','Semi-monthly',IF(salaryclass_id='5','Monthly','Annual'))))) as salaryclass_id
						from payroll_pay_period_sched am
						$criteria
						$strOrderBy";

		// Field and Table Header Mapping
		$arrFields = array(
		 "viewdata"=>"Action"
		,"pps_name" => "Name"
		,"pps_desc" => "Description"
		,"salaryclass_id" => "Type"
		);

		// Column (table data) User Defined Attributes
		$arrAttribs = array(
		"mnu_ord"=>" align='center'",
		"viewdata"=>"width='75' align='center'"
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
	 * Get all the Table Listings
	 *
	 * @return array
	 */
	function getTableList_SchedList(){
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
		 "viewdata"=>""
		,"pps_name" => "pps_name"
		,"salaryclass_name" => "salaryclass_name"
		,"stat_name" => "stat_name"
		,"payperiod_start_date" => "payperiod_start_date"
		,"payperiod_end_date" => "payperiod_end_date"
		,"payperiod_trans_date" => "payperiod_trans_date"
		);

		if(isset($_GET['sortby'])){
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}else{
            $strOrderBy = " order by ppp.payperiod_trans_date";
        }

		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$viewLink = "<a href=\"?statpos=mnge_pg&ppsched=',psar.pps_id,'&ppsched_view=',ppp.payperiod_id,'\"><img src=\"".SYSCONFIG_THEME_URLPATH.SYSCONFIG_THEME."/images/admin/zoom.gif\" title=\"View\" hspace=\"2px\" border=0></a>";
		$editLink = "<a href=\"?statpos=mnge_pg&ppsched=',psar.pps_id,'&ppsched_edit=',ppp.payperiod_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/edit.png\" title=\"Edit\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
		$delLink = "<a href=\"?statpos=mnge_pg&ppsched=',psar.pps_id,'&ppsched_del=',ppp.payperiod_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\"  border=0 width=\"16\" height=\"16\"></a>";

		// SqlAll Query
		$sql = "select ppp.*, CONCAT('$viewLink','$editLink','$delLink') as viewdata, 
				DATE_FORMAT(payperiod_start_date,'%d-%b-%y %h:%i %p') as payperiod_start_date,
				DATE_FORMAT(payperiod_end_date,'%d-%b-%y %h:%i %p') as payperiod_end_date,
				DATE_FORMAT(payperiod_trans_date,'%d-%b-%y %h:%i %p') as payperiod_trans_date, psar.pps_name,
				if(salaryclass_id='1','Daily',IF(salaryclass_id='2','Weekly',IF(salaryclass_id='3','Bi-Weekly',IF(salaryclass_id='4','Semi-monthly',IF(salaryclass_id='5','Monthly','Annual'))))) as salaryclass_id,
				IF(pp_stat_id='1','OPEN',IF(pp_stat_id='2','Locked - Pending Approval',IF(pp_stat_id='3','CLOSED','Post Adjustment'))) as pp_stat_id
						from payroll_pay_period ppp
						inner join payroll_pay_period_sched psar on (psar.pps_id=ppp.pps_id)
						$criteria
						$strOrderBy";

		// Field and Table Header Mapping
		$arrFields = array(
		 "viewdata"=>"Action"
		,"pps_name" => "Name"
		,"salaryclass_id" => "Type"
		,"pp_stat_id" => "Status"
		,"payperiod_start_date" => "Start"
		,"payperiod_end_date" => "End"
		,"payperiod_trans_date" => "Pay Date"
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
	 * Get all the Table Listings
	 *
	 * @return array
	 */
	function getTableList_Emp(){
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
				$qry[] = "mnu_name like '%$search_field%'";
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
		if ($_GET['statpos']=='process_payroll'){
			
			$qrysql= "inner join salary_info sal on (sal.emp_id=empinfo.emp_id)";
			$qrysql_= "inner join payroll_pps_user pps on (pps.emp_id=empinfo.emp_id)";
			$sqlsal = "Select DATE_FORMAT(payperiod_start_date,'%Y-%m-%d') as payperiod_start_date, DATE_FORMAT(payperiod_end_date,'%Y-%m-%d') as payperiod_end_date from payroll_pay_period where payperiod_id='".$_GET['ppsched_view']."'";
			$result = $this->conn->Execute($sqlsal);
			
			$qry[]="empinfo.emp_id not in (select a.emp_id from payroll_pps_user a inner join payroll_paystub_report re on (a.emp_id=re.emp_id) where payperiod_id = '".$_GET['ppsched_view']."')";
			$qry[]="sal.salaryinfo_effectdate <= '".$result->fields['payperiod_end_date']."'";
			$qry[]="pps.pps_id = '".$_GET['ppsched']."'";
		}else{
			$qry[]="empinfo.emp_id not in (select a.emp_id from payroll_pps_user a)";
		}
		
        $qry[] = "empinfo.emp_stat = 1";
		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		 "psar_name" => "psar_name"
		,"psar_desc" => "psar_desc"
		,"jobpos_name" => "jobpos_name"
		,"psar_frequency_id" => "psar_frequency_id"
		,"psatype" => "psatype"
		);

		if(isset($_GET['sortby'])){
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}

		//@note: this is used to count and check all the checkbox.
		//@note set t1 = 0
		$sql = "set @t1:=0";
		$this->conn->Execute($sql);

		//get total number of records and pass it to the javascript function CheckAll
			$sql_ = "select count(*) as mycount_
						from emp_masterfile empinfo
						inner join emp_personal_info pinfo on (pinfo.pi_id=empinfo.pi_id)
						inner join app_userdept dept on (dept.ud_id=empinfo.ud_id)
						inner join emp_position post on (post.post_id=empinfo.post_id)
					$qrysql		
					$qrysql_		
					$criteria
					$strOrderBy";
			$rsResult = $this->conn->Execute($sql_);
			if(!$rsResult->EOF){
				$mycount = $rsResult->fields['mycount_'];
			}

		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$ctr=0;
		$chkAttend = "<input type=\"checkbox\" name=\"chkAttend[]\" id=\"chkAttend[',@t1:=@t1+1,']\" value=\"',empinfo.emp_id,'\" onclick=\"javascript:UncheckAll(".$mycount.");\">";

		// SqlAll Query
		$sql = "select pinfo.pi_lname,pinfo.pi_fname, dept.ud_name,  post.post_name, CONCAT('$chkAttend') as chkbox, empinfo.emp_idnum
						from emp_masterfile empinfo
						inner join emp_personal_info pinfo on (pinfo.pi_id=empinfo.pi_id)
						inner join app_userdept dept on (dept.ud_id=empinfo.ud_id)
						inner join emp_position post on (post.post_id=empinfo.post_id)
						$qrysql
						$qrysql_
						$criteria
						$strOrderBy";

		// Field and Table Header Mapping
		$arrFields = array(
		 "chkbox" => "<input type=\"checkbox\" name=\"chkAttendAll\" id=\"chkAttendAll\" onclick=\"javascript:CheckAll(".$mycount.");\">"
		,"emp_idnum" => "Employee No."
		,"pi_fname" => "First Name"
		,"pi_lname" => "Last Name"
		,"post_name" => "Position"
		,"ud_name" => "Department"
		);

		// Column (table data) User Defined Attributes
		$arrAttribs = array(
		 "mnu_ord"=>" align='right'"
		,"viewdata"=>"width='30' align='center'"
		,"chkbox"=>"width='10' align='center'"
		);

		// Process the Table List
		$tblDisplayList = new clsTableList($this->conn);
//		$tblDisplayList->tblBlock->templateFile = "table2.tpl.php";
		$tblDisplayList->arrFields = $arrFields;
		$tblDisplayList->paginator->linkPage = "?$queryStr";
		$tblDisplayList->sqlAll = $sql;
		$tblDisplayList->sqlCount = $sqlcount;
		$tblDisplayList->tblBlock->templateFile = "table_nosort.tpl.php";
		$tblDisplayList->tblBlock->assign("noSearchStart","<!--");
		$tblDisplayList->tblBlock->assign("noSearchEnd","-->");

		return $tblDisplayList->getTableList($arrAttribs);
	}
	
	/**
	 * Get all the Table Listings
	 *
	 * @return array
	 */
	function getTableList_EmpSave(){
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
        $qry[]  = "empinfo.emp_stat = 1";
        
		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		 "viewdata"=>"viewdata"
		,"emp_idnum" => "emp_idnum"
		,"pi_fname" => "pi_fname"
		,"pi_lname" => "pi_lname"
		,"post_name" => "post_name"
		,"ud_name" => "ud_name"
		);

		if(isset($_GET['sortby'])){
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}

		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$delLink = "<a href=\"?statpos=mnge_pg&empinput=',psar.pps_id,'&empinput_del=',psaru.ppsu_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_THEME_URLPATH.SYSCONFIG_THEME."/images/admin/trash.gif\" title=\"Delete\" hspace=\"2px\"  border=0></a>";

		// SqlAll Query
		$sql = "select psaru.*,pinfo.pi_lname,pinfo.pi_fname, dept.ud_name, psar.*, post.post_name,
				CONCAT('$delLink') as viewdata,empinfo.emp_idnum
						from payroll_pps_user psaru
						inner join payroll_pay_period_sched psar on (psar.pps_id=psaru.pps_id)
						inner join emp_masterfile empinfo on (empinfo.emp_id=psaru.emp_id)
						inner join emp_personal_info pinfo on (pinfo.pi_id=empinfo.pi_id)
						inner join app_userdept dept on (dept.ud_id=empinfo.ud_id)
						inner join emp_position post on (post.post_id=empinfo.post_id)
						$criteria
						$strOrderBy";

		// Field and Table Header Mapping
		$arrFields = array(
		 "viewdata"=>"Action"
		,"emp_idnum" => "Emp No."
		,"pi_fname" => "First Name"
		,"pi_lname" => "Last Name"
		,"post_name" => "Position"
		,"ud_name" => "Department"
		);

		// Column (table data) User Defined Attributes
		$arrAttribs = array(
		"mnu_ord"=>" align='right'",
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
	 * Generate Payslip
	 *
	 * @param unknown_type $payperiod_id_
	 * @param unknown_type $emp_id_
	 * @return unknown
	 */
	function doSaveGeneReport($payperiod_id_ = null, $pData){
		$retval = "";
		if (is_null($payperiod_id_ )) {
			return $retval;
		}

		$ctr = 0;
		do {	
			/* save paystub, */
			$paystub_id = $this->doSavePayStub($pData['chkAttend'][$ctr],$payperiod_id_);
			
			/* compute rate per day, per sec per employee to be used in computation */
//			$rateperday[$i] = $this->getrate($retval[$i]['salaryclass_id'],$retval[$i]['compensation_basic_salary'],1);
//			$ratepersec[$i] = $this->getrate($retval[$i]['salaryclass_id'],$retval[$i]['compensation_basic_salary'],2);
//          $rateperhour[$i] = $this->getrate($retval[$i]['salaryclass_id'],$retval[$i]['compensation_basic_salary'],3);
			
			$empinfo = "select *,a.emp_id,a.comp_id, CONCAT(g.pi_fname,' ',UPPER(SUBSTRING(g.pi_mname,1,1)),'. ',g.pi_lname) as fullname, bank.bankiemp_acct_no, blist.banklist_name
							from emp_masterfile a
							inner join salary_info b on (b.emp_id=a.emp_id)
							inner join payroll_pps_user c on (c.emp_id = a.emp_id)
							inner join payroll_pay_period_sched d on (d.pps_id=c.pps_id)
							inner join factor_rate e on (e.fr_id=d.fr_id)
							inner join payroll_pay_period f on (f.pps_id=d.pps_id)
							inner join emp_personal_info g on (g.pi_id=a.pi_id)
							inner join emp_position h on (h.post_id=a.post_id)
							inner join company_info i on (i.comp_id=a.comp_id)
							inner join app_userdept j on (j.ud_id=a.ud_id)
							inner join emp_type z on (z.emptype_id=a.emptype_id)
							left join bank_infoemp bank on (bank.emp_id=a.emp_id)
							left join bank_info binfo on (binfo.bank_id=bank.bank_id)
							left join bank_list blist on blist.banklist_id=binfo.banklist_id
							where a.emp_id='".$pData['chkAttend'][$ctr]."' and c.pps_id='".$_GET['ppsched']."' and f.payperiod_id='".$payperiod_id_."'";
			$rsResult = $this->conn->Execute($empinfo);
//			printa($rsResult);
//			exit;
			$rsResult->fields['payperiod_start_date'];
			/*compute rate per day, per hour, per sec per employee */
			if ($rsResult->fields['salarytype_id']==5) {
				//convert monthly rate
				$rateperday[$ctr] = (($rsResult->fields['salaryinfo_basicrate']*12)/$rsResult->fields['fr_dayperyear']);
				$rateperhour[$ctr] = (($rateperday[$ctr])/$rsResult->fields['fr_hrperday']);
				$ratepersec[$ctr] = (($rateperhour[$ctr])/3600);
			} elseif ($rsResult->fields['salarytype_id']==1) {
				//convert hourly rate 
				$rateperday[$ctr] = ($rsResult->fields['salaryinfo_basicrate']*$rsResult->fields['fr_hrperday']);
				$rateperhour[$ctr] = $rsResult->fields['salaryinfo_basicrate'];
				$ratepersec[$ctr] = ($rsResult->fields['salaryinfo_basicrate']/3600);
			} elseif ($rsResult->fields['salarytype_id']==3) {
				//convert weekly rate
				$rateperday[$ctr] = ($rsResult->fields['salaryinfo_basicrate']/$rsResult->fields['fr_dayperweek']);
				$rateperhour[$ctr] = (($rateperday[$ctr])/$rsResult->fields['fr_hrperday']);
				$ratepersec[$ctr] = (($rateperhour[$ctr])/3600);
			} elseif ($rsResult->fields['salarytype_id']==6) {
				//convert annual rate to second
				$rateperday[$ctr] = ($rsResult->fields['salaryinfo_basicrate']/$rsResult->fields['fr_dayperyear']);
				$rateperhour[$ctr] = (($rateperday[$ctr])/$rsResult->fields['fr_hrperday']);
				$ratepersec[$ctr] = (($rateperhour[$ctr])/3600);
			} elseif ($rsResult->fields['salarytype_id']==4) {
				//convert bi-weekly rate
				$rateperday[$ctr] = ($rsResult->fields['salaryinfo_basicrate']/($rsResult->fields['fr_dayperweek'] * 2));
				$rateperhour[$ctr] = (($rateperday[$ctr])/$rsResult->fields['fr_hrperday']);
				$ratepersec[$ctr] = (($rateperhour[$ctr])/3600);
			} else {
				//convert daily rate
				$rateperday[$ctr] = $rsResult->fields['salaryinfo_basicrate'];
				$rateperhour[$ctr] = ($rateperhour[$ctr]/$rsResult->fields['fr_hrperday']);
				$ratepersec[$ctr] = (($rateperhour[$ctr])/3600);
			}

			//get regular Time
//			$varTotalRugalarTime = $this->getSumTotalTimeByPayPeriod($retval[$i]['emp_id'],$payperiod_id_,10,10,0);
//			printa($retval[$i]['fullname']);
//			printa($ratepersec[$i]);

//			$varTotalRugalarTime = $this->getSumTotalTimeByPayPeriod($retval[$i]['emp_id'],$payperiod_id_,10,10,0);
			
			//compute regular time base to payperiod type.
			//eg. semi-monthy, monthly, daily ect.
			//base on regular time without OT and lates... to follow un...
            //Daily
			if ($rsResult->fields['salaryclass_id'] == 1) {

					$varTotalRugalarTimeRate[$ctr] = $rateperday[$ctr];
     
            //weekly
			}elseif ($rsResult->fields['salaryclass_id'] == 2){
				
				if($rsResult->fields['salarytype_id']==5){
					//monthly
					$varTotalRugalarTimeRate[$ctr] = (($rsResult->fields['salaryinfo_basicrate']*12)/52);
				}elseif ($rsResult->fields['salarytype_id']==3){
					//weekly
					$varTotalRugalarTimeRate[$ctr] = $rsResult->fields['salaryinfo_basicrate'];
				}elseif ($rsResult->fields['salarytype_id']==6){
					//annual
					$varTotalRugalarTimeRate[$ctr] = $rsResult->fields['salaryinfo_basicrate']/52;
				}elseif ($rsResult->fields['salarytype_id']==4){
					//bi-weekly
					$varTotalRugalarTimeRate[$ctr] = $rsResult->fields['salaryinfo_basicrate']/2;
				}else{
					//daily & hourly
					$varTotalRugalarTimeRate[$ctr] = $rateperday[$ctr] * $rsResult->fields['fr_dayperweek'];
				}
			
			//bi-weekly
			}elseif ($rsResult->fields['salaryclass_id'] == 3){
				if($rsResult->fields['salarytype_id']==5){
					//monthly
					$varTotalRugalarTimeRate[$ctr] = (($rsResult->fields['salaryinfo_basicrate']*12)/26);
				}elseif ($rsResult->fields['salarytype_id']==3){
					//weekly
					$varTotalRugalarTimeRate[$ctr] = $rsResult->fields['salaryinfo_basicrate']*2;
				}elseif ($rsResult->fields['salarytype_id']==6){
					//annual
					$varTotalRugalarTimeRate[$ctr] = $rsResult->fields['salaryinfo_basicrate']/26;
				}elseif ($rsResult->fields['salarytype_id']==4){
					//bi-weekly
					$varTotalRugalarTimeRate[$ctr] = $rsResult->fields['salaryinfo_basicrate'];
				}else{
					//daily & hourly
					$varTotalRugalarTimeRate[$ctr] = ($rateperday[$ctr] * ($rsResult->fields['fr_dayperweek']*2));
				}
			
			// semi-monthly
			}elseif ($rsResult->fields['salaryclass_id'] == 4){
				if($rsResult->fields['salarytype_id']==5){
					//monthly
					if($rsResult->fields['emp_hiredate'] > date("Y-m-d",$rsResult->fields['payperiod_start_date'])){
//						@todo: for back pay :D 
//						$vardate = $rsResult->fields['emp_hiredate'] - $rsResult->fields['payperiod_start_date'];
//						echo $rsResult->fields['emp_hiredate'];
//						echo "<br>"; 
//						echo date('Y-m-d',$rsResult->fields['payperiod_start_date']);
//						echo "<br>";
//						echo $vardate;
//						echo "<br>";
//						echo "hi";
//						exit;
						$varTotalRugalarTimeRate[$ctr] = ($rsResult->fields['salaryinfo_basicrate']/2);
					}else{
						$varTotalRugalarTimeRate[$ctr] = ($rsResult->fields['salaryinfo_basicrate']/2);	
					}
				}elseif ($rsResult->fields['salarytype_id']==3){
					//weekly
					$varTotalRugalarTimeRate[$ctr] = $rsResult->fields['salaryinfo_basicrate']*2;
				}elseif ($rsResult->fields['salarytype_id']==6){
					//annual
					$varTotalRugalarTimeRate[$ctr] = (($rsResult->fields['salaryinfo_basicrate']/12)/2);
				}elseif ($rsResult->fields['salarytype_id']==4){
					//bi-weekly
					$varTotalRugalarTimeRate[$ctr] = $rsResult->fields['salaryinfo_basicrate'];
				}else{
					//daily & hourly
					$varTotalRugalarTimeRate[$ctr] = ($rateperday[$ctr] * ($rsResult->fields['fr_dayperweek']*2));
				}
            //monthly
			}elseif ($rsResult->fields['salaryclass_id'] == 5){
				if($rsResult->fields['salarytype_id']==5){
					//monthly
					$varTotalRugalarTimeRate[$ctr] = $rsResult->fields['salaryinfo_basicrate'];
				}elseif ($rsResult->fields['salarytype_id']==3){
					//weekly
					$varTotalRugalarTimeRate[$ctr] = (($rsResult->fields['salaryinfo_basicrate']*12)/52);
				}elseif ($rsResult->fields['salarytype_id']==6){
					//annual
					$varTotalRugalarTimeRate[$ctr] = $rsResult->fields['salaryinfo_basicrate']/12;
				}elseif ($rsResult->fields['salarytype_id']==4){
					//bi-weekly
					$varTotalRugalarTimeRate[$ctr] = (($rsResult->fields['salaryinfo_basicrate']*12)/13);
				}else{
					//daily & hourly
					$varTotalRugalarTimeRate[$ctr] = (($rateperday[$ctr] * $rsResult->fields['fr_dayperyear'])/12);
				}
			//annual	
			}else {
				if($rsResult->fields['salarytype_id']==5){
					//monthly
					$varTotalRugalarTimeRate[$ctr] = $rsResult->fields['salaryinfo_basicrate']*12;
				}elseif ($rsResult->fields['salarytype_id']==3){
					//weekly
					$varTotalRugalarTimeRate[$ctr] = $rsResult->fields['salaryinfo_basicrate']*52;
				}elseif ($rsResult->fields['salarytype_id']==6){
					//annual
					$varTotalRugalarTimeRate[$ctr] = $rsResult->fields['salaryinfo_basicrate'];
				}elseif ($rsResult->fields['salarytype_id']==4){
					//bi-weekly
					$varTotalRugalarTimeRate[$ctr] = $rsResult->fields['salaryinfo_basicrate']*26;
				}else{
					//daily & hourly
					$varTotalRugalarTimeRate[$ctr] = ($rateperday[$ctr] * $rsResult->fields['fr_dayperyear']);
				}
			}
			//call function to save Paystub Account
            if($emp_id_ == ""){
				$this->doSavePayStubEntry($paystub_id, 1, $varTotalRugalarTimeRate[$ctr]);
            }
//--------------------------------------------------------------------------------------
            // todo: compute OT base on amendments that links to OT table for rate...
            // pag aaralan ko p to..
			//get total OT TIME
//			$varData[$i]['totalOTtime'] = $this->getSumTotalTimeByPayPeriod($retval[$i]['emp_id'],$payperiod_id_,10,30,30);
//			$totalRegtime = $varData[$i]['totalOTtime'];
////            printa($retval[$i]['fullname']);
////            printa($totalRegtime);
//			for ($a=0;$a<count($totalRegtime);$a++) {
//				
//				$varOTTotalrate_ = $totalRegtime[$a]['otpol_rate'] * $ratepersec[$i];
//
//				$varOTTotal_ = ($totalRegtime[$a]['sumtotal_time'] *$varOTTotalrate_);
//
//				$vartotalotrate = $varOTTotal_;
//
//					$this->otDetail[$i][$a] = array(
//									"totaltimesec"=>$totalRegtime[$a]['sumtotal_time']
//									,"totaltimehr"=>($totalRegtime[$a]['sumtotal_time'])
////									,"rate"=>$totalRegtime[$a]['otpol_rate']
//									,"psa_name"=>$totalRegtime[$a]['psa_name']
//									,"otamount"=>$vartotalotrate
//								);
//
//				$varTotalallOT[$i] += $varOTTotal_;
//				if($emp_id_ == ""){
//				$this->doSavePayStubEntry($paystub_id,$totalRegtime[$a]['psa_id'],$vartotalotrate,$totalRegtime[$a]['otpol_rate'],$vartotalotrate,$totalRegtime[$a]['sumtotal_time']);
//                }
//
//            }
//
//			//sum of all OT
//			$varSumAllOTRate[$i] = $varTotalallOT[$i];
//
//			//get total Holiday TIME
//			$varData[$i]['totalHolidaytime'] = $this->getSumTotalTimeByPayPeriod($retval[$i]['emp_id'],$payperiod_id_,10,10,20);
//			$totalHolRegtime = $varData[$i]['totalHolidaytime'];
//
//			 for ($a=0;$a<count($totalHolRegtime);$a++) {
//
//				$varHOLTotal_ = ($totalHolRegtime[$a]['sumtotal_time'] * $ratepersec[$i])*($totalHolRegtime[$a]['holpol_rate']-1);
//				$vartotalholrate = $varHOLTotal_;
//
//					$this->holidayDetail[$i][$a] = array(
//									"totaltimesec"=>$totalHolRegtime[$a]['sumtotal_time']
//									,"totaltimehr"=>($totalHolRegtime[$a]['sumtotal_time'])
////									,"rate"=>$totalRegtime[$a]['otpol_rate']
//									,"psa_name"=>$totalHolRegtime[$a]['psa_name']
//									,"holamount"=>$vartotalholrate
//								);
//
//				$varTotalallHoliday[$i] += $varHOLTotal_;
//
//				if($emp_id_ == ""){
//				$this->doSavePayStubEntry($paystub_id,$totalHolRegtime[$a]['psa_id'],$vartotalholrate,$totalHolRegtime[$a]['holpol_rate'],$vartotalholrate,$totalHolRegtime[$a]['sumtotal_time']);
//                }
//
//             }
//
//			//sum of all Holiday
//			$varSumAllHOLRate[$i] = $varTotalallHoliday[$i];
//--------------------------------------------------------------------------------------------->

			//------------------------------------------------------------------------------------->>>
			//Call the getAmendments function
			$amendments = $this->getAmendments($pData['chkAttend'][$ctr],$rsResult->fields['payperiod_trans_date'],$rsResult->fields['payperiod_start_date'],$rsResult->fields['payperiod_end_date']);
			$totalTEarningAmendments[$ctr] = 0;
			$totalNTEarningAmendments[$ctr] = 0;
			for ($r=0;$r<count($amendments);$r++) {
				$this->doSavePayStubEntry($paystub_id, $amendments[$r]['psa_id'], $amendments[$r]['psamend_amount'], $amendments[$r]['psamend_rate'],$amendments[$r]['psamend_unit']);
				if ($amendments[$r]['psa_type']==1) {
					if ($amendments[$r]['psamend_istaxable']==1) {
						$totalTEarningAmendments[$ctr] += $amendments[$r]['psamend_amount'];
					} else {
						$totalNTEarningAmendments[$ctr] += $amendments[$r]['psamend_amount'];
					}
				} elseif ($amendments[$r]['psa_type']==2) {
                    if ($amendments[$r]['psamend_istaxable']==1) {
						$totalTEDeductionAmendments[$ctr] += $amendments[$r]['psamend_amount'];
					} else {
						$totalNTEDeductionAmendments[$ctr] += $amendments[$r]['psamend_amount'];
					}
				}
			}
			
//			bago to 2010-08-02
////			----------------------------------------------- Recurring Benifits & Deduction -------------------------------
////			This section is for recurring amendment...
//			$recurring_amendments = $this->getRecurringBenifitsDeduction($pData['chkAttend'][$ctr],$rsResult->fields['payperiod_trans_date'],$rsResult->fields['payperiod_start_date'],$rsResult->fields['payperiod_end_date']);
//			
////			echo "test";
////			
////			printa($recurring_amendments);
////			exit;
//
////			$totalTEarningAmendments[$ctr] = 0;
////			$totalNTEarningAmendments[$ctr] = 0;
//			for ($r=0;$r<count($recurring_amendments);$r++) {
//
//				$this->doSavePayStubEntry($paystub_id, $recurring_amendments[$r]['psa_id'], $recurring_amendments[$r]['ben_payperday']);
//				
////				exit;
//				if ($recurring_amendments[$r]['psa_type']==1) {
//					
////					if ($recurring_amendments[$r]['psamend_istaxable']==1) {
////
////						$totalTEarningAmendments[$ctr] += $recurring_amendments[$r]['ben_payperday'];
////
////					} else {
//
//						$totalNTEarningAmendments[$ctr] += $recurring_amendments[$r]['ben_payperday'];
//
////					}
//				} elseif ($recurring_amendments[$r]['psa_type']==2) {
//
////                    if ($recurring_amendments[$r]['psamend_istaxable']==1) {
////
////						$totalTEDeductionAmendments[$ctr] += $recurring_amendments[$r]['ben_payperday'];
////
////					} else {
//
//						$totalNTEDeductionAmendments[$ctr] += $recurring_amendments[$r]['ben_payperday'];
//
////					}
//				}
//			}
//			
////			------------------------------------------ END -----------------------------------------------------------
			
			
//			this section need to study p.. check ko p kung applicable p b sya sa ngaung payroll process...			
//			
////			------------------------recurring amendment----------------------------------
//			$recurring_amendments = $this->getRecurringAmendments($retval[$i]['emp_id'],$retval[$i]['payperiod_trans_date'], $retval[$i]['payperiod_trans_date']);
//
//			for ($r=0;$r<count($recurring_amendments);$r++) {
//
//				$this->doSavePayStubEntry($paystub_id, $recurring_amendments[$r]['psa_id'], $recurring_amendments[$r]['psar_amount'], $recurring_amendments[$r]['psar_rate'],$recurring_amendments[$r]['psar_units']);
//				if ($recurring_amendments[$r]['psa_type']==1) {
//
//					if ($recurring_amendments[$r]['psar_istaxable']==1) {
//
//						$totalTEarningAmendments[$i] += $recurring_amendments[$r]['psar_amount'];
//
//					} else {
//
//						$totalNTEarningAmendments[$i] += $recurring_amendments[$r]['psar_amount'];
//
//					}
//				} elseif ($recurring_amendments[$r]['psa_type']==2) {
//                    if ($recurring_amendments[$r]['psar_istaxable']==1) {
//
//						$totalTEDeductionAmendments[$i] += $recurring_amendments[$r]['psar_amount'];
//
//					} else {
//
//						$totalNTEDeductionAmendments[$i] += $recurring_amendments[$r]['psar_amount'];
//
//					}
//				}
//			}
//
////			____________________________end____________________________________
//
//
//			$taxableGross =  ($varTotalRugalarTimeRate[$i] + $varSumAllOTRate[$i] + $totalTEarningAmendments[$i] + $varSumAllHOLRate[$i] + $varSumAllPremiumRate[$i])-$totalTEDeductionAmendments[$i];
			
			$taxableGross =  ($varTotalRugalarTimeRate[$ctr] + $totalTEarningAmendments[$ctr]) - $totalTEDeductionAmendments[$ctr];// total gross
			$basicgrosspg = $varTotalRugalarTimeRate[$ctr]; // just basic pay
			$othertaxablegross = $totalTEarningAmendments[$ctr]; // other taxable income additional
			$nontaxableGross =  $totalNTEarningAmendments[$ctr]; //other income not taxable

			//$varGrosspay =  $varTotalRugalarTimeRate[$i] + $varSumAllOTRate[$i];
            if($emp_id_ == ""){
			$this->doSavePayStubEntry($paystub_id, 11, $taxableGross);
            }
            
			//deduction
			// check ko p to kung applicable p sya...
			$varData[$ctr]['typeDeduc'] = $this->getTypeDeduction();
			$dectype = $varData[$ctr]['typeDeduc'];
//			printa($dectype);
			
			/*
			modified by Rodwin
			wag muna ideduct ang tax
			deduct it after the ss,phic,hdmf, and others are duducted
			*/
			for ($b=0;$b<count($dectype);$b++){
				$qry = array();
//				$qry[] = "a.emp_id = '".$retval[$i]['emp_id']."'";
				$qry[] = "a.dec_id = '".$dectype[$b]['dec_id']."'";
				$criteria = count($qry)>0 ? " where ".implode(' and ',$qry) : '';
				$sql = "Select * from statutory_contribution a $criteria";
				$varDeducH = $this->conn->Execute($sql);
				if(!$varDeducH->EOF){
					$varHdeduc = $varDeducH->fields;
				}
				switch ($dectype[$b]['dec_id']) {
					case 1:
						$varDec = $this->getTotalDeductionByPayPeriod($pData['chkAttend'][$ctr],$varHdeduc['sc_id'],$dectype[$b]['dec_id'],$varTotalRugalarTimeRate[$ctr],$rsResult->fields['taxep_id']);
//						printa($retval[$i]['fullname']);
//                      printa($computable[$i]);
//                      printa($varDec);
                        if($rsResult->fields['salaryclass_id'] == 5){
                            $varDecSSS = $varDec['dd_phic_sss_ee']/2;
                            $varDecSSSEC = $varDec['dd_phic_sss_ec']/2;
                            $er[$ctr] = $varDec['dd_phic_sss_er']/2;
                            $varDecSSSER = $varDec['dd_phic_sss_er']/2;
                        }else{
                            $varDecSSS = $varDec['scr_ee'];
                            $varDecSSSEC = $varDec['scr_ec'];
                            $er[$ctr] = $varDec['scr_er'];
                            $varDecSSSER = $varDec['scr_er'];
                        }
						$totalDeDuction[$ctr] += $varDecSSS;
						$psaSSS = "SSS";
						if($emp_id_ == ""){
                            $this->doSavePayStubEntry($paystub_id, 15, $varDecSSS,$er[$ctr], $taxableGross,$varDecSSSEC);
                        }
						break;
					case 2:
						$varDec = $this->getTotalDeductionByPayPeriod($pData['chkAttend'][$ctr],$varHdeduc['sc_id'],$dectype[$b]['dec_id'],$varTotalRugalarTimeRate[$ctr],$rsResult->fields['taxep_id']);
//						printa($varDec);
                        if($rsResult->fields['salaryclass_id'] == 5){
                            $varDecPHIL = $varDec['dd_phic_sss_ee']/2;
                            $er[$ctr] = $varDec['dd_phic_sss_er']/2;
                            $varDecPHILER = $varDec['dd_phic_sss_er']/2;
                        }else{
                            $varDecPHIL = $varDec['scr_ee'];
                            $er[$ctr] = $varDec['scr_er'];
                            $varDecPHILER = $varDec['scr_er'];
                        }
						
						$totalDeDuction[$ctr] += $varDecPHIL;
						$psaPhil = "PHIC";
						if($emp_id_ == ""){
                            $this->doSavePayStubEntry($paystub_id, 17, $varDecPHIL,$er[$ctr], $taxableGross,0);
                        }
						break;
//					case 3:
//printa($varDec);
//						$varDec = $this->getTotalDeductionByPayPeriod($pData['chkAttend'][$ctr],$varHdeduc['sc_id'],$dectype[$b]['dec_id'],$varTotalRugalarTimeRate[$i],$rsResult->fields['taxep_id']);
////                        printa($varDec);
//                        if($varDec['dduct_isnominal'] == 10){
//                            if($rsResult->fields['salaryclass_id'] == 6){
//                                $varDecPagibig = (($varDec['dd_phic_sss_ee']*$computable[$i])/2);
//                                $varDecPagibigEmployer = ($varDec['dd_phic_sss_er']*$computable[$i])/2;
//                            }else{
//                                $varDecPagibig = $varDec['dd_phic_sss_ee']*$computable[$i];
//                                $varDecPagibigEmployer = $varDec['dd_phic_sss_er']*$computable[$i];
//                            }
//                        }else{
//                            $varDecPagibig = $varDec['scr_ee'];
//                            $varDecPagibigEmployer = $varDec['scr_ec'];
//                        }
//
////                        printa($retval[$i]['fullname']);
////                        printa($retval[$i]['salaryclass_id']);
////                        printa($computable[$i]);
////                        printa($varDecPagibig);
//						$totalDeDuction[$i] += $varDecPagibig;
//						$psapagibig = "Pag-ibig";
//						if($emp_id_ == ""){
//                            $this->doSavePayStubEntry($paystub_id, $varDec['psa_id'], $varDecPagibig,$varDecPagibigEmployer, $taxableGross,0);
//                        }
//						break;

					default:
					break;
				}
			}
//			exit;
			
//
//			//company contribution/deductions
//			$company_deduction_cont[$i] = $this->getAllEmployeeCompanyContribution($retval[$i]['emp_id']);
//			if (count($company_deduction_cont[$i])>0) {
//
//				$contribution_nontaxable_[$i] = 0;
//				$contribution_taxable_[$i] = 0;
//
//				foreach ($company_deduction_cont[$i] as $keycont => $valcont){
//					if($valcont['cdc_type'] == 20){
//
//                        if($retval[$i]['salaryclass_id'] == 6){
//                            $contribution[$i] = ($valcont['cdc_percent']/100)*($computable[$i]/2);
//                        }else{
//                            $contribution[$i] = ($valcont['cdc_percent']/100)*($computable[$i]);
//                        }
//
//						if ($valcont['cdc_isemployer_share'] == 20) {
//
//                            if($retval[$i]['salaryclass_id'] == 6){
//                                $employer_share[$i] = ($valcont['cdc_employer_percent']/100)*($computable[$i]/2);
//                            }else{
//                                $employer_share[$i] = ($valcont['cdc_employer_percent']/100)*($computable[$i]);
//                            }
//						}
//							if ($valcont['cdc_taxable'] == 20) {
//                                if($retval[$i]['salaryclass_id'] == 6){
//                                    $contribution_taxable_[$i] +=  ($valcont['cdc_percent']/100)*($computable[$i]/2);
//                                }else{
//                                    $contribution_taxable_[$i] +=  ($valcont['cdc_percent']/100)*($computable[$i]);
//                                }
//							}else {
//
//                                if($retval[$i]['salaryclass_id'] == 6){
//                                    $contribution_nontaxable_[$i] +=  ($valcont['cdc_percent']/100)*($computable[$i]/2);
//                                }else{
//                                    $contribution_nontaxable_[$i] +=  ($valcont['cdc_percent']/100)*($computable[$i]);
//                                }
//
//							}
//					}else{
//
//						$contribution[$i] = $valcont['cdc_amount'];
//
//						if ($valcont['cdc_taxable'] == 20) {
//							$contribution_taxable_[$i] +=$valcont['cdc_amount'];
//						}else{
//							$contribution_nontaxable_[$i] +=$valcont['cdc_amount'];
//						}
//					}
//					$company_deduction_cont[$i][$keycont]['cont'] = $contribution[$i];
//
//				if($emp_id_ == ""){
//                    $this->doSavePayStubEntry($paystub_id, $valcont['psa_id'], $contribution[$i],$employer_share[$i], $taxableGross,0);
//                }
//
//                }
//				$company_deduction_cont['total_cont'] = $sum_contribution[$i];
//			}
//
//			----------------------------------------------------
//			paaaralan ko p to...
//			//get active provident loans
//			$loandetails[$i] = $this->getEmployeeActiveProvidentLoan($retval[$i]['emp_id'],$retval[$i]['payperiod_trans_date'],$paystub_id);
//
//			if (count($loandetails[$i])>0) {
//				foreach ($loandetails[$i] as $keydetails => $valdetails){
//					$totalloanpayment[$i] += $valdetails['pd_amortization'];
//				}
//			}
//
			//get gov/reg loans
			$govreg_loan[$ctr] = $this->getEmployeeActiveGovRegLoan($pData['chkAttend'][$ctr],$rsResult->fields['payperiod_trans_date'],$paystub_id);
//            printa($govreg_loan[$i]);
            if(count($govreg_loan[$ctr])>0){
				foreach ($govreg_loan[$ctr] as $keyregloan => $valregloan){
                    //check if negative number
                    if($valregloan['loan_payperperiod'] >0){
                        $totalregloan[$ctr] +=  $valregloan['loan_payperperiod'];
                    }
				}
			}

			
			//Deduction

//			echo $varDeduction  =  $contribution_taxable_[$i] + $varDecSSS + $varDecPHIL + $varDecPagibig;
//			papalitan din para lng sa pagibig 2010.2.23 jim
//			$varDeduction  =  $varDecSSS + $varDecPHIL + $varDecPagibig;
			$varDeduction  =  $totalDeDuction[$ctr] + 50;
//			echo"<br>";
			
            if($emp_id_ == ""){
			$this->doSavePayStubEntry($paystub_id, 5, $varDeduction);
            }
            
            ($varTotalRugalarTimeRate[$ctr]);
//          echo "<br>";
//			$gross = $taxableGross; //change ko muna sya 20100426 to this $basicgrosspg basic lng to wla png OT eh 
			$gross = $taxableGross + $totalNTEarningAmendments[$ctr]; // basic pay of employee
//			echo "<br>";

//			ps: binago ko to 20100505
//			$taxableGross_ = ($basicgrosspg - $varDeduction) + $othertaxablegross;
			$taxableGross_ = ($taxableGross - $varDeduction);
//			exit;
			
			/*
			this variable is only used to remove the pagibig and replace it
			by 50(semi), 100 lng kc ang taxable sa pagibig khit n 500 p ang cont
			*/
//			$taxableGross = ($taxableGross_ + $varDecPagibig)-50;
			$taxableGross = $taxableGross_;
//			----------------katulad ng query sa taas pro inihuli ang pagcompute ng tax, dpat bawas n yung mga $varDeduction
						$qry = array();

//						$qry[] = "a.emp_id = '".$pData['chkAttend'][$ctr]."'";
//						$qry[] = "b.dec_id = 5";

						$criteria = count($qry)>0 ? " where ".implode(' and ',$qry) : '';
						$sql = "Select *
										from tax_policy a
										$criteria";
						$varDeducH = $this->conn->Execute($sql);
						if(!$varDeducH->EOF){
							$varHdeduc = $varDeducH->fields;
						}
						$varDec = $this->getTotalTaxByPayPeriod($pData['chkAttend'][$ctr],$varHdeduc['tp_id'],2,$taxableGross,$rsResult->fields['taxep_id'],$rsResult->fields['salaryclass_id']);
						
//				printa($varDec);
//						echo "<br>";
//						echo $taxableGross;
//						echo "<br>";
						$varStax = $taxableGross - $varDec['tt_minamount'];
//						echo "<br>";
						$conVertTaxper =  $varDec['tt_over_pct'] / 100 ;
//						echo "<br>";
						$varStax_p =  number_format($varStax, 2, '.', '') * $conVertTaxper;
//						echo "<br>";
						$totalTax = number_format($varDec['tt_taxamount'], 2, '.', '') + number_format($varStax_p, 2, '.', '');
//						echo "<br>";
						$totalTax_ = number_format($totalTax, 2, '.', '');
						$psaTax = "Tax";
				if($emp_id_ == ""){
					$this->doSavePayStubEntry($paystub_id, 16, $totalTax, $taxableGross,$conVertTaxper);
                }
//			----------------
			/*now deduct the tax to the taxable gross
			n nabawasan n ng mga contribution (sss,phic,hdmf)
			*/
//			echo "<br>";
			$afterTaxGross = $taxableGross_ - $totalTax;
//			echo "<br>";
//          printa($retval[$i]['fullname']);

			//fetch employee benefits and add it in the net directly because it is not taxable
			$EmployeeBenefits[$ctr] = $this->dbFetchEmployeeBenefits($pData['chkAttend'][$ctr],$rsResult->fields['payperiod_trans_date'],$rsResult->fields['payperiod_start_date'],$rsResult->fields['payperiod_end_date']);
//			printa($EmployeeBenefits[$ctr]);
			
			//sum up the amount of benefits
			if (count($EmployeeBenefits[$ctr])>0) {
				foreach ($EmployeeBenefits[$ctr] as $keyben => $valben){
				$totalbenamount[$ctr] += $valben['ben_payperday'];
				}
			}
//			echo "<br>";
			$totalbenamount[$ctr];
//			exit;
//			echo "<br>";
			$grossandnontaxable = $nontaxableGross + $totalbenamount[$ctr];
			$otherdeducNtax = $totalNTEDeductionAmendments[$ctr] + $totalregloan[$ctr];
			$gross += $totalbenamount[$ctr];
			
			//NETPAY
			$varNetpay[$ctr] = $nontaxableGross + $afterTaxGross + $totalbenamount[$ctr] -($totalloanpayment[$ctr] + $totalregloan[$ctr] + $contribution_nontaxable_[$ctr] + $totalNTEDeductionAmendments[$ctr]);
			
//			exit;
			if($emp_id_ == ""){
            $this->doSavePayStubEntry($paystub_id, 13, $varNetpay[$ctr]);
            }

			//array structure of details on the paystub
			$arrPayStub[$ctr]['empinfo'] = array(
				 "emp_id" => $rsResult->fields['emp_id']
				,"emp_no" => $rsResult->fields['emp_idnum']
				,"fullname" => $rsResult->fields['fullname']
				,"jobpos_name" => $rsResult->fields['post_name']
				,"comp_name" => $rsResult->fields['comp_name']
				,"ud_name" => $rsResult->fields['ud_name']
				,"tax_ex_name" => $varDec['taxep_name']
				,"comp_id" => $rsResult->fields['comp_id']
				,"ud_id" => $rsResult->fields['ud_id']
				,"emptype_name" => $rsResult->fields['emptype_name']
				,"bankiemp_acct_no" => $rsResult->fields['bankiemp_acct_no']
				,"banklist_name" => $rsResult->fields['banklist_name']
				,"pi_emailone" => $rsResult->fields['pi_emailone']
				,"taxep_id" => $rsResult->fields['taxep_id']
				,"salaryclass_id" => $rsResult->fields['salaryclass_id']
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
					)
					,"paystubaccount" => array(
						 "earning" => array(
							 "basic" => $basic[$ctr],
							 "Regulartime" => $varTotalRugalarTimeRate[$ctr]
							,"OT" => array(
								 "OTDetails" => $this->otDetail[$ctr]
								,"TotalallOT" => "0"
								,"OTbackpay" => "0"
								,"SumAllOTRate" => "0"
								)
							/*,"Holiday" => array(
								$this->holidayDetail[$ctr]
								,"SumAllHolRate" => $varSumAllHOLRate[$ctr]
								)
							,"Premium" => array(
								$this->premiumDetail[$ctr]
								,"SumAllPremiumRate" => $varSumAllPremiumRate[$ctr]
								)*/
						)
						,"deduction" => array(
							 "SSS" => $varDecSSS
							,"SSSER" => $varDecSSSER
							,"SSSEC" => $varDecSSSEC
							,"PhilHealth" => $varDecPHIL
							,"PhilHealthER" => $varDecPHILER
							,"Pag-ibig" => "50"
							,"Pag-ibigER" => "50"
							,"Others" => $varDecOther
						 )
						,"TUA"=> array(
							/* "lates" => $totalAmtLates[$i]
							,"undertime" => $totalAmtUndertime[$i]
							,"absent" => $absent[$i]*/
							"TotalLeave" => "0"
						)
						/*,"company_duduction_total" => $contribution_nontaxable_[$ctr]
						,"company_duduction" => $company_deduction_cont[$ctr]
						,"provident" => $totalloanpayment[$ctr]
						,"amt_loans" => $totalloanpayment[$ctr] + $totalregloan[$ctr]*/
						,"government_regular" => $govreg_loan[$ctr]
						,"benefits" => $EmployeeBenefits[$ctr]
						,"amendments" => array(
								 $amendments
								,$recurring_amendments
								,"total_TEarning" => $totalTEarningAmendments[ctr]
								,"total_NTEarning" => $totalNTEarningAmendments[$ctr]
								,"total_TDeduction" => $totalTEDeductionAmendments[$ctr]
								,"total_NTDeduction" => $totalNTEDeductionAmendments[$ctr]
						)
						,"pstotal" => array(
						 	 "gross" => $gross
						 	,"Basic Salary" => $rsResult->fields['salaryinfo_basicrate']
						 	,"PGsalary" => $basicgrosspg
						 	,"gross_nontaxable_income" => $grossandnontaxable
						 	,"taxable_Gross" => $taxableGross_
						 	,"Deduction" => $varDeduction+$totalTax+$totalNTEDeductionAmendments[$ctr]+$totalregloan[$ctr]
						 	,"SatutoryDeduction" => $varDeduction
						 	,"W/H Tax" => $totalTax
						 	,"aftertaxgross" => $afterTaxGross
						 	,"Net Pay" => $varNetpay[$ctr]
						 	,"other_taxable_income" => $totalTEarningAmendments[$ctr]
						 	,"other_deduction" => $otherdeducNtax
						 )
					)
			);
		  $ctr++;
		} while($ctr < sizeof($pData['chkAttend']));
        
		$this->doSavePayStubArr($arrPayStub);
//      printa($arrPayStub);
//		exit;
        if($emp_id_ == ""){
            return $retval;
        }else{
            return $arrPayStub;
        }
	}
	
	// author:rblising
	//Takes $needles as an array, loops through them returning matching
	//keys => value pairs from haystack
	//Useful for filtering results to a select box, like status.
	function getByArray($needles, $haystack) {
		if (!is_array($needles) ) {
			$needles = array($needles);
		}
//		printa($needles);
		$needles = array_unique($needles);
		foreach($needles as $needle) {
			if ( isset($haystack[$needle]) ) {
				$retval[$needle] = $haystack[$needle];
			}
		}
		if ( isset($retval) ) {
			return $retval;
		}
		return FALSE;
	}
	
	/**
	 * Get the Total Employee per pay group..
	 *
	 * @return unknown
	 */
	function get_totalEmp($pData){
		$objData = $this->conn->Execute("Select count(c.emp_id) as totalemp from payroll_pps_user a inner join emp_masterfile c on (a.emp_id = c.emp_id) where a.pps_id='".$pData."'");
		if(!$objData->EOF){
			return $objData->fields;
		}
	}
	
	function doSavePayStub($val,$payperiod_id_) {
//		printa($val);
//		$this->conn->debug=1;
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
	
	function getrate($salary_class = "", $compensation= "", $rate_type = ""){
		/*
		TODO: wla pa computattion para
			  sa weekly, annual, bi-weekly
		*/
		if ($salary_class == "") {
			return 0;
		}
		if ($compensation == "") {
			return 0;
		}
		if ($salary_class==5) {
//			monthly
            $periodic = ($compensation*12)/13;
			$rateper_hour =  (($periodic)/2)/112;
			$rateper_sec = (($periodic/2)/112)/60/60;
//            printa($periodic);
//            printa($rateper_hour);
		}elseif ($salary_class==1) {
//			hourly
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
	 * Enter description here...
	 *
	 * @param unknown_type $paystub_id
	 * @param unknown_type $paystubaccount
	 * @param unknown_type $psatotal_
	 */
	function doSavePayStubEntry($paystub_id_ = null, $psa_id_ = null, $psatotal_ = 0,$psatotal_employer = 0, $ppe_rate_ = 0, $ppe_units_ = 0) {
		$fldspse = array();
		$sql = "select * from payroll_paystub_entry where paystub_id = $paystub_id_ and psa_id = $psa_id_";
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
			$sql = "update payroll_paystub_entry set $fieldspse where paystub_id = $paystub_id_ and psa_id = $psa_id_";
			$this->conn->Execute($sql);
		}else{
			$fldspse[] = "ppe_addwho = '".AppUser::getData('user_name')."'";
			$fieldspse = implode(", ",$fldspse);
			$sql = "insert into payroll_paystub_entry set $fieldspse";
			$this->conn->Execute($sql);
		}
	}
	
	/**
	 * Get Amendments
	 *
	 * @param integer $empid
	 * @return fields
	 *
	 */
	function getAmendments($empid_,$transdate,$startdate,$enddate) {
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
	 * Get Amendments
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
	 *
	 * @param unknown_type $emp_id_
	 * @return unknown
	 */
	function getTypeDeduction() {
//		$this->conn->debug=1;
//		$emp_id_ = null
		$arrData = array();

//		if (is_null($emp_id_)) {
//				return $arrData;
//		}

//		$qry = array();
//
//		$qry[] = "a.emp_id = $emp_id_";
//        //tax not included
//		$qry[] = "b.deductions_id != 2";
//		
//		$criteria = count($qry)>0 ? " where ".implode(' and ',$qry) : '';
//		$sql = "select a.*, b.deductions_id
//						from emp_masterfile a
//						inner join emp_deductions_detail b on (a.emp_id = b.emp_id)
//						$criteria";
//
//		$rsResult = $this->conn->Execute($sql);
//
//		while (!$rsResult->EOF) {
//			$arrData[] = $rsResult->fields;
//			$rsResult->MoveNext();
//		}

//dapat ayusin to.. para lng gumana...
		$qry = array();
//		$qry[] = "a.emp_id = $emp_id_";
        //tax not included
		$qry[] = "a.dec_id not in (4,5)";
		$criteria = count($qry)>0 ? " where ".implode(' and ',$qry) : '';
		$sql = "select a.* from deduction_type a $criteria";
		$rsResult = $this->conn->Execute($sql);
		while (!$rsResult->EOF) {
			$arrData[] = $rsResult->fields;
			$rsResult->MoveNext();
		}
//		$arrData = array('1','2','3');
		return $arrData;
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
		if (is_null($emp_id_)) { return $arrData; }
		if (is_null($dduct_id_) || empty($dduct_id_)) { return $arrData; }
		if (is_null($dduct_type_)) { return $arrData; }
		if ($dduct_type_==5){ $qry[] = "c.tax_ex_id = $tax_ex_id_"; }
        if($dduct_id_ == 1){
            $qry[] = " $totaltgross_ >= b.min_salary";
        }else if($dduct_id_ == 2){
            $qry[] = " $totaltgross_ >= b.min_salary";
        }else{
            $qry[] = "b.min_salary < $totaltgross_";
        }
//		$qry[] = "a.emp_id = $emp_id_";
        $qry[] = "a.sc_id = $dduct_id_";
		$qry[] = "a.dec_id = $dduct_type_";
		$criteria = count($qry)>0 ? " where ".implode(' and ',$qry) : '';
		$orderBy = "order by b.min_salary desc";
		$sql = "select * from statutory_contribution a inner join sc_records b on (a.sc_id = b.sc_id) $criteria $orderBy limit 1";
		$rsResult = $this->conn->Execute($sql);
		if (!$rsResult->EOF) {
			return $rsResult->fields;
		}
	}
	
	
	/**
	 * Get the Total Deduction TAX.
	 *
	 * @param unknown_type $emp_id_
	 * @param unknown_type $payperiod_id_
	 * @param unknown_type $tksudttot_status_
	 * @param unknown_type $tksudttot_type_
	 * @param unknown_type $groupby_option_
	 * @return unknown
	 */
	function getTotalTaxByPayPeriod($emp_id_ = null, $dduct_id_ = null,$dduct_type_ = null, $totaltgross_ = 0, $tax_ex_id_ = null, $tax_table_ = null) {
//		$this->conn->debug=1;
		$arrData = array();
		if (is_null($emp_id_)) {
				return $arrData;
		}
		if (is_null($dduct_id_) || empty($dduct_id_)) {
				return $arrData;
		}
		if (is_null($dduct_type_)) {
			return $arrData;
		}
		$qry = array();
		if($dduct_type_==5){
			$qry[] = "a.tt_exemption = $tax_ex_id_";
		}
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
		//comment ko muna parang mali.
		if ($tax_table_==1) {
			$qry[] = "b.tt_pay_group = '1'";
		}elseif ($tax_table_==2 || $tax_table_==3){
			$qry[] = "b.tt_pay_group = '2'";
		}elseif ($tax_table_==4){
			$qry[] = "b.tt_pay_group = '3'";
		}elseif ($tax_table_==5){
			$qry[] = "b.tt_pay_group = '4'";
		}else{
			$qry[] = "b.tt_pay_group = '5'";
		}
 
        $qry[] = "b.tt_exemption = $tax_ex_id_";
		$criteria = count($qry)>0 ? " where ".implode(' and ',$qry) : '';
		$orderBy = "order by b.tt_minamount desc";
		$sql = "select c.taxep_name , a.*, b.*
						from tax_policy a
						inner join tax_table b on (a.tp_id = b.tp_id)
						inner join tax_excep c on (c.taxep_id=b.tt_exemption)
						$criteria
						$orderBy
						limit 1";
		$rsResult = $this->conn->Execute($sql);
		if (!$rsResult->EOF){
			return $rsResult->fields;
		}
	}
	
	/**
	 * Used to fetch benetfits record.
	 *
	 * @param unknown_type $id_
	 * @return unknown
	 */
	function dbFetchEmployeeBenefits($id_ = "",$transdate,$startdate,$enddate){
		
		$qry = array();
//		echo $startdate;
//		echo "<br>";
//		echo date('F Y',strtotime($startdate));
//		echo $enddate;
		
		$qry[] = "a.emp_id = $id_";
//		$qry[] = "a.psamend_effect_date = '".date('Y-m-d',dDate::parseDateTime($transdate))."'";
//		$qry[] = "(date('F Y',strtotime(a.ben_startdate)) <= '".date('F Y',strtotime($startdate))."')";
//		 and a.psamend_effect_date <= '".date('F Y',strtotime($enddate))."')
//		exit;
		$criteria = count($qry)>0 ? " where ".implode(' and ',$qry) : '';
		
		$sql = "SELECT a.*, b.psa_type, b.psa_name, SUBSTRING_INDEX(SUBSTRING_INDEX(a.ben_startdate,' ',2),' ',-1) as year_,
				CONCAT(SUBSTRING_INDEX(SUBSTRING_INDEX(a.ben_startdate,' ',2),' ',-1),'-',SUBSTRING_INDEX(a.ben_startdate,' ',1),'-01') as startdate,
				SUBSTRING_INDEX(a.ben_startdate,' ',1) as month_
					FROM emp_benefits a
					Inner join payroll_ps_account b on (a.psa_id=b.psa_id)
					$criteria";
		$rsResult = $this->conn->Execute($sql);
		$arrData = array();
		$x = 0;
		while(!$rsResult->EOF){
			 if($rsResult->fields['year_'] <= date('Y',strtotime($startdate))){
				 $monthnum = $this->convert_month_to_num($rsResult->fields['month_']);
				 if($monthnum <= date('n',strtotime($startdate))){
	             	$arrData[$x] = $rsResult->fields;
				 }
			 }
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
//		$this->conn->debug=1;
        unset($_SESSION['payslip']);
        $this->payslips = array();
		for ($i=0;$i<count($arrPayStub);$i++) {
				//printa ($arrPayStub);
				$arrPayStubSer = "";
//				printa($arrPayStub[$i]);
//				exit;
				$arrPayStubSer = serialize($arrPayStub[$i]);
				$sql = "select * from payroll_paystub_report where paystub_id='".$arrPayStub[$i]['paystubdetail']['paystubsched']['paystub_id']."'
						and emp_id = '".$arrPayStub[$i]['empinfo']['emp_id']."'";
				$rsResult = $this->conn->Execute($sql);

				if (!$rsResult->EOF) {

                    $this->payslips[] = array('name' => $arrPayStub[$i]['empinfo']['fullname'],
                        'status' => 1
                    );
					$fldsppr = array();
					$fldsppr[] = "ud_id = '".$arrPayStub[$i]['empinfo']['ud_id']."'";
                    $fldsppr[] = "comp_id = '".$arrPayStub[$i]['empinfo']['comp_id']."'";
					$fldsppr[] = "ppr_paystubdetails = '".$arrPayStubSer."'";
					$fldsppr[] = "ppr_updatewho = '".AppUser::getData('user_name')."'";
					$fldsppr[] = "ppr_updatewhen = '".date("Y-m-d H:i:s")."'";
					$fieldsppr = implode(", ",$fldsppr);
					$sql = "update payroll_paystub_report set $fieldsppr where paystub_id='".$arrPayStub[$i]['paystubdetail']['paystubsched']['paystub_id']."'
						and emp_id = '".$arrPayStub[$i]['empinfo']['emp_id']."'";
					$this->conn->Execute($sql);
				} else {
                    $this->payslips[] = array('name' => $arrPayStub[$i]['empinfo']['fullname'],
                        'status' => 2
                    );
					$fldsppr = array();
					$fldsppr[] = "payperiod_id = '".$arrPayStub[$i]['paystubdetail']['paystubsched']['payperiod_id']."'";
					$fldsppr[] = "emp_id = '".$arrPayStub[$i]['empinfo']['emp_id']."'";
					$fldsppr[] = "paystub_id = '".$arrPayStub[$i]['paystubdetail']['paystubsched']['paystub_id']."'";
                    $fldsppr[] = "ud_id = '".$arrPayStub[$i]['empinfo']['ud_id']."'";
                    $fldsppr[] = "comp_id = '".$arrPayStub[$i]['empinfo']['comp_id']."'";
                    $fldsppr[] = "ppr_paystubdetails = '".addslashes($arrPayStubSer)."'";
					$fldsppr[] = "ppr_addwho = '".AppUser::getData('user_name')."'";
					$fieldsppr = implode(", ",$fldsppr);

					$sql = "insert into payroll_paystub_report set $fieldsppr";
					$this->conn->Execute($sql);
				}
		}

        $_SESSION['payslips'] = $this->payslips;
		//exit;
	}
	
	/**
	 * @note: used to convert month name to num
	 * @param $monthname
	 */
	function convert_month_to_num($monthname=''){
		
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
	 * @note: Get Active Loan of employee
	 * @param unknown_type $emp_id_
	 * @param unknown_type $trans_date
	 * @param unknown_type $paystub_id_
	 */
	function getEmployeeActiveGovRegLoan($emp_id_, $trans_date,$paystub_id_){

//		$sql = "select b.rlt_name,c.rd_amount,c.rd_date,c.rd_id,a.rh_id,a.rh_amortization,a.rh_loan_amount
//				from payroll_db.payroll_regularloan_header a
//				inner join azt_payroll_db.payroll_regular_loan_type b on (a.rlt_id = b.rlt_id)
//                inner join azt_payroll_db.payroll_regularloan_detail c on (a.rh_id = c.rh_id)
//				where a.emp_id =$emp_id_
//				and c.rd_date ='".date('Y-m-d',dDate::parseDateTime($trans_date))."'";
		
		
		$sql = "Select a.*, b.psa_name 
					from loan_info a 
					inner join payroll_ps_account b on (a.psa_id=b.psa_id)
					where a.emp_id = $emp_id_";
		$rsResult = $this->conn->Execute($sql);
		
		
		$arrData = array();
        $ctr = 0;
		while(!$rsResult->EOF){
			$arrData[$ctr] = $rsResult->fields;
            $this->saveGovRegLoanDetails($arrData[$ctr],$paystub_id_);
			$rsResult->MoveNext();
            $ctr++;
		}

		return $arrData;
	}
	
	
	function saveGovRegLoanDetails($arrData = array(),$paystub_id_ = ""){
		
			$flds_[]="paystub_id='".$paystub_id_."'";
			$flds_[]="loan_id='".$arrData['loan_id']."'";
			$flds_[]="loansum_year='".Date('Y')."'";
			$flds_[]="loansum_payment='".$arrData['loan_payperperiod']."'";
			$flds_[]="loansum_addwho='".AppUser::getData('user_name')."'";
			$fields_ = implode(", ",$flds_);
	
			$sql = "insert into loan_detail_sum set $fields_";
			$this->conn->Execute($sql);
			$loansum_id_ = $this->conn->Insert_ID();
			
			$totalpaid = $this->getPaidLoan($arrData['loan_id']);
		
//		$sql ="Insert into ";
		
		
//        $sql = "update azt_payroll_db.payroll_regularloan_detail set paystub_id = '$paystub_id', rd_status = 2 where rd_id = ".$arrData['rd_id']."";
//		$this->conn->Execute($sql);
//
//        $totalpaid = $this->getPaidLoan($arrData['rh_id']);
//        printa($totalpaid);
        if ($totalpaid < $arrData['loan_total']) {
        	
        	$sqlsum = "Select loansum_payment from loan_detail_sum where loansum_id ='".$loansum_id_."'";
        	$rsResultPaid = $this->conn->Execute($sqlsum);
        	
        	$afterbal = $arrData['loan_balance'] - $rsResultPaid->fields['loansum_payment'];
        	$afterytd = $arrData['loan_ytd'] + $rsResultPaid->fields['loansum_payment'];
        	
        	$flds = array();
				$flds[] = "loan_ytd = '".$afterytd."'";
				$flds[] = "loan_balance = '".$afterbal."'";
				$flds[] = "loan_updatewho = '".AppUser::getData('user_name')."'";
				$flds[] = "loan_updatewhen = '".date('Y-m-d')."'";
				$fields = implode(", ",$flds);
				
			$sql3 = "update loan_info set $fields where loan_id = '".$arrData['loan_id']."'";
            $this->conn->Execute($sql3);
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
	
}

?>