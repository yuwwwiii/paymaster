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
class clsState_province{

	var $conn;
	var $fieldMap;
	var $Data;

	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsState_province object
	 */
	function clsState_province($dbconn_ = null){
		$this->conn =& $dbconn_;
		$this->fieldMap = array(
		  "r_id" => "r_id"
		 ,"province_id" => "r_id"
		 ,"province_name" => "province_name"
		);
	}

	/**
	 * Get the records from the database
	 *
	 * @param string $id_
	 * @return array
	 */
	function dbFetch($id_ = ""){
		$sql = "select a.*, b.region_name, b.region_desc 
				from app_province a
				inner join app_region b on (b.r_id=a.r_id) 
				where p_id=?";
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
		
		if(empty($pData_['region_name'])){
		 	$isValid = false;
		 	$_SESSION['eMsg'][] = "Please select a Region Name.";
		}
		
		if(empty($pData_['province_name'])){
		 	$isValid = false;
		 	$_SESSION['eMsg'][] = "Please enter State / Province.";
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
		$fields = implode(", ",$flds);

		$sql = "insert into app_province set $fields";
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

		$sql = "update app_province set $fields where p_id=$id";
		$this->conn->Execute($sql);
		$_SESSION['eMsg']="Successfully Updated.";
	}

	/**
	 * Delete Record
	 *
	 * @param string $id_
	 */
	function doDelete($id_ = ""){
		$sql = "delete from app_province where p_id=?";
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
				$qry[] = "(a.province_name like '%$search_field%' || region_name like '%$search_field%' || region_desc like '%$search_field%')";
			}
		}

		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		 "viewdata"=>"viewdata"
		,"province_name"=>"province_name"
		,"region_name"=>"region_name"
		,"region_desc"=>"region_desc"
		);

		if(isset($_GET['sortby'])){
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}else{
			$strOrderBy = " order by b.region_ord,a.province_name";
		}

		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$viewLink = "";
		if($_GET['statpos']=='state_province'){
			$editLink = "<a href=\"?statpos=state_province&edit=',a.p_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/edit.png\" title=\"Edit\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
			$delLink = "<a href=\"?statpos=state_province&delete=',a.p_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";	
		}else{
			$popupLink = "<a href=\"javascript:void(0);\" onclick=\"opener.document.getElementById(\'province_id\').value=\'',am.province_id,'\';
						window.parent.document.getElementById(\'province_name\').value=\'',am.province_name,'\';
						parent.$.fancybox.close()
						\"><img src=\"".SYSCONFIG_THEME_URLPATH.SYSCONFIG_THEME."/images/admin/accept.gif\" title=\"Select\" hspace=\"2px\" border=0></a>";
		}
		
		
		
		
		
		// SqlAll Query
		$sql = "select a.*, CONCAT('$viewLink','$editLink','$delLink') as viewdata, b.region_name, b.region_desc
						from app_province a
						inner join app_region b on (b.r_id=a.r_id) 
						$criteria
						$strOrderBy";

		// Sql query for paginator list
		$sqlcount = "select count(*) as mycount from app_province $criteria";

		// Field and Table Header Mapping
		$arrFields = array(
		 "viewdata"=>"<a href=\"?statpos=state_province&action=add\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/add.png\" title=\"Add New\" border=0 width=\"16\" height=\"16\"></a>"
		,"province_name"=>"State / Province"
		,"region_name"=>"Region"
		,"region_desc"=>"Region Name"
		);

		// Column (table data) User Defined Attributes
		$arrAttribs = array(
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
	
	function getPopup_city(){
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
				$qry[] = "(province_name like '%$search_field%' || region_name like '%$search_field%')";

			}
		}

		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		 "viewdata"=>"Action"
		,"province_name"=>"province_name"
		,"region_name"=>"region_name"
		);

		if(isset($_GET['sortby'])){
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}else{
			$strOrderBy = " order by b.region_ord,a.province_name";
		}
		
		if(isset($_GET['popupzipcode'])=='zipcode'){
		$viewLink = "<a href=\"javascript:void(0);\" onclick=\"
								window.parent.document.getElementById(\'province_name\').value=\'',a.province_name,'\';
								window.parent.document.getElementById(\'p_id\').value=\'',a.p_id,'\';
								parent.$.fancybox.close();
								\"><img src=\"".SYSCONFIG_THEME_URLPATH.SYSCONFIG_THEME."/images/admin/accept.gif\" title=\"Select\" hspace=\"2px\" border=0></a>";	
		}else{
		
		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$viewLink = "<a href=\"javascript:void(0);\" onclick=\"
								window.parent.document.getElementById(\'province_name\').value=\'',a.province_name,'\';
								window.parent.document.getElementById(\'p_id\').value=\'',a.p_id,'\';
								window.parent.document.getElementById(\'region_name\').value=\'',b.region_name,'\';
								window.parent.document.getElementById(\'cou_description\').value=\'',c.cou_description,'\';
								window.parent.document.getElementById(\'zipcode\').value=\'\';
								parent.$.fancybox.close();
								\"><img src=\"".SYSCONFIG_THEME_URLPATH.SYSCONFIG_THEME."/images/admin/accept.gif\" title=\"Select\" hspace=\"2px\" border=0></a>";
		}
		$editLink = "<a href=\"?statpos=state_province&edit=',a.p_id,'\"><img src=\"".SYSCONFIG_THEME_URLPATH.SYSCONFIG_THEME."/images/admin/edit.gif\" title=\"Edit\" hspace=\"2px\" border=0></a>";
		$delLink = "<a href=\"?statpos=state_province&delete=',a.p_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_THEME_URLPATH.SYSCONFIG_THEME."/images/admin/trash.gif\" title=\"Delete\" hspace=\"2px\"  border=0></a>";

		// SqlAll Query
		$sql = "select a.*, b.region_name, c.cou_description, CONCAT('$viewLink') as viewdata
						from app_province a
						inner join app_region b on (b.r_id=a.r_id)
						inner join app_country c on (c.cou_id = b.cou_id)
						$criteria
						$strOrderBy";

		// Sql query for paginator list
		$sqlcount = "select count(*) as mycount from app_province $criteria";

		// Field and Table Header Mapping
		$arrFields = array(
		 "viewdata"=>"Action"
		,"province_name"=>"State / Province"
		,"region_name"=>"Region Name"
		);

		// Column (table data) User Defined Attributes
		$arrAttribs = array(
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

	/**
	 * get zipcode base in city/province
	 * 
	 */
	function getPopup_zipcode($city_id_ = ""){
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
				$qry[] = "(zipcode like '%$search_field%' || zipcode_name like '%$search_field%')";

			}
		}
		
		$qry[]="zipc.p_id='".$city_id_."'";
		
		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		 "viewdata"=>"Action"
		,"zipcode"=>"zipcode"
		,"zipcode_name"=>"zipcode_name"
		,"province_name"=>"province_name"
		);

		if(isset($_GET['sortby'])){
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}else{
			$strOrderBy = " order by zipc.zipcode,a.province_name";
		}

		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$viewLink = "<a href=\"javascript:void(0);\" onclick=\"
								window.parent.document.getElementById(\'zipcode\').value=\'',zipc.zipcode,'\';
								window.parent.document.getElementById(\'zipcode_id\').value=\'',zipc.zipcode_id,'\';
								parent.$.fancybox.close();
								\"><img src=\"".SYSCONFIG_THEME_URLPATH.SYSCONFIG_THEME."/images/admin/accept.gif\" title=\"Select\" hspace=\"2px\" border=0></a>";
		
		$editLink = "<a href=\"?statpos=state_province&edit=',zipc.zipcode_id,'\"><img src=\"".SYSCONFIG_THEME_URLPATH.SYSCONFIG_THEME."/images/admin/edit.gif\" title=\"Edit\" hspace=\"2px\" border=0></a>";
		$delLink = "<a href=\"?statpos=state_province&delete=',zipc.zipcode_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_THEME_URLPATH.SYSCONFIG_THEME."/images/admin/trash.gif\" title=\"Delete\" hspace=\"2px\"  border=0></a>";

		// SqlAll Query
		$sql = "select zipc.*, a.province_name, CONCAT('$viewLink') as viewdata
						from app_zipcodes zipc 
						inner join app_province a on (a.p_id = zipc.p_id)
						$criteria
						$strOrderBy";

		// Field and Table Header Mapping
		$arrFields = array(
		 "viewdata"=>"Action"
		,"zipcode"=>"Zipcode"
		,"zipcode_name"=>"Zipcode Name"
		,"province_name"=>"State / Province"
		);

		// Column (table data) User Defined Attributes
		$arrAttribs = array(
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