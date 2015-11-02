<?php
/**
 * Initial Declaration
 */
require_once SYSCONFIG_CLASS_PATH.'util/swiftmailer/lib/swift_required.php';
require_once(SYSCONFIG_ROOT_PATH.'helpers/encryption.helper.php');
/**
 * Class Module
 *
 * @author  JIM
 *
 */
class clsPslipR {

	var $conn;
	var $fieldMap;
	var $Data;

	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsPslipR object
	 */
	function clsPslipR ($dbconn_ = null) {
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
	 *
	 * @param string $id_
	 * @return array
	 */
	function dbFetch($id_ = ""){
		$sql = "SELECT psar.pps_name,
			DATE_FORMAT(payperiod_start_date,'%d-%b-%y %h:%i %p') as payperiod_start_date,
			DATE_FORMAT(payperiod_end_date,'%d-%b-%y %h:%i %p') as payperiod_end_date,
			DATE_FORMAT(payperiod_trans_date,'%d-%b-%y %h:%i %p') as payperiod_trans_date,
			IF(pp_stat_id='1','OPEN',IF(pp_stat_id='2','Locked - Pending Approval',IF(pp_stat_id='3','CLOSED','Post Adjustment'))) as pp_stat_id,
			IF(salaryclass_id='1','Daily',IF(salaryclass_id='2','Weekly',IF(salaryclass_id='3','Bi-Weekly',IF(salaryclass_id='4','Semi-monthly',IF(salaryclass_id='5','Monthly','Annual'))))) as salaryclass_id,
			IF(ppp.payperiod_type='2','YTD',IF(ppp.payperiod_type='3','Bonus',IF(ppp.payperiod_type='4','Others','Normal'))) as classification,
			(select count(emp_id) from payroll_paystub_report where payperiod_id=$id_) as totalemp	
			FROM payroll_pay_period ppp
			JOIN payroll_pay_period_sched psar on (psar.pps_id=ppp.pps_id)
			WHERE ppp.payperiod_id = ?";
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
		if(empty($pData_['email_subject'])){
			$isValid = false;
			$_SESSION['eMsg'][] = "Please enter title for message.";
		}
		if(!isset($pData_['chkAttend'])){
			$isValid = false;
			$_SESSION['eMsg'][] = "Please select atleast one employee.";
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

		$sql = "update /*app_modules*/ set $fields where mnu_id=$id";
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
				$qry[] = "payperiod_name like '%$search_field%' || payperiod_trans_date like '%$search_field%'";
			}
		}
		$listpgroup = $_SESSION[admin_session_obj][user_paygroup_list2];
		IF(count($listpgroup)>0){
			$qry[] = "psar.pps_id in (".$listpgroup.")";//pay group that can access
		}
		$qry[] = "ppp.pp_stat_id=3";
		$qry[] = "ppp.payperiod_type IN (1,3,4)";
		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" WHERE ".implode(" AND ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		 "period_name" => "period_name"
		,"salaryclass_id" => "salaryclass_id"
		,"pp_stat_id" => "pp_stat_id"
		,"payperiod_start_date" => "payperiod_start_date"
		,"payperiod_end_date" => "payperiod_end_date"
		,"payperiod_trans_date" => "payperiod_trans_date"
		);

		if(isset($_GET['sortby'])){
			$strOrderBy = " group by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof']." , ".payperiod_id;
		}else{
			$strOrderBy = "group by ".payperiod_id." DESC";
		}
		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$viewLink = "<a href=\"?statpos=pslipr&edit=',ppp.payperiod_id,'\" target=\"_blank\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/printer.png\" title=\"View Payslip\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
		$emailLink = "<a href=\"?statpos=pslipr&email=',ppp.payperiod_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/mail.png\" title=\"Send Payslips\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
//		$delLink = "<a href=\"?statpos=pslipr&delete=',am.mnu_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\"  border=0 width=\"16\" height=\"16\"></a>";
		// SqlAll Query
		$sql = "SELECT ppp.*, 
				IFNULL(NULLIF(ppp.payperiod_name,''),psar.pps_name) as period_name,
				CONCAT('$emailLink','$viewLink') as viewdata,
				DATE_FORMAT(payperiod_start_date,'%d %b %Y %h:%i %p') as payperiod_start_date,
				DATE_FORMAT(payperiod_end_date,'%d %b %Y %h:%i %p') as payperiod_end_date,
				DATE_FORMAT(payperiod_trans_date,'%d %b %Y') as payperiod_trans_date,psar.pps_name, 
				if(salaryclass_id='1','Daily',IF(salaryclass_id='2','Weekly',IF(salaryclass_id='3','Bi-Weekly',IF(salaryclass_id='4','Semi-monthly',IF(salaryclass_id='5','Monthly','Annual'))))) as salaryclass_id,
				IF(pp_stat_id='1','OPEN',IF(pp_stat_id='2','Locked - Pending Approval',IF(pp_stat_id='3','CLOSED','Post Adjustment'))) as pp_stat_id
						FROM payroll_paystub_report ppr
						JOIN payroll_pay_period ppp on (ppr.payperiod_id=ppp.payperiod_id)
						JOIN payroll_pay_period_sched psar on (psar.pps_id=ppp.pps_id)
						$criteria
						$strOrderBy";
		// Field and Table Header Mapping
		$arrFields = array(
		 "viewdata" => "Action"
		,"period_name" => "Name"
		,"salaryclass_id" => "Type"
		,"pp_stat_id" => "Status"
		,"payperiod_start_date" => "Start"
		,"payperiod_end_date" => "End"
		,"payperiod_trans_date" => "Pay Date"
		);
		// Column (table data) User Defined Attributes
		$arrAttribs = array(
		"mnu_ord"=>" align='center'",
		"viewdata"=>"align='center' width='50'",
		"salaryclass_name"=>"width='120'"
		);
		// Process the Table List
		$tblDisplayList = new clsTableList($this->conn);
		$tblDisplayList->arrFields = $arrFields;
		$tblDisplayList->paginator->linkPage = "?$queryStr";
		$tblDisplayList->sqlAll = $sql;
		$tblDisplayList->sqlCount = $sqlcount;
		$tblDisplayList->tblBlock->assign("title","Payslip Report");
		return $tblDisplayList->getTableList($arrAttribs);
	}
	
	/**
	 * Get the records from the database
	 * @param string $id_
	 * @return array
	 */
	function dbFetch_Payslip($id_ = "", $emp_id_= array() ){
//		$this->conn->debug=1;
		$qry = array();
		if (!is_null($id_)) {
			$qry[] = "b.payperiod_id ='".$id_."'";
		}
		if(count($emp_id_) > 0){
			$emp_id_ = implode(",",$emp_id_);
			$qry[] = "a.emp_id IN (".$emp_id_.")";
		}
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";
		$strOrderBy = "ORDER BY e.pi_lname";
		$sql = "SELECT a.*, DATE_FORMAT(b.payperiod_start_date, '%d %b %Y') as start_date, DATE_FORMAT(b.payperiod_end_date, '%d %b %Y') as end_date
					FROM payroll_paystub_report a
					JOIN payroll_pay_period b on (a.payperiod_id=b.payperiod_id)
					JOIN emp_masterfile c on (a.emp_id=c.emp_id)
					LEFT JOIN app_userdept dep on (dep.ud_id=c.ud_id)
					JOIN emp_personal_info e on (e.pi_id=c.pi_id)
					$criteria
					$strOrderBy";
		$rsResult = $this->conn->Execute($sql);
		$c = 0;
		while(!$rsResult->EOF){
			$cResult[$c] = $rsResult->fields; 
			$cResult[$c]['paystubdetails'] = unserialize($rsResult->fields['ppr_paystubdetails']);
			$c++;       	
        	$rsResult->MoveNext();
        }
        return $cResult;
	}
	/**
	 * @note: used for PDF Report. 2010.02.18 jim
	 * @param $oData
	 */
	function getPDFResult($oData = array(),$isLocal=0){
        $orientation='P';
        $unit='mm';
        $format='LETTER';
        $unicode=true;
        $encoding="UTF-8";
        $oPDF = new clsPDF($orientation, $unit, $format, $unicode, $encoding);
        $objClsMngeDecimal = new Application();
        //set margins
        $oPDF->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $oPDF->SetHeaderMargin(PDF_MARGIN_HEADER);
        // set auto page break to false so that we can control the page break
        // depending on the desired number of lines on the ouput
        $oPDF->SetAutoPageBreak(false);
		
        // suppress print header and footer
        $oPDF->setPrintHeader(true);
        $oPDF->setPrintFooter(false);
		        
        $oPDF->AliasNbPages();
        $i=0;
        $count_emp=0;
	    if(count($oData)>0){
        	for($i=0;$i<count($oData);$i++){
//        	if(($i % 2)==0){
//        		$oPDF->AddPage();
//        		$oPDF->SetFillColor(255,255,255);
//		        // set initial coordinates
//		        $coordX = 2;
//		        $coordY = 26;
//		        $coordYtop = 26;
//		        $end = 85;
//	        }else{
//	        	$oPDF->SetFillColor(255,255,255);
//		        // set initial coordinates
//		        $coordX = 2;
//		        $coordY = 160;
//		        $coordYtop = 160;
//		        $end = 220;
//	        }
			$oPDF->AddPage();
        	$oPDF->SetFillColor(255,255,255);
		    // set initial coordinates
		    $coordX = 2;
		    $coordY = 26;
		    $coordYtop = 26;
		    $end = 85;
		    
	        //used to line style...
	        $style4 = array('dash' => 1,1);
			$style5 = array('dash' => 2,2);
			$style6 = array('dash' => 0);
			$oPDF->Line($coordX, $coordY-3, $coordX+212, $coordY-3, $style = $style5);//line after header
			
//			$oPDF->Image(SYSCONFIG_ROOT_PATH2.SYSCONFIG_DEFAULT_IMAGES_INCTEMP.'icons/edited/sigma.png',$coordX+1, $coordY-1, 40, 5, '', 'http://www.sigmasoft.com.ph/', '', false, 300,'L');
        	IF($isLocal==1){ //to check if Location
				$LocInfo = clsPayroll_Details::getLocationInfo($oData[$i]['paystubdetails']['empinfo']['emp_id']);
				$comp_name = $LocInfo['branchinfo_name'];
				$comp_adds = $LocInfo['branchinfo_add'];
			}ELSE{
				$comp_name = $oData[$i]['paystubdetails']['empinfo']['comp_name'];
				$comp_adds = $oData[$i]['paystubdetails']['empinfo']['comp_add'];
			}
			$oPDF->SetFont('dejavusans','B',9);
	        $oPDF->SetXY($coordX, $coordY-1);
	        $oPDF->MultiCell(200, 2,$comp_name,0,'L',1);
	        
			//Receiving Area
			//---------------------------------------------------->>
			$oPDF->SetFont('times','B',6);
	        $oPDF->SetXY($coordX+170, $coordY+1);
	        $oPDF->MultiCell(42, 2,$comp_name,0,'C',1);
	        $oPDF->SetFont('dejavusans','B',7);
	        $oPDF->SetXY($coordX+170, $coordY+33);
	        $oPDF->MultiCell(42, 2,date("Md,Y",strtotime($oData[$i]['paystubdetails']['paystubdetail']['paystubsched']['payperiod_start_date'])).' to '.date("Md,Y",strtotime($oData[$i]['paystubdetails']['paystubdetail']['paystubsched']['payperiod_end_date'])),0,'C',1);
			$oPDF->SetFont('dejavusans','',7);
	        $oPDF->SetXY($coordX+170, $coordY+42);
	        $oPDF->MultiCell(44, 2,'TOTAL INCOME :',0,'L',1);
	        $oPDF->SetXY($coordX+170, $coordY+50);
	        $oPDF->MultiCell(44, 2,'TOTAL DEDUCTIONS :',0,'L',1);
	        $oPDF->SetXY($coordX+170, $coordY+58);
	        $oPDF->MultiCell(44, 2,'TAKE HOME PAY :',0,'L',1);
	        $oPDF->SetXY($coordX+170, $coordY+45);
	        $oPDF->MultiCell(41, 2,number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['TotalEarning_payslip'],2),0,'R',1);
	        $oPDF->SetXY($coordX+171, $coordY+45);
	        $oPDF->MultiCell(41, 2,'P',0,'L',1);
	        $oPDF->SetXY($coordX+170, $coordY+53);
	        $oPDF->MultiCell(41, 2,number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['Deduction'],2),0,'R',1);
	        $oPDF->SetXY($coordX+171, $coordY+53);
	        $oPDF->MultiCell(41, 2,'P',0,'L',1);
	        $oPDF->SetFont('dejavusans','B',7);
	        $oPDF->SetXY($coordX+170, $coordY+61);
	        $oPDF->MultiCell(41, 2,number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['Net Pay'],2),0,'R',1);
	        $oPDF->SetXY($coordX+171, $coordY+61);
	        $oPDF->MultiCell(41, 2,'P',0,'L',1);
	        $oPDF->SetFont('dejavusans','',6);
			$oPDF->SetXY($coordX+170, $coordY+7);
	        $oPDF->MultiCell(130, 2,'RECEIVED BY :',0,'L',1);
	        $oPDF->Line($coordX+171, $coordY+14.5, $coordX+210, $coordY+14.5, $style = $style6);//line
	        $oPDF->SetXY($coordX+170, $coordY+14);
	        $oPDF->MultiCell(130, 2,$oData[$i]['paystubdetails']['empinfo']['fullname'],0,'L',1);
	        $oPDF->SetXY($coordX+170, $coordY+16);
	        $oPDF->MultiCell(130, 2,'Emp No. : '.$oData[$i]['paystubdetails']['empinfo']['emp_no'],0,'L',1);
	        $oPDF->SetXY($coordX+170, $coordY+18);
	        $oPDF->MultiCell(130, 2,'DATE : ',0,'L',1);
	        $oPDF->Line($coordX+180, $coordY+21.5, $coordX+210, $coordY+21.5, $style = $style6);//line
	        $oPDF->SetXY($coordX+170, $coordY+26);
	        $oPDF->MultiCell(42, 2,'Received the amount mentioned',0,'J',1);
	        $oPDF->SetXY($coordX+170, $coordY+28);
	        $oPDF->MultiCell(42, 2,'below as full payment for my',0,'J',1);
	        $oPDF->SetXY($coordX+170, $coordY+30);
	        $oPDF->MultiCell(42, 2,'salary/wages for the Pay Period',0,'J',1);
	        //------------------------------------------------------<<
	        
	        //header
	        //------------------------------------------------------>>
			$oPDF->SetFont('dejavusans','',6);
			$coordY = $coordY + 3; 
	        $oPDF->SetXY($coordX, $coordY);
	        $oPDF->MultiCell(200, 2,$comp_adds,0,'L',1);
	        $coordY+=$oPDF->getFontSize()+1;
	        
	       	$oPDF->SetFont('dejavusans','',7);
	        $oPDF->SetXY($coordX, $coordY);
	        $oPDF->MultiCell(15, 2, "Name/ID",0,'L',1);
	        $oPDF->SetFont('dejavusans','B',7);
	        $oPDF->SetXY($coordX+13, $coordY);
	        $oPDF->MultiCell(85, 2, ':  '.strtoupper($oData[$i]['paystubdetails']['empinfo']['fullname']).' ('.$oData[$i]['paystubdetails']['empinfo']['emp_no'].')',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
	            
	        $oPDF->SetFont('dejavusans','',7);
	        $oPDF->SetXY($coordX+115, $coordY);
	        $oPDF->MultiCell(25, 2, "PAYDATE",0,'L',1);
	        $oPDF->SetXY($coordX+130, $coordY);
	        $oPDF->MultiCell(30, 2, ": ".date("M d, Y",strtotime($oData[$i]['paystubdetails']['paystubdetail']['paystubsched']['payperiod_trans_date'])),0,'L',1);
	        $coordY+=$oPDF->getFontSize()+.5;
	            
	        $oPDF->SetXY($coordX, $coordY);
	        $oPDF->MultiCell(19, 2, "Position",0,'L',1);
	        $oPDF->SetXY($coordX+13, $coordY);
	        $oPDF->MultiCell(110, 2, ':  '.$oData[$i]['paystubdetails']['empinfo']['jobpos_name'],0,'L',1);
	        $oPDF->SetXY($coordX+115, $coordY);
	        $oPDF->MultiCell(30, 2, "DEPT",0,'L',1);
	        $oPDF->SetXY($coordX+130, $coordY);
	        $oPDF->MultiCell(40, 2, ': '.$oData[$i]['paystubdetails']['empinfo']['ud_name'],0,'L',1);
	        $coordY+=$oPDF->getFontSize()+2;
	
	        $y = $coordY;
	        $oPDF->Line($coordX, $coordY, $coordX+169, $coordY, $style = $style6);//line after header
	        $coordY+=.1;
	        $oPDF->Line($coordX, $coordY, $coordX+169, $coordY, $style = $style6);//line after header2
	        //---------------------------------------------------<<
	        
	        //Head Body
        	$oPDF->SetFont('dejavusans','B',7);
            $oPDF->SetXY($coordX, $coordY);
            $oPDF->MultiCell(84.5, 2, 'EARNINGS',0,'C',1, 0, 0, 0, TRUE, 0, TRUE);
            $oPDF->SetXY($coordX+84.5, $coordY);
            $oPDF->MultiCell(84.5, 2, "DEDUCTIONS",0,'C',1, 0, 0, 0, TRUE, 0, TRUE);
            $oPDF->SetFont('dejavusans','',7);
            $coordY+=$oPDF->getFontSize()+2;
			$oPDF->Line($coordX, $coordY, $coordX+169, $coordY, $style=$style6);//bottom line
			
            $y_2nd = $coordY;
	        //Earnings COLUMN
	        	//head
	        	$nodays = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['earning']['totalDays'];
	        	if($nodays!='' AND $nodays > 0){
		        	$oPDF->SetXY($coordX+20, $coordY);
		            $oPDF->MultiCell(58, 2, "Rate",0,'L',1);
		            $oPDF->SetXY($coordX+40, $coordY);
		            $oPDF->MultiCell(20, 2, "Days",0,'L',1);
		            $coordY+=$oPDF->getFontSize();
	        	}
	        	//Basic Details
	            $oPDF->SetXY($coordX+2, $coordY);
	            $basicPAY = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['earning']['Regulartime'];
            	IF($basicPAY != '' AND $basicPAY > 0){
		            $oPDF->MultiCell(58, 2, "BASIC",0,'L',1);
		            if($nodays!='' AND $nodays > 0){
			            $oPDF->SetXY($coordX+20, $coordY);
				        $oPDF->MultiCell(20, 2, number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['earning']['basic'],2),0,'L',1);
			            $oPDF->SetXY($coordX+40, $coordY);
				        $oPDF->MultiCell(20, 2, $nodays,0,'L',1);
		            }
		            $oPDF->SetXY($coordX+60, $coordY);
		            $oPDF->MultiCell(24.5, 2, number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['earning']['Regulartime'],2),0,'R',1);
		            $coordY+=$oPDF->getFontSize();
            	}
            	
        		//BONUS PAY
				$bonusPAY = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['Bonus Pay'];
	            IF($bonusPAY != '' AND $bonusPAY > 0){
		            $oPDF->MultiCell(58, 2, "BONUS PAY",0,'L',1);
		            $oPDF->SetXY($coordX+60, $coordY);
		            $oPDF->MultiCell(24.5, 2, number_format($bonusPAY,$objClsMngeDecimal->getFinalDecimalSettings()),0,'R',1);
		            $coordY+=$oPDF->getFontSize();
	            }
	            
	            //COLA Details
	            $colaAmount = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['earning']['COLA'];
	            if($colaAmount != '' AND $colaAmount > 0){
		            $oPDF->SetXY($coordX+2, $coordY);
		            $oPDF->MultiCell(58, 2, "COLA",0,'L',1);
		            $oPDF->SetXY($coordX+20, $coordY);
		        	$oPDF->MultiCell(20, 2, number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['earning']['COLAperDay'],2),0,'L',1);
		            $oPDF->SetXY($coordX+40, $coordY);
		            $oPDF->MultiCell(20, 2, $nodays,0,'L',1);
		            $oPDF->SetXY($coordX+60, $coordY);
		            $oPDF->MultiCell(24.5, 2, number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['earning']['COLA'],2),0,'R',1);
		            $coordY+=$oPDF->getFontSize();
	            }
	            // OT listing
				$psa_OT = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['earning']['OT']['OTDetails'];  
	            if(count($psa_OT)>0){
//	            	@note: Hide for january payroll
					$oPDF->SetXY($coordX+2, $coordY);
			        $oPDF->MultiCell(58, 2, 'OverTime',0,'L',1);
					$coordY+=$oPDF->getFontSize();
	                for($k=0;$k<count($psa_OT);$k++){
		                $oPDF->SetXY($coordX+4, $coordY);
		                $oPDF->MultiCell(22, 2, substr(trim($psa_OT[$k]['ot_name']),0,12),0,'L',1);
		                $oPDF->SetXY($coordX+22, $coordY);
		                $oPDF->MultiCell(8, 2,' H ',0,'R',1);
		                $oPDF->SetXY($coordX+14, $coordY);
		                $oPDF->MultiCell(28, 2,$psa_OT[$k]['totaltimehr'].' = ',0,'R',1);
		                $oPDF->SetXY($coordX+40, $coordY);
		                $oPDF->MultiCell(28, 2, number_format($psa_OT[$k]['otamount'],$objClsMngeDecimal->getFinalDecimalSettings()),0,'L',1);
		                $coordY+=$oPDF->getFontSize();
	                }
	                $oPDF->SetXY($coordX+2, $coordY);
		            $oPDF->MultiCell(58, 2, 'Total OverTime',0,'L',1);
		            $oPDF->SetXY($coordX+60, $coordY);
		            $oPDF->MultiCell(24.5, 2, number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['earning']['OT']['TotalallOT'],2),0,'R',1);
		            $coordY+=$oPDF->getFontSize();
	            }
				// Amendment listing
				$psa_amendment = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['amendments'][0];
	            for($a=0;$a<count($psa_amendment) ;$a++){
	                IF ($psa_amendment[$a]['psa_type']==1) {
	                	IF($psa_amendment[$a]['amendemp_amount'] != 0){
			                $oPDF->SetXY($coordX+2, $coordY);
			                $oPDF->MultiCell(58, 2, $psa_amendment[$a]['psa_name'],0,'L',1);
			                $oPDF->SetXY($coordX+60, $coordY);
			                $oPDF->MultiCell(24.5, 2, number_format($psa_amendment[$a]['amendemp_amount'],2),0,'R',1);
			                $coordY+=$oPDF->getFontSize();
	                	}
	                }
	            }
	            // benifits listing
	            $psa_benifits = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['benefits'];            
	            if(count($psa_benifits)>0){
	                for($v=0;$v<count($psa_benifits);$v++){
	                	if($psa_benifits[$v]['psa_type']!=2){
	                		IF($psa_benifits[$v]['ben_payperday'] != 0){
				                $oPDF->SetXY($coordX+2, $coordY);
				                $oPDF->MultiCell(58, 2, $psa_benifits[$v]['psa_name'],0,'L',1);
				                $oPDF->SetXY($coordX+60, $coordY);
				                $oPDF->MultiCell(24.5, 2, number_format($psa_benifits[$v]['ben_payperday'],2),0,'R',1);
				                $coordY+=$oPDF->getFontSize();
	                		}
	                	}
	                }
	            }
	            
	            $oPDF->SetXY($coordX, $end-5);
	            $oPDF->MultiCell(60, 2, "TOTAL EARNINGS :",0,'R',1);
	            $oPDF->SetXY($coordX+60, $end-5);
	            $oPDF->MultiCell(24.5, 2, number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['TotalEarning_payslip'],2),0,'R',1);
	            $coordY+=$oPDF->getFontSize();
	            
	            $oPDF->Line($coordX+60, $y+5, $coordX+60, $end-1, $style = $style5);//left line
	            $oPDF->Line($coordX+84.5, $y+1, $coordX+84.5, $end-1, $style = $style6);//middle line
	            $oPDF->Line($coordX+146, $y+5, $coordX+146, $end-1, $style = $style5);//right line
	            $oPDF->Line($coordX, $end, $coordX+169, $end, $style=$style6);//bottom line
	            
	        //Deduction COLUMN
	            $coordY = $y_2nd;
	            $taxwh = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['W/H Tax'];
				if($taxwh != '0.00' AND $taxwh > 0){
		            $oPDF->SetXY($coordX+87, $coordY);
		            $oPDF->MultiCell(60, 2, "W/H TAX",0,'L',1);
		            $oPDF->SetXY($coordX+146, $coordY);
		            $oPDF->MultiCell(24, 2, number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['W/H Tax'],2),0,'R',1);
		            $coordY+=$oPDF->getFontSize()+0;
				}
				$HDMF = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['deduction']['Pag-ibig'];
				IF($HDMF > 0 ){
		            $oPDF->SetXY($coordX+87, $coordY);
		            $oPDF->MultiCell(60, 2, "HDMF",0,'L',1);
		            $oPDF->SetXY($coordX+146, $coordY);
		            $oPDF->MultiCell(24, 2, number_format($HDMF,2),0,'R',1);
		            $coordY+=$oPDF->getFontSize()+0;
				}
				$PHIC = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['deduction']['PhilHealth'];
				IF($PHIC > 0){
		            $oPDF->SetXY($coordX+87, $coordY);
		            $oPDF->MultiCell(60, 2, "PHIC",0,'L',1);
		            $oPDF->SetXY($coordX+146, $coordY);
		            $oPDF->MultiCell(24, 2, number_format($PHIC,2),0,'R',1);
		            $coordY+=$oPDF->getFontSize()+0;
				}
				$SSS = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['deduction']['SSS'];
				IF($SSS > 0){
		            $oPDF->SetXY($coordX+87, $coordY);
		            $oPDF->MultiCell(60, 2, "SSS",0,'L',1);
		            $oPDF->SetXY($coordX+146, $coordY);
		            $oPDF->MultiCell(24, 2, number_format($SSS,2),0,'R',1);
		            $coordY+=$oPDF->getFontSize()+0;
				}
	            // Leave/TA Listing
		        $psa_ta = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['TUA']['TADetails'];
	        	$total_ta = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['TUA']['TotalLeave'];
				IF(count($psa_ta)>0){
	            	IF($total_ta != 0){
//	            	@note: hide for January Payroll
					$oPDF->SetXY($coordX+87, $coordY);
	                $oPDF->MultiCell(60, 2, 'TA Deduction',0,'L',1);
	                $coordY+=$oPDF->getFontSize();
	            	FOR($k=0;$k<count($psa_ta);$k++){
		            	IF($psa_ta[$k]['ta_name']!='Custom Days'){
		            		$oPDF->SetXY($coordX+90, $coordY);
				            $oPDF->MultiCell(25, 2, substr(trim($psa_ta[$k]['ta_name']),0,12),0,'L',1);
				            $oPDF->SetXY($coordX+95, $coordY);
				            $oPDF->MultiCell(20, 2,$psa_ta[$k]['ratetype'],0,'R',1);
				            $oPDF->SetXY($coordX+100, $coordY);
				            $oPDF->MultiCell(28, 2, number_format($psa_ta[$k]['totaltimehr'],$objClsMngeDecimal->getFinalDecimalSettings()).' = ',0,'R',1);
				            $oPDF->SetXY($coordX+126, $coordY);
				            $oPDF->MultiCell(28, 2, number_format($psa_ta[$k]['taamount'],$objClsMngeDecimal->getFinalDecimalSettings()),0,'L',1);
				            $coordY+=$oPDF->getFontSize();
	            		}
	                }
	                $oPDF->SetXY($coordX+87, $coordY);
	                $oPDF->MultiCell(60, 2, 'Total TA Deduction',0,'L',1);
	                $oPDF->SetXY($coordX+146, $coordY);
	                $oPDF->MultiCell(24, 2, number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['TUA']['TotalLeave'],2),0,'R',1);
	                $coordY+=$oPDF->getFontSize();
	            	}
	            }
				
	            //amendment deduction
	            $psa_amendment = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['amendments'][0];
	            if(count($psa_amendment)>0){
	                foreach($psa_amendment as $key => $val){
	                    if ($val['psa_type']==2) {
	                    	IF($val['amendemp_amount'] > 0){
			                    $oPDF->SetXY($coordX+87, $coordY);
			                    $oPDF->MultiCell(60, 2, $val['psa_name'],0,'L',1);
			                    $oPDF->SetXY($coordX+146, $coordY);
			                    $oPDF->MultiCell(24, 2, number_format($val['amendemp_amount'],2),0,'R',1);
			                    $coordY+=$oPDF->getFontSize();
	                    	}
	                    }
	                }
	            }
	            
	            // Benifits Deduction
        		$psa_benifits = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['benefits'];            
	            if(count($psa_benifits)>0){
	                for($v=0;$v<count($psa_benifits);$v++){
	                	if($psa_benifits[$v]['psa_type']!=1){
	                		IF($psa_benifits[$v]['ben_payperday'] > 0){
				                $oPDF->SetXY($coordX+87, $coordY);
				                $oPDF->MultiCell(60, 2, $psa_benifits[$v]['psa_name'],0,'L',1);
				                $oPDF->SetXY($coordX+146, $coordY);
				                $oPDF->MultiCell(24, 2, number_format($psa_benifits[$v]['ben_payperday'],2),0,'R',1);
				                $coordY+=$oPDF->getFontSize();
	                		}
	                	}
	                }
	            }
	            
	            //Loan listing
				$loan_info_ = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['government_regular'];
				for($v=0;$v<count($loan_info_);$v++){
	                $oPDF->SetXY($coordX+87, $coordY);
	                $oPDF->MultiCell(60, 2, $loan_info_[$v]['psa_name'],0,'L',1);
	                $oPDF->SetXY($coordX+121, $coordY);
                	$oPDF->MultiCell(30, 2, " Bal: ".number_format($loan_info_[$v]['loan_balance'],2),0,'L',1);
	                $oPDF->SetXY($coordX+146, $coordY);
	                $oPDF->MultiCell(24, 2, number_format($loan_info_[$v]['loan_payperperiod'],2),0,'R',1);
	                $coordY+=$oPDF->getFontSize()+0;
	            }
	            
	            $oPDF->SetXY($coordX+87, $end-5);
	            $oPDF->MultiCell(59, 2, "TOTAL DEDUCTIONS :",0,'R',1);
	            $oPDF->SetXY($coordX+146, $end-5);
	            $oPDF->MultiCell(24, 2, number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['Deduction'],2),0,'R',1);
	            $coordY+=$oPDF->getFontSize()+0;
	
	            
	         //bottom
	            $coordY=$end;
	            $oPDF->Line($coordX+84.5, $coordY+1, $coordX+84.5, $end+21,$style = $style6);//bottom middle line
	            $oPDF->Line($coordX+170, $coordYtop, $coordX+170, $end+21, $style = $style4);//out right line
	            
	            $oPDF->SetXY($coordX, $coordY);
	            $oPDF->MultiCell(16, 2, "BANK  :",0,'L',1);
	            $oPDF->SetXY($coordX+15, $coordY);
	            $oPDF->MultiCell(100, 2, $oData[$i]['paystubdetails']['empinfo']['banklist_name'].' / '.$oData[$i]['paystubdetails']['empinfo']['bankiemp_acct_no'],0,'L',1);
	            
	            $oPDF->SetFont('dejavusans','B',7);
	            $oPDF->SetXY($coordX+93, $coordY);
	            $oPDF->MultiCell(25, 2,'NET PAY',0,'L',1);
	            $oPDF->SetXY($coordX+120, $coordY);
	            $oPDF->MultiCell(25, 2, ":  ".number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['Net Pay'],2),0,'L',1);
	            $coordY+=$oPDF->getFontSize()+1;
	            $oPDF->Line($coordX+93, $coordY+1, $coordX+160, $coordY+1, $style=$style6);//netpay line
//	            @note: hide for January Payroll
	            $leave_record_ = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['leave_record'];
	            $coordY_leave = $coordY;
				IF(count($leave_record_)>0){
					$oPDF->SetFont('dejavusans','B',7);
					$oPDF->SetXY($coordX+40, $coordY);
		            $oPDF->MultiCell(15, 2, "Credit",0,'L',1);
		            $oPDF->SetXY($coordX+55, $coordY);
		            $oPDF->MultiCell(15, 2, "Bal",0,'L',1);
		            $oPDF->SetXY($coordX+70, $coordY);
		            $oPDF->MultiCell(15, 2,'Taken',0,'L',1);
		            $coordY+=$oPDF->getFontSize()-2;
		            $oPDF->SetFont('dejavusans','',7);
					FOR($v=0;$v<count($leave_record_);$v++){
						IF($leave_record_[$v]['empleave_credit'] > 0){
			                $oPDF->SetXY($coordX, $coordY_leave+3);
			                $oPDF->MultiCell(40, 2, $leave_record_[$v]['leave_name'],0,'L',1);
			                $oPDF->SetXY($coordX+40, $coordY_leave+3);
			                $oPDF->MultiCell(15, 2, number_format($leave_record_[$v]['empleave_credit'],2),0,'L',1);
			                $oPDF->SetXY($coordX+55, $coordY_leave+3);
			                $oPDF->MultiCell(15, 2, number_format($leave_record_[$v]['empleave_available_day'],2),0,'L',1);
			                $oPDF->SetXY($coordX+70, $coordY_leave+3);
			                $oPDF->MultiCell(15, 2, number_format($leave_record_[$v]['empleave_used_day'],2),0,'L',1);
			                $coordY_leave+=$oPDF->getFontSize()+0;
						}
		            }
				}
	            $oPDF->SetFont('dejavusans','B',7);
	            $oPDF->SetXY($coordX+123, $coordY);
	            $oPDF->MultiCell(30, 2, "YTD",0,'L',1);
				$coordY+=$oPDF->getFontSize()+.5;
	            $oPDF->SetFont('dejavusans','',7);
	            $oPDF->SetXY($coordX+93, $coordY);
	            $oPDF->MultiCell(30, 2, "GROSS PAY",0,'L',1);
	            $oPDF->SetXY($coordX+120, $coordY);
				//printa($oData); exit;
				$sqlYear = "SELECT payperiod_period_year,payperiod_period,payperiod_freq FROM payroll_pay_period WHERE payperiod_id='".$oData[$i]['payperiod_id']."'";
				$getYear = $this->conn->Execute($sqlYear);
	            $ytdgrosspay = $this->getYTD($oData[$i]['emp_id'],4,$getYear->fields['payperiod_period_year'],$getYear->fields['payperiod_period'],$getYear->fields['payperiod_freq']);
	            $oPDF->MultiCell(25, 2, ":   ".number_format($ytdgrosspay['ytdamount'],2),0,'L',1);
	            $coordY+=$oPDF->getFontSize()+0;
	            
	            $oPDF->SetXY($coordX+93, $coordY);
	            $oPDF->MultiCell(30, 2, "TAXABLE GROSS",0,'L',1);
	            $oPDF->SetXY($coordX+120, $coordY);
	            $ytdtaxgross = $this->getYTD($oData[$i]['emp_id'],30,$getYear->fields['payperiod_period_year'],$getYear->fields['payperiod_period'],$getYear->fields['payperiod_freq']);
	            $oPDF->MultiCell(25, 2, ":   ".number_format($ytdtaxgross['ytdamount'],2),0,'L',1);
	            $coordY+=$oPDF->getFontSize()+0;
	            
	            $oPDF->SetFont('dejavusans','',6.5);
	            $oPDF->SetXY($coordX+93, $coordY);
	            $oPDF->MultiCell(30, 2, "Statutory Contribution",0,'L',1);
	            $oPDF->SetXY($coordX+120, $coordY);
	            $oPDF->SetFont('dejavusans','',7);
	            $ytdstat = $this->getYTD($oData[$i]['emp_id'],27,$getYear->fields['payperiod_period_year'],$getYear->fields['payperiod_period'],$getYear->fields['payperiod_freq']);
	            $oPDF->MultiCell(25, 2, ":   ".number_format($ytdstat['ytdamount'],2),0,'L',1);
	            $coordY+=$oPDF->getFontSize()+0;
            
	            $oPDF->SetXY($coordX+93, $coordY);
	            $oPDF->MultiCell(30, 2, "W/H TAX",0,'L',1);
	            $oPDF->SetXY($coordX+120, $coordY);
	            $ytdwhtax = $this->getYTD($oData[$i]['emp_id'],8,$getYear->fields['payperiod_period_year'],$getYear->fields['payperiod_period'],$getYear->fields['payperiod_freq']);
	            $oPDF->MultiCell(25, 2, ":   ".number_format($ytdwhtax['ytdamount'],2),0,'L',1);
	            $coordY+=$oPDF->getFontSize()*4;
	            
	            $oPDF->SetXY($coordX, $coordY+2);
	            $oPDF->MultiCell(19, 2, "TAX STATUS",0,'L',1);
	            $oPDF->SetXY($coordX+19, $coordY+2);
	            $oPDF->MultiCell(90, 2, ': '.$oData[$i]['paystubdetails']['empinfo']['tax_ex_name'],0,'L',1);
	            $coordY+=$oPDF->getFontSize()+7;
	            $oPDF->Line($coordX, $coordY, $coordX+212, $coordY, $style=$style5);//bottom line
	        	$coordY+=$oPDF->getFontSize()+0;
	            if(isset($_GET['email'])){
			        $image_url = BASE_URL.'../includes/jscript/ThemeOffice/images/send_email.jpg';
		        	$oPDF->Image($image_url, 180, 10, 25, 8, 'JPG', BASE_URL.'index.php?statpos=send&send='.$_GET['email'], '', true, 150, '', false, false, 1, false, false, false);
		        	$empid = $oData[$i]['paystubdetails']['empinfo']['emp_id'];
		        	$to_q = "select b.pi_emailone from emp_masterfile a join emp_personal_info b on(b.pi_id=a.pi_id) where emp_id='$empid'";
					$to_r = $this->conn->Execute($to_q);
					$to = $to_r->fields['pi_emailone'];
		        	$oPDF->SetFont('dejavusans','',8);
		        	$oPDF->SetXY(2, $coordY);
		        	$oPDF->MultiCell(200, 2,'Email Address : '.$to,0,'L',false,1);
			    }
        	}
        }
        // get the pdf output
        $output = $oPDF->Output("payslip_".$oData['paystubdetails']['empinfo']['fullname'].date('Y-m-d').".pdf");
        if(!empty($output)){
            return $output;
        }
    }
	/**
     * @note: Get YTD values
     * @param $emp_id_
     * @param $psa_id_
     * @param $paystub_id_
     */
    
	function getFEAPPayslipPDF($oData = array()) {
//		printa($oData);exit;
        $orientation = 'P'; // P for Portrait, L for Landscape
		$unit = 'mm'; 		// (string) User measure unit. Possible values are: pt: point, mm: millimeter (default), cm: centimeter, and in: inch
		$format = 'LETTER'; // LETTER, USLETTER, ORGANIZERM (216x279 mm ; 8.50x11.00 in)
		$unicode = true;
		$encoding="UTF-8";
		
        $pdf = new clsPDF($orientation, $unit, $format, $unicode, $encoding);
		$objClsMngeDecimal = new Application();
		
		// remove default header/footer
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		
        //set margins
		$pdf_margin_top = 12;
		$pdf_margin_left_and_right = 22.5;

		$pdf->SetMargins($pdf_margin_left_and_right, $pdf_margin_top, $pdf_margin_left_and_right);
//        $oPDF->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
//        $oPDF->SetHeaderMargin(PDF_MARGIN_HEADER);

        //set auto page breaks
		$pdf_margin_bottom = 0;
		$pdf->SetAutoPageBreak(TRUE, $pdf_margin_bottom);
		
		//set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		
		//set some language-dependent strings
		$pdf->setLanguageArray($l); 
		
		$i = 0;
        $count_emp = 0;
	    if (count($oData) > 0) {
        	for ($i=0; $i < count($oData); $i++) {
				$pdf->AddPage(); // Set initial pdf page
				$pdf->SetFont("dejavuserif", '', 8); // Set Font Type & Size
				if(isset($_GET['email'])){
					$image_url = BASE_URL.'../includes/jscript/ThemeOffice/images/send_email.jpg';
	        		$pdf->Image($image_url, 180, 10, 25, 8, 'JPG', BASE_URL.'index.php?statpos=send&send='.$_GET['email'], '', true, 150, '', false, false, 1, false, false, false);
	        		$empid = $oData[$i]['paystubdetails']['empinfo']['emp_id'];
	        		$to_q = "select b.pi_emailone from emp_masterfile a join emp_personal_info b on(b.pi_id=a.pi_id) where emp_id='$empid'";
					$to_r = $this->conn->Execute($to_q);
					$to = $to_r->fields['pi_emailone'];
					$pdf->SetFont('dejavusans','',8);
	        		$pdf->SetXY(140, 20);
	        		$pdf->MultiCell(200, 2,'Email Address : '.$to,0,'L',false,1);
				}
	        	//used to line style...
		        $style4 = array('dash' => 1,1);
				$style5 = array('dash' => 2,2);
				$style6 = array('dash' => 0);
				
				// set initila coordinates
		        $coordX = $pdf_margin_left_and_right;
		        $coordY = $pdf_margin_top;
		        $coordYtop = 6;
				
		        //Setup Borders
				// Top Line
				$width = $coordX + 78;
				$pdf->Line($coordX,$coordY,$coordX + $width,$coordY,$style5);
				// Bottom Line
				$height = 256.5;
				$total_height = $coordY + $height;
				$pdf->Line($coordX,$total_height,$coordX + $width,$total_height,$style5);
				// Vertical Line
				$middle = 123;
				$pdf->Line($middle,$coordY,$middle,$total_height,$style5);
				
				//header
				$pdf->Ln(10.6);
				$pdf->MultiCell($box_width = 83, $box_height = 0, 'EMPLOYEE PAYSLIP', $border = 0, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		//		$pdf->MultiCell(100 - $box_width, $box_height, 'No.   '.$number, $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
				
				$pdf->Ln(3.7);
				$pdf->MultiCell(100, $box_height,"PAYROLL PERIOD : ".date("m/d/Y",strtotime($oData[$i]['paystubdetails']['paystubdetail']['paystubsched']['payperiod_start_date']))." to ".date("m/d/Y",strtotime($oData[$i]['paystubdetails']['paystubdetail']['paystubsched']['payperiod_end_date'])), $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
				
				$pdf->Ln(3.7);
				$pdf->MultiCell(100, $box_height,"PAYOUT DATE       : ".date("m/d/Y",strtotime($oData[$i]['paystubdetails']['paystubdetail']['paystubsched']['payperiod_trans_date'])), $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
				
				$pdf->Ln(3.7);
				$pdf->MultiCell(100, $box_height,$oData[$i]['paystubdetails']['empinfo']['comp_name'], $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
				
				$pdf->Ln(3.7);
				$pdf->MultiCell($box_width = 18, $box_height, $oData[$i]['paystubdetails']['empinfo']['emp_no'], $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
				$pdf->MultiCell(100 - $box_width, $box_height,strtoupper($oData[$i]['paystubdetails']['empinfo']['fullname']), $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
				
				$pdf->Ln(3.7);
				$pdf->MultiCell($box_width = 51, $box_height, "Dept : ".$oData[$i]['paystubdetails']['empinfo']['ud_name'], $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
				$pdf->MultiCell(100 - $box_width, $box_height, "Exempt Code : ".$oData[$i]['paystubdetails']['empinfo']['taxep_code'], $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
				
				//BODY
				//Leave Balance
				$leave_record_ = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['leave_record'];
				IF(count($leave_record_)>0){
					FOR($v=0;$v<count($leave_record_);$v++){
						IF($leave_record_[$v]['leave_name']=='VL'){
							$vl_credit = number_format($leave_record_[$v]['empleave_creadit'],2);
							$vl_bal = number_format($leave_record_[$v]['empleave_available_day'],2);
							$vl_taken = number_format($leave_record_[$v]['empleave_used_day'],2);
						}
						IF($leave_record_[$v]['leave_name']=='SL'){
							$sl_credit = number_format($leave_record_[$v]['empleave_creadit'],2);
							$sl_bal = number_format($leave_record_[$v]['empleave_available_day'],2);
							$sl_taken = number_format($leave_record_[$v]['empleave_used_day'],2);
						}
		            }
				}
				//GET MONTHLY
				$salaryType = $oData[$i]['paystubdetails']['empinfo']['salarytype_id'];
				IF($salaryType == '2'){
					$monthlyRATE_ = $this->getMonthly_RATE($oData[$i]['paystubdetails']['empinfo']['emp_id'],$oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['earning']['basic']);
				}ELSE{
					$monthlyRATE_ = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['earning']['basic'];
				}
				$pdf->Ln(17);
				$pdf->MultiCell($box_width = 27, $box_height, 'Salary Rates', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
				$pdf->MultiCell($box_width_2 = 15.5, $box_height, 'Monthly', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
				$pdf->MultiCell($box_width_3 = 24, $box_height, number_format($monthlyRATE_,2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
				$pdf->MultiCell(100 - ($box_width + $box_width_2 + $box_width_3), $box_height, "  VL   ".$vl_bal, $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
				
				$pdf->Ln(3.7);
				$pdf->MultiCell($box_width = 27, $box_height, '', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
				$pdf->MultiCell($box_width_2 = 15.5, $box_height, 'Daily', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
				$pdf->MultiCell($box_width_3 = 24, $box_height, number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['earning']['DailyRate'],2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
				$pdf->MultiCell(100 - ($box_width + $box_width_2 + $box_width_3), $box_height, "  SL   ".$sl_bal, $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
				
				$pdf->Ln(3.7);
				$pdf->MultiCell($box_width = 27, $box_height, '', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
				$pdf->MultiCell($box_width_2 = 15.5, $box_height, 'Hourly', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
				$pdf->MultiCell($box_width_3 = 24, $box_height, number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['earning']['HourlyRate'],2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
				$pdf->MultiCell(100 - ($box_width + $box_width_2 + $box_width_3), $box_height, "", $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
				
				$pdf->Ln(10);
				$pdf->MultiCell($box_width = 23.5, $box_height, '', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
				$pdf->MultiCell($box_width_2 = 25.5, $box_height, 'Attendance', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
				$pdf->MultiCell($box_width_3 = 24, $box_height, 'Earnings', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
				$pdf->MultiCell(100 - ($box_width + $box_width_2 + $box_width_3), $box_height, "Deductions", $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		
				//Basic Income & work days
				$pdf->Ln(7.4);
				$pdf->MultiCell($box_width = 27, $box_height, 'Workdays', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
				$pdf->MultiCell($box_width_2 = 15.5, $box_height, number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['earning']['totalDays'],2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
				$pdf->MultiCell($box_width_3 = 24, $box_height, number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['earning']['Regulartime'],2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
				$pdf->MultiCell(100 - ($box_width + $box_width_2 + $box_width_3), $box_height, "", $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
				
				//Leave/TA Listing Deduction
				$psa_ta = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['TUA']['TADetails'];
			    $total_ta = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['TUA']['TotalLeave'];
		        IF(count($psa_ta)>0){
		            IF($total_ta != 0){
						FOR($k=0;$k<count($psa_ta);$k++){
		            		IF($psa_ta[$k]['ta_name']!='Custom Days'){
		            			$pdf->Ln(3.7);
								$pdf->MultiCell($box_width = 27, $box_height, substr(trim($psa_ta[$k]['ta_name']),0,12), $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
								$pdf->MultiCell($box_width_2 = 15.5, $box_height, number_format($psa_ta[$k]['totaltimehr'],2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
								$pdf->MultiCell($box_width_3 = 24, $box_height, "", $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
								$pdf->MultiCell(90 - ($box_width + $box_width_2 + $box_width_3), $box_height, number_format($psa_ta[$k]['taamount'],2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		            		}
		                }
		            }
		        }
		        
				$pdf->Ln(3.7);
				$pdf->MultiCell($box_width = 100, $box_height, '', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		
				// OT listing
				$psa_OT = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['earning']['OT']['OTDetails'];  
		        IF(count($psa_OT)>0){
		            FOR($k=0;$k<count($psa_OT);$k++){
		            	$pdf->Ln(3.7);
						$pdf->MultiCell($box_width = 27, $box_height, substr(trim($psa_OT[$k]['ot_name']),0,12), $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
						$pdf->MultiCell($box_width_2 = 15.5, $box_height, $psa_OT[$k]['totaltimehr'], $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
						$pdf->MultiCell($box_width_3 = 24, $box_height, $psa_OT[$k]['otamount'], $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
						$pdf->MultiCell(100 - ($box_width + $box_width_2 + $box_width_3), $box_height, "", $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
			        }
		        }
		        
		        $pdf->Ln(3.7);
				$pdf->MultiCell($box_width = 100, $box_height, '', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		
				// OTHER Earning Income
				// benifits listing
		        $psa_benifits = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['benefits'];            
		        IF(count($psa_benifits)>0){
					FOR($v=0;$v<count($psa_benifits);$v++){
		                IF($psa_benifits[$v]['psa_type']!=2){
		                	IF($psa_benifits[$v]['ben_payperday'] != 0){
			                	$pdf->Ln(3.7);
								$pdf->MultiCell($box_width = 42.50, $box_height, substr(trim($psa_benifits[$v]['psa_name']),0,12), $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
								$pdf->MultiCell($box_width_3 = 24, $box_height, number_format($psa_benifits[$v]['ben_payperday'],2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		                	}
		                }
					}
		        }
		        
				// Amendment listing
				$psa_amendment = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['amendments'][0];
		        for($a=0;$a<count($psa_amendment) ;$a++){
		            if ($psa_amendment[$a]['psa_type']==1) {
		            	IF($psa_amendment[$a]['amendemp_amount'] != 0){
			            	$pdf->Ln(3.7);
							$pdf->MultiCell($box_width = 42.50, $box_height, substr(trim($psa_amendment[$a]['psa_name']),0,12), $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
							$pdf->MultiCell($box_width_3 = 24, $box_height, number_format($psa_amendment[$a]['amendemp_amount'],2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		            	}
		            }
		        }
		        
		        $pdf->Ln(3.7);
				$pdf->MultiCell($box_width = 100, $box_height, '', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		        
		        //OTHER DEDUCTION INCOME
		        //W/H TAX
		        $taxwh = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['W/H Tax'];
				$pdf->Ln(3.7);
				$pdf->MultiCell($box_width = 27, $box_height, 'WTAX', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
				$pdf->MultiCell(90 - $box_width, $box_height, number_format($taxwh,2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		
				//SSS
				$SSS = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['deduction']['SSS'];
				$pdf->Ln(3.7);
				$pdf->MultiCell($box_width = 27, $box_height, 'SSS', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
				$pdf->MultiCell(90 - $box_width, $box_height, number_format($SSS,2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		
				//HDMF
				$HDMF = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['deduction']['Pag-ibig'];
				$pdf->Ln(3.7);
				$pdf->MultiCell($box_width = 27, $box_height, 'HDMF', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
				$pdf->MultiCell(90 - $box_width, $box_height, number_format($HDMF,2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		
				//PHIC
				$PHIC = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['deduction']['PhilHealth'];
				$pdf->Ln(3.7);
				$pdf->MultiCell($box_width = 27, $box_height, 'PHILHEALTH', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
				$pdf->MultiCell(90 - $box_width, $box_height, number_format($PHIC,2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
				
				//TOTAL Loan Deduction
				$pdf->Ln(3.7);
				$pdf->MultiCell($box_width = 27, $box_height, 'Loan Deduction', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
				$pdf->MultiCell(90 - $box_width, $box_height, number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['Loan_Total'],2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
				
				//amendment deduction
		        $psa_amendment = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['amendments'][0];
		        if(count($psa_amendment)>0){
					foreach($psa_amendment as $key => $val){
		                IF ($val['psa_type']==2) {
		                	IF($val['amendemp_amount'] > 0){
			                    $pdf->Ln(3.7);
								$pdf->MultiCell($box_width = 42.50, $box_height, substr(trim($val['psa_name']),0,12), $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
								$pdf->MultiCell(90 - $box_width, $box_height, number_format($val['amendemp_amount'],2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		                	}
		                }
		            }
		         }
		         
				// benifits Deduction
		        $psa_benifits = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['benefits'];            
		        IF(count($psa_benifits)>0){
		            FOR($v=0;$v<count($psa_benifits);$v++){
		                IF($psa_benifits[$v]['psa_type']!=1){
		                	IF($psa_benifits[$v]['ben_payperday'] > 0){
		                	$pdf->Ln(3.7);
							$pdf->MultiCell($box_width = 42.50, $box_height, substr(trim($psa_benifits[$v]['psa_name']),0,12), $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
							$pdf->MultiCell(90 - $box_width, $box_height, number_format($psa_benifits[$v]['ben_payperday'],2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		                	}
		            	}	
		            }
		        }
		
				$pdf->Ln(3.7);
				$pdf->MultiCell($box_width = 100, $box_height, '', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
				
				//GRAND TOTALS 
				$pdf->Ln(3.7);
				$pdf->MultiCell($box_width = 27, $box_height, 'TOTALS', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
				$pdf->MultiCell($box_width_2 = 15.5, $box_height, '', $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
				$pdf->MultiCell($box_width_3 = 24, $box_height, number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['TotalEarning_payslip'],2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
				$pdf->MultiCell(90 - ($box_width + $box_width_2 + $box_width_3), $box_height, number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['Deduction'],2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		
				$pdf->Ln(3.7);
				$pdf->MultiCell($box_width = 100, $box_height, '', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
				
				//NETPAY
				$pdf->Ln(3.7);
				$pdf->MultiCell($box_width = 27, $box_height, 'NETPAY', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
				$pdf->MultiCell(90 - $box_width, $box_height, number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['Net Pay'],2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		
				$pdf->Ln(3.7);
				$pdf->MultiCell($box_width = 100, $box_height, '', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		
				$pdf->Ln(3.7);
				$pdf->MultiCell($box_width = 100, $box_height, '',$pdf->Line($coordX,$coordY,$coordX + $width,$coordY,$style5), 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
				
				//LOAN DETAILS
				$pdf->Ln(4.7);
				$pdf->MultiCell($box_width = 100, $box_height, 'LOAN DEDUCTIONS', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
				
				$pdf->Ln(3.7);
				$pdf->MultiCell($box_width = 27.5, $box_height, 'LOAN AMT', $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
				$pdf->MultiCell($box_width_2 = 19, $box_height, 'TOT PAID', $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
				$pdf->MultiCell($box_width_3 = 20, $box_height, "CURR DED", $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
				$pdf->MultiCell(90 - ($box_width + $box_width_2 + $box_width_3), $box_height, "BALANCE", $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
				
				//Loan listing
				$loan_info_ = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['government_regular'];
				FOR($v=0;$v<count($loan_info_);$v++){
					$pdf->Ln(3.7);
					$pdf->MultiCell($box_width = 10, $box_height, substr(trim($loan_info_[$v]['psa_name']),0,3), $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
					$pdf->MultiCell($box_width_2 = 17.5, $box_height, number_format($loan_info_[$v]['loan_principal'],2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
					$pdf->MultiCell($box_width_3 = 19, $box_height, number_format($loan_info_[$v]['loan_ytd'],2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
					$pdf->MultiCell($box_width_4 = 20, $box_height, number_format($loan_info_[$v]['loan_payperperiod'],2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
					$pdf->MultiCell(90 - ($box_width + $box_width_2 + $box_width_3 + $box_width_4), $box_height, number_format($loan_info_[$v]['loan_balance'],2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
				}
		
				$pdf->Ln(5);
				$pdf->MultiCell(90, $box_height, "", $style5, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
				
				$pdf->Ln(3.7);
				$pdf->MultiCell(100, $box_height, "Any discrepancies noted should be cleared with", $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
				
				$pdf->Ln(3.7);
				$pdf->MultiCell(100, $box_height, "Payroll Staff within 3 days.", $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
        	}
	    }
		
        // get the pdf output
        $output = $pdf->Output("payslip_".$oData['paystubdetails']['empinfo']['fullname'].date('Y-m-d').".pdf");
        if (!empty($output)) {
            return $output;
        }
    }
    
	function getYTD($emp_id_ = null, $psa_id_ = null, $year_ = null, $period_ = null, $freq_ = null){
    	/**if (is_null($emp_id_)) { return $arrData; }
		if (is_null($psa_id_) || empty($psa_id_)) { return $arrData; }
    	if (is_null($psa_id_) || empty($psa_id_)) { return $arrData; }
    	$qry[]="a.emp_id = '".$emp_id_."'";
		$qry[]="b.psa_id = '".$psa_id_."'";
		$qry[]="a.paystub_id <= '".$paystub_id_."'";
		// put all query array into one string criteria
		$criteria = " WHERE ".implode(" and ",$qry);
		$qryYTD = "SELECT SUM(b.ppe_amount) as ytdamount
					FROM payroll_pay_stub a
					JOIN payroll_paystub_entry b on(b.paystub_id=a.paystub_id) 
					$criteria
					GROUP BY b.psa_id";
		$varYTD = $this->conn->Execute($qryYTD);
		if(!$varDeducH->EOF){
			$varYTD_ = $varYTD->fields;
			return $varYTD_;
		}**/
    if (is_null($emp_id_)) { return $arrData; }
		if (is_null($psa_id_) || empty($psa_id_)) { return $arrData; }
    	if (is_null($psa_id_) || empty($psa_id_)) { return $arrData; }
    	$qry[]="d.emp_id = '".$emp_id_."'";
        if($psa_id_==4){
    		$qry[]="a.psa_id in (30,27,31)";
    	} else {
			$qry[]="a.psa_id = '".$psa_id_."'";
    	}
		//$qry[]="a.paystub_id <= '".$paystub_id_."'";
		//$qry[]="c.payperiod_period_year = '".$year_."'";
		$qry[]="((c.payperiod_period_year = '".$year_."' and c.payperiod_period < '".$period_."') OR 
				(c.payperiod_period_year = '".$year_."' and c.payperiod_period = '".$period_."' and c.payperiod_freq <= ".$freq_."))";
		// put all query array into one string criteria
		$criteria = " WHERE ".implode(" and ",$qry);
		/**$qryYTD = "SELECT SUM(b.ppe_amount) as ytdamount
					FROM payroll_pay_stub a
					JOIN payroll_paystub_entry b on (b.paystub_id=a.paystub_id)
					JOIN payroll_pay_period c on (c.payperiod_id=a.payperiod_id)
					$criteria
					GROUP BY b.psa_id";**/
		/**$qryYTD = "SELECT entry_amt+amm_amt AS ytdamount
				FROM 
				(SELECT COALESCE(SUM(a.ppe_amount), 0) AS entry_amt FROM payroll_paystub_entry a
				INNER JOIN payroll_pay_stub b ON (b.paystub_id=a.paystub_id)
				INNER JOIN payroll_pay_period c ON (c.payperiod_id=b.payperiod_id)
				INNER JOIN payroll_paystub_report d ON (d.paystub_id=b.paystub_id)
				INNER JOIN payroll_ps_account e ON (e.psa_id=a.psa_id)
				$criteria) entry_tbl,
				
				(select COALESCE(sum(a.amendemp_amount),0) as amm_amt 
				from payroll_ps_amendemp a 
				inner join payroll_ps_amendment b on (b.psamend_id=a.psamend_id) 
				inner join payroll_pay_stub c on (c.paystub_id=a.paystub_id) 
				inner join payroll_pay_period d on (d.payperiod_id=c.payperiod_id)
				INNER JOIN payroll_ps_account e ON (e.psa_id=b.psa_id) 
				WHERE d.payperiod_period_year='{$year_}' AND a.emp_id={$emp_id_} AND b.psa_id='$psa_id_'
				AND a.paystub_id NOT IN (SELECT a.paystub_id FROM payroll_paystub_entry a
				INNER JOIN payroll_pay_stub b ON (b.paystub_id=a.paystub_id)
				INNER JOIN payroll_pay_period c ON (c.payperiod_id=b.payperiod_id)
				INNER JOIN payroll_paystub_report d ON (d.paystub_id=b.paystub_id)
				INNER JOIN payroll_ps_account e ON (e.psa_id=a.psa_id)
				$criteria)) amm_tbl";**/
		$qryYTD = "SELECT SUM(a.ppe_amount) AS ytdamount 
					FROM payroll_paystub_entry a 
					INNER JOIN payroll_pay_stub b ON (b.paystub_id=a.paystub_id) 
					INNER JOIN payroll_pay_period c ON (c.payperiod_id=b.payperiod_id) 
					INNER JOIN payroll_paystub_report d ON (d.paystub_id=b.paystub_id) 
					INNER JOIN payroll_ps_account e ON (e.psa_id=a.psa_id) 
					$criteria";
		$varYTD = $this->conn->Execute($qryYTD);
		if(!$varDeducH->EOF){
			$varYTD_ = $varYTD->fields;
			return $varYTD_;
		}
    }
    	
	function swiftSend($to, $filename, $datapdf, $name, $date, $subject, $cred = array()){
		$body = "<html><head><meta http-equiv=\"Content-Type\" content=\"text/html;charset=utf-8\">
				<title>Thank You</title></head>
				<body><p>
				Dear <strong><u><i>$name</i></u></strong> ,<br /><br />
				Attached is your payslip for the period <strong><u>$date</u></strong>.<br /><br />
				Thank you.</p>
				</body></html>";
		// Create the Transport
		$transport = Swift_SmtpTransport::newInstance($cred['smtp'], $cred['port'])
		  ->setUsername($cred['username'])
		  ->setPassword(clsEncryptHelper::decrypt($cred['password'], BASE_URL))
		  ;
		//printa($cred); exit;
		$fromEmail = (($cred['emailfrom'] != NULL OR $cred['emailfrom'] != '') ? $cred['emailfrom'] : $cred['username']);
		// Create the Mailer using your created Transport
		$mailer = Swift_Mailer::newInstance($transport);
		
		// Create the message
		$message = Swift_Message::newInstance()
		// Give the message a subject
		->setSubject($subject)
		// Set the From address with an associative array
		->setFrom(array($fromEmail => $cred['fullname']))
		// Set the To addresses with an associative array
		->setTo(array($to => $name))
		// Give it a body
		//->setBody('Here is the message itself')
		// And optionally an alternative body
		->addPart($body, 'text/html')
		->attach(Swift_Attachment::newInstance($datapdf, $filename, 'application/pdf'));
		
		// Send the message
		$result = $mailer->send($message);
		if($result) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
    function sendEmail($gData=array(),$oData=array(),$payslipFORMAT){
//    	printa($gData); exit;
    	$user_name =  $_SESSION['admin_session_obj']['user_data']['user_name'];
    	$user_id =  $_SESSION['admin_session_obj']['user_id'];
    	$qry = "select a.*,c.pi_emailone from app_users a 
    			left join emp_masterfile b on (b.emp_id=a.emp_id) 
    			left join emp_personal_info c on (c.pi_id=b.pi_id) 
    			where user_name='$user_name'";
    	$date = date("Y-m-d");
    	$time = date("Y-m-d G:i:s");
    	$result_qry = $this->conn->Execute($qry);
    	$credentials = array(
    						'smtp' => $result_qry->fields['user_smtp']
    						,'port' => $result_qry->fields['user_port']
    						,'username' => $result_qry->fields['user_email']
    						,'password' => $result_qry->fields['user_emailpass']
    						,'fullname' => $result_qry->fields['user_fullname']
    						,'emailfrom' => $result_qry->fields['pi_emailone']
    						);
    	$subject = $gData['email_subject'];
    	$qry_select = "SELECT audittrail_id FROM audittrail ORDER BY audittrail_id DESC LIMIT 1";
    	$qry_result = $this->conn->Execute($qry_select);
    	if($qry_result == NULL){
    		$audit_id = 1;
    	} else {
    		$audit_id = $qry_result->fields['audittrail_id'] + 1;
    	}
    	//Loop for sending payslips
    	$empnum = 0;
    	$error = 0;
	    for($i = 0;$i < count($oData);$i++){
	    	$filename = "PaySlip_".date("M_d",strtotime($oData[$i]['paystubdetails']['paystubdetail']['paystubsched']['payperiod_trans_date'])).".pdf";
	    	$emp_id = $oData[$i]['paystubdetails']['empinfo']['emp_id'];
	    	$empid = $this->filterEmail($emp_id, $gData['chkAttend']);
	    	if($empid == $emp_id){
	    		$to_q = "select b.pi_emailone from emp_masterfile a join emp_personal_info b on(b.pi_id=a.pi_id) where emp_id='$emp_id'";
				$to_r = $this->conn->Execute($to_q);
				$to = $to_r->fields['pi_emailone'];
	    		$fullname = $oData[$i]['paystubdetails']['empinfo']['fullname'];
//				$paydate = date("Md,Y",strtotime($oData[$i]['paystubdetails']['paystubdetail']['paystubsched']['payperiod_start_date'])).' to '.date("Md,Y",strtotime($oData[$i]['paystubdetails']['paystubdetail']['paystubsched']['payperiod_end_date']));
				$paydate = date("M d y",strtotime($oData[$i]['paystubdetails']['paystubdetail']['paystubsched']['payperiod_trans_date']));
	    		if($payslipFORMAT['set_stat_type']=='3'){
					$datapdf = $this->sendPDFResult_Format3Email($oData, $i);
	    		} elseif($payslipFORMAT['set_stat_type']=='2'){
	    			$datapdf = $this->sendFEAPFormatPayslip($oData, $i);
				}else{
					$datapdf = $this->sendEmailPdf($oData, $i);
				}
				//printa(array($to, $filename, $datapdf, $fullname, $paydate, $subject, $credentials)); exit;
				if(!empty($to) || $to != NULL || $to != ''){
					//$message = $this->mail_attachment($to, $filename, $user_email, $datapdf, $fullname, $paydate, $subject);
		    		$message =  $this->swiftSend($to, $filename, $datapdf, $fullname, $paydate, $subject, $credentials);
		    		if($message){
		    			$comment = "Sent to ".$to.".";
		    			if($this->getPayslipPassword($emp_id)==null){
		    				$comment .= " WARNING: This employee has no password set for payslip.";
		    			}
		    			$status = 1;
						$qry_insert2 = "INSERT INTO audittrail_log(audittrail_id,emp_id,comments,send_status) VALUE('$audit_id', '$emp_id','$comment','$status')";
			   			$insert_result2 = $this->conn->Execute($qry_insert2);
			   			$empnum++;
			   			echo '<script language="javascript">
					    document.getElementById("sending_status'.$emp_id.'").innerHTML="SUCCESS";
					    document.getElementById("sending_msg'.$emp_id.'").innerHTML="'.$comment.'";
					    document.getElementById("'.$emp_id.'").className="send_success";
					    </script>';
		    		}
				} else {
					$comment = "ERROR: No Email Address!";
		    		$status = 0;
	    			$qry_insert2 = "INSERT INTO audittrail_log(audittrail_id,emp_id,comments,send_status) VALUE('$audit_id', '$emp_id','$comment','$status')";
			   		$insert_result2 = $this->conn->Execute($qry_insert2);
	    			$error++;
	    			echo '<script language="javascript">
				    document.getElementById("sending_status'.$emp_id.'").innerHTML="FAILED";
				    document.getElementById("sending_msg'.$emp_id.'").innerHTML="ERROR: No Email Address!";
				    document.getElementById("'.$emp_id.'").className="send_failed";
				    </script>';
	    		}
		    	flush();
		    	ob_flush();
		    	sleep(1);
	    	}
	    }
	    echo '<script language="javascript">
	    		var mydiv = document.getElementById("msg");
				mydiv.innerHTML="E-MAIL SUMMARY: Success: '.$empnum.', Failed: '.$error.'&nbsp;&nbsp;";
				mydiv.className="tblListErrMsg";
				var aTag = document.createElement("a");
				aTag.setAttribute("href","'.BASE_URL.'setup.php?statpos=audit_ePayslip&view='.$audit_id.'");
				aTag.innerHTML = "View Logs";
				mydiv.appendChild(aTag);
				</script>';
		flush();
		ob_flush();
    	$qry_insert = "INSERT INTO audittrail(audittrail_id, user_id, subject, date, time, good_rec, bad_rec) VALUE('$audit_id','$user_id','$subject','$date','$time','$empnum','$error')";
    	$insert_result = $this->conn->Execute($qry_insert);
   	if(isset($_SESSION['pData'])){
   		unset($_SESSION['pData']);
    	}
    }
    
    function filterEmail($emp_id, $cData){
    	for($i = 0;$i < count($cData);$i++){;
    		if($cData[$i] == $emp_id){
    			return $cData[$i];
    		}
    	}
    }
    
    function sendEmailPdf($oData = array(),$i){
    	$pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$objClsMngeDecimal = new Application();
    	$pdf->SetProtection($permissions=array('print', 'copy'), $user_pass=$this->getPayslipPassword($oData[$i]['emp_id']), $owner_pass=null, $mode=0, $pubkeys=null);
		$txt = "Received the amount mentioned below as full payment for my salary/wages for the Pay Period";
		//$pdf->SetProtection($permissions=array('print', 'copy'), $user_pass='password', $owner_pass='password', $mode=1, $pubkeys=null);
		
		$pdf->setPrintHeader(false);
		$pdf->AddPage();
		
		//LINES
		$fontstyle = 'helvetica';
		
		$pdf->setXY(5, 36);
		$pdf->Cell(230, 0, '', 'T', 1, 'C', false, '', 0, false, 'C', 'C');
		$pdf->setXY(5, 85.5);
		$pdf->Cell(230, 0, '', 'T', 1, 'C', false, '', 0, false, 'C', 'C');
		$pdf->setXY(239, 30);
		$pdf->Cell(51, 0, '', 'T', 1, 'C', false, '', 0, false, 'C', 'C');
		$pdf->setXY(119, 31);
		$pdf->Cell(0, 53, '', 'L', 1, 'C', false, '', 0, false, 'T', 'C');
		$pdf->setXY(119, 88);
		$pdf->Cell(0, 25, '', 'L', 1, 'C', false, '', 0, false, 'T', 'C');
		$pdf->setXY(131, 91);
		$pdf->Cell(92, 0, '', 'T', 1, 'C', false, '', 0, false, 'C', 'C');
		
		$pdf->SetLineStyle(array('width' => 0.4, 'color' => array(0, 0, 0)));
		$pdf->setXY(5, 29.5);
		$pdf->Cell(230, 0, '', 'T', 1, 'C', false, '', 0, false, 'C', 'C');
		
		//Broken Lines
		$pdf->SetLineStyle(array('width' => 0.1, 'dash' => 3, 'color' => array(0, 0, 0)));
		$pdf->setXY(86, 37);
		$pdf->Cell(0, 48, '', 'L', 1, 'C', false, '', 0, false, 'T', 'C');
		$pdf->setXY(204, 37);
		$pdf->Cell(0, 48, '', 'L', 1, 'C', false, '', 0, false, 'T', 'C');
		$pdf->SetLineStyle(array('width' => 0.1, 'dash' => 1.5, 'color' => array(0, 0, 0)));
		$pdf->setXY(237, 11);
		$pdf->Cell(0, 103, '', 'L', 1, 'C', false, '', 0, false, 'T', 'C');
		
		
		
		
		  //----------------------------------------//
		 //                  HEADER                //
		//----------------------------------------// 
		$comp_name = $oData[$i]['paystubdetails']['empinfo']['comp_name'];
		$comp_add = $oData[$i]['paystubdetails']['empinfo']['comp_add'];
		$pdf->SetFont($fontstyle, 'B', 16);
		$pdf->setXY(5,8);
		$pdf->Cell(0, 0, $comp_name, 0, false, 'L', 0, '', 0, false, 'C', 'C');
		$pdf->SetFont($fontstyle, '', 8);
		$pdf->setXY(5,15);
		$pdf->Cell(0, 0, $comp_add, 0, false, 'L', 0, '', 0, false, 'C', 'C');
		
		$pdf->SetFont($fontstyle, '', 11);
		$pdf->setXY(5, 20);
		$pdf->Cell(0, 0, 'Name/ID : ', 0, false, 'L', 0, '', 0, false, 'C', 'C');
		
		$pdf->setXY(160, 20);
		$pdf->Cell(0, 0, 'PAYDATE    : ', 0, false, 'L', 0, '', 0, false, 'C', 'C');
		$payperiod_trans_date = date("M d, Y",strtotime($oData[$i]['paystubdetails']['paystubdetail']['paystubsched']['payperiod_trans_date']));
		$pdf->setXY(185, 20);
		$pdf->Cell(0, 0, $payperiod_trans_date, 0, false, 'L', 0, '', 0, false, 'C', 'C');
		
		$pdf->setXY(160, 24);
		$pdf->Cell(0, 0, 'DEPT           : ', 0, false, 'L', 0, '', 0, false, 'C', 'C');
		$ud_name = $oData[$i]['paystubdetails']['empinfo']['ud_name'];
		$pdf->setXY(185, 24);
		$pdf->Cell(0, 0, $ud_name, 0, false, 'L', 0, '', 0, false, 'C', 'C');
		
		$pdf->setXY(5, 24);
		$pdf->Cell(0, 0, 'Position  : ', 0, false, 'L', 0, '', 0, false, 'C', 'C');
		$jobpos_name = $oData[$i]['paystubdetails']['empinfo']['jobpos_name'];
		$pdf->setXY(25, 24);
		$pdf->Cell(0, 0, $jobpos_name, 0, false, 'L', 0, '', 0, false, 'C', 'C');
				
		$pdf->SetFont($fontstyle, 'B', 11);
		$fullname = $oData[$i]['paystubdetails']['empinfo']['fullname'];
		$emp_no = $oData[$i]['paystubdetails']['empinfo']['emp_no'];
		$name_id = $fullname." (".$emp_no.")";
		$pdf->setXY(25, 20);
		$pdf->Cell(0, 0, strtoupper($name_id), 0, false, 'L', 0, '', 0, false, 'C', 'C');
		
		//-----------------FIELD--------------//
		
		$pdf->setXY(50, 31);
		$pdf->Cell(0, 0, 'EARNINGS', 0, false, 'L', 0, '', 0, false, 'C', 'C');
		$pdf->setXY(163, 31);
		$pdf->Cell(0, 0, 'DEDUCTIONS', 0, false, 'L', 0, '', 0, false, 'C', 'C');
		
		//--------------DATA EARNINGS--------------//
		$pdf->SetFont($fontstyle, '', 10);
    	$coordX = 6;    	
        $coordY = 37;
	    $nodays = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['earning']['totalDays'];
	    if($nodays!='' AND $nodays > 0){
	    	$pdf->SetXY($coordX+20, $coordY);
	    	$pdf->Cell(0, 0, 'Rate', 0, false, 'L', 0, '', 0, false, 'C', 'C');
	    	$pdf->SetXY($coordX+40, $coordY);
	    	$pdf->Cell(0, 0, 'Days', 0, false, 'L', 0, '', 0, false, 'C', 'C');
	    	$coordY+=3.5;
	    }
	    //Basic Details
	    $pdf->SetXY($coordX, $coordY);
	    $basicPAY = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['earning']['Regulartime'];
	    IF($basicPAY != '' AND $basicPAY > 0){
	    	$pdf->Cell(0, 0, 'BASIC', 0, false, 'L', 0, '', 0, false, 'C', 'C');
	    	if($nodays!='' AND $nodays > 0){
	    		$pdf->SetXY($coordX+32, $coordY);
	    		$pdf->Cell(0, 0, number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['earning']['basic'],2), 0, false, 'L', 0, '', 0, false, 'C', 'C');
	    		$pdf->SetXY($coordX+58, $coordY);
	    		$pdf->Cell(0, 0, $nodays, 0, false, 'L', 0, '', 0, false, 'C', 'C');
	    	}
	    	$pdf->SetXY($coordX+73, $coordY);  
	    	$pdf->Cell(40, 0, number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['earning']['Regulartime'],2), 0, false, 'R', 0, '', 0, false, 'C', 'C');
	    	$coordY+=3.5;
	    }
	    
	    //BONUS PAY
	    $bonusPAY = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['Bonus Pay'];
	    IF($bonusPAY != '' AND $bonusPAY > 0){
	    	$pdf->Cell(0, 0, "BONUS PAY", 0, false, 'L', 0, '', 0, false, 'C', 'C');
	    	$pdf->SetXY($coordX+73, $coordY);
	    	$pdf->Cell(40, 0, number_format($bonusPAY,$objClsMngeDecimal->getFinalDecimalSettings()), 0, false, 'R', 0, '', 0, false, 'C', 'C');
	    	$coordY+=3.5;
	    }
	    
	    //COLA Details
	    $colaAmount = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['earning']['COLA'];
	    if($colaAmount != '' AND $colaAmount > 0){
	    	$pdf->SetXY($coordX, $coordY);
	    	$pdf->Cell(0, 0, "COLA", 0, false, 'L', 0, '', 0, false, 'C', 'C');
	    	$pdf->SetXY($coordX+32, $coordY);
	    	$pdf->Cell(0, 0, number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['earning']['COLAperDay'],2), 0, false, 'L', 0, '', 0, false, 'C', 'C');
	    	$pdf->SetXY($coordX+58, $coordY);
	    	$pdf->Cell(0, 0, $nodays, 0, false, 'L', 0, '', 0, false, 'C', 'C');
	    	$pdf->SetXY($coordX+73, $coordY);
	    	$pdf->Cell(40, 0, number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['earning']['COLA'],2), 0, false, 'R', 0, '', 0, false, 'C', 'C');
	    	$coordY+=3.5;
	    }
	    // OT listing
	    $psa_OT = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['earning']['OT']['OTDetails'];  
	    if(count($psa_OT)>0){
	    	//@note: Hide for january payroll
	    	$pdf->SetXY($coordX, $coordY);
	    	$pdf->Cell(0, 0, 'OverTime', 0, false, 'L', 0, '', 0, false, 'C', 'C');
	    	$coordY+=$pdf->getFontSize();
	    	for($k=0;$k<count($psa_OT);$k++){
	    		$pdf->SetXY($coordX+3, $coordY);
	    		$pdf->Cell(0, 0, trim($psa_OT[$k]['ot_name']), 0, false, 'L', 0, '', 0, false, 'C', 'C');
	    		$pdf->SetXY($coordX+32, $coordY);
	    		$pdf->Cell(0, 0, ' H ', 0, false, 'L', 0, '', 0, false, 'C', 'C');
	    		$pdf->SetXY($coordX+40, $coordY);
	    		$pdf->Cell(20, 0, $psa_OT[$k]['totaltimehr'].' = ', 0, false, 'R', 0, '', 0, false, 'C', 'C');
	    		$pdf->SetXY($coordX+58, $coordY);
	    		$pdf->Cell(0, 0, number_format($psa_OT[$k]['otamount'],2), 0, false, 'L', 0, '', 0, false, 'C', 'C');
	    		$coordY+=3.5;
	    	}
	    	$pdf->SetXY($coordX, $coordY);
	    	$pdf->Cell(0, 0, 'Total OverTime', 0, false, 'L', 0, '', 0, false, 'C', 'C');
	    	$pdf->SetXY($coordX+73, $coordY);
	    	$pdf->Cell(40, 0, number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['earning']['OT']['TotalallOT'],2), 0, false, 'R', 0, '', 0, false, 'C', 'C');
	    	$coordY+=3.5;
	    }
	    // Amendment listing
	    $psa_amendment = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['amendments'][0];
	   
	    for($a=0;$a<count($psa_amendment) ;$a++){
	    	IF ($psa_amendment[$a]['psa_type']==1) {
	    		IF($psa_amendment[$a]['amendemp_amount'] != 0){
	    			$pdf->SetXY($coordX, $coordY);
	    			$pdf->Cell(0, 0, $psa_amendment[$a]['psa_name'], 0, false, 'L', 0, '', 0, false, 'C', 'C');
	    			$pdf->SetXY($coordX+73, $coordY);
	    			$pdf->Cell(40, 0, number_format($psa_amendment[$a]['amendemp_amount'],2), 0, false, 'R', 0, '', 0, false, 'C', 'C');
	    			$coordY+=3.5;
	    		}
	    	}
	    }
	    
	    // benifits listing
	    $psa_benifits = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['benefits'];
	    if(count($psa_benifits)>0){
	    	for($v=0;$v<count($psa_benifits);$v++){
	    		if($psa_benifits[$v]['psa_type']!=2){
	    			IF($psa_benifits[$v]['ben_payperday'] > 0){
	    				$pdf->SetXY($coordX, $coordY);
	    				$pdf->Cell(0, 0, $psa_benifits[$v]['psa_name'], 0, false, 'L', 0, '', 0, false, 'C', 'C');
	    				$pdf->SetXY($coordX+73, $coordY);
	    				$pdf->Cell(40, 0, number_format($psa_benifits[$v]['ben_payperday'],2), 0, false, 'R', 0, '', 0, false, 'C', 'C');
	    				$coordY+=3.5;
	    			}
	    		}
	    	 }
	    }
		$pdf->setXY(52, 79);
		$TotalEarning_payslip = number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['TotalEarning_payslip'],2);
		$pdf->Cell(0, 0, 'TOTAL EARNINGS :', 0, false, 'L', 0, '', 0, false, 'C', 'C');
		$pdf->setXY(79, 79);
		$pdf->Cell(40, 0, $TotalEarning_payslip, 0, false, 'R', 0, '', 0, false, 'C', 'C');
		
		$pdf->setXY(5, 87);
		$pdf->Cell(0, 0, 'BANK :', 0, false, 'L', 0, '', 0, false, 'C', 'C');
		$banklist_name = $oData[$i]['paystubdetails']['empinfo']['banklist_name'];
		$bankiemp_acct_no = $oData[$i]['paystubdetails']['empinfo']['bankiemp_acct_no'];
		$bank = $banklist_name." / ".$bankiemp_acct_no;
		$pdf->setXY(24, 87);
		$pdf->Cell(0, 0, $bank, 0, false, 'L', 0, '', 0, false, 'C', 'C');
		
		$leave_record_ = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['leave_record'];
		$coordY_leave = 92;
		$coordX = 5;
		IF(count($leave_record_)>0){
			$pdf->SetFont($fontstyle,'B',10);
			$pdf->SetXY($coordX+45, $coordY_leave);
			$pdf->Cell(0, 0, "Credit", 0, false, 'L', 0, '', 0, false, 'C', 'C');
			$pdf->SetXY($coordX+65, $coordY_leave);
			$pdf->Cell(0, 0, "Bal", 0, false, 'L', 0, '', 0, false, 'C', 'C');
			$pdf->SetXY($coordX+85, $coordY_leave);
			$pdf->Cell(0, 0, 'Taken', 0, false, 'L', 0, '', 0, false, 'C', 'C');
			$coordY+=3.5;
			$pdf->SetFont($fontstyle,'',10);
			FOR($v=0;$v<count($leave_record_);$v++){
				IF($leave_record_[$v]['empleave_credit'] > 0){
					$pdf->SetXY($coordX, $coordY_leave+3);
					$pdf->Cell(0, 0, $leave_record_[$v]['leave_name'], 0, false, 'L', 0, '', 0, false, 'C', 'C');
					$pdf->SetXY($coordX+45, $coordY_leave+3);
					$pdf->Cell(0, 0, number_format($leave_record_[$v]['empleave_credit'],2), 0, false, 'L', 0, '', 0, false, 'C', 'C');
					$pdf->SetXY($coordX+65, $coordY_leave+3);
					$pdf->Cell(0, 0, number_format($leave_record_[$v]['empleave_available_day'],2), 0, false, 'L', 0, '', 0, false, 'C', 'C');
					$pdf->SetXY($coordX+85, $coordY_leave+3);
					$pdf->Cell(0, 0, number_format($leave_record_[$v]['empleave_used_day'],2), 0, false, 'L', 0, '', 0, false, 'C', 'C');
					$coordY_leave+=3.5;
				}
			}
		}
		$coordY_leave+=3.5;
		$pdf->SetFont($fontstyle,'',11);
		$pdf->setXY(5, $coordY_leave);
		$pdf->Cell(0, 0, 'TAX STATUS   :', 0, false, 'L', 0, '', 0, false, 'C', 'C');
		$tax_ex_name = $oData[$i]['paystubdetails']['empinfo']['tax_ex_name'];
		$pdf->setXY(34, $coordY_leave);
		$pdf->Cell(0, 0, $tax_ex_name, 0, false, 'L', 0, '', 0, false, 'C', 'C');
		$coordY_leave+=5;
		$pdf->SetLineStyle(array('width' => 0.1, 'dash' => 3, 'color' => array(0, 0, 0)));
		$pdf->setXY(5, $coordY_leave);
		$pdf->Cell(285, 0, '', 'T', 1, 'C', false, '', 0, false, 'C', 'C');
		
		//----------------DATA DEDUCTION--------------------//
		
		$coordX = 123;
		$coordY = 37;
		$taxwh = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['W/H Tax'];
		if($taxwh != '0.00' AND $taxwh > 0){
			$pdf->SetXY($coordX, $coordY);
			$pdf->Cell(0, 0, "W/H TAX", 0, false, 'L', 0, '', 0, false, 'C', 'C');
			$pdf->SetXY($coordX+73, $coordY);
			$pdf->Cell(40, 0, number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['W/H Tax'],2), 0, false, 'R', 0, '', 0, false, 'C', 'C');
			$coordY+=3.5;
		}
		$HDMF = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['deduction']['Pag-ibig'];
		IF($HDMF > 0 ){
			$pdf->SetXY($coordX, $coordY);
			$pdf->Cell(0, 0, "HDMF", 0, false, 'L', 0, '', 0, false, 'C', 'C');
			$pdf->SetXY($coordX+73, $coordY);
			$pdf->Cell(40, 0, number_format($HDMF,2), 0, false, 'R', 0, '', 0, false, 'C', 'C');
			$coordY+=3.5;
		}
		$PHIC = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['deduction']['PhilHealth'];
		IF($PHIC > 0){
			$pdf->SetXY($coordX, $coordY);
			$pdf->Cell(0, 0, "PHIC", 0, false, 'L', 0, '', 0, false, 'C', 'C');
			$pdf->SetXY($coordX+73, $coordY);
			$pdf->Cell(40, 0, number_format($PHIC,2), 0, false, 'R', 0, '', 0, false, 'C', 'C');
			$coordY+=3.5;
		}
		$SSS = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['deduction']['SSS'];
		IF($SSS > 0){
			$pdf->SetXY($coordX, $coordY);
			$pdf->Cell(0, 0, "SSS", 0, false, 'L', 0, '', 0, false, 'C', 'C');
			$pdf->SetXY($coordX+73, $coordY);
			$pdf->Cell(40, 0, number_format($SSS,2), 0, false, 'R', 0, '', 0, false, 'C', 'C');
			$coordY+=3.5;
		}
		
		// Leave/TA Listing
		$psa_ta = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['TUA']['TADetails'];
		$total_ta = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['TUA']['TotalLeave'];
		IF(count($psa_ta)>0){
			IF($total_ta != 0){
				//@note: hide for January Payroll
				$pdf->SetXY($coordX, $coordY);
				$pdf->Cell(0, 0, 'TA Deduction', 0, false, 'L', 0, '', 0, false, 'C', 'C');
				$coordY+=3.5;
				FOR($k=0;$k<count($psa_ta);$k++){
					IF($psa_ta[$k]['ta_name']!='Custom Days'){
						$pdf->SetXY($coordX+3, $coordY);
						$pdf->Cell(0, 0, substr(trim($psa_ta[$k]['ta_name']),0,12), 0, false, 'L', 0, '', 0, false, 'C', 'C');
						$pdf->SetXY($coordX+32, $coordY);
						$pdf->Cell(0, 0, $psa_ta[$k]['ratetype'], 0, false, 'L', 0, '', 0, false, 'C', 'C');
						$pdf->SetXY($coordX+40, $coordY);
						$pdf->Cell(0, 0, number_format($psa_ta[$k]['totaltimehr'],2).' = ', 0, false, 'L', 0, '', 0, false, 'C', 'C');
						$pdf->MultiCell(28, 2, number_format($psa_ta[$k]['totaltimehr'],$objClsMngeDecimal->getFinalDecimalSettings()).' = ',0,'R',1);
						$pdf->SetXY($coordX+58, $coordY);
						$pdf->Cell(0, 0, number_format($psa_ta[$k]['taamount'],2), 0, false, 'L', 0, '', 0, false, 'C', 'C');
						$coordY+=3.5;
					}
				}
				$pdf->SetXY($coordX, $coordY);
				$pdf->Cell(0, 0, 'Total TA Deduction', 0, false, 'L', 0, '', 0, false, 'C', 'C');
				$pdf->SetXY($coordX+73, $coordY);
				$pdf->Cell(40, 0, number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['TUA']['TotalLeave'],2), 0, false, 'R', 0, '', 0, false, 'C', 'C');
				$coordY+=3.5;
			}
		}
		
		//amendment deduction
		$psa_amendment = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['amendments'][0];
		if(count($psa_amendment)>0){
			foreach($psa_amendment as $key => $val){
				if ($val['psa_type']==2) {
					IF($val['amendemp_amount'] > 0){
						$pdf->SetXY($coordX, $coordY);
						$pdf->Cell(0, 0, $val['psa_name'], 0, false, 'L', 0, '', 0, false, 'C', 'C');
						$pdf->SetXY($coordX+73, $coordY);
						$pdf->Cell(40, 0, number_format($val['amendemp_amount'],2), 0, false, 'R', 0, '', 0, false, 'C', 'C');
						$coordY+=3.5;
					}
				}
			}
		}
		
		// Benifits Deduction
		$psa_benifits = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['benefits'];
		if(count($psa_benifits)>0){
			for($v=0;$v<count($psa_benifits);$v++){
				if($psa_benifits[$v]['psa_type']!=1){
					IF($psa_benifits[$v]['ben_payperday'] > 0){
						$pdf->SetXY($coordX, $coordY);
						$pdf->Cell(0, 0, $psa_benifits[$v]['psa_name'], 0, false, 'L', 0, '', 0, false, 'C', 'C');
						$pdf->SetXY($coordX+73, $coordY);
						$pdf->Cell(40, 0, number_format($psa_benifits[$v]['ben_payperday'],2), 0, false, 'R', 0, '', 0, false, 'C', 'C');
						$coordY+=3.5;
					}
				}
			}
		}
		
		//Loan listing
		$loan_info_ = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['government_regular'];
		for($v=0;$v<count($loan_info_);$v++){
			$pdf->SetXY($coordX, $coordY);
			$pdf->Cell(0, 0, $loan_info_[$v]['psa_name'], 0, false, 'L', 0, '', 0, false, 'C', 'C');
			$pdf->SetXY($coordX+40, $coordY);
			$pdf->Cell(0, 0, "Bal: ".number_format($loan_info_[$v]['loan_balance'],2), 0, false, 'L', 0, '', 0, false, 'C', 'C');
			$pdf->SetXY($coordX+73, $coordY);
			$pdf->Cell(40, 0, number_format($loan_info_[$v]['loan_payperperiod'],2), 0, false, 'R', 0, '', 0, false, 'C', 'C');
			$coordY+=3.5;
		}	
		
		$pdf->setXY(161, 79);
		$Deduction = number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['Deduction'],2);
		$pdf->Cell(0, 0, 'TOTAL DEDUCTIONS :', 0, false, 'L', 0, '', 0, false, 'C', 'C');
		$pdf->setXY(196, 79);
		$pdf->Cell(40, 0, $Deduction, 0, false, 'R', 0, '', 0, false, 'C', 'C');
		//-------------------------------------------------------//
		$sqlYear = "SELECT payperiod_period_year,payperiod_period,payperiod_freq FROM payroll_pay_period WHERE payperiod_id='".$oData[$i]['payperiod_id']."'";
		$getYear = $this->conn->Execute($sqlYear);
		$pdf->setXY(132, 94);
		$pdf->Cell(0, 0, 'GROSS PAY', 0, false, 'L', 0, '', 0, false, 'C', 'C');
		//$ytdgrosspay_ = $this->getYTD($oData[$i]['emp_id'],4,$oData[$i]['paystub_id']);
		$ytdgrosspay_ = $this->getYTD($oData[$i]['emp_id'],4,$getYear->fields['payperiod_period_year'],$getYear->fields['payperiod_period'],$getYear->fields['payperiod_freq']);
		$ytdgrosspay = number_format($ytdgrosspay_['ytdamount'],2);
		$pdf->setXY(169, 94);
		$pdf->Cell(0, 0, ':  '.$ytdgrosspay, 0, false, 'L', 0, '', 0, false, 'C', 'C');
		
		$pdf->setXY(132, 97.5);
		$pdf->Cell(0, 0, 'TAXABLE GROSS', 0, false, 'L', 0, '', 0, false, 'C', 'C');
		//$ytdtaxgross_ = $this->getYTD($oData[$i]['emp_id'],30,$oData[$i]['paystub_id']);
		$ytdtaxgross_ = $this->getYTD($oData[$i]['emp_id'],30,$getYear->fields['payperiod_period_year'],$getYear->fields['payperiod_period'],$getYear->fields['payperiod_freq']);
		$ytdtaxgross = number_format($ytdtaxgross_['ytdamount'],2);
		$pdf->setXY(169, 97.5);
		$pdf->Cell(0, 0, ':  '.$ytdtaxgross, 0, false, 'L', 0, '', 0, false, 'C', 'C');
		
		$pdf->SetFont($fontstyle, '', 9.5);
		$pdf->setXY(132, 101);
		$pdf->Cell(0, 0, 'Statutory Contribution', 0, false, 'L', 0, '', 0, false, 'C', 'C');
		//$ytdtaxgross_ = $this->getYTD($oData[$i]['emp_id'],30,$oData[$i]['paystub_id']);
		$ytdstat_ = $this->getYTD($oData[$i]['emp_id'],27,$getYear->fields['payperiod_period_year'],$getYear->fields['payperiod_period'],$getYear->fields['payperiod_freq']);
		$ytdstat = number_format($ytdstat_['ytdamount'],2);
		$pdf->setXY(169, 101);
		$pdf->SetFont($fontstyle, '', 11);
		$pdf->Cell(0, 0, ':  '.$ytdstat, 0, false, 'L', 0, '', 0, false, 'C', 'C');
		
		$pdf->setXY(132, 104.5);
		$pdf->Cell(0, 0, 'W/H TAX', 0, false, 'L', 0, '', 0, false, 'C', 'C');
		//$ytdwhtax_ = $this->getYTD($oData[$i]['emp_id'],8,$oData[$i]['paystub_id']);
		$ytdwhtax_ = $this->getYTD($oData[$i]['emp_id'],8,$getYear->fields['payperiod_period_year'],$getYear->fields['payperiod_period'],$getYear->fields['payperiod_freq']);
		$ytdwhtax = number_format($ytdwhtax_['ytdamount'],2);
		$pdf->setXY(169, 104.5);
		$pdf->Cell(0, 0, ':  '.$ytdwhtax, 0, false, 'L', 0, '', 0, false, 'C', 'C');
		
		$pdf->SetFont($fontstyle, 'B', 10);
		$Net_Pay = number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['Net Pay'],2);
		$pdf->setXY(132, 86.5);
		$pdf->Cell(0, 0, 'NET PAY', 0, false, 'L', 0, '', 0, false, 'C', 'C');
		$pdf->setXY(169, 86.5);
		$pdf->Cell(0, 0, ':  '.$Net_Pay, 0, false, 'L', 0, '', 0, false, 'C', 'C');
		
		$pdf->setXY(173, 91);
		$pdf->Cell(0, 0, 'YTD', 0, false, 'L', 0, '', 0, false, 'C', 'C');
		
		
		//-----------------------------------RIGHT----------------------------------------------//
		$pdf->SetFont($fontstyle, 'B', 8);
				
		$pdf->setXY(237,10);
		$pdf->MultiCell (55, 0, $comp_name, 0, 'C', false, 0, '', '', true, 0, false, true, 0, 'T', false);
		
		$pdf->SetFont($fontstyle, '', 9);
		$pdf->setXY(239,20);
		$pdf->Cell(0, 0, 'RECEIVED BY :', 0, false, 'L', 0, '', 0, false, 'C', 'C');
		
		
		$pdf->setXY(238,31);
		$pdf->Cell(0, 0, $fullname, 0, false, 'L', 0, '', 0, false, 'C', 'C');
		
		$pdf->setXY(238,34);
		$pdf->Cell(0, 0, 'Emp No. :', 0, false, 'L', 0, '', 0, false, 'C', 'C');
		$pdf->setXY(253,34);
		$pdf->Cell(0, 0, $emp_no, 0, false, 'L', 0, '', 0, false, 'C', 'C');
		
		$pdf->setXY(238,37);
		$pdf->Cell(0, 0, 'DATE : _______________________', 0, false, 'L', 0, '', 0, false, 'C', 'C');
		
		$pdf->SetFont($fontstyle, '', 9);
		$pdf->setXY(238,45);
		$pdf->MultiCell (55, 0, $txt, 0, 'J', false, 0, '', '', true, 0, false, true, 0, 'M', false);
		$pdf->SetFont($fontstyle, 'B', 11);
		$pdf->setXY(245,59);
		$paydate = date("Md,Y",strtotime($oData[$i]['paystubdetails']['paystubdetail']['paystubsched']['payperiod_start_date'])).' to '.date("Md,Y",strtotime($oData[$i]['paystubdetails']['paystubdetail']['paystubsched']['payperiod_end_date']));
		$pdf->Cell(0, 0, $paydate, 0, false, 'C', 0, '', 0, false, 'C', 'C');
		
		$pdf->SetFont($fontstyle, '', 10);
		$pdf->setXY(238,69);
		$pdf->Cell(0, 0, 'TOTAL INCOME :', 0, false, 'L', 0, '', 0, false, 'C', 'C');
		$pdf->setXY(239,73);
		$pdf->Cell(0, 0, 'P', 0, false, 'L', 0, '', 0, false, 'C', 'C');
		$pdf->setXY(239,73);
		$pdf->Cell(54, 0, $TotalEarning_payslip, 0, false, 'R', 0, '', 0, false, 'C', 'C');
		
		$pdf->setXY(238,80);
		$pdf->Cell(0, 0, 'TOTAL DEDUCTIONS :', 0, false, 'L', 0, '', 0, false, 'C', 'C');
		$pdf->setXY(239,84);
		$pdf->Cell(0, 0, 'P', 0, false, 'L', 0, '', 0, false, 'C', 'C');
		$pdf->setXY(239,84);
		$pdf->Cell(54, 0, $Deduction, 0, false, 'R', 0, '', 0, false, 'C', 'C');
		
		$pdf->setXY(238,91);
		$pdf->Cell(0, 0, 'TAKE HOME PAY :', 0, false, 'L', 0, '', 0, false, 'C', 'C');
		$pdf->SetFont($fontstyle, 'B', 10);
		$pdf->setXY(239,95);
		$pdf->Cell(0, 0, 'P', 0, false, 'L', 0, '', 0, false, 'C', 'C');
		$pdf->setXY(239,95);
		$pdf->Cell(54, 0, $Net_Pay, 0, false, 'R', 0, '', 0, false, 'C', 'C');
		
		$data = $pdf->Output("Payslip.pdf","S");
		//$data = base64_encode($data);
		return $data;
    }
    
	function getTableList_Emp() {
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
				$qry[] = "(pi_lname like '%$search_field%' || pi_fname like '%$search_field%' || post_name like '%$search_field%')";
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
        $qry[] = "empinfo.emp_stat in ('1','7','10')";
		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" where ".implode(" AND ",$qry):"";
		// Sort field mapping
		$arrSortBy = array(
		 "chkbox" => "chkbox"
		,"emp_idnum" => "emp_idnum"
		,"pi_lname" => "pi_lname"
		,"pi_fname" => "pi_fname"
		,"post_name" => "post_name"
		,"ud_name" => "ud_name"
		);

		if (isset($_GET['sortby'])) {
			$strOrderBy = " ORDER BY ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		} else {
			$strOrderBy = " ORDER BY e.pi_lname";
		}

		//@note: this is used to count and check all the checkbox.
		//@note set t1 = 0
		$sql = "set @t1:=0";
		$this->conn->Execute($sql);
		//get total number of records and pass it to the javascript function CheckAll
		$sql_ = "SELECT COUNT(*) AS mycount_
					FROM payroll_paystub_report a
					JOIN payroll_pay_period b on (a.payperiod_id=b.payperiod_id)
					JOIN emp_masterfile c on (a.emp_id=c.emp_id)
					JOIN app_userdept dep on (dep.ud_id=c.ud_id)
					JOIN emp_personal_info e on (e.pi_id=c.pi_id)
					WHERE a.payperiod_id=".$_GET['email']."
					$strOrderBy ";
		$rsResult = $this->conn->Execute($sql_);
		if (!$rsResult->EOF) {
			$mycount = $rsResult->fields['mycount_'];
		}
		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$ctr=0;
		$chkAttend = "<input type=\"checkbox\" name=\"chkAttend[]\" id=\"chkAttend[',@t1:=@t1+1,']\" value=\"',c.emp_id,'\" onclick=\"javascript:UncheckAll({$mycount});\">";
		
		// SqlAll Query
		$sql = "SELECT *,
				CONCAT('$chkAttend') as chkbox
					FROM payroll_paystub_report a
					JOIN payroll_pay_period b on (a.payperiod_id=b.payperiod_id)
					JOIN emp_masterfile c on (a.emp_id=c.emp_id)
					LEFT JOIN app_userdept dep on (dep.ud_id=c.ud_id)
					JOIN emp_personal_info e on (e.pi_id=c.pi_id)
					LEFT JOIN emp_position f ON (f.post_id=c.post_id)
					WHERE a.payperiod_id=".$_GET['email']."
					$strOrderBy ";

		// Field and Table Header Mapping
		$arrFields = array(
		 "chkbox" => "<input type=\"checkbox\" name=\"chkAttendAll\" id=\"chkAttendAll\" onclick=\"javascript:CheckAll({$mycount});\">"
		,"emp_idnum" => "Emp No."
		,"pi_lname" => "Last Name"
		,"pi_fname" => "First Name"
		,"post_name" => "Position"
		,"ud_name" => "Department"
		);
		// Column (table data) User Defined Attributes
		$arrAttribs = array(
		 "viewdata"=>"width='30' align='center'"
		,"empLink"=>"width='30' align='center'"
		,"chkbox"=>"width='10' align='center'"
		);
		// Process the Table List
		$tblDisplayList = new clsTableList($this->conn);
//		$tblDisplayList->tblBlock->templateFile = "table2.tpl.php";
		$tblDisplayList->arrFields = $arrFields;
		$tblDisplayList->paginator->linkPage = "?$queryStr";
		$tblDisplayList->sqlAll = $sql;
		$tblDisplayList->sqlCount = $sqlcount;
//		$tblDisplayList->tblBlock->templateFile = "table_nosort.tpl.php";
		$tblDisplayList->tblBlock->assign("noSearchStart","<!--");
		$tblDisplayList->tblBlock->assign("noSearchEnd","-->");
		return $tblDisplayList->getTableList($arrAttribs);
	}
	
	function getPDFResult_Format3($oData = array(),$isLocal=0){
//		printa($oData); exit;
        $orientation='P';
        $unit='mm';
        $format='A4';
        $unicode=true;
        $encoding="UTF-8";
        $oPDF = new clsPDF($orientation, $unit, $format, $unicode, $encoding);
        
        //$resolution= array(100, 100);
		//$oPDF->AddPage('P', $resolution);
        $objClsMngeDecimal = new Application();
        //set margins
        $oPDF->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $oPDF->SetHeaderMargin(PDF_MARGIN_HEADER);
        // set auto page break to false so that we can control the page break
        // depending on the desired number of lines on the ouput
        $oPDF->SetAutoPageBreak(false);
		
        // suppress print header and footer
        $oPDF->setPrintHeader(true);
        $oPDF->setPrintFooter(false);

        $oPDF->AliasNbPages();
        $i=0;
        $count_emp=0;
	    if(count($oData)>0){
        	for($i=0;$i<count($oData);$i++){
//        	if(($i % 2)==0){
        		$oPDF->AddPage();
        		$oPDF->SetFillColor(255,255,255);
		        // set initial coordinates
		        $coordX = 2;
		        $coordY = 26;
		        $coordYtop = 26;
		        $end = 85;
//	        }else{
//	        	$oPDF->SetFillColor(255,255,255);
//		        // set initial coordinates
//		        $coordX = 2;
//		        $coordY = 160;
//		        $coordYtop = 160;
//		        $end = 220;
//	        }
			if(isset($_GET['email'])){
		        $image_url = BASE_URL.'../includes/jscript/ThemeOffice/images/send_email.jpg';
	        	$oPDF->Image($image_url, 180, 10, 25, 8, 'JPG', BASE_URL.'index.php?statpos=send&send='.$_GET['email'], '', true, 150, '', false, false, 1, false, false, false);
	        	$empid = $oData[$i]['paystubdetails']['empinfo']['emp_id'];
	        	$to_q = "select b.pi_emailone from emp_masterfile a join emp_personal_info b on(b.pi_id=a.pi_id) where emp_id='$empid'";
				$to_r = $this->conn->Execute($to_q);
				$to = $to_r->fields['pi_emailone'];
				$oPDF->SetFont('dejavusans','',8);
	        	$oPDF->SetXY(2, 110);
	        	$oPDF->MultiCell(200, 2,'Email Address : '.$to,0,'L',false,1);
			}
	        //used to line style...
	        $style4 = array('dash' => 1,1);
			$style5 = array('dash' => 2,2);
			$style6 = array('dash' => 0);
			$oPDF->Line($coordX, $coordY-3, $coordX+205, $coordY-3, $style = $style5);//line after header
			
//			$oPDF->Image(SYSCONFIG_ROOT_PATH2.SYSCONFIG_DEFAULT_IMAGES_INCTEMP.'icons/edited/sigma.png',$coordX+1, $coordY-1, 40, 5, '', 'http://www.sigmasoft.com.ph/', '', false, 300,'L');
        	IF($isLocal==1){ //to check if Location
				$LocInfo = clsPayroll_Details::getLocationInfo($oData[$i]['paystubdetails']['empinfo']['emp_id']);
				$comp_name = $LocInfo['branchinfo_name'];
				$comp_adds = $LocInfo['branchinfo_add'];
			}ELSE{
				$comp_name = $oData[$i]['paystubdetails']['empinfo']['comp_name'];
				$comp_adds = $oData[$i]['paystubdetails']['empinfo']['comp_add'];
			}
			$oPDF->SetFont('dejavusans','B',9);
	        $oPDF->SetXY($coordX, $coordY-1);
	        $oPDF->MultiCell(200, 2,$comp_name,0,'L',1);
	        
	        //header
	        //------------------------------------------------------>>
			$oPDF->SetFont('dejavusans','',6);
			$coordY = $coordY + 3; 
	        $oPDF->SetXY($coordX, $coordY);
	        $oPDF->MultiCell(200, 2,$comp_adds,0,'L',1);
	        $coordY+=$oPDF->getFontSize()+1;
	        
	       	$oPDF->SetFont('dejavusans','',7);
	        $oPDF->SetXY($coordX, $coordY);
	        $oPDF->MultiCell(15, 2, "Name/ID",0,'L',1);
	        $oPDF->SetFont('dejavusans','B',7);
	        $oPDF->SetXY($coordX+13, $coordY);
	        $oPDF->MultiCell(85, 2, ':  '.strtoupper($oData[$i]['paystubdetails']['empinfo']['fullname']).' ('.$oData[$i]['paystubdetails']['empinfo']['emp_no'].')',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
	            
	        $oPDF->SetFont('dejavusans','',7);
	        $oPDF->SetXY($coordX+145, $coordY);
	        $oPDF->MultiCell(25, 2, "PAYDATE",0,'L',1);
	        $oPDF->SetXY($coordX+160, $coordY);
	        $oPDF->MultiCell(30, 2, ": ".date("M d, Y",strtotime($oData[$i]['paystubdetails']['paystubdetail']['paystubsched']['payperiod_trans_date'])),0,'L',1);
	        $coordY+=$oPDF->getFontSize()+.5;
	            
	        $oPDF->SetXY($coordX, $coordY);
	        $oPDF->MultiCell(19, 2, "Position",0,'L',1);
	        $oPDF->SetXY($coordX+13, $coordY);
	        $oPDF->MultiCell(110, 2, ':  '.$oData[$i]['paystubdetails']['empinfo']['jobpos_name'],0,'L',1);
	        $oPDF->SetXY($coordX+145, $coordY);
	        $oPDF->MultiCell(30, 2, "DEPT",0,'L',1);
	        $oPDF->SetXY($coordX+160, $coordY);
	        $oPDF->MultiCell(40, 2, ': '.$oData[$i]['paystubdetails']['empinfo']['ud_name'],0,'L',1);
	        $coordY+=$oPDF->getFontSize()+2;
	
	        $y = $coordY;
	        $oPDF->Line($coordX, $coordY, $coordX+205, $coordY, $style = $style6);//line after header
	        $coordY+=.1;
	        $oPDF->Line($coordX, $coordY, $coordX+205, $coordY, $style = $style6);//line after header2
	        //---------------------------------------------------<<
	        
	        //Head Body
        	$oPDF->SetFont('dejavusans','B',7);
            $oPDF->SetXY($coordX+11, $coordY);
            $oPDF->MultiCell(84.5, 2, 'EARNINGS',0,'C',1, 0, 0, 0, TRUE, 0, TRUE);
            $oPDF->SetXY($coordX+114.5, $coordY);
            $oPDF->MultiCell(84.5, 2, "DEDUCTIONS",0,'C',1, 0, 0, 0, TRUE, 0, TRUE);
            $oPDF->SetFont('dejavusans','',7);
            $coordY+=$oPDF->getFontSize()+2;
			$oPDF->Line($coordX, $coordY, $coordX+205, $coordY, $style=$style6);//bottom line
			
            $y_2nd = $coordY;
	        //Earnings COLUMN
	        	//head
	        	$nodays = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['earning']['totalDays'];
	        	if($nodays!='' AND $nodays > 0){
		        	$oPDF->SetXY($coordX+20, $coordY);
		            $oPDF->MultiCell(58, 2, "Rate",0,'L',1);
		            $oPDF->SetXY($coordX+40, $coordY);
		            $oPDF->MultiCell(20, 2, "Days",0,'L',1);
		            $coordY+=$oPDF->getFontSize();
	        	}
	        	//Basic Details
	            $oPDF->SetXY($coordX+2, $coordY);
	            $basicPAY = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['earning']['Regulartime'];
            	IF($basicPAY != '' AND $basicPAY > 0){
		            $oPDF->MultiCell(58, 2, "BASIC",0,'L',1);
		            if($nodays!='' AND $nodays > 0){
			            $oPDF->SetXY($coordX+30, $coordY);
				        $oPDF->MultiCell(20, 2, number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['earning']['basic'],2),0,'L',1);
			            $oPDF->SetXY($coordX+50, $coordY);
				        $oPDF->MultiCell(20, 2, $nodays,0,'L',1);
		            }
		            $oPDF->SetXY($coordX+75, $coordY);
		            $oPDF->MultiCell(24.5, 2, number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['earning']['Regulartime'],2),0,'R',1);
		            $coordY+=$oPDF->getFontSize();
            	}
            	
        		//BONUS PAY
				$bonusPAY = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['Bonus Pay'];
	            IF($bonusPAY != '' AND $bonusPAY > 0){
		            $oPDF->MultiCell(58, 2, "BONUS PAY",0,'L',1);
		            $oPDF->SetXY($coordX+75, $coordY);
		            $oPDF->MultiCell(24.5, 2, number_format($bonusPAY,$objClsMngeDecimal->getFinalDecimalSettings()),0,'R',1);
		            $coordY+=$oPDF->getFontSize();
	            }
	            
	            //COLA Details
	            $colaAmount = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['earning']['COLA'];
	            if($colaAmount != '' AND $colaAmount > 0){
		            $oPDF->SetXY($coordX+2, $coordY);
		            $oPDF->MultiCell(58, 2, "COLA",0,'L',1);
		            $oPDF->SetXY($coordX+30, $coordY);
		        	$oPDF->MultiCell(20, 2, number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['earning']['COLAperDay'],2),0,'L',1);
		            $oPDF->SetXY($coordX+50, $coordY);
		            $oPDF->MultiCell(20, 2, $nodays,0,'L',1);
		            $oPDF->SetXY($coordX+75, $coordY);
		            $oPDF->MultiCell(24.5, 2, number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['earning']['COLA'],2),0,'R',1);
		            $coordY+=$oPDF->getFontSize();
	            }
	            // OT listing
				$psa_OT = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['earning']['OT']['OTDetails'];  
	            if(count($psa_OT)>0){
//	            	@note: Hide for january payroll
					$oPDF->SetXY($coordX+2, $coordY);
			        $oPDF->MultiCell(58, 2, 'OverTime',0,'L',1);
					$coordY+=$oPDF->getFontSize();
	                for($k=0;$k<count($psa_OT);$k++){
		                $oPDF->SetXY($coordX+4, $coordY);
		                $oPDF->MultiCell(22, 2, substr(trim($psa_OT[$k]['ot_name']),0,12),0,'L',1);
		                $oPDF->SetXY($coordX+22, $coordY);
		                $oPDF->MultiCell(8, 2,' H ',0,'R',1);
		                $oPDF->SetXY($coordX+24, $coordY);
		                $oPDF->MultiCell(28, 2,$psa_OT[$k]['totaltimehr'].' = ',0,'R',1);
		                $oPDF->SetXY($coordX+50, $coordY);
		                $oPDF->MultiCell(28, 2, number_format($psa_OT[$k]['otamount'],$objClsMngeDecimal->getFinalDecimalSettings()),0,'L',1);
		                $coordY+=$oPDF->getFontSize();
	                }
	                $oPDF->SetXY($coordX+2, $coordY);
		            $oPDF->MultiCell(58, 2, 'Total OverTime',0,'L',1);
		            $oPDF->SetXY($coordX+75, $coordY);
		            $oPDF->MultiCell(24.5, 2, number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['earning']['OT']['TotalallOT'],2),0,'R',1);
		            $coordY+=$oPDF->getFontSize();
	            }
				// Amendment listing
				$psa_amendment = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['amendments'][0];
	            for($a=0;$a<count($psa_amendment) ;$a++){
	                IF ($psa_amendment[$a]['psa_type']==1) {
	                	IF($psa_amendment[$a]['amendemp_amount'] != 0){
			                $oPDF->SetXY($coordX+2, $coordY);
			                $oPDF->MultiCell(58, 2, $psa_amendment[$a]['psa_name'],0,'L',1);
			                $oPDF->SetXY($coordX+75, $coordY);
			                $oPDF->MultiCell(24.5, 2, number_format($psa_amendment[$a]['amendemp_amount'],2),0,'R',1);
			                $coordY+=$oPDF->getFontSize();
	                	}
	                }
	            }
	            // benifits listing
	            $psa_benifits = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['benefits'];            
	            if(count($psa_benifits)>0){
	                for($v=0;$v<count($psa_benifits);$v++){
	                	if($psa_benifits[$v]['psa_type']!=2){
	                		IF($psa_benifits[$v]['ben_payperday'] != 0){
				                $oPDF->SetXY($coordX+2, $coordY);
				                $oPDF->MultiCell(58, 2, $psa_benifits[$v]['psa_name'],0,'L',1);
				                $oPDF->SetXY($coordX+75, $coordY);
				                $oPDF->MultiCell(24.5, 2, number_format($psa_benifits[$v]['ben_payperday'],2),0,'R',1);
				                $coordY+=$oPDF->getFontSize();
	                		}
	                	}
	                }
	            }
	            
	            $oPDF->SetXY($coordX+17, $end-5);
	            $oPDF->MultiCell(60, 2, "TOTAL EARNINGS :",0,'R',1);
	            $oPDF->SetXY($coordX+75, $end-5);
	            $oPDF->MultiCell(24.5, 2, number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['TotalEarning_payslip'],2),0,'R',1);
	            $coordY+=$oPDF->getFontSize();
	            
            $oPDF->Line($coordX+77, $y+5, $coordX+77, $end-1, $style = $style5);//left line
            $oPDF->Line($coordX+102, $y+1, $coordX+102, $end-1, $style = $style6);//middle line
            $oPDF->Line($coordX+185, $y+5, $coordX+185, $end-1, $style = $style5);//right line
            $oPDF->Line($coordX, $end, $coordX+205, $end, $style=$style6);//bottom line
	            
	        //Deduction COLUMN
	            $coordY = $y_2nd;
	            $taxwh = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['W/H Tax'];
				if($taxwh != '0.00' AND $taxwh > 0){
		            $oPDF->SetXY($coordX+105, $coordY);
		            $oPDF->MultiCell(60, 2, "W/H TAX",0,'L',1);
		            $oPDF->SetXY($coordX+180, $coordY);
		            $oPDF->MultiCell(24, 2, number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['W/H Tax'],2),0,'R',1);
		            $coordY+=$oPDF->getFontSize()+0;
				}
				$HDMF = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['deduction']['Pag-ibig'];
				IF($HDMF > 0 ){
		            $oPDF->SetXY($coordX+105, $coordY);
		            $oPDF->MultiCell(60, 2, "HDMF",0,'L',1);
		            $oPDF->SetXY($coordX+180, $coordY);
		            $oPDF->MultiCell(24, 2, number_format($HDMF,2),0,'R',1);
		            $coordY+=$oPDF->getFontSize()+0;
				}
				$PHIC = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['deduction']['PhilHealth'];
				IF($PHIC > 0){
		            $oPDF->SetXY($coordX+105, $coordY);
		            $oPDF->MultiCell(60, 2, "PHIC",0,'L',1);
		            $oPDF->SetXY($coordX+180, $coordY);
		            $oPDF->MultiCell(24, 2, number_format($PHIC,2),0,'R',1);
		            $coordY+=$oPDF->getFontSize()+0;
				}
				$SSS = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['deduction']['SSS'];
				IF($SSS > 0){
		            $oPDF->SetXY($coordX+105, $coordY);
		            $oPDF->MultiCell(60, 2, "SSS",0,'L',1);
		            $oPDF->SetXY($coordX+180, $coordY);
		            $oPDF->MultiCell(24, 2, number_format($SSS,2),0,'R',1);
		            $coordY+=$oPDF->getFontSize()+0;
				}
	            // Leave/TA Listing
		        $psa_ta = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['TUA']['TADetails'];
	        	$total_ta = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['TUA']['TotalLeave'];
	            IF(count($psa_ta)>0){
	            	IF($total_ta != 0){
//	            	@note: hide for January Payroll
					$oPDF->SetXY($coordX+105, $coordY);
	                $oPDF->MultiCell(60, 2, 'TA Deduction',0,'L',1);
	                $coordY+=$oPDF->getFontSize();
	            	FOR($k=0;$k<count($psa_ta);$k++){
		            	IF($psa_ta[$k]['ta_name']!='Custom Days'){
		            		$oPDF->SetXY($coordX+110, $coordY);
				            $oPDF->MultiCell(25, 2, substr(trim($psa_ta[$k]['ta_name']),0,12),0,'L',1);
				            $oPDF->SetXY($coordX+115, $coordY);
				            $oPDF->MultiCell(20, 2,$psa_ta[$k]['ratetype'],0,'R',1);
				            $oPDF->SetXY($coordX+120, $coordY);
				            $oPDF->MultiCell(28, 2, number_format($psa_ta[$k]['totaltimehr'],$objClsMngeDecimal->getFinalDecimalSettings()).' = ',0,'R',1);
				            $oPDF->SetXY($coordX+145, $coordY);
				            $oPDF->MultiCell(28, 2, number_format($psa_ta[$k]['taamount'],$objClsMngeDecimal->getFinalDecimalSettings()),0,'L',1);
				            $coordY+=$oPDF->getFontSize();
	            		}
	                }
	                $oPDF->SetXY($coordX+105, $coordY);
	                $oPDF->MultiCell(60, 2, 'Total TA Deduction',0,'L',1);
	                $oPDF->SetXY($coordX+180, $coordY);
	                $oPDF->MultiCell(24, 2, number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['TUA']['TotalLeave'],2),0,'R',1);
	                $coordY+=$oPDF->getFontSize();
	            	}
	            }
				
	            //amendment deduction
	            $psa_amendment = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['amendments'][0];
	            if(count($psa_amendment)>0){
	                foreach($psa_amendment as $key => $val){
	                    if ($val['psa_type']==2) {
	                    	IF($val['amendemp_amount'] > 0){
			                    $oPDF->SetXY($coordX+105, $coordY);
			                    $oPDF->MultiCell(60, 2, $val['psa_name'],0,'L',1);
			                    $oPDF->SetXY($coordX+180, $coordY);
			                    $oPDF->MultiCell(24, 2, number_format($val['amendemp_amount'],2),0,'R',1);
			                    $coordY+=$oPDF->getFontSize();
	                    	}
	                    }
	                }
	            }
	            
	            // Benifits Deduction
        		$psa_benifits = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['benefits'];            
	            if(count($psa_benifits)>0){
	                for($v=0;$v<count($psa_benifits);$v++){
	                	if($psa_benifits[$v]['psa_type']!=1){
	                		IF($psa_benifits[$v]['ben_payperday'] > 0){
				                $oPDF->SetXY($coordX+105, $coordY);
				                $oPDF->MultiCell(60, 2, $psa_benifits[$v]['psa_name'],0,'L',1);
				                $oPDF->SetXY($coordX+180, $coordY);
				                $oPDF->MultiCell(24, 2, number_format($psa_benifits[$v]['ben_payperday'],2),0,'R',1);
				                $coordY+=$oPDF->getFontSize();
	                		}
	                	}
	                }
	            }
	            
	            //Loan listing
				$loan_info_ = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['government_regular'];
				for($v=0;$v<count($loan_info_);$v++){
	                $oPDF->SetXY($coordX+105, $coordY);
	                $oPDF->MultiCell(60, 2, $loan_info_[$v]['psa_name'],0,'L',1);
	                $oPDF->SetXY($coordX+160, $coordY);
                	$oPDF->MultiCell(30, 2, " Bal: ".number_format($loan_info_[$v]['loan_balance'],2),0,'L',1);
	                $oPDF->SetXY($coordX+180, $coordY);
	                $oPDF->MultiCell(24, 2, number_format($loan_info_[$v]['loan_payperperiod'],2),0,'R',1);
	                $coordY+=$oPDF->getFontSize()+0;
	            }
	            
	            $oPDF->SetXY($coordX+125, $end-5);
	            $oPDF->MultiCell(59, 2, "TOTAL DEDUCTIONS :",0,'R',1);
	            $oPDF->SetXY($coordX+180, $end-5);
	            $oPDF->MultiCell(24, 2, number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['Deduction'],2),0,'R',1);
	            $coordY+=$oPDF->getFontSize()+0;
	
	            
	         //bottom
	            $coordY=$end;
            $oPDF->Line($coordX+102, $coordY+1, $coordX+102, $end+21,$style = $style6);//bottom middle line
	            //$oPDF->Line($coordX+170, $coordYtop, $coordX+170, $end+21, $style = $style4);//out right line
	            
	            $oPDF->SetXY($coordX, $coordY);
	            $oPDF->MultiCell(16, 2, "BANK  :",0,'L',1);
	            $oPDF->SetXY($coordX+15, $coordY);
	            $oPDF->MultiCell(100, 2, $oData[$i]['paystubdetails']['empinfo']['banklist_name'].' / '.$oData[$i]['paystubdetails']['empinfo']['bankiemp_acct_no'],0,'L',1);
	            
	            $oPDF->SetFont('dejavusans','B',7);
	            $oPDF->SetXY($coordX+130, $coordY);
	            $oPDF->MultiCell(25, 2,'NET PAY',0,'L',1);
	            $oPDF->SetXY($coordX+160, $coordY);
	            $oPDF->MultiCell(25, 2, ":  ".number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['Net Pay'],2),0,'L',1);
	            $coordY+=$oPDF->getFontSize()+1;
	            $oPDF->Line($coordX+125, $coordY+1, $coordX+185, $coordY+1, $style=$style6);//netpay line
//	            @note: hide for January Payroll
	            $leave_record_ = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['leave_record'];
				$coordY_leave = $coordY;
				IF(count($leave_record_)>0){
					$oPDF->SetFont('dejavusans','B',7);
					$oPDF->SetXY($coordX+40, $coordY);
		            $oPDF->MultiCell(15, 2, "Credit",0,'L',1);
		            $oPDF->SetXY($coordX+55, $coordY);
		            $oPDF->MultiCell(15, 2, "Bal",0,'L',1);
		            $oPDF->SetXY($coordX+70, $coordY);
		            $oPDF->MultiCell(15, 2,'Taken',0,'L',1);
		            $coordY+=$oPDF->getFontSize()-2;
		            $oPDF->SetFont('dejavusans','',7);
					FOR($v=0;$v<count($leave_record_);$v++){
						IF($leave_record_[$v]['empleave_credit'] > 0){
			                $oPDF->SetXY($coordX, $coordY_leave+3);
			                $oPDF->MultiCell(40, 2, $leave_record_[$v]['leave_name'],0,'L',1);
			                $oPDF->SetXY($coordX+40, $coordY_leave+3);
			                $oPDF->MultiCell(15, 2, number_format($leave_record_[$v]['empleave_credit'],2),0,'L',1);
			                $oPDF->SetXY($coordX+55, $coordY_leave+3);
			                $oPDF->MultiCell(15, 2, number_format($leave_record_[$v]['empleave_available_day'],2),0,'L',1);
			                $oPDF->SetXY($coordX+70, $coordY_leave+3);
			                $oPDF->MultiCell(15, 2, number_format($leave_record_[$v]['empleave_used_day'],2),0,'L',1);
			                $coordY_leave+=$oPDF->getFontSize()+0;
						}
		            }
				}
	            $oPDF->SetFont('dejavusans','B',7);
	            $oPDF->SetXY($coordX+163, $coordY);
	            $oPDF->MultiCell(30, 2, "YTD",0,'L',1);
				$coordY+=$oPDF->getFontSize()+.5;
	            $oPDF->SetFont('dejavusans','',7);
	            $oPDF->SetXY($coordX+130, $coordY);
	            $oPDF->MultiCell(30, 2, "GROSS PAY",0,'L',1);
	            $sqlYear = "SELECT payperiod_period_year,payperiod_period,payperiod_freq FROM payroll_pay_period WHERE payperiod_id='".$oData[$i]['payperiod_id']."'";
				$getYear = $this->conn->Execute($sqlYear);
	            $oPDF->SetXY($coordX+160, $coordY);
	            $ytdgrosspay = $this->getYTD($oData[$i]['emp_id'],4,$getYear->fields['payperiod_period_year'],$getYear->fields['payperiod_period'],$getYear->fields['payperiod_freq']);
	            $oPDF->MultiCell(25, 2, ":   ".number_format($ytdgrosspay['ytdamount'],2),0,'L',1);
	            $coordY+=$oPDF->getFontSize()+0;
	            
	            $oPDF->SetXY($coordX+130, $coordY);
	            $oPDF->MultiCell(30, 2, "TAXABLE GROSS",0,'L',1);
	            $oPDF->SetXY($coordX+160, $coordY);
	            $ytdtaxgross = $this->getYTD($oData[$i]['emp_id'],30,$getYear->fields['payperiod_period_year'],$getYear->fields['payperiod_period'],$getYear->fields['payperiod_freq']);
	            $oPDF->MultiCell(25, 2, ":   ".number_format($ytdtaxgross['ytdamount'],2),0,'L',1);
	            $coordY+=$oPDF->getFontSize()+0;
	            
	            $oPDF->SetFont('dejavusans','',6.5);
	            $oPDF->SetXY($coordX+130, $coordY);
	            $oPDF->MultiCell(30, 2, "Statutory Contribution",0,'L',1);
	           	$oPDF->SetXY($coordX+160, $coordY);
	            $oPDF->SetFont('dejavusans','',7);
	            $ytdstat = $this->getYTD($oData[$i]['emp_id'],27,$getYear->fields['payperiod_period_year'],$getYear->fields['payperiod_period'],$getYear->fields['payperiod_freq']);
	            $oPDF->MultiCell(25, 2, ":   ".number_format($ytdstat['ytdamount'],2),0,'L',1);
	            $coordY+=$oPDF->getFontSize()+0;
	            
	            $oPDF->SetXY($coordX+130, $coordY);
	            $oPDF->MultiCell(30, 2, "W/H TAX",0,'L',1);
	            $oPDF->SetXY($coordX+160, $coordY);
	            $ytdwhtax = $this->getYTD($oData[$i]['emp_id'],8,$getYear->fields['payperiod_period_year'],$getYear->fields['payperiod_period'],$getYear->fields['payperiod_freq']);
	            $oPDF->MultiCell(25, 2, ":   ".number_format($ytdwhtax['ytdamount'],2),0,'L',1);
	            $coordY+=$oPDF->getFontSize()*4;
	            
	            $oPDF->SetXY($coordX, $coordY);
	            $oPDF->MultiCell(19, 2, "TAX STATUS",0,'L',1);
	            $oPDF->SetXY($coordX+19, $coordY);
	            $oPDF->MultiCell(90, 2, ': '.$oData[$i]['paystubdetails']['empinfo']['tax_ex_name'],0,'L',1);
	            $coordY+=$oPDF->getFontSize()+7;
	            $oPDF->Line($coordX, $coordY, $coordX+205, $coordY, $style=$style5);//bottom line
        	}
        }
        // get the pdf output
        $output = $oPDF->Output("payslip_".$oData['paystubdetails']['empinfo']['fullname'].date('Y-m-d').".pdf");
        if(!empty($output)){
        	return $output;
        }
    }
    
	function sendPDFResult_Format3Email($oData = array(),$i,$isLocal=0){
//		printa($oData); exit;
        $orientation='P';
        $unit='mm';
        $format='A4';
        $unicode=true;
        $encoding="UTF-8";

        $oPDF = new clsPDF($orientation, $unit, $format, $unicode, $encoding);
        $oPDF->SetProtection($permissions=array('print', 'copy'), $user_pass=$this->getPayslipPassword($oData[$i]['emp_id']), $owner_pass=null, $mode=0, $pubkeys=null);
		$objClsMngeDecimal = new Application();
        //set margins
        $oPDF->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $oPDF->SetHeaderMargin(PDF_MARGIN_HEADER);
        // set auto page break to false so that we can control the page break
        // depending on the desired number of lines on the ouput
        $oPDF->SetAutoPageBreak(false);
        // use a freesans font as a default font
//        $oPDF->SetFont('dotim5','',7);

        // suppress print header and footer
        $oPDF->setPrintHeader(true);
        $oPDF->setPrintFooter(false);

        $oPDF->AliasNbPages();

        // set initial pdf page
        //        	if(($i % 2)==0){
        		$oPDF->AddPage();
        		$oPDF->SetFillColor(255,255,255);
		        // set initial coordinates
		        $coordX = 2;
		        $coordY = 26;
		        $coordYtop = 26;
		        $end = 85;
//	        }else{
//	        	$oPDF->SetFillColor(255,255,255);
//		        // set initial coordinates
//		        $coordX = 2;
//		        $coordY = 160;
//		        $coordYtop = 160;
//		        $end = 220;
//	        }

	       //used to line style...
	        $style4 = array('dash' => 1,1);
			$style5 = array('dash' => 2,2);
			$style6 = array('dash' => 0);
			$oPDF->Line($coordX, $coordY-3, $coordX+205, $coordY-3, $style = $style5);//line after header
			
//			$oPDF->Image(SYSCONFIG_ROOT_PATH2.SYSCONFIG_DEFAULT_IMAGES_INCTEMP.'icons/edited/sigma.png',$coordX+1, $coordY-1, 40, 5, '', 'http://www.sigmasoft.com.ph/', '', false, 300,'L');
        	IF($isLocal==1){ //to check if Location
				$LocInfo = clsPayroll_Details::getLocationInfo($oData[$i]['paystubdetails']['empinfo']['emp_id']);
				$comp_name = $LocInfo['branchinfo_name'];
				$comp_adds = $LocInfo['branchinfo_add'];
			}ELSE{
				$comp_name = $oData[$i]['paystubdetails']['empinfo']['comp_name'];
				$comp_adds = $oData[$i]['paystubdetails']['empinfo']['comp_add'];
			}
			$oPDF->SetFont('dejavusans','B',9);
	        $oPDF->SetXY($coordX, $coordY-1);
	        $oPDF->MultiCell(200, 2,$comp_name,0,'L',1);
	        
	        //header
	        //------------------------------------------------------>>
			$oPDF->SetFont('dejavusans','',6);
			$coordY = $coordY + 3; 
	        $oPDF->SetXY($coordX, $coordY);
	        $oPDF->MultiCell(200, 2,$comp_adds,0,'L',1);
	        $coordY+=$oPDF->getFontSize()+1;
	        
	       	$oPDF->SetFont('dejavusans','',7);
	        $oPDF->SetXY($coordX, $coordY);
	        $oPDF->MultiCell(15, 2, "Name/ID",0,'L',1);
	        $oPDF->SetFont('dejavusans','B',7);
	        $oPDF->SetXY($coordX+13, $coordY);
	        $oPDF->MultiCell(85, 2, ':  '.strtoupper($oData[$i]['paystubdetails']['empinfo']['fullname']).' ('.$oData[$i]['paystubdetails']['empinfo']['emp_no'].')',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
	            
	        $oPDF->SetFont('dejavusans','',7);
	        $oPDF->SetXY($coordX+145, $coordY);
	        $oPDF->MultiCell(25, 2, "PAYDATE",0,'L',1);
	        $oPDF->SetXY($coordX+160, $coordY);
	        $oPDF->MultiCell(30, 2, ": ".date("M d, Y",strtotime($oData[$i]['paystubdetails']['paystubdetail']['paystubsched']['payperiod_trans_date'])),0,'L',1);
	        $coordY+=$oPDF->getFontSize()+.5;
	            
	        $oPDF->SetXY($coordX, $coordY);
	        $oPDF->MultiCell(19, 2, "Position",0,'L',1);
	        $oPDF->SetXY($coordX+13, $coordY);
	        $oPDF->MultiCell(110, 2, ':  '.$oData[$i]['paystubdetails']['empinfo']['jobpos_name'],0,'L',1);
	        $oPDF->SetXY($coordX+145, $coordY);
	        $oPDF->MultiCell(30, 2, "DEPT",0,'L',1);
	        $oPDF->SetXY($coordX+160, $coordY);
	        $oPDF->MultiCell(40, 2, ': '.$oData[$i]['paystubdetails']['empinfo']['ud_name'],0,'L',1);
	        $coordY+=$oPDF->getFontSize()+2;
	
	        $y = $coordY;
	        $oPDF->Line($coordX, $coordY, $coordX+205, $coordY, $style = $style6);//line after header
	        $coordY+=.1;
	        $oPDF->Line($coordX, $coordY, $coordX+205, $coordY, $style = $style6);//line after header2
	        //---------------------------------------------------<<
	        
	        //Head Body
        	$oPDF->SetFont('dejavusans','B',7);
            $oPDF->SetXY($coordX+11, $coordY);
            $oPDF->MultiCell(84.5, 2, 'EARNINGS',0,'C',1, 0, 0, 0, TRUE, 0, TRUE);
            $oPDF->SetXY($coordX+114.5, $coordY);
            $oPDF->MultiCell(84.5, 2, "DEDUCTIONS",0,'C',1, 0, 0, 0, TRUE, 0, TRUE);
            $oPDF->SetFont('dejavusans','',7);
            $coordY+=$oPDF->getFontSize()+2;
			$oPDF->Line($coordX, $coordY, $coordX+205, $coordY, $style=$style6);//bottom line
			
            $y_2nd = $coordY;
	        //Earnings COLUMN
	        	//head
	        	$nodays = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['earning']['totalDays'];
	        	if($nodays!='' AND $nodays > 0){
		        	$oPDF->SetXY($coordX+20, $coordY);
		            $oPDF->MultiCell(58, 2, "Rate",0,'L',1);
		            $oPDF->SetXY($coordX+40, $coordY);
		            $oPDF->MultiCell(20, 2, "Days",0,'L',1);
		            $coordY+=$oPDF->getFontSize();
	        	}
	        	//Basic Details
	            $oPDF->SetXY($coordX+2, $coordY);
	            $basicPAY = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['earning']['Regulartime'];
            	IF($basicPAY != '' AND $basicPAY > 0){
		            $oPDF->MultiCell(58, 2, "BASIC",0,'L',1);
		            if($nodays!='' AND $nodays > 0){
			            $oPDF->SetXY($coordX+30, $coordY);
				        $oPDF->MultiCell(20, 2, number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['earning']['basic'],2),0,'L',1);
			            $oPDF->SetXY($coordX+50, $coordY);
				        $oPDF->MultiCell(20, 2, $nodays,0,'L',1);
		            }
		            $oPDF->SetXY($coordX+75, $coordY);
		            $oPDF->MultiCell(24.5, 2, number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['earning']['Regulartime'],2),0,'R',1);
		            $coordY+=$oPDF->getFontSize();
            	}
            	
        		//BONUS PAY
				$bonusPAY = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['Bonus Pay'];
	            IF($bonusPAY != '' AND $bonusPAY > 0){
		            $oPDF->MultiCell(58, 2, "BONUS PAY",0,'L',1);
		            $oPDF->SetXY($coordX+75, $coordY);
		            $oPDF->MultiCell(24.5, 2, number_format($bonusPAY,$objClsMngeDecimal->getFinalDecimalSettings()),0,'R',1);
		            $coordY+=$oPDF->getFontSize();
	            }
	            
	            //COLA Details
	            $colaAmount = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['earning']['COLA'];
	            if($colaAmount != '' AND $colaAmount > 0){
		            $oPDF->SetXY($coordX+2, $coordY);
		            $oPDF->MultiCell(58, 2, "COLA",0,'L',1);
		            $oPDF->SetXY($coordX+30, $coordY);
		        	$oPDF->MultiCell(20, 2, number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['earning']['COLAperDay'],2),0,'L',1);
		            $oPDF->SetXY($coordX+50, $coordY);
		            $oPDF->MultiCell(20, 2, $nodays,0,'L',1);
		            $oPDF->SetXY($coordX+75, $coordY);
		            $oPDF->MultiCell(24.5, 2, number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['earning']['COLA'],2),0,'R',1);
		            $coordY+=$oPDF->getFontSize();
	            }
	            // OT listing
				$psa_OT = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['earning']['OT']['OTDetails'];  
	            if(count($psa_OT)>0){
//	            	@note: Hide for january payroll
					$oPDF->SetXY($coordX+2, $coordY);
			        $oPDF->MultiCell(58, 2, 'OverTime',0,'L',1);
					$coordY+=$oPDF->getFontSize();
	                for($k=0;$k<count($psa_OT);$k++){
		                $oPDF->SetXY($coordX+4, $coordY);
		                $oPDF->MultiCell(22, 2, substr(trim($psa_OT[$k]['ot_name']),0,12),0,'L',1);
		                $oPDF->SetXY($coordX+22, $coordY);
		                $oPDF->MultiCell(8, 2,' H ',0,'R',1);
		                $oPDF->SetXY($coordX+24, $coordY);
		                $oPDF->MultiCell(28, 2,$psa_OT[$k]['totaltimehr'].' = ',0,'R',1);
		                $oPDF->SetXY($coordX+50, $coordY);
		                $oPDF->MultiCell(28, 2, number_format($psa_OT[$k]['otamount'],$objClsMngeDecimal->getFinalDecimalSettings()),0,'L',1);
		                $coordY+=$oPDF->getFontSize();
	                }
	                $oPDF->SetXY($coordX+2, $coordY);
		            $oPDF->MultiCell(58, 2, 'Total OverTime',0,'L',1);
		            $oPDF->SetXY($coordX+75, $coordY);
		            $oPDF->MultiCell(24.5, 2, number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['earning']['OT']['TotalallOT'],2),0,'R',1);
		            $coordY+=$oPDF->getFontSize();
	            }
				// Amendment listing
				$psa_amendment = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['amendments'][0];
	            for($a=0;$a<count($psa_amendment) ;$a++){
	                IF ($psa_amendment[$a]['psa_type']==1) {
	                	IF($psa_amendment[$a]['amendemp_amount'] > 0){
			                $oPDF->SetXY($coordX+2, $coordY);
			                $oPDF->MultiCell(58, 2, $psa_amendment[$a]['psa_name'],0,'L',1);
			                $oPDF->SetXY($coordX+75, $coordY);
			                $oPDF->MultiCell(24.5, 2, number_format($psa_amendment[$a]['amendemp_amount'],2),0,'R',1);
			                $coordY+=$oPDF->getFontSize();
	                	}
	                }
	            }
	            // benifits listing
	            $psa_benifits = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['benefits'];            
	            if(count($psa_benifits)>0){
	                for($v=0;$v<count($psa_benifits);$v++){
	                	if($psa_benifits[$v]['psa_type']!=2){
	                		IF($psa_benifits[$v]['ben_payperday'] > 0){
				                $oPDF->SetXY($coordX+2, $coordY);
				                $oPDF->MultiCell(58, 2, $psa_benifits[$v]['psa_name'],0,'L',1);
				                $oPDF->SetXY($coordX+75, $coordY);
				                $oPDF->MultiCell(24.5, 2, number_format($psa_benifits[$v]['ben_payperday'],2),0,'R',1);
				                $coordY+=$oPDF->getFontSize();
	                		}
	                	}
	                }
	            }
	            
	            $oPDF->SetXY($coordX+17, $end-5);
	            $oPDF->MultiCell(60, 2, "TOTAL EARNINGS :",0,'R',1);
	            $oPDF->SetXY($coordX+75, $end-5);
	            $oPDF->MultiCell(24.5, 2, number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['TotalEarning_payslip'],2),0,'R',1);
	            $coordY+=$oPDF->getFontSize();
	            
            $oPDF->Line($coordX+77, $y+5, $coordX+77, $end-1, $style = $style5);//left line
            $oPDF->Line($coordX+102, $y+1, $coordX+102, $end-1, $style = $style6);//middle line
            $oPDF->Line($coordX+185, $y+5, $coordX+185, $end-1, $style = $style5);//right line
            $oPDF->Line($coordX, $end, $coordX+205, $end, $style=$style6);//bottom line
	            
	        //Deduction COLUMN
	            $coordY = $y_2nd;
	            $taxwh = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['W/H Tax'];
				if($taxwh != '0.00' AND $taxwh > 0){
		            $oPDF->SetXY($coordX+105, $coordY);
		            $oPDF->MultiCell(60, 2, "W/H TAX",0,'L',1);
		            $oPDF->SetXY($coordX+180, $coordY);
		            $oPDF->MultiCell(24, 2, number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['W/H Tax'],2),0,'R',1);
		            $coordY+=$oPDF->getFontSize()+0;
				}
				$HDMF = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['deduction']['Pag-ibig'];
				IF($HDMF > 0 ){
		            $oPDF->SetXY($coordX+105, $coordY);
		            $oPDF->MultiCell(60, 2, "HDMF",0,'L',1);
		            $oPDF->SetXY($coordX+180, $coordY);
		            $oPDF->MultiCell(24, 2, number_format($HDMF,2),0,'R',1);
		            $coordY+=$oPDF->getFontSize()+0;
				}
				$PHIC = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['deduction']['PhilHealth'];
				IF($PHIC > 0){
		            $oPDF->SetXY($coordX+105, $coordY);
		            $oPDF->MultiCell(60, 2, "PHIC",0,'L',1);
		            $oPDF->SetXY($coordX+180, $coordY);
		            $oPDF->MultiCell(24, 2, number_format($PHIC,2),0,'R',1);
		            $coordY+=$oPDF->getFontSize()+0;
				}
				$SSS = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['deduction']['SSS'];
				IF($SSS > 0){
		            $oPDF->SetXY($coordX+105, $coordY);
		            $oPDF->MultiCell(60, 2, "SSS",0,'L',1);
		            $oPDF->SetXY($coordX+180, $coordY);
		            $oPDF->MultiCell(24, 2, number_format($SSS,2),0,'R',1);
		            $coordY+=$oPDF->getFontSize()+0;
				}
	            // Leave/TA Listing
		        $psa_ta = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['TUA']['TADetails'];
	        	$total_ta = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['TUA']['TotalLeave'];
	            IF(count($psa_ta)>0){
	            	IF($total_ta != 0){
//	            	@note: hide for January Payroll
					$oPDF->SetXY($coordX+105, $coordY);
	                $oPDF->MultiCell(60, 2, 'TA Deduction',0,'L',1);
	                $coordY+=$oPDF->getFontSize();
	            	FOR($k=0;$k<count($psa_ta);$k++){
		            	IF($psa_ta[$k]['ta_name']!='Custom Days'){
		            		$oPDF->SetXY($coordX+110, $coordY);
				            $oPDF->MultiCell(25, 2, substr(trim($psa_ta[$k]['ta_name']),0,12),0,'L',1);
				            $oPDF->SetXY($coordX+115, $coordY);
				            $oPDF->MultiCell(20, 2,$psa_ta[$k]['ratetype'],0,'R',1);
				            $oPDF->SetXY($coordX+120, $coordY);
				            $oPDF->MultiCell(28, 2, number_format($psa_ta[$k]['totaltimehr'],$objClsMngeDecimal->getFinalDecimalSettings()).' = ',0,'R',1);
				            $oPDF->SetXY($coordX+145, $coordY);
				            $oPDF->MultiCell(28, 2, number_format($psa_ta[$k]['taamount'],$objClsMngeDecimal->getFinalDecimalSettings()),0,'L',1);
				            $coordY+=$oPDF->getFontSize();
	            		}
	                }
	                $oPDF->SetXY($coordX+105, $coordY);
	                $oPDF->MultiCell(60, 2, 'Total TA Deduction',0,'L',1);
	                $oPDF->SetXY($coordX+180, $coordY);
	                $oPDF->MultiCell(24, 2, number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['TUA']['TotalLeave'],2),0,'R',1);
	                $coordY+=$oPDF->getFontSize();
	            	}
	            }
				
	            //amendment deduction
	            $psa_amendment = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['amendments'][0];
	            if(count($psa_amendment)>0){
	                foreach($psa_amendment as $key => $val){
	                    if ($val['psa_type']==2) {
	                    	IF($val['amendemp_amount'] > 0){
			                    $oPDF->SetXY($coordX+105, $coordY);
			                    $oPDF->MultiCell(60, 2, $val['psa_name'],0,'L',1);
			                    $oPDF->SetXY($coordX+180, $coordY);
			                    $oPDF->MultiCell(24, 2, number_format($val['amendemp_amount'],2),0,'R',1);
			                    $coordY+=$oPDF->getFontSize();
	                    	}
	                    }
	                }
	            }
	            
	            // Benifits Deduction
        		$psa_benifits = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['benefits'];            
	            if(count($psa_benifits)>0){
	                for($v=0;$v<count($psa_benifits);$v++){
	                	if($psa_benifits[$v]['psa_type']!=1){
	                		IF($psa_benifits[$v]['ben_payperday'] > 0){
				                $oPDF->SetXY($coordX+105, $coordY);
				                $oPDF->MultiCell(60, 2, $psa_benifits[$v]['psa_name'],0,'L',1);
				                $oPDF->SetXY($coordX+180, $coordY);
				                $oPDF->MultiCell(24, 2, number_format($psa_benifits[$v]['ben_payperday'],2),0,'R',1);
				                $coordY+=$oPDF->getFontSize();
	                		}
	                	}
	                }
	            }
	            
	            //Loan listing
				$loan_info_ = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['government_regular'];
				for($v=0;$v<count($loan_info_);$v++){
	                $oPDF->SetXY($coordX+105, $coordY);
	                $oPDF->MultiCell(60, 2, $loan_info_[$v]['psa_name'],0,'L',1);
	                $oPDF->SetXY($coordX+160, $coordY);
                	$oPDF->MultiCell(30, 2, " Bal: ".number_format($loan_info_[$v]['loan_balance'],2),0,'L',1);
	                $oPDF->SetXY($coordX+180, $coordY);
	                $oPDF->MultiCell(24, 2, number_format($loan_info_[$v]['loan_payperperiod'],2),0,'R',1);
	                $coordY+=$oPDF->getFontSize()+0;
	            }
	            
	            $oPDF->SetXY($coordX+125, $end-5);
	            $oPDF->MultiCell(59, 2, "TOTAL DEDUCTIONS :",0,'R',1);
	            $oPDF->SetXY($coordX+180, $end-5);
	            $oPDF->MultiCell(24, 2, number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['Deduction'],2),0,'R',1);
	            $coordY+=$oPDF->getFontSize()+0;
	
	            
	         //bottom
	            $coordY=$end;
            $oPDF->Line($coordX+102, $coordY+1, $coordX+102, $end+21,$style = $style6);//bottom middle line
	            //$oPDF->Line($coordX+170, $coordYtop, $coordX+170, $end+21, $style = $style4);//out right line
	            
	            $oPDF->SetXY($coordX, $coordY);
	            $oPDF->MultiCell(16, 2, "BANK  :",0,'L',1);
	            $oPDF->SetXY($coordX+15, $coordY);
	            $oPDF->MultiCell(100, 2, $oData[$i]['paystubdetails']['empinfo']['banklist_name'].' / '.$oData[$i]['paystubdetails']['empinfo']['bankiemp_acct_no'],0,'L',1);
	            
	            $oPDF->SetFont('dejavusans','B',7);
	            $oPDF->SetXY($coordX+130, $coordY);
	            $oPDF->MultiCell(25, 2,'NET PAY',0,'L',1);
	            $oPDF->SetXY($coordX+160, $coordY);
	            $oPDF->MultiCell(25, 2, ":  ".number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['Net Pay'],2),0,'L',1);
	            $coordY+=$oPDF->getFontSize()+1;
	            $oPDF->Line($coordX+125, $coordY+1, $coordX+185, $coordY+1, $style=$style6);//netpay line
//	            @note: hide for January Payroll
	            $leave_record_ = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['leave_record'];
				$coordY_leave = $coordY;
				IF(count($leave_record_)>0){
					$oPDF->SetFont('dejavusans','B',7);
					$oPDF->SetXY($coordX+40, $coordY);
		            $oPDF->MultiCell(15, 2, "Credit",0,'L',1);
		            $oPDF->SetXY($coordX+55, $coordY);
		            $oPDF->MultiCell(15, 2, "Bal",0,'L',1);
		            $oPDF->SetXY($coordX+70, $coordY);
		            $oPDF->MultiCell(15, 2,'Taken',0,'L',1);
		            $coordY+=$oPDF->getFontSize()-2;
		            $oPDF->SetFont('dejavusans','',7);
					FOR($v=0;$v<count($leave_record_);$v++){
						IF($leave_record_[$v]['empleave_credit'] > 0){
			                $oPDF->SetXY($coordX, $coordY_leave+3);
			                $oPDF->MultiCell(40, 2, $leave_record_[$v]['leave_name'],0,'L',1);
			                $oPDF->SetXY($coordX+40, $coordY_leave+3);
			                $oPDF->MultiCell(15, 2, number_format($leave_record_[$v]['empleave_credit'],2),0,'L',1);
			                $oPDF->SetXY($coordX+55, $coordY_leave+3);
			                $oPDF->MultiCell(15, 2, number_format($leave_record_[$v]['empleave_available_day'],2),0,'L',1);
			                $oPDF->SetXY($coordX+70, $coordY_leave+3);
			                $oPDF->MultiCell(15, 2, number_format($leave_record_[$v]['empleave_used_day'],2),0,'L',1);
			                $coordY_leave+=$oPDF->getFontSize()+0;
						}
		            }
				}
	            $oPDF->SetFont('dejavusans','B',7);
	            $oPDF->SetXY($coordX+163, $coordY);
	            $oPDF->MultiCell(30, 2, "YTD",0,'L',1);
				$coordY+=$oPDF->getFontSize()+.5;
	            $oPDF->SetFont('dejavusans','',7);
	            $oPDF->SetXY($coordX+130, $coordY);
	            $oPDF->MultiCell(30, 2, "GROSS PAY",0,'L',1);
	            $oPDF->SetXY($coordX+160, $coordY);
	            $sqlYear = "SELECT payperiod_period_year,payperiod_period,payperiod_freq FROM payroll_pay_period WHERE payperiod_id='".$oData[$i]['payperiod_id']."'";
				$getYear = $this->conn->Execute($sqlYear);
	            $ytdgrosspay = $this->getYTD($oData[$i]['emp_id'],4,$getYear->fields['payperiod_period_year'],$getYear->fields['payperiod_period'],$getYear->fields['payperiod_freq']);
	            $oPDF->MultiCell(25, 2, ":   ".number_format($ytdgrosspay['ytdamount'],2),0,'L',1);
	            $coordY+=$oPDF->getFontSize()+0;
	            
	            $oPDF->SetXY($coordX+130, $coordY);
	            $oPDF->MultiCell(30, 2, "TAXABLE GROSS",0,'L',1);
	            $oPDF->SetXY($coordX+160, $coordY);
	            $ytdtaxgross = $this->getYTD($oData[$i]['emp_id'],30,$getYear->fields['payperiod_period_year'],$getYear->fields['payperiod_period'],$getYear->fields['payperiod_freq']);
	            $oPDF->MultiCell(25, 2, ":   ".number_format($ytdtaxgross['ytdamount'],2),0,'L',1);
	            $coordY+=$oPDF->getFontSize()+0;
	            
	            $oPDF->SetFont('dejavusans','',6.5);
	            $oPDF->SetXY($coordX+130, $coordY);
	            $oPDF->MultiCell(30, 2, "Statutory Contribution",0,'L',1);
	           	$oPDF->SetXY($coordX+160, $coordY);
	            $oPDF->SetFont('dejavusans','',7);
	            $ytdstat = $this->getYTD($oData[$i]['emp_id'],27,$getYear->fields['payperiod_period_year'],$getYear->fields['payperiod_period'],$getYear->fields['payperiod_freq']);
	            $oPDF->MultiCell(25, 2, ":   ".number_format($ytdstat['ytdamount'],2),0,'L',1);
	            $coordY+=$oPDF->getFontSize()+0;
	            
	            $oPDF->SetXY($coordX+130, $coordY);
	            $oPDF->MultiCell(30, 2, "W/H TAX",0,'L',1);
	            $oPDF->SetXY($coordX+160, $coordY);
	            $ytdwhtax = $this->getYTD($oData[$i]['emp_id'],8,$getYear->fields['payperiod_period_year'],$getYear->fields['payperiod_period'],$getYear->fields['payperiod_freq']);
	            $oPDF->MultiCell(25, 2, ":   ".number_format($ytdwhtax['ytdamount'],2),0,'L',1);
	            $coordY+=$oPDF->getFontSize()*4;
	            
	            $oPDF->SetXY($coordX, $coordY);
	            $oPDF->MultiCell(19, 2, "TAX STATUS",0,'L',1);
	            $oPDF->SetXY($coordX+19, $coordY);
	            $oPDF->MultiCell(90, 2, ': '.$oData[$i]['paystubdetails']['empinfo']['tax_ex_name'],0,'L',1);
	            $coordY+=$oPDF->getFontSize()+7;
            	$oPDF->Line($coordX, $coordY, $coordX+205, $coordY, $style=$style5);//bottom line

        $output = $oPDF->Output("payslip.pdf","S");
        return $output;
        //if (!empty($output)) {
        //	$data = base64_encode($output);
        //    return $data;
        //}
    }
    
    function get_StatusEmp($emp_list = array()){
		$list = implode(",",$emp_list['chkAttend']);
		$sql = "select em.emp_id, CONCAT(pi.pi_fname,' ',pi.pi_lname) as fullname
				from emp_personal_info pi
    			join emp_masterfile em on (em.pi_id=pi.pi_id)
    			where emp_id in (".$list.")";
		$rsResult = $this->conn->Execute($sql);
    	if (!$rsResult->EOF) {
			while(!$rsResult->EOF){
				$arr[] = $rsResult->fields;
				$rsResult->MoveNext();
			}
		}
		return $arr;
    }
    
    function sendFEAPFormatPayslip($oData = array(), $i){
    	$orientation = 'P'; // P for Portrait, L for Landscape
		$unit = 'mm'; 		// (string) User measure unit. Possible values are: pt: point, mm: millimeter (default), cm: centimeter, and in: inch
		$format = 'LETTER'; // LETTER, USLETTER, ORGANIZERM (216x279 mm ; 8.50x11.00 in)
		$unicode = true;
		$encoding="UTF-8";
		
        $pdf = new clsPDF($orientation, $unit, $format, $unicode, $encoding);
        $pdf->SetProtection($permissions=array('print', 'copy'), $user_pass=$this->getPayslipPassword($oData[$i]['emp_id']), $owner_pass=null, $mode=0, $pubkeys=null);
		$objClsMngeDecimal = new Application();
		
		// remove default header/footer
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		
        //set margins
		$pdf_margin_top = 12;
		$pdf_margin_left_and_right = 22.5;

		$pdf->SetMargins($pdf_margin_left_and_right, $pdf_margin_top, $pdf_margin_left_and_right);
//        $oPDF->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
//        $oPDF->SetHeaderMargin(PDF_MARGIN_HEADER);

        //set auto page breaks
		$pdf_margin_bottom = 0;
		$pdf->SetAutoPageBreak(TRUE, $pdf_margin_bottom);
		
		//set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		
		//set some language-dependent strings
		$pdf->setLanguageArray($l); 
		
		$i = 0;
        $count_emp = 0;
		$pdf->AddPage(); // Set initial pdf page
		$pdf->SetFont("dejavuserif", '', 8); // Set Font Type & Size

		//used to line style...
		$style4 = array('dash' => 1,1);
		$style5 = array('dash' => 2,2);
		$style6 = array('dash' => 0);
				
		// set initila coordinates
		$coordX = $pdf_margin_left_and_right;
		$coordY = $pdf_margin_top;
		$coordYtop = 6;
				
        //Setup Borders
		// Top Line
		$width = $coordX + 78;
		$pdf->Line($coordX,$coordY,$coordX + $width,$coordY,$style5);
		// Bottom Line
		$height = 256.5;
		$total_height = $coordY + $height;
		$pdf->Line($coordX,$total_height,$coordX + $width,$total_height,$style5);
		// Vertical Line
		$middle = 123;
		$pdf->Line($middle,$coordY,$middle,$total_height,$style5);
		
		//header
		$pdf->Ln(10.6);
		$pdf->MultiCell($box_width = 83, $box_height = 0, 'EMPLOYEE PAYSLIP', $border = 0, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
//		$pdf->MultiCell(100 - $box_width, $box_height, 'No.   '.$number, $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		
		$pdf->Ln(3.7);
		$pdf->MultiCell(100, $box_height,"PAYROLL PERIOD : ".date("m/d/Y",strtotime($oData[$i]['paystubdetails']['paystubdetail']['paystubsched']['payperiod_start_date']))." to ".date("m/d/Y",strtotime($oData[$i]['paystubdetails']['paystubdetail']['paystubsched']['payperiod_end_date'])), $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		
		$pdf->Ln(3.7);
		$pdf->MultiCell(100, $box_height,"PAYOUT DATE       : ".date("m/d/Y",strtotime($oData[$i]['paystubdetails']['paystubdetail']['paystubsched']['payperiod_trans_date'])), $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		
		$pdf->Ln(3.7);
		$pdf->MultiCell(100, $box_height,$oData[$i]['paystubdetails']['empinfo']['comp_name'], $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		
		$pdf->Ln(3.7);
		$pdf->MultiCell($box_width = 18, $box_height, $oData[$i]['paystubdetails']['empinfo']['emp_no'], $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		$pdf->MultiCell(100 - $box_width, $box_height,strtoupper($oData[$i]['paystubdetails']['empinfo']['fullname']), $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		
		$pdf->Ln(3.7);
		$pdf->MultiCell($box_width = 51, $box_height, "Dept : ".$oData[$i]['paystubdetails']['empinfo']['ud_name'], $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		$pdf->MultiCell(100 - $box_width, $box_height, "Exempt Code : ".$oData[$i]['paystubdetails']['empinfo']['taxep_code'], $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		
		//BODY
		//Leave Balance
		$leave_record_ = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['leave_record'];
		IF(count($leave_record_)>0){
			FOR($v=0;$v<count($leave_record_);$v++){
				IF($leave_record_[$v]['leave_name']=='VL'){
					$vl_credit = number_format($leave_record_[$v]['empleave_creadit'],2);
					$vl_bal = number_format($leave_record_[$v]['empleave_available_day'],2);
					$vl_taken = number_format($leave_record_[$v]['empleave_used_day'],2);
				}
				IF($leave_record_[$v]['leave_name']=='SL'){
					$sl_credit = number_format($leave_record_[$v]['empleave_creadit'],2);
					$sl_bal = number_format($leave_record_[$v]['empleave_available_day'],2);
					$sl_taken = number_format($leave_record_[$v]['empleave_used_day'],2);
				}
            }
		}
		//GET MONTHLY
		$salaryType = $oData[$i]['paystubdetails']['empinfo']['salarytype_id'];
		IF($salaryType == '2'){
			$monthlyRATE_ = $this->getMonthly_RATE($oData[$i]['paystubdetails']['empinfo']['emp_id'],$oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['earning']['basic']);
		}ELSE{
			$monthlyRATE_ = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['earning']['basic'];
		}
		$pdf->Ln(17);
		$pdf->MultiCell($box_width = 27, $box_height, 'Salary Rates', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		$pdf->MultiCell($box_width_2 = 15.5, $box_height, 'Monthly', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		$pdf->MultiCell($box_width_3 = 24, $box_height, number_format($monthlyRATE_,2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		$pdf->MultiCell(100 - ($box_width + $box_width_2 + $box_width_3), $box_height, "  VL   ".$vl_bal, $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		
		$pdf->Ln(3.7);
		$pdf->MultiCell($box_width = 27, $box_height, '', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		$pdf->MultiCell($box_width_2 = 15.5, $box_height, 'Daily', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		$pdf->MultiCell($box_width_3 = 24, $box_height, number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['earning']['DailyRate'],2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		$pdf->MultiCell(100 - ($box_width + $box_width_2 + $box_width_3), $box_height, "  SL   ".$sl_bal, $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		
		$pdf->Ln(3.7);
		$pdf->MultiCell($box_width = 27, $box_height, '', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		$pdf->MultiCell($box_width_2 = 15.5, $box_height, 'Hourly', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		$pdf->MultiCell($box_width_3 = 24, $box_height, number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['earning']['HourlyRate'],2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		$pdf->MultiCell(100 - ($box_width + $box_width_2 + $box_width_3), $box_height, "", $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		
		$pdf->Ln(10);
		$pdf->MultiCell($box_width = 23.5, $box_height, '', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		$pdf->MultiCell($box_width_2 = 25.5, $box_height, 'Attendance', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		$pdf->MultiCell($box_width_3 = 24, $box_height, 'Earnings', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		$pdf->MultiCell(100 - ($box_width + $box_width_2 + $box_width_3), $box_height, "Deductions", $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');

		//Basic Income & work days
		$pdf->Ln(7.4);
		$pdf->MultiCell($box_width = 27, $box_height, 'Workdays', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		$pdf->MultiCell($box_width_2 = 15.5, $box_height, number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['earning']['totalDays'],2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		$pdf->MultiCell($box_width_3 = 24, $box_height, number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['earning']['Regulartime'],2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		$pdf->MultiCell(100 - ($box_width + $box_width_2 + $box_width_3), $box_height, "", $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		
		//Leave/TA Listing Deduction
		$psa_ta = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['TUA']['TADetails'];
	    $total_ta = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['TUA']['TotalLeave'];
        IF(count($psa_ta)>0){
            IF($total_ta != 0){
				FOR($k=0;$k<count($psa_ta);$k++){
            		IF($psa_ta[$k]['ta_name']!='Custom Days'){
            			$pdf->Ln(3.7);
						$pdf->MultiCell($box_width = 27, $box_height, substr(trim($psa_ta[$k]['ta_name']),0,12), $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
						$pdf->MultiCell($box_width_2 = 15.5, $box_height, number_format($psa_ta[$k]['totaltimehr'],2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
						$pdf->MultiCell($box_width_3 = 24, $box_height, "", $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
						$pdf->MultiCell(90 - ($box_width + $box_width_2 + $box_width_3), $box_height, number_format($psa_ta[$k]['taamount'],2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
            		}
                }
            }
        }
        
		$pdf->Ln(3.7);
		$pdf->MultiCell($box_width = 100, $box_height, '', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');

		// OT listing
		$psa_OT = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['earning']['OT']['OTDetails'];  
        IF(count($psa_OT)>0){
            FOR($k=0;$k<count($psa_OT);$k++){
            	$pdf->Ln(3.7);
				$pdf->MultiCell($box_width = 27, $box_height, substr(trim($psa_OT[$k]['ot_name']),0,12), $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
				$pdf->MultiCell($box_width_2 = 15.5, $box_height, $psa_OT[$k]['totaltimehr'], $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
				$pdf->MultiCell($box_width_3 = 24, $box_height, $psa_OT[$k]['otamount'], $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
				$pdf->MultiCell(100 - ($box_width + $box_width_2 + $box_width_3), $box_height, "", $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
	        }
        }
        
        $pdf->Ln(3.7);
		$pdf->MultiCell($box_width = 100, $box_height, '', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');

		// OTHER Earning Income
		// benifits listing
        $psa_benifits = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['benefits'];            
        IF(count($psa_benifits)>0){
			FOR($v=0;$v<count($psa_benifits);$v++){
                IF($psa_benifits[$v]['psa_type']!=2){
                	IF($psa_benifits[$v]['ben_payperday'] > 0){
	                	$pdf->Ln(3.7);
						$pdf->MultiCell($box_width = 42.50, $box_height, substr(trim($psa_benifits[$v]['psa_name']),0,12), $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
						$pdf->MultiCell($box_width_3 = 24, $box_height, number_format($psa_benifits[$v]['ben_payperday'],2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
                	}
                }
			}
        }
        
		// Amendment listing
		$psa_amendment = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['amendments'][0];
        for($a=0;$a<count($psa_amendment) ;$a++){
            if ($psa_amendment[$a]['psa_type']==1) {
            	IF($psa_amendment[$a]['amendemp_amount'] > 0){
	            	$pdf->Ln(3.7);
					$pdf->MultiCell($box_width = 42.50, $box_height, substr(trim($psa_amendment[$a]['psa_name']),0,12), $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
					$pdf->MultiCell($box_width_3 = 24, $box_height, number_format($psa_amendment[$a]['amendemp_amount'],2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
            	}
            }
        }
        
        $pdf->Ln(3.7);
		$pdf->MultiCell($box_width = 100, $box_height, '', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
        
        //OTHER DEDUCTION INCOME
        //W/H TAX
        $taxwh = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['W/H Tax'];
		$pdf->Ln(3.7);
		$pdf->MultiCell($box_width = 27, $box_height, 'WTAX', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		$pdf->MultiCell(90 - $box_width, $box_height, number_format($taxwh,2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');

		//SSS
		$SSS = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['deduction']['SSS'];
		$pdf->Ln(3.7);
		$pdf->MultiCell($box_width = 27, $box_height, 'SSS', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		$pdf->MultiCell(90 - $box_width, $box_height, number_format($SSS,2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');

		//HDMF
		$HDMF = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['deduction']['Pag-ibig'];
		$pdf->Ln(3.7);
		$pdf->MultiCell($box_width = 27, $box_height, 'HDMF', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		$pdf->MultiCell(90 - $box_width, $box_height, number_format($HDMF,2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');

		//PHIC
		$PHIC = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['deduction']['PhilHealth'];
		$pdf->Ln(3.7);
		$pdf->MultiCell($box_width = 27, $box_height, 'PHILHEALTH', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		$pdf->MultiCell(90 - $box_width, $box_height, number_format($PHIC,2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		
		//TOTAL Loan Deduction
		$pdf->Ln(3.7);
		$pdf->MultiCell($box_width = 27, $box_height, 'Loan Deduction', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		$pdf->MultiCell(90 - $box_width, $box_height, number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['Loan_Total'],2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		
		//amendment deduction
        $psa_amendment = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['amendments'][0];
        if(count($psa_amendment)>0){
			foreach($psa_amendment as $key => $val){
                IF ($val['psa_type']==2) {
                	IF($val['amendemp_amount'] > 0){
	                    $pdf->Ln(3.7);
						$pdf->MultiCell($box_width = 42.50, $box_height, substr(trim($val['psa_name']),0,12), $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
						$pdf->MultiCell(90 - $box_width, $box_height, number_format($val['amendemp_amount'],2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
                	}
                }
            }
         }
         
		// benifits Deduction
        $psa_benifits = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['benefits'];            
        IF(count($psa_benifits)>0){
            FOR($v=0;$v<count($psa_benifits);$v++){
                IF($psa_benifits[$v]['psa_type']!=1){
                	IF($psa_benifits[$v]['ben_payperday'] > 0){
                	$pdf->Ln(3.7);
					$pdf->MultiCell($box_width = 42.50, $box_height, substr(trim($psa_benifits[$v]['psa_name']),0,12), $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
					$pdf->MultiCell(90 - $box_width, $box_height, number_format($psa_benifits[$v]['ben_payperday'],2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
                	}
            	}	
            }
        }

		$pdf->Ln(3.7);
		$pdf->MultiCell($box_width = 100, $box_height, '', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		
		//GRAND TOTALS 
		$pdf->Ln(3.7);
		$pdf->MultiCell($box_width = 27, $box_height, 'TOTALS', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		$pdf->MultiCell($box_width_2 = 15.5, $box_height, '', $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		$pdf->MultiCell($box_width_3 = 24, $box_height, number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['TotalEarning_payslip'],2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		$pdf->MultiCell(90 - ($box_width + $box_width_2 + $box_width_3), $box_height, number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['Deduction'],2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');

		$pdf->Ln(3.7);
		$pdf->MultiCell($box_width = 100, $box_height, '', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		
		//NETPAY
		$pdf->Ln(3.7);
		$pdf->MultiCell($box_width = 27, $box_height, 'NETPAY', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		$pdf->MultiCell(90 - $box_width, $box_height, number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['Net Pay'],2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');

		$pdf->Ln(3.7);
		$pdf->MultiCell($box_width = 100, $box_height, '', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');

		$pdf->Ln(3.7);
		$pdf->MultiCell($box_width = 100, $box_height, '',$pdf->Line($coordX,$coordY,$coordX + $width,$coordY,$style5), 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		
		//LOAN DETAILS
		$pdf->Ln(4.7);
		$pdf->MultiCell($box_width = 100, $box_height, 'LOAN DEDUCTIONS', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		
		$pdf->Ln(3.7);
		$pdf->MultiCell($box_width = 27.5, $box_height, 'LOAN AMT', $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		$pdf->MultiCell($box_width_2 = 19, $box_height, 'TOT PAID', $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		$pdf->MultiCell($box_width_3 = 20, $box_height, "CURR DED", $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		$pdf->MultiCell(90 - ($box_width + $box_width_2 + $box_width_3), $box_height, "BALANCE", $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		
		//Loan listing
		$loan_info_ = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['government_regular'];
		FOR($v=0;$v<count($loan_info_);$v++){
			$pdf->Ln(3.7);
			$pdf->MultiCell($box_width = 10, $box_height, substr(trim($loan_info_[$v]['psa_name']),0,3), $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
			$pdf->MultiCell($box_width_2 = 17.5, $box_height, number_format($loan_info_[$v]['loan_principal'],2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
			$pdf->MultiCell($box_width_3 = 19, $box_height, number_format($loan_info_[$v]['loan_ytd'],2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
			$pdf->MultiCell($box_width_4 = 20, $box_height, number_format($loan_info_[$v]['loan_payperperiod'],2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
			$pdf->MultiCell(90 - ($box_width + $box_width_2 + $box_width_3 + $box_width_4), $box_height, number_format($loan_info_[$v]['loan_balance'],2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		}

		$pdf->Ln(5);
		$pdf->MultiCell(90, $box_height, "", $style5, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		
		$pdf->Ln(3.7);
		$pdf->MultiCell(100, $box_height, "Any discrepancies noted should be cleared with", $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		
		$pdf->Ln(3.7);
		$pdf->MultiCell(100, $box_height, "Payroll Staff within 3 days.", $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
        
		$output = $pdf->Output("payslip.pdf","S");
		return $output;
        /*if (!empty($output)) {
        	$data = base64_encode($output);
            return $data;
        }*/
    
    }
    
    function getPayslipPassword($emp_id_ = ""){
    	$sql = "select ps_passwd_password from app_ps_passwd where emp_id=?";
    	$rsResult = $this->conn->Execute($sql,array($emp_id_));
		if(!$rsResult->EOF){
			$passwd = clsEncryptHelper::decrypt($rsResult->fields['ps_passwd_password'],BASE_URL);
			return $passwd;
		}
    }
    
}