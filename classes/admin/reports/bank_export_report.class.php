<?php
require_once(SYSCONFIG_CLASS_PATH."util/pdf.class.php");
require_once(SYSCONFIG_CLASS_PATH.'admin/reports/sss.class.php');
require_once(SYSCONFIG_CLASS_PATH.'admin/reports/bank_export_rcbc.class.php');
require_once(SYSCONFIG_CLASS_PATH.'admin/reports/bank_export_bdo.class.php');
require_once(SYSCONFIG_CLASS_PATH.'admin/reports/bank_export_union.class.php');
require_once(SYSCONFIG_CLASS_PATH.'admin/reports/bank_export_standard.class.php');
/**
 * Initial Declaration
 */

/**
 * Class Module
 * @author  JIM
 */
class clsBankExportReport {
	var $conn;
	var $fieldMap;
	var $Data;
	var $txtHash;

	/**
	 * Class Constructor
	 * @param object $dbconn_
	 * @return clsBankExportReport object
	 */
	function clsBankExportReport ($dbconn_ = null) {
		$this->conn =& $dbconn_;
		$this->fieldMap = array(
		 "payperiod_id" => "payperiod_id"
		,"pbr_batchno" => "batch_no"
		,"pbr_bank_name" => "banklist_name"
		,"pbr_routing_number" => "bank_routing_number"
		,"pbr_company_code" => "bank_company_code"
		,"pbr_company_accountname" => "bank_acct_name"
		,"pbr_company_accountno" => "bank_acct_no"
		,"pbr_ceiling_amount" => "bank_ceiling_amount"
		,"pbr_prepared_by" => "pbr_prepared_by"
		,"pbr_prepared_pos" => "pbr_prepared_pos"
		,"pbr_approved_by" => "pbr_approved_by"
		,"pbr_approved_pos" => "pbr_approved_pos"
		,"pbr_credit_date" => "pbr_credit_date"
		,"bank_id" => "bank_id"
		,"banklist_id" => "banklist_id"
		,"attention" => "attention"
		,"att_pos" => "att_pos"
		);
		$this->txtHash = "";
	}

	/**
	 * Get the records from the database
	 * @param string $id_
	 * @return array
	 */
	function dbFetch ($id_ = "") {
		$sql = "select * from payroll_bank_reports where pbr_id = ?";
		$rsResult = $this->conn->Execute($sql,array($id_));
		if(!$rsResult->EOF){
			return $rsResult->fields;
		}
	}
	
	function getHashFile ($id_ = "",$isPDF_ = true,$bankname = "") {
		$isVar = 0;
        IF ($isPDF_) {
            $fldname = "pdf";
        } ELSE {
        	IF($bankname=='Union Bank'){ $isVar = 1; }
        	$fldname = "txt";
        }
		$sql = "SELECT pbrd_hash_$fldname FROM payroll_bank_report_data WHERE pbr_id = ?";
		$rsResult = $this->conn->Execute($sql,array($id_));
		if (!$rsResult->EOF) {
			IF($isVar!=0){
				$rsResult->fields["pbrd_hash_$fldname"] = unserialize($rsResult->fields["pbrd_hash_$fldname"]);
				return $rsResult->fields["pbrd_hash_$fldname"];
			}ELSE{
				return $rsResult->fields["pbrd_hash_$fldname"];
			}
		}
	}
	
	/**
	 * To get BANK INFO.
	 * @param $id_
	 */
	function getHashFileInfo($id_ = ""){
		$sql = "SELECT * FROM payroll_bank_report_data WHERE pbr_id = ?";
		$rsResult = $this->conn->Execute($sql,array($id_));
		if(!$rsResult->EOF){
			$sql_ = "SELECT * FROM payroll_bank_reports WHERE pbr_id = ?";
			$rsResult_ = $this->conn->Execute($sql_,array($id_));
			return $rsResult_->fields;
		}
	}
	
	/**
	 * Populate array parameters to Data Variable
	 * @param array $pData_
	 * @param boolean $isForm_
	 * @return bool
	 */
	function doPopulateData ($pData_ = array(),$isForm_ = false) {
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
	 * @param array $pData_
	 * @return bool
	 */
	function doValidateData ($pData_ = array()) {
		$isValid = true;
		if ( (empty($pData_['pps_name'])) || (empty($pData_['payperiod_start_date'])) || (empty($pData_['payperiod_end_date'])) || (empty($pData_['payperiod_trans_date'])) ) {
			$isValid = false;
			$_SESSION['eMsg'][] = "Please select Pay Group.";
		}
		if (empty($pData_['banklist_name'])) {
			$isValid = false;
			$_SESSION['eMsg'][] = "Please select Bank Name.";
		}
		IF($pData_['banklist_name'] == 'BPI'){
			if (empty($pData_['bank_routing_number'])) {
				$isValid = false;
				$_SESSION['eMsg'][] = "If BPI, please enter Presenting Office Code.";
			}
		}
		if (empty($pData_['bank_company_code'])) {
			$isValid = false;
			$_SESSION['eMsg'][] = "Please enter Company Code.";
		}
		if (empty($pData_['bank_acct_no'])) {
			$isValid = false;
			$_SESSION['eMsg'][] = "Please enter Company Account No.";
		}
		if($pData_['pbr_credit_date'] == '0000-00-00'){
			$isValid = false;
			$_SESSION['eMsg'][] = "Please enter a valid date.";
		}
		return $isValid;
	}

	/**
	 * Save New
	 */
	function doSaveAdd(){
		//This is to check what bank need to generate
		$bankInfo = $this->getBankDetails($this->Data['bank_id']);
		IF($this->Data['pbr_bank_name']=='BPI'){
        	$pdfOutput = addslashes($this->getPDFResult($this->Data));
        	$txtOutput = $this->txtHash;
        }ELSEIF($this->Data['pbr_bank_name']=='RCBC'){
        	$objCLSBankExportRCBC = new clsBankExportRCBC($this->conn);
        	$pdfOutput = addslashes($objCLSBankExportRCBC->getPDFResult_RCBC($this->Data));
        	$txtOutput = $objCLSBankExportRCBC->doTextHash_();
        }ELSEIF($this->Data['pbr_bank_name']=='BDO' || $this->Data['pbr_bank_name']=='Banco De Oro'){
        	$objCLSBankExportBDO = new clsBankExportBDO($this->conn);
        	$pdfOutput = addslashes($objCLSBankExportBDO->getPDFResult_BDO($this->Data));
        	$txtOutput = $objCLSBankExportBDO->doTextHash_();
        }ELSEIF($this->Data['pbr_bank_name']=='Union Bank'){
        	$objCLSBankExportUnion = new clsBankExportUnion($this->conn);
        	$pdfOutput = addslashes($objCLSBankExportUnion->getPDFResult_Union($this->Data));
        	$xlsOutput = serialize($objCLSBankExportUnion->BankEmpLIST($this->Data));
        }ELSEIF($this->Data['pbr_bank_name']=='Standard Chartered Bank'){
			$objCLSBankExportStandChart = new clsBankExportStandChart($this->conn);
        	$pdfOutput = addslashes($objCLSBankExportStandChart->generateStdChartLetter($this->Data,$bankInfo,$_POST));
        	$txtOutput = $objCLSBankExportStandChart->doTextHash_();
        }ELSE{
        	$_SESSION['eMsg']="Bank File is Under Development.";
        	return;
        }
//        printa($txtOutput); exit;
		//@note: To save Header Bank file
		$flds = array();
		foreach ($this->Data as $keyData => $valData) {
			IF($keyData=='banklist_id' || $keyData=='attention' || $keyData =='att_pos'){
				break;
			}
			$valData = addslashes($valData);
			$flds[] = "$keyData='$valData'";
		}
		$flds[] = "pbr_addwho='".AppUser::getData('user_name')."'";
		$fields = implode(", ",$flds);
		$sql = "INSERT INTO payroll_bank_reports SET $fields";
		$this->conn->Execute($sql);
        $pbr_id = $this->conn->Insert_ID();
        //@note: to save details bank file
        $flds_ = array();
        $flds_[] = "pbrd_hash_pdf='".$pdfOutput."'";
        IF($this->Data['pbr_bank_name']=='Union Bank'){
        	$flds_[] = "pbrd_hash_txt='".$xlsOutput."'";
        }ELSE{
        	$flds_[] = "pbrd_hash_txt='".$txtOutput."'";
        }
        $flds_[] = "pbr_id='$pbr_id'";
		$fields_ = implode(", ",$flds_);
        $sql_ = "INSERT INTO payroll_bank_report_data SET $fields_";
        $this->conn->Execute($sql_);
		$_SESSION['eMsg']="Successfully Added Payroll Transaction Prooflist batch no. ".$this->Data['pbr_batchno'];
		
		/*$flds = array();
		foreach ($this->Data as $keyData => $valData) {
			$valData = addslashes($valData);
			$flds[] = "$keyData='$valData'";
		}
		$fields = implode(", ",$flds);
		$sql = "insert into payroll_bank_reports set $fields";
		$this->conn->Execute($sql);
        $pbr_id = $this->conn->Insert_ID();
        $pdfOutput = addslashes($this->getPDFResult($this->Data));
//        echo $this->txtHash;
//        printa($this->txtHash);
        $flds = array();
        $flds[] = "pbrd_hash_pdf='$pdfOutput'";
        $flds[] = "pbrd_hash_txt='".$this->txtHash."'";
        $flds[] = "pbr_id='$pbr_id'";
		$fields = implode(", ",$flds);
        $sql = "insert into payroll_bank_report_data set $fields";
        $this->conn->Execute($sql);
		$_SESSION['eMsg']="Successfully Added Payroll Transaction Prooflist batch no. ".$this->Data['pbr_batchno'];*/
	}
	/**
	 * 
	 * for iBank specs
	 * @author: IR Salvador
	 */
	function doSaveAddiBank(){
		IF($this->Data['pbr_bank_name']=='Union Bank'){
		$objCLSBankExportUnion = new clsBankExportUnion($this->conn);
		//$pdfOutput = addslashes($objCLSBankExportUnion->getPDFResult_Union($this->Data));
        $emp = $objCLSBankExportUnion->getEmployees($this->Data);
        $emp['gData']['Attention'] = $_POST['attention'];
        //$xlsOutput = $objCLSBankExportUnion->getXLSResult_Union($emp);
		} ELSE {
			$_SESSION['eMsg']="Bank File is Under Development.";
        	return;
		}
		$flds = array();
		foreach ($this->Data as $keyData => $valData) {
			IF($keyData=='banklist_id'){
				break;
			}
			$valData = addslashes($valData);
			$flds[] = "$keyData='$valData'";
		}
		$flds[] = "pbr_addwho='".AppUser::getData('user_name')."'";
		$flds[] = "pbr_report_type='1'";
		$fields = implode(", ",$flds);
		$sql = "INSERT INTO payroll_bank_reports SET $fields";
		$this->conn->Execute($sql);
        $pbr_id = $this->conn->Insert_ID();
        //@note: to save details bank file
        $flds_ = array();
        $flds_[] = "pbrd_hash_pdf='".$pdfOutput."'";
        $flds_[] = "pbrd_hash_txt='".serialize($emp)."'";
        $flds_[] = "pbr_id='$pbr_id'";
		$fields_ = implode(", ",$flds_);
        $sql_ = "INSERT INTO payroll_bank_report_data SET $fields_";
        $this->conn->Execute($sql_);
		$_SESSION['eMsg']="Successfully Added Payroll Transaction Prooflist batch no. ".$this->Data['pbr_batchno'];
		
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
        $flds[] = "pbd_addwho = '".AppUser::getdata('user_name')."'";
        $flds[] = "pbd_updatewhen = ".date('Y-m-d')."";
		$fields = implode(", ",$flds);

		$sql = "update payroll_bank_details set $fields where pbd_id=$id";
		$this->conn->Execute($sql);
		$_SESSION['eMsg']="Successfully Updated.";
	}

    function updateAccountNo($pData = array()){
        if(count($pData)>0){
            foreach($pData as $key => $val){
                $sql  ="update payroll_employee_account set pea_account_no = '".$val."' where pea_id =".$key." ";
                $this->conn->Execute($sql);

            }
            $_SESSION['eMsg']="Successfully Updated.";
        }
    }

	/**
	 * Delete Record
	 * @param string $id_
	 */
	function doDelete($id_ = ""){
		$sql = "delete from payroll_bank_reports where pbr_id=?";
		$this->conn->Execute($sql,array($id_));
		$sql = "delete from payroll_bank_report_data where pbr_id=?";
		$this->conn->Execute($sql,array($id_));
		$_SESSION['eMsg']="Successfully Deleted.";
	}

	function doDelete_emp($id_ = ""){
		$sql = "delete from payroll_employee_account where pea_id=?";
		$this->conn->Execute($sql,array($id_));
		$_SESSION['eMsg']="Successfully Deleted Employee.";
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
				$qry[] = "(pbr_bank_name like '%$search_field%' || pbr_batchno like '%$search_field%' || pbr_credit_date like '%$search_field%')";

			}
		}
		$listpgroup = $_SESSION[admin_session_obj][user_paygroup_list2];
		IF(count($listpgroup)>0){
			$qry[] = "c.pps_id in (".$listpgroup.")";//pay group that can access
		}
		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" WHERE ".implode(" AND ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		 "viewdata"=>"viewdata"
		,"pbr_bank_name"=>"pbr_bank_name"
		,"pbr_batchno"=>"pbr_batchno"
		,"pps_name"=>"pps_name"
		,"pbr_credit_date"=>"pbr_credit_date"
		);

		if(isset($_GET['sortby'])){
			$strOrderBy = " ORDER BY ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}
		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		// default links
		$viewLink = "";
		$txtLink = "<a href=\"?statpos=bank_export_report&edit=',am.pbr_id,'&pdf=0&bankname=',am.pbr_bank_name,'\" target=\"_blank\"><img src=\"".SYSCONFIG_THEME_URLPATH.SYSCONFIG_THEME."/images/icon6.gif\" title=\"View Text Hash report\" hspace=\"2px\" width=\'13\' border=0></a>";
		$editLink = "<a href=\"?statpos=bank_export_report&edit=',am.pbr_id,'&pdf=1\" target=\"_blank\"><img src=\"".SYSCONFIG_THEME_URLPATH.SYSCONFIG_THEME."/images/admin/file_acrobat.gif\" title=\"View PDF Report\" hspace=\"2px\" border=0></a>";
		$delLink = "<a href=\"?statpos=bank_export_report&delete=',am.pbr_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_THEME_URLPATH.SYSCONFIG_THEME."/images/admin/trash.gif\" title=\"Delete\" hspace=\"2px\"  border=0></a>";
		
		//for ibank links
		$ibankLink = "<a href=\"?statpos=bank_export_report&view=',am.pbr_id,'&excel=0&bankname=',am.pbr_bank_name,'\"><img src=\"".SYSCONFIG_THEME_URLPATH.SYSCONFIG_THEME."/images/admin/menu/report.png\" title=\"Download Details\" hspace=\"2px\" width=\'13\' border=0></a>";
		$ibankLetter = "<a href=\"?statpos=bank_export_report&view=',am.pbr_id,'&excel=1\" ><img src=\"".SYSCONFIG_THEME_URLPATH.SYSCONFIG_THEME."/images/excel.png\" title=\"Download Letter\" hspace=\"2px\" border=0></a>";
		
		$action = "<a href=\"?statpos=bank_export_report&action=add\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/add.png\" title=\"Add New\" border=0 width=\"16\" height=\"16\"></a>";
		// SqlAll Query
		$sql = "SELECT am.*, CONCAT(IFNULL(NULLIF(c.payperiod_name,''),pps_name),' (',DATE_FORMAT(payperiod_start_date,'%b %d, %Y'),' - ',DATE_FORMAT(payperiod_end_date,'%b %d, %Y'),')') as pps_name,
						IF(am.pbr_report_type=1,CONCAT('$ibankLink','$ibankLetter','$delLink'),CONCAT('$txtLink','$editLink','$delLink')) as viewdata
						FROM payroll_bank_reports am
                        JOIN bank_info b on (b.bank_id = am.bank_id)
                        JOIN payroll_pay_period c on (c.payperiod_id=am.payperiod_id)
                        JOIN payroll_pay_period_sched d on (d.pps_id=c.pps_id)
						$criteria
						$strOrderBy";

		// Field and Table Header Mapping
		$arrFields = array(
		 "viewdata"=>$action
		,"pbr_bank_name"=>"Bank Name"
		,"pbr_batchno"=>"Batch No"
		,"pps_name"=>"Pay Group(Cut-off Dates)"
		,"pbr_credit_date"=>"Payroll Date"
		);

		// Column (table data) User Defined Attributes
		$arrAttribs = array(
		"mnu_ord" => "align='center'",
		"viewdata" => "width='80' align='center'"
		);

		// Process the Table List
		$tblDisplayList = new clsTableList($this->conn);
		$tblDisplayList->arrFields = $arrFields;
		$tblDisplayList->paginator->linkPage = "?$queryStr";
		$tblDisplayList->sqlAll = $sql;
		$tblDisplayList->sqlCount = $sqlcount;
		$tblDisplayList->tblBlock->assign("title","Bank Export Report");
		return $tblDisplayList->getTableList($arrAttribs);
	}
	
	function getPDFResult($gData = array()){
        $orientation='P';
        $unit='mm';
        $format='LETTER';
        $unicode=true;
        $encoding="UTF-8";
        $oPDF = new clsPDF($orientation, $unit, $format, $unicode, $encoding);
        $objClsSSS = new clsSSS($this->conn);
        $branch_details = $objClsSSS->dbfetchCompDetails(1);
        // set header and footer fonts
        $oPDF->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $oPDF->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        //set margins
        $oPDF->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $oPDF->SetHeaderMargin(PDF_MARGIN_HEADER);
        // set auto page break to false so that we can control the page break
        // depending on the desired number of lines on the ouput
        $oPDF->SetAutoPageBreak(false);
        // use a freesans font as a default font
        $oPDF->SetFont('dotim5','',10);
        // suppress print header and footer
        $oPDF->setPrintHeader(true);
        $oPDF->setPrintFooter(false);
        // set initila coordinates
        $coordX = 5;
        $coordY = 30;
        $oPDF->AliasNbPages();
        // set initial pdf page
        $oPDF->AddPage();
        $oPDF->SetFillColor(255,255,255);
        $oPDF->SetFont('dotim5', '', '12');
        // printa($branch_details);
        $oPDF->SetHeaderData('', PDF_HEADER_LOGO_WIDTH, $branch_details['comp_name']);
        $oPDF->Text($coordX+60,$coordY-10,'PAYROL TRANSACTION PROOFLIST');
        $oPDF->Text($coordX+67,$coordY-05,'PAYROLL DATE: '.$gData['pbr_credit_date']);
        
        $oPDF->SetFont('dotim5', '', '10');
        $arrData = $this->generateReport($gData);
        $ctr = 0;
        $net =0;
        $gtotal_hash = 0;
        $gtotal_hash_acct=0;
        
        foreach($arrData as $key => $val){
            $net += $val['net'];
            $gtotal_hash += $val['hash'];
            $gtotal_hash_acct += str_replace('-', "", $val['bankiemp_acct_no']);
            $ctr++;
        }
        if($coordY==30){
            $oPDF->SetXY($coordX+5, $coordY);
            $oPDF->MultiCell(40, 2, "COMPANY CODE :",0,'R',1);
            $oPDF->SetXY($coordX+45, $coordY);
            $oPDF->MultiCell(70, 2, $gData['pbr_company_code'],0,'L',1);
            $oPDF->SetXY($coordX+110, $coordY);
            $oPDF->MultiCell(50, 2, "CEILING AMOUNT :",0,'R',1);
            $oPDF->SetXY($coordX+160, $coordY);
            $oPDF->MultiCell(40, 2, $gData['pbr_ceiling_amount'],0,'L',1);
            $coordY+=$oPDF->getFontSize()+1;

            $oPDF->SetXY($coordX+5, $coordY);
            $oPDF->MultiCell(40, 2, "ACCOUNT NO.:",0,'R',1);
            $oPDF->SetXY($coordX+45, $coordY);
            $oPDF->MultiCell(70, 2, trim($gData['pbr_company_accountno']),0,'L',1);
            $oPDF->SetXY($coordX+110, $coordY);
            $oPDF->MultiCell(50, 2, "RECORD COUNT:",0,'R',1);
            $oPDF->SetXY($coordX+160, $coordY);
            $oPDF->MultiCell(40, 2, $ctr,0,'L',1);
            $coordY+=$oPDF->getFontSize()+1;

            $oPDF->SetXY($coordX+5, $coordY);
            $oPDF->MultiCell(40, 2, "BATCH NO.:",0,'R',1);
            $oPDF->SetXY($coordX+45, $coordY);
            $oPDF->MultiCell(70, 2, $gData['pbr_batchno'],0,'L',1);
            $oPDF->SetXY($coordX+110, $coordY);
            $oPDF->MultiCell(50, 2, "TOTAL PAYROLL AMOUNT :",0,'R',1);
            $oPDF->SetXY($coordX+160, $coordY);
            $oPDF->MultiCell(40, 2, number_format($net,2),0,'L',1);
            $coordY+=$oPDF->getFontSize()+3;
            
            $oPDF->SetXY($coordX+5, $coordY);
            $oPDF->MultiCell(40, 2, "ACCOUNT NO.",1,'C',1);
            $oPDF->SetXY($coordX+45, $coordY);
            $oPDF->MultiCell(70, 2, 'EMPLOYEE NAME',1,'C',1);
            $oPDF->SetXY($coordX+110, $coordY);
            $oPDF->MultiCell(50, 2, "TRANSACTION AMOUNT",1,'C',1);
            $oPDF->SetXY($coordX+160, $coordY);
            $oPDF->MultiCell(40, 2, 'HORIZONTAL HASH',1,'C',1);
            $coordY+=$oPDF->getFontSize()+4;
        }
        $row_ctr = 0;
        $net_total_perpage=0;
        $hash_net_total_perpage=0;
        $hash_acct_total_perpage=0;
        foreach($arrData as $key => $val ){
            $net_total_perpage +=$val['net'];
            $hash_net_total_perpage +=$val['hash'];
            $hash_acct_total_perpage +=str_replace('-', "", $val['bankiemp_acct_no']);
            $row_ctr++;
            $oPDF->SetXY($coordX+5, $coordY);
            $oPDF->MultiCell(40, 2, $val['bankiemp_acct_no'],0,'C',1);
            $oPDF->SetXY($coordX+45, $coordY);
            $oPDF->MultiCell(70, 2,strtoupper($val['name']),0,'L',1);
            $oPDF->SetXY($coordX+110, $coordY);
            $oPDF->MultiCell(50, 2, number_format($val['net'],2),0,'R',1);
            $oPDF->SetXY($coordX+160, $coordY);
            $oPDF->MultiCell(40, 2,number_format($val['hash'],2),0,'R',1);
            $coordY+=$oPDF->getFontSize()+1;
            if($row_ctr  > 30){
                $oPDF->Line($coordX+5, $coordY+5.5, 200, $coordY+5.5);
                $coordY+=$oPDF->getFontSize()+1;
                // plot net total,hash total, accnt number hash total per page
                $oPDF->SetFont('dotim5', '', '10');
                $oPDF->SetXY($coordX+5, $coordY);
                $oPDF->MultiCell(80, 2, "HASH TOTALS (page)",0,'L',1);
                $coordY+=$oPDF->getFontSize()+1;
                $oPDF->SetXY($coordX+5, $coordY);
                $oPDF->MultiCell(40, 2, $hash_acct_total_perpage,0,'C',1);
                $oPDF->SetXY($coordX+45, $coordY);
                $oPDF->MultiCell(70, 2,"",0,'L',1);
                $oPDF->SetXY($coordX+110, $coordY);
                $oPDF->MultiCell(50, 2, number_format($net_total_perpage,2),0,'R',1);
                $oPDF->SetXY($coordX+160, $coordY);
                $oPDF->MultiCell(40, 2,number_format($hash_net_total_perpage,2),0,'R',1);
                $coordY+=$oPDF->getFontSize()+1;

                // reset variable settings
                $net_total_perpage = 0;
                $hash_net_total_perpage = 0;
                $hash_acct_total_perpage = 0;
                $row_ctr = 0;
                $coordY = 30;
                $oPDF->AliasNbPages();
                // set initial pdf page
                $oPDF->AddPage();
                $oPDF->SetFillColor(255,255,255);
                $oPDF->SetFont('dotim5', '', '12');
                $oPDF->Text($coordX+60,$coordY-10,'PAYROL TRANSACTION PROOFLIST');
                $oPDF->Text($coordX+67,$coordY-05,'PAYROLL DATE: '.$gData['pbr_credit_date']);
               if($coordY==30){
                    $oPDF->SetFont('dotim5', '', '10');
                    $oPDF->SetXY($coordX+5, $coordY);
                    $oPDF->MultiCell(40, 2, "COMPANY CODE :",0,'R',1);
                    $oPDF->SetXY($coordX+45, $coordY);
                    $oPDF->MultiCell(70, 2, $gData['pbr_company_code'],0,'L',1);
                    $oPDF->SetXY($coordX+110, $coordY);
                    $oPDF->MultiCell(50, 2, "CEILING AMOUNT :",0,'R',1);
                    $oPDF->SetXY($coordX+160, $coordY);
                    $oPDF->MultiCell(40, 2, $gData['pbr_ceiling_amount'],0,'L',1);
                    $coordY+=$oPDF->getFontSize()+1;

                    $oPDF->SetXY($coordX+5, $coordY);
                    $oPDF->MultiCell(40, 2, "ACCOUNT NO.:",0,'R',1);
                    $oPDF->SetXY($coordX+45, $coordY);
                    $oPDF->MultiCell(70, 2, $gData['pbr_company_accountno'],0,'L',1);
                    $oPDF->SetXY($coordX+110, $coordY);
                    $oPDF->MultiCell(50, 2, "RECORD COUNT:",0,'R',1);
                    $oPDF->SetXY($coordX+160, $coordY);
                    $oPDF->MultiCell(40, 2, $ctr,0,'L',1);
                    $coordY+=$oPDF->getFontSize()+1;

                    $oPDF->SetXY($coordX+5, $coordY);
                    $oPDF->MultiCell(40, 2, "BATCH NO.:",0,'R',1);
                    $oPDF->SetXY($coordX+45, $coordY);
                    $oPDF->MultiCell(70, 2, $gData['pbr_credit_date'],0,'L',1);
                    $oPDF->SetXY($coordX+110, $coordY);
                    $oPDF->MultiCell(50, 2, "Total Payroll Amt:",0,'R',1);
                    $oPDF->SetXY($coordX+160, $coordY);
                    $oPDF->MultiCell(40, 2, number_format($net,2),0,'L',1);
                    $coordY+=$oPDF->getFontSize()+3;

                    $oPDF->SetXY($coordX+5, $coordY);
                    $oPDF->MultiCell(40, 2, "ACCOUNT NO.",1,'C',1);
                    $oPDF->SetXY($coordX+45, $coordY);
                    $oPDF->MultiCell(70, 2, 'EMPLOYEE NAME',1,'C',1);
                    $oPDF->SetXY($coordX+110, $coordY);
                    $oPDF->MultiCell(50, 2, "TRANSACTION AMOUNT",1,'C',1);
                    $oPDF->SetXY($coordX+160, $coordY);
                    $oPDF->MultiCell(40, 2, 'HORIZONTAL HASH',1,'C',1);
                    $coordY+=$oPDF->getFontSize()+4;
                }else{
                    // increment coordinate Y
                    $coordY+=$oPDF->getFontSize()+1;
                }
            }
        }
        $oPDF->Line($coordX+5, $coordY+5.5, 200, $coordY+5.5);
        $coordY+=$oPDF->getFontSize()+1;
        // plot net total,hash total, accnt number hash total per page
        $oPDF->SetFont('dotim5', '', '10');
        $oPDF->SetXY($coordX+5, $coordY);
        $oPDF->MultiCell(80, 2, "HASH TOTALS (page)",0,'L',1);
        $coordY+=$oPDF->getFontSize()+1;
        $oPDF->SetXY($coordX+5, $coordY);
        $oPDF->MultiCell(40, 2, $hash_acct_total_perpage,0,'C',1);
        $oPDF->SetXY($coordX+45, $coordY);
        $oPDF->MultiCell(70, 2,"",0,'L',1);
        $oPDF->SetXY($coordX+110, $coordY);
        $oPDF->MultiCell(50, 2, number_format($net_total_perpage,2),0,'R',1);
        $oPDF->SetXY($coordX+160, $coordY);
        $oPDF->MultiCell(40, 2,number_format($hash_net_total_perpage,2),0,'R',1);
        $coordY+=$oPDF->getFontSize()+1;

        // grand totals
        $oPDF->SetXY($coordX+5, $coordY);
        $oPDF->MultiCell(70, 2, "COMPUTED HASH TOTALS",0,'L',1);
        $coordY+=$oPDF->getFontSize()+1;
        
        $oPDF->SetXY($coordX+5, $coordY);
        $oPDF->MultiCell(40, 2, $gtotal_hash_acct,0,'C',1);
        $oPDF->SetXY($coordX+45, $coordY);
        $oPDF->MultiCell(70, 2,"",0,'L',1);
        $oPDF->SetXY($coordX+110, $coordY);
        $oPDF->MultiCell(50, 2, number_format($net,2),0,'R',1);
        $oPDF->SetXY($coordX+160, $coordY);
        $oPDF->MultiCell(40, 2,number_format($gtotal_hash,2),0,'R',1);
        $coordY+=$oPDF->getFontSize()+10;

        $oPDF->SetXY($coordX+5, $coordY);
        $oPDF->MultiCell(100, 2, "PREPARED BY:",0,'L',1);
        $oPDF->SetXY($coordX+100, $coordY);
        $oPDF->MultiCell(100, 2,"APPROVED BY:",0,'L',1);
        $coordY+=$oPDF->getFontSize()+7;
        
        $oPDF->SetXY($coordX+5, $coordY);
        $oPDF->MultiCell(100, 2, "__________________________________________",0,'L',1);
        $oPDF->SetXY($coordX+100, $coordY);
        $oPDF->MultiCell(100, 2,"__________________________________________",0,'L',1);
        $coordY+=$oPDF->getFontSize()+1;
        
        $oPDF->SetXY($coordX+5, $coordY);
        $oPDF->MultiCell(100, 2, "",0,'L',1);
        $oPDF->SetXY($coordX+5, $coordY);
        $oPDF->MultiCell(100, 2,$gData['pbr_prepared_by'],0,'C',1);
        $oPDF->SetXY($coordX+5, $coordY);
        $oPDF->MultiCell(100, 2, "",0,'L',1);
        $oPDF->SetXY($coordX+100, $coordY);
        $oPDF->MultiCell(100, 2,$gData['pbr_approved_by'],0,'C',1);
        $coordY+=$oPDF->getFontSize()+3;
        
        $oPDF->SetXY($coordX+5, $coordY);
        $oPDF->MultiCell(100, 2, "NOTED BY:",0,'L',1);
        $oPDF->SetXY($coordX+100, $coordY);
        $oPDF->MultiCell(100, 2,"",0,'L',1);
        $coordY+=$oPDF->getFontSize()+7;
        $oPDF->SetXY($coordX+5, $coordY);
        $oPDF->MultiCell(100, 2, "__________________________________________",0,'L',1);
        $oPDF->SetXY($coordX+100, $coordY);
        $oPDF->MultiCell(100, 2,"__________________________________________",0,'L',1);
        $coordY+=$oPDF->getFontSize()+1;
        
        $oPDF->SetXY($coordX+5, $coordY);
        $oPDF->MultiCell(100, 2, "",0,'L',1);
        $oPDF->SetXY($coordX+100, $coordY);
        $oPDF->MultiCell(100, 2,$gData['pbr_official_director'],0,'C',1);
        $coordY+=$oPDF->getFontSize()+1;
        $oPDF->SetXY($coordX+5, $coordY);
        $oPDF->MultiCell(100, 2, "",0,'L',1);
        $oPDF->SetXY($coordX+100, $coordY);
        $oPDF->MultiCell(100, 2,"Director",0,'C',1);
        
        //build hash header for the text hash report
        $hashHeader_ ='H'.str_pad($gData['pbr_company_code'],5,'0',STR_PAD_LEFT);
        $date = dDate::getFormattedDateStampMDY(dDate::parseDateTime($gData['pbr_credit_date']));
        $hashHeader_ .= str_pad($date,6,'0',STR_PAD_LEFT);
        //batch no+'3'fixed
        $hashHeader_ .= str_pad($gData['pbr_batchno'],2,'0',STR_PAD_LEFT)."1";
        //company account no
        $hashHeader_ .= str_pad($gData['pbr_company_accountno'],10,'0',STR_PAD_LEFT);
        //presenting office code
        $hashHeader_ .= str_pad($gData['pbr_routing_number'],3,'0',STR_PAD_LEFT);
        //ceiling amount
        $hashHeader_ .= str_pad(str_replace('.', "", $gData['pbr_ceiling_amount']),12,'0',STR_PAD_LEFT);
        //net
        $hashHeader_ .= str_pad(str_replace('.', "", number_format($net,2,'.','')),12,'0',STR_PAD_LEFT)."1";
        //spaces
        $hashHeader_ .= str_repeat(" ", 75);

        //build hash footer for the text hash report
        $hashFooter_ ='T'.str_pad($gData['pbr_company_code'],5,'0',STR_PAD_LEFT);
        $date = dDate::getFormattedDateStampMDY(dDate::parseDateTime($gData['pbr_credit_date']));
        $hashFooter_ .= str_pad($date,6,'0',STR_PAD_LEFT);
        //batch no+'3'fixed
        $hashFooter_ .= str_pad($gData['pbr_batchno'],2,'0',STR_PAD_LEFT)."2";
        //company account no
        $hashFooter_ .= str_replace('CA',"",str_replace(' ',"",str_replace('-', "",$gData['pbr_company_accountno'])));
        //hash account total
        $hashFooter_ .= str_pad(str_replace('.', "", $gtotal_hash_acct),15,'0',STR_PAD_LEFT);
        // total netpay
        $hashFooter_ .= str_pad(str_replace('.', "", number_format($net,2,'.','')),15,'0',STR_PAD_LEFT);
        // total hash
        $hashFooter_ .= str_pad(str_replace('.', "", number_format($gtotal_hash,2,'.','')),18,'0',STR_PAD_LEFT);
        // total cnt
        $hashFooter_ .= str_pad($ctr,5,'0',STR_PAD_LEFT);
        //spaces
        $hashFooter_ .= str_repeat(" ", 50);
        // get the pdf output
        $output = $oPDF->Output("payroll_transaction_prooflist".date('Y-m-d').".pdf","S");

        if(!empty($output)){
            $this->doTextHash($arrData, $hashHeader_, $hashFooter_);
            return $output;
        }
        return false;
    }
    
	function generateReport($gData = array()){
        $qry = array();
        $qry[] = "h.payperiod_id = ".$gData['payperiod_id']."";
        $qry[] = "c.emp_stat in ('1','7')";
        $qry[] = "g.bank_id = ".$gData['bank_id']."";
        $criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";

        $sql = "select concat(e.pi_lname,', ',e.pi_fname) as name,f.bankiemp_acct_no,c.emp_id
                from payroll_pay_period h
                inner join payroll_pay_period_sched a on (a.pps_id=h.pps_id)
                inner join payroll_pps_user b on (b.pps_id=a.pps_id)
                inner join emp_masterfile c on (c.emp_id=b.emp_id)
                inner join emp_personal_info e on (e.pi_id=c.pi_id)
                inner join bank_infoemp f on (f.emp_id = c.emp_id)
                inner join bank_info g on (g.bank_id = f.banklist_id)
                inner join app_userdept dept on dept.ud_id=c.ud_id
                $criteria
                order by e.pi_lname";
        $rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF){
			$arrData[$rsResult->fields['emp_id']] = $rsResult->fields;
            $arrData[$rsResult->fields['emp_id']]['net'] = $this->getNetpayPerEmp($rsResult->fields['emp_id'],$gData['payperiod_id']);
            $arrData[$rsResult->fields['emp_id']]['hash'] = $this->computeHorizontalHash(trim($rsResult->fields['bankiemp_acct_no']),$arrData[$rsResult->fields['emp_id']]['net']);
            //company code
            $text_hash = 'D'.str_pad($gData['pbr_company_code'],5,'00000',STR_PAD_LEFT);
            //convert date to mdy
            $date = dDate::getFormattedDateStampMDY(dDate::parseDateTime($gData['pbr_credit_date']));
            $text_hash .= str_pad($date,5,'000000',STR_PAD_LEFT);
            //batch no+'3'fixed
            $text_hash .= str_pad($gData['pbr_batchno'],2,'00',STR_PAD_LEFT)."3";
            //empolyee account no
            $text_hash .= str_replace('-', "", trim($rsResult->fields['bankiemp_acct_no']));
            //net pay
            $text_hash .= str_pad(str_replace('.', "",number_format($arrData[$rsResult->fields['emp_id']]['net'],2,'.','')),12,'0',STR_PAD_LEFT);
            //hash
            $text_hash .= str_pad(str_replace('.', "",number_format($arrData[$rsResult->fields['emp_id']]['hash'],2,'.','')),12,'0',STR_PAD_LEFT);
            $text_hash .= str_repeat(" ", 79);
            $arrData[$rsResult->fields['emp_id']]['txt_hash'] =$text_hash;
            $rsResult->MoveNext();
		}
//      printa($arrData);
//		exit;
        return $arrData;
    }

    function getNetpayPerEmp($emp_id  ="", $payperiod_id =""){
        if($emp_id == ""){ return 0.00; }
        if($payperiod_id == ""){ return 0.00; }
        $sql ="SELECT b.ppe_amount
                FROM payroll_pay_stub a
                JOIN payroll_paystub_entry b on (a.paystub_id = b.paystub_id)
                JOIN payroll_paystub_report c on (a.payperiod_id = c.payperiod_id) and (b.paystub_id = c.paystub_id)
                where c.ppr_isdeleted = 0 and c.ppr_status = 1 and b.psa_id =5 and a.payperiod_id = $payperiod_id and a.emp_id = $emp_id";
        $rsResult = $this->conn->Execute($sql);
		if(!$rsResult->EOF){
			return $rsResult->fields['ppe_amount'];
		}
    }

    function doTextHash($aData_ = null,$hashHeader_ = "", $hashFooter_ = "", $delimeter_ = "\r\n" ) {
    	if(is_null($aData_)){ return ""; }
        $this->txtHash = $hashHeader_.$delimeter_;
        if(count($aData_)>0)
        foreach ($aData_ as $keyData => $valData) {
            $this->txtHash .= $valData['txt_hash'].$delimeter_;
        }
        $this->txtHash .= $hashFooter_.$delimeter_;
    }

    function computeHorizontalHash($account_no ="", $net=""){
        if($account_no == ""){ return 0.00; }
        if($net == ""){ return 0.00; }
        $data = explode('-',$account_no);
        $x = substr($data[0], 4,2);
        $y = substr($data[0], 6,2);
        $z = substr($data[0], 8,2);
//        echo $x." x<br>";
//        echo $y." y<br>";
//        echo $z." z<br>";
//        echo $net."<br>";
        $x_net = $x*$net;
        $y_net = $y*$net;
        $z_net = $z*$net;
        $hash = $x_net+$y_net+$z_net;
        return $hash;
    }
    
    function getPayrollBankReport($pbr_id_ = null){
    	$sql = "select * from payroll_bank_report_data where pbr_id='".$pbr_id_."'";
    	$rsResult = $this->conn->Execute($sql);
		if(!$rsResult->EOF){
			return unserialize($rsResult->fields['pbrd_hash_txt']);
		}
    }
    
    function getBankDetails($bank_id_ = null){
    	$sql = "select * from bank_info a 
				join bnkaccnt_type b on (b.baccntype_id=a.baccntype_id)
				join bank_list c on (c.banklist_id=a.banklist_id)
				join company_info d on (d.comp_id=a.comp_id)
				where bank_id=?";
    	$rsResult = $this->conn->GetAssoc($sql,array($bank_id_));
    	return $rsResult[$bank_id_];
    }
}
?>