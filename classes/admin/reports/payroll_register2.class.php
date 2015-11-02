<?php
/**
 * Initial Declaration
 */
require_once(SYSCONFIG_CLASS_PATH."util/export-xls.class.php");

$emptype = array(
    1 => 'Officers',
    2 => 'MTC/Additional',
    3 => 'High School'
);

/**
 * Class Module
 *
 * @author  Jason Mabignay
 *
 */
class clsPayrollRegister{
	var $conn;
	var $fieldMap;
	var $Data;

	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsPayrollRegister object
	 */
	function clsPayrollRegister($dbconn_ = null){
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
		$sql = "select ud_name from app_userdept where ud_id = ?";
		$rsResult = $this->conn->Execute($sql,array($id_));
		if(!$rsResult->EOF){
			return $rsResult->fields['ud_name'];
		}
	}
    
    function dbFetchPaystubPerEmp($emp_id_ = null,$payperiod_id_ = null){
		$qry = array();
		if (!is_null($emp_id_)) {
			$qry[] = "a.emp_id =$emp_id_";
		}
		if (!is_null($payperiod_id_)) {
			$qry[] = "b.payperiod_id ='$payperiod_id_'";
		}
        $qry[] = "a.ppr_isdeleted = 0";
        $qry[] = "a.ppr_status =1";
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";
		$sql = "Select *
					from payroll_paystub_report a
					inner join payroll_pay_period b on (a.payperiod_id=b.payperiod_id)
					$criteria";
		$rsResult = $this->conn->Execute($sql);
		if (!$rsResult->EOF){
            $rsResult->fields['paystubdetails'] = unserialize($rsResult->fields['ppr_paystubdetails']);
			return $rsResult->fields;
		}
	}

    function dbFetchHeadDepartment(){
        $sqlparent = "select ud_id from app_userdept where ud_parent = 0 and ud_id != 1";
        $rsResult = $this->conn->Execute($sqlparent);
		if(!$rsResult->EOF){
			$parent_id = $rsResult->fields['ud_id'];
            $sql = "select * from app_userdept a
                    where a.ud_id != 1 ";
            $rsResult_ = $this->conn->Execute($sql);
            while(!$rsResult_->EOF){
                $arrData[$rsResult_->fields['ud_id']] = $rsResult_->fields;
                $rsResult_->MoveNext();
            }
            return $arrData;
        }
	}

    function genReport($gData = array(), $ud_id_ = null){
        $emp = $this->getEmployee($gData, $ud_id_);
        if(count($emp)>0){
         foreach($emp as $key => $val){
         		$paystub[$key] = $this->dbFetchPaystubPerEmp($val['emp_id'], $gData['payperiod_id']);
//         		printa($paystub[$key]);
         		$emp[$key]['salaryinfo_basicrate'] = $paystub[$key]['paystubdetails']['paystubdetail']['paystubaccount']['earning']['basic'];
	            $emp[$key]['gross_semi'] = $paystub[$key]['paystubdetails']['paystubdetail']['paystubaccount']['earning']['Regulartime'];
	            $emp[$key]['cola'] = $paystub[$key]['paystubdetails']['paystubdetail']['paystubaccount']['earning']['COLA'];
	            //TA
	            $emp[$key]['TA']['ot'] = $paystub[$key]['paystubdetails']['paystubdetail']['paystubaccount']['earning']['OT']['SumAllOTRate'];
	            $emp[$key]['TA']['Absences'] = $this->getSemiMonthlyTUAPerEmployee($val['emp_id'],$gData['payperiod_id'],1);
	            $emp[$key]['TA']['LateUT'] = $this->getSemiMonthlyTUAPerEmployee($val['emp_id'],$gData['payperiod_id'],3) + $this->getSemiMonthlyTUAPerEmployee($val['emp_id'],$gData['payperiod_id'],4);
	            $emp[$key]['TA']['totalLateUT'] = $paystub[$key]['paystubdetails']['paystubdetail']['paystubaccount']['TUA']['TotalLeave'];
	            //start of contribution
	            //government
	            $emp[$key]['cont']['sss'] = $paystub[$key]['paystubdetails']['paystubdetail']['paystubaccount']['deduction']['SSS'];
	            $emp[$key]['cont']['phic'] = $paystub[$key]['paystubdetails']['paystubdetail']['paystubaccount']['deduction']['PhilHealth'];
	            $emp[$key]['cont']['hdmf'] = $paystub[$key]['paystubdetails']['paystubdetail']['paystubaccount']['deduction']['Pag-ibig'];
	            $emp[$key]['cont']['tax'] = $paystub[$key]['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['W/H Tax'];
	            //earnings
	         	$psa_amendment = $paystub[$key]['paystubdetails']['paystubdetail']['paystubaccount']['amendments'][0];
				//for dynamic amendments use psa_id as key, earnings
	         	$amm = $this->getAmendments($gData['payperiod_id'], 1);
	         	for($a=0;$a<count($psa_amendment) ;$a++){
						for($f=0;$f<count($amm);$f++){
							if($psa_amendment[$a]['psa_id']===$amm[$f]['psa_id']){
								$keyID = $psa_amendment[$a]['psa_id'];
								$emp[$key]['earnings'][$keyID] = $psa_amendment[$a]['amendemp_amount'];
							}
						}
	         	}
	         	/*for($a=0;$a<count($psa_amendment) ;$a++){
	            	if ($psa_amendment[$a]['psa_type']==1) {
		                if ($psa_amendment[$a]['psa_id']==24) {
			                $emp[$key]['earnings']['Incentive'] = $psa_amendment[$a]['amendemp_amount'];
		                }else if($psa_amendment[$a]['psa_id']==41) {
		                	$emp[$key]['earnings']['otadj'] = $psa_amendment[$a]['amendemp_amount'];
		                }else if($psa_amendment[$a]['psa_id']==43){
		                	$emp[$key]['earnings']['SSSPremadj'] = $psa_amendment[$a]['amendemp_amount'];
		                }else if($psa_amendment[$a]['psa_id']==47){
		                	$emp[$key]['earnings']['HDMFPremadj'] = $psa_amendment[$a]['amendemp_amount'];
		                }else if($psa_amendment[$a]['psa_id']==45){
		                	$emp[$key]['earnings']['PHPremAdj'] = $psa_amendment[$a]['amendemp_amount'];
		                }else if($psa_amendment[$a]['psa_id']==42){
		                	$emp[$key]['earnings']['SLConvertion'] = $psa_amendment[$a]['amendemp_amount'];
		                }else if($psa_amendment[$a]['psa_id']==3){
		                	$emp[$key]['earnings']['MgtParticipation'] = $psa_amendment[$a]['amendemp_amount'];
		                }else if($psa_amendment[$a]['psa_id']==38){
		                	$emp[$key]['earnings']['UniAllow'] = $psa_amendment[$a]['amendemp_amount'];
		                }else if($psa_amendment[$a]['psa_id']==19){
		                	$emp[$key]['earnings']['SalaryAdj'] = $psa_amendment[$a]['amendemp_amount'];
		                }else if($psa_amendment[$a]['psa_id']==49){
		                	$emp[$key]['earnings']['ThirteenthMonth'] = $psa_amendment[$a]['amendemp_amount'];
		                }else if($psa_amendment[$a]['psa_id']==50){
		                	$emp[$key]['earnings']['TaxRefund'] = $psa_amendment[$a]['amendemp_amount'];
		                }else if($psa_amendment[$a]['psa_id']==40){
		                	$emp[$key]['earnings']['EDO'] = $psa_amendment[$a]['amendemp_amount'];
		                }
	            	}
	            }*/
	            //benefits
	         	$psa_benifits = $paystub[$key]['paystubdetails']['paystubdetail']['paystubaccount']['benefits'];            
	            if(count($psa_benifits)>0){
	                for($v=0;$v<count($psa_benifits);$v++){
	                	if ($psa_benifits[$v]['psa_type']==1) {
	                		if ($psa_benifits[$v]['psa_id']==36){
	                			$emp[$key]['earnings']['comallow'] = $psa_benifits[$v]['ben_payperday'];
	                		}
	                	}
	                }
	            }
				$totalearnings = ($emp[$key]['gross_semi'] + $emp[$key]['cola'] + $emp[$key]['TA']['ot'])
								-($emp[$key]['TA']['Absences'] + $emp[$key]['TA']['LateUT']);
         		if(!empty($emp[$key]['earnings'])){
         			foreach($emp[$key]['earnings'] as $valEarnings => $earn){
         				$totalearnings += $earn;
         			}
         		}
				$gross = ($emp[$key]['gross_semi']+$emp[$key]['cola'])
	                    -($emp[$key]['cont']['sss']+$emp[$key]['cont']['phic']+$emp[$key]['cont']['hdmf']+$emp[$key]['cont']['tax']);
	            //deductions
	         	$psa_amendment = $paystub[$key]['paystubdetails']['paystubdetail']['paystubaccount']['amendments'][0];
		        $amm2 = $this->getColumns(2);
			    for($a=0;$a<count($psa_amendment) ;$a++){
					for($f=0;$f<count($amm2);$f++){
						if($psa_amendment[$a]['psa_id']==$amm2[$f]['psa_id']){
							$keyID = $psa_amendment[$a]['psa_id'];
							if($keyID == 51){
								$emp[$key]['deductions']['TaxPayables'] = $psa_amendment[$a]['amendemp_amount'];
							} else {
								$emp[$key]['deductions'][$keyID] = $psa_amendment[$a]['amendemp_amount'];
							}
							
						}
					}
			    }
			   
	           /* for($a=0;$a<count($psa_amendment) ;$a++){
	            	if ($psa_amendment[$a]['psa_type']==2) {
		                if ($psa_amendment[$a]['psa_id']==18) {
			                $emp[$key]['deduction']['overpay'] = $psa_amendment[$a]['amendemp_amount'];
		                }else if($psa_amendment[$a]['psa_id']==44){
		                	$emp[$key]['deduction']['SSSPremadj'] = $psa_amendment[$a]['amendemp_amount'];
		                }else if($psa_amendment[$a]['psa_id']==46){
		                	$emp[$key]['deduction']['PHPremAdj'] = $psa_amendment[$a]['amendemp_amount'];
		                }else if($psa_amendment[$a]['psa_id']==48){
		                	$emp[$key]['deduction']['HDMFPremadj'] = $psa_amendment[$a]['amendemp_amount'];
		                }else if($psa_amendment[$a]['psa_id']==51){
		                	$emp[$key]['deduction']['TaxPayables'] = $psa_amendment[$a]['amendemp_amount'];
		                }
	            	}
	            }*/
	         	$loan_info_ = $paystub[$key]['paystubdetails']['paystubdetail']['paystubaccount']['government_regular'];
	         	for($b=0;$b<count($loan_info_);$b++){
					for($f=0;$f<count($amm2);$f++){
						if($loan_info_[$b]['psa_id']==$amm2[$f]['psa_id']){
							$keyID = $loan_info_[$b]['psa_id'];
							$emp[$key]['deductions'][$keyID] = $loan_info_[$b]['loan_payperperiod'];
						}
					}
			    }
	         	/*for($b=0;$b<count($loan_info_);$b++){
					if ($loan_info_[$b]['psa_id']==11) {
						$emp[$key]['loan']['cashadv'] = $loan_info_[$b]['loan_payperperiod'];
					}else if($loan_info_[$b]['psa_id']==13){
						$emp[$key]['loan']['hdmf'] = $loan_info_[$b]['loan_payperperiod'];
					}else if($loan_info_[$b]['psa_id']==9){
						$emp[$key]['loan']['sss'] = $loan_info_[$b]['loan_payperperiod'];
					}
	            }*/
	            $totaldeduction = 0;
	          	//$totaldeduction = ($emp[$key]['cont']['sss']+$emp[$key]['cont']['phic']+$emp[$key]['cont']['hdmf']+$emp[$key]['cont']['tax'] + $emp[$key]['deduction']['overpay'] + $emp[$key]['loan']['cashadv'] + $emp[$key]['loan']['hdmf'] + $emp[$key]['loan']['sss'] + $emp[$key]['deduction']['SSSPremadj'] + $emp[$key]['deduction']['PHPremAdj'] + $emp[$key]['deduction']['HDMFPremadj']);
         		if(!empty($emp[$key]['cont'])){
         			foreach($emp[$key]['cont'] as $valCont => $cont){
         				$totaldeduction += $cont;
         			}
         		}
         		if(!empty($emp[$key]['deductions'])){
         			foreach($emp[$key]['deductions'] as $valDed => $ded){
         				$totaldeduction += $ded;
         			}
         		}
	            //totals
	            $emp[$key]['totalearning'] = $totalearnings;
	            $emp[$key]['totaldeduction'] = $totaldeduction;
	            //net
//	            $emp[$key]['net'] = $emp[$key]['totalearning'] - $emp[$key]['totaldeduction']; @note: modify jim 20120502: to correct netpay in excel.
	            $emp[$key]['net'] = $paystub[$key]['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['Net Pay'];
         	}
         }
        return $emp;
    }

    function getSubstitution($emp_id = "", $payperiod_id=""){
        if($emp_id == ""){ return 0.00; }
        if($payperiod_id == ""){ return 0.00; }
        $sql = "select b.ppe_amount,d.psa_name,hem.emp_id
                from payroll_pay_stub a
                inner join payroll_paystub_entry b on (a.paystub_id = b.paystub_id)
                inner join payroll_paystub_report c on (a.paystub_id = c.paystub_id)
                inner join payroll_ps_account d on (d.psa_id = b.psa_id)
                inner join payroll_pay_period e on (e.payperiod_id = a.payperiod_id)
                inner join emp_masterfile hem on (a.emp_id = hem.emp_id)
                where e.pp_stat_id = 3 and c.ppr_status =1 and c.ppr_isdeleted =0 and a.emp_id = $emp_id
                and b.psa_id =39 and hem.emp_status=1 and a.payperiod_id = $payperiod_id 
                group by a.emp_id";
        $rsResult = $this->conn->Execute($sql);
		if(!$rsResult->EOF){
             return $rsResult->fields['ppe_amount'];
		}else{
             return '0.00';
        }
    }

    function getOtPerEmp($emp_id_ = "", $payperiod_id = ""){
        if($emp_id_ == ""){ return '0.00'; }
        if($payperiod_id == ""){ return '0.00'; }
        $sql = "select b.*
	            from payroll_pay_stub a
	            inner join payroll_paystub_entry b on (a.paystub_id = b.paystub_id)
	            inner join payroll_paystub_report c on (a.paystub_id = c.paystub_id)
	            where c.ppr_status = 1 and a.payperiod_id = $payperiod_id and a.emp_id = $emp_id_ and b.psa_id in (
	                    select a.psa_id
	                    from payroll_ps_account a
	                    inner join azt_hris_db.hris_policy_ot b on (a.psa_id = b.psa_id)
	                    where a.psa_type = 1)
	            and c.ppr_isdeleted = 0";
        $rsResult = $this->conn->Execute($sql);
		if(!$rsResult->EOF){
             return $rsResult->fields['ppe_amount'];
		}else{
             return '0.00';
        }
    }

    function getTranspoBenefitsPerEmp($emp_id_ = ""){
        if($emp_id_ == ""){ return '0.00'; }
        $sql = "select  amount
                from azt_hris_db.hris_emp_master_ben_rel
                where emp_id = $emp_id_ and ben_id = 1";
        $rsResult = $this->conn->Execute($sql);
		if(!$rsResult->EOF){
            if(!empty ($rsResult->fields['amount'])){
                return $rsResult->fields['amount']/2;
            }else{
                 return '0.00';
            }
		}else{
            return '0.00';
        }
    }

    function getSemiMonthlyOtherDeductionsPerEmp($emp_id_ = "", $payperiod_id_ = "", $psa_type_ =""){
        if($emp_id_ == ""){ return '0.00'; }
        if($payperiod_id_ == ""){ return '0.00'; }
        //fix me: static paystub sccounts
        //since pede p magdagdag ng client ng unlimited ps accounts
        //finilter n ko n lng bases sa existing
        //reference payroll_ps_account table
        if($psa_type_ == 1){
            $not_in = "(1,23,24,6,35,3,39)";
        }else{
            $not_in = "(28,27,29,12,15,16,17,18,33)";
        }

        $sql = "select sum(b.ppe_amount) as ppe_amount from payroll_pay_stub a
                inner join payroll_paystub_entry b on (a.paystub_id = b.paystub_id)
                inner join payroll_paystub_report c on (a.paystub_id = c.paystub_id) 
                where c.ppr_status = 1 and a.payperiod_id = $payperiod_id_ and a.emp_id = $emp_id_ and b.psa_id in(
                    select psa_id
                    from payroll_ps_account
                    where psa_type = $psa_type_ and psa_id not in $not_in)
                and c.ppr_isdeleted = 0 group by a.emp_id";

        $rsResult = $this->conn->Execute($sql);
        if(!$rsResult->EOF){
            return $rsResult->fields['ppe_amount'];
        }else{
            return '0.00';
        }
    }

    function getProvidentPerEmp($emp_id_ = "", $payperiod_id_ = ""){
        if($emp_id_ == ""){ return '0.00'; }
        if($payperiod_id_ == ""){ return '0.00'; }
        $sql = "select sum(b.pd_amount) as pd_amount
				from payroll_provident_header a
				inner join payroll_provident_detail b on (a.ph_id = b.ph_id)
				inner join payroll_provident_loan_type c on (a.plt_id = c.plt_id)
                inner join payroll_pay_stub d on (d.paystub_id = b.paystub_id)
                inner join payroll_paystub_report e on (d.paystub_id = e.paystub_id)
				where d.payperiod_id = $payperiod_id_ and a.emp_id = $emp_id_ and e.ppr_isdeleted = 0 and e.ppr_status =1
                group by a.emp_id";
		$rsResult = $this->conn->Execute($sql);
		if(!$rsResult->EOF){
            return  $rsResult->fields['pd_amount'];
		}else{
            return '0.00';
        }
    }
    
    function getSemiMonthlyLoanPayment($emp_id_ = "", $rlt_id_ = "", $payperiod_id_ = ""){
        if($emp_id_ == ""){ return '0.00'; }
        if($rlt_id_ == ""){ return '0.00'; }
        if($payperiod_id_ == ""){ return '0.00'; }
        $sql = "select sum(b.rd_amount) as rd_amount
				from payroll_regularloan_header a
                inner join payroll_regularloan_detail b on (a.rh_id = b.rh_id)
				inner join payroll_regular_loan_type c on (c.rlt_id = a.rlt_id)
                inner join payroll_pay_stub d on (d.paystub_id = b.paystub_id)
                inner join payroll_paystub_report e on (d.paystub_id = e.paystub_id)
				where a.emp_id =$emp_id_ and a.rlt_id = $rlt_id_ and d.payperiod_id = $payperiod_id_ and e.ppr_isdeleted = 0 and e.ppr_status =1
                group by a.rh_id";
		$rsResult = $this->conn->Execute($sql);
		if(!$rsResult->EOF){
            return $rsResult->fields['rd_amount'];
		}else{
            return '0.00';
        }
    }

    function getSemiMonthlyDeductionPerEmployee($emp_id_ = "", $payperiod_id = "",$psa_id_ =""){
        if($emp_id_ == ""){return '0.00';}
        if($payperiod_id == ""){return '0.00';}
        if($psa_id_ == ""){return '0.00';}
        $sql = "select b.*
            from payroll_pay_stub a
            inner join payroll_paystub_entry b on (a.paystub_id = b.paystub_id)
            inner join payroll_paystub_report c on (a.paystub_id = c.paystub_id)
            where c.ppr_status = 1 and a.payperiod_id = $payperiod_id and a.emp_id = $emp_id_ and b.psa_id = $psa_id_ 
            and c.ppr_isdeleted = 0";
        $rsResult = $this->conn->Execute($sql);
		if(!$rsResult->EOF){
             return $rsResult->fields['ppe_amount'];
		}else{
             return '0.00';
        }
    }
	
    /**
     * @author jim
     * @param $emp_id_
     * @param $payperiod_id
     * @param $psa_id_
     */
	function getSemiMonthlyTUAPerEmployee($emp_id_ = "", $payperiod_id_ = "",$tatbl_id_ =""){
        if($emp_id_ == ""){return '0.00';}
        if($payperiod_id_ == ""){return '0.00';}
        if($tatbl_id_ == ""){return '0.00';}
        $sql = "select b.*
            from payroll_pay_stub a
            inner join ta_emp_rec b on (a.paystub_id = b.paystub_id)
            where a.payperiod_id = '".$payperiod_id_."' and a.emp_id = '".$emp_id_."' and b.tatbl_id = '".$tatbl_id_."'";
        $rsResult = $this->conn->Execute($sql);
		if(!$rsResult->EOF){
             $psa_tua =  $rsResult->fields['emp_tarec_amtperrate'];
		}
        return $psa_tua;
    }
    
/*    function getSemiMonthlyTUAPerEmployee($emp_id_ = "", $payperiod_id = "",$psa_id_ =""){
        if($emp_id_ == ""){return '0.00';}
        if($payperiod_id == ""){return '0.00';}
        if($psa_id_ == ""){return '0.00';}
        $sql = "select b.*
            from payroll_pay_stub a
            inner join payroll_paystub_entry b on (a.paystub_id = b.paystub_id)
            inner join payroll_paystub_report c on (a.paystub_id = c.paystub_id)
            where c.ppr_status = 1 and a.payperiod_id = $payperiod_id and a.emp_id = $emp_id_ and b.psa_id = $psa_id_
            and c.ppr_isdeleted = 0";
        $rsResult = $this->conn->Execute($sql);
		if(!$rsResult->EOF){
             $psa_tua =  $rsResult->fields['ppe_amount'];
		}
        return $psa_tua;
    }*/

   
    function getAdvisoryPerEmpoyee($emp_id_ = ""){
        if($emp_id_ == ""){ return '0.00'; }
        $sql = "select sum(amount) as  amount
                from azt_hris_db.hris_emp_master_ben_rel
                where emp_id = $emp_id_ and ben_id != 1";
        $rsResult = $this->conn->Execute($sql);
		if(!$rsResult->EOF){
            if(!empty ($rsResult->fields['amount'])){
                return $rsResult->fields['amount']/2;
            }else{
				return '0.00';
            }
		}else{
            return '0.00';
        }
    }

    function getEmployee($type = array(), $ud_id_ = null){
        $qry = array();
        if($type['type'] == 1){
            $qry[] = "c.emp_isconfidentail = 1";
        }elseif($type['type'] == 2){
        	$qry[] = "c.emp_isconfidentail = 0";
        }
    	if($type['sbydpart'] == '1'){
    		$qry[] = "g.ud_id='".$ud_id_."'";
			$strOrderBy = " order by g.ud_name,e.pi_lname";
		}else{
			$strOrderBy = " order by e.pi_lname";
		}
		$qry[] = "preport.payperiod_id = ".$type['payperiod_id']."";
		$qry[] = "pps.pps_id = ".$type['edit']."";
//        $qry[] = "c.emp_stat in ('1','7')";
        $qry[] = "j.salaryinfo_isactive='1'";
        $criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";
        $sql = "select c.emp_id, c.emp_idnum, CONCAT(e.pi_lname,', ',e.pi_fname,' ',concat(RPAD(e.pi_mname,1,' '),'.')) as fullname, 
				c.taxep_id,k.taxep_code,k.taxep_name, f.post_name, g.ud_id,g.ud_name, j.salaryinfo_id, j.salaryinfo_basicrate
                from emp_masterfile c
                inner join emp_personal_info e on (e.pi_id=c.pi_id)
                inner join emp_position f on (f.post_id=c.post_id)
                inner join app_userdept g on (g.ud_id=c.ud_id)
                inner join salary_info j on (j.emp_id=c.emp_id)
                inner join payroll_pps_user pps on (c.emp_id = pps.emp_id)
                inner join tax_excep k on (k.taxep_id=c.taxep_id)
                inner join payroll_paystub_report preport on (preport.emp_id=c.emp_id)
                $criteria
                $strOrderBy";
        $rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF){
			$arrData[] = $rsResult->fields;
            $rsResult->MoveNext();
		}
        Return $arrData;
    }

    function getDeptChildrenperHeadDept($dbconn_ = null,$arrMenu_ = array(), $isChild_ = false, $level = 0){
//        printa($arrMenu_);
        if(count($arrMenu_) > 0){
			$arrCtr = 0;
			foreach ($arrMenu_ as $key => $value) {
				$sql = "select a.* from app_userdept a where a.ud_parent=? order by a.ud_name";
				$rsResult = $dbconn_->Execute($sql,array($value['ud_id']));
				$arrMenuIn = array();
				while(!$rsResult->EOF){
					$arrMenuIn[] = $rsResult->fields;
					$rsResult->MoveNext();
				}
				if(count($arrMenuIn) > 0){
                    $mnuData .= clsPayrollRegister::getDeptChildrenperHeadDept($dbconn_, $arrMenuIn, true, $level+1);
                }
                if($level == 0){
                    $mnuData .= $value['ud_id'];
                }else{
                    $mnuData .= $value['ud_id'].",";
                }
			}
		}
        return $mnuData;
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
		$sql = "insert into app_modules set $fields";
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
		$sql = "update app_modules set $fields where mnu_id=$id";
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
//        $qry[] = "am.pp_stat_id = 3";
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
		$editLink = "<a href=\"?statpos=payroll_register&edit=','".$_GET['pps_id']."','&payperiod_id=',am.payperiod_id,'\"><img src=\"".SYSCONFIG_THEME_URLPATH.SYSCONFIG_THEME."/images/admin/zoom.gif\" title=\"View\" hspace=\"2px\" border=0></a>";
//		$delLink = "<a href=\"?statpos=payroll_register&delete=',am.mnu_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_THEME_URLPATH.SYSCONFIG_THEME."/images/admin/trash.gif\" title=\"Delete\" hspace=\"2px\"  border=0></a>";
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
	 * @note: xlspayrollregisterreport
	 * @param unknown_type $gData
	 */
    function generateXLSPayrollRegisterReport($gData = array()){
        $filename = "payroll_register_".date('Y-m-d').".xls"; // The file name you want any resulting file to be called.
        $objClsSSS = new clsSSS($this->conn);
        $oDataBranch = $objClsSSS->dbfetchCompDetails();
        #create an instance of the class
        $xls = new ExportXLS($filename);

        $xls->addHeader($oDataBranch['comp_name']);
        $xls->addHeader($oDataBranch['comp_add']);
        $header = "Payroll Register"; // single first col text
        $xls->addHeader($header);

        if($gData['type'] == 1){
            $empdept_type = "Confidential";
        }else if($gData['type'] == 2){
            $empdept_type = "Non-Confidential";
        }else{
        	$empdept_type = "All Employee";
        }
        $xls->addHeader($empdept_type);
        
//        $objClsTimekeeping = new clsTimekeeping($this->conn);
        $payperiodstatus = $this->getPayperiodDetails($gData['payperiod_id'],null,null);
        $header0 = "for the period: ".date('F d, Y',dDate::parseDateTime($payperiodstatus[0]['payperiod_trans_date']));

        //get earnings and filter if amendments then check if 0 total
		$earn = $this->getColumns(1);
		$earningsPSA = $earn;
		$amendments = $this->getAmendments($gData['payperiod_id'],1);
		for($eCount=0;$eCount<count($amendments);$eCount++) {
			$earningsPSA = $this->removeElementWithValue($earningsPSA, 'psa_id', $amendments[$eCount]['psa_id']);
		}
		
		//check non-amendments if zero total
		for($psaCount=0;$psaCount<count($earningsPSA);$psaCount++){
			//type 1 => psa, type 2 => amendment
			if($this->checkEarningColumnIfZero($earningsPSA[$psaCount]['psa_id'], '1', $_GET, $payperiodstatus[0]['payperiod_trans_date'])){
				$earnings[] = $earningsPSA[$psaCount];
			} else {
				continue;
			}
		}
		
		//check amendments if zero total
		for($amCount=0;$amCount<count($amendments);$amCount++){
			//type 1 => psa, type 2 => amendment
			if($this->checkEarningColumnIfZero($amendments[$amCount]['psa_id'], '1', $_GET, $payperiodstatus[0]['payperiod_trans_date'])){
				$earnings[] = $amendments[$amCount];
			} elseif($this->checkEarningColumnIfZero($amendments[$amCount]['psa_id'], '2', $_GET, $payperiodstatus[0]['payperiod_trans_date'])){
				$earnings[] = $amendments[$amCount];
			} else {
				continue;
			}
		}
		if(count($earnings) > 0){
			$earnings = $this->sortByPsaOrder($earnings);
		}
		//get deductions and filter if loan
		$deduct = $this->getColumns(2);
		for($dCount=0;$dCount<count($deduct);$dCount++){
			if($this->checkDeductionColumnIfZero($deduct[$dCount]['psa_id'], $payperiodstatus[0]['payperiod_trans_date'], $_GET)){
				$deductions[] = $deduct[$dCount];
			} else {
				continue;
			}
		}
        $xls->addHeader($header0);
        
        $header = null;
        $xls->addHeader($header);
        $header1[] = "";
        $header1[] = "";
        $header1[] = "";
        $header1[] = "";
        $header1[] = "Earnings";
        $header1[] = "";
        $header1[] = "";
        $header1[] = "";
        $header1[] = "";
        $header1[] = "";
        for($eCount=0;$eCount<count($earnings);$eCount++){
        	$header1[] = ""; 
        }
        $header1[] = "";
        $header1[] = "Deductions";
    	for($dCount=0;$dCount<count($deductions);$dCount++) {
			$header1[] = "";
		}
        $header1[] = "Salary to Pay";
        $xls->addHeader($header1);
        
        $header2[] = "";
        $header2[] = "Emp ID";
        $header2[] = "Employee Name";
        $header2[] = "Tax Stat";
        $header2[] = "Basic Salary";
        $header2[] = "Basic Semi";
        $header2[] = "UT/Tardy";
        $header2[] = "Absences";
        $header2[] = "Over Time";
        $header2[] = "COLA";
    	for($e=0;$e<count($earnings);$e++){
			$header2[] = substr($this->getPayrollAcountNameByPsaID($earnings[$e]['psa_id']),0,20);
			$earnArr[] = $earnings[$e]['psa_id'];
		}
        $header2[] = "Total Earnings";
   		for($d=0;$d<count($deductions);$d++) {
			$header2[] = substr($deductions[$d]['psa_name'],0,20);
			$dedArr[] = $deductions[$d]['psa_id'];
		}
        $header2[] = "Total Deductions";
        $header2[] = "Net Pay";
        $xls->addHeader($header2);
		
        if($gData['sbydpart'] == '1'){
        	$varDepart = $this->getDepartment();
        	for($c=0;$c<count($varDepart);$c++){
        		$emp = $this->genReport($gData,$varDepart[$c]['ud_id']);
        		if(empty($emp)){
        			continue;
        		} else {
	        	if(count($emp)>0){
				$seq_np2 = 0;
		            $basic2=  0.00;
		            $gross2= 0.00;
		            $ot2= 0.00;
		            $Absences2= 0.00;
		            $LateUT2= 0.00;
		            $cola2= 0.00;
					for($ea=0;$ea<count($earnings);$ea++) {
						$deptTotal[$earnings[$ea]['psa_id']] = 0.00;	
					}
		           // $commallow2= 0.00;
		            $totalearning2= 0.00;
		            $taxpayables2 = 0.00;
		            //static gov contributions		            
		            $tax2 = 0.00;
		            $sss2 = 0.00;
		            $phic2 = 0.00;
		            $pagibig2 = 0.00;
		            //loan
	        		for($l=0;$l<count($deductions);$l++) {
						$deptTotal[$deductions[$l]['psa_id']] = 0.00;	
					}
						            
		            $totaldeduc2= 0.00;
		            $net2 = 0.00;
		            /*$seq_np2 = 0;
		            $basic2=  0.00;
		            $gross2= 0.00;
		            $ot2= 0.00;
		            $Absences2= 0.00;
		            $LateUT2= 0.00;
		            $cola2= 0.00;
		            $otadj2= 0.00;
		            $commallow2= 0.00;
		            $incentive2= 0.00;
		            $ssspread2= 0.00;
		            $phicpread2= 0.00;
		            $hdmfpread2= 0.00;
		            $slconv2= 0.00;
		            $mgtpart2 = 0.00;
		            $uniallow2 = 0.00;
		            $salaryadj = 0.00;
		            $thirteenthmonth = 0.00;
					$taxrefund2 = 0.00;
		            $taxpayables2 = 0.00;
		            $edo2 = 0.00;
		            $totalearning2= 0.00;
		            $tax2 = 0.00;
		            $sss2 = 0.00;
		            $phic2 = 0.00;
		            $pagibig2 = 0.00;
		            $sss_loan2 = 0.00;
		            $pagibig_loan2 = 0.00;
		            $cashadv2= 0.00;
		            $oversal2= 0.00;
		            $ssspreaddec2= 0.00;
		            $phicpreaddec2= 0.00;
		            $hdmfpreaddec2= 0.00;
		            $totaldeduc2= 0.00;
		            $net2 = 0.00;*/
		            
		            $row3 = $varDepart[$c]['ud_name']." - ".$varDepart[$c]['ud_desc'];
		            $xls->addRow($row3);
		            foreach($emp as $key => $val){
					 $basic2 += $this->moneyFormat($val['salaryinfo_basicrate']);
		                $gross2 += $this->moneyFormat($val['gross_semi']);
		                $ot2 += $this->moneyFormat($val['TA']['ot']);
		                $Absences2 += $this->moneyFormat($val['TA']['Absences']);
		                $LateUT2 += $this->moneyFormat($val['TA']['LateUT']);
		                $cola2 += $this->moneyFormat($val['cola']);
			            for($ea=0;$ea<count($earnings);$ea++) {
							$deptTotal[$earnings[$ea]['psa_id']] += $val['earnings'][$earnings[$ea]['psa_id']];
							if($earnings[$ea]['psa_id'] == 36 and $val['earnings'][$earnings[$ea]['psa_id']] == 0){
			           			$deptTotal[$earnings[$ea]['psa_id']] += $val['earnings']['comallow'];
			           		}
						}
						//$commallow2 += $this->moneyFormat($val['earnings']['comallow']);
			            $totalearning2 += $this->moneyFormat($val['totalearning']);
			            $taxpayables2 += $this->moneyFormat($val['deductions']['TaxPayables']);
			            
		                $tax2 += $this->moneyFormat($val['cont']['tax']);
		                $sss2 += $this->moneyFormat($val['cont']['sss']);
		                $phic2 += $this->moneyFormat($val['cont']['phic']);
		                $pagibig2 += $this->moneyFormat($val['cont']['hdmf']);
		                //loan
		            	for($l=0;$l<count($deductions);$l++) {
							$deptTotal[$deductions[$l]['psa_id']] += $val['deductions'][$deductions[$l]['psa_id']];
						}
		                $totaldeduc2 += $this->moneyFormat($val['totaldeduction']);
		                $net2 += $this->moneyFormat($val['net']);
		                $seq_np2++;
//		            	printa($val);
		               /* $basic2 += $this->moneyFormat($val['salaryinfo_basicrate']);
		                $gross2 += $this->moneyFormat($val['gross_semi']);
		                $ot2 += $this->moneyFormat($val['TA']['ot']);
		                $Absences2 += $this->moneyFormat($val['TA']['Absences']);
		                $LateUT2 += $this->moneyFormat($val['TA']['LateUT']);
		                $cola2 += $this->moneyFormat($val['cola']);
		                $otadj2 += $this->moneyFormat($val['earnings']['otadj']);
			            $commallow2 += $this->moneyFormat($val['earnings']['comallow']);
			            $incentive2 += $this->moneyFormat($val['earnings']['Incentive']);
			            $ssspread2 += $this->moneyFormat($val['earnings']['SSSPremadj']);
			            $phicpread2 += $this->moneyFormat($val['earnings']['PHPremAdj']);
			            $hdmfpread2 += $this->moneyFormat($val['earnings']['HDMFPremadj']);
			            $slconv2 += $this->moneyFormat($val['earnings']['SLConvertion']);
			            $mgtpart2 += $this->moneyFormat($val['earnings']['MgtParticipation']);
		            	$uniallow2 += $this->moneyFormat($val['earnings']['UniAllow']);
		            	$salaryadj2 += $this->moneyFormat($val['earnings']['SalaryAdj']);
		            	$thirteenthmonth2 += $this->moneyFormat($val['earnings']['ThirteenthMonth']);
			            $taxrefund2 += $this->moneyFormat($val['earnings']['TaxRefund']);
		           		$taxpayables2 += $this->moneyFormat($val['deduction']['TaxPayables']);
		           		$edo2 += $this->moneyFormat($val['earnings']['EDO']);
						$totalearning2 += $this->moneyFormat($val['totalearning']);
		                $tax2 += $this->moneyFormat($val['cont']['tax']);
		                $sss2 += $this->moneyFormat($val['cont']['sss']);
		                $phic2 += $this->moneyFormat($val['cont']['phic']);
		                $pagibig2 += $this->moneyFormat($val['cont']['hdmf']);
		                $sss_loan2 += $this->moneyFormat($val['loan']['sss']);
		                $pagibig_loan2 += $this->moneyFormat($val['loan']['hdmf']);
		                $cashadv2 += $this->moneyFormat($val['loan']['cashadv']);
		            	$oversal2 += $this->moneyFormat($val['deduction']['overpay']);
		            	$ssspreaddec2 += $this->moneyFormat($val['deduction']['SSSPremadj']);
		            	$phicpreaddec2 += $this->moneyFormat($val['deduction']['PHPremAdj']);
		            	$hdmfpreaddec2 += $this->moneyFormat($val['deduction']['HDMFPremadj']);
		                $totaldeduc2 += $this->moneyFormat($val['totaldeduction']);
		                $net2 += $this->moneyFormat($val['net']);
		                $seq_np2++;*/
		                
		                $row = array();
		                $row[] = $seq_np2.".";
		                $row[] = $val['emp_idnum'];
		                $row[] = $val['fullname'];
		                $row[] = $val['taxep_code'];
		                $row[] = number_format($val['salaryinfo_basicrate'], 2, '.', '');
		                $row[] = number_format($val['gross_semi'], 2, '.', '');
		                $row[] = number_format($this->toNegative($val['TA']['LateUT']), 2, '.', '');
		                $row[] = number_format($this->toNegative($val['TA']['Absences']), 2, '.', '');
		                $row[] = number_format($val['TA']['ot'], 2, '.', '');
		                $row[] = number_format($val['cola'], 2, '.', '');
		                if(count($earnArr) > 0){
			            	foreach($earnArr as $key => $earn) {
								for($am=0;$am<count($amendments);$am++) {
									if($earn === $amendments[$am]['psa_id']){
										$earnVal = $val['earnings'][$earn];
									}
								}
			            		if($earn == 36 and $val['earnings'][$earn] == 0){$earnVal = $val['earnings']['comallow'];}
								/*if($earn == 36){
									$earnVal = $val['earnings']['comallow'];
								}
			            		if($earn == 3){
									$earnVal = $val['earnings']['MgtParticipation'];
								} elseif($earn == 24){
									$earnVal = $val['earnings']['Incentive'];
								} elseif($earn == 36){
									$earnVal = $val['earnings']['comallow'];
								} elseif($earn == 38){
									$earnVal = $val['earnings']['UniAllow'];
								} elseif($earn == 41){
									$earnVal = $val['earnings']['otadj'];
								} elseif($earn == 42){
									$earnVal = $val['earnings']['SLConvertion'];
								} elseif($earn == 43){
									$earnVal = $val['earnings']['SSSPremadj'];
								} elseif($earn == 45){
									$earnVal = $val['earnings']['PHPremAdj'];
								} elseif($earn == 47){
									$earnVal = $val['earnings']['HDMFPremAdj'];
								} elseif($earn == 19){
									$earnVal = $val['earnings']['SalaryAdj'];
								} elseif($earn == 49){
									$earnVal = $val['earnings']['ThirteenthMonth'];
								} elseif($earn == 50){
			                		$earnVal = $val['earnings']['TaxRefund'];
			                	} elseif($earn == 40){
			                		$earnVal = $val['earnings']['EDO'];
			                	} else {
			                		$earnVal = 0.00;
			                	}*/
					   			$row[] = $this->moneyFormat($earnVal);
							
							}
			            }
		                $row[] = number_format($val['totalearning'], 2, '.', '');
		            	foreach($dedArr as $key => $ded) {
							for($lo=0;$lo<count($deductions);$lo++) {
								if($ded === $deductions[$lo]['psa_id']){
									$dedVal = $val['deductions'][$ded];
								}
							}
							
							if($ded == 7){
								$dedVal = $val['cont']['sss'];
							} elseif($ded == 14){
								$dedVal = $val['cont']['phic'];
							} elseif($ded == 15){
								$dedVal = $val['cont']['hdmf'];
							} elseif($ded == 8){
								$dedVal = $val['cont']['tax'];
							} elseif($ded == 51){
		                		$dedVal = $val['deductions']['TaxPayables'];
		               		}
							/*if($ded == 7){
								$dedVal = $val['cont']['sss'];
							} elseif($ded == 14){
								$dedVal = $val['cont']['phic'];
							} elseif($ded == 15){
								$dedVal = $val['cont']['hdmf'];
							} elseif($ded == 8){
								$dedVal = $val['cont']['tax'];
							} elseif($ded == 11){
								$dedVal = $val['loan']['cashadv'];
							} elseif($ded == 9){
								$dedVal = $val['loan']['sss'];
							} elseif($ded == 13){
								$dedVal = $val['loan']['hdmf'];
							} elseif($ded == 18){
								$dedVal = $val['deduction']['overpay'];
							} elseif($ded == 44){
								$dedVal = $val['deduction']['SSSPremadj'];
							} elseif($ded == 46){
								$dedVal = $val['deduction']['PHPremAdj'];
							} elseif($ded == 48){
								$dedVal = $val['deduction']['HDMFPremadj'];
							} elseif($ded == 51){
		                		$dedVal = $val['deduction']['TaxPayables'];
		               		} else {
		               			$dedVal = 0.00;
		               		}*/
				   			$row[] = $this->moneyFormat($this->toNegative($dedVal));
							
						}
		                $row[] = number_format($this->toNegative($val['totaldeduction']), 2, '.', '');
		                $row[] = number_format($val['net'], 2, '.', '');
		                $xls->addRow($row);
		            }
	        	}
        		$row4[] = "Department Total";
		        $row4[] = "";
		        $row4[] = "(".$seq_np2.")";
		        $row4[] = "";
		        $row4[] = $basic2;
		        $row4[] = $gross2;
		        $row4[] = $this->toNegative($LateUT2);
		        $row4[] = $this->toNegative($Absences2);
		        $row4[] = $ot2;
		        $row4[] = $cola2;
		        if(count($earnArr) > 0){
	        		foreach($earnArr as $key => $earn) {
						for($eaDept=0;$eaDept<count($earnings);$eaDept++) {
							if($earn === $earnings[$eaDept]['psa_id']){
								$earnValDept = $deptTotal[$earnings[$eaDept]['psa_id']];
							} 
						}
						//if($earn == 36){
						//	$earnValDept = $commallow2;
						//}
	        			/*if($earn == 3){
							$earnValDept = $mgtpart2;
						} elseif($earn == 24){
							$earnValDept = $incentive2;
						} elseif($earn == 36){
							$earnValDept = $commallow2;
						} elseif($earn == 38){
							$earnValDept = $uniallow2;
						} elseif($earn == 41){
							$earnValDept = $otadj2;
						} elseif($earn == 42){
							$earnValDept = $slconv2;
						} elseif($earn == 43){
							$earnValDept = $ssspread2;
						} elseif($earn == 45){
							$earnValDept = $phicpread2;
						} elseif($earn == 47){
							$earnValDept = $hdmfpread2;
						} elseif($earn == 19){
							$earnValDept = $salaryadj2;
						} elseif($earn == 49){
							$earnValDept = $thirteenthmonth2;
						} elseif($earn == 50){
			             	$earnValDept = $taxrefund2;
			            } elseif($earn == 40){
			             	$earnValDept = $edo2;
			            } else {
			            	$earnValDept = 0.00;
			            }*/
					   	$row4[] = $this->moneyFormat($earnValDept);
					}
        		}
		        $row4[] = $totalearning2;
		        foreach($dedArr as $key => $ded) {
					for($dDept=0;$dDept<count($deductions);$dDept++) {
						if($ded === $deductions[$dDept]['psa_id']){
							$dedValDept = $deptTotal[$deductions[$dDept]['psa_id']];
						} 
					}
					if($ded == 7){
						$dedValDept = $sss2;
					} elseif($ded == 14){
						$dedValDept = $phic2;
					} elseif($ded == 15){
						$dedValDept = $pagibig2;
					} elseif($ded == 8){
						$dedValDept = $tax2;
					} elseif($ded == 51){
		            	$dedValDept = $taxpayables2;
		            }
	        		/*if($ded == 7){
						$dedValDept = $sss2;
					} elseif($ded == 14){
						$dedValDept = $phic2;
					} elseif($ded == 15){
						$dedValDept = $pagibig2;
					} elseif($ded == 8){
						$dedValDept = $tax2;
					} elseif($ded == 11){
						$dedValDept = $cashadv2;
					} elseif($ded == 9){
						$dedValDept = $sss_loan2;
					} elseif($ded == 13){
						$dedValDept = $pagibig_loan2;
					} elseif($ded == 18){
						$dedValDept = $oversal2;
					} elseif($ded == 44){
						$dedValDept = $ssspreaddec2;
					} elseif($ded == 46){
						$dedValDept = $phicpreaddec2;
					} elseif($ded == 48){
						$dedValDept = $hdmfpreaddec2;
					} elseif($ded == 51){
						$dedValDept = $taxpayables2;
		            } else {
		            	$dedValDept = 0.00;
		            }*/
		        	$row4[] = $this->moneyFormat($this->toNegative($dedValDept));
		        }
		        $row4[] = $this->toNegative($totaldeduc2);
		        $row4[] = $net2;
		        $xls->addRow($row4);
		        
		        $row5[] = "";
		        $row5[] = "";
		        $row5[] = "";
		        $row5[] = "";
		        $row5[] = "";
		        $row5[] = "";
		        $row5[] = "";
		        $row5[] = "";
		        $row5[] = "";
		        $row5[] = "";
		        for($eCount=0;$eCount<count($earnings);$eCount++){
        			$row5[] = ""; 
        		}
        		$row5[] = "";
        		$row5[] = "";
		    	for($dCount=0;$dCount<count($deductions);$dCount++) {
					$row5[] = "";
				}
        		$row5[] = "";
        		$row5[] = "";
        		$xls->addRow($row5);
				
        		$basic += $basic2;
                $gross += $gross2;
                $ot += $ot2;
                $Absences += $Absences2;
                $LateUT += $LateUT2;
                $cola += $cola2;
                $commallow += $commallow2;
        		for($eaGrand=0;$eaGrand<count($earnings);$eaGrand++) {
					$grandTotal[$earnings[$eaGrand]['psa_id']] += $deptTotal[$earnings[$eaGrand]['psa_id']];
				}
	            $totalearning += $totalearning2;
	            $taxpayables += $taxpayables2;
	            //static gov contributions
                $tax += $tax2;
                $sss += $sss2;
                $phic += $phic2;
                $pagibig += $pagibig2;
        		for($dGrand=0;$dGrand<count($deductions);$dGrand++) {
					$grandTotal[$deductions[$dGrand]['psa_id']] += $deptTotal[$deductions[$dGrand]['psa_id']];
				}
                
                $totaldeduc += $totaldeduc2;
                $net += $net2;
                $seq_np+=$seq_np2;
        		/*$basic += $basic2;
                $gross += $gross2;
                $ot += $ot2;
                $Absences += $Absences2;
                $LateUT += $LateUT2;
                $cola += $cola2;
                $otadj += $otadj2;
	            $commallow += $commallow2;
	            $incentive += $incentive2;
	            $ssspread += $ssspread2;
	            $phicpread += $phicpread2;
	            $hdmfpread += $hdmfpread2;
	            $slconv += $slconv2;
	            $mgtpart += $mgtpart2;
	            $uniallow += $uniallow2;
	            $salaryadj += $salaryadj2;
				$thirteenthmonth += $thirteenthmonth2;
				$taxrefund += $taxrefund2;
				$taxpayables += $taxpayables2;
				$edo += $edo2;
	            $totalearning += $totalearning2;
                $tax += $tax2;
                $sss += $sss2;
                $phic += $phic2;
                $pagibig += $pagibig2;
                $sss_loan += $sss_loan2;
                $pagibig_loan += $pagibig_loan2;
                $cashadv += $cashadv2;
            	$oversal += $oversal2;
            	$ssspreaddec += $ssspreaddec2;
            	$phicpreaddec += $phicpreaddec2;
            	$hdmfpreaddec += $hdmfpreaddec2;
                $totaldeduc += $totaldeduc2;
                $net += $net2;
                $seq_np+=$seq_np2;*/
        		
        		unset($row5);
		        unset($row4);
        	}
        	}
        }else{
        $emp = $this->genReport($gData);
        if(count($emp)>0){
			$seq_np = 0;
            $basic=  0.00;
            $gross= 0.00;
            $ot= 0.00;
            $Absences= 0.00;
            $LateUT= 0.00;
            $cola= 0.00;
            //$commallow= 0.00;
        	for($eaGrand=0;$eaGrand<count($earnings);$eaGrand++) {
				$grandTotal[$earnings[$eaGrand]['psa_id']] = 0.00;
			}
           
            $totalearning = 0.00;
            $taxpayables = 0.00;
            $tax = 0.00;
            $sss = 0.00;
            $phic = 0.00;
            $pagibig = 0.00;
        	for($dGrand=0;$dGrand<count($deductions);$dGrand++) {
				$grandTotal[$deductions[$dGrand]['psa_id']] = 0.00;
			}
            
            $totaldeduc= 0.00;
            $net = 0.00;
            /*$seq_np = 0;
            $basic=  0.00;
            $gross= 0.00;
            $ot= 0.00;
            $Absences= 0.00;
            $LateUT= 0.00;
            $cola= 0.00;
            $otadj= 0.00;
            $commallow= 0.00;
            $incentive= 0.00;
            $ssspread= 0.00;
            $phicpread= 0.00;
            $hdmfpread= 0.00;
            $slconv= 0.00;
           	$mgtpart = 0.00;
	        $uniallow = 0.00;
	        $salaryadj = 0.00;
			$thirteenthmonth = 0.00;
			$taxrefund = 0.00;
			$taxpayables = 0.00;
			$edo = 0.00;
            $totalearning = 0.00;
            $tax = 0.00;
            $sss = 0.00;
            $phic = 0.00;
            $pagibig = 0.00;
            $sss_loan = 0.00;
            $pagibig_loan = 0.00;
            $cashadv= 0.00;
            $oversal= 0.00;
            $ssspreaddec= 0.00;
            $phicpreaddec= 0.00;
            $hdmfpreaddec= 0.00;
            $totaldeduc= 0.00;
            $net = 0.00;*/
            foreach($emp as $key => $val){
				$basic += $this->moneyFormat($val['salaryinfo_basicrate']);
                $gross += $this->moneyFormat($val['gross_semi']);
                $ot += $this->moneyFormat($val['TA']['ot']);
                $Absences += $this->moneyFormat($val['TA']['Absences']);
                $LateUT += $this->moneyFormat($val['TA']['LateUT']);
                $cola += $this->moneyFormat($val['cola']);
               // $commallow += $this->moneyFormat($val['earnings']['comallow']);
           	 	for($eaGrand=0;$eaGrand<count($earnings);$eaGrand++) {
					$grandTotal[$earnings[$eaGrand]['psa_id']] += $val['earnings'][$earnings[$eaGrand]['psa_id']];
					if($earnings[$eaGrand]['psa_id'] == 36 and $val['earnings'][$earnings[$eaGrand]['psa_id']] == 0){
			    		$grandTotal[$earnings[$eaGrand]['psa_id']] += $val['earnings']['comallow'];
			   		}
				}
                
	            $totalearning += $this->moneyFormat($val['totalearning']);
	            $taxpayables += $this->moneyFormat($val['deductions']['TaxPayables']);
                $tax += $this->moneyFormat($val['cont']['tax']);
                $sss += $this->moneyFormat($val['cont']['sss']);
                $phic += $this->moneyFormat($val['cont']['phic']);
                $pagibig += $this->moneyFormat($val['cont']['hdmf']);
            	for($dGrand=0;$dGrand<count($deductions);$dGrand++) {
					$grandTotal[$deductions[$dGrand]['psa_id']] += $val['deductions'][$deductions[$dGrand]['psa_id']];
				}
                
            	$hdmfpreaddec += $this->moneyFormat($val['deduction']['HDMFPremadj']);
                $totaldeduc += $this->moneyFormat($val['totaldeduction']);
                $net += $this->moneyFormat($val['net']);
                $seq_np++;
//            	printa($val);
                /*$basic += $this->moneyFormat($val['salaryinfo_basicrate']);
                $gross += $this->moneyFormat($val['gross_semi']);
                $ot += $this->moneyFormat($val['TA']['ot']);
                $Absences += $this->moneyFormat($val['TA']['Absences']);
                $LateUT += $this->moneyFormat($val['TA']['LateUT']);
                $cola += $this->moneyFormat($val['cola']);
                $otadj += $this->moneyFormat($val['earnings']['otadj']);
	            $commallow += $this->moneyFormat($val['earnings']['comallow']);
	            $incentive += $this->moneyFormat($val['earnings']['Incentive']);
	            $ssspread += $this->moneyFormat($val['earnings']['SSSPremadj']);
	            $phicpread += $this->moneyFormat($val['earnings']['PHPremAdj']);
	            $hdmfpread += $this->moneyFormat($val['earnings']['HDMFPremadj']);
	            $slconv += $this->moneyFormat($val['earnings']['SLConvertion']);
	            $mgtpart += $this->moneyFormat($val['earnings']['MgtParticipation']);
	       		$uniallow += $this->moneyFormat($val['earnings']['UniAllow']);
	       		$salaryadj += $this->moneyFormat($val['earnings']['SalaryAdj']);
				$thirteenthmonth += $this->moneyFormat($val['earnings']['ThirteenthMonth']);
				$taxrefund += $this->moneyFormat($val['earnings']['TaxRefund']);
				$taxpayables += $this->moneyFormat($val['deduction']['TaxPayables']);
				$edo += $this->moneyFormat($val['earnings']['EDO']);
	            $totalearning += $this->moneyFormat($val['totalearning']);
                $tax += $this->moneyFormat($val['cont']['tax']);
                $sss += $this->moneyFormat($val['cont']['sss']);
                $phic += $this->moneyFormat($val['cont']['phic']);
                $pagibig += $this->moneyFormat($val['cont']['hdmf']);
                $sss_loan += $this->moneyFormat($val['loan']['sss']);
                $pagibig_loan += $this->moneyFormat($val['loan']['hdmf']);
                $cashadv += $this->moneyFormat($val['loan']['cashadv']);
            	$oversal += $this->moneyFormat($val['deduction']['overpay']);
            	$ssspreaddec += $this->moneyFormat($val['deduction']['SSSPremadj']);
            	$phicpreaddec += $this->moneyFormat($val['deduction']['PHPremAdj']);
            	$hdmfpreaddec += $this->moneyFormat($val['deduction']['HDMFPremadj']);
                $totaldeduc += $this->moneyFormat($val['totaldeduction']);
                $net += $this->moneyFormat($val['net']);
                $seq_np++;*/

                $row = array();
                $row[] = $seq_np.".";
                $row[] = $val['emp_idnum'];
                $row[] = $val['fullname'];
                $row[] = $val['taxep_code'];
                $row[] = number_format($val['salaryinfo_basicrate'],2,'.','');
                $row[] = number_format($val['gross_semi'],2,'.','');
                $row[] = number_format($this->toNegative($val['TA']['LateUT']),2,'.','');
                $row[] = number_format($this->toNegative($val['TA']['Absences']),2,'.','');
                $row[] = number_format($val['TA']['ot'],2,'.','');
                $row[] = number_format($val['cola'],2,'.','');
                if(count($earnArr) > 0){
		            foreach($earnArr as $key => $earn) {
						for($am=0;$am<count($amendments);$am++) {
							if($earn === $amendments[$am]['psa_id']){
								$earnVal = $val['earnings'][$earn];
							} 
						}
		            	if($earn == 36 and $val['earnings'][$earn] == 0){$earnVal = $val['earnings']['comallow'];}
						//if($earn == 36){
						//	$earnVal = $val['earnings']['comallow'];
						//}
		            	/*if($earn == 3){
							$earnVal = $val['earnings']['MgtParticipation'];
						} elseif($earn == 24){
							$earnVal = $val['earnings']['Incentive'];
						} elseif($earn == 36){
							$earnVal = $val['earnings']['comallow'];
						} elseif($earn == 38){
							$earnVal = $val['earnings']['UniAllow'];
						} elseif($earn == 41){
							$earnVal = $val['earnings']['otadj'];
						} elseif($earn == 42){
							$earnVal = $val['earnings']['SLConvertion'];
						} elseif($earn == 43){
							$earnVal = $val['earnings']['SSSPremadj'];
						} elseif($earn == 45){
							$earnVal = $val['earnings']['PHPremAdj'];
						} elseif($earn == 47){
							$earnVal = $val['earnings']['HDMFPremAdj'];
						} elseif($earn == 19){
							$earnVal = $val['earnings']['SalaryAdj'];
						} elseif($earn == 49){
							$earnVal = $val['earnings']['ThirteenthMonth'];
						} elseif($earn == 50){
			            	$earnVal = $val['earnings']['TaxRefund'];
			            } elseif($earn == 40){
			            	$earnVal = $val['earnings']['EDO'];
			            } else {
			            	$earnVal = 0.00;
			            }*/
					$row[] = $this->moneyFormat($earnVal);
					}
            	}
                $row[] = number_format($val['totalearning'],2,'.','');
	            foreach($dedArr as $key => $ded) {
					for($lo=0;$lo<count($deductions);$lo++) {
						if($ded === $deductions[$lo]['psa_id']){
							$dedVal = $val['deductions'][$ded];
						}
					}
					if($ded == 7){
						$dedVal = $val['cont']['sss'];
					} elseif($ded == 14){
						$dedVal = $val['cont']['phic'];
					} elseif($ded == 15){
						$dedVal = $val['cont']['hdmf'];
					} elseif($ded == 8){
						$dedVal = $val['cont']['tax'];
					} elseif($ded == 51){
		            	$dedVal = $val['deductions']['TaxPayables'];
		            }
	            	/*if($ded == 7){
						$dedVal = $val['cont']['sss'];
					} elseif($ded == 14){
						$dedVal = $val['cont']['phic'];
					} elseif($ded == 15){
						$dedVal = $val['cont']['hdmf'];
					} elseif($ded == 8){
						$dedVal = $val['cont']['tax'];
					} elseif($ded == 11){
						$dedVal = $val['loan']['cashadv'];
					} elseif($ded == 9){
						$dedVal = $val['loan']['sss'];
					} elseif($ded == 13){
						$dedVal = $val['loan']['hdmf'];
					} elseif($ded == 18){
						$dedVal = $val['deduction']['overpay'];
					} elseif($ded == 44){
						$dedVal = $val['deduction']['SSSPremadj'];
					} elseif($ded == 46){
						$dedVal = $val['deduction']['PHPremAdj'];
					} elseif($ded == 48){
						$dedVal = $val['deduction']['HDMFPremadj'];
					} elseif($ded == 51){
		            	$dedVal = $val['deduction']['TaxPayables'];
		            } else {
		            	$dedVal = 0.00;
		            }*/
				$row[] = $this->moneyFormat($this->toNegative($dedVal));
	    		}
                $row[] = number_format($this->toNegative($val['totaldeduction']),2,'.','');
                $row[] = number_format($val['net'],2,'.','');
                $xls->addRow($row);
            }
        }
        }
        $row1[] = "";
		$row1[] = "";
		$row1[] = "";
		$row1[] = "";
		$row1[] = "";
		$row1[] = "";
		$row1[] = "";
		$row1[] = "";
		$row1[] = "";
		$row1[] = "";
		for($eCount=0;$eCount<count($earnings);$eCount++){
        	$row1[] = ""; 
        }
        $row1[] = "";
        $row1[] = "";
		for($dCount=0;$dCount<count($deductions);$dCount++) {
		$row1[] = "";
		}
        $row1[] = "";
        $row1[] = "";

        $xls->addRow($row1);

        $row2[] = "Grand Total";
        $row2[] = "";
        $row2[] = "(".$seq_np.")";
        $row2[] = "";
        $row2[] = number_format($basic,2,'.','');
        $row2[] = number_format($gross,2,'.','');
        $row2[] = number_format($this->toNegative($LateUT),2,'.','');
        $row2[] = number_format($this->toNegative($Absences),2,'.','');
        $row2[] = number_format($ot,2,'.','');
        $row2[] = number_format($cola,2,'.','');
        if(count($earnArr) > 0){
	    	foreach($earnArr as $key => $earnTotal) {
				for($grandAm=0;$grandAm<count($earnings);$grandAm++) {
					if($earnTotal === $earnings[$grandAm]['psa_id']){
						$earnGrand = $grandTotal[$earnings[$grandAm]['psa_id']];
					} 
				}
				//if($earnTotal == 36){
				//	$earnGrand = $commallow;
				//}
	    		/*if($earnTotal == 3){
					$earnGrand = $mgtpart;
				} elseif($earnTotal == 24){
					$earnGrand = $incentive;
				} elseif($earnTotal == 36){
					$earnGrand = $commallow;
				} elseif($earnTotal == 38){
					$earnGrand = $uniallow;
				} elseif($earnTotal == 41){
					$earnGrand = $otadj;
				} elseif($earnTotal == 42){
					$earnGrand = $slconv;
				} elseif($earnTotal == 43){
					$earnGrand = $ssspread;
				} elseif($earnTotal == 45){
					$earnGrand = $phicpread;
				} elseif($earnTotal == 47){
					$earnGrand = $hdmfpread;
				} elseif($earnTotal == 19){
					$earnGrand = $salaryadj;
				} elseif($earnTotal == 49){
					$earnGrand = $thirteenthmonth;
				} elseif($earnTotal == 50){
			    	$earnGrand = $taxrefund;
			    } elseif($earnTotal == 40){
			    	$earnGrand = $edo;
			    } else {
			    	$earnGrand = 0.00;
			    }*/
				  $row2[] = $this->moneyFormat($earnGrand);
			}
    	}
        $row2[] = number_format($totalearning,2,'.','');
    	foreach($dedArr as $key => $dedTotal) {
			for($grandD=0;$grandD<count($deductions);$grandD++) {
				if($dedTotal === $deductions[$grandD]['psa_id']){
					$dedValGrand = $grandTotal[$deductions[$grandD]['psa_id']];
				} 
			}
			if($dedTotal == 7){
				$dedValGrand = $sss;
			} elseif($dedTotal == 14){
				$dedValGrand = $phic;
			} elseif($dedTotal == 15){
				$dedValGrand = $pagibig;
			} elseif($dedTotal == 8){
				$dedValGrand = $tax;
			} elseif($dedTotal == 51){
		    	$dedValGrand = $taxpayables;
			}
			/*if($dedTotal == 7){
				$dedValGrand = $sss;
			} elseif($dedTotal == 14){
				$dedValGrand = $phic;
			} elseif($dedTotal == 15){
				$dedValGrand = $pagibig;
			} elseif($dedTotal == 8){
				$dedValGrand = $tax;
			} elseif($dedTotal == 11){
				$dedValGrand = $cashadv;
			} elseif($dedTotal == 9){
				$dedValGrand = $sss_loan;
			} elseif($dedTotal == 13){
				$dedValGrand = $pagibig_loan;
			} elseif($dedTotal == 18){
				$dedValGrand = $oversal;
			} elseif($dedTotal == 44){
				$dedValGrand = $ssspreaddec;
			} elseif($dedTotal == 46){
				$dedValGrand = $phicpreaddec;
			} elseif($dedTotal == 48){
				$dedValGrand = $hdmfpreaddec;
			} elseif($dedTotal == 51){
				$dedValGrand = $taxpayables;
			} else {
				$dedValGrand = 0.00;
			}*/
			$row2[] = $this->moneyFormat($this->toNegative($dedValGrand));
    	}
        $row2[] = number_format($this->toNegative($totaldeduc),2,'.','');
        $row2[] = number_format($net,2,'.','');
        $xls->addRow($row2);
        $xls->sendFile();
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
	
	function getDepartment(){
		$sql = "Select * from app_userdept order by ud_name";
		$rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF) {
			$arrData[] = $rsResult->fields;
			$rsResult->MoveNext();
		}
		return $arrData;
	}
	
    //function that will create PDF
	function createPDF($content, $paper, $orientation, $filename){
		$dompdf = new DOMPDF();
		$dompdf->load_html($content);
		$dompdf->set_paper($paper,$orientation);
		$dompdf->render();
		$dompdf->stream($filename,array('Attachment' => 0));	
	}
	
	function moneyFormat($param){
		if($param == 0 or $param == ''){
			return "0.00";
		} else {
			return number_format((float)$param,2,'.','');
		}
	}
	
	function generatePDFPayrollRegisterReport($gData = array()){
		$paper = 'legal';
		$orientation = 'landscape';
		$objClsSSS = new clsSSS($this->conn);
		$oDataBranch = $objClsSSS->dbfetchCompDetails();
		if($gData['type'] == 1){
            $empdept_type = "Confidential";
        }else if($gData['type'] == 2){
            $empdept_type = "Non-Confidential";
        }else{
        	$empdept_type = "All Employee";
        }
        $payperiodstatus = $this->getPayperiodDetails($gData['payperiod_id'],null,null);
        
		$filename = 'PDFPayroll.pdf';
		$content = '<style type="text/css">
						@page { margin:1.3em 1.8em;}
						.leftmost{
							border:1px solid black;
						}
						.rightside{
							border-top:1px solid black;
							border-bottom:1px solid black;
							border-right:1px solid black;
						}
						.bolditalic{
							font-style:italic;font-weight:bold;
							vertical-align:top;
							font-size:10px;
						}
						.righttop{
							border-top:1px solid black;
							border-right:1px solid black;
							font-size:10px;
						}
						.inside_tbl{
							border-collapse:collapse;
							vertical-align:top;
							border-bottom:1px solid black;
							font-size:10px;
						}
						.right_only{
							border-right:1px solid black;
							font-size:10px;
						}
					</style>
					<table style="border-collapse:collapse; font:10px Helvetica;" width="100%">';
		//get earnings and filter if amendments then check if 0 total
		$earn = $this->getColumns(1);
		$earningsPSA = $earn;
		$amendments = $this->getAmendments($gData['payperiod_id'],1);
		for($eCount=0;$eCount<count($amendments);$eCount++) {
			$earningsPSA = $this->removeElementWithValue($earningsPSA, 'psa_id', $amendments[$eCount]['psa_id']);
		}
		//check non-amendments if zero total
		for($psaCount=0;$psaCount<count($earningsPSA);$psaCount++){
			//type 1 => psa, type 2 => amendment
			if($this->checkEarningColumnIfZero($earningsPSA[$psaCount]['psa_id'], '1', $gData['payperiod_id'], $payperiodstatus[0]['payperiod_trans_date'])){
				$earnings[] = $earningsPSA[$psaCount];
			} else {
				continue;
			}
		}
		//check amendments if zero total
		for($amCount=0;$amCount<count($amendments);$amCount++){
			//type 1 => psa, type 2 => amendment
			if($this->checkEarningColumnIfZero($amendments[$amCount]['psa_id'], '2', $_GET, $payperiodstatus[0]['payperiod_trans_date'])){
				$earnings[] = $amendments[$amCount];
			} else {
				continue;
			}
		}
		if(count($earnings) > 0){
			$earnings = $this->sortByPsaOrder($earnings);
		}
		//get deductions and filter if loan
		$deduct = $this->getColumns(2);
		for($dCount=0;$dCount<count($deduct);$dCount++){
			if($this->checkDeductionColumnIfZero($deduct[$dCount]['psa_id'], $payperiodstatus[0]['payperiod_trans_date'], $_GET)){
				$deductions[] = $deduct[$dCount];
			} else {
				continue;
			}
		}
		if((count($earnings)+4)>count($deductions)){
			$maxEarnings = 4;
			$maxDeductions = floor(count($deductions)/(ceil((count($earnings)+4)/4)));
			$maxWidthEarn = '200px';
			$maxWidthDed = '250px';
			$cellWidthEarn = '57px';
			$cellWidthDed = '80px';
		} else {
			$maxDeductions = 4;
			$maxDeductions = floor((count($earnings)+4)/(ceil(count($deductions)/4)));
			$maxWidthEarn = '250px';
			$maxWidthDed = '200px';
			$cellWidthEarn = '80px';
			$cellWidthDed = '57px';
		}
		$header = 	'<tr><td colspan="11"><strong>'.$oDataBranch['comp_name'].'</strong></td></tr>
						<tr><td colspan="11"><i>'.$oDataBranch['comp_add'].'</i></td></tr>
						<tr><td colspan="11"><strong>Payroll Register</strong></td></tr>
						<tr><td colspan="11"><strong>'.$empdept_type.'</strong></td></tr>
						<tr><td colspan="11"><strong>for the period: '.date('F d, Y',dDate::parseDateTime($payperiodstatus[0]['payperiod_trans_date'])).'</strong></td></tr><tr style="font-weight:bold;">
							<td colspan="4">&nbsp;</td>
							<td colspan="4" align="center" class="leftmost">Earnings</td>
							<td colspan="2" align="center" class="rightside">Deductions</td>
							<td class="rightside" align="center">Salary to Pay</td>
						</tr>
						<tr style="font-weight:bold;">
							<td class="leftmost" width="10px">&nbsp;</td>
							<td align="center" class="rightside" width="60px">Emp ID</td>
							<td align="center" class="rightside" width="130px">Employee Name</td>
							<td align="center" class="rightside" width="10px">Tax<br>Stat</td>
							<td align="center" class="rightside" width="50px">Basic Salary</td>
							<td align="center" class="rightside" width="50px">Basic Semi</td>
							<td class="inside_tbl" width="'.$maxWidthEarn.'"><table style="border-collapse:collapse;" width="100%"><tr>
							<td align="center" class="right_only" width="'.$cellWidthEarn.'">UT/Tardy</td>
							<td align="center" class="right_only" width="'.$cellWidthEarn.'">Absences</td>
							<td align="center" class="right_only" width="'.$cellWidthEarn.'">Over Time</td>
							<td align="center" class="right_only" width="'.$cellWidthEarn.'">COLA</td></tr>';
						$ctr=1;
						$max = $maxEarnings;
						$traceEarn=1;
						for($e=0;$e<count($earnings);$e++){
							if($ctr==1){
								$header .= '<tr>';
								$traceEarn++;
							}
				   			$header .= '<td class="righttop">'.substr($this->getPayrollAcountNameByPsaID($earnings[$e]['psa_id']),0,10).'</td>';
							if($ctr>=$max){
								$header .= '</tr>';
								$ctr=0;
							}
							$ctr++;
							$earnArr[] = $earnings[$e]['psa_id'];
						}
						if($ctr <= $max && $ctr != 1){
							$span = ($max - $ctr) + 1;
							$header .= '<td colspan="'.$span.'" class="righttop">&nbsp;</td></tr>';	
						}
						$header .= '</table></td>
							<td align="center" class="rightside" style="font-weight:bold;" width="50px">Total Earnings</td>
							<td class="inside_tbl" width="'.$maxWidthDed.'">
							<table style="border-collapse:collapse;" width="100%">';
						$ctr=1;
						$max = $maxDeductions;
						$trace = 1;
						for($d=0;$d<count($deductions);$d++) {
							if($ctr==1){
								$header .= '<tr>';
							}
						
							if($trace<=ceil(count($deductions)/($traceEarn))){
								$class = "right_only";
							} else {
								$class = "righttop";
							}
				   			$header .= '<td class="'.$class.'" width="'.$cellWidthDed.'">'.substr($deductions[$d]['psa_name'],0,10).'</td>';
							if($ctr>$max){
								$header .= '</tr>';
								$ctr=0;
							}
							$ctr++;
							$trace++;
							$dedArr[] = $deductions[$d]['psa_id'];
						}
						if($ctr >= $max){
							$span = ($max - $ctr) + 2;
							$header .= '<td colspan="'.$span.'" class="righttop">&nbsp;</td></tr>';	
						}
				$header .=	'</table></td>
							<td align="center" class="rightside" style="font-weight:bold;" width="50px">Total<br>Deductions</td>
							<td align="center" class="rightside" width="50px" style="font-weight:bold;">Net Pay</td>
						</tr>';
			if($gData['sbydpart']){
        	$varDepart = $this->getDepartment();
        	for($c=0;$c<count($varDepart);$c++){
        		$emp = $this->genReport($gData,$varDepart[$c]['ud_id']);
        		if(empty($emp)){
        			continue;
        		} else {
	        	if(count($emp)>0){
		            $seq_np2 = 0;
		            $basic2=  0.00;
		            $gross2= 0.00;
		            $ot2= 0.00;
		            $Absences2= 0.00;
		            $LateUT2= 0.00;
		            $cola2= 0.00;
					for($ea=0;$ea<count($earnings);$ea++) {
						$deptTotal[$earnings[$ea]['psa_id']] = 0.00;
					}
		            //$commallow2= 0.00;
		            $totalearning2= 0.00;
		            $taxpayables2 = 0.00;
		            //static gov contributions		            
		            $tax2 = 0.00;
		            $sss2 = 0.00;
		            $phic2 = 0.00;
		            $pagibig2 = 0.00;
		            //loan
	        		for($l=0;$l<count($deductions);$l++) {
						$deptTotal[$deductions[$l]['psa_id']] = 0.00;	
					}
							            
		            $totaldeduc2= 0.00;
		            $net2 = 0.00;
		            
		            $deptDetails = $varDepart[$c]['ud_name']." - ".$varDepart[$c]['ud_desc'];
		            $main .= '<tr><td colspan="11" style="page-break-before:always;">'.$deptDetails.'</td></tr>';
		            
		            foreach($emp as $key => $val){
//		            	printa($val);
		                $basic2 += $this->moneyFormat($val['salaryinfo_basicrate']);
		                $gross2 += $this->moneyFormat($val['gross_semi']);
		                $ot2 += $this->moneyFormat($val['TA']['ot']);
		                $Absences2 += $this->moneyFormat($val['TA']['Absences']);
		                $LateUT2 += $this->moneyFormat($val['TA']['LateUT']);
		                $cola2 += $this->moneyFormat($val['cola']);
			            for($ea=0;$ea<count($earnings);$ea++) {
							$deptTotal[$earnings[$ea]['psa_id']] += $val['earnings'][$earnings[$ea]['psa_id']];
			           		if($earnings[$ea]['psa_id'] == 36 and $val['earnings'][$earnings[$ea]['psa_id']] == 0){
			           			$deptTotal[$earnings[$ea]['psa_id']] += $val['earnings']['comallow'];
			           		}
						}
						//$commallow2 += $this->moneyFormat($val['earnings']['comallow']);
			            $totalearning2 += $this->moneyFormat($val['totalearning']);
			            $taxpayables2 += $this->moneyFormat($val['deductions']['TaxPayables']);
			            
		                $tax2 += $this->moneyFormat($val['cont']['tax']);
		                $sss2 += $this->moneyFormat($val['cont']['sss']);
		                $phic2 += $this->moneyFormat($val['cont']['phic']);
		                $pagibig2 += $this->moneyFormat($val['cont']['hdmf']);
		                //loan
		            	for($l=0;$l<count($deductions);$l++) {
							$deptTotal[$deductions[$l]['psa_id']] += $val['deductions'][$deductions[$l]['psa_id']];
						}
		                $totaldeduc2 += $this->moneyFormat($val['totaldeduction']);
		                $net2 += $this->moneyFormat($val['net']);
		                $seq_np2++;
		                $main .= '<tr>
							<td align="right" class="leftmost" width="10px">'.$seq_np2.".".'</td>
							<td align="left" class="rightside" width="60px">'.$val['emp_idnum'].'</td>
							<td align="left" class="rightside" width="130px">'.$val['fullname'].'</td>
							<td align="center" class="rightside" width="40px">'.$val['taxep_code'].'</td>
							<td align="right" class="rightside" width="50px">'.$this->moneyFormat($val['salaryinfo_basicrate']).'</td>
							<td align="right" class="rightside" width="50px">'.$this->moneyFormat($val['gross_semi']).'</td>
							<td class="inside_tbl" style="border-top:1px solid black;" width="'.$maxWidthEarn.'"><table style="border-collapse:collapse;" width="100%">
							<tr>
								<td align="right" class="right_only" width="'.$cellWidthEarn.'">'.$this->moneyFormat($this->toNegative($val['TA']['LateUT'])).'&nbsp;</td>
								<td align="right" class="right_only" width="'.$cellWidthEarn.'">'.$this->moneyFormat($this->toNegative($val['TA']['Absences'])).'&nbsp;</td>
								<td align="right" class="right_only" width="'.$cellWidthEarn.'">'.$this->moneyFormat($val['TA']['ot']).'&nbsp;</td>
								<td align="right" class="right_only" width="'.$cellWidthEarn.'">'.$this->moneyFormat($val['cola']).'&nbsp;</td>
							</tr>';
						$ctr=1;
						$max = $maxEarnings;
						if(count($earnArr) > 0){
							foreach($earnArr as $key => $earn) {
								if($ctr==1){
									$main .= '<tr>';
								}
								for($am=0;$am<count($amendments);$am++) {
									if($earn === $amendments[$am]['psa_id']){
										$earnVal = $val['earnings'][$earn];
									}
								}
								if($earn == 36 and $val['earnings'][$earn] == 0){$earnVal = $val['earnings']['comallow'];}
								//if($earn == 36){
								//	$earnVal = $val['earnings']['comallow'];
								//}
					   			$main .= '<td class="righttop" align="right" width="'.$cellWidthEarn.'">'.$this->moneyFormat($earnVal).'&nbsp;</td>';
								if($ctr>=$max){
									$main .= '</tr>';
									$ctr=0;
								}
								$ctr++;
							}
		            	}
						if($ctr <= $max && $ctr != 1){
							$span = ($max - $ctr) + 1;
							$main .= '<td colspan="'.$span.'" class="righttop">&nbsp;</td></tr>';	
						}
							$main .= '</table></td>
							<td align="right" class="rightside" style="font-weight:bold;" width="50px">'.$this->moneyFormat($val['totalearning']).'</td>
							<td class="inside_tbl" style="border-top:1px solid black;" width="'.$maxWidthDed.'"><table style="border-collapse:collapse;" width="100%">';
						$ctr=1;
						$max = $maxDeductions;
						$trace=1;
						foreach($dedArr as $key => $ded) {
							if($ctr==1){
								$main .= '<tr>';
							}
						
							if($trace<=ceil(count($deductions)/($traceEarn))){
								$class = "right_only";
							} else {
								$class = "righttop";
							}
							for($lo=0;$lo<count($deductions);$lo++) {
								if($ded === $deductions[$lo]['psa_id']){
									$dedVal = $val['deductions'][$ded];
								}
							}
							
							if($ded == 7){
								$dedVal = $val['cont']['sss'];
							} elseif($ded == 14){
								$dedVal = $val['cont']['phic'];
							} elseif($ded == 15){
								$dedVal = $val['cont']['hdmf'];
							} elseif($ded == 8){
								$dedVal = $val['cont']['tax'];
							} elseif($ded == 51){
		                		$dedVal = $val['deductions']['TaxPayables'];
		               		}
				   			$main .= '<td class="'.$class.'" align="right" width="'.$cellWidthDed.'">'.$this->moneyFormat($this->toNegative($dedVal)).'&nbsp;</td>';
							if($ctr>$max){
								$main .= '</tr>';
								$ctr=0;
							}
							$ctr++;
							$trace++;
						}
						if($ctr >= $max){
							$span = ($max - $ctr) + 2;
							$main .= '<td colspan="'.$span.'" class="righttop">&nbsp;</td></tr>';	
						}
					$main .= '</table></td>
							<td align="right" class="rightside" style="font-weight:bold;" width="50px">'.$this->moneyFormat($this->toNegative($val['totaldeduction'])).'</td>
							<td align="right" class="rightside" width="50px" style="font-weight:bold;">'.$this->moneyFormat($val['net']).'</td>
						</tr>';
		            }
	        	}
        		$main .= '<tr>
							<td colspan="4" style="vertical-align:top;"><strong><i>Department Total&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;('.$seq_np2.')</i></strong></td>
							<td align="right" class="bolditalic" width="50px">'.$this->moneyFormat($basic2).'</td>
							<td align="right" class="bolditalic" width="50px">'.$this->moneyFormat($gross2).'</td>
							<td width="'.$maxWidthEarn.'"><table style="border-collapse:collapse;" width="100%">
							<tr>
								<td align="right" class="bolditalic" width="'.$cellWidthEarn.'">'.$this->moneyFormat($this->toNegative($LateUT2)).'</td>
								<td align="right" class="bolditalic" width="'.$cellWidthEarn.'">'.$this->moneyFormat($this->toNegative($Absences2)).'</td>
								<td align="right" class="bolditalic" width="'.$cellWidthEarn.'">'.$this->moneyFormat($ot2).'</td>
								<td align="right" class="bolditalic" width="'.$cellWidthEarn.'">'.$this->moneyFormat($cola2).'</td>
							</tr>';
						$ctr = 1;
						$max = $maxEarnings;
						if(count($earnArr) > 0){
							foreach($earnArr as $key => $earn) {
								if($ctr==1){
									$main .= '<tr>';
								}
								for($eaDept=0;$eaDept<count($earnings);$eaDept++) {
									if($earn === $earnings[$eaDept]['psa_id']){
										$earnValDept = $deptTotal[$earnings[$eaDept]['psa_id']];
									} 
								}
								//if($earn == 36){
								//	$earnValDept = $commallow2;
								//}
					   			$main .= '<td class="bolditalic" align="right" width="'.$cellWidthEarn.'">'.$this->moneyFormat($earnValDept).'&nbsp;</td>';
								if($ctr>=$max){
									$main .= '</tr>';
									$ctr=0;
								}
								$ctr++;
							}
        				}
						if($ctr <= $max && $ctr != 1){
							$span = ($max - $ctr) + 1;
							$main .= '<td colspan="'.$span.'">&nbsp;</td></tr>';	
						}
							$main .= '</table></td>
							<td align="right" class="bolditalic" width="50px">'.$this->moneyFormat($totalearning2).'</td>
								<td width="'.$maxWidthDed.'"><table style="border-collapse:collapse;" width="100%">';
						$ctr=1;
						$max = $maxDeductions;
						$trace=1;
						foreach($dedArr as $key => $ded) {
							if($ctr==1){
								$main .= '<tr>';
							}
							for($dDept=0;$dDept<count($deductions);$dDept++) {
								if($ded === $deductions[$dDept]['psa_id']){
									$dedValDept = $deptTotal[$deductions[$dDept]['psa_id']];
								} 
							}
							if($ded == 7){
								$dedValDept = $sss2;
							} elseif($ded == 14){
								$dedValDept = $phic2;
							} elseif($ded == 15){
								$dedValDept = $pagibig2;
							} elseif($ded == 8){
								$dedValDept = $tax2;
							} elseif($ded == 51){
		                		$dedValDept = $taxpayables2;
		               		}
				   			$main .= '<td class="bolditalic" align="right" width="'.$cellWidthDed.'">'.$this->moneyFormat($this->toNegative($dedValDept)).'&nbsp;</td>';
							if($ctr>$max){
								$main .= '</tr>';
								$ctr=0;
							}
							$ctr++;
							$trace++;
						}
						if($ctr >= $max){
							$span = ($max - $ctr) + 2;
							$main .= '<td colspan="'.$span.'">&nbsp;</td></tr>';	
						}
					$main .= '</table></td>
							<td align="right" class="bolditalic" width="50px">'.$this->moneyFormat($this->toNegative($totaldeduc2)).'</td>
							<td align="right" class="bolditalic" width="60px">'.$this->moneyFormat($net2).'</td>
						</tr>';
        		
        		$basic += $basic2;
                $gross += $gross2;
                $ot += $ot2;
                $Absences += $Absences2;
                $LateUT += $LateUT2;
                $cola += $cola2;
                //$commallow += $commallow2;
        		for($eaGrand=0;$eaGrand<count($earnings);$eaGrand++) {
					$grandTotal[$earnings[$eaGrand]['psa_id']] += $deptTotal[$earnings[$eaGrand]['psa_id']];
				}
	            $totalearning += $totalearning2;
	            $taxpayables += $taxpayables2;
	            //static gov contributions
                $tax += $tax2;
                $sss += $sss2;
                $phic += $phic2;
                $pagibig += $pagibig2;
        		for($dGrand=0;$dGrand<count($deductions);$dGrand++) {
					$grandTotal[$deductions[$dGrand]['psa_id']] += $deptTotal[$deductions[$dGrand]['psa_id']];
				}
                
                $totaldeduc += $totaldeduc2;
                $net += $net2;
                $seq_np+=$seq_np2;
        		
        	}
			}
        }else{
        $emp = $this->genReport($gData);
        if(count($emp)>0){
            $seq_np = 0;
            $basic=  0.00;
            $gross= 0.00;
            $ot= 0.00;
            $Absences= 0.00;
            $LateUT= 0.00;
            $cola= 0.00;
            //$commallow= 0.00;
        	for($eaGrand=0;$eaGrand<count($earnings);$eaGrand++) {
				$grandTotal[$earnings[$eaGrand]['psa_id']] = 0.00;
			}
           
            $totalearning = 0.00;
            $taxpayables = 0.00;
            $tax = 0.00;
            $sss = 0.00;
            $phic = 0.00;
            $pagibig = 0.00;
        	for($dGrand=0;$dGrand<count($deductions);$dGrand++) {
				$grandTotal[$deductions[$dGrand]['psa_id']] = 0.00;
			}
            
            $totaldeduc= 0.00;
            $net = 0.00;
            foreach($emp as $key => $val){
//            	printa($val);
                $basic += $this->moneyFormat($val['salaryinfo_basicrate']);
                $gross += $this->moneyFormat($val['gross_semi']);
                $ot += $this->moneyFormat($val['TA']['ot']);
                $Absences += $this->moneyFormat($val['TA']['Absences']);
                $LateUT += $this->moneyFormat($val['TA']['LateUT']);
                $cola += $this->moneyFormat($val['cola']);
                //$commallow += $this->moneyFormat($val['earnings']['comallow']);
           	 	for($eaGrand=0;$eaGrand<count($earnings);$eaGrand++) {
					$grandTotal[$earnings[$eaGrand]['psa_id']] += $val['earnings'][$earnings[$eaGrand]['psa_id']];
           	 	    if($earnings[$eaGrand]['psa_id'] == 36 and $val['earnings'][$earnings[$eaGrand]['psa_id']] == 0){
			    		$grandTotal[$earnings[$eaGrand]['psa_id']] += $val['earnings']['comallow'];
			   		}
           	 	}
                
	            $totalearning += $this->moneyFormat($val['totalearning']);
	            $taxpayables += $this->moneyFormat($val['deductions']['TaxPayables']);
                $tax += $this->moneyFormat($val['cont']['tax']);
                $sss += $this->moneyFormat($val['cont']['sss']);
                $phic += $this->moneyFormat($val['cont']['phic']);
                $pagibig += $this->moneyFormat($val['cont']['hdmf']);
            	for($dGrand=0;$dGrand<count($deductions);$dGrand++) {
					$grandTotal[$deductions[$dGrand]['psa_id']] += $val['deductions'][$deductions[$dGrand]['psa_id']];
				}
                
            	$hdmfpreaddec += $this->moneyFormat($val['deduction']['HDMFPremadj']);
                $totaldeduc += $this->moneyFormat($val['totaldeduction']);
                $net += $this->moneyFormat($val['net']);
                $seq_np++;
				
                $main .= '<tr>
							<td align="right" class="leftmost" width="10px">'.$seq_np.".".'</td>
							<td align="left" class="rightside" width="80px">'.$val['emp_idnum'].'</td>
							<td align="left" class="rightside" width="130px">'.$val['fullname'].'</td>
							<td align="center" class="rightside" width="40px">'.$val['taxep_code'].'</td>
							<td align="right" class="rightside" width="50px">'.$this->moneyFormat($val['salaryinfo_basicrate']).'</td>
							<td align="right" class="rightside" width="50px">'.$this->moneyFormat($val['gross_semi']).'</td>
							<td class="inside_tbl" style="border-top:1px solid black;" width="'.$maxWidthEarn.'"><table style="border-collapse:collapse;" width="100%">
							<tr>
								<td align="right" class="right_only" width="'.$cellWidthEarn.'">'.$this->moneyFormat($this->toNegative($val['TA']['LateUT'])).'&nbsp;</td>
								<td align="right" class="right_only" width="'.$cellWidthEarn.'">'.$this->moneyFormat($this->toNegative($val['TA']['Absences'])).'&nbsp;</td>
								<td align="right" class="right_only" width="'.$cellWidthEarn.'">'.$this->moneyFormat($val['TA']['ot']).'&nbsp;</td>
								<td align="right" class="right_only" width="'.$cellWidthEarn.'">'.$this->moneyFormat($val['cola']).'&nbsp;</td>
							</tr>';
						$ctr=1;
						$max = $maxEarnings;
						if(count($earnArr) > 0){
							foreach($earnArr as $key => $earn) {
								if($ctr==1){
									$main .= '<tr>';
								}for($am=0;$am<count($amendments);$am++) {
									if($earn === $amendments[$am]['psa_id']){
										$earnVal = $val['earnings'][$earn];
									}
								}
								if($earn == 36 and $val['earnings'][$earn] == 0){$earnVal = $val['earnings']['comallow'];}
								//if($earn == 36){
								//	$earnVal = $val['earnings']['comallow'];
								//}
			                	
					   			$main .= '<td class="righttop" align="right" width="'.$cellWidthEarn.'">'.$this->moneyFormat($earnVal).'&nbsp;</td>';
								if($ctr>=$max){
									$main .= '</tr>';
									$ctr=0;
								}
								$ctr++;
							}
            			}
						if($ctr <= $max && $ctr != 1){
							$span = ($max - $ctr) + 1;
							$main .= '<td colspan="'.$span.'" class="righttop">&nbsp;</td></tr>';	
						}
							$main .= '</table></td>
							<td align="right" class="rightside" width="50px">'.$this->moneyFormat($val['totalearning']).'</td>
							<td class="inside_tbl" style="border-top:1px solid black;" width="'.$maxWidthDed.'"><table style="border-collapse:collapse;" width="100%">';
						$ctr=1;
						$max = $maxDeductions;
						$trace=1;
						foreach($dedArr as $key => $ded) {
							if($ctr==1){
								$main .= '<tr>';
							}
						
							if($trace<=ceil(count($deductions)/($traceEarn))){
								$class = "right_only";
							} else {
								$class = "righttop";
							}
							for($lo=0;$lo<count($deductions);$lo++) {
								if($ded === $deductions[$lo]['psa_id']){
									$dedVal = $val['deductions'][$ded];
								}
							}
							
							if($ded == 7){
								$dedVal = $val['cont']['sss'];
							} elseif($ded == 14){
								$dedVal = $val['cont']['phic'];
							} elseif($ded == 15){
								$dedVal = $val['cont']['hdmf'];
							} elseif($ded == 8){
								$dedVal = $val['cont']['tax'];
							} elseif($ded == 51){
		                		$dedVal = $val['deductions']['TaxPayables'];
		               		}
				   			$main .= '<td class="'.$class.'" width="'.$cellWidthDed.'" align="right">'.$this->moneyFormat($this->toNegative($dedVal)).'&nbsp;</td>';
							if($ctr>$max){
								$main .= '</tr>';
								$ctr=0;
							}
							$ctr++;
							$trace++;
						}
						if($ctr >= $max){
							$span = ($max - $ctr) + 2;
							$main .= '<td colspan="'.$span.'" class="righttop">&nbsp;</td></tr>';	
						}
					$main .= '</table></td>
							<td align="right" class="rightside" width="50px">'.$this->moneyFormat($this->toNegative($val['totaldeduction'])).'</td>
							<td align="right" class="rightside" style="page-break-after:always;" width="60px">'.$this->moneyFormat($val['net']).'</td>
						</tr>';
            }
        }
        }
				
				$footer = '<tr><td colspan="11">&nbsp;</td></tr>
						<tr>
							<td colspan="4" style="border-top:2px solid black; vertical-align:top;"><strong><i>Grand Total&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;('.$seq_np.')</i></strong></td>
							<td align="right" class="bolditalic" style="border-top:2px solid black;" width="50px">'.$this->moneyFormat($basic).'</td>
							<td align="right" class="bolditalic" style="border-top:2px solid black;" width="50px">'.$this->moneyFormat($gross).'</td>
							<td style="border-top:2px solid black;" width="'.$maxWidthEarn.'"><table style="border-collapse:collapse;" width="100%">
							<tr>
								<td align="right" class="bolditalic" width="'.$cellWidthEarn.'">'.$this->moneyFormat($this->toNegative($LateUT)).'</td>
								<td align="right" class="bolditalic" width="'.$cellWidthEarn.'">'.$this->moneyFormat($this->toNegative($Absences)).'</td>
								<td align="right" class="bolditalic" width="'.$cellWidthEarn.'">'.$this->moneyFormat($ot).'</td>
								<td align="right" class="bolditalic" width="'.$cellWidthEarn.'">'.$this->moneyFormat($cola).'</td>
							</tr>';
						$ctr=1;
						$max = $maxEarnings;
						if(count($earnArr) > 0){
							foreach($earnArr as $key => $earnTotal) {
								if($ctr==1){
									$footer.= '<tr>';
								}
								for($grandAm=0;$grandAm<count($earnings);$grandAm++) {
									if($earnTotal === $earnings[$grandAm]['psa_id']){
										$earnGrand = $grandTotal[$earnings[$grandAm]['psa_id']];
									} 
								}
								//if($earnTotal == 36){
								//	$earnGrand = $commallow;
								//}
					   			$footer.= '<td class="bolditalic" align="right" width="'.$cellWidthEarn.'">'.$this->moneyFormat($earnGrand).'&nbsp;</td>';
								if($ctr>=$max){
									$footer .= '</tr>';
									$ctr=0;
								}
								$ctr++;
							}
						}
						if($ctr <= $max && $ctr != 1){
							$span = ($max - $ctr) + 1;
							$footer .= '<td colspan="'.$span.'">&nbsp;</td></tr>';	
						}
							$footer .= '</table></td>
							<td align="right" class="bolditalic" style="border-top:2px solid black;" width="50px">'.$this->moneyFormat($totalearning).'</td>
							<td style="border-top:2px solid black;" width="'.$maxWidthDed.'"><table style="border-collapse:collapse;" width="100%">';
						$ctr=1;
						$max = $maxDeductions;
						$trace=1;
						foreach($dedArr as $key => $dedTotal) {
							if($ctr==1){
								$footer .= '<tr>';
							}
							for($grandD=0;$grandD<count($deductions);$grandD++) {
								if($dedTotal === $deductions[$grandD]['psa_id']){
									$dedValGrand = $grandTotal[$deductions[$grandD]['psa_id']];
								} 
							}
							if($dedTotal == 7){
								$dedValGrand = $sss;
							} elseif($dedTotal == 14){
								$dedValGrand = $phic;
							} elseif($dedTotal == 15){
								$dedValGrand = $pagibig;
							} elseif($dedTotal == 8){
								$dedValGrand = $tax;
							} elseif($dedTotal == 51){
		                		$dedValGrand = $taxpayables;
							}
				   			$footer .= '<td class="bolditalic" align="right" width="'.$cellWidthDed.'">'.$this->moneyFormat($this->toNegative($dedValGrand)).'&nbsp;</td>';
							if($ctr>$max){
								$footer .= '</tr>';
								$ctr=0;
							}
							$ctr++;
							$trace++;
						}
						if($ctr >= $max){
							$span = ($max - $ctr) + 2;
							$footer .= '<td colspan="'.$span.'">&nbsp;</td></tr>';	
						}
					$footer .= '</table></td>
							<td align="right" class="bolditalic" width="50px" style="border-top:2px solid black;" width="50px">'.$this->moneyFormat($this->toNegative($totaldeduc)).'</td>
							<td align="right" class="bolditalic" style="border-top:2px solid black;">'.$this->moneyFormat($net).'</td>
						</tr>
						<tr><td colspan="11" style="font-size:12px;">Date Printed: '.date("M/d/Y h:i A").'</td></tr>
				</table>';
		$content .= $header . $main. $footer;
		$this->createPDF($content, $paper, $orientation, $filename);
	}
	
	function getColumns($psa_type){
		$sql = "select distinct psa_clsfication from payroll_ps_account";
		$rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF) {
			$psa_clsfication = $rsResult->fields['psa_clsfication'];
			$sql2 = "select psa_id, psa_name, psa_type, psa_clsfication from payroll_ps_account 
			where psa_type='$psa_type' and psa_clsfication='$psa_clsfication' and psa_status='1' and psa_id!='52' order by psa_order";
			$rsResult2 = $this->conn->Execute($sql2);
			while(!$rsResult2->EOF){
				$arr[] = $rsResult2->fields;
				$rsResult2->MoveNext();
			}
			$rsResult->MoveNext();
		}
		return $arr;
	}
	
	function getAmendments($payperiod_id = null, $psa_type){
		//if($effect_date === null){
		$qry[] = "b.payperiod_id = '$payperiod_id'";
		$qry[] = "c.psa_type = '$psa_type'";
		$qry[] = "a.psamend_effect_date <= b.payperiod_end_date";
		$qry[] = "a.psamend_effect_date >= b.payperiod_start_date";
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";
		$sql = "select a.psa_id from payroll_ps_amendment a
				inner join payroll_pay_period b 
				inner join payroll_ps_account c on (c.psa_id=a.psa_id)
				$criteria
				order by psa_order";
		$rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF) {
			$arr[] = $rsResult->fields;
			$rsResult->MoveNext();
		}
		return $arr;
	}
	
	function removeElementWithValue($array, $key, $value){
	     foreach($array as $subKey => $subArray){
	          if($subArray[$key] == $value){
	               unset($array[$subKey]);
	               $array = array_values($array);
	          }
	     }
	     return $array;
	}
	
	function checkEarningColumnIfZero($psa_id, $type_, $gData = array(), $trans_date){
		$payperiod_id = $gData['payperiod_id'];
		$checkSql = "select psa_id from payroll_ps_account where psa_id='$psa_id' and psa_type='2'";
		$checkResult = $this->conn->Execute($checkSql);
		if(!empty($checkResult->fields['psa_id'])){
			return false;
		} else {
			if($type_ == '2'){
			$sql = "select sum(c.amendemp_amount) as total from payroll_ps_amendment a 
					inner join payroll_pay_period b
					inner join payroll_ps_amendemp c on (c.psamend_id=a.psamend_id)
					WHERE b.payperiod_id='$payperiod_id' and a.psa_id='$psa_id' and a.psamend_effect_date <= b.payperiod_end_date and a.psamend_effect_date >= b.payperiod_start_date";
			} else {
			$sql = "select sum(ppe.ppe_amount) as total from payroll_paystub_entry ppe
					left join payroll_paystub_report ppr on (ppr.paystub_id=ppe.paystub_id)
					left join payroll_pay_period ppp on (ppp.payperiod_id=ppr.payperiod_id)
					WHERE ppp.payperiod_trans_date LIKE '$trans_date%' and ppe.psa_id='$psa_id'";
			}
			$rsResult = $this->conn->Execute($sql);
			while(!$rsResult->EOF) {
				if($rsResult->fields['total'] !=0 or $rsResult->fields['total'] != NULL){
					return true;
				} else {
					return false;
				}
			}
		}
	}
	
	function checkDeductionColumnIfZero($psa_id, $trans_date, $gData = array()){
		$payperiod_id = $gData['payperiod_id'];
		$sql = "select psa_id, psa_name, psa_type, psa_clsfication from payroll_ps_account 
				where psa_id='$psa_id' and psa_status='1' order by psa_order";
		$rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF) {
			$sqlAm = "select psa_id from payroll_ps_amendment where psa_id='$psa_id'";
			$rsResultAm = $this->conn->Execute($sqlAm);
			if(!empty($rsResultAm->fields['psa_id'])){
				$checkSql = "select sum(c.amendemp_amount) as total from payroll_ps_amendment a 
				inner join payroll_pay_period b
				inner join payroll_ps_amendemp c on (c.psamend_id=a.psamend_id)
				WHERE b.payperiod_id='$payperiod_id' and a.psa_id='$psa_id' and a.psamend_effect_date <= b.payperiod_end_date and a.psamend_effect_date >= b.payperiod_start_date";
				$rsResult2 = $this->conn->Execute($checkSql);
				while(!$rsResult2->EOF) {
					if($rsResult2->fields['total'] != 0 or $rsResult2->fields['total'] != NULL){
						return true;
					} else {
						return false;
					}
				}
			} else {
			//psa_clsfication => 5; for loan
				if($rsResult->fields['psa_clsfication'] == '5'){
					$checkSql = "select sum(a.loansum_payment) as total from loan_detail_sum a
									inner join loan_info b on (b.loan_id=a.loan_id)
									left join payroll_paystub_report ppr on (ppr.paystub_id=a.paystub_id)
									left join payroll_pay_period ppp on (ppp.payperiod_id=ppr.payperiod_id)
									WHERE ppp.payperiod_trans_date LIKE '$trans_date%' and b.psa_id='$psa_id'";
				} else {
				//psa_clsfication != 5; not loan
					$checkSql = "select sum(ppe.ppe_amount) as total from payroll_paystub_entry ppe
									left join payroll_paystub_report ppr on (ppr.paystub_id=ppe.paystub_id)
									left join payroll_pay_period ppp on (ppp.payperiod_id=ppr.payperiod_id)
									WHERE ppp.payperiod_trans_date LIKE '$trans_date%' and ppe.psa_id='$psa_id'";
				}
				$rsResult2 = $this->conn->Execute($checkSql);
				while(!$rsResult2->EOF) {
					if($rsResult2->fields['total'] != 0 or $rsResult2->fields['total'] != NULL){
						return true;
					} else {
						return false;
					}
				}
			}
		}
	}
	
	function getPayrollAcountNameByPsaID($psa_id){
		$sql = "select psa_name from payroll_ps_account where psa_id='$psa_id'";
		$rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF) {
			return $rsResult->fields['psa_name'];
		}
	}
	
	function toNegative($num){
		$new = -$num;
		return $new;
	}
	
	function sortByPsaOrder($arr = array()){
		$tempEarnings = $arr;
		unset($arr);
		for($tempCtr=0;$tempCtr<count($tempEarnings);$tempCtr++){
			$storeVal[] = $tempEarnings[$tempCtr]['psa_id'];
		}
		$tempPsaID = implode(",",$storeVal);
		$sqlOrder = "select psa_id from payroll_ps_account 
						where psa_id in ($tempPsaID) 
						order by psa_order, psa_name;";
		$rsResultOrder = $this->conn->Execute($sqlOrder);
		while(!$rsResultOrder->EOF) {
			$earnings[] = $rsResultOrder->fields;
			$rsResultOrder->MoveNext();
		}
		return $earnings;
	}
}
?>