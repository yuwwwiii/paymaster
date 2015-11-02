<?php
/**
 * Initial Declaration
 */
$arrStatus = array(
1 => "Active",
2 => "Inactive"
);

/**
 * Class Module
 *
 * @author  Arnold P. Orbista
 *
 */
class clsUserType{

	var $conn;
	var $fieldMap;
	var $Data;

	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsUserType object
	 */
	function clsUserType($dbconn_ = null){
		$this->conn =& $dbconn_;
		$this->fieldMap = array(
		"user_type" => "user_type",
		"user_type_name" => "user_type_name",
		"user_type_ord" => "user_type_ord",
		"user_type_status" => "user_type_status"
		,"user_type_access" => "user_type_access"
		,"user_type_dept" => "user_type_dept"
		);
	}

	/**
	 * Get the records from the database
	 *
	 * @param string $id_
	 * @return array
	 */
	function dbFetch($id_ = null) {
		$sql = "select * from app_usertype where user_type=?";
		$rsResult = $this->conn->Execute($sql,array($id_));
		if (!$rsResult->EOF) {
			if (empty($rsResult->fields['user_type_access'])) {
				$rsResult->fields['user_type_access'] = array();
			} else {
				$rsResult->fields['user_type_access'] = unserialize($rsResult->fields['user_type_access']);
			}
			if (empty($rsResult->fields['user_type_dept'])) {
				$rsResult->fields['user_type_dept']=array();
			} else {
				$rsResult->fields['user_type_dept'] = unserialize($rsResult->fields['user_type_dept']);
			}
			return $rsResult->fields;
		}
	}
	/**
	 * Populate array parameters to Data Variable
	 *
	 * @param array $pData_
	 * @return bool
	 */
	function doPopulateData($pData_ = array()) {
		if (count($pData_)>0) {
			foreach ($this->fieldMap as $key => $value) {
				$this->Data[$key] = $pData_[$value];
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

		if (empty($pData_['user_type'])) {
		 	$isValid = false;
		 	$_SESSION['eMsg'][] = "Please enter a User Type.";
		}
		
		if (empty($pData_['user_type_name'])) {
		 	$isValid = false;
		 	$_SESSION['eMsg'][] = "Please enter a Name.";
		}
		
		if (!is_numeric($pData_['user_type_ord']) && !empty($pData_['user_type_ord'])){
		 	$isValid = false;
		 	$_SESSION['eMsg'][] = "Please enter a valid Order number.";
		}
		
		if (empty($pData_['user_type_dept'])) {
		 	$isValid = false;
		 	$_SESSION['eMsg'][] = "Please choose atleast one Department.";
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
			if ($keyData === 'user_type_access' || $keyData === 'user_type_dept') {
				$valData = trim(serialize($valData));
			}
			$flds[] = "$keyData='$valData'";
		}
		$fields = implode(", ",$flds);

		$sql = "insert into app_usertype set $fields";
		$this->conn->Execute($sql);
		
		// Insert privileges on app_userstypeaccess
		$user_type_id = $this->Data['user_type'];
		if (count($this->Data['user_type_dept'])>0) {
			// set the insert value template for a loop
			$utaTpl = implode("),(ud_id,'$user_type_id',",$_POST['user_type_access']);
			// do the department list loop
			foreach ($this->Data['user_type_dept'] as $key => $value) {
				$formattedUTA = str_replace("ud_id",$value,$utaTpl);
				$formattedUTA = "($value,'$user_type_id',".$formattedUTA.")";
				$sql = "insert into app_userstypeaccess (ud_id,user_type,mnu_id) values $formattedUTA";
				$rsResult = $this->conn->Execute($sql);
			}
		}

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
			if ($keyData === 'user_type_access' || $keyData === 'user_type_dept') {
				$valData = trim(serialize($valData));
			}
			$flds[] = "$keyData='$valData'";
		}
		$fields = implode(", ",$flds);

		$sql = "update app_usertype set $fields where user_type='$id'";
		$this->conn->Execute($sql);
		
		$user_type_id = $this->Data['user_type'];
		
		// Insert privileges on app_userstypeaccess
		$sql = "delete from app_userstypeaccess where user_type=?";
		$rsResult = $this->conn->Execute($sql,array($user_type_id));
		
		// Insert privileges on app_userstypeaccess
		if (count($this->Data['user_type_dept'])>0) {
			// set the insert value template for a loop
			$utaTpl = implode("),(ud_id,'$user_type_id',",$_POST['user_type_access']);
			// do the department list loop
			foreach ($this->Data['user_type_dept'] as $key => $value) {
				$formattedUTA = str_replace("ud_id",$value,$utaTpl);
				$formattedUTA = "($value,'$user_type_id',".$formattedUTA.")";
				$sql = "insert into app_userstypeaccess (ud_id,user_type,mnu_id) values $formattedUTA";
				$rsResult = $this->conn->Execute($sql);
			}
		}

		$_SESSION['eMsg']="Successfully Updated.";
	}

	/**
	 * Delete Record
	 *
	 * @param string $id_
	 */
	function doDelete($id_ = ""){
		$sql = "delete from app_usertype where user_type=?";
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
		if (isset($_REQUEST['search_field'])) {
			// lets check if the search field has a value
			if (strlen($_REQUEST['search_field'])>0) {
				// lets assign the request value in a variable
				$search_field = $_REQUEST['search_field'];
				// create a custom criteria in an array
				$qry[] = "aut.user_type like '%$search_field%'";
			}
		}
		
		//this is to fillter if not super admin hide!.
		if($_SESSION['admin_session_obj']['user_type']!='Super Administrator'){
			$qry[]="aut.user_type != 'Super Administrator'";
		}
		
		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";

		$arrSortBy = array(
		"viewdata"=>"viewdata",
		"user_type"=>"user_type",
		"user_type_name"=>"user_type_name",
		"user_type_ord"=>"user_type_ord",
		"user_type_status"=>"user_type_status",
		);

		if(isset($_GET['sortby'])){
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}else{
			$strOrderBy = " order by user_type_ord";
		}

		$viewLink = "";
		$editLink = "<a href=\"?statpos=manageusertype&edit=',aut.user_type,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/edit.png\" title=\"Edit\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
		$delLink = "<a href=\"?statpos=manageusertype&delete=',aut.user_type,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\"  border=0 width=\"16\" height=\"16\"></a>";

		$sql = "select aut.*, CONCAT('$viewLink','$editLink','$delLink') as viewdata ,
				IF(user_type_status = 1,'Active','Inactive') as user_type_status
					from app_usertype aut
					$criteria
					$strOrderBy";

		$sqlcount = "select count(*) as mycount from app_usertype aut $criteria";

		$arrFields = array(
		"viewdata"=>"<a href=\"?statpos=manageusertype&action=add\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/add.png\" title=\"Add New\" border=0 width=\"16\" height=\"16\"></a>",
		"user_type"=>"User Type",
		"user_type_name"=>"Name",
		"user_type_ord"=>"Order",
		"user_type_status"=>"Status"
		);

		$arrAttribs = array(
		"user_type_ord"=>" align='center'",
		"viewdata"=>"width='40' align='center'"
		);

		$tblDisplayList = new clsTableList($this->conn);
		$tblDisplayList->arrFields = $arrFields;
		$tblDisplayList->paginator->linkPage = "?$queryStr";
		$tblDisplayList->sqlAll = $sql;
		$tblDisplayList->sqlCount = $sqlcount;

		return $tblDisplayList->getTableList($arrAttribs);
	}
	
	/**
	 * Get all the Table Listings
	 *
	 * @return array
	 */
	function getPopup_TableList(){
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
		if (isset($_REQUEST['search_field'])) {

			// lets check if the search field has a value
			if (strlen($_REQUEST['search_field'])>0) {
				// lets assign the request value in a variable
				$search_field = $_REQUEST['search_field'];

				// create a custom criteria in an array
				$qry[] = "aut.user_type like '%$search_field%'";

				// put all query array into one string criteria
				$criteria = " where ".implode(" or ",$qry);

			}
		}

		$arrSortBy = array(
		"user_type"=>"user_type",
		"user_type_name"=>"user_type_name",
		"user_type_ord"=>"user_type_ord",
		"user_type_status"=>"user_type_status",
		);

		if(isset($_GET['sortby'])){
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}


		$viewLink = "";
		
		$idLinkBegin = "<a href=\"javascript:void(0)\"; onclick = \"opener.document.getElementById(\'user_type\').value=\'',aut.user_type,'\'; window.close();\">";
		$idLinkEnd = "</a>";
		
		$editLink = "<a href=\"?statpos=manageusertype&edit=',aut.user_type,'\"><img src=\"".SYSCONFIG_THEME_URLPATH.SYSCONFIG_THEME."/images/admin/edit.gif\" title=\"Edit\" hspace=\"2px\" border=0></a>";
		$delLink = "<a href=\"?statpos=manageusertype&delete=',aut.user_type,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_THEME_URLPATH.SYSCONFIG_THEME."/images/admin/trash.gif\" title=\"Delete\" hspace=\"2px\"  border=0></a>";

		$sql = "select aut.*, CONCAT('$idLinkBegin',aut.user_type,'$idLinkEnd') as user_type from app_usertype aut
				$criteria
				$strOrderBy";

		$sqlcount = "select count(*) as mycount from app_usertype aut $criteria";

		$arrFields = array(
		"user_type"=>"User Type",
		"user_type_name"=>"Name",
		"user_type_ord"=>"Order",
		"user_type_status"=>"Status",
		"viewdata"=>"&nbsp;"
		);

		$arrAttribs = array(
		"user_type_ord"=>" align='right'",
		"viewdata"=>"width='50' align='center'"
		);

		$tblDisplayList = new clsTableList($this->conn);
		$tblDisplayList->arrFields = $arrFields;
		$tblDisplayList->paginator->linkPage = "?$queryStr";
		$tblDisplayList->sqlAll = $sql;
		$tblDisplayList->sqlCount = $sqlcount;

		return $tblDisplayList->getTableList($arrAttribs);
	}

	/**
	 * this function will get all the modules menu item recursively
	 *
	 * @param  $dbconn_ database connection
	 * @param  $arrMenu_ child or parent array
	 * @param  $isChild_ check if the recursion call is a parent or child
	 * @param  $level level of recursion
	 * @return string recursive result
	 */
	function getModulesList($dbconn_ = null,$arrMenu_ = array(), $isChild_ = false, $level = 0 ,$selUserTypes_ = array()){
		if(count($arrMenu_) > 0){
			$arrCtr = 0;
			
			foreach ($arrMenu_ as $key => $value) {
				$sql = "select * from app_modules appmod 
				where appmod.mnu_status=1 and appmod.mnu_parent=? 
				group by appmod.mnu_id
				order by appmod.mnu_ord asc";
				$rsResult = $dbconn_->Execute($sql,array($value['mnu_id']));
				
				if($isChild_ && $level > 0){
					if($_SESSION['admin_session_obj']['user_type']!='Super Administrator'){
						if($value['mnu_name']!='Manage Modules' and $value['mnu_name']!='Deduction Type' and $value['mnu_name']!='Download Backup File'){
							$mnuData .= "<br />".str_repeat("&nbsp;<img src=\"".SYSCONFIG_THEME_URLPATH.SYSCONFIG_THEME."/images/line.gif\" width=\"12px\">&nbsp;",$level*1);
						}
					}else{
						$mnuData .= "<br />".str_repeat("&nbsp;<img src=\"".SYSCONFIG_THEME_URLPATH.SYSCONFIG_THEME."/images/line.gif\" width=\"12px\">&nbsp;",$level*1);
					}
				}else{ 
					$mnuData .= "";
				}
				$mnuIcon = empty($value['mnu_icon'])?"null":"'$value[mnu_icon]'";
				$mnuLink = empty($value['mnu_link'])?"null":"'$value[mnu_link]'";
				
				if($isChild_ && $level > 0){
					if($_SESSION['admin_session_obj']['user_type']!='Super Administrator'){
						if($value['mnu_name']!='Manage Modules' and  $value['mnu_name']!='Deduction Type' and $value['mnu_name']!='Download Backup File'){
							$mnuName = $value['mnu_name'];
						}
					}else{
						$mnuName = $value['mnu_name'];
					}
				}else{
					$mnuName = "<b>".$value['mnu_name']."</b>";
				}
				$checkedValue = (isset($selUserTypes_[$value['mnu_id']]))?" checked ":"";
				
				if($isChild_ && $level > 0){
					if($_SESSION['admin_session_obj']['user_type']!='Super Administrator'){
						if($value['mnu_name']!='Manage Modules' and  $value['mnu_name']!='Deduction Type' and $value['mnu_name']!='Download Backup File'){
							$mnuData .= "<input type='checkbox' name='user_type_access[$value[mnu_id]]' $checkedValue value='$value[mnu_id]'> $mnuName";
						}
					}else{
						$mnuData .= "<input type='checkbox' name='user_type_access[$value[mnu_id]]' $checkedValue value='$value[mnu_id]'> $mnuName";
					}
				}else{
					$mnuData .= "<input type='checkbox' name='user_type_access[$value[mnu_id]]' $checkedValue value='$value[mnu_id]'> $mnuName";
				}	
				$arrMenuIn = array();
				while(!$rsResult->EOF){
					$arrMenuIn[] = $rsResult->fields;
					$rsResult->MoveNext();
				}
				if(count($arrMenuIn) > 0){
					$mnuData .= clsUserType::getModulesList($dbconn_,$arrMenuIn,true,$level + 1 ,$selUserTypes_);
				}
				$mnuData .= "";
				if(!$isChild_ && (count($arrMenu_)-1) > $arrCtr++)
				$mnuData .= "<br /><hr size='1' noshade='noshade' style='color:#CCFF66; border-top: 1px solid' />";
			}		
		}

		return "$mnuData";
	}

	function getModulesParent($dbconn_ = null){
		$sql = "select * from app_modules where mnu_parent=0";
		$rsResult = $dbconn_->Execute($sql);
		$arrResult = array();
		while (!$rsResult->EOF) {
			$arrResult[]=$rsResult->fields;
			$rsResult->MoveNext();
		}
		return $arrResult;
	}

	function getUserTypeDept(){
		$sql = "select * from app_userdept order by ud_name";
		$rsResult = $this->conn->Execute($sql);
		$arrData = array();
		while (!$rsResult->EOF) {
			$arrData[] = $rsResult->fields;
			$rsResult->MoveNext();
		}
		if (count($arrData)==0) return $arrData;
		return $arrData;
	}
	
}

?>