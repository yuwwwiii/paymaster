<?php
/**
 * Initial Declaration
 */

/**
 * Class Module
 * @author  JIM
 */
class clsMyPayslip{
	var $conn;
	var $fieldMap;
	var $Data;

	/**
	 * Class Constructor
	 * @param object $dbconn_
	 * @return clsMyPayslip object
	 */
	function clsMyPayslip($dbconn_ = null){
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
	 * Populate array parameters to Data Variable
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
	 * @param array $pData_
	 * @return bool
	 */
	function doValidateData($pData_ = array()){
		$isValid = true;
//		$isValid = false;
		return $isValid;
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
				$qry[] = "mnu_name like '%$search_field%'";
			}
		}
		$emp_id_ = $_SESSION['empNumber'];
		$qry[]="ppr.emp_id='".$emp_id_."'";
		$qry[]="ppp.pp_stat_id='3'";
		$qry[]="ppp.payperiod_type!='2'";
		$qry[]="ppp.is_payslip_viewable='1'";
		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" WHERE ".implode(" AND ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		 "viewdata"=>"viewdata"
		,"pps_name"=>"pps_name"
		,"paysched"=>"paysched"
		,"payperiod_trans_date"=>"payperiod_trans_date"
		,"pperiod" => "pperiod"
		,"salaryclass_id" => "salaryclass_id"
		,"pp_stat_id" => "pp_stat_id"
		);

		if(isset($_GET['sortby'])){
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}

		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$viewLink = "<a href=\"?statpos=mypayslip&pdfrep=',ppr.ppr_id,'\" target=\"_blank\"><img src=\"".SYSCONFIG_THEME_URLPATH.SYSCONFIG_THEME."/images/pdf2.png\" title=\"Payslip PDF View\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";

		// SqlAll Query
		$sql = "SELECT ppp.*, CONCAT('$viewLink') as viewdata, psar.pps_name,
				CONCAT(UPPER(date_format(payperiod_start_date,'%b %d')),' to ',UPPER(date_format(payperiod_end_date,'%b %d, %Y'))) as paysched,
                DATE_FORMAT(payperiod_start_date,'%Y-%m-%d') as payperiod_start_date,
                DATE_FORMAT(payperiod_end_date,'%Y-%m-%d') as payperiod_end_date,
                UPPER(DATE_FORMAT(payperiod_trans_date,'%M %d, %Y')) as payperiod_trans_date,
				IF(salaryclass_id='1','Daily',IF(salaryclass_id='2','Weekly',IF(salaryclass_id='3','Bi-Weekly',IF(salaryclass_id='4','Semi-monthly',IF(salaryclass_id='5','Monthly','Annual'))))) as salaryclass_id,
				IF(pp_stat_id='1','OPEN',IF(pp_stat_id='2','Locked - Pending Approval',IF(pp_stat_id='3','CLOSED','Post Adjustment'))) as pp_stat_id,
				IF(ppp.payperiod_type='2','YTD',IF(ppp.payperiod_type='3','Bonus',IF(ppp.payperiod_type='4','Others','Normal'))) as classification,
				IF(ppp.payperiod_freq='1','1st',IF(ppp.payperiod_freq='2','2nd',IF(ppp.payperiod_freq='3','3rd',IF(ppp.payperiod_freq='4','4th',IF(ppp.payperiod_freq='5','5th','All'))))) as pperiod
					FROM payroll_paystub_report ppr
					JOIN payroll_pay_period ppp on (ppr.payperiod_id=ppp.payperiod_id)
					JOIN payroll_pay_period_sched psar on (psar.pps_id=ppp.pps_id)
					$criteria
					$strOrderBy";

		// Field and Table Header Mapping
		$arrFields = array(
		 "viewdata" => "Action"
		,"pps_name"=>"Pay Period"
		,"paysched"=>"Cut-offs"
		,"payperiod_trans_date"=>"Pay Date"
		,"pperiod" => "Period"
		,"salaryclass_id" => "Type"
		,"classification" => "Payroll Type"
		,"pp_stat_id" => "Status"
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
		return $tblDisplayList->getTableList($arrAttribs);
	}
	
	function checkPayslips($emp_id_ = null){
		$arr = array();
		$sql = "SELECT ppr.ppr_id 
				FROM payroll_paystub_report ppr 
				JOIN payroll_pay_period ppp on (ppr.payperiod_id=ppp.payperiod_id) 
				JOIN payroll_pay_period_sched psar on (psar.pps_id=ppp.pps_id) 
				WHERE ppr.emp_id='$emp_id_' AND ppp.pp_stat_id='3' AND ppp.payperiod_type!='2' AND ppp.is_payslip_viewable='1'";
		$rsResult = $this->conn->Execute($sql);
    	while(!$rsResult->EOF){
    		$arr[] = $rsResult->fields['ppr_id'];
			$rsResult->MoveNext();
    	}
		return $arr;
	}
}
?>