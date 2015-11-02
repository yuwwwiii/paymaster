<?php
/**
 * Initial Declaration
 */
require_once(SYSCONFIG_CLASS_PATH.'admin/reports/sss.class.php');
/**
 * Class Module
 *
 * @author  JIM
 *
 */
class clsPHIC extends clsSSS{

	var $conn;
	var $fieldMap;
	var $Data;

	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsPHIC object
	 */
	function clsPHIC($dbconn_ = null){
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
		if($gData['type'] == 'month'){
            $y = "".$gData['year']."-".$gData['month']."-01";
            $enddate = dDate::getEndMonthEpoch(dDate::parseDateTime($y));
            $startdate = dDate::getBeginMonthEpoch(dDate::parseDateTime($y));
        }elseif($gData['type'] == 'year'){
            $y = $gData['year']."-01-01";
            $enddate = dDate::getEndYearEpoch(dDate::parseDateTime($y));
            $startdate = dDate::getBeginYearEpoch(dDate::parseDateTime($y));
        }else{
//            printa($gData);
            $y = "".$gData['year']."-".$gData['month']."-01";
            $enddate = dDate::getEndMonthEpoch(dDate::parseDateTime($y));
            $startdate = dDate::parseDateTime($gData['year']."-01-01");
        }
        $arrData = array();
        
		$qry = array();
		$qry[] = "b.emp_stat = 1";
		$qry[] = "b.comp_id = '".$gData['comp']."'";
		$criteria = count($qry)>0 ? " where ".implode(' and ',$qry) : '';

		$sql = "select f.ud_name,b.emp_id, g.salaryinfo_basicrate, d.*, concat(d.pi_fname,' ',d.pi_mname,' ',d.pi_lname) as name, CONCAT(upper(RPAD(d.pi_mname,1,'')),'.') as pi_mname, CONCAT(upper(h.comp_name)) as comp_name, h.*, DATE_FORMAT(b.emp_hiredate,'%m%d%Y') as emp_hiredate
				from payroll_pps_user a 
				inner join emp_masterfile b on (b.emp_id =a.emp_id) 
				inner join emp_personal_info d on (d.pi_id = b.pi_id)  
				inner join app_userdept f on (f.ud_id = b.ud_id) 
				inner join salary_info g on (g.emp_id = b.emp_id)
				inner join company_info h on (h.comp_id = b.comp_id) 
				$criteria
				order by d.pi_lname ASC";

		$rsResult = $this->conn->Execute($sql);
            
			while(!$rsResult->EOF){			
				$arrData[] = $rsResult->fields;
				$rsResult->MoveNext();
			}

			for ($x = 0; $x < count($arrData); $x++){
					$arrDataDeduction[$x] = array(
							"emp_info" => $arrData[$x],
							"date" => $startdate,
							"dateprocess" => date('mdY'),
							"datemyear" => date('mY'),
							"sss" => $this->getDeduction($arrData[$x]['emp_id'],date('Y-m-d',$startdate),date('Y-m-d',$enddate),$istype_));
			}
            printa($arrDataDeduction);
            exit;
		return $arrDataDeduction;
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
		$editLink = "<a href=\"?statpos=phic&edit=',am.mnu_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/edit.png\" title=\"Edit\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
		$delLink = "<a href=\"?statpos=phic&delete=',am.mnu_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\"  border=0 width=\"16\" height=\"16\"></a>";

		// SqlAll Query
		$sql = "select am.*, CONCAT('$viewLink','$editLink','$delLink') as viewdata
						from app_modules am
						$criteria
						$strOrderBy";

		// Field and Table Header Mapping
		$arrFields = array(
		 "viewdata"=>"Action"
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
	
	function generateTxtFileRF1($arrData = array(), $gData = array(), $delimeter_ = "\r\n"){
	  $qrtN = clsSSS::getQuarterPeriod($gData['month']);
	  $dateprocess = dDate::getFormattedDateStampMDYLong(dDate::parseDateTime($gData['trasdate']));
      if(count($arrData)>0){
            $ctr = 0;
            //build hash header for the text hash report
	        $hashHeader_ ='REMITTANCE REPORT'.$delimeter_;
	        $hashHeader_ .= str_pad(substr(trim(strtoupper($arrData['0']['emp_info']['comp_name'])),0,60),60,' ',STR_PAD_RIGHT).$delimeter_;
	        $hashHeader_ .= str_pad(substr(trim(strtoupper($arrData['0']['emp_info']['comp_add'])),0,100),100,' ',STR_PAD_RIGHT).$delimeter_;
	        $Cphicno = str_replace('-',"",trim($arrData['0']['emp_info']['comp_phic']));
	        IF($Cphicno == ""){//Get Company PHIC No.
	        	$Cphicno_ = str_replace('-',"",trim($arrData['0']['emp_info']['comp_sss']));
	        }else{
	        	$Cphicno_ = $Cphicno;
	        }
	        $hashHeader_ .= str_pad($Cphicno_,12,' ',STR_PAD_RIGHT).str_pad($qrtN,1,' ',STR_PAD_RIGHT).str_pad($gData['year'],4,' ',STR_PAD_RIGHT).str_pad('R',1,' ',STR_PAD_RIGHT).$delimeter_;
			$hashHeader_ .='MEMBERS';
	        
			//build hash body for the text hash report
            foreach ($arrData as $key => $val) {
	            $phicno = $val['emp_info']['pi_phic'][0].$val['emp_info']['pi_phic'][1].$val['emp_info']['pi_phic'][2];
	            $MonthlyRate = $this->computeMonthlyRate($val['emp_info']['emp_id']);
	            IF($phicno == ""){//Get Employee PHIC No.
	            	$phicno_ = $val['emp_info']['pi_sss'];
	            }ELSE{
	            	$phicno_ = $phicno;
	            }
            	IF(number_format($val['sss']['ppe_amount_employer'],2)=='0.00'){
            		$estat = "NE";
                }ELSE{
                	$estat = str_replace(' ',"",str_replace(')',"",str_replace('(',"",str_replace('/',"",$val['sss']['remarks']))));
                }
            	$eree = $val['sss']['ppe_amount_employer']+$val['sss']['ppe_amount'];
            	$phicee = $val['sss']['ppe_amount'];
            	$phicer = $val['sss']['ppe_amount_employer'];
	            $ec = $val['sss']['ppe_units'];
		    	$ctr++;
				$text_hash  = str_pad(substr(str_replace('-',"",$phicno_),0,12),12,' ',STR_PAD_RIGHT);
	            $text_hash .= str_pad(substr($val['emp_info']['pi_lname'],0,30),30,' ',STR_PAD_RIGHT);
	            $text_hash .= str_pad(substr($val['emp_info']['pi_fname'],0,30),30,' ',STR_PAD_RIGHT);
	            $text_hash .= str_pad(substr($val['emp_info']['pi_mname'],0,1),1,' ',STR_PAD_RIGHT);
	            $text_hash .= str_pad(number_format(str_replace(',','',$MonthlyRate),2,'',''),8,'0',STR_PAD_LEFT);
	            $text_hash .= str_pad(number_format($phicee,2,'',''),6,'0',STR_PAD_LEFT);
	            $text_hash .= str_pad(number_format($phicer,2,'',''),6,'0',STR_PAD_LEFT);
	            $text_hash .= str_pad('0',24,'0',STR_PAD_LEFT);
	            $text_hash .= str_pad($estat,2,' ',STR_PAD_RIGHT);
	            $text_hash .= str_pad(' ',8,' ',STR_PAD_RIGHT);
				$arr_txthash[$ctr]['txt_hash'] = $text_hash;         

            	$eree_cont += $eree;
            	$phiceeT += $phicee;
            	$phicerT += $phicer;
            	$total_ec += $ec;
            	if($phicee!=''){
            		$empT_ += 1;
            	}
            }
            
            //build hash footer for the text hash report
	        $hashFooter_ ='M5-SUMMARY'.$delimeter_;
	        $hashFooter_ .='1'.str_pad(number_format(substr($eree_cont,0,12),2,'',''),12,'0',STR_PAD_LEFT);
	        $hashFooter_ .=str_pad(substr($gData['receiptno'],0,15),15,' ',STR_PAD_RIGHT);
	        $hashFooter_ .=str_pad($dateprocess,8,'0',STR_PAD_RIGHT);
	        $hashFooter_ .=str_pad($empT_,4,' ',STR_PAD_RIGHT).$delimeter_;
	        $hashFooter_ .='GRAND TOTAL'.str_pad(number_format(substr($eree_cont,0,12),2,'',''),12,'0',STR_PAD_LEFT).$delimeter_;
	        $hashFooter_ .=str_pad(substr(strtoupper($gData['signatoryby']),0,40),40,' ',STR_PAD_RIGHT);
	        $hashFooter_ .=str_pad(substr(strtoupper($gData['position']),0,20),20,' ',STR_PAD_RIGHT);
//			printa($hashHeader_);
//			printa($arr_txthash);
//			printa($hashFooter_);
//			exit;
		}    
		$output = clsSSS::doTextHash($arr_txthash, $hashHeader_, $hashFooter_);
        return $output;
    }
    
	/**
     * 
     * @param $arrData
     * @param $gData
     * @param $delimeter_
     */
	FUNCTION GenTxtFileRF1_Monthly($arrData = array(), $gData = array(), $delimeter_ = "\r\n"){
	  $objClsSSS = new clsSSS();
	  $dateprocess = dDate::getFormattedDateStampMDYLong(dDate::parseDateTime($gData['trasdate']));
      if(count($arrData)>0){
            $ctr = 0;
            //build hash header for the text hash report
	        $hashHeader_ ='REMITTANCE REPORT'.$delimeter_;
	        $hashHeader_ .= str_pad(substr(trim(strtoupper($arrData['0']['emp_info']['comp_name'])),0,60),60,' ',STR_PAD_RIGHT).$delimeter_;
	        $hashHeader_ .= str_pad(substr(trim(strtoupper($arrData['0']['emp_info']['comp_add'])),0,100),100,' ',STR_PAD_RIGHT).$delimeter_;
	        $Cphicno = str_replace('-',"",trim($arrData['0']['emp_info']['comp_phic']));
	        IF($Cphicno == ""){//Get Company PHIC No.
	        	$Cphicno_ = str_replace('-',"",trim($arrData['0']['emp_info']['comp_sss']));
	        }else{
	        	$Cphicno_ = $Cphicno;
	        }
	        $hashHeader_ .= str_pad($Cphicno_,12,' ',STR_PAD_RIGHT).str_pad($gData['month'],2,' ',STR_PAD_RIGHT).str_pad($gData['year'],4,' ',STR_PAD_RIGHT).str_pad('R',1,' ',STR_PAD_RIGHT).$delimeter_;
			$hashHeader_ .='MEMBERS';
	        
			//build hash body for the text hash report
            foreach ($arrData as $key => $val) {
	            $phicno = $val['emp_info']['pi_phic'][0].$val['emp_info']['pi_phic'][1].$val['emp_info']['pi_phic'][2];
	            IF($phicno == ""){//Get Employee PHIC No.
	            	$phicno_ = $val['emp_info']['pi_sss'];
	            }else{
	            	$phicno_ = $phicno;
	            }
            	IF(number_format($val['sss']['ppe_amount_employer'],2)=='0.00'){
            		$estat = "NE";
                }ELSE{
                	$estat = str_replace(' ',"",str_replace(')',"",str_replace('(',"",str_replace('/',"",$val['sss']['remarks']))));
                }
            	$eree = $val['sss']['ppe_amount_employer']+$val['sss']['ppe_amount'];
            	$phicee = $val['sss']['ppe_amount'];
            	$phicer = $val['sss']['ppe_amount_employer'];
	            $ec = $val['sss']['ppe_units'];
		    	$ctr++;
				$text_hash  = str_pad(substr(str_replace('-',"",$phicno_),0,12),12,' ',STR_PAD_RIGHT);
	            $text_hash .= str_pad(substr($val['emp_info']['pi_lname'],0,30),30,' ',STR_PAD_RIGHT);
	            $text_hash .= str_pad(substr($val['emp_info']['pi_fname'],0,30),30,' ',STR_PAD_RIGHT);
	            $text_hash .= str_pad(substr($val['emp_info']['pi_mname'],0,1),1,' ',STR_PAD_RIGHT);
	            $text_hash .= str_pad(number_format($val['emp_info']['salaryinfo_basicrate'],2,'',''),8,'0',STR_PAD_LEFT);
	            $text_hash .= str_pad(number_format($phicee,2,'',''),6,'0',STR_PAD_LEFT);
	            $text_hash .= str_pad(number_format($phicer,2,'',''),6,'0',STR_PAD_LEFT);
	            $text_hash .= str_pad('0',24,'0',STR_PAD_LEFT);
//	            $text_hash .= str_pad('N',2,' ',STR_PAD_RIGHT);
//	            $text_hash .= str_pad($val['emp_info']['emp_hiredate'],8,' ',STR_PAD_RIGHT);
	            $text_hash .= str_pad($estat,2,' ',STR_PAD_RIGHT);
	            $text_hash .= str_pad(' ',8,' ',STR_PAD_RIGHT);
				$arr_txthash[$ctr]['txt_hash'] = $text_hash;         

            	$eree_cont += $eree;
            	$phiceeT += $phicee;
            	$phicerT += $phicer;
            	$total_ec += $ec;
            	if($phicee!=''){
            		$empT_ += 1;
            	}
            }
            
            //build hash footer for the text hash report
	        $hashFooter_ ='M5-/POR SUMMARY'.$delimeter_;
	        $hashFooter_ .='1'.str_pad(number_format(substr($eree_cont,0,12),2,'',''),12,'0',STR_PAD_LEFT);
	        $hashFooter_ .=str_pad(substr($gData['receiptno'],0,15),15,'0',STR_PAD_LEFT);
	        $hashFooter_ .=str_pad($dateprocess,8,'0',STR_PAD_RIGHT);
	        $hashFooter_ .=str_pad($empT_,4,' ',STR_PAD_RIGHT).$delimeter_;
	        $hashFooter_ .='GRAND TOTAL'.str_pad(number_format(substr($eree_cont,0,12),2,'',''),12,'0',STR_PAD_LEFT).$delimeter_;
	        $hashFooter_ .=str_pad(substr(strtoupper($gData['signatoryby']),0,40),40,' ',STR_PAD_RIGHT);
	        $hashFooter_ .=str_pad(substr(strtoupper($gData['position']),0,20),20,' ',STR_PAD_RIGHT);
//			printa($hashHeader_);
//			printa($arr_txthash);
//			printa($hashFooter_);
//			exit;
		}    
		$output = $objClsSSS->doTextHash($arr_txthash, $hashHeader_, $hashFooter_);
        return $output;
    }
    
	/**
	 * @note: This function is used to creat pdf file for SSS R-5.
	 * @param unknown_type $arrData
	 * @param unknown_type $gData
	 */
    function getPDFResultRF1Payment($arrData = array(), $gData = array()){
        $orientation='L';
        $unit='mm';
        $format='letter';
        $unicode=true;
        $encoding="UTF-8";

        $oPDF = new clsPDF($orientation, $unit, $format, $unicode, $encoding);
//        $oPDF->SetHeaderData('', PDF_HEADER_LOGO_WIDTH, $branch_details['comp_name'], $branch_details['branch_address'].'-'.$branch_details['branch_trunklines']);
        // set header and footer fonts
        $oPDF->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $oPDF->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        //set margins
        $oPDF->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $oPDF->SetHeaderMargin(PDF_MARGIN_HEADER);
        $oPDF->SetAutoPageBreak(false);
        // use a freeserif font as a default font
        $oPDF->SetFont('helvetica','',12);

        // suppress print header and footer
        $oPDF->setPrintHeader(true);
        $oPDF->setPrintFooter(false);

        // set initila coordinates
        $coordX = 7;
        $coordY = 15;

        $oPDF->AliasNbPages();

        // set initial pdf page
        $oPDF->AddPage();
        $oPDF->SetFillColor(255,255,255);
        
        //used to line style...
		$style5 = array('dash' => 3);
		$style6 = array('dash' => 0);
		$style7 = array('width' => .5,'dash' => 0);
		
        $m = $gData['year'].'-'.$gData['month'].'-'.'1';
        if($gData['type'] == 'ytd'){
            $str = "YTD Report";
        }elseif($gData['type'] == 'month'){
            $str = "Monthly Report";
        }else{
            $str = "Yearly Report";
        }

        if($gData['type'] == 'month'){
            $str2= date('F Y',dDate::parseDateTime($m));
            $str2month = date('F',dDate::parseDateTime($m));
        }elseif($gData['type'] == 'ytd'){
            $str2= "January 2011 to ".date('F Y',dDate::parseDateTime($m));
        }else{
            $str2= $gData['year'];
        }
        
    	IF($gData['branchinfo_id']!=0){
        	$branch_details = clsSSS::getLocationInfo($gData['branchinfo_id']);
        	$compname = $branch_details['branchinfo_name'];
        	$compadds = $branch_details['branchinfo_add'];
        	$compphicno = $branch_details['branchinfo_phic'];
        	$comptinno = $branch_details['branchinfo_tin'];
        	$comptelno = $branch_details['branchinfo_tel1'];
        }ELSE{
        	$branch_details = $this->dbfetchCompDetails($gData['comp']);
        	$compname = $branch_details['comp_name'];
        	$compadds = $branch_details['comp_add'];
        	$compphicno = $branch_details['comp_phic'];
        	$comptinno = $branch_details['comp_tin'];
        	$comptelno = $branch_details['comp_tel'];
        }
		
        //creating boxs.
        //------------------------------------------------------------------------------->>
        //--->> LEFT
        $oPDF->SetFont('helvetica', '', '9');
        $oPDF->Line($coordX, $coordY, $coordX, $coordY+145, $style = $style6); //left side line
        $oPDF->Line($coordX+127, $coordY, $coordX+127, $coordY+145, $style = $style6); //right side line
        $oPDF->Line($coordX+7, $coordY+55, $coordX+7, $coordY+145, $style = $style6); // from name employee line
        
        $oPDF->Line($coordX, $coordY, $coordX+127, $coordY, $style = $style6);
        $oPDF->Line($coordX, $coordY+15, $coordX+127, $coordY+15, $style = $style6);
        $oPDF->Line($coordX, $coordY+35, $coordX+127, $coordY+35, $style = $style6);
        
        $oPDF->Line($coordX+32, $coordY, $coordX+32, $coordY+15, $style = $style6);
        $oPDF->Line($coordX, $coordY+20, $coordX+5, $coordY+20, $style = $style6);//small box 1
        $oPDF->Line($coordX+5, $coordY+15, $coordX+5, $coordY+20, $style = $style6);//small box 1
        $oPDF->Line($coordX, $coordY+40, $coordX+5, $coordY+40, $style = $style6);//small box 2
        $oPDF->Line($coordX+5, $coordY+35, $coordX+5, $coordY+40, $style = $style6);//small box 2
        
        $oPDF->Line($coordX, $coordY+55, $coordX+127, $coordY+55, $style = $style6);
        $oPDF->Line($coordX, $coordY+62, $coordX+127, $coordY+62, $style = $style6);
        $oPDF->Line($coordX, $coordY+70, $coordX+127, $coordY+70, $style = $style6);
        //--->> END
        
        //--->> RIGHT
        $oPDF->Line($coordX+127, $coordY, $coordX+267, $coordY, $style = $style5);
        $oPDF->Line($coordX+127, $coordY+7.5, $coordX+267, $coordY+7.5, $style = $style5);
        $oPDF->Line($coordX+127, $coordY+34.8, $coordX+267, $coordY+34.8, $style = $style5);
        $oPDF->Line($coordX+127, $coordY+35, $coordX+267, $coordY+35, $style = $style6);
        $oPDF->Line($coordX+127, $coordY+40, $coordX+132, $coordY+40, $style = $style6);//small box 3
        $oPDF->Line($coordX+132, $coordY+35, $coordX+132, $coordY+40, $style = $style6);//small box 3
        $oPDF->Line($coordX+267, $coordY, $coordX+267, $coordY+35, $style = $style5);//side line
        $oPDF->Line($coordX+127.2, $coordY, $coordX+127.2, $coordY+35, $style = $style5);//side line
        $oPDF->Line($coordX+127, $coordY+55, $coordX+267, $coordY+55, $style = $style6);
        $oPDF->Line($coordX+267, $coordY+35, $coordX+267, $coordY+145, $style = $style6);//side right line
        $oPDF->Line($coordX+227, $coordY+35, $coordX+227, $coordY+145, $style = $style6);//side line
        $oPDF->Line($coordX+227, $coordY+40, $coordX+232, $coordY+40, $style = $style6);//small box 5
        $oPDF->Line($coordX+232, $coordY+35, $coordX+232, $coordY+40, $style = $style6);//small box 5
        $oPDF->Line($coordX+176, $coordY+35, $coordX+176, $coordY+145, $style = $style6);//side line
        $oPDF->Line($coordX+176, $coordY+40, $coordX+181, $coordY+40, $style = $style6);//small box 4
        $oPDF->Line($coordX+181, $coordY+35, $coordX+181, $coordY+40, $style = $style6);//small box 4
        $oPDF->Line($coordX+128, $coordY+55, $coordX+128, $coordY+145, $style = $style6);//side line
        
        $oPDF->Line($coordX+128, $coordY+60, $coordX+133, $coordY+60, $style = $style6);//small box 7
        $oPDF->Line($coordX+133, $coordY+55, $coordX+133, $coordY+60, $style = $style6);//small box 7
        $oPDF->Line($coordX+177, $coordY+55, $coordX+177, $coordY+145, $style = $style6);//side line
        $oPDF->Line($coordX+177, $coordY+60, $coordX+182, $coordY+60, $style = $style6);//small box 8
        $oPDF->Line($coordX+182, $coordY+55, $coordX+182, $coordY+60, $style = $style6);//small box 8
        $oPDF->Line($coordX+187, $coordY+55, $coordX+187, $coordY+145, $style = $style6);//side line
        $oPDF->Line($coordX+188, $coordY+55, $coordX+188, $coordY+145, $style = $style6);//side line
        $oPDF->Line($coordX+188, $coordY+60, $coordX+193, $coordY+60, $style = $style6);//small box 9
        $oPDF->Line($coordX+193, $coordY+55, $coordX+193, $coordY+60, $style = $style6);//small box 9
        $oPDF->Line($coordX+207.5, $coordY+65, $coordX+207.5, $coordY+145, $style = $style6);//side line
        $oPDF->Line($coordX+228, $coordY+55, $coordX+228, $coordY+145, $style = $style6);//side line
        $oPDF->Line($coordX+228, $coordY+60, $coordX+233, $coordY+60, $style = $style6);//small box 10
        $oPDF->Line($coordX+233, $coordY+55, $coordX+233, $coordY+60, $style = $style6);//small box 10
        
        $oPDF->Line($coordX+128, $coordY+70, $coordX+176, $coordY+70, $style = $style6);
        $oPDF->Line($coordX+177, $coordY+70, $coordX+187, $coordY+70, $style = $style6);
        $oPDF->Line($coordX+188, $coordY+65, $coordX+227, $coordY+65, $style = $style6);
        $oPDF->Line($coordX+188, $coordY+70, $coordX+227, $coordY+70, $style = $style6);
        $oPDF->Line($coordX+228, $coordY+65, $coordX+267, $coordY+65, $style = $style6);
        $oPDF->Line($coordX+228, $coordY+70, $coordX+267, $coordY+70, $style = $style6);
        //--->> END
        
        //Employee List Boxs
        //--->> LEFT
        $oPDF->Line($coordX, $coordY+75, $coordX+127, $coordY+75, $style = $style6);
        $oPDF->Line($coordX, $coordY+80, $coordX+127, $coordY+80, $style = $style6);
        $oPDF->Line($coordX, $coordY+85, $coordX+127, $coordY+85, $style = $style6);
        $oPDF->Line($coordX, $coordY+90, $coordX+127, $coordY+90, $style = $style6);
        $oPDF->Line($coordX, $coordY+95, $coordX+127, $coordY+95, $style = $style6);
        $oPDF->Line($coordX, $coordY+100, $coordX+127, $coordY+100, $style = $style6);
        $oPDF->Line($coordX, $coordY+105, $coordX+127, $coordY+105, $style = $style6);
        $oPDF->Line($coordX, $coordY+110, $coordX+127, $coordY+110, $style = $style6);
        $oPDF->Line($coordX, $coordY+115, $coordX+127, $coordY+115, $style = $style6);
        $oPDF->Line($coordX, $coordY+120, $coordX+127, $coordY+120, $style = $style6);
        $oPDF->Line($coordX, $coordY+125, $coordX+127, $coordY+125, $style = $style6);
        $oPDF->Line($coordX, $coordY+130, $coordX+127, $coordY+130, $style = $style6);
        $oPDF->Line($coordX, $coordY+135, $coordX+127, $coordY+135, $style = $style6);
        $oPDF->Line($coordX, $coordY+140, $coordX+127, $coordY+140, $style = $style6);
        $oPDF->Line($coordX, $coordY+145, $coordX+127, $coordY+145, $style = $style6);
        //---<< END
        
        //--->> RIGHT
        $oPDF->Line($coordX+128, $coordY+145, $coordX+176, $coordY+145, $style = $style6);
        $oPDF->Line($coordX+128, $coordY+75, $coordX+176, $coordY+75, $style = $style6);
        $oPDF->Line($coordX+128, $coordY+80, $coordX+176, $coordY+80, $style = $style6);
        $oPDF->Line($coordX+128, $coordY+85, $coordX+176, $coordY+85, $style = $style6);
        $oPDF->Line($coordX+128, $coordY+90, $coordX+176, $coordY+90, $style = $style6);
        $oPDF->Line($coordX+128, $coordY+95, $coordX+176, $coordY+95, $style = $style6);
        $oPDF->Line($coordX+128, $coordY+100, $coordX+176, $coordY+100, $style = $style6);
        $oPDF->Line($coordX+128, $coordY+105, $coordX+176, $coordY+105, $style = $style6);
        $oPDF->Line($coordX+128, $coordY+110, $coordX+176, $coordY+110, $style = $style6);
        $oPDF->Line($coordX+128, $coordY+115, $coordX+176, $coordY+115, $style = $style6);
        $oPDF->Line($coordX+128, $coordY+120, $coordX+176, $coordY+120, $style = $style6);
        $oPDF->Line($coordX+128, $coordY+125, $coordX+176, $coordY+125, $style = $style6);
        $oPDF->Line($coordX+128, $coordY+130, $coordX+176, $coordY+130, $style = $style6);
        $oPDF->Line($coordX+128, $coordY+135, $coordX+176, $coordY+135, $style = $style6);
        $oPDF->Line($coordX+128, $coordY+140, $coordX+176, $coordY+140, $style = $style6);
        
        $oPDF->Line($coordX+177, $coordY+75, $coordX+187, $coordY+75, $style = $style6);
        $oPDF->Line($coordX+177, $coordY+80, $coordX+187, $coordY+80, $style = $style6);
        $oPDF->Line($coordX+177, $coordY+85, $coordX+187, $coordY+85, $style = $style6);
        $oPDF->Line($coordX+177, $coordY+90, $coordX+187, $coordY+90, $style = $style6);
        $oPDF->Line($coordX+177, $coordY+95, $coordX+187, $coordY+95, $style = $style6);
        $oPDF->Line($coordX+177, $coordY+100, $coordX+187, $coordY+100, $style = $style6);
        $oPDF->Line($coordX+177, $coordY+105, $coordX+187, $coordY+105, $style = $style6);
        $oPDF->Line($coordX+177, $coordY+110, $coordX+187, $coordY+110, $style = $style6);
        $oPDF->Line($coordX+177, $coordY+115, $coordX+187, $coordY+115, $style = $style6);
        $oPDF->Line($coordX+177, $coordY+120, $coordX+187, $coordY+120, $style = $style6);
        $oPDF->Line($coordX+177, $coordY+125, $coordX+187, $coordY+125, $style = $style6);
        $oPDF->Line($coordX+177, $coordY+130, $coordX+187, $coordY+130, $style = $style6);
        $oPDF->Line($coordX+177, $coordY+135, $coordX+187, $coordY+135, $style = $style6);
        $oPDF->Line($coordX+177, $coordY+140, $coordX+187, $coordY+140, $style = $style6);
        $oPDF->Line($coordX+177, $coordY+145, $coordX+187, $coordY+145, $style = $style6);
        
        $oPDF->Line($coordX+188, $coordY+75, $coordX+227, $coordY+75, $style = $style6);
        $oPDF->Line($coordX+188, $coordY+80, $coordX+227, $coordY+80, $style = $style6);
        $oPDF->Line($coordX+188, $coordY+85, $coordX+227, $coordY+85, $style = $style6);
        $oPDF->Line($coordX+188, $coordY+90, $coordX+227, $coordY+90, $style = $style6);
        $oPDF->Line($coordX+188, $coordY+95, $coordX+227, $coordY+95, $style = $style6);
        $oPDF->Line($coordX+188, $coordY+100, $coordX+227, $coordY+100, $style = $style6);
        $oPDF->Line($coordX+188, $coordY+105, $coordX+227, $coordY+105, $style = $style6);
        $oPDF->Line($coordX+188, $coordY+110, $coordX+227, $coordY+110, $style = $style6);
        $oPDF->Line($coordX+188, $coordY+115, $coordX+227, $coordY+115, $style = $style6);
        $oPDF->Line($coordX+188, $coordY+120, $coordX+227, $coordY+120, $style = $style6);
        $oPDF->Line($coordX+188, $coordY+125, $coordX+227, $coordY+125, $style = $style6);
        $oPDF->Line($coordX+188, $coordY+130, $coordX+227, $coordY+130, $style = $style6);
        $oPDF->Line($coordX+188, $coordY+135, $coordX+227, $coordY+135, $style = $style6);
        $oPDF->Line($coordX+188, $coordY+140, $coordX+227, $coordY+140, $style = $style6);
        $oPDF->Line($coordX+188, $coordY+145, $coordX+227, $coordY+145, $style = $style6);
        
        $oPDF->Line($coordX+228, $coordY+75, $coordX+267, $coordY+75, $style = $style6);
        $oPDF->Line($coordX+228, $coordY+80, $coordX+267, $coordY+80, $style = $style6);
        $oPDF->Line($coordX+228, $coordY+85, $coordX+267, $coordY+85, $style = $style6);
        $oPDF->Line($coordX+228, $coordY+90, $coordX+267, $coordY+90, $style = $style6);
        $oPDF->Line($coordX+228, $coordY+95, $coordX+267, $coordY+95, $style = $style6);
        $oPDF->Line($coordX+228, $coordY+100, $coordX+267, $coordY+100, $style = $style6);
        $oPDF->Line($coordX+228, $coordY+105, $coordX+267, $coordY+105, $style = $style6);
        $oPDF->Line($coordX+228, $coordY+110, $coordX+267, $coordY+110, $style = $style6);
        $oPDF->Line($coordX+228, $coordY+115, $coordX+267, $coordY+115, $style = $style6);
        $oPDF->Line($coordX+228, $coordY+120, $coordX+267, $coordY+120, $style = $style6);
        $oPDF->Line($coordX+228, $coordY+125, $coordX+267, $coordY+125, $style = $style6);
        $oPDF->Line($coordX+228, $coordY+130, $coordX+267, $coordY+130, $style = $style6);
        $oPDF->Line($coordX+228, $coordY+135, $coordX+267, $coordY+135, $style = $style6);
        $oPDF->Line($coordX+228, $coordY+140, $coordX+267, $coordY+140, $style = $style6);
        $oPDF->Line($coordX+228, $coordY+145, $coordX+267, $coordY+145, $style = $style6);
        //---<< END
        
        //footer box.
        //--->> LEFT
        $oPDF->Line($coordX, $coordY+147, $coordX, $coordY+177, $style = $style6);// side line left.
        $oPDF->Line($coordX+7, $coordY+147, $coordX+7, $coordY+152, $style = $style6); // side line 11
        $oPDF->Line($coordX+19, $coordY+152, $coordX+19, $coordY+177, $style = $style6);
        $oPDF->Line($coordX+48, $coordY+152, $coordX+48, $coordY+177, $style = $style6);
        $oPDF->Line($coordX+81, $coordY+152, $coordX+81, $coordY+177, $style = $style6);
        $oPDF->Line($coordX+105, $coordY+152, $coordX+105, $coordY+177, $style = $style6);
        $oPDF->Line($coordX+127, $coordY+147, $coordX+127, $coordY+177, $style = $style6);// side line right
        
        $oPDF->Line($coordX, $coordY+147, $coordX+127, $coordY+147, $style = $style6);
        $oPDF->Line($coordX, $coordY+152, $coordX+127, $coordY+152, $style = $style6);
        $oPDF->Line($coordX, $coordY+160, $coordX+127, $coordY+160, $style = $style6);
        $oPDF->Line($coordX, $coordY+177, $coordX+127, $coordY+177, $style = $style6);
        //---<< END
        
        //--->> RIGHT
        $oPDF->Line($coordX+128, $coordY+147, $coordX+187, $coordY+147, $style = $style6);
        $oPDF->Line($coordX+128, $coordY+160, $coordX+187, $coordY+160, $style = $style6);
        $oPDF->Line($coordX+128, $coordY+177, $coordX+187, $coordY+177, $style = $style6);
        $oPDF->Line($coordX+128, $coordY+147, $coordX+128, $coordY+177, $style = $style6);// side line 
        $oPDF->Line($coordX+128, $coordY+152, $coordX+133, $coordY+152, $style = $style6);//small box 12
        $oPDF->Line($coordX+133, $coordY+147, $coordX+133, $coordY+152, $style = $style6);//small box 12
        $oPDF->Line($coordX+187, $coordY+147, $coordX+187, $coordY+177, $style = $style6);// side line
        
        $oPDF->Line($coordX+188, $coordY+147, $coordX+188, $coordY+177, $style = $style6);// side line
        $oPDF->Line($coordX+207.5, $coordY+147, $coordX+207.5, $coordY+154.5, $style = $style6);//side line
        $oPDF->Line($coordX+227, $coordY+147, $coordX+227, $coordY+177, $style = $style6);// side line
        $oPDF->Line($coordX+207.5, $coordY+162, $coordX+207.5, $coordY+169.5, $style = $style6);//side line
        $oPDF->Line($coordX+188, $coordY+147, $coordX+227, $coordY+147, $style = $style6);
        $oPDF->Line($coordX+188, $coordY+154.5, $coordX+227, $coordY+154.5, $style = $style6);
        $oPDF->Line($coordX+188, $coordY+162, $coordX+227, $coordY+162, $style = $style6);
        $oPDF->Line($coordX+188, $coordY+169.5, $coordX+227, $coordY+169.5, $style = $style6);
        $oPDF->Line($coordX+188, $coordY+177, $coordX+227, $coordY+177, $style = $style6);
        
        $oPDF->Line($coordX+228, $coordY+147, $coordX+228, $coordY+182, $style = $style6);// side line
        $oPDF->Line($coordX+228, $coordY+152, $coordX+233, $coordY+152, $style = $style6);//small box 13
        $oPDF->Line($coordX+233, $coordY+147, $coordX+233, $coordY+152, $style = $style6);//small box 13
        $oPDF->Line($coordX+267, $coordY+147, $coordX+267, $coordY+177, $style = $style6);// side line
        $oPDF->Line($coordX+228, $coordY+147, $coordX+267, $coordY+147, $style = $style6);
        $oPDF->Line($coordX+228, $coordY+155.75, $coordX+267, $coordY+155.75, $style = $style6);
        $oPDF->Line($coordX+228, $coordY+164.5, $coordX+267, $coordY+164.5, $style = $style6);
        $oPDF->Line($coordX+228, $coordY+173.25, $coordX+267, $coordY+173.25, $style = $style6);
        $oPDF->Line($coordX+228, $coordY+177, $coordX+267, $coordY+177, $style = $style6);
        $oPDF->Line($coordX+228, $coordY+182, $coordX+233, $coordY+182, $style = $style6);//small box 14
        $oPDF->Line($coordX+233, $coordY+177, $coordX+233, $coordY+182, $style = $style6);//small box 14
        //---<< END
        //---------------------------------END-------------------------------------------<<
        
        //Input
//		@note this image is the send icon in the payslip inalis ko muna para bukas...
        $oPDF->Image(SYSCONFIG_ROOT_PATH2.SYSCONFIG_DEFAULT_IMAGES_INCTEMP.'icons/edited/phic_logo.jpg',$coordX+2,$coordY+1,8,13, '', '', '', false, 300,'L');
        
        //number
        $oPDF->SetFont('helvetica', 'B', '');
        $oPDF->SetXY($coordX+1, $coordY+14.5);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'1',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+1, $coordY+34.5);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'2',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+128, $coordY+34.5);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'3',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+177, $coordY+34.5);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'4',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+228, $coordY+34.5);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'5',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+1.5, $coordY+55.5);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'6',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+128.5, $coordY+54.5);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'7',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+177.5, $coordY+54.5);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'8',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+189, $coordY+54.5);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'9',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+227.5, $coordY+54.5);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'10',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+.5, $coordY+146.5);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'11',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+127.5, $coordY+146.5);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'12',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+227.5, $coordY+146.5);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'13',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+227.5, $coordY+176.5);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'14',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        //END
        
        $oPDF->SetXY($coordX+12, $coordY+2);
        $oPDF->SetFont('helvetica', 'B', '19');
        $oPDF->MultiCell($oPDF->getPageWidth(),3,'RF-1',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+10, $coordY+9);
        
        $oPDF->SetFont('helvetica', '', '6');
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'REVISED JAN 2008',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+32, $coordY+3);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'PHILIPPINE HEALTH INSURANCE CORPORATION',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+184, $coordY+40);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'REGULAR RF-1',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+184, $coordY+44);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'ADDITION TO PREVIOUS RF-1',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+184, $coordY+48);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'DEDUCTION TO PREVIOUS RF-1',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+80, $coordY+177);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'PLEASE READ INSTRUCTION (FOR EACH NUMBERED BOX)AT THE BACK BEFORE ACCOMPLISHING THIS FORM',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        
        $oPDF->SetFont('helvetica', 'I', '6');
        $oPDF->SetXY($coordX+32, $coordY);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'Republic of the Philippines',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        
        $oPDF->SetFont('helvetica', 'B', '9');
        $oPDF->SetXY($coordX+43, $coordY+8);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'EMPLOYER\'S REMITTANCE REPORT',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        
        $oPDF->SetFont('helvetica', '', '11');
        $oPDF->SetXY($coordX+165, $coordY+.5);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'F O R     P H I L H E A L T H     U S E',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        
        $oPDF->SetFont('helvetica', '', '8.5');
        $oPDF->SetXY($coordX+128, $coordY+8);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'Date Received:',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+216, $coordY+8);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'Action Taken:',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+135, $coordY+14);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'By:',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+170, $coordY+23);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'_______________________________',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        
        $oPDF->SetFont('helvetica', '', '8');
        $oPDF->SetXY($coordX+178, $coordY+27);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'Signature Over Printed Name',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+18.5, $coordY+63.5);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'SURNAME',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+57, $coordY+63.5);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'GIVEN NAME',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+97, $coordY+63.5);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'MIDDLE NAME',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+195, $coordY+65);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'PS',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+214, $coordY+65);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'ES',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+241, $coordY+65);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'STATUS',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+235, $coordY+44);
        $oPDF->MultiCell(25, 3,$str2,0,'C',1, 0, 0, 0, TRUE, 0, TRUE);
        
        $oPDF->SetFont('helvetica', 'B', '8');
        $oPDF->SetXY($coordX+10, $coordY+20);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'PHILHEALTH NO.',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+45, $coordY+20);
		$oPDF->MultiCell($oPDF->getPageWidth(), 3,$compphicno,0,'L',1, 0, 0, 0, TRUE, 0, TRUE);//phic no
        $oPDF->SetXY($coordX+10, $coordY+26);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'EMPLOYER TIN',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+45, $coordY+26);
		$oPDF->MultiCell($oPDF->getPageWidth(), 3,$comptinno,0,'L',1, 0, 0, 0, TRUE, 0, TRUE);//phic no
        $oPDF->SetXY($coordX+48, $coordY+55.5);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'NAME OF EMPLOYEE/S',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX, $coordY+63.5);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'NO.',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+140, $coordY+61);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'PHILHEALTH NO.',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+31, $coordY+147);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'ACKNOWLEDGEMENT RECEIPT (ME-5/POR/OR/PAR)',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        
        $oPDF->SetFont('helvetica', '', '7');
        $oPDF->SetXY($coordX+8, $coordY+36);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'COMPLETE EMPLOYER NAME',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+50, $coordY+36);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,strtoupper($compname),0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+8, $coordY+40);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'COMPLETE MAILING ADDRESS',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+50, $coordY+40);
        $oPDF->MultiCell(60, 3,$compadds,0,'L',3, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+8, $coordY+49);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'TELEPHONE NO.',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+30, $coordY+49);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,$comptelno,0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+140, $coordY+40);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'PRIVATE',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+140, $coordY+44);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'GOVERNMENT',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+140, $coordY+48);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'HOUSEHOLD',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+.5, $coordY+152);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'APPLICABLE',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+3, $coordY+155);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'PERIOD',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+20.5, $coordY+153);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'REMITTED AMOUNT',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+51, $coordY+152);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'ACKNOWLEGEMENT',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+56, $coordY+155);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'RECEIPT NO.',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+83, $coordY+152);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'TRANSACTION',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+89, $coordY+155);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'DATE',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+111, $coordY+152);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'NO. OF',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+108, $coordY+155);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'EMPLOYEES',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        
        $oPDF->SetFont('helvetica', 'B', '7');
        $oPDF->SetXY($coordX+135, $coordY+35);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'EMPLOYER TYPE',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+184, $coordY+35);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'REPORT TYPE',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+235, $coordY+35);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'APPLICABLE PERIOD',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+235, $coordY+147);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'CERTIFIED CORRERCT',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        
        $comp_type = $branch_details['comptype_id'];
            if($comp_type=='2'){
                $oPDF->SetXY($coordX+136.7, $coordY+44);
                $oPDF->MultiCell($oPDF->getPageWidth(), 3,'X',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
            }else if($comp_type=='3'){
            	$oPDF->SetXY($coordX+136.7, $coordY+48);
                $oPDF->MultiCell($oPDF->getPageWidth(), 3,'X',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
            }else{
            	$oPDF->SetXY($coordX+136.7, $coordY+40);
                $oPDF->MultiCell($oPDF->getPageWidth(), 3,'X',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
            }
        $oPDF->SetXY($coordX+180.7, $coordY+40);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'X',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        
        $oPDF->SetFont('helvetica', 'B', '7.5');
        $oPDF->SetXY($coordX+199, $coordY+56);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'NHIP PREMIUM',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+198.5, $coordY+59);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'CONTRIBUTION',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+236, $coordY+55);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'MEMBER STATUS',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+136, $coordY+149);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'SUBTOTAL                         (PS + ES)',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+137, $coordY+163);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'GRAND TOTAL                 (PS + ES)',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        
        $oPDF->SetFont('helvetica', '', '5');
        $oPDF->SetXY($coordX+235.5, $coordY+59);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'S-Separated, NE-No Earnings,',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+242, $coordY+61);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'NH-Newly Hired',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+177, $coordY+60);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'MONTHLY',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+178, $coordY+62);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'SALARY',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+177, $coordY+64);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'BRACKET',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+179, $coordY+66);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'(MSB)',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+231, $coordY+155);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'SIGNATURE OVER PRINTED NAME',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+236, $coordY+164);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'OFFICIAL DESIGNATION',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+244, $coordY+173);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'DATE',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+136, $coordY+154);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'(To be accomplished on every page)',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+137, $coordY+168);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'(To be accomplished on last page)',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        
        //small box
        $oPDF->Line($coordX+137, $coordY+41, $coordX+140, $coordY+41, $style = $style6);//small box private
        $oPDF->Line($coordX+137, $coordY+44, $coordX+140, $coordY+44, $style = $style6);//small box private
        $oPDF->Line($coordX+140, $coordY+41, $coordX+140, $coordY+44, $style = $style6);//small box private
        $oPDF->Line($coordX+137, $coordY+41, $coordX+137, $coordY+44, $style = $style6);//small box private
        $oPDF->Line($coordX+137, $coordY+45, $coordX+140, $coordY+45, $style = $style6);//small box GOVERNMENT
        $oPDF->Line($coordX+137, $coordY+48, $coordX+140, $coordY+48, $style = $style6);//small box GOVERNMENT
        $oPDF->Line($coordX+140, $coordY+45, $coordX+140, $coordY+48, $style = $style6);//small box GOVERNMENT
        $oPDF->Line($coordX+137, $coordY+45, $coordX+137, $coordY+48, $style = $style6);//small box GOVERNMENT
        $oPDF->Line($coordX+137, $coordY+49, $coordX+140, $coordY+49, $style = $style6);//small box HOUSEHOLD
        $oPDF->Line($coordX+137, $coordY+52, $coordX+140, $coordY+52, $style = $style6);//small box HOUSEHOLD
        $oPDF->Line($coordX+140, $coordY+49, $coordX+140, $coordY+52, $style = $style6);//small box HOUSEHOLD
        $oPDF->Line($coordX+137, $coordY+49, $coordX+137, $coordY+52, $style = $style6);//small box HOUSEHOLD
        $oPDF->Line($coordX+181, $coordY+41, $coordX+184, $coordY+41, $style = $style6);//small box REGULAR RF-1
        $oPDF->Line($coordX+181, $coordY+44, $coordX+184, $coordY+44, $style = $style6);//small box REGULAR RF-1
        $oPDF->Line($coordX+184, $coordY+41, $coordX+184, $coordY+44, $style = $style6);//small box REGULAR RF-1
        $oPDF->Line($coordX+181, $coordY+41, $coordX+181, $coordY+44, $style = $style6);//small box REGULAR RF-1
        $oPDF->Line($coordX+181, $coordY+45, $coordX+184, $coordY+45, $style = $style6);//small box ADDITION TO PREVIOUS RF-1
        $oPDF->Line($coordX+181, $coordY+48, $coordX+184, $coordY+48, $style = $style6);//small box ADDITION TO PREVIOUS RF-1
        $oPDF->Line($coordX+184, $coordY+45, $coordX+184, $coordY+48, $style = $style6);//small box ADDITION TO PREVIOUS RF-1
        $oPDF->Line($coordX+181, $coordY+45, $coordX+181, $coordY+48, $style = $style6);//small box ADDITION TO PREVIOUS RF-1
        $oPDF->Line($coordX+181, $coordY+49, $coordX+184, $coordY+49, $style = $style6);//small box DEDUCTION TO PREVIOUS RF-1
        $oPDF->Line($coordX+181, $coordY+52, $coordX+184, $coordY+52, $style = $style6);//small box DEDUCTION TO PREVIOUS RF-1
        $oPDF->Line($coordX+184, $coordY+49, $coordX+184, $coordY+52, $style = $style6);//small box DEDUCTION TO PREVIOUS RF-1
        $oPDF->Line($coordX+181, $coordY+49, $coordX+181, $coordY+52, $style = $style6);//small box DEDUCTION TO PREVIOUS RF-1
        $oPDF->Line($coordX+46, $coordY+62, $coordX+46, $coordY+145, $style = $style6);//line surname
        $oPDF->Line($coordX+87, $coordY+62, $coordX+87, $coordY+145, $style = $style6);//line middle name

        $eree_cont = 0;
        $subtotal = 0;
        $total_ec = 0;
        $eree = 0;
        $ec = 0;
        $total = 0;
//		
//     			$ctr_ = 16;
//				for ($a=1;$a<$ctr_;$a++) {
//					 $oPDF->SetFont('helvetica', '', '7');
//	                 $oPDF->SetXY($coordX+1, $coordY+70);
//	                 $oPDF->MultiCell(5, 3,$a,0,'R');
//	                 $coordY+=$oPDF->getFontSize()+2.5;
//	            }
            
        
        $coordY = 85;
        if(count($arrData)>0){
            $ctr = 0;
            $ctrEmp = 1;
            $countemp = count($arrData);
            foreach ($arrData as $key => $val) {
            $phicno = $val['emp_info']['pi_phic'][0].$val['emp_info']['pi_phic'][1].$val['emp_info']['pi_phic'][2];
            IF($phicno == ""){
            	$phicno_ = $val['emp_info']['pi_sss'];
            }else{
            	$phicno_ = $phicno;
            }
            
            $eree = $val['sss']['ppe_amount_employer']+$val['sss']['ppe_amount'];
            $ec = $val['sss']['ppe_units'];
            $total = $val['sss']['ppe_amount_employer']+$val['sss']['ppe_amount']+$val['sss']['ppe_units'];
            $ctr++;
                // reset coordinate X value every loop
//                $coordX = 10;
				
                if($coordY==85){
					$oPDF->SetFont('helvetica', '', '7');
					//ACKNOWLEDGEMENT RECEIPT (ME-5/POR/OR/PAR)
					$oPDF->SetXY($coordX, $coordY+96);
                    $oPDF->MultiCell(16, 3,$str2month,0,'C');
                    $oPDF->SetXY($coordX+25, $coordY+96);
                    $oPDF->MultiCell(16, 3,$gData['amountpaid'],0,'C');
                    $oPDF->SetXY($coordX+52, $coordY+96);
                    $oPDF->MultiCell(25, 3,$gData['receiptno'],0,'C');
                    $oPDF->SetXY($coordX+85, $coordY+96);
                    $oPDF->MultiCell(16, 3,$gData['trasdate'],0,'C');
                    $oPDF->SetXY($coordX+108, $coordY+96);
                    $oPDF->MultiCell(16, 3,$countemp,0,'C');
                    
					$oPDF->SetXY($coordX+222, $coordY+82);
                    $oPDF->MultiCell(50, 3,$gData['signatoryby'],0,'C');
					$oPDF->SetXY($coordX+222, $coordY+90.5);
                    $oPDF->MultiCell(50, 3,$gData['position'],0,'C');
                    $oPDF->SetXY($coordX+240, $coordY+99);
                    $oPDF->MultiCell(30, 3,date('m-d-Y'),0,'L');
                    $oPDF->SetXY($coordX+232, $coordY+107);
                 	$oPDF->MultiCell(40,3,'PAGE  '.$oPDF->PageNo().'  OF  {nb}  PAGES',0,'C');
                } 
                
				 $oPDF->SetFont('helvetica', '', '7');
	             $oPDF->SetXY($coordX, $coordY);
	             $oPDF->MultiCell(7, 3,$ctrEmp,0,'C');
                 $oPDF->SetXY($coordX+7, $coordY);
                 $oPDF->MultiCell(35, 3, $val['emp_info']['pi_lname'],0,'L');
                 $oPDF->SetXY($coordX+46, $coordY);
                 $oPDF->MultiCell(35, 3, $val['emp_info']['pi_fname'],0,'L');
                 $oPDF->SetXY($coordX+87, $coordY);
                 $oPDF->MultiCell(33, 3, $val['emp_info']['pi_mname_'],0,'L');
                 
                 $oPDF->SetXY($coordX+140, $coordY);
                 $oPDF->MultiCell(33, 3,$phicno_,0,'L');
//                 $oPDF->SetXY($coordX+137.5, $coordY);
//                 $oPDF->MultiCell(33, 3, $val['emp_info']['pi_phic'][1],0,'L');
//                 $oPDF->SetXY($coordX+172.5, $coordY);
//                 $oPDF->MultiCell(33, 3, $val['emp_info']['pi_phic'][2],0,'L');

                 $oPDF->SetXY($coordX+174.5, $coordY);
                 $oPDF->MultiCell(10, 3, $val['sss']['msb'],0,'R');
                 $oPDF->SetXY($coordX+195, $coordY);
                 $oPDF->MultiCell(12, 3, number_format($val['sss']['ppe_amount'],2),0,'R');
                 $oPDF->SetXY($coordX+214, $coordY);
                 $oPDF->MultiCell(12, 3, number_format($val['sss']['ppe_amount_employer'],2),0,'R');
                 if(number_format($val['sss']['ppe_amount_employer'],2)=='0.00'){
	                 $oPDF->SetXY($coordX+235, $coordY);
	                 $oPDF->MultiCell(25, 3, "NE",0,'C');
                 }else{
                 	$oPDF->SetXY($coordX+235, $coordY);
                 	$oPDF->MultiCell(25, 3, $val['sss']['remarks'],0,'C');
                 }
                 
                 // check if the coordinate Y are exceeding the limit of 250
                // if yes create / add new pdf page
                if($coordY  > 150){
                	$oPDF->SetXY($coordX+187, 163.5);
             		$oPDF->MultiCell(20, 3, number_format($empcon,2),0,'R');
		            $oPDF->SetXY($coordX+206, 163.5);
		            $oPDF->MultiCell(20, 3, number_format($emrcon,2),0,'R');
		            $oPDF->SetXY($coordX+193, 171);
		            $oPDF->MultiCell(30, 3, number_format($subtotal,2),0,'C');
		            
		            $eree_cont = 0;
                	$empcon = 0;
	                $emrcon = 0;
	                $subtotal = 0;
	                $total_ec = 0;
	                
	                $ctr_ = 16;
					for ($a=1;$a<$ctr_;$a++) {
						 $oPDF->SetFont('helvetica', '', '7');
		                 $oPDF->SetXY($coordX+1, $coordY+70);
		                 $oPDF->MultiCell(5, 3,$a,0,'R');
		                 $coordY+=$oPDF->getFontSize()+2.5;
		            }
	                
                    $coordX = 7;
        			$coordY = 15;
                    $oPDF->AddPage();
                    
                    $oPDF->SetFont('helvetica', '', '9');
        $oPDF->Line($coordX, $coordY, $coordX, $coordY+145, $style = $style6); //left side line
        $oPDF->Line($coordX+127, $coordY, $coordX+127, $coordY+145, $style = $style6); //right side line
        $oPDF->Line($coordX+7, $coordY+55, $coordX+7, $coordY+145, $style = $style6); // from name employee line
        
        $oPDF->Line($coordX, $coordY, $coordX+127, $coordY, $style = $style6);
        $oPDF->Line($coordX, $coordY+15, $coordX+127, $coordY+15, $style = $style6);
        $oPDF->Line($coordX, $coordY+35, $coordX+127, $coordY+35, $style = $style6);
        
        $oPDF->Line($coordX+32, $coordY, $coordX+32, $coordY+15, $style = $style6);
        $oPDF->Line($coordX, $coordY+20, $coordX+5, $coordY+20, $style = $style6);//small box 1
        $oPDF->Line($coordX+5, $coordY+15, $coordX+5, $coordY+20, $style = $style6);//small box 1
        $oPDF->Line($coordX, $coordY+40, $coordX+5, $coordY+40, $style = $style6);//small box 2
        $oPDF->Line($coordX+5, $coordY+35, $coordX+5, $coordY+40, $style = $style6);//small box 2
        
        $oPDF->Line($coordX, $coordY+55, $coordX+127, $coordY+55, $style = $style6);
        $oPDF->Line($coordX, $coordY+62, $coordX+127, $coordY+62, $style = $style6);
        $oPDF->Line($coordX, $coordY+70, $coordX+127, $coordY+70, $style = $style6);
        //--->> END
        
        //--->> RIGHT
        $oPDF->Line($coordX+127, $coordY, $coordX+267, $coordY, $style = $style5);
        $oPDF->Line($coordX+127, $coordY+7.5, $coordX+267, $coordY+7.5, $style = $style5);
        $oPDF->Line($coordX+127, $coordY+34.8, $coordX+267, $coordY+34.8, $style = $style5);
        $oPDF->Line($coordX+127, $coordY+35, $coordX+267, $coordY+35, $style = $style6);
        $oPDF->Line($coordX+127, $coordY+40, $coordX+132, $coordY+40, $style = $style6);//small box 3
        $oPDF->Line($coordX+132, $coordY+35, $coordX+132, $coordY+40, $style = $style6);//small box 3
        $oPDF->Line($coordX+267, $coordY, $coordX+267, $coordY+35, $style = $style5);//side line
        $oPDF->Line($coordX+127.2, $coordY, $coordX+127.2, $coordY+35, $style = $style5);//side line
        $oPDF->Line($coordX+127, $coordY+55, $coordX+267, $coordY+55, $style = $style6);
        $oPDF->Line($coordX+267, $coordY+35, $coordX+267, $coordY+145, $style = $style6);//side right line
        $oPDF->Line($coordX+227, $coordY+35, $coordX+227, $coordY+145, $style = $style6);//side line
        $oPDF->Line($coordX+227, $coordY+40, $coordX+232, $coordY+40, $style = $style6);//small box 5
        $oPDF->Line($coordX+232, $coordY+35, $coordX+232, $coordY+40, $style = $style6);//small box 5
        $oPDF->Line($coordX+176, $coordY+35, $coordX+176, $coordY+145, $style = $style6);//side line
        $oPDF->Line($coordX+176, $coordY+40, $coordX+181, $coordY+40, $style = $style6);//small box 4
        $oPDF->Line($coordX+181, $coordY+35, $coordX+181, $coordY+40, $style = $style6);//small box 4
        $oPDF->Line($coordX+128, $coordY+55, $coordX+128, $coordY+145, $style = $style6);//side line
        
        $oPDF->Line($coordX+128, $coordY+60, $coordX+133, $coordY+60, $style = $style6);//small box 7
        $oPDF->Line($coordX+133, $coordY+55, $coordX+133, $coordY+60, $style = $style6);//small box 7
        $oPDF->Line($coordX+177, $coordY+55, $coordX+177, $coordY+145, $style = $style6);//side line
        $oPDF->Line($coordX+177, $coordY+60, $coordX+182, $coordY+60, $style = $style6);//small box 8
        $oPDF->Line($coordX+182, $coordY+55, $coordX+182, $coordY+60, $style = $style6);//small box 8
        $oPDF->Line($coordX+187, $coordY+55, $coordX+187, $coordY+145, $style = $style6);//side line
        $oPDF->Line($coordX+188, $coordY+55, $coordX+188, $coordY+145, $style = $style6);//side line
        $oPDF->Line($coordX+188, $coordY+60, $coordX+193, $coordY+60, $style = $style6);//small box 9
        $oPDF->Line($coordX+193, $coordY+55, $coordX+193, $coordY+60, $style = $style6);//small box 9
        $oPDF->Line($coordX+207.5, $coordY+65, $coordX+207.5, $coordY+145, $style = $style6);//side line
        $oPDF->Line($coordX+228, $coordY+55, $coordX+228, $coordY+145, $style = $style6);//side line
        $oPDF->Line($coordX+228, $coordY+60, $coordX+233, $coordY+60, $style = $style6);//small box 10
        $oPDF->Line($coordX+233, $coordY+55, $coordX+233, $coordY+60, $style = $style6);//small box 10
        
        $oPDF->Line($coordX+128, $coordY+70, $coordX+176, $coordY+70, $style = $style6);
        $oPDF->Line($coordX+177, $coordY+70, $coordX+187, $coordY+70, $style = $style6);
        $oPDF->Line($coordX+188, $coordY+65, $coordX+227, $coordY+65, $style = $style6);
        $oPDF->Line($coordX+188, $coordY+70, $coordX+227, $coordY+70, $style = $style6);
        $oPDF->Line($coordX+228, $coordY+65, $coordX+267, $coordY+65, $style = $style6);
        $oPDF->Line($coordX+228, $coordY+70, $coordX+267, $coordY+70, $style = $style6);
        //--->> END
        
        //Employee List Boxs
        //--->> LEFT
        $oPDF->Line($coordX, $coordY+75, $coordX+127, $coordY+75, $style = $style6);
        $oPDF->Line($coordX, $coordY+80, $coordX+127, $coordY+80, $style = $style6);
        $oPDF->Line($coordX, $coordY+85, $coordX+127, $coordY+85, $style = $style6);
        $oPDF->Line($coordX, $coordY+90, $coordX+127, $coordY+90, $style = $style6);
        $oPDF->Line($coordX, $coordY+95, $coordX+127, $coordY+95, $style = $style6);
        $oPDF->Line($coordX, $coordY+100, $coordX+127, $coordY+100, $style = $style6);
        $oPDF->Line($coordX, $coordY+105, $coordX+127, $coordY+105, $style = $style6);
        $oPDF->Line($coordX, $coordY+110, $coordX+127, $coordY+110, $style = $style6);
        $oPDF->Line($coordX, $coordY+115, $coordX+127, $coordY+115, $style = $style6);
        $oPDF->Line($coordX, $coordY+120, $coordX+127, $coordY+120, $style = $style6);
        $oPDF->Line($coordX, $coordY+125, $coordX+127, $coordY+125, $style = $style6);
        $oPDF->Line($coordX, $coordY+130, $coordX+127, $coordY+130, $style = $style6);
        $oPDF->Line($coordX, $coordY+135, $coordX+127, $coordY+135, $style = $style6);
        $oPDF->Line($coordX, $coordY+140, $coordX+127, $coordY+140, $style = $style6);
        $oPDF->Line($coordX, $coordY+145, $coordX+127, $coordY+145, $style = $style6);
        //---<< END
        
        //--->> RIGHT
        $oPDF->Line($coordX+128, $coordY+145, $coordX+176, $coordY+145, $style = $style6);
        $oPDF->Line($coordX+128, $coordY+75, $coordX+176, $coordY+75, $style = $style6);
        $oPDF->Line($coordX+128, $coordY+80, $coordX+176, $coordY+80, $style = $style6);
        $oPDF->Line($coordX+128, $coordY+85, $coordX+176, $coordY+85, $style = $style6);
        $oPDF->Line($coordX+128, $coordY+90, $coordX+176, $coordY+90, $style = $style6);
        $oPDF->Line($coordX+128, $coordY+95, $coordX+176, $coordY+95, $style = $style6);
        $oPDF->Line($coordX+128, $coordY+100, $coordX+176, $coordY+100, $style = $style6);
        $oPDF->Line($coordX+128, $coordY+105, $coordX+176, $coordY+105, $style = $style6);
        $oPDF->Line($coordX+128, $coordY+110, $coordX+176, $coordY+110, $style = $style6);
        $oPDF->Line($coordX+128, $coordY+115, $coordX+176, $coordY+115, $style = $style6);
        $oPDF->Line($coordX+128, $coordY+120, $coordX+176, $coordY+120, $style = $style6);
        $oPDF->Line($coordX+128, $coordY+125, $coordX+176, $coordY+125, $style = $style6);
        $oPDF->Line($coordX+128, $coordY+130, $coordX+176, $coordY+130, $style = $style6);
        $oPDF->Line($coordX+128, $coordY+135, $coordX+176, $coordY+135, $style = $style6);
        $oPDF->Line($coordX+128, $coordY+140, $coordX+176, $coordY+140, $style = $style6);
        
        $oPDF->Line($coordX+177, $coordY+75, $coordX+187, $coordY+75, $style = $style6);
        $oPDF->Line($coordX+177, $coordY+80, $coordX+187, $coordY+80, $style = $style6);
        $oPDF->Line($coordX+177, $coordY+85, $coordX+187, $coordY+85, $style = $style6);
        $oPDF->Line($coordX+177, $coordY+90, $coordX+187, $coordY+90, $style = $style6);
        $oPDF->Line($coordX+177, $coordY+95, $coordX+187, $coordY+95, $style = $style6);
        $oPDF->Line($coordX+177, $coordY+100, $coordX+187, $coordY+100, $style = $style6);
        $oPDF->Line($coordX+177, $coordY+105, $coordX+187, $coordY+105, $style = $style6);
        $oPDF->Line($coordX+177, $coordY+110, $coordX+187, $coordY+110, $style = $style6);
        $oPDF->Line($coordX+177, $coordY+115, $coordX+187, $coordY+115, $style = $style6);
        $oPDF->Line($coordX+177, $coordY+120, $coordX+187, $coordY+120, $style = $style6);
        $oPDF->Line($coordX+177, $coordY+125, $coordX+187, $coordY+125, $style = $style6);
        $oPDF->Line($coordX+177, $coordY+130, $coordX+187, $coordY+130, $style = $style6);
        $oPDF->Line($coordX+177, $coordY+135, $coordX+187, $coordY+135, $style = $style6);
        $oPDF->Line($coordX+177, $coordY+140, $coordX+187, $coordY+140, $style = $style6);
        $oPDF->Line($coordX+177, $coordY+145, $coordX+187, $coordY+145, $style = $style6);
        
        $oPDF->Line($coordX+188, $coordY+75, $coordX+227, $coordY+75, $style = $style6);
        $oPDF->Line($coordX+188, $coordY+80, $coordX+227, $coordY+80, $style = $style6);
        $oPDF->Line($coordX+188, $coordY+85, $coordX+227, $coordY+85, $style = $style6);
        $oPDF->Line($coordX+188, $coordY+90, $coordX+227, $coordY+90, $style = $style6);
        $oPDF->Line($coordX+188, $coordY+95, $coordX+227, $coordY+95, $style = $style6);
        $oPDF->Line($coordX+188, $coordY+100, $coordX+227, $coordY+100, $style = $style6);
        $oPDF->Line($coordX+188, $coordY+105, $coordX+227, $coordY+105, $style = $style6);
        $oPDF->Line($coordX+188, $coordY+110, $coordX+227, $coordY+110, $style = $style6);
        $oPDF->Line($coordX+188, $coordY+115, $coordX+227, $coordY+115, $style = $style6);
        $oPDF->Line($coordX+188, $coordY+120, $coordX+227, $coordY+120, $style = $style6);
        $oPDF->Line($coordX+188, $coordY+125, $coordX+227, $coordY+125, $style = $style6);
        $oPDF->Line($coordX+188, $coordY+130, $coordX+227, $coordY+130, $style = $style6);
        $oPDF->Line($coordX+188, $coordY+135, $coordX+227, $coordY+135, $style = $style6);
        $oPDF->Line($coordX+188, $coordY+140, $coordX+227, $coordY+140, $style = $style6);
        $oPDF->Line($coordX+188, $coordY+145, $coordX+227, $coordY+145, $style = $style6);
        
        $oPDF->Line($coordX+228, $coordY+75, $coordX+267, $coordY+75, $style = $style6);
        $oPDF->Line($coordX+228, $coordY+80, $coordX+267, $coordY+80, $style = $style6);
        $oPDF->Line($coordX+228, $coordY+85, $coordX+267, $coordY+85, $style = $style6);
        $oPDF->Line($coordX+228, $coordY+90, $coordX+267, $coordY+90, $style = $style6);
        $oPDF->Line($coordX+228, $coordY+95, $coordX+267, $coordY+95, $style = $style6);
        $oPDF->Line($coordX+228, $coordY+100, $coordX+267, $coordY+100, $style = $style6);
        $oPDF->Line($coordX+228, $coordY+105, $coordX+267, $coordY+105, $style = $style6);
        $oPDF->Line($coordX+228, $coordY+110, $coordX+267, $coordY+110, $style = $style6);
        $oPDF->Line($coordX+228, $coordY+115, $coordX+267, $coordY+115, $style = $style6);
        $oPDF->Line($coordX+228, $coordY+120, $coordX+267, $coordY+120, $style = $style6);
        $oPDF->Line($coordX+228, $coordY+125, $coordX+267, $coordY+125, $style = $style6);
        $oPDF->Line($coordX+228, $coordY+130, $coordX+267, $coordY+130, $style = $style6);
        $oPDF->Line($coordX+228, $coordY+135, $coordX+267, $coordY+135, $style = $style6);
        $oPDF->Line($coordX+228, $coordY+140, $coordX+267, $coordY+140, $style = $style6);
        $oPDF->Line($coordX+228, $coordY+145, $coordX+267, $coordY+145, $style = $style6);
        //---<< END
        
        //footer box.
        //--->> LEFT
        $oPDF->Line($coordX, $coordY+147, $coordX, $coordY+177, $style = $style6);// side line left.
        $oPDF->Line($coordX+7, $coordY+147, $coordX+7, $coordY+152, $style = $style6); // side line 11
        $oPDF->Line($coordX+19, $coordY+152, $coordX+19, $coordY+177, $style = $style6);
        $oPDF->Line($coordX+48, $coordY+152, $coordX+48, $coordY+177, $style = $style6);
        $oPDF->Line($coordX+81, $coordY+152, $coordX+81, $coordY+177, $style = $style6);
        $oPDF->Line($coordX+105, $coordY+152, $coordX+105, $coordY+177, $style = $style6);
        $oPDF->Line($coordX+127, $coordY+147, $coordX+127, $coordY+177, $style = $style6);// side line right
        
        $oPDF->Line($coordX, $coordY+147, $coordX+127, $coordY+147, $style = $style6);
        $oPDF->Line($coordX, $coordY+152, $coordX+127, $coordY+152, $style = $style6);
        $oPDF->Line($coordX, $coordY+160, $coordX+127, $coordY+160, $style = $style6);
        $oPDF->Line($coordX, $coordY+177, $coordX+127, $coordY+177, $style = $style6);
        //---<< END
        
        //--->> RIGHT
        $oPDF->Line($coordX+128, $coordY+147, $coordX+187, $coordY+147, $style = $style6);
        $oPDF->Line($coordX+128, $coordY+160, $coordX+187, $coordY+160, $style = $style6);
        $oPDF->Line($coordX+128, $coordY+177, $coordX+187, $coordY+177, $style = $style6);
        $oPDF->Line($coordX+128, $coordY+147, $coordX+128, $coordY+177, $style = $style6);// side line 
        $oPDF->Line($coordX+128, $coordY+152, $coordX+133, $coordY+152, $style = $style6);//small box 12
        $oPDF->Line($coordX+133, $coordY+147, $coordX+133, $coordY+152, $style = $style6);//small box 12
        $oPDF->Line($coordX+187, $coordY+147, $coordX+187, $coordY+177, $style = $style6);// side line
        
        $oPDF->Line($coordX+188, $coordY+147, $coordX+188, $coordY+177, $style = $style6);// side line
        $oPDF->Line($coordX+207.5, $coordY+147, $coordX+207.5, $coordY+154.5, $style = $style6);//side line
        $oPDF->Line($coordX+227, $coordY+147, $coordX+227, $coordY+177, $style = $style6);// side line
        $oPDF->Line($coordX+207.5, $coordY+162, $coordX+207.5, $coordY+169.5, $style = $style6);//side line
        $oPDF->Line($coordX+188, $coordY+147, $coordX+227, $coordY+147, $style = $style6);
        $oPDF->Line($coordX+188, $coordY+154.5, $coordX+227, $coordY+154.5, $style = $style6);
        $oPDF->Line($coordX+188, $coordY+162, $coordX+227, $coordY+162, $style = $style6);
        $oPDF->Line($coordX+188, $coordY+169.5, $coordX+227, $coordY+169.5, $style = $style6);
        $oPDF->Line($coordX+188, $coordY+177, $coordX+227, $coordY+177, $style = $style6);
        
        $oPDF->Line($coordX+228, $coordY+147, $coordX+228, $coordY+182, $style = $style6);// side line
        $oPDF->Line($coordX+228, $coordY+152, $coordX+233, $coordY+152, $style = $style6);//small box 13
        $oPDF->Line($coordX+233, $coordY+147, $coordX+233, $coordY+152, $style = $style6);//small box 13
        $oPDF->Line($coordX+267, $coordY+147, $coordX+267, $coordY+177, $style = $style6);// side line
        $oPDF->Line($coordX+228, $coordY+147, $coordX+267, $coordY+147, $style = $style6);
        $oPDF->Line($coordX+228, $coordY+155.75, $coordX+267, $coordY+155.75, $style = $style6);
        $oPDF->Line($coordX+228, $coordY+164.5, $coordX+267, $coordY+164.5, $style = $style6);
        $oPDF->Line($coordX+228, $coordY+173.25, $coordX+267, $coordY+173.25, $style = $style6);
        $oPDF->Line($coordX+228, $coordY+177, $coordX+267, $coordY+177, $style = $style6);
        $oPDF->Line($coordX+228, $coordY+182, $coordX+233, $coordY+182, $style = $style6);//small box 14
        $oPDF->Line($coordX+233, $coordY+177, $coordX+233, $coordY+182, $style = $style6);//small box 14
        //---<< END
        //---------------------------------END-------------------------------------------<<
        
        //Input
//		@note this image is the send icon in the payslip inalis ko muna para bukas...
        $oPDF->Image(SYSCONFIG_ROOT_PATH2.SYSCONFIG_DEFAULT_IMAGES_INCTEMP.'icons/edited/phic_logo.jpg',$coordX+2,$coordY+1,8,13, '', '', '', false, 300,'L');
        
        //number
        $oPDF->SetFont('helvetica', 'B', '');
        $oPDF->SetXY($coordX+1, $coordY+14.5);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'1',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+1, $coordY+34.5);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'2',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+128, $coordY+34.5);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'3',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+177, $coordY+34.5);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'4',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+228, $coordY+34.5);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'5',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+1.5, $coordY+55.5);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'6',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+128.5, $coordY+54.5);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'7',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+177.5, $coordY+54.5);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'8',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+189, $coordY+54.5);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'9',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+227.5, $coordY+54.5);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'10',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+.5, $coordY+146.5);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'11',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+127.5, $coordY+146.5);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'12',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+227.5, $coordY+146.5);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'13',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+227.5, $coordY+176.5);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'14',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        //END
        
        $oPDF->SetXY($coordX+12, $coordY+2);
        $oPDF->SetFont('helvetica', 'B', '19');
        $oPDF->MultiCell($oPDF->getPageWidth(),3,'RF-1',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+10, $coordY+9);
        
        $oPDF->SetFont('helvetica', '', '6');
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'REVISED JAN 2008',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+32, $coordY+3);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'PHILIPPINE HEALTH INSURANCE CORPORATION',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+184, $coordY+40);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'REGULAR RF-1',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+184, $coordY+44);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'ADDITION TO PREVIOUS RF-1',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+184, $coordY+48);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'DEDUCTION TO PREVIOUS RF-1',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+80, $coordY+177);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'PLEASE READ INSTRUCTION (FOR EACH NUMBERED BOX)AT THE BACK BEFORE ACCOMPLISHING THIS FORM',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        
        $oPDF->SetFont('helvetica', 'I', '6');
        $oPDF->SetXY($coordX+32, $coordY);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'Republic of the Philippines',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        
        $oPDF->SetFont('helvetica', 'B', '9');
        $oPDF->SetXY($coordX+43, $coordY+8);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'EMPLOYER\'S REMITTANCE REPORT',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        
        $oPDF->SetFont('helvetica', '', '11');
        $oPDF->SetXY($coordX+165, $coordY+.5);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'F O R     P H I L H E A L T H     U S E',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        
        $oPDF->SetFont('helvetica', '', '8.5');
        $oPDF->SetXY($coordX+128, $coordY+8);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'Date Received:',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+216, $coordY+8);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'Action Taken:',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+135, $coordY+14);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'By:',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+170, $coordY+23);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'_______________________________',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        
        $oPDF->SetFont('helvetica', '', '8');
        $oPDF->SetXY($coordX+178, $coordY+27);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'Signature Over Printed Name',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+18.5, $coordY+63.5);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'SURNAME',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+57, $coordY+63.5);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'GIVEN NAME',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+97, $coordY+63.5);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'MIDDLE NAME',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+195, $coordY+65);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'PS',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+214, $coordY+65);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'ES',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+241, $coordY+65);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'STATUS',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+235, $coordY+44);
        $oPDF->MultiCell(25, 3,$str2,0,'C',1, 0, 0, 0, TRUE, 0, TRUE);
        
        $oPDF->SetFont('helvetica', 'B', '8');
        $oPDF->SetXY($coordX+10, $coordY+20);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'PHILHEALTH NO.',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+45, $coordY+20);
		$oPDF->MultiCell($oPDF->getPageWidth(), 3,$branch_details['comp_phic'],0,'L',1, 0, 0, 0, TRUE, 0, TRUE);//phic no
        $oPDF->SetXY($coordX+10, $coordY+26);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'EMPLOYER TIN',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+45, $coordY+26);
		$oPDF->MultiCell($oPDF->getPageWidth(), 3,$branch_details['comp_tin'],0,'L',1, 0, 0, 0, TRUE, 0, TRUE);//phic no
        $oPDF->SetXY($coordX+48, $coordY+55.5);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'NAME OF EMPLOYEE/S',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX, $coordY+63.5);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'NO.',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+140, $coordY+61);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'PHILHEALTH NO.',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+31, $coordY+147);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'ACKNOWLEDGEMENT RECEIPT (ME-5/POR/OR/PAR)',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        
        $oPDF->SetFont('helvetica', '', '7');
        $oPDF->SetXY($coordX+8, $coordY+36);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'COMPLETE EMPLOYER NAME',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+50, $coordY+36);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,strtoupper($branch_details['comp_name']),0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+8, $coordY+40);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'COMPLETE MAILING ADDRESS',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+50, $coordY+40);
        $oPDF->MultiCell(60, 3,$branch_details['comp_add'],0,'L',3, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+8, $coordY+49);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'TELEPHONE NO.',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+30, $coordY+49);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,$branch_details['comp_tel'],0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+140, $coordY+40);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'PRIVATE',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+140, $coordY+44);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'GOVERNMENT',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+140, $coordY+48);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'HOUSEHOLD',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+.5, $coordY+152);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'APPLICABLE',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+3, $coordY+155);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'PERIOD',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+20.5, $coordY+153);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'REMITTED AMOUNT',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+51, $coordY+152);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'ACKNOWLEGEMENT',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+56, $coordY+155);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'RECEIPT NO.',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+83, $coordY+152);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'TRANSACTION',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+89, $coordY+155);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'DATE',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+111, $coordY+152);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'NO. OF',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+108, $coordY+155);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'EMPLOYEES',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        
        $oPDF->SetFont('helvetica', 'B', '7');
        $oPDF->SetXY($coordX+135, $coordY+35);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'EMPLOYER TYPE',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+184, $coordY+35);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'REPORT TYPE',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+235, $coordY+35);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'APPLICABLE PERIOD',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+235, $coordY+147);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'CERTIFIED CORRERCT',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        
        $comp_type = $branch_details['comptype_id'];
            if($comp_type=='2'){
                $oPDF->SetXY($coordX+136.7, $coordY+44);
                $oPDF->MultiCell($oPDF->getPageWidth(), 3,'X',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
            }else if($comp_type=='3'){
            	$oPDF->SetXY($coordX+136.7, $coordY+48);
                $oPDF->MultiCell($oPDF->getPageWidth(), 3,'X',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
            }else{
            	$oPDF->SetXY($coordX+136.7, $coordY+40);
                $oPDF->MultiCell($oPDF->getPageWidth(), 3,'X',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
            }
        $oPDF->SetXY($coordX+180.7, $coordY+40);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'X',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        
        $oPDF->SetFont('helvetica', 'B', '7.5');
        $oPDF->SetXY($coordX+199, $coordY+56);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'NHIP PREMIUM',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+198.5, $coordY+59);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'CONTRIBUTION',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+236, $coordY+55);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'MEMBER STATUS',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+136, $coordY+149);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'SUBTOTAL                         (PS + ES)',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+137, $coordY+163);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'GRAND TOTAL                 (PS + ES)',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        
        $oPDF->SetFont('helvetica', '', '5');
        $oPDF->SetXY($coordX+235.5, $coordY+59);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'S-Separated, NE-No Earnings,',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+242, $coordY+61);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'NH-Newly Hired',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+177, $coordY+60);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'MONTHLY',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+178, $coordY+62);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'SALARY',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+177, $coordY+64);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'BRACKET',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+179, $coordY+66);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'(MSB)',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+231, $coordY+155);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'SIGNATURE OVER PRINTED NAME',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+236, $coordY+164);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'OFFICIAL DESIGNATION',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+244, $coordY+173);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'DATE',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+136, $coordY+154);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'(To be accomplished on every page)',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        $oPDF->SetXY($coordX+137, $coordY+168);
        $oPDF->MultiCell($oPDF->getPageWidth(), 3,'(To be accomplished on last page)',0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
        
        //small box
        $oPDF->Line($coordX+137, $coordY+41, $coordX+140, $coordY+41, $style = $style6);//small box private
        $oPDF->Line($coordX+137, $coordY+44, $coordX+140, $coordY+44, $style = $style6);//small box private
        $oPDF->Line($coordX+140, $coordY+41, $coordX+140, $coordY+44, $style = $style6);//small box private
        $oPDF->Line($coordX+137, $coordY+41, $coordX+137, $coordY+44, $style = $style6);//small box private
        $oPDF->Line($coordX+137, $coordY+45, $coordX+140, $coordY+45, $style = $style6);//small box GOVERNMENT
        $oPDF->Line($coordX+137, $coordY+48, $coordX+140, $coordY+48, $style = $style6);//small box GOVERNMENT
        $oPDF->Line($coordX+140, $coordY+45, $coordX+140, $coordY+48, $style = $style6);//small box GOVERNMENT
        $oPDF->Line($coordX+137, $coordY+45, $coordX+137, $coordY+48, $style = $style6);//small box GOVERNMENT
        $oPDF->Line($coordX+137, $coordY+49, $coordX+140, $coordY+49, $style = $style6);//small box HOUSEHOLD
        $oPDF->Line($coordX+137, $coordY+52, $coordX+140, $coordY+52, $style = $style6);//small box HOUSEHOLD
        $oPDF->Line($coordX+140, $coordY+49, $coordX+140, $coordY+52, $style = $style6);//small box HOUSEHOLD
        $oPDF->Line($coordX+137, $coordY+49, $coordX+137, $coordY+52, $style = $style6);//small box HOUSEHOLD
        $oPDF->Line($coordX+181, $coordY+41, $coordX+184, $coordY+41, $style = $style6);//small box REGULAR RF-1
        $oPDF->Line($coordX+181, $coordY+44, $coordX+184, $coordY+44, $style = $style6);//small box REGULAR RF-1
        $oPDF->Line($coordX+184, $coordY+41, $coordX+184, $coordY+44, $style = $style6);//small box REGULAR RF-1
        $oPDF->Line($coordX+181, $coordY+41, $coordX+181, $coordY+44, $style = $style6);//small box REGULAR RF-1
        $oPDF->Line($coordX+181, $coordY+45, $coordX+184, $coordY+45, $style = $style6);//small box ADDITION TO PREVIOUS RF-1
        $oPDF->Line($coordX+181, $coordY+48, $coordX+184, $coordY+48, $style = $style6);//small box ADDITION TO PREVIOUS RF-1
        $oPDF->Line($coordX+184, $coordY+45, $coordX+184, $coordY+48, $style = $style6);//small box ADDITION TO PREVIOUS RF-1
        $oPDF->Line($coordX+181, $coordY+45, $coordX+181, $coordY+48, $style = $style6);//small box ADDITION TO PREVIOUS RF-1
        $oPDF->Line($coordX+181, $coordY+49, $coordX+184, $coordY+49, $style = $style6);//small box DEDUCTION TO PREVIOUS RF-1
        $oPDF->Line($coordX+181, $coordY+52, $coordX+184, $coordY+52, $style = $style6);//small box DEDUCTION TO PREVIOUS RF-1
        $oPDF->Line($coordX+184, $coordY+49, $coordX+184, $coordY+52, $style = $style6);//small box DEDUCTION TO PREVIOUS RF-1
        $oPDF->Line($coordX+181, $coordY+49, $coordX+181, $coordY+52, $style = $style6);//small box DEDUCTION TO PREVIOUS RF-1
        $oPDF->Line($coordX+46, $coordY+62, $coordX+46, $coordY+145, $style = $style6);//line surname
        $oPDF->Line($coordX+87, $coordY+62, $coordX+87, $coordY+145, $style = $style6);//line middle name
        
        		$coordY = 85;
                 
                }else{
                    // increment coordinate Y
                    $coordY+=$oPDF->getFontSize()+2.5;
                }
               	
                $ctrEmp++;
                
                $eree_cont += $eree;
                $empcon += $val['sss']['ppe_amount'];
                $emrcon += $val['sss']['ppe_amount_employer'];
                $subtotal += $total;
                $total_ec += $ec;
                
                $eree_cont_G += $eree;
                $empcon_G += $val['sss']['ppe_amount'];
                $emrcon_G += $val['sss']['ppe_amount_employer'];
                $subtotal_G += $total;
                $total_ec_G += $ec;
                
            }
            
             $oPDF->SetFont('helvetica', '', '7');
             $oPDF->SetXY($coordX+7, $coordY);
             $oPDF->MultiCell(35, 3, "NOTHING FOLLOWS",0,'L');
			 
             $oPDF->SetXY($coordX+187, 163.5);
             $oPDF->MultiCell(20, 3, number_format($empcon,2),0,'R');
             $oPDF->SetXY($coordX+206, 163.5);
             $oPDF->MultiCell(20, 3, number_format($emrcon,2),0,'R');
             $oPDF->SetXY($coordX+193, 171);
             $oPDF->MultiCell(30, 3, number_format($subtotal,2),0,'C');
             
             $oPDF->SetXY($coordX+187, 178.5);
             $oPDF->MultiCell(20, 3, number_format($empcon_G,2),0,'R');
             $oPDF->SetXY($coordX+206, 178.5);
             $oPDF->MultiCell(20, 3, number_format($emrcon_G,2),0,'R');
             $oPDF->SetXY($coordX+193, 185);
             $oPDF->MultiCell(30, 3, number_format($subtotal_G,2),0,'C');
        } 
        // get the pdf output
        $output = $oPDF->Output($str."_remittances_phic".date('Y-m-d').".pdf");
        if(!empty($output)){
            return $output;
        }
        return false;
    }
    
    function dbfetchCompDetails($comp_id_ = null){
		if($comp_id_!=null || $comp_id_ != ''){
		$qry[] = "comp_id = '".$comp_id_."'";
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";
		}
		$sql = "select * from company_info a inner join company_type b on (a.comptype_id=b.comptype_id) $criteria";
		$rsResult = $this->conn->Execute($sql);
		if(!$rsResult->EOF){
			return $rsResult->fields;
		}
    }
    
	function createPDF($content, $paper, $orientation, $filename){
		$dompdf = new DOMPDF();
		$dompdf->load_html($content);
		$dompdf->set_paper($paper,$orientation);
		$dompdf->render();
		$dompdf->stream($filename,array('Attachment' => 0));	
	}
	
	function getCompanyDetails($comp_id){
		if($comp_id!=null || $comp_id != ''){
			$qry[] = "comp_id = '".$comp_id."'";
			$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";
		}
		$sql = "select * from company_info $criteria";
		$rsResult = $this->conn->Execute($sql);
		if(!$rsResult->EOF){
			return $rsResult->fields;
		}
	}
	
   function moneyFormat($num){
    	if((float)$num>0){
    		$money_format = number_format((float)$num, 2, '.', ',');
    	}else{
    		$money_format = number_format((float)0, 2, '.', ',');
    	}
    	return $money_format;
    }
    
	function computeMonthlyRate($emp_id, $gData = array()){
		$objClsMngeDecimal = new Application();
		$sql = "SELECT a.emp_id, st.salarytype_id, fr.fr_hrperday, fr.fr_dayperweek, fr.fr_dayperyear, st.salaryinfo_basicrate
				FROM payroll_comp a
				JOIN factor_rate fr on (fr.fr_id=a.fr_id)
				JOIN salary_info st on (st.emp_id=a.emp_id)
				WHERE a.emp_id='$emp_id'";
		$rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF){
			if($rsResult->fields['salarytype_id'] == 1){
				$monthlyRate = ($rsResult->fields['salaryinfo_basicrate']*$rsResult->fields['fr_hrperday']*$rsResult->fields['fr_dayperyear'])/12;
			} elseif($rsResult->fields['salarytype_id'] == 2) {
				$monthlyRate = ($rsResult->fields['salaryinfo_basicrate']*$rsResult->fields['fr_dayperyear'])/12;
			} elseif($rsResult->fields['salarytype_id'] == 3) {
				$monthlyRate = ($rsResult->fields['salaryinfo_basicrate']*(1/$rsResult->fields['fr_dayperweek'])*$rsResult->fields['fr_dayperweek'])/12;
			} elseif($rsResult->fields['salarytype_id'] == 4) {
				$monthlyRate = ($rsResult->fields['salaryinfo_basicrate']*(1/(2*$rsResult->fields['fr_dayperweek']))*$rsResult->fields['fr_dayperweek'])/12;
			} elseif($rsResult->fields['salarytype_id'] == 6){
				$monthlyRate = $rsResult->fields['salaryinfo_basicrate']/12;
			} else {
				$monthlyRate = $rsResult->fields['salaryinfo_basicrate'];
			}
			return $objClsMngeDecimal->setFinalDecimalPlaces($monthlyRate);
		}
	}
	
	function computePHICContribution($emp_id, $gData){
		$month = $gData['month'];
		$year = $gData['year'];
		$sql = "SELECT sum(ppe.ppe_amount) as ppe_amount, sum(ppe.ppe_amount_employer) as ppe_amount_employer 
				FROM payroll_paystub_entry ppe
				JOIN payroll_pay_stub pps on (pps.paystub_id=ppe.paystub_id)
				JOIN payroll_paystub_report ppr on (ppr.paystub_id=ppe.paystub_id)
				JOIN payroll_pay_period ppp on (ppp.payperiod_id=pps.payperiod_id)
				WHERE ppe.psa_id='14' and ppp.payperiod_period='$month' and ppp.payperiod_period_year='$year' and ppr.emp_id='$emp_id'";
		$rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF){
			return $rsResult->fields;
		}
	}
	
	function replacePHICIFNoRecord($PHIC, $SSS){
		if($PHIC == '' || $PHIC == '--' || $PHIC == '-'){
			return $SSS;
		} else {
			return $PHIC;
		}
	}
	
	function getPHICER2a($gData = array(), $companyDetails = array()) {
		$orientation = 'landscape';
		$unit = 'mm';
		$format = 'A4';
		$unicode = true;
		$encoding = "UTF-8";
		$filename = 'PHIC_ER2.pdf';
		
		$oPDF = new clsPDF($orientation, $unit, $format, $unicode, $encoding);
		$oPDF->SetAutoPageBreak(false);
    	$oPDF->setPrintHeader(false);
		$oPDF->setPrintFooter(false);
		
		$date = $gData['year'] . "-" .$gData['month'];
		$qry = array();
		$qry[] = "m.emp_hiredate LIKE '$date%'";
		$qry[] = "s.salaryinfo_isactive='1'";
		if($gData['branchinfo_id'] != 0){
			$qry[] = "m.branchinfo_id='".$gData['branchinfo_id']."'";
		}
		$criteria = count($qry)>0 ? " where ".implode(' and ',$qry) : '';
    	$sql = "SELECT m.emp_id, pe.pi_phic, pe.pi_sss, concat(pe.pi_fname,' ',pe.pi_mname,' ',pe.pi_lname) as fullname, po.post_name, s.salaryinfo_basicrate, DATE_FORMAT(m.emp_hiredate,'%m-%d-%Y') as datehire  
    			FROM emp_personal_info pe
				JOIN emp_masterfile m on m.pi_id=pe.pi_id
				JOIN emp_position po on po.post_id=m.post_id
				JOIN company_info c on c.comp_id=m.comp_id
				JOIN salary_info s on s.emp_id=m.emp_id
				JOIN branch_info bi on (bi.branchinfo_id=m.branchinfo_id)
				$criteria
				GROUP BY m.emp_id
				ORDER BY pe.pi_lname ASC";
    	$rsResult = $this->conn->Execute($sql);
    	$max_per_page = 20;
    	$size = mysql_num_rows(mysql_query($sql));
    	
    	//Add PDF Page
    	$oPDF->AddPage();
    	
    	// set initial coordinates
		$coordX = 0;
		$coordY = 0;
		
		$style1 = array('width' => .3, 'cap' => 'square', 'join' => 'round', 'dash' => '0', 'phase' => 0, 'color' => array(0, 0, 0));
    	$style = array('width' => .5, 'cap' => 'square', 'join' => 'round', 'dash' => '0', 'phase' => 0, 'color' => array(0, 0, 0));
    	
    	$oPDF->SetFont('times', '', 10);
    	$oPDF->SetFontSize(12);
    	$oPDF->Text($coordX+67.4, $coordY+17.5, "PLEASE READ INSTRUCTION AT THE BACK BEFORE ACCOMPLISHING THIS FORM");
    	$oPDF->Line($coordX+13.4, $coordY+20, $coordX+285, $coordY+20, $style);
    	$oPDF->Line($coordX+13.4, $coordY+20, $coordX+13.4, $coordY+181.9, $style);
    	$oPDF->Line($coordX+13.4, $coordY+181.9, $coordX+285, $coordY+181.9, $style);
    	$oPDF->Line($coordX+285, $coordY+20, $coordX+285, $coordY+181.9, $style);
    	
    	//pag-ibig logo
    	$oPDF->Image(SYSCONFIG_CLASS_PATH."util/tcpdf/images/phic_logo.png", 16, 22.8, 24, 24);
    	
    	//row 1
    	$oPDF->line($coordX+13.4, $coordY+49, $coordX+285, $coordY+49, $style);
    	$oPDF->SetFont('times', '', 10);
    	$oPDF->SetFontSize(12);
    	$oPDF->Text($coordX+166.5, $coordY+25, "(CHECK APPLICABLE BOX)");
    	$oPDF->SetFont('times', 'b', 10);
    	$oPDF->SetFontSize(20);
    	$oPDF->Text($coordX+66, $coordY+34.5, "PHILHEALTH");
    	$oPDF->SetFont('times', '', 10);
    	$oPDF->SetFontSize(11);
    	$oPDF->Text($coordX+57.5, $coordY+39.9, "REPORT OF EMPLOYEE-MEMBERS");
    	
    	$oPDF->Line($coordX+136.5, $coordY+27, $coordX+149, $coordY+27, $style1);
    	$oPDF->Line($coordX+136.5, $coordY+27, $coordX+136.5, $coordY+32.7, $style1);
    	$oPDF->Line($coordX+136.5, $coordY+32.7, $coordX+149, $coordY+32.7, $style1);
    	$oPDF->Line($coordX+149, $coordY+27, $coordX+149, $coordY+32.7, $style1);
    	$oPDF->SetFont('times', '', 10);
    	$oPDF->SetFontSize(12.5);
    	$oPDF->Text($coordX+155.3, $coordY+31.1, "INITIAL LIST(Attach to PhilHealth from Er1)");
    	$oPDF->Line($coordX+136.5, $coordY+33.7, $coordX+149, $coordY+33.7, $style1);
    	$oPDF->Line($coordX+136.5, $coordY+33.7, $coordX+136.5, $coordY+39.4, $style1);
    	$oPDF->Line($coordX+136.5, $coordY+39.4, $coordX+149, $coordY+39.4, $style1);
    	$oPDF->Line($coordX+149, $coordY+33.7, $coordX+149, $coordY+39.4, $style1);
    	$oPDF->SetFont('times', '', 10);
    	$oPDF->SetFontSize(12.5);
    	$oPDF->Text($coordX+155.3, $coordY+37.9, "SUBSEQUENT LIST");
    	
    	$oPDF->SetFont('times', 'B', 10);
    	$oPDF->SetFontSize(45);
    	$oPDF->Text($coordX+255, $coordY+40, "Er2");
    	
    	//row 2
    	$oPDF->Line($coordX+13.4, $coordY+60, $coordX+285, $coordY+60, $style);
    	$oPDF->Line($coordX+200, $coordY+49, $coordX+200, $coordY+60, $style);
    	$oPDF->SetFont('times', 'B', 10);
    	$oPDF->SetFontSize(12);
    	$oPDF->Text($coordX+17, $coordY+56.4, "NAME OF EMPLOYER/FIRM:");
    	$oPDF->Text($coordX+205, $coordY+56.4, "EMPLOYER NO.");
    	
    	//row 3
    	$oPDF->Line($coordX+13.4, $coordY+66, $coordX+285, $coordY+66, $style);
    	$oPDF->SetFont('times', 'B', 10);
    	$oPDF->SetFontSize(12);
    	$oPDF->Text($coordX+17, $coordY+65, "ADDRESS:");
    	
    	//ROW 4
    	$oPDF->Line($coordX+13.4, $coordY+78.7, $coordX+285, $coordY+78.7, $style);
    	$oPDF->SetFont('times', 'B', 10);
    	$oPDF->SetFontSize(9);
    	$oPDF->Text($coordX+16.8, $coordY+69.8, "PHILHEALTH");
    	$oPDF->Text($coordX+20.5, $coordY+73.5, "SSS/GSIS");
    	$oPDF->Text($coordX+20.4, $coordY+77.4, "NUMBER");
    	$oPDF->Text($coordX+52.5, $coordY+73.5, "NAME OF EMPLOYEE");
    	$oPDF->Text($coordX+120, $coordY+73.5, "POSITION");
    	$oPDF->Text($coordX+166, $coordY+73.5, "SALARY");
    	$oPDF->Text($coordX+193, $coordY+69.8, "DATE OF");
    	$oPDF->Text($coordX+193, $coordY+73.5, "EMPLOY-");
    	$oPDF->Text($coordX+195.9, $coordY+77.4, "MENT");
    	$oPDF->Text($coordX+217.5, $coordY+73.5, "EFF. DATE OF");
    	$oPDF->Text($coordX+219.5, $coordY+77.4, "COVERAGE");
    	$oPDF->SetFontSize(7);
    	$oPDF->Text($coordX+219.5, $coordY+69.8, "(DO NOT FILL)");
    	$oPDF->SetFontSize(9);
    	$oPDF->Text($coordX+246, $coordY+71.5, "PREVIOUS EMPLOYER");
    	$oPDF->Text($coordX+257.5, $coordY+75.5, "(IF ANY)");
    	
    	//6LINES FOR COLUMNS
    	$oPDF->Line($coordX+41.5, $coordY+66, $coordX+41.5, $coordY+170, $style);
    	$oPDF->Line($coordX+97.1, $coordY+66, $coordX+97.1, $coordY+170, $style);
    	$oPDF->Line($coordX+158.5, $coordY+60, $coordX+158.5, $coordY+170, $style);
    	$oPDF->Line($coordX+186.8, $coordY+66, $coordX+186.8, $coordY+170, $style);
    	$oPDF->Line($coordX+214.5, $coordY+66, $coordX+214.5, $coordY+170, $style);
    	$oPDF->Line($coordX+243, $coordY+66, $coordX+243, $coordY+170, $style);
    	
    	
    	//ROW 5
    	$oPDF->Line($coordX+13.4, $coordY+170, $coordX+285, $coordY+170, $style);
    	
    	//last row
    	$oPDF->SetFont('times', 'B', 10);
    	$oPDF->SetFontSize(12);
    	$oPDF->Text($coordX+14, $coordY+174.8, "TOTAL NO. LISTED ABOVE:");
    	$oPDF->Line($coordX+146.5, $coordY+170, $coordX+146.5, $coordY+181.9, $style);
    	$oPDF->SetFontSize(7.5);
    	$oPDF->Text($coordX+156, $coordY+179.9, "PAGE_____OF_____SHEETS");
    	$oPDF->Line($coordX+200, $coordY+170, $coordX+200, $coordY+181.9, $style);
    	$oPDF->Line($coordX+207.5, $coordY+175.2, $coordX+277.5, $coordY+175.2, $style1);
    	$oPDF->Text($coordX+219.5, $coordY+179.9, "SIGNATURE OVER PRINTED NAME");
    	$oPDF->SetFontSize(11);
    	$oPDF->Text($coordX+227, $coordY+174.5, $gData['signatoryby']);
    	$oPDF->SetFontSize(12);
    	$oPDF->Text($coordX+108.8, $coordY+186.5, "TO BE ACCOMPLISHED IN DUPLICATE");
    	
    	
    	
    	//get the output
    	$output = $oPDF->Output($filename);
    	
    	if (!empty($output)) {
    		return $output;
    	}
    	return false;
	}
	
	function getPHICER2($gData = array(), $companyDetails = array()) {
		$paper = 'A4';
		$orientation = 'landscape';
		$filename = 'PHIC_ER2.pdf';
		$date = $gData['year'] . "-" .$gData['month'];
		$qry = array();
		//$qry[] = "pbs.empdd_id = '2'";
		//$qry[] = "pbs.bldsched_period != '0'";
		$qry[] = "m.emp_hiredate LIKE '$date%'";
		$qry[] = "s.salaryinfo_isactive='1'";
		if($gData['branchinfo_id'] != 0){
			$qry[] = "m.branchinfo_id='".$gData['branchinfo_id']."'";
		}
		$criteria = count($qry)>0 ? " where ".implode(' and ',$qry) : '';
    	$sql = "SELECT m.emp_id, pe.pi_phic, pe.pi_sss, concat(pe.pi_fname,' ',pe.pi_mname,' ',pe.pi_lname) as fullname, po.post_name, s.salaryinfo_basicrate, DATE_FORMAT(m.emp_hiredate,'%m-%d-%Y') as datehire  
    			FROM emp_personal_info pe
				JOIN emp_masterfile m on m.pi_id=pe.pi_id
				JOIN emp_position po on po.post_id=m.post_id
				JOIN company_info c on c.comp_id=m.comp_id
				JOIN salary_info s on s.emp_id=m.emp_id
				JOIN branch_info bi on (bi.branchinfo_id=m.branchinfo_id)
				$criteria
				GROUP BY m.emp_id
				ORDER BY pe.pi_lname ASC";
    	$rsResult = $this->conn->Execute($sql);
    	$max_per_page = 20;
    	$size = mysql_num_rows(mysql_query($sql));
    $header = '<table width="1030px" style="page-break-after: always;"><tr><td>
			<table align="center"><tr><td>PLEASE READ INSTRUCTION AT THE BACK BEFORE ACCOMPLISHING THIS FORM</td></tr></table>
			<table style="border-collapse:collapse; border:2px solid black;" width="1030px">
				<tr>
					<td width="70px" style="padding:10px;"><img src="'.SYSCONFIG_CLASS_PATH.'util/dompdf/images/pdf_report/phic_logo.png" height="90" width="90" align="center"></td>
					<td align="center" width="350px"><strong><span style="font-size:26px;">PHILHEALTH</span><br><span style="font:14px Helvetica;">REPORT OF EMPLOYEE-MEMBERS</span></strong></td>
					<td style="vertical-align:center;"><table width="400px">
						<tr><td colspan="2" align="center">(CHECK APPLICABLE BOX)</td></tr>
						<tr><td style="border:1px solid black;" width="5px">&nbsp;</td><td width="380px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;INITIAL LIST(Attach to PhilHealth Form Er1)</td></tr>
						<tr><td style="border:1px solid black;" width="5px">&nbsp;</td><td width="380px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SUBSEQUENT LIST</td></tr>
					</table></td>
					<td style="font:60px;"><strong>Er2</strong></td>
				</tr>
			</table>
			<table style="border-collapse:collapse; border-bottom:2px solid black; border-right:2px solid black; border-left:2px solid black;" width="1030px">
				<tr>
					<td style="padding:10px 10px 10px 20px;" width="250px"><strong>NAME OF EMPLOYER/FIRM:</strong></td>
					<td style="border-right:2px solid black;" align="left" width="450px">'.$companyDetails['comp_name'].'</td>
					<td style="padding-left:20px;"><strong>EMPLOYER NO.</strong></td>
					<td>'.$companyDetails['comp_phic'].'</td>
				</tr>
			</table>
			<table style="border-collapse:collapse; border-bottom:2px solid black; border-right:2px solid black; border-left:2px solid black;" width="1030px">
				<tr>
					<td style="padding-left:20px;" width="100px"><strong>ADDRESS:</strong></td>
					<td style="border-right:2px solid black;" align="left" width="450px">'.$companyDetails['comp_add'].'</td>
					<td style="padding-left:20px;" width="150px"><strong>E-MAIL ADDRESS:</strong></td>
					<td>'.$companyDetails['comp_email'].'</td>
				</tr>
			</table>
			<table style="border-collapse:collapse; border-bottom:2px solid black; border-right:2px solid black; border-left:2px solid black; font-size:12px;" width="1030px">
				<tr>
					<td align="center" style="border-right:2px solid black; border-bottom:2px solid black;" width="100px"><strong>PHILHEALTH<br>SSS/GSIS<br>NUMBER</strong></td>
					<td align="center" style="border-right:2px solid black; border-bottom:2px solid black;" width="200px"><strong>NAME OF EMPLOYEE</strong></td>
					<td align="center" style="border-right:2px solid black; border-bottom:2px solid black;" width="220px"><strong>POSITION</strong></td>
					<td align="center" style="border-right:2px solid black; border-bottom:2px solid black;" width="100px"><strong>SALARY</strong></td>
					<td align="center" style="border-right:2px solid black; border-bottom:2px solid black;" width="100px"><strong>DATE OF EMPLOY-<br>MENT</strong></td>
					<td align="center" style="border-right:2px solid black; border-bottom:2px solid black;" width="100px"><strong>(<span STYLE="font-size:8px;">DON NOT FILL</span>)<br>EFF. DATE OF COVERAGE</strong></td>
					<td align="center" width="150px" style="border-bottom:2px solid black;"><strong>PREVIOUS EMPLOYER<br>(IF ANY)</strong></td>
				</tr>';
    $blank = '<tr>
						<td align="center" style="border-right:2px solid black; padding-top:2px;" width="100px">&nbsp;</td>
						<td align="center" style="border-right:2px solid black;" width="200px">&nbsp;</td>
						<td align="center" style="border-right:2px solid black;" width="220px">&nbsp;</td>
						<td align="center" style="border-right:2px solid black;" width="100px">&nbsp;</td>
						<td align="center" style="border-right:2px solid black;" width="100px">&nbsp;</td>
						<td align="center" style="border-right:2px solid black;" width="100px">&nbsp;</td>
						<td align="center" width="150px">&nbsp;</td>
					</tr>';
    $content = $header;
	IF($size == 0) {
	    //if no record found
		for ($count=0; $count<$max_per_page; $count++) {
			$content .=	$blank;
		}
	}else{
	//record found
	$totalPage = ceil($size/$max_per_page);
	$page = 1;
	$ctr = 0;
	$emp_count = 0;
	while (!$rsResult->EOF) {
		$phicno = $rsResult->fields['pi_phic'];
		IF($rsResult->fields['pi_phic'] == "" || $rsResult->fields['pi_phic'] == "--"){
			$phicno_ = $rsResult->fields['pi_sss'];
		}ELSE{
			$phicno_ = $rsResult->fields['pi_phic'];
		}
		$content .=	'<tr>
						<td align="center" style="border-right:2px solid black; padding-top:2px;" width="100px">'.$phicno_.'</td>
						<td align="left" style="border-right:2px solid black; padding-left:10px;" width="200px">'.$rsResult->fields['fullname'].'</td>
						<td align="left" style="border-right:2px solid black; padding-left:10px;" width="220px">'.$rsResult->fields['post_name'].'</td>
						<td align="center" style="border-right:2px solid black;" width="100px">'.$this->computeMonthlyRate($rsResult->fields['emp_id']).'</td>
						<td align="center" style="border-right:2px solid black;" width="100px">'.$rsResult->fields['datehire'].'</td>
						<td align="center" style="border-right:2px solid black;" width="100px">&nbsp;</td>
						<td align="center" width="150px">&nbsp;</td>
					</tr>';
	$rsResult->MoveNext();
	$ctr++;
	$emp_count++;
	if($emp_count >= $max_per_page) { 
		$content .='</table>
				<table style="border-collapse:collapse; border-bottom:2px solid black; border-right:2px solid black; border-left:2px solid black;" width="1030px">
					<tr>
						<td style="border-right:2px solid black;" width="500px"><strong>TOTAL NO. LISTED ABOVE:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:18px">'.$emp_count.'</span></strong></td>
						<td style="border-right:2px solid black;" width="200px">&nbsp;</td>
						<td style="border-right:2px solid black;" align="center"><u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$gData['signatoryby'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></td>
					</tr>
					<tr>
						<td style="border-right:2px solid black;">&nbsp;</td>
						<td style="border-right:2px solid black; font-size:10px;" align="center"><strong>PAGE <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$page.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u> OF <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$totalPage.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u> SHEETS</strong></td>
						<td style="border-right:2px solid black; font-size:10px;" align="center"><strong>SIGNATURE OVER PRINTED NAME</strong></td>
					</tr>
				</table>
				<table style="border-collapse:collapse;" width="1030px">
				<tr><td align="center"><strong>TO BE ACCOMPLISHED IN DUPLICATE</strong></td></tr>
				</table>
			</td></tr></table>';
		$ctr = 0;
		$emp_count = 0;
		$page++;
		$content .= $header;
	}
	}
	while ($ctr < $max_per_page) {
		$content .= $blank;
		$ctr++;
	}
}
	$content .='</table>
				<table style="border-collapse:collapse; border-bottom:2px solid black; border-right:2px solid black; border-left:2px solid black;" width="1030px">
					<tr>
						<td style="border-right:2px solid black;" width="500px"><strong>TOTAL NO. LISTED ABOVE:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:18px">'.$emp_count.'</span></strong></td>
						<td style="border-right:2px solid black;" width="200px">&nbsp;</td>
						<td style="border-right:2px solid black;" align="center"><u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$gData['signatoryby'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></td>
					</tr>
					<tr>
						<td style="border-right:2px solid black;">&nbsp;</td>
						<td style="border-right:2px solid black; font-size:10px;" align="center"><strong>PAGE <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$page.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u> OF <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$totalPage.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u> SHEETS</strong></td>
						<td style="border-right:2px solid black; font-size:10px;" align="center"><strong>SIGNATURE OVER PRINTED NAME</strong></td>
					</tr>
				</table>
				<table style="border-collapse:collapse;" width="1030px">
				<tr><td align="center"><strong>TO BE ACCOMPLISHED IN DUPLICATE</strong></td></tr>
				</table>
			</td></tr></table>';
	$this->createPDF($content, $paper, $orientation, $filename);
	}
	
	/**
	 * @note: xlsSSS_Premium_Report
	 * @param unknown_type $gData
	 */
    function generatePHIC_Premium_Report($gData = array(),$arrData = array()){
    	$m = $gData['year'].'-'.$gData['month'].'-'.'1';
        $filename = "PHIC_Premium_".date('FY',dDate::parseDateTime($m)).".xls"; // The file name you want any resulting file to be called.
    	// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		$objClsMngeDecimal = new Application();
		$finalDecFormat = $objClsMngeDecimal->setFinalDecimalPlaces(0);
		$objReader = PHPExcel_IOFactory::createReader('Excel5');
		$objPHPExcel = $objReader->load("templates/PHIC_Premium.xls");

		//header excel
    	IF($gData['branchinfo_id']!=0){
        	$branch_details = clsSSS::getLocationInfo($gData['branchinfo_id']);
        	$compname = $branch_details['branchinfo_name'];
        	$compadds = $branch_details['branchinfo_add'];
        	$compphicno = $branch_details['branchinfo_phic'];
        	$comptinno = $branch_details['branchinfo_tin'];
        	$comptelno = $branch_details['branchinfo_tel1'];
        }ELSE{
        	$compname = $arrData[0]['emp_info']['comp_name'];
        	$compadds = $arrData[0]['emp_info']['comp_add'];
        	$compphicno = $arrData[0]['emp_info']['comp_phic'];
        	$comptinno = $arrData[0]['emp_info']['comp_tin'];
        	$comptelno = $arrData[0]['emp_info']['comp_tel'];
        }
		$objPHPExcel->getActiveSheet()->setCellValue('C6', $compphicno);
		$objPHPExcel->getActiveSheet()->setCellValue('C7', $comptinno);
		$objPHPExcel->getActiveSheet()->setCellValue('D9', $compname);
		$objPHPExcel->getActiveSheet()->setCellValue('D10', $compadds);
		$objPHPExcel->getActiveSheet()->setCellValue('C12', $comptelno);
		$objPHPExcel->getActiveSheet()->setCellValue('Q10', date('F Y',dDate::parseDateTime($m)));

		//Body List
		$sheet = $objPHPExcel->getActiveSheet();
		$styleArray = array('font' => array('bold' => true));
		$styleArrayBorders = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
		
		$numberOfEmployee = count($arrData);
		$baseRow = 17;
		$lastvalue = 0;
		$fifty = 50; // wag tatanggalin ito dahil may weird effect, hindi gagana yung $baseRowPlus50 promise!
		$baseRowPlus50 = $baseRow + $fifty;
		
		if ($numberOfEmployee > 0) {
            foreach ($arrData as $key => $val) {
//            	$bracket_ = $this->getBracketPHIC($val['sss']['ppe_amount']);
				$row = $baseRow + $key;
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $key+1);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $val['emp_info']['pi_lname']);
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$row, $val['emp_info']['pi_fname']);
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$row, $val['emp_info']['pi_mnamefull']);
				if ($val['emp_info']['pi_phic'][0]) {
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$row, $val['emp_info']['pi_phic'][0].'-'.$val['emp_info']['pi_phic'][1].'-'.$val['emp_info']['pi_phic'][2]);
				} else {
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$row, $val['emp_info']['pi_sss']);
				}
//				if ($gData['show']) {
//					$objPHPExcel->getActiveSheet()->setCellValue('I'.$row, $val['emp_info']['salaryinfo_basicrate']);
//				}
				$objPHPExcel->getActiveSheet()->setCellValue('K'.$row, $val['sss']['msb']);
				$objPHPExcel->getActiveSheet()->setCellValue('M'.$row, $val['sss']['ppe_amount']);
				$objPHPExcel->getActiveSheet()->getStyle('M'.$row)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
				$objPHPExcel->getActiveSheet()->setCellValue('O'.$row, $val['sss']['ppe_amount_employer']);
				$objPHPExcel->getActiveSheet()->getStyle('O'.$row)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
				if ($val['sss']['ppe_amount']=='') {
					$objPHPExcel->getActiveSheet()->setCellValue('Q'.$row, 'NE');
				} else {
					$objPHPExcel->getActiveSheet()->setCellValue('Q'.$row, $val['sss']['remarks']);
				}
				$lastvalue++;
			}
			
//			Dynamic Body List
			$basePlusLast = $baseRow + $lastvalue;
			
			if ($numberOfEmployee < $fifty) {
				$style_minus_1 = $baseRowPlus50 - 1;
				$style = $baseRowPlus50;
				$style1 = $baseRowPlus50 + 1;
				$style2 = $baseRowPlus50 + 2;
				$style3 = $baseRowPlus50 + 3;
				$style4 = $baseRowPlus50 + 4;
				$style5 = $baseRowPlus50 + 5;
				$style6 = $baseRowPlus50 + 6;
				$style8 = $baseRowPlus50 + 8;
				$style11 = $baseRowPlus50 + 11;
			} else {
				$style_minus_1 = $basePlusLast - 1;
				$style = $basePlusLast;
				$style1 = $basePlusLast + 1;
				$style2 = $basePlusLast + 2;
				$style3 = $basePlusLast + 3;
				$style4 = $basePlusLast + 4;
				$style5 = $basePlusLast + 5;
				$style6 = $basePlusLast + 6;
				$style8 = $basePlusLast + 8;
				$style11 = $basePlusLast + 11;
			}
			
			$column = 'A';
			$objPHPExcel->getActiveSheet()->setCellValue($column.$style, '11');
			$objPHPExcel->getActiveSheet()->getStyle($column.$style)->getNumberFormat()->setFormatCode('#');
			$sheet->getStyle($column.$style)->getFont()->setSize(12);
			$sheet->getStyle($column.$style)->applyFromArray($styleArray);
			$sheet->getStyle($column.$style)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle($column.$style)->applyFromArray($styleArrayBorders);
			
			$objPHPExcel->getActiveSheet()->mergeCells('B'.$style.':G'.$style);
			$sheet->getStyle('B'.$style.':G'.$style)->applyFromArray($styleArrayBorders);

			$column = 'B';
			$objPHPExcel->getActiveSheet()->setCellValue($column.$style, 'ACKNOWLEDGEMENT RECEIPT (ME-5/POR/OR/PAR)');
			$sheet->getStyle($column.$style)->applyFromArray($styleArray);
			$sheet->getStyle($column.$style)->getFont()->setName('Calibri');
			$sheet->getStyle($column.$style)->getFont()->setSize(11);
			$sheet->getStyle($column.$style)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			
			$column = 'H';
			$objPHPExcel->getActiveSheet()->unmergeCells($column.$style.':J'.$style);
			$objPHPExcel->getActiveSheet()->setCellValue($column.$style, '12');
			$sheet->getStyle($column.$style)->applyFromArray($styleArrayBorders);
			$sheet->getStyle($column.$style)->applyFromArray($styleArray);
			$sheet->getStyle($column.$style)->getFont()->setSize(12);
			$sheet->getStyle($column.$style)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			
			// Unmerge the cells
			$objPHPExcel->getActiveSheet()->unmergeCells('K'.$style.':L'.$style);
			$style_plus_one = $style + 1;
			$objPHPExcel->getActiveSheet()->unmergeCells('H'.$style_plus_one.':J'.$style_plus_one);
			$objPHPExcel->getActiveSheet()->unmergeCells('K'.$style_plus_one.':L'.$style_plus_one);
			
			$column = 'I';
			$objPHPExcel->getActiveSheet()->mergeCells($column.$style.':L'.$style_plus_one);
			$objPHPExcel->getActiveSheet()->setCellValue($column.$style, 'SUBTOTAL          (PS + ES)');
			$sheet->getStyle($column.$style)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$sheet->getStyle($column.$style)->applyFromArray($styleArray);
			$sheet->getStyle($column.$style)->getFont()->setSize(10.5);
			$sheet->getStyle($column.$style)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle($column.$style.':L'.$style)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle('L'.$style.':L'.$style_plus_one)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			
			$sheet->getStyle('M'.$style.':P'.$style)->applyFromArray($styleArrayBorders);
			
			// Unmerge Q, R, and S Columns
			$objPHPExcel->getActiveSheet()->unmergeCells('Q'.$style.':S'.$style);
			
			$column = 'Q';
			$objPHPExcel->getActiveSheet()->setCellValue($column.$style, '13');
			$sheet->getStyle($column.$style)->getFont()->setSize(12);
			$sheet->getStyle($column.$style)->applyFromArray($styleArray);
			$sheet->getStyle($column.$style)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle($column.$style)->applyFromArray($styleArrayBorders);
			
			// Merge R and S Columns
			$objPHPExcel->getActiveSheet()->mergeCells('R'.$style.':S'.$style);
			
			$column = 'R';
			$objPHPExcel->getActiveSheet()->setCellValue($column.$style, 'CERTIFIED CORRECT');
			$sheet->getStyle($column.$style)->getFont()->setSize(11);
			$sheet->getStyle($column.$style)->getFont()->setName('Calibri');
			$sheet->getStyle($column.$style)->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet()->getStyle($column.$style.':S'.$style)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			
			// Unmerge B and C Columns
			$objPHPExcel->getActiveSheet()->unmergeCells('B'.$style1.':C'.$style1);
			
			// Merge A and B Columns
			$objPHPExcel->getActiveSheet()->mergeCells('A'.$style1.':B'.$style1);
			
			$column = 'A';
			$objPHPExcel->getActiveSheet()->setCellValue($column.$style1, 'APPLICABLE');
			$sheet->getStyle($column.$style1)->getFont()->setSize(8);
			$sheet->getStyle($column.$style1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('B'.$style1)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			
			$column = 'C';
			$objPHPExcel->getActiveSheet()->setCellValue($column.$style1, 'REMITTED');
			$sheet->getStyle($column.$style1)->getFont()->setSize(8);
			$sheet->getStyle($column.$style1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle($column.$style1)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			
			$column = 'D';
			$objPHPExcel->getActiveSheet()->setCellValue($column.$style1, 'ACKNOWLEGEMENT');
			$sheet->getStyle($column.$style1)->getFont()->setSize(8);
			$sheet->getStyle($column.$style1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('E'.$style1)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			
			// Unmerge F and G Columns
			$objPHPExcel->getActiveSheet()->unmergeCells('F'.$style1.':G'.$style1);
			
			$column = 'F';
			$objPHPExcel->getActiveSheet()->setCellValue($column.$style1, 'TRANSACTION');
			$sheet->getStyle($column.$style1)->getFont()->setSize(8);
			$sheet->getStyle($column.$style1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle($column.$style1)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			
			$column = 'G';
			$objPHPExcel->getActiveSheet()->setCellValue($column.$style1, 'NO. OF');
			$sheet->getStyle($column.$style1)->getFont()->setSize(8);
			$sheet->getStyle($column.$style1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle($column.$style1)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			
			$objPHPExcel->getActiveSheet()->getStyle('Q'.$style1)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle('Q'.$style1.':S'.$style1)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			
			// Unmerge B and C Columns in Row +2 and +3
			$objPHPExcel->getActiveSheet()->unmergeCells('B'.$style2.':C'.$style2);
			
			// Merge A and B Columns
			$objPHPExcel->getActiveSheet()->mergeCells('A'.$style2.':B'.$style2);
			
			$column = 'A';
			$objPHPExcel->getActiveSheet()->setCellValue($column.$style2, 'PERIOD');
			$sheet->getStyle($column.$style2)->getFont()->setSize(8);
			$sheet->getStyle($column.$style2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('B'.$style2)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$style2.':B'.$style2)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			
			$column = 'C';
			$objPHPExcel->getActiveSheet()->setCellValue($column.$style2, 'AMOUNT');
			$sheet->getStyle($column.$style2)->getFont()->setSize(8);
			$sheet->getStyle($column.$style2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle($column.$style2)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle($column.$style2)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			
			// Merge D and E Columns
			$objPHPExcel->getActiveSheet()->mergeCells('D'.$style2.':E'.$style2);
			
			$column = 'D';
			$objPHPExcel->getActiveSheet()->setCellValue($column.$style2, 'RECEIPT NO.');
			$sheet->getStyle($column.$style2)->getFont()->setSize(8);
			$sheet->getStyle($column.$style2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle($column.$style2)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			
			// Underline Bottom and Right
			$column = 'E';
			$objPHPExcel->getActiveSheet()->getStyle($column.$style2)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle($column.$style2)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			
			// Unmerge F and G Columns in Row +2
			$objPHPExcel->getActiveSheet()->unmergeCells('F'.$style2.':G'.$style2);
			
			$column = 'F';
			$objPHPExcel->getActiveSheet()->setCellValue($column.$style2, 'DATE');
			$sheet->getStyle($column.$style2)->getFont()->setSize(8);
			$sheet->getStyle($column.$style2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle($column.$style2)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle($column.$style2)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			
			$column = 'G';
			$objPHPExcel->getActiveSheet()->setCellValue($column.$style2, 'EMPLOYEES');
			$sheet->getStyle($column.$style2)->getFont()->setSize(8);
			$sheet->getStyle($column.$style2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle($column.$style2)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle($column.$style2)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			
			// Unmerge H, I and J Columns in Row +2
			$objPHPExcel->getActiveSheet()->unmergeCells('H'.$style2.':J'.$style2);
			
			// Unmerge K and l Columns in Row +2
			$objPHPExcel->getActiveSheet()->unmergeCells('K'.$style2.':L'.$style2);
			
			$column = 'H';
			$objPHPExcel->getActiveSheet()->getStyle($column.$style2)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			
			$column = 'I';
			$objPHPExcel->getActiveSheet()->setCellValue($column.$style2, '(To be accomplished on every page)');
			$sheet->getStyle($column.$style2)->getFont()->setSize(6);
			$sheet->getStyle($column.$style2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle($column.$style2)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			
			// Merge M to P style1 to style2
			$objPHPExcel->getActiveSheet()->mergeCells('M'.$style1.':P'.$style2);
			
			$column = 'J';
			$objPHPExcel->getActiveSheet()->getStyle($column.$style2)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			
			$column = 'K';
			$objPHPExcel->getActiveSheet()->getStyle($column.$style2)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			
			$column = 'L';
			$objPHPExcel->getActiveSheet()->getStyle($column.$style2)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle($column.$style2)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			
			$column = 'M';
			$objPHPExcel->getActiveSheet()->getStyle($column.$style2)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			
			$column = 'N';
			$objPHPExcel->getActiveSheet()->getStyle($column.$style2)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			
			$column = 'O';
			$objPHPExcel->getActiveSheet()->getStyle($column.$style2)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			
			$column = 'P';
			$objPHPExcel->getActiveSheet()->getStyle($column.$style2)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle($column.$style2)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			
			$column = 'Q';
			$objPHPExcel->getActiveSheet()->setCellValue($column.$style2, 'SIGNATURE OVER PRINTED NAME');
			$sheet->getStyle($column.$style2)->getFont()->setSize(5);
			$sheet->getStyle($column.$style2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			
			// Unmerge B and C Columns in Row +3
			$objPHPExcel->getActiveSheet()->unmergeCells('B'.$style3.':C'.$style3);
			
			// Merge A and B Columns
			$objPHPExcel->getActiveSheet()->mergeCells('A'.$style3.':B'.$style3);
			
			$column = 'B';
			$objPHPExcel->getActiveSheet()->getStyle($column.$style3)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			
			$column = 'C';
			$objPHPExcel->getActiveSheet()->getStyle($column.$style3)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			
			$column = 'E';
			$objPHPExcel->getActiveSheet()->getStyle($column.$style3)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			
			// Unmerge F and G Columns in Row +3
			$objPHPExcel->getActiveSheet()->unmergeCells('F'.$style3.':G'.$style3);
			
			$column = 'F';
			$objPHPExcel->getActiveSheet()->getStyle($column.$style3)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			
			$column = 'G';
			$objPHPExcel->getActiveSheet()->getStyle($column.$style3)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			
			// Unmerge H, I and J Columns in Row +3
			$objPHPExcel->getActiveSheet()->unmergeCells('H'.$style3.':J'.$style3);
			
			$column = 'M';
			$objPHPExcel->getActiveSheet()->getStyle($column.$style3)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle($column.$style3)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			
			$column = 'N';
			$objPHPExcel->getActiveSheet()->getStyle($column.$style3)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle($column.$style3)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			
			$column = 'O';
			$objPHPExcel->getActiveSheet()->getStyle($column.$style3)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			
			$column = 'P';
			$objPHPExcel->getActiveSheet()->getStyle($column.$style3)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle($column.$style3)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			
			// Unmerge B and C Columns in Row +4
			$objPHPExcel->getActiveSheet()->unmergeCells('B'.$style4.':C'.$style4);
			
			// Merge A and B Columns
			$objPHPExcel->getActiveSheet()->mergeCells('A'.$style4.':B'.$style4);
			
			$column = 'A';
			$objPHPExcel->getActiveSheet()->getStyle($column.$style4)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			
			$column = 'B';
			$objPHPExcel->getActiveSheet()->getStyle($column.$style4)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle($column.$style4)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			
			// Merge AB style3 and AB style4
			$objPHPExcel->getActiveSheet()->mergeCells('A'.$style3.':B'.$style4);
			
			$column = 'C';
			$objPHPExcel->getActiveSheet()->getStyle($column.$style4)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle($column.$style4)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			
			// Merge C style3 and C style4
			$objPHPExcel->getActiveSheet()->mergeCells('C'.$style3.':C'.$style4);
			
			$column = 'D';
			$objPHPExcel->getActiveSheet()->getStyle($column.$style4)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			
			$column = 'E';
			$objPHPExcel->getActiveSheet()->getStyle($column.$style4)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle($column.$style4)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			
			// Merge DE style3 and DE style4
			$objPHPExcel->getActiveSheet()->mergeCells('D'.$style3.':E'.$style4);
			
			// Unmerge F and G Columns in Row +4
			$objPHPExcel->getActiveSheet()->unmergeCells('F'.$style4.':G'.$style4);
			
			$column = 'F';
			$objPHPExcel->getActiveSheet()->getStyle($column.$style4)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle($column.$style4)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			
			// Merge F style3 and F style4
			$objPHPExcel->getActiveSheet()->mergeCells('F'.$style3.':F'.$style4);
			
			$column = 'G';
			$objPHPExcel->getActiveSheet()->getStyle($column.$style4)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle($column.$style4)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			
			// Merge G style3 and G style4
			$objPHPExcel->getActiveSheet()->mergeCells('G'.$style3.':G'.$style4);
			
			// Unmerge H, I and J Columns in Row +4
			$objPHPExcel->getActiveSheet()->unmergeCells('H'.$style4.':J'.$style4);
			
			// Merge H, I, J, K and L style3
			$objPHPExcel->getActiveSheet()->mergeCells('H'.$style3.':L'.$style3);
			
			// Merge H, I, J, K and L style4
			$objPHPExcel->getActiveSheet()->mergeCells('H'.$style4.':L'.$style4);
			
			$column = 'H';
			$objPHPExcel->getActiveSheet()->setCellValue($column.$style3, '      GRAND TOTAL   (PS + ES)');
			$sheet->getStyle($column.$style3)->getFont()->setSize(10.5);
			$sheet->getStyle($column.$style3)->applyFromArray($styleArray);
			$sheet->getStyle($column.$style3)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle($column.$style3)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_BOTTOM);
			$objPHPExcel->getActiveSheet()->getStyle($column.$style4)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			
			$objPHPExcel->getActiveSheet()->setCellValue($column.$style4, '  (To be accomplished on last page)');
			$sheet->getStyle($column.$style4)->getFont()->setSize(6);
			$sheet->getStyle($column.$style4)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle($column.$style4)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			
			// Merge M to P style4
			$objPHPExcel->getActiveSheet()->mergeCells('M'.$style4.':P'.$style4);
			
			$objPHPExcel->getActiveSheet()->getStyle('I'.$style4)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle('J'.$style4)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle('K'.$style4)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle('L'.$style4)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle('L'.$style4)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			
			$objPHPExcel->getActiveSheet()->getStyle('M'.$style4)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle('N'.$style4)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle('O'.$style4)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle('P'.$style4)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			
			
			$column = 'Q';
			$objPHPExcel->getActiveSheet()->setCellValue($column.$style4, 'DATE');
			$sheet->getStyle($column.$style4)->getFont()->setSize(6);
			$sheet->getStyle($column.$style4)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle($column.$style4)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle($column.$style4)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle($column.$style4)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			
			$objPHPExcel->getActiveSheet()->getStyle('R'.$style4)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle('R'.$style4)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			
			$objPHPExcel->getActiveSheet()->getStyle('S'.$style4)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle('S'.$style4)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			
			// Merge A - P style5
			$objPHPExcel->getActiveSheet()->mergeCells('A'.$style5.':P'.$style5);
			
			$column = 'A';
			$objPHPExcel->getActiveSheet()->setCellValue($column.$style5, 'PLEASE READ INSTRUCTION (FOR EACH NUMBERED BOX)AT THE BACK BEFORE ACCOMPLISHING THIS FORM');
			$sheet->getStyle($column.$style5)->getFont()->setSize(6);
			$sheet->getStyle($column.$style5)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle($column.$style5)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			
			// Unmerge Q, R and S Columns in Row +5
			$objPHPExcel->getActiveSheet()->unmergeCells('Q'.$style5.':S'.$style5);
			
			$column = 'Q';
			$objPHPExcel->getActiveSheet()->setCellValue($column.$style5, '14');
			$sheet->getStyle($column.$style5)->getFont()->setSize(12);
			$sheet->getStyle($column.$style5)->applyFromArray($styleArray);
			$sheet->getStyle($column.$style5)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle($column.$style5)->applyFromArray($styleArrayBorders);
			
			// Merge R and S style5
			$objPHPExcel->getActiveSheet()->mergeCells('R'.$style5.':S'.$style5);
			
			$column = 'R';
			$objPHPExcel->getActiveSheet()->setCellValue($column.$style5, 'PAGE  1  of  1  PAGES');
			$sheet->getStyle($column.$style5)->getFont()->setSize(10);
			$sheet->getStyle($column.$style5)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle($column.$style5)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			
			// Below are data from database
			
			$column = 'A';
			$objPHPExcel->getActiveSheet()->setCellValue($column.$style3, date('F',dDate::parseDateTime($m)));
			$sheet->getStyle($column.$style3)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle($column.$style3)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			
			$objPHPExcel->getActiveSheet()->setCellValue('Q'.$style1, strtoupper($gData['signatoryby']));
			$objPHPExcel->getActiveSheet()->setCellValue('Q'.$style3, date('m-d-o'));
			
			$column = 'G';
			// Number of Employees
			$objPHPExcel->getActiveSheet()->setCellValue($column.$style3, $key+1);
			$sheet->getStyle($column.$style3)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle($column.$style3)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			
			$column = 'M';
			$objPHPExcel->getActiveSheet()->setCellValue($column.$style, '=SUM(M' . $baseRow . ':M'.$style_minus_1.')');
			$objPHPExcel->getActiveSheet()->getStyle($column.$style)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
			$objPHPExcel->getActiveSheet()->setCellValue($column.$style1, '=M'.$style.'+O'.$style);
			$objPHPExcel->getActiveSheet()->getStyle($column.$style1)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
			$objPHPExcel->getActiveSheet()->setCellValue($column.$style3, '=M'.$style);
			$objPHPExcel->getActiveSheet()->getStyle($column.$style3)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
			$objPHPExcel->getActiveSheet()->setCellValue($column.$style4, '=M'.$style3.'+O'.$style3);
			$objPHPExcel->getActiveSheet()->getStyle($column.$style4)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
		
			$sheet->getStyle($column.$style1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle($column.$style1)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			
			$sheet->getStyle($column.$style4)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle($column.$style4)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			
			$column = 'O';
			$objPHPExcel->getActiveSheet()->setCellValue($column.$style, '=SUM(O' . $baseRow . ':O'.$style_minus_1.')');
			$objPHPExcel->getActiveSheet()->getStyle($column.$style)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
			$objPHPExcel->getActiveSheet()->setCellValue($column.$style3, '=O'.$style);
			$objPHPExcel->getActiveSheet()->getStyle($column.$style3)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
			
			$sheet->getColumnDimension("C")->setAutoSize(true);
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
    
    function getBracketPHIC($scr_ee_ = 0){
    	$sql = "Select scr_ec from  sc_records where sc_id = '1' and scr_ee ='".$scr_ee_."'";
    	$var = $this->conn->Execute($sql);
    	return 	$var->fields;
    }
    
	function getPHICME5($gData = array(), $compInfo = array(), $isLoc = false){
		IF($isLoc){
        	$branch_details = clsSSS::getLocationInfo($gData['branchinfo_id']);
        	$compname = $branch_details['branchinfo_name'];
        	$compadds = $branch_details['branchinfo_add'];
        	$compphicno = $branch_details['branchinfo_phic'];
        	$compsssno = $branch_details['branchinfo_sss'];
        	$comptinno = $branch_details['branchinfo_tin'];
        	$comptelno = $branch_details['branchinfo_tel1'];
        }ELSE{
        	$compname = $compInfo['comp_name'];
        	$compadds = $compInfo['comp_add'];
        	$compphicno = $compInfo['comp_phic'];
        	$compsssno = $compInfo['comp_sss'];
        	$comptinno = $compInfo['comp_tin'];
        	$comptelno = $compInfo['comp_tel'];
        }
		$objClsMngeDecimal = new Application();
		$paper = 'A4';
		$orientation = 'landscape';
		$filename = 'PHIC_ME5.pdf';
		$content = '';
		$main = '';
		$month = $gData['month'];
		$year = $gData['year'];
		$qry = array();
		IF($gData['branchinfo_id']!=0){//get Location parameter.
			$qry[] = "em.branchinfo_id = '".$gData['branchinfo_id']."'";
		}
		$qry[]="ppp.payperiod_period='".$month."'";
		$qry[]="ppp.payperiod_period_year='".$year."'";
		//$qry[]="pbs.empdd_id = '2'";
		//$qry[]="pbs.bldsched_period != '0'";
		$criteria = count($qry)>0 ? " WHERE ".implode(' AND ',$qry) : '';
		$sql = "SELECT distinct em.emp_id, p.pi_fname, p.pi_lname, CONCAT(upper(RPAD(p.pi_mname,1,'')),'.') as pi_mname, p.pi_sss, p.pi_phic, ep.post_code 
				FROM emp_personal_info p
				JOIN emp_masterfile em on (em.pi_id=p.pi_id)
				JOIN emp_position ep on (ep.post_id=em.post_id)
				JOIN payroll_paystub_report ppr on (ppr.emp_id=em.emp_id)
				JOIN payroll_paystub_entry ppe on (ppe.paystub_id=ppr.paystub_id)
				JOIN payroll_pay_stub pps on (pps.paystub_id=ppe.paystub_id)
				JOIN payroll_pay_period ppp on (ppp.payperiod_id=pps.payperiod_id)
				$criteria
				ORDER BY p.pi_lname";
		$rsResult = $this->conn->Execute($sql);
		$ctr = 1;
		$max_size = 33;
		$trace = 0;
		$page = 1;
		$size = mysql_num_rows(mysql_query($sql));
		if($gData['show']){
			$width = '100px';
			$headerValue = '<strong>MONTHLY<br>COMPENSATION</strong>';
		} else {
			$width = '10px';
			$headerValue = '&nbsp;';
		}
		$header = 	'<style type="text/css">
						@page { margin-top: 1em; margin-bottom:1px;} 
					</style>
					<table style="border-collapse:collapse; font-size:12px; page-break-after:always;" width="1025px">
					<tr><td>
						<table style="border-collapse:collapse;" width="100%">
							<tr>
								<td colspan="3">PHILIPPINE HEALTH INSURANCE CORPORATION - CONTRIBUTION</td>
								<td>PHIC-ME5 SUPPORTING DOCUMENT</td>
							</tr>
							<tr>
								<td width="180px">APPLICABLE PERIOD</td>
								<td width="420px">'.$month_name = date( 'F', mktime(0, 0, 0, $gData['month']) ).' '.$gData['year'].'</td>
								<td colspan="2">&nbsp;</td>
							</tr>
							<tr>
								<td colspan="2">&nbsp;</td>
								<td align="right">PHILHEALTH EMPLOYER NO.(PEN) :</td>
								<td>'.$compphicno.'</td>
							</tr>
							<tr>
								<td colspan="2">&nbsp;</td>
								<td align="right">SSS NO. :</td>
								<td>'.$compsssno.'</td>
							</tr>
							<tr>
								<td colspan="2">&nbsp;</td>
								<td align="right">TIN :</td>
								<td>'.$comptinno.'</td>
							</tr>
							<tr>
								<td colspan="4">EMPLOYER\'S NAME: '.$compname.'</td>
							</tr>
							<tr>
								<td colspan="4">MAILING ADDRESS: '.$compadds.'</td>
							</tr>
							<tr>
								<td colspan="4" align="right" style="padding-right:100px; border-bottom:1px solid black;">TELEPHONE NO.:'.$compInfo['comp_tel'].'</td>
							</tr>
						</table>
					</td></tr>
					<tr><td>
						<table style="border-collapse:collapse;" width="100%">
							<tr>
								<td style="border-bottom:1px solid black;" width="100px"><strong>SURNAME</strong></td>
								<td style="border-bottom:1px solid black;" width="130px"><strong>GIVEN NAME</strong></td>
								<td style="border-bottom:1px solid black;" width="30px"><strong>M.I.</strong></td>
								<td style="border-bottom:1px solid black;" width="170px"><strong>POSITION ID</strong></td>
								<td style="border-bottom:1px solid black;" width="110px"><strong>PHIC/SSS ID NO.</strong></td>
								<td style="border-bottom:1px solid black;" width="'.$width.'">'.$headerValue.'</td>
								<td style="border-bottom:1px solid black; padding-left:40px;" width="60px"><strong>PERSONAL SHARE</strong></td>
								<td style="border-bottom:1px solid black; padding-left:50px;" width="60px"><strong>EMPLOYER SHARE</strong></td>
								<td style="border-bottom:1px solid black; padding-left:20px;" align="right"><strong>TOTAL</strong></td>
							</tr>';
		if($size>0){
		while(!$rsResult->EOF){
		$trace ++;
		$PHICContributions = $this->computePHICContribution($rsResult->fields['emp_id'], $gData);
		$EEPlusER = $PHICContributions['ppe_amount']+$PHICContributions['ppe_amount_employer'];
		$totalEE += $PHICContributions['ppe_amount'];
		$totalER += $PHICContributions['ppe_amount_employer'];
		$grandTotal += $EEPlusER;
					$main =	'<tr>
								<td>'.$rsResult->fields['pi_lname'].'</td>
								<td>'.$rsResult->fields['pi_fname'].'</td>
								<td>'.$rsResult->fields['pi_mname'].'</td>
								<td>'.$rsResult->fields['post_code'].'</td>
								<td>'.$this->replacePHICIFNoRecord($rsResult->fields['pi_phic'],$rsResult->fields['pi_sss']).'</td>
								<td align="right">'.$this->validateContent($gData['show'],$this->computeMonthlyRate($rsResult->fields['emp_id']), '&nbsp;').'</td>
								<td align="right">'.$objClsMngeDecimal->setFinalDecimalPlaces($PHICContributions['ppe_amount']).'</td>
								<td align="right">'.$objClsMngeDecimal->setFinalDecimalPlaces($PHICContributions['ppe_amount_employer']).'</td>
								<td align="right">'.$objClsMngeDecimal->setFinalDecimalPlaces($EEPlusER).'</td>
							</tr>';
					$footer ='
						<tr><td colspan="9">&nbsp;</td></tr>
						<tr><td colspan="6"><strong>*****NOTHING FOLLOWS*****</strong></td></tr>
						<tr>
							<td colspan="6" style="border-top:1px solid black; border-bottom:1px solid black;"><strong>TOTAL REMITTANCE</strong></td>
							<td align="right" style="border-top:1px solid black; border-bottom:1px solid black;"><strong>'.$objClsMngeDecimal->setFinalDecimalPlaces($totalEE).'</strong></td>
							<td align="right" style="border-top:1px solid black; border-bottom:1px solid black;"><strong>'.$objClsMngeDecimal->setFinalDecimalPlaces($totalER).'</strong></td>
							<td align="right" style="border-top:1px solid black; border-bottom:1px solid black;"><strong>'.$objClsMngeDecimal->setFinalDecimalPlaces($grandTotal).'</strong></td>
						</tr>
						<tr><td colspan="9">&nbsp;</td></tr>
						<tr><td colspan="9">&nbsp;</td></tr>
						<tr><td colspan="9">CERTIFIED CORRECT:</td></tr>
						<tr><td colspan="9">&nbsp;<br></td></tr>
						<tr><td colspan="9">
							<table style="border-collapse:collapse;">
								<tr>
									<td style="border-bottom:1px solid black;" align="center" width="230px">&nbsp;'.$gData['signatoryby'].' / '.$gData['position'].'&nbsp;</td>
									<td width="20px">&nbsp;</td>
									<td style="border-bottom:1px solid black;" align="center" width="80px">'.date("m-d-Y").'</td>
								</tr>
								<tr>
									<td align="center">NAME / DESIGNATION</td>
									<td>&nbsp;</td>
									<td align="center">DATE</td>
								</tr>
							</table>
						</td></tr>';
						$page_footer = '<tr><td colspan="9">&nbsp;</td></tr>
						<tr><td colspan="9">&nbsp;</td></tr>
						<tr><td colspan="9" style="border-top:1px solid black;"><strong>
							<table style="border-collapse:collapse;" width="100%">
								<tr>
									<td width="95%">'.date("m-d-Y h:i:s A").'</td>
									<td>Page '.$page.'</td>
								</tr>
							</table>
						</strong></td></tr>
					</table>
					</td></tr>
					</table>';
			if($ctr == 1){
				$content .= $header;
				$content .= $main;
				$ctr++;
			} elseif($ctr == $max_size or $trace == $size){
				$content .= $main;
				if($trace == $size){
					$content .= $footer;
					while($ctr != ($max_size-9)){
						$content .= '<tr><td colspan="9">&nbsp;</td></tr>';
						$ctr++;
					}
				}
				$content .= $page_footer;
				$ctr=1;
				$page++;
			} else {
				$content .= $main;
				$ctr++;
			}
			$rsResult->MoveNext();
		}
		} else {
			$content .= $header;
			$content .= '<tr><td colspan="9">&nbsp;</td></tr>
						<tr><td colspan="6"><strong>*****NO RECORD FOUND*****</strong></td></tr>
						<tr>
							<td colspan="6" style="border-top:1px solid black; border-bottom:1px solid black;"><strong>TOTAL REMITTANCE</strong></td>
							<td align="right" style="border-top:1px solid black; border-bottom:1px solid black;"><strong>'.$objClsMngeDecimal->setFinalDecimalPlaces($totalEE).'</strong></td>
							<td align="right" style="border-top:1px solid black; border-bottom:1px solid black;"><strong>'.$objClsMngeDecimal->setFinalDecimalPlaces($totalER).'</strong></td>
							<td align="right" style="border-top:1px solid black; border-bottom:1px solid black;"><strong>'.$objClsMngeDecimal->setFinalDecimalPlaces($grandTotal).'</strong></td>
						</tr>
						<tr><td colspan="9">&nbsp;</td></tr>
						<tr><td colspan="9">&nbsp;</td></tr>
						<tr><td colspan="9">CERTIFIED CORRECT:</td></tr>
						<tr><td colspan="9">&nbsp;<br></td></tr>
						<tr><td colspan="9">
							<table style="border-collapse:collapse;">
								<tr>
									<td style="border-bottom:1px solid black;" align="center" width="230px">&nbsp;'.$gData['signatoryby'].' / '.$gData['position'].'&nbsp;</td>
									<td width="20px">&nbsp;</td>
									<td style="border-bottom:1px solid black;" align="center" width="80px">'.date("m-d-Y").'</td>
								</tr>
								<tr>
									<td align="center">NAME / DESIGNATION</td>
									<td>&nbsp;</td>
									<td align="center">DATE</td>
								</tr>
							</table>
						</td></tr>';
						for($i=0; $i<=21; $i++){
							$content .= '<tr><td colspan="9">&nbsp;</td></tr>';
						}
						$content .= '<tr><td colspan="9" style="border-top:1px solid black;"><strong>
							<table style="border-collapse:collapse;" width="100%">
								<tr>
									<td width="95%">'.date("m-d-Y h:i:s A").'</td>
									<td>Page '.$page.'</td>
								</tr>
							</table>
						</strong></td></tr>
					</table>
					</td></tr>
					</table>';
		}
		$this->createPDF($content, $paper, $orientation, $filename);
	}
	
	function generatePHICME5($gData = array(), $isLoc = false){
		$emp = $this->getME5Employees($gData);
        $orientation='L';
        $unit='mm';
        $format='A4';
        $unicode=true;
        $encoding="UTF-8";

        $oPDF = new clsPDF($orientation, $unit, $format, $unicode, $encoding);
        $objClsMngeDecimal = new Application();
        
    	IF($isLoc){
        	$branch_details = $this->getLocationInfo($pData['branchinfo_id']);
        	$compname = $branch_details['branchinfo_name'];
        	$compadds = $branch_details['branchinfo_add'];
        	$compsssno = $branch_details['branchinfo_sss'];
        	$comptinno = $branch_details['branchinfo_tin'];
        	$comptelno = $branch_details['branchinfo_tel1'];
        	$compphicno = $branch_details['branchinfo_phic'];
        }ELSE{
        	$branch_details = $this->dbfetchCompDetails($pData['comp']);
        	$compname = $branch_details['comp_name'];
        	$compadds = $branch_details['comp_add'];
        	$compzip = $branch_details['comp_zipcode'];
        	$compsssno = $branch_details['comp_sss'];
        	$comptinno = $branch_details['comp_tin'];
        	$comptelno = $branch_details['comp_tel'];
        	$compphicno = $branch_details['comp_phic'];
        }
        $address = array_filter(explode(",", $compadds));
        $compadds = implode(" ",$address);
//        $oPDF->SetHeaderData('', PDF_HEADER_LOGO_WIDTH, $branch_details['comp_name'], $branch_details['branch_address'].'-'.$branch_details['branch_trunklines']);
        // set header and footer fonts
        $oPDF->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $oPDF->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        //set margins
        $oPDF->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $oPDF->SetHeaderMargin(PDF_MARGIN_HEADER);
        $oPDF->SetAutoPageBreak(false);
        
        // suppress print header and footer
        $oPDF->setPrintHeader(true);
        $oPDF->setPrintFooter(false);

        // set initila coordinates
        $coordX = 10;
        $coordY = 10;

        $oPDF->AliasNbPages();

        // set initial pdf page
        $oPDF->SetFillColor(255,255,255);
        
        //used to line style...
		$style5 = array('dash' => 2);
		$style6 = array('dash' => 0);
       // $oPDF->SetFont('helvetica', '', '10');
        
        // content        
        $maxPerPage = 19;
        $count = 0;
        foreach($emp as $key => $val){
        	if($count == 0){
        		$oPDF->SetFont('helvetica','',9);
        		$oPDF->AddPage();
	        	$oPDF->SetXY($coordX, $coordY);
		        $oPDF->MultiCell(200,3,"PHILIPPINE HEALTH INSURANCE CORPORATION - CONTRIBUTION",0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
		        $oPDF->SetXY($coordX+215, $coordY);
		        $oPDF->MultiCell(200,3,"PHIC ME-5 SUPPORTING DOCUMENT",0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
		        $oPDF->SetXY($coordX, $coordY+5);
		        $oPDF->MultiCell(50,3,"APPLICABLE PERIOD",0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
		        $oPDF->SetXY($coordX+50, $coordY+5);
		        $oPDF->MultiCell(50,3,date( 'F', mktime(0, 0, 0, $gData['month']) )." ".$gData['year'],0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
		        $oPDF->SetXY($coordX+115, $coordY+10);
		        $oPDF->MultiCell(100,3,"PHILHEALTH EMPLOYER NO.(PEN) : ",0,'R',1, 0, 0, 0, TRUE, 0, TRUE);
		        $oPDF->SetXY($coordX+215, $coordY+10);
		        $oPDF->MultiCell(100,3,$compphicno,0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
		        $oPDF->SetXY($coordX+115, $coordY+15);
		        $oPDF->MultiCell(100,3,"SSS NO. : ",0,'R',1, 0, 0, 0, TRUE, 0, TRUE);
		        $oPDF->SetXY($coordX+215, $coordY+15);
		        $oPDF->MultiCell(100,3,$compsssno,0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
		        $oPDF->SetXY($coordX+115, $coordY+20);
		        $oPDF->MultiCell(100,3,"TIN : ",0,'R',1, 0, 0, 0, TRUE, 0, TRUE);
			    $oPDF->SetXY($coordX+215, $coordY+20);
		        $oPDF->MultiCell(100,3,$comptinno,0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
		        $oPDF->SetXY($coordX, $coordY+25);
		        $oPDF->MultiCell(40,3,"EMPLOYER'S NAME : ",0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
		        $oPDF->SetXY($coordX+35, $coordY+25);
		        $oPDF->MultiCell(100,3,$compname,0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
		        $oPDF->SetXY($coordX, $coordY+30);
		        $oPDF->MultiCell(40,3,"MAILING ADDRESS : ",0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
		        $oPDF->SetXY($coordX+35, $coordY+30);
		        $oPDF->MultiCell(100,3,$compadds,0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
		        $oPDF->SetXY($coordX+115, $coordY+35);
		        $oPDF->MultiCell(100,3,"TELEPHONE NO. : ",0,'R',1, 0, 0, 0, TRUE, 0, TRUE);
		        $oPDF->SetXY($coordX+215, $coordY+35);
		        $oPDF->MultiCell(100,3,$comptelno,0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
		        $oPDF->Line($coordX, $coordY+40, 285, $coordY+40, $style = $style6);
		        $oPDF->SetFont('helvetica','b',9);
		        $oPDF->SetXY($coordX, $coordY+42);
		        $oPDF->MultiCell(40,3,"SURNAME",0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
		        $oPDF->SetXY($coordX+40, $coordY+42);
		        $oPDF->MultiCell(40,3,"GIVEN NAME",0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
		        $oPDF->SetXY($coordX+80, $coordY+42);
		        $oPDF->MultiCell(40,3,"M.I.",0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
		        $oPDF->SetXY($coordX+90, $coordY+42);
		        $oPDF->MultiCell(40,3,"POSITION ID",0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
		        $oPDF->SetXY($coordX+130, $coordY+42);
		        $oPDF->MultiCell(40,3,"PHIC/SSS ID NO.",0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
		        $oPDF->SetXY($coordX+170, $coordY+40);
		        $oPDF->MultiCell(40,3,"MONTHLY COMPENSATION",0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
		       	$oPDF->SetXY($coordX+205, $coordY+40);
		        $oPDF->MultiCell(30,3,"PERSONAL SHARE",0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
		        $oPDF->SetXY($coordX+230, $coordY+40);
		        $oPDF->MultiCell(30,3,"EMPLOYER SHARE",0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
		        $oPDF->SetXY($coordX+255, $coordY+42);
		        $oPDF->MultiCell(30,3,"TOTAL",0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
		        $oPDF->Line($coordX, $coordY+50, 285, $coordY+50, $style = $style6);
		        $contentY = 60;
        	}
        	$PHICContributions = $this->computePHICContribution($val['emp_id'], $gData);
			$EEPlusER = $PHICContributions['ppe_amount']+$PHICContributions['ppe_amount_employer'];
			$totalEE += $PHICContributions['ppe_amount'];
			$totalER += $PHICContributions['ppe_amount_employer'];
			$grandTotal += $EEPlusER;
        	$oPDF->SetFont('helvetica','',8);
	        $oPDF->SetXY($coordX, $contentY);
	        $oPDF->MultiCell(40,3,$val['pi_lname'],0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
	        $oPDF->SetXY($coordX+40, $contentY);
	        $oPDF->MultiCell(40,3,$val['pi_fname'],0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
	        $oPDF->SetXY($coordX+80, $contentY);
	        $oPDF->MultiCell(40,3,$val['pi_mname'],0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
	        $oPDF->SetXY($coordX+90, $contentY);
	        $oPDF->MultiCell(40,3,$val['pos'],0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
	        $oPDF->SetXY($coordX+130, $contentY);
	        $oPDF->MultiCell(40,3,$val['subs'],0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
	        $oPDF->SetXY($coordX+170, $contentY);
	        $oPDF->MultiCell(40,3,$this->validateContent($gData['show'],$objClsMngeDecimal->setFinalDecimalPlaces($this->getMontlyCompensation($val['emp_id'], $gData)), ''),0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
	       	$oPDF->SetXY($coordX+205, $contentY);
	        $oPDF->MultiCell(30,3,$objClsMngeDecimal->setFinalDecimalPlaces($PHICContributions['ppe_amount']),0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
	        $oPDF->SetXY($coordX+230, $contentY);
	        $oPDF->MultiCell(30,3,$objClsMngeDecimal->setFinalDecimalPlaces($PHICContributions['ppe_amount_employer']),0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
	        $oPDF->SetXY($coordX+255, $contentY);
	        $oPDF->MultiCell(30,3,$objClsMngeDecimal->setFinalDecimalPlaces($EEPlusER),0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
	        $contentY += 5;
	        $count++;
	        if($count==$maxPerPage || $key == (count($emp)-1)){
	        	$oPDF->Line($coordX, $coordY+190, 285, $coordY+190, $style = $style6);
		        $oPDF->SetFont('helvetica','b',9);
		        $oPDF->SetXY($coordX, $coordY+190);
		        $oPDF->MultiCell(100,3,date("m-d-Y h:i:s A"),0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
		        $oPDF->SetXY($coordX+255, $coordY+190);
		        $oPDF->MultiCell(30,3,"Page ".$oPDF->PageNo(),0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
	        	$count=0;
	        }
        }
        $contentY += 5;
        $oPDF->SetFont('helvetica','b',8);
        $oPDF->SetXY($coordX, $contentY);
	    $oPDF->MultiCell(100,3,"*****NOTHING FOLLOWS*****",0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
	    $oPDF->Line($coordX, $contentY+5, 285, $contentY+5, $style = $style6);
	    $oPDF->SetXY($coordX, $contentY+5);
	    $oPDF->MultiCell(100,3,"TOTAL REMITTANCE",0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
	    $oPDF->SetXY($coordX+205, $contentY+5);
	    $oPDF->MultiCell(100,3,$objClsMngeDecimal->setFinalDecimalPlaces($totalEE),0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
	    $oPDF->SetXY($coordX+230, $contentY+5);
	    $oPDF->MultiCell(30,3,$objClsMngeDecimal->setFinalDecimalPlaces($totalER),0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
	    $oPDF->SetXY($coordX+255, $contentY+5);
	    $oPDF->MultiCell(30,3,$objClsMngeDecimal->setFinalDecimalPlaces($grandTotal),0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
	    $oPDF->Line($coordX, $contentY+10, 285, $contentY+10, $style = $style6);
	    $oPDF->SetFont('helvetica','',10);
	    $oPDF->SetXY($coordX, $contentY+20);
	    $oPDF->MultiCell(100,3,"CERTIFIED CORRECT:",0,'L',1, 0, 0, 0, TRUE, 0, TRUE);
	    $oPDF->SetXY($coordX, $contentY+30);
	    $oPDF->MultiCell(100,3,$gData['signatoryby']."/".$gData['position'],0,'C',1, 0, 0, 0, TRUE, 0, TRUE);
	    $oPDF->Line($coordX+10, $contentY+35, 100, $contentY+35, $style = $style6);
	    $oPDF->SetXY($coordX, $contentY+35);
	    $oPDF->MultiCell(100,3,"NAME/DESIGNATION",0,'C',1, 0, 0, 0, TRUE, 0, TRUE);
	    $oPDF->SetXY($coordX+80, $contentY+30);
	    $oPDF->MultiCell(80,3,date('m-d-Y'),0,'C',1, 0, 0, 0, TRUE, 0, TRUE);
	    $oPDF->Line($coordX+100, $contentY+35, 150, $contentY+35, $style = $style6);
	    $oPDF->SetXY($coordX+80, $contentY+35);
	    $oPDF->MultiCell(80,3,"DATE",0,'C',1, 0, 0, 0, TRUE, 0, TRUE);
        //printa($pData); exit;
        $output = $oPDF->Output("PHIC_ME5_".date('Y-m-d').".pdf");

        if(!empty($output)){
            return $output;
        }
        return false;
	}
	
	function validateContent($statement, $val, $val_) {
		if ($statement) {
			return $val;
		} else {
			return $val_;
		}
	}
	
	function getME5Employees($gData = array()){
		IF($gData['branchinfo_id']!=0){//get Location parameter.
			$qry[] = "em.branchinfo_id = '".$gData['branchinfo_id']."'";
		}
		$qry[]="ppp.payperiod_period='".$gData['month']."'";
		$qry[]="ppp.payperiod_period_year='".$gData['year']."'";
		//$qry[]="pbs.empdd_id = '2'";
		//$qry[]="pbs.bldsched_period != '0'";
		$criteria = count($qry)>0 ? " WHERE ".implode(' AND ',$qry) : '';
		$sql = "SELECT distinct em.emp_id, p.pi_fname, p.pi_lname, CONCAT(upper(RPAD(p.pi_mname,1,'')),'.') as pi_mname, IF(p.pi_phic!='',p.pi_phic,p.pi_sss) as subs, substring(ep.post_name,1,20) as pos
				FROM emp_personal_info p
				JOIN emp_masterfile em on (em.pi_id=p.pi_id)
				JOIN emp_position ep on (ep.post_id=em.post_id)
				JOIN payroll_paystub_report ppr on (ppr.emp_id=em.emp_id)
				JOIN payroll_paystub_entry ppe on (ppe.paystub_id=ppr.paystub_id)
				JOIN payroll_pay_stub pps on (pps.paystub_id=ppe.paystub_id)
				JOIN payroll_pay_period ppp on (ppp.payperiod_id=pps.payperiod_id)
				$criteria
				ORDER BY p.pi_lname";
		$rsResult = $this->conn->GetAll($sql);
		return $rsResult;
	}
	
	function getMontlyCompensation($emp_id = null, $gData = array()){
		$sql = "select sum(ppe_amount) as compensation
				FROM payroll_paystub_entry a
				join payroll_paystub_report b on (b.paystub_id=a.paystub_id)
				join payroll_pay_period c on (c.payperiod_id=b.payperiod_id)
				where c.payperiod_period='".$gData['month']."' 
				AND c.payperiod_period_year='".$gData['year']."' AND b.emp_id='".$emp_id."' 
				AND a.psa_id=4";
		$rsResult = $this->conn->GetOne($sql);
		return $rsResult;
	}
}

?>