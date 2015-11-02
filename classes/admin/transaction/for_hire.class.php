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
class clsFor_Hire{

	var $conn;
	var $fieldMap;
	var $Data;

	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsFor_Hire object
	 */
	function clsFor_Hire($dbconn_ = null){
		$this->conn =& $dbconn_;
		$this->fieldMap = array(
		 "taxep_id" => "taxep_id"
		,"ud_id" => "ud_id"
		,"comp_id" => "comp_id"
		,"post_id" => "post_id"
		,"empcateg_id" => "empcateg_id"
		,"emptype_id" => "emptype_id"
		,"pi_id" => "pi_id"
		,"emp_idnum" => "emp_idnum"
		,"emp_hiredate" => "emp_hiredate"
		,"emp_picture" => "emp_picture"
		);
	}

	/**
	 * Get the records from the database
	 *
	 * @param string $id_
	 * @return array
	 */
	function dbFetch($id_ = ""){
		$sql = "Select emrpinfo.*, post.post_name
				from emr_personal_info emrpinfo
				Left join emp_position post on (post.post_id=emrpinfo.post_id)
				where emrpi_id=?";
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
		
		$EMRpinfo = $this->getEMRpinfo($id);
		
		$flds_ = array();
		$flds_[] = "pi_fname = '".$EMRpinfo['emrpi_fname']."'";
		$flds_[] = "pi_mname = '".$EMRpinfo['emrpi_mname']."'";
		$flds_[] = "pi_lname = '".$EMRpinfo['emrpi_lname']."'";
		$flds_[] = "pi_gender = '".$EMRpinfo['emrpi_gender']."'";
		$flds_[] = "pi_bdate = '".$EMRpinfo['emrpi_bdate']."'";
		$flds_[] = "pi_place_bdate = '".$EMRpinfo['emrpi_place_bdate']."'";
		$flds_[] = "pi_nationality = '".$EMRpinfo['emrpi_nationality']."'";
		$flds_[] = "pi_religion = '".$EMRpinfo['emrpi_religion']."'";
		$flds_[] = "pi_race = '".$EMRpinfo['emrpi_race']."'";
		$flds_[] = "pi_bloodtype = '".$EMRpinfo['emrpi_bloodtype']."'";
		$flds_[] = "pi_height = '".$EMRpinfo['emrpi_height']."'";
		$flds_[] = "pi_weight = '".$EMRpinfo['emrpi_weight']."'";
		$flds_[] = "pi_add = '".$EMRpinfo['emrpi_add']."'";
		$flds_[] = "pi_telone = '".$EMRpinfo['emrpi_telone']."'";
		$flds_[] = "pi_teltwo = '".$EMRpinfo['emrpi_teltwo']."'";
		$flds_[] = "pi_mobileone = '".$EMRpinfo['emrpi_mobileone']."'";
		$flds_[] = "pi_mobiletwo = '".$EMRpinfo['emrpi_mobiletwo']."'";
		$flds_[] = "pi_emailone = '".$EMRpinfo['emrpi_emailone']."'";
		$flds_[] = "pi_emailtwo = '".$EMRpinfo['emrpi_emailtwo']."'";
		$flds_[] = "pi_tin = '".$EMRpinfo['emrpi_tin']."'";
		$flds_[] = "pi_sss = '".$EMRpinfo['emrpi_sss']."'";
		$flds_[] = "pi_phic = '".$EMRpinfo['emrpi_phic']."'";
		$flds_[] = "pi_hdmf = '".$EMRpinfo['emrpi_hdmf']."'";
		$flds_[] = "pi_nhmfc = '".$EMRpinfo['emrpi_nhmfc']."'";
		$flds_[] = "p_id = '".$EMRpinfo['p_id']."'";
		$flds_[] = "zipcode_id = '".$EMRpinfo['zipcode_id']."'";
		$flds_[] = "pi_passport = '".$EMRpinfo['emrpi_passport']."'";
		$flds_[] = "pi_civil = '".$EMRpinfo['emrpi_civil']."'";
		$flds_[] = "pi_addwho = '".AppUser::getData('user_name')."'";
		$fields_ = implode(", ",$flds_);
		
		//@notes: to save employee information in emp_personal_info table.
		$sql_pinfo = "insert into emp_personal_info set $fields_";
		$this->conn->Execute($sql_pinfo);
		
		//@notes: get last inserted ID 
		$lastinsertID_pi_id = $this->conn->Insert_ID();
		
		//@notes: to save employee details in emp_masterfile table.
		$flds = array();
		foreach ($this->Data as $keyData => $valData) {
			If($keyData=='pi_id'){
				$valData = $lastinsertID_pi_id;
			}elseif($keyData=='emp_picture'){
				$valData = $EMRpinfo['emrpi_picture'];
			}
			$valData = addslashes($valData);
			$flds[] = "$keyData='$valData'";
		}
		$flds[] = "emp_addwho = '".AppUser::getData('user_name')."'";
		$fields = implode(", ",$flds);

		$sql = "insert into emp_masterfile set $fields";
		$this->conn->Execute($sql);
		
		//@noted update status in emr_personal_info table.
		$fldsemr[] = "emrpi_ishired = '1'";
		$fldsemr[] = "emrpi_updatewho = '".AppUser::getData('user_name')."'";
		$fldsemr[] = "emrpi_updatewhen = '".date('Y-m-d h:i:s')."'";
		$fieldsemr = implode(", ",$fldsemr);
		
		$sqlemr = "update emr_personal_info set $fieldsemr where emrpi_id='".$id."'";
		$this->conn->Execute($sqlemr);
		
		$_SESSION['eMsg']="Applicant <b>".$EMRpinfo['emrpi_fname']." ".$EMRpinfo['emrpi_lname']."</b> has been hired.";
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
		
		$qry[] = "am.emrpi_status = 1";
		$qry[] = "am.emrpi_ishired = 0";
		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		 "viewdata"=>"viewdata"
		,"emrpi_lname"=>"emrpi_lname"
		,"emrpi_fname"=>"emrpi_fname"
		,"emrpi_mname"=>"emrpi_mname"
		,"post_name"=>"post_name"
		);

		if(isset($_GET['sortby'])){
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}

		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$viewLink = "";
		$editLink = "<a href=\"?statpos=for_hire&edit=',am.emrpi_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/edit.png\" title=\"Process\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
		$delLink = "<a href=\"?statpos=for_hire&delete=',am.emrpi_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\"  border=0 width=\"16\" height=\"16\"></a>";

		// SqlAll Query
		$sql = "select am.*, CONCAT('$editLink') as viewdata, post.post_name
						from emr_personal_info am
						Left join emp_position post on (post.post_id=am.post_id)
						$criteria
						$strOrderBy";

		// Field and Table Header Mapping
		$arrFields = array(
		 "viewdata"=>"Action"
		,"emrpi_lname"=>"Last Name"
		,"emrpi_fname"=>"First Name"
		,"emrpi_mname"=>"Middle Name"
		,"post_name"=>"Position"
		);

		// Column (table data) User Defined Attributes
		$arrAttribs = array(
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
	 * @note This Function is used to get Data Record in emr_personal_info table.
	 * @param primarykey = $emrpi_id_
	 */
	function getEMRpinfo($emrpi_id_ = ""){
		$sql = "Select emrpinfo.*, post.post_name, zcode.zipcode, city.province_name, reg_.region_name, coun_.cou_description
				from emr_personal_info emrpinfo
				Left join emp_position post on (post.post_id=emrpinfo.post_id)
				left join app_province city on (city.p_id=emrpinfo.p_id)
				left join app_region reg_ on (reg_.r_id=city.r_id)
				left join app_country coun_ on (coun_.cou_id=reg_.cou_id)
				left join app_zipcodes zcode on (zcode.zipcode_id=emrpinfo.zipcode_id)
				where emrpi_id=?";
		$rsResult = $this->conn->Execute($sql,array($emrpi_id_));
		if(!$rsResult->EOF){
			return $rsResult->fields;
		}
	}
}
?>