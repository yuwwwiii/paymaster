<?php
/**
 * Initial Declaration
 */
require_once(SYSCONFIG_CLASS_PATH.'util/pdf.class.php');
require_once(SYSCONFIG_CLASS_PATH.'admin/transaction/process_payroll.class.php');

/**
 * Class Module
 *
 * @author Jason I. Mabignay
 *
 */
class clsPayslip_Form2{

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
	function clsPayslip_Form2($dbconn_ = null){
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
	 *
	 * @param string $id_
	 * @return array
	 */
	function dbFetch_Payslip($id_ = "", $emp_id_="" ){
//		$this->conn->debug=1;
		$qry = array();
		if (!is_null($id_)) { $qry[] = "a.ppr_id ='".$id_."'"; }
		if(is_null($emp_id_)||$emp_id_=""){ $qry[] = "a.emp_id ='".$emp_id_."'"; }
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";
		$sql = "Select a.*,b.payperiod_period, b.pp_stat_id,DATE_FORMAT(b.payperiod_start_date, '%d %b %Y') as start_date, DATE_FORMAT(b.payperiod_end_date, '%d %b %Y') as end_date
					from payroll_paystub_report a
					inner join payroll_pay_period b on (a.payperiod_id=b.payperiod_id)
					$criteria";
		$rsResult = $this->conn->Execute($sql);
		if(!$rsResult->EOF){
			$rsResult->fields['paystubdetails'] = unserialize($rsResult->fields['ppr_paystubdetails']);
			return $rsResult->fields;
		}
	}
	
	/**
	 * Get the records from the database
	 *
	 * @param string $id_
	 * @return array
	 */
	function dbFetch_PayslipBEN($id_ = ""){
		$objClsMnge_PG = new clsMnge_PG($this->conn);
//		$this->conn->debug=1;
		$qry = array();
		
		if (!is_null($id_)) {
			$qry[] = "a.ppr_id ='".$id_."'";
		}
		
		//printa ($qry);
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";
		$sql = "Select a.*, DATE_FORMAT(b.payperiod_start_date, '%d %b %Y') as start_date, DATE_FORMAT(b.payperiod_end_date, '%d %b %Y') as end_date
					from payroll_paystub_report a
					inner join payroll_pay_period b on (a.payperiod_id=b.payperiod_id)
					$criteria";
		$rsResult = $this->conn->Execute($sql);
		if(!$rsResult->EOF){
			$rsResult->fields['paystubdetails'] = unserialize($rsResult->fields['ppr_paystubdetails']);
			
//			for 
			$EmployeeBenefits = $objClsMnge_PG->dbFetchEmployeeBenefits($rsResult->fields['emp_id'],$rsResult->fields['paystubdetails']['paystubdetail']['paystubsched']['payperiod_trans_date'],$rsResult->fields['paystubdetails']['paystubdetail']['paystubsched']['payperiod_start_date'],$rsResult->fields['paystubdetails']['paystubdetail']['paystubsched']['payperiod_end_date']);
			
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
	 *
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

	/**
	 * Save Update
	 *
	 */
	function doReProcessPayroll($psdetails_ = null, $emp_id_ =null) {
		$objClsMnge_PG = new clsMnge_PG($this->conn);
		$objClsMngeDecimal = new Application();
		$id = $_GET['edit'];
		$ctr = 0;
		$details = $this->dbFetch_Payslip($psdetails_,$emp_id_);
		$payStubSerialize = array();
//		printa($_POST);
//		printa($details); exit;
//		exit;
//		
		// This section used to compute the Basic Pay
		//-------------------------------------------------------->>	
		$SalaryDetails = $this->getSalaryDetails($details['emp_id']);
//		printa($SalaryDetails);
//		exit;
		if ($SalaryDetails['salaryinfo_basicrate']==$details['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['Basic Salary']) {
			$BPDayRate = $objClsMnge_PG->getBPDayRate($details['emp_id'],$SalaryDetails['salarytype_id'],$SalaryDetails['salaryinfo_basicrate'],$details['payperiod_id'],$SalaryDetails['salaryinfo_ecola']);
			$PGrate = $details['paystubdetails']['paystubdetail']['paystubaccount']['earning']['Regulartime'];
			$rateperhour = $BPDayRate['rateperhour'];// Rate per Hours
			$rateperday = $BPDayRate['rateperday']; // Rate per Day
			$ratepersec = $BPDayRate['ratepersec']; // Rate per Second
			$varActualDaysRender = $BPDayRate['nodays']; // Number of Days Render
			
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
			$varData['totalOTtime'] = $objClsMnge_PG->getTotalOTByPayPeriod($details['emp_id'],$details['paystubdetails']['paystubdetail']['paystubsched']['payperiod_id']);
			$totalRegtime = $varData['totalOTtime'];
			$varTotalallOT_ = 0;
//			printa($varData['totalOTtime']);
			for ($a=0;$a<count($totalRegtime);$a++) {
				$varOTTotalrate_ = $totalRegtime[$a]['otr_factor'] * $rateperhour;
				$varOTTotal_ = Number_Format(($totalRegtime[$a]['otrec_totalhrs'] * $varOTTotalrate_),2,'.','');
				$vartotalotrate = Number_Format($varOTTotal_,2,'.','');
//				echo $varOTTotalrate_."<br>";
//				echo $varOTTotal_."<br>";
//				echo $vartotalotrate."<br>";
					$this->otDetail[$a] = array(
									 "otrec_id"=>$totalRegtime[$a]['otrec_id']
									,"ot_name"=>$totalRegtime[$a]['otr_name']
									,"rate"=>$totalRegtime[$a]['otr_factor']
									,"totaltimehr"=>($totalRegtime[$a]['otrec_totalhrs'])
									,"rateperhr"=>Number_Format($varOTTotalrate_,2,'.','')
									,"otamount"=>$vartotalotrate
								);
				$varTotalallOT_ += $varOTTotal_;
				$objClsMnge_PG->updateOTrecord($totalRegtime[$a]['otrec_id'],$details['paystub_id'],$vartotalotrate);// update OT Record
            }
//		printa ($this->otDetail[$ctr]);
//		exit;
		//----------------------------END-------------------------<<

		// get total TA Time
		//-------------------------------------------------------->> 
			$varData['totalTAtime'] = $objClsMnge_PG->getTotalTAByPayPeriod($details['emp_id'],$details['paystubdetails']['paystubdetail']['paystubsched']['payperiod_id']);
			$totalTAtime = $varData['totalTAtime'];
			$varTotalallTA_ = 0;
			for ($a=0;$a<count($totalTAtime);$a++) {
				($totalTAtime[$a]['tatbl_rate']=='2')?$varTArate_ = $rateperday:$varTArate_ = $rateperhour;//get the TA type: 2=Day, 1=hrs
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
				$varTotalallTA_ += Number_Format($varTATotal_,2,'.','');
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
			$amendments = $objClsMnge_PG->getAmendments($details['emp_id'],$details['paystubdetails']['paystubdetail']['paystubsched']['payperiod_trans_date'],$details['paystubdetails']['paystubdetail']['paystubsched']['payperiod_start_date'],$details['paystubdetails']['paystubdetail']['paystubsched']['payperiod_end_date']);
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
//			exit;
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
			$objClsMnge_PG->doSaveTempPayStub($paystub_id, 1, $BPDayRate['PayPeriodBP']);
			$objClsMnge_PG->doSaveTempPayStub($paystub_id, -1, $BPDayRate['rateperday']);		//save Rate per Day
			$objClsMnge_PG->doSaveTempPayStub($paystub_id, -2, $BPDayRate['rateperhour']);		//save Rate per Hour
			
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
		//----------------------------END-------------------------<<
		// PAYRECORD PART
		//-------------------------------------------------------->>
		$varTotalallOT = $varTotalallOT_; //@note: OT Rate
		$varTotalallTA = $varTotalallTA_; //@note: Leave Deduction
//		$OTbackpay = $_POST['otbackpay']; //@note: OT Backpay
//		$varSumAllOTRate = $OTbackpay + $varTotalallOT; //@note: SUM of all OT
		//----------------------------END-------------------------<<
		// Amount Computed base in Basic Pay.
		//-------------------------------------------------------->>
		$basicpay_rate = $SalaryDetails['salaryinfo_basicrate'];													  					//BasicPay Rate
		$BPperperiod = $PGrate; 																  										//Basic computed base to salary type and pay group
		$COLAperperiod = $PayPeriodCOLA;								  						      			  						//COLA computed base to salary type and pay group
		$varSumAllOTRate = $varTotalallOT; 									  						          							//sum of all OT
		$varSumAllTARate = $varTotalallTA; 									  						 	      							//sum of all TA
		$basicgrosspg = ($BPperperiod + Number_Format($varSumAllOTRate,2,'.','') + $PayPeriodCOLA) - Number_Format($varSumAllTARate,2,'.',''); 				  							//(Basic Pay + OT + COLA)- TA
		$basicPG_ABS = ($basicgrosspg + $SumSABEarning + $SumTSABEarning + $COLAperperiod) - ($SumTSABDeduction + $SumSABDeduction);	//Add AB Earning and Less AB Deduction subject to Stat 
		$basicPG_nostat = ($basicgrosspg + $SumTSABEarning + $SumTABEarning) - ($SumTABDeduction + $SumTSABDeduction);					//Sum all Earning and Deduction that subject to tax.
		$nontaxableGross =  $SumNonABEarning; 																	      					//Sum all non AB Earning
		$other_nontaxdeduction = $SumNonABDeduction;															 	  					//Sum all non AB Deduction
		$SumAllEarning = $BPperperiod + Number_Format($varSumAllOTRate,2,'.','') + $SumALLABEarning + $PayPeriodCOLA; 
		
//		echo "===================Summary====================<br>";
//		echo $BPperperiod.' Basic Pay<br>';
//		echo $varSumAllOTRate.' Total OT<br>';
//		echo $varSumAllTARate.' Total LUA<br>';
//		echo $basicgrosspg.' (BasicPay+OT)-LUA<br>';
//		echo $basicPG_ABS.' Basic AB Stat<br>';
//		echo $nontaxableGross.' Non-TaxGross<br>';
//		echo $other_nontaxdeduction.' Non-TaxDeduction<br>';
//		exit;
		
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
        
		if($varSumAllTARate != $details['paystubdetails']['paystubdetail']['paystubaccount']['TUA']['TotalLeave'] || $varSumAllOTRate != $details['paystubdetails']['paystubdetail']['paystubaccount']['earning']['OT']['TotalallOT'] || $basicpay_rate != $details['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['Basic Salary']){
			//recompute Statutory Contrib
			$varData[$ctr]['typeDeduc'] = $objClsMnge_PG->getTypeDeduction();//get deduction type
			$dectype = $varData[$ctr]['typeDeduc'];
			for ($b=0;$b<count($dectype);$b++){
				$qry = array();
				$qry[] = "a.dec_id = '".$dectype[$b]['dec_id']."'";
				$criteria = count($qry)>0 ? " where ".implode(' and ',$qry) : '';
				$sql = "Select * from statutory_contribution a $criteria";
				$varDeducH = $this->conn->Execute($sql);
				if(!$varDeducH->EOF){
					$varHdeduc = $varDeducH->fields;
				}
//				$transdateSched = $rsResult->fields['ppdTransDate'];
				$transdateSched = date("d", strtotime($details['paystubdetails']['paystubdetail']['paystubsched']['payperiod_trans_date']));
				$startdateSched = date("d", strtotime($details['paystubdetails']['paystubdetail']['paystubsched']['payperiod_start_date']));
				$schedSecond = $details['paystubdetails']['paystubdetail']['paystubsched']['schedSecond'];
				
				//@todo: check for 1st & 2nd Half
				$varDeductionSched = $objClsMnge_PG->getDeductionSched($details['emp_id'],$dectype[$b]['dec_id']);
				
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
										$varPPD_ = $objClsMnge_PG->getMonthlyRecordStat($details['emp_id'],'7',$details['payperiod_period']);
										$totalSSSgross = $varPPD_['ppe_rate'] + $basicPG_ABS;
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
											$varPPD_ = $objClsMnge_PG->getMonthlyRecordStat($details['emp_id'],'4',$details['payperiod_period']);
											$totalSSSgross = $varPPD_['ppe_amount'] + $basicPG_ABS;
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
								if($schedSecond>=$startdateSched && $schedSecond<=$transdateSched){
									$schedSecond = '1';
									$varPPD_ = $objClsMnge_PG->getMonthlyRecordStat($details['emp_id'],'7',$details['payperiod_period']);
									$totalSSSgross = $varPPD_['ppe_rate'] + $basicPG_ABS;
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
										$varPPD_ = $objClsMnge_PG->getMonthlyRecordStat($details['emp_id'],'14',$details['payperiod_period']);
										$totalPhilgross = $varPPD_['ppe_rate'] + $basicPG_ABS;
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
											$varPPD_ = $objClsMnge_PG->getMonthlyRecordStat($details['emp_id'],'4',$details['payperiod_period']);
											$totalPhilgross = $varPPD_['ppe_amount'] + $basicPG_ABS;
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
								if($schedSecond>=$startdateSched && $schedSecond<=$transdateSched){
									$varPPD_ = $objClsMnge_PG->getMonthlyRecordStat($details['emp_id'],'14',$details['payperiod_period']);
									$totalPhilgross = $varPPD_['ppe_rate'] + $basicPG_ABS;
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
						break;	
						
					case 3:
						IF(count($varDeductionSched)=='1'){
								IF($details['paystubdetails']['empinfo']['salaryclass_id']=='5'){//FOR MONTHLY PG SSS COMPUTATION
									IF($varDeductionSched[0]['bldsched_period']=='0'){
										$varDecPagibig = 0;
			                            $varDecPagibigEmployer = 0;
									}ELSE{
										$varPPD_ = $objClsMnge_PG->getMonthlyRecordStat($details['emp_id'],'15',$details['payperiod_period']);
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
											$varPPD_ = $objClsMnge_PG->getMonthlyRecordStat($details['emp_id'],'4',$details['payperiod_period']);
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
									$varPPD_ = $objClsMnge_PG->getMonthlyRecordStat($details['emp_id'],'15',$details['payperiod_period']);
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
		
		$taxableGross = $basicPG_nostat - $varDeduction;//compute taxable gross	
		
		// Compute W/H Tax
		//-------------------------------------------------------->>
		IF($details['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['W/H Tax'] == $_POST['tax']){
			if($rateperday <= $details['paystubdetails']['paystubdetail']['paystubaccount']['earning']['MWR']){
				$totalTax = 0;
				$conVertTaxper = 0;
			}else{
				//Used to check if MWE
				$qry_ = array();
				$qry_[] = "a.empdd_id = '5'";
				$qry_[] = "a.emp_id = '".$details['paystubdetails']['empinfo']['emp_id']."'";
				$criteria = count($qry_)>0 ? " where ".implode(' and ',$qry_) : '';
				$sql_= "select a.bldsched_period from period_benloanduc_sched a $criteria";
				$varMWE = $this->conn->Execute($sql_);
				if(!$varMWE->EOF){
					$varMWE_ = $varMWE->fields['bldsched_period'];
				}else{
					$varMWE_ = 0;
				}
				if($varMWE_['bldsched_period']=='2'){
					$totalTax = 0;
					$conVertTaxper = 0;
				}else{//this is to get the taxpolicy
					$qry = array();
					//$qry[] = "a.emp_id = '".$pData['chkAttend'][$ctr]."'";
					//$qry[] = "b.dec_id = 5";
					$criteria = count($qry)>0 ? " where ".implode(' and ',$qry) : '';
					$sql = "Select * from tax_policy a $criteria";
					$varDeducH = $this->conn->Execute($sql);
					if(!$varDeducH->EOF){
						$varHdeduc = $varDeducH->fields;
					}
					$varDec = $objClsMnge_PG->getTotalTaxByPayPeriod($details['emp_id'],$varHdeduc['tp_id'],2,$taxableGross,$details['paystubdetails']['empinfo']['taxep_id'],$details['paystubdetails']['empinfo']['tt_pay_group']);
					$varStax = $taxableGross - $varDec['tt_minamount'];
					$conVertTaxper =  $varDec['tt_over_pct'] / 100 ;
					$varStax_p =  $varStax * $conVertTaxper;
					$totalTax = $varDec['tt_taxamount'] + $varStax_p;
					$totalTax_ = $totalTax;
					$psaTax = "Tax";
				}
			}
		}else{
			$totalTax = $_POST['tax']; //if the tax is edited.
		}	
		//----------------------------END-------------------------<<
//		echo "=================TAX Summary===================<br>";
//		echo $totalTax." W/H Tax<br>";
//		echo $taxableGross." Taxable Gross<br>";

		//get gov/reg loans
		//-------------------------------------------------------->>
			$govreg_loan = $objClsMnge_PG->getEmployeeActiveGovRegLoan($details['emp_id'],$details['paystubdetails']['paystubdetail']['paystubsched']['payperiod_trans_date'],$details['paystub_id']);
            if(count($govreg_loan)>0){
			foreach ($govreg_loan as $keyregloan => $valregloan){
                    //check if negative number
                    if($valregloan['loan_payperperiod'] >0){
                        $totalregloan +=  $valregloan['loan_payperperiod'];
                    }
			}
		}
		//----------------------------END-------------------------<<	
		
			$afterTaxGross = Number_Format($taxableGross,2,'.','') - Number_Format($totalTax,2,'.','');				 //deduct the tax to the taxable gross
			$grossandnontaxable = Number_Format($nontaxableGross,2,'.','');											 //sum all non-taxable income
			$otherdeducNtax = Number_Format($other_nontaxdeduction,2,'.','') + Number_Format($totalregloan,2,'.','');//sum all non-taxble deduction
			$SumAllDeduction = Number_Format($varSumAllTARate,2,'.','') + Number_Format($SumALLABDeduction,2,'.','') + Number_Format($totalTax,2,'.','') + Number_Format($varDeduction,2,'.','') + Number_Format($totalregloan,2,'.','');//Sum all Deduction
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
					)
					,"paystubaccount" => array(
						 "earning" => array(
							 "basic" => $SalaryDetails['salaryinfo_basicrate']
							,"Regulartime" => $BPperperiod
							,"COLA" => $COLAperperiod
							,"COLAperDay" => $colaperday
							,"totalDays" => $varActualDaysRender
							,"OT" => array(
								 "OTDetails" => $this->otDetail
								,"TotalallOT" => Number_Format($varSumAllOTRate,2,'.','')
								,"OTbackpay" => Number_Format("0",2,'.','')
								,"SumAllOTRate" => Number_Format($varSumAllOTRate,2,'.','')
								)
						)
						,"deduction" => $deduction
						,"TUA"=> array(
							 "TADetails" => $this->taDetail
							,"TotalLeave" => Number_Format($varSumAllTARate,2,'.','')
						)
						,"leave_record" => $LeaveRecords
						,"government_regular" => $govreg_loan
						,"benefits" => $payStubSerialize
						,"amendments" => array(
								 $amendments
								,$recurring_amendments
								,"total_TSAEarning" => Number_Format($totalTSEarningAmendments,2,'.','')
								,"total_TAEarning" => Number_Format($totalTEarningAmendments,2,'.','')
								,"total_SAEarning" => Number_Format($totalSEarningAmendments,2,'.','')
								,"total_NTSAEarning" => Number_Format($totalNTSEarningAmendments,2,'.','')
								,"total_TSADeduction" => Number_Format($totalTSDeductionAmendments,2,'.','')
								,"total_TADeduction" => Number_Format($totalTDeductionAmendments,2,'.','')
								,"total_SADeduction" => Number_Format($totalSDeductionAmendments,2,'.','')
								,"total_NTSADeduction" => Number_Format($totalNTSDeductionAmendments,2,'.','')
						)
						,"pstotal" => array(
						 	 "gross" => Number_Format($SumAllEarning,2,'.','')
						 	,"Basic Salary" => Number_Format($basicpay_rate,2,'.','')
						 	,"PGsalary" => Number_Format($basicgrosspg,2,'.','')
						 	,"gross_nontaxable_income" => Number_Format($grossandnontaxable,2,'.','')
						 	,"taxable_Gross" => Number_Format($taxableGross,2,'.','')
						 	,"Deduction" => Number_Format($SumAllDeduction,2,'.','')
						 	,"SatutoryDeduction" => Number_Format($varDeduction,2,'.','')
						 	,"W/H Tax" => Number_Format($totalTax,2,'.','')
						 	,"aftertaxgross" => Number_Format($afterTaxGross,2,'.','')
						 	,"Net Pay" => Number_Format($varNetpay,2,'.','')
						 	,"other_taxable_income" => Number_Format($totalTEarningAmendments,2,'.','')
						 	,"other_deduction" => Number_Format($otherdeducNtax,2,'.','')
						 	,"TotalEarning_payslip" => Number_Format($SumAllEarning,2,'.','')
						 	,"SumSABEarning" => Number_Format($SumSABEarning,2,'.','')
						 	,"SumTSABEarning" => Number_Format($SumTSABEarning,2,'.','')
						 	,"SumTABDeduction" => Number_Format($SumTABDeduction,2,'.','')
						 	,"SumTABEarning" => Number_Format($SumTABEarning,2,'.','')
						 	,"BaseSTATGross" => Number_Format($basicPG_ABS,2,'.','')
						 )
					)	
			);
//        printa($arrPayStub);
//        exit;
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
		$sqlTA = "UPDATE ta_emp_rec set paystub_id = '0' where paystub_id='".$rsResult->fields['paystub_id']."'";
		$this->conn->Execute($sqlTA);
		//----------------------EXIT----------------------<<
		//-----------------UPDATE OT TABLE---------------->>
		$sqlOT = "UPDATE ot_record set paystub_id = '0' where paystub_id='".$rsResult->fields['paystub_id']."'";
		$this->conn->Execute($sqlOT);
		//----------------------EXIT----------------------<<
		//------------UPDATE Amendments TABLE------------->>
		$sqlAmend = "UPDATE payroll_ps_amendemp set paystub_id = '0' where paystub_id='".$rsResult->fields['paystub_id']."'";
		$this->conn->Execute($sqlAmend);
		//----------------------EXIT----------------------<<
		//------------UPDATE Custom Fields TABLE------------->>
		$sqlCF = "update cf_detail set paystub_id=0 where paystub_id='".$rsResult->fields['paystub_id']."'";
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

		// put all query array into one criteria string
		$criteria = (count($qry)>0)?" WHERE ".implode(" AND ",$qry):"";

		// Sort field mapping
		$arrSortBy = array(
		 "pps_name" => "pps_name"
		,"salaryclass_id" => "salaryclass_id"
		,"pp_stat_id" => "pp_stat_id"
		,"payperiod_start_date" => "payperiod_start_date"
		,"payperiod_end_date" => "payperiod_end_date"
		,"payperiod_trans_date" => "payperiod_trans_date"
		);

		if (isset($_GET['sortby'])) {
			$strOrderBy = " GROUP BY ".$arrSortBy[$_GET['sortby']]." ".$_GET['sortof']." , ".payperiod_id;
		} else {
			$strOrderBy = "GROUP BY ".payperiod_id;
		}

		// Add Option for Image Links or Inline Form eg: Checkbox, Textbox, etc...
		$viewLink = "<a href=\"?statpos=payroll_details&edit=',psar.pps_id,'&ppsched_view=',ppp.payperiod_id,'&ppstat_id=',ppp.pp_stat_id,'\">',psar.pps_name,'</a>";
//		$editLink = "<a href=\"?statpos=payroll_details&edit=',am.mnu_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/edit.png\" title=\"Edit\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
//		$delLink = "<a href=\"?statpos=payroll_details&delete=',am.mnu_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\"  border=0 width=\"16\" height=\"16\"></a>";

		// SqlAll Query
		$sql = "select ppp.*, CONCAT('$viewLink') as viewdata, 
				DATE_FORMAT(payperiod_start_date,'%d %b %Y %h:%i %p') as payperiod_start_date,
				DATE_FORMAT(payperiod_end_date,'%d %b %Y %h:%i %p') as payperiod_end_date,
				DATE_FORMAT(payperiod_trans_date,'%d %b %Y') as payperiod_trans_date,psar.pps_name, 
				if(salaryclass_id='1','Daily',IF(salaryclass_id='2','Weekly',IF(salaryclass_id='3','Bi-Weekly',IF(salaryclass_id='4','Semi-monthly',IF(salaryclass_id='5','Monthly','Annual'))))) as salaryclass_id,
				IF(pp_stat_id='1','OPEN',IF(pp_stat_id='2','Locked - Pending Approval',IF(pp_stat_id='3','CLOSED','Post Adjustment'))) as pp_stat_id
						from payroll_paystub_report ppr
						inner join payroll_pay_period ppp on (ppr.payperiod_id=ppp.payperiod_id)
						inner join payroll_pay_period_sched psar on (psar.pps_id=ppp.pps_id)
						$criteria
						$strOrderBy";

		// Field and Table Header Mapping
		$arrFields = array(
		 "viewdata" => "Name"
		,"salaryclass_id" => "Type"
		,"pp_stat_id" => "Status"
		,"payperiod_start_date" => "Start"
		,"payperiod_end_date" => "End"
		,"payperiod_trans_date" => "Pay Date"
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
						INNER JOIN emp_personal_info pinfo ON (pinfo.pi_id=empinfo.pi_id)
						INNER JOIN app_userdept dept ON (dept.ud_id=empinfo.ud_id)
						INNER JOIN emp_position post ON (post.post_id=empinfo.post_id)
						INNER JOIN payroll_paystub_report ppr ON (ppr.emp_id=empinfo.emp_id)
						INNER JOIN payroll_pps_user pps ON (pps.emp_id=empinfo.emp_id)
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
						INNER JOIN emp_personal_info pinfo ON (pinfo.pi_id=empinfo.pi_id)
						INNER JOIN app_userdept dept ON (dept.ud_id=empinfo.ud_id)
						INNER JOIN emp_position post ON (post.post_id=empinfo.post_id)
						INNER JOIN payroll_paystub_report ppr ON (ppr.emp_id=empinfo.emp_id)
						INNER JOIN payroll_pps_user pps ON (pps.emp_id=empinfo.emp_id)
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
		$tblDisplayList->tblBlock->templateFile = "table_nosort.tpl.php";
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
	 * @author grey
	 * @note: used for PDF Payslip Report. @edit:JIM(2012.10.11)
	 * @note: FEAP Payslip Format
	 * @param $oData
	 */
	function getPayslip_Form2_PDF($oData = array()){
        $orientation = 'P'; // P for Portrait, L for Landscape
		$unit = 'mm'; 		// (string) User measure unit. Possible values are: pt: point, mm: millimeter (default), cm: centimeter, and in: inch
		$format = 'LETTER'; // LETTER, USLETTER, ORGANIZERM (216x279 mm ; 8.50x11.00 in)
		$unicode = true;
		$encoding="UTF-8";
		
        $pdf = new clsPDF($orientation, $unit, $format, $unicode, $encoding);
		$objClsMngeDecimal = new Application();
		
		// remove default header/footer
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		
        //set margins
		$pdf_margin_top = 12;
		$pdf_margin_left_and_right = 22.5;

		$pdf->SetMargins($pdf_margin_left_and_right, $pdf_margin_top, $pdf_margin_left_and_right);
//        $oPDF->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
//        $oPDF->SetHeaderMargin(PDF_MARGIN_HEADER);

        //set auto page breaks
		$pdf_margin_bottom = 0;
		$pdf->SetAutoPageBreak(TRUE, $pdf_margin_bottom);
		
		//set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		
		//set some language-dependent strings
		$pdf->setLanguageArray($l); 
		
		$pdf->AddPage(); // Set initial pdf page
		$pdf->SetFont("dejavuserif", '', 8); // Set Font Type & Size
		
		//used to line style...
        $style4 = array('dash' => 1,1);
		$style5 = array('dash' => 2,2);
		$style6 = array('dash' => 0);
		
		// set initila coordinates
        $coordX = $pdf_margin_left_and_right;
        $coordY = $pdf_margin_top;
        $coordYtop = 6;
		
        //Setup Borders
		// Top Line
		$width = $coordX + 78;
		$pdf->Line($coordX,$coordY,$coordX + $width,$coordY,$style5);
		// Bottom Line
		$height = 256.5;
		$total_height = $coordY + $height;
		$pdf->Line($coordX,$total_height,$coordX + $width,$total_height,$style5);
		// Vertical Line
		$middle = 123;
		$pdf->Line($middle,$coordY,$middle,$total_height,$style5);
		
		//header
		$pdf->Ln(10.6);
		$pdf->MultiCell($box_width = 83, $box_height = 0, 'EMPLOYEE PAYSLIP', $border = 0, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
//		$pdf->MultiCell(100 - $box_width, $box_height, 'No.   '.$number, $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		
		$pdf->Ln(3.7);
		$pdf->MultiCell(100, $box_height,"PAYROLL PERIOD : ".date("m/d/Y",strtotime($oData['paystubdetails']['paystubdetail']['paystubsched']['payperiod_start_date']))." to ".date("m/d/Y",strtotime($oData['paystubdetails']['paystubdetail']['paystubsched']['payperiod_end_date'])), $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		
		$pdf->Ln(3.7);
		$pdf->MultiCell(100, $box_height,"PAYOUT DATE       : ".date("m/d/Y",strtotime($oData['paystubdetails']['paystubdetail']['paystubsched']['payperiod_trans_date'])), $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		
		$pdf->Ln(3.7);
		$pdf->MultiCell(100, $box_height,$oData['paystubdetails']['empinfo']['comp_name'], $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		
		$pdf->Ln(3.7);
		$pdf->MultiCell($box_width = 18, $box_height, $oData['paystubdetails']['empinfo']['emp_no'], $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		$pdf->MultiCell(100 - $box_width, $box_height,strtoupper($oData['paystubdetails']['empinfo']['fullname']), $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		
		$pdf->Ln(3.7);
		$pdf->MultiCell($box_width = 51, $box_height, "Dept : ".$oData['paystubdetails']['empinfo']['ud_name'], $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		$pdf->MultiCell(100 - $box_width, $box_height, "Exempt Code : ".$oData['paystubdetails']['empinfo']['taxep_code'], $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		
		//BODY
		//Leave Balance
		$leave_record_ = $oData['paystubdetails']['paystubdetail']['paystubaccount']['leave_record'];
		IF(count($leave_record_)>0){
			FOR($v=0;$v<count($leave_record_);$v++){
				IF($leave_record_[$v]['leave_name']=='VL'){
					$vl_credit = number_format($leave_record_[$v]['empleave_creadit'],2);
					$vl_bal = number_format($leave_record_[$v]['empleave_available_day'],2);
					$vl_taken = number_format($leave_record_[$v]['empleave_used_day'],2);
				}
				IF($leave_record_[$v]['leave_name']=='SL'){
					$sl_credit = number_format($leave_record_[$v]['empleave_creadit'],2);
					$sl_bal = number_format($leave_record_[$v]['empleave_available_day'],2);
					$sl_taken = number_format($leave_record_[$v]['empleave_used_day'],2);
				}
            }
		}
		//GET MONTHLY
		$salaryType = $oData['paystubdetails']['empinfo']['salarytype_id'];
		IF($salaryType == '2'){
			$monthlyRATE_ = $this->getMonthly_RATE($oData['paystubdetails']['empinfo']['emp_id'],$oData['paystubdetails']['paystubdetail']['paystubaccount']['earning']['basic']);
		}ELSE{
			$monthlyRATE_ = $oData['paystubdetails']['paystubdetail']['paystubaccount']['earning']['basic'];
		}
		$pdf->Ln(17);
		$pdf->MultiCell($box_width = 27, $box_height, 'Salary Rates', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		$pdf->MultiCell($box_width_2 = 15.5, $box_height, 'Monthly', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		$pdf->MultiCell($box_width_3 = 24, $box_height, number_format($monthlyRATE_,2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		$pdf->MultiCell(100 - ($box_width + $box_width_2 + $box_width_3), $box_height, "  VL   ".$vl_bal, $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		
		$pdf->Ln(3.7);
		$pdf->MultiCell($box_width = 27, $box_height, '', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		$pdf->MultiCell($box_width_2 = 15.5, $box_height, 'Daily', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		$pdf->MultiCell($box_width_3 = 24, $box_height, number_format($oData['paystubdetails']['paystubdetail']['paystubaccount']['earning']['DailyRate'],2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		$pdf->MultiCell(100 - ($box_width + $box_width_2 + $box_width_3), $box_height, "  SL   ".$sl_bal, $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		
		$pdf->Ln(3.7);
		$pdf->MultiCell($box_width = 27, $box_height, '', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		$pdf->MultiCell($box_width_2 = 15.5, $box_height, 'Hourly', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		$pdf->MultiCell($box_width_3 = 24, $box_height, number_format($oData['paystubdetails']['paystubdetail']['paystubaccount']['earning']['HourlyRate'],2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		$pdf->MultiCell(100 - ($box_width + $box_width_2 + $box_width_3), $box_height, "", $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		
		$pdf->Ln(10);
		$pdf->MultiCell($box_width = 23.5, $box_height, '', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		$pdf->MultiCell($box_width_2 = 25.5, $box_height, 'Attendance', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		$pdf->MultiCell($box_width_3 = 24, $box_height, 'Earnings', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		$pdf->MultiCell(100 - ($box_width + $box_width_2 + $box_width_3), $box_height, "Deductions", $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');

		//Basic Income & work days
		$pdf->Ln(7.4);
		$pdf->MultiCell($box_width = 27, $box_height, 'Workdays', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		$pdf->MultiCell($box_width_2 = 15.5, $box_height, number_format($oData['paystubdetails']['paystubdetail']['paystubaccount']['earning']['totalDays'],2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		$pdf->MultiCell($box_width_3 = 24, $box_height, number_format($oData['paystubdetails']['paystubdetail']['paystubaccount']['earning']['Regulartime'],2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		$pdf->MultiCell(100 - ($box_width + $box_width_2 + $box_width_3), $box_height, "", $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		
		//Leave/TA Listing Deduction
		$psa_ta = $oData['paystubdetails']['paystubdetail']['paystubaccount']['TUA']['TADetails'];
	    $total_ta = $oData['paystubdetails']['paystubdetail']['paystubaccount']['TUA']['TotalLeave'];
        IF(count($psa_ta)>0){
            IF($total_ta != 0){
				FOR($k=0;$k<count($psa_ta);$k++){
            		IF($psa_ta[$k]['ta_name']!='Custom Days'){
            			$pdf->Ln(3.7);
						$pdf->MultiCell($box_width = 27, $box_height, substr(trim($psa_ta[$k]['ta_name']),0,12), $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
						$pdf->MultiCell($box_width_2 = 15.5, $box_height, number_format($psa_ta[$k]['totaltimehr'],2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
						$pdf->MultiCell($box_width_3 = 24, $box_height, "", $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
						$pdf->MultiCell(90 - ($box_width + $box_width_2 + $box_width_3), $box_height, number_format($psa_ta[$k]['taamount'],2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
            		}
                }
            }
        }
        
		$pdf->Ln(3.7);
		$pdf->MultiCell($box_width = 100, $box_height, '', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');

		// OT listing
		$psa_OT = $oData['paystubdetails']['paystubdetail']['paystubaccount']['earning']['OT']['OTDetails'];  
        IF(count($psa_OT)>0){
            FOR($k=0;$k<count($psa_OT);$k++){
            	$pdf->Ln(3.7);
				$pdf->MultiCell($box_width = 27, $box_height, substr(trim($psa_OT[$k]['ot_name']),0,12), $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
				$pdf->MultiCell($box_width_2 = 15.5, $box_height, $psa_OT[$k]['totaltimehr'], $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
				$pdf->MultiCell($box_width_3 = 24, $box_height, $psa_OT[$k]['otamount'], $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
				$pdf->MultiCell(100 - ($box_width + $box_width_2 + $box_width_3), $box_height, "", $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
	        }
        }
        
        $pdf->Ln(3.7);
		$pdf->MultiCell($box_width = 100, $box_height, '', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');

		// OTHER Earning Income
		// benifits listing
        $psa_benifits = $oData['paystubdetails']['paystubdetail']['paystubaccount']['benefits'];            
        IF(count($psa_benifits)>0){
			FOR($v=0;$v<count($psa_benifits);$v++){
                IF($psa_benifits[$v]['psa_type']!=2){
                	IF($psa_benifits[$v]['ben_payperday'] != 0){
	                	$pdf->Ln(3.7);
						$pdf->MultiCell($box_width = 42.50, $box_height, substr(trim($psa_benifits[$v]['psa_name']),0,12), $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
						$pdf->MultiCell($box_width_3 = 24, $box_height, number_format($psa_benifits[$v]['ben_payperday'],2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
                	}
                }
			}
        }
        
		// Amendment listing
		$psa_amendment = $oData['paystubdetails']['paystubdetail']['paystubaccount']['amendments'][0];
        for($a=0;$a<count($psa_amendment) ;$a++){
            if ($psa_amendment[$a]['psa_type']==1) {
            	IF($psa_amendment[$a]['amendemp_amount'] != 0){
	            	$pdf->Ln(3.7);
					$pdf->MultiCell($box_width = 42.50, $box_height, substr(trim($psa_amendment[$a]['psa_name']),0,12), $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
					$pdf->MultiCell($box_width_3 = 24, $box_height, number_format($psa_amendment[$a]['amendemp_amount'],2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
            	}
            }
        }
        
        $pdf->Ln(3.7);
		$pdf->MultiCell($box_width = 100, $box_height, '', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
        
        //OTHER DEDUCTION INCOME
        //W/H TAX
        $taxwh = $oData['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['W/H Tax'];
		$pdf->Ln(3.7);
		$pdf->MultiCell($box_width = 27, $box_height, 'WTAX', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		$pdf->MultiCell(90 - $box_width, $box_height, number_format($taxwh,2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');

		//SSS
		$SSS = $oData['paystubdetails']['paystubdetail']['paystubaccount']['deduction']['SSS'];
		$pdf->Ln(3.7);
		$pdf->MultiCell($box_width = 27, $box_height, 'SSS', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		$pdf->MultiCell(90 - $box_width, $box_height, number_format($SSS,2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');

		//HDMF
		$HDMF = $oData['paystubdetails']['paystubdetail']['paystubaccount']['deduction']['Pag-ibig'];
		$pdf->Ln(3.7);
		$pdf->MultiCell($box_width = 27, $box_height, 'HDMF', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		$pdf->MultiCell(90 - $box_width, $box_height, number_format($HDMF,2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');

		//PHIC
		$PHIC = $oData['paystubdetails']['paystubdetail']['paystubaccount']['deduction']['PhilHealth'];
		$pdf->Ln(3.7);
		$pdf->MultiCell($box_width = 27, $box_height, 'PHILHEALTH', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		$pdf->MultiCell(90 - $box_width, $box_height, number_format($PHIC,2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		
		//TOTAL Loan Deduction
		$pdf->Ln(3.7);
		$pdf->MultiCell($box_width = 27, $box_height, 'Loan Deduction', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		$pdf->MultiCell(90 - $box_width, $box_height, number_format($oData['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['Loan_Total'],2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		
		//amendment deduction
        $psa_amendment = $oData['paystubdetails']['paystubdetail']['paystubaccount']['amendments'][0];
        if(count($psa_amendment)>0){
			foreach($psa_amendment as $key => $val){
                IF ($val['psa_type']==2) {
                	IF($val['amendemp_amount'] > 0){
	                    $pdf->Ln(3.7);
						$pdf->MultiCell($box_width = 42.50, $box_height, substr(trim($val['psa_name']),0,12), $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
						$pdf->MultiCell(90 - $box_width, $box_height, number_format($val['amendemp_amount'],2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
                	}
                }
            }
         }
         
		// benifits Deduction
        $psa_benifits = $oData['paystubdetails']['paystubdetail']['paystubaccount']['benefits'];            
        IF(count($psa_benifits)>0){
            FOR($v=0;$v<count($psa_benifits);$v++){
                IF($psa_benifits[$v]['psa_type']!=1){
                	IF($psa_benifits[$v]['ben_payperday'] > 0){
                	$pdf->Ln(3.7);
					$pdf->MultiCell($box_width = 42.50, $box_height, substr(trim($psa_benifits[$v]['psa_name']),0,12), $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
					$pdf->MultiCell(90 - $box_width, $box_height, number_format($psa_benifits[$v]['ben_payperday'],2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
                	}
            	}	
            }
        }

		$pdf->Ln(3.7);
		$pdf->MultiCell($box_width = 100, $box_height, '', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		
		//GRAND TOTALS 
		$pdf->Ln(3.7);
		$pdf->MultiCell($box_width = 27, $box_height, 'TOTALS', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		$pdf->MultiCell($box_width_2 = 15.5, $box_height, '', $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		$pdf->MultiCell($box_width_3 = 24, $box_height, number_format($oData['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['TotalEarning_payslip'],2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		$pdf->MultiCell(90 - ($box_width + $box_width_2 + $box_width_3), $box_height, number_format($oData['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['Deduction'],2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');

		$pdf->Ln(3.7);
		$pdf->MultiCell($box_width = 100, $box_height, '', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		
		//NETPAY
		$pdf->Ln(3.7);
		$pdf->MultiCell($box_width = 27, $box_height, 'NETPAY', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		$pdf->MultiCell(90 - $box_width, $box_height, number_format($oData['paystubdetails']['paystubdetail']['paystubaccount']['pstotal']['Net Pay'],2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');

		$pdf->Ln(3.7);
		$pdf->MultiCell($box_width = 100, $box_height, '', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');

		$pdf->Ln(3.7);
		$pdf->MultiCell($box_width = 100, $box_height, '',$pdf->Line($coordX,$coordY,$coordX + $width,$coordY,$style5), 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		
		//LOAN DETAILS
		$pdf->Ln(4.7);
		$pdf->MultiCell($box_width = 100, $box_height, 'LOAN DEDUCTIONS', $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		
		$pdf->Ln(3.7);
		$pdf->MultiCell($box_width = 27.5, $box_height, 'LOAN AMT', $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		$pdf->MultiCell($box_width_2 = 19, $box_height, 'TOT PAID', $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		$pdf->MultiCell($box_width_3 = 20, $box_height, "CURR DED", $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		$pdf->MultiCell(90 - ($box_width + $box_width_2 + $box_width_3), $box_height, "BALANCE", $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		
		//Loan listing
		$loan_info_ = $oData['paystubdetails']['paystubdetail']['paystubaccount']['government_regular'];
		FOR($v=0;$v<count($loan_info_);$v++){
			$pdf->Ln(3.7);
			$pdf->MultiCell($box_width = 10, $box_height, substr(trim($loan_info_[$v]['psa_name']),0,3), $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
			$pdf->MultiCell($box_width_2 = 17.5, $box_height, number_format($loan_info_[$v]['loan_principal'],2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
			$pdf->MultiCell($box_width_3 = 19, $box_height, number_format($loan_info_[$v]['loan_ytd'],2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
			$pdf->MultiCell($box_width_4 = 20, $box_height, number_format($loan_info_[$v]['loan_payperperiod'],2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
			$pdf->MultiCell(90 - ($box_width + $box_width_2 + $box_width_3 + $box_width_4), $box_height, number_format($loan_info_[$v]['loan_balance'],2), $border, 'R', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		}

		$pdf->Ln(5);
		$pdf->MultiCell(90, $box_height, "", $style5, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		
		$pdf->Ln(3.7);
		$pdf->MultiCell(100, $box_height, "Any discrepancies noted should be cleared with", $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		
		$pdf->Ln(3.7);
		$pdf->MultiCell(100, $box_height, "Payroll Staff within 3 days.", $border, 'L', 0, 0, '', '', true, 0, false, true, $box_height, 'M');
		
        // get the pdf output
        $output = $pdf->Output("payslip_".$oData['paystubdetails']['empinfo']['fullname'].date('Y-m-d').".pdf");
        if (!empty($output)) {
            return $output;
        }
    }
    
    /**
     * 
     */
    function getMonthly_RATE($emp_id_ = null,$empDailyRate = 0){
    	if (is_null($emp_id_)) { return $arrData; }
    	$qry[]="a.emp_id = '".$emp_id_."'";
		// put all query array into one string criteria
		$criteria = " WHERE ".implode(" and ",$qry);
		$qry = "SELECT * 
					FROM payroll_comp a
					JOIN factor_rate b on(b.fr_id=a.fr_id) 
					$criteria";
		$varFac = $this->conn->Execute($qry);
		if(!$varFac->EOF){
			$varFac_ = $varFac->fields;
			$MonthlyRate = ($empDailyRate * $varFac_['fr_dayperyear'])/12;
			return $MonthlyRate;
		}
    }
    
    /**
     * @note: Get YTD values
     * @param $emp_id_
     * @param $psa_id_
     * @param $paystub_id_
     */
    function getYTD($emp_id_ = null, $psa_id_ = null, $paystub_id_ = null){
    	if (is_null($emp_id_)) { return $arrData; }
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
		if(!$varYTD->EOF){
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
			
			$sql_remove = "DELETE FROM `payroll_paystub_report` WHERE `payroll_paystub_report`.`payperiod_id` = {$_GET['ppsched_view']} AND `payroll_paystub_report`.`emp_id` = {$pData['chkAttend'][$ctr]}";
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
    
}

?>