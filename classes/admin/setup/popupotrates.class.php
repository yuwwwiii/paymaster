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
	function clsEMPType($dbconn_ = null){
		$this->conn =& $dbconn_;
		$this->fieldMap = array(
		 "ot_id" => "ot_id",
		 "otr_id" => "otr_id"
		);
	}

	/**
	 * Get the records from the database
	 *
	 * @param string $id_
	 * @return array
	 */
	function dbFetch($id_ = ""){
		$sql = "Select * from emp_type where emptype_id=?";
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
			foreach ($this->fieldMap as $key => $value){
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
	function doSaveAdd(){
		$flds = array();
		foreach ($this->Data as $keyData => $valData) {
			$valData = addslashes($valData);
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
	function doSaveEdit($rates){
	
	$id = $_GET['ot_id'];
	if($this->conn->Execute("delete from ot_tr where ot_id='".$id."'")){
	
	}else $_SESSION['eMsg'] .= mysql_error();

	$r = explode(',',$rates);
	///*
	foreach ($r as $valData) {
		$valData = addslashes($valData);
		
		if($this->conn->Execute("insert into ot_tr (ot_id,otr_id) values('".$id."','".$valData."')"))
		{ }else $_SESSION['eMsg'] .= mysql_error();
	}
	//*/
	$sql = "update ot_tbl set ot_rates='".$rates."' where ot_id=$id";
	if($this->conn->Execute($sql))
		$_SESSION['eMsg'] .= "Successfully Updated.";
		
	if($rates==0){
	$this->conn->Execute("delete from ot_tr where ot_id='".$id."'");
	}
	}

	/**
	 * Delete Record
	 *
	 * @param string $id_
	 */
	function doDelete($id_ = ""){
		$sql = "delete from emp_type where emptype_id=?";
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
				$qry[] = "otr_name like '%$search_field%' || otr_desc like '%$search_field%' || otr_type like '%$search_field%' || otr_factor like '%$search_field%'";

			}
		}

		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		 "viewdata"=>"viewdata",
		"otr_name" => "otr_name",
		"otr_desc" => "otr_desc",
		"otr_type" => "otr_type",
		"otr_factor" => "otr_factor"
		);

		if(isset($_GET['sortby'])){
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}
		
		unset($r,$imp,$default);
		//Get ot rates:	
		$sql = "select otr_id from ot_tr where ot_id='".$_GET['ot_id']."'";
		$rsResult = $this->conn->Execute($sql);
	
		while(!$rsResult->EOF){
		$default[] = $rsResult->fields['otr_id'];
		$rsResult->MoveNext();
		}
		
		
		if($default != 0){
		//foreach($default as $rate){
		//	$r[] = "'".$rate."s'";
		//	}
		$rates = implode(",",$default);
		}
		else $rates = "''";

		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$viewLink = "";
		$popupLink = "<input type=\"hidden\" name=\"assignrates\"><input type=\"checkbox\"  name=\"otr_rate[]\" id=\"otr_rate[]\" value=\"',otr_id,'\" ',if(otr_id IN (".$rates."),'checked',''),'>";
		
		
		// SqlAll Query
		$sql = "select am.*, CONCAT('$popupLink') as viewdata
						from ot_rates am
						$criteria
						$strOrderBy";

		// Field and Table Header Mapping
		$arrFields = array(
		"viewdata"=>"",
		"otr_name" => "Name",
		"otr_desc" => "Description",
		"otr_type" => "Type",
		"otr_factor" => "Factor"
		);

		// Column (table data) User Defined Attributes
		$arrAttribs = array(
		"mnu_ord"=>" align='right'",
		"viewdata"=>"width='30' align='center'"
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
	 * 
	 * USED to get OT computation.
	 * 
	 * @param unknown_type $emp_id_
	 */
	function getpopup_OTrate($emp_id_=""){

		$qry[]="comp.emp_id='".$emp_id_."'";
		
		// put all query array into one string criteria
		$criteria = " where ".implode(" or ",$qry);
		
		$sql = "select rates.*
					from payroll_comp comp 
					inner join ot_tbl ottbl on (ottbl.ot_id=comp.ot_id)
					inner join ot_tr ottr on (ottbl.ot_id = ottr.ot_id) 
					inner join ot_rates rates on (rates.otr_id = ottr.otr_id) 
					$criteria";
		$objData = $this->conn->Execute($sql);
		$cResult = array();
		$cnt = 0;
		while ( !$objData->EOF ) { 
			$cResult[$cnt] = $objData->fields;  
			$cResult[$cnt]['rateAmount'] = $this->getComputerate($emp_id_);     	
        	$objData->MoveNext();
        	$cnt ++;
        }
//        printa($cResult);
//        exit;
        return $cResult;
	}
	
	function getComputerate($emp_id_ = null){
		
		$qry[]="emp.emp_id='".$emp_id_."'";
		$qry[]="sal_info.salaryinfo_isactive='1'";
		
		// put all query array into one string criteria
		$criteria = " where ".implode(" and ",$qry);
		$strOrderBy = " order by sal_info.salaryinfo_effectdate ASC";
		$sql ="Select sal_info.* 
			   from emp_masterfile emp
			   inner join salary_info sal_info on (sal_info.emp_id=emp.emp_id)
			   $criteria
			   $strOrderBy
			   limit 1
			   ";
		$objData = $this->conn->Execute($sql);
		$cResult = array();
		if(!$objData->EOF){
			
			return $objData->fields;
		}	   
	}

}

?>