<?php
/**
 * Initial Declaration
 */
require_once(SYSCONFIG_CLASS_PATH."util/PHPExcel.php");
require_once(SYSCONFIG_CLASS_PATH."util/PHPExcel/IOFactory.php");

$emptype = array(
    1 => 'Confidential',
    2 => 'Non-Confidential',
    3 => 'In-active Employee'
);
/**
 * Class Module
 *
 * @author  JIM
 *
 */
class cls201MList{

	var $conn;
	var $fieldMap;
	var $Data;

	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return cls201MList object
	 */
	function cls201MList($dbconn_ = null){
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
	 * @note: xls201Report
	 * @param unknown_type $gData
	 */
    function generateXLS201Report($gData = array()){
    	set_time_limit(10000);//set limit
        $filename = "201_Master_List.xls"; // The file name you want any resulting file to be called.
    	// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		$objReader = PHPExcel_IOFactory::createReader('Excel5');
		$objPHPExcel = $objReader->load("templates/201MasterList_Template.xls");
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
		$emp = $this->getEmployee($gData);//Get Employee Record
		$baseRow = 6;
		if(count($emp)>0){
			foreach($emp as $key => $val){
				$row = $baseRow + $key;
//				$objPHPExcel->getActiveSheet()->insertNewRowBefore($row,6);
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $key+1);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $val['emp_idnum']);
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $val['pi_fname']);
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$row, $val['pi_mname']);
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$row, $val['pi_mname_']);
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$row, $val['pi_lname']);
				$objPHPExcel->getActiveSheet()->setCellValue('G'.$row, $val['branchinfo_name']);
				$objPHPExcel->getActiveSheet()->setCellValue('H'.$row, $val['ud_name']);
				$objPHPExcel->getActiveSheet()->setCellValue('I'.$row, $val['post_name']);
				$objPHPExcel->getActiveSheet()->setCellValue('J'.$row, $val['emptype_name']);
				$objPHPExcel->getActiveSheet()->setCellValue('K'.$row, $val['emptype_rank']);
				$objPHPExcel->getActiveSheet()->setCellValue('L'.$row, $val['empclass_name']);
				$objPHPExcel->getActiveSheet()->setCellValue('M'.$row, $val['empcateg_name']);
				$objPHPExcel->getActiveSheet()->setCellValue('N'.$row, $val['emp201status_name']);
				$objPHPExcel->getActiveSheet()->setCellValue('O'.$row, date("m/d/Y", strtotime($val['emp_hiredate'])));
				$objPHPExcel->getActiveSheet()->setCellValue('P'.$row, $val['pi_gender']);
				$objPHPExcel->getActiveSheet()->setCellValue('Q'.$row, date("m/d/Y", strtotime($val['pi_bdate'])));
				$objPHPExcel->getActiveSheet()->setCellValue('R'.$row, $val['pi_place_bdate']);
				$objPHPExcel->getActiveSheet()->setCellValue('S'.$row, $val['pi_civil']);
				$objPHPExcel->getActiveSheet()->setCellValue('T'.$row, $val['pi_religion']);
				$objPHPExcel->getActiveSheet()->setCellValue('U'.$row, $val['pi_nationality']);
				$objPHPExcel->getActiveSheet()->setCellValue('V'.$row, $val['pi_race']);
				$objPHPExcel->getActiveSheet()->setCellValue('W'.$row, $val['pi_height']);
				$objPHPExcel->getActiveSheet()->setCellValue('X'.$row, $val['pi_weight']);
				$objPHPExcel->getActiveSheet()->setCellValue('Y'.$row, $val['pi_bloodtype']);
				$objPHPExcel->getActiveSheet()->setCellValue('Z'.$row, $val['address']);
				$objPHPExcel->getActiveSheet()->setCellValue('AA'.$row, $val['zipcode']);
				$objPHPExcel->getActiveSheet()->setCellValue('AB'.$row, $val['pi_telone']);
				$objPHPExcel->getActiveSheet()->setCellValue('AC'.$row, $val['pi_teltwo']);
				$objPHPExcel->getActiveSheet()->setCellValue('AD'.$row, $val['pi_mobileone']);
				$objPHPExcel->getActiveSheet()->setCellValue('AE'.$row, $val['pi_mobiletwo']);
				$objPHPExcel->getActiveSheet()->setCellValue('AF'.$row, $val['pi_emailone']);
				$objPHPExcel->getActiveSheet()->setCellValue('AG'.$row, $val['pi_emailtwo']);
				$objPHPExcel->getActiveSheet()->setCellValue('AH'.$row, $val['pi_sss']);
				$objPHPExcel->getActiveSheet()->setCellValue('AI'.$row, $val['pi_phic']);
				$objPHPExcel->getActiveSheet()->setCellValue('AJ'.$row, $val['pi_hdmf']);
				$objPHPExcel->getActiveSheet()->setCellValue('AK'.$row, $val['pi_tin']);
				$objPHPExcel->getActiveSheet()->setCellValue('AL'.$row, $val['pi_passport']);
				$objPHPExcel->getActiveSheet()->setCellValue('AM'.$row, $val['taxep_name']);
				$objPHPExcel->getActiveSheet()->setCellValue('AN'.$row, $val['salarytype_name']);
				$objPHPExcel->getActiveSheet()->setCellValue('AO'.$row, $val['pps_name']);
				$objPHPExcel->getActiveSheet()->setCellValue('AP'.$row, $val['salaryinfo_basicrate']);
				$objPHPExcel->getActiveSheet()->setCellValue('AQ'.$row, $val['salaryinfo_ecola']);
				$objPHPExcel->getActiveSheet()->setCellValue('AR'.$row, date("m/d/Y", strtotime($val['salaryinfo_effectdate'])));
				$objPHPExcel->getActiveSheet()->setCellValue('AS'.$row, $val['salaryinfo_ceilingpay']);
				$objPHPExcel->getActiveSheet()->setCellValue('AT'.$row, $val['banklist_name']);
				$objPHPExcel->getActiveSheet()->setCellValue('AU'.$row, $val['baccntype_name']);
				$objPHPExcel->getActiveSheet()->setCellValue('AV'.$row, $val['bankiemp_acct_name']);
				$objPHPExcel->getActiveSheet()->setCellValue('AW'.$row, $val['bankiemp_acct_no']);
			}
//			$objPHPExcel->getActiveSheet()->removeRow($baseRow-1,1);
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
    
    function getEmployee($type = array(), $ud_id_ = null){
        $qry = array();
		$listpgroup = $_SESSION[admin_session_obj][user_paygroup_list2];
		IF(count($listpgroup)>0){
			$qry[] = "pps.pps_id in (".$listpgroup.")";//pay group that can access
		}
    	IF($type['ud_id_'] > 0){
    		$qry[] = "g.ud_id='".$type['ud_id_']."'";
		}
		IF($type['type'] > 0){
			$qry[] = "c.emp_stat ='".$type['type']."'";
		}
		$objClsSSS = new clsSSS($this->conn);
		IF($objClsSSS->getSettings($type['comp'],12) && ($type['branchinfo_id'] != 0 || $type['branchinfo_id'] != "N/A")){
        	$qry[] = "c.branchinfo_id ='".$type['branchinfo_id']."'";
        }
		$qry[] = "c.comp_id = '".$type['comp']."'";
        $qry[] = "j.salaryinfo_isactive = '1'";
        $criteria = (count($qry)>0)?" WHERE ".implode(" AND ",$qry):"";
        $strGroupBy = " group by c.emp_id";
        $qry2 = array();
        if($type['dtype'] > 0){
        	$qry2[] = "g.ud_name";
        }
    	if($type['lname'] > 0){
        	$qry2[] = "e.pi_lname";
        }
        $strOrderBy = (count($qry2)>0)?" order by ".implode(",",$qry2):" order by c.emp_idnum";
        $sql = "SELECT c.emp_idnum,CONCAT(e.pi_lname,', ',e.pi_fname,' ',CONCAT(RPAD(e.pi_mname,1,' '),'.')) as fullname, CONCAT(RPAD(e.pi_mname,1,''),'.') as pi_mname_, k.taxep_code,k.taxep_name,bnk.bankiemp_acct_no,bnk.bankiemp_acct_name,bnkt.baccntype_name,bl.banklist_name, bran.branchinfo_name,
				f.post_name,g.ud_name,type.emptype_name,type.emptype_rank,cf.empclass_name,categ.empcateg_name,stat.emp201status_name,c.emp_hiredate,stype.salarytype_name,ppsinfo.pps_name,j.salaryinfo_basicrate,j.salaryinfo_ecola,j.salaryinfo_effectdate,j.salaryinfo_ceilingpay,e.*,CONCAT(e.pi_add,' ',prov.province_name,' ',ctry.cou_description) as address, zipc.zipcode
                FROM emp_masterfile c
                JOIN emp_personal_info e on (e.pi_id=c.pi_id)
                LEFT JOIN emp_position f on (f.post_id=c.post_id)
                LEFT JOIN app_userdept g on (g.ud_id=c.ud_id)
                LEFT JOIN emp_type type on (type.emptype_id=c.emptype_id)
                LEFT JOIN emp_classification cf on (cf.empclass_id=type.empclass_id) 
                LEFT JOIN emp_category categ on (categ.empcateg_id=c.empcateg_id)
                LEFT JOIN emp_201status stat on (stat.emp201status_id=c.emp_stat)
                LEFT JOIN salary_info j on (j.emp_id=c.emp_id)
                LEFT JOIN salary_type stype on (stype.salarytype_id=j.salarytype_id)
                JOIN payroll_pps_user pps on (c.emp_id = pps.emp_id)
                LEFT JOIN payroll_pay_period_sched ppsinfo on (ppsinfo.pps_id=pps.pps_id)
                LEFT JOIN tax_excep k on (k.taxep_id=c.taxep_id)
                LEFT JOIN app_province prov on (prov.p_id=e.p_id)
                LEFT JOIN app_region regi on (regi.r_id=prov.r_id)
                LEFT JOIN app_country ctry on (ctry.cou_id=regi.cou_id) 
                LEFT JOIN app_zipcodes zipc on (zipc.zipcode_id=e.zipcode_id)
                LEFT JOIN bank_infoemp bnk on (bnk.emp_id=c.emp_id)
                LEFT JOIN bnkaccnt_type bnkt on (bnkt.baccntype_id=bnk.baccntype_id)
                LEFT JOIN bank_list bl on (bl.banklist_id=bnk.banklist_id)
                LEFT JOIN branch_info bran on (bran.branchinfo_id=c.branchinfo_id)
                $criteria
                $strGroupBy
                $strOrderBy";
        $rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF){
			$arrData[] = $rsResult->fields;
            $rsResult->MoveNext();
		}
        Return $arrData;
    }
}
?>