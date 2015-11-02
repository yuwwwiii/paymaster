<?php
/**
 * Initial Declaration
 */
require_once(SYSCONFIG_CLASS_PATH."util/pdf.class.php");
require_once(SYSCONFIG_CLASS_PATH."util/dompdf/dompdf_config.inc.php");
require_once(SYSCONFIG_CLASS_PATH."util/PHPExcel.php");
require_once(SYSCONFIG_CLASS_PATH."util/PHPExcel/IOFactory.php");
$month = array(
	 "01" => "January"
	,"02" => "Febuary"
	,"03" => "March"
	,"04" => "April"
	,"05" => "May"
	,"06" => "June"
	,"07" => "July"
	,"08" => "August"
	,"09" => "September"
	,"10" => "October"
	,"11" => "November"
	,"12" => "December"
);
$year = array(
	date('Y') => date('Y'),
	date('Y')-1 => date('Y')-1,
	date('Y')-2 => date('Y')-2,
	date('Y')-3 => date('Y')-3,
	date('Y')-4 => date('Y')-4,
	date('Y')-5 => date('Y')-5,
	date('Y')-6 => date('Y')-6,
	date('Y')-7 => date('Y')-7,
);
$type = array(
    'month' => "Monthly"
	/*,'quarterly' => "Quarterly"
	,'ytd' => "YTD",
	,'year' => "Yearly"*/
);

/**
 * Class Module
 * @author  JIM
 */
class clsSSS{
	var $conn;
	var $fieldMap;
	var $Data;
	var $txtHash;

	/**
	 * Class Constructor
	 * @param object $dbconn_
	 * @return clsSSS object
	 */
	function clsSSS($dbconn_ = null){
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

    function dbFetchContribution($gData = array(),$istype_ = null){
//    	printa($gData); exit;
        IF($gData['type'] == 'month'){
            $y = "".$gData['year']."-".$gData['month']."-01";
            $enddate = dDate::getEndMonthEpoch(dDate::parseDateTime($y));
            $startdate = dDate::getBeginMonthEpoch(dDate::parseDateTime($y));
        }elseif($gData['type'] == 'year'){
            $y = $gData['year']."-01-01";
            $enddate = dDate::getEndYearEpoch(dDate::parseDateTime($y));
            $startdate = dDate::getBeginYearEpoch(dDate::parseDateTime($y));
        }else{
            $y = "".$gData['year']."-".$gData['month']."-01";
            $enddate = dDate::getEndMonthEpoch(dDate::parseDateTime($y));
            $startdate = dDate::parseDateTime($gData['year']."-01-01");
        }
        $arrData = array();
		$trans_date = $gData['year'] . '-' . $gData['month'];
		
		$qry = array();
    	$listpgroup = $_SESSION[admin_session_obj][user_paygroup_list2];
		IF(count($listpgroup)>0){
			$qry[] = "a.pps_id in (".$listpgroup.")";//pay group that can access
		}
		IF($this->getSettings($gData['comp'],12) && ($gData['branchinfo_id'] != 0 || $gData['branchinfo_id'] != "N/A")){//get Location parameter.
			$qry[] = "b.branchinfo_id = '".$gData['branchinfo_id']."'";
		}
		IF($istype_=='1'){//PHIC
			$qry[] = "a.emp_id NOT IN (select emp_id from period_benloanduc_sched where empdd_id = '2' and bldsched_period=0)";
		}elseif($istype_=='2'){//HDMF
			$qry[] = "a.emp_id NOT IN (select emp_id from period_benloanduc_sched where empdd_id = '3' and bldsched_period=0)";
		}else{//SSS
			$qry[] = "a.emp_id NOT IN (select emp_id from period_benloanduc_sched where empdd_id = '1' and bldsched_period=0)";
		}
		$qry[] = "b.comp_id = '".$gData['comp']."'";
		$qry[] = "ppp.payperiod_period='".$gData['month']."'";
		$qry[] = "ppp.payperiod_period_year='".$gData['year']."'";
		$criteria = count($qry)>0 ? " WHERE ".implode(' AND ',$qry) : '';
		$sql = "SELECT distinct ppr.emp_id,concat(d.pi_fname,' ',d.pi_mname,' ',d.pi_lname) as name,f.ud_name,b.emp_id, d.*, CONCAT(upper(RPAD(d.pi_mname,1,'')),'.') as pi_mname,d.pi_mname as pi_mnamefull, d.pi_mname as pi_mname_, CONCAT(upper(h.comp_name)) as comp_name, h.*, DATE_FORMAT(b.emp_hiredate,'%m%d%Y') as emp_hiredate, b.emp_idnum, b.emp_hiredate, b.emp_resigndate
				FROM payroll_pps_user a 
				JOIN emp_masterfile b on (b.emp_id =a.emp_id) 
				JOIN emp_personal_info d on (d.pi_id = b.pi_id)  
				JOIN app_userdept f on (f.ud_id = b.ud_id) 
				JOIN company_info h on (h.comp_id = b.comp_id)
				JOIN payroll_paystub_report ppr on (ppr.emp_id=b.emp_id)
				JOIN payroll_pay_stub pps on (pps.paystub_id=ppr.paystub_id)
				JOIN payroll_pay_period ppp on (ppp.payperiod_id=pps.payperiod_id)
				$criteria
				ORDER BY d.pi_lname ASC";
		$rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF){
			$rsResult->fields['pi_phic'] = explode('-',$rsResult->fields['pi_phic']);
			$arrData[] = $rsResult->fields;
			$rsResult->MoveNext();
		}
		for ($x = 0; $x < count($arrData); $x++){
				$arrDataDeduction[$x] = array(
						"emp_info" => $arrData[$x],
						"date" => $startdate,
						"dateprocess" => date('mdY'),
						"datemyear" => date('mY'),
						"sss" => $this->getDeduction($arrData[$x]['emp_id'],$gData['month'],$gData['year'],$istype_,$arrData[$x]['emp_hiredate'],$arrData[$x]['emp_resigndate'])
						);
		}
		return $arrDataDeduction;
	}

	function getDeduction($emp_id_ = null, $month_= null, $year_= null, $isType_ = null, $dateHired = null, $dateResigned = null){
		$arrData = array();
		$qry = array();
		IF(is_null($emp_id_)){ return $arrData; }
		IF($isType_=='1'){
			$qry[] = "b.psa_id =14";//PHIC
		}ELSEIF($isType_=='2'){
			$qry[] = "b.psa_id =15";//HDMF
		}ELSE{
			$qry[] = "b.psa_id =7";//SSS
		}
		$qry[] = "c.ppr_status =1";
        $qry[] = "c.ppr_isdeleted =0";
		$qry[] = "a.emp_id = $emp_id_";
		$qry[] = "f.payperiod_period='".$month_."'";
		$qry[] = "f.payperiod_period_year='".$year_."'";
		//$qry[] = "date_format(a.paystub_trans_date,'%Y-%m-%d') >='$start_date_'";
		//$qry[] = "date_format(a.paystub_trans_date,'%Y-%m-%d') <='$end_date_'";
//		//$qry[] = "";
		$criteria = count($qry)>0 ? " WHERE ".implode(' AND ',$qry) : '';
		$sql = "SELECT a.paystub_id ,b.psa_id, sum(b.ppe_amount) as ppe_amount, sum(b.ppe_amount_employer) as ppe_amount_employer,sum(b.ppe_units) as ppe_units,
				sum(b.ppe_amount) + sum(b.ppe_amount_employer) as total,d.psa_name,b.ppe_rate
				FROM payroll_pay_stub a
				JOIN payroll_paystub_entry b on (a.paystub_id = b.paystub_id)
				JOIN payroll_paystub_report c on (a.paystub_id = c.paystub_id)
				JOIN payroll_ps_account d on (d.psa_id = b.psa_id)
				JOIN payroll_pay_stub e on (e.paystub_id=a.paystub_id)
				JOIN payroll_pay_period f on (f.payperiod_id=e.payperiod_id)
				$criteria
				group by a.emp_id";
		$rsResult = $this->conn->Execute($sql);
		if(!$rsResult->EOF){
			if($isType_=='1'){//PHIC
				$rsResult->fields['msb'] = $this->getMSB($rsResult->fields['ppe_amount']);
				$rsResult->fields['remarks'] = $this->validateNewlyHired($dateHired,$dateResigned,$month_,$year_,1);
			}elseif($isType_=='2'){//HDMF
				$rsResult->fields['remarks'] = $this->validateNewlyHired($dateHired,$dateResigned,$month_,$year_,2);
			}
			return $rsResult->fields;
		}
	}
	
	Function getMSB($ppe_amount_ = 0){
		$sqlsc = "SELECT b.scr_ec FROM statutory_contribution a 
							JOIN sc_records b on (a.sc_id = b.sc_id) 
							WHERE a.sc_id = 1 and a.dec_id = 2 and b.scr_ee = '".$ppe_amount_."'
							ORDER BY b.min_salary desc 
							LIMIT 1";
		$rsResultSC = $this->conn->Execute($sqlsc);
		if(!$rsResultSC->EOF){
			return $rsResultSC->fields['scr_ec'];
		}
	}
	
	/**
	 * @note: validate date hire
	 * @param unknown_type $dateHired
	 * @param unknown_type $dateResigned
	 * @param unknown_type $dateCutOffStart
	 * @param unknown_type $dateCutOffEnd
	 */
	function validateNewlyHired($dateHired, $dateResigned, $month, $year, $getType){
		$datehired_ = date('m/d/Y',dDate::parseDateTime($dateHired));
		$month_ = date('m',dDate::parseDateTime($dateHired));
		$year_ = date('Y',dDate::parseDateTime($dateHired));
		if($dateResigned != NULL and $dateResigned !='' and $dateResigned != '0000-00-00'){
			if($getType=='1'){
				return "S (".date('m/d/Y',dDate::parseDateTime($dateResigned)).")";
			}else{
				return "RS: ".date('m/d/Y',dDate::parseDateTime($dateResigned));
			}
    	}elseif($month_ == $month AND $year_ == $year) {
    		if($getType=='1'){
    			return "NH (".$datehired_.")";
    		}else{
    			return "N: ".$datehired_;
    		}
		}else {
    		return " ";
    	}
    }
    
    /**
     * @note: to get Quarterly Period
     * @param $month
     */
    FUNCTION getQuarterPeriod($month){
    	IF($month >= '01' AND $month <= '03'){
            $qrtN = '1';
      	}ELSEIF($month >= '04' AND $month <= '06'){
      		$qrtN = '2';
      	}ELSEIF($month >= '07' AND $month <= '09'){
      		$qrtN = '3';
      	}ELSE{
      		$qrtN = '4';
      	}
      	return $qrtN;
    }
	
	/**
	 * @note: xlsSSS_Premium_Report
	 * @param unknown_type $gData
	 */
    function generateSSS_Premium_Report($gData = array(),$arrData = array()){
    	$m = $gData['year'].'-'.$gData['month'].'-'.'1';
        $filename = "SSS_Premium_".date('FY',dDate::parseDateTime($m)).".xls"; // The file name you want any resulting file to be called.
    	// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		$objClsMngeDecimal = new Application();
		$finalDecFormat = $objClsMngeDecimal->setFinalDecimalPlaces(0);
		
		$objReader = PHPExcel_IOFactory::createReader('Excel5');
		$objPHPExcel = $objReader->load("templates/SSS_Premium.xls");
		//header excel
    	IF($gData['branchinfo_id']!=0){
        	$branch_details = $this->getLocationInfo($gData['branchinfo_id']);
        	$compname = $branch_details['branchinfo_name'];
        	$compadds = $branch_details['branchinfo_add'];
        	$compsssno = $branch_details['branchinfo_sss'];
        	$comptinno = $branch_details['branchinfo_tin'];
        	$comptelno = $branch_details['branchinfo_tel1'];
        }ELSE{
        	$compname = $arrData[0]['emp_info']['comp_name'];
        	$compadds = $arrData[0]['emp_info']['comp_add'];
        	$comptelno = $arrData[0]['emp_info']['comp_tel'];
        }
		$objPHPExcel->getActiveSheet()->setCellValue('A2', $compname);
		$objPHPExcel->getActiveSheet()->setCellValue('A4', $compadds);
		$objPHPExcel->getActiveSheet()->setCellValue('A5', $comptelno);
		$objPHPExcel->getActiveSheet()->setCellValue('A9', "As of ".date('F Y',dDate::parseDateTime($m)));
		
		//Body List
		$sheet = $objPHPExcel->getActiveSheet();
		$styleArray = array('font' => array('bold' => true));
//		$styleBorderArray = array('bottom' => array('style' => PHPExcel_Style_Border::BORDER_DOUBLE,'color' => array('rgb' => '808080')),'top' => array('style' => PHPExcel_Style_Border::BORDER_DOUBLE,'color' => array('rgb' => '808080')));
		$styleBorderArray = array('bottom' => array('style' => PHPExcel_Style_Border::BORDER_DOUBLE),'top' => array('style' => PHPExcel_Style_Border::BORDER_DOUBLE));
		
		$numberOfEmployee = count($arrData);
		$baseRow = 16;
		$baseRow_F = 16;
		$baseRow_F2 = 16;
		$baseRow_H = 16;
		$baseRow_H2 = 16;
		$lastvalue = 0;
		$fifty = 50; // wag tatanggalin ito dahil may weird effect, hindi gagana yung $baseRowPlus50 promise!
		$baseRowPlus50 = $baseRow + $fifty;
		
		if ($numberOfEmployee > 0) {
            foreach ($arrData as $key => $val) {
				$row = $baseRow + $key;
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $key+1);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $val['emp_info']['pi_lname'].", ".$val['emp_info']['pi_fname']." ".$val['emp_info']['pi_mname']);
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $val['emp_info']['pi_sss']);
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$row, $val['sss']['ppe_amount']);
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$row, $val['sss']['ppe_amount_employer']);
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$row, '=D' . $baseRow_F++ . '+E' . $baseRow_F2++);
				$objPHPExcel->getActiveSheet()->setCellValue('G'.$row, $val['sss']['ppe_units']);
				$objPHPExcel->getActiveSheet()->setCellValue('H'.$row, '=G' . $baseRow_H++ . '+F' . $baseRow_H2++);
				
				$objPHPExcel->getActiveSheet()->getStyle('D'.$row.':H'.$row)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
				$sheet->getStyle('F'.$row)->applyFromArray($styleArray);
				$sheet->getStyle('G'.$row)->applyFromArray($styleArray);
				$sheet->getStyle('H'.$row)->applyFromArray($styleArray);
				$lastvalue++;
			}
			
			$basePlusLast = $baseRow + $lastvalue;
			
			if ($numberOfEmployee < $fifty) {
				$style=$baseRowPlus50;
				$PreCheApp = $baseRowPlus50 + 4;
				$notedBy = $baseRowPlus50 + 7;
				$runDateandTime = $baseRowPlus50 + 9;
			} else {
				$style=$basePlusLast;
				$PreCheApp = $basePlusLast + 4;
				$notedBy = $basePlusLast + 7;
				$runDateandTime = $basePlusLast + 9;
			}
			
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$style, 'Grand Total:');
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$style, '=SUM(D' . $baseRow . ':D' . $row . ')');
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$style, '=SUM(E' . $baseRow . ':E' . $row . ')');
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$style, '=SUM(F' . $baseRow . ':F' . $row . ')');
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$style, '=SUM(G' . $baseRow . ':G' . $row . ')');
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$style, '=SUM(H' . $baseRow . ':H' . $row . ')');
			
			$sheet->getStyle('C'.$style)->applyFromArray($styleArray);
			$sheet->getStyle('D'.$style)->applyFromArray($styleArray);
			$sheet->getStyle('E'.$style)->applyFromArray($styleArray);
			$sheet->getStyle('F'.$style)->applyFromArray($styleArray);
			$sheet->getStyle('G'.$style)->applyFromArray($styleArray);
			$sheet->getStyle('H'.$style)->applyFromArray($styleArray);
			
			$sheet->getStyle('C'.$style)->getBorders()->applyFromArray($styleBorderArray);
			$sheet->getStyle('D'.$style)->getBorders()->applyFromArray($styleBorderArray);
			$sheet->getStyle('E'.$style)->getBorders()->applyFromArray($styleBorderArray);
			$sheet->getStyle('F'.$style)->getBorders()->applyFromArray($styleBorderArray);
			$sheet->getStyle('G'.$style)->getBorders()->applyFromArray($styleBorderArray);
			$sheet->getStyle('H'.$style)->getBorders()->applyFromArray($styleBorderArray);
			
			// Footer Excel
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$PreCheApp, 'Prepared by: ' . $gData['corectby']);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$PreCheApp, 'Checked by: ');
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$PreCheApp, 'Approved by: ');
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$notedBy, 'Noted by: ');
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$runDateandTime, 'Run Date: ' . date("n/j/o"));
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$runDateandTime, 'Run Time: ' . date("h:i A"));
			
			// PreCheApp means Prepared by, Checked by and Approved by.
			$sheet->getStyle('A'.$PreCheApp)->applyFromArray($styleArray);
			$sheet->getStyle('C'.$PreCheApp)->applyFromArray($styleArray);
			$sheet->getStyle('F'.$PreCheApp)->applyFromArray($styleArray);
			$sheet->getStyle('A'.$notedBy)->applyFromArray($styleArray);
		}
		
		// Rename Sheet
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
	
	/**
	 * @note: This function is used to creat pdf file for SSS R-5.
	 * @param unknown_type $arrData
	 * @param unknown_type $gData
	 */
    function getPDFResultSSSPayment($arrData = array(), $gData = array()){
        $orientation='L';
        $unit='mm';
        $format='A4';
        $unicode=true;
        $encoding="UTF-8";

        $oPDF = new clsPDF($orientation, $unit, $format, $unicode, $encoding);
        $objClsMngeDecimal = new Application();
        IF($this->getSettings($gData['comp'],12) && ($gData['branchinfo_id'] != 0 || $gData['branchinfo_id'] != "N/A")){
        	$branch_details = $this->getLocationInfo($gData['branchinfo_id']);
        	$compname = $branch_details['branchinfo_name'];
        	$compadds = $branch_details['branchinfo_add'];
        	$compsssno = $branch_details['branchinfo_sss'];
        	$comptinno = $branch_details['branchinfo_tin'];
        	$comptelno = $branch_details['branchinfo_tel1'];
        }ELSE{
        	$branch_details = $this->dbfetchCompDetails($gData['comp']);
        	$compname = $branch_details['comp_name'];
        	$compadds = $branch_details['comp_add'];
        	$compzip = $branch_details['comp_zipcode'];
        	$compsssno = $branch_details['comp_sss'];
        	$comptinno = $branch_details['comp_tin'];
        	$comptelno = $branch_details['comp_tel'];
        }
//        $oPDF->SetHeaderData('', PDF_HEADER_LOGO_WIDTH, $branch_details['comp_name'], $branch_details['branch_address'].'-'.$branch_details['branch_trunklines']);
        // set header and footer fonts
        $oPDF->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $oPDF->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        //set margins
        $oPDF->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $oPDF->SetHeaderMargin(PDF_MARGIN_HEADER);
        $oPDF->SetAutoPageBreak(false);
        // use a freeserif font as a default font
        $oPDF->SetFont('freeserif','',12);

        // suppress print header and footer
        $oPDF->setPrintHeader(true);
        $oPDF->setPrintFooter(false);

        // set initila coordinates
        $coordX = 10;
        $coordY = 10;

        $oPDF->AliasNbPages();

        // set initial pdf page
        $oPDF->AddPage();
        $oPDF->SetFillColor(255,255,255);
        
        //used to line style...
		$style5 = array('dash' => 2);
		$style6 = array('dash' => 0);

        $m = $gData['year'].'-'.$gData['month'].'-'.'1';
        if($gData['type'] == 'ytd'){
            $str = "YTD Report";
        }elseif($gData['type'] == 'month'){
            $str = "Monthly Report";
        }else{
            $str = "Yearly Report";
        }

        if($gData['type'] == 'month'){
            $str2 = date('F Y',dDate::parseDateTime($m));
            $str2month = date('F',dDate::parseDateTime($m));
        }elseif($gData['type'] == 'ytd'){
            $str2= "January 2011 to ".date('F Y',dDate::parseDateTime($m));
        }else{
            $str2= $gData['year'];
        }

        $oPDF->SetFont('helvetica', '', '9');
        $oPDF->SetXY($coordX, $coordY);
        $oPDF->MultiCell($oPDF->getPageWidth(),3,'SOCIAL SECURITY SYSTEM - CONTRIBUTION',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+220, $coordY);
        $oPDF->MultiCell($oPDF->getPageWidth(),3,'SSS-R5 SUPPORTING DOCUMENT',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $coordY+=$oPDF->getFontSize()+.5;
        $oPDF->SetXY($coordX, $coordY);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'APPLICABLE PERIOD',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+50, $coordY);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,$str2,0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $coordY+=$oPDF->getFontSize()+2;
        $oPDF->SetXY($coordX, $coordY);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'EMPLOYER ID NUMBER:',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $coordY+=$oPDF->getFontSize()+.5;
        $oPDF->SetXY($coordX, $coordY);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,$compsssno,0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+60, $coordY);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'EMPLOYER\'S REGISTERED NAME:',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+120, $coordY);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,$compname,0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $coordY+=$oPDF->getFontSize()+.5;
        $oPDF->SetXY($coordX, $coordY);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'ADDRESS:',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+25, $coordY);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,$compadds,0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+205, $coordY);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'POSTAL CODE:',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+235, $coordY);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,$compzip,0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $coordY+=$oPDF->getFontSize()+.5;
        $oPDF->SetXY($coordX+205, $coordY);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'TELEPHONE NO.:',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+235, $coordY);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,$comptelno,0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
//        printa($arrData);
//		$coordY+=$oPDF->getFontSize()+2;
        $coordY = 38;
        $oPDF->SetFont('helvetica', '', '8');

        $eree_cont = 0;
        $subtotal = 0;
        $total_ec = 0;
        $eree = 0;
        $ec = 0;
        $total = 0;

        if(count($arrData)>0){
            $ctr = 0;
            foreach ($arrData as $key => $val) {
            $eree = $val['sss']['ppe_amount_employer']+$val['sss']['ppe_amount'];
            $ec = $val['sss']['ppe_units'];
            $total = $val['sss']['ppe_amount_employer']+$val['sss']['ppe_amount']+$val['sss']['ppe_units'];
            $ctr++;
                // reset coordinate X value every loop
                $coordX = 10;

                if($coordY==38){
                	
					$oPDF->Line($coordX, $coordY, 283, $coordY, $style = $style6);
					$oPDF->SetFont('helvetica', 'B', '8');
                    //$oPDF->SetFillColor(222,212,212);
                    $oPDF->SetXY($coordX+140, $coordY);
                    $oPDF->MultiCell(45, 3, "SSS CONTRIBUTION",0,'C',1);
                    $oPDF->SetXY($coordX+205, $coordY);
                    $oPDF->MultiCell(47, 3, "EMPLOYEE COMPENSATION",0,'C',1);
                    $coordY+=$oPDF->getFontSize()+.5;
                    $oPDF->SetXY($coordX, $coordY);
                     $oPDF->MultiCell(27, 3, "SSS ID No.",0,'L');
                     $oPDF->SetXY($coordX+27, $coordY);
                     $oPDF->MultiCell(40, 3, "SURNAME",0,'L');
                     $oPDF->SetXY($coordX+67, $coordY);
                     $oPDF->MultiCell(40, 3, "GIVEN NAME",0,'L');
                     $oPDF->SetXY($coordX+102, $coordY);
                     $oPDF->MultiCell(10, 3, "M.I.",0,'L');
                     $oPDF->SetXY($coordX+140, $coordY);
                     $oPDF->MultiCell(20, 3, "EMPLOYEE",0,'R');
                     $oPDF->SetXY($coordX+165, $coordY);
                     $oPDF->MultiCell(20, 3, "EMPLOYER",0,'R');
                     $oPDF->SetXY($coordX+185, $coordY);
                     $oPDF->MultiCell(20, 3, 'TOTAL',0,'R');
                     $oPDF->SetXY($coordX+210, $coordY);
                     $oPDF->MultiCell(30, 3, 'CONTRIBUTION',0,'R');
                     $oPDF->SetXY($coordX+240, $coordY);
                     $oPDF->MultiCell(30, 3, 'TOTAL',0,'R');
                     $coordY+=$oPDF->getFontSize()+3;
                     $oPDF->Line($coordX, $coordY, 283, $coordY, $style = $style6);
                     $oPDF->Line($coordX, $coordY+155, 283, $coordY+155, $style = $style6);
                     $oPDF->SetXY($coordX, $coordY+155);
                     $oPDF->MultiCell(30, 3,date('m-d-Y h:i A'),0,'L');
                     $oPDF->SetXY($coordX+240, $coordY+155);
                     $oPDF->MultiCell(30,3,'Page '.$oPDF->PageNo().'/{nb}',0,'R'); 
                }
				 $oPDF->SetFont('helvetica', '', '8');
                 $oPDF->SetXY($coordX, $coordY);
                 $oPDF->MultiCell(27, 3, $val['emp_info']['pi_sss'],0,'L');
                 $oPDF->SetXY($coordX+27, $coordY);
                 $oPDF->MultiCell(35, 3, $val['emp_info']['pi_lname'],0,'L');
                 $oPDF->SetXY($coordX+67, $coordY);
                 $oPDF->MultiCell(35, 3, $val['emp_info']['pi_fname'],0,'L');
                 $oPDF->SetXY($coordX+102, $coordY);
                 $oPDF->MultiCell(33, 3, $val['emp_info']['pi_mname'],0,'L');
                 $oPDF->SetXY($coordX+140, $coordY);
                 $oPDF->MultiCell(20, 3, $objClsMngeDecimal->setFinalDecimalPlaces($val['sss']['ppe_amount']),0,'R');
                 $oPDF->SetXY($coordX+165, $coordY);
                 $oPDF->MultiCell(20, 3, $objClsMngeDecimal->setFinalDecimalPlaces($val['sss']['ppe_amount_employer']),0,'R');
                 $oPDF->SetXY($coordX+185, $coordY);
                 $oPDF->MultiCell(20, 3, $objClsMngeDecimal->setFinalDecimalPlaces($eree),0,'R');
                 $oPDF->SetXY($coordX+210, $coordY);
                 $oPDF->MultiCell(30, 3, $objClsMngeDecimal->setFinalDecimalPlaces($ec),0,'R');
                 $oPDF->SetXY($coordX+240, $coordY);
                 $oPDF->MultiCell(30, 3, $objClsMngeDecimal->setFinalDecimalPlaces($total),0,'R');

                // check if the coordinate Y are exceeding the limit of 250
                // if yes create / add new pdf page
                if($coordY  > 195){
                    $coordX = 10;
                    $coordY = 10;
                    $oPDF->AddPage();

                    $oPDF->SetFont('helvetica', '', '9');
			        $oPDF->SetXY($coordX, $coordY);
			        $oPDF->MultiCell($oPDF->getPageWidth(),3,'SOCIAL SECURITY SYSTEM - CONTRIBUTION',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
			        $oPDF->SetXY($coordX+220, $coordY);
			        $oPDF->MultiCell($oPDF->getPageWidth(),3,'SSS-R5 SUPPORTING DOCUMENT',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
			        $coordY+=$oPDF->getFontSize()+.5;
			        $oPDF->SetXY($coordX, $coordY);
			        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'APPLICABLE PERIOD',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
			        $oPDF->SetXY($coordX+50, $coordY);
			        $oPDF->MultiCell($oPDF->getPageWidth(), 3,$str2,0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
			        $coordY+=$oPDF->getFontSize()+2;
			        $oPDF->SetXY($coordX, $coordY);
			        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'EMPLOYER ID NUMBER:',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
			        $coordY+=$oPDF->getFontSize()+.5;
			        $oPDF->SetXY($coordX, $coordY);
			        $oPDF->MultiCell($oPDF->getPageWidth(), 3,$branch_details['comp_sss'],0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
			        $oPDF->SetXY($coordX+60, $coordY);
			        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'EMPLOYER\'S REGISTERED NAME:',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
			        $oPDF->SetXY($coordX+120, $coordY);
			        $oPDF->MultiCell($oPDF->getPageWidth(), 3,$branch_details['comp_name'],0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
			        $coordY+=$oPDF->getFontSize()+.5;
			        $oPDF->SetXY($coordX, $coordY);
			        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'ADDRESS:',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
			        $oPDF->SetXY($coordX+25, $coordY);
			        $oPDF->MultiCell($oPDF->getPageWidth(), 3,$branch_details['comp_add'],0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
			        $oPDF->SetXY($coordX+205, $coordY);
			        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'POSTAL CODE:',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
			        $oPDF->SetXY($coordX+235, $coordY);
			        $oPDF->MultiCell($oPDF->getPageWidth(), 3,$branch_details['comp_zipcode'],0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
			        $coordY+=$oPDF->getFontSize()+.5;
			        $oPDF->SetXY($coordX+205, $coordY);
			        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'TELEPHONE NO.:',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
			        $oPDF->SetXY($coordX+235, $coordY);
			        $oPDF->MultiCell($oPDF->getPageWidth(), 3,$branch_details['comp_tel'],0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
                    $coordY = 38;
                }else{
                    // increment coordinate Y
                    $coordY+=$oPDF->getFontSize()+2;
                }

                $eree_cont += $eree;
                $empcon += $val['sss']['ppe_amount'];
                $emrcon += $val['sss']['ppe_amount_employer'];
                $subtotal += $total;
                $total_ec += $ec;
            }

             $oPDF->SetFont('helvetica','B','8');
             $coordY+=$oPDF->getFontSize()+2;
             $oPDF->SetXY($coordX, $coordY);
             $oPDF->MultiCell(100, 3, "*****NOTHING FOLLOWS*****",0,'L');
             $coordY +=$oPDF->getFontSize()+4;
             $oPDF->Line($coordX, $coordY, 283, $coordY, $style = $style6);
             $oPDF->SetXY($coordX, $coordY);
             $oPDF->MultiCell(40, 3, "TOTAL REMITTANCE",0,'L');
             $oPDF->SetXY($coordX+140, $coordY);
             $oPDF->MultiCell(20, 3, $objClsMngeDecimal->setFinalDecimalPlaces($empcon),0,'R');
             $oPDF->SetXY($coordX+165, $coordY);
             $oPDF->MultiCell(20, 3, $objClsMngeDecimal->setFinalDecimalPlaces($emrcon),0,'R');
             $oPDF->SetXY($coordX+185, $coordY);
             $oPDF->MultiCell(20, 3, $objClsMngeDecimal->setFinalDecimalPlaces($eree_cont),0,'R');
             $oPDF->SetXY($coordX+210, $coordY);
             $oPDF->MultiCell(30, 3, $objClsMngeDecimal->setFinalDecimalPlaces($total_ec),0,'R');
             $oPDF->SetXY($coordX+240, $coordY);
             $oPDF->MultiCell(30, 3, $objClsMngeDecimal->setFinalDecimalPlaces($subtotal),0,'R');
             $coordY+=$oPDF->getFontSize()+2;
             $oPDF->Line($coordX, $coordY, 283, $coordY, $style = $style6);
             
             $oPDF->SetFont('helvetica', '', '9');
             $coordY+=$oPDF->getFontSize()+4;
             $oPDF->SetXY($coordX, $coordY);
             $oPDF->MultiCell(50, 3, "CERTIFIED CORRECT:",0,'L');
             $coordY+=$oPDF->getFontSize()+12;
             
             $oPDF->SetFont('helvetica', 'B,I', '9');
             $oPDF->SetXY($coordX, $coordY-6);
             $oPDF->MultiCell(50, 3,$gData['corectby'],0,'C');
             $oPDF->Line($coordX, $coordY, 65, $coordY, $style = $style6);
             $oPDF->SetFont('helvetica', '', '9');
             $oPDF->SetXY($coordX, $coordY);
             $oPDF->MultiCell(100, 3, "SIGNATURE OVER PRINTED NAME",0,'L');
//             $preparedby = $this->getEmpDetailsforReport($_GET['emp_id1']);
        }
        // get the pdf output
        $output = $oPDF->Output("SSS R-5_".date('Y-m-d').".pdf");

        if(!empty($output)){
            return $output;
        }
        return false;
    }
    
	/**
	 * @note: This function is used to creat pdf file for SSS R-5.
	 * @param unknown_type $arrData
	 * @param unknown_type $gData
	 */
    function getPDFSSSTranmittalCert($arrData = array(), $gData = array()){
        $orientation='P';
        $unit='mm';
        $format='A4';
        $unicode=true;
        $encoding="UTF-8";

        $oPDF = new clsPDF($orientation, $unit, $format, $unicode, $encoding);
        $objClsMngeDecimal = new Application();
        
        IF($this->getSettings($gData['comp'],12) && ($gData['branchinfo_id'] != 0 || $gData['branchinfo_id'] != "N/A")){
        	$branch_details = $this->getLocationInfo($gData['branchinfo_id']);
        	$compname = $branch_details['branchinfo_name'];
        	$compadds = $branch_details['branchinfo_add'];
        	$compsssno = $branch_details['branchinfo_sss'];
        	$comptinno = $branch_details['branchinfo_tin'];
        	$comptelno = $branch_details['branchinfo_tel1'];
        }ELSE{
        	$branch_details = $this->dbfetchCompDetails($gData['comp']);
        	$compname = $branch_details['comp_name'];
        	$compadds = $branch_details['comp_add'];
        	$compzip = $branch_details['comp_zipcode'];
        	$compsssno = $branch_details['comp_sss'];
        	$comptinno = $branch_details['comp_tin'];
        	$comptelno = $branch_details['comp_tel'];
        }
//        $oPDF->SetHeaderData('', PDF_HEADER_LOGO_WIDTH, $branch_details['comp_name'], $branch_details['branch_address'].'-'.$branch_details['branch_trunklines']);
        // set header and footer fonts
        $oPDF->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $oPDF->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        //set margins
        $oPDF->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $oPDF->SetHeaderMargin(PDF_MARGIN_HEADER);
        $oPDF->SetAutoPageBreak(false);
        // use a freeserif font as a default font
        $oPDF->SetFont('freeserif','',12);

        // suppress print header and footer
        $oPDF->setPrintHeader(true);
        $oPDF->setPrintFooter(false);

        // set initila coordinates
        $coordX = 10;
        $coordY = 13;

        $oPDF->AliasNbPages();

        // set initial pdf page
        $oPDF->AddPage();
        $oPDF->SetFillColor(255,255,255);
        
        //used to line style...
		$style5 = array('dash' => 2);
		$style6 = array('dash' => 0);

        $m = $gData['year'].'-'.$gData['month'].'-'.'1';
        if($gData['type'] == 'ytd'){
            $str = "YTD Report";
        }elseif($gData['type'] == 'month'){
            $str = "Monthly Report";
        }else{
            $str = "Yearly Report";
        }

        if($gData['type'] == 'month'){
            $str2= date('mY',dDate::parseDateTime($m));
            $str2_= date('m-Y',dDate::parseDateTime($m));
        }elseif($gData['type'] == 'ytd'){
            $str2= "January 2011 to ".date('F Y',dDate::parseDateTime($m));
        }else{
            $str2= $gData['year'];
        }

        $oPDF->SetFont('helvetica', 'B', '12');
        $oPDF->SetXY($coordX, $coordY);
        $oPDF->MultiCell($oPDF->getPageWidth(),3,'SSS Transmittal Certification',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $coordY+=$oPDF->getFontSize()+2;
        $oPDF->Line($coordX, $coordY, 200, $coordY, $style = $style6);
        $coordY+=$oPDF->getFontSize()+1;
        $oPDF->SetFont('helvetica', '', '9');
        $oPDF->SetXY($coordX, $coordY);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'File Name',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+33, $coordY);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,$str2,0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+125, $coordY);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'Date',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+155, $coordY);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,date('M d, Y'),0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $coordY+=$oPDF->getFontSize()+2;
        $oPDF->SetXY($coordX, $coordY);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'Employer Name',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+33, $coordY);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,$compname,0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+125, $coordY);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'Applicable Month',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+155, $coordY);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,$str2_,0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $coordY+=$oPDF->getFontSize()+2;
        $oPDF->SetXY($coordX, $coordY);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'Employer ID',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+33, $coordY);	
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,$compsssno,0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $coordY+=$oPDF->getFontSize()+5;
        
        
        $oPDF->SetXY($coordX+25, $coordY);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'SSS',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+50, $coordY);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'EC',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+85, $coordY);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'Total',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+115, $coordY);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'SBR#/OR#',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+155, $coordY);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'Date Paid',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $coordY+=$oPDF->getFontSize()+2;

        $eree_cont = 0;
        $subtotal = 0;
        $total_ec = 0;
        $eree = 0;
        $ec = 0;
        $total = 0;

        if(count($arrData)>0){
            $ctr = 0;
            foreach ($arrData as $key => $val) {
	            $eree = $val['sss']['ppe_amount_employer']+$val['sss']['ppe_amount'];
	            $ec = $val['sss']['ppe_units'];
	            $total = $val['sss']['ppe_amount_employer']+$val['sss']['ppe_amount']+$val['sss']['ppe_units'];
	            
                $eree_cont += $eree;
                $empcon += $val['sss']['ppe_amount'];
                $emrcon += $val['sss']['ppe_amount_employer'];
                $subtotal += $total;
                $total_ec += $ec;
                $ctr++;
            }
			 $coordY+=$oPDF->getFontSize()-2;
             $oPDF->SetXY($coordX, $coordY);
	         $oPDF->MultiCell($oPDF->getPageWidth(), 3,'Amount',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
	         $oPDF->SetXY($coordX+25, $coordY);
	         $oPDF->MultiCell(30, 3,$objClsMngeDecimal->setFinalDecimalPlaces($eree_cont),0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
	         $oPDF->SetXY($coordX+50, $coordY);
	         $oPDF->MultiCell(30, 3,$objClsMngeDecimal->setFinalDecimalPlaces($total_ec),0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
             $oPDF->SetXY($coordX+85, $coordY);
             $oPDF->MultiCell(30, 3,$objClsMngeDecimal->setFinalDecimalPlaces($subtotal),0,'L');
             $oPDF->SetXY($coordX+115, $coordY);
             $oPDF->MultiCell(30, 3, $gData['sbr'],0,'L');
             $oPDF->SetXY($coordX+155, $coordY);
             $oPDF->MultiCell(30, 3, $gData['date_paid'],0,'L');
             
             $oPDF->SetFont('helvetica', '', '9');
             $coordY+=$oPDF->getFontSize()+12;
             $oPDF->SetXY($coordX, $coordY);
             $oPDF->MultiCell(60, 3, "TOTAL NUMBER OF EMPLOYEES:",0,'L');
             $oPDF->SetXY($coordX+58, $coordY);
             $oPDF->MultiCell(60, 3, $ctr,0,'L');
             $coordY+=$oPDF->getFontSize()+12;
             $coordY = $coordY+6;
             $oPDF->SetFont('helvetica', '', '9');
             $oPDF->SetXY($coordX, $coordY);
             $oPDF->MultiCell(195, 3,'CERTIFIED CORRECT AND PAID:',0,'C');
        	 $coordY = $coordY+10;
             
             $oPDF->SetXY($coordX, $coordY);
             $oPDF->MultiCell(100, 3, "RECEIVED BY:",0,'L');
             $oPDF->Line($coordX+60, $coordY+4, 140, $coordY+4, $style = $style6);
             $coordY+=$oPDF->getFontSize()+4;
             $oPDF->SetXY($coordX, $coordY);
             $oPDF->MultiCell(100, 3, "DATE RECEIVED:",0,'L');
             $oPDF->Line($coordX+60, $coordY+4, 140, $coordY+4, $style = $style6);
             $coordY+=$oPDF->getFontSize()+4;
             $oPDF->SetXY($coordX, $coordY);
             $oPDF->MultiCell(100, 3, "TRANSACTION NO:",0,'L');
             $oPDF->Line($coordX+60, $coordY+4, 140, $coordY+4, $style = $style6);
             
//             $preparedby = $this->getEmpDetailsforReport($_GET['emp_id1']);
        }
        // get the pdf output
        $output = $oPDF->Output("SSS Transmittal Certification_".date('Y-m-d').".pdf");

        if(!empty($output)){
            return $output;
        }
        return false;
    }
    
    /**
     * @note: This is used to get the dropdown menu in Location.
     * @param String $branchinfo_id_
     * @return multitype:
     */
	function dbfetchLocationDetails($branchinfo_id_ = null){
		$listloc = $_SESSION[admin_session_obj][user_branch_list2];
		IF(count($listloc)>0){
			$qry[] = "branchinfo_id in (".$listloc.")";//location that can access
		}
		if($branchinfo_id_!=null || $branchinfo_id_ != ''){
			$qry[] = "branchinfo_id = '".$branchinfo_id_."'";
		}
		$criteria = (count($qry)>0)?" WHERE ".implode(" AND ",$qry):"";
		$sql = "SELECT branchinfo_name, branchinfo_id FROM branch_info $criteria";
		$rsResult = $this->conn->Execute($sql);
		$cResult = array();
		while ( !$rsResult->EOF ) {
			$cResult[$rsResult->fields['branchinfo_id']] = $rsResult->fields['branchinfo_name'];
        	$rsResult->MoveNext();
		}	 
			return $cResult;
    }
    
	function getLocationInfo($branchinfo_id_ = null){//get Location Info.
		if($branchinfo_id_!=null || $branchinfo_id_ != ''){
			$qry[] = "branchinfo_id = '".$branchinfo_id_."'";
		}
		$criteria = (count($qry)>0)?" WHERE ".implode(" AND ",$qry):"";
		$sql = "SELECT * FROM branch_info $criteria";
		$rsResult = $this->conn->Execute($sql);
		if(!$rsResult->EOF){
			return $rsResult->fields;
		}
    }
	
    /**
     * @note: This is used to get the dropdown menu in company.
     * @param $comp_id_
     */
    function dbfetchBranchDetails($comp_id_ = null){
    	$listcomp = $_SESSION[admin_session_obj][user_comp_list2];
		IF(count($listcomp)>0){
			$qry[] = "comp_id in (".$listcomp.")";//company that can access
		}
    	if($comp_id_!=null || $comp_id_ != ''){
			$qry[] = "comp_id = '".$comp_id_."'";
		}
		$criteria = (count($qry)>0)?" WHERE ".implode(" AND ",$qry):"";
		$sql = "SELECT comp_name, comp_id FROM company_info $criteria";
		$rsResult = $this->conn->Execute($sql);
		$cResult = array();
		while ( !$rsResult->EOF ) {
			$cResult[$rsResult->fields['comp_id']] = $rsResult->fields['comp_name'];
        	$rsResult->MoveNext();
		}	 
		return $cResult;
    }
    
    function dbfetchCompDetails($comp_id_ = null){
		if($comp_id_!=null || $comp_id_ != ''){
			$qry[] = "comp_id = '".$comp_id_."'";
			$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";
		}
		$sql = "select * from company_info $criteria";
		$rsResult = $this->conn->Execute($sql);
		if(!$rsResult->EOF){
			return $rsResult->fields;
		}
    }
    
	function dbfetchCompDetails_TAX($comp_id_ = null){
		if($comp_id_!=null || $comp_id_ != ''){
		$qry[] = "a.comp_id = '".$comp_id_."'";
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";
		}
		$sql = "select * 
				from tax_employer a 
				join company_info b on (a.comp_id=b.comp_id) 
				$criteria";
		$rsResult = $this->conn->Execute($sql);
		if(!$rsResult->EOF){
			return $rsResult->fields;
		}
    }

    function getEmpDetailsforReport($emp_id_){
        if($emp_id_ == ""){
            return false;
        }
        $sql = "SELECT upper(rpad(a.pi_fname,1,1)) as pi_fname,upper(RPAD(a.pi_mname,1,' ')) as pi_mname,a.pi_lname,d.post_name
                FROM emp_personalinfo a
                INNER JOIN emp_masterfile c ON (b.pi_id = c.pi_id)
                INNER JOIN emp_position d ON (d.post_id = c.post_id)
                WHERE c.emp_id = '".$emp_id_."'";
		$rsResult = $this->conn->Execute($sql);
		if(!$rsResult->EOF){
            $rsResult->fields['name']=ucfirst($rsResult->fields['pi_fname'])."".ucfirst($rsResult->fields['pi_mname'])."".ucfirst($rsResult->fields['pi_lname']);
			return $rsResult->fields;
		}
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

		$sql = "insert into app_modules set $fields";
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

		$sql = "update app_modules set $fields where mnu_id=$id";
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
				$qry[] = "mnu_name like '%$search_field%'";

			}
		}

		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		 "mnu_name"=>"mnu_name"
		,"mnu_link"=>"mnu_link"
		,"mnu_ord"=>"mnu_ord"
		);

		if(isset($_GET['sortby'])){
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}

		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$viewLink = "";
		$editLink = "<a href=\"?statpos=sss&edit=',am.mnu_id,'\"><img src=\"".SYSCONFIG_THEME_URLPATH.SYSCONFIG_THEME."/images/admin/edit.gif\" title=\"Edit\" hspace=\"2px\" border=0></a>";
		$delLink = "<a href=\"?statpos=sss&delete=',am.mnu_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_THEME_URLPATH.SYSCONFIG_THEME."/images/admin/trash.gif\" title=\"Delete\" hspace=\"2px\"  border=0></a>";

		// SqlAll Query
		$sql = "select am.*, CONCAT('$viewLink','$editLink','$delLink') as viewdata
						from app_modules am
						$criteria
						$strOrderBy";

		// Sql query for paginator list
		// @note no need to use this. it replaced by sql function "FOUND_ROWS()"
		//$sqlcount = "select count(*) as mycount from app_modules $criteria";

		// Field and Table Header Mapping
		$arrFields = array(
		 "mnu_name"=>"Module Name"
		,"mnu_link"=>"Link"
		,"mnu_ord"=>"Order"
		,"viewdata"=>"&nbsp;"
		);

		// Column (table data) User Defined Attributes
		$arrAttribs = array(
		"mnu_ord"=>" align='right'",
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
	
	/**
	 * This is used to Generate Disket file.
	 * @param unknown_type $gData
	 */
	function generateReportDisc($gData = array()){

        $qry = array();
        $qry[] = "h.payperiod_id = ".$gData['payperiod_id']."";
        $qry[] = "c.emp_status = 1";
        $qry[] = "g.pbd_id = ".$gData['pbd_id']."";
        $criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";

        $sql = "select concat(e.pi_lastname,', ',e.pi_firstname) as name,f.pea_account_no,c.emp_id
                from azt_payroll_db.payroll_pay_period h
                inner join azt_payroll_db.payroll_pay_period_sched a on (a.pps_id=h.pps_id)
                inner join azt_payroll_db.payroll_pps_user b on (b.pps_id=a.pps_id)
                inner join azt_hris_db.hris_emp_masterfile c on (c.emp_id=b.emp_id)
                inner join azt_hris_db.hris_emp_pinfo_master_rel d on (d.emp_id=c.emp_id)
                inner join azt_hris_db.hris_emp_personalinfo e on (e.pi_id=d.pi_id)
                inner join azt_payroll_db.payroll_employee_account f on (f.emp_id = c.emp_id)
                inner join azt_payroll_db.payroll_bank_details g on (g.pbd_id = f.pbd_id)
                $criteria
                order by e.pi_lastname";
        $rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF){
			$arrData[$rsResult->fields['emp_id']] = $rsResult->fields;
            $arrData[$rsResult->fields['emp_id']]['net'] = $this->getNetpayPerEmp($rsResult->fields['emp_id'],$gData['payperiod_id']);
            $arrData[$rsResult->fields['emp_id']]['hash'] = $this->computeHorizontalHash($rsResult->fields['pea_account_no'],$arrData[$rsResult->fields['emp_id']]['net']);
            //company code
            $text_hash = 'D'.str_pad($gData['pbr_company_code'],5,'00000',STR_PAD_LEFT);
            //convert date to mdy
            $date = dDate::getFormattedDateStamp(dDate::parseDateTime($gData['pbr_trans_date']));
            $text_hash .= str_pad($date,5,'000000',STR_PAD_LEFT);
            //batch no+'3'fixed
            $text_hash .= str_pad($gData['pbr_batchno'],2,'00',STR_PAD_LEFT)."3";
            //empolyee account no
            $text_hash .= str_replace('-', "", $rsResult->fields['pea_account_no']);
            //net pay
            $text_hash .= str_pad(str_replace('.', "", $arrData[$rsResult->fields['emp_id']]['net']),12,'000000000000',STR_PAD_LEFT);
            //hash
            $text_hash .= str_pad(str_replace('.', "", $arrData[$rsResult->fields['emp_id']]['hash']),12,'000000000000',STR_PAD_LEFT);
            $text_hash .= str_repeat(" ", 79);
            $arrData[$rsResult->fields['emp_id']]['txt_hash'] =$text_hash;
            $rsResult->MoveNext();
		}
//        printa($arrData);
        return $arrData;
    }
    
    /**
     * @note: this function used to create R3 files Disket.
     * @param $arrData
     * @param $gData
     */
    function generateTxtFileR3($arrData = array(), $gData = array()){
      IF (count($arrData) > 0) {
            $ctr = 0;
            IF($this->getSettings($gData['comp'],12) && ($gData['branchinfo_id'] != 0 || $gData['branchinfo_id'] != "N/A")){
	        	$branch_details = $this->getLocationInfo($gData['branchinfo_id']);
	        	$compname = $branch_details['branchinfo_name'];
	        	$compadds = $branch_details['branchinfo_add'];
	        	$compsssno = $branch_details['branchinfo_sss'];
	        	$comptinno = $branch_details['branchinfo_tin'];
	        	$comptelno = $branch_details['branchinfo_tel1'];
	        }ELSE{
	        	$compname = $arrData['0']['emp_info']['comp_name'];
	        	$compsssno = $arrData['0']['emp_info']['comp_sss'];
	        }
            //build hash header for the text hash report
	        $hashHeader_ ='00'.substr(str_pad(trim($compname),30,' ',STR_PAD_RIGHT),0,30);
	        $hashHeader_ .= str_pad($gData['month'].$gData['year'],6,' ',STR_PAD_RIGHT);
			$comp_sss = str_replace("-", "", trim($compsssno));
	        $hashHeader_ .= substr(str_pad($comp_sss,10,' ',STR_PAD_RIGHT),0,10);
	        $hashHeader_ .= str_pad(' ',10,' ',STR_PAD_RIGHT);
	        $hashHeader_ .= str_pad(' ',8,' ',STR_PAD_RIGHT);
	        $hashHeader_ .= str_pad(' ',12,' ',STR_PAD_RIGHT);
	        
            foreach ($arrData as $key => $val) {
            	if($gData['month']=='01' || $gData['month']=='04' || $gData['month']=='07' || $gData['month']=='10'){
            		$eree = $val['sss']['ppe_amount_employer']+$val['sss']['ppe_amount'];
	            	$ec = $val['sss']['ppe_units'];
            	}else if($gData['month']=='02' || $gData['month']=='05' || $gData['month']=='08' || $gData['month']=='11'){
            		$eree2 = $val['sss']['ppe_amount_employer']+$val['sss']['ppe_amount'];
	            	$ec2 = $val['sss']['ppe_units'];
            	}else{
            		$eree3 = $val['sss']['ppe_amount_employer']+$val['sss']['ppe_amount'];
	            	$ec3 = $val['sss']['ppe_units'];
            	}
		    	$ctr++;
		    	
	            //build hash body for the text hash report
	            $text_hash = '20'.substr(str_pad($val['emp_info']['pi_lname'],15,' ',STR_PAD_RIGHT),0,15);
	            $text_hash .= substr(str_pad($val['emp_info']['pi_fname'],15,' ',STR_PAD_RIGHT),0,15);
	            $text_hash .= substr(str_pad($val['emp_info']['pi_mname'],1,' ',STR_PAD_RIGHT),0,1);
	            $text_hash .= str_pad(str_replace('-',"",$val['emp_info']['pi_sss']),10,' ',STR_PAD_RIGHT);
	            $text_hash .= ' '.str_pad(number_format($eree,2,'.',''),7,' ',STR_PAD_LEFT);
	            $text_hash .= ' '.str_pad(number_format($eree2,2,'.',''),7,' ',STR_PAD_LEFT);
	            $text_hash .= ' '.str_pad(number_format($eree3,2,'.',''),7,' ',STR_PAD_LEFT);
	            $text_hash .= ' '.str_pad(number_format(substr('0.00',0,5),2,'.',''),5,' ',STR_PAD_LEFT);
	            $text_hash .= ' '.str_pad(number_format(substr('0.00',0,5),2,'.',''),5,' ',STR_PAD_LEFT);
	            $text_hash .= ' '.str_pad(number_format(substr('0.00',0,5),2,'.',''),5,' ',STR_PAD_LEFT);
	            $text_hash .= ' '.str_pad(number_format(substr($ec,0,5),2,'.',''),5,' ',STR_PAD_LEFT);
	            $text_hash .= ' '.str_pad(number_format(substr($ec2,0,5),2,'.',''),5,' ',STR_PAD_LEFT);
	            $text_hash .= ' '.str_pad(number_format(substr($ec3,0,5),2,'.',''),5,' ',STR_PAD_LEFT);
	            $text_hash .= str_repeat(" ",6).str_pad('N',1,' ',STR_PAD_RIGHT);
				$date = new DateTime($val['emp_info']['emp_hiredate']);
	            $text_hash .= str_pad($date->format('mdY'),8,' ',STR_PAD_RIGHT);
				$arr_txthash[$ctr]['txt_hash'] = $text_hash;         
	            
            	if($gData['month']=='01' || $gData['month']=='04' || $gData['month']=='07' || $gData['month']=='10'){
            		$eree_cont += $eree;
            		$total_ec += $ec;
            	}else if($gData['month']=='02' || $gData['month']=='05' || $gData['month']=='08' || $gData['month']=='11'){
            		$eree_cont2 += $eree2;
            		$total_ec2 += $ec2;
            	}else{
            		$eree_cont3 += $eree3;
            		$total_ec3 += $ec3;
            	}
            }
            
            //build hash footer for the text hash report
	        $hashFooter_ ='99 '.str_pad(number_format(substr($eree_cont,0,11),2,'.',''),11,' ',STR_PAD_LEFT);
	        $hashFooter_ .=' '.str_pad(number_format(substr($eree_cont2,0,11),2,'.',''),11,' ',STR_PAD_LEFT);
	        $hashFooter_ .=' '.str_pad(number_format(substr($eree_cont3,0,11),2,'.',''),11,' ',STR_PAD_LEFT);
	        $hashFooter_ .=' '.str_pad(number_format(substr('0.00',0,9),2,'.',''),9,' ',STR_PAD_LEFT);
	        $hashFooter_ .=' '.str_pad(number_format(substr('0.00',0,9),2,'.',''),9,' ',STR_PAD_LEFT);
	        $hashFooter_ .=' '.str_pad(number_format(substr('0.00',0,9),2,'.',''),9,' ',STR_PAD_LEFT);
	        $hashFooter_ .=' '.str_pad(number_format(substr($total_ec,0,9),2,'.',''),9,' ',STR_PAD_LEFT);
	        $hashFooter_ .=' '.str_pad(number_format(substr($total_ec2,0,9),2,'.',''),9,' ',STR_PAD_LEFT);
	        $hashFooter_ .=' '.str_pad(number_format(substr($total_ec3,0,9),2,'.',''),9,' ',STR_PAD_LEFT);
			$hashFooter_ .='                    ';
//			printa($hashHeader_);
//			printa($arr_txthash);
//			printa($hashFooter_);
//			exit;
		}    
		$output = $this->doTextHash($arr_txthash, $hashHeader_, $hashFooter_);
        return $output;
    }
    
    function doTextHash($aData_ = null,$hashHeader_ = "", $hashFooter_ = "", $delimeter_ = "\r\n" ) {
        if(is_null($aData_)){
            return "";
        }
        $this->txtHash = $hashHeader_.$delimeter_;

        if(count($aData_)>0)
        foreach ($aData_ as $keyData => $valData) {
            $this->txtHash .= $valData['txt_hash'].$delimeter_;
        }
        $this->txtHash .= $hashFooter_.$delimeter_;
        return $this->txtHash;
    }
    
    //function that will create PDF
	function createPDF($content, $paper, $orientation, $filename){
		$dompdf = new DOMPDF();
		$dompdf->load_html($content);
		$dompdf->set_paper($paper,$orientation);
		$dompdf->render();
		$dompdf->stream($filename,array('Attachment' => 0));	
	}
	
	/* will validate if employee is on per-day basis then compute the monthly rate 
	 * salarytype_id = 1 => Hourly
	 * salarytype_id = 2 => Daily
	 * salarytype_id = 3 => Weekly
	 * salarytype_id = 4 => Bi-Weekly
	 * salarytype_id = 5 => Monthly
	 * salarytype_id = 6 => Annual
	 * */
	function computeMonthlyRate($emp_id){
		$objClsMngeDecimal = new Application();
		/**$sql = "select ppu.emp_id, st.salarytype_id, fr.fr_hrperday, fr.fr_dayperweek, fr.fr_dayperyear, st.salaryinfo_basicrate from factor_rate fr
				inner join payroll_pay_period_sched pps on (pps.fr_id=fr.fr_id)
				inner join payroll_pps_user ppu on (ppu.pps_id=pps.pps_id)
				inner join salary_info st on (st.emp_id=ppu.emp_id)
				where ppu.emp_id='$emp_id'";**/
		$sql = "select * from salary_info where emp_id='$emp_id' and salaryinfo_isactive='1'";
		$rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF){
			if($rsResult->fields['salarytype_id'] == 1){
				$monthlyRate = ($rsResult->fields['salaryinfo_basicrate']*$rsResult->fields['fr_hrperday']*$rsResult->fields['fr_dayperyear'])/12;
			} elseif($rsResult->fields['salarytype_id'] == 2) {
				$monthlyRate = ($rsResult->fields['salaryinfo_basicrate']*$rsResult->fields['fr_dayperyear'])/12;
			} elseif($rsResult->fields['salarytype_id'] == 3) {
				$monthlyRate = ($rsResult->fields['salaryinfo_basicrate']*(1/$rsResult->fields['fr_dayperweek'])*$rsResult->fields['fr_dayperweek'])/12;
			} elseif($rsResult->fields['salarytype_id'] == 4) {
				$monthlyRate = ($rsResult->fields['salaryinfo_basicrate']*(1/(2*$rsResult->fields['fr_dayperweek']))*$rsResult->fields['fr_dayperweek'])/12;
			} elseif($rsResult->fields['salarytype_id'] == 6){
				$monthlyRate = $rsResult->fields['salaryinfo_basicrate']/12;
			} else {
				$monthlyRate = $rsResult->fields['salaryinfo_basicrate'];
			}
			return $objClsMngeDecimal->setFinalDecimalPlaces($monthlyRate);
		}
	}
	
	/**
	 * @note: will generate R1-A SSS PDF report
	 */
    function getPDFR1A($gData = array()){
		$paper = 'legal';
		$orientation = 'landscape';
		$filename = 'R1-A.pdf';
		$hiredate = $gData['year'] . "-" .$gData['month'];
		$qry = array();
		//$qry[] = "pbs.empdd_id = '2'";
		//$qry[] = "pbs.bldsched_period != '0'";
		$qry[] = "m.emp_hiredate LIKE '$hiredate%'";
		$qry[] = "s.salaryinfo_isactive='1'";
		if($gData['branchinfo_id'] != 0){
			$qry[] = "m.branchinfo_id='".$gData['branchinfo_id']."'";
		}
		//$qry[] = "m.emp_resigndate NOT LIKE '$hiredate%'";
		$criteria = count($qry)>0 ? " WHERE ".implode(' AND ',$qry) : '';
    	$sql = "SELECT m.emp_id, pe.pi_sss, pe.pi_fname, pe.pi_mname, pe.pi_lname, 
    			DATE_FORMAT(pe.pi_bdate, '%m/%d/%Y') as bdate, po.post_name, s.salaryinfo_basicrate, 
    			DATE_FORMAT(m.emp_hiredate, '%m/%d/%Y') as hiredate, c.comp_sss, c.comp_name, c.comp_tin, c.comp_add, c.comp_zipcode 
    			FROM emp_personal_info pe
				JOIN emp_masterfile m on m.pi_id=pe.pi_id
				JOIN emp_position po on po.post_id=m.post_id
				JOIN company_info c on c.comp_id=m.comp_id
				JOIN salary_info s on s.emp_id=m.emp_id
				$criteria
				GROUP BY m.emp_id
				ORDER BY pe.pi_lname ASC";
    	$rsResult = $this->conn->Execute($sql);
	  	if($this->getSettings($gData['comp'],9) && $gData['branchinfo_id'] != 0){
	  		$branch_details = $this->getLocationInfo($gData['branchinfo_id']);
	  		$compname = $branch_details['branchinfo_name'];
        	$compadds = $branch_details['branchinfo_add'];
        	$compsssno = $branch_details['branchinfo_sss'];
        	$comptinno = $branch_details['branchinfo_tin'];
        	$comptelno = $branch_details['branchinfo_tel1'];
	  	} else {
	  		$branch_details = $this->dbfetchCompDetails($gData['comp']);
        	$compname = $branch_details['comp_name'];
        	$compadds = $branch_details['comp_add'];
        	$compzip = $branch_details['comp_zipcode'];
        	$compsssno = $branch_details['comp_sss'];
        	$comptinno = $branch_details['comp_tin'];
        	$comptelno = $branch_details['comp_tel'];
	  	}
	  	$size = mysql_num_rows(mysql_query($sql));
	  	$max = 15;
	  	$maxpage = ceil($size/$max);
	  	$content = '';
	  	$ctr=1;
	  		$header = '<style type="text/css">
							@page { margin: 1.5em;} 
						</style>
	  					<table style="border-collapse:collapse; border:2px solid black; font-family: Helvetica; font-size:12px; page-break-after: always;" width="1290px">
	  						<tr><td><table><tr>
	  							<td width="95px"><img src="'.SYSCONFIG_CLASS_PATH.'util/dompdf/images/pdf_report/Logo_SSS.gif" width="80px" height="60px" style="margin:10px;"></td>
	  							<td style="padding-top:25px;" width="100px"><table style="border-collapse:collapse;"><tr><td><span style="font-size:23px;"><strong>R1-A</strong></span><br><span style="font-size:12px;">(03-2008)</span></td></tr></table></td>
	  							<td>
	  								<span style="padding-left:380px; font-size:16px;">Republic of the Philippines</span><br>
	  								<span style="font-size:21px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>SOCIAL SECURITY SYSTEM</strong></span><br>
	  								<span style="font-size:27px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>EMPLOYMENT REPORT</strong></span><br>
	  								<span style="font-size:16px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(Please read instructions/reminders at the back. Print all information in black ink.)</span></td>
	  						</tr></table></td></tr>
	  						
	  						<tr><td><table style="border-collapse:collapse; border-top:1px solid black;" width="1285px">
	  							<tr>
		  							<td style="border-right:1px solid black; padding-left:5px;" width="250px"><strong>EMPLOYER/SS NUMBER</strong></td>
		  							<td style="border-right:1px solid black; padding-left:5px;" width="700px"><strong>NAME OF BUSINESS/EMPLOYER</strong></td>
		  							<td style="border-right:1px solid black; padding-left:5px;"><strong>TYPE OF EMPLOYER</strong></td>
		  							<td style="padding-left:5px;"><strong>TYPE OF REPORT</strong></td>
	  							</tr>
	  							<tr>
		  							<td style="border-right:1px solid black; padding-left:5px; font-size:18px;">'.$compsssno.'</td>
		  							<td style="border-right:1px solid black; padding-left:5px; font-size:18px;">'.$compname.'</td>
		  							<td style="border-right:1px solid black; padding-left:5px;">
		  								<table>
		  									<tr><td width="18px;">&nbsp;</td><td style="border:1px solid black;" width="20px">&nbsp;</td><td>Regular</td></tr>
		  									<tr><td width="18px;">&nbsp;</td><td style="border:1px solid black;" width="20px">&nbsp;</td><td>Household(HR)</td></tr>
		  								</table>
		  							</td>
		  							<td style="padding-left:5px;">
		  								<table>
		  									<tr><td width="18px;">&nbsp;</td><td style="border:1px solid black;" width="20px">&nbsp;</td><td>Initial</td></tr>
		  									<tr><td width="18px;">&nbsp;</td><td style="border:1px solid black;" width="20px">&nbsp;</td><td>Subsequent</td></tr>
		  								</table>
		  							</td>
	  							</tr>
	  						</table></td></tr>
	  						
	  						<tr><td><table style="border-collapse:collapse; border-top:1px solid black;" width="1285px">
	  							<tr>
		  							<td style="padding-left:5px;" width="80px"><strong>AREA CODE</strong></td>
		  							<td style="border-right:1px solid black; padding-left:5px;" width="164px"><strong>TELEPHONE NUMBER</strong></td>
		  							<td style="border-right:1px solid black; padding-left:5px;" width="870px"><strong>BUSINESS ADDRESS</strong></td>
		  							<td style="padding-left:5px;"><strong>POSTAL CODE</strong></td>
	  							</tr>
	  							<tr>
		  							<td style="padding-left:5px; font-size:18px;">'.substr($comptelno,0,4).'</td>
		  							<td style="border-right:1px solid black; padding-left:5px; font-size:18px;">'.substr($comptelno,4).'</td>
		  							<td style="border-right:1px solid black; padding-left:5px; font-size:18px;">'.$compadds.'</td>
		  							<td style="padding-left:5px; font-size:18px;">'.$compzip.'</td>
	  							</tr>
	  						</table></td></tr>
	  					<tr><td>
	  						<table style="border-collapse:collapse; border-top:1px solid black;" width="1273px">
	  							<tr>
	  								<td width="5px">&nbsp;</td>
		  							<td style="border-right:2px solid black;" align="center" width="122px"><strong>SS NUMBER</strong></td>
		  							<td style="border-right:2px solid black;" align="center" colspan="3" width="330px"><strong>NAME OF EMPLOYEE</strong></td>
		  							<td style="border-right:2px solid black;" align="center" width="140px"><strong>DATE OF BIRTH</strong></td>
		  							<td style="border-right:2px solid black;" align="center" width="120px"><strong>DATE OF EMPLOYMENT</strong></td>
		  							<td style="border-right:2px solid black;" align="center" width="70px"><strong>MONTHLY EARNINGS</strong></td>
		  							<td style="border-right:2px solid black;" align="center" width="220px"><strong>POSITION</strong></td>
		  							<td style="border-right:2px solid black;" align="center" width="93px"><strong>RELATIONSHIP WITH OWNER/<br>HR</strong></td>
		  							<td align="center">For SSS Use</td>
	  							</tr>';
	  		if($size>0){
	  				while(!$rsResult->EOF){
	  					$emp_count = $ctr;
	  					$main .='<tr>
	  								<td style="border-top:1px solid black; padding:3px 0;">'.$ctr.')</td>
		  							<td style="border-right:2px solid black; border-top:1px solid black;" align="left">&nbsp;&nbsp;'.$rsResult->fields['pi_sss'].'</td>
		  							<td align="left" width="100px" style="border-top:1px solid black;">&nbsp;&nbsp;'.$rsResult->fields['pi_lname'].'</td>
		  							<td align="left" width="120px" style="border-top:1px solid black;">'.$rsResult->fields['pi_fname'].'</td>
		  							<td style="border-right:2px solid black; border-top:1px solid black;" align="center" width="100px">'.$rsResult->fields['pi_mname'].'</td>
		  							<td style="border-right:2px solid black; border-top:1px solid black;" align="center">'.$rsResult->fields['bdate'].'</td>
		  							<td style="border-right:2px solid black; border-top:1px solid black;" align="center">'.$rsResult->fields['hiredate'].'</td>
		  							<td style="border-right:2px solid black; border-top:1px solid black;" align="center">'.$this->computeMonthlyRate($rsResult->fields['emp_id']).'</td>
		  							<td style="border-right:2px solid black; border-top:1px solid black; padding-left:2px; font-size:12px;" align="left">'.$rsResult->fields['post_name'].'</td>
		  							<td style="border-right:2px solid black; border-top:1px solid black;" align="center">&nbsp;</td>
		  							<td style="border-top:1px solid black;">&nbsp;</td>
	  							</tr>';
	  				if($ctr >= $max){ 
	  						$footer =	'<tr>
	  									<td colspan="2" style="border-top:1px solid black; border-right:2px solid black;">
	  										<table>
	  											<tr><td>&nbsp;</td></tr>
	  											<tr>
	  												<td style="font-size:10px" width="30px"><strong>TOTAL NO. OF REPORTED EMPLOYEE/S</strong></td>
	  												<td width="20px"><img src="'.SYSCONFIG_CLASS_PATH.'util/dompdf/images/pdf_report/triangle.png" height="20px" width="20px"></td>
	  												<td style="border:1px solid black; font-size:18px;" align="center">'.$emp_count.'</td>
	  											</tr>
	  											<tr><td>&nbsp;</td></tr>
	  											<tr><td colspan="3">Page 1 of 1 Page/s</td></tr>
	  										</table>
	  									</td>
	  									<td colspan="3" align="center" style="border-top:1px solid black; border-right:2px solid black;">
	  										<table style="border-collapse:collapse;">
	  											<tr><td style="font-size:0.8em;" colspan="3"><strong>&nbsp;&nbsp;&nbsp;NAME OF OWNER/MANAGING PARTNER/PRESIDENT/CHAIRMAN:&nbsp;&nbsp;</strong></td></tr>
	  											<tr><td colspan="3">&nbsp;</td></tr>
	  											<tr><td colspan="3" style="border-bottom:1px solid black;" align="center">'.$gData['corectby'].'&nbsp;</td></tr>
	  											<tr><td colspan="3" style="font-size:10px;" align="center"><strong>I CERTIFY TO THE CORRECTNESS OF ABOVE INFORMATION.</strong></td></tr>
												<tr><td colspan="3">&nbsp;</td></tr>
												<tr>
													<td width="160px">_______________________</td>
													<td>_____________</td>
													<td>__________</td>
												</tr>
												<tr>
	  												<td style="font-size:10px;" align="center">Signature Over Printed Name</td>
	  												<td style="font-size:10px;" align="center">Official Designation</td>
	  												<td style="font-size:10px;" align="center">Date</td>
	  											</tr>
											</table>
	  									</td>
		  									<td colspan="2" style="border-top:1px solid black; border-right:2px solid black;">
	  										<table style="border-collapse:collapse; padding-left:25px;" width="90%">
	  											<tr><td style="font-size:10px;"><strong>RECEIVED/L-501 VERIFIED BY/DATE:</strong></td></tr>
	  											<tr><td>&nbsp;</td></tr>
	  											<tr><td>&nbsp;</td></tr>
	  											<tr><td>&nbsp;</td></tr>
	  											<tr><td>&nbsp;</td></tr>
	  											<tr><td align="center" style="border-bottom:1px solid black;">&nbsp;</td></tr>
	  											<tr><td align="center">Signature Over Printed Name</td></tr>
											</table>
	  									</td>
	  									<td colspan="2" style="border-top:1px solid black; border-right:2px solid black;">
	  										<table style="border-collapse:collapse; padding-left:25px;" width="90%">
	  											<tr><td style="font-size:10px;"><strong>ENCODED BY/DATE:</strong></td></tr>
	  											<tr><td>&nbsp;</td></tr>
	  											<tr><td>&nbsp;</td></tr>
	  											<tr><td>&nbsp;</td></tr>
	  											<tr><td>&nbsp;</td></tr>
	  											<tr><td align="center" style="border-bottom:1px solid black;">&nbsp;</td></tr>
	  											<tr><td align="center">Signature Over Printed Name</td></tr>
											</table>
	  									</td>
	  									<td colspan="2" style="border-top:1px solid black;">
	  										<table style="border-collapse:collapse; padding-left:25px;" width="90%">
	  											<tr><td style="font-size:10px;"><strong>EVALUATED BY/DATE:</strong></td></tr>
	  											<tr><td>&nbsp;</td></tr>
	  											<tr><td>&nbsp;</td></tr>
	  											<tr><td>&nbsp;</td></tr>
	  											<tr><td>&nbsp;</td></tr>
	  											<tr><td align="center" style="border-bottom:1px solid black;">&nbsp;</td></tr>
	  											<tr><td align="center">Signature Over Printed Name</td></tr>
											</table>
	  									</td>
	  								</tr>	  					
	  							</table>
	  					</td></tr>
	  					</table>';
	  						$main .= $footer;
	  						$main .= $header;
	  						$ctr = 0;
	  						}
	  					$ctr++;
	  					$rsResult->MoveNext();
	  					}
    		} else {
    			while($ctr<=$max){
	  					$main .= '<tr>
	  								<td style="border-top:1px solid black; padding:5px 0;">'.$ctr.')</td>
		  							<td style="border-right:2px solid black; border-top:1px solid black;" align="left">&nbsp;</td>
		  							<td align="left" width="100px" style="border-top:1px solid black;">&nbsp;</td>
		  							<td align="left" width="100px" style="border-top:1px solid black;">&nbsp;</td>
		  							<td style="border-right:2px solid black; border-top:1px solid black;" align="center" width="100px">&nbsp;</td>
		  							<td style="border-right:2px solid black; border-top:1px solid black;" align="center">&nbsp;</td>
		  							<td style="border-right:2px solid black; border-top:1px solid black;" align="center">&nbsp;</td>
		  							<td style="border-right:2px solid black; border-top:1px solid black;" align="center">&nbsp;</td>
		  							<td style="border-right:2px solid black; border-top:1px solid black;" align="center">&nbsp;</td>
		  							<td style="border-right:2px solid black; border-top:1px solid black;" align="center">&nbsp;</td>
		  							<td style="border-top:1px solid black;">&nbsp;</td>
	  							</tr>';
	  					$ctr++;
    			}
    		}
		while($ctr<=$max){
			$main .= '<tr>
		  				<td style="border-top:1px solid black; padding:5px 0;">'.$ctr.')</td>
			  			<td style="border-right:2px solid black; border-top:1px solid black;" align="left">&nbsp;</td>
			  			<td align="left" width="100px" style="border-top:1px solid black;">&nbsp;</td>
			  			<td align="left" width="100px" style="border-top:1px solid black;">&nbsp;</td>
			  			<td style="border-right:2px solid black; border-top:1px solid black;" align="center" width="100px">&nbsp;</td>
			  			<td style="border-right:2px solid black; border-top:1px solid black;" align="center">&nbsp;</td>
			  			<td style="border-right:2px solid black; border-top:1px solid black;" align="center">&nbsp;</td>
			  			<td style="border-right:2px solid black; border-top:1px solid black;" align="center">&nbsp;</td>
			  			<td style="border-right:2px solid black; border-top:1px solid black;" align="center">&nbsp;</td>
			  			<td style="border-right:2px solid black; border-top:1px solid black;" align="center">&nbsp;</td>
			  			<td style="border-top:1px solid black;">&nbsp;</td>
		  			</tr>';
			$ctr++;
		}
		$footer =	'<tr>
	  									<td colspan="2" style="border-top:1px solid black; border-right:2px solid black;">
	  										<table>
	  											<tr><td>&nbsp;</td></tr>
	  											<tr>
	  												<td style="font-size:10px" width="30px"><strong>TOTAL NO. OF REPORTED EMPLOYEE/S</strong></td>
	  												<td width="20px"><img src="'.SYSCONFIG_CLASS_PATH.'util/dompdf/images/pdf_report/triangle.png" height="20px" width="20px"></td>
	  												<td style="border:1px solid black; font-size:18px;" align="center">'.$emp_count.'</td>
	  											</tr>
	  											<tr><td>&nbsp;</td></tr>
	  											<tr><td colspan="3">Page 1 of 1 Page/s</td></tr>
	  										</table>
	  									</td>
	  									<td colspan="3" align="center" style="border-top:1px solid black; border-right:2px solid black;">
	  										<table style="border-collapse:collapse;">
	  											<tr><td style="font-size:0.8em;" colspan="3"><strong>&nbsp;&nbsp;&nbsp;NAME OF OWNER/MANAGING PARTNER/PRESIDENT/CHAIRMAN:&nbsp;&nbsp;</strong></td></tr>
	  											<tr><td colspan="3">&nbsp;</td></tr>
	  											<tr><td colspan="3" style="border-bottom:1px solid black;" align="center">'.$gData['corectby'].'&nbsp;</td></tr>
	  											<tr><td colspan="3" style="font-size:10px;" align="center"><strong>I CERTIFY TO THE CORRECTNESS OF ABOVE INFORMATION.</strong></td></tr>
												<tr><td colspan="3">&nbsp;</td></tr>
												<tr>
													<td width="160px">_______________________</td>
													<td>_____________</td>
													<td>__________</td>
												</tr>
												<tr>
	  												<td style="font-size:10px;" align="center">Signature Over Printed Name</td>
	  												<td style="font-size:10px;" align="center">Official Designation</td>
	  												<td style="font-size:10px;" align="center">Date</td>
	  											</tr>
											</table>
	  									</td>
		  									<td colspan="2" style="border-top:1px solid black; border-right:2px solid black;">
	  										<table style="border-collapse:collapse; padding-left:25px;" width="90%">
	  											<tr><td style="font-size:10px;"><strong>RECEIVED/L-501 VERIFIED BY/DATE:</strong></td></tr>
	  											<tr><td>&nbsp;</td></tr>
	  											<tr><td>&nbsp;</td></tr>
	  											<tr><td>&nbsp;</td></tr>
	  											<tr><td>&nbsp;</td></tr>
	  											<tr><td align="center" style="border-bottom:1px solid black;">&nbsp;</td></tr>
	  											<tr><td align="center">Signature Over Printed Name</td></tr>
											</table>
	  									</td>
	  									<td colspan="2" style="border-top:1px solid black; border-right:2px solid black;">
	  										<table style="border-collapse:collapse; padding-left:25px;" width="90%">
	  											<tr><td style="font-size:10px;"><strong>ENCODED BY/DATE:</strong></td></tr>
	  											<tr><td>&nbsp;</td></tr>
	  											<tr><td>&nbsp;</td></tr>
	  											<tr><td>&nbsp;</td></tr>
	  											<tr><td>&nbsp;</td></tr>
	  											<tr><td align="center" style="border-bottom:1px solid black;">&nbsp;</td></tr>
	  											<tr><td align="center">Signature Over Printed Name</td></tr>
											</table>
	  									</td>
	  									<td colspan="2" style="border-top:1px solid black;">
	  										<table style="border-collapse:collapse; padding-left:25px;" width="90%">
	  											<tr><td style="font-size:10px;"><strong>EVALUATED BY/DATE:</strong></td></tr>
	  											<tr><td>&nbsp;</td></tr>
	  											<tr><td>&nbsp;</td></tr>
	  											<tr><td>&nbsp;</td></tr>
	  											<tr><td>&nbsp;</td></tr>
	  											<tr><td align="center" style="border-bottom:1px solid black;">&nbsp;</td></tr>
	  											<tr><td align="center">Signature Over Printed Name</td></tr>
											</table>
	  									</td>
	  								</tr>	  					
	  							</table>
	  					</td></tr>
	  					</table>';
			$content .= $header;
			$content .= $main;
			$content .= $footer;
	  	$this->createPDF($content, $paper, $orientation, $filename);
    }
    
    function getPDFR1A1($gData = array()){
    	$paper = 'legal';
		$orientation = 'landscape';
		$unit = 'mm';
		$unicode = true;
    	$encoding = "UTF-8";
		$hiredate = $gData['year'] . "-" .$gData['month'];

		$qry = array();
		//$qry[] = "pbs.empdd_id = '2'";
		//$qry[] = "pbs.bldsched_period != '0'";
		$qry[] = "m.emp_hiredate LIKE '$hiredate%'";
		$qry[] = "s.salaryinfo_isactive='1'";
    	IF($this->getSettings($gData['comp'],12) && ($gData['branchinfo_id'] != 0 || $gData['branchinfo_id'] != "N/A")){
        	$qry[] = "m.branchinfo_id ='".$gData['branchinfo_id']."'";
        }
		//$qry[] = "m.emp_resigndate NOT LIKE '$hiredate%'";
		$criteria = count($qry)>0 ? " WHERE ".implode(' AND ',$qry) : '';
    	$sql = "SELECT m.emp_id, pe.pi_sss, pe.pi_fname, pe.pi_mname, pe.pi_lname, 
    			DATE_FORMAT(pe.pi_bdate, '%m/%d/%Y') as bdate, po.post_name, s.salaryinfo_basicrate, 
    			DATE_FORMAT(m.emp_hiredate, '%m/%d/%Y') as hiredate, c.comp_sss, c.comp_name, c.comp_tin, c.comp_add, c.comp_zipcode 
    			FROM emp_personal_info pe
				JOIN emp_masterfile m on m.pi_id=pe.pi_id
				JOIN emp_position po on po.post_id=m.post_id
				JOIN company_info c on c.comp_id=m.comp_id
				JOIN salary_info s on s.emp_id=m.emp_id
				$criteria
				GROUP BY m.emp_id
				ORDER BY pe.pi_lname ASC";
    	$rsResult = $this->conn->Execute($sql);
    	if($this->getSettings($gData['comp'],12) && ($gData['branchinfo_id'] != 0|| $gData['branchinfo_id'] != "N/A")){
	  		$branch_details = $this->getLocationInfo($gData['branchinfo_id']);
	  		$company_name = $branch_details['branchinfo_name'];
        	$company_address = $branch_details['branchinfo_add'];
        	$compsssno = $branch_details['branchinfo_sss'];
        	$comptinno = $branch_details['branchinfo_tin'];
        	$company_tel = $branch_details['branchinfo_tel1'];
	  	} else {
	  		$branch_details = $this->dbfetchCompDetails($gData['comp']);
        	$company_name = $branch_details['comp_name'];
        	$company_address = $branch_details['comp_add'];
        	$compzip = $branch_details['comp_zipcode'];
        	$compsssno = $branch_details['comp_sss'];
        	$comptinno = $branch_details['comp_tin'];
        	$company_tel = $branch_details['comp_tel'];
	  	}
		
		$oPDF = new clsPDF($orientation, $unit, $paper, $unicode, $encoding);
		$oPDF->SetAutoPageBreak(false);
    	$oPDF->setPrintHeader(false);
		$oPDF->setPrintFooter(false);
		
		$size = mysql_num_rows(mysql_query($sql));
	  	$max = 15;
	  	$maxpage = ceil($size/$max);
		
		//Add page
		$oPDF->addPage();
		
		//set coordinates
		$coordX=0;
		$coordY=0;
		
		//company name
		$oPDF->SetFont('helvetica','',10);
		$oPDF->SetFontSize(13.5);
		$oPDF->Text($coordX+76.4, $coordY+47.3,  $company_name);
		
		//company address
		$oPDF->SetFont('helvetica','',10);
		$oPDF->SetFontSize(13.5);
		$oPDF->Text($coordX+76.4, $coordY+61.7,  $company_address);
		
		//comp sss
		$oPDF->SetFont('helvetica','',10);
		$oPDF->SetFontSize(13.5);
		$oPDF->Text($coordX+8.5, $coordY+47.3,  $compsssno);
		
		//correct by
		$cby = $gData['correctby'];
		$oPDF->SetFont('helvetica','B',10);
		$oPDF->SetFontSize(9);
		$oPDF->Text($coordX+75, $coordY+188, $gData['corectby']);
		
		
		//tin
		$oPDF->SetFont('helvetica','',10);
		$oPDF->setFontSize(14);
		if (strstr($company_tel,"-")) {
			$tel = explode('-',$company_tel);
			$tel = $tel[0].$tel[1].$tel[2];
		} else if (strstr($company_tel," ")) {
			$tel = explode(' ', $company_tel);
			$tel = $tel[0].$tel[1].$tel[2];
		} else {
			$tel = $company_tel;
		}
	
		//add tin
		$addtel = 61.8;
		$oPDF->text($coordX+8.5,$coordY+$addtel,$tel[0]);
		
		$oPDF->text($coordX+11,$coordY+$addtel,$tel[1]);
		$oPDF->text($coordX+14,$coordY+$addtel,$tel[2]);
		$oPDF->text($coordX+17,$coordY+$addtel,$tel[3]);
		$oPDF->text($coordX+31,$coordY+$addtel,$tel[4]);
		$oPDF->text($coordX+34,$coordY+$addtel,$tel[5]);
		$oPDF->text($coordX+37,$coordY+$addtel,$tel[6]);
		
		
		
		
		$oPDF->SetFont('helvetica','',10);
    	//set line format
    	$style1 = array('width' => .5, 'cap' => 'square', 'join' => 'round', 'dash' => '0', 'phase' => 0, 'color' => array(0, 0, 0));
    	$style = array('width' => .3, 'cap' => 'square', 'join' => 'round', 'dash' => '0', 'phase' => 0, 'color' => array(0, 0, 0));
    	
    	
		$oPDF->Line(7, 7, 347, 7, $style1);
		$oPDF->Line(7, 7, 7, 207, $style1);
		$oPDF->Line(347, 7, 347, 207, $style1);
		$oPDF->Line(7, 207.2, 347, 207.2, $style1);
		
		//sss logo
    	$oPDF->Image(SYSCONFIG_CLASS_PATH."util/tcpdf/images/Logo_SSS.gif", 10.5, 13, 21.2, 15.7);
    	
		//row1
		$oPDF->Line(7, 35.5, 347, 35.5, $style);
		$oPDF->Setfont('helvetica','B',10);
		$oPDF->setFontSize(17);
		$oPDF->Text($coordX+34.5, $coordY+20.5,  "R1-A");
		$oPDF->Setfont('helvetica','',10);
		$oPDF->setFontSize(9);
		$oPDF->Text($coordX+34.5, $coordY+25,  "(03-2008)");
		
		$oPDF->Setfont('helvetica','',10);
		$oPDF->setFontSize(12);
		$oPDF->Text($coordX+162.2, $coordY+11.8,  "Republic of the Philippines");
		$oPDF->Setfont('helvetica','b',10);
		$oPDF->setFontSize(15.5);
		$oPDF->Text($coordX+149.5, $coordY+18.4,  "SOCIAL SECURITY SYSTEM");
		$oPDF->setFontSize(20.5);
		$oPDF->Text($coordX+145.5, $coordY+26.5,  "EMPLOYMENT REPORT");
		$oPDF->Setfont('helvetica','',10);
		$oPDF->setFontSize(12);
		$oPDF->Text($coordX+110, $coordY+33, "(Please read instructions/reminders at the back. Print all information in black ink.)");
		
		//row 2
		$oPDF->Line(7, 52.7, 347, 52.7, $style);
		$oPDF->Setfont('helvetica','B',10);
		$oPDF->setFontSize(9);
		$oPDF->Text($coordX+8.5, $coordY+38.6,  "EMPLOYER/SS NUMBER");
		$oPDF->Line(75, 35.5, 75, 52.7, $style);
		$oPDF->Text($coordX+76.5, $coordY+38.6,  "NAME OF BUSINESS/EMPLOYER");
		$oPDF->Line(262, 35.5, 262, 52.7, $style);
		$oPDF->Text($coordX+263.3, $coordY+38.6,  "TYPE OF EMPLOYER");
		$oPDF->Line(269.7, 40.5, 275.8, 40.5, $style);
		$oPDF->Line(269.7, 40.5, 269.7, 45.5, $style);
		$oPDF->Line(269.7, 45.5, 275.8, 45.5, $style);
		$oPDF->Line(275.8, 40.5, 275.8, 45.5, $style);
		$oPDF->setFontSize(9);
		$oPDF->Text($coordX+276.6, $coordY+44,  "Regular");
		$oPDF->Line(269.7, 46, 275.8, 46, $style);
		$oPDF->Line(269.7, 46, 269.7, 51, $style);
		$oPDF->Line(269.7, 51, 275.8, 51, $style);
		$oPDF->Line(275.8, 46, 275.8, 51, $style);
		$oPDF->setFontSize(9);
		$oPDF->Text($coordX+276.6, $coordY+49.9,  "Household(HR)");
		$oPDF->Line(307, 35.5, 307, 52.7, $style);
		$oPDF->Text($coordX+308.4, $coordY+38.6,  "TYPE OF REPORT");
		$oPDF->Line(314.7, 40.5, 321, 40.5, $style);
		$oPDF->Line(314.7, 40.5, 314.7, 45.5, $style);
		$oPDF->Line(314.7, 45.5, 321, 45.5, $style);
		$oPDF->Line(321, 40.5, 321, 45.5, $style);
		$oPDF->setFontSize(9);
		$oPDF->Text($coordX+322, $coordY+44,  "Initial");
		$oPDF->Line(314.7, 46, 321, 46, $style);
		$oPDF->Line(314.7, 46, 314.7, 51, $style);
		$oPDF->Line(314.7, 51, 321, 51, $style);
		$oPDF->Line(321, 46, 321, 51, $style);
		$oPDF->setFontSize(9);
		$oPDF->Text($coordX+322, $coordY+49.5,  "Subsequent");
		
		//row 3
		$oPDF->Line(7, 64, 347, 64, $style);
		$oPDF->Setfont('helvetica','B',10);
		$oPDF->setFontSize(9);
		$oPDF->Text($coordX+8.5, $coordY+55.8,  "AREA CODE");
		$oPDF->Text($coordX+31, $coordY+55.8,  "TELEPHONE NUMBER");
		$oPDF->Line(75, 52.7, 75, 64, $style);
		$oPDF->Text($coordX+76.5, $coordY+55.8,  "BUSINESS ADDRESS");
		$oPDF->Line(307, 52.7, 307, 64, $style);
		$oPDF->Text($coordX+308.2, $coordY+55.8,  "POSTAL CODE");
		
		//row 4
		$oPDF->Line(7, 76, 347, 76, $style);
		$oPDF->setFontSize(9);
		$oPDF->Text($coordX+18.5, $coordY+71.5,  "SS NUMBER");
		$oPDF->Text($coordX+72, $coordY+71.5,  "NAME OF EMPLOYEE");
		$oPDF->Text($coordX+139.5, $coordY+71.5,  "DATE OF BIRTH");
		$oPDF->Text($coordX+179.5, $coordY+69.3,  "DATE OF");
		$oPDF->Text($coordX+175.7, $coordY+73,  "EMPLOYMENT");
		$oPDF->Text($coordX+204.5, $coordY+69.3, "MONTHLY");
		$oPDF->Text($coordX+204.5, $coordY+73, "EARNINGS");
		$oPDF->Text($coordX+244.5, $coordY+71.5,  "POSITION");
		$oPDF->Text($coordX+282, $coordY+67.5,  "RELATIONSHIP");
		$oPDF->Text($coordX+283.2, $coordY+71.5,  "WITH OWNER/");
		$oPDF->Text($coordX+292, $coordY+75.1,  "HR");
		$oPDF->Text($coordX+317.5, $coordY+71.4,  "For SSS Use");
		
		//7 lines for columns
		$oPDF->Line(44.7, 64.3, 44.7, 207.2, $style1);
		$oPDF->Line(132.8, 64.3, 132.8, 207.2, $style1);
		$oPDF->Line(170.5, 64.3, 170.5, 175.5, $style1);
		$oPDF->Line(203.2, 64.3, 203.2, 207.2, $style1);
		$oPDF->Line(222.5, 64.3, 222.5, 175.5, $style1);
		$oPDF->Line(281.7, 64.3, 281.7, 207.2, $style1);
		$oPDF->Line(307, 64.3, 307, 175.5, $style1);
		
		//textbox
		$oPDF->setFontSize(9);
		$oPDF->Text($coordX+7.2, $coordY+80.5,  "1)");
		$oPDF->Line(7, 83, 347, 83, $style);
		$oPDF->Text($coordX+7.2, $coordY+87.5,  "2)");
		$oPDF->Line(7, 89.5, 347, 89.5, $style);
		$oPDF->Text($coordX+7.2, $coordY+94,  "3)");
		$oPDF->Line(7, 96.3, 347, 96.3, $style);
		$oPDF->Text($coordX+7.2, $coordY+100.7,  "4)");
		$oPDF->Line(7, 102.7, 347, 102.7, $style);
		$oPDF->Text($coordX+7.2, $coordY+107.3,  "5)");
		$oPDF->Line(7, 109.5, 347, 109.5, $style);
		$oPDF->Text($coordX+7.2, $coordY+113.8,  "6)");
		$oPDF->Line(7, 116.2, 347, 116.2, $style);
		$oPDF->Text($coordX+7.2, $coordY+120.8,  "7)");
		$oPDF->Line(7, 123, 347, 123, $style);
		$oPDF->Text($coordX+7.2, $coordY+127.3,  "8)");
		$oPDF->Line(7, 129.5, 347, 129.5, $style);
		$oPDF->Text($coordX+7.2, $coordY+134,  "9)");
		$oPDF->Line(7, 136, 347, 136, $style);
		$oPDF->Text($coordX+7.2, $coordY+140.5,  "10)");
		$oPDF->Line(7, 142.8, 347, 142.8, $style);
		$oPDF->Text($coordX+7.2, $coordY+147.2,  "11)");
		$oPDF->Line(7, 149.5, 347, 149.5, $style);
		$oPDF->Text($coordX+7.2, $coordY+154,  "12)");
		$oPDF->Line(7, 156.1, 347, 156.1, $style);
		$oPDF->Text($coordX+7.2, $coordY+160.5,  "13)");
		$oPDF->Line(7, 162.8, 347, 162.8, $style);
		$oPDF->Text($coordX+7.2, $coordY+167.3,  "14)");
		$oPDF->Line(7, 169.3, 347, 169.3, $style);
		$oPDF->Text($coordX+7.2, $coordY+173.8,  "15)");
		$oPDF->Line(7, 176, 347, 176, $style);
		
		//last row box 1
		$oPDF->Setfont('helvetica','B',10);
		$oPDF->setFontSize(7.5);
		$oPDF->Text($coordX+8, $coordY+184.7,  "TOTAL NO.");
		$oPDF->Text($coordX+8, $coordY+188,  "OF");
		$oPDF->Text($coordX+8, $coordY+191.3,  "REPORTED");
		$oPDF->Text($coordX+8, $coordY+194.5,  "EMPLOYEE/S");
		$oPDF->setFontSize(9.1);
		$oPDF->Text($coordX+8, $coordY+204.1,  "Page 1 of 1 Page/s");
		$oPDF->Image(SYSCONFIG_CLASS_PATH."util/tcpdf/images/triangle.png", 26.5, 186, 5.3, 5.3);
		$oPDF->Line(32.5, 182, 43.8, 182, $style);
		$oPDF->Line(32.5, 182, 32.5, 195.1, $style);
		$oPDF->Line(32.5, 195.1, 43.8, 195.1, $style);
		$oPDF->Line(43.8, 182, 43.8, 195.1, $style);
		
		//last rown box 2
		$oPDF->Setfont('helvetica','B',10);
		$oPDF->setFontSize(7.3);
		$oPDF->Text($coordX+47, $coordY+179.3,  "NAME OF OWNER/MANAGING PARTNER/PRESIDNET/CHAIRMAN:");
		$oPDF->Line(44.7, 189, 132.8, 189, $style);
		$oPDF->Text($coordX+49.2, $coordY+191.8,  "I CERTIFY TO THE CORRECTNESS OF ABOVE INFORMATION.");
		$oPDF->Line(44.7, 201, 86, 201, $style);
		$oPDF->Line(88.3, 201, 111, 201, $style);
		$oPDF->Line(113, 201, 130.7, 201, $style);
		$oPDF->setFontSize(7);
		$oPDF->Text($coordX+49, $coordY+204.5,  "Signature Over Printed Name");
		$oPDF->Text($coordX+88, $coordY+204.5,  "Official Designation");
		$oPDF->Text($coordX+120, $coordY+204.5,  "Date");
		
		//last row box 3
		$oPDF->Setfont('helvetica','B',10);
		$oPDF->setFontSize(7.5);
		$oPDF->Text($coordX+142, $coordY+179.3,  "RECEIVED/L-501 VERIFIED BY/DATE:");
		$oPDF->Line(139.8, 202.3, 195.9, 202.3, $style);
		$oPDF->setFontSize(9.1);
		$oPDF->Text($coordX+146, $coordY+205.5,  "Signature Over Printed Name");
		
		//last row box 4
		$oPDF->Setfont('helvetica','B',10);
		$oPDF->setFontSize(7.5);
		$oPDF->Text($coordX+210.3, $coordY+179.3,  "ENCODED BY/DATE:");
		$oPDF->Line(210, 202.3, 273.5, 202.3, $style);
		$oPDF->setFontSize(9.1);
		$oPDF->Text($coordX+221, $coordY+205.5,  "Signature Over Printed Name");
		
		//last row box 5
		$oPDF->Setfont('helvetica','B',10);
		$oPDF->setFontSize(7.5);
		$oPDF->Text($coordX+288.7, $coordY+179.3,  "EVALUATED BY/DATE:");
		$oPDF->Line(288.3, 202.3, 340, 202.3, $style);
		$oPDF->setFontSize(9.1);
		$oPDF->Text($coordX+292.5, $coordY+205.5,  "Signature Over Printed Name");
		
		
		//-------------------INPUT-------------------//
		
		$size = mysql_num_rows(mysql_query($sql));
		$max = 15;
		//$maxpage = $size/max;
		$ctr = 1;
		
		if ($size>0){
			$coordX = 13;
			$coordY = 80.5;
			
			while (!$rsResult->EOF){
				$emp_count = $ctr;
				
				$oPDF->Text($coordX, $coordY,  $rsResult->fields['pi_sss']);
				$oPDF->Text($coordX+34, $coordY, $rsResult->fields['pi_lname']);
				$oPDF->Text($coordX+60, $coordY, $rsResult->fields['pi_fname']);
				$oPDF->Text($coordX+92, $coordY, $rsResult->fields['pi_mname']);
				$oPDF->Text($coordX+131, $coordY, $rsResult->fields['bdate']);
				$oPDF->Text($coordX+166, $coordY, $rsResult->fields['hiredate']);
				$oPDF->Text($coordX+192, $coordY, $this->computeMonthlyRate($rsResult->fields['emp_id']));
				$oPDF->Text($coordX+211, $coordY, $rsResult->fields['post_name']);
				
				
				$ctr++;
				$coordY+=6.9;
				$rsResult->MoveNext();
			}
			$coordX = 13;
			$coordY = 80.5;
			$oPDF->Setfont('helvetica','B',16);
			$oPDF->Text($coordX+24, $coordY+110,  $emp_count);
			
		}
		
		
		
		//get the output
		$output = $oPDF->Output("R1-A_".$gData['year'].".pdf");
    	
    	if (!empty($output)) {
    		return $output;
    	}
    	return false;
    }
    
    function getQuarterEnd($month){
		$quarter = ceil(((int)$month)/3);
    	switch($quarter){
    		case '1': return 'March';
    		case '2': return 'June';
    		case '3': return 'September';
    		case '4': return 'December';
    	}
    }
    
	function getMonthInWords($month){
		$MonthInWords = date("M", mktime(0, 0, 0, $month));
		return $MonthInWords;
	}
	
	function getQuarterMonths($month){
		$quarter = ceil(((int)$month)/3);
		switch($quarter){
			case '1': 
					$months['1'] = $this->getMonthInWords('01');
					$months['2'] = $this->getMonthInWords('02');
					$months['3'] = $this->getMonthInWords('03');
					return $months;
			case '2': 
					$months['1'] = $this->getMonthInWords('04');
					$months['2'] = $this->getMonthInWords('05');
					$months['3'] = $this->getMonthInWords('06');
					return $months;
			case '3':
					$months['1'] = $this->getMonthInWords('07');
					$months['2'] = $this->getMonthInWords('08');
					$months['3'] = $this->getMonthInWords('09');
					return $months;
			case '4':
					$months['1'] = $this->getMonthInWords('10');
					$months['2'] = $this->getMonthInWords('11');
					$months['3'] = $this->getMonthInWords('12');
					return $months;
		}
	}
	
    function getR3($gData = array(), $comp_info = array()){
    	$paper = 'A4';
		$orientation = 'landscape';
		$filename = 'R-3.pdf';
		$months = $this->getQuarterMonths($gData['month']);
		$header = 	'<html><body>
				<style type="text/css">
					@page { margin-top: 2.1em; margin-bottom: 2.1em;}
					.main_table{
						border-collapse:collapse; border:1px solid black; font-family:Helvetica; font-size:12px; width:1025px;
					}
					.sub_table_collapse{
						border-collapse:collapse;
					}
					.border_right{
						border-right:1px solid black;
					}
					.border_right_bottom{
						border-right:1px solid black;
						border-bottom:1px solid black;
					}
					.border_bottom{
						border-bottom:1px solid black;
					}
				</style>
				<table class="main_table">
					<tr><td><table class="sub_table_collapse"><tr>
						<td width="90px"><img src="'.SYSCONFIG_CLASS_PATH.'util/dompdf/images/pdf_report/Logo_SSS.gif" width="80px" height="60px" style="margin:10px;"></td>
						<td width="210px"><table class="sub_table_collapse">
							<tr><td>&nbsp;</td></tr>
							<tr><td style="font-size:30px; font-family:Times"><strong>R-3</strong></td></tr>
							<tr><td style="font-size:10px; font-family:Times">REV. 08-99</td></tr>
						</table></td>
						<td><table class="sub_table_collapse">
							<tr><td align="center"><strong>Republic of the Philippines</strong></td></tr>
							<tr><td align="center" style="font-size:18px;">SOCIAL SECURITY SYSTEM</td></tr>
							<tr><td align="center" style="font-size:22px;">Contribution Collection List</td></tr>
							<tr><td align="center" style="font-size:12px;"><strong>(Please Read Instructions at the Back. Print All Information on Black Ink)</strong></td></tr>
						</table></td>
					</tr></table></td></tr>
					<tr><td style="border-top:1px solid black;"><table class="sub_table_collapse" style="font:bold 12px Helvetica;">
						<tr>
							<td width="200px" class="border_right">&nbsp;EMPLOYER ID NUMBER</td>
							<td width="600px" class="border_right">&nbsp;REGISTERED EMPLOYER NAME</td>
							<td width="220px">&nbsp;QUARTER ENDING</td>
						</tr>
						<tr>
							<td class="border_right_bottom">&nbsp;'.$comp_info['comp_sss'].'</td>
							<td class="border_right_bottom">&nbsp;'.$comp_info['comp_name'].'</td>
							<td class="border_bottom">&nbsp;'.$this->getQuarterEnd($gData['month']). ' '.$gData['year'].'</td>
						</tr>
						<tr>
							<td class="border_right">&nbsp;TEL NO.</td>
							<td class="border_right">&nbsp;ADDRESS</td>
							<td>&nbsp;TYPE OF EMPLOYER</td>
						</tr>
						<tr>
							<td class="border_right_bottom">&nbsp;'.$comp_info['comp_tel'].'</td>
							<td class="border_right_bottom">&nbsp;'.$comp_info['comp_add'].'</td>
							<td class="border_bottom"><table><tr>
								<td>&nbsp;</td>
								<td width="20px" style="border:1px solid black;" align="center">X</td>
								<td>Regular</td>
								<td width="20px" style="border:1px solid black;" align="center">&nbsp;</td>
								<td>Household</td>
							</tr></table></td>
						</tr>
					</table></td></tr>
					
					<tr><td><table class="sub_table_collapse" style="font:12px Helvetica;">
						<tr>
							<td width="130px" align="center" class="border_right"><strong>SSS NUMBER</strong></td>
							<td width="200px" align="center" class="border_right" colspan="3"><strong>NAME OF MEMBER</strong></td>
							<td colspan="3" align="center" width="250px" class="border_right_bottom"><strong>SOCIAL SECURITY</strong></td>
							<td colspan="3" align="center" width="250px" class="border_right_bottom"><strong>EMPLOYEE COMPENSATION</strong></td>
							<td align="center" width="100px"><strong>SEPARATION DATE</strong></td>
						</tr>
						<tr>
							<td class="border_right_bottom">&nbsp;</td>
							<td align="center" width="100px" class="border_bottom">(Surname)</td>
							<td align="center" width="100px" class="border_bottom">(Given Name)</td>
							<td align="center" class="border_right_bottom">(M.I.)</td>
							<td align="center" class="border_right_bottom">1st Month</td>
							<td align="center" class="border_right_bottom">2nd Month</td>
							<td align="center" class="border_right_bottom">3rd Month</td>
							<td align="center" class="border_right_bottom">1st Month</td>
							<td align="center" class="border_right_bottom">2nd Month</td>
							<td align="center" class="border_right_bottom">3rd Month</td>
							<td align="center" class="border_bottom"><strong>(MM/DD/YYYY</strong>)</td>
						</tr>';
				for($count=1; $count<=15; $count++){
				$main .='<tr>
								<td class="border_right_bottom">12-3456789-0</td>
								<td align="center" class="border_bottom">'.$count. '. Surname,</td>
								<td align="center" class="border_bottom">Firstname</td>
								<td align="center" class="border_right_bottom">M</td>
								<td align="right" class="border_right_bottom">0.00</td>
								<td align="right" class="border_right_bottom">0.00</td>
								<td align="right" class="border_right_bottom">0.00</td>
								<td align="right" class="border_right_bottom">0.00</td>
								<td align="right" class="border_right_bottom">0.00</td>
								<td align="right" class="border_right_bottom">0.00</td>
								<td align="center" class="border_bottom">&nbsp;</td>
							</tr>';
						}
				$footer ='</tr></td></table>
				<tr><td><table class="sub_table_collapse">
					<tr>
						<td colspan="4" style="border-right:1px solid black; border-bottom:1px solid black;" align="center"><strong>GRAND TOTAL</strong></td>
						<td colspan="3" style="border-right:1px solid black; border-bottom:1px solid black;" align="center"><strong>PAYMENT DETAILS</strong></td>
						<td style="border-right:1px solid black;" rowspan="5" width="140px">
							<table class="sub_table_collapse">
								<tr><td style="padding-bottom:5px; font-size:12px;"><strong>ADJUSTMENT TYPE</strong></td></tr>
								<tr><td>&nbsp;</td></tr>
								<tr><td><table class="sub_table_collapse">
									<tr><td><span style="border:1px solid black; font-size:14px;">&nbsp;&nbsp;&nbsp;</span></td><td style="font-size:10px">Addition to Previously</td></tr>
									<tr><td>&nbsp;</td><td style="font-size:10px">Submitted R3</td></tr>
									<tr><td><span style="border:1px solid black; font-size:14px;">&nbsp;&nbsp;&nbsp;</span></td><td style="font-size:10px">Deduction From Previously</td></tr>
									<tr><td>&nbsp;</td><td style="font-size:10px">Submitted R3</td></tr>
								</table></td></tr>
							</table>
						</td>
						<td style="border-right:1px solid black; font-size:9px; vertical-align:top;" rowspan="5">
							<table class="sub_table_collapse">
								<tr><td>CERTIFIED CORRECT AND PAID:</td></tr>
								<tr><td>&nbsp;</td></tr>
								<tr><td>&nbsp;</td></tr>
								<tr><td>&nbsp;</td></tr>
								<tr><td style="border-bottom:1px solid black; font-size:13px;" align="center">&nbsp;'.$gData['corectby'].'</td></tr>
								<tr><td style="font-size:10px;" align="center"><strong>Signature Over Printed Name</strong></td></tr>
								<tr><td>&nbsp;</td></tr>
								<tr><td>&nbsp;</td></tr>
								<tr><td>&nbsp;</td></tr>
								<tr><td><table class="sub_table_collapse">
									<tr>
										<td style="border-bottom:1px solid black;" width="97px">&nbsp;</td>
										<td>&nbsp;</td>
										<td style="border-bottom:1px solid black;" width="40px">&nbsp;</td>
									</tr>
									<tr>
										<td align="center">Official Designation</td>
										<td align="center">&nbsp;</td>
										<td align="center">Date</td>
									</tr>
								</table></td></tr>
							</table>
						</td>
						<td rowspan="5" align="center" width="114px">PAGE 1<br><br><br>OF<br><br><br>PAGES 1</td>
					</tr>
					<tr>
						<td style="border-right:1px solid black; border-bottom:1px solid black;" width="50px" align="center"><strong>Appl.Mo.</strong></td>
						<td style="border-right:1px solid black; border-bottom:1px solid black;" width="90px" align="center"><strong>Social Security</strong></td>
						<td style="border-right:1px solid black; border-bottom:1px solid black;" width="70px" align="center"><strong>Employee<br>Compensation</strong></td>
						<td style="border-right:1px solid black; border-bottom:1px solid black;" width="93px" align="center"><strong>Grand Total</strong></td>
						<td style="border-right:1px solid black; border-bottom:1px solid black;" width="100px" align="center"><strong>TR/SBR NO.</strong></td>
						<td style="border-right:1px solid black; border-bottom:1px solid black;" width="82px" align="center"><strong>Date Paid</strong></td>
						<td style="border-right:1px solid black; border-bottom:1px solid black;" width="100px" align="center"><strong>Amount Paid</strong></td>
					</tr>
					<tr>
						<td style="border-right:1px solid black; border-bottom:1px solid black;" align="center">'.$months[1].'<br><br></td>
						<td style="border-right:1px solid black; border-bottom:1px solid black;" align="right">&nbsp;</td>
						<td style="border-right:1px solid black; border-bottom:1px solid black;" align="right">&nbsp;</td>
						<td style="border-right:1px solid black; border-bottom:1px solid black;" align="right">&nbsp;</td>
						<td style="border-right:1px solid black; border-bottom:1px solid black;" align="right">&nbsp;</td>
						<td style="border-right:1px solid black; border-bottom:1px solid black;" align="right">&nbsp;</td>
						<td style="border-right:1px solid black; border-bottom:1px solid black;" align="right">&nbsp;</td>
					</tr>
					<tr>
						<td style="border-right:1px solid black; border-bottom:1px solid black;" align="center">'.$months[2].'<br><br></td>
						<td style="border-right:1px solid black; border-bottom:1px solid black;" align="right">&nbsp;</td>
						<td style="border-right:1px solid black; border-bottom:1px solid black;" align="right">&nbsp;</td>
						<td style="border-right:1px solid black; border-bottom:1px solid black;" align="right">&nbsp;</td>
						<td style="border-right:1px solid black; border-bottom:1px solid black;" align="right">&nbsp;</td>
						<td style="border-right:1px solid black; border-bottom:1px solid black;" align="right">&nbsp;</td>
						<td style="border-right:1px solid black; border-bottom:1px solid black;" align="right">&nbsp;</td>
					</tr>
					<tr>
						<td style="border-right:1px solid black;" align="center">'.$months[3].'<br><br></td>
						<td style="border-right:1px solid black;" align="right">&nbsp;</td>
						<td style="border-right:1px solid black;" align="right">&nbsp;</td>
						<td style="border-right:1px solid black;" align="right">&nbsp;</td>
						<td style="border-right:1px solid black;" align="right">&nbsp;</td>
						<td style="border-right:1px solid black;" align="right">&nbsp;</td>
						<td style="border-right:1px solid black;" align="right">&nbsp;</td>
					</tr>
				</table></td></tr>
				<tr><td style="border-top:1px solid black; font-size:14px;"><table class="sub_table_collapse">
					<tr>
						<td style="border-right:1px solid black;" align="center" width="51px"><strong>FOR<br>SSS<br>USE</strong></td>
						<td style="border-right:1px solid black; vertical-align:top; padding-left:5px;" width="220px"><strong>PROCESSED BY / DATE:</strong></td>
						<td style="border-right:1px solid black; vertical-align:top; padding-left:5px;" width="227px"><strong>ENCODED BY / DATE:</strong></td>
						<td style="border-right:1px solid black; vertical-align:top; padding-left:5px;" width="239px"><strong>OTHER NOTATIONS:</strong></td>
						<td style="vertical-align:top; padding-left:5px;" width="150px"><strong>RECEIVED BY / DATE:</strong></td>
					</tr>
					<tr>
						<td style="border-right:1px solid black;">&nbsp;</td>
						<td style="border-right:1px solid black; padding-left:17px;" align="center">
							<table class="sub_table_collapse;" width="90%">
								<tr><td style="border_bottom:1px solid black;" align="center">&nbsp;</td></tr>
								<tr><td align="center" style="font-size:10px;"><strong>Signature Over Printed Name</strong></td></tr>
							</table>
						</td>
						<td style="border-right:1px solid black; padding-left:17px;">
							<table class="sub_table_collapse;" width="90%">
								<tr><td style="border_bottom:1px solid black;" align="center">&nbsp;</td></tr>
								<tr><td align="center" style="font-size:10px;"><strong>Signature Over Printed Name</strong></td></tr>
							</table>
						</td>
						<td style="border-right:1px solid black;">&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
				</table></td></tr>
				</table>
			</body></html>';
    	
		$content = $header;
		$content .= $main;
		$content .= $footer;
		$this->createPDF($content, $paper, $orientation, $filename);
    }
    
    function getSettings($comp_id_ = null, $set_id_ = null){
    	$sql = "select set_stat_type from app_settings where comp_id='".$comp_id_."' and set_id='".$set_id_."'";
    	$rsResult = $this->conn->Execute($sql);
    	if(!$rsResult->EOF){
    		return $rsResult->fields['set_stat_type'];
    	}
    }
}

?>