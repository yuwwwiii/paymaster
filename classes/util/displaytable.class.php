<?php

/**
 * display table class. part of the extensible framework.
 *
 * $Id: displaytable.class.php 12 2005-05-15 13:45:36Z aljo $
 *
 * @author aljo fabro <acfabro@gmail.com>
 * @package extension
 */

require_once('paginator.class.php');

/**
 * DisplayTable is an abstract class used to create display/search result tables
 * @abstract
 */
class DisplayTable {
	
	/**
	 * Search parameters used for search
	 * @var array Associative array of parameters
	 */
	var $parameters;
	
	var $starttime;
	var $endtime;
	var $totaltime;
	
	/**
	 * Paginator used for display
	 * @var Paginator
	 */
	var $paginator;
	
	/**
	 * sets the current page number for table
	 * @var int
	 */
	var $pagenum;
	
	/**
	 * Search results
	 * @var array Array of search results of type mixed
	 */
	var $results;
	
	/**
	 * number of search results
	 * @var int
	 */
	var $resultsCount;
	
	/**
	 * Number of pages in complete data set
	 * @var int
	 */
	var $pagesCount;
	
	/**
	 * Sql statement for counting the complete result set
	 * @var string
	 */
	var $sqlCount;
	var $sqlCountParams;
	
	/**
	 * Sql statement for selecting the data in the page
	 * @var string
	 */
	var $sqlAll;
	var $sqlAllParams;
	
	/**
	 * ADORecordSet for the sql get all
	 * @var ADORecordSet
	 */
	var $rsGetAll;
	
	/**
	 * DB connection
	 * @var ADOConnection
	 */
	var $conn;
	var $bypassSqlCalcFoundRows = false;
	
	/**
	 * Contructor initializes values
	 * @return DisplayTable
	 */
	function DisplayTable(&$conn) {
		$this->parameters = array();
		$this->paginator = new Paginator();
		$this->results = array();
		$this->resultsCount = 0;
		$this->pagesCount = 1;
		$this->pagenum = 1;
		
		$this->sqlCountParams = array();
		$this->sqlAllParams = array();
		$this->conn = $conn;
	}
	
	/**
	 * Sets a search parameter
	 * @param string $pname Paameter name
	 * @param mixed $pval Prameter value
	 * @return bool
	 */
	function setParam( $pname, $pval ) {
		if ( !$pname ) return false;
		$this->parameters[$pname] = $pval;
		return true;
	}

	/**
	 * retreives a search parameter
	 * @param string $pname Parameter name
	 * @return mixed
	 */
	function getParam( $pname ) {
		return isset($this->parameters[$pname])?
		       $this->parameters[$pname]:null;
	}
	
	/**
	 * Adds a result record to the array of results set
	 * @param mixed $resultitem Result record of any type and value, except null
	 * @return bool
	 */
	function addResult( $resultitem ) {
		if ( $resultitem == null ) return false;
		$this->results[] = $resultitem;
		return true;
	}
	
	/**
	 * Sets the number of pages from the number of result rows
	 * @param int $newCount The number of result items
	 */
	function setResultsCount($newCount) {
		$this->resultsCount = $newCount;
		$this->pagesCount = ceil($this->resultsCount/$this->paginator->recsperpage);	
	}
	
	/**
	 * Implement this method to initialize your sql statements;
	 * @abstract 
	 */
	function initSQL() {
		// use getParam to init your sqls
	}
	
	/**
	 * executes the sql for count and retrieval
	 */
	function execGetAll() {
		
		$this->starttime = microtime(true);
		
		// execute the sql for counting
//		$rsetGetCount = $this->conn->Execute($this->sqlCount,$this->sqlCountParams);
//		$this->setResultsCount($rsetGetCount->fields['mycount']);
		
		// process query for rows count by adding SQL_CALC_FOUND_ROWS option in the SELECT to obtain the row count
		// even using a LIMIT
		if(!$this->bypassSqlCalcFoundRows){
			$this->sqlAll = trim($this->sqlAll);
			$this->sqlAll = "SELECT SQL_CALC_FOUND_ROWS " . substr($this->sqlAll,7,strlen($this->sqlAll));
		}else{
			$rsetGetCount = $this->conn->Execute($this->sqlCount,$this->sqlCountParams);
			$this->setResultsCount($rsetGetCount->fields['mycount']);
		}
	
		// execute the sql for retrieval
		$rsGetAll = $this->conn->SelectLimit( $this->sqlAll,
                                         $this->paginator->recsperpage, 
                                         ($this->pagenum-1)*$this->paginator->recsperpage,
                                         $this->sqlAllParams );
        if ( $rsGetAll===false ) return false;
		$this->rsGetAll =& $rsGetAll;
		
		if (!$this->bypassSqlCalcFoundRows) {
			//bby: revised getting result count
			$rsetGetCount = $this->conn->Execute("select FOUND_ROWS() as mycount");
			$this->setResultsCount($rsetGetCount->fields['mycount']);
		}

		$this->endtime = microtime(true);
		
		$this->totaltime = 	number_format(($this->endtime - $this->starttime),2);
	}
	
	/**
	 * Implement your own results gathering routine. tip: get $this->reGetAll
	 *
	 */
	function gatherResults() {
		if(count($this->rsGetAll) > 0){
			while ( !$this->rsGetAll->EOF ) {
	        	$cResult = $this->rsGetAll->fields;
	        	$this->addResult( $cResult );
	        	$this->rsGetAll->MoveNext();	
	        }
		}
		$this->paginator->numrecords = $this->resultsCount;
        $this->paginator->pagenum = $this->pagenum;
        return true;
	}
	
	function getAll() {
		$this->conn->SetFetchMode(ADODB_FETCH_ASSOC);
		$this->initSQL();
		$this->execGetAll();
		$this->gatherResults();
        return true;
	}
}
?>
