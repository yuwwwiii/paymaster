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

/*Table structure for table `cf_detail` */
CREATE TABLE IF NOT EXISTS `cf_detail` (
  `cfdetail_id` int(11) NOT NULL AUTO_INCREMENT,
  `paystub_id` int(11) DEFAULT NULL,
  `emp_id` int(11) DEFAULT NULL,
  `cfhead_id` int(11) NOT NULL,
  `cfdetail_rec` varchar(150) DEFAULT NULL,
  `cfdetail_addwho` varchar(150) DEFAULT NULL,
  `cfdetail_addwhen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cfdetail_updatewho` varchar(150) DEFAULT NULL,
  `cfdetail_updatewhen` datetime DEFAULT NULL,
  PRIMARY KEY (`cfdetail_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `cf_head` */
CREATE TABLE IF NOT EXISTS `cf_head` (
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*jim(20121004)*/
/* update by IR Salvador - use SP alter_column */
/*ALTER TABLE `emp_leave_rec` add column `payperiod_id` int (10)  NOT NULL  after `empleave_id`, add column `emp_id` int (10)  NOT NULL  after `payperiod_id`, add column `uptadet_id` int (10)  NOT NULL  after `emp_id`;*/
call alter_column('emp_leave_rec','payperiod_id',"ALTER TABLE `emp_leave_rec` add column `payperiod_id` int (10)  NOT NULL  after `empleave_id`, add column `emp_id` int (10)  NOT NULL  after `payperiod_id`, add column `uptadet_id` int (10)  NOT NULL  after `emp_id`");

/*jim(20121009)*/
/* update by IR Salvador - use SP alter_column */
/*ALTER TABLE `cf_detail` add column `payperiod_id` int (11)  NOT NULL  after `paystub_id`,change `paystub_id` `paystub_id` int (11)  NOT NULL , change `emp_id` `emp_id` int (11)  NOT NULL, add column `uptadet_id` int (11)  NOT NULL  after `cfhead_id`;*/
call alter_column('cf_detail','payperiod_id',"ALTER TABLE `cf_detail` add column `payperiod_id` int (11)  NOT NULL  after `paystub_id`,change `paystub_id` `paystub_id` int (11)  NOT NULL , change `emp_id` `emp_id` int (11)  NOT NULL, add column `uptadet_id` int (11)  NOT NULL  after `cfhead_id`");

/* update by IR Salvador - use SP alter_column */
/*ALTER TABLE `emp_leave_rec` add column `paystub_id` int (10)  NULL  after `emp_leav_rec_id`;*/
call alter_column('emp_leave_rec','paystub_id',"ALTER TABLE `emp_leave_rec` add column `paystub_id` int (10)  NULL  after `emp_leav_rec_id`");

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
