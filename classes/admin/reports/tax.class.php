<?php
require_once(SYSCONFIG_CLASS_PATH."util/dompdf/dompdf_config.inc.php");
require_once(SYSCONFIG_CLASS_PATH.'admin/reports/bir_alphalist.class.php');
require_once(SYSCONFIG_CLASS_PATH.'admin/application.class.php');
require_once(SYSCONFIG_CLASS_PATH."util/pdf.class.php");
/**
 * Initial Declaration
 */
$month = array(
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

$year = array(
	date('Y') => date('Y'),
	date('Y')-1 => date('Y')-1,
	date('Y')-2 => date('Y')-2,
	date('Y')-3 => date('Y')-3,
	date('Y')-4 => date('Y')-4,
	date('Y')-5 => date('Y')-5,
	date('Y')-6 => date('Y')-6,
	date('Y')-7 => date('Y')-7,
);

/**
 * Class Module
 *
 * @author  Jason I. Mabignay
 *
 */
class clsTAX extends clsBIRAlphalist{

	var $conn;
	var $fieldMap;
	var $Data;

	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsTAX object
	 */
	function clsTAX($dbconn_ = null){
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
				$qry[] = "mnu_name like '%$search_field%'";

			}
		}

		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		 "viewdata"=>"viewdata"
		,"mnu_name"=>"mnu_name"
		,"mnu_link"=>"mnu_link"
		,"mnu_ord"=>"mnu_ord"
		);

		if(isset($_GET['sortby'])){
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}

		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$viewLink = "";
		$editLink = "<a href=\"?statpos=tax&edit=',am.mnu_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/edit.png\" title=\"Edit\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
		$delLink = "<a href=\"?statpos=tax&delete=',am.mnu_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\"  border=0 width=\"16\" height=\"16\"></a>";

		// SqlAll Query
		$sql = "select am.*, CONCAT('$viewLink','$editLink','$delLink') as viewdata
						from app_modules am
						$criteria
						$strOrderBy";

		// Field and Table Header Mapping
		$arrFields = array(
		 "viewdata"=>"Action"
		,"mnu_name"=>"Module Name"
		,"mnu_link"=>"Link"
		,"mnu_ord"=>"Order"
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
	
    function dbfetchCompDetails($comp_id_ = null){
		if($comp_id_!=null || $comp_id_ != ''){
		$qry[] = "comp_id = '".$comp_id_."'";
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";
		}
		$sql = "select * from company_info $criteria";
		$rsResult = $this->conn->Execute($sql);
		if(!$rsResult->EOF){
			return $rsResult->fields;
		}
    }
    
    function getTaxContribution($month, $year, $emp_id){
    	$sql = "select sum(ppe.ppe_amount) as ppe_amount_sum from payroll_paystub_entry ppe
				left join payroll_paystub_report ppr on (ppr.paystub_id=ppe.paystub_id)
				left join payroll_pay_period ppp on (ppp.payperiod_id=ppr.payperiod_id)
				WHERE ppp.payperiod_period='$month' and ppp.payperiod_period_year='$year' and ppe.psa_id='8' and ppr.emp_id='$emp_id'";
    	$rsResult = $this->conn->Execute($sql);
    	while(!$rsResult->EOF){
    		return $rsResult->fields;
    	}
    }
    
    function getTotalTaxContribution($month, $year, $comp_id, $branchinfo_id){
    	$qry = "";
    	$objClsSSS = new clsSSS($this->conn);
    	if($objClsSSS->getSettings($comp_id,12) && $branchinfo_id != 0){
    		$qry = " AND branchinfo_id='$branchinfo_id' ";
    	}
    	$sql = "select sum(ppe.ppe_amount) as totalTaxWithheld, sum(ppe.ppe_amount_employer) as totalTaxableCompensation from payroll_paystub_entry ppe
				join payroll_paystub_report ppr on (ppr.paystub_id=ppe.paystub_id)
				join payroll_pay_period ppp on (ppp.payperiod_id=ppr.payperiod_id)
				join emp_masterfile em on (em.emp_id=ppr.emp_id)
				WHERE ppp.payperiod_period='$month' and ppp.payperiod_period_year='$year' and ppe.psa_id='8'
				and em.comp_id='$comp_id' $qry";
    	$rsResult = $this->conn->Execute($sql);
    	while(!$rsResult->EOF){
    		return $rsResult->fields;
    	}   	
    }
    
    function getGrossCompensation($month, $year, $emp_id){
    	/**$sql = "select sum(ppe.ppe_amount) as gross from payroll_paystub_entry ppe
				join payroll_paystub_report ppr on (ppr.paystub_id=ppe.paystub_id)
				join payroll_pay_period ppp on (ppp.payperiod_id=ppr.payperiod_id)
				join payroll_ps_account ppa on (ppa.psa_id=ppe.psa_id)
				where (ppp.payperiod_period='$month' and ppp.payperiod_period_year='$year' and ppr.emp_id='$emp_id') 
				and (ppe.psa_id in ('4','25','26','28'))";**/
    	$sql = "SELECT basic_amt-(ut_abs_amt)+ot_amt+cola_amt+pe_entry_amt+amm_amt AS gross
				FROM 
				
				(SELECT COALESCE(SUM(a.ppe_amount), 0) AS basic_amt FROM payroll_paystub_entry a
				JOIN payroll_pay_stub b ON (b.paystub_id=a.paystub_id)
				JOIN payroll_pay_period c ON (c.payperiod_id=b.payperiod_id)
				JOIN payroll_paystub_report d ON (d.paystub_id=b.paystub_id)
				JOIN payroll_ps_account e ON (e.psa_id=a.psa_id)
				JOIN emp_masterfile em on (em.emp_id=d.emp_id)
				WHERE c.payperiod_period_year='{$year}' AND d.emp_id='{$emp_id}' AND a.psa_id='1' AND c.payperiod_period='{$month}') basic_tbl,
				
				(select COALESCE(sum(emp_tarec_amtperrate),0) as ut_abs_amt 
				FROM ta_emp_rec a 
				JOIN ta_tbl b on (b.tatbl_id=a.tatbl_id) 
				JOIN payroll_pay_period c on (c.payperiod_id=a.payperiod_id) 
				JOIN emp_masterfile em on (em.emp_id=a.emp_id)
				WHERE (a.tatbl_id IN (1,3,4)) AND a.emp_id='{$emp_id}' AND a.paystub_id!=0 AND c.payperiod_period = '{$month}' AND c.payperiod_period_year = '{$year}') ut_tbl,
				
				(select COALESCE(sum(ppe_amount),0) as ot_amt 
				FROM payroll_paystub_entry a 
				JOIN payroll_pay_stub b on (b.paystub_id=a.paystub_id) 
				JOIN payroll_paystub_report c on (c.paystub_id=b.paystub_id) 
				JOIN payroll_pay_period d on (d.payperiod_id=c.payperiod_id)
				JOIN emp_masterfile em on (em.emp_id=c.emp_id)
				WHERE a.psa_id='16' AND c.emp_id='{$emp_id}' AND d.payperiod_period = '{$month}' AND d.payperiod_period_year = '{$year}') ot_tbl,
    			
    			(select COALESCE(sum(ppe_amount),0) as cola_amt 
    			FROM payroll_paystub_entry a 
    			JOIN payroll_pay_stub b on (b.paystub_id=a.paystub_id) 
    			JOIN payroll_paystub_report c on (c.paystub_id=b.paystub_id) 
    			JOIN payroll_pay_period d on (d.payperiod_id=c.payperiod_id)
    			JOIN emp_masterfile em on (em.emp_id=c.emp_id)
    			WHERE a.psa_id='39' AND c.emp_id='{$emp_id}' and d.payperiod_period = '{$month}' AND d.payperiod_period_year = '{$year}') cola_tbl,
    			
    			(SELECT COALESCE(sum(a.ppe_amount),0) as pe_entry_amt 
				FROM payroll_paystub_entry a 
				JOIN payroll_paystub_report b on (b.paystub_id=a.paystub_id) 
				JOIN payroll_pay_period c on (c.payperiod_id=b.payperiod_id) 
				JOIN emp_masterfile em on (em.emp_id=b.emp_id)
				WHERE a.psa_id IN (select distinct a.psa_id 
				FROM payroll_ps_account a 
				JOIN payroll_paystub_entry b on (b.psa_id=a.psa_id) 
				JOIN payroll_pay_stub c on (c.paystub_id=b.paystub_id) 
				JOIN payroll_pay_period d on (d.payperiod_id=c.payperiod_id)
				JOIN payroll_paystub_report e on (e.paystub_id=b.paystub_id) 
				WHERE a.psa_type=1 and a.psa_procode!=6 and e.emp_id='{$emp_id}' and d.payperiod_period = '{$month}' and d.payperiod_period_year = '{$year}') 
				AND b.emp_id='{$emp_id}' AND c.payperiod_period = '{$month}' and c.payperiod_period_year = '{$year}') pe_entry_tbl,
				
				(select COALESCE(sum(a.amendemp_amount),0) as amm_amt 
				FROM payroll_ps_amendemp a 
				JOIN payroll_ps_amendment b on (b.psamend_id=a.psamend_id)
				JOIN payroll_pay_stub c on (c.paystub_id=a.paystub_id)
				JOIN payroll_pay_period d on (d.payperiod_id=c.payperiod_id)
				JOIN emp_masterfile em on (em.emp_id=a.emp_id)
				WHERE b.psa_id IN (select distinct a.psa_id 
				FROM payroll_ps_account a 
				JOIN payroll_ps_amendment b on (b.psa_id=a.psa_id) 
				JOIN payroll_ps_amendemp c on (c.psamend_id=b.psamend_id) 
				JOIN payroll_pay_stub d on (d.paystub_id=c.paystub_id) 
				JOIN payroll_pay_period e on (e.payperiod_id=d.payperiod_id) 
				WHERE a.psa_type=1 and a.psa_procode!=6 and c.emp_id='{$emp_id}' and e.payperiod_period = '{$month}' and e.payperiod_period_year = '{$year}') 
				AND a.emp_id='{$emp_id}' AND a.paystub_id!=0 AND d.payperiod_period = '{$month}' AND d.payperiod_period_year = '{$year}') amm_tbl";
    	$rsResult = $this->conn->Execute($sql);
    	while(!$rsResult->EOF){
    		return $rsResult->fields;
    	}  	
    }
    
    function getTotalGrossCompensation($month, $year, $comp_id, $branchinfo_id){
    	$qry = "";
    	$objClsSSS = new clsSSS($this->conn);
    	if($objClsSSS->getSettings($comp_id,12) && $branchinfo_id != 0){
    		$qry = " AND branchinfo_id='$branchinfo_id' ";
    	}
    	/**$sql = "select sum(ppe.ppe_amount) as gross from payroll_paystub_entry ppe
				join payroll_paystub_report ppr on (ppr.paystub_id=ppe.paystub_id)
				join payroll_pay_period ppp on (ppp.payperiod_id=ppr.payperiod_id)
				join payroll_ps_account ppa on (ppa.psa_id=ppe.psa_id)
				join emp_masterfile em on (em.emp_id=ppr.emp_id)
				where (ppp.payperiod_period='$month' and ppp.payperiod_period_year='$year') 
				and (ppe.psa_id in ('4','25','26','28'))
				and em.comp_id='$comp_id'";**/
    	$sql = "SELECT basic_amt-(ut_abs_amt)+ot_amt+cola_amt+pe_entry_amt+amm_amt AS gross
				FROM 
				
				(SELECT COALESCE(SUM(a.ppe_amount), 0) AS basic_amt FROM payroll_paystub_entry a
				JOIN payroll_pay_stub b ON (b.paystub_id=a.paystub_id)
				JOIN payroll_pay_period c ON (c.payperiod_id=b.payperiod_id)
				JOIN payroll_paystub_report d ON (d.paystub_id=b.paystub_id)
				JOIN payroll_ps_account e ON (e.psa_id=a.psa_id)
				JOIN emp_masterfile em on (em.emp_id=d.emp_id)
				WHERE c.payperiod_period_year='{$year}' AND em.comp_id='{$comp_id}' $qry AND a.psa_id='1' AND c.payperiod_period='{$month}') basic_tbl,
				
				(select COALESCE(sum(emp_tarec_amtperrate),0) as ut_abs_amt 
				FROM ta_emp_rec a 
				JOIN ta_tbl b on (b.tatbl_id=a.tatbl_id) 
				JOIN payroll_pay_period c on (c.payperiod_id=a.payperiod_id) 
				JOIN emp_masterfile em on (em.emp_id=a.emp_id)
				WHERE (a.tatbl_id IN (1,3,4)) AND em.comp_id='{$comp_id}' $qry AND a.paystub_id!=0 AND c.payperiod_period = '{$month}' AND c.payperiod_period_year = '{$year}') ut_tbl,
				
				(select COALESCE(sum(ppe_amount),0) as ot_amt 
				FROM payroll_paystub_entry a 
				JOIN payroll_pay_stub b on (b.paystub_id=a.paystub_id) 
				JOIN payroll_paystub_report c on (c.paystub_id=b.paystub_id) 
				JOIN payroll_pay_period d on (d.payperiod_id=c.payperiod_id)
				JOIN emp_masterfile em on (em.emp_id=c.emp_id)
				WHERE a.psa_id='16' AND em.comp_id='{$comp_id}' $qry AND d.payperiod_period = '{$month}' AND d.payperiod_period_year = '{$year}') ot_tbl,
    			
    			(select COALESCE(sum(ppe_amount),0) as cola_amt 
    			FROM payroll_paystub_entry a 
    			JOIN payroll_pay_stub b on (b.paystub_id=a.paystub_id) 
    			JOIN payroll_paystub_report c on (c.paystub_id=b.paystub_id) 
    			JOIN payroll_pay_period d on (d.payperiod_id=c.payperiod_id)
    			JOIN emp_masterfile em on (em.emp_id=c.emp_id)
    			WHERE a.psa_id='39' AND em.comp_id='{$comp_id}' $qry and d.payperiod_period = '{$month}' AND d.payperiod_period_year = '{$year}') cola_tbl,
    			
    			(SELECT COALESCE(sum(a.ppe_amount),0) as pe_entry_amt 
				FROM payroll_paystub_entry a 
				JOIN payroll_paystub_report b on (b.paystub_id=a.paystub_id) 
				JOIN payroll_pay_period c on (c.payperiod_id=b.payperiod_id) 
				JOIN emp_masterfile em on (em.emp_id=b.emp_id)
				WHERE a.psa_id IN (select distinct a.psa_id 
				FROM payroll_ps_account a 
				JOIN payroll_paystub_entry b on (b.psa_id=a.psa_id) 
				JOIN payroll_pay_stub c on (c.paystub_id=b.paystub_id) 
				JOIN payroll_pay_period d on (d.payperiod_id=c.payperiod_id)
				WHERE a.psa_type=1 and a.psa_procode!=6 and d.payperiod_period = '{$month}' and d.payperiod_period_year = '{$year}') 
				AND em.comp_id='{$comp_id}' $qry AND c.payperiod_period = '{$month}' and c.payperiod_period_year = '{$year}') pe_entry_tbl,
				
				(select COALESCE(sum(a.amendemp_amount),0) as amm_amt 
				FROM payroll_ps_amendemp a 
				JOIN payroll_ps_amendment b on (b.psamend_id=a.psamend_id)
				JOIN payroll_pay_stub c on (c.paystub_id=a.paystub_id)
				JOIN payroll_pay_period d on (d.payperiod_id=c.payperiod_id)
				JOIN emp_masterfile em on (em.emp_id=a.emp_id)
				WHERE b.psa_id IN (select distinct a.psa_id 
				FROM payroll_ps_account a 
				JOIN payroll_ps_amendment b on (b.psa_id=a.psa_id) 
				JOIN payroll_ps_amendemp c on (c.psamend_id=b.psamend_id) 
				JOIN payroll_pay_stub d on (d.paystub_id=c.paystub_id) 
				JOIN payroll_pay_period e on (e.payperiod_id=d.payperiod_id) 
				WHERE a.psa_type=1 and a.psa_procode!=6 and e.payperiod_period = '{$month}' and e.payperiod_period_year = '{$year}') 
				AND em.comp_id='{$comp_id}' $qry AND a.paystub_id!=0 AND d.payperiod_period = '{$month}' AND d.payperiod_period_year = '{$year}') amm_tbl";
    	//echo $sql;
    	//exit;		
    			
    	
    	$rsResult = $this->conn->Execute($sql);
    	while(!$rsResult->EOF){
    		return $rsResult->fields['gross'];
    	}  	
    	
    }
    
    function getNonTaxable($month, $year, $emp_id){
    	/*$sql = "select sum(ppe.ppe_amount) as nontax from payroll_paystub_entry ppe
				join payroll_paystub_report ppr on (ppr.paystub_id=ppe.paystub_id)
				join payroll_pay_period ppp on (ppp.payperiod_id=ppr.payperiod_id)
				join payroll_ps_account ppa on (ppa.psa_id=ppe.psa_id)
				where (ppp.payperiod_period='$month' and ppp.payperiod_period_year='$year' and ppr.emp_id='$emp_id') 
				and (ppe.psa_id='27' or ppa.psa_procode in ('2'))";*/
    	$sql = "SELECT (pe_entry_amt+amm_amt)-(statutory_amt+pe_entry_deduct_amt+amm_deduct_amt) AS nontax
				FROM 
				
				(SELECT COALESCE(sum(a.ppe_amount),0) as pe_entry_amt 
				FROM payroll_paystub_entry a 
				JOIN payroll_paystub_report b on (b.paystub_id=a.paystub_id) 
				JOIN payroll_pay_period c on (c.payperiod_id=b.payperiod_id) 
				JOIN emp_masterfile em on (em.emp_id=b.emp_id)
				WHERE a.psa_id IN (select distinct a.psa_id 
				FROM payroll_ps_account a 
				JOIN payroll_paystub_entry b on (b.psa_id=a.psa_id) 
				JOIN payroll_pay_stub c on (c.paystub_id=b.paystub_id) 
				JOIN payroll_pay_period d on (d.payperiod_id=c.payperiod_id)
				JOIN payroll_paystub_report e on (e.paystub_id=b.paystub_id) 
				WHERE a.psa_type=1 and a.psa_procode!=6 and e.emp_id='{$emp_id}' and d.payperiod_period = '{$month}' and d.payperiod_period_year = '{$year}' AND a.psa_tax=0) 
				AND b.emp_id='{$emp_id}' AND c.payperiod_period = '{$month}' and c.payperiod_period_year = '{$year}') pe_entry_tbl,
				
				(select COALESCE(sum(a.amendemp_amount),0) as amm_amt 
				FROM payroll_ps_amendemp a 
				JOIN payroll_ps_amendment b on (b.psamend_id=a.psamend_id)
				JOIN payroll_pay_stub c on (c.paystub_id=a.paystub_id)
				JOIN payroll_pay_period d on (d.payperiod_id=c.payperiod_id)
				JOIN emp_masterfile em on (em.emp_id=a.emp_id)
				WHERE b.psa_id IN (select distinct a.psa_id 
				FROM payroll_ps_account a 
				JOIN payroll_ps_amendment b on (b.psa_id=a.psa_id) 
				JOIN payroll_ps_amendemp c on (c.psamend_id=b.psamend_id) 
				JOIN payroll_pay_stub d on (d.paystub_id=c.paystub_id) 
				JOIN payroll_pay_period e on (e.payperiod_id=d.payperiod_id) 
				WHERE a.psa_type=1 and a.psa_procode!=6 and c.emp_id='{$emp_id}' and e.payperiod_period = '{$month}' and e.payperiod_period_year = '{$year}' AND a.psa_tax=0) 
				AND a.emp_id='{$emp_id}' AND a.paystub_id!=0 AND d.payperiod_period = '{$month}' AND d.payperiod_period_year = '{$year}') amm_tbl,
				
				(SELECT COALESCE(sum(a.ppe_amount),0) as statutory_amt 
				FROM payroll_paystub_entry a 
				JOIN payroll_paystub_report b on (b.paystub_id=a.paystub_id) 
				JOIN payroll_pay_period c on (c.payperiod_id=b.payperiod_id) 
				JOIN emp_masterfile em on (em.emp_id=b.emp_id)
				WHERE a.psa_id IN (7,14,15) 
				AND b.emp_id='{$emp_id}' AND c.payperiod_period = '{$month}' and c.payperiod_period_year = '{$year}') statutory_tbl,
				
				(SELECT COALESCE(sum(a.ppe_amount),0) as pe_entry_deduct_amt 
				FROM payroll_paystub_entry a 
				JOIN payroll_paystub_report b on (b.paystub_id=a.paystub_id) 
				JOIN payroll_pay_period c on (c.payperiod_id=b.payperiod_id) 
				JOIN emp_masterfile em on (em.emp_id=b.emp_id)
				WHERE a.psa_id IN (select distinct a.psa_id 
				FROM payroll_ps_account a 
				JOIN payroll_paystub_entry b on (b.psa_id=a.psa_id) 
				JOIN payroll_pay_stub c on (c.paystub_id=b.paystub_id) 
				JOIN payroll_pay_period d on (d.payperiod_id=c.payperiod_id)
				JOIN payroll_paystub_report e on (b.paystub_id=e.paystub_id)
				WHERE a.psa_type=2 and a.psa_procode!=6 and e.emp_id='{$emp_id}' and d.payperiod_period = '{$month}' and d.payperiod_period_year = '{$year}' AND a.psa_tax=1) 
				AND b.emp_id='{$emp_id}' AND c.payperiod_period = '{$month}' and c.payperiod_period_year = '{$year}') pe_entry_deduct_tbl,
				
				(select COALESCE(sum(a.amendemp_amount),0) as amm_deduct_amt 
				FROM payroll_ps_amendemp a 
				JOIN payroll_ps_amendment b on (b.psamend_id=a.psamend_id)
				JOIN payroll_pay_stub c on (c.paystub_id=a.paystub_id)
				JOIN payroll_pay_period d on (d.payperiod_id=c.payperiod_id)
				JOIN emp_masterfile em on (em.emp_id=a.emp_id)
				WHERE b.psa_id IN (select distinct a.psa_id 
				FROM payroll_ps_account a 
				JOIN payroll_ps_amendment b on (b.psa_id=a.psa_id) 
				JOIN payroll_ps_amendemp c on (c.psamend_id=b.psamend_id) 
				JOIN payroll_pay_stub d on (d.paystub_id=c.paystub_id) 
				JOIN payroll_pay_period e on (e.payperiod_id=d.payperiod_id) 
				WHERE a.psa_type=2 and a.psa_procode!=6 and c.emp_id='{$emp_id}' and e.payperiod_period = '{$month}' and e.payperiod_period_year = '{$year}' AND a.psa_tax=1) 
				AND a.emp_id='{$emp_id}' AND a.paystub_id!=0 AND d.payperiod_period = '{$month}' AND d.payperiod_period_year = '{$year}') amm_deduct_tbl";
    	$rsResult = $this->conn->Execute($sql);
    	while(!$rsResult->EOF){
    		return $rsResult->fields['nontax'];
    	}
    }  	
    
   function getStatutory($month, $year, $emp_id){
    	$sql = "select sum(ppe.ppe_amount) as statMWE from payroll_paystub_entry ppe
		join payroll_paystub_report ppr on (ppr.paystub_id=ppe.paystub_id)
		join payroll_pay_period ppp on (ppp.payperiod_id=ppr.payperiod_id)
		where (ppp.payperiod_period='$month' and ppp.payperiod_period_year='$year' and ppr.emp_id='$emp_id') 
		and (ppe.psa_id='27')";
    	$rsResult = $this->conn->Execute($sql);
    	while(!$rsResult->EOF){
    		return $rsResult->fields;
    	}  	
    }
	
	function getGrossMWE($month, $year, $emp_id){
		$sql = "SELECT basic_amt-(ut_abs_amt)+cola_amt+pe_entry_amt+amm_amt AS grossMWE
				FROM 
				
				(SELECT COALESCE(SUM(a.ppe_amount), 0) AS basic_amt FROM payroll_paystub_entry a
				JOIN payroll_pay_stub b ON (b.paystub_id=a.paystub_id)
				JOIN payroll_pay_period c ON (c.payperiod_id=b.payperiod_id)
				JOIN payroll_paystub_report d ON (d.paystub_id=b.paystub_id)
				JOIN payroll_ps_account e ON (e.psa_id=a.psa_id)
				JOIN emp_masterfile em on (em.emp_id=d.emp_id)
				JOIN period_benloanduc_sched pbs ON (pbs.emp_id=em.emp_id)
				WHERE c.payperiod_period_year='{$year}' AND d.emp_id='{$emp_id}' AND a.psa_id='1' AND c.payperiod_period='{$month}'
				AND pbs.bldsched_period = '2' AND pbs.empdd_id = '5') basic_tbl,
				
				(select COALESCE(sum(emp_tarec_amtperrate),0) as ut_abs_amt 
				FROM ta_emp_rec a 
				JOIN ta_tbl b on (b.tatbl_id=a.tatbl_id) 
				JOIN payroll_pay_period c on (c.payperiod_id=a.payperiod_id) 
				JOIN emp_masterfile em on (em.emp_id=a.emp_id)
				JOIN period_benloanduc_sched pbs ON (pbs.emp_id=em.emp_id)
				WHERE (a.tatbl_id IN (1,3,4)) AND a.emp_id='{$emp_id}' AND a.paystub_id!=0 AND c.payperiod_period = '{$month}' AND c.payperiod_period_year = '{$year}'
				AND pbs.bldsched_period = '2' AND pbs.empdd_id = '5') ut_tbl,
    			
    			(select COALESCE(sum(ppe_amount),0) as cola_amt 
    			FROM payroll_paystub_entry a 
    			JOIN payroll_pay_stub b on (b.paystub_id=a.paystub_id) 
    			JOIN payroll_paystub_report c on (c.paystub_id=b.paystub_id) 
    			JOIN payroll_pay_period d on (d.payperiod_id=c.payperiod_id)
    			JOIN emp_masterfile em on (em.emp_id=c.emp_id)
    			JOIN period_benloanduc_sched pbs ON (pbs.emp_id=em.emp_id)
    			WHERE a.psa_id='39' AND c.emp_id='{$emp_id}' and d.payperiod_period = '{$month}' AND d.payperiod_period_year = '{$year}'
    			AND pbs.bldsched_period = '2' AND pbs.empdd_id = '5') cola_tbl,
    			
    			(SELECT COALESCE(sum(a.ppe_amount),0) as pe_entry_amt 
				FROM payroll_paystub_entry a 
				JOIN payroll_paystub_report b on (b.paystub_id=a.paystub_id) 
				JOIN payroll_pay_period c on (c.payperiod_id=b.payperiod_id) 
				JOIN emp_masterfile em on (em.emp_id=b.emp_id)
				JOIN period_benloanduc_sched pbs ON (pbs.emp_id=em.emp_id)
				WHERE a.psa_id IN (select distinct a.psa_id 
				FROM payroll_ps_account a 
				JOIN payroll_paystub_entry b on (b.psa_id=a.psa_id) 
				JOIN payroll_pay_stub c on (c.paystub_id=b.paystub_id) 
				JOIN payroll_pay_period d on (d.payperiod_id=c.payperiod_id)
				WHERE a.psa_type=1 and a.psa_procode!=6 and d.payperiod_period = '{$month}' and d.payperiod_period_year = '{$year}') 
				AND b.emp_id='{$emp_id}' AND c.payperiod_period = '{$month}' and c.payperiod_period_year = '{$year}'
				AND pbs.bldsched_period = '2' AND pbs.empdd_id = '5') pe_entry_tbl,
				
				(select COALESCE(sum(a.amendemp_amount),0) as amm_amt 
				FROM payroll_ps_amendemp a 
				JOIN payroll_ps_amendment b on (b.psamend_id=a.psamend_id)
				JOIN payroll_pay_stub c on (c.paystub_id=a.paystub_id)
				JOIN payroll_pay_period d on (d.payperiod_id=c.payperiod_id)
				JOIN emp_masterfile em on (em.emp_id=a.emp_id)
				JOIN period_benloanduc_sched pbs ON (pbs.emp_id=em.emp_id)
				WHERE b.psa_id IN (select distinct a.psa_id 
				FROM payroll_ps_account a 
				JOIN payroll_ps_amendment b on (b.psa_id=a.psa_id) 
				JOIN payroll_ps_amendemp c on (c.psamend_id=b.psamend_id) 
				JOIN payroll_pay_stub d on (d.paystub_id=c.paystub_id) 
				JOIN payroll_pay_period e on (e.payperiod_id=d.payperiod_id) 
				WHERE a.psa_type=1 and a.psa_procode!=6 and e.payperiod_period = '{$month}' and e.payperiod_period_year = '{$year}') 
				AND a.emp_id='{$emp_id}' AND a.paystub_id!=0 AND d.payperiod_period = '{$month}' AND d.payperiod_period_year = '{$year}'
				AND pbs.bldsched_period = '2' AND pbs.empdd_id = '5') amm_tbl";
    	$rsResult = $this->conn->Execute($sql);
    	while(!$rsResult->EOF){
    		return $rsResult->fields['grossMWE'];
    	}
    }
    
    function getTotalGrossMWE($month, $year, $comp_id, $branchinfo_id){
    	$qry = "";
    	$objClsSSS = new clsSSS($this->conn);
    	if($objClsSSS->getSettings($comp_id,12) && $branchinfo_id != 0){
    		$qry = " AND branchinfo_id='$branchinfo_id' ";
    	}
    	$sql = "SELECT basic_amt-(ut_abs_amt)+cola_amt+pe_entry_amt+amm_amt AS grossMWE
				FROM 
				
				(SELECT COALESCE(SUM(a.ppe_amount), 0) AS basic_amt FROM payroll_paystub_entry a
				JOIN payroll_pay_stub b ON (b.paystub_id=a.paystub_id)
				JOIN payroll_pay_period c ON (c.payperiod_id=b.payperiod_id)
				JOIN payroll_paystub_report d ON (d.paystub_id=b.paystub_id)
				JOIN payroll_ps_account e ON (e.psa_id=a.psa_id)
				JOIN emp_masterfile em on (em.emp_id=d.emp_id)
				JOIN period_benloanduc_sched pbs ON (pbs.emp_id=em.emp_id)
				WHERE c.payperiod_period_year='{$year}' AND em.comp_id='{$comp_id}' $qry AND a.psa_id='1' AND c.payperiod_period='{$month}'
				AND pbs.bldsched_period = '2' AND pbs.empdd_id = '5') basic_tbl,
				
				(select COALESCE(sum(emp_tarec_amtperrate),0) as ut_abs_amt 
				FROM ta_emp_rec a 
				JOIN ta_tbl b on (b.tatbl_id=a.tatbl_id) 
				JOIN payroll_pay_period c on (c.payperiod_id=a.payperiod_id) 
				JOIN emp_masterfile em on (em.emp_id=a.emp_id)
				JOIN period_benloanduc_sched pbs ON (pbs.emp_id=em.emp_id)
				WHERE (a.tatbl_id IN (1,3,4)) AND em.comp_id='{$comp_id}' $qry AND a.paystub_id!=0 AND c.payperiod_period = '{$month}' AND c.payperiod_period_year = '{$year}'
				AND pbs.bldsched_period = '2' AND pbs.empdd_id = '5') ut_tbl,
    			
    			(select COALESCE(sum(ppe_amount),0) as cola_amt 
    			FROM payroll_paystub_entry a 
    			JOIN payroll_pay_stub b on (b.paystub_id=a.paystub_id) 
    			JOIN payroll_paystub_report c on (c.paystub_id=b.paystub_id) 
    			JOIN payroll_pay_period d on (d.payperiod_id=c.payperiod_id)
    			JOIN emp_masterfile em on (em.emp_id=c.emp_id)
    			JOIN period_benloanduc_sched pbs ON (pbs.emp_id=em.emp_id)
    			WHERE a.psa_id='39' AND em.comp_id='{$comp_id}' $qry and d.payperiod_period = '{$month}' AND d.payperiod_period_year = '{$year}'
    			AND pbs.bldsched_period = '2' AND pbs.empdd_id = '5') cola_tbl,
    			
    			(SELECT COALESCE(sum(a.ppe_amount),0) as pe_entry_amt 
				FROM payroll_paystub_entry a 
				JOIN payroll_paystub_report b on (b.paystub_id=a.paystub_id) 
				JOIN payroll_pay_period c on (c.payperiod_id=b.payperiod_id) 
				JOIN emp_masterfile em on (em.emp_id=b.emp_id)
				JOIN period_benloanduc_sched pbs ON (pbs.emp_id=em.emp_id)
				WHERE a.psa_id IN (select distinct a.psa_id 
				FROM payroll_ps_account a 
				JOIN payroll_paystub_entry b on (b.psa_id=a.psa_id) 
				JOIN payroll_pay_stub c on (c.paystub_id=b.paystub_id) 
				JOIN payroll_pay_period d on (d.payperiod_id=c.payperiod_id)
				WHERE a.psa_type=1 and a.psa_procode!=6 and d.payperiod_period = '{$month}' and d.payperiod_period_year = '{$year}') 
				AND em.comp_id='{$comp_id}' $qry AND c.payperiod_period = '{$month}' and c.payperiod_period_year = '{$year}'
				AND pbs.bldsched_period = '2' AND pbs.empdd_id = '5') pe_entry_tbl,
				
				(select COALESCE(sum(a.amendemp_amount),0) as amm_amt 
				FROM payroll_ps_amendemp a 
				JOIN payroll_ps_amendment b on (b.psamend_id=a.psamend_id)
				JOIN payroll_pay_stub c on (c.paystub_id=a.paystub_id)
				JOIN payroll_pay_period d on (d.payperiod_id=c.payperiod_id)
				JOIN emp_masterfile em on (em.emp_id=a.emp_id)
				JOIN period_benloanduc_sched pbs ON (pbs.emp_id=em.emp_id)
				WHERE b.psa_id IN (select distinct a.psa_id 
				FROM payroll_ps_account a 
				JOIN payroll_ps_amendment b on (b.psa_id=a.psa_id) 
				JOIN payroll_ps_amendemp c on (c.psamend_id=b.psamend_id) 
				JOIN payroll_pay_stub d on (d.paystub_id=c.paystub_id) 
				JOIN payroll_pay_period e on (e.payperiod_id=d.payperiod_id) 
				WHERE a.psa_type=1 and a.psa_procode!=6 and e.payperiod_period = '{$month}' and e.payperiod_period_year = '{$year}') 
				AND em.comp_id='{$comp_id}' $qry AND a.paystub_id!=0 AND d.payperiod_period = '{$month}' AND d.payperiod_period_year = '{$year}'
				AND pbs.bldsched_period = '2' AND pbs.empdd_id = '5') amm_tbl";
    	$rsResult = $this->conn->Execute($sql);
    	while(!$rsResult->EOF){
    		return $rsResult->fields['grossMWE'];
    	}
    }
    
    function getTotalStat($month, $year, $comp_id, $branchinfo_id){
    	$qry = "";
    	$objClsSSS = new clsSSS($this->conn);
    	if($objClsSSS->getSettings($comp_id,12) && $branchinfo_id != 0){
    		$qry = " AND branchinfo_id='$branchinfo_id' ";
    	}
    	/*$sql = "select sum(ppe.ppe_amount) as statMWE from payroll_paystub_entry ppe
				join payroll_paystub_report ppr on (ppr.paystub_id=ppe.paystub_id)
				join payroll_pay_period ppp on (ppp.payperiod_id=ppr.payperiod_id)
				join emp_masterfile em on (em.emp_id=ppr.emp_id)
				where ppp.payperiod_period='$month' and ppp.payperiod_period_year='$year'
				and (ppe.psa_id='27')
				and em.comp_id='$comp_id'";*/
		$sql = "SELECT (pe_entry_amt+amm_amt)-(statutory_amt+pe_entry_deduct_amt+amm_deduct_amt) AS statMWE
				FROM 
				
				(SELECT COALESCE(sum(a.ppe_amount),0) as pe_entry_amt 
				FROM payroll_paystub_entry a 
				JOIN payroll_paystub_report b on (b.paystub_id=a.paystub_id) 
				JOIN payroll_pay_period c on (c.payperiod_id=b.payperiod_id) 
				JOIN emp_masterfile em on (em.emp_id=b.emp_id)
				WHERE a.psa_id IN (select distinct a.psa_id 
				FROM payroll_ps_account a 
				JOIN payroll_paystub_entry b on (b.psa_id=a.psa_id) 
				JOIN payroll_pay_stub c on (c.paystub_id=b.paystub_id) 
				JOIN payroll_pay_period d on (d.payperiod_id=c.payperiod_id)
				WHERE a.psa_type=1 and a.psa_procode!=6 and d.payperiod_period = '{$month}' and d.payperiod_period_year = '{$year}' AND a.psa_tax=0) 
				AND em.comp_id='{$comp_id}' $qry AND c.payperiod_period = '{$month}' and c.payperiod_period_year = '{$year}') pe_entry_tbl,
				
				(select COALESCE(sum(a.amendemp_amount),0) as amm_amt 
				FROM payroll_ps_amendemp a 
				JOIN payroll_ps_amendment b on (b.psamend_id=a.psamend_id)
				JOIN payroll_pay_stub c on (c.paystub_id=a.paystub_id)
				JOIN payroll_pay_period d on (d.payperiod_id=c.payperiod_id)
				JOIN emp_masterfile em on (em.emp_id=a.emp_id)
				WHERE b.psa_id IN (select distinct a.psa_id 
				FROM payroll_ps_account a 
				JOIN payroll_ps_amendment b on (b.psa_id=a.psa_id) 
				JOIN payroll_ps_amendemp c on (c.psamend_id=b.psamend_id) 
				JOIN payroll_pay_stub d on (d.paystub_id=c.paystub_id) 
				JOIN payroll_pay_period e on (e.payperiod_id=d.payperiod_id) 
				WHERE a.psa_type=1 and a.psa_procode!=6 and e.payperiod_period = '{$month}' and e.payperiod_period_year = '{$year}' AND a.psa_tax=0) 
				AND em.comp_id='{$comp_id}' $qry AND a.paystub_id!=0 AND d.payperiod_period = '{$month}' AND d.payperiod_period_year = '{$year}') amm_tbl,
				
				(SELECT COALESCE(sum(a.ppe_amount),0) as statutory_amt 
				FROM payroll_paystub_entry a 
				JOIN payroll_paystub_report b on (b.paystub_id=a.paystub_id) 
				JOIN payroll_pay_period c on (c.payperiod_id=b.payperiod_id) 
				JOIN emp_masterfile em on (em.emp_id=b.emp_id)
				WHERE a.psa_id IN (7,14,15) 
				AND em.comp_id='{$comp_id}' $qry AND c.payperiod_period = '{$month}' and c.payperiod_period_year = '{$year}') statutory_tbl,
				
				(SELECT COALESCE(sum(a.ppe_amount),0) as pe_entry_deduct_amt 
				FROM payroll_paystub_entry a 
				JOIN payroll_paystub_report b on (b.paystub_id=a.paystub_id) 
				JOIN payroll_pay_period c on (c.payperiod_id=b.payperiod_id) 
				JOIN emp_masterfile em on (em.emp_id=b.emp_id)
				WHERE a.psa_id IN (select distinct a.psa_id 
				FROM payroll_ps_account a 
				JOIN payroll_paystub_entry b on (b.psa_id=a.psa_id) 
				JOIN payroll_pay_stub c on (c.paystub_id=b.paystub_id) 
				JOIN payroll_pay_period d on (d.payperiod_id=c.payperiod_id)
				WHERE a.psa_type=2 and a.psa_procode!=6 and d.payperiod_period = '{$month}' and d.payperiod_period_year = '{$year}' AND a.psa_tax=1) 
				AND em.comp_id='{$comp_id}' $qry AND c.payperiod_period = '{$month}' and c.payperiod_period_year = '{$year}') pe_entry_deduct_tbl,
				
				(select COALESCE(sum(a.amendemp_amount),0) as amm_deduct_amt 
				FROM payroll_ps_amendemp a 
				JOIN payroll_ps_amendment b on (b.psamend_id=a.psamend_id)
				JOIN payroll_pay_stub c on (c.paystub_id=a.paystub_id)
				JOIN payroll_pay_period d on (d.payperiod_id=c.payperiod_id)
				JOIN emp_masterfile em on (em.emp_id=a.emp_id)
				WHERE b.psa_id IN (select distinct a.psa_id 
				FROM payroll_ps_account a 
				JOIN payroll_ps_amendment b on (b.psa_id=a.psa_id) 
				JOIN payroll_ps_amendemp c on (c.psamend_id=b.psamend_id) 
				JOIN payroll_pay_stub d on (d.paystub_id=c.paystub_id) 
				JOIN payroll_pay_period e on (e.payperiod_id=d.payperiod_id) 
				WHERE a.psa_type=2 and a.psa_procode!=6 and e.payperiod_period = '{$month}' and e.payperiod_period_year = '{$year}' AND a.psa_tax=1) 
				AND em.comp_id='{$comp_id}' $qry AND a.paystub_id!=0 AND d.payperiod_period = '{$month}' AND d.payperiod_period_year = '{$year}') amm_deduct_tbl";
        $rsResult = $this->conn->Execute($sql);
    	while(!$rsResult->EOF){
    		return $rsResult->fields['statMWE'];
    	}  	
    }
    
	function getTotalStatMWE($month, $year, $comp_id){
    	$sql = "select sum(ppe.ppe_amount) as statMWE from payroll_paystub_entry ppe
				join payroll_paystub_report ppr on (ppr.paystub_id=ppe.paystub_id)
				join payroll_pay_period ppp on (ppp.payperiod_id=ppr.payperiod_id)
				join emp_masterfile em on (em.emp_id=ppr.emp_id)
				where ppp.payperiod_period='$month' and ppp.payperiod_period_year='$year'
				and (ppe.psa_id='27')
				and em.comp_id='$comp_id'";
        $rsResult = $this->conn->Execute($sql);
    	while(!$rsResult->EOF){
    		return $rsResult->fields['statMWE'];
    	}  	
    }
    
	// function that will compute for the overtime
    function getOvertime($month, $year, $comp_id, $branchinfo_id) {
    	$qry = "";
    	$objClsSSS = new clsSSS($this->conn);
    	if($objClsSSS->getSettings($comp_id,12) && $branchinfo_id != 0){
    		$qry = " AND branchinfo_id='$branchinfo_id' ";
    	}
       	$sql = "SELECT entry_amt+amm_amt AS total
				FROM 
				(SELECT COALESCE(SUM(a.ppe_amount), 0) AS entry_amt FROM payroll_paystub_entry a
				JOIN payroll_pay_stub b ON (b.paystub_id=a.paystub_id)
				JOIN payroll_pay_period c ON (c.payperiod_id=b.payperiod_id)
				JOIN payroll_paystub_report d ON (d.paystub_id=b.paystub_id)
				JOIN payroll_ps_account e ON (e.psa_id=a.psa_id)
				JOIN emp_masterfile em on (em.emp_id=d.emp_id)
				JOIN period_benloanduc_sched f ON (d.emp_id=f.emp_id)
				WHERE c.payperiod_period_year='{$year}' AND em.comp_id='{$comp_id}' $qry AND a.psa_id='16' AND c.payperiod_period='{$month}' AND f.empdd_id = '5' AND f.bldsched_period = '2') entry_tbl,
				
				(select COALESCE(sum(a.amendemp_amount),0) as amm_amt 
				from payroll_ps_amendemp a 
				join payroll_ps_amendment b on (b.psamend_id=a.psamend_id) 
				join payroll_pay_stub c on (c.paystub_id=a.paystub_id) 
				join payroll_pay_period d on (d.payperiod_id=c.payperiod_id)
				JOIN payroll_ps_account e ON (e.psa_id=b.psa_id)
				JOIN emp_masterfile em on (em.emp_id=a.emp_id) 
				JOIN period_benloanduc_sched f ON (a.emp_id=f.emp_id)
				WHERE d.payperiod_period_year='{$year}' AND em.comp_id='{$comp_id}' $qry AND b.psa_id='16' AND d.payperiod_period='{$month}' AND f.empdd_id = '5' AND f.bldsched_period = '2'
				AND a.paystub_id NOT IN (SELECT a.paystub_id FROM payroll_paystub_entry a
				JOIN payroll_pay_stub b ON (b.paystub_id=a.paystub_id)
				JOIN payroll_pay_period c ON (c.payperiod_id=b.payperiod_id)
				JOIN payroll_paystub_report d ON (d.paystub_id=b.paystub_id)
				JOIN payroll_ps_account e ON (e.psa_id=a.psa_id)
				JOIN emp_masterfile em on (em.emp_id=d.emp_id)
				JOIN period_benloanduc_sched f ON (d.emp_id=f.emp_id)
				WHERE c.payperiod_period_year='{$year}' AND em.comp_id='{$comp_id}' $qry AND a.psa_id='16' AND c.payperiod_period='{$month}' AND f.empdd_id = '5' AND f.bldsched_period = '2')) amm_tbl";
    	$rsResult = $this->conn->Execute($sql);
    	while (!$rsResult->EOF) {
    		if ($rsResult->fields['total'] != '' or $rsResult->fields['total'] != NULL or $rsResult->fields['total'] != 0.00) {
    			return $rsResult->fields['total'];
    		} else {
    			return 0.00;
    		}
    	}
    }
    
	function getEmpOvertime($month, $year, $emp_id) {
       	$sql = "SELECT entry_amt+amm_amt AS total
				FROM 
				(SELECT COALESCE(SUM(a.ppe_amount), 0) AS entry_amt FROM payroll_paystub_entry a
				JOIN payroll_pay_stub b ON (b.paystub_id=a.paystub_id)
				JOIN payroll_pay_period c ON (c.payperiod_id=b.payperiod_id)
				JOIN payroll_paystub_report d ON (d.paystub_id=b.paystub_id)
				JOIN payroll_ps_account e ON (e.psa_id=a.psa_id)
				JOIN emp_masterfile em on (em.emp_id=d.emp_id)
				JOIN period_benloanduc_sched f ON (d.emp_id=f.emp_id)
				WHERE c.payperiod_period_year='{$year}' AND d.emp_id='{$emp_id}' AND a.psa_id='16' AND c.payperiod_period='{$month}' AND f.empdd_id = '5' AND f.bldsched_period = '2') entry_tbl,
				
				(select COALESCE(sum(a.amendemp_amount),0) as amm_amt 
				from payroll_ps_amendemp a 
				join payroll_ps_amendment b on (b.psamend_id=a.psamend_id) 
				join payroll_pay_stub c on (c.paystub_id=a.paystub_id) 
				join payroll_pay_period d on (d.payperiod_id=c.payperiod_id)
				JOIN payroll_ps_account e ON (e.psa_id=b.psa_id)
				JOIN emp_masterfile em on (em.emp_id=a.emp_id) 
				JOIN period_benloanduc_sched f ON (a.emp_id=f.emp_id)
				WHERE d.payperiod_period_year='{$year}' AND a.emp_id='{$emp_id}' AND b.psa_id='16' AND d.payperiod_period='{$month}' AND f.empdd_id = '5' AND f.bldsched_period = '2'
				AND a.paystub_id NOT IN (SELECT a.paystub_id FROM payroll_paystub_entry a
				JOIN payroll_pay_stub b ON (b.paystub_id=a.paystub_id)
				JOIN payroll_pay_period c ON (c.payperiod_id=b.payperiod_id)
				JOIN payroll_paystub_report d ON (d.paystub_id=b.paystub_id)
				JOIN payroll_ps_account e ON (e.psa_id=a.psa_id)
				JOIN emp_masterfile em on (em.emp_id=d.emp_id)
				JOIN period_benloanduc_sched f ON (d.emp_id=f.emp_id)
				WHERE c.payperiod_period_year='{$year}' AND d.emp_id='{$emp_id}' AND a.psa_id='16' AND c.payperiod_period='{$month}' AND f.empdd_id = '5' AND f.bldsched_period = '2')) amm_tbl";
    	$rsResult = $this->conn->Execute($sql);
    	while (!$rsResult->EOF) {
    		if ($rsResult->fields['total'] != '' or $rsResult->fields['total'] != NULL or $rsResult->fields['total'] != 0.00) {
    			return $rsResult->fields['total'];
    		} else {
    			return 0.00;
    		}
    	}
    }
    
    function moneyFormat($num){
    	$objClsMngeDecimal = new Application();
    	if((float)$num!=0){
    		$money_format = $finalDecFormat = $objClsMngeDecimal->setFinalDecimalPlaces($num);
    	}else{
    		$money_format = $finalDecFormat = $objClsMngeDecimal->setFinalDecimalPlaces(0);
    	}
    	return $money_format;
    }
    
	function getMonthInWords($month){
		$MonthInWords = date("F", mktime(0, 0, 0, $month));
		return $MonthInWords;
	}
	
	function getTaxDetails($comp_id){
		$sql = "select te_rdo, te_atc FROM tax_employer WHERE comp_id='$comp_id'";
		$rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF){
    		return $rsResult->fields;
    	}  	
	}
	
	function createPDF($content, $paper, $orientation, $filename){
		$dompdf = new DOMPDF();
		$dompdf->load_html($content);
		$dompdf->set_paper($paper,$orientation);
		$dompdf->render();
		$dompdf->stream($filename,array('Attachment' => 0));	
	}
	
	function getPDFMonthlyRemittance1($gData = array()){
		$orientation='P';
		$unit='mm';
		$format='LEGAL';
		$unicode=true;
		$encoding="UTF-8";

		$oPDF = new clsPDF($orientation, $unit, $format, $unicode, $encoding);
		
		// set auto page break to false so that we can control the page break
		// depending on the desired number of lines on the output
		$oPDF->SetAutoPageBreak(false);
		// use a freeserif font as a default font
		$oPDF->SetFont('helvetica','',10);
		
		// suppress print header and footer
		$oPDF->setPrintHeader(false);
		$oPDF->setPrintFooter(false);

		// set initial coordinates
		$coordX = 0;
		$coordY = 0;

		// set initial pdf page
		$oPDF->AddPage();
		
		$oPDF->Image(SYSCONFIG_THEME_PATH."default/images/admin/1601-C.jpg",$coordX,$coordY,$oPDF->getPageWidth(),$oPDF->getPageHeight());
		
		if(clsSSS::getSettings($gData['comp'],12) && $gData['branchinfo_id'] != 0){
        	$branch_details = clsSSS::getLocationInfo($gData['branchinfo_id']);
        	$company_name = $branch_details['branchinfo_name'];
        	$company_address = $branch_details['branchinfo_add'];
        	$company_tin = $branch_details['branchinfo_tin'];
        	$company_tel = $branch_details['branchinfo_tel1'];
        	$company_type = $branch_details['comptype_id'];
        }ELSE{
        	$branch = $this->dbfetchCompDetails($comp_id);
        	$company_name = $branch['comp_name'];
        	$company_address = $branch['comp_add'];
        	$company_tin = $branch['comp_tin'];
        	$company_tel = $branch['comp_tel'];
        	$company_type = $branch['comptype_id'];
        }
		$getZip = explode(",",$company_address);
		$address = "";
		
		//gross compensation
		$totalGross = $this->getTotalGrossCompensation($gData['month'],$gData['year'], $gData['comp'],$gData['branchinfo_id']);
		
		//Statutory(non-taxable) - 16C
		$totalStat = $this->getTotalStat($gData['month'],$gData['year'], $gData['comp'],$gData['branchinfo_id']);
		
		//MWE - 16.A
		$totalMWE = $this->getTotalGrossMWE($gData['month'],$gData['year'], $gData['comp'],$gData['branchinfo_id']);

		//MWE Overtime - 16B
		$totalOT = $this->getOvertime($gData['month'],$gData['year'], $gData['comp'],$gData['branchinfo_id']);

		//taxable
		
		$totalTaxCompensation = $totalGross-($totalStat+$totalMWE+$totalOT);
		
		//tax contribution
		$totalTaxContribution = $this->getTotalTaxContribution($gData['month'],$gData['year'], $gData['comp'],$gData['branchinfo_id']);
		
		$trans_date = $gData['year'].'-'.$gData['month'];
		$tax_emp = $this->getTaxDetails($gData['comp']);
		$month = str_split($gData['month']);
		$year = str_split($gData['year']);
		$rdo_code = str_split($tax_emp['te_rdo']);
		$comp_tel = str_split($company_tel);
		$tin = str_split(str_replace (array(" ","-"), "", $company_tin));
		if ($company_type == 1) {
			$oPDF->SetFont('helvetica','',13);
			$oPDF->Text($coordX+21.6, $coordY+101, "X");
		} else {
			$oPDF->SetFont('helvetica','',13);
			$oPDF->Text($coordX+37.7, $coordY+101, "X");
			
		}
		
		//for the month and year
		$oPDF->Text($coordX+45.5, $coordY+58, $month[0]);
		$oPDF->Text($coordX+50.5, $coordY+58, $month[1]);
		$oPDF->Text($coordX+55.6, $coordY+58, $year[0]);
		$oPDF->Text($coordX+60.6, $coordY+58, $year[1]);
		$oPDF->Text($coordX+65.7, $coordY+58, $year[2]);
		$oPDF->Text($coordX+70.9, $coordY+58, $year[3]);
		
		//tin
		$oPDF->Text($coordX+22.3, $coordY+69.5, $tin[0]);
		$oPDF->Text($coordX+27.5, $coordY+69.5, $tin[1]);
		$oPDF->Text($coordX+31.8, $coordY+69.5, $tin[2]);
		$oPDF->Text($coordX+40, $coordY+69.5, $tin[3]);
		$oPDF->Text($coordX+45, $coordY+69.5, $tin[4]);
		$oPDF->Text($coordX+50, $coordY+69.5, $tin[5]);
		$oPDF->Text($coordX+59, $coordY+69.5, $tin[6]);
		$oPDF->Text($coordX+64, $coordY+69.5, $tin[7]);
		$oPDF->Text($coordX+68.2, $coordY+69.5, $tin[8]);
		$oPDF->Text($coordX+76.5, $coordY+69.5, $tin[9]);
		$oPDF->Text($coordX+81, $coordY+69.5, $tin[10]);
		$oPDF->Text($coordX+85.5, $coordY+69.5, $tin[11]);
		$oPDF->Text($coordX+89, $coordY+69.5, $tin[12]);
		
		//RDO Code
		$oPDF->Text($coordX+117.5, $coordY+69.5, $rdo_code[0]);
		$oPDF->Text($coordX+122.5, $coordY+69.5, $rdo_code[1]);
		$oPDF->Text($coordX+126.5, $coordY+69.5, $rdo_code[2]);
		
		
		
		//company
		$oPDF->SetFont('helvetica','',9.5);
		$oPDF->Text($coordX+22.3, $coordY+79, $company_name);
		//telephone number
		$oPDF->Text($coordX+173.5, $coordY+79, $comp_tel[0]);
		$oPDF->Text($coordX+177.5, $coordY+79, $comp_tel[1]);
		$oPDF->Text($coordX+182, $coordY+79, $comp_tel[2]);
		$oPDF->Text($coordX+186.5, $coordY+79, $comp_tel[3]);
		$oPDF->Text($coordX+190.5, $coordY+79, $comp_tel[4]);
		$oPDF->Text($coordX+195, $coordY+79, $comp_tel[5]);
		$oPDF->Text($coordX+199, $coordY+79, $comp_tel[6]);
		//address
		$oPDF->Text($coordX+22.3, $coordY+90, $company_address);
		//zip
		$getZip = explode(",",$company_address);
		foreach ($getZip as $key => $value) {
			if ($key == count($getZip)-1 && is_numeric($value)) {
				$zip = $value;
			}
		}
		
		$comp_zip = str_split($zip);
		$oPDF->Text($coordX+182, $coordY+90, $comp_zip[1]);
		$oPDF->Text($coordX+188, $coordY+90, $comp_zip[2]);
		$oPDF->Text($coordX+193, $coordY+90, $comp_zip[3]);
		$oPDF->Text($coordX+198, $coordY+90, $comp_zip[4]);
		
		$oPDF->Text($coordx+117, $coordY+116, $this->moneyFormat($totalGross));
		$oPDF->Text($coordx+117, $coordY+123, $this->moneyFormat($totalMWE));
		$oPDF->Text($coordx+117, $coordY+130, $this->moneyFormat($totalOT));
		$oPDF->Text($coordx+117, $coordY+137, $this->moneyFormat($totalStat));
		$oPDF->Text($coordx+117, $coordY+144, $this->moneyFormat($totalTaxCompensation));
		$oPDF->Text($coordx+163, $coordY+149, $this->moneyFormat($totalTaxContribution['totalTaxWithheld']));
		
		$oPDF->setfont('helvetica', '', '7');
		//pos rep
		$oPDF->Text($coordX+30, $coordY+280.5, $gData['pos_rep']);
		$oPDF->Text($coordX+78, $coordY+280.5, $gData['tin_rep']);
		$oPDF->Text($coordX+35, $coordY+286.5, $gData['acc']);
		$oPDF->Text($coordX+69, $coordY+286.5, $gData['issue_date']);
		$oPDF->Text($coordX+96, $coordY+286.5, $gData['exp_date']);
		$oPDF->Text($coordX+145, $coordY+268, $gData['treasurer']);
		$oPDF->Text($coordX+145, $coordY+280, $gData['pos_tre']);
		$oPDF->Text($coordX+145, $coordY+286, $gData['tin_tre']);
		
		
		//get the pdf output
		$output = $oPDF->Output("1601C_".$this->getMonthInWords($gData['month']).'_'.$gData['year'].".pdf");

		if (!empty($output)) {
			return $output;
		}

		return false;
		}
	
	
	function getPDFMonthlyRemittance($gData = array()){
		$objClsMngeDecimal = new Application();
		$paper = 'Legal';
		$orientation = 'portrait';
		$filename = '1601-C_'.$this->getMonthInWords($gData['month']).'_'.$gData['year'].'.pdf';
		$branch = $this->dbfetchCompDetails($gData['comp']);
		
		$getZip = explode(",",$branch['comp_add']);
		$address = "";
		foreach($getZip as $key => $value){
			if($key == count($getZip)-1 && is_numeric($value)){
				$zip = $value;
			} else {
				$address .= $value;
				$zip ="&nbsp;";
			}
		}
		//gross compensation
		$totalGross = $this->getTotalGrossCompensation($gData['month'],$gData['year'], $gData['comp']);
		
		//Statutory(non-taxable)
		$totalStat = $this->getTotalStat($gData['month'],$gData['year'], $gData['comp']);
		
		// MWE
		$totalMWE = $this->getTotalGrossMWE($gData['month'],$gData['year'], $gData['comp']);
		
		//MWE Overtime
		$totalOT = $this->getOvertime($gData['month'],$gData['year'], $gData['comp']);
		
		//taxable
		// $totalTaxCompensation = $totalGross - $totalMWE - $totalStat;
		$totalTaxCompensation = $totalGross-($totalStat+$totalMWE+$totalOT);
		//tax contribution
		$totalTaxContribution = $this->getTotalTaxContribution($gData['month'],$gData['year'], $gData['comp']);
		
		$trans_date = $gData['year'].'-'.$gData['month'];
		$tax_emp = $this->getTaxDetails($gData['comp']);
		$month = str_split($gData['month']);
		$year = str_split($gData['year']);
		$tin = str_split(str_replace (array(" ","-"), "", $branch['comp_tin']));
		if($branch['comptype_id'] == 1){
			$private = 'X';
			$gov = '&nbsp;';
		}else {
			$private = '&nbsp;';
			$gov = 'X';	
		}
		$content = 	'<html><head>
					<style type="text/css">
						@page { margin-top: 1.5em; margin-bottom: 1em;} 
					</style></head>
					<body><table style=\'font-family:Helvetica; border:2px solid black; color:black; border-collapse:collapse; \' width=\'600px\'><tr><td>
					<table style=\'border-bottom:2px solid black; border-collapse:collapse;\' width=\'100%\'><tr><td width=\'60px\'><img src="'.SYSCONFIG_CLASS_PATH.'util/dompdf/images/pdf_report/BIR-Logo.gif" height=\'50px\' width=\'50px\'></td>
					<td style=\'font-size:11px;\' width=\'160px\'>Republika ng Pilipinas<br>Kagawaran ng Pananalapi<br>Kawanihan ng Rentas Internas</td>
					<td align=\'center\' style=\'font-size:18px;\' width=\'270px\'><strong><span>Monthly Remittance Return<br>of Income Taxes Withheld<br>on Compensation</strong></td>
					<td><span style=\'font-size:10px; padding-left:70px;\'>BIR Form No.</span><br><span style=\'font-size:30px;\'><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1601-C</strong></span><br><span style=\'font-size:10px; padding-left:70px;\'>July 2008 (ENCS)</span></td>
					</tr></table>
					
					<table style=\'font-size:10px; border-bottom:2px solid black; border-collapse:collapse;\' width=\'100%\'><tr><td>Fill in all applicable spaces. Mark all appropriate boxes with an "X"</td></tr></table>
					
					<table width=\'100%\' style=\'font-size:10px; border-bottom:2px solid black; background-color:#c0c0c0; border-collapse:collapse;\'>
					<tr>
						<td width=\'230px\' style=\'border-right:2px solid black; border-collapse:collapse;\'><table style="border-collapse:collapse; padding:3px 0;"><tr>
							<td width=\'80px\' style=\'vertical-align:top;\' colspan=\'3\'><strong>1</strong>&nbsp;&nbsp; For the Month</td></tr>
							<tr>
								<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(MM/YYYY)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
								<td style=\'border:1px solid black; vertical-align:bottom; font-size:14px; background-color:white; vertical-align:bottom;\' width=\'40px\' align=\'center\'>'.$month[0].'<span style=\'font-size:2px;\'>|</span> '.$month[1].'</td>
								<td style=\'border:1px solid black; vertical-align:bottom; font-size:14px; background-color:white;\' width=\'60px\' align=\'center\'>'.$year[0].'<span style=\'font-size:2px;\'>|</span> '.$year[1].'<span style=\'font-size:2px;\'>|</span> '.$year[2].'<span style=\'font-size:2px;\'>|</span> '.$year[3].'</td>
						</tr></table></td>
						<td width=\'180px\' style=\'border-right:2px solid black; border-collapse:collapse;\'><table style="border-collapse:collapse;">
							<tr><td colspan=\'8\' style="padding-bottom:4px;">&nbsp;<strong>2</strong>&nbsp;&nbsp; Amended Return?</td></tr>
							<tr>
								<td width="30px">&nbsp;</td>
								<td style=\'border:1px solid black; vertical-align:bottom; font-size:14px; background-color:white;\' width=\'20px\' align=\'center\'>&nbsp;</td>
								<td>&nbsp;</td>
								<td style="vertical-align:bottom;">Yes</td>
								<td>&nbsp;</td>
								<td style=\'border:1px solid black; vertical-align:bottom; font-size:14px; background-color:white;\' width=\'20px\' align=\'center\'>&nbsp;</td>
								<td>&nbsp;</td>
								<td style="vertical-align:bottom;">No</td>
							</tr>
						</table></td>
						<td style=\'border-right:2px solid black;\'><table style="border-collapse:collapse;">
							<tr><td colspan=\'4\' style="padding-bottom:4px;">&nbsp;<strong>3</strong>&nbsp;&nbsp; No. of Sheets Attached</td></tr>
							<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td style=\'border:1px solid black; vertical-align:bottom; font-size:14px; background-color:white;\' width=\'40px\' align=\'center\'>&nbsp;<span style=\'font-size:2px;\'>|</span> &nbsp;</td></tr>
						</table></td>
						<td ><table style="border-collapse:collapse;">
							<tr><td colspan=\'5\' style="padding-bottom:4px;">&nbsp;<strong>4</strong>&nbsp;&nbsp; Any Taxes Withheld?</td></tr>
							<tr>
								<td>&nbsp;</td>
								<td style=\'border:1px solid black; vertical-align:bottom; font-size:14px; background-color:white;\' width=\'20px\' align=\'center\'>&nbsp;</td>
								<td style="vertical-align:bottom;">Yes</td>
								<td style=\'border:1px solid black; vertical-align:bottom; font-size:14px; background-color:white;\' width=\'20px\' align=\'center\'>&nbsp;</td>
								<td style="vertical-align:bottom;">No</td>
							</tr>
						</table></td>
					</tr>
					</table>
					
					<table style=\'font-size:10px; border-bottom:2px solid black;  background-color:#c0c0c0; border-collapse:collapse;\' width=\'100%\'><tr><td><strong>Part I</strong></td><td align=\'center\'><span style=\'padding-left:500px;\'><strong>B a c k g r o u n d<span style="margin-right:10px;">&nbsp;</span>I n f o r m a t i o n</strong></span></td></tr></table>
					
					<table style=\'font-size:10px; border-bottom:2px solid black; background-color:#c0c0c0; border-collapse:collapse;\' width=\'100%\'><tr>
						<td style=\'border-right:2px solid black; padding:3px 0;\' width=\'315px\'><table style="border-collapse:collapse;">
							<tr>
								<td style=\'vertical-align:top;\' width=\'10px\'><strong>5</strong>&nbsp;&nbsp;&nbsp; TIN &nbsp;&nbsp;&nbsp;</td>
								<td style=\'border:1px solid black; vertical-align:bottom; font-size:14px; background-color:white;\' width=\'60px\' align=\'center\'>'. $tin[0] .'<span style=\'font-size:2px;\'>|</span> '. $tin[1] .'<span style=\'font-size:2px;\'>|</span> '. $tin[2] .'</td>
								<td style=\'border:1px solid black; vertical-align:bottom; font-size:14px; background-color:white;\' width=\'60px\' align=\'center\'>'. $tin[3] .'<span style=\'font-size:2px;\'>|</span> '. $tin[4] .'<span style=\'font-size:2px;\'>|</span> '. $tin[5] .'</td>
								<td style=\'border:1px solid black; vertical-align:bottom; font-size:14px; background-color:white;\' width=\'60px\' align=\'center\'>'. $tin[6] .'<span style=\'font-size:2px;\'>|</span> '. $tin[7] .'<span style=\'font-size:2px;\'>|</span> '. $tin[8] .'</td>
								<td style=\'border:1px solid black; vertical-align:bottom; font-size:14px; background-color:white;\' width=\'60px\' align=\'center\'> 0 <span style=\'font-size:2px;\'>|</span> 0 <span style=\'font-size:2px;\'>|</span> 0 </td>
							</tr>
						</table></td>
						<td style=\'border-right:2px solid black; padding:3px 0;\' width=\'140px\'><table style="border-collapse:collapse;"><tr>
							<td style=\'vertical-align:top;\'><strong>&nbsp;6</strong>&nbsp;&nbsp; RDO Code</td>
							<td><table style="border-collapse:collapse;"><tr><td style=\'border:1px solid black; vertical-align:bottom; font-size:14px; background-color:white;\' width=\'55px\' align=\'center\'>&nbsp;'.$tax_emp['te_rdo'].'</td></tr></table></td>
						</tr></table></td>
						<td style="padding:3px 0;"><table style="border-collapse:collapse;"><tr>
							<td width=\'100px\'><strong>&nbsp;7</strong>&nbsp;&nbsp; Line of Business/<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Occupation</td>
							<td><table style="border-collapse:collapse;"><tr><td style=\'border:1px solid black; vertical-align:bottom; font-size:14px; background-color:white;\' width=\'150px\'> &nbsp;</td></tr></table></td>
						</tr></table></td>
					</tr></table>
					
					<table style=\'font-size:10px; border-bottom:2px solid black; background-color:#c0c0c0; border-collapse:collpase;\' width=\'100%\'>
						<tr>
							<td style=\'border-right:2px solid black;\' width=\'583px\'><table style="border-collapse:collapse;">
								<tr><td width="500px"><strong>8</strong>&nbsp;&nbsp; Withholding Agent\'s Name (Last Name, First Name, Middle Name for Individuals)/(Registered Name for Non-Individuals)</td></tr>
								<tr><td><table style="border-collapse:collapse;"><tr><td width="25px" style="padding:3px 0;">&nbsp;</td><td width="539px" style=\'border:1px solid black; background-color:white; font-size:12px;\'>'.$branch['comp_name'].'</td></tr></table></td></tr>
							</table></td>
							<td><table style="border-collapse:collapse;">
								<tr><td><strong>9</strong>&nbsp;&nbsp; Telephone Number</td></tr>
								<tr><td style=\'border:1px solid black; background-color:white; padding:3px 0; font-size:12px;\' width=\'120px\'>'.$branch['comp_tel'].'</td></tr>
							</table></td>
						</tr>
					</table>
					
					<table style=\'font-size:10px; border-bottom:2px solid black; background-color:#c0c0c0; border-collapse:collapse;\' width=\'100%\'>
						<tr>
							<td style=\'border-right:2px solid black;\' width=\'584px\'><table style="border-collapse:collapse;">
								<tr><td width="500px"><strong>10</strong>&nbsp;&nbsp; Registered Address</td></tr>
								<tr><td style="padding-bottom:3px;"><table style="border-collapse:collapse;"><tr><td width="25px" style="padding:3px 0;">&nbsp;</td><td width="539px" style=\'border:1px solid black; background-color:white; font-size:12px;\' width=\'530px\'>&nbsp;'.$address.'</td></tr></table></td></tr>
							</table></td>
							<td><table style="border-collapse:collapse;">
								<tr><td><strong>11</strong>&nbsp;&nbsp; Zip Code</td></tr>
								<tr><td><table style="border-collapse:collapse;"><tr><td width="25px" style="padding:3px 0;">&nbsp;</td><td style=\'border:1px solid black; background-color:white; font-size:12px;\' width=\'95px\'>'.$zip.'</td></tr></table></td></tr>
							</table></td>
						</tr>
					</table>
					
					<table style=\'font-size:10px; border-bottom:2px solid black; background-color:#c0c0c0; border-collapse:collapse;\' width=\'100%\'>
						<tr>
							<td style=\'border-right:2px solid black;\'width=\'180px\'><table style="border-collapse:collapse;">
								<tr><td colspan=\'5\' style="padding-bottom:8px;"><strong>12</strong>&nbsp;&nbsp; Category of Withholding Agent</td></tr>
								<tr>
									<td width="30px">&nbsp;</td>
									<td style=\'border:1px solid black; background-color:white; font-size:16px;\' width=\'20px\' align="center">'.$private.'</td>
									<td><br>Private</td>
									<td style=\'border:1px solid black; background-color:white; font-size:16px;\' width=\'20px\' align="center">'.$gov.'</td>
									<td style="margin-bottom:3px;"><br>Government</td>
								</tr>
							</table></td>
							<td style=\'border-right:2px solid black;\' width=\'401px\'><table style="border-collapse:collapse;">
								<tr><td colspan=\'7\'><strong>13</strong>&nbsp;&nbsp; Are there payees availing of tax relief under Special Law<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;or International Tax Treaty?</td></tr>
								<tr>
									<td width="20px">&nbsp;</td>
									<td style=\'border:1px solid black; background-color:white;s\' width=\'20px\'>&nbsp;</td>
									<td width="10px">Yes</td>
									<td style=\'border:1px solid black; background-color:white;s\' width=\'20px\'>&nbsp;</td>
									<td width="20px">No</td>
									<td width="80px">If yes, specify<br></td>
									<td style=\'border:1px solid black; background-color:white; padding:4px 0;\' width=\'107px\'>&nbsp;</td>
								</tr>
							</table></td>
							<td><table style="border-collapse:collapse;">
								<tr><td><strong>14</strong>&nbsp;&nbsp; A T C<br><br></td></tr>
								<tr><td><table style="border-collapse:collapse;"><tr><td width="25px">&nbsp;</td><td style=\'padding:4px 0; border:1px solid black; background-color:white;\' width=\'95px\'>'.$tax_emp['te_atc'].'&nbsp;</td></tr></table></td></tr>
							</table></td>
						</tr>
					</table>
					
					<table style=\'font-size:10px; border-bottom:2px solid black; background-color:#c0c0c0; border-collapse:collapse;\' width=\'100%\'><tr><td width=\'30px\'><strong>Part II</strong></td><td align=\'center\'><span style=\'padding-left:500px;\'><strong>C o m p u t a t i o n<span style="margin-right:10px;">&nbsp;</span>o f<span style="margin-right:10px;">&nbsp;</span>T a x</strong></span></td></tr></table>
					
					<table style=\'font-size:10px; background-color:#c0c0c0; border-collapse:collapse;\' width=\'100%\'>
						<tr>
							<td colspan=\'2\' align=\'center\' width=\'260px\'>Particulars</td>
							<td align=\'center\' colspan=\'2\' width=\'20px\'>Amount of Compensation</td>
							<td align=\'left\'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tax Due</td>
						</tr>
						<tr>
							<td colspan=\'2\' align=\'left\'>&nbsp;<strong>15</strong>&nbsp;&nbsp; Total Amount of Compensation</td>
							<td colspan=\'2\'><table style="border-collapse:collapse;"><tr><td width=\'20px\'><strong>15</strong></td><td style=\'border:1px solid black; background-color:white; padding:1px 0; font-size:14px;\' width=\'200px\' align=\'right\'>'.$this->moneyFormat($totalGross).'</td></tr></table></td>
							<td align=\'left\'>&nbsp;</td>
						</tr>
						<tr>
							<td colspan=\'2\' align=\'left\'><table style="border-collapse:collapse;"><tr><td><strong>&nbsp;16</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Less: Non-Taxable Compensation<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>16A</strong>&nbsp;&nbsp; Statutory Minimum Wage (MWEs)</td></tr></table></td>
							<td colspan=\'2\'><table style="border-collapse:collapse;"><tr><td width=\'20px\'><strong>16A</strong></td><td style=\'border:1px solid black; background-color:white; padding:1px 0; font-size:14px;\' width=\'200px\' align=\'right\'>'.$this->moneyFormat($totalMWE).'</td></tr></table></td>
							<td align=\'left\'>&nbsp;</td>
						</tr>
						<tr>
							<td colspan=\'2\' align=\'left\'><table style="border-collapse:collapse;"><tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>16B</strong>&nbsp;&nbsp; Holiday Pay, Overtime Pay, Night <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Shift Differential Pay, Hazard Pay <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(Minimum Wage Earner)</td></tr></table></td>
							<td colspan=\'2\'><table style="border-collapse:collapse;"><tr><td width=\'20px\'><strong>16B</strong></td><td style=\'padding:3px 0; font-size:14px; border:1px solid black; background-color:white;\' width=\'200px\' align=\'right\'>'.$this->moneyFormat($totalOT).'</td></tr></table></td>
							<td align=\'left\'>&nbsp;</td>
						</tr>
						<tr>
							<td colspan=\'2\' align=\'left\'><table style="border-collapse:collapse;"><tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>16C</strong>&nbsp;&nbsp; Other Non-Taxable Compensation</td></tr></table></td>
							<td colspan=\'2\'><table style="border-collapse:collapse;"><tr><td width=\'20px\'><strong>16C</strong></td><td style=\'padding:1px 0; font-size:14px; border:1px solid black; background-color:white;\' width=\'200px\' align=\'right\'>'.$this->moneyFormat($totalStat).'</td></tr></table></td>
							<td align=\'left\'>&nbsp;</td>
						</tr>
						<tr>
							<td colspan=\'2\' align=\'left\'>&nbsp;<strong>17</strong>&nbsp;&nbsp; Taxable Compensation</td>
							<td colspan=\'2\'><table style="border-collapse:collapse;"><tr><td width=\'20px\'><strong>&nbsp;17</strong></td><td style=\'padding:1px 0; font-size:14px; border:1px solid black; background-color:white;\' width=\'200px\' align=\'right\'>'.$this->moneyFormat($totalTaxCompensation).'</td></tr></table></td>
							<td align=\'left\'>&nbsp;</td>
						</tr>
						<tr>
							<td colspan=\'2\' align=\'left\'>&nbsp;<strong>18</strong>&nbsp;&nbsp; Tax Required to be Withheld</td>
							<td colspan=\'2\'>&nbsp;</td>
							<td align=\'left\'><table style="border-collapse:collapse;"><tr><td width=\'20px\'><strong>&nbsp;18</strong></td><td style=\'padding:1px 0; border:1px solid black; background-color:white; font-size:14px;\' width=\'200px\' align=\'right\'>'.$this->moneyFormat($totalTaxContribution['totalTaxWithheld']).'</td></tr></table></td>
						</tr>
						<tr>
							<td colspan=\'2\' align=\'left\'><table style="border-collapse:collapse;"><tr><td><strong>&nbsp;19</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Add/Less: Adjustment(from Item 26 of Section A)</td></tr></table></td>
							<td colspan=\'2\'>&nbsp;</td>
							<td align=\'left\'><table style="border-collapse:collapse;"><tr><td width=\'20px\'><strong>&nbsp;19</strong></td><td style=\'padding:1px 0; font-size:14px; border:1px solid black; background-color:white;\' width=\'200px\' align=\'right\'>&nbsp;</td></tr></table></td>
						</tr>
						<tr>
							<td colspan=\'2\' align=\'left\'>&nbsp;<strong>20</strong>&nbsp;&nbsp; Tax Required to be Withheld for Remittance</td>
							<td colspan=\'2\'>&nbsp;</td>
							<td align=\'left\'><table style="border-collapse:collapse;"><tr><td width=\'20px\'>&nbsp;<strong>20</strong></td><td style=\'padding:1px 0; font-size:14px; border:1px solid black; background-color:white;\' width=\'200px\' align=\'right\'>&nbsp;</td></tr></table></td>
						</tr>
						<tr>
							<td colspan=\'2\' align=\'left\'><table style="border-collapse:collapse;"><tr><td><strong>&nbsp;21</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Less: Tax Remitted in Return Previously Filed, <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;if this is an emended return</td></tr></table></td>
							<td colspan=\'2\'><table style="border-collapse:collapse;"><tr><td width=\'20px\'><strong>21A</strong></td><td style=\'padding:1px 0; font-size:14px; border:1px solid black; background-color:white;\' width=\'200px\' align=\'right\'>&nbsp;</td></tr></table></td>
							<td align=\'left\'>&nbsp;</td>
						</tr>
						<tr>
							<td colspan=\'2\' align=\'left\'><table style="border-collapse:collapse;"><tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Other Payments Made (please attach <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;proof of payment BIR Form No. 0605)</td></tr></table></td>
							<td colspan=\'2\'><table style="border-collapse:collapse;"><tr><td width=\'20px\'><strong>21B</strong></td><td style=\'padding:1px 0; font-size:14px; border:1px solid black; background-color:white;\' width=\'200px\' align=\'right\'>&nbsp;</td></tr></table></td>
							<td align=\'left\'>&nbsp;</td>
						</tr>
						<tr>
							<td colspan=\'4\' align=\'left\'>&nbsp;<strong>22</strong>&nbsp;&nbsp; Total Tax payments Made(Sum of Items Nos. 21A & 21B)</td>
							<td align=\'left\'><table style="border-collapse:collapse;"><tr><td width=\'20px\'><strong>22</strong></td><td style=\'padding:1px 0; font-size:14px; border:1px solid black; background-color:white;\' width=\'200px\' align=\'right\'>&nbsp;</td></tr></table></td>
						</tr>
						<tr>
							<td colspan=\'4\' align=\'left\'>&nbsp;<strong>23</strong>&nbsp;&nbsp; Tax Still Due/(Overremittance) (Item No. 20 less Item No. 22)</td>
							<td align=\'left\'><table style="border-collapse:collapse;"><tr><td width=\'20px\'><strong>23</strong></td><td style=\'padding:1px 0; font-size:14px; border:1px solid black; background-color:white;\' width=\'200px\' align=\'right\'>&nbsp;</td></tr></table></td>
						</tr>
						<tr><td colspan=\'5\'>&nbsp;<strong>24</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Add Penalties</td></tr>
						<tr>
							<td colspan="4">
								<table style="border-collapse:collapse;">
									<tr>
										<td colspan="2" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Surcharge</td>
										<td colspan="2" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Interest</td>
										<td colspan="2" align="right">Compromise&nbsp;</td>
									</tr>
									<tr>
										<td><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;24A</strong></td><td style=\'font-size:14px; padding:1px 0; border:1px solid black; background-color:white;\' width=\'121px\' align=\'right\'>&nbsp;</td>
										<td><strong>&nbsp;&nbsp;&nbsp;&nbsp;24B</strong></td><td style=\'font-size:14px; padding:1px 0; border:1px solid black; background-color:white;\' width=\'121px\' align=\'right\'>&nbsp;</td>
										<td><strong>&nbsp;&nbsp;&nbsp;&nbsp;24C</strong></td><td style=\'font-size:14px; padding:1px 0; border:1px solid black; background-color:white;\' width=\'121px\' align=\'right\'>&nbsp;</td>
									</tr>
								</table>
							</td>
							<td align=\'left\'><table style="border-collapse:collapse;"><tr><td>&nbsp;</td></tr><tr><td width=\'20px\'><strong>24D</strong></td><td style=\'padding:3px 0; border:1px solid black; background-color:white;\' width=\'200px\' align=\'right\'>&nbsp;</td></tr></table></td>
						</tr>
						<tr>
							<td colspan=\'4\' align=\'left\'>&nbsp;<strong>25</strong>&nbsp;&nbsp; Total Amount Still Due/(Overremittance)</td>
							<td align=\'left\'><table style="border-collapse:collapse;"><tr><td width=\'20px\'><strong>25</strong></td><td style=\'font-size:14px; padding:1px 0; border:1px solid black; background-color:white;\' width=\'200px\' align=\'right\'>&nbsp;</td></tr></table></td>
						</tr>
					</table>
					
					<table style=\'font-size:10px; border-bottom:2px solid black; border-top:2px solid black; background-color:#c0c0c0; border-collapse:collapse;\' width=\'100%\'><tr><td width=\'50px\'><strong>Section A</strong></td><td align=\'center\'><span style=\'padding-left:500px;\'><strong>Adjustment of Taxes Withheld on Compensation For Previous Months</strong></span></td></tr></table>
					
					<table style=\'vertical-align:top; font-size:10px; border-bottom:2px solid black; background-color:#c0c0c0; border-collapse:collapse;\' width=\'100%\'>
						<tr>
							<td width="100px" colspan=\'2\' align=\'center\' style=\'border-right:1px solid black;\'>Previous Month(s)<br>(1)<br>(MM/YYYY)</td>
							<td width="100px" colspan=\'3\' align=\'center\' style=\'border-right:1px solid black;\'>Date Paid<br>(2)<br>(MM/DD/YYYY)</td>
							<td width="100px" align=\'center\' style=\'border-right:1px solid black;\'>Bank Validation/<br>ROR No.<br>(3)</td>
							<td width="100px" align=\'center\'><br>Bank Code<br>(4)</td>
						</tr>
						<tr>
							<td width="20px" style=\'border-top:1px solid black; background-color:white;\'>&nbsp;</td>
							<td width="80px" style=\'border:1px solid black; background-color:white;\'>&nbsp;</td>
							<td width="20px" style=\'border:1px solid black; background-color:white;\'>&nbsp;</td>
							<td width="20px" style=\'border:1px solid black; background-color:white;\'>&nbsp;</td>
							<td width="60px" style=\'border:1px solid black; background-color:white;\'>&nbsp;</td>
							<td style=\'border:1px solid black; background-color:white;\'>&nbsp;</td>
							<td style=\'border-top:1px solid black; background-color:white;\'>&nbsp;</td>
						</tr>
						<tr>
							<td style=\'border-top:1px solid black; background-color:white;\'>&nbsp;</td>
							<td style=\'border:1px solid black; background-color:white;\'>&nbsp;</td>
							<td style=\'border:1px solid black; background-color:white;\'>&nbsp;</td>
							<td style=\'border:1px solid black; background-color:white;\'>&nbsp;</td>
							<td style=\'border:1px solid black; background-color:white;\'>&nbsp;</td>
							<td style=\'border:1px solid black; background-color:white;\'>&nbsp;</td>
							<td style=\'border-top:1px solid black; background-color:white;\'>&nbsp;</td>
						</tr>
						<tr>
							<td style=\'border-top:1px solid black; background-color:white;\'>&nbsp;</td>
							<td style=\'border:1px solid black; background-color:white;\'>&nbsp;</td>
							<td style=\'border:1px solid black; background-color:white;\'>&nbsp;</td>
							<td style=\'border:1px solid black; background-color:white;\'>&nbsp;</td>
							<td style=\'border:1px solid black; background-color:white;\'>&nbsp;</td>
							<td style=\'border:1px solid black; background-color:white;\'>&nbsp;</td>
							<td style=\'border-top:1px solid black; background-color:white;\'>&nbsp;</td>
						</tr>
					</table>
					
					<table style=\'font-size:10px; border-bottom:2px solid black; background-color:#c0c0c0; border-collapse:collapse;\' width=\'100%\'><tr><td width=\'50px\' colspan=\'2\'><strong>Section A(continuation)</strong></td></tr></table>
					
					<table style=\'vertical-align:top; font-size:10px; border-bottom:1px solid black; background-color:#c0c0c0; border-collapse:collapse;\' width=\'100%\'>
						<tr>
							<td align=\'center\' style=\'border-right:1px solid black;\'>Tax paid (Excluding Penalties)</td>
							<td align=\'center\' style=\'border-right:1px solid black;\'>Should Be Tax Due</td>
							<td align=\'center\' colspan=\'2\' style=\'border-bottom:1px solid black;\'>Adjustment (7)</td>
						</tr>
						<tr>
							<td align=\'center\' style=\'border-right:1px solid black;\'>for the Month<br>(5)</td>
							<td align=\'center\' style=\'border-right:1px solid black;\'>for the Month<br>(6)</td>
							<td align=\'center\' style=\'border-right:1px solid black;\' width="180px">From Current Year<br>(7a)</td>
							<td align=\'center\'>From Year-End Adjustment of the<br>Immediately Preceeding Year (7b)</td>
						</tr>
						<tr>
							<td style=\'border-top:1px solid black; background-color:white;\'>&nbsp;</td>
							<td style=\'border:1px solid black; background-color:white;\'>&nbsp;</td>
							<td style=\'border:1px solid black; background-color:white;\'>&nbsp;</td>
							<td style=\'border-top:1px solid black; background-color:white;\'>&nbsp;</td>
						</tr>
						<tr>
							<td style=\'border-top:1px solid black; background-color:white;\'>&nbsp;</td>
							<td style=\'border:1px solid black; background-color:white;\'>&nbsp;</td>
							<td style=\'border:1px solid black; background-color:white;\'>&nbsp;</td>
							<td style=\'border-top:1px solid black; background-color:white;\'>&nbsp;</td>
						</tr>
						<tr>
							<td style=\'border-top:1px solid black; background-color:white;\'>&nbsp;</td>
							<td style=\'border:1px solid black; background-color:white;\'>&nbsp;</td>
							<td style=\'border:1px solid black; background-color:white;\'>&nbsp;</td>
							<td style=\'border-top:1px solid black; background-color:white;\'>&nbsp;</td>
						</tr>
					</table>
					
					<table style=\'font-size:10px; border-bottom:2px solid black; background-color:#c0c0c0; border-collapse:collapse;\' width=\'100%\'><tr><td width=\'430px\'><strong>26</strong>&nbsp;&nbsp; Total (7a plus 7b)(To Item 19)</td><td width="200px" style="background-color:white; border-left:1px solid black; border-right:1px solid black;">&nbsp;</td><td witdh="80px">&nbsp;</td></tr></table>
					
					<table style="font-size:10px; border-bottom:2px solid black; border-collapse:collapse;" width=\'100%\'>
						<tr><td colspan=\'5\'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;We declare, under the penalties of perjury, that this return has been made in good faith, verified by us, and to the best of our knowledge and belief,<br> is true and correct, pursuant to the provisions of the National Internal Revenue Code, as amended, and the regulations issued under authority thereof.</td></tr>
						<tr>
							<td colspan=\'3\' align=\'center\' style=""><strong>27&nbsp;&nbsp;<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$gData['rep'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></strong><br>President/Vice President/Principal Officer/Accredited Tax Agent/<br>Authorized Representative/Taxpayer<br>(Signature Over Printed Name)</td>
							<td colspan=\'2\' align=\'center\' style=\'vertical-align:top;\'><strong>28&nbsp;&nbsp;<u>_____________'.$gData['treasurer'].'_____________</u></strong><br>Treasurer/Assistant Treasurer<br>(Signature Over Printed Name)</td>
						</tr>
						<tr>
							<td align=\'center\'><u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$gData['pos_rep'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u><br> Title/Position of Signatory</td>
							<td align=\'center\' colspan="2"><u>________'.$gData['tin_rep'].'________</u><br> TIN of Signatory</td>
							<td colspan=\'2\' align=\'center\' style=\'vertical-align:top;\'><u>____________'.$gData['pos_tre'].'____________</u><br>Title/Position of Signatory</td>
						</tr>
						<tr>
							<td align=\'center\'>_______________'.$gData['acc'].'_______________<br> <span style="font-size:8px;">Tax Agent Acc. No./Atty\'s Roll No.(If applicable)</span></td>
							<td align=\'center\'><u>________'.$gData['issue_date'].'_______</u><br> Date of Issuance</td>
							<td align=\'center\'><u>______'.$gData['exp_date'].'_______</u><br> Date of Expiry</td>
							<td align=\'center\' colspan="2"><u>____________'.$gData['tin_tre'].'____________</u><br> TIN of Signatory</td>
						</tr>
					</table>
					
					<table style=\'font-size:10px; background-color:#c0c0c0; border-collapse:collapse; border-bottom:2px solid black;\' width=\'100%\'>
					<tr>
						<td width=\'80%\' style=\'border-right:2px solid black;\'>
							<table style=\'border-bottom:1px solid black; border-collapse:collapse;\' width=\'600px\'><tr>
								<td><strong>Part III</strong></td>
								<td align=\'center\'><strong>D e t a i l s&nbsp;&nbsp;&nbsp;&nbsp;o f&nbsp;&nbsp;&nbsp;&nbsp;P a y m e n t</strong></td>
							</tr></table>
							<table width=\'600px\' style="border-collapse:collapse;"><tr>
								<td align=\'center\' style=\'border-right:1px solid black; border-bottom:1px solid black;\' width=\'70px\'><strong>Particulars</strong></td>
								<td align=\'center\' style=\'border-right:1px solid black; border-bottom:1px solid black;\' width=\'40px\' colspan=\'2\'><strong><strong>Drawee Bank/<br>Agency</strong></td>
								<td align=\'center\' style=\'border-right:1px solid black; border-bottom:1px solid black;\' width=\'40px\' colspan=\'2\'>Number</strong></td>
								<td align=\'center\' style=\'border-right:1px solid black; border-bottom:1px solid black;\' width=\'90px\' colspan=\'4\'><strong>Date<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MM DD YYYY</strong></td>
								<td align=\'center\' style=\'border-bottom:1px solid black;\' colspan=\'2\'><strong>Amount</strong></td>
							</tr>
							<tr>
								<td align=\'left\' style="padding-top:2px;"><strong>29</strong> Cash/Bank <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Debit Demo</td>
								<td align=\'right\' width="10px" style="vertical-align:top; padding-top:2px;"><strong>29A</strong></td><td style=\'vertical-align:center; padding-top:3px;\' width=\'100px\' align=\'right\'><table style=\'border-collapse:collapse;\'><tr><td style=\'border:1px solid black; background-color:white; padding:5px 0;\' width=\'100px\'>&nbsp;</td></tr></table></td>
								<td align=\'left\' style="vertical-align:top; padding-top:2px;"><strong>29B</strong></td><td style=\'vertical-align:center; padding-top:3px;\' width=\'90px\' align=\'left\'><table style=\'border-collapse:collapse;\'><tr><td style=\'border:1px solid black; background-color:white; padding:5px 0;\' width=\'100px\'>&nbsp;</td></tr></table></td>
								<td align=\'left\' style="vertical-align:top; padding-top:2px;"><strong>29C</strong></td><td style=\'vertical-align:center; padding-top:3px;\' colspan="3"><table style=\'border-collapse:collapse;\' width="65px"><tr><td style=\'border:1px solid black; background-color:white; padding:5px 0;\'>&nbsp;&nbsp;&nbsp;</td><td style=\'border:1px solid black; background-color:white;\'>&nbsp;&nbsp;</td><td style=\'border:1px solid black; background-color:white;\'>&nbsp;&nbsp;&nbsp;&nbsp;</td></tr></table></td>
								<td align=\'left\' style="vertical-align:top; padding-top:2px;"><strong>29D</strong></td><td style=\'vertical-align:center; padding-top:3px;\' width=\'90px\' align=\'left\'><table style=\'border-collapse:collapse;\'><tr><td style=\'border:1px solid black; background-color:white; padding:5px 0;\' width=\'165px\'>&nbsp;</td></tr></table></td>
							</tr>
							<tr>
								<td align=\'left\' style="padding-top:3px;"><strong>30</strong> Check</td>
								<td align=\'right\' width="10px" style="vertical-align:top; padding-top:3px;"><strong>30A</strong></td><td style=\'vertical-align:center; padding-top:3px;\' width=\'100px\' align=\'right\'><table style=\'border-collapse:collapse;\'><tr><td style=\'border:1px solid black; background-color:white; padding:5px 0;\' width=\'100px\'>&nbsp;</td></tr></table></td>
								<td align=\'left\' style="vertical-align:top; padding-top:3px;"><strong>30B</strong></td><td style=\'vertical-align:center; padding-top:3px;\' width=\'90px\' align=\'left\'><table style=\'border-collapse:collapse;\'><tr><td style=\'border:1px solid black; background-color:white; padding:5px 0;\' width=\'100px\'>&nbsp;</td></tr></table></td>
								<td align=\'left\' style="vertical-align:top; padding-top:3px;"><strong>30C</strong></td><td style=\'vertical-align:center; padding-top:3px;\' colspan="3"><table style=\'border-collapse:collapse;\' width="65px"><tr><td style=\'border:1px solid black; background-color:white; padding:5px 0;\'>&nbsp;&nbsp;&nbsp;</td><td style=\'border:1px solid black; background-color:white;\'>&nbsp;&nbsp;</td><td style=\'border:1px solid black; background-color:white;\'>&nbsp;&nbsp;&nbsp;&nbsp;</td></tr></table></td>
								<td align=\'left\' style="vertical-align:top; padding-top:3px;"><strong>30D</strong></td><td style=\'vertical-align:center; padding-top:3px;\' width=\'90px\' align=\'left\'><table style=\'border-collapse:collapse;\'><tr><td style=\'border:1px solid black; background-color:white; padding:5px 0;\' width=\'165px\'>&nbsp;</td></tr></table></td>
							</tr>
							<tr>
								<td align=\'left\' style="padding-top:3px;"><strong>31</strong> Others</td>
								<td align=\'right\' width="10px" style="vertical-align:top; padding-top:3px;"><strong>31A</strong></td><td style=\'vertical-align:center; padding-top:3px;\' width=\'100px\' align=\'right\'><table style=\'border-collapse:collapse;\'><tr><td style=\'border:1px solid black; background-color:white; padding:5px 0;\' width=\'100px\'>&nbsp;</td></tr></table></td>
								<td align=\'left\' style="vertical-align:top; padding-top:3px;"><strong>31B</strong></td><td style=\'vertical-align:center; padding-top:3px;\' width=\'90px\' align=\'left\'><table style=\'border-collapse:collapse;\'><tr><td style=\'border:1px solid black; background-color:white; padding:5px 0;\' width=\'100px\'>&nbsp;</td></tr></table></td>
								<td align=\'left\' style="vertical-align:top; padding-top:3px;"><strong>31C</strong></td><td style=\'vertical-align:center; padding-top:3px;\' colspan="3"><table style=\'border-collapse:collapse;\' width="65px"><tr><td style=\'border:1px solid black; background-color:white; padding:5px 0;\'>&nbsp;&nbsp;&nbsp;</td><td style=\'border:1px solid black; background-color:white;\'>&nbsp;&nbsp;</td><td style=\'border:1px solid black; background-color:white;\'>&nbsp;&nbsp;&nbsp;&nbsp;</td></tr></table></td>
								<td align=\'left\' style="vertical-align:top; padding-top:3px;"><strong>31D</strong></td><td style=\'vertical-align:center; padding-top:3px;\' width=\'90px\' align=\'left\'><table style=\'border-collapse:collapse;\'><tr><td style=\'border:1px solid black; background-color:white; padding:5px 0;\' width=\'165px\'>&nbsp;</td></tr></table></td>
							</tr>
							</table>
							
						</td>
						<td style=\'background-color:white;\'><center>Stamp of<br>Receiving Office/AAB<br>and<br>Date of Receipt<br>(RO\'s Signature/<br>Bank Teller\'s Initial)</center></td>
					</tr>
					</table>
					<table style=\'font-size:10px; border-collapse:collapse;\' width=\'100%\'>
						<tr><td style="vertical-align:top;">&nbsp;Machine Validation/Revenue Official Receipt Details (If not filed with the bank)<br><br><br><br><br><br></td></tr>
					</table>
					</td></tr></table></body></html>';
		$this->createPDF($content,$paper,$orientation,$filename);		
	}
	
	function getEmp($gData = array()){
		$comp_id = $gData['comp'];
		$month = $gData['month'];
		$year = $gData['year'];
		$qry = array();
		IF($gData['branchinfo_id']!=0){//get Location parameter.
			$qry[] = "em.branchinfo_id = '".$gData['branchinfo_id']."'";
		}
		$qry[]="em.comp_id='".$comp_id."'";	
		$qry[]="ppp.payperiod_period='".$month."'";	
		$qry[]="ppp.payperiod_period_year='".$year."'";
		//$qry[]="s.empdd_id='5'";
		//$qry[]="s.bldsched_period in ('1','2')";
		$criteria = count($qry)>0 ? " WHERE ".implode(' AND ',$qry) : '';
		$sql = "SELECT distinct em.emp_id, IF(e.pi_tin IS NULL OR e.pi_tin = '','',e.pi_tin) as pi_tin, e.pi_fname, e.pi_mname, e.pi_lname 
				FROM emp_masterfile em 
				JOIN emp_personal_info e on (e.pi_id=em.pi_id)
				JOIN payroll_paystub_report ppr on (ppr.emp_id=em.emp_id) 
				JOIN payroll_pay_stub pps on (pps.payperiod_id=ppr.payperiod_id) 
				JOIN payroll_pay_period ppp on (ppp.payperiod_id=pps.payperiod_id) 
				$criteria
				ORDER BY e.pi_lname ASC";
		$rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF){
			$arr[] = $rsResult->fields;
			$rsResult->MoveNext();
		}
		return $arr;
	}
	
	
	function verifyMWE($emp_id){
		$sql = "select bldsched_period from period_benloanduc_sched where emp_id='$emp_id' and empdd_id='5'";
		$rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF){
			if($rsResult->fields['bldsched_period'] == '2'){
				return true;
			} else {
				return false;
			}
		}
	}
	
	function formatTin($number)
	 {
	    $number = preg_replace('/[^\d]/', '', $number);
	    return substr($number, 0, 3) . '-' . substr($number, 3, 3) . '-' . substr($number, 6, 3);
	 }
	
	function get1601CExcel($gData = array()){
		$filename = '1601_'. $this->getMonthInWords($gData['month']) . '_' . $gData['year'].'.xls';
		if(clsSSS::getSettings($gData['comp'],12) && $gData['branchinfo_id'] != 0){
        	$branch_details = clsSSS::getLocationInfo($gData['branchinfo_id']);
        	$compname = $branch_details['branchinfo_name'];
        	$compadds = $branch_details['branchinfo_add'];
        	$comptinno = $branch_details['branchinfo_tin'];
        	$comptelno = $branch_details['branchinfo_tel1'];
        }ELSE{
        	$branch = $this->dbfetchCompDetails($gData['comp_id']);
        	$compname = $branch['comp_name'];
        	$compadds = $branch['comp_add'];
        	$comptinno = $branch['comp_tin'];
        	$comptelno = $branch['comp_tel'];
        }
		$objPHPExcel = new PHPExcel();
		$objClsMngeDecimal = new Application();
		$finalDecFormat = $objClsMngeDecimal->setFinalDecimalPlaces(0);
		$objReader = PHPExcel_IOFactory::createReader('Excel5');
		$objPHPExcel = $objReader->load("templates/TAX_1601C.xls");
		$objSheet = $objPHPExcel->getActiveSheet();
		//header
		$objSheet->setCellValue('C3', $this->getMonthInWords($gData['month']). ' '.$gData['year']);
		$objSheet->setCellValue('C4', $comptinno);
		$objSheet->getStyle('C4')
			    ->getNumberFormat()
			    ->setFormatCode(
			        PHPExcel_Style_NumberFormat::FORMAT_TEXT
			    );
		$objSheet->setCellValue('C5', $compname);
		$getZip = explode(",",$compadds);
		$compadds = "";
		foreach($getZip as $key => $value){
			if($key == count($getZip)-1 && is_numeric($value)){
				$zip = $value;
			} else {
				$compadds .= $value;
				$zip ="";
			}
		}
		$objSheet->setCellValue('C6', $compadds);
		$objSheet->setCellValue('I6', $zip);
		$objSheet->setCellValue('C7', $comptelno);
		
		//main content
		$emp = $this->getEmp($gData);
		$row = 10;
		if(count($emp)>0){
			foreach($emp as $key => $val){				
				//tax withheld
				$taxCompute = $this->getTaxContribution($gData['month'], $gData['year'], $val['emp_id']);
				$ppe_amount_sum += (float)$taxCompute['ppe_amount_sum'];
				
				//gross
				$emp_gross = $this->getGrossCompensation($gData['month'], $gData['year'], $val['emp_id']);
				$totalGross += $emp_gross['gross'];

				if($this->verifyMWE($val['emp_id'])){
					$statMWE = $this->getGrossMWE($gData['month'], $gData['year'], $val['emp_id']);
					$ot = $this->getEmpOvertime($gData['month'], $gData['year'], $val['emp_id']);
				} else {
					$statMWE = 0;
					$ot = 0;
				}
				$totalMWE += $statMWE;
				
				$nonTaxable = $this->getNonTaxable($gData['month'], $gData['year'], $val['emp_id'])+$statMWE+$ot;
				$taxable = $emp_gross['gross']-$nonTaxable;
			
				$totalTaxable += $taxable;
				$totalNonTaxable += (float)$nonTaxable;
		
				$tin = (empty($val['pi_tin']) ? '' : $this->formatTin($val['pi_tin']));
				
				//write data to excel here
				$objSheet->setCellValue('A'.$row, $tin);
				$objSheet->setCellValue('B'.$row, $val['pi_lname']);
				$objSheet->setCellValue('C'.$row, $val['pi_fname']);
				$objSheet->setCellValue('D'.$row, substr($val['pi_mname'],0,1));
				$objSheet->setCellValue('E'.$row, $this->moneyFormat($emp_gross['gross']));
				$objSheet->getStyle('E'.$row)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
				$objSheet->getStyle('E'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$objSheet->setCellValue('F'.$row, $this->moneyFormat($statMWE));
				$objSheet->getStyle('F'.$row)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
				$objSheet->getStyle('F'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$objSheet->setCellValue('G'.$row, $this->moneyFormat($nonTaxable));
				$objSheet->getStyle('G'.$row)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
				$objSheet->getStyle('G'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$objSheet->setCellValue('H'.$row, $this->moneyFormat($taxable));
				$objSheet->getStyle('H'.$row)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
				$objSheet->getStyle('H'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$objSheet->setCellValue('I'.$row, $this->moneyFormat($taxCompute['ppe_amount_sum']));
				$objSheet->getStyle('I'.$row)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
				$objSheet->getStyle('I'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$row++;
			}
			$row++;
			$objSheet->getStyle('A'.$row.':I'.$row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
			$objSheet->setCellValue('A'.$row, '*****NOTHING FOLLOWS*****');
			$objSheet->getStyle('A'.$row.':I'.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
			$row++;
			//total
			$styleArray = array('font' => array('bold' => true));
			$objSheet->setCellValue('A'.$row, 'TOTAL');
			$objSheet->getStyle('A'.$row)->applyFromArray($styleArray);
			
			$objSheet->setCellValue('E'.$row, $this->moneyFormat($totalGross));
			$objSheet->getStyle('E'.$row)->applyFromArray($styleArray);
			$objSheet->getStyle('E'.$row)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
			$objSheet->getStyle('E'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			
			$objSheet->setCellValue('F'.$row, $this->moneyFormat($totalMWE));
			$objSheet->getStyle('F'.$row)->applyFromArray($styleArray);
			$objSheet->getStyle('F'.$row)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
			$objSheet->getStyle('F'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			
			$objSheet->setCellValue('G'.$row, $this->moneyFormat($totalNonTaxable));
			$objSheet->getStyle('G'.$row)->applyFromArray($styleArray);
			$objSheet->getStyle('G'.$row)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
			$objSheet->getStyle('G'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			
			$objSheet->setCellValue('H'.$row, $this->moneyFormat($totalTaxable));
			$objSheet->getStyle('H'.$row)->applyFromArray($styleArray);
			$objSheet->getStyle('H'.$row)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
			$objSheet->getStyle('H'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			
			$objSheet->setCellValue('I'.$row, $this->moneyFormat($ppe_amount_sum));
			$objSheet->getStyle('I'.$row)->applyFromArray($styleArray);
			$objSheet->getStyle('I'.$row)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
			$objSheet->getStyle('I'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			
			$row = $row+3;
			
			//footer
			$objSheet->setCellValue('A'.$row, 'Certified Correct:');
			$objSheet->getStyle('A'.$row)->getFont()->setBold(true)->setSize(12);
			
			$row = $row+4;
			$objSheet->getStyle('A'.$row.':D'.$row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
			$objSheet->setCellValue('A'.$row, 'Signature Over Printed Name');
			$objSheet->getStyle('A'.$row)->getFont()->setBold(true)->setSize(12);
			
			$row = $row+2;
			$objSheet->setCellValue('A'.$row, '(Please attach to BIR Form 1601-C)');
			$objSheet->getStyle('A'.$row)->getFont()->setBold(true)->setSize(12);
			
			$row++;
			$objSheet->setCellValue('A'.$row, 'Please Take note:');
			$objSheet->getStyle('A'.$row)->getFont()->setBold(false)->setSize(12);
			
			$row++;
			$objSheet->setCellValue('A'.$row, 'Gross Compensation = Total Taxable Compensation + Total Non-Taxable Compensation');
			$objSheet->getStyle('A'.$row)->getFont()->setBold(false)->setSize(12);
			
			$row++;
			$objSheet->setCellValue('A'.$row, 'Taxable Compensation = Gross Compensation - (Staturory Minimum Wage(MWEs) + Other Non-Taxable Compensation)');
			$objSheet->getStyle('A'.$row)->getFont()->setBold(false)->setSize(12);
			
			$row++;
			$objSheet->setCellValue('A'.$row, 'Total Non-Taxable = Statutory Contribution + Bonus + Union Dues + etc');
			$objSheet->getStyle('A'.$row)->getFont()->setBold(false)->setSize(12);
			
		}
		// Rename sheet
		$objSheet->setTitle($filename);
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);
		// Redirect output to a web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename='.$filename);
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
	}
	
	function get1601CPDF1($gData = array()){
		$orientation = 'portrait';
		$unit = 'mm';
		$format = 'A4';
		$unicode = true;
		$encoding = "UTF-8";
		$filename = '1601_'.$this->getMonthInWords($gData['month']).'_'.$gData['year'].'.pdf';
		
		
		$oPDF = new clsPDF($orientation, $unit, $format, $unicode, $encoding);
		$oPDF->SetAutoPageBreak(false);
		$oPDF->SetPrintHeader(false);
		$oPDF->SetPrintFooter(false);
		
		if(clsSSS::getSettings($gData['comp'],12) && $gData['branchinfo_id'] != 0){
        	$branch_details = clsSSS::getLocationInfo($gData['branchinfo_id']);
        	$company_name = $branch_details['branchinfo_name'];
        	$company_address = $branch_details['branchinfo_add'];
        	$company_tin = $branch_details['branchinfo_tin'];
        	$company_tel = $branch_details['branchinfo_tel1'];
        }ELSE{
        	$branch = $this->dbfetchCompDetails($comp_id);
        	$company_name = $branch['comp_name'];
        	$company_address = $branch['comp_add'];
        	$company_tin = $branch['comp_tin'];
        	$company_tel = $branch['comp_tel'];
        }
		
		//set initial PDF Page
		$oPDF->AddPage();
    	
		//line format
		$style = array('width' => .5, 'cap' => 'square', 'join' => 'round', 'dash' => '0', 'phase' => 0, 'color' => array(0, 0, 0));
    	
		
		//set initial coordinates
		$coordX = 0;
		$coordY = 0;
		
		$oPDF->SetFont('times', 'B', 10);
		$oPDF->setFontSize(12);
		$oPDF->SetTextColor(11, 24, 196);
		$oPDF->Text($coordX+12.5, $coordY+16.5, "Bureau Of Internal Revenue");
		$oPDF->Text($coordX+12.5, $coordY+22.5, "For the Month:");
		$oPDF->Text($coordX+12.5, $coordY+28.6, "TIN:");
		$oPDF->Text($coordX+12.5, $coordY+34.7, "Withholding Agent's Name:");
		$oPDF->Text($coordX+12.5, $coordY+40.8, "Registered Address:");
		$oPDF->Text($coordX+156, $coordY+40.8, "Zip Code");
		$oPDF->Text($coordX+12.5, $coordY+46.9, "Telephone Number:");
		
		$oPDF->SetFillColor(197, 197, 197);
		$oPDF->MultiCell(185.5, 8, '', '', 'C', 1, 1, 12.2, 53.1, true, 0, false, false, 1);
		$oPDF->SetFont('times', 'B', 10);
		$oPDF->setFontSize(9);
		$oPDF->SetTextColor(0,0,0);
		$oPDF->Text($coordX+12.5, $coordY+58.2, "TIN");
		$oPDF->Text($coordX+36.9, $coordY+58.2, "Surname");
		$oPDF->Text($coordX+65.2, $coordY+58.2, "Given Name");
		$oPDF->Text($coordX+93.5, $coordY+58.2, "M.I.");
		$oPDF->Text($coordX+101.9, $coordY+56.4, "Gross");
		$oPDF->Text($coordX+101.9, $coordY+60.1, "Compensation");
		$oPDF->Text($coordX+126.3, $coordY+56.4, "Non-Taxable");
		$oPDF->Text($coordX+126.3, $coordY+60.1, "Compensation");
		$oPDF->Text($coordX+150.6, $coordY+56.4, "Taxable");
		$oPDF->Text($coordX+150.6, $coordY+60.1, "Compensation");
		$oPDF->Text($coordX+175.5, $coordY+58.2, "Tax Withheld");		
		
		$oPDF->SetFont('times', '', 10);
		$oPDF->setFontSize(9);
		$oPDF->Text($coordX+65, $coordY+34.2, $company_name); //company name
		$oPDF->Text($coordX+23.3, $coordY+28.1, $company_tin); //company tin
		$oPDF->Text($coordX+50.1, $coordY+39.9, $company_address); //company address
		$oPDF->Text($coordX+50.7, $coordY+46.4, $company_tel); //company address
		$oPDF->Text($coordX+42.4, $coordY+22, $this->getMonthInWords($gData['month']).'-'.$gData['year']); //for the month
		
		
		$oPDF->Line($coordX+12.2, $coordY+61.5, $coordX+197.8, $coordY+61.5, $style);
		
		$emp = $this->getEmp($gData);
		
		$temp = 1;
		$ctr = 0;
		$max = 35;
		$countemp = count($emp);
		
		if ($countemp==0) {
			$coordY = 65.2;
		$oPDF->SetFont('times', 'B', 9.3);
		$oPDF->Text($coordX+81.2, 65.2, "*****NO RECORD FOUND*****"); 
		
		$oPDF->Line($coordX+12.2, $coordY+1.5, $coordX+197.8, $coordY+1.5, $style);
		$oPDF->Text($coordX+12.5, $coordY+5, "Total"); 
		$oPDF->Text($coordX+96, $coordY+5, "PHP ".$this->moneyFormat($totalGross));
		$oPDF->Text($coordX+122.7, $coordY+5, "PHP ".$this->moneyFormat($totalNonTaxable));
		$oPDF->Text($coordX+145.1, $coordY+5, "PHP ".$this->moneyFormat($totalTaxable));
		$oPDF->Text($coordX+174.1, $coordY+5, "PHP ".$this->moneyFormat($totalGross));
		
		$oPDF->SetFontSize(12);
		$oPDF->Text($coordX+12.5, $coordY+15.2, "Certified Correct:");
		$oPDF->Line($coordX+12.5, $coordY+34, $coordX+67, $coordY+34, $style);
		$oPDF->Text($coordX+12.5, $coordY+38.3, "Signature Over Printed Name");
		$oPDF->Text($coordX+12.5, $coordY+48, "(Please attach to BIR Form 1601-C)");
		
		$oPDF->SetFont('times', 'BI', 11);
		$oPDF->Text($coordX+12.5, $coordY+57, "Please take note:");
		$oPDF->SetFont('times', 'B', 10.5);
		$oPDF->Text($coordX+12.5, $coordY+62.2, "Gross Compensation = Total Taxable Compensation + Total non-Taxable Compensation");
		$oPDF->Text($coordX+12.5, $coordY+67, "Taxable Compensation = Gross Compensation - (Statutory Minimum Wage(SMWEs)+Other Non-Taxable Compensation)");
		$oPDF->Text($coordX+12.5, $coordY+72.2, "Other Non-Taxable Compensation = Statutory Contribution + Bonus + Union Dues + etc");
		
		
		
		} else {
			$coordY=65.2;
			
			 foreach($emp as $key => $val) {
			
			 	if ($temp<=$max) {
			
			//tax withheld
			$taxCompute = $this->getTaxContribution($gData['month'], $gData['year'], $val['emp_id']);
			$ppe_amount_sum += (float)$taxCompute['ppe_amount_sum'];
			
			//gross
			$emp_gross = $this->getGrossCompensation($gData['month'], $gData['year'], $val['emp_id']);
			$totalGross += $emp_gross['gross'];
			//echo "EMP: ".$val['emp_id']."<br>";
			$statMWE = $this->getGrossMWE($gData['month'], $gData['year'], $val['emp_id']);
			//echo "<br>";
			$ot = $this->getEmpOvertime($gData['month'], $gData['year'], $val['emp_id']);
			//echo "<br>";
			//non-tax
			$nonTaxable = $this->getNonTaxable($gData['month'], $gData['year'], $val['emp_id'])+$statMWE+$ot;
			//echo "<br><br>";
			$taxable = $emp_gross['gross']-$nonTaxable;
			
			$totalTaxable += $taxable;
			$totalNonTaxable += (float)$nonTaxable;
			
			$oPDF->Text($coordX+10, $coordY, $temp);
			$oPDF->Text($coordX+14.5, $coordY, $val['pi_tin']);
			$oPDF->Text($coordX+36, $coordY, $val['pi_lname']);
			$oPDF->Text($coordX+60, $coordY, $val['pi_fname']);
			$oPDF->Text($coordX+96.5, $coordY, substr($val['pi_mname'],0,1));
			$oPDF->Text($coordX+103, $coordY, $this->moneyFormat($emp_gross['gross']));
			$oPDF->Text($coordX+130, $coordY, $this->moneyFormat($nonTaxable));
			$oPDF->Text($coordX+151, $coordY, $this->moneyFormat($taxable));
			$oPDF->Text($coordX+178, $coordY, $this->moneyFormat($taxCompute['ppe_amount_sum']));
			
			$coordY+=5;
			$temp++;
			}
			
		} //exit;
		if ($temp>$max) {
			$oPDF->AddPage();
			
			//set initial coordinates
		$coordX = 0;
		$coordY = 0;
		
		$oPDF->SetFont('times', 'B', 10);
		$oPDF->setFontSize(12);
		$oPDF->SetTextColor(11, 24, 196);
		$oPDF->Text($coordX+12.5, $coordY+16.5, "Bureau Of Internal Revenue");
		$oPDF->Text($coordX+12.5, $coordY+22.5, "For the Month:");
		$oPDF->Text($coordX+12.5, $coordY+28.6, "TIN:");
		$oPDF->Text($coordX+12.5, $coordY+34.7, "Withholding Agent's Name:");
		$oPDF->Text($coordX+12.5, $coordY+40.8, "Registered Address:");
		$oPDF->Text($coordX+156, $coordY+40.8, "Zip Code");
		$oPDF->Text($coordX+12.5, $coordY+46.9, "Telephone Number:");
		
		$oPDF->SetFillColor(197, 197, 197);
		$oPDF->MultiCell(185.5, 8, '', '', 'C', 1, 1, 12.2, 53.1, true, 0, false, false, 1);
		$oPDF->SetFont('times', 'B', 10);
		$oPDF->setFontSize(9);
		$oPDF->SetTextColor(0,0,0);
		$oPDF->Text($coordX+12.5, $coordY+58.2, "TIN");
		$oPDF->Text($coordX+36.9, $coordY+58.2, "Surname");
		$oPDF->Text($coordX+65.2, $coordY+58.2, "Given Name");
		$oPDF->Text($coordX+93.5, $coordY+58.2, "M.I.");
		$oPDF->Text($coordX+101.9, $coordY+56.4, "Gross");
		$oPDF->Text($coordX+101.9, $coordY+60.1, "Compensation");
		$oPDF->Text($coordX+126.3, $coordY+56.4, "Non-Taxable");
		$oPDF->Text($coordX+126.3, $coordY+60.1, "Compensation");
		$oPDF->Text($coordX+150.6, $coordY+56.4, "Taxable");
		$oPDF->Text($coordX+150.6, $coordY+60.1, "Compensation");
		$oPDF->Text($coordX+175.5, $coordY+58.2, "Tax Withheld");		
		
		$oPDF->SetFont('times', '', 10);
		$oPDF->setFontSize(9);
		$oPDF->Text($coordX+65, $coordY+34.2, $company_name); //company name
		$oPDF->Text($coordX+23.3, $coordY+28.1, $company_tin); //company tin
		$oPDF->Text($coordX+50.1, $coordY+39.9, $company_address); //company address
		$oPDF->Text($coordX+50.7, $coordY+46.4, $company_tel); //company address
		$oPDF->Text($coordX+42.4, $coordY+22, $this->getMonthInWords($gData['month']).'-'.$gData['year']); //for the month
		
		
			$coordY=65.2;
			
			
			for ($i=35; $i<$countemp; $i++) {
				
				//tax withheld
			$taxCompute = $this->getTaxContribution($gData['month'], $gData['year'], $emp[$i]['emp_id']);
			$ppe_amount_sum += (float)$taxCompute['ppe_amount_sum'];
	
			//gross
			$emp_gross = $this->getGrossCompensation($gData['month'], $gData['year'], $emp[$i]['emp_id']);
			$totalGross += $emp_gross['gross'];
			//echo "EMP: ".$emp[$i]['emp_id']."<br>";
			$statMWE = $this->getGrossMWE($gData['month'], $gData['year'], $emp[$i]['emp_id']);
			//echo "<br>";
			$ot = $this->getEmpOvertime($gData['month'], $gData['year'], $emp[$i]['emp_id']);
			//echo "<br>";
			//non-tax
			$nonTaxable = $this->getNonTaxable($gData['month'], $gData['year'], $emp[$i]['emp_id'])+$statMWE+$ot;
			//echo "<br><br>";
			$taxable = $emp_gross['gross']-$nonTaxable;

			$totalTaxable += $taxable;
			$totalNonTaxable += (float)$nonTaxable;
			
				
				
				$oPDF->Text($coordX+10, $coordY, $temp);
				$oPDF->Text($coordX+14.5, $coordY, $emp[$i]['pi_tin']);
				$oPDF->Text($coordX+36, $coordY, $emp[$i]['pi_lname']);
				$oPDF->Text($coordX+60, $coordY, $emp[$i]['pi_fname']);
				$oPDF->Text($coordX+96.5, $coordY, substr($emp[$i]['pi_mname'],0,1));
				$oPDF->Text($coordX+103, $coordY, $this->moneyFormat($emp_gross['gross']));
				$oPDF->Text($coordX+130, $coordY, $this->moneyFormat($nonTaxable));
				$oPDF->Text($coordX+151, $coordY, $this->moneyFormat($taxable));
				$oPDF->Text($coordX+178, $coordY, $this->moneyFormat($taxCompute['ppe_amount_sum']));
				
				$coordY+=5;
				$temp++;
				
			}
		}
				
		
		//$oPDF->Line($coordX+12.2, $coordY+61.5, $coordX+197.8, $coordY+61.5, $style);
		
		$oPDF->Line($coordX+12.2, $coordY+1.5, $coordX+197.8, $coordY+1.5, $style);
		$oPDF->Text($coordX+12.5, $coordY+5, "Total"); 
		$oPDF->Text($coordX+96, $coordY+5, "PHP ".$this->moneyFormat($totalGross));
		$oPDF->Text($coordX+122.7, $coordY+5, "PHP ".$this->moneyFormat($totalNonTaxable));
		$oPDF->Text($coordX+145.1, $coordY+5, "PHP ".$this->moneyFormat($totalTaxable));
		$oPDF->Text($coordX+174.1, $coordY+5, "PHP ".$this->moneyFormat($ppe_amount_sum));
		
		$oPDF->SetFontSize(12);
		$oPDF->Text($coordX+12.5, $coordY+15.2, "Certified Correct:");
		$oPDF->Line($coordX+12.5, $coordY+34, $coordX+67, $coordY+34, $style);
		$oPDF->Text($coordX+12.5, $coordY+38.3, "Signature Over Printed Name");
		$oPDF->Text($coordX+12.5, $coordY+48, "(Please attach to BIR Form 1601-C)");
		
		$oPDF->SetFont('times', 'BI', 11);
		$oPDF->Text($coordX+12.5, $coordY+57, "Please take note:");
		$oPDF->SetFont('times', 'B', 10.5);
		$oPDF->Text($coordX+12.5, $coordY+62.2, "Gross Compensation = Total Taxable Compensation + Total non-Taxable Compensation");
		$oPDF->Text($coordX+12.5, $coordY+67, "Taxable Compensation = Gross Compensation - (Statutory Minimum Wage(SMWEs)+Other Non-Taxable Compensation)");
		$oPDF->Text($coordX+12.5, $coordY+72.2, "Other Non-Taxable Compensation = Statutory Contribution + Bonus + Union Dues + etc");
		
			
		
		
		}
		
		
		
		
		
		
		//get the PDF output
		$output = $oPDF->Output($filename);
		
		if (!empty($output)){
			return $output;
		}
		return false;
		}
	
	function get1601CPDF($gData = array()){
		$paper = 'A4';
		$orientation = 'portrait';
		$filename = '1601_'.$this->getMonthInWords($gData['month']).'_'.$gData['year'].'.pdf';
		$comp_id = $gData['comp'];
		IF($gData['branchinfo_id']!=0){
        	$branch_details = clsSSS::getLocationInfo($gData['branchinfo_id']);
        	$compname = $branch_details['branchinfo_name'];
        	$compadds = $branch_details['branchinfo_add'];
        	$comptinno = $branch_details['branchinfo_tin'];
        	$comptelno = $branch_details['branchinfo_tel1'];
        }ELSE{
        	$branch = $this->dbfetchCompDetails($comp_id);
        	$compname = $branch['comp_name'];
        	$compadds = $branch['comp_add'];
        	$comptinno = $branch['comp_tin'];
        	$comptelno = $branch['comp_tel'];
        }
		$getZip = explode(",",$compadds);
		
		$compadds = "";
		foreach($getZip as $key => $value){
			if($key == count($getZip)-1 && is_numeric($value)){
				$zip = $value;
			} else {
				$compadds .= $value;
				$zip ="&nbsp;";
			}
		}
		$emp = $this->getEmp($gData);
		
		$temp = 1;
		$ctr = 0;
		$content = '';
		$max = 35;
		
		
		if(count($emp)>0){
			foreach($emp as $key => $val){
				
				//tax withheld
				$taxCompute = $this->getTaxContribution($gData['month'], $gData['year'], $val['emp_id']);
				$ppe_amount_sum += (float)$taxCompute['ppe_amount_sum'];
				
				//gross
				$emp_gross = $this->getGrossCompensation($gData['month'], $gData['year'], $val['emp_id']);
				$totalGross += $emp_gross['gross'];
				
				//non-tax
				$nonTaxable = $this->getNonTaxable($gData['month'], $gData['year'], $val['emp_id']);
				
				$totalNonTaxable += (float)$nonTaxable;
				if($this->verifyMWE($val['emp_id'])){
					$statMWE = $this->getGrossMWE($gData['month'], $gData['year'], $val['emp_id']);
				} else {
					$statMWE = 0;
				}
				$totalMWE += $statMWE;
				
				$taxable = $emp_gross['gross']-$statMWE-$nonTaxable;
				if($taxable > 0){
					$totalTaxable += $taxable;
				}
				$tin = (empty($val['pi_tin']) ? "&nbsp;" : $this->formatTin($val['pi_tin']));
				$header = '<table style="border-collapse:collapse; font:12px Times;">
					<tr><td>
						<table style="border-collapse:collapse;"><tr><td style="font:bold 18px Times; color:#00009D;">Bureau Of Internal Revenue</td></tr></table>
					</td></tr>
					<tr><td>
						<table style="border-collapse:collapse;"><tr><td style="font:bold 14px Times; color:#00009D;" width="140px"><em>Registered Name:</em></td><td style="font:bold 16px Times;">'.$compname.'</td></tr></table>
					</td></tr>
					<tr><td>
						<table style="border-collapse:collapse;"><tr>
							<td style="font:bold 14px Times; color:#00009D; vertical-align:top;" width="140px"><em>Registered Address:</em></td>
							<td width="300px" style="vertical-align:top; font:14px Times;">'.$compadds.'</td>
							<td width="80px">&nbsp;</td>
							<td style="font:bold 14px Times; color:#00009D;" width="100px"><em>Zip Code</em></td>
							<td style="font:14px Times;">'.$zip.'</td>
						</tr></table>
					</td></tr>
					<tr><td>
						<table style="border-collapse:collapse;"><tr><td style="font:bold 14px Times; color:#00009D;" width="140px"><em>Telephone Number:</em></td><td style="font:14px Times;">'.$comptelno.'</td></tr></table>
					</td></tr>
					<tr><td>
						<table style="border-collapse:collapse;"><tr><td style="font:bold 14px Times; color:#00009D;" width="140px"><em>TIN:</em></td><td style="font:14px Times;">'.$comptinno.'</td></tr></table>
					</td></tr>
					<tr><td>
						<table style="border-collapse:collapse;"><tr><td style="font:bold 14px Times; color:#00009D;" width="140px"><em>For the Month:</em></td><td style="font:14px Times;">'.$this->getMonthInWords($gData['month']).' '.$gData['year'].'</td></tr></table>
					</td></tr>
					<tr><td>&nbsp;</td></tr>
					<tr><td>
						<table style="border-collapse:collapse;">
							<tr>
								<td width="20px" style="background-color:#c0c0c0;">&nbsp;</td>
								<td width="80px" style="background-color:#c0c0c0;"><strong>TIN</strong></td>
								<td width="75px" style="background-color:#c0c0c0;"><strong>Surname</strong></td>
								<td width="80px" style="background-color:#c0c0c0;"><strong>Given Name</strong></td>
								<td width="25px" style="background-color:#c0c0c0;"><strong>M.I.</strong></td>
								<td width="90px" style="background-color:#c0c0c0;"><strong>Gross Compensation</strong></td>
								<td width="90px" style="background-color:#c0c0c0;"><strong>Staturory Minimum Wage(MWEs)</strong></td>
								<td width="90px" style="background-color:#c0c0c0;"><strong>Other Non-Taxable Compensation</strong></td>
								<td width="92px" style="background-color:#c0c0c0;"><strong>Taxable Compensation</strong></td>
								<td style="background-color:#c0c0c0;"><strong>Tax Withheld</strong></td>
							</tr></table>';
					$body =	'<table style="border-collapse:collapse;font:12px;"><tr>
								<td width="20px">'.$temp.'</td>
								<td width="80px">'.$tin.'</td>
								<td width="75px">'.$val['pi_lname'].'</td>
								<td width="80px">'.$val['pi_fname'].'</td>
								<td width="25px">'.substr($val['pi_mname'],0,1).'.</td>
								<td width="90px">'.$this->moneyFormat($emp_gross['gross']).'</td>
								<td width="90px">'.$this->moneyFormat($statMWE).'</td>
								<td width="90px">'.$this->moneyFormat($nonTaxable).'</td>
								<td width="94px">'.$this->moneyFormat($taxable).'</td>
								<td>'.$this->moneyFormat($taxCompute['ppe_amount_sum']).'</td>
							</tr>
							</table>';
					$total ='</td></tr>
					<tr><td style="border-top:2px solid black; border-bottom:2px solid black;">
						<table style="border-collapse:collapse;">
							<tr><td>*****NOTHING FOLLOWS*****</td></tr>
						</table>
					</td></tr>
					<tr><td>
						<table style="border-collapse:collapse;">
							<tr>
								<td width="280px"><strong>Total</strong></td>
								<td width="92px"><strong>'.$this->moneyFormat($totalGross).'</strong></td>
								<td width="82px"><strong>'.$this->moneyFormat($totalMWE).'</strong></td>
								<td width="97px"><strong>'.$this->moneyFormat($totalNonTaxable).'</strong></td>
								<td width="93px"><strong>'.$this->moneyFormat($totalTaxable).'</strong></td>
								<td><strong>'.$this->moneyFormat($ppe_amount_sum).'</strong></td>
							</tr>
						</table>
					</td></tr>';
					$footer = '<tr><td>&nbsp;</td></tr>
					<tr><td>
						<table style="border-collapse:collapse;">
							<tr><td style="font:bold 16px;">Certified Correct:</td></tr>
							<tr><td>&nbsp;</td></tr>
							<tr><td>&nbsp;</td></tr>
							<tr><td style="border-bottom:2px solid black;" width="50px">&nbsp;</td></tr>
							<tr><td style="font:bold 16px;">Signature Over Printed Name</td></tr>
							<tr><td>&nbsp;</td></tr>
							<tr><td style="font:bold 16px;">(Please attach to BIR Form 1601-C)</td></tr>
							<tr><td>&nbsp;</td></tr>
							<tr><td style="font:14px;"><em>Please Take note:</em></td></tr>
							<tr><td style="font:12px;">Gross Compensation = Total Taxable Compensation + Total Non-Taxable Compensation</td></tr>
							<tr><td style="font:12px;">Taxable Compensation = Gross Compensation - (Staturory Minimum Wage(MWEs) + Other Non-Taxable Compensation)</td></tr>
							<tr><td style="font:12px;">Other Non-Taxable Compensation = Statutory Contribution + Bonus + Union Dues + etc</td></tr>
						</table>
					</td></tr>
					<tr><td>&nbsp;</td></tr>
					<tr><td>&nbsp;</td></tr>
				</table>';
				if($ctr == 0){
					$content .= $header;
					$content .= $body;
					$ctr++;
				} else {
					$content .= $body;
					$ctr++;
					if($temp == count($emp)){
						$content .= $total;
					}
					if($ctr == $max or $temp == count($emp)){
						$content .= $footer;
						$ctr=0;
					}
				}
			$temp++;
			}
		} else {
			$content = '<table style="border-collapse:collapse; font:12px Times;">
					<tr><td>
						<table style="border-collapse:collapse;"><tr><td style="font:bold 16px Times; color:#00009D;">Bureau Of Internal Revenue</td></tr></table>
					</td></tr>
					<tr><td>
						<table style="border-collapse:collapse;"><tr><td style="font:bold 16px Times; color:#00009D;">For the Month:</td><td>&nbsp;</td><td>'.$this->getMonthInWords($gData['month']).'-'.$gData['year'].'</td></tr></table>
					</td></tr>
					<tr><td>
						<table style="border-collapse:collapse;"><tr><td style="font:bold 16px Times; color:#00009D;">TIN:</td><td>&nbsp;</td><td>'.$comptinno.'</td></tr></table>
					</td></tr>
					<tr><td>
						<table style="border-collapse:collapse;"><tr><td style="font:bold 16px Times; color:#00009D;">Withholding Agent\'s Name:</td><td>&nbsp;</td><td>'.$compname.'</td></tr></table>
					</td></tr>
					<tr><td>
						<table style="border-collapse:collapse;"><tr>
							<td style="font:bold 16px Times; color:#00009D; vertical-align:top;" width="140px">Registered Address:</td>
							<td width="300px" style="vertical-align:top;">'.$compadds.'</td>
							<td width="80px">&nbsp;</td>
							<td style="font:bold 16px Times; color:#00009D;" width="100px">Zip Code</td>
							<td>'.$branch['comp_zipcode'].'</td>
						</tr></table>
					</td></tr>
					<tr><td>
						<table style="border-collapse:collapse;"><tr><td style="font:bold 16px Times; color:#00009D;">Telephone Number:</td><td>&nbsp;</td><td>'.$comptelno.'</td></tr></table>
					</td></tr>
					<tr><td>&nbsp;</td></tr>
					<tr><td>
						<table style="border-collapse:collapse;">
							<tr>
								<td width="90px" style="background-color:#c0c0c0;"><strong>TIN</strong></td>
								<td width="105px" style="background-color:#c0c0c0;"><strong>Surname</strong></td>
								<td width="105px" style="background-color:#c0c0c0;"><strong>Given Name</strong></td>
								<td width="30px" style="background-color:#c0c0c0;"><strong>M.I.</strong></td>
								<td width="90px" style="background-color:#c0c0c0;"><strong>Gross Compensation</strong></td>
								<td width="90px" style="background-color:#c0c0c0;"><strong>Non-Taxable Compensation</strong></td>
								<td width="92px" style="background-color:#c0c0c0;"><strong>Taxable Compensation</strong></td>
								<td style="background-color:#c0c0c0;"><strong>Tax Withheld</strong></td>
							</tr></table>
					</td></tr>
					<tr><td style="border-top:2px solid black; border-bottom:2px solid black;">
						<table style="border-collapse:collapse;" align="center">
							<tr><td><strong>*****NO RECORD FOUND*****<strong></td></tr>
						</table>
					</td></tr>
					<tr><td>
						<table style="border-collapse:collapse;">
							<tr>
								<td width="330px"><strong>Total</strong></td>
								<td width="90px"><strong>PHP 0.00;</strong></td>
								<td width="90px"><strong>PHP 0.00</strong></td>
								<td width="93px"><strong>PHP 0.00</strong></td>
								<td><strong>PHP 0.00</strong></td>
							</tr>
						</table>
					</td></tr>
					<tr><td>&nbsp;</td></tr>
					<tr><td>
						<table style="border-collapse:collapse;">
							<tr><td style="font:bold 16px;">Certified Correct:</td></tr>
							<tr><td>&nbsp;</td></tr>
							<tr><td>&nbsp;</td></tr>
							<tr><td>&nbsp;</td></tr>
							<tr><td style="border-bottom:2px solid black;" width="50px">&nbsp;</td></tr>
							<tr><td style="font:bold 16px;">Signature Over Printed Name</td></tr>
							<tr><td>&nbsp;</td></tr>
							<tr><td style="font:bold 16px;">(Please attach to BIR Form 1601-C)</td></tr>
							<tr><td>&nbsp;</td></tr>
							<tr><td style="font:bold 14px;"><em>Please Take note:</em></td></tr>
							<tr><td style="font:bold 14px;">Gross Compensation = Total Taxable Compensation + Total Non-Taxable Compensation</td></tr>
							<tr><td style="font:bold 14px;">Taxable Compensation = Gross Compensation - (Staturory Minimum Wage(MWEs) + Other Non-Taxable Compensation)</td></tr>
							<tr><td style="font:bold 14px;">Other Non-Taxable Compensation = Statutory Contribution + Bonus + Union Dues + etc</td></tr>
						</table>
					</td></tr>
					<tr><td>&nbsp;</td></tr>
					<tr><td>&nbsp;</td></tr>
					<tr><td>&nbsp;</td></tr>
					<tr><td>&nbsp;</td></tr>
				</table>';
		}
//		echo $content;
//		exit;
		$this->createPDF($content, $paper, $orientation, $filename);
	}
	
	function get2316FormPDF($gData = array()){
		$paper = 'legal';
		$orientation = 'portrait';
		$filename = 'Form2316_'.$this->getMonthInWords($gData['month']).'_'.$gData['year'].'.xls';
		$content = 	'<html><body>
				<style>
					.textbox{
						border:1px solid black;
						background-color:white;
					}
				</style>
				<table width="690px" style="border-collapse:collapse; font-size:9px; font-family:Helvetica;" align="left">
				<tr><td style="border:2px solid black;"><table width="690px"><tr>
					<td><table style="border-collapse:collapse;">
						<tr><td><table style="border-collapse:collapse;"><tr>
							<td width="60px"><img src="'.SYSCONFIG_CLASS_PATH.'util/dompdf/images/pdf_report/BIR-Logo.gif" style="width:50px; height:50px; margin:5px;"></td>
							<td>Republika ng Pilipinas<br>Kagawaran ng Pananalapi<br>Kawanihan ng Rentas Internas</td>
						</tr></table></td></tr>
						<tr><td colspan="2" style="font-family:Times; font-size:8px;">For Compensation Payment With or Without Tax Withheld</td></tr>
					</table></td>
					<td><table style="font-size:18px; vertical-align:center; border-collapse:collapse;">
						<tr><td>&nbsp;</td></tr>
						<tr><td align="center">Certificate of Compensation</td></tr>
						<tr><td align="center">Payment Tax Withheld</td></tr>
					</table></td>
					<td><table style="border-collapse:collapse;">
						<tr><td>BIR Form No.n</td></tr>
						<tr><td style="font-size:35px;">2316</td></tr>
						<tr><td>October 2002(ENCS)</td></tr>
					</table></td>
				</tr></table></td></tr>
				
				<tr><td style="border:2px solid black; background-color:#c0c0c0;"><table style="border-collapse:collapse;">
					<tr>
						<td style="border-right:2px solid black;" width="352px"><table style="border-collapse:collapse;"><tr>
							<td>1</td>
							<td width="50px">For the Year</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>1</td>
							<td class="textbox" width="70px" align="center">2007</td>
						</tr>
						<tr><td width="50px" colspan="7">&nbsp;&nbsp;&nbsp;&nbsp;(YYYY)</td></tr>
						</table>
						</td>
						<td><table style="border-collapse:collapse;"><tr>
							<td>2</td>
							<td width="50px">For the Period</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td width="50px" colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;From (MM/DD)</td>
							<td class="textbox"width="70px" align="center">01/01</td>
							<td width="50px">&nbsp;&nbsp;&nbsp;&nbsp;To (MM/DD)</td>
							<td class="textbox" width="70px" align="center">12/31</td>
						</tr>
						</table></td>
					</tr>
				</table></td></tr>
				
				<tr><td style="border:2px solid black;"><table style="border-collapse:collapse;">
					<tr>
						<td style="border-right:2px solid black;" width="352px"><strong>
							<table style="border-collapse:collapse;"><tr><td width="120px">Part I</td><td>Employee Information</td></tr></table>
						</strong></td>
						<td width="345px"><strong>
							<table style="border-collapse:collapse;"><tr><td width="50px">Part IV</td><td style="font-size:8px;">Details of Compensation Income and Tax Withheld from Present Employer</td></tr></table>
						</strong></td>
					</tr>
				</table></td></tr>

				<tr>
					<td style="border:2px solid black; background-color:#c0c0c0;"><table style="border-collapse:collapse;">
					<tr>
						<td style="border-right:2px solid black;" width="352px">
							<table style="border-collapse:collapse;">
								<tr>
									<td style="border-bottom:2px solid black;"><table style="border-collapse:collapse;"><tr>
										<td width="5px">3</td>
										<td width="50px">Taxpayer\'s Identification No.</td>
										<td width="5px">3</td>
										<td class="textbox" width="180px">115 678 923</td>
									</tr></table></td>
								</tr>
								
								<tr>
									<td style="border-bottom:2px solid black;"><table style="border-collapse:collapse;">
										<tr>
											<td width="5px">4</td>
											<td width="110px">Employee\'s Name (Last Name, First Name, Middle Name)</td>
											<td width="5px">5</td>
											<td width="10px">RDO Code</td>
										</tr>
										<tr>
											<td>&nbsp;</td>
											<td class="textbox">BORJA, ABIGAIL</td>
											<td>&nbsp;</td>
											<td class="textbox">&nbsp;</td>
										</tr>
										<tr>
											<td>6</td>
											<td>Registered Address</td>
											<td>6A</td>
											<td>ZIP Code</td>
										</tr>
										<tr>
											<td>&nbsp;</td>
											<td class="textbox">2148 KARAPATAN STREET, STA. CRUZ, MANILA</td>
											<td>&nbsp;</td>
											<td class="textbox">&nbsp;</td>
										</tr>
										<tr>
											<td>6B</td>
											<td>Local Home Address</td>
											<td>6C</td>
											<td>ZIP Code</td>
										</tr>
										<tr>
											<td>&nbsp;</td>
											<td class="textbox">2148 KARAPATAN STREET, STA. CRUZ, MANILA</td>
											<td>&nbsp;</td>
											<td class="textbox">&nbsp;</td>
										</tr>
										<tr>
											<td>6D</td>
											<td>Foreign Address</td>
											<td>6E</td>
											<td>ZIP Code</td>
										</tr>
										<tr>
											<td>&nbsp;</td>
											<td class="textbox">&nbsp;</td>
											<td>&nbsp;</td>
											<td class="textbox">&nbsp;</td>
										</tr>
									</table></td>
								</tr>
								<tr><td width="345px"><table style="border-collapse:collapse;">
									<tr>
										<td width="10px">9</td>
										<td width="200px">Date of Birth(MM/DD/YYYY)</td>
										<td width="10px">8</td>
										<td>Telephone Number</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
										<td width="200px" class="textbox">04-16-1961</td>
										<td>&nbsp;</td>
										<td class="textbox" width="110px">&nbsp;</td>
									</tr>									
								</table></td></tr>
								<tr><td style="border-top:2px solid black;">
									<table style="border-collapse:collapse;">
										<tr>
											<td width="10px">9</td>
											<td colspan="8">Exemption Status</td>
										</tr>
										<tr>
											<td width="10px">&nbsp;</td>
											<td width="15px" class="textbox" align="center">&nbsp;</td>
											<td>Single</td>
											<td width="10px">&nbsp;</td>
											<td width="15px" class="textbox" align="center">&nbsp;</td>
											<td>Head of the Family</td>
											<td width="10px">&nbsp;</td>
											<td width="15px" class="textbox" align="center">X</td>
											<td>Married</td>
										</tr>
									</table>
								</td></tr>
								<tr><td width="345px">
								<table style="border-collapse:collapse;">
										<tr>
											<td width="10px">9A</td>
											<td colspan="6" style="font-size:8px;">Is the wife claiming the additional exemption for qualified dependent children?</td>
										</tr>
										<tr><td colspan="7"><table><tr><td>
											<td width="57px">&nbsp;</td>
											<td width="15px" class="textbox" align="center">&nbsp;</td>
											<td width="40px">Yes</td>
											<td width="15px" class="textbox" align="center">X</td>
											<td>No</td>
											<td width="100px">&nbsp;</td>
										</td></tr></table></tr>
									</table>
								</td></tr>
								<tr><td width="345px" style="border-top:2px solid black;">
									<table style="border-collapse:collapse;">
										<tr>
											<td>10</td>
											<td>Name of Qualified Dependent Children</td>
											<td>11</td>
											<td>Date of Birth (MM/DD/YYYY)</td>
										</tr>
										<tr>
											<td>&nbsp;</td>
											<td class="textbox">&nbsp;</td>
											<td>&nbsp;</td>
											<td class="textbox">&nbsp;</td>
										</tr>
										<tr>
											<td>&nbsp;</td>
											<td class="textbox">&nbsp;</td>
											<td>&nbsp;</td>
											<td class="textbox">&nbsp;</td>
										</tr>
										<tr>
											<td>&nbsp;</td>
											<td class="textbox">&nbsp;</td>
											<td>&nbsp;</td>
											<td class="textbox">&nbsp;</td>
										</tr>
										<tr>
											<td>&nbsp;</td>
											<td class="textbox">&nbsp;</td>
											<td>&nbsp;</td>
											<td class="textbox">&nbsp;</td>
										</tr>
									</table>
								</td></tr>
								
								<tr><td>
									<table>
										<tr>
											<td>12</td>
											<td colspan="3">Other Dependent(to be accomplished if taxpayer is head of the family)</td>
										</tr>
										<tr>
											<td>&nbsp;</td>
											<td align="center">Name of Dependent</td>
											<td align="center">Relationship</td>
											<td align="center">Date of Birth<br>(MM/DD/YYYY)</td>
										</tr>
										<tr>
											<td>&nbsp;</td>
											<td align="center" class="textbox">&nbsp;</td>
											<td align="center" class="textbox">&nbsp;</td>
											<td align="center" class="textbox">&nbsp;</td>
										</tr>										
									</table>
								</td></tr>
							</table>
						</td>
						<td width="330px"><table>
							<tr>
								<td><strong>A. Non Taxable/Exempt Compensation Income</strong></td>
							</tr>
							<tr>
								<td><table><tr>
									<td width="15px">25</td>
									<td width="150px">13th Month Pay and Other Benefits</td>
									<td width="15px">25</td>
									<td class="textbox" align="right" width="150px">0.00</td>
								</tr></table></td>
							</tr>
							<tr>
								<td><table><tr>
									<td width="15px">26</td>
									<td width="150px">SSS, GSIS, PHIC & Pag-ibig Contributions & Union dues</td>
									<td width="15px">26</td>
									<td class="textbox" align="right" width="150px">558.30</td>
								</tr></table></td>
							</tr>	
							<tr>
								<td><table><tr>
									<td width="15px">27</td>
									<td width="150px">Salaries & Other Forms of Compensation</td>
									<td width="15px">27</td>
									<td class="textbox" align="right" width="150px">0.00</td>
								</tr></table></td>
							</tr>
							<tr>
								<td><table><tr>
									<td width="15px">28</td>
									<td width="150px">Total Non-Taxable/Exempt Compensation Income</td>
									<td width="15px">28</td>
									<td class="textbox" align="right" width="150px">558.30</td>
								</tr></table></td>
							</tr>
							<tr>
								<td><strong>B. Taxable Compensation Income</strong></td>
							</tr>
							<tr>
								<td><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;REGULAR</strong></td>
							</tr>
							<tr>
								<td><table><tr>
									<td width="15px">29</td>
									<td width="150px">Basic Salary</td>
									<td width="15px">29</td>
									<td class="textbox" align="right" width="150px">8491.70</td>
								</tr></table></td>
							</tr>
							<tr>
								<td><table><tr>
									<td width="15px">30</td>
									<td width="150px">Representation</td>
									<td width="15px">30</td>
									<td class="textbox" align="right" width="150px">0.00</td>
								</tr></table></td>
							</tr>
							<tr>
								<td><table><tr>
									<td width="15px">31</td>
									<td width="150px">Transportation</td>
									<td width="15px">31</td>
									<td class="textbox" align="right" width="150px">0.00</td>
								</tr></table></td>
							</tr>
							<tr>
								<td><table><tr>
									<td width="15px">32</td>
									<td width="150px">Cost of Living Allowance</td>
									<td width="15px">32</td>
									<td class="textbox" align="right" width="150px">0.00</td>
								</tr></table></td>
							</tr>
							<tr>
								<td><table><tr>
									<td width="15px">33</td>
									<td width="150px">Fixed Housing Allowance</td>
									<td width="15px">33</td>
									<td class="textbox" align="right" width="150px">0.00</td>
								</tr></table></td>
							</tr>
							<tr>
								<td><table><tr>
									<td width="15px">34</td>
									<td width="150px" colspan="3">Others(Specify)</td>
								</tr></table></td>
							</tr>
							<tr>
								<td><table><tr>
									<td width="15px">34A</td>
									<td class="textbox" align="center" width="142px">Others(Regular)</td>
									<td width="15px">34A</td>
									<td class="textbox" align="right" width="150px">1000.00</td>
								</tr></table></td>
							</tr>			
							<tr>
								<td><table><tr>
									<td width="15px">34B</td>
									<td class="textbox" align="center" width="142px">&nbsp</td>
									<td width="15px">34B</td>
									<td class="textbox" align="right" width="150px">0.00</td>
								</tr></table></td>
							</tr>		
						</table></td>
					</tr>
				</table></td>
				
				</tr>
				<tr><td style="border-right:2px solid black; border-bottom:2px solid black;"><table style="border-collapse:collapse;">
					<tr>
						<td style="border:2px solid black; background-color:#c0c0c0;" width="352px"><strong>
							<table style="border-collapse:collapse; background-color:white;" width="352px">
								<tr><td width="90px">Part II</td><td>Employer Information (Present)</td></tr>
							</table>
						</strong>
							<table style="border-collapse:collapse;" width="200px">
								<tr><td style="border-top:2px solid black;" width="250px"></td></tr>
								<tr><td><table><tr>
									<td width="5px" style="vertical-align:top;">13</td>
									<td width="80px">Taxpayer <br>Identification No.</td>
									<td width="10px" style="vertical-align:top;">13 </td>
									<td style="border:2px solid black; background-color:white;" width="220px">&nbsp;</td>
								</tr></table></td></tr>
								<tr><td style="border-top:2px solid black;" width="250px"></td></tr>
								<tr><td>
									<table>
										<tr>
											<td width="5px">14</td>
											<td>Employer\'s Name</td>
										</tr>
										<tr>
											<td>&nbsp;</td>
											<td style="border:1px solid black; background-color:white;" width="320px">Creative Software</td>
										</tr>
									</table>
								</td></tr>
								<tr><td style="border-top:2px solid black;" width="250px"></td></tr>
								<tr><td>
									<table>
										<tr>
											<td width="5px">15</td>
											<td>Registered Address</td>
											<td width="5px">&nbsp;</td>
											<td width="5px">15A</td>
											<td width="50px">Zip Code</td>
										</tr>
										<tr>
											<td>&nbsp;</td>
											<td style="border:1px solid black; background-color:white;" width="220px">Unit 605 Jafer Building No.19 Eisenhower</td>
											<td>&nbsp;</td>
											<td>&nbsp;</td>
											<td style="border:1px solid black; background-color:white;">1504</td>
										</tr>
									</table>
								</td></tr>
								<tr><td style="border-top:2px solid black;" width="250px"></td></tr>
								<tr><td>
									<table><tr>
										<td width="50px">&nbsp;</td>
										<td style="border:1px solid black; background-color:white;" width="15px" align="center">X</td>
										<td width="5px">&nbsp;</td>
										<td width="50px">main employer</td>
										<td style="border:1px solid black; background-color:white;" width="15px" align="center">&nbsp;</td>
										<td width="50px">secondary employer</td>
									</tr></table>
								</td></tr>
							</table>
						</td>
						<td width="360px" colspan="2" style="background-color:#c0c0c0;">
							<table style="border-collapse:collapse;"><tr>
								<td><table><tr><td><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SUPPLEMENTARY</strong></td></tr></table></td>
							</tr>
							<tr>
								<td><table><tr>
									<td width="15px">35</td>
									<td width="153px">Commission</td>
									<td width="15px">35</td>
									<td class="textbox" align="right" width="150px">0.00</td>
								</tr></table></td>
							</tr>		
							<tr>
								<td><table><tr>
									<td width="15px">36</td>
									<td width="153px">Profit Sharing</td>
									<td width="15px">36</td>
									<td class="textbox" align="right" width="150px">0.00</td>
								</tr></table></td>
							</tr>
							<tr>
								<td><table><tr>
									<td width="15px">37</td>
									<td width="153px">Fees Including Director\'s Fees</td>
									<td width="15px">37</td>
									<td class="textbox" align="right" width="150px">0.00</td>
								</tr></table></td>
							</tr>
							<tr>
								<td><table><tr>
									<td width="15px">38</td>
									<td width="153px">Taxable 13th Month Pay<br>and Other Benefits</td>
									<td width="15px">38</td>
									<td class="textbox" align="right" width="150px">0.00</td>
								</tr></table></td>
							</tr>
							<tr>
								<td><table><tr>
									<td width="15px">39</td>
									<td width="153px">Hazard Pay</td>
									<td width="15px">39</td>
									<td class="textbox" align="right" width="150px">0.00</td>
								</tr></table></td>
							</tr>
							<tr>
								<td><table><tr>
									<td width="15px">40</td>
									<td width="142px" colspan="3">Others (Specify)</td>
								</tr></table></td>
							</tr>
							</table>
						</td>
					</tr>
				</table></td></tr>
				
				<tr><td style="border-bottom:2px solid black; border-right:2px solid black; border-left:2px solid black; background-color:#c0c0c0;"><table style="border-collapse:collapse;">
					<tr>
						<td style="border-right:2px solid black; background-color:#c0c0c0;" width="352px"><strong>
							<table style="border-collapse:collapse; background-color:white;" width="352px">
								<tr><td width="90px">Part III</td><td>Employer Information (Previous)-1</td></tr>
							</table>
						</strong>
							<table style="border-collapse:collapse;" width="200px">
								<tr><td style="border-top:2px solid black;" width="250px"></td></tr>
								<tr><td><table><tr>
									<td width="5px" style="vertical-align:top;">16</td>
									<td width="80px">Taxpayer <br>Identification No.</td>
									<td width="10px" style="vertical-align:top;">16 </td>
									<td style="border:2px solid black; background-color:white;" width="220px">&nbsp;</td>
								</tr></table></td></tr>
								<tr><td style="border-top:2px solid black;" width="250px"></td></tr>
								<tr><td>
									<table>
										<tr>
											<td width="5px">17</td>
											<td>Employer\'s Name</td>
										</tr>
										<tr>
											<td>&nbsp;</td>
											<td style="border:1px solid black; background-color:white;" width="320px">&nbsp;</td>
										</tr>
									</table>
								</td></tr>
								<tr><td style="border-top:2px solid black;" width="250px"></td></tr>
								<tr><td>
									<table>
										<tr>
											<td width="5px">18</td>
											<td>Registered Address</td>
											<td width="5px">&nbsp;</td>
											<td width="5px">18A</td>
											<td width="50px">Zip Code</td>
										</tr>
										<tr>
											<td>&nbsp;</td>
											<td style="border:1px solid black; background-color:white;" width="220px">&nbsp;</td>
											<td>&nbsp;</td>
											<td>&nbsp;</td>
											<td style="border:1px solid black; background-color:white;">&nbsp;</td>
										</tr>
									</table>
								</td></tr>
							</table>
						</td>
						<td width="345px" colspan="2">
							<table>
								<tr>
									<td><table><tr>
										<td width="15px">40A</td>
										<td width="142px" class="textbox">Others Supplementary</td>
										<td width="15px">40A</td>
										<td class="textbox" align="right" width="150px">0.00</td>
									</tr></table></td>
								</tr>
								<tr>
									<td><table><tr>
										<td width="15px">40B</td>
										<td width="142px" class="textbox">&nbsp;</td>
										<td width="15px">40B</td>
										<td class="textbox" align="right" width="150px">0.00</td>
									</tr></table></td>
								</tr>
								<tr>
									<td><table><tr>
										<td width="15px">41</td>
										<td width="150px">Total Taxable Compensation<br>Income</td>
										<td width="15px">41</td>
										<td class="textbox" align="right" width="150px">0.00</td>
									</tr></table></td>
								</tr>
								<tr><td align="center" style="border-top:2px solid black; border-bottom:2px solid black;"><strong>Summary</strong></td></tr>
								<tr>
									<td><table><tr>
										<td width="15px">42</td>
										<td width="150px">Total Taxable Compensation Income</td>
										<td width="15px">42</td>
										<td class="textbox" align="right" width="150px">0.00</td>
									</tr></table></td>
								</tr>
							</table>
						</td>
					</tr>
				</table></td></tr>
				
				<tr><td style="background-color:#c0c0c0; border-bottom:2px solid black; border-right:2px solid black;"><table style="border-collapse:collapse;">
					<tr>
						<td style="border:2px solid black; background-color:#c0c0c0;" width="352px"><strong>
							<table style="border-collapse:collapse; background-color:white;" width="352px">
								<tr><td width="90px">&nbsp;</td><td>Employer Information (Previous)-2</td></tr>
							</table>
						</strong>
							<table style="border-collapse:collapse;" width="200px">
								<tr><td style="border-top:2px solid black;" width="250px"></td></tr>
								<tr><td><table><tr>
									<td width="5px" style="vertical-align:top;">19</td>
									<td width="80px">Taxpayer <br>Identification No.</td>
									<td width="10px" style="vertical-align:top;">19 </td>
									<td style="border:2px solid black; background-color:white;" width="220px">&nbsp;</td>
								</tr></table></td></tr>
								<tr><td style="border-top:2px solid black;" width="250px"></td></tr>
								<tr><td>
									<table>
										<tr>
											<td width="5px">20</td>
											<td>Employer\'s Name</td>
										</tr>
										<tr>
											<td>&nbsp;</td>
											<td style="border:1px solid black; background-color:white;" width="320px">&nbsp;</td>
										</tr>
									</table>
								</td></tr>
								<tr><td style="border-top:2px solid black;" width="250px"></td></tr>
								<tr><td>
									<table>
										<tr>
											<td width="5px">21</td>
											<td>Registered Address</td>
											<td width="5px">&nbsp;</td>
											<td width="5px">21A</td>
											<td width="50px">Zip Code</td>
										</tr>
										<tr>
											<td>&nbsp;</td>
											<td style="border:1px solid black; background-color:white;" width="220px">&nbsp;</td>
											<td>&nbsp;</td>
											<td>&nbsp;</td>
											<td style="border:1px solid black; background-color:white;">&nbsp;</td>
										</tr>
									</table>
								</td></tr>
							</table>
						</td>
						<td width="345px" colspan="2" style="vertical-align:top;">
							<table><tr>
									<td><table>
										<tr>
											<td width="15px">42</td>
											<td width="150px">Taxable Compensation Income<br>from Present Employer</td>
											<td width="15px">42</td>
											<td class="textbox" align="right" width="150px">7491.70</td>
										</tr>
										<tr>
											<td width="15px">43</td>
											<td width="150px">Add: Taxable Compensation<br>from Previous Employer(s)</td>
											<td width="15px">43</td>
											<td class="textbox" align="right" width="150px">0.00</td>
										</tr>
										<tr>
											<td width="15px">44</td>
											<td width="150px">Gross Taxable Compensation<br>Income</td>
											<td width="15px">44</td>
											<td class="textbox" align="right" width="150px">7491.70</td>
										</tr>
										<tr>
											<td width="15px">45</td>
											<td width="150px">Less: Total Exemptions</td>
											<td width="15px">45</td>
											<td class="textbox" align="right" width="150px">32000.00</td>
										</tr>
										<tr>
											<td width="15px">46</td>
											<td width="150px">Less: Premium Paid on Health and/or Hospital Insurance</td>
											<td width="15px">46</td>
											<td class="textbox" align="right" width="150px">0.00</td>
										</tr>
									</table></td>
								</tr>
							</table>
						</td>
					</tr>
				</table></td></tr>
				
				<tr><td style="border-bottom:2px solid black; border-right:2px solid black; background-color:#c0c0c0;"><table style="border-collapse:collapse;">
					<tr>
						<td style="border:2px solid black; background-color:#c0c0c0;" width="352px"><strong>
							<table style="border-collapse:collapse; background-color:white;" width="352px">
								<tr><td width="90px">&nbsp;</td><td>Employer Information (Previous)-3</td></tr>
							</table>
						</strong>
							<table style="border-collapse:collapse;" width="200px">
								<tr><td style="border-top:2px solid black;" width="250px"></td></tr>
								<tr><td><table><tr>
									<td width="5px" style="vertical-align:top;">22</td>
									<td width="80px">Taxpayer <br>Identification No.</td>
									<td width="10px" style="vertical-align:top;">22 </td>
									<td style="border:2px solid black; background-color:white;" width="220px">&nbsp;</td>
								</tr></table></td></tr>
								<tr><td style="border-top:2px solid black;" width="250px"></td></tr>
								<tr><td>
									<table>
										<tr>
											<td width="5px">23</td>
											<td>Employer\'s Name</td>
										</tr>
										<tr>
											<td>&nbsp;</td>
											<td style="border:1px solid black; background-color:white;" width="320px">&nbsp;</td>
										</tr>
									</table>
								</td></tr>
								<tr><td style="border-top:2px solid black;" width="250px"></td></tr>
								<tr><td>
									<table>
										<tr>
											<td width="5px">24</td>
											<td>Registered Address</td>
											<td width="5px">&nbsp;</td>
											<td width="5px">24A</td>
											<td width="50px">Zip Code</td>
										</tr>
										<tr>
											<td>&nbsp;</td>
											<td style="border:1px solid black; background-color:white;" width="220px">&nbsp;</td>
											<td>&nbsp;</td>
											<td>&nbsp;</td>
											<td style="border:1px solid black; background-color:white;">&nbsp;</td>
										</tr>
									</table>
								</td></tr>
							</table>
						</td>
						<td width="345px" colspan="2">
							<table>
								<tr>
									<td width="15px">47</td>
									<td width="150px">Taxable Compensation Income</td>
									<td width="15px">47</td>
									<td class="textbox" align="right" width="150px">68900.40</td>
								</tr>
								<tr>
									<td width="15px">48</td>
									<td width="150px">Tax Due</td>
									<td width="15px">48</td>
									<td class="textbox" align="right" width="150px">8335.06</td>
								</tr>
								<tr>
									<td width="15px">49</td>
									<td width="150px" colspan="3">Amount of Taxes Withheld</td>
								</tr>
								<tr>
									<td width="15px">49A</td>
									<td width="150px">Present Employer</td>
									<td width="15px">49A</td>
									<td class="textbox" align="right" width="150px">694.59</td>
								</tr>
								<tr>
									<td width="15px">49B</td>
									<td width="150px">Previous Employer(s)</td>
									<td width="15px">49B</td>
									<td class="textbox" align="right" width="150px">0.00</td>
								</tr>
								<tr>
									<td width="15px">50</td>
									<td width="150px">Total Amount of Taxes Withheld</td>
									<td width="15px">50</td>
									<td class="textbox" align="right" width="150px">694.59</td>
								</tr>
							</table>
						</td>
					</tr>
				</table></td></tr>
				<tr><td style="border:2px solid black;">
					<table style="border-collapse:collapse;">
						<tr>
							<td align="center" style="font-size:10px;" colspan="9">I declare, under the penalties of perjury, that this certificate has been made in good faith, verified by us, and to the best of our knowledge and belief, is true and correct
	pursuant to the provisions of the National Internal Revenue Code, as amended, and the regulations issued under authority thereof.</td>
						</tr>
						<tr>
							<td width="20px">&nbsp;</td>
							<td width="5px">51 </td>
							<td width="270px" align="center" style="border-bottom:1px solid black;">&nbsp;</td>
							<td width="20px">&nbsp;</td>
							<td width="60px">Date Signed </td>
							<td class="textbox" width="100px">&nbsp;</td>
							<td colspan="3">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="2">&nbsp;</td>
							<td>Present Employer/Authorized Agent Signature Over Printed Name</td>
							<td colspan="6">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="9">CONFORME</td>
						</tr>
						<tr>
							<td width="20px">&nbsp;</td>
							<td width="5px">52 </td>
							<td width="270px" align="center" style="border-bottom:1px solid black;">BORJA, ABIGAIL</td>
							<td width="20px">&nbsp;</td>
							<td width="60px">Date Signed </td>
							<td class="textbox" width="100px">&nbsp;</td>
							<td colspan="3">&nbsp;</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>CTC No.</td>
							<td align="center">Employee Signature Over Printed Name</td>
							<td colspan="4">&nbsp;</td>
							<td align="center">Amount Paid</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td width="20px">&nbsp;</td>
							<td width="60px">of Employee </td>
							<td width="270px" align="center"><table><tr>
								<td class="textbox" width="80px">&nbsp;</td>
								<td>&nbsp;</td>
								<td>Place of Issue</td>
								<td class="textbox" width="80px">&nbsp;</td>
							</tr></table></td>
							<td width="20px">&nbsp;</td>
							<td width="60px">Date Signed </td>
							<td><table><tr><td class="textbox" width="100px">&nbsp;</td></tr></table></td>
							<td>&nbsp;</td>
							<td><table><tr><td class="textbox" width="100px" align="right">&nbsp;</td></tr></table></td>
							<td>&nbsp;</td>
						</tr>
						<tr><td colspan="9" align="center" style="border-top:2px solid black; border-bottom:2px solid black;"><strong>To be accomplished under substituted filing</strong></td></tr>
						<tr>
							<td colspan="3" align="justify" style="margin-left:10px; border-right:2px solid black;">
								<table style="border-collapse:collapse;">
									<tr>
										<td style="font-size:8px;" colspan="3">I declare, under the penalties of perjury, that the information herein stated are reported under BIR
Form No. 1604CF which have been filed with the Bureau of Internal Revenue</td>
									</tr>
									<tr>
										<td width="5px">53 </td>
										<td style="border-bottom:1px solid black;" width="140px">&nbsp;</td>
										<td width="20px">&nbsp;</td>
									</tr>
									<tr>
										<td width="5px">&nbsp;</td>
										<td>Present Employer/Authorized Agent Signature Over Printed Name<br>(Head of Accountung/Human Resource or Authorized Representative)</td>
										<td width="20px">&nbsp;</td>
									</tr>
								</table>
							</td>
							<td colspan="5" style="font-size:6px;" align="justify">
								<table><tr><td colspan="4">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;I declare, under the penalties of perjury, that I am qualified under substituted filing of Income Tax Returns (BIR
Form No. 1700), since I received purely compensation income from only one employer in the Phils. for the calendar
year; that taxes have been correctly withheld by my employer (tax due equals tax withheld); that the BIR Form No.
1604CF filed by my employer to the BIR shall constitute as my income tax return; and that BIR Form No. 2316 shall
53 serve as if BIR Form No. 1700 had been filed pursuant to the provisions of RR 3-2002, as amended.</td></tr>
									<tr>
										<td>54 </td>
										<td style="border-bottom:1px solid black;" width="200px">&nbsp;</td>
										<td width="20px">&nbsp;</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
										<td>Employee Signature Over Printed Name</td>
										<td>&nbsp;</td>
									</tr>
								</table>
							</td>
							<td>&nbsp;</td>
						</tr>
					</table>
				</td></tr>
			</table>
			</body></html>';
		$this->createPDF($content, $paper, $orientation, $filename);
	}
	
	function getForm2316($gData = array()) {
        $orientation='P';
        $unit='mm';
        $format='LEGAL';
        $unicode=true;
        $encoding="UTF-8";
		
        $appCls = new Application();
        $oPDF = new clsPDF($orientation, $unit, $format, $unicode, $encoding);
		
        // set auto page break to false so that we can control the page break
        // depending on the desired number of lines on the ouput
        $oPDF->SetAutoPageBreak(false);
        // use a freeserif font as a default font
        $oPDF->SetFont('freeserif','',10);

        // suppress print header and footer
        $oPDF->setPrintHeader(false);
        $oPDF->setPrintFooter(false);

        // get a modules list from the database
        $arrUserTypeList = $this->dbFetch();
        
        
        // set initial coordinates
        $coordX = 0;
        $coordY = 0;
        
         $sql1 = "SELECT (CASE WHEN (emp_hiredate LIKE '%{$gData['year']}%') THEN DATE_FORMAT(emp_hiredate,'%m-%d') ELSE '01-01' END) as startdate, 
				COALESCE(DATE_FORMAT(emp_resigndate,'%m-%d'),'12-31') as enddate,
				CONCAT(pi_lname,', ',pi_fname,' ', RPAD(pi_mname,1,' '),'.') as fullname,
				CONCAT(pi_fname,' ',pi_mname,' ', pi_lname) as employee_name,
				DATE_FORMAT(pi_bdate,'%m%d%Y') as bdate, pi_add, pi_tin, COALESCE(pi_telone,pi_mobileone,'') as tel,
				(CASE WHEN (taxep_code LIKE '%S%') THEN 'S' WHEN (taxep_code LIKE '%M%') THEN 'M' ELSE 'Z' END) as tax_exception,
				taxep_code, bir_alphalist_prev_emp.*
				FROM emp_masterfile emp
				JOIN emp_personal_info p on (p.pi_id=emp.pi_id)
				JOIN tax_excep taxep on (taxep.taxep_id=emp.taxep_id)
				LEFT JOIN bir_alphalist_prev_emp on (bir_alphalist_prev_emp.emp_id=emp.emp_id)
				";
      $rsResultcount = $this->conn->Execute($sql1);
      
 
       
      
 		//$countemp = $rsResultcount['_numOfRows'];
 		$emp = $gData['emp'];
 		
      $empid = $this->getEmpid($gData['year']);
 	  $employee = $this->getEmployeeList();
 
 	
 		
 	if ($emp == 0) {
 	
 			
 			$emp++;
 			foreach ($empid as $value){
 				
 			
 			//while (!$rsResultcount->EOF) {
 				//$x=1;
 				$gData['emp'] = $value;
 			 // set initial coordinates
       		$coordX = 0;
        	$coordY = 0;
 			$oPDF->Addpage();
 			$oPDF->Image(SYSCONFIG_THEME_PATH."default/images/admin/2316.jpg",$coordX,$coordY,$oPDF->getPageWidth(),$oPDF->getPageHeight());
			
 			$arrUserTypeList = $this->dbFetch();
 			
 			$sql = "SELECT (CASE WHEN (emp_hiredate LIKE '%{$gData['year']}%') THEN DATE_FORMAT(emp_hiredate,'%m-%d') ELSE '01-01' END) as startdate, 
				COALESCE(DATE_FORMAT(emp_resigndate,'%m-%d'),'12-31') as enddate,
				CONCAT(pi_lname,', ',pi_fname,' ', RPAD(pi_mname,1,' '),'.') as fullname,
				CONCAT(pi_fname,' ',pi_mname,' ', pi_lname) as employee_name,
				DATE_FORMAT(pi_bdate,'%m%d%Y') as bdate, pi_add, pi_tin, COALESCE(pi_telone,pi_mobileone,'') as tel,
				(CASE WHEN (taxep_code LIKE '%S%') THEN 'S' WHEN (taxep_code LIKE '%M%') THEN 'M' ELSE 'Z' END) as tax_exception,
				taxep_code, bir_alphalist_prev_emp.*
				FROM emp_masterfile emp
				JOIN emp_personal_info p on (p.pi_id=emp.pi_id)
				JOIN tax_excep taxep on (taxep.taxep_id=emp.taxep_id)
				LEFT JOIN bir_alphalist_prev_emp on (bir_alphalist_prev_emp.emp_id=emp.emp_id)
				WHERE emp.emp_id={$gData['emp']}";
    		$rsResult = $this->conn->Execute($sql);
    		// Set footer first
    		$oPDF->SetXY($coordX+30,$coordY+293.5);
        	$oPDF->MultiCell(70, 3,$rsResult->fields['employee_name'],1,'C',1, 0, 0, 0, TRUE, 0, TRUE);
        	$oPDF->SetXY($coordX+125,$coordY+326);
        	$oPDF->MultiCell(60, 3,$rsResult->fields['employee_name'],1,'C',1, 0, 0, 0, TRUE, 0, TRUE);
    		$oPDF->SetXY($coordX+30,$coordY+285.5);
        	$oPDF->MultiCell(70, 3,$gData['rep'],1,'C',1, 0, 0, 0, TRUE, 0, TRUE);
        	$oPDF->SetXY($coordX+25,$coordY+318);
       		$oPDF->MultiCell(75, 3,$gData['rep'],1,'C',1, 0, 0, 0, TRUE, 0, TRUE);
 			
       		 // Part IV-A - Summary
    	$empList = $this->validateMWE();
    	$checkMWE = in_array($gData['emp'], $empList);
    	
    	
    	// Variable assignemnt for easy manipulation and update
    	// Non-Taxable
    	$item32 = 0;
    	$item34 = 0;
    	$item37 = 0;
    	$item38 = 0;
    	$item39 = 0;
    	$item40 = 0;
    	$item41 = 0;
    	$item42 = 0;
    	$item45 = 0;
    	$item51 = 0;
    	$item53 = 0;
    	$item54a = 0;
    	$item55 = 0;
    	$item21 = 0;
    	$item22 = 0;
    	$item23 = 0;
    	$item24 = 0;
    	$item25 = 0;
    	$item26 = 0;
    	$item28 = 0;
    	$item29 = 0;
    	$item30a = 0;
    	$item30b = 0;
    	$item31 = 0;
    	
    	$item32 = ($checkMWE ? $this->getBasicIncome($gData['emp'], $gData['year'])-$this->getStatutoryAndUnionDues($gData['emp'], $gData['year']) : 0);
    	$item33 = 0;
    	$item34 = ($checkMWE ? clsBIRAlphalist::getOvertime($gData['emp'], $gData['year']) : 0);
    	$item35 = 0;
    	$item36 = 0;
    	$this->getBonus($gData['emp'], $gData['year'],0);
    	if($this->getBonus($gData['emp'], $gData['year'],0)>$tax_policy['tp_other_benefits']){
    		$bonus_nt = $tax_policy['tp_other_benefits'];
    		$addtn_bonus_taxable = $this->getBonus($val['emp_id'], $gData['year'],0)-$tax_policy['tp_other_benefits'];
    	} else {
    		$bonus_nt = $this->getBonus($val['emp_id'], $gData['year'],0);
    		$addtn_bonus_taxable = 0;
    	}
    	$item37 = $this->getBonus($gData['emp'], $gData['year'],0);
    	$item38 = $this->getDeminimis($gData['emp'], $gData['year']);
    	$item39 = $this->getStatutoryAndUnionDues($gData['emp'], $gData['year']);
    	$item40 = $this->getOtherCompensation($gData['emp'], $gData['year']);
    	$item41 = $item32+$item33+$item34+$item35+$item36+$item37+$item38+$item39+$item40;
    	
    	
    	
    	// Taxable
    	$item42 = ($checkMWE ? 0 : $this->getBasicIncome($gData['emp'], $gData['year'])-$this->getStatutoryAndUnionDues($gData['emp'], $gData['year']));
    	$item43 = 0;
    	$item44 = 0;
    	$item45 = $this->getCola($gData['emp'], $gData['year']);
    	$item46 = 0;
    	$item47a = $addtn_bonus_taxable;
    	$item47b = 0;
    	$item48 = 0;
    	$item49 = 0;
    	$item50 = 0;
    	$item51 = $this->getBonus($gData['emp'], $gData['year'],1);
    	$item52 = 0;
    	$item53 = ($checkMWE ? 0 : clsBIRAlphalist::getOvertime($gData['emp'], $gData['year']));
    	$item54a = $this->getOtherCompensationTaxable($gData['emp'], $gData['year'])-$item53;
    	$item54b = 0;
    	$item55 = $item42+$item43+$item44+$item45+$item46+$item47a+$item47b+$item48+$item49+$item50+$item51+$item52+$item53+$item54a+$item54b+$item55;
    	
    	//echo '<pre>';
    	//echo print_r($rsResult);
    	//echo '<pre>';
    	//exit;
    	
    	$item21 = $item41+$item55;
    	$item22 = $item41;
    	$item23 = $item55;
    	$item24 = ($rsResult->fields['bir_alphalist_year'] == $gData['year'] ? (($rsResult->fields['taxable_basic']+$rsResult->fields['taxable_other_ben']+$rsResult->fields['taxable_compensation'])-$rsResult->fields['nt_statutories']) : 0.00);
    	$item25 = $item23+$item24;
    	$item26 = $this->getExemptAmount($rsResult->fields['taxep_code']);
    	$item27 = 0;
    	$item28 = $item25-$item26-$item27;
    	
    	$prev_tax_withheld = ($rsResult->fields['bir_alphalist_year'] == $gData['year'] ? $rsResult->fields['tax_withheld'] : 0.00);
    	$item29 = $this->getAnnualTaxDue($gData['emp'],$gData['year'],$rsResult->fields['taxep_code'],$item25);
    	$present_tax_withheld = $this->getTaxWithheld($gData['emp'], $gData['year']);
    	$item30a = $present_tax_withheld;
    	$item30b = $prev_tax_withheld;
    	$item31 = $this->getAnnualTaxDue($gData['emp'],$gData['year'],$rsResult->fields['taxep_code'],$item25);
    	
    	$oPDF->SetXY($coordX+64.8,$coordY+212);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item21),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+64.8,$coordY+217.5);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item22),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
    	$oPDF->SetXY($coordX+64.8,$coordY+223.5);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item23),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+64.8,$coordY+229);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item24),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
    	$oPDF->SetXY($coordX+64.8,$coordY+234.5);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item25),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+64.8,$coordY+240);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item26),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
		$oPDF->SetXY($coordX+64.8,$coordY+245.5);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item27),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
    	$oPDF->SetXY($coordX+64.8,$coordY+251);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item28),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+64.8,$coordY+256.5);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item29),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
     	$oPDF->SetXY($coordX+64.8,$coordY+264);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item30a),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
		$oPDF->SetXY($coordX+64.8,$coordY+270);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item30b),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+64.8,$coordY+275.5);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item31),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
    	
        $oPDF->SetXY($coordX+156,$coordY+51.5);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item32),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+156,$coordY+62.6);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item33),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+156,$coordY+70);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item34),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
		$oPDF->SetXY($coordX+156,$coordY+77.5);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item35),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
    	$oPDF->SetXY($coordX+156,$coordY+85);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item36),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+156,$coordY+92.5);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item37),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+156,$coordY+102);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item38),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+156,$coordY+112.5);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item39),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+156,$coordY+126);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item40),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+156,$coordY+136);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item41),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);

    	$oPDF->SetXY($coordX+156,$coordY+154);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item42),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+156,$coordY+161.5);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item43),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
 		$oPDF->SetXY($coordX+156,$coordY+169);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item44),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
    	$oPDF->SetXY($coordX+156,$coordY+176);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item45),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
  		$oPDF->SetXY($coordX+156,$coordY+183.5);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item46),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
		$oPDF->SetXY($coordX+156,$coordY+193);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item47a),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
    	$oPDF->SetXY($coordX+156,$coordY+200.5);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item47b),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+156,$coordY+209.5);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item48),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+156,$coordY+217.5);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item49),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+156,$coordY+226);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item50),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+156,$coordY+234.5);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item51),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+156,$coordY+243);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item52),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+156,$coordY+251);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item53),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+156,$coordY+262);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item54a),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+156,$coordY+268.5);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item54b),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+156,$coordY+275);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item55),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->Text($coordX+117,$coordY+197.5,"Others Regular");
    	$oPDF->Text($coordX+117,$coordY+204,"");
    	$oPDF->Text($coordX+117,$coordY+266.5,"Others Supplementary");
    	$oPDF->Text($coordX+117,$coordY+272.5,"");
    	
    	
    	// Year and Period
    	$oPDF->Text($coordX+47.8,$coordY+34.6,$gData['year'][0]);
    	$oPDF->Text($coordX+51.8,$coordY+34.6,$gData['year'][1]);
    	$oPDF->Text($coordX+55.8,$coordY+34.6,$gData['year'][2]);
    	$oPDF->Text($coordX+59.8,$coordY+34.6,$gData['year'][3]);
    	
    	$oPDF->Text($coordX+145.8,$coordY+34.6,$rsResult->fields['startdate'][0]);
    	$oPDF->Text($coordX+148.8,$coordY+34.6,$rsResult->fields['startdate'][1]);
    	$oPDF->Text($coordX+152.8,$coordY+34.6,$rsResult->fields['startdate'][3]);
    	$oPDF->Text($coordX+155.8,$coordY+34.6,$rsResult->fields['startdate'][4]);
    	
    	$oPDF->Text($coordX+185.8,$coordY+34.6,$rsResult->fields['enddate'][0]);
    	$oPDF->Text($coordX+188.8,$coordY+34.6,$rsResult->fields['enddate'][1]);
    	$oPDF->Text($coordX+192.8,$coordY+34.6,$rsResult->fields['enddate'][3]);
    	$oPDF->Text($coordX+195.8,$coordY+34.6,$rsResult->fields['enddate'][4]);
    	
    	$tin = str_replace (array(" ","-"), "", $rsResult->fields['pi_tin']);
    	$oPDF->Text($coordX+47.8,$coordY+45.6,$tin[0]);
    	$oPDF->Text($coordX+51.8,$coordY+45.6,$tin[1]);
    	$oPDF->Text($coordX+55.5,$coordY+45.6,$tin[2]);
    	$oPDF->Text($coordX+63.8,$coordY+45.6,$tin[3]);
    	$oPDF->Text($coordX+67.5,$coordY+45.6,$tin[4]);
    	$oPDF->Text($coordX+71,$coordY+45.6,$tin[5]);
    	$oPDF->Text($coordX+79.8,$coordY+45.6,$tin[6]);
    	$oPDF->Text($coordX+83.5,$coordY+45.6,$tin[7]);
    	$oPDF->Text($coordX+87,$coordY+45.6,$tin[8]);
    	$oPDF->Text($coordX+95.8,$coordY+45.6,'0');
    	$oPDF->Text($coordX+98.8,$coordY+45.6,'0');
    	$oPDF->Text($coordX+102.8,$coordY+45.6,'0');
    	$oPDF->Text($coordX+105.8,$coordY+45.6,'0');
    	
    	$oPDF->Text($coordX+18.8,$coordY+55.6,ucfirst($rsResult->fields['fullname']));
    	
		$getZip = explode(",",$rsResult->fields['pi_add']);
		$address = "";
		foreach($getZip as $key => $value){
			if($key == count($getZip)-1 && is_numeric($value)){
				$zip = $value;
			} else {
				$address .= $value;
				$zip ="";
			}
		}
		$lastSpacePosition = strrpos($address, ' ');
		$address = substr($address, 0, $lastSpacePosition);
		$zip = trim($zip);
		if(strlen($address) > 47){
			$oPDF->SetFontSize(7);
		}
		$oPDF->Text($coordX+18.8,$coordY+64.6,$address);
		
		$oPDF->SetFontSize(10);
		$oPDF->Text($coordX+93.8,$coordY+64.6,$zip[0]);
		$oPDF->Text($coordX+97.2,$coordY+64.6,$zip[1]);
		$oPDF->Text($coordX+101.8,$coordY+64.6,$zip[2]);
		$oPDF->Text($coordX+105.8,$coordY+64.6,$zip[3]);
				
		$oPDF->Text($coordX+19,$coordY+92.5,$rsResult->fields['bdate'][0]);
		$oPDF->Text($coordX+23,$coordY+92.5,$rsResult->fields['bdate'][1]);
		$oPDF->Text($coordX+27.5,$coordY+92.5,$rsResult->fields['bdate'][2]);
		$oPDF->Text($coordX+32,$coordY+92.5,$rsResult->fields['bdate'][3]);
		$oPDF->Text($coordX+37,$coordY+92.5,$rsResult->fields['bdate'][4]);
		$oPDF->Text($coordX+41.5,$coordY+92.5,$rsResult->fields['bdate'][5]);
		$oPDF->Text($coordX+47,$coordY+92.5,$rsResult->fields['bdate'][6]);
		$oPDF->Text($coordX+51.5,$coordY+92.5,$rsResult->fields['bdate'][7]);
		
		$oPDF->Text($coordX+75.5,$coordY+92.5,$rsResult->fields['tel']);

		if($rsResult->fields['tax_exception'] == 'S'){
			$oPDF->Text($coordX+35.5,$coordY+101.3,'X');
		} elseif($rsResult->fields['tax_exception'] == 'M') {
			$oPDF->Text($coordX+65.5,$coordY+101.3,'X');
		}
    	// Company Details
        $sqlComp = "SELECT comp_name, comp_tin, comp_add FROM company_info WHERE comp_id = {$gData['comp']}";
    	$rsResultComp = $this->conn->Execute($sqlComp);
    	$getZipComp = explode(",",$rsResultComp->fields['comp_add']);
		$company_add = "";
		foreach($getZipComp as $key => $value){
			if($key == count($getZipComp)-1 && is_numeric($value)){
				$zipComp = $value;
			} else {
				$company_add .= $value;
				$zipComp ="";
			}
		}
		$lastSpacePosition = strrpos($company_add, ' ');
		$company_add = substr($company_add, 0, $lastSpacePosition);
		$zipComp = trim($zipComp);
		
		if(strlen($company_add) > 47){
			$oPDF->SetFontSize(7);
		}
		$oPDF->Text($coordX+18.8,$coordY+175,ucfirst($company_add));
		
		$oPDF->SetFontSize(10);
		$oPDF->Text($coordX+18.8,$coordY+165.6,ucfirst($rsResultComp->fields['comp_name']));
		$oPDF->Text($coordX+93.8,$coordY+175,$zipComp[0]);
		$oPDF->Text($coordX+97.2,$coordY+175,$zipComp[1]);
		$oPDF->Text($coordX+101.8,$coordY+175,$zipComp[2]);
		$oPDF->Text($coordX+105.8,$coordY+175,$zipComp[3]);
		
		$company_tin = str_replace (array(" ","-"), "", trim($rsResultComp->fields['comp_tin']));
    	$oPDF->Text($coordX+47.8,$coordY+156,$company_tin[0]);
    	$oPDF->Text($coordX+51.8,$coordY+156,$company_tin[1]);
    	$oPDF->Text($coordX+55.5,$coordY+156,$company_tin[2]);
    	$oPDF->Text($coordX+63.8,$coordY+156,$company_tin[3]);
    	$oPDF->Text($coordX+67.5,$coordY+156,$company_tin[4]);
    	$oPDF->Text($coordX+71,$coordY+156,$company_tin[5]);
    	$oPDF->Text($coordX+79.8,$coordY+156,$company_tin[6]);
    	$oPDF->Text($coordX+83.5,$coordY+156,$company_tin[7]);
    	$oPDF->Text($coordX+87,$coordY+156,$company_tin[8]);
    	$oPDF->Text($coordX+95.8,$coordY+156,'0');
    	$oPDF->Text($coordX+98.8,$coordY+156,'0');
    	$oPDF->Text($coordX+102.8,$coordY+156,'0');
    	$oPDF->Text($coordX+105.8,$coordY+156,'0');
    	
    	// end of content
    	
       		
 			//$rsResult->MoveNext();
 			$emp += 1;
 			//$x++;
 			}
 			unset($empid);
 			
 		} ELSE {

 		//-----------endif
 		
 			
 		
 		
        // set initial pdf page
        $oPDF->AddPage();
        
		
//        $oPDF->Image(SYSCONFIG_THEME_PATH."default/images/admin/alphalist.png",0,0,$oPDF->getPageWidth(),$oPDF->getPageHeight());
        $oPDF->Image(SYSCONFIG_THEME_PATH."default/images/admin/2316.jpg",$coordX,$coordY,$oPDF->getPageWidth(),$oPDF->getPageHeight());
		
		
        $sql = "SELECT (CASE WHEN (emp_hiredate LIKE '%{$gData['year']}%') THEN DATE_FORMAT(emp_hiredate,'%m-%d') ELSE '01-01' END) as startdate, 
				(CASE WHEN (emp_resigndate LIKE '%{$gData['year']}%') THEN DATE_FORMAT(emp_resigndate,'%m-%d') ELSE '12-31' END) as enddate,
				CONCAT(pi_lname,', ',pi_fname,' ', RPAD(pi_mname,1,' '),'.') as fullname,
				CONCAT(pi_fname,' ',pi_mname,' ', pi_lname) as employee_name,
				DATE_FORMAT(pi_bdate,'%m%d%Y') as bdate, pi_add, pi_tin, COALESCE(pi_telone,pi_mobileone,'') as tel,
				(CASE WHEN (taxep_code LIKE '%S%') THEN 'S' WHEN (taxep_code LIKE '%M%') THEN 'M' ELSE 'Z' END) as tax_exception,
				taxep_code, bir_alphalist_prev_emp.*
				FROM emp_masterfile emp
				JOIN emp_personal_info p on (p.pi_id=emp.pi_id)
				JOIN tax_excep taxep on (taxep.taxep_id=emp.taxep_id)
				LEFT JOIN bir_alphalist_prev_emp on (bir_alphalist_prev_emp.emp_id=emp.emp_id)
				WHERE emp.emp_id={$gData['emp']}";
    	$rsResult = $this->conn->Execute($sql);
    	
    	// Set footer first
    	$oPDF->SetXY($coordX+30,$coordY+293.5);
        $oPDF->MultiCell(70, 3,$rsResult->fields['employee_name'],1,'C',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+125,$coordY+326);
        $oPDF->MultiCell(60, 3,$rsResult->fields['employee_name'],1,'C',1, 0, 0, 0, TRUE, 0, TRUE);
    	$oPDF->SetXY($coordX+30,$coordY+285.5);
        $oPDF->MultiCell(70, 3,$gData['rep'],1,'C',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+25,$coordY+318);
        $oPDF->MultiCell(75, 3,$gData['rep'],1,'C',1, 0, 0, 0, TRUE, 0, TRUE);
        
        // Part IV-A - Summary
    	$empList = $this->validateMWE();
    	$checkMWE = in_array($gData['emp'], $empList);
    	
    	/** Variable assignemnt for easy manipulation and update **/
    	// Non-Taxable
    	$item32 = ($checkMWE ? $this->getBasicIncome($gData['emp'], $gData['year'])-$this->getStatutoryAndUnionDues($gData['emp'], $gData['year']) : 0);
    	$item33 = 0;
    	$item34 = ($checkMWE ? clsBIRAlphalist::getOvertime($gData['emp'], $gData['year']) : 0);
    	$item35 = 0;
    	$item36 = 0;
    	$item37 = $this->getBonus($gData['emp'], $gData['year'],0);
    	$item38 = $this->getDeminimis($gData['emp'], $gData['year']);
    	$item39 = $this->getStatutoryAndUnionDues($gData['emp'], $gData['year']);
    	$item40 = $this->getOtherCompensation($gData['emp'], $gData['year']);
    	$item41 = $item32+$item33+$item34+$item35+$item36+$item37+$item38+$item39+$item40;
    	
    	
    	
    	// Taxable
    	$item42 = ($checkMWE ? 0 : $this->getBasicIncome($gData['emp'], $gData['year'])-$this->getStatutoryAndUnionDues($gData['emp'], $gData['year']));
    	$item43 = 0;
    	$item44 = 0;
    	$item45 = $this->getCola($gData['emp'], $gData['year']);
    	$item46 = 0;
    	$item47a = 0;
    	$item47b = 0;
    	$item48 = 0;
    	$item49 = 0;
    	$item50 = 0;
    	$item51 = $this->getBonus($gData['emp'], $gData['year'],1);
    	$item52 = 0;
    	$item53 = ($checkMWE ? 0 : clsBIRAlphalist::getOvertime($gData['emp'], $gData['year']));
    	$item54a = $this->getOtherCompensationTaxable($gData['emp'], $gData['year'])-$item53;
    	$item54b = 0;
    	$item55 = $item42+$item43+$item44+$item45+$item46+$item47a+$item47b+$item48+$item49+$item50+$item51+$item52+$item53+$item54a+$item54b+$item55;
    	
    	$item21 = $item41+$item55;
    	$item22 = $item41;
    	$item23 = $item55;
    	$item24 = ($rsResult->fields['bir_alphalist_year'] == $gData['year'] ? (($rsResult->fields['taxable_basic']+$rsResult->fields['taxable_other_ben']+$rsResult->fields['taxable_compensation'])-$rsResult->fields['nt_statutories']) : 0.00);
    	$item25 = $item23+$item24;
    	$item26 = $this->getExemptAmount($rsResult->fields['taxep_code']);
    	$item27 = 0;
    	$item28 = $item25-$item26-$item27;
    	$prev_tax_withheld = ($rsResult->fields['bir_alphalist_year'] == $gData['year'] ? $rsResult->fields['tax_withheld'] : 0.00);
    	$item29 = $this->getAnnualTaxDue($gData['emp'],$gData['year'],$rsResult->fields['taxep_code'],$item25);
    	$present_tax_withheld = $this->getTaxWithheld($gData['emp'], $gData['year']);
    	$item30a = $present_tax_withheld;
    	$item30b = $prev_tax_withheld;
    	$item31 = $this->getAnnualTaxDue($gData['emp'],$gData['year'],$rsResult->fields['taxep_code'],$item25);
    	
    	$oPDF->SetXY($coordX+64.8,$coordY+212);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item21),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+64.8,$coordY+217.5);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item22),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
    	$oPDF->SetXY($coordX+64.8,$coordY+223.5);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item23),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+64.8,$coordY+229);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item24),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
    	$oPDF->SetXY($coordX+64.8,$coordY+234.5);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item25),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+64.8,$coordY+240);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item26),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
		$oPDF->SetXY($coordX+64.8,$coordY+245.5);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item27),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
    	$oPDF->SetXY($coordX+64.8,$coordY+251);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item28),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+64.8,$coordY+256.5);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item29),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
     	$oPDF->SetXY($coordX+64.8,$coordY+264);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item30a),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
		$oPDF->SetXY($coordX+64.8,$coordY+270);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item30b),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+64.8,$coordY+275.5);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item31),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
    	
        $oPDF->SetXY($coordX+156,$coordY+51.5);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item32),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+156,$coordY+62.6);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item33),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+156,$coordY+70);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item34),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
		$oPDF->SetXY($coordX+156,$coordY+77.5);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item35),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
    	$oPDF->SetXY($coordX+156,$coordY+85);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item36),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+156,$coordY+92.5);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item37),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+156,$coordY+102);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item38),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+156,$coordY+112.5);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item39),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+156,$coordY+126);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item40),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+156,$coordY+136);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item41),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);

    	$oPDF->SetXY($coordX+156,$coordY+154);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item42),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+156,$coordY+161.5);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item43),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
 		$oPDF->SetXY($coordX+156,$coordY+169);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item44),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
    	$oPDF->SetXY($coordX+156,$coordY+176);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item45),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
  		$oPDF->SetXY($coordX+156,$coordY+183.5);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item46),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
		$oPDF->SetXY($coordX+156,$coordY+193);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item47a),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
    	$oPDF->SetXY($coordX+156,$coordY+200.5);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item47b),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+156,$coordY+209.5);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item48),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+156,$coordY+217.5);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item49),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+156,$coordY+226);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item50),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+156,$coordY+234.5);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item51),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+156,$coordY+243);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item52),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+156,$coordY+251);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item53),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+156,$coordY+262);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item54a),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+156,$coordY+268.5);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item54b),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+156,$coordY+275);
        $oPDF->MultiCell(44, 3,$appCls->setFinalDecimalPlaces($item55),1,'R',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->Text($coordX+117,$coordY+197.5,"Others Regular");
    	$oPDF->Text($coordX+117,$coordY+204,"");
    	$oPDF->Text($coordX+117,$coordY+266.5,"Others Supplementary");
    	$oPDF->Text($coordX+117,$coordY+272.5,"");
    	
    	// Year and Period
    	$oPDF->Text($coordX+47.8,$coordY+34.6,$gData['year'][0]);
    	$oPDF->Text($coordX+51.8,$coordY+34.6,$gData['year'][1]);
    	$oPDF->Text($coordX+55.8,$coordY+34.6,$gData['year'][2]);
    	$oPDF->Text($coordX+59.8,$coordY+34.6,$gData['year'][3]);
    	
    	$oPDF->Text($coordX+145.8,$coordY+34.6,$rsResult->fields['startdate'][0]);
    	$oPDF->Text($coordX+148.8,$coordY+34.6,$rsResult->fields['startdate'][1]);
    	$oPDF->Text($coordX+152.8,$coordY+34.6,$rsResult->fields['startdate'][3]);
    	$oPDF->Text($coordX+155.8,$coordY+34.6,$rsResult->fields['startdate'][4]);
    	
    	$oPDF->Text($coordX+185.8,$coordY+34.6,$rsResult->fields['enddate'][0]);
    	$oPDF->Text($coordX+188.8,$coordY+34.6,$rsResult->fields['enddate'][1]);
    	$oPDF->Text($coordX+192.8,$coordY+34.6,$rsResult->fields['enddate'][3]);
    	$oPDF->Text($coordX+195.8,$coordY+34.6,$rsResult->fields['enddate'][4]);
    	
    	$tin = str_replace (array(" ","-"), "", $rsResult->fields['pi_tin']);
    	$oPDF->Text($coordX+47.8,$coordY+45.6,$tin[0]);
    	$oPDF->Text($coordX+51.8,$coordY+45.6,$tin[1]);
    	$oPDF->Text($coordX+55.5,$coordY+45.6,$tin[2]);
    	$oPDF->Text($coordX+63.8,$coordY+45.6,$tin[3]);
    	$oPDF->Text($coordX+67.5,$coordY+45.6,$tin[4]);
    	$oPDF->Text($coordX+71,$coordY+45.6,$tin[5]);
    	$oPDF->Text($coordX+79.8,$coordY+45.6,$tin[6]);
    	$oPDF->Text($coordX+83.5,$coordY+45.6,$tin[7]);
    	$oPDF->Text($coordX+87,$coordY+45.6,$tin[8]);
    	$oPDF->Text($coordX+95.8,$coordY+45.6,'0');
    	$oPDF->Text($coordX+98.8,$coordY+45.6,'0');
    	$oPDF->Text($coordX+102.8,$coordY+45.6,'0');
    	$oPDF->Text($coordX+105.8,$coordY+45.6,'0');
    	
    	$oPDF->Text($coordX+18.8,$coordY+55.6,ucfirst($rsResult->fields['fullname']));
    	
		$getZip = explode(",",$rsResult->fields['pi_add']);
		$address = "";
		foreach($getZip as $key => $value){
			if($key == count($getZip)-1 && is_numeric($value)){
				$zip = $value;
			} else {
				$address .= $value;
				$zip ="";
			}
		}
		$lastSpacePosition = strrpos($address, ' ');
		$address = substr($address, 0, $lastSpacePosition);
		$zip = trim($zip);
		if(strlen($address) > 47){
			$oPDF->SetFontSize(7);
		}
		$oPDF->Text($coordX+18.8,$coordY+64.6,$address);
		
		$oPDF->SetFontSize(10);
		$oPDF->Text($coordX+93.8,$coordY+64.6,$zip[0]);
		$oPDF->Text($coordX+97.2,$coordY+64.6,$zip[1]);
		$oPDF->Text($coordX+101.8,$coordY+64.6,$zip[2]);
		$oPDF->Text($coordX+105.8,$coordY+64.6,$zip[3]);
				
		$oPDF->Text($coordX+19,$coordY+92.5,$rsResult->fields['bdate'][0]);
		$oPDF->Text($coordX+23,$coordY+92.5,$rsResult->fields['bdate'][1]);
		$oPDF->Text($coordX+27.5,$coordY+92.5,$rsResult->fields['bdate'][2]);
		$oPDF->Text($coordX+32,$coordY+92.5,$rsResult->fields['bdate'][3]);
		$oPDF->Text($coordX+37,$coordY+92.5,$rsResult->fields['bdate'][4]);
		$oPDF->Text($coordX+41.5,$coordY+92.5,$rsResult->fields['bdate'][5]);
		$oPDF->Text($coordX+47,$coordY+92.5,$rsResult->fields['bdate'][6]);
		$oPDF->Text($coordX+51.5,$coordY+92.5,$rsResult->fields['bdate'][7]);
		
		$oPDF->Text($coordX+75.5,$coordY+92.5,$rsResult->fields['tel']);

		if($rsResult->fields['tax_exception'] == 'S'){
			$oPDF->Text($coordX+35.5,$coordY+101.3,'X');
		} elseif($rsResult->fields['tax_exception'] == 'M') {
			$oPDF->Text($coordX+65.5,$coordY+101.3,'X');
		}
    	// Company Details
        $sqlComp = "SELECT comp_name, comp_tin, comp_add FROM company_info WHERE comp_id = {$gData['comp']}";
    	$rsResultComp = $this->conn->Execute($sqlComp);
    	$getZipComp = explode(",",$rsResultComp->fields['comp_add']);
		$company_add = "";
		foreach($getZipComp as $key => $value){
			if($key == count($getZipComp)-1 && is_numeric($value)){
				$zipComp = $value;
			} else {
				$company_add .= $value;
				$zipComp ="";
			}
		}
		$lastSpacePosition = strrpos($company_add, ' ');
		$company_add = substr($company_add, 0, $lastSpacePosition);
		$zipComp = trim($zipComp);
		
		if(strlen($company_add) > 47){
			$oPDF->SetFontSize(7);
		}
		$oPDF->Text($coordX+18.8,$coordY+175,ucfirst($company_add));
		
		$oPDF->SetFontSize(10);
		$oPDF->Text($coordX+18.8,$coordY+165.6,ucfirst($rsResultComp->fields['comp_name']));
		$oPDF->Text($coordX+93.8,$coordY+175,$zipComp[0]);
		$oPDF->Text($coordX+97.2,$coordY+175,$zipComp[1]);
		$oPDF->Text($coordX+101.8,$coordY+175,$zipComp[2]);
		$oPDF->Text($coordX+105.8,$coordY+175,$zipComp[3]);
		
		$company_tin = str_replace (array(" ","-"), "", trim($rsResultComp->fields['comp_tin']));
    	$oPDF->Text($coordX+47.8,$coordY+156,$company_tin[0]);
    	$oPDF->Text($coordX+51.8,$coordY+156,$company_tin[1]);
    	$oPDF->Text($coordX+55.5,$coordY+156,$company_tin[2]);
    	$oPDF->Text($coordX+63.8,$coordY+156,$company_tin[3]);
    	$oPDF->Text($coordX+67.5,$coordY+156,$company_tin[4]);
    	$oPDF->Text($coordX+71,$coordY+156,$company_tin[5]);
    	$oPDF->Text($coordX+79.8,$coordY+156,$company_tin[6]);
    	$oPDF->Text($coordX+83.5,$coordY+156,$company_tin[7]);
    	$oPDF->Text($coordX+87,$coordY+156,$company_tin[8]);
    	$oPDF->Text($coordX+95.8,$coordY+156,'0');
    	$oPDF->Text($coordX+98.8,$coordY+156,'0');
    	$oPDF->Text($coordX+102.8,$coordY+156,'0');
    	$oPDF->Text($coordX+105.8,$coordY+156,'0');
 		}
    	// end of content
 		
        // Get the pdf output
        $output = $oPDF->Output("1604-CF_".$gData['year'].".pdf");
		
        if (!empty($output)) {
            return $output;
        }
		
        return false;
    }
    
    function getEmpid($year_ = null) {
    	$sql = "SELECT emp_id, CONCAT(pi_lname,', ',pi_fname) as fullname
    			FROM emp_masterfile emp
    			JOIN emp_personal_info pi on (pi.pi_id=emp.pi_id)
    			WHERE DATE_FORMAT(emp_resigndate,'%Y') IS NULL OR DATE_FORMAT(emp_resigndate,'%Y') = '$year_'
    			ORDER by pi_lname";
		$rsResult = $this->conn->Execute($sql);
		while (!$rsResult->EOF) {
			$arrData[] = $rsResult->fields['emp_id'] ;
            $rsResult->MoveNext();
		}
        return $arrData;
    }
    function getEmployeeList(){
    	$sql = "SELECT emp_id, CONCAT(pi_lname,', ',pi_fname) as fullname
    			FROM emp_masterfile emp
    			JOIN emp_personal_info pi on (pi.pi_id=emp.pi_id)
    			ORDER by pi_lname";
		$rsResult = $this->conn->Execute($sql);
		while (!$rsResult->EOF) {
			$arrData[$rsResult->fields['emp_id']] = $rsResult->fields['fullname'] ;
            $rsResult->MoveNext();
		}
        return $arrData;
    }
    
	function validateMWE() {
		$arrData = array();
    	$sql = "SELECT emp_id FROM period_benloanduc_sched 
    			WHERE bldsched_period = '2' AND empdd_id = '5'";
        $rsResult = $this->conn->Execute($sql);
		while (!$rsResult->EOF) {
			$arrData[] = $rsResult->fields['emp_id'];
            $rsResult->MoveNext();
		}
		return $arrData;
    }
    
    function getCola($emp_id = null, $year = null){
    $sql = "SELECT entry_amt+amm_amt AS total
				FROM 
				(SELECT COALESCE(SUM(a.ppe_amount), 0) AS entry_amt FROM payroll_paystub_entry a
				INNER JOIN payroll_pay_stub b ON (b.paystub_id=a.paystub_id)
				INNER JOIN payroll_pay_period c ON (c.payperiod_id=b.payperiod_id)
				INNER JOIN payroll_paystub_report d ON (d.paystub_id=b.paystub_id)
				INNER JOIN payroll_ps_account e ON (e.psa_id=a.psa_id)
				WHERE c.payperiod_period_year='{$year}' AND d.emp_id='{$emp_id}' AND a.psa_id='39') entry_tbl,
				
				(select COALESCE(sum(a.amendemp_amount),0) as amm_amt 
				from payroll_ps_amendemp a 
				inner join payroll_ps_amendment b on (b.psamend_id=a.psamend_id) 
				inner join payroll_pay_stub c on (c.paystub_id=a.paystub_id) 
				inner join payroll_pay_period d on (d.payperiod_id=c.payperiod_id)
				INNER JOIN payroll_ps_account e ON (e.psa_id=b.psa_id) 
				WHERE d.payperiod_period_year='{$year}' AND a.emp_id={$emp_id} AND b.psa_id='39'
				AND a.paystub_id NOT IN (SELECT a.paystub_id FROM payroll_paystub_entry a
				INNER JOIN payroll_pay_stub b ON (b.paystub_id=a.paystub_id)
				INNER JOIN payroll_pay_period c ON (c.payperiod_id=b.payperiod_id)
				INNER JOIN payroll_paystub_report d ON (d.paystub_id=b.paystub_id)
				INNER JOIN payroll_ps_account e ON (e.psa_id=a.psa_id)
				WHERE c.payperiod_period_year='{$year}' AND d.emp_id='{$emp_id}' AND a.psa_id='39')) amm_tbl";
    	$rsResult = $this->conn->Execute($sql);
    	while (!$rsResult->EOF) {
    		if ($rsResult->fields['total'] != '' or $rsResult->fields['total'] != NULL or $rsResult->fields['total'] != 0.00) {
    			return $rsResult->fields['total'];
    		} else {
    			return 0.00;
    		}
    	}
    }
}

?>