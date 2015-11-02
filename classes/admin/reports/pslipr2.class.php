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
class clsPslipR{

	var $conn;
	var $fieldMap;
	var $Data;

	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsPslipR object
	 */
	function clsPslipR($dbconn_ = null){
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
		$sql = "";
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
				$qry[] = "pps_name like '%$search_field%' || payperiod_trans_date like '%$search_field%'";

			}
		}

		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		 "pps_name" => "pps_name"
		,"salaryclass_id" => "salaryclass_id"
		,"pp_stat_id" => "pp_stat_id"
		,"payperiod_start_date" => "payperiod_start_date"
		,"payperiod_end_date" => "payperiod_end_date"
		,"payperiod_trans_date" => "payperiod_trans_date"
		);

		if(isset($_GET['sortby'])){
			$strOrderBy = " group by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof']." , ".payperiod_id;
		}else{
			$strOrderBy = "group by ".payperiod_id;
		}
		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$viewLink = "<a href=\"?statpos=pslipr&edit=',ppp.payperiod_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/printer.png\" title=\"View Payslip\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
//		$editLink = "<a href=\"?statpos=pslipr&edit=',am.mnu_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/edit.png\" title=\"Edit\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
//		$delLink = "<a href=\"?statpos=pslipr&delete=',am.mnu_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\"  border=0 width=\"16\" height=\"16\"></a>";
		// SqlAll Query
		$sql = "select ppp.*, CONCAT('$viewLink') as viewdata,
				DATE_FORMAT(payperiod_start_date,'%d %b %Y %h:%i %p') as payperiod_start_date,
				DATE_FORMAT(payperiod_end_date,'%d %b %Y %h:%i %p') as payperiod_end_date,
				DATE_FORMAT(payperiod_trans_date,'%d %b %Y') as payperiod_trans_date,psar.pps_name, 
				if(salaryclass_id='1','Daily',IF(salaryclass_id='2','Weekly',IF(salaryclass_id='3','Bi-Weekly',IF(salaryclass_id='4','Semi-monthly',IF(salaryclass_id='5','Monthly','Annual'))))) as salaryclass_id,
				IF(pp_stat_id='1','OPEN',IF(pp_stat_id='2','Locked - Pending Approval',IF(pp_stat_id='3','CLOSED','Post Adjustment'))) as pp_stat_id
						from payroll_paystub_report ppr
						inner join payroll_pay_period ppp on (ppr.payperiod_id=ppp.payperiod_id)
						inner join payroll_pay_period_sched psar on (psar.pps_id=ppp.pps_id)
						$criteria
						$strOrderBy";
		// Field and Table Header Mapping
		$arrFields = array(
		 "viewdata" => "Action"
		,"pps_name" => "Name"
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
		"salaryclass_name"=>"width='120'",
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
	 * Get the records from the database
	 *
	 * @param string $id_
	 * @return array
	 */
	function dbFetch_Payslip($id_ = "", $emp_id_="" ){
//		$this->conn->debug=1;
		$qry = array();
		if (!is_null($id_)) {
			$qry[] = "b.payperiod_id ='".$id_."'";
		}
		if(is_null($emp_id_)||$emp_id_=""){
			$qry[] = "a.emp_id ='".$emp_id_."'";
		}
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";
		$strOrderBy = "order by dep.ud_name";
		$sql = "Select a.*, DATE_FORMAT(b.payperiod_start_date, '%d %b %Y') as start_date, DATE_FORMAT(b.payperiod_end_date, '%d %b %Y') as end_date
					from payroll_paystub_report a
					inner join payroll_pay_period b on (a.payperiod_id=b.payperiod_id)
					inner join emp_masterfile c on (a.emp_id=c.emp_id)
					inner join app_userdept dep on (dep.ud_id=c.ud_id)
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
	function getPDFResult($oData = array()){
//		printa($oData);
//		exit;
        $orientation='P';
        $unit='mm';
        $format='LETTER';
        $unicode=true;
        $encoding="UTF-8";
        $oPDF = new clsPDF($orientation, $unit, $format, $unicode, $encoding);
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
        	if(($i % 2)==0){
        		$oPDF->AddPage();
        		$oPDF->SetFillColor(255,255,255);
		        // set initial coordinates
		        $coordX = 2;
		        $coordY = 26;
		        $coordYtop = 26;
		        $end = 85;
	        }else{
	        	$oPDF->SetFillColor(255,255,255);
		        // set initial coordinates
		        $coordX = 2;
		        $coordY = 160;
		        $coordYtop = 160;
		        $end = 220;
	        }
	        //used to line style...
	        $style4 = array('dash' => 1,1);
			$style5 = array('dash' => 2,2);
			$style6 = array('dash' => 0);
			$oPDF->Line($coordX, $coordY-3, $coordX+212, $coordY-3, $style = $style5);//line after header
			
			$oPDF->Image(SYSCONFIG_ROOT_PATH2.SYSCONFIG_DEFAULT_IMAGES_INCTEMP.'icons/edited/payrollLOGO2.png',$coordX+1, $coordY-1, 30, 5, '', 'http://www.sophieparis.com.ph/', '', false, 300,'L');
			
			//Receiving Area
			//---------------------------------------------------->>
			$oPDF->SetFont('courier','B',9);
	        $oPDF->SetXY($coordX+170, $coordY+1);
	        $oPDF->MultiCell(42, 2,$oData[$i]['paystubdetails']['empinfo']['comp_name'],0,'C',1);
	        $oPDF->SetFont('courier','B',9);
	        $oPDF->SetXY($coordX+170, $coordY+33);
	        $oPDF->MultiCell(42, 2,date("Md,Y",strtotime($oData[$i]['paystubdetails']['paystubdetail']['paystubsched']['payperiod_start_date'])).' to '.date("Md,Y",strtotime($oData[$i]['paystubdetails']['paystubdetail']['paystubsched']['payperiod_end_date'])),0,'C',1);
			$oPDF->SetFont('courier','',9);
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
	        $oPDF->SetFont('courier','B',9);
	        $oPDF->SetXY($coordX+170, $coordY+61);
	        $oPDF->MultiCell(41, 2,number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['Net Pay'],2),0,'R',1);
	        $oPDF->SetXY($coordX+171, $coordY+61);
	        $oPDF->MultiCell(41, 2,'P',0,'L',1);
	        $oPDF->SetFont('courier','',8);
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
			$oPDF->SetFont('courier','',9);
			$coordY = $coordY + 3; 
	        $oPDF->SetXY($coordX, $coordY);
	        $oPDF->MultiCell(200, 2,$oData[$i]['paystubdetails']['empinfo']['comp_add'],0,'L',1);
	        $coordY+=$oPDF->getFontSize()+1;
	        
	       	$oPDF->SetFont('courier','',9);
	        $oPDF->SetXY($coordX, $coordY);
	        $oPDF->MultiCell(15, 2, "Name/ID",0,'L',1);
	        $oPDF->SetFont('courier','B',9);
	        $oPDF->SetXY($coordX+13, $coordY);
	        $oPDF->MultiCell(85, 2, ':  '.strtoupper($oData[$i]['paystubdetails']['empinfo']['fullname']).' ('.$oData[$i]['paystubdetails']['empinfo']['emp_no'].')',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
	            
	        $oPDF->SetFont('courier','',9);
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
	        	$oPDF->SetFont('courier','B',9);
	            $oPDF->SetXY($coordX, $coordY);
	            $oPDF->MultiCell(84.5, 2, 'EARNINGS',0,'C',1, 0, 0, 0, TRUE, 0, TRUE);
	            $oPDF->SetXY($coordX+84.5, $coordY);
	            $oPDF->MultiCell(84.5, 2, "DEDUCTIONS",0,'C',1, 0, 0, 0, TRUE, 0, TRUE);
	            $oPDF->SetFont('courier','',9);
	            $coordY+=$oPDF->getFontSize()+2;
				$oPDF->Line($coordX, $coordY, $coordX+169, $coordY, $style=$style6);//bottom line
				
	            $y_2nd = $coordY;
	        //Earnings COLUMN
	        	//head
	        	$nodays = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['earning']['totalDays'];
	        	if($nodays!=''){
		        	$oPDF->SetXY($coordX+20, $coordY);
		            $oPDF->MultiCell(58, 2, "Rate",0,'L',1);
		            $oPDF->SetXY($coordX+40, $coordY);
		            $oPDF->MultiCell(20, 2, "Days",0,'L',1);
		            $coordY+=$oPDF->getFontSize();
	        	}
	        	//Basic Details
	            $oPDF->SetXY($coordX+2, $coordY);
	            $oPDF->MultiCell(58, 2, "BASIC",0,'L',1);
	            if($nodays!=''){
		            $oPDF->SetXY($coordX+20, $coordY);
			        $oPDF->MultiCell(20, 2, number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['earning']['basic'],2),0,'L',1);
		            $oPDF->SetXY($coordX+40, $coordY);
			        $oPDF->MultiCell(20, 2, $nodays,0,'L',1);
	            }
	            $oPDF->SetXY($coordX+60, $coordY);
	            $oPDF->MultiCell(24.5, 2, number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['earning']['Regulartime'],2),0,'R',1);
	            $coordY+=$oPDF->getFontSize();
	            
	            //COLA Details
	            $colaAmount = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['earning']['COLA'];
	            if($colaAmount != ''){
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
//	                for($k=0;$k<count($psa_OT);$k++){
//		                $oPDF->SetXY($coordX+4, $coordY);
//		                $oPDF->MultiCell(8, 2, $psa_OT[$k]['totaltimehr'],0,'L',1);
//		                $oPDF->SetXY($coordX+8, $coordY);
//		                $oPDF->MultiCell(8, 2,' H ',0,'R',1);
//		                $oPDF->SetXY($coordX+14, $coordY);
//		                $oPDF->MultiCell(28, 2, $psa_OT[$k]['rate'].'@'.number_format($psa_OT[$k]['rateperhr'],2).' = ',0,'R',1);
//		                $oPDF->SetXY($coordX+40, $coordY);
//		                $oPDF->MultiCell(28, 2, number_format($psa_OT[$k]['otamount'],2),0,'L',1);
//		                $coordY+=$oPDF->getFontSize();
//	                }
	                $oPDF->SetXY($coordX+2, $coordY);
		            $oPDF->MultiCell(58, 2, 'Total OverTime',0,'L',1);
		            $oPDF->SetXY($coordX+60, $coordY);
		            $oPDF->MultiCell(24.5, 2, number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['earning']['OT']['TotalallOT'],2),0,'R',1);
		            $coordY+=$oPDF->getFontSize();
	            }
				// Amendment listing
				$psa_amendment = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['amendments'][0];
	            for($a=0;$a<count($psa_amendment) ;$a++){
	                if ($psa_amendment[$a]['psa_type']==1) {
		                $oPDF->SetXY($coordX+2, $coordY);
		                $oPDF->MultiCell(58, 2, $psa_amendment[$a]['psa_name'],0,'L',1);
		                $oPDF->SetXY($coordX+60, $coordY);
		                $oPDF->MultiCell(24.5, 2, number_format($psa_amendment[$a]['amendemp_amount'],2),0,'R',1);
		                $coordY+=$oPDF->getFontSize();
	                }
	            }
	            // benifits listing
	            $psa_benifits = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['benefits'];            
	            if(count($psa_benifits)>0){
	                for($v=0;$v<count($psa_benifits);$v++){
	                	if($psa_benifits[$v]['psa_type']!=2){
			                $oPDF->SetXY($coordX+2, $coordY);
			                $oPDF->MultiCell(58, 2, $psa_benifits[$v]['psa_name'],0,'L',1);
			                $oPDF->SetXY($coordX+60, $coordY);
			                $oPDF->MultiCell(24.5, 2, number_format($psa_benifits[$v]['ben_payperday'],2),0,'R',1);
			                $coordY+=$oPDF->getFontSize();
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
				if($taxwh != '0.00'){
	            $oPDF->SetXY($coordX+87, $coordY);
	            $oPDF->MultiCell(60, 2, "W/H TAX",0,'L',1);
	            $oPDF->SetXY($coordX+146, $coordY);
	            $oPDF->MultiCell(24, 2, number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['W/H Tax'],2),0,'R',1);
	            $coordY+=$oPDF->getFontSize()+0;
				}
	            $oPDF->SetXY($coordX+87, $coordY);
	            $oPDF->MultiCell(60, 2, "HDMF",0,'L',1);
	            $oPDF->SetXY($coordX+146, $coordY);
	            $oPDF->MultiCell(24, 2, number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['deduction']['Pag-ibig'],2),0,'R',1);
	            $coordY+=$oPDF->getFontSize()+0;
	
	            $oPDF->SetXY($coordX+87, $coordY);
	            $oPDF->MultiCell(60, 2, "PHIC",0,'L',1);
	            $oPDF->SetXY($coordX+146, $coordY);
	            $oPDF->MultiCell(24, 2, number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['deduction']['PhilHealth'],2),0,'R',1);
	            $coordY+=$oPDF->getFontSize()+0;
	
	            $oPDF->SetXY($coordX+87, $coordY);
	            $oPDF->MultiCell(60, 2, "SSS",0,'L',1);
	            $oPDF->SetXY($coordX+146, $coordY);
	            $oPDF->MultiCell(24, 2, number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['deduction']['SSS'],2),0,'R',1);
	            $coordY+=$oPDF->getFontSize()+0;
	            
	            // Leave/TA Listing
		        $psa_ta = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['TUA']['TADetails'];
	            if(count($psa_ta)>0){
//	            	@note: hide for January Payroll
	            	for($k=0;$k<count($psa_ta);$k++){
//	            		$oPDF->SetXY($coordX+90, $coordY);
//		                $oPDF->MultiCell(8, 2, $psa_ta[$k]['totaltimehr'],0,'L',1);
//		                $oPDF->SetXY($coordX+95, $coordY);
//		                $oPDF->MultiCell(8, 2,$psa_ta[$k]['ratetype'],0,'R',1);
//		                $oPDF->SetXY($coordX+100, $coordY);
//		                $oPDF->MultiCell(20, 2, '@'.number_format($psa_ta[$k]['rateperDH'],2).' = ',0,'R',1);
//		                $oPDF->SetXY($coordX+120, $coordY);
//		                $oPDF->MultiCell(20, 2, number_format($psa_ta[$k]['taamount'],2),0,'L',1);
//		                $coordY+=$oPDF->getFontSize();
//	                }
					$oPDF->SetXY($coordX+87, $coordY);
	                $oPDF->MultiCell(60, 2, $psa_ta[$k]['ta_name'],0,'L',1);
	                $oPDF->SetXY($coordX+146, $coordY);
	                $oPDF->MultiCell(24, 2, number_format($psa_ta[$k]['taamount'],2),0,'R',1);
	                $coordY+=$oPDF->getFontSize();
//	                $oPDF->SetXY($coordX+87, $coordY);
//	                $oPDF->MultiCell(60, 2, 'Total Leave Deduction',0,'L',1);
//	                $oPDF->SetXY($coordX+146, $coordY);
//	                $oPDF->MultiCell(24, 2, number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['TUA']['TotalLeave'],2),0,'R',1);
//	                $coordY+=$oPDF->getFontSize();
	            	}
	            }
				
	            //amendment deduction
	            $psa_amendment = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['amendments'][0];
	            if(count($psa_amendment)>0){
	                foreach($psa_amendment as $key => $val){
	                    if ($val['psa_type']==2) {
	                    $oPDF->SetXY($coordX+87, $coordY);
	                    $oPDF->MultiCell(60, 2, $val['psa_name'],0,'L',1);
	                    $oPDF->SetXY($coordX+146, $coordY);
	                    $oPDF->MultiCell(24, 2, number_format($val['amendemp_amount'],2),0,'R',1);
	                    $coordY+=$oPDF->getFontSize();
	                    }
	                }
	            }
	            
	            // Benifits Deduction
        		$psa_benifits = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['benefits'];            
	            if(count($psa_benifits)>0){
	                for($v=0;$v<count($psa_benifits);$v++){
	                	if($psa_benifits[$v]['psa_type']!=1){
			                $oPDF->SetXY($coordX+87, $coordY);
			                $oPDF->MultiCell(60, 2, $psa_benifits[$v]['psa_name'],0,'L',1);
			                $oPDF->SetXY($coordX+146, $coordY);
			                $oPDF->MultiCell(24, 2, number_format($psa_benifits[$v]['ben_payperday'],2),0,'R',1);
			                $coordY+=$oPDF->getFontSize();
	                	}
	                }
	            }
	            
	            //Loan listing
				$loan_info_ = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['government_regular'];
				for($v=0;$v<count($loan_info_);$v++){
	                $oPDF->SetXY($coordX+87, $coordY);
	                $oPDF->MultiCell(60, 2, $loan_info_[$v]['psa_name'],0,'L',1);
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
	            $oPDF->Line($coordX, $coordY+23, $coordX+212, $end+23, $style=$style5);//bottom line
	            $oPDF->Line($coordX+84.5, $coordY+1, $coordX+84.5, $end+21,$style = $style6);//bottom middle line
	            $oPDF->Line($coordX+170, $coordYtop, $coordX+170, $end+21, $style = $style4);//out right line
	            
	            $oPDF->SetXY($coordX, $coordY);
	            $oPDF->MultiCell(16, 2, "BANK  :",0,'L',1);
	            $oPDF->SetXY($coordX+15, $coordY);
	            $oPDF->MultiCell(100, 2, $oData[$i]['paystubdetails']['empinfo']['banklist_name'].' / '.$oData[$i]['paystubdetails']['empinfo']['bankiemp_acct_no'],0,'L',1);
	            
	            $oPDF->SetFont('courier','B',9);
	            $oPDF->SetXY($coordX+93, $coordY);
	            $oPDF->MultiCell(25, 2,'NET PAY',0,'L',1);
	            $oPDF->SetXY($coordX+120, $coordY);
	            $oPDF->MultiCell(25, 2, ":  ".number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['Net Pay'],2),0,'L',1);
	            $coordY+=$oPDF->getFontSize()+1;
	            $oPDF->Line($coordX+93, $coordY+1, $coordX+160, $coordY+1, $style=$style6);//netpay line
//	            @note: hide for January Payroll
//	            $leave_record_ = $oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['leave_record'];
//				$coordY_leave = $coordY;
//				if(count($leave_record_)>0){
//					$oPDF->SetFont('dejavusans','B',7);
//					$oPDF->SetXY($coordX+40, $coordY);
//		            $oPDF->MultiCell(15, 2, "Credit",0,'L',1);
//		            $oPDF->SetXY($coordX+55, $coordY);
//		            $oPDF->MultiCell(15, 2, "Bal",0,'L',1);
//		            $oPDF->SetXY($coordX+70, $coordY);
//		            $oPDF->MultiCell(15, 2,'Taken',0,'L',1);
//		            $coordY+=$oPDF->getFontSize()-2;
//		            $oPDF->SetFont('dejavusans','',7);
//					for($v=0;$v<count($leave_record_);$v++){
//		                $oPDF->SetXY($coordX, $coordY_leave+3);
//		                $oPDF->MultiCell(40, 2, $leave_record_[$v]['leave_name'],0,'L',1);
//		                $oPDF->SetXY($coordX+40, $coordY_leave+3);
//		                $oPDF->MultiCell(15, 2, number_format($leave_record_[$v]['empleave_creadit'],2),0,'L',1);
//		                $oPDF->SetXY($coordX+55, $coordY_leave+3);
//		                $oPDF->MultiCell(15, 2, number_format($leave_record_[$v]['empleave_available_day'],2),0,'L',1);
//		                $oPDF->SetXY($coordX+70, $coordY_leave+3);
//		                $oPDF->MultiCell(15, 2, number_format($leave_record_[$v]['empleave_used_day'],2),0,'L',1);
//		                $coordY_leave+=$oPDF->getFontSize()+0;
//		            }
//				}
	            $oPDF->SetFont('courier','B',9);
	            $oPDF->SetXY($coordX+123, $coordY);
	            $oPDF->MultiCell(30, 2, "YTD",0,'L',1);
				$coordY+=$oPDF->getFontSize()+.5;
	            $oPDF->SetFont('courier','',9);
	            $oPDF->SetXY($coordX+93, $coordY);
	            $oPDF->MultiCell(30, 2, "GROSS PAY",0,'L',1);
	            $oPDF->SetXY($coordX+120, $coordY);
	            $oPDF->MultiCell(25, 2, ":   ".number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['TotalEarning_payslip'],2),0,'L',1);
	            $coordY+=$oPDF->getFontSize()+0;
	            
	            $oPDF->SetXY($coordX+93, $coordY);
	            $oPDF->MultiCell(30, 2, "TAXABLE GROSS",0,'L',1);
	            $oPDF->SetXY($coordX+120, $coordY);
	            $oPDF->MultiCell(25, 2, ":   ".number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['taxable_Gross'],2),0,'L',1);
	            $coordY+=$oPDF->getFontSize()+0;
	            
	            $oPDF->SetXY($coordX+93, $coordY);
	            $oPDF->MultiCell(30, 2, "W/H TAX",0,'L',1);
	            $oPDF->SetXY($coordX+120, $coordY);
	            $oPDF->MultiCell(25, 2, ":   ".number_format($oData[$i]['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['W/H Tax'],2),0,'L',1);
	            $coordY+=$oPDF->getFontSize()*2;
	            
	            $oPDF->SetXY($coordX, $coordY+1);
	            $oPDF->MultiCell(25, 2, "TAX STATUS",0,'L',1);
	            $oPDF->SetXY($coordX+19, $coordY+1);
	            $oPDF->MultiCell(90, 2, ': '.$oData[$i]['paystubdetails']['empinfo']['tax_ex_name'],0,'L',1);
	            $coordY+=$oPDF->getFontSize()+2;
        	}
        }
        // get the pdf output
        $output = $oPDF->Output("payslip_".$oData['paystubdetails']['empinfo']['fullname'].date('Y-m-d').".pdf");
        if(!empty($output)){
            return $output;
        }
    }
}

?>