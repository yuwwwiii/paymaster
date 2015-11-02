<?php
/**
 * Initial Declaration
 */
require_once(SYSCONFIG_CLASS_PATH."util/PHPExcel.php");
require_once(SYSCONFIG_CLASS_PATH."util/PHPExcel/IOFactory.php");

/**
 * Class Module
 * @author  JIM
 *
 */
class clsBdayList{
	var $conn;
	var $fieldMap;
	var $Data;
	
	/**
	 * Class Constructor
	 * @param object $dbconn_
	 * @return clsBdayList object
	 */
	function clsBdayList($dbconn_ = null){
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
	function dbFetch($type = array()){
		$qry = array();
		$listpgroup = $_SESSION[admin_session_obj][user_paygroup_list2];
		IF(count($listpgroup)>0){
			$qry[] = "pps.pps_id in (".$listpgroup.")";//pay group that can access
		}
        if($type['type'] > 0){
            $qry[] = "c.emp_stat = '".$type['type']."'";
        }
    	if($type['dtype'] == '1'){
    		$qry[] = "g.ud_id='".$ud_id_."'";
			$strOrderBy = " order by g.ud_name,e.pi_lname";
		}else{
			$strOrderBy = " order by month_,daymonth";
		}
		if($type['month']!='0'){
			$qry[] = "MONTH(e.pi_bdate) = '".$type['month']."'";
		}
		$objClsSSS = new clsSSS($this->conn);
		IF($objClsSSS->getSettings($type['comp'],12) && ($type['branchinfo_id'] != 0 || $type['branchinfo_id'] != "N/A")){
        	$qry[] = "c.branchinfo_id ='".$type['branchinfo_id']."'";
        }
		$qry[] = "c.comp_id = '".$type['comp']."'";
        $criteria = (count($qry)>0)?" WHERE ".implode(" AND ",$qry):"";
		$sql = "SELECT c.emp_idnum,CONCAT(e.pi_lname,', ',e.pi_fname,' ',concat(RPAD(e.pi_mname,1,' '),'.')) as fullname,g.ud_name,MONTH(e.pi_bdate) as month_,DATE_FORMAT(e.pi_bdate, '%M') as nmemonth,DAYOFMONTH(e.pi_bdate) as daymonth, DATE_FORMAT(e.pi_bdate, '%d-%M, %W') as pi_bdate
                FROM emp_masterfile c
                JOIN emp_personal_info e on (e.pi_id=c.pi_id)
                LEFT JOIN app_userdept g on (g.ud_id=c.ud_id)
                JOIN payroll_pps_user pps on (c.emp_id = pps.emp_id)
                $criteria
                $strOrderBy";
        $rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF){
			$arrData[] = $rsResult->fields;
            $rsResult->MoveNext();
		}
        Return $arrData;
	}
	
	/**
	 * @note: Generate Birthday List
	 * @param $gData
	 */
	function generateXLSBdayReport($gData = array()){
        $filename = "Birthday_List.xls"; // The file name you want any resulting file to be called.
    	// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		$objReader = PHPExcel_IOFactory::createReader('Excel5');
		$objPHPExcel = $objReader->load("templates/Birthday_Template.xls");
		$objClsSSS = new clsSSS($this->conn);
		if($objClsSSS->getSettings($gData['comp'],12) && $gData['branchinfo_id'] != 0){
	  		$branch_details = $objClsSSS->getLocationInfo($gData['branchinfo_id']);
	  		$compname = $branch_details['branchinfo_name'];
        	$compadds = $branch_details['branchinfo_add'];
        	$compsssno = $branch_details['branchinfo_sss'];
        	$comptinno = $branch_details['branchinfo_tin'];
        	$comptelno = $branch_details['branchinfo_tel1'];
	  	} else {
	  		$branch_details = $objClsSSS->dbfetchCompDetails($gData['comp']);//get company info
        	$compname = $branch_details['comp_name'];
        	$compadds = $branch_details['comp_add'];
        	$compzip = $branch_details['comp_zipcode'];
        	$compsssno = $branch_details['comp_sss'];
        	$comptinno = $branch_details['comp_tin'];
        	$comptelno = $branch_details['comp_tel'];
	  	}
        $objPHPExcel->getActiveSheet()->setCellValue('A2', $compname);//display company name
		$emp = $this->dbFetch($gData);//Get Employee Record
		if($gData['month']=='0'){
        	$objPHPExcel->getActiveSheet()->setCellValue('A3', 'ALL Employee');//display company name
        }else{
        	$objPHPExcel->getActiveSheet()->setCellValue('A3', "For The Month of ".$emp['0']['nmemonth']);//display company name
        }
		$baseRow = 7;
		if(count($emp)>0){
			foreach($emp as $key => $val){
				$row = $baseRow + $key;
				$objPHPExcel->getActiveSheet()->insertNewRowBefore($row,6);
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $key+1);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $val['fullname']);
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $val['pi_bdate']);
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$row, $val['ud_name']);
			}
			$objPHPExcel->getActiveSheet()->removeRow($baseRow-1,1);
		}
		// Rename sheet
		$objPHPExcel->getActiveSheet()->setTitle($filename);
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);
		// Redirect output to a client's web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename='.$filename);
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
	}
}
?>