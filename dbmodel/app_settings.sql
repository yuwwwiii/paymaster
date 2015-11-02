/*
SQLyog Enterprise - MySQL GUI v6.0 Beta 4 
Host - 5.5.16 : Database - orangeipay_itochu
*********************************************************************
Server version : 5.5.16
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

/*Table structure for table `app_settings` */

DROP TABLE IF EXISTS `app_settings`;

CREATE TABLE `app_settings` (
  `set_id` int(11) NOT NULL AUTO_INCREMENT,
  `comp_id` int(11) NOT NULL,
  `set_name` varchar(255) NOT NULL,
  `set_decimal_places` int(11) NOT NULL DEFAULT '5',
  `set_order` int(11) DEFAULT '1',
  `set_stat_type` tinyint(1) DEFAULT '1' COMMENT '1=Gross, 0=Basic',
  PRIMARY KEY (`set_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

/*Data for the table `app_settings` */

insert  into `app_settings`(`set_id`,`comp_id`,`set_name`,`set_decimal_places`,`set_order`,`set_stat_type`) values (1,1,'General Decimal Settings',5,10,1),(2,1,'Final Decimal Settings',2,20,1),(3,1,'TAX',1,1,1),(4,1,'SSS',2,0,1),(5,1,'PHIC',5,0,1),(6,1,'HDMF',3,0,1),(7,1,'TA Import Form',0,0,1),(8,1,'Payslip FORM',0,0,1),(9,1,'Location as Company',0,0,0);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
