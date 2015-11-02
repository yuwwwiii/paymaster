<?php
/**
 * Initial Declaration
 */
require_once(SYSCONFIG_CLASS_PATH."util/pdf.class.php");
require_once(SYSCONFIG_CLASS_PATH.'admin/reports/sss.class.php');

/**
 * Class Module
 * @author  jim
 */
class clsBankExportStandChart{
	var $conn;
	var $fieldMap;
	var $Data;
	var $txtHash;

	/**
	 * Class Constructor
	 * @param object $dbconn_
	 * @return clsBankExportReport object
	 */
	function clsBankExportStandChart($dbconn_ = null){
		$this->conn =& $dbconn_;
		$this->fieldMap = array(
		 "payperiod_id" => "payperiod_id"
		,"pbr_batchno" => "batch_no"
		,"pbr_bank_name" => "banklist_name"
		,"pbr_routing_number" => "bank_routing_number"
		,"pbr_company_code" => "bank_company_code"
		,"pbr_company_accountno" => "bank_acct_no"
		,"pbr_ceiling_amount" => "bank_ceiling_amount"
		,"pbr_prepared_by" => "pbr_prepared_by"
		,"pbr_approved_by" => "pbr_approved_by"
		,"pbr_credit_date" => "pbr_credit_date"
		,"bank_id" => "bank_id"
		);
		$this->txtHash = "";
	}

	
	function getHashFile($id_ = "",$isPDF_ = true){
        if ($isPDF_) {
            $fldname = "pdf";
        }else{
            $fldname = "txt";
        }
		$sql = "select pbrd_hash_$fldname from payroll_bank_report_data where pbr_id = ?";
		$rsResult = $this->conn->Execute($sql,array($id_));
		if(!$rsResult->EOF){
			return $rsResult->fields["pbrd_hash_$fldname"];
		}
	}
	
	/**
	 * To get BANK INFO.
	 * @param $id_
	 */
	function getHashFileInfo($id_ = ""){
		$sql = "select * from payroll_bank_report_data where pbr_id = ?";
		$rsResult = $this->conn->Execute($sql,array($id_));
		if(!$rsResult->EOF){
			$sql_ = "select * from payroll_bank_reports where pbr_id = ?";
			$rsResult_ = $this->conn->Execute($sql_,array($id_));
			return $rsResult_->fields;
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

    function updateAccountNo($pData = array()){
        if(count($pData)>0){
            foreach($pData as $key => $val){
                $sql  ="update payroll_employee_account set pea_account_no = '".$val."' where pea_id =".$key." ";
                $this->conn->Execute($sql);
            }
            $_SESSION['eMsg']="Successfully Updated.";
        }
    }
    /**
     * Get Payperiod Details for Txtfile Standard Chartered
     */
    function getPPDetails($payperiod_id_ = null){
    	if(is_null($payperiod_id_)){
    		return false;
    	}
    	$sql = "SELECT DATE_FORMAT(payperiod_start_date,'%m%d%Y') as pp_from, DATE_FORMAT(payperiod_end_date,'%m%d%Y') as pp_to from payroll_pay_period WHERE payperiod_id='".$payperiod_id_."'";
        $rsResult = $this->conn->Execute($sql);
		IF(!$rsResult->EOF){
			return $rsResult->fields;
		}
    }
    /**
     * BANK ADVISE Standard Chartered PDF & TXT FILE
     * @param $gData
     */
	function getPDFResult_StandChart($gData = array(),$gPostData= array()){
        $orientation='P';
        $unit='mm';
        $format='LETTER';
        $unicode=true;
        $encoding="UTF-8";
        $oPDF = new clsPDF($orientation, $unit, $format, $unicode, $encoding);
        $objClsSSS = new clsSSS($this->conn);
        
        $branch_details = $objClsSSS->dbfetchCompDetails(1);
        // set header and footer fonts
        $oPDF->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $oPDF->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        //set margins
        $oPDF->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $oPDF->SetHeaderMargin(PDF_MARGIN_HEADER);
        // set auto page break to false so that we can control the page break
        // depending on the desired number of lines on the ouput
        $oPDF->SetAutoPageBreak(false);
        // use a freesans font as a default font
        $oPDF->SetFont('dotim5','',10);
        // suppress print header and footer
        $oPDF->setPrintHeader(true);
        $oPDF->setPrintFooter(false);
        // set initila coordinates
        $coordX = 5;
        $coordY = 30;
        $oPDF->AliasNbPages();
        // set initial pdf page
        $oPDF->AddPage();
        $oPDF->SetFillColor(255,255,255);
        $oPDF->SetFont('dotim5', '', '12');
        // printa($branch_details);
        $oPDF->SetXY($coordX,$coordY-20);
        $oPDF->MultiCell(200, 2, $branch_details['comp_name'],0,'C',1);
        $oPDF->Text($coordX+60,$coordY-10,'PAYROL TRANSACTION PROOFLIST');
        $oPDF->Text($coordX+67,$coordY-05,'PAYROLL DATE: '.$gData['pbr_credit_date']);
        
        $oPDF->SetFont('dotim5', '', '10');
        $arrData = $this->generateReport($gData);
//		printa($arrData);
//        printa($_POST);
//       printa($gData);exit;
        $ctr = 0;
        $net =0;
        $netxt =0;
        $gtotal_hash = 0;
        $gtotal_hash_acct=0;
        
        foreach($arrData as $key => $val){
            $net += $val['net'];
            $netxt += number_format($val['net']);
            $gtotal_hash += $val['hash'];
            $gtotal_hash_acct += str_replace('-', "", $val['bankiemp_acct_no']);
            $ctr++;
        }
        if($coordY==30){
            $oPDF->SetXY($coordX+5, $coordY);
            $oPDF->MultiCell(40, 2, "COMPANY CODE :",0,'R',1);
            $oPDF->SetXY($coordX+45, $coordY);
            $oPDF->MultiCell(70, 2, $gData['pbr_company_code'],0,'L',1);
            $oPDF->SetXY($coordX+110, $coordY);
            $oPDF->MultiCell(50, 2, "CEILING AMOUNT :",0,'R',1);
            $oPDF->SetXY($coordX+160, $coordY);
            $oPDF->MultiCell(40, 2, $gData['pbr_ceiling_amount'],0,'L',1);
            $coordY+=$oPDF->getFontSize()+1;

            $oPDF->SetXY($coordX+5, $coordY);
            $oPDF->MultiCell(40, 2, "ACCOUNT NO.:",0,'R',1);
            $oPDF->SetXY($coordX+45, $coordY);
            $oPDF->MultiCell(70, 2, $gData['pbr_company_accountno'],0,'L',1);
            $oPDF->SetXY($coordX+110, $coordY);
            $oPDF->MultiCell(50, 2, "RECORD COUNT:",0,'R',1);
            $oPDF->SetXY($coordX+160, $coordY);
            $oPDF->MultiCell(40, 2, $ctr,0,'L',1);
            $coordY+=$oPDF->getFontSize()+1;

            $oPDF->SetXY($coordX+5, $coordY);
            $oPDF->MultiCell(40, 2, "BATCH NO.:",0,'R',1);
            $oPDF->SetXY($coordX+45, $coordY);
            $oPDF->MultiCell(70, 2, $gData['pbr_batchno'],0,'L',1);
            $oPDF->SetXY($coordX+110, $coordY);
            $oPDF->MultiCell(50, 2, "TOTAL PAYROLL AMOUNT :",0,'R',1);
            $oPDF->SetXY($coordX+160, $coordY);
            $oPDF->MultiCell(40, 2, number_format($net,2),0,'L',1);
            $coordY+=$oPDF->getFontSize()+3;
            
            $oPDF->SetXY($coordX+5, $coordY);
            $oPDF->MultiCell(40, 2, "ACCOUNT NO.",1,'C',1);
            $oPDF->SetXY($coordX+45, $coordY);
            $oPDF->MultiCell(70, 2, 'EMPLOYEE NAME',1,'C',1);
            $oPDF->SetXY($coordX+110, $coordY);
            $oPDF->MultiCell(50, 2, "TRANSACTION AMOUNT",1,'C',1);
            $oPDF->SetXY($coordX+160, $coordY);
            $oPDF->MultiCell(40, 2, 'HORIZONTAL HASH',1,'C',1);
            $coordY+=$oPDF->getFontSize()+4;
        }
        $row_ctr = 0;
        $net_total_perpage=0;
        $hash_net_total_perpage=0;
        $hash_acct_total_perpage=0;
        foreach($arrData as $key => $val ){
            $net_total_perpage +=$val['net'];
            $hash_net_total_perpage +=$val['hash'];
            $hash_acct_total_perpage +=str_replace('-', "", $val['bankiemp_acct_no']);
            $row_ctr++;
            $oPDF->SetXY($coordX+5, $coordY);
            $oPDF->MultiCell(40, 2, trim($val['bankiemp_acct_no']),0,'C',1);
            $oPDF->SetXY($coordX+45, $coordY);
            $oPDF->MultiCell(70, 2,strtoupper($val['name']),0,'L',1);
            $oPDF->SetXY($coordX+110, $coordY);
            $oPDF->MultiCell(50, 2, number_format($val['net'],2),0,'R',1);
            $oPDF->SetXY($coordX+160, $coordY);
            $oPDF->MultiCell(40, 2,number_format($val['hash'],2),0,'R',1);
            $coordY+=$oPDF->getFontSize()+1;
            if($row_ctr  > 30){
                $oPDF->Line($coordX+5, $coordY+5.5, 200, $coordY+5.5);
                $coordY+=$oPDF->getFontSize()+1;
                // plot net total,hash total, accnt number hash total per page
                $oPDF->SetFont('dotim5', '', '10');
                $oPDF->SetXY($coordX+5, $coordY);
                $oPDF->MultiCell(80, 2, "HASH TOTALS (page)",0,'L',1);
                $coordY+=$oPDF->getFontSize()+1;
                $oPDF->SetXY($coordX+5, $coordY);
                $oPDF->MultiCell(40, 2, $hash_acct_total_perpage,0,'C',1);
                $oPDF->SetXY($coordX+45, $coordY);
                $oPDF->MultiCell(70, 2,"",0,'L',1);
                $oPDF->SetXY($coordX+110, $coordY);
                $oPDF->MultiCell(50, 2, number_format($net_total_perpage,2),0,'R',1);
                $oPDF->SetXY($coordX+160, $coordY);
                $oPDF->MultiCell(40, 2,number_format($hash_net_total_perpage,2),0,'R',1);
                $coordY+=$oPDF->getFontSize()+1;

                // reset variable settings
                $net_total_perpage = 0;
                $hash_net_total_perpage = 0;
                $hash_acct_total_perpage = 0;
                $row_ctr = 0;
                $coordY = 30;
                $oPDF->AliasNbPages();
                // set initial pdf page
                $oPDF->AddPage();
                $oPDF->SetFillColor(255,255,255);
                $oPDF->SetFont('dotim5', '', '12');
                $oPDF->Text($coordX+60,$coordY-10,'PAYROL TRANSACTION PROOFLIST');
                $oPDF->Text($coordX+67,$coordY-05,'PAYROLL DATE: '.$gData['pbr_credit_date']);
               if($coordY==30){
                    $oPDF->SetFont('dotim5', '', '10');
                    $oPDF->SetXY($coordX+5, $coordY);
                    $oPDF->MultiCell(40, 2, "COMPANY CODE :",0,'R',1);
                    $oPDF->SetXY($coordX+45, $coordY);
                    $oPDF->MultiCell(70, 2, $gData['pbr_company_code'],0,'L',1);
                    $oPDF->SetXY($coordX+110, $coordY);
                    $oPDF->MultiCell(50, 2, "CEILING AMOUNT :",0,'R',1);
                    $oPDF->SetXY($coordX+160, $coordY);
                    $oPDF->MultiCell(40, 2, $gData['pbr_ceiling_amount'],0,'L',1);
                    $coordY+=$oPDF->getFontSize()+1;

                    $oPDF->SetXY($coordX+5, $coordY);
                    $oPDF->MultiCell(40, 2, "ACCOUNT NO.:",0,'R',1);
                    $oPDF->SetXY($coordX+45, $coordY);
                    $oPDF->MultiCell(70, 2, $gData['pbr_company_accountno'],0,'L',1);
                    $oPDF->SetXY($coordX+110, $coordY);
                    $oPDF->MultiCell(50, 2, "RECORD COUNT:",0,'R',1);
                    $oPDF->SetXY($coordX+160, $coordY);
                    $oPDF->MultiCell(40, 2, $ctr,0,'L',1);
                    $coordY+=$oPDF->getFontSize()+1;

                    $oPDF->SetXY($coordX+5, $coordY);
                    $oPDF->MultiCell(40, 2, "BATCH NO.:",0,'R',1);
                    $oPDF->SetXY($coordX+45, $coordY);
                    $oPDF->MultiCell(70, 2, $gData['pbr_credit_date'],0,'L',1);
                    $oPDF->SetXY($coordX+110, $coordY);
                    $oPDF->MultiCell(50, 2, "Total Payroll Amt:",0,'R',1);
                    $oPDF->SetXY($coordX+160, $coordY);
                    $oPDF->MultiCell(40, 2, number_format($net,2),0,'L',1);
                    $coordY+=$oPDF->getFontSize()+3;

                    $oPDF->SetXY($coordX+5, $coordY);
                    $oPDF->MultiCell(40, 2, "ACCOUNT NO.",1,'C',1);
                    $oPDF->SetXY($coordX+45, $coordY);
                    $oPDF->MultiCell(70, 2, 'EMPLOYEE NAME',1,'C',1);
                    $oPDF->SetXY($coordX+110, $coordY);
                    $oPDF->MultiCell(50, 2, "TRANSACTION AMOUNT",1,'C',1);
                    $oPDF->SetXY($coordX+160, $coordY);
                    $oPDF->MultiCell(40, 2, 'HORIZONTAL HASH',1,'C',1);
                    $coordY+=$oPDF->getFontSize()+4;
                }else{
                    // increment coordinate Y
                    $coordY+=$oPDF->getFontSize()+1;
                }
            }
        }
        $oPDF->Line($coordX+5, $coordY+5.5, 200, $coordY+5.5);
        $coordY+=$oPDF->getFontSize()+1;
        // plot net total,hash total, accnt number hash total per page
        $oPDF->SetFont('dotim5', '', '10');
        $oPDF->SetXY($coordX+5, $coordY);
        $oPDF->MultiCell(80, 2, "HASH TOTALS (page)",0,'L',1);
        $coordY+=$oPDF->getFontSize()+1;
        $oPDF->SetXY($coordX+5, $coordY);
        $oPDF->MultiCell(40, 2, $hash_acct_total_perpage,0,'C',1);
        $oPDF->SetXY($coordX+45, $coordY);
        $oPDF->MultiCell(70, 2,"",0,'L',1);
        $oPDF->SetXY($coordX+110, $coordY);
        $oPDF->MultiCell(50, 2, number_format($net_total_perpage,2),0,'R',1);
        $oPDF->SetXY($coordX+160, $coordY);
        $oPDF->MultiCell(40, 2,number_format($hash_net_total_perpage,2),0,'R',1);
        $coordY+=$oPDF->getFontSize()+1;

        // grand totals
        $oPDF->SetXY($coordX+5, $coordY);
        $oPDF->MultiCell(70, 2, "COMPUTED HASH TOTALS",0,'L',1);
        $coordY+=$oPDF->getFontSize()+1;
        
        $oPDF->SetXY($coordX+5, $coordY);
        $oPDF->MultiCell(40, 2, $gtotal_hash_acct,0,'C',1);
        $oPDF->SetXY($coordX+45, $coordY);
        $oPDF->MultiCell(70, 2,"",0,'L',1);
        $oPDF->SetXY($coordX+110, $coordY);
        $oPDF->MultiCell(50, 2, number_format($net,2),0,'R',1);
        $oPDF->SetXY($coordX+160, $coordY);
        $oPDF->MultiCell(40, 2,number_format($gtotal_hash,2),0,'R',1);
        $coordY+=$oPDF->getFontSize()+10;

        $oPDF->SetXY($coordX+5, $coordY);
        $oPDF->MultiCell(100, 2, "PREPARED BY:",0,'L',1);
        $oPDF->SetXY($coordX+100, $coordY);
        $oPDF->MultiCell(100, 2,"APPROVED BY:",0,'L',1);
        $coordY+=$oPDF->getFontSize()+7;
        
        $oPDF->SetXY($coordX+5, $coordY);
        $oPDF->MultiCell(100, 2, "__________________________________________",0,'L',1);
        $oPDF->SetXY($coordX+100, $coordY);
        $oPDF->MultiCell(100, 2,"__________________________________________",0,'L',1);
        $coordY+=$oPDF->getFontSize()+1;
        
        $oPDF->SetXY($coordX+5, $coordY);
        $oPDF->MultiCell(100, 2, "",0,'L',1);
        $oPDF->SetXY($coordX+5, $coordY);
        $oPDF->MultiCell(100, 2,$gData['pbr_prepared_by'],0,'C',1);
        $oPDF->SetXY($coordX+5, $coordY);
        $oPDF->MultiCell(100, 2, "",0,'L',1);
        $oPDF->SetXY($coordX+100, $coordY);
        $oPDF->MultiCell(100, 2,$gData['pbr_approved_by'],0,'C',1);
        $coordY+=$oPDF->getFontSize()+3;
        
        // get payperiod dates(from and to)
        $ppDetails = $this->getPPDetails($gData['payperiod_id']);
        
        //build hash header for the text hash report .str_pad($gData['pbr_company_code'],5,'0',STR_PAD_LEFT)
        $hashHeader_ ='H';
        $date = dDate::getFormattedDateStampMDYLong(dDate::parseDateTime($gData['pbr_credit_date']));
        $hashHeader_ .= str_pad($date,8,'0',STR_PAD_LEFT);
        //company account no
        $hashHeader_ .= str_pad(str_replace('-', "", trim($gData['pbr_company_accountno'])),13,'0',STR_PAD_LEFT);
        //company name
        $hashHeader_ .= str_pad(str_replace('-', "", trim($gData['pbr_company_code'])),30,'0',STR_PAD_LEFT);
        //payroll period from
        $hashHeader_ .= str_pad($ppDetails['pp_from'],8,' ',STR_PAD_LEFT);
        //payroll period to
        $hashHeader_ .= str_pad($ppDetails['pp_to'],8,' ',STR_PAD_LEFT);
        //net
        $hashHeader_ .= str_pad(str_replace('.', "", $net),13,'0',STR_PAD_LEFT);
        //total number of employees
        $hashHeader_ .= str_pad($ctr,3,'0',STR_PAD_LEFT);
        
        //build hash footer for the text hash report
        // H & account # total
        $hashFooter_ ='T'.str_pad(number_format($net,0,'',''),13,'0',STR_PAD_LEFT);
        // total netpay
        //$hashFooter_ .= str_pad(number_format($net,0,'.',''),13,'0',STR_PAD_LEFT);
        // total hash
        $hashFooter_ .= str_pad($ctr,3,'0',STR_PAD_LEFT);
        
        // get the pdf output
        $output = $oPDF->Output("payroll_transaction_prooflist".date('Y-m-d').".pdf","S");

        if(!empty($output)){
            $this->doTextHash($arrData, $hashHeader_, $hashFooter_);
            return $output;
        }
        return false;
    }
    
    function doTextHash_(){
    	return $this->txtHash;
    }
    
	function generateReport($gData = array()){
        $qry = array();
        $qry[] = "h.payperiod_id = ".$gData['payperiod_id']."";
        $qry[] = "c.emp_stat in ('1','7','10')";
        $qry[] = "g.banklist_id = ".$gData['banklist_id']."";
        $criteria = (count($qry)>0)?" WHERE ".implode(" and ",$qry):"";
        $sql = "SELECT concat(e.pi_lname,', ',e.pi_fname) as name,f.bankiemp_acct_name as bname,f.bankiemp_acct_no,c.emp_id
                FROM payroll_pay_period h
                JOIN payroll_pay_period_sched a on (a.pps_id=h.pps_id)
                JOIN payroll_pps_user b on (b.pps_id=a.pps_id)
                JOIN emp_masterfile c on (c.emp_id=b.emp_id)
                JOIN emp_personal_info e on (e.pi_id=c.pi_id)
                JOIN bank_infoemp f on (f.emp_id = c.emp_id)
                JOIN bank_empgroup bnkeg on (bnkeg.bankiemp_id=f.bankiemp_id)
                JOIN bank_info g on (g.bank_id = bnkeg.bank_id)
                LEFT JOIN app_userdept dept on dept.ud_id=c.ud_id
                $criteria
                order by e.pi_lname";
        $rsResult = $this->conn->Execute($sql);
        $varNum = 1;
		while(!$rsResult->EOF){
			$arrData[$rsResult->fields['emp_id']] = $rsResult->fields;
	        $arrData[$rsResult->fields['emp_id']]['net'] = $this->getNetpayPerEmp($rsResult->fields['emp_id'],$gData['payperiod_id']);
	        $arrData[$rsResult->fields['emp_id']]['hash'] = $this->computeHorizontalHash($rsResult->fields['bankiemp_acct_no'],$arrData[$rsResult->fields['emp_id']]['net']);
	           
	        //empolyee account no
	        $text_hash = str_pad($varNum,3,'0',STR_PAD_LEFT).str_pad(str_replace('-', "", trim($rsResult->fields['bankiemp_acct_no'])),13,'0',STR_PAD_LEFT);
	        //employee name
	        $text_hash .= str_pad(trim($rsResult->fields['bname']),30,' ',STR_PAD_RIGHT);
	        //Transaction Code fixed, User Code fixed and DRCR Amount(net pay)
	        $text_hash .= str_pad(str_replace('.', "",number_format($arrData[$rsResult->fields['emp_id']]['net'],2,'.','')),13,'0',STR_PAD_LEFT);
	        //FILLER
	        $arrData[$rsResult->fields['emp_id']]['txt_hash'] = $text_hash;
	        $rsResult->MoveNext();
	        $varNum++;
			
		}
		foreach($arrData as $key => $val){
			IF($arrData[$key]['hash']==0){ continue; } ELSE { $return[$key] = $arrData[$key]; }
		}
        return $return;
    }

    function getNetpayPerEmp($emp_id  ="", $payperiod_id =""){
        IF($emp_id == ""){ return 0.00; }
        IF($payperiod_id == ""){ return 0.00; }
        $sql ="SELECT b.ppe_amount
                FROM payroll_pay_stub a
                JOIN payroll_paystub_entry b on (a.paystub_id = b.paystub_id)
                JOIN payroll_paystub_report c on (a.payperiod_id = c.payperiod_id) and (b.paystub_id = c.paystub_id)
                WHERE c.ppr_isdeleted = 0 and c.ppr_status = 1 and b.psa_id =5 and a.payperiod_id = $payperiod_id and a.emp_id = $emp_id";
        $rsResult = $this->conn->Execute($sql);
		IF(!$rsResult->EOF){
			return $rsResult->fields['ppe_amount'];
		}
    }

    function doTextHash($aData_ = null,$hashHeader_ = "", $hashFooter_ = "", $delimeter_ = "\r\n" ) {
        if(is_null($aData_)){
            return "";
        }
        $this->txtHash = $hashHeader_.$delimeter_;
        if(count($aData_)>0)
        foreach ($aData_ as $keyData => $valData) {
            $this->txtHash .= $valData['txt_hash'].$delimeter_;
        }
        $this->txtHash .= $hashFooter_.$delimeter_;
    }

    function computeHorizontalHash($account_no ="", $net=""){
        if($account_no == ""){ return 0.00; }
        if($net == ""){ return 0.00; }

        $data = explode('-',$account_no);
        $x = substr($data[0], 4,2);
        $y = substr($data[0], 6,2);
        $z = substr($data[0], 8,2);
//        echo $x." x<br>";
//        echo $y." y<br>";
//        echo $z." z<br>";
//        echo $net."<br>";
        $x_net = $x*$net;
        $y_net = $y*$net;
        $z_net = $z*$net;

        $hash = $x_net+$y_net+$z_net;
        return $hash;
    }
    
    /**
     * 
     * Function that will generate bank letters for Std Chartered
     * @param array $gData, $bankInfo, $pData
     * @author IR Salvador
     */
    function generateStdChartLetter($gData = array(),$bankInfo= array(), $pData = array()){
    	//printa($gData); exit;
    	$orientation='P';
        $unit='mm';
        $format='LETTER';
        $unicode=false;
        $encoding="ISO-8859-1";
        $oPDF = new clsPDF($orientation, $unit, $format, $unicode, $encoding);
        $objClsSSS = new clsSSS($this->conn);
        
        //$branch_details = $objClsSSS->dbfetchCompDetails(1);
        // set header and footer fonts
        $oPDF->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $oPDF->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        //set margins
        $oPDF->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $oPDF->SetHeaderMargin(PDF_MARGIN_HEADER);
        // set auto page break to false so that we can control the page break
        // depending on the desired number of lines on the ouput
        $oPDF->SetAutoPageBreak(false);
        // use a freesans font as a default font
        $oPDF->SetFont('helvetica','',10);
        // suppress print header and footer
        $oPDF->setPrintHeader(true);
        $oPDF->setPrintFooter(false);
        // set initila coordinates
        $coordX = 10;
        $coordY = 30;
        $oPDF->AliasNbPages();
        // set initial pdf page
        $oPDF->AddPage();
        $oPDF->SetFillColor(255,255,255);
        // printa($branch_details);
        
        $arrData = $this->generateReport($gData);
//      echo count($arrData);
//		printa($arrData);
//      printa($_POST);
//      printa($gData);exit;
        $ctr = 0;
        $net =0;
        $netxt =0;
        $gtotal_hash = 0;
        $gtotal_hash_acct=0;
        
        foreach($arrData as $key => $val){
            $net += $val['net'];
            $netxt += number_format($val['net']);
            $gtotal_hash += $val['hash'];
            $gtotal_hash_acct += str_replace('-', "", $val['bankiemp_acct_no']);
            $ctr++;
        }        	
            $oPDF->SetFont('helvetica', 'b', '12');
            $oPDF->SetXY($coordX, $coordY+30);
            $oPDF->MultiCell(70, 2, $bankInfo['comp_name'],0,'L',1);
            
            $oPDF->SetFont('helvetica', '', '10');
            $oPDF->SetXY($coordX, $coordY+35);
            $date = new DateTime($gData['pbr_credit_date']);
			$credDate = $date->format('F d, Y');
            $oPDF->MultiCell(50, 2, $credDate,0,'L',1);
            
            $oPDF->SetFont('helvetica', 'b', '10');
            $oPDF->SetXY($coordX, $coordY+45);
            $oPDF->MultiCell(100, 2, strtoupper($gData['pbr_bank_name']),0,'L',1);
            
            $oPDF->SetFont('helvetica', '', '10');
            $oPDF->SetXY($coordX, $coordY+50);
            $oPDF->MultiCell(100, 2, $bankInfo['bank_branch'],0,'L',1);
            
            $oPDF->SetXY($coordX+50, $coordY+60);
            $oPDF->MultiCell(100, 2, "Attention: ",0,'L',1);
            $oPDF->SetFont('helvetica', 'b', '10');
            $oPDF->SetXY($coordX+75, $coordY+60);
            $oPDF->MultiCell(100, 2, $gData['attention'],0,'L',1);
            $oPDF->SetFont('helvetica', '', '10');
            $oPDF->SetXY($coordX+75, $coordY+65);
            $oPDF->MultiCell(100, 2, $gData['att_pos'],0,'L',1);
            
            $amount = $this->convertNumberInWords($net);
            $amount .= " PESOS(Php".number_format($net,2,".",",").")";
            $date = new DateTime($pData['payperiod_start_date']);
            $startDate = $date->format('F j, Y');
            $date = new DateTime($pData['payperiod_end_date']);
            $endDate = $date->format('F j, Y');
            $oPDF->SetXY($coordX, $coordY+75);
            $credit = empty($pData['cred_time']) ? $credDate : $pData['cred_time']." ".$pData['am_pm']." of ".$credDate;
            
            $oPDF->MultiCell(195, 2, "This is to authorize ".strtoupper($gData['pbr_bank_name'])." to DEBIT the ".strtoupper($bankInfo['baccntype_name'])." ACCOUNT number ".$gData['pbr_company_accountno']. " of ".$gData['pbr_company_accountname']." amounting to <strong>".$amount."</strong> credit to the account of the following employees on or before <strong>".$credit."</strong> representing payroll for the period <strong>".$startDate." to ".$endDate."</strong>.",0,'J',1,1,'','',true,0,true);
           
            $coordY+=100;
            //$oPDF->SetXY($coordX+160, $coordY);
           // $oPDF->MultiCell(40, 2, $gData['pbr_ceiling_amount'],0,'L',1);

            //$oPDF->SetXY($coordX+5, $coordY);
            //$oPDF->MultiCell(40, 2, "ACCOUNT NO.:",0,'R',1);
            //$oPDF->SetXY($coordX+45, $coordY);
            //$oPDF->MultiCell(70, 2, $gData['pbr_company_accountno'],0,'L',1);
            //$oPDF->SetXY($coordX+110, $coordY);
           // $oPDF->MultiCell(50, 2, "RECORD COUNT:",0,'R',1);
            //$oPDF->SetXY($coordX+160, $coordY);
            //$oPDF->MultiCell(40, 2, $ctr,0,'L',1);
           

            //$oPDF->SetXY($coordX+5, $coordY);
            //$oPDF->MultiCell(40, 2, "BATCH NO.:",0,'R',1);
            //$oPDF->SetXY($coordX+45, $coordY);
            //$oPDF->MultiCell(70, 2, $gData['pbr_batchno'],0,'L',1);
            //$oPDF->SetXY($coordX+110, $coordY);
            //$oPDF->MultiCell(50, 2, "TOTAL PAYROLL AMOUNT :",0,'R',1);
           // $oPDF->SetXY($coordX+160, $coordY);
            //$oPDF->MultiCell(40, 2, number_format($net,2),0,'L',1);
            //$coordY+=$oPDF->getFontSize()+3;
            $oPDF->SetFont('helvetica', 'b', '10');
            $oPDF->SetXY($coordX, $coordY);
            $oPDF->MultiCell(10, 5, " ",1,'L',1);
            $oPDF->SetXY($coordX+10, $coordY);
            $oPDF->MultiCell(105, 5, "ACCOUNT NAME",1,'C',1);
            $oPDF->SetXY($coordX+110, $coordY);
            $oPDF->MultiCell(55, 5, 'BANK ACCOUNT #',1,'C',1);
            $oPDF->SetXY($coordX+160, $coordY);
            $oPDF->MultiCell(35, 5, "AMOUNT",1,'C',1);
            $coordY+=$oPDF->getFontSize()+1.5;
            $oPDF->SetFont('helvetica', '', '10');
        
        $row_ctr = 0;
        $emp_ctr=0;
        $net_total_perpage=0;
        $hash_net_total_perpage=0;
        $hash_acct_total_perpage=0;
        foreach($arrData as $key => $val ){
        	if($oPDF->getPage() == 1){
        		$maxPerPage = 20;
        	} else {
        		$maxPerPage = 42;
        	}
            $net_total_perpage +=$val['net'];
            $hash_net_total_perpage +=$val['hash'];
            $hash_acct_total_perpage +=str_replace('-', "", $val['bankiemp_acct_no']);
            $row_ctr++;
            $emp_ctr++;
            $oPDF->SetXY($coordX, $coordY);
            $oPDF->MultiCell(10, 5, $emp_ctr,1,'R',1);
            $oPDF->SetXY($coordX+10, $coordY);
            $oPDF->MultiCell(105, 5, trim(strtoupper($val['bname'])),1,'L',1);
            $oPDF->SetXY($coordX+110, $coordY);
            $oPDF->MultiCell(55, 5,strtoupper($val['bankiemp_acct_no']),1,'C',1);
            $oPDF->SetXY($coordX+160, $coordY);
            $oPDF->MultiCell(35, 5, number_format($val['net'],2),1,'R',1);
            $coordY+=$oPDF->getFontSize()+1.5;
            if($row_ctr  > $maxPerPage || $emp_ctr >= count($arrData)){
            	$oPDF->SetFont('helvetica', 'b', '10');
            	$oPDF->SetXY(115, 250);
            	$oPDF->MultiCell(90, 10, "Page Total                                                     ".number_format($net_total_perpage,2),1,'L',1);
            	$oPDF->SetFont('helvetica', '', '10');
                $oPDF->AliasNbPages();
                if($emp_ctr >= count($arrData)){
                	$oPDF->SetFont('helvetica', 'b', '10');
                	$oPDF->SetXY($coordX+110, $coordY+1.5);
            		$oPDF->MultiCell(85, 10, "Grand Total                                              ".number_format($net,2),1,'L',1);
            		$oPDF->SetFont('helvetica', '', '10');
            		$oPDF->SetXY($coordX, $coordY+20);
            		$oPDF->MultiCell(200, 10, "Your usual prompt attention on this matter will be highly appreciated.",0,'L',1);
            		$oPDF->SetXY($coordX, $coordY+35);
            		$oPDF->MultiCell(200, 10, "Very truly yours,",0,'L',1);
            		$oPDF->SetFont('helvetica', 'b', '10');
            		$oPDF->SetXY($coordX, $coordY+50);
            		$oPDF->MultiCell(200, 10, strtoupper($gData['pbr_prepared_by']),0,'L',1);
            		$oPDF->SetXY($coordX, $coordY+55);
            		$oPDF->MultiCell(200, 10, $pData['pbr_prepared_pos'],0,'L',1);
                }
                if($row_ctr  > $maxPerPage){
                // set initial pdf page
		            $oPDF->AddPage();
		            $oPDF->SetFillColor(255,255,255);
                }
	            // reset variable settings
                $net_total_perpage = 0;
                $hash_net_total_perpage = 0;
                $hash_acct_total_perpage = 0;
                $row_ctr = 0;
                $coordY = 30;
               if($coordY==$maxPerPage){
                    $coordY+=$oPDF->getFontSize()+4;
                }else{
                    // increment coordinate Y
                    $coordY+=$oPDF->getFontSize()+1;
                }
            }
        }
       /* $oPDF->Line($coordX+5, $coordY+5.5, 200, $coordY+5.5);
        $coordY+=$oPDF->getFontSize()+1;
        // plot net total,hash total, accnt number hash total per page
        $oPDF->SetFont('helvetica', '', '10');
        $oPDF->SetXY($coordX+5, $coordY);
        $oPDF->MultiCell(80, 2, "HASH TOTALS (page)",0,'L',1);
        $coordY+=$oPDF->getFontSize()+1;
        $oPDF->SetXY($coordX+5, $coordY);
        $oPDF->MultiCell(40, 2, $hash_acct_total_perpage,0,'C',1);
        $oPDF->SetXY($coordX+45, $coordY);
        $oPDF->MultiCell(70, 2,"",0,'L',1);
        $oPDF->SetXY($coordX+110, $coordY);
        $oPDF->MultiCell(50, 2, number_format($net_total_perpage,2),0,'R',1);
        $oPDF->SetXY($coordX+160, $coordY);
        $oPDF->MultiCell(40, 2,number_format($hash_net_total_perpage,2),0,'R',1);
        $coordY+=$oPDF->getFontSize()+1;

        // grand totals
        $oPDF->SetXY($coordX+5, $coordY);
        $oPDF->MultiCell(70, 2, "COMPUTED HASH TOTALS",0,'L',1);
        $coordY+=$oPDF->getFontSize()+1;
        
        $oPDF->SetXY($coordX+5, $coordY);
        $oPDF->MultiCell(40, 2, $gtotal_hash_acct,0,'C',1);
        $oPDF->SetXY($coordX+45, $coordY);
        $oPDF->MultiCell(70, 2,"",0,'L',1);
        $oPDF->SetXY($coordX+110, $coordY);
        $oPDF->MultiCell(50, 2, number_format($net,2),0,'R',1);
        $oPDF->SetXY($coordX+160, $coordY);
        $oPDF->MultiCell(40, 2,number_format($gtotal_hash,2),0,'R',1);
        $coordY+=$oPDF->getFontSize()+10;

        $oPDF->SetXY($coordX+5, $coordY);
        $oPDF->MultiCell(100, 2, "PREPARED BY:",0,'L',1);
        $oPDF->SetXY($coordX+100, $coordY);
        $oPDF->MultiCell(100, 2,"APPROVED BY:",0,'L',1);
        $coordY+=$oPDF->getFontSize()+7;
        
        $oPDF->SetXY($coordX+5, $coordY);
        $oPDF->MultiCell(100, 2, "__________________________________________",0,'L',1);
        $oPDF->SetXY($coordX+100, $coordY);
        $oPDF->MultiCell(100, 2,"__________________________________________",0,'L',1);
        $coordY+=$oPDF->getFontSize()+1;
        
        $oPDF->SetXY($coordX+5, $coordY);
        $oPDF->MultiCell(100, 2, "",0,'L',1);
        $oPDF->SetXY($coordX+5, $coordY);
        $oPDF->MultiCell(100, 2,$gData['pbr_prepared_by'],0,'C',1);
        $oPDF->SetXY($coordX+5, $coordY);
        $oPDF->MultiCell(100, 2, "",0,'L',1);
        $oPDF->SetXY($coordX+100, $coordY);
        $oPDF->MultiCell(100, 2,$gData['pbr_approved_by'],0,'C',1);
        $coordY+=$oPDF->getFontSize()+3;
        */
        // get payperiod dates(from and to)
        $ppDetails = $this->getPPDetails($gData['payperiod_id']);
        
        //build hash header for the text hash report .str_pad($gData['pbr_company_code'],5,'0',STR_PAD_LEFT)
        $hashHeader_ ='H';
        $date = dDate::getFormattedDateStampMDYLong(dDate::parseDateTime($gData['pbr_credit_date']));
        $hashHeader_ .= str_pad($date,8,'0',STR_PAD_LEFT);
        //company account no
        $hashHeader_ .= str_pad(str_replace('-', "", trim($gData['pbr_company_accountno'])),13,'0',STR_PAD_LEFT);
        //company name
        $hashHeader_ .= str_pad(str_replace('-', "", trim($gData['pbr_company_code'])),30,'0',STR_PAD_LEFT);
        //payroll period from
        $hashHeader_ .= str_pad($ppDetails['pp_from'],8,' ',STR_PAD_LEFT);
        //payroll period to
        $hashHeader_ .= str_pad($ppDetails['pp_to'],8,' ',STR_PAD_LEFT);
        //net
        $hashHeader_ .= str_pad(str_replace('.', "", $net),13,'0',STR_PAD_LEFT);
        //total number of employees
        $hashHeader_ .= str_pad($ctr,3,'0',STR_PAD_LEFT);
        
        //build hash footer for the text hash report
        // H & account # total
        $hashFooter_ ='T'.str_pad(number_format($net,0,'',''),13,'0',STR_PAD_LEFT);
        // total netpay
        //$hashFooter_ .= str_pad(number_format($net,0,'.',''),13,'0',STR_PAD_LEFT);
        // total hash
        $hashFooter_ .= str_pad($ctr,3,'0',STR_PAD_LEFT);
        
        // get the pdf output
        $output = $oPDF->Output("payroll_transaction_prooflist".date('Y-m-d').".pdf","S");
        if(!empty($output)){
            $this->doTextHash($arrData, $hashHeader_, $hashFooter_);
            return $output;
        }
        return false;
    }
    /**
     * 
     * Convert monetary value to words
     * @param float $number
     * @author: IR Salvador
     * @note: maximum value is in millions only.
     */
    function convertNumberInWords($number) {
	    if (($number < 0) || ($number > 999999999)) { 
	    	throw new Exception("Number is out of range");
	    } 
		
	    $dec = explode(".", $number);
	 	if(count($dec) > 1){
	 		$decimal = " and ".$dec[1]."/100";
	    }
	    
		$Gn = floor($number / 1000000);  /* Millions (giga) */ 
	    $number -= $Gn * 1000000; 
	    $kn = floor($number / 1000);     /* Thousands (kilo) */ 
	    $number -= $kn * 1000; 
	    $Hn = floor($number / 100);      /* Hundreds (hecto) */ 
	    $number -= $Hn * 100; 
	    $Dn = floor($number / 10);       /* Tens (deca) */ 
	    $n = $number % 10;               /* Ones */ 
	
	    $res = ""; 
	    
		if ($Gn){ 
	        $res .= self::convertNumberInWords($Gn) . " Million"; 
	    } 
	
	    if ($kn){ 
	        $res .= (empty($res) ? "" : " ") . 
	        self::convertNumberInWords($kn) . " Thousand"; 
	    } 
	
	    if ($Hn){ 
	        $res .= (empty($res) ? "" : " ") . 
	        self::convertNumberInWords($Hn) . " Hundred"; 
	    } 
	
	    $ones = array("", "One", "Two", "Three", "Four", "Five", "Six", 
	        "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", 
	        "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen", 
	        "Nineteen"); 
	    $tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty", 
	        "Seventy", "Eigthy", "Ninety"); 
	
	    if ($Dn || $n){ 
	        if (!empty($res) && empty($decimal)){ 
	            $res .= " "; 
	        } else {
	        	$res .=" ";
	        }
	
	        if ($Dn < 2){ 
	            $res .= $ones[$Dn * 10 + $n]; 
	        } else { 
	            $res .= $tens[$Dn]; 
	            if ($n){ 
	                $res .= "-" . $ones[$n]; 
	            } 
	        } 
	    } 
	   	if(!empty($decimal) && $n){
	   		$res .= $decimal;
	   	}
	    if(empty($res)){ 
	        $res = "zero"; 
	    } 
	    return $res; 
} 
}
?>