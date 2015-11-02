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
class clsMnge_Leave{

	var $conn;
	var $fieldMap;
	var $Data;

	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsMnge_Leave object
	 */
	function clsMnge_Leave($dbconn_ = null){
		$this->conn =& $dbconn_;
		$this->conn->debug=false;
		$this->fieldMap = array(
		 "leave_name" => "leave_name"
		,"leave_wpay" => "leave_wpay"
		,"leave_gender" => "leave_gender"
		,"leave_days" => "leave_days"
		,"leave_conv_cash" => "leave_conv_cash"
		,"leave_code" => "leave_code"
		);
	}

	/**
	 * Get the records from the database
	 *
	 * @param string $id_
	 * @return array
	 */
	function dbFetch($id_ = ""){
		$sql = "Select * From leave_type where leave_id=?";
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

		if (empty($pData_['leave_name'])) {
			$isValid = false;
			$_SESSION['eMsg'][] = "Please enter type of leave.";
		}
		
		if (empty($pData_['leave_days'])) {
			$isValid = false;
			$_SESSION['eMsg'][] = "Please enter leave day(s) per month.";
		}
		
		if (!is_numeric($pData_['leave_days']) && !empty($pData_['leave_days'])) {
		 	$isValid = false;
		 	$_SESSION['eMsg'][] = "Please enter a valid Day(s) of Leave.";
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
		$flds[]="leave_addwho='".AppUser::getData('user_name')."'";
		$fields = implode(", ",$flds);

		$sql = "insert into leave_type set $fields";
		
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
		$flds[]="leave_updatewho='".AppUser::getData('user_name')."'";
		$flds[]="leave_updatewhen='".date('Y-m-d H:i:s')."'";
		$fields = implode(", ",$flds);

		$sql = "update leave_type set $fields where leave_id=$id";
		$this->conn->Execute($sql);
		$_SESSION['eMsg']="Successfully Updated.";
	}

	/**
	 * Delete Record
	 *
	 * @param string $id_
	 */
	function doDelete($id_ = ""){
		$sql = "delete from leave_type where leave_id=?";
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
				$qry[] = "leave_name like '%$search_field%'";

			}
		}

		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		 "viewdata"=>"viewdata"
		,"leave_code" => "leave_code"
		,"leave_name" => "leave_name"
		,"leave_days" => "leave_days"
		,"leave_wpay" => "leave_wpay"
		,"leave_conv_cash" => "leave_conv_cash"
		,"leave_gender" => "leave_gender"
		);

		if(isset($_GET['sortby'])){
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}

		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$viewLink = "";
		$editLink = "<a href=\"?statpos=mnge_leave&edit=',am.leave_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/edit.png\" title=\"Edit\" border=0 width=\"16\" height=\"16\"></a>";
		$delLink = "<a href=\"?statpos=mnge_leave&delete=',am.leave_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" border=0 width=\"16\" height=\"16\"></a>";

		// SqlAll Query
		$sql = "select am.*, CONCAT('$viewLink','$editLink','$delLink') as viewdata
						from leave_type am
						$criteria
						$strOrderBy";

		// Sql query for paginator list
		$sqlcount = "select count(*) as mycount from leave_type am $criteria";

		// Field and Table Header Mapping
		$arrFields = array(
		 "viewdata"=>"Action"
		,"leave_code" => "Code"
		,"leave_name" => "Leave Name"
		,"leave_days" => "Day(s) of Leave"
		,"leave_wpay" => "With Pay"
		,"leave_conv_cash" => "Convertible to Cash"
		,"leave_gender" => "Gender"
		);

		// Column (table data) User Defined Attributes
		$arrAttribs = array(
		"leave_days"=>" align='center'",
		"leave_wpay"=>" align='center'",
		"leave_conv_cash"=>" align='center'",
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