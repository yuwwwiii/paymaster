<?php
/**
 * Initial Declaration
 */


/**
 * Class Module
 *
 * @author  Jason I. Mabignay
 *
 */
class clsMnge_TT{

	var $conn;
	var $fieldMap;
	var $Data;
	var $paygroup = array(
	 '1'=>'Daily'
	,'2'=>'Weekly'
	,'3'=>'Semi-Monthly'
	,'4'=>'Monthly'
	,'5'=>'Annual'
	,'6'=>'Expat'
	);
	
	var $tax_table = array(
	 'tt_exemption'=>'tt_exemption'
	,'tt_pay_group'=>'tt_pay_group'
	,'tt_maxamount'=>'tt_maxamount'
	,'tt_minamount'=>'tt_minamount'
	,'tt_taxamount'=>'tt_taxamount'
	,'tt_over_pct'=>'tt_over_pct'
	,'tp_id' => 'tax_policy_tp_id'
	,'tt_other_benefits'=>'tt_other_benefits'
	,'tt_no_q_dependents'=>'tt_no_q_dependents'
	,'tt_head_family'=>'tt_head_family'
	,'tt_married_ind'=>'tt_married_ind'
	,'tt_each_dependent'=>'tt_each_dependent'
	,'tt_num_dependents'=>'tt_num_dependents'
	,'tt_max_premium'=>'tt_max_premium'
	);
	
	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsMnge_TT object
	 */
	 
	function clsMnge_TT($dbconn_ = null){
		$this->conn =& $dbconn_;
		$this->fieldMap = array(
		 "tp_name" => "tp_name"
		,"tp_desc" => "tp_desc"
		,"tp_edate" => "tp_edate"
		,"tp_no_q_dependents" => "tp_no_q_dependents"
		,"tp_head_family" => "tp_head_family"
		,"tp_married_ind" => "tp_married_ind"
		,"tp_each_dependent" => "tp_each_dependent"
		,"tp_num_dependents" => "tp_num_dependents"
		,"tp_other_benefits" => "tp_other_benefits"
		,"tp_max_premium" => "tp_max_premium"
		);
	}
	
	/**
	 * Get the records from the database
	 *
	 * @param string $id_
	 * @return array
	 */
	function dbFetch($id_ = "",$field=''){
		$field = empty($field)?"tax_policy where tp_id=?":"sc_records where scr_id=?";
		$sql = "select * from ".$field;
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
	function doPopulateData($pData_ = array(),$isForm_ = false,$useAderTable = false){
		$fMap = $useAderTable?$this->tax_table:$this->fieldMap;
		if(count($pData_)>0){
			foreach ($fMap as $key => $value) {
				$this->Data[$key] = $pData_[$value];
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
	function doSaveAdd($tbl='tax_policy') {
		$flds = array();
		foreach ($this->Data as $keyData => $valData) {
			if($keyData=='tt_exemption'){
				if ($_POST['tt_exemption']=='1') {
					$valData = '1';
				} elseif ($_POST['tt_exemption']=='3' || $_POST['tt_exemption']=='8') {
					$valData = '3';
				} elseif ($_POST['tt_exemption']=='4' || $_POST['tt_exemption']=='9') {
					$valData = '4';
				} elseif ($_POST['tt_exemption']=='5' || $_POST['tt_exemption']=='10') {
					$valData = '5';
				} elseif ($_POST['tt_exemption']=='6' || $_POST['tt_exemption']=='11') {
					$valData = '6';
				} elseif ($_POST['tt_exemption']=='7' || $_POST['tt_exemption']=='12') {
					$valData = '7';
				} else {
					$valData = '';
				}
			}
			$flds[] = "$keyData='$valData'";
		}
		
		$fields = implode(", ",$flds);
		
		if ($tbl == 'tax_table') { $tbl = 'tax_table'; }
		$sql = "insert into $tbl set $fields";
		
		if($this->conn->Execute($sql)) {
			$_SESSION['eMsg']="Successfully Added.";
		} else if (empty($_POST['dec_id'])) {
			$_SESSION['eMsg']="Please select scheme.";
		} else {
			$_SESSION['eMsg']=mysql_error();
		}
	}
	

	/**
	 * Save Update
	 *
	 */
	function doSaveEdit($tbl='tax_policy') {
		$flds = array();
		foreach ($this->Data as $keyData => $valData) {
			$flds[] = "$keyData='$valData'";
		}
		$fields = implode(", ",$flds);
		
		if ($tbl=='sc_records') {
			$id = $_POST['scr_id'];
			$sql = "update $tbl set $fields where scr_id=$id";
		}
		
		else {
			$id = $_GET['tp_id'];
			$sql = "update $tbl set $fields where tp_id=$id";
		}
		
		if ($this->conn->Execute($sql))
		$_SESSION['eMsg']="Successfully Updated.";
		else
		$_SESSION['eMsg']=mysql_error();
	}
	

	function tt_update() {
		$this->doPopulateData($_POST,false,true);
		foreach ($this->Data as $keyData => $valData) {
			if($keyData=='tt_exemption'){
				if ($_POST['tt_exemption']=='1') {
					$valData = '1';
				} elseif ($_POST['tt_exemption']=='3' || $_POST['tt_exemption']=='8') {
					$valData = '3';
				} elseif ($_POST['tt_exemption']=='4' || $_POST['tt_exemption']=='9') {
					$valData = '4';
				} elseif ($_POST['tt_exemption']=='5' || $_POST['tt_exemption']=='10') {
					$valData = '5';
				} elseif ($_POST['tt_exemption']=='6' || $_POST['tt_exemption']=='11') {
					$valData = '6';
				} elseif ($_POST['tt_exemption']=='7' || $_POST['tt_exemption']=='12') {
					$valData = '7';
				} else {
					$valData = '';
				}
			}
			$flds[] = "$keyData='$valData'";
		}
		$fields = implode(", ",$flds);
		$sql = "update tax_table set $fields where tt_id=".$_GET['editt'];
		if ($this->conn->Execute($sql)) {
		$_SESSION['eMsg']="Successfully Updated.";
		} else {
		$_SESSION['eMsg']=mysql_error();
		}
	}

	/**
	 * Delete Record
	 *
	 * @param string $id_
	 */
	function doDelete($id_ = "",$tbl,$key='tp_id') {
		$sql = "delete from ".$tbl." where ".$key."=?";
		
		if($this->conn->Execute($sql,array($id_)))
		$_SESSION['eMsg']="Successfully Deleted.";
		else
		$_SESSION['eMsg']=mysql_error();
	}

	/**
	 * Get all the Table Listings
	 *
	 * @return array
	 */

	

	/**
	 * Get all the Table Listings
	 *
	 * @return array
	 */
	function getTableList() {
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
				$qry[] = "tt_maxamount like '%$search_field%' || tt_minamount like '%$search_field%' || tt_over_pct like '%$search_field%' || tt_taxamount like '%$search_field%'";
			}
		}
		if($_GET['paygroup']!=5){
			if ($_GET['exemption']=='1') {
				$qry[] = "tt_exemption='1'";
			}elseif($_GET['exemption']=='3' || $_GET['exemption']=='8') {
				$qry[] = "tt_exemption='3'";
			} elseif ($_GET['exemption']=='4' || $_GET['exemption']=='9') {
				$qry[] = "tt_exemption='4'";
			} elseif ($_GET['exemption']=='5' || $_GET['exemption']=='10') {
				$qry[] = "tt_exemption='5'";
			} elseif ($_GET['exemption']=='6' || $_GET['exemption']=='11') {
				$qry[] = "tt_exemption='6'";
			} elseif ($_GET['exemption']=='7' || $_GET['exemption']=='12') {
				$qry[] = "tt_exemption='7'";
			} else {
				$qry[] = "tt_exemption=''";
			}
		}
		$qry[] = "tt_pay_group='".$_GET['paygroup']."'";
		$qry[] = "tp_id='".$_GET['tp_id']."'";
		
		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		 "viewdata"=>"viewdata"
		,"tt_id"=>"tt_id"
		,"tt_maxamount"=>"tt_maxamount"
		,"tt_minamount"=>"tt_minamount"
		,"tt_over_pct"=>"tt_over_pct"
		,"tt_taxamount"=>"tt_taxamount"
		);

		if (isset($_GET['sortby'])) {
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}
		// note:
		/// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$viewLink = "";
		// SqlAll Query
		
		//Get first record note:	
		//$sql = "select tp_id from tax_policy order by tp_id asc";
		//$rsResult = $this->conn->Execute($sql);
		//$default = $rsResult->fields['tp_id'];

		$editLink = "<a href=\"?statpos=mnge_tt&tp_id=".$_GET['tp_id']."&edit=".$_GET['edit']."&paygroup=".$_GET['paygroup']."&exemption=".$_GET['exemption']."&editt=',tt_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/edit.png\" title=\"Edit\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
		$delLink = "<a href=\"?statpos=mnge_tt&tp_id=".$_GET['tp_id']."&edit=".$_GET['edit']."&paygroup=".$_GET['paygroup']."&exemption=".$_GET['exemption']."&deletett=',tt_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\"  border=0 width=\"16\" height=\"16\"></a>";

//		if($_GET['paygroup']!=5) $annualize = "AND tt_exemption='".$_GET['exemption']."'";  else $annualize = "";
		$objClsMngeDecimal = new Application();
		$sql = "select *,
				FORMAT(tt_maxamount,".$objClsMngeDecimal->getGeneralDecimalSettings().") as tt_maxamount,
				FORMAT(tt_minamount,".$objClsMngeDecimal->getGeneralDecimalSettings().") as tt_minamount,
				FORMAT(tt_over_pct,".$objClsMngeDecimal->getGeneralDecimalSettings().") as tt_over_pct,
				FORMAT(tt_taxamount,".$objClsMngeDecimal->getGeneralDecimalSettings().") as tt_taxamount,
				CONCAT('$editLink','$delLink') as viewdata 
				from tax_table 
				$criteria";
		

		// Field and Table Header Mapping
		//note:
		$arrFields = array(
		 "viewdata"=>"Action"
		,"tt_id"=>""		
		,"tt_maxamount"=>"Maximum Tax Amount"
		,"tt_minamount"=>"Minimum Tax Amount"
		,"tt_over_pct"=>"% Over"
		,"tt_taxamount"=>"Tax Amount"
		);
		
		// Column (table data) User Defined Attributes
		$arrAttribs = array(
		"mnu_ord"=>" align='center'",
		"viewdata"=>"width='50' align='center'",
		"tt_id"=>" style='display:none;' "
		);

		// Process the Table List
		$tblDisplayList = new clsTableList($this->conn);
		$tblDisplayList->arrFields = $arrFields;
		$tblDisplayList->paginator->linkPage = "?$queryStr";
		$tblDisplayList->sqlAll = $sql;
		$tblDisplayList->sqlCount = $sqlcount;
		
		//$trID note: name,primary key of base table
		$tblDisplayList->trID = 'tt_id';
		return $tblDisplayList->getTableList($arrAttribs);
	}
	
		function scheme() {
			unset($_SESSION['schemevalue']);
			$sql = "select dec_code,dec_id from deduction_type order by dec_id asc";
			$rsResult = $this->conn->Execute($sql);
			while(!$rsResult->EOF){
				if($rsResult->fields['dec_code'] == 'TAX')
				{
				$deductype[] = $rsResult->fields['dec_code'];
				$deducvalue[]  = $rsResult->fields['dec_id'];
				break;
				}
				$rsResult->MoveNext();
			}
			$_SESSION['schemevalue'] =  $deducvalue;
			return $deductype;
		}
		
		function exemption(){
			unset($_SESSION['exemptionvalue']);
			$sql = "select taxep_id,taxep_name from tax_excep order by taxep_id asc";
			$rsResult = $this->conn->Execute($sql);
			while(!$rsResult->EOF){			
				$id[] = $rsResult->fields['taxep_id'];
				$value[]  = $rsResult->fields['taxep_name'];
				$rsResult->MoveNext();
			}
			$_SESSION['exemptionid'] =  $id;
			return $value;
		}
		
		function policy(){
			unset($_SESSION['policyvalue']);
			$sql = "select tp_id,tp_name from tax_policy order by tp_id asc";
			$rsResult = $this->conn->Execute($sql);
			while(!$rsResult->EOF){			
				$id[] = $rsResult->fields['tp_id'];
				$value[]  = $rsResult->fields['tp_name'];
				$rsResult->MoveNext();
			}
			$_SESSION['policyvalue'] =  $id;
			return $value;
		}
		
		function tax_table(){
			$sql = "select tt_maxamount, tt_minamount, tt_over_pct, tt_taxamount, tt_each_dependent, tt_other_benefits, tt_no_q_dependents, tt_head_family, tt_married_ind, tt_num_dependents, tt_max_premium from tax_table where tt_id=".$_GET['editt'];
			$rsResult = $this->conn->Execute($sql);
				$value[] = $rsResult->fields;
			return $value;
		}
		
		function getTaxPolicy($tp_id = null){
			if($tp_id == null){ return null;}
			$sql = "select * from tax_policy where tp_id=".$tp_id;
			$rsResult = $this->conn->Execute($sql);
			if(!$rsResult->EOF){			
				return $rsResult->fields;
			}
		}
}
?>