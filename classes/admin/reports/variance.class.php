
<?php
/**
 * Initial Declaration
 */

/**
 * Class Module
 * @author  JIM
 */


class clsVariance{
	var $conn;
	var $fieldMap;
	var $Data;

	/**
	 * Class Constructor
	 * @param object $dbconn_
	 * @return clsVariance object
	 */
	function clsVariance($dbconn_ = null){
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
	 * @param string $id_
	 * @return array
	 */
	function dbFetch($id_ = ""){
		$sql = "";
		$rsResult = $this->conn->Execute($sql,array($id_));
		if(!$rsResult->EOF){
			return $rsResult->fields;
		}
	}
	
	/**
	 * Populate array parameters to Data Variable
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
	 * @param array $pData_
	 * @return bool
	 */
	function doValidateData($pData_ = array()){
		$isValid = true;
//		$isValid = false;
		return $isValid;
	}

	/**
	 * Save New Record
	 */
	function doSaveAdd(){
		$flds = array();
		foreach ($this->Data as $keyData => $valData) {
			$valData = addslashes($valData);
			$flds[] = "$keyData='$valData'";
		}
		$fields = implode(", ",$flds);
		$sql = "INSERT INTO /*app_modules*/ SET $fields";
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
		$sql = "UPDATE /*app_modules*/ SET $fields WHERE mnu_id=$id";
		$this->conn->Execute($sql);
		$_SESSION['eMsg']="Successfully Updated.";
	}

	/**
	 * Delete Record
	 * @param string $id_
	 */
	function doDelete($id_ = ""){
		$sql = "DELETE FROM /*app_modules*/ WHERE mnu_id=?";
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
				$qry[] = "mnu_name like '%$search_field%'";
			}
		}

		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" WHERE ".implode(" AND ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		 "viewdata"=>"viewdata"
		,"mnu_name"=>"mnu_name"
		,"mnu_link"=>"mnu_link"
		,"mnu_ord"=>"mnu_ord"
		);

		if(isset($_GET['sortby'])){
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}

		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$viewLink = "";
		$editLink = "<a href=\"?statpos=variance&edit=',am.mnu_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/edit.png\" title=\"Edit\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
		$delLink = "<a href=\"?statpos=variance&delete=',am.mnu_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\"  border=0 width=\"16\" height=\"16\"></a>";
		$action = "<a href=\"?statpos=variance&action=add\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/add.png\" title=\"Add New\" border=0 width=\"16\" height=\"16\"></a>";

		// SqlAll Query
		$sql = "SELECT am.*, CONCAT('$viewLink','$editLink','$delLink') as viewdata
						FROM app_modules am
						$criteria
						$strOrderBy";

		// Field and Table Header Mapping
		$arrFields = array(
		 "viewdata"=>$action
		,"mnu_name"=>"Module Name"
		,"mnu_link"=>"Link"
		,"mnu_ord"=>"Order"
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
	

	function getYear(){ 
		$sql= "Select distinct payperiod_period_year from payroll_pay_period";
		$rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF){
			$year[$rsResult->fields['payperiod_period_year']]=$rsResult->fields['payperiod_period_year']	;	
			$rsResult->MoveNext();
		}
		return $year;		
	}
	
	function getBarData( $month= null,$year){	
		$height=1050;	
		$temp=500000;
		$i=0;
		$list=array();
		for($month=1;$month<=12;$month++){
			$sql= "SELECT SUM(e.ppe_amount)AS ftotal
					FROM payroll_paystub_entry e
					INNER JOIN payroll_paystub_report AS r
					ON e.paystub_id=r.paystub_id
					INNER JOIN payroll_pay_period AS p
					ON r.payperiod_id=p.payperiod_id
					WHERE 
					psa_id=5 AND (p.payperiod_period='".$month."' AND p.payperiod_period_year='".$year."');";
			$r = $this->conn->Execute($sql);
			while(!$r->EOF){	
				$i++;
				$arr[$month]=$r->fields;
				$r->MoveNext();
				$list[$i]=$arr[$month];
			}
		}
		$big=max($list);
		$default=$height/$big['ftotal'];
		for($i=1;$i<=count($list);$i++){
			if($list[$i]['ftotal']==0){
				$sfinal=0;
			} else {
				$hold=$default*$list[$i]['ftotal'];
				$final=($height-$hold)-20;
				$sfinal=(1070-$final)+20;
			}
			$f[$i]=$sfinal;
		}
		return $f;
	}
	
	function getExact($month= null,$year){
		for($month=1;$month<=12;$month++){
			$sql= "SELECT SUM(e.ppe_amount)AS ftotal
					FROM payroll_paystub_entry e
					INNER JOIN payroll_paystub_report AS r ON e.paystub_id=r.paystub_id
					INNER JOIN payroll_pay_period AS p ON r.payperiod_id=p.payperiod_id
					WHERE psa_id=5 AND (p.payperiod_period='".$month."' AND p.payperiod_period_year='".$year."')";
			$r = $this->conn->Execute($sql);
			while(!$r->EOF){	
				$i++;
				$arr[$month]=$r->fields;
				$r->MoveNext();
			}
		}
		return $arr;
	}
		 		
	function tvalue($barHeight = array()){
		foreach($barHeight as $key => $val){
			$height = (1330.5-$val)+15;
		  	$f[$key] = $height;
		}		
		return $f;
	}

	function ceilingTop($month= null,$year){	
		$temp=500000;
		for($month=1;$month<=12;$month++){
			$sql= "SELECT SUM(e.ppe_amount)AS ftotal
					FROM payroll_paystub_entry e
					INNER JOIN payroll_paystub_report AS r ON e.paystub_id=r.paystub_id
					INNER JOIN payroll_pay_period AS p ON r.payperiod_id=p.payperiod_id
					WHERE psa_id=5 AND (p.payperiod_period='".$month."' AND p.payperiod_period_year='".$year."')";
			$r = $this->conn->Execute($sql);
			while(!$r->EOF){	
				$i++;
				$arr[$month]=$r->fields;
				$r->MoveNext();
			}
		}
		$big=max($arr);
		while($temp<$big['ftotal']){	
			$temp=$temp+500000;
			if($temp>$big['ftotal']){	
				$higher=$temp-$big['ftotal'];
		 		$top=$big['ftotal']+$higher;
			}
		}
		return $top;
	}

	function leftValues($month= null,$year){
		$list=array();
		$height=1050;	
		$i=0;
		for($month=1;$month<=12;$month++){
			$sql= "SELECT SUM(e.ppe_amount)AS ftotal
					FROM payroll_paystub_entry e 
					INNER JOIN payroll_paystub_report AS r ON e.paystub_id=r.paystub_id
					INNER JOIN payroll_pay_period AS p ON r.payperiod_id=p.payperiod_id
					WHERE psa_id=5 AND (p.payperiod_period='".$month."' AND p.payperiod_period_year='".$year."')";
			$r = $this->conn->Execute($sql);
			while(!$r->EOF){	
				$i++;
				$arr[$month]=$r->fields;
				$r->MoveNext();
			}
		}
		$big=max($arr);
		$default=$height/$big['ftotal'];
		while($temp<$big['ftotal']){	
			$temp=$temp+500000;
			if($temp>$big['ftotal']){	
				$higher=$temp-$big['ftotal'];
		 		$top=$big['ftotal']+$higher;
			}
		}
		$get=$top/500000;
		for($i=1;$i<=$get;$i++){
			$num=$i*500000;
			$list[$i]=$num;
			rsort($list);
		}
		return $list;
	}
		  
	function leftValuesH($barLeftAmount = array(),$top = null){
		$default = 1147;
		for($i=1;$i<count($barLeftAmount);$i++){ 
			$pct = $barLeftAmount[$i]/$top;
			$height = $default*$pct;
			$actualHeight = 1330.5-$height;
			$f[$i] = $actualHeight;
		}
		return $f;
	}
		  
	function getBarHeight($values = array(), $top = null){
		$default = 1147;
		foreach($values as $key => $val){
			$pct = $val['ftotal']/$top;
			$actualHeight = $default*$pct;
			$height[$key] = $actualHeight;
		}
		return $height;
	}
}
?>