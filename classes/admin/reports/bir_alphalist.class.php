<?php
/**
 * Initial Declaration
 */

$month = array(
	01 => 01,
	02 => 02,
	03 => 03,
	04 => 04,
	05 => 05,
	06 => 06,
	07 => 07,
	08 => 08,
	09 => 09,
	10 => 10,
	11 => 11,
	12 => 12
);

require_once(SYSCONFIG_CLASS_PATH."util/pdf.class.php");
require_once(SYSCONFIG_CLASS_PATH."util/export-xls.class.php");
require_once(SYSCONFIG_CLASS_PATH.'admin/reports/sss.class.php');
require_once(SYSCONFIG_CLASS_PATH."util/PHPExcel.php");
require_once(SYSCONFIG_CLASS_PATH."util/PHPExcel/IOFactory.php");


/**
 * Class Module
 *
 * @author Jason Mabignay
 *
 */
 
class clsBIRAlphalist {

	var $conn;
	var $fieldMap;
	var $Data;
	var $month = array(
					'01' => '01',
					'02' => '02',
					'03' => '03',
					'04' => '04',
					'05' => '05',
					'06' => '06',
					'07' => '07',
					'08' => '08',
					'09' => '09',
					'10' => '10',
					'11' => '11',
					'12' => '12'
					);
	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsBIRAlphalist object
	 */
	 
	function clsBIRAlphalist($dbconn_ = NULL) {
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
	function dbFetch() {
		$sql = "SELECT * FROM payroll_ps_account ORDER BY psa_id";
		$rsResult = $this->conn->Execute($sql);
		while (!$rsResult->EOF) {
			$arrData[] =  $rsResult->fields;
            $rsResult->MoveNext();
		}
        return $arrData;
	}
	
	function dbFetchRemittanceSummary($gData = array()) {
		$total_remitted = "";
		
		foreach ($this->month as $keymonth => $valmonth) {
			$arrData[$keymonth] = $this->getPayperiod($valmonth,$gData['year']);
			
			foreach ($arrData[$keymonth] as $key => $val) {
				$total = "";
				foreach ($val as $keyval => $valkey) {
					// sum of all tax withheld
					$total += $valkey['sum_cont'];
				}
				$arrData[$keymonth]['tax_withheld'] = number_format($total,2);
				$arrData[$keymonth]['ror'] = "";
				$arrData[$keymonth]['remittance_date'] =$this->getRemittanceMonth($keymonth);
				$arrData[$keymonth]['adjustments'] = "";
				$arrData[$keymonth]['stotal_remitted'] = number_format($total-$arrData[$keymonth]['adjustments'],2);
				
				$total_adj += $arrData[$keymonth]['adjustments'];
				$total_withheld += $total; 
				$total_remitted +=($total-$arrData[$keymonth]['adjustments']);
			}
		}
		$arrData['total_adj'] = number_format($total_adj,2);
		$arrData['total_withheld'] = number_format($total_withheld,2);
		
		$total_remitted == NULL ? $total_remitted = '0.00' : $total_remitted;
		$arrData['total_remitted'] = number_format($total_remitted,2);
		$arrData['year'] = $gData['year'];
//		printa($arrData);
		return $arrData;
	}
	
	function getPayperiod($month = "", $year = "") {
		$arrData = array();
		
		if ($month == "") {
			return $arrData;
		}
		
		if ($year == "") {
			return $arrData;
		}
		
		/**$begin_month = $year."-".$month."-01"." 00:00:00";
		$end_month = date('Y-m-d',dDate::getEndMonthEpoch(dDate::parseDateTime($begin_month)))." 23:59:59";

		$sql = "SELECT a.payperiod_id,a.pps_id,a.payperiod_start_date,a.payperiod_end_date,a.payperiod_trans_date,a.pp_stat_id
				FROM payroll_pay_period a
				WHERE a.payperiod_start_date <= '{$begin_month}' AND a.payperiod_end_date >= '{$end_month}' AND a.pp_stat_id = '3'";
		**/
		$sql = "select distinct a.payperiod_id,a.pps_id,a.payperiod_start_date,a.payperiod_end_date,a.payperiod_trans_date,a.pp_stat_id 
				from payroll_paystub_entry ppe
				left join payroll_paystub_report ppr on (ppr.paystub_id=ppe.paystub_id)
				left join payroll_pay_period a on (a.payperiod_id=ppr.payperiod_id)
				WHERE a.payperiod_period='{$month}' and a.payperiod_period_year='{$year}'";
		$rsResult = $this->conn->Execute($sql);
		
		while (!$rsResult->EOF) {
			$arrData['payperiods'][$rsResult->fields['payperiod_id']] =  $rsResult->fields;
			$arrData['payperiods'][$rsResult->fields['payperiod_id']]['sum_cont'] = $this->getSumContribution($rsResult->fields['payperiod_id']);
            		
			$rsResult->MoveNext();
		}
		
        return $arrData;
	}
	
	function getRemittanceMonth($month = "") {
		switch ($month) {
			case '01':
				return "Febuary";
				break;
			case '02':
				return "March";
				break;
			case '03':
				return "April";
				break;
			case '04':
				return "May";
				break;
			case '05':
				return "June";
				break;
			case '06':
				return "July";
				break;
			case '07':
				return "August";
				break;
			case '08':
				return "September";
				break;
			case '09':
				return "October";
				break;	
			case '10':
				return "November";
				break;
			case '11':
				return "December";
				break;
				
			default:
				return "January";
				break;
		}
	}
	
	function getSumContribution($payperiod_id_ = "") {
		if ($payperiod_id_ == "") {
			return 0;
		}
		
		$sql = "SELECT SUM(b.ppe_amount) AS ppe_amount
				FROM payroll_pay_stub a
                INNER JOIN payroll_paystub_entry b ON (a.paystub_id = b.paystub_id)
                INNER JOIN payroll_paystub_report c ON (a.paystub_id = c.paystub_id)
                WHERE a.payperiod_id = {$payperiod_id_} AND b.psa_id = 8 AND c.ppr_status = 1 AND ppr_isdeleted = 0";
		
		$rsResult = $this->conn->Execute($sql);
		if (!$rsResult->EOF) {
			return $rsResult->fields['ppe_amount'];
		}
	}

    function getPDFResult($gData = array()) {
        $orientation='P';
        $unit='mm';
        $format='LEGAL';
        $unicode=true;
        $encoding="UTF-8";

        $oPDF = new clsPDF($orientation, $unit, $format, $unicode, $encoding);

        // set auto page break to false so that we can control the page break
        // depending on the desired number of lines on the ouput
        $oPDF->SetAutoPageBreak(false);
        // use a freeserif font as a default font
        $oPDF->SetFont('freeserif','',10);

        // suppress print header and footer
        $oPDF->setPrintHeader(false);
        $oPDF->setPrintFooter(false);

        // get a modules list from the database
        $arrUserTypeList = $this->dbFetch();

        // set initial coordinates
        $coordX = 0;
        $coordY = 0;

        // set initial pdf page
        $oPDF->AddPage();

//        $oPDF->Image(SYSCONFIG_THEME_PATH."default/images/admin/alphalist.png",0,0,$oPDF->getPageWidth(),$oPDF->getPageHeight());
        $oPDF->Image(SYSCONFIG_THEME_PATH."default/images/admin/1604-CF-2008.jpg",$coordX,$coordY,$oPDF->getPageWidth(),$oPDF->getPageHeight());
        
    	$sql = "SELECT comp_name, comp_tin, comp_add, comp_tel, comp_zipcode FROM company_info WHERE comp_id = {$gData['comp']}";
    	$rsResult = $this->conn->Execute($sql);
    	$company_name = strtoupper($rsResult->fields['comp_name']);
    	$company_tin = trim($rsResult->fields['comp_tin']);
    	$company_add = $rsResult->fields['comp_add'];
    	$company_tel = $rsResult->fields['comp_tel'];
    	$company_zipcode = $rsResult->fields['comp_zipcode'];
		
    	// Company Name
        $oPDF->Text($coordX+21.8,$coordY+58.6,$company_name);
        
        // Company Address
        $oPDF->SetFontSize(7.9);
        $oPDF->Text($coordX+22,$coordY+67.3,$company_add);
        
        // Company Telephone No
        $oPDF->SetFontSize(12);
        $oPDF->Text($coordX+175.8,$coordY+58.8,$company_tel[0]);
        $oPDF->Text($coordX+179.6,$coordY+58.8,$company_tel[1]);
        $oPDF->Text($coordX+183.6,$coordY+58.8,$company_tel[2]);
        $oPDF->Text($coordX+188,$coordY+58.8,$company_tel[3]);
        $oPDF->Text($coordX+192.2,$coordY+58.8,$company_tel[4]);
        $oPDF->Text($coordX+196.2,$coordY+58.8,$company_tel[5]);
        $oPDF->Text($coordX+200,$coordY+58.8,$company_tel[6]);
        
        // Zip Code
        $oPDF->Text($coordX+184,$coordY+67.7,$company_zipcode[0]);
        $oPDF->Text($coordX+189.2,$coordY+67.7,$company_zipcode[1]);
        $oPDF->Text($coordX+194.7,$coordY+67.7,$company_zipcode[2]);
        $oPDF->Text($coordX+199.4,$coordY+67.7,$company_zipcode[3]);
        
        // Category of Withholding Agent
        $oPDF->SetFontSize(6);
        $oPDF->Text($coordX+153.5,$coordY+81.3,'X');
		
        // TIN
        $oPDF->SetFontSize(12);
        if (strstr($company_tin, "-")) {
        	$tin = explode('-',$company_tin);
        	$tin = $tin[0].$tin[1].$tin[2];
        } elseif (strstr($company_tin, " ")) {
        	$tin = explode(' ',$company_tin);
        	$tin = $tin[0].$tin[1].$tin[2];
        } else {
			$tin = $company_tin;
        }
        
        $add_tin = 49.9;
        $oPDF->Text($coordX+21.8,$coordY+$add_tin,$tin[0]);
        $oPDF->Text($coordX+26,$coordY+$add_tin,$tin[1]);
        $oPDF->Text($coordX+30.2,$coordY+$add_tin,$tin[2]);
        $oPDF->Text($coordX+39,$coordY+$add_tin,$tin[3]);
        $oPDF->Text($coordX+43,$coordY+$add_tin,$tin[4]);
        $oPDF->Text($coordX+47,$coordY+$add_tin,$tin[5]);
        $oPDF->Text($coordX+56.5,$coordY+$add_tin,$tin[6]);
        $oPDF->Text($coordX+60.5,$coordY+$add_tin,$tin[7]);
        $oPDF->Text($coordX+64.5,$coordY+$add_tin,$tin[8]);
        $oPDF->Text($coordX+73.3,$coordY+$add_tin,0);
        $oPDF->Text($coordX+77.3,$coordY+$add_tin,0);
        $oPDF->Text($coordX+81.3,$coordY+$add_tin,0);
        $oPDF->Text($coordX+85.2,$coordY+$add_tin,0);
        
        
        $summary_cont = $this->dbFetchRemittanceSummary($gData);
        $oPDF->SetFontSize(8);
        $add_date = 28;
        $add_tax = 77;
        $add_stotal_remitted = 172.8;
        
        // Jan
        $add_jan = 98.6;
        //$oPDF->Text($coordX+$add_date,$coordY+$add_jan,$summary_cont['01']['remittance_date']);
        $oPDF->Text($coordX+$add_tax,$coordY+$add_jan,$summary_cont['01']['tax_withheld']);
        //$oPDF->Text($coordX+$add_stotal_remitted,$coordY+$add_jan,$summary_cont['01']['stotal_remitted']);
        // Feb
        $add_feb = 101.7;
        //$oPDF->Text($coordX+$add_date,$coordY+$add_feb,$summary_cont['02']['remittance_date']);
        $oPDF->Text($coordX+$add_tax,$coordY+$add_feb,$summary_cont['02']['tax_withheld']);
        //$oPDF->Text($coordX+$add_stotal_remitted,$coordY+$add_feb,$summary_cont['02']['stotal_remitted']);
        // Mar
        $add_mar = 105;
        //$oPDF->Text($coordX+$add_date,$coordY+$add_mar,$summary_cont['03']['remittance_date']);
        $oPDF->Text($coordX+$add_tax,$coordY+$add_mar,$summary_cont['03']['tax_withheld']);
       // $oPDF->Text($coordX+$add_stotal_remitted,$coordY+$add_mar,$summary_cont['03']['stotal_remitted']);
        // Apr
        $add_apr = 108.1;
        //$oPDF->Text($coordX+$add_date,$coordY+$add_apr,$summary_cont['04']['remittance_date']);
        $oPDF->Text($coordX+$add_tax,$coordY+$add_apr,$summary_cont['04']['tax_withheld']);
        //$oPDF->Text($coordX+$add_stotal_remitted,$coordY+$add_apr,$summary_cont['04']['stotal_remitted']);
        // May
        $add_may = 111.3;
        //$oPDF->Text($coordX+$add_date,$coordY+$add_may,$summary_cont['05']['remittance_date']);
        $oPDF->Text($coordX+$add_tax,$coordY+$add_may,$summary_cont['05']['tax_withheld']);
        //$oPDF->Text($coordX+$add_stotal_remitted,$coordY+$add_may,$summary_cont['05']['stotal_remitted']);
		// Jun
		$add_jun = 114.5;
       // $oPDF->Text($coordX+$add_date,$coordY+$add_jun,$summary_cont['06']['remittance_date']);
        $oPDF->Text($coordX+$add_tax,$coordY+$add_jun,$summary_cont['06']['tax_withheld']);
        //$oPDF->Text($coordX+$add_stotal_remitted,$coordY+$add_jun,$summary_cont['06']['stotal_remitted']);
        // Jul
        $add_jul = 117.8;
        //$oPDF->Text($coordX+$add_date,$coordY+$add_jul,$summary_cont['07']['remittance_date']);
        $oPDF->Text($coordX+$add_tax,$coordY+$add_jul,$summary_cont['07']['tax_withheld']);
        //$oPDF->Text($coordX+$add_stotal_remitted,$coordY+$add_jul,$summary_cont['07']['stotal_remitted']);
        // Aug
        $add_aug = 121;
        //$oPDF->Text($coordX+$add_date,$coordY+$add_aug,$summary_cont['08']['remittance_date']);
        $oPDF->Text($coordX+$add_tax,$coordY+$add_aug,$summary_cont['08']['tax_withheld']);
        //$oPDF->Text($coordX+$add_stotal_remitted,$coordY+$add_aug,$summary_cont['08']['stotal_remitted']);
        // Sept
        $add_sept = 124.2;
        //$oPDF->Text($coordX+$add_date,$coordY+$add_sept,$summary_cont['09']['remittance_date']);
        $oPDF->Text($coordX+$add_tax,$coordY+$add_sept,$summary_cont['09']['tax_withheld']);
        //$oPDF->Text($coordX+$add_stotal_remitted,$coordY+$add_sept,$summary_cont['09']['stotal_remitted']);
        // Oct
        $add_oct = 127.4;
        //$oPDF->Text($coordX+$add_date,$coordY+$add_oct,$summary_cont['10']['remittance_date']);
        $oPDF->Text($coordX+$add_tax,$coordY+$add_oct,$summary_cont['10']['tax_withheld']);
        //$oPDF->Text($coordX+$add_stotal_remitted,$coordY+$add_oct,$summary_cont['10']['stotal_remitted']);
        // Nov
        $add_nov = 130.65;
        //$oPDF->Text($coordX+$add_date,$coordY+$add_nov,$summary_cont['11']['remittance_date']);
        $oPDF->Text($coordX+$add_tax,$coordY+$add_nov,$summary_cont['11']['tax_withheld']);
        //$oPDF->Text($coordX+$add_stotal_remitted,$coordY+$add_nov,$summary_cont['11']['stotal_remitted']);
        // Dec
        $add_dec = 133.85;
        //$oPDF->Text($coordX+$add_date,$coordY+$add_dec,$summary_cont['12']['remittance_date']);
        $oPDF->Text($coordX+$add_tax,$coordY+$add_dec,$summary_cont['12']['tax_withheld']);
        //$oPDF->Text($coordX+$add_stotal_remitted,$coordY+$add_dec,$summary_cont['12']['stotal_remitted']);
        
        // Totals
        $add_total = 137;
        $oPDF->Text($coordX+$add_tax,$coordY+$add_total,$summary_cont['total_withheld']);
        //$oPDF->Text($coordX+109.5,$coordY+$add_total,$summary_cont['total_adj']);
        //$oPDF->Text($coordX+$add_stotal_remitted,$coordY+$add_total,$summary_cont['total_remitted']);
        
        // Year
        $oPDF->SetFontSize(12);
        $year = $summary_cont['year'];
        
        $add_year = 39.7;
        $oPDF->Text($coordX+46,$coordY+$add_year,$year[0]);
        $oPDF->Text($coordX+50.4,$coordY+$add_year,$year[1]);
        $oPDF->Text($coordX+54.9,$coordY+$add_year,$year[2]);
        $oPDF->Text($coordX+59,$coordY+$add_year,$year[3]);
		
        $oPDF->AddPage();
//        $oPDF->Image(SYSCONFIG_THEME_PATH."default/images/admin/alphalist_page2.jpg",0,0,$oPDF->getPageWidth(),$oPDF->getPageHeight());
        $oPDF->Image(SYSCONFIG_THEME_PATH."default/images/admin/1604-CF-2008_page2.jpg",0,0,$oPDF->getPageWidth(),$oPDF->getPageHeight());
        
        // Get the pdf output
        $output = $oPDF->Output("1604-CF_".$gData['year'].".pdf");
		
        if (!empty($output)) {
            return $output;
        }
		
        return false;
    }
    
function getTransmittal($gData = array()) {
	$orientation='P';
	$unit='mm';
	$format='LEGAL';
	$unicode=true;
	$encoding="UTF-8";

	$oPDF = new clsPDF($orientation, $unit, $format, $unicode, $encoding);

	// set auto page break to false so that we can control the page break
	// depending on the desired number of lines on the output
	$oPDF->SetAutoPageBreak(false);
	// use a freeserif font as a default font
	$oPDF->SetFont('freeserif','',10);

	// suppress print header and footer
	$oPDF->setPrintHeader(false);
	$oPDF->setPrintFooter(false);

	// get a modules list from the database
	$arrUserTypeList = $this->dbFetch();

	// set initial coordinates
	$coordX = 0;
	$coordY = 0;

	// set initial pdf page
	$oPDF->AddPage();

	$oPDF->Image(SYSCONFIG_THEME_PATH."default/images/admin/Transmittal.Jpg",$coordX,$coordY,$oPDF->getPageWidth(),$oPDF->getPageHeight());

	//echo $sql = "SELECT comp_name, comp_tin, comp_add, com_tel FROM company_info WHERE compd_id = {$gData['comp']}"; exit;
	$sql = "SELECT comp_name, comp_tin, comp_add, comp_tel, comp_zipcode FROM company_info WHERE comp_id = {$gData['comp']}";
    
	
	$rsResult = $this->conn->Execute($sql);
	//echo '<pre>';
	//echo print_r($rsResult);
	//echo '<pre>';
	//exit;
	$company_name = strtoupper($rsResult->fields['comp_name']);
	$company_tin = trim($rsResult->fields['comp_tin']);
	$company_add = $rsResult->fields['comp_add'];
	$company_tel = $rsResult->fields['comp_tel'];
	
	
	//company name
	$oPDF->Text($coordX+90, $coordY+71.5, $company_name);
	
	//company address
	$oPDF->Text($coordX+55, $coordY+79, $company_add);
	
	//tin
	$oPDF->setFontSize(12);
	if (strstr($company_tin,"-")) {
		$tin = explode('-',$company_tin);
		$tin = $tin[0].$tin[1].$tin[2];
	} else if (strstr($company_tin," ")) {
		$tin = explode(' ', $company_tin);
		$tin = $tin[0].$tin[1].$tin[2];
	} else {
		$tin = $company_tin;
	}
	
	//add tin
	$addtin = 64;
	$oPDF->text($coordX+135,$coordY+$addtin,$tin[0]);
	$oPDF->text($coordX+138,$coordY+$addtin,$tin[1]);
	$oPDF->text($coordX+141,$coordY+$addtin,$tin[2]);
	$oPDF->text($coordX+149,$coordY+$addtin,$tin[3]);
	$oPDF->text($coordX+152,$coordY+$addtin,$tin[4]);
	$oPDF->text($coordX+155,$coordY+$addtin,$tin[5]);
	$oPDF->text($coordX+163,$coordY+$addtin,$tin[6]);
	$oPDF->text($coordX+166,$coordY+$addtin,$tin[7]);
	$oPDF->text($coordX+169,$coordY+$addtin,$tin[8]);
	$oPDF->text($coordX+176,$coordY+$addtin,0);
	$oPDF->text($coordX+178.5,$coordY+$addtin,0);
	$oPDF->text($coordX+181,$coordY+$addtin,0);
	$oPDF->text($coordX+183.5,$coordY+$addtin,0);

	//Representative
	//$rep = array("Representative" => );
	$rep = $gData['representative'];
	//echo '<pre>';
	//echo print_r($rep);
	//echo '<pre>'; exit;
	$oPDF->text($coordX+111,$coordY+86.5,$rep);
	
	//year
	$cont_summary = $this->dbFetchRemittanceSummary($gData);
	$oPDF->text($coordX+168,$coordY+58.8,$cont_summary['year']);
	
	//telephone number
	$oPDF->text($coordX+167,$coordY+86.5,$company_tel);
	
	//------TAXPAYER'S COPY-----------------------------------------------
	
	//company name
	$oPDF->setFontSize(10);
	$oPDF->Text($coordX+90, $coordY+218, $company_name);

	//company address
	$oPDF->Text($coordX+55, $coordY+225.5, $company_add);
	
	//tin
	$oPDF->setFontSize(12);
	if (strstr($company_tin,"-")) {
		$tin = explode('-',$company_tin);
		$tin = $tin[0].$tin[1].$tin[2];
	} else if (strstr($company_tin," ")) {
		$tin = explode(' ', $company_tin);
		$tin = $tin[0].$tin[1].$tin[2];
	} else {
		$tin = $company_tin;
	}
	
	//add tin
	$addtin = 210.5;
	$oPDF->text($coordX+135,$coordY+$addtin,$tin[0]);
	$oPDF->text($coordX+138,$coordY+$addtin,$tin[1]);
	$oPDF->text($coordX+141,$coordY+$addtin,$tin[2]);
	$oPDF->text($coordX+149,$coordY+$addtin,$tin[3]);
	$oPDF->text($coordX+152,$coordY+$addtin,$tin[4]);
	$oPDF->text($coordX+155,$coordY+$addtin,$tin[5]);
	$oPDF->text($coordX+163,$coordY+$addtin,$tin[6]);
	$oPDF->text($coordX+166,$coordY+$addtin,$tin[7]);
	$oPDF->text($coordX+169,$coordY+$addtin,$tin[8]);
	$oPDF->text($coordX+176,$coordY+$addtin,0);
	$oPDF->text($coordX+178.5,$coordY+$addtin,0);
	$oPDF->text($coordX+181,$coordY+$addtin,0);
	$oPDF->text($coordX+183.5,$coordY+$addtin,0);
	
	//representative
	$oPDF->text($coordX+111,$coordY+233,$rep);
	
	//year
	$cont_summary = $this->dbFetchRemittanceSummary($gData);
	$oPDF->text($coordX+168,$coordY+205.3,$cont_summary['year']);
	
		//telephone number
	$oPDF->text($coordX+167,$coordY+233,$company_tel);
	
	//get the pdf output
	$output = $oPDF->Output("Transmittal_".$gData['year'].".pdf");

	if (!empty($output)) {
		return $output;
	}

	return false;
	}
    
    
	/**
	 * Populate array parameters to Data Variable
	 *
	 * @param array $pData_
	 * @param boolean $isForm_
	 * @return bool
	 */
	function doPopulateData($pData_ = array(),$isForm_ = false) {
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
	function doValidateData($pData_ = array()) {
		$isValid = true;

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

		$sql = "INSERT INTO app_modules SET $fields";
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

		$sql = "UPDATE /*app_modules*/ SET {$fields} WHERE mnu_id={$id}";
		$this->conn->Execute($sql);
		$_SESSION['eMsg']="Successfully Updated.";
	}

	/**
	 * Delete Record
	 *
	 * @param string $id_
	 */
	function doDelete($id_ = "") {
		$sql = "DELETE FROM /*app_modules*/ WHERE mnu_id=?";
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
				$qry[] = "mnu_name LIKE '%$search_field%'";
			}
		}

		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" WHERE ".implode(" AND ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		 "mnu_name"=>"mnu_name"
		,"mnu_link"=>"mnu_link"
		,"mnu_ord"=>"mnu_ord"
		);

		if (isset($_GET['sortby'])) {
			$strOrderBy = " ORDER BY ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}

		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$viewLink = "";
		$editLink = "<a href=\"?statpos=bir_alphalist&edit=',am.mnu_id,'\"><img src=\"".SYSCONFIG_THEME_URLPATH.SYSCONFIG_THEME."/images/admin/edit.gif\" title=\"Edit\" hspace=\"2px\" border=0></a>";
		$delLink = "<a href=\"?statpos=bir_alphalist&delete=',am.mnu_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_THEME_URLPATH.SYSCONFIG_THEME."/images/admin/trash.gif\" title=\"Delete\" hspace=\"2px\"  border=0></a>";

		// SqlAll Query
		$sql = "SELECT am.*, CONCAT('$viewLink','$editLink','$delLink') AS viewdata
						FROM app_modules am
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

    function getEmp() {
//		$this->conn->debug=0;
        $qry = array();

		$criteria = count($qry)>0 ? " where ".implode(' and ',$qry) : '';

		$sql = "SELECT b.emp_id,b.emp_idnum
				,d.pi_tin, g.salaryinfo_basicrate,CONCAT(d.pi_lname,', ',d.pi_mname,' ',d.pi_fname) AS NAME
				FROM payroll_pps_user a
				INNER JOIN emp_masterfile b ON (b.emp_id =a.emp_id)
				INNER JOIN emp_personal_info d ON (d.pi_id = b.pi_id)
				/*inner join azt_hris_db.hris_branch_dept_rel e on (e.bdrel_id = b.bdrel_id)
				inner join app_userdept f on (f.ud_id = e.ud_id)*/
				INNER JOIN salary_info g ON (b.emp_id = g.emp_id)
				$criteria
                ORDER BY NAME";
		$rsResult = $this->conn->Execute($sql);
        while (!$rsResult->EOF) {
            $arrData[] = $rsResult->fields;
            $rsResult->MoveNext();   
        }
        return $arrData;
    }

    function generatexlsreport($gData = array()) {
        $y = $gData['year']."-01-01";

        $enddate = dDate::getEndYearEpoch(dDate::parseDateTime($y));
        $startdate = dDate::getBeginYearEpoch(dDate::parseDateTime($y));

        $emp = $this->getEmp();

        return $emp;
    }

    function getXLSResult($gData = array()) {
        $filename = 'alphabetical_list_of_employee.xls'; // The file name you want any resulting file to be called.

        $objClsSSS = new clsSSS($this->conn);
        $oDataBranch = $objClsSSS->dbfetchBranchDetails();
        #create an instance of the class
        $xls = new ExportXLS($filename);

        $header = "BIR FORM 1604CF - SCHEDULE 7.3"; // single first col text
        $xls->addHeader($header);
        $header = "ALPHALIST OF EMPLOYEES AS OF DECEMBER 31 WITH NO PREVIOUS EMPLOYER WITHIN THE YEAR"; // single first col text
        $xls->addHeader($header);
        $header = "AS OF DECEMBER 31,2008"; // single first col text
        $xls->addHeader($header);

        #add blank line
        $header = NULL;
        $xls->addHeader($header);
        $header = 'TIN : '.$oDataBranch['te_tin'];
        $xls->addHeader($header);
        $header = 'WITHHOLDING AGENTS NAME: '.$oDataBranch['te_name'];
        $xls->addHeader($header);

        $header = NULL;
        $xls->addHeader($header);

        #add 2nd header as an array of 3 columns
        $header[] = "";
        $header[] = "";
        $header[] = "";
        $header[] = "(5) G R O S S   C O M P E N S A T I O N   I N C O M E";
        $xls->addHeader($header);

        $header1[] = "";
        $header1[] = "";
        $header1[] = "";
        $header1[] = "N O N - T A X A B L E";
        $header1[] = "";
        $header1[] = "";
        $header1[] = " T A X A B L E ";
        $header1[] = "";
        $header1[] = "";
        $header1[] = "";
        $header1[] = "";
        $header1[] = "";
        $header1[] = "Y E A R - E N D   A D J U S T M E N T (9a or 9b)";
        $xls->addHeader($header1);

        $header2[] = "SEQ";
        $header2[] = "TAXPAYER";
        $header2[] = "NAME OF EMPLOYEES";
        $header2[] = "13th MONTH PAY";
        $header2[] = "SSS,GSIS,PHIC &";
        $header2[] = "SALARIES & OTHER";
        $header2[] = "13th MONTH PAY";
        $header2[] = "SALARIES & OTHER";
        $header2[] = "AMOUNT OF";
        $header2[] = "PREMIUM PAID";
        $header2[] = "TAX DUE";
        $header2[] = "TAX WITHHELD";
        $header2[] = "AMT WITHHELD";
        $header2[] = "OVER";
        $header2[] = "AMOUNT OF TAX";
        $xls->addHeader($header2);

        $header3[] = "NO";
        $header3[] = "IDENTIFICATION";
        $header3[] = "(Last Name, First Name, Middle Name)";
        $header3[] = "& OTHER BENEFITS";
        $header3[] = "PAG-IBIG CONTRIBUTIONS,";
        $header3[] = "FORMS OF";
        $header3[] = "& OTHER BENFITS";
        $header3[] = "FORMS OF";
        $header3[] = "EXEMPTION";
        $header3[] = "ON HEALTH";
        $header3[] = "(Jan. - Dec.)";
        $header3[] = "(Jan. - Nov.)";
        $header3[] = "& PAID FOR IN";
        $header3[] = "WITHHELD TAX";
        $header3[] = "WITHHELD AS";
        $xls->addHeader($header3);

        $header4[] = "";
        $header4[] = "NUMBER";
        $header4[] = "";
        $header4[] = "";
        $header4[] = "AND UNION DUES";
        $header4[] = "COMPENSATION";
        $header4[] = "";
        $header4[] = "COMPENSATION";
        $header4[] = "";
        $header4[] = "AND/OR HOSPITAL";
        $header4[] = "";
        $header4[] = "";
        $header4[] = "DECEMBER";
        $header4[] = "EMPLOYEE";
        $header4[] = "ADJUSTED";
        $xls->addHeader($header4);

        $header5[] = "(1)";
        $header5[] = "(2)";
        $header5[] = "(3)";
        $header5[] = "4(a)";
        $header5[] = "4(b)";
        $header5[] = "4(c)";
        $header5[] = "4(d)";
        $header5[] = "4(e)";
        $header5[] = "(5)";
        $header5[] = "(6)";
        $header5[] = "(7)";
        $header5[] = "(8)";
        $header5[] = "(9a)=(7)-(8)";
        $header5[] = "(9b)=(8)-(7)";
        $header5[] = "(10)=(8+9a)or(8-9b)";
        $xls->addHeader($header5);

        $header = NULL;
        $xls->addHeader($header);

        $data = $this->generatexlsreport($gData);

        if (count($data)>0) {

            $seq_np = 0;
            foreach ($data as $key => $val) {
                $seq_np++;

                $row = array();
                $row[] = $seq_np;
                $row[] = $val['pi_tin'];
                $row[] = $val['name'];
                $row[] = "0.00";
		        $row[] = "0.00";
		        $row[] = "0.00";
		        $row[] = "0.00";
		        $row[] = "0.00";
		        $row[] = "0.00";
		        $row[] = "0.00";
		        $row[] = "0.00";
		        $row[] = "0.00";
		        $row[] = "0.00";
		        $row[] = "0.00";
		        $row[] = "0.00";

                $xls->addRow($row);
            }
        }
        
        $row1[] = "";
        $row1[] = "";
        $row1[] = "";
        $row1[] = "------------------";
        $row1[] = "------------------";
        $row1[] = "------------------";
        $row1[] = "------------------";
        $row1[] = "------------------";
        $row1[] = "------------------";
        $row1[] = "------------------";
        $row1[] = "------------------";
        $row1[] = "------------------";
        $row1[] = "------------------";
        $row1[] = "------------------";
        $row1[] = "------------------";
        $xls->addRow($row1);

        $row2[] = "Grand Total";
        $row2[] = "";
        $row2[] = "";
        $row2[] = "";
        $row2[] = "";
        $row2[] = "";
        $row2[] = "";
        $row2[] = "";
        $row2[] = "";
        $row2[] = "";
        $row2[] = "";
        $row2[] = "";
        $row2[] = "";
        $row2[] = "";
        $row2[] = "";
        $xls->addRow($row2);
        
        $xls->sendFile();
    }
    
	function get2316($gData = array()) {
        $orientation='P';
        $unit='mm';
        $format='LEGAL';
        $unicode=true;
        $encoding="UTF-8";

        $oPDF = new clsPDF($orientation, $unit, $format, $unicode, $encoding);

        // set auto page break to false so that we can control the page break
        // depending on the desired number of lines on the ouput
        $oPDF->SetAutoPageBreak(false);
        // use a freeserif font as a default font
        $oPDF->SetFont('freeserif','',10);

        // suppress print header and footer
        $oPDF->setPrintHeader(false);
        $oPDF->setPrintFooter(false);

        // get a modules list from the database
        $arrUserTypeList = $this->dbFetch();

        // set initial coordinates
        $coordX = 0;
        $coordY = 0;

        // set initial pdf page
        $oPDF->AddPage();
        $oPDF->Image(SYSCONFIG_THEME_PATH."default/images/admin/2316.jpg",$coordX,$coordY,$oPDF->getPageWidth(),$oPDF->getPageHeight());
        
    	// For the Year
    	$oPDF->SetFontSize(12);
    	$year = '2012';
    	$add_year = 35;
        $oPDF->Text($coordX+47,$coordY+$add_year,$year[0]);
        $oPDF->Text($coordX+51.4,$coordY+$add_year,$year[1]);
        $oPDF->Text($coordX+55.9,$coordY+$add_year,$year[2]);
        $oPDF->Text($coordX+60,$coordY+$add_year,$year[3]);
        
        // For the Period
        $from_month = '01';
        $from_day = '02';
        
        $to_month = '01';
        $to_day = '02';
        
    	$add_md = 35;
    	// From (MM/DD)
        $oPDF->Text($coordX+145.3,$coordY+$add_md,$from_month[0]);
        $oPDF->Text($coordX+149,$coordY+$add_md,$from_month[1]);
        $oPDF->Text($coordX+152.5,$coordY+$add_md,$from_day[0]);
        $oPDF->Text($coordX+156,$coordY+$add_md,$from_day[1]);
        
        // To (MM/DD)
        $oPDF->Text($coordX+185.3,$coordY+$add_md,$to_month[0]);
        $oPDF->Text($coordX+189,$coordY+$add_md,$to_month[1]);
        $oPDF->Text($coordX+192.6,$coordY+$add_md,$to_day[0]);
        $oPDF->Text($coordX+196,$coordY+$add_md,$to_day[1]);
        
        // TIN
        $tin = '123456789';
        
        $add_tin = 45.9;
        $oPDF->Text($coordX+47.4,$coordY+$add_tin,$tin[0]);
        $oPDF->Text($coordX+51.4,$coordY+$add_tin,$tin[1]);
        $oPDF->Text($coordX+55.2,$coordY+$add_tin,$tin[2]);
        $oPDF->Text($coordX+63.1,$coordY+$add_tin,$tin[3]);
        $oPDF->Text($coordX+67,$coordY+$add_tin,$tin[4]);
        $oPDF->Text($coordX+70.8,$coordY+$add_tin,$tin[5]);
        $oPDF->Text($coordX+79.2,$coordY+$add_tin,$tin[6]);
        $oPDF->Text($coordX+83,$coordY+$add_tin,$tin[7]);
        $oPDF->Text($coordX+86.9,$coordY+$add_tin,$tin[8]);
        $oPDF->Text($coordX+95,$coordY+$add_tin,0);
        $oPDF->Text($coordX+98.8,$coordY+$add_tin,0);
        $oPDF->Text($coordX+102.5,$coordY+$add_tin,0);
        $oPDF->Text($coordX+106.1,$coordY+$add_tin,0);
        
        // Employee's Name (Last Name, First Name, Middle Name)
        $fullname = 'Untiveros Grey Macaraeg';
        
        $add_fullname_and_rdo_code = 55.5;
        $add_X = 19;
        $oPDF->Text($coordX+$add_X,$coordY+$add_fullname_and_rdo_code,$fullname);
        
        // RDO Code
        $rdo_code = '000';
        
        $oPDF->Text($coordX+94,$coordY+$add_fullname_and_rdo_code,$rdo_code[0]);
        $oPDF->Text($coordX+100,$coordY+$add_fullname_and_rdo_code,$rdo_code[1]);
        $oPDF->Text($coordX+105.7,$coordY+$add_fullname_and_rdo_code,$rdo_code[2]);
        
        // Registered Address
        $registered_address = 'Sampaloc, Manila';
        
        $add_registered_address_and_zip_code = 64.65;
        $oPDF->Text($coordX+$add_X,$coordY+$add_registered_address_and_zip_code,$registered_address);
        
        // Zip Code
        $zip_code = '1234';
        
        $oPDF->Text($coordX+93.3,$coordY+$add_registered_address_and_zip_code,$zip_code[0]);
        $oPDF->Text($coordX+97.4,$coordY+$add_registered_address_and_zip_code,$zip_code[1]);
        $oPDF->Text($coordX+101.6,$coordY+$add_registered_address_and_zip_code,$zip_code[2]);
        $oPDF->Text($coordX+105.9,$coordY+$add_registered_address_and_zip_code,$zip_code[3]);
        
        // Local Home Address
        $local_home_address = 'Sampaloc, Manila';
        
        $add_local_home_address_and_zip_code = 74;
        $oPDF->Text($coordX+$add_X,$coordY+$add_local_home_address_and_zip_code,$local_home_address);
        
        // Zip Code
        $local_home_address_zip_code = '1234';
        
        $oPDF->Text($coordX+93.5,$coordY+$add_local_home_address_and_zip_code,$local_home_address_zip_code[0]);
        $oPDF->Text($coordX+97.7,$coordY+$add_local_home_address_and_zip_code,$local_home_address_zip_code[1]);
        $oPDF->Text($coordX+101.7,$coordY+$add_local_home_address_and_zip_code,$local_home_address_zip_code[2]);
        $oPDF->Text($coordX+106,$coordY+$add_local_home_address_and_zip_code,$local_home_address_zip_code[3]);
        
        // Foreign Address
        $foreign_address = 'Sampaloc, Manila';
        
        $add_foregin_address_and_zip_code = 83.3;
        $oPDF->Text($coordX+$add_X,$coordY+$add_foregin_address_and_zip_code,$foreign_address);
        
        // Zip Code
        $foreign_address_zip_code = '1234';
        
        $oPDF->Text($coordX+93.3,$coordY+$add_foregin_address_and_zip_code,$foreign_address_zip_code[0]);
        $oPDF->Text($coordX+97.3,$coordY+$add_foregin_address_and_zip_code,$foreign_address_zip_code[1]);
        $oPDF->Text($coordX+101.55,$coordY+$add_foregin_address_and_zip_code,$foreign_address_zip_code[2]);
        $oPDF->Text($coordX+105.75,$coordY+$add_foregin_address_and_zip_code,$foreign_address_zip_code[3]);
        
        // Date of Birth
        $date_of_birth_month = '07';
        $date_of_birth_day = '02';
        $date_of_birth_year = '1992';
        
        $add_date_of_birth_and_telephone_number = 92.6;
        
        $oPDF->Text($coordX+$add_X,$coordY+$add_date_of_birth_and_telephone_number,$date_of_birth_month[0]);
        $oPDF->Text($coordX+23,$coordY+$add_date_of_birth_and_telephone_number,$date_of_birth_month[1]);
        
        $oPDF->Text($coordX+27.4,$coordY+$add_date_of_birth_and_telephone_number,$date_of_birth_day[0]);
        $oPDF->Text($coordX+31.7,$coordY+$add_date_of_birth_and_telephone_number,$date_of_birth_day[1]);
        
        $oPDF->Text($coordX+36.3,$coordY+$add_date_of_birth_and_telephone_number,$date_of_birth_year[0]);
        $oPDF->Text($coordX+41.25,$coordY+$add_date_of_birth_and_telephone_number,$date_of_birth_year[1]);
        $oPDF->Text($coordX+46.4,$coordY+$add_date_of_birth_and_telephone_number,$date_of_birth_year[2]);
        $oPDF->Text($coordX+51.4,$coordY+$add_date_of_birth_and_telephone_number,$date_of_birth_year[3]);
        
        // Telephone Number
        $telephone_number = '12345678901234567';
        
        $oPDF->Text($coordX+72.5,$coordY+$add_date_of_birth_and_telephone_number,$telephone_number);
        
        // Exemption Status
        $oPDF->SetFontSize(8);
        // Single
        $add_single_and_married = 101.05;
        $add_X_single_and_yes = 35.8;
        $oPDF->Text($coordX+$add_X_single_and_yes,$coordY+$add_single_and_married,'X');
        
        // Married
        $add_X_married_and_no = 65.8;
        $oPDF->Text($coordX+65.8,$coordY+$add_single_and_married,'X');
        
        // Yes
        $oPDF->Text($coordX+$add_X_single_and_yes,$coordY+107.45,'X');
        
        // No
        $oPDF->Text($coordX+$add_X_married_and_no,$coordY+107.15,'X');
        
        // Name of Qualified Dependent Children 1
        $oPDF->SetFontSize(10);
        $name_of_qualified_dependent_children_1 = 'Grey Macaraeg Untiveros 1';
        
        $add_name_of_qualified_dependent_children_and_date_of_birth_1 = 116.5;
        $oPDF->Text($coordX+$add_X,$coordY+$add_name_of_qualified_dependent_children_and_date_of_birth_1,$name_of_qualified_dependent_children_1);
        
        // Date of Birth 1
        $date_of_birth_1 = '07021992';
        
        // Month
        $oPDF->Text($coordX+76.4,$coordY+$add_name_of_qualified_dependent_children_and_date_of_birth_1,$date_of_birth_1[0]);
        $oPDF->Text($coordX+80.6,$coordY+$add_name_of_qualified_dependent_children_and_date_of_birth_1,$date_of_birth_1[1]);
        // Day
        $oPDF->Text($coordX+85,$coordY+$add_name_of_qualified_dependent_children_and_date_of_birth_1,$date_of_birth_1[2]);
        $oPDF->Text($coordX+89.2,$coordY+$add_name_of_qualified_dependent_children_and_date_of_birth_1,$date_of_birth_1[3]);
        // Year
        $oPDF->Text($coordX+93.5,$coordY+$add_name_of_qualified_dependent_children_and_date_of_birth_1,$date_of_birth_1[4]);
        $oPDF->Text($coordX+97.7,$coordY+$add_name_of_qualified_dependent_children_and_date_of_birth_1,$date_of_birth_1[5]);
        $oPDF->Text($coordX+102,$coordY+$add_name_of_qualified_dependent_children_and_date_of_birth_1,$date_of_birth_1[6]);
        $oPDF->Text($coordX+106.1,$coordY+$add_name_of_qualified_dependent_children_and_date_of_birth_1,$date_of_birth_1[7]);
        
        // Name of Qualified Dependent Children 2
        $name_of_qualified_dependent_children_2 = 'Grey Macaraeg Untiveros 2';
        
        $add_name_of_qualified_dependent_children_and_date_of_birth_2 = 120.3;
        $oPDF->Text($coordX+$add_X,$coordY+$add_name_of_qualified_dependent_children_and_date_of_birth_2,$name_of_qualified_dependent_children_2);
        
        // Date of Birth 2
        $date_of_birth_2 = '08031992';
        
        // Month
        $oPDF->Text($coordX+76.4,$coordY+$add_name_of_qualified_dependent_children_and_date_of_birth_2,$date_of_birth_2[0]);
        $oPDF->Text($coordX+80.6,$coordY+$add_name_of_qualified_dependent_children_and_date_of_birth_2,$date_of_birth_2[1]);
        // Day
        $oPDF->Text($coordX+85,$coordY+$add_name_of_qualified_dependent_children_and_date_of_birth_2,$date_of_birth_2[2]);
        $oPDF->Text($coordX+89.2,$coordY+$add_name_of_qualified_dependent_children_and_date_of_birth_2,$date_of_birth_2[3]);
        // Year
        $oPDF->Text($coordX+93.5,$coordY+$add_name_of_qualified_dependent_children_and_date_of_birth_2,$date_of_birth_2[4]);
        $oPDF->Text($coordX+97.7,$coordY+$add_name_of_qualified_dependent_children_and_date_of_birth_2,$date_of_birth_2[5]);
        $oPDF->Text($coordX+102,$coordY+$add_name_of_qualified_dependent_children_and_date_of_birth_2,$date_of_birth_2[6]);
        $oPDF->Text($coordX+106.1,$coordY+$add_name_of_qualified_dependent_children_and_date_of_birth_2,$date_of_birth_2[7]);
        
        // Name of Qualified Dependent Children 3
        $name_of_qualified_dependent_children_3 = 'Grey Macaraeg Untiveros 3';
        
        $add_name_of_qualified_dependent_children_and_date_of_birth_3 = 124.1;
        $oPDF->Text($coordX+$add_X,$coordY+$add_name_of_qualified_dependent_children_and_date_of_birth_3,$name_of_qualified_dependent_children_3);
        
        // Date of Birth 3
        $date_of_birth_3 = '09041992';
        
        // Month
        $oPDF->Text($coordX+76.4,$coordY+$add_name_of_qualified_dependent_children_and_date_of_birth_3,$date_of_birth_3[0]);
        $oPDF->Text($coordX+80.6,$coordY+$add_name_of_qualified_dependent_children_and_date_of_birth_3,$date_of_birth_3[1]);
        // Day
        $oPDF->Text($coordX+85,$coordY+$add_name_of_qualified_dependent_children_and_date_of_birth_3,$date_of_birth_3[2]);
        $oPDF->Text($coordX+89.2,$coordY+$add_name_of_qualified_dependent_children_and_date_of_birth_3,$date_of_birth_3[3]);
        // Year
        $oPDF->Text($coordX+93.5,$coordY+$add_name_of_qualified_dependent_children_and_date_of_birth_3,$date_of_birth_3[4]);
        $oPDF->Text($coordX+97.7,$coordY+$add_name_of_qualified_dependent_children_and_date_of_birth_3,$date_of_birth_3[5]);
        $oPDF->Text($coordX+102,$coordY+$add_name_of_qualified_dependent_children_and_date_of_birth_3,$date_of_birth_3[6]);
        $oPDF->Text($coordX+106.1,$coordY+$add_name_of_qualified_dependent_children_and_date_of_birth_3,$date_of_birth_3[7]);
        
        // Name of Qualified Dependent Children 4
        $name_of_qualified_dependent_children_4 = 'Grey Macaraeg Untiveros 4';
        
        $add_name_of_qualified_dependent_children_and_date_of_birth_4 = 128;
        $oPDF->Text($coordX+$add_X,$coordY+$add_name_of_qualified_dependent_children_and_date_of_birth_4,$name_of_qualified_dependent_children_4);
        
        // Date of Birth 3
        $date_of_birth_4 = '10051992';
        
        // Month
        $oPDF->Text($coordX+76.4,$coordY+$add_name_of_qualified_dependent_children_and_date_of_birth_4,$date_of_birth_4[0]);
        $oPDF->Text($coordX+80.6,$coordY+$add_name_of_qualified_dependent_children_and_date_of_birth_4,$date_of_birth_4[1]);
        // Day
        $oPDF->Text($coordX+85,$coordY+$add_name_of_qualified_dependent_children_and_date_of_birth_4,$date_of_birth_4[2]);
        $oPDF->Text($coordX+89.2,$coordY+$add_name_of_qualified_dependent_children_and_date_of_birth_4,$date_of_birth_4[3]);
        // Year
        $oPDF->Text($coordX+93.5,$coordY+$add_name_of_qualified_dependent_children_and_date_of_birth_4,$date_of_birth_4[4]);
        $oPDF->Text($coordX+97.7,$coordY+$add_name_of_qualified_dependent_children_and_date_of_birth_4,$date_of_birth_4[5]);
        $oPDF->Text($coordX+102,$coordY+$add_name_of_qualified_dependent_children_and_date_of_birth_4,$date_of_birth_4[6]);
        $oPDF->Text($coordX+106.1,$coordY+$add_name_of_qualified_dependent_children_and_date_of_birth_4,$date_of_birth_4[7]);
        
        // Statutory Minimum Wage rate per day
        $oPDF->SetFontSize(12);
        
        $oPDF->Text($coordX+76.4,$coordY+134.3,'000');
        
        // Statutory Minimum Wage rate per month
        $oPDF->Text($coordX+76.4,$coordY+140.2,'000');
        
        // Minimum Wage Earner whose compensation is exempt from withholding tax and not subject to income tax
        $oPDF->SetFontSize(8);
        $oPDF->Text($coordX+22,$coordY+145.3,'X');
        
        // Employer Information (Present)
        // Taxpayer Identification No.
        $oPDF->SetFontSize(10);
        $taxpayer_identification_no = '123456789';
        
        $add_taxpayer_identification_no = 156.2;
        $oPDF->Text($coordX+47.8,$coordY+$add_taxpayer_identification_no,$taxpayer_identification_no[0]);
        $oPDF->Text($coordX+51.5,$coordY+$add_taxpayer_identification_no,$taxpayer_identification_no[1]);
        $oPDF->Text($coordX+55.4,$coordY+$add_taxpayer_identification_no,$taxpayer_identification_no[2]);
        $oPDF->Text($coordX+63.35,$coordY+$add_taxpayer_identification_no,$taxpayer_identification_no[3]);
        $oPDF->Text($coordX+67.15,$coordY+$add_taxpayer_identification_no,$taxpayer_identification_no[4]);
        $oPDF->Text($coordX+70.95,$coordY+$add_taxpayer_identification_no,$taxpayer_identification_no[5]);
        $oPDF->Text($coordX+79.35,$coordY+$add_taxpayer_identification_no,$taxpayer_identification_no[6]);
        $oPDF->Text($coordX+83.25,$coordY+$add_taxpayer_identification_no,$taxpayer_identification_no[7]);
        $oPDF->Text($coordX+87,$coordY+$add_taxpayer_identification_no,$taxpayer_identification_no[8]);
        $oPDF->Text($coordX+95.2,$coordY+$add_taxpayer_identification_no,'0');
        $oPDF->Text($coordX+99,$coordY+$add_taxpayer_identification_no,'0');
        $oPDF->Text($coordX+102.7,$coordY+$add_taxpayer_identification_no,'0');
        $oPDF->Text($coordX+106.35,$coordY+$add_taxpayer_identification_no,'0');
        
        // Employer's Name
        $oPDF->Text($coordX+18.3,$coordY+165.2,'Sigmasoft Technologies Corp.');
        
        // Registered Address
        $oPDF->SetFontSize(4.85);
        $oPDF->Text($coordX+18.3,$coordY+174,'Unit 605/606 Jafer Place Building No. 19 Eisenhower St. Greenhills San Juan Manila Philippines 1504');
        
        // Zip Code
        $oPDF->SetFontSize(12);
        $employers_zip_code = '1234';
        
        $add_employers_zip_code = 175.1;
        $oPDF->Text($coordX+93.3,$coordY+$add_employers_zip_code,$employers_zip_code[0]);
        $oPDF->Text($coordX+97.3,$coordY+$add_employers_zip_code,$employers_zip_code[1]);
        $oPDF->Text($coordX+101.55,$coordY+$add_employers_zip_code,$employers_zip_code[2]);
        $oPDF->Text($coordX+105.75,$coordY+$add_employers_zip_code,$employers_zip_code[3]);
        
        // Main Employer
        $oPDF->SetFontSize(7);
        $add_main_employer_and_secondary_employer = 179.55;
        $oPDF->Text($coordX+25.85,$coordY+$add_main_employer_and_secondary_employer,'X');
        
        // Secondary Employer
        $oPDF->Text($coordX+58.8,$coordY+$add_main_employer_and_secondary_employer,'X');
        
        // Employer Information (Previous)
        // Taxpayer Identification No.
        $oPDF->SetFontSize(10);
        $taxpayer_identification_no_previous = '123456789';
        
        $add_taxpayer_identification_no_previous = 187.9;
        $oPDF->Text($coordX+47.8,$coordY+$add_taxpayer_identification_no_previous,$taxpayer_identification_no_previous[0]);
        $oPDF->Text($coordX+51.5,$coordY+$add_taxpayer_identification_no_previous,$taxpayer_identification_no_previous[1]);
        $oPDF->Text($coordX+55.4,$coordY+$add_taxpayer_identification_no_previous,$taxpayer_identification_no_previous[2]);
        $oPDF->Text($coordX+63.35,$coordY+$add_taxpayer_identification_no_previous,$taxpayer_identification_no_previous[3]);
        $oPDF->Text($coordX+67.15,$coordY+$add_taxpayer_identification_no_previous,$taxpayer_identification_no_previous[4]);
        $oPDF->Text($coordX+70.95,$coordY+$add_taxpayer_identification_no_previous,$taxpayer_identification_no_previous[5]);
        $oPDF->Text($coordX+79.35,$coordY+$add_taxpayer_identification_no_previous,$taxpayer_identification_no_previous[6]);
        $oPDF->Text($coordX+83.25,$coordY+$add_taxpayer_identification_no_previous,$taxpayer_identification_no_previous[7]);
        $oPDF->Text($coordX+87,$coordY+$add_taxpayer_identification_no_previous,$taxpayer_identification_no_previous[8]);
        $oPDF->Text($coordX+95.2,$coordY+$add_taxpayer_identification_no_previous,'0');
        $oPDF->Text($coordX+99,$coordY+$add_taxpayer_identification_no_previous,'0');
        $oPDF->Text($coordX+102.7,$coordY+$add_taxpayer_identification_no_previous,'0');
        $oPDF->Text($coordX+106.35,$coordY+$add_taxpayer_identification_no_previous,'0');
        
        // Employer's Name (Previous)
        $oPDF->Text($coordX+18.3,$coordY+196.9,'Sigmasoft Technologies Corp.');
        
        // Registered Address
        $oPDF->SetFontSize(4.85);
        $oPDF->Text($coordX+18.3,$coordY+205.8,'Unit 605/606 Jafer Place Building No. 19 Eisenhower St. Greenhills San Juan Manila Philippines 1504');
        
        // Zip Code
        $oPDF->SetFontSize(12);
        $employers_zip_code = '1234';
        
        $add_employers_zip_code = 206.8;
        $oPDF->Text($coordX+93.3,$coordY+$add_employers_zip_code,$employers_zip_code[0]);
        $oPDF->Text($coordX+97.5,$coordY+$add_employers_zip_code,$employers_zip_code[1]);
        $oPDF->Text($coordX+101.7,$coordY+$add_employers_zip_code,$employers_zip_code[2]);
        $oPDF->Text($coordX+105.85,$coordY+$add_employers_zip_code,$employers_zip_code[3]);
        
        // Summary
        // Gross Compensation Income from Present Employer (Item 41 plus Item 55)
        $add_X_summary = 66;
        $oPDF->Text($coordX+$add_X_summary,$coordY+216.5,'00000');
        
        // Less: Total Non-Taxable/Exempt (Item 41)
        $oPDF->Text($coordX+$add_X_summary,$coordY+222.1,'00000');
        
        // Taxable Compensation Income from Present Employer (Item 55)
        $oPDF->Text($coordX+$add_X_summary,$coordY+227.7,'00000');
        
        
        // Add: Taxable Compensation Income from Previous Employer
        $oPDF->Text($coordX+$add_X_summary,$coordY+233.3,'00000');
        
        // Gross Taxable Compensation Income
        $oPDF->Text($coordX+$add_X_summary,$coordY+238.9,'00000');
        
        // Less: Total Exemptions
        $oPDF->Text($coordX+$add_X_summary,$coordY+244.5,'00000');
        
        // Less: Premium Paid on Health and/or Hospital Insurance (If applicable)
        $oPDF->Text($coordX+$add_X_summary,$coordY+250.1,'00000');
        
        // Net Taxable Compensation Income
        $oPDF->Text($coordX+$add_X_summary,$coordY+255.7,'00000');
        
        // Tax Due
        $oPDF->Text($coordX+$add_X_summary,$coordY+261.3,'00000');
        
        // Amount of Taxes Withheld Present Employer
        $oPDF->Text($coordX+$add_X_summary,$coordY+268.65,'00000');
        
        // Amount of Taxes Withheld Previous Employer
        $oPDF->Text($coordX+$add_X_summary,$coordY+274.5,'00000');
        
        // Total Amount of Taxes Withheld As adjusted
        $oPDF->Text($coordX+$add_X_summary,$coordY+280.2,'00000');
        
        // NON-TAXABLE/EXEMPT COMPENSATION INCOME
        // Basic Salary/Statutory Minimum Wage, Minimum Wage Earner (MWE)
        $add_X_non = 157.3;
        $oPDF->Text($coordX+$add_X_non,$coordY+55.65,'00000');
        
        // Holiday Pay (MWE)
        $oPDF->Text($coordX+$add_X_non,$coordY+66.85,'00000');
        
        // Overtime Pay (MWE)
        $oPDF->Text($coordX+$add_X_non,$coordY+74.4,'00000');
        
        // Night Shift Differential (MWE)
        $oPDF->Text($coordX+$add_X_non,$coordY+81.95,'00000');
        
        // Hazard Pay (MWE)
        $oPDF->Text($coordX+$add_X_non,$coordY+89.7,'00000');
        
        // 13th Month Pay and Other Benefits
        $oPDF->Text($coordX+$add_X_non,$coordY+97.2,'00000');
        
        // De Minimis Benefits
        $oPDF->Text($coordX+$add_X_non,$coordY+106.2,'00000');
        
        // SSS, GSIS, PHIC & Pag-ibig Contributions, & Union Dues (Employee share only)
        $oPDF->Text($coordX+$add_X_non,$coordY+116.7,'00000');
        
        // Salaries & Other Forms of Compensation
        $oPDF->Text($coordX+$add_X_non,$coordY+130.1,'00000');
        
        // Total Non-Taxable/Exempt Compensation Income
        $oPDF->Text($coordX+$add_X_non,$coordY+140,'00000');
        
        // TAXABLE COMPENSATION INCOME REGULAR
        // Basic Salary
        $oPDF->Text($coordX+$add_X_non,$coordY+158.7,'00000');
        
        // Representation
        $oPDF->Text($coordX+$add_X_non,$coordY+165.9,'00000');
        
        // Transportation
        $oPDF->Text($coordX+$add_X_non,$coordY+173.2,'00000');
        
        // Cost of Living Allowance
        $oPDF->Text($coordX+$add_X_non,$coordY+180.65,'00000');
        
        // Fixed Housing Allowance
        $oPDF->Text($coordX+$add_X_non,$coordY+188,'00000');
        
        // Others (Specify)
        // 47A Left
        $oPDF->Text($coordX+117,$coordY+197.75,'00000');
        
        // 47B Left
        $oPDF->Text($coordX+117,$coordY+204.1,'00000');
        
        // 47A Right
        $oPDF->Text($coordX+$add_X_non,$coordY+197.4,'00000');
        
        // 47B Right
        $oPDF->Text($coordX+$add_X_non,$coordY+204.55,'00000');
        
        // SUPPLEMENTARY
        // Commission
        $oPDF->Text($coordX+$add_X_non,$coordY+214,'00000');
        
        // Profit Sharing
        $oPDF->Text($coordX+$add_X_non,$coordY+221.8,'00000');
        
        // Fees Including Director's Fees
        $oPDF->Text($coordX+$add_X_non,$coordY+230.35,'00000');
        
        // Taxable 13th Month Pay and Other Benefits
        $oPDF->Text($coordX+$add_X_non,$coordY+238.45,'00000');
        
        // Hazard Pay
        $oPDF->Text($coordX+$add_X_non,$coordY+247.1,'00000');
        
        // Overtime Pay
        $oPDF->Text($coordX+$add_X_non,$coordY+255.1,'00000');
        
        // Others (Specify)
        // 54A Left
        $oPDF->Text($coordX+117,$coordY+266.7,'00000');
        
        // 54B Left
        $oPDF->Text($coordX+117,$coordY+272.7,'00000');
        
        // 54A Right
        $oPDF->Text($coordX+$add_X_non,$coordY+266.7,'00000');
        
        // 54B Right
        $oPDF->Text($coordX+$add_X_non,$coordY+272.7,'00000');
        
        // Total Taxable Compensation Income
        $oPDF->Text($coordX+$add_X_non,$coordY+279.6,'00000');
        
        // Get the pdf output
        $output = $oPDF->Output();
		
        if (!empty($output)) {
            return $output;
        }
		
        return false;
    }
    
    /**
     * @note Schedule 7.1 Generation
     * @param $gData
     */
    function get1604CF7_1($gData = array()) {
    	IF($gData['branchinfo_id']!=0){//Get company Details
    		$loc_details = clsSSS::getLocationInfo($gData['branchinfo_id']);
    		$company_name = $loc_details['branchinfo_name'];
    		$company_tin = $loc_details['branchinfo_tin'];
    	}ELSE{
    		$comp_details = clsSSS::dbfetchCompDetails($gData['comp']);
    		$company_name = $comp_details['comp_name'];
    		$company_tin = $comp_details['comp_tin'];
    	}
    	$emp = $this->getTerminatedEmployee($gData);//Get all Terminated Employee
    	// The file name you want any resulting file to be called.
    	$filename = "BIR1604CF_SCHEDULE_7.1_".$gData['year'].".xls";
    	// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		$objReader = PHPExcel_IOFactory::createReader('Excel5');
		$objPHPExcel = $objReader->load("templates/BIR_FORM_1604CF_SCHEDULE_7.1.xls");
		// Write Data
		// header
		$sheet = $objPHPExcel->getActiveSheet();
		$sheet->setCellValue('A3','AS OF DECEMBER 31, '.$gData['year']);
		$sheet->setCellValue('A6','TIN: '.$company_tin);
		$sheet->setCellValue('A7','WITHHOLDING AGENT\'S NAME: '.$company_name);
		
		// body - data
		$count = 1;
		$start_cell = 17;
		$cell = $start_cell;
		if (count($emp) > 0) {
			$tax_policy = $this->getTaxPolicy();
			foreach ($emp as $key => $val) {
				$tin = str_replace("-","",$val['pi_tin']);
				switch ($val['taxep_code']) {
					case 'ME': $taxep_code = 'M'; break;
					case 'ME1': $taxep_code = 'M1'; break;
					case 'ME2': $taxep_code = 'M2'; break;
					case 'ME3': $taxep_code = 'M3'; break;
					case 'ME4': $taxep_code = 'M4'; break;
					default: $taxep_code = $val['taxep_code']; break;
				}
				$prevEmployer = $this->getPreviousEmployer($val['emp_id'],$gData['year']);
				$sheet->setCellValue('A'.$cell,$count);
				$sheet->setCellValue('B'.$cell,$tin);
				$sheet->setCellValue('C'.$cell,strtoupper($val['fullname']));
				$sheet->setCellValue('D'.$cell,$val['date_start']);
				$sheet->setCellValue('E'.$cell,$this->endDateReplace($val['date_resign'], $val['date_retire']));
				// GROSS COMPENSATION INCOME = TOTAL NON-TAXABLE/EXEMPT COMPENSATION INCOME + TOTAL TAXABLE COMPENSATION INCOME
				$sheet->setCellValue('F'.$cell,'=O'.$cell.'+K'.$cell);
				if(($this->getBonus($val['emp_id'], $gData['year'],0)+$prevEmployer['nt_other_ben'])>$tax_policy['tp_other_benefits']){
					$bonus_nt = $tax_policy['tp_other_benefits'];
					$addtn_bonus_taxable = ($this->getBonus($val['emp_id'], $gData['year'],0)+$prevEmployer['nt_other_ben'])-$tax_policy['tp_other_benefits'];
				} else {
					$bonus_nt = $this->getBonus($val['emp_id'], $gData['year'],0)+$prevEmployer['nt_other_ben'];
					$addtn_bonus_taxable = 0;
				}
				$sheet->setCellValue('G'.$cell,$bonus_nt);
				
				$dmb_nt = $this->getDeminimis($val['emp_id'], $gData['year'])+$prevEmployer['nt_deminimis'];
				$sheet->setCellValue('H'.$cell,$dmb_nt);
				
				$stat_nt = $this->getStatutoryAndUnionDues($val['emp_id'], $gData['year'])+$prevEmployer['nt_statutories'];
				$sheet->setCellValue('I'.$cell,$stat_nt);
				
				$other_comp_nt = $this->getOtherCompensation($val['emp_id'], $gData['year'])+$prevEmployer['nt_compensation'];
				$sheet->setCellValue('J'.$cell,$other_comp_nt);
				
				$sheet->setCellValue('K'.$cell,'=G' . $cell . '+H' . $cell . '+I' . $cell . '+J' . $cell . ')');
				
				$basic_taxable = ($prevEmployer['taxable_basic']-$prevEmployer['nt_statutories'])+($this->getBasicIncome($val['emp_id'], $gData['year'])-$this->getStatutoryAndUnionDues($val['emp_id'], $gData['year']));
				$sheet->setCellValue('L'.$cell,$basic_taxable);
				
				$bonus_taxable = $this->getBonus($val['emp_id'], $gData['year'],1)+$prevEmployer['taxable_other_ben'];
				$sheet->setCellValue('M'.$cell,$bonus_taxable+$addtn_bonus_taxable);
				
				$other_comp_taxable = $this->getOtherCompensationTaxable($val['emp_id'], $gData['year'])+$prevEmployer['taxable_compensation'];
				$sheet->setCellValue('N'.$cell,$other_comp_taxable);
				
				$sheet->setCellValue('O'.$cell,'=L' . $cell . '+M' . $cell . '+N' . $cell . ')');
				$sheet->setCellValue('P'.$cell,$taxep_code);
				$sheet->setCellValue('Q'.$cell,$this->getExemptAmount($taxep_code));
				//$sheet->setCellValue('R'.$cell,$this->getHealthInsurance($val['emp_id'], $gData['year']));
				$sheet->setCellValue('R'.$cell,'0.00');
				$sheet->setCellValue('S'.$cell,'=IF((O'.$cell.'-Q'.$cell.')<=0,0,O'.$cell.'-Q'.$cell.')');
				//$taxgross = ($this->getBasicIncome($val['emp_id'], $gData['year'])-$this->getStatutoryAndUnionDues($val['emp_id'], $gData['year']))+$this->getBonus($val['emp_id'], $gData['year'],1)+$this->getOtherCompensationTaxable($val['emp_id'], $gData['year']);
				
				$taxgross = $basic_taxable+$bonus_taxable+$other_comp_taxable+$addtn_bonus_taxable;
				$sheet->setCellValue('T'.$cell,$this->getAnnualTaxDue($val['emp_id'],$gData['year'],$taxep_code,$taxgross));
				$sheet->setCellValue('U'.$cell,$this->getTaxWithheld($val['emp_id'], $gData['year'])+$prevEmployer['tax_withheld']);
				$sheet->setCellValue('V'.$cell,$this->getTaxWithheldDecember($val['emp_id'], $gData['year']));
				$sheet->getStyle('V'.$cell)->getNumberFormat()->setFormatCode('###,###,###,##0.00');
				$sheet->setCellValue('W'.$cell,'=(U'.$cell.'+V'.$cell.')-T'.$cell);
				$sheet->getStyle('W'.$cell)->getNumberFormat()->setFormatCode('###,###,###,##0.00');
				$sheet->setCellValue('X'.$cell,'=(U'.$cell.'+V'.$cell.')-W'.$cell);
				$sheet->getStyle('X'.$cell)->getNumberFormat()->setFormatCode('###,###,###,##0.00');
				$sheet->setCellValue('Y'.$cell,'N');
				$count++;
				$end_cell = $cell;
				$cell++;
			}
		} else {
			$sheet->setCellValue('A'.$start_cell,'No Data Found!');
			$sheet->mergeCells('A'.$start_cell.':Y'.$start_cell);
			// Bold
			$styleArray = array('font' => array('bold' => true));
			$sheet->getStyle('A'.$start_cell.':Y'.$start_cell)->applyFromArray($styleArray);
		}
		// footer
		if (count($emp) > 0) {
			$dash = '------------------';
			$sheet->setCellValue('F'.$cell,$dash);
			$sheet->setCellValue('G'.$cell,$dash);
			$sheet->setCellValue('H'.$cell,$dash);
			$sheet->setCellValue('I'.$cell,$dash);
			$sheet->setCellValue('J'.$cell,$dash);
			$sheet->setCellValue('K'.$cell,$dash);
			$sheet->setCellValue('L'.$cell,$dash);
			$sheet->setCellValue('M'.$cell,$dash);
			$sheet->setCellValue('N'.$cell,$dash);
			$sheet->setCellValue('O'.$cell,$dash);
			$sheet->setCellValue('Q'.$cell,$dash);
			$sheet->setCellValue('R'.$cell,$dash);
			$sheet->setCellValue('S'.$cell,$dash);
			$sheet->setCellValue('T'.$cell,$dash);
			$sheet->setCellValue('U'.$cell,$dash);
			$sheet->setCellValue('V'.$cell,$dash);
			$sheet->setCellValue('W'.$cell,$dash);
			$sheet->setCellValue('X'.$cell,$dash);
			$cell++;
			$sheet->setCellValue('A'.$cell,'Grand Total :');
			$sheet->setCellValue('F'.$cell,'=SUM(F'.$start_cell.':F'.$end_cell.')');
			$sheet->setCellValue('G'.$cell,'=SUM(G'.$start_cell.':G'.$end_cell.')');
			$sheet->setCellValue('H'.$cell,'=SUM(H'.$start_cell.':H'.$end_cell.')');
			$sheet->setCellValue('I'.$cell,'=SUM(I'.$start_cell.':I'.$end_cell.')');
			$sheet->setCellValue('J'.$cell,'=SUM(J'.$start_cell.':J'.$end_cell.')');
			$sheet->setCellValue('K'.$cell,'=SUM(K'.$start_cell.':K'.$end_cell.')');
			$sheet->setCellValue('L'.$cell,'=SUM(L'.$start_cell.':L'.$end_cell.')');
			$sheet->setCellValue('M'.$cell,'=SUM(M'.$start_cell.':M'.$end_cell.')');
			$sheet->setCellValue('N'.$cell,'=SUM(N'.$start_cell.':N'.$end_cell.')');
			$sheet->setCellValue('O'.$cell,'=SUM(O'.$start_cell.':O'.$end_cell.')');
			$sheet->setCellValue('Q'.$cell,'=SUM(Q'.$start_cell.':Q'.$end_cell.')');
			$sheet->setCellValue('R'.$cell,'=SUM(R'.$start_cell.':R'.$end_cell.')');
			$sheet->setCellValue('S'.$cell,'=SUM(S'.$start_cell.':S'.$end_cell.')');
			$sheet->setCellValue('T'.$cell,'=SUM(T'.$start_cell.':T'.$end_cell.')');
			$sheet->setCellValue('U'.$cell,'=SUM(U'.$start_cell.':U'.$end_cell.')');
			$sheet->setCellValue('V'.$cell,'=SUM(V'.$start_cell.':V'.$end_cell.')');
			$sheet->setCellValue('W'.$cell,'=SUM(W'.$start_cell.':W'.$end_cell.')');
			$sheet->setCellValue('X'.$cell,'=SUM(X'.$start_cell.':X'.$end_cell.')');
			// Bold
			$styleArray = array('font' => array('bold' => true));
			$sheet->getStyle('A'.$cell.':Y'.$cell)->applyFromArray($styleArray);
			$cell++;
			$dash2 = '--------------------------';
			$sheet->setCellValue('F'.$cell,$dash2);
			$sheet->setCellValue('G'.$cell,$dash2);
			$sheet->setCellValue('H'.$cell,$dash2);
			$sheet->setCellValue('I'.$cell,$dash2);
			$sheet->setCellValue('J'.$cell,$dash2);
			$sheet->setCellValue('K'.$cell,$dash2);
			$sheet->setCellValue('L'.$cell,$dash2);
			$sheet->setCellValue('M'.$cell,$dash2);
			$sheet->setCellValue('N'.$cell,$dash2);
			$sheet->setCellValue('O'.$cell,$dash2);
			$sheet->setCellValue('Q'.$cell,$dash2);
			$sheet->setCellValue('R'.$cell,$dash2);
			$sheet->setCellValue('S'.$cell,$dash2);
			$sheet->setCellValue('T'.$cell,$dash2);
			$sheet->setCellValue('U'.$cell,$dash2);
			$sheet->setCellValue('V'.$cell,$dash2);
			$sheet->setCellValue('W'.$cell,$dash2);
			$sheet->setCellValue('X'.$cell,$dash2);
		}
		$cell++;
		$sheet->setCellValue('A'.$cell,'END OF REPORT');
		$sheet->getStyle('A'.$cell)->applyFromArray($styleArray);// Bold
		$sheet->setTitle($filename);// Rename sheet
		$objPHPExcel->setActiveSheetIndex(0);// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		// Redirect output to a client's web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename='.$filename);
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
    }
    
    /**
     * @note: Schedule 7.2 Generation
     * @param $gData
     */
    function get1604CF7_2($gData = array()) {
    	IF($gData['branchinfo_id']!=0){//Get company Details
    		$loc_details = clsSSS::getLocationInfo($gData['branchinfo_id']);
    		$company_name = $loc_details['branchinfo_name'];
    		$company_tin = $loc_details['branchinfo_tin'];
    	}ELSE{
    		$comp_details = clsSSS::dbfetchCompDetails($gData['comp']);
    		$company_name = $comp_details['comp_name'];
    		$company_tin = $comp_details['comp_tin'];
    	}
    	$emp = $this->getEmpNoPreEmployer($gData,1);
    	// The file name you want any resulting file to be called.
    	$filename = "BIR1604CF_SCHEDULE_7.2_".$gData['year'].".xls";
    	// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		$objReader = PHPExcel_IOFactory::createReader('Excel5');
		$objPHPExcel = $objReader->load("templates/BIR_FORM_1604CF_SCHEDULE_7.2.xls");
		// Write Data
		// header
		$sheet = $objPHPExcel->getActiveSheet();
		$sheet->setCellValue('A3','AS OF DECEMBER 31, '.$gData['year']);
		$sheet->setCellValue('A6','TIN: '.$company_tin);
		$sheet->setCellValue('A7','WITHHOLDING AGENT\'S NAME: '.$company_name);
		
		// body - data
		$count = 1;
		$start_cell = 16;
		$cell = $start_cell;
		if (count($emp) > 0) {
			$tax_policy = $this->getTaxPolicy();
			foreach ($emp as $key => $val) {
				$tin = str_replace("-","",$val['pi_tin']);
				
				switch ($val['taxep_code']) {
					case 'ME': $taxep_code = 'M'; break;
					case 'ME1': $taxep_code = 'M1'; break;
					case 'ME2': $taxep_code = 'M2'; break;
					case 'ME3': $taxep_code = 'M3'; break;
					case 'ME4': $taxep_code = 'M4'; break;
					default: $taxep_code = $val['taxep_code']; break;
				}
				$sheet->setCellValue('A'.$cell,$count);
				$sheet->setCellValue('B'.$cell,$tin);
				$sheet->setCellValue('C'.$cell,strtoupper($val['fullname']));
				$sheet->setCellValue('D'.$cell,'=I'.$cell.'+M'.$cell);
				$sheet->setCellValue('E'.$cell,$this->getBonus($val['emp_id'], $gData['year'],0));
				$sheet->setCellValue('F'.$cell,$this->getDeminimis($val['emp_id'], $gData['year']));
				$sheet->setCellValue('G'.$cell,$this->getStatutoryAndUnionDues($val['emp_id'], $gData['year']));
				$sheet->setCellValue('H'.$cell,$this->getOtherCompensation($val['emp_id'], $gData['year']));
				$sheet->setCellValue('I'.$cell,'=E' . $cell . '+F' . $cell . '+G' . $cell . '+H' . $cell . ')');
				$sheet->setCellValue('J'.$cell,$this->getBasicIncome($val['emp_id'], $gData['year'])-$this->getStatutoryAndUnionDues($val['emp_id'], $gData['year']));
				$sheet->setCellValue('K'.$cell,$this->getOtherCompensationTaxable($val['emp_id'], $gData['year']));
				$sheet->setCellValue('L'.$cell,$taxep_code);
				$sheet->setCellValue('M'.$cell,$this->getExemptAmount($taxep_code));
				//$sheet->setCellValue('N'.$cell,$this->getHealthInsurance($val['emp_id'], $gData['year']));
				$sheet->setCellValue('N'.$cell,'0.00');
				$sheet->setCellValue('O'.$cell,'=IF((M'.$cell.'-(J'.$cell.'+K'.$cell.'))<=0,0,M'.$cell.'-(J'.$cell.'+K'.$cell.'))');
				$sheet->setCellValue('P'.$cell,$this->getAnnualTaxDue($val['emp_id'],$gData['year'],$taxep_code,0));
				$count++;
				$end_cell = $cell;
				$cell++;
			}
		} else {
			$sheet->setCellValue('A'.$start_cell,'No Data Found!');
			$sheet->mergeCells('A'.$start_cell.':Y'.$start_cell);
			// Bold
			$styleArray = array('font' => array('bold' => true));
			$sheet->getStyle('A'.$start_cell.':Y'.$start_cell)->applyFromArray($styleArray);
		}
		
		// footer
		if (count($emp) > 0) {
			$dash = '------------------';
			$sheet->setCellValue('D'.$cell,$dash);
			$sheet->setCellValue('E'.$cell,$dash);
			$sheet->setCellValue('F'.$cell,$dash);
			$sheet->setCellValue('G'.$cell,$dash);
			$sheet->setCellValue('H'.$cell,$dash);
			$sheet->setCellValue('I'.$cell,$dash);
			$sheet->setCellValue('J'.$cell,$dash);
			$sheet->setCellValue('K'.$cell,$dash);
			$sheet->setCellValue('M'.$cell,$dash);
			$sheet->setCellValue('N'.$cell,$dash);
			$sheet->setCellValue('O'.$cell,$dash);
			$sheet->setCellValue('P'.$cell,$dash);
			$cell++;
			$sheet->setCellValue('A'.$cell,'Grand Total :');
			$sheet->setCellValue('D'.$cell,'=SUM(D'.$start_cell.':D'.$end_cell.')');
			$sheet->setCellValue('E'.$cell,'=SUM(E'.$start_cell.':E'.$end_cell.')');
			$sheet->setCellValue('F'.$cell,'=SUM(F'.$start_cell.':F'.$end_cell.')');
			$sheet->setCellValue('G'.$cell,'=SUM(G'.$start_cell.':G'.$end_cell.')');
			$sheet->setCellValue('H'.$cell,'=SUM(H'.$start_cell.':H'.$end_cell.')');
			$sheet->setCellValue('I'.$cell,'=SUM(I'.$start_cell.':I'.$end_cell.')');
			$sheet->setCellValue('J'.$cell,'=SUM(J'.$start_cell.':J'.$end_cell.')');
			$sheet->setCellValue('K'.$cell,'=SUM(K'.$start_cell.':K'.$end_cell.')');
			$sheet->setCellValue('M'.$cell,'=SUM(M'.$start_cell.':M'.$end_cell.')');
			$sheet->setCellValue('N'.$cell,'=SUM(N'.$start_cell.':N'.$end_cell.')');
			$sheet->setCellValue('O'.$cell,'=SUM(O'.$start_cell.':O'.$end_cell.')');
			$sheet->setCellValue('P'.$cell,'=SUM(P'.$start_cell.':P'.$end_cell.')');
			// Bold
			$styleArray = array('font' => array('bold' => true));
			$sheet->getStyle('A'.$cell.':P'.$cell)->applyFromArray($styleArray);
			
			$cell++;
			$dash2 = '--------------------------';
			$sheet->setCellValue('D'.$cell,$dash2);
			$sheet->setCellValue('E'.$cell,$dash2);
			$sheet->setCellValue('F'.$cell,$dash2);
			$sheet->setCellValue('G'.$cell,$dash2);
			$sheet->setCellValue('H'.$cell,$dash2);
			$sheet->setCellValue('I'.$cell,$dash2);
			$sheet->setCellValue('J'.$cell,$dash2);
			$sheet->setCellValue('K'.$cell,$dash2);
			$sheet->setCellValue('M'.$cell,$dash2);
			$sheet->setCellValue('N'.$cell,$dash2);
			$sheet->setCellValue('O'.$cell,$dash2);
			$sheet->setCellValue('P'.$cell,$dash2);
		}
		$cell++;
		$sheet->setCellValue('A'.$cell,'END OF REPORT');
		$sheet->getStyle('A'.$cell)->applyFromArray($styleArray);
		// Rename sheet
		$sheet->setTitle($filename);
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);
		// Redirect output to a client's web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename='.$filename);
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
    }
    
    /**
     * @note Schedule 7.3 Generation
     * @param $gData
     */
	function get1604CF7_3($gData = array()) {
		IF($gData['branchinfo_id']!=0){//Get company Details
    		$loc_details = clsSSS::getLocationInfo($gData['branchinfo_id']);
    		$company_name = $loc_details['branchinfo_name'];
    		$company_tin = $loc_details['branchinfo_tin'];
    	}ELSE{
    		$comp_details = clsSSS::dbfetchCompDetails($gData['comp']);
    		$company_name = $comp_details['comp_name'];
    		$company_tin = $comp_details['comp_tin'];
    	}
    	$emp = $this->getEmpNoPreEmployer($gData);
    	// The file name you want any resulting file to be called.
    	$filename = "BIR1604CF_SCHEDULE_7.3_".$gData['year'].".xls";
    	// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		$objReader = PHPExcel_IOFactory::createReader('Excel5');
		$objPHPExcel = $objReader->load("templates/BIR_FORM_1604CF_SCHEDULE_7.3.xls");
		// Write Data
		// header
		$sheet = $objPHPExcel->getActiveSheet();
		$sheet->setCellValue('A3','AS OF DECEMBER 31, '.$gData['year']);
		$sheet->setCellValue('A6','TIN: '.$company_tin);
		$sheet->setCellValue('A7','WITHHOLDING AGENT\'S NAME: '.$company_name);
		
		// body - data
		$count = 1;
		$start_cell = 17;
		$cell = $start_cell;
		if (count($emp) > 0) {
			$tax_policy = $this->getTaxPolicy();
			foreach ($emp as $key => $val) {
				$tin = str_replace("-","",$val['pi_tin']);
				
				switch ($val['taxep_code']) {
					case 'ME': $taxep_code = 'M'; break;
					case 'ME1': $taxep_code = 'M1'; break;
					case 'ME2': $taxep_code = 'M2'; break;
					case 'ME3': $taxep_code = 'M3'; break;
					case 'ME4': $taxep_code = 'M4'; break;
					default: $taxep_code = $val['taxep_code']; break;
				}
				
				$sheet->setCellValue('A'.$cell,$count);
				$sheet->setCellValue('B'.$cell,$tin);
				$sheet->setCellValue('C'.$cell,strtoupper($val['fullname']));
				// GROSS COMPENSATION INCOME = TOTAL NON-TAXABLE/EXEMPT COMPENSATION INCOME + TOTAL TAXABLE COMPENSATION INCOME
				$sheet->setCellValue('D'.$cell,'=I'.$cell.'+M'.$cell);
				if($this->getBonus($val['emp_id'], $gData['year'],0)>$tax_policy['tp_other_benefits']){
					$bonus_nt = $tax_policy['tp_other_benefits'];
					$addtn_bonus_taxable = $this->getBonus($val['emp_id'], $gData['year'],0)-$tax_policy['tp_other_benefits'];
				} else {
					$bonus_nt = $this->getBonus($val['emp_id'], $gData['year'],0);
					$addtn_bonus_taxable = 0;
				}
				$sheet->setCellValue('E'.$cell,$bonus_nt);
				$sheet->setCellValue('F'.$cell,$this->getDeminimis($val['emp_id'], $gData['year']));
				$sheet->setCellValue('G'.$cell,$this->getStatutoryAndUnionDues($val['emp_id'], $gData['year']));
				$sheet->setCellValue('H'.$cell,$this->getOtherCompensation($val['emp_id'], $gData['year']));
				$sheet->setCellValue('I'.$cell,'=E' . $cell . '+F' . $cell . '+G' . $cell . '+H' . $cell . ')');
				$sheet->setCellValue('J'.$cell,$this->getBasicIncome($val['emp_id'], $gData['year'])-$this->getStatutoryAndUnionDues($val['emp_id'], $gData['year']));
				$sheet->setCellValue('K'.$cell,$this->getBonus($val['emp_id'], $gData['year'],1)+$addtn_bonus_taxable);
				$sheet->setCellValue('L'.$cell,$this->getOtherCompensationTaxable($val['emp_id'], $gData['year']));
				$sheet->setCellValue('M'.$cell,'=J' . $cell . '+K' . $cell . '+L' . $cell . ')');
				$taxgross = ($this->getBasicIncome($val['emp_id'], $gData['year'])-$this->getStatutoryAndUnionDues($val['emp_id'], $gData['year']))+$this->getBonus($val['emp_id'], $gData['year'],1)+$this->getOtherCompensationTaxable($val['emp_id'], $gData['year'])+$addtn_bonus_taxable;
				$sheet->setCellValue('N'.$cell,$taxep_code);
				$sheet->setCellValue('O'.$cell,$this->getExemptAmount($taxep_code));
				//$sheet->setCellValue('P'.$cell,$this->getHealthInsurance($val['emp_id'], $gData['year']));
				$sheet->setCellValue('P'.$cell,'0.00');
				$sheet->setCellValue('Q'.$cell,'=IF((M'.$cell.'-O'.$cell.')<=0,0,M'.$cell.'-O'.$cell.')');
				$sheet->setCellValue('R'.$cell,$this->getAnnualTaxDue($val['emp_id'],$gData['year'],$taxep_code,$taxgross));
				$sheet->setCellValue('S'.$cell,$this->getTaxWithheld($val['emp_id'], $gData['year']));
				$sheet->setCellValue('T'.$cell,$this->getTaxWithheldDecember($val['emp_id'], $gData['year']));
				$sheet->setCellValue('U'.$cell,'=(S'.$cell.'+T'.$cell.')-R'.$cell);
				$sheet->setCellValue('V'.$cell,'=(S'.$cell.'+T'.$cell.')-U'.$cell);
				$sheet->setCellValue('W'.$cell,'N');
				$count++;
				$end_cell = $cell;
				$cell++;
			}
		} else {
			$sheet->setCellValue('A'.$start_cell,'No Data Found!');
			$sheet->mergeCells('A'.$start_cell.':W'.$start_cell);
			// Bold
			$styleArray = array('font' => array('bold' => true));
			$sheet->getStyle('A'.$start_cell.':W'.$start_cell)->applyFromArray($styleArray);
		}
		
		// footer
		if (count($emp) > 0) {
			$dash = '------------------';
			$sheet->setCellValue('D'.$cell,$dash);
			$sheet->setCellValue('E'.$cell,$dash);
			$sheet->setCellValue('F'.$cell,$dash);
			$sheet->setCellValue('G'.$cell,$dash);
			$sheet->setCellValue('H'.$cell,$dash);
			$sheet->setCellValue('I'.$cell,$dash);
			$sheet->setCellValue('J'.$cell,$dash);
			$sheet->setCellValue('K'.$cell,$dash);
			$sheet->setCellValue('L'.$cell,$dash);
			$sheet->setCellValue('M'.$cell,$dash);
			$sheet->setCellValue('O'.$cell,$dash);
			$sheet->setCellValue('P'.$cell,$dash);
			$sheet->setCellValue('Q'.$cell,$dash);
			$sheet->setCellValue('R'.$cell,$dash);
			$sheet->setCellValue('S'.$cell,$dash);
			$sheet->setCellValue('T'.$cell,$dash);
			$sheet->setCellValue('U'.$cell,$dash);
			$sheet->setCellValue('V'.$cell,$dash);
			$cell++;
			$sheet->setCellValue('A'.$cell,'Grand Total :');
			$sheet->setCellValue('D'.$cell,'=SUM(D'.$start_cell.':D'.$end_cell.')');
			$sheet->setCellValue('E'.$cell,'=SUM(E'.$start_cell.':E'.$end_cell.')');
			$sheet->setCellValue('F'.$cell,'=SUM(F'.$start_cell.':F'.$end_cell.')');
			$sheet->setCellValue('G'.$cell,'=SUM(G'.$start_cell.':G'.$end_cell.')');
			$sheet->setCellValue('H'.$cell,'=SUM(H'.$start_cell.':H'.$end_cell.')');
			$sheet->setCellValue('I'.$cell,'=SUM(I'.$start_cell.':I'.$end_cell.')');
			$sheet->setCellValue('J'.$cell,'=SUM(J'.$start_cell.':J'.$end_cell.')');
			$sheet->setCellValue('K'.$cell,'=SUM(K'.$start_cell.':K'.$end_cell.')');
			$sheet->setCellValue('L'.$cell,'=SUM(L'.$start_cell.':L'.$end_cell.')');
			$sheet->setCellValue('M'.$cell,'=SUM(M'.$start_cell.':M'.$end_cell.')');
			$sheet->setCellValue('O'.$cell,'=SUM(O'.$start_cell.':O'.$end_cell.')');
			$sheet->setCellValue('P'.$cell,'=SUM(P'.$start_cell.':P'.$end_cell.')');
			$sheet->setCellValue('Q'.$cell,'=SUM(Q'.$start_cell.':Q'.$end_cell.')');
			$sheet->setCellValue('R'.$cell,'=SUM(R'.$start_cell.':R'.$end_cell.')');
			$sheet->setCellValue('S'.$cell,'=SUM(S'.$start_cell.':S'.$end_cell.')');
			$sheet->setCellValue('T'.$cell,'=SUM(T'.$start_cell.':T'.$end_cell.')');
			$sheet->setCellValue('U'.$cell,'=SUM(U'.$start_cell.':U'.$end_cell.')');
			$sheet->setCellValue('V'.$cell,'=SUM(V'.$start_cell.':V'.$end_cell.')');
			// Bold
			$styleArray = array('font' => array('bold' => true));
			$sheet->getStyle('A'.$cell.':W'.$cell)->applyFromArray($styleArray);
			
			$cell++;
			$dash2 = '--------------------------';
			$sheet->setCellValue('D'.$cell,$dash2);
			$sheet->setCellValue('E'.$cell,$dash2);
			$sheet->setCellValue('F'.$cell,$dash2);
			$sheet->setCellValue('G'.$cell,$dash2);
			$sheet->setCellValue('H'.$cell,$dash2);
			$sheet->setCellValue('I'.$cell,$dash2);
			$sheet->setCellValue('J'.$cell,$dash2);
			$sheet->setCellValue('K'.$cell,$dash2);
			$sheet->setCellValue('L'.$cell,$dash2);
			$sheet->setCellValue('M'.$cell,$dash2);
			$sheet->setCellValue('O'.$cell,$dash2);
			$sheet->setCellValue('P'.$cell,$dash2);
			$sheet->setCellValue('Q'.$cell,$dash2);
			$sheet->setCellValue('R'.$cell,$dash2);
			$sheet->setCellValue('S'.$cell,$dash2);
			$sheet->setCellValue('T'.$cell,$dash2);
			$sheet->setCellValue('U'.$cell,$dash2);
			$sheet->setCellValue('V'.$cell,$dash2);
		}
		$cell++;
		$sheet->setCellValue('A'.$cell,'END OF REPORT');
		$sheet->getStyle('A'.$cell)->applyFromArray($styleArray);// Bold
		$sheet->setTitle($filename);// Rename sheet
		$objPHPExcel->setActiveSheetIndex(0);// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		// Redirect output to a client's web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename='.$filename);
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
    }
    
	function get1604CF7_4($gData = array()) {
		IF($gData['branchinfo_id']!=0){//Get company Details
    		$loc_details = clsSSS::getLocationInfo($gData['branchinfo_id']);
    		$company_name = $loc_details['branchinfo_name'];
    		$company_tin = $loc_details['branchinfo_tin'];
    	}ELSE{
    		$comp_details = clsSSS::dbfetchCompDetails($gData['comp']);
    		$company_name = $comp_details['comp_name'];
    		$company_tin = $comp_details['comp_tin'];
    	}
    	$emp = $this->getEmployeesWithPreviousEmployer($gData);
    	
    	$filename = "BIR1604CF_SCHEDULE_7.4_".$gData['year'].".xls";
    	// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		$objReader = PHPExcel_IOFactory::createReader('Excel5');
		$objPHPExcel = $objReader->load("templates/BIR_FORM_1604CF_SCHEDULE_7.4.xls");
		//Write Data
		//header
		$sheet = $objPHPExcel->getActiveSheet();
		$sheet->setCellValue('A3','AS OF DECEMBER 31, '.$gData['year']);
		$sheet->setCellValue('A4','TIN: '.$company_tin);
		$sheet->setCellValue('A5','WITHHOLDING AGENT\'S NAME: '.$company_name);
		
		//body - data
		$count = 1;
		$start_cell = 16;
		$cell = $start_cell;
		if (count($emp)>0) {
			$tax_policy = $this->getTaxPolicy();
			foreach ($emp as $key => $val) {
				$tin = str_replace("-","",$val['pi_tin']);
				switch ($val['taxep_code']) {
					case 'ME': $taxep_code = 'M'; break;
					case 'ME1': $taxep_code = 'M1'; break;
					case 'ME2': $taxep_code = 'M2'; break;
					case 'ME3': $taxep_code = 'M3'; break;
					case 'ME4': $taxep_code = 'M4'; break;
					default: $taxep_code = $val['taxep_code']; break;
				}
				$sheet->setCellValue('A'.$cell,$count);
				$sheet->setCellValue('B'.$cell,$val['pi_tin']);
				$sheet->setCellValue('C'.$cell,$val['fullname']);
				//$sheet->setCellValue('D'.$cell,$this->getGrossCompensation($val['emp_id'], $gData['year']));
				$sheet->setCellValue('D'.$cell,"=I".$cell."+R".$cell."+W".$cell);
				$sheet->setCellValue('E'.$cell,$val['nt_other_ben']);
				$sheet->setCellValue('F'.$cell,$val['nt_deminimis']);
				$sheet->setCellValue('G'.$cell,$val['nt_statutories']);
				$sheet->setCellValue('H'.$cell,$val['nt_compensation']);
				$sheet->setCellValue('I'.$cell,"=SUM(E".$cell.":H".$cell.")");
				$sheet->setCellValue('J'.$cell,$val['taxable_basic']);
				$sheet->setCellValue('K'.$cell,$val['taxable_other_ben']);
				$sheet->setCellValue('L'.$cell,$val['taxable_compensation']);
				$sheet->setCellValue('M'.$cell,"=SUM(J".$cell.":L".$cell.")-G".$cell);
				IF($val['nt_other_ben'] >= $tax_policy['tp_other_benefits']){
					$bonus_nt = 0;
					$addtn_bonus_taxable = $this->getBonus($val['emp_id'], $gData['year'],0);
				} ELSE {
					if($this->getBonus($val['emp_id'], $gData['year'],0)>$tax_policy['tp_other_benefits']){
						$bonus_nt = $tax_policy['tp_other_benefits'];
						$addtn_bonus_taxable = $this->getBonus($val['emp_id'], $gData['year'],0)-$tax_policy['tp_other_benefits'];
					} else {
						$bonus_nt = $this->getBonus($val['emp_id'], $gData['year'],0);
						$addtn_bonus_taxable = 0;
					}
				}
				$sheet->setCellValue('N'.$cell,$bonus_nt);
				$sheet->setCellValue('O'.$cell,$this->getDeminimis($val['emp_id'], $gData['year']));
				$sheet->setCellValue('P'.$cell,$this->getStatutoryAndUnionDues($val['emp_id'], $gData['year']));
				$sheet->setCellValue('Q'.$cell,$this->getOtherCompensation($val['emp_id'], $gData['year']));
				$sheet->setCellValue('R'.$cell,"=SUM(N".$cell.":Q".$cell.")");
				$sheet->setCellValue('S'.$cell,$this->getBasicIncome($val['emp_id'], $gData['year'])-$this->getStatutoryAndUnionDues($val['emp_id'], $gData['year']));
				$sheet->setCellValue('T'.$cell,$this->getBonus($val['emp_id'], $gData['year'],1)+$addtn_bonus_taxable);
				$sheet->setCellValue('U'.$cell,$this->getOtherCompensationTaxable($val['emp_id'], $gData['year']));
				$sheet->setCellValue('V'.$cell,"=SUM(S".$cell.":U".$cell.")");
				$sheet->setCellValue('W'.$cell,"=M".$cell."+V".$cell);
				$sheet->setCellValue('X'.$cell,$val['taxep_code']);
				$sheet->setCellValue('Y'.$cell,$this->getExemptAmount($taxep_code));
				//$sheet->setCellValue('Z'.$cell,$this->getHealthInsurance($val['emp_id'], $gData['year']));
				$sheet->setCellValue('Z'.$cell,'0.00');
				$sheet->setCellValue('AA'.$cell,'=IF((W'.$cell.'-Y'.$cell.')<=0,0,W'.$cell.'-Y'.$cell.')');
				//$taxgross = $this->getBasicIncome($val['emp_id'], $gData['year'])+$val['taxable_basic']+$val['taxable_other_ben']+$val['taxable_compensation'];
				$taxgross = (($val['taxable_basic']+$val['taxable_other_ben']+$val['taxable_compensation'])-$val['nt_statutories'])+(($this->getBasicIncome($val['emp_id'], $gData['year'])-$this->getStatutoryAndUnionDues($val['emp_id'], $gData['year']))+$this->getBonus($val['emp_id'], $gData['year'],1)+$this->getOtherCompensationTaxable($val['emp_id'], $gData['year']))+$addtn_bonus_taxable;
				$sheet->setCellValue('AB'.$cell,$this->getAnnualTaxDue($val['emp_id'],$gData['year'],$taxep_code,$taxgross));
				$sheet->setCellValue('AC'.$cell,$val['tax_withheld']); // tax withheld - previous employer
				$sheet->setCellValue('AD'.$cell,$this->getTaxWithheld($val['emp_id'], $gData['year']));
				$sheet->setCellValue('AE'.$cell,$this->getTaxWithheldDecember($val['emp_id'], $gData['year']));
				$sheet->setCellValue('AF'.$cell,"=((AC".$cell."+AD".$cell.")+AE".$cell.")-AB".$cell);
				$sheet->setCellValue('AG'.$cell,"=((AC".$cell."+AD".$cell.")+AE".$cell.")-AF".$cell);
				/**$sheet->setCellValue('E'.$cell,$this->getBonus($val['emp_id'], $gData['year']));
				$sheet->setCellValue('F'.$cell,$this->getDeminimis($val['emp_id'], $gData['year']));
				$sheet->setCellValue('G'.$cell,$this->getStatutoryAndUnionDues($val['emp_id'], $gData['year']));
				$sheet->setCellValue('H'.$cell,$this->getOtherCompensation($val['emp_id'], $gData['year']));
				$sheet->setCellValue('I'.$cell,'=E' . $cell . '+F' . $cell . '+G' . $cell . '+H' . $cell . ')');
				$sheet->setCellValue('J'.$cell,$this->getBasicIncome($val['emp_id'], $gData['year'])-$this->getStatutoryAndUnionDues($val['emp_id'], $gData['year']));
				$sheet->setCellValue('K'.$cell,'0.00');
				$sheet->setCellValue('L'.$cell,$this->getOtherCompensationTaxable($val['emp_id'], $gData['year']));
				$sheet->setCellValue('M'.$cell,'=J' . $cell . '+K' . $cell . '+L' . $cell . ')');
				$sheet->setCellValue('N'.$cell,$val['taxep_code']);
				$sheet->setCellValue('O'.$cell,$this->getExemptAmount($val['taxep_code']));
				$sheet->setCellValue('P'.$cell,'0.00');
				$sheet->setCellValue('Q'.$cell,'=IF((M'.$cell.'-O'.$cell.')<=0,0,M'.$cell.'-O'.$cell.')');
				$sheet->setCellValue('R'.$cell,'0.00');
				$sheet->setCellValue('S'.$cell,$this->getTaxWithheld($val['emp_id'], $gData['year']));
				$sheet->setCellValue('T'.$cell,'=R'.$cell.'-S'.$cell);
				$sheet->setCellValue('U'.$cell,'=S'.$cell.'-R'.$cell);
				$sheet->setCellValue('V'.$cell,'=S'.$cell.'-U'.$cell);
				$sheet->setCellValue('W'.$cell,'N');
				//gross = non-taxable + taxable
				$sheet->setCellValue('D'.$cell,'=I'.$cell.'+M'.$cell);**/
				$styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_DASHED)));
				$sheet->getStyle('A'.$cell.':AG'.$cell)->applyFromArray($styleArray);
				unset($styleArray);
				$count++;
				$end_cell = $cell;
				$cell++;
			}
		} else {
			$sheet->setCellValue('A14','No Record Found!');
			$sheet->mergeCells('A14:W14');
		}
		
		//footer
		$styleArray = array('font' => array('bold' => true));
		if (count($emp)>0) {
			$cell++;
			$sheet->getStyle('D'.$cell.':W'.$cell)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
			$sheet->getStyle('Y'.$cell.':AG'.$cell)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
			$sheet->setCellValue('A'.$cell,'Grand Total');
			$sheet->setCellValue('D'.$cell,'=sum(D'.$start_cell.':D'.$end_cell.')');
			$sheet->setCellValue('E'.$cell,'=sum(E'.$start_cell.':E'.$end_cell.')');
			$sheet->setCellValue('F'.$cell,'=sum(F'.$start_cell.':F'.$end_cell.')');
			$sheet->setCellValue('G'.$cell,'=sum(G'.$start_cell.':G'.$end_cell.')');
			$sheet->setCellValue('H'.$cell,'=sum(H'.$start_cell.':H'.$end_cell.')');
			$sheet->setCellValue('I'.$cell,'=sum(I'.$start_cell.':I'.$end_cell.')');
			$sheet->setCellValue('J'.$cell,'=sum(J'.$start_cell.':J'.$end_cell.')');
			$sheet->setCellValue('K'.$cell,'=sum(K'.$start_cell.':K'.$end_cell.')');
			$sheet->setCellValue('L'.$cell,'=sum(L'.$start_cell.':L'.$end_cell.')');
			$sheet->setCellValue('M'.$cell,'=sum(M'.$start_cell.':M'.$end_cell.')');
			$sheet->setCellValue('N'.$cell,'=sum(N'.$start_cell.':N'.$end_cell.')');
			$sheet->setCellValue('O'.$cell,'=sum(O'.$start_cell.':O'.$end_cell.')');
			$sheet->setCellValue('P'.$cell,'=sum(P'.$start_cell.':P'.$end_cell.')');
			$sheet->setCellValue('Q'.$cell,'=sum(Q'.$start_cell.':Q'.$end_cell.')');
			$sheet->setCellValue('R'.$cell,'=sum(R'.$start_cell.':R'.$end_cell.')');
			$sheet->setCellValue('S'.$cell,'=sum(S'.$start_cell.':S'.$end_cell.')');
			$sheet->setCellValue('T'.$cell,'=sum(T'.$start_cell.':T'.$end_cell.')');
			$sheet->setCellValue('U'.$cell,'=sum(U'.$start_cell.':U'.$end_cell.')');
			$sheet->setCellValue('V'.$cell,'=sum(V'.$start_cell.':V'.$end_cell.')');
			$sheet->setCellValue('W'.$cell,'=sum(W'.$start_cell.':W'.$end_cell.')');
			$sheet->setCellValue('Y'.$cell,'=sum(Y'.$start_cell.':Y'.$end_cell.')');
			$sheet->setCellValue('Z'.$cell,'=sum(Z'.$start_cell.':Z'.$end_cell.')');
			$sheet->setCellValue('AA'.$cell,'=sum(AA'.$start_cell.':AA'.$end_cell.')');
			$sheet->setCellValue('AB'.$cell,'=sum(AB'.$start_cell.':AB'.$end_cell.')');
			$sheet->setCellValue('AC'.$cell,'=sum(AC'.$start_cell.':AC'.$end_cell.')');
			$sheet->setCellValue('AD'.$cell,'=sum(AD'.$start_cell.':AD'.$end_cell.')');
			$sheet->setCellValue('AE'.$cell,'=sum(AE'.$start_cell.':AE'.$end_cell.')');
			$sheet->setCellValue('AF'.$cell,'=sum(AF'.$start_cell.':AF'.$end_cell.')');
			$sheet->setCellValue('AG'.$cell,'=sum(AG'.$start_cell.':AG'.$end_cell.')');
			$sheet->getStyle('A'.$cell.':AG'.$cell)->applyFromArray($styleArray);
			$sheet->getStyle('D'.$cell.':W'.$cell)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
			$sheet->getStyle('Y'.$cell.':AG'.$cell)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
		}
		$cell++;
		$sheet->setCellValue('A'.$cell,'End of Report');
		$sheet->getStyle('A'.$cell)->applyFromArray($styleArray);
		$sheet->setTitle($filename);// Rename sheet
		$objPHPExcel->setActiveSheetIndex(0);// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		// Redirect output to a client's web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename='.$filename);
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
    }
    
	/**
     * @note Schedule 7.5 Generation
     * Minimum Wage Earner
     */
    function get1604CF7_5($gData = array(),$arrData = array()) {
    	IF($gData['branchinfo_id']!=0){//Get company Details
    		$loc_details = clsSSS::getLocationInfo($gData['branchinfo_id']);
    		$company_name = $loc_details['branchinfo_name'];
    		$company_tin = $loc_details['branchinfo_tin'];
    	}ELSE{
    		$comp_details = clsSSS::dbfetchCompDetails($gData['comp']);
    		$company_name = $comp_details['comp_name'];
    		$company_tin = $comp_details['comp_tin'];
    	}
    	$emp = $this->getEmpMWE($gData);
    	//printa($emp);exit;
    	$m = $gData['year'].'-'.$gData['month'].'-'.'1';
    	$filename = "BIR1604CF_SCHEDULE_7.5_".$gData['year'].".xls"; // The file name you want any resulting file to be called.
    	// Create new PHPExcel object
    	$objPHPExcel = new PHPExcel();
    	$objReader = PHPExcel_IOFactory::createReader('Excel5');
    	$objPHPExcel = $objReader->load("templates/BIR_FORM_1604CF_SCHEDULE_7.5.xls"); // Template to be loaded.
    	
    	// Write Data
		// header
		$sheet = $objPHPExcel->getActiveSheet();
		$sheet->setCellValue('A3','AS OF DECEMBER 31, '.$gData['year']);
		$sheet->setCellValue('A4','TIN: '.$company_tin);
		$sheet->setCellValue('A5','WITHHOLDING AGENT\'S NAME: '.$company_name);
		
    	// Body List
    	$sheet = $objPHPExcel->getActiveSheet();
    	$styleArray = array('font' => array('bold' => true));
    	$styleArrayBorders = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
    	
    	// Variable Assigning
//    	$numberOfEmployee = $rsResultCount->fields[mycount];
//    	$baseRow = 15;
//    	$lastvalue = 0;
    	// body - data
		$count = 1;
		$start_cell = 15;
		$cell = $start_cell;
    	if (count($emp) > 0) {
    		$tax_policy = $this->getTaxPolicy();
			foreach ($emp as $key => $val) {
				$tin = str_replace("-","",$val['pi_tin']);
				$val['fr_dayperyear'] ? $factor_day_per_year = $val['fr_dayperyear'] : $factor_day_per_year = "";
    			$val['region_code'] ? $region_no_where_assigned = $val['region_code'] :	$region_no_where_assigned = "";
				switch ($val['taxep_code']) {
					case 'ME': $taxep_code = 'M'; break;
					case 'ME1': $taxep_code = 'M1'; break;
					case 'ME2': $taxep_code = 'M2'; break;
					case 'ME3': $taxep_code = 'M3'; break;
					case 'ME4': $taxep_code = 'M4'; break;
					default: $taxep_code = $val['taxep_code']; break;
				}
    			
    			$sheet->setCellValue('A'.$cell,$count);
				$sheet->setCellValue('B'.$cell,$tin);
				$sheet->setCellValue('C'.$cell,$val['pi_lname']);
				$sheet->setCellValue('D'.$cell,$val['pi_fname']);
				$sheet->setCellValue('E'.$cell,$val['pi_mname']);
				$sheet->setCellValue('F'.$cell,$region_no_where_assigned);
				$sheet->setCellValue('G'.$cell,$val['gross_compensation']);
				$sheet->setCellValue('H'.$cell,$val['basic_smw']);
				$sheet->setCellValue('I'.$cell,$val['holiday_pay']);
				$sheet->setCellValue('J'.$cell,$val['overtime_pay']);
				$sheet->setCellValue('K'.$cell,$val['night_differential']);
				$sheet->setCellValue('L'.$cell,$val['hazard_pay']);
				$sheet->setCellValue('M'.$cell,$val['nt_other_ben']);
				$sheet->setCellValue('N'.$cell,$val['nt_deminimis']);
				$sheet->setCellValue('O'.$cell,$val['nt_statutories']);
				$sheet->setCellValue('P'.$cell,$val['nt_compensation']);
				$sheet->setCellValue('Q'.$cell,'=SUM(M'.$cell.":P".$cell.")");
				$sheet->setCellValue('R'.$cell,$val['taxable_other_ben']);
				$sheet->setCellValue('S'.$cell,$val['taxable_compensation']);
				$sheet->setCellValue('T'.$cell,'=R'.$cell."+S".$cell);
				$sheet->setCellValue('U'.$cell,date_format(date_create($val['emp_hiredate']), "m/d/y"));
				$sheet->setCellValue('V'.$cell,date("m/d/y"));
				$sheet->setCellValue('W'.$cell,"=SUM(AB".$cell.":AI".$cell.")+Z".$cell);
				$sheet->setCellValue('AC'.$cell,$this->getOvertime($val['emp_id'], $gData['year']));
				$sheet->setCellValue('AD'.$cell,'0.00'); // ND Pay
				$sheet->setCellValue('AE'.$cell,'0.00'); // Hazard Pay
				if($this->getBonus($val['emp_id'], $gData['year'],0)>$tax_policy['tp_other_benefits']){
					$bonus_nt = $tax_policy['tp_other_benefits'];
					$addtn_bonus_taxable = $this->getBonus($val['emp_id'], $gData['year'],0)-$tax_policy['tp_other_benefits'];
				} else {
					$bonus_nt = $this->getBonus($val['emp_id'], $gData['year'],0);
					$addtn_bonus_taxable = 0;
				}
				$sheet->setCellValue('AF'.$cell,$bonus_nt);
    			$sheet->setCellValue('AG'.$cell,$this->getDeminimis($val['emp_id'], $gData['year']));
				$sheet->setCellValue('AH'.$cell,$this->getStatutoryAndUnionDues($val['emp_id'], $gData['year']));
				$sheet->setCellValue('AI'.$cell,$this->getOtherCompensation($val['emp_id'], $gData['year']));
				$taxable_bonus = $this->getBonus($val['emp_id'], $gData['year'],1)+$addtn_bonus_taxable;
				$sheet->setCellValue('AJ'.$cell,$taxable_bonus);
				$taxable_other = $this->getOtherCompensationTaxable($val['emp_id'], $gData['year'])-$this->getOvertime($val['emp_id'], $gData['year']);
    			$sheet->setCellValue('AK'.$cell,$taxable_other);
				$sheet->setCellValue('AL'.$cell,"=W".$cell."+SUM(AJ".$cell.":AK".$cell.")");
    			$sheet->setCellValue('AM'.$cell,"=G".$cell."+T".$cell."+AL".$cell);
    			$sheet->setCellValue('AN'.$cell, $taxep_code);
    			$sheet->setCellValue('AO'.$cell, $this->getExemptAmount($taxep_code));
    			$sheet->setCellValue('AP'.$cell,'0.00'); // Health premium
    			$sheet->setCellValue('AQ'.$cell,'=IF(((T'.$cell.'+AJ'.$cell.'+AK'.$cell.')-AO'.$cell.')<=0,0,((T'.$cell.'+AJ'.$cell.'+AK'.$cell.')-AO'.$cell.'))');
    			$taxgross = $val['taxable_other_ben']+$val['taxable_compensation']+$taxable_bonus+$taxable_other+$addtn_bonus_taxable;
    			$sheet->setCellValue('AR'.$cell,$this->getAnnualTaxDue($val['emp_id'],$gData['year'],$taxep_code,$taxgross));
    			$sheet->setCellValue('AS'.$cell,$val['tax_withheld']); // previous employer tax
    			$sheet->setCellValue('AT'.$cell,$this->getTaxWithheld($val['emp_id'], $gData['year']));
    			$sheet->setCellValue('AU'.$cell,$this->getTaxWithheldDecember($val['emp_id'], $gData['year']));
    			$sheet->setCellValue('AV'.$cell,'=((AS'.$cell.'+AT'.$cell.')+AU'.$cell.')-AR'.$cell);
    			$sheet->setCellValue('AW'.$cell,'=((AS'.$cell.'+AT'.$cell.')+AU'.$cell.')-AV'.$cell);
    			
    			// No Value will display if there is no Factor Rate / Day Per Year
    			if ($factor_day_per_year) {
	    			/**switch ($val['salarytype_id']) {
	    				case 2: // Daily Rate
	    					/**$salaryInfoBasicRate = $val['salaryinfo_basicrate'];
	    					$salaryInfoBasicRate = $this->getBasicIncome($val['emp_id'], $gData['year']);
	    					$basicSMWPerYear = $salaryInfoBasicRate * $factor_day_per_year;
	    					$basicSMWPerMonth = $basicSMWPerYear / 12;
	    					$salaryInfoBasicRate = $this->getBasicIncome($val['emp_id'], $gData['year']);
	    					$basicSMWPerYear = $salaryInfoBasicRate * $factor_day_per_year;
	    					$basicSMWPerMonth = $basicSMWPerYear / 12;
	    					
	    					$sheet->setCellValue('X'.$cell, $salaryInfoBasicRate);
	    					$sheet->setCellValue('Y'.$cell, round($basicSMWPerMonth, 2));
	    					$sheet->setCellValue('Z'.$cell, $basicSMWPerYear);**/
	    					//break;
	    				//case 5: // Monthly Rate 
	    					/**$salaryInfoBasicRate = $val['salaryinfo_basicrate'];
	    					$basicSMWPerYear = $salaryInfoBasicRate * 12;
	    					$basicSMWPerDay = $basicSMWPerYear / $factor_day_per_year;
	    					$salaryInfoBasicRate = $this->getBasicIncome($val['emp_id'], $gData['year']);
	    					$basicSMWPerYear = $salaryInfoBasicRate;
	    					$basicSMWPerDay = $salaryInfoBasicRate / $factor_day_per_year;**/
	    					$salaryInfoBasicRate = $this->getBasicIncome($val['emp_id'], $gData['year']);
		    				$basicSMWPerYear = $salaryInfoBasicRate;
		    				$basicSMWPerDay = $salaryInfoBasicRate / $factor_day_per_year;
		    				$basicSMWPerMonth = $basicSMWPerYear / 12;
		    				
	    					$sheet->setCellValue('X'.$cell, round($basicSMWPerDay, 2));
	    					$sheet->setCellValue('Y'.$cell, $basicSMWPerMonth);
	    					$sheet->setCellValue('Z'.$cell, $basicSMWPerYear);
	    					//break;
	    			//}
	    			$sheet->setCellValue('AA'.$cell, $factor_day_per_year);
    			}

    			$count++;
				$end_cell = $cell;
				$cell++;
    		} 
    	} else {
			$sheet->setCellValue('A15','No Records Found!');
			$sheet->getStyle('A15')->applyFromArray($styleArray);
			$sheet->mergeCells('A15:Y15');
		}
		//footer
		if (count($emp)>0) {	
	    	$sheet->setCellValue('F'.$cell,'TOTALS');
	    	$sheet->setCellValue('G'.$cell,'=SUM(G'.$start_cell.':G'.$end_cell.')');
	    	$sheet->setCellValue('H'.$cell,'=SUM(H'.$start_cell.':H'.$end_cell.')');
	    	$sheet->setCellValue('I'.$cell,'=SUM(I'.$start_cell.':I'.$end_cell.')');
	    	$sheet->setCellValue('J'.$cell,'=SUM(J'.$start_cell.':J'.$end_cell.')');
	    	$sheet->setCellValue('K'.$cell,'=SUM(K'.$start_cell.':K'.$end_cell.')');
	    	$sheet->setCellValue('L'.$cell,'=SUM(L'.$start_cell.':L'.$end_cell.')');
	    	$sheet->setCellValue('M'.$cell,'=SUM(M'.$start_cell.':M'.$end_cell.')');
	    	$sheet->setCellValue('N'.$cell,'=SUM(N'.$start_cell.':N'.$end_cell.')');
	    	$sheet->setCellValue('O'.$cell,'=SUM(O'.$start_cell.':O'.$end_cell.')');
	    	$sheet->setCellValue('P'.$cell,'=SUM(P'.$start_cell.':P'.$end_cell.')');
	    	$sheet->setCellValue('Q'.$cell,'=SUM(Q'.$start_cell.':Q'.$end_cell.')');
	    	$sheet->setCellValue('R'.$cell,'=SUM(R'.$start_cell.':R'.$end_cell.')');
	    	$sheet->setCellValue('S'.$cell,'=SUM(S'.$start_cell.':S'.$end_cell.')');
	    	$sheet->setCellValue('T'.$cell,'=SUM(T'.$start_cell.':T'.$end_cell.')');
	    	$sheet->setCellValue('W'.$cell,'=SUM(W'.$start_cell.':W'.$end_cell.')');
	    	$sheet->setCellValue('X'.$cell,'=SUM(X'.$start_cell.':X'.$end_cell.')');
	    	$sheet->setCellValue('Y'.$cell,'=SUM(Y'.$start_cell.':Y'.$end_cell.')');
	    	$sheet->setCellValue('Z'.$cell,'=SUM(Z'.$start_cell.':Z'.$end_cell.')');
	    	$sheet->setCellValue('AB'.$cell,'=SUM(AB'.$start_cell.':AB'.$end_cell.')');
	    	$sheet->setCellValue('AC'.$cell,'=SUM(AC'.$start_cell.':AC'.$end_cell.')');
	    	$sheet->setCellValue('AD'.$cell,'=SUM(AD'.$start_cell.':AD'.$end_cell.')');
	    	$sheet->setCellValue('AE'.$cell,'=SUM(AE'.$start_cell.':AE'.$end_cell.')');
	    	$sheet->setCellValue('AF'.$cell,'=SUM(AF'.$start_cell.':AF'.$end_cell.')');
	    	$sheet->setCellValue('AG'.$cell,'=SUM(AG'.$start_cell.':AG'.$end_cell.')');
	    	$sheet->setCellValue('AH'.$cell,'=SUM(AH'.$start_cell.':AH'.$end_cell.')');
	    	$sheet->setCellValue('AI'.$cell,'=SUM(AI'.$start_cell.':AI'.$end_cell.')');
	    	$sheet->setCellValue('AJ'.$cell,'=SUM(AJ'.$start_cell.':AJ'.$end_cell.')');
	    	$sheet->setCellValue('AK'.$cell,'=SUM(AK'.$start_cell.':AK'.$end_cell.')');
	    	$sheet->setCellValue('AL'.$cell,'=SUM(AL'.$start_cell.':AL'.$end_cell.')');
	    	$sheet->setCellValue('AM'.$cell,'=SUM(AM'.$start_cell.':AM'.$end_cell.')');
	    	$sheet->setCellValue('AO'.$cell,'=SUM(AO'.$start_cell.':AO'.$end_cell.')');
	    	$sheet->setCellValue('AP'.$cell,'=SUM(AP'.$start_cell.':AP'.$end_cell.')');
	    	$sheet->setCellValue('AQ'.$cell,'=SUM(AQ'.$start_cell.':AQ'.$end_cell.')');
	    	$sheet->setCellValue('AR'.$cell,'=SUM(AR'.$start_cell.':AR'.$end_cell.')');
	    	$sheet->setCellValue('AS'.$cell,'=SUM(AS'.$start_cell.':AS'.$end_cell.')');
	    	$sheet->setCellValue('AT'.$cell,'=SUM(AT'.$start_cell.':AT'.$end_cell.')');
	    	$sheet->setCellValue('AU'.$cell,'=SUM(AU'.$start_cell.':AU'.$end_cell.')');
	    	$sheet->setCellValue('AV'.$cell,'=SUM(AV'.$start_cell.':AV'.$end_cell.')');
	    	$sheet->setCellValue('AW'.$cell,'=SUM(AW'.$start_cell.':AW'.$end_cell.')');
	    	// Transform text to Bold
	    	$sheet->getStyle('F'.$cell.":AW".$cell)->applyFromArray($styleArray);
	    	//$sheet->getStyle('X'.$cell)->applyFromArray($styleArray);
	    	//$sheet->getStyle('Y'.$cell)->applyFromArray($styleArray);
	    	//$sheet->getStyle('Z'.$cell)->applyFromArray($styleArray);
    	} 
    	$cell++;
		$sheet->setCellValue('A'.$cell,'END OF REPORT');
		$sheet->getStyle('A'.$cell)->applyFromArray($styleArray);// Bold
    	$objPHPExcel->getActiveSheet()->setTitle($filename);// Rename Sheet
    	$objPHPExcel->setActiveSheetIndex(0);// Set active sheet index to the first sheet, so Excel opens this as the first sheet
    	// Redirect output to a client's web browser (Excel5)
    	header('Content-Type: application/vnd.ms-excel');
    	header('Content-Disposition: attachment;filename='.$filename);
    	header('Cache-Control: max-age=0');
    	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    	$objWriter->save('php://output');
    }
    
    // get terminated employees
    function getTerminatedEmployee($gData = array()) {
    	$qry = array();
    	$fDate = $gData['year'].'-12-31';
    	$sDate = $gData['year'].'-01-01';
    	IF($gData['branchinfo_id']!=0){//get Location parameter.
			$qry[] = "a.branchinfo_id = '".$gData['branchinfo_id']."'";
		}
		$qry[] = "a.comp_id = '".$gData['comp']."'";
		$qry[] = "d.bldsched_period NOT IN (2)";
		$qry[] = "(((a.emp_resigndate <= '{$fDate}' AND a.emp_resigndate >= '{$sDate}') AND a.emp_resigndate != '0000-00-00') OR ((a.emp_retiredate  <= '{$fDate}' AND a.emp_retiredate >= '{$sDate}') AND a.emp_retiredate != '0000-00-00'))";
		$qry[] = "a.emp_id NOT IN (select emp_id from period_benloanduc_sched where empdd_id=5 and bldsched_period=4)";
		$criteria = count($qry)>0 ? " WHERE ".implode(' AND ',$qry) : '';
    	$sql = "SELECT DISTINCT a.emp_id, 
    			b.pi_tin, CONCAT(b.pi_lname,', ',b.pi_fname,' ',b.pi_mname) AS fullname, 
    			b.pi_lname, b.pi_mname, b.pi_fname,
    			DATE_FORMAT(a.emp_hiredate,'%c/%e/%Y') AS date_start,
				DATE_FORMAT(a.emp_resigndate,'%c/%e/%Y') AS date_resign, DATE_FORMAT(a.emp_retiredate,'%c/%e/%Y') AS date_retire,
				c.taxep_code
				FROM emp_masterfile a 
				JOIN emp_personal_info b ON (b.pi_id=a.pi_id)
				JOIN tax_excep c ON (c.taxep_id=a.taxep_id)
				JOIN period_benloanduc_sched d ON (d.emp_id=a.emp_id)
				$criteria
				ORDER BY b.pi_lname";
        $rsResult = $this->conn->Execute($sql);
		while (!$rsResult->EOF) {
			$arrData[] = $rsResult->fields;
            $rsResult->MoveNext();
		}
		return $arrData;
    }
    
    // get employee without previous employer within the year
 	function getEmpNoPreEmployer($gData = array(),$is72=0) {
    	$qry = array();
    	$date = $gData['year'].'-12-31';
    	IF($gData['branchinfo_id']!=0){//get Location parameter.
			$qry[] = "a.branchinfo_id = '".$gData['branchinfo_id']."'";
		}
		$qry2[] = "d.bldsched_period = '2'";
		$qry2[] = "d.empdd_id = '5'";
		$qry2[] = "a.comp_id = '".$gData['comp']."'";
		$qry2[] = "e.salaryinfo_isactive='1'";
		$criteria2 = count($qry2)>0 ? " WHERE ".implode(' AND ',$qry2) : '';
    	$sql2 = "SELECT DISTINCT a.emp_id
				FROM emp_masterfile a 
				JOIN emp_personal_info b ON (b.pi_id=a.pi_id)
				JOIN tax_excep c ON (c.taxep_id=a.taxep_id)
				JOIN period_benloanduc_sched d ON (d.emp_id=a.emp_id)
				JOIN salary_info e ON (e.emp_id=a.emp_id)
				JOIN payroll_comp f ON (f.emp_id=a.emp_id)
				JOIN factor_rate g ON (g.fr_id=f.fr_id)
				JOIN app_wagerate h ON (h.wrate_id=g.wrate_id)
				JOIN app_region i ON (i.region_id=h.region_id)
				LEFT JOIN bir_alphalist_prev_emp j ON (j.emp_id=a.emp_id)
				$criteria2
				ORDER BY b.pi_lname";
		IF($is72){
			$qry[] = "c.taxep_code = 'Z'";
		}
		$qry[] = "d.bldsched_period = '1'";
		$qry[] = "a.emp_id NOT IN ($sql2)";
		$qry[] = "a.comp_id = '".$gData['comp']."'";
		$qry[] = "((a.emp_hiredate <= '{$date}' OR a.emp_hiredate = '0000-00-00' OR a.emp_hiredate IS NULL) AND ((a.emp_resigndate = '0000-00-00' OR a.emp_resigndate IS NULL) AND (a.emp_retiredate = '0000-00-00' OR a.emp_retiredate IS NULL)))";
		$qry[] = "a.emp_id NOT IN (SELECT emp_id FROM bir_alphalist_prev_emp WHERE bir_alphalist_year='".$gData['year']."')";
		$qry[] = "a.emp_id NOT IN (select emp_id from period_benloanduc_sched where empdd_id=5 and bldsched_period=4)";
		$criteria = count($qry)>0 ? " WHERE ".implode(' AND ',$qry) : '';
    	$sql = "SELECT DISTINCT a.emp_id, b.pi_tin, CONCAT(b.pi_lname,', ',b.pi_fname,' ',b.pi_mname) AS fullname, c.taxep_code,
    			b.pi_lname,b.pi_fname,b.pi_mname    			
				FROM emp_masterfile a 
				JOIN emp_personal_info b ON (b.pi_id=a.pi_id)
				JOIN tax_excep c ON (c.taxep_id=a.taxep_id)
				JOIN period_benloanduc_sched d ON (d.emp_id=a.emp_id)
				$criteria
				ORDER BY b.pi_lname";
        $rsResult = $this->conn->Execute($sql);
		while (!$rsResult->EOF) {
			IF($is72 > 0){
				$TaxWithheld = $this->getTaxWithheld($rsResult->fields['emp_id'], $gData['year']);
				IF($TaxWithheld!=0.00){
					$AnnualTaxDue = $this->getAnnualTaxDue($rsResult->fields['emp_id'],$gData['year'],$rsResult->fields['taxep_code']);
					IF($AnnualTaxDue > 0){
						$arrData[] = $rsResult->fields;
					}
				}
			}ELSE{
				$arrData[] = $rsResult->fields;
			}
            $rsResult->MoveNext();
		}
		return $arrData;
    }
    
	// get MWE Employees
 	function getEmpMWE($gData = array(),$is72=0) {
    	$qry = array();
    	$date = $gData['year'].'-12-31';
    	IF($gData['branchinfo_id']!=0){//get Location parameter.
			$qry[] = "a.branchinfo_id = '".$gData['branchinfo_id']."'";
		}
		$qry[] = "d.bldsched_period = '2'";
		$qry[] = "d.empdd_id = '5'";
		$qry[] = "a.comp_id = '".$gData['comp']."'";
		$qry[] = "e.salaryinfo_isactive='1'";
		$qry[] = "a.emp_id NOT IN (select emp_id from period_benloanduc_sched where empdd_id=5 and bldsched_period=4)";
		$criteria = count($qry)>0 ? " WHERE ".implode(' AND ',$qry) : '';
    	$sql = "SELECT DISTINCT j.*, a.emp_id, b.pi_tin, b.pi_lname, b.pi_fname, b.pi_mname, c.taxep_code, a.emp_hiredate, e.salaryinfo_basicrate, e.salarytype_id, g.fr_dayperyear, i.region_code
				FROM emp_masterfile a 
				JOIN emp_personal_info b ON (b.pi_id=a.pi_id)
				JOIN tax_excep c ON (c.taxep_id=a.taxep_id)
				JOIN period_benloanduc_sched d ON (d.emp_id=a.emp_id)
				JOIN salary_info e ON (e.emp_id=a.emp_id)
				JOIN payroll_comp f ON (f.emp_id=a.emp_id)
				JOIN factor_rate g ON (g.fr_id=f.fr_id)
				JOIN app_wagerate h ON (h.wrate_id=g.wrate_id)
				JOIN app_region i ON (i.region_id=h.region_id)
				LEFT JOIN bir_alphalist_prev_emp j ON (j.emp_id=a.emp_id)
				$criteria
				ORDER BY b.pi_lname";
        $rsResult = $this->conn->Execute($sql);
		while (!$rsResult->EOF) {
			IF($is72 > 0){
				$TaxWithheld = $this->getTaxWithheld($rsResult->fields['emp_id'], $gData['year']);
				IF($TaxWithheld!=0.00){
					$AnnualTaxDue = $this->getAnnualTaxDue($rsResult->fields['emp_id'],$gData['year'],$rsResult->fields['taxep_code']);
					IF($AnnualTaxDue > 0){
						$arrData[] = $rsResult->fields;
					}
				}
			}ELSE{
				$arrData[] = $rsResult->fields;
			}
            $rsResult->MoveNext();
		}
		return $arrData;
    }
    
    /**
     * 
     * Get employees with previous employer within the year
     * @param array $gData
     */
    function getEmployeesWithPreviousEmployer($gData = array()){
    	$qry = array();
    	$endDate = $gData['year'].'-12-31';
    	$startDate = $gData['year'].'-01-01';
    	IF($gData['branchinfo_id']!=0){//get Location parameter.
			$qry[] = "a.branchinfo_id = '".$gData['branchinfo_id']."'";
		}
		$qry[] = "d.bldsched_period = '1'";
		$qry[] = "a.comp_id = '".$gData['comp']."'";
		$qry[] = "d.bldsched_period != '2'";
		$qry[] = "d.empdd_id = '5'";
		$qry[] = "a.emp_stat = '7'";
		$qry[] = "a.emp_id NOT IN (select emp_id from period_benloanduc_sched where empdd_id=5 and bldsched_period=4)";
		//$qry[] = "((a.emp_hiredate >= '{$startDate}' AND a.emp_hiredate <= '{$endDate}') AND ((a.emp_resigndate = '0000-00-00' OR a.emp_resigndate IS NULL) AND (a.emp_retiredate = '0000-00-00' OR a.emp_retiredate IS NULL)))";
		$qry[] = "e.bir_alphalist_year='".$gData['year']."'";
		$criteria = count($qry)>0 ? " WHERE ".implode(' AND ',$qry) : '';
    	$sql = "SELECT DISTINCT a.emp_id, b.pi_tin, CONCAT(b.pi_lname,', ',b.pi_fname,' ',b.pi_mname) AS fullname, c.taxep_code, e.*,
    			b.pi_lname,b.pi_fname,b.pi_mname
				FROM emp_masterfile a 
				JOIN emp_personal_info b ON (b.pi_id=a.pi_id)
				JOIN tax_excep c ON (c.taxep_id=a.taxep_id)
				JOIN period_benloanduc_sched d ON (d.emp_id=a.emp_id)
				JOIN bir_alphalist_prev_emp e ON (e.emp_id=a.emp_id)
				$criteria
				ORDER BY b.pi_lname";
        $rsResult = $this->conn->GetAll($sql);
        return $rsResult;
    }
    
    function endDateReplace($resignDate, $retireDate) {
    	if ($resignDate != '00/00/0000') {
    		$replaceDate = $resignDate;
    	} elseif ($retireDate != '00/00/0000') {
    		$replaceDate = $retireDate;
    	} else {
    		$replaceDate = '00/00/0000';
    	}
    	return $replaceDate;
    }
    
    // function that will compute for basic of an employee
    function getRegularBasic($emp_id, $year) {    	
    	$sql = "SELECT entry_amt+amm_amt AS total
				FROM 
				(SELECT COALESCE(SUM(a.ppe_amount), 0) AS entry_amt FROM payroll_paystub_entry a
				INNER JOIN payroll_pay_stub b ON (b.paystub_id=a.paystub_id)
				INNER JOIN payroll_pay_period c ON (c.payperiod_id=b.payperiod_id)
				INNER JOIN payroll_paystub_report d ON (d.paystub_id=b.paystub_id)
				WHERE c.payperiod_period_year='{$year}' AND d.emp_id={$emp_id} AND a.psa_id=1) entry_tbl,
				
				(select COALESCE(sum(a.amendemp_amount),0) as amm_amt 
				from payroll_ps_amendemp a 
				inner join payroll_ps_amendment b on (b.psamend_id=a.psamend_id) 
				inner join payroll_pay_stub c on (c.paystub_id=a.paystub_id) 
				inner join payroll_pay_period d on (d.payperiod_id=c.payperiod_id)
				INNER JOIN payroll_ps_account e ON (e.psa_id=b.psa_id) 
				WHERE d.payperiod_period_year='{$year}' AND a.emp_id={$emp_id} AND b.psa_id=1
				AND a.paystub_id NOT IN (SELECT a.paystub_id FROM payroll_paystub_entry a
				INNER JOIN payroll_pay_stub b ON (b.paystub_id=a.paystub_id)
				INNER JOIN payroll_pay_period c ON (c.payperiod_id=b.payperiod_id)
				INNER JOIN payroll_paystub_report d ON (d.paystub_id=b.paystub_id)
				WHERE c.payperiod_period_year='{$year}' AND d.emp_id={$emp_id} AND a.psa_id=1)) amm_tbl";
    	$rsResult = $this->conn->Execute($sql);
    	while (!$rsResult->EOF) {
    		if ($rsResult->fields['total'] != '' or $rsResult->fields['total'] != NULL or $rsResult->fields['total'] != 0.00) {
    			return $rsResult->fields['total'];
    		} else {
    			return 0.00;
    		}
    	}
    }
    
    function getTA($emp_id, $year) {
    	/**$sql = "SELECT entry_amt+amm_amt AS total
				FROM 
				(SELECT COALESCE(SUM(a.ppe_amount), 0) AS entry_amt FROM payroll_paystub_entry a
				INNER JOIN payroll_pay_stub b ON (b.paystub_id=a.paystub_id)
				INNER JOIN payroll_pay_period c ON (c.payperiod_id=b.payperiod_id)
				INNER JOIN payroll_paystub_report d ON (d.paystub_id=b.paystub_id)
				WHERE c.payperiod_period_year='{$year}' AND d.emp_id={$emp_id} AND a.psa_id=17) entry_tbl,
				
				(select COALESCE(sum(a.amendemp_amount),0) as amm_amt 
				from payroll_ps_amendemp a 
				inner join payroll_ps_amendment b on (b.psamend_id=a.psamend_id) 
				inner join payroll_pay_stub c on (c.paystub_id=a.paystub_id) 
				inner join payroll_pay_period d on (d.payperiod_id=c.payperiod_id)
				INNER JOIN payroll_ps_account e ON (e.psa_id=b.psa_id) 
				WHERE d.payperiod_period_year='{$year}' AND a.emp_id={$emp_id} AND b.psa_id=17
				AND a.paystub_id NOT IN (SELECT a.paystub_id FROM payroll_paystub_entry a
				INNER JOIN payroll_pay_stub b ON (b.paystub_id=a.paystub_id)
				INNER JOIN payroll_pay_period c ON (c.payperiod_id=b.payperiod_id)
				INNER JOIN payroll_paystub_report d ON (d.paystub_id=b.paystub_id)
				WHERE c.payperiod_period_year='{$year}' AND d.emp_id={$emp_id} AND a.psa_id=17)) amm_tbl";'{$year}'**/
    	 $sql = "select sum(emp_tarec_amtperrate) as total from ta_emp_rec a
				join ta_tbl b on (b.tatbl_id=a.tatbl_id)
				join payroll_pay_period c on (c.payperiod_id=a.payperiod_id)
				where a.tatbl_id in (1,3,4) 
				and a.emp_id={$emp_id}
				and c.payperiod_period_year='{$year}'
				and a.paystub_id!=0";
    	$rsResult = $this->conn->Execute($sql);
    	while (!$rsResult->EOF) {
    		if ($rsResult->fields['total'] != '' or $rsResult->fields['total'] != NULL or $rsResult->fields['total'] != 0.00) {
    			return $rsResult->fields['total'];
    		} else {
    			return 0.00;
    		}
    	}
    }
    
    // compute for basic income as basic minus TA
    function getBasicIncome($emp_id, $year) {
    	$basicIncome = $this->getRegularBasic($emp_id, $year) - $this->getTA($emp_id, $year);
    	return $basicIncome;
    }
    
    // function that will compute for the 13th month pay and other benefits of an employee
    function getBonus($emp_id, $year, $is_taxable=0) {       	
       	$sql = "SELECT entry_amt+amm_amt AS total
				FROM 
				(SELECT COALESCE(SUM(a.ppe_amount), 0) AS entry_amt FROM payroll_paystub_entry a
				JOIN payroll_pay_stub b ON (b.paystub_id=a.paystub_id)
				JOIN payroll_pay_period c ON (c.payperiod_id=b.payperiod_id)
				JOIN payroll_paystub_report d ON (d.paystub_id=b.paystub_id)
				JOIN payroll_ps_account e ON (e.psa_id=a.psa_id)
				WHERE c.payperiod_period_year='{$year}' AND d.emp_id={$emp_id} AND e.psa_procode='1' AND e.psa_tax={$is_taxable}) entry_tbl,
				
				(select COALESCE(sum(a.amendemp_amount),0) as amm_amt 
				from payroll_ps_amendemp a 
				inner join payroll_ps_amendment b on (b.psamend_id=a.psamend_id) 
				inner join payroll_pay_stub c on (c.paystub_id=a.paystub_id) 
				inner join payroll_pay_period d on (d.payperiod_id=c.payperiod_id)
				INNER JOIN payroll_ps_account e ON (e.psa_id=b.psa_id) 
				WHERE d.payperiod_period_year='{$year}' AND a.emp_id={$emp_id} AND e.psa_procode='1' AND e.psa_tax={$is_taxable}
				AND a.paystub_id NOT IN (SELECT a.paystub_id FROM payroll_paystub_entry a
				JOIN payroll_pay_stub b ON (b.paystub_id=a.paystub_id)
				JOIN payroll_pay_period c ON (c.payperiod_id=b.payperiod_id)
				JOIN payroll_paystub_report d ON (d.paystub_id=b.paystub_id)
				JOIN payroll_ps_account e ON (e.psa_id=a.psa_id)
				WHERE c.payperiod_period_year='{$year}' AND d.emp_id={$emp_id} AND e.psa_procode='1' AND e.psa_tax={$is_taxable})) amm_tbl";
    	$rsResult = $this->conn->Execute($sql);
    	while (!$rsResult->EOF) {
    		if ($rsResult->fields['total'] != '' or $rsResult->fields['total'] != NULL or $rsResult->fields['total'] != 0.00) {
    			return $rsResult->fields['total'];
    		} else {
    			return 0.00;
    		}
    	}
    }
    
    // function that will compute for the deminimis benefits of an employee
    function getDeminimis($emp_id, $year) {       	
       	$sql = "SELECT entry_amt+amm_amt AS total
				FROM 
				(SELECT COALESCE(SUM(a.ppe_amount), 0) AS entry_amt FROM payroll_paystub_entry a 
				INNER JOIN payroll_pay_stub b ON (b.paystub_id=a.paystub_id) 
				INNER JOIN payroll_pay_period c ON (c.payperiod_id=b.payperiod_id) 
				INNER JOIN payroll_paystub_report d ON (d.paystub_id=b.paystub_id) 
				INNER JOIN payroll_ps_account e ON (e.psa_id=a.psa_id) 
				WHERE c.payperiod_period_year='{$year}' AND d.emp_id={$emp_id} AND e.psa_procode='5') entry_tbl,
				
				(select COALESCE(sum(a.amendemp_amount),0) as amm_amt 
				from payroll_ps_amendemp a 
				inner join payroll_ps_amendment b on (b.psamend_id=a.psamend_id) 
				inner join payroll_pay_stub c on (c.paystub_id=a.paystub_id) 
				inner join payroll_pay_period d on (d.payperiod_id=c.payperiod_id)
				INNER JOIN payroll_ps_account e ON (e.psa_id=b.psa_id) 
				WHERE d.payperiod_period_year='{$year}' AND a.emp_id={$emp_id} AND e.psa_procode='5'
				AND a.paystub_id NOT IN (SELECT a.paystub_id FROM payroll_paystub_entry a 
				INNER JOIN payroll_pay_stub b ON (b.paystub_id=a.paystub_id) 
				INNER JOIN payroll_pay_period c ON (c.payperiod_id=b.payperiod_id) 
				INNER JOIN payroll_paystub_report d ON (d.paystub_id=b.paystub_id) 
				INNER JOIN payroll_ps_account e ON (e.psa_id=a.psa_id) 
				WHERE c.payperiod_period_year='{$year}' AND d.emp_id={$emp_id} AND e.psa_procode='5')) amm_tbl";
    	$rsResult = $this->conn->Execute($sql);
    	while (!$rsResult->EOF) {
    		if ($rsResult->fields['total'] != '' or $rsResult->fields['total'] != NULL or $rsResult->fields['total'] != 0.00) {
    			return $rsResult->fields['total'];
    		} else {
    			return 0.00;
    		}
    	}
    }
    
 	// function that will compute for total gross of an employee
    function getStatutoryAndUnionDues($emp_id, $year) {
    	$sql = "SELECT entry_amt+amm_amt AS total
				FROM 
				(SELECT COALESCE(SUM(a.ppe_amount), 0) AS entry_amt FROM payroll_paystub_entry a
				INNER JOIN payroll_pay_stub b ON (b.paystub_id=a.paystub_id)
				INNER JOIN payroll_pay_period c ON (c.payperiod_id=b.payperiod_id)
				INNER JOIN payroll_paystub_report d ON (d.paystub_id=b.paystub_id)
				WHERE c.payperiod_period_year='{$year}' AND d.emp_id='{$emp_id}' AND a.psa_id IN (7,14,15)) entry_tbl,
				
				(select COALESCE(sum(a.amendemp_amount),0) as amm_amt 
				from payroll_ps_amendemp a 
				inner join payroll_ps_amendment b on (b.psamend_id=a.psamend_id) 
				inner join payroll_pay_stub c on (c.paystub_id=a.paystub_id) 
				inner join payroll_pay_period d on (d.payperiod_id=c.payperiod_id)
				INNER JOIN payroll_ps_account e ON (e.psa_id=b.psa_id) 
				WHERE d.payperiod_period_year='{$year}' AND a.emp_id={$emp_id} AND b.psa_id IN (7,14,15)
				AND a.paystub_id NOT IN (SELECT a.paystub_id FROM payroll_paystub_entry a
				INNER JOIN payroll_pay_stub b ON (b.paystub_id=a.paystub_id)
				INNER JOIN payroll_pay_period c ON (c.payperiod_id=b.payperiod_id)
				INNER JOIN payroll_paystub_report d ON (d.paystub_id=b.paystub_id)
				WHERE c.payperiod_period_year='{$year}' AND d.emp_id='{$emp_id}' AND a.psa_id IN (7,14,15))) amm_tbl";
    	$rsResult = $this->conn->Execute($sql);
    	while (!$rsResult->EOF) {
    		if ($rsResult->fields['total'] != '' or $rsResult->fields['total'] != NULL or $rsResult->fields['total'] != 0.00) {
    			return $rsResult->fields['total'];
    		} else {
    			return 0.00;
    		}
    	}
    }
    
 	// function that will compute for the salaries and other forms of compensation of an employee
    function getOtherCompensation($emp_id, $year) {
       	$sql = "SELECT entry_amt+amm_amt AS total
				FROM 
				(SELECT COALESCE(SUM(a.ppe_amount), 0) AS entry_amt FROM payroll_paystub_entry a
				INNER JOIN payroll_pay_stub b ON (b.paystub_id=a.paystub_id)
				INNER JOIN payroll_pay_period c ON (c.payperiod_id=b.payperiod_id)
				INNER JOIN payroll_paystub_report d ON (d.paystub_id=b.paystub_id)
				INNER JOIN payroll_ps_account e ON (e.psa_id=a.psa_id)
				WHERE c.payperiod_period_year='{$year}' AND d.emp_id='{$emp_id}' AND e.psa_procode='2' AND e.psa_id NOT IN (27) AND e.psa_tax=0) entry_tbl,
				
				(select COALESCE(sum(a.amendemp_amount),0) as amm_amt 
				from payroll_ps_amendemp a 
				inner join payroll_ps_amendment b on (b.psamend_id=a.psamend_id) 
				inner join payroll_pay_stub c on (c.paystub_id=a.paystub_id) 
				inner join payroll_pay_period d on (d.payperiod_id=c.payperiod_id)
				INNER JOIN payroll_ps_account e ON (e.psa_id=b.psa_id) 
				WHERE d.payperiod_period_year='{$year}' AND a.emp_id={$emp_id} AND e.psa_procode='2' AND e.psa_tax=0 AND e.psa_id NOT IN (27)
				AND a.paystub_id NOT IN (SELECT a.paystub_id FROM payroll_paystub_entry a
				INNER JOIN payroll_pay_stub b ON (b.paystub_id=a.paystub_id)
				INNER JOIN payroll_pay_period c ON (c.payperiod_id=b.payperiod_id)
				INNER JOIN payroll_paystub_report d ON (d.paystub_id=b.paystub_id)
				INNER JOIN payroll_ps_account e ON (e.psa_id=a.psa_id)
				WHERE c.payperiod_period_year='{$year}' AND d.emp_id='{$emp_id}' AND e.psa_procode='2' AND e.psa_id NOT IN (27) AND e.psa_tax=0 )) amm_tbl";
    	$rsResult = $this->conn->Execute($sql);
    	while (!$rsResult->EOF) {
    		if ($rsResult->fields['total'] != '' or $rsResult->fields['total'] != NULL or $rsResult->fields['total'] != 0.00) {
    			return $rsResult->fields['total'];
    		} else {
    			return 0.00;
    		}
    	}
    }
    
 	// function that will compute for the taxable salaries and other forms of compensation of an employee
    function getOtherCompensationTaxable($emp_id, $year) {
       	$sql = "SELECT 
				(SELECT COALESCE(SUM(a.ppe_amount), 0) AS income FROM payroll_paystub_entry a
				INNER JOIN payroll_pay_stub b ON (b.paystub_id=a.paystub_id)
				INNER JOIN payroll_pay_period c ON (c.payperiod_id=b.payperiod_id)
				INNER JOIN payroll_paystub_report d ON (d.paystub_id=b.paystub_id)
				INNER JOIN payroll_ps_account e ON (e.psa_id=a.psa_id)
				WHERE c.payperiod_period_year='{$year}' AND d.emp_id='{$emp_id}' AND (a.psa_id IN (16,26,28)))-
				
				(SELECT COALESCE(SUM(a.ppe_amount), 0) AS deduction FROM payroll_paystub_entry a
				INNER JOIN payroll_pay_stub b ON (b.paystub_id=a.paystub_id)
				INNER JOIN payroll_pay_period c ON (c.payperiod_id=b.payperiod_id)
				INNER JOIN payroll_paystub_report d ON (d.paystub_id=b.paystub_id)
				INNER JOIN payroll_ps_account e ON (e.psa_id=a.psa_id)
				WHERE c.payperiod_period_year='{$year}' AND d.emp_id='{$emp_id}' AND (a.psa_id IN (29,34))) as total
				";
    	$rsResult = $this->conn->Execute($sql);
    	while (!$rsResult->EOF) {
    		if ($rsResult->fields['total'] != '' or $rsResult->fields['total'] != NULL or $rsResult->fields['total'] != 0.00) {
    			return $rsResult->fields['total'];
    		} else {
    			return 0.00;
    		}
    	}
    }
    
    // Get tax withheld - Jan - Nov
    function getTaxWithheld($emp_id, $year) {
    	$sql = "SELECT entry_amt+amm_amt AS total
				FROM 
				(SELECT COALESCE(SUM(a.ppe_amount), 0) AS entry_amt FROM payroll_paystub_entry a
				INNER JOIN payroll_pay_stub b ON (b.paystub_id=a.paystub_id)
				INNER JOIN payroll_pay_period c ON (c.payperiod_id=b.payperiod_id)
				INNER JOIN payroll_paystub_report d ON (d.paystub_id=b.paystub_id)
				INNER JOIN payroll_ps_account e ON (e.psa_id=a.psa_id)
				WHERE c.payperiod_period_year='{$year}' AND c.payperiod_period != 12 AND d.emp_id='{$emp_id}' AND a.psa_id='8') entry_tbl,
				
				(select COALESCE(sum(a.amendemp_amount),0) as amm_amt 
				from payroll_ps_amendemp a 
				inner join payroll_ps_amendment b on (b.psamend_id=a.psamend_id) 
				inner join payroll_pay_stub c on (c.paystub_id=a.paystub_id) 
				inner join payroll_pay_period d on (d.payperiod_id=c.payperiod_id)
				INNER JOIN payroll_ps_account e ON (e.psa_id=b.psa_id) 
				WHERE d.payperiod_period_year='{$year}' AND d.payperiod_period != 12 AND a.emp_id={$emp_id} AND b.psa_id='8'
				AND a.paystub_id NOT IN (SELECT a.paystub_id FROM payroll_paystub_entry a
				INNER JOIN payroll_pay_stub b ON (b.paystub_id=a.paystub_id)
				INNER JOIN payroll_pay_period c ON (c.payperiod_id=b.payperiod_id)
				INNER JOIN payroll_paystub_report d ON (d.paystub_id=b.paystub_id)
				INNER JOIN payroll_ps_account e ON (e.psa_id=a.psa_id)
				WHERE c.payperiod_period_year='{$year}' AND c.payperiod_period != 12 AND d.emp_id='{$emp_id}' AND a.psa_id='8')) amm_tbl";
    	$rsResult = $this->conn->Execute($sql);
    	while (!$rsResult->EOF) {
    		if ($rsResult->fields['total'] != '' or $rsResult->fields['total'] != NULL or $rsResult->fields['total'] != 0.00) {
    			return $rsResult->fields['total'];
    		} else {
    			return 0.00;
    		}
    	}
    }
    
	// Get tax withheld - December
    function getTaxWithheldDecember($emp_id, $year) {
    	$sql = "SELECT entry_amt+amm_amt AS total
				FROM 
				(SELECT COALESCE(SUM(a.ppe_amount), 0) AS entry_amt FROM payroll_paystub_entry a
				INNER JOIN payroll_pay_stub b ON (b.paystub_id=a.paystub_id)
				INNER JOIN payroll_pay_period c ON (c.payperiod_id=b.payperiod_id)
				INNER JOIN payroll_paystub_report d ON (d.paystub_id=b.paystub_id)
				INNER JOIN payroll_ps_account e ON (e.psa_id=a.psa_id)
				WHERE c.payperiod_period_year='{$year}' AND c.payperiod_period = 12 AND d.emp_id='{$emp_id}' AND a.psa_id='8') entry_tbl,
				
				(select COALESCE(sum(a.amendemp_amount),0) as amm_amt 
				from payroll_ps_amendemp a 
				inner join payroll_ps_amendment b on (b.psamend_id=a.psamend_id) 
				inner join payroll_pay_stub c on (c.paystub_id=a.paystub_id) 
				inner join payroll_pay_period d on (d.payperiod_id=c.payperiod_id)
				INNER JOIN payroll_ps_account e ON (e.psa_id=b.psa_id) 
				WHERE d.payperiod_period_year='{$year}' AND d.payperiod_period = 12 AND a.emp_id={$emp_id} AND b.psa_id='8'
				AND a.paystub_id NOT IN (SELECT a.paystub_id FROM payroll_paystub_entry a
				INNER JOIN payroll_pay_stub b ON (b.paystub_id=a.paystub_id)
				INNER JOIN payroll_pay_period c ON (c.payperiod_id=b.payperiod_id)
				INNER JOIN payroll_paystub_report d ON (d.paystub_id=b.paystub_id)
				INNER JOIN payroll_ps_account e ON (e.psa_id=a.psa_id)
				WHERE c.payperiod_period_year='{$year}' AND c.payperiod_period = 12 AND d.emp_id='{$emp_id}' AND a.psa_id='8')) amm_tbl";
    	$rsResult = $this->conn->Execute($sql);
    	while (!$rsResult->EOF) {
    		if ($rsResult->fields['total'] != '' or $rsResult->fields['total'] != NULL or $rsResult->fields['total'] != 0.00) {
    			return $rsResult->fields['total'];
    		} else {
    			return 0.00;
    		}
    	}
    }
    
	// Get Gross Compensation
    function getGrossCompensation($emp_id, $year) {
    	$sql = "SELECT entry_amt+amm_amt AS total
				FROM 
				(SELECT COALESCE(SUM(a.ppe_amount), 0) AS entry_amt FROM payroll_paystub_entry a
				INNER JOIN payroll_pay_stub b ON (b.paystub_id=a.paystub_id)
				INNER JOIN payroll_pay_period c ON (c.payperiod_id=b.payperiod_id)
				INNER JOIN payroll_paystub_report d ON (d.paystub_id=b.paystub_id)
				INNER JOIN payroll_ps_account e ON (e.psa_id=a.psa_id)
				WHERE c.payperiod_period_year='{$year}' AND d.emp_id='{$emp_id}' AND a.psa_id='4') entry_tbl,
				
				(select COALESCE(sum(a.amendemp_amount),0) as amm_amt 
				from payroll_ps_amendemp a 
				inner join payroll_ps_amendment b on (b.psamend_id=a.psamend_id) 
				inner join payroll_pay_stub c on (c.paystub_id=a.paystub_id) 
				inner join payroll_pay_period d on (d.payperiod_id=c.payperiod_id)
				INNER JOIN payroll_ps_account e ON (e.psa_id=b.psa_id) 
				WHERE d.payperiod_period_year='{$year}' AND a.emp_id={$emp_id} AND b.psa_id='4'
				AND a.paystub_id NOT IN (SELECT a.paystub_id FROM payroll_paystub_entry a
				INNER JOIN payroll_pay_stub b ON (b.paystub_id=a.paystub_id)
				INNER JOIN payroll_pay_period c ON (c.payperiod_id=b.payperiod_id)
				INNER JOIN payroll_paystub_report d ON (d.paystub_id=b.paystub_id)
				INNER JOIN payroll_ps_account e ON (e.psa_id=a.psa_id)
				WHERE c.payperiod_period_year='{$year}' AND d.emp_id='{$emp_id}' AND a.psa_id='4')) amm_tbl";
    	$rsResult = $this->conn->Execute($sql);
    	while (!$rsResult->EOF) {
    		if ($rsResult->fields['total'] != '' or $rsResult->fields['total'] != NULL or $rsResult->fields['total'] != 0.00) {
    			return $rsResult->fields['total'];
    		} else {
    			return 0.00;
    		}
    	}
    }
    // Get Premium Paid on Health And/Or Hospital Insurance
    function getHealthInsurance($emp_id, $year) {
    	$sql = "SELECT SUM(a.ppe_amount) AS total FROM payroll_paystub_entry a
		JOIN payroll_pay_stub b ON (b.paystub_id=a.paystub_id)
		JOIN payroll_pay_period c ON (c.payperiod_id=b.payperiod_id)
		JOIN payroll_paystub_report d ON (d.paystub_id=b.paystub_id)
		WHERE c.payperiod_period_year='{$year}' AND d.emp_id='{$emp_id}' AND a.psa_id='14'";
    	$rsResult = $this->conn->Execute($sql);
    	while (!$rsResult->EOF) {
    		if ($rsResult->fields['total'] != '' or $rsResult->fields['total'] != NULL or $rsResult->fields['total'] != 0.00) {
    			return $rsResult->fields['total'];
    		} else {
    			return 0.00;
    		}
    	}
    }
    
    //Get Tax Due per employee
    function getAnnualTaxDue($emp_id, $year, $taxexmp = 'Z',$ytdtaxgross = 0){
    	$qry = array();
		$qry[] = "a.set_id = 3";
		$criteria = count($qry)>0 ? " WHERE ".implode(' AND ',$qry) : '';
		$sql = "SELECT * FROM app_settings a $criteria";
		$varDeducH = $this->conn->Execute($sql);
		if(!$varDeducH->EOF){
			$varHdeduc = $varDeducH->fields;
		}
		//$ytdtaxgross = $this->getYTD($emp_id,30,$oData['paystub_id'], $year);
		$TaxExmp = $this->getExemptAmount($taxexmp);
		$TaxLessExmp = $ytdtaxgross - $TaxExmp;
		IF($TaxLessExmp > 0){
			$TaxLessExmp_ = $TaxLessExmp;
		}ELSE{
			$TaxLessExmp_ = 0.00;
		}
		$varDec = $this->getAnnualTaxTable($varHdeduc['set_decimal_places'],$TaxLessExmp_,5);
		$varStax = $TaxLessExmp_ - $varDec['tt_minamount'];
		$conVertTaxper =  $varDec['tt_over_pct'] / 100 ;
		$varStax_p =  $varStax * $conVertTaxper;
		$totalTaxDue = $varDec['tt_taxamount'] + $varStax_p;
		/*if($emp_id==4){
		echo "<br>================= Tax Due Summary ================<br>";
		printa($varDec);
		echo $ytdtaxgross['ytdamount']." Taxable Gross<br>";
		echo $TaxExmp." Tax Exemeption<br>";
		echo $TaxLessExmp_." Tax LESS Exemeption<br>";
		echo $varStax." TaxLessExmp_ - minamunt<br>";
		echo $conVertTaxper." % multiplier<br>";
		echo $varStax_p." varStax * % multiplier<br>";
		echo $totalTaxDue." Total Tax Due<br>";
		exit;
		}*/
		return $totalTaxDue;
    }
    
	function getAnnualTaxTable($dduct_id_ = null, $totaltgross_ = 0, $tax_table_ = null) {
		$arrData = array();
		$qry = array();
		if (is_null($dduct_id_) || empty($dduct_id_)) { return $arrData; }
		
        $qry[] = "b.tp_id = $dduct_id_";
		$qry[] = "b.tt_pay_group = '".$tax_table_."'";//tax table to be used in computation.
		IF($dduct_id_ == 1){
            $qry[] = " $totaltgross_ >= b.tt_minamount";
        }ELSE IF($dduct_id_ == 2){
            $qry[] = " $totaltgross_ >= b.tt_minamount";
        }ELSE{
            $qry[] = "b.tt_minamount < $totaltgross_";
        }
		$criteria = count($qry)>0 ? " WHERE ".implode(' AND ',$qry) : '';
		$sql = "SELECT b.*,a.* FROM tax_policy a JOIN tax_table b on (a.tp_id = b.tp_id)
				$criteria
				ORDER BY b.tt_minamount desc
				limit 1";
		$rsResult = $this->conn->Execute($sql);
		if (!$rsResult->EOF){
			return $rsResult->fields;
		}
	}
    
	/**
     * @note: Get YTD values
     * @param $emp_id_
     * @param $psa_id_
     * @param $paystub_id_
     */
    function getYTD($emp_id_ = null, $psa_id_ = null, $paystub_id_ = null, $year_ = null){
    	IF (is_null($emp_id_)) { return $arrData; }
		IF (is_null($psa_id_) || empty($psa_id_)) { return $arrData; }
    	IF (is_null($psa_id_) || empty($psa_id_)) { return $arrData; }
   		IF (is_null($year_) || empty($year_)) { return $arrData; }
    	$qry[]="d.emp_id = '".$emp_id_."'";
		$qry[]="a.psa_id = '".$psa_id_."'";
		$qry[]="c.payperiod_period_year = '".$year_."'";
//		IF($paystub_id_){
//			$qry[]="a.paystub_id <= '".$paystub_id_."'";
//		}
		// put all query array into one string criteria
		$criteria = " WHERE ".implode(" and ",$qry);
		/*$qryYTD = "SELECT SUM(b.ppe_amount) as ytdamount
					FROM payroll_pay_stub a
					JOIN payroll_paystub_entry b on(b.paystub_id=a.paystub_id) 
					$criteria
					GROUP BY b.psa_id";*/
		$qryYTD = "SELECT SUM(a.ppe_amount) as ytdamount  FROM payroll_paystub_entry a
				INNER JOIN payroll_pay_stub b ON (b.paystub_id=a.paystub_id)
				INNER JOIN payroll_pay_period c ON (c.payperiod_id=b.payperiod_id)
				INNER JOIN payroll_paystub_report d ON (d.paystub_id=b.paystub_id)
				$criteria";
		$varYTD = $this->conn->Execute($qryYTD);
		IF(!$varDeducH->EOF){
			$varYTD_ = $varYTD->fields;
			return $varYTD_;
		}
    }
	
    function getExemptAmount($code) {
    	if ($code == 'Z') {
    		$amount = 0.00;
    	} else {
	    	$mul = preg_replace("/[^0-9]/", '', $code);
	    	$amount = 50000+25000*(int)$mul;
    	}
    	return $amount;
    }
    
    // function that will compute for the overtime
    function getOvertime($emp_id, $year) {
       	$sql = "SELECT entry_amt+amm_amt AS total
				FROM 
				(SELECT COALESCE(SUM(a.ppe_amount), 0) AS entry_amt FROM payroll_paystub_entry a
				INNER JOIN payroll_pay_stub b ON (b.paystub_id=a.paystub_id)
				INNER JOIN payroll_pay_period c ON (c.payperiod_id=b.payperiod_id)
				INNER JOIN payroll_paystub_report d ON (d.paystub_id=b.paystub_id)
				INNER JOIN payroll_ps_account e ON (e.psa_id=a.psa_id)
				WHERE c.payperiod_period_year='{$year}' AND d.emp_id='{$emp_id}' AND a.psa_id='16') entry_tbl,
				
				(select COALESCE(sum(a.amendemp_amount),0) as amm_amt 
				from payroll_ps_amendemp a 
				inner join payroll_ps_amendment b on (b.psamend_id=a.psamend_id) 
				inner join payroll_pay_stub c on (c.paystub_id=a.paystub_id) 
				inner join payroll_pay_period d on (d.payperiod_id=c.payperiod_id)
				INNER JOIN payroll_ps_account e ON (e.psa_id=b.psa_id) 
				WHERE d.payperiod_period_year='{$year}' AND a.emp_id={$emp_id} AND b.psa_id='16'
				AND a.paystub_id NOT IN (SELECT a.paystub_id FROM payroll_paystub_entry a
				INNER JOIN payroll_pay_stub b ON (b.paystub_id=a.paystub_id)
				INNER JOIN payroll_pay_period c ON (c.payperiod_id=b.payperiod_id)
				INNER JOIN payroll_paystub_report d ON (d.paystub_id=b.paystub_id)
				INNER JOIN payroll_ps_account e ON (e.psa_id=a.psa_id)
				WHERE c.payperiod_period_year='{$year}' AND d.emp_id='{$emp_id}' AND a.psa_id='16')) amm_tbl";
    	$rsResult = $this->conn->Execute($sql);
    	while (!$rsResult->EOF) {
    		if ($rsResult->fields['total'] != '' or $rsResult->fields['total'] != NULL or $rsResult->fields['total'] != 0.00) {
    			return $rsResult->fields['total'];
    		} else {
    			return 0.00;
    		}
    	}
    }
    
    function getPreviousEmployer($emp_id_ = null, $year_ = null){
    	IF (is_null($emp_id_)) { return null; }
    	IF (is_null($year_)) { return null; }
    	$qry[]="emp_id = '".$emp_id_."'";
		$qry[]="bir_alphalist_year = '".$year_."'";
		$criteria = " WHERE ".implode(" and ",$qry);
    	$sql = "SELECT * FROM bir_alphalist_prev_emp $criteria";
    	$prevEmp = $this->conn->Execute($sql);
		IF(!$prevEmp->EOF){
			return $prevEmp->fields;
		}
    }
    
    function getTaxPolicy(){
    	$qry = array();
		$qry[] = "a.set_id = 3";
		$criteria = count($qry)>0 ? " WHERE ".implode(' AND ',$qry) : '';
		$sql = "SELECT * FROM app_settings a $criteria";
		$varDeducH = $this->conn->Execute($sql);
		if(!$varDeducH->EOF){
			$varHdeduc = $varDeducH->fields;
			$sql_tax = "select * from tax_policy where tp_id=".$varHdeduc['set_decimal_places'];
			$tp = $this->conn->Execute($sql_tax);
			if(!$tp->EOF){
				return $tp->fields;
			} else { return null; }
		} else { return null; }
    }
    
    function get1604CF7_1_diskette($gData = array()){
    	//printa($gData); exit;
    	$date = date_create($gData['return']); 
        IF($gData['branchinfo_id']!=0){//Get company Details
    		$loc_details = clsSSS::getLocationInfo($gData['branchinfo_id']);
    		$company_name = $loc_details['branchinfo_name'];
    		$company_tin = $loc_details['branchinfo_tin'];
    	}ELSE{
    		$comp_details = clsSSS::dbfetchCompDetails($gData['comp']);
    		$company_name = $comp_details['comp_name'];
    		$company_tin = $comp_details['comp_tin'];
    	}
    	
    	$filename = substr(str_replace("-","",$company_tin),0,8).".s71";
		    $handle = fopen($filename, "w");
		    // header
		    fwrite($handle, "H1604CF,".str_replace("-","",$company_tin).",".str_pad($gData['branchcode'],4,'0',STR_PAD_LEFT).",".date_format($date,'m/d/Y')."\r\n");
		    
			$emp = $this->getTerminatedEmployee($gData);//Get all Terminated Employee
			// Initialize variables
		    $ctr = 1;
			$grand_gross = 0;
			$grand_bonus_nt = 0;
			$grand_dmb_nt = 0;
			$grand_stat_nt = 0;
			$grand_other_comp_nt = 0;
			$grand_total_nt = 0;
			$grand_basic_taxable = 0;
			$grand_bonus_taxable = 0;
			$grand_other_comp_taxable = 0;
			$grand_total_taxable = 0;
			$grand_exempt = 0;
			$grand_health_premium = 0;
			$grand_net_taxable = 0;
			$grand_taxdue = 0;
			$grand_taxwithheld = 0;
			$grand_taxwithheld_dec = 0;
			$grand_over_withheld = 0;
			$grand_actual_tax = 0;

			$comp_tin = str_replace("-","",$company_tin);
			$branch_code = str_pad($gData['branchcode'],4,'0',STR_PAD_LEFT);
			$return_period = date_format($date,'m/d/Y');
			// details
		    if (count($emp) > 0) {
		    	$tax_policy = $this->getTaxPolicy();
				foreach ($emp as $key => $val) {
					$string = "";
					switch ($val['taxep_code']) {
						case 'ME': $taxep_code = 'M'; break;
						case 'ME1': $taxep_code = 'M1'; break;
						case 'ME2': $taxep_code = 'M2'; break;
						case 'ME3': $taxep_code = 'M3'; break;
						case 'ME4': $taxep_code = 'M4'; break;
						default: $taxep_code = $val['taxep_code']; break;
					}
					$prevEmployer = $this->getPreviousEmployer($val['emp_id'],$gData['year']);
					// assign variable for easy formatting
					//$count = str_pad($ctr,6,'0',STR_PAD_LEFT);
					$count = $ctr;
					$emp_tin = str_replace("-","",$val['pi_tin']);
					$lastname = strtoupper($val['pi_lname']);
					$firstname = strtoupper($val['pi_fname']);
					$middlename = strtoupper($val['pi_mname']);
					$date_from = date_create($val['date_start']);
					$start_date = date_format($date_from,'m/d/Y');
					$date_to = date_create($this->endDateReplace($val['date_resign'], $val['date_retire']));
					$end_date = date_format($date_to,'m/d/Y');
					
					// non-taxable
					if(($this->getBonus($val['emp_id'], $gData['year'],0)+$prevEmployer['nt_other_ben'])>$tax_policy['tp_other_benefits']){
						$bonus_nt = $tax_policy['tp_other_benefits'];
						$addtn_bonus_taxable = ($this->getBonus($val['emp_id'], $gData['year'],0)+$prevEmployer['nt_other_ben'])-$tax_policy['tp_other_benefits'];
					} else {
						$bonus_nt = $this->getBonus($val['emp_id'], $gData['year'],0)+$prevEmployer['nt_other_ben'];
						$addtn_bonus_taxable = 0;
					}
					$grand_bonus_nt += $bonus_nt;
					$bonus_nt = ($bonus_nt == 0 ? 0 : number_format($bonus_nt,2,'.',''));
					
					$dmb_nt = $this->getDeminimis($val['emp_id'], $gData['year'])+$prevEmployer['nt_deminimis'];				
					$grand_dmb_nt += $dmb_nt;
					$dmb_nt = ($dmb_nt == 0 ? 0 : number_format($dmb_nt,2,'.',''));
					
					$stat_nt = $this->getStatutoryAndUnionDues($val['emp_id'], $gData['year'])+$prevEmployer['nt_statutories'];
					$grand_stat_nt += $stat_nt;
					$stat_nt = ($stat_nt == 0 ? 0 : number_format($stat_nt,2,'.',''));
					
					$other_comp_nt = $this->getOtherCompensation($val['emp_id'], $gData['year'])+$prevEmployer['nt_compensation'];
					$grand_other_comp_nt += $other_comp_nt;
					$other_comp_nt = ($other_comp_nt == 0 ? 0 : number_format($other_comp_nt,2,'.',''));
					
					$total_nt = $bonus_nt+$dmb_nt+$stat_nt+$other_comp_nt;
					$grand_total_nt += $total_nt;
					$total_nt = ($total_nt == 0 ? 0 : number_format($total_nt,2,'.',''));
					
					// taxable
					$basic_taxable = ($prevEmployer['taxable_basic']-$prevEmployer['nt_statutories'])+($this->getBasicIncome($val['emp_id'], $gData['year'])-$this->getStatutoryAndUnionDues($val['emp_id'], $gData['year']));
					$grand_basic_taxable += $basic_taxable;
					$basic_taxable = ($basic_taxable == 0 ? 0 : number_format($basic_taxable,2,'.',''));
					
					$bonus_taxable = $this->getBonus($val['emp_id'], $gData['year'],1)+$prevEmployer['taxable_other_ben']+$addtn_bonus_taxable;
					$grand_bonus_taxable += $bonus_taxable;
					$bonus_taxable = ($bonus_taxable == 0 ? 0 : number_format($bonus_taxable,2,'.',''));
					
					$other_comp_taxable = $this->getOtherCompensationTaxable($val['emp_id'], $gData['year'])+$prevEmployer['taxable_compensation'];
					$grand_other_comp_taxable += $other_comp_taxable;
					$other_comp_taxable = ($other_comp_taxable == 0 ? 0 : number_format($other_comp_taxable,2,'.',''));
					
					$total_taxable = $basic_taxable+$bonus_taxable+$other_comp_taxable;
					$grand_total_taxable += $total_taxable;
					$total_taxable = ($total_taxable == 0 ? 0 : number_format($total_taxable,2,'.',''));
					
					// gross
					$gross = $total_nt+$total_taxable;
					$grand_gross += $gross;
					$gross = ($gross == 0 ? 0 : number_format($gross,2,'.',''));
					
					$exempt = $this->getExemptAmount($taxep_code);
					$grand_exempt += $exempt;
					
					$health_premium = 0;
					$grand_health_premium += $health_premium;
					
					$net_taxable = (($total_taxable-$exempt) <= 0 ? 0 : number_format(($total_taxable-$exempt),2,'.',''));
					$grand_net_taxable += $net_taxable;
					
					$taxdue = $this->getAnnualTaxDue($val['emp_id'],$gData['year'],$taxep_code,$total_taxable);
					$grand_taxdue += $taxdue;
					$taxdue = ($taxdue == 0 ? 0 : number_format($taxdue,2,'.',''));
					
					$taxwithheld = $this->getTaxWithheld($val['emp_id'], $gData['year'])+$prevEmployer['tax_withheld'];
					$grand_taxwithheld += $taxwithheld;
					$taxwithheld = ($taxwithheld == 0 ? 0 : number_format($taxwithheld,2,'.',''));
					
					$taxwithheld_dec  = $this->getTaxWithheldDecember($val['emp_id'], $gData['year']);
					$grand_taxwithheld_dec += $taxwithheld_dec;
					$taxwithheld_dec = ($taxwithheld_dec == 0 ? 0 : number_format($taxwithheld_dec,2,'.',''));
					
					$over_withheld = ($taxwithheld+$taxwithheld_dec)-$taxdue;
					$grand_over_withheld += $over_withheld;
					$over_withheld = ($over_withheld == 0 ? 0 : number_format($over_withheld,2,'.',''));
					
					$actual_tax = ($taxwithheld+$taxwithheld_dec)-$over_withheld;
					$grand_actual_tax += $actual_tax;
					$actual_tax = ($actual_tax == 0 ? 0 : number_format($actual_tax,2,'.',''));
					
					$substituted = "N";
					
					$string .= "D7.1";
					$string .= ",1604CF";
					$string .= ",".$comp_tin;
					$string .= ",".$branch_code;
					$string .= ",".$return_period;
					$string .= ",".$count;
					$string .= ",".$emp_tin;
					$string .= ",".$branch_code;
					$string .= ",".$lastname;
					$string .= ",".$firstname;
					$string .= ",".$middlename;
					$string .= ",".$start_date;
					$string .= ",".$end_date;
					$string .= ",".$gross;
					$string .= ",".$bonus_nt;
					$string .= ",".$dmb_nt;
					$string .= ",".$stat_nt;
					$string .= ",".$other_comp_nt;
					$string .= ",".$total_nt;
					$string .= ",".$basic_taxable;
					$string .= ",".$bonus_taxable;
					$string .= ",".$other_comp_taxable;
					$string .= ",".$total_taxable;
					$string .= ",".$taxep_code;
					$string .= ",".$exempt;
					$string .= ",".$health_premium;
					$string .= ",".$net_taxable;
					$string .= ",".$taxdue;
					$string .= ",".$taxwithheld;
					$string .= ",".$taxwithheld_dec;
					$string .= ",".$over_withheld;
					$string .= ",".$actual_tax;
					$string .= ",".$substituted;
					$string .= "\r\n";
					// write the details per employee
			     	fwrite($handle, $string);
					$ctr++;
				}
		    } else {
		    	
		    }
			// controls
		    $controls = "C7.1";
		    $controls .= ",1604CF";
		    $controls .= ",".$comp_tin;
			$controls .= ",".$branch_code;
			$controls .= ",".$return_period;
			$controls .= ",".$grand_gross;
			$controls .= ",".$grand_bonus_nt;
			$controls .= ",".$grand_dmb_nt;
			$controls .= ",".$grand_stat_nt;
			$controls .= ",".$grand_other_comp_nt;
			$controls .= ",".$grand_total_nt;
			$controls .= ",".$grand_basic_taxable;
			$controls .= ",".$grand_bonus_taxable;
			$controls .= ",".$grand_other_comp_taxable;
			$controls .= ",".$grand_total_taxable;
			$controls .= ",".$grand_exempt;
			$controls .= ",".$grand_health_premium;
			$controls .= ",".$grand_net_taxable;
			$controls .= ",".$grand_taxdue;
			$controls .= ",".$grand_taxwithheld;
			$controls .= ",".$grand_taxwithheld_dec;
			$controls .= ",".$grand_over_withheld;
			$controls .= ",".$grand_actual_tax;
			fwrite($handle, $controls);
		    
			fclose($handle);
			// force download the diskette file
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename='.basename($filename));
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($filename));
			readfile($filename);
			unlink($filename);
			exit;
    }
    
	function get1604CF7_2_diskette($gData = array()){
    	//printa($gData); exit;
    	$date = date_create($gData['return']); 
        IF($gData['branchinfo_id']!=0){//Get company Details
    		$loc_details = clsSSS::getLocationInfo($gData['branchinfo_id']);
    		$company_name = $loc_details['branchinfo_name'];
    		$company_tin = $loc_details['branchinfo_tin'];
    	}ELSE{
    		$comp_details = clsSSS::dbfetchCompDetails($gData['comp']);
    		$company_name = $comp_details['comp_name'];
    		$company_tin = $comp_details['comp_tin'];
    	}
    	
    		$filename = substr(str_replace("-","",$company_tin),0,8).".s72";
		    $handle = fopen($filename, "w");
		    // header
		    fwrite($handle, "H1604CF,".str_replace("-","",$company_tin).",".str_pad($gData['branchcode'],4,'0',STR_PAD_LEFT).",".date_format($date,'m/d/Y')."\r\n");
		    
			$emp = $this->getEmpNoPreEmployer($gData,1);
			// Initialize variables
		    $ctr = 1;
			$grand_gross = 0;
			$grand_bonus_nt = 0;
			$grand_dmb_nt = 0;
			$grand_stat_nt = 0;
			$grand_other_comp_nt = 0;
			$grand_total_nt = 0;
			$grand_basic_taxable = 0;
			$grand_other_comp_taxable = 0;
			$grand_exempt = 0;
			$grand_health_premium = 0;
			$grand_net_taxable = 0;
			$grand_taxdue = 0;
			
			$comp_tin = str_replace("-","",$company_tin);
			$branch_code = str_pad($gData['branchcode'],4,'0',STR_PAD_LEFT);
			$return_period = date_format($date,'m/d/Y');
			// details
		    if (count($emp) > 0) {
				foreach ($emp as $key => $val) {
					$string = "";
					switch ($val['taxep_code']) {
						case 'ME': $taxep_code = 'M'; break;
						case 'ME1': $taxep_code = 'M1'; break;
						case 'ME2': $taxep_code = 'M2'; break;
						case 'ME3': $taxep_code = 'M3'; break;
						case 'ME4': $taxep_code = 'M4'; break;
						default: $taxep_code = $val['taxep_code']; break;
					}
					// assign variable for easy formatting
					//$count = str_pad($ctr,6,'0',STR_PAD_LEFT);
					$count = $ctr;
					$emp_tin = str_replace("-","",$val['pi_tin']);
					$lastname = strtoupper($val['pi_lname']);
					$firstname = strtoupper($val['pi_fname']);
					$middlename = strtoupper($val['pi_mname']);
					$date_from = date_create($val['date_start']);
					$start_date = date_format($date_from,'m/d/Y');
					$date_to = date_create($this->endDateReplace($val['date_resign'], $val['date_retire']));
					$end_date = date_format($date_to,'m/d/Y');
					
					// non-taxable
					$bonus_nt = $this->getBonus($val['emp_id'], $gData['year'],0);
					$grand_bonus_nt += $bonus_nt;
					$bonus_nt = ($bonus_nt == 0 ? 0 : number_format($bonus_nt,2,'.',''));
					
					$dmb_nt = $this->getDeminimis($val['emp_id'], $gData['year']);				
					$grand_dmb_nt += $dmb_nt;
					$dmb_nt = ($dmb_nt == 0 ? 0 : number_format($dmb_nt,2,'.',''));
					
					$stat_nt = $this->getStatutoryAndUnionDues($val['emp_id'], $gData['year']);
					$grand_stat_nt += $stat_nt;
					$stat_nt = ($stat_nt == 0 ? 0 : number_format($stat_nt,2,'.',''));
					
					$other_comp_nt = $this->getOtherCompensation($val['emp_id'], $gData['year']);
					$grand_other_comp_nt += $other_comp_nt;
					$other_comp_nt = ($other_comp_nt == 0 ? 0 : number_format($other_comp_nt,2,'.',''));
					
					$total_nt = $bonus_nt+$dmb_nt+$stat_nt+$other_comp_nt;
					$grand_total_nt += $total_nt;
					$total_nt = ($total_nt == 0 ? 0 : number_format($total_nt,2,'.',''));
					
					// taxable
					$basic_taxable = ($this->getBasicIncome($val['emp_id'], $gData['year'])-$this->getStatutoryAndUnionDues($val['emp_id'], $gData['year']));
					$grand_basic_taxable += $basic_taxable;
					$basic_taxable = ($basic_taxable == 0 ? 0 : number_format($basic_taxable,2,'.',''));
					
					$other_comp_taxable = $this->getOtherCompensationTaxable($val['emp_id'], $gData['year']);
					$grand_other_comp_taxable += $other_comp_taxable;
					$other_comp_taxable = ($other_comp_taxable == 0 ? 0 : number_format($other_comp_taxable,2,'.',''));
					
					// gross
					$gross = $total_nt+$total_taxable;
					$grand_gross += $gross;
					$gross = ($gross == 0 ? 0 : number_format($gross,2,'.',''));
					
					$exempt = $this->getExemptAmount($taxep_code);
					$grand_exempt += $exempt;
					
					$health_premium = 0;
					$grand_health_premium += $health_premium;
					
					$net_taxable = (($total_taxable-$exempt) <= 0 ? 0 : number_format(($total_taxable-$exempt),2,'.',''));
					$grand_net_taxable += $net_taxable;
					
					$taxdue = $this->getAnnualTaxDue($val['emp_id'],$gData['year'],$taxep_code,$total_taxable);
					$grand_taxdue += $taxdue;
					$taxdue = ($taxdue == 0 ? 0 : number_format($taxdue,2,'.',''));
					
					$string .= "D7.2";
					$string .= ",1604CF";
					$string .= ",".$comp_tin;
					$string .= ",".$branch_code;
					$string .= ",".$return_period;
					$string .= ",".$count;
					$string .= ",".$emp_tin;
					$string .= ",".$branch_code;
					$string .= ",".$lastname;
					$string .= ",".$firstname;
					$string .= ",".$middlename;
					$string .= ",".$gross;
					$string .= ",".$bonus_nt;
					$string .= ",".$dmb_nt;
					$string .= ",".$stat_nt;
					$string .= ",".$other_comp_nt;
					$string .= ",".$total_nt;
					$string .= ",".$basic_taxable;
					$string .= ",".$other_comp_taxable;
					$string .= ",".$taxep_code;
					$string .= ",".$exempt;
					$string .= ",".$health_premium;
					$string .= ",".$net_taxable;
					$string .= ",".$taxdue;
					$string .= "\r\n";
					// write the details per employee
			     	fwrite($handle, $string);
					$ctr++;
				}
		    } else {
		    	
		    }
			// controls
		    $controls = "C7.2";
		    $controls .= ",1604CF";
		    $controls .= ",".$comp_tin;
			$controls .= ",".$branch_code;
			$controls .= ",".$return_period;
			$controls .= ",".$grand_gross;
			$controls .= ",".$grand_bonus_nt;
			$controls .= ",".$grand_dmb_nt;
			$controls .= ",".$grand_stat_nt;
			$controls .= ",".$grand_other_comp_nt;
			$controls .= ",".$grand_total_nt;
			$controls .= ",".$grand_basic_taxable;
			$controls .= ",".$grand_other_comp_taxable;
			$controls .= ",".$grand_exempt;
			$controls .= ",".$grand_health_premium;
			$controls .= ",".$grand_net_taxable;
			$controls .= ",".$grand_taxdue;
			fwrite($handle, $controls);
		    
			fclose($handle);
				
			// force download the diskette file
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename='.basename($filename));
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($filename));
			readfile($filename);
			unlink($filename);
			exit;
    }
    
	function get1604CF7_3_diskette($gData = array()){
    	//printa($gData); exit;
    	$date = date_create($gData['return']); 
        IF($gData['branchinfo_id']!=0){//Get company Details
    		$loc_details = clsSSS::getLocationInfo($gData['branchinfo_id']);
    		$company_name = $loc_details['branchinfo_name'];
    		$company_tin = $loc_details['branchinfo_tin'];
    	}ELSE{
    		$comp_details = clsSSS::dbfetchCompDetails($gData['comp']);
    		$company_name = $comp_details['comp_name'];
    		$company_tin = $comp_details['comp_tin'];
    	}
    	
    	$filename = substr(str_replace("-","",$company_tin),0,8).".s73";
		    $handle = fopen($filename, "w");
		    // header
		    fwrite($handle, "H1604CF,".str_replace("-","",$company_tin).",".str_pad($gData['branchcode'],4,'0',STR_PAD_LEFT).",".date_format($date,'m/d/Y')."\r\n");
		    
			$emp = $this->getEmpNoPreEmployer($gData);
			// Initialize variables
		    $ctr = 1;
			$grand_gross = 0;
			$grand_bonus_nt = 0;
			$grand_dmb_nt = 0;
			$grand_stat_nt = 0;
			$grand_other_comp_nt = 0;
			$grand_total_nt = 0;
			$grand_basic_taxable = 0;
			$grand_bonus_taxable = 0;
			$grand_other_comp_taxable = 0;
			$grand_total_taxable = 0;
			$grand_exempt = 0;
			$grand_health_premium = 0;
			$grand_net_taxable = 0;
			$grand_taxdue = 0;
			$grand_taxwithheld = 0;
			$grand_taxwithheld_dec = 0;
			$grand_over_withheld = 0;
			$grand_actual_tax = 0;

			$comp_tin = str_replace("-","",$company_tin);
			$branch_code = str_pad($gData['branchcode'],4,'0',STR_PAD_LEFT);
			$return_period = date_format($date,'m/d/Y');
			// details
		    if (count($emp) > 0) {
		    	$tax_policy = $this->getTaxPolicy();
				foreach ($emp as $key => $val) {
					$string = "";
					switch ($val['taxep_code']) {
						case 'ME': $taxep_code = 'M'; break;
						case 'ME1': $taxep_code = 'M1'; break;
						case 'ME2': $taxep_code = 'M2'; break;
						case 'ME3': $taxep_code = 'M3'; break;
						case 'ME4': $taxep_code = 'M4'; break;
						default: $taxep_code = $val['taxep_code']; break;
					}
					// assign variable for easy formatting
					//$count = str_pad($ctr,6,'0',STR_PAD_LEFT);
					$count = $ctr;
					$emp_tin = str_replace("-","",$val['pi_tin']);
					$lastname = strtoupper($val['pi_lname']);
					$firstname = strtoupper($val['pi_fname']);
					$middlename = strtoupper($val['pi_mname']);
					
					// non-taxable
					if($this->getBonus($val['emp_id'], $gData['year'],0)>$tax_policy['tp_other_benefits']){
						$bonus_nt = $tax_policy['tp_other_benefits'];
						$addtn_bonus_taxable = $this->getBonus($val['emp_id'], $gData['year'],0)-$tax_policy['tp_other_benefits'];
					} else {
						$bonus_nt = $this->getBonus($val['emp_id'], $gData['year'],0);
						$addtn_bonus_taxable = 0;
					}
					$grand_bonus_nt += $bonus_nt;
					$bonus_nt = ($bonus_nt == 0 ? 0 : number_format($bonus_nt,2,'.',''));
					
					$dmb_nt = $this->getDeminimis($val['emp_id'], $gData['year']);				
					$grand_dmb_nt += $dmb_nt;
					$dmb_nt = ($dmb_nt == 0 ? 0 : number_format($dmb_nt,2,'.',''));
					
					$stat_nt = $this->getStatutoryAndUnionDues($val['emp_id'], $gData['year']);
					$grand_stat_nt += $stat_nt;
					$stat_nt = ($stat_nt == 0 ? 0 : number_format($stat_nt,2,'.',''));
					
					$other_comp_nt = $this->getOtherCompensation($val['emp_id'], $gData['year']);
					$grand_other_comp_nt += $other_comp_nt;
					$other_comp_nt = ($other_comp_nt == 0 ? 0 : number_format($other_comp_nt,2,'.',''));
					
					$total_nt = $bonus_nt+$dmb_nt+$stat_nt+$other_comp_nt;
					$grand_total_nt += $total_nt;
					$total_nt = ($total_nt == 0 ? 0 : number_format($total_nt,2,'.',''));
					
					// taxable
					$basic_taxable = ($this->getBasicIncome($val['emp_id'], $gData['year'])-$this->getStatutoryAndUnionDues($val['emp_id'], $gData['year']));
					$grand_basic_taxable += $basic_taxable;
					$basic_taxable = ($basic_taxable == 0 ? 0 : number_format($basic_taxable,2,'.',''));
					
					$bonus_taxable = $this->getBonus($val['emp_id'], $gData['year'],1)+$addtn_bonus_taxable;
					$grand_bonus_taxable += $bonus_taxable;
					$bonus_taxable = ($bonus_taxable == 0 ? 0 : number_format($bonus_taxable,2,'.',''));
					
					$other_comp_taxable = $this->getOtherCompensationTaxable($val['emp_id'], $gData['year']);
					$grand_other_comp_taxable += $other_comp_taxable;
					$other_comp_taxable = ($other_comp_taxable == 0 ? 0 : number_format($other_comp_taxable,2,'.',''));
					
					$total_taxable = $basic_taxable+$bonus_taxable+$other_comp_taxable;
					$grand_total_taxable += $total_taxable;
					$total_taxable = ($total_taxable == 0 ? 0 : number_format($total_taxable,2,'.',''));
					
					// gross
					$gross = $total_nt+$total_taxable;
					$grand_gross += $gross;
					$gross = ($gross == 0 ? 0 : number_format($gross,2,'.',''));
					
					$exempt = $this->getExemptAmount($taxep_code);
					$grand_exempt += $exempt;
					
					$health_premium = 0;
					$grand_health_premium += $health_premium;
					
					$net_taxable = (($total_taxable-$exempt) <= 0 ? 0 : number_format(($total_taxable-$exempt),2,'.',''));
					$grand_net_taxable += $net_taxable;
					
					$taxdue = $this->getAnnualTaxDue($val['emp_id'],$gData['year'],$taxep_code,$total_taxable);
					$grand_taxdue += $taxdue;
					$taxdue = ($taxdue == 0 ? 0 : number_format($taxdue,2,'.',''));
					
					$taxwithheld = $this->getTaxWithheld($val['emp_id'], $gData['year']);
					$grand_taxwithheld += $taxwithheld;
					$taxwithheld = ($taxwithheld == 0 ? 0 : number_format($taxwithheld,2,'.',''));
					
					$taxwithheld_dec  = $this->getTaxWithheldDecember($val['emp_id'], $gData['year']);
					$grand_taxwithheld_dec += $taxwithheld_dec;
					$taxwithheld_dec = ($taxwithheld_dec == 0 ? 0 : number_format($taxwithheld_dec,2,'.',''));
					
					$over_withheld = ($taxwithheld+$taxwithheld_dec)-$taxdue;
					$grand_over_withheld += $over_withheld;
					$over_withheld = ($over_withheld == 0 ? 0 : number_format($over_withheld,2,'.',''));
					
					$actual_tax = ($taxwithheld+$taxwithheld_dec)-$over_withheld;
					$grand_actual_tax += $actual_tax;
					$actual_tax = ($actual_tax == 0 ? 0 : number_format($actual_tax,2,'.',''));
					
					$substituted = "N";
					
					$string .= "D7.3";
					$string .= ",1604CF";
					$string .= ",".$comp_tin;
					$string .= ",".$branch_code;
					$string .= ",".$return_period;
					$string .= ",".$count;
					$string .= ",".$emp_tin;
					$string .= ",".$branch_code;
					$string .= ",".$lastname;
					$string .= ",".$firstname;
					$string .= ",".$middlename;
					$string .= ",".$gross;
					$string .= ",".$bonus_nt;
					$string .= ",".$dmb_nt;
					$string .= ",".$stat_nt;
					$string .= ",".$other_comp_nt;
					$string .= ",".$total_nt;
					$string .= ",".$basic_taxable;
					$string .= ",".$bonus_taxable;
					$string .= ",".$other_comp_taxable;
					$string .= ",".$total_taxable;
					$string .= ",".$taxep_code;
					$string .= ",".$exempt;
					$string .= ",".$health_premium;
					$string .= ",".$net_taxable;
					$string .= ",".$taxdue;
					$string .= ",".$taxwithheld;
					$string .= ",".$taxwithheld_dec;
					$string .= ",".$over_withheld;
					$string .= ",".$actual_tax;
					$string .= ",".$substituted;
					$string .= "\r\n";
					// write the details per employee
			     	fwrite($handle, $string);
					$ctr++;
				}
		    } else {
		    	
		    }
			// controls
		    $controls = "C7.3";
		    $controls .= ",1604CF";
		    $controls .= ",".$comp_tin;
			$controls .= ",".$branch_code;
			$controls .= ",".$return_period;
			$controls .= ",".$grand_gross;
			$controls .= ",".$grand_bonus_nt;
			$controls .= ",".$grand_dmb_nt;
			$controls .= ",".$grand_stat_nt;
			$controls .= ",".$grand_other_comp_nt;
			$controls .= ",".$grand_total_nt;
			$controls .= ",".$grand_basic_taxable;
			$controls .= ",".$grand_bonus_taxable;
			$controls .= ",".$grand_other_comp_taxable;
			$controls .= ",".$grand_total_taxable;
			$controls .= ",".$grand_exempt;
			$controls .= ",".$grand_health_premium;
			$controls .= ",".$grand_net_taxable;
			$controls .= ",".$grand_taxdue;
			$controls .= ",".$grand_taxwithheld;
			$controls .= ",".$grand_taxwithheld_dec;
			$controls .= ",".$grand_over_withheld;
			$controls .= ",".$grand_actual_tax;
			fwrite($handle, $controls);
		    
			fclose($handle);
			// force download the diskette file
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename='.basename($filename));
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($filename));
			readfile($filename);
			unlink($filename);
			exit;
    }
    
	function get1604CF7_4_diskette($gData = array()){
    	//printa($gData); exit;
    	$date = date_create($gData['return']); 
        IF($gData['branchinfo_id']!=0){//Get company Details
    		$loc_details = clsSSS::getLocationInfo($gData['branchinfo_id']);
    		$company_name = $loc_details['branchinfo_name'];
    		$company_tin = $loc_details['branchinfo_tin'];
    	}ELSE{
    		$comp_details = clsSSS::dbfetchCompDetails($gData['comp']);
    		$company_name = $comp_details['comp_name'];
    		$company_tin = $comp_details['comp_tin'];
    	}
    	
    	$filename = substr(str_replace("-","",$company_tin),0,8).".s74";
		    $handle = fopen($filename, "w");
		    // header
		    fwrite($handle, "H1604CF,".str_replace("-","",$company_tin).",".str_pad($gData['branchcode'],4,'0',STR_PAD_LEFT).",".date_format($date,'m/d/Y')."\r\n");
		    
			$emp = $this->getEmployeesWithPreviousEmployer($gData);
			// Initialize variables
		    $ctr = 1;
			$grand_gross = 0;
			$grand_bonus_nt_prev = 0;
			$grand_dmb_nt_prev = 0;
			$grand_stat_nt_prev = 0;
			$grand_other_comp_nt_prev = 0;
			$grand_total_nt_prev = 0;
			$grand_basic_taxable_prev = 0;
			$grand_bonus_taxable_prev = 0;
			$grand_other_comp_taxable_prev = 0;
			$grand_total_taxable_prev = 0;
			$grand_bonus_nt = 0;
			$grand_dmb_nt = 0;
			$grand_stat_nt = 0;
			$grand_other_comp_nt = 0;
			$grand_total_nt = 0;
			$grand_basic_taxable = 0;
			$grand_bonus_taxable = 0;
			$grand_other_comp_taxable = 0;
			$grand_total_taxable = 0;
			$grand_total_taxable_prev_and_curr = 0;
			$grand_exempt = 0;
			$grand_health_premium = 0;
			$grand_net_taxable = 0;
			$grand_taxdue = 0;
			$grand_taxwithheld_prev = 0;
			$grand_taxwithheld_present = 0;
			$grand_taxwithheld_dec = 0;
			$grand_over_withheld = 0;
			$grand_actual_tax = 0;

			$comp_tin = str_replace("-","",$company_tin);
			$branch_code = str_pad($gData['branchcode'],4,'0',STR_PAD_LEFT);
			$return_period = date_format($date,'m/d/Y');
			// details
		    if (count($emp) > 0) {
		    	$tax_policy = $this->getTaxPolicy();
				foreach ($emp as $key => $val) {
					$string = "";
					switch ($val['taxep_code']) {
						case 'ME': $taxep_code = 'M'; break;
						case 'ME1': $taxep_code = 'M1'; break;
						case 'ME2': $taxep_code = 'M2'; break;
						case 'ME3': $taxep_code = 'M3'; break;
						case 'ME4': $taxep_code = 'M4'; break;
						default: $taxep_code = $val['taxep_code']; break;
					}
					// assign variable for easy formatting
					//$count = str_pad($ctr,6,'0',STR_PAD_LEFT);
					$count = $ctr;
					$emp_tin = str_replace("-","",$val['pi_tin']);
					$lastname = strtoupper($val['pi_lname']);
					$firstname = strtoupper($val['pi_fname']);
					$middlename = strtoupper($val['pi_mname']);
					
					// previous
					$bonus_nt_prev = $val['nt_other_ben'];
					$grand_bonus_nt_prev += $bonus_nt_prev;
					$bonus_nt_prev = ($bonus_nt_prev == 0 ? 0 : number_format($bonus_nt_prev,2,'.',''));
					
					$dmb_nt_prev = $val['nt_deminimis'];
					$grand_dmb_nt_prev += $dmb_nt_prev;
					$dmb_nt_prev = ($dmb_nt_prev == 0 ? 0 : number_format($dmb_nt_prev,2,'.',''));
					
					$stat_nt_prev = $val['nt_statutories'];
					$grand_stat_nt_prev += $stat_nt_prev;
					$stat_nt_prev = ($stat_nt_prev == 0 ? 0 : number_format($stat_nt_prev,2,'.',''));
					
					$other_comp_nt_prev = $val['nt_compensation'];
					$grand_other_comp_nt_prev += $other_comp_nt_prev;
					$other_comp_nt_prev = ($other_comp_nt_prev == 0 ? 0 : number_format($other_comp_nt_prev,2,'.',''));
										
					$total_nt_prev = $bonus_nt_prev+$dmb_nt_prev+$stat_nt_prev+$other_comp_nt_prev;
					$grand_total_nt_prev += $total_nt_prev;
					$total_nt_prev = ($total_nt_prev == 0 ? 0 : number_format($total_nt_prev,2,'.',''));
					
					$basic_taxable_prev = $val['taxable_basic'];
					$grand_basic_taxable_prev += $basic_taxable_prev;
					$basic_taxable_prev = ($basic_taxable_prev == 0 ? 0 : number_format($basic_taxable_prev,2,'.',''));
					
					$bonus_taxable_prev = $val['taxable_other_ben'];
					$grand_bonus_taxable_prev += $bonus_taxable_prev;
					$bonus_taxable_prev = ($bonus_taxable_prev == 0 ? 0 : number_format($bonus_taxable_prev,2,'.',''));
					
					$other_comp_taxable_prev = $val['taxable_compensation'];
					$grand_other_comp_taxable_prev += $other_comp_taxable_prev;
					$other_comp_taxable_prev = ($other_comp_taxable_prev == 0 ? 0 : number_format($other_comp_taxable_prev,2,'.',''));
					
					$total_taxable_prev = ($basic_taxable_prev+$bonus_taxable_prev+$other_comp_taxable_prev)-$stat_nt_prev;
					$grand_total_taxable_prev += $total_taxable_prev;
					$total_taxable_prev = ($total_taxable_prev == 0 ? 0 : number_format($total_taxable_prev,2,'.',''));

					// present
					// non-taxable
					IF($val['nt_other_ben'] >= $tax_policy['tp_other_benefits']){
						$bonus_nt = 0;
						$addtn_bonus_taxable = $this->getBonus($val['emp_id'], $gData['year'],0);
					} ELSE {
						if($this->getBonus($val['emp_id'], $gData['year'],0)>$tax_policy['tp_other_benefits']){
							$bonus_nt = $tax_policy['tp_other_benefits'];
							$addtn_bonus_taxable = $this->getBonus($val['emp_id'], $gData['year'],0)-$tax_policy['tp_other_benefits'];
						} else {
							$bonus_nt = $this->getBonus($val['emp_id'], $gData['year'],0);
							$addtn_bonus_taxable = 0;
						}
					}
					$grand_bonus_nt += $bonus_nt;
					$bonus_nt = ($bonus_nt == 0 ? 0 : number_format($bonus_nt,2,'.',''));
					
					$dmb_nt = $this->getDeminimis($val['emp_id'], $gData['year']);				
					$grand_dmb_nt += $dmb_nt;
					$dmb_nt = ($dmb_nt == 0 ? 0 : number_format($dmb_nt,2,'.',''));
					
					$stat_nt = $this->getStatutoryAndUnionDues($val['emp_id'], $gData['year']);
					$grand_stat_nt += $stat_nt;
					$stat_nt = ($stat_nt == 0 ? 0 : number_format($stat_nt,2,'.',''));
					
					$other_comp_nt = $this->getOtherCompensation($val['emp_id'], $gData['year']);
					$grand_other_comp_nt += $other_comp_nt;
					$other_comp_nt = ($other_comp_nt == 0 ? 0 : number_format($other_comp_nt,2,'.',''));
					
					$total_nt = $bonus_nt+$dmb_nt+$stat_nt+$other_comp_nt;
					$grand_total_nt += $total_nt;
					$total_nt = ($total_nt == 0 ? 0 : number_format($total_nt,2,'.',''));
					
					// taxable
					$basic_taxable = ($this->getBasicIncome($val['emp_id'], $gData['year'])-$this->getStatutoryAndUnionDues($val['emp_id'], $gData['year']));
					$grand_basic_taxable += $basic_taxable;
					$basic_taxable = ($basic_taxable == 0 ? 0 : number_format($basic_taxable,2,'.',''));
					
					$bonus_taxable = $this->getBonus($val['emp_id'], $gData['year'],1)+$addtn_bonus_taxable;
					$grand_bonus_taxable += $bonus_taxable;
					$bonus_taxable = ($bonus_taxable == 0 ? 0 : number_format($bonus_taxable,2,'.',''));
					
					$other_comp_taxable = $this->getOtherCompensationTaxable($val['emp_id'], $gData['year']);
					$grand_other_comp_taxable += $other_comp_taxable;
					$other_comp_taxable = ($other_comp_taxable == 0 ? 0 : number_format($other_comp_taxable,2,'.',''));
					
					$total_taxable = $basic_taxable+$bonus_taxable+$other_comp_taxable;
					$grand_total_taxable += $total_taxable;
					$total_taxable = ($total_taxable == 0 ? 0 : number_format($total_taxable,2,'.',''));
					
					$total_taxable_prev_and_curr = $total_taxable_prev+$total_taxable;
					$grand_total_taxable_prev_and_curr += $total_taxable_prev_and_curr;
					$total_taxable_prev_and_curr = ($total_taxable_prev_and_curr == 0 ? 0 : number_format($total_taxable_prev_and_curr,2,'.',''));
					
					// gross
					$gross = $total_nt_prev+$total_nt+$total_taxable_prev_and_curr;
					$grand_gross += $gross;
					$gross = ($gross == 0 ? 0 : number_format($gross,2,'.',''));
					
					$exempt = $this->getExemptAmount($taxep_code);
					$grand_exempt += $exempt;
					
					$health_premium = 0;
					$grand_health_premium += $health_premium;

					$net_taxable = (($total_taxable_prev_and_curr-$exempt) <= 0 ? 0 : number_format(($total_taxable_prev_and_curr-$exempt),2,'.',''));
					$grand_net_taxable += $net_taxable;
					
					$taxdue = $this->getAnnualTaxDue($val['emp_id'],$gData['year'],$taxep_code,$total_taxable_prev_and_curr);
					$grand_taxdue += $taxdue;
					$taxdue = ($taxdue == 0 ? 0 : number_format($taxdue,2,'.',''));
					
					$taxwithheld_prev = $val['tax_withheld'];
					$grand_taxwithheld_prev += $taxwithheld_prev;
					$taxwithheld_prev = ($taxwithheld_prev == 0 ? 0 : number_format($taxwithheld_prev,2,'.',''));
					
					$taxwithheld_present = $this->getTaxWithheld($val['emp_id'], $gData['year']);
					$grand_taxwithheld_present += $taxwithheld_present;
					$taxwithheld_present = ($taxwithheld_present == 0 ? 0 : number_format($taxwithheld_present,2,'.',''));
					
					$taxwithheld_dec  = $this->getTaxWithheldDecember($val['emp_id'], $gData['year']);
					$grand_taxwithheld_dec += $taxwithheld_dec;
					$taxwithheld_dec = ($taxwithheld_dec == 0 ? 0 : number_format($taxwithheld_dec,2,'.',''));
					
					$over_withheld = (($taxwithheld_prev+$taxwithheld_present)+$taxwithheld_dec)-$taxdue;
					$grand_over_withheld += $over_withheld;
					$over_withheld = ($over_withheld == 0 ? 0 : number_format($over_withheld,2,'.',''));
					
					$actual_tax = (($taxwithheld_prev+$taxwithheld_present)+$taxwithheld_dec)-$over_withheld;
					$grand_actual_tax += $actual_tax;
					$actual_tax = ($actual_tax == 0 ? 0 : number_format($actual_tax,2,'.',''));
					
					$string .= "D7.4";
					$string .= ",1604CF";
					$string .= ",".$comp_tin;
					$string .= ",".$branch_code;
					$string .= ",".$return_period;
					$string .= ",".$count;
					$string .= ",".$emp_tin;
					$string .= ",".$branch_code;
					$string .= ",".$lastname;
					$string .= ",".$firstname;
					$string .= ",".$middlename;
					$string .= ",".$gross;
					$string .= ",".$bonus_nt_prev;
					$string .= ",".$dmb_nt_prev;
					$string .= ",".$other_comp_nt_prev;			
					$string .= ",".$stat_nt_prev;
					$string .= ",".$total_nt_prev;
					$string .= ",".$basic_taxable_prev;
					$string .= ",".$bonus_taxable_prev;
					$string .= ",".$other_comp_taxable_prev;
					$string .= ",".$total_taxable_prev;
					$string .= ",".$bonus_nt;
					$string .= ",".$dmb_nt;
					$string .= ",".$stat_nt;
					$string .= ",".$other_comp_nt;
					$string .= ",".$total_nt;
					$string .= ",".$basic_taxable;
					$string .= ",".$bonus_taxable;
					$string .= ",".$other_comp_taxable;
					$string .= ",".$total_taxable;
					$string .= ",".$total_taxable_prev_and_curr;
					$string .= ",".$taxep_code;
					$string .= ",".$exempt;
					$string .= ",".$health_premium;
					$string .= ",".$net_taxable;
					$string .= ",".$taxdue;
					$string .= ",".$taxwithheld_prev;
					$string .= ",".$taxwithheld_present;
					$string .= ",".$taxwithheld_dec;
					$string .= ",".$over_withheld;
					$string .= ",".$actual_tax;
					$string .= "\r\n";
					// write the details per employee
			     	fwrite($handle, $string);
					$ctr++;
				}
		    } else {
		    	
		    }
			// controls
		    $controls = "C7.4";
		    $controls .= ",1604CF";
		    $controls .= ",".$comp_tin;
			$controls .= ",".$branch_code;
			$controls .= ",".$return_period;
			$controls .= ",".number_format($grand_gross,2,'.','');
			$controls .= ",".number_format($grand_bonus_nt_prev,2,'.','');
			$controls .= ",".number_format($grand_dmb_nt_prev,2,'.','');
			$controls .= ",".number_format($grand_stat_nt_prev,2,'.','');
			$controls .= ",".number_format($grand_other_comp_nt_prev,2,'.','');
			$controls .= ",".number_format($grand_total_nt_prev,2,'.','');
			$controls .= ",".number_format($grand_basic_taxable_prev,2,'.','');
			$controls .= ",".number_format($grand_bonus_taxable_prev,2,'.','');
			$controls .= ",".number_format($grand_other_comp_taxable_prev,2,'.','');
			$controls .= ",".number_format($grand_total_taxable_prev,2,'.','');
			$controls .= ",".number_format($grand_bonus_nt,2,'.','');
			$controls .= ",".number_format($grand_dmb_nt,2,'.','');
			$controls .= ",".number_format($grand_stat_nt,2,'.','');
			$controls .= ",".number_format($grand_other_comp_nt,2,'.','');
			$controls .= ",".number_format($grand_total_nt,2,'.','');
			$controls .= ",".number_format($grand_basic_taxable,2,'.','');
			$controls .= ",".number_format($grand_bonus_taxable,2,'.','');
			$controls .= ",".number_format($grand_other_comp_taxable,2,'.','');
			$controls .= ",".number_format($grand_total_taxable,2,'.','');
			$controls .= ",".number_format($grand_total_taxable_prev_and_curr,2,'.','');
			$controls .= ",".number_format($grand_exempt,2,'.','');
			$controls .= ",".number_format($grand_health_premium,2,'.','');
			$controls .= ",".number_format($grand_net_taxable,2,'.','');
			$controls .= ",".number_format($grand_taxdue,2,'.','');
			$controls .= ",".number_format($grand_taxwithheld_prev,2,'.','');
			$controls .= ",".number_format($grand_taxwithheld_present,2,'.','');
			$controls .= ",".number_format($grand_taxwithheld_dec,2,'.','');
			$controls .= ",".number_format($grand_over_withheld,2,'.','');
			$controls .= ",".number_format($grand_actual_tax,2,'.','');
			fwrite($handle, $controls);
		    
			fclose($handle);
			// force download the diskette file
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename='.basename($filename));
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($filename));
			readfile($filename);
			unlink($filename);
			exit;
    }
    
	function get1604CF7_5_diskette($gData = array()){
    	//printa($gData); exit;
    	$date = date_create($gData['return']); 
        IF($gData['branchinfo_id']!=0){//Get company Details
    		$loc_details = clsSSS::getLocationInfo($gData['branchinfo_id']);
    		$company_name = $loc_details['branchinfo_name'];
    		$company_tin = $loc_details['branchinfo_tin'];
    	}ELSE{
    		$comp_details = clsSSS::dbfetchCompDetails($gData['comp']);
    		$company_name = $comp_details['comp_name'];
    		$company_tin = $comp_details['comp_tin'];
    	}
    	
    	$filename = substr(str_replace("-","",$company_tin),0,8).".s75";
		    $handle = fopen($filename, "w");
		    // header
		    fwrite($handle, "H1604CF,".str_replace("-","",$company_tin).",".str_pad($gData['branchcode'],4,'0',STR_PAD_LEFT).",".date_format($date,'m/d/Y')."\r\n");
		    
			$emp = $this->getEmpMWE($gData);
			// Initialize variables
		    $ctr = 1;
			$grand_gross_compensation_prev = 0;
			$grand_basic_smw_prev = 0;
			$grand_holiday_pay_prev = 0;
			$grand_overtime_pay_prev = 0;
			$grand_night_differential_prev = 0;
			$grand_hazard_pay_prev = 0;
			$grand_nt_other_ben_prev = 0;
			$grand_nt_deminimis_prev = 0;
			$grand_nt_statutories_prev = 0;
			$grand_nt_compensation_prev = 0;
			$grand_total_nt_prev = 0;
			$grand_taxable_other_ben_prev = 0;
			$grand_taxable_compensation_prev = 0;
			$grand_total_taxable_prev = 0;
			$grand_gross = 0;
			$grand_basicSMWPerDay = 0;
			$grand_basicSMWPerMonth = 0;
		    $grand_basicSMWPerYear = 0;
		    $grand_holiday_pay_present = 0;
			$grand_overtime_pay_present = 0;
    		$grand_nd_present = 0;
    		$grand_hazard_pay = 0;
			$grand_bonus_nt = 0;		
			$grand_dmb_nt = 0;
			$grand_stat_nt = 0;
			$grand_other_comp_nt = 0;
			$grand_bonus_taxable = 0;
			$grand_other_comp_taxable = 0;
			$grand_total_compensation_present = 0;
			$grand_total_compensation_prev_and_present = 0;
	    	$grand_exempt = 0;
			$grand_health_premium = 0;
			$grand_net_taxable = 0;
			$grand_taxdue = 0;
	    	$grand_tax_withheld_prev = 0;
			$grand_taxwithheld = 0;
			$grand_taxwithheld_dec = 0;
			$grand_over_withheld = 0;
			$grand_actual_tax = 0;
					

			$comp_tin = str_replace("-","",$company_tin);
			$branch_code = str_pad($gData['branchcode'],4,'0',STR_PAD_LEFT);
			$return_period = date_format($date,'m/d/Y');
			// details
		    if (count($emp) > 0) {
		    	$tax_policy = $this->getTaxPolicy();
				foreach ($emp as $key => $val) {
					$string = "";
					$val['fr_dayperyear'] ? $factor_day_per_year = $val['fr_dayperyear'] : $factor_day_per_year = "";
    				$val['region_code'] ? $region_no_where_assigned = $val['region_code'] :	$region_no_where_assigned = "";
					switch ($val['taxep_code']) {
						case 'ME': $taxep_code = 'M'; break;
						case 'ME1': $taxep_code = 'M1'; break;
						case 'ME2': $taxep_code = 'M2'; break;
						case 'ME3': $taxep_code = 'M3'; break;
						case 'ME4': $taxep_code = 'M4'; break;
						default: $taxep_code = $val['taxep_code']; break;
					}
					// assign variable for easy formatting
					//$count = str_pad($ctr,6,'0',STR_PAD_LEFT);
					$count = $ctr;
					$emp_tin = str_replace("-","",$val['pi_tin']);
					$lastname = strtoupper($val['pi_lname']);
					$firstname = strtoupper($val['pi_fname']);
					$middlename = strtoupper($val['pi_mname']);
					
					// previous
					$gross_compensation_prev = $val['gross_compensation'];
					$grand_gross_compensation_prev += $gross_compensation_prev;
					$gross_compensation_prev = ($gross_compensation_prev == 0 ? 0 : number_format($gross_compensation_prev,2,'.',''));
					
					$basic_smw_prev = $val['basic_smw'];
					$grand_basic_smw_prev += $basic_smw_prev;
					$basic_smw_prev = ($basic_smw_prev == 0 ? 0 : number_format($basic_smw_prev,2,'.',''));
					
					$holiday_pay_prev = $val['holiday_pay'];
					$grand_holiday_pay_prev += $holiday_pay_prev;
					$holiday_pay_prev = ($holiday_pay_prev == 0 ? 0 : number_format($holiday_pay_prev,2,'.',''));
					
					$overtime_pay_prev = $val['overtime_pay'];
					$grand_overtime_pay_prev += $overtime_pay_prev;
					$overtime_pay_prev = ($overtime_pay_prev == 0 ? 0 : number_format($overtime_pay_prev,2,'.',''));
					
					$night_differential_prev = $val['night_differential'];
					$grand_night_differential_prev += $night_differential_prev;
					$night_differential_prev = ($night_differential_prev == 0 ? 0 : number_format($night_differential_prev,2,'.',''));
					
					$hazard_pay_prev = $val['hazard_pay'];
					$grand_hazard_pay_prev += $hazard_pay_prev;
					$hazard_pay_prev = ($hazard_pay_prev == 0 ? 0 : number_format($hazard_pay_prev,2,'.',''));
					
					$nt_other_ben_prev = $val['nt_other_ben'];
					$grand_nt_other_ben_prev += $nt_other_ben_prev;
					$nt_other_ben_prev = ($nt_other_ben_prev == 0 ? 0 : number_format($nt_other_ben_prev,2,'.',''));
					
					$nt_deminimis_prev = $val['nt_deminimis'];
					$grand_nt_deminimis_prev += $nt_deminimis_prev;
					$nt_deminimis_prev = ($nt_deminimis_prev == 0 ? 0 : number_format($nt_deminimis_prev,2,'.',''));
					
					$nt_statutories_prev = $val['nt_statutories'];
					$grand_nt_statutories_prev += $nt_statutories_prev;
					$nt_statutories_prev = ($nt_statutories_prev == 0 ? 0 : number_format($nt_statutories_prev,2,'.',''));
					
					$nt_compensation_prev = $val['nt_compensation'];
					$grand_nt_compensation_prev += $nt_compensation_prev;
					$nt_compensation_prev = ($nt_compensation_prev == 0 ? 0 : number_format($nt_compensation_prev,2,'.',''));
					
					$total_nt_prev = $nt_other_ben_prev+$nt_deminimis_prev+$nt_statutories_prev+$nt_compensation_prev;
					$grand_total_nt_prev += $total_nt_prev;
					$total_nt_prev = ($total_nt_prev == 0 ? 0 : number_format($total_nt_prev,2,'.',''));
					
					$taxable_other_ben_prev = $val['taxable_other_ben'];
					$grand_taxable_other_ben_prev += $taxable_other_ben_prev;
					$taxable_other_ben_prev = ($taxable_other_ben_prev == 0 ? 0 : number_format($taxable_other_ben_prev,2,'.',''));
					
					$taxable_compensation_prev = $val['taxable_compensation'];
					$grand_taxable_compensation_prev += $taxable_compensation_prev;
					$taxable_compensation_prev = ($taxable_compensation_prev == 0 ? 0 : number_format($taxable_compensation_prev,2,'.',''));
					
					$total_taxable_prev = $taxable_other_ben_prev+$taxable_compensation_prev;
					$grand_total_taxable_prev += $total_taxable_prev;
					$total_taxable_prev = ($total_taxable_prev == 0 ? 0 : number_format($total_taxable_prev,2,'.',''));
					
					// present
					$date_from = date_create($val['date_start']);
					$start_date = date_format($date_from,'m/d/Y');
					$date_to = date_create($this->endDateReplace($val['date_resign'], $val['date_retire']));
					$end_date = date_format($date_to,'m/d/Y');
					
					if ($factor_day_per_year) {
		    			$salaryInfoBasicRate = $this->getBasicIncome($val['emp_id'], $gData['year']);
		    			
		    			$basicSMWPerYear = $salaryInfoBasicRate;
		    			$grand_basicSMWPerYear += $basicSMWPerYear;
						$basicSMWPerYear = ($basicSMWPerYear == 0 ? 0 : number_format($basicSMWPerYear,2,'.',''));
					
		    			$basicSMWPerDay = $salaryInfoBasicRate / $factor_day_per_year;
		    			$grand_basicSMWPerDay += $basicSMWPerDay;
						$basicSMWPerDay = ($basicSMWPerDay == 0 ? 0 : number_format($basicSMWPerDay,2,'.',''));
					
		    			$basicSMWPerMonth = $basicSMWPerYear / 12;
		    			$grand_basicSMWPerMonth += $basicSMWPerMonth;
						$basicSMWPerMonth = ($basicSMWPerMonth == 0 ? 0 : number_format($basicSMWPerMonth,2,'.',''));
					}
    				$holiday_pay_present = 0;
    				$grand_holiday_pay_present += $holiday_pay_present;
					$holiday_pay_present = ($holiday_pay_present == 0 ? 0 : number_format($holiday_pay_present,2,'.',''));
    				
					$overtime_pay_present = $this->getOvertime($val['emp_id'], $gData['year']);
					$grand_overtime_pay_present += $overtime_pay_present;
					$overtime_pay_present = ($overtime_pay_present == 0 ? 0 : number_format($overtime_pay_present,2,'.',''));
						
    				$nd_present = 0;
    				$grand_nd_present += $nd_present;
					$nd_present = ($nd_present == 0 ? 0 : number_format($nd_present,2,'.',''));
    				
					$hazard_pay = 0;
    				$grand_hazard_pay += $hazard_pay;
					$hazard_pay = ($hazard_pay == 0 ? 0 : number_format($hazard_pay,2,'.',''));
					if($this->getBonus($val['emp_id'], $gData['year'],0)>$tax_policy['tp_other_benefits']){
						$bonus_nt = $tax_policy['tp_other_benefits'];
						$addtn_bonus_taxable = $this->getBonus($val['emp_id'], $gData['year'],0)-$tax_policy['tp_other_benefits'];
					} else {
						$bonus_nt = $this->getBonus($val['emp_id'], $gData['year']);
						$addtn_bonus_taxable = 0;
					}
					$grand_bonus_nt += $bonus_nt;
					$bonus_nt = ($bonus_nt == 0 ? 0 : number_format($bonus_nt,2,'.',''));
					
					$dmb_nt = $this->getDeminimis($val['emp_id'], $gData['year']);				
					$grand_dmb_nt += $dmb_nt;
					$dmb_nt = ($dmb_nt == 0 ? 0 : number_format($dmb_nt,2,'.',''));
					
					$stat_nt = $this->getStatutoryAndUnionDues($val['emp_id'], $gData['year']);
					$grand_stat_nt += $stat_nt;
					$stat_nt = ($stat_nt == 0 ? 0 : number_format($stat_nt,2,'.',''));
					
					$other_comp_nt = $this->getOtherCompensation($val['emp_id'], $gData['year']);
					$grand_other_comp_nt += $other_comp_nt;
					$other_comp_nt = ($other_comp_nt == 0 ? 0 : number_format($other_comp_nt,2,'.',''));
					
					$bonus_taxable = $this->getBonus($val['emp_id'], $gData['year'],1)+$addtn_bonus_taxable;
					$grand_bonus_taxable += $bonus_taxable;
					$bonus_taxable = ($bonus_taxable == 0 ? 0 : number_format($bonus_taxable,2,'.',''));
					
					$other_comp_taxable = $this->getOtherCompensationTaxable($val['emp_id'], $gData['year'])-$overtime_pay_present;
					$grand_other_comp_taxable += $other_comp_taxable;
					$other_comp_taxable = ($other_comp_taxable == 0 ? 0 : number_format($other_comp_taxable,2,'.',''));

					$gross = $basicSMWPerYear+$holiday_pay_present+$overtime_pay_present+$nd_present+$hazard_pay+$bonus_nt+$dmb_nt+$stat_nt+$other_comp_nt;
					$grand_gross += $gross;
					$gross = ($gross == 0 ? 0 : number_format($gross,2,'.',''));
					
					$total_compensation_present = $gross+$bonus_taxable+$other_comp_taxable;
					$grand_total_compensation_present += $total_compensation_present;
					$total_compensation_present = ($total_compensation_present == 0 ? 0 : number_format($total_compensation_present,2,'.',''));
					
					$total_compensation_prev_and_present = $gross_compensation_prev+$total_taxable_prev+$total_compensation_present;
					$grand_total_compensation_prev_and_present += $total_compensation_prev_and_present;
					$total_compensation_prev_and_present = ($total_compensation_prev_and_present == 0 ? 0 : number_format($total_compensation_prev_and_present,2,'.',''));
					
					$exempt = $this->getExemptAmount($taxep_code);
	    			$grand_exempt += $exempt;
					$exempt = ($exempt == 0 ? 0 : number_format($exempt,2,'.',''));
					
					$health_premium = 0;
					$grand_health_premium += $health_premium;
					
					$net_taxable = ((($total_taxable_prev+$bonus_taxable+$other_comp_taxable)-$exempt) <= 0 ? 0 : number_format((($total_taxable_prev+$bonus_taxable+$other_comp_taxable)-$exempt),2,'.',''));
					$grand_net_taxable += $net_taxable;
					$net_taxable = ($net_taxable == 0 ? 0 : number_format($net_taxable,2,'.',''));
	    			
					$taxgross = $total_taxable_prev+$bonus_taxable+$other_comp_taxable;
					$taxdue = $this->getAnnualTaxDue($val['emp_id'],$gData['year'],$taxep_code,$taxgross);
					$grand_taxdue += $taxdue;
					$taxdue = ($taxdue == 0 ? 0 : number_format($taxdue,2,'.',''));
	    			
	    			$tax_withheld_prev = $val['tax_withheld']; // previous employer tax
	    			$grand_tax_withheld_prev += $tax_withheld_prev;
					$tax_withheld_prev = ($tax_withheld_prev == 0 ? 0 : number_format($tax_withheld_prev,2,'.',''));
					
					$taxwithheld = $this->getTaxWithheld($val['emp_id'], $gData['year']);
					$grand_taxwithheld += $taxwithheld;
					$taxwithheld = ($taxwithheld == 0 ? 0 : number_format($taxwithheld,2,'.',''));
					
					$taxwithheld_dec  = $this->getTaxWithheldDecember($val['emp_id'], $gData['year']);
					$grand_taxwithheld_dec += $taxwithheld_dec;
					$taxwithheld_dec = ($taxwithheld_dec == 0 ? 0 : number_format($taxwithheld_dec,2,'.',''));
					
					$over_withheld = (($tax_withheld_prev+$taxwithheld)+$taxwithheld_dec)-$taxdue;
					$grand_over_withheld += $over_withheld;
					$over_withheld = ($over_withheld == 0 ? 0 : number_format($over_withheld,2,'.',''));
					
					$actual_tax = (($tax_withheld_prev+$taxwithheld)+$taxwithheld_dec)-$over_withheld;
					$grand_actual_tax += $actual_tax;
					$actual_tax = ($actual_tax == 0 ? 0 : number_format($actual_tax,2,'.',''));
					

					$string .= "D7.5";
					$string .= ",1604CF";
					$string .= ",".$comp_tin;
					$string .= ",".$branch_code;
					$string .= ",".$return_period;
					$string .= ",".$count;
					$string .= ",".$emp_tin;
					$string .= ",".$branch_code;
					$string .= ",".$lastname;
					$string .= ",".$firstname; // 10
					$string .= ",".$middlename;
					$string .= ",".$region_no_where_assigned;
					$string .= ",".$gross_compensation_prev;
					$string .= ",".$basic_smw_prev;
					$string .= ",".$holiday_pay_prev;
					$string .= ",".$overtime_pay_prev;
					$string .= ",".$night_differential_prev;
					$string .= ",".$hazard_pay_prev;
					$string .= ",".$nt_other_ben_prev;
					$string .= ",".$nt_deminimis_prev; // 20
					$string .= ",".$nt_statutories_prev;
					$string .= ",".$nt_compensation_prev;
					$string .= ",".$total_nt_prev;
					$string .= ",".$taxable_other_ben_prev;
					$string .= ",".$taxable_compensation_prev;
					$string .= ",".$total_taxable_prev;
					$string .= ",".$start_date;
					$string .= ",".$end_date;
					$string .= ",".$gross;
					$string .= ",".$basicSMWPerDay; // 30
					$string .= ",".$basicSMWPerMonth;
					$string .= ",".$basicSMWPerYear;
					$string .= ",".$factor_day_per_year;
					$string .= ",".$holiday_pay_present;
    				$string .= ",".$overtime_pay_present;
					$string .= ",".$nd_present;
    				$string .= ",".$hazard_pay;
					$string .= ",".$bonus_nt;
					$string .= ",".$dmb_nt;
					$string .= ",".$stat_nt; // 40
					$string .= ",".$other_comp_nt;
					$string .= ",".$bonus_taxable;
					$string .= ",".$other_comp_taxable;
					$string .= ",".$total_compensation_present;
					$string .= ",".$total_compensation_prev_and_present;
					$string .= ",".$taxep_code;
					$string .= ",".$exempt;
					$string .= ",".$health_premium;
					$string .= ",".$net_taxable;
					$string .= ",".$taxdue; // 50
	    			$string .= ",".$tax_withheld_prev;
					$string .= ",".$taxwithheld;
					$string .= ",".$taxwithheld_dec;
					$string .= ",".$over_withheld;
					$string .= ",".$actual_tax;
					$string .= "\r\n";
					// write the details per employee
			     	fwrite($handle, $string);
					$ctr++;
				}
		    } else {
		    	
		    }
			// controls
		    $controls = "C7.5";
		    $controls .= ",1604CF";
		    $controls .= ",".$comp_tin;
			$controls .= ",".$branch_code;
			$controls .= ",".$return_period;
			$controls .= ",".number_format($grand_gross_compensation_prev,2,'.','');
			$controls .= ",".number_format($grand_basic_smw_prev,2,'.','');
			$controls .= ",".number_format($grand_holiday_pay_prev,2,'.','');
			$controls .= ",".number_format($grand_overtime_pay_prev,2,'.','');
			$controls .= ",".number_format($grand_night_differential_prev,2,'.',''); // 10
			$controls .= ",".number_format($grand_hazard_pay_prev,2,'.','');
			$controls .= ",".number_format($grand_nt_other_ben_prev,2,'.','');
			$controls .= ",".number_format($grand_nt_deminimis_prev,2,'.','');
			$controls .= ",".number_format($grand_nt_statutories_prev,2,'.','');
			$controls .= ",".number_format($grand_nt_compensation_prev,2,'.','');
			$controls .= ",".number_format($grand_total_nt_prev,2,'.','');
			$controls .= ",".number_format($grand_taxable_other_ben_prev,2,'.','');
			$controls .= ",".number_format($grand_taxable_compensation_prev,2,'.','');
			$controls .= ",".number_format($grand_total_taxable_prev,2,'.','');
			$controls .= ",".number_format($grand_gross,2,'.',''); // 20
			$controls .= ",".number_format($grand_basicSMWPerDay,2,'.','');
			$controls .= ",".number_format($grand_basicSMWPerMonth,2,'.','');
		    $controls .= ",".number_format($grand_basicSMWPerYear,2,'.','');
		    $controls .= ",".number_format($grand_holiday_pay_present,2,'.','');
			$controls .= ",".number_format($grand_overtime_pay_present,2,'.','');
    		$controls .= ",".number_format($grand_nd_present,2,'.','');
    		$controls .= ",".number_format($grand_hazard_pay,2,'.','');
			$controls .= ",".number_format($grand_bonus_nt,2,'.','');		
			$controls .= ",".number_format($grand_dmb_nt,2,'.','');
			$controls .= ",".number_format($grand_stat_nt,2,'.',''); // 30
			$controls .= ",".number_format($grand_other_comp_nt,2,'.','');
			$controls .= ",".number_format($grand_bonus_taxable,2,'.','');
			$controls .= ",".number_format($grand_other_comp_taxable,2,'.','');
			$controls .= ",".number_format($grand_total_compensation_present,2,'.','');
			$controls .= ",".number_format($grand_total_compensation_prev_and_present,2,'.','');
	    	$controls .= ",".number_format($grand_exempt,2,'.','');
			$controls .= ",".number_format($grand_health_premium,2,'.','');
			$controls .= ",".number_format($grand_net_taxable,2,'.','');
			$controls .= ",".number_format($grand_taxdue,2,'.','');
	    	$controls .= ",".number_format($grand_tax_withheld_prev,2,'.',''); // 40
			$controls .= ",".number_format($grand_taxwithheld,2,'.','');
			$controls .= ",".number_format($grand_taxwithheld_dec,2,'.','');
			$controls .= ",".number_format($grand_over_withheld,2,'.','');
			$controls .= ",".number_format($grand_actual_tax,2,'.','');
			fwrite($handle, $controls);
		    
			fclose($handle);
			// force download the diskette file
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename='.basename($filename));
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($filename));
			readfile($filename);
			unlink($filename);
			exit;
    }
    
    function get1604CFDiskette($gData = array()){
    	$date = date_create($gData['return']); 
        IF($gData['branchinfo_id']!=0){//Get company Details
    		$loc_details = clsSSS::getLocationInfo($gData['branchinfo_id']);
    		$company_name = $loc_details['branchinfo_name'];
    		$company_tin = $loc_details['branchinfo_tin'];
    	}ELSE{
    		$comp_details = clsSSS::dbfetchCompDetails($gData['comp']);
    		$company_name = $comp_details['comp_name'];
    		$company_tin = $comp_details['comp_tin'];
    	}
    	
    		$filename = substr(str_replace("-","",$company_tin),0,9).str_pad($gData['branchcode'],4,'0',STR_PAD_LEFT).date_format($date,'mdY')."1604CF.DAT";
		    $handle = fopen($filename, "w");
		    // header
		    fwrite($handle, "H1604CF,".str_replace("-","",$company_tin).",".str_pad($gData['branchcode'],4,'0',STR_PAD_LEFT).",".date_format($date,'m/d/Y')."\r\n");
		    
		    // 7.1 Schedule
			$emp = $this->getTerminatedEmployee($gData);//Get all Terminated Employee
			// Initialize variables
		    $ctr = 1;
			$grand_gross = 0;
			$grand_bonus_nt = 0;
			$grand_dmb_nt = 0;
			$grand_stat_nt = 0;
			$grand_other_comp_nt = 0;
			$grand_total_nt = 0;
			$grand_basic_taxable = 0;
			$grand_bonus_taxable = 0;
			$grand_other_comp_taxable = 0;
			$grand_total_taxable = 0;
			$grand_exempt = 0;
			$grand_health_premium = 0;
			$grand_net_taxable = 0;
			$grand_taxdue = 0;
			$grand_taxwithheld = 0;
			$grand_taxwithheld_dec = 0;
			$grand_over_withheld = 0;
			$grand_actual_tax = 0;

			$comp_tin = str_replace("-","",$company_tin);
			$branch_code = str_pad($gData['branchcode'],4,'0',STR_PAD_LEFT);
			$return_period = date_format($date,'m/d/Y');
			// details
		    if (count($emp) > 0) {
		    	$tax_policy = $this->getTaxPolicy();
				foreach ($emp as $key => $val) {
					$string = "";
					switch ($val['taxep_code']) {
						case 'ME': $taxep_code = 'M'; break;
						case 'ME1': $taxep_code = 'M1'; break;
						case 'ME2': $taxep_code = 'M2'; break;
						case 'ME3': $taxep_code = 'M3'; break;
						case 'ME4': $taxep_code = 'M4'; break;
						default: $taxep_code = $val['taxep_code']; break;
					}
					$prevEmployer = $this->getPreviousEmployer($val['emp_id'],$gData['year']);
					// assign variable for easy formatting
					//$count = str_pad($ctr,6,'0',STR_PAD_LEFT);
					$count = $ctr;
					$emp_tin = str_replace("-","",$val['pi_tin']);
					$lastname = strtoupper($val['pi_lname']);
					$firstname = strtoupper($val['pi_fname']);
					$middlename = strtoupper($val['pi_mname']);
					$date_from = date_create($val['date_start']);
					$start_date = date_format($date_from,'m/d/Y');
					$date_to = date_create($this->endDateReplace($val['date_resign'], $val['date_retire']));
					$end_date = date_format($date_to,'m/d/Y');
					
					// non-taxable
					if(($this->getBonus($val['emp_id'], $gData['year'],0)+$prevEmployer['nt_other_ben'])>$tax_policy['tp_other_benefits']){
						$bonus_nt = $tax_policy['tp_other_benefits'];
						$addtn_bonus_taxable = ($this->getBonus($val['emp_id'], $gData['year'],0)+$prevEmployer['nt_other_ben'])-$tax_policy['tp_other_benefits'];
					} else {
						$bonus_nt = $this->getBonus($val['emp_id'], $gData['year'],0)+$prevEmployer['nt_other_ben'];
						$addtn_bonus_taxable = 0;
					}
					$grand_bonus_nt += $bonus_nt;
					$bonus_nt = ($bonus_nt == 0 ? 0 : number_format($bonus_nt,2,'.',''));
					
					$dmb_nt = $this->getDeminimis($val['emp_id'], $gData['year'])+$prevEmployer['nt_deminimis'];				
					$grand_dmb_nt += $dmb_nt;
					$dmb_nt = ($dmb_nt == 0 ? 0 : number_format($dmb_nt,2,'.',''));
					
					$stat_nt = $this->getStatutoryAndUnionDues($val['emp_id'], $gData['year'])+$prevEmployer['nt_statutories'];
					$grand_stat_nt += $stat_nt;
					$stat_nt = ($stat_nt == 0 ? 0 : number_format($stat_nt,2,'.',''));
					
					$other_comp_nt = $this->getOtherCompensation($val['emp_id'], $gData['year'])+$prevEmployer['nt_compensation'];
					$grand_other_comp_nt += $other_comp_nt;
					$other_comp_nt = ($other_comp_nt == 0 ? 0 : number_format($other_comp_nt,2,'.',''));
					
					$total_nt = $bonus_nt+$dmb_nt+$stat_nt+$other_comp_nt;
					$grand_total_nt += $total_nt;
					$total_nt = ($total_nt == 0 ? 0 : number_format($total_nt,2,'.',''));
					
					// taxable
					$basic_taxable = ($prevEmployer['taxable_basic']-$prevEmployer['nt_statutories'])+($this->getBasicIncome($val['emp_id'], $gData['year'])-$this->getStatutoryAndUnionDues($val['emp_id'], $gData['year']));
					$grand_basic_taxable += $basic_taxable;
					$basic_taxable = ($basic_taxable == 0 ? 0 : number_format($basic_taxable,2,'.',''));
					
					$bonus_taxable = $this->getBonus($val['emp_id'], $gData['year'],1)+$prevEmployer['taxable_other_ben']+$addtn_bonus_taxable;
					$grand_bonus_taxable += $bonus_taxable;
					$bonus_taxable = ($bonus_taxable == 0 ? 0 : number_format($bonus_taxable,2,'.',''));
					
					$other_comp_taxable = $this->getOtherCompensationTaxable($val['emp_id'], $gData['year'])+$prevEmployer['taxable_compensation'];
					$grand_other_comp_taxable += $other_comp_taxable;
					$other_comp_taxable = ($other_comp_taxable == 0 ? 0 : number_format($other_comp_taxable,2,'.',''));
					
					$total_taxable = $basic_taxable+$bonus_taxable+$other_comp_taxable;
					$grand_total_taxable += $total_taxable;
					$total_taxable = ($total_taxable == 0 ? 0 : number_format($total_taxable,2,'.',''));
					
					// gross
					$gross = $total_nt+$total_taxable;
					$grand_gross += $gross;
					$gross = ($gross == 0 ? 0 : number_format($gross,2,'.',''));
					
					$exempt = $this->getExemptAmount($taxep_code);
					$grand_exempt += $exempt;
					
					$health_premium = 0;
					$grand_health_premium += $health_premium;
					
					$net_taxable = (($total_taxable-$exempt) <= 0 ? 0 : number_format(($total_taxable-$exempt),2,'.',''));
					$grand_net_taxable += $net_taxable;
					
					$taxdue = $this->getAnnualTaxDue($val['emp_id'],$gData['year'],$taxep_code,$total_taxable);
					$grand_taxdue += $taxdue;
					$taxdue = ($taxdue == 0 ? 0 : number_format($taxdue,2,'.',''));
					
					$taxwithheld = $this->getTaxWithheld($val['emp_id'], $gData['year'])+$prevEmployer['tax_withheld'];
					$grand_taxwithheld += $taxwithheld;
					$taxwithheld = ($taxwithheld == 0 ? 0 : number_format($taxwithheld,2,'.',''));
					
					$taxwithheld_dec  = $this->getTaxWithheldDecember($val['emp_id'], $gData['year']);
					$grand_taxwithheld_dec += $taxwithheld_dec;
					$taxwithheld_dec = ($taxwithheld_dec == 0 ? 0 : number_format($taxwithheld_dec,2,'.',''));
					
					$over_withheld = ($taxwithheld+$taxwithheld_dec)-$taxdue;
					$grand_over_withheld += $over_withheld;
					$over_withheld = ($over_withheld == 0 ? 0 : number_format($over_withheld,2,'.',''));
					
					$actual_tax = ($taxwithheld+$taxwithheld_dec)-$over_withheld;
					$grand_actual_tax += $actual_tax;
					$actual_tax = ($actual_tax == 0 ? 0 : number_format($actual_tax,2,'.',''));
					
					$substituted = "N";
					
					$string .= "D7.1";
					$string .= ",1604CF";
					$string .= ",".$comp_tin;
					$string .= ",".$branch_code;
					$string .= ",".$return_period;
					$string .= ",".$count;
					$string .= ",".$emp_tin;
					$string .= ",".$branch_code;
					$string .= ",".$lastname;
					$string .= ",".$firstname;
					$string .= ",".$middlename;
					$string .= ",".$start_date;
					$string .= ",".$end_date;
					$string .= ",".$gross;
					$string .= ",".$bonus_nt;
					$string .= ",".$dmb_nt;
					$string .= ",".$stat_nt;
					$string .= ",".$other_comp_nt;
					$string .= ",".$total_nt;
					$string .= ",".$basic_taxable;
					$string .= ",".$bonus_taxable;
					$string .= ",".$other_comp_taxable;
					$string .= ",".$total_taxable;
					$string .= ",".$taxep_code;
					$string .= ",".$exempt;
					$string .= ",".$health_premium;
					$string .= ",".$net_taxable;
					$string .= ",".$taxdue;
					$string .= ",".$taxwithheld;
					$string .= ",".$taxwithheld_dec;
					$string .= ",".$over_withheld;
					$string .= ",".$actual_tax;
					$string .= ",".$substituted;
					$string .= "\r\n";
					// write the details per employee
			     	fwrite($handle, $string);
					$ctr++;
				}
		    } else {
		    	
		    }
		    if (count($emp) > 0) {
				// controls
			    $controls = "C7.1";
			    $controls .= ",1604CF";
			    $controls .= ",".$comp_tin;
				$controls .= ",".$branch_code;
				$controls .= ",".$return_period;
				$controls .= ",".$grand_gross;
				$controls .= ",".$grand_bonus_nt;
				$controls .= ",".$grand_dmb_nt;
				$controls .= ",".$grand_stat_nt;
				$controls .= ",".$grand_other_comp_nt;
				$controls .= ",".$grand_total_nt;
				$controls .= ",".$grand_basic_taxable;
				$controls .= ",".$grand_bonus_taxable;
				$controls .= ",".$grand_other_comp_taxable;
				$controls .= ",".$grand_total_taxable;
				$controls .= ",".$grand_exempt;
				$controls .= ",".$grand_health_premium;
				$controls .= ",".$grand_net_taxable;
				$controls .= ",".$grand_taxdue;
				$controls .= ",".$grand_taxwithheld;
				$controls .= ",".$grand_taxwithheld_dec;
				$controls .= ",".$grand_over_withheld;
				$controls .= ",".$grand_actual_tax;
				$controls .= "\r\n";
				fwrite($handle, $controls);
		    }
		    
			// 7.2 Schedule
			$emp = $this->getEmpNoPreEmployer($gData,1);
			// Initialize variables
		    $ctr = 1;
			$grand_gross = 0;
			$grand_bonus_nt = 0;
			$grand_dmb_nt = 0;
			$grand_stat_nt = 0;
			$grand_other_comp_nt = 0;
			$grand_total_nt = 0;
			$grand_basic_taxable = 0;
			$grand_other_comp_taxable = 0;
			$grand_exempt = 0;
			$grand_health_premium = 0;
			$grand_net_taxable = 0;
			$grand_taxdue = 0;
			
			$comp_tin = str_replace("-","",$company_tin);
			$branch_code = str_pad($gData['branchcode'],4,'0',STR_PAD_LEFT);
			$return_period = date_format($date,'m/d/Y');
			// details
		    if (count($emp) > 0) {
				foreach ($emp as $key => $val) {
					$string = "";
					switch ($val['taxep_code']) {
						case 'ME': $taxep_code = 'M'; break;
						case 'ME1': $taxep_code = 'M1'; break;
						case 'ME2': $taxep_code = 'M2'; break;
						case 'ME3': $taxep_code = 'M3'; break;
						case 'ME4': $taxep_code = 'M4'; break;
						default: $taxep_code = $val['taxep_code']; break;
					}
					// assign variable for easy formatting
					//$count = str_pad($ctr,6,'0',STR_PAD_LEFT);
					$count = $ctr;
					$emp_tin = str_replace("-","",$val['pi_tin']);
					$lastname = strtoupper($val['pi_lname']);
					$firstname = strtoupper($val['pi_fname']);
					$middlename = strtoupper($val['pi_mname']);
					$date_from = date_create($val['date_start']);
					$start_date = date_format($date_from,'m/d/Y');
					$date_to = date_create($this->endDateReplace($val['date_resign'], $val['date_retire']));
					$end_date = date_format($date_to,'m/d/Y');
					
					// non-taxable
					$bonus_nt = $this->getBonus($val['emp_id'], $gData['year'],0);
					$grand_bonus_nt += $bonus_nt;
					$bonus_nt = ($bonus_nt == 0 ? 0 : number_format($bonus_nt,2,'.',''));
					
					$dmb_nt = $this->getDeminimis($val['emp_id'], $gData['year']);				
					$grand_dmb_nt += $dmb_nt;
					$dmb_nt = ($dmb_nt == 0 ? 0 : number_format($dmb_nt,2,'.',''));
					
					$stat_nt = $this->getStatutoryAndUnionDues($val['emp_id'], $gData['year']);
					$grand_stat_nt += $stat_nt;
					$stat_nt = ($stat_nt == 0 ? 0 : number_format($stat_nt,2,'.',''));
					
					$other_comp_nt = $this->getOtherCompensation($val['emp_id'], $gData['year']);
					$grand_other_comp_nt += $other_comp_nt;
					$other_comp_nt = ($other_comp_nt == 0 ? 0 : number_format($other_comp_nt,2,'.',''));
					
					$total_nt = $bonus_nt+$dmb_nt+$stat_nt+$other_comp_nt;
					$grand_total_nt += $total_nt;
					$total_nt = ($total_nt == 0 ? 0 : number_format($total_nt,2,'.',''));
					
					// taxable
					$basic_taxable = ($this->getBasicIncome($val['emp_id'], $gData['year'])-$this->getStatutoryAndUnionDues($val['emp_id'], $gData['year']));
					$grand_basic_taxable += $basic_taxable;
					$basic_taxable = ($basic_taxable == 0 ? 0 : number_format($basic_taxable,2,'.',''));
					
					$other_comp_taxable = $this->getOtherCompensationTaxable($val['emp_id'], $gData['year']);
					$grand_other_comp_taxable += $other_comp_taxable;
					$other_comp_taxable = ($other_comp_taxable == 0 ? 0 : number_format($other_comp_taxable,2,'.',''));
					
					// gross
					$gross = $total_nt+$total_taxable;
					$grand_gross += $gross;
					$gross = ($gross == 0 ? 0 : number_format($gross,2,'.',''));
					
					$exempt = $this->getExemptAmount($taxep_code);
					$grand_exempt += $exempt;
					
					$health_premium = 0;
					$grand_health_premium += $health_premium;
					
					$net_taxable = (($total_taxable-$exempt) <= 0 ? 0 : number_format(($total_taxable-$exempt),2,'.',''));
					$grand_net_taxable += $net_taxable;
					
					$taxdue = $this->getAnnualTaxDue($val['emp_id'],$gData['year'],$taxep_code,$total_taxable);
					$grand_taxdue += $taxdue;
					$taxdue = ($taxdue == 0 ? 0 : number_format($taxdue,2,'.',''));
					
					$string .= "D7.2";
					$string .= ",1604CF";
					$string .= ",".$comp_tin;
					$string .= ",".$branch_code;
					$string .= ",".$return_period;
					$string .= ",".$count;
					$string .= ",".$emp_tin;
					$string .= ",".$branch_code;
					$string .= ",".$lastname;
					$string .= ",".$firstname;
					$string .= ",".$middlename;
					$string .= ",".$gross;
					$string .= ",".$bonus_nt;
					$string .= ",".$dmb_nt;
					$string .= ",".$stat_nt;
					$string .= ",".$other_comp_nt;
					$string .= ",".$total_nt;
					$string .= ",".$basic_taxable;
					$string .= ",".$other_comp_taxable;
					$string .= ",".$taxep_code;
					$string .= ",".$exempt;
					$string .= ",".$health_premium;
					$string .= ",".$net_taxable;
					$string .= ",".$taxdue;
					$string .= "\r\n";
					// write the details per employee
			     	fwrite($handle, $string);
					$ctr++;
				}
		    } else {
		    	
		    }
		    if (count($emp) > 0) {
				// controls
			    $controls = "C7.2";
			    $controls .= ",1604CF";
			    $controls .= ",".$comp_tin;
				$controls .= ",".$branch_code;
				$controls .= ",".$return_period;
				$controls .= ",".$grand_gross;
				$controls .= ",".$grand_bonus_nt;
				$controls .= ",".$grand_dmb_nt;
				$controls .= ",".$grand_stat_nt;
				$controls .= ",".$grand_other_comp_nt;
				$controls .= ",".$grand_total_nt;
				$controls .= ",".$grand_basic_taxable;
				$controls .= ",".$grand_other_comp_taxable;
				$controls .= ",".$grand_exempt;
				$controls .= ",".$grand_health_premium;
				$controls .= ",".$grand_net_taxable;
				$controls .= ",".$grand_taxdue;
				$controls .= "\r\n";
				fwrite($handle, $controls);
		    }
		    
		    // 7.3 Schedule
			$emp = $this->getEmpNoPreEmployer($gData);
			// Initialize variables
		    $ctr = 1;
			$grand_gross = 0;
			$grand_bonus_nt = 0;
			$grand_dmb_nt = 0;
			$grand_stat_nt = 0;
			$grand_other_comp_nt = 0;
			$grand_total_nt = 0;
			$grand_basic_taxable = 0;
			$grand_bonus_taxable = 0;
			$grand_other_comp_taxable = 0;
			$grand_total_taxable = 0;
			$grand_exempt = 0;
			$grand_health_premium = 0;
			$grand_net_taxable = 0;
			$grand_taxdue = 0;
			$grand_taxwithheld = 0;
			$grand_taxwithheld_dec = 0;
			$grand_over_withheld = 0;
			$grand_actual_tax = 0;

			$comp_tin = str_replace("-","",$company_tin);
			$branch_code = str_pad($gData['branchcode'],4,'0',STR_PAD_LEFT);
			$return_period = date_format($date,'m/d/Y');
			// details
		    if (count($emp) > 0) {
		    	$tax_policy = $this->getTaxPolicy();
				foreach ($emp as $key => $val) {
					$string = "";
					switch ($val['taxep_code']) {
						case 'ME': $taxep_code = 'M'; break;
						case 'ME1': $taxep_code = 'M1'; break;
						case 'ME2': $taxep_code = 'M2'; break;
						case 'ME3': $taxep_code = 'M3'; break;
						case 'ME4': $taxep_code = 'M4'; break;
						default: $taxep_code = $val['taxep_code']; break;
					}
					// assign variable for easy formatting
					//$count = str_pad($ctr,6,'0',STR_PAD_LEFT);
					$count = $ctr;
					$emp_tin = str_replace("-","",$val['pi_tin']);
					$lastname = strtoupper($val['pi_lname']);
					$firstname = strtoupper($val['pi_fname']);
					$middlename = strtoupper($val['pi_mname']);
					
					// non-taxable
					if($this->getBonus($val['emp_id'], $gData['year'],0)>$tax_policy['tp_other_benefits']){
						$bonus_nt = $tax_policy['tp_other_benefits'];
						$addtn_bonus_taxable = $this->getBonus($val['emp_id'], $gData['year'],0)-$tax_policy['tp_other_benefits'];
					} else {
						$bonus_nt = $this->getBonus($val['emp_id'], $gData['year'],0);
						$addtn_bonus_taxable = 0;
					}
					$grand_bonus_nt += $bonus_nt;
					$bonus_nt = ($bonus_nt == 0 ? 0 : number_format($bonus_nt,2,'.',''));
					
					$dmb_nt = $this->getDeminimis($val['emp_id'], $gData['year']);				
					$grand_dmb_nt += $dmb_nt;
					$dmb_nt = ($dmb_nt == 0 ? 0 : number_format($dmb_nt,2,'.',''));
					
					$stat_nt = $this->getStatutoryAndUnionDues($val['emp_id'], $gData['year']);
					$grand_stat_nt += $stat_nt;
					$stat_nt = ($stat_nt == 0 ? 0 : number_format($stat_nt,2,'.',''));
					
					$other_comp_nt = $this->getOtherCompensation($val['emp_id'], $gData['year']);
					$grand_other_comp_nt += $other_comp_nt;
					$other_comp_nt = ($other_comp_nt == 0 ? 0 : number_format($other_comp_nt,2,'.',''));
					
					$total_nt = $bonus_nt+$dmb_nt+$stat_nt+$other_comp_nt;
					$grand_total_nt += $total_nt;
					$total_nt = ($total_nt == 0 ? 0 : number_format($total_nt,2,'.',''));
					
					// taxable
					$basic_taxable = ($this->getBasicIncome($val['emp_id'], $gData['year'])-$this->getStatutoryAndUnionDues($val['emp_id'], $gData['year']));
					$grand_basic_taxable += $basic_taxable;
					$basic_taxable = ($basic_taxable == 0 ? 0 : number_format($basic_taxable,2,'.',''));
					
					$bonus_taxable = $this->getBonus($val['emp_id'], $gData['year'],1)+$addtn_bonus_taxable;
					$grand_bonus_taxable += $bonus_taxable;
					$bonus_taxable = ($bonus_taxable == 0 ? 0 : number_format($bonus_taxable,2,'.',''));
					
					$other_comp_taxable = $this->getOtherCompensationTaxable($val['emp_id'], $gData['year']);
					$grand_other_comp_taxable += $other_comp_taxable;
					$other_comp_taxable = ($other_comp_taxable == 0 ? 0 : number_format($other_comp_taxable,2,'.',''));
					
					$total_taxable = $basic_taxable+$bonus_taxable+$other_comp_taxable;
					$grand_total_taxable += $total_taxable;
					$total_taxable = ($total_taxable == 0 ? 0 : number_format($total_taxable,2,'.',''));
					
					// gross
					$gross = $total_nt+$total_taxable;
					$grand_gross += $gross;
					$gross = ($gross == 0 ? 0 : number_format($gross,2,'.',''));
					
					$exempt = $this->getExemptAmount($taxep_code);
					$grand_exempt += $exempt;
					
					$health_premium = 0;
					$grand_health_premium += $health_premium;
					
					$net_taxable = (($total_taxable-$exempt) <= 0 ? 0 : number_format(($total_taxable-$exempt),2,'.',''));
					$grand_net_taxable += $net_taxable;
					
					$taxdue = $this->getAnnualTaxDue($val['emp_id'],$gData['year'],$taxep_code,$total_taxable);
					$grand_taxdue += $taxdue;
					$taxdue = ($taxdue == 0 ? 0 : number_format($taxdue,2,'.',''));
					
					$taxwithheld = $this->getTaxWithheld($val['emp_id'], $gData['year']);
					$grand_taxwithheld += $taxwithheld;
					$taxwithheld = ($taxwithheld == 0 ? 0 : number_format($taxwithheld,2,'.',''));
					
					$taxwithheld_dec  = $this->getTaxWithheldDecember($val['emp_id'], $gData['year']);
					$grand_taxwithheld_dec += $taxwithheld_dec;
					$taxwithheld_dec = ($taxwithheld_dec == 0 ? 0 : number_format($taxwithheld_dec,2,'.',''));
					
					$over_withheld = ($taxwithheld+$taxwithheld_dec)-$taxdue;
					$grand_over_withheld += $over_withheld;
					$over_withheld = ($over_withheld == 0 ? 0 : number_format($over_withheld,2,'.',''));
					
					$actual_tax = ($taxwithheld+$taxwithheld_dec)-$over_withheld;
					$grand_actual_tax += $actual_tax;
					$actual_tax = ($actual_tax == 0 ? 0 : number_format($actual_tax,2,'.',''));
					
					$substituted = "N";
					
					$string .= "D7.3";
					$string .= ",1604CF";
					$string .= ",".$comp_tin;
					$string .= ",".$branch_code;
					$string .= ",".$return_period;
					$string .= ",".$count;
					$string .= ",".$emp_tin;
					$string .= ",".$branch_code;
					$string .= ",".$lastname;
					$string .= ",".$firstname;
					$string .= ",".$middlename;
					$string .= ",".$gross;
					$string .= ",".$bonus_nt;
					$string .= ",".$dmb_nt;
					$string .= ",".$stat_nt;
					$string .= ",".$other_comp_nt;
					$string .= ",".$total_nt;
					$string .= ",".$basic_taxable;
					$string .= ",".$bonus_taxable;
					$string .= ",".$other_comp_taxable;
					$string .= ",".$total_taxable;
					$string .= ",".$taxep_code;
					$string .= ",".$exempt;
					$string .= ",".$health_premium;
					$string .= ",".$net_taxable;
					$string .= ",".$taxdue;
					$string .= ",".$taxwithheld;
					$string .= ",".$taxwithheld_dec;
					$string .= ",".$over_withheld;
					$string .= ",".$actual_tax;
					$string .= ",".$substituted;
					$string .= "\r\n";
					// write the details per employee
			     	fwrite($handle, $string);
					$ctr++;
				}
		    } else {
		    	
		    }
		    if (count($emp) > 0) {
				// controls
			    $controls = "C7.3";
			    $controls .= ",1604CF";
			    $controls .= ",".$comp_tin;
				$controls .= ",".$branch_code;
				$controls .= ",".$return_period;
				$controls .= ",".$grand_gross;
				$controls .= ",".$grand_bonus_nt;
				$controls .= ",".$grand_dmb_nt;
				$controls .= ",".$grand_stat_nt;
				$controls .= ",".$grand_other_comp_nt;
				$controls .= ",".$grand_total_nt;
				$controls .= ",".$grand_basic_taxable;
				$controls .= ",".$grand_bonus_taxable;
				$controls .= ",".$grand_other_comp_taxable;
				$controls .= ",".$grand_total_taxable;
				$controls .= ",".$grand_exempt;
				$controls .= ",".$grand_health_premium;
				$controls .= ",".$grand_net_taxable;
				$controls .= ",".$grand_taxdue;
				$controls .= ",".$grand_taxwithheld;
				$controls .= ",".$grand_taxwithheld_dec;
				$controls .= ",".$grand_over_withheld;
				$controls .= ",".$grand_actual_tax;
				$controls .= "\r\n";
				fwrite($handle, $controls);
		    }
		    
		    // 7.4 Schedule
		    $emp = $this->getEmployeesWithPreviousEmployer($gData);
			// Initialize variables
		    $ctr = 1;
			$grand_gross = 0;
			$grand_bonus_nt_prev = 0;
			$grand_dmb_nt_prev = 0;
			$grand_stat_nt_prev = 0;
			$grand_other_comp_nt_prev = 0;
			$grand_total_nt_prev = 0;
			$grand_basic_taxable_prev = 0;
			$grand_bonus_taxable_prev = 0;
			$grand_other_comp_taxable_prev = 0;
			$grand_total_taxable_prev = 0;
			$grand_bonus_nt = 0;
			$grand_dmb_nt = 0;
			$grand_stat_nt = 0;
			$grand_other_comp_nt = 0;
			$grand_total_nt = 0;
			$grand_basic_taxable = 0;
			$grand_bonus_taxable = 0;
			$grand_other_comp_taxable = 0;
			$grand_total_taxable = 0;
			$grand_total_taxable_prev_and_curr = 0;
			$grand_exempt = 0;
			$grand_health_premium = 0;
			$grand_net_taxable = 0;
			$grand_taxdue = 0;
			$grand_taxwithheld_prev = 0;
			$grand_taxwithheld_present = 0;
			$grand_taxwithheld_dec = 0;
			$grand_over_withheld = 0;
			$grand_actual_tax = 0;

			$comp_tin = str_replace("-","",$company_tin);
			$branch_code = str_pad($gData['branchcode'],4,'0',STR_PAD_LEFT);
			$return_period = date_format($date,'m/d/Y');
			// details
		    if (count($emp) > 0) {
		    	$tax_policy = $this->getTaxPolicy();
				foreach ($emp as $key => $val) {
					$string = "";
					switch ($val['taxep_code']) {
						case 'ME': $taxep_code = 'M'; break;
						case 'ME1': $taxep_code = 'M1'; break;
						case 'ME2': $taxep_code = 'M2'; break;
						case 'ME3': $taxep_code = 'M3'; break;
						case 'ME4': $taxep_code = 'M4'; break;
						default: $taxep_code = $val['taxep_code']; break;
					}
					// assign variable for easy formatting
					//$count = str_pad($ctr,6,'0',STR_PAD_LEFT);
					$count = $ctr;
					$emp_tin = str_replace("-","",$val['pi_tin']);
					$lastname = strtoupper($val['pi_lname']);
					$firstname = strtoupper($val['pi_fname']);
					$middlename = strtoupper($val['pi_mname']);
					
					// previous
					$bonus_nt_prev = $val['nt_other_ben'];
					$grand_bonus_nt_prev += $bonus_nt_prev;
					$bonus_nt_prev = ($bonus_nt_prev == 0 ? 0 : number_format($bonus_nt_prev,2,'.',''));
					
					$dmb_nt_prev = $val['nt_deminimis'];
					$grand_dmb_nt_prev += $dmb_nt_prev;
					$dmb_nt_prev = ($dmb_nt_prev == 0 ? 0 : number_format($dmb_nt_prev,2,'.',''));
					
					$stat_nt_prev = $val['nt_statutories'];
					$grand_stat_nt_prev += $stat_nt_prev;
					$stat_nt_prev = ($stat_nt_prev == 0 ? 0 : number_format($stat_nt_prev,2,'.',''));
					
					$other_comp_nt_prev = $val['nt_compensation'];
					$grand_other_comp_nt_prev += $other_comp_nt_prev;
					$other_comp_nt_prev = ($other_comp_nt_prev == 0 ? 0 : number_format($other_comp_nt_prev,2,'.',''));
										
					$total_nt_prev = $bonus_nt_prev+$dmb_nt_prev+$stat_nt_prev+$other_comp_nt_prev;
					$grand_total_nt_prev += $total_nt_prev;
					$total_nt_prev = ($total_nt_prev == 0 ? 0 : number_format($total_nt_prev,2,'.',''));
					
					$basic_taxable_prev = $val['taxable_basic'];
					$grand_basic_taxable_prev += $basic_taxable_prev;
					$basic_taxable_prev = ($basic_taxable_prev == 0 ? 0 : number_format($basic_taxable_prev,2,'.',''));
					
					$bonus_taxable_prev = $val['taxable_other_ben'];
					$grand_bonus_taxable_prev += $bonus_taxable_prev;
					$bonus_taxable_prev = ($bonus_taxable_prev == 0 ? 0 : number_format($bonus_taxable_prev,2,'.',''));
					
					$other_comp_taxable_prev = $val['taxable_compensation'];
					$grand_other_comp_taxable_prev += $other_comp_taxable_prev;
					$other_comp_taxable_prev = ($other_comp_taxable_prev == 0 ? 0 : number_format($other_comp_taxable_prev,2,'.',''));
					
					$total_taxable_prev = ($basic_taxable_prev+$bonus_taxable_prev+$other_comp_taxable_prev)-$stat_nt_prev;
					$grand_total_taxable_prev += $total_taxable_prev;
					$total_taxable_prev = ($total_taxable_prev == 0 ? 0 : number_format($total_taxable_prev,2,'.',''));

					// present
					// non-taxable
					IF($bonus_nt_prev >= $tax_policy['tp_other_benefits']){
						$bonus_nt = 0;
						$addtn_bonus_taxable = $this->getBonus($val['emp_id'], $gData['year'],0);
					} ELSE {
						if($this->getBonus($val['emp_id'], $gData['year'],0)>$tax_policy['tp_other_benefits']){
							$bonus_nt = $tax_policy['tp_other_benefits'];
							$addtn_bonus_taxable = $this->getBonus($val['emp_id'], $gData['year'],0)-$tax_policy['tp_other_benefits'];
						} else {
							$bonus_nt = $this->getBonus($val['emp_id'], $gData['year'],0);
							$addtn_bonus_taxable = 0;
						}
					}
					$grand_bonus_nt += $bonus_nt;
					$bonus_nt = ($bonus_nt == 0 ? 0 : number_format($bonus_nt,2,'.',''));
					
					$dmb_nt = $this->getDeminimis($val['emp_id'], $gData['year']);				
					$grand_dmb_nt += $dmb_nt;
					$dmb_nt = ($dmb_nt == 0 ? 0 : number_format($dmb_nt,2,'.',''));
					
					$stat_nt = $this->getStatutoryAndUnionDues($val['emp_id'], $gData['year']);
					$grand_stat_nt += $stat_nt;
					$stat_nt = ($stat_nt == 0 ? 0 : number_format($stat_nt,2,'.',''));
					
					$other_comp_nt = $this->getOtherCompensation($val['emp_id'], $gData['year']);
					$grand_other_comp_nt += $other_comp_nt;
					$other_comp_nt = ($other_comp_nt == 0 ? 0 : number_format($other_comp_nt,2,'.',''));
					
					$total_nt = $bonus_nt+$dmb_nt+$stat_nt+$other_comp_nt;
					$grand_total_nt += $total_nt;
					$total_nt = ($total_nt == 0 ? 0 : number_format($total_nt,2,'.',''));
					
					// taxable
					$basic_taxable = ($this->getBasicIncome($val['emp_id'], $gData['year'])-$this->getStatutoryAndUnionDues($val['emp_id'], $gData['year']));
					$grand_basic_taxable += $basic_taxable;
					$basic_taxable = ($basic_taxable == 0 ? 0 : number_format($basic_taxable,2,'.',''));
					
					$bonus_taxable = $this->getBonus($val['emp_id'], $gData['year'],1)+$addtn_bonus_taxable;
					$grand_bonus_taxable += $bonus_taxable;
					$bonus_taxable = ($bonus_taxable == 0 ? 0 : number_format($bonus_taxable,2,'.',''));
					
					$other_comp_taxable = $this->getOtherCompensationTaxable($val['emp_id'], $gData['year']);
					$grand_other_comp_taxable += $other_comp_taxable;
					$other_comp_taxable = ($other_comp_taxable == 0 ? 0 : number_format($other_comp_taxable,2,'.',''));
					
					$total_taxable = $basic_taxable+$bonus_taxable+$other_comp_taxable;
					$grand_total_taxable += $total_taxable;
					$total_taxable = ($total_taxable == 0 ? 0 : number_format($total_taxable,2,'.',''));
					
					$total_taxable_prev_and_curr = $total_taxable_prev+$total_taxable;
					$grand_total_taxable_prev_and_curr += $total_taxable_prev_and_curr;
					$total_taxable_prev_and_curr = ($total_taxable_prev_and_curr == 0 ? 0 : number_format($total_taxable_prev_and_curr,2,'.',''));
					
					// gross
					$gross = $total_nt_prev+$total_nt+$total_taxable_prev_and_curr;
					$grand_gross += $gross;
					$gross = ($gross == 0 ? 0 : number_format($gross,2,'.',''));
					
					$exempt = $this->getExemptAmount($taxep_code);
					$grand_exempt += $exempt;
					
					$health_premium = 0;
					$grand_health_premium += $health_premium;

					$net_taxable = (($total_taxable_prev_and_curr-$exempt) <= 0 ? 0 : number_format(($total_taxable_prev_and_curr-$exempt),2,'.',''));
					$grand_net_taxable += $net_taxable;
					
					$taxdue = $this->getAnnualTaxDue($val['emp_id'],$gData['year'],$taxep_code,$total_taxable_prev_and_curr);
					$grand_taxdue += $taxdue;
					$taxdue = ($taxdue == 0 ? 0 : number_format($taxdue,2,'.',''));
					
					$taxwithheld_prev = $val['tax_withheld'];
					$grand_taxwithheld_prev += $taxwithheld_prev;
					$taxwithheld_prev = ($taxwithheld_prev == 0 ? 0 : number_format($taxwithheld_prev,2,'.',''));
					
					$taxwithheld_present = $this->getTaxWithheld($val['emp_id'], $gData['year']);
					$grand_taxwithheld_present += $taxwithheld_present;
					$taxwithheld_present = ($taxwithheld_present == 0 ? 0 : number_format($taxwithheld_present,2,'.',''));
					
					$taxwithheld_dec  = $this->getTaxWithheldDecember($val['emp_id'], $gData['year']);
					$grand_taxwithheld_dec += $taxwithheld_dec;
					$taxwithheld_dec = ($taxwithheld_dec == 0 ? 0 : number_format($taxwithheld_dec,2,'.',''));
					
					$over_withheld = (($taxwithheld_prev+$taxwithheld_present)+$taxwithheld_dec)-$taxdue;
					$grand_over_withheld += $over_withheld;
					$over_withheld = ($over_withheld == 0 ? 0 : number_format($over_withheld,2,'.',''));
					
					$actual_tax = (($taxwithheld_prev+$taxwithheld_present)+$taxwithheld_dec)-$over_withheld;
					$grand_actual_tax += $actual_tax;
					$actual_tax = ($actual_tax == 0 ? 0 : number_format($actual_tax,2,'.',''));
					
					$string .= "D7.4";
					$string .= ",1604CF";
					$string .= ",".$comp_tin;
					$string .= ",".$branch_code;
					$string .= ",".$return_period;
					$string .= ",".$count;
					$string .= ",".$emp_tin;
					$string .= ",".$branch_code;
					$string .= ",".$lastname;
					$string .= ",".$firstname;
					$string .= ",".$middlename;
					$string .= ",".$gross;
					$string .= ",".$bonus_nt_prev;
					$string .= ",".$dmb_nt_prev;
					$string .= ",".$other_comp_nt_prev;			
					$string .= ",".$stat_nt_prev;
					$string .= ",".$total_nt_prev;
					$string .= ",".$basic_taxable_prev;
					$string .= ",".$bonus_taxable_prev;
					$string .= ",".$other_comp_taxable_prev;
					$string .= ",".$total_taxable_prev;
					$string .= ",".$bonus_nt;
					$string .= ",".$dmb_nt;
					$string .= ",".$stat_nt;
					$string .= ",".$other_comp_nt;
					$string .= ",".$total_nt;
					$string .= ",".$basic_taxable;
					$string .= ",".$bonus_taxable;
					$string .= ",".$other_comp_taxable;
					$string .= ",".$total_taxable;
					$string .= ",".$total_taxable_prev_and_curr;
					$string .= ",".$taxep_code;
					$string .= ",".$exempt;
					$string .= ",".$health_premium;
					$string .= ",".$net_taxable;
					$string .= ",".$taxdue;
					$string .= ",".$taxwithheld_prev;
					$string .= ",".$taxwithheld_present;
					$string .= ",".$taxwithheld_dec;
					$string .= ",".$over_withheld;
					$string .= ",".$actual_tax;
					$string .= "\r\n";
					// write the details per employee
			     	fwrite($handle, $string);
					$ctr++;
				}
		    } else {
		    	
		    }
		    if (count($emp) > 0) {
			// controls
			    $controls = "C7.4";
			    $controls .= ",1604CF";
			    $controls .= ",".$comp_tin;
				$controls .= ",".$branch_code;
				$controls .= ",".$return_period;
				$controls .= ",".number_format($grand_gross,2,'.','');
				$controls .= ",".number_format($grand_bonus_nt_prev,2,'.','');
				$controls .= ",".number_format($grand_dmb_nt_prev,2,'.','');
				$controls .= ",".number_format($grand_stat_nt_prev,2,'.','');
				$controls .= ",".number_format($grand_other_comp_nt_prev,2,'.','');
				$controls .= ",".number_format($grand_total_nt_prev,2,'.','');
				$controls .= ",".number_format($grand_basic_taxable_prev,2,'.','');
				$controls .= ",".number_format($grand_bonus_taxable_prev,2,'.','');
				$controls .= ",".number_format($grand_other_comp_taxable_prev,2,'.','');
				$controls .= ",".number_format($grand_total_taxable_prev,2,'.','');
				$controls .= ",".number_format($grand_bonus_nt,2,'.','');
				$controls .= ",".number_format($grand_dmb_nt,2,'.','');
				$controls .= ",".number_format($grand_stat_nt,2,'.','');
				$controls .= ",".number_format($grand_other_comp_nt,2,'.','');
				$controls .= ",".number_format($grand_total_nt,2,'.','');
				$controls .= ",".number_format($grand_basic_taxable,2,'.','');
				$controls .= ",".number_format($grand_bonus_taxable,2,'.','');
				$controls .= ",".number_format($grand_other_comp_taxable,2,'.','');
				$controls .= ",".number_format($grand_total_taxable,2,'.','');
				$controls .= ",".number_format($grand_total_taxable_prev_and_curr,2,'.','');
				$controls .= ",".number_format($grand_exempt,2,'.','');
				$controls .= ",".number_format($grand_health_premium,2,'.','');
				$controls .= ",".number_format($grand_net_taxable,2,'.','');
				$controls .= ",".number_format($grand_taxdue,2,'.','');
				$controls .= ",".number_format($grand_taxwithheld_prev,2,'.','');
				$controls .= ",".number_format($grand_taxwithheld_present,2,'.','');
				$controls .= ",".number_format($grand_taxwithheld_dec,2,'.','');
				$controls .= ",".number_format($grand_over_withheld,2,'.','');
				$controls .= ",".number_format($grand_actual_tax,2,'.','');
				$controls .= "\r\n";
				fwrite($handle, $controls);
		    }
		    
		    // 7.5 Schedule
		    $emp = $this->getEmpMWE($gData);
			// Initialize variables
		    $ctr = 1;
			$grand_gross_compensation_prev = 0;
			$grand_basic_smw_prev = 0;
			$grand_holiday_pay_prev = 0;
			$grand_overtime_pay_prev = 0;
			$grand_night_differential_prev = 0;
			$grand_hazard_pay_prev = 0;
			$grand_nt_other_ben_prev = 0;
			$grand_nt_deminimis_prev = 0;
			$grand_nt_statutories_prev = 0;
			$grand_nt_compensation_prev = 0;
			$grand_total_nt_prev = 0;
			$grand_taxable_other_ben_prev = 0;
			$grand_taxable_compensation_prev = 0;
			$grand_total_taxable_prev = 0;
			$grand_gross = 0;
			$grand_basicSMWPerDay = 0;
			$grand_basicSMWPerMonth = 0;
		    $grand_basicSMWPerYear = 0;
		    $grand_holiday_pay_present = 0;
			$grand_overtime_pay_present = 0;
    		$grand_nd_present = 0;
    		$grand_hazard_pay = 0;
			$grand_bonus_nt = 0;		
			$grand_dmb_nt = 0;
			$grand_stat_nt = 0;
			$grand_other_comp_nt = 0;
			$grand_bonus_taxable = 0;
			$grand_other_comp_taxable = 0;
			$grand_total_compensation_present = 0;
			$grand_total_compensation_prev_and_present = 0;
	    	$grand_exempt = 0;
			$grand_health_premium = 0;
			$grand_net_taxable = 0;
			$grand_taxdue = 0;
	    	$grand_tax_withheld_prev = 0;
			$grand_taxwithheld = 0;
			$grand_taxwithheld_dec = 0;
			$grand_over_withheld = 0;
			$grand_actual_tax = 0;
					

			$comp_tin = str_replace("-","",$company_tin);
			$branch_code = str_pad($gData['branchcode'],4,'0',STR_PAD_LEFT);
			$return_period = date_format($date,'m/d/Y');
			// details
		    if (count($emp) > 0) {
		    	$tax_policy = $this->getTaxPolicy();
				foreach ($emp as $key => $val) {
					$string = "";
					$val['fr_dayperyear'] ? $factor_day_per_year = $val['fr_dayperyear'] : $factor_day_per_year = "";
    				$val['region_code'] ? $region_no_where_assigned = $val['region_code'] :	$region_no_where_assigned = "";
					switch ($val['taxep_code']) {
						case 'ME': $taxep_code = 'M'; break;
						case 'ME1': $taxep_code = 'M1'; break;
						case 'ME2': $taxep_code = 'M2'; break;
						case 'ME3': $taxep_code = 'M3'; break;
						case 'ME4': $taxep_code = 'M4'; break;
						default: $taxep_code = $val['taxep_code']; break;
					}
					// assign variable for easy formatting
					//$count = str_pad($ctr,6,'0',STR_PAD_LEFT);
					$count = $ctr;
					$emp_tin = str_replace("-","",$val['pi_tin']);
					$lastname = strtoupper($val['pi_lname']);
					$firstname = strtoupper($val['pi_fname']);
					$middlename = strtoupper($val['pi_mname']);
					
					// previous
					$gross_compensation_prev = $val['gross_compensation'];
					$grand_gross_compensation_prev += $gross_compensation_prev;
					$gross_compensation_prev = ($gross_compensation_prev == 0 ? 0 : number_format($gross_compensation_prev,2,'.',''));
					
					$basic_smw_prev = $val['basic_smw'];
					$grand_basic_smw_prev += $basic_smw_prev;
					$basic_smw_prev = ($basic_smw_prev == 0 ? 0 : number_format($basic_smw_prev,2,'.',''));
					
					$holiday_pay_prev = $val['holiday_pay'];
					$grand_holiday_pay_prev += $holiday_pay_prev;
					$holiday_pay_prev = ($holiday_pay_prev == 0 ? 0 : number_format($holiday_pay_prev,2,'.',''));
					
					$overtime_pay_prev = $val['overtime_pay'];
					$grand_overtime_pay_prev += $overtime_pay_prev;
					$overtime_pay_prev = ($overtime_pay_prev == 0 ? 0 : number_format($overtime_pay_prev,2,'.',''));
					
					$night_differential_prev = $val['night_differential'];
					$grand_night_differential_prev += $night_differential_prev;
					$night_differential_prev = ($night_differential_prev == 0 ? 0 : number_format($night_differential_prev,2,'.',''));
					
					$hazard_pay_prev = $val['hazard_pay'];
					$grand_hazard_pay_prev += $hazard_pay_prev;
					$hazard_pay_prev = ($hazard_pay_prev == 0 ? 0 : number_format($hazard_pay_prev,2,'.',''));
					
					$nt_other_ben_prev = $val['nt_other_ben'];
					$grand_nt_other_ben_prev += $nt_other_ben_prev;
					$nt_other_ben_prev = ($nt_other_ben_prev == 0 ? 0 : number_format($nt_other_ben_prev,2,'.',''));
					
					$nt_deminimis_prev = $val['nt_deminimis'];
					$grand_nt_deminimis_prev += $nt_deminimis_prev;
					$nt_deminimis_prev = ($nt_deminimis_prev == 0 ? 0 : number_format($nt_deminimis_prev,2,'.',''));
					
					$nt_statutories_prev = $val['nt_statutories'];
					$grand_nt_statutories_prev += $nt_statutories_prev;
					$nt_statutories_prev = ($nt_statutories_prev == 0 ? 0 : number_format($nt_statutories_prev,2,'.',''));
					
					$nt_compensation_prev = $val['nt_compensation'];
					$grand_nt_compensation_prev += $nt_compensation_prev;
					$nt_compensation_prev = ($nt_compensation_prev == 0 ? 0 : number_format($nt_compensation_prev,2,'.',''));
					
					$total_nt_prev = $nt_other_ben_prev+$nt_deminimis_prev+$nt_statutories_prev+$nt_compensation_prev;
					$grand_total_nt_prev += $total_nt_prev;
					$total_nt_prev = ($total_nt_prev == 0 ? 0 : number_format($total_nt_prev,2,'.',''));
					
					$taxable_other_ben_prev = $val['taxable_other_ben'];
					$grand_taxable_other_ben_prev += $taxable_other_ben_prev;
					$taxable_other_ben_prev = ($taxable_other_ben_prev == 0 ? 0 : number_format($taxable_other_ben_prev,2,'.',''));
					
					$taxable_compensation_prev = $val['taxable_compensation'];
					$grand_taxable_compensation_prev += $taxable_compensation_prev;
					$taxable_compensation_prev = ($taxable_compensation_prev == 0 ? 0 : number_format($taxable_compensation_prev,2,'.',''));
					
					$total_taxable_prev = $taxable_other_ben_prev+$taxable_compensation_prev;
					$grand_total_taxable_prev += $total_taxable_prev;
					$total_taxable_prev = ($total_taxable_prev == 0 ? 0 : number_format($total_taxable_prev,2,'.',''));
					
					// present
					$date_from = date_create($val['date_start']);
					$start_date = date_format($date_from,'m/d/Y');
					$date_to = date_create($this->endDateReplace($val['date_resign'], $val['date_retire']));
					$end_date = date_format($date_to,'m/d/Y');
					
					if ($factor_day_per_year) {
		    			$salaryInfoBasicRate = $this->getBasicIncome($val['emp_id'], $gData['year']);
		    			
		    			$basicSMWPerYear = $salaryInfoBasicRate;
		    			$grand_basicSMWPerYear += $basicSMWPerYear;
						$basicSMWPerYear = ($basicSMWPerYear == 0 ? 0 : number_format($basicSMWPerYear,2,'.',''));
					
		    			$basicSMWPerDay = $salaryInfoBasicRate / $factor_day_per_year;
		    			$grand_basicSMWPerDay += $basicSMWPerDay;
						$basicSMWPerDay = ($basicSMWPerDay == 0 ? 0 : number_format($basicSMWPerDay,2,'.',''));
					
		    			$basicSMWPerMonth = $basicSMWPerYear / 12;
		    			$grand_basicSMWPerMonth += $basicSMWPerMonth;
						$basicSMWPerMonth = ($basicSMWPerMonth == 0 ? 0 : number_format($basicSMWPerMonth,2,'.',''));
					}
    				$holiday_pay_present = 0;
    				$grand_holiday_pay_present += $holiday_pay_present;
					$holiday_pay_present = ($holiday_pay_present == 0 ? 0 : number_format($holiday_pay_present,2,'.',''));
    				
					$overtime_pay_present = $this->getOvertime($val['emp_id'], $gData['year']);
					$grand_overtime_pay_present += $overtime_pay_present;
					$overtime_pay_present = ($overtime_pay_present == 0 ? 0 : number_format($overtime_pay_present,2,'.',''));
						
    				$nd_present = 0;
    				$grand_nd_present += $nd_present;
					$nd_present = ($nd_present == 0 ? 0 : number_format($nd_present,2,'.',''));
    				
					$hazard_pay = 0;
    				$grand_hazard_pay += $hazard_pay;
					$hazard_pay = ($hazard_pay == 0 ? 0 : number_format($hazard_pay,2,'.',''));
					if($this->getBonus($val['emp_id'], $gData['year'],0)>$tax_policy['tp_other_benefits']){
						$bonus_nt = $tax_policy['tp_other_benefits'];
						$addtn_bonus_taxable = $this->getBonus($val['emp_id'], $gData['year'],0)-$tax_policy['tp_other_benefits'];
					} else {
						$bonus_nt = $this->getBonus($val['emp_id'], $gData['year']);
						$addtn_bonus_taxable = 0;
					}
					$grand_bonus_nt += $bonus_nt;
					$bonus_nt = ($bonus_nt == 0 ? 0 : number_format($bonus_nt,2,'.',''));
					
					$dmb_nt = $this->getDeminimis($val['emp_id'], $gData['year']);				
					$grand_dmb_nt += $dmb_nt;
					$dmb_nt = ($dmb_nt == 0 ? 0 : number_format($dmb_nt,2,'.',''));
					
					$stat_nt = $this->getStatutoryAndUnionDues($val['emp_id'], $gData['year']);
					$grand_stat_nt += $stat_nt;
					$stat_nt = ($stat_nt == 0 ? 0 : number_format($stat_nt,2,'.',''));
					
					$other_comp_nt = $this->getOtherCompensation($val['emp_id'], $gData['year']);
					$grand_other_comp_nt += $other_comp_nt;
					$other_comp_nt = ($other_comp_nt == 0 ? 0 : number_format($other_comp_nt,2,'.',''));
					
					$bonus_taxable = $this->getBonus($val['emp_id'], $gData['year'],1)+$addtn_bonus_taxable;
					$grand_bonus_taxable += $bonus_taxable;
					$bonus_taxable = ($bonus_taxable == 0 ? 0 : number_format($bonus_taxable,2,'.',''));
					
					$other_comp_taxable = $this->getOtherCompensationTaxable($val['emp_id'], $gData['year'])-$overtime_pay_present;
					$grand_other_comp_taxable += $other_comp_taxable;
					$other_comp_taxable = ($other_comp_taxable == 0 ? 0 : number_format($other_comp_taxable,2,'.',''));

					$gross = $basicSMWPerYear+$holiday_pay_present+$overtime_pay_present+$nd_present+$hazard_pay+$bonus_nt+$dmb_nt+$stat_nt+$other_comp_nt;
					$grand_gross += $gross;
					$gross = ($gross == 0 ? 0 : number_format($gross,2,'.',''));
					
					$total_compensation_present = $gross+$bonus_taxable+$other_comp_taxable;
					$grand_total_compensation_present += $total_compensation_present;
					$total_compensation_present = ($total_compensation_present == 0 ? 0 : number_format($total_compensation_present,2,'.',''));
					
					$total_compensation_prev_and_present = $gross_compensation_prev+$total_taxable_prev+$total_compensation_present;
					$grand_total_compensation_prev_and_present += $total_compensation_prev_and_present;
					$total_compensation_prev_and_present = ($total_compensation_prev_and_present == 0 ? 0 : number_format($total_compensation_prev_and_present,2,'.',''));
					
					$exempt = $this->getExemptAmount($taxep_code);
	    			$grand_exempt += $exempt;
					$exempt = ($exempt == 0 ? 0 : number_format($exempt,2,'.',''));
					
					$health_premium = 0;
					$grand_health_premium += $health_premium;
					
					$net_taxable = ((($total_taxable_prev+$bonus_taxable+$other_comp_taxable)-$exempt) <= 0 ? 0 : number_format((($total_taxable_prev+$bonus_taxable+$other_comp_taxable)-$exempt),2,'.',''));
					$grand_net_taxable += $net_taxable;
					$net_taxable = ($net_taxable == 0 ? 0 : number_format($net_taxable,2,'.',''));
	    			
					$taxgross = $total_taxable_prev+$bonus_taxable+$other_comp_taxable;
					$taxdue = $this->getAnnualTaxDue($val['emp_id'],$gData['year'],$taxep_code,$taxgross);
					$grand_taxdue += $taxdue;
					$taxdue = ($taxdue == 0 ? 0 : number_format($taxdue,2,'.',''));
	    			
	    			$tax_withheld_prev = $val['tax_withheld']; // previous employer tax
	    			$grand_tax_withheld_prev += $tax_withheld_prev;
					$tax_withheld_prev = ($tax_withheld_prev == 0 ? 0 : number_format($tax_withheld_prev,2,'.',''));
					
					$taxwithheld = $this->getTaxWithheld($val['emp_id'], $gData['year']);
					$grand_taxwithheld += $taxwithheld;
					$taxwithheld = ($taxwithheld == 0 ? 0 : number_format($taxwithheld,2,'.',''));
					
					$taxwithheld_dec  = $this->getTaxWithheldDecember($val['emp_id'], $gData['year']);
					$grand_taxwithheld_dec += $taxwithheld_dec;
					$taxwithheld_dec = ($taxwithheld_dec == 0 ? 0 : number_format($taxwithheld_dec,2,'.',''));
					
					$over_withheld = (($tax_withheld_prev+$taxwithheld)+$taxwithheld_dec)-$taxdue;
					$grand_over_withheld += $over_withheld;
					$over_withheld = ($over_withheld == 0 ? 0 : number_format($over_withheld,2,'.',''));
					
					$actual_tax = (($tax_withheld_prev+$taxwithheld)+$taxwithheld_dec)-$over_withheld;
					$grand_actual_tax += $actual_tax;
					$actual_tax = ($actual_tax == 0 ? 0 : number_format($actual_tax,2,'.',''));
					

					$string .= "D7.5";
					$string .= ",1604CF";
					$string .= ",".$comp_tin;
					$string .= ",".$branch_code;
					$string .= ",".$return_period;
					$string .= ",".$count;
					$string .= ",".$emp_tin;
					$string .= ",".$branch_code;
					$string .= ",".$lastname;
					$string .= ",".$firstname; // 10
					$string .= ",".$middlename;
					$string .= ",".$region_no_where_assigned;
					$string .= ",".$gross_compensation_prev;
					$string .= ",".$basic_smw_prev;
					$string .= ",".$holiday_pay_prev;
					$string .= ",".$overtime_pay_prev;
					$string .= ",".$night_differential_prev;
					$string .= ",".$hazard_pay_prev;
					$string .= ",".$nt_other_ben_prev;
					$string .= ",".$nt_deminimis_prev; // 20
					$string .= ",".$nt_statutories_prev;
					$string .= ",".$nt_compensation_prev;
					$string .= ",".$total_nt_prev;
					$string .= ",".$taxable_other_ben_prev;
					$string .= ",".$taxable_compensation_prev;
					$string .= ",".$total_taxable_prev;
					$string .= ",".$start_date;
					$string .= ",".$end_date;
					$string .= ",".$gross;
					$string .= ",".$basicSMWPerDay; // 30
					$string .= ",".$basicSMWPerMonth;
					$string .= ",".$basicSMWPerYear;
					$string .= ",".$factor_day_per_year;
					$string .= ",".$holiday_pay_present;
    				$string .= ",".$overtime_pay_present;
					$string .= ",".$nd_present;
    				$string .= ",".$hazard_pay;
					$string .= ",".$bonus_nt;
					$string .= ",".$dmb_nt;
					$string .= ",".$stat_nt; // 40
					$string .= ",".$other_comp_nt;
					$string .= ",".$bonus_taxable;
					$string .= ",".$other_comp_taxable;
					$string .= ",".$total_compensation_present;
					$string .= ",".$total_compensation_prev_and_present;
					$string .= ",".$taxep_code;
					$string .= ",".$exempt;
					$string .= ",".$health_premium;
					$string .= ",".$net_taxable;
					$string .= ",".$taxdue; // 50
	    			$string .= ",".$tax_withheld_prev;
					$string .= ",".$taxwithheld;
					$string .= ",".$taxwithheld_dec;
					$string .= ",".$over_withheld;
					$string .= ",".$actual_tax;
					$string .= "\r\n";
					// write the details per employee
			     	fwrite($handle, $string);
					$ctr++;
				}
		    } else {
		    	
		    }
		    if (count($emp) > 0) {
				// controls
			    $controls = "C7.5";
			    $controls .= ",1604CF";
			    $controls .= ",".$comp_tin;
				$controls .= ",".$branch_code;
				$controls .= ",".$return_period;
				$controls .= ",".number_format($grand_gross_compensation_prev,2,'.','');
				$controls .= ",".number_format($grand_basic_smw_prev,2,'.','');
				$controls .= ",".number_format($grand_holiday_pay_prev,2,'.','');
				$controls .= ",".number_format($grand_overtime_pay_prev,2,'.','');
				$controls .= ",".number_format($grand_night_differential_prev,2,'.',''); // 10
				$controls .= ",".number_format($grand_hazard_pay_prev,2,'.','');
				$controls .= ",".number_format($grand_nt_other_ben_prev,2,'.','');
				$controls .= ",".number_format($grand_nt_deminimis_prev,2,'.','');
				$controls .= ",".number_format($grand_nt_statutories_prev,2,'.','');
				$controls .= ",".number_format($grand_nt_compensation_prev,2,'.','');
				$controls .= ",".number_format($grand_total_nt_prev,2,'.','');
				$controls .= ",".number_format($grand_taxable_other_ben_prev,2,'.','');
				$controls .= ",".number_format($grand_taxable_compensation_prev,2,'.','');
				$controls .= ",".number_format($grand_total_taxable_prev,2,'.','');
				$controls .= ",".number_format($grand_gross,2,'.',''); // 20
				$controls .= ",".number_format($grand_basicSMWPerDay,2,'.','');
				$controls .= ",".number_format($grand_basicSMWPerMonth,2,'.','');
			    $controls .= ",".number_format($grand_basicSMWPerYear,2,'.','');
			    $controls .= ",".number_format($grand_holiday_pay_present,2,'.','');
				$controls .= ",".number_format($grand_overtime_pay_present,2,'.','');
	    		$controls .= ",".number_format($grand_nd_present,2,'.','');
	    		$controls .= ",".number_format($grand_hazard_pay,2,'.','');
				$controls .= ",".number_format($grand_bonus_nt,2,'.','');		
				$controls .= ",".number_format($grand_dmb_nt,2,'.','');
				$controls .= ",".number_format($grand_stat_nt,2,'.',''); // 30
				$controls .= ",".number_format($grand_other_comp_nt,2,'.','');
				$controls .= ",".number_format($grand_bonus_taxable,2,'.','');
				$controls .= ",".number_format($grand_other_comp_taxable,2,'.','');
				$controls .= ",".number_format($grand_total_compensation_present,2,'.','');
				$controls .= ",".number_format($grand_total_compensation_prev_and_present,2,'.','');
		    	$controls .= ",".number_format($grand_exempt,2,'.','');
				$controls .= ",".number_format($grand_health_premium,2,'.','');
				$controls .= ",".number_format($grand_net_taxable,2,'.','');
				$controls .= ",".number_format($grand_taxdue,2,'.','');
		    	$controls .= ",".number_format($grand_tax_withheld_prev,2,'.',''); // 40
				$controls .= ",".number_format($grand_taxwithheld,2,'.','');
				$controls .= ",".number_format($grand_taxwithheld_dec,2,'.','');
				$controls .= ",".number_format($grand_over_withheld,2,'.','');
				$controls .= ",".number_format($grand_actual_tax,2,'.','');
		    }
			fwrite($handle, $controls);
		    
			fclose($handle);
			// force download the diskette file
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename='.basename($filename));
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($filename));
			readfile($filename);
			unlink($filename);
			exit;
    }
}

?>