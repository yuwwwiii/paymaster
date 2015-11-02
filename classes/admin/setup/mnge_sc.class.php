<?php
/**
 * Initial Declaration
 */


/**
 * Class Module
 *
 * @author  JIMabignay
 *
 */
class clsMnge_SC {

	var $conn;
	var $fieldMap;
	var $Data;

	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsMnge_SC object
	 */
	function clsMnge_SC ($dbconn_ = null) {
		$this->conn =& $dbconn_;
		$this->fieldMap = array(
		 "sc_code" => "sc_code"
		,"sc_desc" => "sc_desc"
		,"dec_id" => "dec_id"
		,"sc_effectivedate" => "sc_effectivedate"
		);
		
		$this->fieldMap2 = array(
		 "max_salary" => "max_salary"
		,"min_salary" => "min_salary"
		,"max_age" => "max_age"
		,"min_age" => "min_age"
        ,"sc_id" => "sc_id"
		,"scr_id" => "scr_id"
		,"scr_er" => "scr_er"
		,"scr_ee" => "scr_ee"
		,"scr_ec" => "scr_ec"
		,"scr_pcent" => "scr_pcent"
		,"scr_pcentamnt" => "scr_pcentamnt"
		);
	}

	/**
	 * Get the records from the database
	 *
	 * @param string $id_
	 * @return array
	 */
	function dbFetch ($id_ = "",$field='') {
		$field = empty($field)?"statutory_contribution where sc_id=?":"sc_records where scr_id=?";
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
	function doPopulateData ($pData_ = array(),$isForm_ = false,$useAderTable = false) {
		$fMap = $useAderTable?$this->fieldMap2:$this->fieldMap;
		
		if (count($pData_)>0) {
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
	function doValidateData ($pData_ = array()) {
		$isValid = true;
		
//		if (empty($pData_['sc_code'])) {
//			$isValid = false;
//			$_SESSION['eMsg'][] = "Please enter Statutory Name.";
//		}
		
		return $isValid;
	}

	/**
	 * Save New
	 *
	 */
	function doSaveAdd ($tbl='statutory_contribution') {
		$flds = array();
		foreach ($this->Data as $keyData => $valData) {
			$valData = trim(addslashes($valData));
			$flds[] = "$keyData='$valData'";
		}
		$fields = implode(", ",$flds);

		$sql = "insert into $tbl set $fields";
		
		if ($this->conn->Execute($sql) AND (!empty($_POST['dec_id']) OR ($tbl=='sc_records'))) {
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
	function doSaveEdit ($tbl='statutory_contribution') {
		$flds = array();
		foreach ($this->Data as $keyData => $valData) {
			$valData = trim(addslashes($valData));
			$flds[] = "$keyData='$valData'";
		}
		$fields = implode(", ",$flds);
		
		if ($tbl=='sc_records') {
			$id = $_POST['scr_id'];
			$sql = "update $tbl set $fields where scr_id=$id";
		} else {
			$id = $_GET['sc_id'];
			$sql = "update statutory_contribution set $fields where sc_id=$id";
		}
		
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
	function doDelete ($id_ = "",$tbl,$key='sc_id') {
		$sql = "delete from ".$tbl." where ".$key."=?";
		
		if ($this->conn->Execute($sql,array($id_))) {
			$_SESSION['eMsg']="Successfully Deleted.";
		} else {
			$_SESSION['eMsg']=mysql_error();
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
		if (isset($_REQUEST['search_field'])) {

			// lets check if the search field has a value
			if (strlen($_REQUEST['search_field'])>0) {
				// lets assign the request value in a variable
				$search_field = $_REQUEST['search_field'];

				// create a custom criteria in an array
				$qry[] = "ud.min_salary LIKE '%$search_field%'";

				// put all query array into one string criteria
				//$criteria = " where ".implode(" or ",$qry);
			}
		}
		
		$arrSortBy = array(
		"viewdata"=>"Action",
		"min_salary"=>"min_salary",
		"max_salary"=>"max_salary",
		"min_age"=>"min_age",
		"max_age"=>"max_age",
		"scr_er"=>"scr_er",
		"scr_ee"=>"scr_ee",
		"scr_ec"=>"scr_ec",
		"scr_pcent"=>"scr_pcent",
		"scr_pcentamnt"=>"scr_pcentamnt"
		);

		if(isset($_GET['sortby'])){
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}else{
			$strOrderBy = " order by scr_id asc";
		}

		$viewLink = "";
		if ($_GET['statpos']=='mnge_sc') {
			$editLink = "<a href=\"?statpos=mnge_sc&dec_id=".$_GET['dec_id']."&edit=".$_GET['edit']."&sc_id=',sc_id,'&scr_id=',scr_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/edit.png\" title=\"Edit\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
			$delLink = "<a href=\"?statpos=mnge_sc&dec_id=".$_GET['dec_id']."&deletescrecords=".$_GET['edit']."&sc_id=',sc_id,'&scr_id=',scr_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
		} else {
			$popupLink = "<a href=\"javascript:void(0);\" onclick=\"opener.document.getElementById(\'scr_id\').value=\'',scr_id,'\';
							opener.document.getElementById(\'min_age\').value=\'',ud.min_age,'\';
							window.close();\"><img src=\"".SYSCONFIG_THEME_URLPATH.SYSCONFIG_THEME."/images/admin/accept.gif\" title=\"Select\" hspace=\"2px\" border=0></a>";
		}
		$wherec  = isset($_GET['sc_id'])?"where sc_id='".$_GET['sc_id']."'":"where sc_id=0";
		$objClsMngeDecimal = new Application();
		$sql = "select ud.*,
					FORMAT(ud.min_salary,".$objClsMngeDecimal->getGeneralDecimalSettings().") as min_salary,
					FORMAT(ud.max_salary,".$objClsMngeDecimal->getGeneralDecimalSettings().") as max_salary,
					FORMAT(ud.min_age,".$objClsMngeDecimal->getGeneralDecimalSettings().") as min_age,
					FORMAT(ud.max_age,".$objClsMngeDecimal->getGeneralDecimalSettings().") as max_age,
					FORMAT(ud.scr_er,".$objClsMngeDecimal->getGeneralDecimalSettings().") as scr_er,
					FORMAT(ud.scr_ee,".$objClsMngeDecimal->getGeneralDecimalSettings().") as scr_ee,
					FORMAT(ud.scr_ec,".$objClsMngeDecimal->getGeneralDecimalSettings().") as scr_ec,
					FORMAT(ud.scr_pcent,".$objClsMngeDecimal->getGeneralDecimalSettings().") as scr_pcent,
					FORMAT(ud.scr_pcentamnt,".$objClsMngeDecimal->getGeneralDecimalSettings().") as scr_pcentamnt,
					CONCAT('$viewLink','$editLink','$delLink','$popupLink') as viewdata from sc_records  ud $criteria $wherec $strOrderBy ";

		$sqlcount = "select count(*) as mycount from sc_records ud $criteria where sc_id='0'";

		$arrFields = array(
		"viewdata"=>"Action",
		"min_salary"=>"Minimum Salary",
		"max_salary"=>"Maximum Salary",
		"min_age"=>"Minimum Statutory Age",
		"max_age"=>"Maximum Statutory Age",
		"scr_er"=>"ER",
		"scr_ee"=>"EE",
		"scr_ec"=>"EC",
		"scr_pcent"=>"%",
		"scr_pcentamnt"=>"+Amount"
		);

		$arrAttribs = array(
		"viewdata"=>"width='50' align='center'",
		"scr_pcentamnt"=>"width='55'"
		);

		$tblDisplayList = new clsTableList($this->conn);
		$tblDisplayList->arrFields = $arrFields;
		$tblDisplayList->paginator->linkPage = "?$queryStr";
		$tblDisplayList->sqlAll = $sql;
		$tblDisplayList->sqlCount = $sqlcount;

		return $tblDisplayList->getTableList($arrAttribs);
	}

	function statutory(){
		unset($_SESSION['statutory']);
		$wherec = isset($_GET['dec_id'])?"where dec_id=".$_GET['dec_id']:'where dec_id=0';
		$sql = "select sc_code,sc_id from statutory_contribution ".$wherec." order by dec_id asc";
		$rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF){
			$statutory[] = $rsResult->fields['sc_code'];
			$statvalue[] = $rsResult->fields['sc_id'];
			$rsResult->MoveNext();
		}
		$_SESSION['statvalue'] =  $statvalue;
		return $statutory;
	
	}
	
	function scheme(){
		unset($_SESSION['schemevalue']);
		$sql = "select dec_code,dec_id from deduction_type order by dec_id asc";
		$rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF){
			if($rsResult->fields['dec_code'] != 'TAX')
			{
			$deductype[] = $rsResult->fields['dec_code'];
			$deducvalue[]  = $rsResult->fields['dec_id'];
			}
			$rsResult->MoveNext();
		}
		$_SESSION['schemevalue'] =  $deducvalue;
		return $deductype;
	}
}
?>