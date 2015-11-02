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
) ENGINE=MyISAM AUTO_INCREMENT=93 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_modules`
--

LOCK TABLES `app_modules` WRITE;
/*!40000 ALTER TABLE `app_modules` DISABLE KEYS */;
INSERT INTO `app_modules` VALUES (1,'Setup','','&nbsp;<img src=\\\"../themes/default/images/admin/menu/setup16x16.png\\\" align=\\\"absmiddle\\\"/>&nbsp;',0,40,'',1,'a:18:{s:8:\"linkpage\";s:0:\"\";s:7:\"statpos\";s:0:\"\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(2,'Manage User','','&nbsp;<img src=\"../themes/default/images/admin/menu/book.png\" align=\"absmiddle\"/>&nbsp;',19,20,'setup.php?statpos=manageuser',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:10:\"manageuser\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(3,'Manage Modules','','&nbsp;<img src=\"../themes/default/images/admin/menu/modules.png\" align=\"absmiddle\"/>&nbsp;',1,2,'setup.php?statpos=managemodule',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:12:\"managemodule\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:2:{i:0;s:1:\"2\";i:1;s:1:\"1\";}s:9:\"user_type\";a:1:{i:0;s:19:\"Super Administrator\";}}'),(4,'Manage User Type','','&nbsp;<img src=\"../themes/default/images/admin/menu/book.png\" align=\"absmiddle\"/>&nbsp;',19,10,'setup.php?statpos=manageusertype',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:14:\"manageusertype\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(5,'Department','','&nbsp;<img src=\"../themes/default/images/admin/menu/book.png\" align=\"absmiddle\"/>&nbsp;',12,40,'setup.php?statpos=managedept',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:10:\"managedept\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/admin.php\";s:19:\"controller_filename\";s:27:\"admin/setup/manage_dept.php\";s:14:\"class_filename\";s:33:\"admin/setup/manage_dept.class.php\";s:10:\"class_name\";s:13:\"clsManageDept\";s:17:\"template_filename\";s:31:\"admin/setup/manage_dept.tpl.php\";s:21:\"templateform_filename\";s:36:\"admin/setup/manage_dept_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(7,'Home','','&nbsp;<img src=\"../themes/default/images/admin/menu/home.png\" align=\"absmiddle\"/>&nbsp;',0,10,'index.php',1,'a:18:{s:8:\"linkpage\";s:9:\"index.php\";s:7:\"statpos\";s:0:\"\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(8,'Transaction','','&nbsp;<img src=\"../themes/default/images/admin/menu/payroll.png\" width=\"15\" height=\"15\" align=\"absmiddle\"/>&nbsp;',0,20,'',1,'a:18:{s:8:\"linkpage\";s:0:\"\";s:7:\"statpos\";s:0:\"\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(9,'201 File','','&nbsp;<img src=\"../themes/default/images/admin/menu/employee_1.png\" align=\"absmiddle\"/>&nbsp;',63,10,'transaction.php?statpos=emp_masterfile',1,'a:18:{s:8:\"linkpage\";s:15:\"transaction.php\";s:7:\"statpos\";s:14:\"emp_masterfile\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:21:\"admin/transaction.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:47:\"admin/transaction/emp_masterfile.controller.php\";s:14:\"class_filename\";s:42:\"admin/transaction/emp_masterfile.class.php\";s:10:\"class_name\";s:17:\"clsEMP_MasterFile\";s:17:\"template_filename\";s:40:\"admin/transaction/emp_masterfile.tpl.php\";s:21:\"templateform_filename\";s:45:\"admin/transaction/emp_masterfile_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(10,'Company Profile','','&nbsp;<img src=\"../themes/default/images/admin/menu/book.png\" align=\"absmiddle\"/>&nbsp;',20,10,'setup.php?statpos=manage_comp',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:11:\"manage_comp\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:38:\"admin/setup/manage_comp.controller.php\";s:14:\"class_filename\";s:33:\"admin/setup/manage_comp.class.php\";s:10:\"class_name\";s:14:\"clsManage_Comp\";s:17:\"template_filename\";s:31:\"admin/setup/manage_comp.tpl.php\";s:21:\"templateform_filename\";s:36:\"admin/setup/manage_comp_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(12,'Manage 201','','&nbsp;<img src=\"../themes/default/images/admin/menu/bookbined.png\" align=\"absmiddle\"/>&nbsp;',17,30,'',1,'a:18:{s:8:\"linkpage\";s:0:\"\";s:7:\"statpos\";s:0:\"\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(13,'Employee Type','','&nbsp;<img src=\"../themes/default/images/admin/menu/book.png\" align=\"absmiddle\"/>&nbsp;',12,10,'setup.php?statpos=emptype',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:7:\"emptype\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:34:\"admin/setup/emptype.controller.php\";s:14:\"class_filename\";s:29:\"admin/setup/emptype.class.php\";s:10:\"class_name\";s:10:\"clsEMPType\";s:17:\"template_filename\";s:27:\"admin/setup/emptype.tpl.php\";s:21:\"templateform_filename\";s:32:\"admin/setup/emptype_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(14,'Employee Category','','&nbsp;<img src=\"../themes/default/images/admin/menu/book.png\" align=\"absmiddle\"/>&nbsp;',12,20,'setup.php?statpos=empcateg',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:8:\"empcateg\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:35:\"admin/setup/empcateg.controller.php\";s:14:\"class_filename\";s:30:\"admin/setup/empcateg.class.php\";s:10:\"class_name\";s:11:\"clsEMPCateg\";s:17:\"template_filename\";s:28:\"admin/setup/empcateg.tpl.php\";s:21:\"templateform_filename\";s:33:\"admin/setup/empcateg_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(15,'Job Position','','&nbsp;<img src=\"../themes/default/images/admin/menu/book.png\" align=\"absmiddle\"/>&nbsp;',12,30,'setup.php?statpos=jobpost',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:7:\"jobpost\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:34:\"admin/setup/jobpost.controller.php\";s:14:\"class_filename\";s:29:\"admin/setup/jobpost.class.php\";s:10:\"class_name\";s:10:\"clsJobPost\";s:17:\"template_filename\";s:27:\"admin/setup/jobpost.tpl.php\";s:21:\"templateform_filename\";s:32:\"admin/setup/jobpost_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(16,'Reports','','&nbsp;<img src=\"../themes/default/images/admin/menu/reports16x16.png\" align=\"absmiddle\"/>&nbsp;',0,30,'',1,'a:18:{s:8:\"linkpage\";s:0:\"\";s:7:\"statpos\";s:0:\"\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(17,'Libraries','','&nbsp;<img src=\"../themes/default/images/admin/menu/savebook.png\" align=\"absmiddle\"/>&nbsp;',1,20,'',1,'a:18:{s:8:\"linkpage\";s:0:\"\";s:7:\"statpos\";s:0:\"\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(19,'Manage Access Level','','&nbsp;<img src=\"../themes/default/images/admin/menu/bookbined.png\" align=\"absmiddle\"/>&nbsp;',17,10,'',1,'a:18:{s:8:\"linkpage\";s:0:\"\";s:7:\"statpos\";s:0:\"\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(20,'Manage Company','','&nbsp;<img src=\"../themes/default/images/admin/menu/bookbined.png\" align=\"absmiddle\"/>&nbsp;',17,20,'',1,'a:18:{s:8:\"linkpage\";s:0:\"\";s:7:\"statpos\";s:0:\"\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(21,'Company Bank/s','Company Bank/s','&nbsp;<img src=\"../themes/default/images/admin/menu/book.png\" align=\"absmiddle\"/>&nbsp;',20,30,'setup.php?statpos=compbanks',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:9:\"compbanks\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:36:\"admin/setup/compbanks.controller.php\";s:14:\"class_filename\";s:31:\"admin/setup/compbanks.class.php\";s:10:\"class_name\";s:12:\"clsCompBanks\";s:17:\"template_filename\";s:29:\"admin/setup/compbanks.tpl.php\";s:21:\"templateform_filename\";s:34:\"admin/setup/compbanks_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(25,'Process Payroll','','&nbsp;<img src=\"../themes/default/images/admin/menu/processpay.png\" align=\"absmiddle\"/>&nbsp;',72,10,'transaction.php?statpos=process_payroll',1,'a:18:{s:8:\"linkpage\";s:15:\"transaction.php\";s:7:\"statpos\";s:15:\"process_payroll\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:21:\"admin/transaction.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:48:\"admin/transaction/process_payroll.controller.php\";s:14:\"class_filename\";s:43:\"admin/transaction/process_payroll.class.php\";s:10:\"class_name\";s:18:\"clsProcess_Payroll\";s:17:\"template_filename\";s:41:\"admin/transaction/process_payroll.tpl.php\";s:21:\"templateform_filename\";s:46:\"admin/transaction/process_payroll_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(26,'Manage Pay Element ','','&nbsp;<img src=\"../themes/default/images/admin/menu/book.png\" align=\"absmiddle\"/>&nbsp;',17,60,'setup.php?statpos=mnge_pe',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:7:\"mnge_pe\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:34:\"admin/setup/mnge_pe.controller.php\";s:14:\"class_filename\";s:29:\"admin/setup/mnge_pe.class.php\";s:10:\"class_name\";s:10:\"clsMnge_PE\";s:17:\"template_filename\";s:27:\"admin/setup/mnge_pe.tpl.php\";s:21:\"templateform_filename\";s:32:\"admin/setup/mnge_pe_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(27,'Manage Pay Group ','','&nbsp;<img src=\"../themes/default/images/admin/menu/book.png\" align=\"absmiddle\"/>&nbsp;',17,60,'setup.php?statpos=mnge_pg ',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:8:\"mnge_pg \";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:34:\"admin/setup/mnge_pg.controller.php\";s:14:\"class_filename\";s:29:\"admin/setup/mnge_pg.class.php\";s:10:\"class_name\";s:10:\"clsMnge_PG\";s:17:\"template_filename\";s:27:\"admin/setup/mnge_pg.tpl.php\";s:21:\"templateform_filename\";s:32:\"admin/setup/mnge_pg_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(28,'Manage Statutory Contribution','','&nbsp;<img src=\"../themes/default/images/admin/menu/book.png\" align=\"absmiddle\"/>&nbsp;',17,70,'setup.php?statpos=mnge_sc',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:7:\"mnge_sc\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:34:\"admin/setup/mnge_sc.controller.php\";s:14:\"class_filename\";s:29:\"admin/setup/mnge_sc.class.php\";s:10:\"class_name\";s:10:\"clsMnge_SC\";s:17:\"template_filename\";s:27:\"admin/setup/mnge_sc.tpl.php\";s:21:\"templateform_filename\";s:32:\"admin/setup/mnge_sc_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(29,'Manage Income Tax','','&nbsp;<img src=\"../themes/default/images/admin/menu/bookbined.png\" align=\"absmiddle\"/>&nbsp;',17,80,'',1,'a:18:{s:8:\"linkpage\";s:0:\"\";s:7:\"statpos\";s:0:\"\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:34:\"admin/setup/mnge_it.controller.php\";s:14:\"class_filename\";s:29:\"admin/setup/mnge_it.class.php\";s:10:\"class_name\";s:10:\"clsMnge_IT\";s:17:\"template_filename\";s:27:\"admin/setup/mnge_it.tpl.php\";s:21:\"templateform_filename\";s:32:\"admin/setup/mnge_it_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(30,'Manage Tax Employer','','&nbsp;<img src=\"../themes/default/images/admin/menu/book.png\" align=\"absmiddle\"/>&nbsp;',29,10,'setup.php?statpos=mnge_te',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:7:\"mnge_te\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:34:\"admin/setup/mnge_te.controller.php\";s:14:\"class_filename\";s:29:\"admin/setup/mnge_te.class.php\";s:10:\"class_name\";s:10:\"clsMnge_TE\";s:17:\"template_filename\";s:27:\"admin/setup/mnge_te.tpl.php\";s:21:\"templateform_filename\";s:32:\"admin/setup/mnge_te_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(31,'Manage Tax Table','','&nbsp;<img src=\"../themes/default/images/admin/menu/book.png\" align=\"absmiddle\"/>&nbsp;',29,20,'setup.php?statpos=mnge_tt',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:7:\"mnge_tt\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:34:\"admin/setup/mnge_tt.controller.php\";s:14:\"class_filename\";s:29:\"admin/setup/mnge_tt.class.php\";s:10:\"class_name\";s:10:\"clsMnge_TT\";s:17:\"template_filename\";s:27:\"admin/setup/mnge_tt.tpl.php\";s:21:\"templateform_filename\";s:32:\"admin/setup/mnge_tt_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(32,'Salary Classification','','&nbsp;<img src=\"../themes/default/images/admin/menu/book.png\" align=\"absmiddle\"/>&nbsp;',12,50,'setup.php?statpos=salaryclass',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:11:\"salaryclass\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:38:\"admin/setup/salaryclass.controller.php\";s:14:\"class_filename\";s:33:\"admin/setup/salaryclass.class.php\";s:10:\"class_name\";s:14:\"clsSalaryClass\";s:17:\"template_filename\";s:31:\"admin/setup/salaryclass.tpl.php\";s:21:\"templateform_filename\";s:36:\"admin/setup/salaryclass_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(34,'SSS','','&nbsp;<img src=\"../themes/default/images/admin/menu/report.png\" align=\"absmiddle\"/>&nbsp;',84,10,'reports.php?statpos=sss',1,'a:18:{s:8:\"linkpage\";s:11:\"reports.php\";s:7:\"statpos\";s:3:\"sss\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:17:\"admin/reports.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:32:\"admin/reports/sss.controller.php\";s:14:\"class_filename\";s:27:\"admin/reports/sss.class.php\";s:10:\"class_name\";s:6:\"clsSSS\";s:17:\"template_filename\";s:25:\"admin/reports/sss.tpl.php\";s:21:\"templateform_filename\";s:30:\"admin/reports/sss_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(35,'PHIC','','&nbsp;<img src=\"../themes/default/images/admin/menu/report.png\" align=\"absmiddle\"/>&nbsp;',84,20,'reports.php?statpos=phic',1,'a:18:{s:8:\"linkpage\";s:11:\"reports.php\";s:7:\"statpos\";s:4:\"phic\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:17:\"admin/reports.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:33:\"admin/reports/phic.controller.php\";s:14:\"class_filename\";s:28:\"admin/reports/phic.class.php\";s:10:\"class_name\";s:7:\"clsPHIC\";s:17:\"template_filename\";s:26:\"admin/reports/phic.tpl.php\";s:21:\"templateform_filename\";s:31:\"admin/reports/phic_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(36,'HDMF','','&nbsp;<img src=\"../themes/default/images/admin/menu/report.png\" align=\"absmiddle\"/>&nbsp;',84,30,'reports.php?statpos=hdmf',1,'a:18:{s:8:\"linkpage\";s:11:\"reports.php\";s:7:\"statpos\";s:4:\"hdmf\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:17:\"admin/reports.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:33:\"admin/reports/hdmf.controller.php\";s:14:\"class_filename\";s:28:\"admin/reports/hdmf.class.php\";s:10:\"class_name\";s:7:\"clsHDMF\";s:17:\"template_filename\";s:26:\"admin/reports/hdmf.tpl.php\";s:21:\"templateform_filename\";s:31:\"admin/reports/hdmf_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(37,'Tax','','&nbsp;<img src=\"../themes/default/images/admin/menu/report.png\" align=\"absmiddle\"/>&nbsp;',85,40,'reports.php?statpos=tax',1,'a:18:{s:8:\"linkpage\";s:11:\"reports.php\";s:7:\"statpos\";s:3:\"tax\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:18:\"admin/reports.phpa\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:32:\"admin/reports/tax.controller.php\";s:14:\"class_filename\";s:27:\"admin/reports/tax.class.php\";s:10:\"class_name\";s:6:\"clsTAX\";s:17:\"template_filename\";s:25:\"admin/reports/tax.tpl.php\";s:21:\"templateform_filename\";s:30:\"admin/reports/tax_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(39,'SSS Collection','','&nbsp;<img src=\"../themes/default/images/admin/menu/report.png\" align=\"absmiddle\"/>&nbsp;',84,60,'reports.php?statpos=sss_collection',1,'a:18:{s:8:\"linkpage\";s:11:\"reports.php\";s:7:\"statpos\";s:14:\"sss_collection\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:17:\"admin/reports.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:43:\"admin/reports/sss_collection.controller.php\";s:14:\"class_filename\";s:38:\"admin/reports/sss_collection.class.php\";s:10:\"class_name\";s:16:\"clsSSSCollection\";s:17:\"template_filename\";s:36:\"admin/reports/sss_collection.tpl.php\";s:21:\"templateform_filename\";s:41:\"admin/reports/sss_collection_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(40,'HDMF Collection','','&nbsp;<img src=\"../themes/default/images/admin/menu/report.png\" align=\"absmiddle\"/>&nbsp;',84,70,'reports.php?statpos=hdmf_collection',1,'a:18:{s:8:\"linkpage\";s:11:\"reports.php\";s:7:\"statpos\";s:15:\"hdmf_collection\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:17:\"admin/reports.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:44:\"admin/reports/hdmf_collection.controller.php\";s:14:\"class_filename\";s:39:\"admin/reports/hdmf_collection.class.php\";s:10:\"class_name\";s:17:\"clsHDMFCollection\";s:17:\"template_filename\";s:37:\"admin/reports/hdmf_collection.tpl.php\";s:21:\"templateform_filename\";s:42:\"admin/reports/hdmf_collection_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(41,'BIR Alphalist','','&nbsp;<img src=\"../themes/default/images/admin/menu/report.png\" align=\"absmiddle\"/>&nbsp;',85,80,'reports.php?statpos=bir_alphalist',1,'a:18:{s:8:\"linkpage\";s:11:\"reports.php\";s:7:\"statpos\";s:13:\"bir_alphalist\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:17:\"admin/reports.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:42:\"admin/reports/bir_alphalist.controller.php\";s:14:\"class_filename\";s:37:\"admin/reports/bir_alphalist.class.php\";s:10:\"class_name\";s:15:\"clsBIRAlphalist\";s:17:\"template_filename\";s:35:\"admin/reports/bir_alphalist.tpl.php\";s:21:\"templateform_filename\";s:40:\"admin/reports/bir_alphalist_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(42,'Amendments','','&nbsp;<img src=\"../themes/default/images/admin/menu/payadd.png\" align=\"absmiddle\"/>&nbsp;',72,5,'transaction.php?statpos=ps_amend',1,'a:18:{s:8:\"linkpage\";s:15:\"transaction.php\";s:7:\"statpos\";s:8:\"ps_amend\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:21:\"admin/transaction.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:41:\"admin/transaction/ps_amend.controller.php\";s:14:\"class_filename\";s:36:\"admin/transaction/ps_amend.class.php\";s:10:\"class_name\";s:11:\"clsPS_Amend\";s:17:\"template_filename\";s:34:\"admin/transaction/ps_amend.tpl.php\";s:21:\"templateform_filename\";s:39:\"admin/transaction/ps_amend_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(43,'Tax Exemption','','&nbsp;<img src=\"../themes/default/images/admin/menu/book.png\" align=\"absmiddle\"/>&nbsp;',12,70,'setup.php?statpos=tax_excep',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:9:\"tax_excep\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:36:\"admin/setup/tax_excep.controller.php\";s:14:\"class_filename\";s:31:\"admin/setup/tax_excep.class.php\";s:10:\"class_name\";s:12:\"clsTax_Excep\";s:17:\"template_filename\";s:29:\"admin/setup/tax_excep.tpl.php\";s:21:\"templateform_filename\";s:34:\"admin/setup/tax_excep_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(44,'Factor Rate','','&nbsp;<img src=\"../themes/default/images/admin/menu/book.png\" align=\"absmiddle\"/>&nbsp;',17,100,'setup.php?statpos=factor_rate',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:11:\"factor_rate\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:38:\"admin/setup/factor_rate.controller.php\";s:14:\"class_filename\";s:33:\"admin/setup/factor_rate.class.php\";s:10:\"class_name\";s:14:\"clsFactor_Rate\";s:17:\"template_filename\";s:31:\"admin/setup/factor_rate.tpl.php\";s:21:\"templateform_filename\";s:36:\"admin/setup/factor_rate_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(45,'Deduction Type','','&nbsp;<img src=\"../themes/default/images/admin/menu/book.png\" align=\"absmiddle\"/>&nbsp;',12,70,'setup.php?statpos=deductype',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:9:\"deductype\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:36:\"admin/setup/deductype.controller.php\";s:14:\"class_filename\";s:31:\"admin/setup/deductype.class.php\";s:10:\"class_name\";s:12:\"clsDeducType\";s:17:\"template_filename\";s:29:\"admin/setup/deductype.tpl.php\";s:21:\"templateform_filename\";s:34:\"admin/setup/deductype_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(46,'Payroll Details','','&nbsp;<img src=\"../themes/default/images/admin/menu/paydetails.png\" align=\"absmiddle\"/>&nbsp;',72,20,'transaction.php?statpos=payroll_details',1,'a:18:{s:8:\"linkpage\";s:15:\"transaction.php\";s:7:\"statpos\";s:15:\"payroll_details\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:21:\"admin/transaction.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:48:\"admin/transaction/payroll_details.controller.php\";s:14:\"class_filename\";s:43:\"admin/transaction/payroll_details.class.php\";s:10:\"class_name\";s:18:\"clsPayroll_Details\";s:17:\"template_filename\";s:41:\"admin/transaction/payroll_details.tpl.php\";s:21:\"templateform_filename\";s:46:\"admin/transaction/payroll_details_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(47,'OT Manage','','&nbsp;<img src=\"../themes/default/images/admin/menu/book.png\" align=\"absmiddle\"/>&nbsp;',87,50,'',1,'a:18:{s:8:\"linkpage\";s:0:\"\";s:7:\"statpos\";s:0:\"\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(48,'OT Table','','&nbsp;<img src=\"../themes/default/images/admin/menu/book.png\" align=\"absmiddle\"/>&nbsp;',47,10,'setup.php?statpos=ottable',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:7:\"ottable\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:34:\"admin/setup/ottable.controller.php\";s:14:\"class_filename\";s:29:\"admin/setup/ottable.class.php\";s:10:\"class_name\";s:10:\"clsOTTable\";s:17:\"template_filename\";s:27:\"admin/setup/ottable.tpl.php\";s:21:\"templateform_filename\";s:32:\"admin/setup/ottable_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(49,'OT Rate','','&nbsp;<img src=\"../themes/default/images/admin/menu/book.png\" align=\"absmiddle\"/>&nbsp;',47,20,'setup.php?statpos=otrate',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:6:\"otrate\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:33:\"admin/setup/otrate.controller.php\";s:14:\"class_filename\";s:28:\"admin/setup/otrate.class.php\";s:10:\"class_name\";s:9:\"clsOTRate\";s:17:\"template_filename\";s:26:\"admin/setup/otrate.tpl.php\";s:21:\"templateform_filename\";s:31:\"admin/setup/otrate_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(51,'General','','&nbsp;<img src=\"../themes/default/images/admin/menu/bookbined.png\" align=\"absmiddle\"/>&nbsp;',17,40,'',1,'a:18:{s:8:\"linkpage\";s:0:\"\";s:7:\"statpos\";s:0:\"\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(52,'Country','','&nbsp;<img src=\"../themes/default/images/admin/menu/book.png\" align=\"absmiddle\"/>&nbsp;',51,10,'setup.php?statpos=country',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:7:\"country\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/admin.php\";s:19:\"controller_filename\";s:34:\"admin/setup/country.controller.php\";s:14:\"class_filename\";s:29:\"admin/setup/country.class.php\";s:10:\"class_name\";s:10:\"clsCountry\";s:17:\"template_filename\";s:27:\"admin/setup/country.tpl.php\";s:21:\"templateform_filename\";s:32:\"admin/setup/country_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(53,'Region','','&nbsp;<img src=\"../themes/default/images/admin/menu/book.png\" align=\"absmiddle\"/>&nbsp;',51,20,'setup.php?statpos=region',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:6:\"region\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/admin.php\";s:19:\"controller_filename\";s:33:\"admin/setup/region.controller.php\";s:14:\"class_filename\";s:28:\"admin/setup/region.class.php\";s:10:\"class_name\";s:9:\"clsRegion\";s:17:\"template_filename\";s:27:\"admin/setup/region.tpl.php \";s:21:\"templateform_filename\";s:31:\"admin/setup/region_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(54,'State / Province','','&nbsp;<img src=\"../themes/default/images/admin/menu/book.png\" align=\"absmiddle\"/>&nbsp;',51,30,'setup.php?statpos=state_province',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:14:\"state_province\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/admin.php\";s:19:\"controller_filename\";s:41:\"admin/setup/state_province.controller.php\";s:14:\"class_filename\";s:36:\"admin/setup/state_province.class.php\";s:10:\"class_name\";s:17:\"clsState_province\";s:17:\"template_filename\";s:34:\"admin/setup/state_province.tpl.php\";s:21:\"templateform_filename\";s:39:\"admin/setup/state_province_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(55,'Zip Codes','','&nbsp;<img src=\"../themes/default/images/admin/menu/book.png\" align=\"absmiddle\"/>&nbsp;',51,40,'setup.php?statpos=zipcode',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:7:\"zipcode\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/admin.php\";s:19:\"controller_filename\";s:34:\"admin/setup/zipcode.controller.php\";s:14:\"class_filename\";s:29:\"admin/setup/zipcode.class.php\";s:10:\"class_name\";s:10:\"clsZipCode\";s:17:\"template_filename\";s:27:\"admin/setup/zipcode.tpl.php\";s:21:\"templateform_filename\";s:32:\"admin/setup/zipcode_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(56,'Payroll Register','','&nbsp;<img src=\"../themes/default/images/admin/menu/report.png\" align=\"absmiddle\"/>&nbsp;',74,10,'reports.php?statpos=payroll_register',1,'a:18:{s:8:\"linkpage\";s:11:\"reports.php\";s:7:\"statpos\";s:16:\"payroll_register\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:17:\"admin/reports.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:45:\"admin/reports/payroll_register.controller.php\";s:14:\"class_filename\";s:40:\"admin/reports/payroll_register.class.php\";s:10:\"class_name\";s:18:\"clsPayrollRegister\";s:17:\"template_filename\";s:38:\"admin/reports/payroll_register.tpl.php\";s:21:\"templateform_filename\";s:43:\"admin/reports/payroll_register_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(58,'Time and Attendance','','&nbsp;<img src=\"../themes/default/images/admin/menu/timeshit2.png\" align=\"absmiddle\"/>&nbsp;',8,15,'transaction.php?statpos=time_attend',1,'a:18:{s:8:\"linkpage\";s:15:\"transaction.php\";s:7:\"statpos\";s:11:\"time_attend\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:21:\"admin/transaction.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:44:\"admin/transaction/time_attend.controller.php\";s:14:\"class_filename\";s:39:\"admin/transaction/time_attend.class.php\";s:10:\"class_name\";s:14:\"clsTime_Attend\";s:17:\"template_filename\";s:37:\"admin/transaction/time_attend.tpl.php\";s:21:\"templateform_filename\";s:42:\"admin/transaction/time_attend_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";s:1:\"1\";s:8:\"chkclass\";s:1:\"1\";s:11:\"chktemplate\";s:1:\"1\";s:15:\"chktemplateform\";s:1:\"1\";s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(59,'Manage Leave Type','','&nbsp;<img src=\"../themes/default/images/admin/menu/book.png\" align=\"absmiddle\"/>&nbsp;',87,55,'setup.php?statpos=mnge_leave',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:10:\"mnge_leave\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:37:\"admin/setup/mnge_leave.controller.php\";s:14:\"class_filename\";s:32:\"admin/setup/mnge_leave.class.php\";s:10:\"class_name\";s:13:\"clsMnge_Leave\";s:17:\"template_filename\";s:30:\"admin/setup/mnge_leave.tpl.php\";s:21:\"templateform_filename\";s:35:\"admin/setup/mnge_leave_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(60,'Manage TA','','&nbsp;<img src=\"../themes/default/images/admin/menu/book.png\" align=\"absmiddle\"/>&nbsp;',87,57,'setup.php?statpos=mnge_ta',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:7:\"mnge_ta\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:34:\"admin/setup/mnge_ta.controller.php\";s:14:\"class_filename\";s:29:\"admin/setup/mnge_ta.class.php\";s:10:\"class_name\";s:10:\"clsMnge_TA\";s:17:\"template_filename\";s:27:\"admin/setup/mnge_ta.tpl.php\";s:21:\"templateform_filename\";s:32:\"admin/setup/mnge_ta_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(63,'Employee Master File','','&nbsp;<img src=\"../themes/default/images/admin/menu/emprecord.png\" align=\"absmiddle\"/>&nbsp;',8,1,'',1,'a:18:{s:8:\"linkpage\";s:0:\"\";s:7:\"statpos\";s:0:\"\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(64,'201 File Review','','&nbsp;<img src=\"../themes/default/images/admin/menu/201.png\" align=\"absmiddle\"/>&nbsp;',63,20,'transaction.php?statpos=201file_review',1,'a:18:{s:8:\"linkpage\";s:15:\"transaction.php\";s:7:\"statpos\";s:14:\"201file_review\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:21:\"admin/transaction.php\";s:18:\"default_controller\";s:15:\"admin/admin.php\";s:19:\"controller_filename\";s:47:\"admin/transaction/201file_review.controller.php\";s:14:\"class_filename\";s:42:\"admin/transaction/201file_review.class.php\";s:10:\"class_name\";s:17:\"cls201File_Review\";s:17:\"template_filename\";s:40:\"admin/transaction/201file_review.tlp.php\";s:21:\"templateform_filename\";s:45:\"admin/transaction/201file_review_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(65,'201 Status','','&nbsp;<img src=\"../themes/default/images/admin/menu/book.png\" align=\"absmiddle\"/>&nbsp;',12,1,'setup.php?statpos=201status',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:9:\"201status\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/admin.php\";s:19:\"controller_filename\";s:36:\"admin/setup/201status.controller.php\";s:14:\"class_filename\";s:31:\"admin/setup/201status.class.php\";s:10:\"class_name\";s:12:\"cls201Status\";s:17:\"template_filename\";s:29:\"admin/setup/201status.tpl.php\";s:21:\"templateform_filename\";s:34:\"admin/setup/201status_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(66,'Company Type','Company Type','&nbsp;<img src=\"../themes/default/images/admin/menu/book.png\" align=\"absmiddle\"/>&nbsp;',12,130,'setup.php?statpos=comptype',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:8:\"comptype\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:35:\"admin/setup/comptype.controller.php\";s:14:\"class_filename\";s:30:\"admin/setup/comptype.class.php\";s:10:\"class_name\";s:11:\"clsCompType\";s:17:\"template_filename\";s:28:\"admin/setup/comptype.tpl.php\";s:21:\"templateform_filename\";s:33:\"admin/setup/comptype_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(67,'Recruitment','','&nbsp;<img src=\"../themes/default/images/admin/menu/recruit.png\" align=\"absmiddle\"/>&nbsp;',8,0,'',1,'a:18:{s:8:\"linkpage\";s:0:\"\";s:7:\"statpos\";s:0:\"\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(68,'Application Form','','&nbsp;<img src=\"../themes/default/images/admin/menu/application.png\" align=\"absmiddle\"/>&nbsp;',67,10,'transaction.php?statpos=application_form',1,'a:18:{s:8:\"linkpage\";s:15:\"transaction.php\";s:7:\"statpos\";s:16:\"application_form\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:21:\"admin/transaction.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:49:\"admin/transaction/application_form.controller.php\";s:14:\"class_filename\";s:44:\"admin/transaction/application_form.class.php\";s:10:\"class_name\";s:19:\"clsApplication_Form\";s:17:\"template_filename\";s:37:\"admin/transaction/application.tpl.php\";s:21:\"templateform_filename\";s:42:\"admin/transaction/application_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(69,'Applicant Database','','&nbsp;<img src=\"../themes/default/images/admin/menu/files.png\" align=\"absmiddle\"/>&nbsp;',67,20,'transaction.php?statpos=applicant_database',1,'a:18:{s:8:\"linkpage\";s:15:\"transaction.php\";s:7:\"statpos\";s:18:\"applicant_database\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:21:\"admin/transaction.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:51:\"admin/transaction/applicant_database.controller.php\";s:14:\"class_filename\";s:46:\"admin/transaction/applicant_database.class.php\";s:10:\"class_name\";s:21:\"clsApplicant_Database\";s:17:\"template_filename\";s:44:\"admin/transaction/applicant_database.tpl.php\";s:21:\"templateform_filename\";s:49:\"admin/transaction/applicant_database_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(70,'For Hire','','&nbsp;<img src=\"../themes/default/images/admin/menu/hire.png\" align=\"absmiddle\"/>&nbsp;',63,0,'transaction.php?statpos=for_hire',1,'a:18:{s:8:\"linkpage\";s:15:\"transaction.php\";s:7:\"statpos\";s:8:\"for_hire\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:21:\"admin/transaction.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:41:\"admin/transaction/for_hire.controller.php\";s:14:\"class_filename\";s:36:\"admin/transaction/for_hire.class.php\";s:10:\"class_name\";s:11:\"clsFor_Hire\";s:17:\"template_filename\";s:34:\"admin/transaction/for_hire.tpl.php\";s:21:\"templateform_filename\";s:39:\"admin/transaction/for_hire_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(72,'Payroll','','&nbsp;<img src=\"../themes/default/images/admin/menu/process.png\" align=\"absmiddle\"/>&nbsp;',8,30,'',1,'a:18:{s:8:\"linkpage\";s:0:\"\";s:7:\"statpos\";s:0:\"\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(73,'Bank Account Type','','&nbsp;<img src=\"../themes/default/images/admin/menu/book.png\" align=\"absmiddle\"/>&nbsp;',12,100,'setup.php?statpos=bankaccntype',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:12:\"bankaccntype\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:39:\"admin/setup/bankaccntype.controller.php\";s:14:\"class_filename\";s:34:\"admin/setup/bankaccntype.class.php\";s:10:\"class_name\";s:15:\"clsBankAccnType\";s:17:\"template_filename\";s:32:\"admin/setup/bankaccntype.tpl.php\";s:21:\"templateform_filename\";s:37:\"admin/setup/bankaccntype_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";s:1:\"1\";s:8:\"chkclass\";s:1:\"1\";s:11:\"chktemplate\";s:1:\"1\";s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(74,'Analysis Tools','','&nbsp;<img src=\"../themes/default/images/admin/menu/analysistool.png\" align=\"absmiddle\"/>&nbsp;',16,2,'',1,'a:18:{s:8:\"linkpage\";s:0:\"\";s:7:\"statpos\";s:0:\"\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(77,'Bank Export Report','','&nbsp;<img src=\"../themes/default/images/admin/menu/report.png\" align=\"absmiddle\"/>&nbsp;',16,400,'reports.php?statpos=bank_export_report',1,'a:18:{s:8:\"linkpage\";s:11:\"reports.php\";s:7:\"statpos\";s:18:\"bank_export_report\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:17:\"admin/reports.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:47:\"admin/reports/bank_export_report.controller.php\";s:14:\"class_filename\";s:42:\"admin/reports/bank_export_report.class.php\";s:10:\"class_name\";s:21:\"clsBank_Export_Report\";s:17:\"template_filename\";s:40:\"admin/reports/bank_export_report.tpl.php\";s:21:\"templateform_filename\";s:45:\"admin/reports/bank_export_report_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";s:1:\"1\";s:8:\"chkclass\";s:1:\"1\";s:11:\"chktemplate\";s:1:\"1\";s:15:\"chktemplateform\";s:1:\"1\";s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(78,'201 Reports','','&nbsp;<img src=\"../themes/default/images/admin/menu/analysistool.png\" align=\"absmiddle\"/>&nbsp;',16,1,'',1,'a:18:{s:8:\"linkpage\";s:0:\"\";s:7:\"statpos\";s:0:\"\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(79,'201 Master List','','&nbsp;<img src=\"../themes/default/images/admin/menu/report.png\" align=\"absmiddle\"/>&nbsp;',78,10,'reports.php?statpos=201mlist',1,'a:18:{s:8:\"linkpage\";s:11:\"reports.php\";s:7:\"statpos\";s:8:\"201mlist\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:17:\"admin/reports.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:37:\"admin/reports/201mlist.controller.php\";s:14:\"class_filename\";s:32:\"admin/reports/201mlist.class.php\";s:10:\"class_name\";s:11:\"cls201MList\";s:17:\"template_filename\";s:30:\"admin/reports/201mlist.tpl.php\";s:21:\"templateform_filename\";s:35:\"admin/reports/201mlist_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(80,'Birthday List','','&nbsp;<img src=\"../themes/default/images/admin/menu/report.png\" align=\"absmiddle\"/>&nbsp;',78,20,'reports.php?statpos=bdaylist',1,'a:18:{s:8:\"linkpage\";s:11:\"reports.php\";s:7:\"statpos\";s:8:\"bdaylist\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:17:\"admin/reports.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:37:\"admin/reports/bdaylist.controller.php\";s:14:\"class_filename\";s:32:\"admin/reports/bdaylist.class.php\";s:10:\"class_name\";s:11:\"clsBdayList\";s:17:\"template_filename\";s:30:\"admin/reports/bdaylist.tpl.php\";s:21:\"templateform_filename\";s:35:\"admin/reports/bdaylist_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(81,'Database','','&nbsp;<img src=\"../themes/default/images/admin/menu/dbase.png\" align=\"absmiddle\"/>&nbsp;',1,40,'',1,'a:18:{s:8:\"linkpage\";s:0:\"\";s:7:\"statpos\";s:0:\"\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(82,'Backup Database','','&nbsp;<img src=\"../themes/default/images/admin/menu/backupdbase.png\" align=\"absmiddle\"/>&nbsp;',81,10,'setup.php?statpos=bckup_dbase',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:11:\"bckup_dbase\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:38:\"admin/setup/bckup_dbase.controller.php\";s:14:\"class_filename\";s:33:\"admin/setup/bckup_dbase.class.php\";s:10:\"class_name\";s:14:\"clsBckup_Dbase\";s:17:\"template_filename\";s:31:\"admin/setup/bckup_dbase.tpl.php\";s:21:\"templateform_filename\";s:36:\"admin/setup/bckup_dbase_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";s:1:\"1\";s:8:\"chkclass\";s:1:\"1\";s:11:\"chktemplate\";s:1:\"1\";s:15:\"chktemplateform\";s:1:\"1\";s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(83,'Restore Database','','&nbsp;<img src=\"../themes/default/images/admin/menu/restoredbase.png\" align=\"absmiddle\"/>&nbsp;',81,20,'setup.php?statpos=restore_dbase',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:13:\"restore_dbase\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:40:\"admin/setup/restore_dbase.controller.php\";s:14:\"class_filename\";s:35:\"admin/setup/restore_dbase.class.php\";s:10:\"class_name\";s:16:\"clsRestore_Dbase\";s:17:\"template_filename\";s:33:\"admin/setup/restore_dbase.tpl.php\";s:21:\"templateform_filename\";s:38:\"admin/setup/restore_dbase_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";s:1:\"1\";s:8:\"chkclass\";s:1:\"1\";s:11:\"chktemplate\";s:1:\"1\";s:15:\"chktemplateform\";s:1:\"1\";s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(84,'Statutory Report','','&nbsp;<img src=\"../themes/default/images/admin/menu/analysistool.png\" align=\"absmiddle\"/>&nbsp;',16,3,'',1,'a:18:{s:8:\"linkpage\";s:0:\"\";s:7:\"statpos\";s:0:\"\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(85,'Tax Report','','&nbsp;<img src=\"../themes/default/images/admin/menu/analysistool.png\" align=\"absmiddle\"/>&nbsp;',16,4,'',1,'a:18:{s:8:\"linkpage\";s:0:\"\";s:7:\"statpos\";s:0:\"\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(87,'Manage Timekeeping Attendance','','&nbsp;<img src=\"../themes/default/images/admin/menu/bookbined.png\" align=\"absmiddle\"/>&nbsp;',17,50,'',1,'a:18:{s:8:\"linkpage\";s:0:\"\";s:7:\"statpos\";s:0:\"\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:0:\"\";s:18:\"default_controller\";s:0:\"\";s:19:\"controller_filename\";s:0:\"\";s:14:\"class_filename\";s:0:\"\";s:10:\"class_name\";s:0:\"\";s:17:\"template_filename\";s:0:\"\";s:21:\"templateform_filename\";s:0:\"\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(88,'Payslip Report','','&nbsp;<img src=\"../themes/default/images/admin/menu/report.png\" align=\"absmiddle\"/>&nbsp;',16,60,'reports.php?statpos=pslipr',1,'a:18:{s:8:\"linkpage\";s:11:\"reports.php\";s:7:\"statpos\";s:6:\"pslipr\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:17:\"admin/reports.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:35:\"admin/reports/pslipr.controller.php\";s:14:\"class_filename\";s:30:\"admin/reports/pslipr.class.php\";s:10:\"class_name\";s:9:\"clsPslipR\";s:17:\"template_filename\";s:28:\"admin/reports/pslipr.tpl.php\";s:21:\"templateform_filename\";s:33:\"admin/reports/pslipr_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";s:1:\"1\";s:8:\"chkclass\";s:1:\"1\";s:11:\"chktemplate\";s:1:\"1\";s:15:\"chktemplateform\";s:1:\"1\";s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(89,'Year to Date (YTD) Report','','&nbsp;<img src=\"../themes/default/images/admin/menu/report.png\" align=\"absmiddle\"/>&nbsp;',74,20,'reports.php?statpos=ytdrept',1,'a:18:{s:8:\"linkpage\";s:11:\"reports.php\";s:7:\"statpos\";s:7:\"ytdrept\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:17:\"admin/reports.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:36:\"admin/reports/ytdrept.controller.php\";s:14:\"class_filename\";s:31:\"admin/reports/ytdrept.class.php\";s:10:\"class_name\";s:10:\"clsYTDRept\";s:17:\"template_filename\";s:29:\"admin/reports/ytdrept.tpl.php\";s:21:\"templateform_filename\";s:34:\"admin/reports/ytdrept_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";s:1:\"1\";s:8:\"chkclass\";s:1:\"1\";s:11:\"chktemplate\";s:1:\"1\";s:15:\"chktemplateform\";s:1:\"1\";s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}'),(91,'Download Backup File','','&nbsp;<img src=\"../themes/default/images/admin/menu/bckupdownload.png\" align=\"absmiddle\"/>&nbsp;',81,30,'setup.php?statpos=dload_dbase',1,'a:18:{s:8:\"linkpage\";s:9:\"setup.php\";s:7:\"statpos\";s:11:\"dload_dbase\";s:11:\"querystring\";s:0:\"\";s:14:\"maincontroller\";s:15:\"admin/setup.php\";s:18:\"default_controller\";s:15:\"admin/index.php\";s:19:\"controller_filename\";s:38:\"admin/setup/dload_dbase.controller.php\";s:14:\"class_filename\";s:33:\"admin/setup/dload_dbase.class.php\";s:10:\"class_name\";s:14:\"clsDload_Dbase\";s:17:\"template_filename\";s:31:\"admin/setup/dload_dbase.tpl.php\";s:21:\"templateform_filename\";s:36:\"admin/setup/dload_dbase_form.tpl.php\";s:7:\"chkmenu\";s:1:\"1\";s:7:\"chkmain\";N;s:13:\"chkcontroller\";N;s:8:\"chkclass\";N;s:11:\"chktemplate\";N;s:15:\"chktemplateform\";N;s:5:\"ud_id\";a:0:{}s:9:\"user_type\";a:0:{}}');
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
-- Table structure for table `app_userdept`
--

DROP TABLE IF EXISTS `app_userdept`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_userdept` (
  `ud_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ud_name` varchar(45) COLLATE latin1_general_ci DEFAULT NULL,
  `ud_desc` varchar(45) COLLATE latin1_general_ci DEFAULT NULL,
  `ud_parent` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`ud_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_userdept`
--

LOCK TABLES `app_userdept` WRITE;
/*!40000 ALTER TABLE `app_userdept` DISABLE KEYS */;
INSERT INTO `app_userdept` VALUES (1,'Operations Dept.','Operations Department',0),(2,'Warehouse Dept.','Warehouse Department',0),(3,'IT Dept.','Information Technology Department',0),(4,'Sales and Mktg. Dept.','Sales and Marketing Department',0),(5,'Acctg Dept.','Accounting and Finance Department',0),(6,'HR Dept.','Human Resource Department',0),(7,'Admin Dept.','Administration Department',NULL);
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
  `user_picture` mediumblob,
  PRIMARY KEY (`user_id`),
  KEY `user_name` (`user_name`),
  KEY `user_password` (`user_password`),
  KEY `app_users_FKIndex1` (`user_type`),
  KEY `app_users_FKIndex2` (`ud_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_users`
--

LOCK TABLES `app_users` WRITE;
/*!40000 ALTER TABLE `app_users` DISABLE KEYS */;
INSERT INTO `app_users` VALUES (1,0,3,'jimabignay','Jason Mabignay','8020a11ea1be81e9fb1e0a5deda6fffc','Super Administrator',1,'jason.mabignay@sigmasoft.com.ph','cutefullness','mail.sigmasoft.com.ph','26','',''),(2,0,3,'irsalvador','Ishmael Rolph C. Salvador','19ced5b5c528a2c4aa19aa27e52c2d7d','Super Administrator',1,'irsalvador06@gmail.com','','','','',''),(3,0,3,'duntiveros','Dionisio M. Untiveros Jr.','759c2375c925d29a9c48e98147c46dbc','Super Administrator',1,'duntiverosjr@gmail.com','','','','',''),(4,0,6,'admin','Administrator','21232f297a57a5a743894a0e4a801fc3','Administrator',1,'','','sample.email.com.ph','25','','');
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
) ENGINE=MyISAM AUTO_INCREMENT=1456 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_userstypeaccess`
--

LOCK TABLES `app_userstypeaccess` WRITE;
/*!40000 ALTER TABLE `app_userstypeaccess` DISABLE KEYS */;
INSERT INTO `app_userstypeaccess` VALUES (1,5,7,'Super Administrator'),(2,5,8,'Super Administrator'),(3,5,67,'Super Administrator'),(4,5,68,'Super Administrator'),(5,5,69,'Super Administrator'),(6,5,63,'Super Administrator'),(7,5,70,'Super Administrator'),(8,5,9,'Super Administrator'),(9,5,64,'Super Administrator'),(10,5,58,'Super Administrator'),(11,5,72,'Super Administrator'),(12,5,42,'Super Administrator'),(13,5,25,'Super Administrator'),(14,5,46,'Super Administrator'),(15,5,16,'Super Administrator'),(16,5,78,'Super Administrator'),(17,5,79,'Super Administrator'),(18,5,80,'Super Administrator'),(19,5,74,'Super Administrator'),(20,5,56,'Super Administrator'),(21,5,89,'Super Administrator'),(22,5,84,'Super Administrator'),(23,5,34,'Super Administrator'),(24,5,35,'Super Administrator'),(25,5,36,'Super Administrator'),(26,5,39,'Super Administrator'),(27,5,40,'Super Administrator'),(28,5,85,'Super Administrator'),(29,5,37,'Super Administrator'),(30,5,41,'Super Administrator'),(31,5,88,'Super Administrator'),(32,5,77,'Super Administrator'),(33,5,1,'Super Administrator'),(34,5,3,'Super Administrator'),(35,5,17,'Super Administrator'),(36,5,19,'Super Administrator'),(37,5,4,'Super Administrator'),(38,5,2,'Super Administrator'),(39,5,20,'Super Administrator'),(40,5,10,'Super Administrator'),(41,5,21,'Super Administrator'),(42,5,12,'Super Administrator'),(43,5,65,'Super Administrator'),(44,5,13,'Super Administrator'),(45,5,14,'Super Administrator'),(46,5,15,'Super Administrator'),(47,5,5,'Super Administrator'),(48,5,32,'Super Administrator'),(49,5,43,'Super Administrator'),(50,5,45,'Super Administrator'),(51,5,73,'Super Administrator'),(52,5,66,'Super Administrator'),(53,5,51,'Super Administrator'),(54,5,52,'Super Administrator'),(55,5,53,'Super Administrator'),(56,5,54,'Super Administrator'),(57,5,55,'Super Administrator'),(58,5,87,'Super Administrator'),(59,5,47,'Super Administrator'),(60,5,48,'Super Administrator'),(61,5,49,'Super Administrator'),(62,5,59,'Super Administrator'),(63,5,60,'Super Administrator'),(64,5,26,'Super Administrator'),(65,5,27,'Super Administrator'),(66,5,28,'Super Administrator'),(67,5,29,'Super Administrator'),(68,5,30,'Super Administrator'),(69,5,31,'Super Administrator'),(70,5,44,'Super Administrator'),(71,5,81,'Super Administrator'),(72,5,82,'Super Administrator'),(73,5,83,'Super Administrator'),(74,5,91,'Super Administrator'),(75,7,7,'Super Administrator'),(76,7,8,'Super Administrator'),(77,7,67,'Super Administrator'),(78,7,68,'Super Administrator'),(79,7,69,'Super Administrator'),(80,7,63,'Super Administrator'),(81,7,70,'Super Administrator'),(82,7,9,'Super Administrator'),(83,7,64,'Super Administrator'),(84,7,58,'Super Administrator'),(85,7,72,'Super Administrator'),(86,7,42,'Super Administrator'),(87,7,25,'Super Administrator'),(88,7,46,'Super Administrator'),(89,7,16,'Super Administrator'),(90,7,78,'Super Administrator'),(91,7,79,'Super Administrator'),(92,7,80,'Super Administrator'),(93,7,74,'Super Administrator'),(94,7,56,'Super Administrator'),(95,7,89,'Super Administrator'),(96,7,84,'Super Administrator'),(97,7,34,'Super Administrator'),(98,7,35,'Super Administrator'),(99,7,36,'Super Administrator'),(100,7,39,'Super Administrator'),(101,7,40,'Super Administrator'),(102,7,85,'Super Administrator'),(103,7,37,'Super Administrator'),(104,7,41,'Super Administrator'),(105,7,88,'Super Administrator'),(106,7,77,'Super Administrator'),(107,7,1,'Super Administrator'),(108,7,3,'Super Administrator'),(109,7,17,'Super Administrator'),(110,7,19,'Super Administrator'),(111,7,4,'Super Administrator'),(112,7,2,'Super Administrator'),(113,7,20,'Super Administrator'),(114,7,10,'Super Administrator'),(115,7,21,'Super Administrator'),(116,7,12,'Super Administrator'),(117,7,65,'Super Administrator'),(118,7,13,'Super Administrator'),(119,7,14,'Super Administrator'),(120,7,15,'Super Administrator'),(121,7,5,'Super Administrator'),(122,7,32,'Super Administrator'),(123,7,43,'Super Administrator'),(124,7,45,'Super Administrator'),(125,7,73,'Super Administrator'),(126,7,66,'Super Administrator'),(127,7,51,'Super Administrator'),(128,7,52,'Super Administrator'),(129,7,53,'Super Administrator'),(130,7,54,'Super Administrator'),(131,7,55,'Super Administrator'),(132,7,87,'Super Administrator'),(133,7,47,'Super Administrator'),(134,7,48,'Super Administrator'),(135,7,49,'Super Administrator'),(136,7,59,'Super Administrator'),(137,7,60,'Super Administrator'),(138,7,26,'Super Administrator'),(139,7,27,'Super Administrator'),(140,7,28,'Super Administrator'),(141,7,29,'Super Administrator'),(142,7,30,'Super Administrator'),(143,7,31,'Super Administrator'),(144,7,44,'Super Administrator'),(145,7,81,'Super Administrator'),(146,7,82,'Super Administrator'),(147,7,83,'Super Administrator'),(148,7,91,'Super Administrator'),(149,6,7,'Super Administrator'),(150,6,8,'Super Administrator'),(151,6,67,'Super Administrator'),(152,6,68,'Super Administrator'),(153,6,69,'Super Administrator'),(154,6,63,'Super Administrator'),(155,6,70,'Super Administrator'),(156,6,9,'Super Administrator'),(157,6,64,'Super Administrator'),(158,6,58,'Super Administrator'),(159,6,72,'Super Administrator'),(160,6,42,'Super Administrator'),(161,6,25,'Super Administrator'),(162,6,46,'Super Administrator'),(163,6,16,'Super Administrator'),(164,6,78,'Super Administrator'),(165,6,79,'Super Administrator'),(166,6,80,'Super Administrator'),(167,6,74,'Super Administrator'),(168,6,56,'Super Administrator'),(169,6,89,'Super Administrator'),(170,6,84,'Super Administrator'),(171,6,34,'Super Administrator'),(172,6,35,'Super Administrator'),(173,6,36,'Super Administrator'),(174,6,39,'Super Administrator'),(175,6,40,'Super Administrator'),(176,6,85,'Super Administrator'),(177,6,37,'Super Administrator'),(178,6,41,'Super Administrator'),(179,6,88,'Super Administrator'),(180,6,77,'Super Administrator'),(181,6,1,'Super Administrator'),(182,6,3,'Super Administrator'),(183,6,17,'Super Administrator'),(184,6,19,'Super Administrator'),(185,6,4,'Super Administrator'),(186,6,2,'Super Administrator'),(187,6,20,'Super Administrator'),(188,6,10,'Super Administrator'),(189,6,21,'Super Administrator'),(190,6,12,'Super Administrator'),(191,6,65,'Super Administrator'),(192,6,13,'Super Administrator'),(193,6,14,'Super Administrator'),(194,6,15,'Super Administrator'),(195,6,5,'Super Administrator'),(196,6,32,'Super Administrator'),(197,6,43,'Super Administrator'),(198,6,45,'Super Administrator'),(199,6,73,'Super Administrator'),(200,6,66,'Super Administrator'),(201,6,51,'Super Administrator'),(202,6,52,'Super Administrator'),(203,6,53,'Super Administrator'),(204,6,54,'Super Administrator'),(205,6,55,'Super Administrator'),(206,6,87,'Super Administrator'),(207,6,47,'Super Administrator'),(208,6,48,'Super Administrator'),(209,6,49,'Super Administrator'),(210,6,59,'Super Administrator'),(211,6,60,'Super Administrator'),(212,6,26,'Super Administrator'),(213,6,27,'Super Administrator'),(214,6,28,'Super Administrator'),(215,6,29,'Super Administrator'),(216,6,30,'Super Administrator'),(217,6,31,'Super Administrator'),(218,6,44,'Super Administrator'),(219,6,81,'Super Administrator'),(220,6,82,'Super Administrator'),(221,6,83,'Super Administrator'),(222,6,91,'Super Administrator'),(223,3,7,'Super Administrator'),(224,3,8,'Super Administrator'),(225,3,67,'Super Administrator'),(226,3,68,'Super Administrator'),(227,3,69,'Super Administrator'),(228,3,63,'Super Administrator'),(229,3,70,'Super Administrator'),(230,3,9,'Super Administrator'),(231,3,64,'Super Administrator'),(232,3,58,'Super Administrator'),(233,3,72,'Super Administrator'),(234,3,42,'Super Administrator'),(235,3,25,'Super Administrator'),(236,3,46,'Super Administrator'),(237,3,16,'Super Administrator'),(238,3,78,'Super Administrator'),(239,3,79,'Super Administrator'),(240,3,80,'Super Administrator'),(241,3,74,'Super Administrator'),(242,3,56,'Super Administrator'),(243,3,89,'Super Administrator'),(244,3,84,'Super Administrator'),(245,3,34,'Super Administrator'),(246,3,35,'Super Administrator'),(247,3,36,'Super Administrator'),(248,3,39,'Super Administrator'),(249,3,40,'Super Administrator'),(250,3,85,'Super Administrator'),(251,3,37,'Super Administrator'),(252,3,41,'Super Administrator'),(253,3,88,'Super Administrator'),(254,3,77,'Super Administrator'),(255,3,1,'Super Administrator'),(256,3,3,'Super Administrator'),(257,3,17,'Super Administrator'),(258,3,19,'Super Administrator'),(259,3,4,'Super Administrator'),(260,3,2,'Super Administrator'),(261,3,20,'Super Administrator'),(262,3,10,'Super Administrator'),(263,3,21,'Super Administrator'),(264,3,12,'Super Administrator'),(265,3,65,'Super Administrator'),(266,3,13,'Super Administrator'),(267,3,14,'Super Administrator'),(268,3,15,'Super Administrator'),(269,3,5,'Super Administrator'),(270,3,32,'Super Administrator'),(271,3,43,'Super Administrator'),(272,3,45,'Super Administrator'),(273,3,73,'Super Administrator'),(274,3,66,'Super Administrator'),(275,3,51,'Super Administrator'),(276,3,52,'Super Administrator'),(277,3,53,'Super Administrator'),(278,3,54,'Super Administrator'),(279,3,55,'Super Administrator'),(280,3,87,'Super Administrator'),(281,3,47,'Super Administrator'),(282,3,48,'Super Administrator'),(283,3,49,'Super Administrator'),(284,3,59,'Super Administrator'),(285,3,60,'Super Administrator'),(286,3,26,'Super Administrator'),(287,3,27,'Super Administrator'),(288,3,28,'Super Administrator'),(289,3,29,'Super Administrator'),(290,3,30,'Super Administrator'),(291,3,31,'Super Administrator'),(292,3,44,'Super Administrator'),(293,3,81,'Super Administrator'),(294,3,82,'Super Administrator'),(295,3,83,'Super Administrator'),(296,3,91,'Super Administrator'),(297,1,7,'Super Administrator'),(298,1,8,'Super Administrator'),(299,1,67,'Super Administrator'),(300,1,68,'Super Administrator'),(301,1,69,'Super Administrator'),(302,1,63,'Super Administrator'),(303,1,70,'Super Administrator'),(304,1,9,'Super Administrator'),(305,1,64,'Super Administrator'),(306,1,58,'Super Administrator'),(307,1,72,'Super Administrator'),(308,1,42,'Super Administrator'),(309,1,25,'Super Administrator'),(310,1,46,'Super Administrator'),(311,1,16,'Super Administrator'),(312,1,78,'Super Administrator'),(313,1,79,'Super Administrator'),(314,1,80,'Super Administrator'),(315,1,74,'Super Administrator'),(316,1,56,'Super Administrator'),(317,1,89,'Super Administrator'),(318,1,84,'Super Administrator'),(319,1,34,'Super Administrator'),(320,1,35,'Super Administrator'),(321,1,36,'Super Administrator'),(322,1,39,'Super Administrator'),(323,1,40,'Super Administrator'),(324,1,85,'Super Administrator'),(325,1,37,'Super Administrator'),(326,1,41,'Super Administrator'),(327,1,88,'Super Administrator'),(328,1,77,'Super Administrator'),(329,1,1,'Super Administrator'),(330,1,3,'Super Administrator'),(331,1,17,'Super Administrator'),(332,1,19,'Super Administrator'),(333,1,4,'Super Administrator'),(334,1,2,'Super Administrator'),(335,1,20,'Super Administrator'),(336,1,10,'Super Administrator'),(337,1,21,'Super Administrator'),(338,1,12,'Super Administrator'),(339,1,65,'Super Administrator'),(340,1,13,'Super Administrator'),(341,1,14,'Super Administrator'),(342,1,15,'Super Administrator'),(343,1,5,'Super Administrator'),(344,1,32,'Super Administrator'),(345,1,43,'Super Administrator'),(346,1,45,'Super Administrator'),(347,1,73,'Super Administrator'),(348,1,66,'Super Administrator'),(349,1,51,'Super Administrator'),(350,1,52,'Super Administrator'),(351,1,53,'Super Administrator'),(352,1,54,'Super Administrator'),(353,1,55,'Super Administrator'),(354,1,87,'Super Administrator'),(355,1,47,'Super Administrator'),(356,1,48,'Super Administrator'),(357,1,49,'Super Administrator'),(358,1,59,'Super Administrator'),(359,1,60,'Super Administrator'),(360,1,26,'Super Administrator'),(361,1,27,'Super Administrator'),(362,1,28,'Super Administrator'),(363,1,29,'Super Administrator'),(364,1,30,'Super Administrator'),(365,1,31,'Super Administrator'),(366,1,44,'Super Administrator'),(367,1,81,'Super Administrator'),(368,1,82,'Super Administrator'),(369,1,83,'Super Administrator'),(370,1,91,'Super Administrator'),(371,4,7,'Super Administrator'),(372,4,8,'Super Administrator'),(373,4,67,'Super Administrator'),(374,4,68,'Super Administrator'),(375,4,69,'Super Administrator'),(376,4,63,'Super Administrator'),(377,4,70,'Super Administrator'),(378,4,9,'Super Administrator'),(379,4,64,'Super Administrator'),(380,4,58,'Super Administrator'),(381,4,72,'Super Administrator'),(382,4,42,'Super Administrator'),(383,4,25,'Super Administrator'),(384,4,46,'Super Administrator'),(385,4,16,'Super Administrator'),(386,4,78,'Super Administrator'),(387,4,79,'Super Administrator'),(388,4,80,'Super Administrator'),(389,4,74,'Super Administrator'),(390,4,56,'Super Administrator'),(391,4,89,'Super Administrator'),(392,4,84,'Super Administrator'),(393,4,34,'Super Administrator'),(394,4,35,'Super Administrator'),(395,4,36,'Super Administrator'),(396,4,39,'Super Administrator'),(397,4,40,'Super Administrator'),(398,4,85,'Super Administrator'),(399,4,37,'Super Administrator'),(400,4,41,'Super Administrator'),(401,4,88,'Super Administrator'),(402,4,77,'Super Administrator'),(403,4,1,'Super Administrator'),(404,4,3,'Super Administrator'),(405,4,17,'Super Administrator'),(406,4,19,'Super Administrator'),(407,4,4,'Super Administrator'),(408,4,2,'Super Administrator'),(409,4,20,'Super Administrator'),(410,4,10,'Super Administrator'),(411,4,21,'Super Administrator'),(412,4,12,'Super Administrator'),(413,4,65,'Super Administrator'),(414,4,13,'Super Administrator'),(415,4,14,'Super Administrator'),(416,4,15,'Super Administrator'),(417,4,5,'Super Administrator'),(418,4,32,'Super Administrator'),(419,4,43,'Super Administrator'),(420,4,45,'Super Administrator'),(421,4,73,'Super Administrator'),(422,4,66,'Super Administrator'),(423,4,51,'Super Administrator'),(424,4,52,'Super Administrator'),(425,4,53,'Super Administrator'),(426,4,54,'Super Administrator'),(427,4,55,'Super Administrator'),(428,4,87,'Super Administrator'),(429,4,47,'Super Administrator'),(430,4,48,'Super Administrator'),(431,4,49,'Super Administrator'),(432,4,59,'Super Administrator'),(433,4,60,'Super Administrator'),(434,4,26,'Super Administrator'),(435,4,27,'Super Administrator'),(436,4,28,'Super Administrator'),(437,4,29,'Super Administrator'),(438,4,30,'Super Administrator'),(439,4,31,'Super Administrator'),(440,4,44,'Super Administrator'),(441,4,81,'Super Administrator'),(442,4,82,'Super Administrator'),(443,4,83,'Super Administrator'),(444,4,91,'Super Administrator'),(445,2,7,'Super Administrator'),(446,2,8,'Super Administrator'),(447,2,67,'Super Administrator'),(448,2,68,'Super Administrator'),(449,2,69,'Super Administrator'),(450,2,63,'Super Administrator'),(451,2,70,'Super Administrator'),(452,2,9,'Super Administrator'),(453,2,64,'Super Administrator'),(454,2,58,'Super Administrator'),(455,2,72,'Super Administrator'),(456,2,42,'Super Administrator'),(457,2,25,'Super Administrator'),(458,2,46,'Super Administrator'),(459,2,16,'Super Administrator'),(460,2,78,'Super Administrator'),(461,2,79,'Super Administrator'),(462,2,80,'Super Administrator'),(463,2,74,'Super Administrator'),(464,2,56,'Super Administrator'),(465,2,89,'Super Administrator'),(466,2,84,'Super Administrator'),(467,2,34,'Super Administrator'),(468,2,35,'Super Administrator'),(469,2,36,'Super Administrator'),(470,2,39,'Super Administrator'),(471,2,40,'Super Administrator'),(472,2,85,'Super Administrator'),(473,2,37,'Super Administrator'),(474,2,41,'Super Administrator'),(475,2,88,'Super Administrator'),(476,2,77,'Super Administrator'),(477,2,1,'Super Administrator'),(478,2,3,'Super Administrator'),(479,2,17,'Super Administrator'),(480,2,19,'Super Administrator'),(481,2,4,'Super Administrator'),(482,2,2,'Super Administrator'),(483,2,20,'Super Administrator'),(484,2,10,'Super Administrator'),(485,2,21,'Super Administrator'),(486,2,12,'Super Administrator'),(487,2,65,'Super Administrator'),(488,2,13,'Super Administrator'),(489,2,14,'Super Administrator'),(490,2,15,'Super Administrator'),(491,2,5,'Super Administrator'),(492,2,32,'Super Administrator'),(493,2,43,'Super Administrator'),(494,2,45,'Super Administrator'),(495,2,73,'Super Administrator'),(496,2,66,'Super Administrator'),(497,2,51,'Super Administrator'),(498,2,52,'Super Administrator'),(499,2,53,'Super Administrator'),(500,2,54,'Super Administrator'),(501,2,55,'Super Administrator'),(502,2,87,'Super Administrator'),(503,2,47,'Super Administrator'),(504,2,48,'Super Administrator'),(505,2,49,'Super Administrator'),(506,2,59,'Super Administrator'),(507,2,60,'Super Administrator'),(508,2,26,'Super Administrator'),(509,2,27,'Super Administrator'),(510,2,28,'Super Administrator'),(511,2,29,'Super Administrator'),(512,2,30,'Super Administrator'),(513,2,31,'Super Administrator'),(514,2,44,'Super Administrator'),(515,2,81,'Super Administrator'),(516,2,82,'Super Administrator'),(517,2,83,'Super Administrator'),(518,2,91,'Super Administrator'),(519,5,7,'Administrator'),(520,5,8,'Administrator'),(521,5,67,'Administrator'),(522,5,68,'Administrator'),(523,5,69,'Administrator'),(524,5,63,'Administrator'),(525,5,70,'Administrator'),(526,5,9,'Administrator'),(527,5,64,'Administrator'),(528,5,58,'Administrator'),(529,5,72,'Administrator'),(530,5,42,'Administrator'),(531,5,25,'Administrator'),(532,5,46,'Administrator'),(533,5,16,'Administrator'),(534,5,78,'Administrator'),(535,5,79,'Administrator'),(536,5,80,'Administrator'),(537,5,74,'Administrator'),(538,5,56,'Administrator'),(539,5,89,'Administrator'),(540,5,84,'Administrator'),(541,5,34,'Administrator'),(542,5,35,'Administrator'),(543,5,36,'Administrator'),(544,5,39,'Administrator'),(545,5,40,'Administrator'),(546,5,85,'Administrator'),(547,5,37,'Administrator'),(548,5,41,'Administrator'),(549,5,88,'Administrator'),(550,5,77,'Administrator'),(551,5,1,'Administrator'),(552,5,17,'Administrator'),(553,5,19,'Administrator'),(554,5,4,'Administrator'),(555,5,2,'Administrator'),(556,5,20,'Administrator'),(557,5,10,'Administrator'),(558,5,21,'Administrator'),(559,5,12,'Administrator'),(560,5,65,'Administrator'),(561,5,13,'Administrator'),(562,5,14,'Administrator'),(563,5,15,'Administrator'),(564,5,5,'Administrator'),(565,5,32,'Administrator'),(566,5,43,'Administrator'),(567,5,45,'Administrator'),(568,5,73,'Administrator'),(569,5,66,'Administrator'),(570,5,51,'Administrator'),(571,5,52,'Administrator'),(572,5,53,'Administrator'),(573,5,54,'Administrator'),(574,5,55,'Administrator'),(575,5,87,'Administrator'),(576,5,47,'Administrator'),(577,5,48,'Administrator'),(578,5,49,'Administrator'),(579,5,59,'Administrator'),(580,5,60,'Administrator'),(581,5,26,'Administrator'),(582,5,27,'Administrator'),(583,5,28,'Administrator'),(584,5,29,'Administrator'),(585,5,30,'Administrator'),(586,5,31,'Administrator'),(587,5,44,'Administrator'),(588,5,81,'Administrator'),(589,5,82,'Administrator'),(590,5,83,'Administrator'),(591,5,91,'Administrator'),(592,7,7,'Administrator'),(593,7,8,'Administrator'),(594,7,67,'Administrator'),(595,7,68,'Administrator'),(596,7,69,'Administrator'),(597,7,63,'Administrator'),(598,7,70,'Administrator'),(599,7,9,'Administrator'),(600,7,64,'Administrator'),(601,7,58,'Administrator'),(602,7,72,'Administrator'),(603,7,42,'Administrator'),(604,7,25,'Administrator'),(605,7,46,'Administrator'),(606,7,16,'Administrator'),(607,7,78,'Administrator'),(608,7,79,'Administrator'),(609,7,80,'Administrator'),(610,7,74,'Administrator'),(611,7,56,'Administrator'),(612,7,89,'Administrator'),(613,7,84,'Administrator'),(614,7,34,'Administrator'),(615,7,35,'Administrator'),(616,7,36,'Administrator'),(617,7,39,'Administrator'),(618,7,40,'Administrator'),(619,7,85,'Administrator'),(620,7,37,'Administrator'),(621,7,41,'Administrator'),(622,7,88,'Administrator'),(623,7,77,'Administrator'),(624,7,1,'Administrator'),(625,7,17,'Administrator'),(626,7,19,'Administrator'),(627,7,4,'Administrator'),(628,7,2,'Administrator'),(629,7,20,'Administrator'),(630,7,10,'Administrator'),(631,7,21,'Administrator'),(632,7,12,'Administrator'),(633,7,65,'Administrator'),(634,7,13,'Administrator'),(635,7,14,'Administrator'),(636,7,15,'Administrator'),(637,7,5,'Administrator'),(638,7,32,'Administrator'),(639,7,43,'Administrator'),(640,7,45,'Administrator'),(641,7,73,'Administrator'),(642,7,66,'Administrator'),(643,7,51,'Administrator'),(644,7,52,'Administrator'),(645,7,53,'Administrator'),(646,7,54,'Administrator'),(647,7,55,'Administrator'),(648,7,87,'Administrator'),(649,7,47,'Administrator'),(650,7,48,'Administrator'),(651,7,49,'Administrator'),(652,7,59,'Administrator'),(653,7,60,'Administrator'),(654,7,26,'Administrator'),(655,7,27,'Administrator'),(656,7,28,'Administrator'),(657,7,29,'Administrator'),(658,7,30,'Administrator'),(659,7,31,'Administrator'),(660,7,44,'Administrator'),(661,7,81,'Administrator'),(662,7,82,'Administrator'),(663,7,83,'Administrator'),(664,7,91,'Administrator'),(665,6,7,'Administrator'),(666,6,8,'Administrator'),(667,6,67,'Administrator'),(668,6,68,'Administrator'),(669,6,69,'Administrator'),(670,6,63,'Administrator'),(671,6,70,'Administrator'),(672,6,9,'Administrator'),(673,6,64,'Administrator'),(674,6,58,'Administrator'),(675,6,72,'Administrator'),(676,6,42,'Administrator'),(677,6,25,'Administrator'),(678,6,46,'Administrator'),(679,6,16,'Administrator'),(680,6,78,'Administrator'),(681,6,79,'Administrator'),(682,6,80,'Administrator'),(683,6,74,'Administrator'),(684,6,56,'Administrator'),(685,6,89,'Administrator'),(686,6,84,'Administrator'),(687,6,34,'Administrator'),(688,6,35,'Administrator'),(689,6,36,'Administrator'),(690,6,39,'Administrator'),(691,6,40,'Administrator'),(692,6,85,'Administrator'),(693,6,37,'Administrator'),(694,6,41,'Administrator'),(695,6,88,'Administrator'),(696,6,77,'Administrator'),(697,6,1,'Administrator'),(698,6,17,'Administrator'),(699,6,19,'Administrator'),(700,6,4,'Administrator'),(701,6,2,'Administrator'),(702,6,20,'Administrator'),(703,6,10,'Administrator'),(704,6,21,'Administrator'),(705,6,12,'Administrator'),(706,6,65,'Administrator'),(707,6,13,'Administrator'),(708,6,14,'Administrator'),(709,6,15,'Administrator'),(710,6,5,'Administrator'),(711,6,32,'Administrator'),(712,6,43,'Administrator'),(713,6,45,'Administrator'),(714,6,73,'Administrator'),(715,6,66,'Administrator'),(716,6,51,'Administrator'),(717,6,52,'Administrator'),(718,6,53,'Administrator'),(719,6,54,'Administrator'),(720,6,55,'Administrator'),(721,6,87,'Administrator'),(722,6,47,'Administrator'),(723,6,48,'Administrator'),(724,6,49,'Administrator'),(725,6,59,'Administrator'),(726,6,60,'Administrator'),(727,6,26,'Administrator'),(728,6,27,'Administrator'),(729,6,28,'Administrator'),(730,6,29,'Administrator'),(731,6,30,'Administrator'),(732,6,31,'Administrator'),(733,6,44,'Administrator'),(734,6,81,'Administrator'),(735,6,82,'Administrator'),(736,6,83,'Administrator'),(737,6,91,'Administrator'),(738,3,7,'Administrator'),(739,3,8,'Administrator'),(740,3,67,'Administrator'),(741,3,68,'Administrator'),(742,3,69,'Administrator'),(743,3,63,'Administrator'),(744,3,70,'Administrator'),(745,3,9,'Administrator'),(746,3,64,'Administrator'),(747,3,58,'Administrator'),(748,3,72,'Administrator'),(749,3,42,'Administrator'),(750,3,25,'Administrator'),(751,3,46,'Administrator'),(752,3,16,'Administrator'),(753,3,78,'Administrator'),(754,3,79,'Administrator'),(755,3,80,'Administrator'),(756,3,74,'Administrator'),(757,3,56,'Administrator'),(758,3,89,'Administrator'),(759,3,84,'Administrator'),(760,3,34,'Administrator'),(761,3,35,'Administrator'),(762,3,36,'Administrator'),(763,3,39,'Administrator'),(764,3,40,'Administrator'),(765,3,85,'Administrator'),(766,3,37,'Administrator'),(767,3,41,'Administrator'),(768,3,88,'Administrator'),(769,3,77,'Administrator'),(770,3,1,'Administrator'),(771,3,17,'Administrator'),(772,3,19,'Administrator'),(773,3,4,'Administrator'),(774,3,2,'Administrator'),(775,3,20,'Administrator'),(776,3,10,'Administrator'),(777,3,21,'Administrator'),(778,3,12,'Administrator'),(779,3,65,'Administrator'),(780,3,13,'Administrator'),(781,3,14,'Administrator'),(782,3,15,'Administrator'),(783,3,5,'Administrator'),(784,3,32,'Administrator'),(785,3,43,'Administrator'),(786,3,45,'Administrator'),(787,3,73,'Administrator'),(788,3,66,'Administrator'),(789,3,51,'Administrator'),(790,3,52,'Administrator'),(791,3,53,'Administrator'),(792,3,54,'Administrator'),(793,3,55,'Administrator'),(794,3,87,'Administrator'),(795,3,47,'Administrator'),(796,3,48,'Administrator'),(797,3,49,'Administrator'),(798,3,59,'Administrator'),(799,3,60,'Administrator'),(800,3,26,'Administrator'),(801,3,27,'Administrator'),(802,3,28,'Administrator'),(803,3,29,'Administrator'),(804,3,30,'Administrator'),(805,3,31,'Administrator'),(806,3,44,'Administrator'),(807,3,81,'Administrator'),(808,3,82,'Administrator'),(809,3,83,'Administrator'),(810,3,91,'Administrator'),(811,1,7,'Administrator'),(812,1,8,'Administrator'),(813,1,67,'Administrator'),(814,1,68,'Administrator'),(815,1,69,'Administrator'),(816,1,63,'Administrator'),(817,1,70,'Administrator'),(818,1,9,'Administrator'),(819,1,64,'Administrator'),(820,1,58,'Administrator'),(821,1,72,'Administrator'),(822,1,42,'Administrator'),(823,1,25,'Administrator'),(824,1,46,'Administrator'),(825,1,16,'Administrator'),(826,1,78,'Administrator'),(827,1,79,'Administrator'),(828,1,80,'Administrator'),(829,1,74,'Administrator'),(830,1,56,'Administrator'),(831,1,89,'Administrator'),(832,1,84,'Administrator'),(833,1,34,'Administrator'),(834,1,35,'Administrator'),(835,1,36,'Administrator'),(836,1,39,'Administrator'),(837,1,40,'Administrator'),(838,1,85,'Administrator'),(839,1,37,'Administrator'),(840,1,41,'Administrator'),(841,1,88,'Administrator'),(842,1,77,'Administrator'),(843,1,1,'Administrator'),(844,1,17,'Administrator'),(845,1,19,'Administrator'),(846,1,4,'Administrator'),(847,1,2,'Administrator'),(848,1,20,'Administrator'),(849,1,10,'Administrator'),(850,1,21,'Administrator'),(851,1,12,'Administrator'),(852,1,65,'Administrator'),(853,1,13,'Administrator'),(854,1,14,'Administrator'),(855,1,15,'Administrator'),(856,1,5,'Administrator'),(857,1,32,'Administrator'),(858,1,43,'Administrator'),(859,1,45,'Administrator'),(860,1,73,'Administrator'),(861,1,66,'Administrator'),(862,1,51,'Administrator'),(863,1,52,'Administrator'),(864,1,53,'Administrator'),(865,1,54,'Administrator'),(866,1,55,'Administrator'),(867,1,87,'Administrator'),(868,1,47,'Administrator'),(869,1,48,'Administrator'),(870,1,49,'Administrator'),(871,1,59,'Administrator'),(872,1,60,'Administrator'),(873,1,26,'Administrator'),(874,1,27,'Administrator'),(875,1,28,'Administrator'),(876,1,29,'Administrator'),(877,1,30,'Administrator'),(878,1,31,'Administrator'),(879,1,44,'Administrator'),(880,1,81,'Administrator'),(881,1,82,'Administrator'),(882,1,83,'Administrator'),(883,1,91,'Administrator'),(884,4,7,'Administrator'),(885,4,8,'Administrator'),(886,4,67,'Administrator'),(887,4,68,'Administrator'),(888,4,69,'Administrator'),(889,4,63,'Administrator'),(890,4,70,'Administrator'),(891,4,9,'Administrator'),(892,4,64,'Administrator'),(893,4,58,'Administrator'),(894,4,72,'Administrator'),(895,4,42,'Administrator'),(896,4,25,'Administrator'),(897,4,46,'Administrator'),(898,4,16,'Administrator'),(899,4,78,'Administrator'),(900,4,79,'Administrator'),(901,4,80,'Administrator'),(902,4,74,'Administrator'),(903,4,56,'Administrator'),(904,4,89,'Administrator'),(905,4,84,'Administrator'),(906,4,34,'Administrator'),(907,4,35,'Administrator'),(908,4,36,'Administrator'),(909,4,39,'Administrator'),(910,4,40,'Administrator'),(911,4,85,'Administrator'),(912,4,37,'Administrator'),(913,4,41,'Administrator'),(914,4,88,'Administrator'),(915,4,77,'Administrator'),(916,4,1,'Administrator'),(917,4,17,'Administrator'),(918,4,19,'Administrator'),(919,4,4,'Administrator'),(920,4,2,'Administrator'),(921,4,20,'Administrator'),(922,4,10,'Administrator'),(923,4,21,'Administrator'),(924,4,12,'Administrator'),(925,4,65,'Administrator'),(926,4,13,'Administrator'),(927,4,14,'Administrator'),(928,4,15,'Administrator'),(929,4,5,'Administrator'),(930,4,32,'Administrator'),(931,4,43,'Administrator'),(932,4,45,'Administrator'),(933,4,73,'Administrator'),(934,4,66,'Administrator'),(935,4,51,'Administrator'),(936,4,52,'Administrator'),(937,4,53,'Administrator'),(938,4,54,'Administrator'),(939,4,55,'Administrator'),(940,4,87,'Administrator'),(941,4,47,'Administrator'),(942,4,48,'Administrator'),(943,4,49,'Administrator'),(944,4,59,'Administrator'),(945,4,60,'Administrator'),(946,4,26,'Administrator'),(947,4,27,'Administrator'),(948,4,28,'Administrator'),(949,4,29,'Administrator'),(950,4,30,'Administrator'),(951,4,31,'Administrator'),(952,4,44,'Administrator'),(953,4,81,'Administrator'),(954,4,82,'Administrator'),(955,4,83,'Administrator'),(956,4,91,'Administrator'),(957,2,7,'Administrator'),(958,2,8,'Administrator'),(959,2,67,'Administrator'),(960,2,68,'Administrator'),(961,2,69,'Administrator'),(962,2,63,'Administrator'),(963,2,70,'Administrator'),(964,2,9,'Administrator'),(965,2,64,'Administrator'),(966,2,58,'Administrator'),(967,2,72,'Administrator'),(968,2,42,'Administrator'),(969,2,25,'Administrator'),(970,2,46,'Administrator'),(971,2,16,'Administrator'),(972,2,78,'Administrator'),(973,2,79,'Administrator'),(974,2,80,'Administrator'),(975,2,74,'Administrator'),(976,2,56,'Administrator'),(977,2,89,'Administrator'),(978,2,84,'Administrator'),(979,2,34,'Administrator'),(980,2,35,'Administrator'),(981,2,36,'Administrator'),(982,2,39,'Administrator'),(983,2,40,'Administrator'),(984,2,85,'Administrator'),(985,2,37,'Administrator'),(986,2,41,'Administrator'),(987,2,88,'Administrator'),(988,2,77,'Administrator'),(989,2,1,'Administrator'),(990,2,17,'Administrator'),(991,2,19,'Administrator'),(992,2,4,'Administrator'),(993,2,2,'Administrator'),(994,2,20,'Administrator'),(995,2,10,'Administrator'),(996,2,21,'Administrator'),(997,2,12,'Administrator'),(998,2,65,'Administrator'),(999,2,13,'Administrator'),(1000,2,14,'Administrator'),(1001,2,15,'Administrator'),(1002,2,5,'Administrator'),(1003,2,32,'Administrator'),(1004,2,43,'Administrator'),(1005,2,45,'Administrator'),(1006,2,73,'Administrator'),(1007,2,66,'Administrator'),(1008,2,51,'Administrator'),(1009,2,52,'Administrator'),(1010,2,53,'Administrator'),(1011,2,54,'Administrator'),(1012,2,55,'Administrator'),(1013,2,87,'Administrator'),(1014,2,47,'Administrator'),(1015,2,48,'Administrator'),(1016,2,49,'Administrator'),(1017,2,59,'Administrator'),(1018,2,60,'Administrator'),(1019,2,26,'Administrator'),(1020,2,27,'Administrator'),(1021,2,28,'Administrator'),(1022,2,29,'Administrator'),(1023,2,30,'Administrator'),(1024,2,31,'Administrator'),(1025,2,44,'Administrator'),(1026,2,81,'Administrator'),(1027,2,82,'Administrator'),(1028,2,83,'Administrator'),(1029,2,91,'Administrator'),(1030,5,7,'Finance'),(1031,5,8,'Finance'),(1032,5,63,'Finance'),(1033,5,9,'Finance'),(1034,5,58,'Finance'),(1035,5,72,'Finance'),(1036,5,42,'Finance'),(1037,5,25,'Finance'),(1038,5,46,'Finance'),(1039,5,16,'Finance'),(1040,5,78,'Finance'),(1041,5,79,'Finance'),(1042,5,80,'Finance'),(1043,5,74,'Finance'),(1044,5,56,'Finance'),(1045,5,89,'Finance'),(1046,5,84,'Finance'),(1047,5,34,'Finance'),(1048,5,35,'Finance'),(1049,5,36,'Finance'),(1050,5,39,'Finance'),(1051,5,40,'Finance'),(1052,5,85,'Finance'),(1053,5,37,'Finance'),(1054,5,41,'Finance'),(1055,5,88,'Finance'),(1056,5,77,'Finance'),(1057,5,1,'Finance'),(1058,5,17,'Finance'),(1059,5,87,'Finance'),(1060,5,47,'Finance'),(1061,5,48,'Finance'),(1062,5,49,'Finance'),(1063,5,59,'Finance'),(1064,5,60,'Finance'),(1065,5,26,'Finance'),(1066,5,27,'Finance'),(1067,5,28,'Finance'),(1068,5,29,'Finance'),(1069,5,30,'Finance'),(1070,5,31,'Finance'),(1071,5,44,'Finance'),(1072,5,81,'Finance'),(1073,5,82,'Finance'),(1074,5,83,'Finance'),(1075,5,91,'Finance'),(1076,5,7,'General Manager'),(1077,5,8,'General Manager'),(1078,5,63,'General Manager'),(1079,5,9,'General Manager'),(1080,5,64,'General Manager'),(1081,5,16,'General Manager'),(1082,5,78,'General Manager'),(1083,5,79,'General Manager'),(1084,5,80,'General Manager'),(1085,5,74,'General Manager'),(1086,5,56,'General Manager'),(1087,5,89,'General Manager'),(1088,5,84,'General Manager'),(1089,5,34,'General Manager'),(1090,5,35,'General Manager'),(1091,5,36,'General Manager'),(1092,5,39,'General Manager'),(1093,5,40,'General Manager'),(1094,5,85,'General Manager'),(1095,5,37,'General Manager'),(1096,5,41,'General Manager'),(1097,5,77,'General Manager'),(1098,5,1,'General Manager'),(1099,5,17,'General Manager'),(1100,5,81,'General Manager'),(1101,5,82,'General Manager'),(1102,5,83,'General Manager'),(1103,5,91,'General Manager'),(1104,7,7,'General Manager'),(1105,7,8,'General Manager'),(1106,7,63,'General Manager'),(1107,7,9,'General Manager'),(1108,7,64,'General Manager'),(1109,7,16,'General Manager'),(1110,7,78,'General Manager'),(1111,7,79,'General Manager'),(1112,7,80,'General Manager'),(1113,7,74,'General Manager'),(1114,7,56,'General Manager'),(1115,7,89,'General Manager'),(1116,7,84,'General Manager'),(1117,7,34,'General Manager'),(1118,7,35,'General Manager'),(1119,7,36,'General Manager'),(1120,7,39,'General Manager'),(1121,7,40,'General Manager'),(1122,7,85,'General Manager'),(1123,7,37,'General Manager'),(1124,7,41,'General Manager'),(1125,7,77,'General Manager'),(1126,7,1,'General Manager'),(1127,7,17,'General Manager'),(1128,7,81,'General Manager'),(1129,7,82,'General Manager'),(1130,7,83,'General Manager'),(1131,7,91,'General Manager'),(1132,6,7,'General Manager'),(1133,6,8,'General Manager'),(1134,6,63,'General Manager'),(1135,6,9,'General Manager'),(1136,6,64,'General Manager'),(1137,6,16,'General Manager'),(1138,6,78,'General Manager'),(1139,6,79,'General Manager'),(1140,6,80,'General Manager'),(1141,6,74,'General Manager'),(1142,6,56,'General Manager'),(1143,6,89,'General Manager'),(1144,6,84,'General Manager'),(1145,6,34,'General Manager'),(1146,6,35,'General Manager'),(1147,6,36,'General Manager'),(1148,6,39,'General Manager'),(1149,6,40,'General Manager'),(1150,6,85,'General Manager'),(1151,6,37,'General Manager'),(1152,6,41,'General Manager'),(1153,6,77,'General Manager'),(1154,6,1,'General Manager'),(1155,6,17,'General Manager'),(1156,6,81,'General Manager'),(1157,6,82,'General Manager'),(1158,6,83,'General Manager'),(1159,6,91,'General Manager'),(1160,3,7,'General Manager'),(1161,3,8,'General Manager'),(1162,3,63,'General Manager'),(1163,3,9,'General Manager'),(1164,3,64,'General Manager'),(1165,3,16,'General Manager'),(1166,3,78,'General Manager'),(1167,3,79,'General Manager'),(1168,3,80,'General Manager'),(1169,3,74,'General Manager'),(1170,3,56,'General Manager'),(1171,3,89,'General Manager'),(1172,3,84,'General Manager'),(1173,3,34,'General Manager'),(1174,3,35,'General Manager'),(1175,3,36,'General Manager'),(1176,3,39,'General Manager'),(1177,3,40,'General Manager'),(1178,3,85,'General Manager'),(1179,3,37,'General Manager'),(1180,3,41,'General Manager'),(1181,3,77,'General Manager'),(1182,3,1,'General Manager'),(1183,3,17,'General Manager'),(1184,3,81,'General Manager'),(1185,3,82,'General Manager'),(1186,3,83,'General Manager'),(1187,3,91,'General Manager'),(1188,1,7,'General Manager'),(1189,1,8,'General Manager'),(1190,1,63,'General Manager'),(1191,1,9,'General Manager'),(1192,1,64,'General Manager'),(1193,1,16,'General Manager'),(1194,1,78,'General Manager'),(1195,1,79,'General Manager'),(1196,1,80,'General Manager'),(1197,1,74,'General Manager'),(1198,1,56,'General Manager'),(1199,1,89,'General Manager'),(1200,1,84,'General Manager'),(1201,1,34,'General Manager'),(1202,1,35,'General Manager'),(1203,1,36,'General Manager'),(1204,1,39,'General Manager'),(1205,1,40,'General Manager'),(1206,1,85,'General Manager'),(1207,1,37,'General Manager'),(1208,1,41,'General Manager'),(1209,1,77,'General Manager'),(1210,1,1,'General Manager'),(1211,1,17,'General Manager'),(1212,1,81,'General Manager'),(1213,1,82,'General Manager'),(1214,1,83,'General Manager'),(1215,1,91,'General Manager'),(1216,4,7,'General Manager'),(1217,4,8,'General Manager'),(1218,4,63,'General Manager'),(1219,4,9,'General Manager'),(1220,4,64,'General Manager'),(1221,4,16,'General Manager'),(1222,4,78,'General Manager'),(1223,4,79,'General Manager'),(1224,4,80,'General Manager'),(1225,4,74,'General Manager'),(1226,4,56,'General Manager'),(1227,4,89,'General Manager'),(1228,4,84,'General Manager'),(1229,4,34,'General Manager'),(1230,4,35,'General Manager'),(1231,4,36,'General Manager'),(1232,4,39,'General Manager'),(1233,4,40,'General Manager'),(1234,4,85,'General Manager'),(1235,4,37,'General Manager'),(1236,4,41,'General Manager'),(1237,4,77,'General Manager'),(1238,4,1,'General Manager'),(1239,4,17,'General Manager'),(1240,4,81,'General Manager'),(1241,4,82,'General Manager'),(1242,4,83,'General Manager'),(1243,4,91,'General Manager'),(1244,2,7,'General Manager'),(1245,2,8,'General Manager'),(1246,2,63,'General Manager'),(1247,2,9,'General Manager'),(1248,2,64,'General Manager'),(1249,2,16,'General Manager'),(1250,2,78,'General Manager'),(1251,2,79,'General Manager'),(1252,2,80,'General Manager'),(1253,2,74,'General Manager'),(1254,2,56,'General Manager'),(1255,2,89,'General Manager'),(1256,2,84,'General Manager'),(1257,2,34,'General Manager'),(1258,2,35,'General Manager'),(1259,2,36,'General Manager'),(1260,2,39,'General Manager'),(1261,2,40,'General Manager'),(1262,2,85,'General Manager'),(1263,2,37,'General Manager'),(1264,2,41,'General Manager'),(1265,2,77,'General Manager'),(1266,2,1,'General Manager'),(1267,2,17,'General Manager'),(1268,2,81,'General Manager'),(1269,2,82,'General Manager'),(1270,2,83,'General Manager'),(1271,2,91,'General Manager'),(1272,5,7,'Payroll Master'),(1273,5,8,'Payroll Master'),(1274,5,63,'Payroll Master'),(1275,5,9,'Payroll Master'),(1276,5,58,'Payroll Master'),(1277,5,72,'Payroll Master'),(1278,5,42,'Payroll Master'),(1279,5,25,'Payroll Master'),(1280,5,46,'Payroll Master'),(1281,5,16,'Payroll Master'),(1282,5,74,'Payroll Master'),(1283,5,56,'Payroll Master'),(1284,5,89,'Payroll Master'),(1285,5,84,'Payroll Master'),(1286,5,34,'Payroll Master'),(1287,5,35,'Payroll Master'),(1288,5,36,'Payroll Master'),(1289,5,39,'Payroll Master'),(1290,5,40,'Payroll Master'),(1291,5,85,'Payroll Master'),(1292,5,37,'Payroll Master'),(1293,5,41,'Payroll Master'),(1294,5,88,'Payroll Master'),(1295,5,77,'Payroll Master'),(1296,5,1,'Payroll Master'),(1297,5,17,'Payroll Master'),(1298,5,20,'Payroll Master'),(1299,5,10,'Payroll Master'),(1300,5,21,'Payroll Master'),(1301,5,87,'Payroll Master'),(1302,5,47,'Payroll Master'),(1303,5,48,'Payroll Master'),(1304,5,49,'Payroll Master'),(1305,5,59,'Payroll Master'),(1306,5,60,'Payroll Master'),(1307,5,26,'Payroll Master'),(1308,5,27,'Payroll Master'),(1309,5,28,'Payroll Master'),(1310,5,29,'Payroll Master'),(1311,5,30,'Payroll Master'),(1312,5,31,'Payroll Master'),(1313,5,44,'Payroll Master'),(1314,5,81,'Payroll Master'),(1315,5,82,'Payroll Master'),(1316,5,83,'Payroll Master'),(1317,5,91,'Payroll Master'),(1318,7,7,'Payroll Master'),(1319,7,8,'Payroll Master'),(1320,7,63,'Payroll Master'),(1321,7,9,'Payroll Master'),(1322,7,58,'Payroll Master'),(1323,7,72,'Payroll Master'),(1324,7,42,'Payroll Master'),(1325,7,25,'Payroll Master'),(1326,7,46,'Payroll Master'),(1327,7,16,'Payroll Master'),(1328,7,74,'Payroll Master'),(1329,7,56,'Payroll Master'),(1330,7,89,'Payroll Master'),(1331,7,84,'Payroll Master'),(1332,7,34,'Payroll Master'),(1333,7,35,'Payroll Master'),(1334,7,36,'Payroll Master'),(1335,7,39,'Payroll Master'),(1336,7,40,'Payroll Master'),(1337,7,85,'Payroll Master'),(1338,7,37,'Payroll Master'),(1339,7,41,'Payroll Master'),(1340,7,88,'Payroll Master'),(1341,7,77,'Payroll Master'),(1342,7,1,'Payroll Master'),(1343,7,17,'Payroll Master'),(1344,7,20,'Payroll Master'),(1345,7,10,'Payroll Master'),(1346,7,21,'Payroll Master'),(1347,7,87,'Payroll Master'),(1348,7,47,'Payroll Master'),(1349,7,48,'Payroll Master'),(1350,7,49,'Payroll Master'),(1351,7,59,'Payroll Master'),(1352,7,60,'Payroll Master'),(1353,7,26,'Payroll Master'),(1354,7,27,'Payroll Master'),(1355,7,28,'Payroll Master'),(1356,7,29,'Payroll Master'),(1357,7,30,'Payroll Master'),(1358,7,31,'Payroll Master'),(1359,7,44,'Payroll Master'),(1360,7,81,'Payroll Master'),(1361,7,82,'Payroll Master'),(1362,7,83,'Payroll Master'),(1363,7,91,'Payroll Master'),(1364,6,7,'Payroll Master'),(1365,6,8,'Payroll Master'),(1366,6,63,'Payroll Master'),(1367,6,9,'Payroll Master'),(1368,6,58,'Payroll Master'),(1369,6,72,'Payroll Master'),(1370,6,42,'Payroll Master'),(1371,6,25,'Payroll Master'),(1372,6,46,'Payroll Master'),(1373,6,16,'Payroll Master'),(1374,6,74,'Payroll Master'),(1375,6,56,'Payroll Master'),(1376,6,89,'Payroll Master'),(1377,6,84,'Payroll Master'),(1378,6,34,'Payroll Master'),(1379,6,35,'Payroll Master'),(1380,6,36,'Payroll Master'),(1381,6,39,'Payroll Master'),(1382,6,40,'Payroll Master'),(1383,6,85,'Payroll Master'),(1384,6,37,'Payroll Master'),(1385,6,41,'Payroll Master'),(1386,6,88,'Payroll Master'),(1387,6,77,'Payroll Master'),(1388,6,1,'Payroll Master'),(1389,6,17,'Payroll Master'),(1390,6,20,'Payroll Master'),(1391,6,10,'Payroll Master'),(1392,6,21,'Payroll Master'),(1393,6,87,'Payroll Master'),(1394,6,47,'Payroll Master'),(1395,6,48,'Payroll Master'),(1396,6,49,'Payroll Master'),(1397,6,59,'Payroll Master'),(1398,6,60,'Payroll Master'),(1399,6,26,'Payroll Master'),(1400,6,27,'Payroll Master'),(1401,6,28,'Payroll Master'),(1402,6,29,'Payroll Master'),(1403,6,30,'Payroll Master'),(1404,6,31,'Payroll Master'),(1405,6,44,'Payroll Master'),(1406,6,81,'Payroll Master'),(1407,6,82,'Payroll Master'),(1408,6,83,'Payroll Master'),(1409,6,91,'Payroll Master'),(1410,3,7,'Payroll Master'),(1411,3,8,'Payroll Master'),(1412,3,63,'Payroll Master'),(1413,3,9,'Payroll Master'),(1414,3,58,'Payroll Master'),(1415,3,72,'Payroll Master'),(1416,3,42,'Payroll Master'),(1417,3,25,'Payroll Master'),(1418,3,46,'Payroll Master'),(1419,3,16,'Payroll Master'),(1420,3,74,'Payroll Master'),(1421,3,56,'Payroll Master'),(1422,3,89,'Payroll Master'),(1423,3,84,'Payroll Master'),(1424,3,34,'Payroll Master'),(1425,3,35,'Payroll Master'),(1426,3,36,'Payroll Master'),(1427,3,39,'Payroll Master'),(1428,3,40,'Payroll Master'),(1429,3,85,'Payroll Master'),(1430,3,37,'Payroll Master'),(1431,3,41,'Payroll Master'),(1432,3,88,'Payroll Master'),(1433,3,77,'Payroll Master'),(1434,3,1,'Payroll Master'),(1435,3,17,'Payroll Master'),(1436,3,20,'Payroll Master'),(1437,3,10,'Payroll Master'),(1438,3,21,'Payroll Master'),(1439,3,87,'Payroll Master'),(1440,3,47,'Payroll Master'),(1441,3,48,'Payroll Master'),(1442,3,49,'Payroll Master'),(1443,3,59,'Payroll Master'),(1444,3,60,'Payroll Master'),(1445,3,26,'Payroll Master'),(1446,3,27,'Payroll Master'),(1447,3,28,'Payroll Master'),(1448,3,29,'Payroll Master'),(1449,3,30,'Payroll Master'),(1450,3,31,'Payroll Master'),(1451,3,44,'Payroll Master'),(1452,3,81,'Payroll Master'),(1453,3,82,'Payroll Master'),(1454,3,83,'Payroll Master'),(1455,3,91,'Payroll Master');
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
INSERT INTO `app_usertype` VALUES ('Administrator','Administrator',20,1,'a:73:{i:7;s:1:\"7\";i:8;s:1:\"8\";i:67;s:2:\"67\";i:68;s:2:\"68\";i:69;s:2:\"69\";i:63;s:2:\"63\";i:70;s:2:\"70\";i:9;s:1:\"9\";i:64;s:2:\"64\";i:58;s:2:\"58\";i:72;s:2:\"72\";i:42;s:2:\"42\";i:25;s:2:\"25\";i:46;s:2:\"46\";i:16;s:2:\"16\";i:78;s:2:\"78\";i:79;s:2:\"79\";i:80;s:2:\"80\";i:74;s:2:\"74\";i:56;s:2:\"56\";i:89;s:2:\"89\";i:84;s:2:\"84\";i:34;s:2:\"34\";i:35;s:2:\"35\";i:36;s:2:\"36\";i:39;s:2:\"39\";i:40;s:2:\"40\";i:85;s:2:\"85\";i:37;s:2:\"37\";i:41;s:2:\"41\";i:88;s:2:\"88\";i:77;s:2:\"77\";i:1;s:1:\"1\";i:17;s:2:\"17\";i:19;s:2:\"19\";i:4;s:1:\"4\";i:2;s:1:\"2\";i:20;s:2:\"20\";i:10;s:2:\"10\";i:21;s:2:\"21\";i:12;s:2:\"12\";i:65;s:2:\"65\";i:13;s:2:\"13\";i:14;s:2:\"14\";i:15;s:2:\"15\";i:5;s:1:\"5\";i:32;s:2:\"32\";i:43;s:2:\"43\";i:45;s:2:\"45\";i:73;s:2:\"73\";i:66;s:2:\"66\";i:51;s:2:\"51\";i:52;s:2:\"52\";i:53;s:2:\"53\";i:54;s:2:\"54\";i:55;s:2:\"55\";i:87;s:2:\"87\";i:47;s:2:\"47\";i:48;s:2:\"48\";i:49;s:2:\"49\";i:59;s:2:\"59\";i:60;s:2:\"60\";i:26;s:2:\"26\";i:27;s:2:\"27\";i:28;s:2:\"28\";i:29;s:2:\"29\";i:30;s:2:\"30\";i:31;s:2:\"31\";i:44;s:2:\"44\";i:81;s:2:\"81\";i:82;s:2:\"82\";i:83;s:2:\"83\";i:91;s:2:\"91\";}','a:7:{i:0;s:1:\"5\";i:1;s:1:\"7\";i:2;s:1:\"6\";i:3;s:1:\"3\";i:4;s:1:\"1\";i:5;s:1:\"4\";i:6;s:1:\"2\";}'),('Finance','Finance',30,1,'a:46:{i:7;s:1:\"7\";i:8;s:1:\"8\";i:63;s:2:\"63\";i:9;s:1:\"9\";i:58;s:2:\"58\";i:72;s:2:\"72\";i:42;s:2:\"42\";i:25;s:2:\"25\";i:46;s:2:\"46\";i:16;s:2:\"16\";i:78;s:2:\"78\";i:79;s:2:\"79\";i:80;s:2:\"80\";i:74;s:2:\"74\";i:56;s:2:\"56\";i:89;s:2:\"89\";i:84;s:2:\"84\";i:34;s:2:\"34\";i:35;s:2:\"35\";i:36;s:2:\"36\";i:39;s:2:\"39\";i:40;s:2:\"40\";i:85;s:2:\"85\";i:37;s:2:\"37\";i:41;s:2:\"41\";i:88;s:2:\"88\";i:77;s:2:\"77\";i:1;s:1:\"1\";i:17;s:2:\"17\";i:87;s:2:\"87\";i:47;s:2:\"47\";i:48;s:2:\"48\";i:49;s:2:\"49\";i:59;s:2:\"59\";i:60;s:2:\"60\";i:26;s:2:\"26\";i:27;s:2:\"27\";i:28;s:2:\"28\";i:29;s:2:\"29\";i:30;s:2:\"30\";i:31;s:2:\"31\";i:44;s:2:\"44\";i:81;s:2:\"81\";i:82;s:2:\"82\";i:83;s:2:\"83\";i:91;s:2:\"91\";}','a:1:{i:0;s:1:\"5\";}'),('General Manager','General Manager (GM)',40,1,'a:28:{i:7;s:1:\"7\";i:8;s:1:\"8\";i:63;s:2:\"63\";i:9;s:1:\"9\";i:64;s:2:\"64\";i:16;s:2:\"16\";i:78;s:2:\"78\";i:79;s:2:\"79\";i:80;s:2:\"80\";i:74;s:2:\"74\";i:56;s:2:\"56\";i:89;s:2:\"89\";i:84;s:2:\"84\";i:34;s:2:\"34\";i:35;s:2:\"35\";i:36;s:2:\"36\";i:39;s:2:\"39\";i:40;s:2:\"40\";i:85;s:2:\"85\";i:37;s:2:\"37\";i:41;s:2:\"41\";i:77;s:2:\"77\";i:1;s:1:\"1\";i:17;s:2:\"17\";i:81;s:2:\"81\";i:82;s:2:\"82\";i:83;s:2:\"83\";i:91;s:2:\"91\";}','a:7:{i:0;s:1:\"5\";i:1;s:1:\"7\";i:2;s:1:\"6\";i:3;s:1:\"3\";i:4;s:1:\"1\";i:5;s:1:\"4\";i:6;s:1:\"2\";}'),('Payroll Master','Payroll Master',50,1,'a:46:{i:7;s:1:\"7\";i:8;s:1:\"8\";i:63;s:2:\"63\";i:9;s:1:\"9\";i:58;s:2:\"58\";i:72;s:2:\"72\";i:42;s:2:\"42\";i:25;s:2:\"25\";i:46;s:2:\"46\";i:16;s:2:\"16\";i:74;s:2:\"74\";i:56;s:2:\"56\";i:89;s:2:\"89\";i:84;s:2:\"84\";i:34;s:2:\"34\";i:35;s:2:\"35\";i:36;s:2:\"36\";i:39;s:2:\"39\";i:40;s:2:\"40\";i:85;s:2:\"85\";i:37;s:2:\"37\";i:41;s:2:\"41\";i:88;s:2:\"88\";i:77;s:2:\"77\";i:1;s:1:\"1\";i:17;s:2:\"17\";i:20;s:2:\"20\";i:10;s:2:\"10\";i:21;s:2:\"21\";i:87;s:2:\"87\";i:47;s:2:\"47\";i:48;s:2:\"48\";i:49;s:2:\"49\";i:59;s:2:\"59\";i:60;s:2:\"60\";i:26;s:2:\"26\";i:27;s:2:\"27\";i:28;s:2:\"28\";i:29;s:2:\"29\";i:30;s:2:\"30\";i:31;s:2:\"31\";i:44;s:2:\"44\";i:81;s:2:\"81\";i:82;s:2:\"82\";i:83;s:2:\"83\";i:91;s:2:\"91\";}','a:4:{i:0;s:1:\"5\";i:1;s:1:\"7\";i:2;s:1:\"6\";i:3;s:1:\"3\";}'),('Super Administrator','Super Administrator',10,1,'a:74:{i:7;s:1:\"7\";i:8;s:1:\"8\";i:67;s:2:\"67\";i:68;s:2:\"68\";i:69;s:2:\"69\";i:63;s:2:\"63\";i:70;s:2:\"70\";i:9;s:1:\"9\";i:64;s:2:\"64\";i:58;s:2:\"58\";i:72;s:2:\"72\";i:42;s:2:\"42\";i:25;s:2:\"25\";i:46;s:2:\"46\";i:16;s:2:\"16\";i:78;s:2:\"78\";i:79;s:2:\"79\";i:80;s:2:\"80\";i:74;s:2:\"74\";i:56;s:2:\"56\";i:89;s:2:\"89\";i:84;s:2:\"84\";i:34;s:2:\"34\";i:35;s:2:\"35\";i:36;s:2:\"36\";i:39;s:2:\"39\";i:40;s:2:\"40\";i:85;s:2:\"85\";i:37;s:2:\"37\";i:41;s:2:\"41\";i:88;s:2:\"88\";i:77;s:2:\"77\";i:1;s:1:\"1\";i:3;s:1:\"3\";i:17;s:2:\"17\";i:19;s:2:\"19\";i:4;s:1:\"4\";i:2;s:1:\"2\";i:20;s:2:\"20\";i:10;s:2:\"10\";i:21;s:2:\"21\";i:12;s:2:\"12\";i:65;s:2:\"65\";i:13;s:2:\"13\";i:14;s:2:\"14\";i:15;s:2:\"15\";i:5;s:1:\"5\";i:32;s:2:\"32\";i:43;s:2:\"43\";i:45;s:2:\"45\";i:73;s:2:\"73\";i:66;s:2:\"66\";i:51;s:2:\"51\";i:52;s:2:\"52\";i:53;s:2:\"53\";i:54;s:2:\"54\";i:55;s:2:\"55\";i:87;s:2:\"87\";i:47;s:2:\"47\";i:48;s:2:\"48\";i:49;s:2:\"49\";i:59;s:2:\"59\";i:60;s:2:\"60\";i:26;s:2:\"26\";i:27;s:2:\"27\";i:28;s:2:\"28\";i:29;s:2:\"29\";i:30;s:2:\"30\";i:31;s:2:\"31\";i:44;s:2:\"44\";i:81;s:2:\"81\";i:82;s:2:\"82\";i:83;s:2:\"83\";i:91;s:2:\"91\";}','a:7:{i:0;s:1:\"5\";i:1;s:1:\"7\";i:2;s:1:\"6\";i:3;s:1:\"3\";i:4;s:1:\"1\";i:5;s:1:\"4\";i:6;s:1:\"2\";}');
/*!40000 ALTER TABLE `app_usertype` ENABLE KEYS */;
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
-- Table structure for table `bank_info`
--

DROP TABLE IF EXISTS `bank_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bank_info` (
  `bank_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `baccntype_id` int(10) unsigned NOT NULL,
  `comp_id` int(10) unsigned NOT NULL,
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
  `bank_routing_number` int(10) DEFAULT NULL,
  `bank_company_code` varchar(30) COLLATE latin1_general_ci DEFAULT NULL,
  `bank_ceiling_amount` decimal(10,2) DEFAULT NULL,
  `bank_contact` varchar(225) COLLATE latin1_general_ci DEFAULT NULL,
  PRIMARY KEY (`bank_id`),
  KEY `banklist_id` (`banklist_id`),
  KEY `bank_info_FKIndex2` (`comp_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bank_info`
--

LOCK TABLES `bank_info` WRITE;
/*!40000 ALTER TABLE `bank_info` DISABLE KEYS */;
INSERT INTO `bank_info` VALUES (1,1,1,1,'0181000000','Company XYZ','','Mandaluyong','jimabignay','2012-04-15 23:52:07',NULL,NULL,1,1,'48000',0.00,'Juan Cruz');
/*!40000 ALTER TABLE `bank_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bank_infoemp`
--

DROP TABLE IF EXISTS `bank_infoemp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bank_infoemp` (
  `bankiemp_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `baccntype_id` int(10) unsigned NOT NULL,
  `emp_id` int(10) unsigned NOT NULL,
  `bank_id` int(10) unsigned NOT NULL,
  `bankiemp_acct_no` varchar(30) COLLATE latin1_general_ci DEFAULT NULL,
  `bankiemp_acct_name` varchar(150) COLLATE latin1_general_ci DEFAULT NULL,
  `bankiemp_swift_code` varchar(30) COLLATE latin1_general_ci DEFAULT NULL,
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
  `banklist_addwho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `banklist_addwhen` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `banklist_updatewho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `banklist_updatewhen` datetime DEFAULT NULL,
  `banklist_isactive` tinyint(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`banklist_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bank_list`
--

LOCK TABLES `bank_list` WRITE;
/*!40000 ALTER TABLE `bank_list` DISABLE KEYS */;
INSERT INTO `bank_list` VALUES (1,'BPI','admin','2011-11-03 02:15:15','','0000-00-00 00:00:00',1);
/*!40000 ALTER TABLE `bank_list` ENABLE KEYS */;
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
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bnkaccnt_type`
--

LOCK TABLES `bnkaccnt_type` WRITE;
/*!40000 ALTER TABLE `bnkaccnt_type` DISABLE KEYS */;
INSERT INTO `bnkaccnt_type` VALUES (1,'Saving'),(2,'Current'),(3,'Credit Card'),(4,'Others');
/*!40000 ALTER TABLE `bnkaccnt_type` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `company_info`
--

LOCK TABLES `company_info` WRITE;
/*!40000 ALTER TABLE `company_info` DISABLE KEYS */;
INSERT INTO `company_info` VALUES (1,1,'COMPXYZ','Company XYZ','Testing Data',1504,'(02) 636-0000','xyz@company.com.ph','Juan Dela Cruz','219 978 549 000','0391666959','01-900001251-2','800137810006',50,'jimabignay','2012-04-15 23:48:44',NULL,NULL);
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
INSERT INTO `company_type` VALUES (1,'Private','jayadmin','2011-06-27 00:53:58','','0000-00-00 00:00:00'),(2,'Government','jayadmin','2011-06-27 01:12:21','','0000-00-00 00:00:00'),(3,'Household','jayadmin','2011-06-27 01:12:37','','0000-00-00 00:00:00');
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
INSERT INTO `deduction_type` VALUES (1,'SSS','Social Security Service',10,'admin','2009-10-06 10:13:10','','0000-00-00 00:00:00'),(2,'PHIC','Philippine Health Insurance Corporation ',20,'admin','2009-10-06 10:19:05','','0000-00-00 00:00:00'),(3,'HDMF','Home Development Mutual Fund',30,'admin','2009-10-06 10:19:42','','0000-00-00 00:00:00'),(4,'COLA','COLA',40,'admin','2009-10-06 10:19:57','','0000-00-00 00:00:00'),(5,'TAX','Tax',50,'admin','2009-10-06 10:20:21','','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `deduction_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dependent_info`
--

DROP TABLE IF EXISTS `dependent_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dependent_info` (
  `depnd_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
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
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `emp_201status`
--

LOCK TABLES `emp_201status` WRITE;
/*!40000 ALTER TABLE `emp_201status` DISABLE KEYS */;
INSERT INTO `emp_201status` VALUES (1,'NEW HIRE',10),(2,'RETIREMENT',20),(3,'RETRENCHED',30),(4,'SUSPENDED',40),(5,'TERMINATED',50),(6,'TEMPORARY LAYOFF',60),(7,'REGULAR',70),(8,'RESIGNED',80),(9,'END OF CONTRACT',90);
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
  `ben_amount` float DEFAULT NULL,
  `ben_payperday` float DEFAULT NULL,
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
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `emp_category`
--

LOCK TABLES `emp_category` WRITE;
/*!40000 ALTER TABLE `emp_category` DISABLE KEYS */;
INSERT INTO `emp_category` VALUES (1,'Casual',10,'jay','2009-07-08 09:39:02','','0000-00-00 00:00:00'),(2,'Contractual',20,'jay','2009-07-08 09:40:39','','0000-00-00 00:00:00'),(3,'Probationary',30,'jay','2009-07-08 09:40:54','admin','2011-10-05 20:51:52'),(4,'Full-time',40,'mark','2009-10-27 14:34:27','admin','2011-11-03 10:24:34');
/*!40000 ALTER TABLE `emp_category` ENABLE KEYS */;
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
  `empleave_creadit` decimal(10,2) DEFAULT NULL,
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
  `empleave_id` int(10) unsigned NOT NULL,
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
  `emp_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `taxep_id` int(10) unsigned NOT NULL,
  `ud_id` int(10) unsigned NOT NULL,
  `comp_id` int(10) unsigned NOT NULL,
  `post_id` int(10) unsigned NOT NULL,
  `empcateg_id` int(10) unsigned NOT NULL,
  `emptype_id` int(10) unsigned NOT NULL,
  `pi_id` int(10) unsigned NOT NULL,
  `emp_idnum` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
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
  `emp_stat` int(10) unsigned DEFAULT '1',
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
  `pi_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `zipcode_id` int(10) unsigned NOT NULL,
  `p_id` int(10) unsigned NOT NULL,
  `pi_fname` varchar(60) COLLATE latin1_general_ci DEFAULT NULL,
  `pi_mname` varchar(60) COLLATE latin1_general_ci DEFAULT NULL,
  `pi_lname` varchar(60) COLLATE latin1_general_ci DEFAULT NULL,
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
  `post_code` varchar(10) COLLATE latin1_general_ci DEFAULT NULL,
  `post_name` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `post_desc` text COLLATE latin1_general_ci,
  `post_ord` int(10) unsigned DEFAULT NULL,
  `post_addwho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `post_addwhen` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `post_updatewho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `post_updatewhen` datetime DEFAULT NULL,
  PRIMARY KEY (`post_id`)
) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `emp_position`
--

LOCK TABLES `emp_position` WRITE;
/*!40000 ALTER TABLE `emp_position` DISABLE KEYS */;
INSERT INTO `emp_position` VALUES (1,'GM','General Manager','typically oversees all aspects of operations within a company, organization or factory. All department heads typically report to the GM, and the GM typically reports to the vice president or president of the company.',10,'admin','2011-11-07 09:59:35','admin','2011-11-07 18:27:25'),(2,'HRM','Human Resources Manager','guides and manages the overall provision of Human Resources services, policies, and programs for the entire company.',20,'admin','2011-11-07 09:59:59','admin','2011-11-07 18:17:16'),(3,'ITMNGR','IT Manager','',30,'admin','2011-11-07 10:00:17','','0000-00-00 00:00:00'),(4,'ITSUP','IT Supervisor','',40,'admin','2011-11-07 10:04:07','','0000-00-00 00:00:00'),(5,'MEMBERSHIP','Membership Officer','',50,'admin','2011-11-07 10:04:24','','0000-00-00 00:00:00'),(6,'ITASST','IT Technical Support','',60,'admin','2011-11-07 10:04:42','','0000-00-00 00:00:00'),(7,'FINMNGR','Finance Manager','',70,'admin','2011-11-07 10:05:00','admin','2012-03-08 11:59:24'),(8,'ACCTGMNGR','Accounting Manager','',80,'admin','2011-11-07 10:07:42','','0000-00-00 00:00:00'),(9,'PAYACCT','Payable Accountant','',90,'admin','2011-11-07 10:08:16','','0000-00-00 00:00:00'),(10,'RECACCT','Receivable Accountant','',100,'admin','2011-11-07 10:08:32','','0000-00-00 00:00:00'),(11,'COSTACCT','Cost Accountant','',110,'admin','2011-11-07 10:08:49','','0000-00-00 00:00:00'),(12,'SMM','Sales and Marketing Manager','',120,'admin','2011-11-07 10:18:15','','0000-00-00 00:00:00'),(13,'JRSM','Jr. Sales Manager','',130,'admin','2011-11-07 10:18:40','','0000-00-00 00:00:00'),(14,'RSO','Regional Sales Officer','',140,'admin','2011-11-07 10:18:56','','0000-00-00 00:00:00'),(15,'BEAUTY','Beauty and Wellness Supervisor','',150,'admin','2011-11-07 10:19:12','','0000-00-00 00:00:00'),(16,'PBCOFCR','PBC Officer','',160,'admin','2011-11-07 10:20:50','','0000-00-00 00:00:00'),(17,'GRAPHICS','Graphic Artist','',170,'admin','2011-11-07 10:21:06','','0000-00-00 00:00:00'),(18,'MKTGCOMM','Marketing and Communication Assistant','',180,'admin','2011-11-07 10:21:22','','0000-00-00 00:00:00'),(19,'OPSMNGR','Operations Manager','',190,'admin','2011-11-07 10:21:58','','0000-00-00 00:00:00'),(20,'SHOWROOMSU','Showroom Supervisor','',200,'admin','2011-11-07 10:22:16','','0000-00-00 00:00:00'),(21,'OPSASST','Operations Assistant','',210,'admin','2011-11-07 10:22:35','','0000-00-00 00:00:00'),(22,'CSSUP','Customer Service Supervisor','',220,'admin','2011-11-07 10:22:51','','0000-00-00 00:00:00'),(23,'CSWI','Customer Service Representative- Walk In','',230,'admin','2011-11-07 10:23:12','','0000-00-00 00:00:00'),(24,'CSRET','Customer Service Representative- Returns','',240,'admin','2011-11-07 10:23:30','','0000-00-00 00:00:00'),(25,'CSBC','Customer Service Representative- Business Center','',250,'admin','2011-11-07 10:23:47','','0000-00-00 00:00:00'),(26,'UTILITY','Utility / Messenger','',260,'admin','2011-11-07 10:24:08','','0000-00-00 00:00:00'),(27,'WHMNGR','Warehouse Manager','',270,'admin','2011-11-07 10:24:24','','0000-00-00 00:00:00'),(28,'WHSUP','Warehouse Supervisor','',280,'admin','2011-11-07 10:24:41','','0000-00-00 00:00:00'),(29,'WHSTAFF','Warehouse Assistant','',290,'admin','2011-11-07 10:24:56','','0000-00-00 00:00:00'),(30,'CC','Catalogue Coordinator','Catalogue Coordinator',300,'admin','2011-12-20 07:16:04','','0000-00-00 00:00:00'),(31,'CSO','Customer Service Officer - Cashier','Customer Service Officer - Cashier',310,'admin','2011-12-20 07:16:20','','0000-00-00 00:00:00'),(32,'EPA','Executive / Personal Assistant ','PA',320,'admin','2011-12-20 07:16:33','','0000-00-00 00:00:00'),(33,'PHARMA','Pharmacist','',330,'jimabignay','2012-01-12 04:14:21','admin','2012-01-12 14:18:13'),(34,'SA','Server / System Administrator','',0,'admin','2012-01-27 02:55:39','','0000-00-00 00:00:00');
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
  `emptype_name` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `emptype_ord` int(10) unsigned DEFAULT NULL,
  `emptype_addwho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `emptype_addwhen` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `emptype_updatewho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `emptype_updatewhen` datetime DEFAULT NULL,
  PRIMARY KEY (`emptype_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `emp_type`
--

LOCK TABLES `emp_type` WRITE;
/*!40000 ALTER TABLE `emp_type` DISABLE KEYS */;
INSERT INTO `emp_type` VALUES (1,'Manager',10,'','2009-07-08 08:26:24','','0000-00-00 00:00:00'),(2,'Supervisor',20,'','2009-07-08 08:47:42','','0000-00-00 00:00:00'),(3,'Officer',15,'','2009-07-08 08:48:07','admin','2011-11-03 10:23:02'),(4,'Rank File',40,'','2009-07-08 08:48:26','','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `emp_type` ENABLE KEYS */;
UNLOCK TABLES;

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
  `fr_name` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `fr_hrperday` int(10) unsigned DEFAULT NULL,
  `fr_dayperweek` int(10) unsigned DEFAULT NULL,
  `fr_dayperyear` int(10) unsigned DEFAULT NULL,
  `fr_hrperweek` int(10) unsigned DEFAULT NULL,
  `fr_addwho` varchar(30) COLLATE latin1_general_ci DEFAULT NULL,
  `fr_addwhen` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `fr_updatewho` varchar(30) COLLATE latin1_general_ci DEFAULT NULL,
  `fr_updatewhen` datetime DEFAULT NULL,
  PRIMARY KEY (`fr_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `factor_rate`
--

LOCK TABLES `factor_rate` WRITE;
/*!40000 ALTER TABLE `factor_rate` DISABLE KEYS */;
INSERT INTO `factor_rate` VALUES (1,'Factor A (365 days/year)',9,5,365,44,'','2009-10-06 07:50:25','','0000-00-00 00:00:00'),(2,'Factor B (312 days/year)',8,6,312,48,'','2011-07-25 02:58:02','','0000-00-00 00:00:00'),(3,'Factor C (313 days/year)',8,6,313,48,'','2011-11-17 04:58:38','','0000-00-00 00:00:00');
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
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leave_type`
--

LOCK TABLES `leave_type` WRITE;
/*!40000 ALTER TABLE `leave_type` DISABLE KEYS */;
INSERT INTO `leave_type` VALUES (1,'VL','Vacation Leave','Yes',10.00,'Both','Yes','jayadmin','2011-01-31 16:55:18','jayadmin','2011-02-01 00:59:38'),(2,'SL','Sick Leave','Yes',10.00,'Both','Yes','jayadmin','2011-01-31 16:59:14','jayadmin','2011-02-01 00:59:51'),(3,'EL','Emergency Leave','Yes',10.00,'Both','Yes','jayadmin','2011-01-31 17:00:19','','0000-00-00 00:00:00'),(4,'PL','Paternity Leave','No',10.00,'Male','No','jayadmin','2011-01-31 21:37:17','jayadmin','2011-02-01 05:38:10'),(5,'ML','Maternity Leave','No',30.00,'Female','No','jayadmin','2011-01-31 21:37:50','jayadmin','2011-02-01 05:38:00');
/*!40000 ALTER TABLE `leave_type` ENABLE KEYS */;
UNLOCK TABLES;

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
  `otr_factor` float DEFAULT '0',
  `otr_max` int(10) unsigned DEFAULT '0',
  `otr_addwho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `otr_addwhen` timestamp NULL DEFAULT NULL,
  `otr_updatewho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `otr_updatewhen` datetime DEFAULT NULL,
  PRIMARY KEY (`otr_id`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ot_rates`
--

LOCK TABLES `ot_rates` WRITE;
/*!40000 ALTER TABLE `ot_rates` DISABLE KEYS */;
INSERT INTO `ot_rates` VALUES (1,'Holiday','Holiday Pay','Hour',1,0,'','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(2,'ND_RDF8','ND Rest Day First 8 Hours','Hour',1.43,0,'','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(3,'ND_RDX8','ND Rest Day excess of 8 Hours','Hour',1.859,0,'','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(4,'ND_RHF8','ND Regular Holiday First 8 Hours','Hour',1.2,0,'','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(5,'ND_RHRDF8','ND Regular Holiday Falls on Rest Day First 8 Hours','Hour',2.86,0,'','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(6,'ND_RHRDX8','ND Regular Holiday falls on Rest Day Excess of First 8 Hours','Hour',3.718,0,'','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(7,'ND_RHX8','ND Regular Holiday Excess of First 8 Hours','Hour',2.86,0,'','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(8,'ND_SHF8','ND Special Holiday First 8 Hours','Hour',1.43,0,'','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(9,'ND_SHF8M','ND Special Holiday First 8 Hours - Monthly','Hour',0.43,0,'','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(10,'ND_SHRDF8','ND Special Holiday Falls on Rest Day First 8 Hours ','Hour',1.65,0,'','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(11,'ND_SHRDX8','ND Special Holiday Falls on Rest Day Excess of First 8 Hours','Hour',2.145,0,'','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(12,'ND_SHX8','ND Special Holiday Excess of First 8 Hours','Hour',1.859,0,'','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(13,'NDF8','ND Regular Day First 8 Hours','Hour',0.1,0,'','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(14,'NDX8','ND Regular Day Excess of First 8 Hours','Hour',1.375,0,'','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(15,'OT_RDF8','OT Rest Day First 8 Hours','Hour',1.3,0,'','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(16,'OT_RD88','OT Rest Day Excess of First 8 Hours','Hour',1.69,0,'','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(17,'OT_RHF8','OT Regular Holiday First 8 Hours','Hour',1,0,'','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(18,'OT_RHRDF8','OT Regular Holiday Falls on Rest Day First 8 Hours','Hour',2.6,0,'','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(19,'OT_RHRDX8','OT Regular Holiday Falls on Rest Day Excess of First 8 Hours','Hour',3.38,0,'','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(20,'OT_RHX8','OT Regular Holiday Excess of First 8 Hours','Hour',2.6,0,'','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(21,'OT_SHF8','OT Special Holiday First 8 Hours','Hour',1.3,0,'','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(22,'OT_SHF8M','OT Special Holiday First 8 Hours - Monthly','Hour',0.3,0,'','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(23,'OT_SHRDF8','OT Special Holiday Falls on Rest Day First 8 Hours','Hour',1.5,0,'','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(24,'OT_SHRDX8','OT Special Holiday Falls on Rest Day Excess of First 8 Hours','Hour',1.95,0,'','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(25,'OT_SHX8','OT Special Holiday Excess of 8 Hours','Hour',1.69,0,'','0000-00-00 00:00:00','','0000-00-00 00:00:00'),(26,'ROT','Regular OT','Hour',1.25,0,'','0000-00-00 00:00:00','','0000-00-00 00:00:00');
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
  `otrec_totalhrs` decimal(10,2) DEFAULT NULL,
  `otrec_subtotal` decimal(10,2) DEFAULT NULL,
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
  `ot_addwho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `ot_addwhen` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `ot_updatewho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `ot_updatewhen` datetime DEFAULT NULL,
  PRIMARY KEY (`ot_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ot_tbl`
--

LOCK TABLES `ot_tbl` WRITE;
/*!40000 ALTER TABLE `ot_tbl` DISABLE KEYS */;
INSERT INTO `ot_tbl` VALUES (1,'OT - Table A','1,2,3,4,5,6,7,8,10,11,12,13,14,15,16,17,18,19,20,21,23,24,25,26','','admin','2011-11-10 06:39:40','','0000-00-00 00:00:00'),(2,'OT - Table B','1,2,3,4,5,6,7,9,10,11,12,13,14,15,16,17,18,19,20,22,23,24,25,26','OT - Table B','admin','2011-12-20 07:43:38','','0000-00-00 00:00:00');
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
) ENGINE=MyISAM AUTO_INCREMENT=49 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ot_tr`
--

LOCK TABLES `ot_tr` WRITE;
/*!40000 ALTER TABLE `ot_tr` DISABLE KEYS */;
INSERT INTO `ot_tr` VALUES (1,1,1,NULL,'2012-04-15 23:35:28',NULL,NULL),(2,1,2,NULL,'2012-04-15 23:35:28',NULL,NULL),(3,1,3,NULL,'2012-04-15 23:35:28',NULL,NULL),(4,1,4,NULL,'2012-04-15 23:35:28',NULL,NULL),(5,1,5,NULL,'2012-04-15 23:35:28',NULL,NULL),(6,1,6,NULL,'2012-04-15 23:35:28',NULL,NULL),(7,1,7,NULL,'2012-04-15 23:35:28',NULL,NULL),(8,1,8,NULL,'2012-04-15 23:35:28',NULL,NULL),(9,1,10,NULL,'2012-04-15 23:35:28',NULL,NULL),(10,1,11,NULL,'2012-04-15 23:35:28',NULL,NULL),(11,1,12,NULL,'2012-04-15 23:35:28',NULL,NULL),(12,1,13,NULL,'2012-04-15 23:35:28',NULL,NULL),(13,1,14,NULL,'2012-04-15 23:35:28',NULL,NULL),(14,1,15,NULL,'2012-04-15 23:35:28',NULL,NULL),(15,1,16,NULL,'2012-04-15 23:35:28',NULL,NULL),(16,1,17,NULL,'2012-04-15 23:35:28',NULL,NULL),(17,1,18,NULL,'2012-04-15 23:35:28',NULL,NULL),(18,1,19,NULL,'2012-04-15 23:35:28',NULL,NULL),(19,1,20,NULL,'2012-04-15 23:35:28',NULL,NULL),(20,1,21,NULL,'2012-04-15 23:35:28',NULL,NULL),(21,1,23,NULL,'2012-04-15 23:35:28',NULL,NULL),(22,1,24,NULL,'2012-04-15 23:35:28',NULL,NULL),(23,1,25,NULL,'2012-04-15 23:35:28',NULL,NULL),(24,1,26,NULL,'2012-04-15 23:35:28',NULL,NULL),(25,2,1,NULL,'2012-04-15 23:36:18',NULL,NULL),(26,2,2,NULL,'2012-04-15 23:36:18',NULL,NULL),(27,2,3,NULL,'2012-04-15 23:36:18',NULL,NULL),(28,2,4,NULL,'2012-04-15 23:36:18',NULL,NULL),(29,2,5,NULL,'2012-04-15 23:36:18',NULL,NULL),(30,2,6,NULL,'2012-04-15 23:36:18',NULL,NULL),(31,2,7,NULL,'2012-04-15 23:36:18',NULL,NULL),(32,2,9,NULL,'2012-04-15 23:36:18',NULL,NULL),(33,2,10,NULL,'2012-04-15 23:36:18',NULL,NULL),(34,2,11,NULL,'2012-04-15 23:36:18',NULL,NULL),(35,2,12,NULL,'2012-04-15 23:36:18',NULL,NULL),(36,2,13,NULL,'2012-04-15 23:36:18',NULL,NULL),(37,2,14,NULL,'2012-04-15 23:36:18',NULL,NULL),(38,2,15,NULL,'2012-04-15 23:36:18',NULL,NULL),(39,2,16,NULL,'2012-04-15 23:36:18',NULL,NULL),(40,2,17,NULL,'2012-04-15 23:36:18',NULL,NULL),(41,2,18,NULL,'2012-04-15 23:36:18',NULL,NULL),(42,2,19,NULL,'2012-04-15 23:36:18',NULL,NULL),(43,2,20,NULL,'2012-04-15 23:36:18',NULL,NULL),(44,2,22,NULL,'2012-04-15 23:36:18',NULL,NULL),(45,2,23,NULL,'2012-04-15 23:36:18',NULL,NULL),(46,2,24,NULL,'2012-04-15 23:36:18',NULL,NULL),(47,2,25,NULL,'2012-04-15 23:36:18',NULL,NULL),(48,2,26,NULL,'2012-04-15 23:36:18',NULL,NULL);
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
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
  `pbr_batchno` varchar(4) COLLATE latin1_general_ci DEFAULT NULL,
  `pbr_bank_name` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `pbr_routing_number` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `pbr_company_code` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `pbr_company_accountno` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `pbr_ceiling_amount` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `pbr_credit_date` date DEFAULT NULL,
  `pbr_prepared_by` varchar(225) COLLATE latin1_general_ci DEFAULT NULL,
  `pbr_approved_by` varchar(225) COLLATE latin1_general_ci DEFAULT NULL,
  `pbr_addwho` varchar(100) COLLATE latin1_general_ci DEFAULT NULL,
  `pbr_addwhen` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pbr_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
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
  `ot_id` int(10) unsigned NOT NULL,
  `pc_addwho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `pc_addwhen` timestamp NULL DEFAULT NULL,
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
  `payperiod_period_year` varchar(10) COLLATE latin1_general_ci DEFAULT NULL,
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
  PRIMARY KEY (`payperiod_id`),
  KEY `payroll_pay_period_FKIndex1` (`pps_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payroll_pay_period`
--

LOCK TABLES `payroll_pay_period` WRITE;
/*!40000 ALTER TABLE `payroll_pay_period` DISABLE KEYS */;
INSERT INTO `payroll_pay_period` VALUES (1,1,0,4,'2012',0,'jimabignay','2012-04-15 23:55:06','0000-00-00 00:00:00','','2012-03-26 00:00:00','2012-04-10 23:59:00','2012-04-13 23:59:00','0000-00-00 00:00:00','0000-00-00 00:00:00',0,'','0000-00-00 00:00:00',1,0);
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
  `pps_pri_daymonth` int(10) unsigned NOT NULL,
  `pps_secnd_daymonth` int(10) unsigned NOT NULL,
  `pps_pri_trans_daymonth` int(10) unsigned NOT NULL,
  `pps_secnd_trans_daymonth` int(10) unsigned NOT NULL,
  `pps_trans_datebd` int(10) unsigned NOT NULL,
  `pps_time_zone` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `pps_new_daytrigger_time` int(10) unsigned NOT NULL,
  `pps_max_shifttime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`pps_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payroll_pay_period_sched`
--

LOCK TABLES `payroll_pay_period_sched` WRITE;
/*!40000 ALTER TABLE `payroll_pay_period_sched` DISABLE KEYS */;
INSERT INTO `payroll_pay_period_sched` VALUES (1,3,1,3,'Semi-Monthly','Semi Monthly Payroll',4,0,0,0,0,0,0,'jimabignay','2012-04-15 23:53:40','','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',0,0,0,1,0,26,11,10,25,0,'',0,0);
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
) ENGINE=MyISAM AUTO_INCREMENT=51 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payroll_priority_comp`
--

LOCK TABLES `payroll_priority_comp` WRITE;
/*!40000 ALTER TABLE `payroll_priority_comp` DISABLE KEYS */;
INSERT INTO `payroll_priority_comp` VALUES (1,0,0,1,1,0),(2,0,0,1,2,0),(3,0,0,1,3,0),(4,0,0,1,4,0),(5,0,0,1,5,0),(6,0,0,1,6,0),(7,0,0,1,7,0),(8,0,0,1,8,0),(9,0,0,1,9,0),(10,0,0,1,10,0),(11,0,0,1,11,0),(12,0,0,1,12,0),(13,0,0,1,13,0),(14,0,0,1,14,0),(15,0,0,1,15,0),(16,0,0,1,16,0),(17,0,0,1,17,0),(18,0,0,1,18,0),(19,0,0,1,19,0),(20,0,0,1,20,0),(21,0,0,1,21,0),(22,0,0,1,22,0),(23,0,0,1,23,0),(24,0,0,1,24,0),(25,0,0,1,25,0),(26,0,0,1,26,0),(27,0,0,1,27,0),(28,0,0,1,28,0),(29,0,0,1,29,0),(30,0,0,1,30,0),(31,0,0,1,31,0),(32,0,0,1,32,0),(33,0,0,1,33,0),(34,0,0,1,34,0),(35,0,0,1,35,0),(36,0,0,1,36,0),(37,0,0,1,37,0),(38,0,0,1,38,0),(39,0,0,1,39,0),(40,0,0,1,40,0),(41,0,0,1,41,0),(42,0,0,1,42,0),(43,0,0,1,43,0),(44,0,0,1,44,0),(45,0,0,1,45,0),(46,0,0,1,46,0),(47,0,0,1,47,0),(48,0,0,1,48,0),(49,0,0,1,49,0),(50,0,0,1,50,0);
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
  PRIMARY KEY (`psa_id`)
) ENGINE=MyISAM AUTO_INCREMENT=56 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payroll_ps_account`
--

LOCK TABLES `payroll_ps_account` WRITE;
/*!40000 ALTER TABLE `payroll_ps_account` DISABLE KEYS */;
INSERT INTO `payroll_ps_account` VALUES (1,1,1,4,301,0,'Regular Time',0,'','',0,'jay','2008-11-06 03:35:43','jimabignay','2011-12-13 10:56:50',0,0,0,3),(2,1,1,4,300,0,'Total Deductions',0,'','',0,'jay','2008-11-07 01:46:38','admin','2011-10-21 02:44:59',0,0,0,3),(3,1,1,1,40,0,'Mgt. Participation',0,'','',0,'jay','2008-11-13 01:58:52','admin','2012-01-12 02:48:04',0,0,0,3),(4,1,1,4,315,0,'Total Gross',0,'','',0,'jay','2008-11-13 02:00:39','admin','2012-01-12 02:46:48',0,0,0,3),(5,1,1,4,301,0,'Net Pay',0,'','',0,'jay','2008-11-13 02:03:32','admin','2011-10-21 02:45:12',0,0,0,3),(6,1,1,4,302,0,'Employer Total Contributions',0,'','',0,'jay','2008-11-13 02:04:55','admin','2011-10-21 02:45:22',0,0,0,3),(7,1,1,2,214,0,'SSS',0,'','',0,'jay','2009-01-19 02:54:34','admin','2011-12-06 04:38:15',0,1,4,3),(8,1,1,2,213,0,'W/H TAX',0,'','',0,'jay','2009-01-19 02:57:42','jimabignay','2012-02-29 04:23:53',0,0,4,3),(9,1,1,2,280,0,'SSS Loan',0,'','',1,'jay','2009-03-01 18:10:03','admin','2011-10-26 03:30:10',0,0,5,3),(10,1,2,1,20,0,'Meal Allowance',0,'','',0,'jay','2009-10-12 19:42:44','jimabignay','2011-12-13 10:34:46',0,0,1,3),(11,1,1,2,282,0,'Personal Loan',0,'','',0,'jay','2010-03-30 03:03:00','jimabignay','2012-02-29 04:24:50',0,0,5,3),(12,1,2,1,30,0,'Rice Allowance',0,'','',0,'jay','2010-04-26 08:09:31','jimabignay','2011-12-13 10:35:07',1,1,1,3),(13,1,1,2,281,0,'HDMF Loan',0,'','',1,'jayadmin','2010-08-05 09:17:32','admin','2011-10-26 03:30:18',0,0,5,3),(14,1,1,2,215,0,'PHIC',0,'','',0,'jayadmin','2010-10-28 05:06:52','admin','2011-12-06 04:38:23',0,1,4,3),(15,1,1,2,216,0,'HDMF',0,'','',0,'jayadmin','2010-10-28 05:07:44','admin','2011-12-06 04:38:32',0,1,4,3),(16,1,1,4,303,0,'Overtime',0,'','',0,'admin','2011-10-25 01:45:02','jimabignay','2012-03-08 01:14:28',0,0,0,4),(17,1,1,4,304,0,'Total TA',0,'','',0,'admin','2011-10-25 01:45:46','','0000-00-00 00:00:00',0,0,0,3),(18,1,1,2,205,0,'Overpay',0,'','',0,'admin','2011-10-27 09:14:54','jimabignay','2011-12-22 03:27:58',0,0,1,3),(19,1,1,1,15,0,'Salary Adj.',0,'','',0,'admin','2011-10-27 09:15:33','jimabignay','2012-03-08 01:13:20',1,1,1,4),(24,1,1,1,10,0,'Incentive',0,'','',0,'admin','2011-10-27 09:32:35','jimabignay','2012-03-08 01:12:55',1,0,1,4),(25,1,1,4,305,0,'Other Statutory Income',0,'','',0,'admin','2011-11-02 02:47:16','','0000-00-00 00:00:00',0,0,0,3),(26,1,1,4,306,0,'OtherStat&TaxIncome',0,'','',0,'admin','2011-11-02 02:47:56','jimabignay','2012-03-08 01:15:29',0,0,0,4),(27,1,1,4,307,0,'Statutory Contrib',0,'','',0,'admin','2011-11-02 02:48:49','jimabignay','2012-03-08 01:17:24',0,0,0,2),(28,1,1,4,308,0,'Other Taxable Income',0,'','',0,'admin','2011-11-02 02:49:42','jimabignay','2012-03-08 01:16:23',0,0,0,4),(29,1,1,4,309,0,'Other Taxable Deduction',0,'','',0,'admin','2011-11-02 02:50:05','','0000-00-00 00:00:00',0,0,0,3),(30,1,1,4,310,0,'Taxable Income Gross ',0,'','',0,'admin','2011-11-02 02:50:24','','0000-00-00 00:00:00',0,0,0,3),(31,1,1,4,311,0,'NonTaxable Income',0,'','',0,'admin','2011-11-02 02:52:34','','0000-00-00 00:00:00',0,0,0,3),(32,1,1,4,312,0,'Other Deduction',0,'','',0,'admin','2011-11-02 02:53:50','','0000-00-00 00:00:00',0,0,0,3),(33,1,1,4,313,0,'Other Statutory Deduction',0,'','',0,'admin','2011-11-02 03:19:31','','0000-00-00 00:00:00',0,0,0,3),(34,1,1,4,314,0,'Other Stat & Tax Deduction',0,'','',0,'admin','2011-11-02 03:20:06','','0000-00-00 00:00:00',0,0,0,3),(35,1,2,1,0,0,'Maternity',0,'','',0,'admin','2011-11-03 03:04:56','jimabignay','2011-12-13 10:56:00',0,0,1,3),(36,1,1,1,8,0,'Comm. Allowance',0,'','',0,'admin','2011-11-22 05:39:43','jimabignay','2012-03-08 08:53:10',0,0,1,3),(37,1,2,1,31,0,'Trasfo. Allowance ',0,'','',0,'admin','2011-11-22 05:49:13','jimabignay','2011-12-13 10:55:10',0,0,1,3),(38,1,1,1,33,0,'Uniform Allowance ',0,'','',0,'admin','2011-11-22 05:49:36','','0000-00-00 00:00:00',0,0,1,3),(39,1,1,4,301,0,'COLA',0,'','',0,'admin','2011-12-12 06:01:57','jimabignay','2011-12-13 10:58:07',0,1,0,3),(40,1,2,1,16,0,'EDO cash conversion',0,'','',0,'admin','2011-12-12 09:46:49','jimabignay','2012-03-08 01:10:19',1,0,1,4),(41,1,1,1,5,0,'OT Adj',0,'','',0,'admin','2011-12-12 09:48:15','jimabignay','2012-03-08 01:10:54',1,1,1,4),(42,1,1,1,15,0,'SL Convertion',0,'','',0,'jimabignay','2012-01-12 01:44:51','jimabignay','2012-03-08 01:13:34',1,0,1,4),(43,1,1,1,2,0,'SSS Prem adj',0,'','',0,'admin','2012-01-12 06:44:47','jimabignay','2012-03-08 08:41:02',0,0,1,3),(44,1,1,2,211,0,'SSS Prem adj',0,'','',0,'admin','2012-01-12 06:45:35','admin','2012-01-12 02:46:23',0,0,1,3),(45,1,1,1,3,0,'PH Prem Adj',0,'','',0,'admin','2012-01-12 06:48:59','jimabignay','2012-03-08 08:41:11',0,0,1,3),(46,1,1,2,212,0,'PH Prem Adj',0,'','',0,'admin','2012-01-12 06:49:25','','0000-00-00 00:00:00',0,0,1,3),(47,1,1,1,4,0,'HDMF Prem adj',0,'','',0,'admin','2012-01-12 06:49:48','jimabignay','2012-03-08 08:41:16',0,0,1,3),(48,1,1,2,213,0,'HDMF Prem adj',0,'','',0,'admin','2012-01-12 06:50:59','','0000-00-00 00:00:00',0,0,1,3),(49,1,1,1,14,0,'13th Month adj',0,'','',0,'admin','2012-01-20 07:14:39','jimabignay','2012-02-29 04:27:23',0,0,1,3),(50,1,1,1,17,0,'Tax Refund',0,'','',0,'jimabignay','2012-01-27 02:24:12','jimabignay','2012-03-08 08:53:53',0,0,1,3),(51,1,1,2,207,0,'Tax Payables',0,'','',0,'jimabignay','2012-01-27 02:24:30','','0000-00-00 00:00:00',0,0,1,3),(52,1,1,1,0,0,'Special Incentive',0,'','',0,'jimabignay','2012-01-27 06:21:51','','0000-00-00 00:00:00',0,1,1,3),(53,1,1,1,18,0,'Sal increase Retro',0,'','',0,'admin','2012-02-11 01:29:31','jimabignay','2012-03-08 01:09:55',1,0,1,4),(54,1,1,1,0,0,'Comm. Allow. adjust',0,'','',0,'admin','2012-03-12 07:51:01','','0000-00-00 00:00:00',0,0,1,3),(55,1,1,2,284,0,'Salary Deduction',0,'','',0,'jimabignay','2012-03-28 06:44:33','jimabignay','2012-03-28 02:45:06',0,0,1,3);
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
  `amendemp_id` int(11) unsigned NOT NULL DEFAULT '0',
  `emp_id` int(11) unsigned NOT NULL DEFAULT '0',
  `paystub_id` int(11) unsigned NOT NULL DEFAULT '0',
  `psamend_id` int(11) unsigned NOT NULL DEFAULT '0',
  `amendemp_amount` decimal(11,2) DEFAULT NULL,
  `amendemp_addwho` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `amendemp_addwhen` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
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
-- Table structure for table `salary_info`
--

DROP TABLE IF EXISTS `salary_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `salary_info` (
  `salaryinfo_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
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
  `min_salary` int(10) unsigned DEFAULT NULL,
  `max_salary` int(10) unsigned DEFAULT NULL,
  `min_age` int(10) unsigned DEFAULT NULL,
  `max_age` int(10) unsigned DEFAULT NULL,
  `scr_ee` decimal(10,2) DEFAULT NULL,
  `scr_er` decimal(10,2) DEFAULT NULL,
  `scr_ec` float DEFAULT NULL,
  `scr_pcent` float DEFAULT '0',
  `scr_pcentamnt` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`scr_id`),
  KEY `sc_records_FKIndex1` (`sc_id`)
) ENGINE=MyISAM AUTO_INCREMENT=61 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sc_records`
--

LOCK TABLES `sc_records` WRITE;
/*!40000 ALTER TABLE `sc_records` DISABLE KEYS */;
INSERT INTO `sc_records` VALUES (1,2,2750,3250,0,99,100.00,212.00,10,0,0.00),(2,2,2250,2750,0,99,83.30,176.70,10,0,0.00),(3,2,1750,2256,0,99,66.70,141.30,10,0,0.00),(4,2,1250,1750,0,99,50.00,106.00,10,0,0.00),(5,2,1000,1250,0,99,33.30,70.70,10,0,0.00),(6,2,3250,3750,0,99,116.70,247.30,10,0,0.00),(7,2,3750,4250,0,99,133.30,282.70,10,0,0.00),(8,2,4250,4750,0,99,150.00,318.00,10,0,0.00),(9,2,4750,5250,0,99,166.70,353.30,10,0,0.00),(10,2,5250,5750,0,99,183.30,388.70,10,0,0.00),(11,1,0,5000,0,99,50.00,50.00,1,1.25,0.00),(12,1,5000,6000,0,99,62.50,62.50,2,1.25,0.00),(13,1,6000,7000,0,99,75.00,75.00,3,1.25,0.00),(14,1,7000,8000,0,99,87.50,87.50,4,1.25,0.00),(15,1,8000,9000,0,99,100.00,100.00,5,1.25,0.00),(16,1,9000,10000,0,99,112.50,112.50,6,1.25,0.00),(17,1,10000,11000,0,99,125.00,125.00,7,1.25,0.00),(18,1,11000,12000,0,99,137.50,137.50,8,1.25,0.00),(19,1,12000,13000,0,99,150.00,150.00,9,1.25,0.00),(20,1,13000,14000,0,99,162.50,162.50,10,1.25,0.00),(21,1,14000,15000,0,99,175.00,175.00,11,1.25,0.00),(22,1,15000,16000,0,99,187.50,187.50,12,1.25,0.00),(23,1,16000,17000,0,99,200.00,200.00,13,1.25,0.00),(24,1,17000,18000,0,99,212.50,212.50,14,1.25,0.00),(25,2,5750,6250,0,99,200.00,424.00,10,0,0.00),(26,2,6250,6750,0,99,216.70,459.30,10,0,0.00),(27,2,6750,7250,0,99,233.30,494.70,10,0,0.00),(28,2,7250,7750,0,99,250.00,530.00,10,0,0.00),(29,2,7750,8250,0,99,266.70,565.30,10,0,0.00),(30,2,8250,8750,0,99,283.30,600.70,10,0,0.00),(31,2,8750,9250,0,99,300.00,636.00,10,0,0.00),(32,2,9250,9750,0,99,316.70,671.30,10,0,0.00),(33,2,9750,10250,0,99,333.30,706.70,10,0,0.00),(34,2,10250,10750,0,99,350.00,742.00,10,0,0.00),(35,2,10750,11250,0,99,366.70,777.30,10,0,0.00),(36,2,11250,11750,0,99,383.30,812.70,10,0,0.00),(37,2,11750,12250,0,99,400.00,848.00,10,0,0.00),(38,2,12250,12750,0,99,416.70,883.30,10,0,0.00),(39,2,12750,13250,0,99,433.30,918.70,10,0,0.00),(40,2,13250,13750,0,99,450.00,954.00,10,0,0.00),(41,2,13750,14250,0,99,466.70,989.30,10,0,0.00),(42,2,14250,14750,0,99,483.30,1024.70,10,0,0.00),(43,2,14750,0,0,99,500.00,1060.00,30,0,0.00),(44,3,0,1501,0,99,0.00,0.00,0,2,0.00),(45,3,1501,5001,0,99,0.00,0.00,0,2,0.00),(46,3,5001,999999,0,99,100.00,100.00,0,0,0.00),(47,1,18000,19000,0,99,225.00,225.00,15,1.25,0.00),(48,1,19000,20000,0,99,237.50,237.50,16,1.25,0.00),(49,1,20000,21000,0,99,250.00,250.00,17,1.25,0.00),(50,1,21000,22000,0,99,262.50,262.50,18,1.25,0.00),(51,1,22000,23000,0,99,275.00,275.00,19,1.25,0.00),(52,1,23000,24000,0,99,287.50,287.50,20,1.25,0.00),(53,1,24000,25000,0,99,300.00,300.00,21,1.25,0.00),(54,1,25000,26000,0,99,312.50,312.50,22,1.25,0.00),(55,1,26000,27000,0,99,325.00,325.00,23,1.25,0.00),(56,1,27000,28000,0,99,337.50,337.50,24,1.25,0.00),(57,1,28000,29000,0,99,350.00,350.00,25,1.25,0.00),(58,1,29000,30000,0,99,362.50,362.50,26,1.25,0.00),(59,1,30000,0,0,99,375.00,375.00,27,1.25,0.00),(60,4,0,0,0,0,0.00,0.00,0,0,22.00);
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
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `statutory_contribution`
--

LOCK TABLES `statutory_contribution` WRITE;
/*!40000 ALTER TABLE `statutory_contribution` DISABLE KEYS */;
INSERT INTO `statutory_contribution` VALUES (1,2,'PHIC','Philippine Health Insurance Company','2009-10-01','admin','2009-10-01 18:45:47','','0000-00-00 00:00:00',1),(2,1,'SSS','Social Security Service','2007-01-01','admin','2009-10-01 18:31:07','','0000-00-00 00:00:00',1),(3,3,'HDMF','Home Development Mutual Fund','2005-01-01','admin','2011-07-18 09:02:27','','0000-00-00 00:00:00',1),(4,4,'COLA','Cost-of-Living Allowance','2011-05-26','','2011-11-17 03:31:43','','0000-00-00 00:00:00',1);
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
  `emp_tarec_nohrday` decimal(10,2) DEFAULT NULL,
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
INSERT INTO `ta_tbl` VALUES (1,'Authorized absence - no pay',2,'jayadmin','2011-02-02 16:12:58','jimabignay','2011-12-20 17:54:51'),(3,'Late',1,'jayadmin','2011-02-02 16:13:23','','0000-00-00 00:00:00'),(4,'Undertime',1,'jayadmin','2011-02-02 16:13:49','','0000-00-00 00:00:00'),(5,'No of Days',2,'jayadmin','2011-12-20 08:22:16','','0000-00-00 00:00:00');
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
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tax_excep`
--

LOCK TABLES `tax_excep` WRITE;
/*!40000 ALTER TABLE `tax_excep` DISABLE KEYS */;
INSERT INTO `tax_excep` VALUES (1,'Z','Zero',10,'jayadmin','2010-11-15 18:26:45','','0000-00-00 00:00:00'),(3,'S','Single(S)',20,'jayadmin','2010-11-15 18:26:45','','0000-00-00 00:00:00'),(4,'S1','Single1 (S1)',30,'jayadmin','2010-11-15 18:26:45','','0000-00-00 00:00:00'),(5,'S2','Single2 (S2)',40,'jayadmin','2010-11-15 18:26:45','','0000-00-00 00:00:00'),(6,'S3','Single3 (S3)',50,'jayadmin','2010-11-15 18:26:45','','0000-00-00 00:00:00'),(7,'S4','Single4 (S4)',60,'jayadmin','2010-11-15 18:26:45','','0000-00-00 00:00:00'),(8,'ME','Married (ME)',70,'jayadmin','2010-11-15 18:26:45','','0000-00-00 00:00:00'),(9,'ME1','Married1 (ME1)',80,'jayadmin','2010-11-15 18:27:10','','0000-00-00 00:00:00'),(10,'ME2','Married2 (ME2)',90,'jayadmin','2010-11-15 18:27:37','','0000-00-00 00:00:00'),(11,'ME3','Married3 (ME3)',100,'jayadmin','2010-11-15 18:27:49','','0000-00-00 00:00:00'),(12,'ME4','Married4 (ME4)',110,'jayadmin','2010-11-15 18:27:59','','0000-00-00 00:00:00');
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
) ENGINE=MyISAM AUTO_INCREMENT=208 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tax_table`
--

LOCK TABLES `tax_table` WRITE;
/*!40000 ALTER TABLE `tax_table` DISABLE KEYS */;
INSERT INTO `tax_table` VALUES (2,1,1,33,0,5,'jayadmin','2009-10-13 22:29:43','','0000-00-00 00:00:00','1',0.00,0,0,0,0,0,0,0),(3,1,1,99,33,10,'jayadmin','2009-10-13 22:33:59','','0000-00-00 00:00:00','1',1.65,0,0,0,0,0,0,0),(4,1,1,231,99,15,'jayadmin','2009-10-13 22:34:47','','0000-00-00 00:00:00','1',8.25,0,0,0,0,0,0,0),(5,1,1,462,231,20,'jayadmin','2009-10-13 22:35:27','','0000-00-00 00:00:00','1',28.05,0,0,0,0,0,0,0),(6,1,1,825,462,25,'jayadmin','2009-10-13 22:36:29','','0000-00-00 00:00:00','1',74.26,0,0,0,0,0,0,0),(7,1,1,1650,825,30,'jayadmin','2009-10-13 22:37:16','','0000-00-00 00:00:00','1',165.02,0,0,0,0,0,0,0),(8,1,1,165,1,0,'jayadmin','2009-10-13 22:38:29','','0000-00-00 00:00:00','3',0.00,0,0,0,0,0,0,0),(9,1,1,198,165,5,'jayadmin','2009-10-13 22:39:06','','0000-00-00 00:00:00','3',0.00,0,0,0,0,0,0,0),(10,1,1,264,198,10,'jayadmin','2009-10-13 22:40:02','','0000-00-00 00:00:00','3',1.65,0,0,0,0,0,0,0),(11,1,1,396,264,15,'jayadmin','2009-10-13 22:40:49','','0000-00-00 00:00:00','3',8.25,0,0,0,0,0,0,0),(12,1,3,2083,1,0,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','3',0.00,0,0,0,0,0,0,0),(13,1,3,2500,2083,5,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','3',0.00,0,0,0,0,0,0,0),(14,1,3,3333,2500,10,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','3',20.83,0,0,0,0,0,0,0),(15,1,3,5000,3333,15,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','3',104.17,0,0,0,0,0,0,0),(16,1,3,7917,5000,20,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','3',354.17,0,0,0,0,0,0,0),(17,1,3,12500,7917,25,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','3',937.50,0,0,0,0,0,0,0),(18,1,3,22917,12500,30,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','3',2083.33,0,0,0,0,0,0,0),(19,1,3,0,22917,32,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','3',5208.33,0,0,0,0,0,0,0),(20,1,3,0,1,0,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','1',0.00,0,0,0,0,0,0,0),(21,1,4,4167,1,0,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','3',0.00,0,0,0,0,0,0,0),(22,1,4,5000,4167,5,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','3',0.00,0,0,0,0,0,0,0),(23,1,4,6667,5000,10,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','3',41.67,0,0,0,0,0,0,0),(24,1,4,10000,6667,15,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','3',208.33,0,0,0,0,0,0,0),(25,1,4,15833,10000,20,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','3',708.33,0,0,0,0,0,0,0),(26,1,4,25000,15833,25,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','3',1875.00,0,0,0,0,0,0,0),(27,1,4,45833,25000,30,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','3',4166.67,0,0,0,0,0,0,0),(28,1,4,0,45833,32,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','3',10416.67,0,0,0,0,0,0,0),(29,1,4,6250,1,0,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',0.00,0,0,0,0,0,0,0),(30,1,4,7083,6250,5,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',0.00,0,0,0,0,0,0,0),(31,1,4,8750,7083,10,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',41.67,0,0,0,0,0,0,0),(32,1,4,12083,8750,15,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',208.33,0,0,0,0,0,0,0),(33,1,4,17917,12083,20,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',708.33,0,0,0,0,0,0,0),(34,1,4,27083,17917,25,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',1875.00,0,0,0,0,0,0,0),(35,1,4,47917,27083,30,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',4166.67,0,0,0,0,0,0,0),(36,1,4,0,47917,32,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','9',10416.67,0,0,0,0,0,0,0),(37,1,4,0,1,0,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','1',0.00,0,0,0,0,0,0,0),(38,1,4,833,0,5,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','1',0.00,0,0,0,0,0,0,0),(39,1,4,2500,833,10,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','1',41.67,0,0,0,0,0,0,0),(40,1,4,5833,2500,15,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','1',208.33,0,0,0,0,0,0,0),(41,1,4,11667,5833,20,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','1',708.33,0,0,0,0,0,0,0),(42,1,4,20833,11667,25,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','1',1875.00,0,0,0,0,0,0,0),(43,1,4,41667,20833,30,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','1',4166.67,0,0,0,0,0,0,0),(44,1,4,0,41667,32,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','1',10416.67,0,0,0,0,0,0,0),(45,1,3,417,0,5,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','1',0.00,0,0,0,0,0,0,0),(46,1,3,1250,417,10,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','1',20.83,0,0,0,0,0,0,0),(47,1,3,2917,1250,15,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','1',104.17,0,0,0,0,0,0,0),(48,1,3,5833,2917,20,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','1',354.17,0,0,0,0,0,0,0),(49,1,3,10417,5833,25,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','1',937.50,0,0,0,0,0,0,0),(50,1,3,20833,10417,30,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','1',2083.33,0,0,0,0,0,0,0),(51,1,3,0,20833,32,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','1',5208.33,0,0,0,0,0,0,0),(52,1,3,3125,1,0,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',0.00,0,0,0,0,0,0,0),(53,1,3,3542,3125,5,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',0.00,0,0,0,0,0,0,0),(54,1,3,4375,3542,10,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',20.83,0,0,0,0,0,0,0),(55,1,3,6042,4375,15,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',104.17,0,0,0,0,0,0,0),(56,1,3,8958,6042,20,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',354.17,0,0,0,0,0,0,0),(57,1,3,13542,8958,25,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',937.50,0,0,0,0,0,0,0),(58,1,3,23958,13542,30,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',2083.33,0,0,0,0,0,0,0),(59,1,3,0,23958,32,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',5208.33,0,0,0,0,0,0,0),(60,1,3,4167,1,0,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',0.00,0,0,0,0,0,0,0),(61,1,3,4583,4167,5,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',0.00,0,0,0,0,0,0,0),(62,1,3,5417,4583,10,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',20.83,0,0,0,0,0,0,0),(63,1,3,7083,5417,15,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',104.17,0,0,0,0,0,0,0),(64,1,3,10000,7083,20,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',354.17,0,0,0,0,0,0,0),(65,1,3,14583,10000,25,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',937.50,0,0,0,0,0,0,0),(66,1,3,25000,14583,30,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',2083.33,0,0,0,0,0,0,0),(67,1,3,0,25000,32,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',5208.33,0,0,0,0,0,0,0),(68,1,3,5208,1,0,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',0.00,0,0,0,0,0,0,0),(69,1,3,5625,5208,5,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',0.00,0,0,0,0,0,0,0),(70,1,3,6458,5625,10,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',20.83,0,0,0,0,0,0,0),(71,1,3,8125,6458,15,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',104.17,0,0,0,0,0,0,0),(72,1,3,11042,8125,20,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',354.17,0,0,0,0,0,0,0),(73,1,3,15625,11042,25,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',937.50,0,0,0,0,0,0,0),(74,1,3,26042,15625,30,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',2083.33,0,0,0,0,0,0,0),(75,1,3,0,26042,32,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',5208.33,0,0,0,0,0,0,0),(76,1,3,6250,1,0,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',0.00,0,0,0,0,0,0,0),(77,1,3,6667,6250,5,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',0.00,0,0,0,0,0,0,0),(78,1,3,7500,6667,10,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',20.83,0,0,0,0,0,0,0),(79,1,3,9167,7500,15,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',104.17,0,0,0,0,0,0,0),(80,1,3,12083,9167,20,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',354.17,0,0,0,0,0,0,0),(81,1,3,16667,12083,25,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',937.50,0,0,0,0,0,0,0),(82,1,3,27083,16667,30,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',2083.33,0,0,0,0,0,0,0),(83,1,3,0,27083,32,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',5208.33,0,0,0,0,0,0,0),(84,1,4,8333,1,0,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',0.00,0,0,0,0,0,0,0),(85,1,4,9167,8333,5,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',0.00,0,0,0,0,0,0,0),(86,1,4,10833,9167,10,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',41.67,0,0,0,0,0,0,0),(87,1,4,14167,10833,15,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',208.33,0,0,0,0,0,0,0),(88,1,4,20000,14167,20,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',708.33,0,0,0,0,0,0,0),(89,1,4,29167,20000,25,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',1875.00,0,0,0,0,0,0,0),(90,1,4,50000,29167,30,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','10',4166.67,0,0,0,0,0,0,0),(91,1,4,0,50000,32,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','10',10416.67,0,0,0,0,0,0,0),(92,1,4,10417,1,0,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',0.00,0,0,0,0,0,0,0),(93,1,4,11250,10417,5,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',0.00,0,0,0,0,0,0,0),(94,1,4,12917,11250,10,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',41.67,0,0,0,0,0,0,0),(95,1,4,16250,12917,15,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',208.33,0,0,0,0,0,0,0),(96,1,4,22083,16250,20,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',708.33,0,0,0,0,0,0,0),(97,1,4,31250,22083,25,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',1875.00,0,0,0,0,0,0,0),(98,1,4,52083,31250,30,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',4166.67,0,0,0,0,0,0,0),(99,1,4,0,52083,32,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',10416.67,0,0,0,0,0,0,0),(100,1,4,12500,1,0,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',0.00,0,0,0,0,0,0,0),(101,1,4,13333,12500,5,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',0.00,0,0,0,0,0,0,0),(102,1,4,15000,13333,10,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',41.67,0,0,0,0,0,0,0),(103,1,4,18333,15000,15,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',208.33,0,0,0,0,0,0,0),(104,1,4,24167,18333,20,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',708.33,0,0,0,0,0,0,0),(105,1,4,33333,24167,25,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',1875.00,0,0,0,0,0,0,0),(106,1,4,54167,33333,30,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',4166.67,0,0,0,0,0,0,0),(107,1,4,0,54167,32,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',10416.67,0,0,0,0,0,0,0),(108,1,2,0,1,0,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','1',0.00,0,0,0,0,0,0,0),(109,1,2,192,0,5,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','1',0.00,0,0,0,0,0,0,0),(110,1,2,577,192,10,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','1',9.62,0,0,0,0,0,0,0),(111,1,2,1346,577,15,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','1',48.08,0,0,0,0,0,0,0),(112,1,2,2692,1346,20,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','1',163.46,0,0,0,0,0,0,0),(113,1,2,4808,2692,25,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','1',432.69,0,0,0,0,0,0,0),(114,1,2,9615,4808,30,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','1',961.54,0,0,0,0,0,0,0),(115,1,2,0,9615,32,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','1',2403.85,0,0,0,0,0,0,0),(116,1,2,962,1,0,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','3',0.00,0,0,0,0,0,0,0),(117,1,2,1154,962,5,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','3',0.00,0,0,0,0,0,0,0),(118,1,2,1538,1154,10,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','3',9.62,0,0,0,0,0,0,0),(119,1,2,2308,1538,15,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','3',48.08,0,0,0,0,0,0,0),(120,1,2,3654,2308,20,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','3',163.46,0,0,0,0,0,0,0),(121,1,2,5769,3654,25,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','3',432.69,0,0,0,0,0,0,0),(122,1,2,10577,5769,30,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','3',961.54,0,0,0,0,0,0,0),(123,1,2,0,10577,32,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','3',2403.85,0,0,0,0,0,0,0),(124,1,2,1442,1,0,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',0.00,0,0,0,0,0,0,0),(125,1,2,1635,1442,5,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',0.00,0,0,0,0,0,0,0),(126,1,2,2019,1635,10,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',9.62,0,0,0,0,0,0,0),(127,1,2,2788,2019,15,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',48.08,0,0,0,0,0,0,0),(128,1,2,4135,2788,20,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',163.46,0,0,0,0,0,0,0),(129,1,2,6250,4135,25,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',432.69,0,0,0,0,0,0,0),(130,1,2,11058,6250,30,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',961.54,0,0,0,0,0,0,0),(131,1,2,0,11058,32,'jayadmin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',2403.85,0,0,0,0,0,0,0),(132,1,1,627,396,20,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','3',28.05,0,0,0,0,0,0,0),(133,1,1,990,627,25,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','3',74.26,0,0,0,0,0,0,0),(134,1,1,1815,990,30,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','3',165.02,0,0,0,0,0,0,0),(135,1,1,0,1815,32,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','3',412.54,0,0,0,0,0,0,0),(136,1,1,248,1,0,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',0.00,0,0,0,0,0,0,0),(137,1,1,281,248,5,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',0.00,0,0,0,0,0,0,0),(138,1,1,347,281,10,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',1.65,0,0,0,0,0,0,0),(139,1,1,479,347,15,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',8.25,0,0,0,0,0,0,0),(140,1,1,710,479,20,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',28.05,0,0,0,0,0,0,0),(141,1,1,1073,710,25,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',74.26,0,0,0,0,0,0,0),(142,1,1,1898,1073,30,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',165.02,0,0,0,0,0,0,0),(143,1,1,0,1898,32,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',412.54,0,0,0,0,0,0,0),(144,1,1,330,1,0,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',0.00,0,0,0,0,0,0,0),(145,1,1,363,330,5,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',0.00,0,0,0,0,0,0,0),(146,1,1,429,363,10,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',1.65,0,0,0,0,0,0,0),(147,1,1,561,429,15,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',8.25,0,0,0,0,0,0,0),(148,1,1,792,561,20,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',28.05,0,0,0,0,0,0,0),(149,1,1,1155,792,25,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',74.26,0,0,0,0,0,0,0),(150,1,1,1980,1155,30,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',165.02,0,0,0,0,0,0,0),(151,1,1,0,1980,32,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',412.54,0,0,0,0,0,0,0),(152,1,1,413,1,0,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',0.00,0,0,0,0,0,0,0),(153,1,1,446,413,5,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',0.00,0,0,0,0,0,0,0),(154,1,1,512,446,10,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',1.65,0,0,0,0,0,0,0),(155,1,1,644,512,15,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',8.25,0,0,0,0,0,0,0),(156,1,1,875,644,20,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',28.05,0,0,0,0,0,0,0),(157,1,1,1238,875,25,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',74.26,0,0,0,0,0,0,0),(158,1,1,2063,1238,30,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',165.02,0,0,0,0,0,0,0),(159,1,1,0,2063,32,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',412.54,0,0,0,0,0,0,0),(160,1,1,495,1,0,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',0.00,0,0,0,0,0,0,0),(161,1,1,528,495,5,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',0.00,0,0,0,0,0,0,0),(162,1,1,594,528,10,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',1.65,0,0,0,0,0,0,0),(163,1,1,726,594,15,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',8.25,0,0,0,0,0,0,0),(164,1,1,957,726,20,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',28.05,0,0,0,0,0,0,0),(165,1,1,1320,957,25,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',74.26,0,0,0,0,0,0,0),(166,1,1,2145,1320,30,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',165.02,0,0,0,0,0,0,0),(167,1,1,0,2145,32,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',412.54,0,0,0,0,0,0,0),(168,1,2,1923,1,0,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',0.00,0,0,0,0,0,0,0),(169,1,2,2115,1923,5,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',0.00,0,0,0,0,0,0,0),(170,1,2,2500,2115,10,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',9.62,0,0,0,0,0,0,0),(171,1,2,3269,2500,15,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',48.08,0,0,0,0,0,0,0),(172,1,2,4615,3269,20,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',163.46,0,0,0,0,0,0,0),(173,1,2,6731,4615,25,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',432.69,0,0,0,0,0,0,0),(174,1,2,11538,6731,30,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',961.54,0,0,0,0,0,0,0),(175,1,2,0,11538,32,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',2403.85,0,0,0,0,0,0,0),(176,1,2,2404,1,0,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',0.00,0,0,0,0,0,0,0),(177,1,2,2596,2404,5,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',0.00,0,0,0,0,0,0,0),(178,1,2,2981,2596,10,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',9.62,0,0,0,0,0,0,0),(179,1,2,3750,2981,15,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',48.08,0,0,0,0,0,0,0),(180,1,2,5096,3750,20,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',163.46,0,0,0,0,0,0,0),(181,1,2,7212,5096,25,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',432.69,0,0,0,0,0,0,0),(182,1,2,12019,7212,30,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',961.54,0,0,0,0,0,0,0),(183,1,2,0,12019,32,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','6',2403.85,0,0,0,0,0,0,0),(184,1,2,2885,1,0,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',0.00,0,0,0,0,0,0,0),(185,1,2,3077,2885,5,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',0.00,0,0,0,0,0,0,0),(186,1,2,3462,3077,10,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',9.62,0,0,0,0,0,0,0),(187,1,2,4231,3462,15,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',48.08,0,0,0,0,0,0,0),(188,1,2,5577,4231,20,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',163.46,0,0,0,0,0,0,0),(189,1,2,7692,5577,25,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',432.69,0,0,0,0,0,0,0),(190,1,2,12500,7692,30,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',961.54,0,0,0,0,0,0,0),(191,1,2,0,12500,32,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','7',2403.85,0,0,0,0,0,0,0),(192,1,4,0,47917,32,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','4',10416.67,0,0,0,0,0,0,0),(193,1,4,50000,29167,30,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',4166.67,0,0,0,0,0,0,0),(194,1,4,0,50000,32,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','5',10416.67,0,0,0,0,0,0,0),(197,1,1,0,1,0,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','1',0.00,0,0,0,0,0,0,0),(199,1,1,0,1650,32,'admin','0000-00-00 00:00:00','','0000-00-00 00:00:00','1',412.54,0,0,0,0,0,0,0),(200,1,5,10000,0,5,'admin','2012-03-26 00:20:08','','0000-00-00 00:00:00','1',0.00,30000,50000,50000,50000,25000,4,2400),(201,1,5,30000,10000,10,'admin','2012-03-26 00:21:12','','0000-00-00 00:00:00','1',500.00,30000,50000,50000,50000,25000,4,2400),(202,1,5,70000,30000,15,'admin','2012-03-26 00:21:49','','0000-00-00 00:00:00','1',2500.00,30000,50000,50000,50000,25000,4,2400),(203,1,5,140000,70000,20,'admin','2012-03-26 00:22:27','','0000-00-00 00:00:00','1',8500.00,30000,50000,50000,50000,25000,4,2400),(204,1,5,250000,140000,25,'admin','2012-03-26 00:23:12','','0000-00-00 00:00:00','1',22500.00,30000,50000,50000,50000,25000,4,2400),(205,1,5,500000,250000,30,'admin','2012-03-26 00:23:53','','0000-00-00 00:00:00','1',50000.00,30000,50000,50000,50000,25000,4,2400),(206,1,5,0,500000,32,'admin','2012-03-26 00:24:37','','0000-00-00 00:00:00','1',125000.00,30000,50000,50000,50000,25000,4,2400),(207,1,6,0,0,32,'admin','2012-03-26 21:30:56',NULL,NULL,'1',0.00,0,0,0,0,0,0,0);
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
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-04-16  7:55:22
