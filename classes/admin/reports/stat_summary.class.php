<?php
require_once(SYSCONFIG_CLASS_PATH."util/PHPExcel.php");
require_once(SYSCONFIG_CLASS_PATH."util/PHPExcel/IOFactory.php");
require_once(SYSCONFIG_CLASS_PATH."util/pdf.class.php");
/**
 * Initial Declaration
 */

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
/**
 * Class Module
 *
 * @author  JIM
 *
 */
class clsStatSummary{

	var $conn;
	var $fieldMap;
	var $Data;

	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsStatSummary object
	 */
	function clsStatSummary($dbconn_ = null){
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
		$editLink = "<a href=\"?statpos=summary&edit=',am.mnu_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/edit.png\" title=\"Edit\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
		$delLink = "<a href=\"?statpos=summary&delete=',am.mnu_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\"  border=0 width=\"16\" height=\"16\"></a>";
		$action = "<a href=\"?statpos=summary&action=add\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/add.png\" title=\"Add New\" border=0 width=\"16\" height=\"16\"></a>";

		// SqlAll Query
		$sql = "select am.*, CONCAT('$viewLink','$editLink','$delLink') as viewdata
						from app_modules am
						$criteria
						$strOrderBy";

		// Field and Table Header Mapping
		$arrFields = array(
		 "viewdata"=>$action
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

	function summaryReport($gData = array()){
		$m = $gData['year'].'-'.$gData['month'];
        $filename = "Stat_Summary_".date('FY',dDate::parseDateTime($m)).".xls"; // The file name you want any resulting file to be called.
    	$compDetails = $this->getCompany($gData['comp']);
        // Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		$objClsMngeDecimal = new Application();
		$finalDecFormat = $objClsMngeDecimal->setFinalDecimalPlaces(0);
		$objReader = PHPExcel_IOFactory::createReader('Excel5');
		$objPHPExcel = $objReader->load("templates/StatutorySummary.xls");
		
		$sheet = $objPHPExcel->getActiveSheet();
		$styleBold = array('font' => array('bold' => true));
		$start_col = 'A';
		$start_row = 1;
		$sheet->setCellValue($start_col.$start_row, $compDetails['comp_name']);
		$start_row++;
		$sheet->setCellValue($start_col.$start_row, $compDetails['comp_add']);
		$start_row++;
		$start_row++;
		$start_row++;
		$sheet->setCellValue($start_col.$start_row, date("F", mktime(0, 0, 0, $gData['month'])).'-'.$gData['year']);
		$recStartRow = 8;
		$recStartCol = 'A';
		$mysqli = Application::mysqli_connect(SYSCONFIG_DBNAME);
		$sql = "CALL statSummary(".$gData['month'].",".$gData['year'].")";
		$arr = array();
		$row = $recStartRow;
		$col = $recStartCol;
		if($mysqli->multi_query($sql)){
		      do{
		         if($result = $mysqli->use_result()){
		            while($r = $result->fetch_row()){
		            $arr[] = $r;
		               foreach($r as $cell){
		               		$sheet->setCellValue($col.$row, $cell);
		               		if($col == 'E' or $col == 'G' or $col == 'I' or $col == 'K'){
		               			$sheet->getStyle($col.$row)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
		               		}
		               		$col++;
		               }
		            }
		            $col = $recStartCol;
		            $endRow = $row;
		            $row++;
		            $result->close();
		         }
		      } while($mysqli->more_results() && $mysqli->next_result());
		   }
		   $row++;
		while($col!='L'){
			if($col == 'A'){
				$sheet->setCellValue($col.$row, "Total: ".count($arr));
			}
			if($col == 'G'){
				$sheet->setCellValue($col.$row, "=sum(".$col.$recStartRow.":".$col.$endRow.")");
				$sheet->getStyle($col.$row)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
			}
			if($col == 'I'){
				$sheet->setCellValue($col.$row, "=sum(".$col.$recStartRow.":".$col.$endRow.")");
				$sheet->getStyle($col.$row)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
			}
			if($col == 'K'){
				$sheet->setCellValue($col.$row, "=sum(".$col.$recStartRow.":".$col.$endRow.")");
				$sheet->getStyle($col.$row)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
			}
			$sheet->getStyle($col.$row)->applyFromArray($styleBold);
			$sheet->getStyle($col.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);
			$sheet->getStyle($col.$row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);
			$col++;
		}
		//echo $row;
		//exit;
		// Rename Sheet
		if(count($arr) > 0){
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
		} else {
			$_SESSION['eMsg'][] = "No Record Found!";
		}
	}
	
	function getCertrification($gData = array()) {
		$orientation='P';
		$unit='mm';
		$format='LEGAL';
		$unicode=true;
		$encoding = "UTF-8";
		
		$oPDF = new clsPDF($orientation, $unit, $format, $unicode, $encoding);
		
		
		
		$oPDF->SetAutoPageBreak(false);
		$oPDF->setPrintFooter(false);
		$oPDF->setPrintHeader(false);
		
		
		$oPDF->Setfont('helvetica', '', 10);
		$coordX=0;
		$coordY=0;
		
		
		
		
		//set initial page
		$oPDF->AddPage();
		//company
		$compDetails = $this->getCompany($gData['comp']);
		
		$emp = $gData['emp'];
		/*$sql = "SELECT CONCAT(pi_lname,', ',pi_fname,' ', RPAD(pi_mname,1,' '),'.') as fullname,
				CONCAT(pi_fname,' ',pi_mname,' ', pi_lname) as employee_name,
				pi_phic
				FROM emp_personal_info
				WHERE pi_id={$emp}";
		$rsResult = $this->conn->Execute($sql); **/
		
		$sql = "SELECT CONCAT(emp_personal_info.pi_lname,', ',emp_personal_info.pi_fname,' ', RPAD(emp_personal_info.pi_mname,1,' '),'.') as fullname,
				CONCAT(emp_personal_info.pi_fname,' ',emp_personal_info.pi_mname,' ', emp_personal_info.pi_lname) as employee_name,
				emp_personal_info.pi_phic, emp_personal_info.pi_sss, emp_personal_info.pi_hdmf, emp_masterfile.emp_hiredate, emp_masterfile.emp_resigndate
				From emp_personal_info 
				join emp_masterfile on emp_personal_info.pi_id=emp_masterfile.pi_id 
				where emp_masterfile.emp_id={$emp}";
		
		$rsResult = $this->conn->Execute($sql);
		
		
		
		
		//$sql1 = "select emp_hiredate, emp_resigndate from emp_masterfile where emp_id={$emp}";
		//$rsResult1 = $this->conn->Execute($sql1); 
		
		$Startyear = $rsResult->fields['emp_hiredate'];
		$Endyear = $rsResult->fields['emp_resigndate'];
		
		if ($gData['mode'] == 03) {
			$mode = "PHIC# ";
			$mode1 = $rsResult->fields['pi_phic'];
			$mode2 = "PhilHealth";
		} else if ($gData['mode'] == 04) {
			$mode = "SSS# ";
			$mode1 = $rsResult->fields['pi_sss'];
			$mode2 = "SSS";
		} else if ($gData['mode'] == 05) {
			$mode = "HDMF# ";
			$mode2 = "Pag-ibig";
			$mode1 = $rsResult->fields['pi_hdmf'];
		}
		//$oPDF->SetfontColor(27, 50, 216);
		$oPDF->SetTextColor(27, 50, 216);
		$oPDF->Setfont('helvetica', 'U', 15);
		$oPDF->Text($coordX+88, $coordY+15, "CERTIFICATION");
		$oPDF->SetTextColor(0,0,0);
		$oPDF->Setfont('helvetica', '', 12);
		$oPDF->Text($coordX+8, $coordY+40, "TO WHOM IT MAY CONCERN :");
		$oPDF->Text($coordX+8, $coordY+45, "This is to certify that ".$compDetails['comp_name']." had deducted and remitted to the PhilHealth");
		$oPDF->Text($coordX+8, $coordY+50, "the premium contributions of ".$rsResult->fields['employee_name']." with ".$mode.$mode1." as follows:");
		
		//input-----
		$Startyear1 = new DateTime($Startyear);
		
		if ($Endyear == "") {
			$Endyear1 = new dateTime();
		} else {
			$Endyear1 = new DateTime($Endyear);
		}
		
		
		$yearstart = $Startyear1->format('Y');
		$yearend = $Endyear1->format('Y');
		
		$startdate = $Startyear1->format('m-Y');
		$enddate = $Endyear1->format('m-Y');
		
		$oPDF->Text($coordX+8, $coordY+65, "YEAR/MONTH");
		$oPDF->Text($coordX+75, $coordY+65, "AMOUNT PAID");
		$oPDF->Text($coordX+130, $coordY+65, "OR/SBR No.");
		$oPDF->Text($coordX+175, $coordY+65, "DATE PAID");
		$oPDF->Text($coordX+70, $coordY+70, "EE");
		$oPDF->Text($coordX+103, $coordY+70, "ER");
		$oPDF->Text($coordX+173.5, $coordY+70, "(mm/dd/yyyy)");
		$oPDF->Setfont('helvetica', 'B', 12);
		//$oPDF->Text($coordX+14, $coordY+70, $yearstart);
		
		$coordY = 70;
		
		//exit;
		$oPDF->Setfont('helvetica', '', 12);
		for ($o=$yearstart;$o<=$yearend;$o++) {
			
			$oPDF->Setfont('helvetica', 'B', 12);
		$oPDF->Text($coordX+14, $coordY, $o);
		$coordY+=5;
			//while($startdate != $enddate) {
			if ($o<$yearend) {
				for ($ctr=$Startyear1->Format('m');$ctr<=12;$ctr++) {
		$startmonth = $Startyear1->format('F');
		$oPDF->Setfont('helvetica', '', 12);
		$oPDF->Text($coordX+12, $coordY, $startmonth);
		$Startyear1->add(new DateInterval('P1M'));
		//$stardate = $Startyear1->add(new DateInterval('P1M'));
		//$startmonth+=1;
		$coordY+=5;
				if ($coordY == 290) {
				$oPDF->addPage();
				$coordY = 30;
			}
			} 
		
		
		} else if ($o == $yearend) {
			for ($ctr=01;$ctr<$Endyear1->Format('m');$ctr++) {
				$startmonth = $Startyear1->format('F');
				$oPDF->Setfont('helvetica', '', 12);
				$oPDF->Text($coordX+12, $coordY, $startmonth);
				$Startyear1->add(new DateInterval('P1M'));
				$coordY+=5;
			}
			
		}
			
		}
			
		
		$oPDF->Setfont('helvetica', '', 12);
		$oPDF->Text($coordX+8, $coordY+5, $mode2." Premium Payment of");
		$oPDF->Text($coordX+8, $coordY+10, $rsResult->fields['employee_name']);
		
		$oPDF->Text($coordX+8, $coordY+20, "The certification is being issued upon request of the above-named employee for whatever legal");
		$oPDF->Text($coordX+8, $coordY+25, "purpose(s) it may serve.");
		$dt = new DateTime();
		$oPDF->Text($coordX+8, $coordY+35, "Issued this ".$dt->format("l jS F Y"));
		
		$oPDF->Text($coordX+8, $coordY+45, "Prepared by:");
		$oPDF->Text($coordX+8, $coordY+50, $gData['prepby']);
		
		
		$oPDF->Text($coordX+8, $coordY+60, "Noted by:");
		$oPDF->Text($coordX+8, $coordY+65, $gData['notedby']);
		
		$oPDF->Text($coordX+8, $coordY+75, "Certified Correct:");
		$oPDF->Text($coordX+8, $coordY+80, $gData['certifiedby']);
		
		
		
		
		
		//get the output
		$output = $oPDF->Output("Certification_".$gData['year']."pdf");
		
		if (!empty($output)) {
			return $output;
			
		}
		return false;
		
	}
	
	function getCompany($comp_id){
		if($comp_id == ""){
			return false;
		}
		$sql = "SELECT comp_name, comp_add FROM company_info WHERE comp_id='$comp_id'";
		$rsResult = $this->conn->Execute($sql);
		if(!$rsResult->EOF){
			return $rsResult->fields;
		}
	}
}

?>