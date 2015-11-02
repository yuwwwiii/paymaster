<?php
/**
 * Initial Declaration
 */


/**
 * Class Module
 * @author  JIM
 */
class clsPopup_PayDetails {
	var $conn;
	var $fieldMap;
	var $Data;

	/**
	 * Class Constructor
	 * @param object $dbconn_
	 * @return clsPopup_PayDetails object
	 */
	function clsPopup_PayDetails ($dbconn_ = null) {
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
				$qry[] = "payperiod_trans_date like '%$search_field%' || pps_name like '%$search_field%'";
			}
		}
		$listpgroup = $_SESSION[admin_session_obj][user_paygroup_list2];
		IF(count($listpgroup)>0){
			$qry[] = "psar.pps_id in (".$listpgroup.")";//pay group that can access
		}
		$qry[] = "payperiod_type IN (1,3,4)";
		$qry[] = "pp_stat_id=3";
		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		 "viewdata"=>"viewdata"
		,"period_name" => "period_name"
		,"salaryclass_id" => "salaryclass_id"
		,"pp_stat_id" => "pp_stat_id"
		,"payperiod_start_date" => "payperiod_start_date"
		,"payperiod_end_date" => "payperiod_end_date"
		,"payperiod_trans_date" => "payperiod_trans_date"
		);
		if(isset($_GET['sortby'])){
			$strOrderBy = " GROUP BY ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof']." , ".payperiod_id;
		}else{
			$strOrderBy = " GROUP BY ".payperiod_id." DESC";
		}

		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
//		$viewLink = "<a href=\"?statpos=payroll_details&edit=',psar.pps_id,'&ppsched_view=',ppp.payperiod_id,'\">',psar.pps_name,'</a>";
		$popupLink = "<a href=\"javascript:void(0);\" onclick=\"window.parent.document.getElementById(\'pps_id\').value=\'',psar.pps_id,'\';
						window.parent.document.getElementById(\'payperiod_id\').value=\'',ppp.payperiod_id,'\';
						window.parent.document.getElementById(\'pps_name\').value=\'',psar.pps_name,'\';
						window.parent.document.getElementById(\'payperiod_start_date\').value=\'',payperiod_start_date,'\';
						window.parent.document.getElementById(\'payperiod_end_date\').value=\'',payperiod_end_date,'\';
						window.parent.document.getElementById(\'payperiod_trans_date\').value=\'',payperiod_trans_date,'\';
						parent.$.fancybox.close();\"><img src=\"".SYSCONFIG_THEME_URLPATH.SYSCONFIG_THEME."/images/admin/accept.gif\" title=\"Select\" hspace=\"2px\" border=0></a>";
		
		// SqlAll Query
		$sql = "SELECT ppp.*, CONCAT('$popupLink') as viewdata,
				IFNULL(NULLIF(ppp.payperiod_name,''),psar.pps_name) as period_name,
				DATE_FORMAT(payperiod_start_date,'%d %b %Y') as payperiod_start_date,
				DATE_FORMAT(payperiod_end_date,'%d %b %Y') as payperiod_end_date,
				DATE_FORMAT(payperiod_trans_date,'%d %b %Y') as payperiod_trans_date,psar.pps_name, 
				if(salaryclass_id='1','Daily',IF(salaryclass_id='2','Weekly',IF(salaryclass_id='3','Bi-Weekly',IF(salaryclass_id='4','Semi-monthly',IF(salaryclass_id='5','Monthly','Annual'))))) as salaryclass_id,
				IF(pp_stat_id='1','OPEN',IF(pp_stat_id='2','Locked - Pending Approval',IF(pp_stat_id='3','CLOSED','Post Adjustment'))) as pp_stat_id
						FROM payroll_paystub_report ppr
						JOIN payroll_pay_period ppp on (ppr.payperiod_id=ppp.payperiod_id)
						JOIN payroll_pay_period_sched psar on (psar.pps_id=ppp.pps_id)
						$criteria
						$strOrderBy";
		// Field and Table Header Mapping
		$arrFields = array(
		 "viewdata" => "Action"
		,"period_name" => "Name" 
		,"salaryclass_id" => "Type"
		,"pp_stat_id" => "Status"
		,"payperiod_start_date" => "Start"
		,"payperiod_end_date" => "End"
		,"payperiod_trans_date" => "Pay Date"
		);
		// Column (table data) User Defined Attributes
		$arrAttribs = array(
		"mnu_ord" => "align='center'",
		"pps_name" => "width='150'",
		"salaryclass_name" => "width='120'",
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
}
?>