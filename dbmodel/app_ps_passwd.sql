/*
SQLyog Enterprise - MySQL GUI v6.0 Beta 4 
Host - 5.5.16 : Database - orangeipay_tsi
*********************************************************************
Server version : 5.5.16
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

/*Table structure for table `app_ps_passwd` */

DROP TABLE IF EXISTS `app_ps_passwd`;

CREATE TABLE `app_ps_passwd` (
  `ps_passwd_id` int(11) NOT NULL AUTO_INCREMENT,
  `emp_id` int(11) NOT NULL,
  `ps_passwd_password` varchar(100) NOT NULL,
  PRIMARY KEY (`ps_passwd_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
