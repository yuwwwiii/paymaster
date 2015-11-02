<?php
/**
 * Initial Declaration
 */
require_once(SYSCONFIG_CLASS_PATH.'util/pdf.class.php');
require_once(SYSCONFIG_CLASS_PATH.'admin/transaction/process_payroll.class.php');

/**
 * Class Module
 * @author Jason I. Mabignay
 */
class clsPayroll_Details{
	var $conn;
	var $fieldMap;
	var $Data;
	var $payslips;
	var $Data_;
	var $otDetail = array();
	var $taDetail = array();
	var $holidayDetail = array();
	var $premiumDetail = array();
	var $fieldMap_PP;

	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsPayroll_Details object
	 */
	function clsPayroll_Details($dbconn_ = null){
		$this->conn =& $dbconn_;
		$this->fieldMap = array(
		 "basic" => "basic"
		,"otamount" => "otamount"
		,"otbackpay" => "otbackpay"
		,"leavedec" => "leavedec"
		,"phicee" => "phicee"
		,"phicer" => "phicer"
		,"sssee" => "sssee"
		,"ssser" => "ssser"
		,"sssec" => "sssec"
		,"hdmfee" => "hdmfee"
		,"hdmfer" => "hdmfer"
		);
	}

	/**
	 * Get the records from the database
	 * @param string $id_
	 * @return array
	 */
	function dbFetch_Payslip($id_ = "", $emp_id_=""){
//		$this->conn->debug=1;
		$qry = array();
		if (!is_null($id_)) { 
			$qry[] = "a.ppr_id ='".$id_."'"; 
		}
		if(is_null($emp_id_)||$emp_id_=""){ 
			$qry[] = "a.emp_id ='".$emp_id_."'"; 
		}
		$criteria = (count($qry)>0)?" WHERE ".implode(" AND ",$qry):"";
		$sql = "SELECT a.*,b.payperiod_type,b.payperiod_period, b.pp_stat_id,DATE_FORMAT(b.payperiod_start_date, '%d %b %Y') as start_date, DATE_FORMAT(b.payperiod_end_date, '%d %b %Y') as end_date
					FROM payroll_paystub_report a
					JOIN payroll_pay_period b on (a.payperiod_id=b.payperiod_id)
					$criteria";
		$rsResult = $this->conn->Execute($sql);
		if(!$rsResult->EOF){
			$rsResult->fields['paystubdetails'] = unserialize($rsResult->fields['ppr_paystubdetails']);
			return $rsResult->fields;
		}
	}
	
	/**
	 * To get General Setup for ipay
	 * @param $set_name_
	 */
	function getGeneralSetup($set_name_ = null){
		$arrData = array();
    	if (is_null($set_name_)) {
				return $arrData;
		}
		$qry = array();
		$qry[] = "set_name='".$set_name_."'";
		$criteria = (count($qry)>0)?" WHERE ".implode(" AND ",$qry):"";
		$sql = "SELECT * FROM app_settings $criteria";
		$rsResult = $this->conn->Execute($sql);
		if(!$rsResult->EOF){
			return $rsResult->fields;
		}
	}
	
	/**
	 * Get the records from the database
	 * @param string $id_
	 * @return array
	 */
	function dbFetch_PayslipBEN($id_ = ""){
//		$this->conn->debug=1;
		$objClsMnge_PG = new clsMnge_PG($this->conn);
		$qry = array();
		if (!is_null($id_)) {
			$qry[] = "a.ppr_id ='".$id_."'";
		}
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";
		$sql = "SELECT a.*, DATE_FORMAT(b.payperiod_start_date, '%d %b %Y') as start_date, DATE_FORMAT(b.payperiod_end_date, '%d %b %Y') as end_date
					FROM payroll_paystub_report a
					JOIN payroll_pay_period b on (a.payperiod_id=b.payperiod_id)
					$criteria";
		$rsResult = $this->conn->Execute($sql);
		if(!$rsResult->EOF){
			$rsResult->fields['paystubdetails'] = unserialize($rsResult->fields['ppr_paystubdetails']);
			$EmployeeBenefits = $objClsMnge_PG->dbFetchEmployeeBenefits($rsResult->fields['emp_id'],$rsResult->fields['paystubdetails']['paystubdetail']['paystubsched']['payperiod_trans_date'],$rsResult->fields['paystubdetails']['paystubdetail']['paystubsched']['payperiod_start_date'],$rsResult->fields['paystubdetails']['paystubdetail']['paystubsched']['payperiod_end_date']);
			return $rsResult->fields;
		}
	}
	
	/**
	 * Populate array parameters to Data Variable
	 * @param array $pData_
	 * @param boolean $isForm_
	 * @return bool
	 */
	function doPopulateData($pData_ = array(),$isForm_ = false){
		if(count($pData_)>0){
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
	 * @param array $pData_
	 * @return bool
	 */
	function doValidateData($pData_ = array(), $netpay_= null, $emp_id_ = null){
		$isValid = true;
		//validation for SSS amount, check if it is in a table.
		if (!$pData_['phicee'] == "") {
			$varGetPHIC = $this->getContrib(1,$pData_['phicee']);
			if ($varGetPHIC > 0) {
				If ($varGetPHIC['scr_er']==$pData_['phicer']) {
					$isValid = true;
				} else {
				    $isValid = false;
					$_SESSION['eMsg'][] = "Cannot Recalculate. The given <b>".$pData_['phicer']."</b> PHIC Employer Share is not in the table.";	
				}
			} else {
				$isValid = false;
				$_SESSION['eMsg'][] = "Cannot Recalculate. The given <b>".$pData_['phicee']."</b> PHIC Employee Share is not in the table.";
			}	
		}
		
		//validation for PHIC amount, check if it is in a table.
		if (!$pData_['sssee'] == "") {
			$varGetSSS = $this->getContrib(2,$pData_['sssee']);
			if ($varGetSSS > 0) {
				if ($varGetSSS['scr_er']==$pData_['ssser']) {
					$isValid = true;
				} else {
				    $isValid = false;
					$_SESSION['eMsg'][] = "Cannot Recalculate. The given <b>".$pData_['ssser']."</b> SSS Employer Share is not in the table.";	
				}
			} else {
				$isValid = false;
				$_SESSION['eMsg'][] = "Cannot Recalculate. The given <b>".$pData_['sssee']."</b> SSS Employee Share is not in the table.";
			}	
		} 
		
		//validation for HDMF amount, check if it is in a table.
		if (!$pData_['hdmfee'] == "") {
//			$varGetSSS = $this->getContrib(2,$pData_['sssee']);
			if ($pData_['hdmfer'] == "" || $pData_['hdmfer'] > 100) {
				$isValid = false;
				$_SESSION['eMsg'][] = "Cannot Recalculate. The given <b>".$pData_['hdmfer']."</b> HDMF Employer Share is not in the table.";	
			}
		}
			
		//validation for Net Pay, check if is greather than salaryinfo_ceilingpay.
		$sql = "Select * from salary_info where emp_id = '".$emp_id_."' and salaryinfo_isactive = '1'"; 
		$vAr = $this->conn->Execute($sql);
		if($netpay_ < $vAr->fields['salaryinfo_ceilingpay']){
			$isValid = false;
			$_SESSION['eMsg'][] = "The Net Pay is below <b>".$vAr->fields['salaryinfo_ceilingpay']."</b>. Cannot Recalculate.";
		}
		return $isValid;
	}
	
	function getOTTableID($emp_id=null){
		$sql="SELECT * FROM payroll_comp WHERE emp_id='".$emp_id."'";
		$rsResult = $this->conn->Execute($sql);
		if(!$rsResult->EOF){
			return $rsResult->fields;
		}
	}

	/**
	 * Do Re-Calculate for payroll process
	 * @param $psdetails_
	 * @param $emp_id_
	 */
	function doReProcessPayroll($psdetails_ = null, $emp_id_ = null) {
		$objClsMnge_PG = new clsMnge_PG($this->conn);
		$objClsMngeDecimal = new Application();
		$id = $_GET['edit'];
		$ctr = 0;
		$details = $this->dbFetch_Payslip($psdetails_,$emp_id_);
		$payStubSerialize = array();
//		printa($_POST); printa($details); exit;
		// This section used to compute the Basic Pay
		//-------------------------------------------------------->>	
		$SalaryDetails = $this->getSalaryDetails($details['emp_id']);
//		printa($SalaryDetails); exit;
		if ($SalaryDetails['salaryinfo_basicrate']==$details['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['Basic Salary']) {
			$BPDayRate = $objClsMnge_PG->getBPDayRate($details['emp_id'],$SalaryDetails['salarytype_id'],$SalaryDetails['salaryinfo_basicrate'],$details['payperiod_id'],$SalaryDetails['salaryinfo_ecola']);
			$PGrate = $details['paystubdetails']['paystubdetail']['paystubaccount']['earning']['Regulartime'];
			$rateperhour = $BPDayRate['rateperhour'];	// Rate per Hours
			$rateperday = $BPDayRate['rateperday']; 	// Rate per Day
			$ratepersec = $BPDayRate['ratepersec']; 	// Rate per Second
			$varActualDaysRender = $BPDayRate['nodays'];// Number of Days Render
			$MWR = $details['paystubdetails']['paystubdetail']['paystubaccount']['earning']['MWR'];
			//COLA
			$colaperday = $BPDayRate['colaperday'];
			$colaperhour = $BPDayRate['colaperhour'];
			$colapersec = $BPDayRate['colapersec'];
			$PayPeriodCOLA = $BPDayRate['PayPeriodCOLA'];
		} else {
			$BPDayRate = $objClsMnge_PG->getBPDayRate($details['emp_id'],$SalaryDetails['salarytype_id'],$SalaryDetails['salaryinfo_basicrate'],$details['payperiod_id'],$SalaryDetails['salaryinfo_ecola']);
				//Basic Pay
				$rateperday = $BPDayRate['rateperday'];
				$rateperhour = $BPDayRate['rateperhour'];
				$ratepersec = $BPDayRate['ratepersec'];
				$PGrate = $BPDayRate['PayPeriodBP'];// Basic Rate per period
				$varActualDaysRender = $BPDayRate['nodays'];// Get number of days render
				$MWR = $details['paystubdetails']['paystubdetail']['paystubaccount']['earning']['MWR'];
				//COLA
				$colaperday = $BPDayRate['colaperday'];
				$colaperhour = $BPDayRate['colaperhour'];
				$colapersec = $BPDayRate['colapersec'];
				$PayPeriodCOLA = $BPDayRate['PayPeriodCOLA'];
		}
//		echo "==================Basic Rate===================<br>";
//		printa($BPDayRate);
//		echo $PGrate." Basic Rate<br>";
//		echo $rateperhour." rate per Hours<br>";
//		echo $rateperday." rate per Day<br>";
//		echo $ratepersec." rate per second<br>";
//		echo $varActualDaysRender." # of days in cutoff<br>";
//		echo "==================COLA Rate==================<br>";
//		echo $colaperday." rate per day<br>";
//		echo $colaperhour." rate per hrs<br>";
//		echo $colapersec." rate per sec<br>";
//		echo $PayPeriodCOLA." COLA Rate<br>";
//		exit;
		//----------------------------END-------------------------<<

		// get total OT TIME
		//-------------------------------------------------------->>
			$otSettings = $this->getGeneralSetup("Overtime Computation");
			$ot_id = $this->getOTTableID($details['emp_id']);
			$varData['totalOTtime'] = $objClsMnge_PG->getTotalOTByPayPeriod($details['emp_id'],$details['paystubdetails']['paystubdetail']['paystubsched']['payperiod_id'],$ot_id['ot_id']);
			$totalRegtime = $varData['totalOTtime'];
			$varTotalallOT_ = 0;
			$TotalNonTaxOT = 0;
//			printa($varData['totalOTtime']); exit;
			for ($a=0;$a<count($totalRegtime);$a++) {
				if($otSettings['set_stat_type']==0){
					$varOTTotalrate_ = $totalRegtime[$a]['otr_factor'] * $rateperhour;
				} else {
					$varOTTotalrate_ = $totalRegtime[$a]['otr_factor'] * ($rateperhour+$colaperhour);
				}
				$varOTTotal_ = Number_Format(($totalRegtime[$a]['otrec_totalhrs'] * $varOTTotalrate_),$objClsMngeDecimal->getGeneralDecimalSettings(),'.','');
				$vartotalotrate = Number_Format($varOTTotal_,$objClsMngeDecimal->getGeneralDecimalSettings(),'.','');
					$this->otDetail[$a] = array(
						 "otrec_id"=>$totalRegtime[$a]['otrec_id']
						,"ot_name"=>$totalRegtime[$a]['otr_name']
						,"rate"=>$totalRegtime[$a]['otr_factor']
						,"totaltimehr"=>($totalRegtime[$a]['otrec_totalhrs'])
						,"ratephrs"=>$rateperhour
						,"rateperhr"=>Number_Format($varOTTotalrate_,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						,"otamount"=>$vartotalotrate
						,"ot_istax"=>$totalRegtime[$a]['ot_istax']
					);
				IF($totalRegtime[$a]['ot_istax']==0){				
					$TotalNonTaxOT += $varOTTotal_;
				}ELSE{
					$varTotalallOT_ += $varOTTotal_;
				}
				$objClsMnge_PG->updateOTrecord($totalRegtime[$a]['otrec_id'],$details['paystub_id'],$vartotalotrate);// update OT Record
            }
//		printa ($this->otDetail[$ctr]);
		//----------------------------END-------------------------<<

		// get total TA Time
		//-------------------------------------------------------->> 
			$taSettings = clsPayroll_Details::getGeneralSetup("Leave Deduction");
            $varData['totalTAtime'] = $objClsMnge_PG->getTotalTAByPayPeriod($details['emp_id'],$details['paystubdetails']['paystubdetail']['paystubsched']['payperiod_id']);
			$totalTAtime = $varData['totalTAtime'];
			$varTotalallTA_ = 0;
			for ($a=0;$a<count($totalTAtime);$a++) {
				if($taSettings['set_stat_type']==0){
					($totalTAtime[$a]['tatbl_rate']=='2')?$varTArate_ = $rateperday:$varTArate_ = $rateperhour;//get the TA type: 2=Day, 1=hrs
				} else {
					($totalTAtime[$a]['tatbl_rate']=='2')?$varTArate_ = $rateperday+$colaperday:$varTArate_ = $rateperhour+$colaperhour;//get the TA type: 2=Day, 1=hrs
				}
				($totalTAtime[$a]['tatbl_rate']=='2')?$varTAtype_ = 'D':$varTAtype_ = 'H';
				if($totalTAtime[$a]['tatbl_name']=='Custom Days'){$varTArate_ = 0;}
				$varTATotal_ = ($totalTAtime[$a]['emp_tarec_nohrday'] * $varTArate_);
				$vartotaltarate = $varTATotal_;
				$this->taDetail[$a] = array(
								 "emp_tarec_id"=>$totalTAtime[$a]['emp_tarec_id']
								,"ta_name"=>$totalTAtime[$a]['tatbl_name']
								,"type"=>$totalTAtime[$a]['tatbl_rate']
								,"totaltimehr"=>($totalTAtime[$a]['emp_tarec_nohrday'])
								,"ratetype"=>$varTAtype_
								,"rateperDH"=>$varTArate_
								,"taamount"=>$vartotaltarate
							);
				$varTotalallTA_ += Number_Format($varTATotal_,$objClsMngeDecimal->getGeneralDecimalSettings(),'.','');
				$objClsMnge_PG->updateTArecord($totalTAtime[$a]['emp_tarec_id'],$details['paystub_id'],$vartotaltarate);// update TA record
            }
		// printa ($this->taDetail[$ctr]);
		//----------------------------END-------------------------<<

	    // get Leave Records
		//-------------------------------------------------------->> 
		$LeaveRecords = $objClsMnge_PG->getLeaveRecord($details['emp_id']);
		//----------------------------END-------------------------<<
		
		//@Note: Call the getAmendments function
		//-------------------------------------------------------->>
			$amendments = $objClsMnge_PG->getAmendments($details['emp_id'],$details['paystubdetails']['paystubdetail']['paystubsched']['payperiod_trans_date'],$details['paystubdetails']['paystubdetail']['paystubsched']['payperiod_start_date'],$details['paystubdetails']['paystubdetail']['paystubsched']['payperiod_end_date'],$details['paystub_id']);
			$totalTSEarningAmendments = 0;   // Earning subject to Tax & Statutory
			$totalTEarningAmendments = 0;    // Earning subject to Tax
			$totalSEarningAmendments = 0;    // Earning subject to Statutory
			$totalNTSEarningAmendments = 0;  // Earning Non Tax & Statutory
			$totalTSDeductionAmendments = 0; // Deduction subject to Tax & Statutory
			$totalTDeductionAmendments = 0;  // Deduction subject to Tax
			$totalSDeductionAmendments = 0;  // Deduction subject to Statutory
			$totalNTSDeductionAmendments = 0;// Deduction Non Tax & Statutory
			//sum up the amount of Amendments
			if (count($amendments)>0) {
				foreach ($amendments as $keyamend => $valamend){
//					printa ($valamend);
//				$this->doSavePayStubEntry($paystub_id,$amendments[$r]['psa_id'], $amendments[$r]['psamend_amount'], $amendments[$r]['psamend_rate'],$amendments[$r]['psamend_unit']);
					if ($valamend['psa_type']==1) { // Earning
						if ($valamend['psa_statutory'] == 1) {
							if ($valamend['psa_tax'] == 1) {
								$totalTSEarningAmendments += $valamend['amendemp_amount'];   //subject to Tax & Statutory
							} else {
								$totalSEarningAmendments += $valamend['amendemp_amount'];    //subject to Statutory
							}
						} else {
							if ($valamend['psa_tax'] == 1) {
								$totalTEarningAmendments += $valamend['amendemp_amount'];    //subject to Tax 
							} else {
								$totalNTSEarningAmendments += $valamend['amendemp_amount'];  //Non Tax & Statutory
							}
						}
					} else { // Deduction
						if ($valamend['psa_statutory'] == 1) {
							if ($valamend['psa_tax'] == 1) {
								$totalTSDeductionAmendments += $valamend['amendemp_amount']; //subject to Tax & Statutory
							} else {
								$totalSDeductionAmendments += $valamend['amendemp_amount'];  //subject to Statutory
							}
						} else {
							if ($valamend['psa_tax'] == 1) {
								$totalTDeductionAmendments += $valamend['amendemp_amount'];  //subject to Tax 
							} else {
								$totalNTSDeductionAmendments += $valamend['amendemp_amount'];//Non Tax & Statutory
							}
						}
					}
				}
			}
//			echo "==================Amendments===================<br>";
//			echo $totalTSEarningAmendments." E Tax & Stat<br>";
//			echo $totalTEarningAmendments." E Tax<br>";
//			echo $totalSEarningAmendments." E Stat<br>";
//			echo $totalNTSEarningAmendments." E Non Tax & Stat<br>";
//			echo $totalTSDeductionAmendments." D Tax & Stat<br>";
//			echo $totalTDeductionAmendments." D Tax<br>";
//			echo $totalSDeductionAmendments." D Stat<br>";
//			echo $totalNTSDeductionAmendments." D Non Tax & Stat<br>";
		//----------------------------END-------------------------<<	
			
		// get employee benefits & Deduction
		//-------------------------------------------------------->>
//			$EmployeeBenefits = $objClsMnge_PG->dbFetchEmployeeBenefits($details['emp_id'],$details['paystubdetails']['paystubdetail']['paystubsched']['payperiod_trans_date'],$details['paystubdetails']['paystubdetail']['paystubsched']['payperiod_start_date'],$details['paystubdetails']['paystubdetail']['paystubsched']['payperiod_end_date']);
			$EmployeeBenefits = $details['paystubdetails']['paystubdetail']['paystubaccount']['benefits'];
			$totalTSEarningBenefits = 0;   // Earning subject to Tax & Statutory
			$totalTEarningBenefits = 0;    // Earning subject to Tax
			$totalSEarningBenefits = 0;    // Earning subject to Statutory
			$totalNTSEarningBenefits = 0;  // Earning Non Tax & Statutory
			$totalTSDeductionBenefits = 0; // Deduction subject to Tax & Statutory
			$totalTDeductionBenefits = 0;  // Deduction subject to Tax
			$totalSDeductionBenefits = 0;  // Deduction subject to Statutory
			$totalNTSDeductionBenefits = 0;// Deduction Non Tax & Statutory
			//sum up the amount of benefits
			$objClsMnge_PG->doSaveTempPayStub($details['paystub_id'], 1, $BPDayRate['PayPeriodBP']);
			$objClsMnge_PG->doSaveTempPayStub($details['paystub_id'], -1, $BPDayRate['rateperday']);		//save Rate per Day
			$objClsMnge_PG->doSaveTempPayStub($details['paystub_id'], -2, $BPDayRate['rateperhour']);		//save Rate per Hour
			
			if (count($EmployeeBenefits)>0) {
//				printa($EmployeeBenefits);
				foreach ($EmployeeBenefits as $keyben => $valben){
//					printa ($valben);
					if ($objClsMnge_PG->validateFormulaExists($valben['psa_id'],$details['emp_id'])) {
						$v = $objClsMnge_PG->doGetFormulaVal($details['emp_id'], $valben['psa_id'], $details['paystub_id'], $details['payperiod_id']);
					} else {
						$v = $valben['ben_payperday'];
					}
					$objClsMnge_PG->doSavePayStubEntry($details['paystub_id'],$valben['psa_id'], $v);
					if ($valben['psa_type']==1) { // Earning
						if ($valben['psa_statutory'] == 1) {
							if ($valben['psa_tax'] == 1) {
								$totalTSEarningBenefits += $v;   //subject to Tax & Statutory
							} else {
								$totalSEarningBenefits += $v;    //subject to Statutory
							}
						} else {
							if ($valben['psa_tax'] == 1) {
								$totalTEarningBenefits += $v;    //subject to Tax 
							} else {
								$totalNTSEarningBenefits += $v;  //Non Tax & Statutory
							}
						}
					} else { // deduction
						if ($valben['psa_statutory'] == 1) {
							if ($valben['psa_tax'] == 1) {
								$totalTSDeductionBenefits += $v; //subject to Tax & Statutory
							} else {
								$totalSDeductionBenefits += $v;  //subject to Statutory
							} 
						}else{
							if($valben['psa_tax'] == 1){
								$totalTDeductionBenefits += $v;  //subject to Tax 
							}else{
								$totalNTSDeductionBenefits += $v;//Non Tax & Statutory
							}
						}
					}
					if($valben['ben_isfixed']){
						$payStubSerialize[] = array(
											"psa_id" => $valben['psa_id'],
											"ben_id" => $valben['ben_id'],
								            "psa_name" => $valben['psa_name'],
								            "psa_type" => $valben['psa_type'],
								            "ben_amount" => $valben['ben_amount'],
								            "ben_payperday" => $v,
								            "ben_startdate" => $valben['ben_startdate'],
								            "ben_enddate" => $valben['ben_enddate'],
								            "ben_isfixed" => $valben['ben_isfixed'],
								            "ben_suspend" => $valben['ben_suspend'],
								            "ben_periodselection" => $valben['ben_periodselection'],
								            "psa_priority" => $valben['psa_priority'],
								            "psa_tax" => $valben['psa_tax'],
								            "psa_statutory" => $valben['psa_statutory'],
								            "year_" => $valben['year_'],
								            "month_" => $valben['month_'],
								            "yearend_" => $valben['yearend_'],
								            "monthend_" => $valben['monthend_']
										);
					} else {
						$payStubSerialize[] = $valben;
					}
				}
			}
//			echo "====================Benefits====================<br>";
//			echo $totalTSEarningBenefits." E Tax & Stat<br>";
//			echo $totalTEarningBenefits." E Tax<br>";
//			echo $totalSEarningBenefits." E Stat<br>";
//			echo $totalNTSEarningBenefits." E Non Tax & Stat<br>";
//			echo $totalTSDeductionBenefits." D Tax & Stat<br>";
//			echo $totalTDeductionBenefits." D Tax<br>";
//			echo $totalSDeductionBenefits." D Stat<br>";
//			echo $totalNTSDeductionBenefits." D Non Tax & Stat<br>";
			//----------------------------END-------------------------<<

		//Sum all PayElements
		//-------------------------------------------------------->>
		$SumTSABEarning = $totalTSEarningAmendments + $totalTSEarningBenefits;         						//TS Earning
		$SumTABEarning = $totalTEarningAmendments + $totalTEarningBenefits;			   						//T Earning
		$SumSABEarning = $totalSEarningAmendments + $totalSEarningBenefits;			   						//S Earning
		$SumNonABEarning = $totalNTSEarningAmendments + $totalNTSEarningBenefits;      						//Non TS Earning
		$SumALLABEarning = $SumTSABEarning + $SumTABEarning + $SumSABEarning + $SumNonABEarning;   			//SUM all AB Earning
		
		$SumTSABDeduction = $totalTSDeductionAmendments + $totalTSDeductionBenefits;   						//TS Deduction
		$SumTABDeduction = $totalTDeductionAmendments + $totalTDeductionBenefits;	   						//T Deduction
		$SumSABDeduction = $totalSDeductionAmendments + $totalSDeductionBenefits;      						//S Deduction
		$SumNonABDeduction = $totalNTSDeductionAmendments + $totalNTSDeductionBenefits;						//Non TS Deduction
		$SumALLABDeduction = $SumTSABDeduction + $SumTABDeduction + $SumSABDeduction + $SumNonABDeduction;	//SUM all AB Deduction
		
		$TotalTSSDeduction = $SumTSABDeduction + $SumSABDeduction;
		//----------------------------END-------------------------<<
		// PAYRECORD PART
		//-------------------------------------------------------->>
		$varTotalallOT = number_format($varTotalallOT_,2,'.','');; //@note: OT Rate
		$varTotalallTA = number_format($varTotalallTA_,2,'.',''); //@note: Leave Deduction
//		$OTbackpay = $_POST['otbackpay']; //@note: OT Backpay
//		$varSumAllOTRate = $OTbackpay + $varTotalallOT; //@note: SUM of all OT
		//----------------------------END-------------------------<<
		// Amount Computed base in Basic Pay.
		//-------------------------------------------------------->>
		$basicpay_rate = $SalaryDetails['salaryinfo_basicrate'];													  					//BasicPay Rate
		$BPperperiod = $PGrate; 																  										//Basic computed base to salary type and pay group
		$COLAperperiod = $PayPeriodCOLA;																								//COLA computed base to salary type and pay group
		IF($totalRegtime[0]['ot_istax']==1){						  						      			      
			$varSumAllOTRate = Number_Format($varTotalallOT,$objClsMngeDecimal->getFinalDecimalSettings(),'.','');  //sum of all OT
		}ELSE{
			$SumNonTaxOTRate = Number_Format($TotalNonTaxOT,$objClsMngeDecimal->getFinalDecimalSettings(),'.','');  //sum of all Non-TAX OT	
		}								  						      			  						
		$varSumAllTARate = Number_Format($varTotalallTA,$objClsMngeDecimal->getFinalDecimalSettings(),'.',''); 									  						 	      							//sum of all TA
		$basicgrosspg = ($BPperperiod + $varSumAllOTRate + $PayPeriodCOLA) - $varSumAllTARate; 				  							//(Basic Pay + OT + COLA)- TA
		$basicPG_ABS = ($basicgrosspg + $SumSABEarning + $SumTSABEarning + $COLAperperiod) - ($SumTSABDeduction + $SumSABDeduction);	//Add AB Earning and Less AB Deduction subject to Stat 
		$basicPG_nostat = ($basicgrosspg + $SumTSABEarning + $SumTABEarning) - ($SumTABDeduction + $SumTSABDeduction);					//Sum all Earning and Deduction that subject to tax.
		$nontaxableGross =  $SumNonABEarning; 																	      					//Sum all non AB Earning
		$other_nontaxdeduction = $SumNonABDeduction;															 	  					//Sum all non AB Deduction
		$SumAllEarning = $BPperperiod + $varSumAllOTRate + $SumNonTaxOTRate + $SumALLABEarning + $PayPeriodCOLA; 
//		echo "===================Summary====================<br>";
//		echo $BPperperiod.' Basic Pay<br>';
//		echo $varSumAllOTRate.' Total OT<br>';
//		echo $varSumAllTARate.' Total LUA<br>';
//		echo $basicgrosspg.' (BasicPay+OT)-LUA<br>';
//		echo $basicPG_ABS.' Basic AB Stat<br>';
//		echo $nontaxableGross.' Non-TaxGross<br>';
//		echo $other_nontaxdeduction.' Non-TaxDeduction<br>';
		
        $objClsMnge_PG->doSavePayStubEntry($details['paystub_id'], 1, $BPperperiod);          //save Basic computed base to salary type and pay group to payroll_paystub_entry table
		$objClsMnge_PG->doSavePayStubEntry($details['paystub_id'], 4, $basicgrosspg);		  //save Total Grosspay to payroll_paystub_entry table
        $objClsMnge_PG->doSavePayStubEntry($details['paystub_id'],16, $varSumAllOTRate);	  //save Total OT to payroll_paystub_entry table
		$objClsMnge_PG->doSavePayStubEntry($details['paystub_id'],17, $varSumAllTARate);	  //save Total TA to payroll_paystub_entry table
		$objClsMnge_PG->doSavePayStubEntry($details['paystub_id'],25, $SumSABEarning);		  //save Total S Earning to payroll_paystub_entry table
		$objClsMnge_PG->doSavePayStubEntry($details['paystub_id'],26, $SumTSABEarning);		  //save Total TS Earning to payroll_paystub_entry table
		$objClsMnge_PG->doSavePayStubEntry($details['paystub_id'],28, $SumTABEarning);		  //save Total T Earning to payroll_paystub_entry table
		$objClsMnge_PG->doSavePayStubEntry($details['paystub_id'],29, $SumTABDeduction);	  //save Total T Deduction to payroll_paystub_entry table
		$objClsMnge_PG->doSavePayStubEntry($details['paystub_id'],33, $SumSABDeduction);	  //save Total S Deduction to payroll_paystub_entry table
		$objClsMnge_PG->doSavePayStubEntry($details['paystub_id'],34, $SumTSABDeduction);	  //save Total ST Deduction to payroll_paystub_entry table
        $objClsMnge_PG->doSavePayStubEntry($details['paystub_id'],39, $COLAperperiod);        //save Total COLA computed base to salary type and pay group to payroll_paystub_entry table
		//----------------------------END-------------------------<<
		
		//CHECKING
		//------------------------->>
//			echo "==============Summary STATUTORY===============<br>";
//			echo $varSumAllTARate." = ".$details['paystubdetails']['paystubdetail']['paystubaccount']['TUA']['TotalLeave']."<br>";
//			echo $varSumAllOTRate." = ".$details['paystubdetails']['paystubdetail']['paystubaccount']['earning']['OT']['TotalallOT']."<br>";
//			echo $basicpay_rate." = ".$details['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['Basic Salary']."<br>";
//			exit;
		//-------------------------<<
//        printa($details['paystubdetails']['paystubdetail']['paystubaccount']['deduction']);
//        printa($_POST);
//        exit;
        
		if($varSumAllTARate != $details['paystubdetails']['paystubdetail']['paystubaccount']['TUA']['TotalLeave'] && $varSumAllOTRate != $details['paystubdetails']['paystubdetail']['paystubaccount']['earning']['OT']['TotalallOT'] && $basicpay_rate != $details['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['Basic Salary']){
			//recompute Statutory Contrib
			$varData[$ctr]['typeDeduc'] = $objClsMnge_PG->getTypeDeduction();//get deduction type
			$dectype = $varData[$ctr]['typeDeduc'];
			for ($b=0;$b<count($dectype);$b++){
				$qry = array();
				$qry[] = "a.sc_id = '".$dectype[$b]['GenSetup']['set_decimal_places']."'";
				$qry[] = "a.dec_id = '".$dectype[$b]['dec_id']."'";
				$criteria = count($qry)>0 ? " WHERE ".implode(' AND ',$qry) : '';
				$sql = "SELECT a.* FROM statutory_contribution a $criteria";
				$varDeducH = $this->conn->Execute($sql);
				if(!$varDeducH->EOF){
					$varHdeduc = $varDeducH->fields;
				}
//				$transdateSched = $rsResult->fields['ppdTransDate'];
				$transdateSched = date("d", strtotime($details['paystubdetails']['paystubdetail']['paystubsched']['payperiod_trans_date']));
				$startdateSched = date("d", strtotime($details['paystubdetails']['paystubdetail']['paystubsched']['payperiod_start_date']));
				$schedSecond = $details['paystubdetails']['paystubdetail']['paystubsched']['schedSecond'];
//				echo "<BR>".$transdateSched." transdateSched<br>"; echo $startdateSched." startdateSched<br>"; echo $schedSecond." schedSecond<br>";
				
				//@todo: check for 1st & 2nd Half
				$varDeductionSched = $objClsMnge_PG->getDeductionSched($details['emp_id'],$dectype[$b]['dec_id']);
//				printa($varDeductionSched); echo count($varDeductionSched).'<br>';
				switch ($dectype[$b]['dec_id']) {
					case 1:
						IF(count($varDeductionSched)=='1'){
								IF($details['paystubdetails']['empinfo']['salaryclass_id']=='5'){//FOR MONTHLY PG SSS COMPUTATION
									IF($varDeductionSched[0]['bldsched_period']=='0'){
										$varDecSSS = 0;
			                            $varDecSSSEC = 0;
			                            $er[$ctr] = 0;
			                            $varDecSSSER = 0;
									}ELSE{
										$schedSecond = '1';
										$varPPD_ = $objClsMnge_PG->getMonthlyRecordStat($details['emp_id'],'7',$details['payperiod_period'],1);
										IF($dectype[$b]['GenSetup']['set_stat_type'] == 1){
											//SSS BASE ON GROSS
											$totalSSSgross = $basicPG_ABS + $varPPD_['ppe_rate'];
										}ELSE{
											//SSS BASE ON BASIC
											$totalSSSgross = $BPperperiod;
										}
										$varDec = $objClsMnge_PG->getTotalDeductionByPayPeriod($details['emp_id'],$varHdeduc['sc_id'],$dectype[$b]['dec_id'],$totalSSSgross,$details['paystubdetails']['empinfo']['taxep_id']);
					                    $varDecSSS = $varDec['scr_ee'] - $varPPD_['ppe_amount'];
					                    $er[$ctr] = $varDec['scr_er'] - $varPPD_['ppe_amount_employer'];
					                    $varDecSSSER = $varDec['scr_er'] - $varPPD_['ppe_amount_employer'];
					                    $varDecSSSEC = $varDec['scr_ec'] - $varPPD_['ppe_units'];
									}
								}ELSEIF($details['paystubdetails']['empinfo']['salaryclass_id']=='4'){//FOR SEMI-MONTHLY PG SSS COMPUTATION
									IF($varDeductionSched[0]['bldsched_period']=='0' OR $varDeductionSched[0]['bldsched_period']=='1'){
											$varDecSSS = 0;
				                            $varDecSSSEC = 0;
				                            $er[$ctr] = 0;
				                            $varDecSSSER = 0;
									}ELSE{
										IF($details['paystubdetails']['paystubdetail']['paystubsched']['payperiod_freq']=='2'){
											$schedSecond = '1';
											$varPPD_ = $objClsMnge_PG->getMonthlyRecordStat($details['emp_id'],'7',$details['payperiod_period'],1);
											IF($dectype[$b]['GenSetup']['set_stat_type'] == 1){
												//SSS BASE ON GROSS
												$totalSSSgross = $basicPG_ABS + $varPPD_['ppe_rate'];
											}ELSE{
												//SSS BASE ON BASIC
												$totalSSSgross = $BPperperiod + $varPPD_['ppe_rate'];
											}
											$varDec = $objClsMnge_PG->getTotalDeductionByPayPeriod($details['emp_id'],$varHdeduc['sc_id'],$dectype[$b]['dec_id'],$totalSSSgross,$details['paystubdetails']['empinfo']['taxep_id']);
						                    $varDecSSS = $varDec['scr_ee'];
						                    $er[$ctr] = $varDec['scr_er'];
						                    $varDecSSSER = $varDec['scr_er'];
						                    $varDecSSSEC = $varDec['scr_ec'];
										}else{
											$varDecSSS = 0;
				                            $varDecSSSEC = 0;
				                            $er[$ctr] = 0;
				                            $varDecSSSER = 0;
										}
									}
								}
							}ELSE{
								if($details['paystubdetails']['paystubdetail']['paystubsched']['payperiod_freq']>='2'){
									$schedSecond = '1';
									$varPPD_ = $objClsMnge_PG->getMonthlyRecordStat($details['emp_id'],'7',$details['payperiod_period'],1);
									IF($dectype[$b]['GenSetup']['set_stat_type'] == 1){
										//SSS BASE ON GROSS
										$totalSSSgross = $basicPG_ABS + $varPPD_['ppe_rate'];
									}ELSE{
										//SSS BASE ON BASIC
										$totalSSSgross = $BPperperiod + $varPPD_['ppe_rate'];
									}
									$varDec = $objClsMnge_PG->getTotalDeductionByPayPeriod($details['emp_id'],$varHdeduc['sc_id'],$dectype[$b]['dec_id'],$totalSSSgross,$details['paystubdetails']['empinfo']['taxep_id']);
									$varDecSSS = $varDec['scr_ee'] - $varPPD_['ppe_amount'];
				                    $er[$ctr] = $varDec['scr_er'] - $varPPD_['ppe_amount_employer'];
				                    $varDecSSSER = $varDec['scr_er'] - $varPPD_['ppe_amount_employer'];
				                    if($varDec['scr_ec']>10){
				                    	$varDecSSSEC = $varDec['scr_ec'] - $varPPD_['ppe_units'];
				                    }else{
				                    	$varDecSSSEC = $varDec['scr_ec'];
				                    }
								}else{
									$schedSecond = '0';
									$totalSSSgross = $basicPG_ABS;
									$varDec = $objClsMnge_PG->getTotalDeductionByPayPeriod($details['emp_id'],$varHdeduc['sc_id'],$dectype[$b]['dec_id'],$totalSSSgross,$details['paystubdetails']['empinfo']['taxep_id']);
			                        if($details['paystubdetails']['empinfo']['salaryclass_id'] == 5){
			                            $varDecSSS = $varDec['dd_phic_sss_ee']/2;
			                            $varDecSSSEC = $varDec['dd_phic_sss_ec']/2;
			                            $er[$ctr] = $varDec['dd_phic_sss_er']/2;
			                            $varDecSSSER = $varDec['dd_phic_sss_er']/2;
			                        }else{
			                            $varDecSSS = $varDec['scr_ee'];
			                            $varDecSSSEC = $varDec['scr_ec'];
			                            $er[$ctr] = $varDec['scr_er'];
			                            $varDecSSSER = $varDec['scr_er'];
			                        }
								}
							}
						$totalDeDuction[$ctr] += $varDecSSS;
						$psaSSS = "SSS";
                        $objClsMnge_PG->doSavePayStubEntry($details['paystub_id'], 7, $varDecSSS,$er[$ctr], $totalSSSgross,$varDecSSSEC);//update SSS
//						echo "================== SSS STAT ==================<br>";
//						echo $schedSecond." schedSecond <br>";
//	                    echo $totalSSSgross." total SSS GROSS <br>";
//	                    echo $varDecSSS." Total employee deduction <br>";
//	                    echo $er[$ctr]." Total Employer Deduction <br>";
//	                    echo $varDecSSSER." Total Employer Deduction <br>";
//	                    echo $varDecSSSEC." ECC <br>";
                        break;
					
					case 2:
						IF(count($varDeductionSched)=='1'){
								IF($details['paystubdetails']['empinfo']['salaryclass_id']=='5'){//FOR MONTHLY PG SSS COMPUTATION
									IF($varDeductionSched[0]['bldsched_period']=='0'){
										$varDecPHIL = 0;
			                            $er[$ctr] = 0;
			                            $varDecPHILER = 0;
			                            $varDecPHILEC = 0;
									}ELSE{
										$varPPD_ = $objClsMnge_PG->getMonthlyRecordStat($details['emp_id'],'14',$details['payperiod_period'],1);
										IF($dectype[$b]['GenSetup']['set_stat_type'] == 1){
											//PHIC BASE ON GROSS
											$totalPhilgross = $basicPG_ABS + $varPPD_['ppe_rate'];
										}ELSE{
											//PHIC BASE ON BASIC
											$totalPhilgross = $BPperperiod;
										}
										$varDec = $objClsMnge_PG->getTotalDeductionByPayPeriod($details['emp_id'],$varHdeduc['sc_id'],$dectype[$b]['dec_id'],$totalPhilgross,$details['paystubdetails']['empinfo']['taxep_id']);
					                    $varDecPHIL = $varDec['scr_ee'] - $varPPD_['ppe_amount'];
					                    $er[$ctr] = $varDec['scr_er'] - $varPPD_['ppe_amount_employer'];
					                    $varDecPHILER = $varDec['scr_er'] - $varPPD_['ppe_amount_employer'];
					                    $varDecPHILEC = $varDec['scr_ec'];
									}
								}ELSEIF($details['paystubdetails']['empinfo']['salaryclass_id']=='4'){//FOR SEMI-MONTHLY PG SSS COMPUTATION
									IF($varDeductionSched[0]['bldsched_period']=='0' OR $varDeductionSched[0]['bldsched_period']=='1'){
											$varDecPHIL = 0;
				                            $er[$ctr] = 0;
				                            $varDecPHILER = 0;
				                            $varDecPHILEC = 0;
									}ELSE{
										IF($details['paystubdetails']['paystubdetail']['paystubsched']['payperiod_freq']=='2'){
											$varPPD_ = $objClsMnge_PG->getMonthlyRecordStat($details['emp_id'],'4',$details['payperiod_period'],1);
											IF($dectype[$b]['GenSetup']['set_stat_type'] == 1){
												//PHIC BASE ON GROSS
												$totalPhilgross = $basicPG_ABS + $varPPD_['ppe_rate'];
											}ELSE{
												//PHIC BASE ON BASIC
												$totalPhilgross = $BPperperiod + $varPPD_['ppe_rate'];
											}
											$varDec = $objClsMnge_PG->getTotalDeductionByPayPeriod($details['emp_id'],$varHdeduc['sc_id'],$dectype[$b]['dec_id'],$totalPhilgross,$details['paystubdetails']['empinfo']['taxep_id']);
						                    $varDecPHIL = $varDec['scr_ee'];
						                    $er[$ctr] = $varDec['scr_er'];
						                    $varDecPHILER = $varDec['scr_er'];
						                    $varDecPHILEC = $varDec['scr_ec'];
										}else{
											$varDecPHIL = 0;
				                            $er[$ctr] = 0;
				                            $varDecPHILER = 0;
				                            $varDecPHILEC = 0;
										}
									}
								}
							}ELSE{
								if($details['paystubdetails']['paystubdetail']['paystubsched']['payperiod_freq']>='2') {
									$varPPD_ = $objClsMnge_PG->getMonthlyRecordStat($details['emp_id'],'14',$details['payperiod_period'],1);
									IF($dectype[$b]['GenSetup']['set_stat_type'] == 1){
										//PHIC BASE ON GROSS
										$totalPhilgross = $basicPG_ABS + $varPPD_['ppe_rate'];
									}ELSE{
										//PHIC BASE ON BASIC
										$totalPhilgross = $BPperperiod + $varPPD_['ppe_rate'];
									}
									$varDec = $objClsMnge_PG->getTotalDeductionByPayPeriod($details['emp_id'],$varHdeduc['sc_id'],$dectype[$b]['dec_id'],$totalPhilgross,$details['paystubdetails']['empinfo']['taxep_id']);
				                    $varDecPHIL = $varDec['scr_ee'] - $varPPD_['ppe_amount'];
				                    $er[$ctr] = $varDec['scr_er'] - $varPPD_['ppe_amount_employer'];
				                    $varDecPHILER = $varDec['scr_er'] - $varPPD_['ppe_amount_employer'];
								}else{
									$totalPhilgross = $basicPG_ABS;
									$varDec = $objClsMnge_PG->getTotalDeductionByPayPeriod($details['emp_id'],$varHdeduc['sc_id'],$dectype[$b]['dec_id'],$totalPhilgross,$details['paystubdetails']['empinfo']['taxep_id']);
			                        if($details['paystubdetails']['empinfo']['salaryclass_id'] == 5){
			                            $varDecPHIL = $varDec['dd_phic_sss_ee']/2;
			                            $er[$ctr] = $varDec['dd_phic_sss_er']/2;
			                            $varDecPHILER = $varDec['dd_phic_sss_er']/2;
			                            $varDecPHILEC = $varDec['scr_ec'];
			                        }else{
			                            $varDecPHIL = $varDec['scr_ee'];
			                            $er[$ctr] = $varDec['scr_er'];
			                            $varDecPHILER = $varDec['scr_er'];
			                            $varDecPHILEC = $varDec['scr_ec'];
			                        }
								}
							}
						$totalDeDuction[$ctr] += $varDecPHIL;
						$psaPhil = "PHIC";
                        $objClsMnge_PG->doSavePayStubEntry($details['paystub_id'], 14, $varDecPHIL,$er[$ctr], $totalPhilgross,0);// update PHIC
//						echo "================== PHIC STAT ================<br>";
//	                    echo $totalPhilgross." total PHIC GROSS <br>";
//	                    echo $varDecPHIL." Total employee deduction <br>";
//	                    echo $er[$ctr]." Total Employer Deduction <br>";
//	                    echo $varDecPHILER." Total Employer Deduction <br>";
//	                    echo $varDecPHILEC." MSB <br>";
                        break;	
						
					case 3:
						IF(count($varDeductionSched)=='1'){
								IF($details['paystubdetails']['empinfo']['salaryclass_id']=='5'){//FOR MONTHLY PG SSS COMPUTATION
									IF($varDeductionSched[0]['bldsched_period']=='0'){
										$varDecPagibig = 0;
			                            $varDecPagibigEmployer = 0;
									}ELSE{
										$varPPD_ = $objClsMnge_PG->getMonthlyRecordStat($details['emp_id'],'15',$details['payperiod_period'],1);
										$totalHDMFgross = $varPPD_['ppe_rate'] + $basicPG_ABS;
										$varDec = $objClsMnge_PG->getTotalDeductionByPayPeriod($details['emp_id'],$varHdeduc['sc_id'],$dectype[$b]['dec_id'],$totalHDMFgross,$details['paystubdetails']['empinfo']['taxep_id']);
										$varDecPagibig = $varDec['scr_ee'] - $varPPD_['ppe_amount'];
					                    $varDecPagibigEmployer = $varDec['scr_er'] - $varPPD_['ppe_amount_employer'];
									}
								}ELSEIF($details['paystubdetails']['empinfo']['salaryclass_id']=='4'){//FOR SEMI-MONTHLY PG SSS COMPUTATION
									IF($varDeductionSched[0]['bldsched_period']=='0' OR $varDeductionSched[0]['bldsched_period']=='1'){
											$varDecPagibig = 0;
			                            	$varDecPagibigEmployer = 0;
									}ELSE{
										IF($details['paystubdetails']['paystubdetail']['paystubsched']['payperiod_freq']=='2'){
											$varPPD_ = $objClsMnge_PG->getMonthlyRecordStat($details['emp_id'],'4',$details['payperiod_period'],1);
											$totalHDMFgross = $varPPD_['ppe_amount'] + $basicPG_ABS;
											$varDec = $objClsMnge_PG->getTotalDeductionByPayPeriod($details['emp_id'],$varHdeduc['sc_id'],$dectype[$b]['dec_id'],$totalHDMFgross,$details['paystubdetails']['empinfo']['taxep_id']);
											$varDecPagibig = $varDec['scr_ee'];
						                    $varDecPagibigEmployer = $varDec['scr_er'];
										}else{
											$varDecPagibig = 0;
			                            	$varDecPagibigEmployer = 0;
										}
									}
								}
							}ELSE{
								if($schedSecond>=$startdateSched && $schedSecond<=$transdateSched){
									$varPPD_ = $objClsMnge_PG->getMonthlyRecordStat($details['emp_id'],'15',$details['payperiod_period'],1);
									$totalHDMFgross = $varPPD_['ppe_rate'] + $basicPG_ABS;
									$varDec = $objClsMnge_PG->getTotalDeductionByPayPeriod($details['emp_id'],$varHdeduc['sc_id'],$dectype[$b]['dec_id'],$totalHDMFgross,$details['paystubdetails']['empinfo']['taxep_id']);
									$varDecPagibig = $varDec['scr_ee'] - $varPPD_['ppe_amount'];
				                    $varDecPagibigEmployer = $varDec['scr_er'] - $varPPD_['ppe_amount_employer'];
								}else{
									$totalHDMFgross = $basicPG_ABS;
									$varDec = $objClsMnge_PG->getTotalDeductionByPayPeriod($details['emp_id'],$varHdeduc['sc_id'],$dectype[$b]['dec_id'],$totalHDMFgross,$details['paystubdetails']['empinfo']['taxep_id']);
			                        if($varDec['dduct_isnominal'] == 10){
			                            if($details['paystubdetails']['empinfo']['salaryclass_id'] == 5){
			                                $varDecPagibig = (($varDec['dd_phic_sss_ee']*$totalHDMFgross)/2);
			                                $varDecPagibigEmployer = ($varDec['dd_phic_sss_er']*$totalHDMFgross)/2;
			                            }else{
			                                $varDecPagibig = $varDec['dd_phic_sss_ee']*$totalHDMFgross;
			                                $varDecPagibigEmployer = $varDec['dd_phic_sss_er']*$totalHDMFgross;
			                            }
			                        }else{
			                            $varDecPagibig = $varDec['scr_ee'];
			                            $varDecPagibigEmployer = $varDec['scr_er'];
			                        }
								}
							}
						$totalDeDuction[$ctr] += $varDecPagibig;
						$psapagibig = "Pag-ibig";
                        $objClsMnge_PG->doSavePayStubEntry($details['paystub_id'], 15, $varDecPagibig,$varDecPagibigEmployer,$totalHDMFgross,0);// update PAGIBIG
						break;
					default:
					break;
				}
			}
			$deduction = array(
							 "SSS" => $varDecSSS
							,"SSSER" => $varDecSSSER
							,"SSSEC" => $varDecSSSEC
							,"PhilHealth" => $varDecPHIL
							,"PhilHealthER" => $varDecPHILER
							,"Pag-ibig" => $varDecPagibig
							,"Pag-ibigER" => $varDecPagibigEmployer
							,"Others" => $details['paystubdetails']['paystubdetail']['paystubaccount']['deduction']['Others']
						 );
			$varDeduction = $totalDeDuction[$ctr];//sum all statutory contribution.
			$objClsMnge_PG->doSavePayStubEntry($details['paystub_id'], 27, $varDeduction);// update stat contrib
		}else{
			//this to get total goverment contribution and update to paystub entry table.
			$varDeduction  =  $_POST['sssee'] + $_POST['phicee'] + $_POST['hdmfee'];
			
			$deduction = array("SSS" => $_POST['sssee']
							  ,"SSSER" => $_POST['ssser']
							  ,"SSSEC" => $_POST['sssec']
							  ,"PhilHealth" => $_POST['phicee']
							  ,"PhilHealthER" => $_POST['phicer']
							  ,"Pag-ibig" => $_POST['hdmfee']
							  ,"Pag-ibigER" => $_POST['hdmfer']
							  ,"Others" => $details['paystubdetails']['paystubdetail']['paystubaccount']['deduction']['Others']);
			
			$objClsMnge_PG->doSavePayStubEntry($details['paystub_id'], 7, $_POST['sssee'],$_POST['ssser'], $basicPG_ABS,$_POST['sssec']);//update SSS
			$objClsMnge_PG->doSavePayStubEntry($details['paystub_id'], 14, $_POST['phicee'],$_POST['phicer'], $basicPG_ABS,0);// update PHIC
			$objClsMnge_PG->doSavePayStubEntry($details['paystub_id'], 15, $_POST['hdmfee'],$_POST['hdmfer'],$basicPG_ABS,0);// update PAGIBIG
			$objClsMnge_PG->doSavePayStubEntry($details['paystub_id'], 27, $varDeduction);// update stat contrib
		}
//		echo "===============Statutory Summary==================<br>";
//		echo $transdateSched." TranDate<br>";
//		echo $varDecSSS." SSSEE<br>";
//		echo $varDecSSSER." SSSER<br>";
//		echo $varDecSSSEC." SSSEC<br>";
//		echo $varDecPHIL." PHICEE<br>";
//		echo $varDecPHILER." PHICER<br>";
//		echo $varDecPagibig." HDMFEE<br>";
//		echo $varDecPagibigEmployer." HDMFER<br>";
//		echo $varDeduction." Total Statutory Contrib<br>";
		
		// Compute W/H Tax
		//-------------------------------------------------------->>
		IF($details['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['W/H Tax'] == $_POST['tax']){
			IF($rateperday <= $details['paystubdetails']['paystubdetail']['paystubaccount']['earning']['MWR']){
				$totalTax = 0;
				$conVertTaxper = 0;
			}ELSE{
				//Used to check if MWE
				$qry_ = array();
				$qry_[] = "a.empdd_id = '5'";
				$qry_[] = "a.emp_id = '".$details['emp_id']."'";
				$criteria = count($qry_)>0 ? " WHERE ".implode(' AND ',$qry_) : '';
				$sql_= "SELECT a.bldsched_period,a.percent_tax,a.s_ltu,a.s_stat FROM period_benloanduc_sched a $criteria";
				$varMWE = $this->conn->Execute($sql_);
				IF(!$varMWE->EOF){
					$varMWE_ = $varMWE->fields['bldsched_period'];
				}ELSE{
					$varMWE_ = 0;
				}
			//Get General Setup for TAX Computation
			$varHdeduc = clsPayroll_Details::getGeneralSetup('TAX');
			IF($varHdeduc['set_stat_type']=='1'){//1 = GROSS PAY
				IF($varHdeduc['set_order']=='1'){//1 = Subject to statutory Deduction
	        		$taxableGross = $basicPG_nostat - $varDeduction;//Taxable gross - Total Statutory Deduection
					IF($varMWE_['bldsched_period']=='4'){
						IF($varMWE_['s_stat']!='1'){//if no-stat plus Statutory
							$taxableGross = $taxableGross + $varDeduction;
						}
						IF($varMWE_['s_ltu']!='1'){
							$taxableGross = $taxableGross + $varSumAllTARate;
						}
					}
				}ELSE{//0 = not suject to statutory deduction
					$taxableGross = $basicPG_nostat;
					IF($varMWE_['bldsched_period']=='4'){
		        		IF($varMWE_['s_stat']=='1'){
							$taxableGross = $taxableGross - $varDeduction;
						}
						IF($varMWE_['s_ltu']!='1'){
							$taxableGross = $taxableGross + $varSumAllTARate;
						}
					}
				}
			}ELSE{//0 = BASIC SALARY
				IF($varHdeduc['set_order']=='1'){//1 = Subject to statutory Deduction
					$taxableGross = $BPperperiod - $varDeduction;//Basic Salary - Total Statutory Deduction
					IF($varMWE_['bldsched_period']=='4'){
		        		IF($varMWE_['s_stat']!='1'){
							$taxableGross = $taxableGross + $varDeduction;
						}
						IF($varMWE_['s_ltu']=='1'){
							$taxableGross = $taxableGross - $varSumAllTARate;
						}
					}
				}ELSE{//0 = not suject to statutory deduction
					$taxableGross = $BPperperiod;//Basic Salary
					IF($varMWE_['bldsched_period']=='4'){
		        		IF($varMWE_['s_stat']=='1'){
							$taxableGross = $taxableGross - $varDeduction;
						}
						IF($varMWE_['s_ltu']=='1'){
							$taxableGross = $taxableGross - $varSumAllTARate;
						}
					}
				}
			}
				IF($varMWE_['bldsched_period']=='2'){
					$totalTax = 0;
					$conVertTaxper = 0;
				}ELSEIF($varMWE_['bldsched_period']=='3'){
					$totalTax = 0;
					$conVertTaxper = 0;	
				}ELSEIF($varMWE_['bldsched_period']=='4'){
					$conVertTaxper = $varMWE_['percent_tax'] / 100;
					$totalTax = $taxableGross * $conVertTaxper;
					$totalTax_ = $totalTax;
					$psaTax = "Tax";
				}ELSE{//this is to get the taxpolicy
					$varDec = $objClsMnge_PG->getTotalTaxByPayPeriod($details['emp_id'],$varHdeduc['set_decimal_places'],2,$taxableGross,$details['paystubdetails']['empinfo']['taxep_id'],$details['paystubdetails']['empinfo']['tt_pay_group']);
					$varStax = $taxableGross - $varDec['tt_minamount'];
					$conVertTaxper =  $varDec['tt_over_pct'] / 100 ;
					$varStax_p =  $varStax * $conVertTaxper;
					$totalTax = $varDec['tt_taxamount'] + $varStax_p;
					$totalTax_ = $totalTax;
					$psaTax = "Tax";
				}
			}
		}ELSE{
			$totalTax = $_POST['tax']; //if the tax is edited.
		}	
		//----------------------------END-------------------------<<
//		echo "=================TAX Summary===================<br>";
//		echo $totalTax." W/H Tax<br>";
//		echo $varMWE_['percent_tax']." varMWE_[percent_tax]<br>";
//		echo $conVertTaxper." conVertTaxper<br>";
//		echo $taxableGross." Taxable Gross<br>";

		//get gov/reg loans
		//-------------------------------------------------------->>
			$govreg_loan = $objClsMnge_PG->getEmployeeActiveGovRegLoan($details['emp_id'],$details['paystubdetails']['paystubdetail']['paystubsched']['payperiod_trans_date'],$details['paystub_id'],$details['paystubdetails']['paystubdetail']['paystubsched']['payperiod_freq']);
            if(count($govreg_loan)>0){
			foreach ($govreg_loan as $keyregloan => $valregloan){
                    //check if negative number
                    if($valregloan['loan_payperperiod'] >0){
                        $totalregloan +=  $valregloan['loan_payperperiod'];
                    }
			}
		}
		//----------------------------END-------------------------<<	
		
			$afterTaxGross = Number_Format($taxableGross,$objClsMngeDecimal->getFinalDecimalSettings(),'.','') - Number_Format($totalTax,$objClsMngeDecimal->getFinalDecimalSettings(),'.','');				 //deduct the tax to the taxable gross
			$grossandnontaxable = Number_Format($nontaxableGross,$objClsMngeDecimal->getFinalDecimalSettings(),'.','') + $SumNonTaxOTRate;						 //sum all non-taxable income
			$otherdeducNtax = Number_Format($other_nontaxdeduction,$objClsMngeDecimal->getFinalDecimalSettings(),'.','') + Number_Format($totalregloan,$objClsMngeDecimal->getFinalDecimalSettings(),'.','');//sum all non-taxble deduction
			$SumAllDeduction = Number_Format($varSumAllTARate,$objClsMngeDecimal->getFinalDecimalSettings(),'.','') + Number_Format($SumALLABDeduction,$objClsMngeDecimal->getFinalDecimalSettings(),'.','') + Number_Format($totalTax,$objClsMngeDecimal->getFinalDecimalSettings(),'.','') + Number_Format($varDeduction,$objClsMngeDecimal->getFinalDecimalSettings(),'.','') + Number_Format($totalregloan,$objClsMngeDecimal->getFinalDecimalSettings(),'.','');//Sum all Deduction
			$varNetpay = $SumAllEarning - $SumAllDeduction;//NETPAY
			
//			echo "================Final Summary=================<br>";
//			echo $afterTaxGross." deduct the tax to the taxable gross<br>";
//			echo $grossandnontaxable." sum all non-taxable income<br>";
//			echo $otherdeducNtax." sum all non-taxble deduction<br>";
//			echo $SumAllDeduction." Sum all Deduction<br>";
//			echo $varNetpay." NETPAY<br>";
//			exit;
			
			$objClsMnge_PG->doSavePayStubEntry($details['paystub_id'],31,$grossandnontaxable);//save Total Non TS Earning to payroll_paystub_entry table
			$objClsMnge_PG->doSavePayStubEntry($details['paystub_id'],32,$otherdeducNtax);	  //save Total Non TS Deduction to payroll_paystub_entry table
            $objClsMnge_PG->doSavePayStubEntry($details['paystub_id'],2,$SumAllDeduction);    //save Total Deduction to payroll_paystub_entry table
			$objClsMnge_PG->doSavePayStubEntry($details['paystub_id'],5,$varNetpay);    	  //save netpay to payroll_paystub_entry table
			$objClsMnge_PG->doSavePayStubEntry($details['paystub_id'],30,$taxableGross);	  //save Taxablegross
			$objClsMnge_PG->doSavePayStubEntry($details['paystub_id'],8,$totalTax,$taxableGross,$conVertTaxper); //save W/H Tax
		
		//array structure of details on the paystub
		$arrPayStub[$ctr]['empinfo'] = array(
			 "emp_id" => $details['paystubdetails']['empinfo']['emp_id']
			,"emp_no" => $details['paystubdetails']['empinfo']['emp_no']
			,"fullname" => $details['paystubdetails']['empinfo']['fullname']
			,"jobcode" => $details['paystubdetails']['empinfo']['post_code']
			,"jobpos_name" => $details['paystubdetails']['empinfo']['jobpos_name']
			,"comp_name" => $details['paystubdetails']['empinfo']['comp_name']
			,"comp_add" => $details['paystubdetails']['empinfo']['comp_add']
			,"ud_name" => $details['paystubdetails']['empinfo']['ud_name']
			,"tax_ex_name" => $details['paystubdetails']['empinfo']['tax_ex_name']
			,"comp_id" => $details['paystubdetails']['empinfo']['comp_id']
			,"ud_id" => $details['paystubdetails']['empinfo']['ud_id']
			,"emptype_name" => $details['paystubdetails']['empinfo']['emptype_name']
			,"bankiemp_acct_no" => $details['paystubdetails']['empinfo']['bankiemp_acct_no']
			,"banklist_name" => $details['paystubdetails']['empinfo']['banklist_name']
			,"pi_emailone" => $details['paystubdetails']['empinfo']['pi_emailone']
			,"taxep_id" => $details['paystubdetails']['empinfo']['taxep_id']
			,"salaryclass_id" => $details['paystubdetails']['empinfo']['salaryclass_id']
			,"tt_pay_group" => $details['paystubdetails']['empinfo']['tt_pay_group']
			,"ud_code" => $details['paystubdetails']['empinfo']['ud_code']
			,"taxep_code" => $details['paystubdetails']['empinfo']['taxep_code']
			,"salarytype_id" => $details['paystubdetails']['empinfo']['salarytype_id']
		); 
		$arrPayStub[$ctr]['paystubdetail'] = array(
					"paystubsched" =>array(
						 "pps_id" => $details['paystubdetails']['paystubdetail']['paystubsched']['pps_id']
						,"pps_name" => $details['paystubdetails']['paystubdetail']['paystubsched']['pps_name']
						,"payperiod_id" => $details['paystubdetails']['paystubdetail']['paystubsched']['payperiod_id']
						,"paystub_id" => $details['paystubdetails']['paystubdetail']['paystubsched']['paystub_id']
						,"payperiod_start_date" => $details['paystubdetails']['paystubdetail']['paystubsched']['payperiod_start_date']
						,"payperiod_end_date" => $details['paystubdetails']['paystubdetail']['paystubsched']['payperiod_end_date']
						,"payperiod_trans_date" => $details['paystubdetails']['paystubdetail']['paystubsched']['payperiod_trans_date']
						,"schedSecond" => $details['paystubdetails']['paystubdetail']['paystubsched']['schedSecond']
						,"payperiod_freq" => $details['paystubdetails']['paystubdetail']['paystubsched']['payperiod_freq']
					)
					,"paystubaccount" => array(
						 "earning" => array(
							 "basic" => $SalaryDetails['salaryinfo_basicrate']
							,"Regulartime" => $BPperperiod
							,"COLA" => $COLAperperiod
							,"COLAperDay" => $colaperday
							,"totalDays" => $varActualDaysRender
							,"MWR" => $MWR
							,"DailyRate" => $rateperday
							,"HourlyRate" => $rateperhour
							,"OT" => array(
								 "OTDetails" => $this->otDetail
								,"TotalallOT" => $varSumAllOTRate + $SumNonTaxOTRate
								,"OTbackpay" => Number_Format("0",$objClsMngeDecimal->getFinalDecimalSettings(),'.','') + $SumNonTaxOTRate
								,"SumAllOTRate" => $varSumAllOTRate + $SumNonTaxOTRate
								)
						)
						,"deduction" => $deduction
						,"TUA"=> array(
							 "TADetails" => $this->taDetail
							,"TotalLeave" => $varSumAllTARate
						)
						,"leave_record" => $LeaveRecords
						,"government_regular" => $govreg_loan
						,"benefits" => $payStubSerialize
						,"amendments" => array(
								 $amendments
								,$recurring_amendments
								,"total_TSAEarning" => Number_Format($totalTSEarningAmendments,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
								,"total_TAEarning" => Number_Format($totalTEarningAmendments,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
								,"total_SAEarning" => Number_Format($totalSEarningAmendments,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
								,"total_NTSAEarning" => Number_Format($totalNTSEarningAmendments,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
								,"total_TSADeduction" => Number_Format($totalTSDeductionAmendments,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
								,"total_TADeduction" => Number_Format($totalTDeductionAmendments,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
								,"total_SADeduction" => Number_Format($totalSDeductionAmendments,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
								,"total_NTSADeduction" => Number_Format($totalNTSDeductionAmendments,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						)
						,"pstotal" => array(
						 	 "gross" => Number_Format($SumAllEarning,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"Basic Salary" => Number_Format($basicpay_rate,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"PGsalary" => Number_Format($basicgrosspg,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"gross_nontaxable_income" => Number_Format($grossandnontaxable,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"taxable_Gross" => Number_Format($taxableGross,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"Deduction" => Number_Format($SumAllDeduction,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"SatutoryDeduction" => Number_Format($varDeduction,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"W/H Tax" => Number_Format($totalTax,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"aftertaxgross" => Number_Format($afterTaxGross,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"Net Pay" => Number_Format($varNetpay,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"Loan_Total" => Number_Format($totalregloan,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"other_taxable_income" => Number_Format($totalTEarningAmendments,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"other_deduction" => Number_Format($otherdeducNtax,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"TotalEarning_payslip" => Number_Format($SumAllEarning,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"SumSABEarning" => Number_Format($SumSABEarning,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"SumTSABEarning" => Number_Format($SumTSABEarning,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"SumTABDeduction" => Number_Format($SumTABDeduction,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"SumTABEarning" => Number_Format($SumTABEarning,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"BaseSTATGross" => Number_Format($basicPG_ABS,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 	,"SumTSABDeduction" => Number_Format($SumTSABDeduction,$objClsMngeDecimal->getFinalDecimalSettings(),'.','')
						 )
					)	
			);
//		printa($arrPayStub);
//		exit;
		$objClsMnge_PG->doSavePayStubArr($arrPayStub);
		return $arrPayStub;
	}

	/**
	 * Delete Pay Details Record
	 *
	 * @param string $id_
	 */
	function doDelete(/*$id_ = "", */$paystub_id_ = "", $emp_id_ = ""){
		$sqlpstub = "SELECT paystub_id from payroll_paystub_report where payperiod_id = '".$paystub_id_."' and emp_id = '".$emp_id_."'";
		$rsResult = $this->conn->Execute($sqlpstub);
		//---------------DELETE LOAN HISTORY-------------->>
		$sqlLoanD = "SELECT loansum_id, loan_id, loansum_payment FROM loan_detail_sum WHERE paystub_id = '".$rsResult->fields['paystub_id']."'";
		$rsResultLoanD = $this->conn->Execute($sqlLoanD);
		if(count($rsResultLoanD)>0){
			foreach ($rsResultLoanD as $keyLoanD => $valLoanD){//delete payroll_paystub_entry record.
				$sqlLoanInfo = "SELECT loan_id,loantype_id,psa_id,emp_id,loan_ytd,loan_balance,loan_total FROM loan_info WHERE loan_id='".$valLoanD['loan_id']."'";
				$varLoanInfo = $this->conn->Execute($sqlLoanInfo);
				$varLoanBalance = $varLoanInfo->fields['loan_balance'] + $valLoanD['loansum_payment'];
				$varLoanYTD = $varLoanInfo->fields['loan_ytd'] - $valLoanD['loansum_payment'];
//				echo "<br>=========LOAN DELETE==========<br>";
//				echo $varLoanBalance." Loan Balance <br>";
//				echo $varLoanYTD." Loan YTD";
//				exit;
				$flds = array();
				$flds[] = "loan_balance = '".trim(addslashes($varLoanBalance))."'";
				$flds[] = "loan_ytd = '".trim(addslashes($varLoanYTD))."'";
				$fields = implode(", ",$flds);
				$sqlLoanInfoUPdate = "UPDATE loan_info set $fields WHERE loan_id='".$valLoanD['loan_id']."'";//update loan_info before delete loan_detail_sum
				$this->conn->Execute($sqlLoanInfoUPdate);
				$sqlLoanDsum = "DELETE from loan_detail_sum where loansum_id='".$valLoanD['loansum_id']."'";//delete record in loan_detail_sum
				$this->conn->Execute($sqlLoanDsum);
			}
		}
		//----------------------EXIT----------------------<<
		//-----------------UPDATE TA TABLE---------------->>
		$sqlTA = "UPDATE ta_emp_rec SET paystub_id = '0' WHERE paystub_id='".$rsResult->fields['paystub_id']."' AND emp_id='".$emp_id_."'";
		$this->conn->Execute($sqlTA);
		//----------------------EXIT----------------------<<
		//-----------------UPDATE OT TABLE---------------->>
		$sqlOT = "UPDATE ot_record SET paystub_id = '0' WHERE paystub_id='".$rsResult->fields['paystub_id']."' AND emp_id='".$emp_id_."'";
		$this->conn->Execute($sqlOT);
		//----------------------EXIT----------------------<<
		//------------UPDATE Amendments TABLE------------->>
		$sqlAmend = "UPDATE payroll_ps_amendemp SET paystub_id = '0' WHERE paystub_id='".$rsResult->fields['paystub_id']."' AND emp_id='".$emp_id_."'";
		$this->conn->Execute($sqlAmend);
		//----------------------EXIT----------------------<<
		//------------UPDATE Custom Fields TABLE---------->>
		$sqlCF = "UPDATE cf_detail SET paystub_id = '0' WHERE paystub_id='".$rsResult->fields['paystub_id']."' AND emp_id='".$emp_id_."'";
		$this->conn->Execute($sqlCF);
		//----------------------EXIT----------------------<<
		$sqlpsentry = "SELECT * from payroll_paystub_entry where paystub_id = '".$rsResult->fields['paystub_id']."' order by psa_id";
		$rsResult2 = $this->conn->Execute($sqlpsentry);
		if(count($rsResult2)>0){
			foreach ($rsResult2 as $keypsentry => $valpsentry){//delete payroll_paystub_entry record.
				$sqldel_psentry = "delete from payroll_paystub_entry where ppe_id='".$rsResult2->fields['ppe_id']."'";
				$this->conn->Execute($sqldel_psentry);
			}
		}
		$sqlTemp = "delete from z_formula_temp where paystub_id= '".$rsResult->fields['paystub_id']."'";
		$this->conn->Execute($sqlTemp);
		$sqlps = "delete from payroll_pay_stub where paystub_id = '".$rsResult->fields['paystub_id']."'";//delete payroll_pay_stub record.
		$this->conn->Execute($sqlps);
//		$sql = "delete from payroll_paystub_report where ppr_id=?";//delete payroll_paystub_report record.
//		$this->conn->Execute($sql,array($id_));
//		$_SESSION['eMsg']="Pay Details Successfully Deleted.";
	}

	/**
	 * Get all the Table Listings
	 *
	 * @return array
	 */
	function getTableList(){
//		$this->conn->debug=1;
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
				$qry[] = "pps_name LIKE '%$search_field%' || payperiod_trans_date LIKE '%$search_field%'";
			}
		}
		$listpgroup = $_SESSION[admin_session_obj][user_paygroup_list2];
		IF(count($listpgroup)>0){
			$qry[] = "psar.pps_id in (".$listpgroup.")";//pay group that can access
		}
		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" WHERE ".implode(" AND ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		 "viewdata" => "viewdata"
		,"paysched"=>"paysched"
		,"payperiod_trans_date" => "payperiod_trans_date"
		,"pperiod" => "pperiod"
		,"salaryclass_id" => "salaryclass_id"
		,"classification" => "classification"
		,"pp_stat_id" => "pp_stat_id"
		);

		if (isset($_GET['sortby'])) {
			$strOrderBy = " GROUP BY ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof']." , ".payperiod_id;
		} else {
			$strOrderBy = "GROUP BY ppp.payperiod_id DESC";
		}

		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$viewLink = "<a href=\"?statpos=payroll_details&edit=',psar.pps_id,'&ppsched_view=',ppp.payperiod_id,'&ppstat_id=',ppp.pp_stat_id,'\">',IFNULL(NULLIF(ppp.payperiod_name,''),psar.pps_name),'</a>";
//		$editLink = "<a href=\"?statpos=payroll_details&edit=',am.mnu_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/edit.png\" title=\"Edit\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
//		$delLink = "<a href=\"?statpos=payroll_details&delete=',am.mnu_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\"  border=0 width=\"16\" height=\"16\"></a>";
		
		// SqlAll Query
		$sql = "SELECT ppp.*, CONCAT('$viewLink') as viewdata, psar.pps_name,
				CONCAT(UPPER(date_format(payperiod_start_date,'%b %d')),' to ',UPPER(date_format(payperiod_end_date,'%b %d, %Y'))) as paysched,
				DATE_FORMAT(payperiod_start_date,'%d %b %Y %h:%i %p') as payperiod_start_date,
				DATE_FORMAT(payperiod_end_date,'%d %b %Y %h:%i %p') as payperiod_end_date,
				UPPER(DATE_FORMAT(payperiod_trans_date,'%M %d, %Y')) as payperiod_trans_date,
				IF(salaryclass_id='1','Daily',IF(salaryclass_id='2','Weekly',IF(salaryclass_id='3','Bi-Weekly',IF(salaryclass_id='4','Semi-monthly',IF(salaryclass_id='5','Monthly','Annual'))))) as salaryclass_id,
				IF(pp_stat_id='1','OPEN',IF(pp_stat_id='2','Locked - Pending Approval',IF(pp_stat_id='3','CLOSED','Post Adjustment'))) as pp_stat_id,
				IF(ppp.payperiod_type='2','YTD',IF(ppp.payperiod_type='3','BONUS',IF(ppp.payperiod_type='4','OTHERS','NORMAL'))) as classification,
				IF(ppp.payperiod_freq='1','1st',IF(ppp.payperiod_freq='2','2nd',IF(ppp.payperiod_freq='3','3rd',IF(ppp.payperiod_freq='4','4th',IF(ppp.payperiod_freq='5','5th','All'))))) as pperiod
					FROM payroll_paystub_report ppr
					JOIN payroll_pay_period ppp on (ppr.payperiod_id=ppp.payperiod_id)
					JOIN payroll_pay_period_sched psar on (psar.pps_id=ppp.pps_id)
					$criteria
					$strOrderBy";
					
		// Field and Table Header Mapping
		$arrFields = array(
		 "viewdata" => "Name"
		,"paysched"=>"Cut-offs"
		,"payperiod_trans_date" => "Pay Date"
		,"pperiod" => "Period"
		,"salaryclass_id" => "Type"
		,"classification" => "Payroll Type"
		,"pp_stat_id" => "Status"
		);
		// Column (table data) User Defined Attributes
		$arrAttribs = array(
		"mnu_ord"=>" align='center'",
		"pps_name"=>"width='150'",
		"salaryclass_name"=>"width='120'",
//		"viewdata"=>"width='50' align='center'"
		);
		// Process the Table List
		$tblDisplayList = new clsTableList($this->conn);
		$tblDisplayList->arrFields = $arrFields;
		$tblDisplayList->paginator->linkPage = "?$queryStr";
		$tblDisplayList->sqlAll = $sql;
		$tblDisplayList->sqlCount = $sqlcount;
		$tblDisplayList->tblBlock->assign("title","Payroll Details");
		return $tblDisplayList->getTableList($arrAttribs);
	}
	
	
	/**
	 * Get all the Table Listings
	 *
	 * @return array
	 */
	function getTableList_Emppaystub() {
//		printa($_GET);
//		$this->conn->debug=1;
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
		
//		$qry[]="pps.pps_id='".$_GET['edit']."'";
		$qry[]="ppr.payperiod_id='".$_GET['ppsched_view']."'";
		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		 "viewdata"=>"viewdata"
		,"emp_idnum"=>"emp_idnum"
		,"pi_lname"=>"pi_lname"
		,"pi_fname"=>"pi_fname"
		,"post_name"=>"post_name"
		,"ud_name"=>"ud_name"
		);

		if (isset($_GET['sortby'])) {
			$strOrderBy = " ORDER BY ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		} else {
			$strOrderBy = " ORDER BY pinfo.pi_lname";
		}
		
		// @note: This is used to count and check all the checkbox.
		// @note: SET t1 = 0
		$sql_set = "SET @t1:=0";
		$this->conn->Execute($sql_set);
		// Get total number of records and pass it to the javascript function CheckAll
		// SqlAll Query
		$sql2 = "SELECT COUNT(*) AS mycount
						FROM emp_masterfile empinfo
						JOIN emp_personal_info pinfo ON (pinfo.pi_id=empinfo.pi_id)
						JOIN payroll_paystub_report ppr ON (ppr.emp_id=empinfo.emp_id)
						JOIN payroll_pps_user pps ON (pps.emp_id=empinfo.emp_id)
						LEFT JOIN app_userdept dept ON (dept.ud_id=empinfo.ud_id)
						LEFT JOIN emp_position post ON (post.post_id=empinfo.post_id)
				$criteria";
		$rsResult = $this->conn->Execute($sql2);
		if (!$rsResult->EOF) {
			$mycount = $rsResult->fields['mycount'];
		}

		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		if ($_GET['ppstat_id']!='3') {
			$chkAttend = "<input type=\"checkbox\" name=\"chkAttend[]\" id=\"chkAttend[',@t1:=@t1+1,']\" value=\"',`ppr`.`emp_id`,'\" onclick=\"javascript:UncheckAll({$mycount});\">";
		}
		$viewLink = "<a href=\"?statpos=payroll_details&psdetails=',ppr.ppr_id,'&emp=',empinfo.emp_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/edit.png\" title=\"Edit Payslip\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
		$editLink = "<a href=\"?statpos=payroll_details&pdfrep=',ppr.ppr_id,'\" target=\"_blank\"><img src=\"".SYSCONFIG_THEME_URLPATH.SYSCONFIG_THEME."/images/pdf2.png\" title=\"Payslip PDF View\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
//		if ($_GET['ppstat_id']!='3') {
//			$delLink = "<a href=\"?statpos=payroll_details&delete=',ppr.ppr_id,'&pps_id=',pps.pps_id,'&payperiod_id=',ppr.payperiod_id,'&emp_id=',empinfo.emp_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete this record?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\"  border=0 width=\"16\" height=\"16\"></a>";
//		}
		
		// SqlAll Query
		$sql = "SELECT pinfo.pi_lname,pinfo.pi_fname, dept.ud_name,  post.post_name,
				CONCAT('$chkAttend','$editLink','$viewLink','$delLink') AS viewdata, empinfo.emp_idnum, ppr.*
						FROM emp_masterfile empinfo
						JOIN emp_personal_info pinfo ON (pinfo.pi_id=empinfo.pi_id)
						JOIN payroll_paystub_report ppr ON (ppr.emp_id=empinfo.emp_id)
						JOIN payroll_pps_user pps ON (pps.emp_id=empinfo.emp_id)
						LEFT JOIN app_userdept dept ON (dept.ud_id=empinfo.ud_id)
						LEFT JOIN emp_position post ON (post.post_id=empinfo.post_id)
						$criteria
						$strOrderBy";

		// Field and Table Header Mapping
		if ($_GET['ppstat_id']!='3') {
			$arrFields = array(
			 "viewdata"=>"<input title='Select All' type='checkbox' name='chkAttendAll' id='chkAttendAll' onclick='javascript:CheckAll({$mycount});' style='margin-left: 3px;' /><span style='margin-left: 3px;'>Action</span>"
			,"emp_idnum"=>"Employee No."
			,"pi_lname"=>"Last Name"
			,"pi_fname"=>"First Name"
			,"post_name"=>"Position"
			,"ud_name"=>"Department"
			);
			
			// Column (table data) User Defined Attributes
			$arrAttribs = array(
			"mnu_ord"=>"align='center'",
			"pps_name"=>"width='150'",
			"salaryclass_name"=>"width='120'",
			"viewdata"=>"width='60' align='center'"
			);
		} else {
			$arrFields = array(
			 "viewdata"=>"<span style='margin-left: 8px;'>Action</span>"
			,"emp_idnum"=>"Employee No."
			,"pi_lname"=>"Last Name"
			,"pi_fname"=>"First Name"
			,"post_name"=>"Position"
			,"ud_name"=>"Department"
			);
			
			// Column (table data) User Defined Attributes
			$arrAttribs = array(
			"mnu_ord"=>"align='center'",
			"pps_name"=>"width='150'",
			"salaryclass_name"=>"width='120'",
			"viewdata"=>"width='50' align='center'"
			);
		}
		
		// Process the Table List
		$tblDisplayList = new clsTableList($this->conn);
		$tblDisplayList->arrFields = $arrFields;
		$tblDisplayList->paginator->linkPage = "?$queryStr";
		$tblDisplayList->sqlAll = $sql;
		$tblDisplayList->sqlCount = $sqlcount;
//		$tblDisplayList->tblBlock->templateFile = "table_nosort.tpl.php";
		$tblDisplayList->tblBlock->assign("noSearchStart","<!--");
		$tblDisplayList->tblBlock->assign("noSearchEnd","-->");

		return $tblDisplayList->getTableList($arrAttribs);
	}
	
	/**
	 * Get all the Table Listings
	 *
	 * @return array
	 */
	function getTableList_paystubPayelement($paystub_,$emp_){
//		$this->conn->debug=1;
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

		$qry_[]="ppr.emp_id='".$emp_."'";
		$qry_[]="ppr.paystub_id='".$paystub_."'";
		
		// put all query array into one criteria string
		$ctr_ = (count($qry_)>0)?" where ".implode(" and ",$qry_):"";
		
				echo $sql_ = "Select ppp.payperiod_start_date, ppp.payperiod_end_date
					from payroll_paystub_report ppr 
					inner join payroll_pay_period ppp on (ppp.payperiod_id=ppr.payperiod_id)
					$ctr_";
				$rsResult = $this->conn->Execute($sql_);
				
		$qry[]="ppr.emp_id='".$emp_."'";
//		$qry[]="ppr.paystub_id='".$paystub_."'";
		$qry[]="(a.psamend_effect_date >= '".$rsResult->fields['payperiod_start_date']."' 
				 and a.psamend_effect_date <= '".$rsResult->fields['payperiod_end_date']."')";
		
		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		 "psa_name" => "psa_name"
		,"psamend_effect_date" => "psamend_effect_date"
		,"psamend_amount" => "psamend_amount"
		);

		if(isset($_GET['sortby'])){
			$strOrderBy = " order by ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof'];
		}

		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$viewLink = "<a href=\"?statpos=payroll_details&psdetails=',ppr.ppr_id,'\">',empinfo.emp_idnum,'</a>";
//		$editLink = "<a href=\"?statpos=payroll_details&edit=',am.mnu_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/edit.png\" title=\"Edit\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
//		$delLink = "<a href=\"?statpos=payroll_details&delete=',am.mnu_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\"  border=0 width=\"16\" height=\"16\"></a>";

		// SqlAll Query
		$sql = "select b.psa_name, a.psamend_effect_date, a.psamend_amount
						from payroll_paystub_report ppr 
						inner join payroll_ps_amendemp c on (c.emp_id=ppr.emp_id)
						inner join payroll_ps_amendment a on (a.psamend_id=c.psamend_id) 
						inner join payroll_ps_account b on a.psa_id=b.psa_id 
						$criteria
						$strOrderBy";

		// Field and Table Header Mapping
		$arrFields = array(
		 "psa_name" => "Pay Element"
		,"psamend_effect_date" => "Effective Date"
		,"psamend_amount" => "Amount"
		);

		// Column (table data) User Defined Attributes
		$arrAttribs = array(
		"mnu_ord"=>" align='center'",
		"pps_name"=>"width='150'",
		"salaryclass_name"=>"width='120'",
//		"viewdata"=>"width='50' align='center'"
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
	 * @note: used for PDF Report. 2010.02.18 jim
	 * @param $oData
	 */
	function getPDFResult($oData = array(),$isLocal=0){
//		printa($oData); exit;
        $orientation='P';
        $unit='mm';
        $format='LETTER';
        $unicode=true;
        $encoding="UTF-8";

        $oPDF = new clsPDF($orientation, $unit, $format, $unicode, $encoding);
		$objClsMngeDecimal = new Application();
        //set margins
        $oPDF->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $oPDF->SetHeaderMargin(PDF_MARGIN_HEADER);
        // set auto page break to false so that we can control the page break
        // depending on the desired number of lines on the ouput
        $oPDF->SetAutoPageBreak(false);
        // use a freesans font as a default font
//        $oPDF->SetFont('dotim5','',7);

        // suppress print header and footer
        $oPDF->setPrintHeader(true);
        $oPDF->setPrintFooter(false);

        $oPDF->AliasNbPages();

        // set initial pdf page
        $oPDF->AddPage();
        $oPDF->SetFillColor(255,255,255);
        $i= 0;

//        $oPDF->Image(\logo_example.png,160,5,0,0);
//        $oPDF->Image('C:/Program Files/xampp/htdocs/payroll/classes/util/tcpdf/images/logo_example.gif',200,200,200,200,'LTR');
//        $oPDF->Image('C:/Program Files/xampp/htdocs/payroll/classes/util/tcpdf/images/logo_example.gif', 50, 50, 100, '', '', 'http://www.tcpdf.org', '', false, 300);
//        $oPDF->Image('C:/Program Files/xampp/htdocs'.SYSCONFIG_DEFAULT_IMAGES_INCTEMP.'icons/edited/send_email.jpg', 177, 2, 25, '', '', 'http://www.tcpdf.org', 'LTR', false, 300);
//		  @note this image is the send icon in the payslip inalis ko muna para bukas...
//        $oPDF->Image(SYSCONFIG_ROOT_PATH2.SYSCONFIG_DEFAULT_IMAGES_INCTEMP.'icons/edited/send_email.jpg','', 3, 22, '', '', '', '', false, 300,'R');

        // set initila coordinates
        $coordX = 2;
        $coordY = 6;
        $coordYtop = 6;
        //used to line style...
        $style4 = array('dash' => 1,1);
		$style5 = array('dash' => 2,2);
		$style6 = array('dash' => 0);
//		$oPDF->Line($coordX, $coordY-2, $coordX+212, $coordY-2, $style = $style5);//line after header
		
//		$oPDF->Image(SYSCONFIG_ROOT_PATH2.SYSCONFIG_DEFAULT_IMAGES_INCTEMP.'icons/edited/sigma.png',$coordX+1, $coordY-1, 40, 5, '', 'http://www.sigmasoft.com.ph/', '', false, 300,'L');
		IF($isLocal==1){ //to check if Location
			$LocInfo = $this->getLocationInfo($oData['paystubdetails']['empinfo']['emp_id']);
			$comp_name = $LocInfo['branchinfo_name'];
			$comp_adds = $LocInfo['branchinfo_add'];
		}ELSE{
			$comp_name = $oData['paystubdetails']['empinfo']['comp_name'];
			$comp_adds = $oData['paystubdetails']['empinfo']['comp_add'];
		}
		$oPDF->SetFont('dejavusans','B',9);
        $oPDF->SetXY($coordX, $coordY);
        $oPDF->MultiCell(200, 2,$comp_name,0,'L',1);
		
		//Receiving Area
		//---------------------------------------------------->>
		$oPDF->SetFont('times','B',6);
        $oPDF->SetXY($coordX+170, $coordY+1);
        $oPDF->MultiCell(42, 2,$comp_name,0,'C',1);
        $oPDF->SetFont('dejavusans','B',7);
        $oPDF->SetXY($coordX+170, $coordY+33);
        $oPDF->MultiCell(42, 2,date("Md,Y",strtotime($oData['paystubdetails']['paystubdetail']['paystubsched']['payperiod_start_date'])).' to '.date("Md,Y",strtotime($oData['paystubdetails']['paystubdetail']['paystubsched']['payperiod_end_date'])),0,'C',1);
		$oPDF->SetFont('dejavusans','',7);
        $oPDF->SetXY($coordX+170, $coordY+42);
        $oPDF->MultiCell(44, 2,'TOTAL INCOME :',0,'L',1);
        $oPDF->SetXY($coordX+170, $coordY+50);
        $oPDF->MultiCell(44, 2,'TOTAL DEDUCTIONS :',0,'L',1);
        $oPDF->SetXY($coordX+170, $coordY+58);
        $oPDF->MultiCell(44, 2,'TAKE HOME PAY :',0,'L',1);
        $oPDF->SetXY($coordX+170, $coordY+45);
        $oPDF->MultiCell(41, 2,number_format($oData['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['TotalEarning_payslip'],2),0,'R',1);
        $oPDF->SetXY($coordX+171, $coordY+45);
        $oPDF->MultiCell(41, 2,'P',0,'L',1);
        $oPDF->SetXY($coordX+170, $coordY+53);
        $oPDF->MultiCell(41, 2,number_format($oData['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['Deduction'],2),0,'R',1);
        $oPDF->SetXY($coordX+171, $coordY+53);
        $oPDF->MultiCell(41, 2,'P',0,'L',1);
        $oPDF->SetFont('dejavusans','B',7);
        $oPDF->SetXY($coordX+170, $coordY+61);
        $oPDF->MultiCell(41, 2,number_format($oData['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['Net Pay'],2),0,'R',1);
        $oPDF->SetXY($coordX+171, $coordY+61);
        $oPDF->MultiCell(41, 2,'P',0,'L',1);
        $oPDF->SetFont('dejavusans','',6);
		$oPDF->SetXY($coordX+170, $coordY+7);
        $oPDF->MultiCell(130, 2,'RECEIVED BY :',0,'L',1);
        $oPDF->Line($coordX+171, $coordY+14.5, $coordX+210, $coordY+14.5, $style = $style6);//line
        $oPDF->SetXY($coordX+170, $coordY+14);
        $oPDF->MultiCell(130, 2,$oData['paystubdetails']['empinfo']['fullname'],0,'L',1);
        $oPDF->SetXY($coordX+170, $coordY+16);
        $oPDF->MultiCell(130, 2,'Emp No. : '.$oData['paystubdetails']['empinfo']['emp_no'],0,'L',1);
        $oPDF->SetXY($coordX+170, $coordY+18);
        $oPDF->MultiCell(130, 2,'DATE : ',0,'L',1);
        $oPDF->Line($coordX+180, $coordY+21.5, $coordX+210, $coordY+21.5, $style = $style6);//line
        $oPDF->SetXY($coordX+170, $coordY+26);
        $oPDF->MultiCell(42, 2,'Received the amount mentioned',0,'J',1);
        $oPDF->SetXY($coordX+170, $coordY+28);
        $oPDF->MultiCell(42, 2,'below as full payment for my',0,'J',1);
        $oPDF->SetXY($coordX+170, $coordY+30);
        $oPDF->MultiCell(42, 2,'salary/wages for the Pay Period',0,'J',1);
        //------------------------------------------------------<<
        
        //header
        //------------------------------------------------------>>
		$oPDF->SetFont('dejavusans','',6);
		$coordY = $coordY + 3; 
        $oPDF->SetXY($coordX, $coordY);
        $oPDF->MultiCell(200, 2,$comp_adds,0,'L',1);
        $coordY+=$oPDF->getFontSize()+1;
        
       	$oPDF->SetFont('dejavusans','',7);
        $oPDF->SetXY($coordX, $coordY);
        $oPDF->MultiCell(15, 2, "Name/ID",0,'L',1);
        $oPDF->SetFont('dejavusans','B',7);
        $oPDF->SetXY($coordX+13, $coordY);
        $oPDF->MultiCell(85, 2, ':  '.strtoupper($oData['paystubdetails']['empinfo']['fullname']).' ('.$oData['paystubdetails']['empinfo']['emp_no'].')',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
            
        $oPDF->SetFont('dejavusans','',7);
        $oPDF->SetXY($coordX+115, $coordY);
        $oPDF->MultiCell(25, 2, "PAYDATE",0,'L',1);
        $oPDF->SetXY($coordX+130, $coordY);
        $oPDF->MultiCell(30, 2, ": ".date("M d, Y",strtotime($oData['paystubdetails']['paystubdetail']['paystubsched']['payperiod_trans_date'])),0,'L',1);
        $coordY+=$oPDF->getFontSize()+.5;
            
        $oPDF->SetXY($coordX, $coordY);
        $oPDF->MultiCell(19, 2, "Position",0,'L',1);
        $oPDF->SetXY($coordX+13, $coordY);
        $oPDF->MultiCell(110, 2, ':  '.$oData['paystubdetails']['empinfo']['jobpos_name'],0,'L',1);
        $oPDF->SetXY($coordX+115, $coordY);
        $oPDF->MultiCell(30, 2, "DEPT",0,'L',1);
        $oPDF->SetXY($coordX+130, $coordY);
        $oPDF->MultiCell(40, 2, ': '.$oData['paystubdetails']['empinfo']['ud_name'],0,'L',1);
        $coordY+=$oPDF->getFontSize()+2;

        $y = $coordY;
        $oPDF->Line($coordX, $coordY, $coordX+169, $coordY, $style = $style6);//line after header
        $coordY+=.1;
        $oPDF->Line($coordX, $coordY, $coordX+169, $coordY, $style = $style6);//line after header2
        //---------------------------------------------------<<
        
        //Head Body
        	$oPDF->SetFont('dejavusans','B',7);
            $oPDF->SetXY($coordX, $coordY);
            $oPDF->MultiCell(84.5, 2, 'EARNINGS',0,'C',1, 0, 0, 0, TRUE, 0, TRUE);
            $oPDF->SetXY($coordX+84.5, $coordY);
            $oPDF->MultiCell(84.5, 2, "DEDUCTIONS",0,'C',1, 0, 0, 0, TRUE, 0, TRUE);
            $oPDF->SetFont('dejavusans','',7);
            $coordY+=$oPDF->getFontSize()+2;
			$oPDF->Line($coordX, $coordY, $coordX+169, $coordY, $style=$style6);//bottom line
			
            $y_2nd = $coordY;
        //Earnings COLUMN
        	//head
        	$nodays = $oData['paystubdetails']['paystubdetail']['paystubaccount']['earning']['totalDays'];
        	if($nodays!='' AND $nodays > 0){
	        	$oPDF->SetXY($coordX+20, $coordY);
	            $oPDF->MultiCell(58, 2, "Rate",0,'L',1);
	            $oPDF->SetXY($coordX+40, $coordY);
	            $oPDF->MultiCell(20, 2, "Days",0,'L',1);
	            $coordY+=$oPDF->getFontSize();
        	}
        	//Basic Details
            $oPDF->SetXY($coordX+2, $coordY);
            $basicPAY = $oData['paystubdetails']['paystubdetail']['paystubaccount']['earning']['Regulartime'];
            IF($basicPAY != '' AND $basicPAY > 0){
	            $oPDF->MultiCell(58, 2, "BASIC",0,'L',1);
	            if($nodays!='' AND $nodays > 0){
		            $oPDF->SetXY($coordX+20, $coordY);
			        $oPDF->MultiCell(20, 2, number_format($oData['paystubdetails']['paystubdetail']['paystubaccount']['earning']['basic'],2),0,'L',1);
		            $oPDF->SetXY($coordX+40, $coordY);
			        $oPDF->MultiCell(20, 2, $nodays,0,'L',1);
	            }
	            $oPDF->SetXY($coordX+60, $coordY);
	            $oPDF->MultiCell(24.5, 2, number_format($oData['paystubdetails']['paystubdetail']['paystubaccount']['earning']['Regulartime'],2),0,'R',1);
	            $coordY+=$oPDF->getFontSize();
            }
            
			//BONUS PAY
			$bonusPAY = $oData['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['Bonus Pay'];
            IF($bonusPAY != '' AND $bonusPAY > 0){
	            $oPDF->MultiCell(58, 2, "BONUS PAY",0,'L',1);
	            $oPDF->SetXY($coordX+60, $coordY);
	            $oPDF->MultiCell(24.5, 2, number_format($bonusPAY,$objClsMngeDecimal->getFinalDecimalSettings()),0,'R',1);
	            $coordY+=$oPDF->getFontSize();
            }
            
            //COLA Details
            $colaAmount = $oData['paystubdetails']['paystubdetail']['paystubaccount']['earning']['COLA'];
            if($colaAmount != '' AND $colaAmount > 0){
	            $oPDF->SetXY($coordX+2, $coordY);
	            $oPDF->MultiCell(58, 2, "COLA",0,'L',1);
	            $oPDF->SetXY($coordX+20, $coordY);
	        	$oPDF->MultiCell(20, 2, number_format($oData['paystubdetails']['paystubdetail']['paystubaccount']['earning']['COLAperDay'],2),0,'L',1);
	            $oPDF->SetXY($coordX+40, $coordY);
	            $oPDF->MultiCell(20, 2, $nodays,0,'L',1);
	            $oPDF->SetXY($coordX+60, $coordY);
	            $oPDF->MultiCell(24.5, 2, number_format($oData['paystubdetails']['paystubdetail']['paystubaccount']['earning']['COLA'],2),0,'R',1);
	            $coordY+=$oPDF->getFontSize();
            }
            // OT listing
			$psa_OT = $oData['paystubdetails']['paystubdetail']['paystubaccount']['earning']['OT']['OTDetails'];  
            if(count($psa_OT)>0){
//            	@Note:Hide for January Payroll
				$oPDF->SetXY($coordX+2, $coordY);
		        $oPDF->MultiCell(58, 2, 'OverTime',0,'L',1);
				$coordY+=$oPDF->getFontSize();
            	for($k=0;$k<count($psa_OT);$k++){
		                $oPDF->SetXY($coordX+4, $coordY);
		                $oPDF->MultiCell(22, 2, substr(trim($psa_OT[$k]['ot_name']),0,12),0,'L',1);
		                $oPDF->SetXY($coordX+22, $coordY);
		                $oPDF->MultiCell(8, 2,' H ',0,'R',1);
		                $oPDF->SetXY($coordX+14, $coordY);
		                $oPDF->MultiCell(28, 2,$psa_OT[$k]['totaltimehr'].' = ',0,'R',1);
		                $oPDF->SetXY($coordX+40, $coordY);
		                $oPDF->MultiCell(28, 2, number_format($psa_OT[$k]['otamount'],$objClsMngeDecimal->getFinalDecimalSettings()),0,'L',1);
		                $coordY+=$oPDF->getFontSize();
	                }
                $oPDF->SetXY($coordX+2, $coordY);
	            $oPDF->MultiCell(58, 2, 'Total OverTime',0,'L',1);
	            $oPDF->SetXY($coordX+60, $coordY);
	            $oPDF->MultiCell(24.5, 2, number_format($oData['paystubdetails']['paystubdetail']['paystubaccount']['earning']['OT']['TotalallOT'],$objClsMngeDecimal->getFinalDecimalSettings()),0,'R',1);
	            $coordY+=$oPDF->getFontSize();
            }
			// Amendment listing
			$psa_amendment = $oData['paystubdetails']['paystubdetail']['paystubaccount']['amendments'][0];

			for($a=0;$a<count($psa_amendment) ;$a++){
                IF ($psa_amendment[$a]['psa_type']==1) {
                	IF($psa_amendment[$a]['amendemp_amount']!=0){
		                $oPDF->SetXY($coordX+2, $coordY);
		                $oPDF->MultiCell(58, 2, $psa_amendment[$a]['psa_name'],0,'L',1); 
		                $oPDF->SetXY($coordX+60, $coordY);
		                $oPDF->MultiCell(24.5, 2, number_format($psa_amendment[$a]['amendemp_amount'],2),0,'R',1);
		                $coordY+=$oPDF->getFontSize();
                	}
                }
            }
            // benifits listing
            $psa_benifits = $oData['paystubdetails']['paystubdetail']['paystubaccount']['benefits'];   
            IF(count($psa_benifits)>0){
                FOR($v=0;$v<count($psa_benifits);$v++){
                	IF($psa_benifits[$v]['psa_type']!=2){
                		IF($psa_benifits[$v]['ben_payperday']!=0){
			                $oPDF->SetXY($coordX+2, $coordY);
			                $oPDF->MultiCell(58, 2, $psa_benifits[$v]['psa_name'],0,'L',1);
			                $oPDF->SetXY($coordX+60, $coordY);
			                $oPDF->MultiCell(24.5, 2, number_format($psa_benifits[$v]['ben_payperday'],2),0,'R',1);
			                $coordY+=$oPDF->getFontSize();
                		}
                	}
                }
            }
            
            $oPDF->SetXY($coordX, 55);
            $oPDF->MultiCell(60, 2, "TOTAL EARNINGS :",0,'R',1);
            $oPDF->SetXY($coordX+60, 55);
            $oPDF->MultiCell(24.5, 2, number_format($oData['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['TotalEarning_payslip'],2),0,'R',1);
            $coordY+=$oPDF->getFontSize();

            $end = 60;
            $oPDF->Line($coordX+60, $y+5, $coordX+60, $end-1, $style = $style5);//left line
            $oPDF->Line($coordX+84.5, $y+1, $coordX+84.5, $end-1, $style = $style6);//middle line
            $oPDF->Line($coordX+146, $y+5, $coordX+146, $end-1, $style = $style5);//right line
            $oPDF->Line($coordX, $end, $coordX+169, $end, $style=$style6);//bottom line
            
        //Deduction COLUMN
            $coordY = $y_2nd;
            $taxwh = $oData['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['W/H Tax'];
			if($taxwh != '0.00' AND $taxwh > 0){
	            $oPDF->SetXY($coordX+87, $coordY);
	            $oPDF->MultiCell(60, 2, "W/H TAX",0,'L',1);
	            $oPDF->SetXY($coordX+146, $coordY);
	            $oPDF->MultiCell(24, 2, number_format($taxwh,2),0,'R',1);
	            $coordY+=$oPDF->getFontSize()+0;
			}
			$HDMF = $oData['paystubdetails']['paystubdetail']['paystubaccount']['deduction']['Pag-ibig'];
			IF($HDMF > 0 ){
	            $oPDF->SetXY($coordX+87, $coordY);
	            $oPDF->MultiCell(60, 2, "HDMF",0,'L',1);
	            $oPDF->SetXY($coordX+146, $coordY);
	            $oPDF->MultiCell(24, 2, number_format($HDMF,2),0,'R',1);
	            $coordY+=$oPDF->getFontSize()+0;
			}
			$PHIC = $oData['paystubdetails']['paystubdetail']['paystubaccount']['deduction']['PhilHealth'];
			IF($PHIC > 0){
	            $oPDF->SetXY($coordX+87, $coordY);
	            $oPDF->MultiCell(60, 2, "PHIC",0,'L',1);
	            $oPDF->SetXY($coordX+146, $coordY);
	            $oPDF->MultiCell(24, 2, number_format($PHIC,2),0,'R',1);
	            $coordY+=$oPDF->getFontSize()+0;
			}
			$SSS = $oData['paystubdetails']['paystubdetail']['paystubaccount']['deduction']['SSS'];
			IF($SSS > 0){
	            $oPDF->SetXY($coordX+87, $coordY);
	            $oPDF->MultiCell(60, 2, "SSS",0,'L',1);
	            $oPDF->SetXY($coordX+146, $coordY);
	            $oPDF->MultiCell(24, 2, number_format($SSS,2),0,'R',1);
	            $coordY+=$oPDF->getFontSize()+0;
			}
            // Leave/TA Listing
			$psa_ta = $oData['paystubdetails']['paystubdetail']['paystubaccount']['TUA']['TADetails'];
	        $total_ta = $oData['paystubdetails']['paystubdetail']['paystubaccount']['TUA']['TotalLeave'];
            IF(count($psa_ta)>0){
            	IF($total_ta != 0){
//            	@Note:Hide for January Payroll
				$oPDF->SetXY($coordX+87, $coordY);
                $oPDF->MultiCell(60, 2, 'TA Deduction',0,'L',1);
                $coordY+=$oPDF->getFontSize();
            	FOR($k=0;$k<count($psa_ta);$k++){
            		IF($psa_ta[$k]['ta_name']!='Custom Days'){
	            		$oPDF->SetXY($coordX+90, $coordY);
			            $oPDF->MultiCell(25, 2, substr(trim($psa_ta[$k]['ta_name']),0,12),0,'L',1);
			            $oPDF->SetXY($coordX+95, $coordY);
			            $oPDF->MultiCell(20, 2,$psa_ta[$k]['ratetype'],0,'R',1);
			            $oPDF->SetXY($coordX+100, $coordY);
			            $oPDF->MultiCell(28, 2, number_format($psa_ta[$k]['totaltimehr'],$objClsMngeDecimal->getFinalDecimalSettings()).' = ',0,'R',1);
			            $oPDF->SetXY($coordX+126, $coordY);
			            $oPDF->MultiCell(28, 2, number_format($psa_ta[$k]['taamount'],$objClsMngeDecimal->getFinalDecimalSettings()),0,'L',1);
			            $coordY+=$oPDF->getFontSize();
            		}
                }
                $oPDF->SetXY($coordX+87, $coordY);
                $oPDF->MultiCell(60, 2, 'Total TA Deduction',0,'L',1);
                $oPDF->SetXY($coordX+146, $coordY);
                $oPDF->MultiCell(24, 2, number_format($oData['paystubdetails']['paystubdetail']['paystubaccount']['TUA']['TotalLeave'],$objClsMngeDecimal->getFinalDecimalSettings()),0,'R',1);
                $coordY+=$oPDF->getFontSize();
            	}
            }
            //amendment deduction
            $psa_amendment = $oData['paystubdetails']['paystubdetail']['paystubaccount']['amendments'][0];
            if(count($psa_amendment)>0){
                foreach($psa_amendment as $key => $val){
                    if ($val['psa_type']==2) {
                    $oPDF->SetXY($coordX+87, $coordY);
                    $oPDF->MultiCell(60, 2, $val['psa_name'],0,'L',1);
                    $oPDF->SetXY($coordX+146, $coordY);
                    $oPDF->MultiCell(24, 2, number_format($val['amendemp_amount'],2),0,'R',1);
                    $coordY+=$oPDF->getFontSize();
                    }
                }
            }
            
			// benifits Deduction
            $psa_benifits = $oData['paystubdetails']['paystubdetail']['paystubaccount']['benefits'];            
            if(count($psa_benifits)>0){
                for($v=0;$v<count($psa_benifits);$v++){
                	if($psa_benifits[$v]['psa_type']!=1){
		                $oPDF->SetXY($coordX+87, $coordY);
		                $oPDF->MultiCell(60, 2, $psa_benifits[$v]['psa_name'],0,'L',1);
		                $oPDF->SetXY($coordX+146, $coordY);
		                $oPDF->MultiCell(24, 2, number_format($psa_benifits[$v]['ben_payperday'],2),0,'R',1);
		                $coordY+=$oPDF->getFontSize();
                	}
                }
            }
            
            //Loan listing
			$loan_info_ = $oData['paystubdetails']['paystubdetail']['paystubaccount']['government_regular'];
			for($v=0;$v<count($loan_info_);$v++){
                $oPDF->SetXY($coordX+87, $coordY);
                $oPDF->MultiCell(60, 2, $loan_info_[$v]['psa_name'],0,'L',1);
                $oPDF->SetXY($coordX+121, $coordY);
                $oPDF->MultiCell(60, 2, " Bal: ".number_format($loan_info_[$v]['loan_balance'],2),0,'L',1);
                $oPDF->SetXY($coordX+146, $coordY);
                $oPDF->MultiCell(24, 2, number_format($loan_info_[$v]['loan_payperperiod'],2),0,'R',1);
                $coordY+=$oPDF->getFontSize()+0;
            }
            
            $oPDF->SetXY($coordX+87, 55);
            $oPDF->MultiCell(59, 2, "TOTAL DEDUCTIONS :",0,'R',1);
            $oPDF->SetXY($coordX+146, 55);
            $oPDF->MultiCell(24, 2, number_format($oData['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['Deduction'],2),0,'R',1);
            $coordY+=$oPDF->getFontSize()+0;

            
         //bottom
            $coordY=$end;
            $oPDF->Line($coordX+84.5, $coordY+1, $coordX+84.5, $end+21,$style = $style6);//bottom middle line
            $oPDF->Line($coordX+170, $coordYtop, $coordX+170, $end+21, $style = $style4);//out right line
            
            $oPDF->SetXY($coordX, $coordY);
            $oPDF->MultiCell(16, 2, "BANK  :",0,'L',1);
            $oPDF->SetXY($coordX+15, $coordY);
            $oPDF->MultiCell(100, 2, $oData['paystubdetails']['empinfo']['banklist_name'].' / '.$oData['paystubdetails']['empinfo']['bankiemp_acct_no'],0,'L',1);
            
            $oPDF->SetFont('dejavusans','B',7);
            $oPDF->SetXY($coordX+93, $coordY);
            $oPDF->MultiCell(25, 2,'NET PAY',0,'L',1);
            $oPDF->SetXY($coordX+120, $coordY);
            $oPDF->MultiCell(25, 2, ":  ".number_format($oData['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['Net Pay'],2),0,'L',1);
            $coordY+=$oPDF->getFontSize()+1;
            $oPDF->Line($coordX+93, $coordY+1, $coordX+160, $coordY+1, $style=$style6);//netpay line
            
//          @note: hide for january payroll.
          	$leave_record_ = $oData['paystubdetails']['paystubdetail']['paystubaccount']['leave_record'];
			$coordY_leave = $coordY;
			if(count($leave_record_)>0){
				$oPDF->SetFont('dejavusans','B',7);
				$oPDF->SetXY($coordX+40, $coordY);
	            $oPDF->MultiCell(15, 2, "Credit",0,'L',1);
	            $oPDF->SetXY($coordX+55, $coordY);
	            $oPDF->MultiCell(15, 2, "Bal",0,'L',1);
	            $oPDF->SetXY($coordX+70, $coordY);
	            $oPDF->MultiCell(15, 2,'Taken',0,'L',1);
	            $coordY+=$oPDF->getFontSize()-2;
	            $oPDF->SetFont('dejavusans','',7);
				for($v=0;$v<count($leave_record_);$v++){
					IF($leave_record_[$v]['empleave_credit'] > 0){
		                $oPDF->SetXY($coordX, $coordY_leave+3);
		                $oPDF->MultiCell(40, 2, $leave_record_[$v]['leave_name'],0,'L',1);
		                $oPDF->SetXY($coordX+40, $coordY_leave+3);
		                $oPDF->MultiCell(15, 2, number_format($leave_record_[$v]['empleave_credit'],2),0,'L',1);
		                $oPDF->SetXY($coordX+55, $coordY_leave+3);
		                $oPDF->MultiCell(15, 2, number_format($leave_record_[$v]['empleave_available_day'],2),0,'L',1);
		                $oPDF->SetXY($coordX+70, $coordY_leave+3);
		                $oPDF->MultiCell(15, 2, number_format($leave_record_[$v]['empleave_used_day'],2),0,'L',1);
		                $coordY_leave+=$oPDF->getFontSize()+0;
					}
	            }
			}
            $oPDF->SetFont('dejavusans','B',7);
            $oPDF->SetXY($coordX+123, $coordY);
            $oPDF->MultiCell(30, 2, "YTD",0,'L',1);
			$coordY+=$oPDF->getFontSize()+.5;
            $oPDF->SetFont('dejavusans','',7);
            $oPDF->SetXY($coordX+93, $coordY);
            $oPDF->MultiCell(30, 2, "GROSS PAY",0,'L',1);
            $oPDF->SetXY($coordX+120, $coordY);
            $sqlYear = "SELECT payperiod_period_year,payperiod_period,payperiod_freq FROM payroll_pay_period WHERE payperiod_id='".$oData['payperiod_id']."'";
			$getYear = $this->conn->Execute($sqlYear);
            $ytdgrosspay = $this->getYTD($oData['emp_id'],4,$getYear->fields['payperiod_period_year'],$getYear->fields['payperiod_period'],$getYear->fields['payperiod_freq']);
            $oPDF->MultiCell(25, 2, ":   ".number_format($ytdgrosspay['ytdamount'],2),0,'L',1);
            $coordY+=$oPDF->getFontSize()+0;
            
            $oPDF->SetXY($coordX+93, $coordY);
            $oPDF->MultiCell(30, 2, "TAXABLE GROSS",0,'L',1);
            $oPDF->SetXY($coordX+120, $coordY);
            $ytdtaxgross = $this->getYTD($oData['emp_id'],30,$getYear->fields['payperiod_period_year'],$getYear->fields['payperiod_period'],$getYear->fields['payperiod_freq']);
            $oPDF->MultiCell(25, 2, ":   ".number_format($ytdtaxgross['ytdamount'],2),0,'L',1);
            $coordY+=$oPDF->getFontSize()+0;
            
            $oPDF->SetFont('dejavusans','',6.5);
            $oPDF->SetXY($coordX+93, $coordY);
            $oPDF->MultiCell(30, 2, "Statutory Contribution",0,'L',1);
            $oPDF->SetXY($coordX+120, $coordY);
            $oPDF->SetFont('dejavusans','',7);
            $ytdstat = $this->getYTD($oData['emp_id'],27,$getYear->fields['payperiod_period_year'],$getYear->fields['payperiod_period'],$getYear->fields['payperiod_freq']);
            $oPDF->MultiCell(25, 2, ":   ".number_format($ytdstat['ytdamount'],2),0,'L',1);
            $coordY+=$oPDF->getFontSize()+0;
            
            $oPDF->SetXY($coordX+93, $coordY);
            $oPDF->MultiCell(30, 2, "W/H TAX",0,'L',1);
            $oPDF->SetXY($coordX+120, $coordY);
            $ytdwhtax = $this->getYTD($oData['emp_id'],8,$getYear->fields['payperiod_period_year'],$getYear->fields['payperiod_period'],$getYear->fields['payperiod_freq']);
            $oPDF->MultiCell(25, 2, ":   ".number_format($ytdwhtax['ytdamount'],2),0,'L',1);
            $coordY+=$oPDF->getFontSize()*2;
            
            $oPDF->SetXY($coordX, $coordY_leave+3);
            $oPDF->MultiCell(19, 2, "TAX STATUS",0,'L',1);
            $oPDF->SetXY($coordX+19, $coordY_leave+3);
            $oPDF->MultiCell(90, 2, ': '.$oData['paystubdetails']['empinfo']['tax_ex_name'],0,'L',1);
            $coordY+=$oPDF->getFontSize()+7;
            $oPDF->Line($coordX, $coordY, $coordX+212, $coordY, $style=$style5);//bottom line
        // get the pdf output
        $output = $oPDF->Output("payslip_".$oData['paystubdetails']['empinfo']['fullname'].date('Y-m-d').".pdf");
        if (!empty($output)) {
            return $output;
        }
    }
    
    /**
     * @note: Get YTD values
     * @param $emp_id_
     * @param $psa_id_
     * @param $paystub_id_
     */
    function getYTD($emp_id_ = null, $psa_id_ = null, $year_ = null, $period_ = null, $freq_ = null, $isbonus_ = false){
    	/**if (is_null($emp_id_)) { return $arrData; }
		if (is_null($psa_id_) || empty($psa_id_)) { return $arrData; }
    	if (is_null($psa_id_) || empty($psa_id_)) { return $arrData; }
    	$qry[]="a.emp_id = '".$emp_id_."'";
		$qry[]="b.psa_id = '".$psa_id_."'";
		$qry[]="a.paystub_id <= '".$paystub_id_."'";
		// put all query array into one string criteria
		$criteria = " WHERE ".implode(" and ",$qry);
		$qryYTD = "SELECT SUM(b.ppe_amount) as ytdamount
					FROM payroll_pay_stub a
					JOIN payroll_paystub_entry b on(b.paystub_id=a.paystub_id) 
					$criteria
					GROUP BY b.psa_id";
		$varYTD = $this->conn->Execute($qryYTD);
		if(!$varDeducH->EOF){
			$varYTD_ = $varYTD->fields;
			return $varYTD_;
		}**/
    if (is_null($emp_id_)) { return $arrData; }
		if (is_null($psa_id_) || empty($psa_id_)) { return $arrData; }
    	if (is_null($psa_id_) || empty($psa_id_)) { return $arrData; }
    	$qry[]="d.emp_id = '".$emp_id_."'";
        if($psa_id_==4){
    		$qry[]="a.psa_id in (30,27,31)";
    	} else {
			$qry[]="a.psa_id = '".$psa_id_."'";
    	}
		//$qry[]="a.paystub_id <= '".$paystub_id_."'";
		//$qry[]="c.payperiod_period_year = '".$year_."'";
		if($isbonus_){
			$qry[]="((c.payperiod_period_year = '".$year_."' and c.payperiod_period <= '".$period_."'))";
		} else {
		$qry[]="((c.payperiod_period_year = '".$year_."' and c.payperiod_period < '".$period_."') OR 
				(c.payperiod_period_year = '".$year_."' and c.payperiod_period = '".$period_."' and c.payperiod_freq <= ".$freq_."))";
		}
		// put all query array into one string criteria
		$criteria = " WHERE ".implode(" and ",$qry);
		/**$qryYTD = "SELECT SUM(b.ppe_amount) as ytdamount
					FROM payroll_pay_stub a
					JOIN payroll_paystub_entry b on (b.paystub_id=a.paystub_id)
					JOIN payroll_pay_period c on (c.payperiod_id=a.payperiod_id)
					$criteria
					GROUP BY b.psa_id";**/
		/**$qryYTD = "SELECT entry_amt+amm_amt AS ytdamount
				FROM 
				(SELECT COALESCE(SUM(a.ppe_amount), 0) AS entry_amt FROM payroll_paystub_entry a
				INNER JOIN payroll_pay_stub b ON (b.paystub_id=a.paystub_id)
				INNER JOIN payroll_pay_period c ON (c.payperiod_id=b.payperiod_id)
				INNER JOIN payroll_paystub_report d ON (d.paystub_id=b.paystub_id)
				INNER JOIN payroll_ps_account e ON (e.psa_id=a.psa_id)
				$criteria) entry_tbl,
				
				(select COALESCE(sum(a.amendemp_amount),0) as amm_amt 
				from payroll_ps_amendemp a 
				inner join payroll_ps_amendment b on (b.psamend_id=a.psamend_id) 
				inner join payroll_pay_stub c on (c.paystub_id=a.paystub_id) 
				inner join payroll_pay_period d on (d.payperiod_id=c.payperiod_id)
				INNER JOIN payroll_ps_account e ON (e.psa_id=b.psa_id) 
				WHERE d.payperiod_period_year='{$year_}' AND a.emp_id={$emp_id_} AND b.psa_id='$psa_id_'
				AND a.paystub_id NOT IN (SELECT a.paystub_id FROM payroll_paystub_entry a
				INNER JOIN payroll_pay_stub b ON (b.paystub_id=a.paystub_id)
				INNER JOIN payroll_pay_period c ON (c.payperiod_id=b.payperiod_id)
				INNER JOIN payroll_paystub_report d ON (d.paystub_id=b.paystub_id)
				INNER JOIN payroll_ps_account e ON (e.psa_id=a.psa_id)
				$criteria)) amm_tbl";**/
		$qryYTD = "SELECT SUM(a.ppe_amount) AS ytdamount 
					FROM payroll_paystub_entry a 
					INNER JOIN payroll_pay_stub b ON (b.paystub_id=a.paystub_id) 
					INNER JOIN payroll_pay_period c ON (c.payperiod_id=b.payperiod_id) 
					INNER JOIN payroll_paystub_report d ON (d.paystub_id=b.paystub_id) 
					INNER JOIN payroll_ps_account e ON (e.psa_id=a.psa_id) 
					$criteria";
		$varYTD = $this->conn->Execute($qryYTD);
		if(!$varDeducH->EOF){
			$varYTD_ = $varYTD->fields;
			return $varYTD_;
		}
    }
    
	/**
	 * @note: get Contrib
	 * 
	 * @param unknown_type $sc_id_
	 * @param unknown_type $empCont
	 */
    function getContrib($sc_id_ = null, $empCont = null) {
    	$arrData = array();
    	if (is_null($sc_id_)) {
				return $arrData;
		}
		if (is_null($empCont) || empty($empCont)) {
				return $arrData;
		}
		$qry[] = "a.sc_id = '".$sc_id_."'";
		$qry[] = "b.scr_ee = '".$empCont."'";
		$criteria = count($qry)>0 ? " where ".implode(' and ',$qry) : '';  	
		$sql = "select *
						from statutory_contribution a
						inner join sc_records b on (a.sc_id = b.sc_id)
						$criteria";

		$rsResult = $this->conn->Execute($sql);
		if (!$rsResult->EOF) {
			return $rsResult->fields;
		}

    }
    
    
    function getSalaryDetails($emp_id_ = null) {
    	$arrData = array();
    	if (is_null($emp_id_)) {
			return $arrData;
		}
		$qry[] = "emp_id = '".$emp_id_."'";
		$qry[] = "salaryinfo_isactive = '1'";
		$criteria = count($qry)>0 ? " where ".implode(' and ',$qry) : '';
    	$sqlsal ="Select * from salary_info $criteria";
		$rsResult_ = $this->conn->Execute($sqlsal);
    	if (!$rsResult_->EOF) {
			return $rsResult_->fields;
		}
    }
    
    /**
     * note: This function is used to get the Day Rate.
     * @param $SalaryDetails_
     */
    function getBPDayRate($emp_id_ = null, $salarytype_id_ = null, $BP = 0 ) {
    	
    	$qry[] = "emp_id = '".$emp_id_."'";
		$criteria = count($qry)>0 ? " where ".implode(' and ',$qry) : '';
    	$sql = "Select * from payroll_pps_user a inner join payroll_pay_period_sched b on (a.pps_id=b.pps_id) inner join factor_rate c on (b.fr_id=c.fr_id) $criteria";
    	$rsResult = $this->conn->Execute($sql);
    	
    	/*compute rate per day, per hour, per sec per employee */
			if ($salarytype_id_==5) {
				//convert monthly rate
				$rateperday = (($BP*12)/$rsResult->fields['fr_dayperyear']);
				$rateperhour = (($rateperday)/$rsResult->fields['fr_hrperday']);
				$ratepersec = (($rateperhour)/3600);
			} elseif ($salarytype_id_==1) {
				//convert hourly rate 
				$rateperday = ($BP*$rsResult->fields['fr_hrperday']);
				$rateperhour = $BP;
				$ratepersec = ($BP/3600);
			} elseif ($salarytype_id_==3) {
				//convert weekly rate
				$rateperday = ($BP/$rsResult->fields['fr_dayperweek']);
				$rateperhour = (($rateperday)/$rsResult->fields['fr_hrperday']);
				$ratepersec = (($rateperhour)/3600);
			} elseif ($salarytype_id_==6) {
				//convert annual rate to second
				$rateperday = ($BP/$rsResult->fields['fr_dayperyear']);
				$rateperhour = (($rateperday)/$rsResult->fields['fr_hrperday']);
				$ratepersec = (($rateperhour)/3600);
			} elseif ($salarytype_id_==4) {
				//convert bi-weekly rate
				$rateperday = ($BP/($rsResult->fields['fr_dayperweek'] * 2));
				$rateperhour = (($rateperday)/$rsResult->fields['fr_hrperday']);
				$ratepersec = (($rateperhour)/3600);
			} else {
				//convert daily rate
				$rateperday = $BP;
				$rateperhour = ($rateperhour/$rsResult->fields['fr_hrperday']);
				$ratepersec = (($rateperhour)/3600);
			}
			
			
			//compute regular time base to payperiod type.
			//eg. semi-monthy, monthly, daily ect.
            //Daily
			if ($rsResult->fields['salaryclass_id'] == 1) {
					$PayPeriodBP = $rateperday;
     
            //weekly
			} elseif ($rsResult->fields['salaryclass_id'] == 2) {
				if ($salarytype_id_==5) {
					//monthly
					$PayPeriodBP = (($BP*12)/52);
				} elseif ($salarytype_id_==3) {
					//weekly
					$PayPeriodBP = $BP;
				} elseif ($salarytype_id_==6) {
					//annual
					$PayPeriodBP = $BP/52;
				} elseif ($salarytype_id_==4) {
					//bi-weekly
					$PayPeriodBP = $BP/2;
				} else {
					//daily & hourly
					$PayPeriodBP = $rateperday * $rsResult->fields['fr_dayperweek'];
				}
			
			//bi-weekly
			} elseif ($rsResult->fields['salaryclass_id'] == 3) {
				if ($salarytype_id_==5) {
					//monthly
					$PayPeriodBP = (($BP*12)/26);
				} elseif ($salarytype_id_==3) {
					//weekly
					$PayPeriodBP = $BP*2;
				} elseif ($salarytype_id_==6) {
					//annual
					$PayPeriodBP = $BP/26;
				} elseif ($salarytype_id_==4) {
					//bi-weekly
					$PayPeriodBP = $BP;
				} else {
					//daily & hourly
					$PayPeriodBP = ($rateperday * ($rsResult->fields['fr_dayperweek']*2));
				}
			
			// semi-monthly
			} elseif ($rsResult->fields['salaryclass_id'] == 4) {
				if ($salarytype_id_==5) {
					//monthly
					if ($rsResult->fields['emp_hiredate'] > date("Y-m-d",$rsResult->fields['payperiod_start_date'])) {
//						@todo: for back pay :D 
//						$vardate = $rsResult->fields['emp_hiredate'] - $rsResult->fields['payperiod_start_date'];
//						echo $rsResult->fields['emp_hiredate'];
//						echo "<br>"; 
//						echo date('Y-m-d',$rsResult->fields['payperiod_start_date']);
//						echo "<br>";
//						echo $vardate;
//						echo "<br>";
//						echo "hi";
//						exit;
						$PayPeriodBP = ($BP/2);
					} else {
						$PayPeriodBP = ($BP/2);	
					}
				} elseif ($salarytype_id_==3) {
					//weekly
					$PayPeriodBP = $BP*2;
				} elseif ($salarytype_id_==6) {
					//annual
					$PayPeriodBP = (($BP/12)/2);
				} elseif ($salarytype_id_==4) {
					//bi-weekly
					$PayPeriodBP = $BP;
				} else {
					//daily & hourly
					$PayPeriodBP = ($rateperday * ($rsResult->fields['fr_dayperweek']*2));
				}
            //monthly
			} elseif ($rsResult->fields['salaryclass_id'] == 5) {
				if ($salarytype_id_==5) {
					//monthly
					$PayPeriodBP = $BP;
				} elseif ($salarytype_id_==3) {
					//weekly
					$PayPeriodBP = (($BP*12)/52);
				} elseif ($salarytype_id_==6) {
					//annual
					$PayPeriodBP = $BP/12;
				} elseif ($salarytype_id_==4) {
					//bi-weekly
					$PayPeriodBP = (($BP*12)/13);
				} else {
					//daily & hourly
					$PayPeriodBP = (($rateperday * $rsResult->fields['fr_dayperyear'])/12);
				}
			//annual	
			} else {
				if ($salarytype_id_==5) {
					//monthly
					$PayPeriodBP = $BP*12;
				} elseif ($salarytype_id_==3) {
					//weekly
					$PayPeriodBP = $BP*52;
				} elseif ($salarytype_id_==6) {
					//annual
					$PayPeriodBP = $BP;
				} elseif ($salarytype_id_==4) {
					//bi-weekly
					$PayPeriodBP = $BP*26;
				} else {
					//daily & hourly
					$PayPeriodBP = ($rateperday * $rsResult->fields['fr_dayperyear']);
				}
			}
			
			// Return the day rate value
			return $DayRate = array("rateperday" => $rateperday,"rateperhour" => $rateperhour,"ratepersec" => $ratepersec,"PayPeriodBP" => $PayPeriodBP);
    }

    function remove($pData) {
		$flds = array();
		$flds_ = array();
		$ctr = 0;
		do {
			$this->doDelete($_GET['ppsched_view'], $pData['chkAttend'][$ctr]);
			$sql_remove = "DELETE FROM payroll_paystub_report WHERE payperiod_id = '".$_GET['ppsched_view']."' AND emp_id = '".$pData['chkAttend'][$ctr]."'";
			$rsResult = $this->conn->Execute($sql_remove);
			$ctr++;
		} while($ctr < sizeof($pData['chkAttend']));
		$_SESSION['eMsg']="Pay Details Successfully Deleted.";
    }
    
	function doValidate($pData_ = array(), $kind_of_validation) {
		$isValid = true;
		if (empty($pData_['chkAttend'])) {
			$isValid = false;
			$_SESSION['eMsg'][] = "Please Select Employee/s to {$kind_of_validation}.";
		}
		return $isValid;
	}
    
	function getLocationInfo($emp_id_=null){
		$arrData = array();
    	if (is_null($emp_id_)) {
			return $arrData;
		}
		$qry[] = "a.emp_id = '".$emp_id_."'";
		$criteria = count($qry)>0 ? " WHERE ".implode(' AND ',$qry) : '';
    	$sqlsal ="SELECT b.* FROM emp_masterfile a JOIN branch_info b on (a.branchinfo_id=b.branchinfo_id) $criteria";
		$rsResult_ = $this->conn->Execute($sqlsal);
    	if (!$rsResult_->EOF) {
			return $rsResult_->fields;
		}
	}
	
function getPDFResult_Format3($oData = array(),$isLocal=0){
//		printa($oData); exit;
        $orientation='P';
        $unit='mm';
        $format='LETTER';
        $unicode=true;
        $encoding="UTF-8";

        $oPDF = new clsPDF($orientation, $unit, $format, $unicode, $encoding);
		$objClsMngeDecimal = new Application();
        //set margins
        $oPDF->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $oPDF->SetHeaderMargin(PDF_MARGIN_HEADER);
        // set auto page break to false so that we can control the page break
        // depending on the desired number of lines on the ouput
        $oPDF->SetAutoPageBreak(false);
        // use a freesans font as a default font
//        $oPDF->SetFont('dotim5','',7);

        // suppress print header and footer
        $oPDF->setPrintHeader(true);
        $oPDF->setPrintFooter(false);

        $oPDF->AliasNbPages();

        // set initial pdf page
        $oPDF->AddPage();
        $oPDF->SetFillColor(255,255,255);
        $i= 0;

//        $oPDF->Image(\logo_example.png,160,5,0,0);
//        $oPDF->Image('C:/Program Files/xampp/htdocs/payroll/classes/util/tcpdf/images/logo_example.gif',200,200,200,200,'LTR');
//        $oPDF->Image('C:/Program Files/xampp/htdocs/payroll/classes/util/tcpdf/images/logo_example.gif', 50, 50, 100, '', '', 'http://www.tcpdf.org', '', false, 300);
//        $oPDF->Image('C:/Program Files/xampp/htdocs'.SYSCONFIG_DEFAULT_IMAGES_INCTEMP.'icons/edited/send_email.jpg', 177, 2, 25, '', '', 'http://www.tcpdf.org', 'LTR', false, 300);
//		  @note this image is the send icon in the payslip inalis ko muna para bukas...
//        $oPDF->Image(SYSCONFIG_ROOT_PATH2.SYSCONFIG_DEFAULT_IMAGES_INCTEMP.'icons/edited/send_email.jpg','', 3, 22, '', '', '', '', false, 300,'R');

        // set initila coordinates
        $coordX = 2;
        $coordY = 6;
        $coordYtop = 6;
        //used to line style...
        $style4 = array('dash' => 1,1);
		$style5 = array('dash' => 2,2);
		$style6 = array('dash' => 0);
//		$oPDF->Line($coordX, $coordY-2, $coordX+212, $coordY-2, $style = $style5);//line after header
		
//		$oPDF->Image(SYSCONFIG_ROOT_PATH2.SYSCONFIG_DEFAULT_IMAGES_INCTEMP.'icons/edited/sigma.png',$coordX+1, $coordY-1, 40, 5, '', 'http://www.sigmasoft.com.ph/', '', false, 300,'L');
		IF($isLocal==1){ //to check if Location
			$LocInfo = $this->getLocationInfo($oData['paystubdetails']['empinfo']['emp_id']);
			$comp_name = $LocInfo['branchinfo_name'];
			$comp_adds = $LocInfo['branchinfo_add'];
		}ELSE{
			$comp_name = $oData['paystubdetails']['empinfo']['comp_name'];
			$comp_adds = $oData['paystubdetails']['empinfo']['comp_add'];
		}
		$oPDF->SetFont('dejavusans','B',9);
        $oPDF->SetXY($coordX, $coordY);
        $oPDF->MultiCell(200, 2,$comp_name,0,'L',1);
        
        //header
        //------------------------------------------------------>>
		$oPDF->SetFont('dejavusans','',6);
		$coordY = $coordY + 3; 
        $oPDF->SetXY($coordX, $coordY);
        $oPDF->MultiCell(200, 2,$comp_adds,0,'L',1);
        $coordY+=$oPDF->getFontSize()+1;
        
       	$oPDF->SetFont('dejavusans','',7);
        $oPDF->SetXY($coordX, $coordY);
        $oPDF->MultiCell(15, 2, "Name/ID",0,'L',1);
        $oPDF->SetFont('dejavusans','B',7);
        $oPDF->SetXY($coordX+13, $coordY);
        $oPDF->MultiCell(85, 2, ':  '.strtoupper($oData['paystubdetails']['empinfo']['fullname']).' ('.$oData['paystubdetails']['empinfo']['emp_no'].')',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
            
        $oPDF->SetFont('dejavusans','',7);
        $oPDF->SetXY($coordX+145, $coordY);
        $oPDF->MultiCell(25, 2, "PAYDATE",0,'L',1);
        $oPDF->SetXY($coordX+160, $coordY);
        $oPDF->MultiCell(30, 2, ": ".date("M d, Y",strtotime($oData['paystubdetails']['paystubdetail']['paystubsched']['payperiod_trans_date'])),0,'L',1);
        $coordY+=$oPDF->getFontSize()+.5;
            
        $oPDF->SetXY($coordX, $coordY);
        $oPDF->MultiCell(19, 2, "Position",0,'L',1);
        $oPDF->SetXY($coordX+13, $coordY);
        $oPDF->MultiCell(110, 2, ':  '.$oData['paystubdetails']['empinfo']['jobpos_name'],0,'L',1);
        $oPDF->SetXY($coordX+145, $coordY);
        $oPDF->MultiCell(30, 2, "DEPT",0,'L',1);
        $oPDF->SetXY($coordX+160, $coordY);
        $oPDF->MultiCell(40, 2, ': '.$oData['paystubdetails']['empinfo']['ud_name'],0,'L',1);
        $coordY+=$oPDF->getFontSize()+2;

        $y = $coordY;
        $oPDF->Line($coordX, $coordY, $coordX+212, $coordY, $style = $style6);//line after header
        $coordY+=.1;
        $oPDF->Line($coordX, $coordY, $coordX+212, $coordY, $style = $style6);//line after header2
        //---------------------------------------------------<<
        
        //Head Body
        	$oPDF->SetFont('dejavusans','B',7);
            $oPDF->SetXY($coordX+11, $coordY);
            $oPDF->MultiCell(84.5, 2, 'EARNINGS',0,'C',1, 0, 0, 0, TRUE, 0, TRUE);
            $oPDF->SetXY($coordX+114.5, $coordY);
            $oPDF->MultiCell(84.5, 2, "DEDUCTIONS",0,'C',1, 0, 0, 0, TRUE, 0, TRUE);
            $oPDF->SetFont('dejavusans','',7);
            $coordY+=$oPDF->getFontSize()+2;
			$oPDF->Line($coordX, $coordY, $coordX+212, $coordY, $style=$style6);//bottom line
			
            $y_2nd = $coordY;
        //Earnings COLUMN
        	//head
        	$nodays = $oData['paystubdetails']['paystubdetail']['paystubaccount']['earning']['totalDays'];
        	if($nodays!='' AND $nodays > 0){
	        	$oPDF->SetXY($coordX+20, $coordY);
	            $oPDF->MultiCell(58, 2, "Rate",0,'L',1);
	            $oPDF->SetXY($coordX+40, $coordY);
	            $oPDF->MultiCell(20, 2, "Days",0,'L',1);
	            $coordY+=$oPDF->getFontSize();
        	}
        	//Basic Details
            $oPDF->SetXY($coordX+2, $coordY);
            $basicPAY = $oData['paystubdetails']['paystubdetail']['paystubaccount']['earning']['Regulartime'];
            IF($basicPAY != '' AND $basicPAY > 0){
	            $oPDF->MultiCell(58, 2, "BASIC",0,'L',1);
	            if($nodays!='' AND $nodays > 0){
		            $oPDF->SetXY($coordX+30, $coordY);
			        $oPDF->MultiCell(20, 2, number_format($oData['paystubdetails']['paystubdetail']['paystubaccount']['earning']['basic'],2),0,'L',1);
		            $oPDF->SetXY($coordX+50, $coordY);
			        $oPDF->MultiCell(20, 2, $nodays,0,'L',1);
	            }
	            $oPDF->SetXY($coordX+75, $coordY);
	            $oPDF->MultiCell(24.5, 2, number_format($oData['paystubdetails']['paystubdetail']['paystubaccount']['earning']['Regulartime'],2),0,'R',1);
	            $coordY+=$oPDF->getFontSize();
            }
            
			//BONUS PAY
			$bonusPAY = $oData['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['Bonus Pay'];
            IF($bonusPAY != '' AND $bonusPAY > 0){
	            $oPDF->MultiCell(58, 2, "BONUS PAY",0,'L',1);
	            $oPDF->SetXY($coordX+75, $coordY);
	            $oPDF->MultiCell(24.5, 2, number_format($bonusPAY,$objClsMngeDecimal->getFinalDecimalSettings()),0,'R',1);
	            $coordY+=$oPDF->getFontSize();
            }
            
            //COLA Details
            $colaAmount = $oData['paystubdetails']['paystubdetail']['paystubaccount']['earning']['COLA'];
            if($colaAmount != '' AND $colaAmount > 0){
	            $oPDF->SetXY($coordX+2, $coordY);
	            $oPDF->MultiCell(58, 2, "COLA",0,'L',1);
	            $oPDF->SetXY($coordX+30, $coordY);
	        	$oPDF->MultiCell(20, 2, number_format($oData['paystubdetails']['paystubdetail']['paystubaccount']['earning']['COLAperDay'],2),0,'L',1);
	            $oPDF->SetXY($coordX+50, $coordY);
	            $oPDF->MultiCell(20, 2, $nodays,0,'L',1);
	            $oPDF->SetXY($coordX+75, $coordY);
	            $oPDF->MultiCell(24.5, 2, number_format($oData['paystubdetails']['paystubdetail']['paystubaccount']['earning']['COLA'],2),0,'R',1);
	            $coordY+=$oPDF->getFontSize();
            }
            // OT listing
			$psa_OT = $oData['paystubdetails']['paystubdetail']['paystubaccount']['earning']['OT']['OTDetails'];  
            if(count($psa_OT)>0){
//            	@Note:Hide for January Payroll
				$oPDF->SetXY($coordX+2, $coordY);
		        $oPDF->MultiCell(58, 2, 'OverTime',0,'L',1);
				$coordY+=$oPDF->getFontSize();
            	for($k=0;$k<count($psa_OT);$k++){
		                $oPDF->SetXY($coordX+4, $coordY);
		                $oPDF->MultiCell(32, 2, substr(trim($psa_OT[$k]['ot_name']),0,12),0,'L',1);
		                $oPDF->SetXY($coordX+22, $coordY);
		                $oPDF->MultiCell(18, 2,' H ',0,'R',1);
		                $oPDF->SetXY($coordX+24, $coordY);
		                $oPDF->MultiCell(28, 2,$psa_OT[$k]['totaltimehr'].' = ',0,'R',1);
		                $oPDF->SetXY($coordX+50, $coordY);
		                $oPDF->MultiCell(28, 2, number_format($psa_OT[$k]['otamount'],$objClsMngeDecimal->getFinalDecimalSettings()),0,'L',1);
		                $coordY+=$oPDF->getFontSize();
	                }
                $oPDF->SetXY($coordX+2, $coordY);
	            $oPDF->MultiCell(58, 2, 'Total OverTime',0,'L',1);
	            $oPDF->SetXY($coordX+75, $coordY);
	            $oPDF->MultiCell(24.5, 2, number_format($oData['paystubdetails']['paystubdetail']['paystubaccount']['earning']['OT']['TotalallOT'],$objClsMngeDecimal->getFinalDecimalSettings()),0,'R',1);
	            $coordY+=$oPDF->getFontSize();
            }
			// Amendment listing
			$psa_amendment = $oData['paystubdetails']['paystubdetail']['paystubaccount']['amendments'][0];
            for($a=0;$a<count($psa_amendment) ;$a++){
                IF ($psa_amendment[$a]['psa_type']==1) {
                	IF($psa_amendment[$a]['amendemp_amount']!=0){
		                $oPDF->SetXY($coordX+2, $coordY);
		                $oPDF->MultiCell(58, 2, $psa_amendment[$a]['psa_name'],0,'L',1);
		                $oPDF->SetXY($coordX+75, $coordY);
		                $oPDF->MultiCell(24.5, 2, number_format($psa_amendment[$a]['amendemp_amount'],2),0,'R',1);
		                $coordY+=$oPDF->getFontSize();
                	}
                }
            }
            // benifits listing
            $psa_benifits = $oData['paystubdetails']['paystubdetail']['paystubaccount']['benefits'];   
            IF(count($psa_benifits)>0){
                FOR($v=0;$v<count($psa_benifits);$v++){
                	IF($psa_benifits[$v]['psa_type']!=2){
                		IF($psa_benifits[$v]['ben_payperday']!=0){
			                $oPDF->SetXY($coordX+2, $coordY);
			                $oPDF->MultiCell(58, 2, $psa_benifits[$v]['psa_name'],0,'L',1);
			                $oPDF->SetXY($coordX+75, $coordY);
			                $oPDF->MultiCell(24.5, 2, number_format($psa_benifits[$v]['ben_payperday'],2),0,'R',1);
			                $coordY+=$oPDF->getFontSize();
                		}
                	}
                }
            }
            
            $oPDF->SetXY($coordX+17, 55);
            $oPDF->MultiCell(60, 2, "TOTAL EARNINGS :",0,'R',1);
            $oPDF->SetXY($coordX+75, 55);
            $oPDF->MultiCell(24.5, 2, number_format($oData['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['TotalEarning_payslip'],2),0,'R',1);
            $coordY+=$oPDF->getFontSize();

            $end = 60;
            $oPDF->Line($coordX+77, $y+5, $coordX+77, $end-1, $style = $style5);//left line
            $oPDF->Line($coordX+102, $y+1, $coordX+102, $end-1, $style = $style6);//middle line
            $oPDF->Line($coordX+185, $y+5, $coordX+185, $end-1, $style = $style5);//right line
            $oPDF->Line($coordX, $end, $coordX+212, $end, $style=$style6);//bottom line
            
        //Deduction COLUMN
            $coordY = $y_2nd;
            $taxwh = $oData['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['W/H Tax'];
			if($taxwh != '0.00' AND $taxwh > 0){
	            $oPDF->SetXY($coordX+105, $coordY);
	            $oPDF->MultiCell(60, 2, "W/H TAX",0,'L',1);
	            $oPDF->SetXY($coordX+185, $coordY);
	            $oPDF->MultiCell(24, 2, number_format($taxwh,2),0,'R',1);
	            $coordY+=$oPDF->getFontSize()+0;
			}
			$HDMF = $oData['paystubdetails']['paystubdetail']['paystubaccount']['deduction']['Pag-ibig'];
			IF($HDMF > 0 ){
	            $oPDF->SetXY($coordX+105, $coordY);
	            $oPDF->MultiCell(60, 2, "HDMF",0,'L',1);
	            $oPDF->SetXY($coordX+185, $coordY);
	            $oPDF->MultiCell(24, 2, number_format($HDMF,2),0,'R',1);
	            $coordY+=$oPDF->getFontSize()+0;
			}
			$PHIC = $oData['paystubdetails']['paystubdetail']['paystubaccount']['deduction']['PhilHealth'];
			IF($PHIC > 0){
	            $oPDF->SetXY($coordX+105, $coordY);
	            $oPDF->MultiCell(60, 2, "PHIC",0,'L',1);
	            $oPDF->SetXY($coordX+185, $coordY);
	            $oPDF->MultiCell(24, 2, number_format($PHIC,2),0,'R',1);
	            $coordY+=$oPDF->getFontSize()+0;
			}
			$SSS = $oData['paystubdetails']['paystubdetail']['paystubaccount']['deduction']['SSS'];
			IF($SSS > 0){
	            $oPDF->SetXY($coordX+105, $coordY);
	            $oPDF->MultiCell(60, 2, "SSS",0,'L',1);
	            $oPDF->SetXY($coordX+185, $coordY);
	            $oPDF->MultiCell(24, 2, number_format($SSS,2),0,'R',1);
	            $coordY+=$oPDF->getFontSize()+0;
			}
            // Leave/TA Listing
			$psa_ta = $oData['paystubdetails']['paystubdetail']['paystubaccount']['TUA']['TADetails'];
	        $total_ta = $oData['paystubdetails']['paystubdetail']['paystubaccount']['TUA']['TotalLeave'];
            IF(count($psa_ta)>0){
            	IF($total_ta != 0){
//            	@Note:Hide for January Payroll
				$oPDF->SetXY($coordX+105, $coordY);
                $oPDF->MultiCell(60, 2, 'TA Deduction',0,'L',1);
                $coordY+=$oPDF->getFontSize();
            	FOR($k=0;$k<count($psa_ta);$k++){
            		IF($psa_ta[$k]['ta_name']!='Custom Days'){
	            		$oPDF->SetXY($coordX+110, $coordY);
			            $oPDF->MultiCell(25, 2, substr(trim($psa_ta[$k]['ta_name']),0,12),0,'L',1);
			            $oPDF->SetXY($coordX+115, $coordY);
			            $oPDF->MultiCell(20, 2,$psa_ta[$k]['ratetype'],0,'R',1);
			            $oPDF->SetXY($coordX+120, $coordY);
			            $oPDF->MultiCell(28, 2, number_format($psa_ta[$k]['totaltimehr'],$objClsMngeDecimal->getFinalDecimalSettings()).' = ',0,'R',1);
			            $oPDF->SetXY($coordX+146, $coordY);
			            $oPDF->MultiCell(28, 2, number_format($psa_ta[$k]['taamount'],$objClsMngeDecimal->getFinalDecimalSettings()),0,'L',1);
			            $coordY+=$oPDF->getFontSize();
            		}
                }
                $oPDF->SetXY($coordX+105, $coordY);
                $oPDF->MultiCell(60, 2, 'Total TA Deduction',0,'L',1);
                $oPDF->SetXY($coordX+185, $coordY);
                $oPDF->MultiCell(24, 2, number_format($oData['paystubdetails']['paystubdetail']['paystubaccount']['TUA']['TotalLeave'],$objClsMngeDecimal->getFinalDecimalSettings()),0,'R',1);
                $coordY+=$oPDF->getFontSize();
            	}
            }
            //amendment deduction
            $psa_amendment = $oData['paystubdetails']['paystubdetail']['paystubaccount']['amendments'][0];
            if(count($psa_amendment)>0){
                foreach($psa_amendment as $key => $val){
                    if ($val['psa_type']==2) {
                    $oPDF->SetXY($coordX+105, $coordY);
                    $oPDF->MultiCell(60, 2, $val['psa_name'],0,'L',1);
                    $oPDF->SetXY($coordX+185, $coordY);
                    $oPDF->MultiCell(24, 2, number_format($val['amendemp_amount'],2),0,'R',1);
                    $coordY+=$oPDF->getFontSize();
                    }
                }
            }
            
			// benifits Deduction
            $psa_benifits = $oData['paystubdetails']['paystubdetail']['paystubaccount']['benefits'];            
            if(count($psa_benifits)>0){
                for($v=0;$v<count($psa_benifits);$v++){
                	if($psa_benifits[$v]['psa_type']!=1){
		                $oPDF->SetXY($coordX+105, $coordY);
		                $oPDF->MultiCell(60, 2, $psa_benifits[$v]['psa_name'],0,'L',1);
		                $oPDF->SetXY($coordX+185, $coordY);
		                $oPDF->MultiCell(24, 2, number_format($psa_benifits[$v]['ben_payperday'],2),0,'R',1);
		                $coordY+=$oPDF->getFontSize();
                	}
                }
            }
            
            //Loan listing
			$loan_info_ = $oData['paystubdetails']['paystubdetail']['paystubaccount']['government_regular'];
			for($v=0;$v<count($loan_info_);$v++){
                $oPDF->SetXY($coordX+105, $coordY);
                $oPDF->MultiCell(60, 2, $loan_info_[$v]['psa_name'],0,'L',1);
                $oPDF->SetXY($coordX+160, $coordY);
                $oPDF->MultiCell(60, 2, " Bal: ".number_format($loan_info_[$v]['loan_balance'],2),0,'L',1);
                $oPDF->SetXY($coordX+185, $coordY);
                $oPDF->MultiCell(24, 2, number_format($loan_info_[$v]['loan_payperperiod'],2),0,'R',1);
                $coordY+=$oPDF->getFontSize()+0;
            }
            
            $oPDF->SetXY($coordX+125, 55);
            $oPDF->MultiCell(59, 2, "TOTAL DEDUCTIONS :",0,'R',1);
            $oPDF->SetXY($coordX+185, 55);
            $oPDF->MultiCell(24, 2, number_format($oData['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['Deduction'],2),0,'R',1);
            $coordY+=$oPDF->getFontSize()+0;

            
         //bottom
            $coordY=$end;
            $oPDF->Line($coordX+102, $coordY+1, $coordX+102, $end+21,$style = $style6);//bottom middle line
            //$oPDF->Line($coordX+170, $coordYtop, $coordX+170, $end+21, $style = $style4);//out right line
            
            $oPDF->SetXY($coordX, $coordY);
            $oPDF->MultiCell(16, 2, "BANK  :",0,'L',1);
            $oPDF->SetXY($coordX+15, $coordY);
            $oPDF->MultiCell(100, 2, $oData['paystubdetails']['empinfo']['banklist_name'].' / '.$oData['paystubdetails']['empinfo']['bankiemp_acct_no'],0,'L',1);
            
            $oPDF->SetFont('dejavusans','B',7);
            $oPDF->SetXY($coordX+130, $coordY);
            $oPDF->MultiCell(25, 2,'NET PAY',0,'L',1);
            $oPDF->SetXY($coordX+160, $coordY);
            $oPDF->MultiCell(25, 2, ":  ".number_format($oData['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['Net Pay'],2),0,'L',1);
            $coordY+=$oPDF->getFontSize()+1;
            $oPDF->Line($coordX+125, $coordY+1, $coordX+185, $coordY+1, $style=$style6);//netpay line
            
//          @note: hide for january payroll.
          	$leave_record_ = $oData['paystubdetails']['paystubdetail']['paystubaccount']['leave_record'];
			$coordY_leave = $coordY;
			if(count($leave_record_)>0){
				$oPDF->SetFont('dejavusans','B',7);
				$oPDF->SetXY($coordX+40, $coordY);
	            $oPDF->MultiCell(15, 2, "Credit",0,'L',1);
	            $oPDF->SetXY($coordX+55, $coordY);
	            $oPDF->MultiCell(15, 2, "Bal",0,'L',1);
	            $oPDF->SetXY($coordX+70, $coordY);
	            $oPDF->MultiCell(15, 2,'Taken',0,'L',1);
	            $coordY+=$oPDF->getFontSize()-2;
	            $oPDF->SetFont('dejavusans','',7);
				for($v=0;$v<count($leave_record_);$v++){
					IF($leave_record_[$v]['empleave_credit'] > 0){
		                $oPDF->SetXY($coordX, $coordY_leave+3);
		                $oPDF->MultiCell(40, 2, $leave_record_[$v]['leave_name'],0,'L',1);
		                $oPDF->SetXY($coordX+40, $coordY_leave+3);
		                $oPDF->MultiCell(15, 2, number_format($leave_record_[$v]['empleave_credit'],2),0,'L',1);
		                $oPDF->SetXY($coordX+55, $coordY_leave+3);
		                $oPDF->MultiCell(15, 2, number_format($leave_record_[$v]['empleave_available_day'],2),0,'L',1);
		                $oPDF->SetXY($coordX+70, $coordY_leave+3);
		                $oPDF->MultiCell(15, 2, number_format($leave_record_[$v]['empleave_used_day'],2),0,'L',1);
		                $coordY_leave+=$oPDF->getFontSize()+0;
					}
	            }
			}
            $oPDF->SetFont('dejavusans','B',7);
            $oPDF->SetXY($coordX+163, $coordY);
            $oPDF->MultiCell(30, 2, "YTD",0,'L',1);
			$coordY+=$oPDF->getFontSize()+.5;
            $oPDF->SetFont('dejavusans','',7);
            $oPDF->SetXY($coordX+130, $coordY);
            $oPDF->MultiCell(30, 2, "GROSS PAY",0,'L',1);
            $oPDF->SetXY($coordX+160, $coordY);
            $sqlYear = "SELECT payperiod_period_year,payperiod_period,payperiod_freq FROM payroll_pay_period WHERE payperiod_id='".$oData['payperiod_id']."'";
			$getYear = $this->conn->Execute($sqlYear);
			$ytdgrosspay = $this->getYTD($oData['emp_id'],4,$getYear->fields['payperiod_period_year'],$getYear->fields['payperiod_period'],$getYear->fields['payperiod_freq']);
            //$ytdgrosspay = $this->getYTD($oData['emp_id'],4,$oData['paystub_id']);
            $oPDF->MultiCell(25, 2, ":   ".number_format($ytdgrosspay['ytdamount'],2),0,'L',1);
            $coordY+=$oPDF->getFontSize()+0;
            
            $oPDF->SetXY($coordX+130, $coordY);
            $oPDF->MultiCell(30, 2, "TAXABLE GROSS",0,'L',1);
            $oPDF->SetXY($coordX+160, $coordY);
            $ytdtaxgross = $this->getYTD($oData['emp_id'],30,$getYear->fields['payperiod_period_year'],$getYear->fields['payperiod_period'],$getYear->fields['payperiod_freq']);
            $oPDF->MultiCell(25, 2, ":   ".number_format($ytdtaxgross['ytdamount'],2),0,'L',1);
            $coordY+=$oPDF->getFontSize()+0;
            
            $oPDF->SetFont('dejavusans','',6.5);
            $oPDF->SetXY($coordX+130, $coordY);
            $oPDF->MultiCell(30, 2, "Statutory Contribution",0,'L',1);
           	$oPDF->SetXY($coordX+160, $coordY);
            $oPDF->SetFont('dejavusans','',7);
            $ytdstat = $this->getYTD($oData['emp_id'],27,$getYear->fields['payperiod_period_year'],$getYear->fields['payperiod_period'],$getYear->fields['payperiod_freq']);
            $oPDF->MultiCell(25, 2, ":   ".number_format($ytdstat['ytdamount'],2),0,'L',1);
            $coordY+=$oPDF->getFontSize()+0;
            
            $oPDF->SetXY($coordX+130, $coordY);
            $oPDF->MultiCell(30, 2, "W/H TAX",0,'L',1);
            $oPDF->SetXY($coordX+160, $coordY);
            $ytdwhtax = $this->getYTD($oData['emp_id'],8,$getYear->fields['payperiod_period_year'],$getYear->fields['payperiod_period'],$getYear->fields['payperiod_freq']);
            $oPDF->MultiCell(25, 2, ":   ".number_format($ytdwhtax['ytdamount'],2),0,'L',1);
            $coordY+=$oPDF->getFontSize()*4;
            
            $oPDF->SetXY($coordX, $coordY);
            $oPDF->MultiCell(19, 2, "TAX STATUS",0,'L',1);
            $oPDF->SetXY($coordX+19, $coordY);
            $oPDF->MultiCell(90, 2, ': '.$oData['paystubdetails']['empinfo']['tax_ex_name'],0,'L',1);
            $coordY+=$oPDF->getFontSize()+7;
            
             $oPDF->Line($coordX, $coordY, $coordX+212, $coordY, $style=$style5);//bottom line
        // get the pdf output
        $output = $oPDF->Output("payslip_".$oData['paystubdetails']['empinfo']['fullname'].date('Y-m-d').".pdf");
        if (!empty($output)) {
            return $output;
        }
    }
}
?>