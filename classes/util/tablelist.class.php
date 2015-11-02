<?php

require_once(SYSCONFIG_CLASS_PATH."util/displaytable.class.php");

class clsTableList extends DisplayTable {
	
	var $conn;
	var $tblBlock;
	var $resultInfo;
	var $arrFields;
	var $tDisplayTable;
	var $linkPage;
	var $GET;
	var $trID;

	function clsTableList($dbconn_ = null) {
		$this->GET = $_GET;
		$this->conn =& $dbconn_;
		parent::DisplayTable($this->conn);
		$this->tblBlock = new BlockBasePHP();
		$this->tblBlock->templateFile = "table.tpl.php";
		$this->arrFields = array();
		$this->linkPage = "&p=@@";
		$this->pagenum = (isset($this->GET['p']))?$this->GET['p']:1;
	}
	
	function getTableList($attribs = array()) {
		
		if (empty($this->sqlAll) /*|| empty($this->sqlCount)*/) return "";
		
		$this->getAll();
		
		$this->resultInfo = $this->paginator->getPaginatorLine();
		$this->tblBlock->assign('trID',$this->trID);		
		$this->tblBlock->assign('attribs',$attribs);
		$this->tblBlock->assign('resultsInfo',$this->resultInfo);
		$this->tblBlock->assign('tblFields',$this->arrFields);
		$this->tblBlock->assign('tblData',$this->results);
		return $this->tblBlock->fetchBlock();
	}

	function comp(){
		$sql = "select comp_name,comp_id from company_info order by comp_name asc";
		$rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF){
			$compvalue[] = $rsResult->fields['comp_id'];
			$comp[] = $rsResult->fields['comp_name'];
			$rsResult->MoveNext();
		}
		$_SESSION['compvalue'] = $compvalue;
		return $comp;
	}
	
	function departments(){
		$sql = "select ud_id,ud_name from app_userdept where ud_id != 1 order by ud_name asc";
		$rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF){
			$deptvalue[] = $rsResult->fields['ud_id'];
			$dept[] = $rsResult->fields['ud_name'];
			$rsResult->MoveNext();
		}
		$_SESSION['deptvalue'] = $deptvalue;
		return $dept;
	}
	
	function position(){
		$sql = "select post_id,post_name from emp_position order by  post_name asc";
		$rsResult = $this->conn->Execute($sql);
		
		while(!$rsResult->EOF){
		$positionvalue[] = $rsResult->fields['post_id'];
		$position[] = $rsResult->fields['post_name'];
		$rsResult->MoveNext();
		}
		$_SESSION['positionvalue'] = $positionvalue;
		return $position;	
	}
	
	function location(){
		$sql = "select branchinfo_name from branch_info order by branchinfo_name asc";
		$rsResult = $this->conn->Execute($sql);
		while(!$rsResult->EOF){
			$branch[] = $rsResult->fields['branchinfo_name'];
			$rsResult->MoveNext();
		}
		return $branch;
	}
	
	function empstat(){
		$sql = "SELECT emp201status_id, emp201status_name FROM emp_201status ORDER BY  emp201status_name asc";
		$rsResult = $this->conn->Execute($sql);
		$empStatus = array();
		while ( !$rsResult->EOF ) {       	
			$empStatus[] = $rsResult->fields;        	
        	$rsResult->MoveNext();
        }
        return $empStatus;
	}
}


?>