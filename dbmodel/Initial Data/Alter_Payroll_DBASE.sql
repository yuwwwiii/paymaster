-- MySQL dump 10.13  Distrib 5.5.16, for Win32 (x86)
--
-- Host: localhost    Database: payroll_db
-- ------------------------------------------------------
-- Server version	5.5.16

/*@note: bank employee info, affected the process of payroll*/

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

/* @note: ICS(20120926)
	If your ALTER statement includes add column or just renaming the column without changing its properties, please use the stored procedure to avoid error in running this script.

	For adding new column, follow this syntax:
	CALL alter_column('TABLE NAME','COLUMN NAME',"ALTER STATEMENT");
	eg: CALL alter_column('ot_rates','otr_ord',"ALTER TABLE `ot_rates` add column `otr_ord` int  DEFAULT '0' NULL  after `otr_updatewhen`");
	
	For renaming column, follow this syntax:
	CALL alter_column('TABLE NAME','NEW COLUMN NAME',"ALTER STATEMENT");
	eg: CALL alter_column('bank_infoemp','banklist_id',"ALTER TABLE `bank_infoemp` change `bank_id` `banklist_id` int (10)UNSIGNED   NOT NULL");
*/
-- 
-- Truncate data for Database `payroll_db`
ALTER TABLE `emp_masterfile` change `emp_idnum` `emp_idnum` varchar (20)  NULL  COLLATE latin1_swedish_ci;
ALTER TABLE `payroll_pay_period` change `payperiod_period_year` `payperiod_period_year` varchar (10)  NULL  COLLATE latin1_swedish_ci;
ALTER TABLE `bank_info` change `bank_routing_number` `bank_routing_number` varchar (10)  NULL;
ALTER TABLE `ta_emp_rec` change `emp_tarec_nohrday` `emp_tarec_nohrday` decimal (10,5)  NULL;
ALTER TABLE `branch_info` change `branchinfo_code` `branchinfo_code` varchar (60)  NULL  COLLATE latin1_swedish_ci;
ALTER TABLE `emp_position` change `post_code` `post_code` varchar (50)  NULL  COLLATE latin1_general_ci , change `post_name` `post_name` varchar (150)  NULL  COLLATE latin1_general_ci;
ALTER TABLE `factor_rate` change `fr_hrperday` `fr_hrperday` decimal (11,5)UNSIGNED   NULL , change `fr_dayperweek` `fr_dayperweek` decimal (11,5)UNSIGNED   NULL , change `fr_dayperyear` `fr_dayperyear` decimal (11,5)UNSIGNED   NULL , change `fr_hrperweek` `fr_hrperweek` decimal (11,5)UNSIGNED   NULL;
ALTER TABLE `ot_rates` change `otr_addwhen` `otr_addwhen` timestamp  DEFAULT CURRENT_TIMESTAMP NULL;
/*ALTER TABLE `ot_rates` add column `otr_ord` int  DEFAULT '0' NULL  after `otr_updatewhen`;*/
CALL alter_column('ot_rates','otr_ord',"ALTER TABLE `ot_rates` add column `otr_ord` int  DEFAULT '0' NULL  after `otr_updatewhen`");
ALTER TABLE `ot_rates` change `otr_factor` `otr_factor` decimal (10,5) DEFAULT '0' NULL;
ALTER TABLE `emp_masterfile` change `emp_stat` `emp_stat` int (10)UNSIGNED  DEFAULT '7' NULL;
ALTER TABLE `emp_masterfile` change `emp_id` `emp_id` int (10)UNSIGNED   NOT NULL;
ALTER TABLE `emp_personal_info` change `pi_id` `pi_id` int (10)UNSIGNED   NOT NULL;

/*Aug 09,2012 adjust for OrangeHRM_iPAY */
ALTER TABLE `payroll_pay_period_sched` change `pps_pri_daymonth` `pps_pri_daymonth` varchar (10)  NOT NULL , change `pps_secnd_daymonth` `pps_secnd_daymonth` varchar (10)  NOT NULL , change `pps_pri_trans_daymonth` `pps_pri_trans_daymonth` varchar (10)  NOT NULL , change `pps_secnd_trans_daymonth` `pps_secnd_trans_daymonth` varchar (10)  NOT NULL;
/* ++++++++++++++++++++++++++++++++++ */

/*Aug 1,2012 adjust for OrangeHRM_iPAY*/
/*ALTER TABLE `payroll_comp` add column `fr_id` int (10) DEFAULT '0' NULL  after `ot_id`, add column `pps_id` int (10) DEFAULT '0' NULL  after `fr_id`,change `ot_id` `ot_id` int (10) DEFAULT '0' NULL;*/
CALL alter_column('payroll_comp','fr_id',"ALTER TABLE `payroll_comp` add column `fr_id` int (10) DEFAULT '0' NULL  after `ot_id`, add column `pps_id` int (10) DEFAULT '0' NULL  after `fr_id`,change `ot_id` `ot_id` int (10) DEFAULT '0' NULL");
ALTER TABLE `payroll_comp` change `pc_addwhen` `pc_addwhen` timestamp  DEFAULT CURRENT_TIMESTAMP NULL;
/* ++++++++++++++++++++++++++++++++++ */

/*@note: IR Modify*/
CALL alter_column('company_info','comp_industry',"alter table `company_info` add column `comp_industry` varchar (150)  NULL  after `comp_code`");
/*alter table `company_info` add column `comp_industry` varchar (150)  NULL  after `comp_code`;*/
CALL alter_column('branch_info','branchinfo_tin',"alter table `branch_info` add column `branchinfo_tin` varchar (50)  NULL  after `branchinfo_name`");
CALL alter_column('branch_info','branchinfo_sss',"alter table `branch_info` add column `branchinfo_sss` varchar (50)  NULL  after `branchinfo_tin`");
CALL alter_column('branch_info','branchinfo_phic',"alter table `branch_info` add column `branchinfo_phic` varchar (50)  NULL  after `branchinfo_sss`");
CALL alter_column('branch_info','branchinfo_hdmf',"alter table `branch_info` add column `branchinfo_hdmf` varchar (50)  NULL  after `branchinfo_phic`");
ALTER TABLE `branch_info` change `branchinfo_name` `branchinfo_name` varchar (150)  NULL  COLLATE latin1_swedish_ci;
/*alter table `branch_info` add column `branchinfo_tin` varchar (50)  NULL  after `branchinfo_name`, 
add column `branchinfo_sss` varchar (50)  NULL  after `branchinfo_tin`, 
add column `branchinfo_phic` varchar (50)  NULL  after `branchinfo_sss`, 
add column `branchinfo_hdmf` varchar (50)  NULL  after `branchinfo_phic`,
change `branchinfo_name` `branchinfo_name` varchar (150)  NULL  COLLATE latin1_swedish_ci*/;
CALL alter_column('branch_info','branchinfo_email',"alter table `branch_info` add column `branchinfo_email` varchar (150)  NULL  after `branchinfo_hdmf`");
/*alter table `branch_info` add column `branchinfo_email` varchar (150)  NULL  after `branchinfo_hdmf`*/;
CALL alter_column('branch_info','comptype_id',"alter table `branch_info` add column `comptype_id` int   NULL  after `comp_id`");
/*alter table `branch_info` add column `comptype_id` int   NULL  after `comp_id`*/;
CALL alter_column('branch_info','branch_industry',"alter table `branch_info` add column `branch_industry` varchar (150)  NULL  after `comptype_id`");
/*alter table `branch_info` add column `branch_industry` varchar (150)  NULL  after `comptype_id`*/;
CALL alter_column('emp_personal_info','pi_nickname',"alter table `emp_personal_info` add column `pi_nickname` varchar (60)  NULL  after `pi_lname`");
/*alter table `emp_personal_info` add column `pi_nickname` varchar (60)  NULL  after `pi_lname`*/;
CALL alter_column('bank_info','branchinfo_id',"alter table `bank_info` add column `branchinfo_id` int (10)  NULL  after `comp_id`");
/*alter table `bank_info` add column `branchinfo_id` int (10)  NULL  after `comp_id`*/;
CALL alter_column('emp_leave','empleave_credit',"alter table `emp_leave` change `empleave_creadit` `empleave_credit` decimal (10,2)  NULL");
/*alter table `emp_leave` change `empleave_creadit` `empleave_credit` decimal (10,2)  NULL;*/
CALL alter_column('bank_infoemp','bankiemp_perc',"alter table `bank_infoemp` add column `bankiemp_perc` decimal (10,2)  NULL  after `bankiemp_swift_code`");
/*alter table `bank_infoemp` add column `bankiemp_perc` decimal (10,2)  NULL  after `bankiemp_swift_code`*/;
/* ++++++++++++++++ */


/*DELETE FROM `emp_201status` where `emp201status_id`='7'*/;

/*Table structure for table `app_wagerate` */
/* DROP TABLE IF EXISTS `app_wagerate`; */
CREATE TABLE IF NOT EXISTS `app_wagerate` (  
  `wrate_id` int (10) NOT NULL AUTO_INCREMENT, 
  `region_id` int (10)  NOT NULL,
  `wrate_name` varchar (225), 
  `wrate_sec_ind` varchar (225), 
  `wrate_minwagerate` decimal (12,5), 
  `wrate_addwho` varchar (50), 
  `wrate_addwhen` timestamp DEFAULT CURRENT_TIMESTAMP, 
  `wrate_updatewho` varchar (50), 
  `wrate_updatewhen` datetime , PRIMARY KEY (`wrate_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*Table structure for table `app_wagerate` */

/*Table structure for table `app_audittrail` */
DROP TABLE IF EXISTS `app_audittrail`;
CREATE TABLE `app_audittrail` (  
  `audit_id` int (10) NOT NULL AUTO_INCREMENT, 
  `audit_username` varchar (225), 
  `audit_datetime` timestamp DEFAULT CURRENT_TIMESTAMP, 
  `audit_remarks` text, 
  `comp_id` int(11) DEFAULT NULL, 
  PRIMARY KEY (`audit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*Data for the table `app_audittrail` */
CALL alter_column('app_userdept','ud_code',"ALTER TABLE `app_userdept` add column `ud_code` varchar (10)  NULL  after `ud_id`");
/*ALTER TABLE `app_userdept` add column `ud_code` varchar (10)  NULL  after `ud_id`;*/
CALL alter_column('emp_type','empclass_id',"ALTER TABLE `emp_type` add column `empclass_id` int (10)  NULL  after `emptype_id`");
CALL alter_column('emp_type','emptype_rank',"ALTER TABLE `emp_type` add column `emptype_rank` int (10)  NULL  after `emptype_name`");
/*ALTER TABLE `emp_type` add column `empclass_id` int (10)  NULL  after `emptype_id`, 
add column `emptype_rank` int (10)  NULL  after `emptype_name`;*/
CALL alter_column('bank_list','banklist_desc',"ALTER TABLE `bank_list` add column `banklist_desc` varchar (150)  NULL  after `banklist_name`");
/*ALTER TABLE `bank_list` add column `banklist_desc` varchar (150)  NULL  after `banklist_name`;*/
CALL alter_column('emp_masterfile','branchinfo_id',"ALTER TABLE `emp_masterfile` add column `branchinfo_id` int (10)UNSIGNED   NOT NULL  after `pi_id`");
/*ALTER TABLE `emp_masterfile` add column `branchinfo_id` int (10)UNSIGNED   NOT NULL  after `pi_id`;*/
CALL alter_column('app_users','user_branch_list',"ALTER TABLE `app_users` add column `user_branch_list` text   NULL  after `user_comp_list`");
CALL alter_column('app_users','user_201stat_list',"ALTER TABLE `app_users` add column `user_201stat_list` text   NULL  after `user_branch_list`");
/*ALTER TABLE `app_users` add column `user_branch_list` text   NULL  after `user_comp_list`, 
add column `user_201stat_list` text   NULL  after `user_branch_list`;*/
CALL alter_column('bank_infoemp','banklist_id',"ALTER TABLE `bank_infoemp` change `bank_id` `banklist_id` int (10)UNSIGNED   NOT NULL");
/*ALTER TABLE `bank_infoemp` change `bank_id` `banklist_id` int (10)UNSIGNED   NOT NULL;*/
CALL alter_column('factor_rate','wrate_id',"ALTER TABLE `factor_rate` add column `wrate_id` int (10)  NOT NULL  after `fr_id`");
/*ALTER TABLE `factor_rate` add column `wrate_id` int (10)  NOT NULL  after `fr_id`;*/

/*Table structure for table `emp_classification` */
/* DROP TABLE IF EXISTS `emp_classification`; */
CREATE TABLE IF NOT EXISTS `emp_classification` (  
  `empclass_id` int NOT NULL AUTO_INCREMENT , 
  `empclass_name` varchar(50), 
  `empclass_ord` int(10), 
  `empclass_addwho` varchar(50), 
  `empclass_addwhen` timestamp DEFAULT CURRENT_TIMESTAMP, 
  `empclass_updatewho` varchar(50), 
  `empclass_updatewhen` datetime, 
  PRIMARY KEY (`empclass_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*Table structure for table `emp_classification` */

/*Table structure for table `branch_info` */
/* DROP TABLE IF EXISTS `branch_info`; */
CREATE TABLE IF NOT EXISTS `branch_info` (
  `branchinfo_id` int(11) NOT NULL AUTO_INCREMENT,
  `comp_id` int(11) DEFAULT NULL,
  `comptype_id` int(11) DEFAULT NULL,
  `branch_industry` varchar(150) DEFAULT NULL,
  `branchinfo_code` varchar(30) DEFAULT NULL,
  `branchinfo_name` varchar(150) DEFAULT NULL,
  `branchinfo_tin` varchar(50) DEFAULT NULL,
  `branchinfo_sss` varchar(50) DEFAULT NULL,
  `branchinfo_phic` varchar(50) DEFAULT NULL,
  `branchinfo_hdmf` varchar(50) DEFAULT NULL,
  `branchinfo_email` varchar(150) DEFAULT NULL,
  `branchinfo_siteloc` varchar(150) DEFAULT NULL,
  `branchinfo_add` text,
  `branchinfo_contact` varchar(100) DEFAULT NULL,
  `branchinfo_tel1` varchar(30) DEFAULT NULL,
  `branchinfo_tel2` varchar(30) DEFAULT NULL,
  `branchinfo_addwho` varchar(50) DEFAULT NULL,
  `branchinfo_addwhen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `branchinfo_updatewho` varchar(50) DEFAULT NULL,
  `branchinfo_updatewhen` datetime DEFAULT NULL,
  PRIMARY KEY (`branchinfo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*Data for the table `branch_info` */

/* Alter table app_userdept */
alter table `app_userdept` change `ud_code` `ud_code` varchar (45)  NULL  COLLATE latin1_general_ci;
alter table `salary_info` change `salaryinfo_id` `salaryinfo_id` varchar (20)  NOT NULL  COLLATE latin1_swedish_ci;
alter table `bank_infoemp` change `bankiemp_id` `bankiemp_id` int (10)UNSIGNED   NOT NULL;
alter table `dependent_info` change `depnd_id` `depnd_id` varchar (20)  NOT NULL;
CALL alter_column('ot_tbl','ot_istax',"ALTER TABLE `ot_tbl` add column `ot_istax` tinyint (2) DEFAULT '1' NULL  after `ot_desc`");
ALTER TABLE `app_settings` change `set_order` `set_order` int (11) DEFAULT '1' NULL;
CALL alter_column('period_benloanduc_sched','percent_tax',"ALTER TABLE `period_benloanduc_sched` add column `percent_tax` int (10) DEFAULT '0' NULL  after `bldsched_period`");
CALL alter_column('period_benloanduc_sched','s_ltu',"ALTER TABLE `period_benloanduc_sched` add column `s_ltu` int (10) DEFAULT '0' NULL after `percent_tax`");
CALL alter_column('period_benloanduc_sched','s_stat',"ALTER TABLE `period_benloanduc_sched` add column `s_stat` int (10) DEFAULT '0' NULL  after `s_ltu`");
/*ALTER TABLE `tax_policy` change `tp_head_family` `tp_head_family` decimal (11,2)UNSIGNED   NULL , change `tp_married_ind` `tp_married_ind` decimal (11,2)UNSIGNED   NULL , change `tp_each_dependent` `tp_each_dependent` decimal (11,2)UNSIGNED   NULL , change `tp_other_benefits` `tp_other_benefits` decimal (11,2)  NULL , change `tp_max_premium` `tp_max_premium` decimal (11,2)  NULL , change `tp_no_q_dependents` `tp_no_q_dependents` decimal (11,2)  NULL*/

/* Table structure for table `app_settings` */
/* DROP TABLE IF EXISTS `app_settings`; */
CREATE TABLE IF NOT EXISTS `app_settings` (
  `set_id` int(11) NOT NULL AUTO_INCREMENT,
  `set_name` varchar(255) NOT NULL,
  `set_decimal_places` int(11) NOT NULL DEFAULT '5',
  `set_order` int(11) DEFAULT NULL,
  PRIMARY KEY (`set_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;


/*@note JIM(20120829)*/
/* Table structure for table `bank_empgroup` */
/* DROP TABLE IF EXISTS `bank_empgroup`; */
CREATE TABLE IF NOT EXISTS `bank_empgroup` (  
  `bnkgrup_id` int (11) NOT NULL AUTO_INCREMENT , 
  `bankiemp_id` int (10) NOT NULL , 
  `bank_id` int (10) NOT NULL , 
  `bnkgrup_addwho` varchar (100) , 
  `bnkgrup_addwhen` timestamp DEFAULT CURRENT_TIMESTAMP, 
  PRIMARY KEY (`bnkgrup_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/* ++++++++++++++++ */


/*@note ICS(20120919)*/
/*Table structure for table `app_formula_keywords` */
/*DROP TABLE IF EXISTS `app_formula_keywords`; */

CREATE TABLE IF NOT EXISTS `app_formula_keywords` (
  `app_fkey_id` int(11) NOT NULL AUTO_INCREMENT,
  `app_fkey_name` varchar(100) NOT NULL,
  `psa_id` int(11) NOT NULL,
  `tatble_id` int(11) NOT NULL,
  `app_fkey_isactive` int(11) NOT NULL DEFAULT '0',
  `app_fkey_query` text,
  `app_fkey_vars` text,
  `app_fkey_result` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`app_fkey_id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=latin1;

/*Data for the table `app_formula_keywords` */

call alter_column('payroll_ps_account','psa_formula',"alter table `payroll_ps_account` add column `psa_formula` varchar (300)  NULL  after `psa_procode`");
call alter_column('payroll_ps_account','psa_formula_el',"alter table `payroll_ps_account` add column `psa_formula_el` text  NULL  COLLATE latin1_general_ci after `psa_formula`");

/*@note ICS(20120919)*/
/*Table structure for table `z_formula_temp` */
/*DROP TABLE IF EXISTS `z_formula_temp`;*/
create table IF NOT EXISTS `z_formula_temp` (  `paystub_id` int NOT NULL , `psa_id` int NOT NULL , `amount` int NOT NULL );
call alter_column('app_formula_keywords','cfhead_id',"alter table `app_formula_keywords` add column `cfhead_id` int   NULL  after `tatble_id`");
alter table `app_formula_keywords` change `psa_id` `psa_id` int (11)  NULL , change `tatble_id` `tatble_id` int (11)  NULL ;
call alter_column('z_formula_temp','custom_id',"alter table `z_formula_temp` add column `custom_id` varchar (20)  NULL  after `amount`");
alter table `z_formula_temp` change `amount` `amount` decimal (10,5)  NOT NULL;
call alter_column('app_formula_keywords','leave_id',"alter table `app_formula_keywords` add column `leave_id` int (11)  NULL  after `cfhead_id`");
alter table `emp_benefits` change `ben_amount` `ben_amount` decimal (15,6)  NULL , change `ben_payperday` `ben_payperday` decimal (15,6)  NULL;
call alter_column('payroll_pay_period','payperiod_type',"alter table `payroll_pay_period` add column `payperiod_type` varchar (20)  NULL  after `payperiod_freq`");
call alter_column('payroll_pay_period','payperiod_period_to',"alter table `payroll_pay_period` add column `payperiod_period_to` int (5)  NULL  after `payperiod_period`");
call alter_column('payroll_pay_period','payperiod_period_year_to',"alter table `payroll_pay_period` add column `payperiod_period_year_to` varchar (10)  NULL  after `payperiod_period_year`,change `payperiod_period_year` `payperiod_period_year` varchar (10)  NULL  COLLATE latin1_swedish_ci");
alter table `payroll_pay_period` change `payperiod_type` `payperiod_type` tinyint (2) DEFAULT '1' NULL  COLLATE latin1_general_ci;

/* @note ICS(20121108) */
/*Table structure for table `import_ytd_head` */
create table IF NOT EXISTS `import_ytd_head` (  
	`import_ytd_head_id` int NOT NULL AUTO_INCREMENT ,
	`payperiod_id` int NOT NULL ,
	`import_ytd_head_addwhen` timestamp , 
	`import_ytd_head_addwho` varchar (50) , 
	`import_ytd_head_updatewhen` timestamp , 
	`import_ytd_head_updatewho` varchar (50) , 
	PRIMARY KEY (`import_ytd_head_id`)
);
/* @note ICS(20121108) */
/*Table structure for table `import_ytd_details` */
create table IF NOT EXISTS `import_ytd_details` (  
	`import_ytd_detail_id` int NOT NULL AUTO_INCREMENT , 
	`import_ytd_head_id` int NOT NULL , 
	`emp_id` int NOT NULL , 
	`import_ytd_detail_arr` text NOT NULL , 
	`import_ytd_detail_addwho` varchar (50) , 
	`import_ytd_detail_addwhen` timestamp , 
	`import_ytd_detail_updatewho` varchar (50) , 
	`import_ytd_detail_updatewhen` timestamp , 
	PRIMARY KEY (`import_ytd_detail_id`)
);

update `app_modules` set `mnu_id`='144',`mnu_name`='Import Summary/Pay Element',`mnu_desc`='Import Summary/Pay Element',`mnu_icon`='',`mnu_parent`='142',`mnu_ord`='20',`mnu_link`='transaction.php?statpos=ytd_import',`mnu_status`='1',`mnu_link_info`='a:18:{s:8:\"linkpage\";s:15:\"transaction.php\";s:7:\"statpos\";s:12:\"ytd_sumtotal\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:21:\"admin/transaction.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:45:\"admin/transaction/ytd_sumtotal.controller.php\";s:14:\"class_filename\";s:40:\"admin/transaction/ytd_sumtotal.class.php\";s:10:\"class_name\";s:15:\"clsYTD_SumTotal\";s:17:\"template_filename\";s:38:\"admin/transaction/ytd_sumtotal.tpl.php\";s:21:\"templateform_filename\";s:43:\"admin/transaction/ytd_sumtotal_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}' where `mnu_id`='144';
alter table `import_ytd_details` change `import_ytd_detail_addwhen` `import_ytd_detail_addwhen` timestamp  DEFAULT '0000-00-00 00:00:00' NOT NULL , change `import_ytd_detail_updatewhen` `import_ytd_detail_updatewhen` timestamp  DEFAULT '0000-00-00 00:00:00' NOT NULL;
call alter_column('app_users','user_paygroup_list',"alter table `app_users` change `user_201stat_list` `user_paygroup_list` text   NULL  COLLATE latin1_swedish_ci");
alter table `app_userdept` change `ud_code` `ud_code` varchar (200)  NULL  COLLATE latin1_general_ci , change `ud_name` `ud_name` varchar (200)  NULL  COLLATE latin1_general_ci , change `ud_desc` `ud_desc` varchar (200)  NULL  COLLATE latin1_general_ci;
call alter_column('bank_info','bank_building',"alter table `bank_info` add column `bank_building` varchar (255)  NULL  after `bank_contact`");
call alter_column('bank_info','bank_address',"alter table `bank_info` add column `bank_address` varchar (255)  NULL  after `bank_building`");
call alter_column('payroll_bank_reports','pbr_report_type',"alter table `payroll_bank_reports` add column `pbr_report_type` tinyint  DEFAULT '0' NULL  after `pbr_addwhen`");
call alter_column('payroll_bank_reports','pbr_prepared_pos',"alter table `payroll_bank_reports` add column `pbr_prepared_pos` varchar (255)  NULL  after `pbr_prepared_by`");
call alter_column('payroll_bank_reports','pbr_approved_pos',"alter table `payroll_bank_reports` add column `pbr_approved_pos` varchar (255)  NULL  after `pbr_approved_by`");
update `app_modules` set `mnu_id`='148',`mnu_name`='My Payslip',`mnu_desc`='List of All Payslip Process in Payroll',`mnu_icon`='',`mnu_parent`='16',`mnu_ord`='90',`mnu_link`='transaction.php?statpos=mypayslip',`mnu_status`='1',`mnu_link_info`='a:18:{s:8:\"linkpage\";s:15:\"transaction.php\";s:7:\"statpos\";s:9:\"mypayslip\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:21:\"admin/transaction.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:42:\"admin/transaction/mypayslip.controller.php\";s:14:\"class_filename\";s:37:\"admin/transaction/mypayslip.class.php\";s:10:\"class_name\";s:12:\"clsMyPayslip\";s:17:\"template_filename\";s:35:\"admin/transaction/mypayslip.tpl.php\";s:21:\"templateform_filename\";s:40:\"admin/transaction/mypayslip_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}' where `mnu_id`='148';
alter table `ot_record` change `otrec_totalhrs` `otrec_totalhrs` decimal (10,5)  NULL;
alter table `ot_record` change `otrec_subtotal` `otrec_subtotal` decimal (10,5)  NULL;
update `app_modules` set `mnu_id`='115',`mnu_name`='Wage Rate',`mnu_desc`='Wage Rate',`mnu_icon`='',`mnu_parent`='17',`mnu_ord`='100',`mnu_link`='setup.php?statpos=wagerate',`mnu_status`='1',`mnu_link_info`='a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:8:\"wagerate\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:35:\"admin/setup/wagerate.controller.php\";s:14:\"class_filename\";s:30:\"admin/setup/wagerate.class.php\";s:10:\"class_name\";s:11:\"clsWageRate\";s:17:\"template_filename\";s:28:\"admin/setup/wagerate.tpl.php\";s:21:\"templateform_filename\";s:33:\"admin/setup/wagerate_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";s:1:\"1\";s:8:\"chkclass\";s:1:\"1\";s:11:\"chktemplate\";s:1:\"1\";s:15:\"chktemplateform\";s:1:\"1\";s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}' where `mnu_id`='115';
update `app_modules` set `mnu_id`='44',`mnu_name`='Factor Rate',`mnu_desc`='',`mnu_icon`='',`mnu_parent`='17',`mnu_ord`='110',`mnu_link`='setup.php?statpos=factor_rate',`mnu_status`='1',`mnu_link_info`='a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:11:\"factor_rate\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:38:\"admin/setup/factor_rate.controller.php\";s:14:\"class_filename\";s:33:\"admin/setup/factor_rate.class.php\";s:10:\"class_name\";s:14:\"clsFactor_Rate\";s:17:\"template_filename\";s:31:\"admin/setup/factor_rate.tpl.php\";s:21:\"templateform_filename\";s:36:\"admin/setup/factor_rate_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}' where `mnu_id`='44';
update `app_modules` set `mnu_id`='9',`mnu_name`='Employee Information',`mnu_desc`='',`mnu_icon`='',`mnu_parent`='8',`mnu_ord`='2',`mnu_link`='transaction.php?statpos=emp_masterfile',`mnu_status`='1',`mnu_link_info`='a:18:{s:8:\"linkpage\";s:15:\"transaction.php\";s:7:\"statpos\";s:14:\"emp_masterfile\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:21:\"admin/transaction.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:47:\"admin/transaction/emp_masterfile.controller.php\";s:14:\"class_filename\";s:42:\"admin/transaction/emp_masterfile.class.php\";s:10:\"class_name\";s:17:\"clsEMP_MasterFile\";s:17:\"template_filename\";s:40:\"admin/transaction/emp_masterfile.tpl.php\";s:21:\"templateform_filename\";s:45:\"admin/transaction/emp_masterfile_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}' where `mnu_id`='9';
call alter_column('payroll_bank_reports','pbr_company_accountname',"alter table `payroll_bank_reports` add column `pbr_company_accountname` varchar (200)  NULL  after `pbr_company_code`");
update `bank_list` set `banklist_id`='4',`banklist_name`='Standard Chartered Bank',`banklist_desc`='Standard Chartered Bank',`banklist_addwho`='admin',`banklist_addwhen`='2012-08-16 10:36:28',`banklist_updatewho`=NULL,`banklist_updatewhen`=NULL,`banklist_isactive`='1' where `banklist_id`='4';
update `app_modules` set `mnu_id`='127',`mnu_name`='Help',`mnu_desc`='Help for user',`mnu_icon`='',`mnu_parent`='0',`mnu_ord`='70',`mnu_link`='',`mnu_status`='1',`mnu_link_info`='a:18:{s:8:\"linkpage\";s:0:\"\";s:7:\"statpos\";s:0:\"\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}' where `mnu_id`='127';
update `app_modules` set `mnu_id`='153',`mnu_name`='Dtr',`mnu_desc`='for DTR link',`mnu_icon`='',`mnu_parent`='0',`mnu_ord`='60',`mnu_link`='/orangeidtr_itochu',`mnu_status`='0',`mnu_link_info`='a:18:{s:8:\"linkpage\";s:9:\"/ipay_dtr\";s:7:\"statpos\";s:0:\"\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}' where `mnu_id`='153';
update `app_modules` set `mnu_id`='122',`mnu_name`='Hris',`mnu_desc`='Link for OrangeHRM',`mnu_icon`='',`mnu_parent`='0',`mnu_ord`='50',`mnu_link`='/orangehrm-2.7.1_itochu',`mnu_status`='0',`mnu_link_info`='a:18:{s:8:\"linkpage\";s:0:\"\";s:7:\"statpos\";s:0:\"\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}' where `mnu_id`='122';
update `app_modules` set `mnu_id`='148',`mnu_name`='My Payslip',`mnu_desc`='List of All Payslip Process in Payroll',`mnu_icon`='',`mnu_parent`='16',`mnu_ord`='90',`mnu_link`='reports.php?statpos=mypayslip',`mnu_status`='1',`mnu_link_info`='a:18:{s:8:\"linkpage\";s:15:\"reports.php\";s:7:\"statpos\";s:9:\"mypayslip\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:21:\"admin/reports.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:42:\"admin/reports/mypayslip.controller.php\";s:14:\"class_filename\";s:37:\"admin/reports/mypayslip.class.php\";s:10:\"class_name\";s:12:\"clsMyPayslip\";s:17:\"template_filename\";s:35:\"admin/reports/mypayslip.tpl.php\";s:21:\"templateform_filename\";s:40:\"admin/reports/mypayslip_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}' where `mnu_id`='148';
call alter_column('payroll_pay_period','is_payslip_viewable',"alter table `payroll_pay_period` add column `is_payslip_viewable` boolean  DEFAULT '0' NULL  after `payperiod_type`;");
ALTER TABLE `payroll_bank_reports` ENGINE = InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `payroll_bank_report_data` ENGINE = InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
alter table `bir_alphalist_prev_emp` change `taxable_basic` `taxable_basic` decimal (10,5)  NOT NULL , change `taxable_other_ben` `taxable_other_ben` decimal (10,5)  NOT NULL , change `taxable_compensation` `taxable_compensation` decimal (10,5)  NOT NULL , change `nt_other_ben` `nt_other_ben` decimal (10,5)  NOT NULL , change `nt_deminimis` `nt_deminimis` decimal (10,5)  NOT NULL , change `nt_statutories` `nt_statutories` decimal (10,5)  NOT NULL , change `nt_compensation` `nt_compensation` decimal (10,5)  NOT NULL;
call alter_column('bir_alphalist_prev_emp','gross_compensation',"alter table `bir_alphalist_prev_emp` add column `gross_compensation` decimal (10,5)  NOT NULL  after `nt_compensation`");
call alter_column('bir_alphalist_prev_emp','basic_smw',"alter table `bir_alphalist_prev_emp` add column `basic_smw` decimal (10,5)  NOT NULL  after `gross_compensation`");
call alter_column('bir_alphalist_prev_emp','holiday_pay',"alter table `bir_alphalist_prev_emp` add column `holiday_pay` decimal (10,5)  NOT NULL  after `basic_smw`");
call alter_column('bir_alphalist_prev_emp','overtime_pay',"alter table `bir_alphalist_prev_emp` add column `overtime_pay` decimal (10,5)  NOT NULL  after `holiday_pay`");
call alter_column('bir_alphalist_prev_emp','night_differential',"alter table `bir_alphalist_prev_emp` add column `night_differential` decimal (10,5)  NOT NULL  after `overtime_pay`");
call alter_column('bir_alphalist_prev_emp','hazard_pay',"alter table `bir_alphalist_prev_emp` add column `hazard_pay` decimal (10,5)  NOT NULL  after `night_differential`");
call alter_column('bir_alphalist_prev_emp','tax_withheld',"alter table `bir_alphalist_prev_emp` add column `tax_withheld` decimal (10,5)  NOT NULL  after `bir_alphalist_year`");
/* change previous employer from 10 to 20 */
alter table `bir_alphalist_prev_emp` change `tax_withheld` `tax_withheld` decimal (50,5)  NOT NULL;
alter table `bir_alphalist_prev_emp` change `taxable_basic` `taxable_basic` decimal (50,5)  NOT NULL;
alter table `bir_alphalist_prev_emp` change `taxable_other_ben` `taxable_other_ben` decimal (50,5)  NOT NULL;
alter table `bir_alphalist_prev_emp` change `taxable_compensation` `taxable_compensation` decimal (50,5)  NOT NULL;
alter table `bir_alphalist_prev_emp` change `nt_other_ben` `nt_other_ben` decimal (50,5)  NOT NULL;
alter table `bir_alphalist_prev_emp` change `nt_deminimis` `nt_deminimis` decimal (50,5)  NOT NULL;
alter table `bir_alphalist_prev_emp` change `nt_statutories` `nt_statutories` decimal (50,5)  NOT NULL;
alter table `bir_alphalist_prev_emp` change `nt_compensation` `nt_compensation` decimal (50,5)  NOT NULL;
alter table `bir_alphalist_prev_emp` change `gross_compensation` `gross_compensation` decimal (50,5)  NOT NULL;
alter table `bir_alphalist_prev_emp` change `basic_smw` `basic_smw` decimal (50,5)  NOT NULL;
alter table `bir_alphalist_prev_emp` change `holiday_pay` `holiday_pay` decimal (50,5)  NOT NULL;
alter table `bir_alphalist_prev_emp` change `overtime_pay` `overtime_pay` decimal (50,5)  NOT NULL;
alter table `bir_alphalist_prev_emp` change `night_differential` `night_differential` decimal (50,5)  NOT NULL;
alter table `bir_alphalist_prev_emp` change `hazard_pay` `hazard_pay` decimal (50,5)  NOT NULL;
call alter_column('payroll_pay_period','payperiod_name',"alter table `payroll_pay_period` add column `payperiod_name` varchar (50)  NULL  after `payperiod_status_id`");
call alter_column('payroll_ps_amendment','payperiod_id',"alter table `payroll_ps_amendment` add column `payperiod_id` int   NOT NULL  after `psamend_istaxable`");
call alter_column('app_settings','set_other_data',"alter table `app_settings` add column `set_other_data` varchar (255)  NULL  after `set_stat_type`");
call alter_column('payroll_pay_period','convert_leave',"alter table `payroll_pay_period` add column `convert_leave` tinyint (1) DEFAULT '0' NULL  after `is_payslip_viewable`");
create table IF NOT EXISTS `audit_access` (
  `track_id` int NOT NULL AUTO_INCREMENT , 
  `fullname` varchar (300) NOT NULL , 
  `track_module` varchar (300) NOT NULL , 
  `track_date` timestamp , PRIMARY KEY (`track_id`)
  );
