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
class clsRegion{

	var $conn;
	var $fieldMap;
	var $Data;

	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsRegion object
	 */
	function clsRegion($dbconn_ = null){
		$this->conn =& $dbconn_;
		$this->fieldMap = array(
		  "region_code" => "region_code"
		 ,"region_name" => "region_name"
		 ,"region_desc" => "region_desc"
		 ,"region_ord" => "region_ord"
		 ,"cou_id" => "cou_id"
		);
	}

	/**
	 * Get the records from the database
	 *
	 * @param string $id_
	 * @return array
	 */
	function dbFetch($id_ = ""){
		$sql = "select a.*, b.cou_description as cou_description, b.cou_id as cou_id  
					from app_region a
					inner join app_country b on (a.cou_id=b.cou_id)
					where a.r_id=?";
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
	function doPopulateData($pData_ = array(),$isForm_ = false) {
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
	function doValidateData($pData_ = array()){
		$isValid = true;
		
		if (empty($pData_['cou_description'])) {
		 	$isValid = false;
		 	$_SESSION['eMsg'][] = "Please select Country.";
		}
		
		if (empty($pData_['region_code'])) {
		 	$isValid = false;
		 	$_SESSION['eMsg'][] = "Please enter Region Code.";
		}
		
		if (empty($pData_['region_name'])) {
		 	$isValid = false;
		 	$_SESSION['eMsg'][] = "Please enter Region Name.";
		}
		
		if (empty($pData_['region_desc'])) {
		 	$isValid = false;
		 	$_SESSION['eMsg'][] = "Please enter Region Description.";
		}
		
		if (!is_numeric($pData_['region_ord']) && !empty($pData_['region_ord'])) {
		 	$isValid = false;
		 	$_SESSION['eMsg'][] = "Please enter a valid Order number.";
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
		$flds[]="region_addwho='".AppUser::getData('user_name')."'";
		$fields = implode(", ",$flds);

		$sql = "insert into app_region set $fields";
		$this->conn->Execute($sql);
		
		$reg_id = $this->conn->Insert_ID;
		
		$sql_= "Update app_region set region_id = '$reg_id' where r_id = '".$reg_id."'";
		$this->conn->Execute($sql_);
		
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
		$flds[]="region_updatewho='".AppUser::getData('user_name')."'";
		$flds[]="region_updatewhen='".date('Y-m-d H:i:s')."'";
		$fields = implode(", ",$flds);

		$sql = "update app_region set $fields where r_id=$id";
		$this->conn->Execute($sql);
		$_SESSION['eMsg']="Successfully Updated.";
	}

	/**
	 * Delete Record
	 *
	 * @param string $id_
	 */
	function doDelete($id_ = ""){
		$sql = "delete from app_region where r_id=?";
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
				$qry[] = "(region_name like '%$search_field%' || region_desc like '%$search_field%' || region_ord like '%$search_field%')";

			}
		}

		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		  "viewdata"=>"viewdata"
		 ,"region_code" => "region_code"
		 ,"region_name" => "region_name"
		 ,"region_desc" => "region_desc"
		 ,"region_ord" => "region_ord"
		);

		if(isset($_GET['sortby'])){
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}else{
			$strOrderBy = " order by region_ord";
		}

		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		if(isset($_GET['ftype'])){
			$viewLink = "<a href=\"javascript:void(0);\" onclick=\"
							window.parent.document.getElementById(\'region_name\').value=\'',app_region.region_name,'\';
							window.parent.document.getElementById(\'r_id\').value=\'',app_region.r_id,'\';
							parent.$.fancybox.close();\"><img src=\"".SYSCONFIG_THEME_URLPATH.SYSCONFIG_THEME."/images/admin/accept.gif\" title=\"Select\" hspace=\"2px\" border=0></a>";
		}else{
			$editLink = "<a href=\"?statpos=region&edit=',app_region.r_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/edit.png\" title=\"Edit\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
			$delLink = "<a href=\"?statpos=region&delete=',app_region.r_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\"  border=0 width=\"16\" height=\"16\"></a>";
		}
		// SqlAll Query
		$sql = "select app_region.*, CONCAT('$viewLink','$editLink','$delLink') as viewdata
						from app_region
						$criteria
						$strOrderBy";

		// Sql query for paginator list
		$sqlcount = "select count(*) as mycount from app_region $criteria";

		// Field and Table Header Mapping
		$arrFields = array(
		 "viewdata"=>(isset($_GET['ftype'])) ? "Action" : "<a href=\"?statpos=region&action=add\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/add.png\" title=\"Add New\" border=0 width=\"16\" height=\"16\"></a>"
		,"region_code" => "Code"
		,"region_name" => "Region Name"
		,"region_desc" => "Region Description"
		,"region_ord" => "Order"
		);

		// Column (table data) User Defined Attributes
		$arrAttribs = array(
		"viewdata"=>"width='40' align='center'",
		"region_ord"=>"width='100'",
		"region_code"=>"width='60'"
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