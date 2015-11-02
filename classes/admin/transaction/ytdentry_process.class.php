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
class clsYTDEntry_Process{

	var $conn;
	var $fieldMap;
	var $Data;

	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsYTDEntry_Process object
	 */
	function clsYTDEntry_Process($dbconn_ = null){
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
			$valData = addslashes($valData);
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
		$sql = "delete from /*app_modules*/ where mnu_id=?";
		$this->conn->Execute($sql,array($id_));
		$_SESSION['eMsg']="Successfully Deleted.";
	}

	/**
	 * Get all the Table Listings
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
		$qry[] = "ppp.payperiod_type=2";
		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		 "pps_name" => "pps_name"
		,"salaryclass_id" => "salaryclass_name"
		,"pp_stat_id" => "stat_name"
		,"payperiod_start_date" => "payperiod_start_date"
		,"payperiod_end_date" => "payperiod_end_date"
		,"payperiod_trans_date" => "payperiod_trans_date"
		,"classification" => "classification"
		);

		if(isset($_GET['sortby'])){
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}else{
			$strOrderBy = " order by ppp.payperiod_id DESC";
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
		IF(!$rsResult->EOF){
			$mycount = $rsResult->fields['mycount_'];
		}

		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$viewLink = "<a href=\"?statpos=ytdentry_process&ppsched=',psar.pps_id,'&ppsched_view=',ppp.payperiod_id,'\">',psar.pps_name,'</a>";
		$empLink = "<a href=\"?statpos=ytdentry_process&empinput=',psar.pps_id,'\"><img src=\"".SYSCONFIG_THEME_URLPATH.SYSCONFIG_THEME."/images/admin/report_user.gif\" title=\"Select Employee\" hspace=\"2px\" border=0></a>";
		$ctr=0;		
		$chkAttend = "<input type=\"checkbox\" name=\"chkAttend[]\" id=\"chkAttend[',@t1:=@t1+1,']\" value=\"',ppp.payperiod_id,'\" onclick=\"javascript:UncheckAll(".$mycount.");\">";

		// SqlAll Query
		$sql = "SELECT ppp.*, CONCAT('$viewLink') as viewdata, 
				DATE_FORMAT(payperiod_start_date,'%d %b %Y %h:%i %p') as payperiod_start_date,
				DATE_FORMAT(payperiod_end_date,'%d %b %Y %h:%i %p') as payperiod_end_date,
				DATE_FORMAT(payperiod_trans_date,'%d %b %Y') as payperiod_trans_date,
				psar.pps_name, CONCAT('$chkAttend') as chkbox,
				IF(salaryclass_id='1','Daily',IF(salaryclass_id='2','Weekly',IF(salaryclass_id='3','Bi-Weekly',IF(salaryclass_id='4','Semi-monthly',IF(salaryclass_id='5','Monthly','Annual'))))) as salaryclass_id,
				IF(pp_stat_id='1','OPEN',IF(pp_stat_id='2','Locked - Pending Approval',IF(pp_stat_id='3','CLOSED','Post Adjustment'))) as pp_stat_id,
				IF(ppp.payperiod_type='2','YTD',IF(ppp.payperiod_type='3','Bonus',IF(ppp.payperiod_type='4','Others','Normal'))) as classification
				FROM payroll_pay_period ppp
				JOIN payroll_pay_period_sched psar on (psar.pps_id=ppp.pps_id)
				$criteria
				$strOrderBy";

		// Field and Table Header Mapping
		$arrFields = array(
		 /*"chkbox"=>"<input type=\"checkbox\" name=\"chkAttendAll\" id=\"chkAttendAll\" onclick=\"javascript:CheckAll(".$mycount.");\">"*/
		 "viewdata" => "Name"
		,"salaryclass_id" => "Salary Type"
		,"pp_stat_id" => "Status"
		,"payperiod_start_date" => "Start"
		,"payperiod_end_date" => "End"
		,"payperiod_trans_date" => "Pay Date"
		,"classification" => "Payroll Type"
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
		$tblDisplayList->tblBlock->assign("noPaginatorStart","<!--");
		$tblDisplayList->tblBlock->assign("noPaginatorEnd","-->");
		return $tblDisplayList->getTableList($arrAttribs);
	}
	
	/**
	 * Get all the Table Listings
	 * @return array
	 */
	function getEmpToProcess() {
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
		if ($_GET['statpos']=='ytdentry_process') {
			$qrysql= "inner join salary_info sal on (sal.emp_id=empinfo.emp_id)";
			$qrysql_= "inner join payroll_pps_user pps on (pps.emp_id=empinfo.emp_id)";
			$sqlsal = "Select DATE_FORMAT(payperiod_start_date,'%Y-%m-%d') as payperiod_start_date, DATE_FORMAT(payperiod_end_date,'%Y-%m-%d') as payperiod_end_date, payperiod_id  from payroll_pay_period where payperiod_id='".$_GET['ppsched_view']."'";
			$result = $this->conn->Execute($sqlsal);
			$var = $result->fields['payperiod_id'];
			$qry[]="empinfo.emp_id not in (select a.emp_id from payroll_pps_user a inner join payroll_paystub_report re on (a.emp_id=re.emp_id) where payperiod_id = '".$_GET['ppsched_view']."')";
			//$qry[]="sal.salaryinfo_effectdate <= '".$result->fields['payperiod_end_date']."'";
			$qry[]="ytdhead.payperiod_id='".$_GET['ppsched_view']."'";
			$qry[]="pps.pps_id = '".$_GET['ppsched']."'";
			$otadd = "<a href=\"?statpos=process_payroll&otcomp=',pps.pps_id,'&emp=',empinfo.emp_id,'&edit=',$var,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/leaveicon.png\" title=\"TA\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
			$leaveadd = "<a href=\"?statpos=process_payroll&edit=',pps.pps_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/leaveicon.png\" title=\"Leave\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
			
			$qry[] = "sal.salaryinfo_isactive = '1'";
		} else {
			$qry[]="empinfo.emp_id not in (select a.emp_id from payroll_pps_user a)";
		}
		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";
		// Sort field mapping
		$arrSortBy = array(
		 "chkbox" => "chkbox"
		,"emp_idnum" => "emp_idnum"
		,"pi_lname" => "pi_lname"
		,"pi_fname" => "pi_fname"
		,"post_name" => "post_name"
		,"comp_name" => "comp_name"
		,"branchinfo_name" => "branchinfo_name"
		,"ud_name" => "ud_name"
		);

		if (isset($_GET['sortby'])) {
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		} else {
			$strOrderBy = " order by pinfo.pi_lname";
		}
		
		//@note: this is used to count and check all the checkbox.
		//@note set t1 = 0
		$sql = "set @t1:=0";
		$this->conn->Execute($sql);
		//get total number of records and pass it to the javascript function CheckAll
			$sql_ = "SELECT COUNT(*) AS mycount_
						FROM import_ytd_details a
						JOIN import_ytd_head ytdhead on (ytdhead.import_ytd_head_id=a.import_ytd_head_id)
						JOIN emp_masterfile empinfo ON (a.emp_id=empinfo.emp_id)
						JOIN emp_personal_info pinfo ON (pinfo.pi_id=empinfo.pi_id)
						LEFT JOIN app_userdept dept ON (dept.ud_id=empinfo.ud_id)
						LEFT JOIN emp_position post ON (post.post_id=empinfo.post_id)
						LEFT JOIN company_info comp ON (comp.comp_id=empinfo.comp_id)
						LEFT JOIN branch_info bran ON (bran.branchinfo_id=empinfo.branchinfo_id)
					$qrysql
					$qrysql_
					$criteria
					$strOrderBy";
			$rsResult = $this->conn->Execute($sql_);
			if (!$rsResult->EOF) {
				$mycount = $rsResult->fields['mycount_'];
			}
		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$ctr=0;
		$chkAttend = "<input type=\"checkbox\" name=\"chkAttend[]\" id=\"chkAttend[',@t1:=@t1+1,']\" value=\"',a.import_ytd_detail_id,'\" onclick=\"javascript:UncheckAll({$mycount});\">";
		
		// SqlAll Query
		$sql = "select pinfo.pi_lname,pinfo.pi_fname, dept.ud_name,  post.post_name, bran.branchinfo_name, comp.comp_name,
				CONCAT('$chkAttend') as chkbox, empinfo.emp_idnum, CONCAT('$otadd') as empLink
						FROM import_ytd_details a
						JOIN import_ytd_head ytdhead on (ytdhead.import_ytd_head_id=a.import_ytd_head_id)
						JOIN emp_masterfile empinfo ON (a.emp_id=empinfo.emp_id)
						JOIN emp_personal_info pinfo on (pinfo.pi_id=empinfo.pi_id)
						LEFT JOIN app_userdept dept on (dept.ud_id=empinfo.ud_id)
						LEFT JOIN emp_position post on (post.post_id=empinfo.post_id)
						LEFT JOIN company_info comp on (comp.comp_id=empinfo.comp_id)
						LEFT JOIN branch_info bran on (bran.branchinfo_id=empinfo.branchinfo_id)
						$qrysql
						$qrysql_
						$criteria
						$strOrderBy";

		// Field and Table Header Mapping
		$arrFields = array(
		 "chkbox" => "<input type=\"checkbox\" name=\"chkAttendAll\" id=\"chkAttendAll\" onclick=\"javascript:CheckAll({$mycount});\">"
		,"emp_idnum" => "Emp No."
		,"pi_lname" => "Last Name"
		,"pi_fname" => "First Name"
		,"post_name" => "Position"
		,"comp_name" => "Company"
		,"branchinfo_name" => "Location"
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
			$sqlsal = "Select DATE_FORMAT(payperiod_start_date,'%Y-%m-%d') as payperiod_start_date, DATE_FORMAT(payperiod_end_date,'%Y-%m-%d') as payperiod_end_date, payperiod_id  from payroll_pay_period where payperiod_id='".$payperiod_id_."'";
			$sqlsal_ = $this->conn->Execute($sqlsal);
//			printa($sqlsal_);
			$notin = 'not in';
			//$qry[] = "d.salaryinfo_effectdate <= '".$sqlsal_->fields['payperiod_end_date']."'";
			//$qry[] = "c.emp_stat in ('1','7','10')";
			$qry[] = "d.salaryinfo_isactive = 1";
			$qry[] = "e.payperiod_id = '".$payperiod_id_."'";
			$stat_ = (count($qry)>0)?" and ".implode(" and ",$qry):"";
			$var = "JOIN salary_info d on (c.emp_id=d.emp_id)";
		}
		$sql = "SELECT count(c.emp_id) as totalemp 
				FROM import_ytd_details b
				JOIN payroll_pps_user a on (b.emp_id=a.emp_id)
				JOIN emp_masterfile c on (a.emp_id = c.emp_id)
				JOIN import_ytd_head e on (e.import_ytd_head_id=b.import_ytd_head_id)
				$var
				WHERE a.pps_id='".$pps_id_."' $stat_ and a.emp_id $notin (SELECT a.emp_id FROM payroll_pps_user a JOIN payroll_paystub_report re on (a.emp_id=re.emp_id) WHERE payperiod_id = '".$payperiod_id_."')";
		$objData = $this->conn->Execute($sql);
		if(!$objData->EOF){
			return $objData->fields;
		}
	}
	
	/**
	 * Used to generate YTD computation
	 * @author: jim
	 */
	function doGenerateYTD($payperiod_id_ = null, $pData){
		//printa($payperiod_id_); printa($pData); //exit;
		$objClsMngeDecimal = new Application();
		$retval = "";
		if (is_null($payperiod_id_ )){ return $retval; }
		if (is_null($pData)){ return $retval; }
		
		$ctr = 0;
		do {$payStubSerialize = array();
			$paystub_id = clsMnge_PG::doSavePayStub($pData['chkAttend'][$ctr],$payperiod_id_);// Save Paystub
			$empinfo = "SELECT *,a.emp_id,a.comp_id, txcep.taxep_name,
							CONCAT(g.pi_fname,' ',UPPER(SUBSTRING(g.pi_mname,1,1)),'. ',g.pi_lname) as fullname, 
							bank.bankiemp_acct_no, blist.banklist_name, 
							DATE_FORMAT(f.payperiod_trans_date,'%d') as ppdTransDate, 
							DATE_FORMAT(f.payperiod_start_date,'%d') as ppdStartDate
							FROM import_ytd_details ytd
							JOIN emp_masterfile a on (ytd.emp_id=a.emp_id)
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
							WHERE ytd.import_ytd_detail_id='".$pData['chkAttend'][$ctr]."' and c.pps_id='".$_GET['ppsched']."' and f.payperiod_id='".$payperiod_id_."'";
			$rsResult = $this->conn->Execute($empinfo);
			if(!$rsResult->EOF){
				$rsResult->fields['paystubdetails'] = unserialize($rsResult->fields['import_ytd_detail_arr']);
			}
			//printa($rsResult->fields); exit;
			
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
				$varDec = clsMnge_PG::getTotalTaxByPayPeriod($rsResult->fields['emp_id'],$getTAXLimit->fields['tp_id'],2,$taxableBonus_,$rsResult->fields['taxep_id'],4);
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
			clsMnge_PG::doSavePayStubEntry($paystub_id,$pData['psaid'],$bonustax);    			  //save Bonus to payroll_paystub_entry table
			clsMnge_PG::doSavePayStubEntry($paystub_id,30,$taxableBonus);	  					  //save Taxablegross
			clsMnge_PG::doSavePayStubEntry($paystub_id,8,$bonustax,$taxableBonus,$conVertTaxper); //save W/H Tax
			clsMnge_PG::doSavePayStubEntry($paystub_id,5,$varNetpay);    		//save NETPAY to payroll_paystub_entry table
			clsMnge_PG::doSavePayStubEntry($paystub_id,2,$SumAllDeduction);    	//save Total Deduction to payroll_paystub_entry table
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
							 "basic" => $rsResult->fields['paystubdetails']['ytd_summary']['basicSalaryRate']
							,"Regulartime" => $rsResult->fields['paystubdetails']['ytd_summary']['basicPGRate']
							,"COLA" => $rsResult->fields['paystubdetails']['ytd_summary']['COLA']
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
    * 
    * Saving YTD From Import Pay Elements and Summary
    * @param int $payperiod_id_
    * @param array $pData
    * @author IR Salvador
    */
    function doSaveYTD($payperiod_id_ = null, $pData){
    //printa($payperiod_id_); printa($pData); //exit;
		$objClsMngeDecimal = new Application();
		$retval = "";
		if (is_null($payperiod_id_ )){ return $retval; }
		if (is_null($pData)){ return $retval; }
		
		$ctr = 0;
		do {$payStubSerialize = array();
			$paystub_id = clsMnge_PG::doSavePayStub($pData['chkAttend'][$ctr],$payperiod_id_);// Save Paystub
			$empinfo = "SELECT *,a.emp_id,a.comp_id, txcep.taxep_name,
							CONCAT(g.pi_fname,' ',UPPER(SUBSTRING(g.pi_mname,1,1)),'. ',g.pi_lname) as fullname, 
							bank.bankiemp_acct_no, blist.banklist_name, 
							DATE_FORMAT(f.payperiod_trans_date,'%d') as ppdTransDate, 
							DATE_FORMAT(f.payperiod_start_date,'%d') as ppdStartDate,
							DATE_FORMAT(f.payperiod_start_date, '%Y-%m-%d') as startDate,
							DATE_FORMAT(f.payperiod_end_date, '%Y-%m-%d') as endDate
							FROM import_ytd_details ytd
							JOIN emp_masterfile a on (ytd.emp_id=a.emp_id)
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
							WHERE ytd.import_ytd_detail_id='".$pData['chkAttend'][$ctr]."' and c.pps_id='".$_GET['ppsched']."' and f.payperiod_id='".$payperiod_id_."'";
			$rsResult = $this->conn->Execute($empinfo);
			if(!$rsResult->EOF){
				$rsResult->fields['paystubdetails'] = unserialize($rsResult->fields['import_ytd_detail_arr']);
			}
			/*
			//Sum all Payelements
			//-------------------------------------------------------->>
			$SumTSABEarning = $rsResult->fields['paystubdetails']['otherStatTaxIncome'];		//TS Earning
			$SumTABEarning = $rsResult->fields['paystubdetails']['otherTaxIncome'];		//T Earning
			$SumSABEarning = $rsResult->fields['paystubdetails']['otherStatIncome'];		//S Earning
			$SumNonABEarning = $rsResult->fields['paystubdetails']['nonTaxIncome'];	//Non TS Earning
			$SumALLABEarning = $SumTSABEarning + $SumTABEarning;	//SUM all AB Earning
			
			$SumTSABDeduction = $rsResult->fields['paystubdetails']['otherStatTaxDed'];	//TS Deduction
			$SumTABDeduction = $rsResult->fields['paystubdetails']['otherTaxDed'];	//T Deduction
			$SumSABDeduction = $rsResult->fields['paystubdetails']['nonTaxIncome'];	//S Deduction
			$SumNonABDeduction = $rsResult->fields['paystubdetails']['otherDeduction']; //Non TS Deduction
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
			/*
			//TAX Computation
			$sql = "SELECT  * FROM tax_policy";
			$getTAXLimit = $this->conn->Execute($sql);
			$taxableBonusPay = $BRecord - $getTAXLimit->fields['tp_other_benefits'];//computation for taxable gross less bonus exeption
			
			$taxableBonus_ = $taxableBonusPay + $basicPG_nostat;//compute taxable gross
			IF($taxableBonus_ > 0){
				// Compute W/H Tax
				//-------------------------------------------------------->>
				$varDec = clsMnge_PG::getTotalTaxByPayPeriod($rsResult->fields['emp_id'],$getTAXLimit->fields['tp_id'],2,$taxableBonus_,$rsResult->fields['taxep_id'],4);
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
			*/
			//printa($rsResult->fields); exit;
			$amendments = $this->getAmendments($rsResult->fields['startDate'],$rsResult->fields['endDate']);
			//printa($amendments);
			$EarningsAndDeductions = array();
			foreach($rsResult->fields['paystubdetails']['Earnings'] as $key => $val){
				$EarningsAndDeductions[] = $val['psa_id'];
			}
			foreach($rsResult->fields['paystubdetails']['Deductions'] as $key => $val){
				$EarningsAndDeductions[] = $val['psa_id'];
			}
			//printa($EarningsAndDeductions);
			$array1 = $EarningsAndDeductions;
			$array2 = $amendments;
			$result = array_diff($array1, $array2);
			//print_r($result);
			$this->doSavePEasAmendment($result, $rsResult->fields['startDate'], $_GET);
			$amendments = $this->doSaveAndGenerateAmendmentsArray($rsResult->fields, $paystub_id);
			//exit;
			$grossPay = $rsResult->fields['paystubdetails']['ytd_summary']['grossPay'];
			$statIncome = $rsResult->fields['paystubdetails']['ytd_summary']['statIncome'];
			$SumTSABEarning = $rsResult->fields['paystubdetails']['ytd_summary']['otherStatTaxIncome'];		//TS Earning
			$SumTABEarning = $rsResult->fields['paystubdetails']['ytd_summary']['otherTaxIncome'];		//T Earning
			$SumSABEarning = $rsResult->fields['paystubdetails']['ytd_summary']['otherStatIncome'];		//S Earning
			$SumNonABEarning = $rsResult->fields['paystubdetails']['ytd_summary']['nonTaxIncome'];	//Non TS Earning
			$SumALLABEarning = $SumTSABEarning + $SumTABEarning;	//SUM all AB Earning
			
			$SumTSABDeduction = $rsResult->fields['paystubdetails']['ytd_summary']['otherStatTaxDed'];	//TS Deduction
			$SumTABDeduction = $rsResult->fields['paystubdetails']['ytd_summary']['otherTaxDed'];	//T Deduction
			$SumSABDeduction = 0;	//S Deduction
			$SumNonABDeduction = $rsResult->fields['paystubdetails']['ytd_summary']['otherDeduction']; //Non TS Deduction
			$SumALLABDeduction = $SumTSABDeduction + $SumTABDeduction + $SumSABDeduction + $SumNonABDeduction;//SUM all AB Deduction
			
			$SumAllEarning = Number_Format($SumAllEarning,$objClsMngeDecimal->getFinalDecimalSettings(),'.','');//SUM all Earnings
			$SumAllDeduction = Number_Format($SumALLABDeduction,$objClsMngeDecimal->getFinalDecimalSettings(),'.','') + Number_Format($bonustax,$objClsMngeDecimal->getFinalDecimalSettings(),'.','');//Sum all Deduction
			$grossandnontaxable = Number_Format($SumNonABEarning,$objClsMngeDecimal->getFinalDecimalSettings(),'.','');//sum all non-taxable income
			$otherdeducNtax = Number_Format($SumNonABDeduction,$objClsMngeDecimal->getFinalDecimalSettings(),'.','');//sum all non-taxble deduction
			$afterTaxGross = Number_Format($rsResult->fields['paystubdetails']['ytd_summary']['taxableIncomeGross'],$objClsMngeDecimal->getFinalDecimalSettings(),'.','') - Number_Format($rsResult->fields['paystubdetails']['ytd_summary']['tax'],$objClsMngeDecimal->getFinalDecimalSettings(),'.','');//deduct the tax to the taxable gross
			//Save Entry Record in payroll_paystub_entry table.
			//clsMnge_PG::doSavePayStubEntry($paystub_id,$pData['psaid'],$bonustax);    			  //save Bonus to payroll_paystub_entry table
			clsMnge_PG::doSavePayStubEntry($paystub_id,1,$rsResult->fields['paystubdetails']['ytd_summary']['basicSalaryRate']);    	//save Basic Pay
			clsMnge_PG::doSavePayStubEntry($paystub_id,30,$rsResult->fields['paystubdetails']['ytd_summary']['taxableIncomeGross']);	  					  //save Taxablegross
			clsMnge_PG::doSavePayStubEntry($paystub_id,8,$rsResult->fields['paystubdetails']['ytd_summary']['tax'],$rsResult->fields['paystubdetails']['ytd_summary']['taxableIncomeGross']); //save W/H Tax
			clsMnge_PG::doSavePayStubEntry($paystub_id,5,$rsResult->fields['paystubdetails']['ytd_summary']['netPay']);    		//save NETPAY to payroll_paystub_entry table
			clsMnge_PG::doSavePayStubEntry($paystub_id,4,$grossPay);
			clsMnge_PG::doSavePayStubEntry($paystub_id,2,$SumAllDeduction);    	//save Total Deduction to payroll_paystub_entry table
			clsMnge_PG::doSavePayStubEntry($paystub_id,25,$SumSABEarning);		//save Total S Earning to payroll_paystub_entry table
			clsMnge_PG::doSavePayStubEntry($paystub_id,26,$SumTSABEarning);	//save Total TS Earning to payroll_paystub_entry table
			clsMnge_PG::doSavePayStubEntry($paystub_id,27,$statIncome);
			clsMnge_PG::doSavePayStubEntry($paystub_id,28,$SumTABEarning);		//save Total T Earning to payroll_paystub_entry table
			clsMnge_PG::doSavePayStubEntry($paystub_id,29,$SumTABDeduction);	//save Total T Deduction to payroll_paystub_entry table
			clsMnge_PG::doSavePayStubEntry($paystub_id,33,$SumSABDeduction);	//save Total S Deduction to payroll_paystub_entry table
			clsMnge_PG::doSavePayStubEntry($paystub_id,34,$SumTSABDeduction);	//save Total ST Deduction to payroll_paystub_entry table
			clsMnge_PG::doSavePayStubEntry($paystub_id,31,$grossandnontaxable);//save Total Non TS Earning to payroll_paystub_entry table
			clsMnge_PG::doSavePayStubEntry($paystub_id,32,$otherdeducNtax);		//save Total Non TS Deduction to payroll_paystub_entry table
			clsMnge_PG::doSavePayStubEntry($paystub_id,39, $rsResult->fields['paystubdetails']['ytd_summary']['COLA']);        //save Total COLA computed base to salary type and pay group to payroll_paystub_entry table
			clsMnge_PG::doSavePayStubEntry($paystub_id,16,$rsResult->fields['paystubdetails']['ytd_summary']['OTAmount']);
			$this->saveTARec($paystub_id,$payperiod_id_,$rsResult->fields['emp_id'],4,$rsResult->fields['paystubdetails']['ytd_summary']['LeaveDedAmount']);
			// save statutory contributions
			// SSS
			$SSSwage = Number_Format($rsResult->fields['paystubdetails']['ytd_summary']['SSSWage'],$objClsMngeDecimal->getFinalDecimalSettings(),'.','');
			$SSSee = Number_Format($rsResult->fields['paystubdetails']['ytd_summary']['SSSee'],$objClsMngeDecimal->getFinalDecimalSettings(),'.','');
			$SSSer = Number_Format($rsResult->fields['paystubdetails']['ytd_summary']['SSSer'],$objClsMngeDecimal->getFinalDecimalSettings(),'.','');
			$SSSec = Number_Format($rsResult->fields['paystubdetails']['ytd_summary']['SSSec'],$objClsMngeDecimal->getFinalDecimalSettings(),'.','');
			clsMnge_PG::doSavePayStubEntry($paystub_id, 7, $SSSee, $SSSer, $SSSwage,$SSSec);
			
			// PHIC
			$PHICWage = Number_Format($rsResult->fields['paystubdetails']['ytd_summary']['PHICWage'],$objClsMngeDecimal->getFinalDecimalSettings(),'.','');
			$PHICee = Number_Format($rsResult->fields['paystubdetails']['ytd_summary']['PHICee'],$objClsMngeDecimal->getFinalDecimalSettings(),'.','');
			$PHICer = Number_Format($rsResult->fields['paystubdetails']['ytd_summary']['PHICer'],$objClsMngeDecimal->getFinalDecimalSettings(),'.','');
			clsMnge_PG::doSavePayStubEntry($paystub_id, 14, $PHICee,$PHICer, $PHICWage,0);
			
			// HDMF
			$HDMFWage = Number_Format($rsResult->fields['paystubdetails']['ytd_summary']['HDMFWage'],$objClsMngeDecimal->getFinalDecimalSettings(),'.','');
			$HDMFee = Number_Format($rsResult->fields['paystubdetails']['ytd_summary']['HDMFee'],$objClsMngeDecimal->getFinalDecimalSettings(),'.','');
			$HDMFer = Number_Format($rsResult->fields['paystubdetails']['ytd_summary']['HDMFer'],$objClsMngeDecimal->getFinalDecimalSettings(),'.','');
			clsMnge_PG::doSavePayStubEntry($paystub_id, 15, $HDMFee,$HDMFer, $HDMFWage,0);
			
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
							 "basic" => $rsResult->fields['paystubdetails']['ytd_summary']['basicSalaryRate']
							,"Regulartime" => $rsResult->fields['paystubdetails']['ytd_summary']['basicPGRate']
							,"COLA" => $rsResult->fields['paystubdetails']['ytd_summary']['COLA']
							,"COLAperDay" => "0"
							,"totalDays" => "0"
							,"MWR" => "0"
							,"DailyRate" => "0"
							,"HourlyRate" => "0"
							,"OT" => array(
										"TotalallOT" => $rsResult->fields['paystubdetails']['ytd_summary']['OTAmount']
									)
						)
					,"deduction" => array(
						"SSS" => $rsResult->fields['paystubdetails']['ytd_summary']['SSSee']
						,"SSSER" => $rsResult->fields['paystubdetails']['ytd_summary']['SSSer']
                        ,"SSSEC" => $rsResult->fields['paystubdetails']['ytd_summary']['SSSec']
                        ,"PhilHealth" => $rsResult->fields['paystubdetails']['ytd_summary']['PHICee']
                        ,"PhilHealthER" => $rsResult->fields['paystubdetails']['ytd_summary']['PHICer']
                        ,"Pag-ibig" => $rsResult->fields['paystubdetails']['ytd_summary']['HDMFee']
                        ,"Pag-ibigER" => $rsResult->fields['paystubdetails']['ytd_summary']['HDMFer']
                        ,"Others" => ''
					)
					,"TUA" => array(
						"TotalLeave" => $rsResult->fields['paystubdetails']['ytd_summary']['LeaveDedAmount'])
					,"amendments" => array(
							$amendments
						)
					,"pstotal" => array(
						 	 "gross" => Number_Format($rsResult->fields['paystubdetails']['ytd_summary']['grossPay'],$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
							,"Basic Salary" => Number_Format("0",$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"PGsalary" => Number_Format($rsResult->fields['paystubdetails']['ytd_summary']['grossPay'],$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"gross_nontaxable_income" => Number_Format($grossandnontaxable,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"taxable_Gross" => Number_Format($rsResult->fields['paystubdetails']['ytd_summary']['taxableIncomeGross'],$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"Deduction" => Number_Format($SumAllDeduction,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"SatutoryDeduction" => Number_Format($rsResult->fields['paystubdetails']['ytd_summary']['statIncome'],$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"W/H Tax" => Number_Format($rsResult->fields['paystubdetails']['ytd_summary']['tax'],$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"aftertaxgross" => Number_Format($afterTaxGross,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"Net Pay" => Number_Format($rsResult->fields['paystubdetails']['ytd_summary']['netPay'],$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
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
		//printa($arrPayStub); exit;
		clsMnge_PG::doSavePayStubArr($arrPayStub);
        if($emp_id_ == ""){
            return $retval;
        }else{
            return $arrPayStub;
        }		
    }
    
    function getAmendments($start_date_ = null, $end_date_ = null){
    	$qry[] = "psamend_effect_date >= '$start_date_'";
    	$qry[] = "psamend_effect_date <= '$end_date_'";
    	if(!empty($psa_id)){
    		$qry[] = "psa_id = ";
    	}
    	$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";
    	$sql = "select psa_id from payroll_ps_amendment $criteria";
    	
    	$rsResult = $this->conn->Execute($sql);
    	$arr = array();
    	while (!$rsResult->EOF) {
			$arr[] = $rsResult->fields['psa_id'];
			$rsResult->MoveNext();
    	}
    	return $arr;
    }
    
    function doSavePEasAmendment($pe_arr = array(), $effective_date = null, $gData = array()){
    	foreach($pe_arr as $key => $value){
    		$sql = "INSERT INTO payroll_ps_amendment SET 
    				pps_id='".$gData['ppsched']."',
    				psa_id='".$value."',
    				psamend_name='".$this->getPEName($value)."',
    				psamend_status='2',
    				psamend_effect_date='".$effective_date."',
    				psamend_desc='For YTD Use'";
    		$rsResult = $this->conn->Execute($sql);
    	}
    }
    
    function getPEName($psa_id){
    	$sql = "select psa_name from payroll_ps_account where psa_id=".$psa_id;
    	$rsResult = $this->conn->Execute($sql);
    	if (!$rsResult->EOF) {
    		return $rsResult->fields['psa_name'];
    	}
    }
    function doSaveAndGenerateAmendmentsArray($data = array(), $paystub_id_ = null){
    	//printa($data);
    	$returnArr = array();
    	if(count($data['paystubdetails']['Earnings']) > 0){
	    	for($count = 0;$count<count($data['paystubdetails']['Earnings']);$count++){
	    		$amm = $this->getAmendmentID($data['startDate'],$data['endDate'],$data['paystubdetails']['Earnings'][$count]['psa_id']);
    			//printa($amm);
    			if($this->checkIfAmendExists($data['emp_id'],$paystub_id_,$amm[0]['psamend_id'])){
		    		$sql = "UPDATE payroll_ps_amendemp SET
		    				amendemp_amount='".$data['paystubdetails']['Earnings'][$count]['ben_payperday']."'
		    				WHERE amendemp_id='".$this->checkIfAmendExists($data['emp_id'],$paystub_id_,$amm[0]['psamend_id'])."'";
    			} else {
    				$sql = "INSERT INTO payroll_ps_amendemp SET 
		    				emp_id='".$data['emp_id']."',
		    				paystub_id='".$paystub_id_."',
		    				psamend_id='".$amm[0]['psamend_id']."',
		    				amendemp_amount='".$data['paystubdetails']['Earnings'][$count]['ben_payperday']."',
		    				amendemp_addwho='".AppUser::getData('user_name')."'";
    			}
		    	$rsResult = $this->conn->Execute($sql);
		    	$amendData = $this->getAmendData($data['emp_id'],$paystub_id_,$amm[0]['psamend_id']);
		    	
		    	$returnArr[] = array(
		    						"psa_id" => $amendData['psa_id']
                                    ,"psamend_id" => $amendData['psamend_id']
                                    ,"amendemp_id" => $amendData['amendemp_id']
                                    ,"psa_name" => $amendData['psa_name']
                                    ,"psamend_desc" => $amendData['psamend_desc']
                                    ,"psa_type" => $amendData['psa_type']
                                    ,"psamend_rate" => $amendData['psamend_rate']
                                    ,"psamend_units" => $amendData['psamend_units']
                                    ,"psamend_amount" => $amendData['psamend_amount']
                                    ,"psamend_effect_date" => $amendData['psamend_effect_date']
                                    ,"psamend_istaxable" => $amendData['psamend_istaxable']
                                    ,"psa_tax" => $amendData['psa_tax']
                                    ,"psa_statutory" => $amendData['psa_statutory']
                                    ,"amendemp_amount" => $amendData['amendemp_amount']
                                );
	    		//printa($data['paystubdetails']['Earnings'][$count]);
	    	}
    	}
    	if(count($data['paystubdetails']['Deductions']) > 0){
	    	for($count = 0;$count<count($data['paystubdetails']['Deductions']);$count++){
	    		$amm = $this->getAmendmentID($data['startDate'],$data['endDate'],$data['paystubdetails']['Deductions'][$count]['psa_id']);
    			//printa($amm);
	    		if($this->checkIfAmendExists($data['emp_id'],$paystub_id_,$amm[0]['psamend_id'])){
		    		$sql = "UPDATE payroll_ps_amendemp SET
		    				amendemp_amount='".$data['paystubdetails']['Deductions'][$count]['ben_payperday']."'
		    				WHERE amendemp_id='".$this->checkIfAmendExists($data['emp_id'],$paystub_id_,$amm[0]['psamend_id'])."'";
    			} else {
	    		$sql = "INSERT INTO payroll_ps_amendemp SET 
	    				emp_id='".$data['emp_id']."',
	    				paystub_id='".$paystub_id_."',
	    				psamend_id='".$amm[0]['psamend_id']."',
	    				amendemp_amount='".$data['paystubdetails']['Deductions'][$count]['ben_payperday']."',
	    				amendemp_addwho='".AppUser::getData('user_name')."'";
    			}
	    		$rsResult = $this->conn->Execute($sql);
	    		$amendData = $this->getAmendData($data['emp_id'],$paystub_id_,$amm[0]['psamend_id']);
		    	
		    	$returnArr[] = array(
		    						"psa_id" => $amendData['psa_id']
                                    ,"psamend_id" => $amendData['psamend_id']
                                    ,"amendemp_id" => $amendData['amendemp_id']
                                    ,"psa_name" => $amendData['psa_name']
                                    ,"psamend_desc" => $amendData['psamend_desc']
                                    ,"psa_type" => $amendData['psa_type']
                                    ,"psamend_rate" => $amendData['psamend_rate']
                                    ,"psamend_units" => $amendData['psamend_units']
                                    ,"psamend_amount" => $amendData['psamend_amount']
                                    ,"psamend_effect_date" => $amendData['psamend_effect_date']
                                    ,"psamend_istaxable" => $amendData['psamend_istaxable']
                                    ,"psa_tax" => $amendData['psa_tax']
                                    ,"psa_statutory" => $amendData['psa_statutory']
                                    ,"amendemp_amount" => $amendData['amendemp_amount']
                                );
	    	}
    	}
    	return $returnArr;
    }
    
 	function getAmendmentID($start_date_ = null, $end_date_ = null, $psa_id_ = null){
    	$qry[] = "psamend_effect_date >= '$start_date_'";
    	$qry[] = "psamend_effect_date <= '$end_date_'";
    	$qry[] = "psa_id = '$psa_id_'";
    	$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";
    	$sql = "select * from payroll_ps_amendment $criteria";
    	
    	$rsResult = $this->conn->Execute($sql);
    	$arr = array();
    	while (!$rsResult->EOF) {
			$arr[] = $rsResult->fields;
			$rsResult->MoveNext();
    	}
    	return $arr;
    }
    
    function checkIfAmendExists($emp_id_ = null, $paystub_id_ = null, $psamend_id_ = null){
    	$sql = "select amendemp_id from payroll_ps_amendemp where emp_id='$emp_id_' and paystub_id='$paystub_id_' and psamend_id='$psamend_id_'";
    	$rsResult = $this->conn->Execute($sql);
    	if(!$rsResult->EOF){
    		return $rsResult->fields['amendemp_id'];
    	} else {
    		return false;
    	}
    }
    
    function getAmendData($emp_id_ = null, $paystub_id_ = null, $psamend_id_ = null){
    	$sql = "select * from payroll_ps_amendemp a 
    			join payroll_ps_amendment b on (b.psamend_id=a.psamend_id) 
    			join payroll_ps_account c on (c.psa_id=b.psa_id) 
    			where a.emp_id='$emp_id_' 
    			and a.paystub_id='$paystub_id_' 
    			and a.psamend_id='$psamend_id_'";
    	$rsResult = $this->conn->Execute($sql);
    	if(!$rsResult->EOF){
    		return $rsResult->fields;
    	}
    }
    
    function saveTARec($paystub_id_ = null, $payperiod_id_ = null, $emp_id_ = null, $tatbl_id_ = null, $emp_tarec_amtperrate_ = null){
    	$qry[] = "paystub_id = '$paystub_id_'";
    	$qry[] = "payperiod_id = '$payperiod_id_'";
    	$qry[] = "emp_id = '$emp_id_'";
    	$qry[] = "tatbl_id = '$tatbl_id_'";
    	$qry[] = "emp_tarec_amtperrate = '$emp_tarec_amtperrate_'";
    	$criteria = (count($qry)>0)?" set ".implode(",",$qry):"";
    	$sql = "insert into ta_emp_rec $criteria";
    	$rsResult = $this->conn->Execute($sql);
    }
}
?>