<?php
include('../../../configurations/adminconfig.php');
mysql_connect(SYSCONFIG_DBHOST, SYSCONFIG_DBUSER, SYSCONFIG_DBPASS);
mysql_select_db(SYSCONFIG_DBNAME);
$q = strtolower($_GET["q"]);
if (!$q) return;
$sql = "select distinct emp_idnum from emp_masterfile
where emp_stat=7 AND emp_idnum LIKE '%$q%'";
$rsd = mysql_query($sql);
while($rs = mysql_fetch_array($rsd)) {
    echo $rs['emp_idnum']."\n";
}
