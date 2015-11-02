<?php
require_once(SYSCONFIG_CLASS_PATH."util/PHPExcel.php");
require_once(SYSCONFIG_CLASS_PATH."util/PHPExcel/IOFactory.php");
require_once(SYSCONFIG_CLASS_PATH."util/dompdf/dompdf_config.inc.php");
/**
 * Initial Declaration
 */
$month = array(
	"1" => "January"
	,"2" => "Febuary"
	,"3" => "March"
	,"4" => "April"
	,"5" => "May"
	,"6" => "June"
	,"7" => "July"
	,"8" => "August"
	,"9" => "September"
	,"10" => "October"
	,"11" => "November"
	,"12" => "December"
);
$year = array(
	date('Y') => date('Y'),
	date('Y')-1 => date('Y')-1,
	date('Y')-2 => date('Y')-2,
	date('Y')-3 => date('Y')-3,
	date('Y')-4 => date('Y')-4,
	date('Y')-5 => date('Y')-5,
	date('Y')-6 => date('Y')-6,
	date('Y')-7 => date('Y')-7,
);
/**
 * Class Module
 *
 * @author  JIM
 *
 */
class clsYTDRept{

	var $conn;
	var $fieldMap;
	var $Data;

	/**
	 * Class Constructor
	 *
	 * @param object $dbconn_
	 * @return clsYTDRept object
	 */
	function clsYTDRept($dbconn_ = null){
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
	function doValidateData($gData_ = array()){
		$isValid = true;
		if($gData_['fromyear'] == $gData_['toyear']){
			if($gData_['frommonth'] > $gData_['tomonth']){
				$isValid = false;
				$_SESSION['eMsg'][] = "From Month Invalid. Please enter a valid range.";
			}
		}
		if($gData_['fromyear'] > $gData_['toyear']){
			$isValid = false;
			$_SESSION['eMsg'][] = "From Year Invalid. Please enter a valid range.";
		}
		if($type['isdpart'] != '1'){
			if(count($this->getEmployee($gData_)) <= 0){
				$isValid = false;
				$_SESSION['eMsg'][] = "No Records Found!";
			}
		}
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
		$editLink = "<a href=\"?statpos=ytdrept&edit=',am.mnu_id,'\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/edit.png\" title=\"Edit\" hspace=\"2px\" border=0 width=\"16\" height=\"16\"></a>";
		$delLink = "<a href=\"?statpos=ytdrept&delete=',am.mnu_id,'\" onclick=\"return confirm(\'Are you sure, you want to delete?\');\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/delete.png\" title=\"Delete\" hspace=\"2px\"  border=0 width=\"16\" height=\"16\"></a>";
		$action = "<a href=\"?statpos=ytdrept&action=add\"><img src=\"".SYSCONFIG_DEFAULT_IMAGES_INCTEMP."icons/edited/add.png\" title=\"Add New\" border=0 width=\"16\" height=\"16\"></a>";

		// SqlAll Query
		$sql = "select am.*, CONCAT('$viewLink','$editLink','$delLink') as viewdata
						from app_modules am
						$criteria
						$strOrderBy";

		// Field and Table Header Mapping
		$arrFields = array(
		 "viewdata"=>$action
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
	
	function getCompanyList(){
		$sql = "select * from company_info";
		$rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF){
			$arr[] = $rsResult->fields;
			$rsResult->MoveNext();
		}
		return $arr;
	}
	
	function filterEmployees($gData = array()){
		if(isset($gData['dept_id'])){
			$qry[] = "c.ud_id =".$gData['dept_id'];
		}
		$criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";
		$sql = "select b.emp_id, CONCAT(a.pi_lname,', ',a.pi_fname,' ',concat(RPAD(a.pi_mname,1,' '),'.')) as fullname
				from emp_personal_info a
				inner join emp_masterfile b on (b.pi_id=a.pi_id)
				inner join app_userdept c on (c.ud_id=b.ud_id)
				$criteria
				order by a.pi_lname";
		$rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF){
			$arrData[] = $rsResult->fields;
            $rsResult->MoveNext();
		}
        return $arrData;
	}
	
	function getYTDReportPDF($gData = array(), $cData = array()){
		$paper = 'legal';
		$orientation = 'landscape';
		if($gData['type'] == 1){
            $empdept_type = "Confidential";
        }else if($gData['type'] == 2){
            $empdept_type = "Non-Confidential";
        }else{
        	$empdept_type = "All Employee";
        }
        $filename = 'YTDReport.pdf';
        if($gData['isdpart']){
        	$dept = $this->getDepartment();
        	for($c=0;$c<count($dept);$c++){
        		$emp[] = $this->getEmployee($gData, $dept[$c]['ud_id']);
        		for($count=0;count($emp[$c])>$count;$count++){
			        $content .= '<body style="font-family:Helvetica; font-size:12px;"><table style="page-break-after: always"><tr><td>
	        			<table style="border-collapse:collapse;">
	        				<tr><td style="font-weight:bold;">'.$cData['comp_name'].'</td></tr>
	        				<tr><td>Year-To-Date Statistics Report</td></tr>
	        				<tr><td>From '.strtoupper(date( 'M', mktime(0, 0, 0, $gData['frommonth']))).' '.$gData['fromyear'].' To '.strtoupper(date( 'M', mktime(0, 0, 0, $gData['tomonth']))).' '.$gData['toyear'].'</td></tr>
	        		   		<tr><td>&nbsp;</td></tr>
	        			</table>
	        			<table style="border-collapse:collapse;">
	        				<tr>
	        					<td>Employee/ID: </td>
	        					<td style="font-weight:bold;">'.$emp[$c][$count]['fullname'].'('.$emp[$c][$count]['emp_idnum'].')</td>
	        				</tr>
	        				<tr>
	        					<td>Position: </td>
	        					<td>'.$emp[$c][$count]['post_name'].'</td>
	        				</tr>
	        				<tr>
	        					<td>Department: </td>
	        					<td>'.$emp[$c][$count]['ud_name'].'</td>
	        				</tr>
	        			</table>';
	       		$ms = $gData['frommonth'];
				$me = $gData['tomonth'];
				$ys = $gData['fromyear'];
				$me++;
				$str_date = $ms.' '.$ys;
				$compare = $me.' '.$gData['toyear'];
				$content .=	'<table style="border-collapse:collapse;"><tr><td style="border-top:1px solid black; border-bottom:3px solid black; font-weight:bold;">Period</td>';
				$pCount = 0;
				while($str_date != $compare){
						$period[$pCount]['month'] = $ms;
						$period[$pCount]['year'] = $ys;
	        			$content .=	'<td style="border-top:1px solid black; border-bottom:3px solid black; font-weight:bold;">'.date( 'M', mktime(0, 0, 0, $ms)).'-'.$ys.'</td>';
	        			if($ms == 12){
							$ms = 1;
							$ys++;
						} else {
							$ms++;
						}

						$str_date = $ms.' '.$ys;
						$pCount++;
					}
				
				$content .= '<td style="border-top:1px solid black; border-bottom:3px solid black; font-weight:bold;">YTD Total</td></tr>';
        		$p = $this->getNonZeroPayElements($gData['frommonth'], $gData['tomonth'], $gData['fromyear'],$gData['toyear'], $emp[$c][$count]['emp_id']);
				
				$eTemp = $this->filterPayElement(1);
				if(count($p) > 0){
					foreach($p as $tempKey => $tempVal){
						if(in_array($tempVal, $eTemp)){
							$earnings[] = $tempVal; 
						} else {
							$deductions[] = $tempVal;
						}
					}
				}
				// Basic Salary
				$content .= '<tr><td>Basic Salary</td>';
        		for($countCol=0;count($period)>$countCol;$countCol++){
					$content .=	'<td align="right" width="70px">'.number_format($this->getEntryRec($emp[$c][$count]['emp_id'], 1, $period[$countCol]['month'], $period[$countCol]['year']),2).'</td>';
					$sum += $this->getEntryRec($emp[$c][$count]['emp_id'], 1, $period[$countCol]['month'], $period[$countCol]['year']);
					$earningsTotal[$countCol] = $this->getEntryRec($emp[$c][$count]['emp_id'], 1, $period[$countCol]['month'], $period[$countCol]['year']);
        		}
				$content .=  '<td align="right" width="70px">'.number_format($sum,2).'</td></tr>';
				$sum = 0;
				
				// UT & Tardy
				if($this->checkIfZeroTA($gData['frommonth'], $gData['tomonth'], $gData['fromyear'],$gData['toyear'],3,$emp[$c][$count]['emp_id']) or $this->checkIfZeroTA($gData['frommonth'], $gData['tomonth'], $gData['fromyear'],$gData['toyear'],4,$emp[$c][$count]['emp_id'])){
					$content .= '<tr><td>UT/Tardy</td>';
					for($countCol=0;count($period)>$countCol;$countCol++){
						$data = $this->toNegative($this->getTARec($emp[$c][$count]['emp_id'], 3, $period[$countCol]['month'], $period[$countCol]['year'])+$this->getTARec($emp[$c][$count]['emp_id'], 4, $period[$countCol]['month'], $period[$countCol]['year']));
						$content .=	'<td align="right" width="70px">'.number_format($data,2).'</td>';
						$sum += $data;
						$earningsTotal[$countCol] += $data;
					}
					$content .=  '<td align="right" width="70px">'.number_format($sum,2).'</td></tr>';
				}
				$sum = 0;
				
				//Absences
				if($this->checkIfZeroTA($gData['frommonth'], $gData['tomonth'], $gData['fromyear'],$gData['toyear'],1,$emp[$c][$count]['emp_id'])){
					$content .= '<tr><td>Absences</td>';
					for($countCol=0;count($period)>$countCol;$countCol++){
						$data = $this->toNegative($this->getTARec($emp[$c][$count]['emp_id'], 1, $period[$countCol]['month'], $period[$countCol]['year']));
						$content .=	'<td align="right" width="70px">'.number_format($data,2).'</td>';
						$sum += $data;
						$earningsTotal[$countCol] += $data;
					}
					$content .=  '<td align="right" width="70px">'.number_format($sum,2).'</td></tr>';
				}
				$sum = 0;
				
				// Overtime
				if($this->checkIfZero($gData['frommonth'], $gData['tomonth'], $gData['fromyear'],$gData['toyear'],16,$emp[$c][$count]['emp_id'])){
					$content .= '<tr><td>Overtime</td>';
					for($countCol=0;count($period)>$countCol;$countCol++){
						$data = $this->getEntryRec($emp[$c][$count]['emp_id'], 16, $period[$countCol]['month'], $period[$countCol]['year']);
						$content .=	'<td align="right" width="70px">'.number_format($data,2).'</td>';
						$sum += $data;
						$earningsTotal[$countCol] += $data;
					}
					$content .=  '<td align="right" width="70px">'.number_format($sum,2).'</td></tr>';
				}
				$sum = 0;
				
				// Earnings
        		if(count($earnings) > 0){
					foreach($earnings as $earnKey => $earnVal){
						$content .= '<tr>
									 <td>'.$this->getPayElementNameById($earnVal).'</td>';
						for($countCol=0;count($period)>$countCol;$countCol++){
							$data = $this->getEntryRec($emp[$c][$count]['emp_id'], $earnVal, $period[$countCol]['month'], $period[$countCol]['year']);
							$content .=	'<td align="right" width="70px">'.number_format($this->getEntryRec($emp[$c][$count]['emp_id'], $earnVal, $period[$countCol]['month'], $period[$countCol]['year']),2).'</td>';
							$sum += $data;
							$earningsTotal[$countCol] += $data;
						}
						$content .=  '<td align="right" width="70px">'.number_format($sum,2).'</td></tr>';
					$sum = 0;
					}
					
				}
				
				// Total Earnings
				$content .= '<tr><td style="font-weight:bold; border-top:1px solid black; border-bottom:1px solid black;">TOTAL EARNINGS</td>';
        			for($countCol=0;count($period)>$countCol;$countCol++){
        				$data = $earningsTotal[$countCol];
						$content .=	'<td align="right" width="70px" style="font-weight:bold; border-top:1px solid black; border-bottom:1px solid black;">'.number_format($data,2).'</td>';
						$sum += $data;
					}
				$content .=  '<td align="right" width="70px" style="font-weight:bold; border-top:1px solid black; border-bottom:1px solid black;">'.number_format($sum,2).'</td></tr>';
				$sum = 0;
				
				// Deductions
				if(count($deductions) > 0){
					foreach($deductions as $dedKey => $dedVal){
						$content .= '<tr>
									 <td>'.$this->getPayElementNameById($dedVal).'</td>';
						for($countCol=0;count($period)>$countCol;$countCol++){
							$data = $this->toNegative($this->getEntryRec($emp[$c][$count]['emp_id'], $dedVal, $period[$countCol]['month'], $period[$countCol]['year']));
							$content .=	'<td align="right" width="70px">'.number_format($data,2).'</td>';
							$sum += $data;
							$deductionsTotal[$countCol] += $data;
						}
						$content .=  '<td align="right" width="70px">'.number_format($sum,2).'</td></tr>';
					$sum = 0;
					}
				}
				
				// Total Deductions
				$content .= '<tr><td style="font-weight:bold; border-top:1px solid black; border-bottom:1px solid black;">TOTAL DEDUCTIONS</td>';
        			for($countCol=0;count($period)>$countCol;$countCol++){
        				$data = $deductionsTotal[$countCol];
						$content .=	'<td align="right" width="70px" style="font-weight:bold; border-top:1px solid black; border-bottom:1px solid black;">'.number_format($data,2).'</td>';
						$sum += $data;
					}
				$content .=  '<td align="right" width="70px" style="font-weight:bold; border-top:1px solid black; border-bottom: 1px solid black;">'.number_format($sum,2).'</td></tr>';
				$sum = 0;
				
				// Net Pay
				$content .= '<tr><td style="font-weight:bold; border-top:1px solid black; border-bottom: double black;">NET PAY</td>';
        			for($countCol=0;count($period)>$countCol;$countCol++){
        				$data = $earningsTotal[$countCol] +$deductionsTotal[$countCol];
						$content .=	'<td align="right" width="70px" style="font-weight:bold; border-top:1px solid black; border-bottom: double black;">'.number_format($data,2).'</td>';
						$sum += $data;
					}
				$content .=  '<td align="right" width="70px" style="font-weight:bold; border-top:1px solid black; border-bottom: double black;">'.number_format($sum,2).'</td></tr>';
				$sum = 0;
				
	        	$content .= '</table></td></tr></table></body>';
	        	unset($earningsTotal);
	        	unset($deductionsTotal);
	        	unset($period);
	        	unset($earnings);
				unset($deductions);
        		}
        	}
        } else {
        	$emp = $this->getEmployee($gData);
        	for($count=0;count($emp)>$count;$count++){
	        $content .= '<body style="font-family:Helvetica; font-size:12px;"><table style="page-break-after: always"><tr><td>
	        			<table style="border-collapse:collapse;">
	        				<tr><td style="font-weight:bold;">'.$cData['comp_name'].'</td></tr>
	        				<tr><td>Year-To-Date Statistics Report</td></tr>
	        				<tr><td>From '.strtoupper(date( 'M', mktime(0, 0, 0, $gData['frommonth']))).' '.$gData['fromyear'].' To '.strtoupper(date( 'M', mktime(0, 0, 0, $gData['tomonth']))).' '.$gData['toyear'].'</td></tr>
	        		   		<tr><td>&nbsp;</td></tr>
	        			</table>
	        			<table style="border-collapse:collapse;">
	        				<tr>
	        					<td>Employee/ID: </td>
	        					<td style="font-weight:bold;">'.$emp[$count]['fullname'].'('.$emp[$count]['emp_idnum'].')</td>
	        				</tr>
	        				<tr>
	        					<td>Position: </td>
	        					<td>'.$emp[$count]['post_name'].'</td>
	        				</tr>
	        				<tr>
	        					<td>Department: </td>
	        					<td>'.$emp[$count]['ud_name'].'</td>
	        				</tr>
	        			</table>';
	       		$ms = $gData['frommonth'];
				$me = $gData['tomonth'];
				$ys = $gData['fromyear'];
				$me++;
				$str_date = $ms.' '.$ys;
				$compare = $me.' '.$gData['toyear'];
				$content .=	'<table style="border-collapse:collapse;"><tr><td style="border-top:1px solid black; border-bottom:3px solid black; font-weight:bold;">Period</td>';
				$pCount = 0;
				while($str_date != $compare){
						$period[$pCount]['month'] = $ms;
						$period[$pCount]['year'] = $ys;
	        			$content .=	'<td style="border-top:1px solid black; border-bottom:3px solid black; font-weight:bold;">'.date( 'M', mktime(0, 0, 0, $ms)).'-'.$ys.'</td>';
	        			if($ms == 12){
							$ms = 1;
							$ys++;
						} else {
							$ms++;
						}

						$str_date = $ms.' '.$ys;
						$pCount++;
					}
				
				$content .= '<td style="border-top:1px solid black; border-bottom:3px solid black; font-weight:bold;">YTD Total</td></tr>';
        		$p = $this->getNonZeroPayElements($gData['frommonth'], $gData['tomonth'], $gData['fromyear'],$gData['toyear'], $emp[$count]['emp_id']);
				
				$eTemp = $this->filterPayElement(1);
				if(count($p) > 0){
					foreach($p as $tempKey => $tempVal){
						if(in_array($tempVal, $eTemp)){
							$earnings[] = $tempVal; 
						} else {
							$deductions[] = $tempVal;
						}
					}
				}
				// Basic Salary
				$content .= '<tr><td>Basic Salary</td>';
        		for($countCol=0;count($period)>$countCol;$countCol++){
					$content .=	'<td align="right" width="70px">'.number_format($this->getEntryRec($emp[$count]['emp_id'], 1, $period[$countCol]['month'], $period[$countCol]['year']),2).'</td>';
					$sum += $this->getEntryRec($emp[$count]['emp_id'], 1, $period[$countCol]['month'], $period[$countCol]['year']);
					$earningsTotal[$countCol] = $this->getEntryRec($emp[$count]['emp_id'], 1, $period[$countCol]['month'], $period[$countCol]['year']);
        		}
				$content .=  '<td align="right" width="70px">'.number_format($sum,2).'</td></tr>';
				$sum = 0;
				
				// UT & Tardy
				if($this->checkIfZeroTA($gData['frommonth'], $gData['tomonth'], $gData['fromyear'],$gData['toyear'],3,$emp[$count]['emp_id']) or $this->checkIfZeroTA($gData['frommonth'], $gData['tomonth'], $gData['fromyear'],$gData['toyear'],4,$emp[$count]['emp_id'])){
					$content .= '<tr><td>UT/Tardy</td>';
					for($countCol=0;count($period)>$countCol;$countCol++){
						$data = $this->toNegative($this->getTARec($emp[$count]['emp_id'], 3, $period[$countCol]['month'], $period[$countCol]['year'])+$this->getTARec($emp[$count]['emp_id'], 4, $period[$countCol]['month'], $period[$countCol]['year']));
						$content .=	'<td align="right" width="70px">'.number_format($data,2).'</td>';
						$sum += $data;
						$earningsTotal[$countCol] += $data;
					}
					$content .=  '<td align="right" width="70px">'.number_format($sum,2).'</td></tr>';
				}
				$sum = 0;
				
				//Absences
				if($this->checkIfZeroTA($gData['frommonth'], $gData['tomonth'], $gData['fromyear'],$gData['toyear'],1,$emp[$count]['emp_id'])){
					$content .= '<tr><td>Absences</td>';
					for($countCol=0;count($period)>$countCol;$countCol++){
						$data = $this->toNegative($this->getTARec($emp[$count]['emp_id'], 1, $period[$countCol]['month'], $period[$countCol]['year']));
						$content .=	'<td align="right" width="70px">'.number_format($data,2).'</td>';
						$sum += $data;
						$earningsTotal[$countCol] += $data;
					}
					$content .=  '<td align="right" width="70px">'.number_format($sum,2).'</td></tr>';
				}
				$sum = 0;
				
				// Overtime
				if($this->checkIfZero($gData['frommonth'], $gData['tomonth'], $gData['fromyear'],$gData['toyear'],16,$emp[$count]['emp_id'])){
					$content .= '<tr><td>Overtime</td>';
					for($countCol=0;count($period)>$countCol;$countCol++){
						$data = $this->getEntryRec($emp[$count]['emp_id'], 16, $period[$countCol]['month'], $period[$countCol]['year']);
						$content .=	'<td align="right" width="70px">'.number_format($data,2).'</td>';
						$sum += $data;
						$earningsTotal[$countCol] += $data;
					}
					$content .=  '<td align="right" width="70px">'.number_format($sum,2).'</td></tr>';
				}
				$sum = 0;
				
				// Earnings
        		if(count($earnings) > 0){
					foreach($earnings as $earnKey => $earnVal){
						$content .= '<tr>
									 <td>'.$this->getPayElementNameById($earnVal).'</td>';
						for($countCol=0;count($period)>$countCol;$countCol++){
							$data = $this->getEntryRec($emp[$count]['emp_id'], $earnVal, $period[$countCol]['month'], $period[$countCol]['year']);
							$content .=	'<td align="right" width="70px">'.number_format($this->getEntryRec($emp[$count]['emp_id'], $earnVal, $period[$countCol]['month'], $period[$countCol]['year']),2).'</td>';
							$sum += $data;
							$earningsTotal[$countCol] += $data;
						}
						$content .=  '<td align="right" width="70px">'.number_format($sum,2).'</td></tr>';
					$sum = 0;
					}
					
				}
				
				// Total Earnings
				$content .= '<tr><td style="font-weight:bold; border-top:1px solid black; border-bottom:1px solid black;">TOTAL EARNINGS</td>';
        			for($countCol=0;count($period)>$countCol;$countCol++){
        				$data = $earningsTotal[$countCol];
						$content .=	'<td align="right" width="70px" style="font-weight:bold; border-top:1px solid black; border-bottom:1px solid black;">'.number_format($data,2).'</td>';
						$sum += $data;
					}
				$content .=  '<td align="right" width="70px" style="font-weight:bold; border-top:1px solid black; border-bottom:1px solid black;">'.number_format($sum,2).'</td></tr>';
				$sum = 0;
				
				// Deductions
				if(count($deductions) > 0){
					foreach($deductions as $dedKey => $dedVal){
						$content .= '<tr>
									 <td>'.$this->getPayElementNameById($dedVal).'</td>';
						for($countCol=0;count($period)>$countCol;$countCol++){
							$data = $this->toNegative($this->getEntryRec($emp[$count]['emp_id'], $dedVal, $period[$countCol]['month'], $period[$countCol]['year']));
							$content .=	'<td align="right" width="70px">'.number_format($data,2).'</td>';
							$sum += $data;
							$deductionsTotal[$countCol] += $data;
						}
						$content .=  '<td align="right" width="70px">'.number_format($sum,2).'</td></tr>';
					$sum = 0;
					}
				}
				
				// Total Deductions
				$content .= '<tr><td style="font-weight:bold; border-top:1px solid black; border-bottom:1px solid black;">TOTAL DEDUCTIONS</td>';
        			for($countCol=0;count($period)>$countCol;$countCol++){
        				$data = $deductionsTotal[$countCol];
						$content .=	'<td align="right" width="70px" style="font-weight:bold; border-top:1px solid black; border-bottom:1px solid black;">'.number_format($data,2).'</td>';
						$sum += $data;
					}
				$content .=  '<td align="right" width="70px" style="font-weight:bold; border-top:1px solid black; border-bottom: 1px solid black;">'.number_format($sum,2).'</td></tr>';
				$sum = 0;
				
				// Net Pay
				$content .= '<tr><td style="font-weight:bold; border-top:1px solid black; border-bottom: double black;">NET PAY</td>';
        			for($countCol=0;count($period)>$countCol;$countCol++){
        				$data = $earningsTotal[$countCol] +$deductionsTotal[$countCol];
						$content .=	'<td align="right" width="70px" style="font-weight:bold; border-top:1px solid black; border-bottom: double black;">'.number_format($data,2).'</td>';
						$sum += $data;
					}
				$content .=  '<td align="right" width="70px" style="font-weight:bold; border-top:1px solid black; border-bottom: double black;">'.number_format($sum,2).'</td></tr>';
				$sum = 0;
				
	        	$content .= '</table></td></tr></table></body>';
	        	unset($earningsTotal);
	        	unset($deductionsTotal);
	        	unset($period);
	        	unset($earnings);
				unset($deductions);
	        }
        }
        $this->createPDF($content, $paper, $orientation, $filename);
	}
	
	function getYTDReportExcel($gData = array(), $cData = array()){
		if($gData['type'] == 1){
            $empdept_type = "Confidential";
        }else if($gData['type'] == 2){
            $empdept_type = "Non-Confidential";
        }else{
        	$empdept_type = "All Employee";
        }
		$filename = "YTDReport_".date("mY",mktime(0,0,0,$gData['frommonth'],1,$gData['fromyear']))."_".date("mY",mktime(0,0,0,$gData['tomonth'],1,$gData['toyear'])).".xls"; // The file name you want any resulting file to be called.
    	// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();		
		$objClsMngeDecimal = new Application();
		$finalDecFormat = $objClsMngeDecimal->setFinalDecimalPlaces(0);
		$objReader = PHPExcel_IOFactory::createReader('Excel5');
		$sheet = $objPHPExcel->getActiveSheet();
		$styleBold = array('font' => array('bold' => true));
		if($gData['isdpart']){
			$dept = $this->getDepartment();
			$start_col = "A";
			$col = $start_col;
			$row = 1;
			for($c=0;$c<count($dept);$c++){
				$emp[] = $this->getEmployee($gData, $dept[$c]['ud_id']);
				for($empCount=0;$empCount<count($emp[$c]);$empCount++){
					//header
					$sheet->setCellValue($col.$row, $cData['comp_name']);
					$sheet->getStyle($col.$row)->applyFromArray($styleBold);
					$row++;
					$sheet->setCellValue($col.$row, "Year-To-Date Statistics Report");
					$row++;
					$sheet->setCellValue($col.$row, "From ".strtoupper(date( 'M', mktime(0, 0, 0, $gData['frommonth'])))." ".$gData['fromyear']." To ".strtoupper(date( 'M', mktime(0, 0, 0, $gData['tomonth'])))." ".$gData['toyear']);
					$row = $row+2;
					$sheet->setCellValue($col.$row, "Employee/ID:");
					$col++;
					$sheet->setCellValue($col.$row, $emp[$c][$empCount]['fullname']."(".$emp[$c][$empCount]['emp_idnum'].")");
					$sheet->getStyle($col.$row)->applyFromArray($styleBold);
					$row++;
					$col=$start_col;
					$sheet->setCellValue($col.$row, "Position");
					$col++;
					$sheet->setCellValue($col.$row, $emp[$c][$empCount]['post_name']);
					$row++;
					$col=$start_col;
					$sheet->setCellValue($col.$row, "Department");
					$col++;
					$sheet->setCellValue($col.$row, $emp[$c][$empCount]['ud_name']);
					$row++;
					$col=$start_col;
					$sheet->setCellValue($col.$row, "Period");
					$sheet->getStyle($col.$row)->applyFromArray($styleBold);
					$sheet->getStyle($col.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
					$sheet->getStyle($col.$row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$reset_row = $row;
					$row++;
					$sheet->setCellValue($col.$row, "Basic Salary");
					$row++;
					if($this->checkIfZeroTA($gData['frommonth'], $gData['tomonth'], $gData['fromyear'],$gData['toyear'],3,$emp[$c][$empCount]['emp_id']) or $this->checkIfZeroTA($gData['frommonth'], $gData['tomonth'], $gData['fromyear'],$gData['toyear'],4,$emp[$c][$empCount]['emp_id'])){
						$sheet->setCellValue($col.$row, "UT/Tardy");
						$row++;
					}
					
					if($this->checkIfZeroTA($gData['frommonth'], $gData['tomonth'], $gData['fromyear'],$gData['toyear'],1,$emp[$c][$empCount]['emp_id'])){
						$sheet->setCellValue($col.$row, "Absences");
						$row++;
					}
					if($this->checkIfZero($gData['frommonth'], $gData['tomonth'], $gData['fromyear'],$gData['toyear'],16,$emp[$c][$empCount]['emp_id'])){
						$sheet->setCellValue($col.$row, "Overtime");
						$row++;
					}
					if($this->checkIfZero($gData['frommonth'], $gData['tomonth'], $gData['fromyear'],$gData['toyear'],39,$emp[$c][$empCount]['emp_id'])){
						$sheet->setCellValue($col.$row, "COLA");
						$row++;
					}
					$p = $this->getNonZeroPayElements($gData['frommonth'], $gData['tomonth'], $gData['fromyear'],$gData['toyear'], $emp[$c][$empCount]['emp_id']);
					
					$eTemp = $this->filterPayElement(1);
					if(count($p) > 0){
						foreach($p as $tempKey => $tempVal){
							if(in_array($tempVal, $eTemp)){
								$earnings[] = $tempVal; 
							} else {
								$deductions[] = $tempVal;
							}
						}
					}
					
					if(count($earnings) > 0){
						$earnings = array_unique($earnings);
						foreach($earnings as $earnKey => $earnVal){
							$sheet->setCellValue($col.$row, $this->getPayElementNameById($earnVal));
							$row++;
						}
					}
					$sheet->setCellValue($col.$row, "TOTAL EARNINGS");
					$sheet->getStyle($col.$row)->applyFromArray($styleBold);
					$sheet->getStyle($col.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$sheet->getStyle($col.$row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$row++;
					
					if(count($deductions) > 0){
						$deductions = array_unique($deductions);
						foreach($deductions as $dedKey => $dedVal){
							$sheet->setCellValue($col.$row, $this->getPayElementNameById($dedVal));
							$row++;
						}
					}
					$sheet->setCellValue($col.$row, "TOTAL DEDUCTIONS");
					$sheet->getStyle($col.$row)->applyFromArray($styleBold);
					$sheet->getStyle($col.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$sheet->getStyle($col.$row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$row++;
					$sheet->setCellValue($col.$row, "NET PAY");
					$sheet->getStyle($col.$row)->applyFromArray($styleBold);
					$sheet->getStyle($col.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);
					$sheet->getStyle($col.$row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$row++;
					/* 	End of Header
						Start of Body/Data */
					$col++;
					$row = $reset_row;
					$ms = $gData['frommonth'];
					$me = $gData['tomonth'];
					$ys = $gData['fromyear'];
					$ye = $gData['toyear'];
					if($me == 12){
						$me = 1;
						$ye++;
					} else {
						$me++;
					}
					$str_date = $ms.' '.$ys;
					$compare = $me.' '.$ye;
					while($str_date != $compare){
						$sheet->setCellValue($col.$row, date( 'M', mktime(0, 0, 0, $ms)).'-'.$ys);
						$sheet->getStyle($col.$row)->applyFromArray($styleBold);
						$sheet->getStyle($col.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
						$sheet->getStyle($col.$row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$row++;
						$sumEarnStart = $col.$row;
						$sheet->setCellValue($col.$row, $this->getEntryRec($emp[$c][$empCount]['emp_id'], 1, $ms, $ys));
						$sheet->getStyle($col.$row)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
						$row++;
						//UT&Tardy
						if($this->checkIfZeroTA($gData['frommonth'], $gData['tomonth'], $gData['fromyear'],$gData['toyear'],3,$emp[$c][$empCount]['emp_id']) or $this->checkIfZeroTA($gData['frommonth'], $gData['tomonth'], $gData['fromyear'],$gData['toyear'],4,$emp[$c][$empCount]['emp_id'])){
							$sheet->setCellValue($col.$row, $this->toNegative($this->getTARec($emp[$c][$empCount]['emp_id'], 3, $ms, $ys)+$this->getTARec($emp[$c][$empCount]['emp_id'], 4, $ms, $ys)));
							$sheet->getStyle($col.$row)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
							$row++;
						}
						//Absences
						if($this->checkIfZeroTA($gData['frommonth'], $gData['tomonth'], $gData['fromyear'],$gData['toyear'],1,$emp[$c][$empCount]['emp_id'])){
							$sheet->setCellValue($col.$row, $this->toNegative($this->getTARec($emp[$c][$empCount]['emp_id'], 1, $ms, $ys)));
							$sheet->getStyle($col.$row)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
							$row++;
						}
						//OT
						if($this->checkIfZero($gData['frommonth'], $gData['tomonth'], $gData['fromyear'],$gData['toyear'],16,$emp[$c][$empCount]['emp_id'])){
							$sheet->setCellValue($col.$row, $this->getEntryRec($emp[$c][$empCount]['emp_id'], 16, $ms, $ys));
							$sheet->getStyle($col.$row)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
							$row++;
						}
						//COLA
						if($this->checkIfZero($gData['frommonth'], $gData['tomonth'], $gData['fromyear'],$gData['toyear'],39,$emp[$c][$empCount]['emp_id'])){
							$sheet->setCellValue($col.$row, $this->getEntryRec($emp[$c][$empCount]['emp_id'], 39, $ms, $ys));
							$sheet->getStyle($col.$row)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
							$row++;
						}
						//Earnings
						if(count($earnings) > 0){
							foreach($earnings as $earnKey => $earnVal){
								$sheet->setCellValue($col.$row, $this->getEntryRec($emp[$c][$empCount]['emp_id'], $earnVal, $ms, $ys));
								$sheet->getStyle($col.$row)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
								$row++;
							}
						}
						$r = $row-1;
						$sumEarnEnd = $col.$r;
						$sheet->setCellValue($col.$row, "=sum(".$sumEarnStart.":".$sumEarnEnd.")");
						$sheet->getStyle($col.$row)->applyFromArray($styleBold);
						$sheet->getStyle($col.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$sheet->getStyle($col.$row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$sheet->getStyle($col.$row)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
						$earnCell = $col.$row;
						$earnRow = $row;
						$row++;
						$sumDeductStart = $col.$row;
						//Deductions
						if(count($deductions) > 0){
							foreach($deductions as $dedKey => $dedVal){
								$sheet->setCellValue($col.$row, $this->toNegative($this->getEntryRec($emp[$c][$empCount]['emp_id'], $dedVal, $ms, $ys)));
								$sheet->getStyle($col.$row)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
								$row++;
							}
						}
						$r = $row-1;
						$sumDeductEnd = $col.$r;
						$sheet->setCellValue($col.$row, "=sum(".$sumDeductStart.":".$sumDeductEnd.")");
						$sheet->getStyle($col.$row)->applyFromArray($styleBold);
						$sheet->getStyle($col.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$sheet->getStyle($col.$row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$sheet->getStyle($col.$row)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
						$deductionCell = $col.$row;
						$deductionRow = $row;
						$row++;
						$sheet->setCellValue($col.$row, "=".$earnCell."+".$deductionCell);
						$sheet->getStyle($col.$row)->applyFromArray($styleBold);
						$sheet->getStyle($col.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);
						$sheet->getStyle($col.$row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$sheet->getStyle($col.$row)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
						$netRow = $row;
						$row++;
						if($ms == 12){
							$ms = 1;
							$ys++;
						} else {
							$ms++;
						}
						$str_date = $ms.' '.$ys;
						$col++;
						$last_row = $row;
						$row = $reset_row;
					}
					$last_col = chr(ord($col) - 1 );
					$sheet->setCellValue($col.$row, 'YTD Total');
					$sheet->getStyle($col.$row)->applyFromArray($styleBold);
					$sheet->getStyle($col.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
					$sheet->getStyle($col.$row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$row++;
					while($row < $last_row){
						$sheet->setCellValue($col.$row, '=sum(B'.$row.':'.$last_col.$row.')');
						$sheet->getStyle($col.$row)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
						if($row == $earnRow or $row == $deductionRow){
							$sheet->getStyle($col.$row)->applyFromArray($styleBold);
							$sheet->getStyle($col.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$sheet->getStyle($col.$row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						}
						if($row == $netRow){
							$sheet->getStyle($col.$row)->applyFromArray($styleBold);
							$sheet->getStyle($col.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);
							$sheet->getStyle($col.$row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						}
						$row++;
					}
					$col = $start_col;
					//add space for next record			
					$row = $last_row+5;
					unset($earnings);
					unset($deductions);
				}
			}
		} else {
			$start_col = "A";
			$col = $start_col;
			$row = 1;
			$emp = $this->getEmployee($gData);
			//printa($emp);exit;
			for($count=0;$count<count($emp);$count++){
			//header
				$sheet->setCellValue($col.$row, $cData['comp_name']);
				$sheet->getStyle($col.$row)->applyFromArray($styleBold);
				$row++;
				$sheet->setCellValue($col.$row, "Year-To-Date Statistics Report");
				$row++;
				$sheet->setCellValue($col.$row, "From ".strtoupper(date( 'M', mktime(0, 0, 0, $gData['frommonth'])))." ".$gData['fromyear']." To ".strtoupper(date( 'M', mktime(0, 0, 0, $gData['tomonth'])))." ".$gData['toyear']);
				$row = $row+2;
				$sheet->setCellValue($col.$row, "Employee/ID:");
				$col++;
				$sheet->setCellValue($col.$row, $emp[$count]['fullname']."(".$emp[$count]['emp_idnum'].")");
				$sheet->getStyle($col.$row)->applyFromArray($styleBold);
				$row++;
				$col=$start_col;
				$sheet->setCellValue($col.$row, "Position");
				$col++;
				$sheet->setCellValue($col.$row, $emp[$count]['post_name']);
				$row++;
				$col=$start_col;
				$sheet->setCellValue($col.$row, "Department");
				$col++;
				$sheet->setCellValue($col.$row, $emp[$count]['ud_name']);
				$row++;
				$col=$start_col;
				$sheet->setCellValue($col.$row, "Period");
				$sheet->getStyle($col.$row)->applyFromArray($styleBold);
				$sheet->getStyle($col.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
				$sheet->getStyle($col.$row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$reset_row = $row;
				$row++;
				$sheet->setCellValue($col.$row, "Basic Salary");
				$row++;
				if($this->checkIfZeroTA($gData['frommonth'], $gData['tomonth'], $gData['fromyear'],$gData['toyear'],3,$emp[$count]['emp_id']) or $this->checkIfZeroTA($gData['frommonth'], $gData['tomonth'], $gData['fromyear'],$gData['toyear'],4,$emp[$count]['emp_id'])){
					$sheet->setCellValue($col.$row, "UT/Tardy");
					$row++;
				}
				
				if($this->checkIfZeroTA($gData['frommonth'], $gData['tomonth'], $gData['fromyear'],$gData['toyear'],1,$emp[$count]['emp_id'])){
					$sheet->setCellValue($col.$row, "Absences");
					$row++;
				}
				if($this->checkIfZero($gData['frommonth'], $gData['tomonth'], $gData['fromyear'],$gData['toyear'],16,$emp[$count]['emp_id'])){
					$sheet->setCellValue($col.$row, "Overtime");
					$row++;
				}
				if($this->checkIfZero($gData['frommonth'], $gData['tomonth'], $gData['fromyear'],$gData['toyear'],39,$emp[$count]['emp_id'])){
					$sheet->setCellValue($col.$row, "COLA");
					$row++;
				}
				$p = $this->getNonZeroPayElements($gData['frommonth'], $gData['tomonth'], $gData['fromyear'],$gData['toyear'], $emp[$count]['emp_id']);
				//here
				$eTemp = $this->filterPayElement(1);
				if(count($p) > 0){
					foreach($p as $tempKey => $tempVal){
						if(in_array($tempVal, $eTemp)){
							$earnings[] = $tempVal; 
						} else {
							$deductions[] = $tempVal;
						}
					}
				}
							
				if(count($earnings) > 0){
					$earnings = array_unique($earnings);
					foreach($earnings as $earnKey => $earnVal){;
						$sheet->setCellValue($col.$row, $this->getPayElementNameById($earnVal));
						$row++;
					}
				}
				$sheet->setCellValue($col.$row, "TOTAL EARNINGS");
				$sheet->getStyle($col.$row)->applyFromArray($styleBold);
				$sheet->getStyle($col.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$sheet->getStyle($col.$row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$row++;
				if(count($deductions) > 0){
					$deductions = array_unique($deductions);
					foreach($deductions as $dedKey => $dedVal){
						$sheet->setCellValue($col.$row, $this->getPayElementNameById($dedVal));
						$row++;
					}
				}
				$sheet->setCellValue($col.$row, "TOTAL DEDUCTIONS");
				$sheet->getStyle($col.$row)->applyFromArray($styleBold);
				$sheet->getStyle($col.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$sheet->getStyle($col.$row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$row++;
				$sheet->setCellValue($col.$row, "NET PAY");
				$sheet->getStyle($col.$row)->applyFromArray($styleBold);
				$sheet->getStyle($col.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);
				$sheet->getStyle($col.$row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$row++;
				/* 	End of Header
					Start of Body/Data */
				$col++;
				$row = $reset_row;
				$ms = $gData['frommonth'];
				$me = $gData['tomonth'];
				$ys = $gData['fromyear'];
				$ye = $gData['toyear'];
				if($me == 12){
						$me = 1;
						$ye++;
					} else {
						$me++;
					}
				$str_date = $ms.' '.$ys;
				$compare = $me.' '.$ye;
				
				while($str_date != $compare){
					$sheet->setCellValue($col.$row, date( 'M', mktime(0, 0, 0, $ms)).'-'.$ys);
					$sheet->getStyle($col.$row)->applyFromArray($styleBold);
					$sheet->getStyle($col.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
					$sheet->getStyle($col.$row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$row++;
					$sumEarnStart = $col.$row;
					$sheet->setCellValue($col.$row, $this->getEntryRec($emp[$count]['emp_id'], 1, $ms, $ys));
					$sheet->getStyle($col.$row)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
					$row++;
					//UT&Tardy
					if($this->checkIfZeroTA($gData['frommonth'], $gData['tomonth'], $gData['fromyear'],$gData['toyear'],3,$emp[$count]['emp_id']) or $this->checkIfZeroTA($gData['frommonth'], $gData['tomonth'], $gData['fromyear'],$gData['toyear'],4,$emp[$count]['emp_id'])){
						$sheet->setCellValue($col.$row, $this->toNegative($this->getTARec($emp[$count]['emp_id'], 3, $ms, $ys)+$this->getTARec($emp[$count]['emp_id'], 4, $ms, $ys)));
						$sheet->getStyle($col.$row)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
						$row++;
					}
					//Absences
					if($this->checkIfZeroTA($gData['frommonth'], $gData['tomonth'], $gData['fromyear'],$gData['toyear'],1,$emp[$count]['emp_id'])){
						$sheet->setCellValue($col.$row, $this->toNegative($this->getTARec($emp[$count]['emp_id'], 1, $ms, $ys)));
						$sheet->getStyle($col.$row)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
						$row++;
					}
					//OT
					if($this->checkIfZero($gData['frommonth'], $gData['tomonth'], $gData['fromyear'],$gData['toyear'],16,$emp[$count]['emp_id'])){
						$sheet->setCellValue($col.$row, $this->getEntryRec($emp[$count]['emp_id'], 16, $ms, $ys));
						$sheet->getStyle($col.$row)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
						$row++;
					}
					//COLA
					if($this->checkIfZero($gData['frommonth'], $gData['tomonth'], $gData['fromyear'],$gData['toyear'],39,$emp[$count]['emp_id'])){
						$sheet->setCellValue($col.$row, $this->getEntryRec($emp[$count]['emp_id'], 39, $ms, $ys));
						$sheet->getStyle($col.$row)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
						$row++;
					}
					//Earnings
					if(count($earnings) > 0){
						foreach($earnings as $earnKey => $earnVal){
							$sheet->setCellValue($col.$row, $this->getEntryRec($emp[$count]['emp_id'], $earnVal, $ms, $ys));
							$sheet->getStyle($col.$row)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
							$row++;
						}
					}
					$r = $row-1;
					$sumEarnEnd = $col.$r;
					$sheet->setCellValue($col.$row, "=sum(".$sumEarnStart.":".$sumEarnEnd.")");
					$sheet->getStyle($col.$row)->applyFromArray($styleBold);
					$sheet->getStyle($col.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$sheet->getStyle($col.$row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$sheet->getStyle($col.$row)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
					$earnCell = $col.$row;
					$earnRow = $row;
					$row++;
					$sumDeductStart = $col.$row;
					$totalDeduct = 0;
					//Deductions
					if(count($deductions) > 0){
						foreach($deductions as $dedKey => $dedVal){
							$sheet->setCellValue($col.$row, $this->toNegative($this->getEntryRec($emp[$count]['emp_id'], $dedVal, $ms, $ys)));
							$sheet->getStyle($col.$row)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
							$row++;
							$totalDeduct += $this->getEntryRec($emp[$count]['emp_id'], $dedVal, $ms, $ys);
						}
					}
					$r = $row-1;
					$sumDeductEnd = $col.$r; //echo "=sum(".$sumDeductStart.":".$sumDeductEnd.")<br>";
					$sheet->setCellValue($col.$row, $this->toNegative($totalDeduct));
					//$sheet->setCellValue($col.$row, "1");
					$sheet->getStyle($col.$row)->applyFromArray($styleBold);
					$sheet->getStyle($col.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$sheet->getStyle($col.$row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$sheet->getStyle($col.$row)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
					$deductionCell = $col.$row;
					$deductionRow = $row;
					$row++;
					$sheet->setCellValue($col.$row, "=".$earnCell."+".$deductionCell);
					$sheet->getStyle($col.$row)->applyFromArray($styleBold);
					$sheet->getStyle($col.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);
					$sheet->getStyle($col.$row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$sheet->getStyle($col.$row)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
					$netRow = $row;
					$row++;
					if($ms == 12){
						$ms = 1;
						$ys++;
					} else {
						$ms++;
					}
					$str_date = $ms.' '.$ys;
					$col++;
					$last_row = $row;
					$row = $reset_row;
					//if($str_date == $compare){
					//	break;
					//}
				}
				$last_col = chr(ord($col) - 1 );
				$sheet->setCellValue($col.$row, 'YTD Total');
				$sheet->getStyle($col.$row)->applyFromArray($styleBold);
				$sheet->getStyle($col.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
				$sheet->getStyle($col.$row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$row++;
				while($row < $last_row){
					$sheet->setCellValue($col.$row, '=sum(B'.$row.':'.$last_col.$row.')');
					$sheet->getStyle($col.$row)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
					if($row == $earnRow or $row == $deductionRow){
						$sheet->getStyle($col.$row)->applyFromArray($styleBold);
						$sheet->getStyle($col.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$sheet->getStyle($col.$row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					}
					if($row == $netRow){
						$sheet->getStyle($col.$row)->applyFromArray($styleBold);
						$sheet->getStyle($col.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);
						$sheet->getStyle($col.$row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					}
					$row++;
				}
				$col = $start_col;
				//add space for next record			
				$row = $last_row+5;
				unset($earnings);
				unset($deductions);
			}
		}
		//formatting of columns
		$sheet->getColumnDimension('A')->setWidth(20);
		$formatStart = 'B';
		$formatEnd = chr(ord($last_col) + 1);
		while($formatStart <= $formatEnd){
			$sheet->getColumnDimension($formatStart)->setWidth(12);
			$formatStart++;
		}
		// Rename sheet
		$sheet->setTitle($filename);
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);
		$sheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
		$sheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
		$sheet->getPageSetup()->setFitToPage(true);
		$sheet->getPageSetup()->setFitToWidth(1);
		$sheet->getPageSetup()->setFitToHeight(0);
		// Redirect output to a client's web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename='.$filename);
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
	}
	
	function getYTDReportExcelSummary($gData = array(), $cData = array()){
//		printa($cData);
//		printa($gData);
//		exit;
		if($gData['type'] == 1){
            $empdept_type = "Confidential";
        }else if($gData['type'] == 2){
            $empdept_type = "Non-Confidential";
        }else{
        	$empdept_type = "All Employee";
        }
		$filename = "YTD Report.xls"; // The file name you want any resulting file to be called.
    	// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		$objClsMngeDecimal = new Application();
		$finalDecFormat = $objClsMngeDecimal->setFinalDecimalPlaces(0);
		$objReader = PHPExcel_IOFactory::createReader('Excel5');
		$sheet = $objPHPExcel->getActiveSheet();
		$styleBold = array('font' => array('bold' => true));
		$start_col = "A";
		$col = $start_col;
		$row = 1;
		// Header
		$sheet->setCellValue($col.$row, $cData['comp_name']);
		$sheet->getStyle($col.$row)->applyFromArray($styleBold);
		$row++;
		$sheet->setCellValue($col.$row, $cData['comp_add']);
		$row++;
		$sheet->setCellValue($col.$row, "Year-To-Date Statistics Report");
		$row++;
		$sheet->setCellValue($col.$row, "From ".strtoupper(date( 'M', mktime(0, 0, 0, $gData['frommonth'])))." ".$gData['fromyear']." To ".strtoupper(date( 'M', mktime(0, 0, 0, $gData['tomonth'])))." ".$gData['toyear']);
		$row =+ 7;
		$sheet->setCellValue($col.$row, "");
		$sheet->getStyle($col.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sheet->getStyle($col.$row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$col++;
		$sheet->setCellValue($col.$row, "Emp ID");
		$sheet->getStyle($col.$row)->applyFromArray($styleBold);
		$sheet->getStyle($col.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sheet->getStyle($col.$row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$col++;
		$sheet->setCellValue($col.$row, "Employee Name");
		$sheet->getStyle($col.$row)->applyFromArray($styleBold);
		$sheet->getStyle($col.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sheet->getStyle($col.$row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$col++;
		$sheet->setCellValue($col.$row, "Tax Stat");
		$sheet->getStyle($col.$row)->applyFromArray($styleBold);
		$sheet->getStyle($col.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sheet->getStyle($col.$row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$col++;
		$row--;
		$sheet->setCellValue($col.$row, "Earnings");
		$sheet->getStyle($col.$row)->applyFromArray($styleBold);
		$row++;
		$sheet->setCellValue($col.$row, "Basic Salary");
		$sheet->getStyle($col.$row)->applyFromArray($styleBold);
		$cellHeadStart = $col.$row;
		$col++;
		if($this->checkIfZeroTA($gData['frommonth'], $gData['tomonth'], $gData['fromyear'],$gData['toyear'],3,null, $gData['dept_id']) or $this->checkIfZeroTA($gData['frommonth'], $gData['tomonth'], $gData['fromyear'],$gData['toyear'],4,null,$gData['dept_id'])){
			$sheet->setCellValue($col.$row, "UT/Tardy");
			$col++;
		}
		
		if($this->checkIfZeroTA($gData['frommonth'], $gData['tomonth'], $gData['fromyear'],$gData['toyear'],1,null, $gData['dept_id'])){
			$sheet->setCellValue($col.$row, "Absences");
			$col++;
		}
		if($this->checkIfZero($gData['frommonth'], $gData['tomonth'], $gData['fromyear'],$gData['toyear'],16,null, $gData['dept_id'])){
			$sheet->setCellValue($col.$row, "Overtime");
			$col++;
		}
		if($this->checkIfZero($gData['frommonth'], $gData['tomonth'], $gData['fromyear'],$gData['toyear'],39,null, $gData['dept_id'])){
			$sheet->setCellValue($col.$row, "COLA");
			$col++;
		}
		$p = $this->getNonZeroPayElements($gData['frommonth'], $gData['tomonth'], $gData['fromyear'],$gData['toyear'], null, $gData['dept_id']);
		
		$eTemp = $this->filterPayElement(1);
		if(count($p) > 0){
			foreach($p as $tempKey => $tempVal){
				if(in_array($tempVal, $eTemp)){
					$earnings[] = $tempVal; 
				} else {
					$deductions[] = $tempVal;
				}
			}
		}
		if(count($earnings) > 0){
			foreach($earnings as $earnKey => $earnVal){
				$sheet->setCellValue($col.$row, $this->getPayElementNameById($earnVal));
				$col++;
			}
		}
		$sheet->setCellValue($col.$row, "Total Earnings");
		$sheet->getStyle($col.$row)->applyFromArray($styleBold);
		$col++;
		$row--;
		$sheet->setCellValue($col.$row, "Deductions");
		$sheet->getStyle($col.$row)->applyFromArray($styleBold);
		$row++;
		if(count($deductions) > 0){
			foreach($deductions as $dedKey => $dedVal){
				$sheet->setCellValue($col.$row, $this->getPayElementNameById($dedVal));
				$col++;
			}
		}
		$sheet->setCellValue($col.$row, "Total Deductions");
		$col++;
		$sheet->setCellValue($col.$row, "NET PAY");
		$sheet->getStyle($cellHeadStart.':'.$col.$row)->applyFromArray($styleBold);
		$sheet->getStyle($cellHeadStart.':'.$col.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sheet->getStyle($cellHeadStart.':'.$col.$row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$lastColumn = $col;
		$col = $start_col;
		$row++;
		if($gData['isdpart']){
			$dept = $this->getDepartment();
			for($c=0;$c<count($dept);$c++){
				$emp[] = $this->getEmployee($gData, $dept[$c]['ud_id']);
				$sheet->setCellValue($col.$row, $dept[$c]['ud_name']." - ".$dept[$c]['ud_desc']);
				$row++;
				$computeStartRow = $row;
				$startingCol = $col;
				$startingRow = $row;
				if(count($emp[$c]) > 0){
					for($empCount=0;$empCount<count($emp[$c]);$empCount++){
						$ms = $gData['frommonth'];
						$me = $gData['tomonth'];
						$ys = $gData['fromyear'];
						$me++;
						$str_date = $ms.' '.$ys;
						$compare = $me.' '.$gData['toyear'];
						
						// Initialize variables for total
						$basic = 0;
						$UT = 0;
						$Absences = 0;
						$OT = 0;
						$COLA = 0;
						$payElements = array();
						$seq = $count + 1;
						$seq = $empCount + 1;
						$sheet->setCellValue($col.$row, $seq);
						$col++;
						$sheet->setCellValue($col.$row, $emp[$c][$empCount]['emp_idnum']);
						$col++;
						$sheet->setCellValue($col.$row, $emp[$c][$empCount]['fullname']);
						$col++;
						$sheet->setCellValue($col.$row, $emp[$c][$empCount]['taxep_code']);
						$col++;
						$earnStartCol = $col;
						$endingCell = $col.$row;
						while($str_date != $compare){
							$basic += $this->getEntryRec($emp[$c][$empCount]['emp_id'], 1, $ms, $ys);
							if($this->checkIfZeroTA($gData['frommonth'], $gData['tomonth'], $gData['fromyear'],$gData['toyear'],3,null, $gData['dept_id']) or $this->checkIfZeroTA($gData['frommonth'], $gData['tomonth'], $gData['fromyear'],$gData['toyear'],4, null, $gData['dept_id'])){
								$UT += $this->toNegative($this->getTARec($emp[$c][$empCount]['emp_id'], 3, $ms, $ys)+$this->getTARec($emp[$c][$empCount]['emp_id'], 4, $ms, $ys));
							}
							if($this->checkIfZeroTA($gData['frommonth'], $gData['tomonth'], $gData['fromyear'],$gData['toyear'],1, null, $gData['dept_id'])){
								$Absences += $this->toNegative($this->getTARec($emp[$c][$empCount]['emp_id'], 1, $ms, $ys));
							}
							if($this->checkIfZero($gData['frommonth'], $gData['tomonth'], $gData['fromyear'],$gData['toyear'],16, null, $gData['dept_id'])){
								$OT += $this->getEntryRec($emp[$c][$empCount]['emp_id'], 16, $ms, $ys);
							}
							if($this->checkIfZero($gData['frommonth'], $gData['tomonth'], $gData['fromyear'],$gData['toyear'],39, null, $gData['dept_id'])){
								$COLA += $this->getEntryRec($emp[$c][$empCount]['emp_id'], 39, $ms, $ys);
							}
							if(count($earnings) > 0){
								foreach($earnings as $earnKey => $earnVal){
									$payElements[$earnVal] += $this->getEntryRec($emp[$c][$empCount]['emp_id'], $earnVal, $ms, $ys);
								}
							}
							if(count($deductions) > 0){
								foreach($deductions as $dedKey => $dedVal){
									$payElements[$dedVal] += $this->getEntryRec($emp[$c][$empCount]['emp_id'], $dedVal, $ms, $ys);
								}
							}
							if($ms == 12){
								$ms = 1;
								$ys++;
							} else {
								$ms++;
							}
							$str_date = $ms.' '.$ys;
						}
						// Basic
						$sheet->setCellValue($col.$row, $basic);
						$sheet->getColumnDimension($col)->setWidth(12);
						$col++;
						// UT/Tardy
						if($this->checkIfZeroTA($gData['frommonth'], $gData['tomonth'], $gData['fromyear'],$gData['toyear'],3,null, $gData['dept_id']) or $this->checkIfZeroTA($gData['frommonth'], $gData['tomonth'], $gData['fromyear'],$gData['toyear'],4, null, $gData['dept_id'])){
							$sheet->setCellValue($col.$row, $UT);
							$sheet->getColumnDimension($col)->setWidth(12);
							$col++;
						}
						// Absences
						if($this->checkIfZeroTA($gData['frommonth'], $gData['tomonth'], $gData['fromyear'],$gData['toyear'],1, null, $gData['dept_id'])){
							$sheet->setCellValue($col.$row, $Absences);
							$sheet->getColumnDimension($col)->setWidth(12);
							$col++;
						}
						// Overtime
						if($this->checkIfZero($gData['frommonth'], $gData['tomonth'], $gData['fromyear'],$gData['toyear'],16, null, $gData['dept_id'])){
							$sheet->setCellValue($col.$row, $OT);
							$sheet->getColumnDimension($col)->setWidth(12);
							$col++;
						}
						// COLA
						if($this->checkIfZero($gData['frommonth'], $gData['tomonth'], $gData['fromyear'],$gData['toyear'],39, null, $gData['dept_id'])){
							$sheet->setCellValue($col.$row, $COLA);
							$sheet->getColumnDimension($col)->setWidth(12);
							$col++;
						}
						if(count($earnings) > 0){
							foreach($earnings as $earnKey => $earnVal){
								$sheet->setCellValue($col.$row, $payElements[$earnVal]);
								$sheet->getColumnDimension($col)->setWidth(12);
								$earnLastCol = $col;
								$col++;
							}
						}
						$sheet->setCellValue($col.$row, "=sum(".$earnStartCol.$row.":".$earnLastCol.$row.")");
						$sheet->getColumnDimension($col)->setWidth(15);
						$earnCell = $col.$row;
						$col++;
						$dedStartCol = $col;
						if(count($deductions) > 0){
							foreach($deductions as $dedKey => $dedVal){
								$sheet->setCellValue($col.$row, $this->toNegative($payElements[$dedVal]));
								$sheet->getColumnDimension($col)->setWidth(12);
								$dedLastCol = $col;
								$col++;
							}
						}
						$sheet->setCellValue($col.$row, "=sum(".$dedStartCol.$row.":".$dedLastCol.$row.")");
						$sheet->getColumnDimension($col)->setWidth(15);
						$deductionCell = $col.$row;
						$col++;
						$sheet->setCellValue($col.$row, "=".$earnCell."+".$deductionCell);
						$sheet->getStyle($col.$row)->applyFromArray($styleBold);
						$sheet->getColumnDimension($col)->setWidth(15);
						$sheet->getStyle($earnStartCol.$row.":".$col.$row)->getNumberFormat()->setFormatCode('#,##0.00');
						$col = $start_col;
						$lastRow = $row;
						$row++;
					}
				} else {
					$sheet->setCellValue($col.$row, "No Records Found!");
					$col = $start_col;
					$lastRow = $row;
					$row++;
				}
				$sheet->setCellValue($col.$row, "Departmental Total");
				$sheet->getStyle($col.$row)->applyFromArray($styleBold);
				$sheet->getStyle($col.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);
				$sheet->getStyle($col.$row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$col++;
				$sheet->getStyle($col.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);
				$sheet->getStyle($col.$row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$col++;
				$sheet->setCellValue($col.$row, "(".$seq.")");
				$sheet->getStyle($col.$row)->applyFromArray($styleBold);
				$sheet->getStyle($col.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);
				$sheet->getStyle($col.$row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$grandSeq += $seq;
				$seq = 0;
				$col++;
				$sheet->getStyle($col.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);
				$sheet->getStyle($col.$row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$col++;
				$lastColumn_ =$lastColumn;
				$lastColumn_++;
				while($col != $lastColumn_){
					$sheet->setCellValue($col.$row, "=sum(".$col.$startingRow.":".$col.$lastRow.")");
					$sheet->getStyle($col.$row)->applyFromArray($styleBold);
					$sheet->getStyle($col.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);
					$sheet->getStyle($col.$row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$sheet->getStyle($col.$row)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
					$arrCells[$col][] = $col.$row;
					$col++;
				}
				$col = $start_col;
				$row++;
			}
				$row++;
				$sheet->setCellValue($col.$row, "Grand Total");
				$sheet->getStyle($col.$row)->applyFromArray($styleBold);
				$sheet->getStyle($col.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);
				$sheet->getStyle($col.$row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$col++;
				$sheet->getStyle($col.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);
				$sheet->getStyle($col.$row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$col++;
				$sheet->setCellValue($col.$row, "(".$grandSeq.")");
				$sheet->getStyle($col.$row)->applyFromArray($styleBold);
				$sheet->getStyle($col.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);
				$sheet->getStyle($col.$row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$col++;
				$sheet->getStyle($col.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);
				$sheet->getStyle($col.$row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$col++;
				while($col != $lastColumn_){
					$summation = (count($arrCells[$col])>0)?"= ".implode(" + ",$arrCells[$col]):"";
					$sheet->setCellValue($col.$row, $summation);
					$sheet->getStyle($col.$row)->applyFromArray($styleBold);
					$sheet->getStyle($col.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);
					$sheet->getStyle($col.$row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$sheet->getStyle($col.$row)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
					$col++;
				}
				$col = $start_col;
				$row++;
				$sheet->setCellValue($col.$row, "END OF REPORT");
				$sheet->getStyle($col.$row)->applyFromArray($styleBold);
		} else {
			$emp = $this->getEmployee($gData);
			$computeStartRow = $row;
			$startingCol = $col;
			$startingRow = $row;
			for($count=0;$count<count($emp);$count++){
				$ms = $gData['frommonth'];
				$me = $gData['tomonth'];
				$ys = $gData['fromyear'];
				$me++;
				$str_date = $ms.' '.$ys;
				$compare = $me.' '.$gData['toyear'];
				
				// Initialize variables for total
				$basic = 0;
				$UT = 0;
				$Absences = 0;
				$OT = 0;
				$COLA = 0;
				$payElements = array();
				$seq = $count + 1;
				$sheet->setCellValue($col.$row, $seq);
				$col++;
				$sheet->setCellValue($col.$row, $emp[$count]['emp_idnum']);
				$col++;
				$sheet->setCellValue($col.$row, $emp[$count]['fullname']);
				$col++;
				$sheet->setCellValue($col.$row, $emp[$count]['taxep_code']);
				$col++;
				$earnStartCol = $col;
				$endingCell = $col.$row;
				while($str_date != $compare){
					$basic += $this->getEntryRec($emp[$count]['emp_id'], 1, $ms, $ys);
					if($this->checkIfZeroTA($gData['frommonth'], $gData['tomonth'], $gData['fromyear'],$gData['toyear'],3,null, $gData['dept_id']) or $this->checkIfZeroTA($gData['frommonth'], $gData['tomonth'], $gData['fromyear'],$gData['toyear'],4, null, $gData['dept_id'])){
						$UT += $this->toNegative($this->getTARec($emp[$count]['emp_id'], 3, $ms, $ys)+$this->getTARec($emp[$count]['emp_id'], 4, $ms, $ys));
					}
					if($this->checkIfZeroTA($gData['frommonth'], $gData['tomonth'], $gData['fromyear'],$gData['toyear'],1, null, $gData['dept_id'])){
						$Absences += $this->toNegative($this->getTARec($emp[$count]['emp_id'], 1, $ms, $ys));
					}
					if($this->checkIfZero($gData['frommonth'], $gData['tomonth'], $gData['fromyear'],$gData['toyear'],16, null, $gData['dept_id'])){
						$OT += $this->getEntryRec($emp[$count]['emp_id'], 16, $ms, $ys);
					}
					if($this->checkIfZero($gData['frommonth'], $gData['tomonth'], $gData['fromyear'],$gData['toyear'],39, null, $gData['dept_id'])){
						$COLA += $this->getEntryRec($emp[$count]['emp_id'], 39, $ms, $ys);
					}
					if(count($earnings) > 0){
						foreach($earnings as $earnKey => $earnVal){
							$payElements[$earnVal] += $this->getEntryRec($emp[$count]['emp_id'], $earnVal, $ms, $ys);
						}
					}
					if(count($deductions) > 0){
						foreach($deductions as $dedKey => $dedVal){
							$payElements[$dedVal] += $this->getEntryRec($emp[$count]['emp_id'], $dedVal, $ms, $ys);
						}
					}
					if($ms == 12){
						$ms = 1;
						$ys++;
					} else {
						$ms++;
					}
					$str_date = $ms.' '.$ys;
				}
				// Basic
				$sheet->setCellValue($col.$row, $basic);
				$sheet->getColumnDimension($col)->setWidth(12);
				$col++;
				// UT/Tardy
				if($this->checkIfZeroTA($gData['frommonth'], $gData['tomonth'], $gData['fromyear'],$gData['toyear'],3,null, $gData['dept_id']) or $this->checkIfZeroTA($gData['frommonth'], $gData['tomonth'], $gData['fromyear'],$gData['toyear'],4, null, $gData['dept_id'])){
					$sheet->setCellValue($col.$row, $UT);
					$sheet->getColumnDimension($col)->setWidth(12);
					$col++;
				}
				// Absences
				if($this->checkIfZeroTA($gData['frommonth'], $gData['tomonth'], $gData['fromyear'],$gData['toyear'],1, null, $gData['dept_id'])){
					$sheet->setCellValue($col.$row, $Absences);
					$sheet->getColumnDimension($col)->setWidth(12);
					$col++;
				}
				// Overtime
				if($this->checkIfZero($gData['frommonth'], $gData['tomonth'], $gData['fromyear'],$gData['toyear'],16, null, $gData['dept_id'])){
					$sheet->setCellValue($col.$row, $OT);
					$sheet->getColumnDimension($col)->setWidth(12);
					$col++;
				}
				// COLA
				if($this->checkIfZero($gData['frommonth'], $gData['tomonth'], $gData['fromyear'],$gData['toyear'],39, null, $gData['dept_id'])){
					$sheet->setCellValue($col.$row, $COLA);
					$sheet->getColumnDimension($col)->setWidth(12);
					$col++;
				}
				if(count($earnings) > 0){
					foreach($earnings as $earnKey => $earnVal){
						$sheet->setCellValue($col.$row, $payElements[$earnVal]);
						$sheet->getColumnDimension($col)->setWidth(12);
						$earnLastCol = $col;
						$col++;
					}
				}
				$sheet->setCellValue($col.$row, "=sum(".$earnStartCol.$row.":".$earnLastCol.$row.")");
				$sheet->getColumnDimension($col)->setWidth(15);
				$earnCell = $col.$row;
				$col++;
				$dedStartCol = $col;
				if(count($deductions) > 0){
					foreach($deductions as $dedKey => $dedVal){
						$sheet->setCellValue($col.$row, $this->toNegative($payElements[$dedVal]));
						$sheet->getColumnDimension($col)->setWidth(12);
						$dedLastCol = $col;
						$col++;
					}
				}
				$sheet->setCellValue($col.$row, "=sum(".$dedStartCol.$row.":".$dedLastCol.$row.")");
				$sheet->getColumnDimension($col)->setWidth(15);
				$deductionCell = $col.$row;
				$col++;
				$sheet->setCellValue($col.$row, "=".$earnCell."+".$deductionCell);
				$sheet->getStyle($col.$row)->applyFromArray($styleBold);
				$sheet->getColumnDimension($col)->setWidth(15);
				$sheet->getStyle($earnStartCol.$row.":".$col.$row)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
				$lastColumn = $col;
				$col = $start_col;
				$lastRow = $row;
				$row++;
			}
		$row++;
		$sheet->setCellValue($col.$row, "TOTAL");
		$sheet->getStyle($col.$row)->applyFromArray($styleBold);
		$sheet->getStyle($col.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);
		$sheet->getStyle($col.$row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$col++;
		$sheet->getStyle($col.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);
		$sheet->getStyle($col.$row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$col++;
		$sheet->setCellValue($col.$row, "(".$seq.")");
		$sheet->getStyle($col.$row)->applyFromArray($styleBold);
		$sheet->getStyle($col.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);
		$sheet->getStyle($col.$row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$col++;
		$sheet->getStyle($col.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);
		$sheet->getStyle($col.$row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$col++;
		$lastColumn++;
		while($col != $lastColumn){
			$sheet->setCellValue($col.$row, "=sum(".$col.$startingRow.":".$col.$lastRow.")");
			$sheet->getStyle($col.$row)->applyFromArray($styleBold);
			$sheet->getStyle($col.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);
			$sheet->getStyle($col.$row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$sheet->getStyle($col.$row)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
			$col++;
		}
		$col = $start_col;
		$row++;
		$sheet->setCellValue($col.$row, "END OF REPORT");
		$sheet->getStyle($col.$row)->applyFromArray($styleBold);
		}
		//formatting of columns
		$sheet->getColumnDimension('C')->setWidth(25);
		// Rename sheet
		$sheet->setTitle($filename);
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);
		$sheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
		$sheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
		$sheet->getPageSetup()->setFitToPage(true);
		$sheet->getPageSetup()->setFitToWidth(1);
		$sheet->getPageSetup()->setFitToHeight(0);
		// Redirect output to a client's web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename='.$filename);
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
	}
	
	function getTARec($emp_id = null, $tatbl_id = null, $period = null, $year = null){
		$sql = "select sum(emp_tarec_amtperrate) as total from ta_emp_rec a
				inner join ta_tbl b on (b.tatbl_id=a.tatbl_id)
				inner join payroll_pay_period c on (c.payperiod_id=a.payperiod_id)
				where a.tatbl_id='$tatbl_id' 
				and a.emp_id='$emp_id' 
				and c.payperiod_period = $period
				and c.payperiod_period_year='$year'
				and a.paystub_id!=0";
		$rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF){
			if($rsResult->fields['total'] == ''){
				return 0.00;
			} else {
				return $rsResult->fields['total'];
			}
		}
	}
	
	function getEntryRec($emp_id = null, $psa_id = null, $period = null, $year = null){
		$loan_temp = array();
		$loan_temp = $this->getLoan();
		$loan = array();
		foreach($loan_temp as $key1 => $val1) {
			$loan[] = $val1['psa_id'];
		}
		$amendment_temp = array();
		$amendment_temp = $this->getAmendmentsPerEmp($emp_id, $period, $year);
		$amendment = array();
		foreach($amendment_temp as $key2 => $val2) {
			$amendment[] = $val2['psa_id'];
		}
		//printa($loan); printa($amendment); exit;
		/*if(in_array($psa_id, $loan) and !in_array($psa_id, $amendment)){
			$sql = "select sum(loansum_payment) as total from loan_detail_sum a
					inner join loan_info b on (b.loan_id=a.loan_id)
					inner join payroll_pay_stub c on (c.paystub_id=a.paystub_id)
					inner join payroll_pay_period d on (d.payperiod_id=c.payperiod_id)
					where b.psa_id='$psa_id' 
					and b.emp_id='$emp_id' 
					and d.payperiod_period = $period
					and d.payperiod_period_year='$year'";
		} elseif(in_array($psa_id, $amendment)){
			$sql = "select sum(amendemp_amount) as total from payroll_ps_amendemp a
					inner join payroll_ps_amendment b on (b.psamend_id=a.psamend_id)
					inner join payroll_pay_stub c on (c.paystub_id=a.paystub_id)
					inner join payroll_pay_period d on (d.payperiod_id=c.payperiod_id)
					where b.psa_id='$psa_id'
					and a.emp_id='$emp_id'
					and d.payperiod_period = $period
					and d.payperiod_period_year='$year'";
		} else {
			$sql = "select sum(ppe_amount) as total from payroll_paystub_entry a
					inner join payroll_pay_stub b on (b.paystub_id=a.paystub_id)
					inner join payroll_paystub_report c on (c.paystub_id=b.paystub_id)
					inner join payroll_pay_period d on (d.payperiod_id=c.payperiod_id)
					where a.psa_id='$psa_id' 
					and c.emp_id='$emp_id' 
					and d.payperiod_period = $period
					and d.payperiod_period_year='$year'";
		}*/
		$sql = "select loan+amm+entry as total FROM
		
				(select COALESCE(sum(loansum_payment),0) as loan from loan_detail_sum a
					inner join loan_info b on (b.loan_id=a.loan_id)
					inner join payroll_pay_stub c on (c.paystub_id=a.paystub_id)
					inner join payroll_pay_period d on (d.payperiod_id=c.payperiod_id)
					where b.psa_id='$psa_id' 
					and b.emp_id='$emp_id' 
					and d.payperiod_period = $period
					and d.payperiod_period_year='$year') loan_tbl,
					
				(select COALESCE(sum(amendemp_amount),0) as amm from payroll_ps_amendemp a
					inner join payroll_ps_amendment b on (b.psamend_id=a.psamend_id)
					inner join payroll_pay_stub c on (c.paystub_id=a.paystub_id)
					inner join payroll_pay_period d on (d.payperiod_id=c.payperiod_id)
					where b.psa_id='$psa_id'
					and a.emp_id='$emp_id'
					and d.payperiod_period = $period
					and d.payperiod_period_year='$year') amm_tbl,
					
				(select COALESCE(sum(ppe_amount),0) as entry from payroll_paystub_entry a
					inner join payroll_pay_stub b on (b.paystub_id=a.paystub_id)
					inner join payroll_paystub_report c on (c.paystub_id=b.paystub_id)
					inner join payroll_pay_period d on (d.payperiod_id=c.payperiod_id)
					where a.psa_id='$psa_id' 
					and c.emp_id='$emp_id' 
					and d.payperiod_period = $period
					and d.payperiod_period_year='$year') entry_tbl";
		$rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF){
			if($rsResult->fields['total'] == ''){
				return 0.00;
			} else {
				return $rsResult->fields['total'];
			}
		}
	}
	
	function getPayElementNameById($psa_id_ = null){
		$sql = "select psa_name from payroll_ps_account where psa_id='$psa_id_'";
		$rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF){
            return $rsResult->fields['psa_name'];
		}
	}
	
	function filterPayElement($psa_type_ = null){
		$sql = "select psa_id from payroll_ps_account where psa_type='$psa_type_' order by psa_order, psa_name";
		$rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF){
            $arrData[] = $rsResult->fields['psa_id'];
            $rsResult->MoveNext();
		}
        return $arrData;
	}
	
	function getEmployee($type = array(), $ud_id_ = null){
//		if($type['frommonth'] >= 10){
//			$start_m = $type['frommonth'];
//		} else {
//			$start_m = '0'.$type['frommonth'];
//		}
//		if($type['tomonth'] >= 10){
//			$end_m = $type['tomonth'];
//		} else {
//			$end_m = '0'.$type['tomonth'];
//		}
//		$start_y = $type['fromyear'];
//		$end_y = $type['toyear'];
//		$lastday = date('t',strtotime($end_m.'/1/'.$end_y));
//		
//		$startDate = $start_y.'-01-'.$start_m;
//		$endDate = $end_y.'-'.$lastday.'-'.$end_y;
		
		$start_ = $type['frommonth'];
		$end_ = $type['tomonth'];
		$year_start_ = $type['fromyear'];;
		$year_end_ = $type['toyear'];
		
        $qry = array();
		if(!empty($type['emp_id'])){
			$qry[] = "c.emp_id = ".$type['emp_id'];
		}
        if($type['emp_status'] != 0){
            $qry[] = "c.emp_stat =".$type['emp_status'];
        }
        // Department filter 
        if($type['dept_id'] == 0){
	    	if($type['isdpart'] == '1' && $ud_id_ != null){
	    		$qry[] = "g.ud_id='".$ud_id_."'";
				$strOrderBy = " order by g.ud_name,e.pi_lname";
			}else{
				$strOrderBy = " order by e.pi_lname";
			}
        } else {
        	$qry[] = "g.ud_id='".$type['dept_id']."'";
        }
		
//        $qry[] = "c.emp_stat in ('1','7')";
        $qry[] = "j.salaryinfo_isactive='1'";
//        $qry[] = "pperiod.payperiod_start_date >= '$startDate'";
//        $qry[] = "pperiod.payperiod_end_date <= '$endDate'";
		$qry[] = "(pperiod.payperiod_period >= $start_ AND pperiod.payperiod_period <= $end_)";
		$qry[] = "(pperiod.payperiod_period_year >= $year_start_ AND pperiod.payperiod_period_year <= $year_end_)";
        $criteria = (count($qry)>0)?" where ".implode(" and ",$qry):"";
        $sql = "select distinct c.emp_id, c.emp_idnum, CONCAT(e.pi_lname,', ',e.pi_fname,' ',concat(RPAD(e.pi_mname,1,' '),'.')) as fullname, 
				c.taxep_id,k.taxep_code,k.taxep_name, f.post_name, g.ud_id,g.ud_name, j.salaryinfo_id, j.salaryinfo_basicrate
                from emp_masterfile c
                join emp_personal_info e on (e.pi_id=c.pi_id)
                join emp_position f on (f.post_id=c.post_id)
                left join app_userdept g on (g.ud_id=c.ud_id)
                join salary_info j on (j.emp_id=c.emp_id)
                join payroll_pps_user pps on (c.emp_id = pps.emp_id)
                join tax_excep k on (k.taxep_id=c.taxep_id)
                join payroll_paystub_report preport on (preport.emp_id=c.emp_id)
                join payroll_pay_period pperiod on (pperiod.payperiod_id=preport.payperiod_id)
                $criteria
                $strOrderBy";
        $rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF){
			$arrData[] = $rsResult->fields;
            $rsResult->MoveNext();
		}
        return $arrData;
    }
    
 	function getDept(){
    	$arrData = array();
    	$sql = "select distinct ud_id from emp_masterfile";
    	$rsResult = $this->conn->Execute($sql);
    	while(!$rsResult->EOF){
			$arrData[] = $rsResult->fields;
            $rsResult->MoveNext();
		}
        return $arrData;
    }
    
	function getDepartment(){
		$sql = "Select * from app_userdept where ud_id != 1 order by ud_name";
		$rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF) {
			$arrData[] = $rsResult->fields;
			$rsResult->MoveNext();
		}
		return $arrData;
	}
	
	function getNonZeroPayElements($start_ = null, $end_ = null, $year_start_ = null, $year_end_ = null, $emp_id_ = null, $dept_id_ = null){
		$payElements = $this->getPayElements();
		foreach($payElements as $key => $val){
			if($this->checkIfZero($start_, $end_, $year_start_, $year_end_, $val['psa_id'], $emp_id_, $dept_id_)){
				$arrPayElement[] = $val['psa_id'];
			}
		}
		$amendments = $this->getAmendments();
		foreach($amendments as $key2 => $val2){
			if($this->checkIfZeroAmendment($start_, $end_, $year_start_, $year_end_, $val2['psa_id'], $emp_id_, $dept_id_)){
				$arrPayElement[] = $val2['psa_id'];
			}
		}
		$loan = $this->getLoan();
		foreach($loan as $key3 => $val3){
		if($this->checkIfZeroLoan($start_, $end_, $year_start_, $year_end_, $val3['psa_id'], $emp_id_, $dept_id_)){
				$arrPayElement[] = $val3['psa_id'];
			}
		}
		return $arrPayElement;
	}
	
	function getPayElements(){
		$arrData = array();
		$sql = "select psa_id from payroll_ps_account 
				where psa_type in ('1','2')
				and psa_clsfication not in ('5')
		 		order by psa_type,psa_order,psa_name";
		$rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF) {
			$arrData[] = $rsResult->fields;
			$rsResult->MoveNext();
		}
		return $arrData;
	}
	
	function getAmendments(){
		$arrData = array();
		$sql = "select distinct psa_id from payroll_ps_amendment";
		$rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF) {
			$arrData[] = $rsResult->fields;
			$rsResult->MoveNext();
		}
		return $arrData;
	}
	
	function getLoan(){
		$arrData = array();
		$sql = "select distinct psa_id from loan_info";
		$rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF) {
			$arrData[] = $rsResult->fields;
			$rsResult->MoveNext();
		}
		return $arrData;
	}
	
	function checkIfZeroLoan($start_ = null, $end_ = null, $year_start_ = null, $year_end_ = null, $psa_id_ = null, $emp_id_ = null, $dept_id_ = null){
        if($emp_id_ != null){
        	$qry[] = "b.emp_id=$emp_id_";
        }
        if($dept_id_ != null){
        	$qry[] = "e.ud_id=$dept_id_";
        }
//        $qry[] = "d.payperiod_period >= $start_";
//		$qry[] = "d.payperiod_period_year >= $year_start_";
//		$qry[] = "d.payperiod_period <= $end_";
//		$qry[] = "d.payperiod_period_year <= $year_end_";
		$qry[] = "(d.payperiod_period >= $start_ AND d.payperiod_period <= $end_)";
		$qry[] = "(d.payperiod_period_year >= $year_start_ AND d.payperiod_period_year <= $year_end_)";
		$qry[] = "b.psa_id='$psa_id_'";
		$criteria = count($qry)>0 ? " where ".implode(' and ',$qry) : '';
		
		$sql = "select sum(loansum_payment) as total from loan_detail_sum a
				inner join loan_info b on (b.loan_id=a.loan_id)
				inner join payroll_pay_stub c on (c.paystub_id=a.paystub_id)
				inner join payroll_pay_period d on (d.payperiod_id=c.payperiod_id)
				inner join emp_masterfile e on (e.emp_id=b.emp_id)
				$criteria";
				
		$rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF) {
			if($rsResult->fields['total'] == '0' or $rsResult->fields['total'] == null or $rsResult->fields['total'] == ''){
				return false;
			} else {
				return true;
			}
		}
	}
	
	function checkIfZeroAmendment($start_ = null, $end_ = null, $year_start_ = null, $year_end_ = null, $psa_id_ = null, $emp_id_ = null, $dept_id_ = null){
		if($emp_id_ != null){
        	$qry[] = "b.emp_id=$emp_id_";
        }
        if($dept_id_ != null){
        	$qry[] = "c.ud_id=$dept_id_";
        }
//        $qry[] = "e.payperiod_period >= $start_";
//		$qry[] = "e.payperiod_period_year >= $year_start_";
//		$qry[] = "e.payperiod_period <= '$end_'";
//		$qry[] = "e.payperiod_period_year <= $year_end_";
		$qry[] = "(e.payperiod_period >= $start_ AND e.payperiod_period <= $end_)";
		$qry[] = "(e.payperiod_period_year >= $year_start_ AND e.payperiod_period_year <= $year_end_)";
		$qry[] = "a.psa_id='$psa_id_'";
		$criteria = count($qry)>0 ? " where ".implode(' and ',$qry) : '';
		$sql = "select sum(b.amendemp_amount) as total from payroll_ps_amendment a
				inner join payroll_ps_amendemp b on (b.psamend_id=a.psamend_id)
				inner join emp_masterfile c on (c.emp_id=b.emp_id)
				inner join payroll_pay_stub d on (d.paystub_id=b.paystub_id)
				inner join payroll_pay_period e on (e.payperiod_id=d.payperiod_id)
				$criteria";
				
		$rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF) {
			if($rsResult->fields['total'] == '0' or $rsResult->fields['total'] == null or $rsResult->fields['total'] == ''){
				return false;
			} else {
				return true;
			}
		}
	}
	
	function checkIfZero($start_ = null, $end_ = null, $year_start_ = null, $year_end_ = null, $psa_id_ = null, $emp_id_ = null, $dept_id_ = null){
//		if($start_ >= 10){
//			$start_m = $start_;
//		} else {
//			$start_m = '0'.$start_;
//		}
//		if($end_ >= 10){
//			$end_m = $end_;
//		} else {
//			$end_m = '0'.$end_;
//		}
//		$start_y = $year_start_;
//		$end_y = $year_end_;
//		$lastday = date('t',strtotime($end_m.'/1/'.$end_y));
//		
//		$startDate = $start_y.$start_m.'-01-';
//		$endDate = $end_y.'-'.$end_m.'-'.$lastday;
		if($emp_id_ != null){
        	$qry[] = "d.emp_id=$emp_id_";
        }
        if($dept_id_ != null){
        	$qry[] = "e.ud_id=$dept_id_";
        }
//		$qry[] = "c.payperiod_start_date >= '$startDate'";
//		$qry[] = "c.payperiod_end_date <= '$endDate'";
		//$qry[] = "(c.payperiod_period >= ".$start_." and c.payperiod_period <= ".$end_.")";
		//$qry[] = "(c.payperiod_period_year >= ".$year_start_." and c.payperiod_period_year <= ".$year_end_.")";
		$qry[] = "(c.payperiod_period >= $start_ AND c.payperiod_period <= $end_)";
		$qry[] = "(c.payperiod_period_year >= $year_start_ AND c.payperiod_period_year <= $year_end_)";
		//$qry[] = "c.payperiod_period <= $end_";
		//$qry[] = "c.payperiod_period_year <= $year_end_";
		$qry[] = "a.psa_id=$psa_id_";
		$criteria = count($qry)>0 ? " where ".implode(' and ',$qry) : '';
		$sql = "select sum(a.ppe_amount) as total from payroll_paystub_entry a
				inner join payroll_pay_stub b on (b.paystub_id=a.paystub_id)
				inner join payroll_pay_period c on (c.payperiod_id=b.payperiod_id)
				inner join payroll_paystub_report d on (d.paystub_id=b.paystub_id)
				inner join emp_masterfile e on (e.emp_id=d.emp_id)
				$criteria";
		$rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF) {
			if($rsResult->fields['total'] == '0' or $rsResult->fields['total'] == null or $rsResult->fields['total'] == ''){
				return false;
			} else {
				return true;
			}
		}
	}
	
	function checkIfZeroTA($start_ = null, $end_ = null, $year_start_ = null, $year_end_ = null, $tatbl_id_ = null, $emp_id_ = null, $dept_id_ = null){
		if($emp_id_ != null){
        	$qry[] = "a.emp_id=$emp_id_";
        }
        if($dept_id_ != null){
        	$qry[] = "d.ud_id=$dept_id_";
        }
//		$qry[] = "c.payperiod_period >= $start_";
//		$qry[] = "c.payperiod_period_year >= $year_start_";
//		$qry[] = "c.payperiod_period <= $end_";
//		$qry[] = "c.payperiod_period_year <= $year_end_";
		$qry[] = "(c.payperiod_period >= $start_ AND c.payperiod_period <= $end_)";
		$qry[] = "(c.payperiod_period_year >= $year_start_ AND c.payperiod_period_year <= $year_end_)";
		$qry[] = "a.tatbl_id='$tatbl_id_'";
		$criteria = count($qry)>0 ? " where ".implode(' and ',$qry) : '';
		$sql = "select sum(emp_tarec_amtperrate) as total from ta_emp_rec a
				inner join ta_tbl b on (b.tatbl_id=a.tatbl_id)
				inner join payroll_pay_period c on (c.payperiod_id=a.payperiod_id)
				inner join emp_masterfile d on (d.emp_id=a.emp_id)
				$criteria";
		$rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF) {
			if($rsResult->fields['total'] == '0' or $rsResult->fields['total'] == null or $rsResult->fields['total'] == ''){
				return false;
			} else {
				return true;
			}
		}
	}
	
	function getCompanyDetails($comp_id_ = null){
		$sql = "select * from company_info where comp_id='$comp_id_'";
		$rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF) {
			return $rsResult->fields;
		}		
	}
	
	function toNegative($params_){
		if($params_ == 0){
			return 0;
		} else {
			return "-".$params_;
		}
	}
	
	function createPDF($content, $paper, $orientation, $filename){
		$dompdf = new DOMPDF();
		$dompdf->load_html($content);
		$dompdf->set_paper($paper,$orientation);
		$dompdf->render();
		$dompdf->stream($filename,array('Attachment' => 0));	
	}
	
	function getEmpStatus(){
		$sql = "select emp201status_id,emp201status_name from emp_201status order by  emp201status_name asc";
		$rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF) {
			$arr[] = $rsResult->fields;
			$rsResult->MoveNext();
		}
		return $arr;
	}
	
	function generateYTDReportSummary($gData = array(), $cData = array()){
		if(empty($gData['emp_id'])){
			$paramEmpID = "NULL";
		} else {
			$paramEmpID = $gData['emp_id'];
		}
		if(!isset($gData['dept_id'])){
			$paramDept = 0;
		} else {
			$paramDept = $gData['dept_id'];
		}
		if(empty($gData['isdpart'])){
			$paramGroup = 0;
		} else {
			$paramGroup = 1;
		}
		$filename = "YTDSummary_".$gData['frommonth'].$gData['fromyear']."_".$gData['tomonth'].$gData['toyear'].".xls"; // The file name you want any resulting file to be called.
    	// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		$objClsMngeDecimal = new Application();
		$finalDecFormat = $objClsMngeDecimal->setFinalDecimalPlaces(0);
		$objReader = PHPExcel_IOFactory::createReader('Excel5');
		$sheet = $objPHPExcel->getActiveSheet();
		$styleBold = array('font' => array('bold' => true));
		$start_col = "A";
		$col = $start_col;
		$row = 1;
		// Header
		$sheet->setCellValue($col.$row, $cData['comp_name']);
		$sheet->getStyle($col.$row)->applyFromArray($styleBold);
		$row++;
		$sheet->setCellValue($col.$row, $cData['comp_add']);
		$row++;
		$sheet->setCellValue($col.$row, "Year-To-Date Statistics Report");
		$row++;
		$sheet->setCellValue($col.$row, "From ".strtoupper(date( 'M', mktime(0, 0, 0, $gData['frommonth'])))." ".$gData['fromyear']." To ".strtoupper(date( 'M', mktime(0, 0, 0, $gData['tomonth'])))." ".$gData['toyear']);
		$row++;
		$row++;
		$sheet->setCellValue("E".$row, "Earnings");
		$sheet->getStyle("E".$row)->applyFromArray($styleBold);
		$row++;
		$cellHeadStart = $col.$row;
		$col++;
		$sheet->setCellValue($col.$row, "Emp ID");
		$col++;
		$sheet->setCellValue($col.$row, "Employee Name");
		$col++;
		$sheet->setCellValue($col.$row, "Tax Stat");
		$col++;
		$sheet->setCellValue($col.$row, "Basic Salary");
		$col++;
		$sheet->setCellValue($col.$row, "UT/Tardy");
		$col++;
		$sheet->setCellValue($col.$row, "Absences");
		$col++;
		$sheet->setCellValue($col.$row, "Overtime");
		$col++;
		$sheet->setCellValue($col.$row, "COLA");	
		$col++;
		$startHeadCol = $col;
		$headRow = $row;
		
		$row++;
		$recStartRow = $row;
		$recStartCol = 'A';
		$mysqli = Application::mysqli_connect(SYSCONFIG_DBNAME);
		$sql_sp = "CALL ytdSummary(".$gData['frommonth'].",".$gData['tomonth'].",".$gData['fromyear'].",".$gData['toyear'].",".$paramEmpID.",".$gData['emp_status'].",".$paramDept.",".$paramGroup.");";
		
		$arr = array();
		$row = $recStartRow;
		$col = $recStartCol;
		$count = 0;
		$empCount = 1;
		$oldDept = "";
		if($mysqli->multi_query($sql_sp)){
		$sql = "select count(*) as totalEmp from emp_vw";
		$rsResult = $this->conn->Execute($sql);
		$totalEmp = $rsResult->fields['totalEmp'];
		      do{
		         if($result = $mysqli->use_result()){
		            while($r = $result->fetch_row()){
		            $arr[] = $r;
		            if($paramGroup){
			            $sql = "select fetchDept('".$arr[$count][0]."') as deptName;";
						$rsResult = $this->conn->Execute($sql);
						$newDept = $rsResult->fields['deptName'];
						if($oldDept != "" and $newDept != $oldDept){
			            	$sheet->setCellValue("A".$row, "Departmental Total");
			            	$sheet->setCellValue("C".$row, "(".($empCount-1).")");
			            	$deptCol="E";
			            	$deptRow=$row-1;
			            	$sheet->getStyle("A".$row.":"."D".$row)->applyFromArray($styleBold);
							$sheet->getStyle("A".$row.":"."D".$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);
							$sheet->getStyle("A".$row.":"."D".$row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							while($deptCol != $endCol){
			            		$gTotal[$deptCol][] = $deptCol.$row;
			            		$sheet->setCellValue($deptCol.$row, "=sum(".$deptCol.$deptStart.":".$deptCol.$deptRow.")");
			            		$sheet->getStyle($deptCol.$row)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
			            		$sheet->getStyle($deptCol.$row)->applyFromArray($styleBold);
				            	$sheet->getStyle($deptCol.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);
								$sheet->getStyle($deptCol.$row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			            		$deptCol++;
			            	}
			            	$row++;
			            }
						if($newDept != $oldDept){
							$sheet->setCellValue($col.$row, $newDept);
							$row++;
							$deptStart=$row;
							$empCount = 1;
						}
						$oldDept = $newDept;
		            }
		            $sheet->setCellValue($col.$row, $empCount);
					$col++;
		            foreach($r as $cell){
		            	$sheet->setCellValue($col.$row, $cell);
		               	if($col > 'A' and $col < 'E'){
		               		$sheet->getColumnDimension($col)->setAutoSize(true);
		               	}
		               	if($col > 'D' or $col >= 'AA'){
		               		$sheet->getColumnDimension($col)->setWidth(15);
		               		$sheet->getStyle($col.$row)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
		               	}
		               	if($col == 'B'){
		               		$sheet->getStyle($col.$row)->getNumberFormat()->setFormatCode(str_repeat("0", strlen($cell)));
		               	}
		               	$endCol = $col;
		               	$endCol++;
		               	$col++;
		               }
		            }
		            $col = $recStartCol;
		            $endRow = $row;
		            $row++;
		            $count++;
		            $empCount++;
		            $result->close();
		         }
		      } while($mysqli->more_results() && $mysqli->next_result());
		   }
		   if($paramGroup){
			   if($count == $totalEmp){
			   		$sheet->setCellValue("A".$row, "Departmental Total");
				    $sheet->setCellValue("C".$row, "(".($empCount-1).")");
				    $deptCol="E";
				    $deptRow=$row-1;
				    $sheet->getStyle("A".$row.":"."D".$row)->applyFromArray($styleBold);
					$sheet->getStyle("A".$row.":"."D".$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);
					$sheet->getStyle("A".$row.":"."D".$row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

					while($deptCol != $endCol){
				    	$gTotal[$deptCol][] = $deptCol.$row;
				    	$sheet->setCellValue($deptCol.$row, "=sum(".$deptCol.$deptStart.":".$deptCol.$deptRow.")");
				        $sheet->getStyle($deptCol.$row)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
				        $sheet->getStyle($deptCol.$row)->applyFromArray($styleBold);
					    $sheet->getStyle($deptCol.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);
						$sheet->getStyle($deptCol.$row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				        $deptCol++;
				    }
				    $row++;
			   }
		   }
		if(count($arr) > 0){
			$row++;
			$cellFootStart = $col.$row;
			$sheet->setCellValue($col.$row, "Grand Total:");
			$col='C';
			$sheet->setCellValue($col.$row, "(".count($arr).")");
			$col='E';
			while($col != $endCol){
				if($paramGroup){
					$grandTotal = implode(",",$gTotal[$col]);
					$sheet->setCellValue($col.$row, "=sum(".$grandTotal.")");
				} else {
					$sheet->setCellValue($col.$row, "=sum(".$col.$recStartRow.":".$col.$endRow.")");
				}
				$sheet->getStyle($col.$row)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
				$endFootCol = $col;
				$col++;
			}
			$sheet->getStyle($cellFootStart.':'.$endFootCol.$row)->applyFromArray($styleBold);
			$sheet->getStyle($cellFootStart.':'.$endFootCol.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);
			$sheet->getStyle($cellFootStart.':'.$endFootCol.$row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$row++;
			$sheet->setCellValue("A".$row, "END OF REPORT");
			$sheet->getStyle("A".$row)->applyFromArray($styleBold);
			//$sheet->getStyle($endFootCol.$recStartRow.":".$endFootCol.$endRow)->applyFromArray($styleBold);
			$sheet->getStyle($endFootCol.$recStartRow.":".$endFootCol.$endRow)->getNumberFormat()->setFormatCode('#,##'.$finalDecFormat);
		// put headers
		$sql = "select psa_name from psa_vw where psa_type=1";
		$rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF) {
			$sheet->setCellValue($startHeadCol.$headRow, $rsResult->fields['psa_name']);	
			$startHeadCol++;
			$rsResult->MoveNext();
		}
		$sheet->setCellValue($startHeadCol.$headRow, "Total Earnings");
		$startHeadCol++;	
		$headRow--;
		$sheet->setCellValue($startHeadCol.$headRow, "Deductions");
		$sheet->getStyle($startHeadCol.$headRow)->applyFromArray($styleBold);
		$headRow++;
		$sql = "select psa_name from psa_vw where psa_type=2";
		$rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF) {
			$sheet->setCellValue($startHeadCol.$headRow, $rsResult->fields['psa_name']);	
			$startHeadCol++;
			$rsResult->MoveNext();
		}
		$sheet->setCellValue($startHeadCol.$headRow, "Total Deductions");
		$startHeadCol++;
		$sheet->setCellValue($startHeadCol.$headRow, "NET PAY");
		$sheet->getStyle($startHeadCol.$recStartRow.":".$startHeadCol.$endRow)->applyFromArray($styleBold);
		$startHeadCol++;	
		$headRow--;
		$sheet->setCellValue($startHeadCol.$headRow, "Employer's Shares");
		$sheet->getStyle($startHeadCol.$headRow)->applyFromArray($styleBold);
		$headRow++;	
		$sheet->setCellValue($startHeadCol.$headRow, "SSS ER");
		$startHeadCol++;
		$sheet->setCellValue($startHeadCol.$headRow, "SSS EC");
		$startHeadCol++;
		$sheet->setCellValue($startHeadCol.$headRow, "PHIC ER");
		$startHeadCol++;
		$sheet->setCellValue($startHeadCol.$headRow, "HDMF ER");
		$startHeadCol++;
		$sheet->getStyle($cellHeadStart.':'.$startHeadCol.$headRow)->applyFromArray($styleBold);
		$sheet->getStyle($cellHeadStart.':'.$startHeadCol.$headRow)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sheet->getStyle($cellHeadStart.':'.$startHeadCol.$headRow)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		// Rename Sheet
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
		} else {
			$_SESSION['eMsg'][] = "No Records Found!";
		}
	}
	
	function getAmendmentsPerEmp($emp_id = null, $period = null, $year= null){
		$sql = "select distinct psa_id from payroll_ps_amendemp a 
					join payroll_ps_amendment b on (b.psamend_id=a.psamend_id) 
					join payroll_pay_stub c on (c.paystub_id=a.paystub_id) 
					join payroll_pay_period d on (d.payperiod_id=c.payperiod_id) 
					where a.emp_id='$emp_id' 
					and d.payperiod_period = $period
					and d.payperiod_period_year='$year'";
		$rsResult = $this->conn->Execute($sql);
		$arrData = array();
		while(!$rsResult->EOF) {
			$arrData[] = $rsResult->fields;
			$rsResult->MoveNext();
		}
		return $arrData;
	}
}

?>