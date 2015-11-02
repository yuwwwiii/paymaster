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
class clsEMPType{

	var $conn;
	var $fieldMap;
	var $Data;

	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsEMPType object
	 */
	function clsEMPType($dbconn_ = null) {
		$this->conn =& $dbconn_;
		$this->fieldMap = array(
		 "emptype_name" => "emptype_name"
		,"emptype_ord" => "emptype_ord"
		,"empclass_id" => "empclass_id"
		,"emptype_rank" => "emptype_rank"
		);
	}

	/**
	 * Get the records from the database
	 *
	 * @param string $id_
	 * @return array
	 */
	function dbFetch($id_ = "") {
		$sql = "Select * from emp_type where emptype_id=?";
		$rsResult = $this->conn->Execute($sql, array($id_));
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
	function doPopulateData($pData_ = array(), $isForm_ = false) {
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
	
		if (empty($pData_['emptype_name'])) {
			$isValid = false;
			$_SESSION['eMsg'][] = "Please enter Employee Type.";
		}
		
		if (!is_numeric($pData_['emptype_ord']) && !empty($pData_['emptype_ord'])) {
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
		$flds[]="emptype_addwho='".AppUser::getData('user_name')."'";
		$fields = implode(", ",$flds);

		$sql = "insert into emp_type set $fields";
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
		$flds[]="emptype_updatewho='".AppUser::getData('user_name')."'";
		$flds[]="emptype_updatewhen='".date('Y-m-d H:i:s')."'";
		$fields = implode(", ",$flds);

		$sql = "update emp_type set $fields where emptype_id=$id";
		$this->conn->Execute($sql);
		$_SESSION['eMsg']="Successfully Updated.";
	}

	/**
	 * Delete Record
	 *
	 * @param string $id_
	 */
	function doDelete($id_ = "") {
		$sql = "delete from emp_type where emptype_id=?";
		$this->conn->Execute($sql,array($id_));
		$_SESSION['eMsg']="Successfully Deleted.";
	}

	/**
	 * Get all the Table Listings
	 *
	 * @return array
	 */
	function getTableList() {
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
				$qry[] = "emptype_name like '%$search_field%'";

			}
		}

		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		 "viewdata"=>"viewdata"
		,"emptype_name" => "emptype_name"
		,"emptype_rank" => "emptype_rank"
		,"empclass_name" => "empclass_name"
		,"emptype_ord" => "emptype_ord"
		);

		if(isset($_GET['sortby'])){
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}else{
			$strOrderBy = " order by emptype_ord";
		}

		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$viewLink = "";
		if ($_GET['statpos']=='emptype') {
			$editLink = "<a href=\"?statpos=emptype&edit=',am.emptype_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/edit.png\" title=\"Edit\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
			$delLink = "<a href=\"?statpos=emptype&delete=',am.emptype_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\"  border=0 width=\"16\" height=\"16\"></a>";
		} else {
			$popupLink = "<a href=\"javascript:void(0);\" onclick=\"opener.document.getElementById(\'emptype_id\').value=\'',am.emptype_id,'\';
							opener.document.getElementById(\'emptype_name\').value=\'',am.emptype_name,'\';
							window.close();\"><img src=\"".SYSCONFIG_THEME_URLPATH.SYSCONFIG_THEME."/images/admin/accept.gif\" title=\"Select\" hspace=\"2px\" border=0></a>";
		}
		
		// SqlAll Query
		$sql = "select am.*,b.empclass_name,CONCAT('$viewLink','$editLink','$delLink','$popupLink') as viewdata
						from emp_type am
						LEFT JOIN emp_classification b on (b.empclass_id=am.empclass_id)  
						$criteria
						$strOrderBy";

		// Field and Table Header Mapping
		$arrFields = array(
		 "viewdata"=>"Action"
		,"emptype_name"=>"Employee Type"
		,"emptype_rank" => "Rank"
		,"empclass_name" => "Classification"
		,"emptype_ord"=>"Order"
		);

		// Column (table data) User Defined Attributes
		$arrAttribs = array(
		"emptype_ord"=>" align='right'",
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
	 * @note: This is used to get the dropdown menu in Classification.
	 * @param unknown_type $empclass_id_
	 */
	function dbfetchClassification($empclass_id_ = null){
		if($empclass_id_!=null || $empclass_id_ != ''){
		$qry[] = "empclass_id = '".$empclass_id_."'";
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";
		}
		$sql = "select empclass_name, empclass_id from emp_classification $criteria";
		$rsResult = $this->conn->Execute($sql);
		$cResult = array();
		while ( !$rsResult->EOF ) {
			$cResult[$rsResult->fields['empclass_id']] = $rsResult->fields['empclass_name'];
        	$rsResult->MoveNext();
		}	 
			return $cResult;
    }

}

?>