<?php
/**
 * Initial Declaration
 */


/**
 * Class Module
 *
 * @author  JIM
 *
 */
class clsWageRate {

	var $conn;
	var $fieldMap;
	var $Data;

	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsWageRate object
	 */
	function clsWageRate ($dbconn_ = null) {
		$this->conn =& $dbconn_;
		$this->fieldMap = array(
		 "region_id" => "region_id"
		,"wrate_name" => "wrate_name"
		,"wrate_sec_ind" => "wrate_sec_ind"
		,"wrate_minwagerate" => "wrate_minwagerate"
		);
	}

	/**
	 * Get the records from the database
	 *
	 * @param string $id_
	 * @return array
	 */
	function dbFetch ($id_ = "") {
		$objClsMngeDecimal = new Application();
		$sql = "SELECT a.*,b.region_name, FORMAT(a.wrate_minwagerate,".$objClsMngeDecimal->getGeneralDecimalSettings().") as wrate_minwagerate FROM app_wagerate a JOIN app_region b on(b.region_id=a.region_id) WHERE wrate_id=?";
		$rsResult = $this->conn->Execute($sql,array($id_));
		if (!$rsResult->EOF) {
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
	function doPopulateData ($pData_ = array(),$isForm_ = false) {
		if (count($pData_)>0) {
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
		
		if (empty($pData_['wrate_name'])) {
			$isValid = false;
			$_SESSION['eMsg'][] = "Please enter a Name.";
		}
		
		if (empty($pData_['wrate_sec_ind'])) {
			$isValid = false;
			$_SESSION['eMsg'][] = "Please enter Sector/Industry.";
		}
		
		if (empty($pData_['region_id'])) {
			$isValid = false;
			$_SESSION['eMsg'][] = "Please select Region.";
		}
		
		if (empty($pData_['wrate_minwagerate'])) {
			$isValid = false;
			$_SESSION['eMsg'][] = "Please enter Basic Wage After COLA Integration.";
		} else {
			if ( !is_numeric($pData_['wrate_minwagerate']) ) {
				$isValid = false;
				$_SESSION['eMsg'][] = "Please enter a valid Basic Wage After COLA Integration.";
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
		foreach ($this->Data as $keyData => $valData) {
			$valData = addslashes($valData);
			$flds[] = "$keyData='$valData'";
		}
		$flds[]="wrate_addwho='".AppUser::getData('user_name')."'";
		$fields = implode(", ",$flds);

		$sql = "INSERT INTO app_wagerate SET $fields";
		$this->conn->Execute($sql);

		$_SESSION['eMsg']="Successfully Added.";
	}

	/**
	 * Save Update
	 *
	 */
	function doSaveEdit () {
		$id = $_GET['edit'];

		$flds = array();
		foreach ($this->Data as $keyData => $valData) {
			$valData = addslashes($valData);
			$flds[] = "$keyData='$valData'";
		}
		$flds[]="wrate_updatewho='".AppUser::getData('user_name')."'";
		$flds[]="wrate_updatewhen='".date('Y-m-d H:i:s')."'";
		$fields = implode(", ",$flds);
		$sql = "UPDATE app_wagerate SET $fields WHERE wrate_id=$id";
		$this->conn->Execute($sql);
		$_SESSION['eMsg']="Successfully Updated.";
	}

	/**
	 * Delete Record
	 *
	 * @param string $id_
	 */
	function doDelete ($id_ = "") {
		$sql = "DELETE FROM app_wagerate WHERE wrate_id=?";
		$this->conn->Execute($sql,array($id_));
		$_SESSION['eMsg']="Successfully Deleted.";
	}

	/**
	 * Get all the Table Listings
	 *
	 * @return array
	 */
	function getTableList () {
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
				$qry[] = "wrate_name LIKE '%$search_field%'";

			}
		}

		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" WHERE ".implode(" AND ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		 "viewdata"=>"viewdata"
		,"wrate_name"=>"wrate_name"
		,"wrate_sec_ind"=>"wrate_sec_ind"
		,"region_name"=>"region_name"
		,"wrate_minwagerate"=>"wrate_minwagerate"
		);

		if (isset($_GET['sortby'])) {
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}

		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$viewLink = "";
		$editLink = "<a href=\"?statpos=wagerate&edit=',am.wrate_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/edit.png\" title=\"Edit\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
		$delLink = "<a href=\"?statpos=wagerate&delete=',am.wrate_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\"  border=0 width=\"16\" height=\"16\"></a>";
		$action = "<a href=\"?statpos=wagerate&action=add\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/add.png\" title=\"Add New\" border=0 width=\"16\" height=\"16\"></a>";
		$objClsMngeDecimal = new Application();
		// SqlAll Query
		$sql = "select am.wrate_id,am.region_id,am.wrate_name,am.wrate_sec_ind,
				FORMAT(am.wrate_minwagerate,".$objClsMngeDecimal->getGeneralDecimalSettings().") as wrate_minwagerate,
				b.region_name,b.region_desc, CONCAT('$viewLink','$editLink','$delLink') as viewdata
						from app_wagerate am
						JOIN app_region b on (b.region_id=am.region_id)
						$criteria
						$strOrderBy";

		// Field and Table Header Mapping
		$arrFields = array(
		 "viewdata"=>"Action"
		,"wrate_name"=>"Name"
		,"wrate_sec_ind"=>"Sector/Industry"
		,"region_name"=>"Region"
		,"wrate_minwagerate"=>"Basic Wage After COLA Integration"
		);

		// Column (table data) User Defined Attributes
		$arrAttribs = array(
		"mnu_ord" => "align='center'",
		"viewdata" => "width='50' align='center'"
		);

		// Process the Table List
		$tblDisplayList = new clsTableList($this->conn);
		$tblDisplayList->arrFields = $arrFields;
		$tblDisplayList->paginator->linkPage = "?$queryStr";
		$tblDisplayList->sqlAll = $sql;
		$tblDisplayList->sqlCount = $sqlcount;

		return $tblDisplayList->getTableList($arrAttribs);
	}
	
	function GetRegion () {//drop down bank list
		$sql = "SELECT * FROM app_region order by region_name";
		$rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF){
			$app_region[] = $rsResult->fields;
			$rsResult->MoveNext();
		}
		return $app_region;
	}
}
?>