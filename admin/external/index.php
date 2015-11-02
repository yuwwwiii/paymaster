<?php
include('includes/class.php');

switch ($action)
{
case 'test':
$p->alert('hi');exit;
break;


default:
print "alert('Please report this to a Sigmasoft Personnel. Error Code: 00015');";
}

?>