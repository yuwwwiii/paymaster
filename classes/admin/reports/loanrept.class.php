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
class clsLoanRept{

	var $conn;
	var $fieldMap;
	var $Data;

	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsLoanRept object
	 */
	function clsLoanRept($dbconn_ = null){
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
		$editLink = "<a href=\"?statpos=loanrept&edit=',am.mnu_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/edit.png\" title=\"Edit\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
		$delLink = "<a href=\"?statpos=loanrept&delete=',am.mnu_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\"  border=0 width=\"16\" height=\"16\"></a>";
		$action = "<a href=\"?statpos=loanrept&action=add\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/add.png\" title=\"Add New\" border=0 width=\"16\" height=\"16\"></a>";

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
	
	function generateReport($gData = array()){
		$filename = 'LoanReport_'.date("mdY", time()).'.xls';
		$objPHPExcel = new PHPExcel();
		$objClsMngeDecimal = new Application();
		$finalDecFormat = $objClsMngeDecimal->setFinalDecimalPlaces(0);
		$objReader = PHPExcel_IOFactory::createReader('Excel5');
		$objPHPExcel = $objReader->load("templates/LoanTemplate.xls");
		$company = clsSSS::dbfetchBranchDetails();
		if(clsSSS::getSettings($gData['comp'],12) && $gData['branchinfo_id'] != 0){
	  		$branch_details = $objClsSSS->getLocationInfo($gData['branchinfo_id']);
	  		$compname = $branch_details['branchinfo_name'];
        	$compadds = $branch_details['branchinfo_add'];
        	$compsssno = $branch_details['branchinfo_sss'];
        	$comptinno = $branch_details['branchinfo_tin'];
        	$comptelno = $branch_details['branchinfo_tel1'];
	  	} else {
	  		$branch_details = clsSSS::dbfetchCompDetails($gData['comp']);//get company info
        	$compname = $branch_details['comp_name'];
        	$compadds = $branch_details['comp_add'];
        	$compzip = $branch_details['comp_zipcode'];
        	$compsssno = $branch_details['comp_sss'];
        	$comptinno = $branch_details['comp_tin'];
        	$comptelno = $branch_details['comp_tel'];
	  	}
		$objPHPExcel->getActiveSheet()->setCellValue('A1', $company[1]);
		$title = array(
	    			'font'    => array(
	        				'name'      => 'Arial',
	        				'size'        => 12,
	        				'bold'      => true,
	        				'italic'    => true
	        		)
   	 			);
		$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($title);
		$objSheet = $objPHPExcel->getActiveSheet();
		// added header YTD
		$objSheet->setCellValue("R5", "YTD");
		$borderThin = array(
		    'style' => PHPExcel_Style_Border::BORDER_THIN
		);
		$borderMedium = array(
		    'style' => PHPExcel_Style_Border::BORDER_MEDIUM
		);
		$styleBoldTop = array(
						'fill' => array(
					        'type' => PHPExcel_Style_Fill::FILL_SOLID,
					        'color' => array('rgb'=>'FFFF99'),
					    ),
					    'borders' => array(
					        'bottom' => $borderThin,
					        'top' => $borderMedium,
					        'right' => $borderMedium,
					    ));
		$styleBoldTopMiddle = array(
						'fill' => array(
					        'type' => PHPExcel_Style_Fill::FILL_SOLID,
					        'color' => array('rgb'=>'FFFF99'),
					    ),
					    'borders' => array(
					        'right' => $borderMedium,
					    ));
		$styleBoldBottom = array(
						'font' => array('bold' => true, 'color' => array('rgb'=>'C00000'),), 
						'fill' => array(
					        'type' => PHPExcel_Style_Fill::FILL_SOLID,
					        'color' => array('rgb'=>'FFFF99'),
					    ),
					    'borders' => array(
					        'bottom' => $borderMedium,
					        'left' => $borderThin,
					        'top' => $borderThin,
					        'right' => $borderMedium,
					    ));
					    
		$objSheet->getStyle("R3")->applyFromArray($styleBoldTop);
		$objSheet->getStyle("R4")->applyFromArray($styleBoldTopMiddle);
		$objSheet->getStyle("R5")->applyFromArray($styleBoldBottom);
		
		// format existing template to add YTD Column
		$objSheet->getStyle("Q3")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_NONE);
		$objSheet->getStyle("Q4")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_NONE);
		$objSheet->getStyle("Q5")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_NONE);
		$loanData = $this->getLoanData($gData);
		$row = 6;
		if(count($loanData) > 0){
			for($index=0;$index<count($loanData);$index++){
				$objSheet->setCellValue("A".$row, $loanData[$index]['emp_idnum']);
				$objSheet->getStyle("A".$row)->getNumberFormat()->setFormatCode('#');
				$objSheet->getColumnDimension('A')->setAutoSize(true);
				$objSheet->setCellValue("B".$row, $loanData[$index]['fullname']);
				$objSheet->getColumnDimension('B')->setAutoSize(true);
				$objSheet->setCellValue("C".$row, $loanData[$index]['psa_name']);
				$objSheet->getColumnDimension('C')->setAutoSize(true);
				$objSheet->setCellValue("D".$row, $loanData[$index]['loantype_desc']);
				$objSheet->getColumnDimension('D')->setAutoSize(true);
				$objSheet->setCellValue("E".$row, $loanData[$index]['loan_voucher_no']);
				$objSheet->getColumnDimension('E')->setAutoSize(true);
				$objSheet->setCellValue("F".$row, $loanData[$index]['loan_datepromissory']);
				$objSheet->getColumnDimension('F')->setAutoSize(true);
				$objSheet->setCellValue("G".$row, $loanData[$index]['loan_dategrant']);
				$objSheet->getColumnDimension('G')->setAutoSize(true);
				$objSheet->setCellValue("H".$row, $loanData[$index]['loan_startdate']);
				$objSheet->getColumnDimension('H')->setAutoSize(true);
				$objSheet->setCellValue("I".$row, $loanData[$index]['loan_enddate']);
				$objSheet->getColumnDimension('I')->setAutoSize(true);
				$objSheet->setCellValue("J".$row, $loanData[$index]['loan_numofmonths']);
				$objSheet->getColumnDimension('J')->setAutoSize(true);
				$objSheet->setCellValue("K".$row, $loanData[$index]['loan_principal']);
				$objSheet->getStyle("K".$row)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
				$objSheet->getColumnDimension('K')->setAutoSize(true);
				$objSheet->setCellValue("L".$row, $loanData[$index]['loan_interestperc']);
				$objSheet->getStyle("L".$row)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
				$objSheet->getColumnDimension('L')->setAutoSize(true);
				$objSheet->setCellValue("M".$row, $loanData[$index]['loan_interestamount']);
				$objSheet->getStyle("M".$row)->getNumberFormat()->setFormatCode('#'.$finalDecFormat);
				$objSheet->getColumnDimension('M')->setAutoSize(true);
				$objSheet->setCellValue("N".$row, $loanData[$index]['loan_monthly_amortization']);
				$objSheet->getStyle("N".$row)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
				$objSheet->getColumnDimension('N')->setAutoSize(true);
				$objSheet->setCellValue("O".$row, $this->getLoanFrequency($loanData[$index]['loan_periodselection']));
				$objSheet->getColumnDimension('O')->setAutoSize(true);
				$objSheet->setCellValue("P".$row, $loanData[$index]['loan_payperperiod']);
				$objSheet->getStyle("P".$row)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
				$objSheet->getColumnDimension('P')->setAutoSize(true);
				$objSheet->setCellValue("Q".$row, $loanData[$index]['loan_balance']);
				$objSheet->getStyle("Q".$row)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
				$objSheet->getColumnDimension('Q')->setAutoSize(true);
				$objSheet->setCellValue("R".$row, $loanData[$index]['loan_ytd']);
				$objSheet->getStyle("R".$row)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
				$objSheet->getColumnDimension('R')->setAutoSize(true);
				$row++;
			}
		} else {
			$_SESSION['eMsg'][] = "No Record Found!";
			header("Location: reports.php?statpos=loanrept");
		}
		// Rename sheet
		$objSheet->setTitle($filename);
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);
		// Redirect output to a web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename='.$filename);
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
	}

	function getLoanData($gData = array()){
		$qry = array();
		$order = array();
        if($gData['comp'] != 0){
            $qry[] = "b.comp_id = '".$gData['comp']."'";
        }
	    if($gData['branch'] != 0){
            $qry[] = "b.branchinfo_id = '".$gData['branch']."'";
        }
		if($gData['dept'] != 0){
            $qry[] = "b.ud_id = '".$gData['dept']."'";
        }
		if($gData['status'] != 2){
            $qry[] = "a.loan_suspend = '".$gData['status']."'";
        }
    	if($gData['isdpart'] == '1'){
			$order[] = "f.ud_name";
		}
		if($gData['islname'] == '1'){
			$order[] = "fullname";
		}
		/** update by: ir salvador - remove pay group filter**/
//		$qry[] = "pps.pps_id = ".$type['edit']."";
//      $qry[] = "c.emp_stat in ('1','7')";
        $criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";
        $strOrderBy = (count($order)>0)?" order by ".implode(",",$order):"";
		$sql = "select b.emp_idnum, concat(c.pi_lname,', ',c.pi_fname,' ',left(c.pi_mname,1),'.') as fullname, 
				d.psa_name, e.loantype_desc, a.loan_voucher_no, a.loan_datepromissory, a.loan_dategrant, 
				a.loan_startdate, a.loan_enddate, a.loan_numofmonths, a.loan_principal, a.loan_interestperc,
				a.loan_interestamount, a.loan_monthly_amortization, a.loan_periodselection, 
				a.loan_payperperiod, a.loan_balance, a.loan_ytd
				from loan_info a
				inner join emp_masterfile b on (b.emp_id=a.emp_id)
				inner join emp_personal_info c on (c.pi_id=b.pi_id)
				inner join payroll_ps_account d on (d.psa_id=a.psa_id)
				inner join loan_type e on (e.loantype_id=a.loantype_id)
				inner join app_userdept f on (f.ud_id=b.ud_id)
				$criteria
                $strOrderBy";
        $rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF){
			$arrData[] = $rsResult->fields;
            $rsResult->MoveNext();
		}
        Return $arrData;
	}
	
	function getLoanFrequency($param_){
		switch($param_){
			case "1,0,0,0,0" : $frequency = "Every 15";
			case "0,1,0,0,0" : $frequency = "Every end of month";
			case "1,1,0,0,0" : $frequency = "Every payroll";
		}
		return $frequency;
	}
}

?>