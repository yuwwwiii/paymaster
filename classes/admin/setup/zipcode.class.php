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
class clsZipCode{

	var $conn;
	var $fieldMap;
	var $Data;

	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsZipCode object
	 */
	function clsZipCode($dbconn_ = null){
		$this->conn =& $dbconn_;
		$this->fieldMap = array(
		 "p_id" => "p_id"
		,"zipcode_name" => "zipcode_name"
		,"zipcode" => "zipcode"
		);
	}

	/**
	 * Get the records from the database
	 *
	 * @param string $id_
	 * @return array
	 */
	function dbFetch($id_ = ""){
		$sql = "SELECT hz.zipcode ,hp.province_name ,hz.zipcode_name ,hz.zipcode_id, hz.p_id
				FROM app_province as hp 
				INNER JOIN app_zipcodes as hz ON (hp.p_id = hz.p_id)
				where hz.zipcode_id = ? ";
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
	function doValidateData($pData_ = array()){
		$isValid = true;

		if (empty($pData_['province_name'])) {
		 	$isValid = false;
		 	$_SESSION['eMsg'][] = "Please select Province.";
		}
		
		if (empty($pData_['zipcode_name'])) {
		 	$isValid = false;
		 	$_SESSION['eMsg'][] = "Please enter Zip Code Name.";
		}
		
		if (empty($pData_['zipcode'])) {
		 	$isValid = false;
		 	$_SESSION['eMsg'][] = "Please enter Zip Code.";
		}
		
		if (!is_numeric($pData_['zipcode']) && !empty($pData_['zipcode'])) {
		 	$isValid = false;
		 	$_SESSION['eMsg'][] = "Please enter a valid Zip Code.";
		}
		
//		if (!isset($_GET['edit'])) {
//        	$sql = "SELECT  *
//					FROM hris_zipcodes
//                	WHERE zipcode = ?";
//			$rsResult = $this->conn->Execute($sql,array($pData_['zipcode']));
//			if (!$rsResult->EOF) {
//				$isValid = false;
//		 		$_SESSION['eMsg'][] = "The zipcode is not unique";
//			}
//		}

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

		$sql = "insert into app_zipcodes set $fields";
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
			$valData = trim(addslashes($valData));
			$flds[] = "$keyData='$valData'";
		}
		$fields = implode(", ",$flds);
		printa($fields);
		$sql = "update app_zipcodes set $fields where zipcode_id=$id";
		$this->conn->Execute($sql);
		$_SESSION['eMsg']="Successfully Updated.";
	}

	/**
	 * Delete Record
	 *
	 * @param string $id_
	 */
	function doDelete($id_ = ""){
		$sql = "delete from app_zipcodes where zipcode_id=?";
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
				$qry[] = "(province_name like '%$search_field%' || zipcode_name like '%$search_field%' || zipcode like '%$search_field%')";

			}
		}

		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		 "viewdata"=>"viewdata"
		,"province_name" => "province_name"
		,"zipcode_name" => "zipcode_name"
		,"zipcode" => "zipcode"
		);

		if(isset($_GET['sortby'])){
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}

		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$viewLink = "";
		$editLink = "<a href=\"?statpos=zipcode&edit=',am.zipcode_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/edit.png\" title=\"Edit\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
		$delLink = "<a href=\"?statpos=zipcode&delete=',am.zipcode_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\"  border=0 width=\"16\" height=\"16\"></a>";

		// SqlAll Query
		$sql = "select am.*,app_province.*, CONCAT('$viewLink','$editLink','$delLink') as viewdata
						from app_zipcodes am
					    INNER JOIN app_province ON (am.p_id = app_province.p_id)
						$criteria
						$strOrderBy";

		// Sql query for paginator list
		$sqlcount = "select count(*) as mycount from app_zipcodes $criteria";

		// Field and Table Header Mapping
		$arrFields = array(
	 	 "viewdata"=>"<a href=\"?statpos=zipcode&action=add\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/add.png\" title=\"Add New\" border=0 width=\"16\" height=\"16\"></a>"
		,"province_name" => "Province Name"
		,"zipcode_name" => "Zip Code Name"
		,"zipcode" => "Zip Code"
		);

		// Column (table data) User Defined Attributes
		$arrAttribs = array(
		"mnu_ord"=>" align='right'",
		"viewdata"=>"width='40' align='center'"
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