<?php
include('../../../configurations/adminconfig.php');
mysql_connect(SYSCONFIG_DBHOST, SYSCONFIG_DBUSER, SYSCONFIG_DBPASS);
mysql_select_db(SYSCONFIG_DBNAME);
$q = strtolower($_GET["q"]);
if (!$q) return;
$sql = "select distinct concat(a.pi_fname,' ',a.pi_mname,' ',a.pi_lname) as fullname
from emp_personal_info a
join emp_masterfile b
where b.emp_stat=7 AND (a.pi_fname LIKE '%$q%' OR a.pi_mname LIKE '%$q%' OR a.pi_lname LIKE '%$q%')";
$rsd = mysql_query($sql);
while($rs = mysql_fetch_array($rsd)) {
    echo $rs['fullname']."\n";
}
