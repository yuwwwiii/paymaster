<?php
/**
 * Initial Declaration
 */
$AppStat = array(
	 '0' => 'New'
	,'1' => 'For Hiring Pool'
	,'2' => 'For Applicant Database'
);

/**
 * Class Module
 *
 * @author  JIM
 *
 */
class clsApplication_Form{

	var $conn;
	var $fieldMap;
	var $Data;
	var $AppStat;

	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsApplication_Form object
	 */
	function clsApplication_Form($dbconn_ = null){
		$this->conn =& $dbconn_;
		$this->fieldMap = array(
		 "emrpi_fname" => "emrpi_fname"
		,"emrpi_mname" => "emrpi_mname"
		,"emrpi_lname" => "emrpi_lname"
		,"emrpi_gender" => "emrpi_gender"
		,"emrpi_bdate" => "emrpi_bdate"
		,"emrpi_place_bdate" => "emrpi_place_bdate"
		,"emrpi_nationality" => "emrpi_nationality"
		,"emrpi_religion" => "emrpi_religion"
		,"emrpi_race" => "emrpi_race"
		,"emrpi_bloodtype" => "emrpi_bloodtype"
		,"emrpi_height" => "emrpi_height"
		,"emrpi_weight" => "emrpi_weight"
		,"emrpi_add" => "emrpi_add"
		,"emrpi_telone" => "emrpi_telone"
		,"emrpi_teltwo" => "emrpi_teltwo"
		,"emrpi_mobileone" => "emrpi_mobileone"
		,"emrpi_mobiletwo" => "emrpi_mobiletwo"
		,"emrpi_emailone" => "emrpi_emailone"
		,"emrpi_emailtwo" => "emrpi_emailtwo"
		,"emrpi_tin" => "emrpi_tin"
		,"emrpi_sss" => "emrpi_sss"
		,"emrpi_phic" => "emrpi_phic"
		,"emrpi_hdmf" => "emrpi_hdmf"
		,"emrpi_nhmfc" => "emrpi_nhmfc"
		,"p_id" => "p_id"
		,"zipcode_id" => "zipcode_id"
		,"post_id" => "post_id" 
		,"emrpi_passport" => "emrpi_passport"
		,"emrpi_civil" => "emrpi_civil"
		,"emrpi_appdate" => "emrpi_appdate"
		,"emrpi_picture" => "emrpi_picture"
		);
		
		$this->AppStat = array(
		 '1' => 'For Hire'
		,'2' => 'For Applicant Database'
		,'3' => 'For Black List Database'
		);
	}

	/**
	 * Get the records from the database
	 *
	 * @param string $id_
	 * @return array
	 */
	function dbFetch($id_ = ""){
		$sql = "SELECT emrpinfo.*, post.post_name, zcode.zipcode, city.province_name, reg_.region_name, coun_.cou_description
				FROM emr_personal_info emrpinfo
				LEFT JOIN emp_position post on (post.post_id=emrpinfo.post_id)
				LEFT JOIN app_province city on (city.p_id=emrpinfo.p_id)
				LEFT JOIN app_region reg_ on (reg_.r_id=city.r_id)
				LEFT JOIN app_country coun_ on (coun_.cou_id=reg_.cou_id)
				LEFT JOIN app_zipcodes zcode on (zcode.zipcode_id=emrpinfo.zipcode_id)
				WHERE emrpi_id=?";
		$rsResult = $this->conn->Execute($sql,array($id_));
		if(!$rsResult->EOF){
			$rsResult->fields['emrpi_sss'] = explode('-',$rsResult->fields['emrpi_sss']);
			$rsResult->fields['emrpi_tin'] = explode('-',$rsResult->fields['emrpi_tin']);
			$rsResult->fields['emrpi_phic'] = explode('-',$rsResult->fields['emrpi_phic']);
			$rsResult->fields['emrpi_hdmf'] = explode('-',$rsResult->fields['emrpi_hdmf']);
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
				if ($key=='emrpi_picture') {
					$this->Data[$key] = ($_FILES[$key]);
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

		if (empty($pData_['emrpi_fname'])) {
			$isValid = false;
			$_SESSION['eMsg'][] = "Please enter First Name.";
		}
		
		if (empty($pData_['emrpi_mname'])) {
			$isValid = false;
			$_SESSION['eMsg'][] = "Please enter Middle Name.";
		}
		
		if (empty($pData_['emrpi_lname'])) {
			$isValid = false;
			$_SESSION['eMsg'][] = "Please enter Last Name.";
		}

		return $isValid;
	}

	/**
	 * Save New
	 * @note: this is used to save the emr_personal_info
	 */
	function doSaveAdd(){
		$flds = array();
		$gov = array(
			'emrpi_sss'=>implode('-',$_POST['sss']),
			'emrpi_phic'=>implode('-',$_POST['phic']),
			'emrpi_hdmf'=>implode('-',$_POST['hdmf']),
			'emrpi_tin'=>implode('-',$_POST['tin'])
		);
		
		foreach ($this->Data as $keyData => $valData) {
			$valData = $valData;
			if($keyData=="emrpi_picture"){
				if (empty($valData['error'])) {	
					$valData = (file_get_contents($this->Data['emrpi_picture']['tmp_name']));
				}else{
					break;
				}
			}
			if(in_array($keyData,array_keys($gov))){
				$flds[] = $keyData."='".$gov[$keyData]."'";
			}else{
				$valData = addslashes($valData);
				$flds[] = "$keyData='$valData'";
			}
		}
		$flds[]="emrpi_addwho='".AppUser::getData('user_name')."'";
		$fields = implode(", ",$flds);
		
		$sql = "insert into emr_personal_info set $fields";
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
		$gov = array(
			'emrpi_sss'=>implode('-',$_POST['sss']),
			'emrpi_phic'=>implode('-',$_POST['phic']),
			'emrpi_hdmf'=>implode('-',$_POST['hdmf']),
			'emrpi_tin'=>implode('-',$_POST['tin'])
		);
		foreach ($this->Data as $keyData => $valData) {
			$valData = $valData;
			if($keyData=="emrpi_picture"){
				if (empty($valData['error'])) {	
					$valData = (file_get_contents($this->Data['emrpi_picture']['tmp_name']));
				}else{
					break;
				}
			}
			if(in_array($keyData,array_keys($gov))){
				$flds[] = $keyData."='".$gov[$keyData]."'";
			}else{					
				$valData = addslashes($valData);
				$flds[] = "$keyData='$valData'";
			}
		}
		$flds[]="emrpi_updatewho='".AppUser::getData('user_name')."'";
		$flds[]="emrpi_updatewhen='".date('Y-m-d H:i:s')."'";
		$fields = implode(", ",$flds);

		$sql = "update emr_personal_info set $fields where emrpi_id=$id";
		$this->conn->Execute($sql);
		$_SESSION['eMsg']="Successfully Updated.";
	}

	/**
	 * Delete Record
	 *
	 * @param string $id_
	 */
	function doDelete($id_ = ""){
		$sql = "delete from emr_personal_info where emrpi_id=?";
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
				$qry[] = "emrpi_lname like '%$search_field%' || emrpi_fname like '%$search_field%' || emrpi_appdate like '%$search_field%'";

			}
		}
		if($_GET['statpos']=='applicant_database'){
			$qry[] = "am.emrpi_status in (2,3)";
			$qry[] = "am.emrpi_ishired = 0";
			$action = "Action";
		}else{
			$qry[] = "am.emrpi_status not in (2,3)";
			$qry[] = "am.emrpi_ishired = 0";
			$action = "<a href=\"?statpos=application_form&action=add\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/add.png\" title=\"Add New\" border=0 width=\"16\" height=\"16\"></a>";
		}
		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		 "viewdata"=>"viewdata"
		,"emrpi_lname"=>"emrpi_lname"
		,"emrpi_fname"=>"emrpi_fname"
		,"emrpi_mname"=>"emrpi_mname"
		,"post_name"=>"post_name"
		,"emrpi_appdate"=>"emrpi_appdate"
		,"emrpi_status"=>"emrpi_status"
		);

		if(isset($_GET['sortby'])){
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}
		
		//@note: this is used to count and check all the checkbox.
		//@note set t1 = 0
		$sql = "set @t1:=0";
		$this->conn->Execute($sql);	
		
		//get total number of records and pass it to the javascript function CheckAll
			$sql_ = "select count(*) as mycount_
						from emr_personal_info am 
					$criteria";
			$rsResult = $this->conn->Execute($sql_);
			if(!$rsResult->EOF){
				$mycount2 = $rsResult->fields['mycount_'];
			}

		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$viewLink = "";
		$editLink = "<a href=\"?statpos=application_form&edit=',am.emrpi_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/edit.png\" title=\"Edit\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
		$delLink = "<a href=\"?statpos=application_form&delete=',am.emrpi_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\"  border=0 width=\"16\" height=\"16\"></a>";
		$editTD = "onclick=\"location.href='transaction.php?statpos=application_form&edit=',am.emrpi_id,'\"";
		
		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$ctr=0;		
		$chkAttend = "<input type=\"checkbox\" name=\"chkAttend[]\" id=\"chkAttend[',@t1:=@t1+1,']\" value=\"',am.emrpi_id,'\" onclick=\"javascript:UncheckAll(".$mycount2.");\">";
		
		// SqlAll Query
		$sql = "select am.*, CONCAT('$viewLink','$editLink','$delLink') as viewdata,CONCAT('$chkAttend') as chkbox, post.post_name, IF(emrpi_status=0,'New Applicant',IF(emrpi_status=1,'For Hire',IF(emrpi_status=2,'Applicant Database','Black listed'))) as emrpi_status
						from emr_personal_info am
						Left join emp_position post on (post.post_id=am.post_id)
						$criteria
						$strOrderBy";
		
		// Field and Table Header Mapping
		$arrFields = array(
		 "chkbox" => "<input type=\"checkbox\" name=\"chkAttendAll\" id=\"chkAttendAll\" onclick=\"javascript:CheckAll(".$mycount2.");\">"
		,"viewdata"=>$action
		,"emrpi_lname"=>"Last Name"
		,"emrpi_fname"=>"First Name"
		,"emrpi_mname"=>"Middle Name"
		,"post_name"=>"Position"
		,"emrpi_appdate"=>"Application Date"
		,"emrpi_status"=>"Status"
		);

		// Column (table data) User Defined Attributes
		$arrAttribs = array(
		 "viewdata"=>"width='40' align='center'"
		,"chkbox"=>"width='10' align='center'"
	  /*,"emrpi_lname"=>"onclick=\"location.href='transaction.php?statpos=application_form&edit='\"'.emrpi_id.'"
		,"emrpi_fname"=>"onclick=\"location.href='transaction.php?statpos=application_form&edit='\"'.emrpi_id.'"
		,"emrpi_mname"=>"onclick=\"location.href='transaction.php?statpos=application_form&edit='\"'.emrpi_id.'"
		,"emrpi_name"=>"onclick=\"location.href='transaction.php?statpos=application_form&edit='\"'.emrpi_id.'"
		,"emrpi_appdate"=>"onclick=\"location.href='transaction.php?statpos=application_form&edit='\"'.emrpi_id.'"*/
		);

		// Process the Table List
		$tblDisplayList = new clsTableList($this->conn);
		$tblDisplayList->arrFields = $arrFields;
		$tblDisplayList->paginator->linkPage = "?$queryStr";
		$tblDisplayList->sqlAll = $sql;
		$tblDisplayList->sqlCount = $sqlcount;
		$tblDisplayList->tblBlock->templateFile = "table_application.tpl.php";
		$tblDisplayList->tblBlock->assign("noPaginatorStart","<!--");
		$tblDisplayList->tblBlock->assign("noPaginatorEnd","-->");
		$tblDisplayList->tblBlock->assign("AppStat",$this->AppStat);

		return $tblDisplayList->getTableList($arrAttribs);
	}
	
	/**
	 * @added by: jmabignay (2009.03.12)
	 * @note : update status of employee
	 *
	 * @param unknown_type $pData
	 */
	function doUpdateStatus_($pData){
//		printa($pData);
		$id = $_GET['view_emp'];
		
		$flds = array();
		$ctr=0;
		do{
			$flds[] = "emrpi_status='".$_POST['emrpi_status']."'";
			$fields = implode(", ",$flds);
			
			$sql = "update emr_personal_info set $fields where emrpi_id = '".$pData['chkAttend'][$ctr]."'";
			$this->conn->Execute($sql);
			
			$flds = "";
			$fields = "";
			$ctr++;
			
		} while($ctr < sizeof($pData['chkAttend']));
			
		$_SESSION['eMsg']="Successfully Updated Status.";
	}
}
?>