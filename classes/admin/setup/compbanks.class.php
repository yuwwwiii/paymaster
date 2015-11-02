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
 * @author  Jason I. Mabignay
 *
 */
class clsCompBanks{

	var $conn;
	var $fieldMap;
	var $Data;

	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsCompBanks object
	 */
	function clsCompBanks ($dbconn_ = null) {
		$this->conn =& $dbconn_;
		$this->fieldMap = array(
		 "bank_acct_no" => "bank_acct_no"
		,"bank_acct_name" => "bank_acct_name"
		,"bank_swift_code" => "bank_swift_code"
		,"bank_branch" => "bank_branch"
		,"bank_isactive" => "bank_isactive"
		,"banklist_id" => "banklist_id"
		,"baccntype_id" => "baccntype_id"
		,"bank_routing_number" => "bank_routing_number"
		,"bank_company_code" => "bank_company_code"
		,"bank_ceiling_amount" => "bank_ceiling_amount"
		,"bank_contact"=>"bank_contact"
		,"bank_building"=>"bank_building"
		,"bank_address"=>"bank_address"
		);
	}

	/**
	 * Get the records from the database
	 *
	 * @param string $id_
	 * @return array
	 */
	function dbFetch($id_ = "") {
		$sql = "Select a.*, b.banklist_id, b.banklist_name, c.baccntype_name
					from bank_info a 
					left join bank_list b on (b.banklist_id = a.banklist_id)
					left join bnkaccnt_type c on (a.baccntype_id = c.baccntype_id)
					where a.bank_id=?";
		$rsResult = $this->conn->Execute($sql,array($id_));
		if (!$rsResult->EOF) {
			return $rsResult->fields;
		}
	}	
	
	/**
	 * Get the records from the database
	 *
	 * @param string $id_
	 * @return array
	 */
	function dbFetch_Company ($id_ = "") {
		$sql = "Select a.* from company_info a where a.comp_id=?";
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
		if (empty($pData_['banklist_name'])) {
			$isValid = false;
			$_SESSION['eMsg'][] = "Please select Bank.";
		}
		if (!is_numeric($pData_['bank_routing_number']) && !empty($pData_['bank_routing_number'])) {
			$isValid = false;
			$_SESSION['eMsg'][] = "Please enter a valid Presenting Office Code.";
		}
		if (empty($pData_['bank_acct_no'])) {
			$isValid = false;
			$_SESSION['eMsg'][] = "Please enter Account number.";
		}
		if (!is_numeric($pData_['bank_acct_no']) && !empty($pData_['bank_acct_no'])) {
			$isValid = false;
			$_SESSION['eMsg'][] = "Please enter a valid Account number.";
		}
		if (empty($pData_['bank_acct_name'])) {
			$isValid = false;
			$_SESSION['eMsg'][] = "Please enter Account Name.";
		}
		if (!is_numeric($pData_['bank_ceiling_amount']) && !empty($pData_['bank_ceiling_amount'])) {
			$isValid = false;
			$_SESSION['eMsg'][] = "Please enter a valid Default Ceiling Amount.";
		}
		if (empty($pData_['bank_branch'])) {
			$isValid = false;
			$_SESSION['eMsg'][] = "Please enter Branch.";
		}
		return $isValid;
	}
	
	function doValidateData_emp($pData_ = array()) {
		$isValid = true;
		if (empty($pData_['chkAttend'])) {
			$isValid = false;
			$_SESSION['eMsg'][] = "Please Select Employee first.";
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
		$flds[]="comp_id='".$_GET['view']."'";
		$flds[]="bank_addwho='".AppUser::getData('user_name')."'";
		$fields = implode(", ",$flds);

		$sql = "insert into bank_info set $fields";
		$this->conn->Execute($sql);

		$_SESSION['eMsg']="Successfully Added.";
	}
	
	/**
	 * Save Employee
	 *
	 */
	function doSaveEmployee($pData){
		$flds = array();
		$ctr=0;
		do{
			$flds[] = "bankiemp_id='".$pData['chkAttend'][$ctr]."'";
			$flds[] = "bank_id='".$_GET['empinput']."'";
			$flds[] = "bnkgrup_addwho='".$_SESSION['admin_session_obj']['user_data']['user_name']."'";
			$fields = implode(", ",$flds);
			$sql = "INSERT INTO bank_empgroup SET $fields";
			$this->conn->Execute($sql);
			$flds = "";
			$fields = "";
			$ctr++;
		} while($ctr < sizeof($pData['chkAttend']));
		$_SESSION['eMsg']="Total of ".$ctr." Employee, Successfully Added.";
	}

	/**
	 * Save Update
	 *
	 */
	function doSaveEdit($bank_id_ = "", $comp_id_ = ""){

		$flds = array();
		foreach ($this->Data as $keyData => $valData) {
			$valData = trim(addslashes($valData));
			$flds[] = "$keyData='$valData'";
		}
		$flds[]="comp_id='".$comp_id_."'";
		$flds[]="bank_updatewho='".AppUser::getData('user_name')."'";
		$flds[]="bank_upadtewhen='".date('Y-m-d H:i:s')."'";
		$fields = implode(", ",$flds);

		$sql = "update bank_info set $fields where bank_id=$bank_id_";
		$this->conn->Execute($sql);
		$_SESSION['eMsg']="Successfully Updated.";
	}
	
	/**
	 * Delete Record
	 *
	 * @param string $id_
	 */
	function doDelete_Emp($id_ = ""){
		$sql = "DELETE FROM bank_empgroup where bnkgrup_id=?";
		$this->conn->Execute($sql,array($id_));
		$_SESSION['eMsg']="Successfully Deleted.";
	}

	/**
	 * Delete Record
	 *
	 * @param string $id_
	 */
	function doDelete($id_ = ""){
		$sql = "delete from bank_info where bank_id=?";
		$this->conn->Execute($sql,array($id_));
		$_SESSION['eMsg']="Successfully Deleted.";
	}

	/**
	 * Get all the Table Listings
	 *
	 * @return array
	 */
	function getTableList($comp_id_="") {
		// Process the query string and exclude querystring named "p"
		if (!empty($_SERVER['QUERY_STRING'])) {
			$qrystr = explode("&", $_SERVER['QUERY_STRING']);
			foreach ($qrystr as $value) {
				$qstr = explode("=", $value);
				if ($qstr[0]!="p") {
					$arrQryStr[] = implode("=", $qstr);
				}
			}
			$aQryStr = $arrQryStr;
			$aQryStr[] = "p=@@";
			$queryStr = implode("&", $aQryStr);
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
		
		$qry[]="am.comp_id = '".$comp_id_."'";
		
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
		$empLink = "<a href=\"?statpos=compbanks&view=',am.comp_id,'&empinput=',am.bank_id,'&bank=',bank.banklist_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/useradd.png\" title=\"Select Employee\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
		$editLink = "<a href=\"?statpos=compbanks&edit=',am.bank_id,'&view_=',am.comp_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/edit.png\" title=\"Edit\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
		$delLink = "<a href=\"?statpos=compbanks&delete=',am.bank_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\"  border=0 width=\"16\" height=\"16\"></a>";

		// SqlAll Query
		$sql = "SELECT am.*, CONCAT('$empLink','$editLink') as viewdata, 
				bank.banklist_name, IF(am.bank_isactive = '1','Active','Inactive') as bank_isactive
						FROM bank_info am
						JOIN bank_list bank on (bank.banklist_id=am.banklist_id)
						$criteria
						$strOrderBy";

		// Sql query for paginator list
		// @note no need to use this. it replaced by sql function "FOUND_ROWS()"
		//$sqlcount = "select count(*) as mycount from app_modules $criteria";

		// Field and Table Header Mapping
		$arrFields = array(
		 "viewdata"=>"Action"
		,"banklist_name" => "Bank Name"
		,"bank_acct_no" => "Account No."
		,"bank_acct_name" => "Account Name"
		,"bank_branch" => "Branch"
		,"bank_isactive" => "Status"
		);

		// Column (table data) User Defined Attributes
		$arrAttribs = array(
		"mnu_ord"=>" align='right'",
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

	/**
	 * Get all the Table Listings
	 *
	 * @return array
	 */
	function getCompany_List(){
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
				$qry[] = "(comp_code like '%$search_field%' || comp_name like '%$search_field%' || comp_add like '%$search_field%')";

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
		$viewLink = "<a href=\"?statpos=compbanks&view=',am.comp_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/search.png\" title=\"View\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
		$editLink = "<a href=\"?statpos=compbanks&edit=',am.comp_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/edit.png\" title=\"Edit\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
		$delLink = "<a href=\"?statpos=compbanks&delete=',am.comp_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\"  border=0 width=\"16\" height=\"16\"></a>";

		// SqlAll Query
		$sql = "select am.*, CONCAT('$viewLink') as viewdata
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
	
	function bnkaccntype (){//Get all Bank Account Type
		$sql = "select * from bnkaccnt_type";
		$rsResult = $this->conn->Execute($sql);
		
		while(!$rsResult->EOF){
		$baccntype_id[] = $rsResult->fields['baccntype_id'];
		$baccntype_name[] = $rsResult->fields['baccntype_name'];
		$rsResult->MoveNext();
		}
		$_SESSION['baccntype_id'] = $baccntype_id;
		return $baccntype_name;
	}
	
	/**
	 * Get all the Table Listings
	 *
	 * @return array
	 */
	function getTableList_EmpSave(){
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
				$qry[] = "(empinfo.emp_idnum like '%$search_field%' or CONCAT(pinfo.pi_lname,', ',pinfo.pi_fname) like '%$search_field%')";
			}
		}

		$qry[]="bnkcomp.bank_id = '".$_GET['empinput']."'";
        $qry[]  = "empinfo.emp_stat in ('1','7','10')";
        
		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		 "viewdata"=>"viewdata"
		,"emp_idnum" => "emp_idnum"
		,"empname" => "empname"
		,"banklist_name" => "banklist_name"
		,"bankiemp_acct_name" => "bankiemp_acct_name"
		,"bankiemp_acct_no" => "bankiemp_acct_no"
		,"baccntype_name" => "baccntype_name"
		);

		if(isset($_GET['sortby'])){
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}else{
			$strOrderBy = " order by empname";
		}

		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$delLink = "<a href=\"?statpos=compbanks&view=',bnkcomp.comp_id,'&empinput=',bnkcomp.bank_id,'&bank=',bank.banklist_id,'&empinput_del=',bnkeg.bnkgrup_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\"  border=0 width=\"16\" height=\"16\"></a>";

		// SqlAll Query
		$sql = "SELECT bnkeg.bnkgrup_id,bnkeg.bankiemp_id,bnkeg.bank_id,empinfo.emp_idnum,CONCAT(pinfo.pi_lname,', ',pinfo.pi_fname) as empname, bnkemp.bankiemp_acct_no, bnkemp.bankiemp_acct_name, bank.banklist_name, tbnk.baccntype_name, CONCAT('$delLink') as viewdata
					FROM bank_empgroup bnkeg
					JOIN bank_info bnkcomp on (bnkcomp.bank_id=bnkeg.bank_id)
					JOIN bank_infoemp bnkemp on (bnkemp.bankiemp_id=bnkeg.bankiemp_id)
					JOIN emp_masterfile empinfo on (empinfo.emp_id=bnkemp.emp_id)
					JOIN emp_personal_info pinfo on (pinfo.pi_id=empinfo.pi_id)
					JOIN bank_list bank on (bank.banklist_id=bnkemp.banklist_id)
					JOIN bnkaccnt_type tbnk on (tbnk.baccntype_id=bnkemp.baccntype_id)
					$criteria
					$strOrderBy";

		// Field and Table Header Mapping
		$arrFields = array(
		 "viewdata"=>"Action"
		,"emp_idnum" => "Emp No."
		,"empname" => "Employee Name"
		,"banklist_name" => "Bank"
		,"bankiemp_acct_name" => "Acct Name"
		,"bankiemp_acct_no" => "Acct No"
		,"baccntype_name" => "Acct Type"
		);

		// Column (table data) User Defined Attributes
		$arrAttribs = array(
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
	 * Get all the Table Listings
	 *
	 * @return array
	 */
	function getTableList_Emp($gData = array()){
//		$this->conn->debug=1;
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
		//@note query filters for Quick Search->Suppliers
		if (count($_POST) > 0) {
			//@note for department
			if (($_POST['ud_name'] != '') && ($_POST['ud_name'] != '*')) {
				$qry[] = "dept.ud_name like '" . $_POST['ud_name'] . "%' ";
			}
			//@note for product line category
			if (($_POST['emp_idnum'] != '') && ($_POST['emp_idnum'] != '*')) {
				//$qry[] = "MATCH(am.si_prodlinecat) AGAINST('" . $_POST['si_prodlinecat'] . "') ";
				$qry[] = "(empinfo.emp_idnum LIKE '%".$_POST['emp_idnum']."%' || empinfo.pi_fname LIKE '%".$_POST['emp_idnum']."%' || empinfo.pi_lname LIKE '%".$_POST['emp_idnum']."%') ";
			}
		}
		$qry[]="bnkemp.banklist_id = '".$gData['bank']."'";
		$qry[]="bnkemp.bankiemp_id not in (select a.bankiemp_id from bank_empgroup a WHERE a.bank_id='".$gData['empinput']."')";
        $qry[] = "empinfo.emp_stat in ('1','7','10')";
        
		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";
		// Sort field mapping
		$arrSortBy = array(
		 "chkbox" => "chkbox"
		,"emp_idnum" => "emp_idnum"
		,"empname" => "empname"
		,"banklist_name" => "banklist_name"
		,"bankiemp_acct_name" => "bankiemp_acct_name"
		,"bankiemp_acct_no" => "bankiemp_acct_no"
		,"baccntype_name" => "baccntype_name"
		);

		if(isset($_GET['sortby'])){
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}/*else{
			$strOrderBy = " order by empname";
		}*/

		//@note: this is used to count and check all the checkbox.
		//@note set t1 = 0
		$sql = "set @t1:=0";
		$this->conn->Execute($sql);
		//get total number of records and pass it to the javascript function CheckAll
			$sql_ = "SELECT count(*) as mycount_
						FROM bank_infoemp bnkemp
						JOIN emp_masterfile empinfo on (bnkemp.emp_id=empinfo.emp_id)
						JOIN emp_personal_info pinfo on (pinfo.pi_id=empinfo.pi_id)
						JOIN bank_list bank on (bank.banklist_id=bnkemp.banklist_id)
						JOIN bnkaccnt_type tbnk on (tbnk.baccntype_id=bnkemp.baccntype_id)
					$qrysql		
					$qrysql_		
					$criteria
					$strOrderBy";
			$rsResult = $this->conn->Execute($sql_);
			if(!$rsResult->EOF){
				$mycount = $rsResult->fields['mycount_'];
			}
		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$ctr=0;
		$chkAttend = "<input type=\"checkbox\" name=\"chkAttend[]\" id=\"chkAttend[',@t1:=@t1+1,']\" value=\"',bnkemp.bankiemp_id,'\" onclick=\"javascript:UncheckAll(".$mycount.");\">";
		
		// SqlAll Query
		$sql = "SELECT CONCAT(pinfo.pi_lname,', ',pinfo.pi_fname) as empname, bnkemp.bankiemp_acct_no, bnkemp.bankiemp_acct_name, bank.banklist_name, tbnk.baccntype_name,
				CONCAT('$chkAttend') as chkbox, empinfo.emp_idnum
						FROM bank_infoemp bnkemp
						JOIN emp_masterfile empinfo on (bnkemp.emp_id=empinfo.emp_id)
						JOIN emp_personal_info pinfo on (pinfo.pi_id=empinfo.pi_id)
						JOIN bank_list bank on (bank.banklist_id=bnkemp.banklist_id)
						JOIN bnkaccnt_type tbnk on (tbnk.baccntype_id=bnkemp.baccntype_id)
						$qrysql
						$qrysql_
						$criteria
						$strOrderBy";

		// Field and Table Header Mapping
		$arrFields = array(
		 "chkbox" => "<input type=\"checkbox\" name=\"chkAttendAll\" id=\"chkAttendAll\" onclick=\"javascript:CheckAll(".$mycount.");\">"
		,"emp_idnum" => "Emp No."
		,"empname" => "Employee Name"
		,"banklist_name" => "Bank"
		,"bankiemp_acct_name" => "Acct Name"
		,"bankiemp_acct_no" => "Acct No"
		,"baccntype_name" => "Acct Type"
		);
		// Column (table data) User Defined Attributes
		$arrAttribs = array(
		 "viewdata"=>"width='30' align='center'"
		,"chkbox"=>"width='10' align='center'"
		);
		// Process the Table List
		$tblDisplayList = new clsTableList($this->conn);
//		$tblDisplayList->tblBlock->templateFile = "table2.tpl.php";
		$tblDisplayList->arrFields = $arrFields;
		$tblDisplayList->paginator->linkPage = "?$queryStr";
		$tblDisplayList->sqlAll = $sql;
		$tblDisplayList->sqlCount = $sqlcount;
		$tblDisplayList->tblBlock->templateFile = "table_nosort.tpl.php";
		$tblDisplayList->tblBlock->assign("noSearchStart","<!--");
		$tblDisplayList->tblBlock->assign("noSearchEnd","-->");
		return $tblDisplayList->getTableList($arrAttribs);
	}
}
?>