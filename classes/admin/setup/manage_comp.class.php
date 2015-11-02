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
class clsManage_Comp{

	var $conn;
	var $fieldMap;
	var $Data;

	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsManage_Comp object
	 */
	function clsManage_Comp($dbconn_ = null){
		$this->conn =& $dbconn_;
		$this->fieldMap = array(
		 "comp_name" => "comp_name"
		,"comp_add" => "comp_add"
		,"comp_tel" => "comp_tel"
		,"comp_email" => "comp_email"
		,"comp_prim_contc" => "comp_prim_contc"
		,"comp_tin" => "comp_tin"
		,"comp_sss" => "comp_sss"
		,"comp_phic" => "comp_phic"
		,"comp_hdmf" => "comp_hdmf"
		,"comp_code" => "comp_code"
		,"comp_priority" => "comp_priority"
		,"comp_zipcode" => "comp_zipcode"
		,"comptype_id" => "comptype_id"
		);
	}

	/**
	 * Get the records from the database
	 *
	 * @param string $id_
	 * @return array
	 */
	function dbFetch($id_ = ""){
		$sql = "Select * from company_info a inner join company_type b on (a.comptype_id=b.comptype_id) where comp_id=?";
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
	function doPopulateData ($pData_ = array(), $isForm_ = false) {
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

		if (empty($pData_['comp_code'])) {
			$isValid = false;
			$_SESSION['eMsg'][] = "Please enter Company Code.";
		}
		
		if (empty($pData_['comp_name'])) {
			$isValid = false;
			$_SESSION['eMsg'][] = "Please enter Company Name.";
		}
		
		if (empty($pData_['comp_add'])) {
			$isValid = false;
			$_SESSION['eMsg'][] = "Please enter Company Address.";
		}
		
		if (empty($pData_['comp_zipcode'])) {
			$isValid = false;
			$_SESSION['eMsg'][] = "Please enter Zip Code.";
		}
		
		if (!is_numeric($pData_['comp_zipcode']) && !empty($pData_['comp_zipcode'])) {
			$isValid = false;
			$_SESSION['eMsg'][] = "Please enter a valid Zip Code.";
		}
		
		if (empty($pData_['comp_tel'])) {
			$isValid = false;
			$_SESSION['eMsg'][] = "Please enter Telephone Number.";
		}
		
		if(!filter_var($pData_['comp_email'], FILTER_VALIDATE_EMAIL) && !empty($pData_['comp_email'])){
			$isValid = false;
			$_SESSION['eMsg'][] = "Please enter a valid Email Address.";
		}
		
		if (empty($pData_['comp_tin'])) {
			$isValid = false;
			$_SESSION['eMsg'][] = "Please enter TIN number.";
		}
		
		if (empty($pData_['comp_sss'])) {
			$isValid = false;
			$_SESSION['eMsg'][] = "Please enter SSS number.";
		}
		
		if (empty($pData_['comp_phic'])) {
			$isValid = false;
			$_SESSION['eMsg'][] = "Please enter PHIC number.";
		}
		
		if (empty($pData_['comp_hdmf'])) {
			$isValid = false;
			$_SESSION['eMsg'][] = "Please enter HDMF number.";
		}
		
		if (!is_numeric($pData_['comp_priority']) && !empty($pData_['comp_priority'])) {
			$isValid = false;
			$_SESSION['eMsg'][] = "Please enter a valid Priority number.";
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
		$flds[]="comp_addwho='".AppUser::getData('user_name')."'";
		$fields = implode(", ",$flds);

		$sql = "insert into company_info set $fields";
		$this->conn->Execute($sql);
		
		//@notes: get last inserted ID 
		$comp_id = $this->conn->Insert_ID();
		
		//@note used add priority in the database.
		$ctr = 1;
		do {
			$flds_[]="comp_id='".$comp_id."'";
			$flds_[]="ppc_priority_no='".$ctr."'";
			$fields_ = implode(", ",$flds_);
	
			$sql_ = "insert into payroll_priority_comp set $fields_";
//			exit;
			$this->conn->Execute($sql_);
			
		 $flds_="";
		 $fields_ = "";
		 $ctr++;
		} while($ctr <= $_POST['comp_priority']);

		$_SESSION['eMsg']="Successfully Added.";
	}

	/**
	 * Save Update
	 *
	 */
	function doSaveEdit($comp_id_ ="") {
		$flds = array();
		foreach ($this->Data as $keyData => $valData) {
			$valData = trim(addslashes($valData));
			$flds[] = "$keyData='$valData'";
		}
		$flds[]="comp_updatewho='".AppUser::getData('user_name')."'";
		$flds[]="comp_updatewhen='".date('Y-m-d H:i:s')."'";
		$fields = implode(", ",$flds);

		$sql = "update company_info set $fields where comp_id=$comp_id_";
		$this->conn->Execute($sql);
		
		//@note used add priority in the database.
		$ctr = 1;
		do {
			$flds_[]="comp_id='".$comp_id_."'";
			$flds_[]="ppc_priority_no='".$ctr."'";
			$fields_ = implode(", ",$flds_);
	
			$sql_ = "insert into payroll_priority_comp set $fields_";
//			exit;
			$this->conn->Execute($sql_);
			
		 $flds_="";
		 $fields_ = "";
		 $ctr++;
		} while ($ctr <= $_POST['comp_priority']);
		
		$_SESSION['eMsg']="Successfully Updated.";
	}

	/**
	 * Delete Record
	 *
	 * @param string $id_
	 */
	function doDelete($id_ = "") {
		$sql = "delete from company_info where comp_id=?";
		$this->conn->Execute($sql,array($id_));
		$_SESSION['eMsg']="Successfully Deleted.";
	}

	/**
	 * Get all the Table Listings
	 * Original Campany INFO, iPAY Integrated.
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
				$qry[] = "(comp_name like '%$search_field%' || comp_add like '%$search_field%')";

			}
		}

		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		 "viewdata"=>"viewdata"
		,"comp_code" => "comp_code"
		,"comp_name" => "comp_name"
		,"comp_add" => "comp_add"
		,"comp_tel" => "comp_tel"
		);

		if (isset($_GET['sortby'])) {
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}

		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$viewLink = "";
		if ($_GET['statpos']=='manage_comp') {
			$editLink = "<a href=\"?statpos=manage_comp&edit=',am.comp_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/edit.png\" title=\"Edit\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
			$delLink = "<a href=\"?statpos=manage_comp&delete=',am.comp_id,'\" onclick=\"return confirm(\'ALL DATA CONNECTED TO IT WILL BE DELETED. Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\"  border=0 width=\"16\" height=\"16\"></a>";
		} elseif ($_GET['statpos']=='mnge_pg' || $_GET['detect']=='mnge_pg') {
			 $popupLink = "<a href=\"javascript:void(0);\" onclick=\"
							window.parent.document.getElementById(\'comp_id\').value=\'',am.comp_id,'\';
							window.parent.document.getElementById(\'comp_name\').value=\'',am.comp_name,'\';
							parent.$.fancybox.close();
							\"><img src=\"".SYSCONFIG_THEME_URLPATH.SYSCONFIG_THEME."/images/admin/accept.gif\" title=\"Select\" hspace=\"2px\" border=\"0\"></a>";
		} else {
			$popupLink = "<a href=\"javascript:void(0);\" onclick=\"
							window.parent.document.getElementById(\'comp_id\').value=\'',comp_id,'\';
							window.parent.document.getElementById(\'comp_name\').value=\'',comp_name,'\';
							window.parent.document.getElementById(\'comp_add\').value=\'',comp_add,'\';
							window.parent.document.getElementById(\'comp_tel\').value=\'',comp_tel,'\';
							window.parent.document.getElementById(\'comp_tin\').value=\'',comp_tin,'\';
							parent.$.fancybox.close();
							\"><img src=\"".SYSCONFIG_THEME_URLPATH.SYSCONFIG_THEME."/images/admin/accept.gif\" title=\"Select\" hspace=\"2px\" border=\"0\"></a>";
		}
		
		//print "var newOption = document.createElement('OPTION');";
		//print "document.getElementById('".$selectID."').appendChild(newOption);";
		//print "newOption.id = 'opt_".$id."';";
		//print "newOption.value = '".$id."';";
		//print "newOption.selected = 'selected';";
		//print "newOption.text = '".$text."';";
		
		// SqlAll Query
		$sql = "SELECT am.*, CONCAT('$viewLink','$editLink','$delLink','$popupLink') as viewdata
						from company_info am
						$criteria
						$strOrderBy";

		// Field and Table Header Mapping
		$arrFields = array(
		 "viewdata"=>($_GET['statpos']=='manage_comp') ? "<a href=\"?statpos=manage_comp&action=add\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/add.png\" title=\"Add New\" border=0 width=\"16\" height=\"16\"></a>" : "Action"
		,"comp_code"=>"Company Code"
		,"comp_name"=>"Company Name"
		,"comp_add"=>"Address"
		,"comp_tel"=>"Tel"
		);

		// Column (table data) User Defined Attributes
		$arrAttribs = array(
		"mnu_ord" => "align='right'",
		"viewdata" => "width='40' align='center'"
		);

		// Process the Table List
		$tblDisplayList = new clsTableList($this->conn);
		$tblDisplayList->arrFields = $arrFields;
		$tblDisplayList->paginator->linkPage = "?$queryStr";
		$tblDisplayList->sqlAll = $sql;
		$tblDisplayList->sqlCount = $sqlcount;

		return $tblDisplayList->getTableList($arrAttribs);
	}

	function comptype() {
		$sql = "select comptype_desc,comptype_id from company_type";
		$rsResult = $this->conn->Execute($sql);
		
		while (!$rsResult->EOF) {
			$compvalue[] = $rsResult->fields['comptype_id'];
			$comp[] = $rsResult->fields['comptype_desc'];
			$rsResult->MoveNext();
		}
		$_SESSION['compvalue'] = $compvalue;
		return $comp;
	}
}
?>