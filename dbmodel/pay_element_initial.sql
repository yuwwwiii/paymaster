/*
SQLyog Enterprise - MySQL GUI v6.0 Beta 4 
Host - 5.5.16 : Database - orangeipay_db
*********************************************************************
Server version : 5.5.16
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

/*Table structure for table `payroll_ps_account` */

DROP TABLE IF EXISTS `payroll_ps_account`;

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
) ENGINE=MyISAM AUTO_INCREMENT=65 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci ROW_FORMAT=DYNAMIC;

/*Data for the table `payroll_ps_account` */

insert  into `payroll_ps_account`(`psa_id`,`comp_id`,`psa_status`,`psa_type`,`psa_order`,`psa_priority`,`psa_name`,`psa_accrual`,`psa_debit_accnt`,`psa_credit_accnt`,`psa_isloan`,`psa_addwho`,`psa_addwhen`,`psa_updatedwho`,`psa_updatedwhen`,`psa_tax`,`psa_statutory`,`psa_clsfication`,`psa_procode`) values (1,1,1,4,301,0,'Basic Pay',0,'','',0,'jay','2008-11-06 11:35:43','jimabignay','2011-12-13 10:56:50',0,0,0,3),(2,1,1,4,300,0,'Total Deductions',0,'','',0,'jay','2008-11-07 09:46:38','admin','2011-10-21 02:44:59',0,0,0,3),(4,1,1,4,315,0,'Total Gross',0,'','',0,'jay','2008-11-13 10:00:39','admin','2012-01-12 02:46:48',0,0,0,3),(5,1,1,4,301,0,'Net Pay',0,'','',0,'jay','2008-11-13 10:03:32','admin','2011-10-21 02:45:12',0,0,0,3),(6,1,1,4,302,0,'Employer Total Contributions',0,'','',0,'jay','2008-11-13 10:04:55','admin','2011-10-21 02:45:22',0,0,0,3),(7,1,1,2,214,0,'SSS',0,'','',0,'jay','2009-01-19 10:54:34','admin','2011-12-06 04:38:15',0,1,4,3),(8,1,1,2,213,0,'W/H TAX',0,'','',0,'jay','2009-01-19 10:57:42','jimabignay','2012-02-29 04:23:53',0,0,4,3),(9,1,1,2,280,0,'SSS Loan',0,'','',1,'jay','2009-03-02 02:10:03','admin','2011-10-26 03:30:10',0,0,5,3),(11,1,1,2,282,0,'Personal Loan',0,'','',0,'jay','2010-03-30 11:03:00','jimabignay','2012-02-29 04:24:50',0,0,5,3),(13,1,1,2,281,0,'HDMF Loan',0,'','',1,'jayadmin','2010-08-05 17:17:32','admin','2011-10-26 03:30:18',0,0,5,3),(14,1,1,2,215,0,'PHIC',0,'','',0,'jayadmin','2010-10-28 13:06:52','admin','2011-12-06 04:38:23',0,1,4,3),(15,1,1,2,216,0,'HDMF',0,'','',0,'jayadmin','2010-10-28 13:07:44','admin','2011-12-06 04:38:32',0,1,4,3),(16,1,1,4,303,0,'Overtime',0,'','',0,'admin','2011-10-25 09:45:02','jimabignay','2012-03-08 01:14:28',0,0,0,4),(17,1,1,4,304,0,'Total TA',0,'','',0,'admin','2011-10-25 09:45:46','','0000-00-00 00:00:00',0,0,0,3),(25,1,1,4,305,0,'Other Statutory Income',0,'','',0,'admin','2011-11-02 10:47:16','','0000-00-00 00:00:00',0,0,0,3),(26,1,1,4,306,0,'OtherStat&TaxIncome',0,'','',0,'admin','2011-11-02 10:47:56','jimabignay','2012-03-08 01:15:29',0,0,0,4),(27,1,1,4,307,0,'Statutory Contrib',0,'','',0,'admin','2011-11-02 10:48:49','jimabignay','2012-03-08 01:17:24',0,0,0,2),(28,1,1,4,308,0,'Other Taxable Income',0,'','',0,'admin','2011-11-02 10:49:42','jimabignay','2012-03-08 01:16:23',0,0,0,4),(29,1,1,4,309,0,'Other Taxable Deduction',0,'','',0,'admin','2011-11-02 10:50:05','','0000-00-00 00:00:00',0,0,0,3),(30,1,1,4,310,0,'Taxable Income Gross ',0,'','',0,'admin','2011-11-02 10:50:24','','0000-00-00 00:00:00',0,0,0,3),(31,1,1,4,311,0,'NonTaxable Income',0,'','',0,'admin','2011-11-02 10:52:34','','0000-00-00 00:00:00',0,0,0,3),(32,1,1,4,312,0,'Other Deduction',0,'','',0,'admin','2011-11-02 10:53:50','','0000-00-00 00:00:00',0,0,0,3),(33,1,1,4,313,0,'Other Statutory Deduction',0,'','',0,'admin','2011-11-02 11:19:31','','0000-00-00 00:00:00',0,0,0,3),(34,1,1,4,314,0,'Other Stat & Tax Deduction',0,'','',0,'admin','2011-11-02 11:20:06','','0000-00-00 00:00:00',0,0,0,3),(39,1,1,4,301,0,'COLA',0,'','',0,'admin','2011-12-12 14:01:57','jimabignay','2011-12-13 10:58:07',0,1,0,3),(64,1,1,2,300,0,'Company Loan',0,'','',0,'jimabignay','2012-06-21 12:26:55','jimabignay','2012-06-21 12:27:35',0,0,5,3);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
