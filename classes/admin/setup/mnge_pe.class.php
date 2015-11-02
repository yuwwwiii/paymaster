<?php
/**
 * Initial Declaration
 */
$typePSAccnt = array(
	 '1'=>'Earning'
	,'2'=>'Employee Deduction'
	,'3'=>'Employer Deduction'
	,'4'=>'Total'
	/*,'5'=>'Accrual'*/
);
$clsfiPSAccnt = array(
	 '0'=>'N/A'
	,'1'=>'Ben & Deduc'
	/*,'2'=>'OT List'
	,'3'=>'TUA'
	,'4'=>'Govt Contrib'*/
	,'5'=>'Govt/Reg Loan'
);
$typeStat = array(
	 '1'=>'Enabled'
	,'2'=>'Disabled'
);
$propertycode = array(
    '1' => "Bonus Code",
    '2' => "Compensation Code",
    '3' => "Non Taxable Code",
	'4' => "Recurring Tax Projection Code",
	'5' => "De Minimis Code",
	'6' => "Reimbursement Code"
);

/**
 * Class Module
 *
 * @author  JIM
 *
 */
class clsMnge_PE{

	var $conn;
	var $fieldMap;
	var $Data;

	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsMnge_PE object
	 */
	function clsMnge_PE($dbconn_ = null){
		$this->conn =& $dbconn_;
		$this->fieldMap = array(
		//"comp_id" => "comp_id",
		"psa_status" => "psa_status"
		,"psa_type" => "psa_type"
		,"psa_order" => "psa_order"
		,"psa_name" => "psa_name"
		,"psa_accrual" => "psa_accrual"
		,"psa_debit_accnt" => "psa_debit_accnt"
		,"psa_credit_accnt" => "psa_credit_accnt"
		,"psa_tax" => "tax"
		,"psa_statutory" => "psa_statutory"
		,"psa_isloan" => "psa_isloan"
		,"psa_clsfication" => "psa_clsfication"
		,"psa_procode" => "psa_procode"
		,"psa_formula" => "psa_formula"
		);
	}

	/**
	 * Get the records from the database
	 *
	 * @param string $id_
	 * @return array
	 */
	function dbFetch($id_ = ""){
		$sql = "Select a.* from payroll_ps_account a left join emp_benefits b on (b.psa_id=a.psa_id)where a.psa_id=?";
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
	 * Validation function
	 *
	 * @param array $pData_
	 * @return bool
	 */
	function doValidateData ($pData_ = array()) {
		$isValid = true;
		
		if (empty($pData_['psa_name'])) {
			$isValid = false;
			$_SESSION['eMsg'][] = "Please enter a Name.";
		}
		
		if (strlen($pData_['psa_name'])>20) {
			$isValid = false;
			$_SESSION['eMsg'][] = "Maximum of 20 characters.";
		}
		
		if (!is_numeric($pData_['psa_order']) && !empty($pData_['psa_order'])) {
		 	$isValid = false;
		 	$_SESSION['eMsg'][] = "Please enter a valid Order number.";
		}
		if($pData_['formula']!='' || $pData_['formula'] != null){
			if($this->doValidateFormula($pData_['formula'])){
				$isValid = false;
			 	$_SESSION['eMsg'][] = "Error in the formula. Please check your formula.";
			}
		}
		return $isValid;
	}

	/**
	 * Save New
	 *
	 */
	function doSaveAdd () {
		$flds = array();
		if($_POST['tax']) $tax = 1; else $tax = 0;
		if($_POST['psa_statutory']) $psa_statutory = 1; else $psa_statutory = 0;
		if($_POST['psa_isloan']) $isloan = 1; else $isloan = 0;
		foreach ($this->Data as $keyData => $valData) {
			if($keyData=='psa_tax') $valData = $tax;
			if($keyData=='psa_statutory') $valData = $psa_statutory;
			if($keyData=='psa_isloan') $valData = $isloan;
			$valData = trim(addslashes($valData));
			$flds[] = "$keyData='$valData'";
		}
		$flds[] = "psa_addwho = '".$_SESSION['admin_session_obj']['user_data']['user_name']."'";
		$fields = implode(", ",$flds);

		$sql = "insert into payroll_ps_account set $fields";
		$this->conn->Execute($sql);
		$_SESSION['eMsg']="Successfully Added.";
	}

	/**
	 * Save Update
	 *
	 */
	function doSaveEdit () {
		$id = $_GET['edit'];
		if ($_POST['tax']) $tax = 1; else $tax = 0;
		if ($_POST['psa_statutory']) $psa_statutory = 1; else $psa_statutory = 0;
		if ($_POST['psa_isloan']) $isloan = 1; else $isloan = 0;
		$flds = array();
		foreach ($this->Data as $keyData => $valData) {
			if ($keyData=='psa_tax') $valData = $tax;
			if ($keyData=='psa_statutory') $valData = $psa_statutory;
			if ($keyData=='psa_isloan') $valData = $isloan;
			$valData = trim(addslashes($valData));
			$flds[] = "$keyData='$valData'";
		}
		if($this->validateFormula($id)){ 
			$flds[] = "psa_formula = '".$_POST['formula']."'";
			//$arr = preg_split("/[\s]*[*|\/|+|-][\s]*/", $_POST['formula'], -1);
			$arr = preg_split("/[\s]*[*|\/|+|-][\s]*/", str_replace(array("(",")"),"",$_POST['formula']), -1);
			$flds[] = "psa_formula_el = '".serialize($arr)."'";
		}
		$flds[] = "psa_updatedwho = '".$_SESSION['admin_session_obj']['user_data']['user_name']."'";
		$flds[] = "psa_updatedwhen = '".date('Y-m-d h:i:s')."'";
		$fields = implode(", ",$flds);

		$sql = "update payroll_ps_account set $fields where psa_id=$id";
		$this->conn->Execute($sql);
		
		//@note used to update the priority
		$flds_[] = "psa_id = '".$id."'";
		$flds_[] = "ppc_stat = '1'";
		$fields_ = implode(", ",$flds_);
		$sql_ = "update payroll_priority_comp set $fields_ where ppc_id='".$_POST['psa_priority']."'";
		$this->conn->Execute($sql_);
		$_SESSION['eMsg']="Successfully Updated.";
	}

	/**
	 * Delete Record
	 *
	 * @param string $id_
	 */
	function doDelete ($id_ = "") {
		IF($id_ != '1' and $id_ != '2' and $id_ != '4' and $id_ != '5' and $id_ != '6' and $id_ != '7' and $id_ != '8' and $id_ != '9' and $id_ != '11' and $id_ != '13' and $id_ != '14' and $id_ != '15' and $id_ != '16' and $id_ != '17' and $id_ != '25' and $id_ != '26' and $id_ != '27' and $id_ != '28' and $id_ != '29' and $id_ != '30' and $id_ != '31' and $id_ != '32' and $id_ != '33' and $id_ != '34' and $id_ != '39'){
			$sql = "delete from payroll_ps_account where psa_id=?";
			$this->conn->Execute($sql,array($id_));
			$_SESSION['eMsg']="Successfully Deleted.";
		}else{
			$_SESSION['eMsg']="Pay Element in used. Can not be deleted.";
		}
		
	}

	/**
	 * Get all the Table Listings
	 *
	 * @return array
	 */
	function getTableList () {
//		printa($_SESSION);
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
				$qry[] = "IF(psa_type=1,'Earning',IF(psa_type=2,'Employee Deduction',IF(psa_type=3,'Employer Deduction',IF(psa_type=4,'Total','Accrual')))) like '%$search_field%'";

			}
		}

		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		 "viewdata"=>"viewdata"
		,"psa_type" => "psa_type"
		,"psa_name" => "psa_name"
		,"psa_clsfication"=>"psa_clsfication"
		/*,"psa_debit_accnt" => "psa_debit_accnt"
		,"psa_credit_accnt" => "psa_credit_accnt"*/
		,"ppc_priority_no" => "ppc_priority_no"
		,"psa_order" => "psa_order"
		,"psa_status"=>"psa_status"
		);

		if (isset($_GET['sortby'])) {
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		} else {
			$strOrderBy = " order by psa_type,psa_order asc";
		}

		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$viewLink = "";
		$editLink = "<a href=\"?statpos=mnge_pe&edit=',am.psa_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/edit.png\" title=\"Edit\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
		$delLink = "<a href=\"?statpos=mnge_pe&delete=',am.psa_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\"  border=0 width=\"16\" height=\"16\"></a>";

		// SqlAll Query
		$sql = "select am.*, IF(psa_type=1,'Earning',IF(psa_type=2,'Employee Deduction',IF(psa_type=3,'Employer Deduction',IF(psa_type=4,'Total','Accrual')))) as psa_type, 
				IF(psa_clsfication=1,'Ben & Deduc',IF(psa_clsfication=2,'OT List',IF(psa_clsfication=3,'TUA',IF(psa_clsfication=4,'Govt Contrib',IF(psa_clsfication=5,'Govt/Reg Loan','N/A'))))) as psa_clsfication,
				IF(psa_status=1,'Enabled','Disabled') as psa_status, b.ppc_priority_no,
				CONCAT('$viewLink','$editLink','$delLink') as viewdata
						from payroll_ps_account am
						left join payroll_priority_comp b on (am.psa_id=b.psa_id)
						$criteria
						$strOrderBy";

		// Field and Table Header Mapping
		$arrFields = array(
		 "viewdata"=>"<a href=\"?statpos=mnge_pe&action=add\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/add.png\" title=\"Add New\" border=0 width=\"16\" height=\"16\"></a>"
		,"psa_type" => "Type"
		,"psa_name" => "Name"
		,"psa_clsfication"=>"Group"
/*		,"psa_debit_accnt" => "Debit Account"
		,"psa_credit_accnt" => "Credit Account"*/
		,"ppc_priority_no" => "Priority"
		,"psa_order" => "Order"
		,"psa_status"=>"Status"
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
		$tblDisplayList->tblBlock->assign("title","Manage Pay Element");

		return $tblDisplayList->getTableList($arrAttribs);
	}
	
	function getPrio ($id="", $comp_id_="") {
		$objData = $this->conn->Execute("select a.* from payroll_priority_comp a left join payroll_ps_account b on (a.psa_id=b.psa_id) where a.ppc_stat = '0' or b.psa_id='".$id."'");
		$cResult = array();
		while ( !$objData->EOF ) {       	
			$cResult[] = $objData->fields;        	
        	$objData->MoveNext();
        }
        return $cResult;
	}
	
	function getKeywords(){
		$sql = "select * from app_formula_keywords where app_fkey_isactive=1";
		$objData = $this->conn->Execute($sql);
		while ( !$objData->EOF ) {       	
			$cResult[] = $objData->fields;
        	$objData->MoveNext();
        }
        return $cResult;
	}
	
	function getKeys($keyName){
		$sql = "select * from app_formula_keywords where app_fkey_name=?";
		$objData = $this->conn->Execute($sql,array($keyName));
		if ( !$objData->EOF ) {
			return true;
        } else {
        	return false;
        }
	}
	
	function validateFormula($psa_id){
		$sql = "select ben_isfixed from payroll_ps_account a inner join emp_benefits b on (b.psa_id=a.psa_id) where a.psa_id=? and b.ben_isfixed=1";
		$r = $this->conn->Execute($sql,$psa_id);
		if(!$r->EOF){
			if($r->fields['ben_isfixed'] != NULL and $r->fields['ben_isfixed'] != 0){
				$val = true;
			} else {
				$val = false;
			}
		} else {
			$val = false;
		}
		return $val;
	}
	
	function getFormula($psa_name){
		$sql = "select psa_formula from payroll_ps_account where psa_name=?";
		$r = $this->conn->Execute($sql,$psa_name);
		if(!$r->EOF){
			$val = $r->fields['psa_formula'];
		} else {
			$val = "";
		}
		return $val;
	}
	
	function doValidateFormula($input){
		ini_set('display_errors', false);
		$arr = preg_split("/[\s]*[*|\/|+|-][\s]*/", str_replace(array("(",")"),"",$input), -1);
//		printa($this->getKeys()); 
//		printa($arr); 
//		exit;
		$ctr=0;
		foreach($arr as $val){
			if(!is_numeric($val)){
				if(!$this->getKeys($val)){ $ctr++;}
			}
		}
		if($ctr>0){
			return true;
		}
		$input = str_replace($arr,"1",$input);
//		$result = 0;
		try { 
		  eval('$result = ' . $input . ';');
		  $error = error_get_last();
		} catch (Exception $e) { 
		
		}
		//$error["message"]; exit;
		if(strpos($error["message"], "syntax error") === 0){ 
			return true;
		}
	}
}

?>