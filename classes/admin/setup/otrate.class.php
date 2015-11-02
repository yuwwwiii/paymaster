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
class clsMnge_OTR{

	var $conn;
	var $fieldMap;
	var $Data;

	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsMnge_OTR object
	 */
	function clsMnge_OTR($dbconn_ = null){
		$this->conn =& $dbconn_;
		$this->fieldMap = array(
		"otr_name" => "otr_name",
		"otr_desc" => "otr_desc",
		"otr_type" => "otr_type",
		"otr_factor" => "otr_factor",
		"otr_max" => "otr_max",
		);
	}

	/**
	 * Get the records from the database
	 *
	 * @param string $id_
	 * @return array
	 */
	function dbFetch($id_ = ""){
		$objClsMngeDecimal = new Application();
		$sql = "select *,FORMAT(otr_factor,".$objClsMngeDecimal->getGeneralDecimalSettings().") as otr_factor,
				FORMAT(otr_max,".$objClsMngeDecimal->getGeneralDecimalSettings().") as otr_max from ot_rates where otr_id=?";
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
	function doPopulateData($pData_ = array()){
		if(count($pData_)>0){
			foreach ($this->fieldMap as $key => $value) {
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

		if (empty($pData_['otr_name'])) {
		 	$isValid = false;
		 	$_SESSION['eMsg'][] = "Please enter a Code.";
		}
		
		if (empty($pData_['otr_desc'])) {
		 	$isValid = false;
		 	$_SESSION['eMsg'][] = "Please enter a Description.";
		}
		
		if (empty($pData_['otr_factor'])) {
		 	$isValid = false;
		 	$_SESSION['eMsg'][] = "Please enter a Rate Factor.";
		}
		
		if (!is_numeric($pData_['otr_factor']) && !empty($pData_['otr_factor'])) {
		 	$isValid = false;
		 	$_SESSION['eMsg'][] = "Please enter a valid Rate Factor.";
		}
		
		if (!is_numeric($pData_['otr_max']) && !empty($pData_['otr_max'])) {
		 	$isValid = false;
		 	$_SESSION['eMsg'][] = "Please enter a valid Maximum Rate Amount.";
		}
		
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
		$flds[] = "otr_addwho = '".AppUser::getData('user_name')."'";
		$fields = implode(", ",$flds);
		$sql = "INSERT INTO ot_rates SET $fields";
		if($this->conn->Execute($sql))
			$_SESSION['eMsg']="Successfully Added.";
		else 
			$_SESSION['eMsg']=mysql_error();
	}

	/**
	 * Save Update
	 *
	 */
	function doSaveEdit($id){
		$flds = array();
		foreach ($this->Data as $keyData => $valData) {
			$valData = trim(addslashes($valData));
			$flds[] = "$keyData='$valData'";
		}
		$flds[] = "otr_updatewho = '".AppUser::getData('user_name')."'";
		$flds[] = "otr_updatewhen = '".date('Y-m-d h:i:s')."'";
		$fields = implode(", ",$flds);
		$sql = "UPDATE ot_rates set $fields WHERE otr_id=$id";
		if($this->conn->Execute($sql))
		$_SESSION['eMsg']="Successfully Updated.";
		else
		$_SESSION['eMsg']=mysql_error();
	}

	/**
	 * Delete Record
	 *
	 * @param string $id_
	 */
	function doDelete($id_ = ""){
		$sql = "delete from ot_rates where otr_id=?";
		
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
				$qry[] = "ud.otr_name like '%$search_field%'|| otr_desc like '%$search_field%'";

				// put all query array into one string criteria
				$criteria = " where ".implode(" or ",$qry);
			}
		}

		$arrSortBy = array(
		 "viewdata"=>"viewdata"
		,"otr_name"=>"otr_name"
		,"otr_desc"=>"otr_desc"
		,"otr_type"=>"otr_type"
		,"otr_factor"=>"otr_factor"
		,"otr_max"=>"otr_max"
		);

		if(isset($_GET['sortby'])){
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}

		$viewLink = "";
		if ($_GET['statpos']=='otrate') {
			$editLink = "<a href=\"?statpos=otrate&edit=',otr_id,'&otype=',otr_type,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/edit.png\" title=\"Edit\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
			$delLink = "<a href=\"?statpos=otrate&delete=',otr_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\"  border=0 width=\"16\" height=\"16\"></a>";
			$action = "Action";
		} else if ($_GET['statpos']=='otreport'){
			$action = "";
		} else {
			$action = "Action";
			$popupLink = "<a href=\"javascript:void(0);\" onclick=\"opener.document.getElementById(\'te_id\').value=\'',te_id,'\';
							opener.document.getElementById(\'ud_name\').value=\'',ud.ud_name,'\';
							window.close();\"><img src=\"".SYSCONFIG_THEME_URLPATH.SYSCONFIG_THEME."/images/admin/accept.gif\" title=\"Select\" hspace=\"2px\" border=0></a>";
		}
		$objClsMngeDecimal = new Application();
		$sql = "select ud.*, 
				FORMAT(ud.otr_factor,".$objClsMngeDecimal->getGeneralDecimalSettings().") as otr_factor,
				FORMAT(ud.otr_max,".$objClsMngeDecimal->getGeneralDecimalSettings().") as otr_max,
				CONCAT('$editLink','$delLink') as viewdata from ot_rates ud $criteria $strOrderBy";

		$sqlcount = "select count(*) as mycount from ot_rates ud $criteria";

		$arrFields = array(
		"viewdata"=>$action 
		,"otr_name"=>"Code"
		,"otr_desc"=>"Description"
		,"otr_type"=>"Type"
		,"otr_factor"=>"Factor Rate"
		,"otr_max"=>"Maximum Rate Amount"
		);

		$arrAttribs = array(
		"viewdata"=>"width='50' align='center'",
		"otr_type"=>"align='center'",
		"otr_factor"=>"width='60' align='center'",
		"otr_max"=>"width='90' align='center'"
		);

		$tblDisplayList = new clsTableList($this->conn);
		$tblDisplayList->arrFields = $arrFields;
		$tblDisplayList->paginator->linkPage = "?$queryStr";
		$tblDisplayList->sqlAll = $sql;
		$tblDisplayList->sqlCount = $sqlcount;

		return $tblDisplayList->getTableList($arrAttribs);
	}

}

?>