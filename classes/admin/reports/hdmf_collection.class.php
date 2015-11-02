<?php
/**
 * Initial Declaration
 */

require_once(SYSCONFIG_CLASS_PATH."util/pdf.class.php");
require_once(SYSCONFIG_CLASS_PATH."util/dompdf/dompdf_config.inc.php");
require_once(SYSCONFIG_CLASS_PATH."util/PHPExcel.php");
require_once(SYSCONFIG_CLASS_PATH."util/PHPExcel/IOFactory.php");

/**
 * Class Module
 *
 * @author  Jason I. Mabignay
 *
 */
class clsHDMFCollection{

	var $conn;
	var $fieldMap;
	var $Data;

	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsHDMFCollection object
	 */
	function clsHDMFCollection($dbconn_ = null){
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
		$editLink = "<a href=\"?statpos=hdmf_collection&edit=',am.mnu_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/edit.png\" title=\"Edit\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
		$delLink = "<a href=\"?statpos=hdmf_collection&delete=',am.mnu_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\"  border=0 width=\"16\" height=\"16\"></a>";
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
	
	/**
	 * @note: xlsHDMF_MCR_Report
	 * @param unknown_type $gData
	 */
    function generateHDMF_MPL_Report($gData = array(),$arrData = array(), $compInfo = array()){
    	$m = $gData['year'].'-'.$gData['month'].'-'.'1';
        $filename = "HDMF_MPL_".date('FY',dDate::parseDateTime($m)).".xls"; // The file name you want any resulting file to be called.
    	// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		$objReader = PHPExcel_IOFactory::createReader('Excel5');
		$objPHPExcel = $objReader->load("templates/HDMF_MPL_Template.xls");
		$baseRow = 3;
		if(count($arrData)>0){
            foreach($arrData as $key => $val){
            	IF(count($val['collection']['payment']) > 0){
					$row = $baseRow + $key;
					$objPHPExcel->getActiveSheet()->insertNewRowBefore($row,1);
					$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $compInfo['comp_hdmf']);
					$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, str_replace("-", "",$val['emp_info']['pi_hdmf']));
					$objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $val['emp_info']['pi_tin']);
					$objPHPExcel->getActiveSheet()->setCellValue('D'.$row, date("m/d/Y", strtotime($val['emp_info']['pi_bdate'])));
					$objPHPExcel->getActiveSheet()->setCellValue('E'.$row, $val['emp_info']['pi_lname']);
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$row, $val['emp_info']['pi_fname']);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$row, $val['emp_info']['pi_mnamefull']);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$row, $gData['year'].$gData['month']);
					$objPHPExcel->getActiveSheet()->setCellValue('K'.$row, $val['collection']['payment']);
            	}ELSE{
            		$baseRow = $baseRow-1;
            	}
			}
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
	 * @note: xlsHDMF_LOAN_Report
	 * @param unknown_type $gData
	 */
	function generateHDMF_LOAN_Report($gData = array(),$arrData = array(), $compInfo = array()){
    	$m = $gData['year'].'-'.$gData['month'].'-'.'1';
        $filename = "HDMF_LOAN_".date('FY',dDate::parseDateTime($m)).".xls"; // The file name you want any resulting file to be called.
    	// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		$objClsMngeDecimal = new Application();
		$finalDecFormat = $objClsMngeDecimal->setFinalDecimalPlaces(0);
		
		$objReader = PHPExcel_IOFactory::createReader('Excel5');
		$objPHPExcel = $objReader->load("templates/HDMF_LOAN_Template.xls");
		
		//header excel
		$objPHPExcel->getActiveSheet()->setCellValue('B6', $compInfo['comp_name']);
		$objPHPExcel->getActiveSheet()->setCellValue('D6', date('F',dDate::parseDateTime($m)));
		$objPHPExcel->getActiveSheet()->setCellValue('D7', date('Y',dDate::parseDateTime($m)));
		$objPHPExcel->getActiveSheet()->setCellValue('B8', $compInfo['comp_add']);
		$objPHPExcel->getActiveSheet()->setCellValue('D8', $compInfo['comp_hdmf']);
		$objPHPExcel->getActiveSheet()->setCellValue('D9', $compInfo['comp_tel']);
		//Body List
		$baseRow = 17;
		$numlist = 1;
		if(count($arrData)>0){
            foreach($arrData as $key => $val){
            	IF(count($val['collection']['payment']) > 0){
					$row = $baseRow + $key;
					$var = $numlist + $key;
					$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, str_replace("-", "",$val['emp_info']['pi_hdmf']));
					$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $var.". ".$val['emp_info']['pi_lname'].", ".$val['emp_info']['pi_fname']." ".$val['emp_info']['pi_mname'].".");
					$objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $val['collection']['payment']);
					$objPHPExcel->getActiveSheet()->getStyle('C'.$row)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
            	}ELSE{
            		$baseRow = $baseRow - 1;
            		$numlist = $numlist - 1;
            	}
			}
		}
		//Footer excel
		$objPHPExcel->getActiveSheet()->setCellValue('A45', "on this page: ".$var);
		$objPHPExcel->getActiveSheet()->setCellValue('B45', "if last page: ".$var);
		$objPHPExcel->getActiveSheet()->setCellValue('C53', $gData['co_maker1_name']);
		$objPHPExcel->getActiveSheet()->setCellValue('C54', $gData['co_maker1_job']);
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

	function createPDF($content, $paper, $orientation, $filename){
		$dompdf = new DOMPDF();
		$dompdf->load_html($content);
		$dompdf->set_paper($paper,$orientation);
		$dompdf->render();
		$dompdf->stream($filename,array('Attachment' => 0));	
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
	
	function getHDMFP24_1($pData = array(), $arrData = array(), $comp_info = array()){
		$orientation = 'portrait';
		$unit = 'mm';
		$format = 'PA4';
		$unicode = true;
		$encoding = "UTF-8";
		
		$oPDF = new clsPDF($orientation, $unit, $format, $unicode, $encoding);
		
		//add initial page
		$oPDF->AddPage();
		
		//set line format
    	$style1 = array('width' => .3, 'cap' => 'square', 'join' => 'round', 'dash' => '0', 'phase' => 0, 'color' => array(0, 0, 0));
    	
		$coordY = 63;
		$c = 65.5;
		
		
		
		for ($a=1;$a<21;$a++){
		
		$oPDF->setFont('helvetica', '', 8);
		//$oPDF->Text(56.5, $c, $b.")");
		$oPDF->SetFillColor(197, 197, 197);
		$oPDF->MultiCell(203, 2, '', 'BT', 'C', 1, 1, 3.3, $coordY, true, 0, false, false, 1);
		
		//$oPDF->Text(56.5, $c+=3.5, $b.")");
		$oPDF->SetFillColor(255, 255, 255);
		$oPDF->MultiCell(203, 2, '', 'BT', 'C', 1, 1, 3.3, $coordY+=3.5, true, 0, false, false, 1);
		
		$c+=3.5;
		$coordY+=3.5;
    	
		}
    	
    	
		//set coordinates
		$coordX = 0;
		$coordY = 0;
		
		
		
		//pag-ibig logo
		$oPDF->Image(SYSCONFIG_CLASS_PATH."util/tcpdf/images/pag-ibig_logo.jpg", 3.5, 4.5, 20.5, 23.3);
		
		$oPDF->SetFont('helvetica', 'B', 20);
		$oPDF->Text($coordX+51, $coordY+10, "MONTHLY REMITTANCE SCHEDULE");
		$oPDF->SetFont('helvetica', 'B', 13);
		$oPDF->Text($coordX+79, $coordY+18, "FOR MULTI-PURPOSE LOAN");
		$oPDF->SetFont('helvetica', 'B', 8.5);
		$oPDF->Text($coordX+185, $coordY+11, "Pag-IBIG Fund");
		$oPDF->SetFont('helvetica', 'B', 17.8);
		$oPDF->Text($coordX+191, $coordY+18.5, "P2-4");
		$oPDF->SetFont('helvetica', '', 4.5);
		$oPDF->Text($coordX+3.3, $coordY+32, "Atrium of Makati, Makati Ave. Makati City");
		$oPDF->SetFont('helvetica', '', 4.5);
		$oPDF->Text($coordX+3.3, $coordY+34, "Tel. No.:811-4401 to 27");
		$oPDF->SetFont('helvetica', 'I', 4.5);
		$oPDF->Text($coordX+20, $coordY+34, "(Connected to all Departments)");
		
		
		$oPDF->SetFont('helvetica', '', '13');
		$oPDF->Text($coordX+55, $coordY+27, "X");
		$oPDF->SetFont('helvetica', '', 7.5);
		$oPDF->Line($coordX+53.5, $coordY+22.1, $coordX+59.5, $coordY+22.1, $style1);
		$oPDF->Line($coordX+53.5, $coordY+22.1, $coordX+53.5, $coordY+28, $style1);
		$oPDF->Line($coordX+59.5, $coordY+22.1, $coordX+59.5, $coordY+28, $style1);
		$oPDF->Line($coordX+53.5, $coordY+28, $coordX+59.5, $coordY+28, $style1);
		$oPDF->Text($coordX+60, $coordY+27, "PRIVATE EMPLOYER");
		
		
		
		$oPDF->Line($coordX+102.2, $coordY+22.1, $coordX+108, $coordY+22.1, $style1);
		$oPDF->Line($coordX+102.2, $coordY+22.1, $coordX+102.2, $coordY+28, $style1);
		$oPDF->Line($coordX+108, $coordY+22.1, $coordX+108, $coordY+28, $style1);
		$oPDF->Line($coordX+102.2, $coordY+28, $coordX+108, $coordY+28, $style1);
		$oPDF->Text($coordX+108.5, $coordY+27, "GOVERNMENT CONTROLLED CORP.");
		
		$oPDF->Line($coordX+53.5, $coordY+30.8, $coordX+59.5, $coordY+30.8, $style1);
		$oPDF->Line($coordX+53.5, $coordY+30.8, $coordX+53.5, $coordY+36.5, $style1);
		$oPDF->Line($coordX+59.5, $coordY+30.8, $coordX+59.5, $coordY+36.5, $style1);
		$oPDF->Line($coordX+53.5, $coordY+36.5, $coordX+59.5, $coordY+36.5, $style1);
		$oPDF->Text($coordX+60, $coordY+35.5, "LOCAL GOVERNMENT UNIT");
		
		$oPDF->Line($coordX+102.2, $coordY+30.8, $coordX+108, $coordY+30.8, $style1);
		$oPDF->Line($coordX+102.2, $coordY+30.8, $coordX+102.2, $coordY+36.5, $style1);
		$oPDF->Line($coordX+108, $coordY+30.8, $coordX+108, $coordY+36.5, $style1);
		$oPDF->Line($coordX+102.2, $coordY+36.5, $coordX+108, $coordY+36.5, $style1);
		$oPDF->Text($coordX+108.5, $coordY+35.5, "NATIONAL GOVERNMENT AGENCY");
		
		$oPDF->SetFont('helvetica', '', 8.5);
		$oPDF->Text($coordX+177.2, $coordY+23.2, "Month");
		$oPDF->Text($coordX+180.5, $coordY+31.5, $pData['month']);
		$oPDF->Text($coordX+198.5, $coordY+23.2, "Year");
		$oPDF->Text($coordX+198, $coordY+31.5, $pData['year']);
		
		$oPDF->Line($coorX+176.5, $coordY+19.7, $coordX+206.5, $coordY+19.7, $style1);
		$oPDF->Line($coorX+176.5, $coordY+19.7, $coordX+176.5, $coordY+38, $style1);
		$oPDF->Line($coorX+206.5, $coordY+19.7, $coordX+206.5, $coordY+38, $style1);
		
		$oPDF->Line($coordX+3.3, $coordY+38, $coordX+206.5, $coordY+38, $style1);
		$oPDF->Line($coordX+3.3, $coordY+38, $coordX+3.3, $coordY+253, $style1);
		$oPDF->Line($coordX+206.5, $coordY+38, $coordX+206.5, $coordY+253, $style1);
		$oPDF->Line($coordX+3.3, $coordY+253, $coordX+206.5, $coordY+253, $style1);
		
		//row 1
		$oPDF->Line($coordX+3.3, $coordY+47.8, $coordX+206.5, $coordY+47.8, $style1);
		$oPDF->SetFont('helvetica', '', 5.8);
		$oPDF->TexT($coordX+4.5, $coordY+41.3, 'NAME OF EMPLOYER');
		$oPDF->TexT($coordX+122, $coordY+42.1, "FOR PRIVATE");
		$oPDF->TexT($coordX+122, $coordY+44.5, "EMPLOYER");
		$oPDF->SetFont('helvetica', '', 8.5);
		$oPDF->Text($coordX+7.2, $coordY+46.5, $comp_info['comp_name']);
		$oPDF->Line($coordX+140.4, $coordY+38, $coordX+140.4, $coordY+47.8, $style1);
		$oPDF->SetFont('helvetica', '', 5.8);
		$oPDF->TexT($coordX+140.6, $coordY+40.1, "EMPLOYER SSS NUMBER");
		$oPDF->SetFont('helvetica', '', 8.5);
		$oPDF->Text($coordX+144.5, $coordY+46.5, $comp_info['comp_sss']);
		$oPDF->Line($coordX+166.7, $coordY+38, $coordX+166.7, $coordY+55.4, $style1);
		$oPDF->SetFont('helvetica', '', 5.8);
		$oPDF->TexT($coordX+167, $coordY+42.1, "FOR GOV'T");
		$oPDF->TexT($coordX+167, $coordY+44.5, "EMPLOYER");
		$oPDF->Line($coordX+179, $coordY+38, $coordX+179, $coordY+47.8, $style1);
		$oPDF->TexT($coordX+179.1, $coordY+40.1, "AGENCY");
		$oPDF->TexT($coordX+179.1, $coordY+42.5, "CODE");
		$oPDF->Line($coordX+188.5, $coordY+38, $coordX+188.5, $coordY+47.8, $style1);
		$oPDF->TexT($coordX+188.7, $coordY+40.1, "BRANCH");
		$oPDF->TexT($coordX+188.7, $coordY+42.5, "CODE");
		$oPDF->Line($coordX+197.8, $coordY+38, $coordX+197.8, $coordY+47.8, $style1);
		$oPDF->TexT($coordX+198, $coordY+40.1, "REGION");
		$oPDF->TexT($coordX+198, $coordY+42.5, "CODE");
		
		//row 2
		$oPDF->Line($coordX+3.3, $coordY+55.4, $coordX+206.5, $coordY+55.4, $style1);
		$oPDF->SetFont('helvetica', '', 5.8);
		$oPDF->TexT($coordX+4.5, $coordY+50, 'ADDRESS OF EMPLOYER');
		$oPDF->SetFont('helvetica', '', 8.5);
		$oPDF->Text($coordX+7.2, $coordY+54, $comp_info['comp_add']);
		$oPDF->SetFont('helvetica', '', 5.8);
		$oPDF->TexT($coordX+140.6, $coordY+50, 'ZIP CODE');
		$oPDF->TexT($coordX+167, $coordY+50, "TELEPHONE NO/S");
		$oPDF->SetFont('helvetica', '', 8.5);
		$oPDF->Text($coordX+170, $coordY+54, $comp_info['comp_tel']);
		
		//row 3
		
		$oPDF->Line($coordX+3.3, $coordY+62.6, $coordX+206.5, $coordY+62.6, $style1);
		$oPDF->setFont('helvetica', 'B', 7);
		$oPDF->Text($coordX+14.5, $coordY+60, "TIN");
		$oPDF->Line($coordX+29.8, $coordY+55.4, $coordX+29.8, $coordY+203, $style1);
		$oPDF->Text($coordX+37.4, $coordY+58, "DATE OF");
		$oPDF->Text($coordX+39, $coordY+61.7, "BIRTH");
		$oPDF->Line($coordX+56.3, $coordY+55.4, $coordX+56.3, $coordY+213, $style1);
		$oPDF->Text($coordX+95.5, $coordY+58, "NAME OF BORROWERS");
		$oPDF->setFont('helvetica', '', 7);
		$oPDF->text($coordX+56.8, $coordY+61.7, "(Family Name");
		$oPDF->text($coordX+92.5, $coordY+61.7, "First Name");
		$oPDF->text($coordX+130, $coordY+61.7, "Middle Name)");
		$oPDF->Line($coordX+165, $coordY+55.4, $coordX+165, $coordY+203, $style1);
		$oPDF->setFont('helvetica', 'B', 7);
		$oPDF->Text($coordX+168.3, $coordY+58, "Monthly");
		$oPDF->Text($coordX+166, $coordY+61.7, "Amortization");
		$oPDF->Line($coordX+181.7, $coordY+55.4, $coordX+181.7, $coordY+253, $style1);
		$oPDF->Text($coordX+183, $coordY+58, "USE");
		$oPDF->Text($coordX+182, $coordY+61.7, "CODE");
		$oPDF->Line($coordX+189.7, $coordY+55.4, $coordX+189.7, $coordY+203, $style1);
		$oPDF->Text($coordX+192, $coordY+60, "REMARKS");
		
		//---------------INPUT--------------//
		$count_emp = count($arrData);
		$c = 65.5;
		$b = 1;
		$pageTotalAmortization = 0;
		
		$pagenum = 1;
		
		
		if ($count_emp>40) {
			$pagenum++;
			
		}
		$totalpage = $pagenum;
		
		
		$oPDF->setFont('helvetica', '', 8);
		for ($x=0;$x<$count_emp;$x++) {
			
			$oPDF->Text($coordX+8, $c, $arrData[$x]['emp_info']['pi_tin']);
			$oPDF->Text($coordX+36, $c, $arrData[$x]['emp_info']['bdate']);
			$oPDF->Text($coordX+56.5, $c, $b.")".$arrData[$x]['emp_info']['pi_lname']);
			$oPDF->Text($coordX+92.5, $c, $arrData[$x]['emp_info']['pi_fname']);
			$oPDF->Text($coordX+130, $c, $arrData[$x]['emp_info']['pi_mnamefull']);
			$oPDF->Text($coordX+168, $c, $this->MoneyFormat($arrData[$x]['collection']['payment']));
			$pageTotalAmortization += $arrData[$x]['collection']['payment'];
			$b++;
			$c+=3.5;
		}
		$grandTotal = $pageTotalAmortization;
		
		//=========
		//$oPDF->SetXY($coordX+20, $coordY+20);
        //$oPDF->MultiCell($oPDF->getPageWidth(), 3,'asdasdasdasd',0,'C',0, 0, 0, 0, false, 0, false);
		$oPDF->Line($coordX+3.3, $coordY+203, $coordX+206.5, $coordY+203, $style1);
		
		//row 4
		$oPDF->Line($coordX+3.3, $coordY+213, $coordX+181.7, $coordY+213, $style1);
		$oPDF->SetFont('helvetica', '', 5.8);
		$oPDF->Text($coordX+4.5, $coordX+205.5, "No. of");
		$oPDF->Text($coordX+4.5, $coordX+208, "employees");
		$oPDF->Text($coordX+4.5, $coordX+211, "on this page");
		$oPDF->SetFont('helvetica', '', 16);
		$oPDF->Text($coordX+36, $coordY+210, $x);
		$oPDF->Line($coordX+70, $coordY+203, $coordX+70, $coordY+213, $style1);
		$oPDF->Text($coordX+85, $coordY+210, $x);
		$oPDF->Line($coordX+19, $coordY+203, $coordX+19, $coordY+213, $style1);
		$oPDF->SetFont('helvetica', '', 5.8);
		$oPDF->Text($coordX+57, $coordX+205.5, "No. of");
		$oPDF->Text($coordX+57, $coordX+208, "employees");
		$oPDF->Text($coordX+57, $coordX+211, "on this page");
		$oPDF->Line($coordX+105, $coordY+203, $coordX+105, $coordY+253, $style1);
		$oPDF->Line($coordX+108, $coordY+203, $coordX+108, $coordY+253, $style1);
		$oPDF->setFont('helvetica', '', 6);
		$oPDF->Text($coordX+109, $coordY+207, "TOTAL FOR");
		$oPDF->Text($coordX+109, $coordY+209.5, "THIS PAGE");
		$oPDF->Line($coordX+128, $coordY+203, $coordX+128, $coordY+213, $style1);
		$oPDF->Line($coordX+165, $coordY+203, $coordX+165, $coordY+213, $style1);
		$oPDF->setFont('helvetica', '', 7);
		$oPDF->Text($coordX+168, $coordY+209, "P".$this->moneyFormat($pageTotalAmortization));
		$oPDF->Text($coordX+168, $coordY+219, "P".$this->moneyFormat($grandTotal));
		
		//left footer
		$oPDF->Line($coordX+3.3, $coordY+217, $coordX+105, $coordY+217, $style1);
		$oPDF->setFont('helvetica', '', 7);
		$oPDF->Text($coordX+4, $coordY+220, "PFR No.");
		$oPDF->Line($coordX+37, $coordY+217, $coordX+37, $coordY+229, $style1);
		$oPDF->Text($coordX+38, $coordY+220, "DATE");
		$oPDF->Text($coordX+40, $coordY+224, "MM");
		$oPDF->Line($coordX+47, $coordY+221, $coordX+47, $coordY+229, $style1);
		$oPDF->Text($coordX+50, $coordY+224, "DD");
		$oPDF->Line($coordX+57, $coordY+221, $coordX+57, $coordY+229, $style1);
		$oPDF->Text($coordX+60, $coordY+224, "YY");
		$oPDF->Line($coordX+70, $coordY+217, $coordX+70, $coordY+229, $style1);
		$oPDF->Text($coordX+71, $coordY+220, "AMOUNT");
		$oPDF->Line($coordX+3.3, $coordY+229, $coordX+108, $coordY+229, $style1);
		$oPDF->Text($coordX+4, $coordY+233, "COLLECTING BANK");
		$oPDF->Line($coordX+70, $coordY+217, $coordX+70, $coordY+240, $style1);
		$oPDF->Text($coordX+71, $coordY+233, "REMARKS");
		$oPDF->Line($coordX+3.3, $coordY+240, $coordX+108, $coordY+240, $style1);
		$oPDF->Text($coordX+4, $coordY+243, "TICKET DATE");
		$oPDF->Text($coordX+6, $coordY+248, "MM");
		$oPDF->Line($coordX+13, $coordY+245, $coordX+13, $coordY+253, $style1);
		$oPDF->Text($coordX+16, $coordY+248, "DD");
		$oPDF->Line($coordX+23, $coordY+245, $coordX+23, $coordY+253, $style1);
		$oPDF->Text($coordX+26, $coordY+248, "YY");
		$oPDF->Line($coordX+37, $coordY+240, $coordX+37, $coordY+253, $style1);
		$oPDF->Text($coordX+38, $coordY+243, "RECONCILED BY");
		$oPDF->Line($coordX+70, $coordY+240, $coordX+70, $coordY+253, $style1);
		$oPDF->Text($coordX+71, $coordY+243, "CHECKED BY");
		
		//Right Footer
		$oPDF->setFont('helvetica', '', 6);
		$oPDF->Text($coordX+109, $coordY+217, "GRAND TOTAL");
		$oPDF->setFont('helvetica', '', 5.8);
		$oPDF->Text($coordX+109, $coordY+219.5, "(if last page)");
		$oPDF->Line($coordX+128, $coordY+213, $coordX+128, $coordY+222, $style1);
		$oPDF->Line($coordX+165, $coordY+213, $coordX+165, $coordY+222, $style1);
		$oPDF->Line($coordX+108, $coordY+222, $coordX+181.7, $coordY+222, $style1);
		$oPDF->setFont('helvetica', 'B', 8.5);
		$oPDF->Text($coordX+126, $coordY+227, "CERTIFIED CORRECT BY:");
		$oPDF->Line($coordX+108, $coordY+229, $coordX+181.7, $coordY+229, $style1);
		$oPDF->setFont('helvetica', '', 7);
		$oPDF->Text($coordX+109, $coordY+232, "SIGNATURE OVER PRINTED NAME");
		$oPDF->Line($coordX+108, $coordY+240, $coordX+206.5, $coordY+240, $style1);
		$oPDF->Text($coordX+109, $coordY+243, "OFFICIAL DESIGNATION");
		
		$oPDF->setFont('helvetica', 'B', 10);
		$oPDF->Text($coordX+188, $coordY+208, "CODES");
		$oPDF->setFont('helvetica', '', 7);
		$oPDF->Text($coordX+183, $coordY+215, "A -  Resigned/");
		$oPDF->Text($coordX+188.5, $coordY+219, "Separated");
		$oPDF->Text($coordX+183, $coordY+223, "B -  Deceased");
		$oPDF->Text($coordX+183, $coordY+227, "C -  Retired");
		$oPDF->Text($coordX+183, $coordY+231, "D -  Leave w/o pay");
		$oPDF->Text($coordX+183, $coordY+235, "E -  Others");
		$oPDF->setFont('helvetica', 'I', 7);
		$oPDF->Text($coordX+195, $coordY+235, "(Specify)");
		$oPDF->Line($coordX+181.7, $coordY+243, $coordX+206.5, $coordY+243, $style1);
		$oPDF->setFont('helvetica', '', 4.5);
		$oPDF->Text($coordX+182, $coordY+242, "PAGE NO.");
		$oPDF->Line($coordX+194.5, $coordY+240, $coordX+194.5, $coordY+253, $style1);
		$oPDF->Text($coordX+195, $coordY+242, "NO. OF PAGES");
		$oPDF->setFont('helvetica', 'B', 20);
		$oPDF->Text($coordX+186, $coordY+250, $pagenum);
		$oPDF->Text($coordX+198, $coordY+250, $totalpage);
		
		$oPDF->setFont('helvetica', 'B', 8.5);
		$oPDF->Text($coordX+68, $coordY+257, "THIS FORM CAN BE REPRODUCED. NOT FOR SALE");
		
		
		
		
		
		
		$output = $oPDF->Output("HDMFP24_".$pData['month'].$pData['year']);
		if (!empty($output)) {
			return $output;
		}
		
	}
	 
	function getHDMFP24($pData = array(), $arrData = array(), $comp_info = array()){
		$paper = 'PA4';
		$orientation = 'portrait';
		$content = '';
		$ctr = 0;
		$max_per_page = 40;
		$item = 1;
		$size = count($arrData);
		$page = 1;
		$max_page = ceil($size/$max_per_page);
		if(count($arrData)>0){
            foreach($arrData as $key => $val){
			if($ctr == 0){
				$pageTotalAmortization = 0;
			}
		$pageTotalAmortization += $val['collection']['payment'];
		$grandTotalAmortization += $val['collection']['payment'];
		$header = '<style type="text/css">
						@page { margin: 0.7em;} 
					</style>
					<table style="border-collapse:collapse"><tr><td>
					<table style="border-collapse:collapse; font-family:Helvetica; font-size:12px;" width="790px">
						<tr>
							<td rowspan="3" width="150px"><img src="'.SYSCONFIG_CLASS_PATH.'util/dompdf/images/pdf_report/pag-ibig_logo.jpg" width="80px" height="90px" width="100px"></td>
							<td style="font-size:27px; padding-left:30px;" width="475px"><strong>MONTHLY REMITTANCE SCHEDULE</strong></td>
							<td align="right" width="100px" style="vertical-align:bottom;"><strong>Pag-IBIG Fund</strong></td>
						</tr>
						<tr>
							<td style="font-size:18px; padding-left:140px;"><strong>FOR MULTI-PURPOSE LOAN</strong></td>
							<td style="font-size:24px; padding-left:55px; vertical-align:top;"><strong>P2-4</strong></td>
						</tr>
						<tr>
							<td style="padding-top:10px; padding-left:40px; font-size:10px;" align="left">
								<table style="border-collapse:collapse;">
									<tr>
										<td style="border:1px solid black; font-size:16px;" width="20px" align="center">X</td>
										<td style="padding-left:3px; vertical-align:bottom; padding-right:62px;">PRIVATE EMPLOYER</td>
										<td style="border:1px solid black; font-size:16px;" width="20px" align="center">&nbsp;</td>
										<td style="padding-left:3px; vertical-align:bottom;">GOVERNMENT CONTROLLED CORP.</td>
									</tr>
								</table>
							</td>
							<td align="center" style="border-top:1px solid black; border-right:1px solid black; border-left:1px solid black;" width="100px">
								<table style="border-collapse:collapse;">
									<tr>
										<td width="80px">Month</td>
										<td>Year</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td style="font-size:6px;">Atrium of Makati, Makati Ave. Makati City<br>Tel. No.: 811-4401 to 27 <i>(Connecting all Departments)</i></td>
							<td style="padding-top:10px; padding-left:40px; font-size:10px;" align="left">
								<table style="border-collapse:collapse;">
									<tr>
										<td style="border:1px solid black; font-size:16px;" width="20px" align="center">&nbsp;</td>
										<td style="padding-left:3px; vertical-align:bottom; padding-right:30px;">LOCAL GOVERNMENT UNIT</td>
										<td style="border:1px solid black; font-size:16px;" width="20px" align="center">&nbsp;</td>
										<td style="padding-left:3px; vertical-align:bottom;">NATIONAL GOVERNMENT AGENCY</td>
									</tr>
								</table>
							</td>
							<td align="center" style="border-right:1px solid black; border-left:1px solid black;" width="100px">
								<table style="border-collapse:collapse;">
									<tr>
										<td width="80px">&nbsp;&nbsp;&nbsp;&nbsp;'.$pData['month'].'</td>
										<td align="center">'.$pData['year'].'</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
					<table style="border-collapse:collapse; font-family:Helvetica; font-size:12px; border-top:1px solid black; border-right:1px solid black; border-left:1px solid black;" width="792px">
						<tr>
							<td colspan="4" width="450px" style="font-size:8px; padding-left:5px;">NAME OF EMPLOYER</td>
							<td style="border-right:1px solid black; font-size:8px; padding-left:5px;" width="70px" rowspan="2">FOR PRIVATE<br>EMPLOYER</td>
							<td style="border-right:1px solid black; vertical-align:top; font-size:8px;" width="100px">EMPLOYER SSS NUMBER</td>
							<td style="border-right:1px solid black; font-size:8px;" rowspan="2">FOR GOV\'T<br>EMPLOYER</td>
							<td style="border-right:1px solid black; font-size:8px;">AGENCY CODE</td>
							<td style="border-right:1px solid black; font-size:8px;">BRANCH CODE</td>
							<td style="border-right:1px solid black; font-size:8px;">REGION CODE</td>
						</tr>
						<tr>
							<td colspan="4" width="435px" style="padding-left:15px;">'.$comp_info['comp_name'].'</td>
							<td style="border-right:1px solid black; vertical-align:top; padding-left:15px;" width="85px">'.$comp_info['comp_sss'].'</td>
							<td style="border-right:1px solid black;">&nbsp;</td>
							<td style="border-right:1px solid black;">&nbsp;</td>
							<td style="border-right:1px solid black;">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="5" style="font-size:8px; border-top:1px solid black;">ADDRESS OF EMPLOYER</td>
							<td style="font-size:8px; border-top:1px solid black; border-right:1px solid black;">ZIP CODE</td>
							<td colspan="4" style="font-size:8px; border-top:1px solid black;">TELEPHONE NO/S.</td>
						</tr>
						<tr>
							<td colspan="5" style="padding-left:15px;">'.$comp_info['comp_add'].'</td>
							<td style="border-right:1px solid black;">'.$comp_info['comp_zipcode'].'</td>
							<td colspan="4" style="padding-left:15px;">'.$comp_info['comp_tel'].'</td>
						</tr>
					</table>
					<table style="border-collapse:collapse; font-family:Helvetica; font-size:10px; border:1px solid black;" width="792px">
						<tr>
							<td width="100px" align="center" rowspan="2" style="border-right:1px solid black;"><strong>TIN</strong></td>
							<td width="100px" align="center" style="border-right:1px solid black;"><strong>DATE OF</strong></td>
							<td colspan="3" align="center" style="border-right:1px solid black;"><strong>NAME OF BORROWERS</strong></td>
							<td width="63px" align="center" style="border-right:1px solid black;"><strong>Monthly</strong></td>
							<td width="10px" align="center" style="border-right:1px solid black;"><strong>USE</strong></td>
							<td align="center" rowspan="2" style="border-right:1px solid black;"><strong>REMARKS</strong></td>
						</tr>
						<tr>
							<td align="center" style="border-right:1px solid black;"><strong>BIRTH</strong></td>
							<td align="left" width="135px">(Family Name</td>
							<td align="left" width="146px">First Name</td>
							<td align="left" width="135px" style="border-right:1px solid black;">Middle Name)</td>
							<td align="center" style="border-right:1px solid black;"><strong>Amortization</strong></td>
							<td width="10px" align="center" style="border-right:1px solid black;"><strong>CODE</strong></td>
						</tr>';
				$bgcolor = $ctr%2;
				if($bgcolor == 0){
					$color = '#c0c0c0';
				} else {
					$color = 'white';
				}
					$main ='<tr>
								<td align="center" style="border-right:1px solid black; border-top:1px solid black; padding:1px 0; background-color:'.$color.';">'.$val['emp_info']['pi_tin'].'</td>
								<td align="center" style="border-right:1px solid black; border-top:1px solid black; background-color:'.$color.';">'.$val['emp_info']['bdate'].'</td>
								<td align="left" style="border-top:1px solid black; background-color:'.$color.';">'.$item.')'.$val['emp_info']['pi_lname'].'</td>
								<td align="left"style="border-top:1px solid black; background-color:'.$color.';">'.$val['emp_info']['pi_fname'].'</td>
								<td align="left" style="border-right:1px solid black; border-top:1px solid black; background-color:'.$color.';">'.$val['emp_info']['pi_mnamefull'].'</td>
								<td style="border-right:1px solid black; border-top:1px solid black; background-color:'.$color.';" align="right">'.$this->moneyFormat($val['collection']['payment']).'</td>
								<td style="border-right:1px solid black; border-top:1px solid black; background-color:'.$color.';" align="center">&nbsp;</td>
								<td style="border-top:1px solid black; background-color:'.$color.';">&nbsp;</td>
							</tr>';				
			$footer ='</table>
					  	<table style="border-collapse:collapse; font-family:Helvetica; font-size:10px; border-bottom:1px solid black; border-right:1px solid black; border-left:1px solid black;" width="792px">
							<tr>
								<td width="49%"><table style="border-collapse:collapse;" width="49%">
									<tr>
										<td style="border-right:1px solid black; font-size:8px; padding-left:2px;" width="50px">No. of employees on this page</td>
										<td style="border-right:1px solid black; padding-left:2px; font-size:20px;" width="147px" align="center">'.$item.'</td>
										<td style="border-right:1px solid black; font-size:8px; padding-left:2px;" width="40px">No. of employees if last page</td>
										<td style="border-right:1px solid black; padding-left:2px; font-size:20px;" width="150px" align="center">'.$item.'</td>
										<td style="border-right:1px solid black; border-bottom:1px solid black;" width="10px">&nbsp;</td>
									</tr>
									<tr>
										<td style="border-right:1px solid black; border-top:1px solid black;" colspan="4">&nbsp;</td>
										<td style="border-right:1px solid black;" width="10px">&nbsp;</td>
									</tr>
									<tr>
										<td style="border-right:1px solid black; border-top:1px solid black;" colspan="4">
											<table style="border-collapse:collapse;">
												<tr>
													<td style="border-right:1px solid black; padding-left:2px;" width="120px">PFR No.</td>
													<td style="border-right:1px solid black; padding-left:2px;" width="119px">DATE</td>
													<td style="padding-left:2px;" width="70px">AMOUNT</td>
												</tr>
												<tr>
													<td style="border-right:1px solid black; padding-left:2px;">&nbsp;</td>
													<td style="border-right:1px solid black; padding-left:2px;">
														<table width="99px" style="border-collapse:collapse;">
															<tr><td width="33px" align="center" style="border-right:1px solid black;">MM<br>&nbsp;</td><td width="33px" align="center" style="border-right:1px solid black;">DD<br>&nbsp;</td><td width="33px" align="center">YY<br>&nbsp;</td></tr>
														</table>
													</td>
													<td>&nbsp;</td>
												</tr>
											</table>
										</td>
										<td style="border-right:1px solid black; border-bottom:1px solid black;" width="10px">&nbsp;</td>
									</tr>
									<tr>
										<td style="border-right:1px solid black; border-top:1px solid black; padding-left:2px; padding:5px 0;" colspan="3">COLLECTING BANK</td>
										<td style="border-right:1px solid black; border-top:1px solid black; padding-left:2px;">REMARKS</td>
										<td style="border-right:1px solid black;" width="10px">&nbsp;</td>
									</tr>
									<tr>
										<td style="border-right:1px solid black; padding-left:2px;" colspan="3">&nbsp;</td>
										<td style="border-right:1px solid black; padding-left:2px;">&nbsp;</td>
										<td style="border-right:1px solid black; border-bottom:1px solid black;" width="10px">&nbsp;</td>
									</tr>
									<tr>
										<td style="border-right:1px solid black; border-top:1px solid black;" colspan="4">
											<table style="border-collapse:collapse;">
												<tr>
													<td style="border-right:1px solid black; padding-left:2px;" width="120px">TICKET DATE</td>
													<td style="border-right:1px solid black; padding-left:2px;" width="119px">RECONCILED BY</td>
													<td style="padding-left:2px;" width="70px">CHECKED BY</td>
												</tr>
												<tr>
													<td style="border-right:1px solid black; padding-left:2px;">
														<table width="99px" style="border-collapse:collapse;">
															<tr><td width="33px" align="center" style="border-right:1px solid black;">MM<br>&nbsp;</td><td width="33px" align="center" style="border-right:1px solid black;">DD<br>&nbsp;</td><td width="33px" align="center">YY<br>&nbsp;</td></tr>
														</table>
													</td>
													<td style="border-right:1px solid black; padding-left:2px;">&nbsp;</td>
													<td>&nbsp;</td>
												</tr>
											</table>
										</td>
										<td style="border-right:1px solid black;" width="10px">&nbsp;</td>
									</tr>
								</table></td>
								<td width="34.5%" style="border-right:1px solid black;">
									<table style="border-collapse:collapse;">
										<tr>
											<td style="border-right:1px solid black; font-size:8px; padding:6px 0;" width="65px">TOTAL FOR<br>THIS PAGE</td>
											<td style="border-right:1px solid black;" width="132px">&nbsp;</td>
											<td width="60px" align="right">&nbsp;P'.$this->moneyFormat($pageTotalAmortization).'</td>
										</tr>
										<tr>
											<td style="border-right:1px solid black; border-top:1px solid black; font-size:8px; padding:6px 0;">GRAND TOTAL<span style="font-size:8px;">(if last page)</span></td>
											<td style="border-right:1px solid black; border-top:1px solid black;">&nbsp;</td>
											<td style="border-top:1px solid black;" align="right">&nbsp;P'.$this->moneyFormat($grandTotalAmortization).'</td>
										</tr>
										<tr>
											<td style="border-top:1px solid black; font-size:12px; padding:6px 0;" colspan="3" align="center"><strong>CERTIFIED CORRECT BY:</strong></td>
										</tr>
										<tr>
											<td style="border-top:1px solid black;" colspan="3">SIGNATURE OVER PRINTED NAME</td>
										</tr>
										<tr>
											<td colspan="3" align="center" style="padding:5px 0;">&nbsp;</td>
										</tr>
										<tr>
											<td style="border-top:1px solid black;" colspan="3">OFFICIAL DESIGNATION</td>
										</tr>
										<tr>
											<td colspan="3" align="center">&nbsp;</td>
										</tr>
									</table>
								</td>
								<td width="14.5%">
									<table style="border-collapse:collapse;" width="100%">
										<tr><td colspan="2" align="center" style="font-size:14px; padding-top:3px;"><strong>CODES</strong></td></tr>
										<tr><td colspan="2" style="padding:11px 0;">
											<table style="font-size:10px; border-collapse:collapse;" align="center">
												<tr>
													<td>A</td>
													<td> - </td>
													<td>Resigned/</td>
												</tr>
												<tr>
													<td>&nbsp;</td>
													<td>&nbsp;</td>
													<td>Separated</td>
												</tr>
												<tr>
													<td>B</td>
													<td> - </td>
													<td>Deceased</td>
												</tr>
												<tr>
													<td>C</td>
													<td> - </td>
													<td>Retired</td>
												</tr>
												<tr>
													<td>D</td>
													<td> - </td>
													<td>Leave w/o pay</td>
												</tr>
												<tr>
													<td>E</td>
													<td> - </td>
													<td>Others<i>(specify)</i></td>
												</tr>
											</table>
										</td></tr>
										<tr>
											<td style="border-top:1px solid black; border-right:1px solid black; font-size:6px;">PAGE NO.</td>
											<td style="border-top:1px solid black; font-size:6px;">NO. OF PAGES</td>
										</tr>
										<tr>
											<td style="border-top:1px solid black; border-right:1px solid black; font-size:27px;" align="center">'.$page.'</td>
											<td style="border-top:1px solid black; font-size:27px;" align="center">'.$max_page.'</td>
										</tr>
									</table>
								</td>
							</tr>
					  	</table>
					  	</td></tr>
					  	<tr><td style="font:bold 12px Helvetica;" width="100%" align="center">THIS FORM CAN BE REPRODUCED. NOT FOR SALE.</td></tr>
					  	</table>
					  	';
			if($ctr == 0){
				$content .= $header;
				$content .= $main;
				$ctr++;
			} elseif($ctr == $size){
				$content .= $main;
				$content .= $footer;
				$page++;
				$ctr = 0;
			} else {
				$content .= $main;
				$ctr++;
			}
			$item++;
		}}
		if(($item-1) == $size and $ctr != $max_per_page){
				while($ctr < $max_per_page){
				$bgcolor = $ctr%2;
				if($bgcolor == 0){
					$color = '#c0c0c0';
				} else {
					$color = 'white';
				}
					$content .= '<tr>
								<td align="center" style="border-right:1px solid black; border-top:1px solid black; padding:1px 0; background-color:'.$color.';">&nbsp;</td>
								<td align="center" style="border-right:1px solid black; border-top:1px solid black; background-color:'.$color.';">&nbsp;</td>
								<td align="left" style="border-top:1px solid black; background-color:'.$color.';">'.$item.')&nbsp;</td>
								<td align="left"style="border-top:1px solid black; background-color:'.$color.';">&nbsp;</td>
								<td align="left" style="border-right:1px solid black; border-top:1px solid black; background-color:'.$color.';">&nbsp;</td>
								<td style="border-right:1px solid black; border-top:1px solid black; background-color:'.$color.';">&nbsp;</td>
								<td style="border-right:1px solid black; border-top:1px solid black; background-color:'.$color.';" align="center">&nbsp;</td>
								<td style="border-top:1px solid black; background-color:'.$color.';">&nbsp;</td>
							</tr>';
					
					$ctr++;
					$item++;
				}
		    	if($ctr >= $max_per_page){
		    		$page++;
					$content .= $footer;
				}
			}
		$this->createPDF($content, $paper, $orientation, $filename);
		//echo $content; echo $item; echo $ctr;
	}
}

?>