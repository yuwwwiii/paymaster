/*
SQLyog Enterprise - MySQL GUI v6.0 Beta 4 
Host - 5.5.16 : Database - payroll_db
*********************************************************************
Server version : 5.5.16
*/
/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

/*Table structure for table `z_temp` */

DROP TABLE IF EXISTS `z_temp`;

CREATE TABLE `z_temp` (
  `psa_id` int(10) unsigned NOT NULL,
  `emp_id` int(10) unsigned NOT NULL,
  `amt` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*View structure for view emp_vw */

/*!50001 DROP TABLE IF EXISTS `emp_vw` */;
/*!50001 DROP VIEW IF EXISTS `emp_vw` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `emp_vw` AS select distinct `a`.`emp_id` AS `emp_id`,`a`.`ud_id` AS `ud_id` from ((((`emp_masterfile` `a` join `emp_personal_info` `b` on((`b`.`pi_id` = `a`.`pi_id`))) join `payroll_paystub_report` `c` on((`c`.`emp_id` = `a`.`emp_id`))) join `payroll_pay_period` `d` on((`d`.`payperiod_id` = `c`.`payperiod_id`))) join `app_userdept` `e` on((`e`.`ud_id` = `a`.`ud_id`))) where ((`d`.`payperiod_period` >= 1) and (`d`.`payperiod_period` <= 1) and (`d`.`payperiod_period_year` >= 2012) and (`d`.`payperiod_period_year` <= 2012) and (`a`.`emp_id` = 10)) order by `b`.`pi_lname` */;

/*View structure for view psa_vw */

/*!50001 DROP TABLE IF EXISTS `psa_vw` */;
/*!50001 DROP VIEW IF EXISTS `psa_vw` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `psa_vw` AS select distinct `a`.`psa_id` AS `psa_id`,`b`.`psa_name` AS `psa_name`,`b`.`psa_type` AS `psa_type` from (`z_temp` `a` join `payroll_ps_account` `b` on((`b`.`psa_id` = `a`.`psa_id`))) */;

/* Procedure structure for procedure `fetchPsaAmendment` */

/*!50003 DROP PROCEDURE IF EXISTS  `fetchPsaAmendment` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `fetchPsaAmendment`(m_period_start int, m_period_end int, y_period_start varchar(10), y_period_end varchar(10), l_id INT, l_type INT)
BEGIN
 DECLARE l_psa_id INT; DECLARE no_more_ea_am INT; DECLARE l_amt DECIMAL(10,2); DECLARE l_curr_amt DECIMAL(10,2); DECLARE curr_csr CURSOR FOR select amt from z_temp where emp_id=l_id and psa_id=l_psa_id; DECLARE ent_ea_am_csr CURSOR FOR select distinct a.psa_id from payroll_ps_account a inner join payroll_ps_amendment b on (b.psa_id=a.psa_id) inner join payroll_ps_amendemp c on (c.psamend_id=b.psamend_id) inner join payroll_pay_stub d on (d.paystub_id=c.paystub_id) inner join payroll_pay_period e on (e.payperiod_id=d.payperiod_id) where a.psa_type=l_type and (e.payperiod_period >= m_period_start and e.payperiod_period <= m_period_end) and (e.payperiod_period_year >= y_period_start and e.payperiod_period_year <= y_period_end) order by a.psa_order, a.psa_name; DECLARE amt_csr CURSOR FOR select sum(a.amendemp_amount) as total from payroll_ps_amendemp a inner join payroll_ps_amendment b on (b.psamend_id=a.psamend_id) inner join payroll_pay_stub c on (c.paystub_id=a.paystub_id) inner join payroll_pay_period d on (d.payperiod_id=c.payperiod_id) where a.emp_id=l_id and b.psa_id=l_psa_id and a.paystub_id!=0 and (d.payperiod_period >= m_period_start and d.payperiod_period <= m_period_end) and (d.payperiod_period_year >= y_period_start and d.payperiod_period_year <= y_period_end); DECLARE CONTINUE HANDLER FOR NOT FOUND SET no_more_ea_am=1; SET no_more_ea_am=0; OPEN ent_ea_am_csr; ea_am_loop:WHILE(no_more_ea_am=0) DO FETCH ent_ea_am_csr INTO l_psa_id; IF no_more_ea_am=1 THEN LEAVE ea_am_loop; END IF; SET l_amt = 0; SET l_curr_amt = 0; SET l_amt = 0; OPEN amt_csr; FETCH amt_csr INTO l_amt; CLOSE amt_csr; IF(l_amt IS NULL) THEN SET l_amt = 0.00; END IF; IF(checkNonZeroInAmendment(m_period_start,m_period_end,y_period_start,y_period_end,l_psa_id)) THEN IF(checkIfPSAExists(l_psa_id,l_id)) THEN OPEN curr_csr; FETCH curr_csr INTO l_curr_amt; CLOSE curr_csr; SET @total = l_amt + l_curr_amt; IF(@total IS NULL) THEN SET @total = 0; END IF; UPDATE z_temp SET amt = @total WHERE emp_id=l_id and psa_id=l_psa_id; ELSE INSERT INTO z_temp (psa_id, emp_id, amt) VALUES (l_psa_id, l_id, l_amt); END IF; END IF; END WHILE ea_am_loop; CLOSE ent_ea_am_csr; END */$$
DELIMITER ;

/* Procedure structure for procedure `fetchPsaEntry` */

/*!50003 DROP PROCEDURE IF EXISTS  `fetchPsaEntry` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `fetchPsaEntry`(m_period_start int, m_period_end int, y_period_start varchar(10), y_period_end varchar(10), l_id INT, l_type INT)
BEGIN DECLARE l_psa_id INT; DECLARE no_more_el INT; DECLARE l_amt DECIMAL(10,2); DECLARE ent_ea_csr CURSOR FOR select distinct a.psa_id from payroll_ps_account a inner join payroll_paystub_entry b on (b.psa_id=a.psa_id) inner join payroll_pay_stub c on (c.paystub_id=b.paystub_id) inner join payroll_pay_period d on (d.payperiod_id=c.payperiod_id) where a.psa_type=l_type and (d.payperiod_period >= m_period_start and d.payperiod_period <= m_period_end) and (d.payperiod_period_year >= y_period_start and d.payperiod_period_year <= y_period_end) order by a.psa_order, a.psa_name; DECLARE amt_csr CURSOR FOR SELECT sum(a.ppe_amount) as total FROM payroll_paystub_entry a inner join payroll_paystub_report b on (b.paystub_id=a.paystub_id) inner join payroll_pay_period c on (c.payperiod_id=b.payperiod_id) where b.emp_id=l_id and a.psa_id=l_psa_id and (c.payperiod_period >= m_period_start and c.payperiod_period <= m_period_end) and (c.payperiod_period_year >= y_period_start and c.payperiod_period_year <= y_period_end); DECLARE CONTINUE HANDLER FOR NOT FOUND SET no_more_el=1; SET no_more_el=0; OPEN ent_ea_csr; ent_loop:WHILE(no_more_el=0) DO FETCH ent_ea_csr INTO l_psa_id; IF no_more_el=1 THEN LEAVE ent_loop; END IF; OPEN amt_csr; FETCH amt_csr INTO l_amt; CLOSE amt_csr; IF(l_amt IS NULL) THEN SET l_amt = 0.00; END IF; IF(NOT checkIfPSAExists(l_psa_id,l_id) AND checkNonZeroInEntry(m_period_start,m_period_end,y_period_start,y_period_end,l_psa_id)) THEN INSERT INTO z_temp (psa_id, emp_id, amt) VALUES (l_psa_id, l_id, l_amt); END IF; END WHILE ent_loop; CLOSE ent_ea_csr; END */$$
DELIMITER ;

/* Procedure structure for procedure `fetchPsaEntryPerEmp` */

/*!50003 DROP PROCEDURE IF EXISTS  `fetchPsaEntryPerEmp` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `fetchPsaEntryPerEmp`(m_period_start int, m_period_end int, y_period_start varchar(10), y_period_end varchar(10), l_id INT, l_type INT)
BEGIN
 DECLARE l_psa_id INT; DECLARE no_more_el INT; DECLARE l_amt DECIMAL(10,2); DECLARE ent_ea_csr CURSOR FOR  select distinct a.psa_id from payroll_ps_account a inner join payroll_paystub_entry b on (b.psa_id=a.psa_id) inner join payroll_pay_stub c on (c.paystub_id=b.paystub_id) inner join payroll_pay_period d on (d.payperiod_id=c.payperiod_id) inner join payroll_paystub_report e on (e.paystub_id=b.paystub_id) where a.psa_type=l_type and e.emp_id=l_id and (d.payperiod_period >= m_period_start and d.payperiod_period <= m_period_end) and (d.payperiod_period_year >= y_period_start and d.payperiod_period_year <= y_period_end) order by a.psa_order, a.psa_name; DECLARE amt_csr CURSOR FOR SELECT sum(a.ppe_amount) as total FROM payroll_paystub_entry a inner join payroll_paystub_report b on (b.paystub_id=a.paystub_id) inner join payroll_pay_period c on (c.payperiod_id=b.payperiod_id) where b.emp_id=l_id and a.psa_id=l_psa_id and (c.payperiod_period >= m_period_start and c.payperiod_period <= m_period_end) and (c.payperiod_period_year >= y_period_start and c.payperiod_period_year <= y_period_end); DECLARE CONTINUE HANDLER FOR NOT FOUND SET no_more_el=1; SET no_more_el=0; OPEN ent_ea_csr; ent_loop:WHILE(no_more_el=0) DO FETCH ent_ea_csr INTO l_psa_id; OPEN amt_csr; FETCH amt_csr INTO l_amt; CLOSE amt_csr; IF(l_amt IS NULL) THEN SET l_amt = 0.00; END IF; IF(NOT checkIfPSAExists(l_psa_id,l_id) AND checkNonZeroInEntry(m_period_start,m_period_end,y_period_start,y_period_end,l_psa_id)) THEN INSERT INTO z_temp (psa_id, emp_id, amt) VALUES (l_psa_id, l_id, l_amt); END IF; IF no_more_el=1 THEN LEAVE ent_loop; END IF; END WHILE ent_loop; CLOSE ent_ea_csr;
 END */$$
DELIMITER ;

/* Procedure structure for procedure `fetchPsaLoan` */

/*!50003 DROP PROCEDURE IF EXISTS  `fetchPsaLoan` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `fetchPsaLoan`(m_period_start int, m_period_end int, y_period_start varchar(10), y_period_end varchar(10), l_id INT, l_type INT)
BEGIN
 DECLARE l_psa_id INT; DECLARE no_more_loan INT; DECLARE l_amt DECIMAL(10,2); DECLARE loan_csr CURSOR FOR select distinct a.psa_id from payroll_ps_account a inner join loan_info b on (b.psa_id=a.psa_id) inner join loan_detail_sum c on (c.loan_id=b.loan_id) inner join payroll_pay_stub d on (d.paystub_id=c.paystub_id) inner join payroll_pay_period e on (e.payperiod_id=d.payperiod_id) where a.psa_type=l_type and (e.payperiod_period >= m_period_start and e.payperiod_period <= m_period_end) and (e.payperiod_period_year >= y_period_start and e.payperiod_period_year <= y_period_end) order by a.psa_order, a.psa_name; DECLARE amt_csr CURSOR FOR select sum(a.loansum_payment) from loan_detail_sum a inner join loan_info b on (b.loan_id=a.loan_id) inner join payroll_pay_stub c on (c.paystub_id=a.paystub_id) inner join payroll_pay_period d on (d.payperiod_id=c.payperiod_id) where b.emp_id=l_id and b.psa_id=l_psa_id and (d.payperiod_period >= m_period_start and d.payperiod_period <= m_period_end) and (d.payperiod_period_year >= y_period_start and d.payperiod_period_year <= y_period_end); DECLARE CONTINUE HANDLER FOR NOT FOUND SET no_more_loan=1; SET no_more_loan=0; OPEN loan_csr; loan_loop:WHILE(no_more_loan=0) DO FETCH loan_csr INTO l_psa_id; IF no_more_loan=1 THEN LEAVE loan_loop; END IF; OPEN amt_csr; FETCH amt_csr INTO l_amt; CLOSE amt_csr; IF(NOT checkIfPSAExists(l_psa_id,l_id) AND checkNonZeroInLoan(m_period_start,m_period_end,y_period_start,y_period_end,l_psa_id)) THEN IF(l_amt IS NULL) THEN SET l_amt = 0.00; END IF; INSERT INTO z_temp (psa_id, emp_id, amt) VALUES (l_psa_id, l_id, l_amt); END IF; END WHILE loan_loop; CLOSE loan_csr; END */$$
DELIMITER ;

/* Procedure structure for procedure `mergeTbl` */

/*!50003 DROP PROCEDURE IF EXISTS  `mergeTbl` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `mergeTbl`()
BEGIN DROP TABLE IF EXISTS `payroll_ps_amendemp_temp`; CREATE TABLE `payroll_ps_amendemp_temp` ( `amendemp_id` int(10) unsigned NOT NULL AUTO_INCREMENT, `emp_id` int(10) unsigned NOT NULL, `paystub_id` int(11) unsigned NOT NULL, `psamend_id` int(11) unsigned NOT NULL, `amendemp_amount` decimal(11,2) DEFAULT '0.00', `amendemp_addwho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL, `amendemp_addwhen` timestamp NULL DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (`amendemp_id`) ) ENGINE=MyISAM AUTO_INCREMENT=346 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci; ALTER IGNORE TABLE payroll_ps_amendemp ADD paystub_id int(11) unsigned NOT NULL AFTER emp_id; INSERT INTO payroll_ps_amendemp_temp(amendemp_id, emp_id, paystub_id, psamend_id, amendemp_amount, amendemp_addwho, amendemp_addwhen) SELECT c.* FROM  ((SELECT a.* FROM payroll_ps_amendemp as a) UNION (SELECT b.* FROM payroll_db2.payroll_ps_amendemp as b)) c ON DUPLICATE KEY UPDATE paystub_id = IF(c.paystub_id != 0, VALUES(paystub_id), 0); DROP TABLE IF EXISTS `payroll_ps_amendemp`; RENAME TABLE payroll_ps_amendemp_temp TO payroll_ps_amendemp; END */$$
DELIMITER ;

/* Procedure structure for procedure `statSummary` */

/*!50003 DROP PROCEDURE IF EXISTS  `statSummary` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `statSummary`(m_period varchar(10), y_period varchar(10))
BEGIN DECLARE l_id INT; DECLARE l_emp_idnum VARCHAR(30);  DECLARE l_lastname VARCHAR(30);  DECLARE l_firstname VARCHAR(30); DECLARE l_middlename VARCHAR(30); DECLARE l_salary DECIMAL(10,2); DECLARE l_deduction DECIMAL(10,2); DECLARE l_sss_num VARCHAR(30); DECLARE l_sss_contribution DECIMAL(10,2); DECLARE l_phic_num VARCHAR(30); DECLARE l_phic_contribution DECIMAL(10,2); DECLARE l_hdmf_num VARCHAR(30); DECLARE l_hdmf_contribution DECIMAL(10,2); DECLARE no_more_emp INT; DECLARE l_emp_count INT; DECLARE emp_csr CURSOR FOR select distinct a.emp_id from emp_masterfile a inner join emp_personal_info b on (b.pi_id=a.pi_id) inner join payroll_paystub_report c on (c.emp_id=a.emp_id) inner join payroll_pay_period d on (d.payperiod_id=c.payperiod_id) where d.payperiod_period=m_period and d.payperiod_period_year=y_period order by b.pi_lname; DECLARE emp_info_csr CURSOR FOR select a.emp_idnum, b.pi_lname, b.pi_fname, b.pi_mname, replace(b.pi_sss,'-',''), replace(b.pi_phic,'-',''), replace(b.pi_hdmf,'-','') from emp_masterfile a inner join emp_personal_info b on (b.pi_id=a.pi_id) WHERE a.emp_id=l_id; DECLARE salary_csr CURSOR FOR select sum(a.ppe_amount)  from payroll_paystub_entry a inner join payroll_paystub_report b on (b.paystub_id=a.paystub_id) inner join payroll_pay_period c on (c.payperiod_id=b.payperiod_id) where b.emp_id=l_id and c.payperiod_period=m_period and c.payperiod_period_year=y_period and (a.psa_id=4 or a.psa_id=25 or a.psa_id=26); DECLARE deduction_csr CURSOR FOR select sum(a.ppe_amount)  from payroll_paystub_entry a inner join payroll_paystub_report b on (b.paystub_id=a.paystub_id) inner join payroll_pay_period c on (c.payperiod_id=b.payperiod_id) where b.emp_id=10 and c.payperiod_period=1 and c.payperiod_period_year=2012 and (a.psa_id=33 or a.psa_id=34); DECLARE sss_contribution_csr CURSOR FOR select sum(a.ppe_amount)  from payroll_paystub_entry a inner join payroll_paystub_report b on (b.paystub_id=a.paystub_id) inner join payroll_pay_period c on (c.payperiod_id=b.payperiod_id) where b.emp_id=l_id and c.payperiod_period=m_period and c.payperiod_period_year=y_period and a.psa_id=7; DECLARE phic_contribution_csr CURSOR FOR select sum(a.ppe_amount)  from payroll_paystub_entry a inner join payroll_paystub_report b on (b.paystub_id=a.paystub_id) inner join payroll_pay_period c on (c.payperiod_id=b.payperiod_id) where b.emp_id=l_id and c.payperiod_period=m_period and c.payperiod_period_year=y_period and a.psa_id=14; DECLARE hdmf_contribution_csr CURSOR FOR select sum(a.ppe_amount)  from payroll_paystub_entry a inner join payroll_paystub_report b on (b.paystub_id=a.paystub_id) inner join payroll_pay_period c on (c.payperiod_id=b.payperiod_id) where b.emp_id=l_id and c.payperiod_period=m_period and c.payperiod_period_year=y_period and a.psa_id=15; DECLARE CONTINUE HANDLER FOR NOT FOUND SET no_more_emp=1; SET no_more_emp=0; OPEN emp_csr; emp_loop:WHILE(no_more_emp=0) DO FETCH emp_csr INTO l_id; OPEN emp_info_csr; FETCH emp_info_csr INTO l_emp_idnum, l_lastname, l_firstname, l_middlename, l_sss_num, l_phic_num, l_hdmf_num; CLOSE emp_info_csr; OPEN salary_csr; FETCH salary_csr INTO l_salary; CLOSE salary_csr; OPEN deduction_csr; FETCH deduction_csr INTO l_deduction; CLOSE deduction_csr; SET l_salary = l_salary - l_deduction; OPEN sss_contribution_csr; FETCH sss_contribution_csr INTO l_sss_contribution; CLOSE sss_contribution_csr; OPEN phic_contribution_csr; FETCH phic_contribution_csr INTO l_phic_contribution; CLOSE phic_contribution_csr; OPEN hdmf_contribution_csr; FETCH hdmf_contribution_csr INTO l_hdmf_contribution; CLOSE hdmf_contribution_csr; IF no_more_emp=1 THEN LEAVE emp_loop; END IF; SET l_emp_count=l_emp_count+1; select l_emp_idnum, l_lastname, l_firstname, l_middlename, l_salary, l_sss_num, l_sss_contribution, l_phic_num, l_phic_contribution, l_hdmf_num, l_hdmf_contribution; END WHILE emp_loop; CLOSE emp_csr; SET no_more_emp=0; END */$$
DELIMITER ;

/* Procedure structure for procedure `ytdPerEmp` */

/*!50003 DROP PROCEDURE IF EXISTS  `ytdPerEmp` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `ytdPerEmp`(m_period_start int, m_period_end int, y_period_start varchar(10), y_period_end varchar(10), p_emp_id INT, p_emp_status INT, p_dept_id INT, p_groupings INT)
BEGIN
 DECLARE emp_str VARCHAR(90000);
 DECLARE l_id INT; DECLARE l_pay_id INT;
 DECLARE l_fullname varchar(300);
 DECLARE l_postname varchar(100);
 DECLARE l_dept_name varchar(100);
 DECLARE l_basic DECIMAL(10,2); DECLARE l_ut_tadry DECIMAL(10,2); DECLARE l_absences DECIMAL(10,2); DECLARE l_ot DECIMAL(10,2); DECLARE l_cola DECIMAL(10,2); DECLARE m_period INT; DECLARE y_period INT;
 DECLARE no_more_emp INT; DECLARE no_pay_period INT;
 DECLARE str_query varchar(90000); DECLARE emp_info_csr CURSOR FOR
 select CONCAT(b.pi_lname,', ',b.pi_fname,' ',concat(RPAD(b.pi_mname,1,' '),'.(',a.emp_idnum,')')) as fullname, c.post_name, d.ud_name
 from emp_masterfile a
 inner join emp_personal_info b on (b.pi_id=a.pi_id)
 inner join emp_position c on (c.post_id=a.post_id)
 inner join app_userdept d on (d.ud_id=a.ud_id)
 WHERE a.emp_id=l_id and a.emp_stat!=0;  DECLARE payperiod_csr CURSOR FOR select d.payperiod_id from payroll_paystub_entry a inner join payroll_pay_stub b on (b.paystub_id=a.paystub_id) inner join payroll_paystub_report c on (c.paystub_id=b.paystub_id) inner join payroll_pay_period d on (d.payperiod_id=c.payperiod_id) where a.psa_id='1'  and c.emp_id=10  and (d.payperiod_period = m_period) and (d.payperiod_period_year = y_period);  DECLARE salary_csr CURSOR FOR
 select ppe_amount from payroll_paystub_entry a
 inner join payroll_pay_stub b on (b.paystub_id=a.paystub_id)
 inner join payroll_paystub_report c on (c.paystub_id=b.paystub_id)
 inner join payroll_pay_period d on (d.payperiod_id=c.payperiod_id)
 where a.psa_id='1'  and d.payperiod_id=l_pay_id
 and c.emp_id=l_id;  DECLARE ut_tardy_csr CURSOR FOR select concat('-',sum(emp_tarec_amtperrate)) as total from ta_emp_rec a inner join ta_tbl b on (b.tatbl_id=a.tatbl_id) inner join payroll_pay_period c on (c.payperiod_id=a.payperiod_id) where (a.tatbl_id='3' or a.tatbl_id='4') and a.emp_id=l_id and a.paystub_id!=0 and c.payperiod_id=l_pay_id;  DECLARE absences_csr CURSOR FOR select concat('-',sum(emp_tarec_amtperrate)) as total from ta_emp_rec a inner join ta_tbl b on (b.tatbl_id=a.tatbl_id) inner join payroll_pay_period c on (c.payperiod_id=a.payperiod_id) where a.tatbl_id='1' and a.emp_id=l_id and a.paystub_id!=0 and c.payperiod_id=l_pay_id; DECLARE ot_csr CURSOR FOR select sum(ppe_amount) as total from payroll_paystub_entry a inner join payroll_pay_stub b on (b.paystub_id=a.paystub_id) inner join payroll_paystub_report c on (c.paystub_id=b.paystub_id) inner join payroll_pay_period d on (d.payperiod_id=c.payperiod_id) where a.psa_id='16'  and c.emp_id=l_id  and d.payperiod_id=l_pay_id; DECLARE cola_csr CURSOR FOR select sum(ppe_amount) as total from payroll_paystub_entry a inner join payroll_pay_stub b on (b.paystub_id=a.paystub_id) inner join payroll_paystub_report c on (c.paystub_id=b.paystub_id) inner join payroll_pay_period d on (d.payperiod_id=c.payperiod_id) where a.psa_id='39'  and c.emp_id=l_id  and d.payperiod_id=l_pay_id;  DECLARE emp_csr CURSOR FOR select * from emp_vw; DECLARE CONTINUE HANDLER FOR NOT FOUND SET no_more_emp=1;
 DROP VIEW IF EXISTS emp_vw;
 SET emp_str = CONCAT("CREATE VIEW emp_vw as
 select distinct a.emp_id
 from emp_masterfile a
 inner join emp_personal_info b on (b.pi_id=a.pi_id)
 inner join payroll_paystub_report c on (c.emp_id=a.emp_id)
 inner join payroll_pay_period d on (d.payperiod_id=c.payperiod_id)
 inner join app_userdept e on (e.ud_id=a.ud_id)
 where (d.payperiod_period >=",m_period_start," and d.payperiod_period <= ",m_period_end,") 
 and (d.payperiod_period_year >= ",y_period_start," and d.payperiod_period_year <= ",y_period_end," )");
 IF(p_emp_id IS NOT NULL) THEN
 SET emp_str =+ CONCAT(emp_str," and a.emp_id=",p_emp_id);
 END IF;
 IF(p_emp_status != 0) THEN
 SET emp_str =+ CONCAT(emp_str," and a.emp_stat=",p_emp_status);
 END IF;
 IF(p_dept_id != 0) THEN
 SET emp_str =+ CONCAT(emp_str," and a.ud_id=",p_dept_id);
 END IF;
 IF(p_groupings = 1) THEN
 SET emp_str =+ CONCAT(emp_str," ORDER BY e.ud_name,b.pi_lname");
 ELSE
 SET emp_str =+ CONCAT(emp_str," ORDER BY b.pi_lname");
 END IF;
 SET @emp_cmd = emp_str;
 PREPARE stmt FROM @emp_cmd;
 EXECUTE stmt;
 DEALLOCATE PREPARE stmt; DROP TABLE IF EXISTS `z_temp`; CREATE TABLE `z_temp` (	`psa_id` int(10) unsigned NOT NULL, 	`emp_id` int(10) unsigned NOT NULL, 	`amt` DECIMAL(10,2), 	`payperiod_period` int(10) NOT NULL,	`payperiod_period_year` int(10) NOT NULL);
 SET no_more_emp=0;
 SET @l_emp_count=0;
 OPEN emp_csr;
	 emp_loop:WHILE(no_more_emp=0) DO
		 FETCH emp_csr INTO l_id;
		 OPEN emp_info_csr;
			 FETCH emp_info_csr INTO l_fullname, l_postname, l_dept_name;
			 SET @l_fullname = l_fullname;
			 SET @l_postname = l_postname;
			 SET @l_dept_name = l_dept_name;
		 CLOSE emp_info_csr;		 SET str_query = CONCAT("select @l_fullname, @l_postname, @l_dept_name");		 SET m_period = m_period_start;		 SET y_period = y_period_start;		 y_period_loop:WHILE (y_period <= y_period_end) DO			m_period_loop:WHILE (m_period <= m_period_end) DO				 IF(NOT checkIfPayperiodExists(m_period,y_period)) THEN					LEAVE y_period_loop;				 END IF;				 BEGIN					 DECLARE CONTINUE HANDLER FOR NOT FOUND SET no_pay_period=1;					 SET no_pay_period=0;					 OPEN payperiod_csr;					 pay_loop:WHILE(no_pay_period=0) DO					 FETCH payperiod_csr INTO l_pay_id;					 IF no_pay_period=1 THEN						LEAVE pay_loop;					 END IF;					 OPEN salary_csr;
						 FETCH salary_csr INTO l_basic;
						 SET @str = CONCAT("SET @l_basic_",m_period,"_",y_period,"_",l_pay_id,"=", l_basic,";");						 PREPARE stmt FROM @str;						 EXECUTE stmt;						 DEALLOCATE PREPARE stmt;						 SET str_query =+ CONCAT(str_query,", @l_basic_",m_period,"_",y_period,"_",l_pay_id);
					 CLOSE salary_csr;					IF (checkIfNotZero(m_period_start,m_period_end,y_period_start,y_period_end,l_id,3,"TA") OR checkIfNotZero(m_period_start,m_period_end,y_period_start,y_period_end,l_id,4,"TA")) THEN						 OPEN ut_tardy_csr;							 FETCH ut_tardy_csr INTO l_ut_tadry;							 IF l_ut_tadry IS NOT NULL THEN							 SET @str = CONCAT("SET @l_ut_tardy_",m_period,"_",y_period,"_",l_pay_id,"=", l_ut_tadry,";");							 ELSE							 SET @str = CONCAT("SET @l_ut_tardy_",m_period,"_",y_period,"_",l_pay_id,"= 0.00;");							 END IF;							 PREPARE stmt FROM @str;							 EXECUTE stmt;							 DEALLOCATE PREPARE stmt;							 SET str_query =+ CONCAT(str_query,", @l_ut_tardy_",m_period,"_",y_period,"_",l_pay_id);						  CLOSE ut_tardy_csr;					 END IF;					 IF checkIfNotZero(m_period_start,m_period_end,y_period_start,y_period_end,l_id,1,"TA") THEN						 OPEN absences_csr;							 FETCH absences_csr INTO l_absences;							 IF l_absences IS NOT NULL THEN							 SET @str = CONCAT("SET @l_absences_",m_period,"_",y_period,"_",l_pay_id,"=",l_absences,";");							 ELSE							 SET @str = CONCAT("SET @l_absences_",m_period,"_",y_period,"_",l_pay_id,"= 0.00;");							 END IF;							 PREPARE stmt FROM @str;							 EXECUTE stmt;							 DEALLOCATE PREPARE stmt;							 SET str_query =+ CONCAT(str_query,", @l_absences_",m_period,"_",y_period,"_",l_pay_id);						 CLOSE absences_csr;					 END IF;					 IF checkIfNotZero(m_period_start,m_period_end,y_period_start,y_period_end,l_id,16,"ENTRY") THEN						OPEN ot_csr;							FETCH ot_csr INTO l_ot;							IF l_ot IS NOT NULL THEN							 SET @str = CONCAT("SET @l_ot_",m_period,"_",y_period,"_",l_pay_id,"=",l_ot,";");							 ELSE							 SET @str = CONCAT("SET @l_ot_",m_period,"_",y_period,"_",l_pay_id,"= 0.00;");							 END IF;							 PREPARE stmt FROM @str;							 EXECUTE stmt;							 DEALLOCATE PREPARE stmt;							 SET str_query =+ CONCAT(str_query,", @l_ot_",m_period,"_",y_period,"_",l_pay_id);						CLOSE ot_csr;					 END IF;					 IF checkIfNotZero(m_period_start,m_period_end,y_period_start,y_period_end,l_id,39,"ENTRY") THEN						OPEN cola_csr;							FETCH cola_csr INTO l_cola;							IF l_cola IS NOT NULL THEN							 SET @str = CONCAT("SET @l_cola_",m_period,"_",y_period,"_",l_pay_id,"=",l_cola,";");							 ELSE							 SET @str = CONCAT("SET @l_cola_",m_period,"_",y_period,"_",l_pay_id,"= 0.00;");							 END IF;							 PREPARE stmt FROM @str;							 EXECUTE stmt;							 DEALLOCATE PREPARE stmt;							 SET str_query =+ CONCAT(str_query,", @l_cola_",m_period,"_",y_period,"_",l_pay_id);						CLOSE cola_csr;					 END IF;					 END WHILE pay_loop;					 CLOSE payperiod_csr;				 END;				 SET m_period = m_period + 1;				 END WHILE m_period_loop;				 SET y_period = y_period + 1;				 IF(m_period = 12 AND y_period <= y_period_end) THEN				 SET m_period = 1;				 END IF;			 END WHILE y_period_loop;
		 IF no_more_emp=1 THEN
		 LEAVE emp_loop;
		 END IF;
		 SET @l_emp_count=@l_emp_count+1;
		 SET @fetch_cmd = str_query;
		 PREPARE stmt FROM @fetch_cmd;
		 EXECUTE stmt;
		 DEALLOCATE PREPARE stmt;
	 END WHILE emp_loop;
 CLOSE emp_csr;
 SET no_more_emp=0;
 END */$$
DELIMITER ;

/* Procedure structure for procedure `ytdSummary` */

DELIMITER $$

DROP PROCEDURE IF EXISTS `ytdSummary`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ytdSummary`(m_period_start int, m_period_end int, y_period_start varchar(10), y_period_end varchar(10), p_emp_id INT, p_emp_status INT, p_dept_id INT, p_groupings INT)
BEGIN DECLARE emp_str VARCHAR(90000); DECLARE l_id INT; DECLARE l_dept_ud INT; DECLARE l_psa_data INT; DECLARE no_more_emp INT; DECLARE l_emp_idnum VARCHAR(30);  DECLARE l_fullname VARCHAR(100);  DECLARE l_taxep_code VARCHAR(10); DECLARE l_basic DECIMAL(10,2); DECLARE l_ut_tardy DECIMAL(10,2); DECLARE l_absences DECIMAL(10,2); DECLARE l_ot DECIMAL(10,2); DECLARE l_cola DECIMAL(10,2); DECLARE l_psa INT; DECLARE l_amt DECIMAL(10,2); DECLARE l_sss_er DECIMAL(10,2); DECLARE l_sss_ec DECIMAL(10,2); DECLARE l_phic_er DECIMAL(10,2); DECLARE l_hdmf_er DECIMAL(10,2); DECLARE c_psa_id INT; DECLARE str_query varchar(90000); DECLARE emp_info_csr CURSOR FOR select a.emp_idnum, CONCAT(b.pi_lname,', ',b.pi_fname,' ',concat(RPAD(b.pi_mname,1,' '),'.')) as fullname, c.taxep_code from emp_masterfile a inner join emp_personal_info b on (b.pi_id=a.pi_id) inner join tax_excep c on (c.taxep_id=a.taxep_id) WHERE a.emp_id=l_id and a.emp_stat!=0; 
 DECLARE salary_csr CURSOR FOR select sum(ppe_amount) as total from payroll_paystub_entry a inner join payroll_pay_stub b on (b.paystub_id=a.paystub_id) inner join payroll_paystub_report c on (c.paystub_id=b.paystub_id) inner join payroll_pay_period d on (d.payperiod_id=c.payperiod_id) where a.psa_id='1'  and c.emp_id=l_id  and (d.payperiod_period >= m_period_start and d.payperiod_period <= m_period_end) and (d.payperiod_period_year >= y_period_start and d.payperiod_period_year <= y_period_end); 
 DECLARE ut_tardy_csr CURSOR FOR select concat('-',sum(emp_tarec_amtperrate)) as total from ta_emp_rec a inner join ta_tbl b on (b.tatbl_id=a.tatbl_id) inner join payroll_pay_period c on (c.payperiod_id=a.payperiod_id) where (a.tatbl_id='3' or a.tatbl_id='4') and a.emp_id=l_id and a.paystub_id!=0 and (c.payperiod_period >= m_period_start and c.payperiod_period <= m_period_end) and (c.payperiod_period_year >= y_period_start and c.payperiod_period_year <= y_period_end); 
 DECLARE absences_csr CURSOR FOR select concat('-',sum(emp_tarec_amtperrate)) as total from ta_emp_rec a inner join ta_tbl b on (b.tatbl_id=a.tatbl_id) inner join payroll_pay_period c on (c.payperiod_id=a.payperiod_id) where a.tatbl_id='1' and a.emp_id=l_id and a.paystub_id!=0 and (c.payperiod_period >= m_period_start and c.payperiod_period <= m_period_end) and (c.payperiod_period_year >= y_period_start and c.payperiod_period_year <= y_period_end); 
 DECLARE ot_csr CURSOR FOR select sum(ppe_amount) as total from payroll_paystub_entry a inner join payroll_pay_stub b on (b.paystub_id=a.paystub_id) inner join payroll_paystub_report c on (c.paystub_id=b.paystub_id) inner join payroll_pay_period d on (d.payperiod_id=c.payperiod_id) where a.psa_id='16'  and c.emp_id=l_id  and (d.payperiod_period >= m_period_start and d.payperiod_period <= m_period_end) and (d.payperiod_period_year >= y_period_start and d.payperiod_period_year <= y_period_end); 
 DECLARE cola_csr CURSOR FOR select sum(ppe_amount) as total from payroll_paystub_entry a inner join payroll_pay_stub b on (b.paystub_id=a.paystub_id) inner join payroll_paystub_report c on (c.paystub_id=b.paystub_id) inner join payroll_pay_period d on (d.payperiod_id=c.payperiod_id) where a.psa_id='39'  and c.emp_id=l_id  and (d.payperiod_period >= m_period_start and d.payperiod_period <= m_period_end) and (d.payperiod_period_year >= y_period_start and d.payperiod_period_year <= y_period_end); 
 DECLARE psa_csr CURSOR FOR select distinct psa_id from z_temp; 
 DECLARE psa_amt_csr CURSOR FOR select sum(amt) from z_temp where psa_id=l_psa and emp_id=l_id; 
DECLARE sss_er_csr CURSOR FOR select sum(ppe_amount_employer) as total from payroll_paystub_entry a inner join payroll_pay_stub b on (b.paystub_id=a.paystub_id) inner join payroll_paystub_report c on (c.paystub_id=b.paystub_id) inner join payroll_pay_period d on (d.payperiod_id=c.payperiod_id) where a.psa_id='7'  and c.emp_id=l_id  and (d.payperiod_period >= m_period_start and d.payperiod_period <= m_period_end) and (d.payperiod_period_year >= y_period_start and d.payperiod_period_year <= y_period_end);
DECLARE sss_ec_csr CURSOR FOR select sum(ppe_units) as total from payroll_paystub_entry a inner join payroll_pay_stub b on (b.paystub_id=a.paystub_id) inner join payroll_paystub_report c on (c.paystub_id=b.paystub_id) inner join payroll_pay_period d on (d.payperiod_id=c.payperiod_id) where a.psa_id='7'  and c.emp_id=l_id  and (d.payperiod_period >= m_period_start and d.payperiod_period <= m_period_end) and (d.payperiod_period_year >= y_period_start and d.payperiod_period_year <= y_period_end);
DECLARE phic_er_csr CURSOR FOR select sum(ppe_amount_employer) as total from payroll_paystub_entry a inner join payroll_pay_stub b on (b.paystub_id=a.paystub_id) inner join payroll_paystub_report c on (c.paystub_id=b.paystub_id) inner join payroll_pay_period d on (d.payperiod_id=c.payperiod_id) where a.psa_id='14'  and c.emp_id=l_id  and (d.payperiod_period >= m_period_start and d.payperiod_period <= m_period_end) and (d.payperiod_period_year >= y_period_start and d.payperiod_period_year <= y_period_end); 
DECLARE hdmf_er_csr CURSOR FOR select sum(ppe_amount_employer) as total from payroll_paystub_entry a inner join payroll_pay_stub b on (b.paystub_id=a.paystub_id) inner join payroll_paystub_report c on (c.paystub_id=b.paystub_id) inner join payroll_pay_period d on (d.payperiod_id=c.payperiod_id) where a.psa_id='15'  and c.emp_id=l_id  and (d.payperiod_period >= m_period_start and d.payperiod_period <= m_period_end) and (d.payperiod_period_year >= y_period_start and d.payperiod_period_year <= y_period_end);  
DECLARE emp_csr CURSOR FOR select * from emp_vw; 
DECLARE CONTINUE HANDLER FOR NOT FOUND SET no_more_emp=1; 
DROP VIEW IF EXISTS emp_vw; 
SET emp_str = CONCAT("CREATE VIEW emp_vw as select distinct a.emp_id, a.ud_id from emp_masterfile a inner join emp_personal_info b on (b.pi_id=a.pi_id) inner join payroll_paystub_report c on (c.emp_id=a.emp_id) inner join payroll_pay_period d on (d.payperiod_id=c.payperiod_id) left join app_userdept e on (e.ud_id=a.ud_id) where (d.payperiod_period >=",m_period_start," and d.payperiod_period <= ",m_period_end,")  and (d.payperiod_period_year >= ",y_period_start," and d.payperiod_period_year <= ",y_period_end," )"); 
IF(p_emp_id IS NOT NULL) THEN 
SET emp_str =+ CONCAT(emp_str," and a.emp_id=",p_emp_id); 
END IF; 
IF(p_emp_status != 0) THEN 
SET emp_str =+ CONCAT(emp_str," and a.emp_stat=",p_emp_status); 
END IF; 
IF(p_dept_id != 0) THEN 
SET emp_str =+ CONCAT(emp_str," and a.ud_id=",p_dept_id); 
END IF; 
IF(p_groupings = 1) THEN 
SET emp_str =+ CONCAT(emp_str," ORDER BY e.ud_name,b.pi_lname"); 
ELSE 
SET emp_str =+ CONCAT(emp_str," ORDER BY b.pi_lname"); 
END IF; 
SET @emp_cmd = emp_str; 
PREPARE stmt FROM @emp_cmd; 
EXECUTE stmt; 
DEALLOCATE PREPARE stmt; 
DROP TABLE IF EXISTS `z_temp`; 
CREATE TABLE `z_temp` (`psa_id` int(10) unsigned NOT NULL, `emp_id` int(10) unsigned NOT NULL, `amt` DECIMAL(10,2)); 
SET no_more_emp=0; 
SET @l_emp_count=0; 
OPEN emp_csr; 
emp_loop:
WHILE(no_more_emp=0) DO 
FETCH emp_csr INTO l_id, l_dept_ud; 
OPEN emp_info_csr; 
FETCH emp_info_csr INTO l_emp_idnum, l_fullname, l_taxep_code; 
SET @l_emp_idnum = l_emp_idnum; 
SET @l_fullname = l_fullname; 
SET @l_taxep_code = l_taxep_code; CLOSE emp_info_csr; 
OPEN salary_csr; 
FETCH salary_csr INTO l_basic; 
SET @l_basic = l_basic; 
CLOSE salary_csr; 
OPEN ut_tardy_csr; 
FETCH ut_tardy_csr INTO l_ut_tardy; 
IF(l_ut_tardy IS NOT NULL) THEN 
SET @l_ut_tardy = l_ut_tardy; 
ELSE 
SET @l_ut_tardy = 0; 
END IF; 
CLOSE ut_tardy_csr; 
OPEN absences_csr; 
FETCH absences_csr INTO l_absences; 
IF(l_absences IS NOT NULL) THEN 
SET @l_absences = l_absences; 
ELSE 
SET @l_absences = 0; 
END IF; 
CLOSE absences_csr; 
OPEN ot_csr; 
FETCH ot_csr INTO l_ot; 
IF(l_ot IS NOT NULL) THEN 
SET @l_ot = l_ot; 
ELSE 
SET @l_ot = 0; 
END IF; 
CLOSE ot_csr; 
OPEN cola_csr; 
FETCH cola_csr INTO l_cola; 
IF(l_cola IS NOT NULL) THEN 
SET @l_cola = l_cola; 
ELSE 
SET @l_cola = 0; 
END IF; 
CLOSE cola_csr;	 
IF no_more_emp=1 THEN 
LEAVE emp_loop; 
END IF; 
SET str_query = CONCAT("select @l_emp_idnum, @l_fullname, @l_taxep_code, @l_basic, @l_ut_tardy, @l_absences, @l_ot, @l_cola"); 
CALL fetchPsaEntry(m_period_start,m_period_end,y_period_start,y_period_end,l_id,1); 
CALL fetchPsaAmendment(m_period_start,m_period_end,y_period_start,y_period_end,l_id,1); 
CALL fetchPsaEntry(m_period_start,m_period_end,y_period_start,y_period_end,l_id,2); 
CALL fetchPsaLoan(m_period_start,m_period_end,y_period_start,y_period_end,l_id,2); 
CALL fetchPsaAmendment(m_period_start,m_period_end,y_period_start,y_period_end,l_id,2); 
DROP VIEW IF EXISTS psa_vw; 
CREATE VIEW psa_vw as select distinct a.psa_id, b.psa_name, b.psa_type from z_temp a inner join payroll_ps_account b on (b.psa_id=a.psa_id); 
SET @earnings = @l_basic + @l_ut_tardy + @l_absences + @l_ot + @l_cola; 
SET @deductions = 0; 
BEGIN 
DECLARE no_psa INT; 
DECLARE CONTINUE HANDLER FOR NOT FOUND SET no_psa=1; 
SET no_psa=0; 
set c_psa_id=0; 
OPEN psa_csr; 
psa_loop:
WHILE(no_psa=0) DO 
FETCH psa_csr INTO l_psa; 
OPEN psa_amt_csr; 
FETCH psa_amt_csr INTO l_amt; 
IF(checkPsaType(l_psa)) THEN 
SET @cmd = CONCAT("SET @`", getPayrollPsAccountById(l_psa), "` = ", l_amt); 
ELSE 
SET @cmd = CONCAT("SET @`", getPayrollPsAccountById(l_psa), "` = -", l_amt); 
END IF; 
PREPARE stmt FROM @cmd; 
EXECUTE stmt; 
DEALLOCATE PREPARE stmt; 
SET @cmd = ''; 
SET str_query =+ CONCAT(str_query, ", @`",getPayrollPsAccountById(l_psa),"`"); 
IF(checkPsaType(l_psa)) THEN 
SET @cmd = CONCAT("SET @earnings = @earnings + @`",getPayrollPsAccountById(l_psa),"`"); 
ELSE 
SET @cmd = CONCAT("SET @deductions = @deductions + @",getPayrollPsAccountById(l_psa)); 
END IF; 
PREPARE stmt FROM @cmd; 
EXECUTE stmt;				 
DEALLOCATE PREPARE stmt; 
SET @cmd = ''; 
SET @net = @earnings + @deductions; 
SET c_psa_id = c_psa_id + 1; 
IF(c_psa_id = (select count(distinct a.psa_id) from z_temp a inner join payroll_ps_account b on (b.psa_id=a.psa_id) where b.psa_type=1)) THEN 
SET str_query =+ CONCAT(str_query, ", @earnings"); 
END IF; 
IF(c_psa_id = (select count(distinct psa_id) from z_temp)) THEN 
SET str_query =+ CONCAT(str_query, ", @deductions, @net"); 
SET no_psa=1; 
END IF; 
CLOSE psa_amt_csr; 
IF no_psa=1 
THEN LEAVE psa_loop; 
END IF; 
END WHILE psa_loop; 
CLOSE psa_csr; 
END; 
OPEN sss_er_csr; 
FETCH sss_er_csr INTO l_sss_er; 
SET @l_sss_er = l_sss_er; 
CLOSE sss_er_csr;
OPEN sss_ec_csr; 
FETCH sss_ec_csr INTO l_sss_ec; 
SET @l_sss_ec = l_sss_ec; 
CLOSE sss_ec_csr;
OPEN phic_er_csr; 
FETCH phic_er_csr INTO l_phic_er; 
SET @l_phic_er = l_phic_er; 
CLOSE phic_er_csr;
OPEN hdmf_er_csr; 
FETCH hdmf_er_csr INTO l_hdmf_er; 
SET @l_hdmf_er = l_hdmf_er; 
CLOSE hdmf_er_csr;
SET str_query =+ CONCAT(str_query, ", @l_sss_er, @l_sss_ec, @l_phic_er, @l_hdmf_er");
SET @l_emp_count=@l_emp_count+1; 
SET @fetch_cmd = str_query; 
PREPARE stmt FROM @fetch_cmd; 
EXECUTE stmt; 
DEALLOCATE PREPARE stmt; 
END WHILE emp_loop; 
CLOSE emp_csr; 
SET no_more_emp=0; 
END$$

DELIMITER ;

/* Function  structure for function  `checkIfEntryIsNotZero` */

/*!50003 DROP FUNCTION IF EXISTS `checkIfEntryIsNotZero` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` FUNCTION `checkIfEntryIsNotZero`(m_period_start INT, m_period_end INT, y_period_start INT, y_period_end INT, emp_id INT, psa_id INT) RETURNS tinyint(1)
BEGIN DECLARE val tinyint;
 IF((select sum(ppe_amount) as total from payroll_paystub_entry a inner join payroll_pay_stub b on (b.paystub_id=a.paystub_id) inner join payroll_paystub_report c on (c.paystub_id=b.paystub_id) inner join payroll_pay_period d on (d.payperiod_id=c.payperiod_id) where a.psa_id=psa_id  and c.emp_id=emp_id and (d.payperiod_period >= m_period_start and d.payperiod_period <= m_period_end) and (d.payperiod_period_year >= y_period_start and d.payperiod_period_year <= y_period_end)) > 0) THEN SET val = TRUE; ELSE SET val = FALSE; END IF; RETURN val;
 END */$$
DELIMITER ;

/* Function  structure for function  `checkIfNotZero` */

/*!50003 DROP FUNCTION IF EXISTS `checkIfNotZero` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` FUNCTION `checkIfNotZero`(m_period_start INT, m_period_end INT, y_period_start INT, y_period_end INT, emp_id INT, param_id INT, tble varchar(30)) RETURNS tinyint(1)
BEGIN
 DECLARE val tinyint;IF tble = "TA" THEN	 IF ((select sum(emp_tarec_amtperrate) as total from ta_emp_rec a	 inner join ta_tbl b on (b.tatbl_id=a.tatbl_id)	 inner join payroll_pay_period c on (c.payperiod_id=a.payperiod_id)	 where a.tatbl_id=param_id	 and a.emp_id=emp_id  	 and (c.payperiod_period >= m_period_start and c.payperiod_period <= m_period_end)	 and (c.payperiod_period_year >= y_period_start and c.payperiod_period_year <= y_period_end)) > 0) THEN	 SET val = TRUE;	 ELSE	 SET val = FALSE;	 END IF;ELSEIF tble = "ENTRY" THEN	IF ((select sum(ppe_amount) as total from payroll_paystub_entry a	inner join payroll_pay_stub b on (b.paystub_id=a.paystub_id)	inner join payroll_paystub_report c on (c.paystub_id=b.paystub_id)	inner join payroll_pay_period d on (d.payperiod_id=c.payperiod_id)	where a.psa_id=param_id 	and c.emp_id=emp_id 	and (d.payperiod_period >= m_period_start and d.payperiod_period <= m_period_end)	and (d.payperiod_period_year >= y_period_start and d.payperiod_period_year <= y_period_end)) > 0) THEN	SET val = TRUE;	ELSE	SET val = FALSE;	END IF;END IF; RETURN val;
 END */$$
DELIMITER ;

/* Function  structure for function  `checkIfPayperiodExists` */

/*!50003 DROP FUNCTION IF EXISTS `checkIfPayperiodExists` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` FUNCTION `checkIfPayperiodExists`(p_period INT, p_year INT) RETURNS tinyint(1)
BEGIN DECLARE val tinyint;
 IF ((select count(1) from payroll_pay_period where payperiod_period=p_period and payperiod_period_year=p_year) > 0) THEN SET val = true; ELSE SET val = false; END IF; RETURN val;
 END */$$
DELIMITER ;

/* Function  structure for function  `checkIfPSAExists` */

/*!50003 DROP FUNCTION IF EXISTS `checkIfPSAExists` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` FUNCTION `checkIfPSAExists`(p_psa_id int, p_emp_id int) RETURNS tinyint(1)
BEGIN DECLARE val BOOL; if(exists(select 1 from z_temp where psa_id=p_psa_id and emp_id=p_emp_id) = 1) then  set val = true; else set val = false; end if; return val;
 END */$$
DELIMITER ;

/* Function  structure for function  `checkNonZeroInAmendment` */

/*!50003 DROP FUNCTION IF EXISTS `checkNonZeroInAmendment` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` FUNCTION `checkNonZeroInAmendment`(m_period_start int, m_period_end int, y_period_start varchar(10), y_period_end varchar(10), m_psa_id INT) RETURNS tinyint(1)
BEGIN
 DECLARE val BOOL; IF(((select sum(a.amendemp_amount) from payroll_ps_amendemp a inner join payroll_ps_amendment b on (b.psamend_id=a.psamend_id) inner join payroll_pay_stub c on (c.paystub_id=a.paystub_id) inner join payroll_pay_period d on (d.payperiod_id=c.payperiod_id) where b.psa_id=m_psa_id and (d.payperiod_period >= m_period_start and d.payperiod_period <= m_period_end) and (d.payperiod_period_year >= y_period_start and d.payperiod_period_year <= y_period_end))> 0)) THEN set val = true; ELSE  set val = false; END IF; return val;
 END */$$
DELIMITER ;

/* Function  structure for function  `checkNonZeroInEntry` */

/*!50003 DROP FUNCTION IF EXISTS `checkNonZeroInEntry` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` FUNCTION `checkNonZeroInEntry`(m_period_start int, m_period_end int, y_period_start varchar(10), y_period_end varchar(10), m_psa_id INT) RETURNS tinyint(1)
BEGIN
 DECLARE val BOOL; IF(((select sum(ppe_amount) as total from payroll_paystub_entry a inner join payroll_pay_stub b on (b.paystub_id=a.paystub_id) inner join payroll_paystub_report c on (c.paystub_id=b.paystub_id) inner join payroll_pay_period d on (d.payperiod_id=c.payperiod_id) where a.psa_id=m_psa_id and (d.payperiod_period >= m_period_start and d.payperiod_period <= m_period_end) and (d.payperiod_period_year >= y_period_start and d.payperiod_period_year <= y_period_end)) > 0)) THEN set val = true; ELSE  set val = false; END IF; return val;
 END */$$
DELIMITER ;

/* Function  structure for function  `checkNonZeroInLoan` */

/*!50003 DROP FUNCTION IF EXISTS `checkNonZeroInLoan` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` FUNCTION `checkNonZeroInLoan`(m_period_start int, m_period_end int, y_period_start varchar(10), y_period_end varchar(10), m_psa_id INT) RETURNS tinyint(1)
BEGIN
 DECLARE val BOOL; IF(((select sum(a.loansum_payment) from loan_detail_sum a inner join loan_info b on (b.loan_id=a.loan_id) inner join payroll_pay_stub c on (c.paystub_id=a.paystub_id) inner join payroll_pay_period d on (d.payperiod_id=c.payperiod_id) where b.psa_id=m_psa_id and (d.payperiod_period >= m_period_start and d.payperiod_period <= m_period_start) and (d.payperiod_period_year >= y_period_start and d.payperiod_period_year <= y_period_end)) > 0)) THEN set val = true; ELSE  set val = false; END IF; return val;
 END */$$
DELIMITER ;

/* Function  structure for function  `checkPsaType` */

/*!50003 DROP FUNCTION IF EXISTS `checkPsaType` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` FUNCTION `checkPsaType`(p_id INT) RETURNS tinyint(1)
BEGIN
 DECLARE val BOOL; IF((SELECT psa_type FROM payroll_ps_account WHERE psa_id=p_id)=1) THEN SET val = true; ELSE SET val = false; END IF; RETURN val;
 END */$$
DELIMITER ;

/* Function  structure for function  `fetchDept` */

/*!50003 DROP FUNCTION IF EXISTS `fetchDept` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` FUNCTION `fetchDept`(p_empidnum varchar(20)) RETURNS varchar(300) CHARSET latin1
BEGIN
 DECLARE val varchar(300); select CONCAT(a.ud_name," - ",a.ud_desc) INTO val from app_userdept a inner join emp_masterfile b on (b.ud_id=a.ud_id) where b.emp_idnum=p_empidnum and b.emp_stat!=0; RETURN val;
 END */$$
DELIMITER ;

/* Function  structure for function  `getPayrollPsAccountById` */

/*!50003 DROP FUNCTION IF EXISTS `getPayrollPsAccountById` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` FUNCTION `getPayrollPsAccountById`(p_id INT) RETURNS varchar(300) CHARSET latin1
BEGIN
 DECLARE val varchar(300); SELECT REPLACE(psa_name,' ','_') INTO val FROM payroll_ps_account WHERE psa_id=p_id; SET val = REPLACE(val,'/','_'); RETURN val;
 END */$$
DELIMITER ;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;

DELIMITER $$

DROP FUNCTION IF EXISTS `check_column`$$

CREATE DEFINER=`root`@`%` FUNCTION `check_column`(tble varchar(30), col varchar(30)) RETURNS tinyint(1)
BEGIN
 DECLARE val tinyint(1);
 IF NOT EXISTS ( SELECT * FROM information_schema.columns WHERE table_name = tble AND column_name = col AND table_schema = DATABASE() ) THEN
 SET val = TRUE;
 ELSE
 SET val = FALSE;
 END IF;
 RETURN val;
 END$$

DELIMITER ;

DELIMITER $$

DROP PROCEDURE IF EXISTS `alter_column`$$

CREATE DEFINER=`root`@`%` PROCEDURE `alter_column`(tble varchar(30), col varchar(30), statment text)
BEGIN
 IF (select check_column(tble,col)) THEN
	 SET @str_query = statment;
	 PREPARE stmt FROM @str_query;
	 EXECUTE stmt;
	 DEALLOCATE PREPARE stmt;
 END IF;
 END$$

DELIMITER ;

DELIMITER $$

DROP TRIGGER IF EXISTS `afterUpdate` $$

CREATE TRIGGER `afterUpdate` AFTER UPDATE on `cf_head`
FOR EACH ROW BEGIN
	UPDATE app_formula_keywords SET app_fkey_name = NEW.cfhead_name WHERE cfhead_id = NEW.cfhead_id;
END$$

DELIMITER ;

DELIMITER $$

DROP TRIGGER IF EXISTS `afterCreate`$$

CREATE TRIGGER `afterCreate` AFTER INSERT on `cf_head`
FOR EACH ROW BEGIN
	INSERT INTO app_formula_keywords (app_fkey_name, cfhead_id, app_fkey_isactive, app_fkey_query, app_fkey_vars, app_fkey_result) VALUES (NEW.cfhead_name, NEW.cfhead_id, NEW.cfhead_stat,CONCAT("select cfdetail_rec from cf_detail where payperiod_id=? and emp_id=? and cfhead_id=",NEW.cfhead_id),"array($payperiod_id,$emp_id)","cfdetail_rec");
END$$

DELIMITER ;

DELIMITER $$

DROP TRIGGER IF EXISTS `afterDelete`$$

CREATE TRIGGER `afterDelete` AFTER DELETE on `cf_head`
FOR EACH ROW BEGIN
	delete from app_formula_keywords where cfhead_id=OLD.cfhead_id;
END$$

DELIMITER ;

DELIMITER $$

DROP TRIGGER IF EXISTS `leaveCreate`$$

create trigger `leaveCreate` AFTER INSERT on `leave_type` 
for each row BEGIN
	INSERT INTO app_formula_keywords (app_fkey_name, leave_id, app_fkey_isactive, app_fkey_query, app_fkey_vars, app_fkey_result) VALUES (NEW.leave_name, NEW.leave_id, 1,CONCAT("select emp_leav_rec_leavedays from emp_leave_rec a join emp_leave b on (b.empleave_id=a.empleave_id) join leave_type c on (c.leave_id=b.leave_id) where a.payperiod_id=? and a.emp_id=? and b.leave_id=",NEW.leave_id),"array($payperiod_id,$emp_id)","emp_leav_rec_leavedays");
END;
$$

DELIMITER ;

DELIMITER $$

DROP TRIGGER IF EXISTS `leaveUpdate`$$

CREATE TRIGGER `leaveUpdate` AFTER UPDATE on `leave_type`
FOR EACH ROW BEGIN
	 UPDATE app_formula_keywords SET app_fkey_name = NEW.leave_name WHERE leave_id = NEW.leave_id;
END$$

DELIMITER ;