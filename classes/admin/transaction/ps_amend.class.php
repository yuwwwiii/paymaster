<?php
/**
 * Initial Declaration
 */
$psar_status = array(
	1 => 'Active'
	,0 => 'Disabled'
);

$psar_istaxable = array(
	1 => 'True'
	,0 => 'False'
);

/**
 * Class Module
 *
 * @author  Jason I. Mabignay
 *
 */
class clsPS_Amend {

	var $conn;
	var $fieldMap;
	var $Data;

	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsPS_Amend object
	 */
	function clsPS_Amend ($dbconn_ = null) {
		$this->conn =& $dbconn_;
		$this->fieldMap = array(
		 "pps_id" => "pps_id"
		,"psa_id" => "psaid"
		,"psamend_effect_date" => "psamend_effect_date"
		,"psamend_rate" => "psamend_rate"
		,"psamend_units" => "psamend_units"
		,"psamend_amount" => "psamend_amount"
		,"psamend_desc" => "psamend_desc"
		,"psamend_authorized" => "psamend_authorized"
		,"psamend_recurring_psamend_id" => "psamend_recurring_psamend_id"
		,"psamend_ytd_adj" => "psamend_ytd_adj"
		,"psamend_type_id" => "psamend_type_id"
		,"psamend_percent_amount" => "psamend_percent_amount"
		,"psamend_percent_of" => "psamend_percent_of"
		,"emp_id" => "emp_id"
		,"psamend_istaxable" => "psamend_istaxable"
		,"psamend_name" => "psamend_name"
		,"payperiod_id" => "payperiod_id"
		);
	}

	/**
	 * Get the records from the database
	 *
	 * @param string $id_
	 * @return array
	 */
	function dbFetch ($id_ = "") {
		$sql = "SELECT psamend.*, psamend.psa_id as psaid
					FROM payroll_ps_amendment psamend 
					LEFT JOIN payroll_ps_account psa on (psa.psa_id=psamend.psa_id)  
					WHERE psamend.psamend_id=?";
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
	function doPopulateData ($pData_ = array(),$isForm_ = false) {
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
		
		if (empty($pData_['psamend_name'])) {
			$isValid = false;
			$_SESSION['eMsg'][] = "Please enter Amendment Name.";
		}
		if (empty($pData_['psaid'])) {
			$isValid = false;
			$_SESSION['eMsg'][] = "Please select Pay Slip Account.";
		}
		if ( (empty($pData_['psamend_effect_date'])) ) {
			$isValid = false;
			$_SESSION['eMsg'][] = "Please enter Effective Date.";
		} else {
			if ( ($pData_['psamend_effect_date'] == '0000-00-00') || (strlen($pData_['psamend_effect_date']) != 10) ) {
				$isValid = false;
				$_SESSION['eMsg'][] = "Please enter a valid Effective Date.";
			}
		}
		if (empty($pData_['payperiod_id'])) {
			$isValid = false;
			$_SESSION['eMsg'][] = "Please select Pay period.";
		}
		return $isValid;
	}

	/**
	 * Save New
	 *
	 */
	function doSaveAdd () {
		$flds = array();
		$pps_id_ = $_SESSION[admin_session_obj][user_paygroup_list][0];
		foreach ($this->Data as $keyData => $valData) {
			IF($keyData=='pps_id'){
				$valData = "$pps_id_";
			}
			$valData = trim(addslashes($valData));
			$flds[] = "$keyData='$valData'";
		}
		$flds[] = "psamend_addwho = '".AppUser::getData('user_name')."'";
		$fields = implode(", ",$flds);
		$sql = "INSERT INTO payroll_ps_amendment SET $fields";
		$this->conn->Execute($sql);
		$amend_id = $this->conn->Insert_ID();
		return $amend_id;
		$_SESSION['eMsg']="Successfully Added.";
	}
	
	/**
	 * Save Update
	 */
	function doSaveEdit () {
		$id = $_GET['edit'];
		$flds = array();
		foreach ($this->Data as $keyData => $valData) {
			$valData = trim(addslashes($valData));
			$flds[] = "$keyData='$valData'";
		}
		$flds[] = "psamend_updatewho = '".AppUser::getData('user_name')."'";
		$flds[] = "psamend_updatewhen = '".date('Y-m-d h:i:s')."'";
		$fields = implode(", ",$flds);

		$sql = "UPDATE payroll_ps_amendment SET $fields WHERE psamend_id=$id";
		$this->conn->Execute($sql);
		$_SESSION['eMsg']="Successfully Updated.";
	}
	
	/**
	 * Save Update
	 */
	function doAmendAmount ($pdata = array()) {
		foreach ($pdata['amend'] as $value => $amend){
			$sql = "update payroll_ps_amendemp set amendemp_amount = '".$amend."' where amendemp_id = '".$value."'";
			$this->conn->Execute($sql);
			$_SESSION['eMsg']="Successfully Updated.";
		}
	}

	/**
	 * Delete Record
	 * @param string $id_
	 */
	function doDelete ($id_ = "") {
		$sqlAmendD = "SELECT * FROM payroll_ps_amendemp WHERE psamend_id='".$id_."'";
		$rsResult = $this->conn->Execute($sqlAmendD);
		if(count($rsResult)>0){
			foreach ($rsResult as $keypsentry => $valpsentry){//delete payroll_ps_amendemp record.
				$sqldel_AmendD = "DELETE FROM payroll_ps_amendemp WHERE amendemp_id='".$rsResult->fields['amendemp_id']."'";
				$this->conn->Execute($sqldel_AmendD);
			}
		}
		$sql = "DELETE FROM payroll_ps_amendment WHERE psamend_id=?";
		$this->conn->Execute($sql,array($id_));
		$_SESSION['eMsg']="Successfully Deleted.";
	}
	
	/**
	 * Delete Record
	 * @param string $id_
	 */
	function doDelete_Emp ($id_ = "") {
		$sql = "delete from payroll_ps_amendemp where amendemp_id=?";
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
				$qry[] = "(psamend_name like '%$search_field%' || psamend_status like '%$search_field%' || psamend_effect_date like '%$search_field%')";
			}
		}
		//-------------------------------------------------------------------------->>
		//@note: to determine the pay period.
		//adjust by: jmabignay 2009.02.24
		if(isset($_GET['view'])){
			$result = $this->conn->Execute("Select date_format(payperiod_start_date,'%Y-%m-%d') as payperiod_start_date ,date_format(payperiod_end_date,'%Y-%m-%d') as payperiod_end_date from payroll_pay_period 
											where payperiod_id = '".$_GET['view']."'");
		    if(!$result->EOF){
			$result->fields;
			}
		$qry[]="am.psamend_effect_date between '".$result->fields['payperiod_start_date']."' and '".$result->fields['payperiod_end_date']."'";		
		}
		//--------------------------------------------------------------------------<<
		$listpgroup = $_SESSION[admin_session_obj][user_paygroup_list2];
		IF(count($listpgroup)>0){
			$qry[] = "am.pps_id in (".$listpgroup.")";//pay group that can access
		}
		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		 "viewdata"=>"viewdata"
		,"psamend_name" => "psamend_name"
		,"psamend_status" => "psamend_status"
		,"psatype" => "psatype"
		,"payperiod_name" => "payperiod_name"
		,"psamend_desc" => "psamend_desc"
		,"psamend_effect_date" => "psamend_effect_date"
		);

		if(isset($_GET['sortby'])){
			$strOrderBy = " ORDER BY ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}else{
			$strOrderBy = " ORDER BY am.psamend_effect_date desc";
		}

		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$viewLink = "";
		$editLink = "<a href=\"?statpos=ps_amend&edit=',am.psamend_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/edit.png\" title=\"Edit\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
		$delLink = "<a href=\"?statpos=ps_amend&delete=',am.psamend_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\"  border=0 width=\"16\" height=\"16\"></a>";

		// SqlAll Query
		$sql = "SELECT am.*, pp.payperiod_name, IF(psa.psa_type=1,CONCAT('Earning - ',psa.psa_name),IF(psa.psa_type=2,CONCAT('EE Ded - ',psa.psa_name),IF(psa.psa_type=2,CONCAT('ER Ded - ',psa.psa_name),'wrong entry'))) as psatype,
				IF(am.psamend_type_id=2,CONCAT(am.psamend_percent_amount,'%'),am.psamend_amount) as amount, 
				IF(am.psamend_status=1,'Active','Paid') as psamend_status,
				CONCAT('$viewLink','$editLink','$delLink') as viewdata
						FROM payroll_ps_amendment am
						LEFT JOIN payroll_pay_period_sched pps on (pps.pps_id=am.pps_id)
						LEFT JOIN payroll_ps_account psa on (psa.psa_id=am.psa_id)
						LEFT JOIN payroll_pay_period pp on (pp.payperiod_id=am.payperiod_id)
						$criteria
						$strOrderBy";

		// Field and Table Header Mapping
		$arrFields = array(
		 "viewdata"=>"<a href=\"?statpos=ps_amend&action=add\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/add.png\" title=\"Add New\" border=0 width=\"16\" height=\"16\"></a>"
		,"psamend_name" => "Name"
		,"psamend_status" => "Status"
		,"psatype" => "Account"
		,"payperiod_name" => "Pay Period"
		,"psamend_desc" => "Description"
		,"psamend_effect_date" => "Effective Date"
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
		$tblDisplayList->tblBlock->assign("title","Payroll Amendments");
		return $tblDisplayList->getTableList($arrAttribs);
	}
	
	/**
	 * Get all the Table Listings
	 *
	 * @return array
	 */
	function getTableList_Emp(){
//		$this->conn->debug=0;
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
			//@note for department
			if (($_POST['post_name'] != '') && ($_POST['post_name'] != '*')) {
				$qry[] = "post.post_name like '" . $_POST['post_name'] . "%' ";
			}
			//@note for product line category
			if (($_POST['emp_no'] != '') && ($_POST['emp_no'] != '*')) {
				//$qry[] = "MATCH(am.si_prodlinecat) AGAINST('" . $_POST['si_prodlinecat'] . "') ";
				$qry[] = "(empinfo.emp_idnum LIKE '%".$_POST['emp_no']."%' || pinfo.pi_fname LIKE '%".$_POST['emp_no']."%' || pinfo.pi_lname LIKE '%".$_POST['emp_no']."%') ";
			}

		}
		if ($_GET['statpos']=='ps_amend_'){
			$qry[]="empinfo.emp_id in (select a.emp_id from payroll_ps_amendemp a where psamend_id = '".$_GET['edit']."')";
		}else{
			$qry[]="empinfo.emp_id not in (select a.emp_id from payroll_ps_amendemp a where psamend_id = '".$_GET['edit']."')";
		}
		$listcomp = $_SESSION[admin_session_obj][user_comp_list2];
		$listloc = $_SESSION[admin_session_obj][user_branch_list2];
		$listpgroup = $_SESSION[admin_session_obj][user_paygroup_list2];
		IF(count($listcomp)>0){
			$qry[] = "empinfo.comp_id in (".$listcomp.")";//company that can access
		}
		IF(count($listloc)>0){
			$qry[] = "empinfo.branchinfo_id in (".$listloc.")";//location that can access
		}
		IF(count($listpgroup)>0){
			$qry[] = "ppuser.pps_id in (".$listpgroup.")";//pay group that can access
		}
        $qry[] = "empinfo.emp_stat in ('1','7','4','10')";
		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" WHERE ".implode(" AND ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		 "chkbox" => "chkbox"
		,"emp_idnum" => "emp_idnum"
		,"pi_lname" => "pi_lname"
		,"pi_fname" => "pi_fname"
		,"post_name" => "post_name"
		,"comp_name"=>"comp_name"
		,"branchinfo_name"=>"branchinfo_name"
		,"ud_name" => "ud_name"
		);

		if(isset($_GET['sortby'])){
			$strOrderBy = " ORDER BY ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}else{
			$strOrderBy = " ORDER BY pinfo.pi_lname";
		}

		//@note: this is used to count and check all the checkbox.
		//@note set t1 = 0
		$sql = "set @t1:=0";
		$this->conn->Execute($sql);

		//get total number of records and pass it to the javascript function CheckAll
			$sql_ = "SELECT count(*) as mycount_
						FROM emp_masterfile empinfo
						JOIN emp_personal_info pinfo on (pinfo.pi_id=empinfo.pi_id)
						JOIN payroll_pps_user ppuser on (ppuser.emp_id=empinfo.emp_id)
						LEFT JOIN app_userdept dept on (dept.ud_id=empinfo.ud_id)
						LEFT JOIN emp_position post on (post.post_id=empinfo.post_id)
						LEFT JOIN branch_info bran on (bran.branchinfo_id=empinfo.branchinfo_id)
						LEFT JOIN company_info comp on (comp.comp_id=empinfo.comp_id)
					$criteria
					$strOrderBy";
			$rsResult = $this->conn->Execute($sql_);
			if(!$rsResult->EOF){
				$mycount = $rsResult->fields['mycount_'];
			}

		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$ctr=0;
		$chkAttend = "<input type=\"checkbox\" name=\"chkAttend[]\" id=\"chkAttend[',@t1:=@t1+1,']\" value=\"',empinfo.emp_id,'\" onclick=\"javascript:UncheckAll(".$mycount.");\">";

		// SqlAll Query
		$sql = "SELECT pinfo.pi_lname,pinfo.pi_fname, dept.ud_name,  post.post_name, bran.branchinfo_name, comp.comp_name, CONCAT('$chkAttend') as chkbox, empinfo.emp_idnum 
					FROM emp_masterfile empinfo
					JOIN emp_personal_info pinfo on (pinfo.pi_id=empinfo.pi_id)
					JOIN payroll_pps_user ppuser on (ppuser.emp_id=empinfo.emp_id)
					LEFT JOIN app_userdept dept on (dept.ud_id=empinfo.ud_id)
					LEFT JOIN emp_position post on (post.post_id=empinfo.post_id)
					LEFT JOIN branch_info bran on (bran.branchinfo_id=empinfo.branchinfo_id)
					LEFT JOIN company_info comp on (comp.comp_id=empinfo.comp_id)
					$criteria
					$strOrderBy";

		// Field and Table Header Mapping
		$arrFields = array(
		 "chkbox" => "<input type=\"checkbox\" name=\"chkAttendAll\" id=\"chkAttendAll\" onclick=\"javascript:CheckAll(".$mycount.");\">"
		,"emp_idnum" => "Employee No."
		,"pi_lname" => "Last Name"
		,"pi_fname" => "First Name"
		,"post_name" => "Position"
		,"comp_name"=>"Company"
		,"branchinfo_name"=>"Location"
		,"ud_name" => "Department"
		);

		// Column (table data) User Defined Attributes
		$arrAttribs = array(
		 "mnu_ord"=>" align='right'"
		,"viewdata"=>"width='30' align='center'"
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
	
	/**
	 * Get all the Table Listings
	 *
	 * @return array
	 */
	function getTableList_EmpSave(){
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
				$qry[] = "(pinfo.pi_fname like '%$search_field%' or pinfo.pi_lname like '%$search_field%' or dept.ud_name like '%$search_field%')";

			}
		}
		$qry[]="psar.psamend_id = '".$_GET['edit']."'";
       // $qry[]  = "empinfo.emp_stat in ('1','7','4','10')";
		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" WHERE ".implode(" AND ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		 "viewdata"=>"viewdata"
		,"emp_idnum" => "emp_idnum"
		,"pi_lname" => "pi_lname"
		,"pi_fname" => "pi_fname"
		,"post_name" => "post_name"
		,"ud_name" => "ud_name"
		,"qty" => "qty"
		);

		if(isset($_GET['sortby'])){
			$strOrderBy = " ORDER BY ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}else{
			$strOrderBy = " ORDER BY pinfo.pi_lname";
		}
		
		$objClsMngeDecimal = new Application();
		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$delLink = "<a href=\"?statpos=ps_amend&edit=',psar.psamend_id,'&empinput_del=',psaru.amendemp_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\"  border=0 width=\"16\" height=\"16\"></a>";
		$qty = "<input name=\"amend[',psaru.amendemp_id,']\" style=\"text-align:right\" id=\"amend[',psaru.amendemp_id,']\" type=\"text\" value=\"',amendemp_amount,'\" />";
		
		// SqlAll Query
		$sql = "SELECT psaru.*,pinfo.pi_lname,pinfo.pi_fname, dept.ud_name, psar.*, post.post_name,
				CONCAT('$delLink') as viewdata,empinfo.emp_idnum,CONCAT('$qty') as qty
						FROM payroll_ps_amendemp psaru
						JOIN payroll_ps_amendment psar on (psar.psamend_id=psaru.psamend_id)
						JOIN emp_masterfile empinfo on (empinfo.emp_id=psaru.emp_id)
						JOIN emp_personal_info pinfo on (pinfo.pi_id=empinfo.pi_id)
						LEFT JOIN app_userdept dept on (dept.ud_id=empinfo.ud_id)
						LEFT JOIN emp_position post on (post.post_id=empinfo.post_id)
						$criteria
						$strOrderBy";

		// Field and Table Header Mapping
		$arrFields = array(
		 "viewdata"=>"Action"
		,"emp_idnum" => "Emp No."
		,"pi_lname" => "Last Name"
		,"pi_fname" => "First Name"
		,"post_name" => "Position"
		,"ud_name" => "Department"
		,"qty" => "Amount"
		);

		// Column (table data) User Defined Attributes
		$arrAttribs = array(
		"mnu_ord"=>" align='right'",
		"qty"=>" align='right' width='50'",
		"viewdata"=>"width='30' align='center'"
		);

		// Process the Table List
		$tblDisplayList = new clsTableList($this->conn);
		$tblDisplayList->arrFields = $arrFields;
		$tblDisplayList->paginator->linkPage = "?$queryStr";
		$tblDisplayList->sqlAll = $sql;
		$tblDisplayList->sqlCount = $sqlcount;
		$tblDisplayList->tblBlock->templateFile = "table_nosort.tpl.php";
		$tblDisplayList->tblBlock->assign("noSearchStart","<!--");
		$tblDisplayList->tblBlock->assign("noSearchEnd","-->");	
		return $tblDisplayList->getTableList($arrAttribs);
	}
	
	/**
	 * Get Pay Stud Account
	 * @return Pay Stud Account List array
	 */
	function getPSAccnt() {
		$objData = $this->conn->Execute("SELECT psa_id, IF(psa_type=1,CONCAT('Earning - ',psa_name),IF(psa_type=2,CONCAT('EE Ded - ',psa_name),IF(psa_type=3,CONCAT('ER Ded - ',psa_name),'wrong entry'))) as psatype
										 FROM payroll_ps_account 
										 WHERE psa_type in ('1','2','3') AND psa_status != '2'
										 ORDER BY psa_order");
		$cResult = array();
		while ( !$objData->EOF ) {       	
			$cResult[] = $objData->fields;
        	$objData->MoveNext();
        }
        return $cResult;
	}
	
	/**
	 * Get Pay Stud Account All
 	 * @return all PSAccnt List array
	 */
	function getPSAccntALL() {
		$objData = $this->conn->Execute("SELECT psa_id, IF(psa_type=1,CONCAT('Earning - ',psa_name),IF(psa_type=2,CONCAT('EE Ded - ',psa_name),IF(psa_type=3,CONCAT('ER Ded - ',psa_name),IF(psa_type=4,CONCAT('Total - ',psa_name),CONCAT('Accrual - ',psa_name))))) as psatype
										 FROM payroll_ps_account 
										 ORDER BY psa_id");
		$cResult = array();
		while ( !$objData->EOF ) {       	
//			$cResult[$objData->fields['psa_id']] = $objData->fields['psatype'];
			$cResult[] = $objData->fields;
        	$objData->MoveNext();
        }
        return $cResult;
	}	
	
	/**
	 * Save Employee
	 *
	 */
	function doSaveEmployee($pData){
		$flds = array();
		$ctr=0;
		do{
			$flds[] = "emp_id='".$pData['chkAttend'][$ctr]."'";
			$flds[] = "psamend_id='".$_GET['edit']."'";
			$flds[] = "amendemp_amount='0'";
			$flds[] = "amendemp_addwho='".$_SESSION['admin_session_obj']['user_data']['user_name']."'";
			$fields = implode(", ",$flds);
			$sql = "INSERT INTO payroll_ps_amendemp set $fields";
			$this->conn->Execute($sql);
			$flds = "";
			$fields = "";
			$ctr++;
		} while($ctr < sizeof($pData['chkAttend']));
		$_SESSION['eMsg']="Successfully Updated.";
	}
}

?>