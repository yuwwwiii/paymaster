<?php
/**
 * Initial Declaration
 */
require_once(SYSCONFIG_CLASS_PATH."util/PHPExcel.php");
require_once(SYSCONFIG_CLASS_PATH."util/PHPExcel/IOFactory.php");
require_once(SYSCONFIG_CLASS_PATH.'admin/reports/sss.class.php');
/**
 * Class Module
 *
 * @author  Jason I. Mabignay
 *
 */
class clsSSSCollection extends clsSSS{

	var $conn;
	var $fieldMap;
	var $Data;

	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsSSSCollection object
	 */
	function clsSSSCollection($dbconn_ = null) {
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
	function dbFetch($id_ = "") {
		$sql = "";
		$rsResult = $this->conn->Execute($sql,array($id_));
		if(!$rsResult->EOF){
			return $rsResult->fields;
		}
	}
	
	function dbFetchCollection($gData = array(), $isType = null) {
        $y = "".$gData['year']."-".$gData['month']."-01";
        $str1= date('F Y',dDate::parseDateTime($y));
        $enddate = dDate::getEndMonthEpoch(dDate::parseDateTime($y));
        $startdate = dDate::getBeginMonthEpoch(dDate::parseDateTime($y));
		$arrData = array();
		$qry = array();
		if($isType == '1'){
			$qry[] = "a.psa_id = 9";
		}elseif($isType == '2'){
			$qry[] = "a.psa_id = 13";
		}
		$objClsSSS = new clsSSS($this->conn);
		IF($objClsSSS->getSettings($gData['comp_id'],12) && ($gData['branchinfo_id'] != 0 || $gData['branchinfo_id'] != "N/A")){
        	$qry[] = "b.branchinfo_id ='".$gData['branchinfo_id']."'";
        }
		$qry[] = "a.loan_suspend = 0";
		$qry[] = "f.payperiod_period_year='".$gData['year']."'";
		$qry[] = "f.payperiod_period='".$gData['month']."'";
//		$qry[] = "a.loan_startdate = '".$str1."'";
		$criteria = count($qry)>0 ? " where ".implode(' and ',$qry) : '';
		$strOrderBy = " order by d.pi_lname";
		$sql = "SELECT distinct e.emp_id, a.*,d.*,b.*,upper(RPAD(d.pi_mname,1,'.')) as pi_mname, DATE_FORMAT(d.pi_bdate, '%m-%d-%Y') as bdate, ltype.loantype_code, d.pi_mname as pi_mnamefull
                FROM loan_info a
                inner join emp_masterfile b on (b.emp_id =a.emp_id)
				inner join emp_personal_info d on (d.pi_id = b.pi_id)
				left join loan_type ltype on (a.loantype_id=ltype.loantype_id)
				join payroll_paystub_report e on (e.emp_id=a.emp_id)
				join payroll_pay_period f on (f.payperiod_id=e.payperiod_id)
                $criteria
                $strOrderBy";
		$rsResult = $this->conn->Execute($sql);
        while (!$rsResult->EOF) {
            $arrData[] = $rsResult->fields;
            $rsResult->MoveNext();
        }
        for ($x = 0; $x < count($arrData); $x++) {
                $arrDataCollection[$x] = array(
                        "emp_info" => $arrData[$x],
                        "collection" => $this->getCollection($arrData[$x]['loan_id'],date('Y-m-d',$startdate),date('Y-m-d',$enddate),$gData),
                );
        }
//		printa($arrDataCollection);
//		exit;
		return $arrDataCollection;
	}
	
	/**
	 * Populate array parameters to Data Variable
	 *
	 * @param array $pData_
	 * @param boolean $isForm_
	 * @return bool
	 */
	function doPopulateData($pData_ = array(),$isForm_ = false) {
		if (count($pData_) > 0) {
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
	function doValidateData($pData_ = array()) {
		$isValid = true;
		if($pData_['date_paid'] == '0000-00-00'){
			$isValid = false;
			$_SESSION['eMsg'][] = "Invalid Date. Please enter valid date of payment.";
		}
//		$isValid = false;

		return $isValid;
	}

	/**
	 * Save New
	 *
	 */
	function doSaveAdd() {
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
	function doSaveEdit() {
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
	function doDelete($id_ = "") {
		$sql = "DELETE FROM /*app_modules*/ where mnu_id=?";
		$this->conn->Execute($sql,array($id_));
		$_SESSION['eMsg']="Successfully Deleted.";
	}

	/**
	 * Get all the Table Listings
	 *
	 * @return array
	 */
	function getTableList() {
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
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";

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
		$editLink = "<a href=\"?statpos=sss_collection&edit=',am.mnu_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/edit.png\" title=\"Edit\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
		$delLink = "<a href=\"?statpos=sss_collection&delete=',am.mnu_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\"  border=0 width=\"16\" height=\"16\"></a>";

		// SqlAll Query
		$sql = "select am.*, CONCAT('$viewLink','$editLink','$delLink') as viewdata
						from app_modules am
						$criteria
						$strOrderBy";

		// Field and Table Header Mapping
		$arrFields = array(
		 "viewdata"=>"Action"
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
	
	function getCollection($loan_id_ = null, $start_date_= null, $end_date_= null, $gData = array()) {
		$arrData = array();
		if (is_null($loan_id_)) {
			return $arrData;
		}
		$qry = array();
		$qry[] = "a.loan_id =$loan_id_";
        $qry[] = "d.ppr_status =1";
		$qry[] = "d.ppr_isdeleted =0";
		$qry[] = "ppp.payperiod_period='".$gData['month']."'";
		$qry[] = "ppp.payperiod_period_year='".$gData['year']."'";
//		$qry[] = "b.paystub_trans_date >='$start_date_'";
//		$qry[] = "b.paystub_trans_date <='$end_date_'";
		$criteria = count($qry)>0 ? " where ".implode(' and ',$qry) : '';
		$sql = "SELECT SUM(a.loansum_payment) as payment
                    FROM loan_detail_sum a
                    INNER JOIN payroll_pay_stub b ON (b.paystub_id = a.paystub_id)
                    INNER JOIN payroll_paystub_report d ON (b.paystub_id = d.paystub_id)
                    JOIN payroll_pay_period ppp on (ppp.payperiod_id=b.payperiod_id)
				$criteria
                GROUP BY a.loan_id";
		$rsResult = $this->conn->Execute($sql);
		if(!$rsResult->EOF){
			return $rsResult->fields;
		}
	}
	
	/**
     * @note: this function used to create R3 files Disket.
     * @param $arrData
     * @param $gData
     */
    function generateTxtFileLCL($arrData = array(), $gData = array(), $compData = array()) {
//    	printa($arrData);
//		printa($compData);
//		printa($gData);
      $GetDate = "".$gData['year']."-".$gData['month']."-01";
      $YYMM = date("ym", strtotime($GetDate));
//      exit;
      if (count($arrData) > 0) {
            $ctr = 0;
            $totalamntpaid = 0;
            //build hash header for the text hash report
	        $hashHeader_ ='00'.substr(str_replace("-", "", str_pad(trim($compData['comp_sss']),10,' ',STR_PAD_RIGHT)),0,10);
	        $hashHeader_ .= substr(str_pad(trim($compData['comp_name']),30,' ',STR_PAD_RIGHT),0,30);
	        $hashHeader_ .= str_pad($YYMM,4,' ',STR_PAD_RIGHT);
	        
            foreach ($arrData as $key => $val) {
            	$penalty = $val['emp_info']['loan_interestamount'];
            	$amntpaid = $val['collection'];
		    	$ctr++;
//		    	printa($val);
//		    	exit;
	            //build hash body for the text hash report
	            $text_hash = '10'.str_pad(str_replace('-',"",$val['emp_info']['pi_sss']),10,' ',STR_PAD_RIGHT);
	            $text_hash .= substr(str_pad($val['emp_info']['pi_lname'],15,' ',STR_PAD_RIGHT),0,15);
	            $text_hash .= substr(str_pad($val['emp_info']['pi_fname'],15,' ',STR_PAD_RIGHT),0,15);
	            $text_hash .= substr(str_pad($val['emp_info']['pi_mname'],2,' ',STR_PAD_RIGHT),0,2);
	            $text_hash .= substr(str_pad($val['emp_info']['loantype_code'],1,' ',STR_PAD_RIGHT),0,1);
	            $text_hash .= substr(str_pad(date("ymd",strtotime($val['emp_info']['loan_datepromissory'])),6,' ',STR_PAD_RIGHT),0,6);
	            $text_hash .= substr(str_pad(number_format($val['emp_info']['loan_principal'],2,'',''),6,'0',STR_PAD_LEFT),0,6);
	            $text_hash .= substr(str_pad(number_format($val['emp_info']['loan_interestamount'],2,'',''),7,'0',STR_PAD_LEFT),0,7);
	            $text_hash .= substr(str_pad(number_format($val['collection']['payment'],2,'',''),7,'0',STR_PAD_LEFT),0,7);
	            $text_hash .= '  ';
				$arr_txthash[$ctr]['txt_hash'] = $text_hash;         
            	$penalty += $penalty;
            	$totalamntpaid += $val['collection']['payment'];
            	$temp_ = $ctr;
            }
            //build hash footer for the text hash report
	        $hashFooter_ ='99'.substr(str_pad($temp_,4,'0',STR_PAD_LEFT),0,4);;
	        $hashFooter_ .= str_pad(number_format(substr($penalty,0,9),2,'',''),9,'0',STR_PAD_LEFT);
	        $hashFooter_ .= str_pad(number_format(substr($totalamntpaid,0,9),2,'.',''),9,'0',STR_PAD_LEFT);
		}    
		$output = $this->doTextHashLCL($arr_txthash, $hashHeader_, $hashFooter_);
        return $output;
    }
    
    function doTextHashLCL($aData_ = null,$hashHeader_ = "", $hashFooter_ = "", $delimeter_ = "\r\n" ) {
        if (is_null($aData_)) {
            return "";
        }
        $this->txtHash = $hashHeader_.$delimeter_;

        if (count($aData_)> 0 )
        foreach ($aData_ as $keyData => $valData) {
            $this->txtHash .= $valData['txt_hash'].$delimeter_;
        }
        $this->txtHash .= $hashFooter_.$delimeter_;
        return $this->txtHash;
    }
    
	/**
	 * @note: xlsHDMF_LOAN_Report
	 * @param unknown_type $gData
	 */
	function generateSSS_LOAN_Report($gData = array(), $arrData = array(), $compInfo = array()) {
		$m = $gData['year'].'-'.$gData['month'].'-'.'1';
		$filename = "SSS_LOAN_".date('FY',dDate::parseDateTime($m)).".xls"; // The file name you want any resulting file to be called.
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		$objClsMngeDecimal = new Application();
		$finalDecFormat = $objClsMngeDecimal->setFinalDecimalPlaces(0);
		
		$objReader = PHPExcel_IOFactory::createReader('Excel5');
		$objPHPExcel = $objReader->load("templates/SSS_LOAN_Template.xls");
		//header excel
		$objPHPExcel->getActiveSheet()->setCellValue('B8', $compInfo['comp_name']);
		$objPHPExcel->getActiveSheet()->setCellValue('B9', $compInfo['comp_sss']);
		$objPHPExcel->getActiveSheet()->setCellValue('A6', "As of ".date('F Y',dDate::parseDateTime($m)));
		//Body List
		$baseRow = 14;
		if (count($arrData) > 0) {
			foreach ($arrData as $key => $val) {
				if ($val['collection']['payment'] > 0) {
					$row = $baseRow++; //from $row = $baseRow + $key;
					$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $val['emp_info']['pi_sss']);
					$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $val['emp_info']['pi_lname'].", ".$val['emp_info']['pi_fname']." ".$val['emp_info']['pi_mname'].".");
					$objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $val['emp_info']['loantype_code']);
					$objPHPExcel->getActiveSheet()->setCellValue('D'.$row, $val['emp_info']['loan_dategrant']);
					$objPHPExcel->getActiveSheet()->setCellValue('E'.$row, $val['emp_info']['loan_principal']);
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$row, $val['collection']['payment']);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$row, '=SUM(F'.$row.':G'.$row.')');
					$objPHPExcel->getActiveSheet()->getStyle('E'.$row.':H'.$row)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
					$objPHPExcel->getActiveSheet()->setCellValue('I'.$row, 'H');
				}
			}
		}
		//Footer excel
		$objPHPExcel->getActiveSheet()->setCellValue('F51', $gData['co_maker1_name']);
		// Rename sheet
		$objPHPExcel->getActiveSheet()->setTitle($filename);
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);
		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename='.$filename);
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
	}
    
	function getSSSLoanTransmittal($arrData = array(), $pData = array()){
		$amtPd = 0;
		foreach($arrData as $key => $value){
			$amtPd += $value['collection']['payment'];
		}
        $orientation='P';
        $unit='mm';
        $format='A4';
        $unicode=true;
        $encoding="UTF-8";

        $oPDF = new clsPDF($orientation, $unit, $format, $unicode, $encoding);
        $objClsMngeDecimal = new Application();
        
    	IF($this->getSettings($pData['comp'],9)){
        	$branch_details = $this->getLocationInfo($pData['branchinfo_id']);
        	$compname = $branch_details['branchinfo_name'];
        	$compadds = $branch_details['branchinfo_add'];
        	$compsssno = $branch_details['branchinfo_sss'];
        	$comptinno = $branch_details['branchinfo_tin'];
        	$comptelno = $branch_details['branchinfo_tel1'];
        }ELSE{
        	$branch_details = $this->dbfetchCompDetails($pData['comp']);
        	$compname = $branch_details['comp_name'];
        	$compadds = $branch_details['comp_add'];
        	$compzip = $branch_details['comp_zipcode'];
        	$compsssno = $branch_details['comp_sss'];
        	$comptinno = $branch_details['comp_tin'];
        	$comptelno = $branch_details['comp_tel'];
        }
        $address = array_filter(explode(",", $compadds));
        $compadds = implode(" ",$address);
//        $oPDF->SetHeaderData('', PDF_HEADER_LOGO_WIDTH, $branch_details['comp_name'], $branch_details['branch_address'].'-'.$branch_details['branch_trunklines']);
        // set header and footer fonts
        $oPDF->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $oPDF->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        //set margins
        $oPDF->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $oPDF->SetHeaderMargin(PDF_MARGIN_HEADER);
        $oPDF->SetAutoPageBreak(false);
        // use a freeserif font as a default font
        $oPDF->SetFont('helvetica','',8);

        // suppress print header and footer
        $oPDF->setPrintHeader(true);
        $oPDF->setPrintFooter(false);

        // set initila coordinates
        $coordX = 10;
        $coordY = 20;

        $oPDF->AliasNbPages();

        // set initial pdf page
        $oPDF->AddPage();
        $oPDF->SetFillColor(255,255,255);
        
        //used to line style...
		$style5 = array('dash' => 2);
		$style6 = array('dash' => 0);

       // $oPDF->SetFont('helvetica', '', '10');
        $oPDF->SetXY($coordX, $coordY);
        $oPDF->MultiCell($oPDF->getPageWidth()-20,3,strtoupper($compname),0,'C',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX, $coordY+5);
        $oPDF->MultiCell($oPDF->getPageWidth()-20,3,strtoupper($compadds),0,'C',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX, $coordY+10);
        $oPDF->MultiCell($oPDF->getPageWidth()-20,3,"TEL. NO. ".$comptelno,0,'C',1, 0, 0, 0, TRUE, 0, TRUE);
		$oPDF->SetXY($coordX+20, $coordY+20);
		$oPDF->MultiCell(100,3,"TRANSMITTAL LIST",0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
		$oPDF->SetXY($coordX+20, $coordY+30);
		$oPDF->MultiCell(100,3,"ER ID No.",0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
		$oPDF->SetXY($coordX+100, $coordY+30);
		$oPDF->MultiCell(100,3, ": ".$compsssno,0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
		$oPDF->SetXY($coordX+20, $coordY+35);
		$oPDF->MultiCell(100,3,"Billing Month",0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
		$oPDF->SetXY($coordX+100, $coordY+35);
		$timestamp = mktime(0, 0, 0, sprintf("%02s", $pData['month']), 10);
		$oPDF->MultiCell(100,3,": ".date("F", $timestamp),0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
		$oPDF->SetXY($coordX+20, $coordY+40);
		$oPDF->MultiCell(100,3,"Total No. of Records",0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
		$oPDF->SetXY($coordX+100, $coordY+40);
		$oPDF->MultiCell(100,3,": ".count($arrData),0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
		$oPDF->SetXY($coordX+20, $coordY+45);
		$oPDF->MultiCell(100,3,"Total Amount Paid",0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
		$oPDF->SetXY($coordX+100, $coordY+45);
		$oPDF->MultiCell(100,3,":      ".$objClsMngeDecimal->setFinalDecimalPlaces($amtPd),0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
		$oPDF->SetXY($coordX+20, $coordY+55);
		$oPDF->MultiCell(100,3,"REMITTANCE",0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
		$oPDF->SetXY($coordX+20, $coordY+65);
		$oPDF->MultiCell(100,3,"Amount Paid",0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
		$oPDF->SetXY($coordX+100, $coordY+65);
		$oPDF->MultiCell(100,3,":      ".$objClsMngeDecimal->setFinalDecimalPlaces($amtPd),0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
		$oPDF->SetXY($coordX+20, $coordY+70);
		$oPDF->MultiCell(100,3,"Trans/SBR No.",0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
		$oPDF->SetXY($coordX+100, $coordY+70);
		$oPDF->MultiCell(100,3,": ".$pData['sbr_no'],0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
		$oPDF->SetXY($coordX+20, $coordY+75);
		$oPDF->MultiCell(100,3,"Date Paid",0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
		$oPDF->SetXY($coordX+100, $coordY+75);
		$oPDF->MultiCell(100,3,": ".date('n/j/Y',dDate::parseDateTime($pData['date_paid'])),0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
		$oPDF->SetXY($coordX+68, $coordY+85);
		$oPDF->MultiCell(100,3,"Certified Correct:",0,'C',1, 0, 0, 0, TRUE, 0, TRUE);
		$oPDF->SetXY($coordX+68, $coordY+95);
		$oPDF->MultiCell(100,3,$pData['co_maker4_name'],0,'C',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->Line($coordX+90, $coordY+100, 155, $coordY+100, $style = $style6);
		$oPDF->SetXY($coordX+68, $coordY+100);
		$oPDF->MultiCell(100,3,$pData['co_maker4_job'],0,'C',1, 0, 0, 0, TRUE, 0, TRUE);
		$oPDF->Line($coordX+20, $coordY+110, $coordX+145, $coordY+110, $style = $style6);
		$oPDF->SetXY($coordX+20, $coordY+115);
		$oPDF->MultiCell(100,3,"To be Filled Up By SSS Personnel",0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
		$oPDF->SetXY($coordX+30, $coordY+125);
		$oPDF->MultiCell(100,3,"Received by     :     _________________________________",0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
		$oPDF->SetXY($coordX+30, $coordY+130);
		$oPDF->MultiCell(100,3,"Printed Name",0,'C',1, 0, 0, 0, TRUE, 0, TRUE);
		$oPDF->SetXY($coordX+49, $coordY+140);
		$oPDF->MultiCell(100,3,":     _________________________________",0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+30, $coordY+145);
		$oPDF->MultiCell(100,3,"Signature",0,'C',1, 0, 0, 0, TRUE, 0, TRUE);
		$oPDF->SetXY($coordX+49, $coordY+155);
		$oPDF->MultiCell(100,3,":     _________________________________",0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
		$oPDF->SetXY($coordX+30, $coordY+160);
		$oPDF->MultiCell(100,3,"Date",0,'C',1, 0, 0, 0, TRUE, 0, TRUE);
		$oPDF->SetXY($coordX+20, $coordY+165);
		$oPDF->MultiCell(100,3,"Remarks",0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
		$oPDF->SetXY($coordX+20, $coordY+170);
		$oPDF->MultiCell(100,3,"______________________________________________________________",0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
		$oPDF->SetXY($coordX+20, $coordY+175);
		$oPDF->MultiCell(100,3,"______________________________________________________________",0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
		$oPDF->SetXY($coordX+20, $coordY+180);
		$oPDF->MultiCell(100,3,"______________________________________________________________",0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
		$oPDF->SetXY($coordX+20, $coordY+190);
		$oPDF->MultiCell(100,3,"Diskette returned to ER:",0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
		$oPDF->SetXY($coordX+30, $coordY+200);
		$oPDF->MultiCell(100,3,"Received by     :     _________________________________",0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
		$oPDF->SetXY($coordX+30, $coordY+205);
		$oPDF->MultiCell(100,3,"Printed Name",0,'C',1, 0, 0, 0, TRUE, 0, TRUE);
		$oPDF->SetXY($coordX+49, $coordY+215);
		$oPDF->MultiCell(100,3,":     _________________________________",0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+30, $coordY+220);
		$oPDF->MultiCell(100,3,"Signature",0,'C',1, 0, 0, 0, TRUE, 0, TRUE);
		$oPDF->SetXY($coordX+49, $coordY+230);
		$oPDF->MultiCell(100,3,":     _________________________________",0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
		$oPDF->SetXY($coordX+30, $coordY+235);
		$oPDF->MultiCell(100,3,"Date",0,'C',1, 0, 0, 0, TRUE, 0, TRUE);
		$oPDF->SetXY($coordX+20, $coordY+245);
		$oPDF->MultiCell(100,3,"Other Documents: (Specify)",0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
		//printa($pData); exit;
        $output = $oPDF->Output("SSS Transmittal Certification_".date('Y-m-d').".pdf");

        if(!empty($output)){
            return $output;
        }
        return false;
    }
}
?>