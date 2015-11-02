<?php
$p = new epay_general_functions;
$restriction = $process?'continue':exit;
//DONT NA EDIT BELOW
unset($postVars,$t,$r,$nc);
$nc = array('_tbl','_pk','base_tbl','base_pk','base_fk');
$f=0;
foreach($nc as $cTbl)
{
${$cTbl} = $_SESSION['cTbl'][$f];
$f++;
}

$top_fld = $_SESSION['top_fld'];
$base_fld = $_SESSION['base_fld'];

$sel3 = $_POST['sel3'];
$action = $_POST['action'];
$code = $_POST['table_id'];
$base_id = $_POST['base_id'];
	

$id = $_SESSION['full_desc'][$code][$_pk];
$_SESSION['id'] = empty($id)?0:$id;
$error = "";	

//$p->alert($sel3);


//foreach($top_fld as $data)$p->alert($data);
//exit;


switch($action){	
/*BEGIN Add,Edit,Delete for Base Table Data*/
		
	case 'new_base_table': //ok
		$p->dom('base_save','disabled',false);
		$p->dom('base_update','disabled',false);
		$p->dom('base_update','style.display','none');
		$p->dom('base_save','style.display','inline');
		$p->fillValues(array_keys($base_fld));
		$p->emptyCheck($_tbl);
		break;
	
	case 'edit_base_table': //ok
		$saveUpdate = array('base_save','base_update');
		if(empty($base_fk)){	
		$p->editValues($_pk,$top_fld,$_tbl,$base_id,'');
		$p->fillValues('table_id',$base_id);
		$p->dom('opt_'.$p->getKey($_pk,$base_id),'selected',true);
		}
		else $p->editValues($base_pk,array_keys($base_fld),$base_tbl,$base_id,$saveUpdate);
		break;
	
	case 'delete_base_table': //ok

		$delete = $p->deleteData($base_tbl,$base_id,$base_pk);
		if($delete){
		$p->dom($base_id,'style.display','none');
		if(empty($base_fk)){
		$nextID = $p->getID($_tbl,$_pk);
		$p->fillValues('table_id',$nextID);
		$p->topTable($_tbl,$_pk,'',$top_fld);
		$rc = $p->countRows($_tbl);
		$p->removeOption('selector');
		$p->dom('selector','options.selectedIndex','0');		
		}
		else{
		$rc = $p->countRows($base_tbl," where ".$base_fk."='".$code."'");
		$p->fillValues(array_keys($base_fld));
		}
		
		if($rc==0)
		{
		//$p->no_record_found();
		}
		$p->emptyCheck($_tbl);
		$p->alert('Successfully deleted.');
		}else $p->alert('Delete failed.');
		break;
		
	case 'update_base_table': //ok
		$bv = $p->postVars($base_fld,true);
		$vars   = $p->setValues(array_keys($base_fld),$bv);
		$update = $p->updateData($base_tbl,$base_pk,$base_id,$vars);
		if($update){
		$x=0;
		foreach($base_fld as $value => $title){
			$p->dom($base_id."_".$value,'innerHTML',str_replace("'","",$bv[$x]));
			$x++;}
		$p->dom('saving','style.display','none');	
		$p->alert('Successfully updated.');
		}else $p->alert('Update failed.');
		break;

	
	case 'save_base_table';	//ok
		$error  = $p->getErrors($base_fld);
		//if(!is_array($error))
		//{
		$insert = $p->insertData($base_tbl,$base_fk.','.implode(",",array_keys($base_fld)),"'".$code."',".implode(",",$p->postVars($base_fld,true)));
			if($insert)
			{
			$p->interchange('base_save','base_update');
			$p->dom('base_save','disabled',false);
			$p->dom('base_update','disabled',false);
			$jEdit = "<img  onclick=\"jah(\'\',\'base_id=".mysql_insert_id()."&action=edit_base_table\',true);document.getElementById(\\'base_id\\').value=\\'".mysql_insert_id()."\\';\" src=\"external/images/edit.png\" title=\"Edit\" hspace=\"2px\" border=0 width=\"16\" height=\"16\" style=\"cursor:hand;\">";
			$jDelete  = "<img  onclick=\"if(confirm(\'Are you sure you want to delete?\'))jah(\'\',\'base_id=".mysql_insert_id()."&action=delete_base_table\',true);\" src=\"external/images/delete.png\" title=\"Edit\" hspace=\"2px\" border=0 width=\"16\" height=\"16\" style=\"cursor:hand;\">";
	
			$p->newRow($p->postVars($base_fld,true),mysql_insert_id(),$base_tbl,array_keys($base_fld),$jEdit,$jDelete,$base_fk,$code);
			$p->fillValues('base_id',mysql_insert_id());
			$p->dom('saving','style.display','none');
			$p->alert('1 tax table added.');
			}
			else{
			$p->alert('Save failed.');
			}
		//}
		//else
		//{
		//$p->alert('All fields are required\n\n-'.implode('\n-',$error));
		//}
		unset($base_values);
		break;
	
/*END Add,Edit,Delete for Base Table Data*/	
	
	case 'new': //ok
		$p->fillValues($top_fld);
		$p->fillValues(array_keys($base_fld));
		$p->dom('selector','options.selectedIndex','0');
		if(!empty($base_fk))
		$p->baseTable($base_tbl,$base_fk,'-1',$base_fld);	
		break;
	
	case 'del': //ok
		$delete = $p->deleteData($_tbl,$code,$_pk);
		if($delete)
		{
		$nextID = $p->getID($_tbl,$_pk);
		$p->fillValues('table_id',$nextID);
		$p->topTable($_tbl,$_pk,'',$top_fld);
	
		if(!empty($base_fk))
		{
		$p->deleteData($base_tbl,$code,$base_fk);
		$p->baseTable($base_tbl,$base_fk,$nextID,$base_fld);	
		}
		else
		{
		$p->baseTable($base_tbl,'','',$base_fld,$base_pk);	
		}
		$p->ifelse($delete,'Successfully deleted.','Failed.');
		$p->removeOption('selector');
		$p->dom('selector','options.selectedIndex','0');
		$p->emptyCheck($_tbl);

		}	
	break;
	
	case 'save': //ok
	$insert = $p->insertData($_tbl,implode(",",$top_fld),implode(",",$p->postVars($top_fld)));
		
	if($insert)
	{
	$s = $p->postVars($top_fld);
	$p->updateSession($_tbl,$_pk);
	$cRow = ($p->countRows($_tbl))-1;
	$row = ($cRow>0)?$cRow:0;
	$p->fillValues('table_id',mysql_insert_id());
	if(!empty($base_fk)){
	$p->dom('addNew','style.display','block');
	$p->dom('addNew','disabled',false);
	}
	if(empty($base_fk)){
	$jEdit = "<img  onclick=\"jah(\'\',\'table_id=".$code."&base_id=".mysql_insert_id()."&action=edit_base_table\',true);document.getElementById(\\'base_id\\').value=\\'".mysql_insert_id()."\\';\" src=\"external/images/edit.png\" title=\"Edit\" hspace=\"2px\" border=0 width=\"16\" height=\"16\" style=\"cursor:hand;\">";
	$jDelete  = "<img  onclick=\"if(confirm(\'Are you sure you want to delete?\'))jah(\'\',\'table_id=".$code."&base_id=".mysql_insert_id()."&action=delete_base_table\',true);\" src=\"external/images/delete.png\" title=\"Edit\" hspace=\"2px\" border=0 width=\"16\" height=\"16\" style=\"cursor:hand;\">";
	
	$p->newRow($p->postVars($base_fld,true),mysql_insert_id(),$_tbl,array_keys($base_fld),$jEdit,$jDelete,$base_fk='',$code);
	}
	$p->newOption('selector',str_replace("'","",$s[0]),$row);
	$p->dom('update','disabled',false);
	$p->dom('save','disabled',true);
	$p->dom('cancel','disabled',true);
	$p->dom('new','disabled',false);
	$p->dom('del','disabled',false);
	$p->dom('saving','style.display','none');
	$p->alert('Successfully added.');	
	}
		
	else
	{
	$p->alert('Failed');
	}
	break;
		
	case 'update': //ok
	$vars   = $p->setValues($top_fld,$p->postVars($top_fld));
	$update = $p->updateData($_tbl,$_pk,$code,$vars);		
	if($update)
	{
	$f = $top_fld[0];
	$p->dom('opt_'.$p->getKey($_pk,$code),'text',$_POST[$f]);
		if(empty($base_fk))
		{
		$bv = $p->postVars($base_fld,true);
		$x=0;
		foreach($base_fld as $value => $title){
			$p->dom($code."_".$value,'innerHTML',str_replace("'","",$bv[$x]));
			$x++;}
		}
	$p->dom('saving','style.display','none');	
	$p->alert('Successfully updated.');
	$p->dom('cancel','disabled',true);
	}
	
	else
	{
	$p->alert('Failed');
	}
	break;
	
	case 'cancel': //ok
		$p->dom('selector','options.selectedIndex','0');
		$nextID = $p->getID($_tbl,$_pk);
		$p->fillValues('table_id',$nextID);
		$p->topTable($_tbl,$_pk,'',$top_fld);
		$p->baseTable($base_tbl,$base_fk,$nextID,$base_fld,$base_pk);

		$p->dom('new','disabled',false);
		$p->dom('del','disabled',false);
		$p->emptyCheck($_tbl);
		//$p->alert($base_pk);

	break;
			
	case 'browse':
	//$type = isset($_POST['type'])?$_POST['type']:'';
	$p->createOptions('dec_id',$sel3,'statutory_contribution');
	$p->emptyCheck($_tbl);
	$p->fillValues('table_id',$_SESSION['id']);
	if(empty($base_fk))
	{
	$p->fillValues('base_id',$_SESSION['id']);
	}
	$p->fillValues(array_keys($base_fld));
	$p->misc('select',$code);
	$p->topTable($_tbl,$_pk,$_SESSION['id'],$top_fld,$_POST['type']);
	if(!empty($base_fk))
	{
	$p->baseTable($base_tbl,$base_fk,$_SESSION['id'],$base_fld,$base_pk);	
	}
	break;
		
		
	}





	
?>