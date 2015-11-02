<?php
/**
 * Initial Declaration
 */
//$cusnum = array(
//  	 'Custom 1'=>'Custom 1'
//	,'Custom 2'=>'Custom 2'
//	,'Custom 3'=>'Custom 3'
//	,'Custom 4'=>'Custom 4'
//	,'Custom 5'=>'Custom 5'
//	,'Custom 6'=>'Custom 6'
//	,'Custom 7'=>'Custom 7'
//	,'Custom 8'=>'Custom 8'
//	,'Custom 9'=>'Custom 9'
//	,'Custom 10'=>'Custom 10'
//);

$cfStat = array( 1 => 'ACTIVE', 2 => 'INACTIVE');

/**
 * Class Module
 *
 * @author  JIM
 *
 */
class clsMng_CF{

	var $conn;
	var $fieldMap;
	var $Data;

	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsMng_CF object
	 */
	function clsMng_CF($dbconn_ = null){
		$this->conn =& $dbconn_;
		$this->fieldMap = array(
		 "cfhead_code" => "cfhead_code"
		,"cfhead_name" => "cfhead_name"
		,"cfhead_type" => "cfhead_type"
		,"cfhead_stat" => "cfhead_stat"
		);
	}

	/**
	 * Get the records from the database
	 *
	 * @param string $id_
	 * @return array
	 */
	function dbFetch($id_ = ""){
		$sql = "select cfhead_name, cfhead_type from cf_head where cfhead_id=?";
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
		if(!isset($_GET['edit'])){
			$sql = "Select * from cf_head where cfhead_name = '".$pData_['cfhead_name']."'"; 
			$rsResult = $this->conn->Execute($sql);
			if(!$rsResult->EOF){
				$_SESSION['eMsg'][] = "Custom Field already exists.";
				$isValid = false;
			}
		}
		if (strlen($pData_['cfhead_type']=='N/A')) {
			$isValid = false;
			$_SESSION['eMsg'][] = "Please Select Custom type.";
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
			$valData = addslashes($valData);
			$flds[] = "$keyData='$valData'";
		}
		$flds[] = "cfhead_addwho = '".AppUser::getData('user_name')."'";
		$fields = implode(", ",$flds);
		
		$sql = "INSERT INTO cf_head SET $fields";
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
		$flds[] = "cfhead_updatewho = '".AppUser::getData('user_name')."'";
		$flds[] = "cfhead_updatewhen = '".date('Y-m-d h:i:s')."'";
		$fields = implode(", ",$flds);

		$sql = "update cf_head set $fields where cfhead_id=$id";
		$this->conn->Execute($sql);
				
		$_SESSION['eMsg']="Successfully Updated.";
	}

	/**
	 * Delete Record
	 *
	 * @param string $id_
	 */
	function doDelete($id_ = ""){
		$sql = "delete from cf_head where cfhead_id=?";
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
				$qry[] = "cfhead_name like '%$search_field%'";

			}
		}

		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		 "viewdata"=>"viewdata"
		,"cfhead_name"=>"cfhead_name"
		,"cfhead_type"=>"cfhead_type"
		);

		if(isset($_GET['sortby'])){
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}

		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$viewLink = "";
		$editLink = "<a href=\"?statpos=mng_cf&edit=',am.cfhead_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/edit.png\" title=\"Edit\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
		$delLink = "<a href=\"?statpos=mng_cf&delete=',am.cfhead_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\"  border=0 width=\"16\" height=\"16\"></a>";
		$action = "<a href=\"?statpos=mng_cf&action=add\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/add.png\" title=\"Add New\" border=0 width=\"16\" height=\"16\"></a>";

		// SqlAll Query
		$sql = "select am.*, CONCAT('$viewLink','$editLink','$delLink') as viewdata
						from cf_head am
						$criteria
						$strOrderBy";

		// Field and Table Header Mapping
		$arrFields = array(
		 "viewdata"=>"Action"
		,"cfhead_name"=>"Custom Name"
		,"cfhead_type"=>"Custom Type"
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

}

?>