<?php
/**
 * Initial Declaration
 */
$arrStatus = array(
1 => "Active",
2 => "Inactive"
);

class clsManageModules{

	var $conn;
	var $fieldMap;
	var $fieldMapInfo;
	var $Data;

	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsManageModules object
	 */
	function clsManageModules($dbconn_ = null){
		$this->conn =& $dbconn_;
		$this->fieldMap = array(
		"mnu_name" => "mnu_name",
		"mnu_desc" => "mnu_desc",
		"mnu_parent" => "mnu_parent",
		"mnu_icon" => "mnu_icon",
		"mnu_ord" => "mnu_ord",
		"mnu_status" => "mnu_status",
		"mnu_link_info" => "mnu_link_info"
		);
		$this->fieldMapInfo = array(
		"linkpage",
		"statpos",
		"querystring",
		"maincontroller",
		"default_controller",
		"controller_filename",
		"class_filename",
		"class_name",
		"template_filename",
		"templateform_filename",
		"chkmenu",
		"chkmain",
		"chkcontroller",
		"chkclass",
		"chktemplate",
		"chktemplateform",
		"ud_id",
		"user_type"
		);
	}

	/**
	 * Get the records from the database
	 *
	 * @param string $id_
	 * @return array
	 */
	function dbFetch($id_ = ""){
		$sql = "select * from app_modules where mnu_id=?";
		$rsResult = $this->conn->Execute($sql,array($id_));
		if(!$rsResult->EOF){
			$rsResult->fields['mnu_link_info'] = unserialize($rsResult->fields['mnu_link_info']);
			return $rsResult->fields;
		}
	}
	/**
	 * Populate array parameters to Data Variable
	 *
	 * @param array $pData_
	 * @return bool
	 */
	function doPopulateData($pData_ = array()){
		if(count($pData_)>0){
			foreach ($this->fieldMap as $key => $value) {
				$this->Data[$key] = $pData_[$value];
			}
			foreach ($this->fieldMapInfo as $value) {
				if(($value == "ud_id" || $value == "user_type") && !isset($pData_[$value]))
				$this->Data['mnu_link_info'][$value] = array();
				else
				$this->Data['mnu_link_info'][$value] = $pData_[$value];
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

		if (strlen($pData_['mnu_name'])==0) {
			$_SESSION['eMsg'][] = "Enter name of menu";
			$isValid = false;
		}

		if (strlen($pData_['mnu_ord'])==0) {
			$_SESSION['eMsg'][] = "Enter menu order";
			$isValid = false;
		}

		$isValid = true;

		return $isValid;
	}

	/**
	 * Save New
	 *
	 */
	function doSaveAdd(){
		$flds = array();
		$snull = is_null($this->Data['mnu_link_info']['chkmenu']);
		if(!is_null($this->Data['mnu_link_info']['chkmenu'])){
			foreach ($this->Data as $keyData => $valData) {
				if($keyData=='mnu_link_info') $valData = serialize($valData);
				$valData = addslashes($valData);
				$flds[] = "$keyData='$valData'";
			}
	//		if($this->Data['mnu_parent']!=0){
				$mnuLink = ((empty($this->Data['mnu_link_info']['linkpage']))?"":$this->Data['mnu_link_info']['linkpage']).((empty($this->Data['mnu_link_info']['statpos']))?"":"?statpos=".$this->Data['mnu_link_info']['statpos']).((empty($this->Data['mnu_link_info']['querystring']))?"":$this->Data['mnu_link_info']['querystring']);
				$flds[] = "mnu_link='$mnuLink'";
	//		}
			$fields = implode(", ",$flds);

			$sql = "insert into app_modules set $fields";
			$this->conn->Execute($sql);

			$mnu_id = $this->conn->Insert_Id();

			//process user rights per usertype
			/*if (count($this->Data['mnu_link_info']['ud_id'])>0) {
				foreach ($this->Data['mnu_link_info']['ud_id'] as $udkey => $udvalue) {
					if (count($this->Data['mnu_link_info']['user_type'])>0) {
						foreach ($this->Data['mnu_link_info']['user_type'] as $key => $value) {
							$sql = "insert into app_userstypeaccess (mnu_id,user_type,ud_id) values ($mnu_id,'$value','$udvalue')";
							$this->conn->Execute($sql);
						}
					}
				}
			}*/
		}

		$this->doCreateFile();

		$_SESSION['eMsg']="Successfully Added.";
	}


	/**
	 * Save Update
	 *
	 */
	function doSaveEdit(){
		$mnu_id = $_GET['edit'];

		$flds = array();
		if(!is_null($this->Data['mnu_link_info']['chkmenu'])){
			foreach ($this->Data as $keyData => $valData) {
				if($keyData=='mnu_link_info') $valData = serialize($valData);
				$valData = addslashes($valData);
				$flds[] = "$keyData='$valData'";
			}
			//if($this->Data['mnu_parent']!=0){
			$mnuLink = ((empty($this->Data['mnu_link_info']['linkpage']))?"":$this->Data['mnu_link_info']['linkpage']).((empty($this->Data['mnu_link_info']['statpos']))?"":"?statpos=".$this->Data['mnu_link_info']['statpos']).((empty($this->Data['mnu_link_info']['querystring']))?"":$this->Data['mnu_link_info']['querystring']);
			$flds[] = "mnu_link='$mnuLink'";
			//}
			$fields = implode(", ",$flds);

			$sql = "update app_modules set $fields where mnu_id=$mnu_id";
			$this->conn->Execute($sql);

			$sql = "delete from app_userstypeaccess where mnu_id=$mnu_id";
			$this->conn->Execute($sql);

			// update user rights access
			/*if (count($this->Data['mnu_link_info']['ud_id'])>0) {
				foreach ($this->Data['mnu_link_info']['ud_id'] as $udkey => $udvalue) {
					if (count($this->Data['mnu_link_info']['user_type'])>0) {
						foreach ($this->Data['mnu_link_info']['user_type'] as $key => $value) {
							$sql = "insert into app_userstypeaccess (mnu_id,user_type,ud_id) values ($mnu_id,'$value','$udvalue')";
							$this->conn->Execute($sql);
						}
					}
				}
			}*/
		}

		$this->doCreateFile();

		$_SESSION['eMsg']="Successfully Updated.";
	}

	function doCreateFolders($mainPath_,$path_){
		if (!empty($path_)) {
			$arrTemp = explode("/",$path_);
			array_splice($arrTemp,count($arrTemp)-1);
			$tFilename = $mainPath_;
			foreach ($arrTemp as $value) {
				$tFilename .= $value."/";
				if (!file_exists($tFilename)) {
			    mkdir($tFilename);
				}
			}
		}
	}

	function doCreateFile(){
		if (!is_null($this->Data['mnu_link_info']['chkmain'])) {
			$filename = SYSCONFIG_ROOT_PATH.$this->Data['mnu_link_info']['maincontroller'];
//			echo $filename;
			$fhandle = fopen($filename,"wb");
			$tplFilename = SYSCONFIG_THEME_PATH.SYSCONFIG_THEME."/templates/modz_tpl/maincontroller.tpl.php";
			$templateData = file_get_contents($tplFilename);

			$arrTemp = explode("/",$this->Data['mnu_link_info']['maincontroller']);
			$templateData = str_replace("{*linkpage*}",$arrTemp[count($arrTemp)-1],$templateData);
			$arrTemp = explode("/",$this->Data['mnu_link_info']['controller_filename']);
			array_splice($arrTemp,count($arrTemp)-1);
			$templateData = str_replace("{*controllerpath*}",implode("/",$arrTemp)."/",$templateData);
			$templateData = str_replace("{*maincontroller*}",$this->Data['mnu_link_info']['default_controller'],$templateData);
//			echo "<pre>".htmlentities($templateData)."</pre>";
			fputs($fhandle,$templateData);
			fclose($fhandle);
		}
	// controller creation


		if (!is_null($this->Data['mnu_link_info']['chkcontroller'])) {
			$filename = SYSCONFIG_MODULE_PATH.$this->Data['mnu_link_info']['controller_filename'];
			$this->doCreateFolders(SYSCONFIG_MODULE_PATH,$this->Data['mnu_link_info']['controller_filename']);
//			echo $filename;
			$fhandle = fopen($filename,"wb");
			$tplFilename = SYSCONFIG_THEME_PATH.SYSCONFIG_THEME."/templates/modz_tpl/controller.tpl.php";
			$templateData = file_get_contents($tplFilename);

			$templateData = str_replace("{*classpath_filename*}",$this->Data['mnu_link_info']['class_filename'],$templateData);
			$templateData = str_replace("{*PageTitle*}",$this->Data['mnu_name'],$templateData);
			$arrTemp = explode("/",$this->Data['mnu_link_info']['template_filename']);
			$templateData = str_replace("{*template_filename*}",$arrTemp[count($arrTemp)-1],$templateData);
			$arrTemp = explode("/",$this->Data['mnu_link_info']['templateform_filename']);
			$templateData = str_replace("{*templateform_filename*}",$arrTemp[count($arrTemp)-1],$templateData);
			$arrTemp = explode("/",$this->Data['mnu_link_info']['maincontroller']);
			$templateData = str_replace("{*mainblockpath*}",$arrTemp[0],$templateData);
			$arrTemp = explode("/",$this->Data['mnu_link_info']['controller_filename']);
			array_splice($arrTemp,count($arrTemp)-1);
			$templateData = str_replace("{*centerpanelblockpath*}",implode("/",$arrTemp),$templateData);
			$templateData = str_replace("{*mnu_link*}",$this->Data['mnu_link_info']['linkpage']."?statpos=".$this->Data['mnu_link_info']['statpos'].((!empty($this->Data['mnu_link_info']['querystring']))?"&".$this->Data['mnu_link_info']['querystring']:""),$templateData);
			$templateData = str_replace("{*mnu_parent*}",$this->Data['mnu_name'],$templateData);
			$templateData = str_replace("{*mnu_name*}",$this->Data['mnu_name'],$templateData);
			$templateData = str_replace("{*classname*}",$this->Data['mnu_link_info']['class_name'],$templateData);
			$templateData = str_replace("{*omodule_name*}","obj".ucfirst($this->Data['mnu_link_info']['class_name']),$templateData);
//			echo "<pre>".htmlentities($templateData)."</pre>";
			fputs($fhandle,$templateData);
			fclose($fhandle);
		}

	// Class creation
		if (!is_null($this->Data['mnu_link_info']['chkclass'])) {
			$filename = SYSCONFIG_CLASS_PATH.$this->Data['mnu_link_info']['class_filename'];
			$this->doCreateFolders(SYSCONFIG_CLASS_PATH,$this->Data['mnu_link_info']['class_filename']);
//				echo $filename;
			$fhandle = fopen($filename,"wb");
			$tplFilename = SYSCONFIG_THEME_PATH.SYSCONFIG_THEME."/templates/modz_tpl/class.tpl.php";
			$templateData = file_get_contents($tplFilename);

			$templateData = str_replace("{*classname*}",$this->Data['mnu_link_info']['class_name'],$templateData);
			$templateData = str_replace("{*statpos*}",$this->Data['mnu_link_info']['statpos'],$templateData);
//				echo "<pre>".htmlentities($templateData)."</pre>";
			fputs($fhandle,$templateData);
			fclose($fhandle);
		}

	// template creation
		if (!is_null($this->Data['mnu_link_info']['chktemplate'])) {
			$filename = SYSCONFIG_THEME_PATH.SYSCONFIG_THEME."/templates/".$this->Data['mnu_link_info']['template_filename'];
			$this->doCreateFolders(SYSCONFIG_THEME_PATH.SYSCONFIG_THEME."/templates/",$this->Data['mnu_link_info']['template_filename']);
//			echo $filename;
			$fhandle = fopen($filename,"wb");
			$tplFilename = SYSCONFIG_THEME_PATH.SYSCONFIG_THEME."/templates/modz_tpl/template.tpl.php";
			$templateData = file_get_contents($tplFilename);

			$templateData = str_replace("{*modulelink*}",$this->Data['mnu_link_info']['linkpage']."?statpos=".$this->Data['mnu_link_info']['statpos'],$templateData);
//			echo "<pre>".htmlentities($templateData)."</pre>";
			fputs($fhandle,$templateData);
			fclose($fhandle);
		}

	// template form creation
		if (!is_null($this->Data['mnu_link_info']['chktemplateform'])) {
			$filename = SYSCONFIG_THEME_PATH.SYSCONFIG_THEME."/templates/".$this->Data['mnu_link_info']['templateform_filename'];
			$this->doCreateFolders(SYSCONFIG_THEME_PATH.SYSCONFIG_THEME."/templates/",$this->Data['mnu_link_info']['templateform_filename']);
//			echo $filename;
			$fhandle = fopen($filename,"wb");
			$tplFilename = SYSCONFIG_THEME_PATH.SYSCONFIG_THEME."/templates/modz_tpl/template_form.tpl.php";
			$templateData = file_get_contents($tplFilename);

			$templateData = str_replace("{*moduleformname*}",$this->Data['mnu_name']." Form",$templateData);
//			echo "<pre>".htmlentities($templateData)."</pre>";
			fputs($fhandle,$templateData);
			fclose($fhandle);
		}

	}

	function doDelete($id_ = ""){
		$sql = "delete from app_modules where mnu_id=?";
		$this->conn->Execute($sql,array($id_));

		$sql = "delete from app_userstypeaccess where mnu_id=?";
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
				$qry[] = "mnu_name like '%$search_field%'";

				// put all query array into one string criteria
				$criteria = " where ".implode(" or ",$qry);

			}
		}

		$arrSortBy = array(
		"viewdata"=>"viewdata",
		"mnu_name"=>"mnu_name",
		"mnu_link"=>"mnu_link",
		"mnu_ord"=>"mnu_ord",
		);

		if(isset($_GET['sortby'])){
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}

		$viewLink = "";
		$editLink = "<a href=\"?statpos=managemodule&edit=',am.mnu_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/edit.png\" title=\"Edit\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
		$delLink = "<a href=\"?statpos=managemodule&delete=',am.mnu_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\"  border=0 width=\"16\" height=\"16\"></a>";

		$sql = "select am.*, CONCAT('$viewLink','$editLink','$delLink') as viewdata from app_modules am $criteria $strOrderBy";

		$sqlcount = "select count(*) as mycount from app_modules $criteria order by mnu_ord";

		$arrFields = array(
		"viewdata"=>"<a href=\"?statpos=managemodule&action=add\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/add.png\" title=\"Add New\" border=0 width=\"16\" height=\"16\"></a>",
		"mnu_name"=>"Module Name",
		"mnu_link"=>"Link",
		"mnu_ord"=>"Order"
		);

		$arrAttribs = array(
		"mnu_ord"=>" align='center'",
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
	 * Get the list of all Modules or Menu from the database
	 *
	 * @return array
	 */
	function getParents(){
		$sql = "select mnu_id, mnu_name from app_modules where mnu_status=1 order by mnu_name";
		$rsResult = $this->conn->Execute($sql);
		$arrData = array();
		$arrData[] = array('mnu_id'=>0, 'mnu_name'=>'ROOT');
		while (!$rsResult->EOF) {
			$arrData[] = $rsResult->fields;
			$rsResult->MoveNext();
		}
		if (count($arrData)==0) return $arrData;
		return $arrData;
	}

	/**
	 * Get the current list of User Types
	 *
	 * @return array
	 */
	function getUserTypes(){
		$sql = "select * from app_usertype where user_type_status=1 order by user_type_ord";
		$rsResult = $this->conn->Execute($sql);
		$arrData = array();
		while (!$rsResult->EOF) {
			$arrData[] = $rsResult->fields;
			$rsResult->MoveNext();
		}
		if (count($arrData)==0) return $arrData;
		return $arrData;
	}

	function getDepartment(){
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


	function getModuleChildren($dbconn_ = null,$arrMenu_ = array(),$keySel = "", $isChild_ = false, $level = 0){
		if(count($arrMenu_) > 0){
			$arrCtr = 0;
			foreach ($arrMenu_ as $key => $value) {
				$sql = "select a.* from app_modules a where a.mnu_parent=? order by a.mnu_ord";
				$rsResult = $dbconn_->Execute($sql,array($value['mnu_id']));

				if($isChild_ && $level > 0)
				$tab = str_repeat("+",$level);
				$kSel = ($value['mnu_id']==$keySel)?" selected ":"";

				$arrMenuIn = array();
				while(!$rsResult->EOF){
					$arrMenuIn[] = $rsResult->fields;
					$rsResult->MoveNext();
				}
				if(count($arrMenuIn) > 0){
					$mnuData .= "<option value='$value[mnu_id]' $kSel style='font-weight:bold;'>$tab$value[mnu_name]</option>";
					$mnuData .= clsManageModules::getModuleChildren($dbconn_,$arrMenuIn,$keySel,true,$level + 1);
				}else {
					$mnuData .= "<option value='$value[mnu_id]' $kSel>$tab$value[mnu_name]</option>";
				}
			}

		}
		return "$mnuData";
	}

	function getModuleParent($dbconn_ = null){
//		$sql = "select * from app_modules where mnu_parent=0";
//		$rsResult = $dbconn_->Execute($sql);
		$arrResult = array();
    $arrResult[] = Array
    (
        "mnu_id" => 0
        ,"mnu_name" => "ROOT"
        ,"mnu_desc" => ""
        ,"mnu_icon" => ""
        ,"mnu_parent" => 0
        ,"mnu_ord" => 0
        ,"mnu_link" => ""
        ,"mnu_status" => 1
        ,"mnu_link_info" => ""
    );
        
//		while (!$rsResult->EOF) {
//			$arrResult[]=$rsResult->fields;
//			$rsResult->MoveNext();
//		}
		return $arrResult;
	}

}


?>
