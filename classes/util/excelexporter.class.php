<?php
require_once(SYSCONFIG_CLASS_PATH.'util/phpsimplexlsgen.class.php');


class ExcelExporter extends PhpSimpleXlsGen {

	var $xlsData;
	var $arrFields;
	
	function ExcelExporter() {
		parent::PhpSimpleXlsGen();
		$this->xlsData = array();
		$this->arrFields = array();
	}
	
	/**
	 * Append 1 row data array
	 * 
	 * $arrSampleData[] = array(
	 * 	'id' => 2
	 * 	,'name' => 'juan dela cruz'
	 * 	,'address' => 'the quick brown'
	 * 	,'age' => 28
	 * );
	 *
	 * same as 
	 * 
	 * $dbconn->fields data array result
	 * 
	 * @param array $arrParam_
	 */
	function appendData($arrParam_ = array()){
		if (count($arrParam_) == 0) {
			return;
		}
		$this->xlsData[] = $arrParam_;
	}
	
	/**
	 * set display field
	 *
	 * $arrSampleFieldList = array(
	 * 	 'id' => array('name' => 'ID','type' => 'int')
	 * 	,'name' => array('name' => 'Employee Name')
	 * 	,'address' => array('name' => 'Address')
	 * 	,'age' => array('name' => 'Age','type'=>1)
	 * );
	 * 
	 * @param array $arrFlds_
	 */
	function setFieldArray($arrFlds_ = array()){
		$this->arrFields = $arrFlds_;
	}
	
	/**
	 * export result into excel file format
	 *
	 * @param string $exportfilename_
	 * @return boolean
	 */
	function exportExcel($exportfilename_ = null){
		
		if (is_null($exportfilename_)) {
			return false;
		}else {
			$this->filename = $exportfilename_;
		}
		
		if (count($this->xlsData) == 0) {
			return false;
		}
		
		if (count($this->arrFields) == 0) {
			return false;
		}
		
		$this->totalcol = count($this->arrFields);
		
		foreach ($this->arrFields as $fldKey => $fldValue) {
			$this->InsertText($fldValue['name']);
		}
		
		$this->NewLine();
		
		foreach ($this->xlsData as $xlsDataKey => $xlsDataValue) {
			foreach ($this->arrFields as $fldKey => $fldValue) {
				if (isset($fldValue['type'])) {
					if ($fldValue['type'] == 'int' || $fldValue['type'] == 1) {
						$this->InsertNumber($xlsDataValue[$fldKey]);
					}else { // text
						$this->InsertText($xlsDataValue[$fldKey]);
					}
				}else { // text by default
					$this->InsertText($xlsDataValue[$fldKey]);
				}
			}
			$this->NewLine();
		}
		
		$this->SendFile();
		return true;
	}
	
}


?>