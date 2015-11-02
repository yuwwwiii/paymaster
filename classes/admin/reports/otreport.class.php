<?php
/**
 * Initial Declaration
 */
require_once(SYSCONFIG_CLASS_PATH."util/PHPExcel.php");
require_once(SYSCONFIG_CLASS_PATH."util/PHPExcel/IOFactory.php");

/**
 * Class Module
 *
 * @author  JIM
 *
 */
class clsOTReport{

	var $conn;
	var $fieldMap;
	var $Data;

	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsOTReport object
	 */
	function clsOTReport($dbconn_ = null){
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
	 * Export OT LIST
	 *
	 */
	function generateXLSOTListReport(){
//		set_time_limit(10000);//set limit
        $filename = "OT_Master_List.xls"; // The file name you want any resulting file to be called.
    	// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		$objReader = PHPExcel_IOFactory::createReader('Excel5');
		$objPHPExcel = $objReader->load("templates/OTRatesTemplate.xls");
		$company = clsSSS::dbfetchBranchDetails();
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
		$otlist = $this->getOTList();//Get OT List Record
		$baseRow = 5;
		if(count($otlist)>0){
			foreach($otlist as $key => $val){
				$row = $baseRow + $key;
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $key+1);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $val['otr_name']);
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $val['otr_desc']);
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$row, $val['otr_type']);
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$row, $val['otr_factor']);
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$row, $val['otr_max']);
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
	
	function getOTList(){
        $qry = array();
        $criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";
        $strOrderBy = " order by a.otr_name";
        $sql = "SELECT a.* from ot_rates a
                $criteria
                $strOrderBy";
        $rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF){
			$arrData[] = $rsResult->fields;
            $rsResult->MoveNext();
		}
        Return $arrData;
    }
    
    function getCompanyName(){
    	$sql = "select comp_name from company_info";
    	$rsResult = $this->conn->Execute($sql);
    	if(!$rsResult->EOF){
    		return $rsResult->fields['comp_name'];
    	}
    }
}
?>