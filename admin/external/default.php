<?php
session_start();
Header("content-type: application/x-javascript");
if(isset($_SESSION['top_fld']))
{
foreach($_SESSION['top_fld'] as $name){
print "document.getElementById('".$name."').value='".addslashes($_SESSION['full_desc'][0][$name])."';";

}}
?>