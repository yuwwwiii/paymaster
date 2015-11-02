/*
SQLyog Enterprise - MySQL GUI v6.0 Beta 4 
Host - 5.5.16 : Database - orangeipay_feap
*********************************************************************
Server version : 5.5.16
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

/*Table structure for table `payroll_ps_ytdamend_detail` */

DROP TABLE IF EXISTS `payroll_ps_ytdamend_detail`;

CREATE TABLE `payroll_ps_ytdamend_detail` (
  `ytdamendd_id` int(11) NOT NULL AUTO_INCREMENT,
  `emp_id` int(11) NOT NULL,
  `paystub_id` int(11) NOT NULL,
  `psytdamendh_id` int(11) NOT NULL,
  `ytdamendd_amount` decimal(15,5) DEFAULT NULL,
  `ytdamendd_addwho` varchar(100) DEFAULT NULL,
  `ytdamendd_addwhen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ytdamendd_updatewho` varchar(100) DEFAULT NULL,
  `ytdamendd_updatewhen` datetime DEFAULT NULL,
  PRIMARY KEY (`ytdamendd_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `payroll_ps_ytdamend_detail` */

/*Table structure for table `payroll_ps_ytdamend_head` */

DROP TABLE IF EXISTS `payroll_ps_ytdamend_head`;

CREATE TABLE `payroll_ps_ytdamend_head` (
  `psytdamendh_id` int(11) NOT NULL AUTO_INCREMENT,
  `psa_id` int(11) DEFAULT NULL,
  `psytdamendh_name` varchar(250) DEFAULT NULL,
  `psytdamendh_status` tinyint(2) DEFAULT NULL,
  `psytdamendh_effect_date` date DEFAULT NULL,
  `psytdamendh_addwho` varchar(100) DEFAULT NULL,
  `psytdamendh_addwhen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `psytdamendh_updatewho` varchar(100) DEFAULT NULL,
  `psytdamendh_updatewhen` datetime DEFAULT NULL,
  PRIMARY KEY (`psytdamendh_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `payroll_ps_ytdamend_head` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
