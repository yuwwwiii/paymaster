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
class clsJobPost{

	var $conn;
	var $fieldMap;
	var $Data;

	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsJobPost object
	 */
	function clsJobPost($dbconn_ = null){
		$this->conn =& $dbconn_;
		$this->fieldMap = array(
		 "post_code" => "post_code"
		,"post_name" => "post_name"
		,"post_desc" => "post_desc"
		,"post_ord" => "post_ord"
		);
	}

	/**
	 * Get the records from the database
	 *
	 * @param string $id_
	 * @return array
	 */
	function dbFetch($id_ = ""){
		$sql = "select * from emp_position where post_id=?";
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
	
	function doValidateData($pData_ = array()){
		$isValid = true;
		
		if (empty($pData_['post_code'])) {
			$isValid = false;
			$_SESSION['eMsg'][] = "Please enter Job Code.";
		}
		
		if (empty($pData_['post_name'])) {
			$isValid = false;
			$_SESSION['eMsg'][] = "Please enter Job Position.";
		}
		
		if (!is_numeric($pData_['post_ord']) && !empty($pData_['post_ord'])) {
		 	$isValid = false;
		 	$_SESSION['eMsg'][] = "Please enter a valid Order number.";
		}
	
		return $isValid;
	}

	/**
	 * Save New
	 *
	 */
	function doSaveAdd() {
		$flds = array();
		foreach ($this->Data as $keyData => $valData) {
			$valData = trim(addslashes($valData));
			$flds[] = "$keyData='$valData'";
		}
		$flds[]="post_addwho='".AppUser::getData('user_name')."'";
		$fields = implode(", ",$flds);

		$sql = "insert into emp_position set $fields";
		$this->conn->Execute($sql);

		$_SESSION['eMsg']="Successfully Added.";
	}

	/**
	 * Save Update
	 *
	 */
	function doSaveEdit() {
		$id = $_GET['edit'];

		$flds = array();
		foreach ($this->Data as $keyData => $valData) {
			$valData = trim(addslashes($valData));
			$flds[] = "$keyData='$valData'";
		}
		$flds[]="post_updatewho='".AppUser::getData('user_name')."'";
		$flds[]="post_updatewhen='".date('Y-m-d H:i:s')."'";
		$fields = implode(", ",$flds);

		$sql = "update emp_position set $fields where post_id=$id";
		$this->conn->Execute($sql);
		$_SESSION['eMsg']="Successfully Updated.";
	}

	/**
	 * Delete Record
	 *
	 * @param string $id_
	 */
	function doDelete($id_ = ""){
		$sql = "delete from emp_position where post_id=?";
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
				$qry[] = "post_name like '%$search_field%' || post_code like '%$search_field%'";

			}
		}

		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		 "viewdata"=>"viewdata"
		,"post_code"=>"post_code"
		,"post_name"=>"post_name"
		,"post_desc"=>"post_desc"
		,"post_ord"=>"post_ord"
		);

		if(isset($_GET['sortby'])){
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}else{
			$strOrderBy = " order by am.post_ord";
		}

		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$viewLink = "";
		if ($_GET['statpos']=='jobpost') {
			$editLink = "<a href=\"?statpos=jobpost&edit=',am.post_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/edit.png\" title=\"Edit\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
			$delLink = "<a href=\"?statpos=jobpost&delete=',am.post_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\"  border=0 width=\"16\" height=\"16\"></a>";
		}else{
			$popupLink = "<a href=\"javascript:void(0);\" onclick=\"window.parent.document.getElementById(\'post_id\').value=\'',am.post_id,'\';
							window.parent.document.getElementById(\'post_name\').value=\'',am.post_name,'\';
							parent.$.fancybox.close();\"><img src=\"".SYSCONFIG_THEME_URLPATH.SYSCONFIG_THEME."/images/admin/accept.gif\" title=\"Select\" hspace=\"2px\" border=0></a>";
		}
		
		// SqlAll Query
		$sql = "select am.*, CONCAT('$viewLink','$editLink','$delLink','$popupLink') as viewdata
						from emp_position am
						$criteria
						$strOrderBy";

		// Field and Table Header Mapping
		$arrFields = array(
		 "viewdata"=>"Action"
		,"post_code"=>"Job Code" 
		,"post_name"=>"Job Position"
		,"post_desc"=>"Job Description"	
		,"post_ord"=>"Order"
		);

		// Column (table data) User Defined Attributes
		$arrAttribs = array(
		"post_name"=> "width='190'",
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