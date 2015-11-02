/*
SQLyog Enterprise - MySQL GUI v6.0 Beta 4 
Host - 5.5.16 : Database - orangeipay_tacoma
*********************************************************************
Server version : 5.5.16
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

/*Table structure for table `sc_records` */

DROP TABLE IF EXISTS `sc_records`;

CREATE TABLE `sc_records` (
  `scr_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sc_id` int(10) unsigned NOT NULL,
  `min_salary` decimal(12,2) unsigned DEFAULT NULL,
  `max_salary` decimal(12,2) unsigned DEFAULT NULL,
  `min_age` int(10) unsigned DEFAULT NULL,
  `max_age` int(10) unsigned DEFAULT NULL,
  `scr_ee` decimal(12,2) DEFAULT NULL,
  `scr_er` decimal(12,2) DEFAULT NULL,
  `scr_ec` float DEFAULT NULL,
  `scr_pcent` float DEFAULT '0',
  `scr_pcentamnt` decimal(12,2) DEFAULT '0.00',
  PRIMARY KEY (`scr_id`),
  KEY `sc_records_FKIndex1` (`sc_id`)
) ENGINE=MyISAM AUTO_INCREMENT=90 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci ROW_FORMAT=DYNAMIC;

/*Data for the table `sc_records` */

insert  into `sc_records`(`scr_id`,`sc_id`,`min_salary`,`max_salary`,`min_age`,`max_age`,`scr_ee`,`scr_er`,`scr_ec`,`scr_pcent`,`scr_pcentamnt`) values (1,2,'2750.00','3249.99',0,99,'100.00','212.00',10,0,'0.00'),(2,2,'2250.00','2749.99',0,99,'83.30','176.70',10,0,'0.00'),(3,2,'1750.00','2249.99',0,99,'66.70','141.30',10,0,'0.00'),(4,2,'1250.00','1749.99',0,99,'50.00','106.00',10,0,'0.00'),(5,2,'1000.00','1249.99',0,99,'33.30','70.70',10,0,'0.00'),(6,2,'3250.00','3749.99',0,99,'116.70','247.30',10,0,'0.00'),(7,2,'3750.00','4249.99',0,99,'133.30','282.70',10,0,'0.00'),(8,2,'4250.00','4749.99',0,99,'150.00','318.00',10,0,'0.00'),(9,2,'4750.00','5249.99',0,99,'166.70','353.30',10,0,'0.00'),(10,2,'5250.00','5749.99',0,99,'183.30','388.70',10,0,'0.00'),(11,1,'0.00','4999.99',0,99,'50.00','50.00',1,1.25,'0.00'),(12,1,'5000.00','5999.99',0,99,'62.50','62.50',2,1.25,'0.00'),(13,1,'6000.00','6999.99',0,99,'75.00','75.00',3,1.25,'0.00'),(14,1,'7000.00','7999.99',0,99,'87.50','87.50',4,1.25,'0.00'),(15,1,'8000.00','8999.99',0,99,'100.00','100.00',5,1.25,'0.00'),(16,1,'9000.00','9999.99',0,99,'112.50','112.50',6,1.25,'0.00'),(17,1,'10000.00','10999.99',0,99,'125.00','125.00',7,1.25,'0.00'),(18,1,'11000.00','11999.99',0,99,'137.50','137.50',8,1.25,'0.00'),(19,1,'12000.00','12999.99',0,99,'150.00','150.00',9,1.25,'0.00'),(20,1,'13000.00','13999.99',0,99,'162.50','162.50',10,1.25,'0.00'),(21,1,'14000.00','14999.99',0,99,'175.00','175.00',11,1.25,'0.00'),(22,1,'15000.00','15999.99',0,99,'187.50','187.50',12,1.25,'0.00'),(23,1,'16000.00','16999.99',0,99,'200.00','200.00',13,1.25,'0.00'),(24,1,'17000.00','17999.99',0,99,'212.50','212.50',14,1.25,'0.00'),(25,2,'5750.00','6249.99',0,99,'200.00','424.00',10,0,'0.00'),(26,2,'6250.00','6749.99',0,99,'216.70','459.30',10,0,'0.00'),(27,2,'6750.00','7249.99',0,99,'233.30','494.70',10,0,'0.00'),(28,2,'7250.00','7749.99',0,99,'250.00','530.00',10,0,'0.00'),(29,2,'7750.00','8249.99',0,99,'266.70','565.30',10,0,'0.00'),(30,2,'8250.00','8749.99',0,99,'283.30','600.70',10,0,'0.00'),(31,2,'8750.00','9249.99',0,99,'300.00','636.00',10,0,'0.00'),(32,2,'9250.00','9749.99',0,99,'316.70','671.30',10,0,'0.00'),(33,2,'9750.00','10249.99',0,99,'333.30','706.70',10,0,'0.00'),(34,2,'10250.00','10749.99',0,99,'350.00','742.00',10,0,'0.00'),(35,2,'10750.00','11249.99',0,99,'366.70','777.30',10,0,'0.00'),(36,2,'11250.00','11749.99',0,99,'383.30','812.70',10,0,'0.00'),(37,2,'11750.00','12249.99',0,99,'400.00','848.00',10,0,'0.00'),(38,2,'12250.00','12749.99',0,99,'416.70','883.30',10,0,'0.00'),(39,2,'12750.00','13249.99',0,99,'433.30','918.70',10,0,'0.00'),(40,2,'13250.00','13749.99',0,99,'450.00','954.00',10,0,'0.00'),(41,2,'13750.00','14249.99',0,99,'466.70','989.30',10,0,'0.00'),(42,2,'14250.00','14749.99',0,99,'483.30','1024.70',10,0,'0.00'),(43,2,'14750.00','9999999.99',0,99,'500.00','1060.00',30,0,'0.00'),(44,3,'0.00','1501.00',0,99,'0.00','0.00',0,2,'0.00'),(45,3,'1501.00','5001.00',0,99,'100.00','100.00',0,0,'0.00'),(46,3,'5001.00','999999.00',0,99,'100.00','100.00',0,0,'0.00'),(47,1,'18000.00','18999.99',0,99,'225.00','225.00',15,1.25,'0.00'),(48,1,'19000.00','19999.99',0,99,'237.50','237.50',16,1.25,'0.00'),(49,1,'20000.00','20999.99',0,99,'250.00','250.00',17,1.25,'0.00'),(50,1,'21000.00','21999.99',0,99,'262.50','262.50',18,1.25,'0.00'),(51,1,'22000.00','22999.99',0,99,'275.00','275.00',19,1.25,'0.00'),(52,1,'23000.00','23999.99',0,99,'287.50','287.50',20,1.25,'0.00'),(53,1,'24000.00','24999.99',0,99,'300.00','300.00',21,1.25,'0.00'),(54,1,'25000.00','25999.99',0,99,'312.50','312.50',22,1.25,'0.00'),(55,1,'26000.00','26999.99',0,99,'325.00','325.00',23,1.25,'0.00'),(56,1,'27000.00','27999.99',0,99,'337.50','337.50',24,1.25,'0.00'),(57,1,'28000.00','28999.99',0,99,'350.00','350.00',25,1.25,'0.00'),(58,1,'29000.00','29999.99',0,99,'362.50','362.50',26,1.25,'0.00'),(59,1,'30000.00','9999999.99',0,99,'375.00','375.00',27,1.25,'0.00'),(61,5,'1.00','7999.99',1,99,'87.50','87.50',1,0,'0.00'),(62,5,'8000.00','8999.99',1,99,'100.00','100.00',2,0,'0.00'),(63,5,'9000.00','9999.99',1,99,'112.50','112.50',3,0,'0.00'),(64,5,'10000.00','10999.99',1,99,'125.00','125.00',4,0,'0.00'),(65,5,'11000.00','11999.99',1,99,'137.50','137.50',5,0,'0.00'),(66,5,'12000.00','12999.99',1,99,'150.00','150.00',6,0,'0.00'),(67,5,'13000.00','13999.99',1,99,'162.50','162.50',7,0,'0.00'),(68,5,'14000.00','14999.99',1,99,'175.00','175.00',8,0,'0.00'),(69,5,'15000.00','15999.99',1,99,'187.50','187.50',9,0,'0.00'),(70,5,'16000.00','16999.99',1,99,'200.00','200.00',10,0,'0.00'),(71,5,'17000.00','17999.99',1,99,'212.50','212.50',11,0,'0.00'),(72,5,'18000.00','18999.99',1,99,'225.00','225.00',12,0,'0.00'),(73,5,'19000.00','19999.99',1,99,'237.50','237.50',13,0,'0.00'),(74,5,'20000.00','20999.99',1,99,'250.00','250.00',14,0,'0.00'),(75,5,'21000.00','21999.99',1,99,'262.50','262.50',15,0,'0.00'),(76,5,'22000.00','22999.99',1,99,'275.00','275.00',16,0,'0.00'),(77,5,'23000.00','23999.99',1,99,'287.50','287.50',17,0,'0.00'),(78,5,'24000.00','24999.99',1,99,'300.00','300.00',18,0,'0.00'),(79,5,'25000.00','25999.99',1,99,'312.50','312.50',19,0,'0.00'),(80,5,'26000.00','26999.99',1,99,'325.00','325.00',20,0,'0.00'),(81,5,'27000.00','27999.99',1,99,'337.50','337.50',21,0,'0.00'),(82,5,'28000.00','28999.99',1,99,'350.00','350.00',22,0,'0.00'),(83,5,'29000.00','29999.99',1,99,'362.50','362.50',23,0,'0.00'),(84,5,'30000.00','30999.99',1,99,'375.00','375.00',24,0,'0.00'),(85,5,'31000.00','31999.99',1,99,'387.50','387.50',25,0,'0.00'),(86,5,'32000.00','32999.99',1,99,'400.00','400.00',26,0,'0.00'),(87,5,'33000.00','33999.99',1,99,'412.50','412.50',27,0,'0.00'),(88,5,'34000.00','34999.99',1,99,'425.00','425.00',28,0,'0.00'),(89,5,'35000.00','999999.99',1,99,'437.50','437.50',29,0,'0.00');

/*Table structure for table `statutory_contribution` */

DROP TABLE IF EXISTS `statutory_contribution`;

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
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci ROW_FORMAT=DYNAMIC;

/*Data for the table `statutory_contribution` */

insert  into `statutory_contribution`(`sc_id`,`dec_id`,`sc_code`,`sc_desc`,`sc_effectivedate`,`sc_addwho`,`sc_addwhen`,`sc_updatewho`,`sc_updatewhen`,`sc_stat`) values (1,2,'PHIC','Philippine Health Insurance Company','2009-10-01','admin','2009-10-02 02:45:47','','0000-00-00 00:00:00',1),(2,1,'SSS','Social Security Service','2007-01-01','admin','2009-10-02 02:31:07','','0000-00-00 00:00:00',1),(3,3,'HDMF','Home Development Mutual Fund','2005-01-01','admin','2011-07-18 17:02:27','','0000-00-00 00:00:00',1),(5,2,'PHIC 2013','Philippine Health Insurance Company','2013-01-01',NULL,'2013-01-02 16:06:39',NULL,NULL,1);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
