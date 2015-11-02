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
class clsMnge_TE{

	var $conn;
	var $fieldMap;
	var $Data;

	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsMnge_TE object
	 */
	function clsMnge_TE($dbconn_ = null){
		$this->conn =& $dbconn_;
		$this->fieldMap = array(
		"comp_id" => "comp_id",
		"te_rdo" => "te_rdo",
		"te_cat_agent" => "te_cat_agent",
		"te_atc" => "te_atc"
		);
	}

	/**
	 * Get the records from the database
	 *
	 * @param string $id_
	 * @return array
	 */
	function dbFetch($id_ = ""){
		$sql = "select * from tax_employer te inner join company_info ci on (te.comp_id=ci.comp_id) where te_id=?";
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
		if (empty($pData_['comp_name'])) {
		 	$isValid = false;
		 	$_SESSION['eMsg'][] = "Please select Company Name.";
		}
		if (empty($pData_['comp_add'])) {
		 	$isValid = false;
		 	$_SESSION['eMsg'][] = "Please select Address.";
		}
		if (empty($pData_['comp_zipcode'])) {
		 	$isValid = false;
		 	$_SESSION['eMsg'][] = "Please select Zip Code.";
		}
		if (empty($pData_['comp_tel'])) {
		 	$isValid = false;
		 	$_SESSION['eMsg'][] = "Please select Telephone No.";
		}
		if (empty($pData_['comp_tin'])) {
		 	$isValid = false;
		 	$_SESSION['eMsg'][] = "Please select Tax Identification No.";
		}
		if (empty($pData_['te_rdo'])) {
		 	$isValid = false;
		 	$_SESSION['eMsg'][] = "Please enter Revenue District Office Code.";
		}
		if (!is_numeric($pData_['te_rdo']) && !empty($pData_['te_rdo'])) {
		 	$isValid = false;
		 	$_SESSION['eMsg'][] = "Please enter a valid Revenue District Office Code.";
		}
		/*if (!is_numeric($pData_['te_atc']) && !empty($pData_['te_atc'])) {
		 	$isValid = false;
		 	$_SESSION['eMsg'][] = "Please enter a valid Alphanumeric Tax Code.";
		}*/
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

		$sql = "insert into tax_employer set $fields";
		if($this->conn->Execute($sql))
		$_SESSION['eMsg']="Successfully Added.";
		else
		$_SESSION['eMsg']=mysql_error();
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

		$sql = "update tax_employer set $fields where te_id=$id";
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
		$sql = "delete from tax_employer where te_id=?";
		
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
				$qry[] = "(comp_name like '%$search_field%' || comp_add like '%$search_field%' || comp_tin like '%$search_field%')";

				// put all query array into one string criteria
				$criteria = " where ".implode(" or ",$qry);
			}
		}

		$arrSortBy = array(
		"viewdata"=>"viewdata",
		"comp_name"=>"comp_name",
		"comp_add"=>"comp_add",
		"comp_tin"=>"comp_tin"
		);

		if(isset($_GET['sortby'])){
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}

		$viewLink = "";
		if ($_GET['statpos']=='mnge_te') {
			$editLink = "<a href=\"?statpos=mnge_te&edit=',te_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/edit.png\" title=\"Edit\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
			$delLink = "<a href=\"?statpos=mnge_te&delete=',te_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\"  border=0 width=\"16\" height=\"16\"></a>";
		}else{
			$popupLink = "<a href=\"javascript:void(0);\" onclick=\"opener.document.getElementById(\'te_id\').value=\'',te_id,'\';
							opener.document.getElementById(\'ud_name\').value=\'',ud.ud_name,'\';
							window.close();\"><img src=\"".SYSCONFIG_THEME_URLPATH.SYSCONFIG_THEME."/images/admin/accept.gif\" title=\"Select\" hspace=\"2px\" border=0></a>";
		}
		$sql = "select ud.*, ci.*, CONCAT('$viewLink','$editLink','$delLink','$popupLink') as viewdata
				from tax_employer ud 
				inner join company_info ci on (ud.comp_id=ci.comp_id)
				$criteria 
				$strOrderBy";

		$arrFields = array(
		"viewdata"=>"Action",
		"comp_name"=>"Registered Name",
		"comp_add"=>"Address",
		"comp_tin"=>"Tax Identification No."
		);

		$arrAttribs = array(
		"viewdata"=>"width='50' align='center'"
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