<?php
/**
 * Initial Declaration
 */

/**
 * Class Module
 * @author  JIM
 *
 */
class clsMngeDecimal{
	var $conn;
	var $fieldMap;
	var $Data;

	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsMngeDecimal object
	 */
	function clsMngeDecimal($dbconn_ = null){
		$this->conn =& $dbconn_;
		$this->fieldMap = array(
		 "set_name" => "set_name"
		,"set_decimal_places" => "set_decimal_places"
		);
	}

	/**
	 * Get the records from the database
	 *
	 * @param string $id_
	 * @return array
	 */
	function dbFetch($id_ = ""){
		$sql = "SELECT * FROM app_settings WHERE comp_id=?";
		$rsResult = $this->conn->Execute($sql,array($id_));
		$a = 0;
		while(!$rsResult->EOF){
			$arrData[$a] = $rsResult->fields;
			IF($rsResult->fields['set_name']=='TAX'){
				$arrData[$a][tax] = $this->getInfoSetup('tax_policy','tp_id',$rsResult->fields['set_decimal_places']);
			}ELSEIF($rsResult->fields['set_name']=='SSS'){
				$arrData[$a][sss] = $this->getInfoSetup('statutory_contribution','sc_id',$rsResult->fields['set_decimal_places']);
			}ELSEIF($rsResult->fields['set_name']=='PHIC'){
				$arrData[$a][phic] = $this->getInfoSetup('statutory_contribution','sc_id',$rsResult->fields['set_decimal_places']);
			}ELSEIF($rsResult->fields['set_name']=='HDMF'){
				$arrData[$a][hdmf] = $this->getInfoSetup('statutory_contribution','sc_id',$rsResult->fields['set_decimal_places']);
			}
			$a++;
            $rsResult->MoveNext();
		}
        return $arrData;
	}
	
	function getInfoSetup($table_ = null,$id_ = null,$idval = null){
		$sql = "SELECT * FROM $table_ WHERE $id_ = '".$idval."'";
		$rsResult = $this->conn->Execute($sql);
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
		if($pData_['val0'] > 5 || $pData_['val0'] < 0){
			$isValid = false;
			$_SESSION['eMsg'] = "Maximum decimal places for general settings is 5.";
		}
		if($pData_['val1'] > 2 || $pData_['val1'] < 0){
			$isValid = false;
			$_SESSION['eMsg'] = "Maximum decimal places for final values is 2.";
		}
		if($pData_['annualize']){
			if($pData_['last_paydate'] == "" || $pData_['last_paydate'] == "0000-00-00"){
				$isValid = false;
				$_SESSION['eMsg'] = "You've enable annualization. Last pay date should be valid date.";
			}
		}
//		$isValid = false;

		return $isValid;
	}

	/**
	 * Save New
	 *
	 */
	function doSaveAdd(){
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
	function doSaveEdit(){
		$id = $_GET['edit'];
		$flds = array();
		foreach ($this->Data as $keyData => $valData) {
			$valData = addslashes($valData);
			$flds[] = "$keyData='$valData'";
		}
		$fields = implode(", ",$flds);

		$sql = "update /*app_modules*/ set $fields";
		$this->conn->Execute($sql);
		$_SESSION['eMsg']="Successfully Updated.";
	}

	/**
	 * Delete Record
	 * @param string $id_
	 */
	function doDelete($id_ = ""){
		$sql = "delete from /*app_modules*/ where mnu_id=?";
		$this->conn->Execute($sql,array($id_));
		$_SESSION['eMsg']="Successfully Deleted.";
	}

	/**
	 * Get all the Table Listings
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
				$qry[] = "comp_name like '%$search_field%'";

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

		if(isset($_GET['sortby'])){
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}

		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$viewLink = "";
		$editLink = "<a href=\"?statpos=manage_decimal&edit=',am.comp_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/search.png\" title=\"View Policy\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
		// SqlAll Query
		$sql = "select am.*, CONCAT('$viewLink','$editLink','$delLink') as viewdata
						from company_info am
						$criteria
						$strOrderBy";

		// Field and Table Header Mapping
		$arrFields = array(
		 "viewdata"=>"Action"
		,"comp_code"=>"Company Code"
		,"comp_name"=>"Company Name"
		,"comp_add"=>"Address"
		,"comp_tel"=>"Tel"
		);

		// Column (table data) User Defined Attributes
		$arrAttribs = array(
		"set_order"=>" align='center'",
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

	function getDecimalValues(){
		$sql = "SELECT set_name, set_decimal_places FROM app_settings";
		$rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF){
			$arrData[] = $rsResult->fields;
            $rsResult->MoveNext();
		}
        return $arrData;
	}
	
	function doUpdate($pData =  array()){
		//Update General Decimal Settings
		$sql = "UPDATE app_settings SET set_decimal_places='".$pData['val0']."' WHERE set_name='General Decimal Settings'";
		$this->conn->Execute($sql);
		//Update Final Decimal Settings
		$sql = "UPDATE app_settings SET set_decimal_places='".$pData['val1']."' WHERE set_name='Final Decimal Settings'";
		$this->conn->Execute($sql);
		//Update Tax Settings
		$sql = "UPDATE app_settings SET set_stat_type='".$pData['stype_tax']."',set_order='".$pData['isStat']."',set_decimal_places='".$pData['tp_id']."' WHERE set_id='".$pData['valtax']."'";
		$this->conn->Execute($sql);
		//Update SSS Settings
		$sql = "UPDATE app_settings SET set_stat_type='".$pData['stype_sss']."',set_decimal_places='".$pData['sc_id']."' WHERE set_id='".$pData['valsss']."'";
		$this->conn->Execute($sql);
		//Update PHIC Settings
		$sql = "UPDATE app_settings SET set_stat_type='".$pData['stype_phic']."',set_decimal_places='".$pData['sc_id2']."' WHERE set_id='".$pData['valphic']."'";
		$this->conn->Execute($sql);
		//Update HDMF Settings
		$sql = "UPDATE app_settings SET set_stat_type='".$pData['stype_hdmf']."',set_decimal_places='".$pData['sc_id3']."' WHERE set_id='".$pData['valhdmf']."'";
		$this->conn->Execute($sql);
		//Update TA Import Form
		$sql = "UPDATE app_settings SET set_stat_type='".$pData['stype_taform']."' WHERE set_name='TA Import Form'";
		$this->conn->Execute($sql);
		//Update Payslip FORM
		$sql = "UPDATE app_settings SET set_stat_type='".$pData['stype_payslip']."' WHERE set_name='Payslip FORM'";
		$this->conn->Execute($sql);
		//Update OT Computation
		$sql = "UPDATE app_settings SET set_stat_type='".$pData['stype_ot']."' WHERE set_name='Overtime Computation'";
		$this->conn->Execute($sql);
		//Update Leave Deduction Computation
		$sql = "UPDATE app_settings SET set_stat_type='".$pData['stype_leave']."' WHERE set_name='Leave Deduction'";
		$this->conn->Execute($sql);
		//Update Tax Annualization
		if($pData['annualize']){
			$sql = "UPDATE app_settings SET set_stat_type='".$pData['annualize']."',set_other_data='".$pData['last_paydate']."' WHERE set_name='Annualize Tax on Last Pay Period of the Year'";
		} else {
			$sql = "UPDATE app_settings SET set_stat_type='".$pData['annualize']."',set_other_data=NULL WHERE set_name='Annualize Tax on Last Pay Period of the Year'";
		}
		$this->conn->Execute($sql);
		//Update Location as Company
		$sql = "UPDATE app_settings SET set_stat_type='".$pData['stype_isLoc']."' WHERE set_name='Location as Company'";
		$this->conn->Execute($sql);
		$_SESSION['eMsg']="Successfully Updated.";
	}
	
	/**
	 * Get Tax Table List
	 * @author : jim
 	 * @return all cResult List array
	 */
	function getTaxTableList(){
		$objData = $this->conn->Execute("SELECT tp_id,tp_name,tp_desc,tp_edate FROM tax_policy ORDER BY tp_edate");
		$cResult = array();
		while ( !$objData->EOF ) {       	
			$cResult[] = $objData->fields;
        	$objData->MoveNext();
        }
        return $cResult;
	}
	
	/**
	 * Get Statutory Table List
	 * @author : jim
 	 * @return all cResult List array
	 */
	function getStatutoryTableList($dec_id_=null){
		$objData = $this->conn->Execute("SELECT * FROM statutory_contribution WHERE dec_id='".$dec_id_."' ORDER BY sc_effectivedate");
		$cResult = array();
		while ( !$objData->EOF ) {       	
			$cResult[] = $objData->fields;
        	$objData->MoveNext();
        }
        return $cResult;
	}
}

?>