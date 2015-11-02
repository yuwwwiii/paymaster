<?php
/**
 * Initial Declaration
 */
require_once(SYSCONFIG_CLASS_PATH."util/pdf.class.php");
require_once(SYSCONFIG_CLASS_PATH.'admin/reports/sss.class.php');
//require_once(SYSCONFIG_CLASS_PATH.'admin/reports/pdfreport.class.php');

/**
 * Class Module
 *
 * @author  JIM
 *
 */
class clsPayrollSummary{

	var $conn;
	var $fieldMap;
	var $Data;

	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsPayrollSummary object
	 */
	function clsPayrollSummary($dbconn_ = null){
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
	 * @param string $id_
	 * @return array
	 */
	function dbFetch($id_ = ""){
		$sql = "SELECT * from payroll_pay_period a JOIN payroll_pay_period_sched b on (b.pps_id = a.pps_id) WHERE payperiod_id = ?";
		$rsResult = $this->conn->Execute($sql,array($id_));
		if(!$rsResult->EOF){
			return $rsResult->fields;
		}
	}

	function getPayperiod(){
		$listpgroup = $_SESSION[admin_session_obj][user_paygroup_list2];
		IF(count($listpgroup)>0){
			$qry[] = "pps_id in (".$listpgroup.")";//pay group that can access
		}
		$criteria = (count($qry)>0)?" WHERE ".implode(" AND ",$qry):"";
        $sql = "SELECT * FROM payroll_pay_period_sched $criteria";
		$rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF){
			$arrData[$rsResult->fields['pps_id']] =  $rsResult->fields;
            $rsResult->MoveNext();
		}
//		printa($arrData); exit;
        return $arrData;
	}

    function dbFetchHeadDepartment(){
        $sqlparent = "select ud_id from app_userdept where ud_parent = 0 and ud_id != 1";
        $rsResult = $this->conn->Execute($sqlparent);
		if(!$rsResult->EOF){
			$parent_id = $rsResult->fields['ud_id'];

            $sql = "select *
				from app_userdept a
				/*inner join azt_hris_db.hris_branch_dept_rel b on (a.ud_id = b.ud_id)*/
                    where a.ud_parent = $parent_id and a.ud_id != 1 ";
            $rsResult_ = $this->conn->Execute($sql);
            while(!$rsResult_->EOF){
                $arrData[$rsResult_->fields['ud_id']] = $rsResult_->fields;
                $rsResult_->MoveNext();
            }
            return $arrData;
        }
	}

    function dbFetch_paystub($payperiod_id = "",$emp_id = "", $return = ""){
        $qry = array();
        if($payperiod_id == ""){ return '0.00'; }
        if($emp_id == ""){ 
        	return '0.00';
        }else{
            $emp_id = substr($emp_id, 0, -1);
        }
        $qry[] = "a.payperiod_id = $payperiod_id";
        $qry[] = "a.emp_id in ($emp_id)";
        $qry[] = "b.pp_stat_id =3";
		$qry[] = "a.ppr_status =1";
		$qry[] = "a.ppr_isdeleted =0";
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";
		$sql = "SELECT * FROM payroll_paystub_report a JOIN payroll_pay_period b on (a.payperiod_id=b.payperiod_id) $criteria";
		$rsResult = $this->conn->Execute($sql);
        $sum_ot = 0;
        $sum_hol = 0;
        $sum_tax = 0;
        $sum_sss = 0;
        $sum_phic = 0;
        $sum_hdmf = 0;
        $sum_basic = 0;
        while (!$rsResult->EOF){
            $arrResult[$rsResult->fields['emp_id']] = unserialize($rsResult->fields['ppr_paystubdetails']);
            $sum_ot += $arrResult[$rsResult->fields['emp_id']]['paystubdetail']['paystubaccount']['earning']['OT']['SumAllOTRate'];
            $sum_hol += $arrResult[$rsResult->fields['emp_id']]['paystubdetail']['paystubaccount']['earning']['Holiday']['SumAllHolRate'];
            $sum_tax += $arrResult[$rsResult->fields['emp_id']]['paystubdetail']['paystubaccount']['pstotal']['W/H Tax'];
            $sum_sss += $arrResult[$rsResult->fields['emp_id']]['paystubdetail']['paystubaccount']['deduction']['SSS'];
            $sum_phic += $arrResult[$rsResult->fields['emp_id']]['paystubdetail']['paystubaccount']['deduction']['PhilHealth'];
            $sum_hdmf += $arrResult[$rsResult->fields['emp_id']]['paystubdetail']['paystubaccount']['deduction']['Pag-ibig'];
            $sum_basic += $arrResult[$rsResult->fields['emp_id']]['paystubdetail']['paystubaccount']['earning']['basic'];
			$rsResult->MoveNext();
		}
//        printa($arrResult);
        if($return == 1){//1 = ot
            return $sum_ot;
        }else if($return == 2){//2 = hol
            return $sum_hol;
        }else if($return == 3){//3 = tax
            return $sum_tax;
        }else if($return == 4){//4 = sss
            return $sum_sss;
        }else if($return == 5){//5 = phic
            return $sum_phic;
        }else if($return == 7){
            return $sum_basic;
        }else{//6 = hdmf
            return $sum_hdmf;
        }
	}

    function getsumSalariesBonuses($emp_ids = ""){
        if($emp_ids == ""){ return '0.00'; }
        $emp_ids = substr($emp_ids, 0, -1);
        $sql = "select sum(compensation_basic_salary/2) as totalsalary
                from hris_emp_compensation
                where emp_id in ($emp_ids)";
        $rsResult = $this->conn->Execute($sql);
        $sum_salary = $rsResult->fields['totalsalary'];
        return $sum_salary;
    }
    
    function getsumTranspoAllowance($emp_ids = ""){
        if($emp_ids == ""){ return '0.00'; }
        $emp_ids = substr($emp_ids, 0, -1);
        $sql2 = "select sum(amount) as totalbenefits
                from hris_emp_master_ben_rel
                where emp_id in ($emp_ids) and ben_id = 1
                ";
        $rsResult2 = $this->conn->Execute($sql2);
        $sum_benefit = $rsResult2->fields['totalbenefits']/2;
        return $sum_benefit;
    }

    function getSumProvidentPerraUnionCoopRemittances($payperiod_id = "", $emp_id_ = null, $psa_id = ""){
		if (is_null($emp_id_)) {
			return '0.00';
		}else{
            $emp_id_ = substr($emp_id_, 0, -1);
        }
		$qry = array();
		$qry[] = "e.payperiod_id =$payperiod_id";
		$qry[] = "e.pp_stat_id =3";
		$qry[] = "c.ppr_status =1";
		$qry[] = "c.ppr_isdeleted =0";
		$qry[] = "b.psa_id =$psa_id";
		$qry[] = "a.emp_id in ($emp_id_)";
		$criteria = count($qry)>0 ? " where ".implode(' and ',$qry) : '';
		$sql = "select sum(b.ppe_amount) as ppe_amount
				from payroll_pay_stub a
				inner join payroll_paystub_entry b on (a.paystub_id = b.paystub_id)
				inner join payroll_paystub_report c on (a.paystub_id = c.paystub_id)
                inner join payroll_pay_period e on (e.payperiod_id = a.payperiod_id)
				$criteria";
		$rsResult = $this->conn->Execute($sql);
		if(!$rsResult->EOF){
			return $rsResult->fields['ppe_amount'];
		}else{
            return '0.00';
        }
	}

    function getSumProvidentLoanPayment($payperiod_id = "", $emp_id_ = null){
        if (is_null($emp_id_)) {
			return '0.00';
		}else{
            $emp_id_ = substr($emp_id_, 0, -1);
        }
		$sql = "select sum(c.pd_amount) as pd_amount
				from payroll_provident_header a
				inner join payroll_provident_loan_type b on (a.plt_id = b.plt_id)
                inner join payroll_provident_detail c on (a.ph_id = c.ph_id)
                INNER JOIN payroll_pay_stub d ON ( d.paystub_id = c.paystub_id )
                inner join payroll_paystub_report e on (c.paystub_id = e.paystub_id)
                INNER JOIN payroll_pay_period f ON ( f.payperiod_id = d.payperiod_id )
				where /*a.ph_status = 1 and*/ a.emp_id in ($emp_id_) and f.pp_stat_id = 3 and e.ppr_isdeleted = 0 and e.ppr_status = 1
                and f.payperiod_id = $payperiod_id";
		$rsResult = $this->conn->Execute($sql);
		if(!$rsResult->EOF){
			return $rsResult->fields['pd_amount'];
		}else{
            return "0.00";
        }
	}

    function getSumLoanPayments($payperiod_id = "", $emp_id_ = "",$rlt_id= ""){
		if ($emp_id_ == "") {
			return '0.00';
		}else{
            $emp_id_ = substr($emp_id_, 0, -1);
        }
		if ($payperiod_id == "") {
			return '0.00';
		}
		$sql = "SELECT sum(b.rd_amount) as rd_amount
				FROM payroll_regularloan_header a
				INNER JOIN payroll_regularloan_detail b ON ( a.rh_id = b.rh_id )
				INNER JOIN payroll_pay_stub c ON ( b.paystub_id = c.paystub_id )
                inner join payroll_paystub_report e on (c.paystub_id = e.paystub_id)
				INNER JOIN payroll_pay_period d ON ( d.payperiod_id = c.payperiod_id )
				where a.emp_id in ($emp_id_) and d.pp_stat_id = 3 and e.ppr_isdeleted = 0 and e.ppr_status = 1 and a.rlt_id = $rlt_id /*and a.rh_status = 1*/
                and d.payperiod_id = $payperiod_id";
		$rsResult = $this->conn->Execute($sql);
		if(!$rsResult->EOF){
			return $rsResult->fields['rd_amount'];
		}else{
            return "0.00";
        }
	}

	function generateReport($gData = array()){
		$y = "".$gData['year']."-".$gData['month']."-01";
		$enddate = dDate::getEndMonthEpoch(dDate::parseDateTime($y));
		$startdate = dDate::getBeginMonthEpoch(dDate::parseDateTime($y));
        $headDept = $this->dbFetchHeadDepartment();
			foreach ($headDept as $keyheaddept){
                $headDept[$keyheaddept['ud_id']]['ud_ids'] = clsPayrollSummary::getDeptChildrenperHeadDept($this->conn,array($keyheaddept));
                $headDept[$keyheaddept['ud_id']]['employee'] = $this->getEmpPerDept($headDept[$keyheaddept['ud_id']]['ud_ids'], $gData['type']);
                //salaries,ots,holidays,benifits
                $headDept[$keyheaddept['ud_id']]['details']['salaries_bonuses'] = $this->dbFetch_paystub($gData['payperiod_id'],$headDept[$keyheaddept['ud_id']]['employee'],7);
                $headDept[$keyheaddept['ud_id']]['details']['ot'] = $this->dbFetch_paystub($gData['payperiod_id'],$headDept[$keyheaddept['ud_id']]['employee'],1);
                $headDept[$keyheaddept['ud_id']]['details']['hol'] = $this->dbFetch_paystub($gData['payperiod_id'],$headDept[$keyheaddept['ud_id']]['employee'],2);
                $headDept[$keyheaddept['ud_id']]['details']['transpo'] = $this->getsumTranspoAllowance($headDept[$keyheaddept['ud_id']]['employee']);
                $headDept[$keyheaddept['ud_id']]['details']['otherbenefits'] = $this->getAdvisoryPerEmpoyee($headDept[$keyheaddept['ud_id']]['employee']);
                $headDept[$keyheaddept['ud_id']]['details']['other_income'] = $this->getSemiMonthlyOtherBenefits($headDept[$keyheaddept['ud_id']]['employee'],$gData['payperiod_id']);
                $headDept[$keyheaddept['ud_id']]['details']['substitution'] = $this->getSubstitution($headDept[$keyheaddept['ud_id']]['employee'],$gData['payperiod_id']);
                //government remittances
                $headDept[$keyheaddept['ud_id']]['details']['tax'] = $this->dbFetch_paystub($gData['payperiod_id'],$headDept[$keyheaddept['ud_id']]['employee'],3);
                $headDept[$keyheaddept['ud_id']]['details']['sss'] = $this->dbFetch_paystub($gData['payperiod_id'],$headDept[$keyheaddept['ud_id']]['employee'],4);
                $headDept[$keyheaddept['ud_id']]['details']['phic'] = $this->dbFetch_paystub($gData['payperiod_id'],$headDept[$keyheaddept['ud_id']]['employee'],5);
                $headDept[$keyheaddept['ud_id']]['details']['hdmf'] = $this->dbFetch_paystub($gData['payperiod_id'],$headDept[$keyheaddept['ud_id']]['employee'],6);
                //company remittances
                $headDept[$keyheaddept['ud_id']]['details']['provident'] = $this->getSumProvidentPerraUnionCoopRemittances($gData['payperiod_id'],$headDept[$keyheaddept['ud_id']]['employee'],27);
                $headDept[$keyheaddept['ud_id']]['details']['peraa'] = $this->getSumProvidentPerraUnionCoopRemittances($gData['payperiod_id'],$headDept[$keyheaddept['ud_id']]['employee'],28);
                $headDept[$keyheaddept['ud_id']]['details']['union'] = $this->getSumProvidentPerraUnionCoopRemittances($gData['payperiod_id'],$headDept[$keyheaddept['ud_id']]['employee'],12);
                $headDept[$keyheaddept['ud_id']]['details']['coop'] = $this->getSumProvidentPerraUnionCoopRemittances($gData['payperiod_id'],$headDept[$keyheaddept['ud_id']]['employee'],29);
                //comnpany/governemtn loans
                $headDept[$keyheaddept['ud_id']]['details']['sss_loan'] = $this->getSumLoanPayments($gData['payperiod_id'],$headDept[$keyheaddept['ud_id']]['employee'],2);
                $headDept[$keyheaddept['ud_id']]['details']['housing_loan'] = $this->getSumLoanPayments($gData['payperiod_id'],$headDept[$keyheaddept['ud_id']]['employee'],3);
                $headDept[$keyheaddept['ud_id']]['details']['pagibig_loan'] = $this->getSumLoanPayments($gData['payperiod_id'],$headDept[$keyheaddept['ud_id']]['employee'],4);
                $headDept[$keyheaddept['ud_id']]['details']['peraa_loan'] = $this->getSumLoanPayments($gData['payperiod_id'],$headDept[$keyheaddept['ud_id']]['employee'],5);
                $headDept[$keyheaddept['ud_id']]['details']['provident_loan'] = $this->getSumProvidentLoanPayment($gData['payperiod_id'],$headDept[$keyheaddept['ud_id']]['employee']);
                $headDept[$keyheaddept['ud_id']]['details']['coop_loan'] = $this->getSumLoanPayments($gData['payperiod_id'],$headDept[$keyheaddept['ud_id']]['employee'],9);
                $headDept[$keyheaddept['ud_id']]['details']['insurance_payable'] = $this->getSumLoanPayments($gData['payperiod_id'],$headDept[$keyheaddept['ud_id']]['employee'],7);
                $headDept[$keyheaddept['ud_id']]['details']['union_payable'] = $this->getSumLoanPayments($gData['payperiod_id'],$headDept[$keyheaddept['ud_id']]['employee'],13);
                $headDept[$keyheaddept['ud_id']]['details']['personal'] = $this->getSumLoanPayments($gData['payperiod_id'],$headDept[$keyheaddept['ud_id']]['employee'],6);
                $headDept[$keyheaddept['ud_id']]['details']['nhmfc'] = $this->getSumLoanPayments($gData['payperiod_id'],$headDept[$keyheaddept['ud_id']]['employee'],14);
                $headDept[$keyheaddept['ud_id']]['details']['adv_child'] = $this->getSumLoanPayments($gData['payperiod_id'],$headDept[$keyheaddept['ud_id']]['employee'],8);
                $headDept[$keyheaddept['ud_id']]['details']['ar_ledonne'] = $this->getSumLoanPayments($gData['payperiod_id'],$headDept[$keyheaddept['ud_id']]['employee'],10);
                $headDept[$keyheaddept['ud_id']]['details']['ar_others'] = $this->getSumLoanPayments($gData['payperiod_id'],$headDept[$keyheaddept['ud_id']]['employee'],11);
                $headDept[$keyheaddept['ud_id']]['details']['death_cont'] = $this->getDeathCont($headDept[$keyheaddept['ud_id']]['employee'], $gData['payperiod_id']);
        	}
//		printa($headDept); exit;
		return $headDept;
	}

    function getDeathCont($emp_id_ = "",$payperiod_id=""){
        $arrData = array();
		$qry = array();
		if ($payperiod_id == "") { return '0.00'; }
		if($emp_id_ == ""){
            return '0.00';
        }else{
            $emp_id_ = substr($emp_id_, 0, -1);
        }
		$qry[] = "e.payperiod_id =$payperiod_id";
		$qry[] = "b.psa_id =31";
		$qry[] = "e.pp_stat_id =3";
		$qry[] = "c.ppr_isdeleted =0";
		$qry[] = "c.ppr_status =1";
		$qry[] = "b.emp_status = 1";
		$qry[] = "b.emp_id in ($emp_id_)";
		$criteria = count($qry)>0 ? " where ".implode(' and ',$qry) : '';
		$sql = "select sum(b.ppe_amount) as ppe_amount
                from payroll_pps_user a
                inner join azt_hris_db.hris_emp_masterfile b on (b.emp_id =a.emp_id)
                inner join azt_hris_db.hris_emp_pinfo_master_rel c on (b.emp_id = c.emp_id)
                inner join azt_hris_db.hris_emp_personalinfo d on (d.pi_id = c.pi_id)
                inner join azt_hris_db.hris_branch_dept_rel bdrel on (bdrel.bdrel_id = b.bdrel_id)
                inner join payroll_pay_stub pps on (b.emp_id = pps.emp_id)
                inner join payroll_paystub_entry b on (pps.paystub_id = b.paystub_id)
                inner join payroll_paystub_report c on (pps.paystub_id = c.paystub_id)
                inner join payroll_ps_account d on (d.psa_id = b.psa_id)
                inner join payroll_pay_period e on (e.payperiod_id = pps.payperiod_id)
				$criteria
                group by b.emp_id
                order by d.pi_lastname";
		$rsResult = $this->conn->Execute($sql);
		if(!$rsResult->EOF){
            return $rsResult->fields['ppe_amount'];
        }else{
            return '0.00';
        }
	}

    function getAdvisoryPerEmpoyee($emp_id_ = ""){
        if($emp_id_ == ""){
            return '0.00';
        }else{
            $emp_id_ = substr($emp_id_, 0, -1);
        }
		$sql = "select sum(amount) as  amount
                from azt_hris_db.hris_emp_master_ben_rel
                where emp_id in ($emp_id_) and ben_id != 1";
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

    function getSemiMonthlyOtherBenefits($emp_id_ = "", $payperiod_id_ = ""){
        if($emp_id_ == ""){
            return '0.00';
        }else{
            $emp_id_ = substr($emp_id_, 0, -1);
        }
        if($payperiod_id_ == ""){ return '0.00'; }
        //fix me: static paystub sccounts
        //since pede p magdagdag ng client ng unlimited ps accounts
        //finilter n ko n lng bases sa existing
        //reference payroll_ps_account table
        $not_in = "(1,23,24,6,35,3,39)";
        $sql = "select sum(b.ppe_amount) as ppe_amount from payroll_pay_stub a
                inner join payroll_paystub_entry b on (a.paystub_id = b.paystub_id)
                inner join payroll_paystub_report c on (a.paystub_id = c.paystub_id)
                where c.ppr_status = 1 and a.payperiod_id = $payperiod_id_ and a.emp_id in ($emp_id_) and b.psa_id in(
                    select psa_id
                    from payroll_ps_account
                    where psa_type = 1 and psa_id not in $not_in)
                and c.ppr_isdeleted = 0 group by a.emp_id";
        $rsResult = $this->conn->Execute($sql);
        if(!$rsResult->EOF){
            return $rsResult->fields['ppe_amount'];
        }else{
            return '0.00';
        }
    }

    function getSubstitution($emp_id = "", $payperiod_id=""){
        if($emp_id == ""){
            return '0.00';
        }else{
            $emp_id = substr($emp_id, 0, -1);
        }
        if($payperiod_id == ""){
            return 0.00;
        }
        $sql = "select b.ppe_amount,d.psa_name,hem.emp_id
                from payroll_pay_stub a
                inner join payroll_paystub_entry b on (a.paystub_id = b.paystub_id)
                inner join payroll_paystub_report c on (a.paystub_id = c.paystub_id)
                inner join payroll_ps_account d on (d.psa_id = b.psa_id)
                inner join payroll_pay_period e on (e.payperiod_id = a.payperiod_id)
                inner join azt_hris_db.hris_emp_masterfile hem on (a.emp_id = hem.emp_id)
                where e.pp_stat_id =3 and c.ppr_status =1 and c.ppr_isdeleted =0 and a.emp_id in ($emp_id)
                and b.psa_id =39 and hem.emp_status=1 and a.payperiod_id = $payperiod_id
                group by a.emp_id";
        $rsResult = $this->conn->Execute($sql);
		if(!$rsResult->EOF){
             return $rsResult->fields['ppe_amount'];
		}else{
             return '0.00';
        }
    }

    function getDeptChildrenperHeadDept($dbconn_ = null,$arrMenu_ = array(), $isChild_ = false, $level = 0){
//        printa($arrMenu_);
        if(count($arrMenu_) > 0){
			$arrCtr = 0;
			foreach ($arrMenu_ as $key => $value) {
				$sql = "select a.* from azt_hris_db.app_userdept a where a.ud_parent=? order by a.ud_name";
				$rsResult = $dbconn_->Execute($sql,array($value['ud_id']));

				$arrMenuIn = array();
				while(!$rsResult->EOF){
					$arrMenuIn[] = $rsResult->fields;
					$rsResult->MoveNext();
				}
				if(count($arrMenuIn) > 0){
                    $mnuData .= clsPayrollSummary::getDeptChildrenperHeadDept($dbconn_, $arrMenuIn, true, $level+1);
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

	function getEmpPerDept($ud_id = "", $type = ""){

        $arrData = array();
		$qry = array();

        if ($ud_id == "") {
			return $arrData;
		}

        if($type == 10){
            
            $qry[] = "h.emptype_id = 3";
            $qry[] = "a.pps_id = 1";
            
        }else if($type == 20){

            $qry[] = "h.emptype_id != 3";
            $qry[] = "a.pps_id = 1";

        }else{

            $qry[] = "h.emptype_id != 3";
            $qry[] = "a.pps_id != 1";
        }

		$qry[] = "f.ud_id in ($ud_id)";
        $qry[] = "b.emp_status = 1";
        
		$criteria = count($qry)>0 ? " where ".implode(' and ',$qry) : '';
		$sql = "select concat(d.pi_lastname,', ',d.pi_firstname,' ',concat(RPAD(d.pi_middlename,1,'?'),'.')) as name,b.emp_no,f.ud_name,b.emp_id,b.emp_no
				,d.pi_sss,d.pi_pagibigno,d.pi_philhealthno, g.compensation_basic_salary
				from payroll_pps_user a
				inner join azt_hris_db.hris_emp_masterfile b on (b.emp_id =a.emp_id)
				inner join azt_hris_db.hris_emp_pinfo_master_rel c on (b.emp_id = c.emp_id)
				inner join azt_hris_db.hris_emp_personalinfo d on (d.pi_id = c.pi_id)
				inner join azt_hris_db.hris_branch_dept_rel e on (e.bdrel_id = b.bdrel_id)
				inner join azt_hris_db.app_userdept f on (f.ud_id = e.ud_id)
				inner join azt_hris_db.hris_emp_compensation g on (b.emp_id = g.emp_id)
				inner join azt_hris_db.hris_jobposition h on (h.jobpos_id=b.jobpos_id)

				$criteria
                group by b.emp_id
                order by d.pi_lastname
				";
		$rsResult = $this->conn->Execute($sql);
		while (!$rsResult->EOF) {
				$arrData[] = $rsResult->fields;
			
            $mnuData .= $rsResult->fields['emp_id'].",";
            $rsResult->MoveNext();
		}

        
		return $mnuData;
	}


    function getPDFResult($gData = array()){

        $orientation='L';
        $unit='mm';
        $format='LEGAL';
        $unicode=true;
        $encoding="UTF-8";

        $oPDF = new clsPDF($orientation, $unit, $format, $unicode, $encoding);

        $objClsSSS = new clsSSS($this->conn);
        $branch_details = $objClsSSS->dbfetchBranchDetails();

        $payperiod = $this->dbFetch($_GET['payperiod_id']);

        // set initila coordinates
        $coordX = 10;
        $coordY = 50;

        $oPDF->AliasNbPages();

        // set initial pdf page
        $oPDF->AddPage();
        $oPDF->SetFillColor(255,255,255);
        if($gData['type'] == 10){
            $title = 'OFFICERS';
        }else if($gData['type'] == 20){
            $title = 'ALL EMPLOYEE EXCEPT OFFICERS';
        }else{
            $title = 'ADDITIONAL';
        }
        $oPDF->SetFont('helvetica', '', '13');
        $oPDF->Text(142,15,$branch_details['branch_name']);
        $oPDF->SetFont('helvetica', '', '11');
        $oPDF->Text(149,20,$branch_details['branch_address']);

        $oPDF->SetFont('helvetica', 'B', '15');
        $oPDF->SetXY($coordX, 25);
        $oPDF->MultiCell($oPDF->getPageWidth()-15, 5, "P A Y R O L L  S U M M A R Y - ".$title,0,'C',1);

        $oPDF->SetFont('helvetica', '', '11');
        $oPDF->SetXY($coordX+93, 35);
        $oPDF->MultiCell(150, 5, "For the Period - ".date('M d, Y',dDate::parseDateTime($payperiod['payperiod_trans_date'])),0,'C',1,150);

        $arrData = $this->generateReport($gData);
//        printa($arrData);
        $oPDF->SetFont('helvetica', '', '7');
        if(count($arrData)>0){
            foreach ($arrData as $key => $val) {
                // reset coordinate X value every loop
                $coordX = 30;

                if($coordY==50){
                    //$oPDF->SetFillColor(222,212,212);
                    $oPDF->SetXY($coordX, $coordY);
                    $oPDF->MultiCell(15, 10, "8,000,100.00",1,'L',1);
                    $oPDF->SetXY($coordX+15, $coordY);
                    $oPDF->MultiCell(15, 10, "OT",1,'C',1);
                    $oPDF->SetXY($coordX+30, $coordY);
                    $oPDF->MultiCell(15, 10, "Holiday",1,'C',1);
                    $oPDF->SetXY($coordX+45, $coordY);
                    $oPDF->MultiCell(15, 10, "Tanspo Allowance",1,'C',1);
                    $oPDF->SetXY($coordX+60, $coordY);
                    $oPDF->MultiCell(15, 10, "Tax",1,'C',1);
                    $oPDF->SetXY($coordX+75, $coordY);
                    $oPDF->MultiCell(15, 10, "SSS Payable",1,'C',1);
                    $oPDF->SetXY($coordX+90, $coordY);
                    $oPDF->MultiCell(15, 10, "Philheath Payable",1,'C',1);
                    $oPDF->SetXY($coordX+105, $coordY);
                    $oPDF->MultiCell(15, 10, "Pagibig Payable",1,'C',1);
                    $oPDF->SetXY($coordX+120, $coordY);
                    $oPDF->MultiCell(15, 10, "Peraa Payable",1,'C',1);
                    $oPDF->SetXY($coordX+135, $coordY);
                    $oPDF->MultiCell(15, 10, "Provident Payable",1,'C',1);
                    $oPDF->SetXY($coordX+150, $coordY);
                    $oPDF->MultiCell(15, 10, "Union Payable",1,'C',1);
                    $oPDF->SetXY($coordX+165, $coordY);
                    $oPDF->MultiCell(15, 10, "Coop Payable",1,'C',1);
                    $oPDF->SetXY($coordX+180, $coordY);
                    $oPDF->MultiCell(15, 10, "SSS Loan",1,'C',1);
                    $oPDF->SetXY($coordX+195, $coordY);
                    $oPDF->MultiCell(15, 10, "Housing Loan",1,'C',1);
                    $oPDF->SetXY($coordX+210, $coordY);
                    $oPDF->MultiCell(15, 10, "Pagibig Loan",1,'C',1);
                    $oPDF->SetXY($coordX+225, $coordY);
                    $oPDF->MultiCell(15, 10, "Peraa Loan",1,'C',1);
                    $oPDF->SetXY($coordX+240, $coordY);
                    $oPDF->MultiCell(15, 10, "Provident Loan",1,'C',1);
                    $oPDF->SetXY($coordX+255, $coordY);
                    $oPDF->MultiCell(15, 10, "Coop Loan",1,'C',1);
                    $oPDF->SetXY($coordX+270, $coordY);
                    $oPDF->MultiCell(15, 10, "A/R LEdonne",1,'C',1);
                    $oPDF->SetXY($coordX+285, $coordY);
                    $oPDF->MultiCell(15, 10, "A/R Others",1,'C',1);
                    $oPDF->SetXY($coordX+300, $coordY);
                    $oPDF->MultiCell(15, 10, "Insurance",1,'C',1);
                    $oPDF->SetXY($coordX+315, $coordY);
                    $oPDF->MultiCell(15, 10, "Death Cont.",1,'C',1);

                    $coordY+=$oPDF->getFontSize()+2.5;

                }
            }
        }
       
        // get the pdf output
        $output = $oPDF->Output("monthly_remittances_provident.pdf");

        if(!empty($output)){
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
				$qry[] = "(payperiod_start_date like '%$search_field%' || payperiod_end_date like '%$search_field%' || payperiod_trans_date like '%$search_field%')";

			}
		}
        $qry[] = "am.pps_id = '".$_GET['pps_id']."'";
        $qry[] = "pp_stat_id = 3";
		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		 "payperiod_start_date"=>"payperiod_start_date"
		,"payperiod_end_date"=>"payperiod_end_date"
		,"payperiod_trans_date"=>"payperiod_trans_date"
		);

		if(isset($_GET['sortby'])){
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}else{
            $strOrderBy = " order by payperiod_trans_date";
        }

		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$viewLink = "";
		$editLink = "<a href=\"?statpos=payroll_summary&edit=','".$_GET['pps_id']."','&payperiod_id=',am.payperiod_id,'\"><img src=\"".SYSCONFIG_THEME_URLPATH.SYSCONFIG_THEME."/images/admin/edit.gif\" title=\"Edit\" hspace=\"2px\" border=0></a>";
//		$delLink = "<a href=\"?statpos=payroll_summary&delete=',am.mnu_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_THEME_URLPATH.SYSCONFIG_THEME."/images/admin/trash.gif\" title=\"Delete\" hspace=\"2px\"  border=0></a>";

		// SqlAll Query
		$sql = "select am.*, CONCAT('$viewLink','$editLink','$delLink') as viewdata
                from payroll_pay_period am
						$criteria
						$strOrderBy";

		// Sql query for paginator list
		// @note no need to use this. it replaced by sql function "FOUND_ROWS()"
		//$sqlcount = "select count(*) as mycount from app_modules $criteria";

		// Field and Table Header Mapping
		$arrFields = array(
		 "payperiod_start_date"=>"Start Date"
		,"payperiod_end_date"=>"End Date"
		,"payperiod_trans_date"=>"Transaction Date"
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
}
?>