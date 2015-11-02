<?php
/**
 * Initial Declaration
 */
require_once(SYSCONFIG_CLASS_PATH."util/PHPExcel.php");
require_once(SYSCONFIG_CLASS_PATH."util/PHPExcel/IOFactory.php");
require_once(SYSCONFIG_CLASS_PATH."util/export-xls.class.php");
/**
 * Class Module
 *
 * @author  JIM
 *
 */
class clsOTRpt{

	var $conn;
	var $fieldMap;
	var $Data;

	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsOTRpt object
	 */
	function clsOTRpt($dbconn_ = null){
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
				$qry[] = "pps_name like '%$search_field%' || date_format(am.payperiod_start_date,'%Y-%m-%d') like '%$search_field%' || date_format(am.payperiod_end_date,'%Y-%m-%d') like '%$search_field%' || date_format(am.payperiod_trans_date,'%Y-%m-%d') like '%$search_field%'";
			}
		}
		$qry[] = "am.pps_id = '".$_GET['pps_id']."'";
		$qry[] = "am.payperiod_type = '1'";
		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		 "pps_name"=>"pps_name"
		,"paysched"=>"paysched"
		,"payperiod_trans_date"=>"payperiod_trans_date"
		,"viewdata"=>"viewdata"
		);

		if(isset($_GET['sortby'])){
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}else{
			$strOrderBy = " order by am.payperiod_trans_date DESC";
		}
		
		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$viewLink = "";
//		$editLink = "<a href=\"?statpos=otrpt&edit=',am.mnu_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/edit.png\" title=\"Edit\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
		$editLink = "<a href=\"?statpos=otrpt&edit=','".$_GET['pps_id']."','&payperiod_id=',am.payperiod_id,'\"><img src=\"".SYSCONFIG_THEME_URLPATH.SYSCONFIG_THEME."/images/admin/zoom.gif\" title=\"View\" hspace=\"2px\" border=0></a>";
//		$delLink = "<a href=\"?statpos=otrpt&delete=',am.mnu_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\"  border=0 width=\"16\" height=\"16\"></a>";
//		$action = "<a href=\"?statpos=otrpt&action=add\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/add.png\" title=\"Add New\" border=0 width=\"16\" height=\"16\"></a>";

		// SqlAll Query
		$sql = "select am.*,b.pps_name, CONCAT('$viewLink','$editLink','$delLink') as viewdata,
                        CONCAT(date_format(am.payperiod_start_date,'%b %d'),' - ',date_format(am.payperiod_end_date,'%b %d, %Y')) as paysched,
                        date_format(am.payperiod_start_date,'%Y-%m-%d') as payperiod_start_date,
                        date_format(am.payperiod_end_date,'%Y-%m-%d') as payperiod_end_date,
                        date_format(am.payperiod_trans_date,'%b %d, %Y') as payperiod_trans_date
						from payroll_pay_period am
                        inner join payroll_pay_period_sched b on (b.pps_id = am.pps_id)
						$criteria
						$strOrderBy";
		// Field and Table Header Mapping
		$arrFields = array(
		 "pps_name"=>"Pay Period"
		,"paysched"=>"Cut-offs"
		,"payperiod_trans_date"=>"Pay Date"
		,"viewdata"=>"Action"
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
	 * @note: xls OT Report
	 * @param array $gData
	 */
    function generateXLSOTRecordReport($gData = array()){
    	$data = $this->getEmployee($gData['payperiod_id']);
    	// Validation
    	if (empty($data['types'])) {
    		$_SESSION['eMsg'][] = "No record found in OT Record Report.";
    		header("Location: reports.php?statpos=otrpt&pps_id={$_GET['edit']}");
    		exit;
    	}
    	$objClsMngeDecimal = new Application();
		$finalDecFormat = $objClsMngeDecimal->setFinalDecimalPlaces(0);
    	
    	$filename = "overtime_".$data['info']['transDate'].".xls"; //The file name you want any resulting file to be called.
    	$objPHPExcel = new PHPExcel();	
    	$objReader = PHPExcel_IOFactory::createReader('Excel5');
		$sheet = $objPHPExcel->getActiveSheet();
		
		// header
		$sheet->setCellValue("A1", $data['info']['comp_name']);
		$sheet->setCellValue("A2", $data['info']['pps_name']);
		$sheet->setCellValue("A3", "Overtime Summary");
		$sheet->setCellValue("A4", "From: ".$data['info']['startDate']);
		$sheet->setCellValue("A5", "To: ".$data['info']['endDate']);
		
		$rowStart = 6;
		$sheet->setCellValue("A6", "Classification");
		$sheet->mergeCells("A6:A7");
		$sheet->setCellValue("B6", "Employee Name");
		$sheet->mergeCells("B6:B7");
		$sheet->getStyle("A6:A7")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sheet->getStyle("B6:B7")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sheet->getStyle('A6:B7')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		
		foreach($data['types'] as $key => $value){
			$row = $rowStart;
			$sheet->setCellValue($key.$row, $value);
			$origKey = $key;
			$key++;
			$sheet->mergeCells($origKey.$row.":".$key.$row);
			$sheet->getStyle($origKey.$row.":".$key.$row)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$row++;
			$sheet->setCellValue($origKey.$row, "Hours");
			$sheet->getStyle($origKey.$row)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$sheet->setCellValue($key.$row, "Amount");
			$sheet->getStyle($key.$row)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		}
		
		// Start - Total OT Amount
		$for_total = ++$key;
		$sheet->setCellValue($for_total.'6', "Total OT Amount");
		$sheet->mergeCells($for_total."6:".$for_total.'7');
		$sheet->getStyle($for_total.'6')->applyFromArray(array('font' => array('bold' => true)));
		$sheet->getStyle($for_total."6:".$for_total.'7')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);
		$sheet->getStyle($for_total."6:".$for_total.'7')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sheet->getStyle($for_total."6:".$for_total.'7')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sheet->getStyle($for_total."6:".$for_total.'7')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
		$sheet->getStyle($for_total."6:".$for_total.'7')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$sheet->getColumnDimension($for_total)->setWidth(16.01);
		// End - Total OT Amount
		
		//$sheet->getStyle($col.$row)->applyFromArray($styleBold);
		$sheet->getStyle("A".$rowStart.":".$key.$rowStart)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sheet->getStyle("A".$rowStart.":".$key.$rowStart)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);
		$sheet->getStyle("A".$row.":".$key.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
		$sheet->getStyle("A".$rowStart.":".$key.$row)->applyFromArray(array('font' => array('bold' => true)));
		$row++;
		
		/** Start of Content**/
		$array_total = array_keys($data['types']);
		
		for ($count=0;$count<count($array_total);$count++) {
			$adjusted_array_total[$count] = ++$array_total[$count];
		}
		
		for ($count=0;$count<count($data['emp']);$count++) {
			$sheet->setCellValue("A".$row, $data['emp'][$count]['emptype_name']);
			$sheet->setCellValue("B".$row, $data['emp'][$count]['fullname']);
			foreach($data['emp'][$count]['OT'] as $k => $val){
				$sheet->setCellValue($k.$row, $val['otrec_totalhrs']);
				$k++;
				$sheet->setCellValue($k.$row, $val['otrec_subtotal']);
				
				// Start - Total OT Amount
				$imploded_array_total = implode("$row+", $adjusted_array_total);
//				echo $imploded_array_total.$row;exit;
				
				$sheet->setCellValue($for_total.$row, "=SUM(".$imploded_array_total.$row.')');
				// End - Total OT Amount
			}
			$row++;
		}
    	/** End of Content **/
		$colStart = "A";
		while($colStart <= $k){
			$sheet->getColumnDimension($colStart)->setAutoSize(true);
			$colStart++;
		}
		$maxCol = $sheet->getHighestColumn();
		$maxRow = $sheet->getHighestRow();
		
		for($i=8;$i<=$maxRow;$i++){
			for($j="A";$j<=$maxCol;$j++){
				$cellVal = $sheet->getCell($j.$i)->getValue();
				if(empty($cellVal)){
					$sheet->setCellValue($j.$i, '0');
				}
			}
		}
		$sheet->getStyle('A8:' . $maxCol . $maxRow)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
		
		// Start - Grand Total
		$sum = $sheet->getHighestRow() + 2;
		$total_employee_count = $sum + 2;
		$for_grand_total = ++$key;
		for ($letter = "C"; $letter != $for_grand_total; $letter++) {
			$sheet->setCellValue($letter.$sum, '=SUM('.$letter.'8:'.$letter.$maxRow.')');
		}
		$sheet->getStyle('C'.$sum.':'.$for_grand_total.$sum)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
		$sheet->setCellValue('A'.$total_employee_count, 'Total Employee Count: '.count($data['emp']));
		// End - Grand Total
		
		$sheet->setTitle($filename);
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);
		$sheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
		$sheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
		$sheet->getPageSetup()->setFitToPage(true);
		$sheet->getPageSetup()->setFitToWidth(1);
		$sheet->getPageSetup()->setFitToHeight(0);
		// Redirect output to a client's web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename='.$filename);
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
    	//exit;
    }
    
    /**
     * 
     * Get Employees with OT for payperiod
     * @param int $payperiod_id_
     */
    function getEmployee($payperiod_id_ = null){
    	$arrData = array();
    	$sql = "SELECT DISTINCT CONCAT(pi_lname, ', ' ,pi_fname) AS fullname, ot_record.emp_id, emp_masterfile.emp_idnum, emp_type.emptype_name
    			FROM ot_record
				JOIN emp_masterfile ON (emp_masterfile.emp_id = ot_record.emp_id)
				JOIN emp_personal_info ON (emp_personal_info.pi_id = emp_masterfile.pi_id)
				LEFT JOIN emp_type ON (emp_type.emptype_id = emp_masterfile.emptype_id)
				WHERE payperiod_id='{$payperiod_id_}' ORDER BY pi_lname";
    	$result = $this->conn->GetAll($sql);
    	
    	$comSql = "SELECT distinct f.pps_name, h.comp_name, g.branchinfo_name, DATE_FORMAT(e.payperiod_start_date,'%M %d, %Y') as startDate, 
					DATE_FORMAT(e.payperiod_end_date,'%M %d, %Y') as endDate, DATE_FORMAT(e.payperiod_trans_date,'%M_%d_%Y') as transDate 
					FROM ot_record a 
					join emp_masterfile c on (c.emp_id=a.emp_id) 
					join emp_personal_info d on (d.pi_id=c.pi_id) 
					join payroll_pay_period e on (a.payperiod_id=e.payperiod_id) 
					join payroll_pay_period_sched f on (f.pps_id=e.pps_id)
					join company_info h on (h.comp_id=c.comp_id) 
					left join branch_info g on (g.branchinfo_id=c.branchinfo_id) 
					WHERE a.payperiod_id='".$payperiod_id_."'";
    	$comResult = $this->conn->GetAll($comSql);
    	
    	$arrData["info"] = $comResult[0];
    	$arrData["types"] = array();
    	$col = "C";
    	$sql_stmt = "SELECT b.otr_desc,a.otrec_totalhrs,a.otrec_subtotal
    				FROM ot_record a
    				JOIN ot_rates b on (b.otr_id=a.otr_id)
    				WHERE payperiod_id=? AND emp_id=?";
    	$stmt = $this->conn->Prepare($sql_stmt);
    	if(count($result) > 0){
    		for($count=0;$count < count($result); $count++){
    			$arrData["emp"][$count] = $result[$count];
    			$rsResult = $this->conn->Execute($stmt,array($payperiod_id_,$result[$count]['emp_id']));
    			while(!$rsResult->EOF){
    				if(!in_array($rsResult->fields['otr_desc'],$arrData["types"])){
    					$arrData["types"][$col] = $rsResult->fields['otr_desc'];
    					$col++;
    					$col++;
    				}
    				$key = array_search($rsResult->fields['otr_desc'],$arrData["types"]);
    				$arrData["emp"][$count]["OT"][$key] = $rsResult->fields;
    				$rsResult->MoveNext();
    			}
    		}
    	}
    	return $arrData;
    }
    
	function getPayperiodDetails($payperiod = null,$startdate = null, $enddate = null){
		if ($payperiod !="") { $qry[] = "pp.payperiod_id = $payperiod"; }
		if (!is_null($startdate)) { $qry[] = "pp.payperiod_trans_date >= '$startdate'"; }
		if (!is_null($enddate)) { $qry[] = "pp.payperiod_trans_date <= '$enddate'"; }
		$criteria = count($qry)>0 ? " where ".implode(' and ',$qry) : '';
		$sql = "select pp.payperiod_id,pp.payperiod_trans_date,pp.pps_id, pp.payperiod_start_date, pp.payperiod_end_date , pp.payperiod_status_id, pp.pp_stat_id ,
							concat( DATE_FORMAT(pp.payperiod_start_date,'%b %e, %Y'),' - ',date_format(pp.payperiod_end_date,'%b %e, %Y')) as pdate
							from payroll_pay_period pp
							inner join payroll_pay_period_sched ppps on (ppps.pps_id = pp.pps_id)
							$criteria";
		$rsResult = $this->conn->Execute($sql);
		$arrData = array();
		while(!$rsResult->EOF) {
			$arrData[] = $rsResult->fields;
			$rsResult->MoveNext();
		}
		return $arrData;
	}
	
	function getColumns($psa_type){
		$sql = "select distinct psa_clsfication from payroll_ps_account";
		$rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF) {
			$psa_clsfication = $rsResult->fields['psa_clsfication'];
			/** edited by ir salvador**/
			/**$sql2 = "select psa_id, psa_name, psa_type, psa_clsfication from payroll_ps_account 
			where psa_type='$psa_type' and psa_clsfication='$psa_clsfication' and psa_status='1' and psa_id!='52' order by psa_order";**/
			$sql2 = "select psa_id, psa_name, psa_type, psa_clsfication from payroll_ps_account 
			where psa_type='$psa_type' and psa_clsfication='$psa_clsfication' and psa_status='1' order by psa_order";
			$rsResult2 = $this->conn->Execute($sql2);
			while(!$rsResult2->EOF){
				$arr[] = $rsResult2->fields;
				$rsResult2->MoveNext();
			}
			$rsResult->MoveNext();
		}
		return $arr;
	}
}
?>