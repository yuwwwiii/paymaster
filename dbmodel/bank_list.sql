/*
SQLyog Enterprise - MySQL GUI v6.0 Beta 4 
Host - 5.5.16 : Database - falp_payroll_db
*********************************************************************
Server version : 5.5.16
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

/*Table structure for table `bank_list` */

DROP TABLE IF EXISTS `bank_list`;

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

/*Data for the table `bank_list` */

insert  into `bank_list`(`banklist_id`,`banklist_name`,`banklist_desc`,`banklist_addwho`,`banklist_addwhen`,`banklist_updatewho`,`banklist_updatewhen`,`banklist_isactive`) values (1,'BPI','Bank of the Philippine Islands','admin','2011-11-03 10:15:15','','0000-00-00 00:00:00',1),(2,'BDO','Banco de Oro','admin','2012-06-19 13:05:04',NULL,NULL,1),(3,'RCBC','RCBC Commercial Bank','admin','2012-06-19 13:05:06',NULL,NULL,1),(4,'Standard Chartered','Standard Chartered','admin','2012-08-16 10:36:28',NULL,NULL,1),(5,'Metrobank','Metrobank','admin','2012-08-16 10:41:31',NULL,NULL,1),(6,'Union Bank','Union Bank','admin','2012-08-16 10:45:18',NULL,NULL,1);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
