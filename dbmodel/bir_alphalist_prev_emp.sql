/*
SQLyog Enterprise - MySQL GUI v5.22
Host - 5.5.16 : Database - orangeipay_tsi
*********************************************************************
Server version : 5.5.16
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

/*Table structure for table `bir_alphalist_prev_emp` */

DROP TABLE IF EXISTS `bir_alphalist_prev_emp`;

CREATE TABLE `bir_alphalist_prev_emp` (
  `bir_alphalist_prev_emp_id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `emp_id` int(11) NOT NULL,
  `bir_alphalist_year` varchar(10) NOT NULL,
  `taxable_basic` decimal(10,0) NOT NULL,
  `taxable_other_ben` decimal(10,0) NOT NULL,
  `taxable_compensation` decimal(10,0) NOT NULL,
  `nt_other_ben` decimal(10,0) NOT NULL,
  `nt_deminimis` decimal(10,0) NOT NULL,
  `nt_statutories` decimal(10,0) NOT NULL,
  `nt_compensation` decimal(10,0) NOT NULL,
  PRIMARY KEY (`bir_alphalist_prev_emp_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `bir_alphalist_prev_emp` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
