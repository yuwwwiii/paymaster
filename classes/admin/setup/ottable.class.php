<?php
/**
 * Initial Declaration
 */

/**
 * Class Module
 * @author  JIM
 */
class clsMnge_OT{
	var $conn;
	var $fieldMap;
	var $Data;

	/**
	 * Class Constructor
	 * @param object $dbconn_
	 * @return clsMnge_OT object
	 */
	function clsMnge_OT($dbconn_ = null){
		$this->conn =& $dbconn_;
		$this->fieldMap = array(
		"ot_name" => "ot_name",
		"ot_desc" => "ot_desc",
		"ot_istax" => "ot_istax"
		);
	}

	/**
	 * Get the records from the database
	 * @param string $id_
	 * @return array
	 */
	function dbFetch($id_ = "") {
		$sql = "select * from ot_tbl where ot_id=?";
		$rsResult = $this->conn->Execute($sql, array($id_));
		if (!$rsResult->EOF) {
			return $rsResult->fields;
		}
	}
	/**
	 * Populate array parameters to Data Variable
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
	 * @param array $pData_
	 * @return bool
	 */
	function doValidateData($pData_ = array()){
		$isValid = true;
		if (empty($pData_['ot_name'])) {
		 	$isValid = false;
		 	$_SESSION['eMsg'][] = "Please enter a Name.";
		}
		if (empty($pData_['ot_desc'])) {
		 	$isValid = false;
		 	$_SESSION['eMsg'][] = "Please enter a Description.";
		}
		return $isValid;
	}

	/**
	 * Save New
	 */
	function doSaveAdd(){
		$flds = array();
		foreach ($this->Data as $keyData => $valData) {
			$valData = trim(addslashes($valData));
			$flds[] = "$keyData='$valData'";
		}
		$flds[]="ot_addwho='".AppUser::getData('user_name')."'";
		$fields = implode(", ",$flds);

		$sql = "insert into ot_tbl set $fields";
		if($this->conn->Execute($sql))
		$_SESSION['eMsg']="Successfully Added.";
		else 
		$_SESSION['eMsg']=mysql_error();
	}

	/**
	 * Save Update
	 */
	function doSaveEdit(){
		$id = $_GET['ot_id'];
		$flds = array();
		foreach ($this->Data as $keyData => $valData) {
			$valData = trim(addslashes($valData));
			$flds[] = "$keyData='$valData'";
		}
		$flds[]="ot_updatewho='".AppUser::getData('user_name')."'";
		$flds[]="ot_updatewhen='".date('Y-m-d H:i:s')."'";
		$fields = implode(", ",$flds);

		$sql = "update ot_tbl set $fields where ot_id=$id";
		if($this->conn->Execute($sql))
		$_SESSION['eMsg']="Successfully Updated.";
		else
		$_SESSION['eMsg']=mysql_error();
	}

	/**
	 * Delete Record
	 * @param string $id_
	 */
	function doDelete($id_ = "",$otr=""){
		if(empty($otr)){
			$sql = "delete from ot_tbl where ot_id='".$id_."'";
		}else{
			$sql = "delete from ot_tr where ot_id='".$id_."' AND otr_id='".$otr."'";
		}
		if($this->conn->Execute($sql)){
			$_SESSION['eMsg']="Successfully Deleted.";
		}else{
			$_SESSION['eMsg']=mysql_error();
		}
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
		if (isset($_REQUEST['search_field'])) {
			// lets check if the search field has a value
			if (strlen($_REQUEST['search_field'])>0) {
				// lets assign the request value in a variable
				$search_field = $_REQUEST['search_field'];
				// create a custom criteria in an array
				$qry[] = "ud.te_name like '%$search_field%'";
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
		);

		if(isset($_GET['sortby'])){
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}
		
		$viewLink = "";
		$editLink = "<a href=\"?statpos=ottable&edit=',otr_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/edit.png\" title=\"Edit\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
		$delLink = "<a href=\"?statpos=ottable&deletetr=".$_GET['ot_id']."&deleteotr=',otr_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete ',otr_name,'?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";

		unset($r,$imp);
		//Get ot rates:	
		$sql = "select otr_id from ot_tr where ot_id='".$_GET['ot_id']."'";
		$rsResult = $this->conn->Execute($sql);
		
		while(!$rsResult->EOF){
		$default[] = $rsResult->fields['otr_id'];
		$rsResult->MoveNext();
		}
		
		if($default != 0){
			$rates = " where otr_id IN (".implode(",",$default).")"; 
		}else {
			$rates = " where otr_id='-1'";
		}
		$objClsMngeDecimal = new Application();
		$sql = "select *, FORMAT(otr_factor,".$objClsMngeDecimal->getGeneralDecimalSettings().") as otr_factor, CONCAT('$delLink') as viewdata from ot_rates $rates";
		$sqlcount = "select count(*) as mycount from ot_rates $rates";

		$arrFields = array(
		 "viewdata"=>""
		,"otr_name"=>"OT Rates ID"
		,"otr_desc"=>"Description"
		,"otr_type"=>"Type"
		,"otr_factor"=>"Factor Rate"
		);
		$arrAttribs = array(
			"viewdata"=>"width='30' align='center'"
		);
		$tblDisplayList = new clsTableList($this->conn);
		$tblDisplayList->arrFields = $arrFields;
		$tblDisplayList->paginator->linkPage = "?$queryStr";
		$tblDisplayList->sqlAll = $sql;
		$tblDisplayList->sqlCount = $sqlcount;
		return $tblDisplayList->getTableList($arrAttribs);
	}
	
	function ot_tbls(){
		unset($_SESSION['ot_tbls']);
		$sql = "SELECT ot_id,ot_name,ot_desc,ot_rates,ot_istax FROM ot_tbl ORDER BY ot_id asc";
		$rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF){
			$otname[] = $rsResult->fields['ot_name'];
			$otvalue[]  = $rsResult->fields['ot_id'];
			$rsResult->MoveNext();
		}
		$_SESSION['otvalue'] =  $otvalue;
		return $otname;	
		}
}
?>