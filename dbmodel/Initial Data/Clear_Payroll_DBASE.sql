-- MySQL dump 10.13  Distrib 5.5.16, for Win32 (x86)
--
-- Host: localhost    Database: payroll_db
-- ------------------------------------------------------
-- Server version	5.5.16

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- 
-- Truncate data for Database `payroll_db`
/*TRUNCATE TABLE `app_userstypeaccess`*/;
/*TRUNCATE TABLE `app_usertype`*/;
TRUNCATE TABLE `app_ps_passwd`;

TRUNCATE TABLE `app_userdept`;
INSERT INTO `app_userdept`(`ud_id`,`ud_code`,`ud_name`,`ud_desc`,`ud_parent`) values ( NULL,'0','IT Dept.','Information Technology Department','0');

TRUNCATE TABLE `app_users`;
INSERT INTO `app_users`(`user_id`,`emp_id`,`ud_id`,`user_name`,`user_fullname`,`user_password`,`user_type`,`user_status`,`user_email`,`user_emailpass`,`user_smtp`,`user_port`,`user_comp_list`,`user_picture`) values ('1','0','1','jimabignay','Jason Mabignay','8020a11ea1be81e9fb1e0a5deda6fffc','Super Administrator','1','jason.mabignay@sigmasoft.com.ph','test','mail.sample.com.ph','25',NULL,NULL);
INSERT INTO `app_users` set `user_id`='2',`emp_id`='0',`ud_id`='1',`user_name`='irsalvador',`user_fullname`='Ishmael Rolph C. Salvador',`user_password`='19ced5b5c528a2c4aa19aa27e52c2d7d',`user_type`='Super Administrator',`user_status`='1',`user_email`='irsalvador06@gmail.com',`user_emailpass`='',`user_smtp`='',`user_port`='',`user_comp_list`='',`user_picture`='';
INSERT INTO `app_users` set `user_id`='3',`emp_id`='0',`ud_id`='1',`user_name`='duntiveros',`user_fullname`='Dionisio M. Untiveros Jr.',`user_password`='759c2375c925d29a9c48e98147c46dbc',`user_type`='Super Administrator',`user_status`='1',`user_email`='duntiverosjr@gmail.com',`user_emailpass`='',`user_smtp`='',`user_port`='',`user_comp_list`='',`user_picture`='';
INSERT INTO `app_users` set `user_id`='4',`emp_id`='0',`ud_id`='1',`user_name`='admin',`user_fullname`='Administrator',`user_password`='21232f297a57a5a743894a0e4a801fc3',`user_type`='Administrator',`user_status`='1',`user_email`='',`user_emailpass`='',`user_smtp`='',`user_port`='',`user_comp_list`='',`user_picture`='';

TRUNCATE TABLE `audittrail`;
TRUNCATE TABLE `audittrail_log`;
TRUNCATE TABLE `bank_empgroup`;
TRUNCATE TABLE `bank_info`;
TRUNCATE TABLE `bank_infoemp`;
TRUNCATE TABLE `bir_alphalist_prev_emp`;
TRUNCATE TABLE `branch_info`;
TRUNCATE TABLE `cf_detail`;
TRUNCATE TABLE `cf_head`;
TRUNCATE TABLE `company_info`;
TRUNCATE TABLE `dependent_info`;
TRUNCATE TABLE `emp_benefits`;
TRUNCATE TABLE `emp_category`;
TRUNCATE TABLE `emp_deductions_detail`;
TRUNCATE TABLE `emp_leave`;
TRUNCATE TABLE `emp_leave_rec`;
TRUNCATE TABLE `emp_masterfile`;
TRUNCATE TABLE `emp_personal_info`;
TRUNCATE TABLE `emp_position`;
TRUNCATE TABLE `emp_type`;
TRUNCATE TABLE `emp_position`;
TRUNCATE TABLE `empdec_rel`;
TRUNCATE TABLE `emr_personal_info`;
TRUNCATE TABLE `factor_rate`;
TRUNCATE TABLE `import_details`;
TRUNCATE TABLE `import_head`;
TRUNCATE TABLE `import_ytd_details`;
TRUNCATE TABLE `import_ytd_head`;
TRUNCATE TABLE `leave_type`;
TRUNCATE TABLE `loan_detail_sum`;
TRUNCATE TABLE `loan_info`;
TRUNCATE TABLE `ot_record`;
TRUNCATE TABLE `ot_tbl`;
TRUNCATE TABLE `ot_tr`;
TRUNCATE TABLE `pay_period`;
TRUNCATE TABLE `payroll_bank_details`;
TRUNCATE TABLE `payroll_bank_report_data`;
TRUNCATE TABLE `payroll_bank_reports`;
TRUNCATE TABLE `payroll_comp`;
TRUNCATE TABLE `payroll_pay_period`;
TRUNCATE TABLE `payroll_pay_period_sched`;
TRUNCATE TABLE `payroll_pay_stub`;
TRUNCATE TABLE `payroll_paystub_entry`;
TRUNCATE TABLE `payroll_paystub_report`;
TRUNCATE TABLE `payroll_pps_user`;
TRUNCATE TABLE `payroll_priority_comp`;
DELETE FROM `payroll_ps_account` WHERE psa_id > 39;
alter table `payroll_ps_account` auto_increment=40 comment='' row_format=DYNAMIC;
TRUNCATE TABLE `payroll_ps_account_link`;
TRUNCATE TABLE `payroll_ps_amendemp`;
TRUNCATE TABLE `payroll_ps_amendment`;
TRUNCATE TABLE `period_benloanduc_sched`;
TRUNCATE TABLE `salary_info`;
TRUNCATE TABLE `ta_emp_rec`;
TRUNCATE TABLE `tax_employer`;
TRUNCATE TABLE `tks_uploadta_details`;
TRUNCATE TABLE `tks_uploadta_header`;
TRUNCATE TABLE `z_temp`;
ALTER TABLE `emp_masterfile` change `emp_idnum` `emp_idnum` varchar (20)  NULL  COLLATE latin1_swedish_ci;
ALTER TABLE `payroll_pay_period` change `payperiod_period_year` `payperiod_period_year` varchar (10)  NULL  COLLATE latin1_swedish_ci;
