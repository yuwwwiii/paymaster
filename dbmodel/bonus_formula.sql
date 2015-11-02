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

/*Table structure for table `bonus_formula` */

DROP TABLE IF EXISTS `bonus_formula`;

CREATE TABLE `bonus_formula` (
  `bonus_id` int(11) NOT NULL AUTO_INCREMENT,
  `bonus_code` varchar(50) DEFAULT NULL,
  `bonus_Desc` varchar(225) DEFAULT NULL,
  PRIMARY KEY (`bonus_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

/*Data for the table `bonus_formula` */

insert  into `bonus_formula`(`bonus_id`,`bonus_code`,`bonus_Desc`) values (1,'BCBSwithLD','Base on Current Basic Salary (w/ Leave Deduction)'),(2,'BARS','Base on Actual Received Salary (if w/ basic salary increase and w/ Leave Deduction)'),(3,'BCBSnoLD','Base on Current Basic Salary (no Leave deduction)'),(4,'BCBSxP1.25','Base on Current Basic Salary multiply by percentage (1.25)'),(5,'IPE','Included Pay Element ( Part of Salary but non-taxable)'),(6,'FAS','Base on Current Basic Salary (no Leave deduction) FAS'),(7,'FEAP','Base on Current Basic Salary (no Leave deduction) FEAP');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
