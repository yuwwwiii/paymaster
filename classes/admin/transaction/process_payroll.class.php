<?php
/**
 * Initial Declaration
 */
require_once(SYSCONFIG_CLASS_PATH.'admin/transaction/process_payroll.class.php');
require_once(SYSCONFIG_CLASS_PATH.'admin/setup/mnge_pg.class.php');
require_once(SYSCONFIG_CLASS_PATH.'admin/transaction/payroll_details.class.php');

/**
 * Class Module
 *
 * @author  Jason I. Mabignay
 *
 */
class clsProcess_Payroll{

	var $conn;
	var $fieldMap;
	var $Data;
	var $formula = array(
		array(
            "bonus_id" => "1",
            "bonus_code" => "Current Amount"
        ),
        array(
            "bonus_id" => "2",
            "bonus_code" => "YTD Amount"
        ));
	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsProcess_Payroll object
	 */
	function clsProcess_Payroll($dbconn_ = null){
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
	function dbFetch($payperiod_id_ = "", $pps_id_ = ""){
		$sql = "SELECT ppp.*, IFNULL(NULLIF(ppp.payperiod_name,''),psar.pps_name) as pps_name,
				DATE_FORMAT(payperiod_start_date,'%d-%b-%y %h:%i %p') as payperiod_start_date,
				DATE_FORMAT(payperiod_end_date,'%d-%b-%y %h:%i %p') as payperiod_end_date,
				DATE_FORMAT(payperiod_trans_date,'%d-%b-%y %h:%i %p') as payperiod_trans_date,
				IF(pp_stat_id='1','OPEN',IF(pp_stat_id='2','Locked - Pending Approval',IF(pp_stat_id='3','CLOSED','Post Adjustment'))) as pp_stat_id,
				IF(salaryclass_id='1','Daily',IF(salaryclass_id='2','Weekly',IF(salaryclass_id='3','Bi-Weekly',IF(salaryclass_id='4','Semi-monthly',IF(salaryclass_id='5','Monthly','Annual'))))) as salaryclass_id,
				IF(ppp.payperiod_type='2','YTD',IF(ppp.payperiod_type='3','Bonus',IF(ppp.payperiod_type='4','Others','Normal'))) as classification	
					FROM payroll_pay_period ppp
					JOIN payroll_pay_period_sched psar on (psar.pps_id=ppp.pps_id)
					WHERE ppp.payperiod_id = '".$payperiod_id_."'";
		$rsResult = $this->conn->Execute($sql);
		if(!$rsResult->EOF){
			return $rsResult->fields;
		}
	}
	
	/**
	 * Get the records from the database
	 *
	 * @param string $id_
	 * @return array
	 */
	function dbFetch_OT($payperiod_id_ = "", $pps_id_ = "", $puser_ = "", $process_ = false){
		if($process_ == true){
			$qry[] = "ppp.payperiod_id = '".$payperiod_id_."'";
			$qry[] = "puser.emp_id = '".$puser_."'";
			// put all query array into one criteria string
			$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";
		}else{
			$qry[] = "ppp.payperiod_id = '".$payperiod_id_."'";
			$qry[] = "puser.emp_id = '".$puser_."'";
			// put all query array into one criteria string
			$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";
		}
		$sql = "SELECT ppp.*, psar.pps_name, CONCAT(empinfo.pi_fname,' ',empinfo.pi_mname,' ',empinfo.pi_lname) as empname,
				CONCAT(DATE_FORMAT(payperiod_start_date,'%b. %d, %Y'),' - ',DATE_FORMAT(payperiod_end_date,'%b. %d, %Y')) as pdate,
				DATE_FORMAT(payperiod_start_date,'%d-%b-%y') as payperiod_start_date,
				DATE_FORMAT(payperiod_end_date,'%d-%b-%y') as payperiod_end_date,
				DATE_FORMAT(payperiod_trans_date,'%b. %d, %Y') as payperiod_trans_date,
				IF(pp_stat_id='1','OPEN',IF(pp_stat_id='2','Locked - Pending Approval',IF(pp_stat_id='3','CLOSED','Post Adjustment'))) as pp_stat_id,
				if(salaryclass_id='1','Daily',IF(salaryclass_id='2','Weekly',IF(salaryclass_id='3','Bi-Weekly',IF(salaryclass_id='4','Semi-monthly',IF(salaryclass_id='5','Monthly','Annual'))))) as salaryclass_id
					FROM payroll_pay_period ppp
					JOIN payroll_pay_period_sched psar on (psar.pps_id=ppp.pps_id)
					JOIN payroll_pps_user puser on (puser.pps_id=ppp.pps_id)
					JOIN emp_masterfile emp on (emp.emp_id=puser.emp_id)
					JOIN emp_personal_info empinfo on (empinfo.pi_id=emp.pi_id)
					$criteria";
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
			$valData = trim(addslashes($valData));
			$flds[] = "$keyData='$valData'";
		}
		$fields = implode(", ",$flds);

		$sql = "insert into /*app_modules*/ set $fields";
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
			$valData = trim(addslashes($valData));
			$flds[] = "$keyData='$valData'";
		}
		$fields = implode(", ",$flds);

		$sql = "update /*app_modules*/ set $fields where mnu_id=$id";
		$this->conn->Execute($sql);
		$_SESSION['eMsg']="Successfully Updated.";
	}

	/**
	 * Save OT
	 *
	 */
	function doSaveOT($emp_id_ = null, $payperiod_id_ = null, $pData = ""){
		$flds = array();
		$ctr=0;
		do{
//			@note: to compute the sub total of OT.
//			@formula: OT = ((OT_Factor * basic_per_hrs) * Total_OT_hrs)
			$comSub = (($pData['ot'][$ctr]['hrsrate'] * $pData['ot'][$ctr]['otr_factor'])* $pData['ot'][$ctr]['numhrs']);

			$flds[] = "emp_id='".$emp_id_."'";
			$flds[] = "payperiod_id='".$payperiod_id_."'";
			$flds[] = "otr_id='".$pData['ot'][$ctr]['otr_id']."'";
			$flds[] = "otrec_totalhrs='".$pData['ot'][$ctr]['numhrs']."'";
			$flds[] = "otrec_subtotal='".$comSub."'";
			
			if($pData['ot'][$ctr]['otrec_id']!=""){
				$flds[] = "otrec_updatewho='".AppUser::getData('user_name')."'";
				$flds[] = "otrec_updatewhen='".date('Y-m-d H:i:s')."'";
				$fields = implode(", ",$flds);
				if($pData['ot'][$ctr]['numhrs']=="" or $pData['ot'][$ctr]['numhrs']=="0" or $pData['ot'][$ctr]['numhrs']=="0.00"){
					//delete TA
					$sql = "DELETE FROM ot_record WHERE otrec_id = '".$pData['ot'][$ctr]['otrec_id']."'";
					$this->conn->Execute($sql);
				}else{
					//update OT
					$sql = "UPDATE ot_record SET $fields WHERE otrec_id = '".$pData['ot'][$ctr]['otrec_id']."'";
					$this->conn->Execute($sql);
				}
			}else{
				$flds[] = "otrec_addwho='".AppUser::getData('user_name')."'";
				$fields = implode(", ",$flds);
				if($pData['ot'][$ctr]['numhrs']!="" AND $pData['ot'][$ctr]['numhrs']!="0.00" AND $pData['ot'][$ctr]['numhrs']!="0"){
					$sql = "insert into ot_record set $fields";
					$this->conn->Execute($sql);
				}
			}
			$flds = "";
			$fields = "";
			$comSub = "";
			$ctr++;
		} while($ctr < sizeof($pData['ot']));

		$_SESSION['eMsg']="Successfully Save OT.";
	}
	
	/**
	 * Save TA
	 *
	 */
	function doSaveTA($emp_id_ = null, $payperiod_id_ = null, $pData = ""){
		$flds = array();
		$ctr=0;
		do{
//			@note: to compute the sub total of TA.
//			@formula: TA = basic_per_hrs * Total_TA_hrs)
			$comSub = ($pData['TA'][$ctr]['rateAmount'] * $pData['TA'][$ctr]['emp_tarec_nohrday']);

			$flds[] = "emp_id='".$emp_id_."'";
			$flds[] = "payperiod_id='".$payperiod_id_."'";
			$flds[] = "tatbl_id='".$pData['TA'][$ctr]['tatbl_id']."'";
			$flds[] = "emp_tarec_nohrday='".$pData['TA'][$ctr]['emp_tarec_nohrday']."'";
			$flds[] = "emp_tarec_amtperrate='".$comSub."'";

			if($pData['TA'][$ctr]['emp_tarec_id']!=""){
				$flds[] = "emp_tarec_updatewho='".AppUser::getData('user_name')."'";
				$flds[] = "emp_tarec_updatewhen='".date('Y-m-d H:i:s')."'";
				$fields = implode(", ",$flds);
				if($pData['TA'][$ctr]['emp_tarec_nohrday']=="" or $pData['TA'][$ctr]['emp_tarec_nohrday']=="0" or $pData['TA'][$ctr]['emp_tarec_nohrday']=="0.00"){
					//delete TA
					$sql = "delete from ta_emp_rec where emp_tarec_id = '".$pData['TA'][$ctr]['emp_tarec_id']."'";
					$this->conn->Execute($sql);
				}else{
					//update TA
					$sql = "update ta_emp_rec set $fields where emp_tarec_id = '".$pData['TA'][$ctr]['emp_tarec_id']."'";
					$this->conn->Execute($sql);
				}
			}else{
				$flds[] = "emp_tarec_addwho='".AppUser::getData('user_name')."'";
				$fields = implode(", ",$flds);
				if($pData['TA'][$ctr]['emp_tarec_nohrday']!="" and $pData['TA'][$ctr]['emp_tarec_nohrday']!="0" and $pData['TA'][$ctr]['emp_tarec_nohrday']!="0.00"){
					//save TA
					$sql = "insert into ta_emp_rec set $fields";
					$this->conn->Execute($sql);
				}
			}
			$flds = "";
			$fields = "";
			$comSub = "";
			$ctr++;
		} while($ctr < sizeof($pData['TA']));
		$_SESSION['eMsg']="Successfully Save TA.";
	}
	
	/**
	 * Save Leave
	 *
	 */
	function doSaveLeave($emp_id_ = null, $payperiod_id_ = null, $pData = ""){
		$flds = array();
		$ctr=0;
		do{
//			@note: to compute the Balance Leave.
//			@formula: Balance = empleave_available_day - empleave_used_day
			$comSub = ($pData['leave'][$ctr]['empleave_available_day'] - $pData['leave'][$ctr]['empleave_used_day']);

			$flds[] = "empleave_used_day='".$pData['leave'][$ctr]['empleave_used_day']."'";
			$flds[] = "empleave_available_day='".$comSub."'";
			$flds[] = "empleave_updatewho='".AppUser::getData('user_name')."'";
			$flds[] = "empleave_updatewhen='".date('Y-m-d H:i:s')."'";
			
			$fields = implode(", ",$flds);
			$sql = "update emp_leave set $fields where empleave_id='".$pData['leave'][$ctr]['empleave_id']."'";
			$this->conn->Execute($sql);
			$flds = "";
			$fields = "";
			$comSub = "";
			$ctr++;
		} while($ctr < sizeof($pData['leave']));

		$_SESSION['eMsg']="Successfully Save Leaves.";
	}
	
	/**
	 *  Update Custom Fields
	 *  @update by: IR Salvador
	 */
	function doSaveCF($emp_id_ = null, $payperiod_id_ = null, $pData = ""){
		$flds = array();
		$ctr=0;
		do{
			$flds[] = "cfdetail_rec='".$pData['cf'][$ctr]['cfdetail_rec']."'";
			$flds[] = "cfdetail_updatewho='".AppUser::getData('user_name')."'";
			$flds[] = "cfdetail_updatewhen='".date('Y-m-d H:i:s')."'";
			
			if(!empty($pData['cf'][$ctr]['cfdetail_id'])){
				$fields = implode(", ",$flds);
				$sql = "update cf_detail set $fields where cfdetail_id='".$pData['cf'][$ctr]['cfdetail_id']."'";
			} else {
				$flds[] = "payperiod_id='".$payperiod_id_."'";
				$flds[] = "emp_id='".$emp_id_."'";
				$flds[] = "cfhead_id='".$pData['cf'][$ctr]['cfhead_id']."'";
				$flds[] = "uptadet_id='".$_GET['viewuptahead']."'";
				$fields = implode(", ",$flds);
				$sql = "insert into cf_detail set $fields";
			}
			$this->conn->Execute($sql);
			$flds = "";
			$fields = "";
			$ctr++;
		} while($ctr < sizeof($pData['cf']));
		
		$_SESSION['eMsg']="Successfully Save Data.";
	}
	
	/**
	 * Delete Record
	 *
	 * @param string $id_
	 */
	function doDelete($id_ = ""){
		$sql = "delete from /*app_modules*/ where mnu_id=?";
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
        $time = date('Y-m-d h:i:s',dDate::parseDateTime(dDate::getTime()));
//		$qry[] = "ppp.payperiod_trans_date > '".$time."'";
		$qry[] = "ppp.pp_stat_id in (1,2)";
		$qry[] = "ppp.payperiod_type not in (2)";
		$listpgroup = $_SESSION[admin_session_obj][user_paygroup_list2];
		IF(count($listpgroup)>0){
			$qry[] = "psar.pps_id in (".$listpgroup.")";//pay group that can access
		}
		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" WHERE ".implode(" AND ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		 "pps_name" => "pps_name"
		,"paysched"=>"paysched"
		,"payperiod_trans_date" => "payperiod_trans_date"
		,"pperiod" => "pperiod"
		,"salaryclass_id" => "salaryclass_id"
		,"classification" => "classification"
		,"pp_stat_id" => "pp_stat_id"
		);

		if(isset($_GET['sortby'])){
			$strOrderBy = " ORDER BY ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}else{
			$strOrderBy = " ORDER BY ppp.payperiod_id DESC";
		}
		
		//@note: this is used to count and check all the checkbox.
		//@note set t1 = 0
		$sql = "set @t1:=0";
		$this->conn->Execute($sql);	
		
		//get total number of records and pass it to the javascript function CheckAll
			$sql_ = "SELECT count(*) as mycount_
						FROM payroll_pay_period ppp
						JOIN payroll_pay_period_sched psar on (psar.pps_id=ppp.pps_id)
					$criteria
					$strOrderBy";
			$rsResult = $this->conn->Execute($sql_);
			if(!$rsResult->EOF){
				$mycount = $rsResult->fields['mycount_'];
			}

		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$viewLink = "<a href=\"?statpos=process_payroll&ppsched=',psar.pps_id,'&ppsched_view=',ppp.payperiod_id,'\">',psar.pps_name,'</a>";
		$empLink = "<a href=\"?statpos=payperiodsched&empinput=',psar.pps_id,'\"><img src=\"".SYSCONFIG_THEME_URLPATH.SYSCONFIG_THEME."/images/admin/report_user.gif\" title=\"Select Employee\" hspace=\"2px\" border=0></a>";
		$ctr=0;		
		$chkAttend = "<input type=\"checkbox\" name=\"chkAttend[]\" id=\"chkAttend[',@t1:=@t1+1,']\" value=\"',ppp.payperiod_id,'\" onclick=\"javascript:UncheckAll(".$mycount.");\">";
//		$editLink = "<a href=\"?statpos=endpayperiod&ppsched=',psar.pps_id,'&ppsched_edit=',ppp.payperiod_id,'\"><img src=\"".SYSCONFIG_THEME_URLPATH.SYSCONFIG_THEME."/images/admin/edit.gif\" title=\"Edit\" hspace=\"2px\" border=0></a>";
//		$delLink = "<a href=\"?statpos=endpayperiod&ppsched=',psar.pps_id,'&ppsched_del=',ppp.payperiod_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_THEME_URLPATH.SYSCONFIG_THEME."/images/admin/trash.gif\" title=\"Delete\" hspace=\"2px\"  border=0></a>";

		// SqlAll Query
		$sql = "SELECT ppp.*, CONCAT('$viewLink') as viewdata, 
				CONCAT(UPPER(date_format(payperiod_start_date,'%b %d')),' to ',UPPER(date_format(payperiod_end_date,'%b %d, %Y'))) as paysched,
				DATE_FORMAT(payperiod_start_date,'%d %b %Y %h:%i %p') as payperiod_start_date,
				DATE_FORMAT(payperiod_end_date,'%d %b %Y %h:%i %p') as payperiod_end_date,
				UPPER(DATE_FORMAT(payperiod_trans_date,'%M %d, %Y')) as payperiod_trans_date,
				psar.pps_name, CONCAT('$chkAttend') as chkbox,
				if(salaryclass_id='1','Daily',IF(salaryclass_id='2','Weekly',IF(salaryclass_id='3','Bi-Weekly',IF(salaryclass_id='4','Semi-monthly',IF(salaryclass_id='5','Monthly','Annual'))))) as salaryclass_id,
				IF(pp_stat_id='1','OPEN',IF(pp_stat_id='2','Locked - Pending Approval',IF(pp_stat_id='3','CLOSED','Post Adjustment'))) as pp_stat_id,
				IF(ppp.payperiod_type='2','YTD',IF(ppp.payperiod_type='3','Bonus',IF(ppp.payperiod_type='4','Others',IF(ppp.payperiod_type='5','Last Pay','Normal')))) as classification,
				IF(ppp.payperiod_freq='1','1st',IF(ppp.payperiod_freq='2','2nd',IF(ppp.payperiod_freq='3','3rd',IF(ppp.payperiod_freq='4','4th',IF(ppp.payperiod_freq='5','5th','All'))))) as pperiod
					FROM payroll_pay_period ppp
					JOIN payroll_pay_period_sched psar on (psar.pps_id=ppp.pps_id)
					$criteria
					$strOrderBy";

		// Field and Table Header Mapping
		$arrFields = array(
		 "chkbox"=>"<input type=\"checkbox\" name=\"chkAttendAll\" id=\"chkAttendAll\" onclick=\"javascript:CheckAll(".$mycount.");\">"
		,"viewdata" => "Name"
		,"paysched"=>"Cut-offs"
		,"payperiod_trans_date" => "Pay Date"
		,"pperiod" => "Period"
		,"salaryclass_id" => "Type"
		,"classification" => "Payroll Type"
		,"pp_stat_id" => "Status"
		);

		// Column (table data) User Defined Attributes
		$arrAttribs = array(
		 "mnu_ord"=>"align='right'"
		,"pps_name"=>"width='150'"
		,"salaryclass_name"=>"width='120'"
		,"chkbox"=>"width='10' align='center'"
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
		$tblDisplayList->tblBlock->assign("noPaginatorStart","<!--");
		$tblDisplayList->tblBlock->assign("noPaginatorEnd","-->");
		return $tblDisplayList->getTableList($arrAttribs);
	}

	/**
	 * 
	 * USED to get OT computation.
	 * 
	 * @param unknown_type $emp_id_
	 */
	function Leaverate($emp_id_=""){

		$qry[]="lemp.emp_id='".$emp_id_."'";
		
		// put all query array into one string criteria
		$criteria = " where ".implode(" or ",$qry);
		
		$sql = "select lemp.*, ltype.leave_name
					from emp_leave lemp 
					inner join leave_type ltype on (ltype.leave_id=lemp.leave_id)
					$criteria";
		$objData = $this->conn->Execute($sql);
		$cResult = array();
		$cnt = 0;
		while ( !$objData->EOF ) { 
			$cResult[$cnt] = $objData->fields;    	
        	$objData->MoveNext();
        	$cnt ++;
        }
//        printa($cResult);
//        exit;
        return $cResult;
	}
	
	/**
	 * 
	 * USED to get TA computation.
	 * 
	 * @param unknown_type $emp_id_
	 */
	function TArate($emp_id_= null,$payperiod_id_= null){
		$objClsMnge_PG = new clsMnge_PG($this->conn);
		
		$sql = "SELECT ta.tatbl_id,ta.tatbl_name,ta.tatbl_rate, IF(ta.tatbl_rate='1','H','D') as tatbl_rate FROM ta_tbl ta";
		$objData = $this->conn->Execute($sql);
		$cResult = array();
		$cnt = 0;
		while ( !$objData->EOF ) { 
			$cResult[$cnt] = $objData->fields; 	
			if($objData->fields['tatbl_id'] != '6'){
				$cResult[$cnt]['rateAmount'] = $this->getComputerate($emp_id_,$objData->fields['tatbl_rate']);
			} else {
				$cResult[$cnt]['rateAmount'] = 0;
			}
			$varTArec = $this->getNumTAhrs($emp_id_,$payperiod_id_,$objData->fields['tatbl_id']); 
        	$cResult[$cnt]['emp_tarec_nohrday'] = $varTArec['othrs'];
        	$cResult[$cnt]['emp_tarec_id'] = $varTArec['emp_tarec_id'];
        	if($objData->fields['tatbl_id'] == '5') {
        		$cResult[$cnt]['subtotal'] = 0;
        	} else {
        		$cResult[$cnt]['subtotal'] =  Number_Format($varTArec['othrs'] * $cResult[$cnt]['rateAmount'],5,'.','');
        	}
        	$objData->MoveNext();
        	$cnt ++;
        }
//        printa($cResult);
//        exit;
        return $cResult;
	}
	
	/**
	 * USED to get OT computation.
	 * @param string $emp_id_
	 * @param string $payperiod_id_
	 * @return multitype:
	 */
	function getpopup_OTrate($emp_id_= null,$payperiod_id_= null){
		$objClsMnge_PG = new clsMnge_PG($this->conn);
		$objClsMngeDecimal = new Application();
		$pperiod_ = $_GET['edit'];
		$qry[]="comp.emp_id='".$emp_id_."'";
		$qry[]="b.salaryinfo_isactive = '1'";
		// put all query array into one string criteria
		$criteria = " WHERE ".implode(" AND ",$qry);
		$strOrderBy = " ORDER BY rates.otr_id ASC";
		$sql = "SELECT rates.*, b.salarytype_id, b.salaryinfo_basicrate, b.salaryinfo_ecola, ottbl.ot_istax
					FROM payroll_comp comp 
					JOIN ot_tbl ottbl on (ottbl.ot_id=comp.ot_id)
					JOIN ot_tr ottr on (ottbl.ot_id = ottr.ot_id) 
					JOIN ot_rates rates on (rates.otr_id = ottr.otr_id)
					JOIN emp_masterfile a on (a.emp_id=comp.emp_id)
					JOIN salary_info b on (b.emp_id=a.emp_id)
					$criteria
					$strOrderBy";
		$objData = $this->conn->Execute($sql);
		$cResult = array();
		$cnt = 0;
		//printa($objData->fields); exit;
		while ( !$objData->EOF ) { 
			$cResult[$cnt] = $objData->fields;  
//			$cResult[$cnt]['rateAmount'] = $this->getComputerate($emp_id_); 
			$BPDayRate = $objClsMnge_PG->getBPDayRate($emp_id_,$objData->fields['salarytype_id'],$objData->fields['salaryinfo_basicrate'],null,$objData->fields['salaryinfo_ecola']);
			$otSettings = clsPayroll_Details::getGeneralSetup("Overtime Computation");
			if($otSettings['set_stat_type']==0){
					$cResult[$cnt]['rateAmount'] = Number_Format(($BPDayRate['rateperhour']),$objClsMngeDecimal->getFinalDecimalSettings(),'.','');
				} else {
					$cResult[$cnt]['rateAmount'] = Number_Format(($BPDayRate['rateperhour']+$BPDayRate['colaperhour']),$objClsMngeDecimal->getFinalDecimalSettings(),'.','');
				}
			
			$varOTrec = $this->getNumOThrs($emp_id_,$_GET['otcomp'],$payperiod_id_,$objData->fields['otr_id']); 
			$cResult[$cnt]['numhrs'] = $varOTrec['othrs']; 
			$cResult[$cnt]['otrec_id'] = $varOTrec['otrec_id']; 
			$cResult[$cnt]['subtotal'] = Number_Format(($objData->fields['otr_factor'] * $varOTrec['othrs']) * ($BPDayRate['rateperhour']+$BPDayRate['colaperhour']),$objClsMngeDecimal->getFinalDecimalSettings(),'.','');
        	$objData->MoveNext();
        	$cnt ++;
        }
       //printa($objData->fields);
       //exit;
        return $cResult;
	}
	
	/**
	 * @note get Numbers of Hrs
	 * 
	 * @param $emp_id_ get employee id
	 * @param $pps_id_ get pps_id
	 * @param $payperiod_id_ get pay period id
	 * @param $otr_id_ get ot rates.
	 */
	function getNumOThrs($emp_id_ = null, $pps_id_ = null, $payperiod_id_ = null, $otr_id_ = null){
		
		$qry[]="otrec.emp_id='".$emp_id_."'";
		$qry[]="otrec.payperiod_id='".$payperiod_id_."'";
		$qry[]="otrec.otr_id='".$otr_id_."'";
		
		// put all query array into one string criteria
		$criteria = " WHERE ".implode(" AND ",$qry);
		$sql ="SELECT otrec.otrec_totalhrs as othrs, otrec.otrec_id
			   FROM ot_record otrec
			   $criteria";
		$objData = $this->conn->Execute($sql);
		if(!$objData->EOF){
			return $objData->fields;
		}else{
			return '';
		}
	}
	
	/**
	 * @note get Numbers of Hrs for TA
	 * 
	 */
	function getNumTAhrs($emp_id_ = null, $payperiod_id_ = null, $tatbl_id_ = null){
		
		$qry[]="lrec.emp_id='".$emp_id_."'";
		$qry[]="lrec.payperiod_id='".$payperiod_id_."'";
		$qry[]="lrec.tatbl_id='".$tatbl_id_."'";
		
		// put all query array into one string criteria
		$criteria = " where ".implode(" and ",$qry);
		$sql ="SELECT lrec.emp_tarec_nohrday as othrs, lrec.emp_tarec_id
			   FROM ta_emp_rec lrec
			   $criteria";
		$objData = $this->conn->Execute($sql);
		if(!$objData->EOF){
			return $objData->fields;
		}else{
			return '';
		}
	}
	
	/**
	 * @note Compute hour rate.
	 * @param unknown_type $emp_id_
	 */
	function getComputerate($emp_id_ = null, $ta = null){
		$qry[]="emp.emp_id='".$emp_id_."'";
		$qry[]="sal_info.salaryinfo_isactive='1'";
		
		// put all query array into one string criteria
		$criteria = " where ".implode(" and ",$qry);
		$strOrderBy = " order by sal_info.salaryinfo_effectdate ASC";
		$sql ="SELECT sal_info.* 
			   FROM emp_masterfile emp
			   JOIN salary_info sal_info on (sal_info.emp_id=emp.emp_id)
			   $criteria
			   $strOrderBy
			   limit 1 ";
		$objData = $this->conn->Execute($sql);
		$cResult = array();
		if(!$objData->EOF){
			$hrate = $this->getTheFactoRate($emp_id_);
			if(count($hrate)> 0) {
				$taSettings = clsPayroll_Details::getGeneralSetup("Leave Deduction");
				if($taSettings['set_stat_type']==0){
					$brate = $objData->fields['salaryinfo_basicrate'];
				} else {
					$brate = $objData->fields['salaryinfo_basicrate']+$objData->fields['salaryinfo_ecola'];
				}
				if($objData->fields['salarytype_id']=='2'){
					if($ta == 'D'){
						$hrsrate = $brate;
					}else{
						$hrsrate = ($brate/$hrate['fr_hrperday']);
					}
				}else{
					if($ta == 'D'){
						$hrsrate = ($brate*12)/$hrate['fr_dayperyear'];
					}else{
						$hrsrate = ((($brate*12)/$hrate['fr_dayperyear'])/$hrate['fr_hrperday']);
					}
				}
			}
			return $hrsrate;
		}	   
	}
	
	function getTheFactoRate($emp_id_ = null){
		$qry[]="pcal.emp_id='".$emp_id_."'";
		// put all query array into one string criteria
		$criteria = " where ".implode(" and ",$qry);
//		$strOrderBy = " order by sal_info.salaryinfo_effectdate ASC";
		$sql ="SELECT fac.* 
			   FROM payroll_comp pcal
			   JOIN factor_rate fac on (fac.fr_id=pcal.fr_id)
			   $criteria
			   $strOrderBy";
		$objData = $this->conn->Execute($sql);
		if(!$objData->EOF){
			return $objData->fields;
		}
	}

	/**
	 * Used to generate Bonus computation
	 * @author: jim
	 */
	function doGenerateBonus($payperiod_id_ = null, $pData){ 
//		printa($payperiod_id_); printa($pData); exit;
		$objClsMngeDecimal = new Application();
		$retval = "";
		if (is_null($payperiod_id_ )){ return $retval; }
		if (is_null($pData)){ return $retval; }
		
		$ctr = 0;
		do {$payStubSerialize = array();
			$paystub_id = clsMnge_PG::doSavePayStub($pData['chkAttend'][$ctr],$payperiod_id_); // Save Paystub
			$empinfo = "SELECT *,a.emp_id,a.comp_id, txcep.taxep_name,
							CONCAT(g.pi_fname,' ',UPPER(SUBSTRING(g.pi_mname,1,1)),'. ',g.pi_lname) as fullname, 
							bank.bankiemp_acct_no, blist.banklist_name, 
							DATE_FORMAT(f.payperiod_trans_date,'%d') as ppdTransDate, 
							DATE_FORMAT(f.payperiod_start_date,'%d') as ppdStartDate
							FROM emp_masterfile a
							JOIN emp_personal_info g on (g.pi_id=a.pi_id)
							JOIN company_info i on (i.comp_id=a.comp_id)
							LEFT JOIN emp_position h on (h.post_id=a.post_id) 
							LEFT JOIN app_userdept j on (j.ud_id=a.ud_id) 
							LEFT JOIN emp_type z on (z.emptype_id=a.emptype_id) 
							LEFT JOIN bank_infoemp bank on (bank.emp_id=a.emp_id) 
							LEFT JOIN bank_list blist on blist.banklist_id=bank.banklist_id 
							LEFT JOIN tax_excep txcep on (txcep.taxep_id=a.taxep_id)
							JOIN payroll_pps_user c on (c.emp_id = a.emp_id) 
							JOIN payroll_pay_period_sched d on (d.pps_id=c.pps_id) 
							JOIN payroll_pay_period f on (f.pps_id=d.pps_id) 
							WHERE a.emp_id='".$pData['chkAttend'][$ctr]."' and c.pps_id='".$_GET['ppsched']."' and f.payperiod_id='".$payperiod_id_."'";
			$rsResult = $this->conn->Execute($empinfo);
			//printa($rsResult->fields);
			
			// get the Bonus Pay.
			//-------------------------------------------------------->>
			$BRecord = 0;
			IF($pData['wbonus']==0){//if with bonus computation
				IF($pData['bonus_id']=='1'){
					$BRecord = $this->getBonusFormula_1($rsResult->fields['emp_id'],$payperiod_id_,$pData);
				}ELSE{
					$BRecord = $this->getBonusFormula_2($rsResult->fields['emp_id'],$payperiod_id_,$pData);
				}
			}
			$BRecord = $BRecord*$pData['factor'];
			//--------------------------------------------------------<<
			
			// Call the getAmendments function
			//-------------------------------------------------------->>
			$amendments = clsMnge_PG::getAmendments($rsResult->fields['emp_id'],$rsResult->fields['payperiod_trans_date'],$rsResult->fields['payperiod_start_date'],$rsResult->fields['payperiod_end_date'],$paystub_id);
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
//			echo "==================Amendments===================<br>";
//			echo $totalTSEarningAmendments[$ctr]." E Tax & Stat<br>";
//			echo $totalTEarningAmendments[$ctr]." E Tax<br>";
//			echo $totalSEarningAmendments[$ctr]." E Stat<br>";
//			echo $totalNTSEarningAmendments[$ctr]." E Non Tax & Stat<br>";
//			echo $totalTSDeductionAmendments[$ctr]." D Tax & Stat<br>";
//			echo $totalTDeductionAmendments[$ctr]." D Tax<br>";
//			echo $totalSDeductionAmendments[$ctr]." D Stat<br>";
//			echo $totalNTSDeductionAmendments[$ctr]." D Non Tax & Stat<br>";
			//----------------------------END-------------------------<<
			
			//Sum all Payelements
			//-------------------------------------------------------->>
			$SumTSABEarning = $totalTSEarningAmendments[$ctr];		//TS Earning
			$SumTABEarning = $totalTEarningAmendments[$ctr];		//T Earning
			$SumSABEarning = $totalSEarningAmendments[$ctr];		//S Earning
			$SumNonABEarning = $totalNTSEarningAmendments[$ctr];	//Non TS Earning
			$SumALLABEarning = $SumTSABEarning + $SumTABEarning;	//SUM all AB Earning
			
			$SumTSABDeduction = $totalTSDeductionAmendments[$ctr];	//TS Deduction
			$SumTABDeduction = $totalTDeductionAmendments[$ctr];	//T Deduction
			$SumSABDeduction = $totalSDeductionAmendments[$ctr];	//S Deduction
			$SumNonABDeduction = $totalNTSDeductionAmendments[$ctr];//Non TS Deduction
			$SumALLABDeduction = $SumTSABDeduction + $SumTABDeduction + $SumSABDeduction + $SumNonABDeduction;//SUM all AB Deduction
			//----------------------------END-------------------------<<
			
			// Amount Computed base in Basic Pay.
			//-------------------------------------------------------->>
			$basicgrosspg = $BRecord;																				   //(Basic Pay + OT + COLA)- TA
			$basicPG_ABS = ($basicgrosspg + $SumSABEarning + $SumTSABEarning) - ($SumTSABDeduction + $SumSABDeduction);//Add AB Earning and Less AB Deduction subject to Stat 
			$basicPG_nostat = ($SumTSABEarning + $SumTABEarning) - ($SumTABDeduction + $SumTSABDeduction);			   //Sum all Earning and Deduction that subject to tax.
			$nontaxableGross =  $SumNonABEarning;																	   //Sum all non AB Earning
			$other_nontaxdeduction = $SumNonABDeduction;															   //Sum all non AB Deduction
			$SumAllEarning = $basicgrosspg + $SumALLABEarning;
			
			//TAX Computation
			$sql = "SELECT  * FROM tax_policy";
			$getTAXLimit = $this->conn->Execute($sql);
			$taxableBonusPay = $BRecord - $getTAXLimit->fields['tp_other_benefits'];//computation for taxable gross less bonus exeption
			
			$taxableBonus_ = $taxableBonusPay + $basicPG_nostat;//compute taxable gross
			IF($taxableBonus_ > 0){
				// Compute W/H Tax
				//-------------------------------------------------------->>
				$varDec = clsMnge_PG::getTotalTaxByPayPeriod($rsResult->fields['emp_id'],$getTAXLimit->fields['tp_id'],2,$taxableBonus_,$rsResult->fields['taxep_id'],$rsResult->fields['tt_pay_group']);
				$varStax = $taxableBonus_ - $varDec['tt_minamount'];
				$conVertTaxper = $varDec['tt_over_pct'] / 100 ;
				$varStax_p =  $varStax * $conVertTaxper;
				$totalTax = $varDec['tt_taxamount'] + $varStax_p;
				$bonustax = $totalTax;
				$taxableBonus = $taxableBonus_;
				$psaTax = "Tax";
	//			echo "<br>================= Tax Summary ================<br>";
	//			echo $taxableGross." Taxable Gross<br>";
	//			echo $totalTax." W/H Tax<br>";
			}ELSE{
				$bonustax = Number_Format("0",$objClsMngeDecimal->getFinalDecimalSettings(),'.','');
				$taxableBonus = Number_Format("0",$objClsMngeDecimal->getFinalDecimalSettings(),'.','');
			}
			$afterTaxGross = Number_Format($taxableBonus,$objClsMngeDecimal->getFinalDecimalSettings(),'.','') - Number_Format($bonustax,$objClsMngeDecimal->getFinalDecimalSettings(),'.','');//deduct the tax to the taxable gross
			$SumAllEarning = Number_Format($SumAllEarning,$objClsMngeDecimal->getFinalDecimalSettings(),'.','');//SUM all Earnings
			$SumAllDeduction = Number_Format($SumALLABDeduction,$objClsMngeDecimal->getFinalDecimalSettings(),'.','') + Number_Format($bonustax,$objClsMngeDecimal->getFinalDecimalSettings(),'.','');//Sum all Deduction
			$grossandnontaxable = Number_Format($nontaxableGross,$objClsMngeDecimal->getFinalDecimalSettings(),'.','');//sum all non-taxable income
			$otherdeducNtax = Number_Format($other_nontaxdeduction,$objClsMngeDecimal->getFinalDecimalSettings(),'.','');//sum all non-taxble deduction
			$BonusPay = Number_Format($BRecord,$objClsMngeDecimal->getFinalDecimalSettings(),'.','');//BONUS PAY
			$varNetpay = $SumAllEarning - $SumAllDeduction;//NETPAY
			
			//TESTING
//			echo "<br>".$BRecord." Bonus Record";
//			echo "<br>".$afterTaxGross." AFTER TAX GROSS";
//			echo "<br>".$SumAllEarning." SUM all Earnings";
//			echo "<br>".$SumAllDeduction." Sum all Deductions";
//			echo "<br>".$BonusPay." BONUS PAY";
//			echo "<br>".$varNetpay." NET PAY";
			
			//Save Entry Record in payroll_paystub_entry table.
			clsMnge_PG::doSavePayStubEntry($paystub_id,$pData['psaid'],$BonusPay);    			  //save Bonus to payroll_paystub_entry table
			clsMnge_PG::doSavePayStubEntry($paystub_id,30,$taxableBonus);	  					  //save Taxablegross
			clsMnge_PG::doSavePayStubEntry($paystub_id,8,$bonustax,$taxableBonus,$conVertTaxper); //save W/H Tax
			clsMnge_PG::doSavePayStubEntry($paystub_id,5,$varNetpay);    						  //save NETPAY to payroll_paystub_entry table
			clsMnge_PG::doSavePayStubEntry($paystub_id,2,$SumAllDeduction);    					  //save Total Deduction to payroll_paystub_entry table
			clsMnge_PG::doSavePayStubEntry($paystub_id,25, $SumSABEarning);		//save Total S Earning to payroll_paystub_entry table
			clsMnge_PG::doSavePayStubEntry($paystub_id,26, $SumTSABEarning);	//save Total TS Earning to payroll_paystub_entry table
			clsMnge_PG::doSavePayStubEntry($paystub_id,28, $SumTABEarning);		//save Total T Earning to payroll_paystub_entry table
			clsMnge_PG::doSavePayStubEntry($paystub_id,29, $SumTABDeduction);	//save Total T Deduction to payroll_paystub_entry table
			clsMnge_PG::doSavePayStubEntry($paystub_id,33, $SumSABDeduction);	//save Total S Deduction to payroll_paystub_entry table
			clsMnge_PG::doSavePayStubEntry($paystub_id,34, $SumTSABDeduction);	//save Total ST Deduction to payroll_paystub_entry table
			clsMnge_PG::doSavePayStubEntry($paystub_id,31, $grossandnontaxable);//save Total Non TS Earning to payroll_paystub_entry table
			clsMnge_PG::doSavePayStubEntry($paystub_id,32,$otherdeducNtax);		//save Total Non TS Deduction to payroll_paystub_entry table
			
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
						,"payperiod_type" => $rsResult->fields['payperiod_type']
						)
					,"paystubaccount" => array(
						 "earning" => array(
							 "basic" => "0"
							,"Regulartime" => "0"
							,"COLA" => "0"
							,"COLAperDay" => "0"
							,"totalDays" => "0"
							,"MWR" => "0"
							,"DailyRate" => "0"
							,"HourlyRate" => "0"
						)
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
							,"Bonus Pay" => Number_Format($BonusPay,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"Basic Salary" => Number_Format("0",$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"PGsalary" => Number_Format($BonusPay,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"gross_nontaxable_income" => Number_Format($grossandnontaxable,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"taxable_Gross" => Number_Format($taxableBonus,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"Deduction" => Number_Format($SumAllDeduction,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"SatutoryDeduction" => Number_Format("0",$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"W/H Tax" => Number_Format($bonustax,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"aftertaxgross" => Number_Format($afterTaxGross,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"Net Pay" => Number_Format($varNetpay,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"Loan_Total" => Number_Format("0",$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"other_taxable_income" => Number_Format($SumTABEarning,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"other_deduction" => Number_Format($otherdeducNtax,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"TotalEarning_payslip" => Number_Format($SumAllEarning,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"SumSABEarning" => Number_Format($SumSABEarning,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"SumTSABEarning" => Number_Format($SumTSABEarning,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"SumTABDeduction" => Number_Format($SumTABDeduction,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"SumTABEarning" => Number_Format($SumTABEarning,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"BaseSTATGross" => Number_Format("0",$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"SumTSABDeduction" => Number_Format($SumTSABDeduction,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 )
					)
			);
		$ctr++;
		}while($ctr < sizeof($pData['chkAttend']));
//		printa($arrPayStub); exit;
		clsMnge_PG::doSavePayStubArr($arrPayStub);
        if($emp_id_ == ""){
            return $retval;
        }else{
            return $arrPayStub;
        }
	}
	
	/**
	 * Get Bonus Record Formula 1
	 * @author  : irsalvador
	 * @formula : ((Current Basic Pay * No. of Months Renderd) - Total Leave Deduction) / 12 
	 * @param $EmpInfo
	 */
	function getBonusFormula_1($EmpInfo = null, $payperiod_id_ = null, $pData){
		$retval = "";
		IF (is_null($EmpInfo)){ return $retval; }
		
		//To get Employee Current Salary.
		//-------------------------------------------------------->>
		$currentBS = $this->getCurrentBS($EmpInfo);//Get Current Salary Rate
	    $currentMonthly = $this->getMonthlyRate($currentBS);//Get Current Monthly Rate
		$sqlYear = "SELECT payperiod_period_year FROM payroll_pay_period WHERE payperiod_id='".$payperiod_id_."'";
		$getYear = $this->conn->Execute($sqlYear);
	    $ytdTADeduction = $this->getTAYTD($EmpInfo,$getYear->fields['payperiod_period_year']); 
//		$ytdTADeduction = clsPayroll_Details::getYTD($EmpInfo,17,$paystub_id_);//Get total TA Deduction
//		printa($pData); printa($currentBS); printa($ytdTADeduction); exit;

		//To get months render
		IF($pData['ishire']=='1'){
			$workMonth = $this->getHiredate($EmpInfo);
		}ELSE{
			$workMonth = 12;
		}
		//compute Bonus with formula : (YTD Monthly Pay - YTD TA Deduction)/12	
		IF($pData['leavededuct']){	
			$BonusPay = (($currentMonthly['ratepermonth']*$workMonth)-$ytdTADeduction['ytdamount'])/12;
		} ELSE {
			$BonusPay = (($currentMonthly['ratepermonth']*$workMonth))/12;
		}
		return $BonusPay;
	}
	
	/**
	 * Get Bonus Record Formula 2
	 * @author  : irsalvador
	 * @formula : (YTD Basic Pay - YTD TA Deduction)/12 
	 * @param $EmpInfo
	 */
	function getBonusFormula_2($EmpInfo = null, $payperiod_id_ = null, $pData){
		$retval = "";
		IF (is_null($EmpInfo)){ return $retval; }
		
		// get employee YTD Salary.
		//-------------------------------------------------------->>
		$sqlYear = "SELECT payperiod_period_year,payperiod_period,payperiod_freq FROM payroll_pay_period WHERE payperiod_id='".$payperiod_id_."'";
		$getYear = $this->conn->Execute($sqlYear);
		$ytdBasicPay = clsPayroll_Details::getYTD($EmpInfo,1,$getYear->fields['payperiod_period_year'],12,$getYear->fields['payperiod_freq'],TRUE);//Get total Basic Salary
		$ytdTADeduction = $this->getTAYTD($EmpInfo,$getYear->fields['payperiod_period_year']); 
		//$ytdTADeduction = clsPayroll_Details::getYTD($EmpInfo,17,$paystub_id_);//Get total TA Deduction

		//compute Bonus with formula : (YTD Basic Pay - YTD TA Deduction)/12
		IF($pData['leavededuct']){	
			$BonusPay = ($ytdBasicPay['ytdamount']-$ytdTADeduction['ytdamount'])/12;
		} ELSE {
			$BonusPay = ($ytdBasicPay['ytdamount'])/12;
		}
		return $BonusPay;
	}
	
	/**
	 * Get Bonus Record Formula 3
	 * @author  : irsalvador
	 * @formula : (Current Basic Pay * No. of Months Renderd)/12 
	 * @param $EmpInfo
	 */
	function getBonusFormula_3($EmpInfo = null, $paystub_id_ = null, $pData){
		$retval = "";
		IF (is_null($EmpInfo)){ return $retval; }
		
		// get employee Current Basic Salary.
		//-------------------------------------------------------->>
		$currentBS = $this->getCurrentBS($EmpInfo);//To Get Current Salary Rate
	    $currentMonthly = $this->getMonthlyRate($currentBS);//To get Monthly Rate:
//	    printa($currentMonthly);
		
		//To get months render
		IF($pData['ishire']=='1'){
			$workMonth = $this->getHiredate($EmpInfo);
		}ELSE{
			$workMonth = 12;
		}
		//compute Bonus with formula : (YTD Basic Pay - YTD TA Deduction)/12
		$BonusPay = ($currentMonthly['ratepermonth']*$workMonth)/12;
		return $BonusPay;
		
	}
	
	/**
	 * Get Bonus Record
	 * @author  : irsalvador
	 * @formula : ((Current Basic Pay * No. of Months Renderd) - Total Leave Deduction) / 12 
	 * @param $EmpInfo
	 */
	function getBonusFormula_4($EmpInfo = null, $paystub_id_ = null){
		$retval = "";
		IF (is_null($EmpInfo )){ return $retval; }
		
	}
	
	/**
	 * Get Bonus Record
	 * @author  : irsalvador
	 * @formula : ((Current Basic Pay * No. of Months Renderd) - Total Leave Deduction) / 12 
	 * @param $bformala_
	 */
	function getBonusFormula_5($EmpInfo = null, $paystub_id_ = null){
		$retval = "";
		IF (is_null($EmpInfo )){ return $retval; }
		
	}
	
	/**
	 * Get Bonus Record for FAS Company
	 * @author  : irsalvador
	 * @formula : (Current Basic Pay * No. of Months Renderd)/12 
	 * @param $bformala_
	 */
	function getBonusFormula_6($EmpInfo = null, $paystub_id_ = null, $pData){
		$retval = "";
		IF (is_null($EmpInfo )){ return $retval; }
		
		// get employee Current Basic Salary.
		//-------------------------------------------------------->>
		$currentBS = $this->getCurrentBS($EmpInfo);//To Get Current Salary Rate
	    $currentMonthly = $this->getMonthlyRate($currentBS);//To get Monthly Rate:
//	    printa($currentMonthly);
//		printa($pData);
		//To get months render
		IF($pData['ishire']=='1'){
			$workMonth = $this->getHiredate($EmpInfo,1);
		}ELSE{
			$workMonth = 12;
		}
		//compute Bonus with formula : (Current BasicPay * Working Month)/12
		$BonusPay = ($currentMonthly['ratepermonth']*$workMonth)/12;
//		echo '<br>'.$workMonth." Working Month"; echo '<br>'.$BonusPay." Bonus Pay"; exit;
		return $BonusPay;
	}
	
	/**
	 * Get Bonus Record for FEAP Company
	 * @author  : irsalvador
	 * @formula : (Current Basic Pay * No. of Months Renderd)/12 
	 * @param $bformala_
	 */
	function getBonusFormula_7($EmpInfo = null, $paystub_id_ = null, $pData){
		$retval = "";
		IF (is_null($EmpInfo )){ return $retval; }
		
		// get employee Current Basic Salary.
		//-------------------------------------------------------->>
		$currentBS = $this->getCurrentBS($EmpInfo);//To Get Current Salary Rate
	    $currentMonthly = $this->getMonthlyRate($currentBS);//To get Monthly Rate:
//	    printa($currentMonthly);
//		printa($pData);
		//To get months render
		IF($pData['ishire']=='1'){
			$workMonth = $this->getHiredate($EmpInfo,2);
		}ELSE{
			$workMonth = 12;
		}
		//compute Bonus with formula : (Current BasicPay * Working Month)/12
		$BonusPay = ($currentMonthly['ratepermonth']*$workMonth)/12;
//		echo '<br>'.$workMonth." Working Month"; echo '<br>'.$BonusPay." Bonus Pay"; exit;
		return $BonusPay;
	}
	
	function getHiredate($emp_id_ = null,$iffas = 0){
		$retval = "";
		IF (is_null($emp_id_)){ return $retval; }
		$sqlhiredate = "SELECT a.emp_id,a.emp_hiredate,DATE_FORMAT(a.emp_hiredate,'%Y') as hiredyear,DATE_FORMAT(a.emp_hiredate,'%m') as hiredmonth, DATE_FORMAT(a.emp_hiredate,'%d') as hiredday FROM emp_masterfile a WHERE a.emp_id = '".$emp_id_."'";
		$rsResult = $this->conn->Execute($sqlhiredate);
		IF (!$rsResult->EOF){
			IF($rsResult->fields['hiredyear']==date("Y")){
				IF($iffas==1){
					$initialmonths = $rsResult->fields['hiredmonth'];
					$initialday = $rsResult->fields['hiredday'];
					IF($initialday < 8){
						$hiremonth = 1;
					}ELSEIF($initialday > 7 && $initialday < 16){
						$hiremonth = .75;
					}ELSEIF($initialday > 15 && $initialday < 24){
						$hiremonth = .5;
					}ELSEIF($initialday > 23){
						$hiremonth = .25;
					}
					$hiremonth_ = $hiremonth;
				}ELSEIF($iffas==2){
					$initialmonths = $rsResult->fields['hiredmonth'];
					$initialday = $rsResult->fields['hiredday'];
					IF($initialday < 16){
						$hiremonth = 1;
					}ELSEIF($initialday > 15){
						$hiremonth = 0;
					}
					$hiremonth_ = $hiremonth;	
				}ELSE{					
					$initialmonths = $rsResult->fields['hiredmonth'];
				}
				$monthRender = (12 - $initialmonths)+ $hiremonth_;
			}ELSE{
				//$monthRender = 12;
				$initialmonths = $rsResult->fields['hiredmonth'];
				$initialday = $rsResult->fields['hiredday'];
				$hiremonth = 1;
				$hiremonth_ = $hiremonth;
				$monthRender = (12 - $initialmonths)+ $hiremonth_;
			}
//			echo '<br>'.$initialmonths." initialmonths"; echo '<br>'.$hiremonth." hiremonth"; echo '<br>'.$monthRender." monthRender";
			return $monthRender;
		}
	}
	
	function getCurrentBS($emp_id_ = null){
		$retval = "";
		IF (is_null($emp_id_)){ return $retval; }
		$sqlcurrentBS = "SELECT * FROM salary_info a JOIN factor_rate b on (b.fr_id=a.fr_id) LEFT JOIN app_wagerate wrate on (wrate.wrate_id=b.wrate_id) WHERE a.emp_id = '".$emp_id_."' AND a.salaryinfo_isactive = '1'";
		$rsResult = $this->conn->Execute($sqlcurrentBS);
		IF (!$rsResult->EOF){
			return $rsResult->fields;
		}
	}
	
	/**
	 * Get Monthly Rate per Employee
	 */
	function getMonthlyRate($SalInfo = array()){
		$BP = $SalInfo['salaryinfo_basicrate'];
		$fr_dayperyear = $SalInfo['fr_dayperyear'];
		$fr_hrperday = $SalInfo['fr_hrperday'];
		$fr_dayperweek = $SalInfo['fr_dayperweek'];
		
		/*compute rate per day, per hour, per sec per employee */
			if ($SalInfo['salarytype_id']==5) {
				//convert monthly rate
				$ratepermonth = $BP;
				$rateperday = (($BP*12)/$fr_dayperyear);
				$rateperhour = (($rateperday)/$fr_hrperday);
				$ratepersec = (($rateperhour)/3600);
			} elseif ($SalInfo['salarytype_id']==1) {
				//convert hourly rate 
				$ratepermonth = ((($BP*$fr_hrperday)*$fr_dayperyear)/12);
				$rateperday = ($BP*$fr_hrperday);
				$rateperhour = $BP;
				$ratepersec = ($BP/3600);
			} elseif ($SalInfo['salarytype_id']==3) {
				//convert weekly rate
				$rateperday = ($BP/$fr_dayperweek);
				$rateperhour = (($rateperday)/$fr_hrperday);
				$ratepersec = (($rateperhour)/3600);
				$ratepermonth = (($rateperday*$fr_dayperyear)/12);
			} elseif ($SalInfo['salarytype_id']==6) {
				//convert annual rate to second
				$ratepermonth = ($BP/12);
				$rateperday = ($BP/$fr_dayperyear);
				$rateperhour = ($rateperday/$fr_hrperday);
				$ratepersec = ($rateperhour/3600);
			} elseif ($SalInfo['salarytype_id']==4) {
				//convert bi-weekly rate
				$rateperday = (($fr_dayperweek * 2)/$BP);
				$ratepermonth = ($rateperday*$fr_dayperyear)/12;
				$rateperhour = ($rateperday/$fr_hrperday);
				$ratepersec = ($rateperhour/3600);
			} else {
				//convert daily rate
				$rsResult->fields['fr_hrperday'];
				$ratepermonth = ($BP*$fr_dayperyear)/12;
				$rateperday = $BP;
				$rateperhour = ($rateperday/$fr_hrperday);
				$ratepersec = ($rateperhour/3600);
			}
			
//			return the day rate value
			$objClsMngeDecimal = new Application();
			return $MonthlyRate = array("rateperday" => Number_Format($rateperday,$objClsMngeDecimal->getGeneralDecimalSettings(),'.','')
									,"rateperhour" => Number_Format($rateperhour,$objClsMngeDecimal->getGeneralDecimalSettings(),'.','')
									,"ratepersec" => Number_Format($ratepersec,$objClsMngeDecimal->getGeneralDecimalSettings(),'.','')
									,"ratepermonth" => Number_Format($ratepermonth,$objClsMngeDecimal->getGeneralDecimalSettings(),'.',''));
			
	}
	
	/**
	 *  Get Custom Fields
	 *  @update by: IR Salvador
	 */
	function getCustomFields($emp_id_= null,$payperiod_id_= null, $uptadet_id_ = null){
		$qry[]="b.emp_id='".$emp_id_."'";
		$qry[]="b.payperiod_id='".$payperiod_id_."'";
		$jn[] = "JOIN cf_detail b on (b.cfhead_id=a.cfhead_id)";
		if($uptahead_id_ != null){
			$qry[]="c.uptadet_id='".$uptadet_id_."'";
			$jn[] = "JOIN tks_uploadta_details c on (c.uptadet_id=b.uptadet_id)";
		}
		$join = " ".implode(" ",$jn);
		$criteria = " where ".implode(" and ",$qry);
		$sql = "SELECT a.cfhead_id, a.cfhead_name, a.cfhead_type, b.cfdetail_rec, b.cfdetail_id
				FROM cf_head a
				$join
				$criteria";
		$resultData = $this->conn->Execute($sql);
		$cResult = array();
		while(!$resultData->EOF){
			$cResult[] = $resultData->fields;
			$resultData->MoveNext();
		}
		if(count($cResult) > 0){
			return $cResult;
		} else {
			$sql ="select * from cf_head";
				$resultData = $this->conn->Execute($sql);
			$cResult = array();
			while(!$resultData->EOF){
				$cResult[] = $resultData->fields;
				$resultData->MoveNext();
			}
			return $cResult;
		}
	}

	/**
	 * Get All Bonus Code Pay Element Account
	 * @author : jim
 	 * @return all PSAccnt List array
	 */
	function getALLBonusPEAccnt() {
		$objData = $this->conn->Execute("SELECT psa_id, IF(psa_type=1,CONCAT('Earning - ',psa_name),IF(psa_type=2,CONCAT('EE Ded - ',psa_name),IF(psa_type=3,CONCAT('ER Ded - ',psa_name),IF(psa_type=4,CONCAT('Total - ',psa_name),CONCAT('Accrual - ',psa_name))))) as psatype
										 FROM payroll_ps_account
										 WHERE psa_procode = '1'
										 ORDER BY psa_name");
		$cResult = array();
		while ( !$objData->EOF ) {       	
			$cResult[] = $objData->fields;
        	$objData->MoveNext();
        }
        return $cResult;
	}
	
	/**
	 * Get All Bonus Formula
	 * @author : jim
 	 * @return all BonusFormula List array
	 */
	function getALLBonusFormula() {
		$objData = $this->conn->Execute("SELECT bonus_id, 
		IF(bonus_code='BCBSwithLD',CONCAT('Formula [BCBSwithLD] - ',bonus_Desc),
		IF(bonus_code='BARS',CONCAT('Formula [BARS] - ',bonus_Desc),
		IF(bonus_code='BCBSnoLD',CONCAT('Formula [BCBSnoLD] - ',bonus_Desc),
		IF(bonus_code='BCBSxP1.25',CONCAT('Formula [BCBSxP1.25] - ',bonus_Desc),
		IF(bonus_code='IPE',CONCAT('Formula [IPE] - ',bonus_Desc),
		IF(bonus_code='FAS',CONCAT('Formula [FAS] - ',bonus_Desc),
		CONCAT('Formula [FEAP] - ',bonus_Desc))))))) as bonus_code
										 FROM bonus_formula
										 ORDER BY bonus_id");
		$cResult = array();
		while ( !$objData->EOF ) {       	
			$cResult[] = $objData->fields;
        	$objData->MoveNext();
        }
        return $cResult;
	}
	
function getTableList_Emp_OtherPay() {
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
			$sqlsal = "SELECT DATE_FORMAT(payperiod_start_date,'%Y-%m-%d') as payperiod_start_date, DATE_FORMAT(payperiod_end_date,'%Y-%m-%d') as payperiod_end_date, payperiod_id FROM payroll_pay_period WHERE payperiod_id='".$_GET['ppsched_view']."'";
			$result = $this->conn->Execute($sqlsal);
			$var = $result->fields['payperiod_id'];
			$qry[]="empinfo.emp_id not in (SELECT a.emp_id FROM payroll_pps_user a JOIN payroll_paystub_report re on (a.emp_id=re.emp_id) WHERE payperiod_id = '".$_GET['ppsched_view']."')";
			$qry[]="sal.salaryinfo_effectdate <= '".$result->fields['payperiod_end_date']."'";
			$qry[]="pps.pps_id = '".$_GET['ppsched']."'";
			$otadd = "<a href=\"?statpos=process_payroll&otcomp=',pps.pps_id,'&emp=',empinfo.emp_id,'&edit=',$var,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/leaveicon.png\" title=\"TA\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
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
		$qry[] = "amm.payperiod_id=".$_GET['ppsched_view'];
        // put all query array into one criteria string
		$criteria = (count($qry)>0)?" where ".implode(" AND ",$qry):"";
		// Sort field mapping
		$arrSortBy = array(
		 "chkbox" => "chkbox"
		//,"empLink" => "empLink"
		,"emp_idnum" => "emp_idnum"
		,"pi_lname" => "pi_lname"
		,"pi_fname" => "pi_fname"
		,"post_name" => "post_name"
		,"comp_name" => "comp_name"
		,"branchinfo_name" => "branchinfo_name"
		,"ud_name" => "ud_name"
		);
		$groupBy = " GROUP BY empinfo.emp_idnum ";
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
		$sql_ = "SELECT COUNT(distinct empinfo.emp_idnum) AS mycount_
					FROM emp_masterfile empinfo
					JOIN emp_personal_info pinfo ON (pinfo.pi_id=empinfo.pi_id)
					LEFT JOIN app_userdept dept ON (dept.ud_id=empinfo.ud_id)
					LEFT JOIN emp_position post ON (post.post_id=empinfo.post_id)
					LEFT JOIN company_info comp ON (comp.comp_id=empinfo.comp_id)
					LEFT JOIN branch_info bran ON (bran.branchinfo_id=empinfo.branchinfo_id)
					JOIN payroll_ps_amendemp ppa ON (ppa.emp_id=empinfo.emp_id)
					JOIN payroll_ps_amendment amm ON (amm.psamend_id=ppa.psamend_id)
				$qrysql
				$qrysql_
				$qrysql2
				$criteria
				$groupBy
				$strOrderBy";
		$rsResult = $this->conn->Execute($sql_);
		if (!$rsResult->EOF) {
			$mycount = $rsResult->fields['mycount_'];
		}
		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$ctr=0;
		$chkAttend = "<input type=\"checkbox\" name=\"chkAttend[]\" id=\"chkAttend[',@t1:=@t1+1,']\" value=\"',empinfo.emp_id,'\" onclick=\"javascript:UncheckAll({$mycount});\">";
		
		// SqlAll Query
		$sql = "SELECT distinct pinfo.pi_lname,pinfo.pi_fname, dept.ud_name,  post.post_name, bran.branchinfo_name, comp.comp_name,
				CONCAT('$chkAttend') as chkbox, empinfo.emp_idnum
						FROM emp_masterfile empinfo
						JOIN emp_personal_info pinfo on (pinfo.pi_id=empinfo.pi_id)
						LEFT JOIN app_userdept dept on (dept.ud_id=empinfo.ud_id)
						LEFT JOIN emp_position post on (post.post_id=empinfo.post_id)
						LEFT JOIN company_info comp on (comp.comp_id=empinfo.comp_id)
						LEFT JOIN branch_info bran on (bran.branchinfo_id=empinfo.branchinfo_id)
						JOIN payroll_ps_amendemp ppa ON (ppa.emp_id=empinfo.emp_id)
					JOIN payroll_ps_amendment amm ON (amm.psamend_id=ppa.psamend_id)
						$qrysql
						$qrysql_
						$qrysql2
						$criteria
						$groupBy
						$strOrderBy";
		// Field and Table Header Mapping
		$arrFields = array(
		 "chkbox" => "<input type=\"checkbox\" name=\"chkAttendAll\" id=\"chkAttendAll\" onclick=\"javascript:CheckAll({$mycount});\">"
		//,"empLink" => "Action"
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
	
	function getTAYTD($emp_id_ = null, $year_ = null){
		$qry[] = "(a.tatbl_id IN (3,4,1))";
		$qry[] = "a.emp_id='".$emp_id_."'";
		$qry[] = "a.paystub_id!=0";
		$qry[] = "(c.payperiod_period >= 1 AND c.payperiod_period <= 12)";
		$qry[] = "(c.payperiod_period_year = $year_)";
		$criteria = (count($qry)>0)?" where ".implode(" AND ",$qry):"";
		$sql = "select sum(emp_tarec_amtperrate) as ytdamount 
				FROM ta_emp_rec a 
				JOIN ta_tbl b on (b.tatbl_id=a.tatbl_id) 
				JOIN payroll_pay_period c on (c.payperiod_id=a.payperiod_id) 
				$criteria";
		$varYTD = $this->conn->Execute($sql);
		if(!$varDeducH->EOF){
			$varYTD_ = $varYTD->fields;
			return $varYTD_;
		}
	}
}
?>