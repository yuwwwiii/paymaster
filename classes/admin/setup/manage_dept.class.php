<?php
/**
 * Initial Declaration
 */


/**
 * Class Module
 *
 * @author  Jason Ife Mabignay
 *
 */
class clsManageDept{

	var $conn;
	var $fieldMap;
	var $Data;

	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsManageDept object
	 */
	function clsManageDept($dbconn_ = null){
		$this->conn =& $dbconn_;
		$this->fieldMap = array(
		"ud_name" => "ud_name",
		"ud_desc" => "ud_desc",
		"ud_code" => "ud_code",
		"ud_parent" => "ud_parent"
		);
	}

	/**
	 * Get the records from the database
	 *
	 * @param string $id_
	 * @return array
	 */
	function dbFetch($id_ = ""){
		$sql = "select * from app_userdept where ud_id=?";
		$rsResult = $this->conn->Execute($sql,array($id_));
		if(!$rsResult->EOF){
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

		if (empty($pData_['ud_name'])) {
			$isValid = false;
			$_SESSION['eMsg'][] = "Please enter Department Name.";
		}
		
		if (empty($pData_['ud_desc'])) {
			$isValid = false;
			$_SESSION['eMsg'][] = "Please enter Description.";
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

		$sql = "insert into app_userdept set $fields";
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

		$sql = "update app_userdept set $fields where ud_id=$id";
		$this->conn->Execute($sql);
		$_SESSION['eMsg']="Successfully Updated.";
	}

	/**
	 * Delete Record
	 *
	 * @param string $id_
	 */
	function doDelete($id_ = ""){
		$sql = "delete from app_userdept where ud_id=?";
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
				$qry[] = "ud.ud_name like '%$search_field%'";

				// put all query array into one string criteria
				$criteria = " where ".implode(" or ",$qry);
			}
		}

		$arrSortBy = array(
		"viewdata"=>"viewdata",
		"ud_code"=>"ud_code",
		"ud_name"=>"ud_name",
		"ud_desc"=>"ud_desc",
		"pcode"=>"pcode"
		);

		if(isset($_GET['sortby'])){
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}

		$viewLink = "";
		if ($_GET['statpos']=='managedept') {
			$editLink = "<a href=\"?statpos=managedept&edit=',ud.ud_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/edit.png\" title=\"Edit\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
			$delLink = "<a href=\"?statpos=managedept&delete=',ud.ud_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\"  border=0 width=\"16\" height=\"16\"></a>";
		}else{
			$popupLink = "<a href=\"javascript:void(0);\" onclick=\"window.parent.document.getElementById(\'ud_id\').value=\'',ud.ud_id,'\';
						window.parent.document.getElementById(\'ud_name\').value=\'',ud.ud_name,'\';
						parent.$.fancybox.close();\"><img src=\"".SYSCONFIG_THEME_URLPATH.SYSCONFIG_THEME."/images/admin/accept.gif\" title=\"Select\" hspace=\"2px\" border=0></a>";
		}
		
		$sql = "SELECT ud.*, CONCAT('$viewLink','$editLink','$delLink','$popupLink') as viewdata, IF(a.ud_code > 0,a.ud_code,0) as pcode, a.ud_name as pname
					FROM app_userdept a
				    RIGHT JOIN app_userdept ud ON (a.ud_id = ud.ud_parent) 
					$criteria 
					$strOrderBy";

//		$sqlcount = "select count(*) as mycount from app_userdept ud $criteria";

		$arrFields = array(
		"viewdata"=>"Action",
		"ud_code"=>"CODE",
		"ud_name"=>"Department Name",
		"ud_desc"=>"Description",
		"pcode"=>"Parent"
		);

		$arrAttribs = array(
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
	 * Get the list of all Department from the database
	 *
	 * @return array
	 */
	function getDeptParents(){
		$sql = "select ud_id, ud_name from app_userdept order by ud_name";
		$rsResult = $this->conn->Execute($sql);
		$arrData = array();
		$arrData[] = array('ud_id'=>0, 'ud_name'=>'ROOT');
		while (!$rsResult->EOF) {
			$arrData[] = $rsResult->fields;
			$rsResult->MoveNext();
		}
		if (count($arrData)==0) return $arrData;
		return $arrData;
	}
	
	function getDepartmentChildren($dbconn_ = null,$arrMenu_ = array(),$keySel = "", $isChild_ = false, $level = 0){
		if(count($arrMenu_) > 0){
			$arrCtr = 0;
			foreach ($arrMenu_ as $key => $value) {
				$sql = "select a.* from app_userdept a where a.ud_parent=? order by a.ud_name";
				$rsResult = $dbconn_->Execute($sql,array($value['ud_id']));

				if($isChild_ && $level > 0)
				$tab = str_repeat("+",$level);
				$kSel = ($value['ud_id']==$keySel)?" selected ":"";

				$arrMenuIn = array();
				while(!$rsResult->EOF){
					$arrMenuIn[] = $rsResult->fields;
					$rsResult->MoveNext();
				}
				if(count($arrMenuIn) > 0){
					$mnuData .= "<option value='$value[ud_id]' $kSel style='font-weight:bold;'>$tab$value[ud_name]</option>";
					$mnuData .= clsManageDept::getDepartmentChildren($dbconn_,$arrMenuIn,$keySel,true,$level + 1);
				}else {
					$mnuData .= "<option value='$value[ud_id]' $kSel>$tab$value[ud_name]</option>";
				}
			}

		}
		return "$mnuData";
	}
	
	function getDepartmentParent($dbconn_ = null){
		$arrResult = array();
	    $arrResult[] = Array(
	         "ud_id" => 0
	        ,"ud_name" => "ROOT"
	        ,"ud_desc" => ""
	        ,"ud_code" => ""
	        ,"ud_parent" => 0
	    );
		return $arrResult;
	}
}

?>