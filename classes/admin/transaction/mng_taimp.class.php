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
class clsMng_TAImp{

	var $conn;
	var $fieldMap;
	var $Data;

	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsMng_TAImp object
	 */
	function clsMng_TAImp($dbconn_ = null){
		$this->conn =& $dbconn_;
		$this->fieldMap = array(
		 "otr_id" => "otr_id"
		,"tatbl_id" => "tatbl_id"
		,"cfhead_id" => "cfhead_id"
		,"tamap_class" => "tamap_class"
		,"tamap_fixvalue" => "tamap_fixvalue"
		,"tamap_column" => "tamap_column"
		);
	}

	/**
	 * Get the records from the database
	 *
	 * @param string $id_
	 * @return array
	 */
	function dbFetch(){
		$sql = "SELECT * FROM ta_mapping_head WHERE tamap_id ='1'";
		$rsResult = $this->conn->Execute($sql);
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
	function doValidateData($pData_ = array()){
		$isValid = true;

//		$isValid = false;

		return $isValid;
	}

	/**
	 * Save New
	 *
	 */
	function doSaveAdd($gtype = 0,$gData = array()){
		$flds = array();
		IF($gtype=='1'){
			$flds[] = "tamapd_class = '1'";
			$flds[] = "tatbl_id = '".$gData['tatbl_id']."'";
			$flds[] = "tamapd_type = '".$gData['tabtn']."'";
			$column_ = $gData['tamapd_column'][$gData['tabtn']];
			$flds[] = "tamapd_column = '".$column_."'";
		}ELSEIF($gtype=='2'){
			
		}ELSE{
			
		}
		$flds[] = "tamap_id='1'";
		$flds[]="tamapd_addwho='".AppUser::getData('user_name')."'";
		$fields = implode(", ",$flds);

		$sql = "INSERT INTO ta_mapping_details SET $fields";
		$this->conn->Execute($sql);

		$_SESSION['eMsg']="Successfully Added.";
	}

	/**
	 * Save Update
	 *
	 */
	function doSaveEdit($id){
		$flds = array();
		$flds[] = "tamap_emp_id = '".$_POST['tamap_emp_id']."'";
		$flds[] = "tamap_start = '".$_POST['tamap_start']."'";
		$fields = implode(", ",$flds);
		$sql = "update ta_mapping_head set $fields where tamap_id='$id'";
		$this->conn->Execute($sql);
		$_SESSION['eMsg']="Successfully Updated.";
	}

	/**
	 * Delete Record
	 *
	 * @param string $id_
	 */
	function doDelete($id_ = ""){
		$sql = "delete from /*app_modules*/ where mnu_id=?";
		$this->conn->Execute($sql,array($id_));
		$_SESSION['eMsg']="Successfully Deleted.";
	}

	/**
	 * Get all the Table Listings
	 *
	 * @return array
	 */
	function getTableList($gtype = 0){
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
				$qry[] = "mnu_name like '%$search_field%'";

			}
		}
		IF($gtype=='1'){
			$qry[] = "am.tatbl_id != 0 ";
			$qry_ = "JOIN ta_tbl b on (am.tatbl_id=b.tatbl_id)";
			$var = "b.tatbl_name";
		}ELSEIF($gtype=='2'){
			$qry[] = "am.otr_id != 0 ";
			$qry_ = "JOIN ot_rates b on (am.otr_id=b.otr_id)";
			$var = "b.otr_name";
		}ELSE{
			$qry[] = "am.cfhead_id != 0 ";
			$qry_ = "JOIN cf_head b on (am.cfhead_id=b.cfhead_id)";
			$var = "b.cfhead_name";
		}
		
		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		 "viewdata"=>"viewdata"
		,$var=>$var
		,"tamapd_type"=>"tamapd_type"
		,"tamapd_column"=>"tamapd_column"
		);

		if(isset($_GET['sortby'])){
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}

		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$viewLink = "";
		$editLink = "<a href=\"?statpos=mng_taimp&edit=',am.tamapd_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/edit.png\" title=\"Edit\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
		$delLink = "<a href=\"?statpos=mng_taimp&delete=',am.tamapd_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\"  border=0 width=\"16\" height=\"16\"></a>";
		$action = "<a href=\"?statpos=mng_taimp&action=add\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/add.png\" title=\"Add New\" border=0 width=\"16\" height=\"16\"></a>";

		// SqlAll Query
		$sql = "select am.*, CONCAT('$viewLink','$editLink','$delLink') as viewdata, $var
						from ta_mapping_details am
						$qry_
						$criteria
						$strOrderBy";

		// Field and Table Header Mapping
		$arrFields = array(
		 "viewdata"=>$action
		,$var=>"Name"
		,"tamapd_type"=>"Type"
		,"tamapd_column"=>"Map"
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
	
	function getTAlist(){
		$sql = "SELECT * FROM ta_tbl";
		$rsResult = $this->conn->Execute($sql);
		while ( !$rsResult->EOF ) {       	
			$cResult[] = $rsResult->fields;        	
        	$rsResult->MoveNext();
        }
        return $cResult;
	}
	
	function getOTlist(){
		$sql = "SELECT * FROM ot_rates";
		$rsResult = $this->conn->Execute($sql);
		while ( !$rsResult->EOF ) {       	
			$cResult[] = $rsResult->fields;        	
        	$rsResult->MoveNext();
        }
        return $cResult;
	}
	
	function getCustomlist(){
		$sql = "SELECT * FROM cf_head";
		$rsResult = $this->conn->Execute($sql);
		while ( !$rsResult->EOF ) {       	
			$cResult[] = $rsResult->fields;        	
        	$rsResult->MoveNext();
        }
        return $cResult;
	}

}

?>