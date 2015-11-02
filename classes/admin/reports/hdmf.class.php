<?php
/**
 * Initial Declaration
 */
require_once(SYSCONFIG_CLASS_PATH."util/pdf.class.php");
require_once(SYSCONFIG_CLASS_PATH."util/export-xls.class.php");
require_once(SYSCONFIG_CLASS_PATH."util/PHPExcel.php");
require_once(SYSCONFIG_CLASS_PATH."util/PHPExcel/IOFactory.php");
require_once(SYSCONFIG_CLASS_PATH."util/dompdf/dompdf_config.inc.php");

/**
 * Class Module
 *
 * @author  JMabignay
 *
 */
class clsHDMF{

	var $conn;
	var $fieldMap;
	var $Data;

	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsHDMF object
	 */
	function clsHDMF($dbconn_ = null){
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
		$editLink = "<a href=\"?statpos=hdmf&edit=',am.mnu_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/edit.png\" title=\"Edit\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
		$delLink = "<a href=\"?statpos=hdmf&delete=',am.mnu_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\"  border=0 width=\"16\" height=\"16\"></a>";

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
	
/* will validate if employee is on per-day basis then compute the monthly rate 
	 * salarytype_id = 1 => Hourly
	 * salarytype_id = 2 => Daily
	 * salarytype_id = 3 => Weekly
	 * salarytype_id = 4 => Bi-Weekly
	 * salarytype_id = 5 => Monthly
	 * salarytype_id = 6 => Annual
	 * */
	function computeMonthlyRate($emp_id){
		$sql = "select ppu.emp_id, st.salarytype_id, fr.fr_hrperday, fr.fr_dayperweek, fr.fr_dayperyear, st.salaryinfo_basicrate from factor_rate fr
				inner join payroll_pay_period_sched pps on (pps.fr_id=fr.fr_id)
				inner join payroll_pps_user ppu on (ppu.pps_id=pps.pps_id)
				inner join salary_info st on (st.emp_id=ppu.emp_id)
				where ppu.emp_id='$emp_id'";
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
			return number_format($monthlyRate, 2, '.', ',');
		}
	}
	
    function getHDMFContribution($month, $year, $emp_id, $isSal = 0){
//    	@todo: Need to finish for tommorow.
    	$trans_date = $year.'-'.$month;
    	IF($isSal == '1'){
    		$qrySelect[] = "sum(ppe.ppe_amount) as emp_monthlysal";
    		$criteriaselect = (count($qrySelect)>0)?implode(", ",$qrySelect):"";
    		$qry[] = "ppe.psa_id='4'";
    	}else{
    		$qrySelect[] = "sum(ppe.ppe_amount) as employee_share";
    		$qrySelect[] = "sum(ppe.ppe_amount_employer) as employer_share";
    		$criteriaselect = (count($qrySelect)>0)?implode(", ",$qrySelect):"";
    		$qry[] = "ppe.psa_id='15'";
    	}
    	$qry[] = "ppp.payperiod_period='".$month."'";
    	$qry[] = "ppp.payperiod_period_year='".$year."'";
    	$qry[] = "ppr.emp_id='".$emp_id."'";
    	// put all query array into one criteria string
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";
    	$sql = "SELECT $criteriaselect 
    			FROM payroll_paystub_entry ppe
				LEFT JOIN payroll_paystub_report ppr on (ppr.paystub_id=ppe.paystub_id)
				LEFT JOIN payroll_pay_period ppp on (ppp.payperiod_id=ppr.payperiod_id)
				$criteria";
    	$rsResult = $this->conn->Execute($sql);
    	while(!$rsResult->EOF){
    		return $rsResult->fields;
    	}  	
    }
    
    function moneyFormat($num){
    	$objClsMngeDecimal = new Application();
    	if((float)$num>0){
    		$money_format = $objClsMngeDecimal->setFinalDecimalPlaces($num);
    	}else{
    		$money_format = $objClsMngeDecimal->setFinalDecimalPlaces(0);
    	}
    	return $money_format;
    }
    
    function validateIfLastPage($max_page, $current_page, $val_){
    	if($current_page == $max_page){
    		$str = '&nbsp;';
    	} else {
    		$str = $val_;
    	}
    	return $str;
    }
    
	function createPDF($content, $paper, $orientation, $filename){
		$dompdf = new DOMPDF();
		$dompdf->load_html($content);
		$dompdf->set_paper($paper,$orientation);
		$dompdf->render();
		$dompdf->stream($filename,array('Attachment' => 0));	
	}
	
	/**
	 * @note: xlsSSS_Premium_Report
	 * @param unknown_type $gData
	 */
    function generateHDMF_Premium_Report($gData = array(),$arrData = array(), $isLoc = false) {
    	$m = $gData['year'].'-'.$gData['month'].'-'.'10';
        $filename = "HDMF_Premium_".date('FY',dDate::parseDateTime($m)).".xls"; // The file name you want any resulting file to be called.
    	// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		$objClsMngeDecimal = new Application();
		$finalDecFormat = $objClsMngeDecimal->setFinalDecimalPlaces(0);
		
		$objReader = PHPExcel_IOFactory::createReader('Excel5');
		$objPHPExcel = $objReader->load("templates/HDMF_Premium.xls");
		//header excel
    	IF($isLoc AND $gData['branchinfo_id'] != 0){
        	$branch_details = clsSSS::getLocationInfo($gData['branchinfo_id']);
        	$compname = $branch_details['branchinfo_name'];
        	$compadds = $branch_details['branchinfo_add'];
        	$comphdmfno = $branch_details['branchinfo_hdmf'];
        	$comptinno = $branch_details['branchinfo_tin'];
        	$comptelno = $branch_details['branchinfo_tel1'];
        }ELSE{
        	$compname = $arrData[0]['emp_info']['comp_name'];
        	$compadds = $arrData[0]['emp_info']['comp_add'];
        	$comphdmfno = $arrData[0]['emp_info']['comp_hdmf'];
        	$comptelno = $arrData[0]['emp_info']['comp_tel'];
        }
		$objPHPExcel->getActiveSheet()->setCellValue('A8', $compname);
		$objPHPExcel->getActiveSheet()->setCellValue('A10', $compadds);
		$objPHPExcel->getActiveSheet()->setCellValue('I4', $comphdmfno);
		$objPHPExcel->getActiveSheet()->setCellValue('I15', date('F',dDate::parseDateTime($m)).' '.date('Y',dDate::parseDateTime($m)));
		
		//Body List
		$sheet = $objPHPExcel->getActiveSheet();
		$styleArray = array('font' => array('bold' => true));
		$styleArrayAllborders = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
		
		$numberOfEmployee = count($arrData);
		$baseRow = 19;
		$lastvalue = 0;
		$fifty = 50; // wag tatanggalin ito dahil may weird effect, hindi gagana yung $baseRowPlus50 promise!
		$baseRowPlus50 = $baseRow + $fifty;
		
		if ($numberOfEmployee > 0) {
            foreach ($arrData as $key => $val) {
				$row = $baseRow + $key;
//				$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $key+1);
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $val['emp_info']['pi_hdmf']);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $key+1 .'. '. $val['emp_info']['pi_lname']);
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $val['emp_info']['pi_fname']);
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$row, $val['emp_info']['pi_mnamefull']);
				$MonthlyRate = $this->getHDMFContribution($gData['month'], $gData['year'], $val['emp_info']['emp_id'],1);
				$objPHPExcel->getActiveSheet()->setCellValue('G'.$row, $this->moneyFormat($MonthlyRate['emp_monthlysal']));
				$objPHPExcel->getActiveSheet()->setCellValue('H'.$row, $val['sss']['ppe_amount']);
				$sheet->getStyle('H'.$row)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
				$objPHPExcel->getActiveSheet()->setCellValue('I'.$row, $val['sss']['ppe_amount_employer']);
				$sheet->getStyle('I'.$row)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
				$objPHPExcel->getActiveSheet()->setCellValue('L'.$row, $val['sss']['total']);
				$sheet->getStyle('L'.$row)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
				$objPHPExcel->getActiveSheet()->setCellValue('M'.$row, $val['sss']['remarks']);
				$lastvalue++;
			}
			
			//Dynamic Body List
			$basePlusLast = $baseRow + $lastvalue;
			
			if ($numberOfEmployee < $fifty) {
				$style=$baseRowPlus50;
				$style1=$baseRowPlus50;
				$style2=$baseRowPlus50 + 1;
				$style4=$baseRowPlus50 + 6;
				$style6=$baseRowPlus50 + 7;
				$style7=$baseRowPlus50 + 8;
				$style8=$baseRowPlus50 + 2;
				$style9=$baseRowPlus50 + 3;
				$style10=$baseRowPlus50 + 4;
				$style11=$baseRowPlus50 + 11;
			} else {
				$style=$basePlusLast;
				$style1=$basePlusLast;
				$style2=$basePlusLast + 1;
				$style4=$basePlusLast + 6;
				$style6=$basePlusLast + 7;
				$style7=$basePlusLast + 8;
				$style8=$basePlusLast + 2;
				$style9=$basePlusLast + 3;
				$style10=$basePlusLast + 4;
				$style11=$basePlusLast + 11;
			}
			
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$style, '=SUM(H' . $baseRow . ':H' . $row . ')');
			$sheet->getStyle('H'.$style)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
			$sheet->getStyle('H'.$style)->getFont()->setSize(10);
			$sheet->getStyle('H'.$style)->applyFromArray($styleArray);
			$sheet->getStyle('H'.$style)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$style, '=SUM(I' . $baseRow . ':I' . $row . ')');
			$sheet->getStyle('I'.$style)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
			$sheet->getStyle('I'.$style)->getFont()->setSize(10);
			$sheet->getStyle('I'.$style)->applyFromArray($styleArray);
			$sheet->getStyle('I'.$style)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			
			$objPHPExcel->getActiveSheet()->setCellValue('L'.$style, '=SUM(L' . $baseRow . ':L' . $row . ')');
			$sheet->getStyle('L'.$style)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
			$sheet->getStyle('L'.$style)->getFont()->setSize(10);
			$sheet->getStyle('L'.$style)->applyFromArray($styleArray);
			$sheet->getStyle('L'.$style)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
//			$sheet->getStyle('J'.$style)->applyFromArray($styleArrayAllborders);
			
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$style1, 'No. of Employees');
			$sheet->getStyle('A'.$style1)->getFont()->setSize(8);
			$sheet->getStyle('A'.$style1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$style1, 'Total no. of Employees/');
			$sheet->getStyle('C'.$style1)->getFont()->setSize(8);
			$sheet->getStyle('C'.$style1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$style1, 'TOTAL FOR THIS PAGE');
			$sheet->getStyle('F'.$style1)->getFont()->setSize(8);
			$sheet->getStyle('F'.$style1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$style2, 'on this page');
			$sheet->getStyle('A'.$style2)->getFont()->setSize(8);
			$sheet->getStyle('A'.$style2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$style2, $key+1);
			$sheet->getStyle('B'.$style2)->getFont()->setSize(10);
			$sheet->getStyle('B'.$style)->applyFromArray($styleArray);
			$sheet->getStyle('B'.$style2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$style2, 'Members If last page');
			$sheet->getStyle('C'.$style2)->getFont()->setSize(8);
			$sheet->getStyle('C'.$style2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$style2, $key+1);
			$sheet->getStyle('E'.$style2)->getFont()->setSize(10);
			$sheet->getStyle('E'.$style)->applyFromArray($styleArray);
			$sheet->getStyle('E'.$style2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$style2, 'GRAND TOTAL (if last page)');
			$sheet->getStyle('F'.$style2)->getFont()->setSize(8);
			$sheet->getStyle('F'.$style2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			
			$grand = '=H'.$style;
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$style2, $grand);
			$sheet->getStyle('H'.$style2)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
			$sheet->getStyle('H'.$style2)->getFont()->setSize(10);
			$sheet->getStyle('H'.$style2)->applyFromArray($styleArray);
			$sheet->getStyle('H'.$style2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			
			$grand2 = '=I'.$style;
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$style2, $grand2);
			$sheet->getStyle('I'.$style2)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
			$sheet->getStyle('I'.$style2)->getFont()->setSize(10);
			$sheet->getStyle('I'.$style2)->applyFromArray($styleArray);
			$sheet->getStyle('I'.$style2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			
			$grand3 = '=L'.$style;
			$objPHPExcel->getActiveSheet()->setCellValue('L'.$style2, $grand3);
			$sheet->getStyle('L'.$style2)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
			$sheet->getStyle('L'.$style2)->getFont()->setSize(10);
			$sheet->getStyle('L'.$style2)->applyFromArray($styleArray);
			$sheet->getStyle('L'.$style2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			
			$objPHPExcel->getActiveSheet()->mergeCells('A'.$style8.':N'.$style8);
			
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$style8, 'EMPLOYER CERTIFICATION');
			$sheet->getStyle('A'.$style8)->getFont()->setSize(10);
			$sheet->getStyle('A'.$style8)->applyFromArray($styleArray);
			$sheet->getStyle('A'.$style8)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			
			$objPHPExcel->getActiveSheet()->unmergeCells('I'.$style4.':K'.$style4);
			$objPHPExcel->getActiveSheet()->unmergeCells('I'.$style9.':K'.$style9);
			$objPHPExcel->getActiveSheet()->unmergeCells('M'.$style4.':N'.$style4);
			$objPHPExcel->getActiveSheet()->unmergeCells('M'.$style9.':N'.$style9);
			
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$style9, 'I hereby certify under pain of perjury that the information given and all statements made herein are true and correct to the best of my knowledge and belief.');
			$sheet->getStyle('B'.$style9)->getFont()->setSize(10);
			$sheet->getStyle('B'.$style9)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$style10, 'I further certify that my signature appearing herein is genuine and authentic.');
			$sheet->getStyle('A'.$style10)->getFont()->setSize(10);
			$sheet->getStyle('A'.$style10)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$style4, $gData['signatoryby']);
			$sheet->getStyle('C'.$style4)->getFont()->setSize(10);
			$sheet->getStyle('C'.$style4)->applyFromArray($styleArray);
			$sheet->getStyle('C'.$style4)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$style4, $gData['position']);
			$sheet->getStyle('G'.$style4)->getFont()->setSize(10);
			$sheet->getStyle('G'.$style4)->applyFromArray($styleArray);
			$sheet->getStyle('G'.$style4)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			
			$objPHPExcel->getActiveSheet()->setCellValue('L'.$style4, date("F d, Y"));
			$sheet->getStyle('L'.$style4)->getFont()->setSize(10);
			$sheet->getStyle('L'.$style4)->applyFromArray($styleArray);
			$sheet->getStyle('L'.$style4)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$style6, 'HEAD OF OFFICE OR AUTHORIZED REPRESENTATIVE');
			$sheet->getStyle('C'.$style6)->getFont()->setSize(10);
			$sheet->getStyle('C'.$style6)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$style7, '(Signature Over Printed Name)');
			$sheet->getStyle('C'.$style7)->getFont()->setSize(9);
			$sheet->getStyle('C'.$style7)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$style6, 'DESIGNATION/POSITION');
			$sheet->getStyle('G'.$style6)->getFont()->setSize(10);
			$sheet->getStyle('G'.$style6)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			
			$objPHPExcel->getActiveSheet()->setCellValue('L'.$style6, 'DATE');
			$sheet->getStyle('L'.$style6)->getFont()->setSize(10);
			$sheet->getStyle('L'.$style6)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		}
		
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
    
	/**
	 * @note: This function is used to create pdf file for HDMF MCRF File.
	 * @param unknown_type $arrData
	 * @param unknown_type $gData
	 */
    
    function getPDFResultMCRF1($arrData = array(), $gData = array(), $comp_info = array(), $isLoc = false){
    	$orientation = 'P';
    	$unit = 'mm';
    	$format = 'A4';
    	$unicode = true;
    	$encoding = "UTF-8";
    	
    	$oPDF = new clsPDF($orientation, $unit, $format, $unicode, $encoding);
    	$oPDF->SetAutoPageBreak(false);
    	$oPDF->setPrintHeader(false);
		$oPDF->setPrintFooter(false);
		
		// get a modules list from the database
		$arrUserTypeList = $this->dbFetch();
		$objClsSSS = new clsSSS($this->conn);
    	IF($isLoc && ($gData['branchinfo_id'] != 0 || $gData['branchinfo_id'] != "N/A")){
				$branch_details = $objClsSSS->getLocationInfo($gData['branchinfo_id']);
		  		$company_name = $branch_details['branchinfo_name'];
	        	$company_address = $branch_details['branchinfo_add'];
	        	$compphicno = $branch_details['branchinfo_phic'];
	        	$comptinno = $branch_details['branchinfo_tin'];
	        	$comptelno = $branch_details['branchinfo_tel1'];
	        	$compemail = $branch_details['branchinfo_email'];
		  	} else {
		  		$branch_details = $objClsSSS->dbfetchCompDetails($gData['comp']);//get company info
	        	$company_name = $branch_details['comp_name'];
	        	$company_address = $branch_details['comp_add'];
	        	$compzip = $branch_details['comp_zipcode'];
	        	$compphicno = $branch_details['comp_phic'];
	        	$comptinno = $branch_details['comp_tin'];
	        	$comptelno = $branch_details['comp_tel'];
	        	$compemail = $branch_details['comp_email'];
		  	}
    	
    	
    	
		// set initial pdf page
		$oPDF->AddPage();
		
		// set initial coordinates
		$coordX = 0;
		$coordY = 0;
    	
    	//line style
    	$oPDF->SetLineStyle(array('width' => 1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
		$oPDF->SetTextColor(0,0,0);
		$oPDF->SetFont('helvetica','BI',0);
		$oPDF->SetFontSize(8.2);
		$oPDF->Text($coordX+4.5, $coordY+46, "NOTE: PLEASE READ INSTRUCTIONS AT THE BACK.", 0, false);
		$oPDF->SetFont('helvetica','I',0);
		$oPDF->SetFontSize(8);
		$oPDF->Text($coordX+157, $coordY+75.2, "(if abroad)", 0, false);
		$oPDF->SetFontSize(9);
		$oPDF->Text($coordX+173.3, $coordY+85.7, "(month/year)", 0, false);
		
		$oPDF->SetFont('helvetica','',10);
    	//set line format
    	$style1 = array('width' => .3, 'cap' => 'square', 'join' => 'round', 'dash' => '0', 'phase' => 0, 'color' => array(0, 0, 0));
    	$style = array('width' => 1, 'cap' => 'square', 'join' => 'round', 'dash' => '0', 'phase' => 0, 'color' => array(0, 0, 0));
    	
    	//$oPDF->Line($coordX+7, $coordX+5, $coordY+207, $coordY+5, $style1);
    	
    	//pag-ibig logo
    	$oPDF->Image(SYSCONFIG_CLASS_PATH."util/tcpdf/images/pag-ibig_logo.jpg", 7.4, 15.5, 21.2, 24);
    	
    	$oPDF->SetFont('helvetica','B',10);
    	$oPDF->SetFontSize(18);
		$oPDF->Text($coordX+31.5, $coordY+26.3, "MEMBER'S CONTRIBUTION", 0, false);
		$oPDF->Text($coordX+31.5, $coordY+34, "REMITTANCE FORM (MCRF)", 0, false);
		$oPDF->SetFont('helvetica','',10);
    	$oPDF->SetFontSize(9);
		$oPDF->Text($coordX+194, $coordY+16.5, "FPF060", 0, false);
		$oPDF->SetFont('helvetica','B',10);
		$oPDF->SetFontSize(7.5);
		$oPDF->Text($coordX+148, $coordY+25, "Pag-IBIG EMPLOYER'S ID NUMBER", 0, false);
		$oPDF->SetFont('helvetica','',10);
		$oPDF->SetFontSize(9);
		$oPDF->Text($coordX+148, $coordY+32, $rsResult->fields['comp_hdmf'], 0, false);
    	
		
		
		$oPDF->line($coordX+146, $coordY+22, $coordX+205.5, $coordY+22, $style1);
		$oPDF->line($coordX+205.5, $coordY+35.5, $coordX+205.5, $coordY+22, $style1);
		$oPDF->line($coordX+146, $coordY+35.5, $coordX+146, $coordY+22, $style1);
		$oPDF->line($coordX+146, $coordY+35.5, $coordX+205.5, $coordY+35.5, $style1);
		
		$oPDF->SetFillColor(197, 197, 197);
		$oPDF->MultiCell(59.5, 2, '', 'LRB', 'C', 1, 1, 146, 22, true, 0, false, false, 1);
		$oPDF->SetFillColor(255, 255, 255);
		$oPDF->MultiCell(59.5, 5, '', 'T', 'C', 1, 1, 146, 26, true, 0, false, false, 1);
		
		//START LINE AND END LINE
		$oPDF->line($coordX+4.7, $coordY+47.5, $coordX+4.7, $coordY+266, $style1);
		$oPDF->line($coordX+203.5, $coordY+47.5, $coordX+203.5, $coordY+266, $style1);
		$oPDF->line($coordX+4.7, $coordY+266.2, $coordX+203.5, $coordY+266.2, $style1);
		
		//BEGIN OF ROW1	
		$oPDF->line($coordX+4.7, $coordY+47.5, $coordX+203.5, $coordY+47.5, $style1);
		$oPDF->SetFontSize(9);
		$oPDF->Text($coordX+6.8, $coordY+50.7, "EMPLOYER/BUSINESS NAME", 0, false);
		$oPDF->Text($coordX+144.6, $coordY+50.7, "BRANCH/OFFICE", 0, false);
		//---company name-------
		$oPDF->Text($coordX+6.8, $coordY+55, $company_name);
		$oPDF->line($coordX+4.7, $coordY+56.3, $coordX+203.5, $coordY+56.3, $style1);
		$oPDF->line($coordX+142.6, $coordY+47.5, $coordX+142.6, $coordY+56.3, $style1);
		//row 2
		$oPDF->line($coordX+4.7, $coordY+71.5, $coordX+203.5, $coordY+71.5, $style1);
		$oPDF->SetFontSize(9);
		$oPDF->Text($coordX+6.8, $coordY+59.6, "EMPLOYER/BUSINESS ADDRESS", 0, false);
		$oPDF->SetFontSize(7.5);
		$oPDF->Text($coordX+5.8, $coordY+64.4, "Unit/Room No., Floor", 0, false);
		$oPDF->Text($coordX+46.6, $coordY+64.4, "Building Name", 0, false);
		$oPDF->Text($coordX+74.1, $coordY+64.4, "Lot No., Block No., Phase No. House No.", 0, false);
		$oPDF->line($coordX+142.6, $coordY+56.3, $coordX+142.6, $coordY+71.5, $style1);
		
		$oPDF->SetFontSize(9);
		$oPDF->Text($coordX+144.6, $coordY+59.6, "TYPE OF EMPLOYER", 0, false);
		$oPDF->line($coordX+147.5, $coordY+61.8, $coordX+150.9, $coordY+61.8, $style1);
		$oPDF->line($coordX+147.5, $coordY+62, $coordX+147.5, $coordY+65.7, $style1);
		$oPDF->line($coordX+150.9, $coordY+62, $coordX+150.9, $coordY+65.7, $style1);
		$oPDF->line($coordX+147.5, $coordY+65.7, $coordX+150.9, $coordY+65.7, $style1);
		//cross
		$oPDF->SetFontSize(7);
		$oPDF->text($coordX+148.3, $coordY+64.5, "X");
		
		$oPDF->SetFontSize(7.5);
		$oPDF->Text($coordX+151.8, $coordY+64.7, "Private", 0, false);
		$oPDF->line($coordX+179.5, $coordY+61.8, $coordX+182.9, $coordY+61.8, $style1);
		$oPDF->line($coordX+179.5, $coordY+62, $coordX+179.5, $coordY+65.7, $style1);
		$oPDF->line($coordX+182.9, $coordY+62, $coordX+182.9, $coordY+65.7, $style1);
		$oPDF->line($coordX+179.5, $coordY+65.7, $coordX+182.9, $coordY+65.7, $style1);
		$oPDF->SetFontSize(7.5);
		$oPDF->Text($coordX+183.5, $coordY+64.7, "Household", 0, false);
		$oPDF->line($coordX+147.5, $coordY+66.5, $coordX+150.9, $coordY+66.5, $style1);
		$oPDF->line($coordX+147.5, $coordY+66.5, $coordX+147.5, $coordY+70.4, $style1);
		$oPDF->line($coordX+150.9, $coordY+66.5, $coordX+150.9, $coordY+70.4, $style1);
		$oPDF->line($coordX+147.5, $coordY+70.7, $coordX+150.9, $coordY+70.7, $style1);
		$oPDF->SetFontSize(7.5);
		$oPDF->Text($coordX+151.8, $coordY+69.5, "Government", 0, false);
		
		//row 3
		$oPDF->line($coordX+4.7, $coordY+82.3, $coordX+203.5, $coordY+82.3, $style1);
		$oPDF->SetFontSize(7.5);
		$oPDF->Text($coordX+5.8, $coordY+75.2, "Street Name", 0, false);
		$oPDF->Text($coordX+36, $coordY+75.2, "Subdivision", 0, false);
		$oPDF->Text($coordX+66.2, $coordY+75.2, "Barangay", 0, false);
		$oPDF->Text($coordX+96.3, $coordY+75.2, "Municipality/City", 0, false);
		$oPDF->Text($coordX+129.1, $coordY+75.2, "Province/State/Country", 0, false);
		$oPDF->Text($coordX+175, $coordY+75.2, "ZIP Code", 0, false);
		$oPDF->SetFontSize(7.3);
		$oPDF->Text($coordX+5.8, $coordY+80.5, $company_address);
		
		//row 4
		$oPDF->line($coordX+4.7, $coordY+93.5, $coordX+203.5, $coordY+93.5, $style1);
		$oPDF->SetFontSize(9);
		$oPDF->Text($coordX+6.8, $coordY+85.7, "MEMBERSHIP PROGRAM", 0, false);
		$oPDF->line($coordX+5.8, $coordY+87.8, $coordX+9.1, $coordY+87.8, $style1);
		$oPDF->line($coordX+5.8, $coordY+87.8, $coordX+5.8, $coordY+92.4, $style1);
		$oPDF->line($coordX+9.1, $coordY+87.8, $coordX+9.1, $coordY+92.4, $style1);
		$oPDF->line($coordX+5.8, $coordY+92.4, $coordX+9.1, $coordY+92.4, $style1);
		//cross
		$oPDF->SetFontSize(7.5);
		$oPDF->text($coordX+6.5, $coordY+91, "X");
		
		$oPDF->Text($coordX+10, $coordY+91.1, "Pag-IBIG I", 0, false);
		$oPDF->line($coordX+42.6, $coordY+87.8, $coordX+46.3, $coordY+87.8, $style1);
		$oPDF->line($coordX+42.6, $coordY+87.8, $coordX+42.6, $coordY+92.4, $style1);
		$oPDF->line($coordX+46.3, $coordY+87.8, $coordX+46.3, $coordY+92.4, $style1);
		$oPDF->line($coordX+42.6, $coordY+92.4, $coordX+46.3, $coordY+92.4, $style1);
		$oPDF->Text($coordX+47.1, $coordY+91.1, "Pag-IBIG II", 0, false);
		$oPDF->line($coordX+79.7, $coordY+87.8, $coordX+83.1, $coordY+87.8, $style1);
		$oPDF->line($coordX+79.7, $coordY+87.8, $coordX+79.7, $coordY+92.4, $style1);
		$oPDF->line($coordX+83.1, $coordY+87.8, $coordX+83.1, $coordY+92.4, $style1);
		$oPDF->line($coordX+79.7, $coordY+92.4, $coordX+83.1, $coordY+92.4, $style1);
		$oPDF->Text($coordX+84.1, $coordY+91.1, "Modified Pag-IBIG II", 0, false);
		
		$oPDF->line($coordX+142.6, $coordY+82.3, $coordX+142.6, $coordY+93.5, $style1);
		$oPDF->SetFontSize(9);
		$oPDF->Text($coordX+144.6, $coordY+85.7, "PERIOD COVERED", 0, false);
		$m = $gData['year'].'-'.$gData['month'].'-'.'10';
		//month and year
		$oPDF->Text($coordX+164.4, $coordY+91.1, date('F',dDate::parseDateTime($m)));
		$oPDF->Text($coordX+176.5, $coordY+91.1, date('Y',dDate::parseDateTime($m)));
		
		//row 5
		$oPDF->line($coordX+4.7, $coordY+105.8, $coordX+203.5, $coordY+105.8, $style1);
		$oPDF->SetFont('helvetica','B',10);
		$oPDF->SetFontSize(7.5);
		$oPDF->Text($coordX+6.4, $coordY+98.2, "Pag-IBIG MID No.", 0, false);
		$oPDF->Text($coordX+51.4, $coordY+98.2, "NAME OF MEMBERS", 0, false);
		$oPDF->Text($coordX+102.6, $coordY+98.2, "ACCOUNT NO.", 0, false);
		$oPDF->Text($coordX+128.5, $coordY+96.5, "MONTHLY", 0, false);
		$oPDF->Text($coordX+124.6, $coordY+100, "COMPENSATION", 0, false);
		$oPDF->Text($coordX+153, $coordY+98.2, "CONTRIBUTIONS", 0, false);
		$oPDF->Text($coordX+185.8, $coordY+98.2, "REMARKS", 0, false);
		$oPDF->SetFont('helvetica','I',0);
		$oPDF->SetFontSize(5.5);
		$oPDF->Text($coordX+29.7, $coordY+104, "Last Name", 0, false);
		$oPDF->Text($coordX+47.5, $coordY+102.8, "First Name", 0, false);
		$oPDF->Text($coordX+58, $coordY+102.8, "Name Extension", 0, false);
		$oPDF->Text($coordX+60.1, $coordY+105, "(Jr., II, etc.)", 0, false);
		$oPDF->Text($coordX+82, $coordY+104, "Middle Name", 0, false);
		$oPDF->line($coordX+146.6, $coordY+100.8, $coordX+181.5, $coordY+100.8, $style1);
		$oPDF->SetFont('helvetica','B',0);
		$oPDF->SetFontSize(5.3);
		$oPDF->Text($coordX+147, $coordY+102.8, "EMPLOYEE", 0, false);
		$oPDF->Text($coordX+158.5, $coordY+102.8, "EMPLOYER", 0, false);
		$oPDF->Text($coordX+149.1, $coordY+105.1, "SHARE", 0, false);
		$oPDF->Text($coordX+160.7, $coordY+105.1, "SHARE", 0, false);
		$oPDF->Text($coordX+172.7, $coordY+104, "TOTAL", 0, false);
		
		// 7 LINES FOR COLUMNS
		
		$oPDF->line($coordX+29.5, $coordY+93.5, $coordX+29.5, $coordY+222.7, $style1);
		$oPDF->line($coordX+99.8, $coordY+93.5, $coordX+99.8, $coordY+232.2, $style1);
		$oPDF->line($coordX+124.3, $coordY+93.5, $coordX+124.3, $coordY+222.7, $style1);
		$oPDF->line($coordX+146.5, $coordY+93.5, $coordX+146.5, $coordY+232.2, $style1);
		$oPDF->line($coordX+158.1, $coordY+100.8, $coordX+158.1, $coordY+232.2, $style1);
		$oPDF->line($coordX+169.9, $coordY+100.8, $coordX+169.9, $coordY+232.2, $style1);
		$oPDF->line($coordX+181.5, $coordY+93.5, $coordX+181.5, $coordY+222.7, $style1);
		
		//TEXTBOX (31 lines)
		$oPDF->line($coordX+4.7, $coordY+109.8, $coordX+203.5, $coordY+109.8, $style1);
		$oPDF->line($coordX+4.7, $coordY+113.7, $coordX+203.5, $coordY+113.7, $style1);
		$oPDF->line($coordX+4.7, $coordY+117.6, $coordX+203.5, $coordY+117.6, $style1);
		$oPDF->line($coordX+4.7, $coordY+121.5, $coordX+203.5, $coordY+121.5, $style1);
		$oPDF->line($coordX+4.7, $coordY+125.4, $coordX+203.5, $coordY+125.4, $style1);
		$oPDF->line($coordX+4.7, $coordY+129.3, $coordX+203.5, $coordY+129.3, $style1);
		$oPDF->line($coordX+4.7, $coordY+133.2, $coordX+203.5, $coordY+133.2, $style1);
		$oPDF->line($coordX+4.7, $coordY+137.1, $coordX+203.5, $coordY+137.1, $style1);
		$oPDF->line($coordX+4.7, $coordY+141.0, $coordX+203.5, $coordY+141.0, $style1);
		$oPDF->line($coordX+4.7, $coordY+144.9, $coordX+203.5, $coordY+144.9, $style1);
		$oPDF->line($coordX+4.7, $coordY+148.8, $coordX+203.5, $coordY+148.8, $style1);
		$oPDF->line($coordX+4.7, $coordY+152.7, $coordX+203.5, $coordY+152.7, $style1);
		$oPDF->line($coordX+4.7, $coordY+156.6, $coordX+203.5, $coordY+156.6, $style1);
		$oPDF->line($coordX+4.7, $coordY+160.5, $coordX+203.5, $coordY+160.5, $style1);
		$oPDF->line($coordX+4.7, $coordY+164.4, $coordX+203.5, $coordY+164.4, $style1);
		$oPDF->line($coordX+4.7, $coordY+168.3, $coordX+203.5, $coordY+168.3, $style1);
		$oPDF->line($coordX+4.7, $coordY+172.0, $coordX+203.5, $coordY+172.0, $style1);
		$oPDF->line($coordX+4.7, $coordY+175.9, $coordX+203.5, $coordY+175.9, $style1);
		$oPDF->line($coordX+4.7, $coordY+179.8, $coordX+203.5, $coordY+179.8, $style1);
		$oPDF->line($coordX+4.7, $coordY+183.7, $coordX+203.5, $coordY+183.7, $style1);
		$oPDF->line($coordX+4.7, $coordY+187.6, $coordX+203.5, $coordY+187.6, $style1);
		$oPDF->line($coordX+4.7, $coordY+191.5, $coordX+203.5, $coordY+191.5, $style1);
		$oPDF->line($coordX+4.7, $coordY+195.4, $coordX+203.5, $coordY+195.4, $style1);
		$oPDF->line($coordX+4.7, $coordY+199.2, $coordX+203.4, $coordY+199.2, $style1);
		$oPDF->line($coordX+4.7, $coordY+203.2, $coordX+203.5, $coordY+203.2, $style1);
		$oPDF->line($coordX+4.7, $coordY+207.1, $coordX+203.5, $coordY+207.1, $style1);
		$oPDF->line($coordX+4.7, $coordY+211, $coordX+203.5, $coordY+211, $style1);
		$oPDF->line($coordX+4.7, $coordY+214.9, $coordX+203.5, $coordY+214.9, $style1);
		$oPDF->line($coordX+4.7, $coordY+218.8, $coordX+203.5, $coordY+218.8, $style1);
		$oPDF->line($coordX+4.7, $coordY+222.7, $coordX+203.5, $coordY+222.7, $style1);
		
		
		//row 6
		$oPDF->line($coordX+4.7, $coordY+232.2, $coordX+203.5, $coordY+232.2, $style1);
		$oPDF->SetFont('helvetica','',0);
		$oPDF->SetFontSize(6.8);
		$oPDF->Text($coordX+5.6, $coordY+226.5, "No. of Employees/", 0, false);
		$oPDF->Text($coordX+5.6, $coordY+229.3, "Members on this page", 0, false);
		$oPDF->line($coordX+32.4, $coordY+222.7, $coordX+32.4, $coordY+232.2, $style1);
		$oPDF->Text($coordX+47.7, $coordY+226.5, "Total no. of Employees/", 0, false);
		$oPDF->Text($coordX+47.7, $coordY+229.3, "Members if last page", 0, false);
		$oPDF->line($coordX+47.3, $coordY+222.7, $coordX+47.3, $coordY+232.2, $style1);
		$oPDF->line($coordX+74.7, $coordY+222.7, $coordX+74.7, $coordY+232.2, $style1);
		$oPDF->SetFontSize(8);
		$oPDF->Text($coordX+100.1, $coordY+226, "TOTAL FOR THIS PAGE", 0, false);
		$oPDF->SetFontSize(6.5);
		
		$oPDF->Text($coordX+100.1, $coordY+230.5, "GRAND TOTAL(if last page)", 0, false);
		$oPDF->line($coordX+99.8, $coordY+227.5, $coordX+203.5, $coordY+227.5, $style1);
		
		//row 7
		$oPDF->SetFillColor(197, 197, 197);
		$oPDF->MultiCell(198.9, 4, '', 'LRB', 'C', 1, 1, 4.7, 232, true, 0, false, false, 1);
		$oPDF->SetFont('helvetica','B',0);
		$oPDF->SetFontSize(8);
		$oPDF->Text($coordX+85, $coordY+235, "EMPLOYEE CERTIFICATION", 0, false);

		//row 8
		$oPDF->SetFont('helvetica','',0);
		$oPDF->SetFontSize(7.9);
		$oPDF->Text($coordX+10, $coordY+240, "I hereby certify under pain of perjury that the information given and all statements made herein are true and correct to the best of my knowledge and belief. I", 0, false);
		$oPDF->Text($coordX+6.5, $coordY+242.8, "further certify that my signature appearing herein is genuine and authentic.", 0, false);
		$oPDF->line($coordX+6, $coordY+257.4, $coordX+97, $coordY+257.4, $style1);
		
		//signatory
		$oPDF->Text($coordX+40, $coordY+256.5, $gData['signatoryby']);
		//designation position
		$oPDF->Text($coordX+120, $coordY+256.5, $gData['position']);
		//date
		$oPDF->Text($coordX+169, $coordY+256.5, date("F d, Y"));
		
		$oPDF->Text($coordX+15, $coordY+260.5, "HEAD OF OFFICE OR AUTHORIZED REPRESENTATIVE", 0, false);
		$oPDF->line($coordX+101, $coordY+257.4, $coordX+156, $coordY+257.4, $style1);
		$oPDF->Text($coordX+112, $coordY+260.5, "DESIGNATION/POSITION", 0, false);
		$oPDF->line($coordX+159, $coordY+257.4, $coordX+201, $coordY+257.4, $style1);
		$oPDF->Text($coordX+177, $coordY+260.5, "DATE", 0, false);
		$oPDF->SetFont('helvetica','I',0);
		$oPDF->SetFontSize(7.9);
		$oPDF->Text($coordX+33, $coordY+264, "(Signature Over Printed Name)", 0, false);
		
		$oPDF->SetFont('helvetica','B',0);
		$oPDF->SetFontSize(9);
		$oPDF->Text($coordX+66, $coordY+270, "THIS FORM MAY BE REPRODUCED. NOT FOR SALE", 0, false);
		$oPDF->SetFont('helvetica','i',0);
		$oPDF->SetFontSize(5);
		$oPDF->Text($coordX+187, $coordY+270, "(Revised 03/2011)", 0, false);
		
		
		
		
		
		///------------------EMPLOYEE INPUT--------------------------------
		$countemp = count($arrData);
		$coordX = 7;
		$coordY = 105;
		$x=0;
		$y=0;
		
		
		
		$sql = "SELECT distinct em.emp_id,e.pi_hdmf,e.pi_lname,e.pi_fname,e.pi_mname,em.emp_hiredate,em.emp_resigndate,pps.paystub_start_date,pps.paystub_end_date, DATE_FORMAT(e.pi_bdate,'%m%d%Y') as bday 
				FROM emp_masterfile em
				JOIN emp_personal_info e on (e.pi_id=em.pi_id)
				JOIN payroll_paystub_report ppr on (ppr.emp_id=em.emp_id)
				JOIN payroll_pay_stub pps on(pps.payperiod_id=ppr.payperiod_id)
				JOIN payroll_pay_period ppp on (ppp.payperiod_id=pps.payperiod_id)
				$criteria
				GROUP BY em.emp_id ORDER BY e.pi_lname ASC";
		$size = mysql_num_rows(mysql_query($sql));
		$rsResult1 = $this->conn->Execute($sql);
    	
		
		$empcount=1;
		$totalcount=1;
		for ($x=0;$x<$countemp;$x++){
			
				if (x<29) {
					$HDMFContribution = $this->getHDMFContribution($gData['month'],$gData['year'],$arrData[$x]['emp_info']['emp_id']);
					$Emp_MONTHLYSAL = $this->getHDMFContribution($gData['month'], $gData['year'], $arrData[$x]['emp_info']['emp_id'],1);
					$TotalHDMFContribution = $HDMFContribution['employee_share'] + $HDMFContribution['employer_share'];
		
					$pageTotalHDMFContribution += $TotalHDMFContribution;
					$pageTotalEmployeeContribution += $HDMFContribution['employee_share'];
					$pageTotalEmployerContribution += $HDMFContribution['employer_share'];
					$grandTotalEmployee += $HDMFContribution['employee_share'];
					$grandTotalEmployer += $HDMFContribution['employer_share'];
					$grandTotalHDMFContribution += $TotalHDMFContribution;
					
					
		
		$oPDF->SetFont('helvetica','',7.5);
		$coordY = $coordY;
		//$oPDF->Text($coordX, $coordY+4, $rsResult1->fields['pi_hdmf']);
		//$oPDF->Text($coordX+23, $coordY+4, $rsResult1->fields['pi_lname']);
		//$oPDF->Text($coordX+42, $coordY+4, $rsResult1->fields['pi_fname']);
		//$oPDF->Text($coordX+75, $coordY+4, $rsResult1->fields['pi_mname']);
		$oPDF->Text($coordX, $coordY+4, $arrData[$x]['emp_info']['pi_hdmf']);
		$oPDF->Text($coordX+23, $coordY+4, $arrData[$x]['emp_info']['pi_lname']);
		$oPDF->Text($coordX+42, $coordY+4, $arrData[$x]['emp_info']['pi_fname']);
		$oPDF->Text($coordX+75, $coordY+4, $arrData[$x]['emp_info']['pi_mname']);
		$oPDF->Text($coordX+126, $coordY+4, $this->moneyFormat($Emp_MONTHLYSAL['emp_monthlysal']));
		$oPDF->Text($coordX+142, $coordY+4, $this->moneyFormat($HDMFContribution['employee_share']));
		$oPDF->Text($coordX+153.5, $coordY+4, $this->moneyFormat($HDMFContribution['employer_share']));
		$oPDF->Text($coordX+165.5, $coordY+4, $this->moneyFormat($TotalHDMFContribution));
		$oPDF->SetFont('helvetica','',14);

		$coordY += 3.9;
		$y++;
			if(($empcount%30)==0 || $empcount==$countemp){
				$coordX=0;
				$coordY=0;
				$oPDF->SetFontSize(6);
				$oPDF->Text($coordX+147, $coordY+226, "P".$this->moneyFormat($pageTotalEmployeeContribution));
				$oPDF->Text($coordX+158.5, $coordY+226, "P".$this->moneyFormat($pageTotalEmployerContribution));
				$oPDF->Text($coordX+170.5, $coordY+226, "P".$this->moneyFormat($pageTotalHDMFContribution));
				$pageTotalHDMFContribution = 0;
				$pageTotalEmployeeContribution = 0;
				$pageTotalEmployerContribution = 0;
				
				$oPDF->SetFont('helvetica','',14);
				IF($empcount==$countemp){
					$oPDF->Text(84, 229, $countemp);
				}
				$oPDF->Text(37, 229, $totalcount);
				
				IF($empcount!=$countemp){
					$oPDF->AddPage();
				}
		    	
		    	//line style
		    	$oPDF->SetLineStyle(array('width' => 1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
				$oPDF->SetTextColor(0,0,0);
				$oPDF->SetFont('helvetica','BI',0);
				$oPDF->SetFontSize(8.2);
				$oPDF->Text($coordX+4.5, $coordY+46, "NOTE: PLEASE READ INSTRUCTIONS AT THE BACK.", 0, false);
				$oPDF->SetFont('helvetica','I',0);
				$oPDF->SetFontSize(8);
				$oPDF->Text($coordX+157, $coordY+75.2, "(if abroad)", 0, false);
				$oPDF->SetFontSize(9);
				$oPDF->Text($coordX+173.3, $coordY+85.7, "(month/year)", 0, false);
				
				$oPDF->SetFont('helvetica','',10);
		    	//set line format
		    	$style1 = array('width' => .3, 'cap' => 'square', 'join' => 'round', 'dash' => '0', 'phase' => 0, 'color' => array(0, 0, 0));
		    	$style = array('width' => 1, 'cap' => 'square', 'join' => 'round', 'dash' => '0', 'phase' => 0, 'color' => array(0, 0, 0));
		    	
		    	//$oPDF->Line($coordX+7, $coordX+5, $coordY+207, $coordY+5, $style1);
		    	
		    	//pag-ibig logo
		    	$oPDF->Image(SYSCONFIG_CLASS_PATH."util/tcpdf/images/pag-ibig_logo.jpg", 7.4, 15.5, 21.2, 24);
		    	
				$oPDF->SetFont('helvetica','B',10);
		    	$oPDF->SetFontSize(18);
				$oPDF->Text($coordX+31.5, $coordY+26.3, "MEMBER'S CONTRIBUTION", 0, false);
				$oPDF->Text($coordX+31.5, $coordY+34, "REMITTANCE FORM (MCRF)", 0, false);
				$oPDF->SetFont('helvetica','',10);
		    	$oPDF->SetFontSize(9);
				$oPDF->Text($coordX+194, $coordY+16.5, "FPF060", 0, false);
				$oPDF->SetFont('helvetica','B',10);
				$oPDF->SetFontSize(7.5);
				$oPDF->Text($coordX+148, $coordY+25, "Pag-IBIG EMPLOYER'S ID NUMBER", 0, false);
				$oPDF->SetFont('helvetica','',10);
				$oPDF->SetFontSize(9);
				$oPDF->Text($coordX+148, $coordY+32, $rsResult->fields['comp_hdmf'], 0, false);
		    	
				
				
				$oPDF->line($coordX+146, $coordY+22, $coordX+205.5, $coordY+22, $style1);
				$oPDF->line($coordX+205.5, $coordY+35.5, $coordX+205.5, $coordY+22, $style1);
				$oPDF->line($coordX+146, $coordY+35.5, $coordX+146, $coordY+22, $style1);
				$oPDF->line($coordX+146, $coordY+35.5, $coordX+205.5, $coordY+35.5, $style1);
				
				$oPDF->SetFillColor(197, 197, 197);
				$oPDF->MultiCell(59.5, 2, '', 'LRB', 'C', 1, 1, 146, 22, true, 0, false, false, 1);
				$oPDF->SetFillColor(255, 255, 255);
				$oPDF->MultiCell(59.5, 5, '', 'T', 'C', 1, 1, 146, 26, true, 0, false, false, 1);
				
				//START LINE AND END LINE
				$oPDF->line($coordX+4.7, $coordY+47.5, $coordX+4.7, $coordY+266, $style1);
				$oPDF->line($coordX+203.5, $coordY+47.5, $coordX+203.5, $coordY+266, $style1);
				$oPDF->line($coordX+4.7, $coordY+266.2, $coordX+203.5, $coordY+266.2, $style1);
				
				//BEGIN OF ROW1	
				$oPDF->line($coordX+4.7, $coordY+47.5, $coordX+203.5, $coordY+47.5, $style1);
				$oPDF->SetFontSize(9);
				$oPDF->Text($coordX+6.8, $coordY+50.7, "EMPLOYER/BUSINESS NAME", 0, false);
				$oPDF->Text($coordX+144.6, $coordY+50.7, "BRANCH/OFFICE", 0, false);
				//---company name-------
				$oPDF->Text($coordX+6.8, $coordY+55, $company_name);
				$oPDF->line($coordX+4.7, $coordY+56.3, $coordX+203.5, $coordY+56.3, $style1);
				$oPDF->line($coordX+142.6, $coordY+47.5, $coordX+142.6, $coordY+56.3, $style1);
				//row 2
				$oPDF->line($coordX+4.7, $coordY+71.5, $coordX+203.5, $coordY+71.5, $style1);
				$oPDF->SetFontSize(9);
				$oPDF->Text($coordX+6.8, $coordY+59.6, "EMPLOYER/BUSINESS ADDRESS", 0, false);
				$oPDF->SetFontSize(7.5);
				$oPDF->Text($coordX+5.8, $coordY+64.4, "Unit/Room No., Floor", 0, false);
				$oPDF->Text($coordX+46.6, $coordY+64.4, "Building Name", 0, false);
				$oPDF->Text($coordX+74.1, $coordY+64.4, "Lot No., Block No., Phase No. House No.", 0, false);
				$oPDF->line($coordX+142.6, $coordY+56.3, $coordX+142.6, $coordY+71.5, $style1);
				
				$oPDF->SetFontSize(9);
				$oPDF->Text($coordX+144.6, $coordY+59.6, "TYPE OF EMPLOYER", 0, false);
				$oPDF->line($coordX+147.5, $coordY+61.8, $coordX+150.9, $coordY+61.8, $style1);
				$oPDF->line($coordX+147.5, $coordY+62, $coordX+147.5, $coordY+65.7, $style1);
				$oPDF->line($coordX+150.9, $coordY+62, $coordX+150.9, $coordY+65.7, $style1);
				$oPDF->line($coordX+147.5, $coordY+65.7, $coordX+150.9, $coordY+65.7, $style1);
				//cross
				$oPDF->SetFontSize(7);
				$oPDF->text($coordX+148.3, $coordY+64.5, "X");
				
				$oPDF->SetFontSize(7.5);
				$oPDF->Text($coordX+151.8, $coordY+64.7, "Private", 0, false);
				$oPDF->line($coordX+179.5, $coordY+61.8, $coordX+182.9, $coordY+61.8, $style1);
				$oPDF->line($coordX+179.5, $coordY+62, $coordX+179.5, $coordY+65.7, $style1);
				$oPDF->line($coordX+182.9, $coordY+62, $coordX+182.9, $coordY+65.7, $style1);
				$oPDF->line($coordX+179.5, $coordY+65.7, $coordX+182.9, $coordY+65.7, $style1);
				$oPDF->SetFontSize(7.5);
				$oPDF->Text($coordX+183.5, $coordY+64.7, "Household", 0, false);
				$oPDF->line($coordX+147.5, $coordY+66.5, $coordX+150.9, $coordY+66.5, $style1);
				$oPDF->line($coordX+147.5, $coordY+66.5, $coordX+147.5, $coordY+70.4, $style1);
				$oPDF->line($coordX+150.9, $coordY+66.5, $coordX+150.9, $coordY+70.4, $style1);
				$oPDF->line($coordX+147.5, $coordY+70.7, $coordX+150.9, $coordY+70.7, $style1);
				$oPDF->SetFontSize(7.5);
				$oPDF->Text($coordX+151.8, $coordY+69.5, "Government", 0, false);
				
				//row 3
				$oPDF->line($coordX+4.7, $coordY+82.3, $coordX+203.5, $coordY+82.3, $style1);
				$oPDF->SetFontSize(7.5);
				$oPDF->Text($coordX+5.8, $coordY+75.2, "Street Name", 0, false);
				$oPDF->Text($coordX+36, $coordY+75.2, "Subdivision", 0, false);
				$oPDF->Text($coordX+66.2, $coordY+75.2, "Barangay", 0, false);
				$oPDF->Text($coordX+96.3, $coordY+75.2, "Municipality/City", 0, false);
				$oPDF->Text($coordX+129.1, $coordY+75.2, "Province/State/Country", 0, false);
				$oPDF->Text($coordX+175, $coordY+75.2, "ZIP Code", 0, false);
				$oPDF->SetFontSize(7.3);
				$oPDF->Text($coordX+5.8, $coordY+80.5, $company_address);
				
				//row 4
				$oPDF->line($coordX+4.7, $coordY+93.5, $coordX+203.5, $coordY+93.5, $style1);
				$oPDF->SetFontSize(9);
				$oPDF->Text($coordX+6.8, $coordY+85.7, "MEMBERSHIP PROGRAM", 0, false);
				$oPDF->line($coordX+5.8, $coordY+87.8, $coordX+9.1, $coordY+87.8, $style1);
				$oPDF->line($coordX+5.8, $coordY+87.8, $coordX+5.8, $coordY+92.4, $style1);
				$oPDF->line($coordX+9.1, $coordY+87.8, $coordX+9.1, $coordY+92.4, $style1);
				$oPDF->line($coordX+5.8, $coordY+92.4, $coordX+9.1, $coordY+92.4, $style1);
				//cross
				$oPDF->SetFontSize(7.5);
				$oPDF->text($coordX+6.5, $coordY+91, "X");
				
				$oPDF->Text($coordX+10, $coordY+91.1, "Pag-IBIG I", 0, false);
				$oPDF->line($coordX+42.6, $coordY+87.8, $coordX+46.3, $coordY+87.8, $style1);
				$oPDF->line($coordX+42.6, $coordY+87.8, $coordX+42.6, $coordY+92.4, $style1);
				$oPDF->line($coordX+46.3, $coordY+87.8, $coordX+46.3, $coordY+92.4, $style1);
				$oPDF->line($coordX+42.6, $coordY+92.4, $coordX+46.3, $coordY+92.4, $style1);
				$oPDF->Text($coordX+47.1, $coordY+91.1, "Pag-IBIG II", 0, false);
				$oPDF->line($coordX+79.7, $coordY+87.8, $coordX+83.1, $coordY+87.8, $style1);
				$oPDF->line($coordX+79.7, $coordY+87.8, $coordX+79.7, $coordY+92.4, $style1);
				$oPDF->line($coordX+83.1, $coordY+87.8, $coordX+83.1, $coordY+92.4, $style1);
				$oPDF->line($coordX+79.7, $coordY+92.4, $coordX+83.1, $coordY+92.4, $style1);
				$oPDF->Text($coordX+84.1, $coordY+91.1, "Modified Pag-IBIG II", 0, false);
				
				$oPDF->line($coordX+142.6, $coordY+82.3, $coordX+142.6, $coordY+93.5, $style1);
				$oPDF->SetFontSize(9);
				$oPDF->Text($coordX+144.6, $coordY+85.7, "PERIOD COVERED", 0, false);
				$m = $gData['year'].'-'.$gData['month'].'-'.'10';
				//month and year
				$oPDF->Text($coordX+164.4, $coordY+91.1, date('F',dDate::parseDateTime($m)));
				$oPDF->Text($coordX+176.5, $coordY+91.1, date('Y',dDate::parseDateTime($m)));
				
				//row 5
				$oPDF->line($coordX+4.7, $coordY+105.8, $coordX+203.5, $coordY+105.8, $style1);
				$oPDF->SetFont('helvetica','B',10);
				$oPDF->SetFontSize(7.5);
				$oPDF->Text($coordX+6.4, $coordY+98.2, "Pag-IBIG MID No.", 0, false);
				$oPDF->Text($coordX+51.4, $coordY+98.2, "NAME OF MEMBERS", 0, false);
				$oPDF->Text($coordX+102.6, $coordY+98.2, "ACCOUNT NO.", 0, false);
				$oPDF->Text($coordX+128.5, $coordY+96.5, "MONTHLY", 0, false);
				$oPDF->Text($coordX+124.6, $coordY+100, "COMPENSATION", 0, false);
				$oPDF->Text($coordX+153, $coordY+98.2, "CONTRIBUTIONS", 0, false);
				$oPDF->Text($coordX+185.8, $coordY+98.2, "REMARKS", 0, false);
				$oPDF->SetFont('helvetica','I',0);
				$oPDF->SetFontSize(5.5);
				$oPDF->Text($coordX+29.7, $coordY+104, "Last Name", 0, false);
				$oPDF->Text($coordX+47.5, $coordY+102.8, "First Name", 0, false);
				$oPDF->Text($coordX+58, $coordY+102.8, "Name Extension", 0, false);
				$oPDF->Text($coordX+60.1, $coordY+105, "(Jr., II, etc.)", 0, false);
				$oPDF->Text($coordX+82, $coordY+104, "Middle Name", 0, false);
				$oPDF->line($coordX+146.6, $coordY+100.8, $coordX+181.5, $coordY+100.8, $style1);
				$oPDF->SetFont('helvetica','B',0);
				$oPDF->SetFontSize(5.3);
				$oPDF->Text($coordX+147, $coordY+102.8, "EMPLOYEE", 0, false);
				$oPDF->Text($coordX+158.5, $coordY+102.8, "EMPLOYER", 0, false);
				$oPDF->Text($coordX+149.1, $coordY+105.1, "SHARE", 0, false);
				$oPDF->Text($coordX+160.7, $coordY+105.1, "SHARE", 0, false);
				$oPDF->Text($coordX+172.7, $coordY+104, "TOTAL", 0, false);
				
				// 7 LINES FOR COLUMNS
				
				$oPDF->line($coordX+29.5, $coordY+93.5, $coordX+29.5, $coordY+222.7, $style1);
				$oPDF->line($coordX+99.8, $coordY+93.5, $coordX+99.8, $coordY+232.2, $style1);
				$oPDF->line($coordX+124.3, $coordY+93.5, $coordX+124.3, $coordY+222.7, $style1);
				$oPDF->line($coordX+146.5, $coordY+93.5, $coordX+146.5, $coordY+232.2, $style1);
				$oPDF->line($coordX+158.1, $coordY+100.8, $coordX+158.1, $coordY+232.2, $style1);
				$oPDF->line($coordX+169.9, $coordY+100.8, $coordX+169.9, $coordY+232.2, $style1);
				$oPDF->line($coordX+181.5, $coordY+93.5, $coordX+181.5, $coordY+222.7, $style1);
				
				//TEXTBOX (31 lines)
				$oPDF->line($coordX+4.7, $coordY+109.8, $coordX+203.5, $coordY+109.8, $style1);
				$oPDF->line($coordX+4.7, $coordY+113.7, $coordX+203.5, $coordY+113.7, $style1);
				$oPDF->line($coordX+4.7, $coordY+117.6, $coordX+203.5, $coordY+117.6, $style1);
				$oPDF->line($coordX+4.7, $coordY+121.5, $coordX+203.5, $coordY+121.5, $style1);
				$oPDF->line($coordX+4.7, $coordY+125.4, $coordX+203.5, $coordY+125.4, $style1);
				$oPDF->line($coordX+4.7, $coordY+129.3, $coordX+203.5, $coordY+129.3, $style1);
				$oPDF->line($coordX+4.7, $coordY+133.2, $coordX+203.5, $coordY+133.2, $style1);
				$oPDF->line($coordX+4.7, $coordY+137.1, $coordX+203.5, $coordY+137.1, $style1);
				$oPDF->line($coordX+4.7, $coordY+141.0, $coordX+203.5, $coordY+141.0, $style1);
				$oPDF->line($coordX+4.7, $coordY+144.9, $coordX+203.5, $coordY+144.9, $style1);
				$oPDF->line($coordX+4.7, $coordY+148.8, $coordX+203.5, $coordY+148.8, $style1);
				$oPDF->line($coordX+4.7, $coordY+152.7, $coordX+203.5, $coordY+152.7, $style1);
				$oPDF->line($coordX+4.7, $coordY+156.6, $coordX+203.5, $coordY+156.6, $style1);
				$oPDF->line($coordX+4.7, $coordY+160.5, $coordX+203.5, $coordY+160.5, $style1);
				$oPDF->line($coordX+4.7, $coordY+164.4, $coordX+203.5, $coordY+164.4, $style1);
				$oPDF->line($coordX+4.7, $coordY+168.3, $coordX+203.5, $coordY+168.3, $style1);
				$oPDF->line($coordX+4.7, $coordY+172.0, $coordX+203.5, $coordY+172.0, $style1);
				$oPDF->line($coordX+4.7, $coordY+175.9, $coordX+203.5, $coordY+175.9, $style1);
				$oPDF->line($coordX+4.7, $coordY+179.8, $coordX+203.5, $coordY+179.8, $style1);
				$oPDF->line($coordX+4.7, $coordY+183.7, $coordX+203.5, $coordY+183.7, $style1);
				$oPDF->line($coordX+4.7, $coordY+187.6, $coordX+203.5, $coordY+187.6, $style1);
				$oPDF->line($coordX+4.7, $coordY+191.5, $coordX+203.5, $coordY+191.5, $style1);
				$oPDF->line($coordX+4.7, $coordY+195.4, $coordX+203.5, $coordY+195.4, $style1);
				$oPDF->line($coordX+4.7, $coordY+199.2, $coordX+203.4, $coordY+199.2, $style1);
				$oPDF->line($coordX+4.7, $coordY+203.2, $coordX+203.5, $coordY+203.2, $style1);
				$oPDF->line($coordX+4.7, $coordY+207.1, $coordX+203.5, $coordY+207.1, $style1);
				$oPDF->line($coordX+4.7, $coordY+211, $coordX+203.5, $coordY+211, $style1);
				$oPDF->line($coordX+4.7, $coordY+214.9, $coordX+203.5, $coordY+214.9, $style1);
				$oPDF->line($coordX+4.7, $coordY+218.8, $coordX+203.5, $coordY+218.8, $style1);
				$oPDF->line($coordX+4.7, $coordY+222.7, $coordX+203.5, $coordY+222.7, $style1);
				
				
				//row 6
				$oPDF->line($coordX+4.7, $coordY+232.2, $coordX+203.5, $coordY+232.2, $style1);
				$oPDF->SetFont('helvetica','',0);
				$oPDF->SetFontSize(6.8);
				$oPDF->Text($coordX+5.6, $coordY+226.5, "No. of Employees/", 0, false);
				$oPDF->Text($coordX+5.6, $coordY+229.3, "Members on this page", 0, false);
				$oPDF->line($coordX+32.4, $coordY+222.7, $coordX+32.4, $coordY+232.2, $style1);
				$oPDF->Text($coordX+47.7, $coordY+226.5, "Total no. of Employees/", 0, false);
				$oPDF->Text($coordX+47.7, $coordY+229.3, "Members if last page", 0, false);
				$oPDF->line($coordX+47.3, $coordY+222.7, $coordX+47.3, $coordY+232.2, $style1);
				$oPDF->line($coordX+74.7, $coordY+222.7, $coordX+74.7, $coordY+232.2, $style1);
				$oPDF->SetFontSize(8);
				$oPDF->Text($coordX+100.1, $coordY+226, "TOTAL FOR THIS PAGE", 0, false);
				$oPDF->SetFontSize(6.5);

				$oPDF->Text($coordX+100.1, $coordY+230.5, "GRAND TOTAL(if last page)", 0, false);
				$oPDF->line($coordX+99.8, $coordY+227.5, $coordX+203.5, $coordY+227.5, $style1);
				
				//row 7
				$oPDF->SetFillColor(197, 197, 197);
				$oPDF->MultiCell(198.9, 4, '', 'LRB', 'C', 1, 1, 4.7, 232, true, 0, false, false, 1);
				$oPDF->SetFont('helvetica','B',0);
				$oPDF->SetFontSize(8);
				$oPDF->Text($coordX+85, $coordY+235, "EMPLOYEE CERTIFICATION", 0, false);
		
				//row 8
				$oPDF->SetFont('helvetica','',0);
				$oPDF->SetFontSize(7.9);
				$oPDF->Text($coordX+10, $coordY+240, "I hereby certify under pain of perjury that the information given and all statements made herein are true and correct to the best of my knowledge and belief. I", 0, false);
				$oPDF->Text($coordX+6.5, $coordY+242.8, "further certify that my signature appearing herein is genuine and authentic.", 0, false);
				$oPDF->line($coordX+6, $coordY+257.4, $coordX+97, $coordY+257.4, $style1);
				
				//signatory
				$oPDF->Text($coordX+40, $coordY+256.5, $gData['signatoryby']);
				//designation position
				$oPDF->Text($coordX+120, $coordY+256.5, $gData['position']);
				//date
				$oPDF->Text($coordX+169, $coordY+256.5, date("F d, Y"));
				
				$oPDF->Text($coordX+15, $coordY+260.5, "HEAD OF OFFICE OR AUTHORIZED REPRESENTATIVE", 0, false);
				$oPDF->line($coordX+101, $coordY+257.4, $coordX+156, $coordY+257.4, $style1);
				$oPDF->Text($coordX+112, $coordY+260.5, "DESIGNATION/POSITION", 0, false);
				$oPDF->line($coordX+159, $coordY+257.4, $coordX+201, $coordY+257.4, $style1);
				$oPDF->Text($coordX+177, $coordY+260.5, "DATE", 0, false);
				$oPDF->SetFont('helvetica','I',0);
				$oPDF->SetFontSize(7.9);
				$oPDF->Text($coordX+33, $coordY+264, "(Signature Over Printed Name)", 0, false);
				
				$oPDF->SetFont('helvetica','B',0);
				$oPDF->SetFontSize(9);
				$oPDF->Text($coordX+66, $coordY+270, "THIS FORM MAY BE REPRODUCED. NOT FOR SALE", 0, false);
				$oPDF->SetFont('helvetica','i',0);
				$oPDF->SetFontSize(5);
				$oPDF->Text($coordX+187, $coordY+270, "(Revised 03/2011)", 0, false);
				
				$coordX = 7;
				$coordY = 105;
			}
				}
			IF($empcount==$countemp){
				$coordX=0;
				$coordY=0;
				$oPDF->SetFontSize(6);
				$oPDF->Text($coordX+147, $coordY+231, "P".$this->moneyFormat($grandTotalEmployee));
				$oPDF->Text($coordX+158.5, $coordY+231, "P".$this->moneyFormat($grandTotalEmployer));
				$oPDF->Text($coordX+170.5, $coordY+231, "P".$this->moneyFormat($grandTotalHDMFContribution));
			}
			$rsResult1->MoveNext();
		
			$empcount++;
			$totalcount++;
		}
		
		$coordX=0;
		$coordY=0;
			//$oPDF->SetFontSize(6);
		//$oPDF->Text($coordX+147, $coordY+226, "P".$this->moneyFormat($pageTotalEmployeeContribution));
		//$oPDF->Text($coordX+158.5, $coordY+226, "P".$this->moneyFormat($pageTotalEmployerContribution));
		//$oPDF->Text($coordX+170.5, $coordY+226, "P".$this->moneyFormat($pageTotalHDMFContribution));
		
		
		
    	//get the output
    	$output = $oPDF->Output("PDFResultMCRF_".$gData['year'].".pdf");
    	
    	if (!empty($output)) {
    		return $output;
    	}
    	return false;
    }
    function getPDFResultMCRF($arrData = array(), $gData = array(), $comp_info = array(), $isLoc = false){
    	IF($isLoc AND $gData['branchinfo_id'] != 0){
        	$branch_details = clsSSS::getLocationInfo($gData['branchinfo_id']);
        	$compname = $branch_details['branchinfo_name'];
        	$compadds = $branch_details['branchinfo_add'];
        	$comphdmfno = $branch_details['branchinfo_hdmf'];
        	$comptinno = $branch_details['branchinfo_tin'];
        	$comptelno = $branch_details['branchinfo_tel1'];
        }ELSE{
        	$compname = $comp_info['comp_name'];
        	$compadds = $comp_info['comp_add'];
        	$comphdmfno = $comp_info['comp_hdmf'];
        	$comptelno = $comp_info['comp_tel'];
        }
    	$m = $gData['year'].'-'.$gData['month'].'-'.'10';
    	$objClsMngeDecimal = new Application();
    	$paper = 'A4';
		$orientation = 'portrait';
		$filename = 'MCRF.pdf';
		$month = $gData['month'];
		$year = $gData['year'];
		$comp = $gData['comp'];
		$content = '';
		$ctr = 0;
		$trace = 1;
		$max_per_page = 30;
		$max_page = ceil($size/30);
		$page = 1;
		//@note: jim(20120913 adjust to check if not included.
    	$qry = array();
		IF($gData['branchinfo_id']!=0){//get Location parameter.
			$qry[] = "em.branchinfo_id = '".$gData['branchinfo_id']."'";
		}
		//$qry[] = "pbs.empdd_id = '3'";
		//$qry[] = "pbs.bldsched_period != '0'";
		$qry[] = "em.comp_id='".$comp."'";
		$qry[] = "ppp.payperiod_period='".$month."'";
		$qry[] = "ppp.payperiod_period_year='".$year."'";
		$criteria = count($qry)>0 ? " WHERE ".implode(' and ',$qry) : '';
		$sql = "SELECT distinct em.emp_id,e.pi_hdmf,e.pi_lname,e.pi_fname,e.pi_mname,em.emp_hiredate,em.emp_resigndate,pps.paystub_start_date,pps.paystub_end_date, DATE_FORMAT(e.pi_bdate,'%m%d%Y') as bday 
				FROM emp_masterfile em
				JOIN emp_personal_info e on (e.pi_id=em.pi_id)
				JOIN payroll_paystub_report ppr on (ppr.emp_id=em.emp_id)
				JOIN payroll_pay_stub pps on(pps.payperiod_id=ppr.payperiod_id)
				JOIN payroll_pay_period ppp on (ppp.payperiod_id=pps.payperiod_id)
				$criteria
				GROUP BY em.emp_id ORDER BY e.pi_lname ASC";
		$size = mysql_num_rows(mysql_query($sql));
		$rsResult = $this->conn->Execute($sql);

		$header = '	<style type="text/css">
							@page { margin-top: 3em; margin-left:1em;} 
						</style>
					<table style="border-collapse:collapse; font-family: Helvetica; font-size:12px; page-break-after:always;" width="713px">
						<tr><td width="100%">
							<table style="border-collapse:collapse;">
								<tr>
									<td><img src="'.SYSCONFIG_CLASS_PATH.'util/dompdf/images/pdf_report/pag-ibig_logo.jpg" width="80px" height="90px" style="margin:10px;"></td>
									<td style="padding-left:20px; font-size:24px;"><strong>MEMBER\'S CONTRIBUTION<br>REMITTANCE FORM (MCRF)</strong></td>
									<td style="padding-left:105px; font-size:12px;">
										<table style="border-collapse:collapse;" width="225px">
											<tr><td align="right">FPF060</td></tr>
											<tr><td>&nbsp;</td></tr>
											<tr><td style="background-color:#c0c0c0; border:1px solid black; font-size:10px;"><strong>&nbsp;&nbsp;Pag-IBIG EMPLOYER\'S ID NUMBER</strong></td></tr>
											<tr><td style="border:1px solid black; padding:10px 0;">&nbsp;&nbsp;'.$comphdmfno.'</td></tr>
										</table>
									</td>
								</tr>
							</table>
						</td></tr>
						<tr><td style="font-size:11px;" width="100%"><strong><i>NOTE: PLEASE READ INSTRUCTIONS AT THE BACK.</i></strong></td></tr>
						<tr><td width="100%">
							<table style="border-collapse:collapse; border:1px solid black;" width="100%">
								<tr>
									<td colspan="7" style="border-right:1px solid black;" width="457px">&nbsp;&nbsp;EMPLOYER/BUSINESS NAME</td>
									<td colspan="4">&nbsp;&nbsp;BRANCH/OFFICE</td>
								</tr>
								<tr>
									<td colspan="7" style="border-right:1px solid black;">&nbsp;&nbsp;'.$compname.'</td>
									<td colspan="4">&nbsp;</td>
								</tr>
								<tr>
									<td colspan="7" style="border-right:1px solid black; border-top:1px solid black;">&nbsp;&nbsp;EMPLOYER/BUSINESS ADDRESS</td>
									<td colspan="4" style="border-top:1px solid black;">&nbsp;&nbsp;TYPE OF EMPLOYER</td>
								</tr>
								<tr>
									<td colspan="7" style="border-right:1px solid black;">
										<table style="font-size:10px;"><tr>
											<td width="150px">Unit/Room No., Floor</td>
											<td width="100px">Building Name</td>
											<td>Lot No., Block No., Phase No. House No.</td>
										</tr>
										<tr>
											<td width="150px">&nbsp;</td>
											<td width="100px">&nbsp;</td>
											<td>&nbsp;</td>
										</tr>
										
										</table>
									</td>
									<td colspan="4">
										<table style="font-size:10px; padding-left:15px;">
											<tr><td style="border:1px solid black;" width="10px" align="center">X</td>
											<td width="100px">Private</td>
											<td style="border:1px solid black;" width="10px" align="center">&nbsp;</td>
											<td>Household</td>
											</tr>
											<tr><td style="border:1px solid black;" width="10px" align="center">&nbsp;</td>
											<td width="100px">Government</td>
										</tr></table>
									</td>
								</tr>
								<tr>
									<td colspan="11" style="border-top:1px solid black;"><table style="font-size:10px;"><tr>
										<td width="110px">Street Name</td>
										<td width="110px">Subdivision</td>
										<td width="110px">Barangay</td>
										<td width="120px">Municipality/City</td>
										<td width="170px">Province/State/Country <i>(if aborad)</i></td>
										<td>ZIP Code</td>
									</tr></table></td>
								</tr>
								<tr>
									<td colspan="11"><table style="font-size:10px;"><tr>
										<td width="510px">'.$compadds.'</td>
										<td width="10px">&nbsp;</td>
										<td width="10px">&nbsp;</td>
										<td width="20px">&nbsp;</td>
										<td width="70px">&nbsp;</td>
										<td>'.$comp_info['comp_zipcode'].'</td>
									</tr></table></td>
								</tr>
								<tr>
									<td colspan="7" style="border-right:1px solid black; border-top:1px solid black;" width="480px">&nbsp;&nbsp;MEMBERSHIP PROGRAM</td>
									<td colspan="4" style="border-top:1px solid black;">&nbsp;&nbsp;PERIOD COVERED<i>(month/year)</i></td>
								</tr>
								<tr>
									<td colspan="7" style="border-right:1px solid black;">
										<table><tr>
											<td style="border:1px solid black;" width="10px" align="center">X</td>
											<td width="120px">Pag-IBIG I</td>
											<td style="border:1px solid black;" width="10px">&nbsp;</td>
											<td width="120px">Pag-IBIG II</td>
											<td style="border:1px solid black;" width="10px">&nbsp;</td>
											<td>Modified Pag-IBIG II</td>
										</tr></table>
									</td>
									<td colspan="4" align="center">&nbsp;&nbsp;'.date('F',dDate::parseDateTime($m)).' '.date('Y',dDate::parseDateTime($m)).'</td>
								</tr>
								<tr>
									<td colspan="11" style="border-top:1px solid black;">
										<table style="font-size:10px; border-collapse:collapse;"><tr>
											<td align="center" width="90px" style="border-right:1px solid black;"><strong>Pag-IBIG MID No.</strong></td>
											<td align="center" width="260px" style="border-right:1px solid black;" colspan="4"><strong>NAME OF MEMBERS</strong></td>
											<td align="center" width="90px" style="border-right:1px solid black;"><strong>ACCOUNT NO.</strong></td>
											<td align="center" width="30px" style="border-right:1px solid black;"><strong>MONTHLY COMPENSATION</strong></td>
											<td align="center" colspan="3" width="130px" style="border-right:1px solid black; border-bottom:1px solid black;"><strong>CONTRIBUTIONS</strong></td>
											<td align="center" width="80px"><strong>REMARKS</strong></td>
										</tr>
										<tr>
											<td style="border-right:1px solid black;" width="50px">&nbsp;</td>
											<td style="font-size:7px;" width="65px"><i>Last Name</i></td>
											<td style="font-size:7px;" width="65px" colspan="2"><i>First Name&nbsp;&nbsp;&nbsp;Name Extension<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(Jr., III, etc.)</i></td>
											
											<td style="font-size:7px; border-right:1px solid black;" width="65px"><i>Middle Name</i></td>
											<td style="border-right:1px solid black;" width="50px">&nbsp;</td>
											<td style="border-right:1px solid black;" width="50px">&nbsp;</td>
											<td style="font-size:7px; border-right:1px solid black;" align="center"><strong>EMPLOYEE SHARE</strong></td>
											<td style="font-size:7px; border-right:1px solid black;" align="center"><strong>EMPLOYER SHARE</strong></td>
											<td style="font-size:7px; border-right:1px solid black;" align="center"><strong>TOTAL</strong></td>
											<td>&nbsp;</td>
										</tr>';
		$blank = '<tr>
											<td style="border-top:1px solid black; border-right:1px solid black;">&nbsp;</td>
											<td style="border-top:1px solid black;">&nbsp;</td>
											<td style="border-top:1px solid black;" colspan="2">&nbsp;</td>
											<td style="border-top:1px solid black; border-right:1px solid black;">&nbsp;</td>
											<td style="border-top:1px solid black; border-right:1px solid black;">&nbsp;</td>
											<td style="border-top:1px solid black; border-right:1px solid black;">&nbsp;</td>
											<td style="border-right:1px solid black; border-top:1px solid black;" align="center">&nbsp;</td>
											<td style="border-right:1px solid black; border-top:1px solid black;" align="center">&nbsp;</td>
											<td style="border-right:1px solid black; border-top:1px solid black;" align="center">&nbsp;</td>
											<td style="border-top:1px solid black;">&nbsp;</td>
										</tr>';
				
						
		if(!$rsResult->EOF){
		while(!$rsResult->EOF){
			//@note: jim(20120913) adjust if no hdmf no. get birthday
			IF($rsResult->fields['pi_hdmf']=='-' || $rsResult->fields['pi_hdmf']=='--'){
				$hdmfno = $rsResult->fields['bday'];
			}ELSE{
				$hdmfno = $rsResult->fields['pi_hdmf'];
			}
			$HDMFContribution = $this->getHDMFContribution($gData['month'],$gData['year'],$rsResult->fields['emp_id']);
			$Emp_MONTHLYSAL = $this->getHDMFContribution($gData['month'], $gData['year'], $rsResult->fields['emp_id'],1);
			$TotalHDMFContribution = $HDMFContribution['employee_share'] + $HDMFContribution['employer_share'];
			if($ctr == 0){
				$pageTotalEmployeeContribution = 0;
				$pageTotalEmployerContribution = 0;
				$pageTotalHDMFContribution = 0;
			}
			$pageTotalHDMFContribution += $TotalHDMFContribution;
			$pageTotalEmployeeContribution += $HDMFContribution['employee_share'];
			$pageTotalEmployerContribution += $HDMFContribution['employer_share'];
			$grandTotalEmployee += $HDMFContribution['employee_share'];
			$grandTotalEmployer += $HDMFContribution['employer_share'];
			$grandTotalHDMFContribution += $TotalHDMFContribution;
		
			
			
			$main = '<tr>
											<td style="border-top:1px solid black; border-right:1px solid black;" align="right">'.$hdmfno.'&nbsp;&nbsp;&nbsp;</td>
											<td style="border-top:1px solid black;">'.$rsResult->fields['pi_lname'].'</td>
											<td style="border-top:1px solid black;" colspan="2">'.$rsResult->fields['pi_fname'].'</td>
											<td style="border-top:1px solid black; border-right:1px solid black;">'.$rsResult->fields['pi_mname'].'</td>
											<td style="border-top:1px solid black; border-right:1px solid black;">&nbsp;</td>
											<td style="border-top:1px solid black; border-right:1px solid black;" align="right">'.$this->moneyFormat($Emp_MONTHLYSAL['emp_monthlysal']).'&nbsp;&nbsp;</td>
											<td style="border-right:1px solid black; border-top:1px solid black;" align="center">'.$this->moneyFormat($HDMFContribution['employee_share']).'</td>
											<td style="border-right:1px solid black; border-top:1px solid black;" align="center">'.$this->moneyFormat($HDMFContribution['employer_share']).'</td>
											<td style="border-right:1px solid black; border-top:1px solid black;" align="center">'.$this->moneyFormat($TotalHDMFContribution).'</td>
											<td style="border-top:1px solid black;" align="center">'.$this->validateNewlyHired($rsResult->fields['emp_hiredate'], $rsResult->fields['emp_resigndate'], $rsResult->fields['paystub_start_date'], $rsResult->fields['paystub_end_date'],$gData['month'],$gData['year']).'</td>
										</tr>';
			if($ctr == 0){
				$content .= $header;
				$content .= $main;
				$ctr++;
				
			} elseif($ctr <= ($max_per_page-2)){
				$content .= $main;
				$ctr++;
			} else {
				$footer =	'<tr>
												<td colspan="2" rowspan="2" style="border-top:1px solid black; border-right:1px solid black;"><table style="border-collapse:collapse;" width="100px">
													<tr><td style="font-size:9px; border-right:1px solid black;" width="100px">No. of Employees/ Members on this page</td><td style="padding-left:10px; font-size:24px;">'.($ctr+1).'</td></tr>
												</table></td>
												<td colspan="3" rowspan="2" style="border-top:1px solid black; border-right:1px solid black;" width="50px"><table style="border-collapse:collapse;">
													<tr><td style="font-size:9px; border-right:1px solid black;" width="100px">Total no. of Employees/<br>Members if last page</td><td style="padding-left:20px; font-size:24px;">&nbsp;</td></tr>
												</table></td>
												<td colspan="2" style="border-top:1px solid black; border-right:1px solid black;">TOTAL FOR THIS PAGE</td>
												<td style="border-top:1px solid black; border-right:1px solid black;">P'.$this->moneyFormat($pageTotalEmployeeContribution).'</td>
												<td style="border-top:1px solid black; border-right:1px solid black;">P'.$this->moneyFormat($pageTotalEmployerContribution).'</td>
												<td colspan="2" style="border-top:1px solid black;">P'.$this->moneyFormat($pageTotalHDMFContribution).'</td>
											</tr>
											<tr>
												<td colspan="2" style="border-top:1px solid black; border-right:1px solid black;">GRAND TOTAL (if last page)</td>
												<td style="border-top:1px solid black; border-right:1px solid black;">&nbsp;</td>
												<td style="border-top:1px solid black; border-right:1px solid black;">&nbsp;</td>
												<td colspan="2" style="border-top:1px solid black;">&nbsp;</td>
											</tr>
											<tr><td colspan="11" align="center" style="border-top:1px solid black; background-color:#c0c0c0;"><strong>EMPLOYER CERTIFICATION</strong></td></tr>
											<tr><td colspan="11" align="justify" style="border-top:1px solid black; font-size:10px; padding:5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;I hereby certify under pain of perjury that the information given and all statements made herein are true and correct to the best of my knowledge and belief. I further certify that my signature appearing herein is genuine and authentic.</td></tr>
											<tr><td colspan="11">&nbsp;</td></tr>
											<tr><td colspan="11">&nbsp;</td></tr>
											<tr>
												<td colspan="5" align="center"><table width="98%"><tr>
													<td style="border-bottom:1px solid black;" align="center">&nbsp;'.$gData['signatoryby'].'</td>
												</tr></table></td>
												<td colspan="3" align="center"><table width="98%"><tr>
													<td style="border-bottom:1px solid black;" align="center">&nbsp;'.$gData['position'].'</td>
												</tr></table></td>
												<td colspan="3" align="center"><table width="98%"><tr>
													<td style="border-bottom:1px solid black;" align="center">&nbsp;'.date("F d, Y").'</td>
												</tr></table></td>
											</tr>
											<tr>
												<td colspan="5" align="center">HEAD OF OFFICE OR AUTHORIZED REPRESENTATIVE</td>
												<td colspan="3" align="center">DESIGNATION/POSITION</td>
												<td colspan="3" align="center">DATE</td>
											</tr>
											<tr>
												<td colspan="5" align="center"><i>(Signature Over Printed Name)</i></td>
												<td colspan="6" align="center">&nbsp;</td>
											</tr>				
										</table>
									</td>
								</tr>
							</table>
						</td></tr>
						<tr><td width="100%" align="center" style="vertical-align:top;"><table width="100%"><tr><td width="92%" style="padding-left:240px;"><strong>THIS FORM MAY BE REPRODUCED. NOT FOR SALE.</strong></td><td style="font-size:6px;"><i>(Revised 03/2011)</i></td></tr></table></td></tr>
					</table>';
				$content .= $main;
				$content .= $footer;
				$ctr = 0;
				$page++;
			}
			$trace++;
			$rsResult->MoveNext();
		}} else {
			$content .= $header;
		}
    	if(($trace-1) == $size and $ctr != $max_per_page){
    			$last_count = $ctr;
				while($ctr < $max_per_page){
					$content .= $blank;
					$ctr++;
				}
		    	if($ctr >= $max_per_page){
		    		$footer =	'<tr>
												<td colspan="2" rowspan="2" style="border-top:1px solid black; border-right:1px solid black;"><table style="border-collapse:collapse;" width="100px">
													<tr><td style="font-size:9px; border-right:1px solid black;" width="100px">No. of Employees/ Members on this page</td><td style="padding-left:10px; font-size:24px;">'.($last_count).'</td></tr>
												</table></td>
												<td colspan="3" rowspan="2" style="border-top:1px solid black; border-right:1px solid black;" width="50px"><table style="border-collapse:collapse;">
													<tr><td style="font-size:9px; border-right:1px solid black;" width="100px">Total no. of Employees/<br>Members if last page</td><td style="padding-left:20px; font-size:24px;">'. ($trace-1) .'</td></tr>
												</table></td>
												<td colspan="2" style="border-top:1px solid black; border-right:1px solid black;">TOTAL FOR THIS PAGE</td>
												<td style="border-top:1px solid black; border-right:1px solid black;">P'.$this->moneyFormat($pageTotalEmployeeContribution).'</td>
												<td style="border-top:1px solid black; border-right:1px solid black;">P'.$this->moneyFormat($pageTotalEmployerContribution).'</td>
												<td colspan="2" style="border-top:1px solid black;">P'.$this->moneyFormat($pageTotalHDMFContribution).'</td>
											</tr>
											<tr>
												<td colspan="2" style="border-top:1px solid black; border-right:1px solid black;">GRAND TOTAL (if last page)</td>
												<td style="border-top:1px solid black; border-right:1px solid black;">P'. $this->validateIfLastPage($max_page, $page-1, $this->moneyFormat($grandTotalEmployee)) .'</td>
												<td style="border-top:1px solid black; border-right:1px solid black;">P'. $this->validateIfLastPage($max_page, $page-1, $this->moneyFormat($grandTotalEmployer)) .'</td>
												<td colspan="2" style="border-top:1px solid black;">P'. $this->validateIfLastPage($max_page, $page-1, $this->moneyFormat($grandTotalHDMFContribution)) .'</td>
											</tr>
											<tr><td colspan="11" align="center" style="border-top:1px solid black; background-color:#c0c0c0;"><strong>EMPLOYER CERTIFICATION</strong></td></tr>
											<tr><td colspan="11" align="justify" style="border-top:1px solid black; font-size:10px; padding:5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;I hereby certify under pain of perjury that the information given and all statements made herein are true and correct to the best of my knowledge and belief. I further certify that my signature appearing herein is genuine and authentic.</td></tr>
											<tr><td colspan="11">&nbsp;</td></tr>
											<tr><td colspan="11">&nbsp;</td></tr>
											<tr>
												<td colspan="5" align="center"><table width="98%"><tr>
													<td style="border-bottom:1px solid black;" align="center">&nbsp;'.$gData['signatoryby'].'</td>
												</tr></table></td>
												<td colspan="3" align="center"><table width="98%"><tr>
													<td style="border-bottom:1px solid black;" align="center">&nbsp;'.$gData['position'].'</td>
												</tr></table></td>
												<td colspan="3" align="center"><table width="98%"><tr>
													<td style="border-bottom:1px solid black;" align="center">&nbsp;'.date("F d, Y").'</td>
												</tr></table></td>
											</tr>
											<tr>
												<td colspan="5" align="center">HEAD OF OFFICE OR AUTHORIZED REPRESENTATIVE</td>
												<td colspan="3" align="center">DESIGNATION/POSITION</td>
												<td colspan="3" align="center">DATE</td>
											</tr>
											<tr>
												<td colspan="5" align="center"><i>(Signature Over Printed Name)</i></td>
												<td colspan="6" align="center">&nbsp;</td>
											</tr>				
										</table>
									</td>
								</tr>
							</table>
						</td></tr>
						<tr><td width="100%" align="center" style="vertical-align:top;"><table width="100%"><tr><td width="92%" style="padding-left:240px;"><strong>THIS FORM MAY BE REPRODUCED. NOT FOR SALE.</strong></td><td style="font-size:6px;"><i>(Revised 03/2011)</i></td></tr></table></td></tr>
					</table>';
					$content .= $footer;
				}
			}
		$this->createPDF($content, $paper, $orientation, $filename);
    }
    
	function dbfetchCompDetails($comp_id_ = null){
		if($comp_id_!=null || $comp_id_ != ''){
		$qry[] = "comp_id = '".$comp_id_."'";
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";
		}
		$sql = "select * from company_info a inner join company_type b on (a.comptype_id=b.comptype_id) $criteria";
		$rsResult = $this->conn->Execute($sql);
		if(!$rsResult->EOF){
			return $rsResult->fields;
		}
    }
    
	/**
	 * @note: xlsHDMF_MCR_Report
	 * @param unknown_type $gData
	 */
    function generateXLSHReport($gData = array(),$arrData = array(), $isLoc = false) {
    	$m = $gData['year'].'-'.$gData['month'].'-'.'1';
    	if($gData['type'] == 'ytd'){
            $str = "YTD Report";
        }elseif($gData['type'] == 'month'){
            $str = "Monthly Report";
        }else{
            $str = "Yearly Report";
        }
        if($gData['type'] == 'month'){
            $str2 = date('Ym',dDate::parseDateTime($m));
            $str2month = date('F',dDate::parseDateTime($m));
        }elseif($gData['type'] == 'ytd'){
            $str2= "January 2011 to ".date('F Y',dDate::parseDateTime($m));
        }else{
            $str2= $gData['year'];
        }
    	IF($isLoc){
        	$branch_details = clsSSS::getLocationInfo($gData['branchinfo_id']);
        	$comphdmfno = $branch_details['branchinfo_hdmf'];
        }ELSE{
        	$comphdmfno = $arrData[0]['emp_info']['comp_hdmf'];
        }
        $filename = "HDMF_MCF ".date('FY',dDate::parseDateTime($m)).".xls"; // The file name you want any resulting file to be called.
    	// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		$objReader = PHPExcel_IOFactory::createReader('Excel5');
		$objPHPExcel = $objReader->load("templates/HDMF_Contri_Template.xls");
		$baseRow = 3;
		if(count($arrData)>0){
            foreach($arrData as $key => $val){
				$row = $baseRow + $key;
				$objPHPExcel->getActiveSheet()->insertNewRowBefore($row,1);
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $comphdmfno);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, str_replace("-", "",$val['emp_info']['pi_hdmf']));
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $val['emp_info']['pi_lname']);
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$row, $val['emp_info']['pi_fname']);
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$row, $val['emp_info']['pi_mnamefull']);
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$row, $str2);
				$objPHPExcel->getActiveSheet()->setCellValue('I'.$row, $val['sss']['ppe_amount']);
				$objPHPExcel->getActiveSheet()->setCellValue('J'.$row, $val['sss']['ppe_amount_employer']);
				$objPHPExcel->getActiveSheet()->setCellValue('K'.$row, $val['emp_info']['emp_idnum']);
				$objPHPExcel->getActiveSheet()->setCellValue('L'.$row, $val['emp_info']['pi_tin']);
				$objPHPExcel->getActiveSheet()->setCellValue('M'.$row, date("m/d/Y", strtotime($val['emp_info']['pi_bdate'])));
			}
			$objPHPExcel->getActiveSheet()->removeRow($baseRow-1,1);
		}
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
    
	function validateNewlyHired($dateHired, $dateResigned, $dateCutOffStart, $dateCutOffEnd,$month, $year){
    	$datehired_ = date('m/d/Y',dDate::parseDateTime($dateHired));
		$month_ = date('m',dDate::parseDateTime($dateHired));
		$year_ = date('Y',dDate::parseDateTime($dateHired));
    	IF($dateResigned != NULL and $dateResigned !='' and $dateResigned != '0000-00-00'){
    		return "RS: " . date('m/d/Y',dDate::parseDateTime($dateResigned));
    	} ELSEIF($month_ == $month AND $year_ == $year) {
    		return "N: " . date('m/d/Y',dDate::parseDateTime($dateHired));
    	} ELSE {
    		return "&nbsp;";
    	}
    }
}
?>