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
class clsPopup_LoanPaymentHistory {

	var $conn;
	var $fieldMap;
	var $Data;

	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsPopup_LoanPaymentHistory object
	 */
	function clsPopup_LoanPaymentHistory ($dbconn_ = null) {
		$this->conn =& $dbconn_;
		$this->fieldMap = array(
//		 "mnu_name" => "mnu_name"
//		,"mnu_desc" => "mnu_desc"
//		,"mnu_parent" => "mnu_parent"
//		,"mnu_icon" => "mnu_icon"
//		,"mnu_ord" => "mnu_ord"
//		,"mnu_status" => "mnu_status"
//		,"mnu_link_info" => "mnu_link_info"
		);
	}
	
	/**
	 * Delete Record
	 *
	 * @param string $id_
	 */
	function doDelete ($id_ = "", $loan_id_ = null) {
		$sqlSumDetails = "SELECT loansum_id,paystub_id,loan_id,loansum_payment from loan_detail_sum where loansum_id='".$id_."'";
		$varSumDetails = $this->conn->Execute($sqlSumDetails);
//		printa($varSumDetails->fields);
		if (!$varSumDetails->EOF) {
			$sqlLoanInfo = "SELECT loan_id,loantype_id,psa_id,emp_id,loan_ytd,loan_balance,loan_total from loan_info where loan_id='".$varSumDetails->fields['loan_id']."'";
			$varLoanInfo = $this->conn->Execute($sqlLoanInfo);
//			printa($varLoanInfo->fields);
			$varLoanBalance = $varLoanInfo->fields['loan_balance'] + $varSumDetails->fields['loansum_payment'];
			$varLoanYTD = $varLoanInfo->fields['loan_ytd'] - $varSumDetails->fields['loansum_payment'];
//			echo "=========LOAN DELETE==========<br>";
//			echo $varLoanBalance." Loan Balance <br>";
//			echo $varLoanYTD." Loan YTD";
//			exit;
			$flds = array();
			$flds[] = "loan_balance = '".trim(addslashes($varLoanBalance))."'";
			$flds[] = "loan_ytd = '".trim(addslashes($varLoanYTD))."'";
			$fields = implode(", ",$flds);
			$sqlLoanInfoUPdate = "UPDATE loan_info set $fields WHERE loan_id='".$varSumDetails->fields['loan_id']."'";
			$this->conn->Execute($sqlLoanInfoUPdate);
			
			$sql = "delete from loan_detail_sum where loansum_id='".$id_."'";
			$this->conn->Execute($sql);
			$_SESSION['eMsg']="Successfully Deleted.";
		}
	}

	function getPopup_loanpaymenthistory () {
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
				$qry[] = "(CONCAT_WS(' - ', DATE_FORMAT(LEFT(a.payperiod_start_date,10),'%e %b %Y'),DATE_FORMAT(LEFT(a.payperiod_end_date,10),'%e %b %Y')) LIKE '%$search_field%' || a.payperiod_trans_date LIKE '%$search_field%' || loan_detail_sum.loansum_payment LIKE '%$search_field%')";
			}
		}

		$qry[] = "b.emp_id ='".$_GET['emp_id']."'";
		$qry[] = "d.loantype_id ='".$_GET['loantype_id']."'";
		$qry[] = "d.loan_id ='".$_GET['loan_id']."'";
		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" WHERE ".implode(" AND ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		 "viewdata"=>"viewdata"
		,"pay_period"=>"pay_period"
		,"payperiod_trans_date"=>"payperiod_trans_date"
		,"loansum_payment"=>"loansum_payment"
		);

		if (isset($_GET['sortby'])) {
			$strOrderBy = " ORDER BY ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}
		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$delLink = "<a href=\"?statpos=popuploanpaymenthistory&delete=',c.loansum_id,'&loan_id=',d.loan_id,'&emp_id=',b.emp_id,'&type=',d.loantype_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
		// SqlAll Query
			$sql="SELECT CONCAT('$delLink') as viewdata, CONCAT_WS(' - ', DATE_FORMAT(LEFT(a.payperiod_start_date,10),'%e %b %Y') ,DATE_FORMAT(LEFT(a.payperiod_end_date,10),'%e %b %Y')) AS pay_period ,
				  DATE_FORMAT(LEFT(a.payperiod_trans_date,10),'%e %b %Y') AS payperiod_trans_date, FORMAT(c.loansum_payment,2) as loansum_payment, b.emp_id ,d.loantype_id 
					FROM payroll_pay_period a 
					INNER JOIN payroll_pay_stub b ON (a.payperiod_id = b.payperiod_id) 
					INNER JOIN loan_detail_sum c ON (b.paystub_id = c.paystub_id) 
					INNER JOIN loan_info d ON (c.loan_id = d.loan_id)
					$criteria
					$strOrderBy";

		// Field and Table Header Mapping
		$arrFields = array(
		 "viewdata" => "Action"
		,"pay_period" => "Pay Period"
		,"payperiod_trans_date" => "Pay Date"
		,"loansum_payment" => "Amount Paid"
		);

		// Column (table data) User Defined Attributes
		$arrAttribs = array(
		"viewdata" => "width='30' align='center'",
		"loansum_payment" => "align='RIGHT'"
		);

		// Process the Table List
		$tblDisplayList = new clsTableList($this->conn);
		$tblDisplayList->arrFields = $arrFields;
		$tblDisplayList->paginator->linkPage = "?$queryStr";
		$tblDisplayList->sqlAll = $sql;
		return $tblDisplayList->getTableList($arrAttribs);
	}
	
	/**
	 * @note: xlsLoanDetailsReport
	 * @param unknown_type $gData
	 */
    function generateXLSLoanDetailsReport($gData = array()){
    	set_time_limit(10000);//set limit
        $filename = "Loan_List_Report.xls"; // The file name you want any resulting file to be called.
    	// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		$objReader = PHPExcel_IOFactory::createReader('Excel5');
		$objPHPExcel = $objReader->load("templates/LoanDetails.xls");
		$objClsSSS = new clsSSS($this->conn);
       $emp = $this->getEmployee($gData['emp_id']);//Get Employee Record
        $objPHPExcel->getActiveSheet()->setCellValue('C1', $emp['comp_name']);//display company name
        $objPHPExcel->getActiveSheet()->setCellValue('C2', $emp['fullname']." (".$emp['emp_idnum'].")");//display employee name
        $objPHPExcel->getActiveSheet()->setCellValue('C3', $emp['post_name']);//display Position
       $loanInfo = $this->getLoanInfo($gData);//get Loan Info
        $objPHPExcel->getActiveSheet()->setCellValue('C5', $loanInfo['psa_name']);//display Loan name
        $objPHPExcel->getActiveSheet()->setCellValue('C6', $loanInfo['loantype_desc']);//display Loan type
        
        $loandetails = $this->getLoanDetails($gData);//GET loan Details 
		$baseRow = 10;
		if(count($loandetails)>0){
			foreach($loandetails as $key => $val){
				$row = $baseRow + $key;
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $key+1);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $val['pay_period']);
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $val['payperiod_trans_date']);
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$row, $val['loansum_payment']);
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
    
    function getLoanDetails($gData = array()){
    	$qry = array();
    	$qry[] = "b.emp_id ='".$gData['emp_id']."'";
		$qry[] = "d.loantype_id ='".$gData['loantype_id']."'";
		$qry[] = "d.loan_id ='".$gData['loan_id']."'";
		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" WHERE ".implode(" AND ",$qry):"";
    	$sql = "SELECT CONCAT('$delLink') as viewdata, CONCAT_WS(' - ', DATE_FORMAT(LEFT(a.payperiod_start_date,10),'%e %b %Y') ,DATE_FORMAT(LEFT(a.payperiod_end_date,10),'%e %b %Y')) AS pay_period ,
				  DATE_FORMAT(LEFT(a.payperiod_trans_date,10),'%e %b %Y') AS payperiod_trans_date, FORMAT(c.loansum_payment,2) as loansum_payment, b.emp_id ,d.loantype_id 
					FROM payroll_pay_period a 
					JOIN payroll_pay_stub b ON (a.payperiod_id = b.payperiod_id) 
					JOIN loan_detail_sum c ON (b.paystub_id = c.paystub_id) 
					JOIN loan_info d ON (c.loan_id = d.loan_id)
					$criteria";
		$rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF){
			$arrData[] = $rsResult->fields;
            $rsResult->MoveNext();
		}
        Return $arrData;
    }
    
    function getLoanInfo($gData = array()){
    	$qry = array();
		$qry[] = "a.loan_id ='".$gData['loan_id']."'";
		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" WHERE ".implode(" AND ",$qry):"";
    	$sql = "SELECT a.*,b.psa_name,c.loantype_code,c.loantype_desc 
    			FROM loan_info a 
    			JOIN payroll_ps_account b on (a.psa_id=b.psa_id)
    			JOIN loan_type c on (a.loantype_id=c.loantype_id)
    			$criteria";
		$rsResult = $this->conn->Execute($sql);
    	if(!$rsResult->EOF){
			return $rsResult->fields;
		}
    }
    
	function getEmployee($emp_id_ = null){
        $qry = array();
		$qry[] = "c.emp_id = '".$emp_id_."'";
        $criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";
        $sql = "SELECT c.emp_idnum,CONCAT(e.pi_lname,', ',e.pi_fname,' ',CONCAT(RPAD(e.pi_mname,1,' '),'.')) as fullname, CONCAT(RPAD(e.pi_mname,1,''),'.') as pi_mname_, k.taxep_code,k.taxep_name,bnk.bankiemp_acct_no,bnk.bankiemp_acct_name,bnkt.baccntype_name,bl.banklist_name, bran.branchinfo_name, comp_.comp_name,
				f.post_name,g.ud_name,type.emptype_name,type.emptype_rank,cf.empclass_name,categ.empcateg_name,stat.emp201status_name,c.emp_hiredate,stype.salarytype_name,ppsinfo.pps_name,j.salaryinfo_basicrate,j.salaryinfo_ecola,j.salaryinfo_effectdate,j.salaryinfo_ceilingpay,e.*,CONCAT(e.pi_add,' ',prov.province_name,' ',ctry.cou_description) as address, zipc.zipcode
                from emp_masterfile c
                JOIN emp_personal_info e on (e.pi_id=c.pi_id)
                LEFT JOIN emp_position f on (f.post_id=c.post_id)
                LEFT JOIN app_userdept g on (g.ud_id=c.ud_id)
                LEFT JOIN emp_type type on (type.emptype_id=c.emptype_id)
                LEFT JOIN emp_classification cf on (cf.empclass_id=type.empclass_id) 
                LEFT JOIN emp_category categ on (categ.empcateg_id=c.empcateg_id)
                LEFT JOIN emp_201status stat on (stat.emp201status_id=c.emp_stat)
                LEFT JOIN salary_info j on (j.emp_id=c.emp_id)
                LEFT JOIN salary_type stype on (stype.salarytype_id=j.salarytype_id)
                LEFT JOIN payroll_pps_user pps on (c.emp_id = pps.emp_id)
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
                LEFT JOIN company_info comp_ on (c.comp_id=comp_.comp_id)
                $criteria";
        $rsResult = $this->conn->Execute($sql);
		if(!$rsResult->EOF){
			return $rsResult->fields;
		}
    }
}
?>