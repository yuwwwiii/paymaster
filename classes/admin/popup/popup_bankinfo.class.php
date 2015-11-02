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
class clsPopup_BankInfo {

	var $conn;
	var $fieldMap;
	var $Data;

	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsPopup_BankInfo object
	 */
	function clsPopup_BankInfo ($dbconn_ = null) {
		$this->conn =& $dbconn_;
		$this->fieldMap = array(
		 "mnu_name" => "mnu_name"
		,"mnu_desc" => "mnu_desc"
		,"mnu_parent" => "mnu_parent"
		,"mnu_icon" => "mnu_icon"
		,"mnu_ord" => "mnu_ord"
		,"mnu_status" => "mnu_status"
		,"mnu_link_info" => "mnu_link_info"
		);
	}

	/**
	 * Get the records from the database
	 *
	 * @param string $id_
	 * @return array
	 */
	function dbFetch ($id_ = "") {
		$sql = "";
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
	function doValidateData ($pData_ = array()) {
		$isValid = true;

//		$isValid = false;

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
		$fields = implode(", ",$flds);

		$sql = "insert into /*app_modules*/ set $fields";
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
		$fields = implode(", ",$flds);

		$sql = "update /*app_modules*/ set $fields where mnu_id=$id";
		$this->conn->Execute($sql);
		$_SESSION['eMsg']="Successfully Updated.";
	}

	/**
	 * Delete Record
	 *
	 * @param string $id_
	 */
	function doDelete ($id_ = "") {
		$sql = "delete from /*app_modules*/ where mnu_id=?";
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
				$qry[] = "(banklist_name like '%$search_field%' || bank_acct_no like '%$search_field%' || bank_acct_name like '%$search_field%' || bank_branch like '%$search_field%')";
				
			}
		}

		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		 "viewdata"=>"viewdata"
		,"banklist_name" => "banklist_name"
		,"bank_acct_no" => "bank_acct_no"
		,"bank_acct_name" => "bank_acct_name"
		,"bank_branch" => "bank_branch"
		,"bank_isactive" => "bank_isactive"
		);

		if(isset($_GET['sortby'])){
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}

		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
//		$viewLink = "";
//		$editLink = "<a href=\"?statpos=popup_bankinfo&edit=',am.bank_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/edit.png\" title=\"Edit\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
//		$delLink = "<a href=\"?statpos=popup_bankinfo&delete=',am.bank_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\"  border=0 width=\"16\" height=\"16\"></a>";
//		$action = "<a href=\"?statpos=popup_bankinfo&action=add\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/add.png\" title=\"Add New\" border=0 width=\"16\" height=\"16\"></a>";
		$popupLink = "<a href=\"javascript:void(0);\" onclick=\"window.parent.document.getElementById(\'bank_id\').value=\'',am.bank_id,'\';
						window.parent.document.getElementById(\'banklist_id\').value=\'',am.banklist_id,'\';
						window.parent.document.getElementById(\'banklist_name\').value=\'',bank.banklist_name,'\';
						window.parent.document.getElementById(\'bank_acct_name\').value=\'',am.bank_acct_name,'\';
						window.parent.document.getElementById(\'bank_acct_no\').value=\'',am.bank_acct_no,'\';
						window.parent.document.getElementById(\'bank_routing_number\').value=\'',IF(am.bank_routing_number IS NULL,\"\", am.bank_routing_number),'\';
						window.parent.document.getElementById(\'bank_company_code\').value=\'',IF(am.bank_company_code IS NULL,\"\",am.bank_company_code),'\';
						window.parent.document.getElementById(\'bank_ceiling_amount\').value=\'',IF(am.bank_ceiling_amount IS NULL,\"\",am.bank_ceiling_amount),'\';
						parent.$.fancybox.close();\"><img src=\"".SYSCONFIG_THEME_URLPATH.SYSCONFIG_THEME."/images/admin/accept.gif\" title=\"Select\" hspace=\"2px\" border=\"0\" /></a>";

		// SqlAll Query
		$sql = "SELECT am.*,CONCAT('$popupLink') AS viewdata,
				bank.banklist_name, IF(am.bank_isactive = '1','Active','Inactive') AS bank_isactive
						FROM bank_info am
						JOIN bank_list bank ON (bank.banklist_id=am.banklist_id)
						$criteria
						$strOrderBy";

		// Field and Table Header Mapping
		$arrFields = array(
		 "viewdata" => "Action"
		,"banklist_name" => "Bank Name"
		,"bank_acct_no" => "Acct No"
		,"bank_acct_name" => "Acct Name"
		,"bank_branch" => "Branch"
		,"bank_isactive" => "Status"
		);

		// Column (table data) User Defined Attributes
		$arrAttribs = array(
		"mnu_ord"=>" align='center'",
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