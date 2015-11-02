<?php
class epay_general_functions
{		/*
		topTable($a,$b)
		$a: Array{'Table Name','Primary Key Field','Primary Key Value'}
		$b: Array{'List of Field Names of $a Table'}
		*/
		
		
		function topTable($p1,$p2,$p3='',$arrFld, $type='')
		{
			if(!empty($type)) $this->alert('Type is not empty');
			$id = "$p2 = '$p3'";
			if(!empty($p3))
			{
			$q = mysql_query("select * from ".$p1." ".$type." where ".$id."");
			}
			
			else{
			$q = mysql_query("select * from ".$p1." order by ".$p2." asc LIMIT 0,1");
			}
			
			$query = mysql_fetch_array($q,MYSQL_ASSOC);
		
			foreach($arrFld as $val)
			{ 
				$this->fillValues($val,$query[$val]); 
			}			
			$_SESSION['cancel']=$cancel[$p1];
		}		
		/*
		fillValues($a,$b)
		$a: Array{'List of Field Names in Top Table'}
		$b: String: Leave blank if to fill empty input fields
		*/		
		function fillValues($fld,$value='')
		{	
			if(is_array($fld))
			{
				foreach($fld as $name){
				print "document.getElementById('".$name."').value='".addslashes($value)."';"; 
				}
			}			
			else
			{
				print "document.getElementById('".$fld."').value='".addslashes($value)."';"; 
			}
		}	

		
		/*
		baseTable($base_tbl,$base_fk,$nextID,$base_fld,$base_pk);
		*/
		function baseTable($p1,$p2,$p3='',$arrFld,$base_pk='')
		{
		if(!empty($p2))	$q = mysql_query("select * from ".$p1." where ".$p2."='".$p3."'");
		else 
		$q = mysql_query("select * from ".$p1." order by ".$base_pk." asc");
		$str .="<table width=100% border=0 cellspacing=1 cellpadding=0 id=tbl_list><tr><td class=divTblListTH >Action</td>";
		foreach($arrFld as $val => $title)
			{
				$str .="<td class=divTblListTH >".$title."</td>";
			}
			$str .="</tr>";
		 while($query = mysql_fetch_array($q,MYSQL_ASSOC))
		 {
		$jEdit = "<img  onclick=\"jah(\'\',\'table_id=".$p3."&base_id=".$query[$base_pk]."&action=edit_base_table\',true);document.getElementById(\\'base_id\\').value=\\'".$query[$base_pk]."\\';\" src=\"external/images/edit.png\" title=\"Edit\" hspace=\"2px\" border=0 width=\"16\" height=\"16\" style=\"cursor:hand;\">";
		$jDelete  = "<img  onclick=\"if(confirm(\'Are you sure you want to delete?\'))jah(\'\',\'table_id=".$p3."&base_id=".$query[$base_pk]."&action=delete_base_table\',true);\" src=\"external/images/delete.png\" title=\"Edit\" hspace=\"2px\" border=0 width=\"16\" height=\"16\" style=\"cursor:hand;\">";
	
		$str .= "<tr class=divTblListTR id=".$query[$base_pk]."><td class=divTblListTD width=50 align=center>".$jEdit.$jDelete."</td>";
		 foreach($arrFld as $val => $title)
			{
			$str .= "<td class=divTblListTD id=".$query[$base_pk]."_".$val." >".$query[$val]."</td>";
			}
			$str .=  "</tr>";
		 }
		$str .= (mysql_num_rows($q)<1)?'<tr class=divTblListTR id=norecord><td colspan=100% class=divTblListTD >No record found.</td></tr>':'';
		$str .= "</table>";
		print "document.getElementById('update_tbl_list').innerHTML='".$str."';";
		
		}
		/* Insert New Option in Top Table*/
		function newOption($selectID,$text,$id)
		{
		print "var newOption = document.createElement('OPTION');";
		print "document.getElementById('".$selectID."').appendChild(newOption);";
		print "newOption.id = 'opt_".$id."';";
		print "newOption.value = '".$id."';";
		print "newOption.selected = 'selected';";
		print "newOption.text = '".$text."';";
		}	
		
		/* Insert Row in Base Table*/
		function newRow($fld,$id,$tbl='',$fldName,$jEdit,$jDelete,$fkey='',$code='')
		{
			$where = !empty($fkey)?" where ".$fkey."='".$code."'":"";
			$rc = $this->countRows($tbl,$where);
			if($rc==1)
			{//remove no record found
			print "document.getElementById('tbl_list').deleteRow(1);";
			}
			
			print "
			var tbl=document.getElementById('tbl_list').insertRow(1);tbl.className = 'divTblListTR';tbl.id = '".$id."';	var c1=tbl.insertCell(0);c1.align = 'center'; c1.width = '50px';";
			print "c1.innerHTML='".$jEdit.$jDelete."';";
			$x=2; $y=1; $z=0;
			foreach($fld as $value){
			print "var c".$x."=tbl.insertCell (".$y.");"; 
			print "c".$x.".id='".$id."_".$fldName[$z]."';";
			print "c".$x.".innerHTML='".str_replace("'","",$value)."';";
			$x++; $y++; $z++;
			}
			
		
		}
		/* Remove option */
		function removeOption($selectId)
		{
		print "var x=document.getElementById('".$selectId."');x.remove(x.selectedIndex);";
		}
		
		function newSelect($select)
		{
		print"
		var i;	sel= document.getElementById('".$select."'); for(i=-1; i<sel.length; i++){ sel.options[i]=null; }sel.options.selectedIndex = 0; 
		";
		}
		
		
		function removeOptions($selectbox)
		{
		print "
		var selectbox = document.getElementById('".$selectbox."');
		var i;
		for(i=selectbox.options.length-1;i>=0;i--)
		{
		selectbox.remove(i);
		}";
		}
		
		function createOptions($selectbox,$code='',$tbl='')
		{
			$this->alert($code);
			//$this->removeOptions($selectbox);
			if(mysql_num_rows($q = mysql_query("select * from ".$tbl." where ".$selectbox."='".$code."'"))>0)
			{	$x=0;
				while($f = mysql_fetch_array($q,MYSQL_ASSOC))
				{
				$this->newOptions($selectbox,$x);
				$x++;
				}
			}
				
		}

		function newOptions($selectbox,$x)
		{
		print "var newOption".$x." = document.createElement('OPTION');";
		print "document.getElementById('".$selectbox."').appendChild(newOption);";
		print "newOption".$x.".id = 'opt_".$x."';";
		print "newOption".$x.".value = '".$x."';";
		print "newOption".$x.".text = '".$text."';";		
		}
		
		
		/*
		insertData($a,$b,$c)
		$a: String-Table Name
		$b: String from Array Imploded by ,
		$c: String from Array Imploded by ,:Should be enclosed in ''
		*/	
		function insertData($tbl,$columns,$values)
		{
		return mysql_query("insert into ".$tbl." (".$columns.") values (".$values.");");
		}
		/*
		Update query
		*/
		function updateData($tbl,$pKey,$key,$fld)
		{
		return mysql_query("update ".$tbl." set ".implode(',',$fld)." where ".$pKey."='".$key."'");
		}
		
		/*
		editValues($a,$b,$c)
		*/		
		function editValues($key,$fld,$tbl,$id,$su='')
		{	
			$q = mysql_query("select ".implode(',',$fld)." from ".$tbl." where ".$key."='".$id."'");
			$query = mysql_fetch_array($q,MYSQL_ASSOC);
			
			foreach($fld as $name){
				$this->dom($name,'value',$query[$name]);
				}
			if(is_array($su))
			{			
			$this->dom($su[0],'style.display','none');
			$this->dom($su[1],'style.display','inline');
			$this->dom($su[1],'disabled',false);
			}
		}
		
		/*
		Delete
		*/
		function deleteData($tbl,$code,$pKey=''){
		$fld = empty($pKey)?$_SESSION['prim_key']:$pKey;
		return mysql_query("delete from ".$tbl." where ".$fld."='".$code."'");		
		}
		
		
		/*
		Use this when setting/imploding values for update queries
		*/
		function setValues($fld,$values){
			$x=0;
			foreach($fld as $name)
			{
			$update[] = $name."=".$values[$x]; 
			$x++;
			}
			return $update;
		
		}
		/*
		Error handler
		*/
		function getErrors($fld)
		{			
			$num = $this->numeric($fld);
			foreach($fld as $q => $title){
			$this->dom($q,'style.border','1px solid #c0c0c0');
			if((in_array($q,$num) AND !is_numeric($_POST[$q])) OR trim($_POST[$q])==''){ $error[]=$title; $this->dom($q,'style.border','1px solid #FF0000');  }
		}
		return $error;
		}
		
		function numeric($fld)
		{
		return $fld;		
		}
		/*
		This function is use to construct POST Variables
		*/
		function js_param($fld)
		{
			foreach($fld as $input => $title){
			$js_param[] = $input."=' + document.getElementById('".$input."').value";
			}
			return " ' ".implode(" + '&",$js_param);
		}
		/*
		Create new session
		*/
		function updateSession($tbl,$pKey)
		{
			return $this->newSession($tbl,$pKey);
		}
		
		function newSession($tbl,$pKey)
		{
			unset($_SESSION['full_desc']);
			$q = mysql_query("select * from ".$tbl." order by ".$pKey);
			if(mysql_num_rows($q)>0)
			{
			
			  while($val = mysql_fetch_array($q,MYSQL_ASSOC))
			  {
			   $_SESSION['full_desc'][] =  $val;
			
			  }
			}
			return $_SESSION['full_desc'];
		}
		
		function alert($msg)
		{
		return print "alert('".$msg."');";
		}
		
		function ifelse($arg,$true,$false)
		{
			$result = $arg?$true:$false;
			print "alert('".$result."');";
		}
		
		function dom($id,$att,$value)
		{
		print "document.getElementById('".$id."').".$att."='".$value."';";		
		}
		
		function countRows($tbl,$where='')
		{
		return mysql_num_rows(mysql_query("select * from ".$tbl.$where));
		
		}
		
		function post($name)
		{
		return print "alert('".$_POST[$name]."');";
		
		}
		
		function getID($tbl,$key){
			$q = mysql_query("select * from ".$tbl." order by ".$key." asc LIMIT 0,1");
			$query = mysql_fetch_array($q,MYSQL_ASSOC);
			if($query[$key]){ return $query[$key]; }else{ return false;}
		}
		
		function interchange($from,$to)
		{
		print "document.getElementById('".$from."').style.display='none';";
		print "document.getElementById('".$to."').style.display='block';";
		}
		
		function emptyCheck($tbl)
		{
		if($this->countRows($tbl)>0)
		{
		return true;
		}
		
		else
		{
		$this->fillValues('table_id');
		$this->dom('base_save','disabled',true);
		$this->dom('base_update','disabled',true);
		$this->dom('addNew','disabled',true);
		$this->dom('del','disabled',true);
		$this->dom('update','disabled',true);
		$this->dom('save','disabled',true);
		$this->dom('cancel','disabled',true);
		$this->dom('new','disabled',false);
		}
		
		}
		
		function postVars($fld,$useKeys=false)
		{
			if($useKeys)
			{
				foreach($fld as $name => $title){
				$values[] = "'".$_POST[$name]."'";
				}
			}
			
			else
			{
				foreach($fld as $name){
				$values[] = "'".$_POST[$name]."'";
				}
			}
			
			return $values;
		}
		
		function no_record_found()
		{
		print "
		var tbl=document.getElementById('tbl_list').insertRow(1);
		tbl.className = 'divTblListTR';
		var c1=tbl.insertCell(0);
		c1.innerHTML='No record found.';	
		c1.colSpan = '100%';		
		";
		}
		
		function getKey($id,$search)
		{	$x=0;
			foreach($_SESSION['full_desc'] as $array)
			{
			if($array[$id]==$search)
			{
			return $x;
			break;
			}
			$x++;
			}
		}
		
		function misc($type='',$code)
		{
		if($type=='select' AND $_SESSION['topSelector'])
		{
		$i = $_SESSION['topSelector'][0];	
		$select = $_SESSION['full_desc'][$code][$i];
		$this->dom($i.'_'.$select,'selected',true);
		}

		}
		
		
		
		
		
		
		
		
		
}

?>