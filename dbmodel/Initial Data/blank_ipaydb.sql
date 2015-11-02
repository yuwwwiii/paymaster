-- MySQL dump 10.13  Distrib 5.5.16, for Win32 (x86)
--
-- Host: localhost    Database: orangeipay_ssi
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
-- Table structure for table `app_audittrail`
--

DROP TABLE IF EXISTS `app_audittrail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_audittrail` (
  `audit_id` int(10) NOT NULL AUTO_INCREMENT,
  `audit_username` varchar(225) DEFAULT NULL,
  `audit_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `audit_remarks` text,
  `comp_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`audit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_audittrail`
--

LOCK TABLES `app_audittrail` WRITE;
/*!40000 ALTER TABLE `app_audittrail` DISABLE KEYS */;
/*!40000 ALTER TABLE `app_audittrail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_country`
--

DROP TABLE IF EXISTS `app_country`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_country` (
  `cou_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cou_description` varchar(255) DEFAULT NULL,
  `cou_code` varchar(10) DEFAULT NULL,
  `cou_addwho` varchar(50) DEFAULT NULL,
  `cou_addwhen` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `cou_updatewho` varchar(50) DEFAULT NULL,
  `cou_updatewhen` datetime DEFAULT NULL,
  PRIMARY KEY (`cou_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_country`
--

LOCK TABLES `app_country` WRITE;
/*!40000 ALTER TABLE `app_country` DISABLE KEYS */;
INSERT INTO `app_country` VALUES (1,'Philippines','PH','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(2,'Canada','CA','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(3,'United Kingdom','FR','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(4,'Indonesia','ID','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(5,'Malaysia','MV','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(6,'Singapore','SG','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(7,'Saudi Arabia','KSA','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(8,'Bahrain','KOB','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(9,'Kuwait','KUW','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(10,'United Arab Emirates','UAE','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `app_country` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_formula_keywords`
--

DROP TABLE IF EXISTS `app_formula_keywords`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_formula_keywords` (
  `app_fkey_id` int(11) NOT NULL AUTO_INCREMENT,
  `app_fkey_name` varchar(100) NOT NULL,
  `psa_id` int(11) DEFAULT NULL,
  `tatble_id` int(11) DEFAULT NULL,
  `cfhead_id` int(11) DEFAULT NULL,
  `leave_id` int(11) DEFAULT NULL,
  `app_fkey_isactive` int(11) NOT NULL DEFAULT '0',
  `app_fkey_query` text,
  `app_fkey_vars` text,
  `app_fkey_result` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`app_fkey_id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_formula_keywords`
--

LOCK TABLES `app_formula_keywords` WRITE;
/*!40000 ALTER TABLE `app_formula_keywords` DISABLE KEYS */;
INSERT INTO `app_formula_keywords` VALUES (1,'Base Amount',0,0,NULL,NULL,1,'SELECT ben_amount FROM emp_benefits WHERE ben_suspend != \'1\' and psa_id=? and emp_id=? and ben_startdate <= ? \r\nand IF(ben_enddate=\'0000-00-00\',ben_enddate <= ?,ben_enddate >= ?)','array($psa_id, $emp_id, date(\"Y-m-d\"),date(\"Y-m-d\"), date(\"Y-m-d\"))','ben_amount'),(2,'Monthly Rate',0,0,NULL,NULL,0,NULL,NULL,NULL),(3,'Weekly Rate',0,0,NULL,NULL,0,NULL,NULL,NULL),(4,'Daily Rate',0,0,NULL,NULL,0,NULL,NULL,NULL),(5,'Hourly Rate',0,0,NULL,NULL,0,NULL,NULL,NULL),(6,'No Pay Leave (NPL)',0,1,NULL,NULL,1,'SELECT emp_tarec_nohrday FROM ta_emp_rec WHERE tatbl_id=? AND emp_id=? AND paystub_id=?','array(1,$emp_id,$paystub_id)','emp_tarec_nohrday'),(7,'Late(Hours)',0,3,NULL,NULL,1,'SELECT emp_tarec_nohrday FROM ta_emp_rec WHERE tatbl_id=? AND emp_id=? AND paystub_id=?','array(3,$emp_id,$paystub_id)','emp_tarec_nohrday'),(8,'Undertime (Hours)',0,4,NULL,NULL,1,'SELECT emp_tarec_nohrday FROM ta_emp_rec WHERE tatbl_id=? AND emp_id=? AND paystub_id=?','array(4,$emp_id,$paystub_id)','emp_tarec_nohrday'),(9,'Working Days',0,5,NULL,NULL,1,'SELECT emp_tarec_nohrday FROM ta_emp_rec WHERE tatbl_id=? AND emp_id=? AND payperiod_id=?','array(5,$emp_id,$payperiod_id)','emp_tarec_nohrday'),(10,'Custom Days',0,6,NULL,NULL,1,'SELECT emp_tarec_nohrday FROM ta_emp_rec WHERE tatbl_id=? AND emp_id=? AND paystub_id=?','array(6,$emp_id,$paystub_id)','emp_tarec_nohrday'),(11,'W/H Tax',8,0,NULL,NULL,0,NULL,NULL,NULL),(12,'SSS',7,0,NULL,NULL,0,NULL,NULL,NULL),(13,'PHIC',14,0,NULL,NULL,0,NULL,NULL,NULL),(14,'HDMF',15,0,NULL,NULL,0,NULL,NULL,NULL),(15,'Basic Pay',1,0,NULL,NULL,1,'select amount from z_formula_temp where psa_id=? and paystub_id=?','array(1,$paystub_id)','amount'),(16,'Net Pay',5,0,NULL,NULL,0,NULL,NULL,NULL),(17,'COLA',39,0,NULL,NULL,0,NULL,NULL,NULL),(18,'Employer Total Contributions',6,0,NULL,NULL,0,NULL,NULL,NULL),(19,'Total Deductions',2,0,NULL,NULL,0,NULL,NULL,NULL),(20,'Overtime(Hours)',0,0,NULL,NULL,0,NULL,NULL,NULL),(21,'Total TA',17,0,NULL,NULL,0,NULL,NULL,NULL),(22,'Other Statutory Income',25,0,NULL,NULL,0,NULL,NULL,NULL),(23,'OtherStat&TaxIncome',26,0,NULL,NULL,0,NULL,NULL,NULL),(24,'Statutory Contrib',27,0,NULL,NULL,0,NULL,NULL,NULL),(25,'Other Taxable Income',28,0,NULL,NULL,0,NULL,NULL,NULL),(26,'Other Taxable Deduction',29,0,NULL,NULL,0,NULL,NULL,NULL),(27,'Taxable Income Gross',30,0,NULL,NULL,0,NULL,NULL,NULL),(28,'NonTaxable Income',31,0,NULL,NULL,0,NULL,NULL,NULL),(29,'Other Deduction',32,0,NULL,NULL,0,NULL,NULL,NULL),(30,'Other Statutory Deduction',33,0,NULL,NULL,0,NULL,NULL,NULL),(31,'Other Stat & Tax Deduction',34,0,NULL,NULL,0,NULL,NULL,NULL),(32,'Total Gross',4,0,NULL,NULL,0,NULL,NULL,NULL),(33,'Hours Per Day',0,0,NULL,NULL,1,'select fr_hrperday from factor_rate a inner join salary_info b on (b.fr_id=a.fr_id) where emp_id=? and salaryinfo_isactive=1','array($emp_id)','fr_hrperday'),(34,'Days Per Week',0,0,NULL,NULL,1,'select fr_dayperweek from factor_rate a inner join salary_info b on (b.fr_id=a.fr_id) where emp_id=? and salaryinfo_isactive=1','array($emp_id)','fr_dayperweek'),(35,'Days Per Year',0,0,NULL,NULL,1,'select fr_dayperyear from factor_rate a inner join salary_info b on (b.fr_id=a.fr_id) where emp_id=? and salaryinfo_isactive=1','array($emp_id)','fr_dayperyear'),(36,'Hours Per Week',0,0,NULL,NULL,1,'select fr_hrperweek from factor_rate a inner join salary_info b on (b.fr_id=a.fr_id) where emp_id=? and salaryinfo_isactive=1','array($emp_id)','fr_hrperweek'),(37,'Emergency Leave',NULL,NULL,NULL,1,1,'select emp_leav_rec_leavedays from emp_leave_rec a join emp_leave b on (b.empleave_id=a.empleave_id) join leave_type c on (c.leave_id=b.leave_id) where a.payperiod_id=? and a.emp_id=? and b.leave_id=1','array($payperiod_id,$emp_id)','emp_leav_rec_leavedays'),(38,'Sick Leave',NULL,NULL,NULL,2,1,'select emp_leav_rec_leavedays from emp_leave_rec a join emp_leave b on (b.empleave_id=a.empleave_id) join leave_type c on (c.leave_id=b.leave_id) where a.payperiod_id=? and a.emp_id=? and b.leave_id=2','array($payperiod_id,$emp_id)','emp_leav_rec_leavedays'),(39,'Vacation Leave',NULL,NULL,NULL,3,1,'select emp_leav_rec_leavedays from emp_leave_rec a join emp_leave b on (b.empleave_id=a.empleave_id) join leave_type c on (c.leave_id=b.leave_id) where a.payperiod_id=? and a.emp_id=? and b.leave_id=3','array($payperiod_id,$emp_id)','emp_leav_rec_leavedays'),(40,'Vacation Leave',NULL,NULL,NULL,4,1,'select emp_leav_rec_leavedays from emp_leave_rec a join emp_leave b on (b.empleave_id=a.empleave_id) join leave_type c on (c.leave_id=b.leave_id) where a.payperiod_id=? and a.emp_id=? and b.leave_id=4','array($payperiod_id,$emp_id)','emp_leav_rec_leavedays'),(41,'Sick Leave',NULL,NULL,NULL,5,1,'select emp_leav_rec_leavedays from emp_leave_rec a join emp_leave b on (b.empleave_id=a.empleave_id) join leave_type c on (c.leave_id=b.leave_id) where a.payperiod_id=? and a.emp_id=? and b.leave_id=5','array($payperiod_id,$emp_id)','emp_leav_rec_leavedays'),(42,'Emergency Leave',NULL,NULL,NULL,6,1,'select emp_leav_rec_leavedays from emp_leave_rec a join emp_leave b on (b.empleave_id=a.empleave_id) join leave_type c on (c.leave_id=b.leave_id) where a.payperiod_id=? and a.emp_id=? and b.leave_id=6','array($payperiod_id,$emp_id)','emp_leav_rec_leavedays'),(43,'Mandatory Leave',NULL,NULL,NULL,7,1,'select emp_leav_rec_leavedays from emp_leave_rec a join emp_leave b on (b.empleave_id=a.empleave_id) join leave_type c on (c.leave_id=b.leave_id) where a.payperiod_id=? and a.emp_id=? and b.leave_id=7','array($payperiod_id,$emp_id)','emp_leav_rec_leavedays'),(44,'No Pay Leave',NULL,NULL,NULL,8,1,'select emp_leav_rec_leavedays from emp_leave_rec a join emp_leave b on (b.empleave_id=a.empleave_id) join leave_type c on (c.leave_id=b.leave_id) where a.payperiod_id=? and a.emp_id=? and b.leave_id=8','array($payperiod_id,$emp_id)','emp_leav_rec_leavedays'),(45,'Vacation Leaves',NULL,NULL,NULL,9,1,'select emp_leav_rec_leavedays from emp_leave_rec a join emp_leave b on (b.empleave_id=a.empleave_id) join leave_type c on (c.leave_id=b.leave_id) where a.payperiod_id=? and a.emp_id=? and b.leave_id=9','array($payperiod_id,$emp_id)','emp_leav_rec_leavedays');
/*!40000 ALTER TABLE `app_formula_keywords` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_modules`
--

DROP TABLE IF EXISTS `app_modules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_modules` (
  `mnu_id` int(11) NOT NULL AUTO_INCREMENT,
  `mnu_name` varchar(64) COLLATE latin1_general_ci NOT NULL,
  `mnu_desc` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `mnu_icon` varchar(125) COLLATE latin1_general_ci NOT NULL,
  `mnu_parent` int(11) NOT NULL DEFAULT '0',
  `mnu_ord` int(11) NOT NULL DEFAULT '1',
  `mnu_link` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `mnu_status` tinyint(2) NOT NULL DEFAULT '1',
  `mnu_link_info` text COLLATE latin1_general_ci,
  PRIMARY KEY (`mnu_id`),
  KEY `mnu_parent` (`mnu_parent`,`mnu_ord`)
) ENGINE=MyISAM AUTO_INCREMENT=154 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_modules`
--

LOCK TABLES `app_modules` WRITE;
/*!40000 ALTER TABLE `app_modules` DISABLE KEYS */;
INSERT INTO `app_modules` VALUES (1,'Admin','Setup Libraries','',0,10,'',1,'a:18:{s:8:\"linkpage\";s:0:\"\";s:7:\"statpos\";s:0:\"\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(2,'Manage User','','',19,20,'setup.php?statpos=manageuser',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:10:\"manageuser\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(3,'Manage Modules','','',1,2,'setup.php?statpos=managemodule',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:12:\"managemodule\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:2:{i:0;s:1:\"2\";i:1;s:1:\"1\";}s:9:\"user_type\";a:1:{i:0;s:19:\"Super Administrator\";}}'),(4,'Manage User Type','','',19,10,'setup.php?statpos=manageusertype',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:14:\"manageusertype\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(5,'Department','','',20,40,'setup.php?statpos=managedept',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:10:\"managedept\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/admin.php\";s:19:\"controller_filename\";s:27:\"admin/setup/manage_dept.php\";s:14:\"class_filename\";s:33:\"admin/setup/manage_dept.class.php\";s:10:\"class_name\";s:13:\"clsManageDept\";s:17:\"template_filename\";s:31:\"admin/setup/manage_dept.tpl.php\";s:21:\"templateform_filename\";s:36:\"admin/setup/manage_dept_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(7,'Home','','',0,5,'index.php',1,'a:18:{s:8:\"linkpage\";s:9:\"index.php\";s:7:\"statpos\";s:0:\"\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(8,'Transaction','','',0,20,'',1,'a:18:{s:8:\"linkpage\";s:0:\"\";s:7:\"statpos\";s:0:\"\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(9,'Employee Information','','',8,2,'transaction.php?statpos=emp_masterfile',1,'a:18:{s:8:\"linkpage\";s:15:\"transaction.php\";s:7:\"statpos\";s:14:\"emp_masterfile\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:21:\"admin/transaction.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:47:\"admin/transaction/emp_masterfile.controller.php\";s:14:\"class_filename\";s:42:\"admin/transaction/emp_masterfile.class.php\";s:10:\"class_name\";s:17:\"clsEMP_MasterFile\";s:17:\"template_filename\";s:40:\"admin/transaction/emp_masterfile.tpl.php\";s:21:\"templateform_filename\";s:45:\"admin/transaction/emp_masterfile_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(10,'Company Profile','','',20,10,'setup.php?statpos=manage_comp',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:11:\"manage_comp\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:38:\"admin/setup/manage_comp.controller.php\";s:14:\"class_filename\";s:33:\"admin/setup/manage_comp.class.php\";s:10:\"class_name\";s:14:\"clsManage_Comp\";s:17:\"template_filename\";s:31:\"admin/setup/manage_comp.tpl.php\";s:21:\"templateform_filename\";s:36:\"admin/setup/manage_comp_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(12,'Manage 201','','',17,30,'',1,'a:18:{s:8:\"linkpage\";s:0:\"\";s:7:\"statpos\";s:0:\"\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(13,'201 Type','','',12,10,'setup.php?statpos=emptype',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:7:\"emptype\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:34:\"admin/setup/emptype.controller.php\";s:14:\"class_filename\";s:29:\"admin/setup/emptype.class.php\";s:10:\"class_name\";s:10:\"clsEMPType\";s:17:\"template_filename\";s:27:\"admin/setup/emptype.tpl.php\";s:21:\"templateform_filename\";s:32:\"admin/setup/emptype_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(14,'201 Category','','',12,20,'setup.php?statpos=empcateg',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:8:\"empcateg\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:35:\"admin/setup/empcateg.controller.php\";s:14:\"class_filename\";s:30:\"admin/setup/empcateg.class.php\";s:10:\"class_name\";s:11:\"clsEMPCateg\";s:17:\"template_filename\";s:28:\"admin/setup/empcateg.tpl.php\";s:21:\"templateform_filename\";s:33:\"admin/setup/empcateg_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(15,'Job Position','','',12,30,'setup.php?statpos=jobpost',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:7:\"jobpost\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:34:\"admin/setup/jobpost.controller.php\";s:14:\"class_filename\";s:29:\"admin/setup/jobpost.class.php\";s:10:\"class_name\";s:10:\"clsJobPost\";s:17:\"template_filename\";s:27:\"admin/setup/jobpost.tpl.php\";s:21:\"templateform_filename\";s:32:\"admin/setup/jobpost_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(16,'Reports','','',0,30,'',1,'a:18:{s:8:\"linkpage\";s:0:\"\";s:7:\"statpos\";s:0:\"\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(17,'Libraries','','',1,20,'',1,'a:18:{s:8:\"linkpage\";s:0:\"\";s:7:\"statpos\";s:0:\"\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(19,'Manage Access Level','','',126,1,'',1,'a:18:{s:8:\"linkpage\";s:0:\"\";s:7:\"statpos\";s:0:\"\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(20,'Manage Company','','',17,20,'',1,'a:18:{s:8:\"linkpage\";s:0:\"\";s:7:\"statpos\";s:0:\"\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(21,'Manage Bank','Company Bank','',126,1,'setup.php?statpos=compbanks',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:9:\"compbanks\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:36:\"admin/setup/compbanks.controller.php\";s:14:\"class_filename\";s:31:\"admin/setup/compbanks.class.php\";s:10:\"class_name\";s:12:\"clsCompBanks\";s:17:\"template_filename\";s:29:\"admin/setup/compbanks.tpl.php\";s:21:\"templateform_filename\";s:34:\"admin/setup/compbanks_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(25,'Payroll Process','Payroll Process','',72,10,'transaction.php?statpos=process_payroll',1,'a:18:{s:8:\"linkpage\";s:15:\"transaction.php\";s:7:\"statpos\";s:15:\"process_payroll\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:21:\"admin/transaction.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:48:\"admin/transaction/process_payroll.controller.php\";s:14:\"class_filename\";s:43:\"admin/transaction/process_payroll.class.php\";s:10:\"class_name\";s:18:\"clsProcess_Payroll\";s:17:\"template_filename\";s:41:\"admin/transaction/process_payroll.tpl.php\";s:21:\"templateform_filename\";s:46:\"admin/transaction/process_payroll_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(26,'Manage Pay Element ','','',17,60,'setup.php?statpos=mnge_pe',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:7:\"mnge_pe\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:34:\"admin/setup/mnge_pe.controller.php\";s:14:\"class_filename\";s:29:\"admin/setup/mnge_pe.class.php\";s:10:\"class_name\";s:10:\"clsMnge_PE\";s:17:\"template_filename\";s:27:\"admin/setup/mnge_pe.tpl.php\";s:21:\"templateform_filename\";s:32:\"admin/setup/mnge_pe_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(27,'Manage Pay Group ','','',17,60,'setup.php?statpos=mnge_pg ',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:8:\"mnge_pg \";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:34:\"admin/setup/mnge_pg.controller.php\";s:14:\"class_filename\";s:29:\"admin/setup/mnge_pg.class.php\";s:10:\"class_name\";s:10:\"clsMnge_PG\";s:17:\"template_filename\";s:27:\"admin/setup/mnge_pg.tpl.php\";s:21:\"templateform_filename\";s:32:\"admin/setup/mnge_pg_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(28,'Manage Statutory','','',17,70,'setup.php?statpos=mnge_sc',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:7:\"mnge_sc\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:34:\"admin/setup/mnge_sc.controller.php\";s:14:\"class_filename\";s:29:\"admin/setup/mnge_sc.class.php\";s:10:\"class_name\";s:10:\"clsMnge_SC\";s:17:\"template_filename\";s:27:\"admin/setup/mnge_sc.tpl.php\";s:21:\"templateform_filename\";s:32:\"admin/setup/mnge_sc_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(29,'Manage Income Tax','','',17,80,'',1,'a:18:{s:8:\"linkpage\";s:0:\"\";s:7:\"statpos\";s:0:\"\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:34:\"admin/setup/mnge_it.controller.php\";s:14:\"class_filename\";s:29:\"admin/setup/mnge_it.class.php\";s:10:\"class_name\";s:10:\"clsMnge_IT\";s:17:\"template_filename\";s:27:\"admin/setup/mnge_it.tpl.php\";s:21:\"templateform_filename\";s:32:\"admin/setup/mnge_it_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(30,'Manage Tax Employer','','',29,10,'setup.php?statpos=mnge_te',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:7:\"mnge_te\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:34:\"admin/setup/mnge_te.controller.php\";s:14:\"class_filename\";s:29:\"admin/setup/mnge_te.class.php\";s:10:\"class_name\";s:10:\"clsMnge_TE\";s:17:\"template_filename\";s:27:\"admin/setup/mnge_te.tpl.php\";s:21:\"templateform_filename\";s:32:\"admin/setup/mnge_te_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(31,'Manage Tax Table','','',29,20,'setup.php?statpos=mnge_tt',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:7:\"mnge_tt\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:34:\"admin/setup/mnge_tt.controller.php\";s:14:\"class_filename\";s:29:\"admin/setup/mnge_tt.class.php\";s:10:\"class_name\";s:10:\"clsMnge_TT\";s:17:\"template_filename\";s:27:\"admin/setup/mnge_tt.tpl.php\";s:21:\"templateform_filename\";s:32:\"admin/setup/mnge_tt_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(32,'Salary Classification','','',12,50,'setup.php?statpos=salaryclass',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:11:\"salaryclass\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:38:\"admin/setup/salaryclass.controller.php\";s:14:\"class_filename\";s:33:\"admin/setup/salaryclass.class.php\";s:10:\"class_name\";s:14:\"clsSalaryClass\";s:17:\"template_filename\";s:31:\"admin/setup/salaryclass.tpl.php\";s:21:\"templateform_filename\";s:36:\"admin/setup/salaryclass_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(34,'SSS Premium','','',84,10,'reports.php?statpos=sss',1,'a:18:{s:8:\"linkpage\";s:11:\"reports.php\";s:7:\"statpos\";s:3:\"sss\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:17:\"admin/reports.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:32:\"admin/reports/sss.controller.php\";s:14:\"class_filename\";s:27:\"admin/reports/sss.class.php\";s:10:\"class_name\";s:6:\"clsSSS\";s:17:\"template_filename\";s:25:\"admin/reports/sss.tpl.php\";s:21:\"templateform_filename\";s:30:\"admin/reports/sss_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(35,'PHIC Premium','','',84,20,'reports.php?statpos=phic',1,'a:18:{s:8:\"linkpage\";s:11:\"reports.php\";s:7:\"statpos\";s:4:\"phic\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:17:\"admin/reports.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:33:\"admin/reports/phic.controller.php\";s:14:\"class_filename\";s:28:\"admin/reports/phic.class.php\";s:10:\"class_name\";s:7:\"clsPHIC\";s:17:\"template_filename\";s:26:\"admin/reports/phic.tpl.php\";s:21:\"templateform_filename\";s:31:\"admin/reports/phic_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(36,'HDMF Premium','','',84,30,'reports.php?statpos=hdmf',1,'a:18:{s:8:\"linkpage\";s:11:\"reports.php\";s:7:\"statpos\";s:4:\"hdmf\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:17:\"admin/reports.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:33:\"admin/reports/hdmf.controller.php\";s:14:\"class_filename\";s:28:\"admin/reports/hdmf.class.php\";s:10:\"class_name\";s:7:\"clsHDMF\";s:17:\"template_filename\";s:26:\"admin/reports/hdmf.tpl.php\";s:21:\"templateform_filename\";s:31:\"admin/reports/hdmf_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(37,'Tax','','',85,40,'reports.php?statpos=tax',1,'a:18:{s:8:\"linkpage\";s:11:\"reports.php\";s:7:\"statpos\";s:3:\"tax\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:18:\"admin/reports.phpa\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:32:\"admin/reports/tax.controller.php\";s:14:\"class_filename\";s:27:\"admin/reports/tax.class.php\";s:10:\"class_name\";s:6:\"clsTAX\";s:17:\"template_filename\";s:25:\"admin/reports/tax.tpl.php\";s:21:\"templateform_filename\";s:30:\"admin/reports/tax_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(39,'SSS Collection','','',119,10,'reports.php?statpos=sss_collection',1,'a:18:{s:8:\"linkpage\";s:11:\"reports.php\";s:7:\"statpos\";s:14:\"sss_collection\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:17:\"admin/reports.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:43:\"admin/reports/sss_collection.controller.php\";s:14:\"class_filename\";s:38:\"admin/reports/sss_collection.class.php\";s:10:\"class_name\";s:16:\"clsSSSCollection\";s:17:\"template_filename\";s:36:\"admin/reports/sss_collection.tpl.php\";s:21:\"templateform_filename\";s:41:\"admin/reports/sss_collection_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(40,'HDMF Collection','','',119,20,'reports.php?statpos=hdmf_collection',1,'a:18:{s:8:\"linkpage\";s:11:\"reports.php\";s:7:\"statpos\";s:15:\"hdmf_collection\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:17:\"admin/reports.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:44:\"admin/reports/hdmf_collection.controller.php\";s:14:\"class_filename\";s:39:\"admin/reports/hdmf_collection.class.php\";s:10:\"class_name\";s:17:\"clsHDMFCollection\";s:17:\"template_filename\";s:37:\"admin/reports/hdmf_collection.tpl.php\";s:21:\"templateform_filename\";s:42:\"admin/reports/hdmf_collection_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(41,'BIR Alphalist','','',85,80,'reports.php?statpos=bir_alphalist',1,'a:18:{s:8:\"linkpage\";s:11:\"reports.php\";s:7:\"statpos\";s:13:\"bir_alphalist\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:17:\"admin/reports.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:42:\"admin/reports/bir_alphalist.controller.php\";s:14:\"class_filename\";s:37:\"admin/reports/bir_alphalist.class.php\";s:10:\"class_name\";s:15:\"clsBIRAlphalist\";s:17:\"template_filename\";s:35:\"admin/reports/bir_alphalist.tpl.php\";s:21:\"templateform_filename\";s:40:\"admin/reports/bir_alphalist_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(42,'Amendments','','',72,5,'transaction.php?statpos=ps_amend',1,'a:18:{s:8:\"linkpage\";s:15:\"transaction.php\";s:7:\"statpos\";s:8:\"ps_amend\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:21:\"admin/transaction.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:41:\"admin/transaction/ps_amend.controller.php\";s:14:\"class_filename\";s:36:\"admin/transaction/ps_amend.class.php\";s:10:\"class_name\";s:11:\"clsPS_Amend\";s:17:\"template_filename\";s:34:\"admin/transaction/ps_amend.tpl.php\";s:21:\"templateform_filename\";s:39:\"admin/transaction/ps_amend_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(43,'Tax Exemption','','',12,70,'setup.php?statpos=tax_excep',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:9:\"tax_excep\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:36:\"admin/setup/tax_excep.controller.php\";s:14:\"class_filename\";s:31:\"admin/setup/tax_excep.class.php\";s:10:\"class_name\";s:12:\"clsTax_Excep\";s:17:\"template_filename\";s:29:\"admin/setup/tax_excep.tpl.php\";s:21:\"templateform_filename\";s:34:\"admin/setup/tax_excep_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(44,'Factor Rate','','',17,110,'setup.php?statpos=factor_rate',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:11:\"factor_rate\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:38:\"admin/setup/factor_rate.controller.php\";s:14:\"class_filename\";s:33:\"admin/setup/factor_rate.class.php\";s:10:\"class_name\";s:14:\"clsFactor_Rate\";s:17:\"template_filename\";s:31:\"admin/setup/factor_rate.tpl.php\";s:21:\"templateform_filename\";s:36:\"admin/setup/factor_rate_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(45,'Deduction Type','','',12,70,'setup.php?statpos=deductype',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:9:\"deductype\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:36:\"admin/setup/deductype.controller.php\";s:14:\"class_filename\";s:31:\"admin/setup/deductype.class.php\";s:10:\"class_name\";s:12:\"clsDeducType\";s:17:\"template_filename\";s:29:\"admin/setup/deductype.tpl.php\";s:21:\"templateform_filename\";s:34:\"admin/setup/deductype_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(46,'Payroll Details','Payroll Details','',72,20,'transaction.php?statpos=payroll_details',1,'a:18:{s:8:\"linkpage\";s:15:\"transaction.php\";s:7:\"statpos\";s:15:\"payroll_details\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:21:\"admin/transaction.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:48:\"admin/transaction/payroll_details.controller.php\";s:14:\"class_filename\";s:43:\"admin/transaction/payroll_details.class.php\";s:10:\"class_name\";s:18:\"clsPayroll_Details\";s:17:\"template_filename\";s:41:\"admin/transaction/payroll_details.tpl.php\";s:21:\"templateform_filename\";s:46:\"admin/transaction/payroll_details_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(47,'OT Manage','','',126,20,'',1,'a:18:{s:8:\"linkpage\";s:0:\"\";s:7:\"statpos\";s:0:\"\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(48,'OT Table','','',47,10,'setup.php?statpos=ottable',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:7:\"ottable\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:34:\"admin/setup/ottable.controller.php\";s:14:\"class_filename\";s:29:\"admin/setup/ottable.class.php\";s:10:\"class_name\";s:10:\"clsOTTable\";s:17:\"template_filename\";s:27:\"admin/setup/ottable.tpl.php\";s:21:\"templateform_filename\";s:32:\"admin/setup/ottable_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(49,'OT Rate','','',47,20,'setup.php?statpos=otrate',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:6:\"otrate\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:33:\"admin/setup/otrate.controller.php\";s:14:\"class_filename\";s:28:\"admin/setup/otrate.class.php\";s:10:\"class_name\";s:9:\"clsOTRate\";s:17:\"template_filename\";s:26:\"admin/setup/otrate.tpl.php\";s:21:\"templateform_filename\";s:31:\"admin/setup/otrate_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(51,'General','','',17,40,'',1,'a:18:{s:8:\"linkpage\";s:0:\"\";s:7:\"statpos\";s:0:\"\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(52,'Country','','',51,10,'setup.php?statpos=country',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:7:\"country\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/admin.php\";s:19:\"controller_filename\";s:34:\"admin/setup/country.controller.php\";s:14:\"class_filename\";s:29:\"admin/setup/country.class.php\";s:10:\"class_name\";s:10:\"clsCountry\";s:17:\"template_filename\";s:27:\"admin/setup/country.tpl.php\";s:21:\"templateform_filename\";s:32:\"admin/setup/country_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(53,'Region','','',51,20,'setup.php?statpos=region',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:6:\"region\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/admin.php\";s:19:\"controller_filename\";s:33:\"admin/setup/region.controller.php\";s:14:\"class_filename\";s:28:\"admin/setup/region.class.php\";s:10:\"class_name\";s:9:\"clsRegion\";s:17:\"template_filename\";s:27:\"admin/setup/region.tpl.php \";s:21:\"templateform_filename\";s:31:\"admin/setup/region_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(54,'State / Province','','',51,30,'setup.php?statpos=state_province',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:14:\"state_province\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/admin.php\";s:19:\"controller_filename\";s:41:\"admin/setup/state_province.controller.php\";s:14:\"class_filename\";s:36:\"admin/setup/state_province.class.php\";s:10:\"class_name\";s:17:\"clsState_province\";s:17:\"template_filename\";s:34:\"admin/setup/state_province.tpl.php\";s:21:\"templateform_filename\";s:39:\"admin/setup/state_province_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(55,'Zip Codes','','',51,40,'setup.php?statpos=zipcode',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:7:\"zipcode\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/admin.php\";s:19:\"controller_filename\";s:34:\"admin/setup/zipcode.controller.php\";s:14:\"class_filename\";s:29:\"admin/setup/zipcode.class.php\";s:10:\"class_name\";s:10:\"clsZipCode\";s:17:\"template_filename\";s:27:\"admin/setup/zipcode.tpl.php\";s:21:\"templateform_filename\";s:32:\"admin/setup/zipcode_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(56,'Payroll Register','','',74,10,'reports.php?statpos=payroll_register',1,'a:18:{s:8:\"linkpage\";s:11:\"reports.php\";s:7:\"statpos\";s:16:\"payroll_register\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:17:\"admin/reports.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:45:\"admin/reports/payroll_register.controller.php\";s:14:\"class_filename\";s:40:\"admin/reports/payroll_register.class.php\";s:10:\"class_name\";s:18:\"clsPayrollRegister\";s:17:\"template_filename\";s:38:\"admin/reports/payroll_register.tpl.php\";s:21:\"templateform_filename\";s:43:\"admin/reports/payroll_register_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(58,'TA Import','','',131,20,'transaction.php?statpos=time_attend',1,'a:18:{s:8:\"linkpage\";s:15:\"transaction.php\";s:7:\"statpos\";s:11:\"time_attend\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:21:\"admin/transaction.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:44:\"admin/transaction/time_attend.controller.php\";s:14:\"class_filename\";s:39:\"admin/transaction/time_attend.class.php\";s:10:\"class_name\";s:14:\"clsTime_Attend\";s:17:\"template_filename\";s:37:\"admin/transaction/time_attend.tpl.php\";s:21:\"templateform_filename\";s:42:\"admin/transaction/time_attend_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(59,'Manage Leave Type','','',126,55,'setup.php?statpos=mnge_leave',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:10:\"mnge_leave\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:37:\"admin/setup/mnge_leave.controller.php\";s:14:\"class_filename\";s:32:\"admin/setup/mnge_leave.class.php\";s:10:\"class_name\";s:13:\"clsMnge_Leave\";s:17:\"template_filename\";s:30:\"admin/setup/mnge_leave.tpl.php\";s:21:\"templateform_filename\";s:35:\"admin/setup/mnge_leave_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(60,'Manage TA','','',126,30,'setup.php?statpos=mnge_ta',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:7:\"mnge_ta\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:34:\"admin/setup/mnge_ta.controller.php\";s:14:\"class_filename\";s:29:\"admin/setup/mnge_ta.class.php\";s:10:\"class_name\";s:10:\"clsMnge_TA\";s:17:\"template_filename\";s:27:\"admin/setup/mnge_ta.tpl.php\";s:21:\"templateform_filename\";s:32:\"admin/setup/mnge_ta_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(63,'Employee Master List','','',8,1,'',1,'a:18:{s:8:\"linkpage\";s:0:\"\";s:7:\"statpos\";s:0:\"\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(64,'201 Review','201 File Review','',8,1,'transaction.php?statpos=201file_review',1,'a:18:{s:8:\"linkpage\";s:15:\"transaction.php\";s:7:\"statpos\";s:14:\"201file_review\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:21:\"admin/transaction.php\";s:18:\"default_controller\";s:15:\"admin/admin.php\";s:19:\"controller_filename\";s:47:\"admin/transaction/201file_review.controller.php\";s:14:\"class_filename\";s:42:\"admin/transaction/201file_review.class.php\";s:10:\"class_name\";s:17:\"cls201File_Review\";s:17:\"template_filename\";s:40:\"admin/transaction/201file_review.tlp.php\";s:21:\"templateform_filename\";s:45:\"admin/transaction/201file_review_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(65,'201 Status','','',12,1,'setup.php?statpos=201status',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:9:\"201status\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/admin.php\";s:19:\"controller_filename\";s:36:\"admin/setup/201status.controller.php\";s:14:\"class_filename\";s:31:\"admin/setup/201status.class.php\";s:10:\"class_name\";s:12:\"cls201Status\";s:17:\"template_filename\";s:29:\"admin/setup/201status.tpl.php\";s:21:\"templateform_filename\";s:34:\"admin/setup/201status_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(66,'Company Type','Company Type','',12,130,'setup.php?statpos=comptype',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:8:\"comptype\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:35:\"admin/setup/comptype.controller.php\";s:14:\"class_filename\";s:30:\"admin/setup/comptype.class.php\";s:10:\"class_name\";s:11:\"clsCompType\";s:17:\"template_filename\";s:28:\"admin/setup/comptype.tpl.php\";s:21:\"templateform_filename\";s:33:\"admin/setup/comptype_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(67,'Recruitment','','',8,0,'',1,'a:18:{s:8:\"linkpage\";s:0:\"\";s:7:\"statpos\";s:0:\"\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(68,'Application Form','','',67,10,'transaction.php?statpos=application_form',1,'a:18:{s:8:\"linkpage\";s:15:\"transaction.php\";s:7:\"statpos\";s:16:\"application_form\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:21:\"admin/transaction.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:49:\"admin/transaction/application_form.controller.php\";s:14:\"class_filename\";s:44:\"admin/transaction/application_form.class.php\";s:10:\"class_name\";s:19:\"clsApplication_Form\";s:17:\"template_filename\";s:37:\"admin/transaction/application.tpl.php\";s:21:\"templateform_filename\";s:42:\"admin/transaction/application_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(69,'Applicant Database','','',67,20,'transaction.php?statpos=applicant_database',1,'a:18:{s:8:\"linkpage\";s:15:\"transaction.php\";s:7:\"statpos\";s:18:\"applicant_database\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:21:\"admin/transaction.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:51:\"admin/transaction/applicant_database.controller.php\";s:14:\"class_filename\";s:46:\"admin/transaction/applicant_database.class.php\";s:10:\"class_name\";s:21:\"clsApplicant_Database\";s:17:\"template_filename\";s:44:\"admin/transaction/applicant_database.tpl.php\";s:21:\"templateform_filename\";s:49:\"admin/transaction/applicant_database_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(70,'For Hire','','',63,0,'transaction.php?statpos=for_hire',1,'a:18:{s:8:\"linkpage\";s:15:\"transaction.php\";s:7:\"statpos\";s:8:\"for_hire\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:21:\"admin/transaction.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:41:\"admin/transaction/for_hire.controller.php\";s:14:\"class_filename\";s:36:\"admin/transaction/for_hire.class.php\";s:10:\"class_name\";s:11:\"clsFor_Hire\";s:17:\"template_filename\";s:34:\"admin/transaction/for_hire.tpl.php\";s:21:\"templateform_filename\";s:39:\"admin/transaction/for_hire_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(72,'Payroll Entry','','',8,30,'',1,'a:18:{s:8:\"linkpage\";s:0:\"\";s:7:\"statpos\";s:0:\"\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(73,'Bank Account Type','','',12,100,'setup.php?statpos=bankaccntype',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:12:\"bankaccntype\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:39:\"admin/setup/bankaccntype.controller.php\";s:14:\"class_filename\";s:34:\"admin/setup/bankaccntype.class.php\";s:10:\"class_name\";s:15:\"clsBankAccnType\";s:17:\"template_filename\";s:32:\"admin/setup/bankaccntype.tpl.php\";s:21:\"templateform_filename\";s:37:\"admin/setup/bankaccntype_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";s:1:\"1\";s:8:\"chkclass\";s:1:\"1\";s:11:\"chktemplate\";s:1:\"1\";s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(74,'Analysis Tools','','',16,2,'',1,'a:18:{s:8:\"linkpage\";s:0:\"\";s:7:\"statpos\";s:0:\"\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(77,'Bank Export Report','','',16,400,'reports.php?statpos=bank_export_report',1,'a:18:{s:8:\"linkpage\";s:11:\"reports.php\";s:7:\"statpos\";s:18:\"bank_export_report\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:17:\"admin/reports.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:47:\"admin/reports/bank_export_report.controller.php\";s:14:\"class_filename\";s:42:\"admin/reports/bank_export_report.class.php\";s:10:\"class_name\";s:21:\"clsBank_Export_Report\";s:17:\"template_filename\";s:40:\"admin/reports/bank_export_report.tpl.php\";s:21:\"templateform_filename\";s:45:\"admin/reports/bank_export_report_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";s:1:\"1\";s:8:\"chkclass\";s:1:\"1\";s:11:\"chktemplate\";s:1:\"1\";s:15:\"chktemplateform\";s:1:\"1\";s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(78,'201 Reports','','',16,1,'',1,'a:18:{s:8:\"linkpage\";s:0:\"\";s:7:\"statpos\";s:0:\"\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(79,'201 Master List','','',78,10,'reports.php?statpos=201mlist',1,'a:18:{s:8:\"linkpage\";s:11:\"reports.php\";s:7:\"statpos\";s:8:\"201mlist\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:17:\"admin/reports.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:37:\"admin/reports/201mlist.controller.php\";s:14:\"class_filename\";s:32:\"admin/reports/201mlist.class.php\";s:10:\"class_name\";s:11:\"cls201MList\";s:17:\"template_filename\";s:30:\"admin/reports/201mlist.tpl.php\";s:21:\"templateform_filename\";s:35:\"admin/reports/201mlist_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(80,'Birthday List','','',78,20,'reports.php?statpos=bdaylist',1,'a:18:{s:8:\"linkpage\";s:11:\"reports.php\";s:7:\"statpos\";s:8:\"bdaylist\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:17:\"admin/reports.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:37:\"admin/reports/bdaylist.controller.php\";s:14:\"class_filename\";s:32:\"admin/reports/bdaylist.class.php\";s:10:\"class_name\";s:11:\"clsBdayList\";s:17:\"template_filename\";s:30:\"admin/reports/bdaylist.tpl.php\";s:21:\"templateform_filename\";s:35:\"admin/reports/bdaylist_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(81,'Database','','',1,40,'',1,'a:18:{s:8:\"linkpage\";s:0:\"\";s:7:\"statpos\";s:0:\"\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(82,'Backup Database','','',81,10,'setup.php?statpos=bckup_dbase',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:11:\"bckup_dbase\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:38:\"admin/setup/bckup_dbase.controller.php\";s:14:\"class_filename\";s:33:\"admin/setup/bckup_dbase.class.php\";s:10:\"class_name\";s:14:\"clsBckup_Dbase\";s:17:\"template_filename\";s:31:\"admin/setup/bckup_dbase.tpl.php\";s:21:\"templateform_filename\";s:36:\"admin/setup/bckup_dbase_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";s:1:\"1\";s:8:\"chkclass\";s:1:\"1\";s:11:\"chktemplate\";s:1:\"1\";s:15:\"chktemplateform\";s:1:\"1\";s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(83,'Restore Database','','',81,20,'setup.php?statpos=restore_dbase',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:13:\"restore_dbase\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:40:\"admin/setup/restore_dbase.controller.php\";s:14:\"class_filename\";s:35:\"admin/setup/restore_dbase.class.php\";s:10:\"class_name\";s:16:\"clsRestore_Dbase\";s:17:\"template_filename\";s:33:\"admin/setup/restore_dbase.tpl.php\";s:21:\"templateform_filename\";s:38:\"admin/setup/restore_dbase_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";s:1:\"1\";s:8:\"chkclass\";s:1:\"1\";s:11:\"chktemplate\";s:1:\"1\";s:15:\"chktemplateform\";s:1:\"1\";s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(84,'Premium Report','','',16,3,'',1,'a:18:{s:8:\"linkpage\";s:0:\"\";s:7:\"statpos\";s:0:\"\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(85,'Tax Report','','',16,4,'',1,'a:18:{s:8:\"linkpage\";s:0:\"\";s:7:\"statpos\";s:0:\"\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(87,'Manage Timekeeping Attendance','','',17,50,'',1,'a:18:{s:8:\"linkpage\";s:0:\"\";s:7:\"statpos\";s:0:\"\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(88,'Payslip Report','','',16,60,'reports.php?statpos=pslipr',1,'a:18:{s:8:\"linkpage\";s:11:\"reports.php\";s:7:\"statpos\";s:6:\"pslipr\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:17:\"admin/reports.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:35:\"admin/reports/pslipr.controller.php\";s:14:\"class_filename\";s:30:\"admin/reports/pslipr.class.php\";s:10:\"class_name\";s:9:\"clsPslipR\";s:17:\"template_filename\";s:28:\"admin/reports/pslipr.tpl.php\";s:21:\"templateform_filename\";s:33:\"admin/reports/pslipr_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";s:1:\"1\";s:8:\"chkclass\";s:1:\"1\";s:11:\"chktemplate\";s:1:\"1\";s:15:\"chktemplateform\";s:1:\"1\";s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(89,'YTD Report','','',74,20,'reports.php?statpos=ytdrept',1,'a:18:{s:8:\"linkpage\";s:11:\"reports.php\";s:7:\"statpos\";s:7:\"ytdrept\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:17:\"admin/reports.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:36:\"admin/reports/ytdrept.controller.php\";s:14:\"class_filename\";s:31:\"admin/reports/ytdrept.class.php\";s:10:\"class_name\";s:10:\"clsYTDRept\";s:17:\"template_filename\";s:29:\"admin/reports/ytdrept.tpl.php\";s:21:\"templateform_filename\";s:34:\"admin/reports/ytdrept_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";s:1:\"1\";s:8:\"chkclass\";s:1:\"1\";s:11:\"chktemplate\";s:1:\"1\";s:15:\"chktemplateform\";s:1:\"1\";s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(91,'Download Backup File','','',81,30,'setup.php?statpos=dload_dbase',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:11:\"dload_dbase\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:38:\"admin/setup/dload_dbase.controller.php\";s:14:\"class_filename\";s:33:\"admin/setup/dload_dbase.class.php\";s:10:\"class_name\";s:14:\"clsDload_Dbase\";s:17:\"template_filename\";s:31:\"admin/setup/dload_dbase.tpl.php\";s:21:\"templateform_filename\";s:36:\"admin/setup/dload_dbase_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(93,'Premium Summary','','',84,1,'reports.php?statpos=summary',1,'a:18:{s:8:\"linkpage\";s:11:\"reports.php\";s:7:\"statpos\";s:7:\"summary\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:17:\"admin/reports.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:41:\"admin/reports/stat_summary.controller.php\";s:14:\"class_filename\";s:36:\"admin/reports/stat_summary.class.php\";s:10:\"class_name\";s:14:\"clsStatSummary\";s:17:\"template_filename\";s:34:\"admin/reports/stat_summary.tpl.php\";s:21:\"templateform_filename\";s:39:\"admin/reports/stat_summary_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(95,'Loan Type','','',126,10,'setup.php?statpos=loantype',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:8:\"loantype\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:35:\"admin/setup/loantype.controller.php\";s:14:\"class_filename\";s:30:\"admin/setup/loantype.class.php\";s:10:\"class_name\";s:11:\"clsLoanType\";s:17:\"template_filename\";s:28:\"admin/setup/loantype.tpl.php\";s:21:\"templateform_filename\";s:33:\"admin/setup/loantype_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(96,'Manage Import','Manage Import','',1,120,'',1,'a:18:{s:8:\"linkpage\";s:0:\"\";s:7:\"statpos\";s:0:\"\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(97,'Import Company','','',108,10,'setup.php?statpos=importcomp',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:10:\"importcomp\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:37:\"admin/setup/importcomp.controller.php\";s:14:\"class_filename\";s:32:\"admin/setup/importcomp.class.php\";s:10:\"class_name\";s:13:\"clsImportComp\";s:17:\"template_filename\";s:30:\"admin/setup/importcomp.tpl.php\";s:21:\"templateform_filename\";s:35:\"admin/setup/importcomp_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(98,'Import Department','','',108,20,'setup.php?statpos=importdept',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:10:\"importdept\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:37:\"admin/setup/importdept.controller.php\";s:14:\"class_filename\";s:32:\"admin/setup/importdept.class.php\";s:10:\"class_name\";s:13:\"clsImportDept\";s:17:\"template_filename\";s:30:\"admin/setup/importdept.tpl.php\";s:21:\"templateform_filename\";s:35:\"admin/setup/importdept_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";s:1:\"1\";s:8:\"chkclass\";s:1:\"1\";s:11:\"chktemplate\";s:1:\"1\";s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(99,'Import Job Position','','',108,30,'setup.php?statpos=importjob',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:9:\"importjob\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:36:\"admin/setup/importjob.controller.php\";s:14:\"class_filename\";s:31:\"admin/setup/importjob.class.php\";s:10:\"class_name\";s:12:\"clsImportJob\";s:17:\"template_filename\";s:29:\"admin/setup/importjob.tpl.php\";s:21:\"templateform_filename\";s:34:\"admin/setup/importjob_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";s:1:\"1\";s:8:\"chkclass\";s:1:\"1\";s:11:\"chktemplate\";s:1:\"1\";s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(100,'Branch Profile','Branch Profile','',20,30,'setup.php?statpos=branchpro',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:9:\"branchpro\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:36:\"admin/setup/branchpro.controller.php\";s:14:\"class_filename\";s:31:\"admin/setup/branchpro.class.php\";s:10:\"class_name\";s:12:\"clsBranchPro\";s:17:\"template_filename\";s:29:\"admin/setup/branchpro.tpl.php\";s:21:\"templateform_filename\";s:34:\"admin/setup/branchpro_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";s:1:\"1\";s:8:\"chkclass\";s:1:\"1\";s:11:\"chktemplate\";s:1:\"1\";s:15:\"chktemplateform\";s:1:\"1\";s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(101,'Import Branch','','',108,15,'setup.php?statpos=importbrch',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:10:\"importbrch\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:37:\"admin/setup/importbrch.controller.php\";s:14:\"class_filename\";s:32:\"admin/setup/importbrch.class.php\";s:10:\"class_name\";s:13:\"clsImportBrch\";s:17:\"template_filename\";s:30:\"admin/setup/importbrch.tpl.php\";s:21:\"templateform_filename\";s:35:\"admin/setup/importbrch_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";s:1:\"1\";s:8:\"chkclass\";s:1:\"1\";s:11:\"chktemplate\";s:1:\"1\";s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(102,'Import 201 Status','','',108,40,'setup.php?statpos=import201stat',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:13:\"import201stat\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:40:\"admin/setup/import201stat.controller.php\";s:14:\"class_filename\";s:35:\"admin/setup/import201stat.class.php\";s:10:\"class_name\";s:16:\"clsImport201Stat\";s:17:\"template_filename\";s:33:\"admin/setup/import201stat.tpl.php\";s:21:\"templateform_filename\";s:38:\"admin/setup/import201stat_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";s:1:\"1\";s:8:\"chkclass\";s:1:\"1\";s:11:\"chktemplate\";s:1:\"1\";s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(103,'Import 201 Type','Import Employee Type','',108,50,'setup.php?statpos=importemp_type',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:14:\"importemp_type\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:41:\"admin/setup/importemp_type.controller.php\";s:14:\"class_filename\";s:36:\"admin/setup/importemp_type.class.php\";s:10:\"class_name\";s:17:\"clsImportEmp_Type\";s:17:\"template_filename\";s:34:\"admin/setup/importemp_type.tpl.php\";s:21:\"templateform_filename\";s:39:\"admin/setup/importemp_type_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(104,'Import 201 Category','','',108,60,'setup.php?statpos=importemp_categ',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:15:\"importemp_categ\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:42:\"admin/setup/importemp_categ.controller.php\";s:14:\"class_filename\";s:37:\"admin/setup/importemp_categ.class.php\";s:10:\"class_name\";s:18:\"clsImportEmp_Categ\";s:17:\"template_filename\";s:35:\"admin/setup/importemp_categ.tpl.php\";s:21:\"templateform_filename\";s:40:\"admin/setup/importemp_categ_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(105,'Import OT Rate','','',96,70,'setup.php?statpos=importot_rate',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:13:\"importot_rate\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:40:\"admin/setup/importot_rate.controller.php\";s:14:\"class_filename\";s:35:\"admin/setup/importot_rate.class.php\";s:10:\"class_name\";s:16:\"clsImportOT_Rate\";s:17:\"template_filename\";s:33:\"admin/setup/importot_rate.tpl.php\";s:21:\"templateform_filename\";s:38:\"admin/setup/importot_rate_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(106,'Import Leave Type','','',108,80,'setup.php?statpos=importleav_type',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:15:\"importleav_type\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:42:\"admin/setup/importleav_type.controller.php\";s:14:\"class_filename\";s:37:\"admin/setup/importleav_type.class.php\";s:10:\"class_name\";s:18:\"clsImportLeav_Type\";s:17:\"template_filename\";s:35:\"admin/setup/importleav_type.tpl.php\";s:21:\"templateform_filename\";s:40:\"admin/setup/importleav_type_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";s:1:\"1\";s:8:\"chkclass\";s:1:\"1\";s:11:\"chktemplate\";s:1:\"1\";s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(107,'Classification','','',12,4,'setup.php?statpos=empclasify',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:10:\"empclasify\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:37:\"admin/setup/empclasify.controller.php\";s:14:\"class_filename\";s:32:\"admin/setup/empclasify.class.php\";s:10:\"class_name\";s:13:\"clsEmpClasify\";s:17:\"template_filename\";s:30:\"admin/setup/empclasify.tpl.php\";s:21:\"templateform_filename\";s:35:\"admin/setup/empclasify_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(108,'Import Libraries','','',96,10,'',1,'a:18:{s:8:\"linkpage\";s:0:\"\";s:7:\"statpos\";s:0:\"\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(109,'Import Comp. Banks','','',108,13,'setup.php?statpos=importcompbanks',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:15:\"importcompbanks\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:42:\"admin/setup/importcompbanks.controller.php\";s:14:\"class_filename\";s:37:\"admin/setup/importcompbanks.class.php\";s:10:\"class_name\";s:18:\"clsImportCompBanks\";s:17:\"template_filename\";s:35:\"admin/setup/importcompbanks.tpl.php\";s:21:\"templateform_filename\";s:40:\"admin/setup/importcompbanks_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(110,'Import 201 File','','',96,20,'setup.php?statpos=import201file',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:13:\"import201file\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:40:\"admin/setup/import201file.controller.php\";s:14:\"class_filename\";s:35:\"admin/setup/import201file.class.php\";s:10:\"class_name\";s:16:\"clsImport201File\";s:17:\"template_filename\";s:33:\"admin/setup/import201file.tpl.php\";s:21:\"templateform_filename\";s:38:\"admin/setup/import201file_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(111,'Import Dependents','','',96,50,'setup.php?statpos=importtaxdepnts',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:15:\"importtaxdepnts\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:42:\"admin/setup/importtaxdepnts.controller.php\";s:14:\"class_filename\";s:37:\"admin/setup/importtaxdepnts.class.php\";s:10:\"class_name\";s:18:\"clsImportTaxDepnts\";s:17:\"template_filename\";s:35:\"admin/setup/importtaxdepnts.tpl.php\";s:21:\"templateform_filename\";s:40:\"admin/setup/importtaxdepnts_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(112,'Import 201 Bank','','',96,40,'setup.php?statpos=import201bank',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:13:\"import201bank\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:40:\"admin/setup/import201bank.controller.php\";s:14:\"class_filename\";s:35:\"admin/setup/import201bank.class.php\";s:10:\"class_name\";s:16:\"clsImport201Bank\";s:17:\"template_filename\";s:33:\"admin/setup/import201bank.tpl.php\";s:21:\"templateform_filename\";s:38:\"admin/setup/import201bank_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(114,'Loan Setup','','',8,9,'transaction.php?statpos=loan_app',1,'a:18:{s:8:\"linkpage\";s:15:\"transaction.php\";s:7:\"statpos\";s:8:\"loan_app\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:21:\"admin/transaction.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:41:\"admin/transaction/loan_app.controller.php\";s:14:\"class_filename\";s:36:\"admin/transaction/loan_app.class.php\";s:10:\"class_name\";s:11:\"clsLoan_App\";s:17:\"template_filename\";s:34:\"admin/transaction/loan_app.tpl.php\";s:21:\"templateform_filename\";s:39:\"admin/transaction/loan_app_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(115,'Wage Rate','Wage Rate','',17,100,'setup.php?statpos=wagerate',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:8:\"wagerate\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:35:\"admin/setup/wagerate.controller.php\";s:14:\"class_filename\";s:30:\"admin/setup/wagerate.class.php\";s:10:\"class_name\";s:11:\"clsWageRate\";s:17:\"template_filename\";s:28:\"admin/setup/wagerate.tpl.php\";s:21:\"templateform_filename\";s:33:\"admin/setup/wagerate_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";s:1:\"1\";s:8:\"chkclass\";s:1:\"1\";s:11:\"chktemplate\";s:1:\"1\";s:15:\"chktemplateform\";s:1:\"1\";s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(116,'Import Loan','Import Loan','',96,50,'setup.php?statpos=importloan',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:10:\"importloan\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:37:\"admin/setup/importloan.controller.php\";s:14:\"class_filename\";s:32:\"admin/setup/importloan.class.php\";s:10:\"class_name\";s:13:\"clsImportLoan\";s:17:\"template_filename\";s:30:\"admin/setup/importloan.tpl.php\";s:21:\"templateform_filename\";s:35:\"admin/setup/importloan_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";s:1:\"1\";s:8:\"chkclass\";s:1:\"1\";s:11:\"chktemplate\";s:1:\"1\";s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(117,'Loan Information','Loan Information Report','',119,0,'reports.php?statpos=loanrept',1,'a:18:{s:8:\"linkpage\";s:11:\"reports.php\";s:7:\"statpos\";s:8:\"loanrept\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:17:\"admin/reports.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:37:\"admin/reports/loanrept.controller.php\";s:14:\"class_filename\";s:32:\"admin/reports/loanrept.class.php\";s:10:\"class_name\";s:11:\"clsLoanRept\";s:17:\"template_filename\";s:30:\"admin/reports/loanrept.tpl.php\";s:21:\"templateform_filename\";s:35:\"admin/reports/loanrept_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(118,'OT Rate List','OT Rate List','',147,10,'reports.php?statpos=otreport',1,'a:18:{s:8:\"linkpage\";s:11:\"reports.php\";s:7:\"statpos\";s:8:\"otreport\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:17:\"admin/reports.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:37:\"admin/reports/otreport.controller.php\";s:14:\"class_filename\";s:32:\"admin/reports/otreport.class.php\";s:10:\"class_name\";s:11:\"clsOTReport\";s:17:\"template_filename\";s:30:\"admin/reports/otreport.tpl.php\";s:21:\"templateform_filename\";s:35:\"admin/reports/otreport_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(119,'Loan Report','Loan Report','',16,30,'',1,'a:18:{s:8:\"linkpage\";s:0:\"\";s:7:\"statpos\";s:0:\"\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(120,'Other Loan','Other Loan Report','',119,30,'reports.php?statpos=other_lr',1,'a:18:{s:8:\"linkpage\";s:11:\"reports.php\";s:7:\"statpos\";s:8:\"other_lr\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:17:\"admin/reports.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:37:\"admin/reports/other_lr.controller.php\";s:14:\"class_filename\";s:32:\"admin/reports/other_lr.class.php\";s:10:\"class_name\";s:11:\"clsOther_LR\";s:17:\"template_filename\";s:30:\"admin/reports/other_lr.tpl.php\";s:21:\"templateform_filename\";s:35:\"admin/reports/other_lr_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(121,'Import Recurring','','',96,60,'setup.php?statpos=imbenduc',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:8:\"imbenduc\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:35:\"admin/setup/imbenduc.controller.php\";s:14:\"class_filename\";s:30:\"admin/setup/imbenduc.class.php\";s:10:\"class_name\";s:11:\"clsImBenDuc\";s:17:\"template_filename\";s:28:\"admin/setup/imbenduc.tpl.php\";s:21:\"templateform_filename\";s:33:\"admin/setup/imbenduc_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(122,'Hris','Link for OrangeHRM','',0,50,'/orangehrm-2.7.1_itochu',0,'a:18:{s:8:\"linkpage\";s:0:\"\";s:7:\"statpos\";s:0:\"\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(124,'Recurring Setup ','Recurring Benefits or\r\nRecurring Deduction\r\n','',8,5,'transaction.php?statpos=recur_setup',1,'a:18:{s:8:\"linkpage\";s:15:\"transaction.php\";s:7:\"statpos\";s:11:\"recur_setup\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:21:\"admin/transaction.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:44:\"admin/transaction/recur_setup.controller.php\";s:14:\"class_filename\";s:39:\"admin/transaction/recur_setup.class.php\";s:10:\"class_name\";s:14:\"clsRecur_Setup\";s:17:\"template_filename\";s:37:\"admin/transaction/recur_setup.tpl.php\";s:21:\"templateform_filename\";s:42:\"admin/transaction/recur_setup_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";s:1:\"1\";s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(126,'Master Data','General Setup','',1,20,'',1,'a:18:{s:8:\"linkpage\";s:0:\"\";s:7:\"statpos\";s:0:\"\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(127,'Help','Help for user','',0,70,'',1,'a:18:{s:8:\"linkpage\";s:0:\"\";s:7:\"statpos\";s:0:\"\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(128,'iPAY User Manual','','',127,10,'templates/iPAY_UM.pdf',1,'a:18:{s:8:\"linkpage\";s:8:\"help.php\";s:7:\"statpos\";s:7:\"ipay_um\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:14:\"admin/help.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:33:\"admin/help/ipay_um.controller.php\";s:14:\"class_filename\";s:28:\"admin/help/ipay_um.class.php\";s:10:\"class_name\";s:10:\"clsIPay_UM\";s:17:\"template_filename\";s:26:\"admin/help/ipay_um.tpl.php\";s:21:\"templateform_filename\";s:31:\"admin/help/ipay_um_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(129,'About','','',127,20,'help.php?statpos=about',1,'a:18:{s:8:\"linkpage\";s:8:\"help.php\";s:7:\"statpos\";s:5:\"about\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:14:\"admin/help.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:31:\"admin/help/about.controller.php\";s:14:\"class_filename\";s:26:\"admin/help/about.class.php\";s:10:\"class_name\";s:8:\"clsAbout\";s:17:\"template_filename\";s:24:\"admin/help/about.tpl.php\";s:21:\"templateform_filename\";s:29:\"admin/help/about_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";s:1:\"1\";s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(130,'General Setup','General Setup','',126,1,'setup.php?statpos=manage_decimal',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:14:\"manage_decimal\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:39:\"admin/setup/mnge_decimal.controller.php\";s:14:\"class_filename\";s:34:\"admin/setup/mnge_decimal.class.php\";s:10:\"class_name\";s:15:\"clsMnge_Decimal\";s:17:\"template_filename\";s:32:\"admin/setup/mnge_decimal.tpl.php\";s:21:\"templateform_filename\";s:37:\"admin/setup/mnge_decimal_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(131,'Payroll Import','','',8,15,'',1,'a:18:{s:8:\"linkpage\";s:0:\"\";s:7:\"statpos\";s:0:\"\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(132,'Amend Import','Amendments Import','',131,10,'transaction.php?statpos=amendimp',1,'a:18:{s:8:\"linkpage\";s:15:\"transaction.php\";s:7:\"statpos\";s:8:\"amendimp\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:21:\"admin/transaction.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:41:\"admin/transaction/amendimp.controller.php\";s:14:\"class_filename\";s:36:\"admin/transaction/amendimp.class.php\";s:10:\"class_name\";s:11:\"clsAmendImp\";s:17:\"template_filename\";s:34:\"admin/transaction/amendimp.tpl.php\";s:21:\"templateform_filename\";s:39:\"admin/transaction/amendimp_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(133,'Mass Assign','','',1,50,'',1,'a:18:{s:8:\"linkpage\";s:0:\"\";s:7:\"statpos\";s:0:\"\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(134,'Factor Rate Mass Assign','','',133,10,'setup.php?statpos=mass_assign_factor_rate',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:23:\"mass_assign_factor_rate\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(135,'OT Table Mass Assign','','',133,20,'setup.php?statpos=mass_assign_ot_table',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:20:\"mass_assign_ot_table\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(136,'Bank Group Mass Assign','','',133,30,'setup.php?statpos=mass_assign_bank_group',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:22:\"mass_assign_bank_group\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(137,'Manage Custom Fields','Manage Custom Fields','',26,10,'setup.php?statpos=mng_cf',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:6:\"mng_cf\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:33:\"admin/setup/mng_cf.controller.php\";s:14:\"class_filename\";s:28:\"admin/setup/mng_cf.class.php\";s:10:\"class_name\";s:9:\"clsMng_CF\";s:17:\"template_filename\";s:26:\"admin/setup/mng_cf.tpl.php\";s:21:\"templateform_filename\";s:31:\"admin/setup/mng_cf_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";s:1:\"1\";s:8:\"chkclass\";s:1:\"1\";s:11:\"chktemplate\";s:1:\"1\";s:15:\"chktemplateform\";s:1:\"1\";s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(138,'Manage TA Import','Manage TA Import','',58,10,'transaction.php?statpos=mng_taimp#tab-1',1,'a:18:{s:8:\"linkpage\";s:15:\"transaction.php\";s:7:\"statpos\";s:9:\"mng_taimp\";s:11:\"querystring\";s:6:\"#tab-1\";s:14:\"maincontroller\";s:21:\"admin/transaction.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:42:\"admin/transaction/mng_taimp.controller.php\";s:14:\"class_filename\";s:37:\"admin/transaction/mng_taimp.class.php\";s:10:\"class_name\";s:12:\"clsMng_TAImp\";s:17:\"template_filename\";s:35:\"admin/transaction/mng_taimp.tpl.php\";s:21:\"templateform_filename\";s:40:\"admin/transaction/mng_taimp_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(139,'YTD Entry','YTD Entry','',8,70,'',1,'a:18:{s:8:\"linkpage\";s:0:\"\";s:7:\"statpos\";s:0:\"\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(140,'YTD Process','YTD Entry Process','',139,10,'transaction.php?statpos=ytdentry_process',1,'a:18:{s:8:\"linkpage\";s:15:\"transaction.php\";s:7:\"statpos\";s:16:\"ytdentry_process\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:21:\"admin/transaction.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:49:\"admin/transaction/ytdentry_process.controller.php\";s:14:\"class_filename\";s:44:\"admin/transaction/ytdentry_process.class.php\";s:10:\"class_name\";s:19:\"clsYTDEntry_Process\";s:17:\"template_filename\";s:42:\"admin/transaction/ytdentry_process.tpl.php\";s:21:\"templateform_filename\";s:47:\"admin/transaction/ytdentry_process_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(141,'YTD Details','YTD Entry Details','',139,20,'transaction.php?statpos=ytdentry_details',1,'a:18:{s:8:\"linkpage\";s:15:\"transaction.php\";s:7:\"statpos\";s:16:\"ytdentry_details\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:21:\"admin/transaction.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:49:\"admin/transaction/ytdentry_details.controller.php\";s:14:\"class_filename\";s:44:\"admin/transaction/ytdentry_details.class.php\";s:10:\"class_name\";s:19:\"clsYTDEntry_Details\";s:17:\"template_filename\";s:42:\"admin/transaction/ytdentry_details.tpl.php\";s:21:\"templateform_filename\";s:47:\"admin/transaction/ytdentry_details_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(142,'YTD Import','','',8,45,'',1,'a:18:{s:8:\"linkpage\";s:0:\"\";s:7:\"statpos\";s:0:\"\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(143,'Pay Elements Import','YTD Pay Elements','',142,10,'transaction.php?statpos=ytd_payelem',1,'a:18:{s:8:\"linkpage\";s:15:\"transaction.php\";s:7:\"statpos\";s:11:\"ytd_payelem\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:21:\"admin/transaction.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:44:\"admin/transaction/ytd_payelem.controller.php\";s:14:\"class_filename\";s:39:\"admin/transaction/ytd_payelem.class.php\";s:10:\"class_name\";s:14:\"clsYTD_PayElem\";s:17:\"template_filename\";s:37:\"admin/transaction/ytd_payelem.tpl.php\";s:21:\"templateform_filename\";s:42:\"admin/transaction/ytd_payelem_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(144,'Import Summary/Pay Element','Import Summary/Pay Element','',142,20,'transaction.php?statpos=ytd_import',1,'a:18:{s:8:\"linkpage\";s:15:\"transaction.php\";s:7:\"statpos\";s:12:\"ytd_sumtotal\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:21:\"admin/transaction.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:45:\"admin/transaction/ytd_sumtotal.controller.php\";s:14:\"class_filename\";s:40:\"admin/transaction/ytd_sumtotal.class.php\";s:10:\"class_name\";s:15:\"clsYTD_SumTotal\";s:17:\"template_filename\";s:38:\"admin/transaction/ytd_sumtotal.tpl.php\";s:21:\"templateform_filename\";s:43:\"admin/transaction/ytd_sumtotal_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(145,'Deduction Type Mass Assign','','',133,30,'setup.php?statpos=mass_assign_deduction_type',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:26:\"mass_assign_deduction_type\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:53:\"admin/setup/mass_assign_deduction_type.controller.php\";s:14:\"class_filename\";s:48:\"admin/setup/mass_assign_deduction_type.class.php\";s:10:\"class_name\";s:26:\"clsMassAssignDeductionType\";s:17:\"template_filename\";s:46:\"admin/setup/mass_assign_deduction_type.tpl.php\";s:21:\"templateform_filename\";s:51:\"admin/setup/mass_assign_deduction_type_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(146,'Import Pay Element','Import Pay Element','',96,10,'setup.php?statpos=import_pe',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:9:\"import_pe\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:36:\"admin/setup/import_pe.controller.php\";s:14:\"class_filename\";s:31:\"admin/setup/import_pe.class.php\";s:10:\"class_name\";s:12:\"clsImport_PE\";s:17:\"template_filename\";s:29:\"admin/setup/import_pe.tpl.php\";s:21:\"templateform_filename\";s:34:\"admin/setup/import_pe_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(147,'OT Report','OT Report','',74,30,'reports.php?statpos=otrpt',1,'a:18:{s:8:\"linkpage\";s:11:\"reports.php\";s:7:\"statpos\";s:5:\"otrpt\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:17:\"admin/reports.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:34:\"admin/reports/otrpt.controller.php\";s:14:\"class_filename\";s:29:\"admin/reports/otrpt.class.php\";s:10:\"class_name\";s:8:\"clsOTRpt\";s:17:\"template_filename\";s:27:\"admin/reports/otrpt.tpl.php\";s:21:\"templateform_filename\";s:32:\"admin/reports/otrpt_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(148,'My Payslip','List of All Payslip Process in Payroll','',16,90,'reports.php?statpos=mypayslip',1,'a:18:{s:8:\"linkpage\";s:15:\"reports.php\";s:7:\"statpos\";s:9:\"mypayslip\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:21:\"admin/reports.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:42:\"admin/reports/mypayslip.controller.php\";s:14:\"class_filename\";s:37:\"admin/reports/mypayslip.class.php\";s:10:\"class_name\";s:12:\"clsMyPayslip\";s:17:\"template_filename\";s:35:\"admin/reports/mypayslip.tpl.php\";s:21:\"templateform_filename\";s:40:\"admin/reports/mypayslip_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(150,'Audit Trail','','',1,999,'',1,'a:18:{s:8:\"linkpage\";s:0:\"\";s:7:\"statpos\";s:0:\"\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(151,'ePayslip','','',150,0,'setup.php?statpos=audit_ePayslip',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:14:\"audit_ePayslip\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:9:\"setup.php\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:29:\"audit_epayslip.controller.php\";s:14:\"class_filename\";s:15:\"clsAuditPayslip\";s:10:\"class_name\";s:22:\"audit_epayslip.tpl.php\";s:17:\"template_filename\";s:27:\"audit_epayslip_form.tpl.php\";s:21:\"templateform_filename\";s:27:\"audit_epayslip_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(152,'Manage Payslip Access','Manage Payslip Password','',19,0,'setup.php?statpos=manageps_passwd',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:15:\"manageps_passwd\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:42:\"admin/setup/manageps_passwd.controller.php\";s:14:\"class_filename\";s:37:\"admin/setup/manageps_passwd.class.php\";s:10:\"class_name\";s:18:\"clsManagePS_Passwd\";s:17:\"template_filename\";s:35:\"admin/setup/manageps_passwd.tpl.php\";s:21:\"templateform_filename\";s:40:\"admin/setup/manageps_passwd_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";s:1:\"1\";s:8:\"chkclass\";s:1:\"1\";s:11:\"chktemplate\";s:1:\"1\";s:15:\"chktemplateform\";s:1:\"1\";s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(153,'Dtr','for DTR link','',0,60,'/orangeidtr_itochu',0,'a:18:{s:8:\"linkpage\";s:9:\"/ipay_dtr\";s:7:\"statpos\";s:0:\"\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}');
/*!40000 ALTER TABLE `app_modules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_province`
--

DROP TABLE IF EXISTS `app_province`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_province` (
  `p_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `r_id` int(11) unsigned NOT NULL DEFAULT '0',
  `province_id` int(10) unsigned DEFAULT NULL,
  `province_name` varchar(100) DEFAULT NULL,
  `province_addwho` varchar(50) DEFAULT NULL,
  `province_addwhen` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `province_updatewho` varchar(50) DEFAULT NULL,
  `province_updatewhen` datetime DEFAULT NULL,
  PRIMARY KEY (`p_id`)
) ENGINE=MyISAM AUTO_INCREMENT=106 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_province`
--

LOCK TABLES `app_province` WRITE;
/*!40000 ALTER TABLE `app_province` DISABLE KEYS */;
INSERT INTO `app_province` VALUES (3,15,3,'Benguet','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(4,16,9,'Navotas','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(5,16,12,'Pasig City','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(6,2,3,'Isabela Province','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(7,1,3,'La Union','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(8,2,5,'Quirino','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(9,5,2,'Camarines Norte','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(10,8,1,'Eastern Samar','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(11,11,3,'Davao del Sur','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(12,16,4,'Malabon City','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(13,3,1,'Aurora','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(14,14,4,'Surigao del Sur','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(15,7,3,'Negros Oriental','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(16,13,4,'Sulu','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(17,16,8,'Muntinlupa City','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(18,16,17,'Valenzuela','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(19,12,1,'Cotabato (North)','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(20,2,2,'Cagayan','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(21,9,2,'Zamboanga del Sur','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(22,17,17,'Romblon','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(23,8,5,'Southern Leyte','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(24,10,3,'Lanao del Norte','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(25,16,10,'Paranaque City','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(26,5,3,'Camarines Sur','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(27,3,7,'Zambales','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(28,6,5,'Iloilo','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(29,16,16,'Taguig','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(30,16,7,'Marikina City','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(31,14,1,'Agusan del Norte','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(32,14,5,'Dinagat Islands','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(33,16,2,'Las Pinas City','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(34,4,2,'Cavite','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(35,17,17,'Marinduque','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(37,10,1,'Bukidnon','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(38,10,5,'Misamis Oriental','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(39,6,3,'Capiz','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(40,4,3,'Laguna','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(41,16,1,'Kalookan','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(42,2,1,'Batanes','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(43,16,5,'Mandaluyong','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(44,10,2,'Camiguin','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(45,16,14,'Quezon City','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(46,5,1,'Albay','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(47,16,11,'Pasay City','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(48,11,4,'Davao Oriental','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(49,17,17,'Oriental Mindoro','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(50,7,1,'Bohol','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(51,15,6,'Mountain Province','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(52,3,5,'Pampanga','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(53,8,2,'Leyte','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(54,8,6,'Biliran','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(55,14,2,'Agusan del Sur','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(56,7,4,'Siquijor','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(57,13,2,'Lanao del Sur','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(58,8,4,'Samar','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(59,16,6,'Manila','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(60,15,1,'Abra','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(61,11,1,'Compostela Valley','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(62,5,4,'Catanduanes','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(63,6,1,'Aklan','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(64,2,4,'Nueva Vizcaya','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(65,3,3,'Bulacan','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(66,9,3,'Zamboanga Sibugay','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(67,11,2,'Davao del Norte','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(68,5,6,'Sorsogon','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(69,17,17,'Occidental Mindoro','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(70,16,3,'Makati City','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(71,3,2,'Bataan','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(72,12,99,'Cotabato City','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(73,14,3,'Surigao del Norte','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(74,1,1,'Ilocos Norte','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(75,12,3,'Sarangani','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(76,3,4,'Nueva Ecija','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(77,4,9,'Rizal','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(78,7,2,'Cebu','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(79,10,4,'Misamis Occidental','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(80,15,4,'Ifugao','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(81,1,2,'Ilocos Sur','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(82,8,3,'Northern Samar','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(83,15,5,'Kalinga','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(84,15,2,'Apayao','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(85,13,6,'Shariff Kabunsuan','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(86,3,6,'Tarlac','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(87,5,5,'Masbate','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(88,6,2,'Antique','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(89,6,4,'Guimaras','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(90,17,17,'Palawan','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(91,12,2,'South Cotabato','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(92,4,1,'Batangas','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(93,13,3,'Maguindanao','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(94,4,8,'Quezon','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(95,13,1,'Basilan','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(96,12,4,'Sultan Kudarat','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(97,16,13,'Pateros','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(98,6,6,'Negros Occidental','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(99,16,15,'San Juan','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(100,13,5,'Tawi-Tawi','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(101,1,4,'Pangasinan','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(102,9,1,'Zamboanga del Norte','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(104,0,0,'Antipolo','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(105,16,0,'Caloocan City','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `app_province` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_ps_passwd`
--

DROP TABLE IF EXISTS `app_ps_passwd`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_ps_passwd` (
  `ps_passwd_id` int(11) NOT NULL AUTO_INCREMENT,
  `emp_id` int(11) NOT NULL,
  `ps_passwd_password` varchar(100) NOT NULL,
  PRIMARY KEY (`ps_passwd_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_ps_passwd`
--

LOCK TABLES `app_ps_passwd` WRITE;
/*!40000 ALTER TABLE `app_ps_passwd` DISABLE KEYS */;
/*!40000 ALTER TABLE `app_ps_passwd` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_region`
--

DROP TABLE IF EXISTS `app_region`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_region` (
  `r_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cou_id` int(10) unsigned NOT NULL,
  `region_id` int(10) unsigned DEFAULT NULL,
  `region_code` varchar(4) DEFAULT NULL,
  `region_name` varchar(100) DEFAULT NULL,
  `region_desc` varchar(100) DEFAULT NULL,
  `region_ord` tinyint(11) unsigned DEFAULT NULL,
  `region_addwho` varchar(50) DEFAULT NULL,
  `region_addwhen` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `region_updatewho` varchar(50) DEFAULT NULL,
  `region_updatewhen` datetime DEFAULT NULL,
  PRIMARY KEY (`r_id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_region`
--

LOCK TABLES `app_region` WRITE;
/*!40000 ALTER TABLE `app_region` DISABLE KEYS */;
INSERT INTO `app_region` VALUES (1,1,1,'I','Region I','Ilocos Region',10,'jayadmin','2010-10-06 21:41:14','','0000-00-00 00:00:00'),(2,1,2,'II','Region II','Cagayan Valley\r',20,'jayadmin','2010-10-06 21:41:14','','0000-00-00 00:00:00'),(3,1,3,'III','Region III','Central Luzon\r',30,'jayadmin','2010-10-06 21:41:14','','0000-00-00 00:00:00'),(4,1,4,'IV-A','Region IV-A','CALABARZON\r',40,'jayadmin','2010-10-06 21:41:14','','0000-00-00 00:00:00'),(5,1,5,'V','Region V','Bicol Region',50,'jayadmin','2010-10-06 21:41:14','','0000-00-00 00:00:00'),(6,1,6,'VI','Region VI','Western Visayas\r',60,'jayadmin','2010-10-06 21:41:14','','0000-00-00 00:00:00'),(7,1,7,'VII','Region VII','Central Visayas\r',70,'jayadmin','2010-10-06 21:41:14','','0000-00-00 00:00:00'),(8,1,8,'VIII','Region VIII','Eastern Visayas\r',80,'jayadmin','2010-10-06 21:41:14','','0000-00-00 00:00:00'),(9,1,9,'IX','Region IX','Zamboanga Peninsula\r',90,'jayadmin','2010-10-06 21:41:14','','0000-00-00 00:00:00'),(10,1,10,'X','Region X','Northern Mindanao\r',100,'jayadmin','2010-10-06 21:41:14','','0000-00-00 00:00:00'),(11,1,11,'XI','Region XI','Davao Region\r',110,'jayadmin','2010-10-06 21:41:14','','0000-00-00 00:00:00'),(12,1,12,'XII','Region XII','SOCCSKSARGEN\r',120,'jayadmin','2010-10-06 21:41:14','','0000-00-00 00:00:00'),(13,1,13,'ARMM','A.R.M.M','Autonomous Region of Muslim Mindanao',140,'jayadmin','2010-10-06 21:41:14','jayadmin','2010-10-07 23:23:49'),(14,1,14,'XIII','Region XIII','Caraga Region',130,'jayadmin','2010-10-06 21:41:14','jayadmin','2010-10-07 23:24:08'),(15,1,15,'CAR','C.A.R.','Cordillera Administrative Region',150,'jayadmin','2010-10-06 21:41:14','jayadmin','2010-10-07 23:21:37'),(16,1,16,'NCR','N.C.R.','National Capital Region',160,'jayadmin','2010-10-06 21:41:14','jayadmin','2010-10-07 23:21:59'),(17,1,17,'IV-B','Region IV-B','MIMAROPA',45,'jayadmin','2010-10-06 21:41:14','','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `app_region` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_settings`
--

DROP TABLE IF EXISTS `app_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_settings` (
  `set_id` int(11) NOT NULL AUTO_INCREMENT,
  `comp_id` int(11) NOT NULL,
  `set_name` varchar(255) NOT NULL,
  `set_decimal_places` int(11) NOT NULL DEFAULT '5',
  `set_order` int(11) DEFAULT '1',
  `set_stat_type` tinyint(1) DEFAULT '1' COMMENT '1=Gross, 0=Basic',
  PRIMARY KEY (`set_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_settings`
--

LOCK TABLES `app_settings` WRITE;
/*!40000 ALTER TABLE `app_settings` DISABLE KEYS */;
INSERT INTO `app_settings` VALUES (1,1,'General Decimal Settings',5,10,1),(2,1,'Final Decimal Settings',2,20,1),(3,1,'TAX',1,1,1),(4,1,'SSS',2,0,1),(5,1,'PHIC',5,0,0),(6,1,'HDMF',3,0,0),(7,1,'TA Import Form',0,0,1),(8,1,'Payslip FORM',0,0,1),(9,1,'Location as Company',0,0,1);
/*!40000 ALTER TABLE `app_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_userdept`
--

DROP TABLE IF EXISTS `app_userdept`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_userdept` (
  `ud_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ud_code` varchar(200) COLLATE latin1_general_ci DEFAULT NULL,
  `ud_name` varchar(200) COLLATE latin1_general_ci DEFAULT NULL,
  `ud_desc` varchar(200) COLLATE latin1_general_ci DEFAULT NULL,
  `ud_parent` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`ud_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_userdept`
--

LOCK TABLES `app_userdept` WRITE;
/*!40000 ALTER TABLE `app_userdept` DISABLE KEYS */;
INSERT INTO `app_userdept` VALUES (1,'0','IT Dept.','Information Technology Department',0);
/*!40000 ALTER TABLE `app_userdept` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_users`
--

DROP TABLE IF EXISTS `app_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `emp_id` int(10) unsigned NOT NULL,
  `ud_id` int(10) unsigned NOT NULL,
  `user_name` varchar(100) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `user_fullname` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `user_password` varchar(100) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `user_type` varchar(64) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `user_status` tinyint(2) NOT NULL DEFAULT '1',
  `user_email` varchar(255) DEFAULT NULL,
  `user_emailpass` varchar(255) DEFAULT NULL,
  `user_smtp` varchar(255) DEFAULT NULL,
  `user_port` varchar(20) DEFAULT NULL,
  `user_comp_list` text,
  `user_branch_list` text,
  `user_201stat_list` text,
  `user_paygroup_list` text,
  `user_picture` mediumblob,
  PRIMARY KEY (`user_id`),
  KEY `user_name` (`user_name`),
  KEY `user_password` (`user_password`),
  KEY `app_users_FKIndex1` (`user_type`),
  KEY `app_users_FKIndex2` (`ud_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_users`
--

LOCK TABLES `app_users` WRITE;
/*!40000 ALTER TABLE `app_users` DISABLE KEYS */;
INSERT INTO `app_users` VALUES (1,0,1,'jimabignay','Jason Mabignay','8020a11ea1be81e9fb1e0a5deda6fffc','Super Administrator',1,'jason.mabignay@sigmasoft.com.ph','test','mail.sample.com.ph','25',NULL,NULL,NULL,NULL,NULL),(2,0,1,'irsalvador','Ishmael Rolph C. Salvador','19ced5b5c528a2c4aa19aa27e52c2d7d','Super Administrator',1,'irsalvador06@gmail.com','','','','',NULL,NULL,NULL,''),(3,0,1,'duntiveros','Dionisio M. Untiveros Jr.','759c2375c925d29a9c48e98147c46dbc','Super Administrator',1,'duntiverosjr@gmail.com','','','','',NULL,NULL,NULL,''),(4,0,1,'admin','Administrator','21232f297a57a5a743894a0e4a801fc3','Administrator',1,'','','','','',NULL,NULL,NULL,'');
/*!40000 ALTER TABLE `app_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_userstypeaccess`
--

DROP TABLE IF EXISTS `app_userstypeaccess`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_userstypeaccess` (
  `uta_id` int(11) NOT NULL AUTO_INCREMENT,
  `ud_id` int(10) unsigned NOT NULL,
  `mnu_id` int(11) NOT NULL,
  `user_type` varchar(64) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`uta_id`),
  KEY `app_userstypeaccess_FKIndex1` (`user_type`),
  KEY `app_userstypeaccess_FKIndex2` (`mnu_id`),
  KEY `app_userstypeaccess_FKIndex3` (`ud_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4175 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_userstypeaccess`
--

LOCK TABLES `app_userstypeaccess` WRITE;
/*!40000 ALTER TABLE `app_userstypeaccess` DISABLE KEYS */;
INSERT INTO `app_userstypeaccess` VALUES (1,1,7,'Super Administrator'),(2,1,1,'Super Administrator'),(3,1,3,'Super Administrator'),(4,1,17,'Super Administrator'),(5,1,20,'Super Administrator'),(6,1,10,'Super Administrator'),(7,1,100,'Super Administrator'),(8,1,5,'Super Administrator'),(9,1,12,'Super Administrator'),(10,1,65,'Super Administrator'),(11,1,107,'Super Administrator'),(12,1,13,'Super Administrator'),(13,1,14,'Super Administrator'),(14,1,15,'Super Administrator'),(15,1,32,'Super Administrator'),(16,1,43,'Super Administrator'),(17,1,45,'Super Administrator'),(18,1,73,'Super Administrator'),(19,1,66,'Super Administrator'),(20,1,26,'Super Administrator'),(21,1,137,'Super Administrator'),(22,1,27,'Super Administrator'),(23,1,28,'Super Administrator'),(24,1,29,'Super Administrator'),(25,1,30,'Super Administrator'),(26,1,31,'Super Administrator'),(27,1,44,'Super Administrator'),(28,1,115,'Super Administrator'),(29,1,126,'Super Administrator'),(30,1,19,'Super Administrator'),(31,1,4,'Super Administrator'),(32,1,2,'Super Administrator'),(33,1,21,'Super Administrator'),(34,1,130,'Super Administrator'),(35,1,95,'Super Administrator'),(36,1,47,'Super Administrator'),(37,1,48,'Super Administrator'),(38,1,49,'Super Administrator'),(39,1,60,'Super Administrator'),(40,1,59,'Super Administrator'),(41,1,81,'Super Administrator'),(42,1,82,'Super Administrator'),(43,1,83,'Super Administrator'),(44,1,91,'Super Administrator'),(45,1,113,'Super Administrator'),(46,1,133,'Super Administrator'),(47,1,134,'Super Administrator'),(48,1,135,'Super Administrator'),(49,1,145,'Super Administrator'),(50,1,96,'Super Administrator'),(51,1,146,'Super Administrator'),(52,1,116,'Super Administrator'),(53,1,121,'Super Administrator'),(54,1,105,'Super Administrator'),(55,1,8,'Super Administrator'),(56,1,64,'Super Administrator'),(57,1,9,'Super Administrator'),(58,1,124,'Super Administrator'),(59,1,114,'Super Administrator'),(60,1,131,'Super Administrator'),(61,1,132,'Super Administrator'),(62,1,58,'Super Administrator'),(63,1,72,'Super Administrator'),(64,1,42,'Super Administrator'),(65,1,25,'Super Administrator'),(66,1,46,'Super Administrator'),(67,1,16,'Super Administrator'),(68,1,78,'Super Administrator'),(69,1,79,'Super Administrator'),(70,1,80,'Super Administrator'),(71,1,74,'Super Administrator'),(72,1,56,'Super Administrator'),(73,1,89,'Super Administrator'),(74,1,147,'Super Administrator'),(75,1,118,'Super Administrator'),(76,1,84,'Super Administrator'),(77,1,93,'Super Administrator'),(78,1,34,'Super Administrator'),(79,1,35,'Super Administrator'),(80,1,36,'Super Administrator'),(81,1,85,'Super Administrator'),(82,1,37,'Super Administrator'),(83,1,41,'Super Administrator'),(84,1,119,'Super Administrator'),(85,1,117,'Super Administrator'),(86,1,39,'Super Administrator'),(87,1,40,'Super Administrator'),(88,1,120,'Super Administrator'),(89,1,88,'Super Administrator'),(90,1,77,'Super Administrator'),(92,1,127,'Super Administrator'),(93,1,128,'Super Administrator'),(94,1,129,'Super Administrator'),(2039,5,145,'Payroll Master'),(2040,5,144,'Payroll Master'),(2041,7,145,'Payroll Master'),(2042,7,144,'Payroll Master'),(2043,4,145,'Payroll Master'),(2044,4,144,'Payroll Master'),(2045,12,145,'Payroll Master'),(2046,12,144,'Payroll Master'),(2047,2,145,'Payroll Master'),(2048,2,144,'Payroll Master'),(2049,3,145,'Payroll Master'),(2050,3,144,'Payroll Master'),(2051,1,145,'Payroll Master'),(2052,1,144,'Payroll Master'),(2053,11,145,'Payroll Master'),(2054,11,144,'Payroll Master'),(2055,6,145,'Payroll Master'),(2056,6,144,'Payroll Master'),(2057,8,145,'Payroll Master'),(2058,8,144,'Payroll Master'),(2059,9,145,'Payroll Master'),(2060,9,144,'Payroll Master'),(2061,10,145,'Payroll Master'),(2062,10,144,'Payroll Master'),(2063,5,145,'HR MASTER'),(2064,5,144,'HR MASTER'),(2065,7,145,'HR MASTER'),(2066,7,144,'HR MASTER'),(2067,4,145,'HR MASTER'),(2068,4,144,'HR MASTER'),(2069,12,145,'HR MASTER'),(2070,12,144,'HR MASTER'),(2071,2,145,'HR MASTER'),(2072,2,144,'HR MASTER'),(2073,3,145,'HR MASTER'),(2074,3,144,'HR MASTER'),(2075,1,145,'HR MASTER'),(2076,1,144,'HR MASTER'),(2077,11,145,'HR MASTER'),(2078,11,144,'HR MASTER'),(2079,6,145,'HR MASTER'),(2080,6,144,'HR MASTER'),(2081,8,145,'HR MASTER'),(2082,8,144,'HR MASTER'),(2083,9,145,'HR MASTER'),(2084,9,144,'HR MASTER'),(2085,10,145,'HR MASTER'),(2086,10,144,'HR MASTER'),(2087,5,145,'LOAN MASTER'),(2088,5,144,'LOAN MASTER'),(2089,7,145,'LOAN MASTER'),(2090,7,144,'LOAN MASTER'),(2091,4,145,'LOAN MASTER'),(2092,4,144,'LOAN MASTER'),(2093,12,145,'LOAN MASTER'),(2094,12,144,'LOAN MASTER'),(2095,2,145,'LOAN MASTER'),(2096,2,144,'LOAN MASTER'),(2097,3,145,'LOAN MASTER'),(2098,3,144,'LOAN MASTER'),(2099,1,145,'LOAN MASTER'),(2100,1,144,'LOAN MASTER'),(2101,11,145,'LOAN MASTER'),(2102,11,144,'LOAN MASTER'),(2103,6,145,'LOAN MASTER'),(2104,6,144,'LOAN MASTER'),(2105,8,145,'LOAN MASTER'),(2106,8,144,'LOAN MASTER'),(2107,9,145,'LOAN MASTER'),(2108,9,144,'LOAN MASTER'),(2109,10,145,'LOAN MASTER'),(2110,10,144,'LOAN MASTER'),(2111,5,145,'ADMIN MASTER'),(2112,5,144,'ADMIN MASTER'),(2113,7,145,'ADMIN MASTER'),(2114,7,144,'ADMIN MASTER'),(2115,4,145,'ADMIN MASTER'),(2116,4,144,'ADMIN MASTER'),(2117,12,145,'ADMIN MASTER'),(2118,12,144,'ADMIN MASTER'),(2119,2,145,'ADMIN MASTER'),(2120,2,144,'ADMIN MASTER'),(2121,3,145,'ADMIN MASTER'),(2122,3,144,'ADMIN MASTER'),(2123,1,145,'ADMIN MASTER'),(2124,1,144,'ADMIN MASTER'),(2125,11,145,'ADMIN MASTER'),(2126,11,144,'ADMIN MASTER'),(2127,6,145,'ADMIN MASTER'),(2128,6,144,'ADMIN MASTER'),(2129,8,145,'ADMIN MASTER'),(2130,8,144,'ADMIN MASTER'),(2131,9,145,'ADMIN MASTER'),(2132,9,144,'ADMIN MASTER'),(2133,10,145,'ADMIN MASTER'),(2134,10,144,'ADMIN MASTER'),(3131,5,7,'Administrator'),(3132,5,1,'Administrator'),(3133,5,17,'Administrator'),(3134,5,26,'Administrator'),(3135,5,137,'Administrator'),(3136,5,27,'Administrator'),(3137,5,28,'Administrator'),(3138,5,29,'Administrator'),(3139,5,30,'Administrator'),(3140,5,31,'Administrator'),(3141,5,115,'Administrator'),(3142,5,44,'Administrator'),(3143,5,126,'Administrator'),(3144,5,19,'Administrator'),(3145,5,152,'Administrator'),(3146,5,2,'Administrator'),(3147,5,21,'Administrator'),(3148,5,130,'Administrator'),(3149,5,95,'Administrator'),(3150,5,47,'Administrator'),(3151,5,48,'Administrator'),(3152,5,49,'Administrator'),(3153,5,60,'Administrator'),(3154,5,59,'Administrator'),(3155,5,133,'Administrator'),(3156,5,134,'Administrator'),(3157,5,135,'Administrator'),(3158,5,145,'Administrator'),(3159,5,96,'Administrator'),(3160,5,146,'Administrator'),(3161,5,116,'Administrator'),(3162,5,121,'Administrator'),(3163,5,105,'Administrator'),(3164,5,150,'Administrator'),(3165,5,151,'Administrator'),(3166,5,8,'Administrator'),(3167,5,9,'Administrator'),(3168,5,124,'Administrator'),(3169,5,114,'Administrator'),(3170,5,131,'Administrator'),(3171,5,132,'Administrator'),(3172,5,58,'Administrator'),(3173,5,72,'Administrator'),(3174,5,42,'Administrator'),(3175,5,25,'Administrator'),(3176,5,46,'Administrator'),(3177,5,142,'Administrator'),(3178,5,144,'Administrator'),(3179,5,139,'Administrator'),(3180,5,140,'Administrator'),(3181,5,16,'Administrator'),(3182,5,78,'Administrator'),(3183,5,79,'Administrator'),(3184,5,80,'Administrator'),(3185,5,74,'Administrator'),(3186,5,56,'Administrator'),(3187,5,89,'Administrator'),(3188,5,147,'Administrator'),(3189,5,118,'Administrator'),(3190,5,154,'Administrator'),(3191,5,84,'Administrator'),(3192,5,93,'Administrator'),(3193,5,34,'Administrator'),(3194,5,35,'Administrator'),(3195,5,36,'Administrator'),(3196,5,85,'Administrator'),(3197,5,37,'Administrator'),(3198,5,41,'Administrator'),(3199,5,119,'Administrator'),(3200,5,117,'Administrator'),(3201,5,39,'Administrator'),(3202,5,40,'Administrator'),(3203,5,88,'Administrator'),(3204,5,148,'Administrator'),(3205,5,77,'Administrator'),(3206,5,122,'Administrator'),(3207,5,127,'Administrator'),(3208,5,128,'Administrator'),(3209,5,129,'Administrator'),(3210,5,153,'Administrator'),(3211,7,7,'Administrator'),(3212,7,1,'Administrator'),(3213,7,17,'Administrator'),(3214,7,26,'Administrator'),(3215,7,137,'Administrator'),(3216,7,27,'Administrator'),(3217,7,28,'Administrator'),(3218,7,29,'Administrator'),(3219,7,30,'Administrator'),(3220,7,31,'Administrator'),(3221,7,115,'Administrator'),(3222,7,44,'Administrator'),(3223,7,126,'Administrator'),(3224,7,19,'Administrator'),(3225,7,152,'Administrator'),(3226,7,2,'Administrator'),(3227,7,21,'Administrator'),(3228,7,130,'Administrator'),(3229,7,95,'Administrator'),(3230,7,47,'Administrator'),(3231,7,48,'Administrator'),(3232,7,49,'Administrator'),(3233,7,60,'Administrator'),(3234,7,59,'Administrator'),(3235,7,133,'Administrator'),(3236,7,134,'Administrator'),(3237,7,135,'Administrator'),(3238,7,145,'Administrator'),(3239,7,96,'Administrator'),(3240,7,146,'Administrator'),(3241,7,116,'Administrator'),(3242,7,121,'Administrator'),(3243,7,105,'Administrator'),(3244,7,150,'Administrator'),(3245,7,151,'Administrator'),(3246,7,8,'Administrator'),(3247,7,9,'Administrator'),(3248,7,124,'Administrator'),(3249,7,114,'Administrator'),(3250,7,131,'Administrator'),(3251,7,132,'Administrator'),(3252,7,58,'Administrator'),(3253,7,72,'Administrator'),(3254,7,42,'Administrator'),(3255,7,25,'Administrator'),(3256,7,46,'Administrator'),(3257,7,142,'Administrator'),(3258,7,144,'Administrator'),(3259,7,139,'Administrator'),(3260,7,140,'Administrator'),(3261,7,16,'Administrator'),(3262,7,78,'Administrator'),(3263,7,79,'Administrator'),(3264,7,80,'Administrator'),(3265,7,74,'Administrator'),(3266,7,56,'Administrator'),(3267,7,89,'Administrator'),(3268,7,147,'Administrator'),(3269,7,118,'Administrator'),(3270,7,154,'Administrator'),(3271,7,84,'Administrator'),(3272,7,93,'Administrator'),(3273,7,34,'Administrator'),(3274,7,35,'Administrator'),(3275,7,36,'Administrator'),(3276,7,85,'Administrator'),(3277,7,37,'Administrator'),(3278,7,41,'Administrator'),(3279,7,119,'Administrator'),(3280,7,117,'Administrator'),(3281,7,39,'Administrator'),(3282,7,40,'Administrator'),(3283,7,88,'Administrator'),(3284,7,148,'Administrator'),(3285,7,77,'Administrator'),(3286,7,122,'Administrator'),(3287,7,127,'Administrator'),(3288,7,128,'Administrator'),(3289,7,129,'Administrator'),(3290,7,153,'Administrator'),(3291,4,7,'Administrator'),(3292,4,1,'Administrator'),(3293,4,17,'Administrator'),(3294,4,26,'Administrator'),(3295,4,137,'Administrator'),(3296,4,27,'Administrator'),(3297,4,28,'Administrator'),(3298,4,29,'Administrator'),(3299,4,30,'Administrator'),(3300,4,31,'Administrator'),(3301,4,115,'Administrator'),(3302,4,44,'Administrator'),(3303,4,126,'Administrator'),(3304,4,19,'Administrator'),(3305,4,152,'Administrator'),(3306,4,2,'Administrator'),(3307,4,21,'Administrator'),(3308,4,130,'Administrator'),(3309,4,95,'Administrator'),(3310,4,47,'Administrator'),(3311,4,48,'Administrator'),(3312,4,49,'Administrator'),(3313,4,60,'Administrator'),(3314,4,59,'Administrator'),(3315,4,133,'Administrator'),(3316,4,134,'Administrator'),(3317,4,135,'Administrator'),(3318,4,145,'Administrator'),(3319,4,96,'Administrator'),(3320,4,146,'Administrator'),(3321,4,116,'Administrator'),(3322,4,121,'Administrator'),(3323,4,105,'Administrator'),(3324,4,150,'Administrator'),(3325,4,151,'Administrator'),(3326,4,8,'Administrator'),(3327,4,9,'Administrator'),(3328,4,124,'Administrator'),(3329,4,114,'Administrator'),(3330,4,131,'Administrator'),(3331,4,132,'Administrator'),(3332,4,58,'Administrator'),(3333,4,72,'Administrator'),(3334,4,42,'Administrator'),(3335,4,25,'Administrator'),(3336,4,46,'Administrator'),(3337,4,142,'Administrator'),(3338,4,144,'Administrator'),(3339,4,139,'Administrator'),(3340,4,140,'Administrator'),(3341,4,16,'Administrator'),(3342,4,78,'Administrator'),(3343,4,79,'Administrator'),(3344,4,80,'Administrator'),(3345,4,74,'Administrator'),(3346,4,56,'Administrator'),(3347,4,89,'Administrator'),(3348,4,147,'Administrator'),(3349,4,118,'Administrator'),(3350,4,154,'Administrator'),(3351,4,84,'Administrator'),(3352,4,93,'Administrator'),(3353,4,34,'Administrator'),(3354,4,35,'Administrator'),(3355,4,36,'Administrator'),(3356,4,85,'Administrator'),(3357,4,37,'Administrator'),(3358,4,41,'Administrator'),(3359,4,119,'Administrator'),(3360,4,117,'Administrator'),(3361,4,39,'Administrator'),(3362,4,40,'Administrator'),(3363,4,88,'Administrator'),(3364,4,148,'Administrator'),(3365,4,77,'Administrator'),(3366,4,122,'Administrator'),(3367,4,127,'Administrator'),(3368,4,128,'Administrator'),(3369,4,129,'Administrator'),(3370,4,153,'Administrator'),(3371,12,7,'Administrator'),(3372,12,1,'Administrator'),(3373,12,17,'Administrator'),(3374,12,26,'Administrator'),(3375,12,137,'Administrator'),(3376,12,27,'Administrator'),(3377,12,28,'Administrator'),(3378,12,29,'Administrator'),(3379,12,30,'Administrator'),(3380,12,31,'Administrator'),(3381,12,115,'Administrator'),(3382,12,44,'Administrator'),(3383,12,126,'Administrator'),(3384,12,19,'Administrator'),(3385,12,152,'Administrator'),(3386,12,2,'Administrator'),(3387,12,21,'Administrator'),(3388,12,130,'Administrator'),(3389,12,95,'Administrator'),(3390,12,47,'Administrator'),(3391,12,48,'Administrator'),(3392,12,49,'Administrator'),(3393,12,60,'Administrator'),(3394,12,59,'Administrator'),(3395,12,133,'Administrator'),(3396,12,134,'Administrator'),(3397,12,135,'Administrator'),(3398,12,145,'Administrator'),(3399,12,96,'Administrator'),(3400,12,146,'Administrator'),(3401,12,116,'Administrator'),(3402,12,121,'Administrator'),(3403,12,105,'Administrator'),(3404,12,150,'Administrator'),(3405,12,151,'Administrator'),(3406,12,8,'Administrator'),(3407,12,9,'Administrator'),(3408,12,124,'Administrator'),(3409,12,114,'Administrator'),(3410,12,131,'Administrator'),(3411,12,132,'Administrator'),(3412,12,58,'Administrator'),(3413,12,72,'Administrator'),(3414,12,42,'Administrator'),(3415,12,25,'Administrator'),(3416,12,46,'Administrator'),(3417,12,142,'Administrator'),(3418,12,144,'Administrator'),(3419,12,139,'Administrator'),(3420,12,140,'Administrator'),(3421,12,16,'Administrator'),(3422,12,78,'Administrator'),(3423,12,79,'Administrator'),(3424,12,80,'Administrator'),(3425,12,74,'Administrator'),(3426,12,56,'Administrator'),(3427,12,89,'Administrator'),(3428,12,147,'Administrator'),(3429,12,118,'Administrator'),(3430,12,154,'Administrator'),(3431,12,84,'Administrator'),(3432,12,93,'Administrator'),(3433,12,34,'Administrator'),(3434,12,35,'Administrator'),(3435,12,36,'Administrator'),(3436,12,85,'Administrator'),(3437,12,37,'Administrator'),(3438,12,41,'Administrator'),(3439,12,119,'Administrator'),(3440,12,117,'Administrator'),(3441,12,39,'Administrator'),(3442,12,40,'Administrator'),(3443,12,88,'Administrator'),(3444,12,148,'Administrator'),(3445,12,77,'Administrator'),(3446,12,122,'Administrator'),(3447,12,127,'Administrator'),(3448,12,128,'Administrator'),(3449,12,129,'Administrator'),(3450,12,153,'Administrator'),(3451,2,7,'Administrator'),(3452,2,1,'Administrator'),(3453,2,17,'Administrator'),(3454,2,26,'Administrator'),(3455,2,137,'Administrator'),(3456,2,27,'Administrator'),(3457,2,28,'Administrator'),(3458,2,29,'Administrator'),(3459,2,30,'Administrator'),(3460,2,31,'Administrator'),(3461,2,115,'Administrator'),(3462,2,44,'Administrator'),(3463,2,126,'Administrator'),(3464,2,19,'Administrator'),(3465,2,152,'Administrator'),(3466,2,2,'Administrator'),(3467,2,21,'Administrator'),(3468,2,130,'Administrator'),(3469,2,95,'Administrator'),(3470,2,47,'Administrator'),(3471,2,48,'Administrator'),(3472,2,49,'Administrator'),(3473,2,60,'Administrator'),(3474,2,59,'Administrator'),(3475,2,133,'Administrator'),(3476,2,134,'Administrator'),(3477,2,135,'Administrator'),(3478,2,145,'Administrator'),(3479,2,96,'Administrator'),(3480,2,146,'Administrator'),(3481,2,116,'Administrator'),(3482,2,121,'Administrator'),(3483,2,105,'Administrator'),(3484,2,150,'Administrator'),(3485,2,151,'Administrator'),(3486,2,8,'Administrator'),(3487,2,9,'Administrator'),(3488,2,124,'Administrator'),(3489,2,114,'Administrator'),(3490,2,131,'Administrator'),(3491,2,132,'Administrator'),(3492,2,58,'Administrator'),(3493,2,72,'Administrator'),(3494,2,42,'Administrator'),(3495,2,25,'Administrator'),(3496,2,46,'Administrator'),(3497,2,142,'Administrator'),(3498,2,144,'Administrator'),(3499,2,139,'Administrator'),(3500,2,140,'Administrator'),(3501,2,16,'Administrator'),(3502,2,78,'Administrator'),(3503,2,79,'Administrator'),(3504,2,80,'Administrator'),(3505,2,74,'Administrator'),(3506,2,56,'Administrator'),(3507,2,89,'Administrator'),(3508,2,147,'Administrator'),(3509,2,118,'Administrator'),(3510,2,154,'Administrator'),(3511,2,84,'Administrator'),(3512,2,93,'Administrator'),(3513,2,34,'Administrator'),(3514,2,35,'Administrator'),(3515,2,36,'Administrator'),(3516,2,85,'Administrator'),(3517,2,37,'Administrator'),(3518,2,41,'Administrator'),(3519,2,119,'Administrator'),(3520,2,117,'Administrator'),(3521,2,39,'Administrator'),(3522,2,40,'Administrator'),(3523,2,88,'Administrator'),(3524,2,148,'Administrator'),(3525,2,77,'Administrator'),(3526,2,122,'Administrator'),(3527,2,127,'Administrator'),(3528,2,128,'Administrator'),(3529,2,129,'Administrator'),(3530,2,153,'Administrator'),(3531,3,7,'Administrator'),(3532,3,1,'Administrator'),(3533,3,17,'Administrator'),(3534,3,26,'Administrator'),(3535,3,137,'Administrator'),(3536,3,27,'Administrator'),(3537,3,28,'Administrator'),(3538,3,29,'Administrator'),(3539,3,30,'Administrator'),(3540,3,31,'Administrator'),(3541,3,115,'Administrator'),(3542,3,44,'Administrator'),(3543,3,126,'Administrator'),(3544,3,19,'Administrator'),(3545,3,152,'Administrator'),(3546,3,2,'Administrator'),(3547,3,21,'Administrator'),(3548,3,130,'Administrator'),(3549,3,95,'Administrator'),(3550,3,47,'Administrator'),(3551,3,48,'Administrator'),(3552,3,49,'Administrator'),(3553,3,60,'Administrator'),(3554,3,59,'Administrator'),(3555,3,133,'Administrator'),(3556,3,134,'Administrator'),(3557,3,135,'Administrator'),(3558,3,145,'Administrator'),(3559,3,96,'Administrator'),(3560,3,146,'Administrator'),(3561,3,116,'Administrator'),(3562,3,121,'Administrator'),(3563,3,105,'Administrator'),(3564,3,150,'Administrator'),(3565,3,151,'Administrator'),(3566,3,8,'Administrator'),(3567,3,9,'Administrator'),(3568,3,124,'Administrator'),(3569,3,114,'Administrator'),(3570,3,131,'Administrator'),(3571,3,132,'Administrator'),(3572,3,58,'Administrator'),(3573,3,72,'Administrator'),(3574,3,42,'Administrator'),(3575,3,25,'Administrator'),(3576,3,46,'Administrator'),(3577,3,142,'Administrator'),(3578,3,144,'Administrator'),(3579,3,139,'Administrator'),(3580,3,140,'Administrator'),(3581,3,16,'Administrator'),(3582,3,78,'Administrator'),(3583,3,79,'Administrator'),(3584,3,80,'Administrator'),(3585,3,74,'Administrator'),(3586,3,56,'Administrator'),(3587,3,89,'Administrator'),(3588,3,147,'Administrator'),(3589,3,118,'Administrator'),(3590,3,154,'Administrator'),(3591,3,84,'Administrator'),(3592,3,93,'Administrator'),(3593,3,34,'Administrator'),(3594,3,35,'Administrator'),(3595,3,36,'Administrator'),(3596,3,85,'Administrator'),(3597,3,37,'Administrator'),(3598,3,41,'Administrator'),(3599,3,119,'Administrator'),(3600,3,117,'Administrator'),(3601,3,39,'Administrator'),(3602,3,40,'Administrator'),(3603,3,88,'Administrator'),(3604,3,148,'Administrator'),(3605,3,77,'Administrator'),(3606,3,122,'Administrator'),(3607,3,127,'Administrator'),(3608,3,128,'Administrator'),(3609,3,129,'Administrator'),(3610,3,153,'Administrator'),(3611,1,7,'Administrator'),(3612,1,1,'Administrator'),(3613,1,17,'Administrator'),(3614,1,26,'Administrator'),(3615,1,137,'Administrator'),(3616,1,27,'Administrator'),(3617,1,28,'Administrator'),(3618,1,29,'Administrator'),(3619,1,30,'Administrator'),(3620,1,31,'Administrator'),(3621,1,115,'Administrator'),(3622,1,44,'Administrator'),(3623,1,126,'Administrator'),(3624,1,19,'Administrator'),(3625,1,152,'Administrator'),(3626,1,2,'Administrator'),(3627,1,21,'Administrator'),(3628,1,130,'Administrator'),(3629,1,95,'Administrator'),(3630,1,47,'Administrator'),(3631,1,48,'Administrator'),(3632,1,49,'Administrator'),(3633,1,60,'Administrator'),(3634,1,59,'Administrator'),(3635,1,133,'Administrator'),(3636,1,134,'Administrator'),(3637,1,135,'Administrator'),(3638,1,145,'Administrator'),(3639,1,96,'Administrator'),(3640,1,146,'Administrator'),(3641,1,116,'Administrator'),(3642,1,121,'Administrator'),(3643,1,105,'Administrator'),(3644,1,150,'Administrator'),(3645,1,151,'Administrator'),(3646,1,8,'Administrator'),(3647,1,9,'Administrator'),(3648,1,124,'Administrator'),(3649,1,114,'Administrator'),(3650,1,131,'Administrator'),(3651,1,132,'Administrator'),(3652,1,58,'Administrator'),(3653,1,72,'Administrator'),(3654,1,42,'Administrator'),(3655,1,25,'Administrator'),(3656,1,46,'Administrator'),(3657,1,142,'Administrator'),(3658,1,144,'Administrator'),(3659,1,139,'Administrator'),(3660,1,140,'Administrator'),(3661,1,16,'Administrator'),(3662,1,78,'Administrator'),(3663,1,79,'Administrator'),(3664,1,80,'Administrator'),(3665,1,74,'Administrator'),(3666,1,56,'Administrator'),(3667,1,89,'Administrator'),(3668,1,147,'Administrator'),(3669,1,118,'Administrator'),(3670,1,154,'Administrator'),(3671,1,84,'Administrator'),(3672,1,93,'Administrator'),(3673,1,34,'Administrator'),(3674,1,35,'Administrator'),(3675,1,36,'Administrator'),(3676,1,85,'Administrator'),(3677,1,37,'Administrator'),(3678,1,41,'Administrator'),(3679,1,119,'Administrator'),(3680,1,117,'Administrator'),(3681,1,39,'Administrator'),(3682,1,40,'Administrator'),(3683,1,88,'Administrator'),(3684,1,148,'Administrator'),(3685,1,77,'Administrator'),(3686,1,122,'Administrator'),(3687,1,127,'Administrator'),(3688,1,128,'Administrator'),(3689,1,129,'Administrator'),(3690,1,153,'Administrator'),(3691,11,7,'Administrator'),(3692,11,1,'Administrator'),(3693,11,17,'Administrator'),(3694,11,26,'Administrator'),(3695,11,137,'Administrator'),(3696,11,27,'Administrator'),(3697,11,28,'Administrator'),(3698,11,29,'Administrator'),(3699,11,30,'Administrator'),(3700,11,31,'Administrator'),(3701,11,115,'Administrator'),(3702,11,44,'Administrator'),(3703,11,126,'Administrator'),(3704,11,19,'Administrator'),(3705,11,152,'Administrator'),(3706,11,2,'Administrator'),(3707,11,21,'Administrator'),(3708,11,130,'Administrator'),(3709,11,95,'Administrator'),(3710,11,47,'Administrator'),(3711,11,48,'Administrator'),(3712,11,49,'Administrator'),(3713,11,60,'Administrator'),(3714,11,59,'Administrator'),(3715,11,133,'Administrator'),(3716,11,134,'Administrator'),(3717,11,135,'Administrator'),(3718,11,145,'Administrator'),(3719,11,96,'Administrator'),(3720,11,146,'Administrator'),(3721,11,116,'Administrator'),(3722,11,121,'Administrator'),(3723,11,105,'Administrator'),(3724,11,150,'Administrator'),(3725,11,151,'Administrator'),(3726,11,8,'Administrator'),(3727,11,9,'Administrator'),(3728,11,124,'Administrator'),(3729,11,114,'Administrator'),(3730,11,131,'Administrator'),(3731,11,132,'Administrator'),(3732,11,58,'Administrator'),(3733,11,72,'Administrator'),(3734,11,42,'Administrator'),(3735,11,25,'Administrator'),(3736,11,46,'Administrator'),(3737,11,142,'Administrator'),(3738,11,144,'Administrator'),(3739,11,139,'Administrator'),(3740,11,140,'Administrator'),(3741,11,16,'Administrator'),(3742,11,78,'Administrator'),(3743,11,79,'Administrator'),(3744,11,80,'Administrator'),(3745,11,74,'Administrator'),(3746,11,56,'Administrator'),(3747,11,89,'Administrator'),(3748,11,147,'Administrator'),(3749,11,118,'Administrator'),(3750,11,154,'Administrator'),(3751,11,84,'Administrator'),(3752,11,93,'Administrator'),(3753,11,34,'Administrator'),(3754,11,35,'Administrator'),(3755,11,36,'Administrator'),(3756,11,85,'Administrator'),(3757,11,37,'Administrator'),(3758,11,41,'Administrator'),(3759,11,119,'Administrator'),(3760,11,117,'Administrator'),(3761,11,39,'Administrator'),(3762,11,40,'Administrator'),(3763,11,88,'Administrator'),(3764,11,148,'Administrator'),(3765,11,77,'Administrator'),(3766,11,122,'Administrator'),(3767,11,127,'Administrator'),(3768,11,128,'Administrator'),(3769,11,129,'Administrator'),(3770,11,153,'Administrator'),(3771,6,7,'Administrator'),(3772,6,1,'Administrator'),(3773,6,17,'Administrator'),(3774,6,26,'Administrator'),(3775,6,137,'Administrator'),(3776,6,27,'Administrator'),(3777,6,28,'Administrator'),(3778,6,29,'Administrator'),(3779,6,30,'Administrator'),(3780,6,31,'Administrator'),(3781,6,115,'Administrator'),(3782,6,44,'Administrator'),(3783,6,126,'Administrator'),(3784,6,19,'Administrator'),(3785,6,152,'Administrator'),(3786,6,2,'Administrator'),(3787,6,21,'Administrator'),(3788,6,130,'Administrator'),(3789,6,95,'Administrator'),(3790,6,47,'Administrator'),(3791,6,48,'Administrator'),(3792,6,49,'Administrator'),(3793,6,60,'Administrator'),(3794,6,59,'Administrator'),(3795,6,133,'Administrator'),(3796,6,134,'Administrator'),(3797,6,135,'Administrator'),(3798,6,145,'Administrator'),(3799,6,96,'Administrator'),(3800,6,146,'Administrator'),(3801,6,116,'Administrator'),(3802,6,121,'Administrator'),(3803,6,105,'Administrator'),(3804,6,150,'Administrator'),(3805,6,151,'Administrator'),(3806,6,8,'Administrator'),(3807,6,9,'Administrator'),(3808,6,124,'Administrator'),(3809,6,114,'Administrator'),(3810,6,131,'Administrator'),(3811,6,132,'Administrator'),(3812,6,58,'Administrator'),(3813,6,72,'Administrator'),(3814,6,42,'Administrator'),(3815,6,25,'Administrator'),(3816,6,46,'Administrator'),(3817,6,142,'Administrator'),(3818,6,144,'Administrator'),(3819,6,139,'Administrator'),(3820,6,140,'Administrator'),(3821,6,16,'Administrator'),(3822,6,78,'Administrator'),(3823,6,79,'Administrator'),(3824,6,80,'Administrator'),(3825,6,74,'Administrator'),(3826,6,56,'Administrator'),(3827,6,89,'Administrator'),(3828,6,147,'Administrator'),(3829,6,118,'Administrator'),(3830,6,154,'Administrator'),(3831,6,84,'Administrator'),(3832,6,93,'Administrator'),(3833,6,34,'Administrator'),(3834,6,35,'Administrator'),(3835,6,36,'Administrator'),(3836,6,85,'Administrator'),(3837,6,37,'Administrator'),(3838,6,41,'Administrator'),(3839,6,119,'Administrator'),(3840,6,117,'Administrator'),(3841,6,39,'Administrator'),(3842,6,40,'Administrator'),(3843,6,88,'Administrator'),(3844,6,148,'Administrator'),(3845,6,77,'Administrator'),(3846,6,122,'Administrator'),(3847,6,127,'Administrator'),(3848,6,128,'Administrator'),(3849,6,129,'Administrator'),(3850,6,153,'Administrator'),(3851,8,7,'Administrator'),(3852,8,1,'Administrator'),(3853,8,17,'Administrator'),(3854,8,26,'Administrator'),(3855,8,137,'Administrator'),(3856,8,27,'Administrator'),(3857,8,28,'Administrator'),(3858,8,29,'Administrator'),(3859,8,30,'Administrator'),(3860,8,31,'Administrator'),(3861,8,115,'Administrator'),(3862,8,44,'Administrator'),(3863,8,126,'Administrator'),(3864,8,19,'Administrator'),(3865,8,152,'Administrator'),(3866,8,2,'Administrator'),(3867,8,21,'Administrator'),(3868,8,130,'Administrator'),(3869,8,95,'Administrator'),(3870,8,47,'Administrator'),(3871,8,48,'Administrator'),(3872,8,49,'Administrator'),(3873,8,60,'Administrator'),(3874,8,59,'Administrator'),(3875,8,133,'Administrator'),(3876,8,134,'Administrator'),(3877,8,135,'Administrator'),(3878,8,145,'Administrator'),(3879,8,96,'Administrator'),(3880,8,146,'Administrator'),(3881,8,116,'Administrator'),(3882,8,121,'Administrator'),(3883,8,105,'Administrator'),(3884,8,150,'Administrator'),(3885,8,151,'Administrator'),(3886,8,8,'Administrator'),(3887,8,9,'Administrator'),(3888,8,124,'Administrator'),(3889,8,114,'Administrator'),(3890,8,131,'Administrator'),(3891,8,132,'Administrator'),(3892,8,58,'Administrator'),(3893,8,72,'Administrator'),(3894,8,42,'Administrator'),(3895,8,25,'Administrator'),(3896,8,46,'Administrator'),(3897,8,142,'Administrator'),(3898,8,144,'Administrator'),(3899,8,139,'Administrator'),(3900,8,140,'Administrator'),(3901,8,16,'Administrator'),(3902,8,78,'Administrator'),(3903,8,79,'Administrator'),(3904,8,80,'Administrator'),(3905,8,74,'Administrator'),(3906,8,56,'Administrator'),(3907,8,89,'Administrator'),(3908,8,147,'Administrator'),(3909,8,118,'Administrator'),(3910,8,154,'Administrator'),(3911,8,84,'Administrator'),(3912,8,93,'Administrator'),(3913,8,34,'Administrator'),(3914,8,35,'Administrator'),(3915,8,36,'Administrator'),(3916,8,85,'Administrator'),(3917,8,37,'Administrator'),(3918,8,41,'Administrator'),(3919,8,119,'Administrator'),(3920,8,117,'Administrator'),(3921,8,39,'Administrator'),(3922,8,40,'Administrator'),(3923,8,88,'Administrator'),(3924,8,148,'Administrator'),(3925,8,77,'Administrator'),(3926,8,122,'Administrator'),(3927,8,127,'Administrator'),(3928,8,128,'Administrator'),(3929,8,129,'Administrator'),(3930,8,153,'Administrator'),(3931,9,7,'Administrator'),(3932,9,1,'Administrator'),(3933,9,17,'Administrator'),(3934,9,26,'Administrator'),(3935,9,137,'Administrator'),(3936,9,27,'Administrator'),(3937,9,28,'Administrator'),(3938,9,29,'Administrator'),(3939,9,30,'Administrator'),(3940,9,31,'Administrator'),(3941,9,115,'Administrator'),(3942,9,44,'Administrator'),(3943,9,126,'Administrator'),(3944,9,19,'Administrator'),(3945,9,152,'Administrator'),(3946,9,2,'Administrator'),(3947,9,21,'Administrator'),(3948,9,130,'Administrator'),(3949,9,95,'Administrator'),(3950,9,47,'Administrator'),(3951,9,48,'Administrator'),(3952,9,49,'Administrator'),(3953,9,60,'Administrator'),(3954,9,59,'Administrator'),(3955,9,133,'Administrator'),(3956,9,134,'Administrator'),(3957,9,135,'Administrator'),(3958,9,145,'Administrator'),(3959,9,96,'Administrator'),(3960,9,146,'Administrator'),(3961,9,116,'Administrator'),(3962,9,121,'Administrator'),(3963,9,105,'Administrator'),(3964,9,150,'Administrator'),(3965,9,151,'Administrator'),(3966,9,8,'Administrator'),(3967,9,9,'Administrator'),(3968,9,124,'Administrator'),(3969,9,114,'Administrator'),(3970,9,131,'Administrator'),(3971,9,132,'Administrator'),(3972,9,58,'Administrator'),(3973,9,72,'Administrator'),(3974,9,42,'Administrator'),(3975,9,25,'Administrator'),(3976,9,46,'Administrator'),(3977,9,142,'Administrator'),(3978,9,144,'Administrator'),(3979,9,139,'Administrator'),(3980,9,140,'Administrator'),(3981,9,16,'Administrator'),(3982,9,78,'Administrator'),(3983,9,79,'Administrator'),(3984,9,80,'Administrator'),(3985,9,74,'Administrator'),(3986,9,56,'Administrator'),(3987,9,89,'Administrator'),(3988,9,147,'Administrator'),(3989,9,118,'Administrator'),(3990,9,154,'Administrator'),(3991,9,84,'Administrator'),(3992,9,93,'Administrator'),(3993,9,34,'Administrator'),(3994,9,35,'Administrator'),(3995,9,36,'Administrator'),(3996,9,85,'Administrator'),(3997,9,37,'Administrator'),(3998,9,41,'Administrator'),(3999,9,119,'Administrator'),(4000,9,117,'Administrator'),(4001,9,39,'Administrator'),(4002,9,40,'Administrator'),(4003,9,88,'Administrator'),(4004,9,148,'Administrator'),(4005,9,77,'Administrator'),(4006,9,122,'Administrator'),(4007,9,127,'Administrator'),(4008,9,128,'Administrator'),(4009,9,129,'Administrator'),(4010,9,153,'Administrator'),(4011,10,7,'Administrator'),(4012,10,1,'Administrator'),(4013,10,17,'Administrator'),(4014,10,26,'Administrator'),(4015,10,137,'Administrator'),(4016,10,27,'Administrator'),(4017,10,28,'Administrator'),(4018,10,29,'Administrator'),(4019,10,30,'Administrator'),(4020,10,31,'Administrator'),(4021,10,115,'Administrator'),(4022,10,44,'Administrator'),(4023,10,126,'Administrator'),(4024,10,19,'Administrator'),(4025,10,152,'Administrator'),(4026,10,2,'Administrator'),(4027,10,21,'Administrator'),(4028,10,130,'Administrator'),(4029,10,95,'Administrator'),(4030,10,47,'Administrator'),(4031,10,48,'Administrator'),(4032,10,49,'Administrator'),(4033,10,60,'Administrator'),(4034,10,59,'Administrator'),(4035,10,133,'Administrator'),(4036,10,134,'Administrator'),(4037,10,135,'Administrator'),(4038,10,145,'Administrator'),(4039,10,96,'Administrator'),(4040,10,146,'Administrator'),(4041,10,116,'Administrator'),(4042,10,121,'Administrator'),(4043,10,105,'Administrator'),(4044,10,150,'Administrator'),(4045,10,151,'Administrator'),(4046,10,8,'Administrator'),(4047,10,9,'Administrator'),(4048,10,124,'Administrator'),(4049,10,114,'Administrator'),(4050,10,131,'Administrator'),(4051,10,132,'Administrator'),(4052,10,58,'Administrator'),(4053,10,72,'Administrator'),(4054,10,42,'Administrator'),(4055,10,25,'Administrator'),(4056,10,46,'Administrator'),(4057,10,142,'Administrator'),(4058,10,144,'Administrator'),(4059,10,139,'Administrator'),(4060,10,140,'Administrator'),(4061,10,16,'Administrator'),(4062,10,78,'Administrator'),(4063,10,79,'Administrator'),(4064,10,80,'Administrator'),(4065,10,74,'Administrator'),(4066,10,56,'Administrator'),(4067,10,89,'Administrator'),(4068,10,147,'Administrator'),(4069,10,118,'Administrator'),(4070,10,154,'Administrator'),(4071,10,84,'Administrator'),(4072,10,93,'Administrator'),(4073,10,34,'Administrator'),(4074,10,35,'Administrator'),(4075,10,36,'Administrator'),(4076,10,85,'Administrator'),(4077,10,37,'Administrator'),(4078,10,41,'Administrator'),(4079,10,119,'Administrator'),(4080,10,117,'Administrator'),(4081,10,39,'Administrator'),(4082,10,40,'Administrator'),(4083,10,88,'Administrator'),(4084,10,148,'Administrator'),(4085,10,77,'Administrator'),(4086,10,122,'Administrator'),(4087,10,127,'Administrator'),(4088,10,128,'Administrator'),(4089,10,129,'Administrator'),(4090,10,153,'Administrator'),(4091,5,7,'ESS'),(4092,5,16,'ESS'),(4093,5,148,'ESS'),(4094,5,122,'ESS'),(4095,5,127,'ESS'),(4096,5,129,'ESS'),(4097,5,153,'ESS'),(4098,7,7,'ESS'),(4099,7,16,'ESS'),(4100,7,148,'ESS'),(4101,7,122,'ESS'),(4102,7,127,'ESS'),(4103,7,129,'ESS'),(4104,7,153,'ESS'),(4105,4,7,'ESS'),(4106,4,16,'ESS'),(4107,4,148,'ESS'),(4108,4,122,'ESS'),(4109,4,127,'ESS'),(4110,4,129,'ESS'),(4111,4,153,'ESS'),(4112,12,7,'ESS'),(4113,12,16,'ESS'),(4114,12,148,'ESS'),(4115,12,122,'ESS'),(4116,12,127,'ESS'),(4117,12,129,'ESS'),(4118,12,153,'ESS'),(4119,2,7,'ESS'),(4120,2,16,'ESS'),(4121,2,148,'ESS'),(4122,2,122,'ESS'),(4123,2,127,'ESS'),(4124,2,129,'ESS'),(4125,2,153,'ESS'),(4126,3,7,'ESS'),(4127,3,16,'ESS'),(4128,3,148,'ESS'),(4129,3,122,'ESS'),(4130,3,127,'ESS'),(4131,3,129,'ESS'),(4132,3,153,'ESS'),(4133,1,7,'ESS'),(4134,1,16,'ESS'),(4135,1,148,'ESS'),(4136,1,122,'ESS'),(4137,1,127,'ESS'),(4138,1,129,'ESS'),(4139,1,153,'ESS'),(4140,11,7,'ESS'),(4141,11,16,'ESS'),(4142,11,148,'ESS'),(4143,11,122,'ESS'),(4144,11,127,'ESS'),(4145,11,129,'ESS'),(4146,11,153,'ESS'),(4147,6,7,'ESS'),(4148,6,16,'ESS'),(4149,6,148,'ESS'),(4150,6,122,'ESS'),(4151,6,127,'ESS'),(4152,6,129,'ESS'),(4153,6,153,'ESS'),(4154,8,7,'ESS'),(4155,8,16,'ESS'),(4156,8,148,'ESS'),(4157,8,122,'ESS'),(4158,8,127,'ESS'),(4159,8,129,'ESS'),(4160,8,153,'ESS'),(4161,9,7,'ESS'),(4162,9,16,'ESS'),(4163,9,148,'ESS'),(4164,9,122,'ESS'),(4165,9,127,'ESS'),(4166,9,129,'ESS'),(4167,9,153,'ESS'),(4168,10,7,'ESS'),(4169,10,16,'ESS'),(4170,10,148,'ESS'),(4171,10,122,'ESS'),(4172,10,127,'ESS'),(4173,10,129,'ESS'),(4174,10,153,'ESS');
/*!40000 ALTER TABLE `app_userstypeaccess` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_usertype`
--

DROP TABLE IF EXISTS `app_usertype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_usertype` (
  `user_type` varchar(64) COLLATE latin1_general_ci NOT NULL,
  `user_type_name` varchar(64) COLLATE latin1_general_ci NOT NULL,
  `user_type_ord` int(11) NOT NULL,
  `user_type_status` tinyint(2) NOT NULL DEFAULT '1',
  `user_type_access` text COLLATE latin1_general_ci,
  `user_type_dept` text COLLATE latin1_general_ci,
  PRIMARY KEY (`user_type`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_usertype`
--

LOCK TABLES `app_usertype` WRITE;
/*!40000 ALTER TABLE `app_usertype` DISABLE KEYS */;
INSERT INTO `app_usertype` VALUES ('ADMIN MASTER','ADMIN MASTER',50,1,'a:3:{i:144;s:3:\"144\";i:145;s:3:\"145\";i:152;s:3:\"152\";}','a:28:{i:0;s:2:\"13\";i:1;s:1:\"5\";i:2;s:2:\"10\";i:3;s:2:\"27\";i:4;s:2:\"11\";i:5;s:1:\"4\";i:6;s:1:\"1\";i:7;s:1:\"9\";i:8;s:1:\"7\";i:9;s:2:\"14\";i:10;s:2:\"19\";i:11;s:2:\"20\";i:12;s:2:\"16\";i:13;s:2:\"26\";i:14;s:2:\"12\";i:15;s:2:\"28\";i:16;s:2:\"24\";i:17;s:1:\"8\";i:18;s:2:\"25\";i:19;s:1:\"2\";i:20;s:1:\"6\";i:21;s:2:\"17\";i:22;s:2:\"23\";i:23;s:2:\"15\";i:24;s:2:\"21\";i:25;s:2:\"18\";i:26;s:1:\"3\";i:27;s:2:\"22\";}'),('Administrator','Administrator',20,1,'a:3:{i:144;s:3:\"144\";i:145;s:3:\"145\";i:152;s:3:\"152\";}','a:28:{i:0;s:2:\"13\";i:1;s:1:\"5\";i:2;s:2:\"10\";i:3;s:2:\"27\";i:4;s:2:\"11\";i:5;s:1:\"4\";i:6;s:1:\"1\";i:7;s:1:\"9\";i:8;s:1:\"7\";i:9;s:2:\"14\";i:10;s:2:\"19\";i:11;s:2:\"20\";i:12;s:2:\"16\";i:13;s:2:\"26\";i:14;s:2:\"12\";i:15;s:2:\"28\";i:16;s:2:\"24\";i:17;s:1:\"8\";i:18;s:2:\"25\";i:19;s:1:\"2\";i:20;s:1:\"6\";i:21;s:2:\"17\";i:22;s:2:\"23\";i:23;s:2:\"15\";i:24;s:2:\"21\";i:25;s:2:\"18\";i:26;s:1:\"3\";i:27;s:2:\"22\";}'),('ESS','Employee Self Service',100,1,'a:3:{i:144;s:3:\"144\";i:145;s:3:\"145\";i:152;s:3:\"152\";}','a:28:{i:0;s:2:\"13\";i:1;s:1:\"5\";i:2;s:2:\"10\";i:3;s:2:\"27\";i:4;s:2:\"11\";i:5;s:1:\"4\";i:6;s:1:\"1\";i:7;s:1:\"9\";i:8;s:1:\"7\";i:9;s:2:\"14\";i:10;s:2:\"19\";i:11;s:2:\"20\";i:12;s:2:\"16\";i:13;s:2:\"26\";i:14;s:2:\"12\";i:15;s:2:\"28\";i:16;s:2:\"24\";i:17;s:1:\"8\";i:18;s:2:\"25\";i:19;s:1:\"2\";i:20;s:1:\"6\";i:21;s:2:\"17\";i:22;s:2:\"23\";i:23;s:2:\"15\";i:24;s:2:\"21\";i:25;s:2:\"18\";i:26;s:1:\"3\";i:27;s:2:\"22\";}'),('HR MASTER','HR MASTER',30,1,'a:3:{i:144;s:3:\"144\";i:145;s:3:\"145\";i:152;s:3:\"152\";}','a:28:{i:0;s:2:\"13\";i:1;s:1:\"5\";i:2;s:2:\"10\";i:3;s:2:\"27\";i:4;s:2:\"11\";i:5;s:1:\"4\";i:6;s:1:\"1\";i:7;s:1:\"9\";i:8;s:1:\"7\";i:9;s:2:\"14\";i:10;s:2:\"19\";i:11;s:2:\"20\";i:12;s:2:\"16\";i:13;s:2:\"26\";i:14;s:2:\"12\";i:15;s:2:\"28\";i:16;s:2:\"24\";i:17;s:1:\"8\";i:18;s:2:\"25\";i:19;s:1:\"2\";i:20;s:1:\"6\";i:21;s:2:\"17\";i:22;s:2:\"23\";i:23;s:2:\"15\";i:24;s:2:\"21\";i:25;s:2:\"18\";i:26;s:1:\"3\";i:27;s:2:\"22\";}'),('LOAN MASTER','LOAN MASTER',40,1,'a:3:{i:144;s:3:\"144\";i:145;s:3:\"145\";i:152;s:3:\"152\";}','a:28:{i:0;s:2:\"13\";i:1;s:1:\"5\";i:2;s:2:\"10\";i:3;s:2:\"27\";i:4;s:2:\"11\";i:5;s:1:\"4\";i:6;s:1:\"1\";i:7;s:1:\"9\";i:8;s:1:\"7\";i:9;s:2:\"14\";i:10;s:2:\"19\";i:11;s:2:\"20\";i:12;s:2:\"16\";i:13;s:2:\"26\";i:14;s:2:\"12\";i:15;s:2:\"28\";i:16;s:2:\"24\";i:17;s:1:\"8\";i:18;s:2:\"25\";i:19;s:1:\"2\";i:20;s:1:\"6\";i:21;s:2:\"17\";i:22;s:2:\"23\";i:23;s:2:\"15\";i:24;s:2:\"21\";i:25;s:2:\"18\";i:26;s:1:\"3\";i:27;s:2:\"22\";}'),('Payroll Master','Payroll Master',20,1,'a:3:{i:144;s:3:\"144\";i:145;s:3:\"145\";i:152;s:3:\"152\";}','a:28:{i:0;s:2:\"13\";i:1;s:1:\"5\";i:2;s:2:\"10\";i:3;s:2:\"27\";i:4;s:2:\"11\";i:5;s:1:\"4\";i:6;s:1:\"1\";i:7;s:1:\"9\";i:8;s:1:\"7\";i:9;s:2:\"14\";i:10;s:2:\"19\";i:11;s:2:\"20\";i:12;s:2:\"16\";i:13;s:2:\"26\";i:14;s:2:\"12\";i:15;s:2:\"28\";i:16;s:2:\"24\";i:17;s:1:\"8\";i:18;s:2:\"25\";i:19;s:1:\"2\";i:20;s:1:\"6\";i:21;s:2:\"17\";i:22;s:2:\"23\";i:23;s:2:\"15\";i:24;s:2:\"21\";i:25;s:2:\"18\";i:26;s:1:\"3\";i:27;s:2:\"22\";}'),('Super Administrator','Super Administrator',10,1,'a:108:{i:7;s:1:\"7\";i:1;s:1:\"1\";i:3;s:1:\"3\";i:17;s:2:\"17\";i:19;s:2:\"19\";i:4;s:1:\"4\";i:2;s:1:\"2\";i:20;s:2:\"20\";i:10;s:2:\"10\";i:100;s:3:\"100\";i:5;s:1:\"5\";i:12;s:2:\"12\";i:65;s:2:\"65\";i:107;s:3:\"107\";i:13;s:2:\"13\";i:14;s:2:\"14\";i:15;s:2:\"15\";i:32;s:2:\"32\";i:43;s:2:\"43\";i:45;s:2:\"45\";i:73;s:2:\"73\";i:66;s:2:\"66\";i:51;s:2:\"51\";i:52;s:2:\"52\";i:53;s:2:\"53\";i:54;s:2:\"54\";i:55;s:2:\"55\";i:87;s:2:\"87\";i:26;s:2:\"26\";i:137;s:3:\"137\";i:27;s:2:\"27\";i:28;s:2:\"28\";i:29;s:2:\"29\";i:30;s:2:\"30\";i:31;s:2:\"31\";i:44;s:2:\"44\";i:115;s:3:\"115\";i:126;s:3:\"126\";i:21;s:2:\"21\";i:130;s:3:\"130\";i:95;s:2:\"95\";i:47;s:2:\"47\";i:48;s:2:\"48\";i:49;s:2:\"49\";i:60;s:2:\"60\";i:59;s:2:\"59\";i:81;s:2:\"81\";i:82;s:2:\"82\";i:83;s:2:\"83\";i:91;s:2:\"91\";i:113;s:3:\"113\";i:133;s:3:\"133\";i:134;s:3:\"134\";i:135;s:3:\"135\";i:136;s:3:\"136\";i:145;s:3:\"145\";i:96;s:2:\"96\";i:146;s:3:\"146\";i:116;s:3:\"116\";i:121;s:3:\"121\";i:105;s:3:\"105\";i:8;s:1:\"8\";i:64;s:2:\"64\";i:9;s:1:\"9\";i:124;s:3:\"124\";i:114;s:3:\"114\";i:131;s:3:\"131\";i:132;s:3:\"132\";i:58;s:2:\"58\";i:138;s:3:\"138\";i:72;s:2:\"72\";i:42;s:2:\"42\";i:25;s:2:\"25\";i:46;s:2:\"46\";i:142;s:3:\"142\";i:143;s:3:\"143\";i:144;s:3:\"144\";i:139;s:3:\"139\";i:140;s:3:\"140\";i:141;s:3:\"141\";i:16;s:2:\"16\";i:78;s:2:\"78\";i:79;s:2:\"79\";i:80;s:2:\"80\";i:74;s:2:\"74\";i:56;s:2:\"56\";i:89;s:2:\"89\";i:147;s:3:\"147\";i:118;s:3:\"118\";i:84;s:2:\"84\";i:93;s:2:\"93\";i:34;s:2:\"34\";i:35;s:2:\"35\";i:36;s:2:\"36\";i:85;s:2:\"85\";i:37;s:2:\"37\";i:41;s:2:\"41\";i:119;s:3:\"119\";i:117;s:3:\"117\";i:39;s:2:\"39\";i:40;s:2:\"40\";i:120;s:3:\"120\";i:88;s:2:\"88\";i:77;s:2:\"77\";i:122;s:3:\"122\";i:127;s:3:\"127\";i:128;s:3:\"128\";i:129;s:3:\"129\";}','a:4:{i:0;s:1:\"5\";i:1;s:1:\"8\";i:2;s:1:\"9\";i:3;s:1:\"1\";}');
/*!40000 ALTER TABLE `app_usertype` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_wagerate`
--

DROP TABLE IF EXISTS `app_wagerate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_wagerate` (
  `wrate_id` int(10) NOT NULL AUTO_INCREMENT,
  `region_id` int(10) NOT NULL,
  `wrate_name` varchar(225) DEFAULT NULL,
  `wrate_sec_ind` varchar(225) DEFAULT NULL,
  `wrate_minwagerate` decimal(12,5) DEFAULT NULL,
  `wrate_addwho` varchar(50) DEFAULT NULL,
  `wrate_addwhen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `wrate_updatewho` varchar(50) DEFAULT NULL,
  `wrate_updatewhen` datetime DEFAULT NULL,
  PRIMARY KEY (`wrate_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_wagerate`
--

LOCK TABLES `app_wagerate` WRITE;
/*!40000 ALTER TABLE `app_wagerate` DISABLE KEYS */;
INSERT INTO `app_wagerate` VALUES (1,16,'NCR','Non Agri',466.00000,'admin','2012-11-09 02:59:18','Paulene','2013-10-22 22:34:34');
/*!40000 ALTER TABLE `app_wagerate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_zipcodes`
--

DROP TABLE IF EXISTS `app_zipcodes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_zipcodes` (
  `zipcode_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `p_id` int(11) unsigned NOT NULL DEFAULT '0',
  `zipcode_name` varchar(100) DEFAULT NULL,
  `zipcode` varchar(10) DEFAULT NULL,
  `zipcode_addwho` varchar(50) DEFAULT NULL,
  `zipcode_addwhen` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `zipcode_updatewho` varchar(50) DEFAULT NULL,
  `zipcode_updatewhen` datetime DEFAULT NULL,
  PRIMARY KEY (`zipcode_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1878 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_zipcodes`
--

LOCK TABLES `app_zipcodes` WRITE;
/*!40000 ALTER TABLE `app_zipcodes` DISABLE KEYS */;
INSERT INTO `app_zipcodes` VALUES (1,60,'Boliney','2815','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(2,60,'Bangued','2800','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(6,60,'Bucloc','2817','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(7,60,'Daguioman','2816','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(8,60,'Danglas','2825','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(9,60,'Dolores','2801','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(10,60,'Lacub','2821','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(11,60,'Lagangilang','2802','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(12,60,'Lagayan','2824','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(13,60,'Langiden','2807','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(14,60,'La Paz','2826','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(15,60,'Licuan (Baay)','2819','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(16,60,'Luba','2813','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(17,60,'Malibcong','2820','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(18,60,'Manabo','2810','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(20,60,'Pe?arrubia','2804','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(21,60,'Pidigan','2806','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(22,60,'Pilar','2812','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(23,60,'Sallapadan','2818','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(24,60,'San Isidro','2809','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(25,60,'San Juan','2823','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(26,60,'San Quintin','2808','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(27,60,'Tayum','2803','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(28,60,'Tineg','2822','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(29,60,'Tubo','2814','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(30,60,'Villaviciosa','2811','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(31,60,'Bucay','2805','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(32,31,'Buenavista','8601','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(33,31,'Butuan City','8600','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(34,31,'Cabadbaran','8605','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(35,31,'Carmen','8603','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(36,31,'Jabonga','8607','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(37,31,'Kitcharao','8609','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(38,31,'Las Nieves','8610','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(39,31,'Magallanes','8604','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(40,31,'Nasipit','8602','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(41,31,'Remedios T. Romualdez','8611','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(42,31,'Santiago','8608','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(43,31,'Tubay','8606','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(44,55,'Bayugan','8502','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(45,55,'Bunawan','8506','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(46,55,'Esperanza','8513','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(47,55,'La Paz','8508','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(48,55,'Loreto','8507','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(49,55,'Prosperidad','8500','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(50,55,'Rosario','8504','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(51,55,'San Francisco','8501','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(52,55,'San Luis','8511','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(53,55,'Santa Josefa','8512','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(54,55,'Sibagat','8503','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(55,55,'Talacogon','8510','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(56,55,'Trento','8505','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(57,55,'Veruela','8509','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(58,63,'Altavas','5616','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(59,63,'Balete','5614','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(60,63,'Banga','5601','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(61,63,'Batan','5615','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(62,63,'Buruanga','5609','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(63,63,'Ibajay','5613','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(64,63,'Kalibo','5600','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(65,63,'Lezo','5605','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(66,63,'Libacao','5602','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(67,63,'Madalag','5603','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(68,63,'Makato','5611','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(69,63,'Malay','5608','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(70,63,'Malinao','5606','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(71,63,'Nabas','5607','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(72,63,'New Washington','5610','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(73,63,'Numancia','5604','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(74,63,'Tangalan','5612','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(75,46,'Bacacay','4509','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(76,46,' Camalig','4502','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(77,46,'Daraga (Locsin)','4501','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(78,46,'Guinobatan','4503','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(79,46,'Jovellar','4515','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(80,46,'Legazpi City','4500','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(81,46,'Libon','4507','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(82,46,'Ligao City','4504','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(83,46,' Malilipot','4510','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(84,46,'Malinao','4512','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(85,46,' Manito','4514','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(86,46,'Oas','4505','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(87,46,'Pio Duran (Malacbalac)','4516','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(88,46,'Polangui','4506','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(89,46,'Rapu-Rapu','4517','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(90,46,' Santo Domingo','4508','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(91,46,'Tabaco City','4511','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(92,46,'Tiwi','4513','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(93,59,'Manila Central Post Office','1000','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(94,59,'Quiapo','1001','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(95,59,'Intramuros','1002','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(96,59,'Santa Cruz South','1003','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(97,59,'Malate','1004','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(98,59,'San Miguel','1005','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(99,59,'Binondo','1006','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(100,59,'Paco','1007','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(101,59,'Sampaloc East','1008','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(102,59,'Santa Ana','1009','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(103,59,'San Nicolas','1010','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(104,88,'Anini-y','5717','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(105,59,'Pandacan','1011','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(106,88,'Barbaza','5706','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(107,59,'Tondo South','1012','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(108,88,'Belison','5701','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(109,59,'Tondo North','1013','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(110,88,'Bugasong','5704','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(111,88,'Caluya','5711','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(112,88,'Hamtic','5715','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(113,59,'Not used','1014','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(114,59,'Sampaloc West','1015','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(115,59,'Santa Mesa','1016','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(116,59,'San Andres','1017','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(117,88,'Laua-an','5705','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(118,59,'Port Area South','1018','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(119,88,'Libertad','5710','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(120,30,'Marikina Central Post Office','1800','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(121,88,'Pandan','5712','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(122,30,'San Roque-Calumpang','1801','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(123,88,'Patnongon','5702','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(124,30,'Industrial Valley','1802','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(125,88,'San Jose','5700','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(126,30,'Barangka and Ta?ong','1803','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(127,88,' San Remigio','5714','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(128,88,'Sebaste','5709','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(129,30,'J. De La Pe?a','1804','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(130,88,'Culasi','5708','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(131,88,'Sibalom','5713','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(132,88,'Tibiao','5707','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(133,88,'Tobias Fornier (Dao)','5716','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(134,88,'Valderrama','5703','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(135,13,'Baler','3200','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(136,13,'Casiguran','3204','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(137,13,'Dilasag','3205','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(138,13,'Dinalungan','3206','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(139,13,'Dingalan','3207','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(140,13,'Dipaculao','3203','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(141,13,'Maria Aurora','3202','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(142,13,' San Luis','3201','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(143,30,'Malanday','1805','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(144,30,'Northern and Western Marikina River','1806','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(145,30,'Concepcion 1','1807','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(146,30,'Nangka','1808','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(147,95,'Isabela de Basilan/Isabela City','7300','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(148,30,' Parang','1809','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(149,95,'Lamitan','7302','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(150,30,'Marikina Heights','1810','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(151,30,'Concepcion 2','1811','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(152,95,'Lantawan','7301','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(153,95,'Maluso','7303','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(154,95,'Sumisip','7305','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(155,95,'Tipo-Tipo','7304','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(156,35,'Boac','4900','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(157,35,'Buenavista','4904','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(158,95,'Tuburan','7306','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(159,71,'Abucay','2114','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(160,71,'Bagac','2107','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(161,71,'Balanga City','2100','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(162,71,' Mariveles (Bataan Export Processing Zone)','2106','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(163,71,'Dinalupihan','2110','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(164,71,'Hermosa','2111','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(165,71,'Lamao','2104','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(166,35,'Gasan','4905','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(167,71,'Limay','2103','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(168,71,'Mariveles','2105','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(169,35,'Mogpog','4901','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(170,35,'Santa Cruz','4902','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(171,71,'Morong','2108','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(172,35,'Torrijos','4903','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(173,87,'Aroroy','5414','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(174,71,'Orani','2112','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(175,87,'Baleno','5413','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(176,71,'Orion','2102','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(177,87,' Balud','5412','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(178,71,'Pilar','2101','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(179,87,'Batuan','5415','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(180,87,'Buenavista','5421','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(181,87,'Cataingan','5405','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(182,87,'Cawayan','5409','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(183,87,'Claveria','5419','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(184,87,'Dimasalang','5403','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(185,87,'Esperanza','5407','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(186,87,'Mandaon','5411','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(187,87,' Masbate','5400','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(188,87,'Milagros','5410','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(189,87,'Mobo','5401','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(190,87,'Monreal','5418','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(191,87,'Palanas','5404','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(192,87,'Pio V. Corpuz','5406','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(193,87,'Placer','5408','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(194,87,'San Fernando','5416','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(195,87,' San Jacinto','5417','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(196,87,'San Pascual','5420','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(197,87,' Uson','5402','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(198,79,'Aloran','7206','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(199,79,'Bonifacio','7215','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(200,79,'Calamba','7210','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(201,79,'Clarin','7201','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(202,79,'Concepcion','7213','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(203,79,'Jimenez','7204','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(204,79,' Lopez Jaena','7208','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(205,79,'Oroquieta City','7207','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(206,79,' Ozamis City','7200','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(207,79,' Panaon','7205','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(208,79,'Plaridel','7209','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(209,79,'Sapang Dalaga','7212','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(210,79,'Sinacaban','7203','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(211,79,'Tangub City','7214','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(212,79,'Tudela','7202','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(213,38,'Alubijid','9018','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(214,38,' Balingasag','9005','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(215,38,'Balinguan','9011','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(216,38,'Binuangan','9008','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(217,38,'Cagayan de Oro City','9000','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(218,71,'Morong (Refugee Processing Center)','2109','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(219,38,'Claveria','9004','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(220,38,'El Salvador','9017','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(221,38,'Gingoog City','9014','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(222,38,'Gitagum','9020','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(223,38,'Initao','9022','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(224,38,'Jasaan','9003','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(225,38,'Kinogitan','9010','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(226,38,'Lagonglong','9006','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(227,71,'Samal','2113','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(228,38,'Laguindingan','9019','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(229,38,'Libertad','9021','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(230,38,'Lugait','9025','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(231,38,'Magsaysay','9015','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(232,42,'Governor-General Jose Vargas Basco','3900','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(233,38,'Manticao','9024','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(234,38,'Medina','9013','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(235,38,'Naawan','9023','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(236,38,'Opol','9016','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(237,42,'Itbayat','3905','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(238,38,'Salay','9007','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(239,38,'Sugbungcogon','9009','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(240,38,'Tagoloan','9001','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(241,38,'Talisayan','9012','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(242,38,'Villanueva','9002','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(243,51,'Barlig','2623','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(244,51,'Bauko','2621','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(245,51,'Besao','2618','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(246,51,' Bontoc','2616','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(247,51,'Natonin','2624','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(248,51,'Paracelis','2625','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(249,51,'Sabangan','2622','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(250,51,'Sadanga','2617','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(251,51,'Sagada','2619','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(252,51,'Tadian','2620','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(253,17,'Muntinlupa CPO','1770','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(254,17,'Bule/Cupang','1771','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(255,42,'Ivana','3902','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(256,17,'Bayanan/Putatan','1772','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(257,42,'Mahatao','3901','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(258,17,'Tunasan','1773','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(259,42,'Sabtang','3904','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(260,17,'Susana Heights','1774','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(261,42,'Uyugan','3903','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(262,17,'Pearl Heights','1775','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(263,17,'Poblacion','1776','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(264,17,'Pleasant Village','1777','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(265,92,'Agoncillo','4211','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(266,17,'Ayala Alabang Subdivision','1780','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(267,17,'Ayala Alabang P.O. Boxes','1799','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(268,4,'Fish Market','1411','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(269,92,'Alitagtag','4205','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(270,4,'Isla de Cocomo','1412','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(271,92,'Balayan','4213','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(272,4,'Kapitbahayan [East]','1413','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(273,92,'Balete','4219','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(274,4,'Kaunlaran Village','1409','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(275,92,' Batangas City','4200','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(276,4,'Navotas','1485','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(277,92,'Bauan','4201','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(278,4,'Tangos','1489','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(279,4,'Tanza','1490','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(280,98,' Bacolod City','6100','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(281,92,'Calaca','4212','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(282,98,'Bago City','6101','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(283,98,'Binalbagan','6107','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(284,98,'Cadiz City','6121','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(285,92,'Calatagan, incl. Punta Baluarte','4215','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(286,98,'Calatrava','6126','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(287,98,'Candoni','6110','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(288,92,' Cuenca','4222','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(289,98,'Enrique Magalona','6118','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(290,92,' Fernando Airbase','4218','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(291,98,'Escalante','6124','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(292,92,'Ibaan','4230','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(293,98,'Himamaylan','6108','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(294,98,' Hinigaran','6106','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(295,98,'Hinoba-an','6114','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(296,98,' Ilog','6109','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(297,98,'Isabela','6128','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(298,98,'Kabangkalan','6111','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(299,98,'Kauayan','6112','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(300,98,'La Carlota City','6130','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(301,92,'Laurel, incl. Diokno Highway','4221','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(302,98,'La Castillana','6131','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(303,98,'Manapla','6120','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(304,98,'Moises Padilla','6132','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(305,98,'Murcia','6129','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(306,98,'Paraiso (Fabrica)','6123','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(307,92,'Lemery','4209','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(308,98,'Pontevedra','6105','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(309,98,'Pulupandan','6102','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(310,92,'Lian, incl. Matabungkay','4216','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(311,98,'Sagay','6122','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(312,98,'San Carlos City','6127','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(313,92,'Lipa City','4217','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(314,92,' Lobo, incl. Laiya','4229','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(315,92,'Mabini','4202','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(316,92,'Malvar','4233','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(317,98,'San Enrique','6104','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(318,98,' Silay City','6116','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(319,98,'Silay Hawaiian Central','6117','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(320,98,'Sipalay','6113','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(321,98,'Talisay','6115','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(322,98,'Toboso','6125','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(323,98,'Valladolid','6103','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(324,98,'Victorias','6119','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(325,15,'Amlan','6203','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(326,15,'Ayungon','6210','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(327,15,'Bacung','6216','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(328,15,'Bais City','6206','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(329,15,'Basay','6222','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(330,15,'Bayawan','6221','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(331,15,'Bindoy','6209','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(332,15,'Canlaon City','6223','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(333,15,'Duin','6217','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(334,15,'Dumaguete City','6200','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(335,15,'Guihulngan','6214','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(336,15,'Jimalalud','6212','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(337,15,'La Libertad','6213','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(338,15,'Mabinay','6207','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(339,15,'Manjuyod','6208','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(340,15,'Pamplona','6205','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(341,92,'Mataas na Kahoy','4223','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(342,15,'San Jose','6202','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(343,92,'Nasugbu, incl. Punta Fuego and Calaruega','4231','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(344,15,'Siaton','6219','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(345,15,'Sibulan','6201','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(346,92,'Padre Garcia','4224','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(347,15,'Sta. Catalina','6220','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(348,15,'Tanjay','6204','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(349,92,'Rosario','4225','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(350,15,'Tayasan','6211','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(351,15,'Valencia','6215','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(352,15,'Valle Hermoso','6224','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(353,15,'Zamboanguita','6218','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(354,92,'San Jose','4227','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(355,92,'San Juan','4226','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(356,92,'San Luis','4210','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(357,92,'San Nicolas','4207','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(358,92,'San Pascual','4204','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(359,92,'Santa Teresita','4206','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(360,92,'Santo Tomas','4234','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(361,92,'Taal','4208','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(362,82,'Allen','6405','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(363,92,'Talisay City','4220','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(364,82,'Biri','6410','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(365,92,'Tanauan City','4232','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(366,82,'Bobon','6401','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(367,92,'Taysan','4228','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(368,82,'Capul','6408','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(369,92,'Tingloy','4203','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(370,82,'Catarman','6400','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(371,92,'Tuy','4214','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(372,82,'Catubig','6418','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(373,82,'Gamay','6422','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(374,3,'Atok','2612','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(375,82,'La Navas','6420','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(376,82,'Laoang','6411','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(377,3,'Baguio City','2600','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(378,82,'Lapineg','6423','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(379,3,'Bakun','2610','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(380,82,'Lavezares','6404','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(381,3,'Bokod','2605','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(382,82,'Lope De Vega','6403','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(383,3,'Buguias','2607','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(384,3,'Itogon','2604','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(385,82,'Mapanas','6412','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(386,82,'Mondragon','6417','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(387,3,'Kabayan','2606','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(388,82,'Palapag','6421','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(389,3,'Kapangan','2613','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(390,82,' Pambujan','6413','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(391,3,'Kibungan','2611','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(392,82,'Rosario','6416','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(393,82,'San Antonio','6407','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(394,3,'La Trinidad, incl. Pico','2601','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(395,82,'San Isidro','6409','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(396,3,'Lepanto','2609','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(397,82,'San Jose','6402','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(398,3,'Mankayan','2608','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(399,82,'San Roque','6415','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(400,82,'San Vicente','6419','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(401,3,'Philippine Military Academy, Baguio City','2602','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(402,3,'Sablan','2614','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(403,82,'Silvino Lubos','6414','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(404,82,'Victoria','6406','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(405,3,'Tuba, incl. Marcos Highway and Kennon Road','2603','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(406,3,'Tublay','2615','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(407,54,'Almeria','6544','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(408,54,'Biliran','6549','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(409,54,'Cabucgayan','6550','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(410,54,'Caibiran','6548','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(411,54,'Culaba','6547','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(412,54,'Kawayan','6545','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(413,54,'Maripipi','6546','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(414,54,'Naval','6543','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(415,50,'Alburquerque','6302','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(416,50,'Alicia','6314','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(417,50,'Anda','6311','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(418,50,'Antequera','6335','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(419,50,'Baclayon','6301','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(420,50,'Balilihan','6342','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(421,50,'Batuan','6318','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(422,50,'Bien Unido','6326','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(423,50,'Bilar','6317','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(424,50,'Buenavista','6333','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(425,50,'Calape','6328','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(426,50,'Candijay','6312','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(427,50,'Pres. Carlos P. Garcia (Pitogo)','6346','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(428,50,'Carmen','6319','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(429,50,'Catigbian','6343','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(430,50,'Clarin','6330','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(431,50,'Corella','6337','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(432,50,'Cortes','6341','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(433,50,'Dagohoy','6322','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(434,50,'Danao','6344','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(435,50,'Dauis','6339','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(436,50,'Dimiao','6305','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(437,50,'Duero','6309','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(438,50,' Garcia Hernandez','6307','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(439,50,'Guindulman','6310','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(440,50,'Inabanga','6332','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(441,50,'Jagna','6308','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(442,50,'Jetafe','6334','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(443,50,'Lila','6304','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(444,50,'Loay','6303','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(445,50,'Loboc','6316','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(446,50,'Loon','6327','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(447,50,'Mabini','6313','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(448,50,'Maribojoc','6336','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(449,50,'Panglao','6340','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(450,50,'Pilar','6321','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(451,50,'Sagbayan','6331','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(452,50,'San Isidro','6345','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(453,50,'San Miguel','6323','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(454,50,'Sevilla','6347','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(455,50,'Sierra Bullones','6320','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(456,50,'Sikatuna','6338','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(457,50,'Tagbilaran City','6300','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(458,50,'Talibon','6325','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(459,50,'Trinidad','6324','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(460,50,'Tubigon','6329','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(461,50,'Ubay','6315','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(462,50,'Valencia','6306','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(463,76,'Aliaga','3111','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(464,76,'Bongabon','3128','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(465,76,'Cabanatuan City','3100','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(466,76,'Cabiao','3107','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(467,76,'Carranglan','3123','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(468,76,'Central Luzon State University','3120','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(469,76,'Cuyapo','3117','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(470,76,'Fort Magsaysay','3130','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(471,76,'Gabaldon','3131','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(472,76,'Gapan City','3105','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(473,76,'General M. Natividad','3125','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(474,76,'General Tinio','3104','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(475,76,'Guimba','3115','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(476,76,'Jaen','3109','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(477,76,'Laur','3129','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(478,76,'Licab','3112','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(479,76,'Llanera','3126','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(480,76,'Lupao','3122','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(481,76,'Mu?oz','3119','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(482,76,'Nampicuan','3116','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(483,76,'Palayan City','3132','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(484,76,'Pantabangan','3124','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(485,76,'Pe?aranda','3103','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(486,76,'Quezon','3113','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(487,76,'Rizal','3127','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(488,76,'San Antonio','3108','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(489,76,'San Isidro','3106','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(490,76,'San Jose City','3121','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(491,76,'San Leonardo','3102','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(492,76,'Santa Rosa','3101','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(493,76,'Santo Domingo','3133','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(494,76,'Talavera','3114','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(495,76,'Talugtog','3118','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(496,76,' Zaragosa','3110','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(497,64,'Alfonso Casta?eda','3714','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(498,64,'Ambaguio','3701','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(499,64,' Aritao','3704','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(500,64,'Bagabag','3711','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(501,64,'Bambang','3702','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(502,64,' Bayombong','3700','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(503,64,'Diadi','3712','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(504,64,'Dupax del Norte','3706','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(505,64,'Dupax del Sur','3707','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(506,64,'Kasibu','3703','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(507,64,'Kayapa','3708','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(508,64,'Quezon','3713','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(509,64,'Solano','3709','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(510,64,'Santa Fe (Imugan)','3705','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(511,64,'Villa Verde (Ibung)','3710','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(512,69,'Abra de Ilog','5108','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(513,69,'Calintaan','5102','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(514,69,'Looc','5111','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(515,97,'Aguho','1620','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(516,69,'Lubang','5109','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(517,97,'Santa Ana','1621','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(518,69,'Magsaysay','5101','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(519,69,'Mamburao','5106','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(520,69,'Paluan','5107','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(521,69,'Rizal','5103','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(522,69,'Sablayan','5104','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(523,69,'San Jose','5100','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(524,69,'Santa Cruz','5105','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(525,69,'Tilik','5110','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(526,49,'Baco','5201','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(527,49,'Bansud','5210','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(528,49,'Bongabon','5211','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(529,49,'Bulalacao','5214','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(530,49,'Calapan','5200','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(531,49,'Gloria','5209','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(532,49,'Mansalay','5213','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(533,49,'Naujan','5204','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(534,49,'Pinamalayan','5208','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(535,49,'Pola','5206','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(536,49,'Puerto Galera','5203','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(537,49,'Roxas','5212','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(538,49,'San Teodoro','5202','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(539,49,'Socorro','5207','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(540,49,'Victoria','5205','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(541,90,'Aborlan','5302','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(542,90,'Agutaya','5320','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(543,90,'Araceli','5311','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(544,90,' Balabac','5307','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(545,90,'Bataraza','5306','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(546,90,' Brooke\'s Point','5305','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(547,90,'Busuanga','5317','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(548,90,'Cagayancillo','5321','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(549,90,'Coron','5316','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(550,90,'Culion','5315','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(551,90,'Cuyo','5318','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(552,90,'Dumaran','5310','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(553,90,'El Nido (Baquit)','5313','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(554,90,'Iwahig Penal Colony','5301','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(555,90,'Kalayaan','5322','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(556,90,'Linapacan','5314','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(557,90,'Magsaysay','5319','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(558,90,'Narra (Panacan)','5303','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(559,90,'Puerto Princesa City','5300','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(560,90,'Quezon','5304','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(561,90,'Roxas','5308','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(562,90,'San Vicente','5309','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(563,90,'Taytay','5312','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(564,52,'Angeles City','2009','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(565,52,'Apalit','2016','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(566,52,'Arayat','2012','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(567,52,'Bacolor','2001','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(568,52,'Basa Airbase','2007','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(569,52,'Candaba','2013','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(570,52,'Floridablanca','2006','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(571,52,'Guagua','2003','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(572,52,'Lubao','2005','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(573,52,'Mabalacat','2010','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(574,52,'Macabebe','2018','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(575,37,'Baungon','8707','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(576,37,'Cabanglasan','8723','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(577,37,'Damulog','8721','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(578,37,'Dangcagan','8719','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(579,37,'Don Carlos','8712','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(580,37,'Impasugong','8702','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(581,37,'Kadingilan','8713','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(582,52,'Magalang','2011','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(583,52,'Masantol','2017','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(584,37,'Kalilangan','8718','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(585,52,'Mexico','2021','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(586,37,'Kibawe','8720','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(587,52,'Minalin','2019','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(588,37,'Kitaotao','8716','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(589,52,'Porac','2008','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(590,37,'Lantapan','8722','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(591,52,'City of San Fernando','2000','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(592,37,'Libona','8706','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(593,37,'Malaybalay City','8700','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(594,37,'Malitbog','8704','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(595,37,'Manolo Fortich','8703','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(596,37,'Maramag','8714','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(597,37,'Musuan','8710','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(598,37,'Pangantucan','8717','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(599,52,'San Luis','2014','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(600,52,' San Simon','2015','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(601,52,'Santa Ana','2022','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(602,52,'Santa Rita','2002','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(603,37,'Phillips','8705','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(604,52,'Santo Tomas','2020','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(605,37,'Quezon','8715','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(606,52,'Sasmuan (Sexmoan)','2004','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(607,37,'San Fernando','8711','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(608,37,'Sumilao','8701','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(609,101,'Agno','2408','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(610,37,'Talakag','8708','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(611,37,'Valencia City','8709','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(612,101,'Aguilar','2415','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(613,101,'Alaminos','2404','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(614,65,'Angat','3012','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(615,101,'Alcala','2425','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(616,101,'Anda','2405','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(617,101,'Asingan','2439','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(618,101,'Balungao','2442','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(619,101,'Bani','2407','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(620,101,'Basista','2422','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(621,101,'Bautista','2424','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(622,101,'Bayambang','2423','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(623,101,'Binalonan','2436','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(624,101,' Binmaley','2417','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(625,101,'Bolinao','2406','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(626,101,'Bugallon','2416','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(627,101,'Burgos','2410','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(628,101,'Calasiao','2418','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(629,101,'Dagupan City','2400','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(630,101,'Dasol','2411','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(631,101,'Infanta','2412','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(632,65,'Balagtas (Bigaa)','3016','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(633,101,'Labrador','2402','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(634,101,'Laoac','2437','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(635,65,'Baliuag','3006','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(636,101,'Lingayen','2401','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(637,65,'Bocaue','3018','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(638,101,'Mabini','2409','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(639,65,'Bulacan','3017','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(640,101,'Malasiqui','2421','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(641,65,'Bustos','3007','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(642,101,'Manaoag','2430','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(643,65,'Calumpit','3003','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(644,101,'Mangaldan','2432','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(645,101,'Mangatarem','2413','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(646,65,'Dona Remedios Trinidad','3009','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(647,101,'Mapandan','2429','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(648,65,'Guiguinto','3015','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(649,65,'Hagonoy','3002','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(650,101,'Natividad','2446','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(651,65,'Malolos City','3000','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(652,101,'Pozorrubio','2435','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(653,65,'Marilao','3019','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(654,65,'Meycauayan','3020','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(655,101,'Rosales','2441','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(656,65,'Governor-General Francisco Escudero Norzagaray','3013','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(657,101,'San Carlos City','2420','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(658,65,'Obando','3021','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(659,101,'San Fabian','2433','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(660,65,'Pandi','3014','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(661,101,'San Jacinto','2431','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(662,101,'San Manuel','2438','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(663,65,'Paombong','3001','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(664,101,'San Nicolas','2447','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(665,65,'Plaridel','3004','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(666,101,'San Quintin','2444','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(667,65,'Pulilan (pulong-ilan)','3005','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(668,101,'Santa Barbara','2419','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(669,65,'San Ildefonso','3010','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(670,65,'San Jose del Monte City','3023','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(671,101,'Santa Maria','2440','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(672,65,'San Miguel','3011','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(673,101,'Santo Tomas','2426','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(674,65,'San Rafael','3008','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(675,101,'Sison','2434','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(676,65,'Santa Maria','3022','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(677,101,'Sual','2403','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(678,65,'Sapang Palay, San Jose del Monte City','3024','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(679,101,'Tayug','2445','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(680,20,'Abulug','3517','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(681,20,'Alcala','3507','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(682,20,'Allacapan','3523','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(683,101,'Umingan','2443','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(684,101,'Urbiztondo','2414','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(685,101,'Urdaneta','2428','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(686,101,' Villasis','2427','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(687,25,'Aeropark Subdivision','1714','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(688,25,' Baclaran','1702','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(689,45,'Alicia','1105','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(690,25,'Better Living Subdivision','1711','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(691,20,'Amulung','3505','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(692,20,'Aparri','3515','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(693,45,'Amihan','1102','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(694,25,'BF Homes 1','1720','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(695,45,'Apolonio Samson','1106','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(696,45,'Araneta Center','0810','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(697,25,'BF Homes 2','1718','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(698,25,'Executive Heights Subdivision','1710','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(699,45,'Araneta Center P.O. Boxes','1135','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(700,20,'Baggao','3506','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(701,20,'Ballesteros','3516','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(702,20,'Buguey','3511','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(703,45,'Bagbag','1116','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(704,20,'Calayan','3520','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(705,25,'Ireneville Subdivision I & III','1719','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(706,20,'Camalaniugan','3510','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(707,20,'Claveria','3519','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(708,45,' Bagong Bayan','1110','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(709,20,'Enrile','3501','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(710,20,'Gattaran','3508','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(711,20,'Gonzaga','3513','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(712,20,'Iguig','3504','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(713,20,'Lal-Lo','3509','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(714,20,'Lasam','3524','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(715,20,'Pamplona','3522','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(716,20,'Pe?ablanca','3502','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(717,20,'Piat','3527','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(718,20,'Rizal','3526','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(719,20,'Sanchez-Mira','3518','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(720,20,'Santa Ana','3514','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(721,20,'Santa Praxedes','3521','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(722,20,'Santa Teresita','3512','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(723,20,'Santo Ni?o','3525','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(724,20,'Solana','3503','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(725,20,'Tuao','3528','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(726,20,'Tuguegarao City','3500','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(727,9,'Basud','4608','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(728,9,'Capalonga','4607','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(729,9,'Daet','4600','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(730,9,'Imelda','4610','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(731,9,'Jose Panganiban','4606','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(732,9,'Labo','4604','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(733,9,'Mercedes','4601','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(734,9,'Paracale','4605','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(735,9,'San Vicente','4609','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(736,9,'Santa Elena','4611','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(737,9,'Talisay','4602','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(738,9,'Tula-na-Lupa','4612','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(739,9,'Vinzons','4603','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(740,26,'Baao','Baao','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(741,45,'Bagong Buhay','1109','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(742,45,'Bagong Lipunan','1111','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(743,26,'Baao','4432','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(744,26,'Balatan','4436','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(745,26,'Bato','4435','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(746,26,'Bombon','4404','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(747,26,'Buhi','4433','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(748,26,'Bula','4430','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(749,26,'Cabusao','4406','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(750,26,'Calabanga','4405','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(751,26,'Camaligan','4401','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(752,26,'Canaman','4402','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(753,26,'Caramoan','4429','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(754,26,'Del Gallego','4411','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(755,26,'Gainza','4412','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(756,26,'Garchitorena','4428','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(757,26,'Goa','4422','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(758,26,'Iriga City','4431','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(759,26,'Lagonoy','4425','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(760,26,'Libmanan','4407','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(761,26,'Lupi','4409','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(762,26,'Magarao','4403','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(763,26,'Milaor','4413','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(764,26,'Minalabac','4414','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(765,26,'Nabua','4434','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(766,26,'Naga City','4400','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(767,26,'Ocampo','4419','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(768,26,'Pamplona','4416','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(769,26,'Pasacao','4417','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(770,26,'Pili','4418','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(771,26,'Presentacion','4424','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(772,26,'Ragay','4410','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(773,26,'Sagnay','4421','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(774,26,' San Fernando','4415','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(775,26,'San Jose','4423','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(776,26,'Sipocot','4408','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(777,26,'Siruma','4427','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(778,26,'Tigaon','4420','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(779,26,'Tinambac','4426','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(780,44,'Catarman','9104','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(781,44,'Guinsiliban','9102','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(782,44,'Mahinog','9101','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(783,44,'Mambajao','9100','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(784,44,'Sagay','9103','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(785,39,'Cuartero','5811','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(786,39,'Dao','5810','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(787,39,'Dumalag','5813','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(788,39,'Dumarao','5812','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(789,39,'Ivisan','5805','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(790,39,'Jamindan','5808','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(791,39,'Ma-ayon','5809','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(792,39,'Mambusao','5807','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(793,39,'Panay','5801','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(794,39,'Panitan','5815','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(795,39,'Pilar','5804','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(796,39,'Pontevedra','5802','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(797,39,'President Roxas','5803','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(798,39,'Roxas City','5800','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(800,39,'Sapian','5806','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(801,39,'Sigma','5816','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(802,45,'Bagong Silangan','1119','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(803,39,'Tapaz','5814','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(804,62,'Bagamanoc','4807','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(805,62,'Baras','4803','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(806,62,'Bato','4801','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(807,62,'Caramoran','4808','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(808,62,'Gigmoto','4804','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(809,62,'Pandan','4809','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(810,62,'Panganiban','4806','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(811,62,'San Andres','4810','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(812,62,'San Miguel','4802','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(813,62,'Viga','4805','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(814,62,'Virac','4800','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(815,45,'Balingasa','1115','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(816,45,'Batasan Hills','1126','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(817,25,'Manila Memorial Park','1717','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(818,25,'Marina Subdivision (Reclamation)','1703','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(819,25,'Maywood Village II','1716','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(820,25,'Miramar Subdivision','1712','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(821,25,'Multinational Village','1708','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(822,25,'Ninoy Aquino International Airport','1705','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(823,45,'BF Homes','1120','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(824,25,'Para?aque CPO','1700','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(825,25,'Pulo','1706','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(826,45,'Botocan','1101','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(827,25,'San Antonio Valley 1','1715','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(828,45,'Broadway Center P.O. Boxes','1141','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(829,25,'San Antonio Valley 11 & 12','1707','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(830,25,'Santo Ni?o','1704','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(831,25,'South Admiral Village, Merville Park & Moonwalk Su','1709','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(832,45,'Bureau of Internal Revenue','0820','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(833,25,'Tambo','1701','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(834,45,'Camp Aguinaldo','1110','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(835,45,'Camp Aguinaldo','0802','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(836,45,'Camp Crame','0801','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(837,45,'Capri','1117','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(838,45,'Central','1100','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(839,45,'Claro','1102','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(840,45,'Commission on Audit','0880','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(841,45,'Commonwealth','1121','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(842,25,'United Para?aque Subdivision','1713','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(843,45,'Culiat','1128','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(844,25,'Domestic Airport P.O. - P.O. Box Nos. 1000 to 1099','1375','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(845,25,'Domestic Airport P.O. - P.O. Box Nos. 1100 to 1199','1376','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(846,25,'Domestic Airport P.O. - P.O. Box Nos. 1200 to 1299','1377','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(847,25,'Domestic Airport P.O. - P.O. Box Nos. 1300 to 1399','1378','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(848,45,'Damayan','1104','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(849,25,'Domestic Airport P.O. - P.O. Box Nos. 1400 to 1499','1379','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(850,25,'Domestic Airport P.O. - P.O. Box Nos. 1500 to 1599','1380','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(851,25,'Domestic Airport P.O. - P.O. Box Nos. 1600 to 1699','1381','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(852,47,'Domestic Airport P.O.','1301','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(853,47,'Manila Bay (Reclamation)','1308','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(854,47,'Pasay City CPO - Malibay','1300','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(855,47,'PICC (Reclamation Area)','1307','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(856,45,'Damayan Lagi','1112','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(857,45,'Damong Maliit','1123','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(858,45,'Don Manuel','1113','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(859,45,'Do?a Faustina Subdivision','1125','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(860,47,'San Isidro','1306','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(861,45,'Fairview','1118','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(862,47,' San Jose','1305','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(863,47,'San Rafael','1302','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(864,47,'San Roque','1303','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(865,47,'Santa Clara','1304','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(866,47,'Villamor Airbase','1309','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(867,47,'Pasay City CPO - P.O. Box Nos. 1000 to 1099','1350','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(868,47,'Pasay City CPO - P.O. Box Nos. 1100 to 1199','1351','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(869,45,'Fairview [South]','1122','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(870,47,'Pasay City CPO - P.O. Box Nos. 1200 to 1299','1352','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(871,45,'Gintong Silahis','1114','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(872,47,'Pasay City CPO - P.O. Box Nos. 1300 to 1399','1353','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(873,47,'Pasay City CPO - P.O. Box Nos. 1400 to 1499','1354','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(874,45,'Holy Spirit','1127','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(875,5,'Caniogan','1606','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(876,5,'Green Park','1612','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(877,5,'Kapitolyo','1603','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(878,5,'Manggahan','1611','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(879,45,'Kaligayahan','1124','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(880,5,'Maybunga','1607','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(881,5,'Ortigas P.O.','1605','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(882,5,'Pasig CPO','1600','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(883,5,' Pinagbuhatan','1602','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(884,45,'Kamuning','1103','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(885,5,'Rosario','1609','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(886,5,'San Joaquin','1601','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(887,5,'Santa Lucia','1608','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(888,5,'Santolan','1610','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(889,5,'Ugong','1604','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(890,5,'Pasig Ortigas CTR-PO Box# 1000 to 1099','1650','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(891,5,'Pasig Ortigas CTR-PO Box# 1100 to 1199','1651','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(892,5,'Pasig Ortigas CTR-PO Box# 1200 to 1299','1652','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(893,5,'Pasig Ortigas CTR-PO Box# 1300 to 1399','1653','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(894,5,'Pasig Ortigas CTR-PO Box# 1400 to 1499','1654','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(895,5,'Pasig Ortigas CTR-PO Box# 1500 to 1599','1655','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(896,5,'Pasig Ortigas CTR-PO Box# 1600 to 1699','1656','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(897,5,'Pasig Ortigas CTR-PO Box# 1700 to 1799','1657','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(898,5,'Pasig Ortigas CTR-PO Box# 1800 to 1899','1658','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(899,5,'Pasig Ortigas CTR-PO Box# 1900 to 1999','1659','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(900,5,'Pasig Ortigas CTR-PO Box# 2000 to 2099','1660','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(901,5,'Pasig Ortigas CTR-PO Box# 2100 to 2199','1661','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(902,5,'Pasig Ortigas CTR-PO Box# 2200 to 2299','1662','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(903,5,'Pasig Ortigas CTR-PO Box# 2300 to 2399','1663','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(904,5,'Pasig Ortigas CTR-PO Box# 2400 to 2499','1664','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(905,5,'Pasig Ortigas CTR-PO Box# 2500 to 2599','1665','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(906,5,'Pasig Ortigas CTR-PO Box# 2600 to 2699','1666','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(907,5,'Pasig Ortigas CTR-PO Box# 2700 to 2799','1667','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(908,5,'Pasig Ortigas CTR-PO Box# 2800 to 2899','1668','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(909,5,'Pasig Ortigas CTR-PO Box# 2900 to 2999','1669','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(910,5,'Pasig Ortigas CTR-PO Box# 3000 to 3099','1670','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(911,5,'Pasig Ortigas CTR-PO Box# 3100 to 3199','1671','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(912,5,'Pasig Ortigas CTR-PO Box# 3200 to 3299','1672','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(913,5,'Pasig Ortigas CTR-PO Box# 3300 to 3399','1673','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(914,5,'Pasig Ortigas CTR-PO Box# 3400 to 3499','1674','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(915,5,'Pasig Ortigas CTR-PO Box# 3500 to 3599','1675','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(916,5,'Pasig Ortigas CTR-PO Box# 3600 to 3699','1676','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(917,5,'Pasig Ortigas CTR-PO Box# 3700 to 3799','1677','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(918,5,'Pasig Ortigas CTR-PO Box# 3800 to 3899','1678','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(919,45,'Murphy District P.O. Boxes','1138','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(920,45,'National Irrigation Administration','0830','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(921,5,'Pasig Ortigas CTR-PO Box# 3900 to 3999','1679','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(922,5,'Pasig Ortigas CTR-PO Box# 4000 to 4099','1680','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(923,45,'New Era','1107','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(924,45,'Novaliches P.O. Boxes','1147','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(925,45,'Pansol','1108','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(926,5,'Pasig Ortigas CTR-PO Box# 4100 to 4199','1681','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(927,5,'Pasig Ortigas CTR-PO Box# 4200 to 4299','1682','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(928,5,'Pasig Ortigas CTR-PO Box# 4300 to 4399','1683','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(929,5,'Pasig Ortigas CTR-PO Box# 4400 to 4499','1684','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(930,5,'Pasig Ortigas CTR-PO Box# 4500 to 4599','1685','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(931,45,'Philippine Heart Center','0850','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(932,5,'Pasig Ortigas CTR-PO Box# 4600 to 4699','1686','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(933,45,'Quezon City CPO - P.O. Box Nos. 1000 to 1099','1150','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(934,45,'Quezon City CPO - P.O. Box Nos. 1100 to 1199','1151','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(935,45,'Quezon City CPO - P.O. Box Nos. 1200 to 1299','1152','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(936,45,'Quezon City CPO - P.O. Box Nos. 1300 to 1399','1153','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(937,45,'Quezon City CPO - P.O. Box Nos. 1400 to 1499','1154','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(938,45,'Quezon City CPO - P.O. Box Nos. 1500 to 1599','1155','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(939,45,'Quezon City CPO - P.O. Box Nos. 1600 to 1699','1156','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(940,45,'Quezon City CPO - P.O. Box Nos. 1700 to 1799','1157','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(941,5,'Pasig Ortigas CTR-PO Box# 4700 to 4799','1687','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(942,5,'Pasig Ortigas CTR-PO Box# 4800 to 4899','1688','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(943,5,'Pasig Ortigas CTR-PO Box# 4900 to 4999','1689','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(944,5,'Pasig Ortigas CTR-PO Box# 5000 to 5099','1690','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(945,45,'Quezon City CPO - P.O. Box Nos. 1800 to 1899','1158','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(946,5,'Pasig Ortigas CTR-PO Box# 5100 to 5199','1691','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(947,5,'Pasig Ortigas CTR-PO Box# 5200 to 5299','1692','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(948,5,'Pasig Ortigas CTR-PO Box# 5300 to 5399','1693','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(949,5,'Pasig Ortigas CTR-PO Box# 5400 to 5499','1694','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(950,5,'Pasig Ortigas CTR-PO Box# 5500 to 5599','1695','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(951,5,'Pasig Ortigas CTR-PO Box# 5600 to 5699','1696','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(952,5,'Pasig Ortigas CTR-PO Box# 5700 to 5799','1697','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(953,5,'Pasig Ortigas CTR-PO Box# 5800 to 5899','1698','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(954,5,'Pasig Ortigas CTR-PO Box# 5900 to 5999','1699','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(955,34,'Alfonso','4123','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(956,34,'Amadeo','4119','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(957,34,'Bacoor','4102','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(958,34,'Carmona','4116','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(959,34,'Cavite City','4100','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(960,34,'Sangley Point Naval Base','4101','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(961,34,'Corregidor Island','4125','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(962,34,'Dasmari?as','4114','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(963,34,'Dasmarinas Resettlement Area','4115','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(964,34,'General Emilio Aguinaldo (Bailen)','4124','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(965,34,'General Mariano Alvarez','4117','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(966,34,'General Trias','4107','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(967,34,' Imus','4103','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(968,34,'Indang','4122','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(969,34,'Kawit','4104','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(970,34,'Magallanes','4113','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(971,34,'Maragondon','4112','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(972,34,'Mendez (Mendez-Nu?ez)','4121','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(973,34,'Naic','4110','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(974,34,'Noveleta','4105','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(975,34,'Rosario','4106','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(976,34,'Silang','4118','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(977,34,'Tagaytay City','4120','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(978,34,'Tanza','4108','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(979,34,'Ternate incl. Caylabne Bay & Puerto Azul','4111','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(980,34,' Trece Martires City','4109','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(981,78,'Alcantara','6033','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(982,78,'Alcoy','6023','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(983,78,'Alegria','6030','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(984,78,'Aloguinsan','6040','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(985,78,'Argao','6021','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(986,78,'Asturias','6042','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(987,78,'Balamban','6041','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(988,78,'Bantayan','6052','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(989,78,' Barili','6036','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(990,78,'Bogo','6010','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(991,78,'Boljoon','6024','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(992,78,' Borbon','6008','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(993,78,'Carcar','6019','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(994,78,'Carmen','6005','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(995,78,'Catmon','6006','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(996,45,'Quezon City CPO - P.O. Box Nos. 1900 to 1999','1159','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(997,78,'Cebu City','6000','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(998,45,'Quezon City CPO - P.O. Box Nos. 2000 to 2099','1160','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(999,45,'Quezon City CPO - P.O. Box Nos. 2100 to 2199','1161','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1000,78,'Compostela','6003','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1001,45,'Quezon City CPO - P.O. Box Nos. 2200 to 2299','1162','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1002,78,'Consolacion','6001','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1003,45,'Quezon City CPO - P.O. Box Nos. 2300 to 2399','1163','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1004,78,'Cordova','6017','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1005,45,'Quezon City CPO - P.O. Box Nos. 2400 to 2499\r\n','1164','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1006,45,'Quezon City CPO - P.O. Box Nos. 2500 to 2599','1165','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1007,78,'Daanbantayan','6013','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1008,45,'Quezon City CPO - P.O. Box Nos. 2600 to 2699','1166','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1009,78,'Dalaguete','6022','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1010,78,'Danao City','6004','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1011,78,'Dumanjug','6035','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1012,45,'Quezon City CPO - P.O. Box Nos. 2700 to 2799','1167','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1013,45,'Quezon City CPO - P.O. Box Nos. 2800 to 2899','1168','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1014,45,'Quezon City CPO - P.O. Box Nos. 2900 to 2999','1169','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1015,45,'Quezon City CPO - P.O. Box Nos. 3000 to 3099','1170','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1016,45,'Quezon City CPO - P.O. Box Nos. 3100 to 3199','1171','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1017,45,'Quezon City CPO - P.O. Box Nos. 3200 to 3299','1172','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1018,45,'Quezon City CPO - P.O. Box Nos. 3300 to 3399','1173','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1019,45,'Quezon City CPO - P.O. Box Nos. 3400 to 3499','1174','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1020,45,'Quezon City CPO - P.O. Box Nos. 3500 to 3599','1175','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1021,78,'Ginatilan','6028','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1022,78,'Lapu-Lapu City (Opon)','6015','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1023,78,'Liloan','6002','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1024,78,'Mactan Airport','6016','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1025,78,'Madridejos','6053','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1026,78,' Malabuyoc','6029','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1027,78,'Mandaue City','6014','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1028,78,' Medellin','6012','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1029,78,'Minglanilla','6046','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1030,78,'Moalboal','6032','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1031,78,'Naga','6037','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1032,78,'Oslob','6025','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1033,78,'Pilar',' 6048','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1034,78,'Pinamungahan','6039','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1035,45,'Quezon City CPO - P.O. Box Nos. 3600 to 3699','1176','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1036,45,'Quezon City CPO - P.O. Box Nos. 3700 to 3799','1177','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1037,45,'Quezon City Hall','0860','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1038,78,'Poro','6049','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1039,78,'Ronda','6034','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1040,78,'Samboan','6027','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1041,78,'San Fernando','6018','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1042,78,'San Francisco','6050','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1043,78,'San Remigio','6011','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1044,78,'Santa Fe','6047','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1045,78,' Santander','6026','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1046,78,'Sibonga','6020','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1047,78,'Sogod','6007','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1048,78,'Tabogon','6009','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1049,78,'Tabuelan','6044','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1050,78,'Talisay City','6045','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1051,78,'Toledo City','6038','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1052,78,'Tuburan','6043','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1053,78,'Tudela','6051','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1054,91,'Banga','9511','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1055,91,'General Santos City (Dadiangas)','9500','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1056,91,'Koronadal City','9506','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1057,91,'Norala','9508','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1058,91,'Polomolok','9504','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1059,91,'Santo Ni?o','9509','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1060,91,'Surallah','9512','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1061,91,'Tampakan','9507','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1062,91,'Tantangan','9510','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1063,91,'T\'Boli','9513','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1064,91,'Tupi','9505','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1065,19,'Alamada','9413','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1066,19,'Aleosan','9415','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1067,19,'Antipas','9414','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1068,19,'Arakan','9417','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1069,19,'Banisilan','9416','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1070,19,' Carmen','9408','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1071,45,'Social Security System','0800','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1072,19,'Kabacan','9407','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1073,19,'Kidapawan City','9400','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1074,19,'Libungan','9411','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1075,19,'Magpet','9404','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1076,19,'Makilala','9401','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1077,19,'Matalam','9406','jay','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1078,19,'Midsayap','9410','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1079,19,'M\'Lang','9402','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1080,19,'Pigkawayan','9412','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1081,19,' Pikit','9409','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1082,19,'President Roxas','9405','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1083,19,'Tulunan','9403','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1084,45,'University of the Philippines P.O. Boxes','1144','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1085,45,'V. Luna Hospital','0840','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1086,45,'Veterans Hospital','0870','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1087,45,'Violago Homes','1120','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1088,94,'Agdangan','4304','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1089,94,'Alabat','4333','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1090,94,'Atimonan','4331','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1091,94,'Buenavista','4320','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1092,94,'Burdeos','4340','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1093,94,'Calauag','4318','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1094,94,'Candelaria','4323','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1095,94,'Catanauan','4311','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1096,94,'Dolores','4326','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1097,94,' General Antonio Luna','4310','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1098,94,'General Nakar','4338','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1099,94,'Guinayangan','4319','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1100,94,'Gumaca','4307','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1101,94,'Hondagua','4317','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1102,94,'Infanta','4336','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1103,94,'Jomalig','4342','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1104,94,'Lopez','4316','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1105,94,'Lucban','4328','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1106,94,'Lucena City','4301','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1107,94,'Macalelon','4309','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1108,94,'Mauban','4330','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1109,94,'Mulanay','4312','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1110,94,'Padre Burgos','4303','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1111,94,'Pagbilao','4302','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1112,94,'Panukulan','4337','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1113,94,'Patnanungan','4341','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1114,94,'Perez','4334','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1115,94,'Pitogo','4308','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1116,94,'Plaridel','4306','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1117,94,'Polilio','4339','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1118,94,'Quezon','4332','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1119,94,'Provincial Capitol, Lucena City','4300','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1120,94,'Real','4335','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1121,67,'Asuncion','8102','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1122,94,'Sampaloc','4329','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1123,67,' Babak','8118','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1124,94,'San Andres','4314','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1125,67,'Carmen','8101','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1126,67,'Kapalong','8113','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1127,94,'San Antonio','4324','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1128,67,'Kaputian','8120','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1129,94,'San Francisco (Aurora)','4315','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1130,94,'San Narciso','4313','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1131,94,'Sariaya','4322','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1132,67,'New Corella','8104','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1133,94,'Tagkawayan','4321','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1134,67,'Panabo City','8105','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1135,94,'Tayabas','4327','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1136,67,'Island Garden City of Samal','8119','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1137,94,'Tiaong','4325','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1138,94,'Unisan','4305','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1139,8,'Aglipay','3403','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1140,8,'Cabarruguis','3400','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1141,8,'Diffun','3401','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1142,8,'Maddela','3404','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1143,8,'Nagtipunan (Abbag)','3405','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1144,8,'Saguday','3402','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1145,77,'Angono','1930','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1146,67,'Santo Tomas','8112','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1147,67,' Tagum City','8100','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1148,77,'Antipolo City','1870','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1149,61,'Compostela','8109','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1150,77,'Baras','1970','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1151,77,'Binangonan','1940','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1152,61,'Laak (San Vicente)','8103','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1153,77,'Cainta','1900','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1154,77,'Cardona','1950','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1155,77,'Eulogio Rodriguez (Montalban)','1860','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1156,61,'Mabini (Do?a Alicia)','8115','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1157,77,'Jala-Jala','1990','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1158,61,'Maco','8114','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1159,61,'Maragusan (San Mariano)','8116','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1160,77,'Morong','1960','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1161,61,'Mawab','8108','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1162,61,'Monkayo','8111','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1163,61,'Montevista','8107','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1164,77,'Pililia','1910','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1165,61,' Nabunturan','8106','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1166,61,'New Bataan','8110','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1167,61,'Pantukan','8117','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1168,77,'San Mateo','1850','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1169,77,'Tanay','1980','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1170,11,'Bansalan','8005','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1171,77,'Taytay','1920','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1172,11,'Davao City','8000','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1173,77,'Teresa','1880','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1174,11,'Digos City','8002','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1175,11,'Don Marcelino','8013','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1176,11,'Hagonoy','8006','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1177,11,'Jose Abad Santos',' 8014','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1178,11,'Kiblawan','8008','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1179,11,'Magsaysay','8004','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1180,22,'Alcantara','5509','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1181,11,'Malalag','8010','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1182,11,'Malita','8012','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1183,11,'Matanao',' 8003','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1184,11,'Santa Cruz','8001','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1185,22,'Banton (Jones)','5515','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1186,48,'Baganga','8204','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1187,22,'Cajidiocan','5512','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1188,48,'Banaybanay','8208','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1189,48,'Boston','8206','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1190,48,'Caraga','8203','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1191,22,'Calatrava','5503','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1192,48,'Cateel','8205','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1193,48,'Governor Generoso',' 8210','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1194,22,'Concepcion','5516','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1195,48,'Lopon','8207','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1196,22,'Corcuera','5514','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1197,48,'Manay','8202','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1198,48,'Mati','8200','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1199,22,'Ferrol','5506','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1200,22,'Imelda','5502','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1201,48,'San Isidro','8209','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1202,22,'Looc','5507','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1203,48,'Tarragona','8201','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1204,22,'Magdiwang','5511','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1205,22,'Odiongan','5505','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1206,10,'Arteche','6822','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1207,10,'Balangiga','6812','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1208,10,'Balangkayan','6801','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1209,10,'Borongan','6800','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1210,22,'Romblon','5500','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1211,10,'Can-avid','6806','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1212,10,'Dolores','6817','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1213,22,'San Agustin','5501','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1214,10,'General MacArthur','6805','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1215,10,'Giporlos','6811','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1216,22,'San Andres','5504','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1217,10,'Guiuan','6809','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1218,22,'San Fernando','5513','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1219,22,'San Jose','5510','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1220,10,'Jipapad','6819','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1221,22,'Santa Fe','5508','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1222,10,'Lawaan','6813','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1223,10,'Llorente','6803','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1224,10,'Maslog','6820','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1225,99,'Asian Development Bank','0401','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1226,10,'Maydolong','6802','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1227,99,'Bible School on the Air','0420','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1228,99,'Eisenhower - Crame','1504','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1229,99,'Greenhills P.O.','1502','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1230,99,'Greenhills P.O. Box Nos. 1000 to 1099','1530','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1231,10,'Mercedes','6808','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1232,99,'Greenhills P.O. Box Nos. 1100 to 1199','1531','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1233,10,'Oras','6818','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1234,99,'Greenhills P.O. Box Nos. 1200 to 1299','1532','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1235,10,'Quinapondan','6810','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1236,10,'Salcedo','6807','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1237,99,'Greenhills P.O. Box Nos. 1300 to 1399','1533','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1238,10,'San Julian','6814','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1239,10,'San Policarpo','6821','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1240,10,'Sulat','6815','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1241,10,'Taft','6816','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1242,89,'Buenavista','5044','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1243,89,'Jordan','5045','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1244,99,'Greenhills P.O. Box Nos. 1400 to 1499','1534','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1245,99,'Greenhills P.O. Box Nos. 1500 to 1599','1535','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1246,99,'Greenhills P.O. Box Nos. 1600 to 1699','1536','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1247,89,'Nueva Valencia','5046','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1248,80,'Aguinaldo','3606','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1249,80,'Alfonso Lista (Potia)','3608','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1250,99,'Greenhills [North]','1503','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1251,80,'Asipulo','3610','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1252,80,'Banaue','3601','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1253,99,'International Correspondence School','0400','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1254,80,'Hingyon','3607','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1255,99,'Radio Bible Class','0410','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1256,80,'Hungduan','3603','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1257,80,'Kiangan','3604','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1258,80,'Lagawe','3600','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1259,99,'San Juan CPO','1500','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1260,80,'Lamut','3605','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1261,80,'Mayoyao (Mayaoyao)','3602','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1262,75,'Alabel','9501','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1263,80,'Tinoc','3609','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1264,75,'Glan','9517','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1265,74,'Adams','2922\r\n','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1266,75,'Kiamba','9514','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1267,75,'Maasim','9502','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1268,74,'Bacarra','2916','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1269,75,'Maitum','9515','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1270,74,'Badoc','2904','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1271,75,'Malapatan','9516','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1272,74,'Bangui','2920','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1273,74,'Banna (Espiritu)','2908','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1274,75,'Malungon','9503','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1275,74,'Batac','2906','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1276,74,'Burgos','2918','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1277,56,'Enrique Villanueva','6230','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1278,74,'Carasi','2911','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1279,56,'Larena','6226','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1280,74,'Currimao','2903','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1281,74,'Dingras','2913','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1282,56,'Lazi','6228','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1283,74,'Dumalneg','2921','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1284,74,'Laoag City','2900','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1285,56,'Maria','6229','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1286,74,'Marcos','2907','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1287,56,'San Juan','6227','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1288,74,'Nueva Era','2909','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1289,74,'Pagudpud','2919','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1290,56,'Siquijor','6225','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1291,74,'Paoay','2902','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1292,74,'Pasuquin','2917','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1293,68,'Bacon','4701','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1294,74,'Piddig','2912','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1295,68,'Barcelona','4712','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1296,74,'Pinili','2905','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1297,74,'San Nicolas','2901','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1298,74,'Sarrat','2914','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1299,68,'Bulan','4706','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1300,74,'Solsona','2910','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1301,74,'Vintar','2915','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1302,68,'Bulusan','4704','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1303,81,'Alilem','2716','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1304,81,'Banayoyo','2708','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1305,81,'Bantay','2727','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1306,68,'Casiguran','4702','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1307,68,'Castilla','4713','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1308,68,'Donsol','4715','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1309,68,'Gubat','4710','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1310,68,'Irosin','4707','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1311,68,'Juban','4703','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1312,68,'Magallanes','4705','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1313,68,'Matnog','4708','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1314,68,'Pilar','4714','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1315,68,'Prieto Diaz','4711','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1316,68,'Santa Magdalena','4709','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1317,68,'Sorsogon','4700','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1318,81,'Burgos','2724','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1319,81,'Cabugao','2732','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1320,81,'Candon City','2710','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1321,81,'Caoayan','2702','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1322,81,'Cervantes','2718','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1323,81,'Galimuyod','2709','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1324,81,'Gregorio del Pilar','2720','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1325,81,'Lidlidda','2723','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1326,23,'Anahawan','6610','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1327,23,'Bontoc','6604','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1328,23,'Hinunangan','6608','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1329,23,'Hinundayan','6609','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1330,81,'Magsingal','2730','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1331,23,'Libagon','6615','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1332,81,'Nagbukel','2725','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1333,81,'Narvacan','2704','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1334,81,'Quirino (Angkaki)','2721','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1335,23,'Liloan','6612','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1336,23,'Maasin','6600','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1337,81,'Salcedo (Baugen)','2711','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1338,23,'Macrohon','6601','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1339,81,'San Emilio','2722','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1340,81,'San Esteban','2706','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1341,81,'San Ildefonso','2728','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1342,81,'San Juan (Lapog)','2731','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1343,81,'San Vicente','2726','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1344,81,'Santa','2703','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1345,81,'Santa Catalina','2701','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1346,81,'Santa Cruz','2713','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1347,81,'Santa Lucia','2712','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1348,81,'Santa Maria','2705','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1349,81,'Santiago','2707','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1350,81,'Santo Domingo','2729','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1351,81,'Sigay','2719','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1352,81,'Sinait','2733','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1353,81,'Sugpon','2717','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1354,81,'Suyo','2715','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1355,81,'Tagudin','2714','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1356,81,'Vigan City','2700','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1357,23,'Malitbog','6603','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1358,23,'Padre Burgos','6602','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1359,23,'Pintuyan','6614','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1360,23,'San Francisco','6613','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1361,23,'San Juan (Cabalian)','6611','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1362,23,'San Ricardo','6617','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1363,23,'Silago','6607','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1364,23,'Sogod','6606','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1365,28,'Ajuy','5012','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1366,23,'St. Bernard','6616','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1367,28,'Alimodian','5028','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1368,23,'Tomas Oppus','6605','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1369,28,'Anilao','5009','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1370,28,'Badiangan','5033','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1371,28,'Balasan','5018','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1372,28,'Banate','5010','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1373,28,'Barotoc Nuevo','5007','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1374,28,'Barotoc Viejo','5011','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1375,28,'Batad','5016','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1376,28,'Bingawan','5041','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1377,28,'Cabatuan','5031','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1378,28,'Calinog','5040','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1379,28,'Carles','5019','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1380,81,'Concepcion','5013','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1381,28,'Dingle','5035','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1382,28,'Duenas','5038','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1383,28,'Dumangas','5006','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1384,28,'Estancia','5017','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1385,28,'Guimbal','5022','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1386,28,'Igbaras','5029','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1387,28,'Iloilo City','5000','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1388,28,'Janiuay','5034','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1389,28,'Lambunao','5042','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1390,28,'Leganes','5003','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1391,28,'Lemery','5043','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1392,28,'Leon','5026','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1393,28,'Maasin','5030','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1394,28,'Miagao','5023','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1395,28,'Mina','5032','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1396,28,'New Lucena','5005','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1397,28,'Oton','5020','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1398,28,'Passi','5037','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1399,28,'Pavia','5001','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1400,28,'Pototan','5008','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1401,28,'San Dionisio','5015','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1402,28,'San Enrique','5036','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1403,28,'San Joaquin','5024','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1404,28,'San Miguel','5025','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1405,28,'San Rafael','5039','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1406,28,'Santa Barbara','5002','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1407,28,'Sara','5014','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1408,28,'Tigbauan','5021','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1409,28,'Tubungan','5027','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1410,28,'Zarraga','5004','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1411,3,'asdfsadf','66666','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1412,43,'Mandaluyong Central Post Office','1550','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1413,43,'Vergara','1551','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1414,43,'Shaw Boulevard','1552','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1415,43,'National Center for Mental Health','1553','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1416,43,'East EDSA','1554','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1417,43,'Wack Wack','1555','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1418,43,'Greenhills South','1556','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1419,12,'Malabon','1470','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1420,12,'Flores','1471','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1421,12,'Longos','1472','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1422,12,'Tonsuya','1473','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1423,12,'Acacia','1474','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1424,12,'Potrero','1475','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1425,12,'Araneta Subdivision','1476','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1426,12,'Maysilo','1477','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1427,12,'Santolan','1478','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1428,12,'Muzon','1479','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1429,12,'Dampalit','1480','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1430,83,'Balbalan','3801','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1431,83,'Calanasan','3814','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1432,83,'Conner','3807','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1433,83,'Flora',' 3810','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1434,83,'Kabugao','3809','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1435,96,'Bagumbayan','9810','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1436,70,'Makati Central Post Office','1200','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1437,96,'Columbio','9801','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1438,70,'Fort Bonifacio (now part of Taguig City)','1201','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1439,83,'Liwan (Rizal)','3808','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1440,70,'Fort Bonifacio Naval Station','1202','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1441,83,'Lubuagan','3802','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1442,96,'Esperanza (Ampatuan)','9806','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1443,70,'San Antonio Village','1203','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1444,96,'Isulan','9805','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1445,70,'Santa Cruz','1205','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1446,96,'Kalamansig','9808','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1447,70,'Kasilawan','1206','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1448,96,'Lebak (Salaman)','9807','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1449,70,'Valenzuela (includes Rizal, San Miguel, and Santia','1208','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1450,70,'Bel-Air','1209','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1451,96,'Lutayan','9803','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1452,70,'Poblacion','1210','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1453,70,'Guadalupe Viejo (includes Palm Village)','1211','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1454,70,'Guadalupe Nuevo (includes Visayan Village)','1212','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1455,70,'Cembo','1214','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1456,96,'Mariano Marcos','9802','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1457,96,'Palimbang','9809','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1458,96,'Pres. Quirino','9804','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1459,96,'Sen. Ninoy Aquino','9811','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1460,96,'Tacurong','9800','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1461,16,'Indanan','7407','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1462,16,'Jolo','7400','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1463,16,'Kalingalan Kalauang','7416','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1464,83,'Luna','3813','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1465,16,'Lugus','7411','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1466,83,'Pasil','3803','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1467,16,'Luuk','7404','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1468,83,'Pinukpuk','3806','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1469,83,'Pudtol','3812','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1470,83,'Santa Marcela','3811','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1471,83,'Tabuk','3800','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1472,83,'Tanudan','3805','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1473,16,'Maimbung','7409','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1474,83,'Tinglayan','3804','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1475,7,'Agoo','2504','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1476,7,'Aringay',' 2503','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1477,7,'Bacnotan','2515','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1478,16,'Marungas','7413','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1479,16,'Panamao','7402','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1480,16,'Panglima Estino','7415','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1481,16,'Panguntaran','7414','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1482,16,'Parang','7408','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1483,16,'Pata','7405','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1484,7,'Bagulin','2512','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1485,7,'Balaoan','2517','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1486,7,'Bangar','2519','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1487,7,'Bauang','2501','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1488,7,'Burgos','2510','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1489,16,'Patikul','7401','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1490,7,'Caba','2502','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1491,16,'Siasi','7412','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1492,7,'Damortis',' 2507','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1493,7,'Luna','2518','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1494,16,'Talipao','7403','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1495,7,'Naguillan','2511','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1496,16,'Tapul','7410','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1497,7,'Pugo','2508','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1498,7,'Rosario','2506','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1499,7,' San Fernando City','2500','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1500,16,'Tongkil','7406','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1501,7,'San Gabriel','2513','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1502,7,'San Juan','2514','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1503,73,'Alegria','8425','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1504,7,'Santo Tomas','2505','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1505,70,'West Rembo','1215','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1506,7,'Santol','2516','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1507,70,'Comembo','1217','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1508,7,'Sudepen','2520','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1509,70,'Pembo','1218','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1510,7,'Tubao','2509','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1511,73,'Bacuag','8408','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1512,70,'Forbes Park North','1219','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1513,40,'Alaminos','4001','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1514,70,'Forbes Park South','1220','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1515,40,'Bay',' 4033','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1516,73,'Basilisa (Rizal)','8413','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1517,70,'Dasmari?as Village North','1221','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1518,40,'Binan','4024','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1519,70,'Dasmari?as Village South','1222','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1520,40,'Botocan, Magdalena','4006','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1521,40,'Cabuyao','4025','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1522,70,'San Lorenzo Village','1223','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1523,70,'Makati Commercial Center','1224','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1524,40,'Calamba City','4027','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1525,73,'Burgos','8424','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1526,70,'Urdaneta Village','1225','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1527,70,'Ayala Avenue-Paseo de Roxas','1226','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1528,73,'Cagdianao','8411','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1529,40,'Calauan','4012','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1530,70,'Salcedo Village','1227','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1531,73,'Claver','8410','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1532,40,'Camp Vicente Lim','4029','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1533,70,'Greenbelt','1228','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1534,40,'Canlubang, Calamba City',' 4028','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1535,70,'Legaspi Village','1229','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1536,40,'Cavinti','4013','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1537,70,'Pio del Pilar','1230','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1538,73,'Dapa','8417','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1539,40,'Famy','4021','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1540,40,'Kalayaan','4015','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1541,73,'Del Carmen','8418','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1542,40,'Liliw','4004','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1543,70,'Magallanes Village','1232','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1544,40,'Los Ba?os','4030','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1545,70,'Bangkal','1233','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1546,40,'Luisiana','4032','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1547,73,'Dinagat','8412','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1548,40,'Lumban','4014','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1549,73,'Gen. Luna','8419','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1550,40,'Mabitac','4020','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1551,73,'Gigaquit','8409','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1552,40,'Magdalena','4007','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1553,40,'Majayjay','4005','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1554,73,'Libjo (Albor)','8414','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1555,40,'Nagcarlan',' 4002','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1556,40,'Paete',' 4016','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1557,40,'Pagsanjan','4008','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1558,40,'Pakil','4017','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1559,70,'San Isidro','1234','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1560,40,'Pangil','4018','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1561,40,'Pila','4010','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1562,70,'Palanan','1235','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1563,40,'Rizal','4003','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1564,40,' San Pablo City',' 4000','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1565,73,'Loreto','8415','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1566,40,'San Pedro','4023','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1567,73,'Mainit','8407','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1568,102,'Baliguian','7123','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1569,73,'Malimano','8402','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1570,40,'Santa Cruz','4009','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1571,102,'Dapitan City','7101','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1572,40,'Santa Maria','4022','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1573,73,'Pilar','8420','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1574,102,'Dipolog City','7100','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1575,40,' Santa Rosa City','4026','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1576,102,'Gutalac','7118','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1577,40,'Siniloan','4019','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1578,73,'Placer','8405','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1579,102,'Jose Dalman (Ponot)','7111','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1580,102,'Kalawit','7124','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1581,73,'San Benito','8423','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1582,102,'Katipunan','7109','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1583,40,'University of the Philippines - Los Banos','4031','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1584,102,'La Libertad','7119','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1585,73,'San Francisco','8401','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1586,102,'Labason','7117','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1587,40,'Victoria',' 4011','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1588,102,'Liloy','7115','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1589,73,'San Isidro','8421','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1590,102,'Manukan','7110','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1591,102,'Mutia','7107','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1592,102,'Pinan','7105','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1593,24,'Bacolod','9205','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1594,73,'San Jose','8427','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1595,102,'Polanco','7106','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1596,24,'Baloi','9217','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1597,102,'Rizal','7104','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1598,24,'Baroy','9210','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1599,102,'Roxas','7102','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1600,24,'Iligan City','9200','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1601,102,'Salug','7114','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1602,24,'Kapatagan','9214','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1603,102,'Sergio Osme?a','7108','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1604,102,'Siayan','7113','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1605,24,'Karomatan','9215','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1606,73,'Santa Monica','8422','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1607,102,'Sibuco','7122','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1608,24,'Kauswagan','9202','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1609,24,'Kolambogan','9207','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1610,102,'Sibutad','7103','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1611,73,'Sison','8404','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1612,24,'Lala','9211','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1613,102,'Sindangan (Leon B. Postigo)','7112','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1614,102,'Siocon','7120','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1615,73,'Socorro','8416','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1616,24,'Linamon',' 9201','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1617,102,'Siraway','7121','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1618,24,'Magsaysay',' 9221','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1619,102,'Tampilisan','7116','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1620,73,'Surigao City','8400','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1621,24,'Maigo','9206','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1622,73,'Tagana-an','8403','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1623,24,'Matungao','9203','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1624,102,'Margo sa Tubig','7035','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1625,73,'Tubajon','8426','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1626,24,'Munai','9219','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1627,102,'Midsalip','7021','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1628,24,'Nunungan','9216','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1629,73,'Tubod','8406','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1630,102,'Molave','7023','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1631,102,'Pagadian City','7016','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1632,24,'Pantao Ragat','9208','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1633,24,'Pantar','9218','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1634,14,'Barobo','8309','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1635,102,'Pitogo','7033','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1636,24,'Poona Piagapo','9204','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1637,102,'Ramon Magsaysay','7024','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1638,24,'Salvador','9212','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1639,14,'Bayabas','8303','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1640,102,'San Miguel','7029','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1641,102,'San Pablo','7031','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1642,24,'Sapad',' 9213','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1643,102,'Tabina','7034','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1644,24,'Tagoloan','9222','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1645,14,'Bislig','8311','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1646,102,'Tambulig','7025','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1647,24,'Tangkal',' 9220','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1648,102,'Tigbao','7043','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1649,14,'Cagwait','8304','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1650,24,'Tubod','9209','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1651,102,'Tukuran','7019','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1652,14,'Cantillan','8317','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1653,102,'Vicencio Sagun','7036','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1654,57,'Bacolod Grande','9316','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1655,102,'Zamboanga City','7000','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1656,57,'Balabagan',' 9302','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1657,14,'Carmen','8315','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1658,57,'Balindong','9318','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1659,66,'Alicia','7040','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1660,57,'Bayang','9309','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1661,14,'Carrascal','8318','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1662,66,'Buug','7009','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1663,57,'Binidayan','9310','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1664,14,'Cortez','8313','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1665,66,'Diplahan','7039','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1666,57,'Buadiposo Buntong',' 9714','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1667,66,'Imelda','7007','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1668,57,'Bubong','9708','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1669,66,'Ipil','7001','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1670,14,'Hinatuan','8310','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1671,57,'Bumbaran','9320','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1672,66,'Kabalasan','7005','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1673,66,'Mabuhay','7010','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1674,57,'Butig',' 9305','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1675,66,'Malangas','7038','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1676,57,'Calanogas','9319','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1677,66,'Naga','7004','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1678,57,'Ganassi','9311','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1679,66,'Olutanga','7041','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1680,57,'Kapai','9709','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1681,66,'Payao','7008','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1682,57,'Lumba Bayabao','9703','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1683,66,'Roseller Lim','7002','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1684,57,'Lumbatan','9307','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1685,66,'Siay','7006','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1686,66,'Talusan','7012','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1687,57,'Lumbayanague','9306','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1688,66,'Titay','7003','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1689,57,' Macador Andong','9308','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1690,66,'Tungawan','7018','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1691,57,'Madalum','9315','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1692,27,'Botolan','2202','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1693,57,'Madamba','9314','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1694,27,'Cabangan','2203','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1695,57,'Maguing','9715','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1696,27,'Candelaria','2212','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1697,57,'Malabang','9300','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1698,27,'Castillejos','2208','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1699,57,'Maragong','9303','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1700,27,'Iba City','2201','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1701,57,'Marantao','9711','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1702,27,'Masinloc','2211','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1703,27,'Olongapo City','2200','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1704,27,'Subic Bay Freeport Zone (Metro Subic)','2222','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1705,27,'San Antonio','2206','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1706,57,' Marawi City','9700','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1707,27,'Palauig & Scarborough Shoal','2210','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1708,57,'Masui','9706','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1709,27,'San Felipe','2204','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1710,57,'Mulondo','9702','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1711,57,'Pagayawan','9312','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1712,27,'San Marcelino','2207','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1713,27,'San Narciso','2205','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1714,57,'Piagapo','9710','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1715,57,'Poona Bayabao','9705','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1716,27,'Subic','2209','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1717,57,'Pualas','9313','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1718,57,'Ramain-Ditsaan','9713','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1719,57,'Saguiaran','9701','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1720,57,'Sultan Gumander','9301','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1721,57,'Tagoloan-II',' 9321','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1722,57,'Tamparan','9704','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1723,57,'Taraka','9712','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1724,57,'Tubaran','9304','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1725,57,'Tugaya','9317','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1726,14,'Lanuza','8314','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1727,57,'Wao','9716','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1728,14,'Lianga','8307','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1729,33,'Las Pi?as Central Post Office','1740','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1730,33,'Remarville Subdivision','1741','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1731,14,'Lingig','8312','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1732,33,'Pulang Lupa and Zapote',' 1742','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1733,14,'Madrid','8316','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1734,33,'Cut-cut','1743','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1735,33,'Manuyo','1744','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1736,33,'Gatchalian Subdivision',' 1745','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1737,33,'Verdant Acres Subdivision','1746','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1738,18,'Arkong Bato','1444','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1739,33,' Moonwalk Subdivision and Talon','1747','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1740,18,'Balangkas - Caloong','1445','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1741,33,' Manila Doctors Village','1748','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1742,18,'Dalandan - West Canumay','1443','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1743,18,'East Canumay - Lawang Bato Punturin','1447','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1744,18,'Fortune Village','1442','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1745,18,'Karuhatan','1441','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1746,18,'Lingunan','1446','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1747,33,'Angela Village','1749','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1748,33,'Almanza','1750','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1749,33,'T.S. Cruz Subdivision',' 1751','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1750,18,'Mapulang Lupa','1448','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1751,33,'Soldiers Hills Subdivision','1752','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1752,18,'Valenzuela CPO - Malinta','1440','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1753,53,'Abuyog',' 6510','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1754,18,'Valenzuela P.O. Boxes','1469','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1755,53,'Alangalang','6517','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1756,53,'Albuera','6542','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1757,53,'Babatngon','6520','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1758,53,'Barugo','6519','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1759,100,'Bongao','7500','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1760,53,'Bato','6525','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1761,53,'Baybay','6521','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1762,53,'Burauen','6516','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1763,100,'Cagayan de Sulu (Mapun)','7508','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1764,53,'Calubian',' 6534','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1765,100,'Languyan','7509','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1766,53,'Capoocan',' 6530','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1767,100,'Panglima Sugala (Balimbing)','7501','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1768,53,'Carigara','6529','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1769,100,'Sapa-Sapa','7503','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1770,53,'Dagami','6515','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1771,100,'Simunol','7505','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1772,53,'Dulag','6505','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1773,100,'Sitangkai','7506','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1774,100,'South Ubian','7504','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1775,100,'Taganak (Turtle Island)','7507','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1776,100,'Tandu Bas','7502','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1777,53,'Hilongos','6524','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1778,86,'Anao','2310','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1779,53,'Hindang','6523','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1780,86,'Bamban','2317','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1781,86,'Camiling','2306','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1782,86,'Capas','2315','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1783,53,'Inopacan',' 6522','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1784,86,'Concepcion','2316','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1785,53,'Isabel','6539','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1786,86,'Gerona','2302','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1787,53,'Jaro','6527','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1788,86,'La Paz','2314','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1789,53,'Javier','6511','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1790,86,'Mayantoc','2304','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1791,53,'Julita','6506','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1792,86,'Moncada','2308','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1793,53,'Kananga','6531','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1794,14,'Marihatag','8306','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1795,86,'Paniqui','2307','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1796,53,'La Paz','6508','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1797,86,'Pura','2312','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1798,53,'Leyte','6533','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1799,14,'San Agustin','8305','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1800,53,'Macarthur','6509','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1801,86,'Ramos','2311','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1802,53,'Mahaplag','6512','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1803,14,'San Miguel','8301','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1804,86,'San Clemente','2305','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1805,14,'Tagbina','8308','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1806,86,'San Manuel','2309','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1807,86,'San Miguel','2301','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1808,86,'Santa Ignacia','2303','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1809,86,'Tarlac City','2300','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1810,86,'Victoria','2313','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1811,14,'Tago','8302','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1812,14,'Tandag','8300','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1813,29,'Bay Breeze Executive Village','1636','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1814,29,'Tuktukan','1637','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1815,29,'Bicutan','1631','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1816,29,'Bicutan [Lower]','1632','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1817,53,'Matag-ob',' 6532','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1818,29,'Bicutan [Western ] (including FTI)','1630','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1819,29,'Bicutan [Upper]','1633','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1820,53,'Matalom',' 6526','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1821,29,'Nichols - McKinley','1634','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1822,53,'Mayorga','6507','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1823,29,'Ligid','1638','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1824,53,'Merida','6540','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1825,53,'Ormoc City','6541','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1826,53,'Palo',' 6501','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1827,53,'Palompon','6538','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1828,53,'Pastrana','6514','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1829,53,'San Isidro','6535','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1830,53,'San Miguel','6518','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1831,53,'Santa Fe','6513','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1832,53,'Tabango','6536','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1833,53,'Tabontabon','6504','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1834,53,'Tacloban City','6500','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1835,53,'Tanuan','6502','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1836,53,'Tolosa','6503','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1837,53,'Tunga','6528','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1838,53,'Villaba','6537','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1839,93,'Cotabato City','9600','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1840,93,'Ampatuan','9609','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1841,93,'Barira',' 9614','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1842,93,'Buldon','9615','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1843,93,'Buluan','9616','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1844,93,'Datu Paglas','9617','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1845,93,'Datu Piang','9607','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1846,93,'Dinaig','9601','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1847,93,'Gen. S.K. Datun','9618','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1848,93,'Kabuntulan','9606','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1849,93,'Maganoy','9608','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1850,93,'Matanog','9613','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1851,93,'Pagalungan','9610','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1852,93,'Parang','9604','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1853,93,'South Upi','9603','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1854,93,'Sultan Kudarat','9605','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1855,93,'Sultan sa Barongis','9611','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1856,93,'Talayan','9612','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1857,93,'Upi','9602','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1858,105,'Amparo Subdivision','1425','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1859,105,'Bagong Silang','1428','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1860,105,'Bagumbong/Pag-asa','1421','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1861,105,'Bankers Village','1426','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1862,105,'Capitol Parkland Subd.','1424','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1863,105,'Kaybiga/Deparo','1420','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1864,105,'Lilles Ville Subd.','1423','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1865,105,'Novaliches North','1422','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1866,105,'Tala Leprosarium','1427','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1867,105,'Victory Heights','1429','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1868,105,'1st to 7th Ave. West','1405','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1869,105,'Baesa','1401','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1870,105,'Grace Park East','1403','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1871,105,'Grace Park West','1406','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1872,105,'Kaloocan City CPO','1400','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1873,105,'Maypajo','1410','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1874,105,'San Jose','1404','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1875,105,'Sangandaan','1408','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1876,105,'Sta. Quiteria','1402','','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(1877,105,'University Hills','1407','','0000-00-00 00:00:00','','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `app_zipcodes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `audittrail`
--

DROP TABLE IF EXISTS `audittrail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `audittrail` (
  `audittrail_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `subject` text NOT NULL,
  `date` date NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `good_rec` int(11) NOT NULL,
  `bad_rec` int(11) NOT NULL,
  PRIMARY KEY (`audittrail_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audittrail`
--

LOCK TABLES `audittrail` WRITE;
/*!40000 ALTER TABLE `audittrail` DISABLE KEYS */;
/*!40000 ALTER TABLE `audittrail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `audittrail_log`
--

DROP TABLE IF EXISTS `audittrail_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `audittrail_log` (
  `audittrail_id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL,
  `comments` varchar(100) DEFAULT NULL,
  `send_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audittrail_log`
--

LOCK TABLES `audittrail_log` WRITE;
/*!40000 ALTER TABLE `audittrail_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `audittrail_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bank_empgroup`
--

DROP TABLE IF EXISTS `bank_empgroup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bank_empgroup` (
  `bnkgrup_id` int(11) NOT NULL AUTO_INCREMENT,
  `bankiemp_id` int(10) NOT NULL,
  `bank_id` int(10) NOT NULL,
  `bnkgrup_addwho` varchar(100) DEFAULT NULL,
  `bnkgrup_addwhen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`bnkgrup_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bank_empgroup`
--

LOCK TABLES `bank_empgroup` WRITE;
/*!40000 ALTER TABLE `bank_empgroup` DISABLE KEYS */;
/*!40000 ALTER TABLE `bank_empgroup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bank_info`
--

DROP TABLE IF EXISTS `bank_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bank_info` (
  `bank_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `baccntype_id` int(10) unsigned NOT NULL,
  `comp_id` int(10) unsigned NOT NULL,
  `branchinfo_id` int(10) DEFAULT NULL,
  `banklist_id` int(10) unsigned NOT NULL,
  `bank_acct_no` varchar(30) COLLATE latin1_general_ci DEFAULT NULL,
  `bank_acct_name` varchar(150) COLLATE latin1_general_ci DEFAULT NULL,
  `bank_swift_code` varchar(30) COLLATE latin1_general_ci DEFAULT NULL,
  `bank_branch` varchar(150) COLLATE latin1_general_ci DEFAULT NULL,
  `bank_addwho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `bank_addwhen` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `bank_updatewho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `bank_upadtewhen` datetime DEFAULT NULL,
  `bank_isactive` tinyint(1) unsigned DEFAULT '1',
  `bank_routing_number` varchar(10) COLLATE latin1_general_ci DEFAULT NULL,
  `bank_company_code` varchar(30) COLLATE latin1_general_ci DEFAULT NULL,
  `bank_ceiling_amount` decimal(10,2) DEFAULT NULL,
  `bank_contact` varchar(225) COLLATE latin1_general_ci DEFAULT NULL,
  `bank_building` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `bank_address` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  PRIMARY KEY (`bank_id`),
  KEY `banklist_id` (`banklist_id`),
  KEY `bank_info_FKIndex2` (`comp_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bank_info`
--

LOCK TABLES `bank_info` WRITE;
/*!40000 ALTER TABLE `bank_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `bank_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bank_infoemp`
--

DROP TABLE IF EXISTS `bank_infoemp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bank_infoemp` (
  `bankiemp_id` int(10) unsigned NOT NULL,
  `baccntype_id` int(10) unsigned NOT NULL,
  `emp_id` int(10) unsigned NOT NULL,
  `banklist_id` int(10) unsigned NOT NULL,
  `bankiemp_acct_no` varchar(30) COLLATE latin1_general_ci DEFAULT NULL,
  `bankiemp_acct_name` varchar(150) COLLATE latin1_general_ci DEFAULT NULL,
  `bankiemp_swift_code` varchar(30) COLLATE latin1_general_ci DEFAULT NULL,
  `bankiemp_perc` decimal(10,2) DEFAULT NULL,
  `bankiemp_addwho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `bankiemp_addwhen` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `bankiemp_updatewho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `bankiemp_updatewhen` datetime DEFAULT NULL,
  PRIMARY KEY (`bankiemp_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bank_infoemp`
--

LOCK TABLES `bank_infoemp` WRITE;
/*!40000 ALTER TABLE `bank_infoemp` DISABLE KEYS */;
/*!40000 ALTER TABLE `bank_infoemp` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bank_list`
--

DROP TABLE IF EXISTS `bank_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bank_list` (
  `banklist_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `banklist_name` varchar(60) COLLATE latin1_general_ci DEFAULT NULL,
  `banklist_desc` varchar(150) COLLATE latin1_general_ci DEFAULT NULL,
  `banklist_addwho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `banklist_addwhen` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `banklist_updatewho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `banklist_updatewhen` datetime DEFAULT NULL,
  `banklist_isactive` tinyint(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`banklist_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bank_list`
--

LOCK TABLES `bank_list` WRITE;
/*!40000 ALTER TABLE `bank_list` DISABLE KEYS */;
INSERT INTO `bank_list` VALUES (1,'BPI','Bank of the Philippine Islands','admin','2011-11-03 10:15:15','','0000-00-00 00:00:00',1),(2,'BDO','Banco de Oro','admin','2012-06-19 13:05:04',NULL,NULL,1),(3,'RCBC','RCBC Commercial Bank','admin','2012-06-19 13:05:06',NULL,NULL,1),(4,'Standard Chartered Bank','Standard Chartered Bank','admin','2012-08-16 10:36:28',NULL,NULL,1),(5,'Metrobank','Metrobank','admin','2012-08-16 10:41:31',NULL,NULL,1),(6,'Union Bank','Union Bank','admin','2012-08-16 10:45:18',NULL,NULL,1);
/*!40000 ALTER TABLE `bank_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bir_alphalist_prev_emp`
--

DROP TABLE IF EXISTS `bir_alphalist_prev_emp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bir_alphalist_prev_emp` (
  `bir_alphalist_prev_emp_id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `emp_id` int(11) NOT NULL,
  `bir_alphalist_year` varchar(10) NOT NULL,
  `tax_withheld` decimal(10,5) NOT NULL,
  `taxable_basic` decimal(10,5) NOT NULL,
  `taxable_other_ben` decimal(10,5) NOT NULL,
  `taxable_compensation` decimal(10,5) NOT NULL,
  `nt_other_ben` decimal(10,5) NOT NULL,
  `nt_deminimis` decimal(10,5) NOT NULL,
  `nt_statutories` decimal(10,5) NOT NULL,
  `nt_compensation` decimal(10,5) NOT NULL,
  `gross_compensation` decimal(10,5) NOT NULL,
  `basic_smw` decimal(10,5) NOT NULL,
  `holiday_pay` decimal(10,5) NOT NULL,
  `overtime_pay` decimal(10,5) NOT NULL,
  `night_differential` decimal(10,5) NOT NULL,
  `hazard_pay` decimal(10,5) NOT NULL,
  PRIMARY KEY (`bir_alphalist_prev_emp_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bir_alphalist_prev_emp`
--

LOCK TABLES `bir_alphalist_prev_emp` WRITE;
/*!40000 ALTER TABLE `bir_alphalist_prev_emp` DISABLE KEYS */;
/*!40000 ALTER TABLE `bir_alphalist_prev_emp` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bnkaccnt_type`
--

DROP TABLE IF EXISTS `bnkaccnt_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bnkaccnt_type` (
  `baccntype_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `baccntype_name` varchar(100) COLLATE latin1_general_ci DEFAULT NULL,
  PRIMARY KEY (`baccntype_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bnkaccnt_type`
--

LOCK TABLES `bnkaccnt_type` WRITE;
/*!40000 ALTER TABLE `bnkaccnt_type` DISABLE KEYS */;
INSERT INTO `bnkaccnt_type` VALUES (1,'Saving'),(2,'Current'),(3,'Credit Card'),(4,'Others'),(5,'Currrent');
/*!40000 ALTER TABLE `bnkaccnt_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bonus_formula`
--

DROP TABLE IF EXISTS `bonus_formula`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bonus_formula` (
  `bonus_id` int(11) NOT NULL AUTO_INCREMENT,
  `bonus_code` varchar(50) DEFAULT NULL,
  `bonus_Desc` varchar(225) DEFAULT NULL,
  PRIMARY KEY (`bonus_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bonus_formula`
--

LOCK TABLES `bonus_formula` WRITE;
/*!40000 ALTER TABLE `bonus_formula` DISABLE KEYS */;
INSERT INTO `bonus_formula` VALUES (1,'BCBSwithLD','Base on Current Basic Salary (w/ Leave Deduction)'),(2,'BARS','Base on Actual Received Salary (if w/ basic salary increase and w/ Leave Deduction)'),(3,'BCBSnoLD','Base on Current Basic Salary (no Leave deduction)'),(4,'BCBSxP1.25','Base on Current Basic Salary multiply by percentage (1.25)'),(5,'IPE','Included Pay Element ( Part of Salary but non-taxable)');
/*!40000 ALTER TABLE `bonus_formula` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `branch_info`
--

DROP TABLE IF EXISTS `branch_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `branch_info` (
  `branchinfo_id` int(11) NOT NULL AUTO_INCREMENT,
  `comp_id` int(11) DEFAULT NULL,
  `comptype_id` int(11) DEFAULT NULL,
  `branch_industry` varchar(150) DEFAULT NULL,
  `branchinfo_code` varchar(60) DEFAULT NULL,
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `branch_info`
--

LOCK TABLES `branch_info` WRITE;
/*!40000 ALTER TABLE `branch_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `branch_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cf_detail`
--

DROP TABLE IF EXISTS `cf_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cf_detail` (
  `cfdetail_id` int(11) NOT NULL AUTO_INCREMENT,
  `paystub_id` int(11) NOT NULL,
  `payperiod_id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL,
  `cfhead_id` int(11) NOT NULL,
  `uptadet_id` int(11) NOT NULL,
  `cfdetail_rec` varchar(150) DEFAULT NULL,
  `cfdetail_addwho` varchar(150) DEFAULT NULL,
  `cfdetail_addwhen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cfdetail_updatewho` varchar(150) DEFAULT NULL,
  `cfdetail_updatewhen` datetime DEFAULT NULL,
  PRIMARY KEY (`cfdetail_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cf_detail`
--

LOCK TABLES `cf_detail` WRITE;
/*!40000 ALTER TABLE `cf_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `cf_detail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cf_head`
--

DROP TABLE IF EXISTS `cf_head`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cf_head` (
  `cfhead_id` int(11) NOT NULL AUTO_INCREMENT,
  `cfhead_code` varchar(100) DEFAULT NULL,
  `cfhead_name` varchar(150) DEFAULT NULL,
  `cfhead_type` varchar(15) DEFAULT NULL,
  `cfhead_stat` int(2) DEFAULT NULL,
  `cfhead_addwho` varchar(150) DEFAULT NULL,
  `cfhead_addwhen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cfhead_updatewho` varchar(150) DEFAULT NULL,
  `cfhead_updatewhen` datetime DEFAULT NULL,
  PRIMARY KEY (`cfhead_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cf_head`
--

LOCK TABLES `cf_head` WRITE;
/*!40000 ALTER TABLE `cf_head` DISABLE KEYS */;
/*!40000 ALTER TABLE `cf_head` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `afterCreate` AFTER INSERT on `cf_head`
FOR EACH ROW BEGIN
	INSERT INTO app_formula_keywords (app_fkey_name, cfhead_id, app_fkey_isactive, app_fkey_query, app_fkey_vars, app_fkey_result) VALUES (NEW.cfhead_name, NEW.cfhead_id, NEW.cfhead_stat,CONCAT("select cfdetail_rec from cf_detail where payperiod_id=? and emp_id=? and cfhead_id=",NEW.cfhead_id),"array($payperiod_id,$emp_id)","cfdetail_rec");
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `afterUpdate` AFTER UPDATE on `cf_head`
FOR EACH ROW BEGIN
	UPDATE app_formula_keywords SET app_fkey_name = NEW.cfhead_name WHERE cfhead_id = NEW.cfhead_id;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `afterDelete` AFTER DELETE on `cf_head`
FOR EACH ROW BEGIN
	delete from app_formula_keywords where cfhead_id=OLD.cfhead_id;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `company_info`
--

DROP TABLE IF EXISTS `company_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `company_info` (
  `comp_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `comptype_id` int(10) unsigned NOT NULL,
  `comp_code` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `comp_industry` varchar(150) COLLATE latin1_general_ci DEFAULT NULL,
  `comp_name` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `comp_add` text COLLATE latin1_general_ci,
  `comp_zipcode` int(10) unsigned DEFAULT NULL,
  `comp_tel` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `comp_email` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `comp_prim_contc` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `comp_tin` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `comp_sss` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `comp_phic` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `comp_hdmf` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `comp_priority` int(10) unsigned DEFAULT NULL,
  `comp_addwho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `comp_addwhen` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `comp_updatewho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `comp_updatewhen` datetime DEFAULT NULL,
  PRIMARY KEY (`comp_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `company_info`
--

LOCK TABLES `company_info` WRITE;
/*!40000 ALTER TABLE `company_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `company_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `company_type`
--

DROP TABLE IF EXISTS `company_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `company_type` (
  `comptype_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `comptype_desc` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `comptype_addwho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `comptype_addwhen` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `comptype_updatewho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `comptype_updatewhen` datetime DEFAULT NULL,
  PRIMARY KEY (`comptype_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `company_type`
--

LOCK TABLES `company_type` WRITE;
/*!40000 ALTER TABLE `company_type` DISABLE KEYS */;
INSERT INTO `company_type` VALUES (1,'Private','jayadmin','2011-06-27 00:53:58','','0000-00-00 00:00:00'),(2,'Government','jayadmin','2011-06-27 01:12:21','','0000-00-00 00:00:00'),(3,'NGO','jayadmin','2011-06-27 01:12:37','jimabignay','2012-06-19 03:30:37');
/*!40000 ALTER TABLE `company_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `deduction_type`
--

DROP TABLE IF EXISTS `deduction_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `deduction_type` (
  `dec_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dec_code` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `dec_name` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `dec_ord` int(10) unsigned DEFAULT NULL,
  `dec_addwho` varchar(30) COLLATE latin1_general_ci DEFAULT NULL,
  `dec_addwhen` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `dec_updatewho` varchar(30) COLLATE latin1_general_ci DEFAULT NULL,
  `dec_updatewhen` datetime DEFAULT NULL,
  PRIMARY KEY (`dec_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `deduction_type`
--

LOCK TABLES `deduction_type` WRITE;
/*!40000 ALTER TABLE `deduction_type` DISABLE KEYS */;
INSERT INTO `deduction_type` VALUES (1,'SSS','Social Security Service',10,'admin','2009-10-06 10:13:10','','0000-00-00 00:00:00'),(2,'PHIC','Philippine Health Insurance Corporation ',20,'admin','2009-10-06 10:19:05','','0000-00-00 00:00:00'),(3,'HDMF','Home Development Mutual Fund',30,'admin','2009-10-06 10:19:42','','0000-00-00 00:00:00'),(5,'TAX','Tax',50,'admin','2009-10-06 10:20:21','','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `deduction_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dependent_info`
--

DROP TABLE IF EXISTS `dependent_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dependent_info` (
  `depnd_id` varchar(20) COLLATE latin1_general_ci NOT NULL,
  `emp_id` int(10) unsigned NOT NULL,
  `depnd_fname` varchar(60) COLLATE latin1_general_ci DEFAULT NULL,
  `depnd_mname` varchar(60) COLLATE latin1_general_ci DEFAULT NULL,
  `depnd_lname` varchar(60) COLLATE latin1_general_ci DEFAULT NULL,
  `depnd_gender` varchar(10) COLLATE latin1_general_ci DEFAULT NULL,
  `depnd_bdate` date DEFAULT NULL,
  `depnd_country_bdate` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `depnd_nationality` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `depnd_relationship` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `depnd_addwho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `depnd_addwhen` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `depnd_updatewho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `depnd_updatewhen` datetime DEFAULT NULL,
  PRIMARY KEY (`depnd_id`),
  KEY `dependent_info_FKIndex1` (`emp_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dependent_info`
--

LOCK TABLES `dependent_info` WRITE;
/*!40000 ALTER TABLE `dependent_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `dependent_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `emp_201status`
--

DROP TABLE IF EXISTS `emp_201status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `emp_201status` (
  `emp201status_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `emp201status_name` varchar(30) COLLATE latin1_general_ci DEFAULT NULL,
  `emp201status_ord` int(11) DEFAULT NULL,
  PRIMARY KEY (`emp201status_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `emp_201status`
--

LOCK TABLES `emp_201status` WRITE;
/*!40000 ALTER TABLE `emp_201status` DISABLE KEYS */;
INSERT INTO `emp_201status` VALUES (2,'RETIRED',20),(3,'RETRENCHED',30),(4,'SUSPENDED',40),(5,'TERMINATED',50),(6,'TEMPORARY LAYOFF',60),(7,'ACTIVE',70),(8,'RESIGNED',80),(9,'END OF CONTRACT',90),(10,'CONFIDENTIAL',100);
/*!40000 ALTER TABLE `emp_201status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `emp_benefits`
--

DROP TABLE IF EXISTS `emp_benefits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `emp_benefits` (
  `ben_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `psa_id` int(11) NOT NULL,
  `emp_id` int(10) unsigned NOT NULL,
  `ben_amount` decimal(15,6) DEFAULT NULL,
  `ben_payperday` decimal(15,6) DEFAULT NULL,
  `ben_startdate` date DEFAULT NULL,
  `ben_enddate` date DEFAULT NULL,
  `ben_isfixed` tinyint(3) unsigned DEFAULT '0',
  `ben_addwho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `ben_addwhen` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `ben_updatewho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `ben_updatewhen` datetime DEFAULT NULL,
  `ben_suspend` tinyint(3) unsigned DEFAULT '0',
  `ben_periodselection` varchar(10) COLLATE latin1_general_ci DEFAULT NULL,
  PRIMARY KEY (`ben_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `emp_benefits`
--

LOCK TABLES `emp_benefits` WRITE;
/*!40000 ALTER TABLE `emp_benefits` DISABLE KEYS */;
/*!40000 ALTER TABLE `emp_benefits` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `emp_category`
--

DROP TABLE IF EXISTS `emp_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `emp_category` (
  `empcateg_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `empcateg_name` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `empcateg_ord` int(10) unsigned DEFAULT NULL,
  `empcateg_addwho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `empcateg_addwhen` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `empcateg_updatewho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `empcateg_updatewhen` datetime DEFAULT NULL,
  PRIMARY KEY (`empcateg_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `emp_category`
--

LOCK TABLES `emp_category` WRITE;
/*!40000 ALTER TABLE `emp_category` DISABLE KEYS */;
/*!40000 ALTER TABLE `emp_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `emp_classification`
--

DROP TABLE IF EXISTS `emp_classification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `emp_classification` (
  `empclass_id` int(11) NOT NULL AUTO_INCREMENT,
  `empclass_name` varchar(50) DEFAULT NULL,
  `empclass_ord` int(10) DEFAULT NULL,
  `empclass_addwho` varchar(50) DEFAULT NULL,
  `empclass_addwhen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `empclass_updatewho` varchar(50) DEFAULT NULL,
  `empclass_updatewhen` datetime DEFAULT NULL,
  PRIMARY KEY (`empclass_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `emp_classification`
--

LOCK TABLES `emp_classification` WRITE;
/*!40000 ALTER TABLE `emp_classification` DISABLE KEYS */;
/*!40000 ALTER TABLE `emp_classification` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `emp_deductions_detail`
--

DROP TABLE IF EXISTS `emp_deductions_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `emp_deductions_detail` (
  `empdd_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `emp_id` int(10) unsigned NOT NULL,
  `dec_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`empdd_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `emp_deductions_detail`
--

LOCK TABLES `emp_deductions_detail` WRITE;
/*!40000 ALTER TABLE `emp_deductions_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `emp_deductions_detail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `emp_leave`
--

DROP TABLE IF EXISTS `emp_leave`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `emp_leave` (
  `empleave_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `emp_id` int(10) unsigned NOT NULL,
  `leave_id` int(10) unsigned NOT NULL,
  `empleave_used_day` decimal(10,2) DEFAULT NULL,
  `empleave_available_day` decimal(10,2) DEFAULT NULL,
  `empleave_substitute` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `empleave_credit` decimal(10,2) DEFAULT NULL,
  `empleave_stat` tinyint(2) unsigned DEFAULT '1',
  `empleave_addwho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `empleave_addwhen` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `empleave_updatewho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `empleave_updatewhen` datetime DEFAULT NULL,
  PRIMARY KEY (`empleave_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `emp_leave`
--

LOCK TABLES `emp_leave` WRITE;
/*!40000 ALTER TABLE `emp_leave` DISABLE KEYS */;
/*!40000 ALTER TABLE `emp_leave` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `emp_leave_rec`
--

DROP TABLE IF EXISTS `emp_leave_rec`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `emp_leave_rec` (
  `emp_leav_rec_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `paystub_id` int(10) DEFAULT NULL,
  `empleave_id` int(10) unsigned NOT NULL,
  `payperiod_id` int(10) NOT NULL,
  `emp_id` int(10) NOT NULL,
  `uptadet_id` int(10) NOT NULL,
  `emp_leav_rec_leavedays` decimal(10,2) DEFAULT NULL,
  `emp_leav_rec_date` date DEFAULT NULL,
  `emp_leav_rec_reasonforleave` text COLLATE latin1_general_ci,
  `emp_leav_rec_stat` tinyint(3) unsigned DEFAULT NULL,
  `emp_leav_rec_addwho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `emp_leav_rec_addwhen` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`emp_leav_rec_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `emp_leave_rec`
--

LOCK TABLES `emp_leave_rec` WRITE;
/*!40000 ALTER TABLE `emp_leave_rec` DISABLE KEYS */;
/*!40000 ALTER TABLE `emp_leave_rec` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `emp_masterfile`
--

DROP TABLE IF EXISTS `emp_masterfile`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `emp_masterfile` (
  `emp_id` int(10) unsigned NOT NULL,
  `taxep_id` int(10) unsigned NOT NULL,
  `ud_id` int(10) unsigned NOT NULL,
  `comp_id` int(10) unsigned NOT NULL,
  `post_id` int(10) unsigned NOT NULL,
  `empcateg_id` int(10) unsigned NOT NULL,
  `emptype_id` int(10) unsigned NOT NULL,
  `pi_id` int(10) unsigned NOT NULL,
  `branchinfo_id` int(10) unsigned NOT NULL,
  `emp_idnum` varchar(20) CHARACTER SET latin1 DEFAULT NULL,
  `emp_hiredate` date DEFAULT NULL,
  `emp_regulardate` date DEFAULT NULL,
  `emp_resigndate` date DEFAULT NULL,
  `emp_resonresign` text COLLATE latin1_general_ci,
  `emp_retiredate` date DEFAULT NULL,
  `emp_appdate` date DEFAULT NULL,
  `emp_addwho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `emp_addwhen` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `emp_updatewho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `emp_updatewhen` datetime DEFAULT NULL,
  `emp_stat` int(10) unsigned DEFAULT '7',
  `emp_picture` mediumblob,
  `emp_isconfidentail` tinyint(2) unsigned DEFAULT '0',
  `emp_tacessid` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  PRIMARY KEY (`emp_id`),
  KEY `pi_id` (`pi_id`),
  KEY `emptype_id` (`emptype_id`),
  KEY `empcateg_id` (`empcateg_id`),
  KEY `post_id` (`post_id`),
  KEY `comp_id` (`comp_id`),
  KEY `ud_id` (`ud_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `emp_masterfile`
--

LOCK TABLES `emp_masterfile` WRITE;
/*!40000 ALTER TABLE `emp_masterfile` DISABLE KEYS */;
/*!40000 ALTER TABLE `emp_masterfile` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `emp_personal_info`
--

DROP TABLE IF EXISTS `emp_personal_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `emp_personal_info` (
  `pi_id` int(10) unsigned NOT NULL,
  `zipcode_id` int(10) unsigned NOT NULL,
  `p_id` int(10) unsigned NOT NULL,
  `pi_fname` varchar(60) COLLATE latin1_general_ci DEFAULT NULL,
  `pi_mname` varchar(60) COLLATE latin1_general_ci DEFAULT NULL,
  `pi_lname` varchar(60) COLLATE latin1_general_ci DEFAULT NULL,
  `pi_nickname` varchar(60) COLLATE latin1_general_ci DEFAULT NULL,
  `pi_gender` varchar(6) COLLATE latin1_general_ci DEFAULT NULL,
  `pi_bdate` date DEFAULT NULL,
  `pi_place_bdate` varchar(30) COLLATE latin1_general_ci DEFAULT NULL,
  `pi_nationality` varchar(30) COLLATE latin1_general_ci DEFAULT NULL,
  `pi_religion` varchar(30) COLLATE latin1_general_ci DEFAULT NULL,
  `pi_race` varchar(30) COLLATE latin1_general_ci DEFAULT NULL,
  `pi_bloodtype` varchar(30) COLLATE latin1_general_ci DEFAULT NULL,
  `pi_height` varchar(30) COLLATE latin1_general_ci DEFAULT NULL,
  `pi_weight` varchar(30) COLLATE latin1_general_ci DEFAULT NULL,
  `pi_add` text COLLATE latin1_general_ci,
  `pi_telone` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `pi_teltwo` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `pi_mobileone` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `pi_mobiletwo` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `pi_emailone` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `pi_emailtwo` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `pi_tin` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `pi_sss` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `pi_phic` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `pi_hdmf` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `pi_nhmfc` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `pi_passport` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `pi_addwho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `pi_addwhen` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `pi_updatewho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `pi_updatewhen` datetime DEFAULT NULL,
  `pi_age` int(10) unsigned DEFAULT NULL,
  `pi_zipcode` int(10) unsigned DEFAULT NULL,
  `pi_civil` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  PRIMARY KEY (`pi_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `emp_personal_info`
--

LOCK TABLES `emp_personal_info` WRITE;
/*!40000 ALTER TABLE `emp_personal_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `emp_personal_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `emp_position`
--

DROP TABLE IF EXISTS `emp_position`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `emp_position` (
  `post_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `post_code` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `post_name` varchar(150) COLLATE latin1_general_ci DEFAULT NULL,
  `post_desc` text COLLATE latin1_general_ci,
  `post_ord` int(10) unsigned DEFAULT NULL,
  `post_addwho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `post_addwhen` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `post_updatewho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `post_updatewhen` datetime DEFAULT NULL,
  PRIMARY KEY (`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `emp_position`
--

LOCK TABLES `emp_position` WRITE;
/*!40000 ALTER TABLE `emp_position` DISABLE KEYS */;
/*!40000 ALTER TABLE `emp_position` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `emp_type`
--

DROP TABLE IF EXISTS `emp_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `emp_type` (
  `emptype_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `empclass_id` int(10) DEFAULT NULL,
  `emptype_name` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `emptype_rank` int(10) DEFAULT NULL,
  `emptype_ord` int(10) unsigned DEFAULT NULL,
  `emptype_addwho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `emptype_addwhen` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `emptype_updatewho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `emptype_updatewhen` datetime DEFAULT NULL,
  PRIMARY KEY (`emptype_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `emp_type`
--

LOCK TABLES `emp_type` WRITE;
/*!40000 ALTER TABLE `emp_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `emp_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `emp_vw`
--

DROP TABLE IF EXISTS `emp_vw`;
/*!50001 DROP VIEW IF EXISTS `emp_vw`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `emp_vw` (
  `emp_id` int(10) unsigned,
  `ud_id` int(10) unsigned
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `empdec_rel`
--

DROP TABLE IF EXISTS `empdec_rel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `empdec_rel` (
  `empdec_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dec_id` int(10) unsigned NOT NULL,
  `emp_id` int(10) unsigned NOT NULL,
  `sc_id` int(10) unsigned NOT NULL,
  `empdec_effectivedate` date DEFAULT NULL,
  `empdec_stat` tinyint(1) unsigned DEFAULT '0',
  `empdec_remarks` text COLLATE latin1_general_ci,
  PRIMARY KEY (`empdec_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `empdec_rel`
--

LOCK TABLES `empdec_rel` WRITE;
/*!40000 ALTER TABLE `empdec_rel` DISABLE KEYS */;
/*!40000 ALTER TABLE `empdec_rel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `emr_personal_info`
--

DROP TABLE IF EXISTS `emr_personal_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `emr_personal_info` (
  `emrpi_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(10) unsigned NOT NULL,
  `p_id` int(10) unsigned NOT NULL,
  `zipcode_id` int(10) unsigned NOT NULL,
  `emrpi_fname` varchar(60) COLLATE latin1_general_ci DEFAULT NULL,
  `emrpi_mname` varchar(60) COLLATE latin1_general_ci DEFAULT NULL,
  `emrpi_lname` varchar(60) COLLATE latin1_general_ci DEFAULT NULL,
  `emrpi_gender` varchar(6) COLLATE latin1_general_ci DEFAULT NULL,
  `emrpi_bdate` date DEFAULT NULL,
  `emrpi_place_bdate` varchar(30) COLLATE latin1_general_ci DEFAULT NULL,
  `emrpi_nationality` varchar(30) COLLATE latin1_general_ci DEFAULT NULL,
  `emrpi_religion` varchar(30) COLLATE latin1_general_ci DEFAULT NULL,
  `emrpi_race` varchar(30) COLLATE latin1_general_ci DEFAULT NULL,
  `emrpi_bloodtype` varchar(30) COLLATE latin1_general_ci DEFAULT NULL,
  `emrpi_height` varchar(30) COLLATE latin1_general_ci DEFAULT NULL,
  `emrpi_weight` varchar(30) COLLATE latin1_general_ci DEFAULT NULL,
  `emrpi_add` text COLLATE latin1_general_ci,
  `emrpi_telone` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `emrpi_teltwo` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `emrpi_mobileone` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `emrpi_mobiletwo` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `emrpi_emailone` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `emrpi_emailtwo` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `emrpi_tin` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `emrpi_sss` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `emrpi_phic` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `emrpi_hdmf` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `emrpi_nhmfc` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `emrpi_passport` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `emrpi_addwho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `emrpi_addwhen` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `emrpi_updatewho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `emrpi_updatewhen` datetime DEFAULT NULL,
  `emrpi_age` int(10) unsigned DEFAULT NULL,
  `emrpi_zipcode` int(10) unsigned DEFAULT NULL,
  `emrpi_civil` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `emrpi_status` tinyint(2) unsigned DEFAULT '0',
  `emrpi_appdate` date DEFAULT NULL,
  `emrpi_picture` mediumblob,
  `emrpi_ishired` tinyint(2) unsigned DEFAULT '0',
  PRIMARY KEY (`emrpi_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `emr_personal_info`
--

LOCK TABLES `emr_personal_info` WRITE;
/*!40000 ALTER TABLE `emr_personal_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `emr_personal_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `factor_rate`
--

DROP TABLE IF EXISTS `factor_rate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `factor_rate` (
  `fr_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `wrate_id` int(10) NOT NULL,
  `fr_name` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `fr_hrperday` decimal(11,5) unsigned DEFAULT NULL,
  `fr_dayperweek` decimal(11,5) unsigned DEFAULT NULL,
  `fr_dayperyear` decimal(11,5) unsigned DEFAULT NULL,
  `fr_hrperweek` decimal(11,5) unsigned DEFAULT NULL,
  `fr_addwho` varchar(30) COLLATE latin1_general_ci DEFAULT NULL,
  `fr_addwhen` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `fr_updatewho` varchar(30) COLLATE latin1_general_ci DEFAULT NULL,
  `fr_updatewhen` datetime DEFAULT NULL,
  PRIMARY KEY (`fr_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `factor_rate`
--

LOCK TABLES `factor_rate` WRITE;
/*!40000 ALTER TABLE `factor_rate` DISABLE KEYS */;
/*!40000 ALTER TABLE `factor_rate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `import_details`
--

DROP TABLE IF EXISTS `import_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `import_details` (
  `impd_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `imph_id` int(10) unsigned NOT NULL,
  `impd_type` tinyint(2) unsigned DEFAULT NULL,
  `impd_column` int(10) unsigned DEFAULT NULL,
  `impd_addwho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `impd_addwhen` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `impd_updatewho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `impd_updatewhen` datetime DEFAULT NULL,
  PRIMARY KEY (`impd_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `import_details`
--

LOCK TABLES `import_details` WRITE;
/*!40000 ALTER TABLE `import_details` DISABLE KEYS */;
/*!40000 ALTER TABLE `import_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `import_head`
--

DROP TABLE IF EXISTS `import_head`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `import_head` (
  `imph_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `imph_name` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `imph_addwho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `imph_addwhen` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `imph_updatewho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `imph_updatewhen` datetime DEFAULT NULL,
  PRIMARY KEY (`imph_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `import_head`
--

LOCK TABLES `import_head` WRITE;
/*!40000 ALTER TABLE `import_head` DISABLE KEYS */;
/*!40000 ALTER TABLE `import_head` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `import_ytd_details`
--

DROP TABLE IF EXISTS `import_ytd_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `import_ytd_details` (
  `import_ytd_detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `import_ytd_head_id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL,
  `import_ytd_detail_arr` text NOT NULL,
  `import_ytd_detail_addwho` varchar(50) DEFAULT NULL,
  `import_ytd_detail_addwhen` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `import_ytd_detail_updatewho` varchar(50) DEFAULT NULL,
  `import_ytd_detail_updatewhen` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`import_ytd_detail_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `import_ytd_details`
--

LOCK TABLES `import_ytd_details` WRITE;
/*!40000 ALTER TABLE `import_ytd_details` DISABLE KEYS */;
/*!40000 ALTER TABLE `import_ytd_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `import_ytd_head`
--

DROP TABLE IF EXISTS `import_ytd_head`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `import_ytd_head` (
  `import_ytd_head_id` int(11) NOT NULL AUTO_INCREMENT,
  `payperiod_id` int(11) NOT NULL,
  `import_ytd_head_addwhen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `import_ytd_head_addwho` varchar(50) DEFAULT NULL,
  `import_ytd_head_updatewhen` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `import_ytd_head_updatewho` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`import_ytd_head_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `import_ytd_head`
--

LOCK TABLES `import_ytd_head` WRITE;
/*!40000 ALTER TABLE `import_ytd_head` DISABLE KEYS */;
/*!40000 ALTER TABLE `import_ytd_head` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `leave_type`
--

DROP TABLE IF EXISTS `leave_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `leave_type` (
  `leave_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `leave_code` varchar(30) COLLATE latin1_general_ci DEFAULT NULL,
  `leave_name` varchar(100) COLLATE latin1_general_ci DEFAULT NULL,
  `leave_wpay` char(4) COLLATE latin1_general_ci DEFAULT NULL,
  `leave_days` decimal(10,2) DEFAULT NULL,
  `leave_gender` varchar(10) COLLATE latin1_general_ci DEFAULT NULL,
  `leave_conv_cash` char(4) COLLATE latin1_general_ci DEFAULT NULL,
  `leave_addwho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `leave_addwhen` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `leave_updatewho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `leave_updatewhen` datetime DEFAULT NULL,
  PRIMARY KEY (`leave_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leave_type`
--

LOCK TABLES `leave_type` WRITE;
/*!40000 ALTER TABLE `leave_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `leave_type` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 trigger `leaveCreate` AFTER INSERT on `leave_type` 
for each row BEGIN
	INSERT INTO app_formula_keywords (app_fkey_name, leave_id, app_fkey_isactive, app_fkey_query, app_fkey_vars, app_fkey_result) VALUES (NEW.leave_name, NEW.leave_id, 1,CONCAT("select emp_leav_rec_leavedays from emp_leave_rec a join emp_leave b on (b.empleave_id=a.empleave_id) join leave_type c on (c.leave_id=b.leave_id) where a.payperiod_id=? and a.emp_id=? and b.leave_id=",NEW.leave_id),"array($payperiod_id,$emp_id)","emp_leav_rec_leavedays");
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `leaveUpdate` AFTER UPDATE on `leave_type`
FOR EACH ROW BEGIN
	 UPDATE app_formula_keywords SET app_fkey_name = NEW.leave_name WHERE leave_id = NEW.leave_id;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `loan_detail_sum`
--

DROP TABLE IF EXISTS `loan_detail_sum`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `loan_detail_sum` (
  `loansum_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `paystub_id` int(10) unsigned NOT NULL,
  `loan_id` int(10) unsigned NOT NULL,
  `loansum_year` varchar(4) COLLATE latin1_general_ci DEFAULT NULL,
  `loansum_payment` float DEFAULT NULL,
  `loansum_desc` text COLLATE latin1_general_ci,
  `loansum_addwho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `loansum_addwhen` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`loansum_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loan_detail_sum`
--

LOCK TABLES `loan_detail_sum` WRITE;
/*!40000 ALTER TABLE `loan_detail_sum` DISABLE KEYS */;
/*!40000 ALTER TABLE `loan_detail_sum` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loan_info`
--

DROP TABLE IF EXISTS `loan_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `loan_info` (
  `loan_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `loantype_id` int(10) unsigned NOT NULL,
  `psa_id` int(11) NOT NULL,
  `emp_id` int(10) unsigned NOT NULL,
  `loan_voucher_no` varchar(30) COLLATE latin1_general_ci DEFAULT NULL,
  `loan_datepromissory` date DEFAULT NULL,
  `loan_dategrant` date DEFAULT NULL,
  `loan_principal` decimal(10,2) DEFAULT NULL,
  `loan_interestamount` decimal(10,2) DEFAULT NULL,
  `loan_interestperc` decimal(10,2) DEFAULT NULL,
  `loan_monthly_amortization` decimal(10,2) DEFAULT NULL,
  `loan_payperperiod` decimal(10,2) DEFAULT NULL,
  `loan_ytd` decimal(10,2) DEFAULT NULL,
  `loan_balance` decimal(10,2) DEFAULT NULL,
  `loan_startdate` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `loan_enddate` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `loan_addwho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `loan_addwhen` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `loan_updatewho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `loan_updatewhen` datetime DEFAULT NULL,
  `loan_suspend` tinyint(3) unsigned DEFAULT '0',
  `loan_total` decimal(10,2) DEFAULT NULL,
  `loan_numofmonths` int(10) unsigned DEFAULT NULL,
  `loan_periodselection` varchar(10) COLLATE latin1_general_ci DEFAULT NULL,
  PRIMARY KEY (`loan_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loan_info`
--

LOCK TABLES `loan_info` WRITE;
/*!40000 ALTER TABLE `loan_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `loan_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loan_type`
--

DROP TABLE IF EXISTS `loan_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `loan_type` (
  `loantype_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `loantype_code` varchar(2) COLLATE latin1_general_ci DEFAULT NULL,
  `loantype_desc` varchar(100) COLLATE latin1_general_ci DEFAULT NULL,
  `loantype_addwho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `loantype_addwhen` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `loantype_updatewho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `loantype_updatewhen` datetime DEFAULT NULL,
  PRIMARY KEY (`loantype_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loan_type`
--

LOCK TABLES `loan_type` WRITE;
/*!40000 ALTER TABLE `loan_type` DISABLE KEYS */;
INSERT INTO `loan_type` VALUES (1,'S','Salary Loan','admin','2010-09-03 00:09:28','','0000-00-00 00:00:00'),(2,'C','Calamity Loan','admin','2010-09-03 00:09:45','','0000-00-00 00:00:00'),(3,'R','Emergency Loan','admin','2010-09-03 00:10:12','','0000-00-00 00:00:00'),(4,'E','Educational Loan','admin','2010-09-03 00:10:37','','0000-00-00 00:00:00'),(5,'MP','Multi Purpose Loan','admin','2010-09-03 00:10:37','','0000-00-00 00:00:00'),(6,'Co','Company Loan','admin','2011-11-22 05:32:27','','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `loan_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ot_rates`
--

DROP TABLE IF EXISTS `ot_rates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ot_rates` (
  `otr_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `otr_name` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `otr_desc` tinytext COLLATE latin1_general_ci,
  `otr_type` varchar(10) COLLATE latin1_general_ci DEFAULT NULL,
  `otr_factor` decimal(10,5) DEFAULT '0.00000',
  `otr_max` int(10) unsigned DEFAULT '0',
  `otr_addwho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `otr_addwhen` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `otr_updatewho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `otr_updatewhen` datetime DEFAULT NULL,
  `otr_ord` int(11) DEFAULT '0',
  PRIMARY KEY (`otr_id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ot_rates`
--

LOCK TABLES `ot_rates` WRITE;
/*!40000 ALTER TABLE `ot_rates` DISABLE KEYS */;
INSERT INTO `ot_rates` VALUES (1,'Legal Hol','Legal Holiday','Hour',1.00000,0,'','0000-00-00 00:00:00','admin','2012-11-29 01:48:48',0),(2,'ND_RDF8','ND Rest Day First 8 Hours','Hour',1.43000,0,'','0000-00-00 00:00:00','','0000-00-00 00:00:00',0),(3,'ND_RDX8','ND Rest Day excess of 8 Hours','Hour',1.85900,0,'','0000-00-00 00:00:00','admin','2012-11-06 02:16:16',0),(4,'ND_RHF8','ND Regular Holiday First 8 Hours','Hour',1.20000,0,'','0000-00-00 00:00:00','','0000-00-00 00:00:00',0),(5,'ND_RHRDF8','ND Regular Holiday Falls on Rest Day First 8 Hours','Hour',2.86000,0,'','0000-00-00 00:00:00','','0000-00-00 00:00:00',0),(6,'ND_RHRDX8','ND Regular Holiday falls on Rest Day Excess of First 8 Hours','Hour',3.71800,0,'','0000-00-00 00:00:00','','0000-00-00 00:00:00',0),(7,'ND_RHX8','ND Regular Holiday Excess of First 8 Hours','Hour',2.86000,0,'','0000-00-00 00:00:00','','0000-00-00 00:00:00',0),(8,'ND_SHF8','ND Special Holiday First 8 Hours','Hour',1.43000,0,'','0000-00-00 00:00:00','','0000-00-00 00:00:00',0),(9,'ND_SHF8M','ND Special Holiday First 8 Hours - Monthly','Hour',0.43000,0,'','0000-00-00 00:00:00','','0000-00-00 00:00:00',0),(10,'ND_SHRDF8','ND Special Holiday Falls on Rest Day First 8 Hours ','Hour',1.65000,0,'','0000-00-00 00:00:00','','0000-00-00 00:00:00',0),(11,'ND_SHRDX8','ND Special Holiday Falls on Rest Day Excess of First 8 Hours','Hour',2.14500,0,'','0000-00-00 00:00:00','','0000-00-00 00:00:00',0),(12,'ND_SHX8','ND Special Holiday Excess of First 8 Hours','Hour',1.85900,0,'','0000-00-00 00:00:00','admin','2012-11-29 02:00:04',0),(13,'NDF8','ND Regular Day First 8 Hours','Hour',0.10000,0,'','0000-00-00 00:00:00','','0000-00-00 00:00:00',0),(14,'NDX8','ND Regular Day Excess of First 8 Hours','Hour',1.37500,0,'','0000-00-00 00:00:00','','0000-00-00 00:00:00',0),(15,'Rest Day OT','OT Rest Day First 8 Hours','Hour',1.30000,0,'','0000-00-00 00:00:00','admin','2012-11-29 01:45:17',0),(16,'Rest Day OT Excess8HRS','OT Rest Day Excess of First 8 Hours','Hour',1.69000,0,'','0000-00-00 00:00:00','admin','2012-11-29 01:48:12',0),(17,'OT_RHF8','OT Regular Holiday First 8 Hours','Hour',1.00000,0,'','0000-00-00 00:00:00','','0000-00-00 00:00:00',0),(18,'Legal Hol on RD','Legal Holiday on Rest Day','Hour',2.60000,0,'','0000-00-00 00:00:00','admin','2012-11-29 01:49:31',0),(19,'LHRD_OT_>8HRS','Legal Holiday on Rest Day  after 8 Hours','Hour',3.38000,0,'','0000-00-00 00:00:00','','0000-00-00 00:00:00',0),(20,'LH_OT_>8HRS','Legal Holiday after 8 Hours','Hour',2.60000,0,'','0000-00-00 00:00:00','','0000-00-00 00:00:00',0),(21,'Spl Hol First 8hrs','Special Holiday First 8 Hours','Hour',1.30000,0,'','0000-00-00 00:00:00','admin','2012-11-29 01:56:40',0),(22,'SH_OT_HRS_M','Special Holiday First 8 Hours - Monthly','Hour',0.30000,0,'','0000-00-00 00:00:00','','0000-00-00 00:00:00',0),(23,'Spl Hol on RD','Special Holiday on Rest Day','Hour',1.50000,0,'','0000-00-00 00:00:00','admin','2012-11-29 01:57:27',0),(24,'Spl Hol RD after8HRS','Special Holiday on Rest Day  after 8 Hours','Hour',1.95000,0,'','0000-00-00 00:00:00','admin','2012-11-29 01:52:36',0),(25,'Spl Hol after8HRS','Special Holiday after 8 Hours','Hour',1.69000,0,'','0000-00-00 00:00:00','admin','2012-11-29 01:51:49',0),(26,'REG OT','Regular Overtime','Hour',1.25000,0,'','0000-00-00 00:00:00','admin','2012-11-29 01:51:21',0),(27,'CH_OT_HRS','Company Holiday','Hour',1.25000,0,NULL,NULL,NULL,NULL,0),(28,'CH_OT_HRSX8','Company Holiday excess of 8 Hours','Hour',1.25000,0,NULL,NULL,NULL,NULL,0),(29,'ND_CHF8','ND Company Holiday First 8 Hours','Hour',1.25000,0,NULL,NULL,NULL,NULL,0),(30,'ND_CHX8','ND Company Holiday excess of First 8 Hours','Hour',1.25000,0,NULL,NULL,NULL,NULL,0);
/*!40000 ALTER TABLE `ot_rates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ot_record`
--

DROP TABLE IF EXISTS `ot_record`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ot_record` (
  `otrec_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `otr_id` int(10) unsigned NOT NULL,
  `paystub_id` int(10) unsigned NOT NULL,
  `payperiod_id` int(10) unsigned NOT NULL,
  `emp_id` int(10) unsigned NOT NULL,
  `otrec_totalhrs` decimal(10,5) DEFAULT NULL,
  `otrec_subtotal` decimal(10,5) DEFAULT NULL,
  `otrec_addwho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `otrec_addwhen` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `otrec_updatewho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `otrec_updatewhen` datetime DEFAULT NULL,
  `uptadet_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`otrec_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ot_record`
--

LOCK TABLES `ot_record` WRITE;
/*!40000 ALTER TABLE `ot_record` DISABLE KEYS */;
/*!40000 ALTER TABLE `ot_record` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ot_tbl`
--

DROP TABLE IF EXISTS `ot_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ot_tbl` (
  `ot_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ot_name` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `ot_rates` longtext COLLATE latin1_general_ci,
  `ot_desc` varchar(225) COLLATE latin1_general_ci DEFAULT NULL,
  `ot_istax` tinyint(2) DEFAULT '1',
  `ot_addwho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `ot_addwhen` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `ot_updatewho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `ot_updatewhen` datetime DEFAULT NULL,
  PRIMARY KEY (`ot_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ot_tbl`
--

LOCK TABLES `ot_tbl` WRITE;
/*!40000 ALTER TABLE `ot_tbl` DISABLE KEYS */;
/*!40000 ALTER TABLE `ot_tbl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ot_tr`
--

DROP TABLE IF EXISTS `ot_tr`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ot_tr` (
  `ottr_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ot_id` int(10) unsigned DEFAULT NULL,
  `otr_id` int(10) unsigned DEFAULT NULL,
  `ottr_addwho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `ottr_addwhen` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `ottr_updatewho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `ottr_updatewhen` datetime DEFAULT NULL,
  PRIMARY KEY (`ottr_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ot_tr`
--

LOCK TABLES `ot_tr` WRITE;
/*!40000 ALTER TABLE `ot_tr` DISABLE KEYS */;
/*!40000 ALTER TABLE `ot_tr` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pay_period`
--

DROP TABLE IF EXISTS `pay_period`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pay_period` (
  `payperiod_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`payperiod_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pay_period`
--

LOCK TABLES `pay_period` WRITE;
/*!40000 ALTER TABLE `pay_period` DISABLE KEYS */;
/*!40000 ALTER TABLE `pay_period` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payroll_bank_details`
--

DROP TABLE IF EXISTS `payroll_bank_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payroll_bank_details` (
  `pbd_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pbd_bank_name` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `pbd_routing_number` int(10) unsigned DEFAULT NULL,
  `pbd_company_code` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `pbd_company_accountno` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `pbd_ceiling_amount` decimal(10,2) DEFAULT NULL,
  `pbd_official_tresurer` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `pbd_official_president` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `pbd_official_director` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `pbd_official_finance` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  PRIMARY KEY (`pbd_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payroll_bank_details`
--

LOCK TABLES `payroll_bank_details` WRITE;
/*!40000 ALTER TABLE `payroll_bank_details` DISABLE KEYS */;
/*!40000 ALTER TABLE `payroll_bank_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payroll_bank_report_data`
--

DROP TABLE IF EXISTS `payroll_bank_report_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payroll_bank_report_data` (
  `pbrd_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pbr_id` int(10) unsigned NOT NULL,
  `pbrd_hash_pdf` mediumblob,
  `pbrd_hash_txt` mediumblob,
  PRIMARY KEY (`pbrd_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payroll_bank_report_data`
--

LOCK TABLES `payroll_bank_report_data` WRITE;
/*!40000 ALTER TABLE `payroll_bank_report_data` DISABLE KEYS */;
/*!40000 ALTER TABLE `payroll_bank_report_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payroll_bank_reports`
--

DROP TABLE IF EXISTS `payroll_bank_reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payroll_bank_reports` (
  `pbr_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `payperiod_id` int(10) unsigned NOT NULL,
  `bank_id` int(10) unsigned NOT NULL,
  `pbr_batchno` varchar(4) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `pbr_bank_name` varchar(50) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `pbr_routing_number` varchar(50) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `pbr_company_code` varchar(50) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `pbr_company_accountname` varchar(200) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `pbr_company_accountno` varchar(50) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `pbr_ceiling_amount` varchar(50) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `pbr_credit_date` date DEFAULT NULL,
  `pbr_prepared_by` varchar(225) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `pbr_prepared_pos` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `pbr_approved_by` varchar(225) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `pbr_approved_pos` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `pbr_addwho` varchar(100) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `pbr_addwhen` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `pbr_report_type` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`pbr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payroll_bank_reports`
--

LOCK TABLES `payroll_bank_reports` WRITE;
/*!40000 ALTER TABLE `payroll_bank_reports` DISABLE KEYS */;
/*!40000 ALTER TABLE `payroll_bank_reports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payroll_calculation`
--

DROP TABLE IF EXISTS `payroll_calculation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payroll_calculation` (
  `cal_id` int(11) NOT NULL AUTO_INCREMENT,
  `cal_name` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `cal_order` int(11) NOT NULL,
  PRIMARY KEY (`cal_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payroll_calculation`
--

LOCK TABLES `payroll_calculation` WRITE;
/*!40000 ALTER TABLE `payroll_calculation` DISABLE KEYS */;
/*!40000 ALTER TABLE `payroll_calculation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payroll_comp`
--

DROP TABLE IF EXISTS `payroll_comp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payroll_comp` (
  `pc_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `emp_id` int(10) unsigned NOT NULL,
  `ot_id` int(10) DEFAULT '0',
  `fr_id` int(10) DEFAULT '0',
  `pps_id` int(10) DEFAULT '0',
  `pc_addwho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `pc_addwhen` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pc_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payroll_comp`
--

LOCK TABLES `payroll_comp` WRITE;
/*!40000 ALTER TABLE `payroll_comp` DISABLE KEYS */;
/*!40000 ALTER TABLE `payroll_comp` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payroll_pay_period`
--

DROP TABLE IF EXISTS `payroll_pay_period`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payroll_pay_period` (
  `payperiod_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pps_id` int(10) unsigned NOT NULL,
  `payperiod_status_id` int(10) unsigned NOT NULL,
  `payperiod_period` int(5) DEFAULT NULL,
  `payperiod_period_to` int(5) DEFAULT NULL,
  `payperiod_period_year` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
  `payperiod_period_year_to` varchar(10) COLLATE latin1_general_ci DEFAULT NULL,
  `payperiod_is_primary` tinyint(1) unsigned NOT NULL,
  `payperiod_addwho` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `payperiod_addwhen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `payperiod_updatewhen` datetime NOT NULL,
  `payperiod_updatewho` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `payperiod_start_date` datetime NOT NULL,
  `payperiod_end_date` datetime NOT NULL,
  `payperiod_trans_date` datetime NOT NULL,
  `payperiod_adv_end_date` datetime NOT NULL,
  `payperiod_adv_trans_date` datetime NOT NULL,
  `payperiod_tainted` tinyint(1) unsigned NOT NULL,
  `payperiod_taintedwho` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `payperiod_taintedwhen` datetime NOT NULL,
  `pp_stat_id` int(10) unsigned DEFAULT NULL,
  `payperiod_freq` tinyint(2) DEFAULT '1',
  `payperiod_type` tinyint(2) DEFAULT '1',
  `is_payslip_viewable` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`payperiod_id`),
  KEY `payroll_pay_period_FKIndex1` (`pps_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payroll_pay_period`
--

LOCK TABLES `payroll_pay_period` WRITE;
/*!40000 ALTER TABLE `payroll_pay_period` DISABLE KEYS */;
/*!40000 ALTER TABLE `payroll_pay_period` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payroll_pay_period_sched`
--

DROP TABLE IF EXISTS `payroll_pay_period_sched`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payroll_pay_period_sched` (
  `pps_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fr_id` int(10) unsigned NOT NULL,
  `comp_id` int(10) unsigned NOT NULL,
  `tt_pay_group` int(10) unsigned NOT NULL,
  `pps_name` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `pps_desc` varchar(225) COLLATE latin1_general_ci NOT NULL,
  `salaryclass_id` int(11) unsigned NOT NULL,
  `pps_pri_dateldom` int(10) unsigned NOT NULL,
  `pps_pri_trans_dateldom` int(10) unsigned NOT NULL,
  `pps_pri_trans_datebd` int(10) unsigned NOT NULL,
  `pps_secnd_dateldom` int(10) unsigned NOT NULL,
  `pps_desc_2` int(10) unsigned NOT NULL,
  `pps_secnd_trans_datebd` int(10) unsigned NOT NULL,
  `pps_addwho` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `pps_addwhen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `pps_updatewho` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `pps_updatewhen` datetime NOT NULL,
  `pps_anchor_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `pps_primary_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `pps_primary_trans_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `pps_secnd_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `pps_secnd_trans_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `pps_day_start_time` int(10) unsigned NOT NULL,
  `pps_day_continuous_time` int(10) unsigned NOT NULL,
  `pps_start_week_day` int(10) unsigned NOT NULL,
  `pps_start_day_week` int(10) unsigned NOT NULL,
  `pps_trans_date` int(10) unsigned NOT NULL,
  `pps_pri_daymonth` varchar(10) COLLATE latin1_general_ci NOT NULL,
  `pps_secnd_daymonth` varchar(10) COLLATE latin1_general_ci NOT NULL,
  `pps_pri_trans_daymonth` varchar(10) COLLATE latin1_general_ci NOT NULL,
  `pps_secnd_trans_daymonth` varchar(10) COLLATE latin1_general_ci NOT NULL,
  `pps_trans_datebd` int(10) unsigned NOT NULL,
  `pps_time_zone` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `pps_new_daytrigger_time` int(10) unsigned NOT NULL,
  `pps_max_shifttime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`pps_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payroll_pay_period_sched`
--

LOCK TABLES `payroll_pay_period_sched` WRITE;
/*!40000 ALTER TABLE `payroll_pay_period_sched` DISABLE KEYS */;
/*!40000 ALTER TABLE `payroll_pay_period_sched` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payroll_pay_stub`
--

DROP TABLE IF EXISTS `payroll_pay_stub`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payroll_pay_stub` (
  `paystub_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `payperiod_id` int(10) unsigned NOT NULL,
  `paystub_status_id` int(10) unsigned NOT NULL,
  `paystub_status_date` int(10) unsigned NOT NULL,
  `paystub_status_by` int(10) unsigned NOT NULL,
  `paystub_advance` int(10) unsigned NOT NULL,
  `paystub_addwho` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `paystub_addwhen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `paystub_updatewho` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `paystub_updatewhen` datetime NOT NULL,
  `paystub_start_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `paystub_end_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `paystub_trans_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `paystub_tainted` int(10) unsigned NOT NULL,
  `paystub_temp` int(10) unsigned NOT NULL,
  `paystub_currency_id` int(10) unsigned NOT NULL,
  `paystub_currency_rate` float(18,10) NOT NULL,
  `emp_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`paystub_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payroll_pay_stub`
--

LOCK TABLES `payroll_pay_stub` WRITE;
/*!40000 ALTER TABLE `payroll_pay_stub` DISABLE KEYS */;
/*!40000 ALTER TABLE `payroll_pay_stub` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payroll_paystub_entry`
--

DROP TABLE IF EXISTS `payroll_paystub_entry`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payroll_paystub_entry` (
  `ppe_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `psa_id` int(11) NOT NULL,
  `paystub_id` int(10) unsigned NOT NULL,
  `ppe_rate` decimal(10,2) NOT NULL,
  `ppe_units` decimal(10,2) NOT NULL,
  `ppe_ytd_units` decimal(10,2) NOT NULL,
  `ppe_amount` decimal(10,2) NOT NULL,
  `ppe_amount_employer` decimal(10,2) NOT NULL,
  `ppe_ytd_amount` decimal(10,2) NOT NULL,
  `ppe_addwhen` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `ppe_addwho` varchar(35) COLLATE latin1_general_ci DEFAULT NULL,
  `ppe_updatewho` varchar(35) COLLATE latin1_general_ci DEFAULT NULL,
  `ppe_updatewhen` datetime DEFAULT NULL,
  `ppe_isedited` tinyint(1) unsigned DEFAULT '0',
  PRIMARY KEY (`ppe_id`),
  KEY `payroll_paystub_entry_FKIndex1` (`paystub_id`),
  KEY `payroll_paystub_entry_FKIndex2` (`psa_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payroll_paystub_entry`
--

LOCK TABLES `payroll_paystub_entry` WRITE;
/*!40000 ALTER TABLE `payroll_paystub_entry` DISABLE KEYS */;
/*!40000 ALTER TABLE `payroll_paystub_entry` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payroll_paystub_report`
--

DROP TABLE IF EXISTS `payroll_paystub_report`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payroll_paystub_report` (
  `ppr_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `paystub_id` int(10) unsigned NOT NULL,
  `payperiod_id` int(10) unsigned NOT NULL,
  `emp_id` int(10) unsigned DEFAULT NULL,
  `ud_id` int(10) unsigned DEFAULT NULL,
  `comp_id` int(10) unsigned DEFAULT NULL,
  `ppr_paystubdetails` text COLLATE latin1_general_ci,
  `ppr_addwhen` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `ppr_addwho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `ppr_updatewhen` datetime DEFAULT NULL,
  `ppr_updatewho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `ppr_status` tinyint(1) unsigned DEFAULT '1',
  `ppr_isdeleted` tinyint(1) unsigned DEFAULT '0',
  PRIMARY KEY (`ppr_id`),
  KEY `payroll_paystub_report_FKIndex1` (`payperiod_id`),
  KEY `payroll_paystub_report_FKIndex2` (`paystub_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payroll_paystub_report`
--

LOCK TABLES `payroll_paystub_report` WRITE;
/*!40000 ALTER TABLE `payroll_paystub_report` DISABLE KEYS */;
/*!40000 ALTER TABLE `payroll_paystub_report` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payroll_pps_user`
--

DROP TABLE IF EXISTS `payroll_pps_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payroll_pps_user` (
  `ppsu_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pps_id` int(10) unsigned NOT NULL,
  `emp_id` int(10) unsigned NOT NULL,
  `ppsu_addwho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `ppsu_addwhen` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ppsu_id`),
  KEY `payroll_pps_user_FKIndex1` (`pps_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payroll_pps_user`
--

LOCK TABLES `payroll_pps_user` WRITE;
/*!40000 ALTER TABLE `payroll_pps_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `payroll_pps_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payroll_priority_comp`
--

DROP TABLE IF EXISTS `payroll_priority_comp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payroll_priority_comp` (
  `ppc_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `psachild_id` int(10) unsigned NOT NULL,
  `psa_id` int(11) NOT NULL,
  `comp_id` int(10) unsigned NOT NULL,
  `ppc_priority_no` tinyint(3) unsigned DEFAULT NULL,
  `ppc_stat` tinyint(1) unsigned DEFAULT '0',
  PRIMARY KEY (`ppc_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payroll_priority_comp`
--

LOCK TABLES `payroll_priority_comp` WRITE;
/*!40000 ALTER TABLE `payroll_priority_comp` DISABLE KEYS */;
/*!40000 ALTER TABLE `payroll_priority_comp` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payroll_ps_account`
--

DROP TABLE IF EXISTS `payroll_ps_account`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payroll_ps_account` (
  `psa_id` int(11) NOT NULL AUTO_INCREMENT,
  `comp_id` int(10) unsigned NOT NULL,
  `psa_status` int(11) NOT NULL,
  `psa_type` int(11) NOT NULL,
  `psa_order` int(11) NOT NULL,
  `psa_priority` tinyint(3) unsigned DEFAULT '0',
  `psa_name` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `psa_accrual` int(11) NOT NULL,
  `psa_debit_accnt` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `psa_credit_accnt` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `psa_isloan` tinyint(1) unsigned DEFAULT '0',
  `psa_addwho` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `psa_addwhen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `psa_updatedwho` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `psa_updatedwhen` datetime NOT NULL,
  `psa_tax` tinyint(1) unsigned DEFAULT '0',
  `psa_statutory` tinyint(1) unsigned DEFAULT '0',
  `psa_clsfication` int(11) unsigned DEFAULT '0',
  `psa_procode` tinyint(1) DEFAULT '3',
  `psa_formula` varchar(300) COLLATE latin1_general_ci DEFAULT NULL,
  `psa_formula_el` text COLLATE latin1_general_ci,
  PRIMARY KEY (`psa_id`)
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payroll_ps_account`
--

LOCK TABLES `payroll_ps_account` WRITE;
/*!40000 ALTER TABLE `payroll_ps_account` DISABLE KEYS */;
INSERT INTO `payroll_ps_account` VALUES (1,1,1,4,301,0,'Basic Pay',0,'','',0,'jay','2008-11-06 03:35:43','jimabignay','2011-12-13 10:56:50',0,0,0,3,NULL,NULL),(2,1,1,4,300,0,'Total Deductions',0,'','',0,'jay','2008-11-07 01:46:38','admin','2011-10-21 02:44:59',0,0,0,3,NULL,NULL),(4,1,1,4,315,0,'Total Gross',0,'','',0,'jay','2008-11-13 02:00:39','admin','2012-01-12 02:46:48',0,0,0,3,NULL,NULL),(5,1,1,4,301,0,'Net Pay',0,'','',0,'jay','2008-11-13 02:03:32','admin','2011-10-21 02:45:12',0,0,0,3,NULL,NULL),(6,1,1,4,302,0,'Employer Total Contributions',0,'','',0,'jay','2008-11-13 02:04:55','admin','2011-10-21 02:45:22',0,0,0,3,NULL,NULL),(7,1,1,2,214,0,'SSS',0,'','',0,'jay','2009-01-19 02:54:34','admin','2011-12-06 04:38:15',0,1,4,3,NULL,NULL),(8,1,1,2,213,0,'W/H TAX',0,'','',0,'jay','2009-01-19 02:57:42','jimabignay','2012-02-29 04:23:53',0,0,4,3,NULL,NULL),(9,1,1,2,280,0,'SSS Loan',0,'','',1,'jay','2009-03-01 18:10:03','admin','2011-10-26 03:30:10',0,0,5,3,NULL,NULL),(11,1,1,2,282,0,'Personal Loan',0,'','',0,'jay','2010-03-30 03:03:00','jimabignay','2012-02-29 04:24:50',0,0,5,3,NULL,NULL),(13,1,1,2,281,0,'HDMF Loan',0,'','',1,'jayadmin','2010-08-05 09:17:32','admin','2011-10-26 03:30:18',0,0,5,3,NULL,NULL),(14,1,1,2,215,0,'PHIC',0,'','',0,'jayadmin','2010-10-28 05:06:52','admin','2011-12-06 04:38:23',0,1,4,3,NULL,NULL),(15,1,1,2,216,0,'HDMF',0,'','',0,'jayadmin','2010-10-28 05:07:44','admin','2011-12-06 04:38:32',0,1,4,3,NULL,NULL),(16,1,1,4,303,0,'Overtime',0,'','',0,'admin','2011-10-25 01:45:02','jimabignay','2012-03-08 01:14:28',0,0,0,4,NULL,NULL),(17,1,1,4,304,0,'Total TA',0,'','',0,'admin','2011-10-25 01:45:46','','0000-00-00 00:00:00',0,0,0,3,NULL,NULL),(25,1,1,4,305,0,'Other Statutory Income',0,'','',0,'admin','2011-11-02 02:47:16','','0000-00-00 00:00:00',0,0,0,3,NULL,NULL),(26,1,1,4,306,0,'OtherStat&TaxIncome',0,'','',0,'admin','2011-11-02 02:47:56','jimabignay','2012-03-08 01:15:29',0,0,0,4,NULL,NULL),(27,1,1,4,307,0,'Statutory Contrib',0,'','',0,'admin','2011-11-02 02:48:49','jimabignay','2012-03-08 01:17:24',0,0,0,2,NULL,NULL),(28,1,1,4,308,0,'Other Taxable Income',0,'','',0,'admin','2011-11-02 02:49:42','jimabignay','2012-03-08 01:16:23',0,0,0,4,NULL,NULL),(29,1,1,4,309,0,'Other Taxable Deduction',0,'','',0,'admin','2011-11-02 02:50:05','','0000-00-00 00:00:00',0,0,0,3,NULL,NULL),(30,1,1,4,310,0,'Taxable Income Gross ',0,'','',0,'admin','2011-11-02 02:50:24','','0000-00-00 00:00:00',0,0,0,3,NULL,NULL),(31,1,1,4,311,0,'NonTaxable Income',0,'','',0,'admin','2011-11-02 02:52:34','','0000-00-00 00:00:00',0,0,0,3,NULL,NULL),(32,1,1,4,312,0,'Other Deduction',0,'','',0,'admin','2011-11-02 02:53:50','','0000-00-00 00:00:00',0,0,0,3,NULL,NULL),(33,1,1,4,313,0,'Other Statutory Deduction',0,'','',0,'admin','2011-11-02 03:19:31','','0000-00-00 00:00:00',0,0,0,3,NULL,NULL),(34,1,1,4,314,0,'Other Stat & Tax Deduction',0,'','',0,'admin','2011-11-02 03:20:06','','0000-00-00 00:00:00',0,0,0,3,NULL,NULL),(39,1,1,4,301,0,'COLA',0,'','',0,'admin','2011-12-12 06:01:57','jimabignay','2011-12-13 10:58:07',0,1,0,3,NULL,NULL);
/*!40000 ALTER TABLE `payroll_ps_account` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payroll_ps_account_link`
--

DROP TABLE IF EXISTS `payroll_ps_account_link`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payroll_ps_account_link` (
  `psal_id` int(11) NOT NULL AUTO_INCREMENT,
  `psal_company_id` int(11) NOT NULL,
  `psal_total_gross` int(11) NOT NULL,
  `psal_emp_deduc` int(11) NOT NULL,
  `psal_empr_deduc` int(11) NOT NULL,
  `psal_netpay` int(11) NOT NULL,
  `psal_regtime` int(11) NOT NULL,
  `psal_monthly_adv` int(11) NOT NULL,
  `psal_monthly_adv_deduc` int(11) NOT NULL,
  `psal_emp_cpp` int(11) NOT NULL,
  `psal_emp_ei` int(11) NOT NULL,
  `psal_addwho` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `psal_addwhen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `psal_updatedwho` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `psal_updatedwhen` datetime NOT NULL,
  PRIMARY KEY (`psal_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payroll_ps_account_link`
--

LOCK TABLES `payroll_ps_account_link` WRITE;
/*!40000 ALTER TABLE `payroll_ps_account_link` DISABLE KEYS */;
/*!40000 ALTER TABLE `payroll_ps_account_link` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payroll_ps_amendemp`
--

DROP TABLE IF EXISTS `payroll_ps_amendemp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payroll_ps_amendemp` (
  `amendemp_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `emp_id` int(10) unsigned NOT NULL,
  `paystub_id` int(11) unsigned NOT NULL,
  `psamend_id` int(11) unsigned NOT NULL,
  `amendemp_amount` decimal(11,2) DEFAULT '0.00',
  `amendemp_addwho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `amendemp_addwhen` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`amendemp_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payroll_ps_amendemp`
--

LOCK TABLES `payroll_ps_amendemp` WRITE;
/*!40000 ALTER TABLE `payroll_ps_amendemp` DISABLE KEYS */;
/*!40000 ALTER TABLE `payroll_ps_amendemp` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payroll_ps_amendment`
--

DROP TABLE IF EXISTS `payroll_ps_amendment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payroll_ps_amendment` (
  `psamend_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pps_id` int(10) unsigned NOT NULL,
  `psa_id` int(11) NOT NULL,
  `psamend_name` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `psamend_status` int(1) unsigned NOT NULL DEFAULT '1',
  `psamend_effect_date` date NOT NULL,
  `psamend_rate` float(9,2) NOT NULL DEFAULT '0.00',
  `psamend_units` float(9,2) NOT NULL DEFAULT '0.00',
  `psamend_amount` float(9,2) NOT NULL DEFAULT '0.00',
  `psamend_desc` varchar(225) COLLATE latin1_general_ci NOT NULL,
  `psamend_authorized` int(10) unsigned NOT NULL,
  `psamend_addwho` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `psamend_addwhen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `psamend_updatewho` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `psamend_updatewhen` datetime NOT NULL,
  `psamend_recurring_psamend_id` int(10) unsigned NOT NULL,
  `psamend_ytd_adj` int(1) unsigned NOT NULL,
  `psamend_type_id` int(10) unsigned NOT NULL,
  `psamend_percent_amount` int(10) NOT NULL,
  `psamend_percent_of` int(11) unsigned NOT NULL,
  `emp_id` int(11) unsigned NOT NULL,
  `psamend_istaxable` tinyint(1) unsigned DEFAULT '0',
  PRIMARY KEY (`psamend_id`),
  KEY `payroll_ps_amendment_FKIndex2` (`psa_id`),
  KEY `payroll_ps_amendment_FKIndex3` (`pps_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payroll_ps_amendment`
--

LOCK TABLES `payroll_ps_amendment` WRITE;
/*!40000 ALTER TABLE `payroll_ps_amendment` DISABLE KEYS */;
/*!40000 ALTER TABLE `payroll_ps_amendment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payroll_psa_child`
--

DROP TABLE IF EXISTS `payroll_psa_child`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payroll_psa_child` (
  `psachild_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `psa_id` int(11) NOT NULL,
  `psachild_status` int(11) unsigned DEFAULT NULL,
  `psachild_type` int(11) unsigned DEFAULT NULL,
  `psachild_order` int(11) unsigned DEFAULT NULL,
  `psachild_priority` tinyint(3) unsigned DEFAULT '0',
  `psachild_name` varchar(100) COLLATE latin1_general_ci DEFAULT NULL,
  `psachild_addwho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `psachild_addwhen` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `psachild_updatewho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `psachild_updatewhen` datetime DEFAULT NULL,
  PRIMARY KEY (`psachild_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payroll_psa_child`
--

LOCK TABLES `payroll_psa_child` WRITE;
/*!40000 ALTER TABLE `payroll_psa_child` DISABLE KEYS */;
/*!40000 ALTER TABLE `payroll_psa_child` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payroll_psa_recurring`
--

DROP TABLE IF EXISTS `payroll_psa_recurring`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payroll_psa_recurring` (
  `psar_id` int(11) NOT NULL AUTO_INCREMENT,
  `psar_company_id` int(11) NOT NULL,
  `psar_status` int(11) NOT NULL,
  `psar_startdate` date NOT NULL,
  `psar_enddate` date NOT NULL,
  `psar_frequency_id` int(11) NOT NULL,
  `psar_name` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `psar_desc` varchar(225) COLLATE latin1_general_ci NOT NULL,
  `psar_psename_id` int(11) NOT NULL,
  `psar_rate` float(9,2) NOT NULL,
  `psar_units` float(9,2) NOT NULL,
  `psar_amount` float(9,2) NOT NULL,
  `psar_percent_amnt` float(9,2) NOT NULL,
  `psar_percent_of` int(11) NOT NULL,
  `psar_amend_desc` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `psar_addwho` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `psar_addwhen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `psar_updatedwho` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `psar_updatedwhen` datetime NOT NULL,
  `psar_type_id` int(11) NOT NULL,
  `psar_istaxable` tinyint(3) unsigned DEFAULT NULL,
  PRIMARY KEY (`psar_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payroll_psa_recurring`
--

LOCK TABLES `payroll_psa_recurring` WRITE;
/*!40000 ALTER TABLE `payroll_psa_recurring` DISABLE KEYS */;
/*!40000 ALTER TABLE `payroll_psa_recurring` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payroll_psar_user`
--

DROP TABLE IF EXISTS `payroll_psar_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payroll_psar_user` (
  `psaru_id` int(11) NOT NULL,
  `psar_id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL,
  `psaru_addwho` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `psaru_addwhen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`psaru_id`),
  KEY `payroll_psar_user_FKIndex1` (`psar_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payroll_psar_user`
--

LOCK TABLES `payroll_psar_user` WRITE;
/*!40000 ALTER TABLE `payroll_psar_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `payroll_psar_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `period_benloanduc_sched`
--

DROP TABLE IF EXISTS `period_benloanduc_sched`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `period_benloanduc_sched` (
  `bldsched_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `empdd_id` int(10) unsigned NOT NULL,
  `ben_id` int(10) unsigned NOT NULL,
  `pps_id` int(10) unsigned NOT NULL,
  `emp_id` int(10) unsigned NOT NULL,
  `loan_id` int(10) unsigned NOT NULL,
  `bldsched_period` int(10) unsigned DEFAULT NULL,
  `percent_tax` int(10) DEFAULT '0',
  `s_ltu` int(10) DEFAULT '0',
  `s_stat` int(10) DEFAULT '0',
  PRIMARY KEY (`bldsched_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `period_benloanduc_sched`
--

LOCK TABLES `period_benloanduc_sched` WRITE;
/*!40000 ALTER TABLE `period_benloanduc_sched` DISABLE KEYS */;
/*!40000 ALTER TABLE `period_benloanduc_sched` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `psa_vw`
--

DROP TABLE IF EXISTS `psa_vw`;
/*!50001 DROP VIEW IF EXISTS `psa_vw`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `psa_vw` (
  `psa_id` int(10) unsigned,
  `psa_name` varchar(100),
  `psa_type` int(11)
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `salary_info`
--

DROP TABLE IF EXISTS `salary_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `salary_info` (
  `salaryinfo_id` varchar(20) CHARACTER SET latin1 NOT NULL,
  `salarytype_id` int(10) unsigned NOT NULL,
  `emp_id` int(10) unsigned NOT NULL,
  `fr_id` int(10) unsigned NOT NULL,
  `salaryinfo_effectdate` date DEFAULT NULL,
  `salaryinfo_basicrate` decimal(10,2) DEFAULT NULL,
  `salaryinfo_isactive` tinyint(1) unsigned DEFAULT '1',
  `salaryinfo_addwho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `salaryinfo_addwhen` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `salaryinfo_updatewho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `salaryinfo_updatewhen` datetime DEFAULT NULL,
  `salaryinfo_ceilingpay` decimal(10,2) DEFAULT '0.00',
  `salaryinfo_ecola` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`salaryinfo_id`),
  KEY `salary_info_FKIndex1` (`emp_id`),
  KEY `salary_info_FKIndex2` (`salarytype_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `salary_info`
--

LOCK TABLES `salary_info` WRITE;
/*!40000 ALTER TABLE `salary_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `salary_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `salary_type`
--

DROP TABLE IF EXISTS `salary_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `salary_type` (
  `salarytype_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `salarytype_name` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `salarytype_ord` int(10) unsigned DEFAULT NULL,
  `salarytype_addwho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `salarytype_addwhen` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `salarytype_updatewho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `salarytype_updatewhen` datetime DEFAULT NULL,
  PRIMARY KEY (`salarytype_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `salary_type`
--

LOCK TABLES `salary_type` WRITE;
/*!40000 ALTER TABLE `salary_type` DISABLE KEYS */;
INSERT INTO `salary_type` VALUES (1,'Hourly',10,'jay','2009-09-21 23:04:10','','0000-00-00 00:00:00'),(2,'Daily',20,'jay','2009-09-21 23:04:40','','0000-00-00 00:00:00'),(3,'Weekly',30,'jay','2009-09-21 23:05:05','','0000-00-00 00:00:00'),(4,'Bi-Weekly',40,'jay','2009-09-21 23:05:30','','0000-00-00 00:00:00'),(5,'Monthly',50,'jay','2009-09-21 23:05:45','','0000-00-00 00:00:00'),(6,'Annual',60,'jay','2009-09-21 23:06:01','','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `salary_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sc_records`
--

DROP TABLE IF EXISTS `sc_records`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sc_records` (
  `scr_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sc_id` int(10) unsigned NOT NULL,
  `min_salary` decimal(12,2) unsigned DEFAULT NULL,
  `max_salary` decimal(12,2) unsigned DEFAULT NULL,
  `min_age` int(10) unsigned DEFAULT NULL,
  `max_age` int(10) unsigned DEFAULT NULL,
  `scr_ee` decimal(12,2) DEFAULT NULL,
  `scr_er` decimal(12,2) DEFAULT NULL,
  `scr_ec` float DEFAULT NULL,
  `scr_pcent` float DEFAULT '0',
  `scr_pcentamnt` decimal(12,2) DEFAULT '0.00',
  PRIMARY KEY (`scr_id`),
  KEY `sc_records_FKIndex1` (`sc_id`)
) ENGINE=MyISAM AUTO_INCREMENT=153 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sc_records`
--

LOCK TABLES `sc_records` WRITE;
/*!40000 ALTER TABLE `sc_records` DISABLE KEYS */;
INSERT INTO `sc_records` VALUES (1,2,2750.00,3249.99,0,99,100.00,212.00,10,0,0.00),(2,2,2250.00,2749.99,0,99,83.30,176.70,10,0,0.00),(3,2,1750.00,2249.99,0,99,66.70,141.30,10,0,0.00),(4,2,1250.00,1749.99,0,99,50.00,106.00,10,0,0.00),(5,2,1000.00,1249.99,0,99,33.30,70.70,10,0,0.00),(6,2,3250.00,3749.99,0,99,116.70,247.30,10,0,0.00),(7,2,3750.00,4249.99,0,99,133.30,282.70,10,0,0.00),(8,2,4250.00,4749.99,0,99,150.00,318.00,10,0,0.00),(9,2,4750.00,5249.99,0,99,166.70,353.30,10,0,0.00),(10,2,5250.00,5749.99,0,99,183.30,388.70,10,0,0.00),(11,1,0.00,4999.99,0,99,50.00,50.00,1,1.25,0.00),(12,1,5000.00,5999.99,0,99,62.50,62.50,2,1.25,0.00),(13,1,6000.00,6999.99,0,99,75.00,75.00,3,1.25,0.00),(14,1,7000.00,7999.99,0,99,87.50,87.50,4,1.25,0.00),(15,1,8000.00,8999.99,0,99,100.00,100.00,5,1.25,0.00),(16,1,9000.00,9999.99,0,99,112.50,112.50,6,1.25,0.00),(17,1,10000.00,10999.99,0,99,125.00,125.00,7,1.25,0.00),(18,1,11000.00,11999.99,0,99,137.50,137.50,8,1.25,0.00),(19,1,12000.00,12999.99,0,99,150.00,150.00,9,1.25,0.00),(20,1,13000.00,13999.99,0,99,162.50,162.50,10,1.25,0.00),(21,1,14000.00,14999.99,0,99,175.00,175.00,11,1.25,0.00),(22,1,15000.00,15999.99,0,99,187.50,187.50,12,1.25,0.00),(23,1,16000.00,16999.99,0,99,200.00,200.00,13,1.25,0.00),(24,1,17000.00,17999.99,0,99,212.50,212.50,14,1.25,0.00),(25,2,5750.00,6249.99,0,99,200.00,424.00,10,0,0.00),(26,2,6250.00,6749.99,0,99,216.70,459.30,10,0,0.00),(27,2,6750.00,7249.99,0,99,233.30,494.70,10,0,0.00),(28,2,7250.00,7749.99,0,99,250.00,530.00,10,0,0.00),(29,2,7750.00,8249.99,0,99,266.70,565.30,10,0,0.00),(30,2,8250.00,8749.99,0,99,283.30,600.70,10,0,0.00),(31,2,8750.00,9249.99,0,99,300.00,636.00,10,0,0.00),(32,2,9250.00,9749.99,0,99,316.70,671.30,10,0,0.00),(33,2,9750.00,10249.99,0,99,333.30,706.70,10,0,0.00),(34,2,10250.00,10749.99,0,99,350.00,742.00,10,0,0.00),(35,2,10750.00,11249.99,0,99,366.70,777.30,10,0,0.00),(36,2,11250.00,11749.99,0,99,383.30,812.70,10,0,0.00),(37,2,11750.00,12249.99,0,99,400.00,848.00,10,0,0.00),(38,2,12250.00,12749.99,0,99,416.70,883.30,10,0,0.00),(39,2,12750.00,13249.99,0,99,433.30,918.70,10,0,0.00),(40,2,13250.00,13749.99,0,99,450.00,954.00,10,0,0.00),(41,2,13750.00,14249.99,0,99,466.70,989.30,10,0,0.00),(42,2,14250.00,14749.99,0,99,483.30,1024.70,10,0,0.00),(43,2,14750.00,9999999.99,0,99,500.00,1060.00,30,0,0.00),(44,3,0.00,1501.00,0,99,100.00,100.00,0,0,0.00),(45,3,1501.00,5001.00,0,99,100.00,100.00,0,0,0.00),(46,3,5001.00,999999.00,0,99,100.00,100.00,0,0,0.00),(47,1,18000.00,18999.99,0,99,225.00,225.00,15,1.25,0.00),(48,1,19000.00,19999.99,0,99,237.50,237.50,16,1.25,0.00),(49,1,20000.00,20999.99,0,99,250.00,250.00,17,1.25,0.00),(50,1,21000.00,21999.99,0,99,262.50,262.50,18,1.25,0.00),(51,1,22000.00,22999.99,0,99,275.00,275.00,19,1.25,0.00),(52,1,23000.00,23999.99,0,99,287.50,287.50,20,1.25,0.00),(53,1,24000.00,24999.99,0,99,300.00,300.00,21,1.25,0.00),(54,1,25000.00,25999.99,0,99,312.50,312.50,22,1.25,0.00),(55,1,26000.00,26999.99,0,99,325.00,325.00,23,1.25,0.00),(56,1,27000.00,27999.99,0,99,337.50,337.50,24,1.25,0.00),(57,1,28000.00,28999.99,0,99,350.00,350.00,25,1.25,0.00),(58,1,29000.00,29999.99,0,99,362.50,362.50,26,1.25,0.00),(59,1,30000.00,9999999.99,0,99,375.00,375.00,27,1.25,0.00),(61,5,1.00,7999.99,1,99,87.50,87.50,1,0,0.00),(62,5,8000.00,8999.99,1,99,100.00,100.00,2,0,0.00),(63,5,9000.00,9999.99,1,99,112.50,112.50,3,0,0.00),(64,5,10000.00,10999.99,1,99,125.00,125.00,4,0,0.00),(65,5,11000.00,11999.99,1,99,137.50,137.50,5,0,0.00),(66,5,12000.00,12999.99,1,99,150.00,150.00,6,0,0.00),(67,5,13000.00,13999.99,1,99,162.50,162.50,7,0,0.00),(68,5,14000.00,14999.99,1,99,175.00,175.00,8,0,0.00),(69,5,15000.00,15999.99,1,99,187.50,187.50,9,0,0.00),(70,5,16000.00,16999.99,1,99,200.00,200.00,10,0,0.00),(71,5,17000.00,17999.99,1,99,212.50,212.50,11,0,0.00),(72,5,18000.00,18999.99,1,99,225.00,225.00,12,0,0.00),(73,5,19000.00,19999.99,1,99,237.50,237.50,13,0,0.00),(74,5,20000.00,20999.99,1,99,250.00,250.00,14,0,0.00),(75,5,21000.00,21999.99,1,99,262.50,262.50,15,0,0.00),(76,5,22000.00,22999.99,1,99,275.00,275.00,16,0,0.00),(77,5,23000.00,23999.99,1,99,287.50,287.50,17,0,0.00),(78,5,24000.00,24999.99,1,99,300.00,300.00,18,0,0.00),(79,5,25000.00,25999.99,1,99,312.50,312.50,19,0,0.00),(80,5,26000.00,26999.99,1,99,325.00,325.00,20,0,0.00),(81,5,27000.00,27999.99,1,99,337.50,337.50,21,0,0.00),(82,5,28000.00,28999.99,1,99,350.00,350.00,22,0,0.00),(83,5,29000.00,29999.99,1,99,362.50,362.50,23,0,0.00),(84,5,30000.00,30999.99,1,99,375.00,375.00,24,0,0.00),(85,5,31000.00,31999.99,1,99,387.50,387.50,25,0,0.00),(86,5,32000.00,32999.99,1,99,400.00,400.00,26,0,0.00),(87,5,33000.00,33999.99,1,99,412.50,412.50,27,0,0.00),(88,5,34000.00,34999.99,1,99,425.00,425.00,28,0,0.00),(89,5,35000.00,999999.99,1,99,437.50,437.50,29,0,0.00),(152,6,15750.00,999999.99,0,99,581.30,1178.70,30,0,0.00),(151,6,15250.00,15749.99,0,99,563.20,1141.80,30,0,0.00),(150,6,14750.00,15249.99,0,99,545.00,1105.00,30,0,0.00),(149,6,14250.00,14749.99,0,99,526.80,1068.20,10,0,0.00),(148,6,13750.00,14249.99,0,99,508.70,1031.30,10,0,0.00),(147,6,13250.00,13749.99,0,99,490.50,994.50,10,0,0.00),(146,6,12750.00,13249.99,0,99,472.30,957.70,10,0,0.00),(145,6,12250.00,12749.99,0,99,454.20,920.80,10,0,0.00),(144,6,11750.00,12249.99,0,99,436.00,884.00,10,0,0.00),(143,6,11250.00,11749.99,0,99,417.80,847.20,10,0,0.00),(142,6,10750.00,11249.99,0,99,399.70,810.30,10,0,0.00),(141,6,10250.00,10749.99,0,99,381.50,773.50,10,0,0.00),(140,6,9750.00,10249.99,0,99,363.30,736.70,10,0,0.00),(139,6,9250.00,9749.99,0,99,345.20,699.80,10,0,0.00),(138,6,8750.00,9249.99,0,99,327.00,663.00,10,0,0.00),(137,6,8250.00,8749.99,0,99,308.80,626.20,10,0,0.00),(136,6,7750.00,8249.99,0,99,290.70,589.30,10,0,0.00),(135,6,7250.00,7749.99,0,99,272.50,552.50,10,0,0.00),(134,6,6750.00,7249.99,0,99,254.30,515.70,10,0,0.00),(133,6,6250.00,6749.99,0,99,236.20,478.80,10,0,0.00),(132,6,5750.00,6249.99,0,99,218.00,442.00,10,0,0.00),(131,6,5250.00,5749.99,0,99,199.80,405.20,10,0,0.00),(130,6,4750.00,5249.99,0,99,181.70,368.30,10,0,0.00),(129,6,4250.00,4749.99,0,99,163.50,331.50,10,0,0.00),(128,6,3750.00,4249.99,0,99,145.30,294.70,10,0,0.00),(127,6,3250.00,3749.99,0,99,127.20,257.80,10,0,0.00),(126,6,2750.00,3249.99,0,99,109.00,221.00,10,0,0.00),(125,6,2250.00,2749.99,0,99,90.80,184.20,10,0,0.00),(124,6,1750.00,2249.99,0,99,72.70,147.30,10,0,0.00),(123,6,1250.00,1749.99,0,99,54.50,110.50,10,0,0.00),(122,6,1000.00,1249.99,0,99,36.30,73.70,10,0,0.00);
/*!40000 ALTER TABLE `sc_records` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `statutory_contribution`
--

DROP TABLE IF EXISTS `statutory_contribution`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `statutory_contribution` (
  `sc_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dec_id` int(10) unsigned NOT NULL,
  `sc_code` varchar(32) COLLATE latin1_general_ci DEFAULT NULL,
  `sc_desc` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `sc_effectivedate` date DEFAULT NULL,
  `sc_addwho` varchar(45) COLLATE latin1_general_ci DEFAULT NULL,
  `sc_addwhen` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `sc_updatewho` varchar(45) COLLATE latin1_general_ci DEFAULT NULL,
  `sc_updatewhen` datetime DEFAULT NULL,
  `sc_stat` tinyint(1) unsigned DEFAULT '1',
  PRIMARY KEY (`sc_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `statutory_contribution`
--

LOCK TABLES `statutory_contribution` WRITE;
/*!40000 ALTER TABLE `statutory_contribution` DISABLE KEYS */;
INSERT INTO `statutory_contribution` VALUES (1,2,'PHIC','Philippine Health Insurance Company','2009-10-01','admin','2009-10-02 02:45:47','','0000-00-00 00:00:00',1),(2,1,'SSS','Social Security Service','2007-01-01','admin','2009-10-02 02:31:07','','0000-00-00 00:00:00',1),(3,3,'HDMF','Home Development Mutual Fund','2005-01-01','admin','2011-07-18 17:02:27','','0000-00-00 00:00:00',1),(5,2,'PHIC 2013','Philippine Health Insurance Company','2013-01-01',NULL,'2013-01-02 16:06:39',NULL,NULL,1),(6,1,'SSS 2014','SSS 2014','2014-01-01',NULL,'2014-01-02 05:20:21',NULL,NULL,1);
/*!40000 ALTER TABLE `statutory_contribution` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ta_emp_rec`
--

DROP TABLE IF EXISTS `ta_emp_rec`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ta_emp_rec` (
  `emp_tarec_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `paystub_id` int(10) unsigned NOT NULL,
  `payperiod_id` int(10) unsigned NOT NULL,
  `emp_id` int(10) unsigned NOT NULL,
  `tatbl_id` int(10) unsigned NOT NULL,
  `emp_tarec_amtperrate` decimal(10,2) DEFAULT NULL,
  `emp_tarec_nohrday` decimal(10,5) DEFAULT NULL,
  `emp_tarec_addwho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `emp_tarec_addwhen` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `emp_tarec_updatewho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `emp_tarec_updatewhen` datetime DEFAULT NULL,
  `uptadet_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`emp_tarec_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ta_emp_rec`
--

LOCK TABLES `ta_emp_rec` WRITE;
/*!40000 ALTER TABLE `ta_emp_rec` DISABLE KEYS */;
/*!40000 ALTER TABLE `ta_emp_rec` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ta_tbl`
--

DROP TABLE IF EXISTS `ta_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ta_tbl` (
  `tatbl_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tatbl_name` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `tatbl_rate` tinyint(2) unsigned DEFAULT '1',
  `tatbl_addwho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `tatbl_addwhen` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `tatbl_updatewho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `tatbl_updatewhen` datetime DEFAULT NULL,
  PRIMARY KEY (`tatbl_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ta_tbl`
--

LOCK TABLES `ta_tbl` WRITE;
/*!40000 ALTER TABLE `ta_tbl` DISABLE KEYS */;
INSERT INTO `ta_tbl` VALUES (1,'No Pay Leave (NPL)',2,'jayadmin','2011-02-02 16:12:58','jimabignay','2012-06-19 10:44:54'),(3,'Late',1,'jayadmin','2011-02-02 16:13:23','','0000-00-00 00:00:00'),(4,'Undertime',1,'jayadmin','2011-02-02 16:13:49','','0000-00-00 00:00:00'),(5,'No of Days',2,'jayadmin','2011-12-20 08:22:16','','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `ta_tbl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tax_employer`
--

DROP TABLE IF EXISTS `tax_employer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tax_employer` (
  `te_id` int(11) NOT NULL AUTO_INCREMENT,
  `comp_id` int(10) unsigned NOT NULL,
  `te_rdo` varchar(30) COLLATE latin1_general_ci DEFAULT NULL,
  `te_cat_agent` varchar(30) COLLATE latin1_general_ci DEFAULT NULL,
  `te_atc` varchar(30) COLLATE latin1_general_ci DEFAULT NULL,
  `te_addwho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `te_addwhen` timestamp NULL DEFAULT NULL,
  `te_updatewhen` datetime DEFAULT NULL,
  `te_updatewho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  PRIMARY KEY (`te_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tax_employer`
--

LOCK TABLES `tax_employer` WRITE;
/*!40000 ALTER TABLE `tax_employer` DISABLE KEYS */;
/*!40000 ALTER TABLE `tax_employer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tax_excep`
--

DROP TABLE IF EXISTS `tax_excep`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tax_excep` (
  `taxep_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `taxep_code` varchar(5) COLLATE latin1_general_ci DEFAULT NULL,
  `taxep_name` varchar(30) COLLATE latin1_general_ci DEFAULT NULL,
  `taxep_order` tinyint(1) unsigned DEFAULT NULL,
  `taxep_addwho` varchar(30) COLLATE latin1_general_ci DEFAULT NULL,
  `taxep_addwhen` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `taxep_updatewho` varchar(30) COLLATE latin1_general_ci DEFAULT NULL,
  `taxep_updatewhen` datetime DEFAULT NULL,
  PRIMARY KEY (`taxep_id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tax_excep`
--

LOCK TABLES `tax_excep` WRITE;
/*!40000 ALTER TABLE `tax_excep` DISABLE KEYS */;
INSERT INTO `tax_excep` VALUES (1,'','',10,'jayadmin','2010-11-15 18:26:45','','0000-00-00 00:00:00'),(3,'S','SINGLE',20,'jayadmin','2010-11-15 18:26:45','','0000-00-00 00:00:00'),(4,'S1','SINGLE WITH 1 DEPENDENT',30,'jayadmin','2010-11-15 18:26:45','','0000-00-00 00:00:00'),(5,'S2','SINGLE WITH 2 DEPENDENT',40,'jayadmin','2010-11-15 18:26:45','','0000-00-00 00:00:00'),(6,'S3','Single3 (S3)',50,'jayadmin','2010-11-15 18:26:45','','0000-00-00 00:00:00'),(7,'S4','Single4 (S4)',60,'jayadmin','2010-11-15 18:26:45','','0000-00-00 00:00:00'),(8,'ME','MARRIED',70,'jayadmin','2010-11-15 18:26:45','','0000-00-00 00:00:00'),(9,'ME1','MARRIED WITH 1 DEPENDENT',80,'jayadmin','2010-11-15 18:27:10','','0000-00-00 00:00:00'),(10,'ME2','MARRIED WITH 2 DEPENDENT',90,'jayadmin','2010-11-15 18:27:37','','0000-00-00 00:00:00'),(11,'ME3','MARRIED WITH 3 DEPENDENT',100,'jayadmin','2010-11-15 18:27:49','','0000-00-00 00:00:00'),(12,'ME4','Married4 (ME4)',110,'jayadmin','2010-11-15 18:27:59','','0000-00-00 00:00:00'),(13,'M','Married (M)',120,NULL,'2012-07-28 10:17:50',NULL,NULL),(14,'M1','Married (M1)',130,NULL,'2012-07-28 10:18:10',NULL,NULL),(15,'M2','Married (M2)',140,NULL,'2012-07-28 10:18:35',NULL,NULL),(16,'M3','Married (M3)',150,NULL,'2012-07-28 10:18:47',NULL,NULL),(17,'M4','Married (M4)',160,NULL,'2012-07-28 10:19:06',NULL,NULL),(18,'Z','ZERO',NULL,NULL,'2013-10-22 13:58:42',NULL,NULL);
/*!40000 ALTER TABLE `tax_excep` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tax_policy`
--

DROP TABLE IF EXISTS `tax_policy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tax_policy` (
  `tp_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tp_name` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `tp_desc` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `tp_edate` varchar(45) COLLATE latin1_general_ci DEFAULT NULL,
  `tp_no_q_dependents` int(10) unsigned DEFAULT NULL,
  `tp_head_family` int(10) unsigned DEFAULT NULL,
  `tp_married_ind` int(10) unsigned DEFAULT NULL,
  `tp_each_dependent` int(10) unsigned DEFAULT NULL,
  `tp_num_dependents` int(10) unsigned DEFAULT NULL,
  `tp_addwho` varchar(45) COLLATE latin1_general_ci DEFAULT NULL,
  `tp_addwhen` timestamp NULL DEFAULT NULL,
  `tp_updatewho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `tp_updatewhen` datetime DEFAULT NULL,
  `tp_other_benefits` int(11) DEFAULT NULL,
  `tp_max_premium` int(11) DEFAULT NULL,
  PRIMARY KEY (`tp_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tax_policy`
--

LOCK TABLES `tax_policy` WRITE;
/*!40000 ALTER TABLE `tax_policy` DISABLE KEYS */;
INSERT INTO `tax_policy` VALUES (1,'TAX 2009','Tax Table 2009','2009-01-01',50000,50000,50000,25000,4,'admin','2012-03-25 16:00:00','','0000-00-00 00:00:00',30000,2400);
/*!40000 ALTER TABLE `tax_policy` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tax_table`
--

DROP TABLE IF EXISTS `tax_table`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tax_table` (
  `tt_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tp_id` int(10) unsigned NOT NULL,
  `tt_pay_group` int(10) unsigned DEFAULT NULL,
  `tt_maxamount` int(11) DEFAULT NULL,
  `tt_minamount` int(11) DEFAULT NULL,
  `tt_over_pct` int(11) DEFAULT NULL,
  `tt_addwho` varchar(45) COLLATE latin1_general_ci DEFAULT NULL,
  `tt_addwhen` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `tt_updatewho` varchar(45) COLLATE latin1_general_ci DEFAULT NULL,
  `tt_updatewhen` datetime DEFAULT NULL,
  `tt_exemption` tinytext COLLATE latin1_general_ci,
  `tt_taxamount` decimal(11,2) DEFAULT NULL,
  `tt_other_benefits` int(10) unsigned DEFAULT NULL,
  `tt_no_q_dependents` int(10) unsigned DEFAULT NULL,
  `tt_head_family` int(10) unsigned DEFAULT NULL,
  `tt_married_ind` int(10) unsigned DEFAULT NULL,
  `tt_each_dependent` int(10) unsigned DEFAULT NULL,
  `tt_num_dependents` int(10) unsigned DEFAULT NULL,
  `tt_max_premium` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`tt_id`),
  KEY `tax_table_FKIndex1` (`tp_id`)
) ENGINE=MyISAM AUTO_INCREMENT=220 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tax_table`
--

LOCK TABLES `tax_table` WRITE;
/*!40000 ALTER TABLE `tax_table` DISABLE KEYS */;
INSERT INTO `tax_table` VALUES (2,1,1,33,0,5,'jayadmin','2009-10-13 22:29:43','','0000-00-00 00:00:00','1',0.00,0,0,0,0,0,0,0),(3,1,1,99,33,10,'jayadmin','2009-10-13 22:33:59','','0000-00-00 00:00:00','1',1.65,0,0,0,0,0,0,0),(4,1,1,231,99,15,'jayadmin','2009-10-13 22:34:47','','0000-00-00 00:00:00','1',8.25,0,0,0,0,0,0,0),(5,1,1,462,231,20,'jayadmin','2009-10-13 22:35:27','','0000-00-00 00:00:00','1',28.05,0,0,0,0,0,0,0),(6,1,1,825,462,25,'jayadmin','2009-10-13 22:36:29','','0000-00-00 00:00:00','1',74.26,0,0,0,0,0,0,0),(7,1,1,1650,825,30,'jayadmin','2009-10-13 22:37:16','','0000-00-00 00:00:00','1',165.02,0,0,0,0,0,0,0),(8,1,1,165,1,0,'jayadmin','2009-10-13 22:38:29','','0000-00-00 00:00:00','3',0.00,0,0,0,0,0,0,0),(9,1,1,198,165,5,'jayadmin','2009-10-13 22:39:06','','0000-00-00 00:00:00','3',0.00,0,0,0,0,0,0,0),(10,1,1,264,198,10,'jayadmin','2009-10-13 22:40:02','','0000-00-00 00:00:00','3',1.65,0,0,0,0,0,0,0),(11,1,1,396,264,15,'jayadmin','2009-10-13 22:40:49','','0000-00-00 00:00:00','3',8.25,0,0,0,0,0,0,0),(12,1,3,2083,1,0,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','3',0.00,0,0,0,0,0,0,0),(13,1,3,2500,2083,5,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','3',0.00,0,0,0,0,0,0,0),(14,1,3,3333,2500,10,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','3',20.83,0,0,0,0,0,0,0),(15,1,3,5000,3333,15,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','3',104.17,0,0,0,0,0,0,0),(16,1,3,7917,5000,20,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','3',354.17,0,0,0,0,0,0,0),(17,1,3,12500,7917,25,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','3',937.50,0,0,0,0,0,0,0),(18,1,3,22917,12500,30,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','3',2083.33,0,0,0,0,0,0,0),(19,1,3,0,22917,32,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','3',5208.33,0,0,0,0,0,0,0),(20,1,3,0,1,0,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','1',0.00,0,0,0,0,0,0,0),(21,1,4,4167,1,0,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','3',0.00,0,0,0,0,0,0,0),(22,1,4,5000,4167,5,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','3',0.00,0,0,0,0,0,0,0),(23,1,4,6667,5000,10,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','3',41.67,0,0,0,0,0,0,0),(24,1,4,10000,6667,15,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','3',208.33,0,0,0,0,0,0,0),(25,1,4,15833,10000,20,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','3',708.33,0,0,0,0,0,0,0),(26,1,4,25000,15833,25,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','3',1875.00,0,0,0,0,0,0,0),(27,1,4,45833,25000,30,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','3',4166.67,0,0,0,0,0,0,0),(28,1,4,0,45833,32,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','3',10416.67,0,0,0,0,0,0,0),(29,1,4,6250,1,0,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',0.00,0,0,0,0,0,0,0),(30,1,4,7083,6250,5,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',0.00,0,0,0,0,0,0,0),(31,1,4,8750,7083,10,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',41.67,0,0,0,0,0,0,0),(32,1,4,12083,8750,15,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',208.33,0,0,0,0,0,0,0),(33,1,4,17917,12083,20,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',708.33,0,0,0,0,0,0,0),(34,1,4,27083,17917,25,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',1875.00,0,0,0,0,0,0,0),(35,1,4,47917,27083,30,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',4166.67,0,0,0,0,0,0,0),(36,1,4,0,47917,32,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','9',10416.67,0,0,0,0,0,0,0),(37,1,4,0,1,0,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','1',0.00,0,0,0,0,0,0,0),(38,1,4,833,0,5,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','1',0.00,0,0,0,0,0,0,0),(39,1,4,2500,833,10,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','1',41.67,0,0,0,0,0,0,0),(40,1,4,5833,2500,15,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','1',208.33,0,0,0,0,0,0,0),(41,1,4,11667,5833,20,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','1',708.33,0,0,0,0,0,0,0),(42,1,4,20833,11667,25,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','1',1875.00,0,0,0,0,0,0,0),(43,1,4,41667,20833,30,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','1',4166.67,0,0,0,0,0,0,0),(44,1,4,0,41667,32,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','1',10416.67,0,0,0,0,0,0,0),(45,1,3,417,0,5,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','1',0.00,0,0,0,0,0,0,0),(46,1,3,1250,417,10,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','1',20.83,0,0,0,0,0,0,0),(47,1,3,2917,1250,15,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','1',104.17,0,0,0,0,0,0,0),(48,1,3,5833,2917,20,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','1',354.17,0,0,0,0,0,0,0),(49,1,3,10417,5833,25,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','1',937.50,0,0,0,0,0,0,0),(50,1,3,20833,10417,30,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','1',2083.33,0,0,0,0,0,0,0),(51,1,3,0,20833,32,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','1',5208.33,0,0,0,0,0,0,0),(52,1,3,3125,1,0,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',0.00,0,0,0,0,0,0,0),(53,1,3,3542,3125,5,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',0.00,0,0,0,0,0,0,0),(54,1,3,4375,3542,10,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',20.83,0,0,0,0,0,0,0),(55,1,3,6042,4375,15,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',104.17,0,0,0,0,0,0,0),(56,1,3,8958,6042,20,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',354.17,0,0,0,0,0,0,0),(57,1,3,13542,8958,25,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',937.50,0,0,0,0,0,0,0),(58,1,3,23958,13542,30,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',2083.33,0,0,0,0,0,0,0),(59,1,3,0,23958,32,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',5208.33,0,0,0,0,0,0,0),(60,1,3,4167,1,0,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',0.00,0,0,0,0,0,0,0),(61,1,3,4583,4167,5,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',0.00,0,0,0,0,0,0,0),(62,1,3,5417,4583,10,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',20.83,0,0,0,0,0,0,0),(63,1,3,7083,5417,15,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',104.17,0,0,0,0,0,0,0),(64,1,3,10000,7083,20,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',354.17,0,0,0,0,0,0,0),(65,1,3,14583,10000,25,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',937.50,0,0,0,0,0,0,0),(66,1,3,25000,14583,30,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',2083.33,0,0,0,0,0,0,0),(67,1,3,0,25000,32,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',5208.33,0,0,0,0,0,0,0),(68,1,3,5208,1,0,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',0.00,0,0,0,0,0,0,0),(69,1,3,5625,5208,5,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',0.00,0,0,0,0,0,0,0),(70,1,3,6458,5625,10,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',20.83,0,0,0,0,0,0,0),(71,1,3,8125,6458,15,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',104.17,0,0,0,0,0,0,0),(72,1,3,11042,8125,20,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',354.17,0,0,0,0,0,0,0),(73,1,3,15625,11042,25,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',937.50,0,0,0,0,0,0,0),(74,1,3,26042,15625,30,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',2083.33,0,0,0,0,0,0,0),(75,1,3,0,26042,32,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',5208.33,0,0,0,0,0,0,0),(76,1,3,6250,1,0,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',0.00,0,0,0,0,0,0,0),(77,1,3,6667,6250,5,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',0.00,0,0,0,0,0,0,0),(78,1,3,7500,6667,10,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',20.83,0,0,0,0,0,0,0),(79,1,3,9167,7500,15,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',104.17,0,0,0,0,0,0,0),(80,1,3,12083,9167,20,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',354.17,0,0,0,0,0,0,0),(81,1,3,16667,12083,25,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',937.50,0,0,0,0,0,0,0),(82,1,3,27083,16667,30,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',2083.33,0,0,0,0,0,0,0),(83,1,3,0,27083,32,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',5208.33,0,0,0,0,0,0,0),(84,1,4,8333,1,0,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',0.00,0,0,0,0,0,0,0),(85,1,4,9167,8333,5,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',0.00,0,0,0,0,0,0,0),(86,1,4,10833,9167,10,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',41.67,0,0,0,0,0,0,0),(87,1,4,14167,10833,15,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',208.33,0,0,0,0,0,0,0),(88,1,4,20000,14167,20,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',708.33,0,0,0,0,0,0,0),(89,1,4,29167,20000,25,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',1875.00,0,0,0,0,0,0,0),(90,1,4,50000,29167,30,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','10',4166.67,0,0,0,0,0,0,0),(91,1,4,0,50000,32,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','10',10416.67,0,0,0,0,0,0,0),(92,1,4,10417,1,0,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',0.00,0,0,0,0,0,0,0),(93,1,4,11250,10417,5,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',0.00,0,0,0,0,0,0,0),(94,1,4,12917,11250,10,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',41.67,0,0,0,0,0,0,0),(95,1,4,16250,12917,15,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',208.33,0,0,0,0,0,0,0),(96,1,4,22083,16250,20,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',708.33,0,0,0,0,0,0,0),(97,1,4,31250,22083,25,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',1875.00,0,0,0,0,0,0,0),(98,1,4,52083,31250,30,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',4166.67,0,0,0,0,0,0,0),(99,1,4,0,52083,32,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',10416.67,0,0,0,0,0,0,0),(100,1,4,12500,1,0,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',0.00,0,0,0,0,0,0,0),(101,1,4,13333,12500,5,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',0.00,0,0,0,0,0,0,0),(102,1,4,15000,13333,10,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',41.67,0,0,0,0,0,0,0),(103,1,4,18333,15000,15,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',208.33,0,0,0,0,0,0,0),(104,1,4,24167,18333,20,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',708.33,0,0,0,0,0,0,0),(105,1,4,33333,24167,25,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',1875.00,0,0,0,0,0,0,0),(106,1,4,54167,33333,30,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',4166.67,0,0,0,0,0,0,0),(107,1,4,0,54167,32,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',10416.67,0,0,0,0,0,0,0),(108,1,2,0,1,0,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','1',0.00,0,0,0,0,0,0,0),(109,1,2,192,0,5,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','1',0.00,0,0,0,0,0,0,0),(110,1,2,577,192,10,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','1',9.62,0,0,0,0,0,0,0),(111,1,2,1346,577,15,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','1',48.08,0,0,0,0,0,0,0),(112,1,2,2692,1346,20,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','1',163.46,0,0,0,0,0,0,0),(113,1,2,4808,2692,25,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','1',432.69,0,0,0,0,0,0,0),(114,1,2,9615,4808,30,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','1',961.54,0,0,0,0,0,0,0),(115,1,2,0,9615,32,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','1',2403.85,0,0,0,0,0,0,0),(116,1,2,962,1,0,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','3',0.00,0,0,0,0,0,0,0),(117,1,2,1154,962,5,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','3',0.00,0,0,0,0,0,0,0),(118,1,2,1538,1154,10,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','3',9.62,0,0,0,0,0,0,0),(119,1,2,2308,1538,15,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','3',48.08,0,0,0,0,0,0,0),(120,1,2,3654,2308,20,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','3',163.46,0,0,0,0,0,0,0),(121,1,2,5769,3654,25,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','3',432.69,0,0,0,0,0,0,0),(122,1,2,10577,5769,30,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','3',961.54,0,0,0,0,0,0,0),(123,1,2,0,10577,32,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','3',2403.85,0,0,0,0,0,0,0),(124,1,2,1442,1,0,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',0.00,0,0,0,0,0,0,0),(125,1,2,1635,1442,5,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',0.00,0,0,0,0,0,0,0),(126,1,2,2019,1635,10,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',9.62,0,0,0,0,0,0,0),(127,1,2,2788,2019,15,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',48.08,0,0,0,0,0,0,0),(128,1,2,4135,2788,20,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',163.46,0,0,0,0,0,0,0),(129,1,2,6250,4135,25,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',432.69,0,0,0,0,0,0,0),(130,1,2,11058,6250,30,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',961.54,0,0,0,0,0,0,0),(131,1,2,0,11058,32,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',2403.85,0,0,0,0,0,0,0),(132,1,1,627,396,20,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','3',28.05,0,0,0,0,0,0,0),(133,1,1,990,627,25,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','3',74.26,0,0,0,0,0,0,0),(134,1,1,1815,990,30,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','3',165.02,0,0,0,0,0,0,0),(135,1,1,0,1815,32,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','3',412.54,0,0,0,0,0,0,0),(136,1,1,248,1,0,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',0.00,0,0,0,0,0,0,0),(137,1,1,281,248,5,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',0.00,0,0,0,0,0,0,0),(138,1,1,347,281,10,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',1.65,0,0,0,0,0,0,0),(139,1,1,479,347,15,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',8.25,0,0,0,0,0,0,0),(140,1,1,710,479,20,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',28.05,0,0,0,0,0,0,0),(141,1,1,1073,710,25,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',74.26,0,0,0,0,0,0,0),(142,1,1,1898,1073,30,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',165.02,0,0,0,0,0,0,0),(143,1,1,0,1898,32,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',412.54,0,0,0,0,0,0,0),(144,1,1,330,1,0,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',0.00,0,0,0,0,0,0,0),(145,1,1,363,330,5,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',0.00,0,0,0,0,0,0,0),(146,1,1,429,363,10,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',1.65,0,0,0,0,0,0,0),(147,1,1,561,429,15,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',8.25,0,0,0,0,0,0,0),(148,1,1,792,561,20,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',28.05,0,0,0,0,0,0,0),(149,1,1,1155,792,25,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',74.26,0,0,0,0,0,0,0),(150,1,1,1980,1155,30,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',165.02,0,0,0,0,0,0,0),(151,1,1,0,1980,32,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',412.54,0,0,0,0,0,0,0),(152,1,1,413,1,0,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',0.00,0,0,0,0,0,0,0),(153,1,1,446,413,5,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',0.00,0,0,0,0,0,0,0),(154,1,1,512,446,10,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',1.65,0,0,0,0,0,0,0),(155,1,1,644,512,15,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',8.25,0,0,0,0,0,0,0),(156,1,1,875,644,20,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',28.05,0,0,0,0,0,0,0),(157,1,1,1238,875,25,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',74.26,0,0,0,0,0,0,0),(158,1,1,2063,1238,30,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',165.02,0,0,0,0,0,0,0),(159,1,1,0,2063,32,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',412.54,0,0,0,0,0,0,0),(160,1,1,495,1,0,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',0.00,0,0,0,0,0,0,0),(161,1,1,528,495,5,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',0.00,0,0,0,0,0,0,0),(162,1,1,594,528,10,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',1.65,0,0,0,0,0,0,0),(163,1,1,726,594,15,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',8.25,0,0,0,0,0,0,0),(164,1,1,957,726,20,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',28.05,0,0,0,0,0,0,0),(165,1,1,1320,957,25,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',74.26,0,0,0,0,0,0,0),(166,1,1,2145,1320,30,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',165.02,0,0,0,0,0,0,0),(167,1,1,0,2145,32,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',412.54,0,0,0,0,0,0,0),(168,1,2,1923,1,0,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',0.00,0,0,0,0,0,0,0),(169,1,2,2115,1923,5,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',0.00,0,0,0,0,0,0,0),(170,1,2,2500,2115,10,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',9.62,0,0,0,0,0,0,0),(171,1,2,3269,2500,15,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',48.08,0,0,0,0,0,0,0),(172,1,2,4615,3269,20,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',163.46,0,0,0,0,0,0,0),(173,1,2,6731,4615,25,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',432.69,0,0,0,0,0,0,0),(174,1,2,11538,6731,30,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',961.54,0,0,0,0,0,0,0),(175,1,2,0,11538,32,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',2403.85,0,0,0,0,0,0,0),(176,1,2,2404,1,0,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',0.00,0,0,0,0,0,0,0),(177,1,2,2596,2404,5,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',0.00,0,0,0,0,0,0,0),(178,1,2,2981,2596,10,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',9.62,0,0,0,0,0,0,0),(179,1,2,3750,2981,15,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',48.08,0,0,0,0,0,0,0),(180,1,2,5096,3750,20,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',163.46,0,0,0,0,0,0,0),(181,1,2,7212,5096,25,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',432.69,0,0,0,0,0,0,0),(182,1,2,12019,7212,30,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',961.54,0,0,0,0,0,0,0),(183,1,2,0,12019,32,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',2403.85,0,0,0,0,0,0,0),(184,1,2,2885,1,0,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',0.00,0,0,0,0,0,0,0),(185,1,2,3077,2885,5,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',0.00,0,0,0,0,0,0,0),(186,1,2,3462,3077,10,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',9.62,0,0,0,0,0,0,0),(187,1,2,4231,3462,15,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',48.08,0,0,0,0,0,0,0),(188,1,2,5577,4231,20,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',163.46,0,0,0,0,0,0,0),(189,1,2,7692,5577,25,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',432.69,0,0,0,0,0,0,0),(190,1,2,12500,7692,30,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',961.54,0,0,0,0,0,0,0),(191,1,2,0,12500,32,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',2403.85,0,0,0,0,0,0,0),(192,1,4,0,47917,32,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',10416.67,0,0,0,0,0,0,0),(193,1,4,50000,29167,30,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',4166.67,0,0,0,0,0,0,0),(194,1,4,0,50000,32,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',10416.67,0,0,0,0,0,0,0),(197,1,1,0,1,0,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','1',0.00,0,0,0,0,0,0,0),(199,1,1,0,1650,32,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','1',412.54,0,0,0,0,0,0,0),(200,1,5,10000,0,5,'admin','2012-03-26 00:20:08','','0000-00-00 00:00:00','1',0.00,30000,50000,50000,50000,25000,4,2400),(201,1,5,30000,10000,10,'admin','2012-03-26 00:21:12','','0000-00-00 00:00:00','1',500.00,30000,50000,50000,50000,25000,4,2400),(202,1,5,70000,30000,15,'admin','2012-03-26 00:21:49','','0000-00-00 00:00:00','1',2500.00,30000,50000,50000,50000,25000,4,2400),(203,1,5,140000,70000,20,'admin','2012-03-26 00:22:27','','0000-00-00 00:00:00','1',8500.00,30000,50000,50000,50000,25000,4,2400),(204,1,5,250000,140000,25,'admin','2012-03-26 00:23:12','','0000-00-00 00:00:00','1',22500.00,30000,50000,50000,50000,25000,4,2400),(205,1,5,500000,250000,30,'admin','2012-03-26 00:23:53','','0000-00-00 00:00:00','1',50000.00,30000,50000,50000,50000,25000,4,2400),(206,1,5,0,500000,32,'admin','2012-03-26 00:24:37','','0000-00-00 00:00:00','1',125000.00,30000,50000,50000,50000,25000,4,2400),(207,1,6,0,0,32,'admin','2012-03-26 21:30:56',NULL,NULL,'1',0.00,0,0,0,0,0,0,0),(208,1,6,0,45833,32,NULL,'2012-05-03 01:03:01',NULL,NULL,'3',10416.67,0,0,0,0,0,0,0),(209,1,6,45833,25000,30,NULL,'2012-05-03 01:04:19',NULL,NULL,'3',4166.67,0,0,0,0,0,0,0),(210,1,6,25000,15833,25,NULL,'2012-05-03 01:05:18',NULL,NULL,'3',1875.00,0,0,0,0,0,0,0),(211,1,6,0,47917,32,NULL,'2012-05-03 01:08:38',NULL,NULL,'4',10416.67,0,0,0,0,0,0,0),(212,1,6,47917,27083,30,NULL,'2012-05-03 01:09:20',NULL,NULL,'4',4166.67,0,0,0,0,0,0,0),(213,1,6,27083,17917,25,NULL,'2012-05-03 01:10:03',NULL,NULL,'4',1875.00,0,0,0,0,0,0,0),(214,1,6,17917,12083,20,NULL,'2012-05-03 01:11:41',NULL,NULL,'4',708.33,0,0,0,0,0,0,0),(215,1,6,15833,10000,20,NULL,'2012-05-03 06:19:15',NULL,NULL,'3',708.33,0,0,0,0,0,0,0),(216,1,6,10000,6667,15,NULL,'2012-05-03 06:19:48',NULL,NULL,'3',208.33,0,0,0,0,0,0,0),(217,1,6,6667,5000,10,NULL,'2012-05-03 06:20:27',NULL,NULL,'3',41.67,0,0,0,0,0,0,0),(218,1,6,5000,4167,5,NULL,'2012-05-03 06:21:05',NULL,NULL,'3',0.00,0,0,0,0,0,0,0),(219,1,6,4167,0,0,NULL,'2012-05-03 06:21:34',NULL,NULL,'3',0.00,0,0,0,0,0,0,0);
/*!40000 ALTER TABLE `tax_table` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tks_uploadta_details`
--

DROP TABLE IF EXISTS `tks_uploadta_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tks_uploadta_details` (
  `uptadet_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `emp_id` int(10) unsigned NOT NULL,
  `uptahead_id` int(10) unsigned NOT NULL,
  `uptadet_isgood` tinyint(1) DEFAULT NULL,
  `uptadet_status` varchar(24) COLLATE latin1_general_ci DEFAULT NULL,
  `uptadet_empnum` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `uptadet_empname` varchar(200) COLLATE latin1_general_ci DEFAULT NULL,
  `uptadet_desc` varchar(225) COLLATE latin1_general_ci DEFAULT NULL,
  PRIMARY KEY (`uptadet_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tks_uploadta_details`
--

LOCK TABLES `tks_uploadta_details` WRITE;
/*!40000 ALTER TABLE `tks_uploadta_details` DISABLE KEYS */;
/*!40000 ALTER TABLE `tks_uploadta_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tks_uploadta_header`
--

DROP TABLE IF EXISTS `tks_uploadta_header`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tks_uploadta_header` (
  `uptahead_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uptahead_desc` varchar(128) COLLATE latin1_general_ci DEFAULT NULL,
  `uptahead_status` varchar(24) COLLATE latin1_general_ci DEFAULT NULL,
  `uptahead_goodqty` mediumint(8) unsigned DEFAULT NULL,
  `uptahead_badqty` mediumint(8) unsigned DEFAULT NULL,
  `uptahead_addwho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `uptahead_addwhen` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `uptahead_updatewho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `uptahead_updatewhen` datetime DEFAULT NULL,
  `payperiod_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`uptahead_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tks_uploadta_header`
--

LOCK TABLES `tks_uploadta_header` WRITE;
/*!40000 ALTER TABLE `tks_uploadta_header` DISABLE KEYS */;
/*!40000 ALTER TABLE `tks_uploadta_header` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `z_formula_temp`
--

DROP TABLE IF EXISTS `z_formula_temp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `z_formula_temp` (
  `paystub_id` int(11) NOT NULL,
  `psa_id` int(11) NOT NULL,
  `amount` decimal(10,5) NOT NULL,
  `custom_id` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `z_formula_temp`
--

LOCK TABLES `z_formula_temp` WRITE;
/*!40000 ALTER TABLE `z_formula_temp` DISABLE KEYS */;
INSERT INTO `z_formula_temp` VALUES (3,1,5500.00000,NULL),(3,-1,505.74700,NULL),(3,-2,63.21800,NULL),(4,1,5500.00000,NULL),(4,-1,505.74700,NULL),(4,-2,63.21800,NULL),(6,1,5500.00000,NULL),(6,-1,505.74700,NULL),(6,-2,63.21800,NULL),(8,1,5500.00000,NULL),(8,-1,505.74700,NULL),(8,-2,63.21800,NULL),(11,1,5500.00000,NULL),(11,-1,505.74700,NULL),(11,-2,63.21800,NULL),(14,1,5500.00000,NULL),(14,-1,505.74700,NULL),(14,-2,63.21800,NULL),(15,1,10000.00000,NULL),(15,-1,919.54000,NULL),(15,-2,114.94300,NULL),(21,1,7000.00000,NULL),(21,-1,643.67800,NULL),(21,-2,80.46000,NULL),(22,1,5500.00000,NULL),(22,-1,505.74700,NULL),(22,-2,63.21800,NULL),(23,1,5500.00000,NULL),(23,-1,505.74700,NULL),(23,-2,63.21800,NULL),(26,1,12047.22500,NULL),(26,-1,1107.79100,NULL),(26,-2,138.47400,NULL),(27,1,5500.00000,NULL),(27,-1,505.74700,NULL),(27,-2,63.21800,NULL),(28,1,5500.00000,NULL),(28,-1,505.74700,NULL),(28,-2,63.21800,NULL),(31,1,6250.00000,NULL),(31,-1,574.71300,NULL),(31,-2,71.83900,NULL),(32,1,6250.00000,NULL),(32,-1,574.71300,NULL),(32,-2,71.83900,NULL),(34,1,10000.00000,NULL),(34,-1,919.54000,NULL),(34,-2,114.94300,NULL),(36,1,5500.00000,NULL),(36,-1,505.74700,NULL),(36,-2,63.21800,NULL),(1,1,5816.58500,NULL),(1,-1,446.00000,NULL),(1,-2,55.75000,NULL),(117,1,4850.25000,NULL),(117,-1,446.00000,NULL),(117,-2,55.75000,NULL),(124,1,10000.00000,NULL),(124,-1,919.54000,NULL),(124,-2,114.94300,NULL),(130,1,5500.00000,NULL),(130,-1,505.74700,NULL),(130,-2,63.21800,NULL),(131,1,10000.00000,NULL),(131,-1,919.54000,NULL),(131,-2,114.94300,NULL),(132,1,5816.58500,NULL),(132,-1,446.00000,NULL),(132,-2,55.75000,NULL),(133,1,5500.00000,NULL),(133,-1,505.74700,NULL),(133,-2,63.21800,NULL),(134,1,7650.00000,NULL),(134,-1,703.44800,NULL),(134,-2,87.93100,NULL),(136,1,6323.59500,NULL),(136,-1,581.48000,NULL),(136,-2,72.68500,NULL),(138,1,10000.00000,NULL),(138,-1,919.54000,NULL),(138,-2,114.94300,NULL),(140,1,11350.67500,NULL),(140,-1,1043.74000,NULL),(140,-2,130.46800,NULL),(141,1,5500.00000,NULL),(141,-1,505.74700,NULL),(141,-2,63.21800,NULL),(142,1,5500.00000,NULL),(142,-1,505.74700,NULL),(142,-2,63.21800,NULL),(143,1,5500.00000,NULL),(143,-1,505.74700,NULL),(143,-2,63.21800,NULL),(144,1,5500.00000,NULL),(144,-1,505.74700,NULL),(144,-2,63.21800,NULL),(145,1,5500.00000,NULL),(145,-1,505.74700,NULL),(145,-2,63.21800,NULL),(146,1,5500.00000,NULL),(146,-1,505.74700,NULL),(146,-2,63.21800,NULL),(147,1,5500.00000,NULL),(147,-1,505.74700,NULL),(147,-2,63.21800,NULL),(148,1,5500.00000,NULL),(148,-1,505.74700,NULL),(148,-2,63.21800,NULL),(149,1,5500.00000,NULL),(149,-1,505.74700,NULL),(149,-2,63.21800,NULL),(150,1,12500.00000,NULL),(150,-1,1149.42500,NULL),(150,-2,143.67800,NULL),(153,1,5500.00000,NULL),(153,-1,505.74700,NULL),(153,-2,63.21800,NULL),(154,1,5500.00000,NULL),(154,-1,505.74700,NULL),(154,-2,63.21800,NULL),(157,1,5500.00000,NULL),(157,-1,505.74700,NULL),(157,-2,63.21800,NULL),(158,1,5500.00000,NULL),(158,-1,505.74700,NULL),(158,-2,63.21800,NULL),(162,1,5500.00000,NULL),(162,-1,505.74700,NULL),(162,-2,63.21800,NULL),(163,1,5500.00000,NULL),(163,-1,505.74700,NULL),(163,-2,63.21800,NULL),(164,1,5500.00000,NULL),(164,-1,505.74700,NULL),(164,-2,63.21800,NULL),(165,1,5500.00000,NULL),(165,-1,505.74700,NULL),(165,-2,63.21800,NULL),(166,1,17447.08500,NULL),(166,-1,1604.33000,NULL),(166,-2,200.54100,NULL),(168,1,5500.00000,NULL),(168,-1,505.74700,NULL),(168,-2,63.21800,NULL),(169,1,5500.00000,NULL),(169,-1,505.74700,NULL),(169,-2,63.21800,NULL),(171,1,5500.00000,NULL),(171,-1,505.74700,NULL),(171,-2,63.21800,NULL),(172,1,5500.00000,NULL),(172,-1,505.74700,NULL),(172,-2,63.21800,NULL),(173,1,10000.00000,NULL),(173,-1,919.54000,NULL),(173,-2,114.94300,NULL),(174,1,5500.00000,NULL),(174,-1,505.74700,NULL),(174,-2,63.21800,NULL),(175,1,10000.00000,NULL),(175,-1,919.54000,NULL),(175,-2,114.94300,NULL),(180,1,5500.00000,NULL),(180,-1,505.74700,NULL),(180,-2,63.21800,NULL),(181,1,5500.00000,NULL),(181,-1,505.74700,NULL),(181,-2,63.21800,NULL),(182,1,5500.00000,NULL),(182,-1,505.74700,NULL),(182,-2,63.21800,NULL),(183,1,25000.00000,NULL),(183,-1,2298.85100,NULL),(183,-2,287.35600,NULL),(186,1,17447.08500,NULL),(186,-1,1604.33000,NULL),(186,-2,200.54100,NULL),(187,1,12500.00000,NULL),(187,-1,1149.42500,NULL),(187,-2,143.67800,NULL),(188,1,5500.00000,NULL),(188,-1,505.74700,NULL),(188,-2,63.21800,NULL),(189,1,5500.00000,NULL),(189,-1,505.74700,NULL),(189,-2,63.21800,NULL),(191,1,7500.00000,NULL),(191,-1,689.65500,NULL),(191,-2,86.20700,NULL),(192,1,5500.00000,NULL),(192,-1,505.74700,NULL),(192,-2,63.21800,NULL),(193,1,10000.00000,NULL),(193,-1,919.54000,NULL),(193,-2,114.94300,NULL),(236,1,5500.00000,NULL),(236,-1,505.74700,NULL),(236,-2,63.21800,NULL),(237,1,5500.00000,NULL),(237,-1,505.74700,NULL),(237,-2,63.21800,NULL),(238,1,5500.00000,NULL),(238,-1,505.74700,NULL),(238,-2,63.21800,NULL),(239,1,5500.00000,NULL),(239,-1,505.74700,NULL),(239,-2,63.21800,NULL),(240,1,17447.08500,NULL),(240,-1,1604.33000,NULL),(240,-2,200.54100,NULL),(241,1,5500.00000,NULL),(241,-1,505.74700,NULL),(241,-2,63.21800,NULL),(242,1,5500.00000,NULL),(242,-1,505.74700,NULL),(242,-2,63.21800,NULL),(243,1,5500.00000,NULL),(243,-1,505.74700,NULL),(243,-2,63.21800,NULL),(244,1,10000.00000,NULL),(244,-1,919.54000,NULL),(244,-2,114.94300,NULL),(245,1,12500.00000,NULL),(245,-1,1149.42500,NULL),(245,-2,143.67800,NULL),(246,1,5500.00000,NULL),(246,-1,505.74700,NULL),(246,-2,63.21800,NULL),(247,1,5500.00000,NULL),(247,-1,505.74700,NULL),(247,-2,63.21800,NULL),(248,1,5500.00000,NULL),(248,-1,505.74700,NULL),(248,-2,63.21800,NULL),(249,1,5500.00000,NULL),(249,-1,505.74700,NULL),(249,-2,63.21800,NULL),(250,1,5500.00000,NULL),(250,-1,505.74700,NULL),(250,-2,63.21800,NULL),(251,1,17447.08500,NULL),(251,-1,1604.33000,NULL),(251,-2,200.54100,NULL),(253,1,5500.00000,NULL),(253,-1,505.74700,NULL),(253,-2,63.21800,NULL),(254,1,5500.00000,NULL),(254,-1,505.74700,NULL),(254,-2,63.21800,NULL),(256,1,5500.00000,NULL),(256,-1,505.74700,NULL),(256,-2,63.21800,NULL),(257,1,5500.00000,NULL),(257,-1,505.74700,NULL),(257,-2,63.21800,NULL),(258,1,10000.00000,NULL),(258,-1,919.54000,NULL),(258,-2,114.94300,NULL),(259,1,5500.00000,NULL),(259,-1,505.74700,NULL),(259,-2,63.21800,NULL),(264,1,5500.00000,NULL),(264,-1,505.74700,NULL),(264,-2,63.21800,NULL),(266,1,10000.00000,NULL),(266,-1,919.54000,NULL),(266,-2,114.94300,NULL),(272,1,5500.00000,NULL),(272,-1,505.74700,NULL),(272,-2,63.21800,NULL),(273,1,10000.00000,NULL),(273,-1,919.54000,NULL),(273,-2,114.94300,NULL),(274,1,5500.00000,NULL),(274,-1,505.74700,NULL),(274,-2,63.21800,NULL),(275,1,5816.58500,NULL),(275,-1,446.00000,NULL),(275,-2,55.75000,NULL),(277,1,7500.00000,NULL),(277,-1,689.65500,NULL),(277,-2,86.20700,NULL),(278,1,4850.25000,NULL),(278,-1,446.00000,NULL),(278,-2,55.75000,NULL),(279,1,7000.00000,NULL),(279,-1,643.67800,NULL),(279,-2,80.46000,NULL),(280,1,5500.00000,NULL),(280,-1,505.74700,NULL),(280,-2,63.21800,NULL),(281,1,5500.00000,NULL),(281,-1,505.74700,NULL),(281,-2,63.21800,NULL),(282,1,5500.00000,NULL),(282,-1,505.74700,NULL),(282,-2,63.21800,NULL),(284,1,10000.00000,NULL),(284,-1,919.54000,NULL),(284,-2,114.94300,NULL),(285,1,12047.22500,NULL),(285,-1,1107.79100,NULL),(285,-2,138.47400,NULL),(286,1,5500.00000,NULL),(286,-1,505.74700,NULL),(286,-2,63.21800,NULL),(287,1,5500.00000,NULL),(287,-1,505.74700,NULL),(287,-2,63.21800,NULL),(289,1,7650.00000,NULL),(289,-1,703.44800,NULL),(289,-2,87.93100,NULL),(291,1,6250.00000,NULL),(291,-1,574.71300,NULL),(291,-2,71.83900,NULL),(292,1,6250.00000,NULL),(292,-1,574.71300,NULL),(292,-2,71.83900,NULL),(294,1,10000.00000,NULL),(294,-1,919.54000,NULL),(294,-2,114.94300,NULL),(295,1,6323.59500,NULL),(295,-1,581.48000,NULL),(295,-2,72.68500,NULL),(296,1,5500.00000,NULL),(296,-1,505.74700,NULL),(296,-2,63.21800,NULL),(297,1,10000.00000,NULL),(297,-1,919.54000,NULL),(297,-2,114.94300,NULL),(300,1,5500.00000,NULL),(300,-1,505.74700,NULL),(300,-2,63.21800,NULL),(305,1,25000.00000,NULL),(305,-1,2298.85100,NULL),(305,-2,287.35600,NULL),(310,1,5500.00000,NULL),(310,-1,505.74700,NULL),(310,-2,63.21800,NULL),(311,1,5500.00000,NULL),(311,-1,505.74700,NULL),(311,-2,63.21800,NULL),(312,1,11350.67500,NULL),(312,-1,1043.74000,NULL),(312,-2,130.46800,NULL),(314,1,5500.00000,NULL),(314,-1,505.74700,NULL),(314,-2,63.21800,NULL),(315,1,5500.00000,NULL),(315,-1,505.74700,NULL),(315,-2,63.21800,NULL),(316,1,5500.00000,NULL),(316,-1,505.74700,NULL),(316,-2,63.21800,NULL),(318,1,5500.00000,NULL),(318,-1,505.74700,NULL),(318,-2,63.21800,NULL),(321,1,5613.75000,NULL),(321,-1,516.20700,NULL),(321,-2,64.52600,NULL),(322,1,5613.75000,NULL),(322,-1,516.20700,NULL),(322,-2,64.52600,NULL),(333,1,5000.00000,NULL),(333,-1,459.77000,NULL),(333,-2,57.47100,NULL),(337,1,8113.75000,NULL),(337,-1,746.09200,NULL),(337,-2,93.26100,NULL),(343,1,8113.75000,NULL),(343,-1,746.09200,NULL),(343,-2,93.26100,NULL),(344,1,5500.00000,NULL),(344,-1,505.74700,NULL),(344,-2,63.21800,NULL),(348,1,5500.00000,NULL),(348,-1,505.74700,NULL),(348,-2,63.21800,NULL),(349,1,5500.00000,NULL),(349,-1,505.74700,NULL),(349,-2,63.21800,NULL),(350,1,5500.00000,NULL),(350,-1,505.74700,NULL),(350,-2,63.21800,NULL),(351,1,5500.00000,NULL),(351,-1,505.74700,NULL),(351,-2,63.21800,NULL),(352,1,5500.00000,NULL),(352,-1,505.74700,NULL),(352,-2,63.21800,NULL),(353,1,5500.00000,NULL),(353,-1,505.74700,NULL),(353,-2,63.21800,NULL),(354,1,5500.00000,NULL),(354,-1,505.74700,NULL),(354,-2,63.21800,NULL),(355,1,5500.00000,NULL),(355,-1,505.74700,NULL),(355,-2,63.21800,NULL),(356,1,12500.00000,NULL),(356,-1,1149.42500,NULL),(356,-2,143.67800,NULL),(357,1,5816.58500,NULL),(357,-1,446.00000,NULL),(357,-2,55.75000,NULL),(358,1,5500.00000,NULL),(358,-1,505.74700,NULL),(358,-2,63.21800,NULL),(359,1,5500.00000,NULL),(359,-1,505.74700,NULL),(359,-2,63.21800,NULL),(360,1,5500.00000,NULL),(360,-1,505.74700,NULL),(360,-2,63.21800,NULL),(361,1,5500.00000,NULL),(361,-1,505.74700,NULL),(361,-2,63.21800,NULL),(362,1,5500.00000,NULL),(362,-1,505.74700,NULL),(362,-2,63.21800,NULL),(363,1,5500.00000,NULL),(363,-1,505.74700,NULL),(363,-2,63.21800,NULL),(364,1,5500.00000,NULL),(364,-1,505.74700,NULL),(364,-2,63.21800,NULL),(365,1,10000.00000,NULL),(365,-1,919.54000,NULL),(365,-2,114.94300,NULL),(367,1,5816.58500,NULL),(367,-1,446.00000,NULL),(367,-2,55.75000,NULL),(368,1,5500.00000,NULL),(368,-1,505.74700,NULL),(368,-2,63.21800,NULL),(369,1,5500.00000,NULL),(369,-1,505.74700,NULL),(369,-2,63.21800,NULL),(370,1,5500.00000,NULL),(370,-1,505.74700,NULL),(370,-2,63.21800,NULL),(371,1,5500.00000,NULL),(371,-1,505.74700,NULL),(371,-2,63.21800,NULL),(372,1,5500.00000,NULL),(372,-1,505.74700,NULL),(372,-2,63.21800,NULL),(373,1,10000.00000,NULL),(373,-1,919.54000,NULL),(373,-2,114.94300,NULL),(374,1,5500.00000,NULL),(374,-1,505.74700,NULL),(374,-2,63.21800,NULL),(375,1,5500.00000,NULL),(375,-1,505.74700,NULL),(375,-2,63.21800,NULL),(376,1,5500.00000,NULL),(376,-1,505.74700,NULL),(376,-2,63.21800,NULL),(377,1,5500.00000,NULL),(377,-1,505.74700,NULL),(377,-2,63.21800,NULL),(378,1,10000.00000,NULL),(378,-1,919.54000,NULL),(378,-2,114.94300,NULL),(380,1,10000.00000,NULL),(380,-1,919.54000,NULL),(380,-2,114.94300,NULL),(381,1,5500.00000,NULL),(381,-1,505.74700,NULL),(381,-2,63.21800,NULL),(382,1,5816.58500,NULL),(382,-1,446.00000,NULL),(382,-2,55.75000,NULL),(383,1,5613.75000,NULL),(383,-1,516.20700,NULL),(383,-2,64.52600,NULL),(384,1,7500.00000,NULL),(384,-1,689.65500,NULL),(384,-2,86.20700,NULL),(385,1,4850.25000,NULL),(385,-1,446.00000,NULL),(385,-2,55.75000,NULL),(386,1,5500.00000,NULL),(386,-1,505.74700,NULL),(386,-2,63.21800,NULL),(387,1,7000.00000,NULL),(387,-1,643.67800,NULL),(387,-2,80.46000,NULL),(388,1,5500.00000,NULL),(388,-1,505.74700,NULL),(388,-2,63.21800,NULL),(389,1,5500.00000,NULL),(389,-1,505.74700,NULL),(389,-2,63.21800,NULL),(390,1,11350.67500,NULL),(390,-1,1043.74000,NULL),(390,-2,130.46800,NULL),(391,1,10000.00000,NULL),(391,-1,919.54000,NULL),(391,-2,114.94300,NULL),(392,1,12047.22500,NULL),(392,-1,1107.79100,NULL),(392,-2,138.47400,NULL),(393,1,5500.00000,NULL),(393,-1,505.74700,NULL),(393,-2,63.21800,NULL),(394,1,5500.00000,NULL),(394,-1,505.74700,NULL),(394,-2,63.21800,NULL),(396,1,7650.00000,NULL),(396,-1,703.44800,NULL),(396,-2,87.93100,NULL),(397,1,5000.00000,NULL),(397,-1,459.77000,NULL),(397,-2,57.47100,NULL),(398,1,6250.00000,NULL),(398,-1,574.71300,NULL),(398,-2,71.83900,NULL),(399,1,6250.00000,NULL),(399,-1,574.71300,NULL),(399,-2,71.83900,NULL),(400,1,5500.00000,NULL),(400,-1,505.74700,NULL),(400,-2,63.21800,NULL),(401,1,6323.59500,NULL),(401,-1,581.48000,NULL),(401,-2,72.68500,NULL),(402,1,10000.00000,NULL),(402,-1,919.54000,NULL),(402,-2,114.94300,NULL),(403,1,5500.00000,NULL),(403,-1,505.74700,NULL),(403,-2,63.21800,NULL),(404,1,10000.00000,NULL),(404,-1,919.54000,NULL),(404,-2,114.94300,NULL),(438,1,5500.00000,NULL),(438,-1,505.74700,NULL),(438,-2,63.21800,NULL),(439,1,5500.00000,NULL),(439,-1,505.74700,NULL),(439,-2,63.21800,NULL),(440,1,5500.00000,NULL),(440,-1,505.74700,NULL),(440,-2,63.21800,NULL),(441,1,5500.00000,NULL),(441,-1,505.74700,NULL),(441,-2,63.21800,NULL),(442,1,5500.00000,NULL),(442,-1,505.74700,NULL),(442,-2,63.21800,NULL),(443,1,5500.00000,NULL),(443,-1,505.74700,NULL),(443,-2,63.21800,NULL),(445,1,5500.00000,NULL),(445,-1,505.74700,NULL),(445,-2,63.21800,NULL),(446,1,12500.00000,NULL),(446,-1,1149.42500,NULL),(446,-2,143.67800,NULL),(448,1,5500.00000,NULL),(448,-1,505.74700,NULL),(448,-2,63.21800,NULL),(449,1,5500.00000,NULL),(449,-1,505.74700,NULL),(449,-2,63.21800,NULL),(451,1,17447.08500,NULL),(451,-1,1604.33000,NULL),(451,-2,200.54100,NULL),(452,1,5500.00000,NULL),(452,-1,505.74700,NULL),(452,-2,63.21800,NULL),(453,1,5500.00000,NULL),(453,-1,505.74700,NULL),(453,-2,63.21800,NULL),(454,1,5500.00000,NULL),(454,-1,505.74700,NULL),(454,-2,63.21800,NULL),(455,1,10000.00000,NULL),(455,-1,919.54000,NULL),(455,-2,114.94300,NULL),(456,1,12500.00000,NULL),(456,-1,1149.42500,NULL),(456,-2,143.67800,NULL),(457,1,5500.00000,NULL),(457,-1,505.74700,NULL),(457,-2,63.21800,NULL),(458,1,5500.00000,NULL),(458,-1,505.74700,NULL),(458,-2,63.21800,NULL),(459,1,5500.00000,NULL),(459,-1,505.74700,NULL),(459,-2,63.21800,NULL),(460,1,5500.00000,NULL),(460,-1,505.74700,NULL),(460,-2,63.21800,NULL),(461,1,5500.00000,NULL),(461,-1,505.74700,NULL),(461,-2,63.21800,NULL),(462,1,17447.08500,NULL),(462,-1,1604.33000,NULL),(462,-2,200.54100,NULL),(463,1,5500.00000,NULL),(463,-1,505.74700,NULL),(463,-2,63.21800,NULL),(464,1,5500.00000,NULL),(464,-1,505.74700,NULL),(464,-2,63.21800,NULL),(465,1,5500.00000,NULL),(465,-1,505.74700,NULL),(465,-2,63.21800,NULL),(466,1,25000.00000,NULL),(466,-1,2298.85100,NULL),(466,-2,287.35600,NULL),(467,1,5500.00000,NULL),(467,-1,505.74700,NULL),(467,-2,63.21800,NULL),(468,1,5500.00000,NULL),(468,-1,505.74700,NULL),(468,-2,63.21800,NULL),(469,1,10000.00000,NULL),(469,-1,919.54000,NULL),(469,-2,114.94300,NULL),(470,1,5500.00000,NULL),(470,-1,505.74700,NULL),(470,-2,63.21800,NULL),(472,1,8113.75000,NULL),(472,-1,746.09200,NULL),(472,-2,93.26100,NULL),(473,1,8113.75000,NULL),(473,-1,746.09200,NULL),(473,-2,93.26100,NULL),(474,1,5500.00000,NULL),(474,-1,505.74700,NULL),(474,-2,63.21800,NULL),(475,1,5500.00000,NULL),(475,-1,505.74700,NULL),(475,-2,63.21800,NULL),(476,1,5500.00000,NULL),(476,-1,505.74700,NULL),(476,-2,63.21800,NULL),(477,1,5816.58500,NULL),(477,-1,446.00000,NULL),(477,-2,55.75000,NULL),(478,1,5500.00000,NULL),(478,-1,505.74700,NULL),(478,-2,63.21800,NULL),(479,1,5500.00000,NULL),(479,-1,505.74700,NULL),(479,-2,63.21800,NULL),(480,1,5500.00000,NULL),(480,-1,505.74700,NULL),(480,-2,63.21800,NULL),(481,1,5500.00000,NULL),(481,-1,505.74700,NULL),(481,-2,63.21800,NULL),(482,1,5500.00000,NULL),(482,-1,505.74700,NULL),(482,-2,63.21800,NULL),(483,1,10000.00000,NULL),(483,-1,919.54000,NULL),(483,-2,114.94300,NULL),(484,1,5500.00000,NULL),(484,-1,505.74700,NULL),(484,-2,63.21800,NULL),(485,1,5500.00000,NULL),(485,-1,505.74700,NULL),(485,-2,63.21800,NULL),(486,1,5500.00000,NULL),(486,-1,505.74700,NULL),(486,-2,63.21800,NULL),(487,1,5500.00000,NULL),(487,-1,505.74700,NULL),(487,-2,63.21800,NULL),(488,1,10000.00000,NULL),(488,-1,919.54000,NULL),(488,-2,114.94300,NULL),(490,1,5500.00000,NULL),(490,-1,505.74700,NULL),(490,-2,63.21800,NULL),(491,1,10000.00000,NULL),(491,-1,919.54000,NULL),(491,-2,114.94300,NULL),(492,1,5816.58500,NULL),(492,-1,446.00000,NULL),(492,-2,55.75000,NULL),(493,1,5613.75000,NULL),(493,-1,516.20700,NULL),(493,-2,64.52600,NULL),(494,1,7500.00000,NULL),(494,-1,689.65500,NULL),(494,-2,86.20700,NULL),(495,1,4850.25000,NULL),(495,-1,446.00000,NULL),(495,-2,55.75000,NULL),(496,1,7000.00000,NULL),(496,-1,643.67800,NULL),(496,-2,80.46000,NULL),(497,1,5500.00000,NULL),(497,-1,505.74700,NULL),(497,-2,63.21800,NULL),(498,1,5500.00000,NULL),(498,-1,505.74700,NULL),(498,-2,63.21800,NULL),(499,1,5500.00000,NULL),(499,-1,505.74700,NULL),(499,-2,63.21800,NULL),(500,1,11350.67500,NULL),(500,-1,1043.74000,NULL),(500,-2,130.46800,NULL),(501,1,10000.00000,NULL),(501,-1,919.54000,NULL),(501,-2,114.94300,NULL),(502,1,12047.22500,NULL),(502,-1,1107.79100,NULL),(502,-2,138.47400,NULL),(503,1,5500.00000,NULL),(503,-1,505.74700,NULL),(503,-2,63.21800,NULL),(504,1,5500.00000,NULL),(504,-1,505.74700,NULL),(504,-2,63.21800,NULL),(506,1,7650.00000,NULL),(506,-1,703.44800,NULL),(506,-2,87.93100,NULL),(507,1,5000.00000,NULL),(507,-1,459.77000,NULL),(507,-2,57.47100,NULL),(508,1,6250.00000,NULL),(508,-1,574.71300,NULL),(508,-2,71.83900,NULL),(509,1,6250.00000,NULL),(509,-1,574.71300,NULL),(509,-2,71.83900,NULL),(510,1,5500.00000,NULL),(510,-1,505.74700,NULL),(510,-2,63.21800,NULL),(511,1,10000.00000,NULL),(511,-1,919.54000,NULL),(511,-2,114.94300,NULL),(512,1,6323.59500,NULL),(512,-1,581.48000,NULL),(512,-2,72.68500,NULL),(513,1,5500.00000,NULL),(513,-1,505.74700,NULL),(513,-2,63.21800,NULL),(514,1,10000.00000,NULL),(514,-1,919.54000,NULL),(514,-2,114.94300,NULL),(515,1,5500.00000,NULL),(515,-1,505.74700,NULL),(515,-2,63.21800,NULL),(516,1,5500.00000,NULL),(516,-1,505.74700,NULL),(516,-2,63.21800,NULL),(518,1,5500.00000,NULL),(518,-1,505.74700,NULL),(518,-2,63.21800,NULL),(519,1,5500.00000,NULL),(519,-1,505.74700,NULL),(519,-2,63.21800,NULL),(520,1,5500.00000,NULL),(520,-1,505.74700,NULL),(520,-2,63.21800,NULL),(521,1,5500.00000,NULL),(521,-1,505.74700,NULL),(521,-2,63.21800,NULL),(522,1,5500.00000,NULL),(522,-1,505.74700,NULL),(522,-2,63.21800,NULL),(523,1,12500.00000,NULL),(523,-1,1149.42500,NULL),(523,-2,143.67800,NULL),(526,1,5500.00000,NULL),(526,-1,505.74700,NULL),(526,-2,63.21800,NULL),(527,1,5500.00000,NULL),(527,-1,505.74700,NULL),(527,-2,63.21800,NULL),(529,1,5500.00000,NULL),(529,-1,505.74700,NULL),(529,-2,63.21800,NULL),(530,1,5500.00000,NULL),(530,-1,505.74700,NULL),(530,-2,63.21800,NULL),(531,1,5500.00000,NULL),(531,-1,505.74700,NULL),(531,-2,63.21800,NULL),(532,1,10000.00000,NULL),(532,-1,919.54000,NULL),(532,-2,114.94300,NULL),(533,1,12500.00000,NULL),(533,-1,1149.42500,NULL),(533,-2,143.67800,NULL),(535,1,5500.00000,NULL),(535,-1,505.74700,NULL),(535,-2,63.21800,NULL),(536,1,5500.00000,NULL),(536,-1,505.74700,NULL),(536,-2,63.21800,NULL),(537,1,5500.00000,NULL),(537,-1,505.74700,NULL),(537,-2,63.21800,NULL),(538,1,5500.00000,NULL),(538,-1,505.74700,NULL),(538,-2,63.21800,NULL),(539,1,17447.08500,NULL),(539,-1,1604.33000,NULL),(539,-2,200.54100,NULL),(540,1,5500.00000,NULL),(540,-1,505.74700,NULL),(540,-2,63.21800,NULL),(541,1,5500.00000,NULL),(541,-1,505.74700,NULL),(541,-2,63.21800,NULL),(542,1,8113.75000,NULL),(542,-1,746.09200,NULL),(542,-2,93.26100,NULL),(543,1,5500.00000,NULL),(543,-1,505.74700,NULL),(543,-2,63.21800,NULL),(545,1,5500.00000,NULL),(545,-1,505.74700,NULL),(545,-2,63.21800,NULL),(546,1,5500.00000,NULL),(546,-1,505.74700,NULL),(546,-2,63.21800,NULL),(547,1,10000.00000,NULL),(547,-1,919.54000,NULL),(547,-2,114.94300,NULL),(548,1,5500.00000,NULL),(548,-1,505.74700,NULL),(548,-2,63.21800,NULL),(551,1,5500.00000,NULL),(551,-1,505.74700,NULL),(551,-2,63.21800,NULL),(552,1,5500.00000,NULL),(552,-1,505.74700,NULL),(552,-2,63.21800,NULL),(554,1,25000.00000,NULL),(554,-1,2298.85100,NULL),(554,-2,287.35600,NULL),(555,1,5500.00000,NULL),(555,-1,505.74700,NULL),(555,-2,63.21800,NULL),(556,1,5500.00000,NULL),(556,-1,505.74700,NULL),(556,-2,63.21800,NULL),(557,1,5500.00000,NULL),(557,-1,505.74700,NULL),(557,-2,63.21800,NULL),(558,1,17447.08500,NULL),(558,-1,1604.33000,NULL),(558,-2,200.54100,NULL),(632,1,5816.58500,NULL),(632,-1,446.00000,NULL),(632,-2,55.75000,NULL),(633,1,5500.00000,NULL),(633,-1,505.74700,NULL),(633,-2,63.21800,NULL),(634,1,5500.00000,NULL),(634,-1,505.74700,NULL),(634,-2,63.21800,NULL),(635,1,5500.00000,NULL),(635,-1,505.74700,NULL),(635,-2,63.21800,NULL),(636,1,5500.00000,NULL),(636,-1,505.74700,NULL),(636,-2,63.21800,NULL),(637,1,5500.00000,NULL),(637,-1,505.74700,NULL),(637,-2,63.21800,NULL),(638,1,10000.00000,NULL),(638,-1,919.54000,NULL),(638,-2,114.94300,NULL),(640,1,5500.00000,NULL),(640,-1,505.74700,NULL),(640,-2,63.21800,NULL),(643,1,10000.00000,NULL),(643,-1,919.54000,NULL),(643,-2,114.94300,NULL),(644,1,5500.00000,NULL),(644,-1,505.74700,NULL),(644,-2,63.21800,NULL),(645,1,10000.00000,NULL),(645,-1,919.54000,NULL),(645,-2,114.94300,NULL),(646,1,5500.00000,NULL),(646,-1,505.74700,NULL),(646,-2,63.21800,NULL),(647,1,5816.58500,NULL),(647,-1,446.00000,NULL),(647,-2,55.75000,NULL),(648,1,5613.75000,NULL),(648,-1,516.20700,NULL),(648,-2,64.52600,NULL),(650,1,4850.25000,NULL),(650,-1,446.00000,NULL),(650,-2,55.75000,NULL),(651,1,5500.00000,NULL),(651,-1,505.74700,NULL),(651,-2,63.21800,NULL),(652,1,7000.00000,NULL),(652,-1,643.67800,NULL),(652,-2,80.46000,NULL),(653,1,5500.00000,NULL),(653,-1,505.74700,NULL),(653,-2,63.21800,NULL),(654,1,5500.00000,NULL),(654,-1,505.74700,NULL),(654,-2,63.21800,NULL),(656,1,10000.00000,NULL),(656,-1,919.54000,NULL),(656,-2,114.94300,NULL),(657,1,12047.22500,NULL),(657,-1,1107.79100,NULL),(657,-2,138.47400,NULL),(658,1,5500.00000,NULL),(658,-1,505.74700,NULL),(658,-2,63.21800,NULL),(659,1,5500.00000,NULL),(659,-1,505.74700,NULL),(659,-2,63.21800,NULL),(660,1,7650.00000,NULL),(660,-1,703.44800,NULL),(660,-2,87.93100,NULL),(661,1,5000.00000,NULL),(661,-1,459.77000,NULL),(661,-2,57.47100,NULL),(662,1,6250.00000,NULL),(662,-1,574.71300,NULL),(662,-2,71.83900,NULL),(663,1,6250.00000,NULL),(663,-1,574.71300,NULL),(663,-2,71.83900,NULL),(664,1,5500.00000,NULL),(664,-1,505.74700,NULL),(664,-2,63.21800,NULL),(665,1,10000.00000,NULL),(665,-1,919.54000,NULL),(665,-2,114.94300,NULL),(666,1,6323.59500,NULL),(666,-1,581.48000,NULL),(666,-2,72.68500,NULL),(667,1,5500.00000,NULL),(667,-1,505.74700,NULL),(667,-2,63.21800,NULL),(668,1,10000.00000,NULL),(668,-1,919.54000,NULL),(668,-2,114.94300,NULL),(669,1,5500.00000,NULL),(669,-1,505.74700,NULL),(669,-2,63.21800,NULL),(670,1,5500.00000,NULL),(670,-1,505.74700,NULL),(670,-2,63.21800,NULL),(671,1,5500.00000,NULL),(671,-1,505.74700,NULL),(671,-2,63.21800,NULL),(673,1,5500.00000,NULL),(673,-1,505.74700,NULL),(673,-2,63.21800,NULL),(674,1,5500.00000,NULL),(674,-1,505.74700,NULL),(674,-2,63.21800,NULL),(676,1,5500.00000,NULL),(676,-1,505.74700,NULL),(676,-2,63.21800,NULL),(677,1,12500.00000,NULL),(677,-1,1149.42500,NULL),(677,-2,143.67800,NULL),(679,1,5500.00000,NULL),(679,-1,505.74700,NULL),(679,-2,63.21800,NULL),(680,1,5500.00000,NULL),(680,-1,505.74700,NULL),(680,-2,63.21800,NULL),(682,1,17447.08500,NULL),(682,-1,1604.33000,NULL),(682,-2,200.54100,NULL),(683,1,5500.00000,NULL),(683,-1,505.74700,NULL),(683,-2,63.21800,NULL),(684,1,5500.00000,NULL),(684,-1,505.74700,NULL),(684,-2,63.21800,NULL),(685,1,5500.00000,NULL),(685,-1,505.74700,NULL),(685,-2,63.21800,NULL),(686,1,10000.00000,NULL),(686,-1,919.54000,NULL),(686,-2,114.94300,NULL),(687,1,12500.00000,NULL),(687,-1,1149.42500,NULL),(687,-2,143.67800,NULL),(688,1,5500.00000,NULL),(688,-1,505.74700,NULL),(688,-2,63.21800,NULL),(689,1,5500.00000,NULL),(689,-1,505.74700,NULL),(689,-2,63.21800,NULL),(690,1,5500.00000,NULL),(690,-1,505.74700,NULL),(690,-2,63.21800,NULL),(691,1,5500.00000,NULL),(691,-1,505.74700,NULL),(691,-2,63.21800,NULL),(692,1,0.00000,NULL),(692,-1,0.00000,NULL),(692,-2,0.00000,NULL),(693,1,17447.08500,NULL),(693,-1,1604.33000,NULL),(693,-2,200.54100,NULL),(694,1,5500.00000,NULL),(694,-1,505.74700,NULL),(694,-2,63.21800,NULL),(695,1,5500.00000,NULL),(695,-1,505.74700,NULL),(695,-2,63.21800,NULL),(696,1,8113.75000,NULL),(696,-1,746.09200,NULL),(696,-2,93.26100,NULL),(697,1,5500.00000,NULL),(697,-1,505.74700,NULL),(697,-2,63.21800,NULL),(698,1,25000.00000,NULL),(698,-1,2298.85100,NULL),(698,-2,287.35600,NULL),(699,1,5500.00000,NULL),(699,-1,505.74700,NULL),(699,-2,63.21800,NULL),(700,1,5500.00000,NULL),(700,-1,505.74700,NULL),(700,-2,63.21800,NULL),(701,1,10000.00000,NULL),(701,-1,919.54000,NULL),(701,-2,114.94300,NULL),(702,1,5500.00000,NULL),(702,-1,505.74700,NULL),(702,-2,63.21800,NULL),(703,1,5500.00000,NULL),(703,-1,505.74700,NULL),(703,-2,63.21800,NULL),(704,1,5500.00000,NULL),(704,-1,505.74700,NULL),(704,-2,63.21800,NULL),(705,1,7500.00000,NULL),(705,-1,689.65500,NULL),(705,-2,86.20700,NULL),(706,1,5500.00000,NULL),(706,-1,505.74700,NULL),(706,-2,63.21800,NULL),(708,1,5500.00000,NULL),(708,-1,505.74713,NULL),(708,-2,63.21839,NULL),(709,1,5500.00000,NULL),(709,-1,505.74713,NULL),(709,-2,63.21839,NULL),(711,1,5500.00000,NULL),(711,-1,505.74713,NULL),(711,-2,63.21839,NULL),(713,1,5500.00000,NULL),(713,-1,505.74713,NULL),(713,-2,63.21839,NULL),(714,1,12500.00000,NULL),(714,-1,1149.42529,NULL),(714,-2,143.67816,NULL),(715,1,5500.00000,NULL),(715,-1,505.74713,NULL),(715,-2,63.21839,NULL),(716,1,5500.00000,NULL),(716,-1,505.74713,NULL),(716,-2,63.21839,NULL),(717,1,17447.08500,NULL),(717,-1,1604.32966,NULL),(717,-2,200.54121,NULL),(718,1,5500.00000,NULL),(718,-1,505.74713,NULL),(718,-2,63.21839,NULL),(719,1,5500.00000,NULL),(719,-1,505.74713,NULL),(719,-2,63.21839,NULL),(720,1,5500.00000,NULL),(720,-1,505.74713,NULL),(720,-2,63.21839,NULL),(721,1,10000.00000,NULL),(721,-1,919.54023,NULL),(721,-2,114.94253,NULL),(722,1,12500.00000,NULL),(722,-1,1149.42529,NULL),(722,-2,143.67816,NULL),(723,1,5500.00000,NULL),(723,-1,505.74713,NULL),(723,-2,63.21839,NULL),(724,1,5500.00000,NULL),(724,-1,505.74713,NULL),(724,-2,63.21839,NULL),(725,1,5500.00000,NULL),(725,-1,505.74713,NULL),(725,-2,63.21839,NULL),(726,1,5500.00000,NULL),(726,-1,505.74713,NULL),(726,-2,63.21839,NULL),(728,1,17447.08500,NULL),(728,-1,1604.32966,NULL),(728,-2,200.54121,NULL),(729,1,5500.00000,NULL),(729,-1,505.74713,NULL),(729,-2,63.21839,NULL),(730,1,5500.00000,NULL),(730,-1,505.74713,NULL),(730,-2,63.21839,NULL),(731,1,8113.75000,NULL),(731,-1,746.09195,NULL),(731,-2,93.26149,NULL),(732,1,5500.00000,NULL),(732,-1,505.74713,NULL),(732,-2,63.21839,NULL),(733,1,25000.00000,NULL),(733,-1,2298.85057,NULL),(733,-2,287.35632,NULL),(734,1,5500.00000,NULL),(734,-1,505.74713,NULL),(734,-2,63.21839,NULL),(735,1,5500.00000,NULL),(735,-1,505.74713,NULL),(735,-2,63.21839,NULL),(736,1,10000.00000,NULL),(736,-1,919.54023,NULL),(736,-2,114.94253,NULL),(737,1,5500.00000,NULL),(737,-1,505.74713,NULL),(737,-2,63.21839,NULL),(738,1,5816.58500,NULL),(738,-1,446.00013,NULL),(738,-2,55.75002,NULL),(739,1,5500.00000,NULL),(739,-1,505.74713,NULL),(739,-2,63.21839,NULL),(740,1,5500.00000,NULL),(740,-1,505.74713,NULL),(740,-2,63.21839,NULL),(741,1,5500.00000,NULL),(741,-1,505.74713,NULL),(741,-2,63.21839,NULL),(742,1,5500.00000,NULL),(742,-1,505.74713,NULL),(742,-2,63.21839,NULL),(743,1,5500.00000,NULL),(743,-1,505.74713,NULL),(743,-2,63.21839,NULL),(744,1,10000.00000,NULL),(744,-1,919.54023,NULL),(744,-2,114.94253,NULL),(745,1,5500.00000,NULL),(745,-1,505.74713,NULL),(745,-2,63.21839,NULL),(746,1,5500.00000,NULL),(746,-1,505.74713,NULL),(746,-2,63.21839,NULL),(747,1,5500.00000,NULL),(747,-1,505.74713,NULL),(747,-2,63.21839,NULL),(748,1,5500.00000,NULL),(748,-1,505.74713,NULL),(748,-2,63.21839,NULL),(749,1,10000.00000,NULL),(749,-1,919.54023,NULL),(749,-2,114.94253,NULL),(750,1,5500.00000,NULL),(750,-1,505.74713,NULL),(750,-2,63.21839,NULL),(751,1,5500.00000,NULL),(751,-1,505.74713,NULL),(751,-2,63.21839,NULL),(752,1,10000.00000,NULL),(752,-1,919.54023,NULL),(752,-2,114.94253,NULL),(753,1,5816.58500,NULL),(753,-1,446.00013,NULL),(753,-2,55.75002,NULL),(755,1,7500.00000,NULL),(755,-1,689.65517,NULL),(755,-2,86.20690,NULL),(756,1,4850.25000,NULL),(756,-1,446.00000,NULL),(756,-2,55.75000,NULL),(757,1,7000.00000,NULL),(757,-1,643.67816,NULL),(757,-2,80.45977,NULL),(758,1,5500.00000,NULL),(758,-1,505.74713,NULL),(758,-2,63.21839,NULL),(759,1,5500.00000,NULL),(759,-1,505.74713,NULL),(759,-2,63.21839,NULL),(760,1,5500.00000,NULL),(760,-1,505.74713,NULL),(760,-2,63.21839,NULL),(762,1,10000.00000,NULL),(762,-1,919.54023,NULL),(762,-2,114.94253,NULL),(763,1,12047.22500,NULL),(763,-1,1107.79080,NULL),(763,-2,138.47385,NULL),(764,1,5500.00000,NULL),(764,-1,505.74713,NULL),(764,-2,63.21839,NULL),(765,1,5500.00000,NULL),(765,-1,505.74713,NULL),(765,-2,63.21839,NULL),(766,1,7650.00000,NULL),(766,-1,703.44828,NULL),(766,-2,87.93103,NULL),(768,1,6250.00000,NULL),(768,-1,574.71264,NULL),(768,-2,71.83908,NULL),(769,1,6250.00000,NULL),(769,-1,574.71264,NULL),(769,-2,71.83908,NULL),(770,1,5500.00000,NULL),(770,-1,505.74713,NULL),(770,-2,63.21839,NULL),(771,1,10000.00000,NULL),(771,-1,919.54023,NULL),(771,-2,114.94253,NULL),(772,1,6323.59500,NULL),(772,-1,581.48000,NULL),(772,-2,72.68500,NULL),(773,1,5500.00000,NULL),(773,-1,505.74713,NULL),(773,-2,63.21839,NULL),(774,1,10000.00000,NULL),(774,-1,919.54023,NULL),(774,-2,114.94253,NULL),(776,1,5613.75000,NULL),(776,-1,516.20690,NULL),(776,-2,64.52586,NULL),(777,1,5000.00000,NULL),(777,-1,459.77011,NULL),(777,-2,57.47126,NULL),(778,1,5500.00000,NULL),(778,-1,505.74713,NULL),(778,-2,63.21839,NULL),(785,1,5500.00000,NULL),(785,-1,505.74713,NULL),(785,-2,63.21839,NULL),(791,1,5500.00000,NULL),(791,-1,505.74700,NULL),(791,-2,63.21800,NULL),(793,1,5500.00000,NULL),(793,-1,505.74700,NULL),(793,-2,63.21800,NULL),(794,1,5500.00000,NULL),(794,-1,505.74700,NULL),(794,-2,63.21800,NULL),(1872,1,10000.00000,NULL),(1872,-1,919.54023,NULL),(1872,-2,114.94253,NULL),(1873,1,10000.00000,NULL),(1873,-1,919.54023,NULL),(1873,-2,114.94253,NULL),(1992,1,5947.00000,NULL),(1992,-1,456.00000,NULL),(1992,-2,57.00000,NULL),(1993,1,5500.00000,NULL),(1993,-1,505.74713,NULL),(1993,-2,63.21839,NULL),(1994,1,7500.00000,NULL),(1994,-1,689.65517,NULL),(1994,-2,86.20690,NULL),(1995,1,5500.00000,NULL),(1995,-1,505.74713,NULL),(1995,-2,63.21839,NULL),(1997,1,5500.00000,NULL),(1997,-1,505.74713,NULL),(1997,-2,63.21839,NULL),(1998,1,5500.00000,NULL),(1998,-1,505.74713,NULL),(1998,-2,63.21839,NULL),(1999,1,5500.00000,NULL),(1999,-1,505.74713,NULL),(1999,-2,63.21839,NULL),(2000,1,5500.00000,NULL),(2000,-1,505.74713,NULL),(2000,-2,63.21839,NULL),(2001,1,5500.00000,NULL),(2001,-1,505.74713,NULL),(2001,-2,63.21839,NULL),(2002,1,5500.00000,NULL),(2002,-1,505.74713,NULL),(2002,-2,63.21839,NULL),(2003,1,5500.00000,NULL),(2003,-1,505.74713,NULL),(2003,-2,63.21839,NULL),(2004,1,5500.00000,NULL),(2004,-1,505.74713,NULL),(2004,-2,63.21839,NULL),(2005,1,6000.00000,NULL),(2005,-1,551.72414,NULL),(2005,-2,68.96552,NULL),(2007,1,8084.87500,NULL),(2007,-1,743.43678,NULL),(2007,-2,92.92960,NULL),(2008,1,10000.00000,NULL),(2008,-1,919.54023,NULL),(2008,-2,114.94253,NULL),(2009,1,6138.00000,NULL),(2009,-1,564.41379,NULL),(2009,-2,70.55172,NULL),(2010,1,5500.00000,NULL),(2010,-1,505.74713,NULL),(2010,-2,63.21839,NULL),(2011,1,12500.00000,NULL),(2011,-1,1149.42529,NULL),(2011,-2,143.67816,NULL),(2013,1,5500.00000,NULL),(2013,-1,505.74713,NULL),(2013,-2,63.21839,NULL),(2014,1,5500.00000,NULL),(2014,-1,505.74713,NULL),(2014,-2,63.21839,NULL),(2015,1,5500.00000,NULL),(2015,-1,505.74713,NULL),(2015,-2,63.21839,NULL),(2016,1,4959.00000,NULL),(2016,-1,456.00000,NULL),(2016,-2,57.00000,NULL),(2017,1,12500.00000,NULL),(2017,-1,1149.42529,NULL),(2017,-2,143.67816,NULL),(2018,1,5500.00000,NULL),(2018,-1,505.74713,NULL),(2018,-2,63.21839,NULL),(2019,1,5500.00000,NULL),(2019,-1,505.74713,NULL),(2019,-2,63.21839,NULL),(2020,1,10000.00000,NULL),(2020,-1,919.54023,NULL),(2020,-2,114.94253,NULL),(2021,1,5500.00000,NULL),(2021,-1,505.74713,NULL),(2021,-2,63.21839,NULL),(2022,1,10000.00000,NULL),(2022,-1,919.54023,NULL),(2022,-2,114.94253,NULL),(2023,1,6083.48000,NULL),(2023,-1,559.40046,NULL),(2023,-2,69.92506,NULL),(2024,1,5500.00000,NULL),(2024,-1,505.74713,NULL),(2024,-2,63.21839,NULL),(2026,1,5500.00000,NULL),(2026,-1,505.74713,NULL),(2026,-2,63.21839,NULL),(2027,1,7500.00000,NULL),(2027,-1,689.65517,NULL),(2027,-2,86.20690,NULL),(2028,1,5500.00000,NULL),(2028,-1,505.74713,NULL),(2028,-2,63.21839,NULL),(2029,1,5500.00000,NULL),(2029,-1,505.74713,NULL),(2029,-2,63.21839,NULL),(2030,1,10000.00000,NULL),(2030,-1,919.54023,NULL),(2030,-2,114.94253,NULL),(2031,1,5000.00000,NULL),(2031,-1,459.77011,NULL),(2031,-2,57.47126,NULL),(2032,1,5946.00000,NULL),(2032,-1,455.92332,NULL),(2032,-2,56.99042,NULL),(2033,1,5947.00000,NULL),(2033,-1,456.00000,NULL),(2033,-2,57.00000,NULL),(2035,1,5500.00000,NULL),(2035,-1,505.74713,NULL),(2035,-2,63.21839,NULL),(2036,1,5500.00000,NULL),(2036,-1,505.74713,NULL),(2036,-2,63.21839,NULL),(2037,1,5500.00000,NULL),(2037,-1,505.74713,NULL),(2037,-2,63.21839,NULL),(2038,1,5500.00000,NULL),(2038,-1,505.74713,NULL),(2038,-2,63.21839,NULL),(2040,1,5500.00000,NULL),(2040,-1,505.74713,NULL),(2040,-2,63.21839,NULL),(2042,1,11998.57500,NULL),(2042,-1,1103.31724,NULL),(2042,-2,137.91466,NULL),(2043,1,5500.00000,NULL),(2043,-1,505.74713,NULL),(2043,-2,63.21839,NULL),(2044,1,17447.08500,NULL),(2044,-1,1604.32966,NULL),(2044,-2,200.54121,NULL),(2045,1,5500.00000,NULL),(2045,-1,505.74713,NULL),(2045,-2,63.21839,NULL),(2046,1,5500.00000,NULL),(2046,-1,505.74713,NULL),(2046,-2,63.21839,NULL),(2047,1,12047.22500,NULL),(2047,-1,1107.79080,NULL),(2047,-2,138.47385,NULL),(2048,1,5500.00000,NULL),(2048,-1,505.74713,NULL),(2048,-2,63.21839,NULL),(2049,1,5500.00000,NULL),(2049,-1,505.74713,NULL),(2049,-2,63.21839,NULL),(2051,1,7500.00000,NULL),(2051,-1,689.65517,NULL),(2051,-2,86.20690,NULL),(2052,1,9215.50000,NULL),(2052,-1,847.40230,NULL),(2052,-2,105.92529,NULL),(2053,1,7500.00000,NULL),(2053,-1,689.65517,NULL),(2053,-2,86.20690,NULL),(2055,1,25000.00000,NULL),(2055,-1,2298.85057,NULL),(2055,-2,287.35632,NULL),(2056,1,5500.00000,NULL),(2056,-1,505.74713,NULL),(2056,-2,63.21839,NULL),(2057,1,5500.00000,NULL),(2057,-1,505.74713,NULL),(2057,-2,63.21839,NULL),(2058,1,6250.00000,NULL),(2058,-1,574.71264,NULL),(2058,-2,71.83908,NULL),(2059,1,5000.00000,NULL),(2059,-1,459.77011,NULL),(2059,-2,57.47126,NULL),(2060,1,6250.00000,NULL),(2060,-1,574.71264,NULL),(2060,-2,71.83908,NULL),(2062,1,6500.00000,NULL),(2062,-1,597.70115,NULL),(2062,-2,74.71264,NULL),(2063,1,6323.59500,NULL),(2063,-1,581.48000,NULL),(2063,-2,72.68500,NULL),(2064,1,5500.00000,NULL),(2064,-1,505.74713,NULL),(2064,-2,63.21839,NULL),(2065,1,10000.00000,NULL),(2065,-1,919.54023,NULL),(2065,-2,114.94253,NULL),(2067,1,5000.00000,NULL),(2067,-1,459.77011,NULL),(2067,-2,57.47126,NULL),(2068,1,5000.00000,NULL),(2068,-1,459.77011,NULL),(2068,-2,57.47126,NULL),(2069,1,5500.00000,NULL),(2069,-1,505.74713,NULL),(2069,-2,63.21839,NULL),(2070,1,5000.00000,NULL),(2070,-1,459.77011,NULL),(2070,-2,57.47126,NULL),(2071,1,9601.47000,NULL),(2071,-1,882.89379,NULL),(2071,-2,110.36172,NULL),(2072,1,12847.08500,NULL),(2072,-1,1181.34115,NULL),(2072,-2,147.66764,NULL),(2073,1,5600.00000,NULL),(2073,-1,514.94253,NULL),(2073,-2,64.36782,NULL),(2075,1,6179.75000,NULL),(2075,-1,568.25287,NULL),(2075,-2,71.03161,NULL),(2077,1,6315.63310,NULL),(2077,-1,1052.60552,NULL),(2077,-2,131.57569,NULL),(2078,1,5947.00000,NULL),(2078,-1,546.85057,NULL),(2078,-2,68.35632,NULL),(2079,1,12500.00000,NULL),(2079,-1,1149.42529,NULL),(2079,-2,143.67816,NULL),(2080,1,2432.64368,NULL),(2080,-1,608.16092,NULL),(2080,-2,76.02011,NULL),(2081,1,8365.63218,NULL),(2081,-1,798.96552,NULL),(2081,-2,99.87069,NULL),(2085,1,4551.72414,NULL),(2085,-1,758.62069,NULL),(2085,-2,94.82759,NULL),(2916,1,4597.70115,NULL),(2916,-1,919.54023,NULL),(2916,-2,114.94253,NULL),(2917,1,4597.70115,NULL),(2917,-1,919.54023,NULL),(2917,-2,114.94253,NULL),(3579,1,5500.00000,NULL),(3579,-1,505.74713,NULL),(3579,-2,63.21839,NULL),(3666,1,5947.00000,NULL),(3666,-1,456.00000,NULL),(3666,-2,57.00000,NULL),(3667,1,5500.00000,NULL),(3667,-1,505.74713,NULL),(3667,-2,63.21839,NULL),(3668,1,7500.00000,NULL),(3668,-1,689.65517,NULL),(3668,-2,86.20690,NULL),(3669,1,5500.00000,NULL),(3669,-1,505.74713,NULL),(3669,-2,63.21839,NULL),(3670,1,5600.00000,NULL),(3670,-1,429.39297,NULL),(3670,-2,53.67412,NULL),(3671,1,5500.00000,NULL),(3671,-1,505.74713,NULL),(3671,-2,63.21839,NULL),(3672,1,5500.00000,NULL),(3672,-1,505.74713,NULL),(3672,-2,63.21839,NULL),(3673,1,5500.00000,NULL),(3673,-1,505.74713,NULL),(3673,-2,63.21839,NULL),(3674,1,5500.00000,NULL),(3674,-1,505.74713,NULL),(3674,-2,63.21839,NULL),(3675,1,5500.00000,NULL),(3675,-1,505.74713,NULL),(3675,-2,63.21839,NULL),(3676,1,5500.00000,NULL),(3676,-1,505.74713,NULL),(3676,-2,63.21839,NULL),(3677,1,5500.00000,NULL),(3677,-1,505.74713,NULL),(3677,-2,63.21839,NULL),(3678,1,5500.00000,NULL),(3678,-1,505.74713,NULL),(3678,-2,63.21839,NULL),(3679,1,6000.00000,NULL),(3679,-1,551.72414,NULL),(3679,-2,68.96552,NULL),(3680,1,12847.08500,NULL),(3680,-1,1181.34115,NULL),(3680,-2,147.66764,NULL),(3681,1,8084.87500,NULL),(3681,-1,743.43678,NULL),(3681,-2,92.92960,NULL),(3682,1,10000.00000,NULL),(3682,-1,919.54023,NULL),(3682,-2,114.94253,NULL),(3683,1,6138.00000,NULL),(3683,-1,564.41379,NULL),(3683,-2,70.55172,NULL),(3684,1,5500.00000,NULL),(3684,-1,505.74713,NULL),(3684,-2,63.21839,NULL),(3685,1,12500.00000,NULL),(3685,-1,1149.42529,NULL),(3685,-2,143.67816,NULL),(3686,1,9655.17241,NULL),(3686,-1,1379.31034,NULL),(3686,-2,172.41379,NULL),(3687,1,11447.08500,NULL),(3687,-1,1052.60552,NULL),(3687,-2,131.57569,NULL),(3688,1,5500.00000,NULL),(3688,-1,505.74713,NULL),(3688,-2,63.21839,NULL),(3689,1,5500.00000,NULL),(3689,-1,505.74713,NULL),(3689,-2,63.21839,NULL),(3690,1,5500.00000,NULL),(3690,-1,505.74713,NULL),(3690,-2,63.21839,NULL),(3691,1,4959.00000,NULL),(3691,-1,456.00000,NULL),(3691,-2,57.00000,NULL),(3692,1,12500.00000,NULL),(3692,-1,1149.42529,NULL),(3692,-2,143.67816,NULL),(3693,1,5500.00000,NULL),(3693,-1,505.74713,NULL),(3693,-2,63.21839,NULL),(3694,1,5500.00000,NULL),(3694,-1,505.74713,NULL),(3694,-2,63.21839,NULL),(3695,1,10000.00000,NULL),(3695,-1,919.54023,NULL),(3695,-2,114.94253,NULL),(3696,1,10000.00000,NULL),(3696,-1,919.54023,NULL),(3696,-2,114.94253,NULL),(3697,1,5500.00000,NULL),(3697,-1,505.74713,NULL),(3697,-2,63.21839,NULL),(3698,1,10000.00000,NULL),(3698,-1,919.54023,NULL),(3698,-2,114.94253,NULL),(3699,1,6083.48000,NULL),(3699,-1,559.40046,NULL),(3699,-2,69.92506,NULL),(3700,1,5500.00000,NULL),(3700,-1,505.74713,NULL),(3700,-2,63.21839,NULL),(3701,1,5947.00000,NULL),(3701,-1,546.85057,NULL),(3701,-2,68.35632,NULL),(3702,1,5500.00000,NULL),(3702,-1,505.74713,NULL),(3702,-2,63.21839,NULL),(3703,1,7500.00000,NULL),(3703,-1,689.65517,NULL),(3703,-2,86.20690,NULL),(3704,1,5500.00000,NULL),(3704,-1,505.74713,NULL),(3704,-2,63.21839,NULL),(3705,1,5500.00000,NULL),(3705,-1,505.74713,NULL),(3705,-2,63.21839,NULL),(3706,1,5000.00000,NULL),(3706,-1,459.77011,NULL),(3706,-2,57.47126,NULL),(3707,1,5946.00000,NULL),(3707,-1,455.92332,NULL),(3707,-2,56.99042,NULL),(3708,1,5947.00000,NULL),(3708,-1,456.00000,NULL),(3708,-2,57.00000,NULL),(3709,1,12500.00000,NULL),(3709,-1,1149.42529,NULL),(3709,-2,143.67816,NULL),(3710,1,5517.24138,NULL),(3710,-1,919.54023,NULL),(3710,-2,114.94253,NULL),(3711,1,5500.00000,NULL),(3711,-1,505.74713,NULL),(3711,-2,63.21839,NULL),(3712,1,5500.00000,NULL),(3712,-1,505.74713,NULL),(3712,-2,63.21839,NULL),(3713,1,5500.00000,NULL),(3713,-1,505.74713,NULL),(3713,-2,63.21839,NULL),(3714,1,5500.00000,NULL),(3714,-1,505.74713,NULL),(3714,-2,63.21839,NULL),(3715,1,5500.00000,NULL),(3715,-1,505.74713,NULL),(3715,-2,63.21839,NULL),(3716,1,5500.00000,NULL),(3716,-1,505.74713,NULL),(3716,-2,63.21839,NULL),(3717,1,6613.75000,NULL),(3717,-1,608.16092,NULL),(3717,-2,76.02011,NULL),(3718,1,11998.57500,NULL),(3718,-1,1103.31724,NULL),(3718,-2,137.91466,NULL),(3719,1,5500.00000,NULL),(3719,-1,505.74713,NULL),(3719,-2,63.21839,NULL),(3720,1,17447.08500,NULL),(3720,-1,1604.32966,NULL),(3720,-2,200.54121,NULL),(3721,1,5500.00000,NULL),(3721,-1,505.74713,NULL),(3721,-2,63.21839,NULL),(3722,1,5500.00000,NULL),(3722,-1,505.74713,NULL),(3722,-2,63.21839,NULL),(3723,1,12047.22500,NULL),(3723,-1,1107.79080,NULL),(3723,-2,138.47385,NULL),(3724,1,5500.00000,NULL),(3724,-1,505.74713,NULL),(3724,-2,63.21839,NULL),(3725,1,5500.00000,NULL),(3725,-1,505.74713,NULL),(3725,-2,63.21839,NULL),(3726,1,8688.75000,NULL),(3726,-1,798.96552,NULL),(3726,-2,99.87069,NULL),(3727,1,7500.00000,NULL),(3727,-1,689.65517,NULL),(3727,-2,86.20690,NULL),(3728,1,1011.49425,NULL),(3728,-1,505.74713,NULL),(3728,-2,63.21839,NULL),(3729,1,9215.50000,NULL),(3729,-1,847.40230,NULL),(3729,-2,105.92529,NULL),(3730,1,7500.00000,NULL),(3730,-1,689.65517,NULL),(3730,-2,86.20690,NULL),(3731,1,6179.75000,NULL),(3731,-1,568.25287,NULL),(3731,-2,71.03161,NULL),(3732,1,25000.00000,NULL),(3732,-1,2298.85057,NULL),(3732,-2,287.35632,NULL),(3733,1,5500.00000,NULL),(3733,-1,505.74713,NULL),(3733,-2,63.21839,NULL),(3734,1,5500.00000,NULL),(3734,-1,505.74713,NULL),(3734,-2,63.21839,NULL),(3735,1,6250.00000,NULL),(3735,-1,574.71264,NULL),(3735,-2,71.83908,NULL),(3736,1,5000.00000,NULL),(3736,-1,459.77011,NULL),(3736,-2,57.47126,NULL),(3737,1,6250.00000,NULL),(3737,-1,574.71264,NULL),(3737,-2,71.83908,NULL),(3738,1,6500.00000,NULL),(3738,-1,597.70115,NULL),(3738,-2,74.71264,NULL),(3739,1,6323.59500,NULL),(3739,-1,581.48000,NULL),(3739,-2,72.68500,NULL),(3740,1,5500.00000,NULL),(3740,-1,505.74713,NULL),(3740,-2,63.21839,NULL),(3741,1,10000.00000,NULL),(3741,-1,919.54023,NULL),(3741,-2,114.94253,NULL),(3742,1,9601.47000,NULL),(3742,-1,882.89379,NULL),(3742,-2,110.36172,NULL),(3743,1,5000.00000,NULL),(3743,-1,459.77011,NULL),(3743,-2,57.47126,NULL),(3744,1,5000.00000,NULL),(3744,-1,459.77011,NULL),(3744,-2,57.47126,NULL),(3745,1,5517.24138,NULL),(3745,-1,919.54023,NULL),(3745,-2,114.94253,NULL),(3746,1,5500.00000,NULL),(3746,-1,505.74713,NULL),(3746,-2,63.21839,NULL),(3747,1,5000.00000,NULL),(3747,-1,459.77011,NULL),(3747,-2,57.47126,NULL),(4382,1,5947.00000,NULL),(4382,-1,456.00000,NULL),(4382,-2,57.00000,NULL),(4383,1,5500.00000,NULL),(4383,-1,505.74713,NULL),(4383,-2,63.21839,NULL),(4384,1,7500.00000,NULL),(4384,-1,689.65517,NULL),(4384,-2,86.20690,NULL),(4386,1,5600.00000,NULL),(4386,-1,429.39297,NULL),(4386,-2,53.67412,NULL),(4387,1,5500.00000,NULL),(4387,-1,505.74713,NULL),(4387,-2,63.21839,NULL),(4388,1,5500.00000,NULL),(4388,-1,505.74713,NULL),(4388,-2,63.21839,NULL),(4389,1,5500.00000,NULL),(4389,-1,505.74713,NULL),(4389,-2,63.21839,NULL),(4391,1,5500.00000,NULL),(4391,-1,505.74713,NULL),(4391,-2,63.21839,NULL),(4392,1,5500.00000,NULL),(4392,-1,505.74713,NULL),(4392,-2,63.21839,NULL),(4393,1,5500.00000,NULL),(4393,-1,505.74713,NULL),(4393,-2,63.21839,NULL),(4394,1,5500.00000,NULL),(4394,-1,505.74713,NULL),(4394,-2,63.21839,NULL),(4395,1,12847.08500,NULL),(4395,-1,1181.34115,NULL),(4395,-2,147.66764,NULL),(4396,1,8084.87500,NULL),(4396,-1,743.43678,NULL),(4396,-2,92.92960,NULL),(4397,1,10000.00000,NULL),(4397,-1,919.54023,NULL),(4397,-2,114.94253,NULL),(4398,1,6138.00000,NULL),(4398,-1,564.41379,NULL),(4398,-2,70.55172,NULL),(4399,1,5500.00000,NULL),(4399,-1,505.74713,NULL),(4399,-2,63.21839,NULL),(4400,1,12500.00000,NULL),(4400,-1,1149.42529,NULL),(4400,-2,143.67816,NULL),(4401,1,15000.00000,NULL),(4401,-1,1379.31034,NULL),(4401,-2,172.41379,NULL),(4402,1,11447.08500,NULL),(4402,-1,1052.60552,NULL),(4402,-2,131.57569,NULL),(4403,1,5500.00000,NULL),(4403,-1,505.74713,NULL),(4403,-2,63.21839,NULL),(4404,1,5500.00000,NULL),(4404,-1,505.74713,NULL),(4404,-2,63.21839,NULL),(4405,1,5500.00000,NULL),(4405,-1,505.74713,NULL),(4405,-2,63.21839,NULL),(4406,1,4959.00000,NULL),(4406,-1,456.00000,NULL),(4406,-2,57.00000,NULL),(4407,1,12500.00000,NULL),(4407,-1,1149.42529,NULL),(4407,-2,143.67816,NULL),(4408,1,5500.00000,NULL),(4408,-1,505.74713,NULL),(4408,-2,63.21839,NULL),(4409,1,5500.00000,NULL),(4409,-1,505.74713,NULL),(4409,-2,63.21839,NULL),(4410,1,10000.00000,NULL),(4410,-1,919.54023,NULL),(4410,-2,114.94253,NULL),(4411,1,5500.00000,NULL),(4411,-1,505.74713,NULL),(4411,-2,63.21839,NULL),(4412,1,10000.00000,NULL),(4412,-1,919.54023,NULL),(4412,-2,114.94253,NULL),(4413,1,10000.00000,NULL),(4413,-1,919.54023,NULL),(4413,-2,114.94253,NULL),(4414,1,6083.48000,NULL),(4414,-1,559.40046,NULL),(4414,-2,69.92506,NULL),(4415,1,5500.00000,NULL),(4415,-1,505.74713,NULL),(4415,-2,63.21839,NULL),(4416,1,5947.00000,NULL),(4416,-1,546.85057,NULL),(4416,-2,68.35632,NULL),(4417,1,7500.00000,NULL),(4417,-1,689.65517,NULL),(4417,-2,86.20690,NULL),(4418,1,5500.00000,NULL),(4418,-1,505.74713,NULL),(4418,-2,63.21839,NULL),(4419,1,5500.00000,NULL),(4419,-1,505.74713,NULL),(4419,-2,63.21839,NULL),(4420,1,5500.00000,NULL),(4420,-1,505.74713,NULL),(4420,-2,63.21839,NULL),(4422,1,5946.00000,NULL),(4422,-1,455.92332,NULL),(4422,-2,56.99042,NULL),(4423,1,5947.00000,NULL),(4423,-1,456.00000,NULL),(4423,-2,57.00000,NULL),(4424,1,10000.00000,NULL),(4424,-1,919.54023,NULL),(4424,-2,114.94253,NULL),(4425,1,12500.00000,NULL),(4425,-1,1149.42529,NULL),(4425,-2,143.67816,NULL),(4426,1,5500.00000,NULL),(4426,-1,505.74713,NULL),(4426,-2,63.21839,NULL),(4427,1,5500.00000,NULL),(4427,-1,505.74713,NULL),(4427,-2,63.21839,NULL),(4428,1,5500.00000,NULL),(4428,-1,505.74713,NULL),(4428,-2,63.21839,NULL),(4429,1,5500.00000,NULL),(4429,-1,505.74713,NULL),(4429,-2,63.21839,NULL),(4430,1,5500.00000,NULL),(4430,-1,505.74713,NULL),(4430,-2,63.21839,NULL),(4431,1,5500.00000,NULL),(4431,-1,505.74713,NULL),(4431,-2,63.21839,NULL),(4432,1,6613.75000,NULL),(4432,-1,608.16092,NULL),(4432,-2,76.02011,NULL),(4434,1,5500.00000,NULL),(4434,-1,505.74713,NULL),(4434,-2,63.21839,NULL),(4435,1,17447.08500,NULL),(4435,-1,1604.32966,NULL),(4435,-2,200.54121,NULL),(4436,1,5500.00000,NULL),(4436,-1,505.74713,NULL),(4436,-2,63.21839,NULL),(4437,1,5500.00000,NULL),(4437,-1,505.74713,NULL),(4437,-2,63.21839,NULL),(4438,1,12047.22500,NULL),(4438,-1,1107.79080,NULL),(4438,-2,138.47385,NULL),(4439,1,5500.00000,NULL),(4439,-1,505.74713,NULL),(4439,-2,63.21839,NULL),(4440,1,5500.00000,NULL),(4440,-1,505.74713,NULL),(4440,-2,63.21839,NULL),(4441,1,8688.75000,NULL),(4441,-1,798.96552,NULL),(4441,-2,99.87069,NULL),(4442,1,7500.00000,NULL),(4442,-1,689.65517,NULL),(4442,-2,86.20690,NULL),(4443,1,5500.00000,NULL),(4443,-1,505.74713,NULL),(4443,-2,63.21839,NULL),(4444,1,9215.50000,NULL),(4444,-1,847.40230,NULL),(4444,-2,105.92529,NULL),(4445,1,7500.00000,NULL),(4445,-1,689.65517,NULL),(4445,-2,86.20690,NULL),(4446,1,6179.75000,NULL),(4446,-1,568.25287,NULL),(4446,-2,71.03161,NULL),(4447,1,25000.00000,NULL),(4447,-1,2298.85057,NULL),(4447,-2,287.35632,NULL),(4448,1,5500.00000,NULL),(4448,-1,505.74713,NULL),(4448,-2,63.21839,NULL),(4449,1,5500.00000,NULL),(4449,-1,505.74713,NULL),(4449,-2,63.21839,NULL),(4450,1,6250.00000,NULL),(4450,-1,574.71264,NULL),(4450,-2,71.83908,NULL),(4451,1,5000.00000,NULL),(4451,-1,459.77011,NULL),(4451,-2,57.47126,NULL),(4452,1,6250.00000,NULL),(4452,-1,574.71264,NULL),(4452,-2,71.83908,NULL),(4453,1,6500.00000,NULL),(4453,-1,597.70115,NULL),(4453,-2,74.71264,NULL),(4454,1,6323.59500,NULL),(4454,-1,581.48000,NULL),(4454,-2,72.68500,NULL),(4455,1,5500.00000,NULL),(4455,-1,505.74713,NULL),(4455,-2,63.21839,NULL),(4457,1,9601.47000,NULL),(4457,-1,882.89379,NULL),(4457,-2,110.36172,NULL),(4458,1,5000.00000,NULL),(4458,-1,459.77011,NULL),(4458,-2,57.47126,NULL),(4459,1,5000.00000,NULL),(4459,-1,459.77011,NULL),(4459,-2,57.47126,NULL),(4460,1,10000.00000,NULL),(4460,-1,919.54023,NULL),(4460,-2,114.94253,NULL),(4461,1,5500.00000,NULL),(4461,-1,505.74713,NULL),(4461,-2,63.21839,NULL),(4462,1,5000.00000,NULL),(4462,-1,459.77011,NULL),(4462,-2,57.47126,NULL),(4466,1,4045.97701,NULL),(4466,-1,505.74713,NULL),(4466,-2,63.21839,NULL),(4467,1,1517.24138,NULL),(4467,-1,505.74713,NULL),(4467,-2,63.21839,NULL),(4468,1,5000.00000,NULL),(4468,-1,459.77011,NULL),(4468,-2,57.47126,NULL),(4553,1,6000.00000,NULL),(4553,-1,551.72414,NULL),(4553,-2,68.96552,NULL),(4554,1,4865.28736,NULL),(4554,-1,608.16092,NULL),(4554,-2,76.02011,NULL),(4555,1,11998.57500,NULL),(4555,-1,1103.31724,NULL),(4555,-2,137.91466,NULL),(4735,1,10000.00000,NULL),(4735,-1,919.54023,NULL),(4735,-2,114.94253,NULL),(4904,1,5947.00000,NULL),(4904,-1,456.00000,NULL),(4904,-2,57.00000,NULL),(4905,1,5500.00000,NULL),(4905,-1,505.74713,NULL),(4905,-2,63.21839,NULL),(4906,1,7500.00000,NULL),(4906,-1,575.07987,NULL),(4906,-2,71.88498,NULL),(4907,1,5600.00000,NULL),(4907,-1,429.39297,NULL),(4907,-2,53.67412,NULL),(4908,1,5500.00000,NULL),(4908,-1,505.74713,NULL),(4908,-2,63.21839,NULL),(4909,1,5500.00000,NULL),(4909,-1,505.74713,NULL),(4909,-2,63.21839,NULL),(4910,1,5500.00000,NULL),(4910,-1,505.74713,NULL),(4910,-2,63.21839,NULL),(4911,1,5500.00000,NULL),(4911,-1,505.74713,NULL),(4911,-2,63.21839,NULL),(4912,1,5500.00000,NULL),(4912,-1,505.74713,NULL),(4912,-2,63.21839,NULL),(4913,1,5500.00000,NULL),(4913,-1,505.74713,NULL),(4913,-2,63.21839,NULL),(4914,1,5500.00000,NULL),(4914,-1,505.74713,NULL),(4914,-2,63.21839,NULL),(4915,1,4413.79310,NULL),(4915,-1,551.72414,NULL),(4915,-2,68.96552,NULL),(4916,1,12847.08500,NULL),(4916,-1,1181.34115,NULL),(4916,-2,147.66764,NULL),(4917,1,8084.87500,NULL),(4917,-1,743.43678,NULL),(4917,-2,92.92960,NULL),(4918,1,10000.00000,NULL),(4918,-1,919.54023,NULL),(4918,-2,114.94253,NULL),(4919,1,6138.00000,NULL),(4919,-1,564.41379,NULL),(4919,-2,70.55172,NULL),(4920,1,5500.00000,NULL),(4920,-1,505.74713,NULL),(4920,-2,63.21839,NULL),(4921,1,15000.00000,NULL),(4921,-1,1379.31034,NULL),(4921,-2,172.41379,NULL),(4922,1,12500.00000,NULL),(4922,-1,1149.42529,NULL),(4922,-2,143.67816,NULL),(4923,1,5977.01149,NULL),(4923,-1,1195.40230,NULL),(4923,-2,149.42529,NULL),(4924,1,11447.08500,NULL),(4924,-1,1052.60552,NULL),(4924,-2,131.57569,NULL),(4925,1,5500.00000,NULL),(4925,-1,505.74713,NULL),(4925,-2,63.21839,NULL),(4926,1,5500.00000,NULL),(4926,-1,505.74713,NULL),(4926,-2,63.21839,NULL),(4927,1,5500.00000,NULL),(4927,-1,505.74713,NULL),(4927,-2,63.21839,NULL),(4928,1,4959.00000,NULL),(4928,-1,456.00000,NULL),(4928,-2,57.00000,NULL),(4929,1,12500.00000,NULL),(4929,-1,1149.42529,NULL),(4929,-2,143.67816,NULL),(4930,1,5500.00000,NULL),(4930,-1,505.74713,NULL),(4930,-2,63.21839,NULL),(4931,1,5500.00000,NULL),(4931,-1,505.74713,NULL),(4931,-2,63.21839,NULL),(4932,1,10000.00000,NULL),(4932,-1,919.54023,NULL),(4932,-2,114.94253,NULL),(4933,1,5500.00000,NULL),(4933,-1,505.74713,NULL),(4933,-2,63.21839,NULL),(4934,1,10000.00000,NULL),(4934,-1,919.54023,NULL),(4934,-2,114.94253,NULL),(4935,1,10000.00000,NULL),(4935,-1,919.54023,NULL),(4935,-2,114.94253,NULL),(4936,1,6083.48000,NULL),(4936,-1,559.40046,NULL),(4936,-2,69.92506,NULL),(4937,1,5500.00000,NULL),(4937,-1,505.74713,NULL),(4937,-2,63.21839,NULL),(4938,1,5947.00000,NULL),(4938,-1,546.85057,NULL),(4938,-2,68.35632,NULL),(4939,1,7500.00000,NULL),(4939,-1,689.65517,NULL),(4939,-2,86.20690,NULL),(4940,1,5500.00000,NULL),(4940,-1,505.74713,NULL),(4940,-2,63.21839,NULL),(4941,1,5500.00000,NULL),(4941,-1,505.74713,NULL),(4941,-2,63.21839,NULL),(4942,1,5500.00000,NULL),(4942,-1,505.74713,NULL),(4942,-2,63.21839,NULL),(4943,1,5946.00000,NULL),(4943,-1,455.92332,NULL),(4943,-2,56.99042,NULL),(4944,1,5947.00000,NULL),(4944,-1,456.00000,NULL),(4944,-2,57.00000,NULL),(4945,1,10000.00000,NULL),(4945,-1,919.54023,NULL),(4945,-2,114.94253,NULL),(4946,1,12500.00000,NULL),(4946,-1,1149.42529,NULL),(4946,-2,143.67816,NULL),(4947,1,5500.00000,NULL),(4947,-1,505.74713,NULL),(4947,-2,63.21839,NULL),(4948,1,5500.00000,NULL),(4948,-1,505.74713,NULL),(4948,-2,63.21839,NULL),(4949,1,5500.00000,NULL),(4949,-1,505.74713,NULL),(4949,-2,63.21839,NULL),(4950,1,6613.75000,NULL),(4950,-1,608.16092,NULL),(4950,-2,76.02011,NULL),(4951,1,5500.00000,NULL),(4951,-1,505.74713,NULL),(4951,-2,63.21839,NULL),(4952,1,5500.00000,NULL),(4952,-1,505.74713,NULL),(4952,-2,63.21839,NULL),(4953,1,12613.75000,NULL),(4953,-1,1159.88506,NULL),(4953,-2,144.98563,NULL),(4954,1,5500.00000,NULL),(4954,-1,505.74713,NULL),(4954,-2,63.21839,NULL),(4955,1,6613.75000,NULL),(4955,-1,608.16092,NULL),(4955,-2,76.02011,NULL),(4956,1,11998.57500,NULL),(4956,-1,1103.31724,NULL),(4956,-2,137.91466,NULL),(4957,1,5500.00000,NULL),(4957,-1,505.74713,NULL),(4957,-2,63.21839,NULL),(4958,1,17447.08500,NULL),(4958,-1,1604.32966,NULL),(4958,-2,200.54121,NULL),(4959,1,5500.00000,NULL),(4959,-1,505.74713,NULL),(4959,-2,63.21839,NULL),(4960,1,5500.00000,NULL),(4960,-1,505.74713,NULL),(4960,-2,63.21839,NULL),(4961,1,12047.22500,NULL),(4961,-1,1107.79080,NULL),(4961,-2,138.47385,NULL),(4962,1,5982.96500,NULL),(4962,-1,550.15770,NULL),(4962,-2,68.76971,NULL),(4963,1,5500.00000,NULL),(4963,-1,505.74713,NULL),(4963,-2,63.21839,NULL),(4964,1,8688.75000,NULL),(4964,-1,798.96552,NULL),(4964,-2,99.87069,NULL),(4965,1,7500.00000,NULL),(4965,-1,689.65517,NULL),(4965,-2,86.20690,NULL),(4966,1,5500.00000,NULL),(4966,-1,505.74713,NULL),(4966,-2,63.21839,NULL),(4967,1,9215.50000,NULL),(4967,-1,847.40230,NULL),(4967,-2,105.92529,NULL),(4968,1,7500.00000,NULL),(4968,-1,689.65517,NULL),(4968,-2,86.20690,NULL),(4969,1,5015.15655,NULL),(4969,-1,455.92332,NULL),(4969,-2,56.99042,NULL),(4970,1,6179.75000,NULL),(4970,-1,568.25287,NULL),(4970,-2,71.03161,NULL),(4971,1,25000.00000,NULL),(4971,-1,2298.85057,NULL),(4971,-2,287.35632,NULL),(4972,1,5500.00000,NULL),(4972,-1,505.74713,NULL),(4972,-2,63.21839,NULL),(4973,1,5500.00000,NULL),(4973,-1,505.74713,NULL),(4973,-2,63.21839,NULL),(4974,1,6250.00000,NULL),(4974,-1,574.71264,NULL),(4974,-2,71.83908,NULL),(4975,1,5000.00000,NULL),(4975,-1,459.77011,NULL),(4975,-2,57.47126,NULL),(4976,1,6250.00000,NULL),(4976,-1,574.71264,NULL),(4976,-2,71.83908,NULL),(4977,1,3040.80460,NULL),(4977,-1,608.16092,NULL),(4977,-2,76.02011,NULL),(4978,1,6500.00000,NULL),(4978,-1,597.70115,NULL),(4978,-2,74.71264,NULL),(4979,1,6323.59500,NULL),(4979,-1,581.48000,NULL),(4979,-2,72.68500,NULL),(4980,1,5500.00000,NULL),(4980,-1,505.74713,NULL),(4980,-2,63.21839,NULL),(4981,1,10000.00000,NULL),(4981,-1,919.54023,NULL),(4981,-2,114.94253,NULL),(4982,1,9601.47000,NULL),(4982,-1,882.89379,NULL),(4982,-2,110.36172,NULL),(4983,1,5000.00000,NULL),(4983,-1,459.77011,NULL),(4983,-2,57.47126,NULL),(4984,1,5000.00000,NULL),(4984,-1,459.77011,NULL),(4984,-2,57.47126,NULL),(4985,1,10000.00000,NULL),(4985,-1,919.54023,NULL),(4985,-2,114.94253,NULL),(4986,1,5500.00000,NULL),(4986,-1,505.74713,NULL),(4986,-2,63.21839,NULL),(4987,1,5000.00000,NULL),(4987,-1,459.77011,NULL),(4987,-2,57.47126,NULL),(5076,1,5947.00000,NULL),(5076,-1,456.00000,NULL),(5076,-2,57.00000,NULL),(5077,1,5500.00000,NULL),(5077,-1,505.74713,NULL),(5077,-2,63.21839,NULL),(5078,1,7500.00000,NULL),(5078,-1,689.65517,NULL),(5078,-2,86.20690,NULL),(5079,1,5600.00000,NULL),(5079,-1,429.39297,NULL),(5079,-2,53.67412,NULL),(5080,1,5500.00000,NULL),(5080,-1,505.74713,NULL),(5080,-2,63.21839,NULL),(5081,1,5500.00000,NULL),(5081,-1,505.74713,NULL),(5081,-2,63.21839,NULL),(5082,1,5500.00000,NULL),(5082,-1,505.74713,NULL),(5082,-2,63.21839,NULL),(5083,1,5500.00000,NULL),(5083,-1,505.74713,NULL),(5083,-2,63.21839,NULL),(5084,1,5500.00000,NULL),(5084,-1,505.74713,NULL),(5084,-2,63.21839,NULL),(5085,1,5500.00000,NULL),(5085,-1,505.74713,NULL),(5085,-2,63.21839,NULL),(5086,1,5500.00000,NULL),(5086,-1,505.74713,NULL),(5086,-2,63.21839,NULL),(5087,1,12847.08500,NULL),(5087,-1,1181.34115,NULL),(5087,-2,147.66764,NULL),(5088,1,8084.87500,NULL),(5088,-1,743.43678,NULL),(5088,-2,92.92960,NULL),(5089,1,10000.00000,NULL),(5089,-1,919.54023,NULL),(5089,-2,114.94253,NULL),(5090,1,6138.00000,NULL),(5090,-1,564.41379,NULL),(5090,-2,70.55172,NULL),(5091,1,5500.00000,NULL),(5091,-1,505.74713,NULL),(5091,-2,63.21839,NULL),(5092,1,12500.00000,NULL),(5092,-1,1149.42529,NULL),(5092,-2,143.67816,NULL),(5093,1,15000.00000,NULL),(5093,-1,1379.31034,NULL),(5093,-2,172.41379,NULL),(5094,1,11954.02299,NULL),(5094,-1,1195.40230,NULL),(5094,-2,149.42529,NULL),(5095,1,11447.08500,NULL),(5095,-1,1052.60552,NULL),(5095,-2,131.57569,NULL),(5096,1,5500.00000,NULL),(5096,-1,505.74713,NULL),(5096,-2,63.21839,NULL),(5097,1,5500.00000,NULL),(5097,-1,505.74713,NULL),(5097,-2,63.21839,NULL),(5098,1,5500.00000,NULL),(5098,-1,505.74713,NULL),(5098,-2,63.21839,NULL),(5099,1,4959.00000,NULL),(5099,-1,456.00000,NULL),(5099,-2,57.00000,NULL),(5100,1,12500.00000,NULL),(5100,-1,1149.42529,NULL),(5100,-2,143.67816,NULL),(5101,1,5500.00000,NULL),(5101,-1,505.74713,NULL),(5101,-2,63.21839,NULL),(5102,1,5500.00000,NULL),(5102,-1,505.74713,NULL),(5102,-2,63.21839,NULL),(5103,1,10000.00000,NULL),(5103,-1,919.54023,NULL),(5103,-2,114.94253,NULL),(5104,1,10000.00000,NULL),(5104,-1,919.54023,NULL),(5104,-2,114.94253,NULL),(5105,1,5500.00000,NULL),(5105,-1,505.74713,NULL),(5105,-2,63.21839,NULL),(5106,1,6083.48000,NULL),(5106,-1,559.40046,NULL),(5106,-2,69.92506,NULL),(5107,1,10000.00000,NULL),(5107,-1,919.54023,NULL),(5107,-2,114.94253,NULL),(5108,1,5792.98851,NULL),(5108,-1,587.47126,NULL),(5108,-2,73.43391,NULL),(5109,1,5947.00000,NULL),(5109,-1,546.85057,NULL),(5109,-2,68.35632,NULL),(5110,1,7500.00000,NULL),(5110,-1,689.65517,NULL),(5110,-2,86.20690,NULL),(5111,1,5500.00000,NULL),(5111,-1,505.74713,NULL),(5111,-2,63.21839,NULL),(5112,1,5500.00000,NULL),(5112,-1,505.74713,NULL),(5112,-2,63.21839,NULL),(5113,1,5500.00000,NULL),(5113,-1,505.74713,NULL),(5113,-2,63.21839,NULL),(5114,1,5946.00000,NULL),(5114,-1,455.92332,NULL),(5114,-2,56.99042,NULL),(5115,1,5947.00000,NULL),(5115,-1,456.00000,NULL),(5115,-2,57.00000,NULL),(5116,1,10000.00000,NULL),(5116,-1,919.54023,NULL),(5116,-2,114.94253,NULL),(5117,1,12500.00000,NULL),(5117,-1,1149.42529,NULL),(5117,-2,143.67816,NULL),(5118,1,5500.00000,NULL),(5118,-1,505.74713,NULL),(5118,-2,63.21839,NULL),(5119,1,5500.00000,NULL),(5119,-1,505.74713,NULL),(5119,-2,63.21839,NULL),(5120,1,3040.80460,NULL),(5120,-1,608.16092,NULL),(5120,-2,76.02011,NULL),(5121,1,5500.00000,NULL),(5121,-1,505.74713,NULL),(5121,-2,63.21839,NULL),(5123,1,5500.00000,NULL),(5123,-1,505.74713,NULL),(5123,-2,63.21839,NULL),(5124,1,5500.00000,NULL),(5124,-1,505.74713,NULL),(5124,-2,63.21839,NULL),(5125,1,12613.75000,NULL),(5125,-1,1159.88506,NULL),(5125,-2,144.98563,NULL),(5126,1,5500.00000,NULL),(5126,-1,505.74713,NULL),(5126,-2,63.21839,NULL),(5127,1,6613.75000,NULL),(5127,-1,608.16092,NULL),(5127,-2,76.02011,NULL),(5128,1,11998.57500,NULL),(5128,-1,1103.31724,NULL),(5128,-2,137.91466,NULL),(5129,1,5500.00000,NULL),(5129,-1,505.74713,NULL),(5129,-2,63.21839,NULL),(5130,1,17447.08500,NULL),(5130,-1,1604.32966,NULL),(5130,-2,200.54121,NULL),(5131,1,5500.00000,NULL),(5131,-1,505.74713,NULL),(5131,-2,63.21839,NULL),(5132,1,5500.00000,NULL),(5132,-1,505.74713,NULL),(5132,-2,63.21839,NULL),(5133,1,12047.22500,NULL),(5133,-1,1107.79080,NULL),(5133,-2,138.47385,NULL),(5134,1,5982.96500,NULL),(5134,-1,550.15770,NULL),(5134,-2,68.76971,NULL),(5135,1,5500.00000,NULL),(5135,-1,505.74713,NULL),(5135,-2,63.21839,NULL),(5136,1,8688.75000,NULL),(5136,-1,798.96552,NULL),(5136,-2,99.87069,NULL),(5137,1,7500.00000,NULL),(5137,-1,689.65517,NULL),(5137,-2,86.20690,NULL),(5138,1,5500.00000,NULL),(5138,-1,505.74713,NULL),(5138,-2,63.21839,NULL),(5139,1,9215.50000,NULL),(5139,-1,847.40230,NULL),(5139,-2,105.92529,NULL),(5140,1,7500.00000,NULL),(5140,-1,689.65517,NULL),(5140,-2,86.20690,NULL),(5141,1,5946.00000,NULL),(5141,-1,455.92332,NULL),(5141,-2,56.99042,NULL),(5142,1,6179.75000,NULL),(5142,-1,568.25287,NULL),(5142,-2,71.03161,NULL),(5143,1,25000.00000,NULL),(5143,-1,2298.85057,NULL),(5143,-2,287.35632,NULL),(5144,1,5057.47126,NULL),(5144,-1,505.74713,NULL),(5144,-2,63.21839,NULL),(5145,1,5500.00000,NULL),(5145,-1,505.74713,NULL),(5145,-2,63.21839,NULL),(5146,1,5500.00000,NULL),(5146,-1,505.74713,NULL),(5146,-2,63.21839,NULL),(5147,1,6250.00000,NULL),(5147,-1,574.71264,NULL),(5147,-2,71.83908,NULL),(5148,1,5000.00000,NULL),(5148,-1,459.77011,NULL),(5148,-2,57.47126,NULL),(5149,1,6250.00000,NULL),(5149,-1,574.71264,NULL),(5149,-2,71.83908,NULL),(5150,1,6613.75000,NULL),(5150,-1,608.16092,NULL),(5150,-2,76.02011,NULL),(5151,1,5057.47126,NULL),(5151,-1,505.74713,NULL),(5151,-2,63.21839,NULL),(5152,1,6500.00000,NULL),(5152,-1,597.70115,NULL),(5152,-2,74.71264,NULL),(5153,1,6323.59500,NULL),(5153,-1,581.48000,NULL),(5153,-2,72.68500,NULL),(5154,1,5500.00000,NULL),(5154,-1,505.74713,NULL),(5154,-2,63.21839,NULL),(5155,1,10000.00000,NULL),(5155,-1,919.54023,NULL),(5155,-2,114.94253,NULL),(5156,1,9601.47000,NULL),(5156,-1,882.89379,NULL),(5156,-2,110.36172,NULL),(5157,1,5000.00000,NULL),(5157,-1,459.77011,NULL),(5157,-2,57.47126,NULL),(5158,1,5000.00000,NULL),(5158,-1,459.77011,NULL),(5158,-2,57.47126,NULL),(5159,1,10000.00000,NULL),(5159,-1,919.54023,NULL),(5159,-2,114.94253,NULL),(5160,1,5500.00000,NULL),(5160,-1,505.74713,NULL),(5160,-2,63.21839,NULL),(5161,1,5000.00000,NULL),(5161,-1,459.77011,NULL),(5161,-2,57.47126,NULL),(5165,1,4257.12644,NULL),(5165,-1,608.16092,NULL),(5165,-2,76.02011,NULL),(5250,1,5947.00000,NULL),(5250,-1,456.00000,NULL),(5250,-2,57.00000,NULL),(5251,1,5500.00000,NULL),(5251,-1,505.74713,NULL),(5251,-2,63.21839,NULL),(5252,1,7500.00000,NULL),(5252,-1,689.65517,NULL),(5252,-2,86.20690,NULL),(5253,1,5600.00000,NULL),(5253,-1,429.39297,NULL),(5253,-2,53.67412,NULL),(5254,1,5500.00000,NULL),(5254,-1,505.74713,NULL),(5254,-2,63.21839,NULL),(5255,1,5500.00000,NULL),(5255,-1,505.74713,NULL),(5255,-2,63.21839,NULL),(5256,1,5500.00000,NULL),(5256,-1,505.74713,NULL),(5256,-2,63.21839,NULL),(5257,1,5500.00000,NULL),(5257,-1,505.74713,NULL),(5257,-2,63.21839,NULL),(5258,1,5500.00000,NULL),(5258,-1,505.74713,NULL),(5258,-2,63.21839,NULL),(5259,1,5500.00000,NULL),(5259,-1,505.74713,NULL),(5259,-2,63.21839,NULL),(5260,1,5500.00000,NULL),(5260,-1,505.74713,NULL),(5260,-2,63.21839,NULL),(5261,1,12847.08500,NULL),(5261,-1,1181.34115,NULL),(5261,-2,147.66764,NULL),(5262,1,8084.87500,NULL),(5262,-1,743.43678,NULL),(5262,-2,92.92960,NULL),(5263,1,10000.00000,NULL),(5263,-1,919.54023,NULL),(5263,-2,114.94253,NULL),(5264,1,6138.00000,NULL),(5264,-1,564.41379,NULL),(5264,-2,70.55172,NULL),(5265,1,5500.00000,NULL),(5265,-1,505.74713,NULL),(5265,-2,63.21839,NULL),(5266,1,12500.00000,NULL),(5266,-1,1149.42529,NULL),(5266,-2,143.67816,NULL),(5267,1,15000.00000,NULL),(5267,-1,1379.31034,NULL),(5267,-2,172.41379,NULL),(5268,1,11447.08500,NULL),(5268,-1,1052.60552,NULL),(5268,-2,131.57569,NULL),(5269,1,5500.00000,NULL),(5269,-1,505.74713,NULL),(5269,-2,63.21839,NULL),(5270,1,5500.00000,NULL),(5270,-1,505.74713,NULL),(5270,-2,63.21839,NULL),(5271,1,5500.00000,NULL),(5271,-1,505.74713,NULL),(5271,-2,63.21839,NULL),(5272,1,4959.00000,NULL),(5272,-1,456.00000,NULL),(5272,-2,57.00000,NULL),(5273,1,12500.00000,NULL),(5273,-1,1149.42529,NULL),(5273,-2,143.67816,NULL),(5274,1,5500.00000,NULL),(5274,-1,505.74713,NULL),(5274,-2,63.21839,NULL),(5275,1,5500.00000,NULL),(5275,-1,505.74713,NULL),(5275,-2,63.21839,NULL),(5276,1,10000.00000,NULL),(5276,-1,919.54023,NULL),(5276,-2,114.94253,NULL),(5277,1,5500.00000,NULL),(5277,-1,505.74713,NULL),(5277,-2,63.21839,NULL),(5278,1,10000.00000,NULL),(5278,-1,919.54023,NULL),(5278,-2,114.94253,NULL),(5279,1,10000.00000,NULL),(5279,-1,919.54023,NULL),(5279,-2,114.94253,NULL),(5280,1,6083.48000,NULL),(5280,-1,559.40046,NULL),(5280,-2,69.92506,NULL),(5281,1,6388.75000,NULL),(5281,-1,587.47126,NULL),(5281,-2,73.43391,NULL),(5282,1,5947.00000,NULL),(5282,-1,546.85057,NULL),(5282,-2,68.35632,NULL),(5283,1,5500.00000,NULL),(5283,-1,505.74713,NULL),(5283,-2,63.21839,NULL),(5284,1,7500.00000,NULL),(5284,-1,689.65517,NULL),(5284,-2,86.20690,NULL),(5285,1,5500.00000,NULL),(5285,-1,505.74713,NULL),(5285,-2,63.21839,NULL),(5286,1,5500.00000,NULL),(5286,-1,505.74713,NULL),(5286,-2,63.21839,NULL),(5287,1,5946.00000,NULL),(5287,-1,455.92332,NULL),(5287,-2,56.99042,NULL),(5288,1,5947.00000,NULL),(5288,-1,456.00000,NULL),(5288,-2,57.00000,NULL),(5289,1,10000.00000,NULL),(5289,-1,919.54023,NULL),(5289,-2,114.94253,NULL),(5290,1,12500.00000,NULL),(5290,-1,1149.42529,NULL),(5290,-2,143.67816,NULL),(5291,1,5500.00000,NULL),(5291,-1,505.74713,NULL),(5291,-2,63.21839,NULL),(5292,1,5500.00000,NULL),(5292,-1,505.74713,NULL),(5292,-2,63.21839,NULL),(5293,1,6613.75000,NULL),(5293,-1,608.16092,NULL),(5293,-2,76.02011,NULL),(5294,1,5500.00000,NULL),(5294,-1,505.74713,NULL),(5294,-2,63.21839,NULL),(5295,1,5500.00000,NULL),(5295,-1,505.74713,NULL),(5295,-2,63.21839,NULL),(5296,1,12613.75000,NULL),(5296,-1,1159.88506,NULL),(5296,-2,144.98563,NULL),(5297,1,5500.00000,NULL),(5297,-1,505.74713,NULL),(5297,-2,63.21839,NULL),(5298,1,5500.00000,NULL),(5298,-1,505.74713,NULL),(5298,-2,63.21839,NULL),(5299,1,6613.75000,NULL),(5299,-1,608.16092,NULL),(5299,-2,76.02011,NULL),(5300,1,11998.57500,NULL),(5300,-1,1103.31724,NULL),(5300,-2,137.91466,NULL),(5301,1,5500.00000,NULL),(5301,-1,505.74713,NULL),(5301,-2,63.21839,NULL),(5302,1,17447.08500,NULL),(5302,-1,1604.32966,NULL),(5302,-2,200.54121,NULL),(5303,1,5500.00000,NULL),(5303,-1,505.74713,NULL),(5303,-2,63.21839,NULL),(5304,1,5500.00000,NULL),(5304,-1,505.74713,NULL),(5304,-2,63.21839,NULL),(5305,1,12047.22500,NULL),(5305,-1,1107.79080,NULL),(5305,-2,138.47385,NULL),(5306,1,5982.96500,NULL),(5306,-1,550.15770,NULL),(5306,-2,68.76971,NULL),(5307,1,5500.00000,NULL),(5307,-1,505.74713,NULL),(5307,-2,63.21839,NULL),(5308,1,8688.75000,NULL),(5308,-1,798.96552,NULL),(5308,-2,99.87069,NULL),(5309,1,7500.00000,NULL),(5309,-1,689.65517,NULL),(5309,-2,86.20690,NULL),(5310,1,5500.00000,NULL),(5310,-1,505.74713,NULL),(5310,-2,63.21839,NULL),(5311,1,9215.50000,NULL),(5311,-1,847.40230,NULL),(5311,-2,105.92529,NULL),(5312,1,7500.00000,NULL),(5312,-1,689.65517,NULL),(5312,-2,86.20690,NULL),(5313,1,5946.00000,NULL),(5313,-1,455.92332,NULL),(5313,-2,56.99042,NULL),(5314,1,6179.75000,NULL),(5314,-1,568.25287,NULL),(5314,-2,71.03161,NULL),(5315,1,25000.00000,NULL),(5315,-1,2298.85057,NULL),(5315,-2,287.35632,NULL),(5316,1,5500.00000,NULL),(5316,-1,505.74713,NULL),(5316,-2,63.21839,NULL),(5317,1,5500.00000,NULL),(5317,-1,505.74713,NULL),(5317,-2,63.21839,NULL),(5318,1,5500.00000,NULL),(5318,-1,505.74713,NULL),(5318,-2,63.21839,NULL),(5319,1,6250.00000,NULL),(5319,-1,574.71264,NULL),(5319,-2,71.83908,NULL),(5320,1,5000.00000,NULL),(5320,-1,459.77011,NULL),(5320,-2,57.47126,NULL),(5321,1,6250.00000,NULL),(5321,-1,574.71264,NULL),(5321,-2,71.83908,NULL),(5322,1,6613.75000,NULL),(5322,-1,608.16092,NULL),(5322,-2,76.02011,NULL),(5323,1,5500.00000,NULL),(5323,-1,505.74713,NULL),(5323,-2,63.21839,NULL),(5324,1,6500.00000,NULL),(5324,-1,597.70115,NULL),(5324,-2,74.71264,NULL),(5325,1,6323.59500,NULL),(5325,-1,581.48000,NULL),(5325,-2,72.68500,NULL),(5326,1,5500.00000,NULL),(5326,-1,505.74713,NULL),(5326,-2,63.21839,NULL),(5327,1,10000.00000,NULL),(5327,-1,919.54023,NULL),(5327,-2,114.94253,NULL),(5328,1,9601.47000,NULL),(5328,-1,882.89379,NULL),(5328,-2,110.36172,NULL),(5329,1,5000.00000,NULL),(5329,-1,459.77011,NULL),(5329,-2,57.47126,NULL),(5330,1,5000.00000,NULL),(5330,-1,459.77011,NULL),(5330,-2,57.47126,NULL),(5331,1,10000.00000,NULL),(5331,-1,919.54023,NULL),(5331,-2,114.94253,NULL),(5332,1,5500.00000,NULL),(5332,-1,505.74713,NULL),(5332,-2,63.21839,NULL),(5333,1,5000.00000,NULL),(5333,-1,459.77011,NULL),(5333,-2,57.47126,NULL),(5334,1,5947.00000,NULL),(5334,-1,456.00000,NULL),(5334,-2,57.00000,NULL),(5335,1,5500.00000,NULL),(5335,-1,505.74713,NULL),(5335,-2,63.21839,NULL),(5337,1,5901.39297,NULL),(5337,-1,456.00000,NULL),(5337,-2,57.00000,NULL),(5338,1,6070.00000,NULL),(5338,-1,558.16092,NULL),(5338,-2,69.77011,NULL),(5339,1,5500.00000,NULL),(5339,-1,505.74713,NULL),(5339,-2,63.21839,NULL),(5340,1,5500.00000,NULL),(5340,-1,505.74713,NULL),(5340,-2,63.21839,NULL),(5341,1,5500.00000,NULL),(5341,-1,505.74713,NULL),(5341,-2,63.21839,NULL),(5342,1,5500.00000,NULL),(5342,-1,505.74713,NULL),(5342,-2,63.21839,NULL),(5343,1,5500.00000,NULL),(5343,-1,505.74713,NULL),(5343,-2,63.21839,NULL),(5344,1,6615.25000,NULL),(5344,-1,608.29885,NULL),(5344,-2,76.03736,NULL),(5345,1,12847.08500,NULL),(5345,-1,1181.34115,NULL),(5345,-2,147.66764,NULL),(5346,1,8084.87500,NULL),(5346,-1,743.43678,NULL),(5346,-2,92.92960,NULL),(5347,1,10000.00000,NULL),(5347,-1,919.54023,NULL),(5347,-2,114.94253,NULL),(5348,1,6138.00000,NULL),(5348,-1,564.41379,NULL),(5348,-2,70.55172,NULL),(5350,1,15000.00000,NULL),(5350,-1,1379.31034,NULL),(5350,-2,172.41379,NULL),(5351,1,12500.00000,NULL),(5351,-1,1149.42529,NULL),(5351,-2,143.67816,NULL),(5352,1,11447.08500,NULL),(5352,-1,1052.60552,NULL),(5352,-2,131.57569,NULL),(5353,1,5500.00000,NULL),(5353,-1,505.74713,NULL),(5353,-2,63.21839,NULL),(5354,1,5500.00000,NULL),(5354,-1,505.74713,NULL),(5354,-2,63.21839,NULL),(5355,1,5500.00000,NULL),(5355,-1,505.74713,NULL),(5355,-2,63.21839,NULL),(5356,1,4959.00000,NULL),(5356,-1,456.00000,NULL),(5356,-2,57.00000,NULL),(5357,1,12500.00000,NULL),(5357,-1,1149.42529,NULL),(5357,-2,143.67816,NULL),(5358,1,5500.00000,NULL),(5358,-1,505.74713,NULL),(5358,-2,63.21839,NULL),(5359,1,5500.00000,NULL),(5359,-1,505.74713,NULL),(5359,-2,63.21839,NULL),(5360,1,10000.00000,NULL),(5360,-1,919.54023,NULL),(5360,-2,114.94253,NULL),(5361,1,5500.00000,NULL),(5361,-1,505.74713,NULL),(5361,-2,63.21839,NULL),(5362,1,10000.00000,NULL),(5362,-1,919.54023,NULL),(5362,-2,114.94253,NULL),(5363,1,6696.13500,NULL),(5363,-1,615.73655,NULL),(5363,-2,76.96707,NULL),(5364,1,10000.00000,NULL),(5364,-1,919.54023,NULL),(5364,-2,114.94253,NULL),(5365,1,6388.75000,NULL),(5365,-1,587.47126,NULL),(5365,-2,73.43391,NULL),(5366,1,6543.39500,NULL),(5366,-1,601.69149,NULL),(5366,-2,75.21144,NULL),(5367,1,5500.00000,NULL),(5367,-1,505.74713,NULL),(5367,-2,63.21839,NULL),(5368,1,7500.00000,NULL),(5368,-1,689.65517,NULL),(5368,-2,86.20690,NULL),(5369,1,5500.00000,NULL),(5369,-1,505.74713,NULL),(5369,-2,63.21839,NULL),(5370,1,6000.12500,NULL),(5370,-1,551.73563,NULL),(5370,-2,68.96695,NULL),(5371,1,5946.00000,NULL),(5371,-1,455.92332,NULL),(5371,-2,56.99042,NULL),(5372,1,5947.00000,NULL),(5372,-1,456.00000,NULL),(5372,-2,57.00000,NULL),(5373,1,10000.00000,NULL),(5373,-1,919.54023,NULL),(5373,-2,114.94253,NULL),(5374,1,12500.00000,NULL),(5374,-1,1149.42529,NULL),(5374,-2,143.67816,NULL),(5375,1,5500.00000,NULL),(5375,-1,505.74713,NULL),(5375,-2,63.21839,NULL),(5376,1,6013.95000,NULL),(5376,-1,553.00690,NULL),(5376,-2,69.12586,NULL),(5377,1,6613.75000,NULL),(5377,-1,608.16092,NULL),(5377,-2,76.02011,NULL),(5378,1,5500.00000,NULL),(5378,-1,505.74713,NULL),(5378,-2,63.21839,NULL),(5379,1,5500.00000,NULL),(5379,-1,505.74713,NULL),(5379,-2,63.21839,NULL),(5380,1,5500.00000,NULL),(5380,-1,505.74713,NULL),(5380,-2,63.21839,NULL),(5381,1,12613.75000,NULL),(5381,-1,1159.88506,NULL),(5381,-2,144.98563,NULL),(5382,1,5500.00000,NULL),(5382,-1,505.74713,NULL),(5382,-2,63.21839,NULL),(5383,1,6613.75000,NULL),(5383,-1,608.16092,NULL),(5383,-2,76.02011,NULL),(5384,1,11998.57500,NULL),(5384,-1,1103.31724,NULL),(5384,-2,137.91466,NULL),(5385,1,5500.00000,NULL),(5385,-1,505.74713,NULL),(5385,-2,63.21839,NULL),(5386,1,17447.08500,NULL),(5386,-1,1604.32966,NULL),(5386,-2,200.54121,NULL),(5387,1,5500.00000,NULL),(5387,-1,505.74713,NULL),(5387,-2,63.21839,NULL),(5388,1,5500.00000,NULL),(5388,-1,505.74713,NULL),(5388,-2,63.21839,NULL),(5389,1,12047.22500,NULL),(5389,-1,1107.79080,NULL),(5389,-2,138.47385,NULL),(5390,1,5982.96500,NULL),(5390,-1,550.15770,NULL),(5390,-2,68.76971,NULL),(5391,1,5500.00000,NULL),(5391,-1,505.74713,NULL),(5391,-2,63.21839,NULL),(5392,1,8688.75000,NULL),(5392,-1,798.96552,NULL),(5392,-2,99.87069,NULL),(5393,1,7500.00000,NULL),(5393,-1,689.65517,NULL),(5393,-2,86.20690,NULL),(5394,1,5500.00000,NULL),(5394,-1,505.74713,NULL),(5394,-2,63.21839,NULL),(5395,1,9215.50000,NULL),(5395,-1,847.40230,NULL),(5395,-2,105.92529,NULL),(5396,1,7500.00000,NULL),(5396,-1,689.65517,NULL),(5396,-2,86.20690,NULL),(5397,1,5946.00000,NULL),(5397,-1,455.92332,NULL),(5397,-2,56.99042,NULL),(5398,1,6179.75000,NULL),(5398,-1,568.25287,NULL),(5398,-2,71.03161,NULL),(5399,1,25000.00000,NULL),(5399,-1,2298.85057,NULL),(5399,-2,287.35632,NULL),(5400,1,5500.00000,NULL),(5400,-1,505.74713,NULL),(5400,-2,63.21839,NULL),(5401,1,5500.00000,NULL),(5401,-1,505.74713,NULL),(5401,-2,63.21839,NULL),(5402,1,6000.12500,NULL),(5402,-1,551.73563,NULL),(5402,-2,68.96695,NULL),(5403,1,6250.00000,NULL),(5403,-1,574.71264,NULL),(5403,-2,71.83908,NULL),(5404,1,5264.36782,NULL),(5404,-1,528.73563,NULL),(5404,-2,66.09195,NULL),(5405,1,6250.00000,NULL),(5405,-1,574.71264,NULL),(5405,-2,71.83908,NULL),(5406,1,6613.75000,NULL),(5406,-1,608.16092,NULL),(5406,-2,76.02011,NULL),(5407,1,5500.00000,NULL),(5407,-1,505.74713,NULL),(5407,-2,63.21839,NULL),(5408,1,6905.74713,NULL),(5408,-1,639.08046,NULL),(5408,-2,79.88506,NULL),(5409,1,6323.59500,NULL),(5409,-1,581.48000,NULL),(5409,-2,72.68500,NULL),(5410,1,5500.00000,NULL),(5410,-1,505.74713,NULL),(5410,-2,63.21839,NULL),(5411,1,11621.00000,NULL),(5411,-1,1068.59770,NULL),(5411,-2,133.57471,NULL),(5412,1,9601.47000,NULL),(5412,-1,882.89379,NULL),(5412,-2,110.36172,NULL),(5415,1,10000.00000,NULL),(5415,-1,919.54023,NULL),(5415,-2,114.94253,NULL),(5416,1,5500.00000,NULL),(5416,-1,505.74713,NULL),(5416,-2,63.21839,NULL),(5417,1,5000.00000,NULL),(5417,-1,459.77011,NULL),(5417,-2,57.47126,NULL),(5419,1,3678.16092,NULL),(5419,-1,459.77011,NULL),(5419,-2,57.47126,NULL),(5420,1,8000.00000,NULL),(5420,-1,735.63218,NULL),(5420,-2,91.95402,NULL),(5421,1,7500.00000,NULL),(5421,-1,575.07987,NULL),(5421,-2,71.88498,NULL),(5422,1,5500.00000,NULL),(5422,-1,505.74713,NULL),(5422,-2,63.21839,NULL),(5423,1,5947.00000,NULL),(5423,-1,456.00000,NULL),(5423,-2,57.00000,NULL),(5424,1,5500.00000,NULL),(5424,-1,505.74713,NULL),(5424,-2,63.21839,NULL),(5425,1,7500.00000,NULL),(5425,-1,575.07987,NULL),(5425,-2,71.88498,NULL),(5426,1,5947.00000,NULL),(5426,-1,456.00000,NULL),(5426,-2,57.00000,NULL),(5427,1,6070.00000,NULL),(5427,-1,558.16092,NULL),(5427,-2,69.77011,NULL),(5428,1,5500.00000,NULL),(5428,-1,505.74713,NULL),(5428,-2,63.21839,NULL),(5429,1,5500.00000,NULL),(5429,-1,505.74713,NULL),(5429,-2,63.21839,NULL),(5430,1,5500.00000,NULL),(5430,-1,505.74713,NULL),(5430,-2,63.21839,NULL),(5431,1,5500.00000,NULL),(5431,-1,505.74713,NULL),(5431,-2,63.21839,NULL),(5432,1,5500.00000,NULL),(5432,-1,505.74713,NULL),(5432,-2,63.21839,NULL),(5433,1,6615.25000,NULL),(5433,-1,608.29885,NULL),(5433,-2,76.03736,NULL),(5434,1,12847.08500,NULL),(5434,-1,1181.34115,NULL),(5434,-2,147.66764,NULL),(5435,1,8084.87500,NULL),(5435,-1,743.43678,NULL),(5435,-2,92.92960,NULL),(5436,1,10000.00000,NULL),(5436,-1,919.54023,NULL),(5436,-2,114.94253,NULL),(5437,1,6138.00000,NULL),(5437,-1,564.41379,NULL),(5437,-2,70.55172,NULL),(5438,1,5500.00000,NULL),(5438,-1,505.74713,NULL),(5438,-2,63.21839,NULL),(5439,1,12500.00000,NULL),(5439,-1,1149.42529,NULL),(5439,-2,143.67816,NULL),(5440,1,15000.00000,NULL),(5440,-1,1379.31034,NULL),(5440,-2,172.41379,NULL),(5441,1,11447.08500,NULL),(5441,-1,1052.60552,NULL),(5441,-2,131.57569,NULL),(5442,1,5500.00000,NULL),(5442,-1,505.74713,NULL),(5442,-2,63.21839,NULL),(5443,1,5500.00000,NULL),(5443,-1,505.74713,NULL),(5443,-2,63.21839,NULL),(5444,1,5500.00000,NULL),(5444,-1,505.74713,NULL),(5444,-2,63.21839,NULL),(5445,1,4959.00000,NULL),(5445,-1,456.00000,NULL),(5445,-2,57.00000,NULL),(5447,1,5500.00000,NULL),(5447,-1,505.74713,NULL),(5447,-2,63.21839,NULL),(5448,1,5500.00000,NULL),(5448,-1,505.74713,NULL),(5448,-2,63.21839,NULL),(5449,1,10000.00000,NULL),(5449,-1,919.54023,NULL),(5449,-2,114.94253,NULL),(5450,1,10000.00000,NULL),(5450,-1,919.54023,NULL),(5450,-2,114.94253,NULL),(5451,1,5500.00000,NULL),(5451,-1,505.74713,NULL),(5451,-2,63.21839,NULL),(5452,1,10000.00000,NULL),(5452,-1,919.54023,NULL),(5452,-2,114.94253,NULL),(5453,1,6696.13500,NULL),(5453,-1,615.73655,NULL),(5453,-2,76.96707,NULL),(5454,1,6388.75000,NULL),(5454,-1,587.47126,NULL),(5454,-2,73.43391,NULL),(5455,1,6543.39500,NULL),(5455,-1,601.69149,NULL),(5455,-2,75.21144,NULL),(5456,1,8000.00000,NULL),(5456,-1,735.63218,NULL),(5456,-2,91.95402,NULL),(5457,1,7500.00000,NULL),(5457,-1,689.65517,NULL),(5457,-2,86.20690,NULL),(5458,1,5500.00000,NULL),(5458,-1,505.74713,NULL),(5458,-2,63.21839,NULL),(5459,1,5500.00000,NULL),(5459,-1,505.74713,NULL),(5459,-2,63.21839,NULL),(5460,1,5500.00000,NULL),(5460,-1,505.74713,NULL),(5460,-2,63.21839,NULL),(5461,1,5946.00000,NULL),(5461,-1,455.92332,NULL),(5461,-2,56.99042,NULL),(5462,1,5947.00000,NULL),(5462,-1,456.00000,NULL),(5462,-2,57.00000,NULL),(5463,1,10000.00000,NULL),(5463,-1,919.54023,NULL),(5463,-2,114.94253,NULL),(5464,1,12500.00000,NULL),(5464,-1,1149.42529,NULL),(5464,-2,143.67816,NULL),(5465,1,1610.22364,NULL),(5465,-1,536.74121,NULL),(5465,-2,67.09265,NULL),(5466,1,5500.00000,NULL),(5466,-1,505.74713,NULL),(5466,-2,63.21839,NULL),(5467,1,6013.95000,NULL),(5467,-1,553.00690,NULL),(5467,-2,69.12586,NULL),(5468,1,6613.75000,NULL),(5468,-1,608.16092,NULL),(5468,-2,76.02011,NULL),(5469,1,5500.00000,NULL),(5469,-1,505.74713,NULL),(5469,-2,63.21839,NULL),(5470,1,5500.00000,NULL),(5470,-1,505.74713,NULL),(5470,-2,63.21839,NULL),(5472,1,12613.75000,NULL),(5472,-1,1159.88506,NULL),(5472,-2,144.98563,NULL),(5473,1,5500.00000,NULL),(5473,-1,505.74713,NULL),(5473,-2,63.21839,NULL),(5474,1,6613.75000,NULL),(5474,-1,608.16092,NULL),(5474,-2,76.02011,NULL),(5475,1,11998.57500,NULL),(5475,-1,1103.31724,NULL),(5475,-2,137.91466,NULL),(5476,1,5500.00000,NULL),(5476,-1,505.74713,NULL),(5476,-2,63.21839,NULL),(5477,1,17447.08500,NULL),(5477,-1,1604.32966,NULL),(5477,-2,200.54121,NULL),(5478,1,5500.00000,NULL),(5478,-1,505.74713,NULL),(5478,-2,63.21839,NULL),(5479,1,5500.00000,NULL),(5479,-1,505.74713,NULL),(5479,-2,63.21839,NULL),(5480,1,12047.22500,NULL),(5480,-1,1107.79080,NULL),(5480,-2,138.47385,NULL),(5481,1,5982.96500,NULL),(5481,-1,550.15770,NULL),(5481,-2,68.76971,NULL),(5482,1,5500.00000,NULL),(5482,-1,505.74713,NULL),(5482,-2,63.21839,NULL),(5483,1,8688.75000,NULL),(5483,-1,798.96552,NULL),(5483,-2,99.87069,NULL),(5484,1,7500.00000,NULL),(5484,-1,689.65517,NULL),(5484,-2,86.20690,NULL),(5485,1,5500.00000,NULL),(5485,-1,505.74713,NULL),(5485,-2,63.21839,NULL),(5486,1,9215.50000,NULL),(5486,-1,847.40230,NULL),(5486,-2,105.92529,NULL),(5487,1,7500.00000,NULL),(5487,-1,689.65517,NULL),(5487,-2,86.20690,NULL),(5488,1,5946.00000,NULL),(5488,-1,455.92332,NULL),(5488,-2,56.99042,NULL),(5489,1,6179.75000,NULL),(5489,-1,568.25287,NULL),(5489,-2,71.03161,NULL),(5491,1,5500.00000,NULL),(5491,-1,505.74713,NULL),(5491,-2,63.21839,NULL),(5492,1,5500.00000,NULL),(5492,-1,505.74713,NULL),(5492,-2,63.21839,NULL),(5493,1,6000.12500,NULL),(5493,-1,551.73563,NULL),(5493,-2,68.96695,NULL),(5494,1,6250.00000,NULL),(5494,-1,574.71264,NULL),(5494,-2,71.83908,NULL),(5495,1,5750.00000,NULL),(5495,-1,528.73563,NULL),(5495,-2,66.09195,NULL),(5496,1,6250.00000,NULL),(5496,-1,574.71264,NULL),(5496,-2,71.83908,NULL),(5497,1,6613.75000,NULL),(5497,-1,608.16092,NULL),(5497,-2,76.02011,NULL),(5498,1,5500.00000,NULL),(5498,-1,505.74713,NULL),(5498,-2,63.21839,NULL),(5499,1,6950.00000,NULL),(5499,-1,639.08046,NULL),(5499,-2,79.88506,NULL),(5500,1,6323.59500,NULL),(5500,-1,581.48000,NULL),(5500,-2,72.68500,NULL),(5502,1,11621.00000,NULL),(5502,-1,1068.59770,NULL),(5502,-2,133.57471,NULL),(5503,1,9601.47000,NULL),(5503,-1,882.89379,NULL),(5503,-2,110.36172,NULL),(5504,1,10000.00000,NULL),(5504,-1,919.54023,NULL),(5504,-2,114.94253,NULL),(5505,1,5500.00000,NULL),(5505,-1,505.74713,NULL),(5505,-2,63.21839,NULL),(5506,1,5000.00000,NULL),(5506,-1,459.77011,NULL),(5506,-2,57.47126,NULL),(5507,1,6988.50575,NULL),(5507,-1,873.56322,NULL),(5507,-2,109.19540,NULL),(5509,1,4045.97701,NULL),(5509,-1,505.74713,NULL),(5509,-2,63.21839,NULL),(5510,1,12500.00000,NULL),(5510,-1,1149.42529,NULL),(5510,-2,143.67816,NULL),(5511,1,7816.09195,NULL),(5511,-1,680.45977,NULL),(5511,-2,85.05747,NULL),(5512,1,25000.00000,NULL),(5512,-1,2298.85057,NULL),(5512,-2,287.35632,NULL),(5513,1,5947.00000,NULL),(5513,-1,456.00000,NULL),(5513,-2,57.00000,NULL),(5514,1,5500.00000,NULL),(5514,-1,505.74713,NULL),(5514,-2,63.21839,NULL),(5516,1,5947.00000,NULL),(5516,-1,456.00000,NULL),(5516,-2,57.00000,NULL),(5517,1,6070.00000,NULL),(5517,-1,558.16092,NULL),(5517,-2,69.77011,NULL),(5518,1,5500.00000,NULL),(5518,-1,505.74713,NULL),(5518,-2,63.21839,NULL),(5519,1,5500.00000,NULL),(5519,-1,505.74713,NULL),(5519,-2,63.21839,NULL),(5520,1,5500.00000,NULL),(5520,-1,505.74713,NULL),(5520,-2,63.21839,NULL),(5521,1,5500.00000,NULL),(5521,-1,505.74713,NULL),(5521,-2,63.21839,NULL),(5522,1,5500.00000,NULL),(5522,-1,505.74713,NULL),(5522,-2,63.21839,NULL),(5523,1,6615.25000,NULL),(5523,-1,608.29885,NULL),(5523,-2,76.03736,NULL),(5524,1,12847.08500,NULL),(5524,-1,1181.34115,NULL),(5524,-2,147.66764,NULL),(5525,1,8084.87500,NULL),(5525,-1,743.43678,NULL),(5525,-2,92.92960,NULL),(5526,1,10000.00000,NULL),(5526,-1,919.54023,NULL),(5526,-2,114.94253,NULL),(5527,1,6138.00000,NULL),(5527,-1,564.41379,NULL),(5527,-2,70.55172,NULL),(5528,1,5500.00000,NULL),(5528,-1,505.74713,NULL),(5528,-2,63.21839,NULL),(5529,1,12500.00000,NULL),(5529,-1,1149.42529,NULL),(5529,-2,143.67816,NULL),(5530,1,15000.00000,NULL),(5530,-1,1379.31034,NULL),(5530,-2,172.41379,NULL),(5531,1,11447.08500,NULL),(5531,-1,1052.60552,NULL),(5531,-2,131.57569,NULL),(5532,1,5500.00000,NULL),(5532,-1,505.74713,NULL),(5532,-2,63.21839,NULL),(5533,1,5500.00000,NULL),(5533,-1,505.74713,NULL),(5533,-2,63.21839,NULL),(5534,1,5500.00000,NULL),(5534,-1,505.74713,NULL),(5534,-2,63.21839,NULL),(5535,1,4959.00000,NULL),(5535,-1,456.00000,NULL),(5535,-2,57.00000,NULL),(5536,1,12500.00000,NULL),(5536,-1,1149.42529,NULL),(5536,-2,143.67816,NULL),(5537,1,5500.00000,NULL),(5537,-1,505.74713,NULL),(5537,-2,63.21839,NULL),(5538,1,5500.00000,NULL),(5538,-1,505.74713,NULL),(5538,-2,63.21839,NULL),(5539,1,10000.00000,NULL),(5539,-1,919.54023,NULL),(5539,-2,114.94253,NULL),(5540,1,10000.00000,NULL),(5540,-1,919.54023,NULL),(5540,-2,114.94253,NULL),(5541,1,5500.00000,NULL),(5541,-1,505.74713,NULL),(5541,-2,63.21839,NULL),(5542,1,10000.00000,NULL),(5542,-1,919.54023,NULL),(5542,-2,114.94253,NULL),(5543,1,6696.13500,NULL),(5543,-1,615.73655,NULL),(5543,-2,76.96707,NULL),(5544,1,6388.75000,NULL),(5544,-1,587.47126,NULL),(5544,-2,73.43391,NULL),(5545,1,6543.39500,NULL),(5545,-1,601.69149,NULL),(5545,-2,75.21144,NULL),(5546,1,8000.00000,NULL),(5546,-1,735.63218,NULL),(5546,-2,91.95402,NULL),(5547,1,7500.00000,NULL),(5547,-1,689.65517,NULL),(5547,-2,86.20690,NULL),(5548,1,5500.00000,NULL),(5548,-1,505.74713,NULL),(5548,-2,63.21839,NULL),(5549,1,5500.00000,NULL),(5549,-1,505.74713,NULL),(5549,-2,63.21839,NULL),(5550,1,5500.00000,NULL),(5550,-1,505.74713,NULL),(5550,-2,63.21839,NULL),(5551,1,5946.00000,NULL),(5551,-1,455.92332,NULL),(5551,-2,56.99042,NULL),(5552,1,5947.00000,NULL),(5552,-1,456.00000,NULL),(5552,-2,57.00000,NULL),(5553,1,10000.00000,NULL),(5553,-1,919.54023,NULL),(5553,-2,114.94253,NULL),(5554,1,12500.00000,NULL),(5554,-1,1149.42529,NULL),(5554,-2,143.67816,NULL),(5555,1,7000.00000,NULL),(5555,-1,536.74121,NULL),(5555,-2,67.09265,NULL),(5556,1,5500.00000,NULL),(5556,-1,505.74713,NULL),(5556,-2,63.21839,NULL),(5557,1,6013.95000,NULL),(5557,-1,553.00690,NULL),(5557,-2,69.12586,NULL),(5558,1,6613.75000,NULL),(5558,-1,608.16092,NULL),(5558,-2,76.02011,NULL),(5559,1,5500.00000,NULL),(5559,-1,505.74713,NULL),(5559,-2,63.21839,NULL),(5560,1,5500.00000,NULL),(5560,-1,505.74713,NULL),(5560,-2,63.21839,NULL),(5561,1,12613.75000,NULL),(5561,-1,1159.88506,NULL),(5561,-2,144.98563,NULL),(5562,1,5500.00000,NULL),(5562,-1,505.74713,NULL),(5562,-2,63.21839,NULL),(5563,1,6613.75000,NULL),(5563,-1,608.16092,NULL),(5563,-2,76.02011,NULL),(5564,1,11998.57500,NULL),(5564,-1,1103.31724,NULL),(5564,-2,137.91466,NULL),(5565,1,5500.00000,NULL),(5565,-1,505.74713,NULL),(5565,-2,63.21839,NULL),(5566,1,17447.08500,NULL),(5566,-1,1604.32966,NULL),(5566,-2,200.54121,NULL),(5567,1,5500.00000,NULL),(5567,-1,505.74713,NULL),(5567,-2,63.21839,NULL),(5568,1,5500.00000,NULL),(5568,-1,505.74713,NULL),(5568,-2,63.21839,NULL),(5569,1,12047.22500,NULL),(5569,-1,1107.79080,NULL),(5569,-2,138.47385,NULL),(5570,1,5982.96500,NULL),(5570,-1,550.15770,NULL),(5570,-2,68.76971,NULL),(5571,1,5500.00000,NULL),(5571,-1,505.74713,NULL),(5571,-2,63.21839,NULL),(5572,1,8688.75000,NULL),(5572,-1,798.96552,NULL),(5572,-2,99.87069,NULL),(5573,1,7500.00000,NULL),(5573,-1,689.65517,NULL),(5573,-2,86.20690,NULL),(5574,1,5500.00000,NULL),(5574,-1,505.74713,NULL),(5574,-2,63.21839,NULL),(5575,1,9215.50000,NULL),(5575,-1,847.40230,NULL),(5575,-2,105.92529,NULL),(5576,1,7500.00000,NULL),(5576,-1,689.65517,NULL),(5576,-2,86.20690,NULL),(5577,1,5946.00000,NULL),(5577,-1,455.92332,NULL),(5577,-2,56.99042,NULL),(5578,1,6179.75000,NULL),(5578,-1,568.25287,NULL),(5578,-2,71.03161,NULL),(5579,1,25000.00000,NULL),(5579,-1,2298.85057,NULL),(5579,-2,287.35632,NULL),(5580,1,5500.00000,NULL),(5580,-1,505.74713,NULL),(5580,-2,63.21839,NULL),(5581,1,5500.00000,NULL),(5581,-1,505.74713,NULL),(5581,-2,63.21839,NULL),(5582,1,6000.12500,NULL),(5582,-1,551.73563,NULL),(5582,-2,68.96695,NULL),(5583,1,6250.00000,NULL),(5583,-1,574.71264,NULL),(5583,-2,71.83908,NULL),(5584,1,5750.00000,NULL),(5584,-1,528.73563,NULL),(5584,-2,66.09195,NULL),(5585,1,6250.00000,NULL),(5585,-1,574.71264,NULL),(5585,-2,71.83908,NULL),(5586,1,6613.75000,NULL),(5586,-1,608.16092,NULL),(5586,-2,76.02011,NULL),(5587,1,5500.00000,NULL),(5587,-1,505.74713,NULL),(5587,-2,63.21839,NULL),(5588,1,6950.00000,NULL),(5588,-1,639.08046,NULL),(5588,-2,79.88506,NULL),(5589,1,6323.59500,NULL),(5589,-1,581.48000,NULL),(5589,-2,72.68500,NULL),(5590,1,7400.00000,NULL),(5590,-1,680.45977,NULL),(5590,-2,85.05747,NULL),(5591,1,11621.00000,NULL),(5591,-1,1068.59770,NULL),(5591,-2,133.57471,NULL),(5592,1,9601.47000,NULL),(5592,-1,882.89379,NULL),(5592,-2,110.36172,NULL),(5593,1,10000.00000,NULL),(5593,-1,919.54023,NULL),(5593,-2,114.94253,NULL),(5594,1,5500.00000,NULL),(5594,-1,505.74713,NULL),(5594,-2,63.21839,NULL),(5595,1,5000.00000,NULL),(5595,-1,459.77011,NULL),(5595,-2,57.47126,NULL),(5596,1,9500.00000,NULL),(5596,-1,873.56322,NULL),(5596,-2,109.19540,NULL),(5599,1,575.07987,NULL),(5599,-1,575.07987,NULL),(5599,-2,71.88498,NULL),(5603,1,5947.00000,NULL),(5603,-1,456.00000,NULL),(5603,-2,57.00000,NULL),(5604,1,5500.00000,NULL),(5604,-1,505.74713,NULL),(5604,-2,63.21839,NULL),(5605,1,5947.00000,NULL),(5605,-1,456.00000,NULL),(5605,-2,57.00000,NULL),(5606,1,6070.00000,NULL),(5606,-1,558.16092,NULL),(5606,-2,69.77011,NULL),(5607,1,5500.00000,NULL),(5607,-1,505.74713,NULL),(5607,-2,63.21839,NULL),(5608,1,5500.00000,NULL),(5608,-1,505.74713,NULL),(5608,-2,63.21839,NULL),(5609,1,5500.00000,NULL),(5609,-1,505.74713,NULL),(5609,-2,63.21839,NULL),(5610,1,5500.00000,NULL),(5610,-1,505.74713,NULL),(5610,-2,63.21839,NULL),(5611,1,5500.00000,NULL),(5611,-1,505.74713,NULL),(5611,-2,63.21839,NULL),(5612,1,6615.25000,NULL),(5612,-1,608.29885,NULL),(5612,-2,76.03736,NULL),(5613,1,12847.08500,NULL),(5613,-1,1181.34115,NULL),(5613,-2,147.66764,NULL),(5614,1,8084.87500,NULL),(5614,-1,743.43678,NULL),(5614,-2,92.92960,NULL),(5616,1,6138.00000,NULL),(5616,-1,564.41379,NULL),(5616,-2,70.55172,NULL),(5617,1,5500.00000,NULL),(5617,-1,505.74713,NULL),(5617,-2,63.21839,NULL),(5618,1,15000.00000,NULL),(5618,-1,1379.31034,NULL),(5618,-2,172.41379,NULL),(5619,1,12500.00000,NULL),(5619,-1,1149.42529,NULL),(5619,-2,143.67816,NULL),(5620,1,11447.08500,NULL),(5620,-1,1052.60552,NULL),(5620,-2,131.57569,NULL),(5621,1,5500.00000,NULL),(5621,-1,505.74713,NULL),(5621,-2,63.21839,NULL),(5622,1,5500.00000,NULL),(5622,-1,505.74713,NULL),(5622,-2,63.21839,NULL),(5623,1,5500.00000,NULL),(5623,-1,505.74713,NULL),(5623,-2,63.21839,NULL),(5624,1,4959.00000,NULL),(5624,-1,456.00000,NULL),(5624,-2,57.00000,NULL),(5625,1,12500.00000,NULL),(5625,-1,1149.42529,NULL),(5625,-2,143.67816,NULL),(5626,1,5500.00000,NULL),(5626,-1,505.74713,NULL),(5626,-2,63.21839,NULL),(5627,1,5500.00000,NULL),(5627,-1,505.74713,NULL),(5627,-2,63.21839,NULL),(5628,1,10000.00000,NULL),(5628,-1,919.54023,NULL),(5628,-2,114.94253,NULL),(5629,1,10000.00000,NULL),(5629,-1,919.54023,NULL),(5629,-2,114.94253,NULL),(5630,1,5500.00000,NULL),(5630,-1,505.74713,NULL),(5630,-2,63.21839,NULL),(5631,1,6696.13500,NULL),(5631,-1,615.73655,NULL),(5631,-2,76.96707,NULL),(5632,1,10000.00000,NULL),(5632,-1,919.54023,NULL),(5632,-2,114.94253,NULL),(5633,1,6388.75000,NULL),(5633,-1,587.47126,NULL),(5633,-2,73.43391,NULL),(5634,1,6543.39500,NULL),(5634,-1,601.69149,NULL),(5634,-2,75.21144,NULL),(5635,1,8000.00000,NULL),(5635,-1,735.63218,NULL),(5635,-2,91.95402,NULL),(5636,1,5500.00000,NULL),(5636,-1,505.74713,NULL),(5636,-2,63.21839,NULL),(5637,1,7500.00000,NULL),(5637,-1,689.65517,NULL),(5637,-2,86.20690,NULL),(5638,1,5500.00000,NULL),(5638,-1,505.74713,NULL),(5638,-2,63.21839,NULL),(5639,1,5500.00000,NULL),(5639,-1,505.74713,NULL),(5639,-2,63.21839,NULL),(5640,1,5946.00000,NULL),(5640,-1,455.92332,NULL),(5640,-2,56.99042,NULL),(5641,1,5947.00000,NULL),(5641,-1,456.00000,NULL),(5641,-2,57.00000,NULL),(5642,1,10000.00000,NULL),(5642,-1,919.54023,NULL),(5642,-2,114.94253,NULL),(5643,1,12500.00000,NULL),(5643,-1,1149.42529,NULL),(5643,-2,143.67816,NULL),(5644,1,7000.00000,NULL),(5644,-1,536.74121,NULL),(5644,-2,67.09265,NULL),(5645,1,5500.00000,NULL),(5645,-1,505.74713,NULL),(5645,-2,63.21839,NULL),(5646,1,6013.95000,NULL),(5646,-1,553.00690,NULL),(5646,-2,69.12586,NULL),(5647,1,6613.75000,NULL),(5647,-1,608.16092,NULL),(5647,-2,76.02011,NULL),(5648,1,5500.00000,NULL),(5648,-1,505.74713,NULL),(5648,-2,63.21839,NULL),(5649,1,5500.00000,NULL),(5649,-1,505.74713,NULL),(5649,-2,63.21839,NULL),(5650,1,12613.75000,NULL),(5650,-1,1159.88506,NULL),(5650,-2,144.98563,NULL),(5651,1,5500.00000,NULL),(5651,-1,505.74713,NULL),(5651,-2,63.21839,NULL),(5652,1,6613.75000,NULL),(5652,-1,608.16092,NULL),(5652,-2,76.02011,NULL),(5653,1,11998.57500,NULL),(5653,-1,1103.31724,NULL),(5653,-2,137.91466,NULL),(5654,1,5500.00000,NULL),(5654,-1,505.74713,NULL),(5654,-2,63.21839,NULL),(5655,1,17447.08500,NULL),(5655,-1,1604.32966,NULL),(5655,-2,200.54121,NULL),(5656,1,5500.00000,NULL),(5656,-1,505.74713,NULL),(5656,-2,63.21839,NULL),(5657,1,5500.00000,NULL),(5657,-1,505.74713,NULL),(5657,-2,63.21839,NULL),(5659,1,5982.96500,NULL),(5659,-1,550.15770,NULL),(5659,-2,68.76971,NULL),(5660,1,5500.00000,NULL),(5660,-1,505.74713,NULL),(5660,-2,63.21839,NULL),(5661,1,8688.75000,NULL),(5661,-1,798.96552,NULL),(5661,-2,99.87069,NULL),(5662,1,7500.00000,NULL),(5662,-1,689.65517,NULL),(5662,-2,86.20690,NULL),(5663,1,5500.00000,NULL),(5663,-1,505.74713,NULL),(5663,-2,63.21839,NULL),(5664,1,9215.50000,NULL),(5664,-1,847.40230,NULL),(5664,-2,105.92529,NULL),(5665,1,7500.00000,NULL),(5665,-1,689.65517,NULL),(5665,-2,86.20690,NULL),(5666,1,5946.00000,NULL),(5666,-1,455.92332,NULL),(5666,-2,56.99042,NULL),(5667,1,6179.75000,NULL),(5667,-1,568.25287,NULL),(5667,-2,71.03161,NULL),(5668,1,25000.00000,NULL),(5668,-1,2298.85057,NULL),(5668,-2,287.35632,NULL),(5669,1,5500.00000,NULL),(5669,-1,505.74713,NULL),(5669,-2,63.21839,NULL),(5670,1,5500.00000,NULL),(5670,-1,505.74713,NULL),(5670,-2,63.21839,NULL),(5671,1,6000.12500,NULL),(5671,-1,551.73563,NULL),(5671,-2,68.96695,NULL),(5672,1,6250.00000,NULL),(5672,-1,574.71264,NULL),(5672,-2,71.83908,NULL),(5673,1,5750.00000,NULL),(5673,-1,528.73563,NULL),(5673,-2,66.09195,NULL),(5674,1,6250.00000,NULL),(5674,-1,574.71264,NULL),(5674,-2,71.83908,NULL),(5675,1,6613.75000,NULL),(5675,-1,608.16092,NULL),(5675,-2,76.02011,NULL),(5676,1,5500.00000,NULL),(5676,-1,505.74713,NULL),(5676,-2,63.21839,NULL),(5677,1,6950.00000,NULL),(5677,-1,639.08046,NULL),(5677,-2,79.88506,NULL),(5678,1,6323.59500,NULL),(5678,-1,581.48000,NULL),(5678,-2,72.68500,NULL),(5679,1,7400.00000,NULL),(5679,-1,680.45977,NULL),(5679,-2,85.05747,NULL),(5680,1,11621.00000,NULL),(5680,-1,1068.59770,NULL),(5680,-2,133.57471,NULL),(5681,1,9601.47000,NULL),(5681,-1,882.89379,NULL),(5681,-2,110.36172,NULL),(5682,1,10000.00000,NULL),(5682,-1,919.54023,NULL),(5682,-2,114.94253,NULL),(5683,1,5500.00000,NULL),(5683,-1,505.74713,NULL),(5683,-2,63.21839,NULL),(5684,1,5000.00000,NULL),(5684,-1,459.77011,NULL),(5684,-2,57.47126,NULL),(5685,1,9500.00000,NULL),(5685,-1,873.56322,NULL),(5685,-2,109.19540,NULL),(5690,1,10000.00000,NULL),(5690,-1,919.54023,NULL),(5690,-2,114.94253,NULL),(5692,1,5947.00000,NULL),(5692,-1,456.00000,NULL),(5692,-2,57.00000,NULL),(5693,1,5500.00000,NULL),(5693,-1,505.74713,NULL),(5693,-2,63.21839,NULL),(5694,1,5947.00000,NULL),(5694,-1,456.00000,NULL),(5694,-2,57.00000,NULL),(5695,1,6070.00000,NULL),(5695,-1,558.16092,NULL),(5695,-2,69.77011,NULL),(5696,1,5500.00000,NULL),(5696,-1,505.74713,NULL),(5696,-2,63.21839,NULL),(5697,1,5500.00000,NULL),(5697,-1,505.74713,NULL),(5697,-2,63.21839,NULL),(5698,1,5500.00000,NULL),(5698,-1,505.74713,NULL),(5698,-2,63.21839,NULL),(5699,1,5500.00000,NULL),(5699,-1,505.74713,NULL),(5699,-2,63.21839,NULL),(5700,1,5500.00000,NULL),(5700,-1,505.74713,NULL),(5700,-2,63.21839,NULL),(5701,1,6615.25000,NULL),(5701,-1,608.29885,NULL),(5701,-2,76.03736,NULL),(5702,1,12847.08500,NULL),(5702,-1,1181.34115,NULL),(5702,-2,147.66764,NULL),(5703,1,8084.87500,NULL),(5703,-1,743.43678,NULL),(5703,-2,92.92960,NULL),(5704,1,6138.00000,NULL),(5704,-1,564.41379,NULL),(5704,-2,70.55172,NULL),(5705,1,5500.00000,NULL),(5705,-1,505.74713,NULL),(5705,-2,63.21839,NULL),(5706,1,15000.00000,NULL),(5706,-1,1379.31034,NULL),(5706,-2,172.41379,NULL),(5707,1,12500.00000,NULL),(5707,-1,1149.42529,NULL),(5707,-2,143.67816,NULL),(5709,1,5500.00000,NULL),(5709,-1,505.74713,NULL),(5709,-2,63.21839,NULL),(5710,1,5500.00000,NULL),(5710,-1,505.74713,NULL),(5710,-2,63.21839,NULL),(5711,1,5500.00000,NULL),(5711,-1,505.74713,NULL),(5711,-2,63.21839,NULL),(5712,1,4959.00000,NULL),(5712,-1,456.00000,NULL),(5712,-2,57.00000,NULL),(5713,1,12500.00000,NULL),(5713,-1,1149.42529,NULL),(5713,-2,143.67816,NULL),(5714,1,5500.00000,NULL),(5714,-1,505.74713,NULL),(5714,-2,63.21839,NULL),(5715,1,5500.00000,NULL),(5715,-1,505.74713,NULL),(5715,-2,63.21839,NULL),(5716,1,10000.00000,NULL),(5716,-1,919.54023,NULL),(5716,-2,114.94253,NULL),(5717,1,10000.00000,NULL),(5717,-1,919.54023,NULL),(5717,-2,114.94253,NULL),(5718,1,5500.00000,NULL),(5718,-1,505.74713,NULL),(5718,-2,63.21839,NULL),(5719,1,6696.13500,NULL),(5719,-1,615.73655,NULL),(5719,-2,76.96707,NULL),(5720,1,10000.00000,NULL),(5720,-1,919.54023,NULL),(5720,-2,114.94253,NULL),(5721,1,6388.75000,NULL),(5721,-1,587.47126,NULL),(5721,-2,73.43391,NULL),(5722,1,6543.39500,NULL),(5722,-1,601.69149,NULL),(5722,-2,75.21144,NULL),(5723,1,8000.00000,NULL),(5723,-1,735.63218,NULL),(5723,-2,91.95402,NULL),(5724,1,7500.00000,NULL),(5724,-1,689.65517,NULL),(5724,-2,86.20690,NULL),(5725,1,5500.00000,NULL),(5725,-1,505.74713,NULL),(5725,-2,63.21839,NULL),(5726,1,5500.00000,NULL),(5726,-1,505.74713,NULL),(5726,-2,63.21839,NULL),(5727,1,5500.00000,NULL),(5727,-1,505.74713,NULL),(5727,-2,63.21839,NULL),(5728,1,5946.00000,NULL),(5728,-1,455.92332,NULL),(5728,-2,56.99042,NULL),(5729,1,5947.00000,NULL),(5729,-1,456.00000,NULL),(5729,-2,57.00000,NULL),(5730,1,10000.00000,NULL),(5730,-1,919.54023,NULL),(5730,-2,114.94253,NULL),(5731,1,12500.00000,NULL),(5731,-1,1149.42529,NULL),(5731,-2,143.67816,NULL),(5732,1,7000.00000,NULL),(5732,-1,536.74121,NULL),(5732,-2,67.09265,NULL),(5733,1,5500.00000,NULL),(5733,-1,505.74713,NULL),(5733,-2,63.21839,NULL),(5734,1,6013.95000,NULL),(5734,-1,553.00690,NULL),(5734,-2,69.12586,NULL),(5735,1,6613.75000,NULL),(5735,-1,608.16092,NULL),(5735,-2,76.02011,NULL),(5736,1,5500.00000,NULL),(5736,-1,505.74713,NULL),(5736,-2,63.21839,NULL),(5738,1,12613.75000,NULL),(5738,-1,1159.88506,NULL),(5738,-2,144.98563,NULL),(5739,1,5500.00000,NULL),(5739,-1,505.74713,NULL),(5739,-2,63.21839,NULL),(5740,1,6265.51724,NULL),(5740,-1,654.13793,NULL),(5740,-2,81.76724,NULL),(5741,1,11998.57500,NULL),(5741,-1,1103.31724,NULL),(5741,-2,137.91466,NULL),(5742,1,5500.00000,NULL),(5742,-1,505.74713,NULL),(5742,-2,63.21839,NULL),(5744,1,5500.00000,NULL),(5744,-1,505.74713,NULL),(5744,-2,63.21839,NULL),(5745,1,5500.00000,NULL),(5745,-1,505.74713,NULL),(5745,-2,63.21839,NULL),(5746,1,5982.96500,NULL),(5746,-1,550.15770,NULL),(5746,-2,68.76971,NULL),(5747,1,5500.00000,NULL),(5747,-1,505.74713,NULL),(5747,-2,63.21839,NULL),(5748,1,8688.75000,NULL),(5748,-1,798.96552,NULL),(5748,-2,99.87069,NULL),(5749,1,7500.00000,NULL),(5749,-1,689.65517,NULL),(5749,-2,86.20690,NULL),(5750,1,5500.00000,NULL),(5750,-1,505.74713,NULL),(5750,-2,63.21839,NULL),(5751,1,9215.50000,NULL),(5751,-1,847.40230,NULL),(5751,-2,105.92529,NULL),(5752,1,7875.00000,NULL),(5752,-1,724.13793,NULL),(5752,-2,90.51724,NULL),(5753,1,5946.00000,NULL),(5753,-1,455.92332,NULL),(5753,-2,56.99042,NULL),(5755,1,25000.00000,NULL),(5755,-1,2298.85057,NULL),(5755,-2,287.35632,NULL),(5756,1,5500.00000,NULL),(5756,-1,505.74713,NULL),(5756,-2,63.21839,NULL),(5757,1,5500.00000,NULL),(5757,-1,505.74713,NULL),(5757,-2,63.21839,NULL),(5758,1,6000.12500,NULL),(5758,-1,551.73563,NULL),(5758,-2,68.96695,NULL),(5759,1,6250.00000,NULL),(5759,-1,574.71264,NULL),(5759,-2,71.83908,NULL),(5760,1,5750.00000,NULL),(5760,-1,528.73563,NULL),(5760,-2,66.09195,NULL),(5761,1,6250.00000,NULL),(5761,-1,574.71264,NULL),(5761,-2,71.83908,NULL),(5764,1,6950.00000,NULL),(5764,-1,639.08046,NULL),(5764,-2,79.88506,NULL),(5765,1,6323.59500,NULL),(5765,-1,581.48000,NULL),(5765,-2,72.68500,NULL),(5766,1,7400.00000,NULL),(5766,-1,680.45977,NULL),(5766,-2,85.05747,NULL),(5767,1,11621.00000,NULL),(5767,-1,1068.59770,NULL),(5767,-2,133.57471,NULL),(5768,1,9601.47000,NULL),(5768,-1,882.89379,NULL),(5768,-2,110.36172,NULL),(5769,1,10000.00000,NULL),(5769,-1,919.54023,NULL),(5769,-2,114.94253,NULL),(5770,1,5500.00000,NULL),(5770,-1,505.74713,NULL),(5770,-2,63.21839,NULL),(5771,1,5449.50000,NULL),(5771,-1,501.10345,NULL),(5771,-2,62.63793,NULL),(5772,1,9500.00000,NULL),(5772,-1,873.56322,NULL),(5772,-2,109.19540,NULL),(5779,1,2841.26437,NULL),(5779,-1,568.25287,NULL),(5779,-2,71.03161,NULL),(5781,1,2022.98851,NULL),(5781,-1,505.74713,NULL),(5781,-2,63.21839,NULL),(5784,1,5263.02759,NULL),(5784,-1,1052.60552,NULL),(5784,-2,131.57569,NULL),(5785,1,8275.86207,NULL),(5785,-1,1379.31034,NULL),(5785,-2,172.41379,NULL),(5789,1,5500.00000,NULL),(5789,-1,505.74713,NULL),(5789,-2,63.21839,NULL),(5790,1,17447.08500,NULL),(5790,-1,1604.32966,NULL),(5790,-2,200.54121,NULL),(5792,1,7754.53563,NULL),(5792,-1,1107.79080,NULL),(5792,-2,138.47385,NULL),(5793,1,6613.75000,NULL),(5793,-1,608.16092,NULL),(5793,-2,76.02011,NULL),(5794,1,5947.00000,NULL),(5794,-1,456.00000,NULL),(5794,-2,57.00000,NULL),(5795,1,5500.00000,NULL),(5795,-1,505.74713,NULL),(5795,-2,63.21839,NULL),(5796,1,5947.00000,NULL),(5796,-1,456.00000,NULL),(5796,-2,57.00000,NULL),(5797,1,6070.00000,NULL),(5797,-1,558.16092,NULL),(5797,-2,69.77011,NULL),(5798,1,5500.00000,NULL),(5798,-1,505.74713,NULL),(5798,-2,63.21839,NULL),(5799,1,5500.00000,NULL),(5799,-1,505.74713,NULL),(5799,-2,63.21839,NULL),(5801,1,5500.00000,NULL),(5801,-1,505.74713,NULL),(5801,-2,63.21839,NULL),(5802,1,5500.00000,NULL),(5802,-1,505.74713,NULL),(5802,-2,63.21839,NULL),(5803,1,6615.25000,NULL),(5803,-1,608.29885,NULL),(5803,-2,76.03736,NULL),(5804,1,12847.08500,NULL),(5804,-1,1181.34115,NULL),(5804,-2,147.66764,NULL),(5805,1,8084.87500,NULL),(5805,-1,743.43678,NULL),(5805,-2,92.92960,NULL),(5806,1,6138.00000,NULL),(5806,-1,564.41379,NULL),(5806,-2,70.55172,NULL),(5807,1,5500.00000,NULL),(5807,-1,505.74713,NULL),(5807,-2,63.21839,NULL),(5808,1,5500.00000,NULL),(5808,-1,505.74713,NULL),(5808,-2,63.21839,NULL),(5809,1,17412.41379,NULL),(5809,-1,1752.64368,NULL),(5809,-2,219.08046,NULL),(5810,1,12500.00000,NULL),(5810,-1,1149.42529,NULL),(5810,-2,143.67816,NULL),(5811,1,5500.00000,NULL),(5811,-1,505.74713,NULL),(5811,-2,63.21839,NULL),(5812,1,5500.00000,NULL),(5812,-1,505.74713,NULL),(5812,-2,63.21839,NULL),(5813,1,5500.00000,NULL),(5813,-1,505.74713,NULL),(5813,-2,63.21839,NULL),(5814,1,4959.00000,NULL),(5814,-1,456.00000,NULL),(5814,-2,57.00000,NULL),(5815,1,12500.00000,NULL),(5815,-1,1149.42529,NULL),(5815,-2,143.67816,NULL),(5817,1,5500.00000,NULL),(5817,-1,505.74713,NULL),(5817,-2,63.21839,NULL),(5819,1,10000.00000,NULL),(5819,-1,919.54023,NULL),(5819,-2,114.94253,NULL),(5820,1,5500.00000,NULL),(5820,-1,505.74713,NULL),(5820,-2,63.21839,NULL),(5821,1,10000.00000,NULL),(5821,-1,919.54023,NULL),(5821,-2,114.94253,NULL),(5822,1,10000.00000,NULL),(5822,-1,919.54023,NULL),(5822,-2,114.94253,NULL),(5823,1,6696.13500,NULL),(5823,-1,615.73655,NULL),(5823,-2,76.96707,NULL),(5824,1,6388.75000,NULL),(5824,-1,587.47126,NULL),(5824,-2,73.43391,NULL),(5825,1,6543.39500,NULL),(5825,-1,601.69149,NULL),(5825,-2,75.21144,NULL),(5826,1,8000.00000,NULL),(5826,-1,735.63218,NULL),(5826,-2,91.95402,NULL),(5827,1,5500.00000,NULL),(5827,-1,505.74713,NULL),(5827,-2,63.21839,NULL),(5828,1,7500.00000,NULL),(5828,-1,689.65517,NULL),(5828,-2,86.20690,NULL),(5829,1,5500.00000,NULL),(5829,-1,505.74713,NULL),(5829,-2,63.21839,NULL),(5830,1,5500.00000,NULL),(5830,-1,505.74713,NULL),(5830,-2,63.21839,NULL),(5831,1,5946.00000,NULL),(5831,-1,455.92332,NULL),(5831,-2,56.99042,NULL),(5832,1,5947.00000,NULL),(5832,-1,456.00000,NULL),(5832,-2,57.00000,NULL),(5833,1,12500.00000,NULL),(5833,-1,1149.42529,NULL),(5833,-2,143.67816,NULL),(5834,1,10000.00000,NULL),(5834,-1,919.54023,NULL),(5834,-2,114.94253,NULL),(5835,1,7000.00000,NULL),(5835,-1,536.74121,NULL),(5835,-2,67.09265,NULL),(5836,1,5500.00000,NULL),(5836,-1,505.74713,NULL),(5836,-2,63.21839,NULL),(5837,1,6013.95000,NULL),(5837,-1,553.00690,NULL),(5837,-2,69.12586,NULL),(5838,1,6613.75000,NULL),(5838,-1,608.16092,NULL),(5838,-2,76.02011,NULL),(5839,1,5500.00000,NULL),(5839,-1,505.74713,NULL),(5839,-2,63.21839,NULL),(5840,1,5500.00000,NULL),(5840,-1,505.74713,NULL),(5840,-2,63.21839,NULL),(5841,1,12613.75000,NULL),(5841,-1,1159.88506,NULL),(5841,-2,144.98563,NULL),(5842,1,5500.00000,NULL),(5842,-1,505.74713,NULL),(5842,-2,63.21839,NULL),(5843,1,7113.75000,NULL),(5843,-1,654.13793,NULL),(5843,-2,81.76724,NULL),(5844,1,11998.57500,NULL),(5844,-1,1103.31724,NULL),(5844,-2,137.91466,NULL),(5845,1,5500.00000,NULL),(5845,-1,505.74713,NULL),(5845,-2,63.21839,NULL),(5846,1,17447.08500,NULL),(5846,-1,1604.32966,NULL),(5846,-2,200.54121,NULL),(5847,1,5500.00000,NULL),(5847,-1,505.74713,NULL),(5847,-2,63.21839,NULL),(5848,1,5500.00000,NULL),(5848,-1,505.74713,NULL),(5848,-2,63.21839,NULL),(5849,1,5982.96500,NULL),(5849,-1,550.15770,NULL),(5849,-2,68.76971,NULL),(5850,1,5500.00000,NULL),(5850,-1,505.74713,NULL),(5850,-2,63.21839,NULL),(5851,1,8688.75000,NULL),(5851,-1,798.96552,NULL),(5851,-2,99.87069,NULL),(5852,1,7500.00000,NULL),(5852,-1,689.65517,NULL),(5852,-2,86.20690,NULL),(5853,1,5500.00000,NULL),(5853,-1,505.74713,NULL),(5853,-2,63.21839,NULL),(5854,1,9215.50000,NULL),(5854,-1,847.40230,NULL),(5854,-2,105.92529,NULL),(5855,1,7875.00000,NULL),(5855,-1,724.13793,NULL),(5855,-2,90.51724,NULL),(5856,1,5946.00000,NULL),(5856,-1,455.92332,NULL),(5856,-2,56.99042,NULL),(5857,1,25000.00000,NULL),(5857,-1,2298.85057,NULL),(5857,-2,287.35632,NULL),(5858,1,5500.00000,NULL),(5858,-1,505.74713,NULL),(5858,-2,63.21839,NULL),(5859,1,5500.00000,NULL),(5859,-1,505.74713,NULL),(5859,-2,63.21839,NULL),(5860,1,6000.12500,NULL),(5860,-1,551.73563,NULL),(5860,-2,68.96695,NULL),(5861,1,6250.00000,NULL),(5861,-1,574.71264,NULL),(5861,-2,71.83908,NULL),(5862,1,5750.00000,NULL),(5862,-1,528.73563,NULL),(5862,-2,66.09195,NULL),(5863,1,6250.00000,NULL),(5863,-1,574.71264,NULL),(5863,-2,71.83908,NULL),(5864,1,16500.00000,NULL),(5864,-1,1517.24138,NULL),(5864,-2,189.65517,NULL),(5865,1,6950.00000,NULL),(5865,-1,639.08046,NULL),(5865,-2,79.88506,NULL),(5866,1,6323.59500,NULL),(5866,-1,581.48000,NULL),(5866,-2,72.68500,NULL),(5867,1,1951.95402,NULL),(5867,-1,975.97701,NULL),(5867,-2,121.99713,NULL),(5868,1,7400.00000,NULL),(5868,-1,680.45977,NULL),(5868,-2,85.05747,NULL),(5869,1,11621.00000,NULL),(5869,-1,1068.59770,NULL),(5869,-2,133.57471,NULL),(5870,1,9601.47000,NULL),(5870,-1,882.89379,NULL),(5870,-2,110.36172,NULL),(5871,1,10000.00000,NULL),(5871,-1,919.54023,NULL),(5871,-2,114.94253,NULL),(5872,1,5500.00000,NULL),(5872,-1,505.74713,NULL),(5872,-2,63.21839,NULL),(5873,1,15000.00000,NULL),(5873,-1,1379.31034,NULL),(5873,-2,172.41379,NULL),(5874,1,5449.50000,NULL),(5874,-1,501.10345,NULL),(5874,-2,62.63793,NULL),(5875,1,9500.00000,NULL),(5875,-1,873.56322,NULL),(5875,-2,109.19540,NULL),(5880,1,22500.00000,NULL),(5880,-1,2068.96552,NULL),(5880,-2,258.62069,NULL),(5881,1,5500.00000,NULL),(5881,-1,505.74713,NULL),(5881,-2,63.21839,NULL),(5882,1,6027.99872,NULL),(5882,-1,465.99987,NULL),(5882,-2,58.24998,NULL),(5883,1,5500.00000,NULL),(5883,-1,505.74713,NULL),(5883,-2,63.21839,NULL),(5884,1,6027.99872,NULL),(5884,-1,465.99987,NULL),(5884,-2,58.24998,NULL),(5885,1,6070.00000,NULL),(5885,-1,558.16092,NULL),(5885,-2,69.77011,NULL),(5887,1,6000.00000,NULL),(5887,-1,551.72414,NULL),(5887,-2,68.96552,NULL),(5888,1,5500.00000,NULL),(5888,-1,505.74713,NULL),(5888,-2,63.21839,NULL),(5889,1,5500.00000,NULL),(5889,-1,505.74713,NULL),(5889,-2,63.21839,NULL),(5890,1,6615.25000,NULL),(5890,-1,608.29885,NULL),(5890,-2,76.03736,NULL),(5891,1,12847.08500,NULL),(5891,-1,1181.34115,NULL),(5891,-2,147.66764,NULL),(5892,1,8689.87500,NULL),(5892,-1,799.06897,NULL),(5892,-2,99.88362,NULL),(5893,1,6138.00000,NULL),(5893,-1,564.41379,NULL),(5893,-2,70.55172,NULL),(5894,1,5500.00000,NULL),(5894,-1,505.74713,NULL),(5894,-2,63.21839,NULL),(5895,1,6000.00000,NULL),(5895,-1,551.72414,NULL),(5895,-2,68.96552,NULL),(5896,1,19060.00000,NULL),(5896,-1,1752.64368,NULL),(5896,-2,219.08046,NULL),(5897,1,12500.00000,NULL),(5897,-1,1149.42529,NULL),(5897,-2,143.67816,NULL),(5898,1,5500.00000,NULL),(5898,-1,505.74713,NULL),(5898,-2,63.21839,NULL),(5899,1,5500.00000,NULL),(5899,-1,505.74713,NULL),(5899,-2,63.21839,NULL),(5900,1,6187.50000,NULL),(5900,-1,568.96552,NULL),(5900,-2,71.12069,NULL),(5901,1,5096.00000,NULL),(5901,-1,466.00000,NULL),(5901,-2,58.25000,NULL),(5902,1,12500.00000,NULL),(5902,-1,1149.42529,NULL),(5902,-2,143.67816,NULL),(5903,1,6000.00000,NULL),(5903,-1,551.72414,NULL),(5903,-2,68.96552,NULL),(5904,1,11587.55000,NULL),(5904,-1,1065.52184,NULL),(5904,-2,133.19023,NULL),(5905,1,11817.01000,NULL),(5905,-1,1086.62161,NULL),(5905,-2,135.82770,NULL),(5906,1,5500.00000,NULL),(5906,-1,505.74713,NULL),(5906,-2,63.21839,NULL),(5907,1,13400.95500,NULL),(5907,-1,1232.27172,NULL),(5907,-2,154.03397,NULL),(5908,1,6696.13500,NULL),(5908,-1,615.73655,NULL),(5908,-2,76.96707,NULL),(5909,1,6388.75000,NULL),(5909,-1,587.47126,NULL),(5909,-2,73.43391,NULL),(5910,1,6543.39500,NULL),(5910,-1,601.69149,NULL),(5910,-2,75.21144,NULL),(5911,1,8000.00000,NULL),(5911,-1,735.63218,NULL),(5911,-2,91.95402,NULL),(5912,1,6153.25000,NULL),(5912,-1,565.81609,NULL),(5912,-2,70.72701,NULL),(5913,1,9050.00000,NULL),(5913,-1,832.18391,NULL),(5913,-2,104.02299,NULL),(5914,1,5500.00000,NULL),(5914,-1,505.74713,NULL),(5914,-2,63.21839,NULL),(5915,1,6500.25000,NULL),(5915,-1,597.72414,NULL),(5915,-2,74.71552,NULL),(5916,1,6027.76869,NULL),(5916,-1,465.99987,NULL),(5916,-2,58.24998,NULL),(5917,1,6027.99872,NULL),(5917,-1,465.99987,NULL),(5917,-2,58.24998,NULL),(5918,1,10000.00000,NULL),(5918,-1,919.54023,NULL),(5918,-2,114.94253,NULL),(5919,1,14500.00000,NULL),(5919,-1,1333.33333,NULL),(5919,-2,166.66667,NULL),(5920,1,7000.00000,NULL),(5920,-1,536.74121,NULL),(5920,-2,67.09265,NULL),(5921,1,5500.00000,NULL),(5921,-1,505.74713,NULL),(5921,-2,63.21839,NULL),(5923,1,6613.75000,NULL),(5923,-1,608.16092,NULL),(5923,-2,76.02011,NULL),(5924,1,5500.00000,NULL),(5924,-1,505.74713,NULL),(5924,-2,63.21839,NULL),(5925,1,6086.52500,NULL),(5925,-1,559.68046,NULL),(5925,-2,69.96006,NULL),(5926,1,12613.75000,NULL),(5926,-1,1159.88506,NULL),(5926,-2,144.98563,NULL),(5928,1,7113.75000,NULL),(5928,-1,654.13793,NULL),(5928,-2,81.76724,NULL),(5929,1,14197.00500,NULL),(5929,-1,1305.47172,NULL),(5929,-2,163.18397,NULL),(5930,1,6000.00000,NULL),(5930,-1,551.72414,NULL),(5930,-2,68.96552,NULL),(5931,1,17447.08500,NULL),(5931,-1,1604.32966,NULL),(5931,-2,200.54121,NULL),(5932,1,6007.62500,NULL),(5932,-1,552.42529,NULL),(5932,-2,69.05316,NULL),(5933,1,6195.89000,NULL),(5933,-1,569.73701,NULL),(5933,-2,71.21713,NULL),(5934,1,5982.96500,NULL),(5934,-1,550.15770,NULL),(5934,-2,68.76971,NULL),(5935,1,6000.00000,NULL),(5935,-1,551.72414,NULL),(5935,-2,68.96552,NULL),(5936,1,8688.75000,NULL),(5936,-1,798.96552,NULL),(5936,-2,99.87069,NULL),(5937,1,7500.00000,NULL),(5937,-1,689.65517,NULL),(5937,-2,86.20690,NULL),(5938,1,5500.00000,NULL),(5938,-1,505.74713,NULL),(5938,-2,63.21839,NULL),(5939,1,9215.50000,NULL),(5939,-1,847.40230,NULL),(5939,-2,105.92529,NULL),(5940,1,7875.00000,NULL),(5940,-1,724.13793,NULL),(5940,-2,90.51724,NULL),(5941,1,6027.76869,NULL),(5941,-1,465.99987,NULL),(5941,-2,58.24998,NULL),(5942,1,25000.00000,NULL),(5942,-1,2298.85057,NULL),(5942,-2,287.35632,NULL),(5943,1,5500.00000,NULL),(5943,-1,505.74713,NULL),(5943,-2,63.21839,NULL),(5944,1,5500.00000,NULL),(5944,-1,505.74713,NULL),(5944,-2,63.21839,NULL),(5945,1,6000.12500,NULL),(5945,-1,551.73563,NULL),(5945,-2,68.96695,NULL),(5946,1,6750.00000,NULL),(5946,-1,620.68966,NULL),(5946,-2,77.58621,NULL),(5947,1,5750.00000,NULL),(5947,-1,528.73563,NULL),(5947,-2,66.09195,NULL),(5948,1,6750.00000,NULL),(5948,-1,620.68966,NULL),(5948,-2,77.58621,NULL),(5949,1,4505.74713,NULL),(5949,-1,643.67816,NULL),(5949,-2,80.45977,NULL),(5950,1,16500.00000,NULL),(5950,-1,1517.24138,NULL),(5950,-2,189.65517,NULL),(5951,1,6950.00000,NULL),(5951,-1,639.08046,NULL),(5951,-2,79.88506,NULL),(5952,1,6323.59500,NULL),(5952,-1,581.48000,NULL),(5952,-2,72.68500,NULL),(5953,1,10613.75000,NULL),(5953,-1,975.97701,NULL),(5953,-2,121.99713,NULL),(5954,1,7400.00000,NULL),(5954,-1,680.45977,NULL),(5954,-2,85.05747,NULL),(5955,1,11621.00000,NULL),(5955,-1,1068.59770,NULL),(5955,-2,133.57471,NULL),(5956,1,9601.47000,NULL),(5956,-1,882.89379,NULL),(5956,-2,110.36172,NULL),(5957,1,10000.00000,NULL),(5957,-1,919.54023,NULL),(5957,-2,114.94253,NULL),(5958,1,5500.00000,NULL),(5958,-1,505.74713,NULL),(5958,-2,63.21839,NULL),(5959,1,15000.00000,NULL),(5959,-1,1379.31034,NULL),(5959,-2,172.41379,NULL),(5960,1,5449.50000,NULL),(5960,-1,501.10345,NULL),(5960,-2,62.63793,NULL),(5961,1,9500.00000,NULL),(5961,-1,873.56322,NULL),(5961,-2,109.19540,NULL),(5964,1,5068.96552,NULL),(5964,-1,563.21839,NULL),(5964,-2,70.40230,NULL),(5965,1,6013.95000,NULL),(5965,-1,553.00690,NULL),(5965,-2,69.12586,NULL),(5966,1,6007.62500,NULL),(5966,-1,552.42529,NULL),(5966,-2,69.05316,NULL),(5967,1,6077.41500,NULL),(5967,-1,465.99987,NULL),(5967,-2,58.24998,NULL),(5968,1,5500.00000,NULL),(5968,-1,505.74713,NULL),(5968,-2,63.21839,NULL),(5969,1,6077.41500,NULL),(5969,-1,465.99987,NULL),(5969,-2,58.24998,NULL),(5970,1,6070.00000,NULL),(5970,-1,558.16092,NULL),(5970,-2,69.77011,NULL),(5971,1,6000.00000,NULL),(5971,-1,551.72414,NULL),(5971,-2,68.96552,NULL),(5972,1,5500.00000,NULL),(5972,-1,505.74713,NULL),(5972,-2,63.21839,NULL),(5973,1,5500.00000,NULL),(5973,-1,505.74713,NULL),(5973,-2,63.21839,NULL),(5974,1,6615.25000,NULL),(5974,-1,608.29885,NULL),(5974,-2,76.03736,NULL),(5975,1,12847.08500,NULL),(5975,-1,1181.34115,NULL),(5975,-2,147.66764,NULL),(5976,1,8689.87500,NULL),(5976,-1,799.06897,NULL),(5976,-2,99.88362,NULL),(5977,1,6138.00000,NULL),(5977,-1,564.41379,NULL),(5977,-2,70.55172,NULL),(5978,1,5500.00000,NULL),(5978,-1,505.74713,NULL),(5978,-2,63.21839,NULL),(5979,1,6000.00000,NULL),(5979,-1,551.72414,NULL),(5979,-2,68.96552,NULL),(5981,1,12500.00000,NULL),(5981,-1,1149.42529,NULL),(5981,-2,143.67816,NULL),(5982,1,5500.00000,NULL),(5982,-1,505.74713,NULL),(5982,-2,63.21839,NULL),(5983,1,5500.00000,NULL),(5983,-1,505.74713,NULL),(5983,-2,63.21839,NULL),(5984,1,6187.50000,NULL),(5984,-1,568.96552,NULL),(5984,-2,71.12069,NULL),(5985,1,5067.75000,NULL),(5985,-1,466.00000,NULL),(5985,-2,58.25000,NULL),(5986,1,12500.00000,NULL),(5986,-1,1149.42529,NULL),(5986,-2,143.67816,NULL),(5987,1,6000.00000,NULL),(5987,-1,551.72414,NULL),(5987,-2,68.96552,NULL),(5988,1,11587.55000,NULL),(5988,-1,1065.52184,NULL),(5988,-2,133.19023,NULL),(5989,1,5500.00000,NULL),(5989,-1,505.74713,NULL),(5989,-2,63.21839,NULL),(5990,1,11817.01000,NULL),(5990,-1,1086.62161,NULL),(5990,-2,135.82770,NULL),(5991,1,13400.95500,NULL),(5991,-1,1232.27172,NULL),(5991,-2,154.03397,NULL),(5992,1,6696.13500,NULL),(5992,-1,615.73655,NULL),(5992,-2,76.96707,NULL),(5993,1,6388.75000,NULL),(5993,-1,587.47126,NULL),(5993,-2,73.43391,NULL),(5994,1,6543.39500,NULL),(5994,-1,601.69149,NULL),(5994,-2,75.21144,NULL),(5996,1,9050.00000,NULL),(5996,-1,832.18391,NULL),(5996,-2,104.02299,NULL),(5997,1,6153.25000,NULL),(5997,-1,565.81609,NULL),(5997,-2,70.72701,NULL),(5998,1,5500.00000,NULL),(5998,-1,505.74713,NULL),(5998,-2,63.21839,NULL),(5999,1,6500.25000,NULL),(5999,-1,597.72414,NULL),(5999,-2,74.71552,NULL),(6000,1,6077.41500,NULL),(6000,-1,465.99987,NULL),(6000,-2,58.24998,NULL),(6001,1,6077.41500,NULL),(6001,-1,465.99987,NULL),(6001,-2,58.24998,NULL),(6002,1,10000.00000,NULL),(6002,-1,919.54023,NULL),(6002,-2,114.94253,NULL),(6003,1,14500.00000,NULL),(6003,-1,1333.33333,NULL),(6003,-2,166.66667,NULL),(6004,1,7000.00000,NULL),(6004,-1,536.74121,NULL),(6004,-2,67.09265,NULL),(6005,1,5500.00000,NULL),(6005,-1,505.74713,NULL),(6005,-2,63.21839,NULL),(6006,1,6613.75000,NULL),(6006,-1,608.16092,NULL),(6006,-2,76.02011,NULL),(6007,1,5500.00000,NULL),(6007,-1,505.74713,NULL),(6007,-2,63.21839,NULL),(6008,1,6086.52500,NULL),(6008,-1,559.68046,NULL),(6008,-2,69.96006,NULL),(6009,1,12613.75000,NULL),(6009,-1,1159.88506,NULL),(6009,-2,144.98563,NULL),(6010,1,6007.62500,NULL),(6010,-1,552.42529,NULL),(6010,-2,69.05316,NULL),(6011,1,7113.75000,NULL),(6011,-1,654.13793,NULL),(6011,-2,81.76724,NULL),(6012,1,14197.00500,NULL),(6012,-1,1305.47172,NULL),(6012,-2,163.18397,NULL),(6013,1,6000.00000,NULL),(6013,-1,551.72414,NULL),(6013,-2,68.96552,NULL),(6014,1,17447.08500,NULL),(6014,-1,1604.32966,NULL),(6014,-2,200.54121,NULL),(6015,1,6007.62500,NULL),(6015,-1,552.42529,NULL),(6015,-2,69.05316,NULL),(6016,1,6195.89000,NULL),(6016,-1,569.73701,NULL),(6016,-2,71.21713,NULL),(6017,1,5982.96500,NULL),(6017,-1,550.15770,NULL),(6017,-2,68.76971,NULL),(6018,1,6000.00000,NULL),(6018,-1,551.72414,NULL),(6018,-2,68.96552,NULL),(6019,1,8688.75000,NULL),(6019,-1,798.96552,NULL),(6019,-2,99.87069,NULL),(6020,1,7500.00000,NULL),(6020,-1,689.65517,NULL),(6020,-2,86.20690,NULL),(6022,1,9215.50000,NULL),(6022,-1,847.40230,NULL),(6022,-2,105.92529,NULL),(6023,1,7875.00000,NULL),(6023,-1,724.13793,NULL),(6023,-2,90.51724,NULL),(6024,1,6077.41500,NULL),(6024,-1,465.99987,NULL),(6024,-2,58.24998,NULL),(6025,1,25000.00000,NULL),(6025,-1,2298.85057,NULL),(6025,-2,287.35632,NULL),(6026,1,5500.00000,NULL),(6026,-1,505.74713,NULL),(6026,-2,63.21839,NULL),(6027,1,5500.00000,NULL),(6027,-1,505.74713,NULL),(6027,-2,63.21839,NULL),(6028,1,6000.12500,NULL),(6028,-1,551.73563,NULL),(6028,-2,68.96695,NULL),(6029,1,6750.00000,NULL),(6029,-1,620.68966,NULL),(6029,-2,77.58621,NULL),(6030,1,5750.00000,NULL),(6030,-1,528.73563,NULL),(6030,-2,66.09195,NULL),(6031,1,6750.00000,NULL),(6031,-1,620.68966,NULL),(6031,-2,77.58621,NULL),(6032,1,7000.00000,NULL),(6032,-1,643.67816,NULL),(6032,-2,80.45977,NULL),(6033,1,16500.00000,NULL),(6033,-1,1517.24138,NULL),(6033,-2,189.65517,NULL),(6034,1,6950.00000,NULL),(6034,-1,639.08046,NULL),(6034,-2,79.88506,NULL),(6035,1,6323.59500,NULL),(6035,-1,581.48000,NULL),(6035,-2,72.68500,NULL),(6036,1,10613.75000,NULL),(6036,-1,975.97701,NULL),(6036,-2,121.99713,NULL),(6037,1,7400.00000,NULL),(6037,-1,680.45977,NULL),(6037,-2,85.05747,NULL),(6038,1,11621.00000,NULL),(6038,-1,1068.59770,NULL),(6038,-2,133.57471,NULL),(6039,1,9601.47000,NULL),(6039,-1,882.89379,NULL),(6039,-2,110.36172,NULL),(6040,1,10000.00000,NULL),(6040,-1,919.54023,NULL),(6040,-2,114.94253,NULL),(6041,1,5500.00000,NULL),(6041,-1,505.74713,NULL),(6041,-2,63.21839,NULL),(6042,1,15000.00000,NULL),(6042,-1,1379.31034,NULL),(6042,-2,172.41379,NULL),(6043,1,5449.50000,NULL),(6043,-1,501.10345,NULL),(6043,-2,62.63793,NULL),(6044,1,9500.00000,NULL),(6044,-1,873.56322,NULL),(6044,-2,109.19540,NULL),(6048,1,19060.00000,NULL),(6048,-1,1752.64368,NULL),(6048,-2,219.08046,NULL),(6049,1,8000.00000,NULL),(6049,-1,735.63218,NULL),(6049,-2,91.95402,NULL),(6050,1,5057.47126,NULL),(6050,-1,505.74713,NULL),(6050,-2,63.21839,NULL),(6051,1,6077.41500,NULL),(6051,-1,465.99987,NULL),(6051,-2,58.24998,NULL),(6052,1,5500.00000,NULL),(6052,-1,505.74713,NULL),(6052,-2,63.21839,NULL),(6053,1,6077.41500,NULL),(6053,-1,465.99987,NULL),(6053,-2,58.24998,NULL),(6054,1,6070.00000,NULL),(6054,-1,558.16092,NULL),(6054,-2,69.77011,NULL),(6055,1,6000.00000,NULL),(6055,-1,551.72414,NULL),(6055,-2,68.96552,NULL),(6056,1,5500.00000,NULL),(6056,-1,505.74713,NULL),(6056,-2,63.21839,NULL),(6057,1,5500.00000,NULL),(6057,-1,505.74713,NULL),(6057,-2,63.21839,NULL),(6058,1,6615.25000,NULL),(6058,-1,608.29885,NULL),(6058,-2,76.03736,NULL),(6059,1,12847.08500,NULL),(6059,-1,1181.34115,NULL),(6059,-2,147.66764,NULL),(6060,1,8689.87500,NULL),(6060,-1,799.06897,NULL),(6060,-2,99.88362,NULL),(6061,1,6138.00000,NULL),(6061,-1,564.41379,NULL),(6061,-2,70.55172,NULL),(6062,1,5500.00000,NULL),(6062,-1,505.74713,NULL),(6062,-2,63.21839,NULL),(6063,1,6000.00000,NULL),(6063,-1,551.72414,NULL),(6063,-2,68.96552,NULL),(6064,1,12500.00000,NULL),(6064,-1,1149.42529,NULL),(6064,-2,143.67816,NULL),(6065,1,5500.00000,NULL),(6065,-1,505.74713,NULL),(6065,-2,63.21839,NULL),(6068,1,5067.75000,NULL),(6068,-1,466.00000,NULL),(6068,-2,58.25000,NULL),(6069,1,12500.00000,NULL),(6069,-1,1149.42529,NULL),(6069,-2,143.67816,NULL),(6070,1,6000.00000,NULL),(6070,-1,551.72414,NULL),(6070,-2,68.96552,NULL),(6071,1,11587.55000,NULL),(6071,-1,1065.52184,NULL),(6071,-2,133.19023,NULL),(6072,1,11817.01000,NULL),(6072,-1,1086.62161,NULL),(6072,-2,135.82770,NULL),(6073,1,5500.00000,NULL),(6073,-1,505.74713,NULL),(6073,-2,63.21839,NULL),(6074,1,6696.13500,NULL),(6074,-1,615.73655,NULL),(6074,-2,76.96707,NULL),(6075,1,13400.95500,NULL),(6075,-1,1232.27172,NULL),(6075,-2,154.03397,NULL),(6076,1,6388.75000,NULL),(6076,-1,587.47126,NULL),(6076,-2,73.43391,NULL),(6077,1,6543.39500,NULL),(6077,-1,601.69149,NULL),(6077,-2,75.21144,NULL),(6078,1,9050.00000,NULL),(6078,-1,832.18391,NULL),(6078,-2,104.02299,NULL),(6079,1,6153.25000,NULL),(6079,-1,565.81609,NULL),(6079,-2,70.72701,NULL),(6080,1,5500.00000,NULL),(6080,-1,505.74713,NULL),(6080,-2,63.21839,NULL),(6081,1,6500.25000,NULL),(6081,-1,597.72414,NULL),(6081,-2,74.71552,NULL),(6082,1,6077.41500,NULL),(6082,-1,465.99987,NULL),(6082,-2,58.24998,NULL),(6083,1,6077.41500,NULL),(6083,-1,465.99987,NULL),(6083,-2,58.24998,NULL),(6084,1,14500.00000,NULL),(6084,-1,1333.33333,NULL),(6084,-2,166.66667,NULL),(6085,1,10000.00000,NULL),(6085,-1,919.54023,NULL),(6085,-2,114.94253,NULL),(6086,1,7000.00000,NULL),(6086,-1,536.74121,NULL),(6086,-2,67.09265,NULL),(6087,1,5500.00000,NULL),(6087,-1,505.74713,NULL),(6087,-2,63.21839,NULL),(6088,1,6613.75000,NULL),(6088,-1,608.16092,NULL),(6088,-2,76.02011,NULL),(6089,1,5500.00000,NULL),(6089,-1,505.74713,NULL),(6089,-2,63.21839,NULL),(6090,1,6086.52500,NULL),(6090,-1,559.68046,NULL),(6090,-2,69.96006,NULL),(6091,1,12613.75000,NULL),(6091,-1,1159.88506,NULL),(6091,-2,144.98563,NULL),(6092,1,6007.62500,NULL),(6092,-1,552.42529,NULL),(6092,-2,69.05316,NULL),(6093,1,7113.75000,NULL),(6093,-1,654.13793,NULL),(6093,-2,81.76724,NULL),(6094,1,14197.00500,NULL),(6094,-1,1305.47172,NULL),(6094,-2,163.18397,NULL),(6095,1,6000.00000,NULL),(6095,-1,551.72414,NULL),(6095,-2,68.96552,NULL),(6096,1,17447.08500,NULL),(6096,-1,1604.32966,NULL),(6096,-2,200.54121,NULL),(6097,1,6007.62500,NULL),(6097,-1,552.42529,NULL),(6097,-2,69.05316,NULL),(6098,1,6195.89000,NULL),(6098,-1,569.73701,NULL),(6098,-2,71.21713,NULL),(6099,1,5982.96500,NULL),(6099,-1,550.15770,NULL),(6099,-2,68.76971,NULL),(6100,1,6000.00000,NULL),(6100,-1,551.72414,NULL),(6100,-2,68.96552,NULL),(6101,1,8688.75000,NULL),(6101,-1,798.96552,NULL),(6101,-2,99.87069,NULL),(6102,1,7500.00000,NULL),(6102,-1,689.65517,NULL),(6102,-2,86.20690,NULL),(6103,1,9215.50000,NULL),(6103,-1,847.40230,NULL),(6103,-2,105.92529,NULL),(6104,1,7875.00000,NULL),(6104,-1,724.13793,NULL),(6104,-2,90.51724,NULL),(6105,1,6077.41500,NULL),(6105,-1,465.99987,NULL),(6105,-2,58.24998,NULL),(6106,1,25000.00000,NULL),(6106,-1,2298.85057,NULL),(6106,-2,287.35632,NULL),(6107,1,6022.98851,NULL),(6107,-1,551.72414,NULL),(6107,-2,68.96552,NULL),(6108,1,5500.00000,NULL),(6108,-1,505.74713,NULL),(6108,-2,63.21839,NULL),(6109,1,6000.12500,NULL),(6109,-1,551.73563,NULL),(6109,-2,68.96695,NULL),(6110,1,6750.00000,NULL),(6110,-1,620.68966,NULL),(6110,-2,77.58621,NULL),(6111,1,5750.00000,NULL),(6111,-1,528.73563,NULL),(6111,-2,66.09195,NULL),(6112,1,6750.00000,NULL),(6112,-1,620.68966,NULL),(6112,-2,77.58621,NULL),(6113,1,7000.00000,NULL),(6113,-1,643.67816,NULL),(6113,-2,80.45977,NULL),(6114,1,16500.00000,NULL),(6114,-1,1517.24138,NULL),(6114,-2,189.65517,NULL),(6115,1,6950.00000,NULL),(6115,-1,639.08046,NULL),(6115,-2,79.88506,NULL),(6116,1,6323.59500,NULL),(6116,-1,581.48000,NULL),(6116,-2,72.68500,NULL),(6117,1,10613.75000,NULL),(6117,-1,975.97701,NULL),(6117,-2,121.99713,NULL),(6118,1,7400.00000,NULL),(6118,-1,680.45977,NULL),(6118,-2,85.05747,NULL),(6119,1,11621.00000,NULL),(6119,-1,1068.59770,NULL),(6119,-2,133.57471,NULL),(6120,1,9601.47000,NULL),(6120,-1,882.89379,NULL),(6120,-2,110.36172,NULL),(6121,1,10000.00000,NULL),(6121,-1,919.54023,NULL),(6121,-2,114.94253,NULL),(6122,1,5500.00000,NULL),(6122,-1,505.74713,NULL),(6122,-2,63.21839,NULL),(6123,1,15000.00000,NULL),(6123,-1,1379.31034,NULL),(6123,-2,172.41379,NULL),(6124,1,5449.50000,NULL),(6124,-1,501.10345,NULL),(6124,-2,62.63793,NULL),(6125,1,9500.00000,NULL),(6125,-1,873.56322,NULL),(6125,-2,109.19540,NULL),(6130,1,25287.35632,NULL),(6130,-1,2528.73563,NULL),(6130,-2,316.09195,NULL),(6133,1,3540.22989,NULL),(6133,-1,505.74713,NULL),(6133,-2,63.21839,NULL),(6134,1,3413.79310,NULL),(6134,-1,568.96552,NULL),(6134,-2,71.12069,NULL),(6135,1,5057.47126,NULL),(6135,-1,505.74713,NULL),(6135,-2,63.21839,NULL),(6210,1,6077.41500,NULL),(6210,-1,465.99987,NULL),(6210,-2,58.24998,NULL),(6211,1,5500.00000,NULL),(6211,-1,505.74713,NULL),(6211,-2,63.21839,NULL),(6212,1,6077.41500,NULL),(6212,-1,465.99987,NULL),(6212,-2,58.24998,NULL),(6213,1,6070.00000,NULL),(6213,-1,558.16092,NULL),(6213,-2,69.77011,NULL),(6214,1,6000.00000,NULL),(6214,-1,551.72414,NULL),(6214,-2,68.96552,NULL),(6215,1,5500.00000,NULL),(6215,-1,505.74713,NULL),(6215,-2,63.21839,NULL),(6216,1,5500.00000,NULL),(6216,-1,505.74713,NULL),(6216,-2,63.21839,NULL),(6217,1,6615.25000,NULL),(6217,-1,608.29885,NULL),(6217,-2,76.03736,NULL),(6218,1,12847.08500,NULL),(6218,-1,1181.34115,NULL),(6218,-2,147.66764,NULL),(6219,1,8689.87500,NULL),(6219,-1,799.06897,NULL),(6219,-2,99.88362,NULL),(6220,1,6138.00000,NULL),(6220,-1,564.41379,NULL),(6220,-2,70.55172,NULL),(6221,1,5500.00000,NULL),(6221,-1,505.74713,NULL),(6221,-2,63.21839,NULL),(6222,1,6000.00000,NULL),(6222,-1,551.72414,NULL),(6222,-2,68.96552,NULL),(6223,1,12500.00000,NULL),(6223,-1,1149.42529,NULL),(6223,-2,143.67816,NULL),(6224,1,5500.00000,NULL),(6224,-1,505.74713,NULL),(6224,-2,63.21839,NULL),(6225,1,5067.75000,NULL),(6225,-1,466.00000,NULL),(6225,-2,58.25000,NULL),(6226,1,12500.00000,NULL),(6226,-1,1149.42529,NULL),(6226,-2,143.67816,NULL),(6227,1,6000.00000,NULL),(6227,-1,551.72414,NULL),(6227,-2,68.96552,NULL),(6228,1,11587.55000,NULL),(6228,-1,1065.52184,NULL),(6228,-2,133.19023,NULL),(6229,1,11817.01000,NULL),(6229,-1,1086.62161,NULL),(6229,-2,135.82770,NULL),(6230,1,5500.00000,NULL),(6230,-1,505.74713,NULL),(6230,-2,63.21839,NULL),(6231,1,13400.95500,NULL),(6231,-1,1232.27172,NULL),(6231,-2,154.03397,NULL),(6232,1,6696.13500,NULL),(6232,-1,615.73655,NULL),(6232,-2,76.96707,NULL),(6233,1,6388.75000,NULL),(6233,-1,587.47126,NULL),(6233,-2,73.43391,NULL),(6234,1,6543.39500,NULL),(6234,-1,601.69149,NULL),(6234,-2,75.21144,NULL),(6235,1,6153.25000,NULL),(6235,-1,565.81609,NULL),(6235,-2,70.72701,NULL),(6236,1,9050.00000,NULL),(6236,-1,832.18391,NULL),(6236,-2,104.02299,NULL),(6237,1,5500.00000,NULL),(6237,-1,505.74713,NULL),(6237,-2,63.21839,NULL),(6238,1,6500.25000,NULL),(6238,-1,597.72414,NULL),(6238,-2,74.71552,NULL),(6239,1,27500.00000,NULL),(6239,-1,2528.73563,NULL),(6239,-2,316.09195,NULL),(6240,1,6077.41500,NULL),(6240,-1,465.99987,NULL),(6240,-2,58.24998,NULL),(6241,1,6077.41500,NULL),(6241,-1,465.99987,NULL),(6241,-2,58.24998,NULL),(6242,1,14500.00000,NULL),(6242,-1,1333.33333,NULL),(6242,-2,166.66667,NULL),(6243,1,10000.00000,NULL),(6243,-1,919.54023,NULL),(6243,-2,114.94253,NULL),(6244,1,7000.00000,NULL),(6244,-1,536.74121,NULL),(6244,-2,67.09265,NULL),(6245,1,5500.00000,NULL),(6245,-1,505.74713,NULL),(6245,-2,63.21839,NULL),(6246,1,6613.75000,NULL),(6246,-1,608.16092,NULL),(6246,-2,76.02011,NULL),(6247,1,5500.00000,NULL),(6247,-1,505.74713,NULL),(6247,-2,63.21839,NULL),(6248,1,6086.52500,NULL),(6248,-1,559.68046,NULL),(6248,-2,69.96006,NULL),(6249,1,12613.75000,NULL),(6249,-1,1159.88506,NULL),(6249,-2,144.98563,NULL),(6250,1,6007.62500,NULL),(6250,-1,552.42529,NULL),(6250,-2,69.05316,NULL),(6251,1,7113.75000,NULL),(6251,-1,654.13793,NULL),(6251,-2,81.76724,NULL),(6252,1,14197.00500,NULL),(6252,-1,1305.47172,NULL),(6252,-2,163.18397,NULL),(6253,1,6000.00000,NULL),(6253,-1,551.72414,NULL),(6253,-2,68.96552,NULL),(6254,1,17447.08500,NULL),(6254,-1,1604.32966,NULL),(6254,-2,200.54121,NULL),(6255,1,6007.62500,NULL),(6255,-1,552.42529,NULL),(6255,-2,69.05316,NULL),(6256,1,6195.89000,NULL),(6256,-1,569.73701,NULL),(6256,-2,71.21713,NULL),(6257,1,5982.96500,NULL),(6257,-1,550.15770,NULL),(6257,-2,68.76971,NULL),(6258,1,6000.00000,NULL),(6258,-1,551.72414,NULL),(6258,-2,68.96552,NULL),(6259,1,8688.75000,NULL),(6259,-1,798.96552,NULL),(6259,-2,99.87069,NULL),(6260,1,7500.00000,NULL),(6260,-1,689.65517,NULL),(6260,-2,86.20690,NULL),(6261,1,9215.50000,NULL),(6261,-1,847.40230,NULL),(6261,-2,105.92529,NULL),(6262,1,7875.00000,NULL),(6262,-1,724.13793,NULL),(6262,-2,90.51724,NULL),(6263,1,6077.41500,NULL),(6263,-1,465.99987,NULL),(6263,-2,58.24998,NULL),(6264,1,25000.00000,NULL),(6264,-1,2298.85057,NULL),(6264,-2,287.35632,NULL),(6265,1,6000.00000,NULL),(6265,-1,551.72414,NULL),(6265,-2,68.96552,NULL),(6266,1,5500.00000,NULL),(6266,-1,505.74713,NULL),(6266,-2,63.21839,NULL),(6267,1,6000.12500,NULL),(6267,-1,551.73563,NULL),(6267,-2,68.96695,NULL),(6268,1,6750.00000,NULL),(6268,-1,620.68966,NULL),(6268,-2,77.58621,NULL),(6269,1,5750.00000,NULL),(6269,-1,528.73563,NULL),(6269,-2,66.09195,NULL),(6270,1,6750.00000,NULL),(6270,-1,620.68966,NULL),(6270,-2,77.58621,NULL),(6271,1,7000.00000,NULL),(6271,-1,643.67816,NULL),(6271,-2,80.45977,NULL),(6272,1,16500.00000,NULL),(6272,-1,1517.24138,NULL),(6272,-2,189.65517,NULL),(6273,1,6950.00000,NULL),(6273,-1,639.08046,NULL),(6273,-2,79.88506,NULL),(6274,1,6323.59500,NULL),(6274,-1,581.48000,NULL),(6274,-2,72.68500,NULL),(6275,1,10613.75000,NULL),(6275,-1,975.97701,NULL),(6275,-2,121.99713,NULL),(6276,1,7400.00000,NULL),(6276,-1,680.45977,NULL),(6276,-2,85.05747,NULL),(6277,1,11621.00000,NULL),(6277,-1,1068.59770,NULL),(6277,-2,133.57471,NULL),(6278,1,9601.47000,NULL),(6278,-1,882.89379,NULL),(6278,-2,110.36172,NULL),(6279,1,10000.00000,NULL),(6279,-1,919.54023,NULL),(6279,-2,114.94253,NULL),(6280,1,5500.00000,NULL),(6280,-1,505.74713,NULL),(6280,-2,63.21839,NULL),(6281,1,15000.00000,NULL),(6281,-1,1379.31034,NULL),(6281,-2,172.41379,NULL),(6282,1,5449.50000,NULL),(6282,-1,501.10345,NULL),(6282,-2,62.63793,NULL),(6283,1,9500.00000,NULL),(6283,-1,873.56322,NULL),(6283,-2,109.19540,NULL);
/*!40000 ALTER TABLE `z_formula_temp` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `z_temp`
--

DROP TABLE IF EXISTS `z_temp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `z_temp` (
  `psa_id` int(10) unsigned NOT NULL,
  `emp_id` int(10) unsigned NOT NULL,
  `amt` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `z_temp`
--

LOCK TABLES `z_temp` WRITE;
/*!40000 ALTER TABLE `z_temp` DISABLE KEYS */;
/*!40000 ALTER TABLE `z_temp` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'orangeipay_ssi'
--
/*!50003 DROP FUNCTION IF EXISTS `checkIfEntryIsNotZero` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 FUNCTION `checkIfEntryIsNotZero`(m_period_start INT, m_period_end INT, y_period_start INT, y_period_end INT, emp_id INT, psa_id INT) RETURNS tinyint(1)
BEGIN DECLARE val tinyint;
 IF((select sum(ppe_amount) as total from payroll_paystub_entry a inner join payroll_pay_stub b on (b.paystub_id=a.paystub_id) inner join payroll_paystub_report c on (c.paystub_id=b.paystub_id) inner join payroll_pay_period d on (d.payperiod_id=c.payperiod_id) where a.psa_id=psa_id  and c.emp_id=emp_id and (d.payperiod_period >= m_period_start and d.payperiod_period <= m_period_end) and (d.payperiod_period_year >= y_period_start and d.payperiod_period_year <= y_period_end)) > 0) THEN SET val = TRUE; ELSE SET val = FALSE; END IF; RETURN val;
 END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `checkIfNotZero` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 FUNCTION `checkIfNotZero`(m_period_start INT, m_period_end INT, y_period_start INT, y_period_end INT, emp_id INT, param_id INT, tble varchar(30)) RETURNS tinyint(1)
BEGIN
 DECLARE val tinyint;IF tble = "TA" THEN	 IF ((select sum(emp_tarec_amtperrate) as total from ta_emp_rec a	 inner join ta_tbl b on (b.tatbl_id=a.tatbl_id)	 inner join payroll_pay_period c on (c.payperiod_id=a.payperiod_id)	 where a.tatbl_id=param_id	 and a.emp_id=emp_id  	 and (c.payperiod_period >= m_period_start and c.payperiod_period <= m_period_end)	 and (c.payperiod_period_year >= y_period_start and c.payperiod_period_year <= y_period_end)) > 0) THEN	 SET val = TRUE;	 ELSE	 SET val = FALSE;	 END IF;ELSEIF tble = "ENTRY" THEN	IF ((select sum(ppe_amount) as total from payroll_paystub_entry a	inner join payroll_pay_stub b on (b.paystub_id=a.paystub_id)	inner join payroll_paystub_report c on (c.paystub_id=b.paystub_id)	inner join payroll_pay_period d on (d.payperiod_id=c.payperiod_id)	where a.psa_id=param_id 	and c.emp_id=emp_id 	and (d.payperiod_period >= m_period_start and d.payperiod_period <= m_period_end)	and (d.payperiod_period_year >= y_period_start and d.payperiod_period_year <= y_period_end)) > 0) THEN	SET val = TRUE;	ELSE	SET val = FALSE;	END IF;END IF; RETURN val;
 END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `checkIfPayperiodExists` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 FUNCTION `checkIfPayperiodExists`(p_period INT, p_year INT) RETURNS tinyint(1)
BEGIN DECLARE val tinyint;
 IF ((select count(1) from payroll_pay_period where payperiod_period=p_period and payperiod_period_year=p_year) > 0) THEN SET val = true; ELSE SET val = false; END IF; RETURN val;
 END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `checkIfPSAExists` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 FUNCTION `checkIfPSAExists`(p_psa_id int, p_emp_id int) RETURNS tinyint(1)
BEGIN DECLARE val BOOL; if(exists(select 1 from z_temp where psa_id=p_psa_id and emp_id=p_emp_id) = 1) then  set val = true; else set val = false; end if; return val;
 END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `checkNonZeroInAmendment` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 FUNCTION `checkNonZeroInAmendment`(m_period_start int, m_period_end int, y_period_start varchar(10), y_period_end varchar(10), m_psa_id INT) RETURNS tinyint(1)
BEGIN
 DECLARE val BOOL; IF(((select sum(a.amendemp_amount) from payroll_ps_amendemp a inner join payroll_ps_amendment b on (b.psamend_id=a.psamend_id) inner join payroll_pay_stub c on (c.paystub_id=a.paystub_id) inner join payroll_pay_period d on (d.payperiod_id=c.payperiod_id) where b.psa_id=m_psa_id and (d.payperiod_period >= m_period_start and d.payperiod_period <= m_period_end) and (d.payperiod_period_year >= y_period_start and d.payperiod_period_year <= y_period_end))> 0)) THEN set val = true; ELSE  set val = false; END IF; return val;
 END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `checkNonZeroInEntry` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 FUNCTION `checkNonZeroInEntry`(m_period_start int, m_period_end int, y_period_start varchar(10), y_period_end varchar(10), m_psa_id INT) RETURNS tinyint(1)
BEGIN
 DECLARE val BOOL; IF(((select sum(ppe_amount) as total from payroll_paystub_entry a inner join payroll_pay_stub b on (b.paystub_id=a.paystub_id) inner join payroll_paystub_report c on (c.paystub_id=b.paystub_id) inner join payroll_pay_period d on (d.payperiod_id=c.payperiod_id) where a.psa_id=m_psa_id and (d.payperiod_period >= m_period_start and d.payperiod_period <= m_period_end) and (d.payperiod_period_year >= y_period_start and d.payperiod_period_year <= y_period_end)) > 0)) THEN set val = true; ELSE  set val = false; END IF; return val;
 END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `checkNonZeroInLoan` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 FUNCTION `checkNonZeroInLoan`(m_period_start int, m_period_end int, y_period_start varchar(10), y_period_end varchar(10), m_psa_id INT) RETURNS tinyint(1)
BEGIN
 DECLARE val BOOL; 
 IF(((select sum(a.loansum_payment) 
 from loan_detail_sum a 
 join loan_info b on (b.loan_id=a.loan_id) 
 join payroll_pay_stub c on (c.paystub_id=a.paystub_id) 
 join payroll_pay_period d on (d.payperiod_id=c.payperiod_id) 
 where b.psa_id=m_psa_id and (d.payperiod_period >= m_period_start and d.payperiod_period <= m_period_end) 
 and (d.payperiod_period_year >= y_period_start and d.payperiod_period_year <= y_period_end)) > 0)) 
 THEN 
	set val = true; 
 ELSE 
	set val = false; 
 END IF; 
 return val;
 END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `checkPsaType` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 FUNCTION `checkPsaType`(p_id INT) RETURNS tinyint(1)
BEGIN
 DECLARE val BOOL; IF((SELECT psa_type FROM payroll_ps_account WHERE psa_id=p_id)=1) THEN SET val = true; ELSE SET val = false; END IF; RETURN val;
 END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `check_column` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`%`*/ /*!50003 FUNCTION `check_column`(tble varchar(30), col varchar(30)) RETURNS tinyint(1)
BEGIN
 DECLARE val tinyint(1);
 IF NOT EXISTS ( SELECT * FROM information_schema.columns WHERE table_name = tble AND column_name = col AND table_schema = DATABASE() ) THEN
 SET val = TRUE;
 ELSE
 SET val = FALSE;
 END IF;
 RETURN val;
 END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `fetchDept` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 FUNCTION `fetchDept`(p_empidnum varchar(20)) RETURNS varchar(300) CHARSET latin1
BEGIN
 DECLARE val varchar(300); select CONCAT(a.ud_name," - ",a.ud_desc) INTO val from app_userdept a inner join emp_masterfile b on (b.ud_id=a.ud_id) where b.emp_idnum=p_empidnum and b.emp_stat!=0; RETURN val;
 END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `getPayrollPsAccountById` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 FUNCTION `getPayrollPsAccountById`(p_id INT) RETURNS varchar(300) CHARSET latin1
BEGIN
 DECLARE val varchar(300); SELECT REPLACE(psa_name,' ','_') INTO val FROM payroll_ps_account WHERE psa_id=p_id; SET val = REPLACE(val,'/','_');  SET val = REPLACE(val,'-','_'); RETURN val;
 END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `alter_column` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`%`*/ /*!50003 PROCEDURE `alter_column`(tble varchar(30), col varchar(30), statment text)
BEGIN
 IF (select check_column(tble,col)) THEN
	 SET @str_query = statment;
	 PREPARE stmt FROM @str_query;
	 EXECUTE stmt;
	 DEALLOCATE PREPARE stmt;
 END IF;
 END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `fetchPsaAmendment` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `fetchPsaAmendment`(m_period_start int, m_period_end int, y_period_start varchar(10), y_period_end varchar(10), l_id INT, l_type INT)
BEGIN
 DECLARE l_psa_id INT; DECLARE no_more_ea_am INT; DECLARE l_amt DECIMAL(10,2); DECLARE l_curr_amt DECIMAL(10,2); DECLARE curr_csr CURSOR FOR select amt from z_temp where emp_id=l_id and psa_id=l_psa_id; DECLARE ent_ea_am_csr CURSOR FOR select distinct a.psa_id from payroll_ps_account a inner join payroll_ps_amendment b on (b.psa_id=a.psa_id) inner join payroll_ps_amendemp c on (c.psamend_id=b.psamend_id) inner join payroll_pay_stub d on (d.paystub_id=c.paystub_id) inner join payroll_pay_period e on (e.payperiod_id=d.payperiod_id) where a.psa_type=l_type and (e.payperiod_period >= m_period_start and e.payperiod_period <= m_period_end) and (e.payperiod_period_year >= y_period_start and e.payperiod_period_year <= y_period_end) order by a.psa_order, a.psa_name; DECLARE amt_csr CURSOR FOR select sum(a.amendemp_amount) as total from payroll_ps_amendemp a inner join payroll_ps_amendment b on (b.psamend_id=a.psamend_id) inner join payroll_pay_stub c on (c.paystub_id=a.paystub_id) inner join payroll_pay_period d on (d.payperiod_id=c.payperiod_id) where a.emp_id=l_id and b.psa_id=l_psa_id and a.paystub_id!=0 and (d.payperiod_period >= m_period_start and d.payperiod_period <= m_period_end) and (d.payperiod_period_year >= y_period_start and d.payperiod_period_year <= y_period_end); DECLARE CONTINUE HANDLER FOR NOT FOUND SET no_more_ea_am=1; SET no_more_ea_am=0; OPEN ent_ea_am_csr; ea_am_loop:WHILE(no_more_ea_am=0) DO FETCH ent_ea_am_csr INTO l_psa_id; IF no_more_ea_am=1 THEN LEAVE ea_am_loop; END IF; SET l_amt = 0; SET l_curr_amt = 0; SET l_amt = 0; OPEN amt_csr; FETCH amt_csr INTO l_amt; CLOSE amt_csr; IF(l_amt IS NULL) THEN SET l_amt = 0.00; END IF; IF(checkNonZeroInAmendment(m_period_start,m_period_end,y_period_start,y_period_end,l_psa_id)) THEN IF(checkIfPSAExists(l_psa_id,l_id)) THEN OPEN curr_csr; FETCH curr_csr INTO l_curr_amt; CLOSE curr_csr; SET @total = l_amt + l_curr_amt; IF(@total IS NULL) THEN SET @total = 0; END IF; UPDATE z_temp SET amt = @total WHERE emp_id=l_id and psa_id=l_psa_id; ELSE INSERT INTO z_temp (psa_id, emp_id, amt) VALUES (l_psa_id, l_id, l_amt); END IF; END IF; END WHILE ea_am_loop; CLOSE ent_ea_am_csr; END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `fetchPsaEntry` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `fetchPsaEntry`(m_period_start int, m_period_end int, y_period_start varchar(10), y_period_end varchar(10), l_id INT, l_type INT)
BEGIN DECLARE l_psa_id INT; DECLARE no_more_el INT; DECLARE l_amt DECIMAL(10,2); DECLARE ent_ea_csr CURSOR FOR select distinct a.psa_id from payroll_ps_account a inner join payroll_paystub_entry b on (b.psa_id=a.psa_id) inner join payroll_pay_stub c on (c.paystub_id=b.paystub_id) inner join payroll_pay_period d on (d.payperiod_id=c.payperiod_id) where a.psa_type=l_type and (d.payperiod_period >= m_period_start and d.payperiod_period <= m_period_end) and (d.payperiod_period_year >= y_period_start and d.payperiod_period_year <= y_period_end) order by a.psa_order, a.psa_name; DECLARE amt_csr CURSOR FOR SELECT sum(a.ppe_amount) as total FROM payroll_paystub_entry a inner join payroll_paystub_report b on (b.paystub_id=a.paystub_id) inner join payroll_pay_period c on (c.payperiod_id=b.payperiod_id) where b.emp_id=l_id and a.psa_id=l_psa_id and (c.payperiod_period >= m_period_start and c.payperiod_period <= m_period_end) and (c.payperiod_period_year >= y_period_start and c.payperiod_period_year <= y_period_end); DECLARE CONTINUE HANDLER FOR NOT FOUND SET no_more_el=1; SET no_more_el=0; OPEN ent_ea_csr; ent_loop:WHILE(no_more_el=0) DO FETCH ent_ea_csr INTO l_psa_id; IF no_more_el=1 THEN LEAVE ent_loop; END IF; OPEN amt_csr; FETCH amt_csr INTO l_amt; CLOSE amt_csr; IF(l_amt IS NULL) THEN SET l_amt = 0.00; END IF; IF(NOT checkIfPSAExists(l_psa_id,l_id) AND checkNonZeroInEntry(m_period_start,m_period_end,y_period_start,y_period_end,l_psa_id)) THEN INSERT INTO z_temp (psa_id, emp_id, amt) VALUES (l_psa_id, l_id, l_amt); END IF; END WHILE ent_loop; CLOSE ent_ea_csr; END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `fetchPsaEntryPerEmp` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `fetchPsaEntryPerEmp`(m_period_start int, m_period_end int, y_period_start varchar(10), y_period_end varchar(10), l_id INT, l_type INT)
BEGIN
 DECLARE l_psa_id INT; DECLARE no_more_el INT; DECLARE l_amt DECIMAL(10,2); DECLARE ent_ea_csr CURSOR FOR  select distinct a.psa_id from payroll_ps_account a inner join payroll_paystub_entry b on (b.psa_id=a.psa_id) inner join payroll_pay_stub c on (c.paystub_id=b.paystub_id) inner join payroll_pay_period d on (d.payperiod_id=c.payperiod_id) inner join payroll_paystub_report e on (e.paystub_id=b.paystub_id) where a.psa_type=l_type and e.emp_id=l_id and (d.payperiod_period >= m_period_start and d.payperiod_period <= m_period_end) and (d.payperiod_period_year >= y_period_start and d.payperiod_period_year <= y_period_end) order by a.psa_order, a.psa_name; DECLARE amt_csr CURSOR FOR SELECT sum(a.ppe_amount) as total FROM payroll_paystub_entry a inner join payroll_paystub_report b on (b.paystub_id=a.paystub_id) inner join payroll_pay_period c on (c.payperiod_id=b.payperiod_id) where b.emp_id=l_id and a.psa_id=l_psa_id and (c.payperiod_period >= m_period_start and c.payperiod_period <= m_period_end) and (c.payperiod_period_year >= y_period_start and c.payperiod_period_year <= y_period_end); DECLARE CONTINUE HANDLER FOR NOT FOUND SET no_more_el=1; SET no_more_el=0; OPEN ent_ea_csr; ent_loop:WHILE(no_more_el=0) DO FETCH ent_ea_csr INTO l_psa_id; OPEN amt_csr; FETCH amt_csr INTO l_amt; CLOSE amt_csr; IF(l_amt IS NULL) THEN SET l_amt = 0.00; END IF; IF(NOT checkIfPSAExists(l_psa_id,l_id) AND checkNonZeroInEntry(m_period_start,m_period_end,y_period_start,y_period_end,l_psa_id)) THEN INSERT INTO z_temp (psa_id, emp_id, amt) VALUES (l_psa_id, l_id, l_amt); END IF; IF no_more_el=1 THEN LEAVE ent_loop; END IF; END WHILE ent_loop; CLOSE ent_ea_csr;
 END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `fetchPsaLoan` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `fetchPsaLoan`(m_period_start int, m_period_end int, y_period_start varchar(10), y_period_end varchar(10), l_id INT, l_type INT)
BEGIN
 DECLARE l_psa_id INT; DECLARE no_more_loan INT; DECLARE l_amt DECIMAL(10,2); DECLARE loan_csr CURSOR FOR select distinct a.psa_id from payroll_ps_account a inner join loan_info b on (b.psa_id=a.psa_id) inner join loan_detail_sum c on (c.loan_id=b.loan_id) inner join payroll_pay_stub d on (d.paystub_id=c.paystub_id) inner join payroll_pay_period e on (e.payperiod_id=d.payperiod_id) where a.psa_type=l_type and (e.payperiod_period >= m_period_start and e.payperiod_period <= m_period_end) and (e.payperiod_period_year >= y_period_start and e.payperiod_period_year <= y_period_end) order by a.psa_order, a.psa_name; DECLARE amt_csr CURSOR FOR select sum(a.loansum_payment) from loan_detail_sum a inner join loan_info b on (b.loan_id=a.loan_id) inner join payroll_pay_stub c on (c.paystub_id=a.paystub_id) inner join payroll_pay_period d on (d.payperiod_id=c.payperiod_id) where b.emp_id=l_id and b.psa_id=l_psa_id and (d.payperiod_period >= m_period_start and d.payperiod_period <= m_period_end) and (d.payperiod_period_year >= y_period_start and d.payperiod_period_year <= y_period_end); DECLARE CONTINUE HANDLER FOR NOT FOUND SET no_more_loan=1; SET no_more_loan=0; OPEN loan_csr; loan_loop:WHILE(no_more_loan=0) DO FETCH loan_csr INTO l_psa_id; IF no_more_loan=1 THEN LEAVE loan_loop; END IF; OPEN amt_csr; FETCH amt_csr INTO l_amt; CLOSE amt_csr; IF(NOT checkIfPSAExists(l_psa_id,l_id) AND checkNonZeroInLoan(m_period_start,m_period_end,y_period_start,y_period_end,l_psa_id)) THEN IF(l_amt IS NULL) THEN SET l_amt = 0.00; END IF; INSERT INTO z_temp (psa_id, emp_id, amt) VALUES (l_psa_id, l_id, l_amt); END IF; END WHILE loan_loop; CLOSE loan_csr; END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `mergeTbl` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `mergeTbl`()
BEGIN DROP TABLE IF EXISTS `payroll_ps_amendemp_temp`; CREATE TABLE `payroll_ps_amendemp_temp` ( `amendemp_id` int(10) unsigned NOT NULL AUTO_INCREMENT, `emp_id` int(10) unsigned NOT NULL, `paystub_id` int(11) unsigned NOT NULL, `psamend_id` int(11) unsigned NOT NULL, `amendemp_amount` decimal(11,2) DEFAULT '0.00', `amendemp_addwho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL, `amendemp_addwhen` timestamp NULL DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (`amendemp_id`) ) ENGINE=MyISAM AUTO_INCREMENT=346 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci; ALTER IGNORE TABLE payroll_ps_amendemp ADD paystub_id int(11) unsigned NOT NULL AFTER emp_id; INSERT INTO payroll_ps_amendemp_temp(amendemp_id, emp_id, paystub_id, psamend_id, amendemp_amount, amendemp_addwho, amendemp_addwhen) SELECT c.* FROM  ((SELECT a.* FROM payroll_ps_amendemp as a) UNION (SELECT b.* FROM payroll_db2.payroll_ps_amendemp as b)) c ON DUPLICATE KEY UPDATE paystub_id = IF(c.paystub_id != 0, VALUES(paystub_id), 0); DROP TABLE IF EXISTS `payroll_ps_amendemp`; RENAME TABLE payroll_ps_amendemp_temp TO payroll_ps_amendemp; END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `statSummary` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `statSummary`(m_period varchar(10), y_period varchar(10))
BEGIN DECLARE l_id INT; DECLARE l_emp_idnum VARCHAR(30);  DECLARE l_lastname VARCHAR(30);  DECLARE l_firstname VARCHAR(30); DECLARE l_middlename VARCHAR(30); DECLARE l_salary DECIMAL(10,2); DECLARE l_deduction DECIMAL(10,2); DECLARE l_sss_num VARCHAR(30); DECLARE l_sss_contribution DECIMAL(10,2); DECLARE l_phic_num VARCHAR(30); DECLARE l_phic_contribution DECIMAL(10,2); DECLARE l_hdmf_num VARCHAR(30); DECLARE l_hdmf_contribution DECIMAL(10,2); DECLARE no_more_emp INT; DECLARE l_emp_count INT; DECLARE emp_csr CURSOR FOR select distinct a.emp_id from emp_masterfile a inner join emp_personal_info b on (b.pi_id=a.pi_id) inner join payroll_paystub_report c on (c.emp_id=a.emp_id) inner join payroll_pay_period d on (d.payperiod_id=c.payperiod_id) where d.payperiod_period=m_period and d.payperiod_period_year=y_period order by b.pi_lname; DECLARE emp_info_csr CURSOR FOR select a.emp_idnum, b.pi_lname, b.pi_fname, b.pi_mname, replace(b.pi_sss,'-',''), replace(b.pi_phic,'-',''), replace(b.pi_hdmf,'-','') from emp_masterfile a inner join emp_personal_info b on (b.pi_id=a.pi_id) WHERE a.emp_id=l_id; DECLARE salary_csr CURSOR FOR select sum(a.ppe_amount)  from payroll_paystub_entry a inner join payroll_paystub_report b on (b.paystub_id=a.paystub_id) inner join payroll_pay_period c on (c.payperiod_id=b.payperiod_id) where b.emp_id=l_id and c.payperiod_period=m_period and c.payperiod_period_year=y_period and (a.psa_id=4 or a.psa_id=25 or a.psa_id=26); DECLARE deduction_csr CURSOR FOR select sum(a.ppe_amount)  from payroll_paystub_entry a inner join payroll_paystub_report b on (b.paystub_id=a.paystub_id) inner join payroll_pay_period c on (c.payperiod_id=b.payperiod_id) where b.emp_id=10 and c.payperiod_period=1 and c.payperiod_period_year=2012 and (a.psa_id=33 or a.psa_id=34); DECLARE sss_contribution_csr CURSOR FOR select sum(a.ppe_amount)  from payroll_paystub_entry a inner join payroll_paystub_report b on (b.paystub_id=a.paystub_id) inner join payroll_pay_period c on (c.payperiod_id=b.payperiod_id) where b.emp_id=l_id and c.payperiod_period=m_period and c.payperiod_period_year=y_period and a.psa_id=7; DECLARE phic_contribution_csr CURSOR FOR select sum(a.ppe_amount)  from payroll_paystub_entry a inner join payroll_paystub_report b on (b.paystub_id=a.paystub_id) inner join payroll_pay_period c on (c.payperiod_id=b.payperiod_id) where b.emp_id=l_id and c.payperiod_period=m_period and c.payperiod_period_year=y_period and a.psa_id=14; DECLARE hdmf_contribution_csr CURSOR FOR select sum(a.ppe_amount)  from payroll_paystub_entry a inner join payroll_paystub_report b on (b.paystub_id=a.paystub_id) inner join payroll_pay_period c on (c.payperiod_id=b.payperiod_id) where b.emp_id=l_id and c.payperiod_period=m_period and c.payperiod_period_year=y_period and a.psa_id=15; DECLARE CONTINUE HANDLER FOR NOT FOUND SET no_more_emp=1; SET no_more_emp=0; OPEN emp_csr; emp_loop:WHILE(no_more_emp=0) DO FETCH emp_csr INTO l_id; OPEN emp_info_csr; FETCH emp_info_csr INTO l_emp_idnum, l_lastname, l_firstname, l_middlename, l_sss_num, l_phic_num, l_hdmf_num; CLOSE emp_info_csr; OPEN salary_csr; FETCH salary_csr INTO l_salary; CLOSE salary_csr; OPEN deduction_csr; FETCH deduction_csr INTO l_deduction; CLOSE deduction_csr; SET l_salary = l_salary - l_deduction; OPEN sss_contribution_csr; FETCH sss_contribution_csr INTO l_sss_contribution; CLOSE sss_contribution_csr; OPEN phic_contribution_csr; FETCH phic_contribution_csr INTO l_phic_contribution; CLOSE phic_contribution_csr; OPEN hdmf_contribution_csr; FETCH hdmf_contribution_csr INTO l_hdmf_contribution; CLOSE hdmf_contribution_csr; IF no_more_emp=1 THEN LEAVE emp_loop; END IF; SET l_emp_count=l_emp_count+1; select l_emp_idnum, l_lastname, l_firstname, l_middlename, l_salary, l_sss_num, l_sss_contribution, l_phic_num, l_phic_contribution, l_hdmf_num, l_hdmf_contribution; END WHILE emp_loop; CLOSE emp_csr; SET no_more_emp=0; END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `ytdPerEmp` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `ytdPerEmp`(m_period_start int, m_period_end int, y_period_start varchar(10), y_period_end varchar(10), p_emp_id INT, p_emp_status INT, p_dept_id INT, p_groupings INT)
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
 END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `ytdSummary` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `ytdSummary`(m_period_start int, m_period_end int, y_period_start varchar(10), y_period_end varchar(10), p_emp_id INT, p_emp_status INT, p_dept_id INT, p_groupings INT)
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
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Final view structure for view `emp_vw`
--

/*!50001 DROP TABLE IF EXISTS `emp_vw`*/;
/*!50001 DROP VIEW IF EXISTS `emp_vw`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `emp_vw` AS select distinct `a`.`emp_id` AS `emp_id`,`a`.`ud_id` AS `ud_id` from ((((`emp_masterfile` `a` join `emp_personal_info` `b` on((`b`.`pi_id` = `a`.`pi_id`))) join `payroll_paystub_report` `c` on((`c`.`emp_id` = `a`.`emp_id`))) join `payroll_pay_period` `d` on((`d`.`payperiod_id` = `c`.`payperiod_id`))) left join `app_userdept` `e` on((`e`.`ud_id` = `a`.`ud_id`))) where ((`d`.`payperiod_period` >= 1) and (`d`.`payperiod_period` <= 12) and (`d`.`payperiod_period_year` >= 2013) and (`d`.`payperiod_period_year` <= 2013) and (`a`.`emp_id` = 53) and (`a`.`ud_id` = 18)) order by `b`.`pi_lname` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `psa_vw`
--

/*!50001 DROP TABLE IF EXISTS `psa_vw`*/;
/*!50001 DROP VIEW IF EXISTS `psa_vw`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `psa_vw` AS select distinct `a`.`psa_id` AS `psa_id`,`b`.`psa_name` AS `psa_name`,`b`.`psa_type` AS `psa_type` from (`z_temp` `a` join `payroll_ps_account` `b` on((`b`.`psa_id` = `a`.`psa_id`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-01-03 16:04:38
