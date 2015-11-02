<?php /**
 * Initial Declaration
 */
require_once(SYSCONFIG_CLASS_PATH."util/PHPExcel.php");
require_once(SYSCONFIG_CLASS_PATH."util/PHPExcel/IOFactory.php");
$options = array(
	"1" => "By Pay Period"
	,"2" => "By Month"
);
/**
 * Class Module
 * @author  IRS
 */
class clsCustomPayDetails{
	var $conn;
	var $fieldMap;
	var $Data;
	public $empInfo = array(
		 "pi_lname" => "Last Name"
		,"pi_fname" => "First Name"
		,"pi_mname" => "Middle Name"
		,"emp_idnum" => "Employee ID Number"
		,"emp_hiredate" => "Hire Date"
		,"taxep_name" => "Tax Exemption"
		,"post_name" => "Position"
		,"ud_name" => "Department"
		,"pi_gender" => "Gender"
		,"pi_bdate" => "Birthdate"
		,"pi_gender" => "Gender"
		,"pi_add" => "Address"
		,"pi_emailone" => "Email Address"
		,"pi_tin" => "TIN"
		,"pi_sss" => "SSS Number"
		,"pi_phic" => "PHIC Number"
		,"pi_hdmf" => "HDMF Number"
	);
	public $month = array(
	 "01" => "January"
	,"02" => "Febuary"
	,"03" => "March"
	,"04" => "April"
	,"05" => "May"
	,"06" => "June"
	,"07" => "July"
	,"08" => "August"
	,"09" => "September"
	,"10" => "October"
	,"11" => "November"
	,"12" => "December"
);
	/**
	 * Class Constructor
	 * @param object $dbconn_
	 * @return clsCustomPayDetails object
	 */
	function clsCustomPayDetails($dbconn_ = null){
		$this->conn =& $dbconn_;
		$this->fieldMap = array(
		 "custom_report_name" => "report_name"
		,"custom_report_type" => "type"
		,"custom_report_empinfo" => "emp_info_selected_list"
		,"custom_report_pay_elements" => "selected_list"
		);
	}

	/**
	 * Get the records from the database
	 * @param string $id_
	 * @return array
	 */
	function dbFetch($id_ = ""){
		$sql = "select *,IF(custom_report_type='1','By Pay Period','By Month') as report_type from custom_detail_report where custom_report_id=?";
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
				}else {
					$this->Data[$key] = $pData_[$value];
				}
				if($key=='custom_report_empinfo' || $key=='custom_report_pay_elements'){
					$this->Data[$key] = serialize($pData_[$value]);
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
		IF(empty($pData_['report_name'])){
			$isValid = false;
			$_SESSION['eMsg'][] = "Please enter report name.";
		}
		IF(strlen($pData_['report_name'])>31){
			$isValid = false;
			$_SESSION['eMsg'][] = "Maximum characters for report name is 31.";
		}
		IF(!isset($pData_['emp_info_selected_list']) AND !isset($pData_['selected_list'])){
			$isValid = false;
			$_SESSION['eMsg'][] = "Please select fields to show in the report.";
		}
		
//		$isValid = false;
		return $isValid;
	}

	/**
	 * Save New Record
	 */
	function doSaveAdd(){
		$flds = array();
		foreach ($this->Data as $keyData => $valData) {
			$valData = addslashes($valData);
			$flds[] = "$keyData='$valData'";
		}
		$fields = implode(", ",$flds);
		$sql = "INSERT INTO custom_detail_report SET $fields";
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
		$sql = "UPDATE /*custom_detail_report*/ SET $fields WHERE mnu_id=$id";
		$this->conn->Execute($sql);
		$_SESSION['eMsg']="Successfully Updated.";
	}

	/**
	 * Delete Record
	 * @param string $id_
	 */
	function doDelete($id_ = ""){
		$sql = "DELETE FROM custom_detail_report WHERE custom_report_id=?";
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
				$qry[] = "custom_report_name like '%$search_field%'";
			}
		}

		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" WHERE ".implode(" AND ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		 "viewdata"=>"viewdata"
		,"custom_report_name"=>"custom_report_name"
		,"report_type"=>"report_type"
		);

		if(isset($_GET['sortby'])){
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}

		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		/**$viewLink = "";
		$delLink = "<a href=\"?statpos=custom_paydetails&delete=',am.mnu_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\"  border=0 width=\"16\" height=\"16\"></a>";
		**/
		$delLink = "<a href=\"?statpos=custom_paydetails&delete=',custom_report_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\"  border=0 width=\"16\" height=\"16\"></a>";
		$editLink = "<a href=\"?statpos=custom_paydetails&action=edit&custom_report_id=',am.custom_report_id,'\"><img src=\"".SYSCONFIG_THEME_URLPATH.SYSCONFIG_THEME."/images/admin/zoom.gif\" title=\"Edit\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
		$action = "<a href=\"?statpos=custom_paydetails&action=add\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/add.png\" title=\"Add New\" border=0 width=\"16\" height=\"16\"></a>";
		
		// SqlAll Query
		$sql = "SELECT am.*, CONCAT('$viewLink','$editLink','$delLink') as viewdata,
						IF(am.custom_report_type='1','By Pay Period','By Month') as report_type
						FROM custom_detail_report am
						$criteria
						$strOrderBy";

		// Field and Table Header Mapping
		$arrFields = array(
		 "viewdata"=>$action
		,"custom_report_name"=>"Custom Report Name"
		,"report_type"=>"Custom Report Type"
		);

		// Column (table data) User Defined Attributes
		$arrAttribs = array(
		"mnu_ord"=>" align='center'",
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
	
	function getPayElementList(){
		$ret = array();
		$sql = "select a.psa_id, a.psa_name from payroll_ps_account a
				join payroll_paystub_entry b on (b.psa_id=a.psa_id)
				order by psa_name";
		$rsResult =$this->conn->Execute($sql);
		while(!$rsResult->EOF){
			$ret[$rsResult->fields['psa_id']] = $rsResult->fields['psa_name'];
			$rsResult->MoveNext();
		}
		
		$sql = "select a.psa_id, a.psa_name from payroll_ps_account a
				join loan_info c on (c.psa_id=a.psa_id) 
				order by psa_name";
		$rsResult =$this->conn->Execute($sql);
		while(!$rsResult->EOF){
			$ret[$rsResult->fields['psa_id']] = $rsResult->fields['psa_name'];
			$rsResult->MoveNext();
		}
		
		$sql = "select a.psa_id, a.psa_name from payroll_ps_account a
				join payroll_ps_amendment d on (d.psa_id=a.psa_id)
				order by psa_name";
		$rsResult =$this->conn->Execute($sql);
		while(!$rsResult->EOF){
			$ret[$rsResult->fields['psa_id']] = $rsResult->fields['psa_name'];
			$rsResult->MoveNext();
		}
		asort($ret,SORT_REGULAR);
		return $ret;
	}
	
	function getPayElementName($psa_id_ = null){
		$sql = "select psa_name from payroll_ps_account where psa_id = '$psa_id_'";
		$rsResult =$this->conn->Execute($sql);
		IF(!$rsResult->EOF){
			return $rsResult->fields['psa_name'];
		}
	}
	
	function getPayPeriod(){
		$sql = "select am.payperiod_id, IFNULL(NULLIF(am.payperiod_name,''),CONCAT(b.pps_name,' - ',date_format(am.payperiod_trans_date,'%b %d, %Y'))) pname
						FROM payroll_pay_period am
                        JOIN payroll_pay_period_sched b on (b.pps_id = am.pps_id)";
		$rsResult =$this->conn->Execute($sql);
		while(!$rsResult->EOF){
			$ret[$rsResult->fields['payperiod_id']] = $rsResult->fields['pname'];
			$rsResult->MoveNext();
		}
		return $ret;
	}
	
	function getPayPeriodById($id_ = null){
		IF($id_ == null) return false;
		$sql = "select am.payperiod_id, IFNULL(NULLIF(am.payperiod_name,''),CONCAT(b.pps_name,' - ',date_format(am.payperiod_trans_date,'%b %d, %Y'))) pname
						FROM payroll_pay_period am
                        JOIN payroll_pay_period_sched b on (b.pps_id = am.pps_id)
                        WHERE am.payperiod_id=?";
		$rsResult =$this->conn->Execute($sql,array($id_));
		IF(!$rsResult->EOF){
			return $rsResult->fields;
		}
	}
	function getReportType($id_ = null){
		IF($id_ == null) return false;
		$sql = "select * from custom_detail_report where custom_report_id='$id_'";
		$rsResult =$this->conn->Execute($sql);
		IF(!$rsResult->EOF){
			return $rsResult->fields;
		}
	}
	
	function getInfo($fields_ = array(), $where_ = array(), $type_ = null){
		IF(count($fields_)==0) return false;
		IF(count($where_)==0) return false;
		IF($type_==null) return false;
		$arr = array();
		$select = $where_['select'];
		$year = $where_['year'];
		IF($type_ == 1){
			$where[] = "g.payperiod_id='$select'";
		} ELSE {
			$where[] = "g.payperiod_period='$select'";
			$where[] = "g.payperiod_period_year='$year'";
		}
		$criteria = "WHERE ".implode(" AND ",$where);
		$flds = implode(", ",$fields_);
		$strOrder = " ORDER BY b.pi_lname,b.pi_fname,b.pi_mname ASC";
		$sql = "SELECT distinct a.emp_id, $flds FROM emp_masterfile a
				JOIN emp_personal_info b ON (b.pi_id=a.pi_id)
				LEFT JOIN tax_excep c ON (c.taxep_id=a.taxep_id)
				LEFT JOIN emp_position d ON (d.post_id=a.post_id)
				LEFT JOIN app_userdept e ON (e.ud_id=a.ud_id)
				JOIN payroll_paystub_report f ON (f.emp_id=a.emp_id)
				JOIN payroll_pay_period g ON (g.payperiod_id=f.payperiod_id)
				$criteria
				$strOrder";
		$rsResult =$this->conn->Execute($sql);
		while(!$rsResult->EOF){
			$arr[] = $rsResult->fields;
			$rsResult->MoveNext();
		}
		return $arr;
	}
	
	function getPayElementsValue($emp_id_ = null, $psa_id_ = null, $payperiod_id_ = null, $month_ = null, $year_ = null){
		IF($emp_id_==null) return false;
		IF($psa_id_==null) return false;
		IF($payperiod_id_ != null){
			$period = $this->getMonthAndYear($payperiod_id_);
			$month_ = $period['payperiod_period'];
			$year_ = $period['payperiod_period_year'];
		}
		$sql = "select loan+amm+entry as total FROM
		
				(select COALESCE(sum(loansum_payment),0) as loan from loan_detail_sum a
					inner join loan_info b on (b.loan_id=a.loan_id)
					inner join payroll_pay_stub c on (c.paystub_id=a.paystub_id)
					inner join payroll_pay_period d on (d.payperiod_id=c.payperiod_id)
					where b.psa_id='$psa_id_' 
					and b.emp_id='$emp_id_' 
					and d.payperiod_period = $month_
					and d.payperiod_period_year='$year_') loan_tbl,
					
				(select COALESCE(sum(amendemp_amount),0) as amm from payroll_ps_amendemp a
					inner join payroll_ps_amendment b on (b.psamend_id=a.psamend_id)
					inner join payroll_pay_stub c on (c.paystub_id=a.paystub_id)
					inner join payroll_pay_period d on (d.payperiod_id=c.payperiod_id)
					where b.psa_id='$psa_id_'
					and a.emp_id='$emp_id_'
					and d.payperiod_period = $month_
					and d.payperiod_period_year='$year_') amm_tbl,
					
				(select COALESCE(sum(ppe_amount),0) as entry from payroll_paystub_entry a
					inner join payroll_pay_stub b on (b.paystub_id=a.paystub_id)
					inner join payroll_paystub_report c on (c.paystub_id=b.paystub_id)
					inner join payroll_pay_period d on (d.payperiod_id=c.payperiod_id)
					where a.psa_id='$psa_id_' 
					and c.emp_id='$emp_id_' 
					and d.payperiod_period = $month_
					and d.payperiod_period_year='$year_') entry_tbl";
		$rsResult = $this->conn->Execute($sql);
		IF(!$rsResult->EOF){
			RETURN $rsResult->fields['total'];
		} ELSE {
			RETURN 0.00;
		}
	}
	
	function getMonthAndYear($payperiod_id_ = null){
		IF($payperiod_id_ == null) return FALSE;
		$sql = "select payperiod_period,payperiod_period_year FROM payroll_pay_period WHERE payperiod_id=?";
		$rsResult = $this->conn->Execute($sql,array($payperiod_id_));
		IF(!$rsResult->EOF){
			RETURN $rsResult->fields;
		}
	}
}
?>