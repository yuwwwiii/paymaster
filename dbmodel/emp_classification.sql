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

/*Table structure for table `emp_classification` */

DROP TABLE IF EXISTS `emp_classification`;

CREATE TABLE `emp_classification` (
  `empclass_id` int(11) NOT NULL AUTO_INCREMENT,
  `empclass_name` varchar(50) DEFAULT NULL,
  `empclass_ord` int(10) DEFAULT NULL,
  `empclass_addwho` varchar(50) DEFAULT NULL,
  `empclass_addwhen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `empclass_updatewho` varchar(50) DEFAULT NULL,
  `empclass_updatewhen` datetime DEFAULT NULL,
  PRIMARY KEY (`empclass_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Data for the table `emp_classification` */

insert  into `emp_classification`(`empclass_id`,`empclass_name`,`empclass_ord`,`empclass_addwho`,`empclass_addwhen`,`empclass_updatewho`,`empclass_updatewhen`) values (1,'Manager',10,NULL,'2012-06-18 17:07:27',NULL,NULL),(2,'Supervisor',20,NULL,'2012-06-18 17:07:40',NULL,NULL),(3,'Rank & File',30,NULL,'2012-06-18 17:07:49',NULL,NULL),(5,'Officer',40,NULL,'2012-06-18 17:09:06',NULL,NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
