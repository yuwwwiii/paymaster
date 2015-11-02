<?
require_once("adodb/adodb.inc.php");
class dbase{
	var $db;
	var $host;
	var $user;
	var $pass;
	var $database;
	
	/**
	 * @var ADOConnection
	 */
	var $dbconn;
	
	function  dbase($db, $host, $user, $pass, $database){
		$this->db=$db;
		$this->host=$host;
		$this->user=$user;
		$this->pass=$pass;
		$this->database=$database;

		$this->dbconn = NewADOConnection("$this->db");
		$this->dbconn->Connect("$this->host", "$this->user", "$this->pass", "$this->database");
	}
	
	function select($table, $name, $query){
		$i=0;
		$int=sizeof($name);
		// $dbase = NewADOConnection("$this->db");
		// $this->dbconn->Connect("$this->host", "$this->user", "$this->pass", "$this->database");
		$select="select * from $table $query";
		//$this->dbconn->debug=true;
		$result=$this->dbconn->Execute($select);
		while(!$result->EOF){
			for($j=0; $j<$int; $j++){
				$obj[$i][$j]=$result->fields[$name[$j]];
			}
			$result->MoveNext();
			$i++;
		}
		return $obj;
	}
	
	function showfields($table){
		$i=0;
		$int=sizeof($name);
		//$this->dbconn->Connect("$this->host", "$this->user", "$this->pass", "$this->database");
		$select="select * from $table";
		//print $select."<br>";
		$result=$this->dbconn->Execute($select)or die(mysql_error());
		$fldcount=$result->FieldCount();
		for ($x = 0; $x < $fldcount; $x++){
			$fetchfield = $result->FetchField($x);
			$obj[$x]=$fetchfield->name;
		}
		return $obj;
	}

	
	function select_distinct($table, $name, $query){
		$i=0;
		//$this->dbconn->Connect("$this->host", "$this->user", "$this->pass", "$this->database");
		$select="select distinct ".$name." from $table $query";
		//print $select."<br>";
		$result=$this->dbconn->Execute($select);
		
		while(!$result->EOF){
			$obj[$i]=$result->fields[$name];
			//print($obj[$i]);
			$result->MoveNext();
			$i++;
		}
		return $obj;
	}
	function insert($table, $values){
		$query="";
		//$dbase = NewADOConnection("$this->db");
		//$dbase->Connect("$this->host", "$this->user", "$this->pass", "$this->database");
		for($i=0; $i< sizeof($values); $i++){
			if($i==0){
				$query="'".$values[$i]."'";
				}
			else{
				$query=$query.","."'".$values[$i]."'";
				}
			}
		$insert="insert into $table values ( $query )";
		print($insert);
		
		$result=$this->dbconn->Execute($insert);
	}
	
	function insertAssoc($table, $values) {
		$cols = array();
		$vals = array();
		$qbind = array();
		foreach ( $values as $vkey => $vval ) {
			$cols[] = $vkey;
			$vals[] = $vval;
			$qbind[] = '?';
		}
		
		$strCols = implode(',',$cols);
		$strBind = implode(',',$qbind);
		$sqlinsert = "INSERT INTO {$table}({$strCols}) VALUES({$strBind})";
		$rs = $this->dbconn->Execute($sqlinsert,$vals);

		return $rs;
 	}
	
	function del($table, $query){
		//$dbase = NewADOConnection("$this->db");
		//$dbase->Connect("$this->host", "$this->user", "$this->pass", "$this->database");
		$delete="delete from $table $query";
		print($delete);
		$result=$this->dbconn->Execute($delete);
	}
	
	function update($table, $tablerow, $values, $query){
		//$dbase = NewADOConnection("$this->db");
		//$dbase->Connect("$this->host", "$this->user", "$this->pass", "$this->database");
		//print(sizeof($tablerow));
		for($i=0; $i<sizeof($tablerow); $i++){
			$tablerowt=$tablerow[$i];
			
			$valuest=$values[$tablerowt];
			
			if($valuest==NULL){
				if($tablerowt=='permit_issue_date'){
					if(($_POST['pi_yyyy']!=NULL)||($_POST['pi_mm']!=NULL)||($_POST['pi_dd']!=NULL))
						$valuest=$_POST['pi_yyyy']."-".$_POST['pi_mm']."-".$_POST['pi_dd'];
					else
						$valuest=$values[$tablerowt."1"];
				}elseif($tablerowt=='permit_exp_date'){
					if(($_POST['pe_yyyy']!=NULL)||($_POST['pe_mm']!=NULL)||($_POST['pe_dd']!=NULL))
						$valuest=$_POST['pe_yyyy']."-".$_POST['pe_mm']."-".$_POST['pe_dd'];
					else
						$valuest=$values[$tablerowt."1"];
				}
				else{
					$valuest=$values[$i];
				}
			}
			if($tablerowt=="password"){
				$valuest=md5($values[$tablerowt]);
			}
			$update="update $table set $tablerowt='$valuest' $query";
			print $update."<br>";
			$result=$this->dbconn->Execute($update);
		}
	}
	function aggregate($table, $name, $query, $fields){
		$i=0;
		$int=sizeof($name);
		//$dbase = NewADOConnection("$this->db");
		//$dbase->Connect("$this->host", "$this->user", "$this->pass", "$this->database");
		$select="select $fields from $table $query";
		$result=$this->dbconn->Execute($select);
		//echo $select."<br>";
		while(!$result->EOF){
			for($j=0; $j<$int; $j++){
			$obj[$i][$j]=$result->fields[$name[$j]];
			//print($obj[$i][$j]);
			}
			$result->MoveNext();
			$i++;
		}
		return $obj;
	}
	function auth($username, $password){
		$password=md5($password);
		//$dbase = NewADOConnection("$this->db");
		//$dbase->Connect("$this->host", "$this->user", "$this->pass", "$this->database");
		$select="select * from user where user_id='$username' and password='$password'";
		//print($select);
		$result=$this->dbconn->GetALL($select);
		$num=count($result);
		if($num!=0){
			//print($num);
		}
		$_SESSION['username']=$username;
		return $num;
	}
	
	function color($int,$c){
		for($i=0;$i<=$int; $i++){
			if($i%2=='0'){
				$kulay[$i]=$c[0];
				}
			else{
				$kulay[$i]=$c[1];
				}
		}
		return $kulay;
	}
}
?>
