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
class clsFactor_Rate{

	var $conn;
	var $fieldMap;
	var $Data;

	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsFactor_Rate object
	 */
	function clsFactor_Rate($dbconn_ = null){
		$this->conn =& $dbconn_;
		$this->fieldMap = array(
		 "wrate_id"=>"wrate_id"
		,"fr_name"=>"fr_name"
		,"fr_hrperday"=>"fr_hrperday"
		,"fr_hrperweek"=>"fr_hrperweek"
		,"fr_dayperweek"=>"fr_dayperweek"
		,"fr_dayperyear"=>"fr_dayperyear"
		);
	}

	/**
	 * Get the records from the database
	 *
	 * @param string $id_
	 * @return array
	 */
	function dbFetch($id_ = ""){
		$objClsMngeDecimal = new Application();
		$sql = "SELECT a.*, b.wrate_name,
				FORMAT(a.fr_hrperday,".$objClsMngeDecimal->getGeneralDecimalSettings().") as fr_hrperday,
				FORMAT(a.fr_dayperweek,".$objClsMngeDecimal->getGeneralDecimalSettings().") as fr_dayperweek,
				FORMAT(a.fr_dayperyear,".$objClsMngeDecimal->getGeneralDecimalSettings().") as fr_dayperyear,
				FORMAT(a.fr_hrperweek,".$objClsMngeDecimal->getGeneralDecimalSettings().") as fr_hrperweek
				FROM factor_rate a LEFT JOIN app_wagerate b on(b.wrate_id=a.wrate_id) WHERE fr_id=?";
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

		if (empty($pData_['fr_name'])) {
			$isValid = false;
			$_SESSION['eMsg'][] = "Please enter a Name.";
		}
		if (empty($pData_['fr_hrperday'])) {
			$isValid = false;
			$_SESSION['eMsg'][] = "Please indicate Hours per Day.";
		}
		if (!is_numeric($pData_['fr_hrperday']) && !empty($pData_['fr_hrperday'])) {
			$isValid = false;
			$_SESSION['eMsg'][] = "Please enter a valid Hours per Day.";
		}
		if (empty($pData_['fr_hrperweek'])) {
			$isValid = false;
			$_SESSION['eMsg'][] = "Please indicate Hours per Week.";
		}
		if (!is_numeric($pData_['fr_hrperweek']) && !empty($pData_['fr_hrperweek'])) {
			$isValid = false;
			$_SESSION['eMsg'][] = "Please enter a valid Hours per Week.";
		}
		if (empty($pData_['fr_dayperweek'])) {
			$isValid = false;
			$_SESSION['eMsg'][] = "Please indicate Average Work Days per Week.";
		}
		if (!is_numeric($pData_['fr_dayperweek']) && !empty($pData_['fr_dayperweek'])) {
			$isValid = false;
			$_SESSION['eMsg'][] = "Please enter a valid Average Work Days per Week.";
		}
		if (empty($pData_['fr_dayperyear'])) {
			$isValid = false;
			$_SESSION['eMsg'][] = "Please indicate Days per Year.";
		}
		if (!is_numeric($pData_['fr_dayperyear']) && !empty($pData_['fr_dayperyear'])) {
			$isValid = false;
			$_SESSION['eMsg'][] = "Please enter a valid Days per Year.";
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
		$flds[]="fr_addwho='".AppUser::getData('user_name')."'";
		$fields = implode(", ",$flds);
		$sql = "insert into factor_rate set $fields";
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
		$flds[]="fr_updatewho='".AppUser::getData('user_name')."'";
		$flds[]="fr_updatewhen='".date('Y-m-d H:i:s')."'";
		$fields = implode(", ",$flds);
		$sql = "update factor_rate set $fields where fr_id=$id";
		$this->conn->Execute($sql);
		$_SESSION['eMsg']="Successfully Updated.";
	}

	/**
	 * Delete Record
	 *
	 * @param string $id_
	 */
	function doDelete($id_ = ""){
		$sql = "delete from factor_rate where fr_id=?";
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
				$qry[] = "fr_name like '%$search_field%'";

			}
		}

		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		 "viewdata"=>"viewdata"
		,"fr_name"=>"fr_name"
		,"fr_hrperday"=>"fr_hrperday"
		,"fr_hrperweek"=>"fr_hrperweek"
		,"fr_dayperweek"=>"fr_dayperweek"
		,"fr_dayperyear"=>"fr_dayperyear"
		,"wrate_minwagerate"=>"wrate_minwagerate"
		);

		if(isset($_GET['sortby'])){
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}

		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$viewLink = "";
		if ($_GET['statpos']=='factor_rate') {
			$editLink = "<a href=\"?statpos=factor_rate&edit=',am.fr_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/edit.png\" title=\"Edit\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
			$delLink = "<a href=\"?statpos=factor_rate&delete=',am.fr_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\"  border=0 width=\"16\" height=\"16\"></a>";
		}else{
			$popupLink = "<a href=\"javascript:void(0);\" onclick=\"window.parent.document.getElementById(\'fr_id\').value=\'',am.fr_id,'\';
							window.parent.document.getElementById(\'fr_name\').value=\'',am.fr_name,'\';
							parent.$.fancybox.close();\"><img src=\"".SYSCONFIG_THEME_URLPATH.SYSCONFIG_THEME."/images/admin/accept.gif\" title=\"Select\" hspace=\"2px\" border=0></a>";
		}
		$objClsMngeDecimal = new Application();
		// SqlAll Query
		$sql = "SELECT am.*,
				FORMAT(b.wrate_minwagerate,".$objClsMngeDecimal->getGeneralDecimalSettings().") as wrate_minwagerate, 
						FORMAT(am.fr_hrperday,".$objClsMngeDecimal->getGeneralDecimalSettings().") as fr_hrperday,
						FORMAT(am.fr_hrperweek,".$objClsMngeDecimal->getGeneralDecimalSettings().") as fr_hrperweek,
						FORMAT(am.fr_dayperweek,".$objClsMngeDecimal->getGeneralDecimalSettings().") as fr_dayperweek,
						FORMAT(am.fr_dayperyear,".$objClsMngeDecimal->getGeneralDecimalSettings().") as fr_dayperyear,
				CONCAT('$viewLink','$editLink','$delLink','$popupLink') as viewdata
						FROM factor_rate am
						LEFT JOIN app_wagerate b on (b.wrate_id=am.wrate_id)
						$criteria
						$strOrderBy";

		// Field and Table Header Mapping
		$arrFields = array(
		 "viewdata"=>"Action"
		,"fr_name"=>"Name"
		,"fr_hrperday"=>"Hour per Day"
		,"fr_hrperweek"=>"Hour per Week"
		,"fr_dayperweek"=>"Day per Week"
		,"fr_dayperyear"=>"Day per Year"
		,"wrate_minwagerate"=>"MWR"
		);

		// Column (table data) User Defined Attributes
		$arrAttribs = array(
		"mnu_ord"=>" align='center'",
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
	function GetWageRate(){//drop down bank list
		$sql = "SELECT * FROM app_wagerate order by wrate_name";
		$rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF){
			$app_region[] = $rsResult->fields;
			$rsResult->MoveNext();
		}
		return $app_region;
	}
}
?>